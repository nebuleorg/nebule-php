<?php
declare(strict_types=1);
namespace Nebule\Bootstrap;
use Nebule\Library\Cache;
use Nebule\Library\Crypto;
use Nebule\Library\io;
use Nebule\Library\nebule;
#use Random\RandomException;

const BOOTSTRAP_NAME = 'bootstrap';
const BOOTSTRAP_SURNAME = 'nebule/bootstrap';
const BOOTSTRAP_AUTHOR = 'Project nebule';
const BOOTSTRAP_VERSION = '020250630';
const BOOTSTRAP_LICENCE = 'GNU GPL 2010-2025';
const BOOTSTRAP_WEBSITE = 'www.nebule.org';
const BOOTSTRAP_CODING = 'application/x-httpd-php';
const BOOTSTRAP_FUNCTION_VERSION = '020241123';
// ------------------------------------------------------------------------------------------



/*
|------------------------------------------------------------------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------------------------------------------------------------------
|
|  [DE] Jede Änderung dieses Codes führt zu einer Änderung seines Fingerabdrucks und führt daher automatisch zu seiner Ungültigkeit!
|  [EN] Any modification of this code will result in a modification of its hash digest and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo tanto lugar automáticamente a su anulación!
|  [FR] Toute modification de ce code entraînera une modification de son empreinte et entraînera donc automatiquement son invalidation !
|  [IT] Ogni modifica di questo codice comporterà una modifica della sua impronta e quindi ne causerà automaticamente l'invalidazione!
|  [PL] Każda modyfikacja tego kodu spowoduje zmianę jego odcisku i automatycznie doprowadzi do jego unieważnienia!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і, відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------------------------------------------------------------------
*/



/*
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 *   License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any
 *   later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 *   details.
 *
 * You should have received a copy of the GNU General Public License along with this program. See on the end of file.
 *   If not, see https://www.gnu.org/licenses/.
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
 PART9 : Display of application 0 default application.
 PART10 : Main display router.
 ------------------------------------------------------------------------------------------
*/



const PHP_VERSION_MINIMUM = '8.0.0';
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
try {
    /** @noinspection PhpUnusedLocalVariableInspection */
    $loggerSessionID = bin2hex(random_bytes(6));
} catch (\Exception $e) {
    /** @noinspection PhpUnusedLocalVariableInspection */
    $loggerSessionID = '0123456789ab';
}
$metrologyStartTime = (float)microtime(true);
$permitLogsOnDebugFile = false;

function log_init(string $name): void {
    global $loggerSessionID;
    openlog($name . '/' . $loggerSessionID, LOG_NDELAY, LOG_USER);
}

function log_reopen(string $name): void {
    closelog();
    log_init($name);
}

function log_initDebugFile(): void {
    global $metrologyStartTime, $permitLogsOnDebugFile;
    syslog(LOG_INFO, 'LogT=0.000000 LogT0=' . $metrologyStartTime . ' LogL="info" LogI="76941959" LogM="start ' . BOOTSTRAP_NAME . '"');

    if (file_exists(LIB_LOCAL_OBJECTS_FOLDER . '/' . LIB_LOCAL_DEBUG_FILE)
        && !filter_has_var(INPUT_GET, LIB_ARG_INLINE_DISPLAY)
        //&& !filter_has_var(INPUT_POST, LIB_ARG_INLINE_DISPLAY)
    )
        unlink(LIB_LOCAL_OBJECTS_FOLDER . '/' . LIB_LOCAL_DEBUG_FILE);

    if (lib_getOption('permitLogsOnDebugFile'))
    {
        file_put_contents(LIB_LOCAL_OBJECTS_FOLDER . '/' . LIB_LOCAL_DEBUG_FILE, 'LogT=0.000000 LogT0=' . $metrologyStartTime . ' LogL="info" LogI="76941959" LogM="start ' . BOOTSTRAP_NAME . "\"\n", FILE_APPEND);
        syslog(LOG_INFO, 'LogT=0.000000 LogL="info" LogI="50615f80" LogM="create debug file ' . LIB_LOCAL_OBJECTS_FOLDER . '/' . LIB_LOCAL_DEBUG_FILE . '"');
        $permitLogsOnDebugFile = true;
    }
}

function log_add(string $message, string $level = 'msg', string $function = '', string $id = '00000000'): void {
    global $metrologyStartTime, $permitLogsOnDebugFile;
    $timestamp = sprintf('%01.6f', microtime(true) - $metrologyStartTime);
    if ($level != 'debug')
        syslog(LOG_INFO, 'LogT=' . $timestamp . ' LogL="' . $level . '" LogI="' . $id . '" LogF="' . $function . '" LogM="' . $message . '"');

    if ($permitLogsOnDebugFile)
        file_put_contents(LIB_LOCAL_OBJECTS_FOLDER . '/' . LIB_LOCAL_DEBUG_FILE, 'LogT=' . $timestamp . ' LogL="' . $level . '" LogI="' . $id . '" LogF="' . $function . '" LogM="' . $message . "\"\n", FILE_APPEND);
}

function log_addAndDisplay(string $message, string $level = 'msg', string $function = '', string $id = '00000000'): void {
    log_add($message, $level, $function, $id);
    echo "$level($function) : $id : $message";
}

// Initialize logs.
log_init(BOOTSTRAP_NAME);



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
 * Variable de détection de l'affichage inserré en ligne.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapInlineDisplay = false;

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
 * ID of the last signer find on a link search by reference (app).
 * @noinspection PhpUnusedLocalVariableInspection
 */
$lastReferenceSID = '';

/**
 * ID (Intermediate) of the bootstrap.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapCodeIID = '';

/**
 * ID of the branch of the bootstrap.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapCodeBID = '';

/**
 * ID of the signer for the bootstrap.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapCodeSID = '';

/**
 * ID (Intermediate) de la bibliothèque mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibraryIID = '';

/**
 * ID du code de la bibliothèque mémorisé dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibraryOID = '';

/**
 * ID of the signer for the library.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibrarySID = '';

/**
 * Instance non désérialisée de la bibliothèque mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapLibraryInstanceSleep = '';

/**
 * ID (Intermediate) de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationIID = '0';

/**
 * ID de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationOID = '0';

/**
 * ID of the signer of the application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationSID = '';

/**
 * Instance non désérialisée de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationInstanceSleep = '';

/**
 * Is the application have already been preloaded and on PHP session.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationInstancePreloaded = false;

/**
 * Instance non désérialisée des actions de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationActionInstanceSleep = '';

/**
 * Instance non désérialisée des traductions de l'application mémorisée dans la session PHP.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$bootstrapApplicationTranslateInstanceSleep = '';

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
 * Name space of the application.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$applicationNameSpace = '';


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
const LIB_LOCAL_DEBUG_FILE = 'debug';
const LIB_NID_MIN_HASH_SIZE = 64;
const LIB_NID_MIN_NONE_SIZE = 8;
const LIB_NID_MAX_HASH_SIZE = 8192;
const LIB_NID_MIN_ALGO_SIZE = 2;
const LIB_NID_MAX_ALGO_SIZE = 12;
const LIB_FIRST_GENERATED_NAME_SIZE = 6;
const LIB_FIRST_GENERATED_PASSWORD_SIZE = 16;
const LIB_FIRST_RELOAD_DELAY = 3000;
const LIB_FIRST_LOCALISATIONS = array(
    'http://puppetmaster.nebule.org',
    'http://code.master.nebule.org',
    'http://security.master.nebule.org',
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
const LIB_ARG_FIRST_PUPPETMASTER_LOCATION = 'bootstrapfirstpuppetmasterlocation';
const LIB_ARG_FIRST_SUBORDINATION_EID = 'bootstrapfirstsubordinationeid';
const LIB_ARG_FIRST_SUBORDINATION_LOCATION = 'bootstrapfirstsubordinationlocation';

const BREAK_DESCRIPTIONS = array(
    '00' => 'unknown buggy interrupt reason',
    '11' => 'user interrupt',
    '21' => 'library init error',
    '22' => "library i/o : link's folder error",
    '23' => "library i/o : link's folder error",
    '24' => "library i/o : object's folder error",
    '25' => "library i/o : object's folder error",
    '31' => 'library load : finding library IID error',
    '32' => 'library load : finding library OID error',
    '41' => 'library load : find code error',
    '42' => 'library load : include code error',
    '43' => 'library load : functional version too old',
    '44' => 'library load : load error',
    '45' => 'application : find code error',
    '46' => 'application : include code error',
    '47' => 'application : load error',
    '51' => 'unknown bootstrap hash',
    '61' => 'no local server entity',
    '62' => 'local server entity error',
    '71' => 'need sync puppetmaster',
    '72' => 'need sync authorities of security',
    '73' => 'need sync authorities of security',
    '74' => 'need sync authorities of code',
    '75' => 'need sync authorities of code',
    '76' => 'need sync authorities of time',
    '77' => 'need sync authorities of time',
    '78' => 'need sync authorities of directory',
    '79' => 'need sync authorities of directory',
    '81' => 'library init : I/O open error',
    '82' => 'library init : puppetmaster error',
    '83' => 'library init : security authority error',
    '84' => 'library init : code authority error',
    '85' => 'library init : time authority error',
    '86' => 'library init : directory authority error',
);

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
    'permitServerEntityAsAuthority' => 'boolean',
    'permitDefaultEntityAsAuthority' => 'boolean',
    'permitLocalSecondaryAuthorities' => 'boolean',
    'permitRecoveryEntities' => 'boolean',
    'permitRecoveryRemoveEntity' => 'boolean',
    'permitServerEntityAsRecovery' => 'boolean',
    'permitDefaultEntityAsRecovery' => 'boolean',
    'permitAddLinkToSigner' => 'boolean',
    'permitListOtherHash' => 'boolean',
    'permitLocalisationStats' => 'boolean',
    'permitFollowUpdates' => 'boolean',
    'permitOnlineRescue' => 'boolean',
    'permitLogs' => 'boolean',
    'permitLogsOnDebugFile' => 'boolean',
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
    'defaultEntity' => 'string',
    'defaultApplication' => 'string',
    'permitApplication1' => 'boolean',
    'permitApplication2' => 'boolean',
    'permitApplication3' => 'boolean',
    'permitApplication4' => 'boolean',
    'permitApplication5' => 'boolean',
    'permitApplication6' => 'boolean',
    'permitApplication7' => 'boolean',
    'permitApplication8' => 'boolean',
    'permitApplication9' => 'boolean',
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
    'permitWrite' => 'true',
    'permitWriteObject' => 'true',
    'permitCreateObject' => 'true',
    'permitSynchronizeObject' => 'true',
    'permitProtectedObject' => 'false',
    'permitWriteLink' => 'true',
    'permitCreateLink' => 'true',
    'permitSynchronizeLink' => 'true',
    'permitUploadLink' => 'false',
    'permitPublicUploadLink' => 'false',
    'permitPublicUploadCodeAuthoritiesLink' => 'false',
    'permitObfuscatedLink' => 'false',
    'permitWriteEntity' => 'true',
    'permitPublicCreateEntity' => 'true',
    'permitWriteGroup' => 'true',
    'permitWriteConversation' => 'false',
    'permitCurrency' => 'false',
    'permitWriteCurrency' => 'false',
    'permitCreateCurrency' => 'false',
    'permitWriteTransaction' => 'false',
    'permitObfuscatedTransaction' => 'false',
    'permitSynchronizeApplication' => 'true',
    'permitPublicSynchronizeApplication' => 'true',
    'permitDeleteObjectOnUnknownHash' => 'true',
    'permitCheckSignOnVerify' => 'true',
    'permitCheckObjectHash' => 'true',
    'permitListInvalidLinks' => 'false',
    'permitHistoryLinksSign' => 'false',
    'permitServerEntityAsAuthority' => 'false',
    'permitDefaultEntityAsAuthority' => 'false',
    'permitLocalSecondaryAuthorities' => 'true',
    'permitRecoveryEntities' => 'false',
    'permitRecoveryRemoveEntity' => 'false',
    'permitServerEntityAsRecovery' => 'false',
    'permitDefaultEntityAsRecovery' => 'false',
    'permitAddLinkToSigner' => 'true',
    'permitListOtherHash' => 'false',
    'permitLocalisationStats' => 'true',
    'permitFollowUpdates' => 'true',
    'permitOnlineRescue' => 'false',
    'permitLogs' => 'false',
    'permitLogsOnDebugFile' => 'false',
    'permitJavaScript' => 'false',
    'codeBranch' => 'stable',
    'logsLevel' => 'NORMAL',
    'modeRescue' => 'false',
    'cryptoLibrary' => 'openssl',
    'cryptoHashAlgorithm' => 'sha2.256',
    'cryptoSymmetricAlgorithm' => 'aes.256.ctr',
    'cryptoAsymmetricAlgorithm' => 'rsa.2048',
    'socialLibrary' => 'authority',
    'ioLibrary' => 'ioFileSystem',
    'ioReadMaxLinks' => '2000',
    'ioReadMaxData' => '10000',
    'ioReadMaxUpload' => '2000000',
    'ioTimeout' => '1',
    'displayUnsecureURL' => 'true',
    'displayNameSize' => '128',
    'displayEmotions' => 'false',
    'forceDisplayEntityOnTitle' => 'false',
    'linkMaxFollowedUpdates' => '100',
    'linkMaxRL' => '1',
    'linkMaxRLUID' => '5',
    'linkMaxRS' => '1',
    'permitSessionOptions' => 'true',
    'permitSessionBuffer' => 'true',
    'permitBufferIO' => 'true',
    'sessionBufferSize' => '1000',
    'defaultEntity' => LIB_DEFAULT_PUPPETMASTER_EID,
    'defaultApplication' => '1',
    'permitApplication1' => 'true',
    'permitApplication2' => 'true',
    'permitApplication3' => 'true',
    'permitApplication4' => 'false',
    'permitApplication5' => 'false',
    'permitApplication6' => 'false',
    'permitApplication7' => 'false',
    'permitApplication8' => 'false',
    'permitApplication9' => 'false',
    'defaultObfuscateLinks' => 'false',
    'defaultLinksVersion' => '2.0',
    'subordinationEntity' => '',
);

const LIB_BOOTSTRAP_ICON = "data:image/png;base64,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
f9eV8VcbpfPFAAAAAElFTkSuQmCC";

const LIB_APPLICATION_LOGO_LIGHT = "data:image/png;base64,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
Jggg==";

/**
 * Result of Lib PP initialisation.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$libraryPPCheckOK = false;

/**
 * Activate rescue mode to recovery code problems.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$libraryRescueMode = false;

/**
 * Buffer of option's values.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$optionCache = array();

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
$nebuleGhostPublicEntity = '';

/**
 * Clé privée de l'entité en cours.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleGhostPrivateEntity = '';

/**
 * Mot de passe de l'entité en cours.
 * @noinspection PhpUnusedLocalVariableInspection
 */
$nebuleGhostPasswordEntity = '';

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
$nebuleCacheReadEntitySName = array();
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
 * @return bool|int|string
 */
function lib_getOption(string $name): bool|int|string {
    global $optionCache;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($name == '')
        return '';

    // Use cache if found.
    if (isset($optionCache[$name]))
        return $optionCache[$name];

    // Read file and extract asked option.
    $value = lib_getOptionFromFile($name);

    // If empty, read default value.
    if ($value == '' && isset(LIB_CONFIGURATIONS_DEFAULT[$name]))
        $value = LIB_CONFIGURATIONS_DEFAULT[$name];

    // Convert value onto asked type.
    $result = lib_getOptionConvert($name, $value);

    $optionCache[$name] = $result;
    return $result;
}

/**
 * Extract an option from config file.
 *
 * @param string $name
 * @return string
 */
function lib_getOptionFromFile(string $name): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        $file = file(LIB_LOCAL_ENVIRONMENT_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        if ($file !== false) {
            foreach ($file as $line) {
                $line = trim((string)filter_var($line, FILTER_SANITIZE_STRING));

                if ($line == '' || $line[0] == "#" || !str_contains($line, '='))
                    continue;

                if (trim(strtok($line, '=')) == $name) {
                    return trim(strtok('='));
                }
            }
        }
    }
    return '';
}

/**
 * Convert a known option into typed result.
 *
 * @param string $name
 * @param string $value
 * @return bool|int|string
 */
function lib_getOptionConvert(string $name, string $value) {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    switch (LIB_CONFIGURATIONS_TYPE[$name]) {
        case 'string' :
            return $value;
            break;
        case 'boolean' :
            if ($value == 'true')
                return true;
            else
                return false;
            break;
        case 'integer' :
            if ($value != '')
                return (int)$value;
            else
                return 0;
            break;
        default :
            return $value;
    }
}

/**
 * Metrology - Incrementing one stat counter.
 *
 * @param string $type
 * @return void
 */
function lib_incrementMetrology(string $type): void {
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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
function lib_getMetrology(string $type): string {
    global $nebuleMetrologyLinkRead, $nebuleMetrologyLinkVerify, $nebuleMetrologyObjectRead, $nebuleMetrologyObjectVerify;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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
function lib_setMetrologyTimer(string $type): void {
    global $nebuleMetrologyTimers, $metrologyStartTime;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $nebuleMetrologyTimers[$type] = sprintf('%01.4fs', microtime(true) - $metrologyStartTime);
}

/**
 * Metrology - Get one stat timer.
 *
 * @param string $type
 * @return string
 */
function lib_getMetrologyTimer(string $type): string {
    global $nebuleMetrologyTimers;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleMetrologyTimers[$type]))
        return $nebuleMetrologyTimers[$type];
    return '';
}

/**
 * Initialize nebule procedural library.
 *
 * @return boolean
 */
function lib_init(): bool {
    global $nebuleLocalAuthorities, $libraryPPCheckOK, $libraryRescueMode, $needFirstSynchronization;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // Initialize i/o.
    if (!io_open()) {
        bootstrap_setBreak('81', __FUNCTION__);
        return false;
    }

    // Pour la suite, seul le puppetmaster est enregistré.
    // Une fois les autres entités trouvées, ajoute les autres autorités.
    // Cela empêche qu'une entité compromise ne génère un lien qui passerait avant le puppetmaster
    //   dans la recherche par référence nebFindByRef.
    $puppetmaster = lib_getOption('puppetmaster');
    if (!ent_checkPuppetmaster($puppetmaster)) {
        bootstrap_setBreak('82', __FUNCTION__);
        $needFirstSynchronization = true;
        return false;
    }
    $nebuleLocalAuthorities = array($puppetmaster);

    // Search and check global authorities.
    $nebuleSecurityAuthorities = ent_getSecurityAuthorities(false);
    if (!ent_checkSecurityAuthorities($nebuleSecurityAuthorities)) {
        $nebuleSecurityAuthorities = ent_getSecurityAuthorities(true);
        if (!ent_checkSecurityAuthorities($nebuleSecurityAuthorities)) {
            bootstrap_setBreak('83', __FUNCTION__);
            $needFirstSynchronization = true;
            return false;
        }
    }
    foreach ($nebuleSecurityAuthorities as $authority)
        $nebuleLocalAuthorities[] = $authority;

    $nebuleCodeAuthorities = ent_getCodeAuthorities(false);
    if (!ent_checkCodeAuthorities($nebuleCodeAuthorities)) {
        $nebuleCodeAuthorities = ent_getCodeAuthorities(true);
        if (!ent_checkCodeAuthorities($nebuleCodeAuthorities)) {
            bootstrap_setBreak('84', __FUNCTION__);
            $needFirstSynchronization = true;
            return false;
        }
    }
    foreach ($nebuleCodeAuthorities as $authority)
        $nebuleLocalAuthorities[] = $authority;

    $nebuleTimeAuthorities = ent_getTimeAuthorities(false);
    if (!ent_checkTimeAuthorities($nebuleTimeAuthorities)) {
        $nebuleTimeAuthorities = ent_getTimeAuthorities(true);
        if (!ent_checkTimeAuthorities($nebuleTimeAuthorities)) {
            bootstrap_setBreak('85', __FUNCTION__);
            $needFirstSynchronization = true;
            return false;
        }
    }

    $nebuleDirectoryAuthorities = ent_getDirectoryAuthorities(false);
    if (!ent_checkDirectoryAuthorities($nebuleDirectoryAuthorities)) {
        $nebuleDirectoryAuthorities = ent_getDirectoryAuthorities(true);
        if (!ent_checkDirectoryAuthorities($nebuleDirectoryAuthorities)) {
            bootstrap_setBreak('86', __FUNCTION__);
            $needFirstSynchronization = true;
            return false;
        }
    }

    $libraryRescueMode = lib_getModeRescue();

    lib_setServerEntity($libraryRescueMode);
    lib_setDefaultEntity($libraryRescueMode);
    lib_setPublicEntity();

    $libraryPPCheckOK = true;
    return true;
}

/**
 * Get and check local server entity.
 * If not found, use puppetmaster for temporary replacement.
 *
 * @param bool $rescueMode
 * @return void
 */
