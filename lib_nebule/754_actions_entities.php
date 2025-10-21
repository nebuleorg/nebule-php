<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on entities.
 * This class must not be used directly but via the entry point Actions->getInstanceActionsEntities().
 * TODO must be refactored!
 *
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
    const CHANGE = 'chent';
    const CHANGE_NAME = 'chentnam';
    const CHANGE_PREFIX = 'chentprefix';
    const CHANGE_SUFFIX = 'chentsuffix';
    const CHANGE_FIRSTNAME = 'chentfnam';
    const CHANGE_NICKNAME = 'chentnnam';
    const CHANGE_PASSWORD = 'chentpwd';
    const CHANGE_PASSWORD1 = 'chentpwd1';
    const CHANGE_PASSWORD2 = 'chentpwd2';



    public function initialisation(): void {}
    public function genericActions(): void {

        if ($this->getHaveInput(self::SYNCHRONIZE))
            $this->_synchronize();
        if ($this->getHaveInput(self::CHANGE))
            $this->_change();
        if ($this->getHaveInput(self::CHANGE_PASSWORD))
            $this->_changePassword();

        $this->_extractActionSynchronizeNewEntity();
        if ($this->_actionSynchronizeNewEntityID != '')
            $this->_SynchronizeNew();
    }
    public function specialActions(): void {
        if (($this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            || $this->_entitiesInstance->getConnectedEntityIsUnlocked())
            && $this->getHaveInput(self::CREATE)
        )
            $this->_create();
    }



    private ?Entity $_actionSynchronizeEntityInstance = null;
    public function getSynchronizeEntityInstance(): ?Entity { return $this->_actionSynchronizeEntityInstance; }
    private function _synchronize(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // TODO DISABLED for refactor
        return;

        $arg = $this->getFilterInput(self::SYNCHRONIZE, FILTER_FLAG_ENCODE_LOW);
        if (!Node::checkNID($arg))
            return;
        $this->_actionSynchronizeEntityInstance = $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_ENTITY);

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

    private string $_actionSynchronizeNewEntityID = '';
    private string $_actionSynchronizeNewEntityURL = '';
    private ?Entity $_actionSynchronizeNewEntityInstance = null;
    public function getSynchronizeNewEntityInstance(): ?Entity { return $this->_actionSynchronizeNewEntityInstance; }
    private function _extractActionSynchronizeNewEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeNewEntity'))
            return;


        // TODO DISABLED for refactor
        return;

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
    private function _SynchronizeNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // TODO DISABLED for refactor
        return;

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



    private bool $_change = false;
    public function getChange(): bool { return $this->_change; }
    private function _change(): void { // TODO do the same on 753_actions_objects.php
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_change = false;
        $instance = $this->_nebuleInstance->getCurrentEntityInstance();
        $_changeName = $this->getFilterInput(self::CHANGE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        $_changePrefix = $this->getFilterInput(self::CHANGE_PREFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
        $_changeSuffix = $this->getFilterInput(self::CHANGE_SUFFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
        $_changeFirstname = $this->getFilterInput(self::CHANGE_FIRSTNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
        $_changeNickname = $this->getFilterInput(self::CHANGE_NICKNAME, FILTER_FLAG_NO_ENCODE_QUOTES);

        if ($_changeName  != '') {
            $this->_metrologyInstance->addLog('change entity ' . References::REFERENCE_NEBULE_OBJET_NOM . '=' . $_changeName, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '78517959');
            $instance->setProperty(References::REFERENCE_NEBULE_OBJET_NOM, $_changeName);
            $this->_change = true;
        }
        if ($_changePrefix  != '') {
            $this->_metrologyInstance->addLog('change entity ' . References::REFERENCE_NEBULE_OBJET_PREFIX . '=' . $_changePrefix, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '486f6813');
            $instance->setProperty(References::REFERENCE_NEBULE_OBJET_PREFIX, $_changePrefix);
            $this->_change = true;
        }
        if ($_changeSuffix  != '') {
            $this->_metrologyInstance->addLog('change entity ' . References::REFERENCE_NEBULE_OBJET_SUFFIX . '=' . $_changeSuffix, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '30d08e02');
            $instance->setProperty(References::REFERENCE_NEBULE_OBJET_SUFFIX, $_changeSuffix);
            $this->_change = true;
        }
        if ($_changeFirstname  != '') {
            $this->_metrologyInstance->addLog('change entity ' . References::REFERENCE_NEBULE_OBJET_PRENOM . '=' . $_changeFirstname, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'ceca7fb5');
            $instance->setProperty(References::REFERENCE_NEBULE_OBJET_PRENOM, $_changeFirstname);
            $this->_change = true;
        }
        if ($_changeNickname  != '') {
            $this->_metrologyInstance->addLog('change entity ' . References::REFERENCE_NEBULE_OBJET_SURNOM . '=' . $_changeNickname, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '7af6f9fe');
            $instance->setProperty(References::REFERENCE_NEBULE_OBJET_SURNOM, $_changeNickname);
            $this->_change = true;
        }
    }



    private bool $_changePassword = false;
    public function getChangePassword(): bool { return $this->_changePassword; }
    private function _changePassword(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_changePassword = false;
        $instance = $this->_entitiesInstance->getConnectedEntityInstance();
        $_changePassword1 = $this->getFilterInput(self::CHANGE_PASSWORD1, FILTER_FLAG_NO_ENCODE_QUOTES);
        $_changePassword2 = $this->getFilterInput(self::CHANGE_PASSWORD2, FILTER_FLAG_NO_ENCODE_QUOTES);

        if ($_changePassword1 != '' && $_changePassword2 != '') {
            if ($_changePassword1 == $_changePassword2) {
                $this->_metrologyInstance->addLog('change entity password', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '652ba93f');
                $instance->setNewPrivateKeyPassword($_changePassword1);
                $this->_changePassword = true;
            } else {
                $this->_metrologyInstance->addLog('passwords not match', Metrology::LOG_LEVEL_ERROR, __METHOD__, '11a6587c');
            }
        }
    }



    private bool $_create = false;
    private string $_createName = '';
    private string $_createPassword = '';
    private string $_createEID = '0';
    private string $_createKID = '0';
    private bool $_createObfuscated = false;
    private ?Entity $_createInstance = null;
    private ?Node $_createKeyInstance = null;
    private bool $_createError = false;
    private string $_createErrorMessage = 'initialisation creation';
    public function getCreate(): bool { return $this->_create; }
    public function getCreateEID(): string { return $this->_createEID; }
    public function getCreateKID(): string { return $this->_createKID; }
    public function getCreateInstance(): ?Entity { return $this->_createInstance; }
    public function getCreateKeyInstance(): ?Node { return $this->_createKeyInstance; }
    public function getCreateError(): bool { return $this->_createError; }
    public function getCreateErrorMessage(): string { return $this->_createErrorMessage; }
    private function _create(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ((!$this->_entitiesInstance->getConnectedEntityIsUnlocked() && !$this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity'))
            || !$this->_tokenizeInstance->checkActionToken()
            || !$this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateEntity')
        )
            return;

        if ($this->getHaveInput(self::CREATE)) {
            $this->_create = true;
            $_createPrefix = $this->getFilterInput(self::CREATE_PREFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $_createSuffix = $this->getFilterInput(self::CREATE_SUFFIX, FILTER_FLAG_NO_ENCODE_QUOTES);
            $_createFirstname = $this->getFilterInput(self::CREATE_FIRSTNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $_createNickname = $this->getFilterInput(self::CREATE_NICKNAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->_createName = $this->getFilterInput(self::CREATE_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argPasswd1 = $this->getFilterInput(self::CREATE_PASSWORD1, FILTER_FLAG_NO_ENCODE_QUOTES, false, true);
            $argPasswd2 = $this->getFilterInput(self::CREATE_PASSWORD2, FILTER_FLAG_NO_ENCODE_QUOTES, false, true);
            $_createType = $this->getFilterInput(self::CREATE_TYPE, FILTER_FLAG_NO_ENCODE_QUOTES);
            $_createAlgo = $this->getFilterInput(self::CREATE_ALGORITHM, FILTER_FLAG_NO_ENCODE_QUOTES); // FIXME

            if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                $this->_createObfuscated = filter_has_var(INPUT_POST, self::CREATE_OBFUSCATE_LINKS);
            else
                $this->_createObfuscated = false;

            if ($argPasswd1 == $argPasswd2)
                $this->_createPassword = $argPasswd1;
            else {
                $this->_metrologyInstance->addLog('passwords not match', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9d57a71d');
                $this->_createError = true;
                $this->_createErrorMessage = 'passwords not match';
            }

            if ($_createType != 'human' && $_createType != 'robot')
                $_createType = '';

            if (! in_array($_createAlgo, $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)))
                $_createAlgo = $this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm');
            if (! in_array($_createAlgo, $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)))
                $_createAlgo = $this->_cryptoInstance->getAlgorithmList(Crypto::TYPE_ASYMMETRIC)[0];

            $this->_metrologyInstance->addLog('create entity algo=' . $_createAlgo, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'ea998a6d');
            $algoArray = explode('.', $_createAlgo);
            $algo = $algoArray[0];
            $size = (int)$algoArray[1];
            $instance = new Entity($this->_nebuleInstance, 'new');
            $instance->createNewEntity($algo, $size);

            if (is_a($instance, 'Nebule\Library\Entity') && $instance->getID() != '0') {
                $this->_metrologyInstance->addLog('entity created', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'd23d6cb3');
                $instance->setCreateWrite();
                $instance->setNewPrivateKeyPassword($this->_createPassword);
                $this->_createError = false;

                $this->_createEID = $instance->getID();
                if ($this->_createEID == '0') {
                    $this->_createError = true;
                    $this->_createErrorMessage = 'null entity created';
                    $this->_metrologyInstance->addLog('null entity created', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'd8d70c0f');
                    return;
                }
                $this->_createKID = $instance->getPrivateKeyOID();
                if ($this->_createKID == '0') {
                    $this->_createError = true;
                    $this->_createErrorMessage = 'null entity key created';
                    $this->_metrologyInstance->addLog('null entity key created', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f0a3c2f6');
                    return;
                }
                $this->_createInstance = $instance;
                $this->_createKeyInstance = new Node($this->_nebuleInstance, $this->_createKID);
                $this->_metrologyInstance->addLog('create private key ' . $this->_createKeyInstance->getID() . ' with password', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6a7c4990');

                if ($this->_createName == '' && $_createFirstname != '') {
                    $this->_createName = $_createFirstname;
                    $_createFirstname = '';
                }

                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_NOM, $this->_createName);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_PRENOM, $_createFirstname);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_SURNOM, $_createNickname);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_PREFIX, $_createPrefix);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_SUFFIX, $_createSuffix);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_ENTITE_TYPE, $_createType);
                $this->_writeEntitySelfProperty($instance, References::REFERENCE_NEBULE_OBJET_ENTITE_ALGORITHME, $_createAlgo);

                $this->_nebuleInstance->getCacheInstance()->unsetEntityOnCache($this->_createEID);
                $this->_createInstance = $this->_cacheInstance->newNode($this->_createEID, \Nebule\Library\Cache::TYPE_ENTITY);
                $this->_createInstance->setNewPrivateKeyPassword($this->_createPassword);
                $this->_nebuleInstance->setCurrentEntityInstance($instance);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayInlineErrorFace();
                $this->_metrologyInstance->addLog('fail to generate', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'eec8ce0b');
                $this->_createError = true;
                $this->_createErrorMessage = 'fail to generate';
            }
        }
    }
    private function _writeEntitySelfProperty(Entity $instance, string $reference, string $content): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($content != '') {
            $this->_metrologyInstance->addLog('set entity ' . $reference . '=' . $content, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '81385b9d');
            $instance->setSelfProperty($reference, $content);
        }
    }
}