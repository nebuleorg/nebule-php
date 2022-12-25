<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social des liens limités à tout sauf l'entité en cours d'affichage.
 * Ce peut être une entité différente de l'entité déverrouillée.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Si le signataire du lien est l'entité en cours d'affichage, retourne un score de 0.
 * Sinon retourne un score de 1.
 */
class SocialNotself extends SocialMySelf implements SocialInterface
{
    const SOCIAL_CLASS='notself';

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
        global $applicationInstance;

        if (is_a($applicationInstance, 'Applications'))
            $currentEntity = $this->_applicationInstance->getCurrentEntity();
        else
            $currentEntity = $this->_nebuleInstance->getCurrentEntity();

        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=notself score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités courante, retourne la valeur sociale 1.
        foreach ($link->getSigners() as $signer) {
            if ($signer != $currentEntity) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=notself score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        foreach ($link->getSigners() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=notself score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }
}
