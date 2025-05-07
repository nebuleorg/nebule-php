<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social des liens limités à tout sauf l'entité en cours.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Si le signataire du lien est l'entité en cours, retourne un score de 0.
 * Sinon retourne un score de 1.
 */
class SocialNotMyself extends SocialMySelf implements SocialInterface
{
    const TYPE='notmyself';

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=notmyself score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités courante, retourne la valeur sociale 1.
        foreach ($link->getSignersEID() as $signer) {
            if ($signer != $this->_nebuleInstance->getEntitiesInstance()->getGhostEntityEID()) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=notmyself score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        foreach ($link->getSignersEID() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=notmyself score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }
}
