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
     * @param LinkRegister  &$link
     * @param string         $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=self score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        foreach ($link->getSignersEID() as $signer) {
            if ($link->getParsed()['bl/rl/nid1'] == $signer
                || (isset($link->getParsed()['bl/rl/nid2']) && $link->getParsed()['bl/rl/nid2'] == $signer)
                || (isset($link->getParsed()['bl/rl/nid3']) && $link->getParsed()['bl/rl/nid3'] == $signer)
            ) {
                $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                return 1;
            }
        }

        //foreach ($link->getSignersEID() as $signer)
        //    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }
}
