<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * The primary class io.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * C'est une classe qui ne fait aucune action par elle-même.
 * Elle recense les classes de communication pour différents protocoles.
 * Elle est ensuite utilisée comme routeur pour recevoir les requêtes et les rediriger vers
 *   les bonnes classes par rapport aux protocoles utilisés dans les requêtes...
 */
class io extends Functions implements ioInterface {
    const DEFAULT_CLASS = 'disk';
    const FILTER = '';
    const LOCALISATION = '';

    protected ?ioInterface $_defaultInstance = null;
    private array $_listLocalisations = array();
    private array $_listFilterStrings = array();
    private array $_listModes = array();
    protected string $_filesTranscodeKey = '';

    // NOUVEAU: Gestion des stockages multiples
    private array $_storageInstances = [];
    private array $_storageConfig = [];
    private array $_storageInstancesByType = [];

    public function __sleep() {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTranscodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTranscodeKey = '';
        return array();
    }

    public function __destruct() {
        /** @noinspection PhpFieldImmediatelyRewrittenInspection */
        $this->_filesTranscodeKey = '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $this->_filesTranscodeKey = '';
    }

    public function __toString(): string { return self::TYPE; }

    protected function _initialisation(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        // Charger la configuration des stockages
        $this->_loadStorageConfiguration();
        
        // Initialiser chaque stockage configuré
        foreach ($this->_storageConfig as $name => $config) {
            $this->_storageInstances[$name] = $this->_createStorageInstance($name, $config);
            $this->_storageInstancesByType[$config['type']][$name] = $this->_storageInstances[$name];
        }
        
        // Définir l'instance par défaut
        if (isset($this->_storageInstances['default'])) {
            $this->_defaultInstance = $this->_storageInstances['default'];
        } else {
            // Fallback : créer une instance disk par défaut
            $this->_storageInstances['default'] = $this->_createStorageInstance('default', [
                'type' => 'disk',
                'linksFolder' => References::LINKS_FOLDER,
                'objectsFolder' => References::OBJECTS_FOLDER,
                'mode' => 'RW'
            ]);
            $this->_defaultInstance = $this->_storageInstances['default'];
        }
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getFilterString()
     */
    public function getFilterString(): string { return get_class($this)::FILTER; }

    /**
     * {@inheritDoc}
     * @see ioInterface::getLocation()
     */
    public function getLocation(): string {
        if (get_class($this)::LOCALISATION == '' && ! is_null($this->_defaultInstance))
            return $this->_defaultInstance->getLocation();
        return get_class($this)::LOCALISATION;
    }

    private function _getInstanceByURL(string $url): ioInterface {
        $return = $this->_defaultInstance;
        foreach ($this->_listFilterStrings as $type => $pattern)
            if (preg_match($pattern, $url))
                $return =  $this->_listInstances[$type];
        if (!is_a($return, 'Nebule\Library\io')) {
            $return = $this;
        }
        return $return;
    }

    public function getModulesList(): array { return $this->_listTypes; }

    public function getModuleByType(string $type): ioInterface {
        if (isset($this->_listInstances[$type]))
            return $this->_listInstances[$type];
        return $this->_defaultInstance;
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getMode()
     */
    public function getMode(): string { return $this->_defaultInstance->getMode(); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setFilesTranscodeKey()
     */
    public function setFilesTranscodeKey(string &$key): void {
        foreach ($this->_listClasses as $instance)
            $instance->setFilesTranscodeKey($key);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetFilesTranscodeKey()
     */
    public function unsetFilesTranscodeKey(): void {
        foreach ($this->_listClasses as $instance)
            $instance->unsetFilesTranscodeKey();
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::getInstanceEntityID()
     */
    public function getInstanceEntityID(string $url = ''): string { return $this->_defaultInstance->getInstanceEntityID($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksDirectory()
     */
    public function checkLinksDirectory(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksDirectory($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsDirectory()
     */
    public function checkObjectsDirectory(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsDirectory($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksRead()
     */
    public function checkLinksRead(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksRead($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinksWrite()
     */
    public function checkLinksWrite(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinksWrite($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsRead()
     */
    public function checkObjectsRead(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsRead($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectsWrite()
     */
    public function checkObjectsWrite(string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectsWrite($url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkLinkPresent()
     */
    public function checkLinkPresent(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->checkLinkPresent($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::checkObjectPresent()
     */
    public function checkObjectPresent(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->checkObjectPresent($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getBlockLinks()
     */
    public function getBlockLinks(string $oid, string $url = '', int $offset = 0): array { return $this->_getInstanceByURL($url)->getBlockLinks($oid, $url, $offset); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObfuscatedLinks()
     */
    public function getObfuscatedLinks(string $entity, string $signer = '0', string $url = ''): array { return $this->_getInstanceByURL($url)->getObfuscatedLinks($entity, $signer, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getObject()
     */
    public function getObject(string $oid, int $maxsize = 0, string $url = ''): bool|string { return $this->_getInstanceByURL($url)->getObject($oid, $maxsize, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setBlockLink()
     */
    public function setBlockLink(string $oid, string &$link, string $url = ''): bool { return $this->_getInstanceByURL($url)->setBlockLink($oid, $link, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::setObject()
     */
    public function setObject(string $oid, string &$data, string $url = ''): bool {
        // Écriture toujours sur un stockage RW
        $storage = $this->_getReadWriteStorage();
        if ($storage === null) {
            return false;
        }
        return $storage->setObject($oid, $data, $url);
    }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetLink()
     */
    public function unsetLink(string $oid, string &$link, string $url = ''): bool { return $this->_getInstanceByURL($url)->unsetLink($oid, $link, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::flushLinks()
     */
    public function flushLinks(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->flushLinks($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::unsetObject()
     */
    public function unsetObject(string $oid, string $url = ''): bool { return $this->_getInstanceByURL($url)->unsetObject($oid, $url); }

    /**
     * {@inheritDoc}
     * @see ioInterface::getList()
     */
    public function getList(string $url = ''): array { return $this->_getInstanceByURL($url)->getList($url); }



    // =========================================================================
    // NOUVELLES MÉTHODES POUR LA GESTION MULTI-STOCKAGE
    // =========================================================================

    /**
     * Charge la configuration des stockages depuis l'option JSON ioStorage.
     * Si l'option n'est pas définie ou invalide, utilise une configuration par défaut.
     * 
     * @return void
     */
    private function _loadStorageConfiguration(): void {
        $storagesJson = $this->_configurationInstance->getOptionAsString('ioStorage');
        
        if (empty($storagesJson)) {
            // Configuration par défaut avec stockage disk respectant /l et /o
            $this->_storageConfig = [
                'default' => [
                    'type' => 'disk',
                    'linksFolder' => References::LINKS_FOLDER,
                    'objectsFolder' => References::OBJECTS_FOLDER,
                    'mode' => 'RW'
                ]
            ];
            return;
        }
        
        $storages = json_decode($storagesJson, true);
        if (!is_array($storages)) {
            // En cas d'erreur de parsing, utiliser le défaut
            $this->_storageConfig = [
                'default' => [
                    'type' => 'disk',
                    'linksFolder' => References::LINKS_FOLDER,
                    'objectsFolder' => References::OBJECTS_FOLDER,
                    'mode' => 'RW'
                ]
            ];
            return;
        }
        
        // Vérifier et normaliser la configuration
        foreach ($storages as $name => $config) {
            // S'assurer que le type est défini
            if (!isset($config['type'])) {
                $config['type'] = 'disk';
            }
            
            // Pour le stockage par défaut de type disk, forcer les chemins /l et /o
            if ($name === 'default' && ($config['type'] === 'disk' || !isset($config['type']))) {
                $config['type'] = 'disk';
                $config['linksFolder'] = References::LINKS_FOLDER;
                $config['objectsFolder'] = References::OBJECTS_FOLDER;
                if (!isset($config['mode'])) {
                    $config['mode'] = 'RW';
                }
            }
            
            // Définir le mode par défaut
            if (!isset($config['mode'])) {
                $config['mode'] = ($config['type'] === 'disk') ? 'RW' : 'RO';
            }
            
            $storages[$name] = $config;
        }
        
        // Si pas de stockage 'default', l'ajouter
        if (!isset($storages['default'])) {
            $storages['default'] = [
                'type' => 'disk',
                'linksFolder' => References::LINKS_FOLDER,
                'objectsFolder' => References::OBJECTS_FOLDER,
                'mode' => 'RW'
            ];
        }
        
        $this->_storageConfig = $storages;
    }

    /**
     * Crée une instance de stockage avec sa configuration.
     * 
     * @param string $name Nom du stockage
     * @param array $config Configuration du stockage
     * @return ioInterface Instance configurée
     */
    private function _createStorageInstance(string $name, array $config): ioInterface {
        $type = strtolower($config['type'] ?? 'disk');
        $className = 'Nebule\\Library\\io' . ucfirst($type);
        
        if (!class_exists($className)) {
            $this->_metrologyInstance->addLog(
                'Storage type ' . $type . ' not found for storage ' . $name . ', falling back to disk',
                Metrology::LOG_LEVEL_WARNING,
                __METHOD__,
                '00000000'
            );
            $className = 'Nebule\\Library\\ioDisk';
            $type = 'disk';
            $config['type'] = 'disk';
        }
        
        $instance = new $className($this->_nebuleInstance);
        $instance->setEnvironmentLibrary($this->_nebuleInstance);
        
        // Configurer l'instance avec les paramètres spécifiques
        $this->_configureStorageInstance($instance, $config);
        
        $instance->initialisation();
        return $instance;
    }

    /**
     * Configure une instance de stockage avec ses paramètres spécifiques.
     * 
     * @param ioInterface $instance Instance à configurer
     * @param array $config Configuration à appliquer
     * @return void
     */
    private function _configureStorageInstance(ioInterface $instance, array $config): void {
        if ($instance instanceof ioDisk) {
            // Configuration spécifique pour ioDisk
            if (isset($config['linksFolder'])) {
                $instance->setLinksFolder($config['linksFolder']);
            }
            if (isset($config['objectsFolder'])) {
                $instance->setObjectsFolder($config['objectsFolder']);
            }
        } elseif ($instance instanceof ioNetworkHTTP || $instance instanceof ioNetworkHTTPS) {
            // Configuration spécifique pour les stockages réseau
            if (isset($config['url'])) {
                $instance->setBaseUrl($config['url']);
            }
        }
    }

    /**
     * Retourne un stockage en mode RW pour les opérations d'écriture.
     * Priorité : stockage par défaut si RW, sinon premier stockage RW trouvé.
     * 
     * @return ioInterface|null Instance RW ou null si aucun trouvé
     */
    private function _getReadWriteStorage(): ?ioInterface {
        // D'abord essayer le stockage par défaut
        if (isset($this->_storageInstances['default']) && 
            $this->_storageInstances['default']->getMode() === 'RW') {
            return $this->_storageInstances['default'];
        }
        
        // Sinon chercher dans tous les stockages
        foreach ($this->_storageInstances as $name => $storage) {
            if ($storage->getMode() === 'RW') {
                return $storage;
            }
        }
        
        return null;
    }

    /**
     * Retourne la liste des instances de stockage.
     * 
     * @return array Liste des instances indexées par nom
     */
    public function getStorageInstances(): array {
        return $this->_storageInstances;
    }

    /**
     * Retourne la configuration des stockages.
     * 
     * @return array Configuration complète
     */
    public function getStorageConfiguration(): array {
        return $this->_storageConfig;
    }
}
