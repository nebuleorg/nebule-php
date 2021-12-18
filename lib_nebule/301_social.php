<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

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
    /**
     * Instance de la bibliothèque nebule.
     * @var nebule
     */
    private $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    private $_configuration;

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
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_instanceSocialMySelf = new SocialMySelf();
        $this->_instanceSocialNotMySelf = new SocialNotMySelf();
        $this->_instanceSocialSelf = new SocialSelf();
        $this->_instanceSocialNotself = new SocialNotself();
        $this->_instanceSocialStrict = new SocialStrict();
        $this->_instanceSocialAll = new SocialAll();
        $this->_instanceSocialNone = new SocialNone();
        $this->_instanceSocialOnList = new SocialOnList();
        $this->_instanceSocialOffList = new SocialOffList();
        $this->_instanceSocialReputation = new SocialReputation();
        $this->_instanceSocialUnreputation = new SocialUnreputation();

        // Détermine le traitement social par défaut.
        switch ($this->_configuration->getOption('socialLibrary')) {
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
                $this->_instanceSocialDefault = $this->_instanceSocialOffList;
                break;
            case 'offlist':
                $this->_instanceSocialDefault = $this->_instanceSocialOffList;
                break;
            default:
                $this->_instanceSocialDefault = $this->_instanceSocialStrict;
                break;
        }
    }

    public function __sleep()
    {
        return array();
    }

    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_instanceSocialMySelf = new SocialMySelf();
        $this->_instanceSocialNotMySelf = new SocialNotMySelf();
        $this->_instanceSocialSelf = new SocialSelf();
        $this->_instanceSocialNotself = new SocialNotself();
        $this->_instanceSocialStrict = new SocialStrict();
        $this->_instanceSocialAll = new SocialAll();
        $this->_instanceSocialNone = new SocialNone();
        $this->_instanceSocialOnList = new SocialOnList();
        $this->_instanceSocialOffList = new SocialOffList();
        $this->_instanceSocialReputation = new SocialReputation();
        $this->_instanceSocialUnreputation = new SocialUnreputation();

        // Détermine le traitement social par défaut.
        switch ($this->_configuration->getOption('socialLibrary')) {
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
        }
    }

    /**
     * Gère le classement social des liens.
     *
     * @param array &$links table des liens.
     * @return float
     */
    public function arraySocialFilter(array &$links, $socialClass = '')
    {
        switch ($socialClass) {
            case 'myself':
                return $this->_instanceSocialMySelf->arraySocialFilter($links, '');
                break;
            case 'notmyself':
                return $this->_instanceSocialNotMySelf->arraySocialFilter($links, '');
                break;
            case 'self':
                return $this->_instanceSocialSelf->arraySocialFilter($links, '');
                break;
            case 'notself':
                return $this->_instanceSocialNotself->arraySocialFilter($links, '');
                break;
            case 'strict':
                return $this->_instanceSocialStrict->arraySocialFilter($links, '');
                break;
            case 'all':
                return $this->_instanceSocialAll->arraySocialFilter($links, '');
                break;
            case 'none':
                return $this->_instanceSocialNone->arraySocialFilter($links, '');
                break;
            case 'onlist':
                return $this->_instanceSocialOnList->arraySocialFilter($links, '');
                break;
            case 'offlist':
                return $this->_instanceSocialOffList->arraySocialFilter($links, '');
                break;
            case 'reputation':
                return $this->_instanceSocialReputation->arraySocialFilter($links, '');
                break;
            case 'unreputation':
                return $this->_instanceSocialUnreputation->arraySocialFilter($links, '');
                break;
            default:
                return $this->_instanceSocialDefault->arraySocialFilter($links, '');
                break;
        }
    }

    /**
     * Calcul le score social d'un lien.
     *
     * @param Link &$link lien à calculer.
     * @return float
     */
    public function linkSocialScore(Link &$link, $socialClass = '')
    {
        $result = 0;

        switch ($socialClass) {
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
        }

        return $result;
    }

    /**
     * Permet d'injecter une liste pour le calcul/filtrage social.
     * Nécessaire à certains filtrages sociaux, ignoré par d'autres.
     *
     * La liste doit contenir des ID d'objet et non des objets.
     *
     * @param array:string $listID
     * @return boolean
     */
    public function setList(&$list, $socialClass = '')
    {
        $result = false;

        switch ($socialClass) {
            case 'myself':
                $result = $this->_instanceSocialMySelf->setList($list);
                break;
            case 'notmyself':
                $result = $this->_instanceSocialNotMySelf->setList($list);
                break;
            case 'self':
                $result = $this->_instanceSocialSelf->setList($list);
                break;
            case 'notself':
                $result = $this->_instanceSocialNotself->setList($list);
                break;
            case 'strict':
                $result = $this->_instanceSocialStrict->setList($list);
                break;
            case 'all':
                $result = $this->_instanceSocialAll->setList($list);
                break;
            case 'none':
                $result = $this->_instanceSocialNone->setList($list);
                break;
            case 'onlist':
                $result = $this->_instanceSocialOnList->setList($list);
                break;
            case 'offlist':
                $result = $this->_instanceSocialOffList->setList($list);
                break;
            case 'reputation':
                $result = $this->_instanceSocialReputation->setList($list);
                break;
            case 'unreputation':
                $result = $this->_instanceSocialUnreputation->setList($list);
                break;
            default:
                $result = $this->_instanceSocialDefault->setList($list);
                break;
        }

        return $result;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @return boolean
     */
    public function unsetList($socialClass = '')
    {
        $result = false;

        switch ($socialClass) {
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
        }

        return $result;
    }

    /**
     * Retourne la liste des modes de calculs sociaux disponibles.
     *
     * @return array
     */
    public function getList()
    {
        return array('myself', 'notmyself', 'self', 'notself', 'strict', 'all', 'none', 'onlist', 'offlist', 'reputation', 'unreputation');
    }
}
