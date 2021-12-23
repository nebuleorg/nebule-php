<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe de gestion du côté social des liens, garde tout.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Retourne toujours un score de 1.
 */
class SocialAll implements SocialInterface
{
    /** Instance nebule en cours. */
    private $_nebuleInstance;

    /**
     * Constructeur.
     */
    public function __construct(nebule $nebuleInstance)
    {
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
     * @return void
     */
    public function arraySocialFilter(array &$links, $socialClass = ''): void
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
    public function linkSocialScore(Link &$link, $socialClass = ''): float
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=all score for ' . $link->getSigneValue_disabled(), Metrology::LOG_LEVEL_DEBUG);
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=all score 1 for ' . $link->getSigners(), Metrology::LOG_LEVEL_DEBUG);

        return 1;
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
