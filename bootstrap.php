<?php
declare(strict_types=1);
namespace Nebule\Bootstrap;

//use nebule;
// ------------------------------------------------------------------------------------------
use Nebule\Library\Cache;
use Nebule\Library\Crypto;
use Nebule\Library\nebule;
use Nebule\Library\Node;

const BOOTSTRAP_NAME = 'bootstrap';
const BOOTSTRAP_SURNAME = 'nebule/bootstrap';
const BOOTSTRAP_AUTHOR = 'Project nebule';
const BOOTSTRAP_VERSION = '020220124';
const BOOTSTRAP_LICENCE = 'GNU GPL 02021';
const BOOTSTRAP_WEBSITE = 'www.nebule.org';
// ------------------------------------------------------------------------------------------



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



/*
 ==/ Table /===============================================================================
 PART1 : Initialization of the bootstrap environment.
 PART2 : Procedural PHP library for nebule (Lib PP) restricted for bootstrap usage.
 PART3 : Manage PHP session and arguments.
 PART4 : Find and load object-oriented PHP library for nebule (Lib POO).
 PART5 : Find application code.
 PART6 : Manage and display breaking bootstrap on problem or user ask.
 PART7 : Display of preload application web page.
 PART8 : First synchronization of code and environment.
 PART9 : Display of application 0 web page to select application to run.
 PART10 : Display of application 1 web page to display documentation of nebule.
 PART11 : Display of application 2 default application.
 PART12 : Main display router.
 ------------------------------------------------------------------------------------------
*/



const PHP_VERSION_MINIMUM = '7.3.0';
if (version_compare(phpversion(),PHP_VERSION_MINIMUM, '<'))
    exit('Found PHP version ' . phpversion() . ', need >= ' . PHP_VERSION_MINIMUM);



// Disable display until routing choice have been made.
ob_start();



/*
 *
 *
 *
 *

 ==/ 1 /===================================================================================
 PART1 - Initialization of the bootstrap environment.

 This part include all default values for the procedural library (Lib PP).
 ------------------------------------------------------------------------------------------
 */

// Logs setting and initializing.
/** @noinspection PhpUnusedLocalVariableInspection */
$loggerSessionID = bin2hex(openssl_random_pseudo_bytes(6, $false));
$metrologyStartTime = microtime(true);

/**
 * Switch log prefix from one app to another.
 *
 * @param string $name
 * @return void
 */
