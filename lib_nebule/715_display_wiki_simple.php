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
class DisplayWikiSimple
{
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

        $result = str_replace("\n\n", '<br />', $result);
        //$result = '<p>' . str_replace("\n", '</p><p>', $result) . '</p>';
        //$result = str_replace('<p></p>', '', $result);

        return $result;
    }
}