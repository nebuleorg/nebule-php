<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Crypto extends Functions implements CryptoInterface
{
    public const SESSION_SAVED_VARS = array(
        '_defaultInstance',
        '_ready',
        '_listClasses',
        '_listInstances',
        '_listTypes',
    );

    public const DEFAULT_CLASS = 'openssl';

    public const RANDOM_PSEUDO = 1;
    public const RANDOM_STRONG = 2;
    public const TYPE_HASH = 1;
    public const TYPE_SYMMETRIC = 2;
    public const TYPE_ASYMMETRIC = 3;

    private ?CryptoInterface $_defaultInstance = null;

    public function __toString(): string { return self::TYPE; }

    protected function _initialisation(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, get_class($this)) && $class != get_class($this)) {
                $this->_metrologyInstance->addLog('add class ' . $class, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '53556863');
                $this->_initSubInstance($class);
            }
        }
        $this->_defaultInstance = $this->_getDefaultSubInstance('cryptoLibrary');
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstance()
     */
    public function getCryptoInstance(): CryptoInterface { return $this->_defaultInstance; }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getCryptoInstanceName()
     */
    public function getCryptoInstanceName(): string { return get_class($this->_defaultInstance); }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkFunction()
     */
    public function checkFunction(string $algo, int $type): bool { return $this->_defaultInstance->checkFunction($algo, $type); }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkValidAlgorithm()
     */
    public function checkValidAlgorithm(string $algo, int $type): bool { return $this->_defaultInstance->checkValidAlgorithm($algo, $type); }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getAlgorithmList()
     */
    public function getAlgorithmList(int $type): array { return $this->_defaultInstance->getAlgorithmList($type); }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getRandom()
     */
    public function getRandom(int $size = 32, int $quality = Crypto::RANDOM_PSEUDO): string {
        // FIXME refaire un sélecteur plus propre !
        if ($quality == Crypto::RANDOM_STRONG)
            return $this->_listInstances['openssl']->getRandom($size);
        else
            return $this->_listInstances['software']->getRandom($size);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::getEntropy()
     */
    public function getEntropy(string &$data): float { return $this->_listInstances['software']->getEntropy($data); }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::hash()
     */
    public function hash(string $data, string $algo = ''): string {
        if ($algo == '')
            $algo = \Nebule\Library\References::REFERENCE_CRYPTO_HASH_ALGORITHM;
        return $this->_defaultInstance->hash($data, $algo);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encrypt()
     */
    public function encrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
        return $this->_defaultInstance->encrypt($data, $hexKey, $hexIV);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decrypt()
     */
    public function decrypt(string $data, string $algo, string $hexKey, string $hexIV = ''): string {
        return $this->_defaultInstance->decrypt($data, $hexKey, $hexIV);
    }

    // --------------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     * @see CryptoInterface::sign()
     */
    public function sign(string $data, string $privateKey, string $privatePassword): string {
        return $this->_defaultInstance->sign($data, $privateKey, $privatePassword);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::verify()
     */
    public function verify(string $data, string $sign, string $publicKey, string $algo): bool {
        return $this->_defaultInstance->verify($data, $sign, $publicKey, $algo);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::encryptTo()
     */
    public function encryptTo(string $data, ?string $publicKey): string {
        return $this->_defaultInstance->encryptTo($data, $publicKey);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::decryptTo()
     */
    public function decryptTo(string $code, ?string $privateKey, ?string $password): string {
        return $this->_defaultInstance->decryptTo($code, $privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @param string $password
     * @param string $algo
     * @param int    $size
     * @see CryptoInterface::newAsymmetricKeys()
     */
    public function newAsymmetricKeys(string $password = '', string $algo = '', int $size = 0): array {
        return $this->_defaultInstance->newAsymmetricKeys($password, $algo, $size);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::checkPrivateKeyPassword()
     */
    public function checkPrivateKeyPassword(?string $privateKey, ?string $password): bool {
        return $this->_defaultInstance->checkPrivateKeyPassword($privateKey, $password);
    }

    /**
     * {@inheritDoc}
     * @see CryptoInterface::changePrivateKeyPassword()
     */
    public function changePrivateKeyPassword(?string $privateKey, ?string $oldPassword, ?string $newPassword): string {
        return $this->_defaultInstance->changePrivateKeyPassword($privateKey, $oldPassword, $newPassword);
    }

    static public function echoDocumentationTitles() {
        ?>

        <li><a href="#c">C / Confiance</a>
            <ul>
                <li><a href="#cfo">CFO / Fable des Origines</a></li>
                <li><a href="#co">CO / Confiance dans l’Objet</a></li>
                <li><a href="#cl">CL / Confiance dans le Lien</a></li>
                <li><a href="#coe">COE / Confiance dans l'Objet Entité</a></li>
                <li><a href="#ca">CA / Autorités</a>
                    <ul>
                        <li><a href="#cam">CAM / Autorité Maîtresse</a></li>
                        <li><a href="#cams">CAMS / Autorité Maîtresse de la Sécurité</a></li>
                        <li><a href="#camc">CAMC / Autorité Maîtresse du Code</a></li>
                        <li><a href="#cama">CAMA / Autorité Maîtresse de l'Annuaire</a></li>
                        <li><a href="#camt">CAMT / Autorité Maîtresse du Temps</a></li>
                    </ul>
                </li>
                <li><a href="#cc">CC / Configuration</a>
                    <ul>
                        <li><a href="#cco">CCO / Options</a>
                            <ul>
                                <li><a href="#ccor">CCOR / Réservation</a></li>
                                <li><a href="#ccof">CCOF / Options via Fichier</a></li>
                                <li><a href="#ccol">CCOL / Options via Liens</a></li>
                                <li><a href="#ccos">CCOS / Subordination</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#ce">CE / Confiance dans les Échanges</a>
                    <ul>
                        <li><a href="#cems">CEMS / Moyens et Supports</a></li>
                        <li><a href="#cecto">CECTO / Comportement au Téléchargement d’Objets</a></li>
                        <li><a href="#cectl">CECTL / Comportement au Téléchargement de Liens</a></li>
                        <li><a href="#cecte">CECTE / Comportement au Téléchargement d'Entités</a></li>
                    </ul>
                </li>
                <li><a href="#ck">CK / Cryptographie</a>
                    <ul>
                        <li><a href="#ckl">CKL / Cryptographie du Lien</a></li>
                        <li><a href="#cko">CKO / Cryptographie de l'Objet</a>
                            <ul>
                                <li><a href="#ckode">CKODE / Cryptographie de l'Objet - Deux Étapes</a></li>
                                <li><a href="#ckoecs">CKOECS / Cryptographie de l'Objet - Étape Chiffrement Symétrique</a></li>
                                <li><a href="#ckoeca">CKOECA / Cryptographie de l'Objet - Étape Chiffrement Asymétrique</a></li>
                                <li><a href="#ckoep">CKOEP / Cryptographie de l'Objet - Ensemble du Processus</a></li>
                                <li><a href="#ckovi">CKOVI / Cryptographie de l'Objet - Vecteur Initial</a></li>
                                <li><a href="#ckoc">CKOC / Cryptographie de l'Objet - Compression</a></li>
                                <li><a href="#ckotm">CKOTM / Cryptographie de l'Objet - Type Mime</a></li>
                                <li><a href="#ckorc">CKORC / Cryptographie de l'Objet - Résolution de Conflits</a></li>
                            </ul>
                        </li>
                        <li><a href="#cka">CKA / Aléas cryptographiques</a></li>
                    </ul>
                </li>
                <li><a href="#cs">CS / Sociabilité</a></li>
                <li><a href="#cn">CN / Nettoyage, suppression et oubli</a></li>
            </ul>
        </li>
        <?php
    }

    static public function echoDocumentationCore(): void {
        ?>

        <?php Displays::docDispTitle(1, 'c', 'Confiance'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>La confiance n’est pas quelque chose de palpable, même numériquement. Cela tient plus de la façon de concevoir les choses et le fait de faire en sorte que l’ensemble soit solide. L’ensemble doit être cohérent et résistant. On doit pouvoir compter sur ce que l’on a.</p>
        <p>La confiance est donc sous-jacente aux objets et aux liens.</p>
        <p>Les objets et les liens doivent tous être signés. Toute modification devient impossible <span style="font-weight:bold;">si l’on prend le temps de vérifier les signatures</span>.</p>
        <p>En l’absence de nouvelle découverte mathématique majeure, les algorithmes cryptographiques nous permettent aujourd’hui une offuscation forte et une prise d’empreinte fiable. C’est le chiffrement et la signature.</p>

        <?php Displays::docDispTitle(3, 'cfo', 'Fable des Origines'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 001 -</span><br />Le premier jour, il créa l'<span style="font-weight:bold;">objet</span>, essence de toute chose.<br />Ainsi la matière de l'information naquit du néant de l'éther binaire.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 010 -</span><br />Le deuxième jour, il créa le <span style="font-weight:bold;">lien</span>, pour les relier tous.<br />Ainsi apparurent les objets à la lumière, ils pouvaient se voir mutuellement.<br />Ainsi l'univers informationnel naquit des objets et des liens.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 011 -</span><br />Le troisième jour, il créa l'<span style="font-weight:bold;">entité</span>.<br />La matière inerte et uniforme devint active et protéiforme.<br />Ainsi la vie naquit de l'univers informationnel.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 100 -</span><br />Le quatrième jour, il créa la <span style="font-weight:bold;">signature</span>.<br />L'univers informationnel s'illumina  du feu des entités attirants inexorablement les objets.<br />Ainsi les nébuleuses naquirent des entités.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 101 -</span><br />Le cinquième jour, il créa le <span style="font-weight:bold;">groupe</span>.<br />A l'intérieur des nébuleuses, les objets se rassemblèrent en orbite autour des groupes.<br />Ainsi les galaxies naquirent des nébuleuses.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 110 -</span><br />Le sixième jour, il créa le <span style="font-weight:bold;">cryptogramme</span>.<br />Pour la première fois, la matière des objets commença à disparaître de la lumière.<br />Ainsi les trous noirs naquirent des galaxies.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 111 -</span><br />Le septième jour, il créa l'<span style="font-weight:bold;">interface</span>.<br />Et permit à l'homme de voir l'univers.<br />Ainsi l'univers fut achevé.</p>
        <p style="text-align:center; margin-bottom:10px;"><span style="font-weight:bold; font-family:monospace;">- 8 -</span><br />Le huitième jour, au nom de lui, l'homme créa la religion.<br />Il s'appropria tous les objets et soumit toutes les entités sous une seule.<br />Ainsi disparut l'univers dans un trou noir super-massif.</p>

        <?php Displays::docDispTitle(2, 'co', 'Confiance dans l’Objet'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’intégrité et la confidentialité des objets est garantie non pas par une <i>méta-donnée,</i> mais par les mathématiques qui animent les algorithmes cryptographiques.</p>
        <p>Un objet numérique est identifié par une empreinte ou condensat (hash) numérique. Cette empreinte doit avoir des caractéristiques propres fortes correspondant à des fonctions de prise d’empreinte cryptographiques. C’est à dire :</p>
        <ol>
            <li>L’espace des valeurs possibles est suffisamment grand ;</li>
            <li>La répartition des valeurs possibles est équiprobable ;</li>
            <li>La résistance aux collisions est forte ;</li>
            <li>La fonction utilisée est non réversible.</li>
        </ol>
        <p>Il est donc extrêmement difficile de créer deux contenus différents ayants la même empreinte et extrêmement peu probable de trouver par hasard deux contenus différents ayants la même empreinte. Par 'extrêmement' on entend impossible avec les technologies actuelles ou prévisibles dans un proche avenir. Même à moyen terme, affabli, ces fonctions de prise d'empreinte seront à même d'empêcher une falsification massive des données.</p>
        <p>La fonction de prise d’empreinte actuellement recommandée est <code>sha256</code>. Elle remplie toutes les exigences évoquées ci-dessus. Aucune faille ne permet de remettre en question de façon significative sa résistance et sa non-réversibilité à cours terme.</p>
        <p>Pour certains petits besoins spécifiques, la fonction de prise d’empreinte peut être minimaliste, donc rapide et non sécurisée. Cependant, celle-ci doit faire au minimum 2 octets. Les valeurs sur un octets sont susceptibles d’être interprétées, comme la valeur <code>0</code> qui ne désigne aucun objet.</p>
        <p>Avec le temps, les fonctions de prise d'empreinte vont évoluer avec les besoins et la technologie.</p>
        <p>L’empreinte d’un objet doit être vérifiée lors de la fin de la réception de l’objet. L’empreinte d’un objet devrait être vérifiée avant chaque utilisation de cet objet. Un contenu d'objet avec une empreinte qui ne lui correspond pas doit être supprimé. Lors de la suppression d’un objet, les liens de cet objet sont conservés. La vérification de la validité des objets est complètement indépendante de celle des liens, et inversement (cf <a href="#oov">OOV</a> et <a href="#lv">LV</a>).</p>

        <?php Displays::docDispTitle(2, 'cl', 'Confiance dans le Lien'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L’intégrité des liens est garantie non pas par une <i>méta-donnée,</i> mais par les fonctions mathématiques utilisées par les algorithmes cryptographiques.</p>
        <p>La signature du lien est obligatoire. La signature doit être réalisée par le signataire. La signature englobe tout le lien à l’exception d’elle-même. Un lien avec une signature invalide ou non vérifiable doit être ignoré et supprimé.</p>
        <p>Toute modification de l’un des champs du lien entraîne l’invalidation de tout le lien.</p>
        <p>L’empreinte du signataire est incluse dans la partie signée, ainsi, il ne peut être modifié sans invalider tout le lien. On ne peut ainsi pas usurper une autre entité.</p>
        <p>La signature d’un lien doit être vérifiée lors de la fin de la réception du lien. La signature d’un lien devrait être vérifiée avant chaque utilisation de ce lien. Un lien avec une signature invalide doit être supprimé. Lors de la suppression d’un lien, les autres liens de cet objet ne sont pas supprimés et l'objet n'est pas supprimé. La vérification de la validité des objets est complètement indépendante de celle des liens, et inversement (cf <a href="#lv">LV</a> et <a href="#oov">OOV</a>).</p>

        <?php Displays::docDispTitle(3, 'coe', "Confiance dans l'Objet Entité"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Une entité est un objet contenant une clé cryptographique publique. Cette clé permet de vérifier les liens signés par cette entité.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(2, 'ca', 'Autorités'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les entités autorités, au nombre de 5, permettent de structurer et de gérer la confiance dans le code et l'utilisation du code de la bibliothèque et de toutes les applications.</p>
        <p>Cette restriction à cinq entités est une facilité pour le développement aujourd'hui. Mais ce n'est pas un modèle viable à moyen terme. L'autorité maîtresse pourra être concurrencée par une entité désignée de l'entité de l'instance locale du serveur. Et les autres entités appartiendront à des groupes spécifique dépendants à la fois de l'autorité maîtresse et de l'entité désignée par l'entité de l'instance locale du serveur.</p>

        <?php Displays::docDispTitle(3, 'cam', 'Autorité Maîtresse'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Autrement appelée entité maîtresse du tout, cette entité est la seule déclarée en dur dans le code de la bibliothèque. Toutes les autres entités sont définies par des liens de cette entité.</p>
        <p>Au besoin, elle peut être remplacée par une autre entité via l'option <code>puppetmaster</code> dans le fichier de configuration. Cette option n'est pas utilisable via les liens.</p>
        <p>L'instance actuelle s'appelle <i><?php echo \Nebule\Library\References::PUPPETMASTER_NAME; ?></i> et est localisée en <a href="<?php echo \Nebule\Library\References::PUPPETMASTER_URL; ?>"><?php echo \Nebule\Library\References::PUPPETMASTER_URL; ?></a>.</p>
        <p>L'identifiant de cette entité est <code>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea</code>.</p>

        <?php Displays::docDispTitle(4, 'cams', 'Autorité Maîtresse de la Sécurité'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette entité est désignée par le puppetmaster par rapport au rôle de maître de la sécurité. Le rôle est défini pas l'objet réservé <code>nebule/objet/entite/maitre/securite</code>. Voir <a href="#oor">OOR</a> et <a href="#oer">OER</a>.</p>
        <p>A faire...</p>
        <p>L'instance actuelle s'appelle <i><?php echo \Nebule\Library\References::SECURITY_MASTER_NAME; ?></i> et est localisée en <a href="<?php echo \Nebule\Library\References::SECURITY_MASTER_URL; ?>"><?php echo \Nebule\Library\References::SECURITY_MASTER_URL; ?></a>.</p>
        <p>Les enfers n'ayant pas encore ouvert, cette entité n'est pas utilisée.</p>

        <?php Displays::docDispTitle(4, 'camc', 'Autorité Maîtresse du code'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette entité est désignée par le puppetmaster par rapport au rôle de maître du code. Le rôle est défini pas l'objet réservé <code>nebule/objet/entite/maitre/code</code>. Voir <a href="#oor">OOR</a> et <a href="#oer">OER</a>.</p>
        <p>A faire...</p>
        <p>L'instance actuelle s'appelle <i><?php echo \Nebule\Library\References::CODE_MASTER_NAME; ?></i> et est localisée en <a href="<?php echo \Nebule\Library\References::CODE_MASTER_URL; ?>"><?php echo \Nebule\Library\References::CODE_MASTER_URL; ?></a>.</p>

        <?php Displays::docDispTitle(4, 'cama', "Autorité Maîtresse de l'annuaire"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette entité est désignée par le puppetmaster par rapport au rôle de maître de l'annuaire. Le rôle est défini pas l'objet réservé <code>nebule/objet/entite/maitre/annuaire</code>. Voir <a href="#oor">OOR</a> et <a href="#oer">OER</a>.</p>
        <p>A faire...</p>
        <p>L'instance actuelle s'appelle <i><?php echo \Nebule\Library\References::DIRECTORY_MASTER_NAME; ?></i> et est localisée en <a href="<?php echo \Nebule\Library\References::DIRECTORY_MASTER_URL; ?>"><?php echo \Nebule\Library\References::DIRECTORY_MASTER_URL; ?></a>.</p>

        <?php Displays::docDispTitle(4, 'camt', 'Autorité Maîtresse du temps'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Cette entité est désignée par le puppetmaster par rapport au rôle de maître du temps. Le rôle est défini pas l'objet réservé <code>nebule/objet/entite/maitre/temps</code>. Voir <a href="#oor">OOR</a> et <a href="#oer">OER</a>.</p>
        <p>A faire...</p>
        <p>L'instance actuelle s'appelle <i><?php echo \Nebule\Library\References::TIME_MASTER_NAME; ?></i> et est localisée en <a href="<?php echo \Nebule\Library\References::TIME_MASTER_URL; ?>"><?php echo \Nebule\Library\References::TIME_MASTER_URL; ?></a>.</p>

        <?php Displays::docDispTitle(2, 'cc', 'Configuration'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'cco', 'Options'); ?>
        <p>Les options permettent de modifier le comportement du code de la bibliothèque et des applications.</p>
        <p>La sensibilité des options est variable. On compte trois niveaux de sensibilité :</p>
        <ul>
            <li>Utile (useful)</li>
            <li>Important (careful)</li>
            <li>Critique (critical)</li>
        </ul>
        <p>Les options sont rangées par catégories, c'est juste de l'affichage :</p>
        <ul>
            <?php foreach (\Nebule\Library\Configuration::OPTIONS_CATEGORIES as $o ) { echo "\t<li>".$o."</li>\n"; } ?>
        </ul>
        <p>Toutes les options ont une valeur par défaut. Les valeurs peuvent être modifiées via un fichier de configuration et via des liens. Les modifications appliquées dans le fichier de configuration ne sont pas écrasables par des modifications faites via des liens, cela force le comportement du code sur un serveur. Pour des raisons de sécurité, certaines options ne peuvent être modifiées que dans le fichier de configuration, elles sont dites en lecture seule.</p>
        <p>Liste des options :</p>
        <ul>
            <?php
            foreach ( \Nebule\Library\Configuration::OPTIONS_CATEGORIES as $categorie )
            {
                echo "\t<li>Catégorie '<code>".$categorie."</code>' :\n\t\t<ul>\n";
                foreach ( \Nebule\Library\Configuration::OPTIONS_LIST as $option )
                {
                    if ( \Nebule\Library\Configuration::OPTIONS_CATEGORY[$option] == $categorie )
                    {
                        echo "\t\t\t<li>Option '<code>$option</code>' :\n\t\t\t\t<ul>\n";
                        echo "\t\t\t\t\t<li>Description : <code>".\Nebule\Library\Configuration::OPTIONS_DESCRIPTION[$option]."</code></li>\n";
                        echo "\t\t\t\t\t<li>Criticité : <code>".\Nebule\Library\Configuration::OPTIONS_CRITICALITY[$option]."</code></li>\n";
                        echo "\t\t\t\t\t<li>Type : <code>".\Nebule\Library\Configuration::OPTIONS_TYPE[$option]."</code></li>\n";
                        $value = '';
                        if ( \Nebule\Library\Configuration::OPTIONS_TYPE[$option] == 'boolean' )
                        {
                            if ( \Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE[$option] == 'true' )
                                $value = 'true';
                            else
                                $value = 'false';
                        }
                        else
                            $value = \Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE[$option];
                        echo "\t\t\t\t\t<li>Valeur par défaut : <code>$value</code></li>\n";
                        if ( \Nebule\Library\Configuration::OPTIONS_WRITABLE[$option] != 'true' )
                            echo "\t\t\t\t\t<li>En lecture seule.</li>\n";
                        echo "\t\t\t\t</ul>\n\t\t\t</li>\n";
                    }
                }
                echo "\t\t</ul>\n\t</li>\n";
            }
            ?>
        </ul>

        <?php Displays::docDispTitle(4, 'ccor', 'Réservation'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les objets réservés spécifiquement pour les options :</p>
        <ul>
            <li>nebule/option</li>
        </ul>

        <?php Displays::docDispTitle(4, 'ccof', 'Options via Fichier'); ?>
        <p>Dans les deux méthodes pour gérer les options, il y a le fichier des options. Toutes les options inscrites
            dans ce fichier sont dites forcées et ne peuvent être surchargées par un lien d'option. Les options dites en
            lecture seule ne peuvent être changées que via le fichier des options.</p>
        <p>Le fichier contenant les options doit s'appeler
            <code><?php echo \Nebule\Bootstrap\LIB_LOCAL_ENVIRONMENT_FILE; ?></code>, doit être positionné à côté du
            fichier <code>index.php</code> utilisé, et doit être lisible de l'utilisateur du service web. Par sécurité,
            les fichiers <code><?php echo \Nebule\Bootstrap\LIB_LOCAL_ENVIRONMENT_FILE; ?></code> et
            <code>index.php</code> doivent être protégés en écriture, c'est-à-dire en lecture seule, pour l'utilisateur
            du service web.</p>
        <p>Chaque option est représentée sur une seule ligne commençant par le nom de l'option suivi du caractère
            <code>=</code> entouré ou non d'espaces. Tout ce qui est après le signe <code>=</code> constitue la valeur
            de l'option. La valeur ne nécessite par de simple ou double côte de protection.</p>
        <p>Dans le fichier des options, une ligne commençant par le caractère <code>#</code> est entièrement ignorée.
            C'est un commentaire. Une ligne ne contenant pas le signe <code>=</code> est ignorée, mais cela peut être
            perçu comme ambiguë, à éviter.</p>
        <p>Si des espaces sont présents en début ou fin de ligne, ils sont ignorés lors du traitement de l'option. Les
            espaces autour du signe <code>=</code> sont ignorés lors du traitement de l'option.</p>
        <p>Le fichier des options peut contenir indifféremment des options pour plusieurs bibliothèques et applications.
            Le fichier des options n'est parcouru que lors de la recherche d'une option. Les options inconnues sont
            ignorées. Seule la première occurrence d'une option est prise en compte.</p>
        <p>Les booléens sont comparés par rapport à <code>true</code>. C'est-à-dire que toute autre valeur équivaut à
            <code>false</code>.</p>
        <p>Pour modifier une option, si cela est authorisé, il faut aller modifier sa valeur dans le fichier
            <code><?php echo \Nebule\Bootstrap\LIB_LOCAL_ENVIRONMENT_FILE; ?></code> et au besoin retirer le
            <code>#</code> de mise en commentaire pour que la nouvelle valeur soit prise en compte. Ensuite, si une
            session est déjà ouverte, il faut vider le cache des options pour que ce soit pris en compte, voir
            <a href="oabc">OABC</a>.</p>
        <p>Par défaut, le fichier des options contient :</p>
        <pre>
# Generated by the <?php echo \Nebule\Bootstrap\BOOTSTRAP_NAME; ?>, part of the <?php echo \Nebule\Bootstrap\BOOTSTRAP_AUTHOR; ?>.
# <?php echo \Nebule\Bootstrap\BOOTSTRAP_SURNAME; ?>.
# Version : <?php echo \Nebule\Bootstrap\BOOTSTRAP_VERSION; ?>.
# <?php echo \Nebule\Bootstrap\BOOTSTRAP_WEBSITE; ?>.

# nebule php
<?php
foreach ( \Nebule\Library\Configuration::OPTIONS_LIST as $option ) {
    if ( \Nebule\Library\Configuration::OPTIONS_TYPE[$option] == 'boolean' ) {
        if ( \Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE[$option] == 'true' )
            $value = 'true';
        else
            $value = 'false';
    } elseif ( \Nebule\Library\Configuration::OPTIONS_TYPE[$option] == 'integer' ) {
        $value = (string)\Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE[$option];
    }
    else
        $value = \Nebule\Library\Configuration::OPTIONS_DEFAULT_VALUE[$option];
    echo '#'.$option.' = '.$value."\n";
}
?>
        </pre>

        <?php Displays::docDispTitle(4, 'ccol', 'Options via Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Dans les deux méthodes pour gérer les options, il y a le lien d'option. Toutes les options, à l'exception de celles dites en lecture seule, peuvent être définies par les liens d'options correspondants.</p>
        <p>Toutes les options définis par des liens sont attachées à des entités. C'est à dire que le lien d'une option doit contenir l'entité à laquelle s'applique le lien. L'utilisation ou non de l'option se fait par l'entité si le lien lui appartient ou si elle est subordonnée à l'entité signataire du lien (voir <a href="#ccos">CCOS</a>). Les liens de l'entité de subordination sont prioritaires sur les liens propres.</p>
        <p>Toutes les options inscrites dans le fichier des options sont dites forcées et ne peuvent être surchargées par un lien d'option.</p>
        <p>La valeur de l'option doit être présente ou écrite dans l'objet correspondant. Si la valeur de l'option ne peut être lu, elle ne sera pas prise en compte. Le nom de l'option n'a pas besoin d'être écrit dans l'objet correspondant, il est déjà défini dans le code.</p>
        <p>Les options définis par les liens ne sont pas prises en compte par la bibliothèque nebule en PHP procédurale du bootstrap.</p>
        <p>L'option se définit en créant un lien :</p>
        <ul>
            <li>Signature du lien</li>
            <li>Identifiant du signataire</li>
            <li>Horodatage</li>
            <li>action : <code>l</code></li>
            <li>source : ID entité visée</li>
            <li>cible : hash(valeur de l'option)</li>
            <li>méta : hash(‘nebule/option/’ + nom de l'option)</li>
        </ul>
        <p>Liste des options non modifiables via des liens :</p>
        <ul>
            <?php
            foreach ( \Nebule\Library\Configuration::OPTIONS_LIST as $option )
            {
                if ( \Nebule\Library\Configuration::OPTIONS_WRITABLE[$option] != 'true' )
                    echo "\t<li>Option '<code>$option</code>'</li>\n";
            }
            ?>
        </ul>
        <?php Displays::docDispTitle(4, 'ccos', 'Subordination'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Une entité peut définir ses propres options mais peut aussi se voir défini ses options par une autre entité. C'est principalement utilisé afin de piloter des instances sur des serveurs distants.</p>
        <p>La mise en place de ce mécanisme permet de maintenir autant que possible le contrôle sur un serveur que l'on ne maîtrise pas physiquement. Elle est mise en place via l'option <code>subordinationEntity</code> en lecture seule écrite dans le fichier des options. Cela veut dire aussi qu'une entité peut être compromise et pilotée à distance si le fichier des options est modifié par une entité tièrce.</p>
        <p>La subordination peut être faite vers une seule entité, défini par son identifiant, ou pour un groupe d'entités. La gestion du groupe n'est pas encore fonctionnel, seule une entité peut être défini.</p>
        <p>La subordination n'est pas prise en compte par la bibliothèque nebule en PHP procédurale du bootstrap.</p>

        <?php Displays::docDispTitle(2, 'ce', 'Confiance dans les Échanges'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(4, 'cems', 'Moyens et Supports'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Il est possible de télécharger des objets et des liens avec différents protocoles. Le plus simple étant le <i>http</i>. Le protocole et le serveur distant doivent être capable de transmettre une requête et de renvoyer en sens inverse une réponse.</p>
        <p>Côté serveur, c’est à dire la machine qui fait office de relais des objets et liens, tout ne peut pas être demandé. Les requêtes doivent être triviales à traiter, ne pas nécessiter de forte puissance de calcul ni d’empreinte mémoire démesurée. Une avalanche de requêtes diverses ne doit pas mettre à plat le serveur.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(5, 'cecto', 'Comportement au Téléchargement d’Objets'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Comportement global (téléchargement sans localisation précisée) :</p>
        <ol>
            <li>Si l’empreinte de l’objet demandé est 0, on quitte le processus de téléchargement.</li>
            <li>Si l’objet existe déjà dans le répertoire public des objets, on quitte le processus de téléchargement.</li>
            <li>Si l’objet existe déjà dans le répertoire privé des objets, on quitte le processus de téléchargement.</li>
            <li>Si l’objet n’a pas de lien dans le répertoire public des liens, on quitte le processus de téléchargement.</li>
            <li>Si l’objet n’a pas de lien dans le répertoire privé des liens, on quitte le processus de téléchargement.</li>
            <li>On se réfère à l’objet <code>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355</code> (<code>nebule/objet/entite/localisation</code>) pour trouver la localisation de toutes les entités connues.</li>
            <li>On parcourt les différentes localisations une à une (cf <i>comportement local</i>) pour essayer de télécharger l’objet demandé jusqu’à en obtenir une copie valide si c’est possible.</li>
        </ol>
        <p>Comportement local (téléchargement sur une localisation précise) :</p>
        <ol>
            <li>Si l’empreinte de l’objet demandé est 0, on quitte le processus de téléchargement.</li>
            <li>Si l’objet existe déjà dans le répertoire public des objets, on quitte le processus de téléchargement.</li>
            <li>Si l’objet existe déjà dans le répertoire privé des objets, on quitte le processus de téléchargement.</li>
            <li>Si l’objet n’a pas de lien dans le répertoire public des liens, on quitte le processus de téléchargement.</li>
            <li>Si l’objet n’a pas de lien dans le répertoire privé des liens, on quitte le processus de téléchargement.</li>
            <li>On télécharge un objet sur une localisation précise vers le répertoire public des objets.</li>
            <li>Si il est vide, on supprime l’objet.</li>
            <li>Si l’empreinte est invalide, on supprime l’objet.</li>
        </ol>

        <?php Displays::docDispTitle(5, 'cectl', 'Comportement au Téléchargement de Liens'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(5, 'cecte', "Comportement au Téléchargement d'Entités"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(2, 'ck', 'Cryptographie'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>

        <?php Displays::docDispTitle(3, 'ckl', 'Cryptographie du Lien'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le chiffrement permet de dissimuler des liens. Il est optionnel.</p>
        <p>A faire...</p>
        <p>L'option <code>permitObfuscatedLink</code> permet de désactiver la dissimulation (offuscation) des liens des objets. Dans ce cas le lien de type <code>c</code> est aussi rejeté comme invalide (cf <a href="#lrac">LRAC</a>).</p>

        <?php Displays::docDispTitle(3, 'cko', "Cryptographie de l'Objet"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le chiffrement permet de cacher le contenu des objets. Il est optionnel.</p>
        <p>Ce chiffrement doit être résistant, c’est à dire correspondre à l’état de l’art en cryptographie appliquée. On doit être en mesure de parfaitement distinguer l’objet en clair de l’objet chiffré, même si le second est dérivé du premier.</p>
        <p>L'option <code>permitProtectedObject</code> permet de désactiver la protection (chiffrement) des objets. Dans ce cas le lien de type <code>k</code> est aussi rejeté comme invalide (cf <a href="#lrak">LRAK</a>).</p>

        <?php Displays::docDispTitle(5, 'ckode', "Cryptographie de l'Objet - Deux Étapes"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Les entités sont des objets contenant le matériel cryptographique nécessaire au chiffrement asymétrique. Cependant, le chiffrement asymétrique est très consommateur en ressources CPU (calcul). On peut l’utiliser directement pour chiffrer les objets avec la clé publique d’un correspondant, mais cela devient rapidement catastrophique en terme de performances et donc en expérience utilisateur. D’un autre côté, le chiffrement symétrique est beaucoup plus performant, mais sa gestion des clés de chiffrement est délicate. Pour améliorer l’ensemble, il faut mixer les deux pour profiter des avantages de chacun.</p>
        <p>Ainsi, on va aborder le chiffrement en deux étapes distinctes.</p>
        <p>Pour la compréhension des schémas, ne pas oublier que les propriétés des objets sont elles-mêmes des objets…</p>

        <?php Displays::docDispTitle(6, 'ckoecs', "Cryptographie de l'Objet - Étape Chiffrement Symétrique"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Le chiffrement d’un objet peut prendre du temps, surtout si il est volumineux. On va donc privilégier le chiffrement symétrique qui est assez rapide. Nous avons besoin pour ce chiffrement de deux valeurs.</p>
        <p>La première valeur est une clé de chiffrement. Elle est dite clé de session. La longueur de celle-ci dépend de l’algorithme de chiffrement utilisé. Par exemple, elle fait 128bits pour l’AES. Elle est générée aléatoirement. C’est cette valeur qui va permettre le déchiffrement de l’objet et doit donc rester secrète. Mais il faut pouvoir la partager avec ses correspondants, c’est ce que l’on verra dans la deuxième étape.</p>
        <p>La seconde valeur est ce que l’on appelle une semence ou vecteur initial (IV = Initial Vector). Elle est utilisée dans la méthode de chiffrement sur plusieurs blocs, c’est à dire lorsque l’on chiffre un objet dont la taille dépasse le bloc, quantité de données que traite l’algorithme de chiffrement. Par exemple, le bloc fait 128bits pour l’AES, tout ce qui fait plus que cette taille doit être traité en plusieurs fois. Comme IV, je propose d’utiliser l’identifiant de l’objet à chiffrer, c’est à dire le hash de cet objet. Cela simplifie la diffusion de cette valeur qui n’a pas à être dissimulée.</p>
        <p>L’objet source que l’on voulait à l’origine protéger peut maintenant être marqué à supprimer. Il pourra être restauré depuis l’objet dérivé chiffré et la clé de session.</p>
        <p>Sur le schéma ci-dessous, la partie chiffrement symétrique est mise en valeur. On retrouve l’objet source en clair qui est ici une image de type JPEG. En chiffrant cet objet, cela génère un nouvel objet. Le chiffrement est matérialisé par un lien de type K. Ce lien associe aussi un objet contenant la clé de session. Le nouvel objet est de type AES-CTR, par exemple. Cela signifie qu’il est chiffré avec le protocole AES et la gestion des blocs CTR (CounTeR). L’objet contenant la clé de session est de type texte.</p>

        <?php Displays::docDispTitle(6, 'ckoeca', "Cryptographie de l'Objet - Étape Chiffrement Asymétrique"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Suite à la première étape de chiffrement, nous nous retrouvons avec un objet chiffré et un objet contenant la clé de session. Si le fichier chiffré est bien protégé (en principe) et peut donc être rendu public, l’objet avec la clé de session est au contraire bien embarrassant. C’est là qu’intervient le chiffrement asymétrique et les clés publiques/privées.</p>
        <p>Le système de clés publiques/privées va permettre de chiffrer l’objet contenant la clé de session avec la clé publique d’une entité. Ainsi on permet à cette entité, c’est à dire le destinataire, de récupérer la clé de session avec sa clé privé et donc de lire l’objet source. Et plus encore, en re-chiffrant cette même clé de session avec d’autres clés publiques, ce qui génère autant d’objets de clés chiffrés, nous permettons à autant de nouvelles entités de lire l’objet source.</p>
        <p>Le créateur de l’objet chiffré doit obligatoirement faire partie des entités destinataires si il souhaite pouvoir déchiffrer l’objet source plus tard. Sinon, il passe intégralement sous le contrôle d’une des entités destinataires.</p>
        <p>Sur le schéma ci-dessous, la partie chiffrement asymétrique est mise en valeur. On retrouve l’objet en clair qui est ici la clé des session. En chiffrant cet objet, cela génère un nouvel objet. Le chiffrement est matérialisé par un lien de type K. Ce lien associe aussi un objet contenant la clé publique d’une entité. Le nouvel objet est de type RSA.</p>

        <?php Displays::docDispTitle(5, 'ckoep', "Cryptographie de l'Objet - Ensemble du Processus"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Évidemment, ce schéma de chiffrement ne ré-invente pas la roue. C’est une façon de faire assez commune, voire un cas d’école. Mais il est ici adapté au fonctionnement particulier de nebule et de ses objets.</p>
        <p>Il y a deux points à vérifier : – Partager l’objet chiffré et permettre à une autre entité de le voir, c’est aussi lui donner accès à la clé de session. Rien n’empêche cette entité de rediffuser ensuite cette clé de session en clair ou re-chiffrée à d’autres entités. Cependant, la clé de session est unique et n’a pas de valeur en dehors de l’objet chiffré qu’elle protège. De même, l’objet source peut toujours être re-chiffré avec une nouvelle clé de session et d’autres clés publiques. On retombe sur un problème commun, insoluble et le même constat : on perd automatiquement le contrôle de toute information que l’on diffuse à autrui. – L’empreinte (hash) de la clé de session est publique. Peut-être que cela affaiblie le chiffrement et donc la solidité de la protection des objets. A voir…</p>
        <p>Par commodité, je pense qu’il serait intéressant de lier explicitement l’entité destinataire et l’objet chiffré.</p>

        <?php Displays::docDispTitle(5, 'ckovi', "Cryptographie de l'Objet - Vecteur Initial"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Pour la plupart des modes de chiffrements symétriques, un vecteur initial (semence ou IV) est nécessaire. Il est lié à l’objet chiffré pour permettre le déchiffrement de celui-ci. Par défaut, sa valeur est aléatoire.</p>
        <p>Si pas précisé, il est égale à 0.</p>
        <p>Du fait du fonctionnement du mode CTR (CounTeR), l’IV s’incrémente à chaque bloc chiffré.</p>

        <?php Displays::docDispTitle(4, 'ckoc', "Cryptographie de l'Objet - Compression"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Il est préférable d’associer de la compression avec le chiffrement.</p>
        <p>La compression des données déjà chiffrées est impossible, non que l’on ne puisse le faire, mais le gain de compression sera nul. L’entropie détermine la limite théorique maximum vers laquelle un algorithme de compression sans pertes peut espérer compresser des données. Quelque soit l’entropie des données d’origine, une fois chiffrées leur entropie est maximale. Si un algorithme obtient une compression des données chiffrées, il faut sérieusement remettre en question la fiabilité de l’algorithme de chiffrement. CF <a class="external text" title="http://fr.wikipedia.org/wiki/Entropie_de_Shannon" href="http://fr.wikipedia.org/wiki/Entropie_de_Shannon" rel="nofollow">Wikipedia – Entropie de Shannon</a>.</p>
        <p>A cause de l’entropie après chiffrement, si on veut compresser les données il est donc nécessaire de le faire avant le chiffrement.</p>
        <p>Ensuite, il faut choisir l’algorithme de compression. On pourrait forcer par défaut cet algorithme, pour tout le monde. C’est notamment ce qui se passe pour le HTML5 avec le WebM ou le H.264… et c’est précisément ce qui pose problème. En dehors des problèmes de droits d’utilisation à s’acquitter, c’est une facilité pour l’implémentation de cette compression par défaut dans les programmes. Cela évite de devoir négocier préalablement l’algorithme de compression. Mais si il est difficile de présenter des vidéos en plusieurs formats à pré-négocier, ce n’est pas le cas de la plupart des données. On perd la capacité d’évolution que l’on a en acceptant de nouveaux algorithmes de compression. Et plus encore, on perd la capacité du choix de l’algorithme le plus adapté aux données à compresser. Il faut donc permettre l’utilisation de différents algorithmes de compression.</p>
        <p>Cependant, si l’objet à chiffrer est déjà compressé en interne, comme le PNG ou OGG par exemple, la compression avant chiffrement est inutile. Ce serait une sur compression qui bien souvent n’apporte rien. Le chiffrement n’implique donc pas automatiquement une compression.</p>
        <p>Lors du chiffrement, l’objet résultant chiffré est lié à l’objet source non chiffré par un lien <code>k</code>. Il est aussi marqué comme étant un objet de <i>type-mime</i> correspondant à l’algorithme de chiffrement, via un lien <code>l</code>. Pour marquer la compression avant chiffrement, un autre lien <code>l</code> est ajouté comme <i>type-mime</i> vers l’algorithme de compression utilisé. Ce lien n’est ajouté que dans le cas d’une compression réalisée en même temps que le chiffrement.</p>
        <p>La seule contrainte, c’est l’obligation d’utiliser un algorithme de compression sans perte. L’objet, une fois décompressé doit être vérifiable par sa signature. Il doit donc être strictement identique, aucune modification ou perte n’est tolérée.</p>

        <?php Displays::docDispTitle(5, 'ckotm', "Cryptographie de l'Objet - Type Mime"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Il n’existe pas de type mime généralistes pour des fichiers chiffrés. Comme les objets chiffrés ne sont liés à aucune application en particulier.</p>
        <p>Il faut aussi un moyen de préciser l’algorithme de chiffrement derrière. Une application aura besoin de connaître cet algorithme pour déchiffrer le <i>flux</i> d’octets. En suivant la <a class="external text" title="http://www.rfc-editor.org/rfc/rfc2046.txt" href="http://www.rfc-editor.org/rfc/rfc2046.txt" rel="nofollow">rfc2046</a>, il reste la possibilité de créer quelque chose en <code>application/x-...</code></p>
        <p>Voici donc comment seront définis les objets chiffrés dans nebule :</p>
        <ul>
            <li><code>application/x-encrypted/aes-256-ctr</code></li>
            <li><code>application/x-encrypted/aes-256-cbc</code></li>
            <li><code>application/x-encrypted/rsa</code></li>
            <li>Etc…</li>
        </ul>
        <p>En fonction de l’algorithme invoqué, on sait si c’est du chiffrement symétrique ou asymétrique, et donc en principe si c’est pour une clé de session ou pas.</p>

        <?php Displays::docDispTitle(5, 'ckorc', "Cryptographie de l'Objet - Résolution de Conflits"); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Comment se comporter face à un objet que l’on sait (lien k) chiffré dans un autre objet mais qui est disponible chez d’autres entités ? Si on est destinataire légitime de cet objet, on ne le propage pas en clair. On ne télécharge pas la version en clair. On garde la version chiffrée.</p>

        <?php Displays::docDispTitle(3, 'cka', 'Aléas cryptographiques'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'aléa de qualité cryptographique est défini comme une suite de bits reconnue comme aléatoire, c'est à dire lorsque son état futur est parfaitement imprédictible, y compris en disposant de l'intégralité de l'aléa généré par la machine dans le passé. Elle est nécessaire à certains processus cryptographiques.</p>
        <p>L'aléa de qualité cryptographique étant long à générer, il doit être utilisé avec précaution pour ne pas se retrouver épuisée lorsque le besoin est réel.</p>
        <p>Cependant, l'aléa peut être utile dans certaines fonctions sans pour autant nécessiter d'être de bonne qualité. Il faut donc disposer d'un aléa de qualité cryptographique et un aléa généraliste.</p>
        <p>La bibliothèque propose dans son code deux générations d'aléa.</p>

        <?php Displays::docDispTitle(2, 'cs', 'Sociabilité'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>Lors de l'exploitation des liens, plusieurs méthodes permettent une analyse pré-définie de la validité dite sociale des liens afin de les trier ou de les filtrer.</p>
        <p>A faire...</p>

        <?php Displays::docDispTitle(2, 'cn', 'Nettoyage, suppression et oubli'); ?>
        <p style="color: red; font-weight: bold">A revoir...</p>
        <p>L'oubli vonlontaire de certains liens et objets n'est encore ni théorisé ni implémenté mais deviendra indispensable lorsque l'espace viendra à manquer (cf <a href="#ooo">OOO</a> et <a href="#lo">LO</a>).</p>

        <?php
    }
}