<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Configuration class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Configuration
{
    /**
     * Liste des noms des options.
     *
     * @var array:string
     */
    const OPTIONS_LIST = array(
        'puppetmaster',
        'hostURL',
        'permitWrite',
        'permitWriteObject',
        'permitCreateObject',
        'permitSynchronizeObject',
        'permitProtectedObject',
        'permitWriteLink',
        'permitCreateLink',
        'permitSynchronizeLink',
        'permitUploadLink',
        'permitPublicUploadLink',
        'permitPublicUploadCodeAuthoritiesLink',
        'permitObfuscatedLink',
        'permitWriteEntity',
        'permitPublicCreateEntity',
        'permitWriteGroup',
        'permitWriteConversation',
        'permitCurrency',
        'permitWriteCurrency',
        'permitCreateCurrency',
        'permitWriteTransaction',
        'permitObfuscatedTransaction',
        'permitSynchronizeApplication',
        'permitPublicSynchronizeApplication',
        'permitDeleteObjectOnUnknownHash',
        'permitCheckSignOnVerify',
        'permitCheckSignOnList',
        'permitCheckObjectHash',
        'permitListInvalidLinks',
        'permitHistoryLinksSign',
        'permitInstanceEntityAsAuthority',
        'permitDefaultEntityAsAuthority',
        'permitLocalSecondaryAuthorities',
        'permitRecoveryEntities',
        'permitRecoveryRemoveEntity',
        'permitInstanceEntityAsRecovery',
        'permitDefaultEntityAsRecovery',
        'permitAddLinkToSigner',
        'permitListOtherHash',
        'permitLocalisationStats',
        'permitFollowUpdates',
        'permitOnlineRescue',
        'permitLogs',
        'permitJavaScript',
        'logsLevel',
        'modeRescue',
        'cryptoLibrary',
        'cryptoHashAlgorithm',
        'cryptoSymetricAlgorithm',
        'cryptoAsymetricAlgorithm',
        'socialLibrary',
        'ioLibrary',
        'ioReadMaxLinks',
        'ioReadMaxData',
        'ioReadMaxUpload',
        'ioTimeout',
        'displayUnsecureURL',
        'displayNameSize',
        'displayEmotions',
        'forceDisplayEntityOnTitle',
        'linkMaxFollowedUpdates',
        'linkMaxRL',
        'linkMaxRLUID',
        'linkMaxRS',
        'permitSessionOptions',
        'permitSessionBuffer',
        'permitBufferIO', //  TODO Need to be used on the library in side of permitSessionBuffer...
        'sessionBufferSize',
        'defaultCurrentEntity',
        'defaultApplication',
        'defaultObfuscateLinks',
        'defaultLinksVersion',
        'subordinationEntity',
    );

    /**
     * Liste des catégories de tri des options.
     *
     * @var array:string
     */
    const OPTIONS_CATEGORIES = array(
        'Global',
        'Objects',
        'Links',
        'Entities',
        'Groups',
        'Conversations',
        'Currencies',
        'Applications',
        'Logs',
        'Cryptography',
        'I/O',
        'Social',
        'Display',
    );

    /**
     * Liste de catégorisation des options.
     *
     * @var array:string
     */
    const OPTIONS_CATEGORY = array(
        'puppetmaster' => 'Global',
        'hostURL' => 'Global',
        'permitWrite' => 'Global',
        'permitWriteObject' => 'Objects',
        'permitCreateObject' => 'Objects',
        'permitSynchronizeObject' => 'Objects',
        'permitProtectedObject' => 'Objects',
        'permitWriteLink' => 'Links',
        'permitCreateLink' => 'Links',
        'permitSynchronizeLink' => 'Links',
        'permitUploadLink' => 'Links',
        'permitPublicUploadLink' => 'Links',
        'permitPublicUploadCodeAuthoritiesLink' => 'Links',
        'permitObfuscatedLink' => 'Links',
        'permitWriteEntity' => 'Entities',
        'permitPublicCreateEntity' => 'Entities',
        'permitWriteGroup' => 'Groups',
        'permitWriteConversation' => 'Conversations',
        'permitCurrency' => 'Currencies',
        'permitWriteCurrency' => 'Currencies',
        'permitCreateCurrency' => 'Currencies',
        'permitWriteTransaction' => 'Currencies',
        'permitObfuscatedTransaction' => 'Currencies',
        'permitSynchronizeApplication' => 'Applications',
        'permitPublicSynchronizeApplication' => 'Applications',
        'permitDeleteObjectOnUnknownHash' => 'Objects',
        'permitCheckSignOnVerify' => 'Links',
        'permitCheckSignOnList' => 'Links',
        'permitCheckObjectHash' => 'Objects',
        'permitListInvalidLinks' => 'Links',
        'permitHistoryLinksSign' => 'Links',
        'permitInstanceEntityAsAuthority' => 'Entities',
        'permitDefaultEntityAsAuthority' => 'Entities',
        'permitLocalSecondaryAuthorities' => 'Entities',
        'permitRecoveryEntities' => 'Entities',
        'permitRecoveryRemoveEntity' => 'Entities',
        'permitInstanceEntityAsRecovery' => 'Entities',
        'permitDefaultEntityAsRecovery' => 'Entities',
        'permitAddLinkToSigner' => 'Links',
        'permitListOtherHash' => 'Links',
        'permitLocalisationStats' => 'Global',
        'permitFollowUpdates' => 'Links',
        'permitOnlineRescue' => 'Global',
        'permitLogs' => 'Logs',
        'permitJavaScript' => 'Display',
        'logsLevel' => 'Logs',
        'modeRescue' => 'Global',
        'cryptoLibrary' => 'Cryptography',
        'cryptoHashAlgorithm' => 'Cryptography',
        'cryptoSymetricAlgorithm' => 'Cryptography',
        'cryptoAsymetricAlgorithm' => 'Cryptography',
        'socialLibrary' => 'Social',
        'ioLibrary' => 'I/O',
        'ioReadMaxLinks' => 'I/O',
        'ioReadMaxData' => 'I/O',
        'ioReadMaxUpload' => 'I/O',
        'ioTimeout' => 'I/O',
        'displayUnsecureURL' => 'Display',
        'displayNameSize' => 'Display',
        'displayEmotions' => 'Display',
        'forceDisplayEntityOnTitle' => 'Display',
        'linkMaxFollowedUpdates' => 'Links',
        'linkMaxRL' => 'Links',
        'linkMaxRLUID' => 'Links',
        'linkMaxRS' => 'Links',
        'permitSessionOptions' => 'I/O',
        'permitSessionBuffer' => 'I/O',
        'permitBufferIO' => 'I/O',
        'sessionBufferSize' => 'I/O',
        'defaultCurrentEntity' => 'Entities',
        'defaultApplication' => 'Applications',
        'defaultObfuscateLinks' => 'Links',
        'defaultLinksVersion' => 'Links',
        'subordinationEntity' => 'Global',
    );

    /**
     * Liste des types des options.
     *
     * Les types supportés :
     * - string
     * - boolean
     * - integer
     *
     * @var array:string
     */
    const OPTIONS_TYPE = array(
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
        'permitCheckSignOnList' => 'boolean',
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
        'logsLevel' => 'string',
        'modeRescue' => 'boolean',
        'cryptoLibrary' => 'string',
        'cryptoHashAlgorithm' => 'string',
        'cryptoSymetricAlgorithm' => 'string',
        'cryptoAsymetricAlgorithm' => 'string',
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
     * Liste des options qui sont modifiables.
     * Les options non modifiables peuvent cependant être forcées dans le fichier d'environnement.
     *
     * @var array:boolean
     */
    const OPTIONS_WRITABLE = array(
        'puppetmaster' => false,
        'hostURL' => true,
        'permitWrite' => false,
        'permitWriteObject' => true,
        'permitCreateObject' => true,
        'permitSynchronizeObject' => true,
        'permitProtectedObject' => true,
        'permitWriteLink' => true,
        'permitCreateLink' => true,
        'permitSynchronizeLink' => true,
        'permitUploadLink' => true,
        'permitPublicUploadLink' => true,
        'permitPublicUploadCodeAuthoritiesLink' => true,
        'permitObfuscatedLink' => true,
        'permitWriteEntity' => true,
        'permitPublicCreateEntity' => true,
        'permitWriteGroup' => true,
        'permitWriteConversation' => true,
        'permitCurrency' => true,
        'permitWriteCurrency' => true,
        'permitCreateCurrency' => true,
        'permitWriteTransaction' => true,
        'permitObfuscatedTransaction' => true,
        'permitSynchronizeApplication' => true,
        'permitPublicSynchronizeApplication' => true,
        'permitDeleteObjectOnUnknownHash' => false,
        'permitCheckSignOnVerify' => false,
        'permitCheckSignOnList' => true,
        'permitCheckObjectHash' => false,
        'permitListInvalidLinks' => false,
        'permitHistoryLinksSign' => true,
        'permitInstanceEntityAsAuthority' => false,
        'permitDefaultEntityAsAuthority' => false,
        'permitLocalSecondaryAuthorities' => true, // @todo à voir...
        'permitRecoveryEntities' => false,
        'permitRecoveryRemoveEntity' => false,
        'permitInstanceEntityAsRecovery' => false,
        'permitDefaultEntityAsRecovery' => false,
        'permitAddLinkToSigner' => true,
        'permitListOtherHash' => true,
        'permitLocalisationStats' => true,
        'permitFollowUpdates' => true,
        'permitOnlineRescue' => true,
        'permitLogs' => true,
        'permitJavaScript' => false,
        'logsLevel' => true,
        'modeRescue' => false,
        'cryptoLibrary' => true,
        'cryptoHashAlgorithm' => true,
        'cryptoSymetricAlgorithm' => true,
        'cryptoAsymetricAlgorithm' => true,
        'socialLibrary' => true,
        'ioLibrary' => true,
        'ioReadMaxLinks' => true,
        'ioReadMaxData' => true,
        'ioReadMaxUpload' => true,
        'ioTimeout' => true,
        'displayUnsecureURL' => false,
        'displayNameSize' => true,
        'displayEmotions' => true,
        'forceDisplayEntityOnTitle' => true,
        'linkMaxFollowedUpdates' => true,
        'linkMaxRL' => false,
        'linkMaxRLUID' => false,
        'linkMaxRS' => false,
        'permitSessionOptions' => true,
        'permitSessionBuffer' => true,
        'permitBufferIO' => true,
        'sessionBufferSize' => true,
        'defaultCurrentEntity' => true,
        'defaultApplication' => true,
        'defaultObfuscateLinks' => true,
        'defaultLinksVersion' => true,
        'subordinationEntity' => false,
    );

    /**
     * Liste des valeurs par défaut des options.
     *
     * @var array
     */
    const OPTIONS_DEFAULT_VALUE = array(
        'puppetmaster' => '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256',
        'hostURL' => 'localhost',
        'permitWrite' => true,
        'permitWriteObject' => true,
        'permitCreateObject' => true,
        'permitSynchronizeObject' => false,
        'permitProtectedObject' => true,
        'permitWriteLink' => true,
        'permitCreateLink' => true,
        'permitSynchronizeLink' => false,
        'permitUploadLink' => false,
        'permitPublicUploadLink' => false,
        'permitPublicUploadCodeAuthoritiesLink' => true,
        'permitObfuscatedLink' => true,
        'permitWriteEntity' => true,
        'permitPublicCreateEntity' => false,
        'permitWriteGroup' => true,
        'permitWriteConversation' => true,
        'permitCurrency' => true,
        'permitWriteCurrency' => true,
        'permitCreateCurrency' => false,
        'permitWriteTransaction' => true,
        'permitObfuscatedTransaction' => false,
        'permitSynchronizeApplication' => false,
        'permitPublicSynchronizeApplication' => false,
        'permitDeleteObjectOnUnknownHash' => true,
        'permitCheckSignOnVerify' => true,
        'permitCheckSignOnList' => true,
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
        'permitJavaScript' => true,
        'logsLevel' => 'NORMAL',
        'modeRescue' => false,
        'cryptoLibrary' => 'openssl',
        'cryptoHashAlgorithm' => 'sha2.256',
        'cryptoSymetricAlgorithm' => 'aes-256-ctr',
        'cryptoAsymetricAlgorithm' => 'rsa.2048',
        'socialLibrary' => 'strict',
        'ioLibrary' => 'ioFileSystem',
        'ioReadMaxLinks' => 2000,
        'ioReadMaxData' => 10000,
        'ioReadMaxUpload' => 2000000,
        'ioTimeout' => 1,
        'displayUnsecureURL' => true,
        'displayNameSize' => 128,
        'displayEmotions' => true,
        'forceDisplayEntityOnTitle' => false,
        'linkMaxFollowedUpdates' => 100,
        'linkMaxRL' => 1,
        'linkMaxRLUID' => 3,
        'linkMaxRS' => 1,
        'permitSessionOptions' => true,
        'permitSessionBuffer' => true,
        'permitBufferIO' => true,
        'sessionBufferSize' => 1000,
        'defaultCurrentEntity' => '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256',
        'defaultApplication' => '0',
        'defaultObfuscateLinks' => false,
        'defaultLinksVersion' => '2.0',
        'subordinationEntity' => '',
    );

    /**
     * Liste de la criticité des options.
     *
     * @var array:string
     */
    const OPTIONS_CRITICALITY = array(
        'puppetmaster' => 'critical',
        'hostURL' => 'useful',
        'permitWrite' => 'useful',
        'permitWriteObject' => 'useful',
        'permitCreateObject' => 'useful',
        'permitSynchronizeObject' => 'useful',
        'permitProtectedObject' => 'useful',
        'permitWriteLink' => 'useful',
        'permitCreateLink' => 'useful',
        'permitSynchronizeLink' => 'useful',
        'permitUploadLink' => 'careful',
        'permitPublicUploadLink' => 'careful',
        'permitPublicUploadCodeAuthoritiesLink' => 'useful',
        'permitObfuscatedLink' => 'useful',
        'permitWriteEntity' => 'useful',
        'permitPublicCreateEntity' => 'critical',
        'permitWriteGroup' => 'useful',
        'permitWriteConversation' => 'useful',
        'permitCurrency' => 'useful',
        'permitWriteCurrency' => 'useful',
        'permitCreateCurrency' => 'useful',
        'permitWriteTransaction' => 'useful',
        'permitObfuscatedTransaction' => 'careful',
        'permitSynchronizeApplication' => 'careful',
        'permitPublicSynchronizeApplication' => 'careful',
        'permitDeleteObjectOnUnknownHash' => 'critical',
        'permitCheckSignOnVerify' => 'critical',
        'permitCheckSignOnList' => 'critical',
        'permitCheckObjectHash' => 'critical',
        'permitListInvalidLinks' => 'critical',
        'permitHistoryLinksSign' => 'useful',
        'permitInstanceEntityAsAuthority' => 'careful',
        'permitDefaultEntityAsAuthority' => 'careful',
        'permitLocalSecondaryAuthorities' => 'careful',
        'permitRecoveryEntities' => 'critical',
        'permitRecoveryRemoveEntity' => 'careful',
        'permitInstanceEntityAsRecovery' => 'critical',
        'permitDefaultEntityAsRecovery' => 'critical',
        'permitAddLinkToSigner' => 'useful',
        'permitListOtherHash' => 'useful',
        'permitLocalisationStats' => 'useful',
        'permitFollowUpdates' => 'useful',
        'permitOnlineRescue' => 'careful',
        'permitLogs' => 'useful',
        'permitJavaScript' => 'careful',
        'logsLevel' => 'useful',
        'modeRescue' => 'critical',
        'cryptoLibrary' => 'careful',
        'cryptoHashAlgorithm' => 'careful',
        'cryptoSymetricAlgorithm' => 'careful',
        'cryptoAsymetricAlgorithm' => 'careful',
        'socialLibrary' => 'careful',
        'ioLibrary' => 'careful',
        'ioReadMaxLinks' => 'useful',
        'ioReadMaxData' => 'useful',
        'ioReadMaxUpload' => 'useful',
        'ioTimeout' => 'useful',
        'displayUnsecureURL' => 'critical',
        'displayNameSize' => 'useful',
        'displayEmotions' => 'useful',
        'forceDisplayEntityOnTitle' => 'useful',
        'linkMaxFollowedUpdates' => 'useful',
        'linkMaxRL' => 'careful',
        'linkMaxRLUID' => 'careful',
        'linkMaxRS' => 'careful',
        'permitSessionOptions' => 'careful',
        'permitSessionBuffer' => 'careful',
        'permitBufferIO' => 'careful',
        'sessionBufferSize' => 'useful',
        'defaultCurrentEntity' => 'useful',
        'defaultApplication' => 'useful',
        'defaultObfuscateLinks' => 'useful',
        'defaultLinksVersion' => 'useful',
        'subordinationEntity' => 'critical',
    );

    /**
     * Liste des descriptions des options.
     * @todo
     *
     * @var array:string
     */
    const OPTIONS_DESCRIPTION = array(
        'puppetmaster' => 'The master of all. the authority of all globals authorities.',
        'hostURL' => "The URL, domain name, of this server. This is use by others servers and others entities to find this server and it's local entities.",
        'permitWrite' => 'The big switch to write protect all the instance on this server. This switch is not an object but is on the options file.',
        'permitWriteObject' => 'The switch to permit objects writing.',
        'permitCreateObject' => 'The switch to permit creation of new objects localy.',
        'permitSynchronizeObject' => 'The switch to permit to synchronize (update) objects from other localisations.',
        'permitProtectedObject' => 'The switch to permit read/write protected objects. On false, generation of liens k for protected objects is disabled and all existing/downloaded links for protected objects are assumed as invalid and dropped.',
        'permitWriteLink' => 'The switch to permit links writing.',
        'permitCreateLink' => 'The switch to permit creation of new links localy.',
        'permitSynchronizeLink' => 'The switch to permit to synchronize links of objects from other localisations.',
        'permitUploadLink' => 'The switch to permit ask creation and sign of new links uploaded within an URL.',
        'permitPublicUploadLink' => 'The switch to permit ask upload signed links (from known entities) within an URL.',
        'permitPublicUploadCodeAuthoritiesLink' => 'The switch to permit ask upload signed links by the code master within an URL.',
        'permitObfuscatedLink' => 'The switch to permit read/write obfuscated links. On false, generation of obfuscated liens c is disabled and all existing/downloaded obfuscated links are assumed as invalid and dropped.',
        'permitWriteEntity' => 'The switch to permit entities writing.',
        'permitPublicCreateEntity' => 'The switch to permit create new entity by anyone.',
        'permitWriteGroup' => 'The switch to permit groups writing.',
        'permitWriteConversation' => 'The switch to permit conversations writing.',
        'permitCurrency' => 'The switch to permit use of currencies.',
        'permitWriteCurrency' => 'The switch to permit currencies writing.',
        'permitCreateCurrency' => 'The switch to permit currencies creation.',
        'permitWriteTransaction' => 'The switch to permit transactions writing.',
        'permitObfuscatedTransaction' => 'The switch to permit transactions on obfuscated links.',
        'permitSynchronizeApplication' => 'The switch to permit to synchronize (update) applications from other localisations.',
        'permitPublicSynchronizeApplication' => 'The switch to permit to synchronize (update) applications by anyone from other localisations.',
        'permitDeleteObjectOnUnknownHash' => 'Permit erasing object if not valid hash type can be found.',
        'permitCheckSignOnVerify' => 'Todo description...',
        'permitCheckSignOnList' => 'Todo description...',
        'permitCheckObjectHash' => 'Todo description...',
        'permitListInvalidLinks' => 'Todo description...',
        'permitHistoryLinksSign' => 'Todo description...',
        'permitInstanceEntityAsAuthority' => 'Declare instance entity of this server as local authority.',
        'permitDefaultEntityAsAuthority' => 'Declare default entity on this server as local authority.',
        'permitLocalSecondaryAuthorities' => 'Todo description...',
        'permitRecoveryEntities' => 'Activate the recovery process. Local recovery entities are listed and new protection of objects are automaticaly shared with recovery entities.',
        'permitRecoveryRemoveEntity' => 'An entity can remove shared protection to recovery entity. By default, it is not permited.',
        'permitInstanceEntityAsRecovery' => 'Declare instance entity of this server as recovery entity.',
        'permitDefaultEntityAsRecovery' => 'Declare default entity on this server as recovery entity.',
        'permitAddLinkToSigner' => 'Todo description...',
        'permitListOtherHash' => 'Todo description...',
        'permitLocalisationStats' => 'Todo description...',
        'permitFollowUpdates' => 'Todo description...',
        'permitOnlineRescue' => 'Todo description...',
        'permitLogs' => 'Activate more logs (syslog) on internal process.',
        'permitJavaScript' => 'Activate by default JavaScript (JS) on web pages.',
        'logsLevel' => 'Select verbosity of logs. Select on NORMAL, ERROR, FUNCTION and DEBUG.',
        'modeRescue' => 'Activate the rescue mode. Follow only links from globals authorities for applications detection.',
        'cryptoLibrary' => 'Define the default cryptographic library used.',
        'cryptoHashAlgorithm' => 'Define the default cryptographic hash algorythm used.',
        'cryptoSymetricAlgorithm' => 'Define the default cryptographic symetric algorythm used.',
        'cryptoAsymetricAlgorithm' => 'Define the default cryptographic asymetric algorythm used.',
        'socialLibrary' => 'Todo description...',
        'ioLibrary' => 'Todo description...',
        'ioReadMaxLinks' => 'Maximum number of links readable in one time for one object.',
        'ioReadMaxData' => 'Maximum quantity of bytes readable in one time from one object file content.',
        'ioReadMaxUpload' => 'Maximum file size on upload. Overload default value upload_max_filesize on php.ini file.',
        'ioTimeout' => 'Todo description...',
        'displayUnsecureURL' => 'Display a warning message if the connexion link is not protected (https : HTTP overs TLS).',
        'displayNameSize' => 'The maximum displayable size of a name of objects.',
        'displayEmotions' => 'Display all emotions when asked by applications, or not.',
        'forceDisplayEntityOnTitle' => 'Force display of current selected entity on application even if is the same of current entity used on library.',
        'linkMaxFollowedUpdates' => 'Todo description...',
        'linkMaxRL' => 'Todo description...',
        'linkMaxRLUID' => 'Todo description...',
        'linkMaxRS' => 'Todo description...',
        'permitSessionOptions' => 'Todo description...',
        'permitSessionBuffer' => 'Todo description...',
        'permitBufferIO' => 'Todo description...',
        'sessionBufferSize' => 'Todo description...',
        'defaultCurrentEntity' => 'Todo description...',
        'defaultApplication' => 'Todo description...',
        'defaultObfuscateLinks' => 'Todo description...',
        'defaultLinksVersion' => 'Define the version of new generated links.',
        'subordinationEntity' => 'Define the external entity which can modify writeable options on this server instance.',
    );

    /**
     * Instance de la librairie en cours.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de la métrologie.
     *
     * @var Metrology
     */
    private $_metrologyInstance;

    /**
     * Verrou des modifications des options dans le cache.
     * @var boolean
     */
    private $_writeOptionCacheLock = false;

    /**
     * Verrouillage de la recherche d'option via les liens.
     * Anti-boucle infinie.
     *
     * @var boolean
     */
    private $_optionsByLinksIsInUse = false;

    /**
     * Le cache des options déjà lues.
     *
     * @var array
     */
    private $_optionCache = array();

    /**
     * Marque la fin de l'initialisation.
     * C'est nécessaire pour certaines parties qui nécessitent l'accès à la journalisation mais trop tôt.
     * C'est le cas dans la lecture des options dans les liens.
     *
     * @var boolean
     */
    private $_permitOptionsByLinks = false;



    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     */
    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrologyInstance = $nebuleInstance->getMetrologyInstance();
    }

    /**
     * Donne la liste des noms des options disponibles.
     *
     * @return array
     */
    public static function getListOptions(): array
    {
        return self::OPTIONS_LIST;
    }

    /**
     * Donne la liste de catégories d'options.
     *
     * @return array
     */
    public static function getListCategoriesOptions(): array
    {
        return self::OPTIONS_CATEGORIES;
    }

    /**
     * Donne la liste de catégorisation des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsCategory(): array
    {
        return self::OPTIONS_CATEGORY;
    }

    /**
     * Donne la liste des types des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsType(): array
    {
        return self::OPTIONS_TYPE;
    }

    /**
     * Donne la liste de capacité d'écriture des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsWritable(): array
    {
        return self::OPTIONS_WRITABLE;
    }

    /**
     * Donne la liste des valeurs par défaut des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsDefaultValue(): array
    {
        return self::OPTIONS_DEFAULT_VALUE;
    }

    /**
     * Donne la liste de criticité des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsCriticality(): array
    {
        return self::OPTIONS_CRITICALITY;
    }

    /**
     * Donne la liste des descriptions des options disponibles.
     *
     * @return array
     */
    public static function getListOptionsDescription(): array
    {
        return self::OPTIONS_DESCRIPTION;
    }

    /**
     * Extrait les options nebule.
     * Retourne :
     *  - le contenu de l'option dans l'environnement ;
     *  - ou le contenu de l'option dans les liens ;
     *  - ou le contenu de l'option par défaut ;
     *  - ou false.
     *
     * La valeur trouvée dans l'environnement est prioritaire.
     * C'est la façon de forcer une option.
     *
     * @param string $name
     * @return string|bool|int|null
     */
    public function getOption(string $name)
    {
        // Vérifie le nom.
        if ($name == ''
            || !is_string($name)
            || !isset(self::OPTIONS_TYPE[$name])
        )
            return null;

        if ($this->_metrologyInstance !== null)
            $this->_metrologyInstance->addLog('Get option ' . $name, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // La réponse.
        $result = null;

        // Lit le cache.
        if (isset($this->_optionCache[$name]))
            $result = $this->_optionCache[$name];

        // Cherche l'option dans l'environnement.
        if ($result === null)
            $result = $this->getOptionFromEnvironment($name);

        // Si non trouvé et si non protégée, cherche l'option dans les liens.
        if (self::OPTIONS_WRITABLE[$name]
            && $result === null
        )
            $result = $this->getOptionFromLinks($name);

        // Si non trouvé, cherche la valeur par défaut de l'option.
        if ($result === null) {
            $result = self::OPTIONS_DEFAULT_VALUE[$name];
            if ($this->_metrologyInstance !== null) {
                $this->_metrologyInstance->addLog('Get default option ' . $name . ' = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            }
        }

        // Si non trouvé, retourne la valeur par défaut.
        if ($result === null) {
            $result = false;
            if ($this->_metrologyInstance !== null)
                $this->_metrologyInstance->addLog('Get unknown option ' . $name . ' = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }

        if ($this->_metrologyInstance !== null)
            $this->_metrologyInstance->addLog('Return option ' . $name . ' = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Ecrit le cache.
        if ($result !== null)
            $this->_optionCache[$name] = $result;

        return $result;
    }

    /**
     * Donne la liste des valeurs par défaut des options disponibles.
     *
     * @param string $name
     * @return string|bool|int|null
     */
    public static function getOptionDefaultValue(string $name)
    {
        if ($name == ''
            || !is_string($name)
            || !isset(self::OPTIONS_DEFAULT_VALUE[$name])
        )
            return null;

        return self::OPTIONS_DEFAULT_VALUE[$name];
    }

    /**
     * @param string $name
     * @return string|bool|int|null
     */
    public function getOptionFromEnvironment(string $name)
    {
        $result = self::getOptionFromEnvironmentStatic($name);

        if ($this->_metrologyInstance !== null)
            $this->_metrologyInstance->addLog('Return option env = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        return $result;
    }

    /**
     * @param string $name
     * @return string|bool|int|null
     */
    static public function getOptionFromEnvironmentStatic(string $name)
    {
        if ($name == ''
            || !isset(self::OPTIONS_TYPE[$name])
        )
            return null;

        // Read configuration file.
        $value = '';
        if (file_exists(nebule::NEBULE_ENVIRONMENT_FILE)) {
            $file = file(nebule::NEBULE_ENVIRONMENT_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            foreach ($file as $line) {
                $l = trim($line);

                if (substr($l, 0, 1) == "#")
                    continue;

                if (filter_var(trim(strtok($l, '=')), FILTER_SANITIZE_STRING) == $name) {
                    $value = trim(filter_var(trim(substr($l, strpos($l, '=') + 1)), FILTER_SANITIZE_STRING));
                    break;
                }
            }
            unset($file);
        }

        return self::_transtypeValueFromString($name, $value);
    }

    /**
     * @param string $name
     * @param string $value
     * @return string|bool|int|null
     */
    static private function _transtypeValueFromString(string $name, string $value)
    {
        if ($name == ''
            || !isset(self::OPTIONS_TYPE[$name])
            || $value == ''
        )
            return null;

        switch (self::OPTIONS_TYPE[$name]) {
            case 'string' :
                $result = trim($value);
                break;
            case 'boolean' :
                if ($value == 'true')
                    $result = true;
                elseif ($value == 'false')
                    $result = false;
                else
                    $result = self::OPTIONS_DEFAULT_VALUE[$name];
                break;
            case 'integer' :
                $result = (int)$value;
                break;
            default :
                $result = null;
        }

        return $result;
    }

    /**
     * @param string          $name
     * @param string|bool|int $value
     * @return string
     */
    private function _transtypeValueToString(string $name, $value)
    {
        // Transcode value.
        switch (self::OPTIONS_TYPE[$name]) {
            case 'string' :
                return $value;
            case 'boolean' :
                if ($value === true)
                    return 'true';
                else
                    return 'false';
            case 'integer' :
                return (string)$value;
            default :
                return '';
        }
    }
    /**
     * Extrait les options depuis les liens.
     * Retourne :
     *  - une chaine de caractères avec le contenu de l'option _ou_ un nombre _ou_ un booléen ;
     *  - ou null si rien n'est trouvé.
     *
     * Pour les booléens, on regarde si on a l'inverse de la valeur par défaut de l'option.
     *
     * @param string $name
     * @return string|bool|int|null
     */
    public function getOptionFromLinks(string $name)
    {
        if ($name == ''
            || !isset(self::OPTIONS_TYPE[$name])
            || !$this->_permitOptionsByLinks
            || $this->_optionsByLinksIsInUse
        )
            return null;

        $this->_optionsByLinksIsInUse = true;

        // Si une entité de subordination est défini, lit l'option forcée par cette entité.
        $value = '';
        if ($this->_nebuleInstance->getSubordinationEntity() != '') {
            $instance = $this->_nebuleInstance->getSubordinationEntity();
            $value = trim($instance->getProperty(nebule::REFERENCE_NEBULE_OPTION . '/' . $name));
            unset($instance);
        }

        // Si aucune valeur trouvée de l'entité de subordination, lit l'option pour l'entité en cours.
        if ($value == ''
            && is_a($this->_nebuleInstance->getCurrentEntityInstance(), 'Entity')
        )
            $value = trim($this->_nebuleInstance->getCurrentEntityInstance()->getProperty(nebule::REFERENCE_NEBULE_OPTION . '/' . $name));

        $this->_optionsByLinksIsInUse = false;

        $result = self::_transtypeValueToString($name, $value);

        if ($this->_metrologyInstance !== null) {
            $this->_metrologyInstance->addLog('Return option links = ' . (string)$result, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        }

        return $result;
    }

    /**
     * Ecrit une option en cache.
     *
     * La valeur est traduite sous forme de texte au besoin.
     *
     * L'écritue en cache n'est possible que si cette possibilité n'est pas verrouillée.
     * Le bootstrap verrouille automatiquement en fin de chargement la possibilité
     *   de modification des options directement en cache.
     * Le verrouillage n'est pas annulable.
     *
     * @param string $name
     * @param string|bool|int $value
     * @return boolean
     */
    public function setOptionCache(string $name, $value)
    {
        // Vérifie le verrouillage.
        if ($this->_writeOptionCacheLock)
            return false;

        // Vérifie le nom.
        if ($name == ''
            || !is_string($name)
            || !isset(self::OPTIONS_TYPE[$name])
            || !self::OPTIONS_WRITABLE[$name]
        )
            return false;

        $this->_metrologyInstance->addLog('Set option cache ' . $name, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Transcode value.
        $writeValue = $this->_transtypeValueToString($name, $value);
        if (is_null($writeValue))
            return false;

        $this->_metrologyInstance->addLog('Set option cache value = ' . $writeValue, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Ecrit l'option.
        $this->_optionCache[$name] = $value;
        return true;
    }

    /**
     * Ecrit une option.
     * L'option est écrite sous forme de lien si elle n'est pas protégée ou forcée.
     *
     * La valeur est traduite sous forme de texte au besoin.
     *
     * La variable $entity permet de cibler une entité pour l'application de l'option... si on est entité de subordination.
     *
     * @param string $name
     * @param string|bool|int $value
     * @param string $entity
     * @return boolean
     */
    public function setOptionEnvironment(string $name, $value, string $entity = ''): bool
    {
        // Vérifie le nom.
        if ($name == ''
            || !is_string($name)
            || !isset(self::OPTIONS_TYPE[$name])
            || !self::OPTIONS_WRITABLE[$name]
            || $this->getOptionFromEnvironment($name) !== null
        )
            return false;

        // Détermine l'entité ciblée par l'option.
        if ($entity = ''
            || $entity == '0'
        )
            $entity = $this->_nebuleInstance->getCurrentEntity();

        $this->_metrologyInstance->addLog('Set option ' . $name, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Prépare la valeur.
        $writeValue = $this->_transtypeValueFromString($name, $value);
        if (is_null($writeValue))
            return false;

        $this->_metrologyInstance->addLog('Set option value = ' . $writeValue, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        // Crée l'instance de l'objet de la valeur.
        $instance = new Node($this->_nebuleInstance, '0', $writeValue);
        if ($instance === false) {
            $this->_metrologyInstance->addLog("L'instance de l'objet n'a pas pu être créée.", Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            return false;
        }

        // Lit l'ID.
        $id = $instance->getID();
        if ($id == '0') {
            $this->_metrologyInstance->addLog("L'objet n'a pas pu être créé.", Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            return false;
        }

        // Création du type mime.
        $instance->setType(nebule::REFERENCE_OBJECT_TEXT);

        // Crée le lien de l'option.
        $signer = $this->_nebuleInstance->getCurrentEntity();
        $date = date(DATE_ATOM);
        $action = 'l';
        //$source	= $this->_crypto->hash( $name );
        $source = $entity;
        $target = $id;
        //$meta	= $this->_crypto->hash(nebule::REFERENCE_NEBULE_OPTION);
        $meta = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OPTION . '/' . $name);
        // Génère le lien.
        $link = '0_' . $signer . '_' . $date . '_' . $action . '_' . $source . '_' . $target . '_' . $meta;
        $newLink = new Link($this->_nebuleInstance, $link);
        // Signe le lien.
        $newLink->sign($signer);
        // Ecrit le lien.
        $ok = $newLink->write();

        if ($ok) {
            $this->_optionCache[$name] = $value;
            return true;
        } else {
            $this->_metrologyInstance->addLog('Set option write error', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '00000000'); // Log
            return false;
        }
    }

    /**
     * Lock write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockWrite(): void
    {
        $this->_optionCache['permitWrite'] = false;
    }

    /**
     * Lock object's write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockWriteObject(): void
    {
        $this->_optionCache['permitWriteObject'] = false;
    }

    /**
     * Lock link's write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockWriteLink(): void
    {
        $this->_optionCache['permitWriteLink'] = false;
    }

    /**
     * Verrouille la possibilité de modification des options dans le cache.
     * L'opération n'est pas annulable.
     */
    public function lockCache(): void
    {
        $this->_writeOptionCacheLock = true;
    }

    /**
     * Reinitialize the values of options on cache.
     * Each option will be reloaded on demand.
     */
    public function flushCache(): void
    {
        $this->_optionCache = array();
    }

    /**
     * Change capability to use links for options.
     * @param bool $value
     */
    public function setPermitOptionsByLinks(bool $value): void
    {
        $this->_permitOptionsByLinks = $value;
    }

    /**
     * Check values of criticals options.
     * If not default value, add a log with the value.
     * @return void
     */
    public function checkReadOnlyOptions()
    {
        $this->_metrologyInstance->addLog('Check options', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log

        foreach (self::OPTIONS_LIST as $option) {
            if (self::OPTIONS_CRITICALITY[$option] == 'critical') {
                $value = $this->getOption($option);
                if ($value != self::OPTIONS_DEFAULT_VALUE[$option]) {
                    if (is_bool($value)) {
                        if ($value)
                            $value = 'true';
                        else
                            $value = 'false';
                    }
                    if (is_int($value))
                        $value = (string)$value;
                    $this->_metrologyInstance->addLog('Warning:critical_option ' . $option . '=' . $value, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
                }
            }
        }
    }

}