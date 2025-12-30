<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use const Nebule\Bootstrap\LIB_ARG_BOOTSTRAP_BREAK;
use const Nebule\Bootstrap\LIB_ARG_FLUSH_SESSION;
use const Nebule\Bootstrap\LIB_ARG_INLINE_DISPLAY;
use const Nebule\Bootstrap\LIB_ARG_RESCUE_MODE;
use const Nebule\Bootstrap\LIB_ARG_SWITCH_APPLICATION;
use const Nebule\Bootstrap\LIB_ARG_UPDATE_APPLICATION;
use const Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE;
use const Nebule\Bootstrap\LIB_LOCAL_LINKS_FOLDER;

/**
 * L'interface applicationInterface
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 */
interface ApplicationInterface {
    public function __construct(nebule $nebuleInstance);
    public function __toString(): string;
    public function __sleep(): array;
    public function __wakeup();

    public function initialisation(): void;
    public function getClassName(): string;
    public function getName(): string;
    public function getNebuleInstance(): ?nebule;
    public function getDisplayInstance(): ?Displays;
    public function getTranslateInstance(): ?Translates;
    public function getMetrologyInstance(): ?Metrology;
    public function getActionInstance(): ?Actions;
    // TODO
}



abstract class HelpApplications {
    /**
     * Display the menu part of the documentation.
     * Including modules.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#oa">OA / Application</a>
            <ul>
                <li><a href="#oaf">OAF / Fonctionnement</a></li>
                <li><a href="#oan">OAN / Nommage</a></li>
                <li><a href="#oap">OAP / Protection</a></li>
                <li><a href="#oad">OAD / Dissimulation</a></li>
                <li><a href="#oal">OAL / Liens</a></li>
                <li><a href="#oac">OAC / Création</a></li>
                <li><a href="#oas">OAS / Stockage</a></li>
                <li><a href="#oat">OAT / Transfert</a></li>
                <li><a href="#oar">OAR / Réservation et références</a></li>
                <li><a href="#oai">OAI / Interface</a>
                    <ul>
                        <li><a href="#oain">OAIN / Nommage</a></li>
                        <li><a href="#oaip">OAIP / Protection</a></li>
                        <li><a href="#oaid">OAID / Dissimulation</a></li>
                        <li><a href="#oail">OAIL / Liens</a></li>
                        <li><a href="#oaic">OAIC / Création</a>
                            <ul>
                                <li><a href="#oaicr">OAICR / Référence</a>
                                <li><a href="#oaicc">OAICC / Code</a>
                                <li><a href="#oaice">OAICE / Enregistrement</a>
                            </ul>
                        </li>
                        <li><a href="#oaiu">OAIU / Mise à Jour</a></li>
                        <li><a href="#oais">OAIS / Stockage</a></li>
                        <li><a href="#oait">OAIT / Transfert</a></li>
                        <li><a href="#oair">OAIR / Réservation et références</a></li>
                        <li><a href="#oaig">OAIG / Applications d'Interfaçage Génériques</a>
                            <ul>
                                <li><a href="#oaigb">OAIGB / Nb - bootstrap</a></li>
                                <li><a href="#oaiga">OAIGA / Au - authen</a></li>
                                <li><a href="#oaige">OAIGE / En - entity</a></li>
                                <li><a href="#oaigs">OAIGS / Sy - sylabe</a></li>
                                <li><a href="#oaigk">OAIGK / Kl - klicty</a></li>
                                <li><a href="#oaigm">OAIGM / Me - messae</a></li>
                                <li><a href="#oaign">OAIGN / Ne - neblog</a></li>
                                <li><a href="#oaigq">OAIGQ / Qa - qantion</a></li>
                                <li><a href="#oaigo">OAIGO / No - option</a></li>
                                <li><a href="#oaigu">OAIGU / Nu - upload</a></li>
                            </ul>
                        </li>
                        <li><a href="#oaio">OAIO / Implémentation des Options</a></li>
                        <li><a href="#oaia">OAIA / Implémentation des Actions</a></li>
                    </ul>
                </li>
                <li><a href="#oab">OAB / Bootstrap</a>
                    <ul>
                        <li><a href="#oabd">OABD / Description</a></li>
                        <li><a href="#oabi">OABI / Installation</a></li>
                        <li><a href="#oabf">OABF / Premier démarrage</a></li>
                        <li><a href="#oabm">OABM / Commandes</a></li>
                        <li><a href="#oabc">OABC / Configuration</a></li>
                        <li><a href="#oabb">OABB / Interruption</a></li>
                        <li><a href="#oabe">OABE / Applications externes</a></li>
                        <li><a href="#oabn">OABN / Fonctionnement nominal</a></li>
                    </ul>
                </li>
                <li><a href="#oal">OAL / Librairie</a>
                    <ul>
                        <li><a href="#oald">OALD / Description</a></li>
                        <li><a href="#oalc">OALC / Configuration</a></li>
                        <li><a href="#oala">OALA / Applications intégrées</a>
                            <ul>
                                <li><a href="#oala0">OALA0 / Application 0</a></li>
                                <li><a href="#oala1">OALA1 / Application 1</a></li>
                                <li><a href="#oala2">OALA2 / Application 2</a></li>
                                <li><a href="#oala3">OALA3 / Application 3</a></li>
                                <li><a href="#oala4">OALA4 / Application 4</a></li>
                                <li><a href="#oala5">OALA5 / Application 5</a></li>
                                <li><a href="#oala6">OALA6 / Application 6</a></li>
                                <li><a href="#oala7">OALA7 / Application 7</a></li>
                                <li><a href="#oala8">OALA8 / Application 8</a></li>
                                <li><a href="#oala9">OALA9 / Application 9</a></li>
                            </ul>
                        </li>
                        <li><a href="#oalt">OALT / Actions</a></li>
                        <li><a href="#oalz">OALZ / Mode sommeil</a></li>
                    </ul>
                </li>
                <?php \Nebule\Library\HelpModules::echoDocumentationTitles(); ?>
            </ul>
        </li>

        <?php
    }

    /**
     * Display the core documentation part.
     * Including modules.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(2, 'oa', 'Application'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Une application permet d'interagir avec les objets et liens.</p>
        <p>Une application qui ne fait que lire des objets et liens, ou retransmettre des liens déjà signés, est dite
            passive. Si l'application à la capacité de générer des liens signés, donc avec une entité déverrouillée,
            alors elle est dite active.</p>
        <p>Si l'entité d'une instance d'application est par défaut et automatiquement déverrouillée, donc active, alors
            c'est aussi un robot. Le déverrouillage de cette entité peut cependant bénéficier de protections
            particulières.</p>

        <?php Displays::docDispTitle(3, 'oaf', 'Fonctionnement'); ?>
        <p>Dans la construction du code, il y a quatre niveaux. Chaque niveau de code est constitué d’un et un seul
            objet nebule ou fichier utilisé. Une seule application est utilisée à un instant donné, mais il peut y avoir
            plusieurs modules utilisés par l’application. Les niveaux :</p>
        <ul>
            <li>le bootstrap, fichier ;</li>
            <li>la librairie en PHP orienté objet, objet ;</li>
            <li>une application au choix, objet ou intégré ;</li>
            <li>des modules au choix, facultatifs, objets.</li>
        </ul>
        <p>Les applications sont toutes construites sur le même modèle et dépendent (extend) toutes des mêmes classes de
            l’application de référence dans la librairie nebule.</p>
        <p>Chaque application doit mettre en place les constantes personnalisées :</p>
        <ul>
            <li>APPLICATION_NAME</li>
            <li>APPLICATION_SURNAME</li>
            <li>APPLICATION_AUTHOR</li>
            <li>APPLICATION_VERSION</li>
            <li>APPLICATION_LICENCE</li>
            <li>APPLICATION_WEBSITE</li>
            <li>APPLICATION_NODE</li>
            <li>APPLICATION_CODING</li>
            <li>USE_MODULES</li>
            <li>USE_MODULES_TRANSLATE</li>
            <li>USE_MODULES_EXTERNAL</li>
            <li>LIST_MODULES_INTERNAL</li>
            <li>LIST_MODULES_EXTERNAL</li>
        </ul>
        <p>Chaque application doit mettre en place les classes :</p>
        <ul>
            <li>Application</li>
            <li>Display</li>
            <li>Action</li>
            <li>Traduction</li>
        </ul>
        <p>Elles dépendent respectivement des classes de l’application de référence Applications, Displays, Actions et
            Traductions dans la librairie nebule.</p>
        <p>Les applications peuvent gérer des modules pour les rendre plus souples dans leur fonctionnement et
            adaptatives. CF <a href="#oam">OAM</a>.</p>

        <?php Displays::docDispTitle(3, 'oan', 'Nommage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oap', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oad', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oal', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oac', 'Création'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oas', 'Stockage'); ?>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(3, 'oat', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oar', 'Réservation et références'); ?>
        <p>Pas d'objet réservé spécifiquement pour les applications. Cependant, les applications d'interface en ont, CF <a href="#oair">OAIR</a>.</p>

        <?php Displays::docDispTitle(3, 'oai', 'Interface'); ?>
        <p>Une interface est un programme dédié aux interactions entre deux milieux différents.</p>
        <p>Une interface permet à une entité, c'est-à-dire un utilisateur ou un robot, d'interagir avec une application.
            Cela peut être vu comme une extension de l'application.</p>
        <p>Les applications développées dans le cadre de <i>nebule</i> :</p>
        <ul>
            <li><b>bootstrap</b> : le chargeur initial de la librairie et des applications, <a href="#oaigb">OAIGB</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>autent</b> : l’application d'authentification interactive, <a href="#oaiga">OAIGA</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>autent</b> : l’application de gestion des entités, <a href="#oaige">OAIGE</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>sylabe</b> : l’application de référence des possibilités de nebule, <a href="#oaigs">OAIGS</a>,
                <a href="http://blog.sylabe.org">blog.sylabe.org</a>.
            </li>
            <li><b>klicty</b> : l’application de partage d’objets à durée limitée, <a href="#oaigk">OAIGK</a>,
                <a href="http://blog.klicty.org">blog.klicty.org</a>.
            </li>
            <li><b>messae</b> : l’application de gestion des conversations et messages, <a href="#oaigm">OAIGM</a>,
                <a href="http://blog.messae.org">blog.messae.org</a>.
            </li>
            <li><b>neblog</b> : l’application de gestion des web logs, <a href="#oaign">OAIGN</a>,
                <a href="http://blog.neblog.org">blog.neblog.org</a>.
            </li>
            <li><b>qantion</b> : l’application de gestion des monnaies, <a href="#oaigq">OAIGQ</a>,
                <a href="http://blog.qantion.org">blog.qantion.org</a>.
            </li>
            <li><b>option</b> : l’application de gestion des options, <a href="#oaigo">OAIGO</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
            <li><b>upload</b> : l’application de chargement de mises à jour, <a href="#oaigu">OAIGU</a>,
                <a href="http://blog.nebule.org">blog.nebule.org</a>.
            </li>
        </ul>
        <div class="layout-main">
            <div class="layout-content">
                <div id="appslist">
                    <div class="apps" style="background:#000000;"><span class="appstitle">Nb</span><br/><span
                            class="appsname">break</span></div>
                    <div class="apps" style="background:#000000;"><span class="appstitle">N0</span><br/><span
                            class="appsname">defolt</span></div>
                    <div class="apps" style="background:#333333;"><span class="appstitle">N3</span><br/><span
                            class="appsname">doctech</span></div>
                    <div class="apps" style="background:#902060;"><span class="appstitle">Au</span><br/><span
                            class="appsname">autent</span></div>
                    <div class="apps" style="background:#c02030;"><span class="appstitle">Sy</span><br/><span
                            class="appsname">sylabe</span></div>
                    <div class="apps" style="background:#d0b020;"><span class="appstitle">Kl</span><br/><span
                            class="appsname">klicty</span></div>
                    <div class="apps" style="background:#2060a0;"><span class="appstitle">Me</span><br/><span
                            class="appsname">messae</span></div>
                    <div class="apps" style="background:#05c3dd;"><span class="appstitle">Ne</span><br/><span
                            class="appsname">neblog</span></div>
                    <div class="apps" style="background:#20a040;"><span class="appstitle">Qa</span><br/><span
                            class="appsname">qantion</span></div>
                    <div class="apps" style="background:#555555;"><span class="appstitle">Op</span><br/><span
                            class="appsname">option</span></div>
                    <div class="apps" style="background:#666666;"><span class="appstitle">Up</span><br/><span
                            class="appsname">upload</span></div>
                </div>
            </div>
        </div>

        <?php Displays::docDispTitle(4, 'oain', 'Nommage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaip', 'Protection'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaid', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oail', 'Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaic', 'Création'); ?>
        <p>La création d'une application se passe en trois parties. Il faut créer un objet de référence de la nouvelle
            application. Il faut lui affecter un objet de code, objet de code qui sera mise à jour plus tard. Enfin il
            faut enregistrer l'application pour la rendre disponible.</p>

        <?php Displays::docDispTitle(5, 'oaicr', 'Référence'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette partie est à faire au début lorsque l’on veut rendre visible et utiliser la nouvelle application. Elle
            ne sera plus refaite par la suite. Le but est de permettre au <i>bootstrap</i> de retrouver l’application et
            de permettre à l’utilisateur de la sélectionner.</p>
        <div class="layout-main">
            <div class="layout-content">
                <div id="appslist">
                    <div class="apps" style="background:#c02030;"><span class="appstitle">Sy</span><br/><span
                            class="appsname">sylabe</span></div>
                </div>
            </div>
        </div>
        <p>On définit un objet de référence, un objet qui sera virtuel puisqu'il n’aura pas de contenu.
            Sa seule contrainte forte est que l’empreinte est exprimée en hexadécimal. Par convention, il est recommandé
            que la taille de l’empreinte des objets virtuels soit comprise en 129 et 191 bits. Cet objet de référence
            peut être généré aléatoirement ou au contraire avoir un contenu pré-déterminé, ou mixer les deux.</p>
        <p>Chaque application doit avoir un objet de référence qui lui est réservé. Utiliser l’objet de référence d’une
            autre application revient à tenter de mettre à jour l’application, non à en faire une nouvelle.</p>
        <p>Par exemple avec la commande : <code>openssl rand -hex 36</code></p>
        <p>Cela donne une valeur, notre objet de référence, qui ressemble à ça :</p>
        <code>f3c2e389d0ec1bd3f279410748ba352c205ca354cec396a5f9fa0f8c0dcc1f9900bfd9</code>
        <p>Pour finir avec l’objet de référence, la couleur de l’application dépend de lui. Cette couleur étant
            constituée des 6 premiers caractères de l’empreinte de l’objet de référence, il est possible de choisir
            volontairement cette couleur.</p>
        <p>L’application doit avoir un nom et un préfixe. Ces deux propriétés sont utilisées par le bootstrap pour
            l’affichage des applications dans l’application de sélection des applications.</p>
        <p>Le nom est libre, mais si il est trop grand, il sera tronqué pour tenir dans le carré de l’application.</p>
        <p>Le préfixe doit faire 2 caractères. Si ce sont des lettres, systématiquement la première sera transformée en
            majuscule et la deuxième en minuscule.</p>
        <p>Par exemple :</p>
        <ul>
            <li>sylabe</li>
            <li>Sy</li>
        </ul>

        <p>Lorsque l’on a défini notre objet de référence et le nom de l’application, on crée les liens.</p>
        <p>Liste des liens à générer lors de la création d'une application interface.</p>
        <ul>
            <li>Le lien de hash :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash du nom de l’algorithme de prise d’empreinte</li>
                    <li>NID3 : hash(‘nebule/objet/hash’)</li>
                </ul>
            </li>
            <li>Le lien de définition de type application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(‘nebule/objet/interface/web/php/applications’)</li>
                    <li>NID3 : hash(‘nebule/objet/type’)</li>
                </ul>
            </li>
            <li>Le lien de nommage long de l'application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(nom long de l'application)</li>
                    <li>NID3 : hash(‘nebule/objet/nom’)</li>
                </ul>
            </li>
            <li>Le lien de nommage court de l'application :
                <ul>
                    <li>REQ : <code>l</code></li>
                    <li>NID1 : ID de l'application</li>
                    <li>NID2 : hash(nom court de l'application)</li>
                    <li>NID3 : hash(‘nebule/objet/surnom’)</li>
                </ul>
            </li>
        </ul>
        <p>Pour que ces liens soient reconnus par le bootstrap, ils doivent tous être signés d’une autorité locale.</p>

        <?php Displays::docDispTitle(5, 'oaicc', 'Code'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La création de la base d’une application est simple, il suffit de copier le modèle d’application dans un
            nouveau fichier et dans un premier temps d’adapter les variables et la fonction d’affichage.</p>
        <p>Ensuite, ce fichier doit être nébulisé, c'est-à-dire transféré vers le serveur comme nouvel objet.</p>
        <p>Une fois nébulisé, l’objet peut être déclaré par un lien comme code pour l’objet de référence de
            l’application. Ainsi, l'objet référence point un code à exécuter.</p>
        <p>Le lien de pointage du code :</p>
        <ul>
            <li>REQ : <code>f</code></li>
            <li>NID1 :</li>
            <li>NID2 :</li>
            <li>NID3 :</li>
        </ul>

        <p>Exemple de modèle d'application :</p>
        <pre>
&lt;?php
declare(strict_types=1);
namespace Nebule\Application\share;
use Nebule\Library\Metrology;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\References;
use Nebule\Library\Translates;

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

class Application extends Applications
{
    const APPLICATION_NAME = 'share';
    const APPLICATION_SURNAME = 'nebule/share';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020250111';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2025';
    const APPLICATION_WEBSITE = 'www.neblog.org';
    const APPLICATION_NODE = '70428bfe9e818c0140c06fd681a669370158f1290e589594e9397e567b020796cb29.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array('module_neblog', 'module_lang_fr-fr');
    const LIST_MODULES_EXTERNAL = array();
// ------------------------------------------------------------------------------------------

class Application extends Applications
{
	/**
	 * Constructeur.
	 *
	 * @param nebule $nebuleInstance
	 * @return void
	 */
	public function __construct(nebule $nebuleInstance)
	{
		$this->_nebuleInstance = $nebuleInstance;
	}

