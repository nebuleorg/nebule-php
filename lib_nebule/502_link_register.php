<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\linkInterface;

/**
 * The link register RL: REQ>NID>NID>NID>NID...
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class LinkRegister extends Functions implements linkInterface {
    const SESSION_SAVED_VARS = array(
            '_rawLink',
            '_parsedLink',
            '_parsedLinkObfuscated',
            '_obfuscated',
            '_valid',
            '_validStructure',
            '_permitObfuscated',
            '_maxRLUID',
    );

    protected ?BlocLink $_blocLink = null;
    protected string $_rawLink = '';
    protected array $_parsedLink = array();
    protected array $_parsedLinkObfuscated = array();
    protected bool $_obfuscated = false;
    protected bool $_valid = false;
    protected bool $_validStructure = false;
    protected bool $_permitObfuscated = false;
    protected int $_maxRLUID = 4;

    public function __construct(nebule $nebuleInstance, string $rl, blocLinkInterface $blocLink) {
        parent::__construct($nebuleInstance);
        $this->setEnvironmentLibrary($nebuleInstance);
        $this->_metrologyInstance->addLog('create new link register ' . substr($rl, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        /*if($blocLink->getNew()) {
            $this->_metrologyInstance->addLog('unable to create on an already signed bloc link', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e56f08da');
            return;
        }*/

        $this->_maxRLUID = $this->_configurationInstance->getOptionAsInteger('linkMaxUIDbyRL'); // FIXME ne lit pas correctement la valeur.
        $this->_blocLink = $blocLink;
        $this->_rawLink = $rl;
        $this->_metrologyInstance->addLinkRead();
        $this->_validStructure = $this->_checkRL($rl);

        $this->initialisation();
    }

    public function __toString() { return $this->_rawLink; }

    public function __sleep() { return self::SESSION_SAVED_VARS; }

    public function __wakeup() {
        /*global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrologyInstance = $nebuleInstance->getMetrologyInstance();
        $this->_configurationInstance = $nebuleInstance->getConfigurationInstance();
        $this->_ioInstance = $nebuleInstance->getIoInstance();
        $this->_cryptoInstance = $nebuleInstance->getCryptoInstance();
        $this->_permitObfuscated = (bool)$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink');*/
    }

    protected function _initialisation(): void {
        $this->_permitObfuscated = $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink');
        $this->_obfuscated = false;
        if ($this->_permitObfuscated && $this->_parsedLink['bl/rl/req'] == 'c')
            $this->_extractObfuscated();
    }

    /**
     * Link - Check bloc RL on a link.
     *
     * @param string $rl
     * @return bool
     */
    protected function _checkRL(string $rl): bool {
        //$this->_metrologyInstance->addLog(substr($rl, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($rl) > BlocLink::LINK_MAX_RL_SIZE) {
            $this->_metrologyInstance->addLog('BL/RL size overflow ' . substr($rl, 0, 1000) . '+', Metrology::LOG_LEVEL_ERROR, __METHOD__, '8d33e123');
            return false;
        }

        // Extract items from RL : REQ>NID>NID>NID>NID...
        $req = strtok($rl, '>');
        if (is_bool($req)) {
            $this->_metrologyInstance->addLog('invalid REQ', Metrology::LOG_LEVEL_ERROR, __METHOD__, '380638fc');
            return false;
        }
        if (!$this->_checkREQ($req))
            return false;
        $this->_parsedLink['bl/rl/req'] = $req;

        $rl1nid = strtok('>');
        if (is_bool($rl1nid)) {
            $this->_metrologyInstance->addLog('null NID1', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f4f0eb13');
            return false;
        }

        $list = array();
        $j = 0;
        while (!is_bool($rl1nid)) {
            $list[$j] = $rl1nid;
            $j++;
            if ($j > $this->_maxRLUID) {
                $this->_metrologyInstance->addLog('BL/RL overflow ' . substr($rl, 0, 200) . '+ maxRLUID=' . $this->_maxRLUID, Metrology::LOG_LEVEL_ERROR, __METHOD__, '72920c39');
                return false;
            }
            $rl1nid = strtok('>');
        }
        foreach ($list as $j => $nid) {
            if (!Node::checkNID($nid, $j > 0)) {
                $this->_metrologyInstance->addLog('invalid NID ' . $nid, Metrology::LOG_LEVEL_ERROR, __METHOD__, '40b3486e');
                return false;
            }
            $this->_parsedLink['bl/rl/nid' . ($j + 1)] = $nid;
        }

        $this->_parsedLink['bl/rl'] = $rl;
        $this->_valid = true;
        return true;
    }

    /**
     * Link - Check bloc REQ on a link.
     *
     * @param string $req
     * @return bool
     */
    protected function _checkREQ(string &$req): bool {
        //$this->_metrologyInstance->addLog(substr($req, 0, 5), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($req != 'l'
                && $req != 'f'
                && $req != 'u'
                && $req != 'd'
                && $req != 'e'
                && $req != 'c'
                && $req != 'k'
                && $req != 's'
                && $req != 'x'
        ) {
            $this->_metrologyInstance->addLog('invalid REQ value', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f0deaee8');
            return false;
        }
        return true;
    }

    /**
     * Force bloc instance if not defined.
     * Should be only used on a link's instance wakeup.
     *
     * @param BlocLink $instance
     * @return void
     */
    public function setBlocInstance(BlocLink $instance): void {
        $this->_metrologyInstance?->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!is_null($this->_blocLink))
            return;
        $this->_blocLink = $instance;
    }

    /**
     * {@inheritDoc}
     * @return string
     * @see linkInterface::getRaw()
     */
    public function getRaw(): string {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_rawLink;
    }

    /**
     * Retourne le bloc du lien.
     *
     * @return BlocLink
     */
    public function getBlocLink(): BlocLink{
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_blocLink;
    }

    public function getParsed(): array {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_parsedLink;
    }

    public function getSignersEID(): array {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_blocLink->getSignersEID();
    }

    public function getDate(): string {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_blocLink->getDate();
    }

    /**
     * Retourne l'état de vérification et de validité du lien.
     * Un état transitoire à true existe lors de la vérification initiale du bloc de liens en cours.
     *
     * @return boolean
     */
    public function getValid(): bool {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_valid)
            $this->_metrologyInstance->addLog('get valid is false', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ecbbd1de');
        return ($this->_blocLink->getValid() || !$this->_blocLink->getCheckCompleted()) && $this->_valid;
    }

    /**
     * Retourne l'état de validité de la forme syntaxique du lien.
     *
     * @return boolean
     */
    public function getValidStructure(): bool {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_validStructure)
            $this->_metrologyInstance->addLog('get valid structure is false', Metrology::LOG_LEVEL_ERROR, __METHOD__, '73233c1c');
        return $this->_validStructure;
    }

    /**
     * Retourne si le lien est signé et si la signature est valide.
     *
     * @return boolean
     */
    public function getSigned(): bool {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_blocLink->getSigned())
            $this->_metrologyInstance->addLog('get signed is false', Metrology::LOG_LEVEL_ERROR, __METHOD__, '84c305e5');
        return $this->_blocLink->getSigned();
    }

    /**
     * Retourne si le lien est dissimulé.
     * Dans ce cas les informations retournées sont les informations du lien non dissimulé.
     *
     * @return boolean
     */
    public function getObfuscated(): bool {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_obfuscated;
    }

    /**
     * Retourne la version avec laquelle est exploité le lien.
     *
     * @return string
     */
    public function getVersion(): string {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_blocLink->getVersion();
    }


    /**
     * Extraction de la partie dissimulée du lien.
     * Ne vérifie pas la cohérence ou la validité des champs !
     *
     * @return boolean
     */
    protected function _extractObfuscated(): bool {
        $this->_metrologyInstance->addLog('extract obfuscated part', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_parsedLinkObfuscated = $this->_parsedLink;

        // TODO

        return false;
    }


    /**
     * Offusque le lien. Ne pas oublier de l'écrire.
     *
     * @return boolean
     * TODO
     * Le lien à dissimuler est concaténé avec un bourrage (padding) d'espace de taille aléatoire compris entre 3 et 5 fois la taille du champs source.
     */
    public function setObfuscate(): bool {
        $this->_metrologyInstance->addLog('convert link to obfuscated', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '654de486');
        if (!$this->_obfuscated
                && $this->_valid
                && $this->_permitObfuscated
        ) {
            // TODO
        }

        return false;
    }

    /**
     * Désoffusque le lien. Ne pas oublier de l'écrire.
     *
     * @return boolean
     * TODO
     */
    public function unsetObfuscate(): bool {
        $this->_metrologyInstance->addLog('dis-obfuscate link', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '5e357597');
        if ($this->_obfuscated
                && $this->_valid
        ) {
            // TODO
        }

        return false;
    }

    /**
     * Extrait le lien offusqué.
     *
     * @return boolean
     * TODO
     */
    public function decrypt(): bool {
        $this->_metrologyInstance->addLog(substr($this->_rawLink, 0, 512), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '2c0b785a');
        if ($this->_obfuscated
                && $this->_valid
        ) {
            // TODO
        }
        return false;
    }
}



