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
     *
     * @return void
     */
    protected function _initialisation(): void
    {
//        $this->_nebuleInstance->getMetrologyInstance()->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_cacheCurrentEntityUnlocked = $this->_entitiesInstance->getConnectedEntityIsUnlocked();

        if ($this->_id != '0') {
            $this->_localisation = trim(strtolower($this->_ioInstance->getObject($this->_id)));
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
     *
     * @param string $url
     * @param bool   $protected
     * @param bool   $obfuscated
     * @return bool
     */
    public function setNewLocalisation(string $url, bool $protected = false, bool $obfuscated = false): bool
    {
        $this->_localisation = trim(strtolower($url));
        $this->_parseURL();
        if ($this->_protocol == '') {
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
        if ($this->_configurationInstance->getOptionAsBoolean('permitLocalisationStats')) {
            return false;
        }

        // @todo
    }
}



abstract class HelpLocalisation {
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

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(2, 'ol', 'Localisation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <p>Une localisation permet de trouver l’emplacement des objets et liens générés par une entité.</p>
        <p>Un emplacement n’a de sens que pour une entité.</p>
        <p>Une entité peut disposer de plusieurs localisations. Il faut considérer que toute entité qui héberge l’objet
            d’une autre entité devient de fait une localisation valide même si cela n’est pas explicitement définit.</p>

        <?php Displays::docDispTitle(3, 'oln', 'Nommage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'olp', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'old', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oll', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'olc', 'Création'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Liste des liens à générer lors de la création d'une localisation.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'ols', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(3, 'olt', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'olr', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'olio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'olia', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php
    }
}