function log_init(string $name): void
{
    global $loggerSessionID;
    openlog($name . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
}

/**
 * Switch log prefix from one app to another.
 *
 * @param string $name
 * @return void
 */
function log_reopen(string $name): void
{
    closelog();
    log_init($name);
}

/**
 * Add message to logs.
 *
 * @param string $message
 * @param string $level
 * @param string $function
 * @param string $id
 * @return void
 */
function log_add(string $message, string $level = 'msg', string $function = '', string $id = '00000000'): void
{
    global $metrologyStartTime;
    syslog(LOG_INFO, 'LogT=' . (microtime(true) - $metrologyStartTime) . ' LogL="' . $level . '" LogI="' . $id . '" LogF="' . $function . '" LogM="' . $message . '"');
}

// Initialize logs.
log_init(BOOTSTRAP_NAME);
syslog(LOG_INFO, 'LogT=0 LogT0=' . $metrologyStartTime . ' LogL=B LogM="start ' . BOOTSTRAP_NAME . '"');

// ------------------------------------------------------------------------------------------
// Name of bootstrap file used by web server.
const BOOTSTRAP_FILE_NAME = 'index.php';

/**
 * Instance de la bibliothèque nebule en PHP orienté objet.
 *
 * @var nebule $nebuleInstance
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleInstance = null;

/**
 * Variable de raison d'interruption de chargement du bootstrap.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapBreak = array();

/**
 * Variable de détection d'affichage inserré en ligne.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapInlineDisplay = false;

/**
 * Variable de détection d'affichage de l'ID de l'entité instance du serveur.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapServerEntityDisplay = false;

/**
 * Activation d'un nettoyage de session général.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapFlush = false;

/**
 * Activation d'une mise à jour des instances de bibliothèque et d'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapUpdate = false;

/**
 * ID of the last signer find on a link search by reference.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$lastReferenceSignerID = '';

/**
 * ID de la bibliothèque mémorisé dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibraryID = '';

/**
 * ID of the signer for the library.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibrarySignerID = '';

/**
 * Instance non dé-sérialisée de la bibliothèque mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibraryInstanceSleep = '';

/**
 * ID de l'application mémorisé dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationID = '';

/**
 * ID de départ de l'application mémorisé dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationStartID = '';

/**
 * Instance non dé-sérialisée de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationInstanceSleep = '';

/**
 * Instance non dé-sérialisée de l'affichage de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationDisplayInstanceSleep = '';

/**
 * Instance non dé-sérialisée des actions de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationActionInstanceSleep = '';

/**
 * Instance non dé-sérialisée des traductions de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationTraductionInstanceSleep = '';

/**
 * Commutateur pour charger directement une application sans passer par le pré-chargement.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationNoPreload = false;

/**
 * Demande de changement d'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapSwitchApplication = '';

/**
 * Instance de l'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$applicationInstance = null;

/**
 * Instance d'affichage de l'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$applicationDisplayInstance = null;

/**
 * Instance d'action de l'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$applicationActionInstance = null;

/**
 * Instance de traduction de l'application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$applicationTraductionInstance = null;



/*
 *
 *
 *
 *

 ==/ 2 /===================================================================================
 PART2 : Procedural PHP library for nebule (Lib PP).

 TODO
 ------------------------------------------------------------------------------------------
 */

const LIB_LINK_VERSION = '2:0';
const LIB_DEFAULT_PUPPETMASTER_EID = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
const LIB_DEFAULT_PUPPETMASTER_LOCATION = 'http://puppetmaster.nebule.org';
const LIB_RID_SECURITY_AUTHORITY = 'a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288';
const LIB_RID_CODE_AUTHORITY = '2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288';
const LIB_RID_TIME_AUTHORITY = 'bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288';
const LIB_RID_DIRECTORY_AUTHORITY = '0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288';
const LIB_RID_CODE_BRANCH = '50e1d0348892e7b8a555301983bccdb8a07871843ed8f392d539d3d90f37ea8c2a54d72a.none.288';
const LIB_RID_INTERFACE_BOOTSTRAP = 'fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288';
const LIB_RID_INTERFACE_LIBRARY = '780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288';
const LIB_RID_INTERFACE_APPLICATIONS = '4046edc20127dfa1d99f645a7a4ca3db42e94feffa151319c406269bd6ede981c32b96e2.none.288';
const LIB_RID_INTERFACE_APPLICATIONS_DIRECT = 'f202ca455549a1ddd553251f9c1df49ec6541c3412e52ed5f2ce2adfd772d07d0bfc2d28.none.288';
const LIB_RID_INTERFACE_APPLICATIONS_ACTIVE = 'ae2b0dd506026c59b27ae93ef2d1ead7a2c893d2662d360c3937b699428010538b5c0af9.none.288';
const LIB_REF_CODE_ALGO = 'sha2.256';
const LIB_LOCAL_ENVIRONMENT_FILE = 'c';
const LIB_LOCAL_ENTITY_FILE = 'e';
const LIB_LOCAL_LINKS_FOLDER = 'l';
const LIB_LOCAL_OBJECTS_FOLDER = 'o';
const LIB_LOCAL_HISTORY_FILE = 'h';
const LIB_NID_MIN_HASH_SIZE = 64;
const LIB_NID_MAX_HASH_SIZE = 8192;
const LIB_NID_MIN_ALGO_SIZE = 2;
const LIB_NID_MAX_ALGO_SIZE = 12;
const LIB_FIRST_GENERATED_NAME_SIZE = 6;
const LIB_FIRST_GENERATED_PASSWORD_SIZE = 14;
const LIB_FIRST_RELOAD_DELAY = 3000;
const LIB_FIRST_LOCALISATIONS = array(
    'http://cerberus.nebule.org',
    'http://puppetmaster.nebule.org',
    'http://bachue.nebule.org',
);
const LIB_FIRST_AUTHORITIES_PUBLIC_KEY = array(
    '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAudMrAyvG3uqI9JLZRtqi
nlgiF6hAp/whKWlujNXE+x0p6ibJEaIAPS+VyR4Lw9819UqObpMI2fa+Ql8/dJPM
9r7Js/eJbRy6U7+EtBJa8ZIBTRtGXjKdBhkyQcWm8TqglitTG0pIoJOlB1+CbP2W
TtfbC6ZEFBFhlEH+qqy7Laua3m2yqVXqTY9FEBPYcX/Q2qpOeep+DkMQ/UwYyCZ0
Pv7KJ0aLlbju2UpYAp+zNfl6OKo37Va29anhU1i7lfXug7h0d9Lc4Xpl+KLfKn4A
g6VHSKXRAENvCXnGG3DM7UUdHM74NQXtwKzmtEwn7KT/3MKM6ohdbffkrAJFaeby
EMCVqq9nH4CZUIOGzLsAICtA6FXD5bi0OKv1Y1fzH4MHlc8FL5fCEdJ2ZftlURDH
Z2X2dE73Tx3TuyHr3e3A2xXMxcXZ0bs41Ey9wUWPRtBfEU6Yr3yXDQjMmLeCj/Vz
0Z/92hX5zE6UDpxTbuoPSUzGH0xwwZzsLAOIM0TvOxDI1ATX8M0Di2veYdLJMqoF
QMqFriycSa9a4U4SyXomUAqj9jBzn1dmPN+cvC+2ByqoRdGKkJQZAnLcfpN+G+lt
/GJe8Xgw01QlOFGT8PV9IvZek96PociLNqoyOhye7q5/Ik0fsEEIzYW2jvLGnrkv
6dEOw+BEVa0QiNx/ju9yzHMCAwEAAQ==
-----END PUBLIC KEY-----' . "\n",
    '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAza1LNLyY3EMTeCOATF0A
VvK8MtfpBTV/VEihy5dVedjNERbSsowCaWFDBUmUhRqAVDH/0I3qbP15gNTVa5As
yF0GX3w3268XF7ZEqgfjyDEzVpsUq2iThecHMekKPkiE7UAySfLLNkfnA0yEPcnN
JXo+fGEdnxhXimGO8aEmNNb4St5CUGNFjXlInvQH8vv5s3JV+ZPgAbpnB77ykYSx
+ernGQulv5E6j4sjHLh0M7eoHvt21HuKHHp0dyl5ZVSJJDak1rBRbpmumFqFzSf5
dVCtMiX1Dd2bqqw844JptVciFO/8tVXfaIt3aSbC61bjF+gCGFIe3meOVTVbJ5lN
BQIDAQAB
-----END PUBLIC KEY-----' . "\n",
    '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9fNcqhAIUjQnXJTl7kRR
OWaxG/4luD/V22MqyIGBkopRNS0N2KS+DcTGa8znBbOhZrObtmEG3sRvqMrR5bSL
1TJXvmYf7IP3UygVw2q4khp/Xxpu4dYFow0+Y0Hj6TMf6H0BNa0OZiwpOArCtScz
qgbkLcYViD4pEccCoC/IttBSeXGZ9p9Yaqal8W11EBe8LlajJ0XGRPEy/KZogXzE
OtkCmN47ZsfeKtn75ordrzOL+2W4KgA26QPCfBdPzanrV1NSeGyzmxV6lb2PAVUP
A1tjFJTZ9FBwf4YzWKzOuj4V+0oftsHRI+hKfBphHqWaVrg8QBjONLUePQPNRvZM
GQIDAQAB
-----END PUBLIC KEY-----' . "\n",
    '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDG3s5ky2Ku85OCgVSGqNIpTLUY
ozhnkLO2WScjhREDVTmbA+oiEQ28myOP/30LI6vI3dMHK3dPIcyYK8ApaoGn0H3x
qxRlLiWpdAm47WMgbhuzktHxQ2D7pWWERPb/ybRrXZxymKb2Zv4RXd2WN+qulk1J
vQKPJnvvk30EMrSyhQIDAQAB
-----END PUBLIC KEY-----' . "\n",
    '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAq9dMDhbJB0FK7voozRcd
hLtTJHkrVxPt1XmhFJTnLLsqkbS5hoRqw8POMb5YKpJRinuE19auF12vUwVNNRV1
WEKqNw23yQFj5DAeJhQ0rn12BRr9EGO0fpVcT45c1XUBJ+O3RCjGbLvAuPllVv6o
kPqbWxrpBgjFUbvUgxC543t+Nu7Uih7c5oABp+9H6nLEdWpF+MhbTsOpNp1G+C5o
u6cSeGbs98x91v71AQB/5Y1poVDSCeaO8KT0I1BF6h8ookYJaFtZ4gO/qm4doQDg
YQzYHG+jbU1QJriY5uCQqbgrbGj8b8VfUGNtSoRPVfDbhU0mxKJrrAtpLjsWModS
fQIDAQAB
-----END PUBLIC KEY-----' . "\n",
);
const LIB_FIRST_LINKS = array(
    'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>4fcc946ef03dff882c0b6a1717c99c0ce57639e99d1f52509e846874c98dad5abd28685c9d065b4ef0e9fefbbee217e91fc4a72ecac81712e1e2c14bd06612e71e9afdb09ef1c10e68117fe8edc4f93510318719d0a6d7436a1802cd38f814cba8503ef24d50aeca961825bc39b169acbe52240fa8528a44f387ee5dff0e096a2ab49a0b181fa688678540dfc409000104a6ab77c44a4495ac98d48f35658238c99f5b1f83d04c3309412ebf26b7b23c18bdde43b964ebb6b28b60393b4c343f567137461743153091039c07e35432fa7d0b46b729f65c11960cbda5cb78f3d8da52aaf662724e771125cce2fb99ef1409fbb23840872c6557fe63f2b25c8fc49b6b5663a44cdf2e829ffa9698cc121648136fd102333a556a97ac5b208a6b6fa584e239a35237fe9c38fd09fbe4c0580ca538d92c4e29d5e22ce4846df2563dc4cb39a599b92f22018b4973b768cf59cb8f517f3adae3ee21b7c43a812ec6c245fe548e6187a0e07ce6a0af38c40ccd24383216cbd312322e1583d5d358ccdc9911b67fdbf7d13b9f57a0a17a42f736be9dbd383fd9e7c0ce2589fbd6550a8e07ab90618302956a1bf69e76aaf3da829e1af4f7c7ceff169ce5e698ebe1987fa1b694c6b25130c0be5bbfdfe4a8594e54067abe235bf796cf455a84906d02ebc79e3feaa069db7c4adac872c104bfcbc08b2dfbcc3c9fd6aa465fb9d86c7f26.sha2.512',
    'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>72ce2d1c9075a26bb8264564055426cec289d350cbc1e8d5e7472ee28db17606d06777a5831d3346f82a78a1e0b1a7ce2b66d61f59e0c15e8deb53c45f3245746c4afeece6a240cf50f285597b51050c49156b2b07860c4a78ec07d8bd1ec5bb1450b41b914e96642ae0260b819821727ded678288a10c21a02809a22333fad392c5f2d67636e1174c03d936457371a8f2dae5e1369f93adff03903426a6c69cddada88b6ec687573a2ec5cb78cb8f9401631739c78adcf000b92acece6cc34626528be94173754eda077ea26bbc45b7a4067b1dfdccb54ce8efb7634d2ab19ec0b30441d38d77e412e3bca1fc77fb6552fed7e14b4dfae157db5d1abc0bd768f0fd548a4124908985b7c9cc47e8058516008e99850cf0d7811981c8fd0621ebf8ca0a16b2ea3d6f2bf1a0e6b881344638d314f76af6c97dbf5618d04d881ee3b555284fcf461c23d3729aceb4be35118d28e7bccd2001324cdacb0b8000b21cf23c6d09cbf6d8d0ab4b64dd9e6872ed90ee349164c62a08506f5148cd6beb0b18449f798e6517419db44f14e623de912a6161b1f45eb3a40f8215b61cc5735c1362f7a45463dcc4c8b9a08954afc49f4c9eba13508aa9a5d5ed1bea9f8136ca2acb5c5601dca31b033f802b7fc0491f8b9062574500ad17674e1bfdcd78b183f1e4eeb7395822e95161c3fa5f3a1b59f8c18c9d4ba716a7cb7756d3d971d881.sha2.512',
    'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>f976f916d794ad6384c9084cef7f2515305c464b2ab541142d952126ca9367e3.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>56a9487ee5ea86fa35649b3b5e9fe6c20387139d41b3aa822dacacaa4c1284f31f60aec293df1406fcd6dfc89248457d4f97f35c1ebe0a30c0a0424eb28b9c5b2299b3faecf5b691857f73eaedd98e56b8c17c4f183bb300f6e0c01f156be592e1669fa26db14fc864ebc913df77e53ebeb6e8fdefd49ac71fd00a42f99a790c098a33755078cb73b37c618a379c12578d66be94256689d0cc65427cee7c51e07f37553931e66d0b0090779d3e869fb45888a4c0b1e61bc1ce63f8a12ea4ff6cb039f62c24c1b5cbbee78329e21042278514d4b9cdd3f028c2b12e6ad4a00526e1fc2093a65c8d33402cae2f38a40fa1ec6b37a52725ad1010e78b9b6262d50055e6214a2b4b96fdc7354220b5a0117979441ffd24f976e42943defdf36ef9910fb452d920890251c4e5297e56a16d2a4ab97c00882cff19b42f3f6a1edca9cf6f0a7e157a8dfbeb5595ebb576e5d512f5f046071455a55d1098e19e3725dae564e9fc138d4faec180e5fd71a6db36c6cbece824e52cac913004ad406247b2eb51a91a2c0a2552961a265157c59e8455a4a7c8ad7e0be90014bc8fcac3e103fc4469380961d7d1f59a77678f2ff97f7db78cb243a0bb71ab6b63f4a10e786a06b8d4aff2f5b5a133a1019524af59923c813fcc0bc4588b64e6cb6e81c77fdaec73b069d20e435d69f40fe0a2e5656c9fa7aed13fda2d071bd9c850a7415ffdcd.sha2.512',
    'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>90f1075d96b6d74e3b69bc96448993f9f3a02f593ad0795d5b02e992bacf5b39.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>3a2ea2a5abfb9078ee67ba038f0f7e2900edde48e613ac2cc9c51e2f3951094c8087ce85ff08adf5eecd30d1442210b39e80cb65359290493df77f5d7f1b6b5a4311b506954887e432865174cef01390e8ce891582bdaa1564235848f151869824796316e1206f60132290474b314f55b4c038739e296137f84ea4f7dbf1a099dfdd5f141e0111da06b83016c2dcf857b0542836f9a9ed3cec5623a8be3142b3d29df5b580355969673029dc8c738a485105c8bb653cbc788d49557f02f8d4cf8f3971c23ab9b889f6757da351686ac7b9ed18860750c1f9de77d430805c7a0109eba20bcc33e7bafc77eda83974155285d0af75cebdf3b4784f3adb9ae05c89ef47578658849493ab6ee0cc58410a1a5bae9e75162fcc84a292617b32803d717af6e41cd5c750394edc38cc299dc0bf5133ec610d696d7e1ecd05398332aac8a255741094c2f96802867a139ca9203f8edd5cc23a0a32b75df643d76caf852e1b4557a7efb9b8d837f1a985d3b435df9460e37628a7f37d8527f208bfd9ed58989b72ffc152e7f4e1bbc195319a66bd78e5a9c056905dd0da842be58be710ff41ff96d63166e767868b1d6705cdeb4fbb3261fd36cf64c47db39567b49fda3c257c020b577c837bfe362b6e9b261efe8a33b1586820451eaf9767d8c6511a598864e2f6238be310166ee7309026f7afdf69059f110b88431e6242b2beac7456.sha2.512',
    'nebule:link/2:0_0>020210714/l>01351dd781453092d99377d94990da9bf220c85c43737674a257b525f6566fb4.sha2.256>daea63066cd8f5d4a9c8c80f3cc51f3c20b7fc74ac170ab2ce1950999b422f17.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>8c2333c73a4444979bfe70f1f7f84a095e088ac7d46f3c8f5883461a5c03f3656524302923456527f2df35410b4e6e9f78aa0a8e1eb944b0c1d2ffae256ddec4c4a0ca4487c90aa0f695f595625a841505c53fb38f0b4433f582960825244eb4b916a43bbaef9dee13cfd81c0d4bab18d0b2755441e003f944957134165a84168285cd876af7fe33152ceb3ab0e60c9e855e3ea40828add081166d39fb7c83bb95078dc0f51070d2ec4799838f8015e426ca1f898c1535f8c2e50028ef05365c019f9a08330ce4c093d1a3fb5db053e55b8b3b54b48e1d59a67f4414d50fc720ccf16db0438ff0a0d7f125fd98076058d8a6eb3f36214be7f7fbec484c8baf6260518c66faac802753384f74e4cbaf1412caf05809cdcec706af69ac840d9cb0a267311257afe52646ecb9f3be4b9aaf1f50d69aa65b309a3cb661e85f9cde9b583fc665e0ba4d21baf889067195b294a0a584c65284bd65426dfc2f24a71697834de398cbe2a90331dc1a452feca5b2d1a5bcfd1ab140af4c5ec5c3bf7fa9e9c1e28742495d412ce03da09abf1d7ea6289c2f1e7aff017e05c3fdbd95e5cafcae4d1a3f4567f3c18fd06b42670fa0efb27b8d40a7504004090f9a08292a096a2008017daf5095866a98b01f64ff7646f3798df7a725a2c92f3ecd95c3a535e52c0b38eb0c5789fe19c717414bb9d2152d1583edab8e2bb321e6481b9664d8d1.sha2.512',
    'nebule:link/2:0_0>020210714/l>01351dd781453092d99377d94990da9bf220c85c43737674a257b525f6566fb4.sha2.256>9e854553b868627af36369b5d9e1e9d5ae31a398e2bacb0816e98e5fb6e806ef.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>46b1dbd873bc547d6d7c0cbd9ded5eb9356735fa1944c391420cf8a5b637f2721ca0816621f71ed11e8a2d29a3ac7d5e36cebcc00fdf2545c15af902306f8d9bcd7df679768d4bf80e1a9f86979beb73accfd5f37f9784d44a5caaf1d442e4c66da7986c1a1c8760b07b1c550f751f16f99cd79aa5b639161169421b1ca58e8038aaa5fea7a2dfdf1a8bf18aa1a56a960b06e3637eeba28bc03a7b7326852c6e8890d7e1a7de3fa904e8027ba7142cb190d45832b871f5ba5a3b0230179e5a9ffc2ff8d11cd7ffb681311ca27dfd9aac246a5180f325cf7fb88d3a1c208b695725e365bce130f0b8761523409e0da0b7eec6c76b737d9d3e0c2e2675eadf6f27e60be613c31cd8837f756aa8e8cdca7164437cf19fa3f403cf0ed7a1a146cb2749df0d2d376dd58d18f5aa78c5b3669375ffbb242e1e65b1a78b88213efa7928225de4d09c217e12b941d8dca26719461a1730c45d1d34a88e3f2ea392a706fec654d44793bf1754e7a728e4f8c5a9b258ebc88f916b8f3b906b774145e4270d277f1962b0cfa0141462b7c6a2909966bf94d9b605b1dd89f81c94ed45f511dd0be7489dbf9018d073837d66f4e637dded4e1ea1ff6c0d32ef010df25b38315b0c0ecec8f809a4b1739a96701a143113d5662d31c586310dff7405f45e96ae6fdd2536376533b416e62d0282875d5c8ceb3dd293831613a49a0b492870b8b835.sha2.512',
    'nebule:link/2:0_0>020210714/l>19762515dd804577f9fd8c005a7803ddee413f264319748e30aa2aedf318ca57.sha2.256>b6fef678931e0761314983d9a08c19b095b088cf6500891206ca1f8b78c2d008.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>30093ab7ca813bfaeedb75a5cad9a1a85c66fb541e64d6a3f15853b39d7727704f2fe305c93d3c329941be88c4015098aadfcd2df15e36eb93c29aa97b24f5336fac7fb1e2be8b5d8d2af31239352bce431ab9497b30235a5ddf41a8cb914c1658d8ab6b4c43c492401820aac47b476e085599bbc194ef6820bb8d89cb0663a1c37ac573caffa60f44e0d09f5ac6890fc118b9bf563ce11fc2c02508d54c0d4c340e6da2287a1522b6496644702801b0b669eb32f76f3d55587f74e56d0709ec5a4abd1a86530cbf038ff9ff9b83bbeb9f116e9fabaa8d0b8d426cdd444de489e17898b8d9fe952adb44bcafc0ef1aa1b6389c1ee372ed925c871de08b9cff2d38043c541da66ac8792a421fe44626eefaed010102792f0a3cc6a47a3972f093a8529aa89890630e944da7760b1ef59030bf29e364c7b5576a60a29e6507d93f70d03d3500b3546c2e8cdb81e0bf57507f27d5b76874fa9af0bd417388420fbb070ddbdadb5556e6ec98d6577acf4a52d0856a36a0c1608568792fb21ab668a5ab1b23b508213ee82872605ef0010879d84470399d68ca80996995fdb7fefa261b81b8a3dbb2c60d74508aa671970c2df4029ff345f8e506ec6c503a7db84bc63c29cf56af53296b6f1baf0cd674d9391dc8675cb1c90b7ca1a462a0cb5fc2b1af678d6420bfb6633739502a56dbf8457d530dfbcc40cefd4d0919d64488a8f6.sha2.512',
    'nebule:link/2:0_0>020210714/l>4e72933c991c2bb27415cfeff02e179b9a3002934e472142c4f612e3893e46e1.sha2.256>83db082578142c900e36765ebc210893d79ed0ab1127d687f3307c0c061802e6.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>2a6d15ec23edb07da6e1271026a252c5a3b96bf7931cc25dcbe85ba8d488ff5128e243720202f3a792d3700845b387b8f7b8f0da70770c34fc3172ae9fb17bc8b2e2235a92dd133136cc5b63ebd06ccedeb2c4291b11ff3dd89c73caf65ec14279e08ae2ce02672da9f6de5f40b249857c128d2c2c7932de5fc8223fffd0db899abefeaf725dac4526711594ab06f16a1702b34afef83c17b30b4cde0fe080031a36fb3a676e79199b2601cd1dcf7dc52d43b990634cad8df3dafb645c6fe446aaf25a276103c5989f9536ca4fe7e35d079ea7b61c5d68132bc5c2ac4fdb1cb0762a566fc9da85075ee454a6e2f14af2facd084bda59ce98131f066dff3af7935d107e518310bd84fe54c60be549e48b00937a998969db572bd33757c637b556f12203e999f2a9d5e4f62e2c632a08fdc0c877ef0a75603e5e44f6a5f3b3ac5dbfa4ffec1cb3431a992143d5534f9ded09b0a183c135b5759ddedef9426da41e82f6d522f94c1c4c422fc8983d68685966de408e029d2324e0e9baf00c8bb18454f3f32285b73b4b68abca210afaa8cfe089f7d3b32b1289a57119e8115b85cdc1b2d5756675fce351ef4501f4d7226075d8092e1e428551c8133764751b58311ae0c54e2a57065517020f0286fb51e96a01226db1143e479a94d6ff2c922ec27c5f64af5bc8e8794fb5391400bac6666bd5e21f61d23aefc94db0f3a525a59c.sha2.512',
    'nebule:link/2:0_0>020210714/l>abdbaa31e404463ecc644f1eecdeb9b5f94428eb140fa5c66a7687ee96ed47aa.sha2.256>663eb81c89c27739f0f875617bcd45b3a18d4b8eb859b8c6e5dccbf9085a2ef9.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>7a76055a8e051f927027018b7d82ff2b5e388513f6e901ad61a07a02b6b23ea6f58e1d498eea7aebf597c9975f4422babed00b3dc9c4b1ba09967cd245246412d3827d010f99da45b7408d5d0e6b47a3df8e719675dc907108013ccbd698731d6b74912d1723e58d70cb96ceaac979cfdf2b332d9d010402ef581b4d509ead6d0e03b3587d265e5e460b7f78261315aeb1057ca02e2b4d24590bbc5986d8882d97e1afe080708b8f5d6768b1a423e2fe981916aef2ce663be384c8cff0cffae3f72316fc0bf068b031a068b9f6090cf2cac0be1360acebcb1ad22d67dacb7b46eb51f69e7898d25a9511ae9c4100c42eb0c7a6317d5a90ca1ac40f37d95f83ff9de81843c3206c11c4a26d2b0093b49e48c436f9b1b07ad5828ea711493e69bb08ed6c2940004d56af796c3daf68ad6dbadaecff717f6d7373d1cf182d6be14a5e8c37d4abfdc9f0c843d59de875e4501f202d87b6a14940e0725eb5e5d2a94bea3f2764fa9918093340d7948aab7846fd820dfe1c73345389d8f1a2ea1c4aa943bdeaf73c234812a46f997ebc058caa0a912ce02fe2414ef84bd957c47e78582601ee5aee78f973ff3fe318ac091e2a8021435203d0a7a4b459ab4714bf5d1395324cd4a098777fbaa70716c21d8f2c82632a0f7abb9e366ae3138fd7eb5e024823f80275d486fc7763d3ac5147992807c779ad1fedcf60446de306e453dade.sha2.512',
    'nebule:link/2:0_0>020210714/l>01351dd781453092d99377d94990da9bf220c85c43737674a257b525f6566fb4.sha2.256>a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5ef73754556326b9bde83230cc4ed503364821fa96b774086c2146a451f842339b5ec66741f6608d5fa16a7bde0d80f7711c828163a9b2222c128fa6403130b97592d11b2001551724c65360195764dda11e58c4b3ef8cb7870dd9f1add0ab5680e957d3adcd6b60e22fc718ddd14aa9048f2c26b466b0a0139e7f93a7ffc39df0d430646eff6581548b1cb09b032a829e4a905a712a6400641408a8574d50db98e52a60eae13966ea266023c11c174b86bc4f2e43f10bd4c94e86a8a7b17f9a9eb95cdf82d3f08b25605ba0f7a1b4fe54aaa7bd263cde55bf7e62ab73ddf4d8b5aceccb8d1491344ce001702111e04eb887b45ef8c437c25e1bf4928abded74d1bab4104fa734ccdf22310d771b44972f8aeeaa389e3b0fc2ca32cfc6a4ab89f89ed712b8c13f1e36fde7b00a69a726df4cfc0234d57131d7d5650849be5ba2f1eb8693da15e3502a1787d6e6b5bb74f5cc40e806e53e4888c8d5dd2d0f0f4a8922e9863e0c432dc491260ac6ff34a041e8317e4edf6a265c8f0ce16a39d4ec27ae4d34e8a9c0027318b9f52d35362429f706b9dd9ea6d6c2bb8cf71d6322f62f78ec62532be1abd30f8bc5c5a004c399ce13a53a98d42947c02426e93028918d6d0cd84d81b7e533c8306d81faa1333f2dd904156d8feecf610f1b02096aebac63daa8a59785de0e9e30d4941ebd152bd82ce12cf9d460c01d001c394a404a.sha2.512',
    'nebule:link/2:0_0>020210714/l>19762515dd804577f9fd8c005a7803ddee413f264319748e30aa2aedf318ca57.sha2.256>2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>85662c55aecc78be3d4decb1c97236b0ee574ac9a76bce59f8f7badd0a6697812b64722351c5ff23f230a6a3f2701e0b9abf1ebaa78a61986e2b551ffe9fb53b53d9b617213102dc219e9aff304e5290c9573c5d2b258dfca2bf493ba2b4b9a105335d8efe32cb41ac52035fff04da2b6023c96dc6b3fad2ab3ec5fc6f6c9eb45bc3888241f920b315c679b76f20b6397810da6a9d35e1c7b8c77f2068b6ca99eee5cbe4539fe52b6e0bcb402b8d52da7322b11e083c24fbca066c4a01d6d925cec684f3c8426582ac1123596ff2c79007c7275fa16dcb1297a98c0a5769fdf024994ba085e9f76de875e3e56d82f464432cc56f243c4d8224e7bed16137c8bf17067fdac04494fd3f7aed052f843b01fb611fe5d1874dcbef452b0fcc4898f8c4022673d436a15a75dc1fb664f79c819fef692ff24dd5c411ee169523fb8d7d175f1c681acb42e88669e21a48760a657c53a243fa14740c9ce20e12d78e7184a7fef8ea8e80ecc55e15be78ed99f300a6fa1a864ad532c96351bb4ea6a3608e933c3f6f9baf1c952754d74370a83f917253fb18a707b64ac668ad5a4845602fdf8196f68ba7d71190e2e92f2a71359e6fbc0c05a021efe3ce6d7fa6cb2fd13d4043549ecea2318ef8d515daf1c04e2741ef2946389b547d59d8256fb1dbadd7cee689dbf4fdae0133cda0171fe0131792ec6ab947625783e21a7878b553dc62.sha2.512',
    'nebule:link/2:0_0>020210714/l>4e72933c991c2bb27415cfeff02e179b9a3002934e472142c4f612e3893e46e1.sha2.256>0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>57408e07d0430677174b0e50385ef605a172639f9dd6710b0dafbf1568585d1cdfe9c2149e6b57c585362051c802841e9105c17efbf5502342e624c9c6561a1413b73d79b8ead07131fecc0616d9b1aa25f874809731e52f3c5993821fef827f2fb2d668033f4573f8b7c65e29d69130f3b084bc48b8deb976c44a8874cd9231472390cd9caaed9dbd1a897d48ddb6cf50bfa9d54a870122a18e57064efa4b1c74959f265f280acc16d986c4e767cab2fb88db3d9e1fb6314362dfdfaa6f14d648976518530f3d325d40a6d75fa792f1d34c92f83738a230df743d3f774169a2cb6d8cef187caa3039e899d65f9b6c897a72f8a4ad4b6cd16d43ad843d36c6c762390dadc8b68327e2525e8bb2c27a01e4216e8120d1e60f9651dec68396e85cd953b456e361317f25ceab9b28ebc5a2c850f719978cd6f28b18e07f85e7c962bee9195f9f6bce3061025247aed71905f4f6a77015752a0f370af1302f4c7261de9eb65701d42cae9b0d4c308c51d597c0a5af68b9a60a45f8bfb47b82cd5e4999143cabdeaf49e7cad9d4057a2d2b892418386a79d9ab73390a474bf45045d99a0f589a1ddf70ccecaf51cae014e2a4401571719fd9d3361df062bebd7dd65b15fc0c7d63c186aa21c4c8a38d9a7d66cbf162600b4815c005815e414a0d2ee8174475a67b2a9b31eb53deca780a49649e5638ca867fb80cd2fdb7aea18099cb.sha2.512',
    'nebule:link/2:0_0>020210714/l>abdbaa31e404463ecc644f1eecdeb9b5f94428eb140fa5c66a7687ee96ed47aa.sha2.256>bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>a0fcf7e0dd428892f8e8d95ec5683b37eb00578904b092812a41b32c6ad9e769ee4c6e45fdd628b27e237c89ba2d7faa0b466024f5397aa331e24de3cf0a09501b411f34b91e910273f52eca056d9618e43132302b8dd9c81b7e4b8831dd983fd6674cb8fe8348c7a7baac0d2a68b1538f153e7a316e3c6eff4e59d3b699578cdaf5b00e691f46e7971f0faa74889184509303b49f9b2d6becf74b6f546d7c61deb7709a68446266b35d134df92772f38b5557e6ecf6c5e671c3316f4df28ded39e328c730783d3a030e2c167a581566bfdf19f5fcabd83ccb65b625590aa2402fdcca823611467f17cbf9bc154aefe535055f8674c27e24cad84c9f2984ce1718b133f72ff1eec56d5df8fd1d25a9646044fb28eeb1a4a0a277b844e22ed559efadafc3959bcbde44aa6b6f4fbbee149ecb13a4537edcf654720dd4bf9b5a8b2bb0c322cc182e3ef7bb2971052aa6b3af706da3a04f3854d2b77f4fde40981fbd4ef857fc338303f2ebdd3bff3b9335ed0357730b35a2d5bb9f2d16440cf1230487fb72a5187a82e4d1e6daf66b2d24e8970e6e1aff18218fbfc4a65bd494cd13a345f7bb6c2bc906029af5ab25217e1227915c676437dacf695ac277b9ddabf4910aec25513b39ed1ae82f8fce136674a8e0b061065755230f458fab98818aeae2e9fa3a4542e55361b8d8fd8e07f1d63f411e75903a161f3143d40d70247a.sha2.512',
);
const LIB_FIRST_RESERVED_OBJECTS = array(
    'nebule/objet',
    'nebule/objet/hash',
    'nebule/objet/homomorphe',
    'nebule/objet/type',
    'nebule/objet/localisation',
    'nebule/objet/taille',
    'nebule/objet/prenom',
    'nebule/objet/nom',
    'nebule/objet/surnom',
    'nebule/objet/prefix',
    'nebule/objet/suffix',
    'nebule/objet/lien',
    'nebule/objet/date',
    'nebule/objet/entite',
    'nebule/objet/entite/prive',
    'nebule/objet/entite/localisation',
    'nebule/objet/entite/maitre',
    'nebule/objet/entite/maitre/securite',
    'nebule/objet/entite/maitre/code',
    'nebule/objet/entite/maitre/annuaire',
    'nebule/objet/entite/maitre/temps',
    'nebule/objet/entite/autorite/locale',
    'nebule/objet/entite/recouvrement',
    'nebule/objet/interface/web/php/bootstrap',
    'nebule/objet/interface/web/php/bibliotheque',
    'nebule/objet/interface/web/php/applications',
    'nebule/objet/interface/web/php/applications/modules',
    'nebule/objet/interface/web/php/applications/direct',
    'nebule/objet/interface/web/php/applications/active',
    'nebule/option',
    'nebule/danger',
    'nebule/warning',
    'nebule/reference',
    'puppetmaster',
    'cerberus',
    'bachue',
    'kronos',
    'asabiyya',
    'application/x-pem-file',
    'application/octet-stream',
    'application/x-httpd-php',
    'text/plain',
    'sha224',
    'sha256',
    'sha384',
    'sha512',
);

// Command line args.
const LIB_ARG_BOOTSTRAP_BREAK = 'b';
const LIB_ARG_FLUSH_SESSION = 'f';
const LIB_ARG_UPDATE_APPLICATION = 'u';
const LIB_ARG_SWITCH_APPLICATION = 'a';
const LIB_ARG_RESCUE_MODE = 'r';
const LIB_ARG_INLINE_DISPLAY = 'i';
/** @noinspection PhpUnusedLocalVariableInspection */
const LIB_ARG_STATIC_DISPLAY = 's'; // TODO not used yet
const LIB_ARG_FIRST_PUPPETMASTER_EID = 'bootstrapfirstpuppetmastereid';
const LIB_ARG_FIRST_PUPPETMASTER_LOC = 'bootstrapfirstpuppetmasterlocation';
const LIB_ARG_FIRST_SUBORD_EID = 'bootstrapfirstsubordinationeid';
const LIB_ARG_FIRST_SUBORD_LOC = 'bootstrapfirstsubordinationlocation';

/**
 * List of options types.
 * Supported types :
 * - string
 * - boolean
 * - integer
 */
const LIB_CONFIGURATIONS_TYPE = array(
    'puppetmaster' => 'string',
    'hostURL' => 'string',
    'permitWrite' => 'boolean',
    'permitWriteObject' => 'boolean',
    'permitCreateObject' => 'boolean',
    'permitSynchronizeObject' => 'boolean',
    'permitProtectedObject' => 'boolean',
    'permitWriteLink' => 'boolean',
    'permitCreateLink' => 'boolean',
    'permitSynchronizeLink' => 'boolean',
    'permitUploadLink' => 'boolean',
    'permitPublicUploadLink' => 'boolean',
    'permitPublicUploadCodeAuthoritiesLink' => 'boolean',
    'permitObfuscatedLink' => 'boolean',
    'permitWriteEntity' => 'boolean',
    'permitPublicCreateEntity' => 'boolean',
    'permitWriteGroup' => 'boolean',
    'permitWriteConversation' => 'boolean',
    'permitCurrency' => 'boolean',
    'permitWriteCurrency' => 'boolean',
    'permitCreateCurrency' => 'boolean',
    'permitWriteTransaction' => 'boolean',
    'permitObfuscatedTransaction' => 'boolean',
    'permitSynchronizeApplication' => 'boolean',
    'permitPublicSynchronizeApplication' => 'boolean',
    'permitDeleteObjectOnUnknownHash' => 'boolean',
    'permitCheckSignOnVerify' => 'boolean',
    'permitCheckObjectHash' => 'boolean',
    'permitListInvalidLinks' => 'boolean',
    'permitHistoryLinksSign' => 'boolean',
    'permitInstanceEntityAsAuthority' => 'boolean',
    'permitDefaultEntityAsAuthority' => 'boolean',
    'permitLocalSecondaryAuthorities' => 'boolean',
    'permitRecoveryEntities' => 'boolean',
    'permitRecoveryRemoveEntity' => 'boolean',
    'permitInstanceEntityAsRecovery' => 'boolean',
    'permitDefaultEntityAsRecovery' => 'boolean',
    'permitAddLinkToSigner' => 'boolean',
    'permitListOtherHash' => 'boolean',
    'permitLocalisationStats' => 'boolean',
    'permitFollowUpdates' => 'boolean',
    'permitOnlineRescue' => 'boolean',
    'permitLogs' => 'boolean',
    'permitJavaScript' => 'boolean',
    'codeBranch' => 'string',
    'logsLevel' => 'string',
    'modeRescue' => 'boolean',
    'cryptoLibrary' => 'string',
    'cryptoHashAlgorithm' => 'string',
    'cryptoSymmetricAlgorithm' => 'string',
    'cryptoAsymmetricAlgorithm' => 'string',
    'socialLibrary' => 'string',
    'ioLibrary' => 'string',
    'ioReadMaxLinks' => 'integer',
    'ioReadMaxData' => 'integer',
    'ioReadMaxUpload' => 'integer',
    'ioTimeout' => 'integer',
    'displayUnsecureURL' => 'boolean',
    'displayNameSize' => 'integer',
    'displayEmotions' => 'boolean',
    'forceDisplayEntityOnTitle' => 'boolean',
    'linkMaxFollowedUpdates' => 'integer',
    'linkMaxRL' => 'integer',
    'linkMaxRLUID' => 'integer',
    'linkMaxRS' => 'integer',
    'permitSessionOptions' => 'boolean',
    'permitSessionBuffer' => 'boolean',
    'permitBufferIO' => 'boolean',
    'sessionBufferSize' => 'integer',
    'defaultCurrentEntity' => 'string',
    'defaultApplication' => 'string',
    'defaultObfuscateLinks' => 'boolean',
    'defaultLinksVersion' => 'string',
    'subordinationEntity' => 'string',
);

/**
 * Default options values if not defined in option file.
 */
const LIB_CONFIGURATIONS_DEFAULT = array(
    'puppetmaster' => LIB_DEFAULT_PUPPETMASTER_EID,
    'hostURL' => 'localhost',
    'permitWrite' => true,
    'permitWriteObject' => true,
    'permitCreateObject' => true,
    'permitSynchronizeObject' => true,
    'permitProtectedObject' => false,
    'permitWriteLink' => true,
    'permitCreateLink' => true,
    'permitSynchronizeLink' => true,
    'permitUploadLink' => false,
    'permitPublicUploadLink' => false,
    'permitPublicUploadCodeAuthoritiesLink' => false,
    'permitObfuscatedLink' => false,
    'permitWriteEntity' => true,
    'permitPublicCreateEntity' => true,
    'permitWriteGroup' => true,
    'permitWriteConversation' => false,
    'permitCurrency' => false,
    'permitWriteCurrency' => false,
    'permitCreateCurrency' => false,
    'permitWriteTransaction' => false,
    'permitObfuscatedTransaction' => false,
    'permitSynchronizeApplication' => true,
    'permitPublicSynchronizeApplication' => true,
    'permitDeleteObjectOnUnknownHash' => true,
    'permitCheckSignOnVerify' => true,
    'permitCheckObjectHash' => true,
    'permitListInvalidLinks' => false,
    'permitHistoryLinksSign' => false,
    'permitInstanceEntityAsAuthority' => false,
    'permitDefaultEntityAsAuthority' => false,
    'permitLocalSecondaryAuthorities' => true,
    'permitRecoveryEntities' => false,
    'permitRecoveryRemoveEntity' => false,
    'permitInstanceEntityAsRecovery' => false,
    'permitDefaultEntityAsRecovery' => false,
    'permitAddLinkToSigner' => true,
    'permitListOtherHash' => false,
    'permitLocalisationStats' => true,
    'permitFollowUpdates' => true,
    'permitOnlineRescue' => false,
    'permitLogs' => false,
    'permitJavaScript' => false,
    'codeBranch' => 'stable',
    'logsLevel' => 'NORMAL',
    'modeRescue' => false,
    'cryptoLibrary' => 'openssl',
    'cryptoHashAlgorithm' => 'sha2.256',
    'cryptoSymmetricAlgorithm' => 'aes-256-ctr',
    'cryptoAsymmetricAlgorithm' => 'rsa.2048',
    'socialLibrary' => 'strict',
    'ioLibrary' => 'ioFileSystem',
    'ioReadMaxLinks' => 2000,
    'ioReadMaxData' => 10000,
    'ioReadMaxUpload' => 2000000,
    'ioTimeout' => 1,
    'displayUnsecureURL' => true,
    'displayNameSize' => 128,
    'displayEmotions' => false,
    'forceDisplayEntityOnTitle' => false,
    'linkMaxFollowedUpdates' => 100,
    'linkMaxRL' => 1,
    'linkMaxRLUID' => 3,
    'linkMaxRS' => 1,
    'permitSessionOptions' => true,
    'permitSessionBuffer' => true,
    'permitBufferIO' => true,
    'sessionBufferSize' => 1000,
    'defaultCurrentEntity' => LIB_DEFAULT_PUPPETMASTER_EID,
    'defaultApplication' => '0',
    'defaultObfuscateLinks' => false,
    'defaultLinksVersion' => '2.0',
    'subordinationEntity' => '',
);

const LIB_BOOTSTRAP_ICON = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAARoElEQVR42u2dbbCc
ZX2Hrz1JMBSUAoEECCiVkLAEEgKkSBAwgFgGwZFb6NBOSVJn0H5peXXqSwVrdQZF6Cd0RiQ4kiH415ZpOlUECpKABBLewkIAayFAAgRCGxBylJx+2KdDwHCS
7Ov9PHtdMzt8IGf32f/ev+v+388riIiIiIiIiIiIiIhINalZgvKTUtoTmFy89gUmAXsDewC7F69dgV2AnYGdgLFb/P4jwO+BYeAN4HVgI/AqsAF4BXgRWAc8
DzwLrImIV6y+ApDehHx/4LDiVQemAR8G9uzzpr0M/Bp4HGgAjwCPRMQafzUFIK2F/XDgOGB28TrkXf9kJMPfbmvb9BiwvHgtjYiH/XUVgLwz7GOBU4GTgZOK
GT7noLcrhkeA24BbgVsi4neOAgUwaKE/ETgTOB04aMDL8RSwBLg5Iu5wdCiAKgb+j4BzgXOKmV7em1uBG4FFEfGG5VAAZQ39B4AFwHnAzAq19L1cMjwA/BC4
NiI2WhoFUIbg/zXwOeAoQ99RGdwPfDcirrUsCiC30M8BLgQ+beh7IoOfAFdFxDLLogD6FfoacBFwCc2Tbgx972XwInAF8J2IGLEsCqAXwd8fuByY72yfVVdw
HfBVT0BSAN0K/mzgm8Bcq5E1twN/HxHLLYUC6ETw5wJXAYc725eqK3gYuCAibrckCqCV4J8IXEPznHuDX14RPA583pOMFMD2Bn9msZ6cafArJYIHgfkR8aAl
UQBbC/5EYCHwCYNfaRH8DJgXES9YEhiyBJBS+mea17qfqhgrP9mdCqxLKV1tSQZ8oKeU/qJo98c5FAaSYWBBRNygAAYr+PvRvALNdb7LghrNaw4+GRHPuQSo
fvgvp3lLqxl2QXbAxX9nAs+mlC6zA6hu8A+meSOKyY57GYVngZMi4gk7gOqE/zJgNbCf41u2wX7A6kHpBmoVD/6ewDJgquNaWmA1cGyV7348VOHwnw2sBw52
HEuLHAy8XIwlBVCi8N8ALMY9/NJ+hzwCLC7GlEuAzIM/geYhHXf0STdYA8yKiPV2APmF/3jgJdzRJ91jMvBSMdYUQEbh/wJwpy2/9GhJcGdK6VKXAHmE/ybg
M45N6QM/joizFUB/gl8DHuKdT9IR6TUPAzPLek/CWknDvxvNY7R72/JLnxkBXgCmRsT/KoDuh38y8AQw3vBLRhJ4E5hStguKhkoW/jrwjOGXDCfS8cCalNIh
CqA74T8SeLTMSxepvAQAGimlWQqgs+E/huajoTzMJ7lLYARYkVL6U/cBdC789xh+Kdk+gRpwTETcqwDaa/ud+aXMEjgqIlYogB0Pf71Y8xt+KbsE6hHxmALY
/vBPprm3H8MvFZAAwAER8awC2Hb4P0DzxIr3GX6pkAQ2ARNzO1loKLPw12ie4Wf4pUrUijG9uhjjCuA9eACYaPilohKYWIxxBbCV2X8xzVt1G36psgRmpJRu
zGWDxmQS/i8Af+v4kAFher1e/22j0bg7ByP1O/zH07yZh8igcXxE3DWwAiju4fcSHuuXweP/x/yEiHh5UPcBrDD8MsD7A0aAlf3ciL4JoLjN8gGGXwZcAgek
lH7Urw0Y06fwnwNc7u8vAsDh9Xr9sUaj8Wjl9wEUj+tab+sv0v/9Af1YAtxt+EW2uj9gWaWXAMUTV88y/CJblcCEer1Oo9G4s5cf2qvwT6F5M08RGZ0pEfFU
1ZYAt/H2pZEi8t77A26v1BKgaP3PyKH1r9VcfViLrOtRA3br1VKg1oPwT6b5VNVsmDBhAuvXr3fEA6eddhrjx48f+DoMDw+zZMmS3DZrcrefMzC2B1/i38hs
r/+JJ57I0qVLWbdu3cAP/HHjxjFu3Dj77pHsVqcjRXa6eovxru4DSCmdC8wks73+b731FieccAKTJk2yBZBsVyTAEUWGyikAYCEZ7/hTApJ7Y1JkqHwCSCld
DYwj82P+SkAy7wLGpZSuKpUAUkoTKdENPpSAZM7fFZkqTQdwHSU75q8EJPOlwA9KIYCU0gzgzyjh6b5KQDJeCpxWZCv7DqB0s78SkEHtAjoqgOL+fkdQ8ot9
lIBk2gXMKjKWbQfwPSpyvr8SkEy7gO9mKYCU0seAaVToUl8lIBl2AYeklE7MsQO4igpe7acEJMMu4OqsBJBSmk2Fn+qjBCSzLmBGkblsOoBvUvFr/ZWAZNYF
fCMLAaSU9gfmMgC3+VICklEXcFJxqX3fO4DLBqnySkAy4vK+CqB41vmCQau6EpBMWNBXAQAXDmrllYDkQErpon4K4FIG+EafSkD6zAhwSV8EkFL6CLA3A36P
fyUgfaQGTEwpHdOPDuBCvM23EpAcuoAL+yGAhE/4UQKSQxfwmZ4KIKU037orAcmHlNK8XnYAn7P9VwKS1TLg8z0RQEppV2C27b8SkKyWAbNTSrv0ogNYYL2V
gGTJgl4IYJ7tvxKQLJcB87oqgJTSeCpwyy8lIBVdBswqMtq1DuBc66wEJGvO7aYA/tz2XwlI1suAs7spgFNs/5WAZL0MOLUrAujkjQiVgBKQ7rEjtw7fkQ7g
DEurBKQUnNkNAXzSuioBKQWnd1QAKaWxwEHWVQlIKTg4pTSmkx3AKdZUCUip+HgnBXCy9VQCUirmdlIAJ1lPJSCl4uROCmCG9VQCUipmdkQAKaVDraUSkPKR
UjqsEx3AcZZSCUgpmdMJARyD5/8rASkbI0V22xbAUXj+vxKQslEDju6EAKZbSyUgpaTelgBSSvtZQyUg5SWltG87HYCH/5SAlJsZ7QjAQ4BKQMrN9HYEUMcj
AEpAysrItvYDbEsA0/AIgBKQslIDprYjgCnWUAlIqZnSjgD2tH5KQErNhJYEkFLa3dopASk/KaU/bqUDOMDSKQGpBB9sRQCTrZsSkEowuRUBeBagEpBqsG8r
Apho3ZSAVIKJrQhgb+umBKT0jIyW5dEE4CFAJSDlpzZalkcTgIcBlYBUg91bEcBu1k0JSCVo6TyA91s3JSCVYNdWBLCLdVMCUgl2aUUA462bEpBKsHMrAtjJ
uikBqQQ7tSKAcdZNCUglGNuKAIasW7UlsNdeezE05M88AAwZcvkD5syZw1133WUhNMNW2Wx5qs3IyAjr16/njjvusBjVZnMrAviddRsMXnjhBSVQbX7figCG
rZsSkEow3IoA3rRuSkAqwRutCOB166YEpBK83ooANlo3JSCV4LVWBPA/1k0JSCV4tRUBbLBuSkAJVIINrQjgZesmSqD0jIyW5dEE8KK1EyVQemqjZXk0Aayz
dqIEKsG6VgTwvHUTJVAJ1rYigGetmyiBSrCmFQE8Y91ECVSCZ3ZYABHhYUBRAhUgIlo6DwBgveUTJVBqXhrtf25LAE9aP1ECpeapdgSwmuaJBCJKoHyMAI+3
I4AGzRMJRJRA+agVGW5ZAKusoSiBUrOqHQE8ZP1ECZSah1oWQER4NqAogRITEWvb6QBcBogSKC+Nbf2D7RHAfXgkQJRA2RgBlndCAL/CIwGiBMpGrchu2wJY
Zi1FCZSSZW0LICIetY6iBMpHRKxqWwAFD1pOUQKl4oHt+UfbK4BbracogVJxWycFcLv1FCUwuAK4xXqKEigVt3RMABHxFvCENRUlUApWR8TmTnYAAEusqyiB
UrDdWd0RAdxsXUUJlIJ/7bgAIuKX1lWUQP5ExNJudAAAP8frAkQJ5MoI8LMd+YMdFcBivC5AlECu1IqMdk0Ai6yxKIGsWdQ1AUTEJmCFywBRAlm2//dHxHA3
OwCAhS4DRAlk2f4v3NE/akUAP7DWogSy5LquCyAifgvc6zJAlEBW7f+vimx2vQMAuMZlgCiBrNr/a1r5w5YEEBHXW3NRAvkQET/smQAKfuwyQJRAFu3/Ta3+
cTsCuNJlgCiBLNr/K3sugIi4F1hnFyBKoK+z/9qIWN5zARRcYRcgSqCvs/+32nmDtgQQEVc5DEUJ9I92MzjUgW241mEoSqAvfL/dN+iEAL7qEBQl0Bcu67sA
IuI54Be4M1CUQK8YAW4pstf3DgDgS7gzUJRAr6gBX+zEG3VEABFxH80nkdgFiBLo/uy/MiJWZCOAggvsAkQJ9GT2v6BTb9YxAUTEnUDDLkCUQFdn/0Ynb9A7
1OENPN8uQJRAV2f/8zv5hh0VQHE74pV2AaIEujL7r9iRW373owMAmG8XIEqgK7P/vE6/accFEBEPA/9uFyBKoKOz/5KIWJW9AAoW2AWIEujo7L+gG2/cFQFE
xIvAdxxuogQ6wpUR8VJpBFBI4CJgk0sBUQJttf6bIuLibn3AUJe/wDyXAqIE2mr9z+vmB3RVABFxI3C/XYDkLoExY8bkOPvfFxGLu/khY3vwRc4EnsupssPD
w45865B7J1ArstP1D+k6KaWvAF/Loqq1GiMjNiTWI/tafCUivl4JARQSeBrY330CItts/Z+JiA/14sOGevjF5hp+ke2alOf26sN6tuej0WhsqNfrm4GP+RuL
jNr639xL2/SUlFIDmGY3IPIHrf9jEXFoLz90qA9fdE4Rfvc8ibwd/lqRDSotgIjYACQ7AJF3dOJnRcSrvf7gvpz90Gg0HqvX638CzPC3F+H6iPhmv8zTN1JK
/w0cYDcgA9z6Px0RB/ZrA4b6XIAjtyiEyKCFf8sMDJ4AIuJl4KO4U1AGL/w14KMR8Uo/N6TvV0A0Go019Xr9NeBUx4UMCDXg4m5f6FMKARQSuKder08FDnNs
yACwqJvX+O+oibIhpbQSOMLxIRXmgYiYlVMrQmYSeA7YB48MSPXW/WsjYr+cNmoow0JNA97EnYJSrfC/CUzNbcOyE0BEbAQOAjYrAalI+EeAD0fEawpg+yTw
PFDHw4NS/vDXgGkRsTbHDRzKtXIR8QQwUwlIycM/MyKezHUjh3KuYEQ8BMxWAlLS8B9djOFsKcWe9pTS0cDyLQorUobw35/7xpYmTCmlmcADSkBKEP4jIuLB
MmxwqYKUUpoKNIrtVgKSW/g3A4dGxOqybHTpQpRS2gf4NTBeCUhG4X8DmFIcwSoNQ2WrdHE4ZW9greNOMgn/88DEsoW/lB3Au7oBrx2QfpPVuf2V7wDe1Q3M
AhZtYWKRXs36ADeUOfyQyeXA7dBoNH66xf0EPEIgvQh/DbgoIi4p+5epTFhSSscCS6v2vSTLmf+4iLi7Cl+oUkFJKe0BrAA+5FiVLvAb4Mji1vYogHxFsBA4
zyWBdLDlXxgR86v25SobjpTSp4GfKAHpQPjPioifVvELVjoYKaXdgWXAIY5laYEGMKcfT+xRAJ0VwZeAr9sNyA7M+l+OiH+q+pcdmDCklA4E/hP4oGNcRuFp
YG5E/NcgfNmBmw1TSl8G/tFuQAZ11h9oARQS2Be4GThKERh84D7gU2U8l18BtCeCc4DrgfeZhYFkE/BXEXHToBbAma8pgiuAS+wGBmrW/1ZEXDroxXCwvy2B
CcC1wBmKoNLBvxn4bESstyQO8q2JYDqwkOZjmxVBdYK/AjgvIh61JApge0QwB/gecKgiKHXwHwXOj4hllkQBtCKC44GrgFmKoFTBXwlcEBG/tCQKoBMiOBL4
BvBxq5E1Pwe+GBErLYUC6IYI9gG+Bnz2XTOO9G+2B/g+8A+5PoJLAVRTBhcAFwP7KoK+BH8tcEVEXG1JFEA/RXA0cBFwjl1BT2b7xcC3y/DkHQUweDL4S+Bv
gI8og46G/m7gmoj4kWVRAGUQwc7AAmA+zXMKlMGOh34FcB1wbUS8aWkUQFllsBNwbrFE+IQVGZX/AG4CFkXEsOVQAFUUwnHAp4DTgakDXo7VwBLgXzxZRwEM
ogyGgFOAucDJNE84okJLhnd/h5XArcDtwC8iYrOjQAHIO6UwneZOxGOB2UB9G6HKMejQvKfecpo78O6JiFX+ugpAWpPCJGAGMJ3m9QlTgGnAhD5v2nrgceBJ
mufdrwIeioh1/moKQHojh/cDBwL70zwpaRLNJyjvAexevHYFdqF585Odildti9l7uHhtAl4HNgKvAhuAV4AXgXU0n4S7BvhNRGy0+iIiIiIiIiIiIiIiIjny
f9eV8VcbpfPFAAAAAElFTkSuQmCC";

const LIB_APPLICATION_LOGO_LIGHT = "data:image/png;base64,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
Jggg==";

/**
 * Result of Lib PP initialisation.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$libraryCheckOK = false;

/**
 * Activate rescue mode to recovery code problems.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$libraryRescueMode = false;

/**
 * Buffer of option's values.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$configurationList = array();

/**
 * ID authorities of security.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleSecurityAuthorities = array();

/**
 * ID authorities of code.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleCodeAuthorities = array();

/**
 * ID authorities of directory.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleDirectoryAuthorities = array();

/**
 * ID authorities of time.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleTimeAuthorities = array();

/**
 * ID de l'entité locale du serveur.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleServerEntity = '';

/**
 * ID de l'entité par défaut.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleDefaultEntity = '';

/**
 * ID de l'entité en cours.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebulePublicEntity = '';

/**
 * Clé privée de l'entité en cours.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebulePrivateEntity = '';

/**
 * Mot de passe de l'entité en cours.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebulePasswordEntity = '';

/**
 * Liste des entités autorités locale.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleLocalAuthorities = array();

/**
 * Current code branch NID used to find different apps codes.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$codeBranchNID = '';

/**
 * Metrology - Lib PP link read counter.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleMetrologyLinkRead = 0;

/**
 * Metrology - Lib PP link verify counter.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleMetrologyLinkVerify = 0;

/**
 * Metrology - Lib PP object read counter.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleMetrologyObjectRead = 0;

/**
 * Metrology - Lib PP object verify counter.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleMetrologyObjectVerify = 0;

/**
 * Metrology - Lib PP timers.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleMetrologyTimers = array();

/**
 * First run - EID of an alternative puppetmaster.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$firstAlternativePuppetmasterEid = '';

/**
 * First run - EID of an optional subordination.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$firstSubordinationEID = '';

/**
 * Remember on local entity file if we are on the first launch.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$needFirstSynchronization = false;

// Cache of many search result and content.
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadObjText1line = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadObjName = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadObjSize = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityType = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityLoc = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityFName = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityName = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityPName = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadEntityFullName = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheFindObjType = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadObjTypeMime = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheReadObjTypeHash = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsText = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsBanned = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsSuppr = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsPublicKey = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsPrivateKey = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheIsEncrypt = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheFindPrivKey = '';
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheLibrary_o_vr = array();
/** @noinspection PhpUnusedLocalVariableInspection */
$nebuleCacheLibrary_l_grx = array();

/**
 * Return option's value. Options which are present on environment file are forced.
 * If empty, return the default value. On unknown configuration name, just return the string.
 *
 * @param string $name
 * @return mixed
 */
function lib_getConfiguration(string $name)
{
    global $configurationList;

    if ($name == ''
        || !isset(LIB_CONFIGURATIONS_TYPE[$name])
    )
        return null;

    // Use cache if found.
    if (isset($configurationList[$name]))
        return $configurationList[$name];

    // Read file and extract asked option.
    $value = '';
    if (file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        $file = file(LIB_LOCAL_ENVIRONMENT_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        if ($file !== false) {
            foreach ($file as $line) {
                $line = trim(filter_var($line, FILTER_SANITIZE_STRING));

                if ($line == '' || $line[0] == "#" || strpos($line, '=') === false)
                    continue;

                if (trim(strtok($line, '=')) == $name) {
                    $value = trim(strtok('='));
                    break;
                }
            }
        }
    }

    // If empty, read default value.
    if ($value == '' && isset(LIB_CONFIGURATIONS_DEFAULT[$name]))
        $value = LIB_CONFIGURATIONS_DEFAULT[$name];

    // Convert value onto asked type.
    switch (LIB_CONFIGURATIONS_TYPE[$name]) {
        case 'string' :
            $result = $value;
            break;
        case 'boolean' :
            if ($value == 'true')
                $result = true;
            else
                $result = false;
            break;
        case 'integer' :
            if ($value != '')
                $result = (int)$value;
            else
                $result = 0;
            break;
        default :
            $result = null;
    }

    $configurationList[$name] = $result;
    return $result;
}

/**
 * Metrology - Incrementing one stat counter.
 *
 * @param string $type
 * @return void
 */
function lib_incrementMetrology(string $type): void
{
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;

    switch ($type) {
        case 'lr':
            $nebuleMetrologyLinkRead++;
            break;
        case 'lv':
            $nebuleMetrologyLinkVerify++;
            break;
        case 'or':
            $nebuleMetrologyObjectRead++;
            break;
        case 'ov':
            $nebuleMetrologyObjectVerify++;
            break;
    }
}

/**
 * Metrology - Return one stat counter.
 *
 * @param string $type
 * @return string
 */
function lib_getMetrology(string $type): string
{
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;

    $return = '';
    switch ($type) {
        case 'lr':
            $return = (string)$nebuleMetrologyLinkRead;
            break;
        case 'lv':
            $return = (string)$nebuleMetrologyLinkVerify;
            break;
        case 'or':
            $return = (string)$nebuleMetrologyObjectRead;
            break;
        case 'ov':
            $return = (string)$nebuleMetrologyObjectVerify;
            break;
    }
    return $return;
}

/**
 * Metrology - Set one stat timer.
 *
 * @param string $type
 * @return void
 */
function lib_setMetrologyTimer(string $type): void
{
    global $nebuleMetrologyTimers, $metrologyStartTime;

    $nebuleMetrologyTimers[$type] = sprintf('%01.4fs', microtime(true) - $metrologyStartTime);
}

/**
 * Metrology - Get one stat timer.
 *
 * @param string $type
 * @return string
 */
function lib_getMetrologyTimer(string $type): string
{
    global $nebuleMetrologyTimers;

    if (isset($nebuleMetrologyTimers[$type]))
        return $nebuleMetrologyTimers[$type];
    return '';
}

/**
 * Initialize nebule procedural library.
 *
 * @return boolean
 */
function lib_init(): bool
{
    global $nebuleLocalAuthorities, $libraryCheckOK, $libraryRescueMode;

    // Initialize i/o.
    if (!io_open()) {
        bootstrap_setBreak('81', 'lib init : I/O open error');
        return false;
    }

    // Pour la suite, seul le puppetmaster est enregistré.
    // Une fois les autres entités trouvées, ajoute les autres autorités.
    // Cela empêche qu'une entité compromise ne génère un lien qui passerait avant le puppetmaster
    //   dans la recherche par référence nebFindByRef.
    $puppetmaster = lib_getConfiguration('puppetmaster');
    if (!ent_checkPuppetmaster($puppetmaster)) {
        bootstrap_setBreak('82', 'lib init : puppetmaster error');
        return false;
    }
    $nebuleLocalAuthorities = array($puppetmaster);

    // Search and check global authorities.
    $nebuleSecurityAuthorities = ent_getSecurityAuthorities(false);
    if (!ent_checkSecurityAuthorities($nebuleSecurityAuthorities)) {
        $nebuleSecurityAuthorities = ent_getSecurityAuthorities(true);
        if (!ent_checkSecurityAuthorities($nebuleSecurityAuthorities)) {
            bootstrap_setBreak('83', 'lib init : security authority error');
            return false;
        }
    }
    foreach ($nebuleSecurityAuthorities as $authority)
        $nebuleLocalAuthorities[] = $authority;

    $nebuleCodeAuthorities = ent_getCodeAuthorities(false);
    if (!ent_checkCodeAuthorities($nebuleCodeAuthorities)) {
        $nebuleCodeAuthorities = ent_getCodeAuthorities(true);
        if (!ent_checkCodeAuthorities($nebuleCodeAuthorities)) {
            bootstrap_setBreak('84', 'lib init : code authority error');
            return false;
        }
    }
    foreach ($nebuleCodeAuthorities as $authority)
        $nebuleLocalAuthorities[] = $authority;

    $nebuleTimeAuthorities = ent_getTimeAuthorities(false);
    if (!ent_checkTimeAuthorities($nebuleTimeAuthorities)) {
        $nebuleTimeAuthorities = ent_getTimeAuthorities(true);
        if (!ent_checkTimeAuthorities($nebuleTimeAuthorities)) {
            bootstrap_setBreak('85', 'lib init : time authority error');
            return false;
        }
    }

    $nebuleDirectoryAuthorities = ent_getDirectoryAuthorities(false);
    if (!ent_checkDirectoryAuthorities($nebuleDirectoryAuthorities)) {
        $nebuleDirectoryAuthorities = ent_getDirectoryAuthorities(true);
        if (!ent_checkDirectoryAuthorities($nebuleDirectoryAuthorities)) {
            bootstrap_setBreak('86', 'lib init : directory authority error');
            return false;
        }
    }

    $libraryRescueMode = lib_getModeRescue();
    if ($libraryRescueMode)
        log_add('lib init : rescue mode activated', 'warn', __FUNCTION__, 'ad7056e9');

    lib_setServerEntity($libraryRescueMode);
    lib_setDefaultEntity($libraryRescueMode);
    lib_setPublicEntity();

    $libraryCheckOK = true;
    return true;
}

/**
 * Get and check local server entity.
 * If not found, use puppetmaster for temporary replacement.
 *
 * @param bool $rescueMode
 * @return void
 */
function lib_setServerEntity(bool $rescueMode): void
{
    global $nebuleServerEntity, $nebuleLocalAuthorities, $needFirstSynchronization;

    if (file_exists(LIB_LOCAL_ENTITY_FILE) && is_file(LIB_LOCAL_ENTITY_FILE))
    {
        $nebuleServerEntity = filter_var(strtok(trim(file_get_contents(LIB_LOCAL_ENTITY_FILE, false, null, 0, LIB_NID_MAX_HASH_SIZE)), "\n"), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (!ent_checkIsPublicKey($nebuleServerEntity))
        {
            bootstrap_setBreak('62', 'Local server entity error');
            $nebuleServerEntity = '';
            $needFirstSynchronization = true;
        }
    } else {
        bootstrap_setBreak('61', 'No local server entity');
        $nebuleServerEntity = '';
        $needFirstSynchronization = true;
    }

    if ($nebuleServerEntity == '')
        $nebuleServerEntity = lib_getConfiguration('puppetmaster');

    if (lib_getConfiguration('permitInstanceEntityAsAuthority') && !$rescueMode)
        $nebuleLocalAuthorities[] = $nebuleServerEntity;
}

/**
 * Get and check default entity.
 *
 * @param bool $rescueMode
 * @return void
 */
function lib_setDefaultEntity(bool $rescueMode): void
{
    global $nebuleDefaultEntity, $nebuleLocalAuthorities;
    $nebuleDefaultEntity = lib_getConfiguration('defaultCurrentEntity');
    if (!ent_checkIsPublicKey($nebuleDefaultEntity))
        $nebuleDefaultEntity = lib_getConfiguration('puppetmaster');

    if (lib_getConfiguration('permitDefaultEntityAsAuthority') && !$rescueMode)
        $nebuleLocalAuthorities[] = $nebuleDefaultEntity;
}

/**
 * Get and check public entity.
 *
 * @return void
 */
function lib_setPublicEntity(): void
{
    global $nebulePublicEntity, $nebuleDefaultEntity;
    if (!ent_checkIsPublicKey($nebulePublicEntity))
        $nebulePublicEntity = $nebuleDefaultEntity;
}

/**
 * Check rescue mode asked and authorized.
 * Can be activated by option modeRescue.
 * Can be activated by line argument if permitted by option permitOnlineRescue.
 * This rescue mode is useful when the code loaded crash.
 * By default, rescue mode is not activated.
 *
 * @return bool
 */
function lib_getModeRescue(): bool
{
    if (lib_getConfiguration('modeRescue') === true
        || (lib_getConfiguration('permitOnlineRescue') === true
            && (filter_has_var(INPUT_GET, LIB_ARG_RESCUE_MODE)
                || filter_has_var(INPUT_POST, LIB_ARG_RESCUE_MODE)
            )
        )
    )
        return true;
    return false;
}



/**
 * I/O - Start I/O subsystem with checks.
 *
 * @return boolean
 */
function io_open(): bool
{
    if (!io_checkLinkFolder() || !io_checkObjectFolder())
        return false;
    return true;
}

/**
 * I/O - Check folder status and writeability for links.
 *
 * @return boolean
 */
function io_checkLinkFolder(): bool
{
    // Check if exist.
    if (!file_exists(LIB_LOCAL_LINKS_FOLDER))
        io_createLinkFolder();
    if (!file_exists(LIB_LOCAL_LINKS_FOLDER) || !is_dir(LIB_LOCAL_LINKS_FOLDER)) {
        log_add('I/O no folder for links.', 'error', __FUNCTION__, '5306de5f');
        bootstrap_setBreak('22', "Library i/o link's folder error");
        return false;
    }

    // Check writeability.
    if (lib_getConfiguration('permitWrite') && lib_getConfiguration('permitWriteLink')) {
        $data = crypto_getPseudoRandom(2048);
        $name = LIB_LOCAL_LINKS_FOLDER . '/writest' . bin2hex(crypto_getPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            log_add('I/O error on folder for links.', 'error', __FUNCTION__, 'f72e3a86');
            bootstrap_setBreak('23', "Library i/o link's folder error");
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            log_add('I/O error on folder for links.', 'error', __FUNCTION__, '6f012d85');
            bootstrap_setBreak('23', "Library i/o link's folder error");
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            log_add('I/O error on folder for links.', 'error', __FUNCTION__, 'fd499fcb');
            bootstrap_setBreak('23', "Library i/o link's folder error");
            return false;
        }
        if (!unlink($name)) {
            log_add('I/O error on folder for links.', 'error', __FUNCTION__, '8e0caa66');
            bootstrap_setBreak('23', "Library i/o link's folder error");
            return false;
        }
    }

    return true;
}

/**
 * I/O - Check folder status and writeability for objects.
 *
 * @return boolean
 */
function io_checkObjectFolder(): bool
{
    // Check if exist.
    if (!file_exists(LIB_LOCAL_OBJECTS_FOLDER))
        io_createObjectFolder();
    if (!file_exists(LIB_LOCAL_OBJECTS_FOLDER) || !is_dir(LIB_LOCAL_OBJECTS_FOLDER)) {
        log_add('I/O no folder for objects.', 'error', __FUNCTION__, 'b0cdeafe');
        bootstrap_setBreak('24', "Library i/o object's folder error");
        return false;
    }

    // Check writeability.
    if (lib_getConfiguration('permitWrite') && lib_getConfiguration('permitWriteObject')) {
        $data = crypto_getPseudoRandom(2048);
        $name = LIB_LOCAL_OBJECTS_FOLDER . '/writest' . bin2hex(crypto_getPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            log_add('I/O error on folder for objects.', 'error', __FUNCTION__, '1327da69');
            bootstrap_setBreak('25', "Library i/o object's folder error");
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            log_add('I/O error on folder for objects.', 'error', __FUNCTION__, '2b451a2a');
            bootstrap_setBreak('25', "Library i/o object's folder error");
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            log_add('I/O error on folder for objects.', 'error', __FUNCTION__, '634072e5');
            bootstrap_setBreak('25', "Library i/o object's folder error");
            return false;
        }
        if (!unlink($name)) {
            log_add('I/O error on folder for objects.', 'error', __FUNCTION__, '2b397869');
            bootstrap_setBreak('25', "Library i/o object's folder error");
            return false;
        }
    }

    return true;
}

/**
 * I/O - Try to create folder for links.
 *
 * @return boolean
 */
function io_createLinkFolder(): bool
{
    if (lib_getConfiguration('permitWrite')
        && lib_getConfiguration('permitWriteLink')
        && !file_exists(LIB_LOCAL_LINKS_FOLDER)
    )
        return mkdir(LIB_LOCAL_LINKS_FOLDER);

    return false;
}

/**
 * I/O - Try to create folder for objects.
 *
 * @return boolean
 */
function io_createObjectFolder(): bool
{
    if (lib_getConfiguration('permitWrite')
        && lib_getConfiguration('permitWriteObject')
        && !file_exists(LIB_LOCAL_OBJECTS_FOLDER)
    )
        return mkdir(LIB_LOCAL_OBJECTS_FOLDER);

    return false;
}

/**
 * I/O - Check if node link's file is present, which mean node have one or more links.
 *
 * @param string $nid
 * @return boolean
 */
function io_checkNodeHaveLink(string $nid): bool
{
    if (file_exists(LIB_LOCAL_LINKS_FOLDER . '/' . $nid))
        return true;
    return false;
}

/**
 * I/O - Check if node object's content is present, which mean node is an object with a content.
 *
 * @param string $nid
 * @return boolean
 */
function io_checkNodeHaveContent(string $nid): bool
{
    if (file_exists(LIB_LOCAL_OBJECTS_FOLDER . '/' . $nid))
        return true;
    return false;
}

/**
 * I/O - Read object's links.
 * Return array of links, one string per link, maybe empty.
 *
 * @param string  $nid
 * @param array   $lines
 * @param integer $maxLinks
 * @return array
 */
function io_linksRead(string $nid, array &$lines, int $maxLinks = 0): array
{
    $count = 0;

    if (!nod_checkNID($nid) || !io_checkNodeHaveLink($nid))
        return $lines;
    if ($maxLinks == 0)
        $maxLinks = lib_getConfiguration('ioReadMaxLinks');

    $links = file(LIB_LOCAL_LINKS_FOLDER . '/' . $nid);
    if ($links !== false) {
        foreach ($links as $link) {
            $lines [$count] = $link;
            lib_incrementMetrology('lr');
            $count++;
            if ($count > $maxLinks)
                break 1;
        }
    }

    return $lines;
}

/**
 * I/O - Write a link to a node.
 *
 * @param string $nid
 * @param string $link
 * @return boolean
 */
function io_linkWrite(string $nid, string &$link): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteLink')
        || $nid == ''
    )
        return false;

    // Check if link not already present on file.
    if (file_exists(LIB_LOCAL_LINKS_FOLDER . '/' . $nid)) {
        $l = file(LIB_LOCAL_LINKS_FOLDER . '/' . $nid, FILE_SKIP_EMPTY_LINES);
        if ($l !== false) {
            foreach ($l as $k) {
                if (trim($k) == trim($link))
                    return true;
            }
        }
    }

    // Write link on file.
    if (file_put_contents(LIB_LOCAL_LINKS_FOLDER . '/' . $nid, "$link\n", FILE_APPEND) === false)
        return false;
    return true;
}

/**
 * I/O - Synchronize links from other location.
 *
 * @param string $nid
 * @param string $location
 * @return bool
 */
function io_linkSynchronize(string $nid, string $location): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteLink')
        || !lib_getConfiguration('permitSynchronizeLink')
        || !nod_checkNID($nid)
        || $location == ''
        //|| !is_string($location) // TODO renforcer la vérification de l'URL.
        || nod_checkBanned_FIXME($nid)
    )
        return false;

    if (!io_checkExistOverHTTP($location . '/l/' . $nid))
        return false;

    $ressource = fopen($location . '/l/' . $nid, 'r');
    if ($ressource !== false) {
        while (feof($ressource) !== false) {
            $line = trim(fgets($ressource));
            lnk_write($line);
        }
        fclose($ressource);
    }

    return true;
}

