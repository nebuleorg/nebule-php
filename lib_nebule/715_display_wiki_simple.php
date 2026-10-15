<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
* Classe DisplayWikiSimple
*     ---
* Parse a text with a simple Wiki syntax to HTML.
*     ---
* Example:
*  TODO
*     ---
* Usage:
*   - TODO
*
* @author Projet nebule
* @license GNU GPLv3
* @copyright Projet nebule
* @link www.nebule.org
*/
class DisplayWikiSimple {
    static public function parse($wikiText): string {
        $result = htmlspecialchars(strip_tags($wikiText), ENT_QUOTES, 'UTF-8');

        $result = preg_replace('/====== (.*?) ======/', '<h1>$1</h1>', $result);
        $result = preg_replace('/===== (.*?) =====/', '<h2>$1</h2>', $result);
        $result = preg_replace('/==== (.*?) ====/', '<h3>$1</h3>', $result);
        $result = preg_replace('/=== (.*?) ===/', '<h4>$1</h4>', $result);
        $result = preg_replace('/== (.*?) ==/', '<h5>$1</h5>', $result);

        $result = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $result);
        $result = preg_replace('/\/\/(.*?)\/\//', '<em>$1</em>', $result);
        $result = preg_replace('/\{\{(.*?)\}\}/', '<code>$1</code>', $result);
        $result = preg_replace('/\*\s+(.*?)(?=\n|$)/m', '<li>$1</li>', $result);
        $result = preg_replace('/(<li>.*<\/li>)/ms', '<ul>$0</ul>', $result);

        $result = preg_replace_callback(
        '/\[\[(.*?)(?:\|(.*?))?\]\]/',
            function($matches) {
                $url = $matches[1];
                $text = isset($matches[2]) ? $matches[2] : $matches[1];
                return '<a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($text) . '</a>';
                },
            $result
        );

        //$result = str_replace("\n\n", '<br />', $result);
        $result = nl2br($result);
        //$result = '<p>' . str_replace("\n", '</p><p>', $result) . '</p>';
        //$result = str_replace('<p></p>', '', $result);

        return $result;
    }

    static public function display(string $text): void { echo '<div class="simpleWiki">' . "\n" . self::parse($text) . "\n</div>\n"; }

    static public function displayCSS(): void {
        ?>

/*
* CSS from <?= basename(str_replace('\\', '/', __CLASS__)) ?>::<?= __FUNCTION__ ?>() on nebule library.
*
* (c) <?= \Nebule\Library\nebule::NEBULE_LICENCE ?> <?= \Nebule\Library\nebule::NEBULE_AUTHOR ?> - <?php echo \Nebule\Library\nebule::NEBULE_WEBSITE . "\n"; ?>
*
* This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
*   License as published by the Free Software Foundation, either version 3 of the License or (at your option) any
*   later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
*   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
*   details.
*
* You should have received a copy of the GNU General Public License along with this program.
*   See at the end of the code file. If not, see https://www.gnu.org/licenses/.
*/
.simpleWiki {
    background: rgba(255, 255, 255, 0.666);
    padding: 5px 10px 20px;
    font-family: sans-serif;
    color: #000000;
    overflow: scroll;
    text-align: justify;
}

.simpleWiki h1 {
    font-size: 2rem;
}

.simpleWiki h2 {
    font-size: 1.8rem;
}

.simpleWiki h3 {
    font-size: 1.6rem;
}

.simpleWiki h4 {
    font-size: 1.4rem;
}

.simpleWiki h5 {
    font-size: 1.2rem;
}

.simpleWiki strong {
    font-weight: bold;
}

.simpleWiki em {
}

.simpleWiki code {
}

.simpleWiki li {
}

.simpleWiki ul{
}

        <?php
    }
}