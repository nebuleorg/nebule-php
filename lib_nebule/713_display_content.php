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
class DisplayContent extends DisplayItemIconMessageSizeable implements DisplayInterface
{
    private ?Node $_nid = null;
    
    protected function _initialisation(): void
    {
        $this->setSize();
        $this->setRatio();
    }

    public function getHTML(): string
    {
        $result = '<div class="objectContent">' . "\n";
        $result .= match (get_class($this->_nid)) {
            'Nebule\Library\Entity' => $this->_getEntityHTML(),
            'Nebule\Library\Group' => $this->_getGroupHTML(),
            'Nebule\Library\Conversation' => $this->_getConversationHTML(),
            default => $this->_getObjectHTML(),
        };
        $result .= "</div>\n";

        return $result;
    }

    private function _getEntityHTML(): string
    {
        $result = '';
        if ($this->_nid->checkPresent()) {
            if ($this->_sizeCSS == 'medium' || $this->_sizeCSS == 'full') {
                $result .= '<div class="objectContentEntity">' . "\n<p>";
                $result .= sprintf($this->_translateInstance->getTranslate('::UniqueID'),
                    $this->convertInlineObjectColorIcon($this->_nid) . ' ' . '<b>' . $this->_nid->getID() . "</b>\n");
                $result .= "</p>\n";

                if ($this->_sizeCSS = 'full') {
                    // Liste des localisations.
                    $localisations = $this->_nid->getLocalisationsID();
                    if (sizeof($localisations) > 0) {
                        $result .= '<table border="0"><tr><td><td>' . $this->_translateInstance->getTranslate('::EntityLocalisation') . " :</td><td>\n";
                        foreach ($localisations as $localisation) {
                            $locObject = $this->_cacheInstance->newNodeByType($localisation);
                            $result .= "\t " . $this->convertInlineObjectColorIcon($localisation) . ' '
                                . $this->convertHypertextLink(
                                    $locObject->readOneLineAsText(),
                                    $locObject->readOneLineAsText()
                                ) . "<br />\n";
                        }
                        $result .= "</td></tr></table>\n";
                        unset($localisations, $localisation, $locObject);
                    }
                }
                $result .= "</div>\n";
            }
        } else
            $result .= $this->convertLineMessage('::display:content:errorNotAvailable', 'error');
        return $result;
    }

    private function _getGroupHTML(): string
    {
        $result = '';
        $result .= '<div class="objectContentGroup">' . "\n\t<p>"
            . sprintf($this->_translateInstance->getTranslate('::UniqueID'),
                $this->convertInlineObjectColorIcon($this->_nid) . ' ' . '<b>' . $this->_nid->getID() . "</b>\n");
        if ($this->_nid->getMarkClosed())
            $result .= "<br />\n" . $this->_translateInstance->getTranslate('::closeGroup') . ".\n";
        else
            $result .= "<br />\n" . $this->_translateInstance->getTranslate('::openGroup') . ".\n";
        $result .= "\t</p>\n</div>\n";
        return $result;
    }

    private function _getConversationHTML(): string
    {
        $result = '';
        $result .= '<div class="objectContentConversation">' . "\n\t<p>"
            . sprintf($this->_translateInstance->getTranslate('::UniqueID'),
                $this->convertInlineObjectColorIcon($this->_nid) . ' ' . '<b>' . $this->_nid->getID() . "</b>\n");
        if ($this->_nid->getMarkClosed())
            $result .= "<br />\n" . $this->_translateInstance->getTranslate('::closeConversation') . ".\n";
        else
            $result .= "<br />\n" . $this->_translateInstance->getTranslate('::openConversation') . ".\n";
        $result .= "\t</p>\n</div>\n";
        return $result;
    }

    private function _getObjectHTML(): string
    {
        $result = '';
        //$result .= $this->getDisplayAsObjectContent($this->_nid, $this->_sizeCSS, $this->_ratioCSS, $permitWarnProtected); FIXME
        return $result;
    }

    public function setNID(?Node $nid): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . gettype($nid), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node')) {
            $this->_nebuleInstance->getMetrologyInstance()->addLog('set nid ' . $nid->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '2cd59e0e');
            $this->_nid = $nid;
            $this->setName();
            $this->setShortName();
            $this->setStyleCSS();
        }
    }

    public static function displayCSS(): void
    {
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
.objectContent {
    font-size: 0.8rem;
    border: 0;
    padding: 3px 0 0 0;
    margin: 0;
    color: #000000;
    overflow: auto;
    clear: both;
    max-width: 100%;
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

.objectContentShort {
    width: 378px;
    max-height: 378px;
}

        <?php
    }
}
