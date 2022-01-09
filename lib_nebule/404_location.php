<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\Node;

/**
 * ------------------------------------------------------------------------------------------
 * La classe Localisation.
 * @todo à revoir complètement !
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant l'ID de la localisation ou la localisation.
 *
 * La localisation est forcément un texte et est passée automatiquement en minuscule.
 * ------------------------------------------------------------------------------------------
 */
class Localisation extends Node
{
    private $_localisation = '', $_protocol = '', $_communication, $_ioDefaultPrefix = '';

    public function __construct(nebule $nebuleInstance, string $id, string $localisation = '')
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_social = $nebuleInstance->getSocialInstance();
        $this->_ioDefaultPrefix = $this->_io->getDefaultLocalisation();
        $id = trim(strtolower($id));
        $this->_metrology->addLog('New instance localisation ' . $id, Metrology::LOG_LEVEL_DEBUG); // Métrologie.

        // Vérifie sommairement la localisation.
        if (is_string($id) && $id != '0' && $id != '' && ctype_xdigit($id)) {
            $this->_id = $id;
            // Extrait la localisation et la convertit en minuscule.
            $this->_localisation = trim(strtolower($this->_io->objectRead($id)));
        } elseif (is_string($id) && $id == '0') {
            // Crée le nouvel objet.
            $this->_createNewObject($localisation);
            // Définit la licalisation, en minuscule.
            $this->_localisation = trim(strtolower($localisation));
        } else {
            // La localisation n'est pas valide.
            $this->_id = '0';
            $this->_localisation = '';
        }

        // Extrait le type de protocole.
        // Si invalide ou non reconnu, la variable du protocole est vide.
        if (substr($this->_localisation, 0, 7) == 'http://' || substr($this->_localisation, 0, 8) == 'https://') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'HTTP';
        } elseif (substr($this->_localisation, 0, 5) == 'mail:') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'SMTP';
        } elseif (substr($this->_localisation, 0, 5) == 'xmpp:') {
            // @todo Test la validité de l'adresse.
            $this->_protocol = 'XMPP';
        } else {
            $this->_protocol = '';
        }
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_localisation;
    }

    // Synchronise l'objet avec l'ID donné si non présent localement.
    public function syncObjectID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // @todo
    }

    // Synchronise les liens.
    public function syncLinksID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        // @todo
    }

    // Synchronise à la fois les liens et l'objet avec l'ID donné.
    public function syncID($id)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        $this->syncLinksID($id);
        $this->syncObjectID($id);
    }


    private function _addPonderate($time)
    {
        $this->_metrology->addLog(__METHOD__ . ' ' . $this->_id, Metrology::LOG_LEVEL_FUNCTION); // Log

        if ($this->_configuration->getOptionUntyped('permitLocalisationStats')) {
            return false;
        }

        // @todo
    }


    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#ol">OL / Localisation</a>
            <ul>
                <li><a href="#oln">OLN / Nommage</a></li>
                <li><a href="#olp">OLP / Protection</a></li>
                <li><a href="#old">OLD / Dissimulation</a></li>
                <li><a href="#oll">OLL / Liens</a></li>
                <li><a href="#olc">OLC / Création</a></li>
                <li><a href="#ols">OLS / Stockage</a></li>
                <li><a href="#olt">OLT / Transfert</a></li>
                <li><a href="#olr">OLR / Réservation</a></li>
                <li><a href="#olio">OLIO / Implémentation des Options</a></li>
                <li><a href="#olia">OLIA / Implémentation des Actions</a></li>
            </ul>
        </li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    public function echoDocumentationCore()
    {
        ?>

        <h2 id="ol">OL / Localisation</h2>
        <p>A faire...</p>
        <p>Une localisation permet de trouver l’emplacement des objets et liens générés par une entité.</p>
        <p>Un emplacement n’a de sens que pour une entité.</p>
        <p>Une entité peut disposer de plusieurs localisations. Il faut considérer que toute entité qui héberge l’objet
            d’une autre entité devient de fait une localisation valide même si cela n’est pas explicitement définit.</p>

        <h3 id="oln">OLN / Nommage</h3>
        <p>A faire...</p>

        <h3 id="olp">OLP / Protection</h3>
        <p>A faire...</p>

        <h3 id="old">OLD / Dissimulation</h3>
        <p>A faire...</p>

        <h3 id="oll">OLL / Liens</h3>
        <p>A faire...</p>

        <h3 id="olc">OLC / Création</h3>
        <p>Liste des liens à générer lors de la création d'une localisation.</p>
        <p>A faire...</p>

        <h3 id="ols">OLS / Stockage</h3>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <h3 id="olt">OLT / Transfert</h3>
        <p>A faire...</p>

        <h3 id="olr">OLR / Réservation</h3>
        <p>A faire...</p>

        <h4 id="olio">OLIO / Implémentation des Options</h4>
        <p>A faire...</p>

        <h4 id="olia">OLIA / Implémentation des Actions</h4>
        <p>A faire...</p>

        <?php
    }
}
