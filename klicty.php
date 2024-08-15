<?php
declare(strict_types=1);
namespace Nebule\Application\Klicty;
use DateTime;
use Nebule\Library\DisplayInformation;
use Nebule\Library\nebule;
use Nebule\Library\Metrology;
use Nebule\Library\Entity;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Node;
use Nebule\Library\Translates;
use const Nebule\Bootstrap\BOOTSTRAP_NAME;

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
 * Class Application for klicty
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'klicty';
    const APPLICATION_SURNAME = 'nebule/klicty';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240815';
    const APPLICATION_LICENCE = 'GNU GPL 2015-2024';
    const APPLICATION_WEBSITE = 'www.klicty.org';
    const APPLICATION_NODE = 'd0b02052a575f63a4e87ff320df443a8b417be1b99e8e40592f8f98cbd1adc58c221d501.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = false;
    const USE_MODULES_TRANSLATE = false;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array();
    const LIST_MODULES_EXTERNAL = array();

    const APPLICATION_LICENCE_NAME = 'klicty'; // FIXME en double
    const APPLICATION_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAA
CxMAAAsTAQCanBgAAAAHdElNRQffChcUJhhjX2MDAAABq0lEQVRIx52VPzMDQRiHn7uJGZVxCjTGZBgak0ipQSuNZgspTHyD6CioKJR8AxRG8VLShkZ7SaeRQhMpRIwZhSKKu1zuXzbLW+3uu/vsb3fv
fq+FJlQWB2hLY/AcK3XhKjtsMB0aanLPuTwaAFSJUyYHbNeiItcagBqnSh591FiTTipA5XDTjxSLLgWpJQAqRw3zWO4hrED8u9HufRWOd5CMP1D1l39xw6h24QqzgEWVQqBAlbgKphzLofbbuGTbb5bk
GmwATkMzDtSJVrwdtM78rlqLvfveEEQvJtWqxysnUqaIsgcopqTMEEUPMJWaNEFMg62yA9MGCJW1cTT54QgnExu4ZYmFCAL29a/ajvR/ZJGXqAp0Ktp2zG26wLw5Qho28BYb7Bojmt4z3iW4pog7D3CR
Is0McQE2yAOtfyFa8tj7t3YjlxggZI7nGOKI76BXCTuS65vpK08hZ7IYYTMm7ZMxAFwphB1p3be0GWaGfHtjvtL1iD3IB8vwJ1PtxPxF6uTDN6D1xbzUkwaF1JkwsHYXp788rbRtcfbv0hYprsWI0ZgX
17+W91+F34OCKNnSgAAAAABJRU5ErkJggg==';
    const NEBULE_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAABrElEQVRIx52Vv0oDQRCHvztEEIKk
iEWaFKk97QUTu7zAghEEU6ROmUKfQOxiKUICghbzAumM4gNoUqcQJEVSeCJYKDmLO8/1vD+bTLVzs/Pt7M3dbyxSTJUpADMZJ++xYhOrNKhR1B5N6NOVgQFA7XPORsJxU1pykwJQ69yzRbo9sStvsQC1
yRAv/lKaeVg4MvoHUJsMMbcQYYXFuyxmeXEBVgL3Pij+nVxk4xWrmjdnhxLgccd2WIGqcx1s6PHCsZ4vkXeirqkHywO5ARuAThhfkxPOUku3w1UncFX1b9+lnYH4sQ1V9XmNaMQY0fABtf8RQ0TNBxTj
YkaIItiqnBQ1QaiyTSE5LG0uMwgFOz0uTXVBRldnGWc01WlKdGanqU1g7WSEjG1gsjRi4rexb9DxeETfB3SNvro4RBdskAHTpRBTGfzoQSv8necZiC8+Qq+lK9IjDhbwzAP6t/HJYQTiS47HULZ1Rarw
CkCJUsZFcoEUVv7Ig7g4CymiI25EX2SEA3iZqZ6uybpAISPyBtI+JP+bHjfa6nSWHm0hZI+jmOHak1vD6bzIeP8G1Zl6c0qQU/YAAAAASUVORK5CYII=';

    const APPLICATION_ENVIRONMENT_FILE = 'nebule.env';
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

    // Références de types mimes.
    const REFERENCE_OBJECT_TEXT = 'text/plain';

    // Gestion des expirations d'objets.
    const APPLICATION_EXPIRATION_DATE = 'klicty/expiration/date';
    const APPLICATION_EXPIRATION_COUNT = 'klicty/expiration/count';


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
        if ($name == '' || !is_string($name)) return false;
        $r = ''; // Va contenir le résultat à retourner.
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
            case 'klictyDisplayMetrology' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_DISPLAY_METROLOGY;
                break;
            case 'klictyDisplayUnsecureURL' :
                if ($t == 'false') $r = false; else $r = self::APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL;
                break;
            case 'klictyDisplayUnverifyLargeContent' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT;
                break;
            case 'klictyDisplayNameSize' :
                if ($t != '') $r = (int)$t; else $r = self::APPLICATION_DEFAULT_DISPLAY_NAME_SIZE;
                break;
            case 'klictyIOReadMaxDataPHP' :
                if ($t != '') $r = (int)$t; else $r = self::APPLICATION_DEFAULT_IO_READ_MAX_DATA;
                break;
            case 'klictyPermitUploadObject' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT;
                break;
            case 'klictyPermitUploadLinks' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS;
                break;
            case 'klictyPermitPublicUploadObject' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT;
                break;
            case 'klictyPermitPublicUploadLinks' :
                if ($t == 'true') $r = true; else $r = self::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS;
                break;
