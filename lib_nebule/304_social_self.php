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
 */
class SocialSelf extends SocialMySelf implements SocialInterface {
    const TYPE='self';

    /**
     * @param array &$links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void {
        foreach ($links as $i => $link) {
            if ($this->linkSocialScore($link) != 1)
                unset($links[$i]);
        }
    }

    /**
     * @param LinkRegister  &$link
     * @param string         $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=self score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '46e5975d');
        if (in_array($link->getParsed()['bl/rl/nid1'], $link->getSignersEID()))
            return 1;
        return 0;
    }
}
