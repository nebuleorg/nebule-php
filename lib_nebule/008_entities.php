<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Local entities class for the nebule library.
 * - Server entity : local entity for the nebule instance of server that can manage local configuration for all entities
 *   on this nebule instance.
 * - Default entity : on first connect to the nebule instance, this is the entity to use by default before eventually
 *   change. Can be modified by configuration (defaultEntity).
 * - Ghost entity : which entity used to see all things (point of vue). Before authenticate to connected entity you
 *   have to select the current entity.
 * - Connected entity : entity unlocked that can do signed actions and see/change enciphered and obfuscated things.
 * - Unlocked entities : list of inherited entities automatically unlocked with the connected entity. This permit to
 *   switch quickly from one to other to do some actions in other context. FIXME not functional now.
 * Must not be serialized on PHP session with nebule class.
 *
 * @author    Projet nebule
 * @license   GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Entities extends Functions
{
    const SESSION_SAVED_VARS = array();
    private string $hashType;
    private string $hashEntity;

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
        $this->_findGhostEntity();
        $this->_findConnectedEntity();
        $this->_findGhostEntityPassword();
        $this->_saveEntitiesOnSession();
        $this->hashType = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
        $this->hashEntity = $this->getNidFromData('application/x-pem-file');
    }



    private string $_serverEntityID = '';
    private ?Entity $_serverEntityInstance = null;

    public function getServerEntityEID(): string { return $this->_serverEntityID; }
    public function getServerEntityInstance(): ?Entity { return $this->_serverEntityInstance; }

    /**
     * Try to find server entity from :
     * 1: from file;
     * 2: last keep puppetmaster instance.
     * @return void
     */
    private function _findServerEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNode($this->_findServerEntityFromFileOID(), \Nebule\Library\Cache::TYPE_ENTITY);
        if ($instance->getID() == '0')
            $instance = $this->_authoritiesInstance->getPuppetmasterInstance();
        $this->_serverEntityInstance = $instance;
        $this->_serverEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('server entity eid=' . $this->_serverEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cdfd0e02');
    }

    private function _findServerEntityFromFileOID(): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = file_get_contents(References::COMMAND_LOCAL_ENTITY_FILE);
        if (is_bool($content))
            return '0';
        $content = strtok($content, "\n");
        if (is_bool($content))
            return '0';
        $oid = filter_var(trim($content), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (is_bool($oid) || $oid == '')
            return '0';
        if (!Node::checkNID($oid, false, false)
            || !$this->_ioInstance->checkObjectPresent($oid)
            || !$this->_ioInstance->checkLinkPresent($oid)
        )
            return '0';
        $this->_metrologyInstance->addLog('find server entity from file eid=' . $oid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '811a12be');
        return $oid;
    }



    private string $_defaultEntityID = '';
    private ?Entity $_defaultEntityInstance = null;

    public function getDefaultEntityEID(): string { return $this->_defaultEntityID; }
    public function getDefaultEntityInstance(): ?Entity { return $this->_defaultEntityInstance; }

    /**
     * Try to find default entity from :
     * 1: from PHP session;
     * 2: from option defaultEntity (on config file);
     * 3: last keep server instance.
     * @return void
     */
    private function _findDefaultEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleDefaultEntityInstance');
        if ($instance === null or $instance->getID() == '0')
            $instance = $this->_cacheInstance->newNode($this->_findDefaultEntityFromOptionOID(), \Nebule\Library\Cache::TYPE_ENTITY);
        if ($instance->getID() == '0')
            $instance = $this->_serverEntityInstance;
        $this->_defaultEntityInstance = $instance;
        $this->_defaultEntityID = $instance->getID();
        $this->_metrologyInstance->addLog('default entity eid=' . $this->_defaultEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '17bc6adc');
    }

    private function _findDefaultEntityFromOptionOID(): String
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = $this->_configurationInstance->getOptionFromEnvironmentAsString('defaultEntity');
        if ($content == '')
            return '0';
        $oid = filter_var(trim($content), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (is_bool($oid) || $oid == '')
            return '0';
        if (!Node::checkNID($oid, false, false)
            || !$this->_ioInstance->checkObjectPresent($oid)
            || !$this->_ioInstance->checkLinkPresent($oid)
        )
            return '0';
        $this->_metrologyInstance->addLog('find default entity from config file eid=' . $oid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cf459003');
        return $oid;
    }



    private string $_ghostEntityOID = '';
    private ?Entity $_ghostEntityInstance = null;

    public function getGhostEntityEID(): string { return $this->_ghostEntityOID; }
    public function getGhostEntityInstance(): ?Entity { return $this->_ghostEntityInstance; }

    /**
     * Try to find ghost entity from :
     * 1: from command argument;
     * 2: from PHP session;
     * 3: last keep default instance.
     * If not from PHP session, flush previous private key.
     * @return void
     */
    private function _findGhostEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNode($this->_findGhostEntityFromArg(), \Nebule\Library\Cache::TYPE_ENTITY);
        if ($instance->getID() == '0')
            $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleGhostEntityInstance');
        if ($instance === null or $instance->getID() == '0')
            $instance = $this->_defaultEntityInstance;
        $this->_ghostEntityInstance = $instance;
        $this->_ghostEntityOID = $instance->getID();
        $this->_metrologyInstance->addLog('ghost entity eid=' . $this->_ghostEntityOID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd026d625');
    }

    private function _findGhostEntityFromArg(): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (filter_has_var(INPUT_GET, References::COMMAND_SELECT_GHOST))
            $arg = filter_input(INPUT_GET,
                References::COMMAND_SELECT_GHOST,
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_ENCODE_LOW);
        elseif (filter_has_var(INPUT_POST, References::COMMAND_SELECT_GHOST))
            $arg = filter_input(INPUT_POST,
                References::COMMAND_SELECT_GHOST,
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_ENCODE_LOW);
        else
            return '0';
        if (is_bool($arg) || $arg == '')
            return '0';
        if (!Node::checkNID($arg, false, false)
            || !$this->_ioInstance->checkObjectPresent($arg)
            || !$this->_ioInstance->checkLinkPresent($arg)
        )
            return '0';
        $this->_metrologyInstance->addLog('find ghost entity key from arg eid=' . $arg, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '811a12be');
        return $arg;
    }

    
    
    private string $_ghostEntityPrivateKeyOID = '';
    private ?Node $_ghostEntityPrivateKeyInstance = null;

    /*public function setGhostEntityPrivateKeyInstance(Node $oid): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return false; // TODO
    }*/

    public function getGhostEntityPrivateKeyInstance(): ?Node
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_ghostEntityPrivateKeyOID == '') {
            $this->_ghostEntityPrivateKeyOID = $this->_ghostEntityInstance->getPrivateKeyOID();
            $this->_ghostEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_ghostEntityPrivateKeyOID);
        }
        return $this->_ghostEntityPrivateKeyInstance;
    }

    public function getGhostEntityPrivateKeyOID(): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_ghostEntityPrivateKeyOID == '') {
            $this->_ghostEntityPrivateKeyOID = $this->_ghostEntityInstance->getPrivateKeyOID();
            $this->_ghostEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_ghostEntityPrivateKeyOID);
        }
        return $this->_ghostEntityPrivateKeyOID;
    }

    private function _findGhostEntityPassword(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT)
            || filter_has_var(INPUT_POST, References::COMMAND_AUTH_ENTITY_LOGOUT))
        {
            if ($this->_ghostEntityInstance instanceof Entity) {
                $this->_metrologyInstance->addLog('logout ' . $this->_ghostEntityOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4efbc71f');
                $this->_ghostEntityInstance->unsetPrivateKeyPassword();
            }
            return;
        }

        if ($this->_ghostEntityInstance->getHavePrivateKeyPassword())
            return;

        if (filter_has_var(INPUT_POST, References::COMMAND_SELECT_PASSWORD))
            $arg_pwd = filter_input(INPUT_POST, References::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING);
        elseif (filter_has_var(INPUT_GET, References::COMMAND_SELECT_PASSWORD))
            $arg_pwd = filter_var(hex2bin(filter_input(INPUT_GET, References::COMMAND_SELECT_PASSWORD, FILTER_SANITIZE_STRING)), FILTER_SANITIZE_STRING);
        else
            return;

        $this->setGhostEntityPassword($arg_pwd);
    }

    public function setGhostEntityPassword(string $password): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_ghostEntityInstance->setPrivateKeyPassword($password))
        {
            $this->_metrologyInstance->addLog('login password for eid=' . $this->_ghostEntityOID . ' OK', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '99ed783e');
            $this->_sessionInstance->setSessionStoreAsEntity('nebuleGhostEntityInstance', $this->_ghostEntityInstance);
            $this->_connectedEntityID = $this->_ghostEntityOID;
            $this->_connectedEntityInstance = $this->_ghostEntityInstance;
            $this->_sessionInstance->setSessionStoreAsEntity('nebuleConnectedEntityInstance', $this->_connectedEntityInstance);
            $this->_connectedEntityIsUnlocked = true;
        } else
            $this->_metrologyInstance->addLog('login password for eid=' . $this->_ghostEntityOID . ' NOK', Metrology::LOG_LEVEL_ERROR, __METHOD__, '72a3452d');
    }

    public function setGhostEntity(Entity $entity): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_ghostEntityInstance = $entity;
        $this->_ghostEntityOID = $this->_ghostEntityInstance->getID();
        $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();

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
    public function setTempGhostEntity(Entity $entity): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_sessionInstance->setSessionStoreAsEntity('nebuleTempPublicEntityInstance', $this->_ghostEntityInstance);

        $this->_ghostEntityInstance = $entity;
        $this->_ghostEntityOID = $this->_ghostEntityInstance->getID();
        $this->_ghostEntityPrivateKeyOID = '';
        $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();

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
    public function unsetTempGhostEntity(): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        $entity = $this->_sessionInstance->getSessionStoreAsEntity('nebuleTempPublicEntityInstance');
        if ($entity === null) {
            session_write_close();
            return false;
        }

        $this->_cacheInstance->flushBufferStore();

        $this->_ghostEntityInstance = $entity;
        $this->_ghostEntityOID = $this->_ghostEntityInstance->getID();
        $this->_ghostEntityPrivateKeyOID = '';
        $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }



    private string $_connectedEntityID = '';
    private ?Entity $_connectedEntityInstance = null;
    private bool $_connectedEntityIsUnlocked = false;

    public function getConnectedEntityEID(): string { return $this->_connectedEntityID; }
    public function getConnectedEntityInstance(): ?Entity { return $this->_connectedEntityInstance; }
    public function getConnectedEntityIsUnlocked(): bool { return $this->_connectedEntityIsUnlocked; }

    /**
     * Try to find connected entity from :
     * 1: from PHP session;
     * 3: last keep default instance.
     * If not from PHP session, flush previous private key.
     * @return void
     */
    private function _findConnectedEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_sessionInstance->getSessionStoreAsEntity('nebuleConnectedEntityInstance');
        if ($instance->getID() == '0')
            $instance = $this->_ghostEntityInstance;
        $this->_connectedEntityInstance = $instance;
        $this->_connectedEntityID = $instance->getID();
        $this->_connectedEntityIsUnlocked = $this->_connectedEntityInstance->getHavePrivateKeyPassword();
        $this->_metrologyInstance->addLog('connected entity eid=' . $this->_connectedEntityID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3a4c8867');
    }

    public function setConnectedEntity(Entity $entity): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_ghostEntityInstance = $entity;
        $this->_ghostEntityOID = $this->_ghostEntityInstance->getID();
        $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();

        session_write_close();
        return true;
    }

    
    
    private function _saveEntitiesOnSession(): void
    {
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleDefaultEntityInstance', $this->_defaultEntityInstance);
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleGhostEntityInstance', $this->_ghostEntityInstance);
        $this->_sessionInstance->setSessionStoreAsEntity('nebuleConnectedEntityInstance', $this->_connectedEntityInstance);
    }



/* TODO manage multi-connected entities

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
*/



    public function getListEntitiesLinks(string $eid=''): array
    {
        $hashEntityObject = $this->_cacheInstance->newNode($this->hashEntity);
        return $hashEntityObject->getLinksOnFields($eid, '', 'l', '', $this->hashEntity, $this->hashType);
    }

    public function getListEntitiesInstances(string $eid=''): array
    {
        $result = array();
        foreach ($this->getListEntitiesLinks($eid) as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNode($nid, \Nebule\Library\Cache::TYPE_ENTITY);
            if ($instance->getIsPublicKey())
                $result[$nid] = $instance;
        }
        return $result;
    }

    public function getListEntitiesID(string $eid): array
    {
        $result = array();
        foreach ($this->getListEntitiesLinks($eid) as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (Node::checkNID($nid))
                $result[$nid] = $nid;
        }
        return $result;
    }
}
