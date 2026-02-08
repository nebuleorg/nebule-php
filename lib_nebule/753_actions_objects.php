<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on objects.
 * This class must not be used directly but via the entry point Actions->getInstanceActionsObjects().
 * TODO must be refactored!
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsObjects extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const DELETE = 'action_object_del';
    const DELETE_FORCE = 'action_object_del_force';
    const DELETE_OBFUSCATE = 'action_object_del_obf';
    const PROTECT = 'action_object_protect';
    const UNPROTECT = 'action_object_unprotect';
    const SHARE_PROTECT_TO_ENTITY = 'action_object_share_protect_ent';
    const SHARE_PROTECT_TO_GROUP_OPENED = 'action_object_share_protect_group_open';
    const SHARE_PROTECT_TO_GROUP_CLOSED = 'action_object_share_protect_group_close';
    const CANCEL_SHARE_PROTECT_TO_ENTITY = 'action_object_unshare_protect_ent';
    const SYNCHRONIZE = 'action_object_synchro';
    const UPLOAD_FILE = 'action_object_upload_file';
    const UPLOAD_FILE_UPDATE = 'action_object_upload_file_update';
    const UPLOAD_FILE_PROTECT = 'action_object_upload_file_protect';
    const UPLOAD_FILE_OBFUSCATED = 'action_object_upload_file_obf';
    const UPLOAD_FILE_GROUP = 'action_object_upload_file_group';
    const UPLOAD_FILE_GROUP_TYPED = 'action_object_upload_file_group_typed';
    const UPLOAD_TEXT = 'action_object_upload_text';
    const UPLOAD_TEXT_NAME = 'action_object_upload_text_name';
    const UPLOAD_TEXT_TYPE = 'action_object_upload_text_type';
    const UPLOAD_TEXT_PROTECT = 'action_object_upload_text_protect';
    const UPLOAD_TEXT_OBFUSCATED = 'action_object_upload_text_obf';



    public function initialisation(): void {}
    public function genericActions(): void {
        if ($this->_nebuleInstance->getHaveInput(self::UPLOAD_FILE))
            $this->_uploadFile();



        // FIXME
        $this->_extractActionDeleteObject();
        $this->_extractActionProtectObject();
        $this->_extractActionUnprotectObject();
        $this->_extractActionShareProtectObjectToEntity();
        $this->_extractActionShareProtectObjectToGroupOpened();
        $this->_extractActionShareProtectObjectToGroupClosed();
        $this->_extractActionCancelShareProtectObjectToEntity();
        $this->_extractActionSynchronizeObject();
        //$this->_extractActionUploadFile();
        $this->_extractActionUploadText();

        if ($this->_actionDeleteObject
            && is_a($this->_actionDeleteObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionDeleteObject();

        if ($this->_actionProtectObjectInstance != ''
            && is_a($this->_actionProtectObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionProtectObject();
        if ($this->_actionUnprotectObjectInstance != ''
            && is_a($this->_actionUnprotectObjectInstance, 'Nebule\Library\Node')
        )
            $this->_actionUnprotectObject();
        if ($this->_actionShareProtectObjectToEntity != '')
            $this->_actionShareProtectObjectToEntity();
        if ($this->_actionShareProtectObjectToGroupOpened != '')
            $this->_actionShareProtectObjectToGroupOpened();
        if ($this->_actionShareProtectObjectToGroupClosed != '')
            $this->_actionShareProtectObjectToGroupClosed();
        if ($this->_actionCancelShareProtectObjectToEntity != '')
            $this->_actionCancelShareProtectObjectToEntity();

        if ($this->_actionSynchronizeObjectInstance != '')
            $this->_actionSynchronizeObject();
        //if ($this->_uploadFile)
        //    $this->_actionUploadFile();
        if ($this->_actionUploadText)
            $this->_actionUploadText();
    }
    public function specialActions(): void {}



    protected bool $_actionDeleteObject = false;
    protected string $_actionDeleteObjectID = '0';
    protected ?Node $_actionDeleteObjectInstance = null;
    protected bool $_actionDeleteObjectForce = false;
    protected bool $_actionDeleteObjectObfuscate = false;
    public function getDeleteObject(): bool { return $this->_actionDeleteObject; }
    public function getDeleteObjectID(): string { return $this->_actionDeleteObjectID; }
    protected function _extractActionDeleteObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteObject'))
            return;

        $argObject = $this->getFilterInput(self::DELETE, FILTER_FLAG_ENCODE_LOW);
        $argForce = filter_has_var(INPUT_GET, self::DELETE_FORCE);
        $argObfuscate = filter_has_var(INPUT_GET, self::DELETE_OBFUSCATE);

        // Extraction de l'objet à supprimer.
        if (Node::checkNID($argObject)) {
            $this->_actionDeleteObjectID = $argObject;
            $this->_actionDeleteObjectInstance = $this->_cacheInstance->newNodeByType($argObject);
            $this->_actionDeleteObject = true;
        }

        // Extraction si la suppression doit être forcée.
        if ($argForce)
            $this->_actionDeleteObjectForce = true;

        // Extraction si la suppression doit être cachée.
        if ($argObfuscate
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionDeleteObjectObfuscate = true;
    }
    protected function _actionDeleteObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Suppression de l'objet.
        if ($this->_actionDeleteObjectForce)
            $this->_actionDeleteObjectInstance->deleteForceObject();
        else
            $this->_actionDeleteObjectInstance->deleteObject();
    }

    protected ?Node $_actionProtectObjectInstance = null;
    protected function _extractActionProtectObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupProtectObject'))
            return;

        $arg = $this->getFilterInput(self::PROTECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionProtectObjectInstance = $this->_cacheInstance->newNodeByType($arg);
    }
    protected function _actionProtectObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_actionProtectObjectInstance->setProtected();
    }

    protected ?Node $_actionUnprotectObjectInstance = null;
    protected function _extractActionUnprotectObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUnprotectObject'))
            return;

        $arg = $this->getFilterInput(self::UNPROTECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionUnprotectObjectInstance = $this->_cacheInstance->newNodeByType($arg);
    }
    protected function _actionUnprotectObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_actionUnprotectObjectInstance->setUnprotected();
    }

    protected string $_actionShareProtectObjectToEntity = '';
    protected function _extractActionShareProtectObjectToEntity(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToEntity'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToEntity = $arg;
    }
    protected function _actionShareProtectObjectToEntity(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($this->_actionShareProtectObjectToEntity);
    }

    protected string $_actionShareProtectObjectToGroupOpened = '';
    protected function _extractActionShareProtectObjectToGroupOpened(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupOpened'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_GROUP_OPENED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupOpened = $arg;
    }
    protected function _actionShareProtectObjectToGroupOpened(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupOpened);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionShareProtectObjectToGroupClosed = '';
    protected function _extractActionShareProtectObjectToGroupClosed(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupClosed'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_GROUP_CLOSED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupClosed = $arg;
    }
    protected function _actionShareProtectObjectToGroupClosed(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newGroup($this->_actionShareProtectObjectToGroupClosed);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionCancelShareProtectObjectToEntity = '';
    protected function _extractActionCancelShareProtectObjectToEntity(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCancelShareProtectObjectToEntity'))
            return;

        $arg = $this->getFilterInput(self::CANCEL_SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionCancelShareProtectObjectToEntity = $arg;
    }
    protected function _actionCancelShareProtectObjectToEntity(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande d'annulation de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->cancelShareProtectionTo($this->_actionCancelShareProtectObjectToEntity);
    }

    protected ?Node $_actionSynchronizeObjectInstance = null;
    public function getSynchronizeObjectInstance(): ?Node { return $this->_actionSynchronizeObjectInstance; }
    protected function _extractActionSynchronizeObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeObject'))
            return;

        $arg = $this->getFilterInput(self::SYNCHRONIZE, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectInstance = $this->_cacheInstance->newNodeByType($arg);
    }
    protected function _actionSynchronizeObject(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectInstance->syncObject();
    }

    protected bool $_uploadFile = false;
    protected string $_uploadFileID = '0';
    protected string $_uploadFileName = '';
    protected string $_uploadFileExtension = '';
    protected string $_uploadFileType = '';
    protected int $_uploadFileSize = 0;
    protected string $_uploadFilePath = '';
    protected bool $_uploadFileUpdate = false;
    protected bool $_uploadFileProtect = false;
    protected bool $_uploadFileObfuscate = false;
    protected bool $_uploadFileObfuscateLink = false;
    protected string $_uploadFileGroup = '';
    protected string $_uploadFileGroupTyped = '';
    protected string $_uploadFileContext = '';
    protected bool $_uploadFileError = false;
    protected string $_uploadFileErrorMessage = 'Initialisation du transfert.';
    public function getUploadObject(): bool { return $this->_uploadFile; }
    public function getUploadObjectID(): string { return $this->_uploadFileID; }
    public function getUploadObjectError(): bool { return $this->_uploadFileError; }
    public function getUploadObjectErrorMessage(): string { return $this->_uploadFileErrorMessage; }
    protected function _getFilenameTypeMime(string $f): string {
        $m = '/etc/mime.types';
        $e = substr(strrchr($f, '.'), 1);
        if (empty($e))
            return 'application/octet-stream';
        $r = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($e\s)/i";
        $ls = file($m);
        foreach ($ls as $l) {
            if (substr($l, 0, 1) == '#')
                continue;
            $l = rtrim($l) . ' ';
            if (!preg_match($r, $l, $m))
                continue;
            return $m[1];
        }
        return 'application/octet-stream';
    }
    protected function _uploadFile(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadFile')) {
            $this->_metrologyInstance->addLog('unauthorised to upload file', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c063ad79');
            return;
        }
        if (!isset($_FILES[self::UPLOAD_FILE]))
            return;
        $this->_uploadFile = true;

        try {
            switch ($_FILES[self::UPLOAD_FILE]['error']) {
                case UPLOAD_ERR_OK:
                    $filename = strtok(trim((string)filter_var($_FILES[self::UPLOAD_FILE]['name'], FILTER_SANITIZE_STRING)), "\n");
                    if (extension_loaded('mbstring'))
                        $filename = mb_convert_encoding($filename, 'UTF-8');
                    else
                        $this->_metrologyInstance->addLog('mbstring extension not installed or activated!', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'ce685199');
                    $fileInfo = pathinfo($filename);
                    $this->_uploadFileExtension = $fileInfo['extension'];
                    $this->_uploadFileName = basename($filename, '.' . $this->_uploadFileExtension);
                    $this->_uploadFileSize = $_FILES[self::UPLOAD_FILE]['size'];
                    $this->_uploadFilePath = $_FILES[self::UPLOAD_FILE]['tmp_name'];
                    if (file_exists($this->_uploadFilePath)) {
                        $ioReadMaxUpload = $this->_configurationInstance->getOptionAsInteger('ioReadMaxUpload');
                        if ($this->_uploadFileSize <= $ioReadMaxUpload) {
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $this->_uploadFileType = finfo_file($finfo, $this->_uploadFilePath);
                            finfo_close($finfo);
                            if ($this->_uploadFileType == 'application/octet-stream') {
                                $this->_uploadFileType = $this->_getFilenameTypeMime("$this->_uploadFileName.$this->_uploadFileExtension");
                            }

                            $this->_uploadFileObfuscate = ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink') && $this->getFilterInput(self::UPLOAD_FILE_OBFUSCATED) == 'y');
                            $this->_uploadFileProtect = ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject') && $this->getFilterInput(self::UPLOAD_FILE_PROTECT) == 'y');
                            $this->_uploadFileUpdate = $this->getHaveInput(self::UPLOAD_FILE_UPDATE);
                            $this->_uploadFileGroup = $this->getFilterInput(self::UPLOAD_FILE_GROUP, FILTER_FLAG_NO_ENCODE_QUOTES);
                            $this->_uploadFileGroupTyped = $this->getFilterInput(self::UPLOAD_FILE_GROUP_TYPED, FILTER_FLAG_NO_ENCODE_QUOTES);

                            $data = file_get_contents($_FILES[self::UPLOAD_FILE]['tmp_name']);
                            $instance = new Node($this->_nebuleInstance, '0');
                            $instance->setWriteContent($data, $this->_uploadFileProtect, $this->_uploadFileObfuscate);
                            unset($data);
                            $this->_metrologyInstance->addLog('upload file name=' . $filename . ' size=' . (string)$this->_uploadFileSize, Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'd6d9db77');
                            if ($instance->getID() == '0') {
                                $this->_metrologyInstance->addLog('cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '9a0b2492');
                                $this->_uploadFileError = true;
                                $this->_uploadFileErrorMessage = '::9a0b2492';
                                return;
                            }
                            $this->_uploadFileID = $instance->getID();
                            $this->_metrologyInstance->addLog('upload file oid=' . $this->_uploadFileID, Metrology::LOG_LEVEL_AUDIT, __METHOD__, '0ac7ff91');
                            $instance->setType($this->_uploadFileType, $this->_uploadFileProtect, $this->_uploadFileObfuscate);
                            $instance->setName($this->_uploadFileName, $this->_uploadFileProtect, $this->_uploadFileObfuscate);
                            $instance->setSuffixName($this->_uploadFileExtension, $this->_uploadFileProtect, $this->_uploadFileObfuscate);
                            if ($this->_uploadFileUpdate) {
                                $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
                                $instanceBL->addLink('u>' . $this->_applicationInstance->getCurrentObjectID() . '>' . $instance->getID());
                                $instanceBL->signWrite($this->_entitiesInstance->getGhostEntityInstance(), '');
                            }
                            if ($this->_uploadFileGroup != '' && \Nebule\Library\Node::checkNID($this->_uploadFileGroup)) {
                                $group = $this->_cacheInstance->newGroup($this->_uploadFileGroup);
                                if ($this->_uploadFileGroupTyped == '')
                                    $group->setAsMemberNID($this->_uploadFileID, $this->_uploadFileObfuscate);
                                else
                                    $group->setAsTypedMemberNID($this->_uploadFileID, $this->_uploadFileGroupTyped, $this->_uploadFileObfuscate);
                            }
                        } else {
                            $this->_metrologyInstance->addLog('ioReadMaxData exceeded '  .$this->_uploadFileSize . '/' . $ioReadMaxUpload, Metrology::LOG_LEVEL_ERROR, __METHOD__, '7e720681');
                            $this->_uploadFileError = true;
                            $this->_uploadFileErrorMessage = '::7e720681';
                        }
                    } else {
                        $this->_metrologyInstance->addLog('upload error', Metrology::LOG_LEVEL_ERROR, __METHOD__, '0a2485f6');
                        $this->_uploadFileError = true;
                        $this->_uploadFileErrorMessage = '::0a2485f6';
                    }
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_INI_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '49eedd04');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::49eedd04';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_FORM_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e3a3864c');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::e3a3864c';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_PARTIAL', Metrology::LOG_LEVEL_ERROR, __METHOD__, '4555fd63');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::4555fd63';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_NO_FILE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '27d21ac9');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::27d21ac9';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_NO_TMP_DIR', Metrology::LOG_LEVEL_ERROR, __METHOD__, '5e464992');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::5e464992';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_CANT_WRITE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '3ab22f3a');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::3ab22f3a';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->_metrologyInstance->addLog('upload PHP error UPLOAD_ERR_EXTENSION', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'e3c9845c');
                    $this->_uploadFileError = true;
                    $this->_uploadFileErrorMessage = '::e3c9845c';
                    break;
            }
        } catch (\Throwable $e) {
            $this->_metrologyInstance->addLog('upload file error ('  . $e->getCode() . ') : ' . $e->getFile()
                . '('  . $e->getLine() . ') : '  . $e->getMessage() . "\n"
                . $e->getTraceAsString(), Metrology::LOG_LEVEL_ERROR, __METHOD__, '7e720681');
        }
    }

    protected bool $_actionUploadText = false;
    protected string $_actionUploadTextName = '';
    protected string $_actionUploadTextType = '';
    protected string $_actionUploadTextContent = '';
    protected string $_actionUploadTextID = '0';
    protected bool $_actionUploadTextProtect = false;
    protected bool $_actionUploadTextObfuscateLinks = false;
    protected bool $_actionUploadTextError = false;
    protected string $_actionUploadTextErrorMessage = 'Initialisation du transfert.';
    public function getUploadText(): bool { return $this->_actionUploadText; }
    public function getUploadTextName(): string { return $this->_actionUploadTextName; }
    public function getUploadTextID(): string { return $this->_actionUploadTextID; }
    public function getUploadTextError(): bool { return $this->_actionUploadTextError; }
    public function getUploadTextErrorMessage(): string { return $this->_actionUploadTextErrorMessage; }
    protected function _extractActionUploadText(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadText'))
            return;

        $arg = filter_has_var(INPUT_POST, self::UPLOAD_TEXT);

        // Extraction du lien et stockage pour traitement.
        if ($arg !== false) {
            $argText = $this->getFilterInput(self::UPLOAD_TEXT, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argName = $this->getFilterInput(self::UPLOAD_TEXT_NAME, FILTER_FLAG_NO_ENCODE_QUOTES);
            $argType = $this->getFilterInput(self::UPLOAD_TEXT_TYPE, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Extrait les options de téléchargement.
            $argPrt = filter_has_var(INPUT_POST, self::UPLOAD_TEXT_PROTECT);
            $argObf = filter_has_var(INPUT_POST, self::UPLOAD_TEXT_OBFUSCATED);

            if (strlen($argText) != 0) {
                $this->_actionUploadText = true;
                $this->_actionUploadTextContent = $argText;
                $this->_actionUploadTextName = $argName;
                if ($argType != '')
                    $this->_actionUploadTextType = $argType;
                else
                    $this->_actionUploadTextType = References::REFERENCE_OBJECT_TEXT;

                if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject'))
                    $this->_actionUploadTextProtect = $argPrt;
                if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink'))
                    $this->_actionUploadTextObfuscateLinks = $argObf;
            }
        }
    }
    protected function _actionUploadText(): void {
//        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Crée l'instance de l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $this->_actionUploadTextContent, $this->_actionUploadTextProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object instance', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_uploadFileError = true;
            $this->_uploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        if ($id == '0') {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_uploadFileError = true;
            $this->_uploadFileErrorMessage = "L'objet n'a pas pu être créé.";
            return;
        }
        $this->_actionUploadTextID = $id;

        // Définition de la date et du signataire.
        //$signer	= $this->_nebuleInstance->getCurrentEntity();
        //$date = date(DATE_ATOM);

        $instance->setType($this->_actionUploadTextType);
        $instance->setName($this->_actionUploadTextName);

        unset($id, $instance);
    }

}