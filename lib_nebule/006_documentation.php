<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Documentation class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Documentation
{
	/**
	 * Instance de la librairie en cours.
	 *
	 * @var nebule
	 */
	protected $_nebuleInstance;

	/**
	 * Constructeur.
	 * Toujours transmettre l'instance de la librairie nebule.
	 *
	 * @param nebule $nebuleInstance
	 */
	public function __construct(nebule $nebuleInstance)
	{
		$this->_nebuleInstance = $nebuleInstance;
	}

	/**
	 * Affiche le contenu de la documentation technique de la bibliothèque nebule.
	 *
	 * @return void
	 */
	public function display_content()
	{
		// Début de la documentation technique nebule.
	}
}