/**
 * I/O - Read object content.
 * Return the read data from object.
 *
 * @param string  $nid
 * @param integer $maxData
 * @return string
 */
function io_objectRead(string $nid, int $maxData = 0): string
{
    if ($maxData == 0)
        $maxData = lib_getConfiguration('ioReadMaxData');
    if (!nod_checkNID($nid) || !io_checkNodeHaveContent($nid))
        return '';

    $result = file_get_contents(LIB_LOCAL_OBJECTS_FOLDER . '/' . $nid, false, null, 0, $maxData);
    if ($result === false)
        $result = '';
    lib_incrementMetrology('or');

    return $result;
}

/**
 * I/O - Write object content.
 * This function do not verify data and OID correlation.
 *
 * @param string $data
 * @param string $oid
 * @return boolean
 */
function io_objectWrite(string &$data, string $oid = '0'): bool
{
    if (strlen($data) == 0
        || !lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
    )
        return false;

    if (strlen($oid) < LIB_NID_MIN_HASH_SIZE)
        return false;

    if (io_checkNodeHaveContent($oid))
        return true;

    if (file_put_contents(LIB_LOCAL_OBJECTS_FOLDER . '/' . $oid, $data) === false)
        return false;
    return true;
}

/**
 * I/O - Synchronize object content from other location.
 *
 * @param string $nid
 * @param string $location
 * @return bool
 */
function io_objectSynchronize(string $nid, string $location): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
        || !lib_getConfiguration('permitSynchronizeObject')
        || !nod_checkNID($nid)
        || $location == ''
        //|| !is_string($location) // TODO renforcer la vérification de l'URL.
        || nod_checkBanned_FIXME($nid)
    )
        return false;

    if (io_checkNodeHaveContent($nid))
        return true;

    if (!io_checkExistOverHTTP($location . '/o/' . $nid))
        return false;

    // Téléchargement de l'objet via un fichier temporaire.
    $tmpId = bin2hex(crypto_getPseudoRandom(8));
    $tmpIdName = 'io_objectSynchronize' . $tmpId . '-' . $nid;
    $distobj = fopen($location . '/o/' . $nid, 'r');
    if ($distobj) {
        $localobj = fopen(LIB_LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName, 'w');
        if ($localobj) {
            while (($line = fgets($distobj, lib_getConfiguration('ioReadMaxData'))) !== false)
                fputs($localobj, $line);
            fclose($localobj);
            $algo = nod_getAlgo($nid);
            if ($algo != '')
                $hash = crypto_getFileHash($tmpIdName, crypto_getTranslatedHashAlgo($algo));
            else
                $hash = 'invalid';

            if ($hash . '.' . $algo == $nid)
                rename(LIB_LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName, LIB_LOCAL_OBJECTS_FOLDER . '/' . $nid);
            else
                unlink(LIB_LOCAL_OBJECTS_FOLDER . '/' . $tmpIdName);
        }
        fclose($distobj);
    }

    if (io_checkNodeHaveContent($nid))
        return true;
    return false;
}

/**
 * I/O - Suppress object content.
 *
 * @param string $nid
 * @return boolean
 */
function io_objectDelete(string $nid): bool
{
    if (!lib_getConfiguration('permitWrite') || !lib_getConfiguration('permitWriteObject') || $nid == '')
        return false;
    if (!io_checkNodeHaveContent($nid))
        return true;

    if (!unlink(LIB_LOCAL_OBJECTS_FOLDER . '/' . $nid)) {
        log_add('Unable to delete file.', 'error', __FUNCTION__, '991b11a1');
        return false;
    }
    return true;
}

/**
 * Vérifie la présence d'un fichier ou dossier via HTTP.
 *
 * @param string $location
 * @return boolean
 */
function io_checkExistOverHTTP(string $location): bool
{
    $url = parse_url($location);

    $handle = fsockopen($url['host'], 80, $errno, $errstr, 1);
    if ($handle === false)
        return false;

    $out = "HEAD " . $url['path'] . " HTTP/1.1\r\n" . "Host: " . $url['host'] . "\r\n" . "Connection: Close\r\n\r\n";
    $response = '';

    fwrite($handle, $out);
    while (!feof($handle)) {
        $response .= fgets($handle, 20);
        if (strlen($response) > 0)
            break;
    }
    fclose($handle);

    $pos = strpos($response, ' ');
    if ($pos === false)
        return false;

    $code = substr($response, $pos + 1, 3);
    if ($code == '200')
        return true;

    return false;
}

/**
 * I/O - Include code from object.
 *
 * @param string $nid
 * @return boolean
 */
function io_objectInclude(string $nid): bool
{
    /** @noinspection PhpUnusedLocalVariableInspection */
    global $loggerSessionID, // Used by lib include.
           $metrologyStartTime, // Used by lib include.
           $nebuleName, // Used by lib include.
           $nebuleSurname, // Used by lib include.
           $nebuleDescription, // Used by lib include.
           $nebuleAuthor, // Used by lib include.
           $nebuleLibVersion, // Used by lib include.
           $nebuleLicence, // Used by lib include.
           $nebuleWebsite, // Used by lib include.
           $applicationName, // Used by lib include.
           $applicationSurname, // Used by lib include.
           $applicationDescription, // Used by lib include.
           $applicationVersion, // Used by lib include.
           $applicationLicence, // Used by lib include.
           $applicationAuthor, // Used by lib include.
           $applicationWebsite; // Used by lib include.

    if (!nod_checkNID($nid)
        || !io_checkNodeHaveContent($nid)
    )
        return false;

    $result = true;
    try {
        include_once(LIB_LOCAL_OBJECTS_FOLDER . '/' . $nid);
    } catch (\Error $e) {
        log_add('error include code NID=' . $nid .' ('  . $e->getCode() . ') : ' . $e->getFile()
            . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
            . $e->getTraceAsString(), 'error', __FUNCTION__, 'fa2f570a');
        $result = false;
    }
    return $result;
}

/**
 * I/O - End of work on I/O subsystem.
 *
 * @return void
 */
function io_close(): void
{
    // Nothing to do for local filesystem.
}

/**
 * Crypto - Translate algo name into OpenSSL algo name.
 *
 * @param string $algo
 * @param bool   $loop
 * @return string
 */
function crypto_getTranslatedHashAlgo(string $algo, bool $loop = true): string
{
    if ($algo == '')
        $algo = lib_getConfiguration('cryptoHashAlgorithm');

    $translatedAlgo = '';
    switch ($algo) {
        case 'sha2.128' :
            $translatedAlgo = 'sha128';
            break;
        case 'sha2.256' :
            $translatedAlgo = 'sha256';
            break;
        case 'sha2.384' :
            $translatedAlgo = 'sha384';
            break;
        case 'sha2.512' :
            $translatedAlgo = 'sha512';
            break;
    }

    if ($translatedAlgo == '') {
        if ($loop) {
            log_add('cryptoHashAlgorithm configuration have an unknown value (' . $algo . ')', 'error', __FUNCTION__, 'b7627066');
            $translatedAlgo = crypto_getTranslatedHashAlgo(LIB_CONFIGURATIONS_DEFAULT['cryptoHashAlgorithm'], false);
        } else
            $translatedAlgo = 'sha512';
    }

    return $translatedAlgo;
}

/**
 * Crypto - Calculate hash of data with algo.
 * Use OpenSSL library.
 *
 * @param string $algo
 * @param string $data
 * @return string
 */
function crypto_getDataHash(string &$data, string $algo = ''): string
{
    return hash(crypto_getTranslatedHashAlgo($algo), $data);
}

/**
 * Crypto - Calculate hash of file data with algo.
 * File must be on objects folder.
 * Use OpenSSL library.
 *
 * @param string $algo
 * @param string $file
 * @return string
 */
function crypto_getFileHash(string $file, string $algo = ''): string
{
    return hash_file(crypto_getTranslatedHashAlgo($algo), LIB_LOCAL_OBJECTS_FOLDER . '/' . $file);
}

/**
 * Crypto - Generate pseudo random number
 * Use OpenSSL library.
 *
 * @param int $count
 * @return string
 */
function crypto_getPseudoRandom(int $count = 32): string
{
    global $nebuleServerEntity;

    $result = '';
    $algo = 'sha256';
    if ($count == 0 || !is_int($count))
        return $result;

    // Génère une graine avec la date pour le compteur interne.
    $intcount = date(DATE_ATOM) . microtime(false) . BOOTSTRAP_VERSION . $nebuleServerEntity;

    // Boucle de remplissage.
    while (strlen($result) < $count) {
        $diffsize = $count - strlen($result);

        // Fait évoluer le compteur interne.
        $intcount = hash($algo, $intcount);

        // Fait diverger le compteur interne pour la sortie.
        // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
        $outvalue = hash($algo, $intcount . 'liberté égalité fraternité', true);

        // Tronc au besoin la taille de la sortie.
        if (strlen($outvalue) > $diffsize)
            $outvalue = substr($outvalue, 0, $diffsize);

        // Ajoute la sortie au résultat final.
        $result .= $outvalue;
    }

    return $result;
}

/**
 * Crypto - Generate new public cryptographic keys.
 * Use OpenSSL library.
 *
 * @param string $asymmetricAlgo
 * @param string $hashAlgo
 * @param string $publicKey
 * @param string $privateKey
 * @param string $password
 * @return bool
 */
function crypto_getNewPKey(string $asymmetricAlgo, string $hashAlgo, string &$publicKey, string &$privateKey, string $password): bool
{
    // Prepare values.
    $digestAlgo = crypto_getTranslatedHashAlgo($hashAlgo, true);
    $config = array('digest_alg' => $digestAlgo,);
    switch ($asymmetricAlgo) {
        case 'rsa.1024' :
            $config['private_key_bits'] = 1024;
            $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
            break;
        case 'rsa.2048' :
            $config['private_key_bits'] = 2048;
            $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
            break;
        case 'rsa.3192' :
            $config['private_key_bits'] = 3192;
            $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
            break;
        case 'rsa.4096' :
            $config['private_key_bits'] = 4096;
            $config['private_key_type'] = OPENSSL_KEYTYPE_RSA;
            break;
        case 'dsa.1024' :
            $config['private_key_bits'] = 1024;
            $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
            break;
        case 'dsa.2048' :
            $config['private_key_bits'] = 2048;
            $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
            break;
        case 'dsa.3192' :
            $config['private_key_bits'] = 3192;
            $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
            break;
        case 'dsa.4096' :
            $config['private_key_bits'] = 4096;
            $config['private_key_type'] = OPENSSL_KEYTYPE_DSA;
            break;
        case 'ec.prime256v1' :
            $config['curve_name'] = 'prime256v1';
            $config['private_key_type'] = OPENSSL_KEYTYPE_EC;
            break;
        default:
            return false;
    }

    // Generate the bi-key.
    $newPKey = openssl_pkey_new($config);
    if ($newPKey === false)
        return false;

    // Extract public key.
    $publicKey = openssl_pkey_get_details($newPKey);
    $publicKey = $publicKey ['key'];

    // Extract private key.
    if ($password != '')
        openssl_pkey_export($newPKey, $privateKey, $password);
    else
        openssl_pkey_export($newPKey, $privateKey);
    // Verify.
    $private_key = openssl_pkey_get_private($privateKey, $password);
    if ($private_key === false)
        return false;

    return true;
}

/**
 * Crypto - Encrypt data with private asymmetric key.
 * Use OpenSSL library.
 *
 * @param string $data
 * @param string $privateOid
 * @param string $password
 * @param bool   $entityCheck
 * @return string
 */
function crypto_asymmetricEncrypt(string $data, string $privateOid = '', string $password = '', bool $entityCheck = true): string
{
    if ($privateOid == ''
        || ($entityCheck && !ent_checkIsPrivateKey($privateOid))
        || $password == ''
        || $data == ''
    )
        return '';

    $privateCertificat = '';
    if (!obj_getLocalContent($privateOid, $privateCertificat, 10000))
        return '';
    $private_key = openssl_pkey_get_private($privateCertificat, $password);
    if ($private_key === false)
        return '';
    $binarySignature = '';
    $hashData = crypto_getDataHash($data);
    $binHashData = pack("H*", $hashData);
    $ok = openssl_private_encrypt($binHashData, $binarySignature, $private_key, OPENSSL_PKCS1_PADDING);
    //openssl_free_key($private_key);
    unset($private_key);
    if ($ok === false)
        return '';

    return bin2hex($binarySignature);
}


