<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * L'interface SocialInterface.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
interface SocialInterface
{
    public function __toString();

    /**
     * Retourne le type de système de fichiers.
     *
     * @return string
     */
    //public function getType(): string;

    /**
     * Retourne l'état de préparation'.
     *
     * @return bool
     */
    public function getReady(): bool;

    public function arraySocialFilter(array &$links, string $socialClass = ''): void;
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float;
    public function setList(array $listID, string $socialClass = ''): bool;
    public function unsetList(string $socialClass = ''): bool;
}



abstract class HelpSocial {
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#s">S / Social</a></li>

        <?php
    }

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 's', 'Social'); ?>
        <p>Dans la reconnaissance et la confiance dans les liens que l'on va partager, mais surtout que l'on va
            recevoir, il faut pouvoir faire le tri. Les liens définissants les relations entre les objets, un certain
            nombre de règles sont définie pour pondérer, et donc sélectionner, des liens plutôt que d'autres. Le système
            de pondération fonctionne sur la base de modèles de classification dites sociales, c'est-à-dire sur la
            façon dont les personnes, ici les entités, interagissent.</p>
        <p>Il y a un certain nombre de classifications implémentées dans la bibliothèque :</p>
        <ul>
            <li><b>myself</b> : Classe de gestion du côté social des liens limités à l'entité en cours.</li>
            <li><b>notmyself</b> : Classe de gestion du côté social des liens limités à tout sauf l'entité en cours.</li>
            <li><b>self</b> : Classe de gestion du côté social des liens limités à l'entité en cours d'affichage.
                Ce peut être une entité différente de l'entité déverrouillée.</li>
            <li><b>notself</b> : Classe de gestion du côté social des liens limités à tout sauf l'entité en cours
                d'affichage. Ce peut être une entité différente de l'entité déverrouillée.</li>
            <li><b>authority</b> : Classe de gestion du côté social stricte des liens générés par des autorités locales
                et globales.</li>
            <li><b>all</b> : Classe de gestion du côté social des liens, garde tout.</li>
            <li><b>none</b> : Classe de gestion du côté social des liens, supprime tout.</li>
            <li><b>onlist</b> : Classe de gestion du côté social des liens par rapport à une liste d'ID.
                Ne sélectionne que les liens des entités de la liste.
                La liste doit être pré-alimentée.</li>
            <li><b>offlist</b> : Classe de gestion du côté social des liens par rapport à une liste d'ID.
                Ne sélectionne que les liens des entités qui ne sont pas dans la liste.
                La liste doit être pré-alimentée.</li>
            <li><b>reputation</b> : Classe de gestion du côté social des liens par rapport à la réputation des entités.
                Ne sélectionne que les liens des entités qui sont bien réputées. TODO.</li>
            <li><b>unreputation</b> : Classe de gestion du côté social des liens par rapport à la réputation des entités.
                Ne sélectionne que les liens des entités qui ne sont pas bien réputées. TODO.</li>
        </ul>

        <?php
    }
}