function lib_setServerEntity(bool $rescueMode): void {
    global $nebuleServerEntity, $nebuleLocalAuthorities, $needFirstSynchronization;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (file_exists(LIB_LOCAL_ENTITY_FILE) && is_file(LIB_LOCAL_ENTITY_FILE))
    {
        $nebuleServerEntity = filter_var(strtok(trim(file_get_contents(LIB_LOCAL_ENTITY_FILE, false, null, 0, LIB_NID_MAX_HASH_SIZE)), "\n"), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
        if (!ent_checkIsPublicKey($nebuleServerEntity))
        {
            bootstrap_setBreak('62', __FUNCTION__);
            $nebuleServerEntity = '';
            $needFirstSynchronization = true;
        }
    } else {
        bootstrap_setBreak('61', __FUNCTION__);
        $nebuleServerEntity = '';
        $needFirstSynchronization = true;
    }

    if ($nebuleServerEntity == '')
        $nebuleServerEntity = lib_getOption('puppetmaster');

    if (lib_getOption('permitServerEntityAsAuthority') && !$rescueMode)
        $nebuleLocalAuthorities[] = $nebuleServerEntity;
}

/**
 * Get and check default entity.
 *
 * @param bool $rescueMode
 * @return void
 */
function lib_setDefaultEntity(bool $rescueMode): void {
    global $nebuleDefaultEntity, $nebuleServerEntity, $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $nebuleDefaultEntity = lib_getOption('defaultEntity');
    if (!ent_checkIsPublicKey($nebuleDefaultEntity))
        $nebuleDefaultEntity = $nebuleServerEntity;

    if (lib_getOption('permitDefaultEntityAsAuthority') && !$rescueMode)
        $nebuleLocalAuthorities[] = $nebuleDefaultEntity;
}

function lib_setPublicEntity(): void {
    global $nebuleGhostPublicEntity, $nebuleDefaultEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!ent_checkIsPublicKey($nebuleGhostPublicEntity))
        $nebuleGhostPublicEntity = $nebuleDefaultEntity;
}

function lib_getModeRescue(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $askRescue = false;
    if (filter_has_var(INPUT_GET, LIB_ARG_RESCUE_MODE)
        || filter_has_var(INPUT_POST, LIB_ARG_RESCUE_MODE)) {
        log_add('input ' . LIB_ARG_RESCUE_MODE . ' ask rescue mode', 'info', __FUNCTION__, 'a94208b9');
        $askRescue = true;
    }

    if (lib_getOption('modeRescue') === true
        || (lib_getOption('permitOnlineRescue') === true
            && $askRescue
        )
    ) {
        log_add('lib init : rescue mode activated', 'warn', __FUNCTION__, 'ad7056e9');
        return true;
    }
    return false;
}



/**
 * I/O - Start I/O subsystem with checks.
 *
 * @return boolean
 */
function io_open(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!io_checkLinkFolder() || !io_checkObjectFolder())
        return false;
    return true;
}

/**
 * I/O - Check folder status and writeability for links.
 *
 * @return boolean
 */
function io_checkLinkFolder(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!file_exists(LIB_LOCAL_LINKS_FOLDER))
        io_createLinkFolder();
    if (!file_exists(LIB_LOCAL_LINKS_FOLDER) || !is_dir(LIB_LOCAL_LINKS_FOLDER)) {
        bootstrap_setBreak('22', __FUNCTION__);
        return false;
    }

    if (lib_getOption('permitWrite') && lib_getOption('permitWriteLink')) {
        $data = crypto_getPseudoRandom(2048);
        $name = LIB_LOCAL_LINKS_FOLDER . '/writest' . bin2hex(crypto_getPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            bootstrap_setBreak('23', __FUNCTION__);
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            bootstrap_setBreak('23', __FUNCTION__);
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            bootstrap_setBreak('23', __FUNCTION__);
            return false;
        }
        if (!unlink($name)) {
            bootstrap_setBreak('23', __FUNCTION__);
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
function io_checkObjectFolder(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // Check if exist.
    if (!file_exists(LIB_LOCAL_OBJECTS_FOLDER))
        io_createObjectFolder();
    if (!file_exists(LIB_LOCAL_OBJECTS_FOLDER) || !is_dir(LIB_LOCAL_OBJECTS_FOLDER)) {
        bootstrap_setBreak('24', __FUNCTION__);
        return false;
    }

    // Check writeability.
    if (lib_getOption('permitWrite') && lib_getOption('permitWriteObject')) {
        $data = crypto_getPseudoRandom(2048);
        $name = LIB_LOCAL_OBJECTS_FOLDER . '/writest' . bin2hex(crypto_getPseudoRandom(8));
        if (file_put_contents($name, $data) === false) {
            bootstrap_setBreak('25', __FUNCTION__);
            return false;
        }
        if (!file_exists($name) || !is_file($name)) {
            bootstrap_setBreak('25', __FUNCTION__);
            return false;
        }
        $read = file_get_contents($name, false, null, 0, 2400);
        if ($data != $read) {
            bootstrap_setBreak('25', __FUNCTION__);
            return false;
        }
        if (!unlink($name)) {
            bootstrap_setBreak('25', __FUNCTION__);
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
function io_createLinkFolder(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (lib_getOption('permitWrite')
        && lib_getOption('permitWriteLink')
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
function io_createObjectFolder(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (lib_getOption('permitWrite')
        && lib_getOption('permitWriteObject')
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
function io_checkNodeHaveLink(string $nid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function io_checkNodeHaveContent(string $nid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function io_blockLinksRead(string $nid, array &$lines, int $maxLinks = 0): array {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $count = 0;

    if (!nod_checkNID($nid) || !io_checkNodeHaveLink($nid))
        return $lines;
    if ($maxLinks == 0)
        $maxLinks = lib_getOption('ioReadMaxLinks');

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
 * @param string $block
 * @return boolean
 */
function io_blockLinkWrite(string $nid, string &$block): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteLink')
        || $nid == ''
    )
        return false;

    // Check if link not already present on file.
    if (file_exists(LIB_LOCAL_LINKS_FOLDER . '/' . $nid)) {
        $l = file(LIB_LOCAL_LINKS_FOLDER . '/' . $nid, FILE_SKIP_EMPTY_LINES);
        if ($l !== false) {
            foreach ($l as $k) {
                if (trim($k) == trim($block))
                    return true;
            }
        }
    }

    // Write link on file.
    if (file_put_contents(LIB_LOCAL_LINKS_FOLDER . '/' . $nid, "$block\n", FILE_APPEND) === false)
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
function io_blockLinkSynchronize(string $nid, string $location): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteLink')
        || !lib_getOption('permitSynchronizeLink')
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
            blk_write($line);
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
function io_objectRead(string $nid, int $maxData = 0): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($maxData == 0)
        $maxData = lib_getOption('ioReadMaxData');
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
function io_objectWrite(string &$data, string $oid = '0'): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($data) == 0
        || !lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
    )
        return false;

    if (strlen($oid) < LIB_NID_MIN_NONE_SIZE)
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
function io_objectSynchronize(string $nid, string $location): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
        || !lib_getOption('permitSynchronizeObject')
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
            while (($line = fgets($distobj, lib_getOption('ioReadMaxData'))) !== false)
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
function io_objectDelete(string $nid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite') || !lib_getOption('permitWriteObject') || $nid == '')
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
function io_checkExistOverHTTP(string $location): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function io_objectInclude(string $nid): bool {
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
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!nod_checkNID($nid)
        || !io_checkNodeHaveContent($nid)
    )
        return false;

    $result = true;
    log_add('include code NID=' . $nid, 'info', __FUNCTION__, 'ec10ca1d');
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
function io_close(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // Nothing to do for local filesystem.
}

/**
 * Crypto - Translate algo name into OpenSSL algo name.
 *
 * @param string $algo
 * @param bool   $loop
 * @return string
 */
function crypto_getTranslatedHashAlgo(string $algo, bool $loop = true): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($algo == '')
        $algo = lib_getOption('cryptoHashAlgorithm');

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
function crypto_getDataHash(string &$data, string $algo = ''): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function crypto_getFileHash(string $file, string $algo = ''): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    return hash_file(crypto_getTranslatedHashAlgo($algo), LIB_LOCAL_OBJECTS_FOLDER . '/' . $file);
}

/**
 * Crypto - Generate pseudo random number
 * Use OpenSSL library.
 *
 * @param int $count
 * @return string
 */
function crypto_getPseudoRandom(int $count = 32): string {
    global $nebuleServerEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $result = '';
    $algo = 'sha256'; // fixme Do not use default algo
    if ($count == 0 || !is_int($count))
        return $result;

    // Génère une graine avec la date pour le compteur interne.
    $intCount = date(DATE_ATOM) . microtime(false) . BOOTSTRAP_VERSION . $nebuleServerEntity;

    // Boucle de remplissage.
    while (strlen($result) < $count) {
        $diffSize = $count - strlen($result);

        // Fait évoluer le compteur interne.
        $intCount = hash($algo, $intCount);

        // Fait diverger le compteur interne pour la sortie.
        // La concaténation avec un texte empêche de remonter à la valeur du compteur interne.
        $outValue = hash($algo, $intCount . 'liberté égalité fraternité', true);

        // Tronc au besoin la taille de la sortie.
        if (strlen($outValue) > $diffSize)
            $outValue = substr($outValue, 0, $diffSize);

        // Ajoute la sortie au résultat final.
        $result .= $outValue;
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
function crypto_getNewPKey(string $asymmetricAlgo, string $hashAlgo, string &$publicKey, string &$privateKey, string $password): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function crypto_asymmetricEncrypt(string $data, string $privateOid = '', string $password = '', bool $entityCheck = true): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function crypto_asymmetricVerify(string $sign, string $hash, string $nid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // Read signer's public key.
    $cert = io_objectRead($nid, 10000);
    $hashSize = strlen($hash);

    $pubKeyID = openssl_pkey_get_public($cert);
    if ($pubKeyID === false)
        return false;

    lib_incrementMetrology('lv');

    // Encoding sign before check.
    $binSign = pack('H*', $sign);

    // Decode sign with public key.
    if (openssl_public_decrypt($binSign, $binDecrypted, $pubKeyID, OPENSSL_PKCS1_PADDING)) {
        $decrypted = substr(bin2hex($binDecrypted), -$hashSize, $hashSize);
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
function blk_generate(string $rc, string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function blk_sign(string $bh_bl): string {
    global $nebuleGhostPublicEntity, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity;
    log_add('track functions bh_bl=' . $bh_bl, 'debug', __FUNCTION__, '1111c0de');

    if ($bh_bl == '')
        return '';

    if (!ent_checkIsPublicKey($nebuleGhostPublicEntity)) {
        log_add('invalid current entity (public) ' . $nebuleGhostPublicEntity, 'error', __FUNCTION__, '70e110d7');
        return '';
    }
    if (!ent_checkIsPrivateKey($nebuleGhostPrivateEntity)) {
        log_add('invalid current entity (private) ' . $nebuleGhostPrivateEntity, 'error', __FUNCTION__, 'ca23fd57');
        return '';
    }
    if ($nebuleGhostPasswordEntity == '') {
        log_add('invalid current entity (password)', 'error', __FUNCTION__, '331e1fab');
        return '';
    }

    $sign = crypto_asymmetricEncrypt($bh_bl, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity, true);
    if ($sign == '')
        return '';

    $bs = $nebuleGhostPublicEntity . '>' . $sign . '.' . lib_getOption('cryptoHashAlgorithm');
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
function blk_generateSign(string $rc, string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $bh_bl = blk_generate($rc, $req, $nid1, $nid2, $nid3, $nid4);
    if ($bh_bl == '')
        return '';

    return blk_sign($bh_bl);
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
function lnk_getList(string $nid, array &$links, array $filter, bool $withInvalidLinks = false, string $addSigner = ''): void {
    global $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($nid == '0' || !io_checkNodeHaveLink($nid))
        return;

    // TODO as _lnkGetListFilterNid()
    // If not permitted, do not list invalid links.
    if (!lib_getOption('permitListInvalidLinks'))
        $withInvalidLinks = false;

    $lines = array();
    io_blockLinksRead($nid, $lines);
    foreach ($lines as $line) {
        if ($withInvalidLinks || blk_verify($line)) {
            $link = blk_parse($line);
            if (lnk_checkNotSuppressed($link, $lines) && blk_filterStructure($link, $filter))
                $links[] = $link;
        }
    }

    $validSigners = $nebuleLocalAuthorities;
    if ($addSigner != '' && nod_checkNID($addSigner, false))
        $validSigners[] = $addSigner;

    // Social filter.
    if (!$withInvalidLinks)
        blk_filterBySigners($links, $validSigners);
}

/**
 * Link - Check if link already exist with pre-parsed parts.
 *
 * @param string $req
 * @param string $nid1
 * @param string $nid2
 * @param string $nid3
 * @param string $nid4
 * @return boolean
 */
function lnk_checkExist(string $req, string $nid1, string $nid2 = '', string $nid3 = '', string $nid4 = ''): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
 * Link - Check if block link already exist in complete form.
 *
 * @param string $link
 * @return boolean
 */
function blk_checkExist(string $link): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $linkParsed = blk_parse($link);
    $nid = $linkParsed['bl/rl/nid1'];

    $lines = array();
    io_blockLinksRead($nid, $lines);
    if (in_array($link, $lines))
        return true;
    return false;
}

/**
 * Link - Test if link have been marked as suppressed with a link type x.
 *
 * @param array $link
 * @param array $lines
 * @return bool
 */
function lnk_checkNotSuppressed(array &$link, array &$lines): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    foreach ($lines as $line) {
        if (!str_contains($line, '/x>'))
            continue;
        if (blk_verify($line)) {
            $linkCompare = blk_parse($line);
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
function lnk_dateCompare(string $mod1, string $chr1, string $mod2, string $chr2): int {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
 * @param array $block
 * @param array $filter
 * @return bool
 */
function blk_filterStructure(array $block, array $filter): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    foreach ($filter as $n => $f)
    {
        $a = $f;
        if (is_string($f))
            $a = array($f);
        foreach ($a as $v)
        {
            if (isset($block[$n]) && $block[$n] != $v
                || $v == '' && !isset($block[$n])
            )
                return false;
        }
    }
    return true;
}

/**
 * Filter links by signers (BS/RS1/EID) of the links.
 *
 * @param array $blocks
 * @param array $signers
 */
function blk_filterBySigners(array &$blocks, array $signers): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($blocks) == 0 || sizeof($signers) == 0)
        return;

    foreach ($blocks as $i => $block) {
        $ok = false;
        foreach ($signers as $authority) {
            if ($block['bs/rs1/eid'] == $authority)
                $ok = true;
        }
        if (!$ok)
            unset($blocks[$i]);
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
function lnk_getListFilterNid(string $nid, array &$result, string $filterOnNid = '', string $filterOnReq = '', bool $withInvalidLinks = false): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!nod_checkNID($nid) || !io_checkNodeHaveLink($nid))
        return;

    // If not permitted, do not list invalid links.
    if (!lib_getOption('permitListInvalidLinks'))
        $withInvalidLinks = false;

    $lines = array();
    io_blockLinksRead($nid, $lines);
    foreach ($lines as $line) {
        // Verify link.
        if (!$withInvalidLinks && !blk_verify($line))
            continue;

        $linkParse = blk_parse($line);

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
function lnk_getDistantAnywhere(string $nid): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitSynchronizeLink')
        || nod_checkNID($nid)
    )
        return;

    $links = array();
    $hashType = obj_getNID('nebule/objet/type', lib_getOption('cryptoHashAlgorithm'));
    $hashLocation = obj_getNID('nebule/objet/entite/localisation', lib_getOption('cryptoHashAlgorithm'));
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
            io_blockLinkSynchronize($nid, $url);
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
function lnk_getDistantOnLocations(string $nid, array $locations = array()): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteLink')
        || !lib_getOption('permitSynchronizeLink')
        || !nod_checkNID($nid, false)
        || nod_checkBanned_FIXME($nid)
    )
        return false;

    if (sizeof($locations) == 0)
        $locations = LIB_FIRST_LOCALISATIONS;

    foreach ($locations as $location)
        io_blockLinkSynchronize($nid, $location);
    return true;
}

/**
 * Link - Check block BH on link.
 *
 * @param string $bh
 * @return bool
 */
function blk_checkBH(string &$bh): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($bh) > 15) return false;

    $rf = strtok($bh, '/');
    if (is_bool($rf)) return false;
    $rv = strtok('/');
    if (is_bool($rv)) return false;

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check RF and RV.
    //if (!lnk_checkRF($rf)) log_add('check link BH/RF failed '.$bh, 'error', __FUNCTION__, '3c0b5c4f');
    if (!blk_checkRF($rf)) return false;
    //if (!lnk_checkRV($rv)) log_add('check link BH/RV failed '.$bh, 'error', __FUNCTION__, '80c5975c');
    if (!blk_checkRV($rv)) return false;

    return true;
}

/**
 * Link - Check block RF on link.
 *
 * @param string $rf
 * @return bool
 */
function blk_checkRF(string &$rf): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function blk_checkRV(string &$rv): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function blk_checkBL(string &$bl): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function lnk_checkRC(string &$rc): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function lnk_checkRL(string &$rl): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $linkMaxRLUID = lib_getOption('linkMaxRLUID');
    if (strlen($rl) > 4096) return false; // TODO à revoir.

    // Extract items from RL 1 : REQ>NID>NID>NID>...
    $req = strtok($rl, '>');
    if (!lnk_checkREQ($req)) return false;

    $rl1nid = strtok('>');
    if ($rl1nid === false) $rl1nid = '';
    if (!nod_checkNID($rl1nid, false)) return false;
    $i = 1;
    while (($rl1nid = strtok('>')) !== false) {
        $i++;
        if ($i > $linkMaxRLUID) return false;
        if (!nod_checkNID($rl1nid, true)) return false;
    }

    return true;
}

/**
 * Link - Check block REQ on link.
 *
 * @param string $req
 * @return bool
 */
function lnk_checkREQ(string &$req): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function blk_checkBS(string &$bh, string &$bl, string &$bs): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($bs) > 4096) return false; // TODO à revoir.

    $rs = strtok($bs, '/');
    if (is_bool($rs)) return false;

    // Check bloc overflow
    if (strtok('/') !== false) return false;

    // Check content RS 1 NID 1 : hash.algo.size
    //if (!lnk_checkRS($rs, $bh, $bl)) log_add('check link BS/RS failed '.$bs, 'error', __FUNCTION__, '0690f5ac');
    if (!blk_checkRS($rs, $bh, $bl)) return false;

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
function blk_checkRS(string &$rs, string &$bh, string &$bl): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
    if (!blk_checkSIG($bh, $bl, $sig, $nid)) return false;

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
function blk_checkSIG(string &$bh, string &$bl, string &$sig, string &$nid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($sig) > 4096) return false; // TODO à revoir.

    $sign = strtok($sig, '.');
    if (is_bool($sign)) return false;
    $algo = strtok('.');
    if (is_bool($algo)) return false;
    $size = strtok('.');
    if (is_bool($size)) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    // Check hash value.
    if (strlen($sign) < LIB_NID_MIN_HASH_SIZE) return false;
    if (strlen($sign) > LIB_NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($sign)) return false;

    // Check algo value.
    if (strlen($algo) < LIB_NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > LIB_NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < LIB_NID_MIN_HASH_SIZE) return false;
    if ((int)$size > LIB_NID_MAX_HASH_SIZE) return false;
    //if ((strlen($sign) * 4) != (int)$size) return false;
    //if (strlen($sign) != (int)$size) return false; // TODO can't be checked ?

    if (!lib_getOption('permitCheckSignOnVerify')) return true;
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
 * @param string $block
 * @return boolean
 */
function blk_verify(string $block): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($block) > 4096) return false; // TODO à revoir.
    if (strlen($block) == 0) return false;

    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($block), '_');
    if (is_bool($bh)) return false;
    $bl = strtok('_');
    if (is_bool($bl)) return false;
    $bs = strtok('_');
    if (is_bool($bs)) return false;

    // Check link overflow
    if (strtok('_') !== false) return false;

    // Check BH, BL and BS.
    //if (!lnk_checkBH($bh)) log_add('check link BH failed '.$link, 'error', __FUNCTION__, '80cbba4b');
    if (!blk_checkBH($bh)) return false;
    //if (!lnk_checkBL($bl)) log_add('check link BL failed '.$link, 'error', __FUNCTION__, 'c5d22fda');
    if (!blk_checkBL($bl)) return false;
    //if (!lnk_checkBS($bh, $bl, $bs)) log_add('check link BS failed '.$link, 'error', __FUNCTION__, '2828e6ae');
    if (!blk_checkBS($bh, $bl, $bs)) return false;

    return true;
}

/**
 * Link - Explode link and it's values into array.
 *
 * @param string $block
 * @return array
 */
function blk_parse(string $block): array {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // Extract blocs from link L : BH_BL_BS
    $bh = strtok(trim($block), '_');
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
    $bs_rs1_eid = strtok($bs_rs1, '>');
    $bs_rs1_sig = strtok('>');

    // Check hash value.
    $bs_rs1_sig_sign = strtok($bs_rs1_sig, '.');

    // Check algo value.
    $bs_rs1_sig_algo = strtok('.');

    // Check size value.
    $bs_rs1_sig_size = strtok('.');

    return array(
        'link' => $block, // original link
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
        'bs/rs1/eid' => $bs_rs1_eid,
        'bs/rs1/sig' => $bs_rs1_sig,
        'bs/rs1/sig/sign' => $bs_rs1_sig_sign,
        'bs/rs1/sig/algo' => $bs_rs1_sig_algo,
        'bs/rs1/sig/size' => $bs_rs1_sig_size,
    );
}

/**
 * Link - Check and write link into parts files.
 *
 * @param $block
 * @return boolean
 */
function blk_write($block): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteLink')
        || !blk_verify($block)
    )
        return false;

    // Extract link parts.
    $linkParsed = blk_parse($block);

    // Write link into parts files.
    $result = io_blockLinkWrite($linkParsed['bl/rl/nid1'], $block);
    if ($linkParsed['bl/rl/nid2'] != '')
        $result = io_blockLinkWrite($linkParsed['bl/rl/nid2'], $block) && $result;
    if ($linkParsed['bl/rl/nid3'] != '')
        $result = io_blockLinkWrite($linkParsed['bl/rl/nid3'], $block) && $result;
    if ($linkParsed['bl/rl/nid4'] != '')
        $result = io_blockLinkWrite($linkParsed['bl/rl/nid4'], $block) && $result;

    // Write link for signer if needed.
    if (lib_getOption('permitAddLinkToSigner'))
        $result = io_blockLinkWrite($linkParsed['bs/rs1/eid'], $block) && $result;

    // Write link to history if needed.
    $histFile = LIB_LOCAL_HISTORY_FILE;
    if (lib_getOption('permitHistoryLinksSign'))
        $result = io_blockLinkWrite($histFile, $block) && $result;

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
function nod_findByReference(string $nid, string $rid): string {
    global $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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
        if (in_array($link['bs/rs1/eid'], $nebuleLocalAuthorities)) {
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
function nod_getFirstname(string $nid): string {
    global $nebuleCacheReadEntityFName;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadEntityFName [$nid]))
        return $nebuleCacheReadEntityFName [$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/prenom', lib_getOption('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheReadEntityFName [$nid] = $text;
    return $text;
}

/**
 * Find name to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getName(string $nid): string {
    global $nebuleCacheReadEntityName;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadEntityName[$nid]))
        return $nebuleCacheReadEntityName[$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/nom'));
    $text = obj_getAsText1line($type, 128);

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheReadEntityName[$nid] = $text;
    return $text;
}

/**
 * Find post name to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getPostName(string $nid): string {
    global $nebuleCacheReadEntityPName;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadEntityPName [$nid]))
        return $nebuleCacheReadEntityPName [$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/postnom', lib_getOption('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheReadEntityPName [$nid] = $text;
    return $text;
}

/**
 * Find surname to the NID.
 *
 * @param string $nid
 * @return string
 */
function nod_getSurname(string $nid): string {
    global $nebuleCacheReadEntitySName;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadEntitySName[$nid]))
        return $nebuleCacheReadEntitySName[$nid];

    $type = nod_getByType($nid, obj_getNID('nebule/objet/surnom', lib_getOption('cryptoHashAlgorithm')));
    $text = obj_getAsText1line($type, 128);

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheReadEntitySName[$nid] = $text;
    return $text;
}

/**
 * Find OID with content for the type of the NID.
 *
 * @param string $nid
 * @param string $rid
 * @return string
 */
function nod_getByType(string $nid, string $rid): string {
    global $nebuleCacheFindObjType;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $links = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid3' => $rid,
        'bl/rl/nid4' => '',
    );
    lnk_getList($nid, $links, $filter, false);

    foreach ($links as $link) {
        if (lib_getOption('permitBufferIO'))
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
function nod_getAlgo(&$nid): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function nod_checkNID(string $nid, bool $permitNull = false): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // May be null in some case.
    if ($permitNull && $nid == '')
        return true;

    $hash = strtok($nid, '.');
    if ($hash === false) return false;
    $algo = strtok('.');
    if ($algo === false) return false;
    $size = strtok('.');
    if ($size === false) return false;

    // Check item overflow
    if (strtok('.') !== false) return false;

    if ($algo == 'none' || $algo == 'string')
        $minSize = LIB_NID_MIN_NONE_SIZE;
    else
        $minSize = LIB_NID_MIN_HASH_SIZE;

    // Check hash value.
    if ((strlen($hash) * 4) < $minSize) return false;
    if ((strlen($hash) * 4) > LIB_NID_MAX_HASH_SIZE) return false;
    if (!ctype_xdigit($hash)) return false;

    // Check algo value.
    if (strlen($algo) < LIB_NID_MIN_ALGO_SIZE) return false;
    if (strlen($algo) > LIB_NID_MAX_ALGO_SIZE) return false;
    if (!ctype_alnum($algo)) return false;

    // Check size value.
    if (!ctype_digit($size)) return false; // Check content before!
    if ((int)$size < $minSize) return false;
    if ((int)$size > LIB_NID_MAX_HASH_SIZE) return false;
    if ((strlen($hash) * 4) != (int)$size) return false;

    return true;
}

/**
 * Object - Check with links if a node is marked as banned.
 *
 * @param $nid
 * @return boolean
 */
function nod_checkBanned_FIXME(&$nid): bool {
    global $nebuleGhostPublicEntity, $nebuleSecurityAuthorities, $nebuleCacheIsBanned;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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
function obj_getAsText1line(string &$oid, int $maxData = 0): string {
    global $nebuleCacheReadObjText1line;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadObjText1line[$oid]))
        return $nebuleCacheReadObjText1line[$oid];

    if ($maxData == 0)
        $maxData = lib_getOption('ioReadMaxData');

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

    if (lib_getOption('permitBufferIO'))
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
function obj_getAsText(string &$oid, int $maxData = 0): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($maxData == 0)
        $maxData = lib_getOption('ioReadMaxData');

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
function obj_checkTypeMime(string $nid, string $typeMime, string $addSigner = ''): bool {
    global $nebuleLocalAuthorities, $nebuleCacheReadObjTypeMime, $nebuleServerEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadObjTypeMime [$nid])) {
        if ($nebuleCacheReadObjTypeMime [$nid] == $typeMime)
            return true;
        else
            return false;
    }

    if (!nod_checkNID($nid))
        return false;

    $typeRID = obj_getNID('nebule/objet/type', lib_getOption('cryptoHashAlgorithm'));
    $hashTypeAsked = obj_getNID($typeMime, lib_getOption('cryptoHashAlgorithm'));
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
    blk_filterBySigners($links, $signers);

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
function obj_setContentAsText(string $data, bool $skipIfPresent = true): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
        || !lib_getOption('permitWriteLink')
        || strlen($data) == 0
    )
        return false;

    $oid = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));

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
function obj_generate_FIXME(string &$data, string $typeMime = ''): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($data) == 0
        || !lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
    )
        return false;
    $date = '0' . date('YmdHis');
    $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));

    if (!io_checkNodeHaveContent($hash))
        obj_setContent($data, $hash);

    $link = blk_generateSign(
        $date,
        'l',
        $hash,
        obj_getNID(lib_getOption('cryptoHashAlgorithm'), lib_getOption('cryptoHashAlgorithm')),
        obj_getNID('nebule/objet/hash', lib_getOption('cryptoHashAlgorithm'))
    );
    if (blk_verify($link))
        blk_write($link);

    if ($typeMime != '') {
        $link = blk_generateSign(
            $date,
            'l',
            $hash,
            obj_getNID($typeMime, lib_getOption('cryptoHashAlgorithm')),
            obj_getNID('nebule/objet/type', lib_getOption('cryptoHashAlgorithm'))
        );
        if (blk_verify($link))
            blk_write($link);
    }
    return true;
}