	// Tout par défaut.
}

class Display extends Displays
{
	const DEFAULT_LOGO_BOOTSTRAP = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaX
HeAAABMElEQVR42u3ZQWqDQBTG8Tdp6VjEda5gyBnEeIkcxZ20mRuVrt0MIrhKyB3chFoIhULBalfZhCy6kBmI/7dVnPHHm3E+VC
LyKjOuhcy8AAAAAAAAAAAAAAAAAAAAAJhjPU7xkHEcX3xMXim1894Bvl5+qrHZAwAAAAAAfH+KfI6thP8CLAEAyAJkAbIAewAAAA
AAAFmADgAAALIAWYAswB4AAAAAAEAWoAMAAIAsMGEVRbEyxmz/c29VVYckSd5czOtBRDYuBrLWfmitP+M4XoZh+Hzrnr7vf621+z
zPbdu2P3cFICJSluUpCIKvLMvWt67XdX1M0/Td1cs7B7h0wjAMp2sEl23vFeB6OWitn1y3vXeAy3KIoui767rOGNM0TXP2cprkJM
hBCAAAAJhx/QGiUnc0nJCIeAAAAABJRU5ErkJggg==';

	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}



	/**
	 * Affichage de la page.
	 */
	public function display()
	{
		?>
&lt;!DOCTYPE html>
&lt;html>
	&lt;body>
		Hello
	&lt;/body>
&lt;/html>
&lt;?php
	}
}

