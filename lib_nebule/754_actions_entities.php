<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on entities.
 * TODO must be refactored!
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsEntities extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const SYNCHRONIZE = 'actsynent';
    const SYNCHRONIZE_NEW = 'actsynnewent';
    const CREATE = 'creaent';
    const CREATE_PREFIX = 'creaentprefix';
    const CREATE_SUFFIX = 'creaentsuffix';
    const CREATE_FIRSTNAME = 'creaentfstnam';
    const CREATE_NICKNAME = 'creaentniknam';
    const CREATE_NAME = 'creaentnam';
    const CREATE_PASSWORD1 = 'creaentpwd1';
    const CREATE_PASSWORD2 = 'creaentpwd2';
    const CREATE_ALGORITHM = 'creaentalgo';
    const CREATE_TYPE = 'creaenttype';
    const CREATE_AUTONOMY = 'creaentaut';
    const CREATE_OBFUSCATE_LINKS = 'creaentobf';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionSynchronizeEntity();
        if ($this->_actionSynchronizeEntityInstance != '')
            $this->_actionSynchronizeEntity();

        $this->_extractActionSynchronizeNewEntity();
        if ($this->_actionSynchronizeNewEntityID != '')
            $this->_actionSynchronizeNewEntity();
    }
    public function specialActions(): void {
        if ($this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            || $this->_entitiesInstance->getConnectedEntityIsUnlocked()
        )
            $this->_extractActionCreateEntity();
        if ($this->_actionCreateEntity)
            $this->_actionCreateEntity();
    }



    protected ?Entity $_actionSynchronizeEntityInstance = null;
    public function getSynchronizeEntityInstance(): ?Entity
    {
        return $this->_actionSynchronizeEntityInstance;
    }
    protected function _extractActionSynchronizeEntity(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2ec437bf');

        $arg = $this->getFilterInput(self::SYNCHRONIZE, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeEntityInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_ENTITY);
    }
    protected function _actionSynchronizeEntity(): void
    {
        $this->_metrologyInstance->addLog('action synchronize entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'f41d4b64');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeEntityInstance);

        // Synchronisation des liens (l'objet est forcément présent).
        $this->_actionSynchronizeEntityInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeEntityInstance->getLinksOnFields('', '', 'l', $this->_actionSynchronizeEntityInstance->getID(), '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeEntityInstance->getLinksOnFields('', '', 'l', '', $this->_actionSynchronizeEntityInstance->getID(), '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
    }

    protected string $_actionSynchronizeNewEntityID = '';
    protected string $_actionSynchronizeNewEntityURL = '';
    protected ?Entity $_actionSynchronizeNewEntityInstance = null;
    public function getSynchronizeNewEntityInstance(): ?Entity
    {
        return $this->_actionSynchronizeNewEntityInstance;
    }
    protected function _extractActionSynchronizeNewEntity(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeNewEntity'))
            return;

        $this->_metrologyInstance->addLog('extract action synchronize new entity', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

        $arg = $this->getFilterInput(self::SYNCHRONIZE_NEW, FILTER_FLAG_ENCODE_LOW);

        // Extraction de l'URL et stockage pour traitement.
        if ($arg != ''
            && strlen($arg) >= 9
            && ctype_print($arg)
        ) {
            // Décompose l'URL.
            $parseURL = parse_url($arg);
            // Extrait le protocol.
            if (isset($parseURL['scheme'])
                && ($parseURL['scheme'] == 'http'
                    || $parseURL['scheme'] == 'https'
                )
            )
                $scheme = $parseURL['scheme'];
            else {
                // Il manque le protocol, suppose que c'est http.
                $scheme = 'http';
                $parseURL = parse_url($scheme . '://' . $arg);
            }
            // Vérifie les champs de l'URL.
            if ($parseURL['host'] != ''
                && $parseURL['path'] != ''
                && substr($parseURL['path'], 0, 3) == '/o/'
            ) {
                // Extrait l'ID de l'objet de l'entité à synchroniser.
                $id = substr($parseURL['path'], 3);
                $this->_metrologyInstance->addLog('extract action synchronize new entity - ID=' . $id, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
                // Vérifie l'ID.
                if (!$this->_ioInstance->checkObjectPresent($id)
                    && Node::checkNID($id)
                ) {
                    // Si c'est bon on prépare pour la synchronisation.
                    $this->_actionSynchronizeNewEntityID = $id;
                    $this->_actionSynchronizeNewEntityURL = $scheme . '://' . $parseURL['host'];
                    if (isset($parseURL['port'])) {
                        $port = $parseURL['port'];
                        $this->_actionSynchronizeNewEntityURL .= ':' . $port;
                    }
                    $this->_metrologyInstance->addLog('extract action synchronize new entity - URL=' . $this->_actionSynchronizeNewEntityURL, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
                }
            }
        }
    }
    protected function _actionSynchronizeNewEntity(): void
    {
        $this->_metrologyInstance->addLog('action synchronize new entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Vérifie si l'objet est déjà présent.
        $present = $this->_ioInstance->checkObjectPresent($this->_actionSynchronizeNewEntityID);
        // Lecture de l'objet.
        $data = $this->_ioInstance->getObject($this->_actionSynchronizeNewEntityID, Entity::ENTITY_MAX_SIZE, $this->_actionSynchronizeNewEntityURL);
        // Calcul de l'empreinte.
        $hash = $this->getNidFromData($data);
        if ($hash != $this->_actionSynchronizeNewEntityID) {
            $this->_metrologyInstance->addLog('action synchronize new entity - Hash error', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            unset($data);
            echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') .
                $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeNewEntityID) .
                $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_IERR');
            $this->_actionSynchronizeNewEntityID = '';
            $this->_actionSynchronizeNewEntityURL = '';
            return;
        }
        // Ecriture de l'objet.
        $this->_ioInstance->setObject($this->_actionSynchronizeNewEntityID, $data);

        $this->_actionSynchronizeNewEntityInstance = $this->_cacheInstance->newNode($this->_actionSynchronizeNewEntityID, \Nebule\Library\Cache::TYPE_ENTITY);

        if (!$this->_actionSynchronizeNewEntityInstance->getTypeVerify()) {
            $this->_metrologyInstance->addLog('action synchronize new entity - Not entity', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');
            if (!$present)
                $this->_actionSynchronizeNewEntityInstance->deleteObject();
        }

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNENT') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeNewEntityInstance);

        // Synchronisation des liens.
        $this->_actionSynchronizeNewEntityInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeNewEntityInstance->getLinksOnFields('', '', 'l', $hash, '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeNewEntityInstance->getLinksOnFields('', '', 'l', '', $hash, '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
    }



    protected bool $_actionCreateEntity = false;
    protected string $_actionCreateEntityPrefix = '';
    protected string $_actionCreateEntitySuffix = '';
    protected string $_actionCreateEntityFirstname = '';
    protected string $_actionCreateEntityNickname = '';
    protected string $_actionCreateEntityName = '';
    protected string $_actionCreateEntityPassword = '';
    protected string $_actionCreateEntityType = '';
    protected string $_actionCreateEntityAlgo = '';
    protected string $_actionCreateEntityID = '0';
    protected string $_actionCreateEntityKeyID = '0';
    protected bool $_actionCreateEntityObfuscateLinks = false;
    protected ?Entity $_actionCreateEntityInstance = null;
    protected ?Node $_actionCreateEntityKeyInstance = null;
    protected bool $_actionCreateEntityError = false;
    protected string $_actionCreateEntityErrorMessage = 'initialisation creation';
    public function getCreateEntity(): bool { return $this->_actionCreateEntity; }
    public function getCreateEntityID(): string { return $this->_actionCreateEntityID; }
    public function getCreateEntityKeyID(): string { return $this->_actionCreateEntityKeyID; }
    public function getCreateEntityInstance(): ?Entity { return $this->_actionCreateEntityInstance; }
    public function getCreateEntityKeyInstance(): ?Node { return $this->_actionCreateEntityKeyInstance; }
    public function getCreateEntityError(): bool { return $this->_actionCreateEntityError; }
    public function getCreateEntityErrorMessage(): string { return $this->_actionCreateEntityErrorMessage; }
    protected function _extractActionCreateEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ((!$this->_entitiesInstance->getConnectedEntityIsUnlocked() && !$this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
            || !$this->_tokenizeInstance->checkActionToken()
            || !$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateEntity')
        )
            return;

        if ($this->getHaveInput(self::CREATE)) {
            $this->_actionCreateEntity = true;
            $this->_actionCreateEntityPrefix = $this->getFilterInput(self::CREATE_PREFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_actionCreateEntitySuffix = $this->getFilterInput(self::CREATE_SUFFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_actionCreateEntityFirstname = $this->getFilterInput(self::CREATE_FIRSTNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_actionCreateEntityNickname = $this->getFilterInput(self::CREATE_NICKNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_actionCreateEntityName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argPasswd1 = $this->getFilterInput(self::CREATE_PASSWORD1, FILTER_FLAG_NO_ENCODE_QUOTES, false, true);
            $argPasswd2 = $this->getFilterInput(self::CREATE_PASSWORD2, FILTER_FLAG_NO_ENCODE_QUOTES, false, true);
            $this->_actionCreateEntityType = $this->getFilterInput(self::CREATE_TYPE, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_actionCreateEntityAlgo = $this->getFilterInput(self::CREATE_ALGORITHM, FILTER_FLAG_NO_ENCODE_QUOTES); // FIXME

            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_actionCreateEntityObfuscateLinks = filter_has_var(INPUT_POST, self::CREATE_OBFUSCATE_LINKS);
            else
                $this->_actionCreateEntityObfuscateLinks = false;

            if ($argPasswd1 == $argPasswd2)
                $this->_actionCreateEntityPassword = $argPasswd1;
            else {
                $this->_metrologyInstance->addLog('passwords not match', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9d57a71d');
                $this->_actionCreateEntityError = true;
                $this->_actionCreateEntityErrorMessage = 'passwords not match';
            }

            if ($this->_actionCreateEntityType != 'human' && $this->_actionCreateEntityType != 'robot')
                $this->_actionCreateEntityType = '';

            if (! in_array($this->_actionCreateEntityAlgo, $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)))
                $this->_actionCreateEntityAlgo = $this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm');
            if (! in_array($this->_actionCreateEntityAlgo, $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)))
                $this->_actionCreateEntityAlgo = $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)[0];
        }
    }
    protected function _actionCreateEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_metrologyInstance->addLog('action create entity algo=' . $this->_actionCreateEntityAlgo, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'ea998a6d');
        $algoArray = explode('.', $this->_actionCreateEntityAlgo);
        $algo = $algoArray[0];
        $size = (int)$algoArray[1];
        $instance = new Entity($this->_nebuleInstance, 'new');
        $instance->createNewEntity($algo, $size);

        if (is_a($instance, 'Nebule\Library\Entity') && $instance->getID() != '0') {
            $this->_metrologyInstance->addLog('action entity created', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'd23d6cb3');
            $instance->setCreateWrite();
            $instance->setNewPrivateKeyPassword($this->_actionCreateEntityPassword);
            $this->_actionCreateEntityError = false;

            $this->_actionCreateEntityID = $instance->getID();
            if ($this->_actionCreateEntityID == '0') {
                $this->_actionCreateEntityError = true;
                $this->_actionCreateEntityErrorMessage = 'null entity created';
                $this->_metrologyInstance->addLog('null entity created', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd8d70c0f');
                return;
            }
            $this->_actionCreateEntityKeyID = $instance->getPrivateKeyOID();
            if ($this->_actionCreateEntityKeyID == '0') {
                $this->_actionCreateEntityError = true;
                $this->_actionCreateEntityErrorMessage = 'null entity key created';
                $this->_metrologyInstance->addLog('null entity key created', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f0a3c2f6');
                return;
            }
            $this->_actionCreateEntityInstance = $instance;
            $this->_actionCreateEntityKeyInstance = new Node($this->_nebuleInstance, $this->_actionCreateEntityKeyID);
            $this->_metrologyInstance->addLog('create private key ' . $this->_actionCreateEntityKeyInstance->getID() . ' with password', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6a7c4990');

            if ($this->_actionCreateEntityName == '' && $this->_actionCreateEntityFirstname != '') {
                $this->_actionCreateEntityName = $this->_actionCreateEntityFirstname;
                $this->_actionCreateEntityFirstname = '';
            }

            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_NOM, $this->_actionCreateEntityName);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_PRENOM, $this->_actionCreateEntityFirstname);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_SURNOM, $this->_actionCreateEntityNickname);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_PREFIX, $this->_actionCreateEntityPrefix);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_SUFFIX, $this->_actionCreateEntitySuffix);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_ENTITE_TYPE, $this->_actionCreateEntityType);
            $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_ENTITE_ALGORITHME, $this->_actionCreateEntityAlgo);

            $this->_nebuleInstance->getCacheInstance()->unsetEntityOnCache($this->_actionCreateEntityID);
            $this->_actionCreateEntityInstance = $this->_cacheInstance->newNode($this->_actionCreateEntityID, \Nebule\Library\Cache::TYPE_ENTITY);
            $this->_actionCreateEntityInstance->setNewPrivateKeyPassword($this->_actionCreateEntityPassword);
            $this->_nebuleInstance->setCurrentEntityInstance($instance);
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
            $this->_metrologyInstance->addLog('fail to generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'eec8ce0b');
            $this->_actionCreateEntityError = true;
            $this->_actionCreateEntityErrorMessage = 'fail to generate';
        }
    }
    private function _writeEntitySelfProperty(Entity $instance, string $reference, string $content): void {
        if ($content != '') {
            $this->_metrologyInstance->addLog('action set entity ' . $reference . '=' . $content, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '81385b9d');
            $instance->setSelfProperty($reference, $content);
        }
    }
}