/**
 * Crypto - Decrypt and verify asymmetric sign.
 * Use OpenSSL library.
 *
 * @param string $sign
 * @param string $hash
 * @param string $nid
 * @return boolean
 */
function crypto_asymmetricVerify(string $sign, string $hash, string $nid): bool
{
    // Read signer's public key.
    $cert = io_objectRead($nid, 10000);
    $hashsize = strlen($hash);

    $pubkeyid = openssl_pkey_get_public($cert);
    if ($pubkeyid === false)
        return false;

    lib_incrementMetrology('lv');

    // Encoding sign before check.
    $binsign = pack('H*', $sign);

    // Decode sign with public key.
    if (openssl_public_decrypt($binsign, $bindecrypted, $pubkeyid, OPENSSL_PKCS1_PADDING)) {
        $decrypted = (substr(bin2hex($bindecrypted), -$hashsize, $hashsize));
        //log_add('decrypt RSA ' . $decrypted . '/' . $hash, 'error', __FUNCTION__, 'd4c712ea');
        if ($decrypted == $hash)
            return true;
    }

    return false;
}



/**
 * Link - Generate a new link
 * Use OpenSSL library.
 *
 * @param string $rc
 * @param string $req
 * @param string $nid1
 * @param string $nid2
 * @param string $nid3
 * @param string $nid4
 * @return string
 */
function lnk_generate(string $rc, string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): string
{
    if ($req == ''
        || !nod_checkNID($nid1)
        || !nod_checkNID($nid2, true)
        || !nod_checkNID($nid3, true)
        || !nod_checkNID($nid4, true)
    )
        return '';

    $bh = 'nebule:link/' . LIB_LINK_VERSION;

    if ($rc == '' || !lnk_checkRC($rc))
        $rc = '0>0' . date('YmdHis');

    $rl = $req . '>' . $nid1;
    if ($nid2 != '' && $nid2 != '0')
        $rl .= '>' . $nid2;
    if ($nid3 != '' && $nid3 != '0')
        $rl .= '>' . $nid3;
    if ($nid4 != '' && $nid4 != '0')
        $rl .= '>' . $nid4;
    $bl = $rc . '/' . $rl;

    return $bh . '_' . $bl;
}

/**
 * Link - Generate a valid sign for a link.
 *
 * @param string $bh_bl
 * @return string
 */
function lnk_sign(string $bh_bl): string
{
    global $nebulePublicEntity, $nebulePrivateEntity, $nebulePasswordEntity;

    if ($bh_bl == '')
        return '';
    log_add('MARK sign BH_BL='.$bh_bl, 'normal', __FUNCTION__, '00000003');

    if (!ent_checkIsPublicKey($nebulePublicEntity)) {
        log_add('invalid current entity (public) ' . $nebulePublicEntity, 'error', __FUNCTION__, '70e110d7');
        return '';
    }
    if (!ent_checkIsPrivateKey($nebulePrivateEntity)) {
        log_add('invalid current entity (private) ' . $nebulePrivateEntity, 'error', __FUNCTION__, 'ca23fd57');
        return '';
    }
    if ($nebulePasswordEntity == '') {
        log_add('invalid current entity (password)', 'error', __FUNCTION__, '331e1fab');
        return '';
    }

    $sign = crypto_asymmetricEncrypt($bh_bl, $nebulePrivateEntity, $nebulePasswordEntity, true);
    if ($sign == '')
        return '';

    $bs = $nebulePublicEntity . '>' . $sign . '.' . lib_getConfiguration('cryptoHashAlgorithm');
    return $bh_bl . '_' . $bs;
}

/**
 * Link - Generate and sign a new link
 * Use OpenSSL library.
 *
 * @param string $rc
 * @param string $req
 * @param string $nid1
 * @param string $nid2
 * @param string $nid3
 * @param string $nid4
 * @return string
 */
function lnk_generateSign(string $rc, string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): string
{
    global $nebulePublicEntity;

    $bh_bl = lnk_generate($rc, $req, $nid1, $nid2, $nid3, $nid4);
    if ($bh_bl == '')
        return '';

    $sign = lnk_sign($bh_bl);
    if ($sign == '')
        return '';

    $bs = $nebulePublicEntity . '>' . $sign . lib_getConfiguration('cryptoHashAlgorithm');
    return $bh_bl . '_' . $bs;
}

/**
 * Link - Liste et filtre les liens sur des actions et objets dans un ordre déterminé.
 *  - $object objet dont les liens sont à lire.
 *  - $table table dans laquelle seront retournés les liens.
 *  - $action filtre sur l'action.
 *  - $srcobj filtre sur un objet source.
 *  - $dstobj filtre sur un objet destination.
 *  - $metobj filtre sur un objet meta.
 *  - $withinvalid optionnel pour autoriser la lecture des liens invalides.
 * Les liens sont triés par ordre chronologique et les liens marqués comme supprimés sont retirés de la liste.
 * Version inclusive, càd liens x valable uniquement sur les liens du même signataire.
 *
 * @param string  $nid
 * @param array   $table
 * @param string  $action
 * @param string  $srcobj
 * @param string  $dstobj
 * @param string  $metobj
 * @param boolean $withinvalid
 * @return void
 */
function lnk_findInclusive_FIXME(&$nid, &$table, $action, $srcobj, $dstobj, $metobj, $withinvalid = false): void
{
    $followXOnSameDate = true; // TODO à supprimer.

    $linkDate = array();
    $tmpArray = array();
    $i1 = 0;

    lnk_getListFilterNid($nid, $tmpArray, $metobj, $action, $withinvalid);

    foreach ($tmpArray as $n => $t) {
        $linkDate [$n] = $t [3];
    }

    // Tri par date.
    array_multisort($linkDate, SORT_STRING, SORT_ASC, $tmpArray);

    foreach ($tmpArray as $tline) {
        if ($tline [4] == 'x') {
            continue 1; // Suppression de l'affichage des liens x.
        }
        if ($action != '' && $tline [4] != $action) {
            continue 1;
        }
        if ($srcobj != '' && $tline [5] != $srcobj) {
            continue 1;
        }
        if ($dstobj != '' && $tline [6] != $dstobj) {
            continue 1;
        }
        if ($metobj != '' && $tline [7] != $metobj) {
            continue 1;
        }

        // Filtre du lien.
        foreach ($tmpArray as $vline) {
            if ($vline [4] == 'x'
                && $tline [4] != 'x'
                && $tline [5] == $vline [5]
                && $tline [6] == $vline [6]
                && $tline [7] == $vline [7]
                && $vline [2] == $tline [2]
                && ($vline [9] == 1
                    || $vline [9] == -1
                )
                && (($followXOnSameDate
                        && strtotime($tline [3]) < strtotime($vline [3])
                    )
                    || strtotime($tline [3]) <= strtotime($vline [3])
                )
            ) {
                continue 2;
            }
        }

        // Suppression de l'affichage des liens en double, même à des dates différentes.
        foreach ($table as $vline) {
            if ($tline [2] == $vline [2]
                && $tline [4] == $vline [4]
                && $tline [5] == $vline [5]
                && $tline [6] == $vline [6]
                && $tline [7] == $vline [7]
            ) {
                continue 2;
            }
        }
        // Remplissage de la table des résultats.
        $table [$i1] [0] = $tline [0];
        $table [$i1] [1] = $tline [1];
        $table [$i1] [2] = $tline [2];
        $table [$i1] [3] = $tline [3];
        $table [$i1] [4] = $tline [4];
        $table [$i1] [5] = $tline [5];
        $table [$i1] [6] = $tline [6];
        $table [$i1] [7] = $tline [7];
        $table [$i1] [8] = $tline [8];
        $table [$i1] [9] = $tline [9];
        $table [$i1] [10] = $tline [10];
        $table [$i1] [11] = $tline [11];
        $table [$i1] [12] = $tline [12];
        $i1++;
    }
    unset($linkDate, $i1, $n, $t, $tline);
}

/**
 * Link - Read links, parse and filter each links.
 *
 * @param string $nid
 * @param array  $links
 * @param array  $filter
 * @param bool   $withInvalidLinks
 * @param string $addSigner
 * @return void
 */
function lnk_getList(string $nid, array &$links, array $filter, bool $withInvalidLinks = false, string $addSigner = ''): void
{
    global $nebuleLocalAuthorities;

    if ($nid == '0' || !io_checkNodeHaveLink($nid))
        return;

    // TODO as _lnkGetListFilterNid()
    // If not permitted, do not list invalid links.
    if (!lib_getConfiguration('permitListInvalidLinks'))
        $withInvalidLinks = false;

    $lines = array();
    io_linksRead($nid, $lines);
    foreach ($lines as $line) {
        if ($withInvalidLinks || lnk_verify($line)) {
            $link = lnk_parse($line);
            if (lnk_checkNotSuppressed($link, $lines) && lnk_filterStructure($link, $filter))
                $links [] = $link;
if (lnk_checkNotSuppressed($link, $lines) && lnk_filterStructure($link, $filter))
    log_add('MARK nid='.$nid.' nid1='.$link['bl/rl/nid1'].' nid2='.$link['bl/rl/nid2'].' $nid3='.$link['bl/rl/nid3'], 'normal', __FUNCTION__, '00000002');
        }
    }

    $validSigners = $nebuleLocalAuthorities;
    if ($addSigner != '' && nod_checkNID($addSigner, false))
        $validSigners[] = $addSigner;

    // Social filter.
    if (!$withInvalidLinks)
        lnk_filterBySigners($links, $validSigners);
log_add('MARK nid='.$nid.' size='.sizeof($links), 'normal', __FUNCTION__, '00000004');
}

function lnk_checkExist(string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): bool
{

    $links = array();
    $filter = array(
        'bl/rl/req' => $req,
        'bl/rl/nid1' => $nid1,
        'bl/rl/nid2' => $nid2,
        'bl/rl/nid3' => $nid3,
        'bl/rl/nid4' => $nid4,
    );
    lnk_getList($nid1, $links, $filter);

    if (sizeof($links) == 0)
        return false;
    return true;
}

/**
 * Link - Test if link have been marked as suppressed with a link type x.
 *
 * @param array $link
 * @param array $lines
 * @return bool
 */
function lnk_checkNotSuppressed(array &$link, array &$lines): bool
{
    foreach ($lines as $line) {
        if (strpos($line, '/x>') === false)
            continue;
        if (lnk_verify($line)) {
            $linkCompare = lnk_parse($line);
            if ($linkCompare['bl/rl/req'] == 'x'
                && lnk_dateCompare($link['bl/rc/mod'], $link['bl/rc/chr'], $linkCompare['bl/rc/mod'], $linkCompare['bl/rc/chr']) < 0
            )
                return false;
        }
    }
    return true;
}

/**
 * Link - Compare if date 1 is lower, greater or equal to date 2.
 * Return -1 if lower, +1 if greater and 0 if equal.
 * FIXME Only support date in mode 0!
 *
 * @param string $mod1
 * @param string $chr1
 * @param string $mod2
 * @param string $chr2
 * @return int
 */
function lnk_dateCompare(string $mod1, string $chr1, string $mod2, string $chr2): int
{
    // Convert first date.
    if ($mod1 == '0')
        $numChr1 = (double)$chr1;
    else
        $numChr1 = (int)$chr1;

    // Convert second date.
    if ($mod2 == '0')
        $numChr2 = (double)$chr2;
    else
        $numChr2 = (int)$chr2;

    // Comparing
    if ($numChr1 < $numChr2)
        return -1;
    elseif ($numChr1 > $numChr2)
        return 1;
    else
        return 0;
}

/**
 * Link - Test if a link match a filter.
 * Filtering on have bl/rl/req, bl/rl/nid1, bl/rl/nid2, bl/rl/nid3, bl/rl/nid4, bl/rl/nid*, bs/rs1/eid, or not have.
 * TODO revoir pour les liens de type x...
 *
 * @param array $link
 * @param array $filter
 * @return bool
 */
function lnk_filterStructure(array $link, array $filter): bool
{
log_add('MARK in BL='.$link['bl'], 'normal', __FUNCTION__, '00000006');

    foreach ($filter as $n => $f)
    {
        $a = $f;
        if (is_string($f))
            $a = array($f);
        foreach ($a as $v)
        {
            if (isset($link[$n]) && $link[$n] != $v
                || $v == '' && !isset($link[$n])
            )
                return false;
        }
    }
    return true;





    $ok = false;

    // Positive filtering
    if (isset($filter['bl/rl/req']) && $link['bl/rl/req'] == $filter['bl/rl/req'])
        $ok = true;
    if (isset($filter['bl/rl/nid1']) && $link['bl/rl/nid1'] == $filter['bl/rl/nid1'])
        $ok = true;
    if (isset($filter['bl/rl/nid2']) && isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['bl/rl/nid2'])
        $ok = true;
    if (isset($filter['bl/rl/nid2']) && !isset($link['bl/rl/nid2']) && $filter['bl/rl/nid2'] == '')
        $ok = true;
    if (isset($filter['bl/rl/nid3']) && isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['bl/rl/nid3'])
        $ok = true;
    if (isset($filter['bl/rl/nid3']) && !isset($link['bl/rl/nid3']) && $filter['bl/rl/nid3'] == '')
        $ok = true;
    if (isset($filter['bl/rl/nid4']) && isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['bl/rl/nid4'])
        $ok = true;
    if (isset($filter['bl/rl/nid4']) && !isset($link['bl/rl/nid4']) && $filter['bl/rl/nid4'] == '')
        $ok = true;
    if (isset($filter['bl/rl/nid*']) && ($link['bl/rl/nid1'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['bl/rl/nid*']
            || isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['bl/rl/nid*']
        )
    )
        $ok = true;
    if (isset($filter['bs/rs1/eid']) && $link['bs/rs1/eid'] == $filter['bs/rs1/eid'])
        $ok = true;

    if (!$ok)
        return $ok;

    // Negative filtering
    if (isset($filter['!bl/rl/req']) && $link['bl/rl/req'] == $filter['!bl/rl/req'])
        $ok = false;
    if (isset($filter['!bl/rl/nid1']) && $link['bl/rl/nid1'] == $filter['!bl/rl/nid1'])
        $ok = false;
    if (isset($filter['!bl/rl/nid2']) && isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['!bl/rl/nid2'])
        $ok = false;
    if (isset($filter['!bl/rl/nid3']) && isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['!bl/rl/nid3'])
        $ok = false;
    if (isset($filter['!bl/rl/nid4']) && isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['!bl/rl/nid4'])
        $ok = false;
    if (isset($filter['!bl/rl/nid*']) && ($link['bl/rl/nid1'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid2']) && $link['bl/rl/nid2'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid3']) && $link['bl/rl/nid3'] == $filter['!bl/rl/nid*']
            || isset($link['bl/rl/nid4']) && $link['bl/rl/nid4'] == $filter['!bl/rl/nid*']
        )
    )
        $ok = false;
    if (isset($filter['!bs/rs1/eid']) && $link['bs/rs1/eid'] == $filter['!bs/rs1/eid'])
        $ok = false;

if ($ok) log_add('MARK out ok=true', 'normal', __FUNCTION__, '00000007');
else log_add('MARK out ok=false', 'normal', __FUNCTION__, '00000007');
    return $ok;
}

/**
 * Filter links by signers (BS/RS1/EID) of the links.
 *
 * @param array $links
 * @param array $signers
 */
function lnk_filterBySigners(array &$links, array $signers): void
{
    if (sizeof($links) == 0 || sizeof($signers) == 0)
        return;

    foreach ($links as $i => $link) {
        $ok = false;
        foreach ($signers as $authority) {
            if ($link['bs/rs1/eid'] == $authority)
                $ok = true;
        }
        if (!$ok)
            unset($links[$i]);
    }
}

/**
 * Link - Extract links matching one filter or more (NID1, NID2, NID3 and NID4) and sub-filter on REQ.
 * Return array of parsed links on arg $result (cumulative).
 * TODO à vérifier !
 *
 * @param string $nid
 * @param array  $result
 * @param string $filterOnNid
 * @param string $filterOnReq
 * @param false  $withInvalidLinks
 * @return void
 */
function lnk_getListFilterNid(string $nid, array &$result, string $filterOnNid = '', string $filterOnReq = '', bool $withInvalidLinks = false): void
{
    if (!nod_checkNID($nid) || !io_checkNodeHaveLink($nid))
        return;

    // If not permitted, do not list invalid links.
    if (!lib_getConfiguration('permitListInvalidLinks'))
        $withInvalidLinks = false;

    $lines = array();
    io_linksRead($nid, $lines);
    foreach ($lines as $line) {
        // Verify link.
        if (!$withInvalidLinks && !lnk_verify($line))
            continue;

        $linkParse = lnk_parse($line);

        // Filter and add link on result.
        if (($filterOnReq == '' || $linkParse['bl/rl/req'] == $filterOnReq)
            && ($filterOnNid == ''
                || $linkParse['bl/rl/nid1'] == $filterOnNid
                || $linkParse['bl/rl/nid2'] == $filterOnNid
                || $linkParse['bl/rl/nid3'] == $filterOnNid
                || $linkParse['bl/rl/nid4'] == $filterOnNid
            )
        )
            $result[] = $linkParse;
    }
}

/**
 * Link - Download links on many locations, anywhere.
 *
 * @param string $nid
 * @return void
 */
function lnk_getDistantAnywhere(string $nid): void
{
    if (!lib_getConfiguration('permitSynchronizeLink')
        || nod_checkNID($nid)
    )
        return;

    $links = array();
    $hashType = obj_getNID('nebule/objet/type', lib_getConfiguration('cryptoHashAlgorithm'));
    $hashLocation = obj_getNID('nebule/objet/entite/localisation', lib_getConfiguration('cryptoHashAlgorithm'));
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid2' => $hashLocation,
        'bl/rl/nid3' => $hashType,
        'bl/rl/nid4' => '',
    );
    lnk_getList($hashLocation, $links, $filter);

    $locationList = array();
    foreach ($links as $link)
        $locationList [$link ['bl/rl/nid1']] = $link ['bl/rl/nid1'];

    foreach ($locationList as $location) {
        $url = '';
        if (obj_getLocalContent($location, $url))
            io_linkSynchronize($nid, $url);
    }
}

/**
 * Link - Download node's links on web locations.
 * Only valid links are writen on local filesystem.
 *
 * @param string $nid
 * @param array  $locations
 * @return bool
 */
function lnk_getDistantOnLocations(string $nid, array $locations = array()): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteLink')
        || !lib_getConfiguration('permitSynchronizeLink')
        || !nod_checkNID($nid, false)
        || nod_checkBanned_FIXME($nid)
    )
        return false;

    if (sizeof($locations) == 0)
        $locations = LIB_FIRST_LOCALISATIONS;

    foreach ($locations as $location)
        io_linkSynchronize($nid, $location);
    return true;
}

/**
 * Link - Check block BH on link.
 *
 * @param string $bh
 * @return bool
 */
function lnk_checkBH(string &$bh): bool
{
    if (strlen($bh) > 15) return false;

    $rf = strtok($bh, '/');
    if (is_bool($rf)) return false;
    $rv = strtok('/');
    if (is_bool($rv)) return false;

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RF and RV.
    //if (!lnk_checkRF($rf)) log_add('check link BH/RF failed '.$bh, 'error', __FUNCTION__, '3c0b5c4f');
    if (!lnk_checkRF($rf)) return false;
    //if (!lnk_checkRV($rv)) log_add('check link BH/RV failed '.$bh, 'error', __FUNCTION__, '80c5975c');
    if (!lnk_checkRV($rv)) return false;

    return true;
}

/**
 * Link - Check block RF on link.
 *
 * @param string $rf
 * @return bool
 */
function lnk_checkRF(string &$rf): bool
{
    if (strlen($rf) > 11) return false;

    // Check items from RF : APP:TYP
    $app = strtok($rf, ':');
    if (is_bool($app)) return false;
    if ($app != 'nebule') return false;
    $typ = strtok(':');
    if (is_bool($typ)) return false;
    if ($typ != 'link') return false;

    // Check registry overflow
    if (strtok(':') !== false) return false;

    return true;
}

/**
 * Link - Check block RV on link.
 *
 * @param string $rv
 * @return bool
 */
function lnk_checkRV(string &$rv): bool
{
    if (strlen($rv) > 3) return false;

    // Check items from RV : VER:SUB
    $ver = strtok($rv, ':');
    if (is_bool($ver)) return false;
    $sub = strtok(':');
    if (is_bool($sub)) return false;
    if ("$ver:$sub" != LIB_LINK_VERSION) return false;

    // Check registry overflow
    if (strtok(':') !== false) return false;

    return true;
}

/**
 * Link - Check block BL on link.
 *
 * @param string $bl
 * @return bool
 */
function lnk_checkBL(string &$bl): bool
{
    if (strlen($bl) > 4096) return false; // TODO à revoir.

    $rc = strtok($bl, '/');
    if (is_bool($rc)) return false;
    $rl = strtok('/');
    if (is_bool($rl)) return false;

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RC and RL.
    //if (!lnk_checkRC($rc)) log_add('check link BL/RC failed '.$bl, 'error', __FUNCTION__, '86a58996');
    if (!lnk_checkRC($rc)) return false;
    //if (!lnk_checkRL($rl)) log_add('check link BL/RL failed '.$bl, 'error', __FUNCTION__, 'd865ee87');
    if (!lnk_checkRL($rl)) return false;

    return true;
}

/**
 * Link - Check block RC on link.
 * MOD must be 0 for now.
 * CHR must begin with 0, only contain digits and no more than 15 digits (020211018195523 or 020211018, etc...).
 *
 * @param string $rc
 * @return bool
 */
function lnk_checkRC(string &$rc): bool
{
    if (strlen($rc) > 27) return false;

    // Check items from RC : MOD>CHR
    $mod = strtok($rc, '>');
    if ($mod != '0') return false;
    $chr = strtok('>');
    if (is_bool($chr)) return false;
    if (strlen($chr) > 15) return false;
    if (!ctype_digit($chr)) return false;
    if ($chr[0] != '0') return false;
    // TODO faire un filtrage plus fin...

    // Check registry overflow
    if (strtok('>') !== false) return false;

    return true;
}

/**
 * Link - Check block RL on link.
 *
 * @param string $rl
 * @return bool
 */
function lnk_checkRL(string &$rl): bool
{
    if (strlen($rl) > 4096) return false; // TODO à revoir.

    // Extract items from RL 1 : REQ>NID>NID>NID>NID
    $req = strtok($rl, '>');
    $rl1nid1 = strtok('>');
    if ($rl1nid1 === false) $rl1nid1 = '';
    $rl1nid2 = strtok('>');
    if ($rl1nid2 === false) $rl1nid2 = '';
    $rl1nid3 = strtok('>');
    if ($rl1nid3 === false) $rl1nid3 = '';
    $rl1nid4 = strtok('>');
    if ($rl1nid4 === false) $rl1nid4 = '';

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check REQ, NID1, NID2, NID3 and NID4.
    if (!lnk_checkREQ($req)) return false;
    if (!nod_checkNID($rl1nid1, false)) return false;
    if (!nod_checkNID($rl1nid2, true)) return false;
    if (!nod_checkNID($rl1nid3, true)) return false;
    if (!nod_checkNID($rl1nid4, true)) return false;

    return true;
}

/**
 * Link - Check block REQ on link.
 *
 * @param string $req
 * @return bool
 */
function lnk_checkREQ(string &$req): bool
{
    if ($req != 'l'
        && $req != 'f'
        && $req != 'u'
        && $req != 'd'
        && $req != 'e'
        && $req != 'c'
        && $req != 'k'
        && $req != 's'
        && $req != 'x'
    )
        return false;

    return true;
}

/**
 * Link - Check block BS on link.
 * TODO make a loop on many RS avoid attack on link signs fusion.
 *
 * @param string $bh
 * @param string $bl
 * @param string $bs
 * @return bool
 */
function lnk_checkBS(string &$bh, string &$bl, string &$bs): bool
{
    if (strlen($bs) > 4096) return false; // TODO à revoir.

    $rs = strtok($bs, '/');
    if (is_bool($rs)) return false;

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check content RS 1 NID 1 : hash.algo.size
    //if (!lnk_checkRS($rs, $bh, $bl)) log_add('check link BS/RS failed '.$bs, 'error', __FUNCTION__, '0690f5ac');
    if (!lnk_checkRS($rs, $bh, $bl)) return false;

    return true;
}

/**
 * Link - Check block RS on link.
 *
 * @param string $rs
 * @param string $bh
 * @param string $bl
 * @return bool
 */
function lnk_checkRS(string &$rs, string &$bh, string &$bl): bool
{
    if (strlen($rs) > 4096) return false; // TODO à revoir.

    // Extract items from RS : NID>SIG
    $nid = strtok($rs, '>');
    if (is_bool($nid)) return false;
    $sig = strtok('>');
    if (is_bool($sig)) return false;

    // Check registry overflow
    if (strtok('>') !== false) return false;

    // --- --- --- --- --- --- --- --- ---
    // Check content RS 1 NID 1 : hash.algo.size
    //if (!nod_checkNID($nid, false)) log_add('check link bs/rs1/eid failed '.$rs, 'error', __FUNCTION__, '6e1150f9');
    if (!nod_checkNID($nid, false)) return false;
    //if (!lnk_checkSIG($bh, $bl, $sig, $nid)) log_add('check link BS/RS1/SIG failed '.$rs, 'error', __FUNCTION__, 'e99ec81f');
    if (!lnk_checkSIG($bh, $bl, $sig, $nid)) return false;

    return true;
}

/**
 * Link - Check block SIG on link.
 *
 * @param string $bh
 * @param string $bl
 * @param string $sig
 * @param string $nid
 * @return boolean
 */
function lnk_checkSIG(string &$bh, string &$bl, string &$sig, string &$nid): bool
{
    if (strlen($sig) > 4096) return false; // TODO à revoir.

    // Check hash value.
    $sign = strtok($sig, '.');
    if (is_bool($sign)) return false;
    if (strlen($sign) < LIB_NID_MIN_HASH_SIZE) return false;
    if (strlen($sign) > LIB_NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($sign)) return false;

    // Check algo value.
    $algo = strtok('.');
    if (is_bool($algo)) return false;
    if (strlen($algo) < LIB_NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > LIB_NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    $size = strtok('.');
    if (is_bool($size)) return false;
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < LIB_NID_MIN_HASH_SIZE) return false;
    if ((int)$size > LIB_NID_MAX_HASH_SIZE) return false;
    //if (strlen($sign) != (int)$size) return false; // TODO can't be checked ?

    // Check item overflow
    if (strtok('.') !== false) return false;

    if (!lib_getConfiguration('permitCheckSignOnVerify')) return true;
    if (obj_checkContent($nid)) {
        $data = $bh . '_' . $bl;
        $hash = crypto_getDataHash($data, $algo . '.' . $size);

        return crypto_asymmetricVerify($sign, $hash, $nid);
    }

    return false;
}

/**
 * Link - Verify link consistency.
 * Limites :
 * L : 1 BH + 1 BL + 1 BS
 * BH : 1 RF + 1 RV
 * BL : 1 RC + 1 RL
 * BS : 1 RS
 * RF : 1 APP + 1 TYP
 * APP : 'nebule'
 * TYP : 'link'
 * MOD : '0'
 *
 * @param string $link
 * @return boolean
 */
function lnk_verify(string $link): bool
{
    if (strlen($link) > 4096) return false; // TODO à revoir.
    if (strlen($link) == 0) return false;

    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($link), '_');
    if (is_bool($bh)) return false;
    $bl = strtok('_');
    if (is_bool($bl)) return false;
    $bs = strtok('_');
    if (is_bool($bs)) return false;

    // Check link overflow
    if (strtok('_') !== false) return false;

    // Check BH, BL and BS.
    //if (!lnk_checkBH($bh)) log_add('check link BH failed '.$link, 'error', __FUNCTION__, '80cbba4b');
    if (!lnk_checkBH($bh)) return false;
    //if (!lnk_checkBL($bl)) log_add('check link BL failed '.$link, 'error', __FUNCTION__, 'c5d22fda');
    if (!lnk_checkBL($bl)) return false;
    //if (!lnk_checkBS($bh, $bl, $bs)) log_add('check link BS failed '.$link, 'error', __FUNCTION__, '2828e6ae');
    if (!lnk_checkBS($bh, $bl, $bs)) return false;

    return true;
}

/**
 * Link - Explode link and it's values into array.
 *
 * @param string $link
 * @return array
 */
function lnk_parse(string $link): array
{
    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($link), '_');
    $bl = strtok('_');
    $bs = strtok('_');

    $bh_rf = strtok($bh, '/');
    $bh_rv = strtok('/');

    // Check items from RF : APP:TYP
    $bh_rf_app = strtok($bh_rf, ':');
    $bh_rf_typ = strtok(':');

    // Check items from RV : VER:SUB
    $bh_rv_ver = strtok($bh_rv, ':');
    $bh_rv_sub = strtok(':');

    $bl_rc = strtok($bl, '/');
    $bl_rl = strtok('/');

    // Check items from RC : MOD>CHR
    $bl_rc_mod = strtok($bl_rc, '>');
    $bl_rc_chr = strtok('>');

    // Extract items from RL 1 : REQ>NID>NID>NID>NID
    $bl_rl_req = strtok($bl_rl, '>');
    $bl_rl_nid1 = strtok('>');
    $bl_rl_nid2 = strtok('>');
    if ($bl_rl_nid2 === false) $bl_rl_nid2 = '';
    $bl_rl_nid3 = strtok('>');
    if ($bl_rl_nid3 === false) $bl_rl_nid3 = '';
    $bl_rl_nid4 = strtok('>');
    if ($bl_rl_nid4 === false) $bl_rl_nid4 = '';

    $bs_rs1 = strtok($bs, '/');

    // Extract items from RS : NID>SIG
    $bs_rs1_nid = strtok($bs_rs1, '>');
    $bs_rs1_sig = strtok('>');

    // Check hash value.
    $bs_rs1_sig_sign = strtok($bs_rs1_sig, '.');

    // Check algo value.
    $bs_rs1_sig_algo = strtok('.');

    // Check size value.
    $bs_rs1_sig_size = strtok('.');

    return array(
        'link' => $link, // original link
        'bh' => $bh,
        'bh/rf' => $bh_rf,
        'bh/rf/app' => $bh_rf_app,
        'bh/rf/typ' => $bh_rf_typ,
        'bh/rv' => $bh_rv,
        'bh/rv/ver' => $bh_rv_ver,
        'bh/rv/sub' => $bh_rv_sub,
        'bl' => $bl,
        'bl/rc' => $bl_rc,
        'bl/rc/mod' => $bl_rc_mod,
        'bl/rc/chr' => $bl_rc_chr,
        'bl/rl' => $bl_rl,
        'bl/rl/req' => $bl_rl_req,
        'bl/rl/nid1' => $bl_rl_nid1,
        'bl/rl/nid2' => $bl_rl_nid2,
        'bl/rl/nid3' => $bl_rl_nid3,
        'bl/rl/nid4' => $bl_rl_nid4,
        'bs' => $bs,
        'bs/rs' => $bs_rs1,
        'bs/rs1/eid' => $bs_rs1_nid,
        'bs/rs1/sig' => $bs_rs1_sig,
        'bs/rs1/sig/sign' => $bs_rs1_sig_sign,
        'bs/rs1/sig/algo' => $bs_rs1_sig_algo,
        'bs/rs1/sig/size' => $bs_rs1_sig_size,
    );
}

