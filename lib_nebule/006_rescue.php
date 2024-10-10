<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Rescue class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Rescue extends Functions
{
    protected function _initialisation()
    {
        $this->_findModeRescue();
        $this->_metrologyInstance->addLog('instancing class Rescue', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, 'de62afce');
    }

    /**
     * Variable de mode de récupération.
     *
     * @var boolean
     */
    private bool $_modeRescue = false;

    /**
     * Extrait si on est en mode de récupération.
     *
     * @return void
     */
    private function _findModeRescue(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('modeRescue')
            || ($this->_configurationInstance->getOptionAsBoolean('permitOnlineRescue')
                && (filter_has_var(INPUT_GET, References::COMMAND_RESCUE)
                    || filter_has_var(INPUT_POST, References::COMMAND_RESCUE)
                )
            )
        )
            $this->_modeRescue = true;
    }

    /**
     * Retourne si le mode de récupération est activé.
     * @return boolean
     */
    public function getModeRescue(): bool
    {
        return $this->_modeRescue;
    }
}
