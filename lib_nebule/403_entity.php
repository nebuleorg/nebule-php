<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * Entity class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *           To open a node as entity, create instance with :
 *           - nebule instance;
 *           - valid node ID.
 *           To create a new entity, create a instance with :
 *           - nebule instance;
 *           - node ID = '0'
 *           Creation of new entity is a little bit different to object because it's impossible to check links on
 *              previously unknown entity. Link of entity cannot be obfuscated.
 *           Don't forget to call write() if you want a persistant object on database /o.
 *           On error, return instance with ID = '0'.
 * ------------------------------------------------------------------------------------------
 */
class Entity extends Node implements nodeInterface
{
    const ENTITY_MAX_SIZE = 16000;
    const ENTITY_PASSWORD_SALT_SIZE = 128;
    const ENTITY_TYPE = 'application/x-pem-file';
    const ENTITY_PUBLIC_HEADER = '-----BEGIN PUBLIC KEY-----';
    const ENTITY_PRIVATE_HEADER = '-----BEGIN ENCRYPTED PRIVATE KEY-----';
    const DEFAULT_ICON_RID = '6e6562756c652f6f626a65742f656e74697465000000000000000000000000000000.none.272';

    const SESSION_SAVED_VARS = array(
        '_id',
        '_fullName',
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
        '_privateKeyOID',
        '_privateKey',
        '_newPrivateKey',
        '_privateKeyPassword',
        '_privateKeyPasswordSalt',
        '_isSetPrivateKeyPassword',
        //'_faceCache',
    );

    private string $_publicKey = '';
    private string $_privateKeyOID = '0';
    private string $_privateKey = '';
    private bool $_newPrivateKey = false;
    private ?string $_privateKeyPassword = null;
    private string $_privateKeyPasswordSalt = '';
    private bool $_isSetPrivateKeyPassword = false;
    //private array $_faceCache = array();
    //private bool $_cacheCurrentEntityUnlocked = false;

