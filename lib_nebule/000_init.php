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
$nebuleLibVersion = '020241024';
$nebuleLicence = 'GNU GPL 2010-2024';
$nebuleWebsite = 'www.nebule.org';
// ----------------------------------------------------------------------------------------



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



// Initialisation des logs de la librairie.
closelog();
if (!isset($loggerSessionID))
    $loggerSessionID = '000000';
if (!isset($metrologyStartTime))
    $metrologyStartTime = 0;
openlog($nebuleName . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
syslog(LOG_INFO, 'LogT=' . sprintf('%01.6f',microtime(true) - $metrologyStartTime) . ' LogL="info" LogI="a77c98f7" LogF="include nebule library" LogM="Reading nebule library"');



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
