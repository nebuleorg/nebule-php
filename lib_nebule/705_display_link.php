<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayLink
 *       ---
 *  Example:
 *   FIXME
 *       ---
 *  Usage:
 *   FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayLink extends DisplayItem implements DisplayInterface
{
    public function getHTML(): string
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return ''; // TODO
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayLink(). */
            .layoutLink {
                margin-right: 5px;
            }

            .linkDisplay {
            }

            .linkDisplaySmall img {
                height: 16px;
            }

            .linkDisplayMedium img {
                height: 32px;
            }

            .linkDisplayLarge img {
                height: 64px;
            }
        </style>
        <?php
    }
}