class Action extends Actions
{
	const ACTION_APPLY_DELAY = 5;
	/**
	 * Constructeur.
	 *
	 * @param Applications $applicationInstance
	 * @return void
	 */
	public function __construct(Applications $applicationInstance)
	{
		$this->_applicationInstance = $applicationInstance;
	}



	/**
	 * Traitement des actions génériques.
	 */
	public function genericActions()
	{
		$this->_metrology->addLog('Generic actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

		// Rien.

		$this->_metrology->addLog('Generic actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
	}



	/**
	 * Traitement des actions spéciales, qui peuvent être réalisées sans entité déverrouillée.
	 */
	public function specialActions()
	{
		$this->_metrology->addLog('Special actions', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

		// Rien.

		$this->_metrology->addLog('Special actions end', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');
	}
}

class Traduction extends Traductions
{
	/**
	 * Initialisation de la table de traduction.
	 */
    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::INFO' => 'Information',
            '::OK' => 'OK',
            '::INFORMATION' => 'Message',
            '::WARN' => 'ATTENTION !',
            '::ERROR' => 'ERREUR !',
        ],
        'en-en' => [
            '::INFO' => 'Information',
            '::OK' => 'OK',
            '::INFORMATION' => 'Message',
            '::WARN' => 'WARNING!',
            '::ERROR' => 'ERROR!',
        ],
        'es-co' => [
            '::INFO' => 'Information',
            '::OK' => 'OK',
            '::INFORMATION' => 'Mensaje',
            '::WARN' => '¡ADVERTENCIA!',
            '::ERROR' => '¡ERROR!',
        ],
    ];
}
</pre>

        <?php Displays::docDispTitle(5, 'oaice', 'Enregistrement'); ?>
        <p>Enregistrement d'une application :</p>
        <ul>
            <li>Enregistrement de la référence de l'application :
                <ul>
                    <li>action : <code>f</code></li>
                    <li>nid1 : RID applications</li>
                    <li>nid2 : IID application</li>
                    <li>nid3 : OID type PHP</li>
                    <li>nid4 : NID code branch</li>
                </ul>
            </li>
            <li>Lien entre la référence de l'application et l'objet contenant le code :
                <ul>
                    <li>action : <code>f</code></li>
                    <li>nid1 : IID application</li>
                    <li>nid2 : OID application</li>
                    <li>nid3 : NID code branch</li>
                </ul>
            </li>
            <li>Type de l'objet contenant le code :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : OID application</li>
                    <li>nid2 : OID type PHP</li>
                    <li>nid3 : RID type mime</li>
                </ul>
            </li>
            <li>Activation de l'application :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : IID application</li>
                    <li>nid2 : RID activation application</li>
                </ul>
            </li>
            <li>Nom long de l'application :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : IID application</li>
                    <li>nid2 : OID nom long</li>
                    <li>nid3 : RID nommage long</li>
                </ul>
            </li>
            <li>Type de l'objet contenant le nom long de l'application :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : OID nom long</li>
                    <li>nid2 : OID type texte</li>
                    <li>nid3 : RID type mime</li>
                </ul>
            </li>
            <li>Nom court de l'application :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : IID application</li>
                    <li>nid2 : OID nom court</li>
                    <li>nid3 : RID nommage court</li>
                </ul>
            </li>
            <li>Type de l'objet contenant le nom raccourci de l'application :
                <ul>
                    <li>action : <code>l</code></li>
                    <li>nid1 : OID nom court</li>
                    <li>nid2 : OID type texte</li>
                    <li>nid3 : RID type mime</li>
                </ul>
            </li>
            <li>Légende :
                <ul>
                    <li>RID applications : référence commune pour retrouver les applications.</li>
                    <li>IID applications : référence interne, unique, de l'application.</li>
                    <li>OID applications : objet contenant le code de l'application.</li>
                    <li>OID type PHP : objet contenant le type mime du code (<code>application/x-httpd-php</code>).</li>
                    <li>OID type texte : objet contenant le type mime du code (<code>text/plain</code>).</li>
                    <li>RID type mime : objet de référence du type mime (<code>nebule/objet/type</code>).</li>
                    <li>NID code branch : référence unique du niveau de développement, la branche, du code.</li>
                    <li>RID activation application : référence pour l'activation de l'application.</li>
                    <li>OID nom long : objet contenant le nom long de l'application.</li>
                    <li>OID nom court : objet contenant le nom raccourci (deux caractères) de l'application.</li>
                    <li>RID nommage long : objet de référence du nommage long des objets (<code>nebule/objet/nom</code>).</li>
                    <li>RID nommage court : objet de référence du nommage raccourci des objets (<code>nebule/objet/surnom</code>).</li>
                </ul>
            </li>
        </ul>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaiu', 'Mise à Jour'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oais', 'Stockage'); ?>
        <p>Voir <a href="#oos">OOS</a>, pas de particularité de stockage.</p>

