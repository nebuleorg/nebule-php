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
 *
 * Si le signataire du lien est l'entité en cours, retourne un score de 1.
 * Sinon retourne un score de 0.
 */
class SocialMySelf implements SocialInterface
{
    /** Instance nebule en cours. */
    protected $_nebuleInstance;

    /** Constructeur.*/
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

    public function __toString(): string
    {
        return 'myself';
    }

    /**
     * Gère le classement social des liens.
     *
     * @param array &$links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void
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
     * @param Link  &$link
     * @param string $socialClass
     * @return float
     */
    public function linkSocialScore(Link &$link, string $socialClass = ''): float
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=myself score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités courante, retourne la valeur sociale 1.
        foreach ($link->getSigners() as $signer) {
            if ($signer == $this->_nebuleInstance->getCurrentEntity()) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=myself score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        foreach ($link->getSigners() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=myself score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }



    /**
     * Liste pour le calcul/filtrage social.
     * @var array:string
     */
    protected $_list = array();

    /**
     * Permet d'injecter une liste pour le calcul/filtrage social.
     * La liste doit contenir de préférence des ID d'objet et non des objets.
     * N'est utile que pour certains types de calculs sociaux.
     *
     * @param array:string $listID
     * @return boolean
     */
    public function setList(array $listID): bool
    {
        foreach ($listID as $nid) {
            if (is_string($nid)
                && Node::checkNID($nid)
            )
                $this->_list[$nid] = $nid;
            elseif (is_a($nid, 'Entity'))
                $this->_list[$nid] = $nid->getID();
        }

        if (sizeof($this->_list) == 0)
            return false;
        return true;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @return boolean
     */
    public function unsetList(): bool
    {
        $this->_list = array();
        return true;
    }
}
