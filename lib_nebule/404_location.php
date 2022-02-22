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
class Localisation extends Node implements nodeInterface
{
    private $_localisation = '';
    private $_protocol = '';

    /**
     * Specific part of constructor for an entity.
     * @return void
     */
    protected function _localConstruct(): void
    {
        $this->_cacheCurrentEntityUnlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        if ($this->_id != '0')
        {
            $this->_localisation = trim(strtolower($this->_io->getObject($this->_id)));
            $this->_parseURL();
        }
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->_localisation;
    }

    private function _parseURL(): void
    {
        switch (preg_split('/:/', $this->_localisation)[0]) {
            case 'http':
            case 'https':
                // TODO Test la validité de l'adresse.
                $this->_protocol = 'HTTP';
                break;
            /*case 'smtp':
                $this->_protocol = 'SMTP';
                break;
            case 'xmpp':
                $this->_protocol = 'XMPP';
                break;*/
            default:
                $this->_protocol = '';
        }
    }

    /**
     * Create new URL localisation.
     * @param string $url
     * @param bool   $protected
     * @param bool   $obfuscated
     * @return bool
     */
    public function setNewLocalisation(string $url, bool $protected = false, bool $obfuscated = false): bool
    {
        $this->_localisation = trim(strtolower($url));
        $this->_parseURL();
        if ($this->_protocol == '')
        {
            $this->_localisation = '';
            return false;
        }
        return true;
    }

    // Synchronise l'objet avec l'ID donné si non présent localement.
    public function syncObjectID($id)
    {
        // @todo
    }

    // Synchronise les liens.
    public function syncLinksID($id)
    {
        // @todo
    }

    // Synchronise à la fois les liens et l'objet avec l'ID donné.
    public function syncID($id)
    {
        $this->syncLinksID($id);
        $this->syncObjectID($id);
    }

    private function _addPonderate($time)
    {
        if ($this->_configuration->getOptionAsBoolean('permitLocalisationStats')) {
            return false;
        }

        // @todo
    }



    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
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
    static public function echoDocumentationCore(): void
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
