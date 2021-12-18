<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe de gestion du côté social stricte des liens.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Si le signataire du lien est une des entités autorités locales, retourne un score de 1.
 * Sinon retourne un score de 0.
 */
class SocialStrict implements SocialInterface
{
    /** Instance nebule en cours. */
    private $_nebuleInstance;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
    }

    public function __sleep()
    {
        return array();
    }

    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
    }

    /**
     * Gère le classement social des liens.
     *
     * @param array &$links table des liens.
     * @return float
     */
    public function arraySocialFilter(array &$links, $socialClass = '')
    {
        foreach ($links as $i => $link) {
            if ($this->linkSocialScore($link) != 1) {
                unset($links[$i]);
            }
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=strict score for ' . $link->getSigneValue_disabled(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités autorités, retourne la valeur sociale 1.
        foreach ($this->_nebuleInstance->getLocalAuthorities() as $autority) {
            if ($link->getHashSigner_disabled() == $autority) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=strict score 1 for ' . $link->getHashSigner_disabled(), Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=strict score 0 for ' . $link->getHashSigner_disabled(), Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }

    /**
     * Permet d'injecter une liste pour le calcul/filtrage social.
     *
     * La liste doit contenir des ID d'objet et non des objets.
     *
     * @param array:string $listID
     * @return boolean
     */
    public function setList(&$listID)
    {
        return true;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @return boolean
     */
    public function unsetList()
    {
        return true;
    }
}
