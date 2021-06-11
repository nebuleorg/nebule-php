<?php
declare(strict_types=1);
namespace Nebule\Bootstrap;
//use nebule;
// ------------------------------------------------------------------------------------------
const BOOTSTRAP_NAME = 'bootstrap';
const BOOTSTRAP_SURNAME = 'nebule/bootstrap';
const BOOTSTRAP_AUTHOR = 'Project nebule';
const BOOTSTRAP_VERSION = '020210607';
const BOOTSTRAP_LICENCE = 'GNU GPL 02021';
const BOOTSTRAP_WEBSITE = 'www.nebule.org';
// ------------------------------------------------------------------------------------------



/*
 ------------------------------------------------------------------------------------------
  /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
 ------------------------------------------------------------------------------------------

 [FR] Toute modification de ce code entrainera une modification de son empreinte
      et entrainera donc automatiquement son invalidation !
 [EN] Any changes to this code will cause a change in its footprint and therefore
      automatically result in its invalidation!
 [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
      tanto lugar automáticamente a su anulación!

 ------------------------------------------------------------------------------------------
*/



/*
 ==/ Table /===============================================================================
 PART1 : Initialization of the bootstrap environment.
 PART2 : Procedural PHP library for nebule (Lib PP).
 PART3 : Manage PHP session and arguments.
 PART4 : Find and load object oriented PHP library for nebule (Lib POO).
 PART5 : Find application code.
 PART6 : Manage and display breaking bootstrap on problem or user ask.
 PART7 : Display of pre-load application web page.
 PART8 : First synchronization of code and environment.
 PART9 : Display of application 0 web page to select application to run.
 PART10 : Display of application 1 web page to display documentation of nebule.
 PART11 : Display of application 2 default application.
 PART12 : Main display router.
 ------------------------------------------------------------------------------------------
*/



// Disable display until routing choice have been made.
ob_start();



/*
 *
 *
 *
 *

 ==/ 1 /===================================================================================
 PART1 - Initialization of the bootstrap environment.

 This part include all default values for the procedural library (Lib PP).
 ------------------------------------------------------------------------------------------
 */

// Logs setting and initializing.
$loggerSessionID = bin2hex(openssl_random_pseudo_bytes(6, $false));
$metrologyStartTime = microtime(true);

/**
 * Switch log prefix from one app to another.
 * @param string $name
 * @return void
 */
