<?php
declare(strict_types=1);
namespace Nebule\Application\Modules;
use Nebule\Application\Sylabe\Action;
use Nebule\Application\Sylabe\Display;
use Nebule\Library\Actions;
use Nebule\Library\ActionsEntities;
use Nebule\Library\ActionsGroups;
use Nebule\Library\ActionsLinks;
use Nebule\Library\Cache;
use Nebule\Library\DisplayInformation;
use Nebule\Library\DisplayItem;
use Nebule\Library\DisplayItemIconMessage;
use Nebule\Library\DisplayList;
use Nebule\Library\DisplayNotify;
use Nebule\Library\DisplayObject;
use Nebule\Library\DisplayQuery;
use Nebule\Library\Displays;
use Nebule\Library\DisplayTitle;
use Nebule\Library\Group;
use Nebule\Library\Metrology;
use Nebule\Library\Modules;
use Nebule\Library\nebule;
use Nebule\Library\Node;
use Nebule\Library\References;

/**
 * This module can manage groups of everything.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroups extends \Nebule\Library\Modules {
    const MODULE_TYPE = 'Application';
    const MODULE_NAME = '::ModuleName';
    const MODULE_MENU_NAME = '::MenuName';
    const MODULE_COMMAND_NAME = 'grp';
    const MODULE_DEFAULT_VIEW = 'disp';
    const MODULE_DESCRIPTION = '::ModuleDescription';
    const MODULE_VERSION = '020251223';
    const MODULE_AUTHOR = 'Projet nebule';
    const MODULE_LICENCE = 'GNU GLP v3 2013-2025';
    const MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const MODULE_HELP = '::ModuleHelp';
    const MODULE_INTERFACE = '3.0';

    const MODULE_REGISTERED_VIEWS = array('list', 'listall', 'setgroup', 'unsetgroup', 'addmarked', 'disp');
    const MODULE_REGISTERED_ICONS = array(
        '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256',    // 0 : Icône des groupes.
        '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',    // 1 : Créer un groupe.
        'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3.sha2.256',    // 2 : Ajouter objets marqués.
    );
    const MODULE_APP_TITLE_LIST = array('::AppTitle1');
    const MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256');
    const MODULE_APP_DESC_LIST = array('::AppDesc1');
    const MODULE_APP_VIEW_LIST = array('list');

    const RESTRICTED_TYPE = '';

    protected string $_displayGroup = '';
    protected ?Group $_displayGroupInstance = null;
    protected string $_hashGroup;
    protected string $_hashGroupClosed;
    protected Node $_hashGroupObject;
    protected Node $_hashGroupClosedObject;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    protected function _initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_displayInstance = $this->_applicationInstance->getDisplayInstance();
        $this->_translateInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_unlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();
        $this->_hashGroup = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_hashGroupObject = $this->_cacheInstance->newNode($this->_hashGroup);
        $this->_hashGroupClosed = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        $this->_hashGroupClosedObject = $this->_cacheInstance->newNode($this->_hashGroupClosed);
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?\Nebule\Library\Node $nid = null):array {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();

        switch ($hookName) {
            /*case 'menu':
                $hookArray[0]['name'] = '::MyGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
            case 'selfMenu':
            case 'typeMenuGroup':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[0]) {
                    $hookArray[] = array(
                        'name' => '::MyGroups',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this::MODULE_REGISTERED_VIEWS[1]) {
                    $hookArray[] = array(
                        'name' => '::Groups',
                        'icon' => $this::MODULE_LOGO,
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[1]
                                . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                    );
                }
                if ($this->_unlocked) {
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[0]) {
                        $hookArray[] = array(
                            'name' => '::createGroup',
                            'icon' => $this::MODULE_REGISTERED_ICONS[1],
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[2]
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID(),
                        );
                    }
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this::MODULE_REGISTERED_VIEWS[5]) {
                        $hookArray[] = array(
                            'name' => '::unmakeGroup',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                    . '&' . \Nebule\Library\ActionsGroups::DELETE
                                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand(),
                        );
                    }
                }
                break;

            case 'selfMenuGroup':
                // Refuser l'objet comme un groupe.
                $hookArray[1]['name'] = '::unmakeGroup';
                $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                    . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                break;

            case 'selfMenuObject':
                // Affiche si l'objet courant est un groupe.
                if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Refuser l'objet comme un groupe.
                        $hookArray[1]['name'] = '::unmakeGroup';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si c'est un groupe pour une autre entité.
                elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Voir comme groupe.
                    $hookArray[0]['name'] = '::seeAsGroup';
                    $hookArray[0]['icon'] = $this::MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[5]
                        . '&' . References::COMMAND_SELECT_GROUP . '=' . $object
                        . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();

                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe pour moi aussi.
                        $hookArray[1]['name'] = '::makeGroupMe';
                        $hookArray[1]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

                        // Refuser l'objet comme un groupe.
                        $hookArray[2]['name'] = '::refuseGroup';
                        $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LX;
                        $hookArray[2]['desc'] = '';
                        $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                            . '&' . ActionsLinks::SIGN1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                } // Ou si ce n'est pas un groupe.
                else {
                    if ($this->_unlocked) {
                        // Faire de l'objet un groupe.
                        $hookArray[0]['name'] = '::makeGroup';
                        $hookArray[0]['icon'] = $this::MODULE_REGISTERED_ICONS[1];
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                            . '&' . ActionsLinks::SIGN1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                            . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                            . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                    }
                }
                break;

            case 'selfMenuEntity':
                $hookArray[0]['name'] = '::MyGroups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;

            /*case 'typeMenuEntity':
                $hookArray[0]['name'] = '::Groups';
                $hookArray[0]['icon'] = $this::MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID();
                break;*/
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    public function displayModule(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_displayMyGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_displayAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[3]:
                $this->_displayRemoveGroup();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_displayAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGroup();
                break;
            default:
                $this->_displayMyGroups();
                break;
        }
    }


    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    public function displayModuleInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this::MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineMyGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineAllGroups();
                break;
            case $this::MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineAddMarkedObjects();
                break;
            case $this::MODULE_REGISTERED_VIEWS[5]:
                $this->_display_InlineGroup();
                break;
        }
    }



    protected function _displayMyGroups(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
            $this->_displayGroupCreateNew();
        }
        $this->_displaySimpleTitle('::MyGroups', $this::MODULE_REGISTERED_ICONS[0]);
        $this->_displayInstance->registerInlineContentID('my_groups');
    }

    protected function _display_InlineMyGroups(): void {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID($this->_entitiesInstance->getGhostEntityInstance(), '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_cacheInstance->newNode($group, \Nebule\Library\Cache::TYPE_GROUP);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

        unset($list, $links, $listOkGroups);
    }


    /**
     * Affiche les groupes de toutes les entités.
     *
     * @return void
     */
    protected function _displayAllGroups(): void
    {
        $icon = $this->_cacheInstance->newNode($this::MODULE_LOGO);
        $instance = new DisplayTitle($this->_applicationInstance);
        $instance->setTitle('::otherGroups');
        $instance->setIcon($icon);
        $instance->display();

        // Affiche le contenu.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('allgroups');
    }

    /**
     * Affiche les groupes de toutes les entités, en ligne.
     *
     * @todo filtrer sur tout sauf l'entité en cours.
     *
     * @return void
     */
    protected function _display_InlineAllGroups(): void
    {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID('', '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_cacheInstance->newNode($group, \Nebule\Library\Cache::TYPE_GROUP);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])
                && !isset($signers[$this->_entitiesInstance->getGhostEntityEID()])
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
            unset($signers);
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_displayInstance->getDisplayObjectsList_DEPRECATED($list, 'medium');

        unset($list, $links, $listOkGroups);


        /*		// Liste tous les groupes.
		$links = $this->_nebuleInstance->getListGroupsLinks('', '');
		if ( sizeof($links) != 0 )
		{
			$list = array();
			$i=0;
			foreach ( $links as $i => $link )
			{
				if ( $link->getParsed()['bs/rs1/eid'] != $this->_nebuleInstance->getCurrentEntity() )
				{
					$instance = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);   erreur de fonction !!!
					$instanceEntity = $this->_nebuleInstance->newNode($link->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
					$closed = '::GroupeOuvert';
					if ( $instance->getMarkClosed() )
						$closed = '::GroupeFerme';
					$list[$i]['object'] = $instance;
					$list[$i]['entity'] = $instanceEntity;
					$list[$i]['icon'] = '';
					$list[$i]['htlink'] = '';
					$list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
					$list[$i]['actions'] = array();
					if ( $this->_unlocked )
					{
						// Supprimer le groupe.
						$list[$i]['actions'][0]['name'] = '::unmakeGroup';
						$list[$i]['actions'][0]['icon'] = Display::DEFAULT_ICON_LX;
						$list[$i]['actions'][0]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[3]
							.'&'.References::COMMAND_SELECT_OBJECT.'='.$link->getParsed()['bl/rl/nid1'];
						// Utiliser comme groupe ouvert.
						$list[$i]['actions'][1]['name'] = '::useAsGroupOpened';
						$list[$i]['actions'][1]['icon'] = Display::DEFAULT_ICON_LL;
						$list[$i]['actions'][1]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[1]
							.'&'.Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroup.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
						// Utiliser comme groupe fermé.
						$list[$i]['actions'][2]['name'] = '::useAsGroupClosed';
						$list[$i]['actions'][2]['icon'] = Display::DEFAULT_ICON_LL;
						$list[$i]['actions'][2]['htlink'] = '?'.Displays::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this::MODULE_COMMAND_NAME
							.'&'.Displays::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this::MODULE_REGISTERED_VIEWS[1]
							.'&'.Action::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroupClosed.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
					}
					$i++;
				}
			}
			unset($link, $type, $instance, $instanceEntity, $closed);
			if ( sizeof($list) != 0 )
				$this->_applicationInstance->getDisplayInstance()->displayItemList($list);
			else
				// Pas de groupe.
				$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::noGroup');
			unset($list);
		}
		else
		{
			// Pas de groupe.
			$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::noGroup');
		}
		unset($links, $okobj, $count);*/
    }



    protected function _displayCreateGroup(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_displaySimpleTitle('::createGroup', $this::MODULE_REGISTERED_ICONS[1]);
        $this->_displayGroupCreateForm();
        // MyGroups() view displays the result of the creation
    }

    protected function _displayGroupCreateNew(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreate()) {
            $instanceList = new DisplayList($this->_applicationInstance);
            $instance = new DisplayInformation($this->_applicationInstance);
            $instance->setRatio(DisplayItem::RATIO_SHORT);
            if (!$this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateError()) {
$this->_metrologyInstance->addLog('DEBUGGING MARK 1', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                $_displayGroupInstance = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateInstance();
$this->_metrologyInstance->addLog('DEBUGGING MARK 2 nid=' . $_displayGroupInstance->getID(), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
$this->_metrologyInstance->addLog('DEBUGGING MARK 2 type=' . gettype($_displayGroupInstance), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                //$this->_displayGroupInstance = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateInstance();
                //$this->_displayGroupInstance = $_displayGroupInstance; FIXME
$this->_metrologyInstance->addLog('DEBUGGING MARK 3', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
                $this->_displayGroup = $this->_applicationInstance->getActionInstance()->getInstanceActionsGroups()->getCreateGID();

                $instance->setMessage('::OKCreateGroup');
                $instance->setType(DisplayItemIconMessage::TYPE_OK);
                $instance->setIconText('::::OK');
                $instanceList->addItem($instance);

                $instance = new DisplayObject($this->_applicationInstance);
                $instance->setSocial('self');
                //$instance->setNID($this->_displayGroupInstance); FIXME
                $instance->setNID($_displayGroupInstance);
                $instance->setEnableColor(true);
                $instance->setEnableIcon(true);
                $instance->setEnableName(true);
                $instance->setEnableRefs(false);
                $instance->setEnableNID(false);
                $instance->setEnableFlags(true);
                $instance->setEnableFlagProtection(false);
                $instance->setEnableFlagObfuscate(false);
                $instance->setEnableFlagState(true);
                $instance->setEnableFlagEmotions(false);
                $instance->setEnableStatus(false);
                $instance->setEnableContent(false);
                $instance->setEnableJS(false);
                $instance->setEnableLink(true);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setStatus('');
                $instance->setEnableFlagUnlocked(false);
                $instanceIcon = $this->_cacheInstance->newNode(References::REF_IMG['grpobj']); // FIXME
                $instanceIcon2 = $this->_displayInstance->getImageByReference($instanceIcon);
                $instance->setIcon($instanceIcon2);
            } else {
                $instance = new DisplayInformation($this->_applicationInstance);
                $instance->setMessage('::notOKCreateGroup');
                $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
                $instance->setRatio(DisplayItem::RATIO_SHORT);
                $instance->setIconText('::::ERROR');
            }
            $instanceList->addItem($instance);
            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        }
        echo '<br />' . "\n";
    }

    protected function _displayGroupCreateForm(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkBooleanOptions(array('unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteGroup'))) {
            $commonLink = '?' . References::COMMAND_SWITCH_APPLICATION . '=' . $this->_routerInstance->getApplicationIID()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_REGISTERED_VIEWS[0]
                    . '&' . ActionsGroups::CREATE
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_entitiesInstance->getGhostEntityEID()
                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();

            $instanceList = new DisplayList($this->_applicationInstance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_STRING);
            $instance->setInputValue('');
            $instance->setInputName(ActionsGroups::CREATE_NAME);
            $instance->setIconText(References::REFERENCE_NEBULE_OBJET_NOM);
            $instance->setWithFormOpen(true);
            $instance->setWithFormClose(false);
            $instance->setLink($commonLink);
            $instance->setWithSubmit(false);
            $instance->setIconRID(DisplayItemIconMessage::ICON_WARN_RID);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_SELECT);
            $instance->setInputName(ActionsGroups::CREATE_CLOSED);
            $instance->setIconText('::createGroupClosed');
            $instance->setSelectList(array(
                    'y' => $this->_translateInstance->getTranslate('::::yes'),
                    'n' => $this->_translateInstance->getTranslate('::::no'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_SELECT);
            $instance->setInputName(ActionsGroups::CREATE_OBFUSCATED);
            $instance->setIconText('::createGroupObfuscated');
            $instance->setSelectList(array(
                    'n' => $this->_translateInstance->getTranslate('::::no'),
                    'y' => $this->_translateInstance->getTranslate('::::yes'),
            ));
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(false);
            $instance->setWithSubmit(false);
            $instanceList->addItem($instance);

            $instance = new DisplayQuery($this->_applicationInstance);
            $instance->setType(DisplayQuery::QUERY_TEXT);
            $instance->setMessage('::createTheGroup');
            $instance->setInputValue('');
            $instance->setInputName($this->_translateInstance->getTranslate('::createTheGroup'));
            $instance->setIconText('::confirm');
            $instance->setWithFormOpen(false);
            $instance->setWithFormClose(true);
            $instance->setWithSubmit(true);
            //$instance->setLink($commonLink);
            $instanceList->addItem($instance);

            $instanceList->setSize(DisplayItem::SIZE_MEDIUM);
            $instanceList->setOnePerLine();
            $instanceList->display();
        } else {
            $instance = new DisplayNotify($this->_applicationInstance);
            $instance->setMessage('::::err_NotPermit');
            $instance->setType(DisplayItemIconMessage::TYPE_ERROR);
            $instance->display();
        }
    }

    protected function _displayRemoveGroup(): void
    {
        // Affichage de l'entête.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance());

        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            // Affichage les actions possibles.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:group:remove');
        } else {
            // Ce n'est pas un groupe.
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::thisIsNotGroup');
        }
    }


    /**
     * Permet d'ajouter des objets marqués à un groupe.
     *
     * @return void
     */
    protected function _displayAddMarkedObjects(): void
    {
    }

    /**
     * Affichage en ligne de l'ajout des objets à un groupe.
     *
     * @return void
     */
    protected function _display_InlineAddMarkedObjects(): void
    {
    }


    /**
     * Affiche le groupe.
     *
     * @return void
     */
    protected function _displayGroup(): void
    {
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        $param['objectRefs'] = $instance->getPropertySigners(References::REFERENCE_NEBULE_OBJET_GROUPE);
        echo $this->_displayInstance->getDisplayObject_DEPRECATED($instance, $param);
        unset($instance);

        // Affichage des membres du groupe.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('grpdisp');
    }

    /**
     * Affiche le groupe, en ligne.
     *
     * @return void
     */
    protected function _display_InlineGroup(): void
    {
        // Détermine si c'est un groupe fermé.
        if ($this->_nebuleInstance->getCurrentGroupInstance()->getMarkClosedGroup()) {
            // Liste tous les objets du groupe fermé.
            $groupListID = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersID('self', null);

            //Prépare l'affichage.
            if (sizeof($groupListID) != 0) {
                $list = array();
                $listOkItems = array($this->_hashGroup => true, $this->_hashGroupClosed => true);
                $i = 0;
                foreach ($groupListID as $item) {
                    if (!isset($listOkItems[$item])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item);

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item] = true;
                        $i++;
                    }
                }
                unset($groupListID, $listOkItems, $item, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);

                // Liste tous les objets du groupe ouvert.
                $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

                //Prépare l'affichage.
                if (sizeof($groupListLinks) != 0) {
                    $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                    $list = array();
                    $listOkItems = array();
                    $i = 0;
                    foreach ($groupListLinks as $item) {
                        // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                        // Vérifie si le signataire n'est pas l'entité en cours.
                        if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])
                            && $item->getParsed()['bs/rs1/eid'] != $this->_entitiesInstance->getGhostEntityEID()
                        ) {
                            $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                            $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                            $closed = '::GroupeOuvert';
                            if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                                $closed = '::GroupeFerme';

                            $list[$i]['object'] = $instance;
                            $list[$i]['entity'] = $instanceSigner;
                            $list[$i]['icon'] = '';
                            $list[$i]['htlink'] = '';
                            $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                            $list[$i]['actions'] = array();

                            // Supprimer le groupe.
                            if ($this->_unlocked
                                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                            ) {
                                $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                                $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                    . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                    . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                    . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                            }

                            // Marque comme vu.
                            $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                            $i++;
                        }
                    }
                    unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                    // Affichage
                    if (sizeof($list) != 0) {
                        echo "<div class=\"sequence\"></div>\n";
                        $iconNID = $this->_cacheInstance->newNode($this::MODULE_LOGO);
                        $instance = new \Nebule\Library\DisplayTitle($this->_applicationInstance);
                        $instance->setTitle('::seenFromOthers');
                        $instance->setIcon($iconNID);
                        $instance->display();
                        $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                    }
                }
                unset($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::noGroupMember');
            }
        } // Sinon c'est un groupe ouvert.
        else {
            // Liste tous les groupes.
            $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

            //Prépare l'affichage.
            if (sizeof($groupListLinks) != 0) {
                $hashGroupPriv = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                $list = array();
                $listOkItems = array();
                $i = 0;
                foreach ($groupListLinks as $item) {
                    // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                    if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])) {
                        $instance = $this->_applicationInstance->getTypedInstanceFromNID($item->getParsed()['bl/rl/nid1']);
                        $instanceSigner = $this->_cacheInstance->newNode($item->getParsed()['bs/rs1/eid'], \Nebule\Library\Cache::TYPE_ENTITY);
                        $closed = '::GroupeOuvert';
                        if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                            $closed = '::GroupeFerme';

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = $instanceSigner;
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = $this->_translateInstance->getTranslate($closed);
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this::MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this::MODULE_DEFAULT_VIEW
                                . '&' . References::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroupOID()
                                . '&' . ActionsGroups::REMOVE_MEMBER . '=' . $item->getParsed()['bl/rl/nid1']
                                . $this->_nebuleInstance->getTokenizeInstance()->getActionTokenCommand();
                        }

                        // Marque comme vu.
                        $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                        $i++;
                    }
                }
                unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::noGroupMember');
            }
        }
    }


    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => 'Module des groupes',
            '::MenuName' => 'Groupes',
            '::ModuleDescription' => 'Module de gestion des groupes.',
            '::ModuleHelp' => "Ce module permet de voir et de gérer les groupes.",
            '::AppTitle1' => 'Groupes',
            '::AppDesc1' => 'Gestion des groupes.',
            '::Groups' => 'Les groupes',
            '::MyGroups' => 'Mes groupes',
            '::seeAsGroup' => 'Voir comme groupe',
            '::seenFromOthers' => 'Vu depuis les autres entités',
            '::otherGroups' => 'Les groupes des autres entités',
            '::createGroup' => 'Créer un groupe',
            '::createGroupClosed' => 'Créer un groupe fermé',
            '::createGroupObfuscated' => 'Créer un groupe dissimulé',
            '::AddMarkedObjects' => 'Ajouter les objets marqués',
            '::deleteGroup' => 'Supprimer le groupe',
            '::createTheGroup' => 'Créer le groupe',
            '::nom' => 'Nom',
            '::OKCreateGroup' => 'Le groupe a été créé.',
            '::notOKCreateGroup' => "Le groupe n'a pas été créé ! %s",
            '::noGroup' => 'Pas de groupe.',
            '::noGroupMember' => 'Pas de membre.',
            '::makeGroup' => 'Faire de cet objet un groupe',
            '::makeGroupMe' => 'Faire de cet objet un groupe pour moi aussi',
            '::unmakeGroup' => 'Ne plus faire de cet objet un groupe',
            '::useAsGroupOpened' => 'Utiliser comme groupe ouvert',
            '::useAsGroupClosed' => 'Utiliser comme groupe fermé',
            '::refuseGroup' => 'Refuser cet objet comme un groupe',
            '::removeFromGroup' => 'Retirer du groupe',
            '::isGroup' => 'est un groupe.',
            '::isGroupToOther' => 'est un groupe de',
            '::isNotGroup' => "n'est pas un groupe.",
            '::thisIsGroup' => "C'est un groupe.",
            '::thisIsNotGroup' => "Ce n'est pas un groupe.",
            '::confirm' => 'Confirmation',
        ],
        'en-en' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::Groups' => 'The groups',
            '::MyGroups' => 'My groups',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createGroupClosed' => 'Create a closed group',
            '::createGroupObfuscated' => 'Create an obfuscated group',
            '::AddMarkedObjects' => 'Add marked objects',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::nom' => 'Name',
            '::OKCreateGroup' => 'The group have been created.',
            '::notOKCreateGroup' => 'The group have not been created! %s',
            '::noGroup' => 'No group.',
            '::noGroupMember' => 'No member.',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group.',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group.',
            '::thisIsGroup' => 'This is a group.',
            '::thisIsNotGroup' => 'This is not a group.',
            '::confirm' => 'Confirm',
        ],
        'es-co' => [
            '::ModuleName' => 'Groups module',
            '::MenuName' => 'Groups',
            '::ModuleDescription' => 'Groups management module.',
            '::ModuleHelp' => 'This module permit to see and manage groups.',
            '::AppTitle1' => 'Groups',
            '::AppDesc1' => 'Manage groups.',
            '::Groups' => 'The groups',
            '::MyGroups' => 'My groups',
            '::seeAsGroup' => 'See as group',
            '::seenFromOthers' => 'Seen from others entities',
            '::otherGroups' => 'Groups of other entities',
            '::createGroup' => 'Create a group',
            '::createGroupClosed' => 'Create a closed group',
            '::createGroupObfuscated' => 'Create an obfuscated group',
            '::AddMarkedObjects' => 'Add marked objects',
            '::deleteGroup' => 'Delete group',
            '::createTheGroup' => 'Create the group',
            '::nom' => 'Name',
            '::OKCreateGroup' => 'The group have been created.',
            '::notOKCreateGroup' => 'The group have not been created! %s',
            '::noGroup' => 'No group.',
            '::noGroupMember' => 'No member.',
            '::makeGroup' => 'Make this object a group',
            '::makeGroupMe' => 'Make this object a group for me too',
            '::unmakeGroup' => 'Unmake this object a group',
            '::useAsGroupOpened' => 'Use as group opened',
            '::useAsGroupClosed' => 'Use as group closed',
            '::refuseGroup' => 'Refuse this object as group',
            '::removeFromGroup' => 'Remove from group',
            '::isGroup' => 'is a group.',
            '::isGroupToOther' => 'is a group of',
            '::isNotGroup' => 'is not a group.',
            '::thisIsGroup' => 'This is a group.',
            '::thisIsNotGroup' => 'This is not a group.',
            '::confirm' => 'Confirm',
        ],
    ];
}


/**
 * This module can manage groups of entities.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroupEntities extends ModuleGroups {
    const MODULE_COMMAND_NAME = 'grpent';
    const MODULE_LOGO = '425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272';
    const MODULE_REGISTERED_ICONS = array(
            '425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272', // 0 : Icône des groupes.
            '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256',     // 1 : Créer un groupe.
            'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3.sha2.256',     // 2 : Ajouter objets marqués.
    );
    const MODULE_APP_ICON_LIST = array('425d033815bd76844fc5100ad0ccb2c3d6cd981315794b95210d6c6ec8da22e8faaf.none.272');
    const RESTRICTED_TYPE = 'Entity';
}