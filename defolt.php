<?php
// ------------------------------------------------------------------------------------------
$applicationName		= 'defolt';
$applicationSurname		= 'nebule/defolt';
$applicationDescription = 'Default web page for servers without interactive application.';
						// Page web par défaut pour les serveurs sans application interactive.
$applicationVersion		= '020200222';
$applicationLevel		= 'Testing'; // Experimental | Developpement | Testing | Production
$applicationLicence		= 'GNU GPL 2016-2020';
$applicationAuthor		= 'Projet nebule';
$applicationWebsite		= 'www.nebule.org';
// ------------------------------------------------------------------------------------------



/*
------------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
------------------------------------------------------------------------------------------

    .     [FR] Toute modification de ce code entrainera une modification de son empreinte
   / \           et entrainera donc automatiquement son invalidation !
  / V \   [EN] Any changes to this code will cause a chage in its footprint and therefore
 /__°__\         automatically result in its invalidation!
    N     [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
    N            tanto lugar automáticamente a su anulación!
    N
    N                                                                       Projet nebule
----N-------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
------------------------------------------------------------------------------------------
*/











/**
 * Classe Application
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Le coeur de l'application.
 *
 */
class Application extends Applications
{
	/**
	 * Constructeur.
	 *
	 * @param nebule $nebuleInstance
	 * @return void
	 */
	public function __construct(nebule $nebuleInstance)
	{
		$this->_nebuleInstance = $nebuleInstance;
	}
	
	/**
	 * Fait un état complet de la sécurité.
	 *
	 * @return void
	 */
	protected function _checkSecurity()
	{
	    // Rien.
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
	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
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
	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}
	
	
	
	/**
	 * Traitement des actions spéciales.
	 * Ces actions peuvent être réalisées sans entité déverrouillée.
	 *
	 * @return void
	 */
	public function specialActions()
	{
		$this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_NORMAL); // Log
	
		// Rien
	
		$this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_NORMAL); // Log
	}
	/**
	 * Traitement des actions génériques.
	 *
	 * @return void
	 */
	public function genericActions()
	{
		$this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_NORMAL); // Log
	
		// Rien
	
		$this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_NORMAL); // Log
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
class Traduction extends Traductions
{
	/**
	 * Constructeur.
	 *
	 * @param Application $applicationInstance
	 * @return void
	 */
	public function __construct(Application $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}
	
	// Tout par défaut.
}