function initLog(string $name): void
{
    global $loggerSessionID;
    openlog($name . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
}

/**
 * Switch log prefix from one app to another.
 * @param string $name
 * @return void
 */
function reopenLog(string $name): void
{
    closelog();
    initLog($name);
}

/**
 * Add message to logs.
 * @param string $message
 * @param string $level
 * @param string $function
 * @param string $luid
 * @return void
 */
function addLog(string $message, string $level='msg', string $function='', string $luid='00000000'): void
{
    global $metrologyStartTime;
    syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL="' . $level . '" LogI="' . $luid . '" LogF="' . $function . '" LogM="' . $message . '"');
}

// Initialize logs.
initLog(BOOTSTRAP_NAME);
syslog(LOG_INFO, 'LogT=0 LogT0=' . $metrologyStartTime . ' LogL=B LogM="start ' . BOOTSTRAP_NAME .'"');

// ------------------------------------------------------------------------------------------
// Command line args.
const ARG_BOOTSTRAP_BREAK = 'b';
const ARG_FLUSH_SESSION = 'f';
const ARG_UPDATE_APPLICATION = 'u';
const ARG_SWITCH_APPLICATION = 'a';
const ARG_RESCUE_MODE = 'r';
const ARG_INLINE_DISPLAY = 'i';
const ARG_STATIC_DISPLAY = 's'; // TODO not used yet

const REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP = 'nebule/objet/interface/web/php/bootstrap';
const REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE = 'nebule/objet/interface/web/php/bibliotheque';
const REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS = 'nebule/objet/interface/web/php/applications';
const REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_DIRECT = 'nebule/objet/interface/web/php/applications/direct';
const REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_ACTIVE = 'nebule/objet/interface/web/php/applications/active';

const REFERENCE_BOOTSTRAP_ICON = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAARoElEQVR42u2dbbCc
ZX2Hrz1JMBSUAoEECCiVkLAEEgKkSBAwgFgGwZFb6NBOSVJn0H5peXXqSwVrdQZF6Cd0RiQ4kiH415ZpOlUECpKABBLewkIAayFAAgRCGxBylJx+2KdDwHCS
7Ov9PHtdMzt8IGf32f/ev+v+388riIiIiIiIiIiIiIhINalZgvKTUtoTmFy89gUmAXsDewC7F69dgV2AnYGdgLFb/P4jwO+BYeAN4HVgI/AqsAF4BXgRWAc8
DzwLrImIV6y+ApDehHx/4LDiVQemAR8G9uzzpr0M/Bp4HGgAjwCPRMQafzUFIK2F/XDgOGB28TrkXf9kJMPfbmvb9BiwvHgtjYiH/XUVgLwz7GOBU4GTgZOK
GT7noLcrhkeA24BbgVsi4neOAgUwaKE/ETgTOB04aMDL8RSwBLg5Iu5wdCiAKgb+j4BzgXOKmV7em1uBG4FFEfGG5VAAZQ39B4AFwHnAzAq19L1cMjwA/BC4
NiI2WhoFUIbg/zXwOeAoQ99RGdwPfDcirrUsCiC30M8BLgQ+beh7IoOfAFdFxDLLogD6FfoacBFwCc2Tbgx972XwInAF8J2IGLEsCqAXwd8fuByY72yfVVdw
HfBVT0BSAN0K/mzgm8Bcq5E1twN/HxHLLYUC6ETw5wJXAYc725eqK3gYuCAibrckCqCV4J8IXEPznHuDX14RPA583pOMFMD2Bn9msZ6cafArJYIHgfkR8aAl
UQBbC/5EYCHwCYNfaRH8DJgXES9YEhiyBJBS+mea17qfqhgrP9mdCqxLKV1tSQZ8oKeU/qJo98c5FAaSYWBBRNygAAYr+PvRvALNdb7LghrNaw4+GRHPuQSo
fvgvp3lLqxl2QXbAxX9nAs+mlC6zA6hu8A+meSOKyY57GYVngZMi4gk7gOqE/zJgNbCf41u2wX7A6kHpBmoVD/6ewDJgquNaWmA1cGyV7348VOHwnw2sBw52
HEuLHAy8XIwlBVCi8N8ALMY9/NJ+hzwCLC7GlEuAzIM/geYhHXf0STdYA8yKiPV2APmF/3jgJdzRJ91jMvBSMdYUQEbh/wJwpy2/9GhJcGdK6VKXAHmE/ybg
M45N6QM/joizFUB/gl8DHuKdT9IR6TUPAzPLek/CWknDvxvNY7R72/JLnxkBXgCmRsT/KoDuh38y8AQw3vBLRhJ4E5hStguKhkoW/jrwjOGXDCfS8cCalNIh
CqA74T8SeLTMSxepvAQAGimlWQqgs+E/huajoTzMJ7lLYARYkVL6U/cBdC789xh+Kdk+gRpwTETcqwDaa/ud+aXMEjgqIlYogB0Pf71Y8xt+KbsE6hHxmALY
/vBPprm3H8MvFZAAwAER8awC2Hb4P0DzxIr3GX6pkAQ2ARNzO1loKLPw12ie4Wf4pUrUijG9uhjjCuA9eACYaPilohKYWIxxBbCV2X8xzVt1G36psgRmpJRu
zGWDxmQS/i8Af+v4kAFher1e/22j0bg7ByP1O/zH07yZh8igcXxE3DWwAiju4fcSHuuXweP/x/yEiHh5UPcBrDD8MsD7A0aAlf3ciL4JoLjN8gGGXwZcAgek
lH7Urw0Y06fwnwNc7u8vAsDh9Xr9sUaj8Wjl9wEUj+tab+sv0v/9Af1YAtxt+EW2uj9gWaWXAMUTV88y/CJblcCEer1Oo9G4s5cf2qvwT6F5M08RGZ0pEfFU
1ZYAt/H2pZEi8t77A26v1BKgaP3PyKH1r9VcfViLrOtRA3br1VKg1oPwT6b5VNVsmDBhAuvXr3fEA6eddhrjx48f+DoMDw+zZMmS3DZrcrefMzC2B1/i38hs
r/+JJ57I0qVLWbdu3cAP/HHjxjFu3Dj77pHsVqcjRXa6eovxru4DSCmdC8wks73+b731FieccAKTJk2yBZBsVyTAEUWGyikAYCEZ7/hTApJ7Y1JkqHwCSCld
DYwj82P+SkAy7wLGpZSuKpUAUkoTKdENPpSAZM7fFZkqTQdwHSU75q8EJPOlwA9KIYCU0gzgzyjh6b5KQDJeCpxWZCv7DqB0s78SkEHtAjoqgOL+fkdQ8ot9
lIBk2gXMKjKWbQfwPSpyvr8SkEy7gO9mKYCU0seAaVToUl8lIBl2AYeklE7MsQO4igpe7acEJMMu4OqsBJBSmk2Fn+qjBCSzLmBGkblsOoBvUvFr/ZWAZNYF
fCMLAaSU9gfmMgC3+VICklEXcFJxqX3fO4DLBqnySkAy4vK+CqB41vmCQau6EpBMWNBXAQAXDmrllYDkQErpon4K4FIG+EafSkD6zAhwSV8EkFL6CLA3A36P
fyUgfaQGTEwpHdOPDuBCvM23EpAcuoAL+yGAhE/4UQKSQxfwmZ4KIKU037orAcmHlNK8XnYAn7P9VwKS1TLg8z0RQEppV2C27b8SkKyWAbNTSrv0ogNYYL2V
gGTJgl4IYJ7tvxKQLJcB87oqgJTSeCpwyy8lIBVdBswqMtq1DuBc66wEJGvO7aYA/tz2XwlI1suAs7spgFNs/5WAZL0MOLUrAujkjQiVgBKQ7rEjtw7fkQ7g
DEurBKQUnNkNAXzSuioBKQWnd1QAKaWxwEHWVQlIKTg4pTSmkx3AKdZUCUip+HgnBXCy9VQCUirmdlIAJ1lPJSCl4uROCmCG9VQCUipmdkQAKaVDraUSkPKR
UjqsEx3AcZZSCUgpmdMJARyD5/8rASkbI0V22xbAUXj+vxKQslEDju6EAKZbSyUgpaTelgBSSvtZQyUg5SWltG87HYCH/5SAlJsZ7QjAQ4BKQMrN9HYEUMcj
AEpAysrItvYDbEsA0/AIgBKQslIDprYjgCnWUAlIqZnSjgD2tH5KQErNhJYEkFLa3dopASk/KaU/bqUDOMDSKQGpBB9sRQCTrZsSkEowuRUBeBagEpBqsG8r
Apho3ZSAVIKJrQhgb+umBKT0jIyW5dEE4CFAJSDlpzZalkcTgIcBlYBUg91bEcBu1k0JSCVo6TyA91s3JSCVYNdWBLCLdVMCUgl2aUUA462bEpBKsHMrAtjJ
uikBqQQ7tSKAcdZNCUglGNuKAIasW7UlsNdeezE05M88AAwZcvkD5syZw1133WUhNMNW2Wx5qs3IyAjr16/njjvusBjVZnMrAviddRsMXnjhBSVQbX7figCG
rZsSkEow3IoA3rRuSkAqwRutCOB166YEpBK83ooANlo3JSCV4LVWBPA/1k0JSCV4tRUBbLBuSkAJVIINrQjgZesmSqD0jIyW5dEE8KK1EyVQemqjZXk0Aayz
dqIEKsG6VgTwvHUTJVAJ1rYigGetmyiBSrCmFQE8Y91ECVSCZ3ZYABHhYUBRAhUgIlo6DwBgveUTJVBqXhrtf25LAE9aP1ECpeapdgSwmuaJBCJKoHyMAI+3
I4AGzRMJRJRA+agVGW5ZAKusoSiBUrOqHQE8ZP1ECZSah1oWQER4NqAogRITEWvb6QBcBogSKC+Nbf2D7RHAfXgkQJRA2RgBlndCAL/CIwGiBMpGrchu2wJY
Zi1FCZSSZW0LICIetY6iBMpHRKxqWwAFD1pOUQKl4oHt+UfbK4BbracogVJxWycFcLv1FCUwuAK4xXqKEigVt3RMABHxFvCENRUlUApWR8TmTnYAAEusqyiB
UrDdWd0RAdxsXUUJlIJ/7bgAIuKX1lWUQP5ExNJudAAAP8frAkQJ5MoI8LMd+YMdFcBivC5AlECu1IqMdk0Ai6yxKIGsWdQ1AUTEJmCFywBRAlm2//dHxHA3
OwCAhS4DRAlk2f4v3NE/akUAP7DWogSy5LquCyAifgvc6zJAlEBW7f+vimx2vQMAuMZlgCiBrNr/a1r5w5YEEBHXW3NRAvkQET/smQAKfuwyQJRAFu3/Ta3+
cTsCuNJlgCiBLNr/K3sugIi4F1hnFyBKoK+z/9qIWN5zARRcYRcgSqCvs/+32nmDtgQQEVc5DEUJ9I92MzjUgW241mEoSqAvfL/dN+iEAL7qEBQl0Bcu67sA
IuI54Be4M1CUQK8YAW4pstf3DgDgS7gzUJRAr6gBX+zEG3VEABFxH80nkdgFiBLo/uy/MiJWZCOAggvsAkQJ9GT2v6BTb9YxAUTEnUDDLkCUQFdn/0Ynb9A7
1OENPN8uQJRAV2f/8zv5hh0VQHE74pV2AaIEujL7r9iRW373owMAmG8XIEqgK7P/vE6/accFEBEPA/9uFyBKoKOz/5KIWJW9AAoW2AWIEujo7L+gG2/cFQFE
xIvAdxxuogQ6wpUR8VJpBFBI4CJgk0sBUQJttf6bIuLibn3AUJe/wDyXAqIE2mr9z+vmB3RVABFxI3C/XYDkLoExY8bkOPvfFxGLu/khY3vwRc4EnsupssPD
w45865B7J1ArstP1D+k6KaWvAF/Loqq1GiMjNiTWI/tafCUivl4JARQSeBrY330CItts/Z+JiA/14sOGevjF5hp+ke2alOf26sN6tuej0WhsqNfrm4GP+RuL
jNr639xL2/SUlFIDmGY3IPIHrf9jEXFoLz90qA9fdE4Rfvc8ibwd/lqRDSotgIjYACQ7AJF3dOJnRcSrvf7gvpz90Gg0HqvX638CzPC3F+H6iPhmv8zTN1JK
/w0cYDcgA9z6Px0RB/ZrA4b6XIAjtyiEyKCFf8sMDJ4AIuJl4KO4U1AGL/w14KMR8Uo/N6TvV0A0Go019Xr9NeBUx4UMCDXg4m5f6FMKARQSuKder08FDnNs
yACwqJvX+O+oibIhpbQSOMLxIRXmgYiYlVMrQmYSeA7YB48MSPXW/WsjYr+cNmoow0JNA97EnYJSrfC/CUzNbcOyE0BEbAQOAjYrAalI+EeAD0fEawpg+yTw
PFDHw4NS/vDXgGkRsTbHDRzKtXIR8QQwUwlIycM/MyKezHUjh3KuYEQ8BMxWAlLS8B9djOFsKcWe9pTS0cDyLQorUobw35/7xpYmTCmlmcADSkBKEP4jIuLB
MmxwqYKUUpoKNIrtVgKSW/g3A4dGxOqybHTpQpRS2gf4NTBeCUhG4X8DmFIcwSoNQ2WrdHE4ZW9greNOMgn/88DEsoW/lB3Au7oBrx2QfpPVuf2V7wDe1Q3M
AhZtYWKRXs36ADeUOfyQyeXA7dBoNH66xf0EPEIgvQh/DbgoIi4p+5epTFhSSscCS6v2vSTLmf+4iLi7Cl+oUkFJKe0BrAA+5FiVLvAb4Mji1vYogHxFsBA4
zyWBdLDlXxgR86v25SobjpTSp4GfKAHpQPjPioifVvELVjoYKaXdgWXAIY5laYEGMKcfT+xRAJ0VwZeAr9sNyA7M+l+OiH+q+pcdmDCklA4E/hP4oGNcRuFp
YG5E/NcgfNmBmw1TSl8G/tFuQAZ11h9oARQS2Be4GThKERh84D7gU2U8l18BtCeCc4DrgfeZhYFkE/BXEXHToBbAma8pgiuAS+wGBmrW/1ZEXDroxXCwvy2B
CcC1wBmKoNLBvxn4bESstyQO8q2JYDqwkOZjmxVBdYK/AjgvIh61JApge0QwB/gecKgiKHXwHwXOj4hllkQBtCKC44GrgFmKoFTBXwlcEBG/tCQKoBMiOBL4
BvBxq5E1Pwe+GBErLYUC6IYI9gG+Bnz2XTOO9G+2B/g+8A+5PoJLAVRTBhcAFwP7KoK+BH8tcEVEXG1JFEA/RXA0cBFwjl1BT2b7xcC3y/DkHQUweDL4S+Bv
gI8og46G/m7gmoj4kWVRAGUQwc7AAmA+zXMKlMGOh34FcB1wbUS8aWkUQFllsBNwbrFE+IQVGZX/AG4CFkXEsOVQAFUUwnHAp4DTgakDXo7VwBLgXzxZRwEM
ogyGgFOAucDJNE84okJLhnd/h5XArcDtwC8iYrOjQAHIO6UwneZOxGOB2UB9G6HKMejQvKfecpo78O6JiFX+ugpAWpPCJGAGMJ3m9QlTgGnAhD5v2nrgceBJ
mufdrwIeioh1/moKQHojh/cDBwL70zwpaRLNJyjvAexevHYFdqF585Odildti9l7uHhtAl4HNgKvAhuAV4AXgXU0n4S7BvhNRGy0+iIiIiIiIiIiIiIiIjny
f9eV8VcbpfPFAAAAAElFTkSuQmCC";

const DEFAULT_APPLICATION_LOGO_LIGHT = "data:image/png;base64,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
Jggg==";

// Name of bootstrap file used by web server.
const BOOTSTRAP_FILE_NAME = 'index.php';



// ------------------------------------------------------------------------------------------

/**
 * Instance de la bibliothèque nebule en PHP orienté objet.
 *
 * @var nebule $nebuleInstance
 */
$nebuleInstance = null;

/**
 * Variable de raison d'interruption de chargement du bootstrap.
 */
$bootstrapBreak = array();

/**
 * Variable de détection d'affichage inserré en ligne.
 */
$bootstrapInlineDisplay = false;

/**
 * Variable de détection d'affichage de l'ID de l'entité instance du serveur.
 */
$bootstrapServerEntityDisplay = false;

/**
 * Activation d'un nettoyage de session général.
 */
$bootstrapFlush = false;

/**
 * Activation d'une mise à jour des instances de bibliothèque et d'application.
 */
$bootstrapUpdate = false;

/**
 * ID de la bibliothèque mémorisé dans la session PHP.
 */
$bootstrapLibraryID = '';

/**
 * Instance non dé-sérialisée de la bibliothèque mémorisée dans la session PHP.
 */
$bootstrapLibraryInstanceSleep = '';

/**
 * ID de l'application mémorisé dans la session PHP.
 */
$bootstrapApplicationID = '';

/**
 * ID de départ de l'application mémorisé dans la session PHP.
 */
$bootstrapApplicationStartID = '';

/**
 * Instance non dé-sérialisée de l'application mémorisée dans la session PHP.
 */
$bootstrapApplicationInstanceSleep = '';

/**
 * Instance non dé-sérialisée de l'affichage de l'application mémorisée dans la session PHP.
 */
$bootstrapApplicationDisplayInstanceSleep = '';

/**
 * Instance non dé-sérialisée des actions de l'application mémorisée dans la session PHP.
 */
$bootstrapApplicationActionInstanceSleep = '';

/**
 * Instance non dé-sérialisée des traductions de l'application mémorisée dans la session PHP.
 */
$bootstrapApplicationTraductionInstanceSleep = '';

/**
 * Commutateur pour charger directement une application sans passer par le pré-chargement.
 */
$bootstrapApplicationNoPreload = false;

/**
 * Demande de changement d'application.
 */
$bootstrapSwitchApplication = '';

/**
 * Instance de l'application.
 */
$applicationInstance = null;

/**
 * Instance d'affichage de l'application.
 */
$applicationDisplayInstance = null;

/**
 * Instance d'action de l'application.
 */
$applicationActionInstance = null;

/**
 * Instance de traduction de l'application.
 */
$applicationTraductionInstance = null;

// Metrology vars.
$metrologyLibraryPOOLinksRead = 0;
$metrologyLibraryPOOLinksVerified = 0;
$metrologyLibraryPOOObjectsRead = 0;
$metrologyLibraryPOOObjectsVerified = 0;
$metrologyLibraryPOOLinkCache = 0;
$metrologyLibraryPOOObjectCache = 0;
$metrologyLibraryPOOEntityCache = 0;
$metrologyLibraryPOOGroupCache = 0;
$metrologyLibraryPOOConvertationCache = 0;



/*
 *
 *
 *
 *

 ==/ 2 /===================================================================================
 PART2 : Procedural PHP library for nebule (Lib PP).

 TODO
 ------------------------------------------------------------------------------------------
 */

const NEBULE_LIBPP_VERSION = '020210607';
const NEBULE_LIBPP_LINK_VERSION = '2:0';
const NEBULE_DEFAULT_PUPPETMASTER_ID = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
const NEBULE_ENVIRONMENT_FILE = 'nebule.env';
const LOCAL_ENTITY_FILE = 'e';
const LOCAL_LINKS_FOLDER = 'l';
const LOCAL_OBJECTS_FOLDER = 'o';
const NEBULE_REFERENCE_NID_SECURITYMASTER = 'a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288';
const NEBULE_REFERENCE_NID_CODEMASTER = '2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288';
const NEBULE_REFERENCE_NID_TIMEMASTER = 'bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288';
const NEBULE_REFERENCE_NID_DIRECTORYMASTER = '0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288';
const NID_MIN_HASH_SIZE = 128;
const NID_MAX_HASH_SIZE = 8192;
const NID_MIN_ALGO_SIZE = 2;
const NID_MAX_ALGO_SIZE = 12;

// Constant vars for first run.
const FIRST_LOCALISATIONS = array('http://code.master.nebule.org', 'http://puppetmaster.nebule.org', 'http://security.master.nebule.org',);

const FIRST_PUPPETMASTER_PUBLIC_KEY =
'-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAudMrAyvG3uqI9JLZRtqi
nlgiF6hAp/whKWlujNXE+x0p6ibJEaIAPS+VyR4Lw9819UqObpMI2fa+Ql8/dJPM
9r7Js/eJbRy6U7+EtBJa8ZIBTRtGXjKdBhkyQcWm8TqglitTG0pIoJOlB1+CbP2W
TtfbC6ZEFBFhlEH+qqy7Laua3m2yqVXqTY9FEBPYcX/Q2qpOeep+DkMQ/UwYyCZ0
Pv7KJ0aLlbju2UpYAp+zNfl6OKo37Va29anhU1i7lfXug7h0d9Lc4Xpl+KLfKn4A
g6VHSKXRAENvCXnGG3DM7UUdHM74NQXtwKzmtEwn7KT/3MKM6ohdbffkrAJFaeby
EMCVqq9nH4CZUIOGzLsAICtA6FXD5bi0OKv1Y1fzH4MHlc8FL5fCEdJ2ZftlURDH
Z2X2dE73Tx3TuyHr3e3A2xXMxcXZ0bs41Ey9wUWPRtBfEU6Yr3yXDQjMmLeCj/Vz
0Z/92hX5zE6UDpxTbuoPSUzGH0xwwZzsLAOIM0TvOxDI1ATX8M0Di2veYdLJMqoF
QMqFriycSa9a4U4SyXomUAqj9jBzn1dmPN+cvC+2ByqoRdGKkJQZAnLcfpN+G+lt
/GJe8Xgw01QlOFGT8PV9IvZek96PociLNqoyOhye7q5/Ik0fsEEIzYW2jvLGnrkv
6dEOw+BEVa0QiNx/ju9yzHMCAwEAAQ==
-----END PUBLIC KEY-----';

/*
 * Constante du lien de hash de la clé publique puppetmaster.
 * TODO signature invalide
 */
const FIRST_PUPPETMASTER_HASH_LINK = 'nebule:link/2:0_0:20210611/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>0.rsa.4096';

/*
 * Constante du lien de type de la clé publique puppetmaster.
 * TODO signature invalide
 */
const FIRST_PUPPETMASTER_TYPE_LINK = 'nebule:link/2:0_0:20210611/l_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256_970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256_5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>0.rsa.4096';

// Minimum set of objects to create on first run.
const FIRST_RESERVED_OBJECTS = array(
    'application/x-pem-file',
    'application/octet-stream',
    'text/plain',
    'sha224',
    'sha256',
    'sha384',
    'sha512',
    'nebule/objet',
    'nebule/objet/hash',
    'nebule/objet/homomorphe',
    'nebule/objet/type',
    'nebule/objet/localisation',
    'nebule/objet/taille',
    'nebule/objet/prenom',
    'nebule/objet/nom',
    'nebule/objet/surnom',
    'nebule/objet/prefix',
    'nebule/objet/suffix',
    'nebule/objet/lien',
    'nebule/objet/date',
    'nebule/objet/entite',
    'nebule/objet/entite/localisation',
    'nebule/objet/entite/maitre',
    'nebule/objet/entite/maitre/securite',
    'nebule/objet/entite/maitre/code',
    'nebule/objet/entite/maitre/annuaire',
    'nebule/objet/entite/maitre/temps',
    'nebule/objet/entite/autorite/locale',
    'nebule/objet/entite/recouvrement',
    'nebule/objet/interface/web/php/bootstrap',
    'nebule/objet/interface/web/php/bibliotheque',
    'nebule/objet/interface/web/php/applications',
    'nebule/objet/interface/web/php/applications/modules',
    'nebule/objet/interface/web/php/applications/direct',
    'nebule/objet/interface/web/php/applications/active',
    'nebule/option',
    'nebule/danger',
    'nebule/warning',
    'nebule/reference',
);

const FIRST_GENERATED_NAME_SIZE = 6;
const FIRST_GENERATED_PASSWORD_SIZE = 14;
const FIRST_RELOAD_DELAY = 3000;

/**
 * List of options types.
 *
 * Supported types :
 * - string
 * - boolean
 * - integer
 */
const LIST_OPTIONS_TYPE = array(
        'puppetmaster' => 'string',
        'hostURL' => 'string',
        'permitWrite' => 'boolean',
        'permitWriteObject' => 'boolean',
        'permitCreateObject' => 'boolean',
        'permitSynchronizeObject' => 'boolean',
        'permitProtectedObject' => 'boolean',
        'permitWriteLink' => 'boolean',
        'permitCreateLink' => 'boolean',
        'permitSynchronizeLink' => 'boolean',
        'permitUploadLink' => 'boolean',
        'permitPublicUploadLink' => 'boolean',
        'permitPublicUploadCodeMasterLink' => 'boolean',
        'permitObfuscatedLink' => 'boolean',
        'permitWriteEntity' => 'boolean',
        'permitPublicCreateEntity' => 'boolean',
        'permitWriteGroup' => 'boolean',
        'permitWriteConversation' => 'boolean',
        'permitCurrency' => 'boolean',
        'permitWriteCurrency' => 'boolean',
        'permitCreateCurrency' => 'boolean',
        'permitWriteTransaction' => 'boolean',
        'permitObfuscatedTransaction' => 'boolean',
        'permitSynchronizeApplication' => 'boolean',
        'permitPublicSynchronizeApplication' => 'boolean',
        'permitDeleteObjectOnUnknowHash' => 'boolean',
        'permitCheckSignOnVerify' => 'boolean',
        'permitCheckSignOnList' => 'boolean',
        'permitCheckObjectHash' => 'boolean',
        'permitListInvalidLinks' => 'boolean',
        'permitHistoryLinksSign' => 'boolean',
        'permitInstanceEntityAsAuthority' => 'boolean',
        'permitDefaultEntityAsAuthority' => 'boolean',
        'permitLocalSecondaryAuthorities' => 'boolean',
        'permitRecoveryEntities' => 'boolean',
        'permitRecoveryRemoveEntity' => 'boolean',
        'permitInstanceEntityAsRecovery' => 'boolean',
        'permitDefaultEntityAsRecovery' => 'boolean',
        'permitAddLinkToSigner' => 'boolean',
        'permitListOtherHash' => 'boolean',
        'permitLocalisationStats' => 'boolean',
        'permitFollowUpdates' => 'boolean',
        'permitOnlineRescue' => 'boolean',
        'permitLogs' => 'boolean',
        'permitJavaScript' => 'boolean',
        'logsLevel' => 'string',
        'modeRescue' => 'boolean',
        'cryptoLibrary' => 'string',
        'cryptoHashAlgorithm' => 'string',
        'cryptoSymetricAlgorithm' => 'string',
        'cryptoAsymetricAlgorithm' => 'string',
        'socialLibrary' => 'string',
        'ioLibrary' => 'string',
        'ioReadMaxLinks' => 'integer',
        'ioReadMaxData' => 'integer',
        'ioReadMaxUpload' => 'integer',
        'ioTimeout' => 'integer',
        'displayUnsecureURL' => 'boolean',
        'displayNameSize' => 'integer',
        'displayEmotions' => 'boolean',
        'forceDisplayEntityOnTitle' => 'boolean',
        'maxFollowedUpdates' => 'integer',
        'permitSessionOptions' => 'boolean',
        'permitSessionBuffer' => 'boolean',
        'permitBufferIO' => 'boolean',
        'sessionBufferSize' => 'integer',
        'defaultCurrentEntity' => 'string',
        'defaultApplication' => 'string',
        'defaultObfuscateLinks' => 'boolean',
        'defaultLinksVersion' => 'string',
        'subordinationEntity' => 'string',
);

/**
 * Default options values if not defined in option file.
 */
const LIST_OPTIONS_DEFAULT_VALUE = array(
        'puppetmaster' => NEBULE_DEFAULT_PUPPETMASTER_ID,
        'hostURL' => 'localhost',
        'permitWrite' => true,
        'permitWriteObject' => true,
        'permitCreateObject' => true,
        'permitSynchronizeObject' => true,
        'permitProtectedObject' => false,
        'permitWriteLink' => true,
        'permitCreateLink' => true,
        'permitSynchronizeLink' => true,
        'permitUploadLink' => false,
        'permitPublicUploadLink' => false,
        'permitPublicUploadCodeMasterLink' => false,
        'permitObfuscatedLink' => false,
        'permitWriteEntity' => true,
        'permitPublicCreateEntity' => true,
        'permitWriteGroup' => true,
        'permitWriteConversation' => false,
        'permitCurrency' => false,
        'permitWriteCurrency' => false,
        'permitCreateCurrency' => false,
        'permitWriteTransaction' => false,
        'permitObfuscatedTransaction' => false,
        'permitSynchronizeApplication' => true,
        'permitPublicSynchronizeApplication' => true,
        'permitDeleteObjectOnUnknowHash' => true,
        'permitCheckSignOnVerify' => true,
        'permitCheckSignOnList' => true,
        'permitCheckObjectHash' => true,
        'permitListInvalidLinks' => false,
        'permitHistoryLinksSign' => false,
        'permitInstanceEntityAsAuthority' => false,
        'permitDefaultEntityAsAuthority' => false,
        'permitLocalSecondaryAuthorities' => true,
        'permitRecoveryEntities' => false,
        'permitRecoveryRemoveEntity' => false,
        'permitInstanceEntityAsRecovery' => false,
        'permitDefaultEntityAsRecovery' => false,
        'permitAddLinkToSigner' => true,
        'permitListOtherHash' => false,
        'permitLocalisationStats' => true,
        'permitFollowUpdates' => true,
        'permitOnlineRescue' => false,
        'permitLogs' => false,
        'permitJavaScript' => false,
        'logsLevel' => 'NORMAL',
        'modeRescue' => false,
        'cryptoLibrary' => 'openssl',
        'cryptoHashAlgorithm' => 'sha2.256',
        'cryptoSymetricAlgorithm' => 'aes-256-ctr',
        'cryptoAsymetricAlgorithm' => 'rsa.2048',
        'socialLibrary' => 'strict',
        'ioLibrary' => 'ioFileSystem',
        'ioReadMaxLinks' => 2000,
        'ioReadMaxData' => 10000,
        'ioReadMaxUpload' => 2000000,
        'ioTimeout' => 1,
        'displayUnsecureURL' => true,
        'displayNameSize' => 128,
        'displayEmotions' => false,
        'forceDisplayEntityOnTitle' => false,
        'maxFollowedUpdates' => 100,
        'permitSessionOptions' => true,
        'permitSessionBuffer' => true,
        'permitBufferIO' => true,
        'sessionBufferSize' => 1000,
        'defaultCurrentEntity' => NEBULE_DEFAULT_PUPPETMASTER_ID,
        'defaultApplication' => '0',
        'defaultObfuscateLinks' => false,
        'defaultLinksVersion' => '2.0',
        'subordinationEntity' => '',
);

/**
 * Result of Lib PP initialisation.
 */
$libppCheckOK = false;

/**
 * Buffer of option's values.
 */
$configurationList = array();

/**
 * ID masters of security.
 */
$nebuleSecurityMasters = array();

/**
 * ID masters of code.
 */
$nebuleCodeMasters = array();

/**
 * ID masters of directory.
 */
$nebuleDirectoryMasters = array();

/**
 * ID masters of time.
 */
$nebuleTimeMasters = array();

/**
 * ID de l'entité locale du serveur.
 */
$nebuleServerEntite = '';

/**
 * ID de l'entité par défaut.
 */
$nebuleDefaultEntity = '';

/**
 * ID de l'entité en cours.
 */
$nebulePublicEntity = '';

/**
 * Clé privée de l'entité en cours.
 */
$nebulePrivateEntite = '';

/**
 * Mot de passe de l'entité en cours.
 */
$nebulePasswordEntite = '';

/**
 * Liste des entités autorités locale.
 */
$nebuleLocalAuthorities = array();

/**
 * Metrology - Lib PP link read counter.
 */
$nebuleMetrologyLinkRead = 0;

/**
 * Metrology - Lib PP link verify counter.
 */
$nebuleMetrologyLinkVerify = 0;

/**
 * Metrology - Lib PP object read counter.
 */
$nebuleMetrologyObjectRead = 0;

/**
 * Metrology - Lib PP object verify counter.
 */
$nebuleMetrologyObjectVerify = 0;

// Cache of many search result and content.
$nebuleCacheReadObjText1line = array();
$nebuleCacheReadObjName = array();
$nebuleCacheReadObjSize = array();
$nebuleCacheReadEntityType = array();
$nebuleCacheReadEntityLoc = array();
$nebuleCacheReadEntityFName = array();
$nebuleCacheReadEntityName = array();
$nebuleCacheReadEntityPName = array();
$nebuleCacheReadEntityFullName = array();
$nebuleCacheFindObjType = array();
$nebuleCacheReadObjTypeMime = array();
$nebuleCacheReadObjTypeHash = array();
$nebuleCacheIsText = array();
$nebuleCacheIsBanned = array();
$nebuleCacheIsSuppr = array();
$nebuleCacheIsPubkey = array();
$nebuleCacheIsPrivkey = array();
$nebuleCacheIsEncrypt = array();
$nebuleCacheFindPrivKey = '';
$nebuleCachelibpp_o_vr = array();
$nebuleCachelibpp_l_grx = array();

/**
 * Return option's value. Options presents on environment file are forced.
 * @param string $name
 * @return null|string|boolean|integer
 */
function getConfiguration(string $name)
{
    global $configurationList;

    $value = '';

    if ($name == ''
        || !is_string($name)
        || !isset(LIST_OPTIONS_TYPE[$name])
    )
        return null;

    // Use cache if found.
    if (isset($configurationList[$name]))
        return $configurationList[$name];

    // Read file and extract asked option.
    if (file_exists(NEBULE_ENVIRONMENT_FILE)) {
        $file = file(NEBULE_ENVIRONMENT_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        foreach ($file as $line) {
            $l = trim(filter_var($line, FILTER_SANITIZE_STRING));

            if ($l == '' || $l[0] == "#" || strpos($l, '=') === false)
                continue;

            if (trim(strtok($l, '=')) == $name) {
                $value = trim(strtok('='));
                break;
            }
        }
    }

    // If not found, read default value.
    if ($value == '')
        $value = LIST_OPTIONS_DEFAULT_VALUE[$name];

    // Convert value onto asked type.
    switch (LIST_OPTIONS_TYPE[$name]) {
        case 'string' :
            $result = $value;
            break;
        case 'boolean' :
            if ($value == 'true') {
                $result = true;
            } else {
                $result = false;
            }
            break;
        case 'integer' :
            if ($value != '') {
                $result = (int)$value;
            } else {
                $result = 0;
            }
            break;
        default :
            $result = null;
    }

    $configurationList[$name] = $result;
    return $result;
}

/*
 * ------------------------------------------------------------------------------------------
 * Fonctions haut niveau.
 * ------------------------------------------------------------------------------------------
 */

/** FIXME
 * Initialisation de la bibliothèque.
 * @return boolean
 */
function libppInit(): bool
{
    global $nebuleLocalAuthorities, $libppCheckOK;

    // Initialize i/o.
    if (!io_open())
        return false;

    // Pour la suite, seul le puppetmaster est enregirstré.
    // Une fois les autres entités trouvées, ajoute les autres autorités.
    // Cela empêche qu'une entié compromise ne génère un lien qui passerait avant le puppetmaster
    //   dans la recherche par référence nebFindByRef.
    if (!_entityCheck(getConfiguration('puppetmaster')))
        return false;
    $nebuleLocalAuthorities = array(getConfiguration('puppetmaster'));

    // Search and check global masters.
    $nebuleSecurityMasters = _entityGetSecurityMasters(false);
    if (!_entityCheckSecurityMasters($nebuleSecurityMasters))
        return false;
    $nebuleCodeMasters = _entityGetCodeMasters(false);
    if (!_entityCheckCodeMasters($nebuleCodeMasters))
        return false;
    $nebuleTimeMasters = _entityGetTimeMasters(false);
    if (!_entityCheckTimeMasters($nebuleTimeMasters))
        return false;
    $nebuleDirectoryMasters = _entityGetDirectoryMasters(false);
    if (!_entityCheckDirectoryMasters($nebuleDirectoryMasters))
        return false;

    // Add masters of security as local authorities.
    foreach ($nebuleSecurityMasters as $master) {
        $nebuleLocalAuthorities[] = $master;
    }
    // Add masters of code as local authorities.
    foreach ($nebuleCodeMasters as $master) {
        $nebuleLocalAuthorities[] = $master;
    }

    libppSetServerEntity();
    libppSetDefaultEntity();
    libppSetPublicEntity();

    $libppCheckOK = true;
    return true;
}

/**
 * Get and check local server entity.
 */
function libppSetServerEntity(): void
{
    global $nebuleServerEntite, $nebuleLocalAuthorities;
    if (file_exists(LOCAL_ENTITY_FILE)
        && is_file(LOCAL_ENTITY_FILE)
    )
        $nebuleServerEntite = filter_var(strtok(trim(file_get_contents(LOCAL_ENTITY_FILE)), "\n"), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

    if (!_entityCheck($nebuleServerEntite))
        $nebuleServerEntite = getConfiguration('puppetmaster');

    if (getConfiguration('permitInstanceEntityAsAuthority') && !getModeRescue())
        $nebuleLocalAuthorities[] = $nebuleServerEntite;
}

/**
 * Get and check default entity.
 */
function libppSetDefaultEntity(): void
{
    global $nebuleDefaultEntity, $nebuleLocalAuthorities;
    $nebuleDefaultEntity = getConfiguration('defaultCurrentEntity');
    if (!_entityCheck($nebuleDefaultEntity))
        $nebuleDefaultEntity = getConfiguration('puppetmaster');

    if (getConfiguration('permitDefaultEntityAsAuthority') && !getModeRescue())
        $nebuleLocalAuthorities[] = $nebuleDefaultEntity;
}

/**
 * Get and check public entity.
 */
function libppSetPublicEntity(): void
{
    global $nebulePublicEntity, $nebuleDefaultEntity;
    if (!_entityCheck($nebulePublicEntity))
        $nebulePublicEntity = $nebuleDefaultEntity;
}

/**
 * Check rescue mode asked and authorized.
 * Can be activated by option modeRescue.
 * Can be activated by line argument if permitted by option permitOnlineRescue.
 * This rescue mode is useful when the code loaded crash.
 * By default, rescue mode is not activated.
 * @return bool
 */
function getModeRescue(): bool
{
    if (getConfiguration('modeRescue') === true
        || (getConfiguration('permitOnlineRescue') === true
            && (filter_has_var(INPUT_GET, ARG_RESCUE_MODE)
                || filter_has_var(INPUT_POST, ARG_RESCUE_MODE)
            )
        )
    )
        return true;
    return false;
}

/** FIXME
 * Recherche un objet par référence, c'est à dire référencé par un autre objet dédié.
 * La recherche se fait dans les liens de $object.
 *
 * Si $strict est à true, les liens sont pré-filtrés sur les entités autorités locales.
 * Si la liste des entités autorités locales est vide, tous les liens seront supprimés.
 *
 * Si pas trouvé, retourne '0'.
 *
 * @param string $nid
 * @param string $rid
 * @param boolean $strict
 * @return string
 */
function nebFindByRef(string $nid, string $rid, bool $strict = false)
{
    global $nebuleLocalAuthorities;

    $result = '0';
    if (!_nodCheckNID($nid) || !_nodCheckNID($rid))
        return $result;

    $links = array();
    _lnkFindInclusive($nid, $links, 'f', $nid, '', $rid, false);
    foreach ($links as $link) {
        // Au besoin, filtre les liens sur les autorités locales.
        if ($strict) {
            $signer = $link[2];
            foreach ($nebuleLocalAuthorities as $authority) {
                if ($signer == $authority) {
                    $result = $link[6];
                    break;
                }
            }
        } else {
            // Garde le dernier.
            $result = $link[6];
        }
    }

    return $result;
}

/** FIXME
 * Lit la première ligne d'un objet comme un texte, quel que soit son type mime.
 * Supprime les caractères non imprimables.
 *
 * Fonction avec utilisation du cache si possible.
 *
 * @param string $oid
 * @param integer $maxsize
 * @return string
 */
function nebReadObjText1line(string &$oid, int $maxsize = 128): string
{
    global $nebuleCacheReadObjText1line;

    if (isset($nebuleCacheReadObjText1line [$oid]))
        return $nebuleCacheReadObjText1line [$oid];

    $data = '';
    _objGetLocalContent($oid, $data);
    $data = strtok(filter_var($data, FILTER_SANITIZE_STRING), "\n");
    if (!is_string($data))
        return '';

    $data = trim($data);

    if (extension_loaded('mbstring'))
        $data = mb_convert_encoding($data, 'UTF-8');
    else
        addLog('mbstring extension not installed or activated!', 'warn', __FUNCTION__, 'c2becfad');

    if (strlen($data) > $maxsize) {
        $data = substr($data, 0, ($maxsize - 3)) . '...';
    }

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjText1line [$oid] = $data;

    return $data;
}

/** FIXME
 * Lit le contenu d'un objet comme un texte, quel que soit son type mime.
 * Supprime les caractères non imprimables.
 *
 * @param string $oid
 * @param integer $maxData
 * @return string
 */
function nebGetContentAsText(string &$oid, int $maxData = 0): string
{
    if ($maxData == 0)
        $maxData = getConfiguration('ioReadMaxData');

    $data = '';
    _objGetLocalContent($oid, $data, $maxData + 1);
    $data = filterPrinteableString(filter_var($data, FILTER_SANITIZE_STRING));

    if (strlen($data) > $maxData) {
        $data = substr($data, 0, ($maxData - 3)) . '...';
    }

    return $data;
}

// FIXME
function nebReadEntityFName(&$entite)
{
    // Cherche le prénom d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityFName;

    if (isset($nebuleCacheReadEntityFName [$entite]))
        return $nebuleCacheReadEntityFName [$entite];

    nebCreatAsText('nebule/objet/prenom');
    $type = nebFindObjType($entite, 'nebule/objet/prenom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_checkNodeHaveContent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityFName [$entite] = $text;

    return $text;
}

// FIXME
function nebReadEntityName(&$entite)
{
    // Cherche le nom d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityName;

    if (isset($nebuleCacheReadEntityName [$entite]))
        return $nebuleCacheReadEntityName [$entite];

    nebCreatAsText('nebule/objet/nom');
    $type = nebFindObjType($entite, 'nebule/objet/nom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_checkNodeHaveContent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityName [$entite] = $text;

    return $text;
}

// FIXME
function nebReadEntityPName(&$entite)
{
    // Cherche le postnom d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityPName;

    if (isset($nebuleCacheReadEntityPName [$entite]))
        return $nebuleCacheReadEntityPName [$entite];

    nebCreatAsText('nebule/objet/postnom');
    $type = nebFindObjType($entite, 'nebule/objet/postnom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_checkNodeHaveContent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityPName [$entite] = $text;

    return $text;
}

// FIXME
function nebReadEntityFullName(&$entite)
{
    // Cherche le nom complet d'une entite, càd avec prénom et surnom.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityFullName;

    if (isset($nebuleCacheReadEntityFullName [$entite]))
        return $nebuleCacheReadEntityFullName [$entite];

    $fname = nebReadEntityFName($entite);
    $name = nebReadEntityName($entite);
    $pname = nebReadEntityPName($entite);
    if ($name == '') {
        $fullname = "$entite";
    } else {
        $fullname = $name;
    }
    if ($fname != '') {
        $fullname = "$fname $fullname";
    }
    if ($pname != '') {
        $fullname = "$fullname $pname";
    }
    unset($fname);
    unset($name);
    unset($pname);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityFullName [$entite] = $fullname;

    return $fullname;
}

// FIXME
function nebFindObjType(&$object, $type)
{
    // Cherche l'objet contenant la description de l'objet pour une propriete type.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleCacheFindObjType;

    if (isset($nebuleCacheFindObjType [$object] [$type]))
        return $nebuleCacheFindObjType [$object] [$type];

    if ($object == '811ba947111090b4708da62494a84e5cfc13ea60e16dac94a678f395aa42da07')
        return ''; // WARNING caca - Exception pour problème de performances : 'nebule/objet/entite/suivi'

    $table = array();
    $hashtype = _objGetNID($type, getConfiguration('cryptoHashAlgorithm'));
    $objdst = '';
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $object,
        'bl/rl/nid2' => '',
        'bl/rl/nid3' => $hashtype,
        'bl/rl/nid4' => '0',
    );
    _lnkFind($object, $table, $filter);
    foreach ($table as $itemtable) {
        if (($itemtable [2] == $nebulePublicEntity) && ($itemtable [7] == $hashtype) && ($itemtable [5] == $object) && ($itemtable [4] == 'l')) {
            $objdst = $itemtable [6];
            break 1;
        }
        if (($itemtable [7] == $hashtype) && ($itemtable [5] == $object) && ($itemtable [4] == 'l')) {
            $objdst = $itemtable [6];
        } // WARNING peut-être un problème de sécurité...
    }
    unset($table);
    unset($hashtype);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheFindObjType [$object] [$type] = $objdst;

    return $objdst;
}

// FIXME
function nebReadObjTypeMime(&$object)
{ // Cherche le type mime d'un objet.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleCacheReadObjTypeMime;

    if (isset($nebuleCacheReadObjTypeMime [$object]))
        return $nebuleCacheReadObjTypeMime [$object];

    if ($object == '' || $object == '0')
        return '-indéfini-'; // L'objet doit etre present.

    $table = array();
    nebCreatAsText('nebule/objet/type');
    $hashtype = _objGetNID('nebule/objet/type', getConfiguration('cryptoHashAlgorithm'));
    $type = '';
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $object,
        'bl/rl/nid2' => '',
        'bl/rl/nid3' => $hashtype,
        'bl/rl/nid4' => '0',
    );
    _lnkFind($object, $table, $filter);
    rsort($table);
    foreach ($table as $itemtable) {
        if (($itemtable [2] == $nebulePublicEntity) && ($itemtable [7] == $hashtype) && ($itemtable [5] == $object) && ($itemtable [4] == 'l')) {
            $type = $itemtable [6];
            break 1;
        }
        if (($itemtable [7] == $hashtype) && ($itemtable [5] == $object) && ($itemtable [4] == 'l'))
            $type = $itemtable [6]; // WARNING peut être un problème de sécurité...
    }
    unset($table);
    unset($hashtype);
    $text = '';
    if (io_checkNodeHaveContent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    if ($text == '') {
        $text = '-indéfini-';
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjTypeMime [$object] = $text;

    return $text;
}

/** FIXME
 * nebIsPubkey()
 * Vérifie si l'objet est une clé publique.
 * Fonction avec utilisation du cache si possible.
 *
 * @param string $entite
 * @return boolean
 */
function nebIsPubkey(&$entite): bool
{
    global $nebuleCacheIsPubkey;

    if (isset($nebuleCacheIsPubkey[$entite]))
        return $nebuleCacheIsPubkey[$entite];

    if (!is_string($entite)
        || !_nodCheckNID($entite)
        || !io_checkNodeHaveLink($entite)
    ) {
        return false;
    }
    nebCreatAsText('application/x-pem-file'); // 970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864
    if (nebReadObjTypeMime($entite) != 'application/x-pem-file') {
        return false;
    }
    $line = nebGetContentAsText($entite, 10000);

    if (strstr($line, 'BEGIN PUBLIC KEY') !== false) {
        if (getConfiguration('permitBufferIO')) {
            $nebuleCacheIsPubkey[$entite] = true; // FIXME
        }
        return true;
    } else {
        if (getConfiguration('permitBufferIO')) {
            $nebuleCacheIsPubkey[$entite] = false;
        }
        return false;
    }
}

// FIXME
function nebIsPrivkey(&$entite): bool
{ // Vérifie si l'objet est une clé privée.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheIsPrivkey;

    if (isset($nebuleCacheIsPrivkey [$entite]))
        return $nebuleCacheIsPrivkey [$entite];

    if ($entite == '0')
        return false;

    nebCreatAsText('application/x-pem-file'); // 970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864
    if ((nebReadObjTypeMime($entite)) != 'application/x-pem-file')
        return false;
    $line = nebGetContentAsText($entite, 10000);

    if ((strstr($line, 'BEGIN ENCRYPTED PRIVATE KEY')) !== false) {
        if (getConfiguration('permitBufferIO'))
            $nebuleCacheIsPrivkey [$entite] = true; // FIXME
        return true;
    } else {
        if (getConfiguration('permitBufferIO'))
            $nebuleCacheIsPrivkey [$entite] = false;
        return false;
    }
}

// FIXME
function nebFindPrivKey()
{ // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity;

    $table = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => '',
        'bl/rl/nid2' => $nebulePublicEntity,
        'bl/rl/nid3' => '0',
        'bl/rl/nid4' => '0',
    );
    _lnkFind($nebulePublicEntity, $table, $filter);
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'f') && ($link [6] == $nebulePublicEntity) && ($link [7] == '0')) // && nebIsPrivkey($link[5]) ) à débugger...
        {
            return $link [5];
        }
    }
    return '';
}

// FIXME
function nebCheckPrivkey()
{ // Vérifie si le mot de passe de l'entité en cours est bien valide, c'est à dire qu'il donne accès à la clé privée.
    global $nebulePrivateEntite, $nebulePasswordEntite;

    $privcert = nebGetContentAsText($nebulePrivateEntite, 10000);
    $r = openssl_pkey_get_private($privcert, $nebulePasswordEntite);
    unset($privcert);
    if ($r === false) {
        return false;
    } else {
        return true;
    }
}

// FIXME
function nebListChildrenRecurse($object, &$listchildren, &$node, $level = 1)
{ // Sous-boucle de recheche les objets enfants.
    // Utiliser de préférence la fonction nebListChildren.
    $maxRecurse = getConfiguration('maxFollowedUpdates');

    $link = array();
    $links = array();
    _lnkListOnFullFilter($object, $links, '-', '', true);
    foreach ($links as $link) {
        $c = count($listchildren);
        $exist = false;
        if ((($link [4] == "l") || ($link [4] == "u") || ($link [4] == "e") || ($link [4] == "k")) && ($link [5] == $object || $link [6] == $object) && $level == 1) {
            foreach ($listchildren as $item) {
                if ($item [0] == $link [6])
                    $exist = true;
                break;
            }
            if (!$exist) {
                $listchildren [($c + 1)] [0] = $link [0];
                $listchildren [($c + 1)] [1] = $link [1];
                $listchildren [($c + 1)] [2] = $link [2];
                $listchildren [($c + 1)] [3] = $link [3];
                $listchildren [($c + 1)] [4] = $link [4];
                $listchildren [($c + 1)] [5] = $link [5];
                $listchildren [($c + 1)] [6] = $link [6];
                $listchildren [($c + 1)] [7] = $link [7];
                $listchildren [($c + 1)] [8] = $link [8];
                $listchildren [($c + 1)] [9] = $link [9];
                $listchildren [($c + 1)] [10] = $link [10];
                $listchildren [($c + 1)] [11] = $link [11];
                $listchildren [($c + 1)] [12] = $level;
            }
        } elseif ($link [4] == "f" && $link [5] == $object) {
            foreach ($listchildren as $item) {
                if ($item [0] == $link [6])
                    $exist = true;
                break;
            }
            if (!$exist) {
                $listchildren [($c + 1)] [0] = $link [0];
                $listchildren [($c + 1)] [1] = $link [1];
                $listchildren [($c + 1)] [2] = $link [2];
                $listchildren [($c + 1)] [3] = $link [3];
                $listchildren [($c + 1)] [4] = $link [4];
                $listchildren [($c + 1)] [5] = $link [5];
                $listchildren [($c + 1)] [6] = $link [6];
                $listchildren [($c + 1)] [7] = $link [7];
                $listchildren [($c + 1)] [8] = $link [8];
                $listchildren [($c + 1)] [9] = $link [9];
                $listchildren [($c + 1)] [10] = $link [10];
                $listchildren [($c + 1)] [11] = $link [11];
                $listchildren [($c + 1)] [12] = $level;
                if ($level < $maxRecurse && ($link [7] == $node || $level == 1))
                    nebListChildrenRecurse($link [6], $listchildren, $node, ($level + 1));
            }
        } elseif ($link [4] == "f" && $link [6] == $object && $level == 1) {
            foreach ($listchildren as $item) {
                if ($item [0] == $link [5])
                    $exist = true;
                break;
            }
            if (!$exist) {
                $listchildren [($c + 1)] [0] = $link [0];
                $listchildren [($c + 1)] [1] = $link [1];
                $listchildren [($c + 1)] [2] = $link [2];
                $listchildren [($c + 1)] [3] = $link [3];
                $listchildren [($c + 1)] [4] = $link [4];
                $listchildren [($c + 1)] [5] = $link [5];
                $listchildren [($c + 1)] [6] = $link [6];
                $listchildren [($c + 1)] [7] = $link [7];
                $listchildren [($c + 1)] [8] = $link [8];
                $listchildren [($c + 1)] [9] = $link [9];
                $listchildren [($c + 1)] [10] = $link [10];
                $listchildren [($c + 1)] [11] = $link [11];
                $listchildren [($c + 1)] [12] = $level;
            }
        }
    }
    unset($link);
    unset($links);
    unset($c);
    unset($exist);
}

// FIXME
function nebListChildren(&$object, &$listchildren)
{ // Recheche les objets enfants, c'est à dire dérivés.

    if ($object == '0')
        return;
    nebListChildrenRecurse($object, $listchildren, $object, 1);
}

/**
 * Write text in object content.
 * By default, if object present, do not create links for the object ($skipIfPresent).
 *
 * @param string $data
 * @param bool $skipIfPresent
 * @return bool
 */
function nebCreatAsText(string $data, bool $skipIfPresent = true)
{ // Création d'un nouvel objet texte.
    if (!getConfiguration('permitWriteObject') || !getConfiguration('permitWriteLink') || strlen($data) == 0)
        return false;
    $oid = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    if ($skipIfPresent && io_checkNodeHaveContent($oid))
        return true;
    if (_nodCheckBanned($oid))
        return false;
    _objGenerate($data, 'text/plain');
    return true;
}



/*
 * ------------------------------------------------------------------------------------------
 * Fonctions élémentaires.
 * ------------------------------------------------------------------------------------------
 */

/**
 * Metrology - Incrementing one stat counter.
 * @param string $type
 */
function _metrologyAdd(string $type): void
{
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;

    switch ($type)
    {
        case 'lr':
            $nebuleMetrologyLinkRead++;
            break;
        case 'lv':
            $nebuleMetrologyLinkVerify++;
            break;
        case 'or':
            $nebuleMetrologyObjectRead++;
            break;
        case 'ov':
            $nebuleMetrologyObjectVerify++;
            break;
    }
}

/**
 * Metrology - Return one stat counter.
 * @param string $type
 * @return string
 */
function _metrologyGet(string $type): string
{
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;

    $return = '';
    switch ($type)
    {
        case 'lr':
            $return = (string)$nebuleMetrologyLinkRead;
            break;
        case 'lv':
            $return = (string)$nebuleMetrologyLinkVerify;
            break;
        case 'or':
            $return = (string)$nebuleMetrologyObjectRead;
            break;
        case 'ov':
            $return = (string)$nebuleMetrologyObjectVerify;
            break;
    }
    return $return;
}

// ------------------------------------------------------------------------------------------

/** FIXME
 * Entity -
 *
 * @param $asymetricAlgo
 * @param $hashAlgo
 * @param $hashpubkey
 * @param $hashprivkey
 * @param string $password
 * @return bool
 */
function _entityGenerate($asymetricAlgo, $hashAlgo, &$hashpubkey, &$hashprivkey, $password = '')
{
    if (!getConfiguration('permitWrite'))
        return false;
    if (!getConfiguration('permitWriteEntity'))
        return false;
    if (!getConfiguration('permitWriteObject'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    if (($asymetricAlgo != 'rsa') && ($asymetricAlgo != 'dsa'))
        return false;
    if ($password == '')
        return false;

    // TODO à vérifier...
//getConfiguration('cryptoAsymetricAlgorithm')
    $size = substr($asymetricAlgo, strpos($asymetricAlgo, '.') + 1);
    $algoName = substr($asymetricAlgo, 0, strpos($asymetricAlgo, '.') - 1);

    // Génération de la clé
    switch ($asymetricAlgo) {
        case 'rsa' :
            $config = array(
                'digest_alg' => $hashAlgo,
                'private_key_bits' => (int)$size,
                'private_key_type' => OPENSSL_KEYTYPE_RSA);
            break;
        case 'dsa' :
            $config = array(
                'digest_alg' => $hashAlgo,
                'private_key_bits' => (int)$size,
                'private_key_type' => OPENSSL_KEYTYPE_DSA);
            break;
    }
    $newpkey = openssl_pkey_new($config);
    unset($config);
    // Extraction de la clé publique.
    $pubkey = openssl_pkey_get_details($newpkey);
    $pubkey = $pubkey ['key'];
    $hashpubkey = _objGetNID($pubkey, getConfiguration('cryptoHashAlgorithm'));
    _objWriteContent($hashpubkey, $pubkey);
    // Extraction de la clé privée.
    if ($password != '') {
        openssl_pkey_export($newpkey, $privkey, $password);
    } else {
        openssl_pkey_export($newpkey, $privkey);
    }
    $hashprivkey = _objGetNID($privkey, getConfiguration('cryptoHashAlgorithm'));
    _objWriteContent($hashprivkey, $privkey);
    $private_key = openssl_pkey_get_private($privkey, $password);
    if ($private_key === false) {
        return false;
    }
    // Calcul de hashs communs.
    $date = date(DATE_ATOM);
    $binary_signature = '';
    $refhashhash = _objGetNID('nebule/objet/hash', getConfiguration('cryptoHashAlgorithm'));
    $refhashalgo = _objGetNID(getConfiguration('cryptoHashAlgorithm'), getConfiguration('cryptoHashAlgorithm'));
    $refhashtype = _objGetNID('nebule/objet/type', getConfiguration('cryptoHashAlgorithm'));
    $refhashpem = _objGetNID('application/x-pem-file', getConfiguration('cryptoHashAlgorithm'));
    $refhashtext = _objGetNID('text/plain', getConfiguration('cryptoHashAlgorithm'));
    // Création des objets annexes.
    if (!io_checkNodeHaveContent($refhashhash)) {
        $newtxt = 'nebule/objet/hash';
        _objWriteContent($refhashhash, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashhash . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashhash . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_checkNodeHaveContent($refhashalgo)) {
        $cryptoHashAlgorithm = getConfiguration('cryptoHashAlgorithm');
        _objWriteContent($refhashalgo, $cryptoHashAlgorithm);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashalgo . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashalgo . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_checkNodeHaveContent($refhashtype)) {
        $newtxt = 'nebule/objet/type';
        _objWriteContent($refhashtype, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtype . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtype . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_checkNodeHaveContent($refhashpem)) {
        $newtxt = 'application/x-pem-file';
        _objWriteContent($refhashpem, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashpem . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashpem . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_checkNodeHaveContent($refhashtext)) {
        $newtxt = 'text/plain';
        _objWriteContent($refhashtext, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtext . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtext . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    // Génération du lien de hash de la clé publique.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashpubkey . '_' . $refhashalgo . '_' . $refhashhash;
    $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    $binhash = pack("H*", $hashdata);
    $ok1 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de hash de la clé privée.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashprivkey . '_' . $refhashalgo . '_' . $refhashhash;
    $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    $binhash = pack("H*", $hashdata);
    $ok2 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de typemime de la clé publique.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashpubkey . '_' . $refhashpem . '_' . $refhashtype;
    $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    $binhash = pack("H*", $hashdata);
    $ok3 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de typemime de la clé privée.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashprivkey . '_' . $refhashpem . '_' . $refhashtype;
    $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    $binhash = pack("H*", $hashdata);
    $ok4 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de jumelage des clés.
    $data = '_' . $hashpubkey . '_' . $date . '_f_' . $hashprivkey . '_' . $hashpubkey . '_0';
    $hashdata = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    $binhash = pack("H*", $hashdata);
    $ok5 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    _lnkWrite("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Nettoyage.
    openssl_free_key($private_key);
    unset($private_key);
    unset($hashprivkey);
    unset($hashpubkey);
    unset($data);
    unset($hashdata);
    unset($binhash);
    unset($refhashhash);
    unset($refhashalgo);
    unset($refhashtype);
    unset($refhashpem);
    unset($refhashtext);
    unset($date);
    unset($hexsign);
    unset($binary_signature);
    if (!($ok1 && $ok2 && $ok3 && $ok4 && $ok5))
        return false;
    return true;
}

/**
 * Verify name structure and content of a entity.
 * @param string $nid
 * @param string $name
 * @return bool
 */
function _entityCheck(string $nid): bool
{
    if (!_nodCheckNID($nid, false)
        || $nid == '0'
        || strlen($nid) < NID_MIN_HASH_SIZE
        || !io_checkNodeHaveContent($nid)
        || !io_checkNodeHaveLink($nid)
        || !_objCheckContent($nid)
        || !nebIsPubkey($nid)
    )
        return false;
    return true;
}

/** FIXME
 * Entity -
 *
 * @param $pubkey
 * @param $privkey
 * @param $password
 * @return bool
 */
function _entityAddPasswd($pubkey, $privkey, $password)
{ // Ajoute le mot de passe connu pour la clé privée d'une entité. Utilisé pour les entité esclaves d'une autre entité.
    // - $pubkey : la clé public de l'entité, nécessaire pour un des liens.
    // - $privkey : la clé privée de l'entité.
    // - $password : le mot de passe à reconnaître pour la clé privée. Le mot de passe est vérifié sur la clé.
    global $nebulePublicEntity;

    if (!getConfiguration('permitWrite'))
        return false;
    if (!getConfiguration('permitWriteObject'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    if ($password == '')
        return false;
    if (!io_checkNodeHaveContent($pubkey))
        return false;
    if (!io_checkNodeHaveContent($privkey))
        return false;
    // Vérifie que le mot de passe est valide.
    $privcert = nebGetContentAsText($privkey, 10000);
    $ok = openssl_pkey_get_private($privcert, $password);
    if ($ok === false)
        return false;
    unset($privcert);
    // Génère une clé de session.
    $key = openssl_random_pseudo_bytes(NID_MIN_HASH_SIZE, $true);
    $hashkey = _objGetNID($key, getConfiguration('cryptoHashAlgorithm'));
    // Génère un IV à zéro.
    $hiv = '00000000000000000000000000000000';
    $iv = pack("H*", $hiv); // A modifier pour des blocs de tailles différentes.
    // Chiffrement de l'objet.
    $cryptobj = openssl_encrypt($password, getConfiguration('cryptoSymetricAlgorithm'), $key, OPENSSL_RAW_DATA, $iv);
    $hashpwd = _objGetNID($password, getConfiguration('cryptoHashAlgorithm'));
    $hashcryptobj = _objGetNID($cryptobj, getConfiguration('cryptoHashAlgorithm'));
    _objGenerate($cryptobj, 'application/x-encrypted/' . getConfiguration('cryptoSymetricAlgorithm'));
    // Chiffrement de la clé de session.
    $cryptkey = '';
    _objCheckContent($pubkey);
    $cert = io_objectRead($pubkey);
    $ok = openssl_public_encrypt($key, $cryptkey, $cert, OPENSSL_PKCS1_PADDING);
    if (!$ok)
        return false;
    $hashcryptkey = _objGetNID($cryptkey, getConfiguration('cryptoHashAlgorithm'));
    $algoName = substr(getConfiguration('cryptoAsymetricAlgorithm'), 0, strpos(getConfiguration('cryptoAsymetricAlgorithm'), '.') - 1);
    _objGenerate($cryptkey, 'application/x-encrypted/' . $algoName);
    // Génère le lien de chiffrement entre clé privée et publique avec le mot de passe.
    $newlink = _lnkGenerate('-', 'k', $privkey, $pubkey, $hashpwd);
    if ((_lnkVerify($newlink)) == 1)
        _lnkWrite($newlink);
    // Génère le lien de chiffrement symétrique.
    $newlink = _lnkGenerate('-', 'k', $hashpwd, $hashcryptobj, $hashkey);
    if ((_lnkVerify($newlink)) == 1)
        _lnkWrite($newlink);
    // Génère le lien de chiffrement asymétrique.
    $newlink = _lnkGenerate('-', 'k', $hashkey, $hashcryptkey, $nebulePublicEntity);
    if ((_lnkVerify($newlink)) == 1)
        _lnkWrite($newlink);
    // Suppression de la clé de session.
    $newlink = _lnkGenerate('-', 'd', $hashkey, '0', '0');
    if ((_lnkVerify($newlink)) == 1)
        _lnkWrite($newlink);
    // Suppression de l'objet source.
    $newlink = _lnkGenerate('-', 'd', $hashpwd, '0', '0');
    if ((_lnkVerify($newlink)) == 1)
        _lnkWrite($newlink);
    return true;
}

/** FIXME
 * Entity -
 *
 * @param $entity
 * @param $password
 * @return bool
 */
function _entityChangePasswd($entity, $password): bool
{
    if (!getConfiguration('permitWrite'))
        return false;
    if (!getConfiguration('permitWriteObject'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    if ($entity == '')
        return false;
    if ($password == '')
        return false;
    // A faire...
    return true;
}

/** FIXME
 * Object -
 *
 * @param $data
 * @param string $typemime
 */
function _objGenerate(&$data, $typemime = '')
{ // Crée un nouvel objet.
    if (strlen($data) == 0)
        return;
    if (!getConfiguration('permitWrite'))
        return;
    if (!getConfiguration('permitWriteObject'))
        return;
    $dat = date(DATE_ATOM);
    $hash = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    // Ecrit l'objet.
    if (!io_checkNodeHaveContent($hash))
        _objWriteContent($hash, $data);
    // Ecrit le lien de hash.
    $lnk = _lnkGenerate(
            $dat,
            'l',
            $hash,
            _objGetNID(getConfiguration('cryptoHashAlgorithm'), getConfiguration('cryptoHashAlgorithm')),
            _objGetNID('nebule/objet/hash', getConfiguration('cryptoHashAlgorithm'))
    );
    if ((_lnkVerify($lnk)) == 1)
        _lnkWrite($lnk);
    // Ecrit le lien de type mime.
    if ($typemime != '') {
        $lnk = _lnkGenerate(
                $dat,
                'l',
                $hash,
                _objGetNID($typemime, getConfiguration('cryptoHashAlgorithm')),
                _objGetNID('nebule/objet/type', getConfiguration('cryptoHashAlgorithm'))
        );
        if ((_lnkVerify($lnk)) == 1)
            _lnkWrite($lnk);
    }
    unset($dat);
    unset($hash);
    unset($lnk);
}

/**
 * Object - Read object content and push on $data.
 *
 * @param string $nid
 * @param string $data
 * @param numeric $maxData
 * @return boolean
 */
function _objGetLocalContent(string &$nid, string &$data, int $maxData = 0): bool
{
    if (io_checkNodeHaveContent($nid) && _objCheckContent($nid)) {
        $data = io_objectRead($nid, $maxData);
        return true;
    }
    return false;
}

/** FIXME
 * Object - Download node content (object) on web location.
 * Only valid content are writed on local filesystem.
 *
 * TODO remplacer location par locations !
 *
 * @param string $nid
 * @param array $locations
 * @return boolean
 */
function _objDownloadOnLocations(string $nid, array $locations = array()): bool
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
        || !getConfiguration('permitSynchronizeObject')
        || !_nodCheckNID($nid)
        || _nodCheckBanned($nid)
        || sizeof($locations) == 0
    )
        return false;

    if (io_checkNodeHaveContent($nid))
        return true;

    if (sizeof($locations) == 0)
        $locations = FIRST_LOCALISATIONS;

    foreach ($locations as $location) {
        if (io_objectSynchronize($nid, $location))
            return true;
    }
    return false;
}

/** FIXME
 * Object - Vérifie la consistance d'un objet. Si l'objet est corrompu, il est supprimé.
 *
 * @param string $nid
 * @return boolean
 * @todo refaire avec i/o
 *
 */
function _objCheckContent(&$nid)
{
    global $nebuleCachelibpp_o_vr;

    if (!_nodCheckNID($nid))
        return false;

    if (!io_checkNodeHaveContent($nid))
        return true;

    // Si c'est l'objet 0, le supprime.
    if ($nid == '0') {
        if (io_checkNodeHaveContent($nid)) {
            io_objectDelete($nid);
        }
        return true;
    }

    if (isset($nebuleCachelibpp_o_vr[$nid]))
        return true;

    _metrologyAdd('ov');

    $algo = substr($nid, strpos($nid, '.') + 1);
    if ($algo !== false)
        $hash = cryptoGetFileHash($nid, $algo);
    else
        $hash = 'invalid';

    if ($hash . '.' . $algo !== $nid) {
        // Si invalide, suppression de l'objet localement.
        io_objectDelete($nid);
    }

    if (getConfiguration('permitBufferIO')) {
        $nebuleCachelibpp_o_vr[$nid] = true;
    }
    unset($hash);

    return true;
}

/**
 * Object - Calculate NID for data with hash algo.
 *
 * @param string $data
 * @param string $algo
 * @return string
 */
function _objGetNID(string $data, string $algo = ''): string
{
    return cryptoGetDataHash($data, $algo) . '.' . $algo;
}

/**
 * Object - Verify name structure of the node : hash.algo.size
 *
 * @param string $nid
 * @param boolean $permitNull
 * @return boolean
 */
function _nodCheckNID(string &$nid, bool $permitNull = false): bool
{
    // May be null in some case.
    if (!$permitNull && $nid == '')
        return true;

    // Check hash value.
    $hash = strtok($nid, '.');
    if ($hash === false) return false;
    if (strlen($hash) < NID_MIN_HASH_SIZE) return false;
    if (strlen($hash) > NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($hash)) return false;

    // Check algo value.
    $algo = strtok('.');
    if ($algo === false) return false;
    if (strlen($algo) < NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    $size = strtok('.');
    if ($size === false) return false;
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < NID_MIN_HASH_SIZE) return false;
    if ((int)$size > NID_MAX_HASH_SIZE) return false;
    if (strlen($hash) != (int)$size) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    return true;
}

/** FIXME
 * Object - Check with links if a node is marked as banned.
 *
 * @param $nid
 * @return boolean
 */
function _nodCheckBanned(&$nid): bool
{
    global $nebulePublicEntity, $nebuleSecurityMasters, $nebuleCacheIsBanned;

    // FIXME
    return false;
    /*
    if (isset($nebuleCacheIsBanned [$nid]))
        return $nebuleCacheIsBanned [$nid];

    if ($nid == '0')
        return false;

    $ok = false;
    $table = array();
    $hashtype = _objGetNID('nebule/danger', getConfiguration('cryptoHashAlgorithm'));
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $hashtype,
        'bl/rl/nid2' => $nid,
        'bl/rl/nid3' => '0',
        'bl/rl/nid4' => '0',
    );
    _lnkFind($nid, $table, $filter);
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $nid) && ($link [7] == '0'))
            $ok = true;
        if (($link [2] == $nebuleSecurityMaster) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $nid) && ($link [7] == '0'))
            $ok = true;
    }
    unset($table);
    unset($hashtype);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsBanned [$nid] = $ok;



            addLog($nid . ') banned by ' . $nebulePublicEntity, 'warn', __FUNCTION__, 'a9668cd0');
            addLog($nid . ') banned by ' . $nebuleSecurityMaster, 'warn', __FUNCTION__, 'd84f8e81');



    return $ok;*/
}

/** FIXME
 * Object - Ecrit le contenu d'un objet.
 *
 * @param string $object
 * @param string $data
 * @return bool
 */
function _objWriteContent(string $object, string &$data): bool
{
    if ($object == '0'
        || strlen($data) == 0
        || !getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
    ) {
        return false;
    }

    if (io_checkNodeHaveContent($object)) {
        return true;
    }

    // Ecriture de l'objet.
    if (file_put_contents(LOCAL_OBJECTS_FOLDER . "/$object", $data) === false) // @todo refaire via les i/o.
    {
        return false;
    }
    return true;
}

/** FIXME
 * Object - Delete object if not used by other entity.
 *
 * @param string $object
 * @return bool
 */
function _objDelete(string &$object): bool
{
    global $nebulePublicEntity;

    if (!getConfiguration('permitWrite') || !getConfiguration('permitWriteObject'))
        return false;

    $ok = true;
    $links = array();
    _lnkListOnFullFilter($object, $links);
    foreach ($links as $link) {
        if ($link [2] != $nebulePublicEntity) {
            $ok = false;
            break 1;
        }
    } // Vérifie si l'objet est utilisé par une autre entité.
    unset($links);
    unset($link);
    if (!$ok)
        return false;
    return io_objectDelete($object);
}

/**
 * Link - Generate a new link
 * Use OpenSSL library.
 *
 * @param string $rc
 * @param string $req
 * @param string $nid1
 * @param string $nid2
 * @param string $nid3
 * @return string
 */
function _lnkGenerate(string $rc, string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): string
{
    global $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite;

    if (!_entityCheck($nebulePublicEntity)
        || $nebulePrivateEntite == ''
        || $nebulePasswordEntite == ''
        || !io_checkNodeHaveContent($nebulePrivateEntite)
        || $req == ''
        || !_nodCheckNID($nid1)
        || !_nodCheckNID($nid2, true)
        || !_nodCheckNID($nid3, true)
        || !_nodCheckNID($nid4, true)
    )
        return '';

    $bh = 'nebule:link/'.NEBULE_LIBPP_LINK_VERSION;

    if ($rc == '' || !_lnkCheckRC($rc))
        $rc = '0:' . date(DATE_ATOM);

    $rl = $req . '>' . $nid1;
    if ($nid2 != '' && $nid2 != '0')
        $rl .= '>' . $nid2;
    if ($nid3 != '' && $nid3 != '0')
        $rl .= '>' . $nid3;
    if ($nid4 != '' && $nid4 != '0')
        $rl .= '>' . $nid4;
    $bl = $rc . '/' . $rl;

    $bh_bl = $bh . '_' . $bl;

    $sign = cryptoAsymetricEncrypt($bh_bl);
    if ($sign == '')
        return '';

    $bs = $nebulePublicEntity . '>' . $sign . getConfiguration('cryptoHashAlgorithm');
    return $bh_bl . '_' . $bs;
}

/** FIXME
 * Link - Résoud un embranchement de mise à jour d'un objet.
 * Passer de préférence par la fonction _neblibpp_l_grx !
 *
 *  - $object est l'objet dont on veut trouver la mise à jour.
 *  - $visited est une table des liens et objets déjà parcourus.
 *  - $present permet de controler si l'on veut que l'objet final soit bien présent localement.
 *  - $synchro permet ou non la synchronisation des liens et objets auprès d'entités tièrces,
 *      en clair on télécharge ce qui manque au besoin lors du parcours du graphe.
 *  - $restrict permet de ne parcourir les branche que sur des liens signés des entités marquées comme autorités.
 *
 * Fonction avec utilisation du cache si possible.
 * Ne tient pas compte de $synchro pour le cache.
 * Pas de cache si la recherche est restrainte aux autorités.
 *
 * @param string $nid
 * @param array:string $visited
 * @param boolean $present
 * @param boolean $synchro
 * @param boolean $restrict
 * @return string
 */
function _lnkGraphResolvOne(&$nid, &$visited, $present = true, $synchro = false, $restrict = false)
{
    global $nebuleLocalAuthorities;

    $visited [$nid] = true;
    if (count($visited) > getConfiguration('maxFollowedUpdates')) {
        return '0'; // Anti trou noir.
    }
    $links = array();
    $filter = array(
        'bl/rl/req' => 'u',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid2' => '0',
        'bl/rl/nid3' => '0',
        'bl/rl/nid4' => '0',
    );
    _lnkFind($nid, $links, $filter); // Liste les liens de mise à jour de l'objet.
    $links = array_reverse($links); // Inverse le résultat pour avoir les liens les plus récents en premier.

    // Recherche de nouveaux liens.
    if ($synchro
        && getConfiguration('permitSynchronizeLink')
        && (getConfiguration('permitSynchronizeObject')
            || !$present
        )
    ) {
        _lnkDownloadAnywhere($nid);
    }

    // Supprime les boucles, càd les objets déjà traités.
    $oklinks = array();
    foreach ($links as $link) {
        if (!isset($visited[$link[6]])) {
            array_push($oklinks, $link);
        }
    }
    unset($links); // Nettoyage du tableau des liens de mise à jour.

    if (count($oklinks) == 0) {
        if (!$present
            || io_checkNodeHaveContent($nid)
        ) {
            return $nid;
        } else {
            return '0';
        }
    } // Bout de branche.

    // Extrait le lien socialement le plus élevé, ou le plus récent. Supprime les objets non acceptables socialement.
    $valinks = array();
    $vsoc = array();
    if ($restrict) // Ne prend en compte que les liens des entités marquées comme autorités locales.
    {
        foreach ($oklinks as $link) {
            foreach ($nebuleLocalAuthorities as $auth) {
                if ($link[2] == $auth) {
                    array_push($valinks, $link);
                    array_push($vsoc, 1);
                }
            }
        }
    } else // Sinon regarde la validité sociale du lien. TODO à supprimer !
    {
        foreach ($oklinks as $link) {
            $slv = 1; // WARNING actuellement tout le monde à 1.
            if ($slv >= 1) {
                array_push($valinks, $link);
                array_push($vsoc, $slv);
            } // Tri par validité sociale.
        }
    }
    unset($oklinks); // Nettoyage du tableau des liens de mise à jour vers des objets non déjà vus.
    //array_multisort( $vsoc, SORT_ASC, $valinks ); // Tri sur la validité sociale. WARNING vérifier la pertinence, ne marche pas...

    if (count($valinks) == 0) // Bout de branche parce que sans branche valide.
    {
 /*       if ($synchro
            && getConfiguration('permitSynchronizeObject')
        ) {
            _objDownloadOnLocation($nid); // Syncho de l'objet.
        } TODO non fonctionnel */
        if (!$present
            || io_checkNodeHaveContent($nid)
        ) {
            return $nid;
        } else {
            return '0';
        }
    }

    // Parcours les branches.
    foreach ($valinks as $link) {
        $res = _lnkGraphResolvOne($link [6], $visited, $present);
        if ($res != '0') {
            return $res;
        }
    }

    if (!$present
        || io_checkNodeHaveContent($nid)
    ) {
        return $nid;
    } else {
        return '0'; // Bout de branche parce que sans branche avec objet présent.
    }
}

/** FIXME
 * Link - Résoud le graphe des mises à jours d'un objet.
 *
 *  - $object est l'objet dont on veut trouver la mise à jour.
 *  - $present permet de controler si l'on veut que l'objet final soit bien présent localement.
 *  - $synchro permet ou non la synchronisation des liens et objets auprès d'entités tièrces,
 *      en clair on télécharge ce qui manque au besoin lors du parcours du graphe.
 *  - $restrict permet de ne parcourir les branche que sur des liens signés des entités marquées comme autorités.
 *
 * Fonction avec utilisation du cache si possible.
 * Ne tient pas compte de $synchro pour le cache.
 * Pas de cache si la recherche est restrainte aux autorités.
 *
 * @param string $nid
 * @param boolean $present
 * @param boolean $synchro
 * @param boolean $restrict
 * @return string
 */
function _lnkGraphResolv($nid, $present = true, $synchro = false, $restrict = false)
{
    global $nebule_permitautosync, $nebuleCachelibpp_l_grx;

    // Lit au besoin le cache.
    if (!$restrict && isset($nebuleCachelibpp_l_grx[$nid][$present]))
        return $nebuleCachelibpp_l_grx[$nid][$present];

    // Active la synchronisation automatique au besoin.
    if ($nebule_permitautosync)
        $synchro = true;

    $visited = array();
    $res = _lnkGraphResolvOne($nid, $visited, $present, $synchro, $restrict);
    unset($visited);
    if ($res == '0')
        $res = $nid;

    if (getConfiguration('permitBufferIO') && !$restrict)
        $nebuleCachelibpp_l_grx[$nid][$present] = $res;

    return $res;
}

/** FIXME
 * Link -
 *
 * @param $nid
 * @param array $table
 * @param array $filter
 * @param false $withinvalid
 */
function _lnkFind($nid, &$table, $filter, $withinvalid = false)
{ // Liste et filtre les liens sur des actions et objets dans un ordre déterminé.
    // - $object objet dont les liens sont à lire.
    // - $table table dans laquelle seront retournés les liens.
    // - $action filtre sur l'action.
    // - $srcobj filtre sur un objet source.
    // - $dstobj filtre sur un objet destination.
    // - $metobj filtre sur un objet meta.
    // - $withinvalid optionnel pour autoriser la lecture des liens invalides.
    //
    // Les liens sont triés par ordre chronologique et les liens marqués comme supprimés sont retirés de la liste.
    //
    // Version non inclusive, càd liens x de l'entité courante valable pour tous les liens ciblés.
    global $nebulePublicEntity;

    $followXOnSameDate = true; // TODO à supprimer.

    $bl_rl_req = $filter['bl/rl/req'];
    $bl_rl_nid1 = $filter['bl/rl/nid1'];
    $bl_rl_nid2 = $filter['bl/rl/nid2'];
    $bl_rl_nid3 = $filter['bl/rl/nid3'];
    $bl_rl_nid4 = $filter['bl/rl/nid4'];

    $linkdate = array();
    $tmptable = array();
    $i1 = 0;
    _lnkListOnOneFilter($nid, $tmptable, $bl_rl_req, $bl_rl_nid3, $withinvalid);
    foreach ($tmptable as $n => $t) {
        $linkdate [$n] = $t [3];
    }
    array_multisort($linkdate, SORT_STRING, SORT_ASC, $tmptable); // Tri par date.
    foreach ($tmptable as $tline) {
        if ($tline [4] == 'x')
            continue 1; // Suppression de l'affichage des liens x.
        if ($bl_rl_req != '' && $tline [4] != $bl_rl_req)
            continue 1;
        if ($bl_rl_nid1 != '' && $tline [5] != $bl_rl_nid1)
            continue 1;
        if ($bl_rl_nid2 != '' && $tline [6] != $bl_rl_nid2)
            continue 1;
        if ($bl_rl_nid3 != '' && $tline [7] != $bl_rl_nid3)
            continue 1;
        if ($bl_rl_nid4 != '' && $tline [8] != $bl_rl_nid4) // TODO à vérifier
            continue 1;
        foreach ($tmptable as $vline) {
            if (($vline [4] == 'x') && ($tline [4] != 'x') && ($tline [5] == $vline [5]) && ($tline [6] == $vline [6]) && ($tline [7] == $vline [7]) && (($vline [2] == $tline [2]) || ($vline [2] == $nebulePublicEntity)) && ((($followXOnSameDate) && (strtotime($tline [3]) < strtotime($vline [3]))) || (strtotime($tline [3]) <= strtotime($vline [3]))))
                continue 2;
        }
        foreach ($table as $vline) // Suppression de l'affichage des liens en double, même à des dates différentes.
        {
            if (($tline [2] == $vline [2]) && ($tline [4] == $vline [4]) && ($tline [5] == $vline [5]) && ($vline [9] == 1 || $vline [9] == -1) && ($tline [6] == $vline [6]) && ($tline [7] == $vline [7]))
                continue 2;
        }
        // Remplissage de la table des résultats.
        $table [$i1] [0] = $tline [0];
        $table [$i1] [1] = $tline [1];
        $table [$i1] [2] = $tline [2];
        $table [$i1] [3] = $tline [3];
        $table [$i1] [4] = $tline [4];
        $table [$i1] [5] = $tline [5];
        $table [$i1] [6] = $tline [6];
        $table [$i1] [7] = $tline [7];
        $table [$i1] [8] = $tline [8];
        $table [$i1] [9] = $tline [9];
        $table [$i1] [10] = $tline [10];
        $table [$i1] [11] = $tline [11];
        $table [$i1] [12] = $tline [12];
        $i1++;
    }
}

/** FIXME
 * Link - Liste et filtre les liens sur des actions et objets dans un ordre déterminé.
 *  - $object objet dont les liens sont à lire.
 *  - $table table dans laquelle seront retournés les liens.
 *  - $action filtre sur l'action.
 *  - $srcobj filtre sur un objet source.
 *  - $dstobj filtre sur un objet destination.
 *  - $metobj filtre sur un objet meta.
 *  - $withinvalid optionnel pour autoriser la lecture des liens invalides.
 *
 * Les liens sont triés par ordre chronologique et les liens marqués comme supprimés sont retirés de la liste.
 *
 * Version inclusive, càd liens x valable uniquement sur les liens du même signataire.
 *
 * @param string $nid
 * @param array $table
 * @param string $action
 * @param string $srcobj
 * @param string $dstobj
 * @param string $metobj
 * @param boolean $withinvalid
 * @return null
 */
function _lnkFindInclusive($nid, &$table, $action, $srcobj, $dstobj, $metobj, $withinvalid = false): void
{
    $followXOnSameDate = true; // TODO à supprimer.

    $linkdate = array();
    $tmptable = array();
    $i1 = 0;

    _lnkListOnOneFilter($nid, $tmptable, $action, $metobj, $withinvalid);

    foreach ($tmptable as $n => $t) {
        $linkdate [$n] = $t [3];
    }

    // Tri par date.
    array_multisort($linkdate, SORT_STRING, SORT_ASC, $tmptable);

    foreach ($tmptable as $tline) {
        if ($tline [4] == 'x') {
            continue 1; // Suppression de l'affichage des liens x.
        }
        if ($action != '' && $tline [4] != $action) {
            continue 1;
        }
        if ($srcobj != '' && $tline [5] != $srcobj) {
            continue 1;
        }
        if ($dstobj != '' && $tline [6] != $dstobj) {
            continue 1;
        }
        if ($metobj != '' && $tline [7] != $metobj) {
            continue 1;
        }

        // Filtre du lien.
        foreach ($tmptable as $vline) {
            if ($vline [4] == 'x'
                && $tline [4] != 'x'
                && $tline [5] == $vline [5]
                && $tline [6] == $vline [6]
                && $tline [7] == $vline [7]
                && $vline [2] == $tline [2]
                && ($vline [9] == 1
                    || $vline [9] == -1
                )
                && (($followXOnSameDate
                        && strtotime($tline [3]) < strtotime($vline [3])
                    )
                    || strtotime($tline [3]) <= strtotime($vline [3])
                )
            ) {
                continue 2;
            }
        }

        // Suppression de l'affichage des liens en double, même à des dates différentes.
        foreach ($table as $vline) {
            if ($tline [2] == $vline [2]
                && $tline [4] == $vline [4]
                && $tline [5] == $vline [5]
                && $tline [6] == $vline [6]
                && $tline [7] == $vline [7]
            ) {
                continue 2;
            }
        }
        // Remplissage de la table des résultats.
        $table [$i1] [0] = $tline [0];
        $table [$i1] [1] = $tline [1];
        $table [$i1] [2] = $tline [2];
        $table [$i1] [3] = $tline [3];
        $table [$i1] [4] = $tline [4];
        $table [$i1] [5] = $tline [5];
        $table [$i1] [6] = $tline [6];
        $table [$i1] [7] = $tline [7];
        $table [$i1] [8] = $tline [8];
        $table [$i1] [9] = $tline [9];
        $table [$i1] [10] = $tline [10];
        $table [$i1] [11] = $tline [11];
        $table [$i1] [12] = $tline [12];
        $i1++;
    }
    unset($linkdate, $i1, $n, $t, $tline);
}

/**
 * Link - Read links, parse and filter each links.
 * @param string $nid
 * @param array $result
 * @param array $filter
 * @return void
 */
function _lnkGetList(string &$nid, array &$result, array $filter): void
{
    if ($nid == '0' || !io_checkNodeHaveLink($nid))
        return;

    $lines = array();
    io_linksRead($nid, $lines);
    foreach ($lines as $line) {
        if (_lnkVerify($line)) {
            $link = _lnkParse($line);
            if (_lnkTestNotSuppressed($link, $lines) && _lnkFilter($link, $filter))
                $result [] = $link;
        }
    }
}

/**
 * Link - Test if link have been marked as suppressed with a link type x.
 * @param array $link
 * @param array $lines
 * @return bool
 */
function _lnkTestNotSuppressed(array &$link, array &$lines): bool
{
    foreach ($lines as $line) {
        if (strpos($line, '/x>') === false)
            continue;
        if (_lnkVerify($line)) {
            $linkCompare = _lnkParse($line);
            if ($linkCompare['bl/rl/req'] == 'x'
                && _lnkCompareDate($link['bl/rc/mod'], $link['bl/rc/chr'], $linkCompare['bl/rc/mod'], $linkCompare['bl/rc/chr']) < 0
            )
                return false;
        }
    }
    return true;
}

/**
 * Link - Compare if date 1 is lower, greater or equal to date 2.
 * Return -1 if lower, +1 if greater and 0 if equal.
 * FIXME Only support date in mode 0!
 * @param string $mod1
 * @param string $chr1
 * @param string $mod2
 * @param string $chr2
 * @return int
 */
function _lnkCompareDate(string $mod1, string $chr1, string $mod2, string $chr2): int
{
    // Convert first date.
    if ($mod1 == '0')
        $numChr1 = (double)$chr1;
    else
        $numChr1 = (int)$chr1;

    // Convert second date.
    if ($mod2 == '0')
        $numChr2 = (double)$chr2;
    else
        $numChr2 = (int)$chr2;

    // Comparing
    if ($numChr1 < $numChr2)
        return -1;
    elseif ($numChr1 > $numChr2)
        return 1;
    else
        return 0;
}

/**
 * Link - Test if a link match a filter.
 * Filtering on have bl/rl/req, bl/rl/nid1, bl/rl/nid2, bl/rl/nid3, bl/rl/nid4, bl/rl/nid*, bs/rs/nid, or not have.
 * TODO revoir pour les liens de type x...
 * @param array $link
 * @param array $filter
 * @return bool
 */
function _lnkFilter(array $link, array $filter): bool
{
    $ok = false;

    // Positive filtering
    if (isset($filter['bl/rl/req']) && $link['bl/rl/req'] == $filter['bl/rl/req'])
        $ok = true;
    if (isset($filter['bl/rl/nid1']) && $link['bl/rl/nid1'] == $filter['bl/rl/nid1'])
        $ok = true;
    if (isset($filter['bl/rl/nid2']) && isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['bl/rl/nid2'])
        $ok = true;
    if (isset($filter['bl/rl/nid3']) && isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['bl/rl/nid3'])
        $ok = true;
    if (isset($filter['bl/rl/nid4']) && isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['bl/rl/nid4'])
        $ok = true;
    if (isset($filter['bl/rl/nid*']) && ( $link['bl/rl/nid1'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['bl/rl/nid*']
        )
    )
        $ok = true;
    if (isset($filter['bs/rs/nid']) && $link['bs/rs/nid'] == $filter['bs/rs/nid'])
        $ok = true;

    if (!$ok)
        return $ok;

    // Negative filtering
    if (isset($filter['!bl/rl/req']) && $link['bl/rl/req'] == $filter['!bl/rl/req'])
        $ok = false;
    if (isset($filter['!bl/rl/nid1']) && $link['bl/rl/nid1'] == $filter['!bl/rl/nid1'])
        $ok = false;
    if (isset($filter['!bl/rl/nid2']) && isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['!bl/rl/nid2'])
        $ok = false;
    if (isset($filter['!bl/rl/nid3']) && isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['!bl/rl/nid3'])
        $ok = false;
    if (isset($filter['!bl/rl/nid4']) && isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['!bl/rl/nid4'])
        $ok = false;
    if (isset($filter['!bl/rl/nid*']) && ( $link['bl/rl/nid1'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['!bl/rl/nid*']
        )
    )
        $ok = false;
    if (isset($filter['!bs/rs/nid']) && $link['bs/rs/nid'] == $filter['!bs/rs/nid'])
        $ok = false;

    return $ok;
}

/** FIXME
 * Link -
 *
 * @param string $nid
 * @param array $result
 * @param string $filtreact
 * @param string $filtreobj
 * @param false $withinvalid
 * @return void
 */
function _lnkListOnFullFilter(string $nid, array &$result, string $filtreact = '-', string $filtreobj = '', bool $withinvalid = false): void
{ // Liste et filtre les liens sur des actions et objets dans un ordre indéterminé.
    // - $object objet dont les liens sont à lire.
    // - $table table dans laquelle seront retournés les liens.
    // - $filtreact filtre optionnel sur l'action.
    // - $filtreobj filtre optionnel sur un objet source, destination ou meta.
    // - $withinvalid optionnel pour autoriser la lecture des liens invalides.
    //
    // Les liens sont triés par ordre chronologique et les liens marqués comme supprimés sont retirés de la liste.
    global $nebulePublicEntity;

    $followXOnSameDate = true; // TODO à supprimer.

    $linkdate = array();
    $tmptable = array();
    $i1 = 0;
    if ($filtreact == '')
        $filtreact = '-';
    _lnkListOnOneFilter($nid, $tmptable, $filtreact, $filtreobj, $withinvalid);
    foreach ($tmptable as $n => $t) {
        $linkdate [$n] = $t [3];
    }
    array_multisort($linkdate, SORT_STRING, SORT_ASC, $tmptable); // Tri par date.
    foreach ($tmptable as $tline) {
        if ($tline [4] == 'x')
            continue 1; // Suppression de l'affichage des liens x.
        foreach ($tmptable as $vline) // Suppression des liens marqués supprimés.
        {
            if (($vline [4] == 'x') && ($tline [4] != 'x') && ($tline [5] == $vline [5]) && ($tline [6] == $vline [6]) && ($tline [7] == $vline [7]) && (($vline [2] == $tline [2]) || ($vline [2] == $nebulePublicEntity)) && ($vline [9] == 1 || $vline [9] == -1) && ((($followXOnSameDate) && (strtotime($tline [3]) < strtotime($vline [3]))) || (strtotime($tline [3]) <= strtotime($vline [3]))))
                continue 2;
        }
        foreach ($result as $vline) // Suppression de l'affichage des liens en double, même à des dates différentes.
        {
            if (($tline [2] == $vline [2]) && ($tline [4] == $vline [4]) && ($tline [5] == $vline [5]) && ($tline [6] == $vline [6]) && ($tline [7] == $vline [7]))
                continue 2;
        }
        // Remplissage de la table des résultats.
        $result [$i1] [0] = $tline [0];
        $result [$i1] [1] = $tline [1];
        $result [$i1] [2] = $tline [2];
        $result [$i1] [3] = $tline [3];
        $result [$i1] [4] = $tline [4];
        $result [$i1] [5] = $tline [5];
        $result [$i1] [6] = $tline [6];
        $result [$i1] [7] = $tline [7];
        $result [$i1] [8] = $tline [8];
        $result [$i1] [9] = $tline [9];
        $result [$i1] [10] = $tline [10];
        $result [$i1] [11] = $tline [11];
        $result [$i1] [12] = $tline [12];
        $i1++;
    }
    unset($linkdate);
    unset($i1);
    unset($n);
    unset($t);
    unset($tline);
}

/** FIXME
 * Link -
 *
 * @param string $nid
 * @param array $result
 * @param string $filtreact
 * @param string $filtreobj
 * @param false $withinvalid
 * @return void
 */
function _lnkListOnOneFilter(string &$nid, array &$result, string $filtreact = '-', string $filtreobj = '', bool $withinvalid = false): void
{ // Lit tous les liens d'un objet.
    // - $object objet dont les liens sont à lire.
    // - $table table dans laquelle seront retournés les liens.
    // - $filtreact filtre optionnel sur l'action.
    // - $filtreobj filtre optionnel sur un objet source, destination ou meta.
    // - $withinvalid optionnel pour autoriser la lecture des liens invalides.

    $checkSignOnList = getConfiguration('permitCheckSignOnList');

    if ($nid == '0' || !io_checkNodeHaveLink($nid))
        return;

    if (!getConfiguration('permitListInvalidLinks'))
        $withinvalid = false; // Si pas autorisé, refuse de lire les liens invalides.
    if ($filtreact == '')
        $filtreact = '-';
    $version = ''; // version du lien.
    $n = 0; // indice dans la table des resultats.
    $tline = array(); // table d'un lien en cours de lecture et d'analyse.
    $lines = array();
    io_linksRead($nid, $lines); // liens a lire et analyser.
    $IOMaxlink = getConfiguration('ioReadMaxLinks');
    foreach ($lines as $line) {
        $i = 1;
        if (substr($line, 0, 21) == 'nebule/liens/version/') {
            $version = $line;
            continue 1;
        } // Permet la prise en compte de differentes versions de liens - non utilise aujourd'hui.
        $okfiltre = false; // Résultat du filtre, sera à true si dans les critères.
        $tline [0] = '';
        $tline [1] = '';
        $tline [2] = '';
        $tline [3] = ''; // Initialisation des champs pour éviter les warn des logs.
        $tline [4] = '';
        $tline [5] = '';
        $tline [6] = '';
        $tline [7] = '';
        $loopelem = strtok(trim($line), '_');
        while ($loopelem !== false) {
            $tline [$i] = $loopelem;
            $i++;
            $loopelem = strtok('_');
        } // Extrait le lien.
        if ($filtreobj == '' && ($tline [4] == $filtreact || $filtreact == '-'))
            $okfiltre = true;
        if (($tline [5] == $filtreobj || $tline [6] == $filtreobj || $tline [7] == $filtreobj) && ($tline [4] == $filtreact || $filtreact == '-'))
            $okfiltre = true; // Vérifie le lien par rapport au filtre.
        if ($tline [4] == 'u' || $tline [4] == 'e' || ($tline [4] == 'x' && $filtreact != 'x'))
            $okfiltre = true; // Quelque soit le filtre, ajoute les liens de type u e et x.
        if ($i != 8)
            $okfiltre = false; // Si le lien est invalide, le filtre.
        if ($okfiltre) // Si le lien correspond au filtre, l'enregistre dans la table des resultats.
        {
            if ($checkSignOnList)
                $verify = _lnkVerify(trim($line));
            else
                $verify = -1;
            if ($verify == 1 || $verify == -1 || $withinvalid) // Le lien doit être vérifié ou la vérification désactivée.
            {
                $result [$n] [0] = trim($line); // Remplit le tableau à retourner.
                $result [$n] [1] = $tline [1];
                $result [$n] [2] = $tline [2];
                $result [$n] [3] = $tline [3];
                $result [$n] [4] = $tline [4];
                $result [$n] [5] = $tline [5];
                $result [$n] [6] = $tline [6];
                $result [$n] [7] = $tline [7];
                $result [$n] [8] = $tline [8];
                $result [$n] [9] = $version;
                $result [$n] [10] = $verify;
                $result [$n] [11] = openssl_error_string();
                $result [$n] [12] = 0; // Pour pondération.
                $n++;
            }
            if ($n >= $IOMaxlink) {
                break 1; // TODO WARNING --- BEURK ---
            }
        }
    }
    unset($count);
    unset($n);
    unset($i);
    unset($version);
    unset($okfiltre);
    unset($line);
    unset($tline);
    unset($loopelem);
}

/** FIXME
 * Link -
 *
 * @param $nid
 * @return void
 */
function _lnkDownloadAnywhere(string $nid): void
{ // Télécharge les liens de l'objet sur plusieurs localisations.
    // - $object l'objet dont les liens sont à télécharger.
    if (!getConfiguration('permitSynchronizeLink') || $nid == '0')
        return;

    $table = array();
    $hashtype = _objGetNID('nebule/objet/entite/localisation', getConfiguration('cryptoHashAlgorithm'));
    $okobj = array();
    $count = 1;
    $okobj [1] = '';
    _lnkListOnFullFilter($hashtype, $table);
    foreach ($table as $itemtable) {
        if (($itemtable [7] == $hashtype) && ($itemtable [4] == 'l')) {
            $t = true;
            for ($j = 1; $j < $count; $j++) {
                if ($itemtable [6] == $okobj [$j]) {
                    $t = false;
                }
            }
            if ($t) {
                $lnk = '';
                _objGetLocalContent($itemtable [6], $lnk);
                if ($lnk != '') {
                    _lnkDownloadOnLocation($nid, $lnk);
                }
                $okobj [$count] = $itemtable [6];
                $count++;
            }
        }
    }
    unset($count);
    unset($j);
    unset($t);
    unset($lnk);
    unset($table);
    unset($okobj);
    unset($hashtype);
}

/** FIXME
 * Link - Download links on web location for a node.
 * Only valid links are writed on local filesystem.
 *
 * @param string $nid
 * @param string $location
 * @return integer
 */
function _lnkDownloadOnLocation(string $nid, string $location): int
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('nebulePermitSynchronizeLink')
        || !_nodCheckNID($nid, false)
        || $location == ''
        || !is_string($location) // TODO renforcer la vérification de l'URL.
    )
        return 0;

    $count = 0;

    // WARNING ajouter vérification du lien type texte
    $distobj = fopen($location . '/l/' . $nid, 'r');
    if ($distobj) {
        while (!feof($distobj)) {
            $line = trim(fgets($distobj));
            $verify = _lnkVerify($line);
            if ($verify == 1
                || $verify == -1
            ) {
                _lnkWrite($line);
                $count++;
            }
        }
        fclose($distobj);
    }
    return $count;
}

/**
 * Link - Check block BH on link.
 *
 * @param string $bh
 * @return bool
 */
function _lnkCheckBH(string &$bh): bool
{
    if (strlen($bh) > 15) return false;

    $rf = strtok($bh, '/');
    $rv = strtok('/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RF and RV.
    if (!_lnkCheckRF($rf)) return false;
    if (!_lnkCheckRV($rv)) return false;

    return true;
}

/**
 * Link - Check block RF on link.
 *
 * @param string $rf
 * @return bool
 */
function _lnkCheckRF(string &$rf): bool
{
    if (strlen($rf) > 11) return false;

    // Check items from RF : APP:TYP
    $app = strtok($rf, ':');
    if ($app != 'nebule') return false;
    $typ = strtok(':');
    if ($typ != 'link') return false;

    // Check registry overflow
    if (strtok(':') !== false) return false;

    return true;
}

/**
 * Link - Check block RV on link.
 *
 * @param string $rv
 * @return bool
 */
function _lnkCheckRV(string &$rv): bool
{
    if (strlen($rv) > 3) return false;

    // Check items from RV : VER:SUB
    $ver = strtok($rv, ':');
    $sub = strtok(':');
    if ("$ver:$sub" != NEBULE_LIBPP_LINK_VERSION) return false;

    // Check registry overflow
    if (strtok(':') !== false) return false;

    return true;
}

/**
 * Link - Check block BL on link.
 *
 * @param string $bl
 * @return bool
 */
function _lnkCheckBL(string &$bl): bool
{
    if (strlen($bl) > 4096) return false; // TODO à revoir.

    $rc = strtok($bl, '/');
    $rl = strtok('/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RC and RL.
    if (!_lnkCheckRC($rc)) return false;
    if (!_lnkCheckRL($rl)) return false;

    return true;
}

/**
 * Link - Check block RC on link.
 *
 * @param string $rc
 * @return bool
 */
function _lnkCheckRC(string &$rc): bool
{
    if (strlen($rc) > 17) return false;

    // Check items from RC : MOD>CHR
    $mod = strtok($rc, '>');
    if ($mod != '0') return false;
    $chr = strtok('>');
    if (strlen($chr) != 15) return false;
    if (!ctype_digit($chr)) return false;

    // Check registry overflow
    if (strtok('>') !== false) return false;

    return true;
}

/**
 * Link - Check block RL on link.
 *
 * @param string $rl
 * @return bool
 */
function _lnkCheckRL(string &$rl): bool
{
    if (strlen($rl) > 4096) return false; // TODO à revoir.

    // Extract items from RL 1 : REQ>NID>NID>NID>NID
    $req = strtok($rl, '>');
    $rl1nid1 = strtok('>');
    if ($rl1nid1 === false) $rl1nid1 = '';
    $rl1nid2 = strtok('>');
    if ($rl1nid2 === false) $rl1nid2 = '';
    $rl1nid3 = strtok('>');
    if ($rl1nid3 === false) $rl1nid3 = '';
    $rl1nid4 = strtok('>');
    if ($rl1nid4 === false) $rl1nid4 = '';

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check REQ, NID1, NID2, NID3 and NID4.
    if (!_lnkCheckREQ($req)) return false;
    if (!_nodCheckNID($rl1nid1, false)) return false;
    if (!_nodCheckNID($rl1nid2, true)) return false;
    if (!_nodCheckNID($rl1nid3, true)) return false;
    if (!_nodCheckNID($rl1nid4, true)) return false;

    return true;
}

/**
 * Link - Check block REQ on link.
 *
 * @param string $req
 * @return bool
 */
function _lnkCheckREQ(string &$req): bool
{
    if ($req != 'l'
        && $req != 'f'
        && $req != 'u'
        && $req != 'd'
        && $req != 'e'
        && $req != 'c'
        && $req != 'k'
        && $req != 's'
        && $req != 'x'
    )
        return false;

    return true;
}

/**
 * Link - Check block BS on link.
 *
 * TODO make a loop on many RS avoid attack on link signs fusion.
 *
 * @param string $bh
 * @param string $bl
 * @param string $bs
 * @return bool
 */
function _lnkCheckBS(string &$bh, string &$bl, string &$bs): bool
{
    if (strlen($bs) > 4096) return false; // TODO à revoir.

    $rs = strtok($bs, '/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check content RS 1 NID 1 : hash.algo.size
    if (!_nodCheckNID($rs1nid1, false)) return false;
    if (!_lnkCheckRS($rs, $bh, $bl)) return false;

    return true;
}

/**
 * Link - Check block RS on link.
 *
 * @param string $rs
 * @param string $bh
 * @param string $bl
 * @return bool
 */
function _lnkCheckRS(string &$rs, string &$bh, string &$bl): bool
{
    if (strlen($rs) > 4096) return false; // TODO à revoir.

    // Extract items from RS : NID>SIG
    $nid = strtok($rs, '>');
    $sig = strtok('>');

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check content RS 1 NID 1 : hash.algo.size
    if (!_nodCheckNID($nid, false)) return false;
    if (!_lnkCheckSIG($bh, $bl, $sig, $nid)) return false;

    return true;
}

/**
 * Link - Check block SIG on link.
 *
 * @param string $bh
 * @param string $bl
 * @param string $sig
 * @param string $nid
 * @return boolean
 */
function _lnkCheckSIG(string &$bh, string &$bl, string &$sig, string &$nid): bool
{
    if (strlen($sig) > 4096) return false; // TODO à revoir.

    // Check hash value.
    $sign = strtok($sig, '.');
    if (strlen($sign) < NID_MIN_HASH_SIZE) return false;
    if (strlen($sign) > NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($sign)) return false;

    // Check algo value.
    $algo = strtok('.');
    if (strlen($algo) < NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    $size = strtok('.');
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < NID_MIN_HASH_SIZE) return false;
    if ((int)$size > NID_MAX_HASH_SIZE) return false;
    if (strlen($sign) != (int)$size) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    if (!getConfiguration('permitCheckSignOnVerify')) return true;
    if (io_checkNodeHaveContent($nid) && _objCheckContent($nid)) {
        $data = $bh . '_' . $bl;
        $hash = cryptoGetDataHash($data, $algo . '.' . $size);
        return cryptoAsymetricVerify($sign, $hash, $nid);
    }

    return false;
}

/**
 * Link - Check if hash algorythme used is supported.
 *
 * TODO
 *
 * @param string $algo
 * @param string $size
 * @return bool
 */
function _lnkCheckhashalgo(string &$algo, string &$size): bool
{
    // TODO
    return true;
}

/**
 * Link - Verify link consistency.
 *
 * Limites :
 * L : 1 BH + 1 BL + 1 BS
 * BH : 1 RF + 1 RV
 * BL : 1 RC + 1 RL
 * BS : 1 RS
 * RF : 1 APP + 1 TYP
 * APP : 'nebule'
 * TYP : 'link'
 * MOD : '0'
 *
 * @param string $link
 * @return boolean
 */
function _lnkVerify(string $link): bool
{
    if (strlen($link) > 4096) return false; // TODO à revoir.
    if (strlen($link) == 0) return false;

    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($link), '_');
    $bl = strtok('_');
    $bs = strtok('_');

    // Check link overflow
    if (strtok('_') !== false) return false;

    // Check BH, BL and BS.
    if (!_lnkCheckBH($bh)) return false;
    if (!_lnkCheckBL($bl)) return false;
    if (!_lnkCheckBS($bh, $bl, $bs)) return false;

    return true;
}

/**
 * Link - Explode link and it's values into array.
 * 
 * @param string $link
 * @return array
 */
function _lnkParse(string $link): array
{
    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($link), '_');
    $bl = strtok('_');
    $bs = strtok('_');

    $bh_rf = strtok($bh, '/');
    $bh_rv = strtok('/');

    // Check items from RF : APP:TYP
    $bh_rf_app = strtok($bh_rf, ':');
    $bh_rf_typ = strtok(':');

    // Check items from RV : VER:SUB
    $bh_rv_ver = strtok($bh_rv, ':');
    $bh_rv_sub = strtok(':');

    $bl_rc = strtok($bl, '/');
    $bl_rl = strtok('/');

    // Check items from RC : MOD>CHR
    $bl_rc_mod = strtok($bl_rc, '>');
    $bl_rc_chr = strtok('>');

    // Extract items from RL 1 : REQ>NID>NID>NID>NID
    $bl_rl_req = strtok($bl_rl, '>');
    $bl_rl_nid1 = strtok('>');
    $bl_rl_nid2 = strtok('>');
    if ($bl_rl_nid2 === false) $bl_rl_nid2 = '';
    $bl_rl_nid3 = strtok('>');
    if ($bl_rl_nid3 === false) $bl_rl_nid3 = '';
    $bl_rl_nid4 = strtok('>');
    if ($bl_rl_nid4 === false) $bl_rl_nid4 = '';

    $bs_rs = strtok($bs, '/');

    // Extract items from RS : NID>SIG
    $bs_rs_nid = strtok($bs_rs, '>');
    $bs_rs_sig = strtok('>');

    // Check hash value.
    $bs_rs_sig_sign = strtok($bs_rs_sig, '.');

    // Check algo value.
    $bs_rs_sig_algo = strtok('.');

    // Check size value.
    $bs_rs_sig_size = strtok('.');

    return array(
        'link' => $link, // original link
        'bh' => $bh,
        'bh/rf' => $bh_rf,
        'bh/rf/app' => $bh_rf_app,
        'bh/rf/typ' => $bh_rf_typ,
        'bh/rv' => $bh_rv,
        'bh/rv/ver' => $bh_rv_ver,
        'bh/rv/sub' => $bh_rv_sub,
        'bl' => $bl,
        'bl/rc' => $bl_rc,
        'bl/rc/mod' => $bl_rc_mod,
        'bl/rc/chr' => $bl_rc_chr,
        'bl/rl' => $bl_rl,
        'bl/rl/req' => $bl_rl_req,
        'bl/rl/nid1' => $bl_rl_nid1,
        'bl/rl/nid2' => $bl_rl_nid2,
        'bl/rl/nid3' => $bl_rl_nid3,
        'bl/rl/nid4' => $bl_rl_nid4,
        'bs' => $bs,
        'bs/rs' => $bs_rs,
        'bs/rs/nid' => $bs_rs_nid,
        'bs/rs/sig' => $bs_rs_sig,
        'bs/rs/sig/sign' => $bs_rs_sig_sign,
        'bs/rs/sig/algo' => $bs_rs_sig_algo,
        'bs/rs/sig/size' => $bs_rs_sig_size,
    );
}

/**
 * Link - Write link into parts files.
 *
 * @param $link
 * @return boolean
 */
function _lnkWrite($link): bool
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteLink')
        || !_lnkVerify($link)
    )
        return false;

    // Extract link parts.
    $parseLink = _lnkParse($link);

    // Write link into parts files.
    $result = io_linkWrite($parseLink['bl/rl/nid1'], $link);
    if ($parseLink['bl/rl/nid2'] != '')
        $result &= io_linkWrite($parseLink['bl/rl/nid2'], $link);
    if ($parseLink['bl/rl/nid3'] != '')
        $result &= io_linkWrite($parseLink['bl/rl/nid3'], $link);
    if ($parseLink['bl/rl/nid4'] != '')
        $result &= io_linkWrite($parseLink['bl/rl/nid4'], $link);
    if (getConfiguration(permitAddLinkToSigner))
        $result &= io_linkWrite($parseLink['bs/rs/nid'], $link);

    return $result;
}


/*
 * ------------------------------------------------------------------------------------------
 * Fonctions bas niveau.
 * ------------------------------------------------------------------------------------------
 */

// I/O sont les fonctions liées aux accès disque. Peut être modifié pour permettre un accès en BDD ou autre.
/**
 * I/O - Start I/O subsystem with checks.
 *
 * @return boolean
 */
function io_open(): bool
{
    if (!io_checkLinkFolder() || !io_checkObjectFolder())
        return false;
    return true;
}

/**
 * I/O - Check folder status and writeability for links.
 *
 * @return boolean
 */
function io_checkLinkFolder(): bool
{
    // Check if exist.
    if (!file_exists(LOCAL_LINKS_FOLDER) || !is_dir(LOCAL_LINKS_FOLDER)) {
        addLog('I/O no folder for links.', 'error', __FUNCTION__, '5306de5f');
        return false;
    }

    // Check writeability.
    if (getConfiguration('permitWrite') && getConfiguration('permitWriteLink')) {
        $data = cryptoGetPseudoRandom(2048);
        $name = LOCAL_LINKS_FOLDER . '/writest' . bin2hex(cryptoGetPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            addLog('I/O error on folder for links.', 'error', __FUNCTION__, 'f72e3a86');
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            addLog('I/O error on folder for links.', 'error', __FUNCTION__, '6f012d85');
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            addLog('I/O error on folder for links.', 'error', __FUNCTION__, 'fd499fcb');
            return false;
        }
        if (!unlink($name)) {
            addLog('I/O error on folder for links.', 'error', __FUNCTION__, '8e0caa66');
            return false;
        }
    }

    return true;
}

/**
 * I/O - Check folder status and writeability for objects.
 *
 * @return boolean
 */
function io_checkObjectFolder(): bool
{
    // Check if exist.
    if (!file_exists(LOCAL_OBJECTS_FOLDER) || !is_dir(LOCAL_OBJECTS_FOLDER) ) {
        addLog('I/O no folder for objects.', 'error', __FUNCTION__, 'b0cdeafe');
        return false;
    }

    // Check writeability.
    if (getConfiguration('permitWrite') && getConfiguration('permitWriteObject')) {
        $data = cryptoGetPseudoRandom(2048);
        $name = LOCAL_OBJECTS_FOLDER . '/writest' . bin2hex(cryptoGetPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            addLog('I/O error on folder for objects.', 'error', __FUNCTION__, '1327da69');
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            addLog('I/O error on folder for objects.', 'error', __FUNCTION__, '2b451a2a');
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            addLog('I/O error on folder for objects.', 'error', __FUNCTION__, '634072e5');
            return false;
        }
        if (!unlink($name)) {
            addLog('I/O error on folder for objects.', 'error', __FUNCTION__, '2b397869');
            return false;
        }
    }

    return true;
}

/**
 * I/O - Try to create folder for links.
 *
 * @return boolean
 */
function io_createLinkFolder(): bool
{
    if (getConfiguration('permitWrite')
        && getConfiguration('permitWriteLink')
        && !file_exists(LOCAL_LINKS_FOLDER)
    )
        mkdir(LOCAL_LINKS_FOLDER);

    return io_checkLinkFolder();
}

/**
 * I/O - Try to create folder for objects.
 *
 * @return boolean
 */
function io_createObjectFolder(): bool
{
    if (getConfiguration('permitWrite')
        && getConfiguration('permitWriteObject')
        && !file_exists(LOCAL_OBJECTS_FOLDER)
    )
        mkdir(LOCAL_OBJECTS_FOLDER);

    return io_checkObjectFolder();
}

/**
 * I/O - Check if node link's file is present, which mean node have one or more links.
 *
 * @param string $nid
 * @return boolean
 */
function io_checkNodeHaveLink(string &$nid): bool
{
    if (file_exists(LOCAL_LINKS_FOLDER . '/' . $nid))
        return true;
    return false;
}

/**
 * I/O - Check if node object's content is present, which mean node is an object with a content.
 *
 * @param string $nid
 * @return boolean
 */
function io_checkNodeHaveContent(string &$nid): bool
{
    if (file_exists(LOCAL_OBJECTS_FOLDER . '/' . $nid))
        return true;
    return false;
}

/**
 * I/O - Read object's links.
 * Return array of links, one string per link, maybe empty.
 *
 * @param string $nid
 * @param array $lines
 * @param integer $maxLinks
 * @return array
 */
function io_linksRead(string &$nid, array &$lines, int $maxLinks = 0): array
{
    $count = 0;

    if (!_nodCheckNID($nid) || !io_checkNodeHaveLink($nid))
        return $lines;
    if ($maxLinks == 0)
        $maxLinks = getConfiguration('ioReadMaxLinks');

    $links = file(LOCAL_LINKS_FOLDER . '/' . $nid);
    foreach ($links as $link) {
        $lines [$count] = $link;
        _metrologyAdd('lr');
        $count++;
        if ($count > $maxLinks)
            break 1;
    }
    return $lines;
}

/**
 * I/O - Write a link to a node.
 *
 * @param string $nid
 * @param string $link
 * @return boolean
 */
function io_linkWrite(string &$nid, string &$link): bool
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteLink')
        || $nid == ''
    )
        return false;

    if (file_put_contents(LOCAL_LINKS_FOLDER . '/' . $nid, "$link\n", FILE_APPEND) === false)
        return false;
    return true;
}

/**
 * I/O - Read object content.
 * Return the read data from object.
 *
 * @param string $nid
 * @param integer $maxData
 * @return string
 */
function io_objectRead(string $nid, int $maxData = 0): string
{
    if ($maxData == 0)
        $maxData = getConfiguration('ioReadMaxData');
    if (!_nodCheckNID($nid) || !io_checkNodeHaveContent($nid))
        return '';

    $result = file_get_contents(LOCAL_OBJECTS_FOLDER . '/' . $nid, false, null, 0, $maxData);
    if ($result === false)
        $result = '';
    _metrologyAdd('or');

    return $result;
}

/**
 * I/O - Write object content.
 *
 * @param string $data
 * @return boolean
 */
function io_objectWrite(string &$data): bool
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
    )
        return false;

    $nid = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));

    if (io_checkNodeHaveContent($nid))
        return true;

    if (file_put_contents(LOCAL_OBJECTS_FOLDER . '/' . $nid, $data) === false)
        return false;
    return true;
}

/**
 * I/O - Synchronize object content on other location.
 *
 * @param string $nid
 * @param array $location
 * @return bool
 */
function io_objectSynchronize(string $nid, array $location): bool
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
        || !getConfiguration('permitSynchronizeObject')
        || !_nodCheckNID($nid)
        || $location == ''
        || !is_string($location) // TODO renforcer la vérification de l'URL.
        || _nodCheckBanned($nid)
    )
        return false;

    if (io_checkNodeHaveContent($nid))
        return true;

    // Téléchargement de l'objet via un fichier temporaire.
    $tmpId = bin2hex(cryptoGetPseudoRandom(8));
    $tmpIdName = '_neblibpp_o_dl1_' . $tmpId . '-' . $nid;
    $distobj = fopen($location . '/o/' . $nid, 'r');
    if ($distobj) {
        $localobj = fopen(LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName, 'w');
        if ($localobj) {
            while (($line = fgets($distobj, getConfiguration('ioReadMaxData'))) !== false) {
                fputs($localobj, $line);
            }
            fclose($localobj);
            $algo = substr($nid, strpos($nid, '.') + 1);
            if ($algo !== false)
                $hash = cryptoGetFileHash($tmpIdName, cryptoGetTranslatedHashAlgo($algo));
            else
                $hash = 'invalid';

            if ($hash . '.' . $algo == $nid)
                rename(LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName, LOCAL_OBJECTS_FOLDER . '/' . $nid);
            else
                unlink(LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName);
        }
        fclose($distobj);
    }

    if (io_checkNodeHaveContent($nid))
        return true;
    return false;
}

/**
 * I/O - Suppress object content.
 *
 * @param string $nid
 * @return boolean
 */
function io_objectDelete(string &$nid): bool
{
    if (!getConfiguration('permitWrite') || !getConfiguration('permitWriteObject') || $nid == '')
        return false;
    if (!io_checkNodeHaveContent($nid))
        return true;

    if (!unlink(LOCAL_OBJECTS_FOLDER . '/' . $nid))
    {
        addLog('Unable to delete file.', 'error', __FUNCTION__, '991b11a1');
        return false;
    }
    return true;
}

/**
 * I/O - End of work on I/O subsystem.
 *
 * @return void
 */
function io_close(): void
{
    // Rien à fermer sur un fs.
    return;
}

/*
 * ------------------------------------------------------------------------------------------
 * Others functions.
 * ------------------------------------------------------------------------------------------
 */

/**
 * Filtrer string with printeable chars and CR.
 *
 * @param string $data
 * @return string
 */
function filterPrinteableString($data): string
{
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $data);
}

/*
 * ------------------------------------------------------------------------------------------
 * Cryptography functions.
 * ------------------------------------------------------------------------------------------
 */

/**
 * Translate algo name into OpenSSL algo name.
 *
 * @param string $algo
 * @param bool $loop
 * @return string
 */
function cryptoGetTranslatedHashAlgo(string $algo, bool $loop = true): string
{
    if ($algo == '')
        $algo = getConfiguration('cryptoHashAlgorithm');

    $translatedAlgo = '';
    switch ($algo)
    {
        case 'sha2.128' :
            $translatedAlgo = 'sha128';
            break;
        case 'sha2.256' :
            $translatedAlgo = 'sha256';
            break;
        case 'sha2.384' :
            $translatedAlgo = 'sha384';
            break;
        case 'sha2.512' :
            $translatedAlgo = 'sha512';
            break;
    }

    if ($translatedAlgo == '')
    {
        if($loop) {
            addLog('cryptoHashAlgorithm configuration have an unknown value (' . $algo . ')', 'error', __FUNCTION__, 'b7627066');
            $translatedAlgo = cryptoGetTranslatedHashAlgo(LIST_OPTIONS_DEFAULT_VALUE['cryptoHashAlgorithm'], false);
        }
        else
            $translatedAlgo = 'sha512';
    }

    return $translatedAlgo;
}

/**
 * Calculate hash of data with algo.
 * Use OpenSSL library.
 *
 * @param string $algo
 * @param string $data
 * @return string
 */
function cryptoGetDataHash(string &$data, string $algo = ''): string
{
    return hash(cryptoGetTranslatedHashAlgo($algo), $data);
}

/**
 * Calculate hash of file data with algo.
 * File must be on objects folder.
 * Use OpenSSL library.
 *
 * @param string $algo
 * @param string $file
 * @return string
 */
function cryptoGetFileHash(string $file, string $algo = ''): string
{
    return hash_file(cryptoGetTranslatedHashAlgo($algo), LOCAL_OBJECTS_FOLDER . '/' . $file);
}

/**
 * Generate pseudo random number
 * Use OpenSSL library.
 *
 * @param int $count
 * @return string
 */
function cryptoGetPseudoRandom($count = 32): string
{
    global $nebuleServerEntite;

    $result = '';
    $algo = 'sha256';
    if ($count == 0 || !is_int($count))
        return $result;

    // Génère une graine avec la date pour le compteur interne.
    $intcount = date(DATE_ATOM) . microtime(false) . NEBULE_LIBPP_VERSION . $nebuleServerEntite;

    // Boucle de remplissage.
    while (strlen($result) < $count) {
        $diffsize = $count - strlen($result);

        // Fait évoluer le compteur interne.
        $intcount = hash($algo, $intcount);

        // Fait diverger le compteur interne pour la sortie.
        // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
        $outvalue = hash($algo, $intcount . 'liberté égalité fraternité', true);

        // Tronc au besoin la taille de la sortie.
        if (strlen($outvalue) > $diffsize)
            $outvalue = substr($outvalue, 0, $diffsize);

        // Ajoute la sortie au résultat final.
        $result .= $outvalue;
    }

    return $result;
}

/**
 * Encrypt data with private asymetric key.
 *
 * @param string $data
 * @return string
 */
function cryptoAsymetricEncrypt(string $data): string
{
    global $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite;

    if (!_entityCheck($nebulePublicEntity)
        || $nebulePrivateEntite == ''
        || $nebulePasswordEntite == ''
        || !io_checkNodeHaveContent($nebulePrivateEntite)
        || $data = ''
    )
        return '';

    $privcert = (nebGetContentAsText($nebulePrivateEntite, 10000)); // TODO A modifier pour ne pas appeler une fonction de haut niveau...
    $privcert = '';
    if (!_objGetLocalContent($nebulePrivateEntite, $privcert, 10000))
        return '';
    $private_key = openssl_pkey_get_private($privcert, $nebulePasswordEntite);
    if ($private_key === false)
        return '';
    $binary_signature = '';
    $hashdata = cryptoGetDataHash($data);
    $binhash = pack("H*", $hashdata);
    $ok = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    openssl_free_key($private_key);
    unset($private_key);
    if ($ok === false)
        return '';

    return bin2hex($binary_signature);
}


/**
 * Decrypt and verify asymetric sign.
 *
 * @param string $sign
 * @param string $hash
 * @param string $nid
 * @return boolean
 */
function cryptoAsymetricVerify(string $sign, string $hash, string $nid): bool
{
    // Read signer's public key.
    $cert = io_objectRead($nid, 10000);
    $pubkeyid = openssl_pkey_get_public($cert);
    if ($pubkeyid === false) return false;

    _metrologyAdd('lv');

    // Encoding sign before check.
    $binsign = pack('H*', $sign);

    // Decode sign with public key.
    if (openssl_public_decrypt($binsign, $bindecrypted, $pubkeyid, OPENSSL_PKCS1_PADDING)) {
        $decrypted = (substr(bin2hex($bindecrypted), -64, 64)); // TODO A faire pour le cas général.
        if ($decrypted == $hash)
            return true;
    }

    return false;
}



/*
 *
 *
 *
 *

 ==/ 3 /===================================================================================
 PART3 : Manage PHP session and arguments.

 TODO.
 ------------------------------------------------------------------------------------------
 */

// ------------------------------------------------------------------------------------------
/**
 * Lit si demande de l'utilisateur d'un nettoyage de session général.
 * Dans ce cas, la session PHP est intégralement nettoyée et un nouvel identifiant de session est généré.
 *
 * Si la session est déjà vide, ne prend pas en compte la demande.
 */
function getBootstrapFlushSession()
{
    session_start();

    if (filter_has_var(INPUT_GET, ARG_FLUSH_SESSION)
        || filter_has_var(INPUT_POST, ARG_FLUSH_SESSION)
    ) {
        addLog('ask flush session', 'warn', __FUNCTION__, '4abe475a');

        // Si la session n'est pas vide ou si interruption de l'utilisateur, la vide.
        if (isset($_SESSION['OKsession'])
            || filter_has_var(INPUT_GET, ARG_BOOTSTRAP_BREAK)
            || filter_has_var(INPUT_POST, ARG_BOOTSTRAP_BREAK)
        ) {
            // Mémorise pour la suite que la session est vidée.
            $bootstrapFlush = true;
            addLog('flush session', 'info', __FUNCTION__, '5d008c11');

            // Vide la session.
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(), '', 0, '/');
            session_regenerate_id(true);

            // Reouvre une nouvelle session pour la suite.
            session_start();
        } else {
            // Sinon marque la session.
            $_SESSION['OKsession'] = true;
        }
    } else {
        // Sinon marque la session.
        $_SESSION['OKsession'] = true;

    }

    session_write_close();
}

/**
 * Lit si demande de l'utilisateur d'une mise à jour des instances de bibliothèque et d'application.
 *
 * Dans ce cas, la session PHP n'est pas exploitée.
 */
function getBootstrapUpdate():void
{
    global $bootstrapUpdate;

    if (filter_has_var(INPUT_GET, ARG_UPDATE_APPLICATION)
        || filter_has_var(INPUT_POST, ARG_UPDATE_APPLICATION)
    ) {
        addLog('ask update', 'warn', __FUNCTION__, 'ac8a2330');

        session_start();

        // Si la mise à jour est demandée mais pas déjà faite.
        if (!isset($_SESSION['askUpdate'])) {
            $bootstrapUpdate = true;
            addLog('update', 'info', __FUNCTION__, 'f2ef6dc2');
            $_SESSION['askUpdate'] = true;
        } else {
            unset($_SESSION['askUpdate']);
        }

        session_write_close();
    }
}

/**
 * Lit si demande de l'utilisateur d'un changement d'application.
 */
function getBootstrapSwitchApplication(): void
{
    global $bootstrapFlush, $bootstrapSwitchApplication, $nebuleServerEntite;

    if ($bootstrapFlush)
        return;

    $arg = '';
    if (filter_has_var(INPUT_GET, ARG_SWITCH_APPLICATION)) {
        $arg = trim(filter_input(INPUT_GET, ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    } elseif (filter_has_var(INPUT_POST, ARG_SWITCH_APPLICATION)) {
        $arg = trim(filter_input(INPUT_POST, ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    }
    if (is_string($arg)
        && _nodCheckNID($arg, true)
        && ($arg == '0'
            || $arg == '1'
            || io_checkNodeHaveLink($arg)
        )
    ) {
        $activated = false;
        // Recherche si l'application est activée par l'entité instance de serveur.
        // Ou si c'est l'application par défaut.
        // Ou si c'est l'application 0.
        if ($arg == getConfiguration('defaultApplication')) {
            $activated = true;
        }
        if ($arg == '0') {
            $activated = true;
        }
        if ($arg == '1') {
            $activated = true;
        }
        if ($arg == '2') {
            $activated = true;
        }
        if (!$activated) {
            $refActivated = _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_ACTIVE, getConfiguration('cryptoHashAlgorithm'));
            $links = array();
            _lnkFindInclusive($arg, $links, 'f', $arg, $refActivated, $arg);

            if (sizeof($links) != 0) {
                $signer = '';
                $authority = '';
                foreach ($links as $link) {
                    if ($link[2] == $nebuleServerEntite) {
                        // Si le lien est valide, active l'application.
                        $activated = true;
                        break;
                    }
                }
                unset($signer, $authority);
            }
            unset($links, $refActivated);
        }

        if ($activated) {
            $bootstrapSwitchApplication = $arg;
            addLog('ask switch application to ' . $bootstrapSwitchApplication, 'info', __FUNCTION__, 'd1a3f3f9');
        }
    }
}

/**
 * Activate the capability to open PHP code on other file.
 */
function setPermitOpenFileCode()
{
    ini_set('allow_url_fopen', '1');
    ini_set('allow_url_include', '1');
}


/*
 *
 *
 *
 *

 ==/ 4 /===================================================================================
 PART4 : Load object oriented PHP library for nebule (Lib POO).

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * Try to find nebule Lib POO.
 * @param string $bootstrapLibraryID
 * @param string $bootstrapLibraryInstanceSleep
 * @return void
 */
function findLibraryPOO(&$bootstrapLibraryID, &$bootstrapLibraryInstanceSleep): void
{
    global $libppCheckOK;

    if (!$libppCheckOK)
        return;

    // Try to find on session.
    session_start();
    if (isset($_SESSION['bootstrapLibrariesID'])
        && _nodCheckNID($_SESSION['bootstrapLibrariesID'])
        && io_checkNodeHaveLink($_SESSION['bootstrapLibrariesID'])
        && io_checkNodeHaveContent($_SESSION['bootstrapLibrariesID'])
        && _objCheckContent($_SESSION['bootstrapLibrariesID'])
        && isset($_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']])
        && $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']] != ''
    ) {
        $bootstrapLibraryID = $_SESSION['bootstrapLibrariesID'];
        $bootstrapLibraryInstanceSleep = $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']];
    }
    session_abort();

    // Try to find with links.
    if ($bootstrapLibraryID == '') {
        $bootstrapLibraryID = nebFindByRef(
            _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE, getConfiguration('cryptoHashAlgorithm')),
            _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE, getConfiguration('cryptoHashAlgorithm')),
            false);

        addLog('find nebule library ' . $bootstrapLibraryID, 'info', __FUNCTION__, '90ee41fc');

        if (!_nodCheckNID($bootstrapLibraryID)
            || !io_checkNodeHaveLink($bootstrapLibraryID)
            || !io_checkNodeHaveContent($bootstrapLibraryID)
            || !_objCheckContent($bootstrapLibraryID)
        ) {
            $bootstrapLibraryID = '';
            setBootstrapBreak('31', 'Finding nebule library error.');
        }
    }
}

/**
 * Load and initialize nebule Lib POO.
 * @param string $bootstrapLibraryID
 * @param string $bootstrapLibraryInstanceSleep
 * @return void
 */
function loadLibraryPOO(string $bootstrapLibraryID, string $bootstrapLibraryInstanceSleep): void
{
    global $loggerSessionID,
           $nebuleInstance;

    if ($bootstrapLibraryID != '') {
        // Load lib from object. @todo faire via les i/o.
        include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapLibraryID);

        if ($bootstrapLibraryInstanceSleep == '')
            $nebuleInstance = new nebule();
        else
            $nebuleInstance = unserialize($bootstrapLibraryInstanceSleep);

        reopenLog(BOOTSTRAP_NAME);
    } else
        setBootstrapBreak('41', 'Library nebule error');
}


/*
 *
 *
 *
 *

 ==/ 5 /===================================================================================
 PART5 : Find application code.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function findApplication():void
{
    global $libppCheckOK, $bootstrapSwitchApplication, $bootstrapUpdate, $nebuleLocalAuthorities,
           $bootstrapApplicationInstanceSleep, $bootstrapApplicationDisplayInstanceSleep,
           $bootstrapApplicationActionInstanceSleep, $bootstrapApplicationTraductionInstanceSleep,
           $bootstrapApplicationStartID;

    if (!$libppCheckOK)
        return;

    session_start();

    // Enregistre l'identifiant de session pour le suivi d'un utilisateur.
    $sessionId = session_id();
    addLog('session hash id ' . cryptoGetDataHash($sessionId), 'info', __FUNCTION__, '36ebd66b');

    // Vérifie l'ID de départ de l'application mémorisé.
    if (isset($_SESSION['bootstrapApplicationStartID'])
        && _nodCheckNID($_SESSION['bootstrapApplicationStartID'])
    ) {
        // Mémorise l'ID de départ de l'application en cours.
        $bootstrapApplicationStartID = $_SESSION['bootstrapApplicationStartID'];
    }

    /*
     * Lit la session PHP et place en cache les instances de la bibliothèque et de l'application.
     *
     * Si un changement d'application est demandé, tente de trouver une instance de l'application
     *   et l'instance de bibliothèque associée.
     *
     * A l'exception de l'ID '0', les variables d'ID font référence à des objets à charger.
     * Les variables d'instances font référence à des classes à ré-instancier par dé-sérialisation.
     *
     * Une dé-sérialisation peut échouer si la classe contenu dans l'objet a été modifiée dans sa structure.
     */
    // Si pas de demande de changement d'application.
    if ($bootstrapSwitchApplication == ''
        || $bootstrapSwitchApplication == $bootstrapApplicationStartID
    ) {
        // Si demande de mise à jour de l'application en cours d'usage.
        if ($bootstrapUpdate) {
            // Recherche la dernière application depuis l'objet de référence sur lui-même.
            $bootstrapApplicationID = nebFindByRef(
                $bootstrapApplicationStartID,
                _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS, getConfiguration('cryptoHashAlgorithm')),
                false);
        } else {
            // Vérifie l'ID de l'application mémorisé.
            if (isset($_SESSION['bootstrapApplicationID'])
                && $_SESSION['bootstrapApplicationID'] != ''
                && _nodCheckNID($_SESSION['bootstrapApplicationID'])
                && $_SESSION['bootstrapApplicationID'] == '0'
                || (io_checkNodeHaveLink($_SESSION['bootstrapApplicationID'])
                    && io_checkNodeHaveContent($_SESSION['bootstrapApplicationID'])
                    && _objCheckContent($_SESSION['bootstrapApplicationID'])
                )
            ) {
                // Mémorise l'ID de l'application en cours.
                $bootstrapApplicationID = $_SESSION['bootstrapApplicationID'];

                // Vérifie l'application non dé-sérialisée.
                if (isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID] != ''
                ) {
                    // Mémorise l'instance non dé-sérialisée de l'application en cours et de ses composants.
                    $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationDisplayInstanceSleep = $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationActionInstanceSleep = $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationTraductionInstanceSleep = $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID];
                } else {
                    // Sinon supprime l'ID de l'application en cours.
                    $bootstrapApplicationID = '';
                }
            }
        }
    } else {
        // Sinon essaie de trouver l'ID de l'application demandée.
        if ($bootstrapSwitchApplication == '0') {
            addLog('ask switch application 0', 'warn', __FUNCTION__, '35b3a0dc');

            // Application 0 de sélection des applications.
            $bootstrapApplicationStartID = '0';
            $bootstrapApplicationID = '0';
        } elseif ($bootstrapSwitchApplication == '1') {
            addLog('ask switch application 1', 'warn', __FUNCTION__, '18b6ab88');

            // Application 0 de sélection des applications.
            $bootstrapApplicationStartID = '1';
            $bootstrapApplicationID = '1';
        } elseif (_nodCheckNID($bootstrapSwitchApplication)
            && io_checkNodeHaveLink($bootstrapSwitchApplication)
        ) {
            $refAppsID = _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS, getConfiguration('cryptoHashAlgorithm'));
            $links = array();
            _lnkFindInclusive($refAppsID, $links, 'f', $refAppsID, $bootstrapSwitchApplication, $refAppsID);

            // Vérifie que l'application est autorisée.
            if (sizeof($links) != 0) {
                // Fait le changement d'application.
                $bootstrapApplicationStartID = $bootstrapSwitchApplication;

                // Vérifie l'application non dé-sérialisée.
                if (isset($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && _nodCheckNID($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && io_checkNodeHaveLink($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && io_checkNodeHaveContent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && _objCheckContent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID] != ''
                    && isset($_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID])
                    && $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID] != ''
                ) {
                    // Mémorise l'instance non dé-sérialisée de l'application en cours et de ses composants.
                    $bootstrapApplicationID = $_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID];
                    $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationDisplayInstanceSleep = $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationActionInstanceSleep = $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID];
                    $bootstrapApplicationTraductionInstanceSleep = $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID];
                } else {
                    // Sinon recherche la dernière application depuis l'objet de référence sur lui-même.
                    $bootstrapApplicationID = nebFindByRef(
                        $bootstrapApplicationStartID,
                        _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS, getConfiguration('cryptoHashAlgorithm')),
                        false);
                }

                addLog('find switched application ' . $bootstrapApplicationID, 'info', __FUNCTION__, '0cbacda8');
            }
            unset($refAppsID, $links);
            // Sinon l'application par défaut sera chargée, plus loin.
        }
    }

    // Fermeture de la session sans écriture pour gain de temps.
    session_abort();

    // Désactivation des envois liés aux session après le premier usage. Evite tout un tas de logs inutiles.
    session_cache_limiter('');
    ini_set('session.use_cookies', '0');
    ini_set('session.use_only_cookies', '0');
    ini_set('session.use_trans_sid', '0');

    // Si pas d'application trouvée, recherche l'application par défaut
    //   ou charge l'application '0' de sélection d'application.
    if ($bootstrapApplicationID == '') {
        $forceValue = getConfiguration('defaultApplication');
        if ($forceValue != null) {
            // Sinon fait le changement vers l'application par défaut.
            $bootstrapApplicationStartID = $forceValue;

            // Recherche la dernière application depuis l'objet de référence sur lui-même.
            $bootstrapApplicationID = nebFindByRef(
                $bootstrapApplicationStartID,
                _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS, getConfiguration('cryptoHashAlgorithm')),
                false);
        } else {
            $bootstrapApplicationStartID = '0';
            $bootstrapApplicationID = '0';
        }
        unset($forceValue);

        addLog('find default application ' . $bootstrapApplicationID, 'info', __FUNCTION__, '423ae49b');
    }

    // Recherche si l'application ne doit pas être pré-chargée.
    if ($bootstrapApplicationStartID != '0'
        && $bootstrapApplicationStartID != '1'
        && $bootstrapApplicationInstanceSleep == ''
    ) {
        // Lit les liens de non pré-chargement pour l'application.
        $refNoPreload = _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_DIRECT, getConfiguration('cryptoHashAlgorithm'));
        $links = array();
        _lnkFindInclusive($bootstrapApplicationStartID, $links, 'f', $bootstrapApplicationStartID, $refNoPreload, $bootstrapApplicationStartID);

        // Filtre sur les autorités locales.
        $bootstrapApplicationNoPreload = false;
        if (sizeof($links) != 0) {
            $signer = '';
            $authority = '';
            foreach ($links as $link) {
                $signer = $link[2];
                foreach ($nebuleLocalAuthorities as $authority) {
                    if ($signer == $authority) {
                        // Si le lien est valide, active le chargement direct de l'application.
                        $bootstrapApplicationNoPreload = true;
                        addLog('do not preload application', 'info', __FUNCTION__, '0ac7d800');
                        break 2;
                    }
                }
            }
            unset($signer, $authority);
        }
        unset($links, $refNoPreload);
    }
}


