<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsLinks extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const OBFUSCATE = 'actobflnk';
    const SIGN1 = 'actsiglnk1';
    const SIGN1_OBFUSCATE = 'actsiglnk1o';
    const SIGN2 = 'actsiglnk2';
    const SIGN2_OBFUSCATE = 'actsiglnk2o';
    const SIGN3 = 'actsiglnk3';
    const SIGN3_OBFUSCATE = 'actsiglnk3o';
    const UPLOAD_SIGNED = 'actupsl';
    const UPLOAD_FILE_LINKS = 'actupfl';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionObfuscateLink();

        if ($this->_actionObfuscateLinkInstance !== null
            && $this->_actionObfuscateLinkInstance != ''
            && is_a($this->_actionObfuscateLinkInstance, 'Nebule\Library\LinkRegister')
            && $this->_configurationInstance->getOptionAsBoolean('permitObfuscatedLink')
        )
            $this->_actionObfuscateLink();
    }
    public function specialActions(): void {
        // Vérifie que l'action de chargement de lien soit permise.
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadLink')
            || $this->_unlocked
        ) {
            // Extrait les actions.
            $this->_extractActionSignLink1();
            $this->_extractActionSignLink2();
            $this->_extractActionSignLink3();
            $this->_extractActionUploadLink();
            $this->_extractActionUploadFileLinks();
        }

        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadLink')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
        ) {
            // Lien à signer 1.
            if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance1, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance1, $this->_actionSignLinkInstance1Obfuscate);

            // Lien à signer 2.
            if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance2, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance2, $this->_actionSignLinkInstance2Obfuscate);

            // Lien à signer 3.
            if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupCreateLink')
                && is_a($this->_actionSignLinkInstance3, 'Nebule\Library\LinkRegister')
            )
                $this->_actionSignLink($this->_actionSignLinkInstance3, $this->_actionSignLinkInstance3Obfuscate);

            // Liens pré-signés.
            if ($this->_actionUploadLinkInstance !== null
                && is_a($this->_actionUploadLinkInstance, 'Nebule\Library\LinkRegister')
            )
                $this->_actionUploadLink_DISABLED($this->_actionUploadLinkInstance);

            // Fichier de liens pré-signés.
            if ($this->_actionUploadFileLinks)
                $this->_actionUploadFileLinks();
        }
    }



    protected ?BlocLink $_actionObfuscateLinkInstance = null;
    protected function _extractActionObfuscateLink(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupObfuscateLink'))
            return;

        $this->_metrologyInstance->addLog('extract action obfuscate link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c22677ad');

        $arg = $this->getFilterInput(self::OBFUSCATE);

        if ($arg != '')
            $this->_actionObfuscateLinkInstance = $this->_cacheInstance->newBlockLink($arg);
    }
    protected function _actionObfuscateLink(): void
    {
        if ($this->_actionObfuscateLinkInstance === null
            || !$this->_actionObfuscateLinkInstance->getValid()
            || !$this->_actionObfuscateLinkInstance->getSigned() // FIXME
        )
            return;

        $this->_metrologyInstance->addLog('action obfuscate link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b3bf62a9');

        // On dissimule le lien.
        //$this->_actionObfuscateLinkInstance->obfuscateWrite(); FIXME
    }

    protected string $_actionSignLinkInstance1 = '';
    protected bool $_actionSignLinkInstance1Obfuscate = false;
    protected function _extractActionSignLink1(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 1', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c5415d94');

        $arg = $this->getFilterInput(self::SIGN1);
        $argObfuscate = filter_has_var(INPUT_GET, self::SIGN1_OBFUSCATE);

        if ($arg == '')
            return ;
        $this->_actionSignLinkInstance1 = $arg;
        $this->_actionSignLinkInstance1Obfuscate = $argObfuscate;

    }
    protected function _actionSignLink(string $link, bool $obfuscate = false): void
    {
        if ($this->_unlocked) {
            $this->_metrologyInstance->addLog('action sign link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '8baa9fae');

            $blockLinkInstance = new BlocLink($this->_nebuleInstance, 'new');
            $blockLinkInstance->addLink($link);


            // On cache le lien ? // FIXME
            if ($obfuscate !== false
                && $obfuscate !== true
            )
                $obfuscate = $this->_configurationInstance->getOptionUntyped('defaultObfuscateLinks');
            //...

            $link->signWrite();
        } elseif ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
            || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
        ) {
            $this->_metrologyInstance->addLog('action sign link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'be97740a');

            if ($link->getSigned())
                $link->write();
        }
    }

    protected string $_actionSignLinkInstance2 = '';
    protected bool $_actionSignLinkInstance2Obfuscate = false;
    protected function _extractActionSignLink2(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 2', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'e1059b93');

        $arg = $this->getFilterInput(self::SIGN2);
        $argObfuscate = filter_has_var(INPUT_GET, self::SIGN2_OBFUSCATE);

        if ($arg == '')
            return ;
        $this->_actionSignLinkInstance2 = $arg;
        $this->_actionSignLinkInstance2Obfuscate = $argObfuscate;
    }

    protected string $_actionSignLinkInstance3 = '';
    protected bool $_actionSignLinkInstance3Obfuscate = false;
    protected function _extractActionSignLink3(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupSignLink'))
            return;

        $this->_metrologyInstance->addLog('extract action sign link 3', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'cc145716');

        $arg = $this->getFilterInput(self::SIGN3);
        $argObfuscate = filter_has_var(INPUT_GET, self::SIGN3_OBFUSCATE);

        if ($arg == '')
            return ;
        $this->_actionSignLinkInstance3 = $arg;
        $this->_actionSignLinkInstance3Obfuscate = $argObfuscate;
    }

    protected ?BlocLink $_actionUploadLinkInstance = null;
    protected function _extractActionUploadLink(): void
    {
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadLink')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('extract action upload signed link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '0e682f22');

            $arg = $this->getFilterInput(self::UPLOAD_SIGNED);

            $permitNotCodeMaster = false;
            if ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_unlocked
            )
                $permitNotCodeMaster = true;

            if ($arg != '') {
                $instance = $this->_cacheInstance->newBlockLink($arg);
                if ($instance->getValid()
                    && $instance->getSigned()
                    && ($instance->getSignersEID() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                        || $permitNotCodeMaster
                    )
                )
                    $this->_actionUploadLinkInstance = $instance;
                unset($instance);
            }
        }
    }

    /*protected ?BlocLink $_actionObfuscateLinkInstance = null;
    protected function _extractActionObfuscateLink(): void
    {
        if (!$this->_configurationInstance->checkGroupedBooleanOptions('GroupObfuscateLink'))
            return;

        $this->_metrologyInstance->addLog('extract action obfuscate link', Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'c22677ad');

        $arg = $this->getFilterInput(self::DEFAULT_COMMAND_ACTION_OBFUSCATE_LINK);

        if ($arg != '')
            $this->_actionObfuscateLinkInstance = $this->_cacheInstance->newBlockLink($arg);
    }
    protected function _actionObfuscateLink(): void
    {
        if ($this->_actionObfuscateLinkInstance === null
            || !$this->_actionObfuscateLinkInstance->getValid()
            || !$this->_actionObfuscateLinkInstance->getSigned() // FIXME
        )
            return;

        $this->_metrologyInstance->addLog('action obfuscate link', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'b3bf62a9');

        // On dissimule le lien.
        $this->_actionObfuscateLinkInstance->obfuscateWrite();
    }*/

    protected bool $_actionUploadFileLinks = false;
    protected string $_actionUploadFileLinksName = '';
    protected string $_actionUploadFileLinksSize = '';
    protected string $_actionUploadFileLinksPath = '';
    protected bool $_actionUploadFileLinksError = false;
    protected string $_actionUploadFileLinksErrorMessage = 'Initialisation du transfert.';
    public function getUploadFileSignedLinks(): bool
    {
        return $this->_actionUploadFileLinks;
    }
    protected function _extractActionUploadFileLinks(): void
    {
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupUploadFileLinks')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                || $this->_unlocked
            )
        ) {
            $this->_metrologyInstance->addLog('extract action upload file of signed links', Metrology::LOG_LEVEL_DEBUG, __METHOD__, '00000000');

            // Lit le contenu de la variable _FILE si un fichier est téléchargé.
            if (isset($_FILES[self::UPLOAD_FILE_LINKS]['error'])
                && $_FILES[self::UPLOAD_FILE_LINKS]['error'] == UPLOAD_ERR_OK
                && trim($_FILES[self::UPLOAD_FILE_LINKS]['name']) != ''
            ) {
                // Extraction des méta données du fichier.
                $upname = mb_convert_encoding(strtok(trim((string)filter_var($_FILES[self::UPLOAD_FILE_LINKS]['name'], FILTER_SANITIZE_STRING)), "\n"), 'UTF-8');
                $upsize = $_FILES[self::UPLOAD_FILE_LINKS]['size'];
                $uppath = $_FILES[self::UPLOAD_FILE_LINKS]['tmp_name'];

                // Si le fichier est bien téléchargé.
                if (file_exists($uppath)) {
                    // Si le fichier n'est pas trop gros.
                    if ($upsize <= $this->_configurationInstance->getOptionUntyped('ioReadMaxData')) {
                        // Ecriture des variables.
                        $this->_actionUploadFileLinks = true;
                        $this->_actionUploadFileLinksName = $upname;
                        $this->_actionUploadFileLinksSize = $upsize;
                        $this->_actionUploadFileLinksPath = $uppath;
                    } else {
                        $this->_metrologyInstance->addLog('action _extractActionUploadFileLinks File size too big', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                        $this->_actionUploadFileLinksError = true;
                        $this->_actionUploadFileLinksErrorMessage = 'File size too big';
                    }
                } else {
                    $this->_metrologyInstance->addLog('action _extractActionUploadFileLinks File upload error', Metrology::LOG_LEVEL_ERROR, __METHOD__, '00000000');
                    $this->_actionUploadFileLinksError = true;
                    $this->_actionUploadFileLinksErrorMessage = 'File upload error';
                }
            }
        }
    }
    /**
     * Transfert un fichier de liens pré-signés et les ajoute.
     *
     * Cette fonction est appelée par la fonction specialActions().
     * Elle est utilisée par l'application upload et le module module_upload de l'application sylabe.
     * Le fonctionnement est identique dans ces deux usages même si l'affichage ne le montre pas.
     *
     * La fonction nécessite au minimum les droits :
     *   - permitWrite
     *   - permitWriteLink
     *   - permitUploadLink
     * L'activation de la fonction est ensuite conditionnée par une combination d'autres droits ou facteurs.
     *
     * Si le droit permitPublicUploadCodeAuthoritiesLink est activé :
     *   les liens signés du maître du code sont acceptés ;
     *   les liens des autres entités sont ignorés avec seulement ce droit.
     *
     * Si le droit permitPublicUploadLink est activé :
     *   tous les liens signés sont acceptés ;
     *   les entités signataires doivent exister localement pour la vérification les signatures.
     *
     * Si l'entité en cours est déverrouillée, this->_unlocked :
     *   la réception de liens est prise comme une action légitime ;
     *   les liens signés de toutes les entités sont acceptés ;
     *   les liens non signés sont signés par l'entité en cours.
     * Si un lien est structurellement valide, mais non signé, il est régénéré et signé par l'entité en cours.
     *
     * Les liens ne sont écrit que si leurs signatures sont valides.
     *
     * @return void
     */
    protected function _actionUploadFileLinks(): void
    {
        $this->_metrologyInstance->addLog('action upload file signed links', Metrology::LOG_LEVEL_AUDIT, __METHOD__, '00000000');

        // Ecrit les liens correctement signés.
        $updata = file($this->_actionUploadFileLinksPath);
        $nbLinks = 0; // FIXME unused
        $nbLines = 0; // FIXME unused
        foreach ($updata as $line) {
            if (substr($line, 0, 21) != 'nebule/liens/version/') {
                $nbLines++;
                $instance = $this->_cacheInstance->newBlockLink($line);
                if ($instance->getValid()) {
                    if ($instance->getSigned()
                        && (($instance->getSignersEID() == $this->_authoritiesInstance->getCodeAuthoritiesEID()
                                && $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadCodeAuthoritiesLink')
                            )
                            || $this->_configurationInstance->getOptionAsBoolean('permitPublicUploadLink')
                            || $this->_unlocked
                        )
                    ) {
                        $instance->write();
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('action upload file links - signed link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
                    } elseif ($this->_unlocked) {
                        /*$instance = $this->_cacheInstance->newBlockLink( FIXME
                            '0_'
                            . $this->_entitiesInstance->getCurrentEntityID() . '_'
                            . $instance->getDate() . '_'
                            . $instance->getParsed()['bl/rl/req'] . '_'
                            . $instance->getParsed()['bl/rl/nid1'] . '_'
                            . $instance->getParsed()['bl/rl/nid2'] . '_'
                            . $instance->getParsed()['bl/rl/nid3']
                        );
                        $instance->signWrite();*/
                        $nbLinks++;
                        $this->_metrologyInstance->addLog('action upload file links - unsigned link ' . $instance->getRaw(), Metrology::LOG_LEVEL_NORMAL, __METHOD__, '00000000');
                    }
                }
            }
        }
    }
}