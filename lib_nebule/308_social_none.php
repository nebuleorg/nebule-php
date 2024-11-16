<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social des liens, supprime tout.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Retourne toujours un score de 0.
 */
class SocialNone extends SocialMySelf implements SocialInterface
{
    const TYPE='none';

    /**
     * Gère le classement social des liens.
     *
     * @param array &$links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void
    {
        $links = array();
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
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=none score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);
        foreach ($link->getSigners() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=none score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);

        return 0;
    }
}
