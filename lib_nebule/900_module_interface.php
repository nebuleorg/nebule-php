<?php
declare(strict_types=1);
namespace Nebule\Library;

interface ModuleInterface {
    public function __construct(nebule $nebuleInstance);
    public function __toString(): string;

    public function getClassName(): string;
    public function getHookList(string $hookName, ?Node $instance = null): array;
    public function displayModule(): void;
}

Interface ModuleTranslateInterface {
    public function __construct(nebule $nebuleInstance);
    public function __destruct();
    public function __toString(): string;
}



abstract class HelpModules {
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#oam">OAM / Module</a>
            <ul>
                <li><a href="#oamn">OAMN / Nommage</a></li>
                <li><a href="#oamp">OAMP / Protection</a></li>
                <li><a href="#oamd">OAMD / Dissimulation</a></li>
                <li><a href="#oaml">OAML / Liens</a></li>
                <li><a href="#oamc">OAMC / Création</a></li>
                <li><a href="#oamu">OAMU / Mise à Jour</a></li>
                <li><a href="#oams">OAMS / Stockage</a></li>
                <li><a href="#oamt">OAMT / Transfert</a></li>
                <li><a href="#oamr">OAMR / Réservation et références</a></li>
            </ul>
        </li>

        <?php
    }

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(3, 'oam', 'Module'); ?>
        <p>Le module est une classe enfant de la classe <i>Modules</i>. Cela permet d'étendre les fonctionnalités d'une
            application. Un module peut être par défaut présent dans une application, c'est-à-dire présent dans l'objet
            de l'application. Dans ce cas son nom doit être présent dans la liste des modules intégrés à l'application.
        </p>
        <p>Il y a plusieurs types de modules dans une application. :</p>
        <ul>
            <li>Le type interne (internal) correspond aux modules intégrés dans l'objet de l'application.</li>
            <li>Le type externe (external) correspond uax modules non intégrés dans l'objet de l'application. Ils sont
                détectés par des liens dédiés et chargés puis instanciés par l'application.</li>
            <li>Le type traduction (translate) correspond aux modules externes à l'application et dédiés à la
                traduction, mais avec des capacités réduites.</li>
        </ul>
        <p>Pour qu'une application puisse utiliser des modules, elle doit permettre l'utilisation des modules. De façon
            globale, des options permettent d'utiliser des modules ou non, elles sont prioritaires sur le choix des
            applications.</p>
        <p>Pour activer les modules, internes et/ou externes, dans la classe <i>Application</i> d'une application, il
            faut positionner la constante <b>USE_MODULES = true</b>. L'option <i>permitApplicationModules</i> doit être
            à true. Les modules internes sont intégrés par défaut dans l'objet d'une application. Pour être utilisé, ils
            doivent tous être déclarés dans la constance <b>LIST_MODULES_INTERNAL</b> sous forme d'une liste.</p>
        <p>Les modules externes peuvent être pris en compte via le lien des modules si la constance
            <b>USE_MODULES_EXTERNAL = true</b> en plus de la <b>USE_MODULES = true</b>. Les options
            <i>permitApplicationModules</i> et <i>permitApplicationModulesExternal</i> doivent être à true.</p>
        <p>Les modules externes de traduction peuvent être pris en compte via le lien des modules si la constance
            <b>USE_MODULES_TRANSLATE = true</b> en plus de la <b>USE_MODULES = true</b>. Ces modules n'ont aucun code
            exécuté et exposent uniquement un tableau avec des traductions. Les options <i>permitApplicationModules</i>
            et <i>permitApplicationModulesTranslate</i> doivent être à true.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamn', 'Nommage'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamp', 'Protection'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamd', 'Dissimulation'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oaml', 'Liens'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamc', 'Création'); ?>
        <p>Un module est une classe enfant de la classe <i>Modules</i> ou de la classe <i>ModuleTranslates</i>.</p>
        <p>Les modules sont chargés par la classe <i>Applications</i> dont hérite toutes les applications. </p>
        <p>Liste des liens à générer lors de la création d'un module.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamu', 'Mise à Jour'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oams', 'Stockage'); ?>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(4, 'oamt', 'Transfert'); ?>
        <p>A faire...</p>

        <?php Displays::docDispTitle(4, 'oamr', 'Réservation et références'); ?>
        <p>Pas d'objet réservé spécifiquement pour les modules d'applications.</p>
        <p>Les références :</p>
        <ul>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES; ?> : Référence pour retrouver les modules d'une application.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_TRANSLATE; ?> : Référence pour retrouver les modules de traduction d'une application.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES_ACTIVE; ?> : Référence pour retrouver les modules activés.</li>
        </ul>

        <?php
    }
}