/**
 * Object - Read object content and push on $data.
 *
 * @param string  $nid
 * @param string  $data
 * @param int $maxData
 * @return boolean
 */
function obj_getLocalContent(string $nid, string &$data, int $maxData = 0): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function obj_getDistantContent(string $nid, array $locations = array()): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
        || !lib_getOption('permitSynchronizeObject')
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
function obj_checkContent(string $nid): bool {
    global $nebuleCacheLibrary_o_vr;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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

    if (lib_getOption('permitBufferIO'))
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
function obj_getNID(string $data, string $algo = ''): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($algo == '')
        $algo = lib_getOption('cryptoHashAlgorithm');
    return crypto_getDataHash($data, $algo) . '.' . $algo;
}

/**
 * Object - Write object content.
 *
 * @param string $data
 * @param string $oid
 * @return bool
 */
function obj_setContent(string &$data, string $oid = '0'): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (strlen($data) == 0
        || !lib_getOption('permitWrite')
        || !lib_getOption('permitWriteObject')
    )
        return false;

    $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
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
function ent_getFullName(string $nid): string {
    global $nebuleCacheReadEntityFullName;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheReadEntityFullName[$nid]))
        return $nebuleCacheReadEntityFullName[$nid];

    $firstName = nod_getFirstname($nid);
    $name = nod_getName($nid);
    $postName = nod_getPostName($nid);
    if ($name == '')
        $fullName = "$nid";
    else
        $fullName = $name;
    if ($firstName != '')
        $fullName = "$firstName $fullName";
    if ($postName != '')
        $fullName = "$fullName $postName";

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheReadEntityFullName[$nid] = $fullName;
    return $fullName;
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
function ent_generate(string $asymmetricAlgo, string $hashAlgo, string &$hashPublicKey, string &$hashPrivateKey, string &$password = ''): bool {
    global $nebuleGhostPublicEntity, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!lib_getOption('permitWrite')
        || !lib_getOption('permitWriteEntity')
        || !lib_getOption('permitWriteObject')
        || !lib_getOption('permitWriteLink')
        || $password == ''
    )
        return false;

    // Generate the bi-key.
    $publicKey = '';
    $privateKey = '';
    if (crypto_getNewPKey($asymmetricAlgo, $hashAlgo, $publicKey, $privateKey, $password)) {
        $hashPublicKey = obj_getNID($publicKey, lib_getOption('cryptoHashAlgorithm'));
        log_add('generate new public key ' . $hashPublicKey, 'warn', __FUNCTION__, '9c207dc0');
        if (!obj_setContent($publicKey, $hashPublicKey))
            return false;
        $hashPrivateKey = obj_getNID($privateKey, lib_getOption('cryptoHashAlgorithm'));
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
        $bh_bl = blk_generate('', 'l', $item, $oidText, $oidType);
        $sign = crypto_asymmetricEncrypt($bh_bl, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity, false);
        $link = $bh_bl . '_' . $nebuleGhostPublicEntity . '>' . $sign . '.' . lib_getOption('cryptoHashAlgorithm');
        if (!blk_write($link))
            return false;
    }

    $list = array($hashPublicKey, $hashPrivateKey);
    foreach ($list as $item) {
        $bh_bl = blk_generate('', 'l', $item, $oidPem, $oidType);
        $sign = crypto_asymmetricEncrypt($bh_bl, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity, false);
        $link = $bh_bl . '_' . $nebuleGhostPublicEntity . '>' . $sign . '.' . lib_getOption('cryptoHashAlgorithm');
        if (!blk_write($link))
            return false;
    }

    $bh_bl = blk_generate('', 'f', $hashPublicKey, $hashPrivateKey, $oidPKey);
    $sign = crypto_asymmetricEncrypt($bh_bl, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity, false);
    $link = $bh_bl . '_' . $nebuleGhostPublicEntity . '>' . $sign . '.' . lib_getOption('cryptoHashAlgorithm');
    if (!blk_write($link))
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
function ent_getAskedAuthorities(string $refNid, array &$result, bool $synchronize): array {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($result) != 0)
        return $result;

    if ($synchronize)
        obj_getDistantContent($refNid, array());

    $lnkList = array();
    $entList = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $refNid,
        'bl/rl/nid3' => $refNid,
        'bl/rl/nid4' => '',
        'bs/rs1/eid' => lib_getOption('puppetmaster'),
    );
    lnk_getList($refNid, $lnkList, $filter);

    // Extract uniques entities
    foreach ($lnkList as $lnk)
        $entList[$lnk['bl/rl/nid2']] = $lnk['bl/rl/nid2'];
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
function ent_getSecurityAuthorities(bool $synchronize = false): array {
    global $nebuleSecurityAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    return ent_getAskedAuthorities(LIB_RID_SECURITY_AUTHORITY, $nebuleSecurityAuthorities, $synchronize);
}

/**
 * Get authorities of code IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getCodeAuthorities(bool $synchronize = false): array {
    global $nebuleCodeAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    return ent_getAskedAuthorities(LIB_RID_CODE_AUTHORITY, $nebuleCodeAuthorities, $synchronize);
}

/**
 * Get authorities of time IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getTimeAuthorities(bool $synchronize = false): array {
    global $nebuleTimeAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    return ent_getAskedAuthorities(LIB_RID_TIME_AUTHORITY, $nebuleTimeAuthorities, $synchronize);
}

/**
 * Get authorities of directory IDs.
 * Update global list on the same time.
 *
 * @param bool $synchronize
 * @return array
 */
function ent_getDirectoryAuthorities(bool $synchronize = false): array {
    global $nebuleDirectoryAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    return ent_getAskedAuthorities(LIB_RID_DIRECTORY_AUTHORITY, $nebuleDirectoryAuthorities, $synchronize);
}

/**
 * Check puppetmaster entity.
 *
 * @param string $oid
 * @return bool
 */
function ent_checkPuppetmaster(string $oid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!ent_checkIsPublicKey($oid)) {
        bootstrap_setBreak('71', __FUNCTION__);
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
function ent_checkSecurityAuthorities(array $oidList): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($oidList) == 0) {
        bootstrap_setBreak('72', __FUNCTION__);
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            bootstrap_setBreak('73', __FUNCTION__);
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
function ent_checkCodeAuthorities(array $oidList): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($oidList) == 0) {
        bootstrap_setBreak('74', __FUNCTION__);
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            bootstrap_setBreak('75', __FUNCTION__);
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
function ent_checkTimeAuthorities(array $oidList): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($oidList) == 0) {
        bootstrap_setBreak('76', __FUNCTION__);
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            bootstrap_setBreak('77', __FUNCTION__);
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
function ent_checkDirectoryAuthorities(array $oidList): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (sizeof($oidList) == 0) {
        bootstrap_setBreak('78', __FUNCTION__);
        return false;
    }
    foreach ($oidList as $nid) {
        if (!ent_checkIsPublicKey($nid)) {
            bootstrap_setBreak('79', __FUNCTION__);
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
function ent_syncPuppetmaster(string $oid): void {
    global $optionCache;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!ent_checkIsPublicKey($oid)) {
        $oid = LIB_DEFAULT_PUPPETMASTER_EID;
        $optionCache['puppetmaster'] = LIB_DEFAULT_PUPPETMASTER_EID;
    }

    if ($oid == LIB_DEFAULT_PUPPETMASTER_EID) {
        log_add('Write default puppetmaster', 'info', __FUNCTION__, '555ec326');
        foreach (LIB_FIRST_AUTHORITIES_PUBLIC_KEY as $data)
        {
            $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
            io_objectWrite($data, $hash);
        }
        foreach (LIB_FIRST_LINKS as $link)
            blk_write($link);
    }

    ent_syncAuthorities(array($oid));
}

/**
 * Synchronize authorities from central locations.
 *
 * @param array $oidList
 * @return void
 */
function ent_syncAuthorities(array $oidList): void {
    global $nebuleCacheIsPublicKey, $nebuleCacheIsPrivateKey;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    foreach ($oidList as $nid) {
        log_add('Sync authority entity ' . $nid, 'info', __FUNCTION__, '92e0483f');
        obj_getDistantContent($nid, array());
        lnk_getDistantOnLocations($nid, array());
    }

    $nebuleCacheIsPublicKey = array();
    $nebuleCacheIsPrivateKey = array();
}

/**
 * Verify node is an object and is a valid entity public key.
 *
 * @param string $nid
 * @return boolean
 */
function ent_checkIsPublicKey(string $nid): bool {
    global $nebuleCacheIsPublicKey;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

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
    if (str_contains($line, 'BEGIN PUBLIC KEY'))
        $result = true;
    else
    {
        $result = false;
        log_add('NID do not provide a public key', 'warn', __FUNCTION__, '25743bf3');
    }

    if (lib_getOption('permitBufferIO'))
        $nebuleCacheIsPublicKey[$nid] = $result;
    return $result;
}

/**
 * Verify node is an object and is a valid entity private key.
 *
 * @param $nid
 * @return bool
 */
function ent_checkIsPrivateKey(&$nid): bool {
    global $nebuleCacheIsPrivateKey;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (isset($nebuleCacheIsPrivateKey[$nid]))
        return $nebuleCacheIsPrivateKey[$nid];

    if ($nid == '0'
        || strlen($nid) < LIB_NID_MIN_HASH_SIZE
        || !nod_checkNID($nid)
        || !obj_checkContent($nid)
        || !io_checkNodeHaveLink($nid)
    )
        return false;

    //if (!obj_checkTypeMime($nid, 'application/x-pem-file'))
    //    return false;

    $line = obj_getAsText($nid, 10000);
    $result = false;
    if (str_contains($line, 'BEGIN ENCRYPTED PRIVATE KEY'))
        $result = true;
    if (lib_getOption('permitBufferIO'))
        $nebuleCacheIsPrivateKey[$nid] = $result;
    return $result;
}

/**
 * Application - Check OID for an application.
 *
 * @param string $oid
 * @return bool
 */
function app_checkOID(string $oid): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($oid != '0'
        || $oid != '1'
        || $oid != '2'
        || $oid != '3'
        || $oid != '4'
        || $oid != '5'
        || $oid != '6'
        || $oid != '7'
        || $oid != '8'
        || $oid != '9'
    )
        return true;
    if (!nod_checkNID($oid, false)
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
 * @param string $nid
 * @return bool
 */
function app_getActivate(string $nid): bool {
    global $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // Check for defaults app.
    if (strlen($nid) == '1'
        || $nid == lib_getOption('defaultApplication')
    )
        return true;

    // Check with links.
    $links = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $nid,
        'bl/rl/nid2' => LIB_RID_INTERFACE_APPLICATIONS_ACTIVE,
        'bl/rl/nid3' => '',
        'bl/rl/nid4' => '',
    );
    lnk_getList($nid, $links, $filter);
    foreach ($links as $link) {
        if (in_array($link['bs/rs1/eid'], $nebuleLocalAuthorities))
            return true;
    }

    return false;
}

/**
 * Application - Get if an application have to be preloaded.
 *
 * @param string $oid
 * @return bool
 */
function app_getPreload(string $oid): bool {
    global $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // Check for defaults app.
    if (strlen($oid) < 2)
        return false;

    // Check with links.
    $links = array();
    $filter = array(
        'bl/rl/req' => 'l',
        'bl/rl/nid1' => $oid,
        'bl/rl/nid2' => LIB_RID_INTERFACE_APPLICATIONS_DIRECT,
        'bl/rl/nid3' => '',
        'bl/rl/nid4' => '',
    );
    lnk_getList($oid, $links, $filter);
    blk_filterBySigners($links, $nebuleLocalAuthorities);

    if (sizeof($links) != 0)
        return true;
    return false;
}

/**
 * Find current code branch to find apps codes.
 *
 * @return void
 */
function app_getCurrentBranch(): void {
    global $nebuleLocalAuthorities, $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($codeBranchNID != '')
        return;

    // Get code branch on config
    $codeBranchName = lib_getOption('codeBranch');
    if ($codeBranchName == '')
        $codeBranchName = LIB_CONFIGURATIONS_DEFAULT['codeBranch'];
    $codeBranchNID = '';

    // Check if it's a name or an OID.
    if (nod_checkNID($codeBranchName, false)
        && io_checkNodeHaveLink($codeBranchName)
    ) {
        $codeBranchNID = $codeBranchName;
    } else {
        // Get all RID of code branches
        $codeBranchRID = LIB_RID_CODE_BRANCH;
        $bLinks = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $codeBranchRID,
            'bl/rl/nid3' => $codeBranchRID,
            'bl/rl/nid4' => '',
        );
        lnk_getList($codeBranchRID, $bLinks, $filter, false);
        blk_filterBySigners($bLinks, $nebuleLocalAuthorities);

        // Get all NID with the name of wanted code branch.
        $codeBranchRID = obj_getNID($codeBranchName, LIB_REF_CODE_ALGO);
        $nLinks = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid2' => obj_getNID($codeBranchName, LIB_REF_CODE_ALGO),
            'bl/rl/nid3' => obj_getNID('nebule/objet/nom', LIB_REF_CODE_ALGO),
            'bl/rl/nid4' => '',
        );
        lnk_getList($codeBranchRID, $nLinks, $filter, false);
        blk_filterBySigners($nLinks, $nebuleLocalAuthorities);

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
    }
    log_add('Current branch : ' . $codeBranchNID, 'normal', __FUNCTION__, '9f1bf579');
}

/**
 * Find a valid application OID from an RID for current code branch.
 * Return a IID (Intermediate ID).
 * Can be used both for library and application.
 *
 * @param string $rid
 * @return string
 */