/*
 *
 *
 *
 *

 ==/ 6 /===================================================================================
 PART6 : Manage and display breaking bootstrap on problem or user ask.

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * Add a break on the bootstrap.
 * In the end, this stop the loading of any application code and show the bootstrap break page.
 *
 * @param string $errorCode
 * @param string $errorDesc
 */
function setBootstrapBreak(string $errorCode, string $errorDesc): void
{
    global $bootstrapBreak;

    $bootstrapBreak[$errorCode] = $errorDesc;
    addLog('bootstrap break code=' . $errorCode . ' msg=' . $errorDesc, 'info', __FUNCTION__, '1a59f99c');
}

// ------------------------------------------------------------------------------------------

function getBootstrapUserBreak(): void
{
    if (filter_has_var(INPUT_GET, ARG_BOOTSTRAP_BREAK)
        || filter_has_var(INPUT_POST, ARG_BOOTSTRAP_BREAK)
    )
        setBootstrapBreak('11', 'User interrupt.');
}


// ------------------------------------------------------------------------------------------
function getBootstrapInlineDisplay(): void
{
    global $bootstrapInlineDisplay;

    if (filter_has_var(INPUT_GET, ARG_INLINE_DISPLAY)
        || filter_has_var(INPUT_POST, ARG_INLINE_DISPLAY)
    )
        $bootstrapInlineDisplay = true;
}



