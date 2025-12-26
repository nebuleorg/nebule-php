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
    const UPLOAD_TEXT = 'action_object_upload_text';
    const UPLOAD_TEXT_NAME = 'action_object_upload_text_name';
    const UPLOAD_TEXT_TYPE = 'action_object_upload_text_type';
    const UPLOAD_TEXT_PROTECT = 'action_object_upload_text_protect';
    const UPLOAD_TEXT_OBFUSCATED = 'action_object_upload_text_obf';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionDeleteObject();
        $this->_extractActionProtectObject();
        $this->_extractActionUnprotectObject();
        $this->_extractActionShareProtectObjectToEntity();
        $this->_extractActionShareProtectObjectToGroupOpened();
        $this->_extractActionShareProtectObjectToGroupClosed();
        $this->_extractActionCancelShareProtectObjectToEntity();
        $this->_extractActionSynchronizeObject();
        $this->_extractActionUploadFile();
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
        if ($this->_actionUploadFile)
            $this->_actionUploadFile();
        if ($this->_actionUploadText)
            $this->_actionUploadText();
    }
    public function specialActions(): void {}



    protected bool $_actionDeleteObject = false;
    protected string $_actionDeleteObjectID = '0';
    protected ?Node $_actionDeleteObjectInstance = null;
    protected bool $_actionDeleteObjectForce = false;
    protected bool $_actionDeleteObjectObfuscate = false;
    public function getDeleteObject(): bool
    {
        return $this->_actionDeleteObject;
    }
    public function getDeleteObjectID(): string
    {
        return $this->_actionDeleteObjectID;
    }
    protected function _extractActionDeleteObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupDeleteObject'))
            return;

        $argObject = $this->getFilterInput(self::DELETE, FILTER_FLAG_ENCODE_LOW);
        $argForce = filter_has_var(INPUT_GET, self::DELETE_FORCE);
        $argObfuscate = filter_has_var(INPUT_GET, self::DELETE_OBFUSCATE);

        // Extraction de l'objet à supprimer.
        if (Node::checkNID($argObject)) {
            $this->_actionDeleteObjectID = $argObject;
            $this->_actionDeleteObjectInstance = $this->_cacheInstance->newNode($argObject);
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
    protected function _actionDeleteObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Suppression de l'objet.
        if ($this->_actionDeleteObjectForce)
            $this->_actionDeleteObjectInstance->deleteForceObject();
        else
            $this->_actionDeleteObjectInstance->deleteObject();
    }

    protected ?Node $_actionProtectObjectInstance = null;
    protected function _extractActionProtectObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupProtectObject'))
            return;

        $arg = $this->getFilterInput(self::PROTECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionProtectObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionProtectObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_actionProtectObjectInstance->setProtected();
    }

    protected ?Node $_actionUnprotectObjectInstance = null;
    protected function _extractActionUnprotectObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUnprotectObject'))
            return;

        $arg = $this->getFilterInput(self::UNPROTECT, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionUnprotectObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionUnprotectObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_actionUnprotectObjectInstance->setUnprotected();
    }

    protected string $_actionShareProtectObjectToEntity = '';
    protected function _extractActionShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToEntity'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToEntity = $arg;
    }
    protected function _actionShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($this->_actionShareProtectObjectToEntity);
    }

    protected string $_actionShareProtectObjectToGroupOpened = '';
    protected function _extractActionShareProtectObjectToGroupOpened(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupOpened'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_GROUP_OPENED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupOpened = $arg;
    }
    protected function _actionShareProtectObjectToGroupOpened(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newNode($this->_actionShareProtectObjectToGroupOpened, \Nebule\Library\Cache::TYPE_GROUP);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionShareProtectObjectToGroupClosed = '';
    protected function _extractActionShareProtectObjectToGroupClosed(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupShareProtectObjectToGroupClosed'))
            return;

        $arg = $this->getFilterInput(self::SHARE_PROTECT_TO_GROUP_CLOSED, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionShareProtectObjectToGroupClosed = $arg;
    }
    protected function _actionShareProtectObjectToGroupClosed(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande de protection de l'objet.
        $group = $this->_cacheInstance->newNode($this->_actionShareProtectObjectToGroupClosed, \Nebule\Library\Cache::TYPE_GROUP);
        foreach ($group->getListMembersID('myself') as $id) {
            $this->_nebuleInstance->getCurrentObjectInstance()->shareProtectionTo($id);
        }
        unset($group, $id);
    }

    protected string $_actionCancelShareProtectObjectToEntity = '';
    protected function _extractActionCancelShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupCancelShareProtectObjectToEntity'))
            return;

        $arg = $this->getFilterInput(self::CANCEL_SHARE_PROTECT_TO_ENTITY, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionCancelShareProtectObjectToEntity = $arg;
    }
    protected function _actionCancelShareProtectObjectToEntity(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Demande d'annulation de protection de l'objet.
        $this->_nebuleInstance->getCurrentObjectInstance()->cancelShareProtectionTo($this->_actionCancelShareProtectObjectToEntity);
    }

    protected ?Node $_actionSynchronizeObjectInstance = null;
    public function getSynchronizeObjectInstance(): ?Node
    {
        return $this->_actionSynchronizeObjectInstance;
    }
    protected function _extractActionSynchronizeObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeObject'))
            return;

        $arg = $this->getFilterInput(self::SYNCHRONIZE, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionSynchronizeObjectInstance = $this->_cacheInstance->newNode($arg);
    }
    protected function _actionSynchronizeObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeObjectInstance);

        // Synchronisation.
        $this->_actionSynchronizeObjectInstance->syncObject();
    }

    protected bool $_actionUploadFile = false;
    protected string $_actionUploadFileID = '0';
    protected string $_actionUploadFileName = '';
    protected string $_actionUploadFileExtension = '';
    protected string $_actionUploadFileType = '';
    protected string $_actionUploadFileSize = '';
    protected string $_actionUploadFilePath = '';
    protected bool $_actionUploadFileUpdate = false;
    protected bool $_actionUploadFileProtect = false;
    protected bool $_actionUploadFileObfuscateLinks = false;
    protected bool $_actionUploadFileError = false;
    protected string $_actionUploadFileErrorMessage = 'Initialisation du transfert.';
    public function getUploadObject(): bool
    {
        return $this->_actionUploadFile;
    }
    public function getUploadObjectID(): string
    {
        return $this->_actionUploadFileID;
    }
    public function getUploadObjectError(): bool
    {
        return $this->_actionUploadFileError;
    }
    public function getUploadObjectErrorMessage(): string
    {
        return $this->_actionUploadFileErrorMessage;
    }
    protected function _extractActionUploadFile(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadFile'))
            return;

        $uploadArgName = self::UPLOAD_FILE;
        if (!isset($_FILES[$uploadArgName]))
            return;
        $uploadRawName = $_FILES[$uploadArgName]['name'];
        $uploadError = $_FILES[$uploadArgName]['error'];

        switch ($_FILES[self::UPLOAD_FILE]['error']) {
            case UPLOAD_ERR_OK:
                // Extraction des méta données du fichier.
                $upfname = mb_convert_encoding(strtok(trim((string)filter_var($_FILES[self::UPLOAD_FILE]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upinfo = pathinfo($upfname);
                $upext = $upinfo['extension'];
                $upname = basename($upfname, '.' . $upext);
                $upsize = $_FILES[self::UPLOAD_FILE]['size'];
                $uppath = $_FILES[self::UPLOAD_FILE]['tmp_name'];
                $uptype = '';
                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_configurationInstance->getOptionUntyped('ioReadMaxData')) {
                        // Lit le type mime.
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $uptype = finfo_file($finfo, $uppath);
                        finfo_close($finfo);
                        if ($uptype == 'application/octet-stream') {
                            $uptype = $this->_getFilenameTypeMime("$upname.$upext");
                        }

                        // Extrait les options de téléchargement.
                        $argUpd = filter_has_var(INPUT_POST, self::UPLOAD_FILE_UPDATE);
                        $argPrt = filter_has_var(INPUT_POST, self::UPLOAD_FILE_PROTECT);
                        $argObf = filter_has_var(INPUT_POST, self::UPLOAD_FILE_OBFUSCATED);

                        // Ecriture des variables.
                        $this->_actionUploadFile = true;
                        $this->_actionUploadFileName = $upname;
                        $this->_actionUploadFileExtension = $upext;
                        $this->_actionUploadFileType = $uptype;
                        $this->_actionUploadFileSize = $upsize;
                        $this->_actionUploadFilePath = $uppath;
                        $this->_actionUploadFileUpdate = $argUpd;
                        if ($this->_configurationInstance->getOptionAsBoolean('permitProtectedObject')) {
                            $this->_actionUploadFileProtect = $argPrt;
                        }
                        if ($this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')) {
                            $this->_actionUploadFileObfuscateLinks = $argObf;
                        }
                    } else {
                        $this->_metrologyInstance->addLog('action _extractActionUploadFile ioReadMaxData exeeded', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                        $this->_actionUploadFileError = true;
                        $this->_actionUploadFileErrorMessage = 'Le fichier dépasse la taille limite de transfert.';
                    }
                } else {
                    $this->_metrologyInstance->addLog('action _extractActionUploadFile upload error', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                    $this->_actionUploadFileError = true;
                    $this->_actionUploadFileErrorMessage = "No uploaded file.";
                }
                unset($upfname, $upinfo, $upext, $upname, $upsize, $uppath, $uptype);
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_INI_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_FORM_SIZE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_PARTIAL', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_FILE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_NO_TMP_DIR', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_CANT_WRITE', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->_metrologyInstance->addLog('action _extractActionUploadFile upload PHP error UPLOAD_ERR_EXTENSION', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                $this->_actionUploadFileError = true;
                $this->_actionUploadFileErrorMessage = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop.";
                break;
        }
    }
    protected function _getFilenameTypeMime(string $f): string
    {
        $m = '/etc/mime.types'; // Chemin du fichier pour trouver le type mime.
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
    protected function _actionUploadFile(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Lit le contenu du fichier.
        $data = file_get_contents($_FILES[self::UPLOAD_FILE]['tmp_name']);

        // Ecrit le contenu dans l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $data, $this->_actionUploadFileProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('action _actionUploadFile cant create object instance', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        unset($data);
        if ($id == '0') {
            $this->_metrologyInstance->addLog('action _actionUploadFile cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
            return;
        }
        $this->_actionUploadFileID = $id;

        // Définition de la date et le signataire.
        $date = date(DATE_ATOM);
        $signer = $this->_entitiesInstance->getGhostEntityEID();

        // Création du type mime.
        $instance->setType($this->_actionUploadFileType);

        // Crée l'objet du nom.
        $instance->setName($this->_actionUploadFileName);

        // Crée l'objet de l'extension.
        $instance->setSuffixName($this->_actionUploadFileExtension);

        // Si mise à jour de l'objet en cours.
        if ($this->_actionUploadFileUpdate) {
            $instanceBL = new \Nebule\Library\BlocLink($this->_nebuleInstance, 'new');
            $instanceBL->addLink('u>' . $this->_applicationInstance->getCurrentObjectID() . '>' . $id);
            $instanceBL->signWrite($this->_entitiesInstance->getGhostEntityInstance(), '');
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
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
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
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        // Crée l'instance de l'objet.
        $instance = new Node($this->_nebuleInstance, '0', $this->_actionUploadTextContent, $this->_actionUploadTextProtect);
        if ($instance === false) {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object instance', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'instance de l'objet n'a pas pu être créée.";
            return;
        }

        // Lit l'ID.
        $id = $instance->getID();
        if ($id == '0') {
            $this->_metrologyInstance->addLog('action _actionUploadText cant create object', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
            $this->_actionUploadFileError = true;
            $this->_actionUploadFileErrorMessage = "L'objet n'a pas pu être créé.";
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