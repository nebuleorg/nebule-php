<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social des liens limités à l'entité en cours.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Social implements SocialInterface
{
    const DEFAULT_CLASS = 'authority';

    /**
     * Social type supported.
     *
     * @var string
     */
    const TYPE = '';

    /**
     * @var ?SocialInterface
     */
    private $_defaultInstance = null;
    private $_ready = false;
    private $_listClasses = array();
    private $_listInstances = array();
    private $_listTypes = array();

    /**
     * Instance de la bibliothèque nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected $_metrology;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Instance de gestion du cache.
     *
     * @var Cache
     */
    protected $_cache;

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_cache = $nebuleInstance->getCacheInstance();

        $this->_initialisation($nebuleInstance);
    }

    public function __toString(): string
    {
        return self::TYPE;
    }

    /**
     * Load all classes on theme.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    protected function _initialisation(nebule $nebuleInstance): void
    {
        $myClass = get_class($this);
        $size = strlen($myClass);
        $list = get_declared_classes();
        foreach ($list as $class) {
            if (substr($class, 0, $size) == $myClass && $class != $myClass)
                $this->_initSubClass($class, $nebuleInstance);
        }

        $this->_initDefault('socialLibrary');
    }

    /**
     * Init instance for a module class.
     *
     * @param string $class
     * @param nebule $nebuleInstance
     * @return void
     */
    protected function _initSubClass(string $class, nebule $nebuleInstance): void
    {
        $instance = new $class($nebuleInstance);
        $type = $instance->getType();

        $this->_listClasses[$type] = $class;
        $this->_listTypes[$class] = $type;
        $this->_listInstances[$type] = $instance;
    }

    /**
     * Select default instance and set ready.
     *
     * @param string $name
     * @return void
     */
    protected function _initDefault(string $name): void
    {
        $option = $this->_configuration->getOptionAsString($name);
        if (isset($this->_listInstances[$option]))
        {
            $this->_defaultInstance = $this->_listInstances[$option];
            $this->_ready = true;
        }
        elseif (isset($this->_listInstances[self::DEFAULT_CLASS]))
        {
            $this->_defaultInstance = $this->_listInstances[self::DEFAULT_CLASS];
            $this->_ready = true;
        }
    }

    /**
     * {@inheritDoc}
     * @see SocialInterface::getType()
     */
    public function getType(): string
    {
        if (get_class($this)::TYPE == '' && ! is_null($this->_defaultInstance))
            return $this->_defaultInstance->getType();
        return get_class($this)::TYPE;
    }

    /**
     * {@inheritDoc}
     * @see SocialInterface::getReady()
     */
    public function getReady(): bool
    {
        return $this->_ready;
    }

    /**
     * Gère le classement social des liens.
     *
     * @param array  $links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void
    {
        $this->_metrology->addLog('MARK1 class=' . get_class($this) . ' socialClass=' . $socialClass, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        if ($socialClass == ''
            || !isset($this->_listClasses[$socialClass])
            || !isset($this->_listInstances[$socialClass])
//            || $this->_listInstances[$socialClass] === null
//            || !is_a($this->_listInstances[$socialClass], get_class($this))
        )
        {
            $this->_defaultInstance->arraySocialFilter($links, '');
            return;
        }

        $this->_metrology->addLog('MARK2 class=' . get_class($this) . ' class=' . get_class($this->_listInstances[$socialClass]), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        $this->_listInstances[$socialClass]->arraySocialFilter($links, '');
    }

    /**
     * Calcul le score social d'un lien.
     *
     * @param Link   $link
     * @param string $socialClass
     * @return float
     */
    public function linkSocialScore(Link &$link, string $socialClass = ''): float
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->linkSocialScore($link, '');
        else
            $result = $this->_defaultInstance->linkSocialScore($link, '');
        return $result;
    }

    /**
     * Permet d'injecter une liste pour le calcul/filtrage social.
     * Nécessaire à certains filtrages sociaux, ignoré par d'autres.
     * La liste doit contenir des ID d'objet et non des objets.
     *
     * @param array  $listID
     * @param string $socialClass
     * @return boolean
     */
    public function setList(array $listID, string $socialClass = ''): bool
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->setList($listID);
        else
            $result = $this->_defaultInstance->setList($listID);
        return $result;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function unsetList(string $socialClass = ''): bool
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->unsetList();
        else
            $result = $this->_defaultInstance->unsetList();
        return $result;
    }

    /**
     * Retourne la liste des modes de calculs sociaux disponibles.
     *
     * @return array
     */
    public function getSocialNames(): array
    {
        return $this->_listTypes;
    }

    public function getSocialInstances(): array
    {
        return $this->_listInstances;
    }

    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#s">S / Social</a></li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <h1 id="s">S / Social</h1>
        <p>Gestion des relations sociales dans l'exploitation des objets. En cours...</p>

        <?php
    }
}
