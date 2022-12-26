<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe de gestion du côté social stricte des liens.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Si le signataire du lien est une des entités autorités locales, retourne un score de 1.
 * Sinon retourne un score de 0.
 */
class SocialStrict extends SocialMySelf implements SocialInterface
{
    const TYPE='strict';

    /**
     * Gère le classement social des liens.
     *
     * @param array  &$links
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
     * @param Link   &$link
     * @param string $socialClass
     * @return float
     */
    public function linkSocialScore(Link &$link, string $socialClass = ''): float
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('Ask link social=strict score for ' . $link->getRaw(), Metrology::LOG_LEVEL_DEBUG);

        // Si l'entité signataire du lien est une des entités autorités, retourne la valeur sociale 1.
        foreach ($this->_nebuleInstance->getLocalAuthorities() as $autority) {
            foreach ($link->getSigners() as $signer) {
                if ($signer == $autority) {
                    $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=strict score 1 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
                    return 1;
                }
            }
        }

        // Sinon par défaut retourne la valeur sociale 0.
        foreach ($link->getSigners() as $signer)
            $this->_nebuleInstance->getMetrologyInstance()->addLog('Link social=strict score 0 for ' . $signer, Metrology::LOG_LEVEL_DEBUG);
        return 0;
    }
}