abstract class HelpLinkRegister {
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#l">L / Registre de lien</a>
            <ul>
                <li><a href="#ls">LS / Structure</a></li>
                <li><a href="#lelpo">LELPO / Liens à Propos d’un Objet</a></li>
                <li><a href="#lelco">LELCO / Liens Contenu dans un Objet</a></li>
                <li><a href="#le">LE / Entête</a></li>
                <li><a href="#lr">LR / Registre</a>
                    <ul>
                        <li><a href="#lrsi">LRSI / Le champ <code>Signature</code></a></li>
                        <li><a href="#lrusi">LRHSI / Le champ <code>HashSignataire</code></a></li>
                        <li><a href="#lrt">LRT / Le champ <code>TimeStamp</code></a></li>
                        <li><a href="#lra">LRA / Le champ <code>Action</code></a>
                            <ul>
                                <li><a href="#lral">LRAL / Action <code>l</code> – Lien entre objets</a></li>
                                <li><a href="#lraf">LRAF / Action <code>f</code> – Dérivé d’objet</a></li>
                                <li><a href="#lrau">LRAU / Action <code>u</code> – Mise à jour d’objet</a></li>
                                <li><a href="#lrad">LRAD / Action <code>d</code> – Suppression d’objet</a></li>
                                <li><a href="#lrae">LRAE / Action <code>e</code> – Équivalence d’objets</a></li>
                                <li><a href="#lrac">LRAC / Action <code>c</code> – Chiffrement de lien</a></li>
                                <li><a href="#lrak">LRAK / Action <code>k</code> – Chiffrement d’objet</a></li>
                                <li><a href="#lras">LRAS / Action <code>s</code> – Subdivision d’objet</a></li>
                                <li><a href="#lrax">LRAX / Action <code>x</code> – Suppression de lien</a></li>
                            </ul>
                        </li>
                        <li><a href="#lrhs">LRHS / Le champ <code>HashSource</code></a></li>
                        <li><a href="#lrhc">LRHC / Le champ <code>HashCible</code></a></li>
                        <li><a href="#lrhm">LRHM / Le champ <code>HashMeta</code></a></li>
                    </ul>
                </li>
                <li><a href="#l1">L1 / Lien simple</a></li>
                <li><a href="#l2">L2 / Lien double</a></li>
                <li><a href="#l3">L3 / Lien triple</a></li>
                <li><a href="#ls">LS / Stockage</a>
                    <ul>
                        <li><a href="#lsa">LSA / Arborescence</a></li>
                        <li><a href="#lsd">LSD / Dissimulation</a>
                            <ul>
                                <li><a href="#lsdrp">LSDRP / Registre public</a></li>
                                <li><a href="#lsdrd">LSDRD / Registre dissimulé</a></li>
                                <li><a href="#lsda">LSDA / Attaque sur la dissimulation</a></li>
                                <li><a href="#lsds">LSDS / Stockage et transcodage</a>
                                    <ul>
                                        <li><a href="#lsdst">LSDST / Translation de lien</a></li>
                                        <li><a href="#lsdsp">LSDSP / Protection de translation</a></li>
                                    </ul>
                                </li>
                                <li><a href="#lsdt">LSDT / Transfert et partage</a></li>
                                <li><a href="#lsdc">LSDC / Compromission</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#lt">LT / Transfert</a></li>
                <li><a href="#lv">LV / Vérification</a></li>
                <li><a href="#lo">LO / Oubli</a></li>
            </ul>
        </li>

