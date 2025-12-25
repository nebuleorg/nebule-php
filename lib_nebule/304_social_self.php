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
class SocialSelf extends SocialMySelf implements SocialInterface {
    const TYPE='self';
    const LIMIT = 5;

    /**
     * @param array &$links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void {
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
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=self score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '46e5975d');

        foreach ($link->getSignersEID() as $signer) {
            $i = 0;
            while (isset($link->getParsed()['bl/rl/nid' . ++$i])) {
                if ($link->getParsed()['bl/rl/nid' . $i] == $signer){
                    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a4487d36');
                    return 1;
                }
                if ($i > $this::LIMIT)
                    break;
            }
        }

        //foreach ($link->getSignersEID() as $signer)
        //    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'acfd6bab');
        return 0;
    }
}