/**
 * Link - Check and write link into parts files.
 *
 * @param $link
 * @return boolean
 */
function lnk_write($link): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteLink')
        || !lnk_verify($link)
    )
        return false;

    // Extract link parts.
    $linkParsed = lnk_parse($link);

    // Write link into parts files.
    $result = io_linkWrite($linkParsed['bl/rl/nid1'], $link);
    if ($linkParsed['bl/rl/nid2'] != '')
        $result = io_linkWrite($linkParsed['bl/rl/nid2'], $link) && $result;
    if ($linkParsed['bl/rl/nid3'] != '')
        $result = io_linkWrite($linkParsed['bl/rl/nid3'], $link) && $result;
    if ($linkParsed['bl/rl/nid4'] != '')
        $result = io_linkWrite($linkParsed['bl/rl/nid4'], $link) && $result;

    // Write link for signer if needed.
    if (lib_getConfiguration('permitAddLinkToSigner'))
        $result = io_linkWrite($linkParsed['bs/rs1/eid'], $link) && $result;

    // Write link to history if needed.
    $histFile = LIB_LOCAL_HISTORY_FILE;
    if (lib_getConfiguration('permitHistoryLinksSign'))
        $result = io_linkWrite($histFile, $link) && $result;

    return $result;
}



/**
 * Find NID by reference RID from initial NID.
 * Links must be signed by a local authority.
 *
 * @param string  $nid
 * @param string  $rid
 * @return string
 */
function nod_findByReference(string $nid, string $rid): string
{
    global $nebuleLocalAuthorities;

    if (!nod_checkNID($nid)
        || !nod_checkNID($rid)
    )
        return '';

    $links = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid3' => $rid,
        'bl/rl/nid4' => '',
    );
    lnk_getList($nid, $links, $filter, false);

    foreach ($links as $link) {
        foreach ($nebuleLocalAuthorities as $authority) {
            if ($link['bs/rs1/eid'] == $authority)
                return $link['bl/rl/nid2'];
        }
    }
    return '';
}

/**
 * Find firstname to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getFirstName(string $nid): string
{
    global $nebuleCacheReadEntityFName;

    if (isset($nebuleCacheReadEntityFName [$nid]))
        return $nebuleCacheReadEntityFName [$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/prenom', lib_getConfiguration('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityFName [$nid] = $text;
    return $text;
}

/**
 * Find name to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getName(string $nid): string
{
    global $nebuleCacheReadEntityName;

    if (isset($nebuleCacheReadEntityName [$nid]))
        return $nebuleCacheReadEntityName [$nid];

log_add('MARK get name nid='.$nid.' --------------------------------------------------------', 'normal', __FUNCTION__, '00000005');
    $type = nod_getByType($nid, obj_getNID('nebule/objet/nom', lib_getConfiguration('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityName [$nid] = $text;
    return $text;
}

/**
 * Find postname to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getPostName(string $nid): string
{
    global $nebuleCacheReadEntityPName;

    if (isset($nebuleCacheReadEntityPName [$nid]))
        return $nebuleCacheReadEntityPName [$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/postnom', lib_getConfiguration('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityPName [$nid] = $text;
    return $text;
}

/**
 * Find OID with content for the type of the NID.
 *
 * @param string $nid
 * @param string $rid
 * @return string
 */
function nod_getByType(string $nid, string $rid): string
{
    global $nebuleCacheFindObjType;

//    if (isset($nebuleCacheFindObjType [$nid] [$rid]))
//        return $nebuleCacheFindObjType [$nid] [$rid];

    $links = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid3' => $rid,
        'bl/rl/nid4' => '',
    );
    lnk_getList($nid, $links, $filter, false);

    foreach ($links as $link) {
log_add('MARK get by type nid1='.$nid.' nid2='.$link['bl/rl/nid2'].' $nid3='.$rid, 'normal', __FUNCTION__, '00000001');
        if (lib_getConfiguration('permitBufferIO'))
            $nebuleCacheFindObjType [$nid] [$rid] = $link['bl/rl/nid2'];
        return $link['bl/rl/nid2'];
    }
    return '';
}

/**
 * Node - Extract algo parts from NID.
 *
 * @param $nid
 * @return string
 */
function nod_getAlgo(&$nid): string
{
    strtok($nid, '.');
    $a = strtok('.');
    $s = strtok('.');
    if ($a == '' || $s == '')
        return '';
    return $a . '.' . $s;
}

/**
 * Object - Verify name structure of the node : hash.algo.size
 *
 * @param string  $nid
 * @param boolean $permitNull
 * @return boolean
 */
function nod_checkNID(string $nid, bool $permitNull = false): bool
{
    // May be null in some case.
    if ($permitNull && $nid == '')
        return true;

    // Check hash value.
    $hash = strtok($nid, '.');
    if ($hash === false) return false;
    if (strlen($hash) < LIB_NID_MIN_HASH_SIZE) return false;
    if (strlen($hash) > LIB_NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($hash)) return false;

    // Check algo value.
    $algo = strtok('.');
    if ($algo === false) return false;
    if (strlen($algo) < LIB_NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > LIB_NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    $size = strtok('.');
    if ($size === false) return false;
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < LIB_NID_MIN_HASH_SIZE) return false;
    if ((int)$size > LIB_NID_MAX_HASH_SIZE) return false;
    if ((strlen($hash) * 4) != (int)$size) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    return true;
}

/**
 * Object - Check with links if a node is marked as banned.
 *
 * @param $nid
 * @return boolean
 */
function nod_checkBanned_FIXME(&$nid): bool
{
    global $nebulePublicEntity, $nebuleSecurityAuthorities, $nebuleCacheIsBanned;

    // FIXME
    return false;
    /*
    if (isset($nebuleCacheIsBanned [$nid]))
        return $nebuleCacheIsBanned [$nid];

    if ($nid == '0')
        return false;

    $ok = false;
    $table = array();
    $hashtype = _objGetNID('nebule/danger', getConfiguration('cryptoHashAlgorithm'));
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $hashtype,
        'bl/rl/nid2' => $nid,
        'bl/rl/nid3' => '',
        'bl/rl/nid4' => '',
    );
    _lnkFind($nid, $table, $filter);
    foreach ($table as $link) {
        if (($link [2] == $nebulePublicEntity) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $nid) && ($link [7] == '0'))
            $ok = true;
        if (($link [2] == $nebuleSecurityAuthority) && ($link [4] == 'f') && ($link [5] == $hashtype) && ($link [6] == $nid) && ($link [7] == '0'))
            $ok = true;
    }
    unset($table);
    unset($hashtype);

    if (getConfiguration('permitBufferIO'))
        $nebuleCacheIsBanned [$nid] = $ok;



            addLog($nid . ') banned by ' . $nebulePublicEntity, 'warn', __FUNCTION__, 'a9668cd0');
            addLog($nid . ') banned by ' . $nebuleSecurityAuthority, 'warn', __FUNCTION__, 'd84f8e81');



    return $ok;*/
}

/**
 * Object - Read one line of object content as printable text.
 *
 * @param string  $oid
 * @param integer $maxData
 * @return string
 */
function obj_getAsText1line(string &$oid, int $maxData = 128): string
{
    global $nebuleCacheReadObjText1line;

    if (isset($nebuleCacheReadObjText1line [$oid]))
        return $nebuleCacheReadObjText1line [$oid];

    if ($maxData == 0)
        $maxData = lib_getConfiguration('ioReadMaxData');

    $data = '';
    obj_getLocalContent($oid, $data, $maxData + 1);
    $data = strtok(filter_var($data, FILTER_SANITIZE_STRING), "\n");
    if (!is_string($data))
        return '';

    $data = trim($data);
    $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', filter_var($data, FILTER_SANITIZE_STRING));

    if (extension_loaded('mbstring'))
        $data = mb_convert_encoding($data, 'UTF-8');
    else
        log_add('mbstring extension not installed or activated!', 'warn', __FUNCTION__, 'c2becfad');

    if (strlen($data) > $maxData)
        $data = substr($data, 0, ($maxData - 3)) . '...';

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheReadObjText1line [$oid] = $data;

    return $data;
}

/**
 * Object - Read object content as printable text.
 *
 * @param string  $oid
 * @param integer $maxData
 * @return string
 */
function obj_getAsText(string &$oid, int $maxData = 0): string
{
    if ($maxData == 0)
        $maxData = lib_getConfiguration('ioReadMaxData');

    $data = '';
    obj_getLocalContent($oid, $data, $maxData + 1);
    $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', filter_var($data, FILTER_SANITIZE_STRING));

    if (strlen($data) > $maxData)
        $data = substr($data, 0, ($maxData - 3)) . '...';

    return $data;
}

/**
 * Get type Mime to the node (object) from his links.
 * The type mime asked is converted as NID and may not have object.
 *
 * @param string $nid
 * @param string $typeMime
 * @param string $addSigner
 * @return bool
 */
function obj_checkTypeMime(string $nid, string $typeMime, string $addSigner = ''): bool
{
    global $nebuleLocalAuthorities, $nebuleCacheReadObjTypeMime, $nebuleServerEntity;

    if (isset($nebuleCacheReadObjTypeMime [$nid])) {
        if ($nebuleCacheReadObjTypeMime [$nid] == $typeMime)
            return true;
        else
            return false;
    }

    if (!nod_checkNID($nid))
        return false;

    $typeRID = obj_getNID('nebule/objet/type', lib_getConfiguration('cryptoHashAlgorithm'));
    $hashTypeAsked = obj_getNID($typeMime, lib_getConfiguration('cryptoHashAlgorithm'));
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid2' => $hashTypeAsked,
        'bl/rl/nid3' => $typeRID,
        'bl/rl/nid4' => '',
    );
    $links = array();
    lnk_getList($nid, $links, $filter, false, $addSigner);
    $signers = $nebuleLocalAuthorities;
    $signers[] = $nid;
    lnk_filterBySigners($links, $signers);

    if (sizeof($links) == 0)
        return false;

    return true;
}

/**
 * Write text in object content.
 * By default, if object present, do not create links for the object ($skipIfPresent).
 *
 * @param string $data
 * @param bool   $skipIfPresent
 * @return bool
 */
function obj_setContentAsText(string $data, bool $skipIfPresent = true): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
        || !lib_getConfiguration('permitWriteLink')
        || strlen($data) == 0
    )
        return false;

    $oid = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));

    if (nod_checkBanned_FIXME($oid))
        return false;
    if ($skipIfPresent && io_checkNodeHaveContent($oid))
        return true;

    return obj_generate_FIXME($data, 'text/plain');
}

/**
 * Object - Create new object from data.
 *
 * @param string $data
 * @param string $typeMime
 * @return bool
 */
function obj_generate_FIXME(string &$data, string $typeMime = ''): bool
{
    if (strlen($data) == 0
        || !lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
    )
        return false;
    $date = '0' . date('YmdHis');
    $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));

    if (!io_checkNodeHaveContent($hash))
        obj_setContent($data, $hash);

    $link = lnk_generateSign(
        $date,
        'l',
        $hash,
        obj_getNID(lib_getConfiguration('cryptoHashAlgorithm'), lib_getConfiguration('cryptoHashAlgorithm')),
        obj_getNID('nebule/objet/hash', lib_getConfiguration('cryptoHashAlgorithm'))
    );
    if (lnk_verify($link))
        lnk_write($link);

    if ($typeMime != '') {
        $link = lnk_generateSign(
            $date,
            'l',
            $hash,
            obj_getNID($typeMime, lib_getConfiguration('cryptoHashAlgorithm')),
            obj_getNID('nebule/objet/type', lib_getConfiguration('cryptoHashAlgorithm'))
        );
        if (lnk_verify($link))
            lnk_write($link);
    }
    return true;
}

/**
 * Object - Read object content and push on $data.
 *
 * @param string  $nid
 * @param string  $data
 * @param numeric $maxData
 * @return boolean
 */
function obj_getLocalContent(string $nid, string &$data, int $maxData = 0): bool
{
    if (obj_checkContent($nid)) {
        $data = io_objectRead($nid, $maxData);
        return true;
    }
    return false;
}

/**
 * Object - Download node content (object) on web locations.
 * Only valid content are written on local filesystem.
 *
 * @param string $nid
 * @param array  $locations
 * @return boolean
 */
function obj_getDistantContent(string $nid, array $locations = array()): bool
{
    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
        || !lib_getConfiguration('permitSynchronizeObject')
        || !nod_checkNID($nid, false)
        || nod_checkBanned_FIXME($nid)
    )
        return false;

    if (io_checkNodeHaveContent($nid))
        return true;

    if (sizeof($locations) == 0)
        $locations = LIB_FIRST_LOCALISATIONS;

    foreach ($locations as $location) {
        if (io_objectSynchronize($nid, $location))
            return true;
    }
    return false;
}

/**
 * Object - Vérifie la consistance d'un objet. Si l'objet est corrompu, il est supprimé.
 * If there's no content, this assumed as false.
 * TODO refaire avec i/o
 *
 * @param string $nid
 * @return boolean
 */
function obj_checkContent(string $nid): bool
{
    global $nebuleCacheLibrary_o_vr;

    lib_incrementMetrology('ov');

    if (!nod_checkNID($nid) || !io_checkNodeHaveContent($nid))
        return false;

    if (isset($nebuleCacheLibrary_o_vr[$nid]))
        return true;

    $hash = '';
    $algo = nod_getAlgo($nid);
    if ($algo != '')
        $hash = crypto_getFileHash($nid, $algo);

    // If invalid, delete file of the object.
    if ($hash . '.' . $algo != $nid)
        io_objectDelete($nid);

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheLibrary_o_vr[$nid] = true;

    return true;
}

/**
 * Object - Calculate NID for data with hash algo.
 *
 * @param string $data
 * @param string $algo
 * @return string
 */
function obj_getNID(string $data, string $algo = ''): string
{
    if ($algo == '')
        $algo = lib_getConfiguration('cryptoHashAlgorithm');
    return crypto_getDataHash($data, $algo) . '.' . $algo;
}

/**
 * Object - Write object content.
 *
 * @param string $data
 * @param string $oid
 * @return bool
 */
function obj_setContent(string &$data, string $oid = '0'): bool
{
    if (strlen($data) == 0
        || !lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteObject')
    )
        return false;

    $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
    if ($oid == '0')
        $oid = $hash;
    elseif ($oid != $hash)
        return false;

    if (io_objectWrite($data, $oid))
        return true;
    return false;
}

/**
 * Find full name to the NID.
 *
 * @param string $nid
 * @return string
 */
function ent_getFullName(string $nid): string
{
    global $nebuleCacheReadEntityFullName;

    if (isset($nebuleCacheReadEntityFullName [$nid]))
        return $nebuleCacheReadEntityFullName [$nid];

    $fname = nod_getFirstName($nid);
    $name = nod_getName($nid);
    $pname = nod_getPostName($nid);
    if ($name == '')
        $fullname = "$nid";
    else
        $fullname = $name;
    if ($fname != '')
        $fullname = "$fname $fullname";
    if ($pname != '')
        $fullname = "$fullname $pname";

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheReadEntityFullName [$nid] = $fullname;
    return $fullname;
}

/**
 * Entity - Generate a new entity.
 *
 * @param string $asymmetricAlgo
 * @param string $hashAlgo
 * @param string $hashPublicKey
 * @param string $hashPrivateKey
 * @param string $password
 * @return bool
 */
function ent_generate(string $asymmetricAlgo, string $hashAlgo, string &$hashPublicKey, string &$hashPrivateKey, string &$password = ''): bool
{
    global $nebulePublicEntity, $nebulePrivateEntity, $nebulePasswordEntity;

    if (!lib_getConfiguration('permitWrite')
        || !lib_getConfiguration('permitWriteEntity')
        || !lib_getConfiguration('permitWriteObject')
        || !lib_getConfiguration('permitWriteLink')
        //    || (($asymmetricAlgo != 'rsa') && ($asymmetricAlgo != 'dsa'))
        || $password == ''
    )
        return false;

    // Generate the bi-key.
    $publicKey = '';
    $privateKey = '';
    if (crypto_getNewPKey($asymmetricAlgo, $hashAlgo, $publicKey, $privateKey, $password)) {
        $hashPublicKey = obj_getNID($publicKey, lib_getConfiguration('cryptoHashAlgorithm'));
        log_add('generate new public key ' . $hashPublicKey, 'warn', __FUNCTION__, '9c207dc0');
        if (!obj_setContent($publicKey, $hashPublicKey))
            return false;
        $hashPrivateKey = obj_getNID($privateKey, lib_getConfiguration('cryptoHashAlgorithm'));
        log_add('generate new private key ' . $hashPrivateKey, 'warn', __FUNCTION__, '96059d19');
        if (!obj_setContent($privateKey, $hashPrivateKey))
            return false;
    } else
        return false;

    // Generate links for properties
    $oidType = obj_getNID('nebule/objet/type');
    $oidPem = obj_getNID('application/x-pem-file');
    $oidPKey = obj_getNID('nebule/objet/entite/prive');
    $oidText = obj_getNID('text/plain');

    $list = array($oidType, $oidPem, $oidPKey, $oidText);
    foreach ($list as $item) {
        $bh_bl = lnk_generate('', 'l', $item, $oidText, $oidType);
        $sign = crypto_asymmetricEncrypt($bh_bl, $nebulePrivateEntity, $nebulePasswordEntity, false);
        $link = $bh_bl . '_' . $nebulePublicEntity . '>' . $sign . '.' . lib_getConfiguration('cryptoHashAlgorithm');
        if (!lnk_write($link))
            return false;
    }

    $list = array($hashPublicKey, $hashPrivateKey);
    foreach ($list as $item) {
        $bh_bl = lnk_generate('', 'l', $item, $oidPem, $oidType);
        $sign = crypto_asymmetricEncrypt($bh_bl, $nebulePrivateEntity, $nebulePasswordEntity, false);
        $link = $bh_bl . '_' . $nebulePublicEntity . '>' . $sign . '.' . lib_getConfiguration('cryptoHashAlgorithm');
        if (!lnk_write($link))
            return false;
    }

    $bh_bl = lnk_generate('', 'f', $hashPublicKey, $hashPrivateKey, $oidPKey);
    $sign = crypto_asymmetricEncrypt($bh_bl, $nebulePrivateEntity, $nebulePasswordEntity, false);
    $link = $bh_bl . '_' . $nebulePublicEntity . '>' . $sign . '.' . lib_getConfiguration('cryptoHashAlgorithm');
    if (!lnk_write($link))
        return false;

    return true;
}

/**
 * Get asked authorities list.
 * Make a search for global entities and for code branch specific entities.
 *
 * @param string $refNid
 * @param array  $result
 * @param bool   $synchronize
 * @return array
 */
function ent_getAskedAuthorities(string $refNid, array &$result, bool $synchronize): array
{
    if (sizeof($result) != 0)
        return $result;

    if ($synchronize)
        obj_getDistantContent($refNid, array());

    $lnkList = array();
    $entList = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid2' => $refNid,
        'bl/rl/nid3' => '',
        'bl/rl/nid4' => '',
        'bs/rs1/eid' => lib_getConfiguration('puppetmaster'),
    );
    lnk_getList($refNid, $lnkList, $filter);

    if (lib_getConfiguration('CodeBranch') != '') {
        $filter['bl/rl/nid3'] = obj_getNID(lib_getConfiguration('CodeBranch'));
        lnk_getList($refNid, $lnkList, $filter);
    }

    // Extract uniques entities
    foreach ($lnkList as $lnk)
        $entList[$lnk['bl/rl/nid1']] = $lnk['bl/rl/nid1'];
    foreach ($entList as $ent)
        $result[] = $ent;

    return $result;
}

/**
 * Get authorities of security IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getSecurityAuthorities(bool $synchronize = false): array
{
    global $nebuleSecurityAuthorities;
    return ent_getAskedAuthorities(LIB_RID_SECURITY_AUTHORITY, $nebuleSecurityAuthorities, $synchronize);
}

/**
 * Get authorities of code IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getCodeAuthorities(bool $synchronize = false): array
{
    global $nebuleCodeAuthorities;
    return ent_getAskedAuthorities(LIB_RID_CODE_AUTHORITY, $nebuleCodeAuthorities, $synchronize);
}

/**
 * Get authorities of time IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getTimeAuthorities(bool $synchronize = false): array
{
    global $nebuleTimeAuthorities;
    return ent_getAskedAuthorities(LIB_RID_TIME_AUTHORITY, $nebuleTimeAuthorities, $synchronize);
}

/**
 * Get authorities of directory IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getDirectoryAuthorities(bool $synchronize = false): array
{
    global $nebuleDirectoryAuthorities;
    return ent_getAskedAuthorities(LIB_RID_DIRECTORY_AUTHORITY, $nebuleDirectoryAuthorities, $synchronize);
}

/**
 * Check puppetmaster entity.
 *
 * @param string $oid
 * @return bool
 */
function ent_checkPuppetmaster(string $oid): bool
{
    if (!ent_checkIsPublicKey($oid)) {
        log_add('need sync puppetmaster', 'warn', __FUNCTION__, '6995b7fd');
        bootstrap_setBreak('71', 'Need sync puppetmaster');
        return false;
    }
    return true;
}

/**
 * Check authorities of security entities.
 *
 * @param array $oidList
 * @return bool
 */
function ent_checkSecurityAuthorities(array $oidList): bool
{
    if (sizeof($oidList) == 0) {
        log_add('need sync authorities of security', 'warn', __FUNCTION__, 'a767699e');
        bootstrap_setBreak('72', 'Need sync authorities of security');
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            log_add('need sync authorities of security ' . $nid, 'warn', __FUNCTION__, '5626b8f');
            bootstrap_setBreak('73', 'Need sync authorities of security');
            return false;
        }
    }
    return true;
}

/**
 * Check authorities of code entities.
 *
 * @param array $oidList
 * @return bool
 */
function ent_checkCodeAuthorities(array $oidList): bool
{
    if (sizeof($oidList) == 0) {
        log_add('need sync authorities of code', 'warn', __FUNCTION__, '8543b436');
        bootstrap_setBreak('74', 'Need sync authorities of code');
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            log_add('need sync authorities of code ' . $nid, 'warn', __FUNCTION__, '0ff4516d');
            bootstrap_setBreak('75', 'Need sync authorities of code');
            return false;
        }
    }
    return true;
}

/**
 * Check authorities of time entities.
 *
 * @param array $oidList
 * @return bool
 */
function ent_checkTimeAuthorities(array $oidList): bool
{
    if (sizeof($oidList) == 0) {
        log_add('need sync authorities of time', 'warn', __FUNCTION__, '0c6f1ef1');
        bootstrap_setBreak('76', 'Need sync authorities of time');
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            log_add('need sync authorities of time ' . $nid, 'warn', __FUNCTION__, '01f5f9b5');
            bootstrap_setBreak('77', 'Need sync authorities of time');
            return false;
        }
    }
    return true;
}

/**
 * Check authorities of directory entities.
 *
 * @param array $oidList
 * @return bool
 */
function ent_checkDirectoryAuthorities(array $oidList): bool
{
    if (sizeof($oidList) == 0) {
        log_add('need sync authorities of directory', 'warn', __FUNCTION__, 'e47e9e04');
        bootstrap_setBreak('78', 'Need sync authorities of directory');
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            log_add('need sync authorities of directory ' . $nid, 'warn', __FUNCTION__, '8b12fe09');
            bootstrap_setBreak('79', 'Need sync authorities of directory');
            return false;
        }
    }
    return true;
}

/**
 * Synchronize puppetmaster from central location.
 * Specifically for puppetmaster, first contents are locally generated.
 *
 * @param string $oid
 * @return void
 */
function ent_syncPuppetmaster(string $oid): void
{
    global $configurationList;

    if (!ent_checkIsPublicKey($oid)) {
        $oid = LIB_DEFAULT_PUPPETMASTER_EID;
        $configurationList['puppetmaster'] = LIB_DEFAULT_PUPPETMASTER_EID;
    }

    if ($oid == LIB_DEFAULT_PUPPETMASTER_EID) {
        log_add('Write default puppetmaster', 'info', __FUNCTION__, '555ec326');
        foreach (LIB_FIRST_AUTHORITIES_PUBLIC_KEY as $data)
        {
            $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
            io_objectWrite($data, $hash);
        }
        foreach (LIB_FIRST_LINKS as $link)
            lnk_write($link);
    }

    ent_syncAuthorities(array($oid));
}

/**
 * Synchronize authorities from central locations.
 *
 * @param array $oidList
 * @return void
 */
function ent_syncAuthorities(array $oidList): void
{
    global $nebuleCacheIsPublicKey, $nebuleCacheIsPrivateKey;

    foreach ($oidList as $nid) {
        log_add('Sync authority entity ' . $nid, 'info', __FUNCTION__, '92e0483f');
        obj_getDistantContent($nid, array());
        lnk_getDistantOnLocations($nid, array());
    }

    $nebuleCacheIsPublicKey = array();
    $nebuleCacheIsPrivateKey = array();
}

/**
 * Object - Verify node is an object and is a valid entity public key.
 *
 * @param string $nid
 * @return boolean
 */
function ent_checkIsPublicKey(string $nid): bool
{
    global $nebuleCacheIsPublicKey;

    $result = false;
    if (isset($nebuleCacheIsPublicKey[$nid]))
        return $nebuleCacheIsPublicKey[$nid];

    if (strlen($nid) < LIB_NID_MIN_HASH_SIZE
        || !nod_checkNID($nid, false)
        || !obj_checkContent($nid)
        || !io_checkNodeHaveLink($nid)
    ) {
        log_add('not a valid object for a key ' . $nid, 'warn', __FUNCTION__, '9c268f6a');
        return false;
    }

    if (!obj_checkTypeMime($nid, 'application/x-pem-file', $nid)) {
        log_add('not marked as key ' . $nid, 'warn', __FUNCTION__, 'e040a140');
        return false;
    }

    $line = obj_getAsText($nid, 10000);
    if (strstr($line, 'BEGIN PUBLIC KEY') !== false)
        $result = true;
    else
        log_add('NID do not provide a public key', 'warn', __FUNCTION__, '25743bf3');

    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheIsPublicKey[$nid] = $result;
    return $result;
}

/**
 * Verify node is an object and is a valid entity private key.
 *
 * @param $nid
 * @return bool
 */
function ent_checkIsPrivateKey(&$nid): bool
{
    global $nebuleCacheIsPrivateKey;

    if (isset($nebuleCacheIsPrivateKey [$nid]))
        return $nebuleCacheIsPrivateKey [$nid];

    if ($nid == '0'
        || strlen($nid) < LIB_NID_MIN_HASH_SIZE
        || !nod_checkNID($nid)
        || !obj_checkContent($nid)
        || !io_checkNodeHaveLink($nid)
    )
        return false;

    if (!obj_checkTypeMime($nid, 'application/x-pem-file'))
        return false;

    $line = obj_getAsText($nid, 10000);
    $result = false;
    if (strstr($line, 'BEGIN ENCRYPTED PRIVATE KEY') !== false)
        $result = true;
    if (lib_getConfiguration('permitBufferIO'))
        $nebuleCacheIsPrivateKey[$nid] = $result;
    return $result;
}

/**
 * Application - Check OID for an application.
 *
 * @param string $oid
 * @return bool
 */
function app_checkOID(string $oid): bool
{
    if ((!nod_checkNID($oid, false)
            && $oid != '0'
            && $oid != '1'
            && $oid != '2'
        )
        || !io_checkNodeHaveLink($oid)
        || !io_checkNodeHaveContent($oid)
    )
        return false;

    // TODO add content check...

    return true;
}

/**
 * Application - Get if an application is activated.
 *
 * @param string $oid
 * @return bool
 */
function app_getActivated(string $oid): bool
{
    global $nebuleLocalAuthorities;

    // Check for defaults app.
    if ($oid == '0'
        || $oid == '1'
        || $oid == '2'
        || $oid == lib_getConfiguration('defaultApplication')
    )
        return true;

    // Check with links.
    $refActivated = LIB_RID_INTERFACE_APPLICATIONS_ACTIVE;
    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $oid,
        'bl/rl/nid2' => $refActivated,
        'bl/rl/nid3' => '',
        'bl/rl/nid4' => '',
    );
    lnk_getList($oid, $links, $filter);
    foreach ($links as $link) {
        foreach ($nebuleLocalAuthorities as $authority) {
            if ($link['bs/rs1/eid'] == $authority)
                return true;
        }
    }

    return false;
}

/**
 * Find code branch to find apps codes.
 *
 * @return void
 */
function app_getCodeBranch(): void
{
    global $nebuleLocalAuthorities, $codeBranchNID;

    if ($codeBranchNID != '')
        return;

    // Get code branch on config
    $codeBranchName = lib_getConfiguration('codeBranch');
    if ($codeBranchName == '')
        $codeBranchName = LIB_CONFIGURATIONS_DEFAULT['codeBranch'];
    $codeBranchNID = '';

    // Check if it's a name or an OID.
    if (nod_checkNID($codeBranchName, false)
        && io_checkNodeHaveContent($codeBranchName)
    ) {
        $codeBranchNID = $codeBranchName;
    } else {
        // Get all RID of code branches
        $CodeBranchRID = LIB_RID_CODE_BRANCH;
        $bLinks = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $CodeBranchRID,
            'bl/rl/nid3' => $CodeBranchRID,
            'bl/rl/nid4' => '',
        );
        lnk_getList($CodeBranchRID, $bLinks, $filter, false);
        lnk_filterBySigners($bLinks, $nebuleLocalAuthorities);

        // Get all NID with the name of wanted code branch.
        $nLinks = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => obj_getNID($codeBranchName, LIB_REF_CODE_ALGO),
            'bl/rl/nid3' => obj_getNID('nebule/objet/nom', LIB_REF_CODE_ALGO),
            'bl/rl/nid4' => '',
        );
        lnk_getList($CodeBranchRID, $nLinks, $filter, false);
        lnk_filterBySigners($nLinks, $nebuleLocalAuthorities);

        // Latest collision of code branches with the name
        $bl_rc_mod = '0';
        $bl_rc_chr = '0';
        foreach ($bLinks as $bLink) {
            foreach ($nLinks as $nLink) {
                if ($bLink['bl/rl/nid2'] == $nLink['bl/rl/nid1']
                    && lnk_dateCompare($bl_rc_mod, $bl_rc_chr, $bLink['bl/rc/mod'], $bLink['bl/rc/chr']) < 0
                ) {
                    $bl_rc_mod = $bLink['bl/rc/mod'];
                    $bl_rc_chr = $bLink['bl/rc/chr'];
                    $codeBranchNID = $bLink['bl/rl/nid2'];
                }
            }
        }
        unset($bLinks, $nLinks);
    }
}

