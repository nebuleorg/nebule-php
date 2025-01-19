<?php
declare(strict_types=1);
namespace Nebule\Library;

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
class SocialOffList extends SocialMySelf implements SocialInterface
{
    const TYPE='offlist';

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
     * @param LinkRegister  &$link
     * @param string         $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=onlist score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        if (sizeof($this->_list) == 0) {
            return 0;
        }

        // Si l'entité signataire du lien est une des entités autorités, retourne la valeur sociale 0.
        foreach ($this->_list as $id) {
            foreach ($link->getSignersEID() as $signer) {
                if ($signer == $id) {
                    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=offlist score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                    return 0;
                }
            }
        }

        // Sinon par défaut retourne la valeur sociale 1.
        foreach ($link->getSignersEID() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=offlist score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 1;
    }
}
