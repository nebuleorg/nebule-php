<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Classe Displays
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Displays
{
    /* ---------- ---------- ---------- ---------- ----------
	 * Constantes.
	 *
	 * Leur modification change profondément le comportement de l'application.
	 *
	 * Si déclarées 'const' ou 'static' elles ne sont pas remplacée dans les classes enfants
	 *   lorsque l'on appelle des fonctions de la classe parente non écrite dans la classe enfant.
	 */
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,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";
    const DEFAULT_APPLICATION_LOGO_DARK = "data:image/png;base64,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";
    const DEFAULT_APPLICATION_LOGO_LIGHT = "data:image/png;base64,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";
    const DEFAULT_APPLICATION_LOGO_LINK = 'a=1';
    const DEFAULT_BOOTSTRAP_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAADkElEQVRo3u2ZT0hUQRzHP9sGgoEh0T8kL8/Ag+tBsUNdCpmKjA7VQdzKwg6pdIoOnkQKPHgUtUOGSrt0qIUORTGIXepgZAc9eJCFMgmUEj0IQotdlJ03b1779u2IK/k7vTfvN/P7zm9+8/v3YI/+d4oEZxUHaaaBCZJyw4cjQgvn+MwLuWIZgKijg9tEgTl6SJggiAhxuqkCMowwKKesARDPiSuvRgiK+C1KyBu2AOj7XWSaKJUcphRYY4nvZIhxxM0mI9sFICAFAbBvp2/BbgAg2sIuHmRmNOcigzwKvb0rztH0mwKMUJSQ4pLhwxTjTDLLAqtAGRVUc4pG6gy8b7kq10MBECW846w2uMYAY3LGZ0YNt+ikVBv+wEV/CP8C8Maz+z565XKOIyuni4e6FmRT3gDEIO2a2u/LTwGN7zT92nEMyY68jFC0aaaXpEl+C2p76XnnGVXElKEG50f6a04NaD4/S09ke4hLOMQ94wdXjIi4It4X44SkjIf0AwlajB/qs5FSdUTmU5qiNbQfaMUckjsMGhAH+WW0iDNBTc/HHD8ahjMc2kpZshpoNorvK0Q8yE/0GU2/2XsEDQbGNXoLjja9rBlGG7wAJpjzsA3kcjsBdLDMgGdwjgkvgCQ9HghjViLumEd8D0mzH7jGS9X+Zb2dmC++KH5xkQdqRunOB1KMK2/j1rIOdaVpd0LrAiA3XDdh0hoAdaWoO5/WM6JK5XnWGoBZHwkGAIeV5wVrABZ8JBgAqMnEqjUAqz4SijErVr1WmTUpZT4SDACWlOcKawAqfCQYAHxXnqutAaj2kaADEBEyyuspawDUlTIi4gNARIi78rhGawDUlWLEVQhRrb4/obAed16lFy1EghpXgnuAWn4702m
PBlq09gLALSv711epojubK2YBxD3ioVOUF7z/cjo9g1Wc8wJ4bZhdSlfB++/ylGoAn4svKZUrjBjX6Bf7Q4vfT7/xw0i2jaf6gUEjcx2joRUwaizYXZIUpad/OiepNbDHnGO52gw+pdkdn9JsIGd1LNp4qhWnrfJPXsof1cqyu3I4j+o4/dU56qoUYlx2ZtLzgU0vxXmtPH+82xoURdCi2fEmlU+rJj/ybc0EBmC4EcHJx/LzBLDXrN5eChto3lOi/bBY58L2AUho7bvr8pXBUtzFPSSsHYG8QT3DmxnzHDdJGdlS3NxscWQYpj7IH6Mi+G23R3v0FwbfFx3mQ2ZaAAAAAElFTkSuQmCC';
    const DEFAULT_BOOTSTRAP_LOGO_LINK = 'a=1';

    // Les commandes.
    /**
     * La commande de sélection du mode.
     *
     * @var string
     */
    const DEFAULT_DISPLAY_COMMAND_MODE = 'mod';

    /**
     * La commande de sélection de la vue.
     *
     * @var string
     */
    const DEFAULT_DISPLAY_COMMAND_VIEW = 'view';
    const DEFAULT_NEXT_COMMAND = 'next';
    const DEFAULT_INLINE_COMMAND = 'i';
    const DEFAULT_INLINE_CONTENT_COMMAND = 'incontent';
    const DEFAULT_DISPLAY_MODE = 'none';
    const DEFAULT_DISPLAY_VIEW = 'none';

    // Les icônes.
    // Icône transparente.
    const DEFAULT_ICON_ALPHA_COLOR = '87b260416aa0f50736d3ca51bcb6aae3eff373bf471d5662883b8b6797e73e85.sha2.256';
    // Icônes des liens.
    const DEFAULT_ICON_LC = 'e60a32f1430b2dc6660dcd7da13fed39885451c3069e6db0daba3708f69b8b6e.sha2.256';
    const DEFAULT_ICON_LD = '232fd0ece47c1ff450927d0153886e9eec64fcf9e16ed396825c33564954f409.sha2.256';
    const DEFAULT_ICON_LE = '700ce9b827d9170303c01541eeba364b2616fabeaf6a9998868753c8feffe3dd.sha2.256';
    const DEFAULT_ICON_LF = '06cac4acb887cff2c7ba6653f865d800276a4e9d493a3be4e1b05d107f5ecbaf.sha2.256';
    const DEFAULT_ICON_LK = '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1.sha2.256';
    const DEFAULT_ICON_LL = '42e46987d36e7ae291fafc889d6ff2718db4ab277bf433491606a4c63dbc32d9.sha2.256';
    const DEFAULT_ICON_LO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const DEFAULT_ICON_LS = 'f6ae2aefb4249267c51ecf9e02c9aefd7f9312e1d22f793d35972d55ee1fb85a.sha2.256';
    const DEFAULT_ICON_LU = '7e9726b5aec1b2ab45c70f882f56ea0687c27d0739022e907c50feb87dfaf37d.sha2.256';
    const DEFAULT_ICON_LX = '72e8483f1e76c9c5ddc61fe014f0eb8a97a20fec5d3f1004085157cff6776f81.sha2.256';
    // Icônes de signalisation.
    const DEFAULT_ICON_IOK = '5931cd5d9d77d3f923cd69d306dfbb869723d1b132f0a64916e78b1bb6adb5e2.sha2.256';
    const DEFAULT_ICON_IWARN = 'bca818062c4548d7957b949707c5160a3606c83027c1e855fa6d189768b60a47.sha2.256';
    const DEFAULT_ICON_IERR = '25a0ea1b1d88d7a659ff0fa3d1b70d0cf7ae788023f897da845b1ce8d1cc7e00.sha2.256';
    const DEFAULT_ICON_IINFO = '77c14d86041ded85f77b1cc3395c55ffe8f9c5eb1bda9dc6dfc650eeecb86980.sha2.256';
    const DEFAULT_ICON_IMLOG = 'd7f68db0a1d0977fb8e521fd038b18cd601946aa0e26071ff8c02c160549633b.sha2.256';
    const DEFAULT_ICON_IMODIFY = '8481a8f5b0172db714caa5ea98a924ff40672e66e563b9b628bbf4c5044cfdad.sha2.256';
    const DEFAULT_ICON_IDOWNLOAD = '3cd50162b66b62d582110858a6167794d7afc7f83a918aa4f2339f00b66ac620.sha2.256';
    const DEFAULT_ICON_HELP = '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d.sha2.256';
    const DEFAULT_ICON_FLUSH = '3fd49fd8e5e86d65f3c6983d12a585467a6304e0e8b137609a8592937aab0dd7.sha2.256';
    // Icônes des synchronisations.
    const DEFAULT_ICON_SYNOBJ = '5fc6d664c592591eedea906e493a94ba916ce0a4e2eaed9f0a53d664f5c98eae.sha2.256';
    const DEFAULT_ICON_SYNLNK = '94c3308da0169f91c875fbd802334ec42883b978248e82bebf3e52f3cc80b4cf.sha2.256';
    const DEFAULT_ICON_SYNENT = '468f2e420371343c58dcdb49c4db9f00b81cce029a5ee1de627b9486994ee199.sha2.256';
    // Icônes des ajouts.
    const DEFAULT_ICON_ADD = 'da27aba584a1058642780169fea2fa9e072d22d1df515a84f5310608b1f31266.sha2.256';
    const DEFAULT_ICON_ADDOBJ = '37be5ba2a53e9835dbb0ff67a0ece1cc349c311660e4779680ee2daa4ac45636.sha2.256';
    const DEFAULT_ICON_ADDLNK = '4be77eff5da4ca093f43af7c71371431b30195aee0587d92e4c012923efc7b8a.sha2.256';
    const DEFAULT_ICON_ADDENT = 'cba3712128bbdd5243af372884eb647595103bb4c1f1b4d2e2bf62f0eba3d6e6.sha2.256';
    // Icônes des groupes.
    const DEFAULT_ICON_GRPENT = 'e0dcd8716dbca0ef984dec7bee459bb64c4c03bbc659d23c09c4681358e15b2c.sha2.256';
    const DEFAULT_ICON_GRPOBJ = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2.sha2.256';
    const DEFAULT_ICON_GRPENTADD = '672bc011bd1a59857603bbe68ad63511b2d47030b7d1d2987e796d796f2928ab.sha2.256';
    const DEFAULT_ICON_GRPOBJADD = '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a.sha2.256';
    // Icônes des conversations.
    const DEFAULT_ICON_CVT = '836e7786bbfc0f360d9c85908920b6dcaee34d46ca8e9c1de3a8462d95388b49.sha2.256';
    const DEFAULT_ICON_CVTENT = 'fdc7745129235ee0952a3276a862c0f36c070005f677889543ce8120c79c5e76.sha2.256';
    const DEFAULT_ICON_CVTOBJ = '77a2eaeedd706b8d62c6e7f39f4f42d4dcf3d78d2145bcd0e6d9d7b4d6852e8f.sha2.256';
    const DEFAULT_ICON_CVTENTADD = '9a7ff374f5de2b18975f2965339fb5b25915d1e64a5034354ca1607deaade057.sha2.256';
    const DEFAULT_ICON_CVTOBJADD = 'ff3fb6c1cfee8d9199e298afcf9e283c5db1b91696dc2677e110a026991aeea6.sha2.256';
    // Icônes des entités.
    const DEFAULT_ICON_USER = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256';
    const DEFAULT_ICON_ENT = '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60.sha2.256';
    const DEFAULT_ICON_OBJECT = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee.sha2.256';
    const DEFAULT_ICON_ENTITY_LOCK = 'de62640d07ac4cb2f50169fa361e062ed3595be1e973c55eb3ef623ed5661947.sha2.256';
    const DEFAULT_ICON_KEY = '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5.sha2.256';
    // Icônes des listes.
    const DEFAULT_ICON_LSTOBJ = 'cc2a24b13d8e03a5de238a79a8adda1a9744507b8870d59448a23b8c8eeb5588.sha2.256';
    const DEFAULT_ICON_LSTLNK = '06cac4acb887cff2c7ba6653f865d800276a4e9d493a3be4e1b05d107f5ecbaf.sha2.256';
    const DEFAULT_ICON_LSTENT = '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1.sha2.256';
    // Icônes diverses.
    const DEFAULT_ICON_WORLD = '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6.sha2.256';
    const DEFAULT_ICON_TIME = '108033240730a0b19e96c82d85802f53c348e446441525696744f7102070b0ed.sha2.256';
    const DEFAULT_ICON_APPLICATION = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c.sha2.256';

    // Références des icônes des liens.
    const REFERENCE_ICON_LINK_LC = '6e6562756c652f6c69656e2f6c630000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LD = '6e6562756c652f6c69656e2f6c640000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LE = '6e6562756c652f6c69656e2f6c650000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LF = '6e6562756c652f6c69656e2f6c660000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LK = '6e6562756c652f6c69656e2f6c6b0000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LL = '6e6562756c652f6c69656e2f6c6c0000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LS = '6e6562756c652f6c69656e2f6c730000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LU = '6e6562756c652f6c69656e2f6c750000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_LINK_LX = '6e6562756c652f6c69656e2f6c780000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_OBJECT = '6e6562756c652f6f626a657400000000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_ENTITY = '6e6562756c652f6f626a65742f656e74697465000000000000000000000000000000.none.272';
    const REFERENCE_ICON_GROUP = '6e6562756c652f6f626a65742f67726f757065000000000000000000000000000000.none.272';
    const REFERENCE_ICON_CONVERSATION = '6e6562756c652f6f626a65742f636f6e766572736174696f6e000000000000000000.none.272';
    const REFERENCE_ICON_INFORMATION = '69636f6e20696e666f726d6174696f6e000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_OK = '69636f6e206f6b000000000000000000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_WARNING = '69636f6e207761726e696e6700000000000000000000000000000000000000000000.none.272';
    const REFERENCE_ICON_ERROR = '69636f6e206572726f72000000000000000000000000000000000000000000000000.none.272';

    // Références des icônes des émotions.
    const REFERENCE_ICON_EMOTION_JOIE0 = '6f6a6569656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000000000000.none.272';
    const REFERENCE_ICON_EMOTION_CONFIANCE0 = '6f63666e6169636e65656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000.none.272';
    const REFERENCE_ICON_EMOTION_PEUR0 = '65707275656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000000000000.none.272';
    const REFERENCE_ICON_EMOTION_SURPRISE0 = '7573707269726573656e7562656c6f2f6a627465652f6f6d69746e6f0a2f00000000.none.272';
    const REFERENCE_ICON_EMOTION_TRISTESSE0 = '727473696574737365656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000.none.272';
    const REFERENCE_ICON_EMOTION_DEGOUT0 = '65646f677475656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000000000.none.272';
    const REFERENCE_ICON_EMOTION_COLERE0 = '6f63656c6572656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000000000.none.272';
    const REFERENCE_ICON_EMOTION_INTERET0 = '6e696574657274656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000000.none.272';
    const REFERENCE_ICON_EMOTION_JOIE1 = '6f6a6569656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000000000001.none.272';
    const REFERENCE_ICON_EMOTION_CONFIANCE1 = '6f63666e6169636e65656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000001.none.272';
    const REFERENCE_ICON_EMOTION_PEUR1 = '65707275656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000000000001.none.272';
    const REFERENCE_ICON_EMOTION_SURPRISE1 = '7573707269726573656e7562656c6f2f6a627465652f6f6d69746e6f0a2f00000001.none.272';
    const REFERENCE_ICON_EMOTION_TRISTESSE1 = '727473696574737365656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000001.none.272';
    const REFERENCE_ICON_EMOTION_DEGOUT1 = '65646f677475656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000000001.none.272';
    const REFERENCE_ICON_EMOTION_COLERE1 = '6f63656c6572656e7562656c6f2f6a627465652f6f6d69746e6f0a2f000000000001.none.272';
    const REFERENCE_ICON_EMOTION_INTERET1 = '6e696574657274656e7562656c6f2f6a627465652f6f6d69746e6f0a2f0000000001.none.272';


    /**
     * Liste des objets nécessaires au bon fonctionnement de l'application.
     * Vide par défaut, est remplacé par l'application.
     *
     * @var array:string
     */
    protected $_neededObjectsList = array();

    /**
     * Liste des objets nécessaires au bon fonctionnement.
     * Ici, ce sont tous les objets communs aux applications.
     *
     * @var array
     */
    protected $_commonNeededObjectsList = array(
        self::DEFAULT_ICON_ALPHA_COLOR,
        self::DEFAULT_ICON_LC,
        self::DEFAULT_ICON_LD,
        self::DEFAULT_ICON_LE,
        self::DEFAULT_ICON_LF,
        self::DEFAULT_ICON_LK,
        self::DEFAULT_ICON_LL,
        self::DEFAULT_ICON_LO,
        self::DEFAULT_ICON_LS,
        self::DEFAULT_ICON_LU,
        self::DEFAULT_ICON_LX,
        self::DEFAULT_ICON_IOK,
        self::DEFAULT_ICON_IWARN,
        self::DEFAULT_ICON_IERR,
        self::DEFAULT_ICON_IINFO,
    );


    /* ---------- ---------- ---------- ---------- ----------
	 * Variables.
	 *
	 * Les valeurs par défaut sont indicatives. Ne pas les replacer.
	 * Les variables sont systématiquement recalculées.
	 */
    /**
     * Instance nebule.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configurationInstance;

    /**
     * Instance sylabe.
     *
     * @var Applications
     */
    protected $_applicationInstance;

    /**
     * Instance de metrologie.
     *
     * @var Metrology
     */
    protected $_metrologyInstance;

    /**
     * Instance des I/O (entrées/sorties).
     *
     * @var IO
     */
    protected $_ioInstance;

    /**
     * Instance de traduction.
     *
     * @var Traductions
     */
    protected $_traductionInstance;

    /**
     * Etat de verrouillage de l'entité en cours.
     *
     * @var boolean
     */
    protected $_unlocked = false;

    /**
     * Instance des actions.
     *
     * @var Actions
     */
    protected $_actionInstance;

    /**
     * La base du lien hypertexte pour les objets.
     *
     * @var string
     */
    protected $_htlinkObjectPrefix = '';

    /**
     * La base du lien hypertexte pour les groupes.
     *
     * @var string
     */
    protected $_htlinkGroupPrefix = '';

    /**
     * La base du lien hypertexte pour les conversations.
     *
     * @var string
     */
    protected $_htlinkConversationPrefix = '';

    /**
     * La base du lien hypertexte pour les entités.
     *
     * @var string
     */
    protected $_htlinkEntityPrefix = '';

    /**
     * La base du lien hypertexte pour les monnaies.
     *
     * @var string
     */
    protected $_htlinkCurrencyPrefix = '';

    /**
     * La base du lien hypertexte pour les sacs de jetons.
     *
     * @var string
     */
    protected $_htlinkTokenPoolPrefix = '';

    /**
     * La base du lien hypertexte pour les jetons.
     *
     * @var string
     */
    protected $_htlinkTokenPrefix = '';

    /**
     * La base du lien hypertexte pour les transactions.
     *
     * @var string
     */
    protected $_htlinkTransactionPrefix = '';

    /**
     * La base du lien hypertexte pour les portefeuilles.
     *
     * @var string
     */
    protected $_htlinkWalletPrefix = '';

    protected $_currentDisplayLanguage;

    protected $_currentDisplayLanguageInstance;

    protected $_displayLanguageList = array();

    protected $_displayLanguageInstanceList = array();


    /**
     * Constructeur.
     *
     * @param Applications $applicationInstance
     * @return void
     */
    public function __construct(Applications $applicationInstance)
    {
        $this->_applicationInstance = $applicationInstance;
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation(): void
    {
        global $applicationName;

        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load display', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '46fcbf07');
        $this->_traductionInstance = $this->_applicationInstance->getTraductionInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        $this->setHtlinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkGroupPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkConversationPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkCurrencyPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTokenPoolPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTokenPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTransactionPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkWalletPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');

        $this->_findCurrentDisplayMode();
        $this->_findCurrentModule();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }

    /**
     * Fonction de suppression de l'instance.
     */
    public function __destruct()
    {
        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Display';
    }

    /**
     * Fonction de mise en sommeil.
     *
     * @return array:string
     */
    public function __sleep()
    {
        return array();
    }

    /**
     * Fonction de réveil.
     *
     * Récupère l'instance de la librairie nebule et de l'application.
     *
     * @return void
     */
    public function __wakeup(): void
    {
        global $applicationInstance;

        $this->_applicationInstance = $applicationInstance;
        $this->_configurationInstance = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     * @return void
     * TODO à optimiser avec __wakeup et __sleep.
     *
     */
    public function initialisation2(): void
    {
        global $applicationName;

        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load display', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '4bffc1e9');
        $this->_traductionInstance = $this->_applicationInstance->getTraductionInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        $this->setHtlinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkGroupPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkConversationPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkCurrencyPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTokenPoolPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTokenPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkTransactionPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        $this->setHtlinkWalletPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $applicationName
            . '&' . self::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_OBJECT
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');

        $this->_findCurrentDisplayMode();
        $this->_findCurrentModule();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Aucun affichage, aucune traduction, aucune action avant le retour de cette fonction.
        // Les instances interdépendantes doivent être synchronisées.
    }

    /**
     * Retourne la liste des objets nécessaires au bon fonctionnement de l'application.
     *
     * Vide par défaut, est remplacé par l'application.
     *
     * @return array:string
     */
    public function getNeededObjectsList(): array
    {
        return $this->_neededObjectsList;
    }


    /**
     * Variable du mode d'affichage en cours.
     *
     * @var string
     */
    protected $_currentDisplayMode = '';

    /**
     * Liste des modes disponibles.
     *
     * @var array of string
     */
    protected $_listDisplayModes = array();

    /**
     * Cherche le mode d'affichage en cours.
     *
     * La recherche du mode se fait en lisant le mode demandé dans l'URL,
     *   puis en le comparant aux modes listés ou aux modes supportés par les modules présents.
     *
     * Seul le mode concerné sera traité et affiché.
     *
     * Si la liste des modes est vide et si les modules ne sont pas activés, on quitte.
     * Si les modules sont activés, mais que la liste des modules est vide, on quitte.
     *
     * @return void
     */
    protected function _findCurrentDisplayMode(): void
    {
        global $applicationName;

        // Prepare the right application's class to use, '\Nebule\Application\*\Display' and not '\Nebule\Library\Displays'.
        $displayClass = $this->_applicationInstance->getNamespace() . '\\Display';

        // If we don't use modules, list of modes must not be empty.
        if (!$this->_applicationInstance->getUseModules()
            && sizeof($this->_listDisplayModes) == 0)
            return;

        // If we use modules, extract name of modes from modules.
        if ($this->_applicationInstance->getUseModules()) {
            // Extract list of modules, must not be empty.
            $listModules = $this->_applicationInstance->getModulesListInstances();
            if (sizeof($listModules) == 0)
                return;
            // Extract names of commands on these modules.
            $this->_listDisplayModes = array(0 => $displayClass::DEFAULT_DISPLAY_MODE);
            foreach ($listModules as $module) {
                if ($module->getCommandName() != ''
                    && strtolower($module->getType()) == 'application'
                ) {
                    $mode = $module->getCommandName();
                    $this->_listDisplayModes[] = $mode;
                }
            }
        }

        // Find mode to display by reading GET or ask cache or keep default mode..
        $modeARG = filter_input(INPUT_GET, self::DEFAULT_DISPLAY_COMMAND_MODE, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        $okModeARG = false;
        foreach ($this->_listDisplayModes as $name) {
            if ($modeARG == $name)
                $okModeARG = true;
        }
        if ($okModeARG)
            $this->_currentDisplayMode = $modeARG;
        else {
            $cache = $this->_nebuleInstance->getSessionStore($applicationName . 'DisplayMode');
            if ($cache !== false
                && $cache != '')
                $this->_currentDisplayMode = $cache;
            else
                $this->_currentDisplayMode = $displayClass::DEFAULT_DISPLAY_MODE;
        }
        $this->_nebuleInstance->setSessionStore($applicationName . 'DisplayMode', $this->_currentDisplayMode);
        $this->_metrologyInstance->addLog('Current mode : ' . $this->_currentDisplayMode, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'bda64a7b');
    }

    /**
     * Retourne le mode d'affichage en cours.
     *
     * @return string
     */
    public function getCurrentDisplayMode(): string
    {
        return $this->_currentDisplayMode;
    }

    /**
     * Variable de l'instance du mode en cours.
     *
     * @var Modules
     */
    protected $_currentModuleInstance = null;

    /**
     * @return void
     */
    protected function _findCurrentModule(): void
    {
        // Récupère l'instance du module en cours.
        if ($this->_applicationInstance->getUseModules()) {
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module->getCommandName() == $this->_currentDisplayMode
                    && strtolower($module->getType()) == 'application')
                {
                    $this->_metrologyInstance->addLog('Find current module name : ' . $module->getCommandName(), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '7cd85d87');
                    $this->_currentModuleInstance = $this->_applicationInstance->getModulesListInstances()['\\' . $module->getClassName()];
                }
            }
        }
    }

    /**
     * Retourne le mode d'affichage en cours.
     *
     * @return Modules
     */
    public function getCurrentModuleInstance(): Modules
    {
        return $this->_currentModuleInstance;
    }

    /**
     * Variable de la vue en cours.
     * @var string
     */
    protected $_currentDisplayView = '';

    /**
     * Liste des vues disponibles.
     *
     * @var array of string
     */
    protected $_listDisplayViews = array();

    /**
     * Recherche la vue en cours.
     *
     * La recherche de la vue se fait en lisant la vue demandée dans l'URL,
     *   puis en la comparant aux vues supportées par le mode en cours
     *   ou par la liste des vues si le mode n'est pas géré.
     *
     * Les vues des autres modes ne sont pas prises en comptes.
     * Seul le mode concerné sera traité et affiché.
     *
     * Si la liste des vues est vide et si les modules ne sont pas activés, on quitte.
     * Si les modules sont activés, mais que la liste des modules est vide, on quitte.
     *
     * @return void
     */
    protected function _findCurrentDisplayView(): void
    {
        global $applicationName;

        // If we don't use modules, list of modes must not be empty.
        if (!$this->_applicationInstance->getUseModules()
            && sizeof($this->_listDisplayModes) == 0)
            return;

        // Vérifie la liste des modules si activée.
        if ($this->_applicationInstance->getUseModules()
            && sizeof($this->_applicationInstance->getModulesListInstances()) == 0)
            return;

        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
 		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg_view = filter_input(INPUT_GET, Displays::DEFAULT_DISPLAY_COMMAND_VIEW, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

        $list_views_names = array();

        // Si activé, extrait les modes.
        if ($this->_applicationInstance->getUseModules() && is_a($this->_currentModuleInstance, '\Nebule\Library\Modules')) {
            $this->_metrologyInstance->addLog('Search view on ' . $this->_currentModuleInstance->getName(), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            // Lit les vues déclarées.
            $list_views_names = $this->_currentModuleInstance->getRegisteredViews();
            // Si demande la vue par défaut.
            if ($arg_view == 'default')
                $arg_view = $this->_currentModuleInstance->getDefaultView();
        } else {
            foreach ($this->_listDisplayViews as $view)
                $list_views_names[$view] = $view;
        }

        // Recherche une vue connue.
        $ok_view = false;
        foreach ($list_views_names as $name) {
            if ($arg_view == $name)
                $ok_view = true;
        }
        if ($arg_view == 'menu')
            $ok_view = true;

        if ($ok_view) // Si la vue est connue.
        {
            // Ecrit la vue dans la variable.
            $this->_currentDisplayView = $arg_view;
            // Ecrit la vue dans la session.
            $this->_nebuleInstance->setSessionStore($applicationName . 'DisplayView', $arg_view);
        } else {
            $cache = $this->_nebuleInstance->getSessionStore($applicationName . 'DisplayView');
            // S'il existe une variable de session pour la vue, la lit.
            if ($cache !== false
                && $cache != ''
            )
                $this->_currentDisplayView = $cache;
            else // Sinon active la vue par defaut.
            {
                // Si activé, extrait les modes.
                if ($this->_applicationInstance->getUseModules() && is_a($this->_currentModuleInstance, 'Nebule\Library\Modules'))
                    $this->_currentDisplayView = $this->_currentModuleInstance->getDefaultView();
                else
                    $this->_currentDisplayView = self::DEFAULT_DISPLAY_VIEW;
                // Ecrit dans le cache.
                $this->_nebuleInstance->setSessionStore($applicationName . 'DisplayView', $this->_currentDisplayView);
            }
            unset($cache);
        }
        $this->_metrologyInstance->addLog('Current view : ' . $this->_currentDisplayView, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'f5231ed0');

        unset($arg_view, $list_views_names, $ok_view);
    }

    /**
     * Retourne la vue en cours.
     *
     * @return string
     */
    public function getCurrentDisplayView(): string
    {
        return $this->_currentDisplayView;
    }


    /**
     * Recherche l'ID de contenu online à afficher.
     */
    protected function _findInlineContentID(): void
    {
        $arg_id = trim(' ' . filter_input(INPUT_GET, self::DEFAULT_INLINE_CONTENT_COMMAND, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if ($arg_id != '')
            $this->_inlineContentID = $arg_id;

        $this->_metrologyInstance->addLog('Find sub display : ' . $this->_inlineContentID, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '046d378a');
    }

    protected $_inlineContentID = '';

    protected $_inlineContentIndex = array();

    protected $_inlineContentOptions = array();

    /**
     * Retourne l'ID html à traiter.
     *
     * @return string
     */
    public function getInlineContentID(): string
    {
        return $this->_inlineContentID;
    }

    /**
     * Enregistre un ID de contenu html à remplacer.
     * La variable 'options' permet de passer des paramètres supplémentaires.
     *
     * @param string $id
     * @param string $options
     * @return boolean
     */
    public function registerInlineContentID(string $id, string $options = ''): bool
    {
        if ($id == ''
            || $id == '0'
        )
            return false;

        $this->_inlineContentIndex[$id] = $id;
        $this->_inlineContentOptions[$id] = $options;

        $this->_metrologyInstance->addLog('Register sub display : ' . $id, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'e3c2b608');
        ?>

        <div class="inlinecontent" id="<?php echo $id; ?>">
            <p class="inlinecontentwait"><?php $this->echoTraduction('::progress'); ?></p>
        </div>
        <?php
        return true;
    }

    /**
     * Affiche le nécesaire pour le contenu html à remplacer.
     *
     * @return boolean
     */
    protected function _displayInlineContentID(): bool
    {
        if (sizeof($this->_inlineContentIndex) == 0)
            return true;

        echo "<script language=\"javascript\" type=\"text/javascript\">\n<!--\n";
        $url = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_currentDisplayMode
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_currentDisplayView;
        foreach ($this->_inlineContentIndex as $id) {
            $option = '';
            if (isset($this->_inlineContentOptions[$id])
                && $this->_inlineContentOptions[$id] != ''
            )
                $option = '&' . $this->_inlineContentOptions[$id];
            echo "setTimeout(replaceInlineContentFromURL('" . $id . "', '" . $url
                . '&' . self::DEFAULT_INLINE_COMMAND . '&' . self::DEFAULT_INLINE_CONTENT_COMMAND . '=' . $id
                . $option . "'), 20);\n";
        }
        echo "//-->\n</script>\n";

        // Vide la liste
        $this->_inlineContentIndex = array();
        $this->_inlineContentOptions = array();

        return true;
    }


    /**
     * Affichage du style CSS commun.
     */
    public function commonCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS reset. http://meyerweb.com/eric/tools/css/reset/ v2.0 20110126. Public domain */
            * {
                margin: 0;
                padding: 0;
            }

            html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
            article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup,
            menu, nav, output, ruby, section, summary, time, mark, audio, video {
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }

            article, aside, details, figure, figcaption, footer, header, hgroup, menu, nav, section {
                display: block;
            }

            body {
                line-height: 1;
            }

            ol, ul {
                list-style: none;
            }

            blockquote, q {
                quotes: none;
            }

            blockquote:before, blockquote:after, q:before, q:after {
                content: '';
                content: none;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            /* Balises communes. */
            html {
                height: 100%;
                width: 100%;
            }

            body {
                color: #ababab;
                font-family: monospace;
                background: #454545;
                height: 100%;
                width: 100%;
                min-height: 480px;
                min-width: 640px;
            }

            img, embed, canvas, video, audio, picture {
                max-width: 100%;
                height: auto;
            }

            img {
                border: 0;
                vertical-align: middle;
            }

            a:link, a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ababab;
            }

            a:hover, a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            input {
                background: #ffffff;
                color: #000000;
                margin: 0;
                margin-top: 5px;
                border: 0;
                box-shadow: none;
                padding: 5px;
                background-origin: border-box;
            }

            input[type=submit] {
                font-weight: bold;
            }

            input[type=password], input[type=text], input[type=email] {
                padding: 6px;
            }

            /* Le bloc d'entête */
            .layout-header {
                position: fixed;
                top: 0;
                width: 100%;
                text-align: center;
            }

            .layout-header {
                height: 68px;
                background: #ababab;
                border-bottom-style: solid;
                border-bottom-color: #c8c8c8;
                border-bottom-width: 1px;
            }

            .header-left {
                height: 64px;
                width: 64px;
                margin: 2px;
                float: left;
            }

            .header-left img {
                height: 64px;
                width: 64px;
            }

            .header-right {
                height: 64px;
                width: 64px;
                margin: 2px;
                float: right;
            }

            .header-right img {
                height: 64px;
                width: 64px;
            }

            .header-center {
                height: 100%;
                display: inline-flex;
            }

            .header-center p {
                margin: auto 3px 3px 3px;
                overflow: hidden;
                white-space: nowrap;
                color: #454545;
                text-align: center;
            }

            /* Le bloc de bas de page */
            .layout-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
            }

            .layout-footer {
                height: 68px;
                background: #ababab;
                border-top-style: solid;
                border-top-color: #c8c8c8;
                border-top-width: 1px;
            }

            .footer-center p {
                margin: 3px;
                overflow: hidden;
                white-space: nowrap;
                color: #454545;
                text-align: center;
            }

            .footer-center a:link, .footer-center a:visited {
                font-weight: normal;
                text-decoration: none;
                color: #454545;
            }

            .footer-center a:hover, .footer-center a:active {
                font-weight: normal;
                text-decoration: underline;
                color: #ffffff;
            }

            /* Le menu des applications, caché par défaut. */
            .layout-menu-applications {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.33);
                display: none;
                z-index: 100;
            }

            .menu-applications {
                position: fixed;
                top: 79px;
                left: 0;
                padding: 3px;
                padding-bottom: 2px;
                padding-right: 2px;
                width: 100%;
                color: #ffffff;
                background: rgba(0, 0, 0, 0.66);
            }

            .menu-applications img {
                height: 64px;
                width: 64px;
            }

            .menu-applications a:link, .menu-applications a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .menu-applications a:hover, .menu-applications a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .menu-applications-logo {
                margin: 5px;
            }

            .menu-applications-logo img {
                height: 64px;
                width: 64px;
            }

            .menu-applications-one {
                margin: 2px;
                margin-bottom: 3px;
                margin-right: 3px;
                padding: 5px;
                float: left;
                background: rgba(0, 0, 0, 0.7);
                width: 186px;
                min-height: 64px;
            }

            .menu-applications-icon {
                float: left;
                margin-right: 5px;
            }

            .menu-applications-title p {
                font-size: 1.1em;
                font-weight: bold;
            }

            .menu-applications-text p {
                font-size: 0.8em;
            }

            .menu-applications-sign {
                position: fixed;
                bottom: 10px;
                right: 7px;
                text-align: right;
                color: #ffffff;
            }

            .menu-applications-sign img {
                height: 32px;
                width: 32px;
            }

            .menu-applications-sign a:link, .menu-applications-sign a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .menu-applications-sign a:hover, .menu-applications-sign a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            /* Le corps de la page qui contient le contenu. Permet le centrage vertical universel */
            .layout-main {
                width: 100%;
                height: 100%;
                display: flex;
            }

            /* Le centre de la page avec le contenu utile. Centrage vertical */
            .layout-content {
                margin: auto;
                padding: 84px 0 84px 0;
            }

            /* Les affichages de parties seules comme un message */
            .layoutAloneItem {
                width: 100%;
            }

            .aloneItemContent {
                margin: auto;
                text-align: center;
                font-size: 0;
                min-height: 34px;
                padding: 0 5px 5px 0;
                background: none;
            }

            .aloneTextItemContent {
                margin: auto;
                text-align: justify;
                font-size: 1rem;
                min-height: 34px;
                padding: 5px;
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .aloneTextItemContent a {
                color: #000000;
            }

            .content {
                margin: 0;
                text-align: left;
            }

            .error {
                background: #ffa0a0;
                background-origin: border-box;
                font-family: monospace;
                font-size: 1.2em;
                color: #ff0000;
                text-align: left;
            }

            .error p {
                padding: 5px;
            }

            .error img {
                height: 32px;
                width: 32px;
            }

            .error a:link, .error a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ef0000;
            }

            .error a:hover, .error a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .warning {
                background: #ffe080;
                background-origin: border-box;
                font-family: monospace;
                font-size: 1.2em;
                color: #ff8000;
                text-align: left;
            }

            .warning p {
                padding: 5px;
            }

            .warning img {
                height: 32px;
                width: 32px;
            }

            .warning a:link, .warning a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ef7000;
            }

            .warning a:hover, .warning a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .message {
                background: #103020;
                background-origin: border-box;
                font-family: monospace;
                font-size: 1.2em;
                color: #ffffff;
                text-align: left;
            }

            .message p {
                padding: 5px;
            }

            .message img {
                height: 32px;
                width: 32px;
            }

            .information {
                background: rgba(0, 0, 0, 0.6);
                background-origin: border-box;
                text-align: left;
            }

            .information p {
                padding: 5px;
                color: #ffffff;
            }

            .information img {
                height: 32px;
                width: 32px;
            }

            .textTitle {
                background: rgba(255, 255, 255, 0.6);
                background-origin: border-box;
                text-align: center;
                color: #000000;
                clear: both;
                padding: 5px;
                min-height: 65px;
            }

            .textTitle img {
                height: 64px;
                width: 64px;
            }

            .textTitle h1 {
                font-size: 1.5em;
                color: #000000;
                font-weight: bold;
                text-align: center;
                padding: 8px;
                background: rgba(255, 255, 255, 0);
            }

            .textTitle2 {
                background: rgba(255, 255, 255, 0.4);
                background-origin: border-box;
                text-align: center;
                color: #000000;
                clear: both;
                padding: 5px;
                min-height: 65px;
            }

            .textTitle2 img {
                height: 64px;
                width: 64px;
            }

            .textTitle2 h2 {
                font-size: 1.4em;
                color: #000000;
                font-weight: bold;
                text-align: center;
                padding: 5px;
                background: rgba(255, 255, 255, 0);
                font-family: monospace;
            }

            .text {
                padding: 10px;
                padding-top: 20px;
                padding-bottom: 40px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
            }

            .text p {
                margin-bottom: 20px;
                clear: both;
                text-align: justify;
            }

            .textSmall {
                padding: 5px;
                padding-bottom: 15px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
            }

            .textSmall p {
                margin: 0;
                clear: both;
                text-align: justify;
            }

            .textnobg {
                padding: 10px;
                padding-top: 20px;
                padding-bottom: 40px;
                color: #000000;
                clear: both;
            }

            .textnobg p {
                margin-bottom: 20px;
                text-align: justify;
            }

            .textcontentfull {
                padding: 5px;
                padding-top: 20px;
                padding-bottom: 40px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
            }

            .textcontentfull pre {
                background: rgba(255, 255, 255, 0.7);
                background-origin: border-box;
                margin: 0;
                padding: 5px;
                clear: both;
            }

            .textcontentfull img, .textcontentfull video, .textcontentfull audio, .textcontentfull picture {
                max-width: 100%;
                height: auto;
                margin: 0;
                padding: 0;
                clear: both;
            }

            .textcontenthalf {
                padding: 5px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
            }

            .textcontenthalf pre {
                background: rgba(255, 255, 255, 0.7);
                background-origin: border-box;
                margin: 0;
                padding: 5px;
                clear: both;
            }

            .textcontenthalf img, .textcontenthalf video, .textcontenthalf audio, .textcontenthalf picture {
                max-width: 50%;
                height: auto;
                margin: 0;
                padding: 0;
                clear: both;
            }

            .textcenter {
                padding: 20px;
                padding-bottom: 40px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                text-align: center;
                color: #000000;
            }

            .textcenter p {
                margin-bottom: 20px;
                clear: both;
            }

            .sequence {
                height: 110px;
                clear: both;
            }

            .bootstrapErrorDiv {
                margin: 0px;
                padding: 5px;
                background: #454545;
            }

            .bootstrapErrorDiv p {
                color: #ababab;
            }

            /* Le pied de page pour le traitement des actions, avant l'affichage du centre de la page. */
            .flowaction {
                position: fixed;
                bottom: 0;
                width: 100%;
                margin: 0px;
                background: rgba(34, 34, 34, 0.8);
            }

            .flowaction p {
                color: #ffffff;
            }

            /* Les insersions en ligne. */
            .inlinecontent {
                width: 100%;
            }

            .inlinecontentwait {
                width: 206px;
                text-align: center;
                margin: 5px;
                padding: 5px;
                min-height: 24px;
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .inlinecontentnext {
                width: 206px;
                text-align: center;
                margin: 5px;
                padding: 5px;
                min-height: 24px;
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            /* Les images */
            .iconegrossepuce {
                height: 64px;
                width: 64px;
            }

            .iconemoyenpuce {
                height: 32px;
                width: 32px;
            }

            .iconepuce {
                height: 16px;
                width: 16px;
            }

            .iconInlineDisplay {
                height: 16px;
                width: 16px;
            }

            /* L'insertion de boutons de fonctionnalités liées aux modules, dans le corps de la page. */
            .textAction {
                padding: 3px;
                min-height: 34px;
                background: rgba(255, 255, 255, 0.12);
                color: #000000;
            }

            .textAction a:link, .textAction a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .textAction a:hover, .textAction a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .oneAction {
                margin: 2px;
                padding: 5px;
                float: left;
                background: rgba(255, 255, 255, 0.7);
                width: 206px;
                min-height: 64px;
            }

            .oneActionItem {
                margin: 2px;
                padding: 5px;
                float: left;
                background: rgba(255, 255, 255, 0.4);
                width: 350px;
                min-height: 64px;
            }

            .oneActionItem-top {
                min-height: 64px;
            }

            .oneAction-icon {
                float: left;
                margin-right: 5px;
            }

            .oneAction-icon img {
                height: 64px;
                width: 64px;
            }

            .oneAction-modname p {
                font-size: 0.6em;
                font-style: italic;
            }

            .oneAction-entityname p {
                font-size: 0.8em;
                margin-bottom: 5px;
            }

            .oneAction-title p {
                font-size: 1.1em;
                font-weight: bold;
            }

            .oneAction-text p {
                font-size: 0.8em;
            }

            .oneAction-actions {
                clear: both;
            }

            .oneAction-actions p {
                background: rgba(255, 255, 255, 0.33);
                margin-top: 5px;
            }

            .oneAction-ok {
                clear: both;
            }

            .oneAction-ok p {
                background: #80ff80;
                margin-top: 5px;
            }

            .oneAction-warn {
                clear: both;
            }

            .oneAction-warn p {
                background: #ffe080;
                margin-top: 5px;
            }

            .oneAction-error {
                clear: both;
            }

            .oneAction-error p {
                background: #ffa0a0;
                margin-top: 5px;
            }

            .oneAction-close {
                height: 1px;
                clear: both;
            }

            #oneAction-bg-warn {
                background: #ffe080;
            }

            .inlineemotions {
                padding: 5px;
                background: rgba(255, 255, 255, 0.3);
                background-origin: border-box;
                text-align: center;
                font-size: 0.8em;
                color: #000000;
            }

            .inlineemotions img {
                height: 24px;
                width: 24px;
                margin-left: 5px;
            }

            /* Pour les bulles d'aide. */
            .infobulle {
                position: absolute;
                left: 0;
                visibility: hidden;
                padding: 5px;
                color: #ffffff;
                background: rgba(0, 0, 0, 0.8);
                width: 50%;
                text-align: justify;
            }

            /* Les champs forçés. */
            .forcedinput {
                background: #ffffff;
                color: #808080;
                margin: 0;
                border: 0;
                box-shadow: none;
                padding: 2px;
                padding-left: 5px;
                padding-right: 5px;
                font-size: 1rem;
            }
        </style>
        <?php
        $this->_getDisplayObjectsListCSS();
        $this->_getDisplayMessageCSS();
        $this->_getDisplayMenuListCSS();
        $this->_getDisplayTitleCSS();
        $this->_getDisplayInformationCSS();
        $this->_getDisplayLinkCSS();
        $this->_getDisplayObjectContentCSS();
        $this->_getDisplayObjectCSS();
    }

    /**
     * Affichage du style CSS de l'application.
     */
    public function displayCSS(): void
    {
        // Cette fonction doit être surchargée par l'application avec ses styles propres.
    }


    /**
     * Affichage des scripts JS communs.
     * Si l'option permitJavaScript est à false, cette partie est désactivée.
     *
     * @return void
     */
    public function commonScripts(): void
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitJavaScript')) {
            return;
        }
        ?>

        <script language="javascript" type="text/javascript">
            <!--
            function replaceInlineContent(id, content) {
                document.getElementById(id).innerHTML = content;
            }

            function replaceInlineContentFromURL(id, url) {
                var req = false;
                // For Safari, Firefox, and other non-MS browsers
                if (window.XMLHttpRequest) {
                    try {
                        req = new XMLHttpRequest();
                    } catch (e) {
                        req = false;
                    }
                } else if (window.ActiveXObject) {
                    // For Internet Explorer on Windows
                    try {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            req = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            req = false;
                        }
                    }
                }
                var element = document.getElementById(id);
                if (!element) {
                    return;
                }
                if (req) {
                    // Synchronous request, wait till we have it all
                    req.open('GET', url, false);
                    req.send(null);
                    element.innerHTML = req.responseText;
                } else {
                    element.innerHTML = "ERROR: your browser does not support XMLHTTPRequest objects!";
                }
            }

            function replaceNextContentFromURL(id, url) {
                replaceInlineContent(id, '<p class="inlinecontentwait">...</p>');
                var req = false;
                // For Safari, Firefox, and other non-MS browsers
                if (window.XMLHttpRequest) {
                    try {
                        req = new XMLHttpRequest();
                    } catch (e) {
                        req = false;
                    }
                } else if (window.ActiveXObject) {
                    // For Internet Explorer on Windows
                    try {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            req = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            req = false;
                        }
                    }
                }
                var element = document.getElementById(id);
                if (!element) {
                    return;
                }
                if (req) {
                    // Synchronous request, wait till we have it all
                    req.open('GET', url, false);
                    req.send(null);
                    element.innerHTML = req.responseText;
                } else {
                    element.innerHTML = "ERROR: your browser does not support XMLHTTPRequest objects!";
                }
            }

            var i = false;
            var j = true;

            function GetId(id) {
                return document.getElementById(id);
            }

            function move(e) {
                if (i && j) {
                    if (navigator.appName !== "Microsoft Internet Explorer") {
                        GetId("curseur").style.left = e.pageX - 720 + "px";
                        GetId("curseur").style.top = e.pageY + 10 + "px";
                    } else {
                        if (document.documentElement.clientWidth > 0) {
                            GetId("curseur").style.left = event.x + document.documentElement.scrollLeft - 720 + "px";
                            GetId("curseur").style.top = 10 + event.y + document.documentElement.scrollTop + "px";
                        } else {
                            GetId("curseur").style.left = event.x + document.body.scrollLeft - 710 + "px";
                            GetId("curseur").style.top = 10 + event.y + document.body.scrollTop + "px";
                        }
                    }
                    j = false;
                }
            }

            function montre(text) {
                if (i === false) {
                    GetId("curseur").style.visibility = "visible";
                    GetId("curseur").innerHTML = text;
                    i = true;
                }
            }

            function cache() {
                if (i === true) {
                    GetId("curseur").style.visibility = "hidden";
                    i = false;
                    j = true;
                }
            }

            document.onmouseover = move;

            ico_lock_off = new Image(64, 64);
            ico_lock_off.src = "/<?php    $objet = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ENTITY_LOCK);
                $newobj = $objet->getUpdateNID(true, false);
                echo nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $newobj; ?>";
            ico_lock_on = new Image(64, 64);
            ico_lock_on.src = "/<?php    $objet = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_ENT);
                $newobj = $objet->getUpdateNID(true, false);
                echo nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $newobj; ?>";

            function hiLite(imgDocID, imgObjName, comment) {
                document.images[imgDocID].src = eval(imgObjName + ".src");
                window.status = comment;
                return true;
            }

            function display_menu(menuid) {
                var test = document.getElementById(menuid).style.display;
                if (test === "block") {
                    document.getElementById(menuid).style.display = "none";
                } else {
                    document.getElementById(menuid).style.display = "block";
                }
            }

            function display_show_block(menuid) {
                document.getElementById(menuid).style.display = "block";
            }

            function display_hide(menuid) {
                document.getElementById(menuid).style.display = "none";
            }

            //-->
        </script>
        <?php
    }

    /**
     * Affichage des scripts JS.
     */
    protected function _displayScripts(): void
    {
        // Nothing to do. This function must be rewrited on apps if needed.
    }

    /**
     * Code on apps before display.
     */
    protected function _preDisplay(): void
    {
        // Nothing to do. This function must be rewrited on apps if needed.
    }

    /**
     * Display page inline or not.
     */
    public function display(): void
    {
        // Preload code from apps.
        $this->_preDisplay();

        // Read GET param if existed.
        if (filter_has_var(INPUT_GET, self::DEFAULT_INLINE_COMMAND))
            $this->_displayInline();
        else
            $this->_displayFull();
    }

    /**
     * Affichage de la page complète.
     */
    protected function _displayFull(): void
    {
        global $applicationVersion, $applicationLicence, $applicationWebsite, $applicationName, $applicationSurname, $applicationAuthor;
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <title><?php echo $applicationName; ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo $applicationSurname; ?>"/>
            <meta name="description" content="<?php echo $applicationName; ?>"/>
            <meta name="author" content="<?php echo $applicationAuthor . ' - ' . $applicationWebsite; ?>"/>
            <meta name="licence" content="<?php echo $applicationLicence; ?>"/>
            <?php $this->commonCSS(); ?>
            <style type="text/css">
                #logo img {
                    height: 256px;
                    width: 256px;
                }
            </style>
        </head>
        <body>
        <div class="layout-header">
            <div class="header-left">
                <a href="/?<?php echo Displays::DEFAULT_BOOTSTRAP_LOGO_LINK; ?>">
                    <img title="App switch" alt="[]" src="<?php echo Displays::DEFAULT_APPLICATION_LOGO; ?>"/>
                </a>
            </div>
            <div class="header-right">
                &nbsp;
            </div>
            <div class="header-center">
                <p>
                    <?php
                    $name = $this->_nebuleInstance->getInstanceEntityInstance()->getFullName();
                    if ($name != $this->_nebuleInstance->getInstanceEntity())
                        echo $name;
                    else
                        echo '/';
                    echo '<br />' . $this->_nebuleInstance->getInstanceEntity();
                    ?>
                </p>
            </div>
        </div>
        <div class="layout-footer">
            <div class="footer-center">
                <p>
                    <?php echo $applicationName; ?><br/>
                    <?php echo $applicationVersion; ?><br/>
                    (c) <?php echo $applicationLicence . ' ' . $applicationAuthor; ?> - <a
                        href="http://<?php echo $applicationWebsite; ?>" target="_blank"
                        style="text-decoration:none;"><?php echo $applicationWebsite; ?></a>
                </p>
            </div>
        </div>
        <div class="layout-main">
            <div class="layout-content">
                <img alt="nebule" id="logo" src="<?php echo static::DEFAULT_APPLICATION_LOGO_LIGHT; ?>"/>
            </div>
        </div>
        </body>
        </html>
        <?php
    }

    /**
     * Affichage de la partie de page en ligne.
     */
    protected function _displayInline(): void
    {
        // Nothing to do. This function must be rewrited on apps if needed.
    }


    // Emulation des fonctions de traduction.
    public function getLanguageList(): array
    {
        return array('none');
    }

    public function getLanguageInstanceList(): array
    {
        return array(null);
    }

    public function getCurrentLanguage(): array
    {
        return array('none');
    }

    public function getCurrentLanguageInstance()
    {
        return null;
    }

    public function getDefaultLanguage(): array
    {
        return array('none');
    }

    public function getDefaultLanguageInstance()
    {
        return null;
    }

    public function getTraduction(string $text): string
    {
        return $this->_traductionInstance->getTraduction($text);
    }

    public function setHtlinkObjectPrefix(string $htlink): void
    {
        $this->_htlinkObjectPrefix = $htlink;
    }

    public function setHtlinkGroupPrefix(string $htlink): void
    {
        $this->_htlinkGroupPrefix = $htlink;
    }

    public function setHtlinkConversationPrefix(string $htlink): void
    {
        $this->_htlinkConversationPrefix = $htlink;
    }

    public function setHtlinkEntityPrefix(string $htlink): void
    {
        $this->_htlinkEntityPrefix = $htlink;
    }

    public function setHtlinkCurrencyPrefix(string $htlink): void
    {
        $this->_htlinkCurrencyPrefix = $htlink;
    }

    public function setHtlinkTokenPoolPrefix(string $htlink): void
    {
        $this->_htlinkTokenPoolPrefix = $htlink;
    }

    public function setHtlinkTokenPrefix(string $htlink): void
    {
        $this->_htlinkTokenPrefix = $htlink;
    }

    public function setHtlinkTransactionPrefix(string $htlink): void
    {
        $this->_htlinkTransactionPrefix = $htlink;
    }

    public function setHtlinkWalletPrefix(string $htlink): void
    {
        $this->_htlinkWalletPrefix = $htlink;
    }

    public function echoTraduction(string $text, string $color = '', string $arg1 = '', string $arg2 = '',
                                   string $arg3 = '', string $arg4 = '', string $arg5 = '', string $arg6 = '',
                                   string $arg7 = '', string $arg8 = '', string $arg9 = ''): void
    {
        if ($color != '') echo "<font color=\"$color\">";
        echo sprintf(($this->getTraduction($text)), $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9);
        if ($color != '') echo '</font>';
    }










    /* --------------------------------------------------------------------------------
	 *  Affichage des messages.
	 * -------------------------------------------------------------------------------- */
    /**
     * Prépare à afficher un message pré-formaté en fonction du type de message.
     * Les types possibles : information, ok, warning, error.
     * Par défaut, le type correspond à un message d'information.
     *
     * @param string $text
     * @param string $type [information|ok|warning|error]
     * @param string $arg1
     * @param string $arg2
     * @param string $arg3
     * @param string $arg4
     * @param string $arg5
     * @return string
     */
    public function convertLineMessage(string $text, string $type = 'information', string $arg1 = '', string $arg2 = '',
                                       string $arg3 = '', string $arg4 = '', string $arg5 = ''): string
    {
        $iconCssClass = 'iconemoyenpuce';

        // Préparation du type de message à afficher.
        if ($type == 'ok') {
            $messageCssClass = 'information';
            $messageText = '::::INFORMATION';
            $messageIcon = self::DEFAULT_ICON_IOK;
        } elseif ($type == 'warning') {
            $messageCssClass = 'warning';
            $messageText = '::::WARN';
            $messageIcon = self::DEFAULT_ICON_IWARN;
        } elseif ($type == 'error') {
            $messageCssClass = 'error';
            $messageText = '::::ERROR';
            $messageIcon = self::DEFAULT_ICON_IERR;
        } else {
            $messageCssClass = 'information';
            $messageText = '::::INFO';
            $messageIcon = self::DEFAULT_ICON_IINFO;
        }
        $instanceIcon = $this->_nebuleInstance->newObject($messageIcon);

        return '<div class="' . $messageCssClass . '"><p>'
            . $this->convertUpdateImage($instanceIcon, $messageText, $iconCssClass)
            . '&nbsp;' . sprintf($this->_traductionInstance->getTraduction($text), $arg1, $arg2, $arg3, $arg4, $arg5)
            . "</p></div>\n";
    }

    /**
     * Affiche un message pré-formaté en fonction du type de message.
     * Les types possibles : information, ok, warning, error.
     * Par défaut, le type correspond à un message d'information.
     *
     * @param string $text
     * @param string $type [information|ok|warning|error]
     * @param string $arg1
     * @param string $arg2
     * @param string $arg3
     * @param string $arg4
     * @param string $arg5
     * @return void
     */
    public function displayLineMessage(string $text, string $type = 'information', string $arg1 = '', string $arg2 = '',
                                       string $arg3 = '', string $arg4 = '', string $arg5 = ''): void
    {
        echo $this->convertLineMessage($text, $type, $arg1, $arg2, $arg3, $arg4, $arg5);
    }

    /**
     * Prépare à afficher un message d'information pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return string
     */
    public function convertMessageInformation(string $text, string $arg1 = ''): string
    {
        return $this->convertLineMessage($text, 'information', $arg1);
    }

    /**
     * Affiche un message d'information pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return void
     */
    public function displayMessageInformation(string $text, string $arg1 = ''): void
    {
        echo $this->convertLineMessage($text, 'information', $arg1);
    }

    /**
     * Prépare à afficher un message de validation pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return string
     */
    public function convertMessageOk(string $text, string $arg1 = ''): string
    {
        return $this->convertLineMessage($text, 'ok', $arg1);
    }

    /**
     * Affiche un message de validation pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return void
     */
    public function displayMessageOk(string $text, string $arg1 = ''): void
    {
        echo $this->convertLineMessage($text, 'ok', $arg1);
    }

    /**
     * Prépare à afficher un message d'avertissement pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return string
     */
    public function convertMessageWarning(string $text, string $arg1 = ''): string
    {
        return $this->convertLineMessage($text, 'warning', $arg1);
    }

    /**
     * Affiche un message d'avertissement pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return void
     */
    public function displayMessageWarning(string $text, string $arg1 = ''): void
    {
        echo $this->convertLineMessage($text, 'warning', $arg1);
    }

    /**
     * Prépare à afficher un message d'erreur pré-formaté.
     *
     * @param string $text
     * @param string $arg1
     * @return string
     */
    public function convertMessageError(string $text, string $arg1 = ''): string
    {
        return $this->convertLineMessage($text, 'error', $arg1);
    }

    /**
     * Affiche un message d'erreur pré-formaté.
     *
     * @param string      $text
     * @param string|null $arg1
     * @return void
     */
    public function displayMessageError(string $text, string $arg1 = ''): void
    {
        echo $this->convertLineMessage($text, 'error', $arg1);
    }



    /* --------------------------------------------------------------------------------
	 *  Affichage des contenus.
	 * -------------------------------------------------------------------------------- */
    /**
     * Prépare à afficher le contenu d'un objet suivant son type.
     *
     * @param Node   $object
     * @param string $size [full|half|small]
     * @param bool   $permitWarnProtected
     * @return string
     */
    public function convertObjectContentSized(Node $object, string $size = 'half', bool $permitWarnProtected = true): string
    {
        $result = '';
        $unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        if ($size != 'full'
            && $size != 'half'
            && $size != 'small'
        )
            $size = 'half';

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Détermine si c'est une entité.
        $type = $object->getType('all');
        $objHead = $object->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $isEntity = ($type == nebule::REFERENCE_OBJECT_ENTITY
            && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false
        );

        // Affiche l'objet suivant son type.
        if ($isEntity) {
            if ($object->checkPresent()) {
                if ($size = 'half' || $size = 'full') {
                    if (!is_a($object, 'Nebule\Library\Entity')) {
                        $object = $this->_nebuleInstance->newEntity($object->getID());
                    }

                    $result = '<div class="text">' . "\n\t<p>"
                        . sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                            $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n")
                        . "\t</p>\n";

                    if ($size = 'full') {
                        // Liste des localisations.
                        $localisations = $object->getLocalisationsID();
                        if (sizeof($localisations) > 0) {
                            $result .= '<table border="0"><tr><td><td>' . $this->_traductionInstance->getTraduction('::EntityLocalisation') . " :</td><td>\n";
                            foreach ($localisations as $localisation) {
                                $locObject = $this->_nebuleInstance->newObject($localisation);
                                $result .= "\t " . $this->convertInlineObjectColorIcon($localisation) . ' '
                                    . $this->convertHypertextLink(
                                        $locObject->readOneLineAsText(),
                                        $locObject->readOneLineAsText()
                                    ) . "<br />\n";
                            }
                            $result .= "</td></tr></table>\n";
                            unset($localisations, $localisation, $locObject);
                        }
                    }
                    $result .= "</div>\n";
                }
            } else
                $result = $result . $this->convertLineMessage(':::display:content:errorNotAvailable', 'error');
        } elseif ($isGroup) {
            if (!is_a($object, 'Group'))
                $object = $this->_nebuleInstance->newGroup($object->getID());
            $isClosed = $object->getMarkClosed();

            $result = '<div class="text">' . "\n\t<p>"
                . sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                    $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n");
            if ($isClosed)
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::GroupeFerme') . ".\n";
            else
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::GroupeOuvert') . ".\n";
            $result .= "\t</p>\n</div>\n";

            unset($isOpened, $isClosed);
        } elseif ($isConversation) {
            if (!is_a($object, 'Conversation'))
                $object = $this->_nebuleInstance->newConversation($object->getID());
            $isClosed = $object->getMarkClosed();

            $result = '<div class="text">' . "\n\t<p>"
                . sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                    $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n");
            if ($isClosed)
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::ConversationFermee') . ".\n";
            else
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::ConversationOuverte') . ".\n";
            $result .= "\t</p>\n</div>\n";

            unset($isClosed);
        } else
            $result = $this->convertAsObjectContentSized($object, $size, $permitWarnProtected);

        return $result;
    }

    /**
     * Prépare à afficher le contenu d'un objet suivant son type. Version full.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertObjectContentFull(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertObjectContentSized($object, 'full', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet suivant son type. Version full.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayObjectContentFull(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertObjectContentSized($object, 'full', $permitWarnProtected);
    }

    /**
     * Prépare à afficher le contenu d'un objet suivant son type. Version half.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertObjectContentHalf(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertObjectContentSized($object, 'half', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet suivant son type. Version half.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayObjectContentHalf(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertObjectContentSized($object, 'half', $permitWarnProtected);
    }

    /**
     * Prépare à afficher le contenu d'un objet suivant son type. Version small.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertObjectContentSmall(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertObjectContentSized($object, 'small', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet suivant son type. Version small.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayObjectContentSmall(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertObjectContentSized($object, 'small', $permitWarnProtected);
    }


    /**
     * Prépare à afficher le contenu d'un objet comme objet pur.
     * Affiche un objet sans tenir compte de son type nebule (Entity|Group|Conversation).
     * Mais affiche l'objet en fonction de son type mime déclaré.
     *
     * @param Node   $object
     * @param string $size [full|half|small]
     * @param bool   $permitWarnProtected
     * @return string
     */
    public function convertAsObjectContentSized(Node $object, string $size = 'half', bool $permitWarnProtected = true): string
    {
        $result = '';
        $unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        if ($size != 'full'
            && $size != 'half'
            && $size != 'small'
        )
            $size = 'half';

        $nid = $object->getID();

        // Vérifie s'il est protégé
        $protected = $object->getMarkProtected();

        // Extrait les propriétés de l'objet.
        $name = $object->getFullName('all');
        $typemime = $object->getType('all');
        $danger = $object->getMarkDanger();
        $warning = $object->getMarkWarning();
        $ispresent = $object->checkPresent();
        $type = $this->_traductionInstance->getTraduction($typemime);

        // Affichage du contenu.
        if ($danger)
            $result = $result . $this->convertLineMessage(
                    $this->_traductionInstance->getTraduction(':::display:content:errorBan')
                    . $this->_traductionInstance->getTraduction(':::display:content:errorNotAvailable'),
                    'error');
        elseif ($protected
            && $nid == $object->getProtectedID()
        ) {
            $result = $result . $this->convertLineMessage(
                    $this->_traductionInstance->getTraduction(':::display:content:warningObjectProctected'),
                    'warning');
            $unprotectedObject = $this->_nebuleInstance->newObject($object->getUnprotectedID());
            $unprotectedName = $unprotectedObject->getFullName('all');
            $unprotectedTypemime = $unprotectedObject->getType('all');
            $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($unprotectedObject);
            $result .= '<div class="textAction">' . "\n";
            $result .= ' <div class="oneActionItem" id="selfEntity">' . "\n";
            $result .= '  <div class="oneActionItem-top">' . "\n";
            $result .= '   <div class="oneAction-icon">' . $this->convertObjectColorIcon($unprotectedObject) . "</div>\n";
            $result .= '   <div class="oneAction-title"><p>' . $this->convertHypertextLink($unprotectedName, $htlink) . "</p></div>\n";
            $result .= '   <div class="oneAction-text"><p>' . $this->_traductionInstance->getTraduction($unprotectedTypemime) . "</p></div>\n";
            $result .= "  </div>\n";
            $result .= " </div>\n";
            $result .= ' <div class="oneAction-close"></div>' . "\n";
            $result .= "</div>\n";
            unset($unprotectedObject, $unprotectedName, $unprotectedTypemime, $htlink);
        } elseif ($ispresent) {
            if ($warning)
                $result = $result . $this->convertLineMessage(
                        $this->_traductionInstance->getTraduction(':::display:content:warningTaggedWarning'),
                        'warning');
            if ($protected
                && $unlocked
                && $permitWarnProtected
            )
                $result = $result . $this->convertLineMessage(
                        $this->_traductionInstance->getTraduction(':::display:content:warningObjectProctected'),
                        'warning');
            $divOpen = "<div class=\"textcontent" . $size . "\">\n\t";
            $divClose = "\n</div>\n";
            switch ($typemime) {
                case nebule::REFERENCE_OBJECT_PNG :
                case nebule::REFERENCE_OBJECT_JPEG :
                    $content = $object->getContent(0);
                    if ($content != null)
                        $result = $result . $divOpen . '<img src="?o=' . $nid
                            . '" alt="Image ' . $nid . '">' . $divClose;
                    else {
                        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash'))
                            $result = $result . $this->convertLineMessage(':::display:content:warningTooBig', 'warning');
                        else
                            $result = $result . $this->convertLineMessage(':::display:content:errorNotDisplayable', 'error');
                    }
                    break;
                case nebule::REFERENCE_OBJECT_TEXT :
                    $content = htmlspecialchars($object->getContent(0));
                    if ($content != null)
                        $result = $result . $divOpen . '<p>' . $content . '</p>' . $divClose;
                    else {
                        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash'))
                            $result = $result . $this->convertLineMessage(':::display:content:warningTooBig', 'warning');
                        else
                            $result = $result . $this->convertLineMessage(':::display:content:errorNotDisplayable', 'error');
                    }
                    unset($content);
                    break;
                case nebule::REFERENCE_OBJECT_ENTITY :
                case nebule::REFERENCE_OBJECT_HTML :
                case nebule::REFERENCE_OBJECT_CSS :
                case nebule::REFERENCE_OBJECT_APP_PHP :
                case nebule::REFERENCE_OBJECT_PHP :
                    $content = htmlspecialchars($object->getContent(0));
                    if ($content != null)
                        $result = $result . $divOpen . '<pre>' . $content . '</pre>' . $divClose;
                    else {
                        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash'))
                            $result = $result . $this->convertLineMessage(':::display:content:warningTooBig', 'warning');
                        else
                            $result = $result . $this->convertLineMessage(':::display:content:errorNotDisplayable', 'error');
                    }
                    unset($content);
                    break;
                case nebule::REFERENCE_OBJECT_MP3 :
                    $content = $object->getContent(0);
                    if ($content != null)
                        $result = $result . $divOpen . '<br /><audio controls><source src="?o=' . $nid . '" type="audio/mp3" />' . $this->_traductionInstance->getTraduction(':::warn_NoAudioTagSupport') . '</audio><br />' . $divClose;
                    else {
                        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash'))
                            $result = $result . $this->convertLineMessage(':::display:content:warningTooBig', 'warning');
                        else
                            $result = $result . $this->convertLineMessage(':::display:content:errorNotDisplayable', 'error');
                    }
                    break;
                case nebule::REFERENCE_OBJECT_OGG :
                    $content = $object->getContent(0);
                    if ($content != null)
                        $result = $result . $divOpen . '<br /><audio controls><source src="?o=' . $nid . '" type="audio/ogg" />' . $this->_traductionInstance->getTraduction(':::warn_NoAudioTagSupport') . '</audio><br />' . $divClose;
                    else {
                        if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckObjectHash'))
                            $result = $result . $this->convertLineMessage(':::display:content:warningTooBig', 'warning');
                        else
                            $result = $result . $this->convertLineMessage(':::display:content:errorNotDisplayable', 'error');
                    }
                    break;
                case nebule::REFERENCE_OBJECT_CRYPT_RSA :
                    $result = $result . $this->convertLineMessage('Chiffré, non affichable.', 'warning');
                    break;
                default :
                    $result = $result . $this->convertLineMessage('Non affichable.', 'warning');
                    break;
            }
        } else
            $result = $result . $this->convertLineMessage(':::display:content:errorNotAvailable', 'error');

        return $result;
    }

    /**
     * Prépare à afficher le contenu d'un objet comme objet pur. Version full.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertAsObjectContentFull(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertAsObjectContentSized($object, 'full', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet comme objet pur. Version full.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayAsObjectContentFull(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertAsObjectContentSized($object, 'full', $permitWarnProtected);
    }

    /**
     * Prépare à afficher le contenu d'un objet comme objet pur. Version half.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertAsObjectContentHalf(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertAsObjectContentSized($object, 'half', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet comme objet pur. Version half.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayAsObjectContentHalf(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertAsObjectContentSized($object, 'half', $permitWarnProtected);
    }

    /**
     * Prépare à afficher le contenu d'un objet comme objet pur. Version small.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return string
     */
    public function convertAsObjectContentSmall(Node $object, bool $permitWarnProtected = true): string
    {
        return $this->convertAsObjectContentSized($object, 'small', $permitWarnProtected);
    }

    /**
     * Afficher le contenu d'un objet comme objet pur. Version small.
     *
     * @param Node $object
     * @param bool $permitWarnProtected
     * @return void
     */
    public function displayAsObjectContentSmall(Node $object, bool $permitWarnProtected = true): void
    {
        echo $this->convertAsObjectContentSized($object, 'small', $permitWarnProtected);
    }

    /**
     * Prépare à afficher un objet comme image avec éventuellement un texte et un identifiant CSS.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return string
     */
    public function convertImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): string
    {
        if ($object->getID() == '0')
            return '';

        $result = '<img src="/' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $object->getID() . '"';

        if ($alt == '')
            $alt = $object->getID();
        $alt = $this->_traductionInstance->getTraduction($alt);
        $result .= ' alt="' . $alt . '" title="' . $alt . '"';

        if ($class != '')
            $result .= ' class="' . $class . '"';

        if ($id != '')
            $result .= ' id="' . $id . '"';

        if ($args != '')
            $result .= ' ' . $args;

        $result .= ' />';
        return $result;
    }

    /**
     * Prépare à afficher un objet comme image avec éventuellement un texte et un identifiant CSS.
     *
     * @param Node|string $object
     * @return string
     */
    public function convertImageURL($object): string
    {
        // Récupère une instance de l'objet.
        if (!is_a($object, 'Nebule\Library\Node'))
            $object = $this->_nebuleInstance->newObject($object);

        if ($object->getID() == '0')
            return '';

        $obj = $object->getID();
        return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $obj;
    }

    /**
     * Affiche un objet comme image avec éventuellement un texte et un identifiant CSS.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return void
     */
    public function displayImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): void
    {
        echo $this->convertImage($object, $alt, $class, $id, $args);
    }

    /**
     * Prépare à un objet comme image avec éventuellement un texte et un identifiant CSS.
     * Une recherche préalable est faite pour trouver la mise à jour la plus récente de l'objet.
     * Si l'objet commence par 'data:' c'est une image encodée en base64.
     * Retourne dans ce cas un affichage d'image.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return string
     */
    public function convertUpdateImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): string
    {
        $nid = $object->getID();

        if ($object->getID() == '0')
            return '';

        $uid = $this->_getImageUpdate($object);

        if ($uid == $nid)
            $newObjectInstance = $object;
        else
            $newObjectInstance = $this->_nebuleInstance->newObject($uid);

        return $this->convertImage($newObjectInstance, $alt, $class, $id, $args);
    }

    /**
     * Affiche un objet comme image avec éventuellement un texte et un identifiant CSS.
     * Une recherche préalable est faite pour trouver la mise à jour la plus récente de l'objet.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return void
     */
    public function displayUpdateImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): void
    {
        echo $this->convertUpdateImage($object, $alt, $class, $id, $args);
    }

    /**
     * Prépare à un objet comme image avec éventuellement un texte et un identifiant CSS.
     * L'objet est un objet virtuel qui permet juste d'adresser l'image attendu.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return string
     * TODO
     */
    public function convertReferenceImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): string
    {
        if ($object->getID() == '0')
            return '';

        $uid = $this->_getImageByReference($object);

        if ($uid == $object->getID())
            $newObjectInstance = $object;
        else
            $newObjectInstance = $this->_nebuleInstance->newObject($uid);

        return $this->convertImage($newObjectInstance, $alt, $class, $id, $args);
    }

    /**
     * Affiche un objet comme image avec éventuellement un texte et un identifiant CSS.
     * L'objet est un objet virtuel qui permet juste d'adresser l'image attendu.
     *
     * @param Node   $object
     * @param string $alt
     * @param string $class
     * @param string $id
     * @param string $args
     * @return void
     */
    public function displayReferenceImage(Node $object, string $alt = '', string $class = '', string $id = '', string $args = ''): void
    {
        echo $this->convertReferenceImage($object, $alt, $class, $id, $args);
    }



    /* --------------------------------------------------------------------------------
	 *  Fonctions internes.
	 * -------------------------------------------------------------------------------- */
    /**
     * Retourne le nom tronqué d'une entité.
     *
     * @param string $name
     * @param int    $maxsize
     * @return string
     */
    public function truncateName(string $name, int $maxsize): string
    {
        return $this->_truncateName($name, $maxsize);
    }

    /**
     * Retourne le nom tronqué d'une entité.
     *
     * @param string $name
     * @param int    $maxsize
     * @return string
     */
    private function _truncateName(string $name, int $maxsize): string
    {
        if ($maxsize == 0 || $maxsize > $this->_configurationInstance->getOptionUntyped('displayNameSize'))
            $maxsize = $this->_configurationInstance->getOptionUntyped('displayNameSize');
        if ($maxsize < 4)
            $maxsize = 4;
        if (strlen($name) > $maxsize)
            $name = substr($name, 0, ($maxsize - 3)) . '...';
        return $name;
    }

    /**
     * Prépare un lien par défaut pour un objet ou un groupe ou une conversation ou une entité si aucun lien hypertexte n'est donné.
     *
     * @param Node|Entity|Group|Conversation $object
     * @param string                         $htlink
     * @return string
     */
    public function prepareDefaultObjectOrGroupOrEntityHtlink($object, string $htlink = ''): string
    {
        return $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
    }

    /**
     * Prépare un lien par défaut pour un objet ou un groupe ou une conversation ou une entité si aucun lien hypertexte n'est donné.
     *
     * @param Node|Entity|Group|Conversation $object
     * @param string                         $htlink
     * @return string
     */
    private function _prepareDefaultObjectOrGroupOrEntityHtlink($object, string $htlink = ''): string
    {
        if ($htlink != '')
            return $htlink;
        if (is_a($object, 'Nebule\Library\Entity')) // TODO switch !
            return $this->_htlinkEntityPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Conversation'))
            return $this->_htlinkConversationPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Group'))
            return $this->_htlinkGroupPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Wallet'))
            return $this->_htlinkWalletPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Transaction'))
            return $this->_htlinkTransactionPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Token'))
            return $this->_htlinkTokenPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\TokenPool'))
            return $this->_htlinkTokenPoolPrefix . $object->getID();
        elseif (is_a($object, 'Nebule\Library\Currency'))
            return $this->_htlinkCurrencyPrefix . $object->getID();
        return $this->_htlinkObjectPrefix . $object->getID();
    }



    /* --------------------------------------------------------------------------------
	 *  Affichage des liens hypertextes.
	 * -------------------------------------------------------------------------------- */
    /**
     * ???
     * @param string $htlink
     * @return string
     * TODO
     */
    public function convertHypertextShortLink(string $htlink): string
    {
        if ($htlink == '')
            return '';
        return $htlink;
    }

    /**
     * Convertit un texte et un lien web en un lien préformaté HTML.
     *
     * @param string $text
     * @param string $htlink
     * @param string $color
     * @param string $class
     * @param string $id
     * @return string
     */
    public function convertHypertextLink(string $text, string $htlink, string $color = '', string $class = '', string $id = ''): string
    {
        if ($text == '')
            return '';

        if ($htlink == '')
            return $text;

        if ($color != '')
            $color = ' style="background:' . $color . ';"';

        if ($class != '')
            $class = ' class="' . $class . '"';

        if ($id != '')
            $id = ' id="' . $id . '"';

        $text = $this->_traductionInstance->getTraduction($text);
        return '<a href="' . $htlink . '"' . $color . $class . $id . '>' . $text . '</a>';
    }

    /**
     * Affiche un texte et un lien web en un lien préformaté HTML.
     *
     * @param string $text
     * @param string $htlink
     * @param string $color
     * @param string $class
     * @param string $id
     * @return void
     */
    public function displayHypertextLink(string $text, string $htlink, string $color = '', string $class = '', string $id = ''): void
    {
        echo $this->convertHypertextLink($text, $htlink, $color, $class, $id);
    }

    /**
     * Convertit une date standard en une date facile à lire.
     *
     * @param string $date
     * @return string
     */
    public function convertDate(string $date): string
    {
        if (substr($date, 10, 1) == 'T')
            $ret = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4) . ' ' . substr($date, 11, 2) . 'h' . substr($date, 14, 2) . "'<sub>" . substr($date, 17, 2) . "''</sub>";
        elseif (substr($date, 4, 1) == '-')
            $ret = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);
        else
            $ret = substr($date, 6, 2) . '/' . substr($date, 4, 2) . '/' . substr($date, 0, 4) . ' ' . substr($date, 8, 2) . 'h' . substr($date, 10, 2) . "'<sub>" . substr($date, 12, 2) . "''</sub>";
        return $ret;
    }

    /**
     * Affiche une date standard en une date facile à lire.
     *
     * @param string $date
     * @return void
     */
    public function displayDate(string $date): void
    {
        echo $this->convertDate($date);
    }



    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    /**
     * Tableau de cache des mises à jour d'icônes déjà recherchées.
     *
     * @var array
     */
    private $_cacheIconUpdate = array();

    /**
     * Recherche la mise à jour de l'objet d'une image.
     *
     * Fait une mise en cache du résultat.
     *
     * @param string|Node $object
     * @param boolean $useBuffer
     * @return string
     */
    private function _getImageUpdate($object, bool $useBuffer = true): string
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer'))
            $useBuffer = false;

        // Récupère une instance de l'objet.
        if (!is_a($object, 'Nebule\Library\Node'))
            $object = $this->_nebuleInstance->newObject($object);

        if ($object->getID() == '0')
            return '';

        // Si présent dans le cache, utilise la valeur stockée.
        if ($useBuffer
            && isset($this->_cacheIconUpdate[$object->getID()])
        )
            return $this->_cacheIconUpdate[$object->getID()];

        $update = $object->getUpdateNID(true, false);

        // Mémorise le résultat.
        if ($useBuffer)
            $this->_cacheIconUpdate[$object->getID()] = $update;

        return $update;
    }

    /**
     * Tableau de cache des icônes déjà recherchées par référence.
     *
     * @var array
     */
    private $_cacheIconByReference = array();

    /**
     * Recherche par référence une image.
     * Fait une mise en cache du résultat.
     *
     * @param string|Node $reference
     * @param boolean     $useBuffer
     * @return string
     */
    private function _getImageByReference($reference, bool $useBuffer = true): string
    {
        if (!$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer'))
            $useBuffer = false;

        // Récupère une instance de l'objet.
        if (!is_a($reference, 'Nebule\Library\Node'))
            $reference = $this->_nebuleInstance->newObject($reference);

        if ($reference->getID() == '0')
            return '';

        // Si présent dans le cache, utilise la valeur stockée.
        if ($useBuffer
            && isset($this->_cacheIconByReference[$reference->getID()])
        )
            return $this->_cacheIconByReference[$reference->getID()];

        // Sinon, lit l'id de l'objet référencé.
        $update = $reference->getReferencedObjectID(nebule::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'myself');
        if ($update == $reference->getID())
            $update = $reference->getReferencedObjectID(nebule::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'authority');

        // Mémorise le résultat.
        if ($useBuffer)
            $this->_cacheIconByReference[$reference->getID()] = $update;

        return $update;
    }

    /**
     * Prépare l'image de l'icône sans lien hypertexte ni encapsulation html img.
     * Cette fonction est dédiée aux icônes de l'interface dont les objets sont par défaut disponibles.
     * Mais les mises à jours de ces objets ne le sont pas forcément.
     *
     * @param string $icon
     * @return string
     */
    public function prepareIcon(string $icon): string
    {
        $updateIcon = $this->_getImageUpdate($icon);
        if ($updateIcon == $icon)
            return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $icon;
        return '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $updateIcon;
    }

    /**
     * Affiche l'image d'une icône sans lien hypertexte et avec encapsulation html img.
     *
     * @param string $icon
     * @param string $title
     * @param string $class
     * @return void
     */
    public function displayIcon(string $icon, string $title = '', string $class = ''): void
    {
        echo $this->convertIcon($icon, $title, $class);
    }

    /**
     * Prépare l'image d'une icône sans lien hypertexte et avec encapsulation html img.
     *
     * @param string $icon
     * @param string $title
     * @param string $class
     * @return string
     */
    public function convertIcon(string $icon, string $title = '', string $class = ''): string
    {
        $image = $this->prepareIcon($icon);
        if ($title != '')
            $title = 'title="' . $title . '" ';
        if ($class != '')
            $class = 'class="' . $class . '" ';
        return '<img ' . $title . 'alt="[]" src="' . $image . '" ' . $class . '/>';
    }

    /**
     * Prépare l'image de l'icône pour un objet sans lien hypertexte.
     *
     * @param Node/entity $object
     * @param string $icon
     * @param string $class
     * @return string
     */
    private function _prepareObjectIcon($object, string $icon, string $class = ''): string
    {
        $color = $object->getPrimaryColor();
        $title = $object->getFullName('all');
        $image = $this->prepareIcon($icon);
        if ($class != '')
            $class = 'class="' . $class . '" ';
        return '<img title="' . $title . '" style="background:#' . $color . ';" alt="[]" src="' . $image . '" ' . $class . '/>';
    }

    /**
     * Prépare l'image du carré de couleur de l'objet ou de l'entité sans lien hypertexte.
     *
     * @param Node/entity $object
     * @param string $class
     * @param string $title
     * @return string
     */
    private function _prepareObjectColor($object, string $class = '', string $title = ''): string
    {
        $color = $object->getPrimaryColor();
        if ($title == '')
            $title = $object->getFullName('all');
        if ($class != '')
            $class = 'class="' . $class . '" ';
        return '<img title="' . $title . '" style="background:#' . $color . ';" alt="[]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" ' . $class . '/>';
    }

    /**
     * Prépare l'image de l'objet ou de l'entité sans lien hypertexte.
     *
     * @param Node/entity $object
     * @param string $class
     * @return string
     */
    private function _prepareObjectFace($object, string $class = ''): string
    {
        $color = $object->getPrimaryColor();
        $title = $object->getFullName('all');
        if (is_a($object, 'Nebule\Library\Entity')) {
            $faceID = $object->getFaceID(64);
            if ($faceID != '0')
                $image = '?o=' . $faceID;
            else
                $image = 'o/' . $this->_getImageUpdate(self::DEFAULT_ICON_USER);
            unset($faceID);
        } elseif (is_a($object, 'Nebule\Library\Conversation'))
            $image = 'o/' . $this->_getImageUpdate(self::DEFAULT_ICON_CVTOBJ);
        elseif (is_a($object, 'Nebule\Library\Group'))
            $image = 'o/' . $this->_getImageUpdate(self::DEFAULT_ICON_GRPOBJ);
        else
            $image = 'o/' . $this->_getImageUpdate(self::DEFAULT_ICON_LO);
        if ($class != '')
            $class = 'class="' . $class . '" ';
        return '<img title="' . $title . '" style="background:#' . $color . ';" alt="[]" src="' . $image . '" ' . $class . '/>';
    }


    /**
     * Le CSS de la fonction getDisplayObject().
     *
     * @return void
     */
    private function _getDisplayObjectCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutObject {
                margin: 5px 0 0 5px;
                border: 0;
                background: none;
                display: inline-block;
                vertical-align: top;
            }

            .objectDisplayTiny {
                font-size: 16px;
            }

            .objectDisplaySmall {
                font-size: 32px;
            }

            .objectDisplayMedium {
                font-size: 64px;
            }

            .objectDisplayLarge {
                font-size: 128px;
            }

            .objectDisplayFull {
                font-size: 256px;
            }

            .objectTitle a:link, .objectTitle a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectTitle a:hover, .objectTitle a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectTitleTiny {
                height: 16px;
                font-size: 16px;
                border: 0;
            }

            .objectTitleSmall {
                height: 32px;
                font-size: 32px;
                border: 0;
            }

            .objectTitleMedium {
                height: 64px;
                font-size: 64px;
                border: 0;
            }

            .objectTitleLarge {
                height: 128px;
                font-size: 128px;
                border: 0;
            }

            .objectTitleFull {
                height: 256px;
                font-size: 256px;
                border: 0;
            }

            .objectTitleText {
                background: rgba(255, 255, 255, 0.5);
            }

            .objectTitleTinyText {
                height: 16px;
                background: none;
            }

            .objectTitleSmallText {
                height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .objectTitleMediumText {
                height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleLargeText {
                height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleFullText {
                height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .objectTitleTinyRefs {
                visibility: hidden;
            }

            .objectTitleTinyRefs img {
                visibility: hidden;
            }

            .objectTitleSmallRefs {
                height: 12px;
                line-height: 12px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 9px;
            }

            .objectTitleSmallRefs img {
                height: 12px;
                width: 12px;
            }

            .objectTitleMediumRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleMediumRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleLargeRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleLargeRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleFullRefs {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleFullRefs img {
                height: 32px;
                width: 32px;
            }

            .objectTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .objectTitleSmallName {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 14px;
            }

            .objectTitleMediumName {
                height: 24px;
                line-height: 24px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleLargeName {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 28px;
            }

            .objectTitleFullName {
                height: 64px;
                line-height: 64px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 40px;
            }

            .objectTitleID {
                height: 16px;
                font-size: 10px;
                overflow: hidden;
            }

            .objectTitleTinyFlags {
                visibility: hidden;
            }

            .objectTitleTinyFlags img {
                visibility: hidden;
            }

            .objectTitleSmallFlags {
                visibility: hidden;
            }

            .objectTitleSmallFlags img {
                visibility: hidden;
            }

            .objectTitleMediumFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleMediumFlags img {
                height: 16px;
                width: 16px;
                margin: 0 1px 0 0;
                float: left;
            }

            .objectTitleLargeFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleLargeFlags img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectTitleFullFlags {
                height: 32px;
                font-size: 32px;
            }

            .objectTitleFullFlags img {
                height: 32px;
                width: 32px;
                margin: 0 4px 0 0;
                float: left;
            }

            .objectTitleIcons img {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsInline img {
                height: 1em;
                width: 1em;
            }

            .objectTitleIconsApp {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsApp div {
                overflow: hidden;
                font-size: 12px;
                text-align: left;
                font-weight: normal;
                margin: 3px;
                color: #ffffff;
            }

            .objectTitleIconsAppShortname {
                font-size: 18px;
            }

            .objectTitleIconsAppTitle {
                font-size: 11px;
            }

            .objectTitleText0 {
                margin-left: 0;
            }

            .objectTitleText1 {
                margin-left: 1em;
            }

            .objectTitleText2 {
                margin-left: 2em;
            }

            .objectTitleStatus {
                height: 1em;
                line-height: 1em;
                overflow: hidden;
                white-space: nowrap;
                font-weight: bold;
                text-align: right;
                padding-right: 2px;
            }

            .objectDisplayTinyShort {
            }

            .objectDisplaySmallShort {
                width: 8em;
            }

            .objectDisplayMediumShort {
                width: 6em;
            }

            .objectDisplayLargeShort {
                width: 5em;
            }

            .objectDisplayFullShort {
                width: 4em;
            }

            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 310px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 470px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 590px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 758px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1014px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1190px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1590px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1910px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2038px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2390px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3830px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 4086px;
                }
            }

            .objectContent {
                font-size: 0.8rem;
                border: 0;
                padding: 3px;
                margin: 0;
                color: #000000;
                overflow: auto;
            }

            .objectContentShort {
                width: 378px;
                max-height: 378px;
            }

            .objectContentText {
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }

            .objectFlagOn {
                background: #00ff20;
            }

            .objectTitleMenuContentLayout {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.33);
                z-index: 100;
                display: none;
                font-size: 0;
            }

            .objectTitleMenuContent {
                position: fixed;
                top: 10%;
                left: 10%;
                width: 80%;
                background: rgba(240, 240, 240, 0.5);
                padding: 16px;
            }

            .objectTitleMenuContent .objectTitleTinyLong, .objectTitleMenuContent .objectTitleSmallLong, .objectTitleMenuContent .objectTitleMediumLong, .objectTitleMenuContent .objectTitleLargeLong, .objectTitleMenuContent .objectTitleFullLong {
                width: 100%;
            }

            .objectTitleMenuContent .objectTitleText {
                background: rgba(255, 255, 255, 0.66);
            }

            .objectTitleMenuContentIcons {
                height: 64px;
                width: 128px;
            }

            .objectTitleMenuContentIcons img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContent {
                background: rgba(255, 255, 255, 0.2);
                padding-top: 4px;
            }

            .objectMenuContentMsg {
                background-origin: border-box;
                font-size: 14px;
                text-align: left;
                margin-top: 1px;
                width: 100%;
                overflow: hidden;
                white-space: normal;
                min-height: 16px;
            }

            .objectMenuContentMsg img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectMenuContentMsgOK {
                background: #103020;
                color: #ffffff;
            }

            .objectMenuContentMsgWarn {
                background: #ffe080;
                color: #ff8000;
            }

            .objectMenuContentMsgError {
                background: #ffa0a0;
                color: #ff0000;
                font-family: monospace;
            }

            .objectMenuContentMsgInfo {
                background: rgba(0, 0, 0, 0.4);
                color: #ffffff;
            }

            .objectMenuContentMsgID {
                background: rgba(255, 255, 255, 0.4);
                color: #000000;
                font-family: monospace;
                font-size: 9px;
                overflow: hidden;
                white-space: nowrap;
                min-height: 4px;
            }

            .objectMenuContentMsgEmotions {
                background: rgba(255, 255, 255, 0.1);
                color: #000000;
                text-align: center;
                min-height: 24px;
            }

            .objectMenuContentMsgEmotions img {
                height: 24px;
                width: 24px;
                margin: 0 1px 0 3px;
                float: none;
            }

            .objectMenuContentMsgtargetObject {
                background: rgba(0, 0, 0, 0.4);
                font-size: 12px;
                color: #ffffff;
                white-space: nowrap;
            }

            .objectMenuContentMsgtargetObject img {
                height: 16px;
                width: 16px;
                margin: 0;
                float: none;
            }

            .objectMenuContentMsgtargetObject a:link, .objectMenuContentMsgtargetObject a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentMsgtargetObject a:hover, .objectMenuContentMsgtargetObject a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentActions {
                margin: 1px 0 0 0;
                min-height: 32px;
                padding-top: 5px;
                background: rgba(255, 255, 255, 0.2);
            }

            .objectMenuContentActionsTinyShort {
            }

            .objectMenuContentActionsSmallShort {
                width: 256px;
            }

            .objectMenuContentActionsMediumShort {
                width: 384px;
            }

            .objectMenuContentActionsLargeShort {
                width: 640px;
            }

            .objectMenuContentActionsFullShort {
                width: 1024px;
            }

            .objectMenuContentActionsTinyLong, .objectMenuContentActionsSmallLong, .objectMenuContentActionsMediumLong, .objectMenuContentActionsLargeLong, .objectMenuContentActionsFullLong {
                width: 100%;
            }

            .objectMenuContentAction {
                height: 64px;
                display: inline-block;
                margin-top: 5px;
                margin-left: 5px;
                text-align: left;
            }

            /* Correction à vérifier */
            .objectMenuContentActionNoJS {
                height: 32px;
                display: inline-block;
                margin-bottom: 1px;
                text-align: left;
            }

            .objectMenuContentActionTinyShort {
            }

            .objectMenuContentActionSmallShort {
                width: 256px;
            }

            .objectMenuContentActionMediumShort {
                width: 384px;
            }

            .objectMenuContentActionLargeShort {
                width: 210px;
                margin-right: 5px;
            }

            .objectMenuContentActionFullShort {
                width: 251px;
                margin-right: 5px;
            }

            .objectMenuContentActionTinyLong, .objectMenuContentActionSmallLong, .objectMenuContentActionMediumLong, .objectMenuContentActionLargeLong, .objectMenuContentActionFullLong {
                width: 251px;
            }

            .objectMenuContentActionSelf {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .objectMenuContentActionSelf a:link, .objectMenuContentActionSelf a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectMenuContentActionSelf a:hover, .objectMenuContentActionSelf a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectMenuContentActionType {
                background: rgba(0, 0, 0, 0.66);
                color: #ffffff;
            }

            .objectMenuContentActionType a:link, .objectMenuContentActionType a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentActionType a:hover, .objectMenuContentActionType a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentAction-icon, .objectMenuContentAction-iconNoJS {
                float: left;
                margin-right: 5px;
            }

            .objectMenuContentAction-icon img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContentAction-iconNoJS img {
                height: 32px;
                width: 32px;
            }

            .objectMenuContentAction-modname p {
                font-size: 0.7rem;
                font-style: italic;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-title p {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-text p {
                font-size: 0.8rem;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-close {
                height: 1px;
                clear: both;
            }
        </style>
        <?php
    }

    /**
     * Retourne la représentation html de l'objet en fonction des paramètres passés.
     *
     * Les paramètres d'activation de contenus :
     * - enableDisplayColor : Affiche le carré de couleur.
     *     Par défaut true : affiche le carré de couleur.
     *     enableDisplayIconApp doit être à false.
     *     Boolean
     * - enableDisplayIcon : Affiche le carré avec l'image attaché à l'objet ou l'icône de son type sur la couleur de l'objet en fond.
     *     Par défaut true : affiche le carré de l'image/icône.
     *     enableDisplayIconApp doit être à false.
     *     Boolean
     * - enableDisplayIconApp : Affiche le carré de couleur avec le nom long et court d'une application.
     *     Par défaut true : affiche le carré de couleur avec le nom.
     *     Boolean
     * - enableDisplayRefs : Affiche le(s) référence(s) de l'objet (signataire du lien).
     *     enableDisplayName doit être à true.
     *     Par défaut false : n'affiche pas la référence.
     *     Boolean
     * - enableDisplayName : Affiche le nom de l'objet.
     *     Par défaut true : affiche le nom.
     *     Boolean
     * - enableDisplayID : Affiche l'ID de l'objet.
     *     Par défaut false : n'affiche pas l'ID.
     *     Boolean
     * - enableDisplayFlags : Affiche les icônes d'état de l'objet (protection...).
     *     enableDisplayName doit être à true.
     *     Par défaut false : n'affiche pas les icônes d'état.
     *     Boolean
     * - enableDisplayFlagEmotions : Affiche les icônes des émotions de l'objet sans les compteurs.
     *     enableDisplayFlags et l'option displayEmotions doivent être à true.
     *     Par défaut false : n'affiche pas les icônes.
     *     Boolean
     * - enableDisplayFlagProtection : Affiche l'icône de protection de l'objet.
     *     enableDisplayFlags doit être à true.
     *     L'option permitProtectedObject doit être à true.
     *     Par défaut false : n'affiche pas l'icône.
     *     Boolean
     * - enableDisplayFlagObfuscate : Affiche l'icône de dissimulation de l'objet.
     *     enableDisplayFlags doit être à true.
     *     L'option permitObfuscatedLink doit être à true.
     *     Par défaut false : n'affiche pas l'icône.
     *     Boolean
     * - enableDisplayFlagUnlocked : Affiche l'icône de déverrouillage de l'entité.
     *     enableDisplayFlags doit être à true.
     *     Par défaut false : n'affiche pas l'icône.
     *     Boolean
     * - enableDisplayFlagActivated : Affiche l'icône d'activation de l'objet.
     *     enableDisplayFlags doit être à true.
     *     Par défaut false : n'affiche pas l'icône.
     *     Boolean
     * - enableDisplayFlagState : Affiche l'icône d'état de l'objet.
     *     enableDisplayFlags doit être à true.
     *     Par défaut false : n'affiche pas l'icône.
     *     Boolean
     * - enableDisplayStatus : Affiche le status de l'objet (indicatif).
     *     Par défaut false : n'affiche pas le status.
     *     Boolean
     * - enableDisplayContent : Affiche le contenu de l'objet si possible.
     *     Par défaut false : n'affiche pas le contenu.
     *     Boolean
     * - enableDisplayLink2Object : Affiche le lien HTML vers l'objet :
     *     Sur le nom de l'objet.
     *     Sur le carré de couleur et sur l'image/icône de l'objet, ou si le menu des actions est activé le menu remplace le lien HTML.
     *     Par défaut true : affiche le lien ou le menu.
     *     Boolean
     * - enableDisplayObjectActions : Affiche le menu des actions liées à l'objet.
     *     Sinon le lien de l'objet est utilisé à la place.
     *     enableDisplayLink2Object doit être à true.
     *     Par défaut true : affiche le menu.
     *     Boolean
     * - enableDisplayLink2Refs : Affiche le lien HTML vers le(s) référence(s) de l'objet.
     *     enableDisplayRefs doit être à true.
     *     Par défaut true : affiche le lien.
     *     Boolean
     * - enableDisplaySelfHook : Affiche les actions principales de l'objet utilisé.
     *     enableDisplayObjectActions doit être à true.
     *     Par défaut true : affiche les actions.
     *     Boolean
     * - enableDisplayTypeHook : Affiche les actions secondaires de l'objet par rapport à son type.
     *     enableDisplayObjectActions doit être à true.
     *     Par défaut true si enableDisplayJS : affiche les actions.
     *     Par défaut false si pas enableDisplayJS : n'affiche pas les actions.
     *     Boolean
     * - enableDisplayJS : Utilise le Java Script pour le menu des actions.
     *     Si false, le menu n'est pas caché et son contenu s'affiche sous la barre de titre de l'objet.
     *     Par défaut true : utilise le Java Script.
     *     Boolean
     *
     * Les paramètres de définition de contenus :
     * - social : Détermine le niveau social de tri des liens.
     *     Par défaut vide : utilise le niveau social par défaut.
     *     String
     * - objectType : Détermine le type d'objet pour le traitement.
     *     Par défaut null : le type est extrait en fonction du niveau social.
     *     String
     * - objectName : Détermine le nom de l'objet ou un texte de remplacement.
     *     enableDisplayName doit être à true.
     *     Par défaut null : le nom complet est extrait en fonction du niveau social.
     *     Si enableDisplayIconApp à true, le nom simple est extrait en fonction du niveau social.
     *     String
     * - objectAppShortName : Détermine le nom de l'objet ou un texte de remplacement.
     *     enableDisplayIconApp doit être à true.
     *     Par défaut null : le nom court (prénom) est extrait en fonction du niveau social.
     *     String
     * - objectIcon : Détermine l'image de l'objet.
     *     enableDisplayIcon doit être à true.
     *     Le fond est de la couleur de l'objet.
     *     Par défaut null : l'image est une icône représentant le type d'objet.
     *     String
     * - objectRefs : Détermine la liste des références de l'objet affiché, ou autres entités.
     *     Si c'est un text, affiche juste le texte après un filtre.
     *     Par défaut vide.
     *     Array of string|Object ou string
     * - link2Object : Détermine le lien HTML vers l'objet.
     *     Par défaut vide : le lien est préparé vers l'objet en fonction de son type.
     *     String
     * - flagProtection : Détermine l'icône de protection de l'objet.
     *     enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
     *     Par défaut false : icône éteinte.
     *     Boolean
     * - flagProtectionIcon : Détermine l'icône de protection de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
     *     Par défaut vide : icône de lien de chiffrement LK.
     *     String
     * - flagProtectionText : Détermine l'icône de protection de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
     *     Par défaut vide : Texte standard traduit.
     *     String
     * - flagProtectionLink : Détermine le lien HTML de l'icône de protection de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
     *     Par défaut vide.
     *     String
     * - flagObfuscate : Détermine l'icône de dissimulation de l'objet.
     *     enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
     *     Par défaut false : icône éteinte.
     *     Boolean
     * - flagObfuscateIcon : Détermine l'icône de dissimulation de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
     *     Par défaut vide : icône de lien de dissimulation LC.
     *     String
     * - flagObfuscateText : Détermine l'icône de dissimulation de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagObfuscate doivent être à true.
     *     Par défaut vide : Texte standard traduit.
     *     String
     * - flagObfuscateLink : Détermine le lien HTML de l'icône de dissimulation de l'objet.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagProtection doivent être à true.
     *     Par défaut vide.
     *     String
     * - flagUnlocked : Détermine l'icône de déverrouillage de l'entité.
     *     enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
     *     Par défaut dépend de l'état de l'entité.
     *     Si pas une entité, par défaut false : icône éteinte.
     *     Boolean
     * - flagUnlockedIcon : Détermine l'icône de déverrouillage de l'entité.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
     *     Par défaut vide : icône de lien de chiffrement LK.
     *     String
     * - flagUnlockedText : Détermine l'icône de déverrouillage de l'entité.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
     *     Par défaut vide : Texte standard traduit.
     *     String
     * - flagUnlockedLink : Détermine le lien HTML de l'icône de déverrouillage de l'entité.
     *     Permet de détourner le bouton de son usage primaire.
     *     enableDisplayFlags et enableDisplayFlagUnlocked doivent être à true.
     *     Par défaut vide.
     *     String
     * - flagActivated : Détermine l'icône d'activation de l'objet.
     *     enableDisplayFlags et enableDisplayFlagActivateded doivent être à true.
     *     Par défaut false : icône rouge en croix.
     *     Si à true, icône verte validée.
     *     Boolean
     * - flagActivatedDesc : Détermine le texte de description de l'activation de l'objet.
     *     enableDisplayFlags et enableDisplayFlagActivated doivent être à true.
     *     Par défaut vide : calcul la description de l'état de l'objet.
     *     Le texte de ces état est traduit.
     *     Par défaut est calculé par rapport à flagActivated :
     *       - false : ':::display:content:NotActived'
     *       - true  : ':::display:content:Actived'
     *     String
     * - flagState : Détermine l'icône de l'état de l'objet.
     *     enableDisplayFlags et enableDisplayFlagState doivent être à true.
     *     Par défaut vide : calcul l'état de l'objet.
     *     Les états possibles sont :
     *       - e : l'objet est taggé 'banni' ;
     *       - w : l'objet est taggé 'warning' ;
     *       - n : l'objet n'est pas présent ;
     *       - o : OK tout va bien.
     *     String
     * - flagStateDesc : Détermine le texte de description de l'état de l'objet.
     *     enableDisplayFlags et enableDisplayFlagState doivent être à true.
     *     Par défaut vide : calcul la description de l'état de l'objet.
     *     Le texte de ces état est traduit.
     *     Par défaut est calculé par rapport à flagState :
     *       - e : ':::display:content:errorBan'
     *       - w : ':::display:content:warningTaggedWarning'
     *       - n : ':::display:content:errorNotAvailable'
     *       - o : ':::display:content:OK'
     *     String
     * - flagMessage : Détermine un message à afficher au niveau des flags dépliés.
     *     enableDisplayFlags doit être à true.
     *     N'apparait pas comme icône simple.
     *     Par défaut vide : pas de message.
     *     String
     * - flagTargetObject : Détermine un objet 'cible' (ou pas) à afficher au niveau des flags dépliés.
     *     enableDisplayFlags doit être à true.
     *     N'apparait pas comme icône simple. L'objet est affiché sous forme tiny.
     *     Par défaut vide : pas d'objet.
     *     String (hex)
     * - status : Détermine le status de l'objet.
     *     Par défaut vide : pas de status.
     *     String
     * - displaySize : Détermine la taille de l'affichage de l'élément complet.
     *     Tailles disponibles :
     *     - tiny : très petite taille correspondant à un carré de base de 16 pixels de large.
     *         Certains éléments ne sont pas affichés.
     *     - small : petite taille correspondant à un carré de base de 32 pixels de large.
     *     - medium : taille moyenne correspondant à un carré de base de 64 pixels de large par défaut.
     *     - large : grande taille correspondant à un carré de base de 128 pixels de large par défaut.
     *     - full : très grande taille correspondant à un carré de base de 256 pixels de large par défaut.
     *     Par défaut medium : taille moyenne.
     *     String
     * - displayRatio : Détermine la forme de l'affichage par son ratio dans la mesure du possible si pas d'affichage du contenu de l'objet.
     *     Ratios disponibles :
     *     - square : forme carrée de 2x2 displaySize.
     *     - short : forme plate courte de 6x1 displaySize.
     *     - long : forme plate longue de toute largeure disponible.
     *     Par défaut short : forme plate courte.
     *     String
     * - selfHookList : Détermine la liste des point d'encrage à utiliser pour les actions sur l'objet utilisé.
     *     Par défaut vide : est préparé en fonction de selfHookName.
     *     Array
     * - selfHookName : Détermine le nom du point d'encrage à utiliser pour les actions sur l'objet utilisé.
     *     Par défaut vide : est préparé en fonction du type d'objet :
     *     - objet : selfMenuObject
     *     - entité : selfMenuEntity
     *     - groupe : selfMenuGroup
     *     - conversation : selfMenuConversation
     *     String
     * - typeHookName : Détermine le nom du point d'encrage à utiliser pour les actions sur l'objet par rapport à son type.
     *     Par défaut vide : est préparé en fonction du type d'objet :
     *     - objet : typeMenuObject
     *     - entité : typeMenuEntity
     *     - groupe : typeMenuGroup
     *     - conversation : typeMenuConversation
     *     String
     *
     * Exemple de table de paramètres avec les valeurs par défaut :
     *
     * $param = array(
     * 'enableDisplayColor' => true,
     * 'enableDisplayIcon' => true,
     * 'enableDisplayIconApp' => false,
     * 'enableDisplayRefs' => false,
     * 'enableDisplayName' => true,
     * 'enableDisplayID' => false,
     * 'enableDisplayFlags' => false,
     * 'enableDisplayFlagEmotions' => true,
     * 'enableDisplayFlagProtection' => false,
     * 'enableDisplayFlagObfuscate' => false,
     * 'enableDisplayFlagUnlocked' => false,
     * 'enableDisplayFlagActivated' => false,
     * 'enableDisplayFlagState' => false,
     * 'enableDisplayStatus' => false,
     * 'enableDisplayContent' => false,
     * 'enableDisplayLink2Object' => true,
     * 'enableDisplayObjectActions' => true,
     * 'enableDisplayLink2Refs' => true,
     * 'enableDisplaySelfHook' => true,
     * 'enableDisplayTypeHook' => true,
     * 'enableDisplayJS' => true,
     * 'social' => '',
     * 'objectType' => null,
     * 'objectName' => null,
     * 'objectAppShortName' => null,
     * 'objectIcon' => null,
     * 'objectRefs' => array(),
     * 'link2Object' => '',
     * 'flagProtection' => false,
     * 'flagProtectionIcon' => '',
     * 'flagProtectionText' => '',
     * 'flagProtectionLink' => '',
     * 'flagObfuscate' => false,
     * 'flagObfuscateIcon' => '',
     * 'flagObfuscateText' => '',
     * 'flagObfuscateLink' => '',
     * 'flagUnlocked' => false,
     * 'flagUnlockedIcon' => '',
     * 'flagUnlockedText' => '',
     * 'flagUnlockedLink' => '',
     * 'flagActivated' => false,
     * 'flagActivatedDesc' => '',
     * 'flagState' => '',
     * 'flagStateDesc' => '',
     * 'flagMessage' => '',
     * 'flagTargetObject' => '',
     * 'status' => '',
     * 'displaySize' => 'medium',
     * 'displayRatio' => 'short',
     * 'selfHookList' => array(),
     * 'selfHookName' => '',
     * 'typeHookName' => '',
     * );
     *
     * @param string|Node|entity $object
     * @param array $param
     * @return string
     */
    public function getDisplayObject($object, array $param): string
    {
        $result = '';

        // Prépare l'objet.
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);

        // Prépare les paramètres d'activation de contenus.
        if (!isset($param['enableDisplayColor'])
            || $param['enableDisplayColor'] !== false
        )
            $param['enableDisplayColor'] = true;

        if (!isset($param['enableDisplayIcon'])
            || $param['enableDisplayIcon'] !== false
        )
            $param['enableDisplayIcon'] = true;

        if (!isset($param['enableDisplayIconApp'])
            || $param['enableDisplayIconApp'] !== true
        )
            $param['enableDisplayIconApp'] = false;

        if (!isset($param['enableDisplayRefs'])
            || $param['enableDisplayRefs'] !== true
        )
            $param['enableDisplayRefs'] = false;

        if (!isset($param['enableDisplayName'])
            || $param['enableDisplayName'] !== false
        )
            $param['enableDisplayName'] = true;

        if (!isset($param['enableDisplayID'])
            || $param['enableDisplayID'] !== true
        )
            $param['enableDisplayID'] = false;

        if (!isset($param['enableDisplayFlags'])
            || $param['enableDisplayFlags'] !== true
        )
            $param['enableDisplayFlags'] = false;

        if (!isset($param['enableDisplayFlagEmotions'])
            || $param['enableDisplayFlagEmotions'] !== true
        )
            $param['enableDisplayFlagEmotions'] = false;

        if (!isset($param['enableDisplayFlagProtection'])
            || $param['enableDisplayFlagProtection'] !== true
            || !$this->_configurationInstance->getOptionAsBoolean('permitProtectedObject')
        )
            $param['enableDisplayFlagProtection'] = false;

        if (!isset($param['enableDisplayFlagObfuscate'])
            || $param['enableDisplayFlagObfuscate'] !== true
            || !$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $param['enableDisplayFlagObfuscate'] = false;

        if (!isset($param['enableDisplayFlagUnlocked'])
            || $param['enableDisplayFlagUnlocked'] !== true
        )
            $param['enableDisplayFlagUnlocked'] = false;

        if (!isset($param['enableDisplayFlagActivated'])
            || $param['enableDisplayFlagActivated'] !== true
        )
            $param['enableDisplayFlagActivated'] = false;

        if (!isset($param['enableDisplayFlagState'])
            || $param['enableDisplayFlagState'] !== true
        )
            $param['enableDisplayFlagState'] = false;

        if (!isset($param['enableDisplayStatus'])
            || $param['enableDisplayStatus'] !== true
        )
            $param['enableDisplayStatus'] = false;

        if (!isset($param['enableDisplayContent'])
            || $param['enableDisplayContent'] !== true
        )
            $param['enableDisplayContent'] = false;

        if (!isset($param['enableDisplayObjectActions'])
            || $param['enableDisplayObjectActions'] !== false
        )
            $param['enableDisplayObjectActions'] = true;

        if (!isset($param['enableDisplayLink2Object'])
            || $param['enableDisplayLink2Object'] !== false
        )
            $param['enableDisplayLink2Object'] = true;

        if (!isset($param['enableDisplayLink2Refs'])
            || $param['enableDisplayLink2Refs'] !== false
        )
            $param['enableDisplayLink2Refs'] = true;

        if (!isset($param['enableDisplayJS'])
            || $param['enableDisplayJS'] !== false
        )
            $param['enableDisplayJS'] = true;
        if (!$this->_configurationInstance->getOptionAsBoolean('permitJavaScript'))
            $param['enableDisplayJS'] = false;

        if (!isset($param['enableDisplaySelfHook'])
            || $param['enableDisplaySelfHook'] !== false
        )
            $param['enableDisplaySelfHook'] = true;

        if (!isset($param['enableDisplayTypeHook'])) {
            if ($param['enableDisplayJS'])
                $param['enableDisplayTypeHook'] = true;
            else
                $param['enableDisplayTypeHook'] = false;
        }
        if ($param['enableDisplayTypeHook'] !== false)
            $param['enableDisplayTypeHook'] = true;

        // Prépare les paramètres de définition de contenus.
        if (!isset($param['social'])
            || !is_string($param['social'])
            || $param['social'] == ''
        )
            $param['social'] = 'all'; // Par défaut vide.
        else {
            $socialList = $this->_nebuleInstance->getSocialInstance()->getSocialNames();
            $ok = false;
            foreach ($socialList as $s) {
                if ($param['social'] == $s) {
                    $ok = true;
                    break;
                }
            }
            unset($socialList);
            if (!$ok)
                $param['social'] = 'all'; // Par défaut all.
        }

        if (!isset($param['objectType'])
            || $param['objectType'] == null
        )
            $param['objectType'] = $object->getType($param['social']); // Par défaut extrait le type de l'objet.

        /**
         * Le nom complet de l'objet à afficher.
         * Si c'est une application, c'est le nom simple qui est affiché.
         */
        $contentDisplayName = '';
        if ($param['enableDisplayName']
            || $param['enableDisplayIconApp']
        ) {
            if (!isset($param['objectName'])
                || $param['objectName'] == null
            ) {
                if ($param['enableDisplayIconApp'])
                    $param['objectName'] = $object->getName($param['social']); // Par défaut extrait le nom simple de l'objet (application).
                else
                    $param['objectName'] = $object->getFullName($param['social']); // Par défaut extrait le nom complet de l'objet.
            }
            $contentDisplayName = trim(filter_var($param['objectName'], FILTER_SANITIZE_STRING));
        } else
            $param['objectName'] = '';

        /**
         * Le nom court d'une application.
         */
        $contentDisplayAppShortName = '';
        if ($param['enableDisplayIconApp']) {
            if (!isset($param['objectAppShortName'])
                || $param['objectAppShortName'] == null
            )
                $param['objectAppShortName'] = $object->getSurname($param['social']); // Par défaut extrait le surnom de l'objet (application).
            $contentDisplayAppShortName = trim(filter_var($param['objectAppShortName'], FILTER_SANITIZE_STRING));
        } else
            $param['objectAppShortName'] = '';


        if (!isset($param['flagProtection'])
            || $param['flagProtection'] !== true
        )
            $param['flagProtection'] = false; // Par défaut à false.
        if ($param['enableDisplayFlagProtection']) {
            if (!isset($param['flagProtectionIcon'])
                || $param['flagProtectionIcon'] == ''
                || !Node::checkNID($param['flagProtectionIcon'])
                || !$this->_ioInstance->checkLinkPresent($param['flagProtectionIcon'])
            )
                $param['flagProtectionIcon'] = self::DEFAULT_ICON_LK;
            if (isset($param['flagProtectionText']))
                $param['flagProtectionText'] = trim(filter_var($param['flagProtectionText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagProtectionText'])
                || trim($param['flagProtectionText']) == ''
            ) {
                if ($param['flagProtection'])
                    $param['flagProtectionText'] = ':::display:object:flag:protected';
                else
                    $param['flagProtectionText'] = ':::display:object:flag:unprotected';
            }
            if (isset($param['flagProtectionLink']))
                $param['flagProtectionLink'] = trim(filter_var($param['flagProtectionLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagProtectionLink'])
                || trim($param['flagProtectionLink']) == ''
            )
                $param['flagProtectionLink'] = null;
        }

        if (!isset($param['flagObfuscate'])
            || $param['flagObfuscate'] !== true
        )
            $param['flagObfuscate'] = false; // Par défaut à false.
        if ($param['enableDisplayFlagObfuscate']) {
            if (!isset($param['flagObfuscateIcon'])
                || $param['flagObfuscateIcon'] == ''
                || !Node::checkNID($param['flagObfuscateIcon'])
                || !$this->_ioInstance->checkLinkPresent($param['flagObfuscateIcon'])
            )
                $param['flagObfuscateIcon'] = self::DEFAULT_ICON_LC;
            if (isset($param['flagObfuscateText']))
                $param['flagObfuscateText'] = trim(filter_var($param['flagObfuscateText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagObfuscateText'])
                || trim($param['flagObfuscateText']) == ''
            ) {
                if ($param['flagObfuscate'])
                    $param['flagObfuscateText'] = ':::display:object:flag:obfuscated';
                else
                    $param['flagObfuscateText'] = ':::display:object:flag:unobfuscated';
            }
            if (isset($param['flagObfuscateLink']))
                $param['flagObfuscateLink'] = trim(filter_var($param['flagObfuscateLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagObfuscateLink'])
                || trim($param['flagObfuscateLink']) == ''
            )
                $param['flagObfuscateLink'] = null;
        }

        if (!isset($param['flagUnlocked'])) {
            $param['flagUnlocked'] = false; // Par défaut à false.
            if (is_a($object, 'Nebule\Library\Entity')) {
                // Extrait l'état de verrouillage de l'objet entité.
                $param['flagUnlocked'] = $object->issetPrivateKeyPassword();
                // Vérifie si c'est l'entité courante.
                if ($object->getID() == $this->_nebuleInstance->getCurrentEntity()
                    && $this->_unlocked
                )
                    $param['flagUnlocked'] = true;
            }
        }
        // Lisse la valeur binaire.
        if ($param['flagUnlocked'] !== true) {
            $param['flagUnlocked'] = false; // Par défaut à false.
        }
        if ($param['enableDisplayFlagUnlocked']) {
            if (!isset($param['flagUnlockedIcon'])
                || $param['flagUnlockedIcon'] == ''
                || !Node::checkNID($param['flagUnlockedIcon'])
                || !$this->_ioInstance->checkLinkPresent($param['flagUnlockedIcon'])
            )
                $param['flagUnlockedIcon'] = self::DEFAULT_ICON_KEY;
            if (isset($param['flagUnlockedText']))
                $param['flagUnlockedText'] = trim(filter_var($param['flagUnlockedText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagUnlockedText'])
                || trim($param['flagUnlockedText']) == ''
            ) {
                if ($param['flagUnlocked'])
                    $param['flagUnlockedText'] = ':::display:object:flag:locked';
                else
                    $param['flagUnlockedText'] = ':::display:object:flag:unlocked';
            }
            if (isset($param['flagUnlockedLink']))
                $param['flagUnlockedLink'] = trim(filter_var($param['flagUnlockedLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagUnlockedLink'])
                || trim($param['flagUnlockedLink']) == ''
            )
                $param['flagUnlockedLink'] = null;
        }

        if (!isset($param['flagActivated'])
            || $param['flagActivated'] !== true
        )
            $param['flagActivated'] = false; // Par défaut à false.

        if ($param['enableDisplayFlagActivated']) {
            if (!isset($param['flagActivatedDesc'])
                || strlen(trim($param['flagActivatedDesc'])) == 0
            ) {
                if ($param['flagActivated'])
                    $param['flagActivatedDesc'] = ':::display:content:Activated';
                else
                    $param['flagActivatedDesc'] = ':::display:content:NotActivated';
            } else
                $param['flagActivatedDesc'] = trim($param['flagActivatedDesc']);
        }

        $flagStateContentIcon = '';
        $flagStateContentDesc = '';
        if ($param['enableDisplayFlagState']) {
            if (!isset($param['flagState'])
                || strlen(trim($param['flagState'])) == 0
            ) {
                if ($object->getMarkDanger())
                    $param['flagState'] = 'e';
                elseif ($object->getMarkWarning())
                    $param['flagState'] = 'w';
                elseif ($object->checkPresent())
                    $param['flagState'] = 'o';
                else
                    $param['flagState'] = 'n';
            }
            if ($param['flagState'] == 'e') {
                $flagStateContentIcon = self::DEFAULT_ICON_IERR;
                $flagStateContentDesc = ':::display:content:errorBan';
            } elseif ($param['flagState'] == 'w') {
                $flagStateContentIcon = self::DEFAULT_ICON_IWARN;
                $flagStateContentDesc = ':::display:content:warningTaggedWarning';
            } elseif ($param['flagState'] == 'o') {
                $flagStateContentIcon = self::DEFAULT_ICON_IOK;
                $flagStateContentDesc = ':::display:content:OK';
            } else {
                $param['flagState'] = 'n';
                $flagStateContentIcon = self::DEFAULT_ICON_IERR;
                $flagStateContentDesc = ':::display:content:errorNotAvailable';
            }
            if (isset($param['flagStateDesc'])
                && strlen(trim($param['flagStateDesc'])) != 0
            )
                $flagStateContentDesc = trim(filter_var($param['flagStateDesc'], FILTER_SANITIZE_STRING));
        } else {
            $param['flagState'] = 'n';
            $param['flagStateDesc'] = '';
        }

        if (!isset($param['flagMessage'])
            || trim($param['flagMessage']) == ''
        )
            $param['flagMessage'] = null; // Par défaut vide.
        else
            $param['flagMessage'] = trim(filter_var($param['flagMessage'], FILTER_SANITIZE_STRING));

        if (!isset($param['flagTargetObject'])
            || trim($param['flagTargetObject']) == ''
        )
            $param['flagTargetObject'] = null; // Par défaut vide.
        else {
            $param['flagTargetObject'] = trim(filter_var($param['flagTargetObject'], FILTER_SANITIZE_STRING));
            if (!Node::checkNID($param['flagTargetObject'])) {
                $param['flagTargetObject'] = null;
            }
        }

        if (!isset($param['displaySize'])) {
            $param['displaySize'] = 'medium';
            $sizeCSS = 'Medium';
        } else {
            switch ($param['displaySize']) {
                case 'tiny':
                    $sizeCSS = 'Tiny';
                    break;
                case 'small':
                    $sizeCSS = 'Small';
                    break;
                case 'large':
                    $sizeCSS = 'Large';
                    break;
                case 'full':
                    $sizeCSS = 'Full';
                    break;
                default:
                    $param['displaySize'] = 'medium';
                    $sizeCSS = 'Medium';
                    break;
            }
        }

        if (!isset($param['displayRatio'])) {
            $param['displayRatio'] = 'short';
            $ratioCSS = 'Short';
        } else {
            switch ($param['displayRatio']) {
                case 'square':
                    $ratioCSS = 'Square';
                    break;
                case 'long':
                    $ratioCSS = 'Long';
                    break;
                default:
                    $param['displayRatio'] = 'short';
                    $ratioCSS = 'Short';
                    break;
            }
        }

        if ($param['enableDisplaySelfHook']) {
            if (isset($param['selfHookName']))
                $param['selfHookName'] = trim(filter_var($param['selfHookName'], FILTER_SANITIZE_STRING));
            else
                $param['selfHookName'] = '';
            if ($param['selfHookName'] == '') {
                if (is_a($object, 'Nebule\Library\Entity'))
                    $param['selfHookName'] = 'selfMenuEntity';
                elseif (is_a($object, 'Nebule\Library\Conversation'))
                    $param['selfHookName'] = 'selfMenuConversation';
                elseif (is_a($object, 'Nebule\Library\Group'))
                    $param['selfHookName'] = 'selfMenuGroup';
                elseif (is_a($object, 'Nebule\Library\Transaction'))
                    $param['selfHookName'] = 'selfMenuTransaction';
                elseif (is_a($object, 'Nebule\Library\Wallet'))
                    $param['selfHookName'] = 'selfMenuWallet';
                elseif (is_a($object, 'Nebule\Library\Token'))
                    $param['selfHookName'] = 'selfMenuToken';
                elseif (is_a($object, 'Nebule\Library\TokenPool'))
                    $param['selfHookName'] = 'selfMenuTokenPool';
                elseif (is_a($object, 'Nebule\Library\Currency'))
                    $param['selfHookName'] = 'selfMenuCurrency';
                else
                    $param['selfHookName'] = 'selfMenuObject';
            }
        } else
            $param['selfHookName'] = '';

        if ($param['enableDisplayTypeHook']) {
            if (isset($param['typeHookName']))
                $param['typeHookName'] = trim(filter_var($param['typeHookName'], FILTER_SANITIZE_STRING));else
                $param['typeHookName'] = '';
            if ($param['typeHookName'] == '') {
                if (is_a($object, 'Nebule\Library\Entity'))
                    $param['typeHookName'] = 'typeMenuEntity';
                elseif (is_a($object, 'Nebule\Library\Conversation'))
                    $param['typeHookName'] = 'typeMenuConversation';
                elseif (is_a($object, 'Nebule\Library\Group'))
                    $param['typeHookName'] = 'typeMenuGroup';
                elseif (is_a($object, 'Nebule\Library\Transaction'))
                    $param['typeHookName'] = 'typeMenuTransaction';
                elseif (is_a($object, 'Nebule\Library\Wallet'))
                    $param['typeHookName'] = 'typeMenuWallet';
                elseif (is_a($object, 'Nebule\Library\Token'))
                    $param['typeHookName'] = 'typeMenuToken';
                elseif (is_a($object, 'Nebule\Library\TokenPool'))
                    $param['typeHookName'] = 'typeMenuTokenPool';
                elseif (is_a($object, 'Nebule\Library\Currency'))
                    $param['typeHookName'] = 'typeMenuCurrency';
                else
                    $param['typeHookName'] = 'typeMenuObject';
            }
        } else
            $param['typeHookName'] = '';

        if (!isset($param['selfHookList'])
            || !is_array($param['selfHookList'])
        )
            $param['selfHookList'] = array();

        // Résoud les conflits.
        if ($param['displaySize'] == 'tiny') {
            $param['enableDisplayLink2Refs'] = false;
            $param['enableDisplayObjectActions'] = false;
            $param['enableDisplayRefs'] = false;
            $param['enableDisplayFlags'] = false;
            $param['enableDisplayStatus'] = false;
            $param['enableDisplayContent'] = false;
        }

        if ($param['displaySize'] == 'small') {
            $param['enableDisplayFlags'] = false;
            $param['enableDisplayStatus'] = false;
        }

        if ($param['enableDisplayContent']
            && $param['displayRatio'] == 'square'
        ) {
            $param['displayRatio'] = 'short';
            $ratioCSS = 'Short';
        }

        if ($param['enableDisplayIconApp']) {
            $param['enableDisplayColor'] = false;
            $param['enableDisplayIcon'] = false;
        }

        if (!$param['enableDisplayColor']
            && !$param['enableDisplayIcon']
            && !$param['enableDisplayIconApp']
        )
            $param['enableDisplayObjectActions'] = false;

        if (!$param['enableDisplayName']) {
            $param['enableDisplayRefs'] = false;
            $param['enableDisplayFlags'] = false;
            $param['enableDisplayStatus'] = false;
        }

        if (!$this->_configurationInstance->getOptionUntyped('displayEmotions'))
            $param['enableDisplayFlagEmotions'] = false;

        if ($param['displaySize'] == 'tiny'
            || $param['displaySize'] == 'small'
            || ($param['displaySize'] == 'medium' && $param['enableDisplayFlags'])
            || !$param['enableDisplayName']
        )
            $param['enableDisplayID'] = false;

        //if ( $param['displaySize'] == 'large' ) $param['enableDisplayID'] == true;

        // Prépare les contenus.
        $objectColor = $object->getPrimaryColor();
        $ObjectActionsID = '0';
        if ($param['enableDisplayObjectActions']
            && $param['enableDisplayJS']
        )
            $ObjectActionsID = bin2hex($this->_nebuleInstance->getCryptoInstance()->getRandom(8, Crypto::RANDOM_PSEUDO));
        $contentDisplayColor = '';
        if ($param['enableDisplayColor']) {
            $contentDisplayColor = '<img title="' . $contentDisplayName;
            $contentDisplayColor .= '" style="background:#' . $objectColor;
            $contentDisplayColor .= ';" alt="[C]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" ';
            if ($param['enableDisplayObjectActions']
                && $param['enableDisplayJS']
            )
                $contentDisplayColor .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayColor .= '/>';
        }

        $contentDisplayIcon = '';
        if ($param['enableDisplayIcon']) {
            if (!isset($param['objectIcon'])
                || $param['objectIcon'] == null
            )
                $param['objectIcon'] = '';
            $contentDisplayIcon = '<img title="' . $contentDisplayName;
            $contentDisplayIcon .= '" style="background:#' . $objectColor;
            $contentDisplayIcon .= ';" alt="[I]" src="' . $this->_getDisplayObjectIcon($object, $param['objectIcon']) . '" ';
            if ($param['enableDisplayObjectActions']
                && $param['enableDisplayJS']
            )
                $contentDisplayIcon .= "onclick=\"display_menu('objectTitleMenu-" . $ObjectActionsID . "');\" ";
            $contentDisplayIcon .= '/>';
        }

        if ($param['enableDisplayIconApp']) {
            $contentDisplayIcon = '<div class="objectTitleIconsApp" style="background:#' . $objectColor . ';">';
            $contentDisplayIcon .= '<div><span class="objectTitleIconsAppShortname">' . $contentDisplayAppShortName . '</span><br /><span class="objectTitleIconsAppTitle">' . $contentDisplayName . '</span></div>';
            $contentDisplayIcon .= '</div>';
        }

        $titleLinkOpenImg = '';
        $titleLinkOpenName = '';
        $titleLinkCloseImg = '';
        $titleLinkCloseName = '';
        if ($param['enableDisplayLink2Object']) {
            if ($param['enableDisplayObjectActions']
                && $param['enableDisplayJS']
            ) {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenName = '<a href="' . $param['link2Object'] . '">';
                else
                    $titleLinkOpenName = '<a href="' . $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object) . '">';
                $titleLinkCloseName = '</a>';
            } else {
                if (isset($param['link2Object'])
                    && $param['link2Object'] != null
                )
                    $titleLinkOpenImg = '<a href="' . $param['link2Object'] . '">' . "\n";
                else
                    $titleLinkOpenImg = '<a href="' . $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object) . '">';
                $titleLinkOpenName = $titleLinkOpenImg;
                $titleLinkCloseImg = '</a>';
                $titleLinkCloseName = $titleLinkCloseImg;
            }
        }

        $status = '';
        if ($param['enableDisplayStatus']
            && isset($param['status'])
        ) {
            $status = trim(filter_var($param['status'], FILTER_SANITIZE_STRING));
            if ($status == '')
                $status = $this->getTraduction($param['objectType']);
            if ($status == '')
                $param['enableDisplayStatus'] = false;
        }

        // Prépare le menu si besoin.
        $divTitleMenuOpen = '';
        $divTitleMenuClose = '';
        $divTitleMenuTitleOpen = '';
        $divTitleMenuTitleClose = '';
        $divTitleMenuIconsOpen = '';
        $divTitleMenuIconsClose = '';
        $divTitleMenuContentOpen = '';
        $divTitleMenuContentClose = '';
        $divTitleMenuActionsOpen = '';
        $divTitleMenuActionsClose = '';
        $menuContent = '';
        $menuActions = '';
        if ($param['enableDisplayLink2Object']
            && $param['enableDisplayObjectActions']
        ) {
            $menuContent = '   <div class="objectMenuContentMsg objectMenuContentMsgID">ID:';
            $menuContent .= $object->getID();
            $menuContent .= '</div>' . "\n";
            if ($param['enableDisplayFlags']) {
                if ($param['enableDisplayFlagState']) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagState'] == 'e')
                        $menuContent .= 'Error';
                    elseif ($param['flagState'] == 'w')
                        $menuContent .= 'Warn';
                    elseif ($param['flagState'] == 'n')
                        $menuContent .= 'Error';
                    else
                        $menuContent .= 'OK';
                    $menuContent .= '">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        false,
                        $flagStateContentIcon,
                        $flagStateContentDesc,
                        '');
                    $menuContent .= $this->_traductionInstance->getTraduction($flagStateContentDesc);
                    $menuContent .= '</div>' . "\n";
                }
                if ($param['enableDisplayFlagProtection']) {
                    if ($param['flagProtectionLink'] != '')
                        $menuContent .= '<a href="' . $param['flagProtectionLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagProtection'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        $param['flagProtection'],
                        $param['flagProtectionIcon'],
                        $param['flagProtectionText'],
                        $param['flagProtectionText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagProtectionText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagProtectionLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($param['enableDisplayFlagObfuscate']) {
                    if ($param['flagObfuscateLink'] != '')
                        $menuContent .= '<a href="' . $param['flagObfuscateLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagObfuscate'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        $param['flagObfuscate'],
                        $param['flagObfuscateIcon'],
                        $param['flagObfuscateText'],
                        $param['flagObfuscateText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagObfuscateText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagObfuscateLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($param['enableDisplayFlagUnlocked']) {
                    if ($param['flagUnlockedLink'] != '')
                        $menuContent .= '<a href="' . $param['flagUnlockedLink'] . '">';
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagUnlocked'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        $param['flagUnlocked'],
                        $param['flagUnlockedIcon'],
                        $param['flagUnlockedText'],
                        $param['flagUnlockedText']);
                    $menuContent .= $this->_traductionInstance->getTraduction($param['flagUnlockedText']);
                    $menuContent .= '</div>' . "\n";
                    if ($param['flagUnlockedLink'] != '')
                        $menuContent .= '</a>';
                }
                if ($param['enableDisplayFlagActivated']) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsg';
                    if ($param['flagActivated'])
                        $menuContent .= 'OK';
                    else
                        $menuContent .= 'Info';
                    $menuContent .= '">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        $param['flagActivated'],
                        self::DEFAULT_ICON_LL,
                        ':::display:object:flag:unactivated',
                        ':::display:object:flag:activated');
                    if ($param['flagActivated'])
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:activated');
                    else
                        $menuContent .= $this->_traductionInstance->getTraduction(':::display:object:flag:unactivated');
                    $menuContent .= '</div>' . "\n";
                }
                if ($param['flagMessage'] != null) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgInfo">';
                    $menuContent .= $this->_getDisplayObjectFlag(
                        false,
                        self::DEFAULT_ICON_IINFO,
                        '',
                        '-');
                    $menuContent .= $param['flagMessage'];
                    $menuContent .= '</div>' . "\n";
                }
                if ($param['flagTargetObject'] != null) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgtargetObject">';
                    $paramTiny = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'tiny',
                        'displayRatio' => 'short',
                    );
                    // ATTENTION à une possible boucle infinie !
                    $menuContent .= $this->getDisplayObject($param['flagTargetObject'], $paramTiny);
                    unset($paramTiny);
                    $menuContent .= '</div>' . "\n";
                }
                // $param['flagTargetObject']
                if ($param['enableDisplayFlagEmotions']
                    && $param['enableDisplayJS']
                ) {
                    $menuContent .= '   <div class="objectMenuContentMsg objectMenuContentMsgEmotions">';
                    $menuContent .= $this->_getDisplayObjectFlagEmotions($object, true);
                    $menuContent .= '</div>' . "\n";
                }
            }

            if ($param['enableDisplayJS']) {
                $divTitleMenuOpen = '  <div class="objectTitleMenuContentLayout" id="objectTitleMenu-' . $ObjectActionsID . '" '
                    . "onclick=\"display_hide('objectTitleMenu-" . $ObjectActionsID . "');\" >\n";
                $divTitleMenuOpen .= '   <div class="objectTitleMenuContent">' . "\n";
                $divTitleMenuTitleOpen = '    <div class="objectTitleMedium objectTitleMediumLong">' . "\n";
                $divTitleMenuIconsOpen = '    <div class="objectTitleIcons">' . "\n";
                $titleMenuIcons = $contentDisplayColor . $contentDisplayIcon . "\n";
                $divTitleMenuIconsClose = '    </div>' . "\n";
                $divTitleMenuTitleClose = '    </div>' . "\n";
                $divTitleMenuContentOpen = '    <div class="objectMenuContent">' . "\n";
                $divTitleMenuContentClose = '    </div>' . "\n";
                $menuActions = $this->_getDisplayObjectHookList(
                    $param['selfHookName'],
                    $param['typeHookName'],
                    $object,
                    true,
                    $sizeCSS . 'Long',
                    $param['selfHookList']);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $sizeCSS . 'Long">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
                $divTitleMenuClose = '   </div></div>' . "\n";
            } else {
                $divMenuContentOpen = '  <div class="objectMenuContent objectDisplay' . $sizeCSS . ' objectDisplay' . $sizeCSS . $ratioCSS . '">' . "\n";
                $divMenuContentClose = '  </div>' . "\n";
                $menuActions = $this->_getDisplayObjectHookList(
                    $param['selfHookName'],
                    $param['typeHookName'],
                    $object,
                    false,
                    $sizeCSS . $ratioCSS,
                    $param['selfHookList']);
                if ($menuActions != '') {
                    $divTitleMenuActionsOpen = '<div class="objectMenuContentActions objectMenuContentActions' . $sizeCSS . $ratioCSS . '">' . "\n";
                    $divTitleMenuActionsClose = ' <div class="objectMenuContentAction-close"></div>' . "\n</div>\n";
                }
            }
        }

        // Assemble les contenus.
        $divDisplayOpen = '';
        $divDisplayClose = '';
        $divTitleOpen = '';
        $divTitleClose = '';
        $titleContent = '';
        $divTitleIconsOpen = '';
        $divTitleIconsClose = '';
        $titleIconsContent = '';
        $divTitleTextOpen = '';
        $divTitleTextClose = '';
        $titleTextContent = '';
        $divTitleRefsOpen = '';
        $divTitleRefsClose = '';
        $titleRefsContent = '';
        $divTitleNameOpen = '';
        $divTitleNameClose = '';
        $titleNameContent = '';
        $divTitleIdOpen = '';
        $divTitleIdClose = '';
        $titleIdContent = '';
        $divTitleFlagsOpen = '';
        $divTitleFlagsClose = '';
        $titleFlagsContent = '';
        $divTitleStatusOpen = '';
        $divTitleStatusClose = '';
        $titleStatusContent = '';
        $divObjectOpen = '';
        $divObjectClose = '';
        $objectContent = '';
        if ($param['displaySize'] == 'tiny')
            $result = $titleLinkOpenName . '<span style="font-size:1em" class="objectTitleIconsInline">' . $contentDisplayColor . $contentDisplayIcon . '</span>' . $contentDisplayName . $titleLinkCloseName;
        else {
            $divDisplayOpen = '<div class="layoutObject">' . "\n";
            $divDisplayClose = '</div>' . "\n";
            $divTitleOpen = ' <div class="objectTitle objectDisplay' . $sizeCSS . ' objectTitle' . $sizeCSS . ' objectDisplay' . $sizeCSS . $ratioCSS . '">' . "\n";
            $divTitleClose = ' </div>' . "\n";
            $divTitleIconsOpen = '  <div class="objectTitleIcons">';
            $divTitleIconsClose = '</div>' . "\n";
            if ($param['enableDisplayColor']
                || $param['enableDisplayIcon']
                || $param['enableDisplayIconApp']
            )
                $titleIconsContent = $contentDisplayColor . $contentDisplayIcon;
            if ($param['enableDisplayName']) {
                $padding = 0;
                if ($param['enableDisplayColor'])
                    $padding += 1;
                if ($param['enableDisplayIcon'])
                    $padding += 1;
                if ($param['enableDisplayIconApp'])
                    $padding += 1;
                $divTitleTextOpen = '  <div class="objectTitleText objectTitle' . $sizeCSS . 'Text objectTitleText' . $padding . '">' . "\n";
                $divTitleTextClose = '  </div>' . "\n";
                $divTitleRefsOpen = '   <div class="objectTitleRefs objectTitle' . $sizeCSS . 'Refs">';
                $divTitleRefsClose = '</div>' . "\n";
                $divTitleNameOpen = '   <div class="objectTitleName objectTitle' . $sizeCSS . 'Name">';
                $divTitleNameClose = '</div>' . "\n";
                $divTitleFlagsOpen = '   <div class="objectTitleFlags objectTitle' . $sizeCSS . 'Flags">' . "\n";
                $divTitleFlagsClose = '   </div>' . "\n";
                $divTitleStatusOpen = '    <div class="objectTitleStatus">';
                $divTitleStatusClose = '</div>' . "\n";
                if ($param['enableDisplayRefs'] && sizeof($param['objectRefs']) > 0 && $param['objectRefs'] !== null)
                    $titleRefsContent = $this->_getDisplayObjectRefs($param['objectRefs']);
                if ($param['enableDisplayID']) {
                    $divTitleIdOpen = '    <div class="objectTitleID">';
                    $divTitleIdClose = '</div>' . "\n";
                    $titleIdContent = $object->getID();
                }
                $titleNameContent = $contentDisplayName;
                if ($param['enableDisplayFlags']) {
                    if ($param['enableDisplayFlagState']) {
                        $titleFlagsContent .= $this->_getDisplayObjectFlag(
                            false,
                            $flagStateContentIcon,
                            $flagStateContentDesc,
                            '');
                    }
                    if ($param['enableDisplayFlagProtection']) {
                        if ($param['flagProtectionLink'] != '') {
                            $titleFlagsContent .= '<a href="' . $param['flagProtectionLink'] . '">';
                        }
                        $titleFlagsContent .= $this->_getDisplayObjectFlag(
                            $param['flagProtection'],
                            $param['flagProtectionIcon'],
                            $param['flagProtectionText'],
                            $param['flagProtectionText']);
                        if ($param['flagProtectionLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($param['enableDisplayFlagObfuscate']) {
                        if ($param['flagObfuscateLink'] != '')
                            $titleFlagsContent .= '<a href="' . $param['flagObfuscateLink'] . '">';
                        $titleFlagsContent .= $this->_getDisplayObjectFlag(
                            $param['flagObfuscate'],
                            $param['flagObfuscateIcon'],
                            $param['flagObfuscateText'],
                            $param['flagObfuscateText']);
                        if ($param['flagObfuscateLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($param['enableDisplayFlagUnlocked']) {
                        if ($param['flagUnlockedLink'] != '')
                            $titleFlagsContent .= '<a href="' . $param['flagUnlockedLink'] . '">';
                        $titleFlagsContent .= $this->_getDisplayObjectFlag(
                            $param['flagUnlocked'],
                            $param['flagUnlockedIcon'],
                            $param['flagUnlockedText'],
                            $param['flagUnlockedText']);
                        if ($param['flagUnlockedLink'] != '')
                            $titleFlagsContent .= '</a>';
                    }
                    if ($param['enableDisplayFlagActivated']) {
                        $titleFlagsContent .= $this->_getDisplayObjectFlag(
                            $param['flagActivated'],
                            self::DEFAULT_ICON_LL,
                            ':::display:object:flag:unactivated',
                            ':::display:object:flag:activated');
                    }
                    if ($param['enableDisplayFlagEmotions'])
                        $titleFlagsContent .= $this->_getDisplayObjectFlagEmotions($object, false);
                }
                if ($param['enableDisplayStatus'])
                    $titleStatusContent = $status;
            }
            $titleContent = $titleLinkOpenImg . "\n" . $divTitleIconsOpen . $titleIconsContent . $divTitleIconsClose . $titleLinkCloseImg . "\n";
            $titleContent .= $divTitleTextOpen;
            $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
            $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
            $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
            $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
            $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
            $titleContent .= $divTitleFlagsClose;
            $titleContent .= $divTitleTextClose;
            if ($param['enableDisplayJS']
                && $param['enableDisplayObjectActions']
            ) {
                $titleContent .= $divTitleMenuOpen;
                $titleContent .= $divTitleMenuTitleOpen;
                $titleContent .= $divTitleMenuIconsOpen . $titleMenuIcons . $divTitleMenuIconsClose;
                $titleContent .= $divTitleTextOpen;
                $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
                $titleContent .= $divTitleNameOpen . $titleLinkOpenName . $titleNameContent . $titleLinkCloseName . $divTitleNameClose;
                $titleContent .= $divTitleIdOpen . $titleIdContent . $divTitleIdClose;
                $titleContent .= $divTitleFlagsOpen . $titleFlagsContent;
                $titleContent .= $divTitleStatusOpen . $titleStatusContent . $divTitleStatusClose;
                $titleContent .= $divTitleFlagsClose;
                $titleContent .= $divTitleTextClose;
                $titleContent .= $divTitleMenuTitleClose;
                $titleContent .= $divTitleMenuContentOpen . $menuContent . $divTitleMenuContentClose;
                $titleContent .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
                $titleContent .= $divTitleMenuClose;
            }

            if ($param['enableDisplayContent'])
                $objectContent = $this->getDisplayObjectContent($object, $param['displaySize'], $param['displayRatio']);

            // Prépare le résultat à afficher.
            $result = $divDisplayOpen;
            $result .= $divTitleOpen . $titleContent . $divTitleClose;
            if (!$param['enableDisplayJS']
                && $param['enableDisplayObjectActions']
            ) {
                $result .= $divMenuContentOpen . $menuContent . $divMenuContentClose;
                $result .= $divTitleMenuActionsOpen . $menuActions . $divTitleMenuActionsClose;
            }
            $result .= $divObjectOpen . $objectContent . $divObjectClose;
            $result .= $divDisplayClose;
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare l'icône de l'objet.
     * Si une icône est imposée, elle est utilisée.
     * Sinon fait une recherche par référence en fonction du type de l'objet.
     * Une mise à jour éventuelle de l'icône est recherchée.
     * Si l'objet de l'icône est présent, génère un chemin direct pour améliorer les performances.
     *
     * @param Node   $object
     * @param string $icon
     * @return string
     */
    private function _getDisplayObjectIcon(Node $object, string $icon = ''): string
    {
//$this->_metrologyInstance->addLog('MARK input icon='.$icon, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        if ($icon != ''
            && $this->_ioInstance->checkLinkPresent($icon)
        ) {
//$this->_metrologyInstance->addLog('MARK if 1', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $instanceIcon = $this->_nebuleInstance->newObject($icon);
        } else {
//$this->_metrologyInstance->addLog('MARK if 2', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            if (is_a($object, 'Nebule\Library\Entity'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_ENTITY);
            elseif (is_a($object, 'Nebule\Library\Conversation'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_CONVERSATION);
            elseif (is_a($object, 'Nebule\Library\Group'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_GROUP);
            elseif (is_a($object, 'Nebule\Library\Wallet'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT); // TODO
            elseif (is_a($object, 'Nebule\Library\Transaction'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT); // TODO
            elseif (is_a($object, 'Nebule\Library\Token'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT); // TODO
            elseif (is_a($object, 'Nebule\Library\TokenPool'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT); // TODO
            elseif (is_a($object, 'Nebule\Library\Currency'))
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT); // TODO
            else
                $icon = $this->_getImageByReference(self::REFERENCE_ICON_OBJECT);
//$this->_metrologyInstance->addLog('MARK found icon='.$icon, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
            $instanceIcon = $this->_nebuleInstance->newObject($icon);
        }

        // Cherche une mise à jour éventuelle.
        $updateIcon = $this->_getImageUpdate($icon); // FIXME TODO ERROR
//$this->_metrologyInstance->addLog('MARK found updateIcon='.$updateIcon, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');
        $updateIcon = '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256'; // FIXME
//$this->_metrologyInstance->addLog('MARK force updateIcon='.$updateIcon, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000');

        // Retourne un chemin direct si l'objet est présent.
        if ($this->_ioInstance->checkObjectPresent($updateIcon))
            return nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '/' . $updateIcon;
        return '?' . nebule::NEBULE_LOCAL_OBJECTS_FOLDER . '=' . $updateIcon;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare la liste des références (signataires).
     *
     * Si l'entrée est un texte, retourne le texte (à afficher).
     *
     * @param array $list
     * @return string
     */
    private function _getDisplayObjectRefs(array $list): string
    {
        $result = '';

        if (sizeof($list) == 0)
            return '';

        $size = sizeof($list);
        $count = 0;

        foreach ($list as $object) {
            $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
            $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object);
            $color = $this->_prepareObjectColor($object);
            $icon = '';
            if ($size < 11)
                $icon = $this->_prepareObjectFace($object);
            $name = '';
            if ($size < 3)
                $name = $this->_truncateName($object->getFullName('all'), 0);
            $result .= $this->convertHypertextLink($color . $icon . $name, $htlink);
            if ($size < 11)
                $result .= ' ';

            $count++;
            if ($count > 30) {
                $result .= '+';
                break;
            }
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare les icônes des indicateurs (flags).
     *
     * @param boolean $on
     * @param string  $image
     * @param string  $descOff
     * @param string  $descOn
     * @return string
     */
    private function _getDisplayObjectFlag(bool $on, string $image, string $descOff, string $descOn): string
    {
        $result = '';

        $image = $this->prepareIcon($image);
        if ($on)
            $desc = $this->_traductionInstance->getTraduction($descOn);
        else
            $desc = $this->_traductionInstance->getTraduction($descOff);
        $result .= '<img title="' . $desc . '" ';
        if ($on)
            $result .= 'class="objectFlagOn" ';
        $result .= 'alt="[C]" src="' . $image . '" />';

        return $result;
    }

    /**
     * Pour les fonctions getDisplayObject() et getDisplayMessage().
     * Prépare les icônes des émotions avec ou sans les compteurs ($counts).
     *
     * @param Node    $object
     * @param boolean $counts
     * @return string
     */
    private function _getDisplayObjectFlagEmotions(Node $object, bool $counts = false): string
    {
        // Vérifie si les émotions doivent être affichées.
        if (!$this->_configurationInstance->getOptionUntyped('displayEmotions'))
            return '';
        $result = '';

        $listEmotions = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
        );
        $listEmotions0 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET0,
        );
        $listEmotions1 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
        );

        foreach ($listEmotions as $emotion) {
            // Génère la base du lien html pour revenir au bon endroit en toute situation.
            $htlink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_nebuleInstance->getCurrentConversation();

            // Préparation du lien.
            $source = $object->getID();
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($emotion);
            $meta = $this->_nebuleInstance->getCurrentEntity();

            // Détermine si l'émotion a été marqué par l'entité en cours.
            if ($object->getMarkEmotion($emotion, 'myself')) {
                // Création du lien.
                $action = 'x';
                $link = $action . '_' . $source . '_' . $target . '_' . $meta;
                $htlink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Préparation de l'icône de l'émotion.
                $rid = $this->_nebuleInstance->newObject($listEmotions1[$emotion]);
                $icon = $this->convertReferenceImage($rid, $emotion, 'iconInlineDisplay');
            } else {
                // Création du lien.
                $action = 'f';
                $link = $action . '_' . $source . '_' . $target . '_' . $meta;
                $htlink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Préparation de l'icône de l'émotion.
                $rid = $this->_nebuleInstance->newObject($listEmotions0[$emotion]);
                $icon = $this->convertReferenceImage($rid, $emotion, 'iconInlineDisplay');
            }

            // Si connecté, l'icône est active.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            )
                $result .= $this->convertHypertextLink($icon, $htlink);
            else
                $result .= $icon;

            // Détermine le nombre d'entités qui ont marqué cette émotion.
            if ($counts) {
                $count = $object->getMarkEmotionSize($emotion, 'all');
                if ($count > 0)
                    $result .= $count . ' ';
            }
        }

        return $result;
    }

    /**
     * Le CSS de la fonction getDisplayMessage().
     *
     * @return void
     */
    private function _getDisplayMessageCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayMessage(). */
            .layoutMessage {
                margin: 5px 0 0 5px;
                border: 0;
                background: none;
                display: inline-block;
                vertical-align: top;
            }

            .messageDisplay {
                background: rgba(255, 255, 255, 0.333);
            }

            .messageDisplay .layoutObject {
                margin: 0;
            }

            .messageDisplaySmall {
                font-size: 32px;
            }

            .messageDisplayMedium {
                font-size: 64px;
            }

            .messageDisplayLarge {
                font-size: 128px;
            }

            .messageDisplaySmallShort {
                width: 8em;
            }

            .messageDisplayMediumShort {
                width: 6em;
            }

            .messageDisplayLargeShort {
                width: 5em;
            }

            .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 310px;
                }
            }

            @media screen and (min-width: 480px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 470px;
                }
            }

            @media screen and (min-width: 600px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 590px;
                }
            }

            @media screen and (min-width: 768px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 758px;
                }
            }

            @media screen and (min-width: 1024px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 914px;
                }
            }

            @media screen and (min-width: 1200px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 943px;
                }
            }

            @media screen and (min-width: 1600px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 1343px;
                }
            }

            @media screen and (min-width: 1920px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 1663px;
                }
            }

            @media screen and (min-width: 2048px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 1040px;
                }
            }

            @media screen and (min-width: 2400px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 1240px;
                }
            }

            @media screen and (min-width: 3840px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 2000px;
                }
            }

            @media screen and (min-width: 4096px) {
                .messageDisplaySmallLong, .messageDisplayMediumLong, .messageDisplayLargeLong {
                    width: 2050px;
                }
            }

            .messageHeader a:link, .messageHeader a:visited, .messageFooter a:link, .messageFooter a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .messageHeader a:hover, .messageHeader a:active, .messageFooter a:hover, .messageFooter a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .messageHeaderSmall {
                height: 16px;
                padding: 2px 2px 0 2px;
                font-size: 16px;
                margin-bottom: 2px;
                border: 0;
            }

            .messageHeaderMedium {
                height: 16px;
                padding: 2px 2px 0 2px;
                font-size: 16px;
                margin-bottom: 2px;
                border: 0;
            }

            .messageHeaderLarge {
                height: 64px;
                padding: 5px 5px 0 5px;
                font-size: 64px;
                margin-bottom: 2px;
                border: 0;
            }

            .messageFooterSmall {
                height: 16px;
                padding: 0 2px 2px 2px;
                font-size: 16px;
                margin-bottom: 8px;
                border: 0;
            }

            .messageFooterMedium {
                height: 16px;
                padding: 0 2px 2px 2px;
                font-size: 16px;
                margin-bottom: 8px;
                border: 0;
            }

            .messageFooterLarge {
                height: 64px;
                padding: 0 5px 5px 5px;
                font-size: 64px;
                margin-bottom: 16px;
                border: 0;
            }

            .messageHeaderDate {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
                float: right;
                color: #454545
            }

            .messageFooterEntity {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
                float: right;
            }

            .messageFooterEntity img {
                height: 16px;
                width: 16px;
            }

            .messageFooterEmots {
                height: 16px;
                font-size: 16px;
                float: left;
            }

            .messageFooterEmots img {
                height: 1em;
                width: 1em;
            }

            .messageHeaderFlags {
                height: 16px;
                font-size: 16px;
                float: left;
            }

            .messageHeaderFlags img {
                height: 1em;
                width: 1em;
            }

            .messageHeaderFlagsPrt img {
                margin-left: 3px;
            }

            .messageFooterFlags {
                height: 16px;
                font-size: 16px;
                float: left;
            }

            .messageFooterFlags img {
                height: 1em;
                width: 1em;
            }

            .messageFooterFlagsPrt img {
                margin-left: 3px;
            }

            .messageContent {
                font-size: 0.8rem;
                border: 0;
                padding: 3px;
                margin: 0;
                color: #000000;
                overflow: auto;
            }

            .messageContentShort {
                width: 378px;
                max-height: 378px;
            }

            .messageContentText {
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
            }

            .messageContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .messageContentImage img {
                height: auto;
                max-width: 100%;
            }
        </style>
        <?php
    }

    /**
     * Retourne la représentation html du message d'une conversation en fonction des paramètres passés.
     *
     * Les paramètres d'activation de contenus :
     * - enableDisplayColor : Affiche le carré de couleur.
     *     Par défaut true : affiche le carré de couleur.
     *     Boolean
     * - enableDisplayIcon : Affiche le carré avec l'image du type message sur la couleur de l'objet en fond.
     *     Par défaut true : affiche le carré de l'image/icône.
     *     Boolean
     *
     * Les paramètres de définition de contenus :
     * - social : Détermine le niveau social de tri des liens.
     *     Par défaut vide : utilise le niveau social par défaut.
     *     String
     *
     * @param Link $link
     * @param array $param
     * @return string
     */
    public function getDisplayMessage(Link $link, array $param): string
    {
        $result = '';

        // Prépare l'objet.
        $messageInstance = $this->_nebuleInstance->convertIdToTypedObjectInstance($link->getParsed()['bl/rl/nid2']);
        $signerInstance = $this->_nebuleInstance->convertIdToTypedObjectInstance($link->getSigners()[0]); // FIXME [0] correction sauvage !

        // Prépare les paramètres d'activation de contenus.
        if (!isset($param['enableDisplayColor'])
            || $param['enableDisplayColor'] !== false
        )
            $param['enableDisplayColor'] = true; // Par défaut à true.

        if (!isset($param['enableDisplayIcon'])
            || $param['enableDisplayIcon'] !== false
        )
            $param['enableDisplayIcon'] = true; // Par défaut à true.

        if (!isset($param['enableDisplayRefs'])
            || $param['enableDisplayRefs'] !== false
        )
            $param['enableDisplayRefs'] = true; // Par défaut à true.

        if (!isset($param['objectRefs'])
            || sizeof($param['objectRefs']) == 0
        )
            $param['objectRefs'] = array($signerInstance);

        if (!isset($param['enableDisplayFlagProtection'])
            || $param['enableDisplayFlagProtection'] !== true
            || !$this->_configurationInstance->getOptionAsBoolean('permitProtectedObject')
        )
            $param['enableDisplayFlagProtection'] = false; // Par défaut à false.

        if (!isset($param['enableDisplayFlagObfuscate'])
            || $param['enableDisplayFlagObfuscate'] !== true
            || !$this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $param['enableDisplayFlagObfuscate'] = false; // Par défaut à false.

        if (!isset($param['enableDisplayFlagState'])
            || $param['enableDisplayFlagState'] !== false
        )
            $param['enableDisplayFlagState'] = true; // Par défaut à true.


        if ($param['enableDisplayFlagProtection']) {
            if (!isset($param['flagProtection'])
                || !is_bool($param['flagProtection'])
            )
                $param['flagProtection'] = $messageInstance->getMarkProtected();

            if (!isset($param['flagProtectionIcon'])
                || $param['flagProtectionIcon'] == ''
                || !Node::checkNID($param['flagProtectionIcon'])
                || !$this->_ioInstance->checkLinkPresent($param['flagProtectionIcon'])
            )
                $param['flagProtectionIcon'] = self::DEFAULT_ICON_LK;
            if (isset($param['flagProtectionText']))
                $param['flagProtectionText'] = trim(filter_var($param['flagProtectionText'], FILTER_SANITIZE_STRING));
            if (!isset($param['flagProtectionText'])
                || trim($param['flagProtectionText']) == ''
            ) {
                if ($param['flagProtection'])
                    $param['flagProtectionText'] = ':::display:object:flag:protected';
                else
                    $param['flagProtectionText'] = ':::display:object:flag:unprotected';
            }
            if (isset($param['flagProtectionLink']))
                $param['flagProtectionLink'] = trim(filter_var($param['flagProtectionLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagProtectionLink'])
                || trim($param['flagProtectionLink']) == ''
            )
                $param['flagProtectionLink'] = null;
        }


        if ($param['enableDisplayFlagObfuscate']) {
            if (!isset($param['flagObfuscate'])
                || !is_bool($param['flagObfuscate'])
            )
                $param['flagObfuscate'] = $link->getObfuscated();

            if (!isset($param['flagObfuscateIcon'])
                || $param['flagObfuscateIcon'] == ''
                || !Node::checkNID($param['flagObfuscateIcon'])
                || !$this->_ioInstance->checkLinkPresent($param['flagObfuscateIcon'])
            )
                $param['flagObfuscateIcon'] = self::DEFAULT_ICON_LC;
            if (isset($param['flagObfuscateText'])) {
                $param['flagObfuscateText'] = trim(filter_var($param['flagObfuscateText'], FILTER_SANITIZE_STRING));
            }
            if (!isset($param['flagObfuscateText'])
                || trim($param['flagObfuscateText']) == ''
            ) {
                if ($param['flagObfuscate'])
                    $param['flagObfuscateText'] = ':::display:object:flag:obfuscated';
                else
                    $param['flagObfuscateText'] = ':::display:object:flag:unobfuscated';
            }
            if (isset($param['flagObfuscateLink']))
                $param['flagObfuscateLink'] = trim(filter_var($param['flagObfuscateLink'], FILTER_SANITIZE_URL));
            if (!isset($param['flagObfuscateLink'])
                || trim($param['flagObfuscateLink']) == ''
            )
                $param['flagObfuscateLink'] = null;
        } else
            $param['flagObfuscate'] = false;


        $sizeCSS = 'Medium';
        if (!isset($param['displaySize'])) {
            $param['displaySize'] = 'medium';
            $sizeCSS = 'Medium';
        } else {
            switch ($param['displaySize']) {
                case 'tiny':
                    $sizeCSS = 'Tiny';
                    break;
                case 'small':
                    $sizeCSS = 'Small';
                    break;
                case 'large':
                    $sizeCSS = 'Large';
                    break;
                case 'full':
                    $sizeCSS = 'Full';
                    break;
                default:
                    $param['displaySize'] = 'medium';
                    $sizeCSS = 'Medium';
                    break;
            }
        }

        $ratioCSS = 'short';
        if (!isset($param['displayRatio'])) {
            $param['displayRatio'] = 'short';
            $ratioCSS = 'Short';
        } else {
            switch ($param['displayRatio']) {
                case 'long':
                    $ratioCSS = 'Long';
                    break;
                default:
                    $param['displayRatio'] = 'short';
                    $ratioCSS = 'Short';
                    break;
            }
        }

        // Assemble les contenus.
        $divDisplayOpen = '<div class="layoutMessage">' . "\n";
        $divDisplayOpen .= ' <div class="messageDisplay messageDisplay' . $sizeCSS . ' messageDisplay' . $sizeCSS . $ratioCSS . '">' . "\n";
        $divDisplayClose = ' </div>' . "\n";
        $divDisplayClose .= '</div>' . "\n";

        $divHeaderOpen = '';
        $divHeaderClose = '';
        $headerContent = '';

        $divObjectOpen = '';
        $divObjectClose = '';
        $objectContent = '';
        $divObjectFlagsOpen = '';
        $divObjectFlagsClose = '';
        $objectFlagsContent = '';

        $divFooterOpen = '';
        $divFooterClose = '';
        $footerContent = '';
        $divHeaderFlagsOpen = '';
        $divHeaderFlagsClose = '';
        $headerFlagsContent = '';
        $divHeaderDateOpen = '';
        $divHeaderDateClose = '';
        $headerDateContent = '';

        $divFooterFlagsOpen = '';
        $divFooterFlagsClose = '';
        $footerFlagsContent = '';
        $divFooterEmotsOpen = '';
        $divFooterEmotsClose = '';
        $footerEmotsContent = '';
        $divFooterEntityOpen = '';
        $divFooterEntityClose = '';
        $footerEntityContent = '';

        // Si taille petite (1).
        if ($param['displaySize'] == 'small') {
            $divFooterOpen = ' <div class="messageFooter messageFooter' . $sizeCSS . '">' . "\n";
            $divFooterClose = ' </div>' . "\n";
            $divFooterFlagsOpen = '  <div class="messageFooterFlags">';
            $divFooterFlagsClose = '</div>' . "\n";
            $divFooterEntityOpen = '  <div class="messageFooterEntity">';
            $divFooterEntityClose = '</div>' . "\n";

            // Ajoute le bandeau bas du message.
            $footerFlagsContent .= $this->_getDisplayMessageFlags($messageInstance, $link, $param, 'Footer');

            // Prépare l'entité.
            $footerEntityContent .= $this->_getDisplayObjectRefs($param['objectRefs']);

            // Assemble la partie footer.
            $footerContent .= $divFooterFlagsOpen . $footerFlagsContent . $divFooterFlagsClose;
            $footerContent .= $divFooterEntityOpen . $footerEntityContent . $divFooterEntityClose;
        } // Si taille moyenne (2).
        elseif ($param['displaySize'] == 'medium') {
            $divHeaderOpen = ' <div class="messageHeader messageHeader' . $sizeCSS . '">' . "\n";
            $divHeaderClose = ' </div>' . "\n";
            $divHeaderFlagsOpen = '  <div class="messageHeaderFlags">';
            $divHeaderFlagsClose = '</div>' . "\n";
            $divHeaderDateOpen = '  <div class="messageHeaderDate">';
            $divHeaderDateClose = '</div>' . "\n";
            $divFooterOpen = ' <div class="messageFooter messageFooter' . $sizeCSS . '">' . "\n";
            $divFooterClose = ' </div>' . "\n";
            $divFooterEmotsOpen = '  <div class="messageFooterEmots">';
            $divFooterEmotsClose = '</div>' . "\n";
            $divFooterEntityOpen = '  <div class="messageFooterEntity">';
            $divFooterEntityClose = '</div>' . "\n";

            // Ajoute le bandeau haut du message.
            $headerFlagsContent .= $this->_getDisplayMessageFlags($messageInstance, $link, $param, 'Footer');

            // Prépare la date.
            $headerDateContent = $this->convertDate($link->getDate());

            // Ajoute le bandeau bas du message.
            $footerEmotsContent .= $this->_getDisplayObjectFlagEmotions($messageInstance, false);

            // Prépare l'entité.
            $footerEntityContent .= $this->_getDisplayObjectRefs($param['objectRefs']);

            // Assemble la partie header.
            $headerContent .= $divHeaderFlagsOpen . $headerFlagsContent . $divHeaderFlagsClose;
            $headerContent .= $divHeaderDateOpen . $headerDateContent . $divHeaderDateClose;

            // Assemble la partie footer.
            $footerContent .= $divFooterEmotsOpen . $footerEmotsContent . $divFooterEmotsClose;
            $footerContent .= $divFooterEntityOpen . $footerEntityContent . $divFooterEntityClose;
        } // Si taille grande (3).
        elseif ($param['displaySize'] == 'large') // TODO pas utilisé...
        {
            // Prépare le titre de l'objet message.
            $paramObject = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayIconApp' => false,
                'enableDisplayRefs' => true,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => false,
                'enableDisplayFlagEmotions' => $param['enableDisplayFlagEmotions'],
                'enableDisplayFlagProtection' => $this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'),
                'enableDisplayFlagObfuscate' => $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'),
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagActivated' => false,
                'enableDisplayFlagState' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'enableDisplayLink2Object' => true,
                'enableDisplayObjectActions' => true,
                'enableDisplayLink2Refs' => true,
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => true,
                'enableDisplayJS' => true,
                'social' => '',
                'objectType' => null,
                'objectName' => $param['name'],
                'objectAppShortName' => null,
                'objectIcon' => $param['objectIcon'],
                'objectRefs' => array($param['signer']),
                'link2Object' => '',
                'flagProtection' => false,
                'flagProtectionIcon' => '',
                'flagProtectionText' => '',
                'flagProtectionLink' => '',
                'flagObfuscate' => false,
                'flagObfuscateIcon' => '',
                'flagObfuscateText' => '',
                'flagObfuscateLink' => '',
                'flagUnlocked' => false,
                'flagUnlockedIcon' => '',
                'flagUnlockedText' => '',
                'flagUnlockedLink' => '',
                'flagActivated' => false,
                'flagActivatedDesc' => '',
                'flagState' => '',
                'flagStateDesc' => '',
                'flagMessage' => '',
                'flagTargetObject' => '',
                'status' => '',
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'selfHookList' => array(),
                'selfHookName' => '',
                'typeHookName' => '',
            );
        }
        // Sinon rien.

        // Affiche le contenu, si pas protégé et présent.
        if ($messageInstance->getMarkProtected()
            && !$this->_unlocked
        ) {
            $paramInfo = array(
                'displaySize' => 'small',
                'enableDisplayIcon' => true,
                'displayRatio' => $param['displayRatio'],
                'informationType' => 'warn',
            );
            $objectContent = $this->getDisplayInformation(':::display:content:ObjectProctected', $paramInfo);
        } else
            $objectContent = $this->getDisplayObjectContent($messageInstance, $param['displaySize'], $param['displayRatio'], false);

        // Prépare le résultat à afficher.
        $result = $divDisplayOpen;
        $result .= $divHeaderOpen . $headerContent . $divHeaderClose;
        $result .= $divObjectOpen . $objectContent . $divObjectClose;
        $result .= $divFooterOpen . $footerContent . $divFooterClose;
        $result .= $divDisplayClose;

        return $result;
    }

    /**
     * Pour la fonction getDisplayMessage().
     * Prépare les icônes des drapeaux.
     *
     * @param Node   $messageInstance
     * @param Link   $link
     * @param array  $param
     * @param string $cssCode
     * @return string
     */
    private function _getDisplayMessageFlags(Node $messageInstance, Link $link, array $param, string $cssCode): string
    {
        $result = '<span class="message' . $cssCode . 'FlagsObj">';
        // Ajoute le lien.
        if (isset($param['link2Object'])
            && $param['link2Object'] != null
        )
            $result .= '<a href="' . $param['link2Object'] . '">';
        else
            $result .= '<a href="' . $this->_prepareDefaultObjectOrGroupOrEntityHtlink($messageInstance) . '">';

        // Ajoute la couleur de l'objet.
        if (isset($param['enableDisplayColor']))
            $result .= $this->_prepareObjectColor($messageInstance);

        // Ajoute l'image de l'objet.
        if (isset($param['enableDisplayIcon']))
            $result .= $this->_prepareObjectIcon($messageInstance, self::DEFAULT_ICON_CVTOBJ);
        $result .= '</a></span>';

        // Prépare les flags.
        if ($param['enableDisplayFlags']) {
            $result .= '<span class="message' . $cssCode . 'FlagsPrt">';

            if ($param['enableDisplayFlagState']) {
                if (!isset($param['flagState'])
                    || strlen(trim($param['flagState'])) == 0
                ) {
                    if ($link->getValid())
                        $param['flagState'] = 'o';
                    else
                        $param['flagState'] = 'e';
                }

                if ($param['flagState'] == 'o') {
                    $result .= $this->_getDisplayObjectFlag(
                        false,
                        self::DEFAULT_ICON_IOK,
                        ':::display:link:OK',
                        '');
                } else {
                    $result .= $this->_getDisplayObjectFlag(
                        false,
                        self::DEFAULT_ICON_IERR,
                        ':::display:link:errorInvalid',
                        '');
                }
            }

            if ($param['enableDisplayFlagProtection']) {
                if ($param['flagProtectionLink'] != '')
                    $result .= '<a href="' . $param['flagProtectionLink'] . '">';
                $result .= $this->_getDisplayObjectFlag(
                    $param['flagProtection'],
                    $param['flagProtectionIcon'],
                    $param['flagProtectionText'],
                    $param['flagProtectionText']);
                if ($param['flagProtectionLink'] != '')
                    $result .= '</a>';
            }
            if ($param['enableDisplayFlagObfuscate']) {
                if ($param['flagObfuscateLink'] != '')
                    $result .= '<a href="' . $param['flagObfuscateLink'] . '">';
                $result .= $this->_getDisplayObjectFlag(
                    $param['flagObfuscate'],
                    $param['flagObfuscateIcon'],
                    $param['flagObfuscateText'],
                    $param['flagObfuscateText']);
                if ($param['flagObfuscateLink'] != '')
                    $result .= '</a>';
            }
            $result .= '</span>' . "\n";
        }

        return $result;
    }

    /**
     * Pour la fonction getDisplayObject().
     * Prépare les actions définies par un point d'ancrage pour un objet.
     * Le point d'ancrage permet aux modules d'ajouter des actions.
     *
     * @param string  $selfHookName
     * @param string  $typeHookName
     * @param Node    $object
     * @param boolean $enableDisplayJS
     * @param string  $size
     * @param array   $appHookList
     * @return string
     */
    private function _getDisplayObjectHookList(string $selfHookName, string $typeHookName, Node $object,
                                               bool $enableDisplayJS, string $size, array $appHookList = array()): string
    {
        $result = '';
        $dispHookList = array();
        $dispHookListT = array();
        $modules = $this->_applicationInstance->getModulesListInstances();

        $iconNoJS = 'NoJS';
        if ($enableDisplayJS)
            $iconNoJS = '';

        // Ajoute les actions demandées spécifiquement et individuellement.
        $i = 0;
        if (sizeof($appHookList) != 0) {
            foreach ($appHookList as $appHook) {
                if ($appHook['name'] != '') {
                    $dispHookList[$i]['moduleName'] = '&nbsp;';
                    $dispHookList[$i]['name'] = $this->getTraduction($appHook['name']);
                    $dispHookList[$i]['icon'] = $appHook['icon'];
                    if ($dispHookList[$i]['icon'] == '')
                        $dispHookList[$i]['icon'] = self::DEFAULT_ICON_LSTOBJ;
                    $dispHookList[$i]['desc'] = $this->getTraduction($appHook['desc']);
                    $dispHookList[$i]['link'] = $appHook['link'];
                    if (isset($appHook['css'])
                        && $appHook['css'] != ''
                    )
                        $dispHookList[$i]['cssid'] = 'id="' . $appHook['css'] . '"';
                    else
                        $dispHookList[$i]['cssid'] = 'id="' . $selfHookName . '"';
                    $dispHookList[$i]['hookType'] = 'Self';
                    $i++;
                }
            }
        }

        // Ajoute les actions spécifiques à l'objet pour le module en cours.
        foreach ($modules as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                // Liste les points d'encrages à afficher.
                if (substr($module->getInterface(), 0, 1) == '1'
                    || substr($module->getInterface(), 0, 1) == '2'
                )
                    $appHookList = $module->getHookList($selfHookName);
                else
                    $appHookList = $module->getHookList($selfHookName, $object);

                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $dispHookList[$i]['moduleName'] = $module->getTraduction($module->getName());
                            $dispHookList[$i]['name'] = $module->getTraduction($appHook['name']);
                            $dispHookList[$i]['icon'] = $appHook['icon'];
                            if ($dispHookList[$i]['icon'] == '')
                                $dispHookList[$i]['icon'] = self::DEFAULT_ICON_LSTOBJ;
                            $dispHookList[$i]['desc'] = $module->getTraduction($appHook['desc']);
                            $dispHookList[$i]['link'] = $appHook['link'];
                            if (isset($appHook['css'])
                                && $appHook['css'] != ''
                            )
                                $dispHookList[$i]['cssid'] = 'id="' . $appHook['css'] . '"';
                            else
                                $dispHookList[$i]['cssid'] = 'id="' . $selfHookName . $i . '"';
                            $dispHookList[$i]['hookType'] = 'Self';
                            $i++;
                        }
                    }
                }
            }
        }

        // Ajoute les actions spécifiques à l'objet pour tout sauf le module en cours.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Liste les points d'encrages à afficher.
                if (substr($module->getInterface(), 0, 1) == '1'
                    || substr($module->getInterface(), 0, 1) == '2'
                )
                    $appHookList = $module->getHookList($selfHookName);
                else
                    $appHookList = $module->getHookList($selfHookName, $object);

                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $dispHookList[$i]['moduleName'] = $module->getTraduction($module->getName());
                            $dispHookList[$i]['name'] = $this->getTraduction($appHook['name']);
                            $dispHookList[$i]['icon'] = $appHook['icon'];
                            if ($dispHookList[$i]['icon'] == '')
                                $dispHookList[$i]['icon'] = self::DEFAULT_ICON_LSTOBJ;
                            $dispHookList[$i]['desc'] = $this->getTraduction($appHook['desc']);
                            $dispHookList[$i]['link'] = $appHook['link'];
                            if (isset($appHook['css'])
                                && $appHook['css'] != ''
                            )
                                $dispHookList[$i]['cssid'] = 'id="' . $appHook['css'] . '"';
                            else
                                $dispHookList[$i]['cssid'] = 'id="' . $selfHookName . $i . '"';
                            $dispHookList[$i]['hookType'] = 'Self';
                            $i++;
                        }
                    }
                }
            }
        }

        // Ajoute les actions spécifiques au type d'objet.
        $i = 0;
        foreach ($modules as $module) {
            // Liste les points d'encrages à afficher.
            if ($module->getInterface() == '3.0')
                $appHookList = $module->getHookList($typeHookName, $object);
            else
                $appHookList = $module->getHookList($typeHookName);
            if (sizeof($appHookList) != 0) {
                foreach ($appHookList as $appHook) {
                    if ($appHook['name'] != '') {
                        $dispHookListT[$i]['moduleName'] = $module->getTraduction($module->getName());
                        $dispHookListT[$i]['name'] = $module->getTraduction($appHook['name']);
                        $dispHookListT[$i]['icon'] = $appHook['icon'];
                        if ($dispHookListT[$i]['icon'] == '')
                            $dispHookListT[$i]['icon'] = self::DEFAULT_ICON_LSTOBJ;
                        $dispHookListT[$i]['desc'] = $module->getTraduction($appHook['desc']);
                        $dispHookListT[$i]['link'] = $appHook['link'];
                        if (isset($appHook['css'])
                            && $appHook['css'] != ''
                        )
                            $dispHookListT[$i]['cssid'] = 'id="' . $appHook['css'] . '"';
                        else
                            $dispHookListT[$i]['cssid'] = 'id="' . $typeHookName . $i . '"';
                        $dispHookListT[$i]['hookType'] = 'Type';
                        $i++;
                    }
                }
            }
        }
        unset($modules, $module, $appHookList, $appHook);

        // Affiche les points d'encrages.
        if (sizeof($dispHookList) != 0) {
            foreach ($dispHookList as $dispHook) {
                /*	$result .= ' <a href="'.$dispHook['link'].'">'."\n";
				$result .= '  <div class="objectMenuContentAction'.$iconNoJS.' objectMenuContentAction'.$size.' objectMenuContentAction'.$dispHook['hookType'].'" '.$dispHook['cssid'].'>'."\n";
				$result .= '   <div class="objectMenuContentAction-icon'.$iconNoJS.'">';
				$result .= $this->convertUpdateImage($dispHook['icon'], $dispHook['name']);
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-modname">';
				$result .= '<p>'.$dispHook['moduleName'].'</p>';
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-title">';
				$result .= '<p>'.$dispHook['name'].'</p>';
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-text">';
				if ( $enableDisplayJS )
				{
					$result .= '<p>'.$dispHook['desc'].'&nbsp;</p>';
				}
				$result .= '</div>'."\n";
				$result .= '  </div>'."\n";
				$result .= ' </a>'."\n"; */

                $result .= $this->_getDisplayHookAction($dispHook, $enableDisplayJS, $size);
            }
        }
        unset($dispHookList, $dispHook);

        // Affiche les points d'encrages.
        if (sizeof($dispHookListT) != 0) {
            foreach ($dispHookListT as $dispHook) {
                /*	$result .= ' <a href="'.$dispHook['link'].'">'."\n";
				$result .= '  <div class="objectMenuContentAction'.$iconNoJS.' objectMenuContentAction'.$size.' objectMenuContentAction'.$dispHook['hookType'].'" '.$dispHook['cssid'].'>'."\n";
				$result .= '   <div class="objectMenuContentAction-icon'.$iconNoJS.'">';
				$result .= $this->convertUpdateImage($dispHook['icon'], $dispHook['name']);
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-modname">';
				$result .= '<p>'.$dispHook['moduleName'].'</p>';
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-title">';
				$result .= '<p>'.$dispHook['name'].'</p>';
				$result .= '</div>'."\n";
				$result .= '   <div class="objectMenuContentAction-text">';
				if ( $enableDisplayJS )
				{
					$result .= '<p>'.$dispHook['desc'].'&nbsp;</p>';
				}
				$result .= '</div>'."\n";
				$result .= '  </div>'."\n";
				$result .= ' </a>'."\n"; */

                $result .= $this->_getDisplayHookAction($dispHook, $enableDisplayJS, $size);
            }
        }
        unset($dispHookListT, $dispHook);

        return $result;
    }

    /**
     * Affiche un bouton des actions définies par un point d'ancrage pour un objet.
     *
     * @param array  $dispHook
     * @param bool   $enableDisplayJS
     * @param string $size
     * @return string
     */
    public function getDisplayHookAction(array $dispHook, bool $enableDisplayJS, string $size): string
    {
        return $this->_getDisplayHookAction($dispHook, $enableDisplayJS, $size);
    }

    /**
     * Affiche un bouton des actions définies par un point d'ancrage pour un objet.
     * Le point d'ancrage permet aux modules d'ajouter des actions.
     * Fonction interne.
     * Pour la fonction _getDisplayObjectHookList
     *
     * @param array  $dispHook
     * @param bool   $enableDisplayJS
     * @param string $size
     * @return string
     */
    private function _getDisplayHookAction(array $dispHook, bool $enableDisplayJS, string $size): string
    {
        $result = '';

        $iconNoJS = 'NoJS';
        if ($enableDisplayJS)
            $iconNoJS = '';

        // Verifications
        if (!isset($dispHook['link']))
            $dispHook['link'] = '';
        if (!isset($dispHook['hookType']))
            $dispHook['hookType'] = '';
        if (!isset($dispHook['icon']))
            $dispHook['icon'] = '';
        if (!isset($dispHook['name']))
            $dispHook['name'] = '';
        if (!isset($dispHook['moduleName']))
            $dispHook['moduleName'] = '';
        if (!isset($dispHook['desc']))
            $dispHook['desc'] = '';

        if ($dispHook['link'] != '')
            $result .= ' <a href="' . $dispHook['link'] . '">' . "\n";
        $result .= '  <div class="objectMenuContentAction' . $iconNoJS . ' objectMenuContentAction' . $size . ' objectMenuContentAction' . $dispHook['hookType'] . '" ' . $dispHook['link'] . '>' . "\n";
        $result .= '   <div class="objectMenuContentAction-icon' . $iconNoJS . '">';
        $result .= $this->convertUpdateImage($dispHook['icon'], $dispHook['name']);
        $result .= '</div>' . "\n";
        $result .= '   <div class="objectMenuContentAction-modname">';
        $result .= '<p>' . $dispHook['moduleName'] . '</p>';
        $result .= '</div>' . "\n";
        $result .= '   <div class="objectMenuContentAction-title">';
        $result .= '<p>' . $dispHook['name'] . '</p>';
        $result .= '</div>' . "\n";
        $result .= '   <div class="objectMenuContentAction-text">';
        if ($enableDisplayJS
            && $dispHook['desc'] != ''
        )
            $result .= '<p>' . $dispHook['desc'] . '&nbsp;</p>';
        $result .= '</div>' . "\n";
        $result .= '  </div>' . "\n";
        if ($dispHook['link'] != '')
            $result .= ' </a>' . "\n";

        return $result;
    }


    /**
     * Le CSS des fonctions getDisplayObjectContent() et getDisplayAsObjectContent().
     *
     * @return void
     */
    private function _getDisplayObjectContentCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS des fonctions getDisplayObjectContent() et getDisplayAsObjectContent(). */
            .objectContent {
            }

            /* .layoutInformation { max-width:2000px; } */
            .objectContent .layoutInformation {
                margin-left: -3px;
            }

            .objectContentObject {
            }

            .objectContentEntity {
            }

            .objectContentGroup {
            }

            .objectContentConversation {
            }

            .objectContentText {
                font-size: 0.8rem;
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
                color: #000000;
                font-family: sans-serif;
            }

            .objectContentCode {
                font-size: 0.8rem;
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
                color: #000000;
                font-family: monospace;
            }

            .objectContentAudio {
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }
        </style>
        <?php
    }

    /**
     * Prépare à afficher le contenu d'un objet suivant sont type.
     *
     * @param string|Node $object
     * @param string      $sizeCSS  [tiny|small|medium|large|full]
     * @param string      $ratioCSS [short|long]
     * @param bool        $permitWarnProtected
     * @return string
     */
    public function getDisplayObjectContent($object, string $sizeCSS = 'medium', string $ratioCSS = '', bool $permitWarnProtected = true): string
    {
        $result = '';

        if ($sizeCSS != 'full'
            && $sizeCSS != 'large'
            && $sizeCSS != 'medium'
            && $sizeCSS != 'small'
            && $sizeCSS != 'tiny'
        )
            $sizeCSS = 'medium';

        if ($ratioCSS != 'short'
            && $ratioCSS != 'long'
        )
            $ratioCSS = 'short';

        // Vérifie que c'est un objet.
        if (!is_a($object, 'Nebule\Library\Node')
            && !is_a($object, 'Nebule\Library\Group')
            && !is_a($object, 'Nebule\Library\Entity')
            && !is_a($object, 'Nebule\Library\Conversation')
            && !is_a($object, 'Nebule\Library\Currency')
            && !is_a($object, 'Nebule\Library\TokenPool')
            && !is_a($object, 'Nebule\Library\Token')
            && !is_a($object, 'Nebule\Library\Transaction')
            && !is_a($object, 'Nebule\Library\Wallet')
        ) {
            $object = $this->_nebuleInstance->newObject($object);
            $id = $object->getID();
            if ($object->getType('all') == nebule::REFERENCE_OBJECT_ENTITY
                && strpos($object->readOneLineAsText(Entity::ENTITY_MAX_SIZE), nebule::REFERENCE_ENTITY_HEADER) !== false
            )
                $object = $this->_nebuleInstance->newEntity($id);
            elseif ($object->getIsGroup('all'))
                $object = $this->_nebuleInstance->newGroup($id);
            elseif ($object->getIsConversation('all'))
                $object = $this->_nebuleInstance->newConversation($id);
        }

        $type = $object->getType('all');

        $result .= '<div class="objectContent">' . "\n";

        // Affiche l'objet suivant son type.
        if (is_a($object, 'Nebule\Library\Entity')) {
            if ($object->checkPresent()) {
                if ($sizeCSS = 'medium' || $sizeCSS = 'full') {
                    $result .= '<div class="objectContentEntity">' . "\n<p>";
                    $result .= sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                        $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n");
                    $result .= "</p>\n";

                    if ($sizeCSS = 'full') {
                        // Liste des localisations.
                        $localisations = $object->getLocalisationsID();
                        if (sizeof($localisations) > 0) {
                            $result .= '<table border="0"><tr><td><td>' . $this->_traductionInstance->getTraduction('::EntityLocalisation') . " :</td><td>\n";
                            foreach ($localisations as $localisation) {
                                $locObject = $this->_nebuleInstance->newObject($localisation);
                                $result .= "\t " . $this->convertInlineObjectColorIcon($localisation) . ' '
                                    . $this->convertHypertextLink(
                                        $locObject->readOneLineAsText(),
                                        $locObject->readOneLineAsText()
                                    ) . "<br />\n";
                            }
                            $result .= "</td></tr></table>\n";
                            unset($localisations, $localisation, $locObject);
                        }
                    }
                    $result .= "</div>\n";
                }
            } else
                $result .= $this->convertLineMessage(':::display:content:errorNotAvailable', 'error');
        } elseif (is_a($object, 'Nebule\Library\Group')) {
            $result .= '<div class="objectContentGroup">' . "\n\t<p>"
                . sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                    $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n");
            if ($object->getMarkClosed())
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::GroupeFerme') . ".\n";
            else
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::GroupeOuvert') . ".\n";
            $result .= "\t</p>\n</div>\n";
        } elseif (is_a($object, 'Nebule\Library\Conversation')) {
            $result .= '<div class="objectContentConversation">' . "\n\t<p>"
                . sprintf($this->_traductionInstance->getTraduction('::UniqueID'),
                    $this->convertInlineObjectColorIcon($object) . ' ' . '<b>' . $object->getID() . "</b>\n");
            if ($object->getMarkClosed())
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::ConversationFermee') . ".\n";
            else
                $result .= "<br />\n" . $this->_traductionInstance->getTraduction('::ConversationOuverte') . ".\n";
            $result .= "\t</p>\n</div>\n";
        } else
            $result .= $this->getDisplayAsObjectContent($object, $sizeCSS, $ratioCSS, $permitWarnProtected);

        $result .= "</div>\n";

        return $result;
    }

    /**
     * Prépare à afficher le contenu d'un objet comme objet pur.
     * Affiche un objet sans tenir compte de son type nebule (Entity|Group|Conversation).
     * Mais affiche l'objet en fonction de son type mime déclaré.
     *
     * @param string|Node $object
     * @param string      $sizeCSS  [tiny|small|medium|large|full]
     * @param string      $ratioCSS [short|long]
     * @param bool        $permitWarnProtected
     * @return string
     */
    public function getDisplayAsObjectContent($object, string $sizeCSS = 'medium', string $ratioCSS = '', bool $permitWarnProtected = true): string
    {
        $result = '';

        if ($sizeCSS != 'full'
            && $sizeCSS != 'large'
            && $sizeCSS != 'medium'
            && $sizeCSS != 'small'
            && $sizeCSS != 'tiny'
        )
            $sizeCSS = 'medium';

        if ($ratioCSS != 'short'
            && $ratioCSS != 'long'
        )
            $ratioCSS = 'short';

        // Vérifie que c'est un objet.
        if (!is_a($object, 'Nebule\Library\Node')
            && !is_a($object, 'Nebule\Library\Group')
            && !is_a($object, 'Nebule\Library\Entity')
            && !is_a($object, 'Nebule\Library\Conversation')
        )
            $object = $this->_nebuleInstance->newObject($object);
        $id = $object->getID();

        // Vérifie si il est protégé
        $protected = $object->getMarkProtected();

        // Extrait les propriétés de l'objet.
        $name = $object->getFullName('all');
        $typemime = $object->getType('all');
        $danger = $object->getMarkDanger();
        $warning = $object->getMarkWarning();
        $ispresent = $object->checkPresent();
        $type = $this->_traductionInstance->getTraduction($typemime);

        $param = array(
            'enableDisplayIcon' => true,
            'displaySize' => $sizeCSS,
            'displayRatio' => $ratioCSS,
            'informationType' => 'error',
        );

        // Affichage du contenu.
        if ($danger) {
            $param['informationType'] = 'error';
            $result .= $this->getDisplayInformation(':::display:content:errorBan', $param);
            $result .= $this->getDisplayInformation(':::display:content:errorNotAvailable', $param);
        } elseif ($protected
            && !$this->_unlocked
        ) {
            $param['informationType'] = 'warn';
            $result .= $this->getDisplayInformation(':::display:content:ObjectProctected', $param);
            $result .= "<br />\n";
            $param['informationType'] = 'error';
            $result .= $this->getDisplayInformation(':::display:content:errorNotAvailable', $param);
        } elseif ($ispresent) {
            if ($warning) {
                $param['informationType'] = 'warn';
                $result .= $this->getDisplayInformation(':::display:content:warningTaggedWarning', $param);
            }
            if ($protected
                && $this->_unlocked
                && $permitWarnProtected
            ) {
                $param['informationType'] = 'warn';
                $result .= $this->getDisplayInformation(':::display:content:warningObjectProctected', $param);
            }

            switch ($typemime) {
                case nebule::REFERENCE_OBJECT_PNG :
                case nebule::REFERENCE_OBJECT_JPEG :
                    $content = $object->getContent(0);
                    if ($content != null) {
                        $result .= '<div class="objectContentObject objectContentImage"><img src="?o=' . $id
                            . '" alt="Image ' . $id . '"></div>' . "\n";
                    } else {
                        $param['informationType'] = 'error';
                        $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    }
                    break;
                case nebule::REFERENCE_OBJECT_TEXT :
                    $content = htmlspecialchars($object->getContent(0));
                    if ($content != null) {
                        $result .= '<div class="objectContentObject objectContentText"><p>' . $content . '</p></div>' . "\n";
                    } else {
                        $param['informationType'] = 'error';
                        $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    }
                    unset($content);
                    break;
                case nebule::REFERENCE_OBJECT_ENTITY :
                case nebule::REFERENCE_OBJECT_HTML :
                case nebule::REFERENCE_OBJECT_CSS :
                case nebule::REFERENCE_OBJECT_APP_PHP :
                case nebule::REFERENCE_OBJECT_PHP :
                case nebule::REFERENCE_NEBULE_OBJET_MONNAIE :
                case nebule::REFERENCE_NEBULE_OBJET_MONNAIE_SAC :
                case nebule::REFERENCE_NEBULE_OBJET_MONNAIE_JETON :
                    $content = htmlspecialchars($object->getContent(0));
                    if ($content != null)
                        $result .= '<div class="objectContentObject objectContentCode"><pre>' . $content . '</pre></div>' . "\n";
                    else {
                        $param['informationType'] = 'error';
                        $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    }
                    unset($content);
                    break;
                case nebule::REFERENCE_OBJECT_MP3 :
                    $content = $object->getContent(0);
                    if ($content != null)
                        $result .= '<div class="objectContentObject objectContentAudio"><audio controls><source src="?o=' . $id . '" type="audio/mp3" />' . $this->_traductionInstance->getTraduction(':::warn_NoAudioTagSupport') . '</audio></div>' . "\n";
                    else {
                        $param['informationType'] = 'error';
                        $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    }
                    break;
                case nebule::REFERENCE_OBJECT_OGG :
                    $content = $object->getContent(0);
                    if ($content != null)
                        $result .= '<div class="objectContentObject objectContentAudio"><audio controls><source src="?o=' . $id . '" type="audio/ogg" />' . $this->_traductionInstance->getTraduction(':::warn_NoAudioTagSupport') . '</audio></div>' . "\n";
                    else {
                        $param['informationType'] = 'error';
                        $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    }
                    break;
                case nebule::REFERENCE_OBJECT_CRYPT_RSA :
                    $param['informationType'] = 'warn';
                    $result .= $this->getDisplayInformation(':::display:content:ObjectProctected', $param);
                    break;
                default :
                    $param['informationType'] = 'error';
                    $result .= $this->getDisplayInformation(':::display:content:errorNotDisplayable', $param);
                    break;
            }
        } else {
            $param['informationType'] = 'error';
            $result .= $this->getDisplayInformation(':::display:content:errorNotAvailable', $param);
        }

        // Recherche si l'objet a une mise à jour.
        $instance = $this->_applicationInstance->getCurrentObjectInstance();
        $UpdateID = $instance->getUpdateNID(false, false);
        if ($instance->getID() != $UpdateID) {
            $param['informationType'] = 'information';
            $param2 = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayName' => true,
                'enableDisplayLink2Object' => true,
                'enableDisplayObjectActions' => false,
                'displaySize' => 'tiny',
                'enableDisplayContent' => false, // Doit impérativement être à false pour éviter une boucle de la mort.
            );
            $result .= "</div>\n";
            $result .= '<div class="objectContent">' . "\n";
            $result .= $this->getDisplayInformation(
                $this->_applicationInstance->getTraductionInstance()->getTraduction(':::display:content:ObjectHaveUpdate')
                . '<br />' . $this->getDisplayObject($UpdateID, $param2),
                $param
            );
        }
        unset($instance, $UpdateID, $param, $param2);

        return $result;
    }


    /**
     * Le CSS de la fonction getDisplayObjectsList().
     *
     * @return void
     */
    private function _getDisplayObjectsListCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObjectsList(). */
            .layoutObjectsList {
                width: 100%;
            }

            .objectsListContent {
                margin: auto;
                text-align: center;
                font-size: 0;
                min-height: 34px;
                padding: 0 5px 5px 0;
                background: none;
            }

            /* max-width:2005px; */
            .objectsListContent p, .objectsListContent form {
                font-size: 1rem;
                color: #000000;
                text-align: left;
            }

            .objectsListContent p a, .objectsListContent form a {
                color: #000000;
            }
        </style>
        <?php
    }

    /**
     * Affiche une liste d'objets avec leurs paramètres propres ou des messages imbriqués.
     * Se base sur la fonction getDisplayObject() pour l'affichage des objets.
     * Se base sur la fonction getDisplayInformation() pour l'affichage des messages.
     * Si 'information' est défini et que les paramètres contiennent 'informationType',
     *   alors on affiche un message et non un objet.
     * Si 'object' n'est défini pour aucun des éléments du tableau,
     *   alors affiche un message générique pour dire que la liste est vide.
     *   Sauf si on demande explicitement de ne pas afficher le message.
     *
     * @param array   $list
     * @param string  $size
     * @param boolean $displayNoObject
     * @return string
     */
    public function getDisplayObjectsList(array $list, string $size = 'medium', bool $displayNoObject = false): string
    {
        $result = '<div class="layoutObjectsList">' . "\n";
        $result .= '<div class="objectsListContent">' . "\n";

        // Prépare le message si pas d'objet à afficher.
        $noObject = true;
        foreach ($list as $item) {
            if (isset($item['object'])
                || isset($item['information'])
                || isset($item['link'])
            )
                $noObject = false;
        }
        if ($noObject
            && !$displayNoObject
        ) {
            $param = array(
                'enableDisplayIcon' => true,
                'informationType' => 'information',
            );
            $result .= $this->getDisplayInformation('::EmptyList', $param);
        }

        foreach ($list as $item) {
            $param = $item['param'];
            $param['displaySize'] = $size;

            // Détermine si c'est un objet ou un message à afficher.
            if (isset($item['object'])
                && (is_a($item['object'], 'Nebule\Library\Node')
                    || $item['object'] != ''
                )
            )
                $result .= $this->getDisplayObject($item['object'], $param);
            elseif (isset($param['informationType'])
                && isset($item['information'])
                && $param['informationType'] != ''
                && $item['information'] != ''
            )
                $result .= $this->getDisplayInformation($item['information'], $param);
            elseif (isset($item['link'])
                && is_a($item['link'], 'Nebule\Library\Link')
            )
                $result .= $this->getDisplayMessage($item['link'], $param);
            // Sinon n'affiche rien.
        }
        unset($param, $item);

        $result .= '</div>' . "\n";
        $result .= '</div>' . "\n";

        return $result;
    }


    /**
     * Le CSS de la fonction getDisplayMenuList().
     *
     * @return void
     */
    private function _getDisplayMenuListCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutMenuList {
                font-size: 0;
                padding-top: 4px;
                width: 100%;
            }

            .menuListContent {
                margin: auto;
                text-align: center;
            }

            .menuListContentActionDiv {
                display: inline-block;
            }

            .menuListContentActionDiv a:link, .menuListContentActionDiv a:visited {
                font-weight: normal;
                text-decoration: none;
            }

            .menuListContentActionDiv a:hover, .menuListContentActionDiv a:active {
                font-weight: bold;
                text-decoration: none;
            }

            .menuListContentAction {
                background: #ababab;
                height: 64px;
                margin-bottom: 5px;
                margin-right: 5px;
                text-align: left;
                color: #545454;
            }

            .menuListContentActionSmall {
                width: 128px;
            }

            .menuListContentActionMedium {
                width: 295px;
            }

            .menuListContentActionLarge {
                width: 395px;
            }

            .menuListContentAction-content {
                padding: 5px;
            }

            .menuListContentAction-icon {
                float: left;
                height: 64px;
                width: 64px;
                margin-right: 5px;
            }

            .menuListContentAction-ref {
                font-size: 0.6rem;
                font-style: italic;
                overflow: hidden;
                white-space: nowrap;
            }

            .menuListContentAction-title {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: normal;
            }

            .menuListContentAction-desc {
                font-size: 0.8rem;
                overflow: hidden;
                white-space: nowrap;
            }
        </style>
        <?php
    }

    /**
     * Affiche un menu constitué d'une liste d'entrées avec icône, titre et lien html.
     * Peut éventuellement afficher une description par entrée.
     * La liste $list est un tableau de listes, chacunes contenant :
     * - icon : la référence de l'îcone, obligatoire ;
     * - title : le titre de l'entrée, obligatoire ;
     * - htlink : le lien HTML, obligatoire ;
     * - desc : la description sous le titre, facultatif ;
     * - ref : un texte de référence au-dessus du titre, facultatif ;
     * - class : un texte qui référence une classe CSS, facultatif.
     * La taille peut être :
     * - Small
     * - Medium
     * - Large
     * Par défaut la taille est Medium.
     *
     * @param array  $list
     * @param string $size
     * @return string
     */
    public function getDisplayMenuList(array $list, string $size = 'Medium'): string
    {
        $result = '';

        if (sizeof($list) == 0)
            return '';

        foreach ($list as $i => $item) {
            if (!isset($item['icon']) || $item['icon'] == ''
                || !isset($item['title']) || $item['title'] == ''
                || !isset($item['htlink']) || $item['htlink'] == ''
            )
                unset($list[$i]);
            if (!isset($item['class']))
                $list[$i]['class'] = '';
            if (!isset($item['ref'])
                || $item['ref'] == ''
            )
                $list[$i]['ref'] = '&nbsp;';
            if (!isset($item['desc'])
                || $item['desc'] == ''
            )
                $list[$i]['desc'] = '&nbsp;';
        }

        if (sizeof($list) == 0)
            return '';

        // Vérification de $size.
        if ($size != 'Small'
            && $size != 'Medium'
            && $size != 'Large'
        )
            $size = 'Medium';

        $result .= '<div class="layoutMenuList">' . "\n";
        $result .= '<div class="menuListContent">' . "\n";

        foreach ($list as $item) {
            $result .= '<div class="menuListContentActionDiv">' . "\n";
            $result .= '<a href="' . $item['htlink'] . '">' . "\n";

            $result .= ' <div class="menuListContentAction menuListContentAction' . $size . "\n";
            if (isset($item['class']))
                $result .= ' ' . $item['class'];
            $result .= '">' . "\n";
            $result .= '  <div class="menuListContentAction-icon">';
            if (is_a($item['icon'], 'Nebule\Library\Node'))
                $icon = $item['icon'];
            else
                $icon = $this->_nebuleInstance->newObject((string)$item['icon']);
            $result .= $this->convertUpdateImage($icon, $item['title']);
            $result .= '</div>' . "\n";

            $result .= '  <div class="menuListContentAction-content">' . "\n";
            $result .= '   <p class="menuListContentAction-ref">' . $item['ref'] . '</p>' . "\n";
            $result .= '   <p class="menuListContentAction-title">' . $item['title'] . '</p>' . "\n";
            $result .= '   <p class="menuListContentAction-desc">' . $item['desc'] . '</p>' . "\n";

            $result .= "  </div>\n";
            $result .= " </div></a>\n";
            $result .= "</div>\n";
        }

        $result .= '</div>' . "\n";
        $result .= '</div>' . "\n";

        return $result;
    }

    /**
     * Affiche la liste de menu en fonction d'un point d'ancrage.
     * Utilise la fonction getDisplayMenuList().
     * Le point d'enrage $hook est un texte qui sert de référence.
     * les modules sont interrogés pour avoir les entrées correspondantes à cette référence.
     * La taille peut être :
     * - Small
     * - Medium
     * - Large
     * Par défaut la taille est Medium.
     * Lors de la consultation des points d'encrage, il est possible de passer un objet à utiliser.
     *
     * @param string $hook
     * @param string $size
     * @param string $object
     * @return string
     */
    public function getDisplayHookMenuList(string $hook, string $size = 'Medium', string $object = 'none'): string
    {
        $list = array();
        $i = 0;
        $module = null;

        $modules = $this->_applicationInstance->getModulesListInstances();
        foreach ($modules as $module) {
            // Liste les points d'encrages à afficher.
            $appHookList = $module->getHookList($hook, $object);
            $appHook = null;
            foreach ($appHookList as $appHook) {
                if ($appHook['name'] != '') {
                    $list[$i]['ref'] = $module->getTraduction($module->getName());
                    $list[$i]['title'] = $module->getTraduction($appHook['name']);
                    $list[$i]['icon'] = $appHook['icon'];
                    if ($list[$i]['icon'] == '')
                        $list[$i]['icon'] = self::DEFAULT_ICON_LSTOBJ;
                    $list[$i]['desc'] = $module->getTraduction($appHook['desc']);
                    $list[$i]['htlink'] = $appHook['link'];
                    if (isset($appHook['css'])
                        && $appHook['css'] != ''
                    )
                        $list[$i]['class'] = $appHook['css'];
                    else
                        $list[$i]['class'] = $hook;
                    $i++;
                }
            }
            unset($appHookList, $appHook);
        }
        unset($modules, $module);

        // Affiche la liste.
        return $this->getDisplayMenuList($list, $size);
    }

    /**
     * @param string $hook
     * @return void
     * TODO Fonction périmée remplacée par getDisplayHookMenuList().
     *
     */
    public function displayHookList(string $hook): void
    {
        echo $this->getDisplayHookMenuList($hook, 'Medium');
    }


    /**
     * Le CSS de la fonction getDisplayTitle().
     *
     * @return void
     */
    private function _getDisplayTitleCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayTitle(). */
            .layoutTitle {
                margin-bottom: 10px;
                margin-top: 32px;
                width: 100%;
                height: 32px;
                text-align: center;
            }

            .titleContent {
                margin: auto;
                text-align: center;
            }

            .titleContentDiv {
                display: inline-block;
                background: #333333;
                height: 32px;
                width: 384px;
            }

            .titleContentEntity {
                display: inline-block;
            }

            .titleContentEntity .layoutObject {
                margin: -11px 0 0 0;
            }

            .titleContentIcon {
                float: left;
            }

            .titleContentIcon img {
                height: 32px;
                width: 32px;
                margin-right: 5px;
            }

            .titleContent h1 {
                font-size: 1.2rem;
                font-weight: bold;
                color: #ababab;
                overflow: hidden;
                white-space: nowrap;
                margin-top: 5px;
            }
        </style>
        <?php
    }

    /**
     * Affiche un titre de paragraphe.
     * La variable $displayEntity et l'option 'forceDisplayEntityOnTitle' forcent l'affichage de l'entité en cours.
     *
     * @param string    $title
     * @param Node|null $icon
     * @param boolean   $displayEntity
     * @return string
     */
    public function getDisplayTitle(string $title, ?Node $icon = null, bool $displayEntity = false): string
    {
        $result = '';

        if (trim($title) == '')
            return '';

        $result .= '<div class="layoutTitle">' . "\n";
        $result .= ' <div class="titleContent">' . "\n";

        $result .= '  <div class="titleContentDiv">' . "\n";
        if ($icon !== null) {
            $result .= '   <div class="titleContentIcon">' . "\n";
            $result .= $this->convertUpdateImage($icon, $title);
            $result .= "   </div>\n";
        }
        $result .= '   <h1>' . $this->_traductionInstance->getTraduction($title) . "</h1>\n";
        $result .= "  </div>\n";

        if ($this->_applicationInstance->getCurrentEntity() != $this->_nebuleInstance->getCurrentEntity()
            || $this->_configurationInstance->getOptionUntyped('forceDisplayEntityOnTitle')
            || $displayEntity
        ) {
            $result .= '  <div class="titleContentEntity">' . "\n";
            $param = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'displaySize' => 'small',
                'displayRatio' => 'short',
                'enableDisplayJS' => false,
                'enableDisplayObjectActions' => false,
            );
            $result .= $this->getDisplayObject($this->_applicationInstance->getCurrentEntity(), $param);
            $result .= "  </div>\n";
        }

        $result .= " </div>\n";
        $result .= "</div>\n";

        return $result;
    }


    /**
     * Le CSS de la fonction getDisplayInformation().
     *
     * Le style CSS est hérité de celui utilisé pour la fonction getDisplayObject() et adapté.
     *
     * @return void
     */
    private function _getDisplayInformationCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayInformation(). */
            .informationTitleIcons img {
                background: none;
            }

            .informationDisplay {
                height: auto;
            }

            .informationDisplayMessage {
                background: #333333;
            }

            .informationDisplayOk {
                background: #103020;
            }

            .informationDisplayWarn {
                background: #ffe080;
            }

            .informationDisplayError {
                background: #ffa0a0;
            }

            .informationDisplayInformation {
                background: #ababab;
            }

            .informationTitleText {
                background: none;
                height: auto;
            }

            .informationDisplayTiny {
            }

            .informationDisplaySmall {
                min-height: 32px;
                font-size: 32px;
                border: 0;
            }

            .informationDisplayMedium {
                min-height: 64px;
                font-size: 64px;
                border: 0;
            }

            .informationDisplayLarge {
                min-height: 128px;
                font-size: 128px;
                border: 0;
            }

            .informationDisplayFull {
                min-height: 256px;
                font-size: 256px;
                border: 0;
            }

            .informationTitleTinyText {
                min-height: 16px;
                background: none;
            }

            .informationTitleSmallText {
                min-height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .informationTitleMediumText {
                min-height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .informationTitleLargeText {
                min-height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .informationTitleFullText {
                min-height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .informationTitleName {
                font-weight: normal;
                overflow: hidden;
                height: auto;
            }

            .informationTitleNameMessage, .informationTitleRefsMessage {
                color: #ffffff;
            }

            .informationTitleNameOk, .informationTitleRefsOk {
                color: #ffffff;
            }

            .informationTitleNameWarn, .informationTitleRefsWarn {
                color: #ff8000;
            }

            .informationTitleNameWarn {
                font-weight: bold;
            }

            .informationTitleNameError, .informationTitleRefsError {
                color: #ff0000;
            }

            .informationTitleNameError {
                font-weight: bold;
            }

            .informationTitleNameInformation, .informationTitleRefsInformation {
                color: #000000;
            }

            .informationTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .informationTitleSmallName {
                line-height: 14px;
                overflow: hidden;
                white-space: normal;
                font-size: 1rem;
            }

            .informationTitleMediumName {
                line-height: 22px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.2rem;
            }

            .informationTitleLargeName {
                line-height: 30px;
                overflow: hidden;
                white-space: normal;
                font-size: 1.5rem;
            }

            .informationTitleFullName {
                line-height: 62px;
                overflow: hidden;
                white-space: normal;
                font-size: 2rem;
            }
        </style>
        <?php
    }

    /**
     * Affiche un message d'information avec un style en fonction du type de message.
     * Le message peut contenir 5 arguments remplacés dans le texte après la traduction.
     * Dans le texte, chaque chaine %s est remplacée successivement avec les arguments à concurence de 5.
     * Le style CSS est hérité de celui utilisé pour la fonction getDisplayObject() et adapté.
     * Les paramètres d'activation de contenus :
     * - enableDisplayIcon : Affiche le carré avec l'image attaché à l'objet ou l'icône de son type.
     *     Par défaut true : affiche le carré de l'image/icône.
     *     Boolean
     * - enableDisplayAlone : Affiche le message dans une position identique à un titre. C'est utilisé pour un message isolé.
     *     Par défaut false : n'affiche pas en isolé.
     *     Boolean
     * Les paramètres de définition de contenus :
     * - informationType : Détermine le type de message.
     *     Les types disponibles :
     *     - message : affichage d'un message simple en blanc sur fond noir transparent.
     *     - ok : affichage d'un message de validation en blanc sur fond vert.
     *     - warn : affichage d'un message d'avertissement en jaune gras sur fond orange clair.
     *     - error : affichage d'un message d'erreur en rouge gras sur fond rose clair.
     *     - info : affichage d'un message simple en noir sur fond blanc transparent (style d'affichage des objets).
     *     Par défaut info.
     *     String
     * - informationTypeName : Détermine le nom du type de message à afficher.
     *     Par défaut vide.
     *     String
     * - displaySize : Détermine la taille de l'affichage de l'élément complet.
     *     Tailles disponibles :
     *     - tiny : très petite taille correspondant à un carré de base de 16 pixels de large.
     *         Certains éléments ne sont pas affichés.
     *     - small : petite taille correspondant à un carré de base de 32 pixels de large.
     *     - medium : taille moyenne correspondant à un carré de base de 64 pixels de large par défaut.
     *     - large : grande taille correspondant à un carré de base de 128 pixels de large par défaut.
     *     - full : très grande taille correspondant à un carré de base de 256 pixels de large par défaut.
     *     Par défaut medium : taille moyenne.
     *     String
     * - displayRatio : Détermine la forme de l'affichage par son ratio dans la mesure du possible si pas d'affichage du contenu de l'objet.
     *     Ratios disponibles :
     *     - square : forme carrée de 2x2 displaySize.
     *     - short : forme plate courte de 6x1 displaySize.
     *     - long : forme plate longue de toute largeure disponible.
     *     Par défaut short : forme plate courte.
     *     String
     * - icon : Détermine l'icône à utiliser.
     *     Si vide, l'icône est sélectionnée automatiquement en fonction du type de message.
     *     Par défaut vide : l'icône est sélectionnée automatiquement.
     *     enableDisplayIcon doit être à true.
     *     String
     * Exemple de table de paramètres avec les valeurs par défaut :
     * $param = array(
     * 'enableDisplayIcon' => true,
     * 'enableDisplayAlone' => false,
     * 'informationType' => 'information',
     * 'informationTypeName' => '',
     * 'displaySize' => 'medium',
     * 'displayRatio' => 'short',
     * 'icon' => '',
     * );
     *
     * @param string $message
     * @param array  $param
     * @param string $arg1
     * @param string $arg2
     * @param string $arg3
     * @param string $arg4
     * @param string $arg5
     * @return string
     */
    public function getDisplayInformation(string $message, array $param, string $arg1 = '', string $arg2 = '',
                                          string $arg3 = '', string $arg4 = '', string $arg5 = ''): string
    {
        $result = '';

        // Prépare les paramètres d'activation de contenus.
        if (!isset($param['enableDisplayIcon'])
            || $param['enableDisplayIcon'] !== false
        )
            $param['enableDisplayIcon'] = true; // Par défaut à true.

        $message = sprintf($this->_traductionInstance->getTraduction($message), $arg1, $arg2, $arg3, $arg4, $arg5);
        if (!is_string($param['informationType'])
            || $param['informationType'] != 'information'
        ) {
            //$message = filter_var($message, FILTER_SANITIZE_STRING); TODO refaire un filtre.
        } else {
            // TODO faire un filtre pour le type message...
        }
        $contentDisplayMessage = trim($message);
        if ($contentDisplayMessage == '')
            return '';

        if (!isset($param['enableDisplayAlone']))
            $param['enableDisplayAlone'] = false;
        if ($param['enableDisplayAlone'] === true) {
            $result .= '<div class="layoutAloneItem">' . "\n";
            $result .= ' <div class="aloneItemContent">' . "\n";
        }

        // Avec une première lettre majuscule pour le CSS.
        $messageType = '';
        switch (strtolower($param['informationType'])) {
            case 'info':
            case 'information':
                $messageType = 'Information';
                break;
            case 'ok':
                $messageType = 'Ok';
                break;
            case 'warn':
            case 'warning':
                $messageType = 'Warn';
                break;
            case 'nok':
            case 'err':
            case 'error':
                $messageType = 'Error';
                break;
            case 'message':
                $messageType = 'Message';
                break;
            default:
                $messageType = 'Information';
                break;
        }

        $messageTextIcon = '';
        $messageIcon = '';
        if (isset($param['icon'])
            && $param['icon'] !== ''
        )
        {
            if (is_string($param['icon']))
                $messageIcon = $this->_nebuleInstance->newObject($param['icon']);
            else
                $messageIcon = $param['icon'];
        }
        else {
            switch ($messageType) {
                case 'Information':
                case 'Message':
                    $messageTextIcon = '::::INFORMATION';
                    $messageIcon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IINFO);
                    break;
                case 'Ok':
                    $messageTextIcon = '::::OK';
                    $messageIcon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IOK);
                    break;
                case 'Warn':
                    $messageTextIcon = '::::WARN';
                    $messageIcon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IWARN);
                    break;
                case 'Error':
                    $messageTextIcon = '::::ERROR';
                    $messageIcon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IERR);
                    break;
            }
        }
        if (isset($param['informationTypeName'])
            && $param['informationTypeName'] != ''
        )
            $messageTextIcon = $param['informationTypeName'];

        $sizeCSS = 'Medium';
        if (!isset($param['displaySize'])) {
            $param['displaySize'] = 'medium';
            $sizeCSS = 'Medium';
        } else {
            switch ($param['displaySize']) {
                case 'tiny':
                    $sizeCSS = 'Tiny';
                    break;
                case 'small':
                    $sizeCSS = 'Small';
                    break;
                case 'large':
                    $sizeCSS = 'Large';
                    break;
                case 'full':
                    $sizeCSS = 'Full';
                    break;
                default:
                    $param['displaySize'] = 'medium';
                    $sizeCSS = 'Medium';
                    break;
            }
        }

        $ratioCSS = 'short';
        if (!isset($param['displayRatio'])) {
            $param['displayRatio'] = 'short';
            $ratioCSS = 'Short';
        } else {
            switch ($param['displayRatio']) {
                case 'square':
                    $ratioCSS = 'Square';
                    break;
                case 'long':
                    $ratioCSS = 'Long';
                    break;
                default:
                    $param['displayRatio'] = 'short';
                    $ratioCSS = 'Short';
                    break;
            }
        }

        // Prépare les contenus.
        $contentDisplayIcon = '';
        if ($param['enableDisplayIcon']) {
            $contentDisplayIcon = $this->convertUpdateImage($messageIcon, $messageTextIcon);
            // $contentDisplayIcon = $this->convertReferenceImage($messageIcon, $messageTextIcon); TODO par référence
        }

        // Assemble les contenus.
        $divDisplayOpen = '';
        $divDisplayClose = '';
        $divTitleOpen = '';
        $divTitleClose = '';
        $titleContent = '';
        $divTitleIconsOpen = '';
        $divTitleIconsClose = '';
        $titleIconsContent = '';
        $divTitleTextOpen = '';
        $divTitleTextClose = '';
        $divTitleRefsOpen = '';
        $divTitleRefsClose = '';
        $titleRefsContent = '';
        $divTitleNameOpen = '';
        $divTitleNameClose = '';
        $titleNameContent = '';
        if ($param['displaySize'] == 'tiny')
            $result = '<span style="font-size:1em" class="objectTitleIconsInline">' . $contentDisplayIcon . '</span>' . $contentDisplayMessage;
        else {
            $divDisplayOpen = '<div class="layoutObject layoutInformation">' . "\n";
            $divDisplayClose = '</div>' . "\n";
            $divTitleOpen = ' <div class="objectTitle objectDisplay' . $sizeCSS . $ratioCSS . ' informationDisplay informationDisplay' . $sizeCSS . ' informationDisplay' . $messageType . '">' . "\n";
            $divTitleClose = ' </div>' . "\n";
            $divTitleIconsOpen = '  <div class="objectTitleIcons informationTitleIcons informationTitleIcons' . $messageType . '">';
            $divTitleIconsClose = '</div>' . "\n";
            $padding = 0;
            if ($param['enableDisplayIcon']) {
                $titleIconsContent = $contentDisplayIcon;
                $padding += 1;
            }
            $divTitleTextOpen = '  <div class="objectTitleText' . $padding . ' informationTitleText informationTitle' . $sizeCSS . 'Text">' . "\n";
            $divTitleTextClose = '  </div>' . "\n";
            $divTitleRefsOpen = '   <div class="objectTitleRefs objectTitle' . $sizeCSS . 'Refs informationTitleRefs informationTitleRefs' . $messageType . '">';
            $divTitleRefsClose = '</div>' . "\n";
            $divTitleNameOpen = '   <div class="informationTitleName informationTitleName' . $messageType . ' informationTitle' . $sizeCSS . 'Name">';
            $divTitleNameClose = '</div>' . "\n";
            $titleRefsContent = $this->_traductionInstance->getTraduction($messageTextIcon);
            $titleNameContent = $contentDisplayMessage;

            $titleContent = $divTitleIconsOpen . $titleIconsContent . $divTitleIconsClose . "\n";
            $titleContent .= $divTitleTextOpen;
            $titleContent .= $divTitleRefsOpen . $titleRefsContent . $divTitleRefsClose;
            $titleContent .= $divTitleNameOpen . $titleNameContent . $divTitleNameClose;
            $titleContent .= $divTitleTextClose;

            // Prépare le résultat à afficher.
            $result .= $divDisplayOpen;
            $result .= $divTitleOpen . $titleContent . $divTitleClose;
            $result .= $divDisplayClose;
        }

        if ($param['enableDisplayAlone'] === true) {
            $result .= " </div>\n";
            $result .= "</div>\n";
        }

        return $result;
    }


    /**
     * Le CSS de la fonction getDisplayLink().
     *
     * @return void
     */
    private function _getDisplayLinkCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayLink(). */
            .layoutLink {
                margin-right: 5px;
            }

            .linkDisplay {
            }

            .linkDisplaySmall img {
                height: 16px;
            }

            .linkDisplayMedium img {
                height: 32px;
            }

            .linkDisplayLarge img {
                height: 64px;
            }
        </style>
        <?php
    }

    /**
     * Retourne la représentation html du lien en fonction des paramètres passés.
     *
     * Les paramètres de définition de contenus :
     * - displaySize : Détermine la taille de l'affichage de l'élément complet.
     *     Tailles disponibles :
     *     - small : très petite taille correspondant à un carré de base de 16 pixels de large.
     *         Certains éléments ne sont pas affichés.
     *     - medium : taille moyenne correspondant à un carré de base de 32 pixels de large par défaut.
     *     - large : grande taille correspondant à un carré de base de 64 pixels de large par défaut.
     *     Par défaut small : taille petite.
     *     String
     *
     * Exemple de table de paramètres avec les valeurs par défaut :
     *
     * $param = array(
     * 'displaySize' => 'small',
     * );
     *
     * @param Link|string $link
     * @param array $param
     * @return string
     */
    public function getDisplayLink($link, array $param): string
    {
        /**
         * Résultat à retourner pour affichage.
         */
        $result = '';

        /**
         * Instance du lien à afficher.
         */
        $instance = null;

        // Prépare le lien.
        if (is_a($link, 'Nebule\Library\Link'))
            $instance = $link;
        elseif (is_string($link))
            $instance = $this->_nebuleInstance->newLink($link);

        // Teste la validité du lien.
        if ($instance == null
            || !is_a($instance, 'Nebule\Library\Link')
        )
            return '';

        $sizeCSS = 'Medium';
        if (!isset($param['displaySize'])) {
            $param['displaySize'] = 'medium';
            $sizeCSS = 'Medium';
        } else {
            switch ($param['displaySize']) {
                case 'small':
                    $sizeCSS = 'Small';
                    break;
                case 'large':
                    $sizeCSS = 'Large';
                    break;
                default:
                    $param['displaySize'] = 'medium';
                    $sizeCSS = 'Medium';
                    break;
            }
        }

        $divDisplayOpen = '<span class="layoutLink">' . "\n";
        $divDisplayClose = '</span>' . "\n";
        $divLinkOpen = ' <span class="linkDisplay linkDisplay' . $sizeCSS . ' ">' . "\n";
        $divLinkClose = ' </span>' . "\n";

        $contantDisplayIcon = '';
        $contantDisplayValid = '';
        $contantDisplaySigner = '';
        $contantDisplayDate = '';
        $contantDisplayAction = '';
        $contantDisplaySource = '';
        $contantDisplayTarget = '';
        $contantDisplayMeta = '';

        if ($instance->getValid()
        ) {
            if ($instance->getSigned()) {
                $contantDisplayValid .= '<img title="OK" ';
                $contantDisplayValid .= 'alt="[O]" src="o/' . $this->_getImageByReference(self::REFERENCE_ICON_OK) . '" ';
                $contantDisplayValid .= '/>';
            } else {
                $contantDisplayValid .= '<img title="ERROR" ';
                $contantDisplayValid .= 'alt="[E]" src="o/' . $this->_getImageByReference(self::REFERENCE_ICON_ERROR) . '" ';
                $contantDisplayValid .= '/>';
            }

            foreach ($instance->getSigners() as $signer)
            {
                $object = $this->_nebuleInstance->newObject($signer);
                $contantDisplaySigner .= '<img title="' . $object->getFullName();
                $contantDisplaySigner .= '" style="background:#' . $object->getPrimaryColor();
                $contantDisplaySigner .= ';" alt="[]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" />';
            }

            $contantDisplayDate = '';

            $icon = self::REFERENCE_ICON_LINK_LL;
            switch ($instance->getAction()) {
                case 'f':
                    $icon = self::REFERENCE_ICON_LINK_LF;
                    break;
                case 'u':
                    $icon = self::REFERENCE_ICON_LINK_LU;
                    break;
                case 'd':
                    $icon = self::REFERENCE_ICON_LINK_LD;
                    break;
                case 'e':
                    $icon = self::REFERENCE_ICON_LINK_LE;
                    break;
                case 'c':
                    $icon = self::REFERENCE_ICON_LINK_LC;
                    break;
                case 'k':
                    $icon = self::REFERENCE_ICON_LINK_LK;
                    break;
                case 's':
                    $icon = self::REFERENCE_ICON_LINK_LS;
                    break;
                case 'x':
                    $icon = self::REFERENCE_ICON_LINK_LX;
                    break;
            }
            $contantDisplayAction .= '<img title="Action ' . $instance->getAction() . '" ';
            $contantDisplayAction .= 'alt="[' . $instance->getAction() . ']" ';
            $contantDisplayAction .= 'src="o/' . $this->_getImageByReference($icon) . '" />';

            $object = $this->_nebuleInstance->newObject($instance->getParsed()['bl/rl/nid1']);
            $contantDisplaySource .= '<img title="' . $object->getFullName();
            $contantDisplaySource .= '" style="background:#' . $object->getPrimaryColor();
            $contantDisplaySource .= ';" alt="[]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" />';

            $object = $this->_nebuleInstance->newObject($instance->getParsed()['bl/rl/nid2']);
            $contantDisplayTarget .= '<img title="' . $object->getFullName();
            $contantDisplayTarget .= '" style="background:#' . $object->getPrimaryColor();
            $contantDisplayTarget .= ';" alt="[]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" />';

            $object = $this->_nebuleInstance->newObject($instance->getParsed()['bl/rl/nid3']);
            $contantDisplayMeta .= '<img title="' . $object->getFullName();
            $contantDisplayMeta .= '" style="background:#' . $object->getPrimaryColor();
            $contantDisplayMeta .= ';" alt="[]" src="o/' . self::DEFAULT_ICON_ALPHA_COLOR . '" />';
        } else {
            $contantDisplayIcon .= '<img title="ERROR" ';
            $contantDisplayIcon .= 'alt="[E]" src="o/' . $this->_getImageByReference(self::REFERENCE_ICON_LINK_LL) . '" ';
            $contantDisplayIcon .= '/>';

            $contantDisplayValid .= '<img title="ERROR" ';
            $contantDisplayValid .= 'alt="[E]" src="o/' . $this->_getImageByReference(self::REFERENCE_ICON_ERROR) . '" ';
            $contantDisplayValid .= '/>';
        }

        $result .= $divDisplayOpen;
        $result .= $divLinkOpen;
        $result .= $contantDisplayIcon;
        $result .= $contantDisplayValid;
        $result .= $contantDisplaySigner;
        $result .= $contantDisplayDate;
        $result .= $contantDisplayAction;
        $result .= $contantDisplaySource;
        $result .= $contantDisplayTarget;
        $result .= $contantDisplayMeta . "\n";
        $result .= $divLinkClose;
        $result .= $divDisplayClose;

        return $result;
    }


    /**
     * Affiche l'image du carré de couleur de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return void
     */
    public function displayObjectColor(Node $object, string $htlink = ''): void
    {
        echo $this->convertObjectColor($object, $htlink);
    }

    /**
     * Prépare l'image du carré de couleur de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function convertObjectColor(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $image = $this->_prepareObjectColor($object, 'iconNormalDisplay');
        return $this->convertHypertextLink($image, $htlink);
    }

    /**
     * Affiche l'image du carré de couleur de l'objet ou de l'entité, mais pas de lien vers l'objet.
     *
     * @param Node $object
     * @return void
     */
    public function displayInlineObjectColorNolink(Node $object): void
    {
        echo $this->convertInlineObjectColorNolink($object);
    }

    /**
     * Prépare l'image du carré de couleur de l'objet ou de l'entité, mais pas de lien vers l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function convertInlineObjectColorNolink(Node $object): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        return $this->_prepareObjectColor($object, 'iconInlineDisplay');
    }

    /**
     * Affiche l'image de l'icône de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @param string $icon
     * @return void
     */
    public function displayObjectIcon(Node $object, string $htlink = '', string $icon = ''): void
    {
        echo $this->convertObjectIcon($object, $htlink, $icon);
    }

    /**
     * Prépare l'image de l'icône de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @param string $icon
     * @return string
     */
    public function convertObjectIcon(Node $object, string $htlink = '', string $icon = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        if ($icon != '')
            $icon = $this->_prepareObjectIcon($object, $icon, 'iconNormalDisplay');
        else
            $icon = $this->_prepareObjectFace($object, 'iconNormalDisplay');
        return $this->convertHypertextLink($icon, $htlink);
    }

    /**
     * Affiche l'image du carré de couleur et l'icône de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @param string $icon
     * @return void
     */
    public function displayObjectColorIcon(Node $object, string $htlink = '', string $icon = ''): void
    {
        echo $this->convertObjectColorIcon($object, $htlink, $icon);
    }

    /**
     * Prépare l'image du carré de couleur et l'icône de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @param string $icon
     * @return string
     */
    public function convertObjectColorIcon(Node $object, string $htlink = '', string $icon = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $color = $this->_prepareObjectColor($object, 'iconNormalDisplay');
        if ($icon != '')
            $icon = $this->_prepareObjectIcon($object, $icon, 'iconNormalDisplay');
        else
            $icon = $this->_prepareObjectFace($object, 'iconNormalDisplay');
        return $this->convertHypertextLink($color . $icon, $htlink);
    }

    /**
     * Prépare en version inserré au texte l'image du carré de couleur de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function prepareInlineObjectColor(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'image de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return void
     */
    public function displayInlineObjectColor(Node $object, string $htlink = ''): void
    {
        echo $this->convertInlineObjectColor($object, $htlink);
    }

    /**
     * Prépare en version inserré au texte l'image de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function convertInlineObjectColor(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'image de l'objet ou de l'entité et le nom ou un texte, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $text
     * @param string $htlink
     * @return void
     */
    public function displayInlineObjectColorText(Node $object, string $text = '', string $htlink = ''): void
    {
        echo $this->convertInlineObjectColorText($object, $text, $htlink);
    }

    /**
     * Prépare en version inserré au texte l'image de l'objet ou de l'entité et le nom ou un texte, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $text
     * @param string $htlink
     * @return string
     */
    public function convertInlineObjectColorText(Node $object, string $text = '', string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        if ($text == '')
            $text = $object->getFullName('all');
        $text = $this->_truncateName($text, 0);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color . $text, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'image de l'objet ou de l'entité et le nom, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return void
     */
    public function displayInlineObjectColorName(Node $object, string $htlink = ''): void
    {
        echo $this->convertInlineObjectColorName($object, $htlink);
    }

    /**
     * Prépare en version inserré au texte l'image de l'objet ou de l'entité et le nom, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function convertInlineObjectColorName(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $name = $this->_truncateName($object->getFullName('all'), 0);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color . $name, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'icône couleur, l'image et un texte ou le nom de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return void
     */
    public function displayInlineObjectColorIcon(Node $object, string $htlink = ''): void
    {
        echo $this->convertInlineObjectColorIcon($object, $htlink);
    }

    /**
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function convertInlineObjectColorIcon(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        $icon = $this->_prepareObjectFace($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color . $icon, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'icône couleur, l'image et un texte ou le nom de l'objet ou de l'entité, lien vers l'objet.
     *
     * @param Node   $object
     * @param string $htlink
     * @return void
     */
    public function displayInlineObjectColorIconName(Node $object, string $htlink = ''): void
    {
        echo $this->convertInlineObjectColorIconName($object, $htlink);
    }

    /**
     * @param Node   $object
     * @param string $htlink
     * @return string
     */
    public function convertInlineObjectColorIconName(Node $object, string $htlink = ''): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $htlink = $this->_prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        $name = $this->_truncateName($object->getFullName('all'), 0);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        $icon = $this->_prepareObjectFace($object, 'iconInlineDisplay');
        return $this->convertHypertextLink($color . $icon . $name, $htlink);
    }

    /**
     * Affiche en version inserré au texte l'icône couleur, l'image et un texte ou le nom de l'objet ou de l'entité, mais pas de lien vers l'objet.
     *
     * @param Node $object
     * @return void
     */
    public function displayInlineObjectColorIconNameNolink(Node $object): void
    {
        echo $this->convertInlineObjectColorIconNameNolink($object);
    }

    /**
     * Prépare en version inserré au texte l'icône couleur, l'image et un texte ou le nom de l'objet ou de l'entité, mais pas de lien vers l'objet.
     *
     * @param Node $object
     * @return string
     */
    public function convertInlineObjectColorIconNameNolink(Node $object): string
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        $name = $this->_truncateName($object->getFullName('all'), 0);
        $color = $this->_prepareObjectColor($object, 'iconInlineDisplay');
        $icon = $this->_prepareObjectFace($object, 'iconInlineDisplay');
        return $color . $icon . $name;
    }


    /* --------------------------------------------------------------------------------
	 *  Affichage des liens.
	 * -------------------------------------------------------------------------------- */
    public function displayInlineLinkFace(string $link): void
    {
        echo $this->convertInlineLinkFace($link);
    }

    public function convertInlineLinkFace($link): string
    {
        if ($link == '')
            return '';
        if (!is_a($link, 'Nebule\Library\Link'))
            $link = $this->_nebuleInstance->newLink($link);
        if (!$link->getValid())
            return '';

        switch ($link->getAction()) {
            case 'f':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LF);
                $iconUpdate = $this->convertUpdateImage($icon, 'f', 'iconInlineDisplay');
                break;
            case 'u':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LU);
                $iconUpdate = $this->convertUpdateImage($icon, 'u', 'iconInlineDisplay');
                break;
            case 'd':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LD);
                $iconUpdate = $this->convertUpdateImage($icon, 'd', 'iconInlineDisplay');
                break;
            case 'e':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LE);
                $iconUpdate = $this->convertUpdateImage($icon, 'e', 'iconInlineDisplay');
                break;
            case 'c':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LC);
                $iconUpdate = $this->convertUpdateImage($icon, 'c', 'iconInlineDisplay');
                break;
            case 'k':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LK);
                $iconUpdate = $this->convertUpdateImage($icon, 'k', 'iconInlineDisplay');
                break;
            case 's':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LS);
                $iconUpdate = $this->convertUpdateImage($icon, 's', 'iconInlineDisplay');
                break;
            case 'x':
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LX);
                $iconUpdate = $this->convertUpdateImage($icon, 'x', 'iconInlineDisplay');
                break;
            default :
                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LL);
                $iconUpdate = $this->convertUpdateImage($icon, 'l', 'iconInlineDisplay');
                break;
        }

        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IMLOG);
        $colorDate = $this->convertUpdateImage($icon, $link->getDate(), 'iconInlineDisplay'); // FIXME

        // Prépare le contenu à afficher.
        $return = '';
        foreach ($link->getSigners() as $signer)
            $return .= $this->convertInlineObjectColor($signer);
        $return .= $colorDate;
        $return .= $iconUpdate;
        $return .= $this->convertInlineObjectColor($link->getParsed()['bl/rl/nid1']);
        $return .= $this->convertInlineObjectColor($link->getParsed()['bl/rl/nid2']);
        $return .= $this->convertInlineObjectColor($link->getParsed()['bl/rl/nid3']);

        return $return;
    }

    public function displayInlineIconFace(string $iconName): void
    {
        echo $this->convertInlineIconFace($iconName);
    }

    public function convertInlineIconFace(string $iconName): string
    {
        if (Node::checkNID($iconName) && $this->_nebuleInstance->getIoInstance()->checkLinkPresent($iconName))
            $iconID = $iconName;
        elseif (defined('self::' . $iconName))
            $iconID = constant('self::' . $iconName);
        else
            $iconID = self::DEFAULT_ICON_LO;

        $instance = $this->_nebuleInstance->newObject($iconID);
        if ($instance->getID() == '0')
            return '';

        return $this->convertUpdateImage($instance, $this->_traductionInstance->getTraduction($instance->getName('all')), 'iconInlineDisplay');
    }

    public function displayInlineInfoFace(): void
    {
        echo $this->convertInlineInfoFace();
    }

    public function convertInlineInfoFace(): string
    {
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IINFO);
        return $this->convertUpdateImage($icon, $this->_traductionInstance->getTraduction('::::INFO'), 'iconInlineDisplay');
    }

    public function displayInlineOKFace(): void
    {
        echo $this->convertInlineOKFace();
    }

    public function convertInlineOKFace(): string
    {
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IOK);
        return $this->convertUpdateImage($icon, $this->_traductionInstance->getTraduction('::::OK'), 'iconInlineDisplay');
    }

    public function displayInlineWarningFace(): void
    {
        echo $this->convertInlineWarningFace();
    }

    public function convertInlineWarningFace(): string
    {
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IWARN);
        return $this->convertUpdateImage($icon, $this->_traductionInstance->getTraduction('::::WARN'), 'iconInlineDisplay');
    }

    public function displayInlineErrorFace(): void
    {
        echo $this->convertInlineErrorFace();
    }

    public function convertInlineErrorFace(): string
    {
        $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IERR);
        return $this->convertUpdateImage($icon, $this->_traductionInstance->getTraduction('::::ERROR'), 'iconInlineDisplay');
    }

    public function displayInlineLastAction(): void
    {
        $array = $this->_metrologyInstance->getLastAction();
        switch ($array['type']) {
            case 'addlnk' :
                $this->displayInlineIconFace('DEFAULT_ICON_ADDLNK');
                if ($array['result'])
                    $this->displayInlineOKFace();
                else
                    $this->displayInlineErrorFace();
                $this->displayInlineLinkFace($array['action']);
                break;
            case 'addobj' :
                $this->displayInlineIconFace('DEFAULT_ICON_ADDOBJ');
                if ($array['result'])
                    $this->displayInlineOKFace();
                else
                    $this->displayInlineErrorFace();
                $this->displayInlineObjectColor($array['action']);
                break;
            case 'delobj' :
                $this->displayInlineIconFace('DEFAULT_ICON_LD');
                if ($array['result'])
                    $this->displayInlineOKFace();
                else
                    $this->displayInlineErrorFace();
                $this->displayInlineObjectColor($array['action']);
                break;
            case 'addent' :
                $this->displayInlineIconFace('DEFAULT_ICON_ADDENT');
                if ($array['result'])
                    $this->displayInlineOKFace();
                else
                    $this->displayInlineErrorFace();
                $this->displayInlineObjectColorIcon($array['action']);
                break;
        }
        echo "\n";
        unset($array);
    }

    public function displayInlineAllActions(): void
    {
        $count = 0;
        while ($array = $this->_metrologyInstance->getFirstAction()) {
            if ($count > 0)
                echo "- \n";
            switch ($array['type']) {
                case 'addlnk' :
                    $this->displayInlineIconFace('DEFAULT_ICON_ADDLNK');
                    if ($array['result'])
                        $this->displayInlineOKFace();
                    else
                        $this->displayInlineErrorFace();
                    $this->displayInlineLinkFace($array['action']);
                    $count++;
                    break;
                case 'addobj' :
                    $this->displayInlineIconFace('DEFAULT_ICON_ADDOBJ');
                    if ($array['result'])
                        $this->displayInlineOKFace();
                    else
                        $this->displayInlineErrorFace();
                    $this->displayInlineObjectColor($array['action']);
                    $count++;
                    break;
                case 'delobj' :
                    $this->displayInlineIconFace('DEFAULT_ICON_LD');
                    if ($array['result'])
                        $this->displayInlineOKFace();
                    else
                        $this->displayInlineErrorFace();
                    $this->displayInlineObjectColor($array['action']);
                    $count++;
                    break;
                case 'addent' :
                    $this->displayInlineIconFace('DEFAULT_ICON_ADDENT');
                    if ($array['result'])
                        $this->displayInlineOKFace();
                    else
                        $this->displayInlineErrorFace();
                    $this->displayInlineObjectColorIcon($array['action']);
                    $count++;
                    break;
                default :
                    $count = 1;
            }
            echo "&nbsp;";
        }
        unset($array);
        // Flush la sortie vers le navigateur.
        flush();
    }


    /**
     * Affiche en version inserré au texte les icônes des émotions de l'objet.
     *
     * @param string $object
     * @return void
     */
    public function displayInlineEmotions(string $object): void
    {
        echo $this->convertInlineEmotions($object);
    }

    /**
     * Prépare l'affichage en version inserré au texte les icônes des émotions de l'objet.
     *
     * @param string $object
     * @return string
     */
    public function convertInlineEmotions(string $object): string
    {
        // Vérifie si les émotions doivent être affichées.
        if (!$this->_configurationInstance->getOptionUntyped('displayEmotions'))
            return '';

        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);

        // Ouverture de la DIV.
        $result = "\n<div class=\"inlineemotions\">\n\t<p>\n\t\t";

        $listEmotions = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
        );
        $listEmotions0 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE0,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET0,
        );
        $listEmotions1 = array(
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
            nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
        );

        foreach ($listEmotions as $emotion) {
            // Génère la base du lien html pour revenir au bon endroit en toute situation.
            $htlink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_nebuleInstance->getCurrentConversation();

            // Préparation du lien.
            $source = $object->getID();
            $target = $this->_nebuleInstance->getCryptoInstance()->hash($emotion);
            $meta = $this->_nebuleInstance->getCurrentEntity();

            // Détermine si l'émotion a été marqué par l'entité en cours.
            if ($object->getMarkEmotion($emotion, 'myself')) {
                // Création du lien.
                $action = 'x';
                $link = $action . '_' . $source . '_' . $target . '_' . $meta;
                $htlink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Préparation de l'icône de l'émotion.
                $icon = $this->convertReferenceImage($listEmotions1[$emotion], $emotion, 'iconInlineDisplay');
            } else {
                // Création du lien.
                $action = 'f';
                $link = $action . '_' . $source . '_' . $target . '_' . $meta;
                $htlink .= '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=' . $link . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Préparation de l'icône de l'émotion.
                $icon = $this->convertReferenceImage($listEmotions0[$emotion], $emotion, 'iconInlineDisplay');
            }

            // Si connecté, l'icône est active.
            if ($this->_unlocked
                && $this->_configurationInstance->getOptionAsBoolean('permitWrite')
                && $this->_configurationInstance->getOptionAsBoolean('permitWriteLink')
            )
                $result .= $this->convertHypertextLink($icon, $htlink);
            else
                $result .= $icon;

            // Détermine le nombre d'entités qui ont marqué cette émotion.
            $count = $object->getMarkEmotionSize($emotion, 'all');
            if ($count > 0)
                $result .= $count . ' ';
        }

        // Fermeture de la DIV.
        $result .= "\n\t</p>\n</div>\n";

        return $result;
    }


    /**
     * Affiche le bouton pour continuer l'affichage lorsque la liste est longue.
     *
     * @param string $ref
     * @param string $url
     * @param string $text
     * @return boolean
     */
    public function displayButtonNextObject(string $ref, string $url, string $text): bool
    {
        if ($ref == '')
            return false;
        if ($url == '')
            return false;
        if ($text == '')
            return false;

        echo "<div class=\"inlinecontent\" id=\"$ref\">\n";
        echo "<p class=\"inlinecontentnext\" onclick=\"replaceNextContentFromURL('$ref', '$url')\">$text</p>\n";

        return true;
    }


    /**
     * Affiche des entrés.
     *
     * @param array $list
     * @return void
     */
    public function displayItemList(array $list): void
    {
        if (sizeof($list) == 0)
            return;
        ?>

        <div class="textAction">
            <?php
            foreach ($list as $item) {
                // Affichage.
                if (is_a($item['object'], 'Nebule\Library\Node'))
                    $this->_displayArboItem($item);
            }
            unset($name, $icon, $desc, $link);
            ?>

            <div class="oneAction-close"></div>
        </div>
        <?php
    }

    /**
     * Affiche un élément de l'arborescence.
     *
     * @param array $item
     * @return void
     */
    private function _displayArboItem(array $item): void
    {
        if (sizeof($item) == 0)
            return;
        if (!is_a($item['object'], 'Nebule\Library\Node')
            && !is_a($item['object'], 'Nebule\Library\Entity')
            && !is_a($item['object'], 'Nebule\Library\Group')
            && !is_a($item['object'], 'Nebule\Library\Conversation')
            && !is_a($item['object'], 'Nebule\Library\Currency')
            && !is_a($item['object'], 'Nebule\Library\TokenPool')
            && !is_a($item['object'], 'Nebule\Library\Token')
            && !is_a($item['object'], 'Nebule\Library\Transaction')
            && !is_a($item['object'], 'Nebule\Library\Wallet')
        )
            return;

        // Extraction.
        $object = $item['object'];

        $entity = null;
        $entityID = '0';
        if (is_a($item['entity'], 'Nebule\Library\Entity')) {
            $entity = $item['entity'];
            $entityID = $entity->getID();
        } elseif (Node::checkNID($item['entity'], false, false)) {
            $entity = $this->_nebuleInstance->newEntity($item['entity']);
            $entityID = $entity->getID();
        }

        $htlink = '';
        if (isset($item['link']))
            $htlink = $item['link'];

        $desc = '';
        if (isset($item['desc']))
            $desc = $this->_traductionInstance->getTraduction($item['desc']);

        $icon = '';
        if (isset($item['icon']))
            $icon = $item['icon'];

        $type = $object->getType('all');
        // Extrait un nom d'objet à afficher de façon correcte.
        $entityName = '';
        if ($entityID != '0')
            $entityName = $entity->getFullName('all');

        if ($object->getIsEntity('all'))
            $name = $object->getFullName('all');
        else
            $name = $object->getName('all');
        $namesize = 21;
        $shortname = $name;

        // Normalise la variable si vide.
        $htlink = $this->prepareDefaultObjectOrGroupOrEntityHtlink($object, $htlink);
        ?>

        <div class="oneActionItem" id="<?php
        if ($entityID == $this->_applicationInstance->getCurrentEntity())
            echo 'selfEntity';
        else
            echo 'otherEntity';
        ?>">
            <div class="oneActionItem-top">
                <div class="oneAction-icon">
                    <?php $this->displayObjectColorIcon($object, $htlink, $icon); ?>
                </div>
                <?php
                if ($entityName != '') {
                    ?>

                    <div class="oneAction-entityname">
                        <p><?php $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorIconName($entityID); ?></p>
                    </div>
                    <?php
                }
                ?>

                <div class="oneAction-title">
                    <p><?php $this->displayHypertextLink($shortname, $htlink); ?></p>
                </div>
                <?php
                if (isset($item['desc'])
                    && strlen($item['desc']) != 0
                ) {
                    ?>

                    <div class="oneAction-text">
                        <p><?php echo $desc; ?></p>
                    </div>
                    <?php
                }
                ?>

            </div>
            <?php
            if ($object->getMarkWarning()
                || $object->getMarkDanger()
                || $object->getMarkProtected()
                || (isset($item['actions'])
                    && sizeof($item['actions']) != 0
                )
            ) {
                ?>

                <div class="oneActionItem-bottom">
                    <?php
                    if ($object->getMarkWarning()) {
                        ?>

                        <div class="oneAction-warn">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IWARN);
                                $this->displayUpdateImage(
                                    $icon,
                                    ':::display:content:warningTaggedWarning',
                                    'iconInlineDisplay');
                                echo ' ';
                                $this->_traductionInstance->echoTraduction(':::display:content:warningTaggedWarning'); ?></p>
                        </div>
                        <?php
                    }
                    if ($object->getMarkDanger()) {
                        ?>

                        <div class="oneAction-error">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_IERR);
                                $this->displayUpdateImage(
                                    $icon,
                                    ':::display:content:errorBan',
                                    'iconInlineDisplay');
                                echo ' ';
                                $this->_traductionInstance->echoTraduction(':::display:content:errorBan'); ?></p>
                        </div>
                        <?php
                    }
                    if ($object->getMarkProtected()) {
                        ?>

                        <div class="oneAction-ok">
                            <p><?php
                                $icon = $this->_nebuleInstance->newObject(self::DEFAULT_ICON_LK);
                                $this->displayUpdateImage(
                                    $icon,
                                    ':::display:content:ObjectProctected',
                                    'iconInlineDisplay');
                                echo ' ';
                                $this->_traductionInstance->echoTraduction(':::display:content:ObjectProctected'); ?></p>
                        </div>
                        <?php
                    }
                    if (isset($item['actions'])
                        && sizeof($item['actions']) != 0
                    ) {
                        ?>

                        <div class="oneAction-actions">
                            <?php
                            foreach ($item['actions'] as $action) {
                                if (is_a($action['icon'], 'Nebule\Library\Node'))
                                    $icon = $action['icon'];
                                else
                                    $icon = $this->_nebuleInstance->newObject($action['icon']);
                                $actionIcon = $this->convertUpdateImage($icon, $action['name'], 'iconInlineDisplay');
                                $actionName = $this->_traductionInstance->getTraduction($action['name']);
                                echo '<p>' . $actionIcon . ' ' . $this->convertHypertextLink($actionName, $action['link']) . "</p>\n";
                                unset($actionIcon, $actionName);
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

        </div>
        <?php
        unset($object, $entityName, $name, $shortname, $icon, $desc);
    }
}
