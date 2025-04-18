<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Local entities class for the nebule library.
 * - Server entity : local entity for the nebule instance of server that can manage local configuration for all entities
 *   on this nebule instance.
 * - Default entity : on first connect to the nebule instance, this is the entity to use by default before eventually
 *   change. Can be modified by configuration (defaultCurrentEntity).
 * - Current entity : which entity used to see all things (point of vue). Before authenticate to connected entity you
 *   have to select the current entity.
 * - Connected entity : entity unlocked that can do signed actions and see/change enciphered and obfuscated things.
 * - Unlocked entities : list of inherited entities automatically unlocked with the connected entity. This permit to
 *   switch quickly from one to other to do some actions in other context. FIXME not functional now.
 * Must be serialized on PHP session with nebule class.
 *
 * @author    Projet nebule
 * @license   GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Entities extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_currentEntityPrivateKey',
        '_currentEntityPrivateKeyInstance',
        '_currentEntityIsUnlocked',
    );

    protected function _initialisation(): void
    {
        $this->_findServerEntity();
        $this->_authoritiesInstance->setServerEntityAsAuthorities($this->_serverEntityInstance);
        $this->_recoveryInstance->setServerEntityAsRecovery($this->_serverEntityInstance);
        $this->_findDefaultEntity();
        $this->_authoritiesInstance->setDefaultEntityAsAuthorities($this->_defaultEntityInstance);
        $this->_recoveryInstance->setDefaultEntityAsRecovery($this->_defaultEntityInstance);
        $this->_authoritiesInstance->setLinkedLocalAuthorities($this);
        $this->_recoveryInstance->setLinkedRecoveryEntities($this);
        $this->_findCurrentEntity();
        $this->_findConnectedEntity();
        $this->_findCurrentEntityPrivateKey();
        $this->_findCurrentEntityPassword();
    }



    private string $_serverEntityID = '';
    private ?Entity $_serverEntityInstance = null;

    /**
     * Try to find server entity from :
     * 1: from PHP session;
     * 2: from file;
     * 3: last keep puppetmaster instance.
     * @return void
     */
    private function _findServerEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleHostEntityInstance');
        if ($instance->getID() == '0')
            $instance = $this->_findServerEntityFromFile();
        if ($instance->getID() == '0')
            $instance = $this->_authoritiesInstance->getPuppetmasterInstance();
        $this->_serverEntityInstance = $instance;
        $this->_serverEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('server entity EID=' . $this->_serverEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cdfd0e02');
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleHostEntityInstance', $this->_serverEntityInstance);
    }

    private function _findServerEntityFromFile(): Node
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $filecontent = file_get_contents(References::COMMAND_LOCAL_ENTITY_FILE);
        if (is_bool($filecontent))
            $filecontent = '';
        $filecontent = strtok($filecontent, "\n");
        if (is_bool($filecontent))
            $filecontent = '';
        $arg = filter_var(trim($filecontent), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (is_bool($arg) || $arg === null || $arg == '')
            $arg = '0';

        if (!Node::checkNID($arg, false, false)
            || !$this->_ioInstance->checkObjectPresent($arg)
            || !$this->_ioInstance->checkLinkPresent($arg)
        )
            $arg = '0';

        $this->_metrologyInstance->addLog('find server entity from file EID=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '811a12be');
        return $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_ENTITY);
    }

    public function getServerEntityID(): string
    {
        return $this->_serverEntityID;
    }

    public function getServerEntityInstance(): ?Entity
    {
        return $this->_serverEntityInstance;
    }



    private string $_defaultEntityID = '';
    private ?Entity $_defaultEntityInstance = null;

    /**
     * Try to find default entity from :
     * 1: from PHP session;
     * 2: from option defaultCurrentEntity (on config file);
     * 3: last keep server instance.
     * @return void
     */
    private function _findDefaultEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleDefaultEntityInstance');
        if ($instance->getID() == '0')
            $instance = $this->_findDefaultEntityFromOption();
        if ($instance->getID() == '0')
            $instance = $this->_serverEntityInstance;
        $this->_defaultEntityInstance = $instance;
        $this->_defaultEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('default entity EID=' . $this->_defaultEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '17bc6adc');
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleDefaultEntityInstance', $this->_defaultEntityInstance);
    }

    private function _findDefaultEntityFromOption(): Node
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNode($this->_configurationInstance->getOptionFromEnvironmentAsString('defaultCurrentEntity'), \Nebule\Library\Cache::TYPE_ENTITY);
        $this->_metrologyInstance->addLog('find default entity from config file EID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cf459003');
        return $instance;
    }

    public function getDefaultEntityID(): string
    {
        return $this->_defaultEntityID;
    }

    public function getDefaultEntityInstance(): ?Entity
    {
        return $this->_defaultEntityInstance;
    }



    private string $_currentEntityID = '';
    private ?Entity $_currentEntityInstance = null;
    private string $_currentEntityPrivateKey = '';
    private ?Node $_currentEntityPrivateKeyInstance = null;

    /**
     * Try to find current entity from :
     * 1: from command argument;
     * 2: from PHP session;
     * 3: from option defaultCurrentEntity (on config file);
     * 4: last keep server instance.
     * If not from PHP session, flush previous private key.
     * @return void
     */
    private function _findCurrentEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_findCurrentEntityFromArg();
        if ($instance->getID() == '0') {
            $eid = $this->_sessionInstance->getSessionStoreAsString('nebulePublicKeyEID');
            if ($eid != '') {
                $this->_metrologyInstance->addLog('find current entity key from cache EID=' . $eid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '28ac240d');
                $this->_cacheInstance->newNode($eid, \Nebule\Library\Cache::TYPE_ENTITY);
            }
        } else {
            $this->_currentEntityPrivateKey = '';
            $this->_currentEntityPrivateKeyInstance = null;
            $this->_sessionInstance->setSessionStoreAsString('nebulePrivateKeyOID', '');
        }
        if ($instance->getID() == '0') {
            $instance = $this->_findDefaultEntityFromOption();
            $this->_currentEntityPrivateKey = '';
            $this->_currentEntityPrivateKeyInstance = null;
            $this->_sessionInstance->setSessionStoreAsString('nebulePrivateKeyOID', '');
        }
        if ($instance->getID() == '0')
            $instance = $this->_serverEntityInstance;
        $this->_currentEntityInstance = $instance;
        $this->_currentEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('current entity EID=' . $this->_currentEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd026d625');
        $this->_sessionInstance->setSessionStoreAsString('nebulePublicKeyEID', $this->_currentEntityID);
    }

    private function _findCurrentEntityFromArg(): Node
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Extract anf filter
        if (filter_has_var(INPUT_GET, References::COMMAND_SWITCH_TO_ENTITY)
            && filter_has_var(INPUT_GET, References::COMMAND_SELECT_ENTITY)
        )
            $arg = filter_input(INPUT_GET,
                References::COMMAND_SELECT_ENTITY,
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_ENCODE_LOW);
        elseif (filter_has_var(INPUT_POST, References::COMMAND_SWITCH_TO_ENTITY)
            && filter_has_var(INPUT_POST, References::COMMAND_SELECT_ENTITY)
        )
            $arg = filter_input(INPUT_POST,
                References::COMMAND_SELECT_ENTITY,
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_ENCODE_LOW);
        else
            $arg = '0';

        if ($arg === false || $arg === null)
            $arg = '0';

        if (!Node::checkNID($arg, false, false)
            || !$this->_ioInstance->checkObjectPresent($arg)
            || !$this->_ioInstance->checkLinkPresent($arg)
        )
            $arg = '0';

        $this->_metrologyInstance->addLog('find current entity key from arg EID=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '811a12be');
        return $this->_cacheInstance->newNode($arg, \Nebule\Library\Cache::TYPE_ENTITY);
    }

    public function getCurrentEntityID(): string
    {
        return $this->_currentEntityID;
    }

    public function getCurrentEntityInstance(): ?Entity
    {
        return $this->_currentEntityInstance;
    }



    private function _findCurrentEntityPrivateKey(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $privateKey = $this->_sessionInstance->getSessionStoreAsString('nebulePrivateKeyOID');

        if ($privateKey != ''
            && $privateKey != '0'
        ) {
            $this->_currentEntityPrivateKey = $privateKey;
            $this->_currentEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_currentEntityPrivateKey);
            $this->_metrologyInstance->addLog('reuse current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '75e1c757');
        }
        else {
            if ($this->_currentEntityInstance instanceof Entity) {
                $this->_currentEntityPrivateKey = $this->_currentEntityInstance->getPrivateKeyID();
                if ($this->_currentEntityPrivateKey != '') {
                    $this->_currentEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_currentEntityPrivateKey);
                    $this->_metrologyInstance->addLog('find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6be388ca');
                } else {
                    $this->_currentEntityPrivateKeyInstance = null;
                    $this->_metrologyInstance->addLog('cant find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '1e5bed72');
                }
                $this->_sessionInstance->setSessionStoreAsString('nebulePrivateKeyOID', $this->_currentEntityPrivateKey);
            } else {
                $this->_currentEntityPrivateKey = '';
                $this->_currentEntityPrivateKeyInstance = null;
            }
        }
    }

    public function getCurrentEntityPrivateKeyInstance(): ?Node
    {
        return $this->_currentEntityPrivateKeyInstance;
    }

    public function getCurrentEntityPrivateKey(): string
    {
        return $this->_currentEntityPrivateKey;
    }



    private function _findCurrentEntityPassword(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT)
            || filter_has_var(INPUT_POST, References::COMMAND_AUTH_ENTITY_LOGOUT))
        {
            if ($this->_currentEntityInstance instanceof Entity) {
                $this->_metrologyInstance->addLog('logout ' . $this->_currentEntityID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4efbc71f');
                $this->_currentEntityInstance->unsetPrivateKeyPassword();
                $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
            }
            return;
        }

        if ($this->_currentEntityInstance->getHavePrivateKeyPassword())
            return;

        $arg_get_pwd = filter_input(INPUT_GET, References::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING);
        $arg_post_pwd = filter_input(INPUT_POST, References::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING);

        if ($arg_post_pwd != '')
            $arg_pwd = $arg_post_pwd;
        elseif ($arg_get_pwd != '')
            $arg_pwd = $arg_get_pwd;
        else
            return;

        if ($this->_currentEntityInstance->setPrivateKeyPassword($arg_pwd))
        {
            $this->_metrologyInstance->addLog('login password ' . $this->_currentEntityID . ' OK', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '99ed783e');
            $this->_connectedEntityIsUnlocked = true;
            $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
        } else {
            $this->_metrologyInstance->addLog('login password ' . $this->_currentEntityID . ' NOK', Metrology::LOG_LEVEL_ERROR, __METHOD__, '72a3452d');
            $this->_connectedEntityIsUnlocked = false;
        }
    }



    public function setCurrentEntity(Entity $entity): bool
    {
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
        $this->_findCurrentEntityPrivateKey();
        $this->_connectedEntityIsUnlocked = $this->_currentEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }

    /**
     * Change l'entité courante de façon temporaire. Les caches sont effacés.
     * Penser à lui pousser son mot de passe pour qu'elle soit déverrouillée.
     *
     * L'ancienne entité est mémorisée pour être restaurée en fin de période temporaire.
     *
     * La fonction est utilisée par la génération d'une nouvelle entité
     *   afin de faire signer les liens par la nouvelle entité.
     *
     * @param Entity $entity
     * @return boolean
     */
    public function setTempCurrentEntity(Entity $entity): bool
    {
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_sessionInstance->setSessionStoreAsEntity('nebuleTempPublicEntityInstance', $this->_currentEntityInstance);

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
        $this->_findCurrentEntityPrivateKey();
        $this->_connectedEntityIsUnlocked = $this->_currentEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }

    /**
     * Annule le changement temporaire de l'entité. Les caches sont effacés.
     * Elle contient déjà son mot de passe si elle était déverrouillée.
     *
     * L'ancienne entité est restaurée. L'entité actuelle est retirée.
     *
     * @return boolean
     */
    public function unsetTempCurrentEntity(): bool
    {
        session_start();

        $entity = $this->_sessionInstance->getSessionStoreAsEntity('nebuleTempPublicEntityInstance');
        if ($entity === null) {
            session_write_close();
            return false;
        }

        $this->_cacheInstance->flushBufferStore();

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
        $this->_findCurrentEntityPrivateKey();
        $this->_connectedEntityIsUnlocked = $this->_currentEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }



    private string $_connectedEntityID = '';
    private ?Entity $_connectedEntityInstance = null;
    private bool $_connectedEntityIsUnlocked = false;

    private function _findConnectedEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleConnectedEntityInstance');
        if ($instance->getID() == '0')
            $instance = $this->_defaultEntityInstance;
        $this->_connectedEntityInstance = $instance;
        $this->_connectedEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('connected entity EID=' . $this->_connectedEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3a4c8867');
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleConnectedEntityInstance', $this->_connectedEntityInstance);
    }

    public function getConnectedEntityID(): string
    {
        return $this->_connectedEntityID;
    }

    public function getConnectedEntityInstance(): ?Entity
    {
        return $this->_connectedEntityInstance;
    }

    public function setConnectedEntity(Entity $entity): bool
    {
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStoreAsEntity('nebulePublicKeyEIDInstance', $this->_currentEntityInstance);
        $this->_findCurrentEntityPrivateKey();
        $this->_connectedEntityIsUnlocked = $this->_currentEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }

    public function getConnectedEntityIsUnlocked(): bool
    {
        return $this->_connectedEntityIsUnlocked;
    }



    private array $_listEntitiesUnlocked = array();
    private array $_listEntitiesUnlockedInstances = array();

    public function getListEntitiesUnlocked(): array
    {
        return $this->_listEntitiesUnlocked;
    }

    public function getListEntitiesUnlockedInstances(): array
    {
        return $this->_listEntitiesUnlockedInstances;
    }

    public function addListEntitiesUnlocked(Entity $entity): void
    {
        if ($entity->getID() == '0')
            return;
        $eid = $entity->getID();

        $this->_listEntitiesUnlocked[$eid] = $eid;
        $this->_listEntitiesUnlockedInstances[$eid] = $entity;
    }

    public function removeListEntitiesUnlocked(Entity $entity): void
    {
        unset($this->_listEntitiesUnlocked[$entity->getID()]);
        unset($this->_listEntitiesUnlockedInstances[$entity->getID()]);
    }

    public function flushListEntitiesUnlocked(): void
    {
        $this->_listEntitiesUnlocked = array();
        $this->_listEntitiesUnlockedInstances = array();
    }



    public function getListEntitiesInstances(): array
    {
        $hashType = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
        $hashEntity = $this->getNidFromData('application/x-pem-file');
        $hashEntityObject = $this->_cacheInstance->newNode($hashEntity);

        $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);

        $result = array();
        foreach ($links as $link) {
            $id = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNode($id, \Nebule\Library\Cache::TYPE_ENTITY);
            if ($instance->getIsPublicKey())
                $result[$id] = $instance;
        }
        return $result;
    }

    public function getListEntitiesID(): array
    {
        $list = $this->getListEntitiesInstances();
        $result = array();

        foreach ($list as $instance) {
            $id = $instance->getID();
            $result[$id] = $id;
        }

        unset($list, $instance);
        return $result;
    }
}
