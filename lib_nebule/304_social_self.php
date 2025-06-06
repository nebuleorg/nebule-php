<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social des liens limités à l'entité en cours d'affichage.
 * Ce peut être une entité différente de l'entité déverrouillée.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Si le signataire du lien est l'entité en cours d'affichage, retourne un score de 1.
 * Sinon retourne un score de 0.
 */
class SocialSelf extends SocialMySelf implements SocialInterface
{
    const TYPE='self';

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
        global $applicationInstance;

        if (is_a($applicationInstance, 'Applications'))
            $currentEntity = $this->_nebuleInstance->getApplicationInstance()->getCurrentEntity();
        else
            $currentEntity = $this->_nebuleInstance->getEntitiesInstance()->getGhostEntityEID();

        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=self score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités courante, retourne la valeur sociale 1.
        foreach ($link->getSignersEID() as $signer) {
            if ($signer == $currentEntity) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        foreach ($link->getSignersEID() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }
}
