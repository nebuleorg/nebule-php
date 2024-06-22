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
class DisplayTitle extends DisplayItem implements DisplayInterface
{
    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayTitle(). */
            .layoutTitle {
                margin-bottom: 10px;
                margin-top: 32px;
                width: 100%;
                height: 32px;
                text-align: center;
            }

            .titleContent {
                margin: auto;
                text-align: center;
            }

            .titleContentDiv {
                display: inline-block;
                background: #333333;
                height: 32px;
                width: 384px;
            }

            .titleContentEntity {
                display: inline-block;
            }

            .titleContentEntity .layoutObject {
                margin: -11px 0 0 0;
            }

            .titleContentIcon {
                float: left;
            }

            .titleContentIcon img {
                height: 32px;
                width: 32px;
                margin-right: 5px;
            }

            .titleContent h1 {
                font-size: 1.2rem;
                font-weight: bold;
                color: #ababab;
                overflow: hidden;
                white-space: nowrap;
                margin-top: 5px;
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
