<?php
declare(strict_types=1);
namespace Nebule\Library;

// TODO à refaire plus flexible

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
    const DEFAULT_CLASS = 'Strict';

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

    private $_instanceSocialMySelf;
    private $_instanceSocialNotMySelf;
    private $_instanceSocialSelf;
    private $_instanceSocialNotself;
    private $_instanceSocialStrict;
    private $_instanceSocialAll;
    private $_instanceSocialNone;
    private $_instanceSocialOnList;
    private $_instanceSocialOffList;
    private $_instanceSocialReputation;
    private $_instanceSocialUnreputation;
    private $_instanceSocialDefault;

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




/*
        $this->_instanceSocialMySelf = new SocialMySelf($nebuleInstance);
        $this->_instanceSocialNotMySelf = new SocialNotMySelf($nebuleInstance);
        $this->_instanceSocialSelf = new SocialSelf($nebuleInstance);
        $this->_instanceSocialNotself = new SocialNotself($nebuleInstance);
        $this->_instanceSocialStrict = new SocialStrict($nebuleInstance);
        $this->_instanceSocialAll = new SocialAll($nebuleInstance);
        $this->_instanceSocialNone = new SocialNone($nebuleInstance);
        $this->_instanceSocialOnList = new SocialOnList($nebuleInstance);
        $this->_instanceSocialOffList = new SocialOffList($nebuleInstance);
        $this->_instanceSocialReputation = new SocialReputation($nebuleInstance);
        $this->_instanceSocialUnreputation = new SocialUnreputation($nebuleInstance);

        // Détermine le traitement social par défaut.
        switch ($nebuleInstance->getConfigurationInstance()->getOptionAsString('socialLibrary')) {
            case 'myself':
                $this->_instanceSocialDefault = $this->_instanceSocialMySelf;
                break;
            case 'notmyself':
                $this->_instanceSocialDefault = $this->_instanceSocialNotMySelf;
                break;
            case 'self':
                $this->_instanceSocialDefault = $this->_instanceSocialSelf;
                break;
            case 'notself':
                $this->_instanceSocialDefault = $this->_instanceSocialNotself;
                break;
            case 'strict':
                $this->_instanceSocialDefault = $this->_instanceSocialStrict;
                break;
            case 'all':
                $this->_instanceSocialDefault = $this->_instanceSocialAll;
                break;
            case 'none':
                $this->_instanceSocialDefault = $this->_instanceSocialNone;
                break;
            case 'onlist':
                $this->_instanceSocialDefault = $this->_instanceSocialOnList;
                break;
            case 'offlist':
                $this->_instanceSocialDefault = $this->_instanceSocialOffList;
                break;
            case 'reputation':
                $this->_instanceSocialDefault = $this->_instanceSocialReputation;
                break;
            case 'unreputation':
                $this->_instanceSocialDefault = $this->_instanceSocialUnreputation;
                break;
            default:
                $this->_instanceSocialDefault = $this->_instanceSocialStrict;
                break;
        }*/
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

        $this->_listClasses[$class] = $class;
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
        if (isset($this->_listClasses[get_class($this) . $option]))
        {
            $this->_defaultInstance = $this->_listInstances[$this->_listTypes[get_class($this) . $option]];
            $this->_ready = true;
        }
        elseif (isset($this->_listClasses[get_class($this) . self::DEFAULT_CLASS]))
        {
            $this->_defaultInstance = $this->_listInstances[$this->_listTypes[get_class($this) . self::DEFAULT_CLASS]];
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
        if ($socialClass != '')
            $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->arraySocialFilter($links, '');
        else
            $this->_defaultInstance->arraySocialFilter($links, '');


 /*       switch ($socialClass) {
            case 'myself':
                $this->_instanceSocialMySelf->arraySocialFilter($links, '');
                break;
            case 'notmyself':
                $this->_instanceSocialNotMySelf->arraySocialFilter($links, '');
                break;
            case 'self':
                $this->_instanceSocialSelf->arraySocialFilter($links, '');
                break;
            case 'notself':
                $this->_instanceSocialNotself->arraySocialFilter($links, '');
                break;
            case 'strict':
                $this->_instanceSocialStrict->arraySocialFilter($links, '');
                break;
            case 'all':
                $this->_instanceSocialAll->arraySocialFilter($links, '');
                break;
            case 'none':
                $this->_instanceSocialNone->arraySocialFilter($links, '');
                break;
            case 'onlist':
                $this->_instanceSocialOnList->arraySocialFilter($links, '');
                break;
            case 'offlist':
                $this->_instanceSocialOffList->arraySocialFilter($links, '');
                break;
            case 'reputation':
                $this->_instanceSocialReputation->arraySocialFilter($links, '');
                break;
            case 'unreputation':
                $this->_instanceSocialUnreputation->arraySocialFilter($links, '');
                break;
            default:
                $this->_instanceSocialDefault->arraySocialFilter($links, '');
                break;
        }*/
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

   /*     switch ($socialClass) {
            case 'myself':
                $result = $this->_instanceSocialMySelf->linkSocialScore($link, '');
                break;
            case 'notmyself':
                $result = $this->_instanceSocialNotMySelf->linkSocialScore($link, '');
                break;
            case 'self':
                $result = $this->_instanceSocialSelf->linkSocialScore($link, '');
                break;
            case 'notself':
                $result = $this->_instanceSocialNotself->linkSocialScore($link, '');
                break;
            case 'strict':
                $result = $this->_instanceSocialStrict->linkSocialScore($link, '');
                break;
            case 'all':
                $result = $this->_instanceSocialAll->linkSocialScore($link, '');
                break;
            case 'none':
                $result = $this->_instanceSocialNone->linkSocialScore($link, '');
                break;
            case 'onlist':
                $result = $this->_instanceSocialOnList->linkSocialScore($link, '');
                break;
            case 'offlist':
                $result = $this->_instanceSocialOffList->linkSocialScore($link, '');
                break;
            case 'reputation':
                $result = $this->_instanceSocialReputation->linkSocialScore($link, '');
                break;
            case 'unreputation':
                $result = $this->_instanceSocialUnreputation->linkSocialScore($link, '');
                break;
            default:
                $result = $this->_instanceSocialDefault->linkSocialScore($link, '');
                break;
        }*/

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

  /*      switch ($socialClass) {
            case 'myself':
                $result = $this->_instanceSocialMySelf->setList($listID);
                break;
            case 'notmyself':
                $result = $this->_instanceSocialNotMySelf->setList($listID);
                break;
            case 'self':
                $result = $this->_instanceSocialSelf->setList($listID);
                break;
            case 'notself':
                $result = $this->_instanceSocialNotself->setList($listID);
                break;
            case 'strict':
                $result = $this->_instanceSocialStrict->setList($listID);
                break;
            case 'all':
                $result = $this->_instanceSocialAll->setList($listID);
                break;
            case 'none':
                $result = $this->_instanceSocialNone->setList($listID);
                break;
            case 'onlist':
                $result = $this->_instanceSocialOnList->setList($listID);
                break;
            case 'offlist':
                $result = $this->_instanceSocialOffList->setList($listID);
                break;
            case 'reputation':
                $result = $this->_instanceSocialReputation->setList($listID);
                break;
            case 'unreputation':
                $result = $this->_instanceSocialUnreputation->setList($listID);
                break;
            default:
                $result = $this->_instanceSocialDefault->setList($listID);
                break;
        }*/

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

     /*   switch ($socialClass) {
            case 'myself':
                $result = $this->_instanceSocialMySelf->unsetList();
                break;
            case 'notmyself':
                $result = $this->_instanceSocialNotMySelf->unsetList();
                break;
            case 'self':
                $result = $this->_instanceSocialSelf->unsetList();
                break;
            case 'notself':
                $result = $this->_instanceSocialNotself->unsetList();
                break;
            case 'strict':
                $result = $this->_instanceSocialStrict->unsetList();
                break;
            case 'all':
                $result = $this->_instanceSocialAll->unsetList();
                break;
            case 'none':
                $result = $this->_instanceSocialNone->unsetList();
                break;
            case 'onlist':
                $result = $this->_instanceSocialOnList->unsetList();
                break;
            case 'offlist':
                $result = $this->_instanceSocialOffList->unsetList();
                break;
            case 'reputation':
                $result = $this->_instanceSocialReputation->unsetList();
                break;
            case 'unreputation':
                $result = $this->_instanceSocialUnreputation->unsetList();
                break;
            default:
                $result = $this->_instanceSocialDefault->unsetList();
                break;
        }*/

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
        //return array('myself', 'notmyself', 'self', 'notself', 'strict', 'all', 'none', 'onlist', 'offlist', 'reputation', 'unreputation');
    }

    public function getSocialInstances(): array
    {
        return $this->_listClasses;

   /*     return array(
            $this->_instanceSocialMySelf,
            $this->_instanceSocialNotMySelf,
            $this->_instanceSocialSelf,
            $this->_instanceSocialNotself,
            $this->_instanceSocialStrict,
            $this->_instanceSocialAll,
            $this->_instanceSocialNone,
            $this->_instanceSocialOnList,
            $this->_instanceSocialOffList,
            $this->_instanceSocialReputation,
            $this->_instanceSocialUnreputation,
        );*/
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
