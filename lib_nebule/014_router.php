<?php
declare(strict_types=1);
namespace Nebule\Library;

use const Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS_ACTIVE;

/**
 * FIXME UNDER CONSTRUCTION
 * Routing to application.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Router extends Functions
{
    const SESSION_SAVED_VARS = array();

    // TODO move router from bootstrap to libPOO
    private string $_phpRID;
    private string $_codeBranchNID = '';
    private bool $_needUpdate = false;
    //private string $_libraryRID = '';
    private string $_libraryIID = '';
    private string $_libraryOID = '';
    private array $_librarySID = array();
    private string $_applicationRID = '';
    private string $_applicationIID = '';
    private string $_applicationOID = '';
    private array $_applicationSID = array();
    private string $_applicationNameSpace = '';
    private bool $_applicationNoPreload = false;
    private ?applicationInterface $applicationInstance = null;
    private bool $_initStatus = false;

    protected function _initialisation(): void {
        $this->_phpRID = $this->getNidFromData(References::REFERENCE_OBJECT_APP_PHP);
        $this->_applicationRID = $this->getNidFromData(References::REFERENCE_OBJECT_APP_PHP);
        $this->_getCurrentBranch();
        $this->_getCurrentLibrary();
        $this->_getArgUpdate();
        $this->_findApplicationIID();
        $this->_findApplicationOID();
        $this->_getApplicationNamespace();
        $this->_getApplicationPreload();
        $this->_initStatus = true;
        $this->_saveLibrarySession();
    }

    public function router(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_nebuleInstance->getLoadingStatus() || !$this->_initStatus)
            return;

        $this->_displayRouter();
        $this->_saveLibrarySession();
        $this->_saveApplicationOnSession();
    }

    public function getLibraryIID(): string {return $this->_libraryIID;}
    public function getLibraryOID(): string {return $this->_libraryOID;}
    public function getLibrarySID(): array {return $this->_librarySID;}
    public function getApplicationIID(): string {return $this->_applicationIID;}
    public function getApplicationOID(): string {return $this->_applicationOID;}
    public function getApplicationSID(): array {return $this->_applicationSID;}
    public function getApplicationInstance(): ?Applications {return $this->_applicationInstance;}
    public function getApplicationNameSpace(): string {return $this->_applicationNameSpace;}
    public function getApplicationNoPreload(): bool {return $this->_applicationNoPreload;}
    public function getReady(): bool {return $this->_ready;}

    /**
     * Get the code branch detected by bootstrap.
     * TODO maybe can be enhanced...
     * @return void
     */
    private function _getCurrentBranch(): void {
        global $codeBranchNID;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_codeBranchNID = $codeBranchNID;
        $this->_metrologyInstance->addLog('Current branch : ' . $this->_codeBranchNID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, '9f1bf579');
    }

    private function _getCurrentLibrary(): void {
        global $bootstrapLibraryIID, $bootstrapLibraryOID, $bootstrapLibrarySID;;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_libraryIID = $bootstrapLibraryIID;
        $this->_libraryOID = $bootstrapLibraryOID;
        $this->_librarySID = array($bootstrapLibrarySID); // FIXME on bootstrap.
    }

    /**
     * Get if codes need to be reloaded after an update of objects.
     * @return bool
     */
    public function getNeedUpdate(): bool { return $this->_needUpdate; }
    private function _getArgUpdate(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_needUpdate = $this->getHaveInput(References::COMMAND_UPDATE);
    }

    private function _findApplicationIID(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_applicationIID = $this->_checkApplicationIID($this->_findApplicationAsk());
        if ($this->_applicationIID == '')
            $this->_applicationIID = $this->_checkApplicationIID($this->_findApplicationSession('bootstrapApplicationIID'));
        if ($this->_applicationIID == '')
            $this->_applicationIID = $this->_checkApplicationIID($this->_findApplicationConfig());
        if (strlen($this->_applicationIID) != 1 && !$this->_getApplicationRelatedLink(References::RID_INTERFACE_APPLICATIONS_ACTIVE))
            $this->_applicationIID = '';
        if ($this->_applicationIID == '')
            $this->_applicationIID = $this->_findApplicationDefault();
        $this->_metrologyInstance->addLog('find application IID=' . $this->_applicationIID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '5bb68dab');
    }

    private function _checkApplicationIID(string $iid): string {
        $this->_metrologyInstance->addLog('track functions IID=' . $iid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($iid) == 0) {
            $this->_metrologyInstance->addLog('empty', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '3f8451cb');
            return '';
        }
        if (strlen($iid) == 1) {
            $iid = preg_replace('/^([0-9])/', '$1', $iid);
            if ($this->_configurationInstance->getOptionAsBoolean('permitApplication' . $iid))
                return $iid;
            else {
                $this->_metrologyInstance->addLog('invalid short app', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '954a99c4');
                return '';
            }
        }
        if (!Node::checkVirtualNID($iid)) {
            $this->_metrologyInstance->addLog('invalid virtual IID', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '43e6baa9');
            return '';
        }

        $instance = $this->_cacheInstance->newNode($iid);
        if ($instance->getID() == '0') {
            $this->_metrologyInstance->addLog('null instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ff07a565');
            return '';
        }
        if (!$this->_ioInstance->checkLinkPresent($iid)) {
            $this->_metrologyInstance->addLog('without link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e0171206');
            return '';
        }

        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_applicationRID,
            'bl/rl/nid2' => $instance->getID(),
            'bl/rl/nid3' => $this->_codeBranchNID,
            'bl/rl/nid4' => '',
        );
        $instance->getSocialLinks($links, $filter, 'authority');
        // TODO
        /*if (\Nebule\Bootstrap\lnk_checkExist('f',
                \Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS,
                $rid,
                $this->phpRID,
                $this->_codeBranchNID)
        )
            $this->_applicationIID = $rid;*/

        $this->_metrologyInstance->addLog('IID OK', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e7e5cfd0');
        return $iid;
    }

    private function _findApplicationOID(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (strlen($this->_applicationIID) == 1)
            $this->_applicationOID = $this->_applicationIID;
        else {
            if (!$this->_needUpdate && $this->_findApplicationSession('bootstrapApplicationIID') == $this->_applicationIID)
                $this->_applicationOID = $this->_checkApplicationOID($this->_findApplicationSession('bootstrapApplicationOID'));
            if ($this->_applicationOID == '')
                $this->_applicationOID = $this->_checkApplicationOID($this->_findApplicationCode());
            if ($this->_applicationOID == '')
                $this->_applicationOID = $this->_findApplicationDefault();
        }
        $this->_metrologyInstance->addLog('find application OID=' . $this->_applicationOID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '3e9b68d3');
    }

    private function _checkApplicationOID(string $oid): string {
        $this->_metrologyInstance->addLog('track functions OID=' . $oid, Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (strlen($oid) == 0) {
            $this->_metrologyInstance->addLog('empty', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '9e55ac4a');
            return '';
        }
        if (strlen($oid) == 1) {
            $oid = preg_replace('/^([0-9])/', '$1', $oid);
            if ($this->_configurationInstance->getOptionAsBoolean('permitApplication' . $oid))
                return $oid;
            else {
                $this->_metrologyInstance->addLog('invalid short app', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '953c5254');
                return '';
            }
        }
        if (!Node::checkNID($oid)) {
            $this->_metrologyInstance->addLog('invalid OID', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b5568936');
            return '';
        }
        $instance = $this->_cacheInstance->newNode($oid);
        if ($instance->getID() == '0') {
            $this->_metrologyInstance->addLog('null instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '61d0b13c');
            return '';
        }
        if (!$this->_ioInstance->checkLinkPresent($oid)) {
            $this->_metrologyInstance->addLog('without link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '8a83ad5f');
            return '';
        }
        if (!$this->_ioInstance->checkObjectPresent($oid)) {
            $this->_metrologyInstance->addLog('without content', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '5b2dc1ed');
            return '';
        }

        $links = array(); // FIXME
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_applicationRID,
            'bl/rl/nid2' => $instance->getID(),
            'bl/rl/nid3' => $this->_codeBranchNID,
            'bl/rl/nid4' => '',
        );
        $instance->getSocialLinks($links, $filter, 'authority');
        // TODO
        /*if (\Nebule\Bootstrap\lnk_checkExist('f',
                \Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS,
                $rid,
                $this->phpRID,
                $this->_codeBranchNID)
        )
            $this->_applicationIID = $rid;*/

        $this->_metrologyInstance->addLog('OID OK', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '634519a2');
        return $oid;
    }

    private function _findApplicationAsk(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->getFilterInput(References::COMMAND_SWITCH_APPLICATION);
    }

    private function _findApplicationSession(string $name): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();
        $iid = (isset($_SESSION[$name][0])) ? $_SESSION[$name][0] : '';
        session_abort();
        return $iid;
    }

    private function _findApplicationConfig(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return $this->_configurationInstance->getOptionAsString('defaultApplication');
    }

    private function _findApplicationDefault(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        return ($this->_configurationInstance->getOptionAsBoolean('permitApplication1')) ? '1' : '0';
    }

    private function _findApplicationCode(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = $this->_cacheInstance->newNode($this->_applicationIID);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'f',
            'bl/rl/nid1' => $this->_applicationIID,
            'bl/rl/nid3' => $this->_codeBranchNID,
            'bl/rl/nid4' => '',
        );
        $instance->getSocialLinks($links, $filter, 'authority');
        $resultLink = $this->getYoungestLink($links); // FIXME verify it's a PHP type mime

        $this->_applicationSID = array($resultLink->getParsed()['bs/rs1/eid']); // FIXME more than one
        return $resultLink->getParsed()['bl/rl/nid2'];
    }

    private function _getApplicationNamespace(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (strlen($this->_applicationIID) == 1 || strlen($this->_applicationOID) == 1 || !$this->_ioInstance->checkObjectPresent($this->_applicationOID)) {
            $this->_metrologyInstance->addLog('namespace=Nebule\Library', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '49374854');
            $this->_applicationNameSpace = 'Nebule\Library';
            return;
        }

        $content = $this->_ioInstance->getObject($this->_applicationOID, 10000);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $content) as $line) {
            $l = trim($line);
            if (str_starts_with($l, "#"))
                continue;
            $name = trim((string)filter_var(strtok($l, ' '), FILTER_SANITIZE_STRING));
            $value = trim(substr_replace(filter_var(strtok(' '), FILTER_SANITIZE_STRING), '', -1));
            if ($name == 'namespace') {
                $this->_applicationNameSpace = $value;
                $this->_metrologyInstance->addLog('namespace=' . $value, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'fe5308a8');
                break;
            }
        }
    }

    private function _getApplicationPreload(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_getApplicationPreloadSession())
            $this->_applicationNoPreload = true;
        elseif (strlen($this->_applicationIID) == 1)
            $this->_applicationNoPreload = true;
        elseif (!$this->_applicationNoPreload)
            $this->_applicationNoPreload = $this->_getApplicationRelatedLink(References::RID_INTERFACE_APPLICATIONS_DIRECT);

        if ($this->_applicationNoPreload)
            $this->_metrologyInstance->addLog('do not preload application', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0ac7d800');
    }

    private function _getApplicationPreloadSession(): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();
        $noPreloadSession = isset($_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID]);
        session_abort();
        return $noPreloadSession;
    }

    private function _getApplicationRelatedLink(string $type): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $instance = $this->_cacheInstance->newNode($this->_applicationIID);
        $links = array();
        $filter = array(
            'bl/rl/req' => 'l',
            'bl/rl/nid1' => $this->_applicationIID,
            'bl/rl/nid2' => $type,
            'bl/rl/nid3' => '',
            'bl/rl/nid4' => '',
        );
        $instance->getSocialLinks($links, $filter, 'authority');
        if (sizeof($links) > 0)
            return true;
        return false;
    }

    private function _includeApplicationFile(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (strlen($this->_applicationIID) == 1)
            return;
        $this->_metrologyInstance->addLog('include application code OID=' . $this->_applicationOID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '8683e195');
        if ($this->_applicationOID == '' || $this->_applicationIID == '') {
            \Nebule\Bootstrap\bootstrap_setBreak('45', __METHOD__);
        } elseif (!\Nebule\Bootstrap\io_objectInclude($this->_applicationOID)) // FIXME move to I/O
            \Nebule\Bootstrap\bootstrap_setBreak('46', __METHOD__);
    }

    /**
     * Load the application instance.
     *
     * @return void
     */
    private function _instancingApplication(): void {
        global $nebuleInstance, $libraryPPCheckOK, $applicationInstance, $applicationNameSpace, $bootstrapApplicationOID;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (strlen($this->_applicationIID) == 1)
            return;

        $nameSpace = $this->_applicationNameSpace;
        $nameSpaceApplication = $nameSpace.'\\Application';
        $applicationNameSpace = $nameSpaceApplication;

        if ($this->_applicationOID == ''
            || $this->_applicationOID == '0'
            || !$this->_nebuleInstance->getLoadingStatus()
            || !class_exists($nameSpaceApplication, false)
        ) {
            $this->_metrologyInstance->addLog('cannot find class Application on code NID=' . $this->_applicationOID . ' NS=' . $nameSpace,
                Metrology::LOG_LEVEL_ERROR, __METHOD__, 'ea9e5908');
            return;
        }

        $this->_metrologyInstance->log_reopen($nameSpace); // . '\\' . Application::APPLICATION_NAME);

        // Get app instances from session if existed.
        $bootstrapApplicationInstanceSleep = '';
        session_start();
        if (isset($_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID])
            && $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID] != '')
            $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID];
        session_abort();

        try {
            if ($bootstrapApplicationInstanceSleep == '') {
                $this->_metrologyInstance->addLog('application load new instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '397ce035');
                $this->_applicationInstance = new $nameSpaceApplication($this->_nebuleInstance);
            }
            else {
                $this->_metrologyInstance->addLog('application load serialized instance', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'b5f2f3f2');
                $this->_applicationInstance = unserialize($bootstrapApplicationInstanceSleep);
            }
        } catch (\Error $e) {
            \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
            $this->_metrologyInstance->addLog('application load error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
                Metrology::LOG_LEVEL_ERROR, __METHOD__, '202824cb');
            \Nebule\Bootstrap\bootstrap_setBreak('47', __METHOD__);
        }
    }

    private function _initialisationApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (strlen($this->_applicationIID) == 1)
            return;

        if (! is_a($this->_applicationInstance, 'Nebule\Library\Applications')) {
            $this->_metrologyInstance->addLog('error init application', Metrology::LOG_LEVEL_ERROR, __METHOD__, '41ba02a9');
            return;
        }

        $this->_applicationInstance->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_applicationInstance->initialisation();

        if (!$this->_applicationInstance->askDownload()) {
            $this->_applicationInstance->checkSecurity();
        }

        if ($this->_applicationNoPreload) {
            try {
                $this->_applicationInstance->router();
            } catch (\Exception $e) {
                \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
                $this->_metrologyInstance->addLog('application router error ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
                    Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b51282b5');
            }
        }
    }

    private function _displayLocalEntity(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $content = '';
        if (file_exists(References::LOCAL_ENTITY_FILE)) {
            $ioReadMaxData = $this->_configurationInstance->getOptionAsInteger('ioReadMaxData');
            $content = (string)file_get_contents(References::LOCAL_ENTITY_FILE, false, null, 0, $ioReadMaxData);
        }
        if ($content == '')
            $content = 'undefined';
        echo $content;
    }

    private function _displayLoadingAppX(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $name = $this->_applicationNameSpace . '\App' . $this->_applicationIID;
        if ($this->_applicationIID == '0' || $this->_configurationInstance->getOptionAsBoolean('permitApplication' . $this->_applicationIID)) {
            $instance = New $name($this->_nebuleInstance);
            $instance->setEnvironmentLibrary($this->_nebuleInstance);
            $instance->initialisation();
            $instance->display();
        }
    }

    public function instancingApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (is_a($this->_applicationInstance, 'Nebule\Library\Applications'))
            return;
        $this->_includeApplicationFile();
        $this->_instancingApplication();
        $this->_initialisationApplication();
    }

    private function _displayLoadingApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $this->_metrologyInstance->addLog('load application without preload OID=' . $this->_applicationOID, Metrology::LOG_LEVEL_AUDIT, __FUNCTION__, 'e01ea813');
        $this->instancingApplication();
        // FIXME
    }

    private function _displayPreloadingApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $name = $this->_applicationNameSpace . '\AppPreload';
        $instance = New AppPreload($this->_nebuleInstance);
        $instance->setEnvironmentLibrary($this->_nebuleInstance);
        $instance->initialisation();
        $instance->display();
    }

    private function _displayRouter(): void {
        global $bootstrapBreak, $needFirstSynchronization, $bootstrapInlineDisplay, $bootstrapApplicationIID,
               $bootstrapApplicationOID, $bootstrapApplicationNoPreload;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Display only server entity if asked.
        // For compatibility and interoperability.
        if ($this->_nebuleInstance->getHaveInput(References::COMMAND_LOCAL_ENTITY_FILE)) {
            $this->_metrologyInstance->addLog('input ' . \Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE . ' ask display local entity only', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'dcfc2e74');
            $this->_displayLocalEntity();
            return;
        }

        $this->_metrologyInstance->addLog('load application code OID=' . $bootstrapApplicationOID, Metrology::LOG_LEVEL_NORMAL, __METHOD__, 'aab236ff');
        if (strlen($this->_applicationIID) == 1)
            $this->_displayLoadingAppX();
        elseif ($this->_applicationNoPreload)
            $this->_displayLoadingApplication();
        else
            $this->_displayPreloadingApplication();

        /*if ($bootstrapApplicationIID == '0' || $bootstrapApplicationOID == '0') {
            $instance = New \Nebule\Library\App0();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '1' && $this->_configurationInstance->getOptionAsBoolean('permitApplication1')) {
            $instance = New \Nebule\Library\App1();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '2' && $this->_configurationInstance->getOptionAsBoolean('permitApplication2')) {
            $instance = New \Nebule\Library\App2();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '3' && $this->_configurationInstance->getOptionAsBoolean('permitApplication3')) {
            $instance = New \Nebule\Library\App3();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '4' && $this->_configurationInstance->getOptionAsBoolean('permitApplication4')) {
            $instance = New \Nebule\Library\App4();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '5' && $this->_configurationInstance->getOptionAsBoolean('permitApplication5')) {
            $instance = New \Nebule\Library\App5();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '6' && $this->_configurationInstance->getOptionAsBoolean('permitApplication6')) {
            $instance = New \Nebule\Library\App6();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '7' && $this->_configurationInstance->getOptionAsBoolean('permitApplication7')) {
            $instance = New \Nebule\Library\App7();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '8' && $this->_configurationInstance->getOptionAsBoolean('permitApplication8')) {
            $instance = New \Nebule\Library\App8();
            $instance->display();
        }
        elseif ($bootstrapApplicationIID == '9' && $this->_configurationInstance->getOptionAsBoolean('permitApplication9')) {
            $instance = New \Nebule\Library\App9();
            $instance->display();
        }
        elseif (strlen($bootstrapApplicationIID) < 2) {
            $instance = New \Nebule\Library\App0();
            $instance->display();
        }*/
//    elseif (isset($bootstrapApplicationInstanceSleep) && $bootstrapApplicationInstanceSleep != '')
//        bootstrap_displaySleepingApplication();
        /*elseif ($bootstrapApplicationNoPreload)
            $this->_displayNoPreloadApplication();
        else
            $this->_displayPreloadApplication();*/

        //\Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);

        // Sérialise les instances et les sauve dans la session PHP.
        //$this->_saveLibrarySession(); // FIXME à supprimer ?
    }

    private function _saveLibrarySession(): void {
        global $bootstrapLibraryIID, $bootstrapLibraryOID, $bootstrapLibrarySID;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        if (!is_a($this->_nebuleInstance, 'Nebule\Library\nebule'))
            return;
        $this->_metrologyInstance->addLog('serialize class \Nebule\Library\nebule', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '01fc1f8f');

        session_start();
        $_SESSION['bootstrapLibraryIID'] = $bootstrapLibraryIID;
        $_SESSION['bootstrapLibraryOID'] = $bootstrapLibraryOID;
        $_SESSION['bootstrapLibrarySID'] = $bootstrapLibrarySID;
        $_SESSION['bootstrapLibraryInstances'][$bootstrapLibraryIID] = serialize($this->_nebuleInstance);
        session_write_close();
    }

    private function _saveApplicationOnSession(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        session_start();
        $_SESSION['bootstrapApplicationOID'][0] = $this->_applicationOID;
        $_SESSION['bootstrapApplicationIID'][0] = $this->_applicationIID;
        $_SESSION['bootstrapApplicationSID'][0] = $this->_applicationSID;
        $_SESSION['bootstrapApplicationIID'][$this->_applicationOID] = $this->_applicationOID;
        if (is_a($this->_applicationInstance, 'Nebule\Library\Applications'))
            $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID] = serialize($this->_applicationInstance);
        session_write_close();
    }
}