//			case 'klictyLogUnlockEntity' :				if ( $t == 'true' )		$r = true;		else $r = self::APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY;				break;
//			case 'klictyLogLockEntity' :				if ( $t == 'true' )		$r = true;		else $r = self::APPLICATION_DEFAULT_LOG_LOCK_ENTITY;					break;
            case 'klictyLoadModules' :
                if ($t != '') $r = $t; else $r = self::APPLICATION_DEFAULT_LOAD_MODULES;
                break;
            default :
                $r = false; // Si l'option est inconnue, retourne false.
        }
        unset($t);
        return $r;
    }


    /**
     * Lit le temps de vie de l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function getObjectLifetime(Node $object): string
    {
        return $object->getProperty(Application::APPLICATION_EXPIRATION_DATE, 'all');
    }

    /**
     * Lit le nombre de vues de l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function getObjectShowtime(Node $object): string
    {
        return $object->getProperty(Application::APPLICATION_EXPIRATION_COUNT, 'all');
    }

    /**
     * Lit si l'objet est expiré en temps de vie.
     * Un objet sans durée de vie n'expire jamais.
     *
     * @param Node $object
     * @return boolean
     */
    public function getObjectLifetimeExpired(Node $object): bool
    {
        // Liste les liens à la recherche de la propriété.
        $link = $object->getPropertyLink(Application::APPLICATION_EXPIRATION_DATE, 'all');
        if (!is_a($link, 'link')) { // FIXME la classe
            return false;
        }

        // Extrait la date du lien d'expiration.
        $objectEndDate = date_create($link->getDate());
        if (!$objectEndDate) {
            return false;
        }

        // Extrait la date courante.
        $currentDate = date_create('now');

        // Calcul la fin du temps de vie.
        switch ($this->getObjectLifetime($object)) {
            case '1h':
                date_add($objectEndDate, date_interval_create_from_date_string('1 hour'));
                break;
            case '2h':
                date_add($objectEndDate, date_interval_create_from_date_string('2 hours'));
                break;
            case '1d':
                date_add($objectEndDate, date_interval_create_from_date_string('1 days'));
                break;
            case '2d':
                date_add($objectEndDate, date_interval_create_from_date_string('2 days'));
                break;
            case '1w':
                date_add($objectEndDate, date_interval_create_from_date_string('1 week'));
                break;
            case '2w':
                date_add($objectEndDate, date_interval_create_from_date_string('2 weeks'));
                break;
            case '1m':
                date_add($objectEndDate, date_interval_create_from_date_string('1 month'));
                break;
            case '2m':
                date_add($objectEndDate, date_interval_create_from_date_string('2 months'));
                break;
            case '1y':
                date_add($objectEndDate, date_interval_create_from_date_string('1 year'));
                break;
            case '2y':
                date_add($objectEndDate, date_interval_create_from_date_string('2 years'));
                break;
        }

        if ($currentDate > $objectEndDate) {
            return true;
        }

        return false;
    }

    /**
     * Lit si l'objet a ou avait une expiration en temps de vie.
     *
     * @param Node $object
     * @return boolean
     */
    public function getObjectHaveLifetime(Node $object): bool
    {
        // Liste les liens à la recherche de la propriété.
        $link = $object->getPropertyLink(Application::APPLICATION_EXPIRATION_DATE, 'all');
        if (is_a($link, 'link')) { // FIXME la classe
            return true;
        }
        return false;
    }

    /**
     * Lit si l'objet est expiré en nombre de vues.
     *
     * @param Node $object
     * @return boolean
     */
    public function getObjectShowtimeExpired(Node $object): bool
    {
//        $count = $object->getProperty(Application::APPLICATION_EXPIRATION_COUNT, 'all'); FIXME
        return false;
    }

    /**
     * Lit le temps de vie restant de l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function getObjectLifetimeToLive(Node $object): string
    {
        // Liste les liens à la recherche de la propriété.
        $link = $object->getPropertyLink(Application::APPLICATION_EXPIRATION_DATE, 'all');
        if (!is_a($link, 'link')) { // FIXME la classe
            return '/';
        }

        // Extrait la date courante.
        $currentDate = new DateTime('now');

        // Extrait la date du lien d'expiration.
        $objectEndDate = new DateTime($link->getDate());
        if ($objectEndDate === false) {
            return '/';
        }

        // Calcul la fin du temps de vie.
        switch ($this->getObjectLifetime($object)) {
            case '1h':
                $objectEndDate->add(date_interval_create_from_date_string('1 hour'));
                break;
            case '2h':
                $objectEndDate->add(date_interval_create_from_date_string('2 hours'));
                break;
            case '1d':
                $objectEndDate->add(date_interval_create_from_date_string('1 days'));
                break;
            case '2d':
                $objectEndDate->add(date_interval_create_from_date_string('2 days'));
                break;
            case '1w':
                $objectEndDate->add(date_interval_create_from_date_string('1 week'));
                break;
            case '2w':
                $objectEndDate->add(date_interval_create_from_date_string('2 weeks'));
                break;
            case '1m':
                $objectEndDate->add(date_interval_create_from_date_string('1 month'));
                break;
            case '2m':
                $objectEndDate->add(date_interval_create_from_date_string('2 months'));
                break;
            case '1y':
                $objectEndDate->add(date_interval_create_from_date_string('1 year'));
                break;
            case '2y':
                $objectEndDate->add(date_interval_create_from_date_string('2 years'));
                break;
        }

        // Calcule le différentiel de date.
        $ttl = $objectEndDate->diff($currentDate);

        // Vérifie le différentiel de date.
        if ($objectEndDate <= $currentDate) {
            return '/';
        }

        $result = '';

        // Calcule les intervales.
        $years = $ttl->format('%y');
        $months = $ttl->format('%m');
        $days = $ttl->format('%d');
        $hours = $ttl->format('%h');
        $minutes = $ttl->format('%i');
        $seconds = $ttl->format('%s');

        // Affichage.
        if ($years != 0) {
            $result .= $years . ' ';
            if ($years > 1) $result .= $this->_translateInstance->getTranslate('::Years');
            else                $result .= $this->_translateInstance->getTranslate('::Year');
            if ($months != 0) {
                $result .= ' ' . $this->_translateInstance->getTranslate('::and') . ' ' . $months . ' ';
                if ($months > 1) $result .= $this->_translateInstance->getTranslate('::Months');
                else                $result .= $this->_translateInstance->getTranslate('::Month');
            }
        } elseif ($months != 0) {
            $result .= $months . ' ';
            if ($months > 1) $result .= $this->_translateInstance->getTranslate('::Months');
            else                $result .= $this->_translateInstance->getTranslate('::Month');
            if ($days != 0) {
                $result .= ' ' . $this->_translateInstance->getTranslate('::and') . ' ' . $days . ' ';
                if ($days > 1) $result .= $this->_translateInstance->getTranslate('::Days');
                else                $result .= $this->_translateInstance->getTranslate('::Day');
            }
        } elseif ($days != 0) {
            $result .= $days . ' ';
            if ($days > 1) $result .= $this->_translateInstance->getTranslate('::Days');
            else                $result .= $this->_translateInstance->getTranslate('::Day');
            if ($hours != 0) {
                $result .= ' ' . $this->_translateInstance->getTranslate('::and') . ' ' . $hours . ' ';
                if ($hours > 1) $result .= $this->_translateInstance->getTranslate('::Hours');
                else                $result .= $this->_translateInstance->getTranslate('::Hour');
            }
        } elseif ($hours != 0) {
            $result .= $hours . ' ';
            if ($hours > 1) $result .= $this->_translateInstance->getTranslate('::Hours');
            else                $result .= $this->_translateInstance->getTranslate('::Hour');
            if ($minutes != 0) {
                $result .= ' ' . $this->_translateInstance->getTranslate('::and') . ' ' . $minutes . ' ';
                if ($minutes > 1) $result .= $this->_translateInstance->getTranslate('::Minutes');
                else                $result .= $this->_translateInstance->getTranslate('::Minute');
            }
        } elseif ($minutes != 0) {
            $result .= $minutes . ' ';
            if ($minutes > 1) $result .= $this->_translateInstance->getTranslate('::Minutes');
            else                $result .= $this->_translateInstance->getTranslate('::Minute');
            $result .= ' ' . $this->_translateInstance->getTranslate('::and') . ' ' . $seconds . ' ';
            if ($seconds > 1) $result .= $this->_translateInstance->getTranslate('::Seconds');
            else                $result .= $this->_translateInstance->getTranslate('::Second');
        } elseif ($seconds != 0) {
            $result .= $seconds . ' ';
            if ($seconds > 1) $result .= $this->_translateInstance->getTranslate('::Seconds');
            else                $result .= $this->_translateInstance->getTranslate('::Second');
        }

        return $result;
    }


    /**
     * Lit le temps de vie limite de l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function getObjectLifetimeEnd(Node $object): string
    {
        // Liste les liens à la recherche de la propriété.
        $link = $object->getPropertyLink(Application::APPLICATION_EXPIRATION_DATE, 'all');
        if (!is_a($link, 'link')) // FIXME la classe
            return '';

        // Extrait la date du lien d'expiration.
        $objectEndDate = date_create($link->getDate());
        if (!$objectEndDate)
            return '';

        // Calcul la fin du temps de vie.
        switch ($this->getObjectLifetime($object)) {
            case '1h':
                date_add($objectEndDate, date_interval_create_from_date_string('1 hour'));
                break;
            case '2h':
                date_add($objectEndDate, date_interval_create_from_date_string('2 hours'));
                break;
            case '1d':
                date_add($objectEndDate, date_interval_create_from_date_string('1 days'));
                break;
            case '2d':
                date_add($objectEndDate, date_interval_create_from_date_string('2 days'));
                break;
            case '1w':
                date_add($objectEndDate, date_interval_create_from_date_string('1 week'));
                break;
            case '2w':
                date_add($objectEndDate, date_interval_create_from_date_string('2 weeks'));
                break;
            case '1m':
                date_add($objectEndDate, date_interval_create_from_date_string('1 month'));
                break;
            case '2m':
                date_add($objectEndDate, date_interval_create_from_date_string('2 months'));
                break;
            case '1y':
                date_add($objectEndDate, date_interval_create_from_date_string('1 year'));
                break;
            case '2y':
                date_add($objectEndDate, date_interval_create_from_date_string('2 years'));
                break;
        }

        return date_format($objectEndDate, 'c');

    }

    /**
     * Lit nombre de vues de l'objet.
     *
     * @param Node $object
     * @return integer
     */
    public function getObjectShowtimeCount(Node $object): int
    {
//        $count = $object->getProperty(Application::APPLICATION_EXPIRATION_COUNT, 'all'); FIXME
        return 0;
    }

    /**
     * Lit les entités qui ont vu l'objet.
     *
     * @param Node $object
     * @return array
     */
    public function getObjectShowtimeList(Node $object): array
    {
//        $count = $object->getProperty(Application::APPLICATION_EXPIRATION_COUNT, 'all'); FIXME
        return array();
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
    //const DEFAULT_LINK_COMMAND				= 'lnk';
    const DEFAULT_ABOUT_COMMAND = 'about';
    const DEFAULT_OBJECT_LIST_COMMAND = 'objlst';
    const DEFAULT_ENTITY_LIST_COMMAND = 'entlst';
    const DEFAULT_GROUP_LIST_COMMAND = 'grplst';
    const DEFAULT_OBJECT_ADD_COMMAND = 'objadd';
    const DEFAULT_ENTITY_ADD_COMMAND = 'entadd';
    const DEFAULT_GROUP_OBJECT_ADD_COMMAND = 'grpobjadd';
    const DEFAULT_GROUP_ENTITY_ADD_COMMAND = 'grpentadd';
    const DEFAULT_ENTITY_SYNC_COMMAND = 'entsync';
    const DEFAULT_PROTEC_COMMAND = 'prt';
    const DEFAULT_AUTH_COMMAND = 'auth';
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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";
    // Voir const DEFAULT_LOGO_WELCOME à la fin...
    const DEFAULT_APPLICATION_LOGO_LINK = '?view=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690.sha2.256';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499.sha2.256';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0.sha2.256';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2.sha2.256';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859.sha2.256';


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
        $this->_metrologyInstance->addLog('Load displays', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
        $this->_traductionInstance = $this->_applicationInstance->getTranslateInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        $this->setUrlLinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkGroupPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkConversationPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkCurrencyPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkTokenPoolPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkTokenPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkTransactionPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');
        $this->setUrlLinkWalletPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
            . '&' . References::COMMAND_SELECT_OBJECT . '=');

        $this->_findLogoApplication();
        $this->_findLogoApplicationLink();
        $this->_findLogoApplicationName();
        $this->_findCurrentDisplayView();
//        $this->_findCurrentAction();
        $this->_findInlineContentID();

        // Si en mode téléchargement d'objet ou de lien, pas de traduction.
        if ($this->_traductionInstance !== null) {
            $this->_currentDisplayLanguage = $this->_traductionInstance->getCurrentLanguage();
        }
    }


    /**
     * Revérifie le verrouillage de l'entité en cours.
     */
    public function refreshUnlocked()
    {
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------
	 *
	 * Pour l'instant, rien n'est personnalisable dans le style, mais ça viendra...
	 */
    /**
     * Variable du logo de l'application.
     * @var string
     */
    private $_logoApplication = '';

    /**
     * Variable du logo de l'application.
     * @var string
     */
    private $_logoApplicationWelcome = '';

    /**
     * Recherche le logo de l'application.
     */
    private function _findLogoApplication()
    {
        $this->_logoApplication = self::DEFAULT_APPLICATION_LOGO;
        $this->_logoApplicationWelcome = self::DEFAULT_LOGO_WELCOME;
        // A faire...
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
        // A faire...
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
        $this->_logoApplicationName = Application::APPLICATION_LICENCE_NAME;
        // A faire...
    }


    /**
     * Liste des vues disponibles.
     *
     * @var array of string
     */
    protected array $_listDisplayViews = array(
        self::DEFAULT_ABOUT_COMMAND,
        self::DEFAULT_OBJECT_LIST_COMMAND,
        self::DEFAULT_ENTITY_LIST_COMMAND,
        self::DEFAULT_GROUP_LIST_COMMAND,
        References::COMMAND_SELECT_OBJECT,
        References::COMMAND_SELECT_GROUP,
        self::DEFAULT_OBJECT_ADD_COMMAND,
        self::DEFAULT_ENTITY_ADD_COMMAND,
        self::DEFAULT_GROUP_ENTITY_ADD_COMMAND,
        self::DEFAULT_ENTITY_SYNC_COMMAND,
        self::DEFAULT_AUTH_COMMAND,
        self::DEFAULT_PROTEC_COMMAND);


    /**
     * Vérifie que toutes les icônes déclarées soient présentes.
     * Sinon les synchronises.
     */
    private function _checkDefinedIcons()
    {

    }



    /**
     * Display full page.
     */
    protected function _displayFull(): void
    {
        $this->_metrologyInstance->addLog('Display full', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log

        // Récupère l'instance de la classe 'Actions' des actions génériques, celle-ci est instanciée après celle de 'Display'.
        //$this->_actionInstance = $this->_applicationInstance->getActionInstance();

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
            $this->_metrologyInstance->addLog('Display css', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
            $this->commonCSS();
            $this->displayCSS();

            $this->_metrologyInstance->addLog('Display vbs', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
            $this->_displayScripts();
            ?>

        </head>
        <body>
        <?php
        $this->_metrologyInstance->addLog('Display actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayActions();

        $this->_metrologyInstance->addLog('Display header', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayHeader();

        $this->_metrologyInstance->addLog('Display menu apps', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayMenuApplications();
        ?>
        <div class="layout-main">
        <div class="layout-main">
            <div class="layout-content">
                <div id="curseur" class="infobulle"></div>
                <div class="content">
                    <?php
                    $this->_metrologyInstance->addLog('Display checks', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayChecks();

                    $this->_metrologyInstance->addLog('Display content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayContent();

                    $this->_metrologyInstance->addLog('Display metrology', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
                    $this->_displayMetrology();
                    ?>

                </div>
            </div>
        </div>
        <?php
        $this->_metrologyInstance->addLog('Display footer', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000'); // Log
        $this->_displayFooter();
    }

    /**
     * Display inline page.
     */
    protected function _displayInline(): void
    {
        $this->_metrologyInstance->addLog('Display inline', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log
        $this->_displayInlineContent();
    }

    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $backgroundInstance = $this->_nebuleInstance->newObject(self::DEFAULT_CSS_BACKGROUND);
        $backgroundOid = $backgroundInstance->getUpdateNID(true, false);
        ?>

        <style type="text/css">
            html, body {
                font-family: Sans-Serif, monospace, Arial, Helvetica;
                font-stretch: condensed;
                font-size: 0.9em;
                text-align: left;
            }

            @media screen {
                body {
                    background: #f0f0f0 url("<?php echo "o/$backgroundOid"; ?>") no-repeat;
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

            /* Liserets de verrouillage. */
            .headerUnlock {
                border-bottom-color: #e0000e;
            }

            .footerUnlock {
                border-top-color: #e0000e;
            }

            /* Les menus */
            .menuListContentActionDiv a:link, .menuListContentActionDiv a:visited {
                font-weight: normal;
                text-decoration: none;
            }

            .menuListContentActionDiv a:hover, .menuListContentActionDiv a:active {
                font-weight: bold;
                text-decoration: none;
            }

            .menuListContentAction {
                background: rgba(255, 255, 255, 0.5);
                height: 64px;
                margin-bottom: 5px;
                margin-right: 5px;
                text-align: left;
                color: #000000;
            }


            /* Les rubriques */
            #lang {
            }

            #recovery {
            }

            #help {
            }

            /* Spécifique à la partie authentification */
            /*.klictyModuleEntityAuthCheckOK { min-height:32px; background:rgba(0,224,14,0.5); margin:0; padding:5px; clear:both; text-align:center; }
			.klictyModuleEntityAuthCheckOK img { width:32px; height:32px; }
			.klictyModuleEntityAuthCheckWarn { min-height:32px; background:rgba(224,224,0,1); margin:0; padding:5px; clear:both; text-align:center; }
			.klictyModuleEntityAuthCheckWarn img { width:32px; height:32px; }
			.klictyModuleEntityAuthCheckError { min-height:32px; background:rgba(224,14,0,1); margin:0; padding:5px; clear:both; text-align:center; }
			.klictyModuleEntityAuthCheckError img { width:32px; height:32px; }
			#klictyModuleEntityAuthUnlock { background:rgba(255,224,14,0.6); color:#b00000; }
			#klictyModuleEntityAuthUnlock input { background:#ffffff; color:#b00000; }
			#klictyModuleEntityAuthLock   { background:rgba(255,224,14,0.6); }
			#klictyModuleEntityAuthLock p	{ color:#b00000; text-align:center; }
			.klictyInput { margin-top:2px; margin-bottom:2px; }*/

            /* Spécifique à la première page */
            #welcome {
                padding-top: 140px;
                padding-bottom: 140px;
                background: none;
                font-size: 2em;
            }

            #iconsWelcome {
                padding: 20px;
                clear: both;
                font-size: 1.2em;
                background: rgba(255, 255, 255, 0.25);
                background-origin: border-box;
                text-align: center;
                font-weight: bold;
                color: #000000;
                min-height: 80px;
            }

            #iconWelcome1 {
                position: absolute;
                left: 33%;
                width: 120px;
                margin-left: -60px;
            }

            #iconWelcome2 {
                position: absolute;
                right: 50%;
                width: 120px;
                margin-right: -60px;
            }

            #iconWelcome3 {
                position: absolute;
                right: 33%;
                width: 120px;
                margin-right: -60px;
            }

            #welcomeFooter {
                padding: 20px;
                margin-top: 120px;
            }

            #welcomeFooter p {
                padding: 20px;
                text-align: center;
                color: #000000;
            }

            #welcomeFooter a {
                font-weight: bold;
                text-decoration: none;
            }

            #welcomeFooter a:link, #welcomeFooter a:visited {
                color: #545454;
            }

            #welcomeFooter a:hover, #welcomeFooter a:active {
                color: #ababab;
            }

            @media screen and (max-height: 500px) {
                #welcomeFooter {
                    padding: 5px;
                    margin-top: 0;
                }

                #welcomeFooter p {
                    padding: 0;
                }
            }

            /* Pour les bulles d'aide. */
            .infobulle {
                position: absolute;
                visibility: hidden;
                padding: 5px;
                color: #ffffff;
                background: rgba(0, 0, 0, 0.6);
                width: 700px;
                text-align: justify;
            }

            /* Connexion */
            #klictyModuleEntityConnect {
                text-align: center;
            }
        </style>
        <?php
    }

    /**
     * Affichage des scripts JS.
     */
    protected function _displayScripts()
    {
        $this->commonScripts();
        /* ?>

		<script language="javascript" type="text/javascript">
			<!--
			var i=false; var j=true;
			function GetId(id) { return document.getElementById(id); }
			function move(e)
			{
				if( i && j )
				{
					if ( navigator.appName!="Microsoft Internet Explorer" )
					{
						GetId("curseur").style.left=e.pageX - 720+"px";
						GetId("curseur").style.top=e.pageY + 10+"px";
					}
					else
					{
						if( document.documentElement.clientWidth > 0 )
						{
							GetId("curseur").style.left=event.x+document.documentElement.scrollLeft-720+"px";
							GetId("curseur").style.top=10+event.y+document.documentElement.scrollTop+"px";
						}
						else
						{
							GetId("curseur").style.left=event.x+document.body.scrollLeft-710+"px";
							GetId("curseur").style.top=10+event.y+document.body.scrollTop+"px";
						}
					}
					j=false;
				}
			}
			function montre(text)
			{
				if( i==false )
				{
					GetId("curseur").style.visibility="visible";
					GetId("curseur").innerHTML = text;
					i=true;
				}
			}
			function cache()
			{
				if( i==true )
				{
					GetId("curseur").style.visibility="hidden";
					i=false;
					j=true;
				}
			}
			document.onmouseover=move;

			ico_lock_off = new Image(64,64);
			ico_lock_off.src = "/<?php	$objet = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ENTITY_LOCK);
										$newobj = $objet->findUpdate(true, false);
										echo nebule::NEBULE_LOCAL_OBJECTS_FOLDER.'/'.$newobj; ?>";
			ico_lock_on = new Image(64,64);
			ico_lock_on.src = "/<?php	$objet = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ENT);
										$newobj = $objet->findUpdate(true, false);
										echo nebule::NEBULE_LOCAL_OBJECTS_FOLDER.'/'.$newobj; ?>";
			function hiLite(imgDocID, imgObjName, comment)
			{
				document.images[imgDocID].src = eval(imgObjName + ".src");
				window.status = comment;
				return true;
			}

			function display_menu(menuid)
			{
				var test = document.getElementById(menuid).style.display;
				if (test == "block")
				{
					document.getElementById(menuid).style.display = "none";
				}
				else
				{
					document.getElementById(menuid).style.display = "block";
				}
			}
			//-->
		</script>
<?php */
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
                }
                ?>

            </p>
        </div>
        <?php
    }

    private function _displayInlineActions()
    {
        ?>

        <div class="inlineaction">
            <p>
                <?php
                // Vérifie le ticket.
                if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
                    // Appelle les actions génériques.
                    $this->_actionInstance->genericActions();
                }
                ?>

            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la barre supérieure.
     *
     * La partie gauche présente le menu et l'entité déverrouillée ou des icônes de navigation hors connexion.
     *
     * La partie droite présente :
     * - Un avertissement s'il y a un problème. En mode rescue, on peut quand même verrouiller/déverrouiller l'entité.
     * - Une erreur s'il y a un gros problème. Il n'est pas possible de déverrouiller l'entité.
     */
    private function _displayHeader()
    {
        ?>

        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <div class="header-left">
                <img src="<?php echo $this->_logoApplication; ?>" alt="[K]"
                     title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"
                     onclick="display_menu('layout-menu-applications');"/>
            </div>
            <?php
            // Si l'entité n'est en cours n'est pas l'entité par défaut.
            if ($this->_nebuleInstance->getCurrentEntity() != $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity')) {
                // Affiche l'entité et son image.
                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
//                    'enableDisplayRefs' => false,
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
                    'enableDisplayRefs' => true,
                    'enableDisplayJS' => false,
                    'enableDisplayObjectActions' => false,
                    'objectRefs' => $this->_nebuleInstance->getListEntitiesUnlockedInstances(),
                );
                if ($this->_unlocked) {
                    $param['flagUnlockedLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth'
                        . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                        . '&' . References::COMMAND_FLUSH;
                } else {
                    $param['flagUnlockedLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth'
                        . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity();
                }
                echo $this->getDisplayObject_DEPRECATED($this->_nebuleInstance->getCurrentEntityInstance(), $param);
            } else {
                ?>

                <div class="header-left2">
                    <?php
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LSTENT),
                            '::EntitiesList'),
                        '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ENTITY_LIST_COMMAND);
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ADDENT),
                            '::EntityAdd'),
                        '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ENTITY_ADD_COMMAND);
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            $this->_nebuleInstance->newObject(self::DEFAULT_ICON_WORLD),
                            ':::SelectLanguage'),
                        '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ABOUT_COMMAND . '#lang');
                    ?>

                </div>
                <?php
            }
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
                                '?' . Display::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth'
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
                                '?' . Display::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth'
                                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity());
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
                echo ' : ' . Application::APPLICATION_VERSION . ' ' . $this->_configurationInstance->getOptionAsString('codeBranch'); ?><br/>
                <a href="<?php echo $linkApplicationWebsite; ?>" target="_blank"><?php echo Application::APPLICATION_WEBSITE; ?></a>
            </div>
            <div class="menu-applications-logo">
                <img src="<?php echo $this->_logoApplication; ?>" alt="[K]"
                     title="<?php echo $this->_traductionInstance->getTranslate('::menu'); ?>"
                     onclick="display_menu('layout-menu-applications');"/>
            </div>
            <div class="menu-applications">
                <?php
                if ($this->_unlocked) {
                    $modulesName = array(
                        '::ShowObjectsOf',
                        '::ObjectAdd',
                        '::EntitiesList',
                        '::EntitiesGroupList',
                        '::EntitiesGroupAdd',
                        '::Help',
                        ':::SelectLanguage',
                        '::::lock');
                    $modulesIcon = array(
                        self::DEFAULT_ICON_LSTOBJ,
                        self::DEFAULT_ICON_ADDOBJ,
                        self::DEFAULT_ICON_LSTENT,
                        self::DEFAULT_ICON_GRPENT,
                        self::DEFAULT_ICON_GRPENTADD,
                        self::DEFAULT_ICON_HELP,
                        self::DEFAULT_ICON_WORLD,
                        self::DEFAULT_ICON_ENTITY_LOCK);
                    $modulesLink = array(
                        self::DEFAULT_OBJECT_LIST_COMMAND . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity(),
                        self::DEFAULT_OBJECT_ADD_COMMAND,
                        self::DEFAULT_ENTITY_LIST_COMMAND,
                        self::DEFAULT_GROUP_LIST_COMMAND . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity(),
                        self::DEFAULT_GROUP_ENTITY_ADD_COMMAND,
                        self::DEFAULT_ABOUT_COMMAND . '#help',
                        self::DEFAULT_ABOUT_COMMAND . '#lang',
                        self::DEFAULT_ABOUT_COMMAND . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT . '&' . References::COMMAND_FLUSH);
                } else {
                    $modulesName = array(
                        '::ObjectsList',
                        '::EntitiesList',
                        '::EntitySync',
                        '::EntityAdd',
                        ':::Flush',
                        '::Help',
                        ':::SelectLanguage',
                    );
                    $modulesIcon = array(
                        self::DEFAULT_ICON_LSTOBJ,
                        self::DEFAULT_ICON_LSTENT,
                        self::DEFAULT_ICON_SYNENT,
                        self::DEFAULT_ICON_ADDENT,
                        self::DEFAULT_ICON_FLUSH,
                        self::DEFAULT_ICON_HELP,
                        self::DEFAULT_ICON_WORLD,
                    );
                    $modulesLink = array(
                        self::DEFAULT_OBJECT_LIST_COMMAND . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity(),
                        self::DEFAULT_ENTITY_LIST_COMMAND,
                        self::DEFAULT_ENTITY_SYNC_COMMAND,
                        self::DEFAULT_ENTITY_ADD_COMMAND,
                        self::DEFAULT_ABOUT_COMMAND . '&' . References::COMMAND_FLUSH,
                        self::DEFAULT_ABOUT_COMMAND . '#help',
                        self::DEFAULT_ABOUT_COMMAND . '#lang',
                    );
                }

                foreach ($modulesName as $i => $module) {
                    $name = $this->_applicationInstance->getTranslateInstance()->getTranslate($module);
                    $icon = $this->_nebuleInstance->newObject($modulesIcon[$i]);
                    $link = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $modulesLink[$i];
                    ?>

                    <div class="menu-applications-one">
                        <div class="menu-applications-icon">
                            <?php $this->displayHypertextLink($this->convertUpdateImage($icon, $name), $link); ?>
                        </div>
                        <div class="menu-applications-title">
                            <p><?php $this->displayHypertextLink($name, $link); ?></p>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="menu-applications-one">
                    <div class="menu-applications-icon">
                        <?php $this->displayHypertextLink('<img src="' . parent::DEFAULT_APPLICATION_LOGO . '" alt="' . BOOTSTRAP_NAME . '" />', '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP); ?>

                    </div>
                    <div class="menu-applications-title">
                        <p><?php echo BOOTSTRAP_NAME; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    /**
     * Affiche les alertes.
     */
    private function _displayChecks()
    {
        if ($this->_nebuleInstance->getModeRescue()) {
            $this->displayMessageWarning_DEPRECATED('::::RESCUE');
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR') {
            $this->displayMessageError_DEPRECATED($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN') {
            $this->displayMessageWarning_DEPRECATED($this->_applicationInstance->getCheckSecurityURLMessage());
        }
        if (!$this->_configurationInstance->getOptionAsBoolean('permitWrite')) {
            $this->displayMessageWarning_DEPRECATED('::::warn_ServNotPermitWrite');
        }
        if ($this->_nebuleInstance->getFlushCache()) {
            $this->displayMessageWarning_DEPRECATED('::::warn_flushSessionAndCache');
        }
    }


    /**
     * Contenu de la page.
     *
     * Affiche le contenu des pages en fonction du mode demandé.
     * Un seul type de vue est pris en compte pour l'affichage, les autres sont ignorés.
     *
     * Chaque page peut faire appel à des contenus en ligne gérés par _displayInlineContent.
     */
    private function _displayContent()
    {
        switch ($this->_currentDisplayView) {
//            case self::DEFAULT_ABOUT_COMMAND :
//                $this->_displayContentAbout();
//                break;
            case self::DEFAULT_OBJECT_LIST_COMMAND :
                $this->_displayContentObjectList();
                break;
            case self::DEFAULT_ENTITY_LIST_COMMAND :
                $this->_displayContentEntityList();
                break;
            case self::DEFAULT_GROUP_LIST_COMMAND :
                $this->_displayContentEntityGroupList();
                break;
            case References::COMMAND_SELECT_OBJECT :
                $this->_displayContentObject();
                break;
            case self::DEFAULT_OBJECT_ADD_COMMAND :
                $this->_displayContentObjectAdd();
                break;
            case self::DEFAULT_ENTITY_ADD_COMMAND :
                $this->_displayContentEntityAdd();
                break;
            case self::DEFAULT_GROUP_ENTITY_ADD_COMMAND :
                $this->_displayContentEntityGroupAdd();
                break;
            case self::DEFAULT_ENTITY_SYNC_COMMAND :
                $this->_displayContentEntitySync();
                break;
            case self::DEFAULT_PROTEC_COMMAND :
                $this->_displayContentProtection();
                break;
            case self::DEFAULT_AUTH_COMMAND :
                $this->_displayContentAuth();
                break;
            default:
                $this->_displayContentAbout();
                break;
        }

        // Lance l'affichage des contenus inline.
        $this->_displayInlineContentID();
    }

    /**
     * Contenu en ligne de la page.
     *
     * Affiche le contenu des pages en fonction du mode demandé.
     * Un seul type de vue est pris en compte pour l'affichage, les autres sont ignorés.
     *
     * Ces contenus sont 'en ligne', c'est-à-dire sans fenêtrage. Ils ne sont pas destinés à être appellés seuls.
     */
    private function _displayInlineContent()
    {
        switch ($this->_currentDisplayView) {
//            case self::DEFAULT_ABOUT_COMMAND :
//                $this->_displayInlineContentAbout();
//                break;
            case self::DEFAULT_OBJECT_LIST_COMMAND :
                $this->_displayInlineContentObjectList();
                break;
            case self::DEFAULT_ENTITY_LIST_COMMAND :
                $this->_displayInlineContentEntityList();
                break;
            case self::DEFAULT_GROUP_LIST_COMMAND :
                $this->_displayInlineContentEntityGroupList();
                break;
            case References::COMMAND_SELECT_OBJECT :
                $this->_displayInlineContentObject();
                break;
            case self::DEFAULT_OBJECT_ADD_COMMAND :
                $this->_displayInlineContentObjectAdd();
                break;
            case self::DEFAULT_ENTITY_SYNC_COMMAND :
                $this->_displayInlineContentEntitySync();
                break;
            case self::DEFAULT_PROTEC_COMMAND :
                $this->_displayInlineContentProtection();
                break;
            default:
                $this->_displayInlineContentAbout();
                break;
        }

        // Lance l'affichage des contenus inline.
        //$this->_displayInlineContentID();
    }


    private function _displayContentObjectList()
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LSTOBJ);
        echo $this->getDisplayTitle_DEPRECATED('::ObjectList', $icon);

        // Appel l'affichage en ligne.
        $this->registerInlineContentID('objectlist');
    }

    private function _displayInlineContentObjectList()
    {
        echo '<div class="layoutObjectsList">' . "\n";
        echo '<div class="objectsListContent">' . "\n";

        // Extrait la liste des objets.
        $list = array();
        $DisplayedList = array();
        $id = $this->_applicationInstance->getCurrentEntityID();
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(Application::APPLICATION_EXPIRATION_DATE);
        $instance = $this->_nebuleInstance->newObject($meta);

        // Si c'est l'entité du serveur, affiche tous les objets.
        if ($id == $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity')) {
            $list = $instance->getLinksOnFields('', '', 'l', '', '', $meta);
        } // Sinon affiche les objets de l'entité.
        else {
            $list = $instance->getLinksOnFields($id, '', 'l', '', '', $meta);
        }

        // Fait un pré-tri.
        foreach ($list as $i => $item) {
            $id2 = $item->getParsed()['bl/rl/nid1'];
            $instance = $this->_nebuleInstance->newObject($id2);
            $lifetimeExpired = $this->_applicationInstance->getObjectLifetimeExpired($instance);
            if ($lifetimeExpired) {
                unset($list[$i]);
            }
        }

        // Liste tous les objets.
        foreach ($list as $item) {
            $id = $item->getParsed()['bl/rl/nid1'];
            $entity = $item->getParsed()['bs/rs1/eid'];
            $instance = $this->_nebuleInstance->newObject($id);
            $id = $instance->getID();
            $present = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($id);
            $ban = $instance->getMarkDanger(); // @todo
            $warn = $instance->getMarkWarning(); // @todo
            $lifetimeExpired = $this->_applicationInstance->getObjectLifetimeExpired($instance);
            $haveLifetime = $this->_applicationInstance->getObjectHaveLifetime($instance);
            if (!$lifetimeExpired) {
                $lifetime = $this->_applicationInstance->getTranslateInstance()->getTranslate('::ExpireIn')
                    . ' ' . $this->_applicationInstance->getObjectLifetimeToLive($instance);
            }

            // Si l'objet n'est pas expiré.
            if (!$lifetimeExpired && $haveLifetime && $DisplayedList[$id] !== true) {
                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayFlagProtection' => true,
                    'flagProtection' => $instance->getMarkProtected(),
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                    'status' => $this->_applicationInstance->getTranslateInstance()->getTranslate($instance->getType('all')),
                    'flagMessage' => $lifetime,
                );
                $param['objectRefs'][0] = $entity;
                echo $this->getDisplayObject_DEPRECATED($instance, $param);
            }
            $DisplayedList[$id] = true;
        }

        // Refait une recherche et un affichage pour les objets protégés partagés.
        $list = array();
        $id = $this->_applicationInstance->getCurrentEntityID();
        if ($id != $this->_configurationInstance->getOptionUntyped('defaultCurrentEntity')) {
            $listK = $this->_applicationInstance->getCurrentEntityInstance()->readLinksFilterFull(
                '',
                '',
                'k',
                '',
                '',
                $id
            );
            $listS = array();

            // Fait un pré-tri.
            if (sizeof($listK) != 0) {
                foreach ($listK as $i => $item) {
                    // Vérifie le signataire.
                    $signer = $item->getParsed()['bs/rs1/eid'];
                    if ($signer != $id) {
                        // Recherche du lien de chiffrement des objets.
                        $source = $item->getParsed()['bl/rl/nid1'];
                        $instance = $this->_nebuleInstance->newObject($source);
                        $list2 = $instance->getLinksOnFields(
                            '',
                            '',
                            'k',
                            '',
                            '',
                            $source
                        );

                        // Extrait l'objet protégé.
                        if (sizeof($list2) != 0) {
                            $source = $list2[0]->getParsed()['bl/rl/nid1'];
                            $instance = $this->_nebuleInstance->newObject($source);
                            $lifetimeExpired = $this->_applicationInstance->getObjectLifetimeExpired($instance);
                            $haveLifetime = $this->_applicationInstance->getObjectHaveLifetime($instance);
                            // Si l'objet n'est pas expiré.
                            if (!$lifetimeExpired
                                && $haveLifetime
                                && $DisplayedList[$source] !== true
                            ) {
                                // Valide l'objet partagé.
                                $listS[$source] = $signer;
                                $list[] = $source;
                            }
                        }
                    }
                }
            }

            foreach ($list as $item) {
                // Extrait et affiche l'objet protégé.
                $entity = $listS[$item];
                $instance = $this->_nebuleInstance->newObject($item);
                $id = $instance->getID();
                $lifetimeExpired = $this->_applicationInstance->getObjectLifetimeExpired($instance);
                $haveLifetime = $this->_applicationInstance->getObjectHaveLifetime($instance);
                if (!$lifetimeExpired) {
                    $lifetime = $this->_applicationInstance->getTranslateInstance()->getTranslate('::ExpireIn')
                        . ' ' . $this->_applicationInstance->getObjectLifetimeToLive($instance);
                }

                // Si l'objet n'est pas expiré.
                if (!$lifetimeExpired && $haveLifetime && $DisplayedList[$id] !== true) {
                    $param = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => true,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayFlagProtection' => true,
                        'flagProtection' => $instance->getMarkProtected(),
                        'enableDisplayStatus' => true,
                        'enableDisplayContent' => false,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                        'enableDisplayJS' => false,
                        'status' => $this->_applicationInstance->getTranslateInstance()->getTranslate($instance->getType('all')),
                        'flagMessage' => $lifetime,
                    );
                    $param['objectRefs'][0] = $entity;
                    echo $this->getDisplayObject_DEPRECATED($instance, $param);
                }
                $DisplayedList[$id] = true;
            }
        }

        // Messages en fin de liste ou si liste vide.
        if (sizeof($DisplayedList) == 0) {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'information',
            );
            echo $this->getDisplayInformation_DEPRECATED('::NoFile', $param);
        } elseif (!$this->_unlocked) {
            // Si pas déverrouillé, affiche un message.
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'warn',
            );
            echo $this->getDisplayInformation_DEPRECATED('::AllNotDisplayed', $param);
        }

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }

    private function _displayContentEntityList()
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LSTENT);
        echo $this->getDisplayTitle_DEPRECATED('::EntitiesList', $icon);

        // Appel l'affichage en ligne.
        $this->registerInlineContentID('listentities');
    }

    private function _displayInlineContentEntityList()
    {
        echo '<div class="layoutObjectsList">' . "\n";
        echo '<div class="objectsListContent">' . "\n";

        $hashType = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
        $hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
        $hashEntityObject = $this->_nebuleInstance->newObject($hashEntity);

        // Liste des entités à ne pas afficher.
        $listOkEntities = $this->_nebuleInstance->getSpecialEntities();
        if ($this->_unlocked) {
            $listOkEntities[$this->_applicationInstance->getCurrentEntityID()] = true;
        }

        // Liste toutes les autres entités.
        $links = $hashEntityObject->getLinksOnFields(
            '',
            '',
            'l',
            '',
            $hashEntity,
            $hashType
        );

        // Affiche les entités.
        foreach ($links as $link) {
            $id = $link->getParsed()['bl/rl/nid1'];
            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => false,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                );
                $param['selfHookList'] = array();

                // Se connecter avec cette entité.
                $param['selfHookList'][0]['name'] = '::ConnectWith';
                $param['selfHookList'][0]['icon'] = self::DEFAULT_ICON_ENTITY_LOCK;
                $param['selfHookList'][0]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_SWITCH_TO_ENTITY
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;

                echo $this->getDisplayObject_DEPRECATED($instance, $param);

                // Marque comme vu.
                $listOkEntities[$id] = true;
            }
        }

        if (sizeof($listOkEntities) == 0) {
            // Pas d'entité.
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'information',
            );
            echo $this->getDisplayInformation_DEPRECATED('::NoEntity', $param);
        }

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }

    private function _displayContentEntityGroupList()
    {
        // Titre.
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_GRPENT);
        echo $this->getDisplayTitle_DEPRECATED('::EntitiesGroupList', $icon);

        // Appel l'affichage en ligne.
        $this->registerInlineContentID('listgroups');
    }

    private function _displayInlineContentEntityGroupList()
    {
        echo '<div class="layoutObjectsList">' . "\n";
        echo '<div class="objectsListContent">' . "\n";

        // Liste des groupes à ne pas afficher.
        $listOkGroups = array();

        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID($this->_applicationInstance->getCurrentEntityID(), '');

        // Affiche les groupes.
        foreach ($listGroups as $id) {
            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkGroups[$id])) {
                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => false,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                );
                $param['selfHookList'] = array();

                // Supprimer ce groupe.
                if ($this->_unlocked
                    && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                    && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                ) {
                    $param['selfHookList'][0]['name'] = '::DeleteGroup';
                    $param['selfHookList'][0]['icon'] = self::DEFAULT_ICON_LX;
                    $param['selfHookList'][0]['link'] = '?'
                        . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_GROUP_LIST_COMMAND
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_GROUP . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                echo $this->getDisplayObject_DEPRECATED($instance, $param);

                // Marque comme vu.
                $listOkGroups[$id] = true;
            }
        }

        if (sizeof($listOkGroups) == 0) {
            // Pas d'entité.
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'informationType' => 'information',
            );
            echo $this->getDisplayInformation_DEPRECATED('::NoEntityGroup', $param);
        }

        echo '</div>' . "\n";
        echo '</div>' . "\n";
        unset($listGroups, $listOkGroups);
    }

    private function _displayContentEntityGroupAdd()
    {
        // Si un groupe a été créé.
        if ($this->_actionInstance->getCreateGroup()) {
            $createGroupID = $this->_actionInstance->getCreateGroupInstance();
            $createGroupInstance = $this->_actionInstance->getCreateGroupInstance();
            $createGroupError = $this->_actionInstance->getCreateGroupError();
            $createGroupErrorMessage = $this->_actionInstance->getCreateGroupErrorMessage();

            // Si la création a réussi.
            if (!$createGroupError
                && is_a($createGroupInstance, 'Group') // FIXME la classe
            ) {
                // Affiche le titre.
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_GRPENT);
                echo $this->getDisplayTitle_DEPRECATED('::CreatedGroup', $icon);

                // Affiche la nouvelle entité.
                echo '<div class="layoutObjectsList">' . "\n";
                echo '<div class="objectsListContent">' . "\n";

                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => false,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                );
                $param['selfHookList'] = array();
                echo $this->getDisplayObject_DEPRECATED($createGroupID, $param);

                echo '</div>' . "\n";
                echo '</div>' . "\n";
            } else {
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'error',
                    'displayRatio' => 'long',
                );
                echo $this->getDisplayInformation_DEPRECATED('::NOKCreateGroup', $param);
            }
        } else {
            // Affiche le titre.
            $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_GRPENTADD);
            echo $this->getDisplayTitle_DEPRECATED('::EntitiesGroupAdd', $icon);

            // Si autorisé à créer un groupe.
            if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                && $this->_unlocked
            ) {
                ?>

                <div class="text">
                    <p>


                    <form method="post"
                          action="?<?php echo self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_GROUP_ENTITY_ADD_COMMAND .
                              '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <div class="floatRight textAlignRight">
                            <input type="checkbox"
                                   name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP_CLOSED; ?>"
                                   value="y" checked>
                            <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::GroupeFerme'); ?>
                        </div>
                        <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Nom'); ?>
                        <input type="text"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP_NAME; ?>"
                               size="20" value="" class="klictyModuleEntityInput"><br/>
                        <input type="submit"
                               value="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::CreateTheGroup'); ?>"
                               class="klictyModuleEntityInput">
                    </form>
                    <?php

                    ?>

                    </p>
                </div>
                <?php
            } else {
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'error',
                    'displayRatio' => 'long',
                );
                echo $this->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
            }
        }
    }

    private function _displayContentObject()
    {
        echo '<div class="layoutObjectsList">' . "\n";
        echo '<div class="objectsListContent">' . "\n";

        $instance = $this->_nebuleInstance->convertIdToTypedObjectInstance($this->_applicationInstance->getCurrentObjectID());
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $instance->getMarkProtected(),
            'enableDisplayStatus' => true,
            'enableDisplayContent' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'status' => $this->_applicationInstance->getTranslateInstance()->getTranslate($instance->getType('all')),
            'enableDisplayJS' => true,
        );
        echo $this->getDisplayObject_DEPRECATED($instance, $param);

        echo '</div>' . "\n";
        echo '</div>' . "\n";

        return;

        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($this->_applicationInstance->getCurrentObjectInstance());

        // Affichage de l'entête.
        $this->displayObjectDivHeaderH1($object);

        // Affiche les émotions.
        $this->displayInlineEmotions($this->_applicationInstance->getCurrentObjectID());

        // Affiche le contenu en ligne.
        $this->registerInlineContentID('objectcontent');
    }

    private function _displayInlineContentObject()
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($this->_applicationInstance->getCurrentObjectID());

        $isGroup = $object->getIsGroup('myself');

        // Affichage les actions possibles.
        $actionList = array();
        $id = $object->getID();
        $isEntity = $object->getIsEntity('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) { // FIXME la classe
            $object = $this->_nebuleInstance->newEntity($object->getID());
        }
        if ($isGroup && !is_a($object, 'Group')) { // FIXME la classe
            $object = $this->_nebuleInstance->newGroup($object->getID());
        }

        // Détermine si l'objet est protégé.
        $protected = $object->getMarkProtected();

        // Affiche le menu des actions.
        if ($isGroup) {
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
            ) {
                // Supprimer le groupe.
                $actionList[0]['name'] = '::DeleteGroup';
                $actionList[0]['icon'] = self::DEFAULT_ICON_LX;
                $actionList[0]['desc'] = '';
                $actionList[0]['css'] = 'oneAction-bg-warn';
                $actionList[0]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID()
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_GROUP . '=' . $this->_applicationInstance->getCurrentObjectID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }
        }
        if ($isEntity) {
            // Se connecter avec l'entité.
            if (!$this->_unlocked || $id != $this->_nebuleInstance->getCurrentEntity()) {
                $actionList[1]['name'] = '::ConnectWith';
                $actionList[1]['icon'] = self::DEFAULT_ICON_ENTITY_LOCK;
                $actionList[1]['desc'] = '';
                $actionList[1]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT . '&' . References::COMMAND_SWITCH_TO_ENTITY
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;
            }

            if ($this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink') && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')) {
                // Synchroniser l'entité.
                $actionList[2]['name'] = '::SynchronizeEntity';
                $actionList[2]['icon'] = self::DEFAULT_ICON_SYNENT;
                $actionList[2]['desc'] = '';
                $actionList[2]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY . '=' . $id
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }

            if ($this->_unlocked && $id == $this->_nebuleInstance->getCurrentEntity()) {
                // Verrouiller l'entité.
                $actionList[3]['name'] = '::::lock';
                $actionList[3]['icon'] = self::DEFAULT_ICON_ENTITY_LOCK;
                $actionList[3]['desc'] = '';
                $actionList[3]['css'] = 'oneAction-bg-warn';
                $actionList[3]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ABOUT_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT . '&' . References::COMMAND_FLUSH;
            }

            // Voir les objets.
            $actionList[4]['name'] = '::ShowObjectsOf';
            $actionList[4]['icon'] = self::DEFAULT_ICON_LSTOBJ;
            $actionList[4]['desc'] = '';
            $actionList[4]['link'] = '?'
                . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_OBJECT_LIST_COMMAND
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;

            // Voir les groupes.
            $actionList[5]['name'] = '::EntitiesGroupList';
            $actionList[5]['icon'] = self::DEFAULT_ICON_GRPENT;
            $actionList[5]['desc'] = '';
            $actionList[5]['link'] = '?'
                . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_GROUP_LIST_COMMAND
                . '&' . References::COMMAND_SELECT_ENTITY . '=' . $id;
        }
        if (!$isEntity
            && !$isGroup
        ) {
            // Si le contenu de l'objet est présent.
            if ($object->checkPresent()) {
                // Télécharger l'objet.
                $actionList[0]['name'] = '::DownloadObject';
                $actionList[0]['icon'] = self::DEFAULT_ICON_IDOWNLOAD;
                $actionList[0]['desc'] = '';
                $actionList[0]['link'] = '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $id;

                // Si l'entité est déverrouillée ou que l'objet est protégé.
                if ($this->_unlocked
                    || $protected
                ) {
                    // Voir la protection de l'objet.
                    $actionList[1]['name'] = '::ProtectionOfObject';
                    $actionList[1]['icon'] = self::DEFAULT_ICON_LK;
                    $actionList[1]['desc'] = '';
                    $actionList[1]['link'] = '?'
                        . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id;
                }

                // Si l'entité est déverrouillée.
                if ($this->_unlocked) {
                    // Supprimer l'objet.
                    $actionList[2]['name'] = '::DeleteObject';
                    $actionList[2]['icon'] = self::DEFAULT_ICON_LD;
                    $actionList[2]['desc'] = '';
                    $actionList[2]['css'] = 'oneAction-bg-warn';
                    $actionList[2]['link'] = '?'
                        . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID()
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                    // Renouveler le bail de durée de vie.
                    $link = $object->getPropertyLink(Application::APPLICATION_EXPIRATION_DATE, 'all');
                    if (is_a($link, 'Link')) { // FIXME la classe
                        $source = $link->getParsed()['bl/rl/nid1'];
                        $target = $link->getParsed()['bl/rl/nid2'];
                        $meta = $link->getParsed()['bl/rl/nid3'];
                        unset($link);
                        $actionList[3]['name'] = '::RenewLiveTimeFile';
                        $actionList[3]['icon'] = self::DEFAULT_ICON_SYNLNK;
                        $actionList[3]['desc'] = '';
                        $actionList[3]['link'] = '?'
                            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID()
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $source . '_' . $target . '_' . $meta
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }
                }
            }
        }
        $this->displayActionList($actionList);
        unset($actionList);

        // Affiche le contenu de l'objet.
        $this->displayObjectContentFull($object);

        // Affiche un complément d'information en fonction du type d'objet.
        if ($isEntity) {
            // Liste des groupes fermés dont l'objet est membre.
            $listGroupsMember = $this->_nebuleInstance->getListGroupsID($this->_applicationInstance->getCurrentEntityID(), '');
            $listOkGroups = array();
            $i = 0;

            // Affiche l'ajout à un groupe.
            if (sizeof($listGroupsMember) != 0) {
                ?>

                <div class="sequence"></div>
                <?php $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_GRPENT); $this->displayDivTextTitleH2($icon, '::GroupList'); ?>
                <div class="text">
                    <p>
                    <form method="post"
                          action="?<?php echo self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                              . '&' . References::COMMAND_SELECT_OBJECT . '=' . $object
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <input type="submit"
                               value="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::AddToGroup'); ?>"
                               class="klictyModuleEntityInput">
                        <select
                                name="<?php echo Actions::DEFAULT_COMMAND_ACTION_ADD_TO_GROUP; ?>"
                                class="klictyModuleEntityInput">
                            <?php
                            foreach ($listGroupsMember as $group) {
                                $instance = $this->_nebuleInstance->newGroup($group);
                                echo '<option value="' . $group . '">' . $instance->getFullName('myself') . "</option>\n";
                            }
                            unset($instance);
                            ?>

                        </select>
                    </form>
                    </p>
                </div>
                <?php
            }

            // Prépare l'affichage des groupes.
            if (sizeof($listGroupsMember) != 0) {
                $list = array();
                foreach ($listGroupsMember as $group) {
                    if (!isset($listOkGroups[$group])) {
                        $instance = $this->_nebuleInstance->newGroup($group);

                        // Si c'est un grupe, l'affiche.
                        if ($instance->getIsGroup('myself')) {
                            $list[$i]['object'] = $instance;
                            $list[$i]['entity'] = '';
                            $list[$i]['icon'] = '';
                            $list[$i]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $group;
                            $list[$i]['desc'] = '';
                            $list[$i]['actions'] = array();

                            // Retirer l'objet du groupe.
                            if ($this->_unlocked
                                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                                && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                            ) {
                                $list[$i]['actions'][0]['name'] = '::RemoveFromGroup';
                                $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID()
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_FROM_GROUP . '=' . $group
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }
                        }

                        // Marque comme vu.
                        $listOkGroups[$group] = true;
                        $i++;
                    }
                }
                unset($group, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->displayMessageInformation_DEPRECATED('::NoGroupMember');
            }
            unset($listGroupsMember, $listOkGroups);

            // Affiche la possibilité d'ajouter l'entité à un groupe.
        }
        if ($isGroup) {
            // Liste tous les groupes.
            $groupListID = $object->getListMembersID('self', null);

            //Prépare l'affichage.
            if (sizeof($groupListID) != 0) {
                $list = array();
                $listOkItems = array($this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE) => true,
                    $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME) => true);
                $i = 0;
                foreach ($groupListID as $item) {
                    if (!isset($listOkItems[$item])) {
                        $instance = $this->_nebuleInstance->convertIdToTypedObjectInstance($item);

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $item;
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteGroup')
                        ) {
                            $list[$i]['actions'][0]['name'] = '::RemoveFromGroup';
                            $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObjectID()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP . '=' . $item
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }

                        // Marque comme vu.
                        $listOkItems[$item] = true;
                        $i++;
                    }
                }
                unset($item, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->displayMessageInformation_DEPRECATED('::NoEntityGroup');
            }
            unset($groupListID, $listOkItems);
        }
        if (!$isEntity && !$isGroup) {
            // Prépare l'affichage des contraintes de temps et de vues.
            $lifetimeString = $this->_applicationInstance->getTranslateInstance()->getTranslate('::LiveTime') . ' : ';
            $showtimeString = $this->_applicationInstance->getTranslateInstance()->getTranslate('::ShowTime') . ' : ';
            $lifetimeExpired = $this->_applicationInstance->getObjectLifetimeExpired($object);
            $showtimeExpired = $this->_applicationInstance->getObjectShowtimeExpired($object);

            // Affiche les attributs de temps de vie et nombre de vues.
            $lifetime = '';
            if (!$lifetimeExpired) {
                $lifetime = $this->_applicationInstance->getObjectLifetime($object);
                $lifetimeToLive = $this->_applicationInstance->getObjectLifetimeToLive($object);
                $lifetimeEnd = $this->_applicationInstance->getObjectLifetimeEnd($object);
                if ($lifetime != '') {
                    $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::' . $lifetime);
                    $lifetimeString .= '.<br />';
                    $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::ExpireIn') . ' ';
                    $lifetimeString .= '<b>' . $lifetimeToLive . '</b>.<br />';
                    $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::Limit') . ' : ';
                    $lifetimeString .= $lifetimeEnd . ".<br />\n";
                } else {
                    $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::Unlimited');
                }
            } else {
                $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::Expired');
            }

            // Émetteurs.
            if ($lifetime != '') {
                $lifetimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::AddBy') . ' : ';
                $links = $object->getPropertiesLinks(Application::APPLICATION_EXPIRATION_DATE, 'all');
                $signers = array();
                foreach ($links as $link) {
                    $signers[$link->getParsed()['bs/rs1/eid']] = $link->getParsed()['bs/rs1/eid'];
                }
                unset($links, $link);
                $f = false;
                foreach ($signers as $signer) {
                    if ($f) {
                        $lifetimeString .= ', ';
                    }
                    $f = true;
                    $lifetimeString .= $this->convertInlineObjectColorIconName($signer);
                }
                unset($signers, $signer);
                $lifetimeString .= ".<br />\n";
            }

            if (!$showtimeExpired) {
                $showtime = $this->_applicationInstance->getObjectShowtime($object);
                $showtimeCount = $this->_applicationInstance->getObjectShowtimeCount($object);
                $showtimeList = $this->_applicationInstance->getObjectShowtimeList($object);
                if ($showtime != '') {
                    $showtimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::' . $showtime);
                } else {
                    $showtimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::Unlimited');
                }
            } else {
                $showtimeString .= $this->_applicationInstance->getTranslateInstance()->getTranslate('::Expired');
            }

            if ($lifetimeExpired) {
                $this->displayMessageError_DEPRECATED('::Expired');
            }
            ?>

            <div class="text">
                <p>
                    <?php if (!$lifetimeExpired) echo $lifetimeString . "<br /><br />\n"; ?>
                    <?php // echo $showtimeString;
                    ?>
                </p>
            </div>
            <?php
        }
    }

    private function _displayContentObjectAdd()
    {
        // Si autorisé à transferer des fichiers.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionUntyped('klictyPermitUploadObject')
            && $this->_unlocked
        ) {
            // S'il y a eu le téléchargement d'un fichier.
            if ($this->_applicationInstance->getActionInstance()->getUploadObject()) {
                // Affiche le titre.
                $this->displayDivTextTitleH2(self::DEFAULT_ICON_LO, '::UploadedNewFile', '', '');

                // Si pas d'erreur.
                if (!$this->_applicationInstance->getActionInstance()->getUploadObjectError()) {
                    // Affiche un message OK.
                    $this->displayMessageOk_DEPRECATED('::UploadedNewFileOK');

                    $id = $this->_applicationInstance->getActionInstance()->getUploadObjectID();
                    $instance = $this->_nebuleInstance->newObject($id);
                    $name = $instance->getName('all');
                    $desc = $instance->getType('all');
                    $htlink = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                        . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id;
                    ?>

                    <div class="textAction">
                        <div class="oneActionItem" id="selfEntity">
                            <div class="oneActionItem-top">
                                <div class="oneAction-icon">
                                    <?php $this->displayObjectColorIcon($id, $htlink, self::DEFAULT_ICON_LO); ?>
                                </div>
                                <div class="oneAction-entityname">
                                    <p><?php $this->displayInlineObjectColorIconName($this->_applicationInstance->getCurrentEntityID()); ?></p>
                                </div>
                                <div class="oneAction-title">
                                    <p><?php $this->displayHypertextLink($name, $htlink); ?></p>
                                </div>
                                <div class="oneAction-text">
                                    <p><?php echo $desc; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="oneAction-close"></div>
                    </div>
                    <?php
                } else {
                    $this->displayMessageError_DEPRECATED('::UploadedNewFileError');
                    ?>

                    <div class="text">
                        <p>
                            (<?php echo $this->_applicationInstance->getActionInstance()->getUploadObjectErrorMessage(); ?>
                            )
                        </p>
                    </div>
                    <?php
                }
            }

            // Affiche le titre.
            $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ADDOBJ);
            $this->displayDivTextTitleH2($icon, '::ObjectAdd', '::SelectUploadFile', '');
            ?>

            <div class="text">
                <form enctype="multipart/form-data" method="post"
                      action="?<?php echo self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_OBJECT_ADD_COMMAND
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                    <input type="hidden"
                           name="MAX_FILE_SIZE"
                           value="<?php echo $this->_configurationInstance->getOptionUntyped('klictyIOReadMaxDataPHP'); ?>"/>
                    <input type="file"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_FILE; ?>"/><br/>
                    <div class="floatRight textAlignRight">
                        <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::SubmitProtectFile'); ?>
                        <input type="checkbox"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_PROTECT; ?>"
                               value="y"><br/>
                        <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::SubmitLiveTimeFile'); ?>
                        :
                        <select
                                name="<?php echo Action::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LIFETIME; ?>"
                                class="klictyInput">
                            <option value="1h">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::1h'); ?>
                            </option>
                            <option value="2h">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::2h'); ?>
                            </option>
                            <option value="1d">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::1d'); ?>
                            </option>
                            <option value="2d">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::2d'); ?>
                            </option>
                            <option value="1w">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::1w'); ?>
                            </option>
                            <option value="2w">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::2w'); ?>
                            </option>
                            <option value="1m" selected>
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::1m'); ?>
                            </option>
                            <option value="2m">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::2m'); ?>
                            </option>
                            <option value="1y">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::1y'); ?>
                            </option>
                            <option value="2y">
                                <?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::2y'); ?>
                            </option>
                        </select><?php /* <br />
					<?php $this->_applicationInstance->getTranslateInstance()->echoTraduction('::SubmitShowTimeFile'); ?> :
<?php					<select
						name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_SHOWTIME; ?>"
						class="klictyInput">
						<option value="0" selected>
							<?php $this->_applicationInstance->getTranslateInstance()->echoTraduction('::Unlimited'); ?>
						</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select> */ ?>
                    </div>
                    <input type="submit"
                           value="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::SubmitFile') ?>"/><br/>
                    <br/>&nbsp;
                </form>
            </div>
            <?php
            $this->displayMessageInformation_DEPRECATED($this->_applicationInstance->getTranslateInstance()->getTranslate('::UploadMaxFileSize') . ' : ' . $this->_configurationInstance->getOptionUntyped('klictyIOReadMaxDataPHP') . 'o');
        } else {
            $this->displayMessageError_DEPRECATED('::::err_NotPermit');
        }
    }

    private function _displayInlineContentObjectAdd()
    {
    }

    private function _displayContentEntityAdd()
    {
        // Si une nouvelle entité vient d'être créée par l'instance des actions.
        if ($this->_actionInstance->getCreateEntity()) {
            $createEntityAction = $this->_actionInstance->getCreateEntity();
            $createEntityID = $this->_actionInstance->getCreateEntityID();
            $createEntityInstance = $this->_actionInstance->getCreateEntityInstance();
            $createEntityError = $this->_actionInstance->getCreateEntityError();
            $createEntityErrorMessage = $this->_actionInstance->getCreateEntityErrorMessage();

            // S'il n'y a pas d'erreur de création.
            if (!$createEntityError
                && is_a($createEntityInstance, 'Entity') // FIXME la classe
            ) {
                // Affiche le titre.
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ADDENT);
                echo $this->getDisplayTitle_DEPRECATED('::NewEntityCreated', $icon);

                // Affiche la nouvelle entité.
                echo '<div class="layoutObjectsList">' . "\n";
                echo '<div class="objectsListContent">' . "\n";

                $param = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => false,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                );
                $param['selfHookList'] = array();

                // Se connecter avec cette entité.
                $param['selfHookList'][0]['name'] = '::ConnectWith';
                $param['selfHookList'][0]['icon'] = self::DEFAULT_ICON_ENTITY_LOCK;
                $param['selfHookList'][0]['link'] = '?'
                    . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_SWITCH_TO_ENTITY
                    . '&' . References::COMMAND_SELECT_ENTITY . '=' . $createEntityInstance;

                echo $this->getDisplayObject_DEPRECATED($createEntityInstance, $param);

                echo '</div>' . "\n";
                echo '</div>' . "\n";
            } else {
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'error',
                    'displayRatio' => 'long',
                );
                echo $this->getDisplayInformation_DEPRECATED('::EntityAddError', $param);
            }
        } else {
            // Affiche le titre.
            $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ADDENT);
            echo $this->getDisplayTitle_DEPRECATED('::CreateEntity', $icon);

            // Si autorisé à créer une entité.
            if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteEntity')
                && !$this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitPublicCreateEntity')
            ) {
                ?>

                <div class="text">
                    <p>


                    <form method="post"
                          action="?<?php echo self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ENTITY_ADD_COMMAND
                              . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <table>
                            <tr>
                                <td></td>
                                <td><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Prenom'); ?></td>
                                <td><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Nom'); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Nommage'); ?></b>
                                </td>
                                <td><input type="text"
                                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME; ?>"
                                           size="10" value="" class="klictyModuleEntityInput"></td>
                                <td><input type="text"
                                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME; ?>"
                                           size="20" value="" class="klictyModuleEntityInput"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <b><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::::Password'); ?></b>
                                </td>
                                <td colspan=2><input type="password"
                                                     name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1; ?>"
                                                     size="30" value="" class="klictyModuleEntityInput"></td>
                            </tr>
                            <tr>
                                <td>
                                    <b><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::Confirmation'); ?></b>
                                </td>
                                <td colspan=2><input type="password"
                                                     name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2; ?>"
                                                     size="30" value="" class="klictyModuleEntityInput"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit"
                                           value="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::CreateAnEntity'); ?>"
                                           class="klictyModuleEntityInput"></td>
                            </tr>
                        </table>
                    </form>
                    <?php

                    ?>

                    </p>
                </div>
                <?php
            } else {
                $param = array(
                    'enableDisplayAlone' => true,
                    'enableDisplayIcon' => true,
                    'informationType' => 'error',
                    'displayRatio' => 'long',
                );
                echo $this->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
            }
        }
    }

    private function _displayContentEntitySync()
    {
        // Affiche le titre.
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_SYNENT);
        echo $this->getDisplayTitle_DEPRECATED('::EntitySync', $icon);

        // Si autorisé à synchroniser une entité.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitSynchronizeLink')
            && !$this->_unlocked
        ) {
            ?>
            <div class="text">

                <form method="post"
                      action="?<?php echo self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ENTITY_SYNC_COMMAND
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                    <table>
                        <tr>
                            <td></td>
                            <td><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::EntityLocalisation'); ?></td>
                        </tr>
                        <tr>
                            <td>
                                <b><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::URL'); ?> </b>
                            </td>
                            <td><input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_NEW_ENTITY; ?>"
                                       size="40" value="" class="klictyModuleEntityInput"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit"
                                       value="<?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::SynchronizeEntity'); ?>"
                                       class="klictyModuleEntityInput"></td>
                        </tr>
                    </table>
                </form>
                <?php

                ?>

            </div>
            <?php
        } else {
            $param = array(
                'enableDisplayAlone' => true,
                'enableDisplayIcon' => true,
                'informationType' => 'error',
                'displayRatio' => 'long',
            );
            echo $this->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
        }
    }

    private function _displayInlineContentEntitySync()
    {
    }

    private function _displayContentProtection()
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();
        $protect = $object->getMarkProtected();

        // Affichage de l'entête.
        $this->displayObjectDivHeaderH1($object);

        // Si l'objet est présent.
        if ($object->checkPresent()) {
            if ($protect) {
                $this->displayMessageOk_DEPRECATED('::ProtectedObject');
                ?>

                <div class="text">
                    <table border="0">
                        <tr>
                            <td><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::ProtectedID'); ?></td>
                            <td>&nbsp;<?php $this->displayInlineObjectColorIconName($object->getProtectedID()); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->_applicationInstance->getTranslateInstance()->getTranslate('::UnprotectedID'); ?></td>
                            <td>
                                &nbsp;<?php $this->displayInlineObjectColorIconName($object->getUnprotectedID()); ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }

            // Affiche en ligne les entités pour qui c'est partagé.
            $this->registerInlineContentID('objectprotectionshared');

            if ($protect) {
                echo "<div class=\"sequence\"></div>\n";

                // Affiche le titre.
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LK);
                $this->displayDivTextTitleH2($icon, '::ShareProtectObject', '', '');

                // Avertissement.
                $this->displayMessageWarning_DEPRECATED('::WarningSharedProtection');

                // Affiche en ligne les entités pour qui cela est partagé.
                $this->registerInlineContentID('objectprotectionshareto');
            }
        } else {
            $this->displayMessageError_DEPRECATED('::::display:content:errorNotAvailable');
        }
    }

    private function _displayInlineContentProtection()
    {
        switch ($this->getInlineContentID()) {
            case 'objectprotectionshared':
                $this->_displayInlineContentProtectionShared();
                break;
            case 'objectprotectionshareto':
                $this->_displayInlineContentProtectionShareTo();
                break;
        }
    }

    private function _displayInlineContentProtectionShared()
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        // Affichage les actions possibles.
        $actionList = array();
        $id = $object->getID();
        $protect = $object->getMarkProtected();

        // Si l'objet est présent.
        if ($object->checkPresent()
            && $protect
        ) {
            // Prépare l'affichage.
            $listOkEntities = array();
            $shareTo = $object->getProtectedTo();
            if (sizeof($shareTo) != 0) {
                $list = array();
                $i = 0;
                foreach ($shareTo as $entity) {
                    $instance = $this->_nebuleInstance->newEntity($entity);
                    $type = $instance->getIsEntity('all');
                    if (!isset($listOkEntities[$entity]) && $type) {
                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $entity;
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        if ($this->_unlocked
                            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
                        ) {
                            if ($entity == $this->_nebuleInstance->getCurrentEntity()) {
                                // Déprotéger l'objet.
                                $list[$i]['actions'][0]['name'] = '::UnprotectObject';
                                $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LK;
                                $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT . '=' . $id
                                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            } elseif (!$this->_nebuleInstance->getIsRecoveryEntity($entity)
                                || $this->_configurationInstance->getOptionAsBoolean('permitRecoveryRemoveEntity')
                            ) {
                                // Annuler le partage de protection. Non fiable...
                                $list[$i]['actions'][0]['name'] = '::RemoveShareProtect';
                                $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY . '=' . $entity
                                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }
                        }

                        // Marque comme vu.
                        $listOkEntities[$entity] = true;
                        $i++;
                    }
                }
                unset($entity, $instance, $type);
                // Affichage
                if (sizeof($list) != 0)
                    $this->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->displayMessageInformation_DEPRECATED('::NoEntity');
            }
            unset($shareTo);
        } else {
            $this->displayMessageInformation_DEPRECATED('::UnprotectedObject');

            // Si l'entité est déverrouillée.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            ) {
                // Protéger l'objet.
                $actionList[0]['name'] = '::ProtectObject';
                $actionList[0]['icon'] = self::DEFAULT_ICON_LK;
                $actionList[0]['desc'] = '';
                $actionList[0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT . '=' . $id
                    . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                $this->displayActionList($actionList);
                unset($actionList);

                $this->displayMessageWarning_DEPRECATED('::WarningProtectObject');
            }
        }
    }

    private function _displayInlineContentProtectionShareTo()
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        // Affichage les entités pour partage.
        $id = $object->getID();
        $protect = $object->getMarkProtected();

        // Si l'objet est présent et protégé et si l'entité est déverrouillée
        if ($object->checkPresent()
            && $protect
            && $this->_unlocked
            && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
        ) {
            // Liste tous les groupes.
            $listGroups = $this->_nebuleInstance->getListGroupsID($this->_nebuleInstance->getCurrentEntity(), '');

            //Prépare l'affichage des groupes.
            $list = array();
            $i = 0;
            if (sizeof($listGroups) != 0) {
                $listOkGroups = array();
                foreach ($listGroups as $group) {
                    if (!isset($listOkGroups[$group])) {
                        $instance = $this->_nebuleInstance->newGroup($group);

                        // Si c'est un groupe fermé.
                        $typeClosed = $instance->getMarkClosed();

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $group;
                        if ($typeClosed)
                            $list[$i]['desc'] = '::GroupeFerme';
                        else
                            $list[$i]['desc'] = '::GroupeOuvert';
                        $list[$i]['actions'] = array();

                        // Partager la protection avec le groupe.
                        $list[$i]['actions'][0]['name'] = '::ShareProtectObject';
                        $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LL;
                        if ($typeClosed)
                            $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED . '=' . $group
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        else
                            $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED . '=' . $group
                                . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                        // Marque comme vu.
                        $listOkGroups[$group] = true;
                        $i++;
                    }
                    unset($instance, $typeClosed);
                }
                unset($listGroups, $group);
            }

            $hashType = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
            $hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
            $hashEntityObject = $this->_nebuleInstance->newObject($hashEntity);

            // Ajoute des entités à ne pas afficher.
            $listOkEntities[$this->_applicationInstance->getCurrentEntityID()] = true;
            $listOkEntities[$this->_nebuleInstance->getInstanceEntity()] = true;
            $listOkEntities[$this->_nebuleInstance->getPuppetmaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getSecurityMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getCodeMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getDirectoryMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getTimeMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getCurrentEntity()] = true;

            // Liste toutes les autres entités.
            $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);

            // Enlève les entités pour lequelles la protection est déjà faite.
            $shareTo = $object->getProtectedTo();
            foreach ($shareTo as $entity) {
                $listOkEntities[$entity] = true;
            }
            unset($shareTo);

            //Prépare l'affichage des entités.
            if (sizeof($links) != 0) {
                foreach ($links as $link) {
                    $instance = $this->_nebuleInstance->newEntity($link->getParsed()['bl/rl/nid1']);
                    $type = $instance->getIsEntity('all');
                    if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid1']]) && $type) {
                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . References::COMMAND_SELECT_OBJECT
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $link->getParsed()['bl/rl/nid1'];
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Se connecter avec cette entité.
                        $list[$i]['actions'][0]['name'] = '::ShareProtectObject';
                        $list[$i]['actions'][0]['icon'] = self::DEFAULT_ICON_LL;
                        $list[$i]['actions'][0]['link'] = '?' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_PROTEC_COMMAND
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY . '=' . $link->getParsed()['bl/rl/nid1']
                            . '&' . References::COMMAND_SELECT_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                        // Marque comme vu.
                        $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
                        $i++;
                    }
                }
                unset($links, $link, $instance, $type);
            }
            unset($hashType, $hashEntity, $hashEntityObject, $listOkGroups, $listOkEntities);

            // Affichage
            if (sizeof($list) != 0)
                $this->displayItemList($list);
            else
                $this->displayMessageInformation_DEPRECATED('::NoEntity');
            unset($list);
        }
    }

    private function _displayContentAuth()
    {
        echo '<div class="layoutAloneItem">' . "\n";
        echo '<div class="aloneItemContent">' . "\n";
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => true,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            //'flagUnlocked' => $this->_unlocked,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => false,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_applicationInstance->getDisplayInstance()->getDisplayObject_DEPRECATED($this->_applicationInstance->getCurrentEntityInstance(), $param);
        echo '</div>' . "\n";
        echo '</div>' . "\n";

        if ($this->_applicationInstance->getCurrentEntityInstance()->issetPrivateKeyPassword()
            || ($this->_applicationInstance->getCurrentEntityID() == $this->_nebuleInstance->getCurrentEntity()
                && $this->_unlocked
            )
        ) {
            $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_KEY);
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayTitle_DEPRECATED('::::entity:unlocked', $icon);
        } else {
            $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ENTITY_LOCK);
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayTitle_DEPRECATED('::::entity:locked', $icon);
        }

        // Extrait les états de tests en warning ou en erreur.
        $idCheck = 'Error';
        if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
            $idCheck = 'Ok';
        } elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN') {
            $idCheck = 'Warn';
        }

        // Affiche les tests.
        if ($idCheck != 'Ok') {
            $list = array();
            $check = array(
                $this->_applicationInstance->getCheckSecurityBootstrap(),
                $this->_applicationInstance->getCheckSecurityCryptoHash(),
                $this->_applicationInstance->getCheckSecurityCryptoSym(),
                $this->_applicationInstance->getCheckSecurityCryptoAsym(),
                $this->_applicationInstance->getCheckSecuritySign(),
                $this->_applicationInstance->getCheckSecurityURL(),
            );
            $chnam = array('Bootstrap', 'Crypto Hash', 'Crypto Sym', 'Crypto Asym', 'Link Sign', 'URL');
            for ($i = 0; $i < sizeof($check); $i++) {
                $list[$i]['param'] = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => false,
                    'displayRatio' => 'short',
                );
                $list[$i]['information'] = $chnam[$i];
                $list[$i]['object'] = '1';
                $list[$i]['param']['informationType'] = 'error';
                if ($check[$i] == 'OK') {
                    $list[$i]['param']['informationType'] = 'ok';
                } elseif ($check[$i] == 'WARN') {
                    $list[$i]['param']['informationType'] = 'warn';
                }
            }
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayObjectsList($list, 'small');
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'enableDisplayAlone' => true,
                'informationType' => 'ok',
                'displaySize' => 'small',
                'displayRatio' => 'short',
            );
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::SecurityChecks', $param);
        }

        // Affiche le champs de mot de passe.
        if ($idCheck != 'Error') {
            if ($this->_applicationInstance->getCurrentEntityInstance()->issetPrivateKeyPassword()
                || ($this->_applicationInstance->getCurrentEntityID() == $this->_nebuleInstance->getCurrentEntity()
                    && $this->_unlocked
                )
            ) {
                // Propose de la verrouiller.
                $list = array();
                $list[0]['title'] = $this->_traductionInstance->getTranslate('::::lock');
                $list[0]['desc'] = $this->_traductionInstance->getTranslate('::::entity:unlocked');
                $list[0]['icon'] = Displays::DEFAULT_ICON_ENTITY_LOCK;
                $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_FLUSH;
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayMenuList($list, 'Medium');
            } else {
                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";
                $param['displaySize'] = 'small';
                $param['displayRatio'] = 'long';
                $param['objectIcon'] = Displays::DEFAULT_ICON_KEY;
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayObject_DEPRECATED($this->_nebuleInstance->getCurrentEntityPrivateKeyInstance(), $param);
                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";

                echo '<div class="layoutObject layoutInformation">' . "\n";
                echo '<div class="objectTitle objectDisplayMediumShort objectTitleMedium objectDisplayShortMedium informationDisplay informationDisplayMedium informationDisplay' . $idCheck . '">' . "\n";

                echo '<div class="objectTitleText objectTitleMediumText objectTitleText0 informationTitleText">' . "\n";

                echo '<div class="objectTitleRefs objectTitleMediumRefs informationTitleRefs informationTitleRefs' . $idCheck . '" id="klictyModuleEntityConnect">' . "\n";
                echo $this->_traductionInstance->getTranslate('::::Password') . "<br />\n";
                echo '</div>' . "\n";

                echo '<div class="objectTitleName objectTitleMediumName informationTitleName informationTitleName' . $idCheck . ' informationTitleMediumName" id="klictyModuleEntityConnect">' . "\n";
                ?>
                <form method="post"
                      action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                          . '&' . References::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntityID(); ?>">
                    <input type="hidden" name="ent"
                           value="<?php echo $this->_applicationInstance->getCurrentEntityID(); ?>">
                    <input type="password" name="pwd">
                    <input type="submit" value="<?php echo $this->_traductionInstance->getTranslate('::::unlock'); ?>">
                </form>
                <?php
                echo '</div>' . "\n";

                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";
            }
        } else {
            if ($this->_applicationInstance->getCurrentEntityInstance()->issetPrivateKeyPassword()
                || ($this->_applicationInstance->getCurrentEntityID() == $this->_nebuleInstance->getCurrentEntity()
                    && $this->_unlocked
                )
            ) {
                // Propose de la verrouiller.
                $list = array();
                $list[0]['title'] = $this->_traductionInstance->getTranslate('::::lock');
                $list[0]['desc'] = $this->_traductionInstance->getTranslate('::::entity:unlocked');
                $list[0]['icon'] = Displays::DEFAULT_ICON_ENTITY_LOCK;
                $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_AUTH_COMMAND
                    . '&' . References::COMMAND_AUTH_ENTITY_LOGOUT
                    . '&' . References::COMMAND_FLUSH;
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayMenuList($list, 'Medium');
            } else {
                // Affiche un message d'erreur.
                $param = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => true,
                    'informationType' => 'error',
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayInformation_DEPRECATED('::::err_NotPermit', $param);
            }
        }
    }

