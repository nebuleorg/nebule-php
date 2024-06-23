<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayObject
 *      ---
 * Example:
 *  FIXME
 *      ---
 * Usage:
 *  FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayObject extends DisplayItemSizeable implements DisplayInterface
{
    private $_nid = null;
    private $_displayColor = true;
    private $_displayIconApp = false;
    private $_displayRefs = false;
    private $_displayName = true;
    private $_displayNID = false;
    private $_displayFlags = false;
    private $_displayFlagEmotions = false;
    private $_displayFlagProtection = false;
    private $_displayFlagObfuscate = false;
    private $_displayFlagUnlocked = false;
    private $_displayFlagActivated = false;
    private $_displayFlagState = false;
    private $_displayStatus = false;
    private $_displayContent = false;
    private $_displayObjectActions = true;
    private $_displayLink2Object = true;
    private $_displayLink2Refs = true;
    private $_displayJS = true;
    private $_displaySelfHook = true;
    private $_displayTypeHook = true;
    private $_social = '';
    private $_type = '';
    private $_name = '';
    private $_appShortname = '';

    protected function _init(): void
    {
        $this->setSize(self::SIZE_MEDIUM);
        $this->setRatio(self::RATIO_SHORT);
        $this->setSocial('');
        $this->setType('');
        $this->setName('');
        $this->setAppShortName('');
    }

    public function getHTML(): string
    {
        if ($this->_nid === null)
            return '';

        $result = '';



        if (!isset($param['flagProtection'])
            || $param['flagProtection'] !== true
        )
            $param['flagProtection'] = false; // Par défaut à false.
        if ($this->_displayFlagProtection) {
            if (!isset($param['flagProtectionIcon'])
                || $param['flagProtectionIcon'] == ''
                || !Node::checkNID($param['flagProtectionIcon'])
                || !$this->_nebuleInstance->getIoInstance()->checkLinkPresent($param['flagProtectionIcon'])
            )
                $param['flagProtectionIcon'] = Displays::DEFAULT_ICON_LK;
            if (isset($param['flagProtectionText']))
                $param['flagProtectionText'] = trim(filter_var($param['flagProtectionText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagProtectionText'])
                || trim($param['flagProtectionText']) == ''
            ) {
                if ($param['flagProtection'])
                    $param['flagProtectionText'] = ':::display:object:flag:protected';
                else
                    $param['flagProtectionText'] = ':::display:object:flag:unprotected';
            }
            if (isset($param['flagProtectionLink']))
                $param['flagProtectionLink'] = trim(filter_var($param['flagProtectionLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagProtectionLink'])
                || trim($param['flagProtectionLink']) == ''
            )
                $param['flagProtectionLink'] = null;
        }

        if (!isset($param['flagObfuscate'])
            || $param['flagObfuscate'] !== true
        )
            $param['flagObfuscate'] = false; // Par défaut à false.
        if ($this->_displayFlagObfuscate) {
            if (!isset($param['flagObfuscateIcon'])
                || $param['flagObfuscateIcon'] == ''
                || !Node::checkNID($param['flagObfuscateIcon'])
                || !$this->_nebuleInstance->getIoInstance()->checkLinkPresent($param['flagObfuscateIcon'])
            )
                $param['flagObfuscateIcon'] = Displays::DEFAULT_ICON_LC;
            if (isset($param['flagObfuscateText']))
                $param['flagObfuscateText'] = trim(filter_var($param['flagObfuscateText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagObfuscateText'])
                || trim($param['flagObfuscateText']) == ''
            ) {
                if ($param['flagObfuscate'])
                    $param['flagObfuscateText'] = ':::display:object:flag:obfuscated';
                else
                    $param['flagObfuscateText'] = ':::display:object:flag:unobfuscated';
            }
            if (isset($param['flagObfuscateLink']))
                $param['flagObfuscateLink'] = trim(filter_var($param['flagObfuscateLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagObfuscateLink'])
                || trim($param['flagObfuscateLink']) == ''
            )
                $param['flagObfuscateLink'] = null;
        }

        if (!isset($param['flagUnlocked'])) {
            $param['flagUnlocked'] = false; // Par défaut à false.
            if (is_a($this->_nid, 'Nebule\Library\Entity')) {
                // Extrait l'état de verrouillage de l'objet entité.
                $param['flagUnlocked'] = $this->_nid->issetPrivateKeyPassword();
                // Vérifie si c'est l'entité courante.
                if ($this->_nid->getID() == $this->_nebuleInstance->getCurrentEntity()
                    && $this->_unlocked
                )
                    $param['flagUnlocked'] = true;
            }
        }
        // Lisse la valeur binaire.
        if ($param['flagUnlocked'] !== true) {
            $param['flagUnlocked'] = false; // Par défaut à false.
        }
        if ($this->_displayFlagUnlocked) {
            if (!isset($param['flagUnlockedIcon'])
                || $param['flagUnlockedIcon'] == ''
                || !Node::checkNID($param['flagUnlockedIcon'])
                || !$this->_nebuleInstance->getIoInstance()->checkLinkPresent($param['flagUnlockedIcon'])
            )
                $param['flagUnlockedIcon'] = Displays::DEFAULT_ICON_KEY;
            if (isset($param['flagUnlockedText']))
                $param['flagUnlockedText'] = trim(filter_var($param['flagUnlockedText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagUnlockedText'])
                || trim($param['flagUnlockedText']) == ''
            ) {
                if ($param['flagUnlocked'])
                    $param['flagUnlockedText'] = ':::display:object:flag:locked';
                else
                    $param['flagUnlockedText'] = ':::display:object:flag:unlocked';
            }
            if (isset($param['flagUnlockedLink']))
                $param['flagUnlockedLink'] = trim(filter_var($param['flagUnlockedLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagUnlockedLink'])
                || trim($param['flagUnlockedLink']) == ''
            )
                $param['flagUnlockedLink'] = null;
        }

        if (!isset($param['flagActivated'])
            || $param['flagActivated'] !== true
        )
            $param['flagActivated'] = false; // Par défaut à false.

        if ($this->_displayFlagActivated) {
            if (!isset($param['flagActivatedDesc'])
                || strlen(trim($param['flagActivatedDesc'])) == 0
            ) {
                if ($param['flagActivated'])
                    $param['flagActivatedDesc'] = ':::display:content:Activated';
                else
                    $param['flagActivatedDesc'] = ':::display:content:NotActivated';
            } else
                $param['flagActivatedDesc'] = trim($param['flagActivatedDesc']);
        }

        $flagStateContentIcon = '';
        $flagStateContentDesc = '';
        if ($this->_displayFlagState) {
            if (!isset($param['flagState'])
                || strlen(trim($param['flagState'])) == 0
            ) {
                if ($this->_nid->getMarkDanger())
                    $param['flagState'] = 'e';
                elseif ($this->_nid->getMarkWarning())
                    $param['flagState'] = 'w';
                elseif ($this->_nid->checkPresent())
                    $param['flagState'] = 'o';
                else
                    $param['flagState'] = 'n';
            }
            if ($param['flagState'] == 'e') {
                $flagStateContentIcon = Displays::DEFAULT_ICON_IERR;
                $flagStateContentDesc = ':::display:content:errorBan';
            } elseif ($param['flagState'] == 'w') {
                $flagStateContentIcon = Displays::DEFAULT_ICON_IWARN;
                $flagStateContentDesc = ':::display:content:warningTaggedWarning';
            } elseif ($param['flagState'] == 'o') {
                $flagStateContentIcon = Displays::DEFAULT_ICON_IOK;
                $flagStateContentDesc = ':::display:content:OK';
            } else {
                $param['flagState'] = 'n';
                $flagStateContentIcon = Displays::DEFAULT_ICON_IERR;
                $flagStateContentDesc = ':::display:content:errorNotAvailable';
            }
            if (isset($param['flagStateDesc'])
                && strlen(trim($param['flagStateDesc'])) != 0
            )
                $flagStateContentDesc = trim(filter_var($param['flagStateDesc'], FILTER_SANITIZE_STRING));
        } else {
            $param['flagState'] = 'n';
            $param['flagStateDesc'] = '';
        }

        if (!isset($param['flagMessage'])
            || trim($param['flagMessage']) == ''
        )
            $param['flagMessage'] = null; // Par défaut vide.
        else
            $param['flagMessage'] = trim(filter_var($param['flagMessage'], FILTER_SANITIZE_STRING));

        if (!isset($param['flagTargetObject'])
            || trim($param['flagTargetObject']) == ''
        )
            $param['flagTargetObject'] = null; // Par défaut vide.
        else {
            $param['flagTargetObject'] = trim(filter_var($param['flagTargetObject'], FILTER_SANITIZE_STRING));
            if (!Node::checkNID($param['flagTargetObject'])) {
                $param['flagTargetObject'] = null;
            }
        }

        if ($this->_displaySelfHook) {
            if (isset($param['selfHookName']))
                $param['selfHookName'] = trim(filter_var($param['selfHookName'], FILTER_SANITIZE_STRING));
            else
                $param['selfHookName'] = '';
            if ($param['selfHookName'] == '') {
                if (is_a($this->_nid, 'Nebule\Library\Entity'))
                    $param['selfHookName'] = 'selfMenuEntity';
                elseif (is_a($this->_nid, 'Nebule\Library\Conversation'))
                    $param['selfHookName'] = 'selfMenuConversation';
                elseif (is_a($this->_nid, 'Nebule\Library\Group'))
                    $param['selfHookName'] = 'selfMenuGroup';
                elseif (is_a($this->_nid, 'Nebule\Library\Transaction'))
                    $param['selfHookName'] = 'selfMenuTransaction';
                elseif (is_a($this->_nid, 'Nebule\Library\Wallet'))
                    $param['selfHookName'] = 'selfMenuWallet';
                elseif (is_a($this->_nid, 'Nebule\Library\Token'))
                    $param['selfHookName'] = 'selfMenuToken';
                elseif (is_a($this->_nid, 'Nebule\Library\TokenPool'))
                    $param['selfHookName'] = 'selfMenuTokenPool';
                elseif (is_a($this->_nid, 'Nebule\Library\Currency'))
                    $param['selfHookName'] = 'selfMenuCurrency';
                else
                    $param['selfHookName'] = 'selfMenuObject';
            }
        } else
            $param['selfHookName'] = '';

        if ($this->_displayTypeHook) {
            if (isset($param['typeHookName']))
                $param['typeHookName'] = trim(filter_var($param['typeHookName'], FILTER_SANITIZE_STRING));else
                $param['typeHookName'] = '';
            if ($param['typeHookName'] == '') {
                if (is_a($this->_nid, 'Nebule\Library\Entity'))
                    $param['typeHookName'] = 'typeMenuEntity';
                elseif (is_a($this->_nid, 'Nebule\Library\Conversation'))
                    $param['typeHookName'] = 'typeMenuConversation';
                elseif (is_a($this->_nid, 'Nebule\Library\Group'))
                    $param['typeHookName'] = 'typeMenuGroup';
                elseif (is_a($this->_nid, 'Nebule\Library\Transaction'))
                    $param['typeHookName'] = 'typeMenuTransaction';
                elseif (is_a($this->_nid, 'Nebule\Library\Wallet'))
                    $param['typeHookName'] = 'typeMenuWallet';
                elseif (is_a($this->_nid, 'Nebule\Library\Token'))
                    $param['typeHookName'] = 'typeMenuToken';
                elseif (is_a($this->_nid, 'Nebule\Library\TokenPool'))
                    $param['typeHookName'] = 'typeMenuTokenPool';
                elseif (is_a($this->_nid, 'Nebule\Library\Currency'))
                    $param['typeHookName'] = 'typeMenuCurrency';
                else
                    $param['typeHookName'] = 'typeMenuObject';
            }
        } else
            $param['typeHookName'] = '';

        if (!isset($param['selfHookList'])
            || !is_array($param['selfHookList'])
        )
            $param['selfHookList'] = array();

        // Résoud les conflits.
        $this->_solveConflicts();
        

        // Prépare les contenus.
        $objectColor = $this->_nid->getPrimaryColor();
        $ObjectActionsID = '0';
        if ($this->_displayObjectActions
            && $this->_displayJS
        )
            $ObjectActionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        $contentDisplayColor = '';
        if ($this->_displayColor) {
            $contentDisplayColor = '<img title="' . $this->_name;
            $contentDisplayColor .= '" style="background:#' . $objectColor;
            $contentDisplayColor .= ';" alt="[C]" src="o/' . displays::DEFAULT_ICON_ALPHA_COLOR . '" ';
            if ($this->_displayObjectActions
                && $this->_displayJS
            )
                $contentDisplayColor .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayColor .= '/>';
        }

        $contentDisplayIcon = '';
        if ($this->_icon !== null) {
            $contentDisplayIcon = '<img title="' . $this->_name;
            $contentDisplayIcon .= '" style="background:#' . $objectColor;
            $contentDisplayIcon .= ';" alt="[I]" src="' . $this->_getObjectIconHTML($this->_nid, $this->_icon) . '" ';
            if ($this->_displayObjectActions
                && $this->_displayJS
            )
                $contentDisplayIcon .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayIcon .= '/>';
        }

        if ($this->_displayIconApp) {
            $contentDisplayIcon = '<div class="objectTitleIconsApp" style="background:#' . $objectColor . ';">';
            $contentDisplayIcon .= '<div><span class="objectTitleIconsAppShortname">' . $contentDisplayAppShortName . '</span><br /><span class="objectTitleIconsAppTitle">' . $this->_name . '</span></div>';
            $contentDisplayIcon .= '</div>';
        }

        $titleLinkOpenImg = '';
        $titleLinkOpenName = '';
        $titleLinkCloseImg = '';
        $titleLinkCloseName = '';
        if ($this->_displayLink2Object) {
            if ($this->_displayObjectActions
                && $this->_displayJS
            ) {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenName = '<a href="' . $param['link2Object'] . '">';
                else
                    $titleLinkOpenName = '<a href="' . $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($this->_nid) . '">';
                $titleLinkCloseName = '</a>';
            } else {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenImg = '<a href="' . $param['link2Object'] . '">' . "\n";
                else
                    $titleLinkOpenImg = '<a href="' . $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($this->_nid) . '">';
                $titleLinkOpenName = $titleLinkOpenImg;
                $titleLinkCloseImg = '</a>';
                $titleLinkCloseName = $titleLinkCloseImg;
            }
        }

        $status = '';
        if ($this->_displayStatus
            && isset($param['status'])
        ) {
            $status = trim(filter_var($param['status'], FILTER_SANITIZE_STRING));
            if ($status == '')
                $status = $this->_traductionInstance->getTraduction($param['objectType']);
            if ($status == '')
                $this->_displayStatus = false;
        }

        // Prépare le menu si besoin.
        $divTitleMenuOpen = '';
        $divTitleMenuClose = '';
        $divTitleMenuTitleOpen = '';
        $divTitleMenuTitleClose = '';
        $divTitleMenuIconsOpen = '';
        $divTitleMenuIconsClose = '';
        $divTitleMenuContentOpen = '';
        $divTitleMenuContentClose = '';
        $divTitleMenuActionsOpen = '';
        $divTitleMenuActionsClose = '';
        $menuContent = '';
        $menuActions = '';
        if ($this->_displayLink2Object
            && $this->_displayObjectActions
        ) {
            $menuContent = '   <div class="objectMenuContentMsg objectMenuContentMsgID">ID:';
            $menuContent .= $this->_nid->getID();
            $menuContent .= '</div>' . "\n";
            if ($this->_displayFlags) {
                if ($this->_displayFlagState) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagState'] == 'e')
                        $menuContent .= 'Error';
                    elseif ($param['flagState'] == 'w')
                        $menuContent .= 'Warn';
                    elseif ($param['flagState'] == 'n')
                        $menuContent .= 'Error';
                    else
                        $menuContent .= 'OK';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        false,
                        $flagStateContentIcon,
                        $flagStateContentDesc,
                        '');
                    $menuContent .= $this->_traductionInstance->getTraduction($flagStateContentDesc);
                    $menuContent .= '</div>' . "\n";
                }
                if ($this->_displayFlagProtection) {
                    if ($param['flagProtectionLink'] != '')
                        $menuContent .= '<a href="' . $param['flagProtectionLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagProtection'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $param['flagProtection'],
                        $param['flagProtectionIcon'],
                        $param['flagProtectionText'],
                        $param['flagProtectionText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagProtectionText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagProtectionLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagObfuscate) {
                    if ($param['flagObfuscateLink'] != '')
                        $menuContent .= '<a href="' . $param['flagObfuscateLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagObfuscate'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $param['flagObfuscate'],
                        $param['flagObfuscateIcon'],
                        $param['flagObfuscateText'],
                        $param['flagObfuscateText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagObfuscateText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagObfuscateLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagUnlocked) {
                    if ($param['flagUnlockedLink'] != '')
                        $menuContent .= '<a href="' . $param['flagUnlockedLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagUnlocked'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $param['flagUnlocked'],
                        $param['flagUnlockedIcon'],
                        $param['flagUnlockedText'],
                        $param['flagUnlockedText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagUnlockedText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagUnlockedLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($this->_displayFlagActivated) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagActivated'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        $param['flagActivated'],
                        Displays::DEFAULT_ICON_LL,
                        ':::display:object:flag:unactivated',
                        ':::display:object:flag:activated');
                    if ($param['flagActivated'])
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:activated');
                    else
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:unactivated');
                    $menuContent .= '</div>' . "\n";
                }
                if ($param['flagMessage'] != null) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgInfo">';
                    $menuContent .= $this->_getObjectFlagHTML(
                        false,
                        Displays::DEFAULT_ICON_IINFO,
                        '',
                        '-');
                    $menuContent .= $param['flagMessage'];
                    $menuContent .= '</div>' . "\n";
                }
                if ($param['flagTargetObject'] != null) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgtargetObject">';
                    $paramTiny = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'tiny',
                        'displayRatio' => 'short',
                    );
                    // ATTENTION à une possible boucle infinie !
                    $menuContent .= $this->_displayInstance->getDisplayObject($param['flagTargetObject'], $paramTiny);
                    unset($paramTiny);
                    $menuContent .= '</div>' . "\n";
                }
                // $param['flagTargetObject']
                if ($this->_displayFlagEmotions
                    && $this->_displayJS
                ) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgEmotions">';
                    $menuContent .= $this->_getObjectFlagEmotionsHTML($this->_nid, true);
                    $menuContent .= '</div>' . "\n";
                }
            }

            if ($this->_displayJS) {
                $divTitleMenuOpen = '  <div class="objectTitleMenuContentLayout" id="objectTitleMenu-' . $ObjectActionsID . '" '
                    . "onclick=\"display_hide('objectTitleMenu-" . $ObjectActionsID . "');\" >\n";
                $divTitleMenuOpen .= '   <div class="objectTitleMenuContent">' . "\n";
                $divTitleMenuTitleOpen = '    <div class="objectTitleMedium objectTitleMediumLong">' . "\n";
                $divTitleMenuIconsOpen = '    <div class="objectTitleIcons">' . "\n";
                $titleMenuIcons = $contentDisplayColor . $contentDisplayIcon . "\n";
                $divTitleMenuIconsClose = '    </div>' . "\n";
                $divTitleMenuTitleClose = '    </div>' . "\n";
                $divTitleMenuContentOpen = '    <div class="objectMenuContent">' . "\n";
                $divTitleMenuContentClose = '    </div>' . "\n";
                $menuActions = $this->_displayInstance->getDisplayObjectHookList(
                    $param['selfHookName'],
                    $param['typeHookName'],
                    $this->_nid,
                    true,
                    $this->_sizeCSS . 'Long',
                    $param['selfHookList']);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $this->_sizeCSS . 'Long">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
                $divTitleMenuClose = '   </div></div>' . "\n";
            } else {
                $divMenuContentOpen = '  <div class="objectMenuContent objectDisplay' . $this->_sizeCSS . ' objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
                $divMenuContentClose = '  </div>' . "\n";
                $menuActions = $this->_displayInstance->getDisplayObjectHookList(
                    $param['selfHookName'],
                    $param['typeHookName'],
                    $this->_nid,
                    false,
                    $this->_sizeCSS . $this->_ratioCSS,
                    $param['selfHookList']);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
            }
        }

        // Assemble les contenus.
        $divDisplayOpen = '';
        $divDisplayClose = '';
        $divTitleOpen = '';
        $divTitleClose = '';
        $titleContent = '';
        $divTitleIconsOpen = '';
        $divTitleIconsClose = '';
        $titleIconsContent = '';
        $divTitleTextOpen = '';
        $divTitleTextClose = '';
        $titleTextContent = '';
        $divTitleRefsOpen = '';
        $divTitleRefsClose = '';
        $titleRefsContent = '';
        $divTitleNameOpen = '';
        $divTitleNameClose = '';
        $titleNameContent = '';
        $divTitleIdOpen = '';
        $divTitleIdClose = '';
        $titleIdContent = '';
        $divTitleFlagsOpen = '';
        $divTitleFlagsClose = '';
        $titleFlagsContent = '';
        $divTitleStatusOpen = '';
        $divTitleStatusClose = '';
        $titleStatusContent = '';
        $divObjectOpen = '';
        $divObjectClose = '';
        $objectContent = '';
        if ($this->_sizeCSS == 'tiny')
            $result = $titleLinkOpenName . '<span style="font-size:1em" class="objectTitleIconsInline">' . $contentDisplayColor . $contentDisplayIcon . '</span>' . $this->_name . $titleLinkCloseName;
        else {
            $divDisplayOpen = '<div class="layoutObject">' . "\n";
            $divDisplayClose = '</div>' . "\n";
            $divTitleOpen = ' <div class="objectTitle objectDisplay' . $this->_sizeCSS . ' objectTitle' . $this->_sizeCSS . ' objectDisplay' . $this->_sizeCSS . $this->_ratioCSS . '">' . "\n";
            $divTitleClose = ' </div>' . "\n";
            $divTitleIconsOpen = '  <div class="objectTitleIcons">';
            $divTitleIconsClose = '</div>' . "\n";
            if ($this->_displayColor
                || $this->_icon !== null
                || $this->_displayIconApp
            )
                $titleIconsContent = $contentDisplayColor . $contentDisplayIcon;
            if ($this->_displayName) {
                $padding = 0;
                if ($this->_displayColor)
                    $padding += 1;
                if ($this->_icon !== null)
                    $padding += 1;
                if ($this->_displayIconApp)
                    $padding += 1;
                $divTitleTextOpen = '  <div class="objectTitleText objectTitle' . $this->_sizeCSS . 'Text objectTitleText' . $padding . '">' . "\n";
                $divTitleTextClose = '  </div>' . "\n";
                $divTitleRefsOpen = '   <div class="objectTitleRefs objectTitle' . $this->_sizeCSS . 'Refs">';
                $divTitleRefsClose = '</div>' . "\n";
                $divTitleNameOpen = '   <div class="objectTitleName objectTitle' . $this->_sizeCSS . 'Name">';
                $divTitleNameClose = '</div>' . "\n";
                $divTitleFlagsOpen = '   <div class="objectTitleFlags objectTitle' . $this->_sizeCSS . 'Flags">' . "\n";
                $divTitleFlagsClose = '   </div>' . "\n";
                $divTitleStatusOpen = '    <div class="objectTitleStatus">';
                $divTitleStatusClose = '</div>' . "\n";
                if ($this->_displayRefs && sizeof($param['objectRefs']) > 0 && $param['objectRefs'] !== null)
                    $titleRefsContent = $this->_getObjectRefsHTML($param['objectRefs']);
                if ($this->_displayNID) {
                    $divTitleIdOpen = '    <div class="objectTitleID">';
                    $divTitleIdClose = '</div>' . "\n";
                    $titleIdContent = $this->_nid->getID();
                }
                $titleNameContent = $this->_name;
                if ($this->_displayFlags) {
                    if ($this->_displayFlagState) {
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            false,
                            $flagStateContentIcon,
                            $flagStateContentDesc,
                            '');
                    }
                    if ($this->_displayFlagProtection) {
                        if ($param['flagProtectionLink'] != '') {
                            $titleFlagsContent .= '<a href="' . $param['flagProtectionLink'] . '">';
                        }
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $param['flagProtection'],
                            $param['flagProtectionIcon'],
                            $param['flagProtectionText'],
                            $param['flagProtectionText']);
                        if ($param['flagProtectionLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagObfuscate) {
                        if ($param['flagObfuscateLink'] != '')
                            $titleFlagsContent .= '<a href="' . $param['flagObfuscateLink'] . '">';
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $param['flagObfuscate'],
                            $param['flagObfuscateIcon'],
                            $param['flagObfuscateText'],
                            $param['flagObfuscateText']);
                        if ($param['flagObfuscateLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagUnlocked) {
                        if ($param['flagUnlockedLink'] != '')
                            $titleFlagsContent .= '<a href="' . $param['flagUnlockedLink'] . '">';
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $param['flagUnlocked'],
                            $param['flagUnlockedIcon'],
                            $param['flagUnlockedText'],
                            $param['flagUnlockedText']);
                        if ($param['flagUnlockedLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($this->_displayFlagActivated) {
                        $titleFlagsContent .= $this->_getObjectFlagHTML(
                            $param['flagActivated'],
                            Displays::DEFAULT_ICON_LL,
                            ':::display:object:flag:unactivated',
                            ':::display:object:flag:activated');
                    }
                    if ($this->_displayFlagEmotions)
                        $titleFlagsContent .= $this->_getObjectFlagEmotionsHTML($this->_nid, false);
                }
                if ($this->_displayStatus)
                    $titleStatusContent = $status;
            }
            $titleContent = $titleLinkOpenImg . "\n" . $divTitleIconsOpen . $titleIconsContent . $divTitleIconsClose . $titleLinkCloseImg . "\n";
            $titleContent .= $divTitleTextOpen;
            $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
            $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
            $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
            $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
            $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
            $titleContent .= $divTitleFlagsClose;
            $titleContent .= $divTitleTextClose;
            if ($this->_displayJS
                && $this->_displayObjectActions
            ) {
                $titleContent .= $divTitleMenuOpen;
                $titleContent .= $divTitleMenuTitleOpen;
                $titleContent .= $divTitleMenuIconsOpen . $titleMenuIcons . $divTitleMenuIconsClose;
                $titleContent .= $divTitleTextOpen;
                $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
                $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
                $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
                $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
                $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
                $titleContent .= $divTitleFlagsClose;
                $titleContent .= $divTitleTextClose;
                $titleContent .= $divTitleMenuTitleClose;
                $titleContent .= $divTitleMenuContentOpen . $menuContent . $divTitleMenuContentClose;
                $titleContent .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
                $titleContent .= $divTitleMenuClose;
            }

            if ($this->_displayContent)
                $objectContent = $this->_displayInstance->getDisplayObjectContent($this->_nid, $this->_sizeCSS, $this->_ratioCSS);

            // Prépare le résultat à afficher.
            $result = $divDisplayOpen;
            $result .= $divTitleOpen . $titleContent . $divTitleClose;
            if (!$this->_displayJS
                && $this->_displayObjectActions
            ) {
                $result .= $divMenuContentOpen . $menuContent . $divMenuContentClose;
                $result .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
            }
            $result .= $divObjectOpen . $objectContent . $divObjectClose;
            $result .= $divDisplayClose;
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare l'icône de l'objet.
     * Si une icône est imposée, elle est utilisée.
     * Sinon fait une recherche par référence en fonction du type de l'objet.
     * Une mise à jour éventuelle de l'icône est recherchée.
     * Si l'objet de l'icône est présent, génère un chemin direct pour améliorer les performances.
     *
     * @param Node   $object
     * @param string $icon
     * @return string
     */
    private function _getObjectIconHTML(Node $object, string $icon = ''): string
    {
        if ($icon != ''
            && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($icon)
        ) {
            $instanceIcon = $this->_nebuleInstance->newObject($icon);
        } else {
            if (is_a($object, 'Nebule\Library\Entity'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newEntity(Displays::REFERENCE_ICON_ENTITY));
            elseif (is_a($object, 'Nebule\Library\Conversation'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newConversation(Displays::REFERENCE_ICON_CONVERSATION));
            elseif (is_a($object, 'Nebule\Library\Group'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newGroup(Displays::REFERENCE_ICON_GROUP));
            elseif (is_a($object, 'Nebule\Library\Wallet'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Transaction'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Token'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\TokenPool'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            elseif (is_a($object, 'Nebule\Library\Currency'))
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT)); // TODO
            else
                $icon = $this->_displayInstance->getImageByReference($this->_nebuleInstance->newObject(Displays::REFERENCE_ICON_OBJECT));
            $instanceIcon = $this->_nebuleInstance->newObject($icon);
        }

        // Cherche une mise à jour éventuelle.
        $updateIcon = $this->_displayInstance->getImageUpdate($icon); // FIXME TODO ERROR
        $updateIcon = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256'; // FIXME

        // Retourne un chemin direct si l'objet est présent.
        if ($this->_nebuleInstance->getIoInstance()->checkObjectPresent($updateIcon))
            return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $updateIcon;
        return '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $updateIcon;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare la liste des références (signataires).
     *
     * Si l'entrée est un texte, retourne le texte (à afficher).
     *
     * @param array $list
     * @return string
     */
    private function _getObjectRefsHTML(array $list): string
    {
        // FIXME add $this->_displayLink2Refs support.
        $result = '';

        if (sizeof($list) == 0)
            return '';

        $size = sizeof($list);
        $count = 0;

        foreach ($list as $object) {
            $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
            $htlink = $this->_displayInstance->prepareDefaultObjectOrGroupOrEntityHtlink($object);
            $color = $this->_displayInstance->prepareObjectColor($object);
            $icon = '';
            if ($size < 11)
                $icon = $this->_displayInstance->prepareObjectFace($object);
            $name = '';
            if ($size < 3)
                $name = $this->_displayInstance->truncateName($object->getFullName('all'));
            $result .= $this->_displayInstance->convertHypertextLink($color . $icon . $name, $htlink);
            if ($size < 11)
                $result .= ' ';

            $count++;
            if ($count > 30) {
                $result .= '+';
                break;
            }
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare les icônes des indicateurs (flags).
     *
     * @param boolean $on
     * @param string  $image
     * @param string  $descOff
     * @param string  $descOn
     * @return string
     */
    private function _getObjectFlagHTML(bool $on, string $image, string $descOff, string $descOn): string
    {
        $result = '';

        $image = $this->_displayInstance->prepareIcon($image);
        if ($on)
            $desc = $this->_traductionInstance->getTraduction($descOn);
        else
            $desc = $this->_traductionInstance->getTraduction($descOff);
        $result .= '<img title="' . $desc . '" ';
        if ($on)
            $result .= 'class="objectFlagOn" ';
        $result .= 'alt="[C]" src="' . $image . '" />';

        return $result;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare les icônes des émotions avec ou sans les compteurs ($counts).
     *
     * @param Node    $object
     * @param boolean $counts
     * @return string
     */
    private function _getObjectFlagEmotionsHTML(Node $object, bool $counts = false): string
    {
        // Vérifie si les émotions doivent être affichées.
        if (!$this->_configurationInstance->getOptionUntyped('displayEmotions'))
            return '';
        $result = '';

        $listEmotions = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
        );
        $listEmotions0 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET0,
        );
        $listEmotions1 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
        );

        foreach ($listEmotions as $emotion) {
            // Génère la base du lien html pour revenir au bon endroit en toute situation.
            $httpLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntityID()
                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                . '&' . References::COMMAND_SELECT_CONVERSATION . '=' . $this->_nebuleInstance->getCurrentConversation();

            // Préparation du lien.
            $source = $object->getID();
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($emotion);
            $meta = $this->_nebuleInstance->getCurrentEntity();

            // Détermine si l'émotion a été marqué par l'entité en cours.
            if ($object->getMarkEmotion($emotion, 'myself')) {
                $action = 'x';
                $rid = $this->_nebuleInstance->newObject($listEmotions1[$emotion]);
            } else {
                $action = 'f';
                $rid = $this->_nebuleInstance->newObject($listEmotions0[$emotion]);
            }
            $link = $action . '_' . $source . '_' . $target . '_' . $meta;
            $httpLink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            $icon = $this->_displayInstance->convertReferenceImage($rid, $emotion, 'iconInlineDisplay');

            // Si connecté, l'icône est active.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            )
                $result .= $this->_displayInstance->convertHypertextLink($icon, $httpLink);
            else
                $result .= $icon;

            // Détermine le nombre d'entités qui ont marqué cette émotion.
            if ($counts) {
                $count = $object->getMarkEmotionSize($emotion, 'all');
                if ($count > 0)
                    $result .= $count . ' ';
            }
        }

        return $result;
    }

    private function _solveConflicts():void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
            $this->_displayFlagProtection = false;
        
        if (!$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            $this->_displayFlagObfuscate = false;
        
        if (!$this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = false;
        
        if ($this->_sizeCSS == 'Tiny') {
            $this->_displayLink2Refs = false;
            $this->_displayObjectActions = false;
            $this->_displayRefs = false;
            $this->_displayFlags = false;
            $this->_displayStatus = false;
            $this->_displayContent = false;
        }

        if ($this->_sizeCSS == 'Small') {
            $this->_displayFlags = false;
            $this->_displayStatus = false;
        }

        if ($this->_displayContent
            && $this->_ratioCSS == 'Square'
        ) {
            $this->_ratioCSS = 'Short';
        }

        if ($this->_displayIconApp) {
            $this->_displayColor = false;
            $this->_icon = null;
        }

        if (!$this->_displayColor
            && $this->_icon === null
            && !$this->_displayIconApp
        )
            $this->_displayObjectActions = false;

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

    public function setNID(?Node $nid): void
    {
        if ($nid === null)
            $this->_nid = null;
        elseif ($nid->getID() != '0' && is_a($nid, 'Nebule\Library\Node') && $nid->checkPresent())
            $this->_nid = $nid;
    }

    public function setEnableColor(bool $enable): void
    {
        $this->_displayColor = $enable;
    }

    public function setEnableIconApp(bool $enable): void
    {
        $this->_displayIconApp = $enable;
    }

    public function setEnableRefs(bool $enable): void
    {
        $this->_displayRefs = $enable;
    }

    public function setEnableName(bool $enable): void
    {
        $this->_displayName = $enable;
    }

    public function setEnableNID(bool $enable): void
    {
        $this->_displayNID = $enable;
    }

    public function setEnableFlags(bool $enable): void
    {
        $this->_displayFlags = $enable;
    }

    public function setEnableFlagEmotions(bool $enable): void
    {
        $this->_displayFlagEmotions = $enable;
    }

    public function setEnableFlagProtection(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
            $this->_displayFlagProtection = $enable;
    }

    public function setEnableFlagObfuscate(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
            $this->_displayFlagObfuscate = $enable;
    }

    public function setEnableFlagUnlocked(bool $enable): void
    {
        $this->_displayFlagUnlocked = $enable;
    }

    public function setEnableFlagActivated(bool $enable): void
    {
        $this->_displayFlagActivated = $enable;
    }

    public function setEnableFlagState(bool $enable): void
    {
        $this->_displayFlagState = $enable;
    }

    public function setEnableStatus(bool $enable): void
    {
        $this->_displayStatus = $enable;
    }

    public function setEnableContent(bool $enable): void
    {
        $this->_displayContent = $enable;
    }

    public function setEnableObjectActions(bool $enable): void
    {
        $this->_displayObjectActions = $enable;
    }

    public function setEnableLink2Object(bool $enable): void
    {
        $this->_displayLink2Object = $enable;
    }

    public function setEnableLink2Refs(bool $enable): void
    {
        $this->_displayLink2Refs = $enable;
    }

    public function setEnableJS(bool $enable): void
    {
        if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $this->_displayJS = $enable;
    }

    public function setEnableSelfHook(bool $enable): void
    {
        $this->_displaySelfHook = $enable;
    }

    public function setEnableTypeHook(bool $enable): void
    {
        $this->_displayTypeHook = $enable;
    }

    public function setSocial(string $social): void
    {
        if ($social == '')
        {
            $this->_social = 'all';
            return;
        }
        $socialList = $this->_nebuleInstance->getSocialInstance()->getSocialNames();
        foreach ($socialList as $s) {
            if ($social == $s) {
                $this->_social = $social;
                break;
            }
        }
        if ($this->_social == '')
            $this->_social = 'all';
    }
    
    public function setType(string $type): void
    {
        if ($type == '')
            $this->_type = $this->_nid->getType($this->_social);
        else
            $this->_type = $type;
    }
    
    public function setName(string $name): void
    {
        if ($name != '')
            $this->_name = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else {
            if ($this->_displayIconApp)
                $this->_name = $this->_nid->getName($this->_social);
            else
                $this->_name = $this->_nid->getFullName($this->_social);
        }
    }

    public function setAppShortName(string $name): void
    {
        if ($name != '')
            $this->_appShortname = trim(filter_var($name, FILTER_SANITIZE_STRING));
        else {
            if ($this->_displayIconApp)
                $this->_appShortname = $this->_nid->getSurname($this->_social);
            else
                $this->_appShortname = '';
        }
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutObject {
                margin: 5px 0 0 5px;
                border: 0;
                background: none;
                display: inline-block;
                vertical-align: top;
            }

            .objectDisplayTiny {
                font-size: 16px;
            }

            .objectDisplaySmall {
                font-size: 32px;
            }

            .objectDisplayMedium {
                font-size: 64px;
            }

            .objectDisplayLarge {
                font-size: 128px;
            }

            .objectDisplayFull {
                font-size: 256px;
            }

            .objectTitle a:link, .objectTitle a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectTitle a:hover, .objectTitle a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectTitleTiny {
                height: 16px;
                font-size: 16px;
                border: 0;
            }

            .objectTitleSmall {
                height: 32px;
                font-size: 32px;
                border: 0;
            }

            .objectTitleMedium {
                height: 64px;
                font-size: 64px;
                border: 0;
            }

            .objectTitleLarge {
                height: 128px;
                font-size: 128px;
                border: 0;
            }

            .objectTitleFull {
                height: 256px;
                font-size: 256px;
                border: 0;
            }

            .objectTitleText {
                background: rgba(255, 255, 255, 0.5);
            }

            .objectTitleTinyText {
                height: 16px;
                background: none;
            }

            .objectTitleSmallText {
                height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .objectTitleMediumText {
                height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleLargeText {
                height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleFullText {
                height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .objectTitleTinyRefs {
                visibility: hidden;
            }

            .objectTitleTinyRefs img {
                visibility: hidden;
            }

            .objectTitleSmallRefs {
                height: 12px;
                line-height: 12px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 9px;
            }

            .objectTitleSmallRefs img {
                height: 12px;
                width: 12px;
            }

            .objectTitleMediumRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleMediumRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleLargeRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleLargeRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleFullRefs {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleFullRefs img {
                height: 32px;
                width: 32px;
            }

            .objectTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .objectTitleSmallName {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 14px;
            }

            .objectTitleMediumName {
                height: 24px;
                line-height: 24px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleLargeName {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 28px;
            }

            .objectTitleFullName {
                height: 64px;
                line-height: 64px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 40px;
            }

            .objectTitleID {
                height: 16px;
                font-size: 10px;
                overflow: hidden;
            }

            .objectTitleTinyFlags {
                visibility: hidden;
            }

            .objectTitleTinyFlags img {
                visibility: hidden;
            }

            .objectTitleSmallFlags {
                visibility: hidden;
            }

            .objectTitleSmallFlags img {
                visibility: hidden;
            }

            .objectTitleMediumFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleMediumFlags img {
                height: 16px;
                width: 16px;
                margin: 0 1px 0 0;
                float: left;
            }

            .objectTitleLargeFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleLargeFlags img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectTitleFullFlags {
                height: 32px;
                font-size: 32px;
            }

            .objectTitleFullFlags img {
                height: 32px;
                width: 32px;
                margin: 0 4px 0 0;
                float: left;
            }

            .objectTitleIcons img {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsInline img {
                height: 1em;
                width: 1em;
            }

            .objectTitleIconsApp {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsApp div {
                overflow: hidden;
                font-size: 12px;
                text-align: left;
                font-weight: normal;
                margin: 3px;
                color: #ffffff;
            }

            .objectTitleIconsAppShortname {
                font-size: 18px;
            }

            .objectTitleIconsAppTitle {
                font-size: 11px;
            }

            .objectTitleText0 {
                margin-left: 0;
            }

            .objectTitleText1 {
                margin-left: 1em;
            }

            .objectTitleText2 {
                margin-left: 2em;
            }

            .objectTitleStatus {
                height: 1em;
                line-height: 1em;
                overflow: hidden;
                white-space: nowrap;
                font-weight: bold;
                text-align: right;
                padding-right: 2px;
            }

            .objectDisplayTinyShort {
            }

            .objectDisplaySmallShort {
                width: 8em;
            }

            .objectDisplayMediumShort {
                width: 6em;
            }

            .objectDisplayLargeShort {
                width: 5em;
            }

            .objectDisplayFullShort {
                width: 4em;
            }

            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 310px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 470px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 590px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 758px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1014px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1190px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1590px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1910px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2038px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2390px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3830px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 4086px;
                }
            }

            .objectContent {
                font-size: 0.8rem;
                border: 0;
                padding: 3px;
                margin: 0;
                color: #000000;
                overflow: auto;
            }

            .objectContentShort {
                width: 378px;
                max-height: 378px;
            }

            .objectContentText {
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }

            .objectFlagOn {
                background: #00ff20;
            }

            .objectTitleMenuContentLayout {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.33);
                z-index: 100;
                display: none;
                font-size: 0;
            }

            .objectTitleMenuContent {
                position: fixed;
                top: 10%;
                left: 10%;
                width: 80%;
                background: rgba(240, 240, 240, 0.5);
                padding: 16px;
            }

            .objectTitleMenuContent .objectTitleTinyLong, .objectTitleMenuContent .objectTitleSmallLong, .objectTitleMenuContent .objectTitleMediumLong, .objectTitleMenuContent .objectTitleLargeLong, .objectTitleMenuContent .objectTitleFullLong {
                width: 100%;
            }

            .objectTitleMenuContent .objectTitleText {
                background: rgba(255, 255, 255, 0.66);
            }

            .objectTitleMenuContentIcons {
                height: 64px;
                width: 128px;
            }

            .objectTitleMenuContentIcons img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContent {
                background: rgba(255, 255, 255, 0.2);
                padding-top: 4px;
            }

            .objectMenuContentMsg {
                background-origin: border-box;
                font-size: 14px;
                text-align: left;
                margin-top: 1px;
                width: 100%;
                overflow: hidden;
                white-space: normal;
                min-height: 16px;
            }

            .objectMenuContentMsg img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectMenuContentMsgOK {
                background: #103020;
                color: #ffffff;
            }

            .objectMenuContentMsgWarn {
                background: #ffe080;
                color: #ff8000;
            }

            .objectMenuContentMsgError {
                background: #ffa0a0;
                color: #ff0000;
                font-family: monospace;
            }

            .objectMenuContentMsgInfo {
                background: rgba(0, 0, 0, 0.4);
                color: #ffffff;
            }

            .objectMenuContentMsgID {
                background: rgba(255, 255, 255, 0.4);
                color: #000000;
                font-family: monospace;
                font-size: 9px;
                overflow: hidden;
                white-space: nowrap;
                min-height: 4px;
            }

            .objectMenuContentMsgEmotions {
                background: rgba(255, 255, 255, 0.1);
                color: #000000;
                text-align: center;
                min-height: 24px;
            }

            .objectMenuContentMsgEmotions img {
                height: 24px;
                width: 24px;
                margin: 0 1px 0 3px;
                float: none;
            }

            .objectMenuContentMsgtargetObject {
                background: rgba(0, 0, 0, 0.4);
                font-size: 12px;
                color: #ffffff;
                white-space: nowrap;
            }

            .objectMenuContentMsgtargetObject img {
                height: 16px;
                width: 16px;
                margin: 0;
                float: none;
            }

            .objectMenuContentMsgtargetObject a:link, .objectMenuContentMsgtargetObject a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentMsgtargetObject a:hover, .objectMenuContentMsgtargetObject a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentActions {
                margin: 1px 0 0 0;
                min-height: 32px;
                padding-top: 5px;
                background: rgba(255, 255, 255, 0.2);
            }

            .objectMenuContentActionsTinyShort {
            }

            .objectMenuContentActionsSmallShort {
                width: 256px;
            }

            .objectMenuContentActionsMediumShort {
                width: 384px;
            }

            .objectMenuContentActionsLargeShort {
                width: 640px;
            }

            .objectMenuContentActionsFullShort {
                width: 1024px;
            }

            .objectMenuContentActionsTinyLong, .objectMenuContentActionsSmallLong, .objectMenuContentActionsMediumLong, .objectMenuContentActionsLargeLong, .objectMenuContentActionsFullLong {
                width: 100%;
            }

            .objectMenuContentAction {
                height: 64px;
                display: inline-block;
                margin-top: 5px;
                margin-left: 5px;
                text-align: left;
            }

            /* Correction à vérifier */
            .objectMenuContentActionNoJS {
                height: 32px;
                display: inline-block;
                margin-bottom: 1px;
                text-align: left;
            }

            .objectMenuContentActionTinyShort {
            }

            .objectMenuContentActionSmallShort {
                width: 256px;
            }

            .objectMenuContentActionMediumShort {
                width: 384px;
            }

            .objectMenuContentActionLargeShort {
                width: 210px;
                margin-right: 5px;
            }

            .objectMenuContentActionFullShort {
                width: 251px;
                margin-right: 5px;
            }

            .objectMenuContentActionTinyLong, .objectMenuContentActionSmallLong, .objectMenuContentActionMediumLong, .objectMenuContentActionLargeLong, .objectMenuContentActionFullLong {
                width: 251px;
            }

            .objectMenuContentActionSelf {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .objectMenuContentActionSelf a:link, .objectMenuContentActionSelf a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectMenuContentActionSelf a:hover, .objectMenuContentActionSelf a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectMenuContentActionType {
                background: rgba(0, 0, 0, 0.66);
                color: #ffffff;
            }

            .objectMenuContentActionType a:link, .objectMenuContentActionType a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentActionType a:hover, .objectMenuContentActionType a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentAction-icon, .objectMenuContentAction-iconNoJS {
                float: left;
                margin-right: 5px;
            }

            .objectMenuContentAction-icon img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContentAction-iconNoJS img {
                height: 32px;
                width: 32px;
            }

            .objectMenuContentAction-modname p {
                font-size: 0.7rem;
                font-style: italic;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-title p {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-text p {
                font-size: 0.8rem;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-close {
                height: 1px;
                clear: both;
            }
        </style>
        <?php
    }
}