    /**
     * {@inheritDoc}
     * @see Node::_initialisation()
     * @return void
     */
    protected function _initialisation(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id != '0')
            $this->_loadEntity();
    }

    private function _loadEntity(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions id=' . $this->_id, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_ioInstance->checkObjectPresent($this->_id)
            || !$this->_ioInstance->checkLinkPresent($this->_id)
        ) {
            $this->_id = '0';
            return;
        }

        $this->_metrologyInstance->addLog('Load entity ' . $this->_id, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2f5d9e5a');

        if ($this->_publicKey == '')
            $this->_findPublicKey();
    }

    public function createNewEntity(string $algo='rsa', int $size=2048): void {
        // FIXME use algo and size.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateEntity')
            && ($this->_entitiesInstance->getConnectedEntityIsUnlocked()
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            )
        ) {
            $this->_metrologyInstance->addLog('create entity algo=' . $this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm'), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '7344db13');

            $this->_privateKeyPassword = $this->_cryptoInstance->getRandom(32, Crypto::RANDOM_STRONG);
            $newPkey = $this->_cryptoInstance->newAsymmetricKeys($this->_privateKeyPassword);
            if (sizeof($newPkey) != 0) {
                $this->_publicKey = $newPkey['public'];
                $this->_id = $this->_nebuleInstance->getFromDataNID($this->_publicKey);
                $this->_metrologyInstance->addLog('create entity public key oid=' . $this->_id, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'b5dacb0d');
                $this->_privateKeyPasswordSalt = '';
                $this->_privateKey = $newPkey['private'];
                $this->_privateKeyOID = $this->_nebuleInstance->getFromDataNID($this->_privateKey);
                $this->_metrologyInstance->addLog('create entity private key oid=' . $this->_privateKeyOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '9afc5da2');
                $this->_isSetPrivateKeyPassword = true;
                $this->_newPrivateKey = true;
                unset($newPkey);
            } else {
                $this->_metrologyInstance->addLog('Create entity error on generation', Metrology::LOG_LEVEL_ERROR, __METHOD__, '98b648b1');
                $this->_id = '0';
            }
        } else {
            $this->_metrologyInstance->addLog('Create entity error no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, '6fcb7d18');
            $this->_id = '0';
        }
    }

    public function setCreateWrite(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_isNew || !$this->_newPrivateKey || $this->_id == '0')
            return;
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateEntity')) {
            $this->_metrologyInstance->addLog('Write object no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ca6f5f59');
            return;
        }

        $this->_ioInstance->setObject($this->_id, $this->_publicKey);

        //$nid1 = $this->_id;
        $nid2 = $this->_nebuleInstance->getFromDataNID(self::ENTITY_TYPE);
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_TYPE);
        $this->_setCreateBlocLink('l', $this->_id, $nid2, $nid3);
    }

    private function _setCreateBlocLink(string $req, string $nid1, string $nid2 = '', string $nid3 = ''): void {
        $link = $req . '>' . $nid1;
        if ($nid2 != '')
            $link .= '>' . $nid2;
        if ($nid3 != '')
            $link .= '>' . $nid3;
        $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
        $instanceBL->addLink($link);
        $instanceBL->signWrite($this, '', $this->_privateKey, $this->_privateKeyPassword);
    }

    public function setSelfProperty(string $type, string $property, bool $protect = false, bool $obfuscated = false):void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_isNew || $this->_id == '0')
            return;
        $signer = array(
            'eid' => $this,
            'key' => $this->_privateKey,
            'pwd' => $this->_privateKeyPassword,
        );
        $this->setProperty($type, $property, $protect, $obfuscated, $signer);
    }



    /**
     * Vérifier que c'est en hexa, et que c'est une entité.
     *
     * @return void
     */
    private function _verifyEntity(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé privée
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false)
            $t = true;
        // Vérifie si le contenu contient un entête de clé publique
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false)
            $t = true;
        unset($objHead);

        // TODO Faire une vérifications plus complète...

        $this->_typeVerified = $t;
    }

    private bool $_typeVerified = false;

    public function getKeyType(): string {
        if ($this->_id == '0')
            return '';
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_ENTITE_TYPE, 'all');
    }

    public function getTypeVerify(): bool {
        $this->_verifyEntity();
        return $this->_typeVerified;
    }

    public function getIsPublicKey(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PUBLIC_HEADER)) !== false)
            $t = true;
        unset($objHead);
        return $t;
    }

    public function getIsPrivateKey(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $t = false;
        // Extrait le contenu de l'objet source.
        $objHead = $this->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        // Vérifie si le contenu contient un entête de clé public
        if ((strstr($objHead, self::ENTITY_PRIVATE_HEADER)) !== false)
            $t = true;
        unset($objHead);
        return $t;
    }



    public function getPublicKeyID(): string { return $this->_id; }

    public function getPublicKey(): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0'
            || $this->_publicKey == ''
        )
            return '';
        return $this->_publicKey;
    }

    private function _findPublicKey(): void { $this->_publicKey = $this->_ioInstance->getObject($this->_id, self::ENTITY_MAX_SIZE); }



    // Get the OID of the private key.
    public function getPrivateKeyOID(): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_findPrivateKeyOID();
        return $this->_privateKeyOID;
    }

    // Get the OID of the private key.
    public function getHavePrivateKey(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_privateKeyOID != '0')
            return true;
        return false;
    }

    // Try to find OID of the private key.
    private function _findPrivateKeyOID(): void {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $oidPKey = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_PRIVATE_KEY);

        if ($this->_privateKeyOID != '0')
            return;

        $list = array();
        $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $this->_id,
                'bl/rl/nid3' => $oidPKey,
        );
        $this->getLinks($list, $filter, false);
        if (sizeof($list) == 0) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('no link to private key for eid=' . $this->_id, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'be65246d');
            return;
        }

        $refDate = '0';
        foreach ($list as $link) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('try link ' . $link->getParsed()['bl/rl'], Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bc321274');
            if (!$link->getValid())
                continue;

            $nid2 = $link->getParsed()['bl/rl/nid2'];
            $line = $this->_ioInstance->getObject($nid2, self::ENTITY_MAX_SIZE);
            $date = $link->getDate();
            if ($link->getBlocLink()->getParsed()['bs/rs1/eid'] == $this->_id
                && strlen($nid2) > BlocLink::NID_MIN_HASH_SIZE
                && !is_bool($line)
                && $line !== null
                && str_contains($line, self::ENTITY_PRIVATE_HEADER)
                && strcmp($date, $refDate) > 0
            ) {
                $this->_privateKeyOID = $nid2;
                $refDate = $date;
                $this->_nebuleInstance->getMetrologyInstance()->addLog('ok private key ' . $nid2, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '63781bce');
            } else
                $this->_nebuleInstance->getMetrologyInstance()->addLog('invalid private key ' . $nid2, Metrology::LOG_LEVEL_ERROR, __METHOD__, '293d8170');
        }
    }

    /**
     * Retrouve la clé privée.
     * TODO vérifier que c'est bien une clé privée _pour_ cette clé publique.
     *
     * @return bool
     */
    private function _findPrivateKey(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_privateKey != '')
            return true;

        $this->_findPrivateKeyOID();
        if ($this->_privateKeyOID == '0')
            return false;

        $content = $this->_ioInstance->getObject($this->_privateKeyOID, self::ENTITY_MAX_SIZE);
        if ($content !== false)
            $this->_privateKey = $content;
        return true;
    }

    /**
     * Définit le mot de passe de la clé privée.
     * TODO le chiffrement du mot de passe avec le sel et l'ID de session php...
     *
     * @param string $passwd
     * @return bool
     */
    public function setPrivateKeyPassword(string $passwd): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (! $this->_findPrivateKey()) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('no private key', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ed4a39cf');
            return false;
        }
        if (! $this->_cryptoInstance->checkPrivateKeyPassword($this->_privateKey, $passwd)) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('check private key failed oid=' . $this->_privateKeyOID, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'bf91c623');
            return false;
        }
        $this->_nebuleInstance->getMetrologyInstance()->addLog('check private key ok oid=' . $this->_privateKeyOID, Metrology::LOG_LEVEL_ERROR, __METHOD__, '4ef37f6e');
        $this->_privateKeyPasswordSalt = $this->_cryptoInstance->getRandom(self::ENTITY_PASSWORD_SALT_SIZE, Crypto::RANDOM_STRONG);
        $this->_privateKeyPassword = $passwd;
        $this->_isSetPrivateKeyPassword = true;
        return true;
    }

    /**
     * Supprime le mot de passe de l'entité.
     * Cela verrouille l'entité.
     *
     * @return boolean
     */
    public function unsetPrivateKeyPassword(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0')
            return false;
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_privateKeyPassword = $this->_privateKeyPasswordSalt;
        $this->_privateKeyPassword = null;
        $this->_privateKeyPasswordSalt = '';
        $this->_isSetPrivateKeyPassword = false;
        return true;
    }

    public function getHavePrivateKeyPassword(): bool { return $this->_isSetPrivateKeyPassword; }
    public function getIsUnlocked(): bool { return $this->_isSetPrivateKeyPassword; }

    public function setNewPrivateKeyPassword(string $newPasswd): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0')
            return false;
        // Vérifie que le mot de passe actuel est présent.
        if (!$this->_isSetPrivateKeyPassword)
            return false;

        $this->_metrologyInstance->addLog('change entity password - old ' . $this->_privateKeyOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '38022519');
        $oldPrivateKeyID = $this->_privateKeyOID;

        if (!$this->_cryptoInstance->checkPrivateKeyPassword($this->_privateKey, $this->_privateKeyPassword))
            return false;

        $newKey = $this->_cryptoInstance->changePrivateKeyPassword($this->_privateKey, $this->_privateKeyPassword, $newPasswd);
        if ($newKey == '')
            return false;

        $this->_privateKeyPasswordSalt = $this->_cryptoInstance->getRandom(self::ENTITY_PASSWORD_SALT_SIZE, Crypto::RANDOM_STRONG);
        // À faire... le chiffrement du mot de passe avec le sel et l'ID de session php...
        $this->_privateKeyPassword = $newPasswd;
        $this->_privateKey = $newKey;
        $this->_privateKeyOID = $this->_nebuleInstance->getFromDataNID($newKey);
        $this->_isSetPrivateKeyPassword = true;
        $this->_metrologyInstance->addLog('change entity password - new ' . $this->_privateKeyOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'd11dc438');

        unset($newKey);

        // Ecrit l'objet de la nouvelle clé privée.
        $this->_ioInstance->setObject($this->_privateKeyOID, $this->_privateKey);

        // Si ce n'est pas une création d'entité, fait les liens de mises à jour de clés privées.
        if (!$this->_newPrivateKey) {
            // Création lien 1.
            $this->_setCreateBlocLink('x', $oldPrivateKeyID, $this->_id);

            // Création lien 2.
            $this->_setCreateBlocLink('u', $oldPrivateKeyID, $this->_privateKeyOID);
        }

        // Création lien 3.
        $nid2 = $this->_nebuleInstance->getFromDataNID(self::ENTITY_TYPE);
        $nid3 = $this->_nebuleInstance->getFromDataNID('nebule/objet/type');
        $this->_setCreateBlocLink('l', $this->_privateKeyOID, $nid2, $nid3);

        // Création lien 4.
        $nid3 = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_PRIVATE_KEY);
        $this->_setCreateBlocLink('f', $this->_id, $this->_privateKeyOID, $nid3);

        $this->_newPrivateKey = false;
        return true;
    }



    /**
     * Link signing.
     *
     * @param string $link
     * @param string $algo
     * @param string $privateKey
     * @param string $password
     * @return string|null
     */
    public function signLink(string $link, string $algo = '', string $privateKey = '', string $password = ''): ?string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($privateKey == '') {
            if ($this->_privateKey == '') {
                $this->_metrologyInstance->addLog('no private key', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '91353b7d');
                return null;
            }
            if ($this->_privateKeyPassword === null) {
                $this->_metrologyInstance->addLog('no password for private key', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '63de2900');
                return null;
            }
            $privateKey = $this->_privateKey;
            $password = $this->_privateKeyPassword;
        }
        if ($algo == '')
            $algo = $this->_configurationInstance->getOptionAsString('cryptoHashAlgorithm');

        $hash = $this->_cryptoInstance->hash($link, $algo);
        return $this->_cryptoInstance->sign($hash, $privateKey, $password) . '.' . $algo;
    }

    /**
     * Signature et écriture de liens.
     *
     * @param string $link
     * @return string|boolean
     */
    /*public function signWriteLink(string $link) {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $signe = $this->signLink($link);
        if ($signe === false) {
            return false;
        }
        $signedLink = $signe . $link;
        // A faire...
        return $signedLink;
    }*/

    /**
     * Déchiffrement de données pour l'entité.
     * Déchiffrement asymétrique uniquement, càd avec la clé privée.
     *
     * @param string $code
     * @return string
     */
    public function decrypt(string $code): string { return $this->_cryptoInstance->decryptTo($code, $this->_privateKey, $this->_privateKeyPassword); }



    public function getName(string $socialClass = 'self'): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($socialClass == '') {
            $socialClass = 'self';
        }

        $refPropertyID = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_NOM);
        $name = $this->getProperty($refPropertyID, $socialClass);
        if ($name == '')
            $name = $this->_id;
        return $name;
    }

    /**
     * Lecture du nom complet.
     * La construction du nom complet d'une entité est légèrement différente d'un objet.
     *
     * @param string $socialClass
     * @return string
     */
    public function getFullName(string $socialClass = ''): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0')
            return '';
        if (isset($this->_fullName) && trim($this->_fullName) != '')
            return $this->_fullName;
        if ($socialClass == '')
            $socialClass = 'self';

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
        $this->_fullName = $fullname;

        return $fullname;
    }

    // Retourne les localisations de l'entité.
    public function getLocations(string $socialClass = ''): array {
        if ($this->_id == '0')
            return array();
        return $this->getProperties(References::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationsID(string $socialClass = ''): array {
        if ($this->_id == '0')
            return array();
        return $this->getPropertiesID(References::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocation(string $socialClass = ''): string {
        if ($this->_id == '0')
            return '';
        return $this->getProperty(References::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    public function getLocalisationID(string $socialClass = ''): string {
        if ($this->_id == '0')
            return '';
        return $this->getPropertyID(References::REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION, $socialClass);
    }

    /**
     * Recherche la miniature d'un image la plus proche possible de la dimension demandée. Recherche faite sur un seul niveau d'arborescence.
     *
     * @param int $size
     * @return string
     */
    public function getFaceID(int$size = 400): string {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_id == '0')
            return '';
        if ($size == '0')
            return '';

        $ximg = (int)($this->getProperty('EXIF/ImageWidth', 'all'));
        if ($ximg == 0)
            $ximg = (int)($this->getProperty('COMPUTED.Width', 'all'));

        $yimg = (int)($this->getProperty('EXIF/ImageHeight', 'all'));
        if ($yimg == 0)
            $yimg = (int)($this->getProperty('COMPUTED.Height', 'all'));

        if ($ximg == 0 || $yimg == 0) // Si pas de dimensions trouvées, continue avec des valeurs par défaut.
        {
            $ximg = 10000;
            $yimg = 10000;
        }

        // Si l'objet est plus petit que la 'miniature' demandée, retourne 0.
        $xyimg = sqrt($ximg * $yimg);
        if ($size >= $xyimg)
            return '0';

        $list = array();
        $links = array();
        //_l_fnd($this->_id, $links, 'f', $this->_id, '', '0');				// @todo Vérifier le bon fonctionnement.
        $links = $this->getLinksOnFields('', '', 'f', $this->_id, '', '0');
        foreach ($links as $link) {
            $instance6 = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            $type = $instance6->getType('all');
            if (($type == 'image/png'
                    || $type == 'image/jpeg')
                && $instance6->checkPresent()
            ) {
                $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                if ($xsize == 0)
                    $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                if ($ysize == 0)
                    $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
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
        if ($bestimg != $this->_id && $xyimg < $best)
            return '0';
        if ($bestimg != $this->_id)
            return $bestimg;
        else // Si pas trouvé d'objet aux dimmensions intéressantes, recherche si ça ne marche pas mieux avec l'objet parent.
        {
            $uplinks = array();
            //_l_fnd($this->_id, $uplinks, 'f', '', $this->_id, '0');							// @todo Vérifier le bon fonctionnement.
            $uplinks = $this->getLinksOnFields('', '', 'f', '', $this->_id, '0');
            foreach ($uplinks as $uplink) {
                $instance5 = $this->_cacheInstance->newNode($uplink->getParsed()['bl/rl/nid1']);
                $type = $instance5->getType('all');
                if (($type == 'image/png' || $type == 'image/jpeg') && $instance5->checkPresent()) {
                    $list = array();
                    $links = array();
                    //_l_fnd($instance5->getID(), $links, 'f', $instance5->getID(), '', '0');          // @todo Vérifier le bon fonctionnement.
                    $links = $instance5->getLinksOnFields('', '', 'f', $instance5->getID(), '', '0');
                    foreach ($links as $link) {
                        $instance6 = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
                        $type = $instance6->getType('all');
                        if ($type == 'image/png'
                            || $type == 'image/jpeg'
                        ) {
                            $xsize = (int)($instance6->getProperty('EXIF/ImageWidth', 'all'));
                            if ($xsize == 0)
                                $xsize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
                            $ysize = (int)($instance6->getProperty('EXIF/ImageHeight', 'all'));
                            if ($ysize == 0)
                                $ysize = (int)($instance6->getProperty('COMPUTED.Width', 'all'));
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
        if ($xyimg < $best)
            return '0';
        return $bestimg;
    }


    public function write(): bool {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkBooleanOptions(array('permitWrite','permitWriteObject', 'permitWriteLink', 'permitWriteEntity'))) {
            $this->_metrologyInstance->addLog('Write object no authorized', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ca6f5f59');
            return false;
        }

        $ok = $this->_ioInstance->setObject($this->_id, $this->_publicKey);
        $this->_metrologyInstance->addAction('addent', $this->_id, $ok);

        return $ok;
    }



    // Désactivation des fonctions de protection.

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getMarkProtected(): bool { return false; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function getReloadMarkProtected(): bool { return false; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getProtectedID(): string { return '0'; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return string
     */
    public function getUnprotectedID(): string { return $this->_id; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @param bool $obfuscated
     * @return boolean
     */
    public function setProtected(bool $obfuscated = false): bool { return false; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return boolean
     */
    public function setUnprotected(): bool { return false; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @param string $entity
     * @return boolean
     */
    public function setProtectedTo(string $entity): bool{ return false; }

    /**
     * Fonction pour les objets, désactivée pour les groupes.
     *
     * @return array
     */
    public function getProtectedTo(): array { return array(); }


    /**
     * Retourne la liste des liens vers les groupes dont l'entité est à l'écoute.
     *
     * @param string $socialClass
     * @return array:Link
     */
    public function getListIsFollowerOfGroupLinks(string $socialClass = 'myself'): array {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getLinksOnFields(
            '',
            '',
            'l',
            $this->_id,
            '',
            $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_GROUP);
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
    public function getListIsFollowerOnGroupID(string $socialClass = 'myself'): array {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getLinksOnFields(
            '',
            '',
            'l',
            $this->_id,
            '',
            $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_GROUPE_SUIVI)
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_GROUP);
            if ($instance->getIsGroup('all')) {
                $list[$link->getParsed()['bl/rl/nid2']] = $link->getParsed()['bl/rl/nid2'];
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
    public function getListIsFollowerOfConversationLinks(string $socialClass = 'myself'): array {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getLinksOnFields(
            '',
            '',
            'l',
            $this->_id,
            '',
            $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE)
        );

        // Fait un tri par pertinence sociale.
        $this->_socialInstance->arraySocialFilter($links, $socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_CONVERSATION);
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
    public function getListIsFollowerOnConversationID(string $socialClass = 'myself'): array {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $list = array();

        // Liste tous les liens de définition des membres du groupe.
        $links = $this->getListIsFollowerOfConversationLinks($socialClass);

        // Tri les objets de type groupe.
        foreach ($links as $i => $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_CONVERSATION);
            if ($instance->getIsConversation('all')) {
                $list[$link->getParsed()['bl/rl/nid2']] = $link->getParsed()['bl/rl/nid2'];
            }
        }

        return $list;
    }


    /**
     * Affiche la partie du menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void {
        ?>

        <li><a href="#oe">OE / Entité</a>
            <ul>
                <li><a href="#oea">OEA / Types d'entités</a></li>
                <li><a href="#oen">OEM / Entités Maîtresses</a></li>
                <li><a href="#oen">OEN / Nommage</a></li>
                <li><a href="#oep">OEP / Protection</a></li>
                <li><a href="#oed">OED / Dissimulation</a></li>
                <li><a href="#oel">OEL / Liens</a></li>
                <li><a href="#oec">OEC / Création</a></li>
                <li><a href="#oes">OES / Stockage</a></li>
                <li><a href="#oet">OET / Transfert</a></li>
                <li><a href="#oer">OER / Réservation</a></li>
                <li><a href="#oei">OEI / Implémentation</a>
                    <ul>
                        <li><a href="#oeio">OEIO / Implémentation des Options</a></li>
                        <li><a href="#oeia">OEIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
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
    static public function echoDocumentationCore(): void {
        ?>

        <?php Displays::docDispTitle(2, 'oe', 'Entité'); ?>
        <p>L’entité est un objet caractéristique. Elle dispose de :</p>
        <ul>
            <li>Une clé cryptographique publique par laquelle elle est identifiée ;</li>
            <li>Une ou plusieurs clés cryptographiques privées par lesquelles elle peut générer des liens (actions) et
                manipuler des données chiffrées (encrypted) et dissimulées (obfuscated).</li>
        </ul>
        <p>Une entité déverrouillée est appelée entité connectée. Le déverrouillage d'une entité est techniquement le
            déverrouillage de la valeur de sa clé cryptographique privée.</p>
        <p>L’indication de la fonction de prise d’empreinte (hash) ainsi que le type de biclé cryptographique sont
            impératifs. Le lien est identique à celui défini pour un objet.</p>
        <p>Pour l'instant, seul le type RSA est supporté, mais à l'avenir tout mécanisme de chiffrement asymétrique
            pourra être supporté.</p>
        <p>Le type mime <code>mime-type:application/x-pem-file</code> est suffisant pour indiquer que cet objet est une
            entité. <i>Des valeurs équivalentes pourront être définies ultérieurement</i>.</p>
        <p>Toutes les autres indications sont optionnelles.</p>

        <?php Displays::docDispTitle(3, 'oea', "Types d'entités"); ?>
        <p>La bibliothèque distingue plusieurs niveaux d'entités :</p>
        <ol>
            <li>Objet entité : C'est un objet auquel on donne des caractéristiques de visualisation propres à une
                entité. Cela permet de manipuler des entités comme des objets, mais avec des actions spécifiques.</li>
            <li>Entité courante : Cela désigne l'objet entité que l'on est en train d'observer.</li>
            <li>Entité serveur : C'est l'entité de l'instance du serveur sur lequel tourne le code.</li>
            <li>Entité par défaut : Lorsque l'on se connecte sur un serveur, c'est l'entité fantôme qui va être utilisée
                par défaut en place de l'entité serveur. Elle peut être changée avec l'option <i>defaultEntity</i>.</li>
            <li>Entité fantôme : Cette façon de désigner une entité permet de voir son empreinte, c'est se mettre à sa
                place en termes de point de vue. Le fantôme (ghost) est une référence à l'insufflation d'une âme dans
                une coquille vide pour lui donner vie. Cependant, ce n'est pas encore une entité connectée.</li>
            <li>Entité connectée : Depuis une entité fantôme, lorsque l'on déverrouille la valeur de sa clé
                cryptographique privée, généralement avec un mot de passe, on permet à cette entité de générer des liens
                et manipuler des données chiffrées et dissimulées.</li>
            <li>Entité maîtresse : C'est une entité spéciale, dite autorité globale ou locale, avec des rôles importants
                prédéfinis par la bibliothèque.</li>
        </ol>

        <?php Displays::docDispTitle(3, 'oem', 'Entités Maîtresses'); ?>
        <p>La bibliothèque utilise actuellement plusieurs entités spéciales, dites autorités maîtresses, avec des rôles
            prédéfinis :</p>
        <ol>
            <li>Maître du tout. L'instance actuelle s'appelle
                <a href="<?php echo References::PUPPETMASTER_URL; ?>">
                    <?php echo \Nebule\Library\References::PUPPETMASTER_NAME; ?></a>.
                Voir <a href="#cam">CAM</a>.</li>
            <li>Maître de la sécurité. L'instance actuelle s'appelle
                <a href="<?php echo References::SECURITY_MASTER_URL; ?>">
                    <?php echo \Nebule\Library\References::SECURITY_MASTER_NAME; ?></a>.
                Voir <a href="#cams">CAMS</a>.</li>
            <li>Maître du code. L'instance actuelle s'appelle
                <a href="<?php echo References::CODE_MASTER_URL; ?>">
                    <?php echo \Nebule\Library\References::CODE_MASTER_NAME; ?></a>.
                Voir <a href="#camc">CAMC</a>.</li>
            <li>Maître de l'annuaire. L'instance actuelle s'appelle
                <a href="<?php echo References::DIRECTORY_MASTER_URL; ?>">
                    <?php echo \Nebule\Library\References::DIRECTORY_MASTER_NAME; ?></a>.
                Voir <a href="#cama">CAMA</a>.</li>
            <li>Maître du temps. L'instance actuelle s'appelle
                <a href="<?php echo References::TIME_MASTER_URL; ?>">
                    <?php echo \Nebule\Library\References::TIME_MASTER_NAME; ?></a>.
                Voir <a href="#camt">CAMT</a>.</li>
        </ol>
        <p>Pour chaque catégorie, il peut y avoir plusieurs entités concurrentes reconnues simultanément. Actuellement
            seul un maître du tout est géré à la fois.</p>
        <p>Il est possible de déléguer des droits supplémentaires à des entités locales. L'entité serveur peut devenir
            autorité locale avec l'option <i>permitServerEntityAsAuthority</i>. L'entité par défaut peut devenir
            autorité locale avec l'option <i>permitDefaultEntityAsAuthority</i>.</p>

        <?php Displays::docDispTitle(3, 'oen', 'Nommage'); ?>
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

        <?php Displays::docDispTitle(3, 'oep', 'Protection'); ?>
        <p>La protection d'une entité n'est pas supportée.</p>
        <p>Cependant, à l'avenir, il serait possible d'avoir une entité fille protégée par une entité principale. Dans
            ce cas l'entité fille ne pourrait être manipulée qu'une fois l'entité principale déverrouillée.</p>

        <?php Displays::docDispTitle(3, 'oed', 'Dissimulation'); ?>
        <p>La dissimulation de liens n'est pas supportée.</p>

        <?php Displays::docDispTitle(3, 'oel', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oec', 'Création'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La première étape consiste en la génération d’une biclé (public/privé) cryptographique. Cette biclé peut être
            de type RSA ou équivalent. Aujourd’hui, seul RSA est reconnu.</p>
        <p>On extrait la clé publique de la biclé. Le calcul de l’empreinte cryptographique de la clé publique donne
            l’identifiant de l’entité. On écrit dans les objets (o/*) l’objet avec comme contenu la clé publique et
            comme id son empreinte cryptographique.</p>
        <p>On extrait la clé privée de la biclé. Il est fortement conseillé lors de l’extraction de protéger tout de
            suite la clé privée avec un mot de passe. On écrit dans les objets (o/*) l’objet avec comme contenu la clé
            privée et comme id son empreinte cryptographique (différente de celle de la clé publique).</p>
        <p>À partir de maintenant, la biclé n’est plus nécessaire. Il faut le supprimer avec un effacement
            sécurisé.</p>
        <p>Pour que l’objet soit reconnu comme entité, il faut créer les liens correspondants.</p>
        <ul>
            <li>Lien 1 :
                <ul>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de ‘<?php echo References::REFERENCE_OBJECT_ENTITY; ?>’</li>
                    <li>Empreinte de ‘<?php echo References::REFERENCE_NEBULE_OBJET_TYPE; ?>’</li>
                </ul>
            </li>
            <li>Lien 2 :
                <ul>
                    <li>Lien de type <code>l</code></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Empreinte de ‘<?php echo References::REFERENCE_OBJECT_ENTITY; ?>’</li>
                    <li>Empreinte de ‘<?php echo References::REFERENCE_NEBULE_OBJET_TYPE; ?>’</li>
                </ul>
            </li>
            <li>Lien 3 :
                <ul>
                    <li>Lien de type <code>f</code> ;</li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">privée</span></li>
                    <li>Identifiant de la clé <span style="font-weight:bold;">publique</span></li>
                    <li>Empreinte de ‘<?php echo References::REFERENCE_PRIVATE_KEY; ?>’.</li>
                </ul>
            </li>
        </ul>
        <p>C’est le minimum vital pour une entité. Ensuite, d’autres propriétés peuvent être ajoutées à l’entité (id clé
            publique) comme son nom, son type, etc…</p>
        <p>Si le mot de passe de la clé privée est défini par l’utilisateur demandeur de la nouvelle entité, il faut
            supprimer ce mot de passe avec un effacement sécurisé.</p>
        <p>Si le mot de passe de la clé privée a été généré, donc que la nouvelle entité est esclave d’une entité
            maître, le mot de passe doit être stocké dans un objet chiffré pour l’entité maître. Et il faut générer un
            lien reliant l’objet de mot de passe à la clé privée de la nouvelle entité.</p>

        <?php Displays::docDispTitle(3, 'oes', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(3, 'oet', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oer', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oei', 'Implémentation'); ?>

        <?php Displays::docDispTitle(4, 'oeio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oeia', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oeo', 'Oubli'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté, mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
