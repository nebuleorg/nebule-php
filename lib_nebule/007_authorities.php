<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Authorities class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Authorities extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_puppetmasterID',
        '_puppetmasterInstance',
        '_securityAuthoritiesID',
        '_securityAuthoritiesInstance',
        '_securitySignersInstance',
        '_codeAuthoritiesID',
        '_codeAuthoritiesInstance',
        '_codeSignersInstance',
        '_directoryAuthoritiesID',
        '_directoryAuthoritiesInstance',
        '_directorySignersInstance',
        '_timeAuthoritiesID',
        '_timeAuthoritiesInstance',
        '_timeSignersInstance',
        '_authoritiesID',
        '_authoritiesInstances',
        '_localAuthoritiesID',
        '_localAuthoritiesInstances',
        '_localPrimaryAuthoritiesID',
        '_localPrimaryAuthoritiesInstances',
        '_localAuthoritiesSigners',
        '_specialEntitiesID',
    );

    private string $_puppetmasterID = '';
    private ?Entity $_puppetmasterInstance = null;
    private array $_securityAuthoritiesID = array();
    private array $_securityAuthoritiesInstance = array();
    private array $_securitySignersInstance = array();
    private array $_codeAuthoritiesID = array();
    private array $_codeAuthoritiesInstance = array();
    private array $_codeSignersInstance = array();
    private array $_directoryAuthoritiesID = array();
    private array $_directoryAuthoritiesInstance = array();
    private array $_directorySignersInstance = array();
    private array $_timeAuthoritiesID = array();
    private array $_timeAuthoritiesInstance = array();
    private array $_timeSignersInstance = array();
    private array $_authoritiesID = array();
    private array $_authoritiesInstances = array();
    private array $_localAuthoritiesID = array();
    private array $_localAuthoritiesInstances = array();
    private array $_localPrimaryAuthoritiesID = array();
    private array $_localPrimaryAuthoritiesInstances = array();
    private array $_localAuthoritiesSigners = array();
    private array $_specialEntitiesID = array();

    protected function _initialisation(): void {
        $this->_findPuppetmaster();
        $this->_findGlobalAuthorities();
        $this->_findLocalAuthorities();
        $this->_metrologyInstance->addLog('instancing class Authorities', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '16aa56f1');
    }

    /**
     * Récupération du maître.
     *
     * Définit par une option ou en dur dans une constante.
     *
     * @return void
     */
    private function _findPuppetmaster(): void {
        $this->_puppetmasterID = $this->_configurationInstance->getOptionUntyped('puppetmaster');
        $this->_puppetmasterInstance = $this->_cacheInstance->newNodeByType($this->_puppetmasterID, \Nebule\Library\Cache::TYPE_ENTITY);
        $this->_metrologyInstance->addLog('Find puppetmaster ' . $this->_puppetmasterID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '88848d09');
    }



    /**
     * Find all global authorities entities after the puppetmaster.
     *
     * @return void
     */
    private function _findGlobalAuthorities(): void {
        $this->_findEntityByType(References::RID_SECURITY_AUTHORITY,
            $this->_securityAuthoritiesID,
            $this->_securityAuthoritiesInstance,
            $this->_securitySignersInstance,
            'security');
        $this->_findEntityByType(References::RID_CODE_AUTHORITY,
            $this->_codeAuthoritiesID,
            $this->_codeAuthoritiesInstance,
            $this->_codeSignersInstance,
            'code');
        $this->_findEntityByType(References::RID_DIRECTORY_AUTHORITY,
            $this->_directoryAuthoritiesID,
            $this->_directoryAuthoritiesInstance,
            $this->_directorySignersInstance,
            'directory');
        $this->_findEntityByType(References::RID_TIME_AUTHORITY,
            $this->_timeAuthoritiesID,
            $this->_timeAuthoritiesInstance,
            $this->_timeSignersInstance,
            'time');
    }

    /**
     * Find authorities by their roles.
     *
     * @param string $rid
     * @param array  $listEID
     * @param array  $listInstances
     * @param array  $signersInstances
     * @param string $name
     * @return void
     */
    private function _findEntityByType(string $rid, array &$listEID, array &$listInstances, array &$signersInstances, string $name): void {
        $instance = $this->_cacheInstance->newNodeByType($rid, Cache::TYPE_NODE);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $rid,
            'bl/rl/nid3' => $rid,
            //'bl/rl/nid4' => '',
            //'bs/rs1/eid' => $this->_puppetmaster,
        );
        $instance->getLinks($links, $filter, 'all', false);

        if (sizeof($links) == 0) {
            $listEID[$this->_puppetmasterID] = $this->_puppetmasterID;
            $listInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
            $signersInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
            return;
        }

        foreach ($links as $link) {
            $eid = $link->getParsed()['bl/rl/nid2'];
            $instance = $this->_cacheInstance->newNodeByType($eid, \Nebule\Library\Cache::TYPE_ENTITY);
            $listEID[$eid] = $eid;
            $listInstances[$eid] = $instance;
            $signersInstances[$eid] = $link->getSignersEID();
            $this->_metrologyInstance->addLog('Find ' . $name . ' authority ' . $eid, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'e6f75b5e');
        }
    }

    public function getPuppetmasterEID(): string { return $this->_puppetmasterID; }
    public function getPuppetmasterInstance(): Entity { return $this->_puppetmasterInstance; }

    public function getSecurityAuthoritiesEID(): array { return $this->_securityAuthoritiesID; }
    public function getSecurityAuthoritiesInstance(): array { return $this->_securityAuthoritiesInstance; }
    public function getSecuritySignersInstance(): array { return $this->_securitySignersInstance; }

    public function getCodeAuthoritiesEID(): array { return $this->_codeAuthoritiesID; }
    public function getCodeAuthoritiesInstance(): array { return $this->_codeAuthoritiesInstance; }
    public function getCodeSignersInstance(): array { return $this->_codeSignersInstance; }

    public function getDirectoryAuthoritiesEID(): array { return $this->_directoryAuthoritiesID; }
    public function getDirectoryAuthoritiesInstance(): array { return $this->_directoryAuthoritiesInstance; }
    public function getDirectorySignersInstance(): array { return $this->_directorySignersInstance; }

    public function getTimeAuthoritiesEID(): array { return $this->_timeAuthoritiesID; }
    public function getTimeAuthoritiesInstance(): array { return $this->_timeAuthoritiesInstance; }
    public function getTimeSignersInstance(): array { return $this->_timeSignersInstance; }



    /**
     * Ajoute les autorités locales par défaut.
     *
     * @return void
     */
    private function _findLocalAuthorities(): void {
        $this->_authoritiesID[$this->_puppetmasterID] = $this->_puppetmasterID;
        $this->_authoritiesInstances[$this->_puppetmasterID] = $this->_puppetmasterInstance;
        $this->_specialEntitiesID[$this->_puppetmasterID] = $this->_puppetmasterID;
        foreach ($this->_securityAuthoritiesID as $item) {
            $this->_authoritiesID[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_securityAuthoritiesInstance[$item];
            $this->_specialEntitiesID[$item] = $item;
        }
        foreach ($this->_codeAuthoritiesID as $item) {
            $this->_authoritiesID[$item] = $item;
            $this->_authoritiesInstances[$item] = $this->_codeAuthoritiesInstance[$item];
            $this->_specialEntitiesID[$item] = $item;
            $this->_localAuthoritiesID[$item] = $item;
            $this->_localAuthoritiesInstances[$item] =$this->_codeAuthoritiesInstance[$item];
            $this->_localAuthoritiesSigners[$item] = $this->_puppetmasterID;
        }
        foreach ($this->_directoryAuthoritiesID as $item)
            $this->_specialEntitiesID[$item] = $item;
        foreach ($this->_timeAuthoritiesID as $item)
            $this->_specialEntitiesID[$item] = $item;
    }

    public function setServerEntityAsAuthorities(Entity $instance): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $eid = $instance->getID();
        if ($this->_configurationInstance->getOptionAsBoolean('permitServerEntityAsAuthority') && !$this->_rescueInstance->getModeRescue()) {
            $this->_addAsLocalAuthority($instance, $eid);
            $this->_metrologyInstance->addLog('Add server entity as authority', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0ccb0886');
        }
    }

    public function setDefaultEntityAsAuthorities(Entity $instance): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $eid = $instance->getID();
        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority') && !$this->_rescueInstance->getModeRescue()) {
            $this->_addAsLocalAuthority($instance, $eid);
            $this->_metrologyInstance->addLog('Add default entity as authority', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '95cc6196');
        }
    }

    private function _addAsLocalAuthority(Entity $instance, String $eid, string $signer='0'): void {
            $this->_authoritiesID[$eid] = $eid;
            $this->_authoritiesInstances[$eid] = $instance;
            $this->_specialEntitiesID[$eid] = $eid;
            $this->_localAuthoritiesID[$eid] = $eid;
            $this->_localAuthoritiesInstances[$eid] = $instance;
            $this->_localAuthoritiesSigners[$eid] = $signer;
            $this->_localPrimaryAuthoritiesID[$eid] = $eid;
            $this->_localPrimaryAuthoritiesInstances[$eid] = $instance;
    }

    /**
     * Ajoute des autres entités marquées comme autorités locales.
     *
     * @param Entities $entitiesInstance
     * @return void
     */
    public function setLinkedLocalAuthorities(Entities $entitiesInstance): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitLocalSecondaryAuthorities'))
            return;

        $refAuthority = $this->_nebuleInstance->getFromDataNID(References::REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE);

        $list = array();
        if ($this->_configurationInstance->getOptionAsBoolean('permitServerEntityAsAuthority') && !$this->_rescueInstance->getModeRescue()) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refAuthority,
                'bl/rl/nid2' => $entitiesInstance->getServerEntityEID(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getServerEntityEID(),
            );
            $entitiesInstance->getServerEntityInstance()->getLinks($list, $filter, 'strict', false);
        }

        if ($this->_configurationInstance->getOptionAsBoolean('permitDefaultEntityAsAuthority') && !$this->_rescueInstance->getModeRescue()) {
            $filter = array(
                'bl/rl/req' => 'f',
                'bl/rl/nid1' => $refAuthority,
                'bl/rl/nid2' => $entitiesInstance->getServerEntityEID(),
                'bl/rl/nid3' => '',
                'bl/rl/nid4' => '',
                'bs/rs1/eid' => $entitiesInstance->getDefaultEntityEID(),
            );
            $entitiesInstance->getServerEntityInstance()->getLinks($list, $filter, 'strict', false);
        }

        foreach ($list as $link) {
            $eid = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_cacheInstance->newNodeByType($eid, \Nebule\Library\Cache::TYPE_ENTITY);
            $this->_addAsLocalAuthority($instance, $eid, $link->getParsed()['bs/rs1/eid']);
            $this->_metrologyInstance->addLog('add as local authority EID=' . $eid, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0074f2c6');
        }
    }

    /**
     * Lit la liste des ID des autorités.
     *
     * @return array:string
     */
    public function getAuthoritiesID(): array { return $this->_authoritiesID; }

    /**
     * Lit la liste des instances des autorités.
     *
     * @return array:Entity
     */
    public function getAuthoritiesInstance(): array { return $this->_authoritiesInstances; }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthoritiesID(): array { return $this->_localAuthoritiesID; }

    /**
     * Lit la liste des instances des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalAuthoritiesInstance(): array { return $this->_localAuthoritiesInstances; }

    /**
     * Lit la liste des autorités locales déclarants des autorités locales.
     *
     * @return array:string
     */
    public function getLocalAuthoritiesSigners(): array { return $this->_localAuthoritiesSigners; }

    /**
     * Lit la liste des ID des autorités locales.
     *
     * @return array:string
     */
    public function getLocalPrimaryAuthoritiesID(): array { return $this->_localPrimaryAuthoritiesID; }

    /**
     * Lit la liste des instances des autorités locales.
     *
     * @return array:Entity
     */
    public function getLocalPrimaryAuthoritiesInstance(): array { return $this->_localPrimaryAuthoritiesInstances; }

    /**
     * Lit la liste des ID des entités avec des rôles.
     *
     * @return array:string
     */
    public function getSpecialEntitiesID(): array { return $this->_specialEntitiesID; }

    public function getIsGlobalAuthority(Entity $entity): bool { return $this->getIsGlobalAuthorityEID($entity->getID()); }
    public function getIsGlobalAuthorityEID(string $eid): bool {
        return ($eid == $this->_puppetmasterID || in_array($eid, $this->_securityAuthoritiesID) || in_array($eid, $this->_codeAuthoritiesID));
    }

    public function getIsLocalAuthority(Entity $entity): bool { return $this->getIsLocalAuthorityEID($entity->getID()); }
    public function getIsLocalAuthorityEID(string $eid): bool { return in_array($eid, $this->_localAuthoritiesID); }

    public function getIsPuppetMaster(Entity $entity): bool { return $this->getIsPuppetMasterEID($entity->getID()); }
    public function getIsPuppetMasterEID(string $eid): bool { return $eid == $this->_puppetmasterID; }

    public function getIsSecurityMaster(Entity $entity): bool { return $this->getIsSecurityMasterEID($entity->getID()); }
    public function getIsSecurityMasterEID(string $eid): bool { return in_array($eid, $this->_securityAuthoritiesID); }

    public function getIsCodeMaster(Entity $entity): bool { return $this->getIsCodeMasterEID($entity->getID()); }
    public function getIsCodeMasterEID(string $eid): bool { return in_array($eid, $this->_codeAuthoritiesID); }

    public function getIsDirectoryMaster(Entity $entity): bool { return $this->getIsDirectoryMasterEID($entity->getID()); }
    public function getIsDirectoryMasterEID(string $eid): bool { return in_array($eid, $this->_directoryAuthoritiesID); }

    public function getIsTimeMaster(Entity $entity): bool { return $this->getIsTimeMasterEID($entity->getID()); }
    public function getIsTimeMasterEID(string $eid): bool { return in_array($eid, $this->_timeAuthoritiesID); }
}