<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayList
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
class DisplayList extends DisplayItem implements DisplayInterface
{
    public function getHTML(): string
    {
        $this->_nebuleInstance->getMetrologyInstance()->addLog('get HTML content', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return ''; // TODO
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObjectsList(). */
            .layoutObjectsList {
                width: 100%;
            }

            .objectsListContent {
                margin: auto;
                text-align: center;
                font-size: 0;
                min-height: 34px;
                padding: 0 5px 5px 0;
                background: none;
            }

            /* max-width:2005px; */
            .objectsListContent p, .objectsListContent form {
                font-size: 1rem;
                color: #000000;
                text-align: left;
            }

            .objectsListContent p a, .objectsListContent form a {
                color: #000000;
            }
        </style>
        <?php
    }
}