/**
 * Find a valid application OID from an RID for current code branch.
 * Can be used both for library and application.
 *
 * @param $rid
 * @return string
 */
function app_getByRef($rid): string
{
    global $nebuleLocalAuthorities,
           $codeBranchNID,
           $lastReferenceSignerID;

    if ($codeBranchNID == '')
        app_getCodeBranch();

    // Get current version of code
    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $rid,
        'bl/rl/nid3' => $codeBranchNID,
        'bl/rl/nid4' => '',
    );
    lnk_getList($rid, $links, $filter, false);
    lnk_filterBySigners($links, $nebuleLocalAuthorities);

    if (sizeof($links) == 0)
        return '';

    // Get newest link
    $resultLink=$links[0];
    foreach ($links as $link)
    {
        if (lnk_dateCompare($link['bl/rc/mod'],$link['bl/rc/chr'],$resultLink['bl/rc/mod'],$resultLink['bl/rc/chr']) > 0)
            $resultLink = $link;
    }

    $lastReferenceSignerID = $resultLink['bs/rs1/eid'];
    return $resultLink['bl/rl/nid2'];
}



/*
 *
 *
 *
 *

 ==/ 3 /===================================================================================
 PART3 : Manage PHP session and arguments.

 TODO.
 ------------------------------------------------------------------------------------------
 */

// ------------------------------------------------------------------------------------------
/**
 * Get on args if user want to flush session and restart a new one.
 * If session already empty, do not flush.
 *
 * @param bool $forceFlush
 * @return void
 */
function bootstrap_getFlushSession(bool $forceFlush = false): void
{
    global $bootstrapFlush;

    session_start();

    if (filter_has_var(INPUT_GET, LIB_ARG_FLUSH_SESSION)
        || filter_has_var(INPUT_POST, LIB_ARG_FLUSH_SESSION)
        || $forceFlush
    ) {
        log_add('ask flush session', 'warn', __FUNCTION__, '4abe475a');

        if (isset($_SESSION['OKsession'])
            || filter_has_var(INPUT_GET, LIB_ARG_BOOTSTRAP_BREAK)
            || filter_has_var(INPUT_POST, LIB_ARG_BOOTSTRAP_BREAK)
        ) {
            $bootstrapFlush = true;
            log_add('flush session', 'info', __FUNCTION__, '5d008c11');

            // flush and reopen session.
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(), '', 0, '/');
            session_regenerate_id(true);
            session_start();
        } else
            $_SESSION['OKsession'] = true;
    } else
        $_SESSION['OKsession'] = true;

    session_write_close();
}

/**
 * Lit si demande de l'utilisateur d'une mise à jour des instances de bibliothèque et d'application.
 * Dans ce cas, la session PHP n'est pas exploitée.
 *
 * @return void
 */
function bootstrap_getUpdate(): void
{
    global $bootstrapUpdate;

    if (filter_has_var(INPUT_GET, LIB_ARG_UPDATE_APPLICATION)
        || filter_has_var(INPUT_POST, LIB_ARG_UPDATE_APPLICATION)
    ) {
        log_add('ask update', 'warn', __FUNCTION__, 'ac8a2330');

        session_start();

        // Si la mise à jour est demandée mais pas déjà faite.
        if (!isset($_SESSION['askUpdate'])) {
            $bootstrapUpdate = true;
            log_add('update', 'info', __FUNCTION__, 'f2ef6dc2');
            $_SESSION['askUpdate'] = true;
        } else {
            unset($_SESSION['askUpdate']);
        }

        session_write_close();
    }
}

/**
 * Read arg to ask switching of application.
 */
function bootstrap_getSwitchApplication(): void
{
    global $bootstrapFlush, $bootstrapSwitchApplication, $nebuleServerEntity;

    if ($bootstrapFlush)
        return;

    $arg = '';
    if (filter_has_var(INPUT_GET, LIB_ARG_SWITCH_APPLICATION))
        $arg = trim(filter_input(INPUT_GET, LIB_ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    elseif (filter_has_var(INPUT_POST, LIB_ARG_SWITCH_APPLICATION))
        $arg = trim(filter_input(INPUT_POST, LIB_ARG_SWITCH_APPLICATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

    if (!app_checkOID($arg))
        return;

    if (app_getActivated($arg)) {
        $bootstrapSwitchApplication = $arg;
        log_add('ask switch application to ' . $bootstrapSwitchApplication, 'info', __FUNCTION__, 'd1a3f3f9');
    }
}

/**
 * Activate the capability to open PHP code on other file.
 */
function bootstrap_setPermitOpenFileCode()
{
    ini_set('allow_url_fopen', '1');
    ini_set('allow_url_include', '1');
}



/*
 *
 *
 *
 *

 ==/ 4 /===================================================================================
 PART4 : Load object oriented PHP library for nebule (Lib POO).

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * Try to find nebule Lib POO.
 *
 * @param string $bootstrapLibraryInstanceSleep
 * @return void
 */
function bootstrap_findLibraryPOO(string &$bootstrapLibraryInstanceSleep): void
{
    global $libraryCheckOK,
           $bootstrapLibraryID,
           $bootstrapLibrarySignerID,
           $lastReferenceSignerID;

    if (!$libraryCheckOK)
        return;

    // Try to find on session.
    session_start();
    if (isset($_SESSION['bootstrapLibrariesID'])
        && nod_checkNID($_SESSION['bootstrapLibrariesID'])
        && io_checkNodeHaveLink($_SESSION['bootstrapLibrariesID'])
        && obj_checkContent($_SESSION['bootstrapLibrariesID'])
        && isset($_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']])
        && $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']] != ''
    ) {
        $bootstrapLibraryID = $_SESSION['bootstrapLibrariesID'];
        $bootstrapLibraryInstanceSleep = $_SESSION['bootstrapLibrariesInstances'][$_SESSION['bootstrapLibrariesID']];
    }
    session_abort();

    // Try to find with links.
    if ($bootstrapLibraryID == '') {
        $bootstrapLibraryID = app_getByRef(LIB_RID_INTERFACE_LIBRARY);

        if (!nod_checkNID($bootstrapLibraryID, false)
            || !io_checkNodeHaveLink($bootstrapLibraryID)
            || !obj_checkContent($bootstrapLibraryID)
        ) {
            $bootstrapLibraryID = '';
            $bootstrapLibraryInstanceSleep = '';
            bootstrap_setBreak('31', 'Finding nebule library error.');
        } else {
            log_add('find nebule library (' . $bootstrapLibraryID . ')', 'info', __FUNCTION__, '90ee41fc');
            $bootstrapLibrarySignerID = $lastReferenceSignerID;
        }
    }
}

/**
 * Include nebule Library POO code.
 *
 * @return void
 */
function bootstrap_includeLibraryPOO(): void
{
    global $bootstrapLibraryID;

    if ($bootstrapLibraryID == '')
        bootstrap_setBreak('41', 'Library nebule find error');
    elseif (!io_objectInclude($bootstrapLibraryID)) {
        log_reopen(BOOTSTRAP_NAME);
        bootstrap_setBreak('42', 'Library nebule include error');
        $bootstrapLibraryID = '';
    }
}

/**
 * Load and initialize nebule Library POO.
 *
 * @param string $bootstrapLibraryInstanceSleep
 * @return void
 */
function bootstrap_loadLibraryPOO(string $bootstrapLibraryInstanceSleep): void
{
    global $nebuleInstance,
           $bootstrapLibraryID;

    if ($bootstrapLibraryID != '') {
        try {
            if (!class_exists('nebule', false))
            {
                if ($bootstrapLibraryInstanceSleep == '')
                    $nebuleInstance = new nebule();
                else
                    $nebuleInstance = unserialize($bootstrapLibraryInstanceSleep);
                log_reopen(BOOTSTRAP_NAME);
            }
        } catch (\Error $e) {
            log_reopen(BOOTSTRAP_NAME);
            log_add('Library nebule load error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), 'error', __FUNCTION__, '959c188b');
            bootstrap_setBreak('43', 'Library nebule load error');
        }
    }
}



/*
 *
 *
 *
 *

 ==/ 5 /===================================================================================
 PART5 : Find application code.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_findApplication(): void
{
    global $libraryCheckOK, $bootstrapSwitchApplication, $bootstrapUpdate, $bootstrapApplicationID,
           $bootstrapApplicationInstanceSleep, $bootstrapApplicationDisplayInstanceSleep,
           $bootstrapApplicationActionInstanceSleep, $bootstrapApplicationTraductionInstanceSleep,
           $bootstrapApplicationStartID;

    if (!$libraryCheckOK)
        return;

    $bootstrapApplicationID = '';
    $bootstrapApplicationStartID = '';
    session_start();

    // Enregistre l'identifiant de session pour le suivi d'un utilisateur.
    $sessionId = session_id();
    log_add('session hash id ' . crypto_getDataHash($sessionId), 'info', __FUNCTION__, '36ebd66b');

    // Vérifie l'ID de départ de l'application mémorisé.
    if (isset($_SESSION['bootstrapApplicationStartID'])
        && nod_checkNID($_SESSION['bootstrapApplicationStartID'])
    )
        $bootstrapApplicationStartID = $_SESSION['bootstrapApplicationStartID'];

    // Check ask to switch of application.
    if ($bootstrapSwitchApplication != ''
        && $bootstrapSwitchApplication != $bootstrapApplicationStartID
    )
    {
        switch ($bootstrapSwitchApplication) {
            case '0':
                log_add('ask switch application 0', 'info', __FUNCTION__, '35b3a0dc');
                $bootstrapApplicationStartID = '0';
                $bootstrapApplicationID = '0';
                break;
            case '1':
                log_add('ask switch application 1', 'info', __FUNCTION__, '18b6ab88');
                $bootstrapApplicationStartID = '1';
                $bootstrapApplicationID = '1';
                break;
            case '2':
                log_add('ask switch application 2', 'info', __FUNCTION__, '936abfaa');
                $bootstrapApplicationStartID = '2';
                $bootstrapApplicationID = '2';
                break;
            default:
                if (lnk_checkExist('f', LIB_RID_INTERFACE_APPLICATIONS, $bootstrapSwitchApplication, LIB_RID_INTERFACE_APPLICATIONS, '')) {

                    // Vérifie l'application non dé-sérialisée.
                    if (isset($_SESSION['bootstrapApplicationStartsID'][$bootstrapSwitchApplication])
                        && nod_checkNID($_SESSION['bootstrapApplicationStartsID'][$bootstrapSwitchApplication])
                        && io_checkNodeHaveLink($_SESSION['bootstrapApplicationStartsID'][$bootstrapSwitchApplication])
                        && obj_checkContent($_SESSION['bootstrapApplicationStartsID'][$bootstrapSwitchApplication]) // TODO à vérifier si utile
                        && isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapSwitchApplication])
                        && $_SESSION['bootstrapApplicationsInstances'][$bootstrapSwitchApplication] != ''
                        && isset($_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapSwitchApplication])
                        && $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapSwitchApplication] != ''
                        && isset($_SESSION['bootstrapApplicationsActionInstances'][$bootstrapSwitchApplication])
                        && $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapSwitchApplication] != ''
                        && isset($_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapSwitchApplication])
                        && $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapSwitchApplication] != ''
                    ) {
                        // Mémorise l'instance non dé-sérialisée de l'application en cours et de ses composants.
                        $bootstrapApplicationStartID = $bootstrapSwitchApplication;
                        $bootstrapApplicationID = $_SESSION['bootstrapApplicationStartsID'][$bootstrapSwitchApplication]; // TODO vérifier le bon remplissage
                        $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$bootstrapSwitchApplication];
                        $bootstrapApplicationDisplayInstanceSleep = $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapSwitchApplication];
                        $bootstrapApplicationActionInstanceSleep = $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapSwitchApplication];
                        $bootstrapApplicationTraductionInstanceSleep = $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapSwitchApplication];
                    } else {
                        $bootstrapApplicationID = app_getByRef($bootstrapApplicationStartID);
                    }
                    log_add('find switched application ' . $bootstrapApplicationID, 'info', __FUNCTION__, '0cbacda8');
                }
        }
    }

    // Check for update.
    if ($bootstrapApplicationID == ''
        && $bootstrapApplicationStartID != ''
        && $bootstrapUpdate
    ) {
        $bootstrapApplicationID = app_getByRef($bootstrapApplicationStartID);
    }

    // If existed, get application from session.
    if ($bootstrapApplicationID == ''
        && isset($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
        && nod_checkNID($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
        && io_checkNodeHaveLink($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID])
        && obj_checkContent($_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID]) // TODO à vérifier si utile
        && isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID])
        && $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID] != ''
        && isset($_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID])
        && $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID] != ''
        && isset($_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID])
        && $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID] != ''
        && isset($_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID])
        && $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID] != ''
    ) {
        // Mémorise l'instance non dé-sérialisée de l'application en cours et de ses composants.
        $bootstrapApplicationID = $_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID]; // TODO vérifier le bon remplissage
        $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID];
        $bootstrapApplicationDisplayInstanceSleep = $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID];
        $bootstrapApplicationActionInstanceSleep = $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID];
        $bootstrapApplicationTraductionInstanceSleep = $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID];

    }

    // Fermeture de la session sans écriture pour gain de temps.
    session_abort();

    // Désactivation des envois liés à la session après le premier usage. Evite tout un tas de logs inutiles.
    session_cache_limiter('');
    ini_set('session.use_cookies', '0');
    ini_set('session.use_only_cookies', '0');
    ini_set('session.use_trans_sid', '0');

    // Si pas d'application trouvée, recherche l'application par défaut
    //   ou charge l'application '0' de sélection d'application.
    if ($bootstrapApplicationID == '') {
        $defaultApplicationID = lib_getConfiguration('defaultApplication');
        if ($defaultApplicationID == 0) {
            $bootstrapApplicationStartID = '0';
            $bootstrapApplicationID = '0';
        } elseif ($defaultApplicationID == 1) {
            $bootstrapApplicationStartID = '1';
            $bootstrapApplicationID = '1';
        } elseif ($defaultApplicationID == 2) {
            $bootstrapApplicationStartID = '2';
            $bootstrapApplicationID = '2';
        } elseif (nod_checkNID($defaultApplicationID)
            && io_checkNodeHaveLink($defaultApplicationID)
        ) {
            $bootstrapApplicationStartID = $defaultApplicationID;
            $bootstrapApplicationID = app_getByRef($bootstrapApplicationStartID);
        }
        log_add('find default application ' . $bootstrapApplicationID, 'info', __FUNCTION__, '423ae49b');
    }

    if ($bootstrapApplicationID == '') {
        $bootstrapApplicationStartID = '0';
        $bootstrapApplicationID = '0';
    }
}

function bootstrap_getApplicationPreload()
{
    global $bootstrapApplicationStartID,
           $bootstrapApplicationInstanceSleep,
           $bootstrapApplicationNoPreload,
           $libraryCheckOK;

    if (!$libraryCheckOK)
        return;

    // Recherche si l'application doit être préchargée.
    if ($bootstrapApplicationStartID != '0'
        && $bootstrapApplicationStartID != '1'
        && $bootstrapApplicationInstanceSleep == ''
    ) {
        // Lit les liens de non préchargement pour l'application.
        $refNoPreload = LIB_RID_INTERFACE_APPLICATIONS_DIRECT;
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $bootstrapApplicationStartID,
            'bl/rl/nid2' => $refNoPreload,
            'bl/rl/nid3' => $bootstrapApplicationStartID,
            'bl/rl/nid4' => '',
        );
        lnk_getList($bootstrapApplicationStartID, $links, $filter);

        // Filtre sur les autorités locales.
        $bootstrapApplicationNoPreload = false;
        if (sizeof($links) != 0) {
            unset($links);
            $bootstrapApplicationNoPreload = true;
            log_add('do not preload application', 'info', __FUNCTION__, '0ac7d800');
        }
    }
}

/**
 * Include application code.
 *
 * @return void
 */
function bootstrap_includeApplication(): void
{
    global $bootstrapApplicationID,
           $libraryCheckOK;

    if ($bootstrapApplicationID != ''
        && $libraryCheckOK
        && !io_objectInclude($bootstrapApplicationID)
    ) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('error include application code ' . $bootstrapApplicationID, 'error', __FUNCTION__, '6fa5eb2b');
        $bootstrapApplicationID = '';
    }
}



/*
 *
 *
 *
 *

 ==/ 6 /===================================================================================
 PART6 : Manage and display breaking bootstrap on problem or user ask.

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * Add a break on the bootstrap.
 * In the end, this stop the loading of any application code and show the bootstrap break page.
 *
 * @param string $errorCode
 * @param string $errorDesc
 */
function bootstrap_setBreak(string $errorCode, string $errorDesc): void
{
    global $bootstrapBreak;

    $bootstrapBreak[$errorCode] = $errorDesc;
    log_add('bootstrap break code=' . $errorCode . ' : ' . $errorDesc, 'info', __FUNCTION__, '100000' . $errorDesc);
}

function bootstrap_getUserBreak(): void
{
    if (filter_has_var(INPUT_GET, LIB_ARG_BOOTSTRAP_BREAK)
        || filter_has_var(INPUT_POST, LIB_ARG_BOOTSTRAP_BREAK)
    )
        bootstrap_setBreak('11', 'User interrupt.');
}

function bootstrap_getInlineDisplay(): void
{
    global $bootstrapInlineDisplay;

    if (filter_has_var(INPUT_GET, LIB_ARG_INLINE_DISPLAY)
        || filter_has_var(INPUT_POST, LIB_ARG_INLINE_DISPLAY)
    )
        $bootstrapInlineDisplay = true;
}

function bootstrap_getCheckFingerprint(): void
{
    global $nebuleLocalAuthorities, $codeBranchNID;

    $data = file_get_contents(BOOTSTRAP_FILE_NAME);
    $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
    unset($data);

    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => LIB_RID_INTERFACE_BOOTSTRAP,
        'bl/rl/nid2' => $hash,
        'bl/rl/nid3' => $codeBranchNID,
        'bl/rl/nid4' => '',
    );
    lnk_getList($hash, $links, $filter, false);
    lnk_filterBySigners($links, $nebuleLocalAuthorities);

    if (sizeof($links) == 0) {
        log_add('unknown bootstrap hash - critical', 'error', __FUNCTION__, 'e294b7b3');
        bootstrap_setBreak('51', 'Unknown bootstrap hash');
    }
}



// ------------------------------------------------------------------------------------------
function bootstrap_getDisplayServerEntity()
{
    global $bootstrapServerEntityDisplay;

    if (filter_has_var(INPUT_GET, LIB_LOCAL_ENTITY_FILE)
        || filter_has_var(INPUT_POST, LIB_LOCAL_ENTITY_FILE)
    ) {
        bootstrap_setBreak('52', 'Ask server instance');
        $bootstrapServerEntityDisplay = true;
    }
}


// ------------------------------------------------------------------------------------------
/**
 * Affichage du début de la page HTML.
 *
 * @return void
 */
function bootstrap_htmlHeader()
{
global $libraryRescueMode;

?>
    <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?php echo BOOTSTRAP_NAME;
        if ($libraryRescueMode) echo ' - RESCUE' ?></title>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <meta name="author"
          content="<?php echo BOOTSTRAP_AUTHOR . ' - ' . BOOTSTRAP_WEBSITE . ' - ' . BOOTSTRAP_VERSION; ?>"/>
    <meta name="licence" content="<?php echo BOOTSTRAP_LICENCE . ' ' . BOOTSTRAP_AUTHOR; ?>"/>
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
            font: inherit;
            font-size: 100%;
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

        /* Le corps de la page qui contient le contenu. Permet le centrage vertical universel */
        .layout-main {
            width: 100%;
            height: 100%;
            display: flex;
        }

        /* Le centre de la page avec le contenu utile. Centrage vertical */
        .layout-content {
            margin: auto;
            padding: 74px 0 74px 0;
        }

        /* Spécifique bootstrap et app 0. */
        .parts {
            margin-bottom: 12px;
        }

        .partstitle {
            font-weight: bold;
        }

        .preload {
            clear: both;
            margin-bottom: 12px;
            min-height: 64px;
            width: 600px;
        }

        .preload img {
            height: 64px;
            width: 64px;
            float: left;
            margin-right: 8px;
        }

        .preloadsync img {
            height: 16px;
            width: 16px;
            float: none;
            margin-left: 0;
            margin-right: 1px;
        }

        .preloadstitle {
            font-weight: bold;
            color: #ffffff;
            font-size: 1.4em;
        }

        #appslist {
        }

        #appslist a:link, #appslist a:visited {
            font-weight: normal;
            text-decoration: none;
            color: #ffffff;
        }

        #appslist a:hover, #appslist a:active {
            font-weight: normal;
            text-decoration: none;
            color: #ffff80;
        }

        .apps {
            float: left;
            margin: 4px;
            height: 64px;
            width: 64px;
            padding: 8px;
            color: #ffffff;
            overflow: hidden;
        }

        .appstitle {
            font-size: 2em;
            font-weight: normal;
            text-decoration: none;
            color: #ffffff;
            margin: 0;
        }

        .appsname {
            font-weight: bold;
        }

        .appssigner {
            float: right;
            height: 24px;
            width: 24px;
        }

        .appssigner img {
            height: 24px;
            width: 24px;
        }

        #sync {
            clear: both;
            width: 100%;
            height: 50px;
        }

        #footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            width: 100%;
            padding: 3px;
            background: #ababab;
            border-top-style: solid;
            border-top-color: #c8c8c8;
            border-top-width: 1px;
            margin: 0;
        }

        .error, .important {
            color: #ffffff;
            font-weight: bold;
        }

        .diverror {
            color: #ffffff;
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .diverror pre {
            padding-left: 6px;
            margin: 3px;
            border-left-style: solid;
            border-left-color: #ababab;
            border-left-width: 1px;
        }

        #reload {
            padding-top: 32px;
            clear: both;
        }

        #reload a {
            color: #ffffff;
            font-weight: bold;
        }

        .important {
            background: #ffffff;
            color: #000000;
            font-weight: bold;
            margin: 10px;
            padding: 10px;
        }

        /* Spécifique app 1. */
        #layout_documentation {
            background: #ffffff;
            padding: 20px;
            text-align: left;
            color: #000000;
            font-size: 0.8rem;
            font-family: sans-serif;
            min-width: 400px;
            max-width: 1200px;
        }

        #title_documentation {
            margin-bottom: 30px;
        }

        #title_documentation p {
            text-align: center;
            color: #000000;
            font-size: 0.7em;
        }

        #title_documentation p a:link, #title_documentation p a:visited {
            font-weight: normal;
            text-decoration: none;
            color: #000000;
        }

        #title_documentation p a:hover, #title_documentation p a:active {
            font-weight: normal;
            text-decoration: underline;
            color: #000000;
        }

        #content_documentation {
            text-align: justify;
            color: #000000;
            font-size: 1em;
        }

        #content_documentation h1 {
            text-align: left;
            color: #454545;
            font-size: 2em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 80px;
            margin-bottom: 5px;
        }

        #content_documentation h2 {
            text-align: left;
            color: #454545;
            font-size: 1.8em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        #content_documentation h3 {
            text-align: left;
            color: #454545;
            font-size: 1.6em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 40px;
            margin-bottom: 5px;
        }

        #content_documentation h4 {
            text-align: left;
            color: #454545;
            font-size: 1.4em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        #content_documentation h5 {
            text-align: left;
            color: #454545;
            font-size: 1.2em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        #content_documentation h6 {
            text-align: left;
            color: #454545;
            font-size: 1.1em;
            font-weight: bold;
            margin-left: 10px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        #content_documentation p {
            text-align: justify;
            margin-top: 5px;
        }

        .pcenter {
            text-align: center;
        }

        #content_documentation a:link, #content_documentation a:visited {
            font-weight: normal;
            text-decoration: underline;
            color: #000000;
        }

        #content_documentation a:hover, #content_documentation a:active {
            font-weight: normal;
            text-decoration: underline;
            color: #0000ab;
        }

        code {
            font-family: monospace;
        }

        #content_documentation pre {
            font-family: monospace;
            text-align: left;
            border-left-style: solid;
            border-left-color: #c8c8c8;
            border-left-width: 1px;
        }

        #content_documentation ol li, #content_documentation ul li {
            text-align: left;
            list-style-position: inside;
            margin-left: 10px;
        }

        #content_documentation ol {
            list-style-type: decimal-leading-zero;
        }

        #content_documentation ul {
            list-style-type: disc;
        }
    </style>
    <script language="javascript" type="text/javascript">
        <!--
        function replaceInlineContentFromURL(id, url) {
            document.getElementById(id).innerHTML = '<object class="inline" type="text/html" data="' + url + '" ></object>';
        }

        function followHref(url) {
            window.location.href = url;
        }

        //-->
    </script>
</head>
<?php
}



// ------------------------------------------------------------------------------------------
/**
 * Affichage de l'entête, du logo et du bas de page.
 *
 * @return void
 */
function bootstrap_htmlTop()
{
    global $nebuleServerEntity;

    $name = ent_getFullName($nebuleServerEntity);
    if ($name == $nebuleServerEntity)
        $name = '/';

    ?>
<body>
<div class="layout-header">
    <div class="header-left">
        <a href="/?<?php echo LIB_ARG_SWITCH_APPLICATION; ?>=0">
            <img title="App switch" alt="[]" src="<?php echo LIB_BOOTSTRAP_ICON; ?>"/>
        </a>
    </div>
    <div class="header-right">
        &nbsp;
    </div>
    <div class="header-center">
        <p>
            <?php echo $name . '<br />' . $nebuleServerEntity; ?>
        </p>
    </div>
</div>
<div class="layout-footer">
    <div class="footer-center">
        <p>
            <?php echo BOOTSTRAP_NAME; ?><br/>
            <?php echo BOOTSTRAP_VERSION; ?><br/>
            (c) <?php echo BOOTSTRAP_LICENCE . ' ' . BOOTSTRAP_AUTHOR; ?> - <a
                    href="http://<?php echo BOOTSTRAP_WEBSITE; ?>" target="_blank"
                    style="text-decoration:none;"><?php echo BOOTSTRAP_WEBSITE; ?></a>
        </p>
    </div>
</div>

    <?php
    echo '<div class="layout-main">' . "\n"; // TODO à revoir...
    echo '    <div class="layout-content">';
}

// ------------------------------------------------------------------------------------------
/**
 * Affichage de la fermeture de la page HTML.
 *
 * @return void
 */
function bootstrap_htmlBottom()
{
    echo '    </div>' . "\n";
    echo '</div>';
    ?>

</body>
</html>
    <?php
}


// ------------------------------------------------------------------------------------------
/**
 * bootstrapNormalDisplayOnBreak()
 * La fonction bootstrapNormalDisplayOnBreak affiche l'écran du bootstrap en cas d'interruption.
 * L'interruption peut être appelée par l'utilisateur ou provoqué par une erreur lors des
 * vérifications de bon fonctionnement. Les vérifications ont lieu à chaque chargement de
 * page. Au cours de l'affichage de la page du bootstrap, les vérifications de bon
 * fonctionnement sont refait un par un avec affichage en direct du résultat.
 *
 * @return void
 */
function bootstrap_displayOnBreak(): void
{
    bootstrap_htmlHeader();
    bootstrap_htmlTop();
    bootstrap_breakDisplay1OnError();
    bootstrap_breakDisplay2LibraryPP();
    bootstrap_breakDisplay3LibraryPOO();
    bootstrap_breakDisplay4Application();
    bootstrap_breakDisplay5End();
    bootstrap_htmlBottom();
}

function bootstrap_breakDisplay1OnError()
{
    global $bootstrapBreak,
           $libraryRescueMode,
           $bootstrapFlush;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#1 ' . BOOTSTRAP_NAME . ' break on</span><br/>' . "\n";
    ksort($bootstrapBreak);
    foreach ($bootstrapBreak as $number => $message)
        echo '- [' . $number . '] <span class="error">' . $message . '</span>' . "<br />\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    if ($libraryRescueMode)
        echo "RESCUE mode<br />\n";
    if ($bootstrapFlush)
        echo "FLUSH<br />\n";
    if (sizeof($bootstrapBreak) != 0 && isset($bootstrapBreak[12]))
        echo "<a href=\"?a=0\">&gt; Return to application 0</a><br />\n";
    $sessionId = session_id();
    echo '<a href="?f">&gt; Flush PHP session</a> (' . substr(crypto_getDataHash($sessionId), 0, 6) . ')' . "\n";
    echo '</div>' . "\n";
}

