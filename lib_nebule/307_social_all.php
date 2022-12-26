<?php
declare(strict_types=1);
namespace Nebule\Library;

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
class SocialAll extends SocialMySelf implements SocialInterface
{
    const TYPE='all';

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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=all score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);
        foreach ($link->getSigners() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=all score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);

        return 1;
    }
}
