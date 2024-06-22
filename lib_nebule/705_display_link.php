<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe Display
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayLink extends DisplayItem implements DisplayInterface
{
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

    public function getHTML(): string
    {
        return ''; // TODO
    }

    public function display(): void
    {
        echo ''; // TODO
    }
}