function app_getByRef(string $rid): string {
    global $nebuleLocalAuthorities, $codeBranchNID, $lastReferenceSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($codeBranchNID == '')
        app_getCurrentBranch();

    $phpNID = obj_getNID(BOOTSTRAP_CODING, LIB_REF_CODE_ALGO);

    // Get current version of code
    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $rid,
        'bl/rl/nid3' => $phpNID,
        'bl/rl/nid4' => $codeBranchNID,
    );
    lnk_getList($rid, $links, $filter, false);
    blk_filterBySigners($links, $nebuleLocalAuthorities);

    if (sizeof($links) == 0)
        return '';

    // Get newest link
    $resultLink=$links[0];
    foreach ($links as $link)
    {
        if (lnk_dateCompare($link['bl/rc/mod'],$link['bl/rc/chr'],$resultLink['bl/rc/mod'],$resultLink['bl/rc/chr']) > 0)
            $resultLink = $link;
    }

    $lastReferenceSID = $resultLink['bs/rs1/eid'];
    return $resultLink['bl/rl/nid2'];
}

/**
 * Find a list of valid application IID from an RID for current code branch.
 * Check or not if app is tagged as activated.
 * Return a list of IID (Intermediate ID).
 * Can be used both for library and application.
 *
 * @param string $rid
 * @param bool   $activated
 * @param bool   $allBranches
 * @return array
 */
function app_getList(string $rid, bool $activated = true, bool $allBranches=false): array {
    global $nebuleLocalAuthorities, $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($codeBranchNID == '')
        app_getCurrentBranch();

    $phpNID = obj_getNID(BOOTSTRAP_CODING, LIB_REF_CODE_ALGO);

    // Get current version of code
    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $rid,
        'bl/rl/nid3' => $phpNID,
    );
    if (!$allBranches)
        $filter['bl/rl/nid4'] = $codeBranchNID;
    lnk_getList($rid, $links, $filter, false);
    blk_filterBySigners($links, $nebuleLocalAuthorities);

    if (sizeof($links) == 0)
        return $links;

    $resultLinks = array();

    foreach ($links as $link)
    {
        if (!isset($link['bl/rl/nid2']) || $link['bl/rl/nid2'] == '')
            continue;
        $oid = $link['bl/rl/nid2'];
        log_add('oid=' . $oid, 'debug', __FUNCTION__, 'e5edd766');
        if (!$activated || app_getActivate($oid))
            $resultLinks[$oid] = $oid;
    }

    return $resultLinks;
}

function app_getCodeList(string $iid, array &$links): void {
    global $nebuleLocalAuthorities, $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($codeBranchNID == '')
        app_getCurrentBranch();

// Get current version of code
    $filter = array(
        'bl/rl/req' => 'f',
        'bl/rl/nid1' => $iid,
        'bl/rl/nid3' => $codeBranchNID,
        'bl/rl/nid4' => '',
    );
    lnk_getList($iid, $links, $filter, false);
    blk_filterBySigners($links, $nebuleLocalAuthorities);
}

/**
 * Find a valid application code from an IID for current code branch.
 * Return a OID containing the code.
 * Can be used both for library and application.
 *
 * @param string $iid
 * @return string
 */
function app_getCode(string $iid): string {
    global $lastReferenceSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // Get list of codes objects for the app IID.
    $links = array();
    app_getCodeList($iid, $links);

    if (sizeof($links) == 0)
        return '';

    // Get newest link
    // TODO inverser la liste
    $resultLink=$links[0];
    foreach ($links as $link)
    {
        if (obj_checkTypeMime($link['bl/rl/nid2'], BOOTSTRAP_CODING)
            && lnk_dateCompare($link['bl/rc/mod'],$link['bl/rc/chr'],$resultLink['bl/rc/mod'],$resultLink['bl/rc/chr']) > 0
        )
            $resultLink = $link;
    }

    $lastReferenceSID = $resultLink['bs/rs1/eid'];
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
function bootstrap_getFlushSession(bool $forceFlush = false): void {
    global $bootstrapFlush;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $askFlush = false;
    if (filter_has_var(INPUT_GET, LIB_ARG_FLUSH_SESSION)
        || filter_has_var(INPUT_POST, LIB_ARG_FLUSH_SESSION)
    ) {
        log_add('input ' . LIB_ARG_FLUSH_SESSION . ' ask flush session',
            'warn', __FUNCTION__, '4abe475a');
        $askFlush = true;
    }

    session_start();
    if (($askFlush || $forceFlush) && (isset($_SESSION['sessionOk']) || bootstrap_getUserBreak())) {
        $bootstrapFlush = true;
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        session_regenerate_id(true);
        session_start();
    } else
        $_SESSION['sessionOk'] = true;

    session_write_close();

    session_cache_limiter('');
    ini_set('session.use_cookies', '0');
    ini_set('session.use_only_cookies', '0');
    ini_set('session.use_trans_sid', '0');
}

/**
 * Write the user session track on logs.
 *
 * @return void
 */
function bootstrap_logUserSession(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $sessionId = session_id();
    log_add('session hash id ' . crypto_getDataHash($sessionId),
        'info', __FUNCTION__, '36ebd66b');
}

/**
 * Lit si demande de l'utilisateur d'une mise à jour des instances de bibliothèque et d'application.
 * Dans ce cas, la session PHP n'est pas exploitée.
 *
 * @return void
 */
function bootstrap_getUpdate(): void {
    global $bootstrapUpdate;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (filter_has_var(INPUT_GET, LIB_ARG_UPDATE_APPLICATION)
        || filter_has_var(INPUT_POST, LIB_ARG_UPDATE_APPLICATION)
    ) {
        log_add('input ' . LIB_ARG_UPDATE_APPLICATION . ' ask update',
            'warn', __FUNCTION__, 'ac8a2330');

        session_start();

        if (!isset($_SESSION['askUpdate'])) {
            $bootstrapUpdate = true;
            log_add('update', 'info', __FUNCTION__, 'f2ef6dc2');
            $_SESSION['askUpdate'] = true;
        } else
            unset($_SESSION['askUpdate']);

        session_write_close();
    }
}

/**
 * Read arg to ask switching of application.
 */
function bootstrap_getSwitchApplication(): void {
    global $bootstrapFlush, $bootstrapSwitchApplication;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if ($bootstrapFlush)
        return;

    $arg = '';
    if (filter_has_var(INPUT_GET, LIB_ARG_SWITCH_APPLICATION))
        $arg = trim(filter_input(INPUT_GET, LIB_ARG_SWITCH_APPLICATION,
            FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
    elseif (filter_has_var(INPUT_POST, LIB_ARG_SWITCH_APPLICATION))
        $arg = trim(filter_input(INPUT_POST, LIB_ARG_SWITCH_APPLICATION,
            FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

    if ($arg != '')
        log_add('input ' . LIB_ARG_SWITCH_APPLICATION . ' ask switch application to ' . $arg,
            'info', __FUNCTION__, 'd1a3f3f9');

    if (!app_checkOID($arg))
        return;

    if (app_getActivate($arg))
        $bootstrapSwitchApplication = $arg;
}

/**
 * Activate the capability to open PHP code on other file.
 *
 * @return void
 */
function bootstrap_setPermitOpenFileCode(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function bootstrap_findLibraryPOO(string &$bootstrapLibraryInstanceSleep): void {
    global $libraryPPCheckOK, $bootstrapLibraryIID, $bootstrapLibraryOID, $bootstrapLibrarySID, $lastReferenceSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!$libraryPPCheckOK)
        return;

    // Try to find on session.
    session_start();
    //bootstrap_checkSessionLibraryPOO(); // FIXME unsaved session !
    if (isset($_SESSION['bootstrapLibraryIID'])
        && nod_checkNID($_SESSION['bootstrapLibraryIID'])
        && io_checkNodeHaveLink($_SESSION['bootstrapLibraryIID'])
        //&& obj_checkContent($_SESSION['bootstrapLibraryIID'])
        && nod_checkNID($_SESSION['bootstrapLibraryOID'])
        && io_checkNodeHaveLink($_SESSION['bootstrapLibraryOID'])
        && obj_checkContent($_SESSION['bootstrapLibraryOID'])
        && isset($_SESSION['bootstrapLibraryInstances'][$_SESSION['bootstrapLibraryIID']])
        && $_SESSION['bootstrapLibraryInstances'][$_SESSION['bootstrapLibraryIID']] != ''
    ) {
        log_add('load serialized class \Nebule\Library\nebule',
            'info', __FUNCTION__, '0a485dce');
        $bootstrapLibraryIID = $_SESSION['bootstrapLibraryIID'];
        $bootstrapLibraryOID = $_SESSION['bootstrapLibraryOID'];
        $bootstrapLibrarySID = $_SESSION['bootstrapLibrarySID'];
        $bootstrapLibraryInstanceSleep = $_SESSION['bootstrapLibraryInstances'][$_SESSION['bootstrapLibraryIID']];
    }
    session_abort();

    // Try to find with links.
    if ($bootstrapLibraryIID == ''
        || $bootstrapLibraryOID == ''
    ) {
        $bootstrapLibraryIID = app_getByRef(LIB_RID_INTERFACE_LIBRARY);
        $bootstrapLibrarySID = $lastReferenceSID;

        if (nod_checkNID($bootstrapLibraryIID, false)
            && io_checkNodeHaveLink($bootstrapLibraryIID)
        ) {
            $bootstrapLibraryOID = app_getCode($bootstrapLibraryIID);

            if (nod_checkNID($bootstrapLibraryOID, false)
                && io_checkNodeHaveLink($bootstrapLibraryOID)
                && obj_checkContent($bootstrapLibraryOID)
            )
                log_add('find nebule library (' . $bootstrapLibraryIID . ')',
                    'info', __FUNCTION__, '90ee41fc');
            else {
                $bootstrapLibraryIID = '';
                $bootstrapLibraryOID = '';
                $bootstrapLibrarySID = '';
                $bootstrapLibraryInstanceSleep = '';
                bootstrap_setBreak('32', __FUNCTION__);
            }
        } else {
            $bootstrapLibraryIID = '';
            $bootstrapLibraryOID = '';
            $bootstrapLibrarySID = '';
            $bootstrapLibraryInstanceSleep = '';
            bootstrap_setBreak('31', __FUNCTION__);
        }
    }
}

/**
 * Include nebule Library POO code.
 *
 * @return void
 */
function bootstrap_includeLibraryPOO(): void {
    global $bootstrapLibraryOID, $libraryPPCheckOK;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!$libraryPPCheckOK)
        return;

    if ($bootstrapLibraryOID == '') {
        log_reopen(BOOTSTRAP_NAME); // TODO vérifier si nécessaire.
        bootstrap_setBreak('41', __FUNCTION__);
    } elseif (!io_objectInclude($bootstrapLibraryOID)) {
        log_reopen(BOOTSTRAP_NAME); // TODO vérifier si nécessaire.
        bootstrap_setBreak('42', __FUNCTION__);
        $bootstrapLibraryOID = '';
    }
}

/**
 * Load and initialize nebule Library POO.
 *
 * @param string $bootstrapLibraryInstanceSleep
 * @return void
 */
function bootstrap_loadLibraryPOO(string &$bootstrapLibraryInstanceSleep): void {
    global $nebuleInstance, $bootstrapLibraryIID, $libraryPPCheckOK;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!$libraryPPCheckOK)
        return;

    if ($bootstrapLibraryIID != '') {
        try {
            if (class_exists('\Nebule\Library\nebule', false))
            {
                log_add('find class \Nebule\Library\nebule to instancing',
                    'info', __FUNCTION__, 'daff566b');
                if ((float)BOOTSTRAP_FUNCTION_VERSION >= (float)\Nebule\Library\nebule::NEBULE_FUNCTION_VERSION)
                {
                    if ($bootstrapLibraryInstanceSleep == '') {
                        log_add('instancing new class \Nebule\Library\nebule',
                            'info', __FUNCTION__, '79835c0f');
                        new nebule();
                    }
                    else {
                        log_add('deserialize previous class \Nebule\Library\nebule',
                            'info', __FUNCTION__, 'de329729');
                        unserialize($bootstrapLibraryInstanceSleep);
                        $bootstrapLibraryInstanceSleep = '';
                    }
                    log_reopen(BOOTSTRAP_NAME);
                } else
                    bootstrap_setBreak('43', __FUNCTION__);
            } else
                log_add('no class \Nebule\Library\nebule to instancing',
                    'error', __FUNCTION__, '60a345b8');
        } catch (\Error $e) {
            log_reopen(BOOTSTRAP_NAME);
            log_add('Library nebule load error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), 'error', __FUNCTION__, '959c188b');
            bootstrap_setBreak('44', __FUNCTION__);
        }
    }
}

/**
 * Save nebule Library POO code on session.
 *
 * @return void
 */
function bootstrap_saveLibraryPOO(): void {
    global $nebuleInstance, $bootstrapLibraryIID, $bootstrapLibraryOID, $bootstrapLibrarySID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!is_a($nebuleInstance, 'Nebule\Library\nebule'))
        return;
    log_add('serialize class \Nebule\Library\nebule', 'info', __FUNCTION__, '01fc1f8f');

    session_start();
    $_SESSION['bootstrapLibraryIID'] = $bootstrapLibraryIID;
    $_SESSION['bootstrapLibraryOID'] = $bootstrapLibraryOID;
    $_SESSION['bootstrapLibrarySID'] = $bootstrapLibrarySID;
    $_SESSION['bootstrapLibraryInstances'][$bootstrapLibraryIID] = serialize($nebuleInstance);
    session_write_close();
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

/**
 * Find app ID and code ID to use.
 *
 * @return void
 */
function bootstrap_findApplication(): void {
    global $nebuleInstance, $libraryPPCheckOK, $bootstrapApplicationIID, $bootstrapApplicationOID, $bootstrapUpdate;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $bootstrapApplicationIID = '';
    $bootstrapApplicationOID = '';

    if (!$libraryPPCheckOK || !is_a($nebuleInstance, 'Nebule\Library\nebule'))
        return;

    // Get ID of app.
    bootstrap_findApplicationAsk($bootstrapApplicationIID);
    if ($bootstrapApplicationIID == '')
        bootstrap_findApplicationSession($bootstrapApplicationIID);
    if ($bootstrapApplicationIID == '')
        bootstrap_findApplicationDefault($bootstrapApplicationIID);

    // Set code ID for internal bootstrap apps.
    session_start();
    if (strlen($bootstrapApplicationIID) < 2)
        $bootstrapApplicationOID = $bootstrapApplicationIID;
    elseif (!$bootstrapUpdate
        && isset($_SESSION['bootstrapApplicationIID'][0])
        && $_SESSION['bootstrapApplicationIID'][0] == $bootstrapApplicationIID
        && nod_checkNID($_SESSION['bootstrapApplicationIID'][0])
        && isset($_SESSION['bootstrapApplicationOID'][0])
    )
        $bootstrapApplicationOID = $_SESSION['bootstrapApplicationOID'][0];
    else
        $bootstrapApplicationOID = app_getCode($bootstrapApplicationIID);
    session_abort();

    // If running bad, use default app.
    if ($bootstrapApplicationOID == '') {
        $bootstrapApplicationIID = '0';
        $bootstrapApplicationOID = '0';
    }

    log_add('find application IID=' . $bootstrapApplicationIID . ' OID=' . $bootstrapApplicationOID,
        'info', __FUNCTION__, '5bb68dab');
}

/**
 * Check if app ID asked by user.
 *
 * @param string $bootstrapApplicationIID
 * @return void
 */
function bootstrap_findApplicationAsk(string &$bootstrapApplicationIID): void {
    global $bootstrapSwitchApplication, $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $phpNID = obj_getNID(BOOTSTRAP_CODING, LIB_REF_CODE_ALGO);

    if ($bootstrapSwitchApplication != ''
        && $bootstrapSwitchApplication != $bootstrapApplicationIID
    ) {
        log_add('ask switch application IID=' . $bootstrapSwitchApplication,
            'info', __FUNCTION__, '0cbacda8');
        if ($bootstrapSwitchApplication == '0'
            || $bootstrapSwitchApplication == '1'
            || $bootstrapSwitchApplication == '2'
            || $bootstrapSwitchApplication == '3'
            || $bootstrapSwitchApplication == '4'
            || $bootstrapSwitchApplication == '5'
            || $bootstrapSwitchApplication == '6'
            || $bootstrapSwitchApplication == '7'
            || $bootstrapSwitchApplication == '8'
            || $bootstrapSwitchApplication == '9'
            || lnk_checkExist('f',
                LIB_RID_INTERFACE_APPLICATIONS,
                $bootstrapSwitchApplication,
                $phpNID,
                $codeBranchNID)
        )
            $bootstrapApplicationIID = $bootstrapSwitchApplication;
    }
}

/**
 * If no app asked, get the app ID on session.
 *
 * @param string $bootstrapApplicationIID
 * @return void
 */
function bootstrap_findApplicationSession(string &$bootstrapApplicationIID): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    session_start();
    if (isset($_SESSION['bootstrapApplicationIID'][0])
        && (nod_checkNID($_SESSION['bootstrapApplicationIID'][0])
            || strlen($_SESSION['bootstrapApplicationIID'][0]) == 1
        )
    )
    {
        $bootstrapApplicationIID = $_SESSION['bootstrapApplicationIID'][0];
        log_add('application on session IID=' . $bootstrapApplicationIID,
            'debug', __FUNCTION__, '14e62960');
    }
    session_abort();
}

/**
 * If no app found, get the default app ID.
 *
 * @param string $bootstrapApplicationIID
 * @return void
 */
function bootstrap_findApplicationDefault(string &$bootstrapApplicationIID): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    //$defaultApplicationID = lib_getConfiguration('defaultApplication');
    $defaultApplicationID = $nebuleInstance->getConfigurationInstance()->getOptionAsString('defaultApplication');
    if ($defaultApplicationID == '0'
        || $defaultApplicationID == '1'
        || $defaultApplicationID == '2'
        || $defaultApplicationID == '3'
        || $defaultApplicationID == '4'
        || $defaultApplicationID == '5'
        || $defaultApplicationID == '6'
        || $defaultApplicationID == '7'
        || $defaultApplicationID == '8'
        || $defaultApplicationID == '9'
    )
        $bootstrapApplicationIID = $defaultApplicationID;
    elseif (nod_checkNID($defaultApplicationID)
        && io_checkNodeHaveLink($defaultApplicationID)
    )
        $bootstrapApplicationIID = $defaultApplicationID;
    elseif (lib_getOption('permitApplication1'))
        $bootstrapApplicationIID = '1';
    else
        $bootstrapApplicationIID = '0';

    log_add('use default application IID=' . $bootstrapApplicationIID,
        'debug', __FUNCTION__, '423ae49b');
}

function bootstrap_getApplicationPreload(): void {
    global $bootstrapApplicationIID, $bootstrapApplicationOID, $bootstrapApplicationNoPreload, $libraryPPCheckOK;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!$libraryPPCheckOK)
        return;

    if (isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationOID]))
        $bootstrapApplicationNoPreload = true;
    elseif (strlen($bootstrapApplicationIID) < 2)
        $bootstrapApplicationNoPreload = true;
    elseif (!$bootstrapApplicationNoPreload) {
        $bootstrapApplicationNoPreload = app_getPreload($bootstrapApplicationIID);

        if ($bootstrapApplicationNoPreload)
            log_add('do not preload application', 'info', __FUNCTION__, '0ac7d800');
    }
    else
        $bootstrapApplicationNoPreload = false;
}

function bootstrap_includeApplicationFile(): void {
    global $bootstrapApplicationOID, $libraryPPCheckOK;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (!$libraryPPCheckOK)
        return;

    //log_add('include application code OID=' . $bootstrapApplicationOID, 'info', __FUNCTION__, '8683e195');
    if ($bootstrapApplicationOID == '' || $bootstrapApplicationOID == '0') {
        log_reopen(BOOTSTRAP_NAME);
        bootstrap_setBreak('45', __FUNCTION__);
    } elseif (!io_objectInclude($bootstrapApplicationOID)) {
        log_reopen(BOOTSTRAP_NAME);
        bootstrap_setBreak('46', __FUNCTION__);
        $bootstrapApplicationOID = '0';
    }
}

function bootstrap_getApplicationNamespace(string $oid): string {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $value = '';

    $content = io_objectRead($oid, 10000);
    foreach (preg_split("/((\r?\n)|(\r\n?))/", $content) as $line) {
        $l = trim($line);

        if (str_starts_with($l, "#"))
            continue;

        $fName = trim((string)filter_var(strtok($l, ' '), FILTER_SANITIZE_STRING));
        $fValue = trim(substr_replace(filter_var(strtok(' '), FILTER_SANITIZE_STRING), '', -1));
        if ($fName == 'namespace') {
            $value = $fValue;
            break;
        }
    }
    unset($file);

    return $value;
}

/**
 * Load the application instance.
 *
 * @return void
 */