        <?php Displays::docDispTitle(4, 'oait', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oair', 'Réservation et références'); ?>
        <p>Les objets réservés spécifiquement pour les applications :</p>
        <p style="color: red; font-weight: bold">Objets réservés obsolètes !</p>
        <ul>
            <li>nebule/objet/interface/web/php/bootstrap</li>
            <li>nebule/objet/interface/web/php/bibliotheque</li>
            <li>nebule/objet/interface/web/php/applications</li>
            <li>nebule/objet/interface/web/php/applications/direct</li>
            <li>nebule/objet/interface/web/php/applications/active</li>
        </ul>
        <p>Les références :</p>
        <ul>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP; ?> : Référence pour retrouver le bootstrap.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_BIBLIOTHEQUE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_BIBLIOTHEQUE; ?> : Référence pour retrouver la bibliothèque (libPOO).</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS; ?> : Référence pour retrouver les applications.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT; ?> : Référence pour désigner les applications sans pré-chargement.</li>
            <li>REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE=<?php echo References::REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE; ?> : Référence pour désigner les applications activées.</li>
        </ul>

        <?php Displays::docDispTitle(4, 'oaig', "Applications d'Interfaçage Génériques"); ?>
        <p>Ces applications sont développées dans le cadre de <i>nebule</i> et sont librement mises à disposition (sous
            license).</p>
        <p>Le nom de ces applications est toujours en minuscule.</p>

        <?php Displays::docDispTitle(5, 'oaigb', 'Nb - bootstrap'); ?>
        <p>voir <a href="#oab">OAB</a></p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaiga', 'Au - authen'); ?>
        <p>IID=9020606a70985a00f1cf73e6aed5cfd46399868871bd26d6c0bd7a202e01759c3d91b97e.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaige', 'En - entity'); ?>
        <p>IID=206090aec4ba9e2eaa66737d34ced59cfe73b8342fc020efbd321eded7c8b46440e0875a.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigs', 'Sy - sylabe'); ?>
        <p>IID=c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigk', 'Kl - klicty'); ?>
        <p>IID=d0b02052a575f63a4e87ff320df443a8b417be1b99e8e40592f8f98cbd1adc58c221d501.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigm', 'Me - messae'); ?>
        <p>IID=2060a0d21853a42093f01d2e4809c2a5e9300b4ec31afbaf18af66ec65586d6c78b2823a.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaign', 'Ne - neblog'); ?>
        <p>IID=05c3dd94a9ae4795c888cb9a6995d1e5a23b43816e2e7fb908b6841694784bc3ecda8adf.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigq', 'Qa - qantion'); ?>
        <p>IID=20a04016698cd3c996fa69e90bbf3e804c582b8946a5d60e9880cdb24b36b5d376208939.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigo', 'No - option'); ?>
        <p>IID=555555712c23ff20740c50e6f15e275f695fe95728142c3f8ba2afa3b5a89b3cd0879211.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'oaigu', 'Nu - upload'); ?>
        <p>IID=6666661d0923f08d50de4d70be7dc3014e73de3325b6c7b16efd1a6f5a12f5957b68336d.none.288</p>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaio', 'Implémentation des Options'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oaia', 'Implémentation des Actions'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'oab', 'Bootstrap'); ?>
        <?php Displays::docDispTitle(4, 'oabd', 'Description'); ?>
        <p>Le <i>bootstrap</i> est un programme autonome de <i>nebule</i> constitué d'un seul et unique fichier écrit en
            PHP.</p>
        <p>Il constitue le point d'entrée par défaut de toute connexion des utilisateurs ou robots vers l'instance,
            c'est-à-dire une localisation précise sur un serveur Web sur lequel on se connecte avec une URL dédiée.</p>
        <p>Il est en charge lors du premier appel de retrouver et télécharger tout le nécessaire à la nouvelle instance
            depuis Internet.</p>
        <p>Il est aussi en charge de trouver les dernières versions de la bibliothèque
            <?php echo \Nebule\Library\nebule::NEBULE_NAME; ?> de manière sécurisée.</p>
        <p>Il gère en interne une page de description de l'instance.</p>
        <p>Il va ensuite normalement charger la bibliothèque qui permet de gérer un certain nombre d'applications
            intégrées ou externes. Ces applications sont des interfaces entre les informations stockées dans les objets
            et reliées par des liens.</p>

        <?php Displays::docDispTitle(4, 'oabi', 'Installation'); ?>
        <p>Le <i>bootstrap</i> nécessite pour fonctionner un serveur Web avec PHP pré-installé. Il peut être hébergé sur
            le site principal, dans un sous-domaine ou un sous-dossier de site Web. Il nécessite le droit d'écriture sur
            le dossier le contenant au moins le temps du premier démarrage.</p>
        <p>Procédure :</p>
        <ul>
            <li>Installer un serveur Web (Linux/Apache) avec PHP. Aucun serveur de base de données n'est utilisé.</li>
            <li>Télécharger le fichier <i>bootstrap</i> sur le site Web
                <a href="<?php echo \Nebule\Library\nebule::NEBULE_WEBSITE; ?>">
                    <?php echo \Nebule\Library\nebule::NEBULE_WEBSITE; ?></a>.
                <span style="color: red; font-weight: bold">FIXME téléchargement sécurisé !</span></li>
            <li>Positionner le fichier <i>bootstrap</i> dans le dossier Web du serveur Web. Au besoin, renommer le
                fichier en <i>index.php</i>.</li>
            <li>Donner les droits de lecture et d'écriture (755 groupe apache) sur le dossier Web contenant le fichier
                <i>bootstrap</i> et le droit de lecture sur le fichier <i>index.php</i> (644).</li>
            <li>Ouvrir un navigateur Web et donner en URL l'adresse du serveur Web.</li>
            <li>La partie premier démarrage du <i>bootstrap</i> est appelée. Voir <a href="#oabf">OABF</a>.</li>
            <li>Entrer un EID et une localisation pour le <i>puppetmaster</i> ou laisser celui par défaut. Valider.</li>
            <li>Entrer un EID et une localisation pour l'entité de subordination ou laisser celle par défaut.
                Valider.</li>
            <li>Noter l'empreinte EID de la nouvelle entité de l'instance et son mot de passe.</li>
            <li>Si tout se passe bien, on arrive sur l'application 1 de selection des applications.</li>
            <li>L'instance est prête.</li>
        </ul>
        <p>Après le premier démarrage, les droits de lecture sont suffisants sauf pour les dossiers <i>o</i> et <i>l</i>
            qui nécessitent le droit d'écriture.</p>
        <p>Il est possible sur une instance de ne laisser que les droits de lecture sur toute l'arborescence. Cependant,
            cela empêchera toute interaction nécessitant la création d'objets ou de liens. Il est préférable dans ce cas
            de positionner l'option <b>permitWrite</b> à <i>false</i> dans le fichier de configuration. Voir
            <a href="#cco">CCO</a> pour changer la configuration.</p>

        <?php Displays::docDispTitle(4, 'oabf', 'Premier démarrage'); ?>
        <p>Lors du premier démarrage (firstboot), c'est-à-dire lorsque l'on appelle le <i>bootstrap</i> via le serveur
            Web PHP, il va se charger de préparer l'environnement nécessaire au bon fonctionnement d'une instance
            <i>nebule</i>.
            Cela va inclure :</p>
        <ul>
            <li>La création d'un fichier <i><?php echo References::CONFIGURATION_FILE; ?></i> de
                configuration générique.</li>
            <li>La création d'un dossier <i><?php echo References::OBJECTS_FOLDER; ?></i> pour
                stocker les objets.</li>
            <li>La création d'un dossier <i><?php echo References::LINKS_FOLDER; ?></i> pour
                stocker les liens.</li>
            <li>La génération d'une entité locale, dite entité de l'instance.</li>
            <li>La création d'un fichier <i><?php echo References::LOCAL_ENTITY_FILE; ?></i>
                contenant l'empreinte <i>EID</i> de l'entité de l'instance.</li>
            <li>La création de différents objets dans le dossier des objets.</li>
            <li>La création de différents liens dans le dossier des liens.</li>
        </ul>
        <p>Au besoin, un certain nombre de ces dossiers et fichiers peuvent être pré-initialisé lorsque l'on dépose le
            <i>bootstrap</i> sur le serveur Web. Mais cela n'est pas le fonctionnement normal.</p>

        <?php Displays::docDispTitle(4, 'oabm', 'Commandes'); ?>
        <p>Il est possible d'interagir avec le <i>bootstrap></i> au moyen de commandes dans l'URL.
            Certaines commandes listées sont sur un digit et ont été historiquement dans le <i>bootstrap</i> mais sont
            maintenant déportées dans la bibliothèque.</p>
        <p>Liste des commandes :</p>
        <ul>
            <li><b><?php echo LIB_ARG_SWITCH_APPLICATION; ?></b> :
                Sélection d'une application.</li>
            <li><b><?php echo LIB_ARG_BOOTSTRAP_BREAK; ?></b> :
                Interruption du <i>bootstrap</i>.
                Voir <a href="#oabb">OABB</a>.</li>
            <li><b><?php echo LIB_LOCAL_ENTITY_FILE; ?></b> :
                Affichage de l'empreinte EID de l'entité de l'instance.</li>
            <li><b><?php echo LIB_ARG_FLUSH_SESSION; ?></b> :
                Réinitialisation de la session, suppression du cache, fermeture des applications et entités.</li>
            <li><b><?php echo LIB_ARG_INLINE_DISPLAY; ?></b> :
                Affichage en mode page intégrée d'une application. Ce n'est pas supporté partout.</li>
            <li><b><?php echo LIB_LOCAL_LINKS_FOLDER; ?></b> :
                Dans l'application 4, désigne le NID dont on veut afficher les blocs de liens.
                Non reconnu ailleurs.
                Voir <a href="#oala4">OALA4</a>.
                (hors bootstrap)</li>
            <li><b><?php echo LIB_ARG_RESCUE_MODE; ?></b> :
                Charge en mode restreint pour dépannage.</li>
            <li><b><?php echo References::COMMAND_TOKEN; ?></b> :
                Transmet un ticket qui doit être valide pour permettre certaines actions.
                (hors bootstrap)</li>
            <li><b><?php echo References::COMMAND_SWITCH_GHOST; ?></b> :
                Change l'entité en cours d'impersonation. (hors bootstrap)</li>
            <li><b><?php echo References::COMMAND_SWITCH_CONNECTED; ?></b> :
                Change l'entité connectée en cours. (hors bootstrap)</li>
            <li><b><?php echo References::COMMAND_PASSWORD; ?></b> :
                Mot de passe de l'entité. (hors bootstrap)</li>
            <li><b><?php echo LIB_ARG_UPDATE_APPLICATION; ?></b> :
                Demande une mise à jour des applications et du cache associé.</li>
            <li><b>bootstrapfirstpuppetmastereid</b> :
                Dans le <i>firstboot</i>, donne l'EID de puppetmaster.
                Non reconnu ailleurs.</li>
            <li><b>bootstrapfirstpuppetmasterlocation</b> :
                Dans le <i>firstboot</i>, donne la localisation de téléchargement de puppetmaster.
                Non reconnu ailleurs.</li>
            <li><b>bootstrapfirstsubordinationeid</b> :
                Dans le <i>firstboot</i>, donne l'EID de l'entité de subordination.
                Non reconnu ailleurs.</li>
            <li><b>bootstrapfirstsubordinationlocation</b> :
                Dans le <i>firstboot</i>, donne la localisation de téléchargement de l'entité de subordination.
                Non reconnu ailleurs.</li>
        </ul>
        <p><b>ATTENTION</b> : si la bibliothèque ne peut être chargée, le <i>bootstrap</i> renverra systématiquement sur
            la page d'interruption ! Voir <a href="#oabb">OABB</a>.</p>

        <?php Displays::docDispTitle(4, 'oabc', 'Configuration'); ?>
        <p>Le <i>bootstrap</i> obéit aux mêmes options que la bibliothèque et les applications. Voir
            <a href="#cco">CCO</a>.</p>

        <?php Displays::docDispTitle(4, 'oabb', 'Interruption'); ?>
        <p>La présence de la commande <i><?php echo LIB_ARG_BOOTSTRAP_BREAK; ?></i> sur l'URL déclenche l'affichage
            d'une page d'interruption dédiée du <i>bootstrap</i>.</p>
        <p>En cas de problème lors de l'initialisation du <i>bootstrap</i> ou de la bibliothèque, le <i>bootstrap</i>
            renverra systématiquement sur la page d'interruption avec une erreur.</p>
        <p>Si la bibliothèque ne peut être chargée, quelque soit les arguments passés, le <i>bootstrap</i> renverra
            systématiquement sur la page d'interruption avec une erreur.</p>
        <p>Le résultat est une page contenant en partie centrale, par exemple :</p>
        <pre>
