<?php
declare(strict_types=1);
namespace Nebule\Bootstrap;
//use nebule;
// ------------------------------------------------------------------------------------------
use function Sodium\add;

$bootstrapName = 'bootstrap';
$bootstrapSurname = 'nebule/bootstrap';
$bootstrapDescription = 'Loader of library and applications.';
$bootstrapAuthor = 'Project nebule';
$bootstrapVersion = '020210424';
$bootstrapLicence = 'GNU GPL 2010-2021';
$bootstrapWebsite = 'www.nebule.org';
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
 PART11 : Main display router.
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

// Command line args.
define('ARG_BOOTSTRAP_BREAK', 'b');
define('ARG_FLUSH_SESSION', 'f');
define('ARG_UPDATE_APPLICATION', 'u');
define('ARG_SWITCH_APPLICATION', 'a');
define('ARG_RESCUE_MODE', 'r');
define('ARG_INLINE_DISPLAY', 'i');
define('ARG_STATIC_DISPLAY', 's'); // TODO not used yet
define('ARG_NODE_OBJECT', 'o');
define('ARG_NODE_LINK', 'l');
define('ARG_SERVER_ENTITY', 'e');

// ------------------------------------------------------------------------------------------

// Logs setting and initializing.
/**
 * Log unique ID for one run.
 */
$loggerSessionID = bin2hex(openssl_random_pseudo_bytes(6, $false));

/**
 * Start timer for metrology.
 *
 * @var float $metrologyStartTime
 */
$metrologyStartTime = microtime(true);

// Initialize logs.
openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
syslog(LOG_INFO, 'LogT=0 LogTabs=' . $metrologyStartTime . ' --- start ' . $bootstrapName);

/**
 * Add message to logs.
 *
 * @param string $message
 * @return void
 */
function addLog(string $message): void
{
    global $metrologyStartTime;

    syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL=B LogM="' . $message . '"');
}

// ------------------------------------------------------------------------------------------

// Objects used as reference.

define('REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP', 'nebule/objet/interface/web/php/bootstrap');
define('REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE', 'nebule/objet/interface/web/php/bibliotheque');
define('REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS', 'nebule/objet/interface/web/php/applications');
define('REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_DIRECT', 'nebule/objet/interface/web/php/applications/direct');
define('REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_ACTIVE', 'nebule/objet/interface/web/php/applications/active');

