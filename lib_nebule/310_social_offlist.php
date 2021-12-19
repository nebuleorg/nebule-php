<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe de gestion du côté social des liens par rapport à une liste d'ID.
 * Ne sélectionne que les liens des entités qui ne sont pas dans la liste.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * La liste doit être préalablement chargée.
 *
 * Si le signataire du lien est une des entités de la liste, retourne un score de 0.
 * Sinon retourne un score de 1.
 */
class SocialOffList implements SocialInterface
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=offlist score for ' . $link->getSigneValue_disabled(), Metrology::LOG_LEVEL_DEBUG);

        if (sizeof($this->_list) == 0) {
            return 0;
        }

        // Si l'entité signataire du lien est une des entités autorités, retourne la valeur sociale 0.
        foreach ($this->_list as $id) {
            if ($link->getHashSigner_disabled() == $id) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=offlist score 0 for ' . $link->getHashSigner_disabled(), Metrology::LOG_LEVEL_DEBUG);
                return 0;
            }
        }

        // Sinon par défaut retourne la valeur sociale 1.
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=offlist score 1 for ' . $link->getHashSigner_disabled(), Metrology::LOG_LEVEL_DEBUG);
        return 1;
    }

    /**
     * Liste pour le calcul/filtrage social.
     * @var array:string
     */
    private $_list = array();

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
        if (!is_array($listID)) {
            return false;
        }

        foreach ($listID as $id) {
            if (is_string($id)
                && $id != ''
                && $id != '0'
                && ctype_xdigit($id)
            ) {
                $this->_list[$id] = $id;
            } else {
                $this->_list = array();
                return false;
            }
        }
        return true;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @return boolean
     */
    public function unsetList()
    {
        $this->_list = array();
        return true;
    }
}