#1 bootstrap break on
- [11] user interrupt
tB=0.1473s
? Flush PHP session (20ef2d)
#2 bootstrap
bootstrap RID         : fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288
bootstrap BID         : 81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288
bootstrap CID         : a9e420daf12bc21278317e180fd51460fa786f275a2923d7a7b0cb0ac9c1ee2f.sha2.256
bootstrap IID         : 304f4431cd011211e8fbb57081cd8f1609a25a46ab30476e4b3bffb90d47e73832374176.none.288
bootstrap OID         : e45853f1709a6b296fa9237f43a494721ffe6e7ca66a34c65da400a483ecd1de.sha2.256 OK
bootstrap SID         : e2b35f9d064e9175ad845ed8261c20c8467f1673fe41112f5a5797e29224e493.sha2.256
#3 nebule library PP
library version       : 020251012
puppetmaster          : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256
security authority    : fe3bb546bafa81bb4316402f436b35f86c74a509b3e171d6365060cd44387c24.sha2.256
code authority        : e2b35f9d064e9175ad845ed8261c20c8467f1673fe41112f5a5797e29224e493.sha2.256
directory authority   : 5451784e6a448f744f56ce6f9c8afdab1473634529cb3f2d4843ba3e1724be9f.sha2.256
time authority        : be7b4b52afcecc65bf8d743ff3b85b61d5abc2ddeb8c4b31af2c756bf8578849.sha2.256
server entity         : 33fb69b18286bdbf7cbc66a377a72f29cc57493916d0668e7031998b26b26039.sha2.256
default entity        : 88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256
ghost entity          : 88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256
code branch           : 81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288 (develop)
php version           : found 8.2.29, need >= 8.0.0
#4 nebule library POO
tL=0.2825s
library RID           : 780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288
library IID           : 21f6396e921e4373a91d70d13895b04a359316fc269a1c0dc9268a71419ecfb41e88d58d.none.288
library OID           : 6fe12abb07c6be3231e2a7d6970b6ce264106f1dc2888db80c41d4e85fbac1f0.sha2.256 version 020251011
library SID           : e2b35f9d064e9175ad845ed8261c20c8467f1673fe41112f5a5797e29224e493.sha2.256
functional level      : found 020241123, need >= 020250928
puppetmaster          : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
security authority    : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
code authority        : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
directory authority   : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
time authority        : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
server entity         : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
default entity        : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
ghost entity          : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
connected entity      : cf5ae12e8a97f7150a689c16734c926a35890d634625e129f8d62b5166eb1209.sha2.256 OK (local authority) (global authority)
subordination         : 0 OK
cryptography class    : Nebule\Library\CryptoOpenssl
cryptography          : hash sha2.256 OK
cryptography          : symmetric aes.256.ctr OK
cryptography          : asymmetric rsa.2048 OK
cryptography          : pseudo-random entropy 7.8967484073741/8 OK
i/o class             : Nebule\Library\io
i/o                   : Nebule\Library\ioDisk (RW) file://, links OK, objects OK
i/o                   : Nebule\Library\ioNetworkHTTP (RO) http://code.master.nebule.org, links OK no write., objects OK no write.
i/o                   : Nebule\Library\ioNetworkHTTPS (RO) https://code.master.nebule.org, links OK no write., objects OK no write.
social class          : Nebule\Library\Social
social                : Nebule\Library\SocialMySelf OK
social                : Nebule\Library\SocialNotMyself OK
social                : Nebule\Library\SocialSelf OK
social                : Nebule\Library\SocialNotself OK
social                : Nebule\Library\SocialAuthority OK
social                : Nebule\Library\SocialAll OK
social                : Nebule\Library\SocialNone OK
social                : Nebule\Library\SocialOnList OK
social                : Nebule\Library\SocialOffList OK
social                : Nebule\Library\SocialReputation OK
social                : Nebule\Library\SocialUnreputation OK
metrology inputs      : Lr=463+153 Lv=463+0 Or=471+1 Ov=471+1 (PP+POO)
metrology buffers     : Lc=0 Oc=5 Ec=2 Gc=0 Cc=0 CUc=0 CPc=0 CTc=0 CWc=0
#5 application
name space            : Nebule\Library
application RID       : 4046edc20127dfa1d99f645a7a4ca3db42e94feffa151319c406269bd6ede981c32b96e2.none.288
application IID       : 1 load
application OID       : 1
application SID       : /
#6 end bootstrap
tE=0.5937s
        </pre>
        <p>Les codes d'interruption :</p>
        <ul>
            <li>[00] unknown buggy interrupt reason</li>
            <li>[11] user interrupt</li>
            <li>[21] library init error</li>
            <li>[22] library i/o : link's folder error</li>
            <li>[23] library i/o : link's folder error</li>
            <li>[24] library i/o : object's folder error</li>
            <li>[25] library i/o : object's folder error</li>
            <li>[31] library load : finding library IID error</li>
            <li>[32] library load : finding library OID error</li>
            <li>[41] library load : find code error</li>
            <li>[42] library load : include code error</li>
            <li>[43] library load : functional version too old</li>
            <li>[44] library load : load error</li>
            <li>[45] application : find code error</li>
            <li>[46] application : include code error</li>
            <li>[47] application : load error</li>
            <li>[51] unknown bootstrap hash</li>
            <li>[61] no local server entity</li>
            <li>[62] local server entity error</li>
            <li>[71] need sync puppetmaster</li>
            <li>[72] need sync authorities of security</li>
            <li>[73] need sync authorities of security</li>
            <li>[74] need sync authorities of code</li>
            <li>[75] need sync authorities of code</li>
            <li>[76] need sync authorities of time</li>
            <li>[77] need sync authorities of time</li>
            <li>[78] need sync authorities of directory</li>
            <li>[79] need sync authorities of directory</li>
            <li>[81] library init : I/O open error</li>
            <li>[82] library init : puppetmaster error</li>
            <li>[83] library init : security authority error</li>
            <li>[84] library init : code authority error</li>
            <li>[85] library init : time authority error</li>
            <li>[86] library init : directory authority error</li>
        </ul>
        <p>Table d'utilisabilité des entités calculée par la bibliothèque orienté objet (entities error level) :</p>
        <ul>
            <li>1 : L'entité puppet n'est pas une entité.</li>
            <li>2 : L'entité puppet a un EID=0.</li>
            <li>3 : L'EID de puppet n'est pas celui de la configuration.</li>
            <li>11 : La liste des autorités de sécurité est vide.</li>
            <li>12 : Une des autorités de sécurité n'est pas une entité.</li>
            <li>13 : Une des autorités de sécurité a un EID=0.</li>
            <li>21 : La liste des autorités du code est vide.</li>
            <li>22 : Une des autorités du code n'est pas une entité.</li>
            <li>23 : Une des autorités du code a un EID=0.</li>
            <li>31 : La liste des autorités de l'annuaire est vide.</li>
            <li>32 : Une des autorités de l'annuaire n'est pas une entité.</li>
            <li>33 : Une des autorités de l'annuaire a un EID=0.</li>
            <li>41 : La liste des autorités du temps est vide.</li>
            <li>42 : Une des autorités du temps n'est pas une entité.</li>
            <li>43 : Une des autorités du temps a un EID=0.</li>
            <li>51 : L'entité instance n'est pas une entité.</li>
            <li>52 : L'entité instance a un EID=0.</li>
            <li>61 : L'entité courante n'est pas une entité.</li>
            <li>62 : L'entité courante a un EID=0.</li>
            <li>128 : Toutes les entités utilisées sont bonnes.</li>
        </ul>
        <p>Les temps de chargement (voir <a href="#mc">MC</a>) :</p>
        <ul>
            <li>tB : Temps de chargement du boostrap après la bibliothèque PP.</li>
            <li>tE : Temps de fin de chargement de page d'interruption.</li>
        </ul>
        <p style="color: red; font-weight: bold">À compléter avec la description des lignes...</p>

        <?php Displays::docDispTitle(4, 'oabe', 'Applications externes'); ?>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Pour les applications externes, le RID est l'objet de référence de l'application. Chaque application dispose
            d'un RID unique avec une valeur assez longue pour éviter toute collision avec une autre application.</p>
        <p>Voir <a href="#oa">OA</a>, <a href="#oail">OAIL</a> et <a href="#oabn">OABN</a>.</p>

        <?php Displays::docDispTitle(4, 'oabn', 'Fonctionnement nominal'); ?>
        <p>Le <i>bootstrap</i> mémorise la dernière application utilisée, externe ou intégrée, et va représenter la même
            application à l'utilisateur jusqu'à déconnexion de la session à l'instance ou vidage du cache ou problème.
            C'est le fonctionnement nominal pour un utilisateur.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Tant que la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?></i> n'est pas de nouveau utilisée, on
            reste sur la même application.</p>
        <p>La recherche de l'application à utiliser par le <i>bootstrap</i> est faite dans l'ordre :</p>
        <ul>
            <li>1 : Si présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?></i>, il essaie de trouver
                l'application correspondante.</li>
            <li>2 : Si une application est mémorisée dans le cache, il la charge.</li>
            <li>3 : Sinon il charge l'application par défaut. C'est équivalent à
                <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=1</i>.</li>
        </ul>
        <p>Voir <a href="#oail">OAIL</a>.</p>

        <?php Displays::docDispTitle(3, 'oal', 'Librairie'); ?>
        <?php Displays::docDispTitle(4, 'oald', 'Description'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'oalc', 'Configuration'); ?>
        <p>La bibliothèque obéit aux mêmes options que le <i>bootstrap</i> et les applications. Voir
            <a href="#cco">CCO</a>.</p>

        <?php Displays::docDispTitle(4, 'oala', 'Applications intégrées'); ?>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=RID</i> sur l'URL permet de passer
            vers l'application référencée par ce RID.
            Pour les applications internes, le RID est l'objet de référence interne de l'application sur un seul
            chiffre (0 à 9).</p>
        <p> Toutes les applications internes ne sont pas actives et donc ne sont pas accessibles. Certaines
            applications internes peuvent être bloquées par des options de configuration.</p>
        <p>Une application intégrée peut être appelée par défaut via l'option de configuration <b>defaultApplication</b>
            par exemple à <i>1</i>.</p>
        <p>Ci-dessous les différentes applications.</p>

        <?php Displays::docDispTitle(5, 'oala0', 'Application 0'); ?>
        <p>C'est l'application par défaut pour les instances sans interaction. La page Web délivrée est minimaliste.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=0</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès ne peut pas être bloqué.</p>

        <?php Displays::docDispTitle(5, 'oala1', 'Application 1'); ?>
        <p>C'est l'application de sélection et de navigation et les différentes applications.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=1</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication1</b> à <i>false</i> ou un lien équivalent.</p>

        <?php Displays::docDispTitle(5, 'oala2', 'Application 2'); ?>
        <p>C'est une application minimale d'authentification des utilisateurs sur les entités.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=2</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication2</b> à <i>false</i> ou un lien équivalent.</p>

        <?php Displays::docDispTitle(5, 'oala3', 'Application 3'); ?>
        <p>C'est l'application d'affichage de la documentation technique.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=3</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication3</b> à <i>false</i> ou un lien équivalent.</p>

        <?php Displays::docDispTitle(5, 'oala4', 'Application 4'); ?>
        <p>C'est une application qui permet de voir de façon simplifiée les blocs de liens.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=4</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication4</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Elle est désactivée par défaut.</p>

        <?php Displays::docDispTitle(5, 'oala5', 'Application 5'); ?>
        <p>Son accès peut être bloqué par l'option <b>permitApplication5</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <?php Displays::docDispTitle(5, 'oala6', 'Application 6'); ?>
        <p>Son accès peut être bloqué par l'option <b>permitApplication6</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <?php Displays::docDispTitle(5, 'oala7', 'Application 7'); ?>
        <p>Son accès peut être bloqué par l'option <b>permitApplication7</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <?php Displays::docDispTitle(5, 'oala8', 'Application 8'); ?>
        <p>Son accès peut être bloqué par l'option <b>permitApplication8</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Non utilisé, redirigé vers l'application 0.</p>

        <?php Displays::docDispTitle(5, 'oala9', 'Application 9'); ?>
        <p>C'est une application utilisée pour la mise à l'arrêt de l'instance, comme une mise en sommeil, dans le but
            de permettre à un autre programme externe de manipuler les objets et liens. Cette mise en sommeil peut être
            utilisée afin de faire sans entrave le ménage, supprimer et réorganiser les données.</p>
        <p>La présence de la commande <i><?php echo LIB_ARG_SWITCH_APPLICATION; ?>=9</i> sur l'URL déclenche l'affichage
            de cette page dédiée de la <i>bibliothèque</i>.</p>
        <p>Son accès peut être bloqué par l'option <b>permitApplication9</b> à <i>false</i> ou un lien équivalent.</p>
        <p>Elle est désactivée par défaut.</p>
        <p>Cette application n'a pas de protection dans son usage et ne doit être activée que sur une instance à usage
            privée.</p>

        <?php Displays::docDispTitle(4, 'oalt', 'Actions'); ?>
        <p>Les actions sont les interactions qui amènent à la modification de liens et/ou d'objets.
            Il y a deux types d'actions :</p>
        <ul>
            <li>Actions génériques : ce sont toutes les actions basiques qui nécessitent cependant qu'une entité soit
                déverrouillée et un jeton valide.</li>
            <li>Actions spéciales : ce sont toutes les actions un peu particulières qui doivent pouvoir être réalisées
                sans entité déverrouillée et/ou sans jeton valide. Un certain nombre de contrôles supplémentaires sont
                nécessaires dans ce cas avant de réaliser l'action.</li>
        </ul>
        <p>Cette distinction se retrouve dans la bibliothèque et dans les applications, mais pas dans les modules.</p>

        <?php Displays::docDispTitle(4, 'oalz', 'Mode sommeil'); ?>
        <p>À définir...</p>

        <?php HelpModules::echoDocumentationCore();
    }
}
