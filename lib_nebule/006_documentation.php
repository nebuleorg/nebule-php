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
        Modules::echoDocumentationTitles();
        ?>

        </ul>
    </li>

        <?php
        blocLink::echoDocumentationTitles();
        Link::echoDocumentationTitles();
        Transaction::echoDocumentationTitles();
        Crypto::echoDocumentationTitles();
        Social::echoDocumentationTitles();
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
        blocLink::echoDocumentationCore();
        Link::echoDocumentationCore();
        Transaction::echoDocumentationCore();
        Crypto::echoDocumentationCore();
        Social::echoDocumentationCore();
    }

    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles()
    {
        ?>

        <li><a href="#f">F / Fondations</a></li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationCore()
    {
        ?>

        <h1 id="f">F / Fondations</h1>
        <p>Le projet nebule est un moteur de gestion d'objets utilisant une combinaison des principes de
            <a href="https://en.wikipedia.org/wiki/Distributed_hash_table">DHT</a> et de
            <a href="https://en.wikipedia.org/wiki/Resource_Description_Framework">RDF</a>.
            Cela peut être vu comme une forme de <a href="https://en.wikipedia.org/wiki/Graph_database">base de donnée
            graphe</a> orienté de type <a href="https://en.wikipedia.org/wiki/Hypergraph">hypergraphe</a>.</p>
        <p>Les objets sont les contenants de tout ce que l'on peut manipuler sous forme numérique.
            Les objets, tel les sommets d'un graphe, sont liés par des liens, tel les arêtes du graphe.
            Cette base de données graphe implémente une cryptographie évolutive afin de faire émerger de la confiance
            dans les objets manipulés.
            Mais le côté technique n'est pas une fin en soi. La forme des liens permet de faire émerger un côté social,
            c'est-à-dire du relationnel entre les humains, et permet ainsi d'apporter du sens aux objets et aux liens
            qui les relient.</p>
        <p></p>

        <?php
	}
}