        <?php
    }

    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 'l', 'Registre de lien'); ?>
        <p>Le lien est la matérialisation dans un graphe d’une relation entre deux nœuds, généralement des objets.
            Le type de cette relation est définie par un troisième objet, c'est la façon dont on va interpréter
            la relation entre les deux premiers nœuds.
            La relation peut être contextualisée avec un quatrième nœud, ou plus.</p>
        <p>Dans le cas d'un lien à deux ou trois nœuds, nous sommes dans un cas similaire à un
            <a href="https://fr.wikipedia.org/wiki/Th%C3%A9orie_des_graphes">graphe</a> orienté tel qu'on
            le trouve dans une base de données de type graphe. Nous retrouvons ce principe dans le Resource
            Description Framework (<a href="https://fr.wikipedia.org/wiki/Resource_Description_Framework">RDF</a>).
            À une différence près cependant, c'est qu'ici le lien ne contient que des identifiants de nœuds et non des
            contenus.</p>
        <p>Dans le cas d'un lien avec plus de 3 nœuds, nous sommes dans un
            <a href="https://fr.wikipedia.org/wiki/Hypergraphe">hypergraphe</a> orienté. Vu l'usage qu'il est fait des
            nœuds dans les liens, nous pouvons cependant déjà considérer que nous sommes dans un hypergraphe au-delà
            de deux nœuds.</p>
        <p>Le lien est enregistré dans un registre avec un format définit et contraint afin de pouvoir être stocké et
            échangé de façon sûre et sécurisée.</p>
        <p>Le registre de lien ne porte pas son autoprotection. Il est enregistré dans un bloc de liens qui se charge de
            faire cohabiter et de protéger un ou plusieurs liens simultanément. Voir <a href="#b">B</a>.</p>

        <?php Displays::docDispTitle(2, 'ls', 'Structure'); ?>
        <p>Chaque lien est écrit dans ce que l'on appelle un registre de lien. Ce registre va comprendre plusieurs
            champs. Le premier champ est dit requête d'action. Il est suivi par les champs contenant les identifiants
            des nœuds dans l'ordre d'usage (graphe orienté).</p>
        <p>La requête d'action est obligatoire et est unique pour un registre de lien.</p>
        <p>Dans le registre, chaque champ est séparé des autres par le caractère «&nbsp;&gt;&nbsp;». Le nombre de champs
            exploitable, sans compter la requête d'action, est limité par l'option <i>linkMaxUIDbyRL</i>. Si le nombre de
            champs du registre est supérieur à la valeur limite, le lien est déclaré invalide.</p>
        <p>La forme du registre de lien :</p>
        <p align="center"><code>REQ>NID>NID>NID>NID</code></p>

        <?php Displays::docDispTitle(5, 'lelpo', 'Liens à Propos d’un Objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les liens d’un objet sont consultables séquentiellement. Ils doivent être perçus comme des méta-données d’un
            objet.</p>
        <p>Les liens sont séparés soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;». Un
            lien est donc une suite de caractères ininterrompue, c'est-à-dire sans espace ou retour à la ligne.</p>
        <p>La taille du lien dépend de la taille de chaque champ.</p>

        <?php Displays::docDispTitle(5, 'lelco', 'Liens Contenu dans un Objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Certains liens d’un objet peuvent être contenus dans un autre objet.</p>
        <p>Cette forme de stockage des liens permet de les transmettre et de les manipuler sous la forme d’un objet. On
            peut ainsi profiter du découpage et du chiffrement. Plusieurs liens peuvent être stockés sans être
            nécessairement en rapport avec les mêmes objets.</p>
        <p>Les liens stockés dans un objet ne peuvent pas faire référence à ce même objet.</p>
        <p>Tout ajout de lien crée implicitement un nouvel objet de mise à jour, c'est-à-dire lié par un lien de type
            u.</p>
        <p>Chaque fichier contenant des liens doit avoir un entête de version.</p>
        <p>Les objets contenants des liens ne sont pas reconnus et exploités lors de la lecture des liens. Ceux-ci
            doivent d’abord être extraits et injectés dans les liens des objets concernés. En clair, on ne peut pas s’en
            servir facilement pour de l’anonymisation.</p>

        <?php Displays::docDispTitle(2, 'le', 'Entête'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’entête des liens est constitué du texte <code>nebule/liens/version/1.2</code>. Il est séparé du premier
            lien soit par un caractère espace «&nbsp;», soit par un retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Il doit être transmis avec les liens, en premier.</p>

        <?php Displays::docDispTitle(2, 'lr', 'Registre'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien décrit la syntaxe du lien :</p>
        <p style="text-align:center">
            <code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>
        <p>Ce registre a un nombre de champs fixe. Chaque champ a une place fixe dans le lien. Les champs ont une
            taille variable. Le séparateur de champs est l’underscore «&nbsp;_&nbsp;». Les champs ne peuvent contenir ni
            l’underscore «&nbsp;_&nbsp;» ni l’espace &nbsp;» &nbsp;» ni le retour chariot «&nbsp;\n&nbsp;».</p>
        <p>Tout lien qui ne respecte pas cette syntaxe est à considérer comme invalide et à supprimer. Tout lien dont la
            <code>Signature</code> est invalide est à considérer comme invalide et à supprimer. La vérification peut
            être réalisée en réassemblant les champs après nettoyage.</p>

        <?php Displays::docDispTitle(4, 'lrsi', 'Le champ <code>Signature</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>Signature</code> est représenté en deux parties séparées par un point «&nbsp;.&nbsp;» . La
            première partie contient la valeur de la signature. La deuxième partie contient le nom court de la fonction
            de prise d’empreinte utilisée.</p>
        <p>La signature est calculée sur l’empreinte du lien réalisée avec la fonction de prise d’empreinte désignée
            dans la deuxième partie. L’empreinte du lien est calculée sur tout le lien sauf le champs
            <code>signature</code>, c'est-à-dire sur «&nbsp;<code>_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code>&nbsp;»
            avec le premier underscore inclus.</p>
        <p>La signature ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;»
            et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule. La fonction de prise d’empreinte est notée en
            caractères alphanumériques en minuscule.</p>

        <?php Displays::docDispTitle(5, 'lrusi', 'Le champ <code>HashSignataire</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>HashSignataire</code> désigne l’objet de l’entité qui génère le lien et le signe.</p>
        <p>Il ne contient que des caractères hexadécimaux, c'est-à-dire de «&nbsp;0&nbsp;» à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;»
            à «&nbsp;f&nbsp;» en minuscule.</p>

        <?php Displays::docDispTitle(3, 'lrt', 'Le champ <code>TimeStamp</code>'); ?>
        <p>Le champ <code>TimeStamp</code> est une marque de temps qui donne un ordre temporel aux liens. Ce champs peut
            être une date et une heure au format <a class="external text" title="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    href="http://fr.wikipedia.org/wiki/ISO_8601"
                                                    rel="nofollow">ISO8601</a> ou simplement un compteur incrémental.
        </p>

        <?php Displays::docDispTitle(3, 'lra', 'Le champ <code>Action</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>Action</code> détermine la façon dont le lien doit être utilisé.</p>
        <p>Quand on parle du type d’un lien, on fait référence à son champ <code>Action</code>.</p>
        <p>L’interprétation de ce champ est limité au premier caractère. Des caractères alpha-numériques supplémentaires
            sont autorisés, mais ignorés.</p>
        <p>Cette interprétation est basée sur un vocabulaire particulier. Ce vocabulaire est spécifique à <i>nebule
                v1.2</i> (et <i>nebule v1.1</i>).</p>
        <p>Le vocabulaire ne reconnaît que les 8 caractères <code>l</code>, <code>f</code>, <code>u</code>,
            <code>d</code>, <code>e</code>, <code>x</code>, <code>k</code> et <code>s</code>, en minuscule.</p>

        <?php Displays::docDispTitle(4, 'lral', 'Action <code>l</code> – Lien entre objets'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Met en place une relation entre deux objets. Cette relation a un sens de mise en place et peut être pondérée
            par un objet méta.</p>
        <p>Les liens de type <code>l</code> ne devraient avoir ni <code>HashMeta</code> nul ni <code>HashCible</code>
            nul.</p>
        <p><code>l</code> comme <i>link</i>.</p>
        <p>Lors de la lecture de multiples liens <code>l</code>, on ne retient qu'un seul lien. On retient le dernier
            lien en date après filtrage social, éventuellement le dernier en date de l'entité signataire avec le plus
            fort score social.</p>

        <?php Displays::docDispTitle(4, 'lraf', 'Action <code>f</code> – Dérivé d’objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le nouvel objet est considéré comme enfant ou parent suivant le sens du lien.</p>
        <p>Le champs <code>ObjetMeta</code> doit être vu comme le contexte du lien. Par exemple, deux objets contenants
            du texte peuvent être reliés simplement sans contexte, c'est-à-dire reliés de façon simplement hiérarchique.
            Ces deux mêmes textes peuvent être plutôt (ou en plus) reliés avec un contexte comme celui d’une discussion
            dans un blog. Dans ce deuxième cas, la relation entre les deux textes n’a pas de sens en dehors de cette
            discussion sur ce blog. Il est même probable que le blog n’affichera pas les autres textes en relations si
            ils n’ont pas un contexte appartenant à ce blog.</p>
        <p><code>f</code> comme <i>fork</i>.</p>
        <p>Lors de la lecture de multiples liens <code>f</code>, on retient une liste de liens. On peut ne retenir que
            les liens ayant un minimum de score social de leurs entités signataires.</p>

        <?php Displays::docDispTitle(4, 'lrau', 'Action <code>u</code> – Mise à jour d’objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Mise à jour d’un objet dérivé qui remplace l’objet parent.</p>
        <p><code>u</code> comme <i>update</i>.</p>

        <?php Displays::docDispTitle(4, 'lrad', 'Action <code>d</code> – Suppression d’objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’objet est marqué comme à supprimer d’un ou de tous ses emplacements de stockage.</p>
        <p><code>d</code> comme <i>delete</i>.</p>
        <p>Le champs <code>HashCible</code> <span style="text-decoration: underline;">peut</span> être nuls, c'est-à-dire égal à <code>0</code>.
            Si non nul, ce champs doit contenir une entité destinataire de <i>l’ordre</i> de
            suppression. C’est utilisé pour demander à une entité relaie de supprimer un objet spécifique. Cela peut
            être utilisé pour demander à une entité en règle générale de bien vouloir supprimer l’objet, ce qui n’est
            pas forcément exécuté.</p>
        <p>Le champs <code>HashMeta</code> <span style="text-decoration: underline;">doit</span> être nuls, c'est-à-dire
            égal à <code>0</code>.</p>
        <p>Un lien de suppression sur un objet ne veut pas forcément dire qu’il a été supprimé. Même localement, l’objet
            est peut-être encore présent. Si le lien de suppression vient d’une autre entité, on ne va sûrement pas par
            défaut en tenir compte.</p>
        <p>Lorsque le lien de suppression est généré, le serveur sur lequel est généré le lien doit essayer par défaut
            de supprimer l’objet. Dans le cas d’un serveur hébergeant plusieurs entités, un objet ne sera pas supprimé
            si il est encore utilisé par une autre entité, c'est-à-dire si une entité a un lien qui le concerne et n’a
            pas de lien de suppression.</p>

        <?php Displays::docDispTitle(4, 'lrae', 'Action <code>e</code> – Équivalence d’objets'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Définit des objets jugés équivalents, et donc interchangeables par exemple pour une traduction.</p>

        <?php Displays::docDispTitle(4, 'lrac', 'Action <code>c</code> – Chiffrement de lien'); ?>
        <p>Ce lien de dissimulation contient un lien dissimulé sans signature. Il permet d’offusquer des liens entre
            objets et donc d’anonymiser certaines actions de l’entité (cf. <a href="#ckl">CKL</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. À part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet, mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui-même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet, mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entité destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Lors du traitement des liens, si une entité est déverrouillée, les liens offusqués pour cette entité doivent
            être déchiffrés et utilisés en remplacement des liens offusqués originels. Les liens offusqués doivent être
            vérifiés avant déchiffrement. Les liens déchiffrés doivent être vérifiés avant exploitation.</p>
        <p>Les liens de dissimulations posent un problème pour être efficacement utilisés par les entités émeteurs et
            destinataires. Pour résoudre ce problème sans risquer de révéler les identifiants des objets utilisés dans
            un lien dissimulé, les liens de dissimulation sont attachés à des objets virtuels translatés depuis les
            identifiants des objets originaux (cf <a href="#ld">LD</a>).</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des
            objets. Dans ce cas le lien de type <code>c</code> est rejeté comme invalide avec le code erreur 43.</p>

        <?php Displays::docDispTitle(4, 'lrak', 'Action <code>k</code> – Chiffrement d’objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Désigne la version chiffrée de l’objet (cf. <a href="#cko">CKO</a>).</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans
            ce cas le lien de type <code>k</code> est rejeté comme invalide avec le code erreur 42.</p>

        <?php Displays::docDispTitle(4, 'lras', 'Action <code>s</code> – Subdivision d’objet'); ?>
        <p>Désigne un fragment de l’objet.</p>
        <p>Ce champ nécessite un objet méta qui précise intervalle de contenu de l’objet d’origine. Le contenu de
            l’objet méta doit être de la forme <code>x-y</code> avec :</p>
        <ul>
            <li><code>x</code> et <code>y</code> exprimé en octet sans zéro et sans unité ;</li>
            <li><code>x</code> strictement supérieur à zéro ;</li>
            <li><code>y</code> strictement inférieur ou égal à la taille de l’objet (lien vers
                <i>nebule/objet/taille</i>) ;
            </li>
            <li><code>x</code> inférieur à <code>y</code> ;</li>
            <li>sans espace, tabulation ou retour chariot.</li>
        </ul>

        <?php Displays::docDispTitle(4, 'lrax', 'Action <code>x</code> – Suppression de lien'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Supprime un ou plusieurs liens précédemment mis en place.</p>
        <p>Les liens concernés par la suppression sont les liens antérieurs de type <code>l</code>, <code>f</code>,
            <code>u</code>, <code>d</code>, <code>e</code>, <code>k</code> et <code>s</code>. Ils sont repérés par les 3
            derniers champs, c’est à dire sur <code>HashSource_HashCible_HashMeta</code>. Les champs nuls sont
            strictement pris en compte.</p>
        <p>Le champ <code>TimeStamp</code> permet de déterminer l’antériorité du lien et donc de déterminer sa
            suppression ou pas.</p>
        <p>C’est la seule action sur les liens et non sur les objets.</p>

        <?php Displays::docDispTitle(4, 'lrhs', 'Le champ <code>HashSource</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>HashSource</code> désigne l’objet source du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>

        <?php Displays::docDispTitle(4, 'lrhc', 'Le champ <code>HashCible</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>HashCible</code> désigne l’objet destination du lien.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <?php Displays::docDispTitle(4, 'lrhm', 'Le champ <code>HashMeta</code>'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le champ <code>HashMeta</code> désigne l’objet contenant une caractérisation du lien entre l’objet source et
            l’objet destination.</p>
        <p>Le champ <code>signataire</code> ne contient que des caractères hexadécimaux, c’est à dire de «&nbsp;0&nbsp;»
            à «&nbsp;9&nbsp;» et de «&nbsp;a&nbsp;» à «&nbsp;f&nbsp;» en minuscule.</p>
        <p>Il peut être nuls, c’est à dire représentés par la valeur «&nbsp;0&nbsp;» sur un seul caractère.</p>

        <?php Displays::docDispTitle(2, 'l1', 'Lien simple'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien simple a ses champs <code>HashCible</code> et <code>HashMeta</code> égaux à «&nbsp;0&nbsp;».
        </p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_0_0</code></p>

        <?php Displays::docDispTitle(2, 'l2', 'Lien double'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien double a son champ <code>HashMeta</code> égal à «&nbsp;0&nbsp;».</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_0</code></p>

        <?php Displays::docDispTitle(2, 'l3', 'Lien triple'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien triple est complètement utilisé.</p>
        <p>Il ressemble à :</p>
        <p class="pcenter"><code>Signature_HashSignataire_TimeStamp_Action_HashSource_HashCible_HashMeta</code></p>

        <?php Displays::docDispTitle(2, 'ls', 'Stockage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Tous les liens sont stockés dans un même emplacement ou sont visible comme étant dans un même emplacement.
            Cet emplacement ne contient pas les contenus des objets (cf <a href="#oos">OOS</a>).</p>
        <p>Le lien dissimulé est stocké dans le même emplacement mais dispose de fichiers de stockages différents du
            fait de la spécificité (cf <a href="#lsds">LSDS</a>).</p>

        <?php Displays::docDispTitle(3, 'lsa', 'Arborescence'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Sur un système de fichiers, tous les liens sont stockés dans des fichiers contenus dans le dossier <code>pub/l/</code>
            (<code>l</code> comme lien).</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(3, 'lsd', 'Dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le lien de dissimulation, de type <code>c</code>, contient un lien dissimulé sans signature (cf <a
                    href="#lrac">LRAC</a>). Il permet d’offusquer des liens entre objets et donc d’anonymiser certaines
            actions de l’entité (cf <a href="#ckl">CKL</a>).</p>

        <?php Displays::docDispTitle(5, 'lsdrp', 'Registre public'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien de dissimulation, public par nature, est conforme au registre des autres liens (cf <a
                    href="#lr">LR</a>). Si ce lien ne respectait pas cette structure il serait automatiquement ignoré ou
            rejeté. Son stockage et sa transmission ont cependant quelques particularités.</p>
        <p>Les champs <code>Signature</code> (cf <a href="#lrsi">LRSI</a>) et <code>HashSignataire</code> (cf <a
                    href="#lrhsi">LRHSI</a>) du registre sont conformes aux autres liens. Ils assurent la protection du
            lien. Le champs signataire fait office d'émeteur du lien dissimulé.</p>
        <p>Le champs <code>TimeStamp</code> (cf <a href="#lrt">LRT</a>) du registre est conformes aux autres liens. Il a
            cependant une valeur non significative et sourtout pas liée au <code>TimeStamp</code> du lien dissimulé.</p>
        <p>Le champs <code>Action</code> (cf <a href="#lrt">LRT</a>) du registre est de type <code>c</code> (cf <a
                    href="#lra">LRA</a> et <a href="#lrac">LRAC</a>).</p>
        <p>Le champs <code>HashSource</code> fait référence à l’entité destinataire du lien, celle qui peut le
            déchiffrer. A part le champs de l’entité signataire, c’est le seul champs qui fait référence à un objet.</p>
        <p>Le champs <code>HashCible</code> ne contient pas la référence d’un objet mais le lien chiffré et encodé en
            hexadécimal. Le chiffrement est de type symétrique avec la clé de session. Le lien offusqué n’a pas grand
            intérêt en lui même, c’est le lien déchiffré qui en a.</p>
        <p>Le champs <code>HashMeta</code> ne contient pas la référence d’un objet mais la clé de chiffrement du lien,
            dite clé de session. Cette clé est chiffrée (asymétrique) pour l’entité destinataire et encodée en
            hexadécimal. Chaque entités destinataires d'un lien de dissimulé doit disposer d'un lien de dissimulation
            qui lui est propre.</p>
        <p>Le registre du lien de dissimulation :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage non significatif</li>
            <li>action : <code>c</code></li>
            <li>source : hash(destinataire)</li>
            <li>cible : Lien dissimulé chiffré</li>
            <li>méta : clé de déchiffrement du lien, chiffrée pour le destinataire</li>
        </ul>

        <?php Displays::docDispTitle(5, 'lsdrd', 'Registre dissimulé'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le registre du lien dissimulé est la partie utile du lien qui est protégée dans le lien de dissimulation.</p>
        <p>L'extraction du lien dissimulé se fait depuis le lien de dissimulation :</p>
        <ol>
            <li>L'entité destinataire vérifie que son identifiant est bien celui présenté par le champs
                <code>HashSource</code>.
            </li>
            <li>Le champs <code>HashMeta</code> est déchiffré (asymétrique) avec la clé privée de l'entité destinataire
                pour obtenir la clé de session.
            </li>
            <li>Le champs <code>HashCible</code> est déchiffré (symétrique) avec la clé de session pour obtenir le lien
                dissimulé.
            </li>
            <li>Le lien dissimulé obtenu ne contient pas les champs <code>Signature</code> et
                <code>HashSignataire</code> mais on peut garder ceux du lien de dissimulation 'pour affichage'.
            </li>
        </ol>
        <p>A faire...</p>
        <p>Le registre du lien dissimulé :</p>
        <ul>
            <li>Horodatage significatif</li>
            <li>action : tout sauf <code>c</code></li>
            <li>source : hash(objet source)</li>
            <li>cible : hash(objet cible)</li>
            <li>méta : hash(objet méta)</li>
        </ul>

        <?php Displays::docDispTitle(4, 'lsda', 'Attaque sur la dissimulation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le fait qu’une entité synchronise des liens dissimulés que d’autres entités partagent et les range dans des
            fichiers transcodés peut révéler l’ID de l’objet transcodé. Et par tâtonnement on peut retourner ainsi le
            transcodage de tous les objets.</p>
        <p>Il suffit qu’une entité attaquante génère un lien dissimulé à destination d’une entité attaquée concernant un
            objet en particulier. L’entité attaquée va alors ranger le lien dissimulé dans le fichier transcodé.
            L’entité attaquante peut alors rechercher quel fichier transcodé contient sont lien dissimulé et en déduire
            que ce fichier transcodé correspond à l’objet.</p>
        <p>En plus, si le lien dissimulé n’a aucune action valable, il ne sera pas exploité, donc pas détecté par
            l’entité attaquée.</p>
        <p>La solution implémentée pour palier à ce problème c'est la méthode dite de translation des liens
            dissimulés.</p>

        <?php Displays::docDispTitle(4, 'lsds', 'Stockage et transcodage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les liens dissimulés sont camouflés dans des liens de dissimulation, ils ne sont donc plus utilisables pour
            assurer le transfert entre entités et le tri dans les fichiers de stockage des liens.</p>
        <p>De plus, les liens de dissimulations ne doivent pas être stockés directement dans des fichiers de stockage
            des liens directement rattachés aux objets concernés, comme les autres liens, sous peine de dévoiler assez
            rapidement les identifiants des objets utilisés... et donc assez facilement le lien dissimulé correspondant.
            Cela poserait en plus un problème lors du nettoyage des liens parce qu'il faut avoir accès aux liens
            dissimulés pour correctement les ranger.</p>
        <p>Le nommage des fichiers contenant ces liens doit aussi être différent des entités signataires et
            destinataires des liens, et ce nommage peut par facilité faire référence simultanément à ces deux entités.
            Ainsi ces fichiers sont stockés dans le dossier des liens. Cette organisation et cette séparation des liens
            dans des fichiers clairement distincts répond au besoin d'utilisation. Et lors du nettoyage des liens, le
            traitement peut être différencié par rapport à la structure du nom des fichiers.</p>

        <?php Displays::docDispTitle(5, 'lsdst', 'Translation de lien'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La répartition des liens de dissimulation dans des fichiers attachés à l'entité émettrice et l'entité
            destinataire ne permet pas une exmploitation efficace et rapide des liens dissimulés. Il faut trouver un
            moyen d'associer les liens de dissimulations aux objets concernés par les liens dissimulés sans révéler
            publiquement ce lien. Une translation va permettre de camoufler cette association.</p>
        <p>La translation des liens dissimulés signifie la dissimulation par translation des identifiants des objets
            auxquels s'appliquent des liens dissimulés moyennant une clé de translation. Cette translation doit
            permettre de préserver la dissociation entre l'identifiant d'un objet et l'identifiant 'virtuel' auquel sont
            attachés les liens dissimulés.</p>
        <p>Le système de translation est basé sur une clé unique de translation par entité. Cette translation doit être
            une fonction à sens unique, donc à base de prise d’empreinte (hash). Elle doit maintenir la non association
            entre identifiants virtuels et réels des objets, y compris lorsqu’une ou plusieurs translations sont
            connues. Enfin, la translation doit être dépendante de l’entité qui les utilise, c’est à dire qu’une même
            clé peut être commune à plusieurs entités sans donner les mêmes translations.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(5, 'lsdsp', 'Protection de translation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'lsdt', 'Transfert et partage'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'lsdc', 'Compromission'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'lt', 'Transfert'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'lv', 'Vérification'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien
            devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être
            supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet
            n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des
            liens, et inversement (cf <a href="#cl">CL</a> et <a href="#oov">OOV</a>).</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>

        <?php Displays::docDispTitle(2, 'lo', 'Oubli'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra
            indispensable lorsque l'espace viendra à manquer (cf <a href="#cn">CN</a>).</p>

        <?php
    }
}
