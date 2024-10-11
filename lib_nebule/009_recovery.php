<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Recovery entities class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Recovery extends Functions
{
    private array $_recoveryEntities = array();
    private array $_recoveryEntitiesInstances = array();
    private array $_recoveryEntitiesSigners = array();
    private bool $_permitInstanceEntityAsRecovery = false;
    private bool $_permitDefaultEntityAsRecovery = false;

    protected function _initialisation(): void
    {
        //$this->_findRecoveryEntities();
        $this->_metrologyInstance->addLog('instancing class Recovery', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '496c1d98');
    }


    public function setInstanceEntityAsRecovery(Entity $instance): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $this->_permitInstanceEntityAsRecovery = $this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsRecovery');

        $eid = $instance->getID();
        if ($this->_permitInstanceEntityAsRecovery) {
            $this->_addAsLocalRecovery($instance, $eid);
            $this->_metrologyInstance->addLog('Add instance entity as recovery', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa22c32d');
        }
    }

    public function setDefaultEntityAsRecovery(Entity $instance): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $this->_permitDefaultEntityAsRecovery = $this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsRecovery');

        $eid = $instance->getID();
        if ($this->_permitDefaultEntityAsRecovery) {
            $this->_addAsLocalRecovery($instance, $eid);
            $this->_metrologyInstance->addLog('Add default entity as recovery', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fa22c32d');
        }
    }

    private function _addAsLocalRecovery(Entity $instance, String $eid, string $signer='0'): void
    {
        $this->_recoveryEntities[$eid] = $eid;
        $this->_recoveryEntitiesInstances[$eid] = $instance;
        $this->_recoveryEntitiesSigners[$eid] = $signer;
    }

    public function setLinkedRecoveryEntities(Entities $entitiesInstance): void
    {
        $this->_recoveryEntities = array();
        $this->_recoveryEntitiesInstances = array();

        if (!$this->_configurationInstance->getOptionAsBoolean('permitRecoveryEntities'))
            return;

        $refRecovery = $this->_nebuleInstance->getNIDfromData(References::REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT);

        $list = array();
        if ($this->_configurationInstance->getOptionAsBoolean('permitInstanceEntityAsAuthority')) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refRecovery,
                'bl/rl/nid2' => $entitiesInstance->getInstanceEntity(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getInstanceEntity(),
            );
            $entitiesInstance->getInstanceEntityInstance()->getLinks($list, $filter, false);
        }

        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority')) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refRecovery,
                'bl/rl/nid2' => $entitiesInstance->getInstanceEntity(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getDefaultEntityID(),
            );
            $entitiesInstance->getInstanceEntityInstance()->getLinks($list, $filter, false);
        }

        foreach ($list as $link) {
            $eid = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newEntity($eid);
            $this->_addAsLocalRecovery($instance, $eid, $link->getParsed()['bs/rs1/eid']);
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