private function _displayContentAbout()
{
    global $nebuleSurname,
           $nebuleWebsite;

    $iconLL = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LL);
    $iconLK = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LK);
    $iconTIME = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_TIME);
    ?>

    <div class="textcenter" id="welcome">
        <p>
            <img alt="klicty" src="<?php echo $this->_logoApplicationWelcome; ?>"/><br/>
            <?php echo Application::APPLICATION_NAME; ?>
        </p>
    </div>
    <div id="about"></div>
    <div id="iconsWelcome">
        <div id="iconWelcome1">
            <?php $this->displayUpdateImage($iconLL, '::Share', 'iconNormalDisplay') ?><br/>
            <?php echo $this->_traductionInstance->getTranslate('::Share'); ?>
        </div>
        <div id="iconWelcome2">
            <?php $this->displayUpdateImage($iconLK, '::Protection', 'iconNormalDisplay') ?><br/>
            <?php echo $this->_traductionInstance->getTranslate('::Protection'); ?>
        </div>
        <div id="iconWelcome3">
            <?php $this->displayUpdateImage($iconTIME, '::TimeLimited', 'iconNormalDisplay') ?><br/>
            <?php echo $this->_traductionInstance->getTranslate('::TimeLimited'); ?>
        </div>
    </div>

    <div class="layoutMenuList">
        <div class="menuListContent">
            <?php
            $param = array(
                'enableDisplayIcon' => true,
                'informationType' => 'ok',
            );
            echo $this->getDisplayInformation_DEPRECATED('::Welcome', $param);

            $applicationLevel = $this->_configurationInstance->getOptionAsString('codeBranch');
            if ($applicationLevel == 'Experimental'
                || $applicationLevel == 'Developpement'
            ) {
                $param['informationType'] = 'warn';
                echo $this->getDisplayInformation_DEPRECATED('::::' . $applicationLevel, $param);
            }
            $param['informationType'] = 'information';
            echo $this->getDisplayInformation_DEPRECATED($this->_traductionInstance->getTranslate('::Version') . ' : ' . Application::APPLICATION_VERSION, $param);
            ?>
        </div>
    </div>

    <div class="sequence" id="about"></div>
    <?php
    $param = array(
        'enableDisplayAlone' => true,
        'enableDisplayIcon' => true,
        'informationType' => 'information',
        'displayRatio' => 'long',
    );
    echo $this->getDisplayInformation_DEPRECATED('::AboutMessage', $param);
    ?>
    </div>

    <div class="sequence" id="lang"></div>
    <?php
    $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_WORLD);
    echo $this->getDisplayTitle_DEPRECATED(':::SelectLanguage', $icon);
    $i = 0;
    foreach ($this->_traductionInstance->getLanguageList() as $lang) {
        $actionList[$i]['title'] = $this->_traductionInstance->getTranslate(':::Language:' . $lang);
        $actionList[$i]['icon'] = $this->_traductionInstance->getLanguageIcon($lang);
        $actionList[$i]['desc'] = '';
        $actionList[$i]['htlink'] = '?'
            . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . self::DEFAULT_ABOUT_COMMAND .
            '&' . Translates::DEFAULT_COMMAND_LANGUAGE . '=' . $lang . '#about';
        $i++;
    }
    // Affichage.
    echo $this->getDisplayMenuList($actionList, 'Medium');
    unset($actionList);
    ?>

    <div class="sequence" id="help"></div>
    <?php $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_HELP); echo $this->getDisplayTitle_DEPRECATED('::Help', $icon); ?>

    <?php
    $param = array(
        'enableDisplayAlone' => true,
        'enableDisplayIcon' => true,
        'informationType' => 'information',
        'displayRatio' => 'long',
    );
    echo $this->getDisplayInformation_DEPRECATED('::HelpMessage', $param);
    ?>

    <div class="sequence" id="recovery"></div>
    <?php $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LC); echo $this->getDisplayTitle_DEPRECATED('::ProtectionRecovery', $icon); ?>
    <?php $this->registerInlineContentID('listrecovery'); ?>

    <?php
    $param = array(
        'enableDisplayAlone' => true,
        'enableDisplayIcon' => true,
        'informationType' => 'information',
        'displayRatio' => 'long',
    );
    echo $this->getDisplayInformation_DEPRECATED('::HelpRecoveryEntity', $param);

    $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
    if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
        $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;

    $linkNebuleWebsite = $nebuleWebsite;
    if (strpos($nebuleWebsite, '://') === false)
        $linkNebuleWebsite = 'http://' . $nebuleWebsite;

    ?>

    <div id="welcomeFooter">
        <p><?php echo $this->_traductionInstance->getTranslate('::Citation'); ?></p>
        <p>
            <a href="<?php echo $linkApplicationWebsite; ?>" target="_blank"><?php echo Application::APPLICATION_SURNAME; ?> <img
                        alt="<?php echo Application::APPLICATION_SURNAME; ?>"
                        src="<?php echo Application::APPLICATION_LICENCE_LOGO; ?>"/></a>&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $linkNebuleWebsite; ?>" target="_blank">
                <img alt="<?php echo $nebuleSurname; ?>"
                    src="<?php echo Application::NEBULE_LICENCE_LOGO; ?>"/> <?php echo $nebuleSurname; ?>
            </a>
        </p>
    </div>
    <?php
}

    private function _displayInlineContentAbout()
    {
        // Lit les entités de recouvrement.
        $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();
        $listSigners = $this->_nebuleInstance->getRecoveryEntitiesSigners();

        $list = array();
        $i = 0;
        foreach ($listEntities as $instance) {
            $id = $instance->getID();
            $list[$i]['object'] = $instance;
            $list[$i]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => true,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
            );
            if ($listSigners[$id] != '0'
                && $listSigners[$id] != ''
            )
                $list[$i]['param']['objectRefs'][0] = $listSigners[$id];
            $i++;
        }
        unset($listEntities, $listSigners, $instance, $id);

        // Affichage FIXME ne semble pas remonter de signataire...
        if (sizeof($list) != 0) {
            echo $this->getDisplayObjectsList($list, 'Medium');
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'informationType' => 'information',
            );
            echo $this->getDisplayInformation_DEPRECATED('::NoRecoveryEntity', $param);
        }
        unset($list);
    }


    /**
     * Affiche la métrologie.
     */
    private function _displayMetrology()
    {
        if ($this->_configurationInstance->getOptionUntyped('klictyDisplayMetrology')) {
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
                    $klictyTimeList = $this->_metrologyInstance->getTimeArray();
                    $klicty_time_total = 0;
                    foreach ($klictyTimeList as $time) {
                        $klicty_time_total = $klicty_time_total + $time;
                    }
                    echo $this->_traductionInstance->getTranslate('Le serveur à pris %01.4fs pour calculer la page.', $klicty_time_total);
                    echo ' (';
                    foreach ($klictyTimeList as $time) {
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

        </div>
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
        if ($desc == '')
            $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($typemime);

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == nebule::REFERENCE_OBJECT_ENTITY && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) // FIXME la classe
            $object = $this->_nebuleInstance->newEntity($object->getID());
        if ($isGroup && !is_a($object, 'Group')) // FIXME la classe
            $object = $this->_nebuleInstance->newGroup($object->getID());

        // Vérifie si il est protégé
        $protected = $object->getMarkProtected();

        $name = $object->getFullName('all');
        $present = $object->checkPresent();

        // Si le contenu est présent.
        if ($protected && $object->getID() == $object->getProtectedID()) {
            $status = 'protected';
        } elseif ($present) {
            // Prépare l'état de l'objet.
            $status = 'ok';
            $content = $object->getContent(0);
            if ($content == null)
                $status = 'tooBig';
            unset($content);
        } else {
            $status = 'notPresent';
        }
        if ($object->getMarkWarning())
            $status = 'warning';
        if ($object->getMarkDanger())
            $status = 'danger';
        // Prépare l'aide en ligne.
        if ($help == '') {
            if ($isEntity)
                $help = ':::display:default:help:Entity';
            elseif ($isGroup)
                $help = ':::display:default:help:Group';
            else
                $help = ':::display:default:help:Object';
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
                        if ($this->_configurationInstance->getOptionUntyped('klictyDisplayUnverifyLargeContent')) {
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
                    case 'protected':
                        $msg = $this->_traductionInstance->getTranslate('::::display:content:ObjectProctected');
                        $this->displayIcon(self::DEFAULT_ICON_IOK, $msg, 'iconNormalDisplay');
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
        unset($name, $typemime, $isEntity, $isGroup);
    }

    /**
     * Affiche dans les barres de titres l'icône d'aide contextuelle.
     * @param string $help
     */
    private function _displayDivOnlineHelp($help)
    {
        // Si authorisé à afficher l'aide.
        if ($this->_configurationInstance->getOptionUntyped('klictyDisplayOnlineHelp')) {
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
                     id="idhelp<?php echo $this->_idHelp; ?>" <?php echo $linkext; ?> />
            </div>
            <?php
            unset($linkext, $image);
            $this->_idHelp++;
        }
    }

    private $_idHelp = 0;


    /**
     * Affiche le titre pour un paragraphe de texte. Par défaut, affiche le titre H1.
     *
     * @param Node   $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     */
    public function displayDivTextTitle(Node $icon, string $title = '', string $desc = '', string $help = '')
    {
        $this->displayDivTextTitleH1($icon, $title, $desc, $help);
    }

    /**
     * Affiche le titre H1 pour un paragraphe de texte.
     *
     * @param Node   $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     */
    public function displayDivTextTitleH1(Node $icon, string $title = '', string $desc = '', string $help = '')
    {
        ?>

        <div class="textTitle">
            <?php
            if ($title != '')
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            if ($desc == '')
                $desc = '-';
            else
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
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
     * @param Node   $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     */
    public function displayDivTextTitleH2(Node $icon, string $title = '', string $desc = '', string $help = '')
    {
        ?>

        <div class="textTitle2">
            <?php
            if ($title != '')
                $title = $this->_applicationInstance->getTranslateInstance()->getTranslate($title);
            if ($desc == '')
                $desc = '-';
            else
                $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($desc);
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


    public function displayActionList($actionList)
    {
        if (sizeof($actionList) == 0)
            return;
        ?>

        <div class="textAction">
            <?php
            foreach ($actionList as $appHook) {
                if ($appHook['name'] != '') {
                    $name = $this->_applicationInstance->getTranslateInstance()->getTranslate($appHook['name']);
                    $icon = $this->_nebuleInstance->newObject($appHook['icon']);
                    if ($icon == '')
                        $icon = self::DEFAULT_ICON_LSTOBJ;
                    $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($appHook['desc']);
                    $link = $appHook['link'];
                    if (isset($appHook['css']) && $appHook['css'] != '')
                        $cssid = 'id="' . $appHook['css'] . '"';
                    else
                        $cssid = '';
                    ?>

                    <div class="oneAction" <?php echo $cssid; ?>>
                        <div class="oneAction-icon">
                            <?php $this->displayHypertextLink($this->convertUpdateImage($icon, $name), $link); ?>
                        </div>
                        <div class="oneAction-title">
                            <p><?php $this->displayHypertextLink($name, $link); ?></p>
                        </div>
                        <div class="oneAction-text">
                            <p><?php echo $desc; ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            unset($appHook, $name, $icon, $desc, $link, $cssid);
            ?>

            <div class="oneAction-close"></div>
        </div>
        <?php
    }


    /**
     * Affiche un élément de l'arborescence.
     *
     * @param array $item
     */
    private function _displayArboItem(array $item)
    {
        if (sizeof($item) == 0) {
            return;
        }
        if (!is_a($item['object'], 'Node') // FIXME la classe
            && !is_a($item['object'], 'entity') // FIXME la classe
            && !is_a($item['object'], 'group') // FIXME la classe
            && !is_a($item['object'], 'conversation') // FIXME la classe
        ) {
            return;
        }

        // Extraction.
        $object = $item['object'];

        $entity = null;
        $entityID = '0';
        if (is_a($item['entity'], 'entity')) { // FIXME la classe
            $entity = $item['entity'];
            $entityID = $entity->getID();
        } elseif (Node::checkNID($item['entity'])
            && $this->_nebuleInstance->getIoInstance()->checkObjectPresent($item['entity'])
            && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($item['entity'])
        ) {
            $entity = $this->_nebuleInstance->newEntity($item['entity']);
            $entityID = $entity->getID();
        }

        $htlink = '';
        if (isset($item['link'])) {
            $htlink = $item['link'];
        }

        $desc = '';
        if (isset($item['desc'])) {
            $desc = $this->_traductionInstance->getTranslate($item['desc']);
        }

        $icon = '';
        if (isset($item['icon'])) {
            $icon = $item['icon'];
        }

        $type = $object->getType('all');
        // Extrait un nom d'objet à afficher de façon correcte.
        $entityName = '';
        if ($entityID != '0') {
            $entityName = $entity->getFullName('all');
        }

        if ($object->getIsEntity('all')) {
            $name = $object->getFullName('all');
        } else {
            $name = $object->getName('all');
        }
        $namesize = 21;
        $shortname = $name;

        // Normalise la variable si vide.
        $htlink = $this->prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        ?>

        <div class="oneActionItem"
             id="<?php if ($entityID == $this->_applicationInstance->getCurrentEntityID()) echo 'selfEntity'; else echo 'otherEntity'; ?>">
            <div class="oneActionItem-top">
                <div class="oneAction-icon">
                    <?php $this->displayObjectColorIcon($object, $htlink, $icon); ?>
                </div>
                <?php
                if ($entityName != '') {
                    ?>

                    <div class="oneAction-entityname">
                        <p><?php $this->displayInlineObjectColorIconName($entityID); ?></p>
                    </div>
                    <?php
                }
                ?>

                <div class="oneAction-title">
                    <p><?php $this->displayHypertextLink($shortname, $htlink); ?></p>
                </div>
                <?php
                if (isset($item['desc'])
                    && strlen($item['desc']) != 0
                ) {
                    ?>

                    <div class="oneAction-text">
                        <p><?php echo $desc; ?></p>
                    </div>
                    <?php
                }
                ?>

            </div>
            <?php
            if ($object->getMarkWarning() || $object->getMarkDanger() || $object->getMarkProtected() || (isset($item['actions']) && sizeof($item['actions']) != 0)) {
                ?>

                <div class="oneActionItem-bottom">
                    <?php
                    if ($object->getMarkWarning()) {
                        ?>

                        <div class="oneAction-warn">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IWARN);
                                $this->displayUpdateImage(
                                    $icon,
                                    '::::display:content:warningTaggedWarning',
                                    'iconInlineDisplay');
                                echo ' ';
                                echo $this->_traductionInstance->getTranslate('::::display:content:warningTaggedWarning'); ?></p>
                        </div>
                        <?php
                    }
                    if ($object->getMarkDanger()) {
                        ?>

                        <div class="oneAction-error">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IERR);
                                $this->displayUpdateImage(
                                    $icon,
                                    '::::display:content:errorBan',
                                    'iconInlineDisplay');
                                echo ' ';
                                echo $this->_traductionInstance->getTranslate('::::display:content:errorBan'); ?></p>
                        </div>
                        <?php
                    }
                    if ($object->getMarkProtected()) {
                        ?>

                        <div class="oneAction-ok">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LK);
                                $this->displayUpdateImage(
                                    $icon,
                                    '::::display:content:ObjectProctected',
                                    'iconInlineDisplay');
                                echo ' ';
                                echo $this->_traductionInstance->getTranslate('::::display:content:ObjectProctected'); ?></p>
                        </div>
                        <?php
                    }
                    if (isset($item['actions'])
                        && sizeof($item['actions']) != 0
                    ) {
                        ?>

                        <div class="oneAction-actions">
                            <?php
                            foreach ($item['actions'] as $action) {
                                $icon = $this->_nebuleInstance->newObject($action['icon']);
                                $actionIcon = $this->convertUpdateImage($icon, $action['name'], 'iconInlineDisplay');
                                $actionName = $this->_traductionInstance->getTranslate($action['name']);
                                echo '<p>' . $actionIcon . ' ' . $this->convertHypertextLink($actionName, $action['link']) . "</p>\n";
                                unset($actionIcon, $actionName);
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

        </div>
        <?php
        unset($object, $type, $entityName, $name, $shortname, $icon, $desc);
    }

    /**
     * Retourne le nom tronqué d'une entité.
     *
     * @param string $name
     * @param int    $maxsize
     * @return string
     */
    private function _truncateName(string $name, int $maxsize): string
    {
        if ($maxsize == 0
            || $maxsize > $this->_configurationInstance->getOptionUntyped('displayNameSize')
        ) {
            $maxsize = $this->_configurationInstance->getOptionUntyped('displayNameSize');
        }
        if ($maxsize < 4) {
            $maxsize = 4;
        }
        if (strlen($name) > $maxsize) {
            $name = substr($name, 0, ($maxsize - 3)) . '...';
        }
        return $name;
    }


    const DEFAULT_LOGO_WELCOME = "data:image/png;base64,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";

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
    /*
	 * Définition des constantes pour les actions.
	 */
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LIFETIME = 'upfilelt';
    const DEFAULT_COMMAND_ACTION_UPLOAD_FILE_SHOWTIME = 'upfilest';

    private $_actionUploadFileLifeTime = '1m',
        $_actionUploadFileShowTime = 0;



    /**
     * Extrait pour action si un fichier est téléchargé vers le serveur.
     */
    protected function _extractActionUploadFile()
    {
        // Vérifie que la création de liens et l'écriture d'objets soient authorisées.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrologyInstance->addLog('Extract action upload file', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log

            /*
			 *  ------------------------------------------------------------------------------------------
			 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
			 *  ------------------------------------------------------------------------------------------
			 */
            // Lit le contenu de la variable _FILE si un fichier est téléchargé.
            if (isset($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['error'])
                && $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['error'] == UPLOAD_ERR_OK
                && trim($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['name']) != ''
            ) {
                // Extraction des méta données du fichier.
                $upfname = mb_convert_encoding(strtok(trim(filter_var($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upinfo = pathinfo($upfname);
                $upext = $upinfo['extension'];
                $upname = basename($upfname, '.' . $upext);
                $upsize = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['size'];
                $uppath = $_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name'];
                $uptype = '';
                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_configurationInstance->getOptionUntyped('klictyIOReadMaxDataPHP')) {
                        // Lit le type mime.
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $uptype = finfo_file($finfo, $uppath);
                        finfo_close($finfo);
                        if ($uptype == 'application/octet-stream')
                            $uptype = $this->_getFilenameTypeMime("$upname.$upext");

                        // Extrait les options de téléchargement.
                        $argUpd = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_UPDATE);
                        $argPrt = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_PROTECT);
                        $argObf = filter_has_var(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_OBFUSCATE_LINKS);

                        // Spécifique klicty.
                        $argLifeTime = trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_LIFETIME, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                        if ($argLifeTime != '1h' && $argLifeTime != '2h'
                            && $argLifeTime != '1d' && $argLifeTime != '2d'
                            && $argLifeTime != '1w' && $argLifeTime != '2w'
                            && $argLifeTime != '1m' && $argLifeTime != '2m'
                            && $argLifeTime != '1y' && $argLifeTime != '2y'
                        )
                            $argLifeTime = '1m';
                        $argShowTime = intval(trim(filter_input(INPUT_POST, self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE_SHOWTIME, FILTER_SANITIZE_STRING)));
                        if ($argShowTime < 0 || $argShowTime > 10)
                            $argShowTime = 0;

                        // Ecriture des variables.
                        $this->_actionUploadFile = true;
                        $this->_actionUploadFileName = $upname;
                        $this->_actionUploadFileExtension = $upext;
                        $this->_actionUploadFileType = $uptype;
                        $this->_actionUploadFileSize = $upsize;
                        $this->_actionUploadFilePath = $uppath;
                        $this->_actionUploadFileUpdate = $argUpd;
                        $this->_actionUploadFileProtect = $argPrt;
                        $this->_actionUploadFileObfuscateLinks = $argObf;

                        // Spécifique klicty.
                        $this->_actionUploadFileLifeTime = $argLifeTime;
                        $this->_actionUploadFileShowTime = $argShowTime;
                    } else {
                        $this->_actionUploadFileError = true;
                        $this->_actionUploadFileErrorMessage = 'Le fichier dépasse la taille limite de transfert.';
                    }
                } else {
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "Le fichier n'a pas été correctement transferé vers le serveur.";
                }
                unset($upfname, $upinfo, $upext, $upname, $upsize, $uppath, $uptype);
            }
        }
    }


    /**
     * Transfert un fichier et le nebulise.
     */
    protected function _actionUploadFile(): void
    {
        // Vérifie que la création d'objet soit authorisée.
        if ($this->_configurationInstance->getOptionAsBoolean('permitWrite')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteObject')
            && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            && $this->_unlocked
        ) {
            $this->_metrologyInstance->addLog('Action upload file', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000'); // Log

            // Lit le contenu du fichier.
            $data = file_get_contents($_FILES[self::DEFAULT_COMMAND_ACTION_UPLOAD_FILE]['tmp_name']);

            // Ecrit le contenu dans l'objet.
            $instance = new Node($this->_nebuleInstance, '0', $data, $this->_actionUploadFileProtect);
            if ($instance === false) {
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
                return;
            }

            // Lit l'ID.
            $id = $instance->getID();
            unset($data);
            if ($id == '0') {
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
                return;
            }
            $this->_actionUploadFileID = $id;

            // Définition de la date et le signataire.
            $date = date(DATE_ATOM);
            $signer = $this->_nebuleInstance->getCurrentEntity();

            // Création du type mime.
            if ($this->_actionUploadFileType != '') {
                // Crée l'objet avec le texte.
                $textID = $this->_nebuleInstance->createTextAsObject($this->_actionUploadFileType);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $id;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/type');
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
                }
            }

            // Crée l'objet du nom.
            if ($this->_actionUploadFileName != '') {
                // Crée l'objet avec le texte.
                $textID = $this->_nebuleInstance->createTextAsObject($this->_actionUploadFileName);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $id;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/nom');
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
                }
            }

            // Crée l'objet de l'extension.
            if ($this->_actionUploadFileExtension != '') {
                // Crée l'objet avec le texte.
                $textID = $this->_nebuleInstance->createTextAsObject($this->_actionUploadFileExtension);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $id;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/suffix');
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
                }
            }

            // Spécifique klicty.
            // Crée l'objet du temps de vie.
            if ($this->_actionUploadFileLifeTime != '') {
                // Crée l'objet avec le texte.
                $textID = $this->_nebuleInstance->createTextAsObject($this->_actionUploadFileLifeTime);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $id;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash(Application::APPLICATION_EXPIRATION_DATE);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, false);
                }
            }
            // Crée l'objet du nombre de vues.
            if ($this->_actionUploadFileShowTime != 0) {
                // Crée l'objet avec le texte.
                $text = (string)$this->_actionUploadFileShowTime;
                $textID = $this->_nebuleInstance->createTextAsObject($text);
                if ($textID !== false) {
                    // Crée le lien.
                    $action = 'l';
                    $source = $id;
                    $target = $textID;
                    $meta = $this->_nebuleInstance->getCryptoInstance()->hash(Application::APPLICATION_EXPIRATION_COUNT);
                    $this->_createLink($signer, $date, $action, $source, $target, $meta, false);
                }
            }

            // Si mise à jour de l'objet en cours.
            if ($this->_actionUploadFileUpdate) {
                // Crée le lien.
                $action = 'u';
                $source = $this->_applicationInstance->getCurrentObjectID();
                $target = $id;
                $meta = '0';
                $this->_createLink($signer, $date, $action, $source, $target, $meta, $this->_actionUploadFileObfuscateLinks);
            }

            unset($date, $signer, $source, $target, $meta, $link, $newLink, $textID);

            // Affichage des actions.
            $this->_displayInstance->displayInlineAllActions();
        }
    }
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

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::Welcome' => 'Bienvenue sur <i>klicty</i>.',
            #'::PleaseSelectEntity']='Sélectionner une entité existante<br />ou en créer une nouvelle :',
            '::Version' => 'Version',
            '::About' => 'A propos',
            '::Help' => 'Aide',
            '::menu' => 'Menu',
//        '::::err_NotPermit' => 'Non autorisé !',
            ':::Flush' => 'Réinitialisation',
            '::Share' => 'Partage',
            '::Protection' => 'Protection',
            '::TimeLimited' => 'Durée limitée',
            '::AboutMessage' => "C'est un <b>espace ouvert et protégé</b> de <b>partage d'information</b> à <b>durée limitée</b>.<br />
<br />
<h2>&gt; Partage</h2>
Les fichiers téléchargés deviennent des objets. Sous forme d'objets, ils sont strictement identifiés et leurs contenus sont complètement verrouillés. Ainsi, ils peuvent être conservés et partagés avec une grande fiabilité.<br />
<br />
<h2>&gt; Protection</h2>
Les objets peuvent être publics ou protégés. Les objets protégés bénéficient d'un chiffrement fort afin de les rendre illisibles de façon permanente pour toute personne non autorisée.<br />
<br />
<h2>&gt; Durée limitée</h2>
Les objets ainsi partagés ont tous une durée de vie limitée sur ce serveur. Ils sont supprimés à l'issue.<br />
La durée de vie est une limite imposée spécifiquement sur <i>klicty</i>.",
            '::HelpMessage' => "L'ensemble repose sur le projet <i>nebule</i> et sa façon spécifique de gérer les objets.<br /><br />Aide en cours de rédaction...",
            '::HelpRecoveryEntity' => "Les entités de recouvrement permettent en cas de besoin de déprotéger un objet.<br />
Cela peut être une politique de sécurité dans une société ou une contrainte légale dans certains pays.<br />
<br />
Quelque soit l'entité qui protège un objet, la protection est automatiquement et silencieusement partagée avec toutes les entités de recouvrement.<br />
Toutes les entités de recouvrement sont affichées ici, aucune n'est cachée.",
            ':::SelectLanguage' => 'Sélectionner la langue',
            ':::Language:fr-fr' => 'Français (France)',
            ':::Language:en-en' => 'English (England)',
            ':::Language:es-co' => 'Español (Colombia)',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'ATTENTION !',
            '::::ERROR' => 'ERREUR !',
            '::::HtmlHeadDescription' => "Espace ouvert et protégé de partage d'information à durée limitée.",
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[En cours de développement]',
            '::::SecurityChecks' => 'Tests de sécurité',
            '::ObjectsList' => 'Lister les objets',
            '::ObjectAdd' => 'Ajouter un objet',
            '::EntitiesList' => 'Liste des entités',
            '::EntitiesGroup' => "Groupe d'entités",
            '::EntitiesGroupList' => "Lister les groupes d'entités",
            '::EntitiesGroupAdd' => "Ajouter un groupe d'entités",
            '::EntityAdd' => 'Créer une entité',
            '::EntityAddError' => "Erreur lors de la création de l'entité !",
            '::EntitySync' => 'Synchroniser une entité',
            '::::entity:locked' => 'Entité verrouillée. Déverrouiller ?',
            '::::entity:unlocked' => 'Entité déverrouillée. Verrouiller ?',
            '::::lock' => 'Verrouiller',
            '::::unlock' => 'Déverrouiller',
            #'::ListOrAddObject']='Lister mes objets ou en ajouter un nouveau :',
            '::OKConnect' => 'Connexion réussi !',
            '::ConnectWith' => 'Se connecter avec cette entité',
            '::SynchronizeEntity' => "Synchroniser l'entité",
            '::ShowObjectsOf' => "Voir les objets de l'entité",
            '::ShowPublicObjectsOf' => "Voir les objets publics de l'entité",
            '::URL' => 'URL',
            '::EntityLocalisation' => "Localisation de l'entité",
            '::progress' => 'Chargement en cours...',
            '::ObjectHaveUpdate' => 'Cet objet a une mise à jour.',
            '::DownloadObject' => "Télécharger l'objet",
            '::DeleteObject' => "Supprimer l'objet",
            '::UnprotectObject' => "Déprotéger l'objet",
            '::ProtectObject' => "Protéger l'objet",
            '::ProtectionOfObject' => "Protection de l'objet",
            '::ShareProtectObject' => "Partager la protection de l'objet",
            '::WarningSharedProtection' => "Lorsque la protection d'un objet est partagée, son annulation est incertaine !",
            '::RemoveShareProtect' => 'Annuler le partager de protection',
            '::ProtectedObject' => 'Cet objet est protégé.',
            '::WarningProtectObject' => "La protection d'un objet déjà existant est incertaine !",
            '::UnprotectedObject' => "Cet objet n'est pas protégé.",
            '::NoEntity' => "Pas d'entité à afficher.",
            '::NoEntityGroup' => "Pas de groupe d'entité à afficher.",
            '::UniqueID' => 'Identifiant universel : %s',
            '::UploadedNewFile' => 'Nouveau fichier envoyé',
            '::UploadedNewFileOK' => 'Transfert réussi.',
            '::UploadedNewFileError' => 'Transfert échoué !',
            '::AddBy' => 'Ajouté par',
            '::NewEntityCreated' => 'Nouvelle entité créée',
            '::PublID' => 'Identifiant public',
            '::PrivID' => 'Identifiant privé',
            '::ProtectedID' => 'Identifiant objet protégé',
            '::UnprotectedID' => 'Identifiant objet non protégé',
            '::CreateEntity' => "Créer l'entité",
            '::Prenom' => 'Prénom',
            '::Nom' => 'Nom',
            '::Nommage' => 'Nommage',
            '::Confirmation' => 'Confirmation',
            '::CreateAnEntity' => 'Créer une entité',
            '::CreateTheGroup' => 'Créer le groupe',
            '::GroupeFerme' => 'Groupe fermé',
            '::GroupeOuvert' => 'Groupe ouvert',
            '::CreatedGroup' => 'Groupe créé',
            '::OKCreateGroup' => 'Le groupe a été créé.',
            '::NOKCreateGroup' => "Le groupe n'a pas été créé ! %s",
            '::DeleteGroup' => 'Supprimer le groupe',
            '::AddToGroup' => 'Ajouter au groupe',
            '::RemoveFromGroup' => 'Retirer du groupe',
            '::NoGroupMember' => "L'entité n'est pas membre d'un groupe.",
            '::GroupList' => 'Liste des groupes',
            //'::UploadFile']='Transfert de fichier',
            '::SelectUploadFile' => 'Sélectionner un fichier à envoyer',
            '::SubmitProtectFile' => 'Protéger le fichier après le transfert',
            '::SubmitLiveTimeFile' => 'Durée de vie du fichier',
            '::RenewLiveTimeFile' => 'Renouveler la durée de vie',
            '::SubmitShowTimeFile' => 'Nombre de visualisation du fichier',
            '::LiveTime' => 'Durée de vie',
            '::ShowTime' => 'Nombre de vues',
            '::SubmitFile' => 'Envoyer',
            '::UploadMaxFileSize' => 'Taille maximum du fichier accepté',
            '::1h' => '1 heure',
            '::2h' => '2 heures',
            '::1d' => '1 jour',
            '::2d' => '2 jours',
            '::1w' => '1 semaine',
            '::2w' => '2 semaines',
            '::1m' => '1 mois',
            '::2m' => '2 mois',
            '::1y' => '1 an',
            '::2y' => '2 ans',
            '::Unlimited' => 'illimité',
            '::Expired' => 'Expiré',
            '::Limit' => 'Limite',
            '::ExpireIn' => 'Expire dans',
            '::and' => 'et',
            '::Second' => 'seconde',
            '::Seconds' => 'secondes',
            '::Minute' => 'minute',
            '::Minutes' => 'minutes',
            '::Hour' => 'heure',
            '::Hours' => 'heures',
            '::Day' => 'jour',
            '::Days' => 'jours',
            '::Month' => 'mois',
            '::Months' => 'mois',
            '::Year' => 'an',
            '::Years' => 'ans',
            '::ObjectList' => 'Liste des objets',
            '::AllNotDisplayed' => 'Les objets protégés ne sont pas accessibles.',
            '::NoFile' => "Pas d'objet à afficher !",
            '::EmptyList' => 'Liste vide.',
            '::NoRecoveryEntity' => "Pas d'entité de recouvrement des objets protégés sur ce serveur.",
            '::ProtectionRecovery' => 'Recouvrement des objets protégés',
            '::MarkAdd' => 'Marquer',
            '::MarkRemove' => 'Démarquer',
            '::MarkRemoveAll' => 'Démarquer tout',
            '::::display:content:errorBan' => 'Cet objet est banni, il ne peut pas être affiché !',
            '::::display:content:warningTaggedWarning' => 'Cet objet est marqué comme dangereux, attention à son contenu !',
            '::::display:content:ObjectProctected' => 'Cet objet est protégé !',
            '::::display:content:warningObjectProctected' => 'Cet objet est marqué comme protégé, attention à la divulgation de son contenu en public !!!',
            '::::display:content:OK' => 'Cet objet est valide, son contenu a été vérifié.',
            '::::display:content:warningTooBig' => "Cet objet est trop volumineux, son contenu n'a pas été vérifié !",
            '::::display:content:errorNotDisplayable' => 'Cet objet ne peut pas être affiché !',
            '::::display:content:errorNotAvailable' => "Cet objet n'est pas disponible, il ne peut pas être affiché !",
            '::::display:content:errorNotAvailableS' => "Cet objet n'est pas disponible !",
            '::::display:content:ObjectHaveUpdate' => 'Cet objet a été mis à jour vers :',
            '::::warn_ServNotPermitWrite' => "Ce serveur n'autorise pas les modifications.",
            '::::warn_flushSessionAndCache' => "Toutes les données de connexion ont été effacées.",
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::display:object:flag:protected' => 'Cet objet est protégé.',
            '::::display:object:flag:unprotected' => "Cet objet n'est pas protégé.",
            '::::display:object:flag:obfuscated' => 'Cet objet est dissimulé.',
            '::::display:object:flag:unobfuscated' => "Cet objet n'est pas dissimulé.",
            '::::display:object:flag:locked' => 'Cet entité est déverrouillée.',
            '::::display:object:flag:unlocked' => 'Cet entité est verrouillée.',
            Application::REFERENCE_OBJECT_TEXT => 'Texte brute',
            'application/x-pem-file' => 'Entité',
            'image/jpeg' => 'Image JPEG',
            'image/png' => 'Image PNG',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'Page HTML',
            'application/x-php' => 'Code PHP',
            'text/x-php' => 'Code PHP',
            'text/css' => 'Feuille de style CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Chiffré',
            'application/x-encrypted/aes-256-ctr' => 'Chiffré',
            'application/x-folder' => 'Dossier',
            '::Citation' => "<i>\"Une donnée que l'on transmet à autrui, c'est une donnée sur laquelle on perd irrémédiablement tout contrôle.\"</i>",
        ],
        'en-en' => [
            '::Welcome' => 'Welcome to <i>klicty</i>.',
            #'::PleaseSelectEntity']='Select an entity<br />or create a new one:',
            '::Version' => 'Version',
            '::About' => 'About',
            '::Help' => 'Help',
            '::menu' => 'Menu',
//        '::::err_NotPermit' => 'Unauthorized!',
            ':::Flush' => 'Flush',
            '::Share' => 'Share',
            '::Protection' => 'Protection',
            '::TimeLimited' => 'Time limited',
            '::AboutMessage' => "This is a <b>open and protected space</b> to <b>share information</b> with <b>limit in time</b>.<br />
<br />
<h2>&gt; Share</h2>
Files uploaded become objects. As objects, they are strictly identified and there contents are completly locked. Thus, they can be stored and shared with high reliability.<br />
<br />
<h2>&gt; Protection</h2>
Objects can be publics ou protected. Protected objects have strong cryptography to leave them permanently unreadeable for unauthorized people.<br />
<br />
<h2>&gt; Time limited</h2>
All the shared objects have a limited time to live on this server. They are removed at the end.<br />
The time to live is a limit imposed specifically on <i>klicty</i>.",
            '::HelpMessage' => "All is based on the 'projet <i>nebule</i>' and his way specifically to manage objets.<br /><br />Help in progess...",
            '::HelpRecoveryEntity' => "The recovery entities can, if needed, unprotect an object.<br />
This can be a security choise in an enterprise or legal contraint in some contries.<br />
<br />
Whatever the entity that protect an object, the protection is automatically and silently shared with recovery entities.<br />
All recovery entities are displayed here, none are hidden.",
            ':::SelectLanguage' => 'Select language',
            ':::Language:fr-fr' => 'Français (France)',
            ':::Language:en-en' => 'English (England)',
            ':::Language:es-co' => 'Español (Colombia)',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Message',
            '::::WARN' => 'WARNING!',
            '::::ERROR' => 'ERROR!',
            '::::HtmlHeadDescription' => 'Open and protected space to share information with limit in time.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::SecurityChecks' => 'Security checks',
            '::ObjectsList' => 'List of objects',
            '::ObjectAdd' => 'Add an object',
            '::EntitiesList' => 'List of entities',
            '::EntitiesGroup' => 'Group of entities',
            '::EntitiesGroupList' => 'List groups of entities',
            '::EntitiesGroupAdd' => 'Add a group of entities',
            '::EntityAdd' => 'Create an entity',
            '::EntityAddError' => 'Create entity error!',
            '::EntitySync' => 'Synchronize an entity',
            '::::entity:locked' => 'Entity locked. Unlock?',
            '::::entity:unlocked' => 'Entity unlocked. Lock?',
            '::::lock' => 'Lock',
            '::::unlock' => 'Unlock',
            #'::ListOrAddObject']='List my objects or add a new one:',
            '::OKConnect' => 'Login successful!',
            '::ConnectWith' => 'Connect with this entity',
            '::SynchronizeEntity' => 'Synchronize entity',
            '::ShowObjectsOf' => "See entity's objects",
            '::ShowPublicObjectsOf' => "See entity's publics objects",
            '::URL' => 'URL',
            '::EntityLocalisation' => "Entity's Localisation",
            '::progress' => 'In progress...',
            '::ObjectHaveUpdate' => 'This object have an update.',
            '::DownloadObject' => 'Download the object',
            '::DeleteObject' => 'Delete the object',
            '::UnprotectObject' => 'Unprotect the object',
            '::ProtectObject' => 'Protect the object',
            '::ProtectionOfObject' => 'Protection of the object',
            '::ShareProtectObject' => 'Share protection of the object',
            '::WarningSharedProtection' => 'When protection of an object is shared, its cancellation is uncertain!',
            '::RemoveShareProtect' => 'Cancel share protection',
            '::ProtectedObject' => 'This object is protected.',
            '::WarningProtectObject' => 'The protection of an existing object is uncertain!',
            '::UnprotectedObject' => 'This object is not protected.',
            '::NoEntity' => 'No entity to display.',
            '::NoEntityGroup' => 'No group of entity to display.',
            '::UniqueID' => 'Universal identifier : %s',
            '::UploadedNewFile' => 'New file send',
            '::UploadedNewFileOK' => 'Upload successful.',
            '::UploadedNewFileError' => 'Upload failed!',
            '::AddBy' => 'Added by',
            '::NewEntityCreated' => 'New entity created',
            '::PublID' => 'Public identifier',
            '::PrivID' => 'Private identifier',
            '::ProtectedID' => 'Protected object identifier',
            '::UnprotectedID' => 'Unprotected object identifier',
            '::CreateEntity' => 'Create entity',
            '::Prenom' => 'First name',
            '::Nom' => 'Name',
            '::Nommage' => 'Naming',
            '::Confirmation' => 'Confirmation',
            '::CreateAnEntity' => 'Create an entity',
            '::CreateTheGroup' => 'Create the group',
            '::GroupeFerme' => 'Closed group',
            '::GroupeOuvert' => 'Opened group',
            '::CreatedGroup' => 'Created group',
            '::OKCreateGroup' => 'The group have been created.',
            '::NOKCreateGroup' => 'The group have not been created! %s',
            '::DeleteGroup' => 'Delete the group',
            '::AddToGroup' => 'Add to group',
            '::RemoveFromGroup' => 'Remove from group',
            '::NoGroupMember' => 'This entity is member of any group.',
            '::GroupList' => 'List of groups',
            //'::UploadFile']='File upload',
            '::SelectUploadFile' => 'Select file to upload',
            '::SubmitProtectFile' => 'Protect file after upload',
            '::SubmitLiveTimeFile' => 'Time to live of file',
            '::RenewLiveTimeFile' => 'Renew the time to live',
            '::SubmitShowTimeFile' => 'Display count of file',
            '::LiveTime' => 'Time to live',
            '::ShowTime' => 'Display count',
            '::SubmitFile' => 'Upload',
            '::UploadMaxFileSize' => 'Maximum autorized file size',
            '::1h' => '1 hour',
            '::2h' => '2 hours',
            '::1d' => '1 day',
            '::2d' => '2 days',
            '::1w' => '1 week',
            '::2w' => '2 weeks',
            '::1m' => '1 mouth',
            '::2m' => '2 mouths',
            '::1y' => '1 year',
            '::2y' => '2 years',
            '::Unlimited' => 'unlimited',
            '::Expired' => 'Expired',
            '::Limit' => 'Limit',
            '::ExpireIn' => 'Expire in',
            '::and' => 'and',
            '::Second' => 'second',
            '::Seconds' => 'seconds',
            '::Minute' => 'minute',
            '::Minutes' => 'minutes',
            '::Hour' => 'hour',
            '::Hours' => 'hours',
            '::Day' => 'day',
            '::Days' => 'days',
            '::Month' => 'mouth',
            '::Months' => 'mouths',
            '::Year' => 'year',
            '::Years' => 'years',
            '::ObjectList' => 'Objects list',
            '::AllNotDisplayed' => 'Protected objects are not be displayed.',
            '::NoFile' => 'No object to display!',
            '::EmptyList' => 'Empty list.',
            '::NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::ProtectionRecovery' => 'Recovery of protected objects',
            '::MarkAdd' => 'Mark',
            '::MarkRemove' => 'Unmark',
            '::MarkRemoveAll' => 'Unmark all',
            '::::display:content:errorBan' => "This object is banned, it can't be displayed!",
            '::::display:content:warningTaggedWarning' => "This object is marked as dangerous, be carfull with it's content!",
            '::::display:content:ObjectProctected' => "This object is marked as protected!",
            '::::display:content:warningObjectProctected' => "This object is marked as protected, be careful when it's content is displayed in public!",
            '::::display:content:OK' => "This object is valid, it's content have been checked!",
            '::::display:content:warningTooBig' => "This object is too big, it's content have not been checked!",
            '::::display:content:errorNotDisplayable' => "This object can't be displayed!",
            '::::display:content:errorNotAvailable' => "This object is not available, it can't be displayed!",
            '::::display:content:errorNotAvailableS' => "This object is not available!",
            '::::display:content:ObjectHaveUpdate' => 'This object have been updated to:',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications.',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed.',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::display:object:flag:protected' => 'This object is protected.',
            '::::display:object:flag:unprotected' => 'This object is not protected.',
            '::::display:object:flag:obfuscated' => 'This object is obfuscated.',
            '::::display:object:flag:unobfuscated' => 'This object is not obfuscated.',
            '::::display:object:flag:locked' => 'This entity is unlocked.',
            '::::display:object:flag:unlocked' => 'This entity is locked.',
            Application::REFERENCE_OBJECT_TEXT => 'RAW text',
            'application/x-pem-file' => 'Entity',
            'image/jpeg' => 'JPEG picture',
            'image/png' => 'PNG picture',
            'application/x-bzip2' => 'Archive BZIP2',
            'text/html' => 'HTML page',
            'application/x-php' => 'PHP code',
            'text/x-php' => 'PHP code',
            'text/css' => 'Cascading Style Sheet CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encrypted',
            'application/x-encrypted/aes-256-ctr' => 'Encrypted',
            'application/x-folder' => 'Folder',
            '::Citation' => '<i>\"A data we share with someone, this is a data on while we definitively lose all control.\"</i>',
        ],
        'es-co' => [
            '::Welcome' => 'Bienvenido en <i>klicty</i>.',
            #'::PleaseSelectEntity']='Select an entity<br />or create a new one:',
            '::Version' => 'Version',
            '::About' => 'About',
            '::Help' => 'Help',
            '::menu' => 'Menu',
//        '::::err_NotPermit' => 'Unauthorized!',
            ':::Flush' => 'Flush',
            '::Share' => 'Share',
            '::Protection' => 'Protection',
            '::TimeLimited' => 'Time limited',
            '::AboutMessage' => "This is a <b>open and protected space</b> to <b>share information</b> with <b>limit in time</b>.<br />
<br />
<h2>&gt; Share</h2>
Files uploaded become objects. As objects, they are strictly identified and there contents are completly locked. Thus, they can be stored and shared with high reliability.<br />
<br />
<h2>&gt; Protection</h2>
Objects can be publics ou protected. Protected objects have strong cryptography to leave them permanently unreadeable for unauthorized people.<br />
<br />
<h2>&gt; Time limited</h2>
All the shared objects have a limited time to live on this server. They are removed at the end.<br />
The time to live is a limit imposed specifically on <i>klicty</i>.",
            '::HelpMessage' => "All is based on the 'projet <i>nebule</i>' and his way specifically to manage objets.<br /><br />Help in progess...",
            '::HelpRecoveryEntity' => "The recovery entities can, if needed, unprotect an object.<br />
This can be a security choise in an enterprise or legal contraint in some contries.<br />
<br />
Whatever the entity that protect an object, the protection is automatically and silently shared with recovery entities.<br />
All recovery entities are displayed here, none are hidden.",
            ':::SelectLanguage' => 'Select language',
            ':::Language:fr-fr' => 'Français (France)',
            ':::Language:en-en' => 'English (England)',
            ':::Language:es-co' => 'Español (Colombia)',
            '::::INFO' => 'Information',
            '::::OK' => 'OK',
            '::::INFORMATION' => 'Mensaje',
            '::::WARN' => '¡ADVERTENCIA!',
            '::::ERROR' => '¡ERROR!',
            '::::HtmlHeadDescription' => 'Open and protected space to share information with limit in time.',
            '::::Experimental' => '[Experimental]',
            '::::Developpement' => '[Under developpement]',
            '::::SecurityChecks' => 'Controles de seguridad',
            '::ObjectsList' => 'List of objects',
            '::ObjectAdd' => 'Add an object',
            '::EntitiesList' => 'List of entities',
            '::EntitiesGroup' => 'Group of entities',
            '::EntitiesGroupList' => 'List groups of entities',
            '::EntitiesGroupAdd' => 'Add a group of entities',
            '::EntityAdd' => 'Create an entity',
            '::EntityAddError' => 'Create  entity error!',
            '::EntitySync' => 'Synchronize an entity',
            '::::entity:locked' => 'Entidad bloqueada. Desbloquear?',
            '::::entity:unlocked' => 'Entidad desbloqueada. Bloquear?',
            '::::lock' => 'Lock',
            '::::unlock' => 'Unlock',
            #'::ListOrAddObject']='List my objects or add a new one:',
            '::OKConnect' => 'Login successful!',
            '::ConnectWith' => 'Connect with this entity',
            '::SynchronizeEntity' => 'Synchronize entity',
            '::ShowObjectsOf' => "See entity's objects",
            '::ShowPublicObjectsOf' => "See entity's publics objects",
            '::URL' => 'URL',
            '::EntityLocalisation' => "Entity's Localisation",
            '::progress' => 'In progress...',
            '::ObjectHaveUpdate' => 'This object have an update.',
            '::DownloadObject' => 'Download the object',
            '::DeleteObject' => 'Delete the object',
            '::UnprotectObject' => 'Unprotect the object',
            '::ProtectObject' => 'Protect the object',
            '::ProtectionOfObject' => 'Protection of the object',
            '::ShareProtectObject' => 'Share protection of the object',
            '::WarningSharedProtection' => 'Donde se comparte la protección de un objeto, su cancelación esta incierto!',
            '::RemoveShareProtect' => 'Cancel share protection',
            '::ProtectedObject' => 'This object is protected.',
            '::WarningProtectObject' => 'The protection of an existing object is uncertain!',
            '::UnprotectedObject' => 'This object is not protected.',
            '::NoEntity' => 'No entity to display.',
            '::NoEntityGroup' => 'No group of entity to display.',
            '::UniqueID' => 'Universal identifier : %s',
            '::UploadedNewFile' => 'New file send',
            '::UploadedNewFileOK' => 'Upload successful.',
            '::UploadedNewFileError' => 'Upload failed!',
            '::AddBy' => 'Added by',
            '::NewEntityCreated' => 'New entity created',
            '::PublID' => 'Public identifier',
            '::PrivID' => 'Private identifier',
            '::ProtectedID' => 'Protected object identifier',
            '::UnprotectedID' => 'Unprotected object identifier',
            '::CreateEntity' => 'Create entity',
            '::Prenom' => 'First name',
            '::Nom' => 'Name',
            '::Nommage' => 'Naming',
            '::Confirmation' => 'Confirmation',
            '::CreateAnEntity' => 'Create an entity',
            '::CreateTheGroup' => 'Create the group',
            '::GroupeFerme' => 'Closed group',
            '::GroupeOuvert' => 'Opened group',
            '::CreatedGroup' => 'Created group',
            '::OKCreateGroup' => 'The group have been created.',
            '::NOKCreateGroup' => 'The group have not been created! %s',
            '::DeleteGroup' => 'Delete the group',
            '::AddToGroup' => 'Add to group',
            '::RemoveFromGroup' => 'Remove from group',
            '::NoGroupMember' => 'This entity is member of any group.',
            '::GroupList' => 'List of groups',
            //'::UploadFile']='File upload',
            '::SelectUploadFile' => 'Select file to upload',
            '::SubmitProtectFile' => 'Protect file after upload',
            '::SubmitLiveTimeFile' => 'Time to live of file',
            '::RenewLiveTimeFile' => 'Renew the time to live',
            '::SubmitShowTimeFile' => 'Display count of file',
            '::LiveTime' => 'Time to live',
            '::ShowTime' => 'Display count',
            '::SubmitFile' => 'Upload',
            '::UploadMaxFileSize' => 'Maximum autorized file size',
            '::1h' => '1 hour',
            '::2h' => '2 hours',
            '::1d' => '1 day',
            '::2d' => '2 days',
            '::1w' => '1 week',
            '::2w' => '2 weeks',
            '::1m' => '1 mouth',
            '::2m' => '2 mouths',
            '::1y' => '1 year',
            '::2y' => '2 years',
            '::Unlimited' => 'unlimited',
            '::Expired' => 'Expired',
            '::Limit' => 'Limit',
            '::ExpireIn' => 'Expire in',
            '::and' => 'and',
            '::Second' => 'second',
            '::Seconds' => 'seconds',
            '::Minute' => 'minute',
            '::Minutes' => 'minutes',
            '::Hour' => 'hour',
            '::Hours' => 'hours',
            '::Day' => 'day',
            '::Days' => 'days',
            '::Month' => 'mouth',
            '::Months' => 'mouths',
            '::Year' => 'year',
            '::Years' => 'years',
            '::ObjectList' => 'Objects list',
            '::AllNotDisplayed' => 'Protected objects are not be displayed.',
            '::NoFile' => 'No object to display!',
            '::EmptyList' => 'Empty list.',
            '::NoRecoveryEntity' => 'No recovery entity for protected objects on this server.',
            '::ProtectionRecovery' => 'Recovery of protected objects',
            '::MarkAdd' => 'Mark',
            '::MarkRemove' => 'Unmark',
            '::MarkRemoveAll' => 'Unmark all',
            '::::display:content:errorBan' => "This object is banned, it can't be displayed!",
            '::::display:content:warningTaggedWarning' => "This object is marked as dangerous, be carfull with it's content!",
            '::::display:content:ObjectProctected' => "This object is marked as protected!",
            '::::display:content:warningObjectProctected' => "This object is marked as protected, be careful when it's content is displayed in public!",
            '::::display:content:OK' => "This object is valid, it's content have been checked!",
            '::::display:content:warningTooBig' => "This object is too big, it's content have not been checked!",
            '::::display:content:errorNotDisplayable' => "This object can't be displayed!",
            '::::display:content:errorNotAvailable' => "This object is not available, it can't be displayed!",
            '::::display:content:errorNotAvailableS' => "This object is not available!",
            '::::display:content:ObjectHaveUpdate' => 'This object have been updated to:',
            '::::warn_ServNotPermitWrite' => 'This server do not permit modifications.',
            '::::warn_flushSessionAndCache' => 'All datas of this connexion have been flushed',
            '::::err_NotPermit' => 'Non autorisé sur ce serveur !',
            '::::act_chk_errCryptHash' => "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptHashkey' => "La taille de l'empreinte cryptographique est trop petite !",
            '::::act_chk_errCryptHashkey' => "La taille de l'empreinte cryptographique est invalide !",
            '::::act_chk_errCryptSym' => "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est trop petite !",
            '::::act_chk_errCryptSymkey' => "La taille de clé de chiffrement cryptographique symétrique est invalide !",
            '::::act_chk_errCryptAsym' => "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !",
            '::::act_chk_warnCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est trop petite !",
            '::::act_chk_errCryptAsymkey' => "La taille de clé de chiffrement cryptographique asymétrique est invalide !",
            '::::act_chk_errBootstrap' => "L'empreinte cryptographique du bootstrap est invalide !",
            '::::act_chk_warnSigns' => 'La vérification des signatures de liens est désactivée !',
            '::::act_chk_errSigns' => 'La vérification des signatures de liens ne fonctionne pas !',
            '::::display:object:flag:protected' => 'Este objeto está protegido.',
            '::::display:object:flag:unprotected' => 'Este objeto no está protegido.',
            '::::display:object:flag:obfuscated' => 'Este objeto está oculto.',
            '::::display:object:flag:unobfuscated' => 'Este objeto no está oculto.',
            '::::display:object:flag:locked' => 'Esta entidad está desbloqueada.',
            '::::display:object:flag:unlocked' => 'Esta entidad está bloqueada.',
            Application::REFERENCE_OBJECT_TEXT => 'Texto en bruto',
            'application/x-pem-file' => 'Entidad',
            'image/jpeg' => 'JPEG gráfico',
            'image/png' => 'PNG gráfico',
            'application/x-bzip2' => 'Archivo BZIP2',
            'text/html' => 'Página HTML',
            'application/x-php' => 'Código PHP',
            'text/x-php' => 'Código PHP',
            'text/css' => 'Hojas de estilo en cascada CSS',
            'audio/mpeg' => 'Audio MP3',
            'audio/x-vorbis+ogg' => 'Audio OGG',
            'application/x-encrypted/rsa' => 'Encriptado',
            'application/x-encrypted/aes-256-ctr' => 'Encriptado',
            'application/x-folder' => 'Archivo',
            '::Citation' => '<i>\"A data we share with someone, this is a data on while we definitively lose all control.\"</i>',
        ],
    ];
}
