<?php
declare(strict_types=1);
namespace Nebule\Library;

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
    private string $_applicationRID = '';
    private string $_applicationIID = '';
    private string $_applicationOID = '';
    private string $_applicationSID = '';
    private string $_applicationNameSpace = '';
    private bool $_applicationNoPreload = false;

    protected function _initialisation(): void {
        $this->_phpRID = $this->getNidFromData(References::REFERENCE_OBJECT_APP_PHP);
        $this->_applicationRID = $this->getNidFromData(References::REFERENCE_OBJECT_APP_PHP);
        $this->_getCurrentBranch();
        //$this->_findApplication();
        //$this->_getApplicationPreload();
    }

    public function router(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        //$this->_includeApplicationFile();
    }

    public function getApplicationIID(): string {return $this->_applicationIID;}
    public function getApplicationOID(): string {return $this->_applicationOID;}
    public function getApplicationSID(): string {return $this->_applicationSID;}
    public function getApplicationNameSpace(): string {return $this->_applicationNameSpace;}
    public function getApplicationNoPreload(): bool {return $this->_applicationNoPreload;}
    public function getReady(): bool {return $this->_ready;}

    private function _findApplication(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_applicationIID = '';
        $this->_applicationOID = '';

        // Get OID of application.
        $this->_applicationIID = $this->_checkApplicationRID($this->_findApplicationAsk());
        if ($this->_applicationIID == '')
            $this->_applicationIID = $this->_checkApplicationRID($this->_findApplicationSession());
        if ($this->_applicationIID == '')
            $this->_findApplicationDefault();

        // Set code ID for internal bootstrap apps.
        session_start();
        if (strlen($this->_applicationIID) < 2)
            $this->_applicationOID = $this->_applicationIID;
        elseif (!$this->_nebuleInstance->getNeedUpdate()
            && isset($_SESSION['bootstrapApplicationIID'][0])
            && $_SESSION['bootstrapApplicationIID'][0] == $this->_applicationIID
            && \Nebule\Bootstrap\nod_checkNID($_SESSION['bootstrapApplicationIID'][0])
            && isset($_SESSION['bootstrapApplicationOID'][0])
        )
            $this->_applicationOID = $_SESSION['bootstrapApplicationOID'][0];
        else
            $this->_applicationOID = \Nebule\Bootstrap\app_getCode($this->_applicationIID);
        session_abort();

        // If running bad, use the default app.
        if ($this->_applicationOID == '') {
            $this->_applicationIID = '0';
            $this->_applicationOID = '0';
        }

        $this->_metrologyInstance->addLog('find application IID=' . $this->_applicationIID . ' OID=' . $this->_applicationOID, Metrology::LOG_LEVEL_AUDIT, __FUNCTION__, '5bb68dab');
    }

    private function _checkApplicationRID(string $rid): string {
        $this->_metrologyInstance->addLog('track functions RID=' . $rid, Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        if (strlen($rid) == 0) {
            $this->_metrologyInstance->addLog('RID empty', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '3f8451cb');
            return '';
        }
        if (strlen($rid) == 1) {
            $rid = preg_replace('/^([0-9])/', '$1', $rid);
            if ($this->_configurationInstance->getOptionAsBoolean('permitApplication' . $rid))
                return $rid;
            else {
                $this->_metrologyInstance->addLog('RID invalid short app', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '954a99c4');
                return '';
            }
        }
        if (strlen($rid) != ((References::VIRTUAL_NODE_SIZE * 2) + strlen('.none.' . (References::VIRTUAL_NODE_SIZE * 8)))) {
            $this->_metrologyInstance->addLog('RID invalid size=' . strlen($rid), Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '99608a08');
            return '';
        }
        if (substr($rid, References::VIRTUAL_NODE_SIZE * 2) != '.none.' . (References::VIRTUAL_NODE_SIZE * 8)) {
            $this->_metrologyInstance->addLog('RID invalid virtual type', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '43e6baa9');
            return '';
        }

        if (!Node::checkNID($rid)) {
            $this->_metrologyInstance->addLog('RID invalid ID', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'e9d6fec8');
            return '';
        }

        $instance = $this->_cacheInstance->newNode($rid);
        if ($instance->getID() == '0') {
            $this->_metrologyInstance->addLog('RID null instance', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'ff07a565');
            return '';
        }

        if (!$this->_ioInstance->checkLinkPresent($rid)) {
            $this->_metrologyInstance->addLog('RID without link', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'e0171206');
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
        $instance->getLinks($links, $filter);
        // TODO

        /*if (\Nebule\Bootstrap\lnk_checkExist('f',
                \Nebule\Bootstrap\LIB_RID_INTERFACE_APPLICATIONS,
            $rid,
                $this->phpRID,
                $this->_codeBranchNID)
        )
            $this->_applicationIID = $rid;*/

        $this->_metrologyInstance->addLog('RID OK', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'e7e5cfd0');
        return $rid;
    }

    private function _findApplicationAsk(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');
        return $this->getFilterInput(References::COMMAND_SWITCH_APPLICATION);
    }

    private function _findApplicationSession(): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');
        session_start();
        $iid = (isset($_SESSION['bootstrapApplicationIID'][0])) ? $_SESSION['bootstrapApplicationIID'][0] : '';
        session_abort();
        return $iid;
    }

    private function _findApplicationDefault(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        //$defaultApplicationID = lib_getConfiguration('defaultApplication');
        $defaultApplicationID = $this->_configurationInstance->getOptionAsString('defaultApplication');
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
            $this->_applicationIID = $defaultApplicationID;
        elseif (\Nebule\Bootstrap\nod_checkNID($defaultApplicationID)
            && \Nebule\Bootstrap\io_checkNodeHaveLink($defaultApplicationID)
        )
            $this->_applicationIID = $defaultApplicationID;
        elseif (\Nebule\Bootstrap\lib_getOption('permitApplication1'))
            $this->_applicationIID = '1';
        else
            $this->_applicationIID = '0';

        $this->_metrologyInstance->addLog('use default application IID=' . $this->_applicationIID,
            Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '423ae49b');
    }

    private function _getApplicationPreload(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        if (!$this->_nebuleInstance->getLoadingStatus())
            return;

        if (isset($_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID]))
            $this->_applicationNoPreload = true;
        elseif (strlen($this->_applicationIID) < 2)
            $this->_applicationNoPreload = true;
        elseif (!$this->_applicationNoPreload) {
            $this->_applicationNoPreload = \Nebule\Bootstrap\app_getPreload($this->_applicationIID);

            if ($this->_applicationNoPreload)
                $this->_metrologyInstance->addLog('do not preload application', Metrology::LOG_LEVEL_AUDIT, __FUNCTION__, '0ac7d800');
        }
        else
            $this->_applicationNoPreload = false;
    }

    private function _includeApplicationFile(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        if (!$this->_nebuleInstance->getLoadingStatus())
            return;

        //$this->_metrologyInstance->addLog('include application code OID=' . $this->_applicationOID, Metrology::LOG_LEVEL_AUDIT, __FUNCTION__, '8683e195');
        if ($this->_applicationOID == '' || $this->_applicationOID == '0') {
            \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
            \Nebule\Bootstrap\bootstrap_setBreak('45', __FUNCTION__);
        } elseif (!\Nebule\Bootstrap\io_objectInclude($this->_applicationOID)) {
            \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
            \Nebule\Bootstrap\bootstrap_setBreak('46', __FUNCTION__);
            $this->_applicationOID = '0';
        }
    }

    private function _getApplicationNamespace(string $oid): string {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');
        $value = '';

        $content = \Nebule\Bootstrap\io_objectRead($oid, 10000);
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

        return $value;
    }

    /**
     * Load the application instance.
     *
     * @return void
     */
    private function _instancingApplication(): void {
        global $nebuleInstance, $libraryPPCheckOK, $applicationInstance, $applicationNameSpace, $bootstrapApplicationOID;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        $nameSpace = $this->_getApplicationNamespace($this->_applicationOID);
        $nameSpaceApplication = $nameSpace.'\\Application';
        $applicationNameSpace = $nameSpaceApplication;

        if ($this->_applicationOID == ''
            || $this->_applicationOID == '0'
            || !$this->_nebuleInstance->getLoadingStatus()
            || !class_exists($nameSpaceApplication, false)
        ) {
            $this->_metrologyInstance->addLog('cannot find class Application on code NID=' . $this->_applicationOID . ' NS=' . $nameSpace,
                Metrology::LOG_LEVEL_ERROR, __FUNCTION__, 'ea9e5908');
            return;
        }

        \Nebule\Bootstrap\log_reopen($nameSpace); // . '\\' . Application::APPLICATION_NAME);

        // Get app instances from session if exist.
        $bootstrapApplicationInstanceSleep = '';
        session_start();
        if (isset($_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID])
            && $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID] != '')
            $bootstrapApplicationInstanceSleep = $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID];
        session_abort();

        try {
            if ($bootstrapApplicationInstanceSleep == '') {
                $this->_metrologyInstance->addLog('application load new instance', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '397ce035');
                $this->_applicationInstance = new $nameSpaceApplication($this->_nebuleInstance);
            }
            else {
                $this->_metrologyInstance->addLog('application load serialized instance', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, 'b5f2f3f2');
                $this->_applicationInstance = unserialize($bootstrapApplicationInstanceSleep);
            }
        } catch (\Error $e) {
            \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
            $this->_metrologyInstance->addLog('application load error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
                Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '202824cb');
            \Nebule\Bootstrap\bootstrap_setBreak('47', __FUNCTION__);
        }
    }

    private function _initialisationApplication(bool $run): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        if (! is_a($this->_applicationInstance, 'Nebule\Library\Applications')) {
            $this->_metrologyInstance->addLogAndDisplay('error init application', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '41ba02a9');
            return;
        }

        $this->_applicationInstance->setEnvironmentLibrary($this->_nebuleInstance);
        $this->_applicationInstance->initialisation();

        if (!$this->_applicationInstance->askDownload()) {
            $this->_applicationInstance->checkSecurity();
        }

        if ($run) {
            try {
                $this->_applicationInstance->router();
            } catch (\Exception $e) {
                \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);
                $this->_metrologyInstance->addLog('application router error ('  . $e->getCode() . ') : ' . $e->getFile()
                    . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n" . $e->getTraceAsString(),
                    Metrology::LOG_LEVEL_ERROR, __FUNCTION__, 'b51282b5');
            }
        }
    }

    private function _saveApplicationOnSession(): void {
        global $applicationInstance, $bootstrapApplicationIID, $bootstrapApplicationOID, $bootstrapApplicationSID;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        session_start();
        $_SESSION['bootstrapApplicationOID'][0] = $this->_applicationOID;
        $_SESSION['bootstrapApplicationIID'][0] = $this->_applicationIID;
        $_SESSION['bootstrapApplicationSID'][0] = $bootstrapApplicationSID;
        $_SESSION['bootstrapApplicationIID'][$this->_applicationOID] = $this->_applicationOID;
        if (is_a($this->_applicationInstance, 'Nebule\Library\Applications')) {
            $_SESSION['bootstrapApplicationsInstances'][$this->_applicationOID] = serialize($this->_applicationInstance);
        }
        session_write_close();
    }
    
    function bootstrap_displayRouter(): void {
        global $bootstrapBreak, $needFirstSynchronization, $bootstrapInlineDisplay, $bootstrapApplicationIID,
               $bootstrapApplicationOID, $bootstrapApplicationNoPreload;
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        // End of Web page buffering with a buffer erase before display important things.
        echo 'Display test of of erased buffer - must not be prompted!';
        ob_end_clean();

        // Break on first run, many things to do before continue.
        if ($needFirstSynchronization && !$bootstrapInlineDisplay) {
            $this->_metrologyInstance->addLog('load first', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '63d9bc00');
            $this->_displayApplicationFirst();
            return;
        }

        // Break on problems on bootstrap load or on user query.
        if (sizeof($bootstrapBreak) > 0) {
            $this->_metrologyInstance->addLog('load break', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '4abf554b');
            if ($bootstrapInlineDisplay)
                $this->_inlineDisplayOnBreak();
            else
                $this->_displayOnBreak();
            return;
        }

        io_close();

        // Display only server entity if asked.
        // For compatibility and interoperability.
        if (filter_has_var(INPUT_GET, \Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE)
            || filter_has_var(INPUT_POST, \Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE)
        ) {
            $this->_metrologyInstance->addLog('input ' . \Nebule\Bootstrap\LIB_LOCAL_ENTITY_FILE . ' ask display local entity only', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'dcfc2e74');
            $this->_displayLocalEntity();
            return;
        }

        $this->_metrologyInstance->addLog('load application code OID=' . $bootstrapApplicationOID, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'aab236ff');

        if ($bootstrapApplicationIID == '0' || $bootstrapApplicationOID == '0') {
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
        }
//    elseif (isset($bootstrapApplicationInstanceSleep) && $bootstrapApplicationInstanceSleep != '')
//        bootstrap_displaySleepingApplication();
        elseif ($bootstrapApplicationNoPreload)
            $this->_displayNoPreloadApplication();
        else
            $this->_displayPreloadApplication();

        \Nebule\Bootstrap\log_reopen(\Nebule\Bootstrap\BOOTSTRAP_NAME);

        // Sérialise les instances et les sauve dans la session PHP.
        $this->_saveLibraryPOO(); // FIXME à supprimer ?
        $this->_saveApplicationOnSession();
    }


    function _getCurrentBranch(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __FUNCTION__, '1111c0de');

        if ($this->_codeBranchNID != '')
            return;

        // Get code branch on config
        $codeBranchName = $this->_configurationInstance->getOptionAsString('codeBranch');
        if ($codeBranchName == '')
            $codeBranchName = Configuration::OPTIONS_DEFAULT_VALUE['codeBranch'];
        $this->_codeBranchNID = '';

        // Check if it's a name or an OID.
        if (Node::checkNID($codeBranchName)
            && \Nebule\Bootstrap\io_checkNodeHaveLink($codeBranchName)
        ) {
            $this->_codeBranchNID = $codeBranchName;
        } else {
            // Get all RID of code branches
            $codeBranchRID = References::LIB_RID_CODE_BRANCH;
            $bLinks = array();
            $filter = array(
                'bl/rl/req' => 'l',
                'bl/rl/nid1' => $codeBranchRID,
                'bl/rl/nid3' => $codeBranchRID,
                'bl/rl/nid4' => '',
            );
            \Nebule\Bootstrap\lnk_getList($codeBranchRID, $bLinks, $filter, false);
            \Nebule\Bootstrap\blk_filterBySigners($bLinks, $this->_authoritiesInstance->getLocalAuthoritiesID());

            // Get all NID with the name of wanted code branch.
            $codeBranchRID = $this->getNidFromData($codeBranchName);
            $nLinks = array();
            $filter = array(
                'bl/rl/req' => 'l',
                'bl/rl/nid2' => $this->getNidFromData($codeBranchName),
                'bl/rl/nid3' => $this->getNidFromData('nebule/objet/nom'),
                'bl/rl/nid4' => '',
            );
            \Nebule\Bootstrap\lnk_getList($codeBranchRID, $nLinks, $filter, false);
            \Nebule\Bootstrap\blk_filterBySigners($nLinks, $this->_authoritiesInstance->getLocalAuthoritiesID());

            // Latest collision of code branches with the name
            $bl_rc_mod = '0';
            $bl_rc_chr = '0';
            foreach ($bLinks as $bLink) {
                foreach ($nLinks as $nLink) {
                    if ($bLink['bl/rl/nid2'] == $nLink['bl/rl/nid1']
                        && \Nebule\Bootstrap\lnk_dateCompare($bl_rc_mod, $bl_rc_chr, $bLink['bl/rc/mod'], $bLink['bl/rc/chr']) < 0 // FIXME $this->dateCompare()
                    ) {
                        $bl_rc_mod = $bLink['bl/rc/mod'];
                        $bl_rc_chr = $bLink['bl/rc/chr'];
                        $this->_codeBranchNID = $bLink['bl/rl/nid2'];
                    }
                }
            }
        }
        $this->_metrologyInstance->addLog('Current branch : ' . $this->_codeBranchNID, Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '9f1bf579');
    }
}