function bootstrap_breakDisplay2LibraryPP()
{
    global $nebuleSecurityAuthorities,
           $nebuleCodeAuthorities,
           $nebuleDirectoryAuthorities,
           $nebuleTimeAuthorities,
           $nebuleServerEntity,
           $nebuleDefaultEntity,
           $nebulePublicEntity;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#2 nebule library PP</span><br/>' . "\n";
    bootstrap_echoLineTitle('library version');
    echo BOOTSTRAP_VERSION . "<br/>\n";

    bootstrap_echoLineTitle('puppetmaster');
    echo lib_getConfiguration('puppetmaster'). "<br/>\n";

    foreach ($nebuleSecurityAuthorities as $m)
    {
        bootstrap_echoLineTitle('security authority');
        echo $m . "<br/>\n";
    }

    foreach ($nebuleCodeAuthorities as $m)
    {
        bootstrap_echoLineTitle('code authority');
        echo $m . "<br/>\n";
    }

    foreach ($nebuleDirectoryAuthorities as $m)
    {
        bootstrap_echoLineTitle('directory authority');
        echo $m . "<br/>\n";
    }

    foreach ($nebuleTimeAuthorities as $m)
    {
        bootstrap_echoLineTitle('time authority');
        echo $m . "<br/>\n";
    }

    bootstrap_echoLineTitle('server entity');
    echo $nebuleServerEntity. "<br/>\n";

    bootstrap_echoLineTitle('default entity');
    echo $nebuleDefaultEntity. "<br/>\n";

    bootstrap_echoLineTitle('current entity');
    echo $nebulePublicEntity. "<br/>\n";

    bootstrap_echoLineTitle('php version');
    echo 'found ' . phpversion() . ', need >= ' . PHP_VERSION_MINIMUM;

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay3LibraryPOO()
{
    global $nebuleInstance,
           $bootstrapLibraryID,
           $bootstrapLibrarySignerID,
           $nebuleLibVersion;

    echo '<div class="parts">' . "\n" . '<span class="partstitle">#3 nebule library POO</span><br/>';
    flush();

    echo "tL=" . lib_getMetrologyTimer('tL') . "<br />\n";
    bootstrap_echoLineTitle('library RID');
    echo LIB_RID_INTERFACE_LIBRARY . "<br />\n";
    bootstrap_echoLineTitle('library ID');
    echo $bootstrapLibraryID . "<br />\n";

    if (is_a($nebuleInstance, 'Nebule\Library\nebule')) {
    bootstrap_echoLineTitle('library signer');
        echo $bootstrapLibrarySignerID . "<br />\n";
    bootstrap_echoLineTitle('library version');
        echo $nebuleLibVersion . "<br />\n";
        bootstrap_breakDisplay31LibraryEntities();
        bootstrap_breakDisplay32LibraryCryptography();
        bootstrap_breakDisplay33LibraryIO();
        bootstrap_breakDisplay34LibrarySocial();
        bootstrap_breakDisplay35LibraryBootstrap();
        bootstrap_breakDisplay36LibraryStats();
    } else
        echo "Not loaded.\n";

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay31LibraryEntities()
{
    global $nebuleInstance;

    $nebuleInstanceCheck = $nebuleInstance->checkInstance();

    bootstrap_breakDisplay311DisplayEntity('puppetmaster',
        array($nebuleInstance->getPuppetmaster() => $nebuleInstance->getPuppetmaster()),
        array($nebuleInstance->getPuppetmaster() => $nebuleInstance->getPuppetmasterInstance()),
        $nebuleInstanceCheck > 10);

    bootstrap_breakDisplay311DisplayEntity('security authority', $nebuleInstance->getSecurityAuthorities(),
        $nebuleInstance->getSecurityAuthoritiesInstance(), $nebuleInstanceCheck > 20);

    bootstrap_breakDisplay311DisplayEntity('code authority', $nebuleInstance->getCodeAuthorities(),
        $nebuleInstance->getSecurityAuthoritiesInstance(), $nebuleInstanceCheck > 30);

    bootstrap_breakDisplay311DisplayEntity('directory authority', $nebuleInstance->getDirectoryAuthorities(),
        $nebuleInstance->getDirectoryAuthoritiesInstance(), $nebuleInstanceCheck > 40);

    bootstrap_breakDisplay311DisplayEntity('time authority', $nebuleInstance->getTimeAuthorities(),
        $nebuleInstance->getTimeAuthoritiesInstance(), $nebuleInstanceCheck > 50);

    bootstrap_breakDisplay311DisplayEntity('server entity',
        array($nebuleInstance->getInstanceEntity() => $nebuleInstance->getInstanceEntity()),
        array($nebuleInstance->getInstanceEntity() => $nebuleInstance->getInstanceEntityInstance()),
        $nebuleInstanceCheck > 60);

    bootstrap_breakDisplay311DisplayEntity('default entity',
        array($nebuleInstance->getDefaultEntity() => $nebuleInstance->getDefaultEntity()),
        array($nebuleInstance->getDefaultEntity() => $nebuleInstance->getDefaultEntityInstance()),
        $nebuleInstanceCheck > 70);

    bootstrap_breakDisplay311DisplayEntity('current entity',
        array($nebuleInstance->getCurrentEntity() => $nebuleInstance->getCurrentEntity()),
        array($nebuleInstance->getCurrentEntity() => $nebuleInstance->getCurrentEntityInstance()),
        $nebuleInstanceCheck > 70);

    $entity = lib_getConfiguration('subordinationEntity');
    if ($entity != '')
        $instance = $nebuleInstance->getCacheInstance()->newNode($entity, Cache::TYPE_ENTITY);
    bootstrap_breakDisplay311DisplayEntity('subordination',
        array($entity => $entity),
        array($entity => $instance),
        $nebuleInstanceCheck > 70);

    if ($nebuleInstanceCheck != 128)
    {
        bootstrap_echoLineTitle('entities error level');
        echo (string)$nebuleInstanceCheck . "<br />\n";
    }
}

function bootstrap_breakDisplay311DisplayEntity(string $title, array $listEID, array $listInstance, bool $ok): void
{
    global $nebuleInstance;

    foreach ($listEID as $eid)
    {
        bootstrap_echoLineTitle($title);

        $name = $eid;
        if (gettype($listInstance[$eid]) == 'object' && get_class($listInstance[$eid]) == 'Entity')
            $name = $listInstance[$eid]->getName();

        if ($ok)
        {
            echo '<a href="o/' . $eid . '">' . $name . '</a> OK';
            if ($nebuleInstance->getIsLocalAuthority($listInstance[$eid]))
                echo ' (local authority)';
        } else
            echo '<span class="error">ERROR!</span>';
        echo  "<br />\n";
    }
}

function bootstrap_breakDisplay32LibraryCryptography()
{
    global $nebuleInstance;

    $_cryptoInstance = $nebuleInstance->getCryptoInstance();
    $_configurationInstance = $nebuleInstance->getConfigurationInstance();

    bootstrap_echoLineTitle('cryptography class');
    if (is_object($_cryptoInstance)) {
        echo get_class($_cryptoInstance);
        echo "<br />\n";

        bootstrap_echoLineTitle('cryptography');
        echo 'hash ' . $_configurationInstance->getOptionAsString('cryptoHashAlgorithm') . ' ';
        bootstrap_echoEndLineTest($_cryptoInstance->checkFunction($_configurationInstance->getOptionAsString('cryptoHashAlgorithm'), Crypto::TYPE_HASH));

        bootstrap_echoLineTitle('cryptography');
        echo 'symmetric ' .  $_configurationInstance->getOptionAsString('cryptoSymmetricAlgorithm') . ' ';
        bootstrap_echoEndLineTest($_cryptoInstance->checkFunction($_configurationInstance->getOptionAsString('cryptoSymmetricAlgorithm'), Crypto::TYPE_SYMMETRIC));

        bootstrap_echoLineTitle('cryptography');
        echo 'asymmetric ' .  $_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm') . ' ';
        bootstrap_echoEndLineTest($_cryptoInstance->checkFunction($_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm'), Crypto::TYPE_ASYMMETRIC));

        $random = $_cryptoInstance->getRandom(2048, Crypto::RANDOM_PSEUDO);
        $entropy = $_cryptoInstance->getEntropy($random);
        bootstrap_echoLineTitle('cryptography');
        echo 'pseudo-random entropy ' . $entropy . ' ';
        bootstrap_echoEndLineTest($entropy > 7.85);
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay33LibraryIO()
{
    global $nebuleInstance;

    $_ioInstance = $nebuleInstance->getIoInstance();

    bootstrap_echoLineTitle('i/o class');
    if (is_object($_ioInstance)) {
        echo get_class($_ioInstance);
        echo "<br />\n";

        $list = $_ioInstance->getModulesList();
        foreach ($list as $class) {
            $module = $nebuleInstance->getIoInstance()->getModule($class);
            bootstrap_echoLineTitle('i/o');
            echo $class . ' (' . $module->getMode() . ') ' . $module->getDefaultLocalisation() . ', links ';
            if (!$module->checkLinksDirectory())
                echo 'directory <span class="error">ERROR!</span>';
            else {
                if (!$module->checkLinksRead())
                    echo 'read <span class="error">ERROR!</span>';
                else {
                    if (!$module->checkLinksWrite() && $module->getMode() == 'RW' )
                        echo 'OK no write.';
                    else
                        echo 'OK';
                }
            }
            echo ', objects ';
            if (!$module->checkObjectsDirectory())
                echo 'directory <span class="error">ERROR!</span>';
            else {
                if (!$module->checkObjectsRead())
                    echo 'read <span class="error">ERROR!</span>';
                else {
                    if (!$module->checkObjectsWrite() && $module->getMode() == 'RW' )
                        echo 'OK no write.';
                    else
                        echo 'OK';
                }
            }
            echo "<br />\n";
        }
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay34LibrarySocial()
{
    global $nebuleInstance;

    if (is_object($nebuleInstance->getSocialInstance())) {
        foreach ($nebuleInstance->getSocialInstance()->getList() as $moduleName)
        {
            bootstrap_echoLineTitle('social');
            echo $moduleName;
            bootstrap_echoEndLineTest(true);
        }
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay35LibraryBootstrap()
{
    $data = file_get_contents(BOOTSTRAP_FILE_NAME);
    $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
    unset($data);
    bootstrap_echoLineTitle('bootstrap');
    echo $hash . ' ';
    $boostrap_hash = app_getByRef(LIB_RID_INTERFACE_BOOTSTRAP);
    bootstrap_echoEndLineTest($boostrap_hash == $hash);
}

function bootstrap_breakDisplay36LibraryStats()
{
    global $nebuleInstance;

    bootstrap_echoLineTitle('metrology inputs');
    echo 'L(r)=' . lib_getMetrology('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead() . ' ';
    echo 'L(v)=' . lib_getMetrology('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify() . ' ';
    echo 'O(r)=' . lib_getMetrology('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead() . ' ';
    echo 'O(v)=' . lib_getMetrology('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify() . " (PP+POO)<br />\n";
    bootstrap_echoLineTitle('metrology buffers');
    echo 'L(c)=' . $nebuleInstance->getCacheInstance()->getCacheLinkSize() . ' ';
    echo 'O(c)=' . $nebuleInstance->getCacheInstance()->getCacheObjectSize() . ' ';
    echo 'E(c)=' . $nebuleInstance->getCacheInstance()->getCacheEntitySize() . ' ';
    echo 'G(c)=' . $nebuleInstance->getCacheInstance()->getCacheGroupSize() . ' ';
    echo 'C(c)=' . $nebuleInstance->getCacheInstance()->getCacheConversationSize() . ' ';
    echo 'CU(c)=' . $nebuleInstance->getCacheInstance()->getCacheCurrencySize() . ' ';
    echo 'CP(c)=' . $nebuleInstance->getCacheInstance()->getCacheTokenPoolSize() . ' ';
    echo 'CT(c)=' . $nebuleInstance->getCacheInstance()->getCacheTokenSize() . ' ';
    echo 'CW(c)=' . $nebuleInstance->getCacheInstance()->getCacheWalletSize();
}

function bootstrap_breakDisplay4Application()
{
    global $bootstrapApplicationStartID,
           $bootstrapApplicationID;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#4 application</span><br/>' . "\n";
    echo 'application RID &nbsp;: <a href="/?' . LIB_ARG_SWITCH_APPLICATION . '=' . $bootstrapApplicationStartID  . '">'
        . $bootstrapApplicationStartID . '</a><br/>' . "\n";
    echo 'application ID &nbsp;&nbsp;: ' . $bootstrapApplicationID . "\n";
    echo '</div>' . "\n";
}

function bootstrap_breakDisplay5End()
{
    lib_setMetrologyTimer('tE');
    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#5 end ' . BOOTSTRAP_NAME . '</span><br/>' . "\n";
    echo 'tE=' . lib_getMetrologyTimer('tE') . '<br/>' . "\n";
    echo '</div>' . "\n";
}

function bootstrap_echoLineTitle(string $title): void
{
    $maxSize = 21;
    $title = trim($title);
    if (strlen($title) > $maxSize)
        $title = substr($title, 0, $maxSize);
    else
        $title .= ' ';
    $c = strlen($title);
    while ($c < $maxSize)
    {
        $title .= '&nbsp;';
        $c++;
    }
    echo $title . ' : ';
}

function bootstrap_echoEndLineTest(bool $test, string $suffix = ''): void
{
    if ($test)
        echo ' OK ' . $suffix;
    else
        echo ' <span class="error">ERROR!</span>';
    echo "<br />\n";
}



// ------------------------------------------------------------------------------------------
/**
 * Cette fonction affiche l'écran du bootstrap en cas d'interruption.
 * L'interruption peut être appelée par l'utilisateur ou provoqué par une erreur lors des
 * vérifications de bon fonctionnement. Les vérifications ont lieu à chaque chargement de
 * page. L'affichage est minimum, il est destiné à apparaître dans une page web déjà ouverte.
 *
 * @return void
 */
function bootstrap_inlineDisplayOnBreak()
{
    global $bootstrapBreak,
           $libraryRescueMode,
           $bootstrapLibraryID,
           $bootstrapApplicationID;

    ob_end_flush();

    echo "<div class=\"bootstrapErrorDiv\"><p>\n";
    echo '&gt; ' . BOOTSTRAP_NAME . ' ' . BOOTSTRAP_VERSION . "<br />\n";

    ksort($bootstrapBreak);
    echo 'Bootstrap break on : ';
    foreach ($bootstrapBreak as $number => $message)
        echo '- [' . $number . '] ' . $message . "<br />\n";
    if ($libraryRescueMode)
        echo "RESCUE<br />\n";

    echo 'nebule loading library : ' . $bootstrapLibraryID . "<br />\n";
    echo 'Application loading : ' . $bootstrapApplicationID . "<br />\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    echo "</p></div>\n";
}



/*
 *
 *
 *
 *

 ==/ 7 /===================================================================================
 PART7 : Display of pre-load application web page.

 TODO.
 ------------------------------------------------------------------------------------------
 */

/**
 * La fonction affiche temporairement l'écran du bootstrap
 *   le temps de charger les instances de la bibliothèque, de l'application et de ses classes annexes.
 * Un certain nombre de variables globalles sont initialisées au chargement des applications,
 *   elles doivent être présentes ici.
 *
 * @return void
 */
function bootstrap_displayPreloadApplication()
{
    global $nebuleInstance,
           $metrologyStartTime,
           $applicationInstance,
           $applicationDisplayInstance,
           $applicationActionInstance,
           $applicationTraductionInstance,
           $bootstrapLibraryID,
           $bootstrapApplicationID,
           $bootstrapApplicationStartID;

    // Initialisation des logs
    log_reopen('preload');
    log_add('Loading library POO', 'info', __FUNCTION__, 'ce5879b0');

    echo 'CHK';
    ob_end_clean();

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    echo '<div class="preload">' . "\n";
    echo "Please wait...<br/>\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    echo '</div>' . "\n";
    flush();

    echo '<div class="preload">' . "\n";
    ?>
    <img title="bootstrap" style="background:#ababab;" alt="[]" src="<?php echo LIB_BOOTSTRAP_ICON; ?>"/>
    Load nebule library POO<br/>
    ID=<?php echo $bootstrapLibraryID; ?><br/>
    <?php
    echo 'tL=' . lib_getMetrologyTimer('tL') . "<br />\n";
    echo '</div>' . "\n";
    flush();

    echo '<div class="preload">' . "\n";
    ?>
    <img title="bootstrap" style="background:#<?php echo substr($bootstrapApplicationStartID . '000000', 0, 6); ?>;"
         alt="[]" src="<?php echo LIB_BOOTSTRAP_ICON; ?>"/>
    Load application<br/>
    ID=<?php echo $bootstrapApplicationID; ?><br/>
    <?php
    flush();

    log_reopen('preload');
    log_add('Loading application ' . $bootstrapApplicationID, 'info', __FUNCTION__, '202824cb');

    try {
        // Charge l'objet de l'application. TODO faire via les i/o.
        include(LIB_LOCAL_OBJECTS_FOLDER . '/' . $bootstrapApplicationID);
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('Application include error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '8101a6fa');
    }

    try {
        $applicationInstance = new Application($nebuleInstance);
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('Application load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, 'a6901e43');
    }
    try {
        $applicationTraductionInstance = new Traduction($applicationInstance);
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('Application traduction load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '17c889d7');
    }
    try {
        $applicationDisplayInstance = new Display($applicationInstance);
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('Application display load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, 'e61c0842');
    }
    try {
        $applicationActionInstance = new Action($applicationInstance);
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('Application action load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '18adef64');
    }

    // Initialisation des instances.
    $applicationInstance->initialisation();
    $applicationTraductionInstance->initialisation();
    $applicationDisplayInstance->initialisation();
    $applicationActionInstance->initialisation();
    ?>

    Name=<?php echo $applicationInstance->getClassName(); ?><br/>
    sync<span class="preloadsync">
    <?php
    // Récupération des éléments annexes nécessaires à l'affichage de l'application.
    $items = $applicationDisplayInstance->getNeededObjectsList();
    $nb = 0;
    foreach ($items as $item) {
        if (!$nebuleInstance->getIoInstance()->checkObjectPresent($item)) {
            $instance = $nebuleInstance->newObject($item);
            $applicationDisplayInstance->displayInlineObjectColorNolink($instance);
            echo "\n";
            $instance->syncObject(false);
            $nb++;
        }
    }
    unset($items);

    if ($nb == 0)
        echo '-';
    ?>

    </span><br/>
    <?php
    lib_setMetrologyTimer('tP');
    echo 'tP=' . lib_getMetrologyTimer('tP') . "<br />\n";
    echo '</div>' . "\n";
    flush();

    bootstrap_partDisplayReloadPage(true, 500);
    bootstrap_htmlBottom();
}

/**
 * Subpart display to reload the HTML page.
 *
 * @param bool $ok
 * @param int  $delay
 * @return void
 */
function bootstrap_partDisplayReloadPage(bool $ok = true, int $delay = 0): void
{
    if ($delay == 0)
        $delay = LIB_FIRST_RELOAD_DELAY;

    echo '<div id="reload">' . "\n";
    if ($ok) {
        ?>

        &gt; <a onclick="javascript:window.location.reload();">reloading <?php echo BOOTSTRAP_NAME; ?></a> ...
        <script type="text/javascript">
            <!--
            setTimeout(function () {
                window.location.reload()
            }, <?php echo $delay; ?>);
            //-->
        </script>
        <?php
    } else {
        ?>

        <button onclick="javascript:window.location.reload();">when ready,
            reload <?php echo BOOTSTRAP_NAME; ?></button>
        <?php
    }
    echo "</div>\n";
}



/*
 *
 *
 *
 *

 ==/ 8 /===================================================================================
 PART8 : First synchronization of code and environment.

 TODO.
 ------------------------------------------------------------------------------------------
 */

// ------------------------------------------------------------------------------------------
/**
 * Affichage de l'initialisation de l'entité locale instance du serveur.
 *
 * @return void
 */
function bootstrap_displayApplicationFirst(): void
{
    bootstrap_firstInitEnv();
    bootstrap_htmlHeader();
    bootstrap_htmlTop();
    bootstrap_firstDisplay1Breaks();
    $ok = bootstrap_firstDisplay2Folders();
    if ($ok)
        $ok = bootstrap_firstDisplay3Objects();
    if ($ok)
        $ok = bootstrap_firstDisplay4Puppetmaster();
    if ($ok)
        $ok = bootstrap_firstDisplay5SyncAuthorities();
    if ($ok)
        $ok = bootstrap_firstDisplay6SyncObjects();
    if ($ok)
        $ok = bootstrap_firstDisplay7Subordination();
    if ($ok)
        $ok = bootstrap_firstDisplay8OptionsFile();
    if ($ok)
        $ok = bootstrap_firstDisplay9LocaleEntity();

    echo '<div id="parts">';
    if ($ok)
        echo '&nbsp;&nbsp;OK finished!' . "\n";
    else
        echo '&gt; <span class="error">ERROR!</span>' . "\n";
    echo '<br />&gt; <span id="reload"><a href="">When ready, reload</a></span></div>' . "\n";

    bootstrap_htmlBottom();
}

function bootstrap_firstInitEnv()
{
    global $configurationList, $nebuleCacheIsPublicKey;

    $configurationList['permitWrite'] = true;
    $configurationList['permitWriteObject'] = true;
    $configurationList['permitSynchronizeObject'] = true;
    $configurationList['permitWriteLink'] = true;
    $configurationList['permitSynchronizeLink'] = true;
    $configurationList['permitWriteEntity'] = true;
    $configurationList['permitBufferIO'] = false;
    $nebuleCacheIsPublicKey = array();

    log_reopen('first');
    log_add('Loading', 'info', __FUNCTION__, '529d21e0');

    ob_end_clean();
}

/**
 * Only display multiples reasons of the break and detection of the first run.
 *
 * @return void
 */
function bootstrap_firstDisplay1Breaks(): void
{
    global $bootstrapBreak, $libraryRescueMode;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#1 ' . BOOTSTRAP_NAME . ' break on</span><br/>' . "\n";
    ksort($bootstrapBreak);
    foreach ($bootstrapBreak as $number => $message)
        echo '- [' . $number . '] <span class="error">' . $message . '</span>' . "<br />\n";

    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    echo 'nebule library : ' . BOOTSTRAP_VERSION . ' PHP PP' . "<br />\n";
    if ($libraryRescueMode)
        echo "RESCUE<br />\n";
    echo "</div>\n";
}

/**
 * Display on first run the check of objects and links folders.
 *
 * @return bool
 */
function bootstrap_firstDisplay2Folders(): bool
{
    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#2 folders</span><br/>' . "\n";

    if (!io_checkLinkFolder()) {
        log_add('error links folder', 'error', __FUNCTION__, 'f1d49c43');
        $ok = false;
        echo '<div class="diverror">' . "\n";
        ?>
        Unable to create folder <b><?php echo LIB_LOCAL_LINKS_FOLDER; ?></b> for links.<br/>
        On the same path as <b>index.php</b>, please create folder manually,<br/>
        and give it to web server process.<br/>
        As <i>root</i>, run :<br/>
        <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

mkdir <?php echo LIB_LOCAL_LINKS_FOLDER; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LIB_LOCAL_LINKS_FOLDER; ?>

chmod 755 <?php echo LIB_LOCAL_LINKS_FOLDER; ?></pre>

        <?php
        echo "</div>\n";
    }

    if (!io_checkObjectFolder()) {
        log_add('error objects folder', 'error', __FUNCTION__, 'dc0c86a4');
        $ok = false;
        echo '<div class="diverror">' . "\n";
        ?>
        Unable to create folder <b><?php echo LIB_LOCAL_OBJECTS_FOLDER; ?></b> for objects.<br/>
        On the same path as <b>index.php</b>, please create folder manually,<br/>
        and give it to web server process.<br/>
        As <i>root</i>, run :<br/>
        <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

mkdir <?php echo LIB_LOCAL_OBJECTS_FOLDER; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LIB_LOCAL_OBJECTS_FOLDER; ?>

chmod 755 <?php echo LIB_LOCAL_OBJECTS_FOLDER; ?></pre>

        <?php
        echo "</div>\n";
    }

    if ($ok) {
        log_add('ok folders', 'info', __FUNCTION__, '68c50ba0');
        echo " ok\n";
    }

    echo "</div>\n";

    return $ok;
}

/**
 * Create if not present objects needed by nebule.
 *
 * @return bool
 */
function bootstrap_firstDisplay3Objects(): bool
{
    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#3 needed objects</span><br/>' . "\n";

    foreach (LIB_FIRST_LOCALISATIONS as $data) {
        $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
        if (!io_checkNodeHaveContent($hash)) {
            log_add('need create objects ' . $hash, 'warn', __FUNCTION__, 'ca195598');
            echo '.';
            if (!io_objectWrite($data, $hash)) {
                $ok = false;
                echo 'E';
            }
        }
    }
    foreach (LIB_FIRST_RESERVED_OBJECTS as $data) {
        $hash = obj_getNID($data, lib_getConfiguration('cryptoHashAlgorithm'));
        if (!io_checkNodeHaveContent($hash)) {
            log_add('need create objects ' . $hash, 'warn', __FUNCTION__, 'fc68d2ff');
            echo '.';
            if (!io_objectWrite($data, $hash)) {
                $ok = false;
                echo 'E';
            }
        }
    }

    if ($ok) {
        log_add('ok objects', 'info', __FUNCTION__, '5c7be016');
        echo " ok\n";
    } else
        echo ' <span class=\"error\">nok</span>' . "\n";

    echo "</div>\n";

    return $ok;
}

/**
 * Ask for subordination of the puppetmaster.
 *
 * @return bool
 */
function bootstrap_firstDisplay4Puppetmaster(): bool
{
    global $firstAlternativePuppetmasterEid;

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#4 puppetmaster</span><br/>' . "\n";

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        if (!filter_has_var(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_EID)) {
            log_add('ask subordination oid', 'info', __FUNCTION__, '213a735c');
            ?>
            <form action="" method="get">
                <div>
                    <label for="oid">OID &nbsp;&nbsp;&nbsp;&nbsp; :</label>
                    <input type="text" id="oid" name="<?php echo LIB_ARG_FIRST_PUPPETMASTER_EID; ?>"
                           value="<?php echo LIB_DEFAULT_PUPPETMASTER_EID; ?>"/>
                </div>
                <div>
                    <label for="loc">Location :</label>
                    <input type="text" id="loc" name="<?php echo LIB_ARG_FIRST_PUPPETMASTER_LOC; ?>"
                           value="<?php echo LIB_DEFAULT_PUPPETMASTER_LOCATION; ?>"/>
                </div>
                <div class="button">
                    <button type="submit">Submit</button>
                </div>
            </form>

            <?php
            $ok = false;
        } else {
            $argOID = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_EID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            if (ent_checkIsPublicKey($argOID)) {
                $firstAlternativePuppetmasterEid = $argOID;
            }
            if (filter_has_var(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_LOC)) {
                echo 'try alternative puppetmaster : ' . $argOID . ' ';
                if (nod_checkNID($argOID, false)) {
                    $firstAlternativePuppetmasterEid = $argOID;
                    $argLoc = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_LOC, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                    if (strlen($argLoc) != 0 && filter_var($argLoc, FILTER_VALIDATE_URL) !== false) {
                        echo 'sync...';
                        obj_getDistantContent($argOID, array($argLoc));
                        lnk_getDistantOnLocations($argOID, array($argLoc));
                    }
                }
                if (!ent_checkIsPublicKey($argOID)) {
                    log_add('unable to find alternative puppetmaster oid', 'error', __FUNCTION__, '102c9011');
                    echo " <span class=\"error\">invalid!</span>\n";
                    $argLoc = LIB_DEFAULT_PUPPETMASTER_LOCATION; // TODO not really used ...
                    $firstAlternativePuppetmasterEid = LIB_DEFAULT_PUPPETMASTER_EID;
                }
                echo "<br />\n";
                log_add('define alternative puppetmaster oid = ' . $firstAlternativePuppetmasterEid, 'warn', __FUNCTION__, '10a0bd6d');
                echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: ' . $firstAlternativePuppetmasterEid . "<br />\n";
                log_add('define alternative puppetmaster location = ' . $argLoc, 'info', __FUNCTION__, '6d54e19e');
                echo 'location on &nbsp;&nbsp;&nbsp;&nbsp; : ' . $argLoc . "\n";
            } else
                echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: ' . $firstAlternativePuppetmasterEid . "\n";
        }
    } else {
        $firstpuppetmasterOid = lib_getConfiguration('puppetmaster');
        echo 'forced to ' . $firstpuppetmasterOid . "\n";
    }

    echo "</div>\n";

    return $ok;
}

/**
 * Synchronize the entities needed by nebule.
 *
 * @return bool
 */
function bootstrap_firstDisplay5SyncAuthorities(): bool
{
    global $nebuleLocalAuthorities;

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#5 synchronizing authorities</span><br/>' . "\n";

    $puppetmaster = lib_getConfiguration('puppetmaster');

    echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
    if (!ent_checkPuppetmaster($puppetmaster)) {
        echo 'sync... ';
        ent_syncPuppetmaster($puppetmaster);
    }
    if (ent_checkPuppetmaster($puppetmaster)) {
        echo $puppetmaster . ' ';
        echo 'ok';
    } else
        echo " <span class=\"error\">invalid!</span>\n";
    echo "<br/>\n";
    flush();

    // Activation comme autorité locale.
    $nebuleLocalAuthorities[0] = $puppetmaster;

    echo 'sync for authorities references';
    lnk_getDistantOnLocations(LIB_RID_SECURITY_AUTHORITY, array());
    echo '.';
    lnk_getDistantOnLocations(LIB_RID_CODE_AUTHORITY, array());
    echo '.';
    lnk_getDistantOnLocations(LIB_RID_TIME_AUTHORITY, array());
    echo '.';
    lnk_getDistantOnLocations(LIB_RID_DIRECTORY_AUTHORITY, array());
    echo '.';
    echo "<br/>\n";
    flush();

    // Try to find others autorities.
    $securityAuthorities = ent_getSecurityAuthorities(true);
    $codeAuthorities = ent_getCodeAuthorities(true);
    $timeAuthorities = ent_getTimeAuthorities(true);
    $directoryAuthorities = ent_getDirectoryAuthorities(true);

    echo 'security authorities &nbsp;: ';
    if (sizeof($securityAuthorities) != 0) {
        if (!ent_checkSecurityAuthorities($securityAuthorities)) {
            echo 'sync... ';
            ent_syncAuthorities($securityAuthorities);
        }
        if (ent_checkSecurityAuthorities($securityAuthorities)) {
            foreach ($securityAuthorities as $authority)
                echo $authority . ' ';
            echo 'ok';
        } else {
            echo " <span class=\"error\">invalid!</span>\n";
            $ok = false;
        }
    } else {
        echo " <span class=\"error\">empty!</span>\n";
        $ok = true; // TODO false;
    }
    echo "<br/>\n";
    flush();

    echo 'code authorities &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
    if (sizeof($codeAuthorities) != 0) {
        if (!ent_checkCodeAuthorities($codeAuthorities)) {
            echo 'sync... ';
            ent_syncAuthorities($codeAuthorities);
        }
        if (ent_checkCodeAuthorities($codeAuthorities)) {
            foreach ($codeAuthorities as $authority)
                echo $authority . ' ';
            echo 'ok';
        } else {
            echo " <span class=\"error\">invalid!</span>\n";
            $ok = false;
        }
    } else {
        echo " <span class=\"error\">empty!</span>\n";
        $ok = true; // TODO false;
    }
    echo "<br/>\n";
    flush();

    echo 'time authorities &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
    if (sizeof($timeAuthorities) != 0) {
        if (!ent_checkTimeAuthorities($timeAuthorities)) {
            echo 'sync... ';
            ent_syncAuthorities($timeAuthorities);
        }
        if (ent_checkTimeAuthorities($timeAuthorities)) {
            foreach ($timeAuthorities as $authority)
                echo $authority . ' ';
            echo 'ok';
        } else {
            echo " <span class=\"error\">invalid!</span>\n";
            $ok = false;
        }
    } else {
        echo " <span class=\"error\">empty!</span>\n";
        $ok = true; // TODO false;
    }
    echo "<br/>\n";
    flush();

    echo 'directory authorities : ';
    if (sizeof($directoryAuthorities) != 0) {
        if (!ent_checkDirectoryAuthorities($directoryAuthorities)) {
            echo 'sync... ';
            ent_syncAuthorities($directoryAuthorities);
        }
        if (ent_checkDirectoryAuthorities($directoryAuthorities)) {
            foreach ($directoryAuthorities as $authority)
                echo $authority . ' ';
            echo 'ok';
        } else {
            echo " <span class=\"error\">invalid!</span>\n";
            $ok = false;
        }
    } else {
        echo " <span class=\"error\">empty!</span>\n";
        $ok = true; // TODO false;
    }
    echo "<br/>\n";
    flush();

    if ($ok)
        log_add('ok sync authorities', 'info', __FUNCTION__, 'c5b55957');

    echo "</div>\n";

    return $ok;
}

/**
 * Synchronisation des objets sur internet pour fonctionner.
 *
 * @return bool
 */
function bootstrap_firstDisplay6SyncObjects(): bool
{
    global $codeBranchNID;

    $ok = true;
    $refAppsID = LIB_RID_INTERFACE_APPLICATIONS;
    $refLibID = LIB_RID_INTERFACE_LIBRARY;
    $refBootID = LIB_RID_INTERFACE_BOOTSTRAP;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#6 synchronizing objets</span><br/>' . "\n";

    // Write locations objects content
    echo 'objects &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
    foreach (LIB_FIRST_LOCALISATIONS as $data) {
        obj_setContent($data);
        echo '.';
    }

    // Write reserved objects content
    foreach (LIB_FIRST_RESERVED_OBJECTS as $data) {
        obj_setContent($data);
        echo '.';
    }
    flush();

    // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
    if (!io_checkNodeHaveLink($refAppsID)
        || !io_checkNodeHaveLink($refLibID)
        || !io_checkNodeHaveLink($refBootID)
    ) {
        log_add('need sync reference objects', 'warn', __FUNCTION__, '0f21ad26');

        echo "<br />\nbootstrap RID &nbsp;&nbsp;&nbsp;&nbsp;: " . $refBootID . ' ';
        lnk_getDistantOnLocations($refBootID, LIB_FIRST_LOCALISATIONS);

        app_getCodeBranch();
        echo "<br />\ncode branch RID &nbsp;&nbsp;: " . $codeBranchNID . ' ';
        lnk_getDistantOnLocations($codeBranchNID, LIB_FIRST_LOCALISATIONS);

        echo "<br />\nlibrary RID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: " . $refLibID . ' ';
        lnk_getDistantOnLocations($refLibID, LIB_FIRST_LOCALISATIONS);
        flush();

        echo "<br />\napplications &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: " . $refAppsID;
        lnk_getDistantOnLocations($refAppsID, LIB_FIRST_LOCALISATIONS);
        flush();
    }

    if (io_checkNodeHaveLink($refAppsID)
        && io_checkNodeHaveLink($refLibID)
        && io_checkNodeHaveLink($refBootID)
    ) {
        log_add('ok sync objects', 'info', __FUNCTION__, '4473358f');
        echo "ok\n";
    } else
        $ok = false;

    echo "</div>\n";

    return $ok;
}


// ------------------------------------------------------------------------------------------
/**
 * Ask for subordination of the local entity.
 *
 * @return bool
 */
function bootstrap_firstDisplay7Subordination(): bool
{
    global $firstAlternativePuppetmasterEid, $firstSubordinationEID;

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#7 subordination</span><br/>' . "\n";

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        if (!filter_has_var(INPUT_GET, LIB_ARG_FIRST_SUBORD_EID)) {
            log_add('ask subordination oid', 'info', __FUNCTION__, '213a735c');
            ?>
            <form action="" method="get">
                <div>
                    <label for="oid">OID &nbsp;&nbsp;&nbsp;&nbsp; :</label>
                    <input type="text" id="oid" name="<?php echo LIB_ARG_FIRST_SUBORD_EID; ?>"/>
                </div>
                <div>
                    <label for="loc">Location :</label>
                    <input type="text" id="loc" name="<?php echo LIB_ARG_FIRST_SUBORD_LOC; ?>"/>
                    <input type="hidden" id="puppetmaster" name="<?php echo LIB_ARG_FIRST_PUPPETMASTER_EID; ?>"
                           value="<?php echo $firstAlternativePuppetmasterEid; ?>"/>
                </div>
                <div class="button">
                    <button type="submit">Submit</button>
                </div>
            </form>

            <?php
            $ok = false;
        } else {
            $argOID = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_SUBORD_EID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            if (nod_checkNID($argOID, false)) {
                echo 'try alternative puppetmaster : ' . $argOID . ' ';
                $firstSubordinationEID = $argOID;
                $argLoc = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_SUBORD_LOC, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                if (strlen($argLoc) != 0 && filter_var($argLoc, FILTER_VALIDATE_URL) !== false) {
                    echo 'sync...';
                    obj_getDistantContent($argOID, array($argLoc));
                    lnk_getDistantOnLocations($argOID, array($argLoc));
                }
            } else {
                log_add('unable to find subordination oid', 'error', __FUNCTION__, '5cd18917');
                echo " <span class=\"error\">invalid!</span>\n";
                $argLoc = '';
                $firstSubordinationEID = '';
            }
            echo "<br />\n";
            log_add('define subordination oid = ' . $firstSubordinationEID, 'warn', __FUNCTION__, 'a875618e');
            echo 'subordination to : ' . $firstSubordinationEID . "<br />\n";
            log_add('define subordination location = ' . $argLoc, 'info', __FUNCTION__, 'c1c943a5');
            echo 'location on &nbsp;&nbsp;&nbsp;&nbsp; : ' . $argLoc . "\n";
        }
    } else {
        $firstSubordinationEID = lib_getConfiguration('subordinationEntity');
        echo 'subordination to ' . $firstSubordinationEID . "\n";
    }

    echo "</div>\n";

    return $ok;
}


// ------------------------------------------------------------------------------------------
/**
 * Crée le fichier des options par défaut.
 *
 * @return bool
 */
function bootstrap_firstDisplay8OptionsFile(): bool
{
    global $firstAlternativePuppetmasterEid, $firstSubordinationEID;

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#8 options file</span><br/>' . "\n";

    // Generate local configuration with default options.
    $defaultOptions = "# Generated by the " . BOOTSTRAP_NAME . ", part of the " . BOOTSTRAP_AUTHOR . ".\n";
    $defaultOptions .= "# " . BOOTSTRAP_SURNAME . "\n";
    $defaultOptions .= "# Version : " . BOOTSTRAP_VERSION . "\n";
    $defaultOptions .= "# http://" . BOOTSTRAP_WEBSITE . "\n";
    $defaultOptions .= "\n";
    $defaultOptions .= "# nebule php\n";
    $defaultOptions .= "# Options writen here are write-protected for the library and all applications.\n";
    foreach (LIB_CONFIGURATIONS_DEFAULT as $option => $value) {
        $prefix = '#';
        if ($option == 'puppetmaster' && $firstAlternativePuppetmasterEid != '') {
            $value = $firstAlternativePuppetmasterEid;
            $prefix = '';
        }
        if ($option == 'subordinationEntity' && $firstSubordinationEID != '') {
            $value = $firstSubordinationEID;
            $prefix = '';
        }
        $defaultOptions .= $prefix . $option . ' = ';
        if (LIB_CONFIGURATIONS_TYPE[$option] == 'boolean') {
            if ($value === true)
                $defaultOptions .= 'true';
            else
                $defaultOptions .= 'false';
        } else
            $defaultOptions .= (string)$value;
        $defaultOptions .= "\n";
    }

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        log_add('need create options file', 'warn', __FUNCTION__, '58d07f71');
        echo "creating<br />\n";
        file_put_contents(LIB_LOCAL_ENVIRONMENT_FILE, $defaultOptions);
    }

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        echo ' <span class="error">ERROR!</span><br />' . "\n";
        echo '<div class="diverror">' . "\n";
        ?>
        Unable to create options file <b><?php echo LIB_LOCAL_ENVIRONMENT_FILE; ?></b> .<br/>
        On the same path as <b>index.php</b>, please create file manually.<br/>
        As <i>root</i>, run :<br/>
        <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

cat &gt; <?php echo LIB_LOCAL_ENVIRONMENT_FILE; ?> &lt;&lt; EOF
<?php echo $defaultOptions; ?>
EOF

chmod 644 <?php echo LIB_LOCAL_ENVIRONMENT_FILE; ?>
</pre>

        <?php
        echo "</div>\n";
        $ok = false;
    } else {
        log_add('ok have options file', 'info', __FUNCTION__, '91e9b5bd');
        echo "ok\n";
    }

    echo "</div>\n";

    return $ok;
}



// ------------------------------------------------------------------------------------------
/**
 * Crée le fichier des options par défaut.
 *
 * @return bool
 */
function bootstrap_firstDisplay9LocaleEntity(): bool
{
    global $nebulePublicEntity, $nebulePrivateEntity, $nebulePasswordEntity;

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#9 local entity for server</span><br/>' . "\n";
    if (file_put_contents(LIB_LOCAL_ENTITY_FILE, '0') !== false) {
        echo 'new server entity<br/>' . "\n";

        // Generate new password for new local entity.
        $nebulePasswordEntity = '';
        $newPasswd = openssl_random_pseudo_bytes(LIB_FIRST_GENERATED_PASSWORD_SIZE * 20);
        $nebulePasswordEntity .= preg_replace('/[^[:print:]]/', '', $newPasswd);
        $nebulePasswordEntity = (string)substr($nebulePasswordEntity, 0, LIB_FIRST_GENERATED_PASSWORD_SIZE);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $newPasswd = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789';
        /** @noinspection PhpUnusedLocalVariableInspection */
        $newPasswd = null;

        $nebulePublicEntity = '0';
        $nebulePrivateEntity = '0';
        // Génère une nouvelle entité.
        ent_generate(
            lib_getConfiguration('cryptoAsymmetricAlgorithm'),
            lib_getConfiguration('cryptoHashAlgorithm'),
            $nebulePublicEntity,
            $nebulePrivateEntity,
            $nebulePasswordEntity
        );
        log_add('switch to new entity ' . $nebulePublicEntity, 'warn', __FUNCTION__, '94c27df0');

        // Définit l'entité comme entité instance du serveur.
        file_put_contents(LIB_LOCAL_ENTITY_FILE, $nebulePublicEntity);

        // Calcul le nom.
        $hexvalue = preg_replace('/[[:^xdigit:]]/', '', $nebulePublicEntity);
        $genname = hex2bin($hexvalue . $hexvalue);
        $name = '';
        // Filtrage des caractères du nom dans un espace restreint.
        for ($i = 0; $i < strlen($genname); $i++) {
            $a = ord($genname[$i]);
            if (($a > 96 && $a < 123)) {
                $name .= $genname[$i];
                // Insertion de voyelles.
                if (($i % 3) == 0) {
                    $car = hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % 14;
                    switch ($car) {
                        case 0 :
                        case 6 :
                            $name .= 'a';
                            break;
                        case 1 :
                        case 7 :
                        case 11 :
                        case 13 :
                            $name .= 'e';
                            break;
                        case 2 :
                        case 8 :
                            $name .= 'i';
                            break;
                        case 3 :
                        case 9 :
                            $name .= 'o';
                            break;
                        case 4 :
                        case 10 :
                        case 12 :
                            $name .= 'u';
                            break;
                        case 5 :
                            $name .= 'y';
                            break;
                    }
                }
            }
        }
        $name = substr($name . 'robott', 0, LIB_FIRST_GENERATED_NAME_SIZE);

        // Enregistrement du nom.
        obj_setContentAsText($name);
        $newLink = lnk_generateSign('',
            'l',
            $nebulePublicEntity,
            obj_getNID($name, lib_getConfiguration('cryptoHashAlgorithm')),
            obj_getNID('nebule/objet/nom', lib_getConfiguration('cryptoHashAlgorithm')));
        lnk_write($newLink);

        ?>

        public ID &nbsp;: <?php echo $nebulePublicEntity; ?><br/>
        private ID : <?php echo $nebulePrivateEntity; ?>

        <div class="important">
            name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $name; ?><br/>
            public ID : <?php echo $nebulePublicEntity; ?><br/>
            password &nbsp;: <?php echo htmlspecialchars($nebulePasswordEntity); ?><br/>
            Please keep and save securely thoses private informations!
        </div>
        <?php
    } else {
        file_put_contents(LIB_LOCAL_ENTITY_FILE, '0');
        echo '<span class="error">ERROR!</span><br />' . "\n";
        echo '<div class="diverror">' . "\n";
        ?>
        Unable to create local entity file <b><?php echo LIB_LOCAL_ENTITY_FILE; ?></b> .<br/>
        On the same path as <b>index.php</b>, please create file manually.<br/>
        As <i>root</i>, run :<br/>
        <pre>cd <?php echo getenv('DOCUMENT_ROOT'); ?>

touch <?php echo LIB_LOCAL_ENTITY_FILE; ?>

chown <?php echo getenv('APACHE_RUN_USER') . '.' . getenv('APACHE_RUN_GROUP') . ' ' . LIB_LOCAL_ENTITY_FILE; ?>

chmod 644 <?php echo LIB_LOCAL_ENTITY_FILE; ?>
</pre>

        <?php
        echo "</div>\n";
    }
    echo "</div>\n";

    $nebulePasswordEntity = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789';
    $nebulePasswordEntity = null;

    return $ok;
}



/*
 *
 *
 *
 *

 ==/ 9 /===================================================================================
 PART9 : Display of application 0 web page to select application to run.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayApplication0()
{
    global $nebuleInstance;

    // Initialisation des logs
    log_reopen('app0');
    log_add('Loading', 'info', __FUNCTION__, '314e6e9b');

    echo 'CHK';
    ob_end_clean();

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    echo '<div id="appslist">';
    // Extraire la liste des applications disponibles.
    $refAppsID = $nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS);
    $instanceAppsID = new Node($nebuleInstance, $refAppsID);
    $applicationsList = array();
    $signersList = array();
    $hashTarget = '';

    // Liste les applications reconnues par le maître du code.
    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $refAppsID,
        'bl/rl/nid3' => $refAppsID,
    );
    $instanceAppsID->getLinks($links, $filter, null);


    $linksList = $instanceAppsID->getLinksOnFields($nebuleInstance->getPuppetmaster(), // FIXME
        '', 'f', $refAppsID, '', $refAppsID);
    $link = null;
    foreach ($linksList as $link) {
        $hashTarget = $link->getHashTarget();
        $applicationsList[$hashTarget] = $hashTarget;
        $signersList[$hashTarget] = $link->getHashSigner();
    }

    // Liste les applications reconnues par l'entité instance du serveur, si autorité locale et pas en mode de récupération.
    if ($nebuleInstance->getConfigurationInstance()->getOptionAsBoolean('permitInstanceEntityAsAuthority')
        && !$nebuleInstance->getModeRescue()
    ) {
        $linksList = $instanceAppsID->getLinksOnFields($nebuleInstance->getInstanceEntity(), '', 'f', $refAppsID, '', $refAppsID);
        foreach ($linksList as $link) {
            $hashTarget = $link->getHashTarget();
            $applicationsList[$hashTarget] = $hashTarget;
            $signersList[$hashTarget] = $link->getHashSigner();
        }
    }

    // Liste les applications reconnues par l'entité par défaut, si autorité locale et pas en mode de récupération.
    if ($nebuleInstance->getConfigurationInstance()->getOptionAsBoolean('permitDefaultEntityAsAuthority')
        && !$nebuleInstance->getModeRescue()
    ) {
        $linksList = $instanceAppsID->getLinksOnFields($nebuleInstance->getDefaultEntity(), '', 'f', $refAppsID, '', $refAppsID);
        foreach ($linksList as $link) {
            $hashTarget = $link->getHashTarget();
            $applicationsList[$hashTarget] = $hashTarget;
            $signersList[$hashTarget] = $link->getHashSigner();
        }
    }
    unset($refAppsID, $linksList, $link, $hashTarget, $instanceAppsID);

    // Affiche la page d'interruption.
    echo '<a href="/?b">';
    echo '<div class="apps" style="background:#000000;">';
    echo '<span class="appstitle">Nb</span><br /><span class="appsname">break</span>';
    echo "</div></a>\n";

    // Lister les applications.
    $application = '';
    foreach ($applicationsList as $application) {
        $instance = new Node($nebuleInstance, $application);

        // Recherche si l'application est activée par l'entité instance de serveur.
        // Ou si l'application est en liste blanche.
        // Ou si c'est l'application par défaut.
        $activated = false;
        foreach (nebule::ACTIVE_APPLICATIONS_WHITELIST as $item) {
            if ($application == $item)
                $activated = true;
        }
        if ($application == $nebuleInstance->getConfigurationInstance()->getOptionAsString('defaultApplication'))
            $activated = true;
        if (!$activated) {
            $refActivated = $nebuleInstance->getNIDfromData(nebule::REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE);
            $linksList = $instance->getLinksOnFields($nebuleInstance->getInstanceEntity(), '', 'f', $application, $refActivated, $application);
            if (sizeof($linksList) != 0)
                $activated = true;
            unset($linksList);
        }

        // En fonction de l'état d'activation, affiche ou non l'application.
        if (!$activated)
            continue;

        $color = '#' . substr($application . '000000', 0, 6);
        //$colorSigner = '#'.substr($signersList[$application].'000000',0,6);
        $title = $instance->getName();
        $shortName = substr($instance->getSurname() . '--', 0, 2);
        $shortName = strtoupper(substr($shortName, 0, 1)) . strtolower(substr($shortName, 1, 1));
        echo '<a href="/?' . LIB_ARG_SWITCH_APPLICATION . '=' . $application . '">';
        echo '<div class="apps" style="background:' . $color . ';">';
        echo '<span class="appstitle">' . $shortName . '</span><br /><span class="appsname">' . $title . '</span>';
        echo "</div></a>\n";
    }
    unset($application, $applicationsList, $instance, $color, $title, $shortName);

    echo "</div>\n";
    echo '<div id="sync">'."\n";
    echo "</div>\n";
    bootstrap_htmlBottom();
}



/*
 *
 *
 *
 *

 ==/ 10 /==================================================================================
 PART10 : Display of application 1 web page to display documentation of nebule.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayApplication1()
{
    global $nebuleInstance, $nebuleLibLevel, $nebuleLibVersion, $nebuleLicence, $nebuleAuthor, $nebuleWebsite;

    // Initialisation des logs
    log_reopen('app1');
    log_add('Loading', 'info', __FUNCTION__, 'a4e4acfe');

    echo 'CHK';
    ob_end_clean();

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    // Instancie la classe de la documentation.
    $instance = new nebdoctech($nebuleInstance);

    // Affiche la documentation.
    echo '<div id="layout_documentation">' . "\n";
    echo ' <div id="title_documentation"><p>Documentation technique de ' . $nebuleInstance->__toString() . '<br />' . "\n";
    echo '  Version ' . $nebuleInstance->getConfigurationInstance()->getOptionAsString('defaultLinksVersion')
        . ' - ' . $nebuleLibVersion . ' ' . $nebuleLibLevel . '<br />' . "\n";
    echo '  (c) ' . $nebuleLicence . ' ' . $nebuleAuthor . ' - <a href="' . $nebuleWebsite . '">' . $nebuleWebsite . "</a></p></div>\n";
    echo ' <div id="content_documentation">' . "\n";
    $instance->display_content();
    echo " </div>\n";
    echo "</div>\n";

    bootstrap_htmlBottom();
}



/*
 *
 *
 *
 *

 ==/ 11 /==================================================================================
 PART11 : Display of application 2 default application.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayApplication2()
{
    // Initialisation des logs
    log_reopen('app2');
    log_add('Loading', 'info', __FUNCTION__, '3a5c4178');

    echo 'CHK';
    ob_end_clean();

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    echo '<div class="layout-main">' . "\n";
    echo ' <div class="layout-content">' . "\n";
    echo '  <img alt="nebule" id="logo" src="<?php echo LIB_APPLICATION_LOGO_LIGHT; ?>"/>' . "\n";
    echo " </div>\n";
    echo "</div>\n";

    bootstrap_htmlBottom();
}



/*
 *
 *
 *
 *

 ==/ 12 /==================================================================================
 PART12 : Main display router.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayRouter()
{
    global $bootstrapBreak,
           $libraryRescueMode,
           $needFirstSynchronization,
           $bootstrapInlineDisplay,
           $bootstrapApplicationID,
           $bootstrapApplicationNoPreload,
           $bootstrapApplicationStartID,
           $nebuleInstance,
           $bootstrapServerEntityDisplay,
           $bootstrapLibraryID;

    // Fin de la bufferisation de la sortie avec effacement du buffer.
    // Ecrit dans le buffer pour test, ne devra jamais apparaître.
    echo 'CHK';
    // Tout ce qui aurait éventuellement essayé d'être affiché est perdu.
    ob_end_clean();

    if (sizeof($bootstrapBreak) > 0) {
        if ($needFirstSynchronization && !$bootstrapInlineDisplay) {
            log_add('load first', 'info', __FUNCTION__, '63d9bc00');
            bootstrap_displayApplicationFirst();
        } elseif ($bootstrapServerEntityDisplay) {
            if (file_exists(LIB_LOCAL_ENTITY_FILE))
                echo file_get_contents(LIB_LOCAL_ENTITY_FILE, false, null, -1, lib_getConfiguration('ioReadMaxData'));
            else
                echo '0';
        } else {
            log_add('load break', 'info', __FUNCTION__, '4abf554b');
            if ($bootstrapInlineDisplay)
                bootstrap_inlineDisplayOnBreak();
            else
                bootstrap_displayOnBreak();
        }
    } else {
        unset($bootstrapBreak, $libraryRescueMode, $bootstrapInlineDisplay);

        // Ferme les I/O de la bibliothèque PHP PP.
        io_close();

        log_add('load application ' . $bootstrapApplicationID, 'info', __FUNCTION__, 'aab236ff');

        if ($bootstrapApplicationID == '0')
            bootstrap_displayApplication0();
        elseif ($bootstrapApplicationID == '1')
            bootstrap_displayApplication1();
        elseif ($bootstrapApplicationID == '2')
            bootstrap_displayApplication2();
        else {
            // Si tout est déjà préchargé, on déserialise.
            if (isset($bootstrapApplicationInstanceSleep)
                && $bootstrapApplicationInstanceSleep != ''
                && isset($bootstrapApplicationDisplayInstanceSleep)
                && $bootstrapApplicationDisplayInstanceSleep != ''
                && isset($bootstrapApplicationActionInstanceSleep)
                && $bootstrapApplicationActionInstanceSleep != ''
                && isset($bootstrapApplicationTraductionInstanceSleep)
                && $bootstrapApplicationTraductionInstanceSleep != ''
            ) {
                bootstrap_includeApplication();

                $applicationName = Application::APPLICATION_NAME;

                // Change les logs au nom de l'application.
                log_reopen($applicationName);

                // Désérialise les instances.
                try {
                    $applicationInstance = unserialize($bootstrapApplicationInstanceSleep);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application unserialize error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, 'de82bc8b');
                }
                try {
                    $applicationDisplayInstance = unserialize($bootstrapApplicationDisplayInstanceSleep);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application traduction unserialize error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '8e344763');
                }
                try {
                    $applicationActionInstance = unserialize($bootstrapApplicationActionInstanceSleep);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application display unserialize error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, 'b15fd5d3');
                }
                try {
                    $applicationTraductionInstance = unserialize($bootstrapApplicationTraductionInstanceSleep);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application action unserialize error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '972ddf39');
                }

                // Initialisation de réveil de l'instance de l'application.
                $applicationInstance->initialisation2();

                // Si la requête web est un téléchargement d'objet ou de lien, des accélérations peuvent être prévues dans ce cas.
                if (!$applicationInstance->askDownload()) {
                    // Initialisation de réveil des instances.
                    $applicationTraductionInstance->initialisation2();
                    $applicationDisplayInstance->initialisation2();
                    $applicationActionInstance->initialisation2();

                    // Réalise les tests de sécurité.
                    $applicationInstance->checkSecurity();
                }

                // Appel de l'application.
                $applicationInstance->router();
            } elseif ($bootstrapApplicationNoPreload) {
                // Si l'application ne doit être préchargée,
                //   réalise maintenant le préchargement de façon transparente et lance l'application.
                // Ainsi, le préchargement n'est pas fait sur une page web à part.

                log_add('load application without preload ' . $bootstrapApplicationID, 'info', __FUNCTION__, 'e01ea813');

                bootstrap_includeApplication();

                $applicationName = Application::APPLICATION_NAME;

                // Change les logs au nom de l'application.
                log_reopen($applicationName);

                try {
                    $applicationInstance = new Application($nebuleInstance);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '202824cb');
                }
                try {
                    $applicationTraductionInstance = new Traduction($applicationInstance);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application traduction load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '585648a2');
                }
                try {
                    $applicationDisplayInstance = new Display($applicationInstance);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application display load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '4c7da4e2');
                }
                try {
                    $applicationActionInstance = new Action($applicationInstance);
                } catch (\Error $e) {
                    log_reopen(BOOTSTRAP_NAME);
                    log_add('Application action load error ('  . $e->getCode() . ') : ' . $e->getFile() . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error', __FUNCTION__, '3c042de3');
                }

                // Initialisation des instances.
                $applicationInstance->initialisation();
                $applicationTraductionInstance->initialisation();
                $applicationDisplayInstance->initialisation();
                $applicationActionInstance->initialisation();

                // Réalise les tests de sécurité.
                $applicationInstance->checkSecurity();

                // Appel de l'application.
                $applicationInstance->router();
            } else
                bootstrap_displayPreloadApplication();

            // Ouverture de la session PHP.
            session_start();

            // Sauve les ID dans la session PHP.
            $_SESSION['bootstrapApplicationID'] = $bootstrapApplicationID;
            $_SESSION['bootstrapApplicationStartID'] = $bootstrapApplicationStartID;
            $_SESSION['bootstrapApplicationStartsID'][$bootstrapApplicationStartID] = $bootstrapApplicationID;
            $_SESSION['bootstrapLibrariesID'] = $bootstrapLibraryID;

            // Sérialise les instances et les sauve dans la session PHP.
            $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationStartID] = serialize($applicationInstance);
            $_SESSION['bootstrapApplicationsDisplayInstances'][$bootstrapApplicationStartID] = serialize($applicationDisplayInstance);
            $_SESSION['bootstrapApplicationsActionInstances'][$bootstrapApplicationStartID] = serialize($applicationActionInstance);
            $_SESSION['bootstrapApplicationsTraductionInstances'][$bootstrapApplicationStartID] = serialize($applicationTraductionInstance);
            $_SESSION['bootstrapLibrariesInstances'][$bootstrapLibraryID] = serialize($nebuleInstance);

            // Fermeture de la session avec écriture.
            session_write_close();
        }
    }

    log_reopen(BOOTSTRAP_NAME);
}

function bootstrap_logMetrology()
{
    global $nebuleInstance, $nebuleMetrologyTimers;

    $memory = sprintf('%01.3f', memory_get_peak_usage() / 1024 / 1024);

    $timers = '';
    foreach ($nebuleMetrologyTimers as $i => $v)
        $timers .= " $i=$v";

    // Metrology on logs.
    if (is_a($nebuleInstance, 'nebule')) {
        log_add('Mp=' . $memory . 'Mb'
            . $timers
            . ' Lr=' . lib_getMetrology('lr') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkRead()
            . ' Lv=' . lib_getMetrology('lv') . '+' . $nebuleInstance->getMetrologyInstance()->getLinkVerify()
            . ' Or=' . lib_getMetrology('or') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectRead()
            . ' Ov=' . lib_getMetrology('ov') . '+' . $nebuleInstance->getMetrologyInstance()->getObjectVerify()
            . ' (PP+POO) -'
            . ' LC=' . $nebuleInstance->getCacheInstance()->getCacheLinkSize()
            . ' OC=' . $nebuleInstance->getCacheInstance()->getCacheObjectSize()
            . ' EC=' . $nebuleInstance->getCacheInstance()->getCacheEntitySize()
            . ' GC=' . $nebuleInstance->getCacheInstance()->getCacheGroupSize()
            . ' CC=' . $nebuleInstance->getCacheInstance()->getCacheConversationSize(),
            'info',
            __FUNCTION__,
            '0d99ad8b');
    } else {
        log_add('Mp=' . $memory . 'Mb'
            . $timers
            . ' Lr=' . lib_getMetrology('lr')
            . ' Lv=' . lib_getMetrology('lv')
            . ' Or=' . lib_getMetrology('or')
            . ' Ov=' . lib_getMetrology('ov')
            . ' (PP)',
            'info',
            __FUNCTION__,
            '52d76692');
    }
}

function main()
{
    if (!lib_init())
        bootstrap_setBreak('21', 'Library init error');

    bootstrap_getUserBreak();
    bootstrap_getInlineDisplay();
    bootstrap_getCheckFingerprint();
    bootstrap_getDisplayServerEntity();
    bootstrap_getFlushSession();
    bootstrap_getUpdate();
    bootstrap_getSwitchApplication();
    bootstrap_setPermitOpenFileCode();
    lib_setMetrologyTimer('tB');

    $bootstrapLibraryInstanceSleep = '';
    bootstrap_findLibraryPOO($bootstrapLibraryInstanceSleep);
    bootstrap_includeLibraryPOO();
    bootstrap_loadLibraryPOO($bootstrapLibraryInstanceSleep);
    lib_setMetrologyTimer('tL');

    bootstrap_findApplication();
    bootstrap_getApplicationPreload();
    bootstrap_displayRouter();
    lib_setMetrologyTimer('tA');
    bootstrap_logMetrology();
}

// OK, now play...
main();

?>
