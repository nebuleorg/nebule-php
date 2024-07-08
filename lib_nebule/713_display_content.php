<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayContent
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
class DisplayContent extends DisplayItemSizeable implements DisplayInterface
{
    public function getHTML(): string
    {
        return ''; // TODO
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS des fonctions getDisplayObjectContent() et getDisplayAsObjectContent(). */
            .objectContent {
            }

            /* .layoutInformation { max-width:2000px; } */
            .objectContent .layoutInformation {
                margin-left: -3px;
            }

            .objectContentObject {
            }

            .objectContentEntity {
            }

            .objectContentGroup {
            }

            .objectContentConversation {
            }

            .objectContentText {
                font-size: 0.8rem;
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
                color: #000000;
                font-family: sans-serif;
            }

            .objectContentCode {
                font-size: 0.8rem;
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
                color: #000000;
                font-family: monospace;
            }

            .objectContentAudio {
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }
        </style>
        <?php
    }
}