// ------------------------------------------------------------------------------------------
function getBootstrapCheckFingerprint(): void
{
    global $nebuleLocalAuthorities;
    $data = file_get_contents(BOOTSTRAP_FILE_NAME);
    $hash = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
    unset($data);
    // Recherche les liens de validation.
    $hashRef = _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP, getConfiguration('cryptoHashAlgorithm'));
    $links = array();
    _lnkFindInclusive($hashRef, $links, 'f', $hashRef, $hash, $hashRef, false);
    // Trie sur les autorités locales, celles reconnues par la bibliothèque PP.
    $ok = false;
    foreach ($links as $link) {
        foreach ($nebuleLocalAuthorities as $autority) {
            if ($link[2] == $autority) {
                $ok = true;
                break 2;
            }
        }
    }
    if (!$ok) {
        addLog('unknown bootstrap hash - critical', 'error', __FUNCTION__, 'e294b7b3');

        // Arrêt du bootstrap.
        setBootstrapBreak('51', 'Unknown bootstrap hash');
    }
}
// Vérifie l'empreinte du bootstrap. @todo ajouter vérification de marquage de danger.



// ------------------------------------------------------------------------------------------
function getBootstrapDisplayServerEntity()
{
    global $bootstrapServerEntityDisplay;

    if (filter_has_var(INPUT_GET, LOCAL_ENTITY_FILE)
        || filter_has_var(INPUT_POST, LOCAL_ENTITY_FILE)
    ) {
        setBootstrapBreak('52', 'Ask server instance');
        $bootstrapServerEntityDisplay = true;
    }
}



