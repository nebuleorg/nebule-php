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
class SocialNotself extends SocialMySelf implements SocialInterface {
    const TYPE='notself';
    const LIMIT = 5;

    /**
     * Gère le classement social des liens.
     *
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
     * Calcul le score social d'un lien.
     *
     * @param LinkRegister  &$link
     * @param string         $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=notself score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '95e7fdc2');

        foreach ($link->getSignersEID() as $signer) {
            $i = 0;
            while (isset($link->getParsed()['bl/rl/nid' . ++$i])) {
                if ($link->getParsed()['bl/rl/nid' . $i] == $signer){
                    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=self score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '58104e81');
                    return 0;
                }
                if ($i > $this::LIMIT)
                    break;
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        //foreach ($link->getSignersEID() as $signer)
        //    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=notself score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '75819b9c');
        return 1;
    }
}
