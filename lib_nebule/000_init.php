<?php
declare(strict_types=1);
namespace Nebule\Library;

// ----------------------------------------------------------------------------------------
$nebuleName = 'library';
$nebuleSurname = 'nebule/library';
$nebuleDescription = 'Library of functions for nebule in php object-oriented.';
$nebuleAuthor = 'Projet nebule';
$nebuleLibVersion = '02021128';
$nebuleLicence = 'GNU GPL 2010-2021';
$nebuleWebsite = 'www.nebule.org';
// ----------------------------------------------------------------------------------------



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



// Initialisation des logs de la librairie.
closelog();
if (isset($loggerSessionID) === false)
    $loggerSessionID = '000000';
openlog($nebuleName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
syslog(LOG_INFO, 'LogT=0 LogTabs=' . (microtime(true)) . ' Loading nebule library');



// Paramètres de l'application par défaut.
$applicationName = 'defolt';
$applicationSurname = 'nebule/defolt';
$applicationDescription = 'Default web page for servers without interactive application.';
$applicationVersion = $nebuleLibVersion;
$applicationLicence = $nebuleLicence;
$applicationAuthor = $nebuleAuthor;
$applicationWebsite = $nebuleWebsite;