// ------------------------------------------------------------------------------------------
/**
 * Affichage du début de la page HTML.
 *
 * @return void
 */
function bootstrapHtmlHeader()
{
global $bootstrapRescueMode;

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?php echo BOOTSTRAP_NAME;
        if ($bootstrapRescueMode) echo ' - RESCUE' ?></title>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <meta name="author"
          content="<?php echo BOOTSTRAP_AUTHOR . ' - ' . BOOTSTRAP_WEBSITE . ' - ' . BOOTSTRAP_VERSION; ?>"/>
    <meta name="licence" content="<?php echo BOOTSTRAP_LICENCE . ' ' . BOOTSTRAP_AUTHOR; ?>"/>
    <style type="text/css">
        /* CSS reset. http://meyerweb.com/eric/tools/css/reset/ v2.0 20110126. Public domain */
        * {
            margin: 0;
            padding: 0;
        }

        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary, time, mark, audio, video {
            border: 0;
            font: inherit;
            font-size: 100%;
            vertical-align: baseline;
        }

        article, aside, details, figure, figcaption, footer, header, hgroup, menu, nav, section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol, ul {
            list-style: none;
        }

        blockquote, q {
            quotes: none;
        }

        blockquote:before, blockquote:after, q:before, q:after {
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* Balises communes. */
        html {
            height: 100%;
            width: 100%;
        }

        body {
            color: #ababab;
            font-family: monospace;
            background: #454545;
            height: 100%;
            width: 100%;
            min-height: 480px;
            min-width: 640px;
        }

        img, embed, canvas, video, audio, picture {
            max-width: 100%;
            height: auto;
        }

        img {
            border: 0;
            vertical-align: middle;
        }

        a:link, a:visited {
            font-weight: bold;
            text-decoration: none;
            color: #ababab;
        }

        a:hover, a:active {
            font-weight: bold;
            text-decoration: underline;
            color: #ffffff;
        }

        input {
            background: #ffffff;
            color: #000000;
            margin: 0;
            margin-top: 5px;
            border: 0;
            box-shadow: none;
            padding: 5px;
            background-origin: border-box;
        }

        input[type=submit] {
            font-weight: bold;
        }

        input[type=password], input[type=text], input[type=email] {
            padding: 6px;
        }

        /* Le bloc d'entête */
        .layout-header {
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
        }

        .layout-header {
            height: 68px;
            background: #ababab;
            border-bottom-style: solid;
            border-bottom-color: #c8c8c8;
            border-bottom-width: 1px;
        }

        .header-left {
            height: 64px;
            width: 64px;
            margin: 2px;
            float: left;
        }

        .header-left img {
            height: 64px;
            width: 64px;
        }

        .header-right {
            height: 64px;
            width: 64px;
            margin: 2px;
            float: right;
        }

        .header-center {
            height: 100%;
            display: inline-flex;
        }

        .header-center p {
            margin: auto 3px 3px 3px;
            overflow: hidden;
            white-space: nowrap;
            color: #454545;
            text-align: center;
        }

        /* Le bloc de bas de page */
        .layout-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }

        .layout-footer {
            height: 68px;
            background: #ababab;
            border-top-style: solid;
            border-top-color: #c8c8c8;
            border-top-width: 1px;
        }

        .footer-center p {
            margin: 3px;
            overflow: hidden;
            white-space: nowrap;
            color: #454545;
            text-align: center;
        }

        .footer-center a:link, .footer-center a:visited {
            font-weight: normal;
            text-decoration: none;
            color: #454545;
        }

        .footer-center a:hover, .footer-center a:active {
            font-weight: normal;
            text-decoration: underline;
            color: #ffffff;
        }

        /* Le corps de la page qui contient le contenu. Permet le centrage vertical universel */
        .layout-main {
            width: 100%;
            height: 100%;
            display: flex;
        }

        /* Le centre de la page avec le contenu utile. Centrage vertical */
        .layout-content {
            margin: auto;
            padding: 74px 0 74px 0;
        }

        /* Spécifique bootstrap et app 0. */
        .parts {
            margin-bottom: 12px;
        }

        .partstitle {
            font-weight: bold;
        }

        .preload {
            clear: both;
            margin-bottom: 12px;
            min-height: 64px;
            width: 600px;
        }

        .preload img {
            height: 64px;
            width: 64px;
            float: left;
            margin-right: 8px;
        }

        .preloadsync img {
            height: 16px;
            width: 16px;
            float: none;
            margin-left: 0;
            margin-right: 1px;
        }

        .preloadstitle {
            font-weight: bold;
            color: #ffffff;
            font-size: 1.4em;
        }

        #appslist {
        }

        #appslist a:link, #appslist a:visited {
            font-weight: normal;
            text-decoration: none;
            color: #ffffff;
        }

        #appslist a:hover, #appslist a:active {
            font-weight: normal;
            text-decoration: none;
            color: #ffff80;
        }

        .apps {
            float: left;
            margin: 4px;
            height: 64px;
            width: 64px;
            padding: 8px;
            color: #ffffff;
            overflow: hidden;
        }

        .appstitle {
            font-size: 2em;
            font-weight: normal;
            text-decoration: none;
            color: #ffffff;
            margin: 0;
        }

        .appsname {
            font-weight: bold;
        }

        .appssigner {
            float: right;
            height: 24px;
            width: 24px;
        }

        .appssigner img {
            height: 24px;
            width: 24px;
        }

        #sync {
            clear: both;
            width: 100%;
            height: 50px;
        }

        #footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            width: 100%;
            padding: 3px;
            background: #ababab;
            border-top-style: solid;
            border-top-color: #c8c8c8;
            border-top-width: 1px;
            margin: 0;
        }

        .error, .important {
            color: #ffffff;
            font-weight: bold;
        }

        .diverror {
            color: #ffffff;
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .diverror pre {
            padding-left: 6px;
            margin: 3px;
            border-left-style: solid;
            border-left-color: #ababab;
            border-left-width: 1px;
        }

        #reload {
            padding-top: 32px;
            clear: both;
        }

        #reload a {
            color: #ffffff;
            font-weight: bold;
        }

        .important {
            background: #ffffff;
            color: #000000;
            font-weight: bold;
            margin: 10px;
            padding: 10px;
        }

        /* Spécifique app 1. */
        #layout_documentation {
            background: #ffffff;
            padding: 20px;
            text-align: left;
            color: #000000;
            font-size: 0.8rem;
            font-family: sans-serif;
            min-width: 400px;
            max-width: 1200px;
        }

        #title_documentation {
            margin-bottom: 30px;
        }

        #title_documentation p {
            text-align: center;
            color: #000000;
            font-size: 0.7em;
        }

        #title_documentation p a:link, #title_documentation p a:visited {
            font-weight: normal;
            text-decoration: none;
            color: #000000;
        }

        #title_documentation p a:hover, #title_documentation p a:active {
            font-weight: normal;
            text-decoration: underline;
            color: #000000;
        }

        #content_documentation {
            text-align: justify;
            color: #000000;
            font-size: 1em;
        }

        #content_documentation h1 {
            text-align: left;
            color: #454545;
            font-size: 2em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 80px;
            margin-bottom: 5px;
        }

        #content_documentation h2 {
            text-align: left;
            color: #454545;
            font-size: 1.8em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        #content_documentation h3 {
            text-align: left;
            color: #454545;
            font-size: 1.6em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 40px;
            margin-bottom: 5px;
        }

        #content_documentation h4 {
            text-align: left;
            color: #454545;
            font-size: 1.4em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        #content_documentation h5 {
            text-align: left;
            color: #454545;
            font-size: 1.2em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        #content_documentation h6 {
            text-align: left;
            color: #454545;
            font-size: 1.1em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        #content_documentation p {
            text-align: justify;
            margin-top: 5px;
        }

        .pcenter {
            text-align: center;
        }

        #content_documentation a:link, #content_documentation a:visited {
            font-weight: normal;
            text-decoration: underline;
            color: #000000;
        }

        #content_documentation a:hover, #content_documentation a:active {
            font-weight: normal;
            text-decoration: underline;
            color: #0000ab;
        }

        code {
            font-family: monospace;
        }

        #content_documentation pre {
            font-family: monospace;
            text-align: left;
            border-left-style: solid;
            border-left-color: #c8c8c8;
            border-left-width: 1px;
        }

        #content_documentation ol li, #content_documentation ul li {
            text-align: left;
            list-style-position: inside;
            margin-left: 10px;
        }

        #content_documentation ol {
            list-style-type: decimal-leading-zero;
        }

        #content_documentation ul {
            list-style-type: disc;
        }
    </style>
    <script language="javascript" type="text/javascript">
        <!--
        function replaceInlineContentFromURL(id, url) {
            document.getElementById(id).innerHTML = '<object class="inline" type="text/html" data="' + url + '" ></object>';
        }

        function followHref(url) {
            window.location.href = url;
        }

        //-->
    </script>