function bootstrap_instancingApplication(): void {
    global $nebuleInstance, $libraryPPCheckOK, $applicationInstance, $applicationNameSpace, $bootstrapApplicationOID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $nameSpace = bootstrap_getApplicationNamespace($bootstrapApplicationOID);
    $nameSpaceApplication = $nameSpace.'\\Application';
    $applicationNameSpace = $nameSpaceApplication;

    if ($bootstrapApplicationOID == ''
        || $bootstrapApplicationOID == '0'
        || !$libraryPPCheckOK
        || !class_exists($nameSpaceApplication, false)
    ) {
        log_add('cannot find class Application on code NID=' . $bootstrapApplicationOID . ' NS=' . $nameSpace,
            'error', __FUNCTION__, 'ea9e5908');
        return;
    }

    log_reopen($nameSpace); // . '\\' . Application::APPLICATION_NAME);

    // Get app instances from session if exist.
    $bootstrapApplicationInstanceSleep = '';
    session_start();
    if (isset($_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationOID])
        && $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationOID] != '')
        $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationOID];
    session_abort();

    try {
        if ($bootstrapApplicationInstanceSleep == '') {
            log_add('application load new instance', 'debug', __FUNCTION__, '397ce035');
            $applicationInstance = new $nameSpaceApplication($nebuleInstance);
        }
        else {
            log_add('application load serialized instance', 'debug', __FUNCTION__, 'b5f2f3f2');
            $applicationInstance = unserialize($bootstrapApplicationInstanceSleep);
        }
    } catch (\Error $e) {
        log_reopen(BOOTSTRAP_NAME);
        log_add('application load error ('  . $e->getCode() . ') : ' . $e->getFile()
            . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
            'error', __FUNCTION__, '202824cb');
        bootstrap_setBreak('47', __FUNCTION__);
    }
}

function bootstrap_initialisationApplication(bool $run): void {
    global $nebuleInstance, $applicationInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (! is_a($applicationInstance, 'Nebule\Library\Applications')) {
        log_addAndDisplay('error init application', 'error', __FUNCTION__, '41ba02a9');
        return;
    }

    $applicationInstance->setEnvironmentLibrary($nebuleInstance);
    $applicationInstance->initialisation();

    if (!$applicationInstance->askDownload()) {
        $applicationInstance->checkSecurity();
    }

    if ($run) {
        try {
            $applicationInstance->router();
        } catch (\Exception $e) {
            log_reopen(BOOTSTRAP_NAME);
            log_add('application router error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
                'error', __FUNCTION__, 'b51282b5');
        }
    }
}

function bootstrap_saveApplicationOnSession(): void {
    global $applicationInstance, $bootstrapApplicationIID, $bootstrapApplicationOID, $bootstrapApplicationSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    session_start();
    $_SESSION['bootstrapApplicationOID'][0] = $bootstrapApplicationOID;
    $_SESSION['bootstrapApplicationIID'][0] = $bootstrapApplicationIID;
    $_SESSION['bootstrapApplicationSID'][0] = $bootstrapApplicationSID;
    $_SESSION['bootstrapApplicationIID'][$bootstrapApplicationOID] = $bootstrapApplicationOID;
    if (is_a($applicationInstance, 'Nebule\Library\Applications')) {
        $_SESSION['bootstrapApplicationsInstances'][$bootstrapApplicationOID] = serialize($applicationInstance);
    }
    session_write_close();
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
function bootstrap_setBreak(string $errorCode, string $function): void {
    global $bootstrapBreak;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $errorDesc = BREAK_DESCRIPTIONS['00'];
    if (isset(BREAK_DESCRIPTIONS[$errorCode]))
        $errorDesc = BREAK_DESCRIPTIONS[$errorCode];

    $bootstrapBreak[$errorCode] = $errorDesc;
    log_add('bootstrap break code=' . $errorCode . ' : ' . $errorDesc, 'error', $function, '100000' . $errorCode);
}

function bootstrap_getUserBreak(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (filter_has_var(INPUT_GET, LIB_ARG_BOOTSTRAP_BREAK)
        || filter_has_var(INPUT_POST, LIB_ARG_BOOTSTRAP_BREAK)
    ) {
        log_add('input ' . LIB_ARG_BOOTSTRAP_BREAK . ' ask bootstrap break', 'info', __FUNCTION__, '5d008c11');
        bootstrap_setBreak('11', __FUNCTION__);
        return true;
    }
    return false;
}

function bootstrap_getInlineDisplay(): void {
    global $bootstrapInlineDisplay;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    if (filter_has_var(INPUT_GET, LIB_ARG_INLINE_DISPLAY)
        || filter_has_var(INPUT_POST, LIB_ARG_INLINE_DISPLAY)
    ) {
        log_add('ask inline display', 'info', __FUNCTION__, '82311cae');
        $bootstrapInlineDisplay = true;
    }
}

function bootstrap_checkFingerprint(): bool {
    global $nebuleLocalAuthorities, $codeBranchNID, $bootstrapCodeIID, $bootstrapCodeBID, $bootstrapCodeSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $data = file_get_contents(BOOTSTRAP_FILE_NAME);
    $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
    unset($data);

    if ($codeBranchNID == '')
        app_getCurrentBranch();

    $bootstrapListIID = app_getList(LIB_RID_INTERFACE_BOOTSTRAP, false, true);

    $links = array();
    $filter = array(
        'bl/rl/req' => 'f',
        //'bl/rl/nid1' => LIB_RID_INTERFACE_BOOTSTRAP,
        'bl/rl/nid2' => $hash,
        //'bl/rl/nid3' => $codeBranchNID,
        'bl/rl/nid4' => '',
    );
    lnk_getList($hash, $links, $filter, false);
    blk_filterBySigners($links, $nebuleLocalAuthorities);

    $result = false;
    foreach ($links as $link)
        if (isset($bootstrapListIID[$link['bl/rl/nid1']]))
        {
            $bootstrapCodeIID = $link['bl/rl/nid1'];
            $bootstrapCodeBID = $link['bl/rl/nid3'];
            $bootstrapCodeSID = $link['bs/rs1/eid'];
            $result = true;
        }
    return $result;
}

function bootstrap_getCheckFingerprint(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!bootstrap_checkFingerprint())
        bootstrap_setBreak('51', __FUNCTION__);
}



// ------------------------------------------------------------------------------------------
/**
 * Affichage du début de la page HTML.
 *
 * @return void
 */
