<?php
declare(strict_types=1);
namespace Nebule\Library;

class DisplayObjectSquare extends DisplayObject implements DisplayInterface {
    protected function _initialisation(): void {
        $this->setSocial('');
        $this->_ratioCSS = 'Square'; // Forced here. TODO to remove.
        $this->setEnableJS();
        $this->setActionsID();
    }

    public function getHTML(): string {
        if ($this->_nid === null)
            return '';

        $result = '';
        $this->_ratioCSS = 'Square'; // Forced here. TODO to remove.

        $divDisplayOpen = '<div class="layoutObject">' . "\n";
        $divDisplayClose = '</div>' . "\n";

        $htLinkOpen = '';
        $htLinkClose = '';
        if ($this->_displayLink) {
            if ($this->_link != '')
                $htLinkOpen = '<a href="' . $this->_link . '">';
            else
                $htLinkOpen = '<a href="' . $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($this->_nid) . '">';
            $htLinkClose = '</a>';
        }

        $divObjectOpen = '<div class="objectSquareContainer objectDisplay' . $this->_sizeCSS . ' objectSquareContainer' . $this->_sizeCSS . '">' . "\n";
        $divObjectClose = '</div>' . "\n";
        $objectContent = '';
        /*if ($this->_displayContent)
            $objectContent = $this->_displayInstance->getDisplayObjectContent($this->_nid, $this->_sizeCSS, $this->_ratioCSS);
        else {
            $objectContent = '<img title="' . $this->_name . '"' . $this->_styleCSS
                . ' alt="[C]" src="o/' . DisplayColor::ICON_ALPHA_COLOR_OID . '" class="iconColor' . $this->_sizeCSS . '"/>';
        }*/

        $type = $this->_nid->getType();
        if ($type == References::REFERENCE_OBJECT_JPEG || $type == References::REFERENCE_OBJECT_PNG) {
            $objectContent = '<img src="o/' . $this->_nid . '" alt="I" class="objectSquareContent" />';
            $objectContent .= '<div class="objectSquareTitle">' . $this->_nid->getFullName() . '</div>';
            $objectContent .= '<div class="objectSquareComment">' . $this->_translateInstance->getTranslate($type) . '<br />' . $this->_displayInstance->prepareObjectColor($this->_nid) . $this->_nid->getID() . '</div>';
        }
        else {
            $type = 'application/x-folder';
            $objectContent = '<div class="objectSquareContent"><p>' . $this->_nid->getFullName() . '</p></div>';
            $objectContent .= '<div class="objectSquareComment">' . $this->_translateInstance->getTranslate($type) . '<br />' . $this->_displayInstance->prepareObjectColor($this->_nid) . $this->_nid->getID() . '</div>';
        }

        // FIXME

        // Prepare result to display.
        $result = $divDisplayOpen;
        $result .= $htLinkOpen . $divObjectOpen . $objectContent . $divObjectClose . $htLinkClose;
        $result .= $divDisplayClose;

        return $result;
    }

    protected function _solveConflicts():void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
            $this->_displayFlagProtection = false;

        if (!$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            $this->_displayFlagObfuscate = false;

        if (!$this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = false;

        if ($this->_sizeCSS == 'Tiny')
            $this->_sizeCSS = 'Small';

        if ($this->_sizeCSS == 'Small') {
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }

        if ($this->_displayIconApp) {
            $this->_displayColor = false;
            $this->_icon = null;
        }

        if (!$this->_displayColor
            && !$this->_displayIcon
            && !$this->_displayIconApp
        )
            $this->_displayActions = false;

        if (!$this->_displayName) {
            $this->_displayRefs = false;
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }

        if (!$this->_configurationInstance->getOptionAsBoolean('displayEmotions'))
            $this->_displayFlagEmotions = false;

        if ($this->_sizeCSS == self::SIZE_TINY
            || $this->_sizeCSS == self::SIZE_SMALL
            || ($this->_sizeCSS == self::SIZE_MEDIUM && $this->_displayFlags)
            || !$this->_displayName
        )
            $this->_displayNID = false;
    }

    public static function displayCSS(): void {
        ?>

        <style>
            /* CSS for DisplayObjectSquare(). */
            .objectSquareContainer {
                position: relative;
                overflow: hidden;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: rgba(69 69 69 / 25%);
            }
            .objectSquareContainerSmall { width: 8em; height: 8em; }
            .objectSquareContainerMedium { width: 6em; height: 6em; }
            .objectSquareContainerLarge { width: 5em; height: 5em; }
            .objectSquareContainerFull { width: 4em; height: 4em; }
            .objectSquareContent {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                text-align: center;
                line-height: normal;
            }
            .objectSquareContent p {
                width: 90%;
                text-align: center;
                font-size: 14px;
                color: #fff;
                overflow: hidden;
            }
            .objectSquareTitle {
                position: absolute;
                top: 5%;
                left: 5%;
                width: 88%;
                background-color: rgba(69 69 69 / 50%);
                color: #fff;
                padding: 1%;
                text-align: left;
                font-size: 14px;
                text-wrap: nowrap;
                white-space: nowrap;
                overflow: hidden;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .objectSquareComment {
                position: absolute;
                bottom: 5%;
                left: 5%;
                width: 88%;
                background-color: rgba(69 69 69 / 50%);
                color: #fff;
                padding: 1%;
                text-align: left;
                font-size: 14px;
                text-wrap: nowrap;
                white-space: nowrap;
                overflow: hidden;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .objectSquareComment img {
                height: 16px;
                width: 16px;
                vertical-align: baseline;
            }
            .objectSquareContainer:hover .objectSquareTitle,
            .objectSquareContainer:hover .objectSquareComment { opacity: 1; }
        </style>
        <?php
    }
}

