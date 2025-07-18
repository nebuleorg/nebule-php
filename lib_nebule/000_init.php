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
$nebuleLibVersion = '020250712';
$nebuleLicence = 'GNU GPL 2010-2025';
$nebuleWebsite = 'www.nebule.org';
// ----------------------------------------------------------------------------------------



/*
|------------------------------------------------------------------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------------------------------------------------------------------
|
|  [DE] Jede Änderung dieses Codes führt zu einer Änderung seines Fingerabdrucks und führt daher automatisch zu seiner Ungültigkeit!
|  [EN] Any modification of this code will result in a modification of its hash digest and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo tanto lugar automáticamente a su anulación!
|  [FR] Toute modification de ce code entraînera une modification de son empreinte et entraînera donc automatiquement son invalidation !
|  [IT] Ogni modifica di questo codice comporterà una modifica della sua impronta e quindi ne causerà automaticamente l'invalidazione!
|  [PL] Każda modyfikacja tego kodu spowoduje zmianę jego odcisku i automatycznie doprowadzi do jego unieważnienia!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і, відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------------------------------------------------------------------
*/



/*
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 *   License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any
 *   later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 *   details.
 *
 * You should have received a copy of the GNU General Public License along with this program. See on the end of file.
 *   If not, see https://www.gnu.org/licenses/.
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
