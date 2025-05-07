<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Recovery entities class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Recovery extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_recoveryEntities',
        '_recoveryEntitiesInstances',
        '_recoveryEntitiesSigners',
    );

    private array $_recoveryEntities = array();
    private array $_recoveryEntitiesInstances = array();
    private array $_recoveryEntitiesSigners = array();


    public function setServerEntityAsRecovery(Entity $instance): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        if ($this->_configurationInstance->getOptionAsBoolean('permitServerEntityAsRecovery')) {
            $this->_addAsLocalRecovery($instance);
            $this->_metrologyInstance->addLog('Add server entity as recovery', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'fa22c32d');
        }
    }

    public function setDefaultEntityAsRecovery(Entity $instance): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsRecovery')) {
            $this->_addAsLocalRecovery($instance);
            $this->_metrologyInstance->addLog('Add default entity as recovery', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd8bcdd46');
        }
    }

    private function _addAsLocalRecovery(Node $instance, string $signer='0'): void
    {
        $eid = $instance->getID();
        if ($eid == '0')
            return;
        $this->_recoveryEntities[$eid] = $eid;
        $this->_recoveryEntitiesInstances[$eid] = $instance;
        $this->_recoveryEntitiesSigners[$eid] = $signer;
    }

    public function setLinkedRecoveryEntities(Entities $entitiesInstance): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_recoveryEntities = array();
        $this->_recoveryEntitiesInstances = array();

        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $refRecovery = $this->_nebuleInstance->getNIDfromData(References::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);

        $list = array();
        if ($this->_configurationInstance->getOptionAsBoolean('permitServerEntityAsAuthority')) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refRecovery,
                'bl/rl/nid2' => $entitiesInstance->getServerEntityEID(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getServerEntityEID(),
            );
            $entitiesInstance->getServerEntityInstance()->getLinks($list, $filter, false);
        }

        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refRecovery,
                'bl/rl/nid2' => $entitiesInstance->getServerEntityEID(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getDefaultEntityEID(),
            );
            $entitiesInstance->getServerEntityInstance()->getLinks($list, $filter, false);
        }

        foreach ($list as $link) {
            $instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2'], \Nebule\Library\Cache::TYPE_ENTITY);
            $this->_addAsLocalRecovery($instance, $link->getParsed()['bs/rs1/eid']);
        }
        unset($list);
    }

    public function getRecoveryEntities(): array
    {
        return $this->_recoveryEntities;
    }

    public function getRecoveryEntitiesInstance(): array
    {
        return $this->_recoveryEntitiesInstances;
    }

    public function getRecoveryEntitiesSigners(): array
    {
        return $this->_recoveryEntitiesSigners;
    }

    public function getIsRecoveryEntity($entity): bool
    {
        if (is_a($entity, 'Node'))
            $entity = $entity->getID();
        if ($entity == '0')
            return false;

        foreach ($this->_recoveryEntities as $recovery) {
            if ($entity == $recovery)
                return true;
        }
        return false;
    }
}
