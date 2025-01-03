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
        Node::echoDocumentationTitles();
        Group::echoDocumentationTitles();
        Entity::echoDocumentationTitles();
        Localisation::echoDocumentationTitles();
        Conversation::echoDocumentationTitles();
        Currency::echoDocumentationTitles();
        TokenPool::echoDocumentationTitles();
        Token::echoDocumentationTitles();
        Wallet::echoDocumentationTitles();
        Applications::echoDocumentationTitles();
        ?>

        </ul>
    </li>

        <?php
        BlocLink::echoDocumentationTitles();
        LinkRegister::echoDocumentationTitles();
        Transaction::echoDocumentationTitles();
        Crypto::echoDocumentationTitles();
        Social::echoDocumentationTitles();
        Metrology::echoDocumentationTitles();
        ?>

</ul>

<?php
        Documentation::echoDocumentationCore();
        Node::echoDocumentationCore();
        Group::echoDocumentationCore();
        Entity::echoDocumentationCore();
        Localisation::echoDocumentationCore();
        Conversation::echoDocumentationCore();
        Currency::echoDocumentationCore();
        TokenPool::echoDocumentationCore();
        Token::echoDocumentationCore();
        Wallet::echoDocumentationCore();
        Applications::echoDocumentationCore();
        Modules::echoDocumentationCore();
        BlocLink::echoDocumentationCore();
        LinkRegister::echoDocumentationCore();
        Transaction::echoDocumentationCore();
        Crypto::echoDocumentationCore();
        Social::echoDocumentationCore();
        Metrology::echoDocumentationCore();
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
