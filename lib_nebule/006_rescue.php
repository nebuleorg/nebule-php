<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Rescue class for the nebule library.
 * Do not serialize on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Rescue extends Functions
{
    protected function _initialisation(): void
    {
        $this->_findModeRescue();
    }

    private bool $_modeRescue = false;

    private function _findModeRescue(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('modeRescue')
            || ($this->_configurationInstance->getOptionAsBoolean('permitOnlineRescue')
                && (filter_has_var(INPUT_GET, References::COMMAND_RESCUE)
                    || filter_has_var(INPUT_POST, References::COMMAND_RESCUE)
                )
            )
        ) {
            $this->_metrologyInstance->addLog('rescue asked by user input (' . References::COMMAND_RESCUE . ')', Metrology::LOG_LEVEL_AUDIT, __METHOD__, 'feb40da2');
            $this->_modeRescue = true;
        }
    }

    public function getModeRescue(): bool
    {
        return $this->_modeRescue;
    }
}