function bootstrap_htmlHeader():void {
    global $libraryRescueMode;
log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    ?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?php echo BOOTSTRAP_NAME;
        if ($libraryRescueMode) echo ' - RESCUE' ?></title>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <meta name="author"
          content="<?php echo BOOTSTRAP_AUTHOR . ' - ' . BOOTSTRAP_WEBSITE . ' - ' . BOOTSTRAP_VERSION; ?>"/>
    <meta name="licence" content="<?php echo BOOTSTRAP_LICENCE . ' ' . BOOTSTRAP_AUTHOR; ?>"/>
    <style>
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
function bootstrap_htmlTop():void {
    global $nebuleServerEntity;
log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $name = ent_getFullName($nebuleServerEntity);
    if ($name == $nebuleServerEntity)
        $name = '/';
    $defaultApp = '0';
    if (lib_getOption('permitApplication1'))
        $defaultApp = '1';

    ?>
<body>
<div class="layout-header">
    <div class="header-left">
        <a href="/?<?php echo LIB_ARG_SWITCH_APPLICATION . '=' . $defaultApp; ?>">
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
function bootstrap_htmlBottom():void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function bootstrap_displayOnBreak(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    bootstrap_htmlHeader();
    bootstrap_htmlTop();
    bootstrap_breakDisplay1OnError();
    bootstrap_breakDisplay2Bootstrap();
    bootstrap_breakDisplay3LibraryPP();
    bootstrap_breakDisplay4LibraryPOO();
    bootstrap_breakDisplay5Application();
    bootstrap_breakDisplay6End();
    bootstrap_htmlBottom();
}

/**
 * Cette fonction affiche l'écran du bootstrap en cas d'interruption.
 * L'interruption peut être appelée par l'utilisateur ou provoqué par une erreur lors des
 * vérifications de bon fonctionnement. Les vérifications ont lieu à chaque chargement de
 * page. L'affichage est minimum, il est destiné à apparaître dans une page web déjà ouverte.
 *
 * @return void
 */
function bootstrap_inlineDisplayOnBreak(): void {
    global $bootstrapBreak, $libraryRescueMode, $bootstrapLibraryIID, $bootstrapApplicationOID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    ob_end_flush();

    echo "<div class=\"bootstrapErrorDiv\"><p>\n";
    echo '&gt; ' . BOOTSTRAP_NAME . ' ' . BOOTSTRAP_VERSION . "<br />\n";

    ksort($bootstrapBreak);
    echo 'Bootstrap break on : ';
    foreach ($bootstrapBreak as $number => $message)
        echo '- [' . $number . '] ' . $message . "<br />\n";
    if ($libraryRescueMode)
        echo "RESCUE<br />\n";

    echo 'nebule loading library : ' . $bootstrapLibraryIID . "<br />\n";
    echo 'Application loading : ' . $bootstrapApplicationOID . "<br />\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    echo "</p></div>\n";
}

function bootstrap_breakDisplay1OnError(): void {
    global $bootstrapBreak, $libraryRescueMode, $bootstrapFlush;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    log_add('1: on error', 'info', __FUNCTION__, 'df175c8c');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#1 ' . BOOTSTRAP_NAME . ' break on</span><br/>' . "\n";

    ksort($bootstrapBreak);
    foreach ($bootstrapBreak as $number => $message)
        echo '- [' . $number . '] <span class="error">' . $message . '</span>' . "<br />\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    if ($libraryRescueMode)
        echo "RESCUE mode<br />\n";
    if ($bootstrapFlush)
        echo "FLUSH<br />\n";
    if (sizeof($bootstrapBreak) != 0 && isset($bootstrapBreak[12]))
        echo "<a href=\"?a=0\">? Return to application 0</a><br />\n";
    $sessionId = session_id();
    echo '<a href="?f">? Flush PHP session</a> (' . substr(crypto_getDataHash($sessionId), 0, 6) . ')' . "\n";

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay2Bootstrap(): void {
    global $bootstrapCodeIID, $bootstrapCodeBID, $bootstrapCodeSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    log_add('2: bootstrap', 'info', __FUNCTION__, 'a844ce2c');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#2 bootstrap</span><br/>' . "\n";

    $data = file_get_contents(BOOTSTRAP_FILE_NAME);
    $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
    unset($data);

    bootstrap_echoLineTitle('bootstrap RID');
    bootstrap_echoLinkNID(LIB_RID_INTERFACE_BOOTSTRAP);
    echo "<br />\n";
    bootstrap_echoLineTitle('bootstrap BID');
    bootstrap_echoLinkNID($bootstrapCodeBID);
    echo "<br />\n";
    bootstrap_echoLineTitle('bootstrap CID');
    bootstrap_echoLinkNID(obj_getNID(BOOTSTRAP_CODING, LIB_REF_CODE_ALGO));
    echo "<br />\n";
    bootstrap_echoLineTitle('bootstrap IID');
    bootstrap_echoLinkNID($bootstrapCodeIID);
    echo "<br />\n";
    bootstrap_echoLineTitle('bootstrap OID');
    bootstrap_echoLinkNID($hash);
    echo ' ';
    bootstrap_echoEndLineTest(bootstrap_checkFingerprint());
    bootstrap_echoLineTitle('bootstrap SID');
    bootstrap_echoLinkNID($bootstrapCodeSID);
    echo "<br />\n";

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay3LibraryPP(): void {
    global $nebuleSecurityAuthorities, $nebuleCodeAuthorities, $nebuleDirectoryAuthorities, $nebuleTimeAuthorities,
           $nebuleServerEntity, $nebuleDefaultEntity, $nebuleGhostPublicEntity, $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    log_add('3: library PP', 'info', __FUNCTION__, '76c10058');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#3 nebule library PP</span><br/>' . "\n";

    bootstrap_echoLineTitle('library version');
    echo BOOTSTRAP_VERSION . "<br/>\n";

    bootstrap_echoLineTitle('puppetmaster');
    bootstrap_echoLinkNID(lib_getOption('puppetmaster'));
    echo "<br/>\n";

    foreach ($nebuleSecurityAuthorities as $m)
    {
        bootstrap_echoLineTitle('security authority');
        bootstrap_echoLinkNID($m);
        echo "<br/>\n";
    }

    foreach ($nebuleCodeAuthorities as $m)
    {
        bootstrap_echoLineTitle('code authority');
        bootstrap_echoLinkNID($m);
        echo "<br/>\n";
    }

    foreach ($nebuleDirectoryAuthorities as $m)
    {
        bootstrap_echoLineTitle('directory authority');
        bootstrap_echoLinkNID($m);
        echo "<br/>\n";
    }

    foreach ($nebuleTimeAuthorities as $m)
    {
        bootstrap_echoLineTitle('time authority');
        bootstrap_echoLinkNID($m);
        echo "<br/>\n";
    }

    bootstrap_echoLineTitle('server entity');
    bootstrap_echoLinkNID($nebuleServerEntity);
    echo "<br/>\n";

    bootstrap_echoLineTitle('default entity');
    bootstrap_echoLinkNID($nebuleDefaultEntity);
    echo "<br/>\n";

    bootstrap_echoLineTitle('ghost entity');
    bootstrap_echoLinkNID($nebuleGhostPublicEntity);
    echo "<br/>\n";

    $codeBranchName = lib_getOption('codeBranch');
    if ($codeBranchName == '')
        $codeBranchName = LIB_CONFIGURATIONS_DEFAULT['codeBranch'];
    bootstrap_echoLineTitle('code branch');
    bootstrap_echoLinkNID($codeBranchNID);
    echo ' (' . $codeBranchName . ")<br />\n";

    bootstrap_echoLineTitle('php version');
    echo 'found ' . phpversion() . ', need >= ' . PHP_VERSION_MINIMUM;

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay4LibraryPOO(): void {
    global $nebuleInstance, $bootstrapLibraryIID, $bootstrapLibraryOID, $bootstrapLibrarySID, $nebuleLibVersion;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    log_add('4: library POO', 'info', __FUNCTION__, '811b1513');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#4 nebule library POO</span><br/>';
    flush();

    echo "tL=" . lib_getMetrologyTimer('tL') . "<br />\n";

    bootstrap_echoLineTitle('library RID');
    bootstrap_echoLinkNID(LIB_RID_INTERFACE_LIBRARY);
    echo "<br />\n";
    bootstrap_echoLineTitle('library IID');
    bootstrap_echoLinkNID($bootstrapLibraryIID);
    echo "<br />\n";
    bootstrap_echoLineTitle('library OID');
    bootstrap_echoLinkNID($bootstrapLibraryOID);
    if ($bootstrapLibraryOID != '')
        echo ' version ' . $nebuleLibVersion;
    echo "<br />\n";

    if ($nebuleInstance instanceof \Nebule\Library\nebule) {
        bootstrap_echoLineTitle('library SID');
        bootstrap_echoLinkNID($bootstrapLibrarySID);
        echo "<br />\n";
        bootstrap_echoLineTitle('functional level');
        echo 'found ' . \Nebule\Library\nebule::NEBULE_FUNCTION_VERSION . ', need >= ' . BOOTSTRAP_FUNCTION_VERSION;
        echo "<br />\n";
        if ($nebuleInstance->getLoadingStatus()) {
            bootstrap_breakDisplay41LibraryEntities();
            bootstrap_breakDisplay42LibraryCryptography();
            bootstrap_breakDisplay43LibraryIO();
            bootstrap_breakDisplay44LibrarySocial();
            bootstrap_breakDisplay45LibraryStats();
        } else {
            bootstrap_echoLineTitle('library status');
            echo '<span class="error">Loading failed</span>' . "\n";
        }
    } else {
        bootstrap_echoLineTitle('library status');
        echo '<span class="error">Not loaded</span>' . "\n";
    }

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay41LibraryEntities(): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $nebuleInstanceCheck = $nebuleInstance->checkInstances();

    bootstrap_breakDisplay411DisplayEntity('puppetmaster',
        array($nebuleInstance->getAuthoritiesInstance()->getPuppetmasterEID() => $nebuleInstance->getAuthoritiesInstance()->getPuppetmasterEID()),
        array($nebuleInstance->getAuthoritiesInstance()->getPuppetmasterEID() => $nebuleInstance->getAuthoritiesInstance()->getPuppetmasterInstance()),
        $nebuleInstanceCheck > 10);

    bootstrap_breakDisplay411DisplayEntity('security authority',
        $nebuleInstance->getAuthoritiesInstance()->getSecurityAuthoritiesEID(),
        $nebuleInstance->getAuthoritiesInstance()->getSecurityAuthoritiesInstance(),
        $nebuleInstanceCheck > 20);

    bootstrap_breakDisplay411DisplayEntity('code authority',
        $nebuleInstance->getAuthoritiesInstance()->getCodeAuthoritiesEID(),
        $nebuleInstance->getAuthoritiesInstance()->getSecurityAuthoritiesInstance(),
        $nebuleInstanceCheck > 30);

    bootstrap_breakDisplay411DisplayEntity('directory authority',
        $nebuleInstance->getAuthoritiesInstance()->getDirectoryAuthoritiesEID(),
        $nebuleInstance->getAuthoritiesInstance()->getDirectoryAuthoritiesInstance(),
        $nebuleInstanceCheck > 40);

    bootstrap_breakDisplay411DisplayEntity('time authority',
        $nebuleInstance->getAuthoritiesInstance()->getTimeAuthoritiesEID(),
        $nebuleInstance->getAuthoritiesInstance()->getTimeAuthoritiesInstance(),
        $nebuleInstanceCheck > 50);

    bootstrap_breakDisplay411DisplayEntity('server entity',
        array($nebuleInstance->getEntitiesInstance()->getServerEntityEID() => $nebuleInstance->getEntitiesInstance()->getServerEntityEID()),
        array($nebuleInstance->getEntitiesInstance()->getServerEntityEID() => $nebuleInstance->getEntitiesInstance()->getServerEntityInstance()),
        $nebuleInstanceCheck > 60);

    bootstrap_breakDisplay411DisplayEntity('default entity',
        array($nebuleInstance->getEntitiesInstance()->getDefaultEntityEID() => $nebuleInstance->getEntitiesInstance()->getDefaultEntityEID()),
        array($nebuleInstance->getEntitiesInstance()->getDefaultEntityEID() => $nebuleInstance->getEntitiesInstance()->getDefaultEntityInstance()),
        $nebuleInstanceCheck > 70);

    bootstrap_breakDisplay411DisplayEntity('ghost entity',
        array($nebuleInstance->getEntitiesInstance()->getGhostEntityEID() => $nebuleInstance->getEntitiesInstance()->getGhostEntityEID()),
        array($nebuleInstance->getEntitiesInstance()->getGhostEntityEID() => $nebuleInstance->getEntitiesInstance()->getGhostEntityInstance()),
        $nebuleInstanceCheck > 80);

    bootstrap_breakDisplay411DisplayEntity('connected entity',
        array($nebuleInstance->getEntitiesInstance()->getConnectedEntityEID() => $nebuleInstance->getEntitiesInstance()->getConnectedEntityEID()),
        array($nebuleInstance->getEntitiesInstance()->getConnectedEntityEID() => $nebuleInstance->getEntitiesInstance()->getConnectedEntityInstance()),
        $nebuleInstanceCheck > 80);

    $entity = lib_getOption('subordinationEntity');
    if ($entity != '') {
        $instance = $nebuleInstance->getCacheInstance()->newNode($entity, Cache::TYPE_ENTITY);
        bootstrap_breakDisplay411DisplayEntity('subordination',
            array($entity => $entity),
            array($entity => $instance),
            $nebuleInstanceCheck > 80);
    } else {
        bootstrap_echoLineTitle('subordination');
        echo "none<br />\n";
    }


    if ($nebuleInstanceCheck != 128)
    {
        bootstrap_echoLineTitle('entities error level');
        echo (string)$nebuleInstanceCheck . "<br />\n";
    }
}

function bootstrap_breakDisplay411DisplayEntity(string $title, array $listEID, array $listInstances, bool $ok): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    foreach ($listEID as $eid)
    {
        bootstrap_echoLineTitle($title);

        $name = $eid;
        if (isset($listInstances[$eid]) && is_a($listInstances[$eid], 'Nebule\Library\Entity'))
            $name = $listInstances[$eid]->getName();

        if ($ok)
        {
            bootstrap_echoLinkNID($eid, $name);
            echo ' OK';
            if ($nebuleInstance->getAuthoritiesInstance()->getIsLocalAuthorityEID($eid))
                echo ' (local authority)';
            if ($nebuleInstance->getAuthoritiesInstance()->getIsGlobalAuthorityEID($eid))
                echo ' (global authority)';
            if (isset($listInstances[$eid]) && is_a($listInstances[$eid], 'Nebule\Library\Entity') && $listInstances[$eid]->getHavePrivateKeyPassword())
                echo ' (unlocked)';
        } else
            echo '<span class="error">ERROR!</span>';
        echo  "<br />\n";
    }
}

function bootstrap_breakDisplay42LibraryCryptography(): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $_cryptoInstance = $nebuleInstance->getCryptoInstance();
    $_configurationInstance = $nebuleInstance->getConfigurationInstance();

    bootstrap_echoLineTitle('cryptography class');
    if (is_object($_cryptoInstance)) {
        echo $_cryptoInstance->getCryptoInstanceName() . "<br />\n";

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
        $entropy = (string)$_cryptoInstance->getEntropy($random);
        bootstrap_echoLineTitle('cryptography');
        echo 'pseudo-random entropy ' . $entropy . '/8 ';
        bootstrap_echoEndLineTest($entropy > 7.85);
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay43LibraryIO(): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ioInstance = $nebuleInstance->getIoInstance();

    bootstrap_echoLineTitle('i/o class');
    if (is_object($ioInstance)) {
        echo get_class($ioInstance);
        echo "<br />\n";

        $list = $ioInstance->getModulesList();
        foreach ($list as $class) {
            $module = $ioInstance->getModuleByType($class);
            bootstrap_echoLineTitle('i/o');
            if (is_a($module, 'Nebule\Library\io')) {
                echo get_class($module) . ' (' . $module->getMode() . ') ' . $module->getLocation() . ', links ';
                if (!$module->checkLinksDirectory())
                    echo 'directory <span class="error">ERROR!</span>';
                else {
                    if (!$module->checkLinksRead())
                        echo 'read <span class="error">ERROR!</span>';
                    else {
                        if ($module->getMode() == 'RO')
                            echo 'OK no write.';
                        elseif ($module->checkLinksWrite())
                            echo 'OK';
                        else
                            echo 'write <span class="error">ERROR!</span>';
                    }
                }
                echo ', objects ';
                if (!$module->checkObjectsDirectory())
                    echo 'directory <span class="error">ERROR!</span>';
                else {
                    if (!$module->checkObjectsRead())
                        echo 'read <span class="error">ERROR!</span>';
                    else {
                        if ($module->getMode() == 'RO')
                            echo 'OK no write.';
                        elseif ($module->checkObjectsWrite())
                            echo 'OK';
                        else
                            echo 'write <span class="error">ERROR!</span>';
                    }
                }
            } else
                echo get_class($module) . ' error';
            echo "<br />\n";
        }
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay44LibrarySocial(): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $socialInstance = $nebuleInstance->getSocialInstance();

    bootstrap_echoLineTitle('social class');
    if (is_object($socialInstance)) {
        echo get_class($socialInstance);
        echo "<br />\n";

        foreach ($socialInstance->getSocialInstances() as $moduleInstance)
        {
            bootstrap_echoLineTitle('social');
            if (is_subclass_of($moduleInstance, 'Nebule\Library\Social', false))
            {
                echo get_class($moduleInstance);
                bootstrap_echoEndLineTest(true);
            } else
                bootstrap_echoEndLineTest(false);
        }
    } else
        bootstrap_echoEndLineTest(false);
}

function bootstrap_breakDisplay45LibraryStats(): void {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    bootstrap_echoLineTitle('metrology inputs');
    echo 'Lr=' . lib_getMetrology('lr') . '+' . $nebuleInstance?->getMetrologyInstance()->getLinkRead() . ' ';
    echo 'Lv=' . lib_getMetrology('lv') . '+' . $nebuleInstance?->getMetrologyInstance()->getLinkVerify() . ' ';
    echo 'Or=' . lib_getMetrology('or') . '+' . $nebuleInstance?->getMetrologyInstance()->getObjectRead() . ' ';
    echo 'Ov=' . lib_getMetrology('or') . '+' . $nebuleInstance?->getMetrologyInstance()->getObjectVerify() . " (PP+POO)<br />\n";
    bootstrap_echoLineTitle('metrology buffers');
    echo 'Lc=' . $nebuleInstance?->getCacheInstance()->getCacheLinkSize() . ' ';
    echo 'Oc=' . $nebuleInstance?->getCacheInstance()->getCacheObjectSize() . ' ';
    echo 'Ec=' . $nebuleInstance?->getCacheInstance()->getCacheEntitySize() . ' ';
    echo 'Gc=' . $nebuleInstance?->getCacheInstance()->getCacheGroupSize() . ' ';
    echo 'Cc=' . $nebuleInstance?->getCacheInstance()->getCacheConversationSize() . ' ';
    echo 'CUc=' . $nebuleInstance?->getCacheInstance()->getCacheCurrencySize() . ' ';
    echo 'CPc=' . $nebuleInstance?->getCacheInstance()->getCacheTokenPoolSize() . ' ';
    echo 'CTc=' . $nebuleInstance?->getCacheInstance()->getCacheTokenSize() . ' ';
    echo 'CWc=' . $nebuleInstance?->getCacheInstance()->getCacheWalletSize();
}

function bootstrap_breakDisplay5Application(): void {
    global $bootstrapApplicationIID, $bootstrapApplicationOID, $bootstrapApplicationSID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    log_add('5: application', 'info', __FUNCTION__, '7f1941a1');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#5 application</span><br/>' . "\n";

    bootstrap_echoLineTitle('application RID');
    bootstrap_echoLinkNID(LIB_RID_INTERFACE_APPLICATIONS);
    echo "<br />\n";
    bootstrap_echoLineTitle('application IID');
    bootstrap_echoLinkNID($bootstrapApplicationIID);
    echo ' <a href="/?' . LIB_ARG_SWITCH_APPLICATION . '=' . $bootstrapApplicationIID  . '">load</a><br/>' . "\n";
    bootstrap_echoLineTitle('application OID');
    bootstrap_echoLinkNID($bootstrapApplicationOID);
    echo "<br />\n";
    bootstrap_echoLineTitle('application SID');
    bootstrap_echoLinkNID($bootstrapApplicationSID);
    echo "\n";

    echo '</div>' . "\n";
}

function bootstrap_breakDisplay6End(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    lib_setMetrologyTimer('tE');

    log_add('6: end', 'info', __FUNCTION__, '03a39126');
    echo '<div class="parts">' . "\n" . '<span class="partstitle">#6 end ' . BOOTSTRAP_NAME . '</span><br/>' . "\n";

    echo 'tE=' . lib_getMetrologyTimer('tE') . '<br/>' . "\n";

    echo '</div>' . "\n";
}

function bootstrap_echoLineTitle(string $title): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $maxSize = 20;
    $title = trim($title);
    if (strlen($title) > $maxSize)
        $title = substr($title, 0, $maxSize);
    $c = strlen($title);
    while ($c <= $maxSize)
    {
        $title .= '&nbsp;';
        $c++;
    }
    echo $title . ' : ';
}

function bootstrap_echoEndLineTest(bool $test, string $suffix = ''): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($test)
        echo ' OK ' . $suffix;
    else
        echo ' <span class="error">ERROR!</span>';

    echo "<br />\n";
}

function bootstrap_echoLinkNID(string $nid, string $name = ''): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if ($name == '')
        $name = $nid;
    if (nod_checkNID($nid))
        echo '<a href="?a=4&l=' . $nid . '">' . $name . '</a>';
    else
        echo $name;
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
 * Load and run application without preload.
 *
 * @return void
 */
function bootstrap_displayNoPreloadApplication(): void {
    global $bootstrapApplicationOID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    log_add('load application without preload ' . $bootstrapApplicationOID, 'info', __FUNCTION__, 'e01ea813');
    bootstrap_includeApplicationFile();
    bootstrap_instancingApplication();
    bootstrap_initialisationApplication(true);
}

/**
 * Load and run preload of an application.
 *
 * @return void
 */
function bootstrap_displayPreloadApplication(): void {
    global $nebuleInstance, $applicationInstance, $applicationNameSpace, $bootstrapLibraryIID, $bootstrapApplicationOID, $bootstrapApplicationIID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // Initialisation des logs
    log_reopen('preload');
    //log_add('Loading library POO', 'info', __FUNCTION__, 'ce5879b0');

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    echo '<div class="preload">' . "\n";
    echo "Please wait...<br/>\n";
    echo 'tB=' . lib_getMetrologyTimer('tB') . "<br />\n";
    echo '</div>' . "\n";
    flush();

    echo '<div class="preload">' . "\n";
    echo '<img title="bootstrap" style="background:#ababab;" alt="[]" src="' . LIB_BOOTSTRAP_ICON . '"/>' . "\n";
    echo 'Load nebule library POO<br/>' . "\n";
    echo 'ID=' . $bootstrapLibraryIID . "\n";
    echo 'tL=' . lib_getMetrologyTimer('tL') . "<br />\n";
    echo '</div>' . "\n";
    flush();

    echo '<div class="preload">' . "\n";
    echo '<img title="bootstrap" style="background:#' . substr($bootstrapApplicationIID . '000000', 0, 6) . ';"' . "\n";
    echo 'alt="[]" src="' . LIB_BOOTSTRAP_ICON . '"/>' . "\n";
    echo 'Load application<br/>' . "\n";
    echo 'IID=' . $bootstrapApplicationIID . "\n";
    echo 'OID=' . $bootstrapApplicationOID;
    if ($bootstrapApplicationOID == '0')
        echo ' <span class="error">ERROR!</span>';
    echo '<br/>' . "\n";
    flush();

    log_add('preload application code OID=' . $bootstrapApplicationOID, 'info', __FUNCTION__, '8d24b491');
    bootstrap_includeApplicationFile();
    bootstrap_instancingApplication();
    bootstrap_initialisationApplication(false);

    if ($applicationInstance === null) {
        log_add('error preload application code OID=' . $bootstrapApplicationOID . ' null', 'error', __FUNCTION__, '9c685c75');
        return;
    }
    if (!is_a($applicationInstance, $applicationNameSpace)) {
        log_add('error preload application code OID=' . $bootstrapApplicationOID . ' classe=' . get_class($applicationInstance), 'error', __FUNCTION__, '2e87a827');
        return;
    }

    echo 'Name=' . $applicationInstance->getClassName() . "<br/>\n";

    // Récupération des éléments annexes nécessaires à l'affichage de l'application.
    echo 'sync<span class="preloadsync">' . "\n";
    $items = $applicationInstance->getDisplayInstance()->getNeededObjectsList();
    $nb = 0;
    foreach ($items as $item) {
        if (!$nebuleInstance->getIoInstance()->checkObjectPresent($item)) {
            $instance = $nebuleInstance->getCacheInstance()->newNode($item);
            $applicationInstance->getDisplayInstance()->displayInlineObjectColorNolink($instance);
            echo "\n";
            $instance->syncObject(false);
            $nb++;
        }
    }
    if ($nb == 0)
        echo '-';
    echo '</span><br/>' . "\n";
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
function bootstrap_partDisplayReloadPage(bool $ok = true, int $delay = 0): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
function bootstrap_displayApplicationFirst(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    bootstrap_firstInitEnv();
    bootstrap_htmlHeader();
    bootstrap_htmlTop();
    bootstrap_firstDisplay1Breaks();
    $ok = bootstrap_firstDisplay2Folders();
    if ($ok)
        $ok = bootstrap_firstDisplay3ReservedObjects();
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
    if ($ok)
        $ok = bootstrap_firstDisplay10NeededObjects();

    echo '<div id="parts">';
    if ($ok)
        echo '&nbsp;&nbsp;OK finished!' . "\n";
    else
        echo '&gt; <span class="error">ERROR!</span>' . "\n";
    echo '<br />? <span id="reload"><a href="">When ready, reload</a></span></div>' . "\n";

    bootstrap_htmlBottom();
}

function bootstrap_firstInitEnv() {
    global $optionCache, $nebuleCacheIsPublicKey;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $optionCache['permitWrite'] = true;
    $optionCache['permitWriteObject'] = true;
    $optionCache['permitSynchronizeObject'] = true;
    $optionCache['permitWriteLink'] = true;
    $optionCache['permitSynchronizeLink'] = true;
    $optionCache['permitWriteEntity'] = true;
    $optionCache['permitBufferIO'] = false;
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
function bootstrap_firstDisplay1Breaks(): void {
    global $bootstrapBreak, $libraryRescueMode;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#1 ' . BOOTSTRAP_NAME . ' break on (need first init)</span><br/>' . "\n";
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
function bootstrap_firstDisplay2Folders(): bool {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
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
 * Create if not present reserved objects needed by nebule.
 *
 * @return bool
 */
function bootstrap_firstDisplay3ReservedObjects(): bool {
    global $nebuleInstance;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#3 reserved objects</span><br/>' . "\no: ";

    foreach (LIB_FIRST_LOCALISATIONS as $data) {
        $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
        if (io_checkNodeHaveContent($hash))
            echo '.';
        else {
            log_add('need create objects ' . $hash, 'warn', __FUNCTION__, 'ca195598');
            if (io_objectWrite($data, $hash))
                echo '+';
            else {
                $ok = false;
                echo 'E';
            }
        }
    }
    if ($nebuleInstance instanceof \Nebule\Library\nebule) {
        foreach (\Nebule\Library\References::RESERVED_OBJECTS_LIST as $data) {
            $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
            if (io_checkNodeHaveContent($hash))
                echo '.';
            else {
                log_add('need create objects ' . $hash, 'warn', __FUNCTION__, 'ca99e341');
                if (io_objectWrite($data, $hash))
                    echo '+';
                else {
                    $ok = false;
                    echo 'E';
                }
            }
        }
    } else {
        foreach (LIB_FIRST_RESERVED_OBJECTS as $data) {
            $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
            if (io_checkNodeHaveContent($hash))
                echo '.';
            else {
                log_add('need create objects ' . $hash, 'warn', __FUNCTION__, 'fc68d2ff');
                if (io_objectWrite($data, $hash))
                    echo '+';
                else {
                    $ok = false;
                    echo 'E';
                }
            }
        }
    }
    foreach (LIB_FIRST_AUTHORITIES_PUBLIC_KEY as $data)
    {
        $hash = obj_getNID($data, lib_getOption('cryptoHashAlgorithm'));
        if (io_checkNodeHaveContent($hash))
            echo '.';
        else {
            log_add('need create objects ' . $hash, 'warn', __FUNCTION__, '6a7b99a6');
            if (io_objectWrite($data, $hash))
                echo '+';
            else {
                $ok = false;
                echo 'E';
            }
        }
    }
    foreach (LIB_FIRST_LINKS as $link)
    {
        $algo = 'sha2.256';
        if (blk_checkExist($link))
            echo '.';
        else {
            $hash = hash(crypto_getTranslatedHashAlgo($algo), $link);
            log_add('need create link ' . $hash, 'warn', __FUNCTION__, '8d9c24e2');
            if (blk_write($link))
                echo '+';
            else {
                $ok = false;
                echo 'E';
            }
        }
    }

    echo '<br />';
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
function bootstrap_firstDisplay4Puppetmaster(): bool {
    global $firstAlternativePuppetmasterEid;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#4 puppetmaster</span><br/>' . "\n";

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        if (!filter_has_var(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_EID)) {
            log_add('query puppetmaster eid', 'info', __FUNCTION__, '70a99b83');
            ?>
            <form action="" method="get">
                <div>
                    <label for="oid">EID &nbsp;&nbsp;&nbsp;&nbsp; :</label>
                    <input type="text" id="eid" name="<?php echo LIB_ARG_FIRST_PUPPETMASTER_EID; ?>"
                           value="<?php echo LIB_DEFAULT_PUPPETMASTER_EID; ?>"/>
                </div>
                <div>
                    <label for="loc">Location :</label>
                    <input type="text" id="loc" name="<?php echo LIB_ARG_FIRST_PUPPETMASTER_LOCATION; ?>"
                           value="<?php echo LIB_DEFAULT_PUPPETMASTER_LOCATION; ?>"/>
                </div>
                <div class="button">
                    <button type="submit">Submit</button>
                </div>
            </form>

            <?php
            $ok = false;
        } else {
            $argEID = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_EID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            log_add('input ' . LIB_ARG_FIRST_PUPPETMASTER_EID . ' ask define alternative puppetmaster eid=' . $argEID, 'warn', __FUNCTION__, '10a0bd6d');
            $argLocation = LIB_DEFAULT_PUPPETMASTER_LOCATION;
            if (ent_checkIsPublicKey($argEID))
                $firstAlternativePuppetmasterEid = $argEID;
            if (filter_has_var(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_LOCATION)) {
                echo 'try alternative puppetmaster eid : ' . $argEID . ' ';
                if (nod_checkNID($argEID, false)) {
                    $firstAlternativePuppetmasterEid = $argEID;
                    $argLocation = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_PUPPETMASTER_LOCATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                    log_add('input ' . LIB_ARG_FIRST_PUPPETMASTER_LOCATION . ' ask define alternative puppetmaster location=' . $argLocation, 'info', __FUNCTION__, '6d54e19e');
                    if (strlen($argLocation) != 0 && filter_var($argLocation, FILTER_VALIDATE_URL) !== false) {
                        echo 'sync...';
                        obj_getDistantContent($argEID, array($argLocation));
                        lnk_getDistantOnLocations($argEID, array($argLocation));
                    }
                }
                if (!ent_checkIsPublicKey($argEID)) {
                    log_add('unable to find alternative puppetmaster eid', 'error', __FUNCTION__, '102c9011');
                    echo " <span class=\"error\">invalid!</span>\n";
                    $firstAlternativePuppetmasterEid = LIB_DEFAULT_PUPPETMASTER_EID;
                }
                echo "<br />\n";
                echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: ' . $firstAlternativePuppetmasterEid . "<br />\n";
                echo 'location on &nbsp;&nbsp;&nbsp;&nbsp; : ' . $argLocation . "\n";
            } else
                echo 'puppetmaster &nbsp;&nbsp;&nbsp;&nbsp;: ' . $firstAlternativePuppetmasterEid . "\n";
        }
    } else
        echo 'forced to ' . lib_getOption('puppetmaster') . "\n";

    echo "</div>\n";

    return $ok;
}

/**
 * Synchronize the entities needed by nebule.
 *
 * @return bool
 */
function bootstrap_firstDisplay5SyncAuthorities(): bool {
    global $nebuleLocalAuthorities;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#5 synchronizing authorities</span><br/>' . "\n";

    $puppetmaster = lib_getOption('puppetmaster');

    bootstrap_echoLineTitle('puppetmaster');
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

    bootstrap_echoLineTitle('security authorities');
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

    bootstrap_echoLineTitle('code authorities');
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

    bootstrap_echoLineTitle('time authorities');
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

    bootstrap_echoLineTitle('directory authorities');
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
function bootstrap_firstDisplay6SyncObjects(): bool {
    global $codeBranchNID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;
    $refAppsID = LIB_RID_INTERFACE_APPLICATIONS;
    $refLibID = LIB_RID_INTERFACE_LIBRARY;
    $refBootID = LIB_RID_INTERFACE_BOOTSTRAP;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#6 synchronizing objets</span><br/>' . "\n";

    // Si la bibliothèque ne se charge pas correctement, fait une première synchronisation des entités.
    if (!io_checkNodeHaveLink($refAppsID)
        || !io_checkNodeHaveLink($refLibID)
        || !io_checkNodeHaveLink($refBootID)
    ) {
        log_add('need sync reference objects', 'warn', __FUNCTION__, '0f21ad26');

        app_getCurrentBranch();
        echo "<br />\ncode branch RID &nbsp;&nbsp;: " . $codeBranchNID . ' ';
        lnk_getDistantOnLocations($codeBranchNID, LIB_FIRST_LOCALISATIONS);

        echo "<br />\nbootstrap RID &nbsp;&nbsp;&nbsp;&nbsp;: " . $refBootID . ' ';
        lnk_getDistantOnLocations($refBootID, LIB_FIRST_LOCALISATIONS);

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

/**
 * Ask for subordination of the local entity.
 *
 * @return bool
 */
function bootstrap_firstDisplay7Subordination(): bool {
    global $firstAlternativePuppetmasterEid, $firstSubordinationEID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#7 subordination</span><br/>' . "\n";

    if (!file_exists(LIB_LOCAL_ENVIRONMENT_FILE)) {
        if (!filter_has_var(INPUT_GET, LIB_ARG_FIRST_SUBORDINATION_EID)) {
            log_add('query subordination eid', 'info', __FUNCTION__, '213a735c');
            ?>
            <form action="" method="get">
                <div>
                    <label for="oid">EID &nbsp;&nbsp;&nbsp;&nbsp; :</label>
                    <input type="text" id="eid" name="<?php echo LIB_ARG_FIRST_SUBORDINATION_EID; ?>"/>
                </div>
                <div>
                    <label for="loc">Location :</label>
                    <input type="text" id="loc" name="<?php echo LIB_ARG_FIRST_SUBORDINATION_LOCATION; ?>"/>
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
            $argEID = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_SUBORDINATION_EID, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
            log_add('input ' . LIB_ARG_FIRST_SUBORDINATION_EID . ' ask define subordination eid=' . $argEID, 'warn', __FUNCTION__, 'a875618e');
            if (nod_checkNID($argEID, false)) {
                echo 'try alternative subordination eid : ' . $argEID . ' ';
                $firstSubordinationEID = $argEID;
                $argLocation = trim(' ' . filter_input(INPUT_GET, LIB_ARG_FIRST_SUBORDINATION_LOCATION, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
                log_add('input ' . LIB_ARG_FIRST_SUBORDINATION_LOCATION . ' ask define subordination location=' . $argLocation, 'info', __FUNCTION__, 'c1c943a5');
                if (strlen($argLocation) != 0 && filter_var($argLocation, FILTER_VALIDATE_URL) !== false) {
                    echo 'sync...';
                    obj_getDistantContent($argEID, array($argLocation));
                    lnk_getDistantOnLocations($argEID, array($argLocation));
                }
            } else {
                log_add('unable to find subordination eid', 'error', __FUNCTION__, '5cd18917');
                echo " <span class=\"error\">invalid!</span>\n";
                $argLocation = '';
                $firstSubordinationEID = '';
            }
            echo "<br />\n";
            echo 'subordination to : ' . $firstSubordinationEID . "<br />\n";
            echo 'location on &nbsp;&nbsp;&nbsp;&nbsp; : ' . $argLocation . "\n";
        }
    } else {
        $firstSubordinationEID = lib_getOption('subordinationEntity');
        bootstrap_echoLineTitle('subordination to');
        echo $firstSubordinationEID . "\n";
    }

    echo "</div>\n";

    return $ok;
}

/**
 * Crée le fichier des options par défaut.
 *
 * @return bool
 */
function bootstrap_firstDisplay8OptionsFile(): bool {
    global $firstAlternativePuppetmasterEid, $firstSubordinationEID;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#8 options file</span><br/>' . "\n";

    $defaultOptions = "# Generated by the " . BOOTSTRAP_NAME . ", part of the " . BOOTSTRAP_AUTHOR . ".\n";
    $defaultOptions .= "# " . BOOTSTRAP_SURNAME . "\n";
    $defaultOptions .= "# Version : " . BOOTSTRAP_VERSION . "\n";
    $defaultOptions .= "# http://" . BOOTSTRAP_WEBSITE . "\n";
    $defaultOptions .= "\n";
    $defaultOptions .= "# nebule php\n";
    $defaultOptions .= "# Options written here are write-protected for the library and all applications.\n";
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
        if (LIB_CONFIGURATIONS_TYPE[$option] == 'boolean' && $value != 'true')
            $defaultOptions .= 'false';
        else
            $defaultOptions .= $value;
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

/**
 * Crée le fichier des options par défaut.
 *
 * @return bool
 * @throws /Random/RandomException
 */
function bootstrap_firstDisplay9LocaleEntity(): bool {
    global $nebuleInstance, $nebuleGhostPublicEntity, $nebuleGhostPrivateEntity, $nebuleGhostPasswordEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#9 local entity for server</span><br/>' . "\n";

    if (is_a($nebuleInstance, 'Nebule\Library\nebule')) {
        if (file_put_contents(LIB_LOCAL_ENTITY_FILE, '0') !== false) {
            echo 'new server entity<br/>' . "\n";

            $nebuleGhostPasswordEntity = '';
            try {
                $newPasswd = random_bytes(LIB_FIRST_GENERATED_PASSWORD_SIZE * 20);
            } catch (\Exception $e) {
                $newPasswd = 'ERROR GEN RANDOM ';
            }
            $nebuleGhostPasswordEntity .= preg_replace('/[^a-zA-Z0-9,;:*&#+=_-]/', '', $newPasswd);
            $nebuleGhostPasswordEntity = (string)substr($nebuleGhostPasswordEntity, 0, LIB_FIRST_GENERATED_PASSWORD_SIZE);
            /** @noinspection PhpUnusedLocalVariableInspection */
            $newPasswd = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789';
            /** @noinspection PhpUnusedLocalVariableInspection */
            $newPasswd = null;

            $nebuleGhostPublicEntity = '0';
            $nebuleGhostPrivateEntity = '0';
            // Génère une nouvelle entité.
            ent_generate(
                lib_getOption('cryptoAsymmetricAlgorithm'),
                lib_getOption('cryptoHashAlgorithm'),
                $nebuleGhostPublicEntity,
                $nebuleGhostPrivateEntity,
                $nebuleGhostPasswordEntity
            );
            log_add('switch to new entity ' . $nebuleGhostPublicEntity, 'warn', __FUNCTION__, '94c27df0');

            // Définit l'entité comme entité instance du serveur.
            file_put_contents(LIB_LOCAL_ENTITY_FILE, $nebuleGhostPublicEntity);
            $instancePubKey = $nebuleInstance->getCacheInstance()->newNode('0', \Nebule\Library\Cache::TYPE_ENTITY);
            $instancePubKey->setContent($nebuleGhostPublicEntity);
            $nebuleInstance->getEntitiesInstance()->setGhostEntity($instancePubKey);
            $nebuleInstance->getEntitiesInstance()->setGhostEntityPassword($nebuleGhostPasswordEntity);

            /*$instancePrivKey = $nebuleInstance->getCacheInstance()->newNode('0', \Nebule\Library\Cache::TYPE_ENTITY);
            $instancePrivKey->setContent($nebuleGhostPrivateEntity);
            $nebuleInstance->getEntitiesInstance()->setGhostEntityPrivateKeyInstance($instancePrivKey);*/

            // Calcul le nom.
            $hexValue = preg_replace('/[[:^xdigit:]]/', '', $nebuleGhostPublicEntity);
            $genName = hex2bin($hexValue . $hexValue);
            $name = '';
            // Filtrage des caractères du nom dans un espace restreint.
            for ($i = 0; $i < strlen($genName); $i++) {
                $a = ord($genName[$i]);
                if (($a > 96 && $a < 123)) {
                    $name .= $genName[$i];
                    // Insertion de voyelles.
                    if (($i % 3) == 0) {
                        $car = hexdec(bin2hex(random_bytes(1))) % 14;
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

            obj_setContentAsText($name);
            $newLink = blk_generateSign('',
                'l',
                $nebuleGhostPublicEntity,
                obj_getNID($name),
                obj_getNID('nebule/objet/nom')
            );
            blk_write($newLink);

            bootstrap_echoLineTitle('public ID');
            echo $nebuleGhostPublicEntity . '<br/>' . "\n";
            bootstrap_echoLineTitle('private ID');
            echo $nebuleGhostPrivateEntity . '<br/>' . "\n";
/*            bootstrap_echoLineTitle('auto login');
            echo '<a href="?' . LIB_ARG_SWITCH_APPLICATION . '=' . \Nebule\Library\References::DEFAULT_REDIRECT_AUTH_APP
                . '&' . \Nebule\Library\References::COMMAND_APPLICATION_BACK . '=1' . '&view=desc'
                . '&' . \Nebule\Library\References::COMMAND_SELECT_GHOST . '=' . $nebuleGhostPublicEntity
                . '&pwd=' . bin2hex($nebuleGhostPasswordEntity) . '" target="_blank">&gt;open in new page</a><br/>' . "\n";
*/
            ?>

            <div class="important">
                name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $name; ?><br/>
                public ID : <?php echo $nebuleGhostPublicEntity; ?><br/>
                password &nbsp;: <?php echo htmlspecialchars($nebuleGhostPasswordEntity); ?><br/>
                Please keep and save securely these private information!
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
    } else {
        echo '<span class="error">ERROR cannot load library!</span><br />' . "\n";
        $ok = false;
    }
    echo "</div>\n";

    return $ok;
}

/**
 * Create if not present objects needed by nebule.
 *
 * @return bool
 */
function bootstrap_firstDisplay10NeededObjects(): bool {
    global $nebuleInstance, $nebuleGhostPasswordEntity;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $ok = true;

    echo '<div class="parts">' . "\n";
    echo '<span class="partstitle">#10 needed objects</span><br/>' . "\n";

    if (is_a($nebuleInstance, '\Nebule\Library\nebule')
        && \Nebule\Library\References::createNodeReferences($nebuleInstance)
        && \Nebule\Library\References::createLinkReferences($nebuleInstance)
    ) {
        log_add('ok objects', 'info', __FUNCTION__, 'ba3772d3');
        echo " ok\n";
    } else {
        $ok = false;
        echo ' <span class="error">nok</span>' . "\n";
    }

    echo "</div>\n";

    $nebuleGhostPasswordEntity = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789';
    $nebuleGhostPasswordEntity = null;

    return $ok;
}

function bootstrap_displayLocalEntity(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    $content = '';
    if (file_exists(LIB_LOCAL_ENTITY_FILE)) {
        $ioReadMaxData = (int)lib_getOption('ioReadMaxData');
        $content = (string)file_get_contents(LIB_LOCAL_ENTITY_FILE, false, null, 0, $ioReadMaxData);
    }
    if ($content == '')
        $content = 'undefined';
    echo $content;
}



/*
 *
 *
 *
 *

 ==/ 9 /===================================================================================
 PART9 : Display of application 0 default application.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayApplication0(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    // Initialisation des logs
    log_reopen('app0');
    log_add('Loading', 'info', __FUNCTION__, '3a5c4178');

    echo 'CHK';
    ob_end_clean();

    bootstrap_htmlHeader();
    bootstrap_htmlTop();

    echo '<div class="layout-main">' . "\n";
    echo ' <div class="layout-content">' . "\n";
    echo '  <img alt="nebule" id="logo" src="' . LIB_APPLICATION_LOGO_LIGHT . '"/>' . "\n";
    echo " </div>\n";
    echo "</div>\n";

    bootstrap_htmlBottom();
}



/*
 *
 *
 *
 *

 ==/ 10 /==================================================================================
 PART15 : Main display router.

 TODO.
 ------------------------------------------------------------------------------------------
 */

function bootstrap_displayRouter(): void {
    global $bootstrapBreak, $needFirstSynchronization, $bootstrapInlineDisplay, $bootstrapApplicationIID,
           $bootstrapApplicationOID, $bootstrapApplicationNoPreload;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    // End of Web page buffering with a buffer erase before display important things.
    echo 'Display test of of erased buffer - must not be prompted!';
    ob_end_clean();

    // Break on first run, many things to do before continue.
    if ($needFirstSynchronization && !$bootstrapInlineDisplay) {
        log_add('load first', 'info', __FUNCTION__, '63d9bc00');
        bootstrap_displayApplicationFirst();
        return;
    }

    // Break on problems on bootstrap load or on user query.
    if (sizeof($bootstrapBreak) > 0) {
        log_add('load break', 'info', __FUNCTION__, '4abf554b');
        if ($bootstrapInlineDisplay)
            bootstrap_inlineDisplayOnBreak();
        else
            bootstrap_displayOnBreak();
        return;
    }

    io_close();

    // Display only server entity if asked.
    // For compatibility and interoperability.
    if (filter_has_var(INPUT_GET, LIB_LOCAL_ENTITY_FILE)
        || filter_has_var(INPUT_POST, LIB_LOCAL_ENTITY_FILE)
    ) {
        log_add('input ' . LIB_LOCAL_ENTITY_FILE . ' ask display local entity only', 'info', __FUNCTION__, 'dcfc2e74');
        bootstrap_displayLocalEntity();
        return;
    }

    log_add('load application code OID=' . $bootstrapApplicationOID, 'info', __FUNCTION__, 'aab236ff');

    if ($bootstrapApplicationIID == '0' || $bootstrapApplicationOID == '0')
        bootstrap_displayApplication0();
    elseif ($bootstrapApplicationIID == '1' && lib_getOption('permitApplication1')) {
        $instance = New \Nebule\Library\App1();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '2' && lib_getOption('permitApplication2')) {
        $instance = New \Nebule\Library\App2();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '3' && lib_getOption('permitApplication3')) {
        $instance = New \Nebule\Library\App3();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '4' && lib_getOption('permitApplication4')) {
        $instance = New \Nebule\Library\App4();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '5' && lib_getOption('permitApplication5')) {
        $instance = New \Nebule\Library\App5();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '6' && lib_getOption('permitApplication6')) {
        $instance = New \Nebule\Library\App6();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '7' && lib_getOption('permitApplication7')) {
        $instance = New \Nebule\Library\App7();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '8' && lib_getOption('permitApplication8')) {
        $instance = New \Nebule\Library\App8();
        $instance->display();
    }
    elseif ($bootstrapApplicationIID == '9' && lib_getOption('permitApplication9')) {
        $instance = New \Nebule\Library\App9();
        $instance->display();
    }
    elseif (strlen($bootstrapApplicationIID) < 2)
        bootstrap_displayApplication0();
//    elseif (isset($bootstrapApplicationInstanceSleep) && $bootstrapApplicationInstanceSleep != '')
//        bootstrap_displaySleepingApplication();
    elseif ($bootstrapApplicationNoPreload)
        bootstrap_displayNoPreloadApplication();
    else
        bootstrap_displayPreloadApplication();

    log_reopen(BOOTSTRAP_NAME);

    // Sérialise les instances et les sauve dans la session PHP.
    bootstrap_saveLibraryPOO(); // FIXME à supprimer ?
    bootstrap_saveApplicationOnSession();
}

function bootstrap_logMetrology(): void {
    global $nebuleInstance, $nebuleMetrologyTimers;
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');

    $timers = '';
    foreach ($nebuleMetrologyTimers as $i => $v)
        $timers .= " $i=$v";

    if (is_a($nebuleInstance, 'Nebule\Library\nebule') && $nebuleInstance !== null)
        $caches = ' Lr=' . lib_getMetrology('lr') . '+' . $nebuleInstance?->getMetrologyInstance()->getLinkRead()
            . ' Lv=' . lib_getMetrology('lv') . '+' . $nebuleInstance?->getMetrologyInstance()->getLinkVerify()
            . ' Or=' . lib_getMetrology('or') . '+' . $nebuleInstance?->getMetrologyInstance()->getObjectRead()
            . ' Ov=' . lib_getMetrology('ov') . '+' . $nebuleInstance?->getMetrologyInstance()->getObjectVerify()
            . ' (PP+POO) -'
            . ' LC=' . $nebuleInstance?->getCacheInstance()->getCacheLinkSize()
            . ' OC=' . $nebuleInstance?->getCacheInstance()->getCacheObjectSize()
            . ' EC=' . $nebuleInstance?->getCacheInstance()->getCacheEntitySize()
            . ' GC=' . $nebuleInstance?->getCacheInstance()->getCacheGroupSize()
            . ' CC=' . $nebuleInstance?->getCacheInstance()->getCacheConversationSize();
    else
        $caches = ' Lr=' . lib_getMetrology('lr') . ' Lv=' . lib_getMetrology('lv')
            . ' Or=' . lib_getMetrology('or') . ' Ov=' . lib_getMetrology('ov') . ' (PP)';

    log_add('end bootstrap Mp='
        . sprintf('%01.3f', memory_get_peak_usage() / 1024 / 1024) . '/' . ini_get('memory_limit') . 'B'
        . $timers
        . $caches,
        'info',
        'end',
        '11111111');
}

function main(): void {
    log_add('track functions', 'debug', __FUNCTION__, '1111c0de');
    if (!lib_init())
        bootstrap_setBreak('21', __FUNCTION__);

    bootstrap_getUserBreak();
    bootstrap_getInlineDisplay();
    bootstrap_getCheckFingerprint();
    bootstrap_getFlushSession();
    bootstrap_logUserSession();
    bootstrap_getUpdate();
    bootstrap_getSwitchApplication();
    bootstrap_setPermitOpenFileCode();
    lib_setMetrologyTimer('tB');

    $bootstrapLibraryInstanceSleep = '';
    bootstrap_findLibraryPOO($bootstrapLibraryInstanceSleep);
    bootstrap_includeLibraryPOO();
    bootstrap_loadLibraryPOO($bootstrapLibraryInstanceSleep);
    bootstrap_saveLibraryPOO();
    lib_setMetrologyTimer('tL');

    bootstrap_findApplication();
    bootstrap_getApplicationPreload();
    bootstrap_displayRouter();
    lib_setMetrologyTimer('tA');
    bootstrap_logMetrology();
}

// OK, now play...
log_initDebugFile();
main();



/*
 *                     GNU GENERAL PUBLIC LICENSE
 *                        Version 3, 29 June 2007
 *
 *  Copyright (C) 2007 Free Software Foundation, Inc. <https://fsf.org/>
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 *                             Preamble
 *
 *   The GNU General Public License is a free, copyleft license for
 * software and other kinds of works.
 *
 *   The licenses for most software and other practical works are designed
 * to take away your freedom to share and change the works.  By contrast,
 * the GNU General Public License is intended to guarantee your freedom to
 * share and change all versions of a program--to make sure it remains free
 * software for all its users.  We, the Free Software Foundation, use the
 * GNU General Public License for most of our software; it applies also to
 * any other work released this way by its authors.  You can apply it to
 * your programs, too.
 *
 *   When we speak of free software, we are referring to freedom, not
 * price.  Our General Public Licenses are designed to make sure that you
 * have the freedom to distribute copies of free software (and charge for
 * them if you wish), that you receive source code or can get it if you
 * want it, that you can change the software or use pieces of it in new
 * free programs, and that you know you can do these things.
 *
 *   To protect your rights, we need to prevent others from denying you
 * these rights or asking you to surrender the rights.  Therefore, you have
 * certain responsibilities if you distribute copies of the software, or if
 * you modify it: responsibilities to respect the freedom of others.
 *
 *   For example, if you distribute copies of such a program, whether
 * gratis or for a fee, you must pass on to the recipients the same
 * freedoms that you received.  You must make sure that they, too, receive
 * or can get the source code.  And you must show them these terms so they
 * know their rights.
 *
 *   Developers that use the GNU GPL protect your rights with two steps:
 * (1) assert copyright on the software, and (2) offer you this License
 * giving you legal permission to copy, distribute and/or modify it.
 *
 *   For the developers' and authors' protection, the GPL clearly explains
 * that there is no warranty for this free software.  For both users' and
 * authors' sake, the GPL requires that modified versions be marked as
 * changed, so that their problems will not be attributed erroneously to
 * authors of previous versions.
 *
 *   Some devices are designed to deny users access to install or run
 * modified versions of the software inside them, although the manufacturer
 * can do so.  This is fundamentally incompatible with the aim of
 * protecting users' freedom to change the software.  The systematic
 * pattern of such abuse occurs in the area of products for individuals to
 * use, which is precisely where it is most unacceptable.  Therefore, we
 * have designed this version of the GPL to prohibit the practice for those
 * products.  If such problems arise substantially in other domains, we
 * stand ready to extend this provision to those domains in future versions
 * of the GPL, as needed to protect the freedom of users.
 *
 *   Finally, every program is threatened constantly by software patents.
 * States should not allow patents to restrict development and use of
 * software on general-purpose computers, but in those that do, we wish to
 * avoid the special danger that patents applied to a free program could
 * make it effectively proprietary.  To prevent this, the GPL assures that
 * patents cannot be used to render the program non-free.
 *
 *   The precise terms and conditions for copying, distribution and
 * modification follow.
 *
 *                        TERMS AND CONDITIONS
 *
 *   0. Definitions.
 *
 *   "This License" refers to version 3 of the GNU General Public License.
 *
 *   "Copyright" also means copyright-like laws that apply to other kinds of
 * works, such as semiconductor masks.
 *
 *   "The Program" refers to any copyrightable work licensed under this
 * License.  Each licensee is addressed as "you".  "Licensees" and
 * "recipients" may be individuals or organizations.
 *
 *   To "modify" a work means to copy from or adapt all or part of the work
 * in a fashion requiring copyright permission, other than the making of an
 * exact copy.  The resulting work is called a "modified version" of the
 * earlier work or a work "based on" the earlier work.
 *
 *   A "covered work" means either the unmodified Program or a work based
 * on the Program.
 *
 *   To "propagate" a work means to do anything with it that, without
 * permission, would make you directly or secondarily liable for
 * infringement under applicable copyright law, except executing it on a
 * computer or modifying a private copy.  Propagation includes copying,
 * distribution (with or without modification), making available to the
 * public, and in some countries other activities as well.
 *
 *   To "convey" a work means any kind of propagation that enables other
 * parties to make or receive copies.  Mere interaction with a user through
 * a computer network, with no transfer of a copy, is not conveying.
 *
 *   An interactive user interface displays "Appropriate Legal Notices"
 * to the extent that it includes a convenient and prominently visible
 * feature that (1) displays an appropriate copyright notice, and (2)
 * tells the user that there is no warranty for the work (except to the
 * extent that warranties are provided), that licensees may convey the
 * work under this License, and how to view a copy of this License.  If
 * the interface presents a list of user commands or options, such as a
 * menu, a prominent item in the list meets this criterion.
 *
 *   1. Source Code.
 *
 *   The "source code" for a work means the preferred form of the work
 * for making modifications to it.  "Object code" means any non-source
 * form of a work.
 *
 *   A "Standard Interface" means an interface that either is an official
 * standard defined by a recognized standards body, or, in the case of
 * interfaces specified for a particular programming language, one that
 * is widely used among developers working in that language.
 *
 *   The "System Libraries" of an executable work include anything, other
 * than the work as a whole, that (a) is included in the normal form of
 * packaging a Major Component, but which is not part of that Major
 * Component, and (b) serves only to enable use of the work with that
 * Major Component, or to implement a Standard Interface for which an
 * implementation is available to the public in source code form.  A
 * "Major Component", in this context, means a major essential component
 * (kernel, window system, and so on) of the specific operating system
 * (if any) on which the executable work runs, or a compiler used to
 * produce the work, or an object code interpreter used to run it.
 *
 *   The "Corresponding Source" for a work in object code form means all
 * the source code needed to generate, install, and (for an executable
 * work) run the object code and to modify the work, including scripts to
 * control those activities.  However, it does not include the work's
 * System Libraries, or general-purpose tools or generally available free
 * programs which are used unmodified in performing those activities but
 * which are not part of the work.  For example, Corresponding Source
 * includes interface definition files associated with source files for
 * the work, and the source code for shared libraries and dynamically
 * linked subprograms that the work is specifically designed to require,
 * such as by intimate data communication or control flow between those
 * subprograms and other parts of the work.
 *
 *   The Corresponding Source need not include anything that users
 * can regenerate automatically from other parts of the Corresponding
 * Source.
 *
 *   The Corresponding Source for a work in source code form is that
 * same work.
 *
 *   2. Basic Permissions.
 *
 *   All rights granted under this License are granted for the term of
 * copyright on the Program, and are irrevocable provided the stated
 * conditions are met.  This License explicitly affirms your unlimited
 * permission to run the unmodified Program.  The output from running a
 * covered work is covered by this License only if the output, given its
 * content, constitutes a covered work.  This License acknowledges your
 * rights of fair use or other equivalent, as provided by copyright law.
 *
 *   You may make, run and propagate covered works that you do not
 * convey, without conditions so long as your license otherwise remains
 * in force.  You may convey covered works to others for the sole purpose
 * of having them make modifications exclusively for you, or provide you
 * with facilities for running those works, provided that you comply with
 * the terms of this License in conveying all material for which you do
 * not control copyright.  Those thus making or running the covered works
 * for you must do so exclusively on your behalf, under your direction
 * and control, on terms that prohibit them from making any copies of
 * your copyrighted material outside their relationship with you.
 *
 *   Conveying under any other circumstances is permitted solely under
 * the conditions stated below.  Sublicensing is not allowed; section 10
 * makes it unnecessary.
 *
 *   3. Protecting Users' Legal Rights From Anti-Circumvention Law.
 *
 *   No covered work shall be deemed part of an effective technological
 * measure under any applicable law fulfilling obligations under article
 * 11 of the WIPO copyright treaty adopted on 20 December 1996, or
 * similar laws prohibiting or restricting circumvention of such
 * measures.
 *
 *   When you convey a covered work, you waive any legal power to forbid
 * circumvention of technological measures to the extent such circumvention
 * is effected by exercising rights under this License with respect to
 * the covered work, and you disclaim any intention to limit operation or
 * modification of the work as a means of enforcing, against the work's
 * users, your or third parties' legal rights to forbid circumvention of
 * technological measures.
 *
 *   4. Conveying Verbatim Copies.
 *
 *   You may convey verbatim copies of the Program's source code as you
 * receive it, in any medium, provided that you conspicuously and
 * appropriately publish on each copy an appropriate copyright notice;
 * keep intact all notices stating that this License and any
 * non-permissive terms added in accord with section 7 apply to the code;
 * keep intact all notices of the absence of any warranty; and give all
 * recipients a copy of this License along with the Program.
 *
 *   You may charge any price or no price for each copy that you convey,
 * and you may offer support or warranty protection for a fee.
 *
 *   5. Conveying Modified Source Versions.
 *
 *   You may convey a work based on the Program, or the modifications to
 * produce it from the Program, in the form of source code under the
 * terms of section 4, provided that you also meet all of these conditions:
 *
 *     a) The work must carry prominent notices stating that you modified
 *     it, and giving a relevant date.
 *
 *     b) The work must carry prominent notices stating that it is
 *     released under this License and any conditions added under section
 *     7.  This requirement modifies the requirement in section 4 to
 *     "keep intact all notices".
 *
 *     c) You must license the entire work, as a whole, under this
 *     License to anyone who comes into possession of a copy.  This
 *     License will therefore apply, along with any applicable section 7
 *     additional terms, to the whole of the work, and all its parts,
 *     regardless of how they are packaged.  This License gives no
 *     permission to license the work in any other way, but it does not
 *     invalidate such permission if you have separately received it.
 *
 *     d) If the work has interactive user interfaces, each must display
 *     Appropriate Legal Notices; however, if the Program has interactive
 *     interfaces that do not display Appropriate Legal Notices, your
 *     work need not make them do so.
 *
 *   A compilation of a covered work with other separate and independent
 * works, which are not by their nature extensions of the covered work,
 * and which are not combined with it such as to form a larger program,
 * in or on a volume of a storage or distribution medium, is called an
 * "aggregate" if the compilation and its resulting copyright are not
 * used to limit the access or legal rights of the compilation's users
 * beyond what the individual works permit.  Inclusion of a covered work
 * in an aggregate does not cause this License to apply to the other
 * parts of the aggregate.
 *
 *   6. Conveying Non-Source Forms.
 *
 *   You may convey a covered work in object code form under the terms
 * of sections 4 and 5, provided that you also convey the
 * machine-readable Corresponding Source under the terms of this License,
 * in one of these ways:
 *
 *     a) Convey the object code in, or embodied in, a physical product
 *     (including a physical distribution medium), accompanied by the
 *     Corresponding Source fixed on a durable physical medium
 *     customarily used for software interchange.
 *
 *     b) Convey the object code in, or embodied in, a physical product
 *     (including a physical distribution medium), accompanied by a
 *     written offer, valid for at least three years and valid for as
 *     long as you offer spare parts or customer support for that product
 *     model, to give anyone who possesses the object code either (1) a
 *     copy of the Corresponding Source for all the software in the
 *     product that is covered by this License, on a durable physical
 *     medium customarily used for software interchange, for a price no
 *     more than your reasonable cost of physically performing this
 *     conveying of source, or (2) access to copy the
 *     Corresponding Source from a network server at no charge.
 *
 *     c) Convey individual copies of the object code with a copy of the
 *     written offer to provide the Corresponding Source.  This
 *     alternative is allowed only occasionally and noncommercially, and
 *     only if you received the object code with such an offer, in accord
 *     with subsection 6b.
 *
 *     d) Convey the object code by offering access from a designated
 *     place (gratis or for a charge), and offer equivalent access to the
 *     Corresponding Source in the same way through the same place at no
 *     further charge.  You need not require recipients to copy the
 *     Corresponding Source along with the object code.  If the place to
 *     copy the object code is a network server, the Corresponding Source
 *     may be on a different server (operated by you or a third party)
 *     that supports equivalent copying facilities, provided you maintain
 *     clear directions next to the object code saying where to find the
 *     Corresponding Source.  Regardless of what server hosts the
 *     Corresponding Source, you remain obligated to ensure that it is
 *     available for as long as needed to satisfy these requirements.
 *
 *     e) Convey the object code using peer-to-peer transmission, provided
 *     you inform other peers where the object code and Corresponding
 *     Source of the work are being offered to the general public at no
 *     charge under subsection 6d.
 *
 *   A separable portion of the object code, whose source code is excluded
 * from the Corresponding Source as a System Library, need not be
 * included in conveying the object code work.
 *
 *   A "User Product" is either (1) a "consumer product", which means any
 * tangible personal property which is normally used for personal, family,
 * or household purposes, or (2) anything designed or sold for incorporation
 * into a dwelling.  In determining whether a product is a consumer product,
 * doubtful cases shall be resolved in favor of coverage.  For a particular
 * product received by a particular user, "normally used" refers to a
 * typical or common use of that class of product, regardless of the status
 * of the particular user or of the way in which the particular user
 * actually uses, or expects or is expected to use, the product.  A product
 * is a consumer product regardless of whether the product has substantial
 * commercial, industrial or non-consumer uses, unless such uses represent
 * the only significant mode of use of the product.
 *
 *   "Installation Information" for a User Product means any methods,
 * procedures, authorization keys, or other information required to install
 * and execute modified versions of a covered work in that User Product from
 * a modified version of its Corresponding Source.  The information must
 * suffice to ensure that the continued functioning of the modified object
 * code is in no case prevented or interfered with solely because
 * modification has been made.
 *
 *   If you convey an object code work under this section in, or with, or
 * specifically for use in, a User Product, and the conveying occurs as
 * part of a transaction in which the right of possession and use of the
 * User Product is transferred to the recipient in perpetuity or for a
 * fixed term (regardless of how the transaction is characterized), the
 * Corresponding Source conveyed under this section must be accompanied
 * by the Installation Information.  But this requirement does not apply
 * if neither you nor any third party retains the ability to install
 * modified object code on the User Product (for example, the work has
 * been installed in ROM).
 *
 *   The requirement to provide Installation Information does not include a
 * requirement to continue to provide support service, warranty, or updates
 * for a work that has been modified or installed by the recipient, or for
 * the User Product in which it has been modified or installed.  Access to a
 * network may be denied when the modification itself materially and
 * adversely affects the operation of the network or violates the rules and
 * protocols for communication across the network.
 *
 *   Corresponding Source conveyed, and Installation Information provided,
 * in accord with this section must be in a format that is publicly
 * documented (and with an implementation available to the public in
 * source code form), and must require no special password or key for
 * unpacking, reading or copying.
 *
 *   7. Additional Terms.
 *
 *   "Additional permissions" are terms that supplement the terms of this
 * License by making exceptions from one or more of its conditions.
 * Additional permissions that are applicable to the entire Program shall
 * be treated as though they were included in this License, to the extent
 * that they are valid under applicable law.  If additional permissions
 * apply only to part of the Program, that part may be used separately
 * under those permissions, but the entire Program remains governed by
 * this License without regard to the additional permissions.
 *
 *   When you convey a copy of a covered work, you may at your option
 * remove any additional permissions from that copy, or from any part of
 * it.  (Additional permissions may be written to require their own
 * removal in certain cases when you modify the work.)  You may place
 * additional permissions on material, added by you to a covered work,
 * for which you have or can give appropriate copyright permission.
 *
 *   Notwithstanding any other provision of this License, for material you
 * add to a covered work, you may (if authorized by the copyright holders of
 * that material) supplement the terms of this License with terms:
 *
 *     a) Disclaiming warranty or limiting liability differently from the
 *     terms of sections 15 and 16 of this License; or
 *
 *     b) Requiring preservation of specified reasonable legal notices or
 *     author attributions in that material or in the Appropriate Legal
 *     Notices displayed by works containing it; or
 *
 *     c) Prohibiting misrepresentation of the origin of that material, or
 *     requiring that modified versions of such material be marked in
 *     reasonable ways as different from the original version; or
 *
 *     d) Limiting the use for publicity purposes of names of licensors or
 *     authors of the material; or
 *
 *     e) Declining to grant rights under trademark law for use of some
 *     trade names, trademarks, or service marks; or
 *
 *     f) Requiring indemnification of licensors and authors of that
 *     material by anyone who conveys the material (or modified versions of
 *     it) with contractual assumptions of liability to the recipient, for
 *     any liability that these contractual assumptions directly impose on
 *     those licensors and authors.
 *
 *   All other non-permissive additional terms are considered "further
 * restrictions" within the meaning of section 10.  If the Program as you
 * received it, or any part of it, contains a notice stating that it is
 * governed by this License along with a term that is a further
 * restriction, you may remove that term.  If a license document contains
 * a further restriction but permits relicensing or conveying under this
 * License, you may add to a covered work material governed by the terms
 * of that license document, provided that the further restriction does
 * not survive such relicensing or conveying.
 *
 *   If you add terms to a covered work in accord with this section, you
 * must place, in the relevant source files, a statement of the
 * additional terms that apply to those files, or a notice indicating
 * where to find the applicable terms.
 *
 *   Additional terms, permissive or non-permissive, may be stated in the
 * form of a separately written license, or stated as exceptions;
 * the above requirements apply either way.
 *
 *   8. Termination.
 *
 *   You may not propagate or modify a covered work except as expressly
 * provided under this License.  Any attempt otherwise to propagate or
 * modify it is void, and will automatically terminate your rights under
 * this License (including any patent licenses granted under the third
 * paragraph of section 11).
 *
 *   However, if you cease all violation of this License, then your
 * license from a particular copyright holder is reinstated (a)
 * provisionally, unless and until the copyright holder explicitly and
 * finally terminates your license, and (b) permanently, if the copyright
 * holder fails to notify you of the violation by some reasonable means
 * prior to 60 days after the cessation.
 *
 *   Moreover, your license from a particular copyright holder is
 * reinstated permanently if the copyright holder notifies you of the
 * violation by some reasonable means, this is the first time you have
 * received notice of violation of this License (for any work) from that
 * copyright holder, and you cure the violation prior to 30 days after
 * your receipt of the notice.
 *
 *   Termination of your rights under this section does not terminate the
 * licenses of parties who have received copies or rights from you under
 * this License.  If your rights have been terminated and not permanently
 * reinstated, you do not qualify to receive new licenses for the same
 * material under section 10.
 *
 *   9. Acceptance Not Required for Having Copies.
 *
 *   You are not required to accept this License in order to receive or
 * run a copy of the Program.  Ancillary propagation of a covered work
 * occurring solely as a consequence of using peer-to-peer transmission
 * to receive a copy likewise does not require acceptance.  However,
 * nothing other than this License grants you permission to propagate or
 * modify any covered work.  These actions infringe copyright if you do
 * not accept this License.  Therefore, by modifying or propagating a
 * covered work, you indicate your acceptance of this License to do so.
 *
 *   10. Automatic Licensing of Downstream Recipients.
 *
 *   Each time you convey a covered work, the recipient automatically
 * receives a license from the original licensors, to run, modify and
 * propagate that work, subject to this License.  You are not responsible
 * for enforcing compliance by third parties with this License.
 *
 *   An "entity transaction" is a transaction transferring control of an
 * organization, or substantially all assets of one, or subdividing an
 * organization, or merging organizations.  If propagation of a covered
 * work results from an entity transaction, each party to that
 * transaction who receives a copy of the work also receives whatever
 * licenses to the work the party's predecessor in interest had or could
 * give under the previous paragraph, plus a right to possession of the
 * Corresponding Source of the work from the predecessor in interest, if
 * the predecessor has it or can get it with reasonable efforts.
 *
 *   You may not impose any further restrictions on the exercise of the
 * rights granted or affirmed under this License.  For example, you may
 * not impose a license fee, royalty, or other charge for exercise of
 * rights granted under this License, and you may not initiate litigation
 * (including a cross-claim or counterclaim in a lawsuit) alleging that
 * any patent claim is infringed by making, using, selling, offering for
 * sale, or importing the Program or any portion of it.
 *
 *   11. Patents.
 *
 *   A "contributor" is a copyright holder who authorizes use under this
 * License of the Program or a work on which the Program is based.  The
 * work thus licensed is called the contributor's "contributor version".
 *
 *   A contributor's "essential patent claims" are all patent claims
 * owned or controlled by the contributor, whether already acquired or
 * hereafter acquired, that would be infringed by some manner, permitted
 * by this License, of making, using, or selling its contributor version,
 * but do not include claims that would be infringed only as a
 * consequence of further modification of the contributor version.  For
 * purposes of this definition, "control" includes the right to grant
 * patent sublicenses in a manner consistent with the requirements of
 * this License.
 *
 *   Each contributor grants you a non-exclusive, worldwide, royalty-free
 * patent license under the contributor's essential patent claims, to
 * make, use, sell, offer for sale, import and otherwise run, modify and
 * propagate the contents of its contributor version.
 *
 *   In the following three paragraphs, a "patent license" is any express
 * agreement or commitment, however denominated, not to enforce a patent
 * (such as an express permission to practice a patent or covenant not to
 * sue for patent infringement).  To "grant" such a patent license to a
 * party means to make such an agreement or commitment not to enforce a
 * patent against the party.
 *
 *   If you convey a covered work, knowingly relying on a patent license,
 * and the Corresponding Source of the work is not available for anyone
 * to copy, free of charge and under the terms of this License, through a
 * publicly available network server or other readily accessible means,
 * then you must either (1) cause the Corresponding Source to be so
 * available, or (2) arrange to deprive yourself of the benefit of the
 * patent license for this particular work, or (3) arrange, in a manner
 * consistent with the requirements of this License, to extend the patent
 * license to downstream recipients.  "Knowingly relying" means you have
 * actual knowledge that, but for the patent license, your conveying the
 * covered work in a country, or your recipient's use of the covered work
 * in a country, would infringe one or more identifiable patents in that
 * country that you have reason to believe are valid.
 *
 *   If, pursuant to or in connection with a single transaction or
 * arrangement, you convey, or propagate by procuring conveyance of, a
 * covered work, and grant a patent license to some of the parties
 * receiving the covered work authorizing them to use, propagate, modify
 * or convey a specific copy of the covered work, then the patent license
 * you grant is automatically extended to all recipients of the covered
 * work and works based on it.
 *
 *   A patent license is "discriminatory" if it does not include within
 * the scope of its coverage, prohibits the exercise of, or is
 * conditioned on the non-exercise of one or more of the rights that are
 * specifically granted under this License.  You may not convey a covered
 * work if you are a party to an arrangement with a third party that is
 * in the business of distributing software, under which you make payment
 * to the third party based on the extent of your activity of conveying
 * the work, and under which the third party grants, to any of the
 * parties who would receive the covered work from you, a discriminatory
 * patent license (a) in connection with copies of the covered work
 * conveyed by you (or copies made from those copies), or (b) primarily
 * for and in connection with specific products or compilations that
 * contain the covered work, unless you entered into that arrangement,
 * or that patent license was granted, prior to 28 March 2007.
 *
 *   Nothing in this License shall be construed as excluding or limiting
 * any implied license or other defenses to infringement that may
 * otherwise be available to you under applicable patent law.
 *
 *   12. No Surrender of Others' Freedom.
 *
 *   If conditions are imposed on you (whether by court order, agreement or
 * otherwise) that contradict the conditions of this License, they do not
 * excuse you from the conditions of this License.  If you cannot convey a
 * covered work so as to satisfy simultaneously your obligations under this
 * License and any other pertinent obligations, then as a consequence you may
 * not convey it at all.  For example, if you agree to terms that obligate you
 * to collect a royalty for further conveying from those to whom you convey
 * the Program, the only way you could satisfy both those terms and this
 * License would be to refrain entirely from conveying the Program.
 *
 *   13. Use with the GNU Affero General Public License.
 *
 *   Notwithstanding any other provision of this License, you have
 * permission to link or combine any covered work with a work licensed
 * under version 3 of the GNU Affero General Public License into a single
 * combined work, and to convey the resulting work.  The terms of this
 * License will continue to apply to the part which is the covered work,
 * but the special requirements of the GNU Affero General Public License,
 * section 13, concerning interaction through a network will apply to the
 * combination as such.
 *
 *   14. Revised Versions of this License.
 *
 *   The Free Software Foundation may publish revised and/or new versions of
 * the GNU General Public License from time to time.  Such new versions will
 * be similar in spirit to the present version, but may differ in detail to
 * address new problems or concerns.
 *
 *   Each version is given a distinguishing version number.  If the
 * Program specifies that a certain numbered version of the GNU General
 * Public License "or any later version" applies to it, you have the
 * option of following the terms and conditions either of that numbered
 * version or of any later version published by the Free Software
 * Foundation.  If the Program does not specify a version number of the
 * GNU General Public License, you may choose any version ever published
 * by the Free Software Foundation.
 *
 *   If the Program specifies that a proxy can decide which future
 * versions of the GNU General Public License can be used, that proxy's
 * public statement of acceptance of a version permanently authorizes you
 * to choose that version for the Program.
 *
 *   Later license versions may give you additional or different
 * permissions.  However, no additional obligations are imposed on any
 * author or copyright holder as a result of your choosing to follow a
 * later version.
 *
 *   15. Disclaimer of Warranty.
 *
 *   THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY
 * APPLICABLE LAW.  EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT
 * HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.  THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM
 * IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF
 * ALL NECESSARY SERVICING, REPAIR OR CORRECTION.
 *
 *   16. Limitation of Liability.
 *
 *   IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
 * WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MODIFIES AND/OR CONVEYS
 * THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY
 * GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE
 * USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF
 * DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD
 * PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS),
 * EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGES.
 *
 *   17. Interpretation of Sections 15 and 16.
 *
 *   If the disclaimer of warranty and limitation of liability provided
 * above cannot be given local legal effect according to their terms,
 * reviewing courts shall apply local law that most closely approximates
 * an absolute waiver of all civil liability in connection with the
 * Program, unless a warranty or assumption of liability accompanies a
 * copy of the Program in return for a fee.
 *
 *                      END OF TERMS AND CONDITIONS
 *
 *             How to Apply These Terms to Your New Programs
 *
 *   If you develop a new program, and you want it to be of the greatest
 * possible use to the public, the best way to achieve this is to make it
 * free software which everyone can redistribute and change under these terms.
 *
 *   To do so, attach the following notices to the program.  It is safest
 * to attach them to the start of each source file to most effectively
 * state the exclusion of warranty; and each file should have at least
 * the "copyright" line and a pointer to where the full notice is found.
 *
 *     <one line to give the program's name and a brief idea of what it does.>
 *     Copyright (C) <year>  <name of author>
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Also add information on how to contact you by electronic and paper mail.
 *
 *   If the program does terminal interaction, make it output a short
 * notice like this when it starts in an interactive mode:
 *
 *     <program>  Copyright (C) <year>  <name of author>
 *     This program comes with ABSOLUTELY NO WARRANTY; for details type `show w'.
 *     This is free software, and you are welcome to redistribute it
 *     under certain conditions; type `show c' for details.
 *
 * The hypothetical commands `show w' and `show c' should show the appropriate
 * parts of the General Public License.  Of course, your program's commands
 * might be different; for a GUI interface, you would use an "about box".
 *
 *   You should also get your employer (if you work as a programmer) or school,
 * if any, to sign a "copyright disclaimer" for the program, if necessary.
 * For more information on this, and how to apply and follow the GNU GPL, see
 * <https://www.gnu.org/licenses/>.
 *
 *   The GNU General Public License does not permit incorporating your program
 * into proprietary programs.  If your program is a subroutine library, you
 * may consider it more useful to permit linking proprietary applications with
 * the library.  If this is what you want to do, use the GNU Lesser General
 * Public License instead of this License.  But first, please read
 * <https://www.gnu.org/licenses/why-not-lgpl.html>.
 */
?>
