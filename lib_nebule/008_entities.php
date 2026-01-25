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
 * Must not be serialized on PHP session with nebule class. Recalculate on each request.
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

    protected function _initialisation(): void {
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
        $this->hashType = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_TYPE);
        $this->hashEntity = $this->getNidFromData('application/x-pem-file');
    }



    private string $_serverEntityEID = '';
    private ?Entity $_serverEntityInstance = null;

    public function getServerEntityEID(): string { return $this->_serverEntityEID; }
    public function getServerEntityInstance(): ?Entity { return $this->_serverEntityInstance; }

    /**
     * Try to find server entity from:
     * 1: from the entity file;
     * 2: last keep puppetmaster instance.
     * @return void
     */
    private function _findServerEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNodeByType($this->_findServerEntityFromFileOID(), \Nebule\Library\Cache::TYPE_ENTITY);
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0')
            $instance = $this->_authoritiesInstance->getPuppetmasterInstance();
        $this->_serverEntityInstance = $instance;
        $this->_serverEntityEID = $instance->getID();
        $this->_metrologyInstance->addLog('server entity eid=' . $this->_serverEntityEID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'cdfd0e02');
    }

    private function _findServerEntityFromFileOID(): string {
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



    private string $_defaultEntityEID = '';
    private ?Entity $_defaultEntityInstance = null;

    public function getDefaultEntityEID(): string { return $this->_defaultEntityEID; }
    public function getDefaultEntityInstance(): ?Entity { return $this->_defaultEntityInstance; }

    /**
     * Try to find default entity from:
     * 1: from PHP session;
     * 2: from option defaultEntity (on the config file);
     * 3: last keep server instance.
     * @return void
     */
    private function _findDefaultEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNodeByType($this->_sessionInstance->getSessionStoreAsString('nebuleDefaultEntityInstance'), \Nebule\Library\Cache::TYPE_ENTITY);
        $from = 'session';
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0') {
            $instance = $this->_cacheInstance->newNodeByType($this->_configurationInstance->getOptionFromEnvironmentAsString('defaultEntity'), \Nebule\Library\Cache::TYPE_ENTITY);
            $from = 'environment config';
        }
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0') {
            $instance = $this->_serverEntityInstance;
            $from = 'server entity';
        }
        $this->_defaultEntityInstance = $instance;
        $this->_defaultEntityEID = $instance->getID();
        $this->_metrologyInstance->addLog('default entity eid=' . $this->_defaultEntityEID . ' from ' . $from, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '17bc6adc');
        $this->_sessionInstance->setSessionStoreAsString('nebuleDefaultEntityInstance', $this->_defaultEntityEID);
    }

    private function _findDefaultEntityFromOptionOID(): String {
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



    private string $_ghostEntityEID = '';
    private ?Entity $_ghostEntityInstance = null;

    public function getGhostEntityEID(): string { return $this->_ghostEntityEID; }
    public function getGhostEntityInstance(): ?Entity { return $this->_ghostEntityInstance; }

    /**
     * Try to find ghost entity from:
     * 1: from command argument;
     * 2: from PHP session;
     * 3: last keep default instance.
     * If not from a PHP session, flush a previous private key.
     * @return void
     */
    private function _findGhostEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNodeByType($this->getFilterInput(References::COMMAND_SWITCH_GHOST, FILTER_FLAG_ENCODE_LOW), \Nebule\Library\Cache::TYPE_ENTITY);
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0')
            $instance = $this->_cacheInstance->newNodeByType($this->_sessionInstance->getSessionStoreAsString('nebuleGhostEntityInstance'), \Nebule\Library\Cache::TYPE_ENTITY);
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0')
            $instance = $this->_defaultEntityInstance;
        $this->setGhostEntity($instance);
    }

    public function setGhostEntity(Entity $entity): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_cacheInstance->flushBufferStore();
        $this->_ghostEntityInstance = $entity;
        $this->_ghostEntityEID = $this->_ghostEntityInstance->getID();
        $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();
        $this->_metrologyInstance->addLog('ghost entity eid=' . $this->_ghostEntityEID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd026d625');
        return $this->_sessionInstance->setSessionStoreAsString('nebuleGhostEntityInstance', $this->_ghostEntityEID);
    }

    private string $_ghostEntityPrivateKeyOID = '';
    private ?Node $_ghostEntityPrivateKeyInstance = null;

    public function setGhostEntityPrivateKeyInstance(Node $oid): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($oid->getID() == '0')
            return false;
        $this->_ghostEntityPrivateKeyInstance = $oid;
        $this->_ghostEntityPrivateKeyOID = $oid->getID();
        return true;
    }

    public function getGhostEntityPrivateKeyInstance(): ?Node {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_ghostEntityPrivateKeyOID == '') {
            $this->_ghostEntityPrivateKeyOID = $this->_ghostEntityInstance->getPrivateKeyOID();
            $this->_ghostEntityPrivateKeyInstance = $this->_cacheInstance->newNodeByType($this->_ghostEntityPrivateKeyOID);
        }
        return $this->_ghostEntityPrivateKeyInstance;
    }

    public function getGhostEntityPrivateKeyOID(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_ghostEntityPrivateKeyOID == '') {
            $this->_ghostEntityPrivateKeyOID = $this->_ghostEntityInstance->getPrivateKeyOID();
            $this->_ghostEntityPrivateKeyInstance = $this->_cacheInstance->newNodeByType($this->_ghostEntityPrivateKeyOID);
        }
        return $this->_ghostEntityPrivateKeyOID;
    }

    private function _findGhostEntityPassword(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT)
            || filter_has_var(INPUT_POST, References::COMMAND_AUTH_ENTITY_LOGOUT)) {
            if ($this->_ghostEntityInstance instanceof \Nebule\Library\Entity) {
                $this->_metrologyInstance->addLog('logout ' . $this->_ghostEntityEID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4efbc71f');
                $this->_ghostEntityInstance->unsetPrivateKeyPassword();
                $this->_connectedEntityIsUnlocked = $this->_ghostEntityInstance->getHavePrivateKeyPassword();
                $this->_connectedEntityInstance = $this->_ghostEntityInstance;
                $this->_connectedEntityEID = $this->_ghostEntityEID;
            }
            return;
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity') || $this->_ghostEntityInstance->getHavePrivateKeyPassword())
            return;

        if (filter_has_var(INPUT_POST, References::COMMAND_PASSWORD))
            $arg_pwd = filter_input(INPUT_POST, References::COMMAND_PASSWORD, FILTER_SANITIZE_STRING);
        elseif (filter_has_var(INPUT_GET, References::COMMAND_PASSWORD))
            $arg_pwd = filter_var(hex2bin(filter_input(INPUT_GET, References::COMMAND_PASSWORD, FILTER_SANITIZE_STRING)), FILTER_SANITIZE_STRING);
        else
            return;

        $this->setGhostEntityPassword($arg_pwd);
    }

    public function setGhostEntityPassword(string $password): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (!$this->_configurationInstance->getOptionAsBoolean('permitAuthenticateEntity')) {
            $this->_metrologyInstance->addLog('permitAuthenticateEntity=false cannot set password to eid=' . $this->_ghostEntityEID, Metrology::LOG_LEVEL_ERROR, __METHOD__, '0fba3fab');
            return;
        }

        if ($this->_ghostEntityInstance->setPrivateKeyPassword($password)) {
            $this->_metrologyInstance->addLog('login password for eid=' . $this->_ghostEntityEID . ' OK', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '99ed783e');
            $this->_sessionInstance->setSessionStoreAsEntity('nebuleGhostEntityInstance', $this->_ghostEntityInstance);
            $this->_connectedEntityEID = $this->_ghostEntityEID;
            $this->_connectedEntityInstance = $this->_ghostEntityInstance;
            $this->_sessionInstance->setSessionStoreAsEntity('nebuleConnectedEntityInstance', $this->_connectedEntityInstance);
            $this->_connectedEntityIsUnlocked = true;
        } else
            $this->_metrologyInstance->addLog('login password for eid=' . $this->_ghostEntityEID . ' NOK', Metrology::LOG_LEVEL_ERROR, __METHOD__, '72a3452d');
    }



    private string $_connectedEntityEID = '';
    private ?Entity $_connectedEntityInstance = null;
    private bool $_connectedEntityIsUnlocked = false;

    public function getConnectedEntityEID(): string { return $this->_connectedEntityEID; }
    public function getConnectedEntityInstance(): ?Entity { return $this->_connectedEntityInstance; }
    public function getConnectedEntityIsUnlocked(): bool { return $this->_connectedEntityIsUnlocked; }

    /**
     * Try to find a connected entity from:
     * 1: from PHP session;
     * 3: last keep default instance.
     * If not from the PHP session, flush the previous private key.
     * @return void
     */
    private function _findConnectedEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_cacheInstance->newNodeByType($this->getFilterInput(References::COMMAND_SWITCH_CONNECTED, FILTER_FLAG_ENCODE_LOW), \Nebule\Library\Cache::TYPE_ENTITY);
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0' || !$instance->getIsUnlocked())
            $instance = $this->_cacheInstance->newNodeByType($this->_sessionInstance->getSessionStoreAsString('nebuleConnectedEntityInstance'), \Nebule\Library\Cache::TYPE_ENTITY);
        if (!$instance instanceof \Nebule\Library\Entity || !$instance->getIsEntity() || $instance->getID() == '0' || !$instance->getIsUnlocked())
            $instance = $this->_ghostEntityInstance;
        $this->setConnectedEntity($instance);
    }

    public function setConnectedEntity(Entity $entity): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_cacheInstance->flushBufferStore();
        $this->_connectedEntityInstance = $entity;
        $this->_connectedEntityEID = $this->_connectedEntityInstance->getID();
        $this->_connectedEntityIsUnlocked = $this->_connectedEntityInstance->getHavePrivateKeyPassword();
        $this->_metrologyInstance->addLog('connected entity eid=' . $this->_connectedEntityEID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3a4c8867');
        return $this->_sessionInstance->setSessionStoreAsString('nebuleConnectedEntityInstance', $this->_connectedEntityEID);
    }



    public function getListEntitiesLinks(): array {
        $hashEntityObject = $this->_cacheInstance->newNodeByType($this->hashEntity);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => $this->hashEntity,
            'bl/rl/nid3' => $this->hashType,
            'bl/rl/nid4' => '',
        );
        $hashEntityObject->getLinks($links, $filter, 'all', false);
        //return $hashEntityObject->getLinksOnFields($eid, '', 'l', '', $this->hashEntity, $this->hashType);
        return $links;
    }

    public function getListEntitiesInstances(): array {
        $result = array();
        foreach ($this->getListEntitiesLinks() as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNodeByType($nid, \Nebule\Library\Cache::TYPE_ENTITY);
            if ($instance->getIsPublicKey())
                $result[$nid] = $instance;
        }
        return $result;
    }

    public function getListEntitiesID(): array {
        $result = array();
        foreach ($this->getListEntitiesLinks() as $link) {
            $nid = $link->getParsed()['bl/rl/nid1'];
            if (Node::checkNID($nid))
                $result[$nid] = $nid;
        }
        return $result;
    }
}
