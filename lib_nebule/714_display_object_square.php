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
        $objectContentFlag = '';

        if ($this->_type == References::REFERENCE_OBJECT_JPEG || $this->_type == References::REFERENCE_OBJECT_PNG) {
            $objectContent = '<img src="o/' . $this->_nid . '" alt="I" class="objectSquareContent" />';
        } elseif ($this->_type == References::REFERENCE_OBJECT_TEXT) {
            $objectContent = '<div class="objectSquareContent"><p>' . $this->_nid->readAsText() . '</p></div>';
        } else {
            $this->_type = 'application/x-folder';
            $this->_displayName = false;
            $objectContent = '<div class="objectSquareContent"><p>' . $this->_nid->getFullName() . '</p></div>';
        }
        if ($this->_displayName)
            $objectContent .= '<div class="objectSquareTitle">' . $this->_nid->getFullName() . '</div>';
        if ($this->_displayType)
            $objectContentFlag .= $this->_translateInstance->getTranslate($this->_type);
        if ($this->_displayNID) {
            if ($objectContentFlag != '') $objectContentFlag .= '<br />' ;
            if ($this->_displayColor) $objectContentFlag .= $this->_displayInstance->prepareObjectColor($this->_nid);
            if ($this->_displayIcon) $objectContentFlag .= $this->_displayInstance->prepareObjectFace($this->_nid);
            $objectContentFlag .= $this->_nid->getID();
        }
        if ($objectContentFlag != '')
            $objectContent .= '<div class="objectSquareFlags">' . $objectContentFlag .'</div>';

        // Prepare result to display.
        $result = '<div class="layoutObject">' . "\n";
        $result .= $htLinkOpen . $divObjectOpen . $objectContent . $divObjectClose . $htLinkClose;
        $result .= '</div>' . "\n";

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

        /*if (!$this->_displayName) {
            $this->_displayRefs = false;
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }*/

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
            .objectSquareFlags {
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
            .objectSquareFlags img {
                height: 16px;
                width: 16px;
                vertical-align: baseline;
            }
            .objectSquareContainer:hover .objectSquareTitle,
            .objectSquareContainer:hover .objectSquareFlags { opacity: 1; }
        </style>
        <?php
    }
}

