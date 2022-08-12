<?php
declare(strict_types=1);
namespace Nebule\Library;

// ----------------------------------------------------------------------------------------
$nebuleName = 'library';
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleSurname = 'nebule/library';
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleDescription = 'Library of functions for nebule in php object-oriented.';
$nebuleAuthor = 'Projet nebule';
$nebuleLibVersion = '020220812';
$nebuleLicence = 'GNU GPL 2010-2022';
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
if (!isset($loggerSessionID))
    $loggerSessionID = '000000';
if (!isset($metrologyStartTime))
    $metrologyStartTime = 0;
openlog($nebuleName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="a77c98f7" LogF="include nebule library" LogM="Reading nebule library"');



// Default application params.
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationName = 'defolt';
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationSurname = 'nebule/defolt';
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationDescription = 'Default web page for servers without interactive application.';
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationVersion = $nebuleLibVersion;
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationLicence = $nebuleLicence;
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationAuthor = $nebuleAuthor;
/** @noinspection PhpUnusedLocalVariableInspection */
$applicationWebsite = $nebuleWebsite;