</head>
<?php
}



// ------------------------------------------------------------------------------------------
/**
 * Affichage de l'entête, du logo et du bas de page.
 *
 * @return void
 */
function bootstrapHtmlTop()
{
global $nebuleServerEntite;

$name = nebReadEntityFullName($nebuleServerEntite);
?>
<body>
<div class="layout-header">
    <div class="header-left">
        <a href="/?<?php echo ARG_SWITCH_APPLICATION; ?>=0">
            <img title="App switch" alt="[]" src="<?php echo REFERENCE_BOOTSTRAP_ICON; ?>"/>
        </a>
    </div>
    <div class="header-right">
        &nbsp;
    </div>
    <div class="header-center">
        <p>
            <?php
            if ($name != $nebuleServerEntite) {
                echo $name;
            } else {
                echo '/';
            }
            echo '<br />' . $nebuleServerEntite;
            ?>
        </p>
    </div>
</div>
<div class="layout-footer">
    <div class="footer-center">
        <p>
            <?php echo BOOTSTRAP_NAME; ?><br/>
            <?php echo BOOTSTRAP_VERSION; ?><br/>
            (c) <?php echo BOOTSTRAP_LICENCE . ' ' . BOOTSTRAP_AUTHOR; ?> - <a
                    href="http://<?php echo BOOTSTRAP_WEBSITE; ?>" target="_blank"
                    style="text-decoration:none;"><?php echo BOOTSTRAP_WEBSITE; ?></a>
        </p>
    </div>
</div>

<?php
    echo '<div class="layout-main">'; // TODO à revoir...
    echo '    <div class="layout-content">';
}

// ------------------------------------------------------------------------------------------
/**
 * Affichage de la fermeture de la page HTML.
 *
 * @return void
 */
function bootstrapHtmlBottom()
{
?>

    </div>
</div>
</body>
</html>
<?php
}


// ------------------------------------------------------------------------------------------
/**
 * bootstrapNormalDisplayOnBreak()
 * La fonction bootstrapNormalDisplayOnBreak affiche l'écran du bootstrap en cas d'interruption.
 * L'interruption peut être appelée par l'utilisateur ou provoqué par une erreur lors des
 * vérifications de bon fonctionnement. Les vérifications ont lieu à chaque chargement de
 * page. Au cours de l'affichage de la page du bootstrap, les vérifications de bon
 * fonctionnement sont refait un par un avec affichage en direct du résultat.
 *
 * @return void
 */
