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
    $wikiText = preg_replace('/======(.*?)======/', '<h1>$1</h1>', $wikiText);
    $wikiText = preg_replace('/=====(.*?)=====/', '<h2>$1</h2>', $wikiText);
    $wikiText = preg_replace('/====(.*?)====/', '<h3>$1</h3>', $wikiText);
    $wikiText = preg_replace('/===(.*?)===/', '<h4>$1</h4>', $wikiText);
    $wikiText = preg_replace('/==(.*?)==/', '<h5>$1</h5>', $wikiText);

    $wikiText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $wikiText);
    $wikiText = preg_replace('/\/\/(.*?)\/\//', '<em>$1</em>', $wikiText);
    $wikiText = preg_replace('/\*\s+(.*?)(?=\n|$)/m', '<li>$1</li>', $wikiText);
    $wikiText = preg_replace('/(<li>.*<\/li>)/ms', '<ul>$0</ul>', $wikiText);

    $wikiText = preg_replace_callback(
    '/\[\[(.*?)(?:\|(.*?))?\]\]/',
        function($matches) {
            $url = $matches[1];
            $text = isset($matches[2]) ? $matches[2] : $matches[1];
            return '<a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($text) . '</a>';
            },
        $wikiText
    );

    $wikiText = '<p>' . str_replace("\n", '</p><p>', $wikiText) . '</p>';
    $wikiText = str_replace('<p></p>', '', $wikiText);

    return $wikiText;
    }
}