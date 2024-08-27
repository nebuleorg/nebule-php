<?php
declare(strict_types=1);
namespace Nebule\Application\Sylabe;
use Nebule\Library\applicationInterface;
use Nebule\Library\DisplayInformation;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\Node;
use Nebule\Library\Translates;
use const Nebule\Bootstrap\BOOTSTRAP_NAME;
use const Nebule\Bootstrap\BOOTSTRAP_SURNAME;
use const Nebule\Bootstrap\BOOTSTRAP_WEBSITE;
use const Nebule\Bootstrap\LIB_BOOTSTRAP_ICON;

/*
|------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------
|
|  [FR] Toute modification de ce code entraînera une modification de son empreinte
|       et entraînera donc automatiquement son invalidation !
|  [EN] Any modification of this code will result in a modification of its hash digest
|       and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
|       tanto lugar automáticamente a su anulación!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і,
|       відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------
*/



/**
 * Class Application for sylabe
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications implements applicationInterface
{
    const APPLICATION_NAME = 'sylabe';
    const APPLICATION_SURNAME = 'nebule/sylabe';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240815';
    const APPLICATION_LICENCE = 'GNU GPL 2013-2024';
    const APPLICATION_WEBSITE = 'www.sylabe.org';
    const APPLICATION_NODE = 'c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = true;
    const LIST_MODULES_INTERNAL = array(
        'module_manage',
        'module_admin',
        'module_objects',
        'module_groups',
        'module_entities',
        'module_lang_fr-fr',
    );
    const LIST_MODULES_EXTERNAL = array(
        'module_neblog'
    );

    const APPLICATION_ENVIRONMENT_FILE = 'nebule.env';
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP = true;
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS = false;
    const APPLICATION_DEFAULT_DISPLAY_METROLOGY = false;
    const APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL = true;
    const APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT = false;
    const APPLICATION_DEFAULT_DISPLAY_NAME_SIZE = 128;
    const APPLICATION_DEFAULT_IO_READ_MAX_DATA = 1000000;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOG_LOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOAD_MODULES = 'd6105350a2680281474df5438ddcb3979e5575afba6fda7f646886e78394a4fb.sha2.256';


    private $_listOptions = array(
        'sylabeDisplayOnlineHelp',
        'sylabeDisplayOnlineOptions',
        'sylabeDisplayMetrology',
//			'sylabeDisplayUnsecureURL',
        'sylabeDisplayUnverifyLargeContent',
        'sylabeDisplayNameSize',
        'sylabeIOReadMaxDataPHP',
        'sylabePermitUploadObject',
        'sylabePermitUploadLinks',
        'sylabePermitPublicUploadObject',
        'sylabePermitPublicUploadLinks',
//			'sylabeLogUnlockEntity',
//			'sylabeLogLockEntity',
//			'sylabeLoadModules',
    );
    private $_listOptionsType = array(
        'sylabeDisplayOnlineHelp' => 'b',        // Booléen
        'sylabeDisplayOnlineOptions' => 'b',        // Booléen
        'sylabeDisplayMetrology' => 'b',        // Booléen
//			'sylabeDisplayUnsecureURL'			=> 'b',		// Booléen
        'sylabeDisplayUnverifyLargeContent' => 'b',        // Booléen
        'sylabeDisplayNameSize' => 'i',        // Entier
        'sylabeIOReadMaxDataPHP' => 'i',        // Entier
        'sylabePermitUploadObject' => 'b',        // Booléen
        'sylabePermitUploadLinks' => 'b',        // Booléen
        'sylabePermitPublicUploadObject' => 'b',        // Booléen
        'sylabePermitPublicUploadLinks' => 'b',        // Booléen
//			'sylabeLogUnlockEntity'				=> 'b',		// Booléen
//			'sylabeLogLockEntity'				=> 'b',		// Booléen
//			'sylabeLoadModules'					=> 't',		// Texte
    );
    private $_listOptionsDefault = array(
        'sylabeDisplayOnlineHelp' => self::APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP,
        'sylabeDisplayOnlineOptions' => self::APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS,
        'sylabeDisplayMetrology' => self::APPLICATION_DEFAULT_DISPLAY_METROLOGY,
//			'sylabeDisplayUnsecureURL'			=> self::APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL,
        'sylabeDisplayUnverifyLargeContent' => self::APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT,
        'sylabeDisplayNameSize' => self::APPLICATION_DEFAULT_DISPLAY_NAME_SIZE,
        'sylabeIOReadMaxDataPHP' => self::APPLICATION_DEFAULT_IO_READ_MAX_DATA,
        'sylabePermitUploadObject' => self::APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT,
        'sylabePermitUploadLinks' => self::APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS,
        'sylabePermitPublicUploadObject' => self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT,
        'sylabePermitPublicUploadLinks' => self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS,
//			'sylabeLogUnlockEntity'				=> self::APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY,
//			'sylabeLogLockEntity'				=> self::APPLICATION_DEFAULT_LOG_LOCK_ENTITY,
//			'sylabeLoadModules'					=> self::APPLICATION_DEFAULT_LOAD_MODULES,
    );


    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    public function __construct(nebule $nebuleInstance)
    {
        parent::__construct($nebuleInstance);
    }

    public static function getOption($name)
    {
        if ($name == ''
            || !is_string($name)
        ) {
            return false;
        }

        // Va contenir le résultat à retourner.
        $r = '';
        $t = '';

        // Lit le fichier d'environnement.
        if (file_exists(self::APPLICATION_ENVIRONMENT_FILE)) {
            $f = file(self::APPLICATION_ENVIRONMENT_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            foreach ($f as $o) {
                $l = trim($o);

                // Si commentaire, passe à la ligne suivante.
                if ($l[0] == "#")
                    continue;

                // Recherche l'option demandée.
                if (filter_var(trim(strtok($l, '=')), FILTER_SANITIZE_STRING) == $name) {
                    $t = filter_var(trim(substr($l, strpos($l, '=') + 1)), FILTER_SANITIZE_STRING);
                    break;
                }
            }
            unset($f, $o, $l);
        }

        // Vérifie que c'est une option connue et qu'elle a une valeur.
        switch ($name) {
            case 'sylabeDisplayOnlineHelp' :
                if ($t == 'false') $r = false; else $r = self::APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP;
                break;
            case 'sylabeDisplayOnlineOptions' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS;
                break;
            case 'sylabeDisplayMetrology' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_DISPLAY_METROLOGY;
                break;
//			case 'sylabeDisplayUnsecureURL' :			if ( $t == 'false' )	$r = false;		else $r = self::APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL;			break;
            case 'sylabeDisplayUnverifyLargeContent' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT;
                break;
            case 'sylabeDisplayNameSize' :
                if ($t != '') $r = (int)$t; else $r = self::APPLICATION_DEFAULT_DISPLAY_NAME_SIZE;
                break;
            case 'sylabeIOReadMaxDataPHP' :
                if ($t != '') $r = (int)$t; else $r = self::APPLICATION_DEFAULT_IO_READ_MAX_DATA;
                break;
            case 'sylabePermitUploadObject' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT;
                break;
            case 'sylabePermitUploadLinks' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS;
                break;
            case 'sylabePermitPublicUploadObject' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT;
                break;
            case 'sylabePermitPublicUploadLinks' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS;
                break;
//			case 'sylabeLogUnlockEntity' :				if ( $t == 'true' )		$r = true;		else $r = self::APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY;				break;
//			case 'sylabeLogLockEntity' :				if ( $t == 'true' )		$r = true;		else $r = self::APPLICATION_DEFAULT_LOG_LOCK_ENTITY;				break;
//			case 'sylabeLoadModules' :					if ( $t != '' )			$r = $t;		else $r = self::APPLICATION_DEFAULT_LOAD_MODULES;					break;
            default :
                $r = false; // Si l'option est inconnue, retourne false.
        }
        unset($t);
        return $r;
    }
}


/**
 * Classe Display
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Display extends Displays
{
    const DEFAULT_DISPLAY_MODE = 'hlp';
    const DEFAULT_DISPLAY_VIEW = '1st';
    const DEFAULT_LINK_COMMAND = 'lnk';
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAGEklEQVR42u2bT0wTWRzH3+
t0ptM4k/5xmhBDIGKi/Ikm7QFLTSzKhQOzJsQ269YziReNKOx66nKCjSYNHmq8eGrNpuBhUze2UQQu5bIpB7JKNhGpyQaSVqh0up1O6bw9lbCu0ul0Zsou/R1bXnnvM9/3e7/f7/0GA
gB+BEfYdOCIWxNAE0ATQBPAkTa91v8QwzBIkqSOIAio0+kgAACIoogEQUA8z4vlchn97wC4XC6zw+GwdnR0mNrb2802m42mKMpoMBj0AABQLBZ3OY4rpNPpXCqVyq6trX1KJpNbiUQi
+58FQNM0Njk5aR8YGOhmGMZEURRFkiQhZSzP8wLHcVwmk8nOzc29vXfv3nIulyurMU+odCRoNBp1oVCon2XZPhzHFQFcKpV2o9Ho0vXr1xcKhYJ4KAG0tLQQ4+PjPTdu3BiU+qRrNZ7
nhWAwGLt///7vm5ubgiI+CQDQX++P+Hy+1idPnrAsyzr1ej2m2n7V67G+vr4zQ0NDrdvb25mVlZVcwwEEAgGH3++/cuLECZtWnpthGMvg4GAXwzB/xePxzYYAwDAMBgIBx82bN4cMBg
Ou9XFKEAR+/vz50xaLJffq1atNhJC2AAKBgOPWrVsshBA2KoiBEEKn03nGZDLtxGKxDc0AVJ58Ixe/33p7e0+bzeYdOduhZgA+n6/V7/dfaYTsD1LCuXPn2tbX19drdYw1HYMtLS3E/
Pz8t52dnSc//25mZmbR6/UuaLVohJD/889WV1ffX7p06edajsiaApXx8fGeLy0eAACGh4cvRiIRMDEx8ZuKi0Zv3rzJf+37zs7Ok2NjYz137txZVlwBRqNRt7W19f1BQQ5CCMzOzmqi
hC8poBIsWa3Wn6RGjJLT4VAo1F8twoMQAo/H445EIv2N8gckSRKhUMitaD2ApmmMZdk+iU9mDwKO4w05JViWddE0jSkGYGpqyiE1samcjFevXnWHw2H3/s+0MhzH9ZOTk3bFAFy+fLl
LxtG0pwS5UVo9NjAw0KUIgAsXLlgYhjHJdFQN8wkMw5hdLpe5bgB2u91CURQlM0ABAIA9CBiGabYXKIqiHA6Hte444NSpU2Yl8nuPx+MGAACv17sAIQRqbwuSJImOjg5TXQrAcRy2tb
WZlZqU1j6hvb3dXE11uiopp85ms1FKTUjrI9Jms9EkSerqAQApijIqmLT864hU2Q8YCYKQrwCdTgcrpWuFszdNTgeDwaCv3D0okgxVWdSEnHGRSKS/4iAbYQcqQBRFVCwWd9WcgNfrX
ZiZmVlUwzEWi8VdURSRbACCICCO4wpqPwWfz7c4Ozu7WHGUShnHcQVBEOoCIKbTaU5tAKVSCVWUoGTekE6nczzPi7IBlEol9OHDh6wWexFCuLcdlPrNVCqVrXbZWjUUfvfuXZbneUFt
ABXpX7t2bVEJCDzPC2tra5/qzgWSyeQWx3Ec0MjK5TLarwS5PoHjOC6ZTG7VDSCRSGQzmcwnoLHV6xMymUxWyvW6pHrA69ev32oNYL9PkKOCubk5SXOWVBSlaRr7+PHjDwdVheQGQhK
qOzAcDrs9Ho8bISSpulQqlXaPHz8+JaWnQJICcrlcORqNLjUiUpNzREaj0YTUhgrFyuJqKaDWsLnWsrjkXKBQKIiPHj2K3b59+5tqf9vT00OpAaBy6TI8PHzxa3l+MBiM1dJFotjVmB
YKqKYEOVdjNbfI+Hy+1sePH3937NgxIzhEls/nCyMjI+GnT5/+Wcu4mm+HV1ZWdmw2W6G3t/f0YbkeF0URBYPBFw8ePPij1rGy+gPi8fiG1WrNOZ3OM4cBwPT09PPR0dFlOWNld4i8f
Ply02Qy7TRSCaIoounp6ed3795d1rxFBiEEYrHYhsViyZ09e7aNIAhc6z0fDAZfjI6OLtdTQ6i7Sywej2+kUqlUd3e3hWEYixaLX11dfT82NvaLnD2vOICKY3z27NlbDMO27Xb7SbV6
BXmeFx4+fPjryMjI/NLSkiJ1imarLFDprbF9zdJdDMOYj0yz9JfsMLfLawLgH07nKL4w8XnJK5/Pl/P5/KEIoZsvTTUBNAE0ATQBHGX7G6N1Cds7Fc/AAAAAAElFTkSuQmC";
    const DEFAULT_APPLICATION_LOGO_LINK = '?mod=hlp&view=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690.sha2.256';
    const DEFAULT_LOGO_MODULE = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';
    //const DEFAULT_CSS_BACKGROUND			= '9fd9fd946bde32cc53e5d2f800f9f01a3957349ad50785b0521ed38408bd2c0e.sha2.256';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499.sha2.256';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0.sha2.256';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2.sha2.256';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859.sha2.256';

    const APPLICATION_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAA
sTAQCanBgAAAAHdElNRQfeDAYWDCX7YSGrAAABn0lEQVRIx62VPU8CQRCGn7toYWKCBRY0FNQe9CZ+dPwAt6AxkmgrJYX2JsaOVgtNTKQYfwCdYGKvUFOYGAooxJiYWHgWd5wL3C0Lc
aqbnZ1nd2923nUwmMqRBgbSTZ7jxCbuUKZIRhvq0eBamhYAVaLGesJyfSpSNwBUihZ5zPbCtgxjAWqDNn78oTTzcfCkMwVQG7SxtwjhRJt/Zz5LyQfAUui2ws1/cs+KIe2HTbKAzyOF
CKBK0a9b5U1OjXfjjizgkFclqYMLQE2bcaLOjVt3o69a6KrdibpXZyBGtq52At7BVMgWUQ4AxZiQHaIYADKxQRtEBlyVSwxbIFTOJW2IV9Xl1Nj3mJdemgjfyv7EGlccmqs6GPOXJyf
IEReG/IFrUpsQUU1GSNcFegsjekEZG7MLnoBoBIAbm0sXi7gGF+SB/kKIvjRHvVXR+t2MONPcY12RnvFwgFeetIadti/2WAV82lLQFWk7lLQsWas+dNgakwcZ4s2liF6giJq+SAcP8G
em+rom6wKFdFizkPY2qb/0/37a/uVxnfd5/wWNcHiC0uUMVAAAAABJRU5ErkJggg==';


    /**
     * Liste des objets nécessaires au bon fonctionnement.
     *
     * @var array
     */
    protected array $_neededObjectsList = array(
        self::DEFAULT_LOGO_MENUS,
        self::DEFAULT_ICON_ALPHA_COLOR,
        self::DEFAULT_ICON_LC,
        self::DEFAULT_ICON_LD,
        self::DEFAULT_ICON_LE,
        self::DEFAULT_ICON_LF,
        self::DEFAULT_ICON_LK,
        self::DEFAULT_ICON_LL,
        self::DEFAULT_ICON_LO,
        self::DEFAULT_ICON_LS,
        self::DEFAULT_ICON_LU,
        self::DEFAULT_ICON_LX,
        self::DEFAULT_ICON_IOK,
        self::DEFAULT_ICON_IWARN,
        self::DEFAULT_ICON_IERR,
        self::DEFAULT_ICON_IINFO,
        self::DEFAULT_ICON_IMLOG,
        self::DEFAULT_ICON_IMODIFY,
        self::DEFAULT_ICON_IDOWNLOAD,
        self::DEFAULT_ICON_HELP,
        self::DEFAULT_ICON_WORLD);

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load display', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'cf96279b');
        $this->_traductionInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityIsUnlocked();

        // Vide, est surchargé juste avant l'affichage.
        $this->setUrlLinkObjectPrefix('?');
        $this->setUrlLinkGroupPrefix('?');
        $this->setUrlLinkConversationPrefix('?');
        $this->setUrlLinkEntityPrefix('?');
        $this->setUrlLinkCurrencyPrefix('?');
        $this->setUrlLinkTokenPoolPrefix('?');
        $this->setUrlLinkTokenPrefix('?');
        $this->setUrlLinkTransactionPrefix('?');
        $this->setUrlLinkWalletPrefix('?');

        $this->_findLogoApplication();
        $this->_findLogoApplicationLink();
        $this->_findLogoApplicationName();
        $this->_findCurrentDisplayMode();
        $this->_findCurrentModule();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Si en mode téléchargement d'objet ou de lien, pas de traduction.
        if ($this->_traductionInstance !== null) {
            $this->_currentDisplayLanguage = $this->_traductionInstance->getCurrentLanguage();
            $this->_currentDisplayLanguageInstance = $this->_traductionInstance->getCurrentLanguageInstance();
            $this->_displayLanguageList = $this->_traductionInstance->getLanguageList();
            $this->_displayLanguageInstanceList = $this->_traductionInstance->getLanguageModuleInstanceList();
        }
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------
	 *
	 * Pour l'instant, rien n'est personnalisable dans le style, mais ça viendra...
	 * @todo
	 */
    /**
     * Variable du logo de l'application.
     * @var string
     */
    private $_logoApplication = '';

    /**
     * Recherche le logo de l'application.
     */
    private function _findLogoApplication()
    {
        $this->_logoApplication = self::DEFAULT_APPLICATION_LOGO;
        // @todo
    }

    /**
     * Variable du lien du logo de l'application.
     * @var string
     */
    private $_logoApplicationLink = '';

    /**
     * Recherche le lien du logo de l'application.
     */
    private function _findLogoApplicationLink()
    {
        $this->_logoApplicationLink = self::DEFAULT_APPLICATION_LOGO_LINK;
        // @todo
    }

    /**
     * Variable du nom de l'application.
     * @var string
     */
    private $_logoApplicationName = '';

    /**
     * Recherche le nom de l'application.
     */
    private function _findLogoApplicationName()
    {
        $this->_logoApplicationName = Application::APPLICATION_NAME;
        // @todo
    }


    /**
     * Vérifie que toutes les icônes déclarées soient présentes.
     * Sinon les synchronises.
     */
    private function _checkDefinedIcons()
    {
        // @todo
    }

    /**
     * Code before display.
     */
    protected function _preDisplay(): void
    {
        $namespace = '\\' . __NAMESPACE__ . '\\';

        // Préfix pour les objets. Les modules sont chargés, on peut les utiliser.
        $this->setUrlLinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        // Préfix pour les groupes.
        if ($this->_applicationInstance->isModuleLoaded($namespace . 'ModuleGroups')) {
            $this->setUrlLinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleGroups')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleGroups')->getDefaultView()
                . '&' . References::COMMAND_SELECT_GROUP . '=');
        } else {
            $this->setUrlLinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }

        // Préfix pour les conversations.
        if ($this->_applicationInstance->isModuleLoaded($namespace . 'Nebule\\Modules\\ModuleMessenger')) {
            $this->setUrlLinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Nebule\\Modules\\ModuleMessenger')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Nebule\\Modules\\ModuleMessenger')->getDefaultView()
                . '&' . References::COMMAND_SELECT_CONVERSATION . '=');
        } else {
            $this->setUrlLinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }

        // Préfix pour les entités.
        $this->setUrlLinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleEntities')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleEntities')->getDefaultView()
            . '&' . References::COMMAND_SELECT_ENTITY . '=');

        // Préfix pour les monnaies.
        if ($this->_applicationInstance->isModuleLoaded('ModuleQantion')) {
            $this->setUrlLinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getRegisteredViews()[3]
                . '&' . References::COMMAND_SELECT_CURRENCY . '=');
            $this->setUrlLinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getRegisteredViews()[8]
                . '&' . References::COMMAND_SELECT_TOKENPOOL . '=');
            $this->setUrlLinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getRegisteredViews()[13]
                . '&' . References::COMMAND_SELECT_TOKEN . '=');
            $this->setUrlLinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getRegisteredViews()[19]
                . '&' . References::COMMAND_SELECT_TRANSACTION . '=');
            $this->setUrlLinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleQantion')->getRegisteredViews()[23]
                . '&' . References::COMMAND_SELECT_WALLET . '=');
        } else {
            $this->setUrlLinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
            $this->setUrlLinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule($namespace . 'ModuleObjects')->getDefaultView()
                . '&' . References::COMMAND_SELECT_OBJECT . '=');
        }
    }


    /**
     * Display full page.
     */
    protected function _displayFull(): void
    {
        $this->_metrologyInstance->addLog('Display full', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'c3cdf3de');
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo $this->_currentDisplayLanguage; ?>">
        <head>
            <meta charset="utf-8"/>
            <title><?php echo Application::APPLICATION_NAME . ' - ' . $this->_nebuleInstance->getCurrentEntityInstance()->getFullName('all'); ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo Application::APPLICATION_SURNAME; ?>"/>
            <meta name="description" content="<?php echo Application::APPLICATION_NAME . ' - ';
            echo $this->_traductionInstance->getTranslate('::::HtmlHeadDescription'); ?>"/>
            <meta name="author" content="<?php echo Application::APPLICATION_AUTHOR . ' - ' . Application::APPLICATION_WEBSITE; ?>"/>
            <meta name="licence" content="<?php echo Application::APPLICATION_LICENCE; ?>"/>
            <?php
            $this->_metrologyInstance->addLog('Display css', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '7fcf1976');
            $this->commonCSS();
            $this->displayCSS();

            $this->_metrologyInstance->addLog('Display vbs', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ecbd188e');
            $this->_displayScripts();
            ?>

        </head>
        <body>
        <?php
        $this->_metrologyInstance->addLog('Display actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'fac72c30');
        $this->_displayActions();

        $this->_metrologyInstance->addLog('Display header', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5d4eb6f0');
        $this->_displayHeader();

        $this->_metrologyInstance->addLog('Display menu apps', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '89f37a59');
        $this->_displayMenuApplications();
        ?>

        <div class="layout-main">
            <div class="layout-content">
                <div id="curseur" class="infobulle"></div>
                <div class="content">
                    <?php
                    $this->_metrologyInstance->addLog('Display checks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '28feadb6');
                    $this->displaySecurityAlert('small', false);

                    $this->_metrologyInstance->addLog('Display content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '58d043ab');
                    $this->_displayContent();

                    $this->_metrologyInstance->addLog('Display metrology', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3012e9f4');
                    $this->_displayMetrology();
                    ?>

                </div>
            </div>
        </div>
        <?php
        $this->_metrologyInstance->addLog('Display footer', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b1d7feb6');
        $this->_displayFooter();
    }

    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $bgobj = $this->_nebuleInstance->newObject(self::DEFAULT_CSS_BACKGROUND);
        $background = $bgobj->getUpdateNID(true, false);
        ?>

        <style type="text/css">
            html, body {
                font-family: Sans-Serif, monospace, Arial, Helvetica;
                font-stretch: condensed;
                font-size: 0.9em;
                text-align: left;
                min-height: 100%;
                width: 100%;
            }

            @media screen {
                body {
                    background: #f0f0f0 url("<?php echo 'o/'.$background; ?>") no-repeat;
                    background-position: center center;
                    background-size: cover;
                    background-attachment: fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                }
            }

            @media print {
                body {
                    background: #ffffff;
                }
            }

            .logoFloatLeft {
                float: left;
                margin: 5px;
                height: 64px;
                width: 64px;
            }

            .floatLeft {
                float: left;
                margin-right: 5px;
            }

            .floatRight {
                float: right;
                margin-left: 5px;
            }

            .textAlignLeft {
                text-align: left;
            }

            .textAlignRight {
                text-align: right;
            }

            .textAlignCenter {
                text-align: center;
            }

            .iconInlineDisplay {
                height: 16px;
                width: 16px;
            }

            .iconNormalDisplay {
                height: 64px;
                width: 64px;
            }

            .hideOnSmallMedia {
                display: block;
            }

            .divHeaderH1, .divHeaderH2 {
                overflow: hidden;
            }

            .layout-header {
                height: 74px;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-header {
                border-bottom-style: solid;
                border-bottom-color: #222222;
                border-bottom-width: 5px;
            }

            .layout-footer {
                height: auto;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-footer {
                border-top-style: solid;
                border-top-color: #222222;
                border-top-width: 5px;
            }

            .header-left {
                margin: 5px 0 5px 5px;
            }

            .header-left2 {
                margin: 5px;
                height: 64px;
                width: 207px;
                float: left;
            }

            .header-left2 img {
                margin-right: 5px;
            }

            .layout-header .layoutObject {
                float: left;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText .objectTitleMediumName {
                overflow: visible;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText {
                background: none;
                color: #ffffff;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText a {
                color: #ffffff;
            }

            .header-right {
                margin: 5px;
            }

            .text a {
                color: #000000;
            }

            .menuListContentAction {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .sylabeMenuListContentActionModules {
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .sylabeMenuListContentActionHooks {
                background: rgba(255, 255, 255, 0.66);
            }

            /* Liserets de verrouillage. */
            .headerUnlock {
                border-bottom-color: #e0000e;
            }

            .footerUnlock {
                border-top-color: #e0000e;
            }

            /* Le menu de gauche, contextuel et toujours visible même partiellement. */
            .layout-menu-left {
                display: inline;
                min-width: 217px;
                max-width: 217px;
                min-height: 100px;
                margin: 0;
                margin-right: 10px;
                margin-bottom: 20px;
                margin-top: 120px;
            }

            @media screen and (max-width: 1020px) {
                .layout-menu-left {
                    min-width: 74px;
                    max-width: 74px;
                    margin-right: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            .menu-left {
                width: 217px;
                color: #000000;
                background: rgba(255, 255, 255, 0.2);
                background-origin: border-box;
            }

            .menu-left img {
                height: 64px;
                width: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left {
                    width: 74px;;
                }

                .menu-left img {
                    height: 64px;
                    width: 64px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            .menu-left-module {
                height: 64px;
                width: 207px;
                padding: 5px;
                margin-bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                background-origin: border-box;
                color: #ffffff;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-module {
                    height: 64px;
                    width: 64px;
                    padding: 5px;
                    margin-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            .menu-left-links {
                width: 207px;
                padding: 5px;
                padding-bottom: 0;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-links {
                    width: 64px;
                    padding: 5px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            .menu-left-interlinks {
                height: 15px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-interlinks {
                    height: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            .menu-left-one {
                clear: both;
                padding-bottom: 5px;
                width: 207px;
                min-height: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-one {
                    min-height: 64px;
                    width: 64px;
                    padding-bottom: 5px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            .menu-left-icon {
                float: left;
                margin-right: 5px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            .menu-left-modname p {
                font-size: 0.6em;
                font-style: italic;
            }

            .menu-left-title p {
                font-size: 1.1em;
                font-weight: bold;
            }

            .menu-left-text p {
                font-size: 0.8em;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            /* Correction affichage objets */
            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 343px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 511px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 767px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 943px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1343px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1663px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1791px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2143px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3583px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3839px;
                }
            }

            .objectsListContent {
                background: rgba(255, 255, 255, 0.1);
            }

            .informationDisplayMessage {
                background: rgba(0, 0, 0, 0.5);
            }

            .informationDisplayInfo {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContentDiv {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContent h1 {
                color: #333333;
            }
        </style>
        <?php

        // Ajout de la partie CSS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }


    /**
     * Affichage des scripts JS.
     */
    protected function _displayScripts(): void
    {
        $this->commonScripts();

        // Ajout de la partie script JS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->headerScript();
                echo "\n";
            }
        }
    }


    /**
     * Affichage des actions.
     */
    private function _displayActions()
    {
        ?>

        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <p>
                <?php
                // Vérifie le ticket.
                if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
                    // Appelle les actions spéciales.
                    $this->_actionInstance->specialActions();

                    // Appelle les actions génériques.
                    $this->_actionInstance->genericActions();

                    // Appelle les actions du module concerné par le mode d'affichage.
                    foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                        if ($module->getCommandName() == $this->_currentDisplayMode) {
                            $this->_metrologyInstance->addLog('Actions for module ' . $module->getCommandName(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '55fba077');
                            $module->action();
                        }
                    }
                }
                ?>

            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la barre supérieure.
     *
     * La partie gauche présente le menu et l'entité en cours.
     *
     * La partie droite présente :
     * - Un ok si tout se passe bien.
     * - Un warning si il y a un problème. En mode rescue, on peut quand même verrouiller/déverrouiller l'entité.
     * - Une erreur si il y a un gros problème. Il n'est pas possible de déverrouiller l'entité.
     */
    private function _displayHeader()
    {
        ?>

        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <div class="header-left">
                <?php
                if ($this->_configurationInstance->getOptionAsBoolean('permitJavaScript')) {
                    ?>
                    <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                         title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"
                         onclick="display_menu('layout-menu-applications');"/>
                    <?php
                } else {
                    ?>
                    <a href="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->getCurrentDisplayMode() . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW; ?>=menu">
                        <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                             title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"/>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
            // Affiche l'entité et son image.
            $param = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => true,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'enableDisplayJS' => false,
                'enableDisplayObjectActions' => false,
            );
            if ($this->_unlocked) {
                $param['flagUnlockedLink'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_INFO
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_FLUSH;
            } else {
                $param['flagUnlockedLink'] = '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGIN
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntityID();
            }
            echo $this->getDisplayObject_DEPRECATED($this->_nebuleInstance->getCurrentEntityInstance(), $param);
            ?>

            <div class="header-right">
                <?php
                if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
                    echo "&nbsp;\n";
                } // Si un test est en warning maximum.
                elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN') {
                    // Si mode rescue et en warning.
                    if ($this->_nebuleInstance->getModeRescue()) {
                        // Si l'entité est déverrouillées.
                        if ($this->_unlocked) {
                            // Affiche le lien de verrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_nebuleInstance->newObject(DisplayInformation::ICON_WARN_RID), 'Etat déverrouillé, verrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_INFO
                                . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                                . '&' . References::COMMAND_FLUSH);
                        } else {
                            // Affiche de lien de déverrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_nebuleInstance->newObject(DisplayInformation::ICON_WARN_RID), 'Etat verrouillé, déverrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . References::COMMAND_SWITCH_APPLICATION . '=2'
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . References::COMMAND_AUTH_ENTITY_MOD
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_AUTH_ENTITY_LOGIN
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntityID());
                        }
                    } // Sinon affiche le warning.
                    else {
                        $this->displayHypertextLink(
                            $this->convertUpdateImage(
                                $this->_nebuleInstance->newObject(DisplayInformation::ICON_WARN_RID),
                                'WARNING'),
                            '?' . References::COMMAND_AUTH_ENTITY_LOGOUT
                            . '&' . References::COMMAND_SWITCH_TO_ENTITY);
                    }
                } // Sinon c'est une erreur.
                else {
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            $this->_nebuleInstance->newObject(DisplayInformation::ICON_ERROR_RID),
                            'ERROR'),
                        '?' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_FLUSH);
                }
                ?>

            </div>
            <div class="header-center">
                <?php $this->_displayHeaderCenter(); ?>

            </div>
        </div>
        <?php
    }


    /**
     * Affiche la partie centrale de l'entête.
     * Non utilisé.
     */
    private function _displayHeaderCenter()
    {
        //...
    }


    /**
     * Affiche le menu des applications.
     */
    private function _displayMenuApplications()
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;
        ?>

        <div class="layout-menu-applications" id="layout-menu-applications">
            <div class="menu-applications-sign">
                <img alt="<?php echo Application::APPLICATION_NAME; ?>" src="<?php echo $this->_logoApplication; ?>"/><br/>
                <?php echo Application::APPLICATION_NAME; ?><br/>
                (c) <?php echo Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR; ?><br/>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Version');
                echo ' : ' . Application::APPLICATION_VERSION; ?><br/>
                <a href="<?php echo $linkApplicationWebsite; ?>" target="_blank"><?php echo Application::APPLICATION_WEBSITE; ?></a>
            </div>
            <div class="menu-applications-logo">
                <img src="<?php echo $this->_logoApplication; ?>" alt="[M]"
                     title="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::menu'); ?>"
                     onclick="display_menu('layout-menu-applications');"/>
            </div>
            <div class="menu-applications">
                <?php
                $this->_displayInternalMenuApplications();
                ?>

            </div>
        </div>
        <?php
    }

    /**
     * Affiche le menu des applications.
     *
     * Affiche dans l'ordre :
     * 1 : Les actions du module concerné par le mode d'affichage.
     *     Pour le module courant, appelle le point d'encrage (hook) nommé 'selfMenu'. Les autres
     *       modules ne sont pas consultés.
     *     Si c'est pour l'affichage du menu déroulant et non de la page de menu, ajoute au début
     *       un lien vers la page de menu. Cela permet de voir toutes les entrés du menu même
     *       si il est très long.
     * 2 : Les actions d'autres modules pour le module concerné par le mode d'affichage.
     *     Pour tous les modules sauf le module courant, appelle le point d'ancrage nommé de la
     *       concaténation du nom du module courant et de 'SelfMenu'. Par exemple 'ObjectSelfMenu'.
     * 3 : Les actions d'autres modules pour le mode d'affichage.
     *     Pour tous les modules sauf le module courant, appelle le point d'ancrage nommé 'menu'.
     * 4 : La liste des modules.
     *     Pour tous les modules, affiche les entrées vers les vues définies par la variable
     *       $MODULE_APP_TITLE_LIST de chaque modules.
     * 5 : Une entrée vers l'application 0.
     *
     * @return void
     */
    private function _displayInternalMenuApplications()
    {
        $modules = $this->_applicationInstance->getModulesListInstances();
        $list = array();
        $j = 0;
        $currentModuleName = 'noModuleFind-';

        // Appelle les actions du module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Mémorise le nom du module trouvé.
                $currentModuleName = $module->getMenuName();

                // Affiche le lien du menu seul (sans JS).
                if ($this->_currentDisplayView != 'menu') {
                    $list[$j]['icon'] = self::DEFAULT_LOGO_MODULE;
                    $list[$j]['title'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::menu', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=menu';
                    $list[$j]['desc'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::menuDesc', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['ref'] = Application::APPLICATION_NAME;
                    $list[$j]['class'] = 'sylabeMenuListContentActionModules';
                    $j++;
                }

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('selfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'sylabeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList($currentModuleName . 'SelfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'sylabeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('menu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'sylabeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle la liste des modules.
        foreach ($modules as $module) {
            // Extrait le nom du module.
            $moduleName = $module->getTraduction($module->getName(), $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());

            // Liste les options à afficher.
            $appTitleList = $module->getAppTitleList();
            if (sizeof($appTitleList) != 0) {
                $appIconList = $module->getAppIconList();
                $appDescList = $module->getAppDescList();
                $appViewList = $module->getAppViewList();
                for ($i = 0; $i < sizeof($appTitleList); $i++) {
                    $icon = $appIconList[$i];
                    if ($icon == '') {
                        $icon = $module->getLogo();
                    }
                    if ($icon == '') {
                        $icon = self::DEFAULT_ICON_LSTOBJ;
                    }
                    $desc = $module->getTraduction($appDescList[$i], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    if ($desc == '') {
                        $desc = '&nbsp;';
                    }

                    $list[$j]['icon'] = $icon;
                    $list[$j]['title'] = $module->getTraduction($appTitleList[$i], $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $appViewList[$i];
                    $list[$j]['desc'] = $desc;
                    $list[$j]['ref'] = $moduleName;
                    $list[$j]['class'] = 'sylabeMenuListContentActionModules';
                    $j++;
                }
            }
        }

        // Ajoute l'application 0.
        $list[$j]['icon'] = parent::DEFAULT_APPLICATION_LOGO;
        $list[$j]['title'] = BOOTSTRAP_NAME;
        $list[$j]['htlink'] = '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP;
        $list[$j]['desc'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::appSwitch', $this->_applicationInstance->getTranslateInstance()->getCurrentLanguage());
        $list[$j]['ref'] = 'nebule';
        $list[$j]['class'] = 'sylabeMenuListContentActionModules';

        echo $this->getDisplayMenuList($list, 'Medium');
    }


    /**
     * Contenu de la page.
     *
     * Affiche le contenu des pages en fonction du mode demandé.
     * Un seul mode est pris en compte pour l'affichage, les autres sont ignorés.
     *
     * Le traitement de l'affichage de la vue est faite par le module gérant le mode d'affichage.
     *
     * Seule exception, la vue 'menu' est traitée comme un affichage du menu sans JS et sans passer directement par un module.
     * Le contenu du menu est lui dépendant du module en cours et de certaines réponses des autres modules.
     */
    private function _displayContent()
    {
        if ($this->_currentDisplayView == 'menu') {
            $this->_displayInternalMenuApplications();
        } else {
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module->getCommandName() == $this->_currentDisplayMode) {
                    $module->displayModule();
                }
            }
        }
        $this->_displayInlineContentID();
    }


    /**
     * Affiche la métrologie.
     */
    private function _displayMetrology()
    {
        if ($this->_configurationInstance->getOptionUntyped('sylabeDisplayMetrology')) {
            ?>

            <?php $this->displayDivTextTitle(self::DEFAULT_ICON_IMLOG, 'Métrologie', 'Mesures quantitatives et temporelles.') ?>
            <div class="text">
                <p>
                    <?php
                    //		aff_title('::bloc_metrolog','imlog');
                    // Affiche les valeurs de la librairie.
                    /*		echo 'Bootstrap : ';
		$this->_traductionInstance->echoTraduction('%01.0f liens lus,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkRead()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f liens vérifiés,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkVerify()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f objets vérifiés.','',$this->_bootstrapInstance->getMetrologyInstance()->getObjectVerify()); echo "<br />\n";*/
                    echo 'Lib nebule : ';
                    echo $this->_traductionInstance->getTranslate('%01.0f liens lus,', $this->_metrologyInstance->getLinkRead());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f liens vérifiés,', $this->_metrologyInstance->getLinkVerify());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f objets lus.', $this->_metrologyInstance->getObjectRead());
                    echo ' ';
                    echo $this->_traductionInstance->getTranslate('%01.0f objets vérifiés.', $this->_metrologyInstance->getObjectVerify());
                    echo "<br />\n";
                    // Calcul de temps de chargement de la page - stop
                    /*		$bootstrapTimeList = $this->_bootstrapInstance->getMetrologyInstance()->getTimeArray();
		$bootstrap_time_total = 0;
		foreach ( $bootstrapTimeList as $time )
		{
			$bootstrap_time_total = $bootstrap_time_total + $time;
		}
		$this->_traductionInstance->echoTraduction('Le bootstrap à pris %01.4fs pour appeler la page.','',$bootstrap_time_total);
		echo ' (';
		foreach ( $bootstrapTimeList as $time )
		{
			echo sprintf(" %1.4fs", $time);
		}
		echo " )\n";
		echo "<br />\n";*/
                    $this->_metrologyInstance->addTime();
                    $sylabeTimeList = $this->_metrologyInstance->getTimeArray();
                    $sylabe_time_total = 0;
                    foreach ($sylabeTimeList as $time) {
                        $sylabe_time_total = $sylabe_time_total + $time;
                    }
                    echo $this->_traductionInstance->getTranslate('Le serveur à pris %01.4fs pour calculer la page.', $sylabe_time_total);
                    echo ' (';
                    foreach ($sylabeTimeList as $time) {
                        echo sprintf(" %1.4fs", $time);
                    }
                    echo " )\n";
                    ?>

                </p>
            </div>
            <?php
        }
    }

    // Affiche la fin de page.
    private function _displayFooter()
    {
        ?>

        </body>
        </html>
        <?php
    }


    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1($object, $help = '', $desc = '')
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        // Prépare le type mime.
        $typemime = $object->getType('all');
        if ($desc == '') {
            $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($typemime);
        }

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == Entity::ENTITY_TYPE && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) {
            $object = $this->_nebuleInstance->newEntity($object->getID());
        }
        if ($isGroup && !is_a($object, 'Group')) {
            $object = $this->_nebuleInstance->newGroup($object->getID());
        }
        if ($isConversation && !is_a($object, 'Conversation')) {
            $object = $this->_nebuleInstance->newConversation($object->getID());
        }

        $name = $object->getFullName('all');
        $present = $object->checkPresent();

        // Si le contenu est présent.
        if ($present) {
            // Prépare l'état de l'objet.
            $status = 'ok';
            $content = $object->getContent(0);
            $tooBig = false;
            if ($content == null) {
                $status = 'tooBig';
            }
            unset($content);
        } elseif ($isConversation
            || $isGroup
        ) {
            $status = 'notAnObject';
        } else {
            $status = 'notPresent';
        }
        if ($object->getMarkWarning()) {
            $status = 'warning';
        }
        if ($object->getMarkDanger()) {
            $status = 'danger';
        }
        // Prépare l'aide en ligne.
        if ($help == '') {
            if ($isEntity) {
                $help = ':::display:default:help:Entity';
            } elseif ($isConversation) {
                $help = ':::display:default:help:Conversation';
            } elseif ($isGroup) {
                $help = ':::display:default:help:Group';
            } else {
                $help = ':::display:default:help:Object';
            }
        }
        ?>

        <div class="textTitle">
            <?php
            $this->_displayDivOnlineHelp($help);
            ?>

            <div class="floatRight">
                <?php
                switch ($status) {
                    case 'notPresent':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configurationInstance->getOptionUntyped('sylabeDisplayUnverifyLargeContent')) {
                            $msg = $this->_traductionInstance->getTranslate('::::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_traductionInstance->getTranslate(':::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:OK');
                        $this->displayIcon(self::DEFAULT_ICON_IOK, $msg, 'iconNormalDisplay');
                        break;
                }
                unset($msg);
                ?>

            </div>
            <div style="float:left;">
                <?php
                $this->displayObjectColorIcon($object);
                ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $name; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php
        unset($name, $typemime, $isEntity, $isGroup, $isConversation);
    }

    /**
     * Affiche dans les barres de titres l'icône d'aide contextuelle.
     * @param string $help
     */
    private function _displayDivOnlineHelp($help)
    {
        // Si authorisé à afficher l'aide.
        if ($this->_configurationInstance->getOptionUntyped('sylabeDisplayOnlineHelp')) {
            // Prépare le texte à afficher dans la bulle.
            $txt = $this->_applicationInstance->getTranslateInstance()->getTranslate($help);
            $txt = str_replace('&', '&amp;', $txt);
            $txt = str_replace('"', '&quot;', $txt);
            $txt = str_replace("'", '&acute;', $txt);
            //$txt = str_replace('<','&lt;',$txt);
            $txt = str_replace("\n", ' ', $txt);
            // Prépare l'extension de lien.
            $linkext = 'onmouseover="montre(\'<b>' . $this->_applicationInstance->getTranslateInstance()->getTranslate('Aide') . ' :</b><br />' . $txt . '\');" onmouseout="cache();"';
            unset($txt);
            // Affiche la bulle et le texte.
            ?>

            <div style="float:right;">
                <?php
                $image = $this->prepareIcon(self::DEFAULT_ICON_HELP);
                ?>

                <img alt="[]" src="<?php echo $image; ?>" class="iconNormalDisplay"
                     id="curseur" <?php echo $linkext; ?> />
            </div>
            <?php
            unset($linkext, $image);
        }
    }


    /**
     * Affiche le titre pour un paragraphe de texte. Par défaut, affiche le titre H1.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitle($icon, $title = '', $desc = '', $help = '')
    {
        $this->displayDivTextTitleH1($icon, $title, $desc, $help);
    }

    /**
     * Affiche le titre H1 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH1($icon, $title = '', $desc = '', $help = '')
    {
        ?>

        <div class="textTitle">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $title; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

    }

    /**
     * Affiche le titre H2 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH2($icon, $title = '', $desc = '', $help = '')
    {
        ?>

        <div class="textTitle2">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h2 class="divHeaderH2"><?php echo $title; ?></h2>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

    }
}


/**
 * Classe Action
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Action extends Actions
{
    // Tout par défaut.
}


/**
 * Classe Traduction
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Translate extends Translates
{
    public function __construct(Application $applicationInstance)
    {
        parent::__construct($applicationInstance);
    }
}


/**
 * Ce module permet d'afficher l'aide et la page par défaut.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleHelp extends Modules
{
    protected string $MODULE_TYPE = 'Application';
    protected string $MODULE_NAME = '::sylabe:module:help:ModuleName';
    protected string $MODULE_MENU_NAME = '::sylabe:module:help:MenuName';
    protected string $MODULE_COMMAND_NAME = 'hlp';
    protected string $MODULE_DEFAULT_VIEW = '1st';
    protected string $MODULE_DESCRIPTION = '::sylabe:module:help:ModuleDescription';
    protected string $MODULE_VERSION = '020230110';
    protected string $MODULE_AUTHOR = 'Projet nebule';
    protected string $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2023';
    protected string $MODULE_LOGO = '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d.sha2.256';
    protected string $MODULE_HELP = '::sylabe:module:help:ModuleHelp';
    protected string $MODULE_INTERFACE = '3.0';

    protected array $MODULE_REGISTERED_VIEWS = array('1st', 'hlp', 'lang', 'about');
    protected array $MODULE_REGISTERED_ICONS = array(
        '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d.sha2.256',    // 0 : icône d'aide.
        '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256',    // 1 : module
        'd7f68db0a1d0977fb8e521fd038b18cd601946aa0e26071ff8c02c160549633b.sha2.256',    // 2 : bootstrap (metrologie)
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256',    // 3 : world
    );
    protected array $MODULE_APP_TITLE_LIST = array('::sylabe:module:help:AppTitle1');
    protected array $MODULE_APP_ICON_LIST = array('1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d.sha2.256');
    protected array $MODULE_APP_DESC_LIST = array('::sylabe:module:help:AppDesc1');
    protected array $MODULE_APP_VIEW_LIST = array('hlp');


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string    $hookName
     * @param Node|null $nid
     * @return array
     */
    public function getHookList(string $hookName, ?Node $nid = null): array
    {
        $object = $this->_applicationInstance->getCurrentObjectID();
        if ($nid !== null)
            $object = $nid->getID();

        $hookArray = array();
        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::sylabe:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::sylabe:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                break;
            case 'selfMenu':
                // Affiche l'aide.
                $hookArray[0]['name'] = '::sylabe:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::sylabe:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];

                // Choix de la langue.
                $hookArray[1]['name'] = '::sylabe:module:help:Langue';
                $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                $hookArray[1]['desc'] = '::sylabe:module:help:ChangerLangue';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];

                // A propos.
                $hookArray[2]['name'] = '::sylabe:module:help:About';
                $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3];
                break;
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
        $this->_displayHlpHeader();
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case '1st':
                $this->_displayHlpFirst();
                break;
            case 'hlp':
                $this->_displayHlpHelp();
                break;
            case 'lang':
                $this->_displayHlpLang();
                break;
            case 'about':
                $this->_displayHlpAbout();
                break;
            default:
                $this->_displayHlpFirst();
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
        // Rien à faire.
    }

    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        echo ".sylabeModuleHelpText1stI1 { position:absolute; left:50%; top:33%; margin-left:-32px; }\n";
        echo ".sylabeModuleHelpText1stI2 { position:absolute; left:50%; top:50%; margin-left:-32px; }\n";
        echo ".sylabeModuleHelpText1stI3 { position:absolute; left:50%; top:67%; margin-left:-32px; }\n";
        echo ".sylabeModuleHelpText1stT { position:absolute; right:33%; bottom:0; height:54px; min-width:33%; padding:5px; background:rgba(0,0,0,0.1); background-origin:border-box; }\n";
        echo ".sylabeModuleHelpText1stT p { position:relative; top:30%; color:#ffffff; text-align:center; font-size:1.5em; }\n";
    }


    /**
     * Affichage de l'entête des pages.
     */
    private function _displayHlpHeader(): void
    {
    }


    /**
     * Affichage de la page par défaut.
     */
    private function _displayHlpFirst(): void
    {
        ?>
        <div class="sylabeModuleHelpText1stI1">
            <?php
            $this->_applicationInstance->getDisplayInstance()->displayHypertextLink(
                $this->_applicationInstance->getDisplayInstance()->convertUpdateImage(
                    $this->_nebuleInstance->newObject(Display::DEFAULT_ICON_LSTENT),
                    $this->_translateInstance->getTranslate('Entités')),
                '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=list'); ?>

        </div>
        <div class="sylabeModuleHelpText1stI2">
            <?php
            $this->_applicationInstance->getDisplayInstance()->displayHypertextLink(
                $this->_applicationInstance->getDisplayInstance()->convertUpdateImage(
                    $this->_nebuleInstance->newObject($this->MODULE_LOGO),
                    $this->_translateInstance->getTranslate('::sylabe:module:help:AideGenerale')),
                '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=hlp'); ?>

        </div>
        <div class="sylabeModuleHelpText1stI3">
            <?php
            $this->_applicationInstance->getDisplayInstance()->displayHypertextLink(
                $this->_applicationInstance->getDisplayInstance()->convertUpdateImage(
                    $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[3]),
                    $this->_translateInstance->getTranslate('::sylabe:module:help:ChangerLangue')),
                '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=lang'); ?>

        </div>
        <div class="sylabeModuleHelpText1stT">
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::sylabe:module:help:Bienvenue') ?>
                <br/>
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la page de choix de la langue.
     */
    private function _displayHlpLang(): void
    {
        $module = $this->_applicationInstance->getTranslateInstance()->getCurrentLanguageInstance();

        // Affiche la langue en cours.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
            'icon' => $module->getLogo(),
        );
        echo $this->_displayInstance->getDisplayInformation_DEPRECATED($module->getTraduction($module->getName()), $param);

        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject($this->MODULE_REGISTERED_ICONS[3]);
        echo $this->_displayInstance->getDisplayTitle_DEPRECATED($this->_applicationInstance->getTranslateInstance()->getTranslate('::ChangeLanguage'), $icon, false);

        // Affiche la liste des langues.
        echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('helpLanguages', 'Medium');
    }


    /**
     * Affichage de l'aide générale.
     */
    private function _displayHlpHelp(): void
    {
        ?>
        <div class="text">
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::sylabe:module:help:AideGenerale:Text') ?>
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la page à propos.
     */
    private function _displayHlpAbout(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;

        // Affiche les informations de l'application.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => false,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list = array();
        $list[0]['information'] = Application::APPLICATION_SURNAME;
        $list[0]['param'] = $param;
        $list[0]['param']['icon'] = Display::DEFAULT_APPLICATION_LOGO;
        //$list[0]['object'] = '1';
        $list[1]['information'] = '<a href="' . $linkApplicationWebsite . '" target="_blank">' . Application::APPLICATION_WEBSITE . '</a>';
        $list[1]['param'] = $param;
        $list[2]['information'] = $this->_applicationInstance->getTranslateInstance()->getTranslate('::Version') . ' : ' . Application::APPLICATION_VERSION;
        $list[2]['param'] = $param;
        $list[3]['information'] = Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR;
        $list[3]['param'] = $param;
        $param['informationType'] = 'information';
        $list[4]['information'] = BOOTSTRAP_SURNAME;
        $list[4]['param'] = $param;
        $list[4]['param']['icon'] = LIB_BOOTSTRAP_ICON;
        //$list[4]['object'] = '1';
        $list[5]['information'] = '<a href="http://' . BOOTSTRAP_WEBSITE . '" target="_blank">' . BOOTSTRAP_WEBSITE . '</a>';
        $list[5]['param'] = $param;
        echo $this->_displayInstance->getDisplayObjectsList($list, 'Medium', false);

        ?>
        <div class="text">
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::sylabe:module:help:APropos:Text') ?>
            </p>
            <p>
                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::sylabe:module:help:APropos:Liens') ?>
            </p>
        </div>
        <?php
    }

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::sylabe:module:help:ModuleName' => "Module d'aide",
            '::sylabe:module:help:MenuName' => 'Aide',
            '::sylabe:module:help:ModuleDescription' => "Module d'aide en ligne.",
            '::sylabe:module:help:ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::sylabe:module:help:AppTitle1' => 'Aide',
            '::sylabe:module:help:AppDesc1' => "Affiche l'aide en ligne.",
            '::sylabe:module:help:Bienvenue' => 'Bienvenue sur <b>sylabe</b>.',
            '::sylabe:module:help:Langue' => 'Langue',
            '::sylabe:module:help:ChangerLangue' => 'Changer de langue',
            '::sylabe:module:help:About' => 'A propos',
            '::sylabe:module:help:Bootstrap' => 'Bootstrap',
            '::sylabe:module:help:Demarrage' => 'Démarrage',
            '::sylabe:module:help:AideGenerale' => 'Aide générale',
            '::sylabe:module:help:APropos' => 'A propos',
            '::sylabe:module:help:APropos:Text' => "Le projet <i>sylabe</i> est une implémentation logicielle basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.<br />
<br />
Le projet <i>nebule</i> crée un réseau. Non un réseau de machines mais un réseau de données.<br />
<br />
Les systèmes informatiques actuels sont incapables de gérer directement les objets et les liens. Il n’est donc pas possible d’utiliser nebule nativement.
Le projet sylabe permet un accès à cette nouvelle façon de gérer nos informations sans remettre en question fondamentalement l’organisation et notamment les systèmes d’exploitation de notre système d’information.<br />
<br />
L’interface sylabe est une page web destinée à être mise en place sur un serveur pour manipuler des objets et des liens.
Cela s’apparente tout à fait à ce qui se fait déjà communément : Google (et sa galaxie de sites), Facebook, Twitter, Outlook, Yahoo et leurs innombrables concurrents et prétendants…
Tous ces sites sont globalement plus concurrents que complémentaires, et tous sans exception sont fermés à leurs concurrents.
Cela se traduit pour l’utilisateur par la nécessité de, soit disposer d’un compte sur chaque site, soit de ne fréquenter que certains d’entre eux, voir un seul.
Cela se traduit aussi par l’impossibilité d’échanger directement des données et informations d’un site à l’autre.<br />
<br />
Le projet sylabe reproduit la concentration des données vers des serveurs sur l’Internet.
Il est nativement prévu pour pouvoir être implanté sur n’importe quel serveur web.
Et, se basant sur les principes de nebule, tout serveur hébergeant sylabe peut nativement :<br />
 1. gérer les identités générées par les autres serveurs, que ce soit un utilisateur ou un robot ;<br />
 2. échanger des données et des informations avec tous les autres serveurs implémentant nebule ;<br />
 3. relayer les données et les informations d’autres serveurs ;<br />
 4. permettre à tout utilisateur (connu du serveur) de s’y connecter.<br />
<br />
Grâce à IPv6, nous avons la possibilité de réellement connecter toutes les machines sur l’Internet.
Chacun peut ainsi mettre en place simplement sylabe chez lui, ou continuer à l’utiliser sur un autre serveur de l’Internet.
Chacun peut devenir individuellement actif.<br />
<br />
Enfin, si un jour nebule s’étend à toutes les machines et que toutes ces machines l’implémentent nativement, alors le projet sylabe disparaîtra.
Il aura rempli sa mission : permettre une transition douce vers nebule.<br />
Il sera vu comme bizarrerie symptomatique d’une époque.",
            '::sylabe:module:help:APropos:Liens' => 'Voir aussi :<br /><a href="http://blog.sylabe.org/">Le blog du projet sylabe</a><br /><a href="http://blog.nebule.org/">Le blog du projet nebule</a>',
            '::sylabe:module:help:AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu en haut à gauche est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::sylabe:module:help:ModuleName' => 'Help module',
            '::sylabe:module:help:MenuName' => 'Help',
            '::sylabe:module:help:ModuleDescription' => 'Online help module.',
            '::sylabe:module:help:ModuleHelp' => 'This module permit to display general help about the interface.',
            '::sylabe:module:help:AppTitle1' => 'Help',
            '::sylabe:module:help:AppDesc1' => 'Display online help.',
            '::sylabe:module:help:Bienvenue' => 'Welcome to <b>sylabe</b>.',
            '::sylabe:module:help:Langue' => 'Language',
            '::sylabe:module:help:ChangerLangue' => 'Change language',
            '::sylabe:module:help:About' => 'About',
            '::sylabe:module:help:Bootstrap' => 'Bootstrap',
            '::sylabe:module:help:Demarrage' => 'Start',
            '::sylabe:module:help:AideGenerale' => 'General help',
            '::sylabe:module:help:APropos' => 'About',
            '::sylabe:module:help:APropos:Text' => 'The <i>sylabe</i> project is a software implementation based on nebule project.<br />
This php implementation is intended to be a reference of the potential of objects and relationships as defined in nebule.<br />
<br />
The <i>nebule</i> project create a network. Not a network of computers but a network of datas.<br />
<br />
Current computer systems are unable to directly manage objects and links. It is thus not possible to use native nebule.
The sylabe project provides access to this new way of managing our information without questioning fundamentally the organization including the operating system of our information systems.<br />
<br />
The sylabe interface is a web page to be set up on a server to handle objects and links.
This all sounds a lot to what is already commonly exist: Google (and its galaxy of sites), Facebook, Twitter, Outlook, Yahoo and countless competitors and pretenders…
All these sites are generally more competitive than complementary, and all without exception are closed to competitors.
This means to the user by the need to either have an account on each site, or attend only some of them, to see one.
This also results in the inability to directly exchange data and information from one site to another.<br />
<br />
The project sylabe reproduced concentration data to servers on the Internet.
It is expected to be natively installed on any web server.
And, based on the principles of nebule, any server hosting sylabe can natively:<br />
 1. manage the identities generated by the other server, whether a user or a robot;<br />
 2. exchange data and information with all other servers implementing nebule;<br />
 3. relaying the data and the other data servers;<br />
 4. allow any user (known to the server) to connect to it.<br />
<br />
With IPv6, we have the ability to actually connect all the machines on the Internet.
Everyone can simply set up sylabe at home, or continue using another Internet server.
Each individual can become active.<br />
<br />
Finally, if one day nebule extends to all machines and all these machines implement it natively, then the project sylabe will disappear.
He will have served its purpose: to allow a smooth transition to nebule.<br />
It will be seen as symptomatic of an era oddity.',
            '::sylabe:module:help:APropos:Liens' => 'See also :<br /><a href="http://blog.sylabe.org/">The blog of sylabe projet</a><br /><a href="http://blog.nebule.org/">the blog of nebule projet</a>',
            '::sylabe:module:help:AideGenerale:Text' => 'The software is composed of three parts:<br />
1. the top banner that contains the application menu and the current entity.<br />
2. <br />
3. <br />
<br />
<br />
',
        ],
        'es-co' => [
            '::sylabe:module:help:ModuleName' => 'Módulo de ayuda',
            '::sylabe:module:help:MenuName' => 'Ayuda',
            '::sylabe:module:help:ModuleDescription' => 'Módulo de ayuda en línea.',
            '::sylabe:module:help:ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::sylabe:module:help:AppTitle1' => 'Ayuda',
            '::sylabe:module:help:AppDesc1' => 'Muestra la ayuda en línea.',
            '::sylabe:module:help:Bienvenue' => 'Bienviedo en <b>sylabe</b>.',
            '::sylabe:module:help:Langue' => 'Idioma',
            '::sylabe:module:help:ChangerLangue' => 'Cambio de idioma',
            '::sylabe:module:help:About' => 'About',
            '::sylabe:module:help:Bootstrap' => 'Bootstrap',
            '::sylabe:module:help:Demarrage' => 'Comienzo',
            '::sylabe:module:help:AideGenerale' => 'Ayuda general',
            '::sylabe:module:help:APropos' => 'Acerca',
            '::sylabe:module:help:APropos:Text' => 'El proyecto <i>sylabe</i> es un proyecto basado nebule implementación de software.<br />
Esta aplicación php está pensado como una referencia del potencial de los objetos y las relaciones como se define en nebule.<br />
<br />
Sistemas informáticos actuales son incapaces de gestionar directamente los objetos y enlaces. Por tanto, no es posible utilizar nebule nativo.
El proyecto sylabe proporciona acceso a esta nueva forma de gestionar nuestra información sin cuestionar en profundidad la organización incluyendo el sistema operativo de nuestros sistemas de información.<br />
<br />
La interfaz sylabe es una página web que se creará en el servidor para manejar objetos y enlaces.
Todo esto suena muy parecido a lo que ya es común: Google (y su galaxia de sitios), Facebook, Twitter, Outlook, Yahoo e innumerables competidores y pretendientes…
Todos estos sitios son generalmente más competitivas que complementarias, y todo sin excepción están cerrados a la competencia.
Esto se traduce en el usuario por la necesidad, ya sea tener una cuenta en cada sitio, o asistir sólo a algunos de ellos, para ver uno.
Esto también resulta en la incapacidad para intercambiar directamente datos y la información de un sitio a otro.<br />
<br />
El sylabe proyecto reproduce datos de concentración a los servidores de Internet.
Se espera que esté instalado de forma nativa en cualquier servidor web.
Y, en base a los principios de nebule, cualquier servidor de alojamiento sylabe puede nativa:<br />
 1. gestionar las identidades generadas por el otro servidor, si un usuario o un robot;<br />
 2. el intercambio de datos e información con el resto de servidores de aplicación nebule;<br />
 3. la retransmisión de los datos y los otros servidores de datos;<br />
 4. permitir a cualquier usuario (conocidos por el servidor) para conectarse a él.<br />
<br />
Con IPv6, tenemos la capacidad de conectarse en realidad todas las máquinas en Internet.
Todo el mundo puede simplemente configurar sylabe casa, o continuar utilizando otro servidor de Internet.
Cada individuo puede llegar a ser activo.<br />
<br />
Por último, si un día nebule se extiende a todas las máquinas y todas estas máquinas implementar de forma nativa, entonces el proyecto sylabe desaparecer.
Él habrá cumplido su propósito: permitir una transición suave a nebule.
Se verá como un síntoma de una rareza era.',
            '::sylabe:module:help:APropos:Liens' => 'Ver también :<br /><a href="http://blog.sylabe.org/">El blog del proyecto sylabe</a><br /><a href="http://blog.nebule.org/">El blog del proyecto nebule</a>',
            '::sylabe:module:help:AideGenerale:Text' => 'El software se compone de tres partes:<br />
1. el banner superior que contiene el menu de la aplicacion y la entidad actual.<br />
2. <br />
3. <br />
<br />
<br />
',
        ],
    ];
}