function bootstrapDisplayOnBreak(): void
{
    global $nebuleInstance,
           $bootstrapBreak,
           $bootstrapRescueMode,
           $bootstrapFlush,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $bootstrapApplicationStartID,
           $metrologyStartTime,
           $nebuleSecurityMasters,
           $nebuleCodeMasters,
           $nebuleDirectoryMasters,
           $nebuleTimeMasters,
           $nebuleLocalAuthorities,
           $nebuleServerEntite,
           $nebuleDefaultEntite,
           $nebulePublicEntity,
           $nebuleLibVersion,
           $libpooCheckOK;

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();
    ?>

    <div class="parts">
        <span class="partstitle">#1 <?php echo BOOTSTRAP_NAME; ?> break on</span>
        <?php
        foreach ($bootstrapBreak as $number => $message) {
            if (sizeof($bootstrapBreak) > 1) {
                echo "<br />\n- ";
            }
            echo '[' . $number . '] <span class="error">' . $message . '</span>';
        }
        ?><br/>
        Tb=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?><br/>
        <?php if ($bootstrapRescueMode) {
            echo "RESCUE mode<br />\n";
        } ?>
        <?php if ($bootstrapFlush) {
            echo "FLUSH<br />\n";
        } ?>
        <?php if (sizeof($bootstrapBreak) != 0 && isset($bootstrapBreak[12])) {
            echo "<a href=\"?a=0\">&gt; Return to application 0</a><br />\n";
        }
        $sessionId = session_id();
        ?>
        <a href="?f">&gt; Flush PHP session</a> (<?php echo substr(cryptoGetDataHash($sessionId), 0, 6); ?>)<br/>
    </div>
    <div class="parts">
        <span class="partstitle">#2 <?php echo BOOTSTRAP_NAME; ?> nebule library PP</span><br/>
        library version &nbsp;: <?php echo NEBULE_LIBPP_VERSION ?><br/>
        puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo getConfiguration('puppetmaster'); ?> (local authority)<br/>
        security master &nbsp;: <?php foreach ($nebuleSecurityMasters as $m) echo $m . ' '; ?> (local authority)<br/>
        code master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php foreach ($nebuleCodeMasters as $m) echo $m . ' '; ?> (local authority)<br/>
        directory master : <?php foreach ($nebuleDirectoryMasters as $m) echo $m . ' '; ?><br/>
        time master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php foreach ($nebuleTimeMasters as $m) echo $m . ' '; ?><br/>
        server entity &nbsp;&nbsp;&nbsp;: <?php echo $nebuleServerEntite; ?><br/>
        default entity &nbsp;&nbsp;: <?php echo $nebuleDefaultEntite; ?><br/>
        current entity &nbsp;&nbsp;: <?php echo $nebulePublicEntity; ?>
    </div>
    <div class="parts">
        <span class="partstitle">#3 nebule library POO</span><br/>
        <?php
        flush();

        // Chargement de la bibliothèque PHP POO.
        echo "Tl=" . sprintf('%01.4fs', microtime(true) - $metrologyStartTime) . "<br />\n";
        echo 'library RID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE, getConfiguration('cryptoHashAlgorithm')) . "<br />\n";

        if (!is_a($nebuleInstance, 'nebule')) {
            echo "Not loaded.\n";
        } else {
            // Version.
            echo 'library ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $bootstrapLibraryID . "<br />\n";
            echo 'library version &nbsp;: ' . $nebuleLibVersion . "<br />\n";

            $checkInstance = $nebuleInstance->checkInstance();

            // Test le puppetmaster.
            echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: ';
            if ($checkInstance == 0) {
                echo "<span id=\"error\">ERROR!</span><br />\n";
            } else {
                echo "OK (local authority)<br />\n";

                // Test le security master.
                echo 'security master &nbsp;: ';
                if ($checkInstance == 1) {
                    echo "<span id=\"error\">ERROR!</span><br />\n";
                } else {
                    echo '<a href="o/' . $nebuleInstance->getSecurityMaster() . '">'
                        . $nebuleInstance->getSecurityMasterInstance()->getName() . "</a> OK (local authority)<br />\n";

                    // Test le code master.
                    echo 'code master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
                    if ($checkInstance == 2) {
                        echo "<span id=\"error\">ERROR!</span><br />\n";
                    } else {
                        echo '<a href="o/' . $nebuleInstance->getCodeMaster() . '">'
                            . $nebuleInstance->getCodeMasterInstance()->getName() . "</a> OK (local authority)<br />\n";

                        // Test le directory master.
                        echo 'directory master : ';
                        if ($checkInstance == 3) {
                            echo "<span id=\"error\">ERROR!</span><br />\n";
                        } else {
                            echo '<a href="o/' . $nebuleInstance->getDirectoryMaster() . '">'
                                . $nebuleInstance->getDirectoryMasterInstance()->getName() . "</a> OK<br />\n";
                        }

                        // Test le time master.
                        echo 'time master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
                        if ($checkInstance == 4) {
                            echo "<span id=\"error\">ERROR!</span><br />\n";
                        } else {
                            echo '<a href="o/' . $nebuleInstance->getTimeMaster() . '">'
                                . $nebuleInstance->getTimeMasterInstance()->getName() . "</a> OK<br />\n";
                        }

                        // Test l'entité de l'instance du serveur.
                        echo 'server entity &nbsp;&nbsp;&nbsp;: ';
                        if ($checkInstance <= 32) {
                            echo "<span id=\"error\">ERROR!</span><br />\n";
                        } else {
                            echo '<a href="o/' . $nebuleInstance->getInstanceEntity() . '">'
                                . $nebuleInstance->getInstanceEntityInstance()->getName() . '</a> OK';
                            if ($nebuleInstance->getIsLocalAuthority($nebuleInstance->getInstanceEntity())) {
                                echo ' (local authority)';
                            }
                            echo "<br />\n";
                        }
                    }
                }
            }
            // Affichage de l'entité par défaut.
            echo 'default entity &nbsp;&nbsp;: <a href="o/' . $nebuleInstance->getDefaultEntity() . '">'
                . $nebuleInstance->getDefaultEntityInstance()->getName() . '</a>';
            if ($nebuleInstance->getIsLocalAuthority($nebuleInstance->getDefaultEntity())) {
                echo ' (local authority)';
            }
            echo "<br />\n";

            // Affichage de l'entité courante.
            echo 'current entity &nbsp;&nbsp;: <a href="o/' . $nebuleInstance->getCurrentEntity() . '">'
                . $nebuleInstance->getCurrentEntityInstance()->getName() . '</a>';
            if ($nebuleInstance->getIsLocalAuthority($nebuleInstance->getCurrentEntity())) {
                echo ' (local authority)';
            }
            echo "<br />\n";

            // Affichage de la subordination de l'instance.
            echo 'subordination &nbsp;&nbsp;&nbsp;: ';
            $entity = getConfiguration('subordinationEntity');
            if ($entity != '') {
                $instance = new Entity($nebuleInstance, $entity);
                echo '<a href="o/' . $entity . '">' . $instance->getName() . '</a>';
                unset($instance);
            } else {
                echo '/';
            }
            if ($nebuleInstance->getIsLocalAuthority($entity)) {
                echo ' (local authority)';
            }
            unset($entity);
            echo "<br />\n";

            // Vérifie la cryptographie.
            echo 'cryptography &nbsp;&nbsp;&nbsp;&nbsp;: ';
            if (!is_object($nebuleInstance->getCrypto())) {
                echo '<span class="error">ERROR!</span>';
            } else {
                echo get_class($nebuleInstance->getCrypto());
                echo "<br />\n";

                // Vérifie la fonction de hash.
                echo 'cryptography &nbsp;&nbsp;&nbsp;&nbsp;: hash ' . $nebuleInstance->getCrypto()->hashAlgorithm() . ' ';
                if ($nebuleInstance->getCrypto()->checkHashFunction()) {
                    echo 'OK';
                } else {
                    echo '<span class="error">ERROR!</span>';
                }
                echo "<br />\n";

                // Vérifie la fonction de cryptographie symétrique.
                echo 'cryptography &nbsp;&nbsp;&nbsp;&nbsp;: symetric ' . $nebuleInstance->getCrypto()->symetricAlgorithm() . ' ';
                if ($nebuleInstance->getCrypto()->checkSymetricFunction()) {
                    echo 'OK';
                } else {
                    echo '<span class="error">ERROR!</span>';
                }
                echo "<br />\n";

                // Vérifie la fonction de cryptographie asymétrique.
                echo 'cryptography &nbsp;&nbsp;&nbsp;&nbsp;: asymetric ' . $nebuleInstance->getCrypto()->asymetricAlgorithm() . ' ';
                if ($nebuleInstance->getCrypto()->checkAsymetricFunction()) {
                    echo 'OK';
                } else {
                    echo '<span class="error">ERROR!</span>';
                }
                echo "<br />\n";

                // Vérifie la fonction de génération pseudo-aléatoire.
                $random = $nebuleInstance->getCrypto()->getPseudoRandom(2048);
                $entropy = $nebuleInstance->getCrypto()->getEntropy($random);
                echo 'cryptography &nbsp;&nbsp;&nbsp;&nbsp;: pseudo-random ' . substr(bin2hex($random), 0, 32) . '(' . $entropy . ') ';
                if ($entropy > 7.85) {
                    echo 'OK';
                } else {
                    echo '<span class="error">ERROR!</span>';
                }
            }
            echo "<br />\n";

            // Vérifie des entrées/sorties (I/O).
            if (!is_object($nebuleInstance->getIO())) {
                echo 'i/o <span class="error">ERROR!</span>' . "<br />\n";
            } else {
                $list = $nebuleInstance->getIO()->getModulesList();
                foreach ($list as $class) {
                    $module = $nebuleInstance->getIO()->getModule($class);
                    echo 'i/o &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $class . ' (' . $module->getMode() . ') ' . $module->getDefaultLocalisation() . ', links ';
                    if (!$module->checkLinksDirectory()) {
                        echo 'directory <span class="error">ERROR!</span>';
                    } else {
                        if (!$module->checkLinksRead()) {
                            echo 'read <span class="error">ERROR!</span>';
                        } else {
                            if (!$module->checkLinksWrite()
                                && $module->getMode() == 'RW'
                            ) {
                                echo 'OK no write.';
                            } else {
                                echo 'OK';
                            }
                        }
                    }
                    echo ', objects ';
                    if (!$module->checkObjectsDirectory()) {
                        echo 'directory <span class="error">ERROR!</span>';
                    } else {
                        if (!$module->checkObjectsRead()) {
                            echo 'read <span class="error">ERROR!</span>';
                        } else {
                            if (!$module->checkObjectsWrite()
                                && $module->getMode() == 'RW'
                            ) {
                                echo 'OK no write.';
                            } else {
                                echo 'OK';
                            }
                        }
                    }
                    echo "<br />\n";
                }
            }

            // Vérifie de la gestion des relations sociales.
            if (!is_object($nebuleInstance->getSocial())) {
                echo '<span class="error">ERROR!</span>' . "<br />\n";
            } else {
                foreach ($nebuleInstance->getSocial()->getList() as $moduleName) {
                    echo 'social &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $moduleName . " OK<br />\n";
                }
            }

            // Vérifie le bootstrap. @todo ajouter vérification de marquage de danger.
            $data = file_get_contents(BOOTSTRAP_FILE_NAME);
            $hash = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));
            unset($data);
            echo 'bootstrap &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $hash . ' ';
            // Recherche les liens de validation.
            $hashRef = _objGetNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP, getConfiguration('cryptoHashAlgorithm'));
            $links = array();
            _lnkFindInclusive($hashRef, $links, 'f', $hashRef, $hash, $hashRef, false);
            // Trie sur les autorités locales, celles reconnues par la bibliothèque PP.
            $ok = false;
            foreach ($links as $link) {
                foreach ($nebuleLocalAuthorities as $autority) {
                    if ($link[2] == $autority) {
                        $ok = true;
                        break 2;
                    }
                }
            }
            if ($ok) {
                echo 'OK';
            } else {
                echo '<span class="error">ERROR!</span>';
            }
            echo "<br />\n";

            // Affichage des valeurs de métrologie.
            echo "<br />\n";
            echo 'L(r)=' . _metrologyGet('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead() . ' ';
            echo 'L(v)=' . _metrologyGet('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify() . ' ';
            echo 'O(r)=' . _metrologyGet('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead() . ' ';
            echo 'O(v)=' . _metrologyGet('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify() . " (PP+POO)<br />\n";
            echo 'L(c)=' . $nebuleInstance->getCacheLinkSize() . ' ';
            echo 'O(c)=' . $nebuleInstance->getCacheObjectSize() . ' ';
            echo 'E(c)=' . $nebuleInstance->getCacheEntitySize() . ' ';
            echo 'G(c)=' . $nebuleInstance->getCacheGroupSize() . ' ';
            echo 'C(c)=' . $nebuleInstance->getCacheConversationSize() . ' ';
            echo 'CU(c)=' . $nebuleInstance->getCacheCurrencySize() . ' ';
            echo 'CP(c)=' . $nebuleInstance->getCacheTokenPoolSize() . ' ';
            echo 'CT(c)=' . $nebuleInstance->getCacheTokenSize() . ' ';
            echo 'CW(c)=' . $nebuleInstance->getCacheWalletSize() . ' ';
            echo 'CS(c)=' . $nebuleInstance->getCacheTransactionSize();
        }
        ?>

    </div>

    <div class="parts">
        <span class="partstitle">#4 application</span><br/>
        application RID &nbsp;: <a
                href="/?<?php echo ARG_SWITCH_APPLICATION . '=' . $bootstrapApplicationStartID; ?>"><?php echo $bootstrapApplicationStartID; ?></a><br/>
        application ID &nbsp;&nbsp;: <?php echo $bootstrapApplicationID; ?>
    </div>

    <span class="partstitle">#- end <?php echo BOOTSTRAP_NAME; ?></span><br/>
    Te=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?><br/>
    <?php

    bootstrapHtmlBottom();
}


// ------------------------------------------------------------------------------------------
/**
 * bootstrapInlineDisplayOnBreak()
 * La fonction bootstrapInlineDisplayOnBreak affiche l'écran du bootstrap en cas d'interruption.
 * L'interruption peut être appelée par l'utilisateur ou provoqué par une erreur lors des
 * vérifications de bon fonctionnement. Les vérifications ont lieu à chaque chargement de
 * page. L'affichage est minimum, il est destiné à apparaître dans une page web déjà ouverte.
 *
 * @return void
 */
function bootstrapInlineDisplayOnBreak()
{
    global $bootstrapBreak,
           $bootstrapRescueMode,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $metrologyStartTime;

    ob_end_flush();

    // Affichage.
    echo "<div class=\"bootstrapErrorDiv\"><p>\n";

    echo '&gt; ' . BOOTSTRAP_NAME . ' ' . BOOTSTRAP_VERSION . "<br />\n";

    echo 'Bootstrap break on : ';
    foreach ($bootstrapBreak as $number => $message) {
        if (sizeof($bootstrapBreak) > 1) {
            echo "<br />\n- ";
        }
        echo '[' . $number . '] ' . $message;
    }
    echo "<br />\n";

    if ($bootstrapRescueMode) {
        echo "RESCUE<br />\n";
    }

    echo 'nebule loading library : ' . $bootstrapLibraryID . "<br />\n";

    echo 'Application loading : ' . $bootstrapApplicationID . "<br />\n";

    echo 'Tb=' . sprintf('%01.4fs', microtime(true) - $metrologyStartTime) . "<br />\n";

    echo "</p></div>\n";
}


/*
 *
 *
 *
 *

 ==/ 7 /===================================================================================
 PART7 : Display of pre-load application web page.

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * bootstrapDisplayPreloadApplication()
 * La fonction affiche temporairement l'écran du bootstrap
 *   le temps de charger les instances de la bibliothèque, de l'application et de ses classes annexes.
 *
 * Un certain nombre de variables globalles sont initialisées au chargement des applications,
 *   elles doivent être présentes ici.
 *
 * @return void
 */
function bootstrapDisplayPreloadApplication()
{
    global $nebuleInstance,
           $loggerSessionID,
           $metrologyStartTime,
           $applicationInstance,
           $applicationDisplayInstance,
           $applicationActionInstance,
           $applicationTraductionInstance,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $bootstrapApplicationStartID;

    // Initialisation des logs
    reopenLog('preload');
    addLog('Loading', 'info', __FUNCTION__, 'ce5879b0');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();
    ?>

    <div class="preload">
        Please wait...<br/>
        Tb=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?>
    </div>

    <div class="preload">
        <img title="bootstrap" style="background:#ababab;" alt="[]" src="<?php echo REFERENCE_BOOTSTRAP_ICON; ?>"/>
        Load nebule library POO<br/>
        ID=<?php echo $bootstrapLibraryID; ?><br/>
        <?php
        flush();

        // Ré-initialisation des logs
        reopenLog('preload');
        ?>

        Tl=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?>
    </div>

    <div class="preload">
        <img title="bootstrap" style="background:#<?php echo substr($bootstrapApplicationStartID . '000000', 0, 6); ?>;"
             alt="[]" src="<?php echo REFERENCE_BOOTSTRAP_ICON; ?>"/>
        Load application<br/>
        ID=<?php echo $bootstrapApplicationID; ?><br/>
        <?php
        flush();

        syslog(LOG_INFO, 'LogT=0 LogTabs=' . (microtime(true)) . ' load_application=' . $bootstrapApplicationID);

        // Charge l'objet de l'application. @todo faire via les i/o.
        include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);

        // Instanciation des classes de l'application.
        $applicationInstance = new Application($nebuleInstance);
        $applicationTraductionInstance = new Traduction($applicationInstance);
        $applicationDisplayInstance = new Display($applicationInstance);
        $applicationActionInstance = new Action($applicationInstance);

        // Initialisation des instances.
        $applicationInstance->initialisation();
        $applicationTraductionInstance->initialisation();
        $applicationDisplayInstance->initialisation();
        $applicationActionInstance->initialisation();
        ?>

        Name=<?php echo $applicationInstance->getClassName(); ?><br/>
        sync<span class="preloadsync">
<?php

// Récupération des éléments annexes nécessaires à l'affichage de l'application.
$items = $applicationDisplayInstance->getNeededObjectsList();
$nb = 0;
foreach ($items as $item) {
    if (!$nebuleInstance->getIO()->checkObjectPresent($item)) {
        $instance = $nebuleInstance->newObject($item, false, false);
        $applicationDisplayInstance->displayInlineObjectColorNolink($instance);
        echo "\n";
        $instance->syncObject(false);
        $nb++;
    }
}
unset($items);

if ($nb == 0) {
    echo '-';
}
?>

	</span><br/>
        Ta=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?>
    </div>
    <div id="reload">
        &gt; <a onclick="javascript:window.location.assign('<?php echo $_SERVER['REQUEST_URI']; ?>');">Reloading
            application</a> ...
        <script language="javascript" type="text/javascript">
            <!--
            setTimeout(function () {
                window.location.assign('<?php echo $_SERVER['REQUEST_URI']; ?>')
            }, 500);
            //-->
        </script>
    </div>
    <?php

    bootstrapHtmlBottom();
}


/*
 *
 *
 *
 *

 ==/ 8 /===================================================================================
 PART8 : First synchronization of code and environment.

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * Check if we need a first synchronization of code and environment.
 *
 * @return boolean
 */
function getBootstrapNeedFirstSynchronization(): bool
{
    if (file_exists(LOCAL_ENTITY_FILE)
        && is_file(LOCAL_ENTITY_FILE)
    ) {
        $serverEntite = filter_var(strtok(trim(file_get_contents(LOCAL_ENTITY_FILE)), "\n"), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (!_entityCheck($serverEntite)) {
            setBootstrapBreak('62', 'Local server entity error');
            return true;
        }
    } else {
        setBootstrapBreak('61', 'No local server entity');
        return true;
    }
    return false;
}



// ------------------------------------------------------------------------------------------
/**
 * Affichage de l'initialisation de l'entité locale instance du serveur.
 *
 * @return void
 */
function bootstrapDisplayApplicationfirst(): void
{
    global $loggerSessionID, $bootstrapBreak, $metrologyStartTime, $bootstrapRescueMode,
           $configurationList, $nebuleCacheIsPubkey;

    // Modifie temporairement la configuration de la bibliothèque PHP PP.
    $configurationList['nebulePermitWrite'] = true;
    $configurationList['nebulePermitWriteObject'] = true;
    $configurationList['nebulePermitSynchronizeObject'] = true;
    $configurationList['nebulePermitWriteLink'] = true;
    $configurationList['nebulePermitSynchronizeLink'] = true;
    $configurationList['nebulePermitWriteEntity'] = true;
    $configurationList['permitBufferIO'] = false;
    $nebuleCacheIsPubkey = array();

    // Initialisation des logs
    reopenLog('first');
    addLog('Loading', 'info', __FUNCTION__, '529d21e0');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    echo '<div class="parts">'."\n";
    ?>

        <span class="partstitle">#1 <?php echo BOOTSTRAP_NAME; ?> break on</span>
        <?php
        foreach ($bootstrapBreak as $number => $message) {
            if (sizeof($bootstrapBreak) > 1) {
                echo "<br />\n- ";
            }
            echo '[' . $number . '] <span class="error">' . $message . '</span>';
        }
        ?><br/>
        Tb=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?><br/>
        nebule library : <?php echo NEBULE_LIBPP_VERSION . ' PHP PP'; ?><br/>
        <?php if ($bootstrapRescueMode) {
            echo "RESCUE<br />\n";
        }
    echo "</div>\n";
    echo '<div class="parts">'."\n";
    ?>
    <span class="partstitle">#2 create folders</span><br/>
    <?php
    if (!io_createLinkFolder() || !io_createObjectFolder()) {
        ?>

        <span class="error">ERROR!</span>
        <?php
        if (!io_checkLinkFolder()) {
            addLog('error links folder', 'error', __FUNCTION__, 'f1d49c43');
            ?>

            <div class="diverror">
                Unable to create folder <b><?php echo LOCAL_LINKS_FOLDER; ?></b> for links.<br/>
                On the same path as <b>index.php</b>, please create folder manually,<br/>
                and give it to web server process.<br/>
                As <i>root</i>, run :<br/>
                <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

mkdir <?php echo LOCAL_LINKS_FOLDER; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LOCAL_LINKS_FOLDER; ?>

chmod 755 <?php echo LOCAL_LINKS_FOLDER; ?>
</pre>
            </div>
            <?php
        }

        if (!io_checkObjectFolder()) {
            addLog('error objects folder', 'error', __FUNCTION__, 'dc0c86a4');
            ?>

            <div class="diverror">
                Unable to create folder <b><?php echo LOCAL_OBJECTS_FOLDER; ?></b> for objects.<br/>
                On the same path as <b>index.php</b>, please create folder manually,<br/>
                and give it to web server process.<br/>
                As <i>root</i>, run :<br/>
                <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

mkdir <?php echo LOCAL_OBJECTS_FOLDER; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LOCAL_OBJECTS_FOLDER; ?>

chmod 755 <?php echo LOCAL_OBJECTS_FOLDER; ?>
</pre>
            </div>
            <?php
        }

        echo "</div>\n";
        ?>

        <div id="reload">
            <button onclick="javascript:window.location.reload(true);">Reload</button>
        </div>
        <?php
    } else {
        addLog('ok folders', 'info', __FUNCTION__, '68c50ba0');
        ?>

        ok
        <?php
        echo "</div>\n";
        bootstrapFirstCreateObjects();
    }

    bootstrapHtmlBottom();
}



// ------------------------------------------------------------------------------------------
/**
 * Création des objets nécessaires au bon fonctionnement de la bibliothèque.
 *
 * @return void
 */
function bootstrapFirstCreateObjects()
{
    echo '<div class="parts">'."\n";

    ?>

        <span class="partstitle">#3 nebule needed library objects</span><br/>
    <?php
    // Si il manque un des objets, recrée les objets.
    $hash = _objGetNID(FIRST_RESERVED_OBJECTS[10], getConfiguration('cryptoHashAlgorithm'));
    if (!io_checkNodeHaveContent($hash))
    {
        addLog('need create objects', 'warn', __FUNCTION__, 'ca195598');

        // Ecrit les objets de localisation.
        foreach (FIRST_LOCALISATIONS as $data) {
            io_objectWrite($data);
            echo '.';
        }

        // Ecrit les objets réservés.
        foreach (FIRST_RESERVED_OBJECTS as $data) {
            io_objectWrite($data);
            echo '.';
        }
        ?>
        OK
        <?php
        echo "</div>\n";
        ?>
    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo BOOTSTRAP_NAME; ?></a> ...
    <script type="text/javascript">
        <!--
        setTimeout(function () {
            window.location.reload(true)
        }, <?php echo FIRST_RELOAD_DELAY; ?>);
        //-->
    </script>
    <?php
    } else {
        addLog('ok create objects', 'info', __FUNCTION__, '5c7be016');
        ?>
        ok
        <?php
        echo "</div>\n";
        // Si c'est bon on continue avec la synchronisation des entités.
        bootstrapFirstSynchronizingEntities();
    }
}


// ------------------------------------------------------------------------------------------
/**
 * Synchronisation du minimum d'entités sur internet pour fonctionner.
 *
 * @return void
 */
function bootstrapFirstSynchronizingEntities()
{
    global $nebuleLocalAuthorities, $libppCheckOK;

    echo '<div class="parts">'."\n";

    $puppetmaster = _entityGetPuppetmaster();
    $securityMasters = _entityGetSecurityMasters(true);
    $codeMasters = _entityGetCodeMasters(true);
    $timeMasters = _entityGetTimeMasters(true);
    $directoryMasters = _entityGetDirectoryMasters(true);
    ?>

    <span class="partstitle">#4 synchronizing entities</span><br/>
    <?php
    // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
    if (!_entityCheckPuppetmaster($puppetmaster)
        || !_entityCheckSecurityMasters($securityMasters)
        || !_entityCheckCodeMasters($codeMasters)
        || !_entityCheckTimeMasters($timeMasters)
        || !_entityCheckDirectoryMasters($directoryMasters)
    ) {
        echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;:';
        if (!_entityCheckPuppetmaster($puppetmaster))
        {
            echo 'sync... ';
            addLog('need sync puppetmaster', 'warn', __FUNCTION__, '6995b7fd');
            _entitySyncPuppetmaster($puppetmaster);
        }
        if (_entityCheckPuppetmaster($puppetmaster))
        {
            echo $puppetmaster . ' ';
            echo 'ok';
        }
        else
            echo " <span class=\"error\">invalid!</span>\n";
        echo "<br/>\n";
        flush();

        // Activation comme autorité locale.
        $nebuleLocalAuthorities[0] = getConfiguration('puppetmaster');

        foreach (FIRST_LOCALISATIONS as $localisation) {
            _lnkDownloadOnLocation(_objGetNID('nebule/objet/entite/maitre/securite', getConfiguration('cryptoHashAlgorithm')), $localisation);
            echo '.';
            _lnkDownloadOnLocation(_objGetNID('nebule/objet/entite/maitre/code', getConfiguration('cryptoHashAlgorithm')), $localisation);
            echo '.';
            _lnkDownloadOnLocation(_objGetNID('nebule/objet/entite/maitre/annuaire', getConfiguration('cryptoHashAlgorithm')), $localisation);
            echo '.';
            _lnkDownloadOnLocation(_objGetNID('nebule/objet/entite/maitre/temps', getConfiguration('cryptoHashAlgorithm')), $localisation);
            echo '.';
        }
        echo "<br/>\n";
        flush();

        echo 'security master &nbsp;:';
        if (sizeof($securityMasters) != 0)
        {
            if (!_entityCheckSecurityMasters($securityMasters))
            {
                echo 'sync... ';
                addLog('need sync masters of security', 'warn', __FUNCTION__, 'a767699e');
                _entitySyncSecurityMasters($securityMasters);
            }
            if (_entityCheckSecurityMasters($securityMasters))
            {
                foreach ($securityMasters as $master)
                    echo $master . ' ';
                echo 'ok';
            }
            else
                echo " <span class=\"error\">invalid!</span>\n";
        } else
            echo " <span class=\"error\">empty!</span>\n";
        echo "<br/>\n";
        flush();

        echo 'code master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:';
        if (sizeof($codeMasters) != 0)
        {
            if (!_entityCheckCodeMasters($codeMasters))
            {
                echo 'sync... ';
                addLog('need sync masters of code', 'warn', __FUNCTION__, '8543b436');
                _entitySyncCodeMasters($codeMasters);
            }
            if (_entityCheckCodeMasters($codeMasters))
            {
                foreach ($codeMasters as $master)
                    echo $master . ' ';
                echo 'ok';
            }
            else
                echo " <span class=\"error\">invalid!</span>\n";
        } else
            echo " <span class=\"error\">empty!</span>\n";
        echo "<br/>\n";
        flush();

        echo 'time master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:';
        if (sizeof($timeMasters) != 0)
        {
            if (!_entityCheckTimeMasters($timeMasters))
            {
                echo 'sync... ';
                addLog('need sync masters of code', 'warn', __FUNCTION__, '0c6f1ef1');
                _entitySyncTimeMasters($timeMasters);
            }
            if (_entityCheckTimeMasters($timeMasters))
            {
                foreach ($timeMasters as $master)
                    echo $master . ' ';
                echo 'ok';
            }
            else
                echo " <span class=\"error\">invalid!</span>\n";
        } else
            echo " <span class=\"error\">empty!</span>\n";
        echo "<br/>\n";
        flush();

        echo 'directory master :';
        if (sizeof($directoryMasters) != 0)
        {
            if (!_entityCheckDirectoryMasters($directoryMasters))
            {
                echo 'sync... ';
                addLog('need sync masters of directory', 'warn', __FUNCTION__, 'e47e9e04');
                _entitySyncDirectoryMasters($directoryMasters);
            }
            if (_entityCheckDirectoryMasters($directoryMasters))
            {
                foreach ($directoryMasters as $master)
                    echo $master . ' ';
                echo 'ok';
            }
            else
                echo " <span class=\"error\">invalid!</span>\n";
        } else
            echo " <span class=\"error\">empty!</span>\n";
        echo "<br/>\n";
        flush();

        echo "</div>\n";
        ?>

        <div id="reload">
            <?php
            if ($libppCheckOK) {
                ?>

                &gt; <a
                    onclick="javascript:window.location.reload(true);">reloading <?php echo BOOTSTRAP_NAME; ?></a> ...
                <script type="text/javascript">
                    <!--
                    setTimeout(function () {
                        window.location.reload(true)
                    }, <?php echo FIRST_RELOAD_DELAY; ?>);
                    //-->
                </script>
            <?php
            }
            else
            {
            ?>

                <button onclick="javascript:window.location.reload(true);">when ready,
                    reload <?php echo BOOTSTRAP_NAME; ?></button>
                <?php
            }
            ?>

        </div>
        <?php
    } else {
        addLog('ok sync entities', 'info', __FUNCTION__, 'c5b55957');
        ?>

        ok
        <?php
        echo "</div>\n";
        // Sinon c'est bon pour la première synchronisation.
        bootstrapFirstSynchronizingObjects();
    }
}

/**
 * Get puppetmaster ID.
 * @return string
 */
function _entityGetPuppetmaster(): string
{
    return getConfiguration('puppetmaster');
}

/**
 * Get masters of security IDs.
 * @param bool $synchronize
 * @return array
 */
function _entityGetSecurityMasters(bool $synchronize=false): array
{
    global $nebuleSecurityMasters;

    if (sizeof($nebuleSecurityMasters) != 0)
        return $nebuleSecurityMasters;

    $nid = NEBULE_REFERENCE_NID_SECURITYMASTER;
    if ($synchronize)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);

    $lnkList = array();
    $filter = array(
        'bl/rl/nid2' => $nid,
        'bs/rs/nid' => getConfiguration('puppetmaster'),
    );
    _lnkGetList($nid, $lnkList, $filter);

    // TODO

    return $nebuleSecurityMasters;
}

/**
 * Get masters of code IDs.
 * @param bool $synchronize
 * @return array
 */
function _entityGetCodeMasters(bool $synchronize=false): array
{
    global $nebuleCodeMasters;

    if (sizeof($nebuleCodeMasters) != 0)
        return $nebuleCodeMasters;

    $nid = NEBULE_REFERENCE_NID_CODEMASTER;
    if ($synchronize)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);

    $lnkList = array();
    $filter = array();
    _lnkGetList($nid, $lnkList, $filter);

    // TODO

    return array();
}

/**
 * Get masters of time IDs.
 * @param bool $synchronize
 * @return array
 */
function _entityGetTimeMasters(bool $synchronize=false): array
{
    global $nebuleTimeMasters;

    if (sizeof($nebuleTimeMasters) != 0)
        return $nebuleTimeMasters;

    $nid = NEBULE_REFERENCE_NID_TIMEMASTER;
    if ($synchronize)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);

    $lnkList = array();
    $filter = array();
    _lnkGetList($nid, $lnkList, $filter);

    // TODO

    return array();
}

/**
 * Get masters of directory IDs.
 * @param bool $synchronize
 * @return array
 */
function _entityGetDirectoryMasters(bool $synchronize=false): array
{
    global $nebuleDirectoryMasters;

    if (sizeof($nebuleDirectoryMasters) != 0)
        return $nebuleDirectoryMasters;

    $nid = NEBULE_REFERENCE_NID_DIRECTORYMASTER;
    if ($synchronize)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);

    $lnkList = array();
    $filter = array();
    _lnkGetList($nid, $lnkList, $filter);

    // TODO

    return array();
}

/**
 * Check puppetmaster entity.
 * @param string $oid
 * @return bool
 */
function _entityCheckPuppetmaster(string $oid): bool
{
    if (!_entityCheck($oid))
        return false;
    return true;
}

/**
 * Check masters of security entities.
 * @param array $oidList
 * @return bool
 */
function _entityCheckSecurityMasters(array $oidList): bool
{
    if (sizeof($oidList) == 0)
        return false;
    foreach ($oidList as $nid)
    {
        if (!_entityCheck($nid))
            return false;
    }
    return true;
}

/**
 * Check masters of code entities.
 * @param array $oidList
 * @return bool
 */
function _entityCheckCodeMasters(array $oidList): bool
{
    if (sizeof($oidList) == 0)
        return false;
    foreach ($oidList as $nid)
    {
        if (!_entityCheck($nid))
            return false;
    }
    return true;
}

/**
 * Check masters of time entities.
 * @param array $oidList
 * @return bool
 */
function _entityCheckTimeMasters(array $oidList): bool
{
    if (sizeof($oidList) == 0)
        return false;
    foreach ($oidList as $nid)
    {
        if (!_entityCheck($nid))
            return false;
    }
    return true;
}

/**
 * Check masters of directory entities.
 * @param array $oidList
 * @return bool
 */
function _entityCheckDirectoryMasters(array $oidList): bool
{
    if (sizeof($oidList) == 0)
        return false;
    foreach ($oidList as $nid)
    {
        if (!_entityCheck($nid))
            return false;
    }
    return true;
}

/**
 * Synchronize puppetmaster from central location.
 * Specifically for puppetmaster, first contents are locally generated.
 * @param string $oid
 * @return void
 */
function _entitySyncPuppetmaster(string $oid): void
{
    global $configurationList;

    if (!_entityCheck($oid))
    {
        $oid = NEBULE_DEFAULT_PUPPETMASTER_ID;
        $configurationList['puppetmaster'] = NEBULE_DEFAULT_PUPPETMASTER_ID;
    }

    if ($oid == NEBULE_DEFAULT_PUPPETMASTER_ID)
    {
        $data = FIRST_PUPPETMASTER_PUBLIC_KEY;
        io_objectWrite($data);
        $link = FIRST_PUPPETMASTER_HASH_LINK;
        io_linkWrite($oid, $link);
        $link = FIRST_PUPPETMASTER_TYPE_LINK;
        io_linkWrite($oid, $link);
    }

    _objDownloadOnLocations($oid, FIRST_LOCALISATIONS);
    // TODO sync lnk
}

/**
 * Synchronize masters of security from central location.
 * @param array $oidList
 * @return void
 */
function _entitySyncSecurityMasters(array $oidList): void
{
    foreach ($oidList as $nid)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);
    // TODO sync lnk
}

/**
 * Synchronize masters of code from central location.
 * @param array $oidList
 * @return void
 */
function _entitySyncCodeMasters(array $oidList): void
{
    foreach ($oidList as $nid)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);
    // TODO sync lnk
}

/**
 * Synchronize masters of time from central location.
 * @param array $oidList
 * @return void
 */
function _entitySyncTimeMasters(array $oidList): void
{
    foreach ($oidList as $nid)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);
    // TODO sync lnk
}

/**
 * Synchronize masters of directory from central location.
 * @param array $oidList
 * @return void
 */
function _entitySyncDirectoryMasters(array $oidList): void
{
    foreach ($oidList as $nid)
        _objDownloadOnLocations($nid, FIRST_LOCALISATIONS);
    // TODO sync lnk
}



// ------------------------------------------------------------------------------------------
/**
 * Synchronisation des objets sur internet pour fonctionner.
 *
 * @return void
 */
