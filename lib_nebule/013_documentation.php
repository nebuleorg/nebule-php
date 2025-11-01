<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Documentation class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Documentation extends Functions
{
	/*protected function _initialisation(): void
    {
		// Nothing
	}*/

	public function display_content(): void
    {
?>

<h1>Table des matières</h1>
<ul>
    <?php
        Documentation::echoDocumentationTitles();
        ?>

    <li><a href="#o">O / Objet</a>
        <ul>

        <?php
        HelpNode::echoDocumentationTitles();
        HelpGroup::echoDocumentationTitles();
        HelpEntity::echoDocumentationTitles();
        HelpLocalisation::echoDocumentationTitles();
        HelpConversation::echoDocumentationTitles();
        HelpCurrency::echoDocumentationTitles();
        HelpTokenPool::echoDocumentationTitles();
        HelpToken::echoDocumentationTitles();
        HelpWallet::echoDocumentationTitles();
        HelpApplications::echoDocumentationTitles();
        ?>

        </ul>
    </li>

        <?php
        HelpBlocLink::echoDocumentationTitles();
        HelpLinkRegister::echoDocumentationTitles();
        HelpTransaction::echoDocumentationTitles();
        HelpSocial::echoDocumentationTitles();
        HelpCrypto::echoDocumentationTitles();
        HelpMetrology::echoDocumentationTitles();
        ?>

</ul>

<?php
        Documentation::echoDocumentationCore();
        HelpNode::echoDocumentationCore();
        HelpGroup::echoDocumentationCore();
        HelpEntity::echoDocumentationCore();
        HelpLocalisation::echoDocumentationCore();
        HelpConversation::echoDocumentationCore();
        HelpCurrency::echoDocumentationCore();
        HelpTokenPool::echoDocumentationCore();
        HelpToken::echoDocumentationCore();
        HelpWallet::echoDocumentationCore();
        HelpApplications::echoDocumentationCore();
        HelpBlocLink::echoDocumentationCore();
        HelpLinkRegister::echoDocumentationCore();
        HelpTransaction::echoDocumentationCore();
        HelpSocial::echoDocumentationCore();
        HelpCrypto::echoDocumentationCore();
        HelpMetrology::echoDocumentationCore();
    }

    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#f">F / Fondations</a></li>

        <?php
    }

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 'f', 'Fondations'); ?>
        <p>Le projet nebule est un moteur de gestion d'objets utilisant une combinaison des principes de
            <a href="https://en.wikipedia.org/wiki/Distributed_hash_table">DHT</a> et de
            <a href="https://en.wikipedia.org/wiki/Resource_Description_Framework">RDF</a>.
            Cela peut être vu comme une forme de <a href="https://en.wikipedia.org/wiki/Graph_database">base de donnée
            graphe</a> orienté de type <a href="https://en.wikipedia.org/wiki/Hypergraph">hypergraphe</a>.</p>
        <p>Les objets sont les contenants de tout ce que l'on peut manipuler sous forme numérique.
            Les objets, tels les sommets d'un graphe, sont liés par des liens, tel les arêtes du graphe.
            Cette base de données graphe implémente une cryptographie évolutive afin de faire émerger de la confiance
            dans les objets manipulés.
            Mais le côté technique n'est pas une fin en soi. La forme des liens permet de faire émerger un côté social,
            c'est-à-dire du relationnel entre les humains, et permet ainsi d'apporter du sens aux objets et aux liens
            qui les relient.</p>

        <?php
	}
}
