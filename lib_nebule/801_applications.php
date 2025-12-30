<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Application\Autent\Display;
use Nebule\Application\Option\Action;
use Nebule\Application\Option\Translate;

/**
 * Classe de référence des applications.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
abstract class Applications extends Functions implements ApplicationInterface
{
    const APPLICATION_NAME = 'undef';
    const APPLICATION_SURNAME = 'undef';
    const APPLICATION_AUTHOR = 'undef';
    const APPLICATION_VERSION = 'undef';
    const APPLICATION_LICENCE = 'undef';
    const APPLICATION_WEBSITE = 'undef';
    const APPLICATION_NODE = 'undef';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = false;
    const USE_MODULES_TRANSLATE = false;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array();
    const LIST_MODULES_EXTERNAL = array();

    protected ?ApplicationModules $_applicationModulesInstance = null;
    protected string $_applicationNamespace = '';

    protected function _initialisation(): void
    {
        $this->_applicationInstance = $this;
        $this->_applicationNamespace = '\\Nebule\\Application\\' . strtoupper(substr(static::APPLICATION_NAME, 0, 1)) . strtolower(substr(static::APPLICATION_NAME, 1));

        $this->_findEnvironment();

        if ($this->_findAskDownload())
            return; // Do nothing more on app.

        $displayName = $this->_applicationNamespace . '\Display';
        $this->_metrologyInstance->addLog('instancing application display ' . $displayName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9d8c59bb');
        $this->_displayInstance = new $displayName($this->_nebuleInstance);

        $actionName = $this->_applicationNamespace . '\Action';
        $this->_metrologyInstance->addLog('instancing application action ' . $actionName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e19f2105');
        $this->_actionInstance = new $actionName($this->_nebuleInstance);

        $translateName = $this->_applicationNamespace . '\Translate';
        $this->_metrologyInstance->addLog('instancing application translate ' . $translateName, Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8fc40a38');
        $this->_translateInstance = new $translateName($this->_nebuleInstance);

        $this->_displayInstance->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_displayInstance->setEnvironmentApplication($this);

        $this->_actionInstance->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_actionInstance->setEnvironmentApplication($this);

        $this->_translateInstance->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_translateInstance->setEnvironmentApplication($this);

        $this->_metrologyInstance->addLog('instancing application modules', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '1ddcee4c');
        $this->_applicationModulesInstance = new ApplicationModules($this);

        $this->_metrologyInstance->addLog('initialisation application translate', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'bd674f44');
        try {
            $this->_translateInstance->initialisation();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('initialisation application translate error (' . $e->getCode() . ') : ' . $e->getFile() . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n" . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '585648a2');
        }

        $this->_metrologyInstance->addLog('initialisation application display', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '451a8518');
        try {
            $this->_displayInstance->initialisation();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('initialisation application display error (' . $e->getCode() . ') : ' . $e->getFile() . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n" . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '4c7da4e2');
        }

        $this->_metrologyInstance->addLog('initialisation application action', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'd3478bd7');
        try {
            $this->_actionInstance->initialisation();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('initialisation application action error (' . $e->getCode() . ') : ' . $e->getFile() . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n" . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '3c042de3');
        }
        $this->_actionInstance->getActions(); // try to catch inside
    }

    public function __destruct()
    {
        return true;
    }

    public function __toString(): string
    {
        return static::class;
    }

    public function __sleep(): array
    {
        return array();
    }


    public function getClassName(): string
    {
        return static::class;
    }

    public function getName(): string
    {
        return static::APPLICATION_NAME;
    }

    public function getNamespace(): string
    {
        return $this->_applicationNamespace;
    }

    public function getNebuleInstance(): ?nebule
    {
        return $this->_nebuleInstance;
    }

    public function getDisplayInstance(): ?Displays
    {
        return $this->_displayInstance;
    }

    public function getTranslateInstance(): ?Translates
    {
        return $this->_translateInstance;
    }

    public function getMetrologyInstance(): ?Metrology
    {
        return $this->_metrologyInstance;
    }

    public function getActionInstance(): ?Actions
    {
        return $this->_actionInstance;
    }

    public function getApplicationModulesInstance(): ApplicationModules
    {
        return $this->_applicationModulesInstance;
    }

    public function getCurrentObjectID(): string
    {
        return $this->_nebuleInstance->getCurrentObjectOID();
    }

    public function getCurrentObjectInstance(): Node
    {
        return $this->_nebuleInstance->getCurrentObjectInstance();
    }

    protected function _findEnvironment(): void
    {
        $this->_findURL();
    }

    protected string $_urlProtocol;
    protected string $_urlHost;
    protected string $_urlBasename;
    protected string $_urlPath;
    protected string $_urlHostname;

    protected function _findURL(): void
    {
        if (isset($_SERVER['HTTPS'])
                && $_SERVER['HTTPS']
        )
            $this->_urlProtocol = 'https';
        else
            $this->_urlProtocol = 'http';
        //$this->_urlHost	= $_SERVER['HTTP_HOST'];
        $this->_urlHost = $this->_configurationInstance->getOptionUntyped('hostURL');
        $explodeBaseName = explode('/', $_SERVER['REQUEST_URI']);
        $this->_urlBasename = end($explodeBaseName);
        $this->_urlPath = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($this->_urlBasename) - 1);
        $this->_urlHostname = $this->_urlProtocol . '://' . $this->_urlHost . $this->_urlPath;
    }

    public function getUrlProtocol(): string
    {
        return $this->_urlProtocol;
    }

    public function getUrlHost(): string
    {
        return $this->_urlHost;
    }

    public function getUrlBasename(): string
    {
        return $this->_urlBasename;
    }

    public function getUrlPath(): string
    {
        return $this->_urlPath;
    }

    public function getUrlHostname(): string
    {
        return $this->_urlHostname;
    }


    protected bool $_askDownload = false;
    protected string $_askDownloadObject = '';
    protected string $_askDownloadLinks = '';

    public function askDownload(): bool
    {
        return $this->_askDownload;
    }

    protected function _findAskDownload(): bool
    {
        $arg_dwlobj = trim((string)filter_input(INPUT_GET, References::OBJECTS_FOLDER, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_dwlobj)) {
            $this->_askDownload = true;
            $this->_askDownloadObject = trim($arg_dwlobj);
            $this->_metrologyInstance->addLog('ask for download object ' . $arg_dwlobj, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'df913e73');
        }
        $arg_dwllnk = trim((string)filter_input(INPUT_GET, References::LINKS_FOLDER, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        if (Node::checkNID($arg_dwllnk)) {
            $this->_askDownload = true;
            $this->_askDownloadLinks = trim($arg_dwllnk);
            $this->_metrologyInstance->addLog('ask for download links ' . $arg_dwllnk, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '98d5ee6d');
        }
        return $this->_askDownload;
    }

    /**
     * Fonction de téléchargement d'objets ou de liens.
     * Le téléchargement se fait sous forme de fichier pour dé-nebulisation d'un objet
     *   ou affichage dans un navigateur.
     * C'est la seule façon de télécharger le contenu d'un objet protégé.
     *
     * @return void
     */
    protected function _download(): void
    {
        $err404 = false;
        if ($this->_askDownloadLinks != '') // Détermine si c'est un lien à télécharger.
        {
            if ($this->_nebuleInstance->getIoInstance()->checkLinkPresent($this->_askDownloadLinks)) {
                $this->_metrologyInstance->addLog('Sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '91b305b1');
                // Flush des erreurs.
                ob_end_clean();
                // Transmission.
                header('Content-Description: File Transfer');
                header('Content-type: text/plain');
                header('Content-Disposition: attachment; filename="' . $this->_askDownloadLinks . '.neb.lnk"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');

                $this->_metrologyInstance->addLog('End sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd5318e9f');
            } else {
                $err404 = true;
                $this->_metrologyInstance->addLog('Error 404 sending links ' . $this->_askDownloadLinks, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'df11e69f');
            }
        } else // Sinon c'est un objet à télécharger.
        {
            $instance = $this->_cacheInstance->newNode($this->_askDownloadObject);
            $data = $instance->getContent(0);
            if ($data != null) {
                $this->_metrologyInstance->addLog('Sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '18852ac4');
                // Calcul type mime, nom et suffixe de fichier pour l'utilisateur final.
                $downloadmime = $instance->getType('all');
                $downloadname = $instance->getName('all');
                $downloadsuffix = $instance->getSuffixName('all');
                if ($downloadsuffix != '') {
                    $downloadname .= '.' . $downloadsuffix;
                }
                // Flush des erreurs.
                ob_end_clean();
                // Transmission.
                header('Content-Description: File Transfer');
                header('Content-type: ' . $downloadmime);
                header('Content-Disposition: attachment; filename="' . $downloadname . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                echo $data;

                $this->_metrologyInstance->addLog('End sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '99f390f9');
            } else {
                $err404 = true;
                $this->_metrologyInstance->addLog('Error 404 sending object ' . $this->_askDownloadObject, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'f8234249');
            }
        }

        if ($err404) {
            $this->_metrologyInstance->addLog('Sending error 404 ', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '6efad36a');
            // Transmission.
            ob_end_clean();
            ob_clean();
            header('HTTP/1.0 404 Not Found');
            echo "<h1>404 Not Found</h1>\nThe page that you have requested could not be found.";
        }
    }


    public function getIsModuleActivated(Node $module): bool
    {
        return $this->_applicationModulesInstance->getIsModuleActivated($module);
    }

    public function getModulesListNames(): array
    {
        return $this->_applicationModulesInstance->getModulesListNames();
    }

    public function getModulesListInstances(): array
    {
        return $this->_applicationModulesInstance->getModulesListInstances();
    }

    public function getModulesTranslateListInstances(): array
    {
        return $this->_applicationModulesInstance->getModulesTranslateListInstances();
    }

    public function getModulesListOID(): array
    {
        return $this->_applicationModulesInstance->getModulesListOID();
    }

    public function getModulesListRID(): array
    {
        return $this->_applicationModulesInstance->getModulesListRID();
    }

    public function getModulesListSignersRID(): array
    {
        return $this->_applicationModulesInstance->getModulesListSignersRID();
    }

    public function getModulesListValid(): array
    {
        return $this->_applicationModulesInstance->getModulesListValid();
    }

    public function getModulesListEnabled(): array
    {
        return $this->_applicationModulesInstance->getModulesListEnabled();
    }

    public function isModuleLoaded(string $name): bool
    {
        return $this->_applicationModulesInstance->getIsModuleLoaded($name);
    }

    public function getCurrentModuleInstance(): ?Modules
    {
        return $this->_applicationModulesInstance->getCurrentModuleInstance();
    }

    public function getModule(string $name): ?Modules
    {
        return $this->_applicationModulesInstance->getModule($name);
    }


    /**
     * Routage.
     */
    public function router(): void
    {
        $this->_metrologyInstance->addLog('running application', Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'cd5ec83d');

        if ($this->_askDownload)
            $this->_download();
        else {
            $this->_metrologyInstance->addLog('running display', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '13cb1fd7');
            try {
                $this->_displayInstance->display();
            } catch (\Exception $e) {
                $this->_metrologyInstance->addLog('error display (' . $e->getCode() . ') : ' . $e->getFile()
                        . '(' . $e->getLine() . ') : ' . $e->getMessage() . "\n"
                        . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '93be3bc2');
            }
            $this->_metrologyInstance->addLog('end display', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '07edae7d');
        }
    }


    protected string $_checkSecurityAll = "OK";

    public function getCheckSecurityAll(): string
    {
        return $this->_checkSecurityAll;
    }

    public function checkSecurity(): void
    {
        $this->_checkSecurity();
    }

    protected function _checkSecurity(): void
    {
        $this->_checkSecurityBootstrap();
        $this->_checkSecurityCryptoHash();
        $this->_checkSecurityCryptoSym();
        $this->_checkSecurityCryptoAsym();
        $this->_checkSecuritySign();
        $this->_checkSecurityURL();

        $this->_checkSecurityAll = 'OK';

        if ($this->_checkSecurityBootstrap == 'WARN'
                || $this->_checkSecurityCryptoHash == 'WARN'
                || $this->_checkSecurityCryptoSym == 'WARN'
                || $this->_checkSecurityCryptoAsym == 'WARN'
                || $this->_checkSecuritySign == 'WARN'
                || $this->_checkSecurityURL == 'WARN'
        ) {
            $this->_checkSecurityAll = 'WARN';
            $this->_metrologyInstance->addLog('general security WARN', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'bb110e27');
        }

        if ($this->_checkSecurityBootstrap == 'ERROR'
                || $this->_checkSecurityCryptoHash == 'ERROR'
                || $this->_checkSecurityCryptoSym == 'ERROR'
                || $this->_checkSecurityCryptoAsym == 'ERROR'
                || $this->_checkSecuritySign == 'ERROR'
                || $this->_checkSecurityURL == 'ERROR'
        ) {
            $this->_checkSecurityAll = 'ERROR';
            $this->_metrologyInstance->addLog('general security ERROR', Metrology::LOG_LEVEL_ERROR, __METHOD__, '7f72506b');
        }
    }

    protected string $_checkSecurityBootstrap = "ERROR";
    protected string $_checkSecurityBootstrapMessage = "::::act_chk_errBootstrap";

    public function getCheckSecurityBootstrap(): string
    {
        return $this->_checkSecurityBootstrap;
    }

    public function getCheckSecurityBootstrapMessage(): string
    {
        return $this->_checkSecurityBootstrapMessage;
    }

    protected function _checkSecurityBootstrap(): void
    {
        $this->_checkSecurityBootstrap = 'OK';
        $this->_checkSecurityBootstrapMessage = "OK";

        /*        $this->_checkSecurityBootstrap = 'ERROR';
                $data = file_get_contents(nebule::NEBULE_BOOTSTRAP_FILE);
                $hash = $this->getNidFromData($data);

                // Recherche les liens de validation.
                $hashRef = $this->getNidFromData(References::REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP);
                $object = $this->_cacheInstance->newNode($hashRef);
                $links = $object->getLinksOnFields('', '', 'f', $hashRef, $hash, $hashRef);

                // Trie sur les autorités locales.
                $ok = false;
                foreach ($links as $link) {
                    foreach ($this->_nebuleInstance->getLocalAuthorities() as $autority) {
                        if ($link->getParsed()['bs/rs1/eid'] == $autority) {
                            $ok = true;
                            break 2;
                        }
                    }
                }
                unset($data, $hash, $object, $links, $link);

                if ($ok) {
                    $this->_checkSecurityBootstrap = 'OK';
                    $this->_checkSecurityBootstrapMessage = "OK";
                    $this->_metrologyInstance->addLog('SECURITY OK Bootstrap', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'a8578fbf');
                }*/
        // Modification pour le mode rescue afin de permettre un déverrouillage sur un boostrap inconnu. Le mode rescue est dangereux. @todo bof...
        /*		if ( $this->_nebuleInstance->getModeRescue()
				&& ! $ok
			)
		{
			$this->_checkSecurityBootstrap = 'WARN';
			$this->_checkSecurityBootstrapMessage = "::::act_chk_errBootstrap";
			$this->_metrologyInstance->addLog('SECURITY WARN Bootstrap', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
		}*/
    }

    protected string $_checkSecurityCryptoHash = 'WARN';
    protected string $_checkSecurityCryptoHashMessage = 'HASH Unchecked';

    public function getCheckSecurityCryptoHash(): string
    {
        return $this->_checkSecurityCryptoHash;
    }

    public function getCheckSecurityCryptoHashMessage(): string
    {
        return $this->_checkSecurityCryptoHashMessage;
    }

    protected function _checkSecurityCryptoHash(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkHashFunction()) { // TODO
            $this->_checkSecurityCryptoHash = 'OK';
            $this->_checkSecurityCryptoHashMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Hash Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '46f04cd0');
        } else {
            $this->_checkSecurityCryptoHash = 'ERROR';
            $this->_checkSecurityCryptoHashMessage = '::::act_chk_errCryptHash';
            $this->_metrologyInstance->addLog('SECURITY ERROR Hash Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3b3440f7');
        }
    }

    protected string $_checkSecurityCryptoSym = 'WARN';
    protected string $_checkSecurityCryptoSymMessage = 'SYM Unchecked';

    public function getCheckSecurityCryptoSym(): string
    {
        return $this->_checkSecurityCryptoSym;
    }

    public function getCheckSecurityCryptoSymMessage(): string
    {
        return $this->_checkSecurityCryptoSymMessage;
    }

    protected function _checkSecurityCryptoSym(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkSymmetricFunction()) { // TODO
            $this->_checkSecurityCryptoSym = 'OK';
            $this->_checkSecurityCryptoSymMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Sym Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'acc2b1c1');
        } else {
            $this->_checkSecurityCryptoSym = 'ERROR';
            $this->_checkSecurityCryptoSymMessage = '::::act_chk_errCryptSym';
            $this->_metrologyInstance->addLog('SECURITY ERROR Sym Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '50a09db3');
        }
    }

    protected string $_checkSecurityCryptoAsym = 'WARN';
    protected string $_checkSecurityCryptoAsymMessage = 'ASYM Unchecked';

    public function getCheckSecurityCryptoAsym(): string
    {
        return $this->_checkSecurityCryptoAsym;
    }

    public function getCheckSecurityCryptoAsymMessage(): string
    {
        return $this->_checkSecurityCryptoAsymMessage;
    }

    protected function _checkSecurityCryptoAsym(): void
    {
        if (true || $this->_nebuleInstance->getCryptoInstance()->checkAsymmetricFunction()) { // TODO
            $this->_checkSecurityCryptoAsym = 'OK';
            $this->_checkSecurityCryptoAsymMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Asym Crypto', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0af33bed');
        } else {
            $this->_checkSecurityCryptoAsym = 'ERROR';
            $this->_checkSecurityCryptoAsymMessage = '::::act_chk_errCryptAsym';
            $this->_metrologyInstance->addLog('SECURITY ERROR Asym Crypto', Metrology::LOG_LEVEL_ERROR, __METHOD__, '12ba7b66');
        }
    }

    protected string $_checkSecuritySign = 'WARN';
    protected string $_checkSecuritySignMessage = 'SIGN Unchecked';

    public function getCheckSecuritySign(): string
    {
        return $this->_checkSecuritySign;
    }

    public function getCheckSecuritySignMessage(): string
    {
        return $this->_checkSecuritySignMessage;
    }

    protected function _checkSecuritySign(): void
    {
        if ($this->_nebuleInstance->getCryptoInstance()->checkFunction($this->_configurationInstance->getOptionAsString('cryptoAsymmetricAlgorithm'), Crypto::TYPE_ASYMMETRIC)) {
            $this->_checkSecuritySign = 'OK';
            $this->_checkSecuritySignMessage = 'OK';
            $this->_metrologyInstance->addLog('SECURITY OK Sign', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '148b111d');
        } else {
            $this->_checkSecuritySign = 'ERROR';
            $this->_checkSecuritySignMessage = '::::act_chk_errSigns';
            $this->_metrologyInstance->addLog('SECURITY ERROR Sign', Metrology::LOG_LEVEL_ERROR, __METHOD__, '70b97981');
        }
        /*       $this->_checkSecuritySign = 'WARN';
               if (!$this->_configurationInstance->getOptionAsBoolean('permitCheckSignOnVerify')) {
                   $this->_checkSecuritySign = 'WARN';
                   $this->_checkSecuritySignMessage = '::::act_chk_warnSigns';
               } else {
                   $validLink = 'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>4fcc946ef03dff882c0b6a1717c99c0ce57639e99d1f52509e846874c98dad5abd28685c9d065b4ef0e9fefbbee217e91fc4a72ecac81712e1e2c14bd06612e71e9afdb09ef1c10e68117fe8edc4f93510318719d0a6d7436a1802cd38f814cba8503ef24d50aeca961825bc39b169acbe52240fa8528a44f387ee5dff0e096a2ab49a0b181fa688678540dfc409000104a6ab77c44a4495ac98d48f35658238c99f5b1f83d04c3309412ebf26b7b23c18bdde43b964ebb6b28b60393b4c343f567137461743153091039c07e35432fa7d0b46b729f65c11960cbda5cb78f3d8da52aaf662724e771125cce2fb99ef1409fbb23840872c6557fe63f2b25c8fc49b6b5663a44cdf2e829ffa9698cc121648136fd102333a556a97ac5b208a6b6fa584e239a35237fe9c38fd09fbe4c0580ca538d92c4e29d5e22ce4846df2563dc4cb39a599b92f22018b4973b768cf59cb8f517f3adae3ee21b7c43a812ec6c245fe548e6187a0e07ce6a0af38c40ccd24383216cbd312322e1583d5d358ccdc9911b67fdbf7d13b9f57a0a17a42f736be9dbd383fd9e7c0ce2589fbd6550a8e07ab90618302956a1bf69e76aaf3da829e1af4f7c7ceff169ce5e698ebe1987fa1b694c6b25130c0be5bbfdfe4a8594e54067abe235bf796cf455a84906d02ebc79e3feaa069db7c4adac872c104bfcbc08b2dfbcc3c9fd6aa465fb9d86c7f26.sha2.512';
                   $invalidLink = 'nebule:link/2:0_0>020210714/l>88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256_88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256>4fcc946ef03dff882c0b6a1717c99c0ce57639e99d1f52509e846874c98dad5abd28685c9d065b4ef0e9fefbbee217e91fc4a72ecac81712e1e2c14bd06612e71e9afdb09ef1c10e68117fe8edc4f93510318719d0a6d7436a1802cd38f814cba8503ef24d50aeca961825bc39b169acbe52240fa8528a44f387ee5dff0e096a2ab49a0b181fa688678540dfc409000104a6ab77c44a4495ac98d48f35658238c99f5b1f83d04c3309412ebf26b7b23c18bdde43b964ebb6b28b60393b4c343f567137461743153091039c07e35432fa7d0b46b729f65c11960cbda5cb78f3d8da52aaf662724e771125cce2fb99ef1409fbb23840872c6557fe63f2b25c8fc49b6b5663a44cdf2e829ffa9698cc121648136fd102333a556a97ac5b208a6b6fa584e239a35237fe9c38fd09fbe4c0580ca538d92c4e29d5e22ce4846df2563dc4cb39a599b92f22018b4973b768cf59cb8f517f3adae3ee21b7c43a812ec6c245fe548e6187a0e07ce6a0af38c40ccd24383216cbd312322e1583d5d358ccdc9911b67fdbf7d13b9f57a0a17a42f736be9dbd383fd9e7c0ce2589fbd6550a8e07ab90618302956a1bf69e76aaf3da829e1af4f7c7ceff169ce5e698ebe1987fa1b694c6b25130c0be5bbfdfe4a8594e54067abe235bf796cf455a84906d02ebc79e3feaa069db7c4adac872c104bfcbc08b2dfbcc3c9fd6aa465fb9d86c7f27.sha2.512';
                   $instanceValidLink = $this->_nebuleInstance->newLink($validLink);
                   $instanceInvalidLink = $this->_nebuleInstance->newLink($invalidLink);

                   if ($instanceValidLink->getSigned() === false
                       || $instanceInvalidLink->getSigned() === true
                   ) {
                       $this->_checkSecuritySign = 'ERROR';
                       $this->_checkSecuritySignMessage = '::::act_chk_errSigns';
                       $this->_metrologyInstance->addLog('SECURITY ERROR Sign', Metrology::LOG_LEVEL_ERROR, __METHOD__, '70b97981');
                   } else {
                       $this->_checkSecuritySign = 'OK';
                       $this->_checkSecuritySignMessage = 'OK';
                       $this->_metrologyInstance->addLog('SECURITY OK Sign', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '148b111d');
                   }
                   unset($validLink, $instanceValidLink, $invalidLink, $instanceInvalidLink);
               }*/
    }

    protected string $_checkSecurityURL = 'OK';
    protected string $_checkSecurityURLMessage = 'OK';

    public function getCheckSecurityURL(): string
    {
        return $this->_checkSecurityURL;
    }

    public function getCheckSecurityURLMessage(): string
    {
        return $this->_checkSecurityURLMessage;
    }

    protected function _checkSecurityURL(): void
    {
        $this->_checkSecurityURL = 'OK';
        if ($this->_urlProtocol == 'http'
                && $this->_configurationInstance->getOptionUntyped('displayUnsecureURL')
        ) {
            $this->_checkSecurityURL = 'WARN';
            $this->_checkSecurityURLMessage = $this->_translateInstance->getTranslate('Connexion non sécurisée')
                    . '. ' . $this->_translateInstance->getTranslate('Essayer plutôt')
                    . ' <a href="https://' . $this->_urlHost . '/' . $this->_urlBasename . '">https://' . $this->_urlHost . '/</a>';
            $this->_metrologyInstance->addLog('SECURITY WARN URL', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'a4f9a7e0');
        } else {
            $this->_metrologyInstance->addLog('SECURITY OK URL', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '98045f5f');
        }
    }


    /**
     * Marque un objet.
     *
     * @param string $object
     * @return void
     */
    public function setMarkObject(string $object): void
    {
        $list = $this->_sessionInstance->getSessionStoreAsArray('objectsMarkList');
        $list[$object] = true;
        $this->_sessionInstance->setSessionStoreAsArray('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Supprime la marque d'un objet.
     *
     * @param string $object
     * @return void
     */
    public function setUnmarkObject(string $object): void
    {
        $list = $this->_sessionInstance->getSessionStoreAsArray('objectsMarkList');
        unset($list[$object]);
        $this->_sessionInstance->setSessionStoreAsArray('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Supprime les marques de tous les objets.
     *
     * @return void
     */
    public function setUnmarkAllObjects(): void
    {
        $list = array();
        $this->_sessionInstance->setSessionStoreAsArray('objectsMarkList', $list);
        unset($list);
    }

    /**
     * Lit si un objet est marqué.
     *
     * @param string $object
     * @return boolean
     */
    public function getMarkObject(string $object): bool
    {
        $list = $this->_sessionInstance->getSessionStoreAsArray('objectsMarkList');
        if (isset($list[$object]))
            return true;
        return false;
    }

    /**
     * Lit la liste des objets marqués.
     *
     * @return array
     */
    public function getMarkObjectList(): array
    {
        $list = $this->_sessionInstance->getSessionStoreAsArray('objectsMarkList');
        return $list;
    }
}