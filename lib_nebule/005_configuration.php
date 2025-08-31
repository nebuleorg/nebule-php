<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Configuration class for the nebule library.
 * Do not serialize on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Configuration extends Functions
{
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
        'permitAuthenticateEntity',
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
        'permitServerEntityAsAuthority',
        'permitDefaultEntityAsAuthority',
        'permitLocalSecondaryAuthorities',
        'permitRecoveryEntities',
        'permitRecoveryRemoveEntity',
        'permitServerEntityAsRecovery',
        'permitDefaultEntityAsRecovery',
        'permitAddLinkToSigner',
        'permitListOtherHash',
        'permitLocalisationStats',
        'permitFollowUpdates',
        'permitOnlineRescue',
        'permitLogs',
        'permitLogsOnDebugFile',
        'permitJavaScript',
        'permitApplicationModules',
        'permitApplicationModulesExternal',
        'permitApplicationModulesTranslate',
        'permitActionWithoutToken',
        'codeBranch',
        'logsLevel',
        'modeRescue',
        'cryptoLibrary',
        'cryptoHashAlgorithm',
        'cryptoSymmetricAlgorithm',
        'cryptoAsymmetricAlgorithm',
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
        'defaultEntity',
        'defaultApplication',
        'permitApplication1',
        'permitApplication2',
        'permitApplication3',
        'permitApplication4',
        'permitApplication5',
        'permitApplication6',
        'permitApplication7',
        'permitApplication8',
        'permitApplication9',
        'defaultObfuscateLinks',
        'defaultLinksVersion',
        'subordinationEntity',
    );

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
        'permitAuthenticateEntity' => 'Entities',
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
        'permitServerEntityAsAuthority' => 'Entities',
        'permitDefaultEntityAsAuthority' => 'Entities',
        'permitLocalSecondaryAuthorities' => 'Entities',
        'permitRecoveryEntities' => 'Entities',
        'permitRecoveryRemoveEntity' => 'Entities',
        'permitServerEntityAsRecovery' => 'Entities',
        'permitDefaultEntityAsRecovery' => 'Entities',
        'permitAddLinkToSigner' => 'Links',
        'permitListOtherHash' => 'Links',
        'permitLocalisationStats' => 'Global',
        'permitFollowUpdates' => 'Links',
        'permitOnlineRescue' => 'Global',
        'permitLogs' => 'Logs',
        'permitLogsOnDebugFile' => 'Logs',
        'permitJavaScript' => 'Display',
        'permitApplicationModules' => 'Applications',
        'permitApplicationModulesExternal' => 'Applications',
        'permitApplicationModulesTranslate' => 'Applications',
        'permitActionWithoutToken' => 'Global',
        'codeBranch' => 'Global',
        'logsLevel' => 'Logs',
        'modeRescue' => 'Global',
        'cryptoLibrary' => 'Cryptography',
        'cryptoHashAlgorithm' => 'Cryptography',
        'cryptoSymmetricAlgorithm' => 'Cryptography',
        'cryptoAsymmetricAlgorithm' => 'Cryptography',
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
        'defaultEntity' => 'Entities',
        'defaultApplication' => 'Applications',
        'permitApplication1' => 'Applications',
        'permitApplication2' => 'Applications',
        'permitApplication3' => 'Applications',
        'permitApplication4' => 'Applications',
        'permitApplication5' => 'Applications',
        'permitApplication6' => 'Applications',
        'permitApplication7' => 'Applications',
        'permitApplication8' => 'Applications',
        'permitApplication9' => 'Applications',
        'defaultObfuscateLinks' => 'Links',
        'defaultLinksVersion' => 'Links',
        'subordinationEntity' => 'Global',
    );

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
        'permitAuthenticateEntity' => 'boolean',
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
        'permitApplicationModules' => 'boolean',
        'permitApplicationModulesExternal' => 'boolean',
        'permitApplicationModulesTranslate' => 'boolean',
        'permitActionWithoutToken' => 'boolean',
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
        'permitAuthenticateEntity' => true,
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
        'permitServerEntityAsAuthority' => false,
        'permitDefaultEntityAsAuthority' => false,
        'permitLocalSecondaryAuthorities' => false,
        'permitRecoveryEntities' => false,
        'permitRecoveryRemoveEntity' => false,
        'permitServerEntityAsRecovery' => false,
        'permitDefaultEntityAsRecovery' => false,
        'permitAddLinkToSigner' => true,
        'permitListOtherHash' => true,
        'permitLocalisationStats' => true,
        'permitFollowUpdates' => true,
        'permitOnlineRescue' => true,
        'permitLogs' => false,
        'permitLogsOnDebugFile' => false,
        'permitJavaScript' => false,
        'permitApplicationModules' => true,
        'permitApplicationModulesExternal' => false,
        'permitApplicationModulesTranslate' => false,
        'permitActionWithoutToken' => false,
        'codeBranch' => false,
        'logsLevel' => false,
        'modeRescue' => false,
        'cryptoLibrary' => true,
        'cryptoHashAlgorithm' => true,
        'cryptoSymmetricAlgorithm' => true,
        'cryptoAsymmetricAlgorithm' => true,
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
        'defaultEntity' => true,
        'defaultApplication' => true,
        'permitApplication1' => true,
        'permitApplication2' => true,
        'permitApplication3' => true,
        'permitApplication4' => true,
        'permitApplication5' => true,
        'permitApplication6' => true,
        'permitApplication7' => true,
        'permitApplication8' => true,
        'permitApplication9' => true,
        'defaultObfuscateLinks' => true,
        'defaultLinksVersion' => true,
        'subordinationEntity' => false,
    );

    const OPTIONS_DEFAULT_VALUE = array(
        'puppetmaster' => '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256',
        'hostURL' => 'localhost',
        'permitWrite' => 'true',
        'permitWriteObject' => 'true',
        'permitCreateObject' => 'true',
        'permitSynchronizeObject' => 'false',
        'permitProtectedObject' => 'true',
        'permitWriteLink' => 'true',
        'permitCreateLink' => 'true',
        'permitSynchronizeLink' => 'false',
        'permitUploadLink' => 'false',
        'permitPublicUploadLink' => 'false',
        'permitPublicUploadCodeAuthoritiesLink' => 'true',
        'permitObfuscatedLink' => 'true',
        'permitWriteEntity' => 'true',
        'permitAuthenticateEntity' => 'true',
        'permitPublicCreateEntity' => 'false',
        'permitWriteGroup' => 'true',
        'permitWriteConversation' => 'true',
        'permitCurrency' => 'true',
        'permitWriteCurrency' => 'true',
        'permitCreateCurrency' => 'false',
        'permitWriteTransaction' => 'true',
        'permitObfuscatedTransaction' => 'false',
        'permitSynchronizeApplication' => 'false',
        'permitPublicSynchronizeApplication' => 'false',
        'permitDeleteObjectOnUnknownHash' => 'true',
        'permitCheckSignOnVerify' => 'true',
        'permitCheckSignOnList' => 'true',
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
        'permitLogs' => 'true',
        'permitLogsOnDebugFile' => 'false',
        'permitJavaScript' => 'true',
        'permitApplicationModules' => 'true',
        'permitApplicationModulesExternal' => 'false',
        'permitApplicationModulesTranslate' => 'true',
        'permitActionWithoutToken' => 'false',
        'codeBranch' => 'develop',
        'logsLevel' => 'NORMAL',
        'modeRescue' => 'false',
        'cryptoLibrary' => 'Openssl',
        'cryptoHashAlgorithm' => 'sha2.256',
        'cryptoSymmetricAlgorithm' => 'aes.256.ctr',
        'cryptoAsymmetricAlgorithm' => 'rsa.2048',
        'socialLibrary' => 'authority',
        'ioLibrary' => 'Disk',
        'ioReadMaxLinks' => '2000',
        'ioReadMaxData' => '10000',
        'ioReadMaxUpload' => '2000000',
        'ioTimeout' => '1',
        'displayUnsecureURL' => 'true',
        'displayNameSize' => '128',
        'displayEmotions' => 'true',
        'forceDisplayEntityOnTitle' => 'false',
        'linkMaxFollowedUpdates' => '100',
        'linkMaxRL' => '1',
        'linkMaxRLUID' => '5',
        'linkMaxRS' => '1',
        'permitSessionOptions' => 'true',
        'permitSessionBuffer' => 'true',
        'permitBufferIO' => 'true',
        'sessionBufferSize' => '1000',
        'defaultEntity' => '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256',
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
        'defaultLinksVersion' => '2:0',
        'subordinationEntity' => '',
    );

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
        'permitAuthenticateEntity' => 'useful',
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
        'permitServerEntityAsAuthority' => 'careful',
        'permitDefaultEntityAsAuthority' => 'careful',
        'permitLocalSecondaryAuthorities' => 'careful',
        'permitRecoveryEntities' => 'critical',
        'permitRecoveryRemoveEntity' => 'careful',
        'permitServerEntityAsRecovery' => 'critical',
        'permitDefaultEntityAsRecovery' => 'critical',
        'permitAddLinkToSigner' => 'useful',
        'permitListOtherHash' => 'useful',
        'permitLocalisationStats' => 'useful',
        'permitFollowUpdates' => 'useful',
        'permitOnlineRescue' => 'careful',
        'permitLogs' => 'useful',
        'permitLogsOnDebugFile' => 'careful',
        'permitJavaScript' => 'careful',
        'permitApplicationModules' => 'careful',
        'permitApplicationModulesExternal' => 'critical',
        'permitApplicationModulesTranslate' => 'careful',
        'permitActionWithoutToken' => 'critical',
        'codeBranch' => 'careful',
        'logsLevel' => 'useful',
        'modeRescue' => 'critical',
        'cryptoLibrary' => 'careful',
        'cryptoHashAlgorithm' => 'careful',
        'cryptoSymmetricAlgorithm' => 'careful',
        'cryptoAsymmetricAlgorithm' => 'careful',
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
        'defaultEntity' => 'useful',
        'defaultApplication' => 'useful',
        'permitApplication1' => 'useful',
        'permitApplication2' => 'useful',
        'permitApplication3' => 'useful',
        'permitApplication4' => 'critical',
        'permitApplication5' => 'critical',
        'permitApplication6' => 'critical',
        'permitApplication7' => 'critical',
        'permitApplication8' => 'critical',
        'permitApplication9' => 'critical',
        'defaultObfuscateLinks' => 'useful',
        'defaultLinksVersion' => 'useful',
        'subordinationEntity' => 'critical',
    );

    const OPTIONS_DESCRIPTION = array(
        'puppetmaster' => 'The master of all. the authority of all globals authorities.',
        'hostURL' => "The URL, domain name, of this server. This is use by others servers and others entities to find this server and it's local entities.",
        'permitWrite' => 'The big switch to write protect all the instance on this server. This switch is not an object but is on the options file.',
        'permitWriteObject' => 'The switch to permit objects writing.',
        'permitCreateObject' => 'The switch to permit creation of new objects locally.',
        'permitSynchronizeObject' => 'The switch to permit to synchronize (update) objects from other localisations.',
        'permitProtectedObject' => 'The switch to permit read/write protected objects. On false, generation of liens k for protected objects is disabled and all existing/downloaded links for protected objects are assumed as invalid and dropped.',
        'permitWriteLink' => 'The switch to permit links writing.',
        'permitCreateLink' => 'The switch to permit creation of new links locally.',
        'permitSynchronizeLink' => 'The switch to permit to synchronize links of objects from other localisations.',
        'permitUploadLink' => 'The switch to permit ask creation and sign of new links uploaded within an URL.',
        'permitPublicUploadLink' => 'The switch to permit ask upload signed links (from known entities) within an URL.',
        'permitPublicUploadCodeAuthoritiesLink' => 'The switch to permit ask upload signed links by the code master within an URL.',
        'permitObfuscatedLink' => 'The switch to permit read/write obfuscated links. On false, generation of obfuscated liens c is disabled and all existing/downloaded obfuscated links are assumed as invalid and dropped.',
        'permitWriteEntity' => 'The switch to permit entities writing.',
        'permitAuthenticateEntity' => 'The switch to permit entities to authenticate on this server instance.',
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
        'permitServerEntityAsAuthority' => 'Declare instance entity of this server as local authority.',
        'permitDefaultEntityAsAuthority' => 'Declare default entity on this server as local authority.',
        'permitLocalSecondaryAuthorities' => 'Todo description...',
        'permitRecoveryEntities' => 'Activate the recovery process. Local recovery entities are listed and new protection of objects are automatically shared with recovery entities.',
        'permitRecoveryRemoveEntity' => 'An entity can remove shared protection to recovery entity. By default, it is not permitted.',
        'permitServerEntityAsRecovery' => 'Declare instance entity of this server as recovery entity.',
        'permitDefaultEntityAsRecovery' => 'Declare default entity on this server as recovery entity.',
        'permitActionWithoutToken' => 'An action do not need valid token.',
        'permitAddLinkToSigner' => 'Todo description...',
        'permitListOtherHash' => 'Todo description...',
        'permitLocalisationStats' => 'Todo description...',
        'permitFollowUpdates' => 'Todo description...',
        'permitOnlineRescue' => 'Todo description...',
        'permitLogs' => 'Activate more logs (syslog) on internal process.',
        'permitLogsOnDebugFile' => 'Activate debug logs to logfile o/log.',
        'permitJavaScript' => 'Activate by default JavaScript (JS) on web pages.',
        'permitApplicationModules' => 'Permit any application to use internal modules and/or external modules (with permitApplicationModulesTranslate).',
        'permitApplicationModulesExternal' => 'Permit any application to use external modules (need permitApplicationModules).',
        'permitApplicationModulesTranslate' => 'Permit any application to use external translate modules (limited) (need permitApplicationModules).',
        'codeBranch' => 'Level quality of used code.',
        'logsLevel' => 'Select verbosity of logs. Select on NORMAL, ERROR, FUNCTION and DEBUG.',
        'modeRescue' => 'Activate the rescue mode. Follow only links from globals authorities for applications detection.',
        'cryptoLibrary' => 'Define the default cryptographic library used.',
        'cryptoHashAlgorithm' => 'Define the default cryptographic hash algorithm used.',
        'cryptoSymmetricAlgorithm' => 'Define the default cryptographic symmetric algorithm used.',
        'cryptoAsymmetricAlgorithm' => 'Define the default cryptographic asymmetric algorithm used.',
        'socialLibrary' => "Default social understanding of link's signers.",
        'ioLibrary' => 'Default storage type for I/O operations.',
        'ioReadMaxLinks' => 'Maximum number of links readable in one time for one object.',
        'ioReadMaxData' => 'Maximum quantity of bytes readable in one time from one object file content.',
        'ioReadMaxUpload' => 'Maximum file size on upload. Overload default value upload_max_filesize on php.ini file.',
        'ioTimeout' => 'Timeout for I/O operations with distant storage.',
        'displayUnsecureURL' => 'Display a warning message if the connexion link is not protected (https : HTTP overs TLS).',
        'displayNameSize' => 'The maximum displayable size of a name of objects.',
        'displayEmotions' => 'Display all emotions when asked by applications, or not.',
        'forceDisplayEntityOnTitle' => 'Force display of current selected entity on application even if is the same of current entity used on library.',
        'linkMaxFollowedUpdates' => 'Todo description...',
        'linkMaxRL' => 'Maximum links that can be read on the registry RL. If more, link is marked to have invalid structure.',
        'linkMaxRLUID' => 'Maximum UIDs of links that can be read on the registry RL. If more, link is marked to have invalid structure.',
        'linkMaxRS' => 'Maximum signes that can be read on the registry RS. If more, ignored.',
        'permitSessionOptions' => 'Todo description...',
        'permitSessionBuffer' => 'Todo description...',
        'permitBufferIO' => 'Todo description...',
        'sessionBufferSize' => 'Todo description...',
        'defaultEntity' => 'Todo description...',
        'defaultApplication' => 'Todo description...',
        'permitApplication1' => 'The switch to permit the use of application 1 to select application.',
        'permitApplication2' => 'The switch to permit the use of application 2 to authenticate with local entity.',
        'permitApplication3' => 'The switch to permit the use of application 3 for documentation.',
        'permitApplication4' => 'The switch to permit the use of application 4 to shortly display blocklink.',
        'permitApplication5' => 'The switch to permit the use of application 5, not implemented.',
        'permitApplication6' => 'The switch to permit the use of application 6, not implemented.',
        'permitApplication7' => 'The switch to permit the use of application 7, not implemented.',
        'permitApplication8' => 'The switch to permit the use of application 8, not implemented.',
        'permitApplication9' => 'The switch to permit the use of application 9 for debug purposes.',
        'defaultObfuscateLinks' => 'Todo description...',
        'defaultLinksVersion' => 'Define the version of new generated links.',
        'subordinationEntity' => 'Define the external entity which can modify writeable options on this server instance.',
    );

    private bool $_overwriteOptionCacheLock = false;
    private bool $_optionsByLinksIsInUse = false;
    private array $_optionCache = array();
    private bool $_permitOptionsByLinks = false;



    protected function _initialisation(): void
    {
        global $needFirstSynchronization;
        $this->_metrologyInstance->addLog('instancing class Configuration', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'fbaec5ee');

        // Needed on first run to create server entity.
        if ($needFirstSynchronization) {
            $this->_optionCache['permitWrite'] = 'true';
            $this->_optionCache['permitWriteLink'] = 'true';
            $this->_optionCache['permitWriteObject'] = 'true';
            $this->_optionCache['permitWriteEntity'] = 'true';
            $this->_optionCache['permitPublicCreateEntity'] = 'true';
        }
    }

    public static function getListOptionsName(): array
    {
        return self::OPTIONS_LIST;
    }

    public static function getListCategoriesOptions(): array
    {
        return self::OPTIONS_CATEGORIES;
    }

    public static function getListOptionsCategory(): array
    {
        return self::OPTIONS_CATEGORY;
    }

    public static function getListOptionsType(): array
    {
        return self::OPTIONS_TYPE;
    }

    public static function getListOptionsWritable(): array
    {
        return self::OPTIONS_WRITABLE;
    }

    public static function getListOptionsDefaultValue(): array
    {
        return self::OPTIONS_DEFAULT_VALUE;
    }

    public static function getListOptionsCriticality(): array
    {
        return self::OPTIONS_CRITICALITY;
    }

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
     *  - ou null.
     *
     * La valeur trouvée dans l'environnement est prioritaire.
     * C'est la façon de forcer une option.
     *
     * @param string $name
     * @return string|bool|int
     */
    public function getOptionUntyped(string $name): bool|int|string // TODO suppress untyped vars on all the class Configuration.
    {
        return self::_changeTypeValueFromString($name, $this->getOptionAsString($name));
    }

    /**
     * Get content of an option as a boolean.
     * An unknown option return a value too.
     * Try to find in this order :
     * - cache
     * - config file
     * - links
     * - default value
     * - or false
     *
     * @param string $name
     * @return bool
     */
    public function getOptionAsBoolean(string $name): bool
    {
        if ($this->getOptionAsString($name) == 'true')
            return true;
        else
            return false;
    }

    /**
     * Get content of an option as a integer.
     * An unknown option return a value too.
     * Try to find in this order :
     * - cache
     * - config file
     * - links
     * - default value
     * - or 0
     *
     * @param string $name
     * @return int
     */
    public function getOptionAsInteger(string $name): int
    {
        $value = $this->getOptionAsString($name);
        if (! is_numeric($value))
            $value = '0';
        return (int)$value;
    }

    /**
     * Get content of an option as a string.
     * An unknown option return a value too.
     * Try to find in this order :
     * - cache
     * - config file
     * - links
     * - default value
     * - or ''
     *
     * @param string $name
     * @return string
     */
    public function getOptionAsString(string $name): string
    {
        if ($name == '')
            return '';

        if (is_a($this->_metrologyInstance, '\Nebule\Library\Metrology'))
            $this->_metrologyInstance->addLog('get option ' . $name, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '56a98331');

        if (! isset(self::OPTIONS_TYPE[$name]))
            $this->_metrologyInstance->addLog('ask unknown option "' . $name . '"', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cbf78f11');

        if (isset($this->_optionCache[$name]) && is_string($this->_optionCache[$name]))
            return $this->_optionCache[$name];

        $result = $this->_getOptionFromEnvironmentStatic($name);

        if ($result == '' && isset(self::OPTIONS_WRITABLE[$name]) && self::OPTIONS_WRITABLE[$name])
            $result = $this->_getOptionFromLinks($name);

        if ($result == '' && isset(self::OPTIONS_DEFAULT_VALUE[$name])) {
            $result = self::OPTIONS_DEFAULT_VALUE[$name];
            if (is_a($this->_metrologyInstance, '\Nebule\Library\Metrology')) {
                $this->_metrologyInstance->addLog('get default value for option ' . $name, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3e39271b');
            }
        }

        if (is_a($this->_metrologyInstance, '\Nebule\Library\Metrology'))
            $this->_metrologyInstance->addLog('return option ' . $name . ' = ' . $result, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd2fd4284');

        if ($result != '' && !$this->_overwriteOptionCacheLock)
            $this->_optionCache[$name] = $result;

        return $result;
    }

    public static function getOptionDefaultValueAsString(string $name): string
    {
        return self::OPTIONS_DEFAULT_VALUE[$name];
    }

    static public function getOptionFromEnvironmentAsString(string $name): string
    {
        return self::_getOptionFromEnvironmentStatic($name);
    }

    static public function getOptionFromEnvironmentAsStringStatic(string $name): string
    {
        return self::_getOptionFromEnvironmentStatic($name);
    }

    public function getOptionFromEnvironmentAsBoolean(string $name): bool
    {
        return self::getOptionFromEnvironmentAsBooleanStatic($name);
    }

    static public function getOptionFromEnvironmentAsBooleanStatic(string $name): bool
    {
        if (self::_getOptionFromEnvironmentStatic($name) == 'true')
            return true;
        else
            return false;
    }

    public function getOptionFromEnvironmentAsInteger(string $name): int
    {
        return (int)self::_getOptionFromEnvironmentStatic($name);
    }

    static public function getOptionFromEnvironmentAsIntegerStatic(string $name): int
    {
        return (int)self::_getOptionFromEnvironmentStatic($name);
    }

    static private function _getOptionFromEnvironmentStatic(string $name): string
    {
        if ($name == '' )
            return '';

        if (file_exists(References::CONFIGURATION_FILE)) {
            $file = file(References::CONFIGURATION_FILE, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            foreach ($file as $line) {
                $l = trim($line);

                if ($l == '' || str_starts_with($l, '#'))
                    continue;

                $nameOnFile = trim((string)filter_var(strtok($l, '='), FILTER_SANITIZE_STRING));
                $value = trim((string)filter_var(strtok('='), FILTER_SANITIZE_STRING));
                if ($nameOnFile == $name)
                    return $value;
            }
        }
        return '';
    }

    /**
     * Transcode option's value from a string.
     *
     * @param string $name
     * @param string $value
     * @return string|bool|int
     */
    static private function _changeTypeValueFromString(string $name, string $value = ''): bool|int|string
    {
        if (!isset(self::OPTIONS_TYPE[$name]))
            return $value;
        switch (self::OPTIONS_TYPE[$name]) {
            case 'string' :
                return trim($value);
            case 'boolean' :
                if ($value == 'true')
                    return true;
                elseif ($value == 'false')
                    return false;
                else
                    return self::OPTIONS_DEFAULT_VALUE[$name];
            case 'integer' :
                return (int)$value;
            default :
                return $value;
        }
    }

    /**
     * Transcode option's value to a string.
     *
     * @param string          $name
     * @param bool|int|string $value
     * @return string
     */
    private function _changeTypeValueToString(string $name, bool|int|string $value): string
    {
        if (!isset(self::OPTIONS_TYPE[$name]) )
            return '';

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
     * @param string $name
     * @return string|bool|int|null
     */
    public function getOptionFromLinksUntyped(string $name): bool|int|string|null
    {
        return $this->_changeTypeValueFromString($name, $this->_getOptionFromLinks($name));
    }

    /**
     * Extrait les options depuis les liens.
     * Retourne :
     *  - une chaine de caractères avec le contenu de l'option ;
     *  - ou null si rien n'est trouvé.
     *
     * @param string $name
     * @return string|null
     */
    public function getOptionFromLinksAsString(string $name): ?string
    {
        return $this->_getOptionFromLinks($name);
    }

    /**
     * Extrait les options depuis les liens.
     * Retourne :
     *  - un booléen avec le contenu de l'option ;
     *  - ou false si rien n'est trouvé.
     *
     * @param string $name
     * @return boolean
     */
    public function getOptionFromLinksAsBoolean(string $name): bool
    {
        if ($this->_getOptionFromLinks($name) == 'true')
            return true;
        else
            return false;
    }

    /**
     * Extrait les options depuis les liens.
     * Retourne :
     *  - un nombre avec le contenu de l'option ;
     *  - ou zéro si rien n'est trouvé.
     *
     * @param string $name
     * @return integer
     */
    public function getOptionFromLinksAsInt(string $name): int
    {
        return (int)$this->_getOptionFromLinks($name);
    }

    /**
     * Extrait les options depuis les liens.
     *
     * @param string $name
     * @return string
     */
    private function _getOptionFromLinks(string $name): string
    {
        if ($name == ''
            || !$this->_permitOptionsByLinks
            || $this->_optionsByLinksIsInUse
            || $this->_nebuleInstance === null
        )
            return '';

        // Anti loop.
        $this->_optionsByLinksIsInUse = true;

        // Si une entité de subordination est défini, lit l'option forcée par cette entité.
        $value = '';
        $rid = \Nebule\Library\References::REFERENCE_NEBULE_OPTION . '/' . $name;
        if ($this->_nebuleInstance->getSubordinationEntity() != '') {
            $instance = $this->_nebuleInstance->getSubordinationEntity();
            $value = trim($instance->getProperty($rid));
        }

        // Si aucune valeur trouvée de l'entité de subordination, lit l'option pour l'entité en cours.
        if ($value == ''
            && is_a($this->_nebuleInstance->getEntitiesInstance()->getGhostEntityInstance(), 'Entity')
        )
            $value = trim($this->_nebuleInstance->getEntitiesInstance()->getGhostEntityInstance()->getProperty($rid));

        $this->_optionsByLinksIsInUse = false;

        if (is_a($this->_metrologyInstance, '\Nebule\Library\Metrology'))
            $this->_metrologyInstance->addLog('return option links = ' . $value, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1dc46c1a');

        return $value;
    }

    /**
     * Ecrit une option en cache.
     * L'écriture en cache n'est possible que si cette possibilité n'est pas verrouillée.
     * Le bootstrap verrouille automatiquement en fin de chargement la possibilité
     *   de modification des options directement en cache.
     * Le verrouillage n'est pas annulable.
     *
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function setOptionCache(string $name, string $value): bool
    {
        if ($name == ''
            || $value == ''
            || !isset(self::OPTIONS_TYPE[$name])
            || !self::OPTIONS_WRITABLE[$name]
            || $this->_overwriteOptionCacheLock
        )
            return false;

        $this->_metrologyInstance->addLog('overwrite option cache value ' . $name .' = ' . $this->_changeTypeValueToString($name, $value), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '73802724');

        $this->_optionCache[$name] = $value;
        return true;
    }

    /**
     * Write an option as link for an entity if this option is not protected.
     *
     * @param string      $name
     * @param string      $value
     * @param Entity|null $eidInstance
     * @return boolean
     */
    public function setOptionEnvironment(string $name, string $value, ?Entity $eidInstance = null): bool
    {
        if ($name == ''
            || $value == ''
            || !isset(self::OPTIONS_TYPE[$name])
            || !self::OPTIONS_WRITABLE[$name]
        )
            return false;
        $this->_metrologyInstance->addLog('set option ' . $name . ' = ' . $value, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3ae7eea2');

        if (! is_a($eidInstance, 'Nebule\Library\Entity') || $eidInstance->getID() == '0')
            $eidInstance = $this->_entitiesInstance->getConnectedEntityInstance();

        $instance = new Node($this->_nebuleInstance, '0');
        if (!$instance->setWriteContent($value) || $instance->getID() == '0') {
            $this->_metrologyInstance->addLog('content of option cannot be created', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e064cd5c');
            return false;
        }
        $instance->setType(References::REFERENCE_OBJECT_TEXT);

        $link = 'l>' . $eidInstance->getID() . '>' . $instance->getID() . '>' . $this->getNidFromData(References::REFERENCE_NEBULE_OPTION . '/' . $name);
        $newBlockLink = new BlocLink($this->_nebuleInstance, 'new', Cache::TYPE_LINK);

        if ($newBlockLink->addLink($link)
            && $newBlockLink->signWrite($eidInstance)
        ) {
            $this->_optionCache[$name] = $value;
            return true;
        } else {
            $this->_metrologyInstance->addLog('set option write error', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ac640a99');
            return false;
        }
    }

    /**
     * Lock write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockPermitWrite(): void
    {
        $this->_optionCache['permitWrite'] = 'false';
    }

    /**
     * Lock object's write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockPermitWriteObject(): void
    {
        $this->_optionCache['permitWriteObject'] = 'false';
    }

    /**
     * Lock link's write capabilities on demande.
     * Can be reloaded by flushCache().
     */
    public function lockPermitWriteLink(): void
    {
        $this->_optionCache['permitWriteLink'] = 'false';
    }

    /**
     * Lock writes on cache capabilities.
     * Not cancelable.
     */
    public function lockCache(): void
    {
        $this->_metrologyInstance->addLog('cache lock', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'a65d3e7e');
        $this->_overwriteOptionCacheLock = true;
    }

    /**
     * Reinitialize the values of options on cache.
     * Each option will be reloaded on demand.
     * No effet if cache locked.
     */
    public function flushCache(): void
    {
        if (!$this->_overwriteOptionCacheLock)
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
     * Check values of critical options.
     * If not default value, add a log with the value.
     * @return void
     */
    public function checkReadOnlyOptions(): void
    {
        $this->_metrologyInstance->addLog('check options', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'aa19c70a');

        foreach (self::OPTIONS_LIST as $option) {
            if (self::OPTIONS_CRITICALITY[$option] == 'critical') {
                $value = $this->getOptionUntyped($option);
                if ($value != self::OPTIONS_DEFAULT_VALUE[$option]) {
                    if (is_bool($value)) {
                        if ($value)
                            $value = 'true';
                        else
                            $value = 'false';
                    }
                    if (is_int($value))
                        $value = (string)$value;
                    $this->_metrologyInstance->addLog('warning:critical_option ' . $option . '=' . $value, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '319eab8f');
                }
            }
        }
    }

    /**
     * Check a list of boolean options. If one is false, return false.
     * Except for unlocked that check the current entity state.
     * Except for tokens that check a valid token.
     * @param array $list
     * @return bool
     */
    public function checkBooleanOptions(array $list): bool
    {
        if (sizeof($list) == 0) {
            $this->_metrologyInstance->addLog('empty list of options', Metrology::LOG_LEVEL_ERROR, __METHOD__,'558f764f');
            return false;
        }
        foreach ($list as $name)
        {
            if ($name == 'unlocked') {
                if (!$this->_entitiesInstance->getConnectedEntityIsUnlocked()) {
                    $this->_metrologyInstance->addLog('not permitted with entity locked', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a6c88b6b');
                    return false;
                }
            } elseif ($name == 'token') {
                if (!$this->_tokenizeInstance->checkActionToken()) {
                    $this->_metrologyInstance->addLog('not permitted with invalid token', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ccd082e7');
                    return false;
                }
            } elseif (!isset(self::OPTIONS_TYPE[$name])
                || self::OPTIONS_TYPE[$name] != 'boolean'
                || !$this->getOptionAsBoolean($name)
            ) {
                $this->_metrologyInstance->addLog('not permitted with option=' . $name, Metrology::LOG_LEVEL_DEBUG, __METHOD__,'8318122c');
                return false;
            }
        }
        return true;
    }

    public function checkGroupedBooleanOptions($name): bool
    {
        if (!isset(self::GROUP_ACTIONS_PERMIT[$name]))
        {
            $this->_metrologyInstance->addLog('unknown group action ' . $name, Metrology::LOG_LEVEL_ERROR, __METHOD__,'5edb0ddf');
            return false;
        }
        return $this->_configurationInstance->checkBooleanOptions(self::GROUP_ACTIONS_PERMIT[$name]);
    }

    const GROUP_ACTIONS_PERMIT = array(
        'GroupCreateObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateLink' => ['unlocked','permitWrite','permitCreateLink'],
        'GroupDeleteObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupProtectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupUnprotectObject' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToGroupOpened' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupShareProtectObjectToGroupClosed' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCancelShareProtectObjectToEntity' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupSynchronizeObject' => ['permitWrite','permitWriteObject'],
        'GroupSynchronizeEntity' => ['permitWrite','permitWriteObject'],
        'GroupSynchronizeObjectLinks' => ['permitWrite','permitWriteLink'],
        'GroupSynchronizeApplication' => ['permitWrite','permitWriteLink','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink','permitSynchronizeApplication'],
        'GroupSynchronizeNewEntity' => ['permitWrite','permitWriteObject','permitSynchronizeObject','permitSynchronizeLink'],
        'GroupUploadFileLinks' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'GroupUploadFile' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupUploadText' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateEntity' => ['permitWrite','permitWriteLink','permitWriteObject','permitWriteEntity','token'],
        'GroupCreateGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteGroup','token'],
        'GroupSignLink' => ['unlocked','permitWrite','permitWriteLink','permitCreateLink'],
        'GroupUploadLink' => ['permitWrite','permitWriteLink','permitUploadLink'],
        'GroupObfuscateLink' => ['unlocked','permitWrite','permitWriteLink','permitObfuscatedLink'],
        'GroupDeleteGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupAddToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupRemoveFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupAddItemToGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupRemoveItemFromGroup' => ['unlocked','permitWrite','permitWriteLink','permitWriteGroup'],
        'GroupCreateConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'GroupDeleteConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupAddMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupRemoveMessageOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupAddMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupRemoveMemberOnConversation' => ['unlocked','permitWrite','permitWriteLink','permitWriteConversation'],
        'GroupCreateMessage' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitWriteConversation'],
        'GroupAddProperty' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject'],
        'GroupCreateCurrency' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'GroupCreateTokenPool' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
        'GroupCreateTokens' => ['unlocked','permitWrite','permitWriteLink','permitWriteObject','permitCurrency','permitWriteCurrency','permitCreateCurrency'],
    );

}
