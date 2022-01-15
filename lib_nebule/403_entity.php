<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Entity.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID d'une entité ou 'new' ;
 *
 * L'ID d'une entité est forcément un texte en hexadécimal.
 *
 * Si une erreur survient lors de la lecture de l'entité ou lors de la création, assigne l'ID 0.
 * ------------------------------------------------------------------------------------------
 */
class Entity extends Node implements nodeInterface
{
    const ENTITY_MAX_SIZE = 16000;
    const ENTITY_PASSWORD_SALT_SIZE = 128;
    const ENTITY_TYPE = 'application/x-pem-file';
    const ENTITY_PUBLIC_HEADER = '-----BEGIN PUBLIC KEY-----';
    const ENTITY_PRIVATE_HEADER = '-----BEGIN ENCRYPTED PRIVATE KEY-----';

    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullname',
        '_cacheProperty',
        '_cacheProperties',
        '_cacheMarkProtected',
        '_idProtected',
        '_idUnprotected',
        '_idProtectedKey',
        '_idUnprotectedKey',
        '_markProtectedChecked',
        '_code',
        '_haveCode',
        '_usedUpdate',
        '_publicKey',
        '_privateKeyID',
        '_privateKey',
        '_newPrivateKey',
        '_privateKeyPassword',
        '_privateKeyPasswordSalt',
        '_issetPrivateKeyPassword',
        '_faceCache',
    );

    private $_publicKey = '';
    private $_privateKeyID = '0';
    private $_privateKey = '';
    private $_newPrivateKey = false;
    private $_privateKeyPassword = '';
    private $_privateKeyPasswordSalt = '';
    private $_issetPrivateKeyPassword = false;
    private $_faceCache = array();

    /**
     * Specific part of constructor for an entity.
     * @return void
     */
    protected function _localConstruct(): void
    {
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        if ($this->_isNew)
            $this->_createNewEntity();
        elseif ($this->_id != '0')
            $this->_loadEntity($this->_id);
    }

    // Chargement d'une entité existante.
    private function _loadEntity(string $id): void
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $id, Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

        if (!$this->_io->checkObjectPresent($id)
            || !$this->_io->checkLinkPresent($id)
        ) {
            $this->_id = '0';
            return;
        }

        $this->_metrology->addLog('Load entity ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

        // Trouve la clé publique.
        $this->_findPublicKey();
    }

    /**
     * Création d'une nouvelle entité.
     * La création est légèrement différente de la création d'un objet parce que les liens de l'entité ne peuvent pas encore être vérifiés.
     * Une entité par nature ne peut pas être dissimulée.
     *
     * @return void
     */
    private function _createNewEntity(): void
    {
        $this->_metrology->addLog(__METHOD__, Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

        // Vérifie que l'on puisse créer une entité.
        if ($this->_configuration->getOptionAsBoolean('permitWrite')
            && $this->_configuration->getOptionAsBoolean('permitWriteObject')
            && $this->_configuration->getOptionAsBoolean('permitWriteLink')
            && $this->_configuration->getOptionAsBoolean('permitWriteEntity')
            && ($this->_nebuleInstance->getCurrentEntityUnlocked()
                || $this->_configuration->getOptionAsBoolean('permitPublicCreateEntity')
            )
        ) {
            $this->_metrology->addLog('Create entity ' . $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm'), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

            // Génère une biclé cryptographique.
            $this->_privateKeyPassword = $this->_crypto->getRandom(32, Crypto::RANDOM_STRONG);
            $newPkey = $this->_crypto->newAsymmetricKeys($this->_privateKeyPassword);
            if (sizeof($newPkey) != 0) {
                // Extraction des infos.
                $this->_publicKey = $newPkey['public'];
                $this->_id = $this->_crypto->hash($this->_publicKey);
                $this->_metrology->addLog('Generated entity ' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
                $this->_privateKeyPasswordSalt = '';
                $this->_privateKey = $newPkey['private'];
                $this->_privateKeyID = $this->_crypto->hash($this->_privateKey);
                $this->_issetPrivateKeyPassword = true;
                $this->_newPrivateKey = true;

                // Écriture de la clé publique.
                $this->write();
                // La clé privée n'est pas écrite. Son mot de passe doit être changé avant son écriture.

                // Définition de la date.
                $date = date(DATE_ATOM);

                // Création lien 1.
                $source = $this->_id;
                $target = $this->_crypto->hash($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_HASH);
                $link = '_' . $this->_id . '_' . $date . '_l_' . $source . '_' . $target . '_' . $meta;
                $this->_createNewEntityWriteLink($link, $source, $target, $meta);

                // Création lien 2.
                $source = $this->_id;
                $target = $this->_crypto->hash(self::ENTITY_TYPE);
                $meta = $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
                $link = '_' . $this->_id . '_' . $date . '_l_' . $source . '_' . $target . '_' . $meta;
                $this->_createNewEntityWriteLink($link, $source, $target, $meta);

                // TODO effacement sécurisé...
                unset($newPkey);
            } else {
                $this->_metrology->addLog('Create entity error on generation', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
                $this->_id = '0';
            }
        } else {
            $this->_metrology->addLog('Create entity error no authorized', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
            $this->_id = '0';
        }
    }

    // Ecrit le lien pour les objets concernés.
    // Utilisé pour la création d'une nouvelle entité, càd dont la clé publique n'est pas encore reconnue.
    private function _createNewEntityWriteLink(string $link, string $source, string $target, string $meta): void
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

        // Signe le lien.
        $signe = $this->signLink($link);
        if ($signe === false)
            return;
        $signedLink = $signe . '.' . $this->_configuration->getOptionAsString('cryptoHashAlgorithm') . $link;

        // Écrit le lien pour l'objet de l'entité signataire.
        if ($this->_configuration->getOptionUntyped('NEBULE_DEFAULT_PERMIT_ADD_LINK_TO_SIGNER'))
            $this->_io->writeLink($this->_id, $signedLink);

        // Écrit le lien pour l'objet source.
        $this->_io->writeLink($source, $signedLink);

        // Écrit le lien pour l'objet cible.
        $this->_io->writeLink($target, $signedLink);

        // Écrit le lien pour l'objet méta.
        $this->_io->writeLink($meta, $signedLink);
    }


    /**
     * Vérifier que c'est en hexa, et que c'est une entité.
     * @return boolean
     */
    private function _verifyEntity(): bool
    {
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé privée
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false) {
            $t = true;
        }
        // Vérifie si le contenu contient un entête de clé publique
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);

        // TODO Faire une vérifications plus complète...

        $this->_typeVerified = $t;
        return $t;
    }

    private $_typeVerified = false;

    public function getKeyType(): string
    {
        if ($this->_id == '0') {
            return '';
        }
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_ENTITE_TYPE, 'all');
    }

    public function getTypeVerify(): bool
    {
        $this->_verifyEntity();
        return $this->_typeVerified;
    }

    public function getIsPublicKey(): bool
    {
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);
        return $t;
    }

    public function getIsPrivateKey(): bool
    {
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false) {
            $t = true;
        }
        unset($objHead);
        return $t;
    }


    // Retrouve et retourne la clé publique.
    public function getPublicKeyID(): string
    {
        return $this->_id;
    }

    public function getPublicKey(): string
    {
        if ($this->_id == '0'
            || $this->_publicKey == ''
        ) {
            return '';
        }
        return $this->_publicKey;
    }

    // Retrouve la clé publique.
    private function _findPublicKey(): void
    {
        $this->_publicKey = $this->_io->objectRead($this->_id, self::ENTITY_MAX_SIZE);
    }


    // Retourne l'identifiant de la clé privée.
    public function getPrivateKeyID(): string
    {
        // Vérifie la présence d'un ID de clé privée. La recherche au besoin.
        if (!isset($this->_privateKeyID)
            || !$this->_privateKeyID != '0'
        ) {
            $this->_findPrivateKeyID();
        }
        // Retourne l'ID.
        return $this->_privateKeyID;
    }

    // Retrouve l'identifiant de la clé privée.
    private function _findPrivateKeyID(): bool
    {
        // Vérifie la présence d'un ID de clé privée.
        if (isset($this->_privateKeyID)
            && $this->_privateKeyID != '0'
        ) {
            return true;
        }
        // Extrait les liens f vers la clé publique.
        $list = $this->readLinksFilterFull_disabled($this->_id, '', 'f', '', $this->_id, '0');
        if (sizeof($list) == 0) {
            return true;
        }
        // Boucle de recherche d'une clé privée.
        foreach ($list as $link) {
            $hashSource = $link->getHashSource();
            // Vérifie le lien et la présence de l'objet source.
            if ($link->getHashSigner() == $this->_id
                && $link->getAction() == 'f'
                && $link->getHashTarget() == $this->_id
                && $link->getHashMeta() == '0'
                && $this->_io->checkObjectPresent($hashSource)
            ) {
                // Extrait le contenu de l'objet source. @todo remplacer par Object::getContent ...
                $line = $this->_io->objectRead($hashSource, self::ENTITY_MAX_SIZE);
                // Vérifie si le contenu contient un entête de clé privée
                if (strstr($line, self::ENTITY_PRIVATE_HEADER) !== false) {
                    // Mémorise l'ID de la clé privée.
                    $this->_privateKeyID = $hashSource;
                }
            }
        }
        // Vérifie qu'une clé privée a été trouvée.
        if (isset($this->_privateKeyID)
            && $this->_privateKeyID != '0'
        ) {
            return true;
        }
        return false;
    }

    // Retrouve la clé privée.
    private function _findPrivateKey(): bool
    {
        // Vérifie la présence d'une clé privée.
        if (isset($this->_privateKey)
            && $this->_privateKey != ''
        ) {
            return true;
        }
        // Vérifie la présence d'un ID de clé privée. La recherche au besoin.
        if (!isset($this->_privateKeyID)
            || !$this->_privateKeyID != '0'
        ) {
            $this->_findPrivateKeyID();
        }
        // Extrait le contenu de l'objet.
        $this->_privateKey = $this->_io->objectRead($this->_privateKeyID, self::ENTITY_MAX_SIZE);
        return true;
        // A faire... vérifier que c'est bien une clé privée _pour_ cette clé publique.
    }

    // Définit le mot de passe de la clé privée.
    public function setPrivateKeyPassword(string $passwd): bool
    {
        $this->_findPrivateKey();
        if (is_string($this->_privateKey)
            && !$this->_crypto->checkPrivateKeyPassword($this->_privateKey, $passwd)
        )
            return false;
        $this->_privateKeyPasswordSalt = $this->_crypto->getRandom(self::ENTITY_PASSWORD_SALT_SIZE, Crypto::RANDOM_STRONG);
        // TODO le chiffrement du mot de passe avec le sel et l'ID de session php...
        $this->_privateKeyPassword = $passwd;
        $this->_issetPrivateKeyPassword = true;
        $this->_nebuleInstance->addListEntitiesUnlocked($this);
        return true;
    }

    /**
     * Supprime le mot de passe de l'entité.
     * Cela verrouille l'entité.
     *
     * @return boolean
     */
    public function unsetPrivateKeyPassword(): bool
    {
        if ($this->_id == '0') {
            return false;
        }
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_privateKeyPassword = $this->_privateKeyPasswordSalt;
        $this->_privateKeyPassword = '';
        $this->_privateKeyPasswordSalt = '';
        $this->_issetPrivateKeyPassword = false;
        $this->_nebuleInstance->removeListEntitiesUnlocked($this->_id);
        return true;
    }

    public function checkPrivateKeyPassword(): bool
    {
        return $this->_issetPrivateKeyPassword;
    }

    public function changePrivateKeyPassword(string $newPasswd): bool
    {
        if ($this->_id == '0') {
            return false;
        }
        // Vérifie que le mot de passe actuel est présent.
        if (!$this->_issetPrivateKeyPassword) {
            return false;
        }

        $this->_metrology->addLog('Change entity password - old ' . $this->_privateKeyID, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
        $oldPrivateKeyID = $this->_privateKeyID;

        if (!$this->_crypto->checkPrivateKeyPassword($this->_privateKey, $this->_privateKeyPassword))
            return false;

        $newKey = $this->_crypto->changePrivateKeyPassword($this->_privateKey, $this->_privateKeyPassword, $newPasswd);
        if ($newKey == '')
            return false;

        $this->_privateKeyPasswordSalt = $this->_crypto->getRandom(self::ENTITY_PASSWORD_SALT_SIZE, Crypto::RANDOM_STRONG);
        // A faire... le chiffrement du mot de passe avec le sel et l'ID de session php...
        $this->_privateKeyPassword = $newPasswd;
        $this->_privateKey = $newKey;
        $this->_privateKeyID = $this->_crypto->hash($newKey);
        $this->_issetPrivateKeyPassword = true;
        $this->_metrology->addLog('Change entity password - new ' . $this->_privateKeyID, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');

        unset($newKey);

        // Ecrit l'objet de la nouvelle clé privée.
        $this->_io->writeObject($this->_privateKey);

        // Définition de la date.
        $date = date(DATE_ATOM);

        // Si ce n'est pas une création d'entité, fait les liens de mises à jour de clés privées.
        if (!$this->_newPrivateKey) {
            // Création lien 1.
            $link = '_' . $this->_id . '_' . $date . '_x_' . $oldPrivateKeyID . '_' . $this->_id . '_0';
            $this->_createNewEntityWriteLink($link, $oldPrivateKeyID, $this->_id, '0');

            // Création lien 2.
            $link = '_' . $this->_id . '_' . $date . '_u_' . $oldPrivateKeyID . '_' . $this->_privateKeyID . '_0';
            $this->_createNewEntityWriteLink($link, $oldPrivateKeyID, $this->_privateKeyID, '0');
        }

        // Création lien 3.
        $target = $this->_crypto->hash($this->_configuration->getOptionAsString('cryptoHashAlgorithm'));
        $meta = $this->_crypto->hash('nebule/objet/hash');
        $link = '_' . $this->_id . '_' . $date . '_l_' . $this->_privateKeyID . '_' . $target . '_' . $meta;
        $this->_createNewEntityWriteLink($link, $this->_privateKeyID, $target, $meta);

        // Création lien 4.
        $target = $this->_crypto->hash(self::ENTITY_TYPE);
        $meta = $this->_crypto->hash('nebule/objet/type');
        $link = '_' . $this->_id . '_' . $date . '_l_' . $this->_privateKeyID . '_' . $target . '_' . $meta;
        $this->_createNewEntityWriteLink($link, $this->_privateKeyID, $target, $meta);

        // Création lien 5.
        $target = $this->_id;
        $link = '_' . $this->_id . '_' . $date . '_f_' . $this->_privateKeyID . '_' . $target . '_0';
        $this->_createNewEntityWriteLink($link, $this->_privateKeyID, $target, '0');

        unset($date, $source, $target, $meta, $link);

        $this->_newPrivateKey = false;
        return true;
    }


    /**
     * Signature de liens.
     *
     * @param string $link
     * @param string $algo
     * @return string|null
     */
    public function signLink(string $link, string $algo = ''): ?string
    {
        if ($this->_privateKey == '') {
            $this->_metrology->addLog('ERROR entity no private key', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
            return null;
        }
        if ($this->_privateKeyPassword == '') {
            $this->_metrology->addLog('ERROR entity no password for private key', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000', __FUNCTION__, '00000000');
            return null;
        }
        if ($algo == '')
            $algo = $this->_configuration->getOptionAsString('cryptoHashAlgorithm');

        $hash = $this->_crypto->hash($link, $algo);
        return $this->_crypto->sign($hash, $this->_privateKey, $this->_privateKeyPassword);
    }

    /**
     * Signature et écriture de liens.
     *
     * @param string $link
     * @return string|boolean
     */
    public function signWriteLink(string $link)
    {
        $signe = $this->signLink($link);
        if ($signe === false) {
            return false;
        }
        $signedLink = $signe . $link;
        // A faire...
        return $signedLink;
    }

    /**
     * Déchiffrement de données pour l'entité.
     * Déchiffrement asymétrique uniquement, càd avec la clé privée.
     *
     * @param string $code
     * @return string
     */
    public function decrypt(string $code): string
    {
        return $this->_crypto->decryptTo($code, $this->_privateKey, $this->_privateKeyPassword);
    }


    /**
     * Lecture du nom complet.
     * La construction du nom complet d'une entité est légèrement différente d'un objet.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFullName(string $socialClass = ''): string
    {
        if ($this->_id == '0')
            return '';
        if (isset($this->_fullname)
            && trim($this->_fullname) != ''
        )
            return $this->_fullname;

        $name = $this->getName($socialClass);
        $prefix = $this->getPrefixName($socialClass);
        $suffix = $this->getSuffixName($socialClass);
        $firstname = $this->getFirstname($socialClass);
        $surname = $this->getSurname($socialClass);

        $fullname = $name;
        if ($surname != '')
            $fullname = '&ldquo;' . $surname . '&rdquo; ' . $fullname;
        if ($firstname != '')
            $fullname = $firstname . ' ' . $fullname;
        if ($prefix != '')
            $fullname = $prefix . ' ' . $fullname;
        if ($suffix != '')
            $fullname = $fullname . ' ' . $suffix;
        $this->_fullname = $fullname;

        return $fullname;
    }

    // Retourne les localisations de l'entité.
    public function getLocalisations(string $socialClass = ''): array
    {
        if ($this->_id == '0')
            return array();
        return $this->getProperties(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationsID(string $socialClass = ''): array
    {
        if ($this->_id == '0')
            return array();
        return $this->getPropertiesID(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisation(string $socialClass = ''): string
    {
        if ($this->_id == '0')
            return '';
        return $this->getProperty(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationID(string $socialClass = ''): string
    {
        if ($this->_id == '0') {
            return '';
        }
        return $this->getPropertyID(nebule::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    /**
     * Recherche la miniature d'un image la plus proche possible de la dimension demandée. Recherche faite sur un seul niveau d'arborescence.
     *
     * @param int $size
     * @return string
     */
    public function getFaceID(int$size = 400): string
    {
        if ($this->_id == '0') {
            return '';
        }
        if ($size == '0') {
            return '';
        }

        $ximg = (int)($this->getProperty('EXIF/ImageWidth', 'all'));
        if ($ximg == 0) {
            $ximg = (int)($this->getProperty('COMPUTED.Width', 'all'));
        }

        $yimg = (int)($this->getProperty('EXIF/ImageHeight', 'all'));
        if ($yimg == 0) {
            $yimg = (int)($this->getProperty('COMPUTED.Height', 'all'));
        }

        if ($ximg == 0 || $yimg == 0) // Si pas de dimensions trouvées, continue avec des valeurs par défaut.
        {
            $ximg = 10000;
            $yimg = 10000;
        }

        // Si l'objet est plus petit que la 'miniature' demandée, retourne 0.
        $xyimg = sqrt($ximg * $yimg);
        if ($size >= $xyimg) {
            return '0';
        }

        $list = array();
        $links = array();
        //_l_fnd($this->_id, $links, 'f', $this->_id, '', '0');				// @todo Vérifier le bon fonctionnement.
        $links = $this->readLinksFilterFull_disabled('', '', 'f', $this->_id, '', '0');
        foreach ($links as $link) {
            $instance6 = $this->_nebuleInstance->newObject($link->getHashTarget());
            $type = $instance6->getType('all');
            if (($type == 'image/png'
                    || $type == 'image/jpeg')
                && $instance6->checkPresent()
            ) {
                $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                if ($xsize == 0) {
                    $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                }
                $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                if ($ysize == 0) {
                    $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                }
                if ($xsize != ''
                    && $ysize != ''
                    && $xsize != '0'
                    && $ysize != '0'
                ) {
                    $list[$instance6->getID()][0] = $instance6->getID();
                    $list[$instance6->getID()][1] = sqrt((int)$xsize * (int)$ysize);
                }
            }
        }

        // Recherche la résolution la plus proche.
        $best = $xyimg;
        $bestimg = '0';
        if (sizeof($list) != 0) {
            foreach ($list as $img) {
                if (abs($size - $img[1]) < $best) {
                    $bestimg = $img[0];
                    $best = abs($size - $img[1]);
                }
            }
        }
        if ($bestimg != $this->_id && $xyimg < $best) {
            return '0';
        }
        if ($bestimg != $this->_id) {
            return $bestimg;
        } else // Si pas trouvé d'objet aux dimmensions intéressantes, recherche si ça ne marche pas mieux avec l'objet parent.
        {
            $uplinks = array();
            //_l_fnd($this->_id, $uplinks, 'f', '', $this->_id, '0');							// @todo Vérifier le bon fonctionnement.
            $uplinks = $this->readLinksFilterFull_disabled('', '', 'f', '', $this->_id, '0');
            foreach ($uplinks as $uplink) {
                $instance5 = $this->_nebuleInstance->newObject($uplink->getHashSource());
                $type = $instance5->getType('all');
                if (($type == 'image/png' || $type == 'image/jpeg') && $instance5->checkPresent()) {
                    $list = array();
                    $links = array();
                    //_l_fnd($instance5->getID(), $links, 'f', $instance5->getID(), '', '0');          // @todo Vérifier le bon fonctionnement.
                    $links = $instance5->readLinksFilterFull_disabled('', '', 'f', $instance5->getID(), '', '0');
                    foreach ($links as $link) {
                        $instance6 = $this->_nebuleInstance->newObject($link->getHashTarget());
                        $type = $instance6->getType('all');
                        if ($type == 'image/png'
                            || $type == 'image/jpeg'
                        ) {
                            $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                            if ($xsize == 0) {
                                $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                            }
                            $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                            if ($ysize == 0) {
                                $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                            }
                            if ($xsize != ''
                                && $ysize != ''
                                && $xsize != '0'
                                && $ysize != '0'
                            ) {
                                $list[$instance6->getID()][0] = $instance6->getID();
                                $list[$instance6->getID()][1] = sqrt((int)$xsize * (int)$ysize);
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($list) != 0) {
            foreach ($list as $img) {
                if (abs($size - $img[1]) < $best) {
                    $bestimg = $img[0];
                    $best = abs($size - $img[1]);
                }
            }
        }
        if ($xyimg < $best) {
            return '0';
        }
        return $bestimg;
    }


    // Ecrit l'objet si non présent.
    public function write(): bool
    {
        if (!$this->_io->checkObjectPresent($this->_id)) {
            $id = $this->_io->writeObject($this->_publicKey);
        } else {
            $id = $this->_id;
        }

        // Métrologie.
        $v = true;
        if ($id === false) {
            $v = false;
            // Si l'écriture échoue, on crée l'objet d'ID '0'. @todo à revoir si vraiment utile... pareil pour objects->write().
            $id = '0';
        }
        $this->_metrology->addAction('addent', $id, $v);

        return $v;
    }



    // Désactivation des fonctions de protection.

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getMarkProtected(): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getReloadMarkProtected(): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getProtectedID(): string
    {
        return '0';
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getUnprotectedID(): string
    {
        return $this->_id;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @param bool $obfuscated
     * @return boolean
     */
    public function setProtected(bool $obfuscated = false): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setUnprotected(): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @param string $entity
     * @return boolean
     */
    public function setProtectedTo(string $entity): bool
    {
        return false;
    }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return array
     */
    public function getProtectedTo(): array
    {
        return array();
    }


    /**
     * Retourne si l'entité est une autorité locale.
     * Fait appel à la fonction dédiée de la classe nebule.
     *
     * @return boolean
     */
    public function getIsLocalAuthority(): bool
    {
        return $this->_nebuleInstance->getIsLocalAuthority($this->_id);
    }


    /**
     * Retourne la liste des liens vers les groupes dont l'entité est à l'écoute.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsFollowerOfGroupLinks(string $socialClass = 'myself'): array
    {
        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull_disabled(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinance sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashTarget());
            if (!$instance->getIsGroup('all')) {
                unset($links[$i]);
            }
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les groupes dont l'entité est à l'écoute.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsFollowerOnGroupID(string $socialClass = 'myself'): array
    {
        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull_disabled(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newGroup($link->getHashTarget());
            if ($instance->getIsGroup('all')) {
                $list[$link->getHashTarget()] = $link->getHashTarget();
            }
        }

        return $list;
    }

    /**
     * Retourne la liste des liens vers les conversations dont l'entité est à l'écoute.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsFollowerOfConversationLinks(string $socialClass = 'myself'): array
    {
        // Liste tous les liens de définition des membres du groupe.
        $links = $this->readLinksFilterFull_disabled(
            '',
            '',
            'l',
            $this->id,
            '',
            $this->_crypto->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE)
        );

        // Fait un tri par pertinence sociale.
        $this->_social->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashTarget());
            if (!$instance->getIsConversation('all')) {
                unset($links[$i]);
            }
        }

        return $links;
    }

    /**
     * Retourne la liste des ID vers les conversations dont l'entité est à l'écoute.
     * S'appuie sur la fonction dédiée aux groupes.
     *
     * @param string $socialClass
     * @return array:string
     */
    public function getListIsFollowerOnConversationID(string $socialClass = 'myself'): array
    {
        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getListIsFollowerOfConversationLinks($socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_nebuleInstance->newConversation($link->getHashTarget());
            if ($instance->getIsConversation('all')) {
                $list[$link->getHashTarget()] = $link->getHashTarget();
            }
        }

        return $list;
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#oe">OE / Entité</a>
            <ul>
                <li><a href="#oen">OEM / Entités Maîtresses</a></li>
                <li><a href="#oen">OEN / Nommage</a></li>
                <li><a href="#oep">OEP / Protection</a></li>
                <li><a href="#oed">OED / Dissimulation</a></li>
                <li><a href="#oel">OEL / Liens</a></li>
                <li><a href="#oec">OEC / Création</a></li>
                <li><a href="#oes">OES / Stockage</a></li>
                <li><a href="#oet">OET / Transfert</a></li>
                <li><a href="#oer">OER / Réservation</a></li>
                <li><a href="#oeio">OEIO / Implémentation des Options</a></li>
                <li><a href="#oeia">OEIA / Implémentation des Actions</a></li>
                <li><a href="#oeo">OEO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="oe">OE / Entité</h2>
        <p>A faire...</p>
        <p>L’entité est un objet caractéristique. Elle dispose d’une clé publique, par laquelle elle est identifiée, et
            d’une clé privée.</p>
        <p>L’indication de la fonction de prise d’empreinte (hashage) ainsi que le type de bi-clé sont impératifs. Le
            lien est identique à celui défini pour un objet.</p>
        <p>Le type mime <code>mime-type:application/x-pem-file</code> est suffisant pour indiquer que cet objet est une
            entité. <i>Des valeurs équivalentes pourront être définies ultérieurement</i>.</p>
        <p>Toutes les autres indications sont optionnelles.</p>

        <h3 id="oem">OEM / Entités Maîtresses</h3>
        <p>La bibliothèque utilise actuellement plusieurs entités spéciales, dites autorités maîtresses, avec des rôles
            prédéfinis.</p>
        <ol>
            <li>Maître du tout. L'instance actuelle s'appelle <a href="http://puppetmaster.nebule.org">puppetmaster</a>.
                Voir <a href="#cam">CAM</a>.
            </li>
            <li>Maître de la sécurité. L'instance actuelle s'appelle <a href="http://cerberus.nebule.org">cerberus</a>.
                Voir <a href="#cams">CAMS</a>.
            </li>
            <li>Maître du code. L'instance actuelle s'appelle <a href="http://bachue.nebule.org">bachue</a>. Voir <a
                    href="#camc">CAMC</a>.
            </li>
            <li>Maître de l'annuaire. L'instance actuelle s'appelle <a href="http://asabiyya.nebule.org">assabyia</a>.
                Voir <a href="#cama">CAMA</a>.
            </li>
            <li>Maître du temps. L'instance actuelle s'appelle <a href="http://kronos.nebule.org">kronos</a>. Voir <a
                    href="#camt">CAMT</a>.
            </li>
        </ol>

        <h3 id="oen">OEN / Nommage</h3>
        <p>Le nommage à l’affichage du nom des entités repose sur plusieurs propriétés :</p>
        <ol>
            <li>nom</li>
            <li>prénom</li>
            <li>surnom</li>
            <li>préfixe</li>
            <li>suffixe</li>
        </ol>
        <p>Ces propriétés sont matérialisées par des liens de type <code>l</code> avec comme objets méta, respectivement
            :</p>
        <ol>
            <li><code>nebule/objet/nom</code></li>
            <li><code>nebule/objet/prenom</code></li>
            <li><code>nebule/objet/surnom</code></li>
            <li><code>nebule/objet/prefix</code></li>
            <li><code>nebule/objet/suffix</code></li>
        </ol>
        <p>Par convention, voici le nommage des entités :</p>
        <p><code>préfixe prénom "surnom" nom suffixe</code></p>

        <h3 id="oep">OEP / Protection</h3>
        <p>A faire...</p>

        <h3 id="oed">OED / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oel">OEL / Liens</h3>
        <p>A faire...</p>

        <h3 id="oec">OEC / Création</h3>
        <p>La première étape consiste en la génération d’un bi-clé (public/privé) cryptographique. Ce bi-clé peut être
            de type RSA ou équivalent. Aujourd’hui, seul RSA est reconnu.</p>
        <p>On extrait la clé publique du bi-clé. Le calcul de l’empreinte cryptographique de la clé publique donne
            l’identifiant de l’entité. On écrit dans les objets (o/*) l’objet avec comme contenu la clé publique et
            comme id son empreinte cryptographique.</p>
        <p>On extrait la clé privée du bi-clé. Il est fortement conseillé lors de l’extraction de protéger tout de suite
            la clé privée avec un mot de passe. On écrit dans les objets (o/*) l’objet avec comme contenu la clé privée
            et comme id son empreinte cryptographique (différente de celle de la clé publique).</p>
        <p>A partir de maintenant, le bi-clé n’est plus nécessaire. Il faut le supprimer avec un effacement
            sécurisé.</p>
        <p>Pour que l’objet soit reconnu comme entité il faut créer les liens correspondants.</p>
        <ul>
            <li>Lien 1 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de l’algorithme de hash utilisé pour le calcul des empreintes</li>
                    <li>Empreinte de ‘nebule/objet/hash’</li>
                </ul>
            </li>
            <li>Lien 2 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Empreinte de l’algorithme de hash utilisé pour le calcul des empreintes</li>
                    <li>Empreinte de ‘nebule/objet/hash’</li>
                </ul>
            </li>
            <li>Lien 3 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de ‘application/x-pem-file’</li>
                    <li>Empreinte de ‘nebule/objet/type’</li>
                </ul>
            </li>
            <li>Lien 4 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Empreinte de ‘application/x-pem-file’</li>
                    <li>Empreinte de ‘nebule/objet/type’</li>
                </ul>
            </li>
            <li>Lien 5 :
                <ul>
                    <li>Signature du lien par la clé privée de la nouvelle entité</li>
                    <li>Identifiant de la clé publique</li>
                    <li>Horodatage</li>
                    <li>Lien de type <code>f</code> ;</li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>0.</li>
                </ul>
            </li>
        </ul>
        <p>C’est le minimum vital pour une entité. Ensuite, d’autres propriétés peuvent être ajoutées à l’entité (id clé
            publique) comme sont nom, son type, etc…</p>
        <p>Si le mot de passe de la clé privée est définit par l’utilisateur demandeur de la nouvelle entité, il faut
            supprimer ce mot de passe avec un effacement sécurisé.</p>
        <p>Si le mot de passe de la clé privée a été généré, donc que la nouvelle entité est esclave d’une entité
            maître, le mot de passe doit être stocké dans un objet chiffré pour l’entité maître. Et il faut générer un
            lien reliant l’objet de mot de passe à la clé privée de la nouvelle entité.</p>

        <h3 id="oes">OES / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="oet">OET / Transfert</h3>
        <p>A faire...</p>

        <h3 id="oer">OER / Réservation</h3>
        <p>A faire...</p>

        <h4 id="oeio">OEIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="oeia">OEIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <h3 id="oeo">OEO / Oubli</h3>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
