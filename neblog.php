<?php
declare(strict_types=1);
namespace Nebule\Application\Neblog;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Traductions;

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



/**
 * Class Application for upload
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'neblog';
    const APPLICATION_SURNAME = 'nebule/neblog';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020240219';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2024';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    // All default.
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
    // Tout par défaut.
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
class Traduction extends Traductions
{
    /**
     * La langue d'affichage de l'interface.
     *
     * Dans cette application la langue est forcée à sa valeur par défaut.
     *
     * @return void
     */
    protected function _findCurrentLanguage()
    {
        $this->_currentLanguage = $this->_defaultLanguage;
        $this->_currentLanguageInstance = $this->_defaultLanguageInstance;
    }


    /**
     * Initialisation de la table de traduction.
     */
    protected function _initTable()
    {
        $this->_table['fr-fr']['::::INFO'] = 'Information';
        $this->_table['en-en']['::::INFO'] = 'Information';
        $this->_table['es-co']['::::INFO'] = 'Information';
        $this->_table['fr-fr']['::::OK'] = 'OK';
        $this->_table['en-en']['::::OK'] = 'OK';
        $this->_table['es-co']['::::OK'] = 'OK';
        $this->_table['fr-fr']['::::INFORMATION'] = 'Message';
        $this->_table['en-en']['::::INFORMATION'] = 'Message';
        $this->_table['es-co']['::::INFORMATION'] = 'Mensaje';
        $this->_table['fr-fr']['::::WARN'] = 'ATTENTION !';
        $this->_table['en-en']['::::WARN'] = 'WARNING!';
        $this->_table['es-co']['::::WARN'] = '¡ADVERTENCIA!';
        $this->_table['fr-fr']['::::ERROR'] = 'ERREUR !';
        $this->_table['en-en']['::::ERROR'] = 'ERROR!';
        $this->_table['es-co']['::::ERROR'] = '¡ERROR!';
    }
}
