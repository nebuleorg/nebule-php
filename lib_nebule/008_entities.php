<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Local entities class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Entities
{
    private ?nebule $_nebuleInstance = null;
    private ?IO $_ioInstance = null;
    private ?Metrology $_metrologyInstance = null;
    private ?Configuration $_configurationInstance = null;
    private ?Cache $_cacheInstance = null;
    private ?Session $_sessionInstance = null;
    private ?Authorities $_authoritiesInstance = null;
    private ?Recovery $_recoveryInstance = null;
    private string $_instanceEntity = '';
    private ?Entity $_instanceEntityInstance = null;
    private string $_defaultEntity = '';
    private ?Entity $_defaultEntityInstance = null;
    private string $_currentEntityID = '';
    private ?Entity $_currentEntityInstance = null;
    private string $_currentEntityPrivateKey = '';
    private ?Node $_currentEntityPrivateKeyInstance = null;
    private bool $_currentEntityIsUnlocked = false;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $nebuleInstance->getMetrologyInstance();
        $this->_configurationInstance = $nebuleInstance->getConfigurationInstance();
        $this->_cacheInstance = $nebuleInstance->getCacheInstance();
        $this->_sessionInstance = $nebuleInstance->getSessionInstance();
        $this->_recoveryInstance = $this->_nebuleInstance->getRecoveryInstance();
        $this->_findInstanceEntity();
        $this->_authoritiesInstance->setInstanceEntityAsAuthorities($this->_instanceEntityInstance);
        $this->_recoveryInstance->setInstanceEntityAsRecovery($this->_instanceEntityInstance);
        $this->_findDefaultEntity();
        $this->_authoritiesInstance->setDefaultEntityAsAuthorities($this->_defaultEntityInstance);
        $this->_recoveryInstance->setDefaultEntityAsRecovery($this->_defaultEntityInstance);
        $this->_authoritiesInstance->setLinkedLocalAuthorities($this);
        $this->_recoveryInstance->setLinkedRecoveryEntities($this);
        $this->_getCurrentEntity();
        $this->_getCurrentEntityPrivateKey();
        $this->_getCurrentEntityPassword();
    }

    private function _findInstanceEntity()
    {
        $instance = null;
        // Vérifie si une valeur n'est pas mémorisée dans la session.
        $id = $this->_sessionInstance->getSessionStore('nebuleHostEntity');

        // Si il existe une variable de session pour l'hôte en cours, la lit.
        if ($id !== false
            && $id != ''
        ) {
            $instance = unserialize($this->_sessionInstance->getSessionStore('nebuleHostEntityInstance'));
        }

        if ($id !== false
            && $id != ''
            && $instance !== false
            && $instance !== ''
            && is_a($instance, 'Nebule\Library\Entity')
        ) {
            $this->_instanceEntity = $id;
            $this->_instanceEntityInstance = $instance;
        } else {
            // Sinon recherche une entité pour l'instance.
            // C'est le fichier 'e' qui contient normalement l'ID de cette entité.
            if (file_exists(References::COMMAND_LOCAL_ENTITY_FILE)
                && is_file(References::COMMAND_LOCAL_ENTITY_FILE)
            ) {
                $id = filter_var(trim(strtok(file_get_contents(References::COMMAND_LOCAL_ENTITY_FILE), "\n")), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
            }

            if (is_string($id) && Node::checkNID($id, false, false)
                && $this->_ioInstance->checkObjectPresent($id)
                && $this->_ioInstance->checkLinkPresent($id)
            ) {
                $this->_instanceEntity = $id;
                $this->_instanceEntityInstance = $this->_cacheInstance->newEntity($id);
            } else {
                // Sinon utilise l'instance du maître du code.
                $this->_instanceEntity = $this->_authoritiesInstance->getPuppetmasterEID();
                $this->_instanceEntityInstance = $this->_authoritiesInstance->getPuppetmasterInstance();
            }

            // Log
            $this->_metrologyInstance->addLog('Find server entity ' . $this->_instanceEntity, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5347c940');

            // Mémorisation.
            $this->_sessionInstance->setSessionStore('nebuleHostEntity', $this->_instanceEntity);
            $this->_sessionInstance->setSessionStore('nebuleHostEntityInstance', serialize($this->_instanceEntityInstance));
        }
        unset($id, $instance);
    }

    public function getInstanceEntity(): string
    {
        return $this->_instanceEntity;
    }

    public function getInstanceEntityInstance(): ?Entity
    {
        return $this->_instanceEntityInstance;
    }



    private function _findDefaultEntity()
    {
        $instance = null;
        // Vérifie si une valeur n'est pas mémorisée dans la session.
        $id = $this->_sessionInstance->getSessionStore('nebuleDefaultEntity');

        // Si il existe une variable de session pour l'hôte en cours, la lit.
        if ($id !== false
            && $id != ''
        ) {
            $instance = unserialize($this->_sessionInstance->getSessionStore('nebuleDefaultEntityInstance'));
        }

        if ($id !== false
            && $id != ''
            && $instance !== false
            && $instance !== ''
            && is_a($instance, 'Nebule\Library\Entity')
        ) {
            $this->_defaultEntity = $id;
            $this->_defaultEntityInstance = $instance;
        } else {
            // Sinon recherche une entité par défaut.
            // C'est définit comme une option.
            $id = $this->_configurationInstance->getOptionAsString('defaultCurrentEntity');

            if (Node::checkNID($id, false, false)
                && $this->_ioInstance->checkObjectPresent($id)
                && $this->_ioInstance->checkLinkPresent($id)
            ) {
                $this->_defaultEntity = $id;
                $this->_defaultEntityInstance = $this->_cacheInstance->newEntity($id);
            } else {
                // Sinon utilise l'instance du serveur hôte.
                $this->_defaultEntity = $this->_instanceEntity;
                $this->_defaultEntityInstance = $this->_instanceEntityInstance;
            }

            // Log
            $this->_metrologyInstance->addLog('Find default entity ' . $this->_defaultEntity, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '17bc6adc');

            // Mémorisation.
            $this->_sessionInstance->setSessionStore('nebuleDefaultEntity', $this->_defaultEntity);
            $this->_sessionInstance->setSessionStore('nebuleDefaultEntityInstance', serialize($this->_defaultEntityInstance));
        }
        unset($id, $instance);
    }

    public function getDefaultEntityID(): string
    {
        return $this->_defaultEntity;
    }

    public function getDefaultEntityInstance(): ?Entity
    {
        return $this->_defaultEntityInstance;
    }


    private function _getCurrentEntity() // FIXME WTF!
    {
        $itc_ent = null;

        $arg_ent = $this->_getCurrentEntityFromArg();
        if ($arg_ent != '')
            $itc_ent = $this->_cacheInstance->newEntity($arg_ent);

        if ($arg_ent != ''
            && is_a($itc_ent, 'Nebule\Library\Node')
            && $itc_ent->getType('all') == Entity::ENTITY_TYPE
        ) {
            // Vide le mot de passe de l'entité en cours.
            if (is_a($this->_currentEntityInstance, 'Nebule\Library\Entity')) {
                $this->_currentEntityInstance->unsetPrivateKeyPassword();
                $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            }
            $this->_currentEntityID = $arg_ent;
            $this->_currentEntityInstance = new Entity($this->_nebuleInstance, $arg_ent);
            $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
            $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            $this->_currentEntityPrivateKey = '';
            $this->_currentEntityPrivateKeyInstance = null;
            $this->_sessionInstance->setSessionStore('nebulePrivateEntity', '');
            $this->_metrologyInstance->addLog('New current entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7252b306');
        } else {
            $tID = $this->_sessionInstance->getSessionStore('nebulePublicEntity');
            $sInstance = $this->_sessionInstance->getSessionStore('nebulePublicEntityInstance');
            $tInstance = false;
            if ($sInstance !== false)
                $tInstance = unserialize($sInstance);
            if ($tID !== false
                && $tID != ''
                && $tInstance !== false
                && is_a($tInstance, 'Nebule\Library\Entity')
            ) {
                $this->_currentEntityID = $tID;
                $this->_currentEntityInstance = $tInstance;
                $this->_metrologyInstance->addLog('Reuse current entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a16292e8');
            } else // Sinon essaie de la trouver ailleurs.
            {
                $itc_ent = '';
                $ext_ent = $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity');
                if (Node::checkNID($ext_ent, false, false)
                    && $this->_ioInstance->checkObjectPresent($ext_ent)
                    && $this->_ioInstance->checkLinkPresent($ext_ent)) {
                    $itc_ent = $this->_cacheInstance->newEntity($ext_ent);
                }
                if (is_a($itc_ent, 'Nebule\Library\Entity') && $itc_ent->getType('all') == Entity::ENTITY_TYPE) {
                    // Ecrit l'entité dans la session et dans la variable globale.
                    $this->_currentEntityID = $ext_ent;
                    $this->_currentEntityInstance = new Entity($this->_nebuleInstance, $ext_ent);
                    $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
                    $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
                    // Vide la clé privée connue.
                    $this->_currentEntityPrivateKey = '';
                    $this->_currentEntityPrivateKeyInstance = null;
                    $this->_sessionInstance->setSessionStore('nebulePrivateEntity', '');
                    $this->_metrologyInstance->addLog('Find default current entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9035e635');
                } // Sinon utilise l'entité de l'instance.
                else {
                    $this->_currentEntityID = $this->_instanceEntity;
                    $this->_currentEntityInstance = $this->_instanceEntityInstance;
                    $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
                    $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
                    // Vide la clé privée connue.
                    $this->_currentEntityPrivateKey = '';
                    $this->_currentEntityPrivateKeyInstance = null;
                    $this->_sessionInstance->setSessionStore('nebulePrivateEntity', '');
                    $this->_metrologyInstance->addLog('Find current (instance) entity ' . $this->_currentEntityID, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1cee288b');
                }
            }
        }
    }

    private function _getCurrentEntityFromArg(): ?string
    {
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
            $arg = '';

        // Verify node
        if (!Node::checkNID($arg, false, false)
            || !$this->_ioInstance->checkObjectPresent($arg)
            || !$this->_ioInstance->checkLinkPresent($arg)
        )
            $arg = '';

        return $arg;
    }

    private function _getCurrentEntityPrivateKey()
    {
        $privateKey = $this->_sessionInstance->getSessionStore('nebulePrivateEntity');

        if ($privateKey !== false
            && $privateKey != ''
            && $privateKey != '0'
        ) {
            $this->_currentEntityPrivateKey = $privateKey;
            $this->_currentEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_currentEntityPrivateKey);
            $this->_metrologyInstance->addLog('Reuse current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '75e1c757');
        }
        else {
            if (is_a($this->_currentEntityInstance, 'Nebule\Library\Entity')) {
                $this->_currentEntityPrivateKey = $this->_currentEntityInstance->getPrivateKeyID();
                if ($this->_currentEntityPrivateKey != '') {
                    $this->_currentEntityPrivateKeyInstance = $this->_cacheInstance->newNode($this->_currentEntityPrivateKey);
                    $this->_metrologyInstance->addLog('Find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '6be388ca');
                } else {
                    $this->_currentEntityPrivateKeyInstance = null;
                    $this->_metrologyInstance->addLog('Cant find current entity private key ' . $this->_currentEntityPrivateKey, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1e5bed72');
                }
                $this->_sessionInstance->setSessionStore('nebulePrivateEntity', $this->_currentEntityPrivateKey);
            } else {
                $this->_currentEntityPrivateKey = '';
                $this->_currentEntityPrivateKeyInstance = null;
            }
        }
    }

    private function _getCurrentEntityPassword()
    {
        if (filter_has_var(INPUT_GET, References::COMMAND_AUTH_ENTITY_LOGOUT)
            || filter_has_var(INPUT_POST, References::COMMAND_AUTH_ENTITY_LOGOUT))
        {
            if (is_a($this->_currentEntityInstance, 'Nebule\Library\Entity')) {
                $this->_metrologyInstance->addLog('Logout ' . $this->_currentEntityID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '4efbc71f');
                $this->_currentEntityInstance->unsetPrivateKeyPassword();
                $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
            }
            return;
        }

        if ($this->_currentEntityInstance->isSetPrivateKeyPassword())
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
            $this->_metrologyInstance->addLog('Login password ' . $this->_currentEntityID . ' OK', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '99ed783e');
            $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        } else
            $this->_metrologyInstance->addLog('Login password ' . $this->_currentEntityID . ' NOK', Metrology::LOG_LEVEL_ERROR, __METHOD__, '72a3452d');
    }

    public function getCurrentEntityID(): string
    {
        return $this->_currentEntityID;
    }

    public function getCurrentEntityInstance(): ?Entity
    {
        return $this->_currentEntityInstance;
    }

    public function getCurrentEntityPrivateKey(): string
    {
        return $this->_currentEntityPrivateKey;
    }

    public function getCurrentEntityPrivateKeyInstance(): ?Node
    {
        return $this->_currentEntityPrivateKeyInstance;
    }

    public function getCurrentEntityIsUnlocked2(): bool
    {
        return $this->_currentEntityIsUnlocked;
    }

    public function setCurrentEntity(Entity $entity): bool
    {
        session_start();

        $this->_cacheInstance->flushBufferStore();

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
        $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        $this->_getCurrentEntityPrivateKey();
        $this->_currentEntityIsUnlocked = $this->_currentEntityInstance->isSetPrivateKeyPassword();

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

        $this->_sessionInstance->setSessionStore('nebuleTempPublicEntityInstance', serialize($this->_currentEntityInstance));

        $this->_currentEntityInstance = $entity;
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
        $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        $this->_getCurrentEntityPrivateKey();
        $this->_currentEntityIsUnlocked = $this->_currentEntityInstance->isSetPrivateKeyPassword();

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

        $entity = $this->_sessionInstance->getSessionStore('nebuleTempPublicEntityInstance');
        if ($entity === false) {
            session_write_close();
            return false;
        }

        $this->_cacheInstance->flushBufferStore();

        $this->_currentEntityInstance = unserialize($entity);
        $this->_currentEntityID = $this->_currentEntityInstance->getID();
        $this->_sessionInstance->setSessionStore('nebulePublicEntity', $this->_currentEntityID);
        $this->_sessionInstance->setSessionStore('nebulePublicEntityInstance', serialize($this->_currentEntityInstance));
        $this->_getCurrentEntityPrivateKey();
        $this->_currentEntityIsUnlocked = $this->_currentEntityInstance->isSetPrivateKeyPassword();

        session_write_close();
        return true;
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

    public function removeListEntitiesUnlocked(Entity $entity)
    {
        unset($this->_listEntitiesUnlocked[$entity->getID()]);
        unset($this->_listEntitiesUnlockedInstances[$entity->getID()]);
    }

    public function flushListEntitiesUnlocked()
    {
        $this->_listEntitiesUnlocked = array();
        $this->_listEntitiesUnlockedInstances = array();
    }
}