function bootstrapFirstSynchronizingObjects()
{
    global $nebuleLocalAuthorities;

    $refApps = REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS;
    $refAppsID = _objGetNID($refApps, getConfiguration('cryptoHashAlgorithm'));
    $refLib = REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE;
    $refLibID = _objGetNID($refLib, getConfiguration('cryptoHashAlgorithm'));
    $refBoot = REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP;
    $refBootID = _objGetNID($refBoot, getConfiguration('cryptoHashAlgorithm'));
    ?>

    <div class="parts">
        <span class="partstitle">#5 synchronizing objets</span><br/>
        <?php
        // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
        if (!io_checkNodeHaveContent($refAppsID)
        && !io_checkNodeHaveContent($refLibID)
        && !io_checkNodeHaveLink($refBootID)
        )
        {
        addLog('need sync objects', 'warn', __FUNCTION__, '0f21ad26');

        // Ecrit les objets de localisation.
        echo 'objects &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        foreach (FIRST_LOCALISATIONS as $data) {
            $hash = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));;
            foreach (FIRST_LOCALISATIONS as $localisation) {
                $count = _lnkDownloadOnLocation($hash, $localisation);
                echo '.';
                if ($count != 0) {
                    break 1;
                }
            }
            echo ' ';
            flush();
        }

        // Ecrit les objets réservés.
        foreach (FIRST_RESERVED_OBJECTS as $data) {
            $hash = _objGetNID($data, getConfiguration('cryptoHashAlgorithm'));;
            foreach (FIRST_LOCALISATIONS as $localisation) {
                $count = _lnkDownloadOnLocation($hash, $localisation);
                echo '.';
                if ($count != 0) {
                    break 1;
                }
            }
            echo ' ';
            flush();
        }

        $data = REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS;
        io_objectWrite($data);

        $data = REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE;
        io_objectWrite($data);
        ?><br/>

        bootstrap start &nbsp;&nbsp;&nbsp;:
        <?php
        echo $refBoot . ' ';
        flush();
        foreach (FIRST_LOCALISATIONS as $localisation) {
            _lnkDownloadOnLocation($refBootID, $localisation);
            echo '.';
        }
        ?><br/>

        library start &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        echo $refLib . ' ';
        flush();
        foreach (FIRST_LOCALISATIONS as $localisation) {
            _lnkDownloadOnLocation($refLibID, $localisation);
            echo '.';
        }
        ?><br/>

        synchronization &nbsp;&nbsp;&nbsp;:
        <?php
        // Recherche par référence.
        $lastID = nebFindByRef(
            $refLibID,
            $refLibID,
            false);
        echo $lastID . ' ';
        if ($lastID != '0') {
            foreach (FIRST_LOCALISATIONS as $localisation) {
                io_objectSynchronize($lastID, $localisation);
                echo '.';
            }
        } else {
            echo '<span id="error">ERROR!</span>';
        }
        ?><br/>

        applications list &nbsp;:
        <?php
        echo $refAppsID . ' ';
        flush();
        foreach (FIRST_LOCALISATIONS as $localisation) {
            _lnkDownloadOnLocation($refAppsID, $localisation);
            echo '.';
        }
        ?><br/>

        application list &nbsp;&nbsp;:
        <?php
        // Pour chaque application, faire une synchronisation.
        $links = array();
        _lnkFindInclusive($refAppsID, $links, 'f', $refAppsID, '', $refAppsID, false);

        // Tri sur autorités locales.
        $signer = '';
        $authority = '';
        foreach ($links as $i => $link) {
            $signer = $link[2];
            $ok = false;
            foreach ($nebuleLocalAuthorities as $authority) {
                if ($signer == $authority) {
                    $ok = true;
                    break;
                }
            }
            if ($ok) {
                echo '.';
            } else {
                // Si le signataire n'est pas autorité locale, supprime le lien.
                unset($links[$i]);
            }
        }
        echo "<br />\n";

        // Pour toutes les applications, les télécharge et recherche leurs noms.
        $refName = 'nebule/objet/nom';
        foreach ($links as $app) {
            ?>

            synchronization &nbsp;&nbsp;&nbsp;:
            <?php
            $appID = $app[6];
            echo $appID . ' ';
            // Recherche par référence.
            $lastID = nebFindByRef(
                $appID,
                $refAppsID,
                false);
            addLog('find app ' . $appID . ' as ' . $lastID, 'info', __FUNCTION__, '4cc18a65');
            if ($lastID != '0') {
                foreach (FIRST_LOCALISATIONS as $localisation) {
                    io_objectSynchronize($lastID, $localisation);
                    _lnkDownloadOnLocation($lastID, $localisation);
                    echo '.';
                }
                echo ' ';
                // Cherche le nom.
                $nameID = nebFindObjType(
                    $lastID,
                    $refName);
                if ($nameID == '0') {
                    $nameID = nebFindObjType(
                        $appID,
                        $refName);
                }
                if ($nameID != '0') {
                    foreach (FIRST_LOCALISATIONS as $localisation) {
                        io_objectSynchronize($nameID, $localisation);
                        _lnkDownloadOnLocation($nameID, $localisation);
                        echo '.';
                    }
                }
            } else {
                echo '<span id="error">ERROR!</span>';
            }
            ?><br/>

            <?php
        }

        echo "</div>\n";
        ?>

    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo BOOTSTRAP_NAME; ?></a> ...
    <script type="text/javascript">
        <!--
        setTimeout(function () {
            window.location.reload(true)
        }, <?php echo FIRST_RELOAD_DELAY; ?>);
        //-->
    </script>
    <?php
} else {
    addLog('ok sync objects', 'info', __FUNCTION__, '4473358f');
    ?>
    ok
    <?php
    echo "</div>\n";
    // Si c'est bon on continue la création du fichier des options par défaut.
    bootstrapFirstCreateOptionsFile();
}
}


// ------------------------------------------------------------------------------------------
/**
 * Crée le fichier des options par défaut.
 *
 * @return void
 */
function bootstrapFirstCreateOptionsFile()
{
    ?>

    <div class="parts">
        <span class="partstitle">#6 options file</span><br/>
        <?php
        if (!file_exists(NEBULE_ENVIRONMENT_FILE))
        {
        addLog('need create options file', 'warn', __FUNCTION__, '58d07f71');

        $defaultOptions = "# Generated by the " . BOOTSTRAP_NAME . ", part of the " . BOOTSTRAP_AUTHOR . ".\n";
        $defaultOptions .= "# Default options file generated after the first synchronization.\n";
        $defaultOptions .= "# " . BOOTSTRAP_SURNAME . "\n";
        $defaultOptions .= "# Version : " . BOOTSTRAP_VERSION . "\n";
        $defaultOptions .= "# http://" . BOOTSTRAP_WEBSITE . "\n";
        $defaultOptions .= "\n";
        $defaultOptions .= "# nebule bash\n";
        $defaultOptions .= "filesystemBaseDirectory = ~/nebule\n";
        $defaultOptions .= "filesystemPublicDirectory = ~/nebule/pub\n";
        $defaultOptions .= "filesystemPrivateDirectory = ~/nebule/priv\n";
        $defaultOptions .= "filesystemTemporaryDirectory = ~/nebule/temp\n";
        $defaultOptions .= "filesystemLogActivate = false\n";
        $defaultOptions .= "filesystemLogFile = ~/nebule/neb.log\n";
        $defaultOptions .= "\n";
        $defaultOptions .= "# nebule php\n";
        $defaultOptions .= "# Options writen here are write-protected for the library and all applications.\n";
        $defaultOptions .= "puppetmaster = " . getConfiguration('puppetmaster') . "\n";
        $defaultOptions .= "permitWrite = true\n";
        $defaultOptions .= "permitListInvalidLinks = false\n";
        $defaultOptions .= "permitInstanceEntityAsAuthority = false\n";
        $defaultOptions .= "permitDefaultEntityAsAuthority = false\n";
        $defaultOptions .= "modeRescue = false\n";
        $defaultOptions .= "displayUnsecureURL = true\n";
        $defaultOptions .= "\n";
        file_put_contents(NEBULE_ENVIRONMENT_FILE, $defaultOptions);
        if (file_exists(NEBULE_ENVIRONMENT_FILE))
        {
        echo "ok created.\n";
        ?>

    </div>
    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo BOOTSTRAP_NAME; ?></a> ...
    <script type="text/javascript">
        <!--
        setTimeout(function () {
            window.location.reload(true)
        }, <?php echo FIRST_RELOAD_DELAY; ?>);
        //-->
    </script>
    <?php
}
else {
    echo " <span class=\"error\">ERROR!</span><br />\n";
    ?>

    <div class="diverror">
        Unable to create options file <b><?php echo NEBULE_ENVIRONMENT_FILE; ?></b> .<br/>
        On the same path as <b>index.php</b>, please create file manually.<br/>
        As <i>root</i>, run :<br/>
        <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

cat &gt; <?php echo NEBULE_ENVIRONMENT_FILE; ?> &lt;&lt; EOF
<?php echo $defaultOptions; ?>

EOF
chmod 644 <?php echo NEBULE_ENVIRONMENT_FILE; ?>
                </pre>
        <?php
        echo "</div>\n";
        ?>
    <button onclick="javascript:window.location.reload(true);">when ready, reload <?php echo BOOTSTRAP_NAME; ?></button>
    </div>
    <?php
}
    unset($defaultOptions);
}
else {
    addLog('ok create options file', 'info', __FUNCTION__, '91e9b5bd');
    ?>
    ok
    <?php
    echo "</div>\n";
    // Si c'est bon on continue.
    bootstrapFirstCreateLocaleEntity();
}
}


// ------------------------------------------------------------------------------------------
/**
 * Crée le fichier des options par défaut.
 *
 * @return void
 */
function bootstrapFirstCreateLocaleEntity()
{
    global $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite;
    ?>

    <div class="parts">
        <span class="partstitle">#7 local entity for server</span><br/>
        <?php
        if ( //file_exists(NEBULE_LOCAL_ENTITY_FILE)
            //&& is_file(NEBULE_LOCAL_ENTITY_FILE)
            //&& file_put_contents( NEBULE_LOCAL_ENTITY_FILE, '0') !== false
            file_put_contents(LOCAL_ENTITY_FILE, '0') !== false
        ) {
            // Génère un mot de passe.
            $nebulePasswordEntite = '';
            $padding = '';
            $genpasswd = openssl_random_pseudo_bytes(512);
            // Filtrage des caractères du mdp dans un espace restreint. Alphadécimal, à revoir...
            for ($i = 0; $i < strlen($genpasswd); $i++) {
                $a = ord($genpasswd[$i]);
                if (!($a < 48 || $a > 102 || ($a > 57 && $a < 97))) {
                    $nebulePasswordEntite .= $genpasswd[$i];
                }
                $padding .= '0';
            }
            $nebulePasswordEntite = substr($nebulePasswordEntite . $padding, 0, FIRST_GENERATED_PASSWORD_SIZE);
            unset($genpasswd, $padding, $i, $a);

            $nebulePublicEntity = '0';
            $nebulePrivateEntite = '0';
            // Génère une nouvelle entité.
            _entityGenerate(getConfiguration('cryptoAsymetricAlgorithm'), getConfiguration('cryptoHashAlgorithm'), $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite);

            // Définit l'entité comme entité instance du serveur.
            file_put_contents(LOCAL_ENTITY_FILE, $nebulePublicEntity);

            // Calcul le nom.
            $genname = hex2bin($nebulePublicEntity . $nebulePrivateEntite);
            $name = '';
            // Filtrage des caractères du nom dans un espace restreint.
            for ($i = 0; $i < strlen($genname); $i++) {
                $a = ord($genname[$i]);
                if (($a > 96 && $a < 123)) {
                    $name .= $genname[$i];
                    // Insertion de voyelles.
                    if (($i % 3) == 0) {
                        $car = hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % 14;
                        switch ($car) {
                            case 0 :
                            case 6 :
                                $name .= 'a';
                                break;
                            case 1 :
                            case 7 :
                            case 11 :
                            case 13 :
                                $name .= 'e';
                                break;
                            case 2 :
                            case 8 :
                                $name .= 'i';
                                break;
                            case 3 :
                            case 9 :
                                $name .= 'o';
                                break;
                            case 4 :
                            case 10 :
                            case 12 :
                                $name .= 'u';
                                break;
                            case 5 :
                                $name .= 'y';
                                break;
                        }
                    }
                }
            }
            $name = substr($name . 'robott', 0, FIRST_GENERATED_NAME_SIZE);

            // Enregistrement du nom.
            nebCreatAsText($name);
            $refHashName = _objGetNID('nebule/objet/nom', getConfiguration('cryptoHashAlgorithm'));
            $hashName = _objGetNID($name, getConfiguration('cryptoHashAlgorithm'));
            $newlink = _lnkGenerate('-', 'l', $nebulePublicEntity, $hashName, $refHashName);
            if (_lnkVerify($newlink) == 1) {
                _lnkWrite($newlink);
            }
            unset($newlink);

            ?>
            new server entity<br/>
            public ID &nbsp;: <?php echo $nebulePublicEntity; ?><br/>
            private ID : <?php echo $nebulePrivateEntite; ?>

            <div class="important">
                name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $name; ?><br/>
                public ID : <?php echo $nebulePublicEntity; ?><br/>
                password &nbsp;: <?php echo $nebulePasswordEntite; ?><br/>
                Please keep and save securely thoses private informations!
            </div>
            <form method="post" action="?<?php echo ARG_SWITCH_APPLICATION; ?>=0">
                <input type="hidden" name="pwd" value="<?php echo $nebulePasswordEntite; ?>">
                <input type="hidden" name="switch" value="true">
                <input type="submit" value="when ready, click here to go to options">
            </form>
            <br/><br/>
            <button onclick="javascript:window.location.assign('?<?php echo ARG_SWITCH_APPLICATION; ?>=0');">when
                ready, click here to go to applications and options
            </button>
            <?php
        } else {
            file_put_contents(LOCAL_ENTITY_FILE, '0');
            echo " <span class=\"error\">ERROR!</span><br />\n";
            ?>

            <div class="diverror">
                Unable to create local entity file <b><?php echo LOCAL_ENTITY_FILE; ?></b> .<br/>
                On the same path as <b>index.php</b>, please create file manually.<br/>
                As <i>root</i>, run :<br/>
                <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

touch <?php echo LOCAL_ENTITY_FILE; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LOCAL_ENTITY_FILE; ?>

chmod 644 <?php echo LOCAL_ENTITY_FILE; ?>
</pre>
                <?php

                echo "</div>\n";
                ?>
            <button onclick="javascript:window.location.reload(true);">when ready,
                reload <?php echo BOOTSTRAP_NAME; ?></button>
            <?php
        }
        ?>

    </div>
    <?php
}


/*
 *
 *
 *
 *

 ==/ 9 /===================================================================================
 PART9 : Display of application 0 web page to select application to run.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrapDisplayApplication0()
{
    global $nebuleInstance,
           $loggerSessionID;

    // Initialisation des logs
    reopenLog('app0');
    addLog('Loading', 'info', __FUNCTION__, '314e6e9b');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    // Ré-initialisation des logs FIXME ???
    reopenLog('app0');

    ?>

    <div id="appslist">
        <?php
        // Extraire la liste des applications disponibles.
        $refAppsID = $nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS);
        $instanceAppsID = new Object($nebuleInstance, $refAppsID);
        $applicationsList = array();
        $signersList = array();
        $hashTarget = '';

        // Liste les applications reconnues par le maître du code.
        $linksList = $instanceAppsID->readLinksFilterFull($nebuleInstance->getCodeMaster(), '', 'f', $refAppsID, '', $refAppsID);
        $link = null;
        foreach ($linksList as $link) {
            $hashTarget = $link->getHashTarget();
            $applicationsList[$hashTarget] = $hashTarget;
            $signersList[$hashTarget] = $link->getHashSigner();
        }

        // Liste les applications reconnues par l'entité instance du serveur, si autorité locale et pas en mode de récupération.
        if ($nebuleInstance->getConfiguration('permitInstanceEntityAsAuthority')
            && !$nebuleInstance->getModeRescue()
        ) {
            $linksList = $instanceAppsID->readLinksFilterFull($nebuleInstance->getInstanceEntity(), '', 'f', $refAppsID, '', $refAppsID);
            foreach ($linksList as $link) {
                $hashTarget = $link->getHashTarget();
                $applicationsList[$hashTarget] = $hashTarget;
                $signersList[$hashTarget] = $link->getHashSigner();
            }
        }

        // Liste les applications reconnues par l'entité par défaut, si autorité locale et pas en mode de récupération.
        if ($nebuleInstance->getConfiguration('permitDefaultEntityAsAuthority')
            && !$nebuleInstance->getModeRescue()
        ) {
            $linksList = $instanceAppsID->readLinksFilterFull($nebuleInstance->getDefaultEntity(), '', 'f', $refAppsID, '', $refAppsID);
            foreach ($linksList as $link) {
                $hashTarget = $link->getHashTarget();
                $applicationsList[$hashTarget] = $hashTarget;
                $signersList[$hashTarget] = $link->getHashSigner();
            }
        }
        unset($refAppsID, $linksList, $link, $hashTarget, $instanceAppsID);

        // Affiche la page d'interruption.
        echo '<a href="/?b">';
        echo '<div class="apps" style="background:#000000;">';
        echo '<span class="appstitle">Nb</span><br /><span class="appsname">break</span>';
        echo "</div></a>\n";

        // Lister les applications.
        $application = '';
        foreach ($applicationsList as $application) {
            $instance = new Object($nebuleInstance, $application);

            // Recherche si l'application est activée par l'entité instance de serveur.
            // Ou si l'application est en liste blanche.
            // Ou si c'est l'application par défaut.
            $activated = false;
            foreach (nebule::ACTIVE_APPLICATIONS_WHITELIST as $item) {
                if ($application == $item) {
                    $activated = true;
                }
            }
            if ($application == $nebuleInstance->getConfiguration('defaultApplication')) {
                $activated = true;
            }
            if (!$activated) {
                $refActivated = $nebuleInstance->getCrypto()->hash(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE);
                $linksList = $instance->readLinksFilterFull($nebuleInstance->getInstanceEntity(), '', 'f', $application, $refActivated, $application);
                if (sizeof($linksList) != 0) {
                    $activated = true;
                }
                unset($linksList);
            }

            // En fonction de l'état d'activation, affiche ou non l'appication.
            if (!$activated) {
                continue;
            }

            $color = '#' . substr($application . '000000', 0, 6);
            //$colorSigner = '#'.substr($signersList[$application].'000000',0,6);
            $title = $instance->getName();
            $shortName = substr($instance->getSurname() . '--', 0, 2);
            $shortName = strtoupper(substr($shortName, 0, 1)) . strtolower(substr($shortName, 1, 1));
            echo '<a href="/?' . ARG_SWITCH_APPLICATION . '=' . $application . '">';
            echo '<div class="apps" style="background:' . $color . ';">';
            echo '<span class="appstitle">' . $shortName . '</span><br /><span class="appsname">' . $title . '</span>';
            echo "</div></a>\n";
        }
        unset($application, $applicationsList, $instance, $color, $title, $shortName);
        ?>

    </div>
    <div id="sync">
    </div>
    <?php
    bootstrapHtmlBottom();
}


/*
 *
 *
 *
 *

 ==/ 10 /==================================================================================
 PART10 : Display of application 1 web page to display documentation of nebule.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrapDisplayApplication1()
{
    global $nebuleInstance, $loggerSessionID, $nebuleLibLevel, $nebuleLibVersion, $nebuleLicence, $nebuleAuthor, $nebuleWebsite;

    // Initialisation des logs
    reopenLog('app1');
    addLog('Loading', 'info', __FUNCTION__, 'a4e4acfe');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    // Ré-initialisation des logs FIXME ???
    reopenLog('app1');

    // Instancie la classe de la documentation.
    $instance = new nebdoctech($nebuleInstance);

    // Affiche la documentation.
    echo '<div id="layout_documentation">' . "\n";
    echo ' <div id="title_documentation"><p>Documentation technique de ' . $nebuleInstance->__toString() . '<br />' . "\n";
    echo '  Version ' . $nebuleInstance->getConfiguration('defaultLinksVersion') . ' - ' . $nebuleLibVersion . ' ' . $nebuleLibLevel . '<br />' . "\n";
    echo '  (c) ' . $nebuleLicence . ' ' . $nebuleAuthor . ' - <a href="' . $nebuleWebsite . '">' . $nebuleWebsite . "</a></p></div>\n";
    echo ' <div id="content_documentation">' . "\n";
    $instance->display_content();
    echo " </div>\n";
    echo "</div>\n";

    bootstrapHtmlBottom();
}


/*
 *
 *
 *
 *

 ==/ 11 /==================================================================================
 PART11 : Display of application 2 default application.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrapDisplayApplication2()
{
    global $nebuleInstance, $loggerSessionID, $nebuleLibLevel, $nebuleLibVersion, $nebuleLicence, $nebuleAuthor, $nebuleWebsite;

    // Initialisation des logs
    reopenLog('app2');
    addLog('Loading', 'info', __FUNCTION__, '3a5c4178');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    // Ré-initialisation des logs FIXME ???
    reopenLog('app1');

?>
    <div class="layout-main">
        <div class="layout-content">
            <img alt="nebule" id="logo" src="<?php echo DEFAULT_APPLICATION_LOGO_LIGHT; ?>"/>
        </div>
    </div>

<?php

    bootstrapHtmlBottom();
}


/*
 *
 *
 *
 *

 ==/ 12 /==================================================================================
 PART12 : Main display router.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function displayRouter(bool $needFirstSynchronization, $bootstrapLibraryID)
{
    global $bootstrapBreak, $bootstrapRescueMode, $bootstrapInlineDisplay, $loggerSessionID,
           $bootstrapApplicationID, $bootstrapApplicationNoPreload,
           $bootstrapApplicationStartID, $nebuleInstance,
           $bootstrapServerEntityDisplay;

    if (sizeof($bootstrapBreak) == 0) {
        unset($bootstrapBreak, $bootstrapRescueMode, $bootstrapInlineDisplay);

        // Ferme les I/O de la bibliothèque PHP PP.
        io_close();

        // Fin de la bufferisation de la sortie avec effacement du buffer.
        // Ecrit dans le buffer pour test, ne devra jamais apparaître.
        echo 'CHK';
        // Tout ce qui aurait éventuellement essayé d'être affiché est perdu.
        ob_end_clean();

        if ($bootstrapApplicationID == '0') {
            addLog('load application 0', 'info', __FUNCTION__, '1ad59685');
            bootstrapDisplayApplication0();
            reopenLog(BOOTSTRAP_NAME);
        } elseif ($bootstrapApplicationID == '1') {
            addLog('load application 1', 'info', __FUNCTION__, '2acd5fee');
            bootstrapDisplayApplication1();
            reopenLog(BOOTSTRAP_NAME);
        } elseif ($bootstrapApplicationID == '2') {
            addLog('load application 2', 'info', __FUNCTION__, '1d718d83');
            bootstrapDisplayApplication2();
            reopenLog(BOOTSTRAP_NAME);
        } else {
            // Si tout est déjà pré-chargé, on déserialise.
            if (isset($bootstrapApplicationInstanceSleep)
                && $bootstrapApplicationInstanceSleep != ''
                && isset($bootstrapApplicationDisplayInstanceSleep)
                && $bootstrapApplicationDisplayInstanceSleep != ''
                && isset($bootstrapApplicationActionInstanceSleep)
                && $bootstrapApplicationActionInstanceSleep != ''
                && isset($bootstrapApplicationTraductionInstanceSleep)
                && $bootstrapApplicationTraductionInstanceSleep != ''
            ) {
                addLog('load application ' . $bootstrapApplicationID, 'info', __FUNCTION__, 'aab236ff');

                // Charge l'objet de l'application. @todo faire via les i/o.
                include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);

                $applicationName = Application::APPLICATION_NAME;

                // Change les logs au nom de l'application.
                reopenLog($applicationName);

                // Désérialise les instances.
                $applicationInstance = unserialize($bootstrapApplicationInstanceSleep);
                $applicationDisplayInstance = unserialize($bootstrapApplicationDisplayInstanceSleep);
                $applicationActionInstance = unserialize($bootstrapApplicationActionInstanceSleep);
                $applicationTraductionInstance = unserialize($bootstrapApplicationTraductionInstanceSleep);

                // Initialisation de réveil de l'instance de l'application.
                $applicationInstance->initialisation2();

                // Si la requête web est un téléchargement d'objet ou de lien, des accélérations pruvent être prévues dans ce cas.
                if (!$applicationInstance->askDownload()) {
                    // Initialisation de réveil des instances.
                    $applicationTraductionInstance->initialisation2();
                    $applicationDisplayInstance->initialisation2();
                    $applicationActionInstance->initialisation2();

                    // Réalise les tests de sécurité.
                    $applicationInstance->checkSecurity();
                }

                // Appel de l'application.
                $applicationInstance->router();
            } elseif ($bootstrapApplicationNoPreload) {
                // Si l'application ne doit être pré-chargée,
                //   réalise maintenant le pré-chargement de façon transparente et lance l'application.
                // Ainsi, le pré-chargement n'est pas fait sur une page web à part.

                addLog('load application whitout preload ' . $bootstrapApplicationID, 'info', __FUNCTION__, 'e01ea813');

                // Charge l'objet de l'application. @todo faire via les i/o.
                include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);

                $applicationName = Application::APPLICATION_NAME;

                // Change les logs au nom de l'application.
                reopenLog($applicationName);

                // Instanciation des classes de l'application.
                $applicationInstance = new Application($nebuleInstance);
                $applicationTraductionInstance = new Traduction($applicationInstance);
                $applicationDisplayInstance = new Display($applicationInstance);
                $applicationActionInstance = new Action($applicationInstance);

                // Initialisation des instances.
                $applicationInstance->initialisation();
                $applicationTraductionInstance->initialisation();
                $applicationDisplayInstance->initialisation();
                $applicationActionInstance->initialisation();

                // Réalise les tests de sécurité.
                $applicationInstance->checkSecurity();

                // Appel de l'application.
                $applicationInstance->router();
            } else {
                // Sinon on va faire un pré-chargement.
                bootstrapDisplayPreloadApplication();
            }

            // Change les logs au nom du bootstrap.
            reopenLog(BOOTSTRAP_NAME);

            // Ouverture de la session PHP.
            session_start();

            // Sauve les ID dans la session PHP.
            $_SESSION['bootstrapApplicationID'] = $bootstrapApplicationID;
            $_SESSION['bootstrapApplicationStartID'] = $bootstrapApplicationStartID;
            $_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID] = $bootstrapApplicationID;
            $_SESSION['bootstrapLibrariesID'] = $bootstrapLibraryID;

            // Sérialise les instances et les sauve dans la session PHP.
            $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID] = serialize($applicationInstance);
            $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID] = serialize($applicationDisplayInstance);
            $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID] = serialize($applicationActionInstance);
            $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID] = serialize($applicationTraductionInstance);
            $_SESSION['bootstrapLibrariesInstances'][$bootstrapLibraryID] = serialize($nebuleInstance);

            // Fermeture de la session avec écriture.
            session_write_close();
        }
    } else {
        if ($needFirstSynchronization) {
            addLog('load first', 'info', __FUNCTION__, '63d9bc00');

            // Affichage sur interruption du chargement.
            bootstrapDisplayApplicationfirst();
        } elseif ($bootstrapServerEntityDisplay) {
            if (file_exists(LOCAL_ENTITY_FILE)) {
                echo file_get_contents(LOCAL_ENTITY_FILE, false, null, -1, getConfiguration('ioReadMaxData'));
            } else {
                echo '0';
            }
        } else {
            addLog('load break', 'info', __FUNCTION__, '4abf554b');

            // Affichage sur interruption du chargement.
            if ($bootstrapInlineDisplay) {
                bootstrapInlineDisplayOnBreak();
            } else {
                bootstrapDisplayOnBreak();
            }
        }

        // Change les logs au nom du bootstrap.
        reopenLog(BOOTSTRAP_NAME);
    }
}

function bootstrapLogMetrology()
{
    global $nebuleInstance;

    // Metrology on logs.
    if (is_a($nebuleInstance, 'nebule')) {
        addLog('Mp=' . memory_get_peak_usage()
            . ' - Lr=' . _metrologyGet('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead()
            . ' Lv=' . _metrologyGet('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify()
            . ' Or=' . _metrologyGet('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead()
            . ' Ov=' . _metrologyGet('ov') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify()
            . ' (PP+POO) -'
            . ' LC=' . $nebuleInstance->getCacheLinkSize()
            . ' OC=' . $nebuleInstance->getCacheObjectSize()
            . ' EC=' . $nebuleInstance->getCacheEntitySize()
            . ' GC=' . $nebuleInstance->getCacheGroupSize()
            . ' CC=' . $nebuleInstance->getCacheConversationSize(), 'info', __FUNCTION__, '0d99ad8b');
    } else {
        addLog('Mp=' . memory_get_peak_usage()
            . ' - Lr=' . _metrologyGet('lr')
            . ' Lv=' . _metrologyGet('lv')
            . ' Or=' . _metrologyGet('or')
            . ' Ov=' . _metrologyGet('ov')
            . ' (PP)', 'info', __FUNCTION__, '52d76692');
    }
}

function main()
{
    if (!libppInit())
        setBootstrapBreak('21', 'Library init error');

    if (!io_checkLinkFolder())
        setBootstrapBreak('22', "Library i/o link's folder error");

    if (!io_checkObjectFolder())
        setBootstrapBreak('23', "Library i/o object's folder error");

    getBootstrapUserBreak();
    getBootstrapInlineDisplay();
    getBootstrapCheckFingerprint();
    $needFirstSynchronization = getBootstrapNeedFirstSynchronization();
    getBootstrapDisplayServerEntity();
    getBootstrapFlushSession();
    getBootstrapUpdate();
    getBootstrapSwitchApplication();

    setPermitOpenFileCode();

    $bootstrapLibraryID = '';
    $bootstrapLibraryInstanceSleep = '';
    findLibraryPOO($bootstrapLibraryID, $bootstrapLibraryInstanceSleep);
    loadLibraryPOO($bootstrapLibraryID, $bootstrapLibraryInstanceSleep);
    findApplication();

    displayRouter($needFirstSynchronization, $bootstrapLibraryID);
    bootstrapLogMetrology();
}

main();

?>