define('REFERENCE_BOOTSTRAP_ICON', "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAARoElEQVR42u2dbbCcZX2Hrz1JMBSUAoEECCiVkLAEEgKkSBAwgFgGwZFb6NBOSVJn0H5peXXqSwVrdQZF6Cd0RiQ4kiH415ZpOlUECpKABBLewkIAayFAAgRCGxBylJx+2KdDwHCS7Ov9PHtdMzt8IGf32f/ev+v+388riIiIiIiIiIiIiIhINalZgvKTUtoTmFy89gUmAXsDewC7F69dgV2AnYGdgLFb/P4jwO+BYeAN4HVgI/AqsAF4BXgRWAc8DzwLrImIV6y+ApDehHx/4LDiVQemAR8G9uzzpr0M/Bp4HGgAjwCPRMQafzUFIK2F/XDgOGB28TrkXf9kJMPfbmvb9BiwvHgtjYiH/XUVgLwz7GOBU4GTgZOKGT7noLcrhkeA24BbgVsi4neOAgUwaKE/ETgTOB04aMDL8RSwBLg5Iu5wdCiAKgb+j4BzgXOKmV7em1uBG4FFEfGG5VAAZQ39B4AFwHnAzAq19L1cMjwA/BC4NiI2WhoFUIbg/zXwOeAoQ99RGdwPfDcirrUsCiC30M8BLgQ+beh7IoOfAFdFxDLLogD6FfoacBFwCc2Tbgx972XwInAF8J2IGLEsCqAXwd8fuByY72yfVVdwHfBVT0BSAN0K/mzgm8Bcq5E1twN/HxHLLYUC6ETw5wJXAYc725eqK3gYuCAibrckCqCV4J8IXEPznHuDX14RPA583pOMFMD2Bn9msZ6cafArJYIHgfkR8aAlUQBbC/5EYCHwCYNf
aRH8DJgXES9YEhiyBJBS+mea17qfqhgrP9mdCqxLKV1tSQZ8oKeU/qJo98c5FAaSYWBBRNygAAYr+PvRvALNdb7LghrNaw4+GRHPuQSofvgvp3lLqxl2QXbAxX9nAs+mlC6zA6hu8A+meSOKyY57GYVngZMi4gk7gOqE/zJgNbCf41u2wX7A6kHpBmoVD/6ewDJgquNaWmA1cGyV7348VOHwnw2sBw52HEuLHAy8XIwlBVCi8N8ALMY9/NJ+hzwCLC7GlEuAzIM/geYhHXf0STdYA8yKiPV2APmF/3jgJdzRJ91jMvBSMdYUQEbh/wJwpy2/9GhJcGdK6VKXAHmE/ybgM45N6QM/joizFUB/gl8DHuKdT9IR6TUPAzPLek/CWknDvxvNY7R72/JLnxkBXgCmRsT/KoDuh38y8AQw3vBLRhJ4E5hStguKhkoW/jrwjOGXDCfS8cCalNIhCqA74T8SeLTMSxepvAQAGimlWQqgs+E/huajoTzMJ7lLYARYkVL6U/cBdC789xh+Kdk+gRpwTETcqwDaa/ud+aXMEjgqIlYogB0Pf71Y8xt+KbsE6hHxmALY/vBPprm3H8MvFZAAwAER8awC2Hb4P0DzxIr3GX6pkAQ2ARNzO1loKLPw12ie4Wf4pUrUijG9uhjjCuA9eACYaPilohKYWIxxBbCV2X8xzVt1G36psgRmpJRuzGWDxmQS/i8Af+v4kAFher1e/22j0bg7ByP1O/zH07yZh8igcXxE3DWwAiju4fcSHuuXweP/x/yEiHh5UPcBrDD8MsD7A0aA
lf3ciL4JoLjN8gGGXwZcAgeklH7Urw0Y06fwnwNc7u8vAsDh9Xr9sUaj8Wjl9wEUj+tab+sv0v/9Af1YAtxt+EW2uj9gWaWXAMUTV88y/CJblcCEer1Oo9G4s5cf2qvwT6F5M08RGZ0pEfFU1ZYAt/H2pZEi8t77A26v1BKgaP3PyKH1r9VcfViLrOtRA3br1VKg1oPwT6b5VNVsmDBhAuvXr3fEA6eddhrjx48f+DoMDw+zZMmS3DZrcrefMzC2B1/i38hsr/+JJ57I0qVLWbdu3cAP/HHjxjFu3Dj77pHsVqcjRXa6eovxru4DSCmdC8wks73+b731FieccAKTJk2yBZBsVyTAEUWGyikAYCEZ7/hTApJ7Y1JkqHwCSCldDYwj82P+SkAy7wLGpZSuKpUAUkoTKdENPpSAZM7fFZkqTQdwHSU75q8EJPOlwA9KIYCU0gzgzyjh6b5KQDJeCpxWZCv7DqB0s78SkEHtAjoqgOL+fkdQ8ot9lIBk2gXMKjKWbQfwPSpyvr8SkEy7gO9mKYCU0seAaVToUl8lIBl2AYeklE7MsQO4igpe7acEJMMu4OqsBJBSmk2Fn+qjBCSzLmBGkblsOoBvUvFr/ZWAZNYFfCMLAaSU9gfmMgC3+VICklEXcFJxqX3fO4DLBqnySkAy4vK+CqB41vmCQau6EpBMWNBXAQAXDmrllYDkQErpon4K4FIG+EafSkD6zAhwSV8EkFL6CLA3A36PfyUgfaQGTEwpHdOPDuBCvM23EpAcuoAL+yGAhE/4UQKSQxfwmZ4KIKU0
37orAcmHlNK8XnYAn7P9VwKS1TLg8z0RQEppV2C27b8SkKyWAbNTSrv0ogNYYL2VgGTJgl4IYJ7tvxKQLJcB87oqgJTSeCpwyy8lIBVdBswqMtq1DuBc66wEJGvO7aYA/tz2XwlI1suAs7spgFNs/5WAZL0MOLUrAujkjQiVgBKQ7rEjtw7fkQ7gDEurBKQUnNkNAXzSuioBKQWnd1QAKaWxwEHWVQlIKTg4pTSmkx3AKdZUCUip+HgnBXCy9VQCUirmdlIAJ1lPJSCl4uROCmCG9VQCUipmdkQAKaVDraUSkPKRUjqsEx3AcZZSCUgpmdMJARyD5/8rASkbI0V22xbAUXj+vxKQslEDju6EAKZbSyUgpaTelgBSSvtZQyUg5SWltG87HYCH/5SAlJsZ7QjAQ4BKQMrN9HYEUMcjAEpAysrItvYDbEsA0/AIgBKQslIDprYjgCnWUAlIqZnSjgD2tH5KQErNhJYEkFLa3dopASk/KaU/bqUDOMDSKQGpBB9sRQCTrZsSkEowuRUBeBagEpBqsG8rApho3ZSAVIKJrQhgb+umBKT0jIyW5dEE4CFAJSDlpzZalkcTgIcBlYBUg91bEcBu1k0JSCVo6TyA91s3JSCVYNdWBLCLdVMCUgl2aUUA462bEpBKsHMrAtjJuikBqQQ7tSKAcdZNCUglGNuKAIasW7UlsNdeezE05M88AAwZcvkD5syZw1133WUhNMNW2Wx5qs3IyAjr16/njjvusBjVZnMrAviddRsMXnjhBSVQbX7figCGrZsSkEow3IoA3rRu
SkAqwRutCOB166YEpBK83ooANlo3JSCV4LVWBPA/1k0JSCV4tRUBbLBuSkAJVIINrQjgZesmSqD0jIyW5dEE8KK1EyVQemqjZXk0AayzdqIEKsG6VgTwvHUTJVAJ1rYigGetmyiBSrCmFQE8Y91ECVSCZ3ZYABHhYUBRAhUgIlo6DwBgveUTJVBqXhrtf25LAE9aP1ECpeapdgSwmuaJBCJKoHyMAI+3I4AGzRMJRJRA+agVGW5ZAKusoSiBUrOqHQE8ZP1ECZSah1oWQER4NqAogRITEWvb6QBcBogSKC+Nbf2D7RHAfXgkQJRA2RgBlndCAL/CIwGiBMpGrchu2wJYZi1FCZSSZW0LICIetY6iBMpHRKxqWwAFD1pOUQKl4oHt+UfbK4BbracogVJxWycFcLv1FCUwuAK4xXqKEigVt3RMABHxFvCENRUlUApWR8TmTnYAAEusqyiBUrDdWd0RAdxsXUUJlIJ/7bgAIuKX1lWUQP5ExNJudAAAP8frAkQJ5MoI8LMd+YMdFcBivC5AlECu1IqMdk0Ai6yxKIGsWdQ1AUTEJmCFywBRAlm2//dHxHA3OwCAhS4DRAlk2f4v3NE/akUAP7DWogSy5LquCyAifgvc6zJAlEBW7f+vimx2vQMAuMZlgCiBrNr/a1r5w5YEEBHXW3NRAvkQET/smQAKfuwyQJRAFu3/Ta3+cTsCuNJlgCiBLNr/K3sugIi4F1hnFyBKoK+z/9qIWN5zARRcYRcgSqCvs/+32nmDtgQQEVc5DEUJ9I92MzjUgW241mEoSqAv
fL/dN+iEAL7qEBQl0Bcu67sAIuI54Be4M1CUQK8YAW4pstf3DgDgS7gzUJRAr6gBX+zEG3VEABFxH80nkdgFiBLo/uy/MiJWZCOAggvsAkQJ9GT2v6BTb9YxAUTEnUDDLkCUQFdn/0Ynb9A71OENPN8uQJRAV2f/8zv5hh0VQHE74pV2AaIEujL7r9iRW373owMAmG8XIEqgK7P/vE6/accFEBEPA/9uFyBKoKOz/5KIWJW9AAoW2AWIEujo7L+gG2/cFQFExIvAdxxuogQ6wpUR8VJpBFBI4CJgk0sBUQJttf6bIuLibn3AUJe/wDyXAqIE2mr9z+vmB3RVABFxI3C/XYDkLoExY8bkOPvfFxGLu/khY3vwRc4EnsupssPDw45865B7J1ArstP1D+k6KaWvAF/Loqq1GiMjNiTWI/tafCUivl4JARQSeBrY330CItts/Z+JiA/14sOGevjF5hp+ke2alOf26sN6tuej0WhsqNfrm4GP+RuLjNr639xL2/SUlFIDmGY3IPIHrf9jEXFoLz90qA9fdE4Rfvc8ibwd/lqRDSotgIjYACQ7AJF3dOJnRcSrvf7gvpz90Gg0HqvX638CzPC3F+H6iPhmv8zTN1JK/w0cYDcgA9z6Px0RB/ZrA4b6XIAjtyiEyKCFf8sMDJ4AIuJl4KO4U1AGL/w14KMR8Uo/N6TvV0A0Go019Xr9NeBUx4UMCDXg4m5f6FMKARQSuKder08FDnNsyACwqJvX+O+oibIhpbQSOMLxIRXmgYiYlVMrQmYSeA7YB48MSPXW/Wsj
Yr+cNmoow0JNA97EnYJSrfC/CUzNbcOyE0BEbAQOAjYrAalI+EeAD0fEawpg+yTwPFDHw4NS/vDXgGkRsTbHDRzKtXIR8QQwUwlIycM/MyKezHUjh3KuYEQ8BMxWAlLS8B9djOFsKcWe9pTS0cDyLQorUobw35/7xpYmTCmlmcADSkBKEP4jIuLBMmxwqYKUUpoKNIrtVgKSW/g3A4dGxOqybHTpQpRS2gf4NTBeCUhG4X8DmFIcwSoNQ2WrdHE4ZW9greNOMgn/88DEsoW/lB3Au7oBrx2QfpPVuf2V7wDe1Q3MAhZtYWKRXs36ADeUOfyQyeXA7dBoNH66xf0EPEIgvQh/DbgoIi4p+5epTFhSSscCS6v2vSTLmf+4iLi7Cl+oUkFJKe0BrAA+5FiVLvAb4Mji1vYogHxFsBA4zyWBdLDlXxgR86v25SobjpTSp4GfKAHpQPjPioifVvELVjoYKaXdgWXAIY5laYEGMKcfT+xRAJ0VwZeAr9sNyA7M+l+OiH+q+pcdmDCklA4E/hP4oGNcRuFpYG5E/NcgfNmBmw1TSl8G/tFuQAZ11h9oARQS2Be4GThKERh84D7gU2U8l18BtCeCc4DrgfeZhYFkE/BXEXHToBbAma8pgiuAS+wGBmrW/1ZEXDroxXCwvy2BCcC1wBmKoNLBvxn4bESstyQO8q2JYDqwkOZjmxVBdYK/AjgvIh61JApge0QwB/gecKgiKHXwHwXOj4hllkQBtCKC44GrgFmKoFTBXwlcEBG/tCQKoBMiOBL4BvBxq5E1Pwe+GBEr
LYUC6IYI9gG+Bnz2XTOO9G+2B/g+8A+5PoJLAVRTBhcAFwP7KoK+BH8tcEVEXG1JFEA/RXA0cBFwjl1BT2b7xcC3y/DkHQUweDL4S+BvgI8og46G/m7gmoj4kWVRAGUQwc7AAmA+zXMKlMGOh34FcB1wbUS8aWkUQFllsBNwbrFE+IQVGZX/AG4CFkXEsOVQAFUUwnHAp4DTgakDXo7VwBLgXzxZRwEMogyGgFOAucDJNE84okJLhnd/h5XArcDtwC8iYrOjQAHIO6UwneZOxGOB2UB9G6HKMejQvKfecpo78O6JiFX+ugpAWpPCJGAGMJ3m9QlTgGnAhD5v2nrgceBJmufdrwIeioh1/moKQHojh/cDBwL70zwpaRLNJyjvAexevHYFdqF585Odildti9l7uHhtAl4HNgKvAhuAV4AXgXU0n4S7BvhNRGy0+iIiIiIiIiIiIiIiIjnyf9eV8VcbpfPFAAAAAElFTkSuQmCC");

define('REFERENCE_APPLICATION_ICON', "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAADkElEQVRo3u2ZT0hUQRzHP9sGgoEh0T8kL8/Ag+tBsUNdCpmKjA7VQdzKwg6pdIoOnkQKPHgUtUOGSrt0qIUORTGIXepgZAc9eJCFMgmUEj0IQotdlJ03b1779u2IK/k7vTfvN/P7zm9+8/v3YI/+d4oEZxUHaaaBCZJyw4cjQgvn+MwLuWIZgKijg9tEgTl6SJggiAhxuqkCMowwKKesARDPiSuvRgiK+C1KyBu2AOj7XWSaKJUcphRYY4nvZIhxxM0mI9sFICAFAbBvp2/BbgAg2sIuHmRmNOcigzwKvb0rztH0mwKMUJSQ4pLhwxTjTDLLAqtAGRVUc4pG6gy8b7kq10MBECW846w2uMYAY3LGZ0YNt+ikVBv+wEV/CP8C8Maz+z565XKOIyuni4e6FmRT3gDEIO2a2u/LTwGN7zT92nEMyY68jFC0aaaXpEl+C2p76XnnGVXElKEG50f6a04NaD4/S09ke4hLOMQ94wdXjIi4It4X44SkjIf0AwlajB/qs5FSdUTmU5qiNbQfaMUckjsMGhAH+WW0iDNBTc/HHD8ahjMc2kpZshpoNorvK0Q8yE/0GU2/2XsEDQbGNXoLjja9rBlGG7wAJpjzsA3kcjsBdLDMgGdwjgkvgCQ9HghjViLumEd8D0mzH7jGS9X+Zb2dmC++KH5xkQdqRunOB1KMK2/j1rIOdaVpd0LrAiA3XDdh0hoAdaWoO5/WM6JK5XnWGoBZHwkGAIeV5wVrABZ8JBgAqMnEqjUAqz4SijErVr1WmTUpZT4SDACWlOcKawAqfCQYAHxXnqutAaj2kaADEBEyyuspawDUlTIi4gNARIi78rhGawDUlWLEVQhRrb4/obAed16lFy1EghpXgnuAWn4702mPBlq09gLALSv711epojubK2YBxD3ioVOUF7z/cjo9g1Wc8wJ4bZhdSlfB++/ylGoAn4svKZUrjBjX6Bf7Q4vfT7/xw0i2jaf6gUEjcx2joRUwaizYXZIUpad/OiepNbDHnGO52gw+pdkdn9JsIGd1LNp4qhWnrfJPXsof1cqyu3I4j+o4/dU56qoUYlx2ZtLzgU0vxXmtPH+82xoURdCi2fEmlU+rJj/ybc0EBmC4EcHJx/LzBLDXrN5eChto3lOi/bBY58L2AUho7bvr8pXBUtzFPSSsHYG8QT3DmxnzHDdJGdlS3NxscWQYpj7IH6Mi+G23R3v0FwbfFx3mQ2ZaAAAAAElFTkSuQmCC");

/**
 * Liste des applications autoriées par défaut.
 * Ces applications ne peuvent être retiré de la liste des applications activées.
 *
 * @var array:string $bootstrapActiveApplicationsWhitelist
 */
$bootstrapActiveApplicationsWhitelist = array(
    '2121510000000000006e6562756c65206170706c69636174696f6e73000000000000212151',
);

// Constante de la première URL à utiliser après la création de l'entité instance du serveur.
define('BOOTSTRAP_FIRST_URL_TO_OPTION', '?a=2121510000000000006e6562756c65206170706c69636174696f6e73000000000000212151&view=applications&ent=');

// Constante du nom du fichier contenant le bootstrap.
define('BOOTSTRAP_FILE_NAME', 'index.php');

// ------------------------------------------------------------------------------------------

// First synchronization.
define('BOOTSTRAP_FIRST_LOCALISATIONS', array(
        'http://code.master.nebule.org',
        'http://puppetmaster.nebule.org',
        'http://security.master.nebule.org',)
);

// Public key of puppetmaster.
define('FIRST_PUPPETMASTER_PUBLIC_KEY',
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
-----END PUBLIC KEY-----');

/*
 * Constante du lien de hash de la clé publique puppetmaster.
 * TODO lien invalide
 */
define('FIRST_PUPPETMASTER_HASH_LINK',
    '77575698703bf581b582457f64e13b2f0f00dd4be95be356c6a24b277161fd5bf331e8990688422d3ff63ebe3ea774b89289765027c9f1bb2082e8ea9ab2ad9b40543eb828018ef1bb70090c67ee7a50dcce95c5a118c47bb76d8702da2335a9d02b47c67f06fec530dddc04f8f486de95d23fc72518cd9d4e7462a8ef731520405e2168283da7ef7207f9960f055270b25786192c60f0157274c2889b8f925b51b40d6a56c3f861c41c1cc6e3996d252bf2c8234bac6142f5582a87fa0bda25d6bbe161c29dfd0a461b69805a3b0306967fec7af6411f68eaf9ff630914ad29b62ddaaa9b3fd8890f656713841dd7a6412117999938c2218625ccf601fd25668fe157d522a64d2d6c4a0c794610af6b2e078284b4514758bf48bb4bdfdca519c8121c3c84045c626ef37a5e9e120954acd42f47cf82a648e989c23746e90bd3a8d6657506a8715a155fe1da124e14ac2db8af17af7e209a159accaf9da183f7b180c1eaf44aee24e6e43767efbf6253ac4ef534752a7389f0be2cd7425e7775ca7d45eb2fcb8d2fcedc0af49893f41936384fb379d4c02aa623dcd24f49798b780294cd60e9388c2bff053a407d1906dcc9cb0a492f0a54d062a1bb10da4f856b4d8af3d2fe4869117fd500a97923e1cb9b505262a48f6f5c72f9c49e82b27e6beefdb8a700740d136c73cacbba955a84ff5aee7bd720da9053bea128b06acf.sha256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea_2013-03-12T20:29:37+0100_l_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea_5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e_8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec'
);

/*
 * Constante du lien de type de la clé publique puppetmaster.
 * TODO lien invalide
 */
define('FIRST_PUPPETMASTER_TYPE_LINK',
    '3c091432e6404b9634783e2b51debd017f07918d7ef88af0e01638955859bdb2ad88df9be624308a3b0cb0904763dd06576510aedf47c94da1ce2fe11d5e324b0947b069be01f1d7626e69d93c4919182ebad607a7b4daf52ad4a68e59a9c514a7021ba4df05fe344544867b890a94007e4867021a417491dcf036a97342f56ff88f0889fe078a3e92349f3f78d74696aaf258175432d9540dff5c889525f693230472b7c5b1c16f31d7f8c3efd444e856f7473e0be7773ed93c3516d074c373433919cfb3773dd272d0236b5db52ef1f3b3eb6c0653eaadb34bbda66e2a10627397a669d35b663a490efbd50d404942ceadf54618a29ada345788ebf0c0258973aac6cefab86e6021eabb67acfad34e6a67b7001351a1179f90d2c98558fd2993be458f3095cb0940fc36c7e40fd04b76a750af48bfa5e5cd26423983580bbd99cfe3daadb928ee0203125fe97940878ad6a1faf385c37fda47769d06153680974d42d145bb9fa5e621d249cac83863a585d2448cd985ae31af7033ce322833d3689bf09033410441e07869ff4c85244d86dea47679ef5daaf726c11650da7858317196bf465e9f930846db9328b20aa0aa7a4280b7515bce8fae32585c3a75dcb88351dabfba013b4970094860904d8f161909cd1164e5506486aac5ad29afccd4740324f889dc936ef7fb85ef16ffd55c8f04be08fd442feb882b23b24b94.sha256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea_2013-03-12T22:28:06+0100_l_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea_970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864_5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0'
);

/**
 * Les premiers objets à créer pour un bon fonctionnement de la bibliothèque.
 *
 * @var array:string $nebuleFirstReservedObjects
 */
$nebuleFirstReservedObjects = array(
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

/*
 * Constante de taille du nom d'une nouvelle entité locale de serveur. FIXME
 */
define('FIRST_NAME_SIZE', 6);

/*
 * Constante de taille du mot de passe d'une nouvelle entité locale de serveur. FIXME
 */
define('FIRST_PASSWORD_SIZE', 14);

/*
 * Constante de temps d'attente entre deux pages lors de la première connexion. FIXME
 */
define('FIRST_RELOAD_DELAY', 3000);

// ------------------------------------------------------------------------------------------

// Préparation des variables des applications.
/**
 * Nom de l'application.
 */
$applicationName = '';

/**
 * Nom complet de l'application.
 */
$applicationSurname = '';

/**
 * Description somaire de l'utilité de l'application.
 */
$applicationDescription = '';

/**
 * Version de l'application.
 */
$applicationVersion = '';

/**
 * Niveau de stabilité et de pérénité de l'application.
 */
$applicationLevel = 'Deprecated';

/**
 * Licence de l'application.
 */
$applicationLicence = '';

/**
 * Auteur de l'application.
 */
$applicationAuthor = '';

/**
 * Site web de référence de l'application.
 */
$applicationWebsite = '';

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

define('NEBULE_LIBPP_VERSION', '020210424');
define('NEBULE_LIBPP_MODE_BOOTSTRAP', true);
define('NEBULE_LIBPP_LINK_VERSION', '2:0');
define('NEBULE_DEFAULT_PUPPETMASTER_ID', '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256');
define('NEBULE_ENVIRONMENT_FILE', 'nebule.env');
define('LOCAL_ENTITY_FILE', 'e');
define('LOCAL_LINKS_FOLDER', 'l');
define('LOCAL_OBJECTS_FOLDER', 'o');
define('NID_MIN_HASH_SIZE', 128);
define('NID_MAX_HASH_SIZE', 8192);
define('NID_MIN_ALGO_SIZE', 2);
define('NID_MAX_ALGO_SIZE', 12);

/**
 * List of options types.
 *
 * Supported types :
 * - string
 * - boolean
 * - integer
 */
define('LIST_OPTIONS_TYPE', array(
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
        'subordinationEntity' => 'string',)
);

/**
 * Default options values if not defined in option file.
 */
define('LIST_OPTIONS_DEFAULT_VALUE', array(
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
        'subordinationEntity' => '',)
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
 * ID cerberus maître de la sécurité.
 */
$nebuleSecurityMaster = '0';

/**
 * ID bachue maître du code.
 */
$nebuleCodeMaster = '0';

/**
 * ID asabiyya maître de l'annuaire.
 */
$nebuleDirectoryMaster = '0';

/**
 * ID kronos maître du temps.
 */
$nebuleTimeMaster = '0';

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
    global $nebuleSecurityMaster, $nebuleCodeMaster, $nebuleDirectoryMaster, $nebuleTimeMaster, $nebuleLocalAuthorities,
           $libppCheckOK;

    // Initialize i/o.
    if (!io_open())
        return false;

    // Pour la suite, seul le puppetmaster est enregirstré.
    // Une fois les autres entités trouvées, ajoute les autres autorités.
    // Cela empêche qu'une entié compromise ne génère un lien qui passerait avant le puppetmaster
    //   dans la recherche par référence nebFindByRef.
    if (!e_check(getConfiguration('puppetmaster')))
        return false;
    $nebuleLocalAuthorities[0] = getConfiguration('puppetmaster');

    // Recherche et vérifie le maître de la sécurité.
    $entity = nebFindByRef(
        o_getNID('nebule/objet/entite/maitre/securite'),
        'nebule/objet/entite/maitre/securite',
        true);
    if (!e_check($entity))
        return false;
    $nebuleSecurityMaster = $entity;

    // Recherche et vérifie le maître du code.
    $entity = nebFindByRef(
        o_getNID('nebule/objet/entite/maitre/code'),
        'nebule/objet/entite/maitre/code',
        true);
    if (!e_check($entity))
        return false;
    $nebuleCodeMaster = $entity;

    // Recherche et vérifie le maître de l'annuaire.
    $entity = nebFindByRef(
        o_getNID('nebule/objet/entite/maitre/annuaire'),
        'nebule/objet/entite/maitre/annuaire',
        true);
    if (!e_check($entity))
        return false;
    $nebuleDirectoryMaster = $entity;

    // Recherche et vérifie le maître du temps.
    $entity = nebFindByRef(
        o_getNID('nebule/objet/entite/maitre/temps'),
        'nebule/objet/entite/maitre/temps',
        true);
    if (!e_check($entity))
        return false;
    $nebuleTimeMaster = $entity;

    // A partir de là on peut ajouter les autres autorités.
    $nebuleLocalAuthorities[1] = $nebuleSecurityMaster;
    $nebuleLocalAuthorities[2] = $nebuleCodeMaster;

    libppSetServerEntity($nebuleCodeMaster, $nebuleLocalAuthorities);
    libppSetDefaultEntity($nebuleCodeMaster, $nebuleLocalAuthorities);
    libppSetPublicEntity();

    $libppCheckOK = true;
    return true;
}

/**
 * Get and check local server entity.
 * @param $nebuleCodeMaster
 * @param $nebuleLocalAuthorities
 */
function libppSetServerEntity(string $nebuleCodeMaster, array &$nebuleLocalAuthorities): void
{
    global $nebuleServerEntite;
    if (file_exists(LOCAL_ENTITY_FILE)
        && is_file(LOCAL_ENTITY_FILE)
    )
        $nebuleServerEntite = filter_var(strtok(trim(file_get_contents(LOCAL_ENTITY_FILE)), "\n"), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

    if (!e_check($nebuleServerEntite))
        $nebuleServerEntite = $nebuleCodeMaster;

    if (getConfiguration('permitInstanceEntityAsAuthority') && !getModeRescue())
        $nebuleLocalAuthorities[] = $nebuleServerEntite;
}

/**
 * Get and check default entity.
 */
function libppSetDefaultEntity(string $nebuleCodeMaster, array &$nebuleLocalAuthorities): void
{
    global $nebuleDefaultEntity;
    $nebuleDefaultEntity = getConfiguration('defaultCurrentEntity');
    if (!e_check($nebuleDefaultEntity))
        $nebuleDefaultEntity = $nebuleCodeMaster;

    if (getConfiguration('permitDefaultEntityAsAuthority') && !getModeRescue())
        $nebuleLocalAuthorities[4] = $nebuleDefaultEntity;
}

/**
 * Get and check public entity.
 */
function libppSetPublicEntity(): void
{
    global $nebulePublicEntity, $nebuleDefaultEntity;
    if (!e_check($nebulePublicEntity))
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
    if (!n_checkNID($nid) || !n_checkNID($rid))
        return $result;

    $links = array();
    l_findinclusive($nid, $links, 'f', $nid, '', $rid, false);
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
    o_getLocalContent($oid, $data);
    $data = strtok(filter_var($data, FILTER_SANITIZE_STRING), "\n");
    if (!is_string($data))
        return '';

    $data = trim($data);

    if (extension_loaded('mbstring'))
        $data = mb_convert_encoding($data, 'UTF-8');
    else
        addLog('fct="nebReadObjText1line:1" info="mbstring extension not installed or activated!"');

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
 * @param integer $maxsize
 * @return string
 */
function nebReadObjText(string &$oid, int $maxsize = 4096): string
{
    $data = '';
    o_getLocalContent($oid, $data);
    $data = filterPrinteableString(filter_var($data, FILTER_SANITIZE_STRING));

    if (strlen($data) > $maxsize) {
        $data = substr($data, 0, ($maxsize - 3)) . '...';
    }

    return $data;
}

/** FIXME
 * Lit les liens nettoyés d'un objet.
 *
 * @param string $object
 * @param array $table
 */
function nebReadObjLinks($object, &$table)
{
    l_listlinks($object, $table);
}

/** FIXME
 * Cherche le nom complet d'un objet, càd avec préfix et suffix.
 * Fonction avec utilisation du cache si possible.
 *
 * @param string $object
 * @return string
 */
function nebReadObjName(&$object)
{
    global $nebuleCacheReadObjName;

    if (isset($nebuleCacheReadObjName [$object]))
        return $nebuleCacheReadObjName [$object];

    nebINECreatObjText('nebule/objet/nom');
    nebINECreatObjText('nebule/objet/prenom');
    nebINECreatObjText('nebule/objet/surnom');
    nebINECreatObjText('nebule/objet/prefix'); // @todo
    nebINECreatObjText('nebule/objet/suffix');

    // Recherche du nom.
    $type = nebFindObjType($object, 'nebule/objet/nom');
    $nom = '';
    if (io_testObjectPresent($type)) {
        $nom = nebReadObjText1line($type, 128);
    }
    if ($nom == '') {
        $nom = $object;
    }

    // Recherche du prénom.
    $type = nebFindObjType($object, 'nebule/objet/prenom');
    $prenom = '';
    if (io_testObjectPresent($type)) {
        $prenom = nebReadObjText1line($type, 128);
    }
    if ($prenom != '') {
        $nom = "$prenom $nom";
    }

    // Recherche du surnom.
    $type = nebFindObjType($object, 'nebule/objet/surnom');
    $surnom = '';
    if (io_testObjectPresent($type)) {
        $surnom = nebReadObjText1line($type, 128);
    }
    if ($surnom != '') {
        $nom = "$nom $surnom";
    }

    // Recherche du suffix.
    $type = nebFindObjType($object, 'nebule/objet/suffix');
    $suffix = '';
    if (io_testObjectPresent($type)) {
        $suffix = nebReadObjText1line($type, 128);
    }
    if ($suffix != '') {
        $nom = "$nom.$suffix";
    }

    unset($type, $prenom, $surnom, $suffix);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjName [$object] = $nom;

    return $nom;
}

// FIXME
function nebReadObjSize(&$object)
{
    // Cherche la taille d'un objet.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadObjSize;

    if (isset($nebuleCacheReadObjSize [$object]))
        return $nebuleCacheReadObjSize [$object];

    nebINECreatObjText('nebule/objet/taille');
    $type = nebFindObjType($object, 'nebule/objet/taille'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    if ($text == '') {
        $text = '-indéfini-';
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjSize [$object] = $text;

    return $text;
}

// FIXME
function nebReadEntityType(&$object)
{
    // Cherche le type d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityType;

    if (isset($nebuleCacheReadEntityType [$object]))
        return $nebuleCacheReadEntityType [$object];

    nebINECreatObjText('nebule/objet/entite/type');
    $type = nebFindObjType($object, 'nebule/objet/entite/type'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    if ($text == '') {
        $text = '-indéterminé-';
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityType [$object] = $text;

    return $text;
}

// FIXME
function nebReadEntityLoc(&$object)
{
    // Cherche la localisation d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityLoc;

    if (isset($nebuleCacheReadEntityLoc [$object]))
        return $nebuleCacheReadEntityLoc [$object];

    nebINECreatObjText('nebule/objet/entite/localisation');
    $type = nebFindObjType($object, 'nebule/objet/entite/localisation'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
        $text = nebReadObjText1line($type, 128);
    }
    if ($text == '') {
        $text = '-indéterminé-';
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityLoc [$object] = $text;

    return $text;
}

// FIXME
function nebReadEntityFName(&$entite)
{
    // Cherche le prénom d'une entite.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheReadEntityFName;

    if (isset($nebuleCacheReadEntityFName [$entite]))
        return $nebuleCacheReadEntityFName [$entite];

    nebINECreatObjText('nebule/objet/prenom');
    $type = nebFindObjType($entite, 'nebule/objet/prenom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
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

    nebINECreatObjText('nebule/objet/nom');
    $type = nebFindObjType($entite, 'nebule/objet/nom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
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

    nebINECreatObjText('nebule/objet/postnom');
    $type = nebFindObjType($entite, 'nebule/objet/postnom'); // L'objet doit etre present et doit etre de type text/plain.
    $text = '';
    if (io_testObjectPresent($type)) {
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
    $hashtype = o_getNID($type);
    $objdst = '';
    l_find($object, $table, 'l', $object, '', $hashtype);
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
    nebINECreatObjText('nebule/objet/type');
    $hashtype = o_getNID('nebule/objet/type'); // 5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0
    $type = '';
    l_find($object, $table, 'l', $object, '', $hashtype);
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
    if (io_testObjectPresent($type)) {
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

// FIXME
function nebReadObjTypeHash(&$object)
{ // Cherche le type de hash d'un objet.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleCacheReadObjTypeHash;

    if (isset($nebuleCacheReadObjTypeHash [$object]))
        return $nebuleCacheReadObjTypeHash [$object];

    if ($object == '' || $object == '0')
        return '-indéfini-'; // L'objet doit etre present.

    $table = array();
    nebINECreatObjText('nebule/objet/hash');
    $hashtype = o_getNID('nebule/objet/hash'); // 8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec
    $type = '';
    l_find($object, $table, 'l', $object, '', $hashtype);
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
    if (io_testObjectPresent($type)) {
        $text = nebReadObjText1line($type, 32);
    }
    if ($text == '') {
        $text = '-indéfini-';
    }
    unset($type);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjTypeHash [$object] = $text;

    return $text;
}

// FIXME
function nebIsText(&$object)
{ // Vérifie si l'objet est marqué comme un texte.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleCacheIsText;

    if (isset($nebuleCacheIsText [$object]))
        return $nebuleCacheIsText [$object];

    if ($object == '0')
        return false;

    $ok = false;
    $table = array();
    $hashtype = o_getNID('text/plain');
    $hashmeta = o_getNID('nebule/objet/type');
    l_find($object, $table, 'l', $object, $hashtype, $hashmeta);
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'l') && ($link [5] == $object) && ($link [6] == $hashtype) && ($link [7] == $hashmeta)) {
            $ok = true;
            break 1;
        }
        if (($link [4] == 'l') && ($link [5] == $object) && ($link [6] == $hashtype) && ($link [7] == $hashmeta))
            $ok = true;
    }
    unset($table);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsText [$object] = $ok;

    return $ok;
}

// FIXME
function nebAddObjTypeMime(&$object, $type)
{ // Ajoute le type mime d'un objet.
    if ($object == '' || $object == '0')
        return false;
    if ($type == '')
        return false;
    $hashtype = o_getNID($type);
    nebINECreatObjText('nebule/objet/type');
    nebINECreatObjText('text/plain');
    $newlink = l_generate('-', 'l', $object, $hashtype, (o_getNID('nebule/objet/type'))); // 5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    if (!io_testObjectPresent($type) && !io_testLinkPresent($type))
        o_generate($type, 'text/plain');
    unset($hashtype);
    unset($newlink);
    return true;
}

// FIXME
function nebIsBanned(&$object)
{ // Vérifie si l'objet est marqué comme banni.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleSecurityMaster, $nebuleCacheIsBanned;

    if (isset($nebuleCacheIsBanned [$object]))
        return $nebuleCacheIsBanned [$object];

    if ($object == '0')
        return false;

    $ok = false;
    $table = array();
    $hashtype = o_getNID('nebule/danger'); // ac2323f77d7ee9f3ae841e8ccd8374397038160ec7cdb2fc86610c0f66eeeedb
    // _neblibpp_l_lsx($object, $table, 'f', $hashtype);
    l_find($object, $table, 'f', $hashtype, $object, '0');
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $object) && ($link [7] == '0'))
            $ok = true;
        if (($link [2] == $nebuleSecurityMaster) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $object) && ($link [7] == '0'))
            $ok = true;
    }
    unset($table);
    unset($hashtype);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsBanned [$object] = $ok;

    return $ok;
}

// FIXME
function nebIsSuppr(&$object)
{ // Vérifie si l'objet est marqué comme supprimé.
    // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity, $nebuleCacheIsSuppr;

    if (isset($nebuleCacheIsSuppr [$object]))
        return $nebuleCacheIsSuppr [$object];

    if ($object == '0')
        return false;
    $ok = false;
    $table = array();
    l_find($object, $table, 'd', $object, '0', '0');
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'd') && ($link [5] == $object) && ($link [6] == '0') && ($link [7] == '0')) {
            $ok = true;
            break 1;
        }
    }
    unset($table);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsSuppr [$object] = $ok;

    return $ok;
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
        || !n_checkNID($entite)
        || !io_testLinkPresent($entite)
    ) {
        return false;
    }
    nebINECreatObjText('application/x-pem-file'); // 970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864
    if (nebReadObjTypeMime($entite) != 'application/x-pem-file') {
        return false;
    }
    $line = nebReadObjText($entite, 10000);

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

    nebINECreatObjText('application/x-pem-file'); // 970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864
    if ((nebReadObjTypeMime($entite)) != 'application/x-pem-file')
        return false;
    $line = nebReadObjText($entite, 10000);

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
function nebIsEncrypt(&$object)
{ // Vérifie si l'objet est marqué comme protégé.
    // Fonction avec utilisation du cache si possible.
    global $nebuleCacheIsEncrypt;

    if (isset($nebuleCacheIsEncrypt [$object]))
        return $nebuleCacheIsEncrypt [$object];

    $table = array();
    $encrypt = false;
    l_find($object, $table, 'k', $object, '', '');
    foreach ($table as $itemtable) {
        if (($itemtable [4] == 'k') && ($itemtable [5] == $object)) {
            $encrypt = true;
            break 1;
        }
    }
    l_find($object, $table, 'k', '', $object, '');
    foreach ($table as $itemtable) {
        if (($itemtable [4] == 'k') && ($itemtable [6] == $object)) {
            $encrypt = true;
            break 1;
        }
    }
    unset($table);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsEncrypt [$object] = $encrypt;

    return $encrypt;
}

// FIXME
function nebFindEncryptFor(&$listdest, &$object)
{ // Cherche l'entite destinataire.

    $table = array();
    $i = 0;
    l_listlinks($object, $table, 'k');
    foreach ($table as $itemtable) {
        if (($itemtable [4] == 'k') && ($itemtable [5] == $object)) {
            $listdest [$i] = $itemtable [7];
            $i++;
        }
        if (($itemtable [4] == 'k') && ($itemtable [6] == $object)) {
            $listdest [$i] = $itemtable [7];
            $i++;
        }
    }
    unset($table);
    unset($i);
}

// FIXME
function nebFindPrivKey()
{ // Fonction avec utilisation du cache si possible.
    global $nebulePublicEntity;

    $table = array();
    l_find($nebulePublicEntity, $table, 'f', '', $nebulePublicEntity, '0');
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

    $privcert = nebReadObjText($nebulePrivateEntite, 10000);
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
    l_listlinks($object, $links, '-', '', true);
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

// FIXME
function nebCreatObjText(&$text)
{ // Création d'un nouvel objet texte.
    if (!getConfiguration('permitWriteObject'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    if (strlen($text) == 0)
        return false;
    $object = o_getNID($text);
    if (nebIsBanned($object))
        return false;
    o_generate($text, 'text/plain');
    unset($object);
    return true;
}

// FIXME
function nebINECreatObjText($text)
{ // If Not Exist - Création d'un nouvel objet texte.
    if (!getConfiguration('permitWriteObject'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    if (strlen($text) == 0)
        return false;
    $object = o_getNID($text);
    if (io_testObjectPresent($object))
        return true;
    if (nebIsBanned($object))
        return false;
    o_generate($text, 'text/plain');
    unset($object);
    return true;
}

// FIXME
function nebCreatObjDate(&$object)
{ // Création d'une date pour un objet.
    if (!getConfiguration('permitWriteLink'))
        return false;
    if ($object == '')
        return false;
    // Prépare certaines valeurs si non présentes.
    nebINECreatObjText('text/plain');
    nebINECreatObjText('nebule/objet/date');
    nebINECreatObjText('nebule/objet/date/annee');
    nebINECreatObjText('nebule/objet/date/mois');
    nebINECreatObjText('nebule/objet/date/jour');
    nebINECreatObjText('nebule/objet/date/heure');
    nebINECreatObjText('nebule/objet/date/minute');
    nebINECreatObjText('nebule/objet/date/seconde');
    nebINECreatObjText('nebule/objet/date/zone');
    // Ajoute la date longue, format ISO8601.
    $crfulldate = date(DATE_ATOM);
    $hashfulldate = o_getNID($crfulldate);
    o_generate($crfulldate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashfulldate, (o_getNID('nebule/objet/date'))); // 31e415a2fb3a47fd1ccd9de4e04d6d71c1386bef639ae38755dd218db9ed92a1
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute l'année.
    $crdate = date('Y');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/annee')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute le mois.
    $crdate = date('m');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/mois')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute le jour.
    $crdate = date('d');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/jour')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute l'heure.
    $crdate = date('H');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/heure')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute la minute.
    $crdate = date('i');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/minute')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute la seconde.
    $crdate = date('s');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/seconde')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Ajoute la zone.
    $crdate = date('P');
    $hashdate = o_getNID($crdate);
    o_generate($crdate, 'text/plain');
    $newlink = l_generate($crfulldate, 'l', $object, $hashdate, (o_getNID('nebule/objet/date/zone')));
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    unset($crfulldate);
    unset($crdate);
    unset($hashfulldate);
    unset($hashdate);
    unset($newlink);
    return true;
}

// FIXME
function nebCreatObjHash(&$object)
{ // Définition du hash pour un objet.
    if (!getConfiguration('permitWriteLink'))
        return false;
    if ($object == '')
        return false;
    nebINECreatObjText('nebule/objet/hash');
    nebINECreatObjText(getConfiguration('cryptoHashAlgorithm'));
    $newlink = l_generate('-', 'l', $object, getConfiguration('cryptoHashAlgorithm'), (o_getNID('nebule/objet/hash'))); // 8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    unset($newlink);
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
function m_add(string $type): void
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
function m_get(string $type): string
{
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;

    $return = '';
    switch ($type)
    {
        case 'lr':
            $return = $nebuleMetrologyLinkRead;
            break;
        case 'lv':
            $return = $nebuleMetrologyLinkVerify;
            break;
        case 'or':
            $return = $nebuleMetrologyObjectRead;
            break;
        case 'ov':
            $return = $nebuleMetrologyObjectVerify;
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
function e_generate($asymetricAlgo, $hashAlgo, &$hashpubkey, &$hashprivkey, $password = '')
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
    $hashpubkey = o_getNID($pubkey);
    o_writecontent($hashpubkey, $pubkey);
    // Extraction de la clé privée.
    if ($password != '') {
        openssl_pkey_export($newpkey, $privkey, $password);
    } else {
        openssl_pkey_export($newpkey, $privkey);
    }
    $hashprivkey = o_getNID($privkey);
    o_writecontent($hashprivkey, $privkey);
    $private_key = openssl_pkey_get_private($privkey, $password);
    if ($private_key === false) {
        return false;
    }
    // Calcul de hashs communs.
    $date = date(DATE_ATOM);
    $binary_signature = '';
    $refhashhash = o_getNID('nebule/objet/hash');
    $refhashalgo = o_getNID(getConfiguration('cryptoHashAlgorithm'));
    $refhashtype = o_getNID('nebule/objet/type');
    $refhashpem = o_getNID('application/x-pem-file');
    $refhashtext = o_getNID('text/plain');
    // Création des objets annexes.
    if (!io_testObjectPresent($refhashhash)) {
        $newtxt = 'nebule/objet/hash';
        o_writecontent($refhashhash, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashhash . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashhash . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_testObjectPresent($refhashalgo)) {
        $cryptoHashAlgorithm = getConfiguration('cryptoHashAlgorithm');
        o_writecontent($refhashalgo, $cryptoHashAlgorithm);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashalgo . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashalgo . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_testObjectPresent($refhashtype)) {
        $newtxt = 'nebule/objet/type';
        o_writecontent($refhashtype, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtype . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtype . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_testObjectPresent($refhashpem)) {
        $newtxt = 'application/x-pem-file';
        o_writecontent($refhashpem, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashpem . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashpem . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    if (!io_testObjectPresent($refhashtext)) {
        $newtxt = 'text/plain';
        o_writecontent($refhashtext, $newtxt);
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtext . '_' . $refhashalgo . '_' . $refhashhash;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
        $data = '_' . $hashpubkey . '_' . $date . '_l_' . $refhashtext . '_' . $refhashtext . '_' . $refhashtype;
        $hashdata = o_getNID($data);
        $binhash = pack("H*", $hashdata);
        openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
        $hexsign = bin2hex($binary_signature);
        l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    }
    // Génération du lien de hash de la clé publique.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashpubkey . '_' . $refhashalgo . '_' . $refhashhash;
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok1 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de hash de la clé privée.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashprivkey . '_' . $refhashalgo . '_' . $refhashhash;
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok2 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de typemime de la clé publique.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashpubkey . '_' . $refhashpem . '_' . $refhashtype;
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok3 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de typemime de la clé privée.
    $data = '_' . $hashpubkey . '_' . $date . '_l_' . $hashprivkey . '_' . $refhashpem . '_' . $refhashtype;
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok4 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
    // Génération du lien de jumelage des clés.
    $data = '_' . $hashpubkey . '_' . $date . '_f_' . $hashprivkey . '_' . $hashpubkey . '_0';
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok5 = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    $hexsign = bin2hex($binary_signature);
    l_writecontent("$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data");
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
function e_check(string $nid): bool
{
    if (!n_checkNID($nid, false)
        || $nid == '0'
        || strlen($nid) < NID_MIN_HASH_SIZE
        || !io_testObjectPresent($nid)
        || !io_testLinkPresent($nid)
        || !o_checkContent($nid)
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
function e_addpasswd($pubkey, $privkey, $password)
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
    if (!io_testObjectPresent($pubkey))
        return false;
    if (!io_testObjectPresent($privkey))
        return false;
    // Vérifie que le mot de passe est valide.
    $privcert = nebReadObjText($privkey, 10000);
    $ok = openssl_pkey_get_private($privcert, $password);
    if ($ok === false)
        return false;
    unset($privcert);
    // Génère une clé de session.
    $key = openssl_random_pseudo_bytes(NID_MIN_HASH_SIZE, $true);
    $hashkey = o_getNID($key);
    // Génère un IV à zéro.
    $hiv = '00000000000000000000000000000000';
    $iv = pack("H*", $hiv); // A modifier pour des blocs de tailles différentes.
    // Chiffrement de l'objet.
    $cryptobj = openssl_encrypt($password, getConfiguration('cryptoSymetricAlgorithm'), $key, OPENSSL_RAW_DATA, $iv);
    $hashpwd = o_getNID($password);
    $hashcryptobj = o_getNID($cryptobj);
    o_generate($cryptobj, 'application/x-encrypted/' . getConfiguration('cryptoSymetricAlgorithm'));
    // Chiffrement de la clé de session.
    $cryptkey = '';
    o_checkContent($pubkey);
    $cert = io_objectRead($pubkey);
    $ok = openssl_public_encrypt($key, $cryptkey, $cert, OPENSSL_PKCS1_PADDING);
    if (!$ok)
        return false;
    $hashcryptkey = o_getNID($cryptkey);
    $algoName = substr(getConfiguration('cryptoAsymetricAlgorithm'), 0, strpos(getConfiguration('cryptoAsymetricAlgorithm'), '.') - 1);
    o_generate($cryptkey, 'application/x-encrypted/' . $algoName);
    // Génère le lien de chiffrement entre clé privée et publique avec le mot de passe.
    $newlink = l_generate('-', 'k', $privkey, $pubkey, $hashpwd);
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Génère le lien de chiffrement symétrique.
    $newlink = l_generate('-', 'k', $hashpwd, $hashcryptobj, $hashkey);
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Génère le lien de chiffrement asymétrique.
    $newlink = l_generate('-', 'k', $hashkey, $hashcryptkey, $nebulePublicEntity);
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Suppression de la clé de session.
    $newlink = l_generate('-', 'd', $hashkey, '0', '0');
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    // Suppression de l'objet source.
    $newlink = l_generate('-', 'd', $hashpwd, '0', '0');
    if ((l_verify($newlink)) == 1)
        l_writecontent($newlink);
    return true;
}

/** FIXME
 * Entity -
 *
 * @param $entity
 * @param $password
 * @return bool
 */
function e_changepasswd($entity, $password): bool
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
function o_generate(&$data, $typemime = '')
{ // Crée un nouvel objet.
    if (strlen($data) == 0)
        return;
    if (!getConfiguration('permitWrite'))
        return;
    if (!getConfiguration('permitWriteObject'))
        return;
    $dat = date(DATE_ATOM);
    $hash = o_getNID($data);
    // Ecrit l'objet.
    if (!io_testObjectPresent($hash))
        o_writecontent($hash, $data);
    // Ecrit le lien de hash.
    $lnk = l_generate($dat, 'l', $hash, (o_getNID(getConfiguration('cryptoHashAlgorithm'))), (o_getNID('nebule/objet/hash'))); // 8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec
    if ((l_verify($lnk)) == 1)
        l_writecontent($lnk);
    // Ecrit le lien de type mime.
    if ($typemime != '') {
        $lnk = l_generate($dat, 'l', $hash, (o_getNID($typemime)), (o_getNID('nebule/objet/type'))); // 5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0
        if ((l_verify($lnk)) == 1)
            l_writecontent($lnk);
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
 * @return boolean
 */
function o_getLocalContent(&$nid, &$data): bool
{
    if (io_testObjectPresent($nid) && o_checkContent($nid)) {
        $data = io_objectRead($nid);
        return true;
    }
    return false;
}

/** FIXME
 * Object - Télécharge l'objet sur une localisation précise (site web).
 *  - $object l'objet à télécharger.
 *  - $localisation le site web sur lequel aller télécharger l'objet.
 *
 * @param string $nid
 * @param string $localisation
 * @return void
 */
function o_downloadContent(string $nid, string $localisation): void
{
    global $nebulePublicEntity, $nebuleSecurityMaster;

    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
        || !getConfiguration('permitSynchronizeObject')
        || !n_checkNID($nid)
        || $localisation == '0'
        || $localisation == ''
        || !is_string($localisation)
        || io_testObjectPresent($nid)
    )
        return;

    // Recherche si banni. TODO à sortir !
    $table = array();
    $hashtype = o_getNID('nebule/danger'); // ac2323f77d7ee9f3ae841e8ccd8374397038160ec7cdb2fc86610c0f66eeeedb
    l_listlinks($nid, $table, '-', $hashtype);
    foreach ($table as $link) {
        if ($link [2] == $nebulePublicEntity
            && $link [4] == 'f'
            && $link [5] == $hashtype
            && $link [6] == $nid
            && $link [7] == '0'
        ) {
            addLog('fct="o_downloadContent:1" warn="' . $nid . ') banned by ' . $nebulePublicEntity . '"');
            return;
        }
        if ($link [2] == $nebuleSecurityMaster
            && $link [4] == 'f'
            && $link [5] == $hashtype
            && $link [6] == $nid
            && $link [7] == '0'
        ) {
            addLog('fct="o_downloadContent:2" warn="' . $nid . ') banned by ' . $nebuleSecurityMaster . '"');
            return;
        }
    }

    // Téléchargement de l'objet.
    $hexid = getPseudoRandom(8); // id pour fichier temporaire.
    $id = bin2hex($hexid);
    $idname = '_neblibpp_o_dl1_' . $id . '-' . $nid;
    $distobj = fopen($localisation . '/o/' . $nid, 'r');
    if ($distobj) {
        $localobj = fopen(LOCAL_OBJECTS_FOLDER . '/' . $idname, 'w'); // @todo refaire via les i/o.
        if ($localobj) {
            while (($line = fgets($distobj, getConfiguration('ioReadMaxData'))) !== false) {
                fputs($localobj, $line);
            }
            fclose($localobj);
            $algo = substr($nid, strpos($nid, '.') + 1);
            $hash = getFileHash($idname, $algo);

            if ($hash . '.' . $algo == $nid)
                rename(LOCAL_OBJECTS_FOLDER . '/' . $idname, LOCAL_OBJECTS_FOLDER . '/' . $nid);
            else
                unlink(LOCAL_OBJECTS_FOLDER . '/' . $idname);
        }
        fclose($distobj);
    }
}

/** FIXME
 * Object - Vérifie la consistance d'un objet. Si l'objet est corrompu, il est supprimé.
 *
 * @param string $nid
 * @return boolean
 * @todo refaire avec i/o
 *
 */
function o_checkContent(&$nid)
{
    global $nebuleCachelibpp_o_vr;

    if (!n_checkNID($nid))
        return false;

    if (!io_testObjectPresent($nid))
        return true;

    // Si c'est l'objet 0, le supprime.
    if ($nid == '0') {
        if (io_testObjectPresent($nid)) {
            io_objectDelete($nid);
        }
        return true;
    }

    if (isset($nebuleCachelibpp_o_vr[$nid]))
        return true;

    m_add('ov');

    $algo = substr($nid, strpos($nid, '.') + 1);
    $hash = getFileHash($nid, $algo);

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

/** FIXME
 * Object - Calculate NID with hash algo.
 *
 * @param string $data
 * @param string $algo
 * @return string
 */
function o_getNID(string $data, string $algo = ''): string
{
    if ($algo == '')
        $algo = getConfiguration('cryptoHashAlgorithm');

    return getDataHash($data, $algo) . '.' . $algo;
}

/**
 * Object - Verify name structure of the node : hash.algo.size
 *
 * @param string $nid
 * @param boolean $permitnull
 * @return boolean
 */
function n_checkNID(string &$nid, bool $permitnull = false): bool
{
    // May be null in some case.
    if ($permitnull && $nid == '')
        return true;

    // Check hash value.
    $hash = strtok($nid, '.');
    if (strlen($hash) < NID_MIN_HASH_SIZE) return false;
    if (strlen($hash) > NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($hash)) return false;

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
    if (strlen($hash) != (int)$size) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    return true;
}

/** FIXME
 * Object - Ecrit le contenu d'un objet.
 *
 * @param string $object
 * @param string $data
 * @return bool
 */
function o_writecontent(string $object, string &$data): bool
{
    if ($object == '0'
        || strlen($data) == 0
        || !getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
    ) {
        return false;
    }

    if (io_testObjectPresent($object)) {
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
function o_delete(string &$object): bool
{
    global $nebulePublicEntity;

    if (!getConfiguration('permitWrite'))
        return false;
    if (!getConfiguration('permitWriteObject'))
        return false;
    $ok = true;
    $links = array();
    l_listlinks($object, $links);
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

/** FIXME
 * Link - Generate a new link TODO à refaire !
 *
 * @param string $CHR
 * @param string $REQ
 * @param string $NID1
 * @param string $NID2
 * @param string $NID3
 * @return string
 */
function l_generate(string $CHR, string $REQ, string $NID1, string $NID2 = '', string $NID3 = '')
{
    global $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite;

    if ($nebulePublicEntity == '')
        return '';
    if ($nebulePrivateEntite == '')
        return '';
    if ($nebulePasswordEntite == '')
        return '';
    if (!io_testObjectPresent($nebulePrivateEntite))
        return '';
    if ($REQ == '')
        return '';
    if (!n_checkNID($NID1))
        return '';
    if (!n_checkNID($NID2, true))
        return '';
    if (!n_checkNID($NID3, true))
        return '';
    if ($CHR == '-')
        $CHR = date(DATE_ATOM);

    $privcert = (nebReadObjText($nebulePrivateEntite, 10000)); // WARNING A modifier pour ne pas appeler une fonction de haut niveau...
    $private_key = openssl_pkey_get_private($privcert, $nebulePasswordEntite);
    if ($private_key === false) {
        return '';
    }
    $data = '_' . $nebulePublicEntity . '_' . $CHR . '_' . $REQ . '_' . $NID1 . '_' . $NID2 . '_' . $NID3;
    $binary_signature = '';
    $hashdata = o_getNID($data);
    $binhash = pack("H*", $hashdata);
    $ok = openssl_private_encrypt($binhash, $binary_signature, $private_key, OPENSSL_PKCS1_PADDING);
    openssl_free_key($private_key);
    unset($private_key);
    unset($hashdata);
    unset($binhash);
    if ($ok === false)
        return '';
    $hexsign = bin2hex($binary_signature);

    return "$hexsign." . getConfiguration('cryptoHashAlgorithm') . "$data";
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
 * @param string $object
 * @param array:string $visited
 * @param boolean $present
 * @param boolean $synchro
 * @param boolean $restrict
 * @return string
 */
function l_graphresolvone(&$object, &$visited, $present = true, $synchro = false, $restrict = false)
{
    global $nebuleLocalAuthorities;

    $visited [$object] = true;
    if (count($visited) > getConfiguration('maxFollowedUpdates')) {
        return '0'; // Anti trou noir.
    }
    $links = array();
    l_find($object, $links, 'u', $object, '', ''); // Liste les liens de mise à jour de l'objet.
    $links = array_reverse($links); // Inverse le résultat pour avoir les liens les plus récents en premier.

    // Recherche de nouveaux liens.
    if ($synchro
        && getConfiguration('permitSynchronizeLink')
        && (getConfiguration('permitSynchronizeObject')
            || !$present
        )
    ) {
        l_downloadlinkanywhere($object);
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
            || io_testObjectPresent($object)
        ) {
            return $object;
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
        if ($synchro
            && getConfiguration('permitSynchronizeObject')
        ) {
            o_downloadContent($object); // Syncho de l'objet.
        }
        if (!$present
            || io_testObjectPresent($object)
        ) {
            return $object;
        } else {
            return '0';
        }
    }

    // Parcours les branches.
    foreach ($valinks as $link) {
        $res = l_graphresolvone($link [6], $visited, $present);
        if ($res != '0') {
            return $res;
        }
    }

    if (!$present
        || io_testObjectPresent($object)
    ) {
        return $object;
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
 * @param string $object
 * @param boolean $present
 * @param boolean $synchro
 * @param boolean $restrict
 * @return string
 */
function l_graphresolv($object, $present = true, $synchro = false, $restrict = false)
{
    global $nebule_permitautosync, $nebuleCachelibpp_l_grx;

    // Lit au besoin le cache.
    if (!$restrict && isset($nebuleCachelibpp_l_grx[$object][$present]))
        return $nebuleCachelibpp_l_grx[$object][$present];

    // Active la synchronisation automatique au besoin.
    if ($nebule_permitautosync)
        $synchro = true;

    $visited = array();
    $res = l_graphresolvone($object, $visited, $present, $synchro, $restrict);
    unset($visited);
    if ($res == '0')
        $res = $object;

    if (getConfiguration('permitBufferIO') && !$restrict)
        $nebuleCachelibpp_l_grx[$object][$present] = $res;

    return $res;
}

/** FIXME
 * Link -
 *
 * @param $object
 * @param $table
 * @param $action
 * @param $srcobj
 * @param $dstobj
 * @param $metobj
 * @param false $withinvalid
 */
function l_find($object, &$table, $action, $srcobj, $dstobj, $metobj, $withinvalid = false)
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

    $linkdate = array();
    $tmptable = array();
    $i1 = 0;
    l_listonelink($object, $tmptable, $action, $metobj, $withinvalid);
    foreach ($tmptable as $n => $t) {
        $linkdate [$n] = $t [3];
    }
    array_multisort($linkdate, SORT_STRING, SORT_ASC, $tmptable); // Tri par date.
    foreach ($tmptable as $tline) {
        if ($tline [4] == 'x')
            continue 1; // Suppression de l'affichage des liens x.
        if ($action != '' && $tline [4] != $action)
            continue 1;
        if ($srcobj != '' && $tline [5] != $srcobj)
            continue 1;
        if ($dstobj != '' && $tline [6] != $dstobj)
            continue 1;
        if ($metobj != '' && $tline [7] != $metobj)
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
        $i1++;
    }
    unset($linkdate);
    unset($i1);
    unset($n);
    unset($t);
    unset($tline);
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
 * @param string $object
 * @param array $table
 * @param string $action
 * @param string $srcobj
 * @param string $dstobj
 * @param string $metobj
 * @param boolean $withinvalid
 * @return null
 */
function l_findinclusive($object, &$table, $action, $srcobj, $dstobj, $metobj, $withinvalid = false): void
{
    $followXOnSameDate = true; // TODO à supprimer.

    $linkdate = array();
    $tmptable = array();
    $i1 = 0;

    l_listonelink($object, $tmptable, $action, $metobj, $withinvalid);

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
        $i1++;
    }
    unset($linkdate, $i1, $n, $t, $tline);

    return;
}

/** FIXME
 * Link -
 *
 * @param $object
 * @param $table
 * @param string $filtreact
 * @param string $filtreobj
 * @param false $withinvalid
 */
function l_listlinks($object, &$table, $filtreact = '-', $filtreobj = '', $withinvalid = false)
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
    l_listonelink($object, $tmptable, $filtreact, $filtreobj, $withinvalid);
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
        foreach ($table as $vline) // Suppression de l'affichage des liens en double, même à des dates différentes.
        {
            if (($tline [2] == $vline [2]) && ($tline [4] == $vline [4]) && ($tline [5] == $vline [5]) && ($tline [6] == $vline [6]) && ($tline [7] == $vline [7]))
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
 * @param $object
 * @param $table
 * @param string $filtreact
 * @param string $filtreobj
 * @param false $withinvalid
 */
function l_listonelink(&$object, &$table, $filtreact = '-', $filtreobj = '', $withinvalid = false)
{ // Lit tous les liens d'un objet.
    // - $object objet dont les liens sont à lire.
    // - $table table dans laquelle seront retournés les liens.
    // - $filtreact filtre optionnel sur l'action.
    // - $filtreobj filtre optionnel sur un objet source, destination ou meta.
    // - $withinvalid optionnel pour autoriser la lecture des liens invalides.

    $checkSignOnList = getConfiguration('permitCheckSignOnList');

    if ($object == '0')
        return;
    if (!io_testLinkPresent($object))
        return;
    if (!getConfiguration('permitListInvalidLinks'))
        $withinvalid = false; // Si pas autorisé, refuse de lire les liens invalides.
    if ($filtreact == '')
        $filtreact = '-';
    $version = ''; // version du lien.
    $n = 0; // indice dans la table des resultats.
    $tline = array(); // table d'un lien en cours de lecture et d'analyse.
    $lines = io_linksRead($object); // liens a lire et analyser.
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
                $verify = l_verify(trim($line));
            else
                $verify = -1;
            if ($verify == 1 || $verify == -1 || $withinvalid) // Le lien doit être vérifié ou la vérification désactivée.
            {
                $table [$n] [0] = trim($line); // Remplit le tableau à retourner.
                $table [$n] [1] = $tline [1];
                $table [$n] [2] = $tline [2];
                $table [$n] [3] = $tline [3];
                $table [$n] [4] = $tline [4];
                $table [$n] [5] = $tline [5];
                $table [$n] [6] = $tline [6];
                $table [$n] [7] = $tline [7];
                $table [$n] [8] = $version;
                $table [$n] [9] = $verify;
                $table [$n] [10] = openssl_error_string();
                $table [$n] [11] = 0; // Pour pondération.
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
 * @param $object
 */
function l_downloadlinkanywhere($object)
{ // Télécharge les liens de l'objet sur plusieurs localisations.
    // - $object l'objet dont les liens sont à télécharger.
    if (!getConfiguration('permitSynchronizeLink'))
        return;
    if ($object == '0')
        return;
    $table = array();
    $hashtype = o_getNID('nebule/objet/entite/localisation');
    $okobj = array();
    $count = 1;
    $okobj [1] = '';
    l_listlinks($hashtype, $table);
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
                o_getLocalContent($itemtable [6], $lnk);
                if ($lnk != '') {
                    l_downloadlinkonlocation($object, $lnk);
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
 * Link -
 *
 * Télécharge les liens de l'objet sur une localisation précise (un site web).
 *  - $object l'objet dont les liens sont à télécharger.
 *  - $localisation le site web sur lequel aller télécharger les liens.
 * Les liens valides sont écrits.
 *
 * @param string $oid
 * @param string $localisation
 * @return integer
 */
function l_downloadlinkonlocation($oid, $localisation)
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('nebulePermitSynchronizeLink')
        || $oid == '0'
        || $oid == ''
        || !is_string($oid)
        || $localisation == '0'
        || $localisation == ''
        || !is_string($localisation)
    )
        return 0;

    $count = 0;

    // WARNING ajouter vérification du lien type texte
    $distobj = fopen($localisation . '/l/' . $oid, 'r');
    if ($distobj) {
        while (!feof($distobj)) {
            $line = trim(fgets($distobj));
            $verify = l_verify($line);
            if ($verify == 1
                || $verify == -1
            ) {
                l_writecontent($line);
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
function l_checkBH(string &$bh): bool
{
    if (strlen($bh) > 4096) return false; // TODO à revoir.

    $rf = strtok($bh, '/');
    $rv = strtok('/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RF and RV.
    if (!l_checkRF($rf)) return false;
    if (!l_checkRV($rv)) return false;

    return true;
}

/**
 * Link - Check block RF on link.
 *
 * @param string $rf
 * @return bool
 */
function l_checkRF(string &$rf): bool
{
    if (strlen($rf) > 4096) return false; // TODO à revoir.

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
function l_checkRV(string &$rv): bool
{
    if (strlen($rv) > 4096) return false; // TODO à revoir.

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
function l_checkBL(string &$bl): bool
{
    if (strlen($bl) > 4096) return false; // TODO à revoir.

    $rc = strtok($bl, '/');
    $rl = strtok('/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RC and RL.
    if (!l_checkRC($rc)) return false;
    if (!l_checkRL($rl)) return false;

    return true;
}

/**
 * Link - Check block RC on link.
 *
 * @param string $rc
 * @return bool
 */
function l_checkRC(string &$rc): bool
{
    if (strlen($rc) > 4096) return false; // TODO à revoir.

    // Check items from RC : MOD>CHR
    $mod = strtok($rc, '>');
    if ($mod != '0') return false;
    $chr = strtok('>');
    if (strlen($chr) != 15) return false;
    if (!ctype_digit($chr)) return false;

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // TODO check MOD
    // TODO check CHR

    return true;
}

/**
 * Link - Check block RL on link.
 *
 * @param string $rl
 * @return bool
 */
function l_checkRL(string &$rl): bool
{
    if (strlen($rl) > 4096) return false; // TODO à revoir.

    // Extract items from RL 1 : REQ>NID>NID>NID
    $req = strtok($rl, '>');
    $rl1nid1 = strtok('>');
    if ($rl1nid1 === false) $rl1nid1 = '';
    $rl1nid2 = strtok('>');
    if ($rl1nid2 === false) $rl1nid2 = '';
    $rl1nid3 = strtok('>');
    if ($rl1nid3 === false) $rl1nid3 = '';

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check REQ, NID1, NID2 and NID4.
    if (!l_checkREQ($req)) return false;
    if (!n_checkNID($rl1nid1, false)) return false;
    if (!n_checkNID($rl1nid2, true)) return false;
    if (!n_checkNID($rl1nid3, true)) return false;

    return true;
}

/**
 * Link - Check block REQ on link.
 *
 * @param string $req
 * @return bool
 */
function l_checkREQ(string &$req): bool
{
    if ($req != 'l' && $req != 'f' && $req != 'u' && $req != 'd' && $req != 'e' && $req != 'c' && $req != 'k' && $req != 's' && $req != 'x') return false;

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
function l_checkBS(string &$bh, string &$bl, string &$bs): bool
{
    if (strlen($bs) > 4096) return false; // TODO à revoir.

    $rs = strtok($bs, '/');

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check content RS 1 NID 1 : hash.algo.size
    if (!n_checkNID($rs1nid1, false)) return false;
    if (!l_checkRS($rs, $bh, $bl)) return false;

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
function l_checkRS(string &$rs, string &$bh, string &$bl): bool
{
    if (strlen($rs) > 4096) return false; // TODO à revoir.

    // Extract items from RS : NID>SIG
    $nid = strtok($rs, '>');
    $sig = strtok('>');

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check content RS 1 NID 1 : hash.algo.size
    if (!n_checkNID($nid, false)) return false;
    if (!l_checkSIG($bh, $bl, $sig, $nid)) return false;

    return true;
}

/**
 * Link - Check block SIG on link.
 *
 * @param string $bh
 * @param string $bl
 * @param string $sig
 * @param string $nid
 * @return bool
 */
function l_checkSIG(string &$bh, string &$bl, string &$sig, string &$nid): bool
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

    // --- --- --- --- --- --- --- --- ---
    // Check sign.
    if (!getConfiguration('permitCheckSignOnVerify')) return true;
    if (io_testObjectPresent($nid)) {
        $hash = o_getNID($bh . '_' . $bl); // TODO remplacer getConfiguration('cryptoHashAlgorithm') par une convertion des algo et size.

        // Read signer's public key.
        o_checkContent($nid);
        $cert = io_objectRead($nid);
        $pubkeyid = openssl_pkey_get_public($cert);
        if ($pubkeyid === false) return false;

        m_add('lv');

        // Encoding sign before check.
        $binsign = pack('H*', $sign);

        // Decode sign with public key.
        if (openssl_public_decrypt($binsign, $bindecrypted, $pubkeyid, OPENSSL_PKCS1_PADDING)) {
            $decrypted = (substr(bin2hex($bindecrypted), -64, 64)); // TODO A faire pour le cas général.
            if ($decrypted == $hash)
                return true;
        }
    }

    return true;
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
function l_checkhashalgo(string &$algo, string &$size): bool
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
function l_verify(string $link): bool
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
    if (!l_checkBH($bh)) return false;
    if (!l_checkBL($bl)) return false;
    if (!l_checkBS($bh, $bl, $bs)) return false;

    return true;
}

/** FIXME
 * Link -
 *
 * TODO à refaire !
 *
 * @param $link
 * @return bool
 */
function l_writecontent($link)
{ // Ecrit le lien dans les objets concernés.
    // - $link le lien à écrire.
    // Se charge de répartir les liens sur les objets concernés.
    // Le lien de type c est géré un peu à part puisque tous les champs ne sont pas des objets.
    if (!getConfiguration('permitWrite'))
        return false;
    if (!getConfiguration('permitWriteLink'))
        return false;
    $sign = strtok(trim($link), '_'); // Lit la signature.
    if ($sign == '' || $sign == '0')
        return false;
    $objsig = strtok('_'); // Lit le signataire.
    if ($objsig == '' || $objsig == '0' || !n_checkNID($objsig))
        return false;
    $objtyp = strtok('_'); // Lit la date. N'est pas gardé.
    $objtyp = strtok('_'); // Lit le type.
    if ($objtyp != 'c' && $objtyp != 'd' && $objtyp != 'e' && $objtyp != 'f' && $objtyp != 'k' && $objtyp != 'l' && $objtyp != 's' && $objtyp != 'u' && $objtyp != 'x')
        return false;
    $objsrc = strtok('_'); // Lit l'objet source.
    if ($objsrc == '' || $objsrc == '0' || !n_checkNID($objsrc))
        return false;
    $objdst = strtok('_'); // Lit l'objet destination.
    if ($objdst == '' || !n_checkNID($objdst))
        return false;
    $objmet = strtok('_'); // Lit l'objet meta.
    if ($objmet == '' || !n_checkNID($objmet))
        return false;

    // Recherche la pré-existance du lien.
    $res = false;
    if (io_testLinkPresent($objsrc)) {
        $lines = io_linksRead($objsrc);
        foreach ($lines as $line) {
            if (!(substr($line, 0, 21) == 'nebule/liens/version/')) {
                $lsign = strtok(trim($line), '_');
                if ($lsign == $sign) {
                    $res = true;
                    break 1;
                } // Si le lien est déjà présent, on arrête l'écriture.
            }
        }
    }
    if (!$res) {
        io_linkWrite($objsig, $link); // Ecrit le lien dans l'entité signataire. Nouveauté v1.2 .
        if ($objtyp != 'c') {
            if ($objsrc != $objsig)
                io_linkWrite($objsrc, $link); // Ecrit le lien dans l'objet source.
            if ($objdst != $objsig && $objdst != '0')
                io_linkWrite($objdst, $link); // Ecrit le lien dans l'objet destination.
            if ($objmet != $objsig && $objmet != '0')
                io_linkWrite($objmet, $link); // Ecrit le lien dans l'objet méta.
        }
    }
    unset($lines);
    unset($res);
    unset($sign);
    unset($objsig);
    unset($objtyp);
    unset($objsrc);
    unset($objdst);
    unset($objmet);
    return true;
}


/*
 * ------------------------------------------------------------------------------------------
 * Fonctions bas niveau.
 * ------------------------------------------------------------------------------------------
 */

// I/O sont les fonctions liées aux accès disque. Peut être modifié pour permettre un accès en BDD ou autre.
/**
 * I/O - Preparing folders for links and objects (nodes with content).
 *
 * @return boolean
 */
function io_open(): bool
{
    if (!file_exists(LOCAL_LINKS_FOLDER))
        mkdir(LOCAL_LINKS_FOLDER);
    if (!file_exists(LOCAL_OBJECTS_FOLDER))
        mkdir(LOCAL_OBJECTS_FOLDER);
    if (!io_checkLinkFolder() || !io_checkObjectFolder())
        return false;
    return true;
}

/** FIXME
 * I/O - Vérifie l'état du dossier des liens.
 *
 * @return boolean
 */
function io_checkLinkFolder(): bool
{
    if (!file_exists(LOCAL_LINKS_FOLDER)
        || !is_dir(LOCAL_LINKS_FOLDER)
    ) {
        return false;
    }
    if (getConfiguration('permitWrite')
        && getConfiguration('permitWriteLink')
    ) {
        $data = o_getNID(getPseudoRandom(8) . date(DATE_ATOM));
        $name = LOCAL_LINKS_FOLDER . '/writest' . $data;
        file_put_contents($name, $data);
        if (!file_exists($name)
            || !is_file($name)
        ) {
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 1024);
        if ($data != $read) {
            return false;
        }
        unset($data, $read);
        unlink($name);
        unset($name);
    }
    return true;
}

/** FIXME
 * I/O - Vérifie l'état du dossier des objets.
 *
 * @return boolean
 */
function io_checkObjectFolder()
{
    if (!file_exists(LOCAL_OBJECTS_FOLDER)
        || !is_dir(LOCAL_OBJECTS_FOLDER)
    ) {
        return false;
    }
    if (getConfiguration('permitWrite')
        && getConfiguration('permitWriteObject')
    ) {
        $data = o_getNID(getPseudoRandom(8) . date(DATE_ATOM));
        $name = LOCAL_OBJECTS_FOLDER . '/writest' . $data;
        file_put_contents($name, $data);
        if (!file_exists($name)
            || !is_file($name)
        ) {
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 1024);
        if ($data != $read) {
            return false;
        }
        unset($data, $read);
        unlink($name);
        unset($name);
    }
    return true;
}

/** FIXME
 * I/O - Indique true si l'objet a des liens, ou false sinon.
 *
 * @param string $nid
 * @return boolean
 */
function io_testLinkPresent(&$nid): bool
{
    if (file_exists(LOCAL_LINKS_FOLDER . '/' . $nid)) {
        return true;
    }
    return false;
}

/** FIXME
 * I/O - Indique true si l'objet est présent, ou false sinon.
 *
 * @param string $nid
 * @return boolean
 */
function io_testObjectPresent(&$nid): bool
{
    if (file_exists(LOCAL_OBJECTS_FOLDER . '/' . $nid))
        return true;
    return false;
}

/** FIXME
 * I/O - Read object's links.
 * Retourne un tableau des liens lus, même vide.
 *
 * @param string $nid
 * @return boolean|array:string
 */
function io_linksRead(&$nid)
{
    $result = array();
    $count = 0;
    if (!io_testLinkPresent($nid))
        return $result;
    $links = file(LOCAL_LINKS_FOLDER . '/' . $nid); // TODO à refaire avec stream_context_create
    $maxLinks = getConfiguration('ioReadMaxLinks');
    foreach ($links as $link) {
        $result [$count] = $link;
        m_add('lr');
        $count++;
        if ($count > $maxLinks)
            break 1;
    }
    return $result;
}

/** FIXME
 * I/O - Read object content.
 * The function returns the read data or false on failure.
 *
 * @param string $nid
 * @param number $maxData
 * @return boolean|string
 */
function io_objectRead($nid, $maxData = 0)
{
    if ($maxData == 0)
        $maxData = getConfiguration('ioReadMaxData');
    if (!io_testObjectPresent($nid))
        return false;
    m_add('or');
    return file_get_contents(LOCAL_OBJECTS_FOLDER . '/' . $nid, false, null, 0, $maxData);
}

/** FIXME
 * I/O - Ecrit un lien de l'objet.
 * Retourne le nombre d'octets écrits ou false si erreur.
 *
 * @param string $nid
 * @param string $link
 * @return boolean|number
 */
function io_linkWrite(&$nid, &$link)
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteLink')
    )
        return false;
    return file_put_contents(LOCAL_LINKS_FOLDER . '/' . $nid, "$link\n", FILE_APPEND);
}

/**
 * I/O - Write object content.
 * The function returns the number of bytes that were written to the file, or false on failure.
 *
 * @param string $data
 * @return boolean|number
 */
function io_objectWrite(&$data)
{
    if (!getConfiguration('permitWrite')
        || !getConfiguration('permitWriteObject')
    )
        return false;
    return file_put_contents(LOCAL_OBJECTS_FOLDER . '/' . o_getNID($data), $data);
}

/**
 * I/O - Suppress object content.
 *
 * @param string $nid
 * @return boolean
 */
function io_objectDelete(&$nid)
{
    if (!getConfiguration('permitWrite') || !getConfiguration('permitWriteObject'))
        return false;
    if (!io_testObjectPresent($nid))
        return true;
    if (!unlink(LOCAL_OBJECTS_FOLDER . '/' . $nid))
    {
        addLog('fct="io_objectDelete:1" error="Unable to delete file"');
        return false;
    }
    return true;
}

/** FIXME
 * I/O - Fin de traitement.
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
 * Fonctions diverses.
 * ------------------------------------------------------------------------------------------
 */

/** FIXME
 * Filtre les chaines de caractères non imprimables mais conserve les retours chariots.
 *
 * @param string $data
 * @return string
 */
function filterPrinteableString($data): string
{
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $data);
}

/**
 * Translate algo name into OpenSSL algo name.
 *
 * @param string $algo
 * @param bool $loop
 * @return string
 */
function getTranslatedHashAlgo(string $algo, bool $loop = true): string
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

    if ($translatedAlgo == '' && $loop)
        $translatedAlgo = getTranslatedHashAlgo($algo, false);
    else {
        addLog('fct="getTranslatedHashAlgo" warn="cryptoHashAlgorithm configuration have an unknown value"');
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
function getDataHash(string &$data, string $algo = ''): string
{
    return hash(getTranslatedHashAlgo($algo), $data);
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
function getFileHash(string $file, string $algo = ''): string
{
    return hash_file(getTranslatedHashAlgo($algo), LOCAL_OBJECTS_FOLDER . '/' . $file);
}

/** FIXME
 * Generate pseudo random number
 * Use OpenSSL library.
 *
 * @param int $count
 * @return string
 */
function getPseudoRandom($count = 32): string
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
        $outvalue = pack("H*", hash($algo, $intcount . 'liberté égalité fraternité'));

        // Tronc au besoin la taille de la sortie.
        if (strlen($outvalue) > $diffsize)
            $outvalue = substr($outvalue, 0, $diffsize);

        // Ajoute la sortie au résultat final.
        $result .= $outvalue;
    }

    return $result;
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
        addLog('ask flush session');

        // Si la session n'est pas vide ou si interruption de l'utilisateur, la vide.
        if (isset($_SESSION['OKsession'])
            || filter_has_var(INPUT_GET, ARG_BOOTSTRAP_BREAK)
            || filter_has_var(INPUT_POST, ARG_BOOTSTRAP_BREAK)
        ) {
            // Mémorise pour la suite que la session est vidée.
            $bootstrapFlush = true;
            addLog('flush session');

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
        addLog('ask update');

        session_start();

        // Si la mise à jour est demandée mais pas déjà faite.
        if (!isset($_SESSION['askUpdate'])) {
            $bootstrapUpdate = true;
            addLog('update');
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
    global $bootstrapFlush, $bootstrapSwitchApplication, $bootstrapActiveApplicationsWhitelist, $nebuleServerEntite;

    if ($bootstrapFlush)
        return;

    $arg = '';
    if (filter_has_var(INPUT_GET, ARG_SWITCH_APPLICATION)) {
        $arg = trim(filter_input(INPUT_GET, ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    } elseif (filter_has_var(INPUT_POST, ARG_SWITCH_APPLICATION)) {
        $arg = trim(filter_input(INPUT_POST, ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    }
    if (is_string($arg)
        && n_checkNID($arg, true)
        && ($arg == '0'
            || $arg == '1'
            || io_testLinkPresent($arg)
        )
    ) {
        // Recherche si l'application est activée par l'entité instance de serveur.
        // Ou si l'application est en liste blanche.
        // Ou si c'est l'application par défaut.
        // Ou si c'est l'application 0.
        $activated = false;
        foreach ($bootstrapActiveApplicationsWhitelist as $item) {
            if ($arg == $item) {
                $activated = true;
            }
        }
        if ($arg == getConfiguration('defaultApplication')) {
            $activated = true;
        }
        if ($arg == '0') {
            $activated = true;
        }
        if ($arg == '1') {
            $activated = true;
        }
        if (!$activated) {
            $refActivated = o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_ACTIVE);
            $links = array();
            l_findinclusive($arg, $links, 'f', $arg, $refActivated, $arg);

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
            addLog('ask switch application to ' . $bootstrapSwitchApplication);
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

function findLibraryPOO(): void
{
    global $libppCheckOK, $bootstrapLibraryID, $bootstrapLibraryInstanceSleep;

    if (!$libppCheckOK)
        return;

    session_start();

    if (isset($_SESSION['bootstrapLibrariesID'])
        && n_checkNID($_SESSION['bootstrapLibrariesID'])
        && io_testLinkPresent($_SESSION['bootstrapLibrariesID'])
        && io_testObjectPresent($_SESSION['bootstrapLibrariesID'])
        && o_checkContent($_SESSION['bootstrapLibrariesID'])
        && isset($_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']])
        && $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']] != ''
    ) {
        $bootstrapLibraryID = $_SESSION['bootstrapLibrariesID'];
        $bootstrapLibraryInstanceSleep = $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']];
    }

    session_abort();

    if ($bootstrapLibraryID == '') {
        $bootstrapLibraryID = nebFindByRef(
            o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE),
            o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE),
            false);

        addLog('find nebule library ' . $bootstrapLibraryID);

        if (!n_checkNID($bootstrapLibraryID)
            || !io_testLinkPresent($bootstrapLibraryID)
            || !io_testObjectPresent($bootstrapLibraryID)
            || !o_checkContent($bootstrapLibraryID)
        ) {
            $bootstrapLibraryID = '';
            setBootstrapBreak('31', 'Finding nebule library error.');
        }
    }
}

/**
 * loadLibrary()
 * La fonction réalise le chargement et l'initialisation de la bibliothèque .
 *
 * Un certain nombre de variables globales sont initialisées au chargement de la bibliothèque,
 *   elles doivent être présentes ici.
 *
 * @return void
 */
function loadLibraryPOO(): void
{
    global $bootstrapName,
           $loggerSessionID,
           $nebuleInstance,
           $libpooCheckOK,
           $bootstrapLibraryID,
           $bootstrapLibraryInstanceSleep;

    if ($bootstrapLibraryID != '') {
        // Charge l'objet de la bibliothèque. @todo faire via les i/o.
        include(LOCAL_OBJECTS_FOLDER . '/' . '$bootstrapLibraryID');

        if ($bootstrapLibraryInstanceSleep == '') {
            // Instancie la bibliothèque.
            $nebuleInstance = new nebule();
        } else {
            // Récupère l'instance de la bibliothèque.
            $nebuleInstance = unserialize($bootstrapLibraryInstanceSleep);
        }

        // Ré-ouvre les logs pour le bootstrap.
        closelog();
        openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
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
    global $libppCheckOK;
    if (!$libppCheckOK)
        return;

    session_start();

    // Enregistre l'identifiant de session pour le suivi d'un utilisateur.
    $sessionId = session_id();
    addLog('session hash id ' . getDataHash($sessionId));

    // Vérifie l'ID de départ de l'application mémorisé.
    if (isset($_SESSION['bootstrapApplicationStartID'])
        && n_checkNID($_SESSION['bootstrapApplicationStartID'])
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
                o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS),
                false);
        } else {
            // Vérifie l'ID de l'application mémorisé.
            if (isset($_SESSION['bootstrapApplicationID'])
                && $_SESSION['bootstrapApplicationID'] != ''
                && n_checkNID($_SESSION['bootstrapApplicationID'])
                && $_SESSION['bootstrapApplicationID'] == '0'
                || (io_testLinkPresent($_SESSION['bootstrapApplicationID'])
                    && io_testObjectPresent($_SESSION['bootstrapApplicationID'])
                    && o_checkContent($_SESSION['bootstrapApplicationID'])
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
            addLog('ask switch application 0');

            // Application 0 de sélection des applications.
            $bootstrapApplicationStartID = '0';
            $bootstrapApplicationID = '0';
        } elseif ($bootstrapSwitchApplication == '1') {
            addLog('ask switch application 1');

            // Application 0 de sélection des applications.
            $bootstrapApplicationStartID = '1';
            $bootstrapApplicationID = '1';
        } elseif (n_checkNID($bootstrapSwitchApplication)
            && io_testLinkPresent($bootstrapSwitchApplication)
        ) {
            $refAppsID = o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS);
            $links = array();
            l_findinclusive($refAppsID, $links, 'f', $refAppsID, $bootstrapSwitchApplication, $refAppsID);

            // Vérifie que l'application est autorisée.
            if (sizeof($links) != 0) {
                // Fait le changement d'application.
                $bootstrapApplicationStartID = $bootstrapSwitchApplication;

                // Vérifie l'application non dé-sérialisée.
                if (isset($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && n_checkNID($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && io_testLinkPresent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && io_testObjectPresent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
                    && o_checkContent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
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
                        o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS),
                        false);
                }

                addLog('find switched application ' . $bootstrapApplicationID);
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
                o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS),
                false);
        } else {
            $bootstrapApplicationStartID = '0';
            $bootstrapApplicationID = '0';
        }
        unset($forceValue);

        addLog('find default application ' . $bootstrapApplicationID);
    }

    // Recherche si l'application ne doit pas être pré-chargée.
    if ($bootstrapApplicationStartID != '0'
        && $bootstrapApplicationStartID != '1'
        && $bootstrapApplicationInstanceSleep == ''
    ) {
        // Lit les liens de non pré-chargement pour l'application.
        $refNoPreload = o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS_DIRECT);
        $links = array();
        l_findinclusive($bootstrapApplicationStartID, $links, 'f', $bootstrapApplicationStartID, $refNoPreload, $bootstrapApplicationStartID);

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
                        addLog('do not preload application');
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
    addLog('bootstrap break code=' . $errorCode . ' msg="' . $errorDesc . '"');
}

// ------------------------------------------------------------------------------------------

function getBootstrapUserBreak(): void
{
    if (filter_has_var(INPUT_GET, ARG_BOOTSTRAP_BREAK)
        || filter_has_var(INPUT_POST, ARG_BOOTSTRAP_BREAK)
    ) {
        addLog('ask user interrupt');
        setBootstrapBreak('12', 'User interrupt.');
    }
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
    $hash = o_getNID($data);
    unset($data);
    // Recherche les liens de validation.
    $hashRef = o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP);
    $links = array();
    l_findinclusive($hashRef, $links, 'f', $hashRef, $hash, $hashRef, false);
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
        addLog('unknown bootstrap hash - critical');

        // Arrêt du bootstrap.
        setBootstrapBreak('52', 'Unknown bootstrap hash');
    }
}
// Vérifie l'empreinte du bootstrap. @todo ajouter vérification de marquage de danger.



// ------------------------------------------------------------------------------------------
function getBootstrapServerEntityDisplay()
{
    global $bootstrapServerEntityDisplay;

    if (filter_has_var(INPUT_GET, ARG_SERVER_ENTITY)
        || filter_has_var(INPUT_POST, ARG_SERVER_ENTITY)
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
global $bootstrapName, $bootstrapVersion, $bootstrapLicence, $bootstrapAuthor, $bootstrapWebsite,
       $bootstrapRescueMode;

?>
    <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?php echo $bootstrapName;
        if ($bootstrapRescueMode) echo ' - RESCUE' ?></title>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <meta name="author"
          content="<?php echo $bootstrapAuthor . ' - ' . $bootstrapWebsite . ' - ' . $bootstrapVersion; ?>"/>
    <meta name="licence" content="<?php echo $bootstrapLicence . ' ' . $bootstrapAuthor; ?>"/>
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
global $bootstrapName, $bootstrapVersion, $bootstrapLicence, $bootstrapAuthor, $bootstrapWebsite,
       $nebuleServerEntite;

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
            <?php echo $bootstrapName; ?><br/>
            <?php echo $bootstrapVersion; ?><br/>
            (c) <?php echo $bootstrapLicence . ' ' . $bootstrapAuthor; ?> - <a
                    href="http://<?php echo $bootstrapWebsite; ?>" target="_blank"
                    style="text-decoration:none;"><?php echo $bootstrapWebsite; ?></a>
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
    global $bootstrapName,
           $nebuleInstance,
           $bootstrapBreak,
           $bootstrapRescueMode,
           $bootstrapFlush,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $bootstrapApplicationStartID,
           $metrologyStartTime,
           $nebuleSecurityMaster,
           $nebuleCodeMaster,
           $nebuleDirectoryMaster,
           $nebuleTimeMaster,
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
        <span class="partstitle">#1 <?php echo $bootstrapName; ?> break on</span>
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
        <a href="?f">&gt; Flush PHP session</a> (<?php echo substr(getDataHash($sessionId), 0, 6); ?>)<br/>
    </div>
    <div class="parts">
        <span class="partstitle">#2 <?php echo $bootstrapName; ?> nebule library PP</span><br/>
        library version &nbsp;: <?php echo NEBULE_LIBPP_VERSION ?><br/>
        puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo getConfiguration('puppetmaster'); ?> (local authority)<br/>
        security master &nbsp;: <?php echo $nebuleSecurityMaster; ?> (local authority)<br/>
        code master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $nebuleCodeMaster; ?> (local authority)<br/>
        directory master : <?php echo $nebuleDirectoryMaster; ?><br/>
        time master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $nebuleTimeMaster; ?><br/>
        server entity &nbsp;&nbsp;&nbsp;: <?php echo $nebuleServerEntite;
        if (isset($nebuleLocalAuthorities[3]) && $nebuleLocalAuthorities[3] == $nebuleServerEntite) echo ' (local authority)'; ?>
        <br/>
        default entity &nbsp;&nbsp;: <?php echo $nebuleDefaultEntite;
        if (isset($nebuleLocalAuthorities[4]) && $nebuleLocalAuthorities[4] == $nebuleDefaultEntite) echo ' (local authority)'; ?>
        <br/>
        current entity &nbsp;&nbsp;: <?php echo $nebulePublicEntity; ?>
    </div>
    <div class="parts">
        <span class="partstitle">#3 nebule library POO</span><br/>
        <?php
        flush();

        // Chargement de la bibliothèque PHP POO.
        echo "Tl=" . sprintf('%01.4fs', microtime(true) - $metrologyStartTime) . "<br />\n";
        echo 'library RID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE) . "<br />\n";

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
            $hash = o_getNID($data);
            unset($data);
            echo 'bootstrap &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $hash . ' ';
            // Recherche les liens de validation.
            $hashRef = o_getNID(REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP);
            $links = array();
            l_findinclusive($hashRef, $links, 'f', $hashRef, $hash, $hashRef, false);
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
            echo 'L(r)=' . m_get('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead() . ' ';
            echo 'L(v)=' . m_get('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify() . ' ';
            echo 'O(r)=' . m_get('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead() . ' ';
            echo 'O(v)=' . m_get('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify() . " (PP+POO)<br />\n";
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

    <span class="partstitle">#- end <?php echo $bootstrapName; ?></span><br/>
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
    global $bootstrapName,
           $bootstrapVersion,
           $bootstrapBreak,
           $bootstrapRescueMode,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $metrologyStartTime;

    ob_end_flush();

    // Affichage.
    echo "<div class=\"bootstrapErrorDiv\"><p>\n";

    echo '&gt; ' . $bootstrapName . ' ' . $bootstrapVersion . "<br />\n";

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
    closelog();
    openlog('preload/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
    addLog('Loading');

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
        closelog();
        openlog('preload/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
        ?>

        Tl=<?php echo sprintf('%01.4fs', microtime(true) - $metrologyStartTime); ?>
    </div>

    <div class="preload">
        <img title="bootstrap" style="background:#<?php echo substr($bootstrapApplicationStartID . '000000', 0, 6); ?>;"
             alt="[]" src="<?php echo REFERENCE_APPLICATION_ICON; ?>"/>
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
        if (!e_check($serverEntite)) {
            setBootstrapBreak('72', 'Local server entity error');
            return true;
        }
    } else {
        setBootstrapBreak('71', 'No local server entity');
        return true;
    }
    return false;
}



// ------------------------------------------------------------------------------------------
/**
 * bootstrapDisplayApplicationfirst()
 * Affichage de l'initialisation de l'entité locale instance du serveur.
 *
 * @return void
 */
function bootstrapDisplayApplicationfirst(): void
{
    global $loggerSessionID, $bootstrapBreak, $metrologyStartTime, $bootstrapName, $bootstrapRescueMode,
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
    closelog();
    openlog('first/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
    addLog('Loading');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();
    ?>

    <div class="parts">
        <span class="partstitle">#1 <?php echo $bootstrapName; ?> break on</span>
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
        } ?>
    </div>

    <div class="parts">
    <span class="partstitle">#2 create folders</span><br/>
    <?php
    if (!io_checkLinkFolder() || !io_checkObjectFolder()) {
        ?>

        <span class="error">ERROR!</span>
        <?php
        if (!io_checkLinkFolder()) {
            addLog('error links folder');
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
            addLog('error objects folder');
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
        ?>

        </div>

        <div id="reload">
            <button onclick="javascript:window.location.reload(true);">Reload</button>
        </div>
        <?php
    } else {
        addLog('ok folders');
        ?>

        ok
        <?php
        echo "</div>\n";
        bootstrapFirstCreateObjects();
    }

    bootstrapHtmlBottom();
}

/**
 * bootstrapInlineDisplayApplicationfirst()
 * Affichage de l'initialisation de l'entité locale instance du serveur.
 *
 * @return void
 */
function bootstrapInlineDisplayApplicationfirst()
{

}


// ------------------------------------------------------------------------------------------
/**
 * bootstrapFirstCreateObjects()
 * Création des objets nécessaires au bon fonctionnement de la bibliothèque.
 *
 * @return void
 */
function bootstrapFirstCreateObjects()
{
    global $nebuleFirstReservedObjects, $bootstrapName;

    ?>

    <div class="parts">
        <span class="partstitle">#3 nebule needed library objects</span><br/>
        <?php
        // Si il manque un des objets, recrée les objets.
        $hash = o_getNID($nebuleFirstReservedObjects[10]);
        if (!io_testObjectPresent($hash))
        {
        addLog('need create objects');

        // Ecrit les objets de localisation.
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $data) {
            io_objectWrite($data);
            echo '.';
        }

        // Ecrit les objets réservés.
        foreach ($nebuleFirstReservedObjects as $data) {
            io_objectWrite($data);
            echo '.';
        }
        ?>
        OK
    </div>
    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo $bootstrapName; ?></a> ...
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
    addLog('ok create objects');
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
 * bootstrapFirstSynchronizingEntities()
 * Synchronisation du minimum d'entités sur internet pour fonctionner.
 *
 * @return void
 */
function bootstrapFirstSynchronizingEntities()
{
    global $bootstrapName, $nebuleLocalAuthorities, $libppCheckOK,
           $nebuleSecurityMaster, $nebuleCodeMaster, $nebuleDirectoryMaster, $nebuleTimeMaster;

    ?>

    <div class="parts">
    <span class="partstitle">#4 synchronizing entities</span><br/>
    <?php
    // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
    if (!$libppCheckOK) {
        ?>

        puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        addLog('need puppetmaster');
        echo ' ' . getConfiguration('puppetmaster') . ' ';

        // Ecriture de la clé publique par défaut.
        $data = FIRST_PUPPETMASTER_PUBLIC_KEY;
        io_objectWrite($data);
        $object = NEBULE_DEFAULT_PUPPETMASTER_ID;
        $link = FIRST_PUPPETMASTER_HASH_LINK;
        io_linkWrite($object, $link);
        $link = FIRST_PUPPETMASTER_TYPE_LINK;
        io_linkWrite($object, $link);
        unset($data, $object, $link);

        // Activation comme autorité locale.
        $nebuleLocalAuthorities[0] = getConfiguration('puppetmaster');

        // Recherche des autres liens.
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
            o_downloadContent(getConfiguration('puppetmaster'), $localisation);
            l_downloadlinkonlocation(getConfiguration('puppetmaster'), $localisation);
            echo '.';
        }
        echo ' ';
        flush();
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
            l_downloadlinkonlocation(o_getNID('nebule/objet/entite/maitre/securite'), $localisation);
            echo '.';
            l_downloadlinkonlocation(o_getNID('nebule/objet/entite/maitre/code'), $localisation);
            echo '.';
            l_downloadlinkonlocation(o_getNID('nebule/objet/entite/maitre/annuaire'), $localisation);
            echo '.';
            l_downloadlinkonlocation(o_getNID('nebule/objet/entite/maitre/temps'), $localisation);
            echo '.';
        }
        flush();
        ?><br/>

        security master &nbsp;:
        <?php
        // Recherche l'ID de l'entité par référence du maître du tout.
        addLog('need sync security master');
        // Recherche via l'objet de référence.
        $entity = nebFindByRef(
            o_getNID('nebule/objet/entite/maitre/securite'),
            o_getNID('nebule/objet/entite/maitre/securite'),
            false);
        echo ' ' . $entity . ' ';
        if (n_checkNID($entity)
            && io_testLinkPresent($entity)
        ) {
            // Recherche de l'objet et des liens de l'entité.
            $nebuleSecurityMaster = $entity;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                o_downloadContent($nebuleSecurityMaster, $localisation);
                l_downloadlinkonlocation($nebuleSecurityMaster, $localisation);
                echo '.';
            }
        } else {
            echo " <span class=\"error\">fail!</span>\n";
        }
        flush();
        ?><br/>

        code master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        // Recherche l'ID de l'entité par référence du maître du tout.
        addLog('need sync code master');
        // Recherche via l'objet de référence.
        $entity = nebFindByRef(
            o_getNID('nebule/objet/entite/maitre/code'),
            o_getNID('nebule/objet/entite/maitre/code'),
            false);
        echo ' ' . $entity . ' ';
        if (n_checkNID($entity)
            && io_testLinkPresent($entity)
        ) {
            // Recherche de l'objet et des liens de l'entité.
            $nebuleCodeMaster = $entity;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                o_downloadContent($nebuleCodeMaster, $localisation);
                l_downloadlinkonlocation($nebuleCodeMaster, $localisation);
                echo '.';
            }
        } else {
            echo " <span class=\"error\">fail!</span>\n";
        }
        flush();
        ?><br/>

        directory master :
        <?php
        // Recherche l'ID de l'entité par référence du maître du tout.
        addLog('need sync directory master');
        // Recherche via l'objet de référence.
        $entity = nebFindByRef(
            o_getNID('nebule/objet/entite/maitre/annuaire'),
            o_getNID('nebule/objet/entite/maitre/annuaire'),
            false);
        echo ' ' . $entity . ' ';
        if (n_checkNID($entity)
            && io_testLinkPresent($entity)
        ) {
            // Recherche de l'objet et des liens de l'entité.
            $nebuleDirectoryMaster = $entity;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                o_downloadContent($nebuleDirectoryMaster, $localisation);
                l_downloadlinkonlocation($nebuleDirectoryMaster, $localisation);
                echo '.';
            }
        } else {
            echo " <span class=\"error\">fail!</span>\n";
        }
        flush();
        ?><br/>

        time master &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        // Recherche l'ID de l'entité par référence du maître du tout.
        addLog('need sync time master');
        // Recherche via l'objet de référence.
        $entity = nebFindByRef(
            o_getNID('nebule/objet/entite/maitre/temps'),
            o_getNID('nebule/objet/entite/maitre/temps'),
            false);
        echo ' ' . $entity . ' ';
        if (n_checkNID($entity)
            && io_testLinkPresent($entity)
        ) {
            // Recherche de l'objet et des liens de l'entité.
            $nebuleTimeMaster = $entity;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                o_downloadContent($nebuleTimeMaster, $localisation);
                l_downloadlinkonlocation($nebuleTimeMaster, $localisation);
                echo '.';
            }
        } else {
            echo " <span class=\"error\">fail!</span>\n";
        }
        ?>

        </div>

        <div id="reload">
            <?php
            if ($libppCheckOK) {
                ?>

                &gt; <a
                    onclick="javascript:window.location.reload(true);">reloading <?php echo $bootstrapName; ?></a> ...
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
                    reload <?php echo $bootstrapName; ?></button>
                <?php
            }
            ?>

        </div>
        <?php
    } else {
        addLog('ok sync entities');
        ?>

        ok
        <?php
        echo "</div>\n";
        // Sinon c'est bon pour la première synchronisation.
        bootstrapFirstSynchronizingObjects();
    }

}


// ------------------------------------------------------------------------------------------
/**
 * bootstrapFirstSynchronizingObjects()
 * Synchronisation des objets sur internet pour fonctionner.
 *
 * @return void
 */
function bootstrapFirstSynchronizingObjects()
{
    global $nebuleFirstReservedObjects, $bootstrapName, $nebuleLocalAuthorities;

    $refApps = REFERENCE_NEBULE_OBJECT_INTERFACE_APPLICATIONS;
    $refAppsID = o_getNID($refApps);
    $refLib = REFERENCE_NEBULE_OBJECT_INTERFACE_BIBLIOTHEQUE;
    $refLibID = o_getNID($refLib);
    $refBoot = REFERENCE_NEBULE_OBJECT_INTERFACE_BOOTSTRAP;
    $refBootID = o_getNID($refBoot);
    ?>

    <div class="parts">
        <span class="partstitle">#5 synchronizing objets</span><br/>
        <?php
        // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
        if (!io_testObjectPresent($refAppsID)
        && !io_testObjectPresent($refLibID)
        && !io_testLinkPresent($refBootID)
        )
        {
        addLog('need sync objects');

        // Ecrit les objets de localisation.
        echo 'objects &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $data) {
            $hash = o_getNID($data);;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                $count = l_downloadlinkonlocation($hash, $localisation);
                echo '.';
                if ($count != 0) {
                    break 1;
                }
            }
            echo ' ';
            flush();
        }

        // Ecrit les objets réservés.
        foreach ($nebuleFirstReservedObjects as $data) {
            $hash = o_getNID($data);;
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                $count = l_downloadlinkonlocation($hash, $localisation);
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
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
            l_downloadlinkonlocation($refBootID, $localisation);
            echo '.';
        }
        ?><br/>

        library start &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        echo $refLib . ' ';
        flush();
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
            l_downloadlinkonlocation($refLibID, $localisation);
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
            foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                o_downloadContent($lastID, $localisation);
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
        foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
            l_downloadlinkonlocation($refAppsID, $localisation);
            echo '.';
        }
        ?><br/>

        application list &nbsp;&nbsp;:
        <?php
        // Pour chaque application, faire une synchronisation.
        $links = array();
        l_findinclusive($refAppsID, $links, 'f', $refAppsID, '', $refAppsID, false);

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
            addLog('find app ' . $appID . ' as ' . $lastID);
            if ($lastID != '0') {
                foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                    o_downloadContent($lastID, $localisation);
                    l_downloadlinkonlocation($lastID, $localisation);
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
                    foreach (BOOTSTRAP_FIRST_LOCALISATIONS as $localisation) {
                        o_downloadContent($nameID, $localisation);
                        l_downloadlinkonlocation($nameID, $localisation);
                        echo '.';
                    }
                }
            } else {
                echo '<span id="error">ERROR!</span>';
            }
            ?><br/>

            <?php
        }
        ?>

    </div>
    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo $bootstrapName; ?></a> ...
    <script type="text/javascript">
        <!--
        setTimeout(function () {
            window.location.reload(true)
        }, <?php echo FIRST_RELOAD_DELAY; ?>);
        //-->
    </script>
    <?php
} else {
    addLog('ok sync objects');
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
 * bootstrapFirstCreateOptionsFile()
 * Crée le fichier des options par défaut.
 *
 * @return void
 */
function bootstrapFirstCreateOptionsFile()
{
    global $bootstrapName, $bootstrapSurname, $bootstrapAuthor, $bootstrapVersion, $bootstrapWebsite;

    ?>

    <div class="parts">
        <span class="partstitle">#6 options file</span><br/>
        <?php
        if (!file_exists(NEBULE_ENVIRONMENT_FILE))
        {
        addLog('need create options file');

        $defaultOptions = "# Generated by the " . $bootstrapName . ", part of the " . $bootstrapAuthor . ".\n";
        $defaultOptions .= "# Default options file generated after the first synchronization.\n";
        $defaultOptions .= "# " . $bootstrapSurname . "\n";
        $defaultOptions .= "# Version : " . $bootstrapVersion . "\n";
        $defaultOptions .= "# http://" . $bootstrapWebsite . "\n";
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
    &gt; <a onclick="javascript:window.location.reload(true);">reloading <?php echo $bootstrapName; ?></a> ...
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
    </div>
    <button onclick="javascript:window.location.reload(true);">when ready, reload <?php echo $bootstrapName; ?></button>
    </div>
    <?php
}
    unset($defaultOptions);
}
else {
    addLog('ok create options file');
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
 * bootstrapFirstCreateLocaleEntity()
 * Crée le fichier des options par défaut.
 *
 * @return void
 */
function bootstrapFirstCreateLocaleEntity()
{
    global $bootstrapName,
           $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite;
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
            $nebulePasswordEntite = substr($nebulePasswordEntite . $padding, 0, FIRST_PASSWORD_SIZE);
            unset($genpasswd, $padding, $i, $a);

            $nebulePublicEntity = '0';
            $nebulePrivateEntite = '0';
            // Génère une nouvelle entité.
            e_generate(getConfiguration('cryptoAsymetricAlgorithm'), getConfiguration('cryptoHashAlgorithm'), $nebulePublicEntity, $nebulePrivateEntite, $nebulePasswordEntite);

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
            $name = substr($name . 'robott', 0, FIRST_NAME_SIZE);

            // Enregistrement du nom.
            nebINECreatObjText($name);
            $refHashName = o_getNID('nebule/objet/nom');
            $hashName = o_getNID($name);
            $newlink = l_generate('-', 'l', $nebulePublicEntity, $hashName, $refHashName);
            if (l_verify($newlink) == 1) {
                l_writecontent($newlink);
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
            <form method="post" action="<?php echo BOOTSTRAP_FIRST_URL_TO_OPTION . $nebulePublicEntity; ?>">
                <input type="hidden" name="pwd" value="<?php echo $nebulePasswordEntite; ?>">
                <input type="hidden" name="switch" value="true">
                <input type="submit" value="when ready, click here to go to options">
            </form>
            <br/><br/>
            <button onclick="javascript:window.location.assign('<?php echo BOOTSTRAP_FIRST_URL_TO_OPTION; ?>');">when
                ready, click here to go to options
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
            </div>
            <button onclick="javascript:window.location.reload(true);">when ready,
                reload <?php echo $bootstrapName; ?></button>
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
    closelog();
    openlog('app0/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
    addLog('Loading');

    echo 'CHK';
    ob_end_clean();

    $alogo = 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAADkElEQVRo3u2ZT0hUQRzHP9sGgoEh0T8kL8/Ag+tBsUNdCpmKjA7VQdzKwg6pdIoOnkQKPHgUtUOGSrt0qIUORTGIXepgZAc9eJCFMgmUEj0IQotdlJ03b1779u2IK/k7vTfvN/P7zm9+8/v3YI/+d4oEZxUHaaaBCZJyw4cjQgvn+MwLuWIZgKijg9tEgTl6SJggiAhxuqkCMowwKKesARDPiSuvRgiK+C1KyBu2AOj7XWSaKJUcphRYY4nvZIhxxM0mI9sFICAFAbBvp2/BbgAg2sIuHmRmNOcigzwKvb0rztH0mwKMUJSQ4pLhwxTjTDLLAqtAGRVUc4pG6gy8b7kq10MBECW846w2uMYAY3LGZ0YNt+ikVBv+wEV/CP8C8Maz+z565XKOIyuni4e6FmRT3gDEIO2a2u/LTwGN7zT92nEMyY68jFC0aaaXpEl+C2p76XnnGVXElKEG50f6a04NaD4/S09ke4hLOMQ94wdXjIi4It4X44SkjIf0AwlajB/qs5FSdUTmU5qiNbQfaMUckjsMGhAH+WW0iDNBTc/HHD8ahjMc2kpZshpoNorvK0Q8yE/0GU2/2XsEDQbGNXoLjja9rBlGG7wAJpjzsA3kcjsBdLDMgGdwjgkvgCQ9HghjViLumEd8D0mzH7jGS9X+Zb2dmC++KH5xkQdqRunOB1KMK2/j1rIOdaVpd0LrAiA3XDdh0hoAdaWoO5/WM6JK5XnWGoBZHwkGAIeV5wVrABZ8JBgAqMnEqjUAqz4SijErVr1WmTUpZT4SDACWlOcKawAqfCQYAHxXnqutAaj2kaADEBEyyuspawDUlTIi4gNARIi78rhGawDUlWLEVQhRrb4/obAed16lFy1EghpXgnuAWn4702mPBlq09gLALSv711epojubK2YBxD3ioVOUF7z/cjo9g1Wc8wJ4bZhdSlfB++/ylGoAn4svKZUrjBjX6Bf7Q4vfT7/xw0i2jaf6gUEjcx2joRUwaizYXZIUpad/OiepNbDHnGO52gw+pdkdn9JsIGd1LNp4qhWnrfJPXsof1cqyu3I4j+o4/dU56qoUYlx2ZtLzgU0vxXmtPH+82xoURdCi2fEmlU+rJj/ybc0EBmC4EcHJx/LzBLDXrN5eChto3lOi/bBY58L2AUho7bvr8pXBUtzFPSSsHYG8QT3DmxnzHDdJGdlS3NxscWQYpj7IH6Mi+G23R3v0FwbfFx3mQ2ZaAAAAAElFTkSuQmCC';

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    // Ré-initialisation des logs
    closelog();
    openlog('app0/' . $loggerSessionID, LOG_NDELAY, LOG_USER);

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
            //echo '<div class="apps" style="background:'.$color.';" onclick="followHref(\'/?'.ARG_SWITCH_APPLICATION.'='.$application.'\')">';
            echo '<div class="apps" style="background:' . $color . ';">';
            //echo '<div class="appssigner" style="background:'.$color.';"><img alt="'.$signersList[$application].'" src="data:image/png;base64,'.$alogo.'" /></div>';
            echo '<span class="appstitle">' . $shortName . '</span><br /><span class="appsname">' . $title . '</span>';
            echo "</div></a>\n";
        }
        unset($alogo, $application, $applicationsList, $instance, $color, $title, $shortName);
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
    closelog();
    openlog('app1/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
    addLog('Loading');

    echo 'CHK';
    ob_end_clean();

    bootstrapHtmlHeader();
    bootstrapHtmlTop();

    // Ré-initialisation des logs
    closelog();
    openlog('app1/' . $loggerSessionID, LOG_NDELAY, LOG_USER);

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
 PART11 : Main display router.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function displayRouter(bool $needFirstSynchronization)
{
    global $bootstrapBreak, $bootstrapRescueMode, $bootstrapInlineDisplay, $bootstrapName, $loggerSessionID,
           $bootstrapApplicationID, $applicationName, $bootstrapApplicationNoPreload,
           $bootstrapApplicationStartID, $nebuleInstance, $bootstrapLibraryID,
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
            addLog('load application 0');

            bootstrapDisplayApplication0();

            // Change les logs au nom du bootstrap.
            closelog();
            openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
        } elseif ($bootstrapApplicationID == '1') {
            addLog('load application 1');

            bootstrapDisplayApplication1();

            // Change les logs au nom du bootstrap.
            closelog();
            openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
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
                addLog('load application ' . $bootstrapApplicationID);

                // Charge l'objet de l'application. @todo faire via les i/o.
                include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);

                // Change les logs au nom de l'application.
                closelog();
                openlog($applicationName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);

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

                addLog('load application whitout preload ' . $bootstrapApplicationID);

                // Charge l'objet de l'application. @todo faire via les i/o.
                include(LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);

                // Change les logs au nom de l'application.
                closelog();
                openlog($applicationName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);

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
            closelog();
            openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);

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
            addLog('load first');

            // Affichage sur interruption du chargement.
            if ($bootstrapInlineDisplay) {
                bootstrapInlineDisplayApplicationfirst();
            } else {
                bootstrapDisplayApplicationfirst();
            }
        } elseif ($bootstrapServerEntityDisplay) {
            if (file_exists(LOCAL_ENTITY_FILE)) {
                echo file_get_contents(LOCAL_ENTITY_FILE, false, null, -1, getConfiguration('ioReadMaxData'));
            } else {
                echo '0';
            }
        } else {
            addLog('load break');

            // Affichage sur interruption du chargement.
            if ($bootstrapInlineDisplay) {
                bootstrapInlineDisplayOnBreak();
            } else {
                bootstrapDisplayOnBreak();
            }
        }

        // Change les logs au nom du bootstrap.
        closelog();
        openlog($bootstrapName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
    }
}

function bootstrapLogMetrology()
{
    global $nebuleInstance;

    // Metrology on logs.
    if (is_a($nebuleInstance, 'nebule')) {
        addLog('Mp=' . memory_get_peak_usage()
            . ' - Lr=' . m_get('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead()
            . ' Lv=' . m_get('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify()
            . ' Or=' . m_get('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead()
            . ' Ov=' . m_get('ov') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify()
            . ' (PP+POO) -'
            . ' LC=' . $nebuleInstance->getCacheLinkSize()
            . ' OC=' . $nebuleInstance->getCacheObjectSize()
            . ' EC=' . $nebuleInstance->getCacheEntitySize()
            . ' GC=' . $nebuleInstance->getCacheGroupSize()
            . ' CC=' . $nebuleInstance->getCacheConversationSize());
    } else {
        addLog('Mp=' . memory_get_peak_usage()
            . ' - Lr=' . m_get('lr')
            . ' Lv=' . m_get('lv')
            . ' Or=' . m_get('or')
            . ' Ov=' . m_get('ov')
            . ' (PP)');
    }
}

function main()
{
    /*
     * Initialization of Lib PP.
     */
    if (!libppInit())
        setBootstrapBreak('21', 'Library init error');

    /*
     * Vérifie que le dossier des liens est fonctionnel.
     */
    if (!io_checkLinkFolder())
        setBootstrapBreak('22', "Library i/o link's folder error");

    /*
     * Vérifie que le dossier des objets est fonctionnel.
     */
    if (!io_checkObjectFolder())
        setBootstrapBreak('23', "Library i/o object's folder error");

    getBootstrapUserBreak();
    getBootstrapInlineDisplay();
    getBootstrapCheckFingerprint();
    $needFirstSynchronization = getBootstrapNeedFirstSynchronization();
    getBootstrapServerEntityDisplay();
    getBootstrapFlushSession();
    getBootstrapUpdate();
    getBootstrapSwitchApplication();

    setPermitOpenFileCode();
    findLibraryPOO();
    loadLibraryPOO();
    findApplication();

    displayRouter($needFirstSynchronization);
    bootstrapLogMetrology();
}

// Calcul du temps de chargement du bootstrap.
$bootstrapLoadingTime = microtime(true) - $metrologyStartTime;

main();

?>
