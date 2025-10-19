<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on marks for links and objects.
 * TODO must be refactored!
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsMarks extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const MARK = 'actmarkobj';
    const UNMARK = 'actunmarkobj';
    const UNMARK_ALL = 'actunmarkallobj';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionMarkObject();
        $this->_extractActionUnmarkObject();
        $this->_extractActionUnmarkAllObjects();

        if ($this->_actionMarkObject != '')
            $this->_actionMarkObject();
        if ($this->_actionUnmarkObject != '')
            $this->_actionUnmarkObject();
        if ($this->_actionUnmarkAllObjects)
            $this->_actionUnmarkAllObjects();
    }
    public function specialActions(): void {}



    protected string $_actionMarkObject = '';
    protected function _extractActionMarkObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $arg = $this->getFilterInput(self::MARK, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionMarkObject = $arg;
    }
    protected function _actionMarkObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_applicationInstance->setMarkObject($this->_actionMarkObject);
    }

    protected string $_actionUnmarkObject = '';
    protected function _extractActionUnmarkObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $arg = $this->getFilterInput(self::UNMARK, FILTER_FLAG_ENCODE_LOW);

        if (Node::checkNID($arg))
            $this->_actionUnmarkObject = $arg;
    }
    protected function _actionUnmarkObject(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_applicationInstance->setUnmarkObject($this->_actionUnmarkObject);
    }

    protected bool $_actionUnmarkAllObjects = false;
    protected function _extractActionUnmarkAllObjects(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $arg = filter_has_var(INPUT_GET, self::UNMARK_ALL);

        if ($arg !== false)
            $this->_actionUnmarkAllObjects = true;
    }
    protected function _actionUnmarkAllObjects(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        $this->_applicationInstance->setUnmarkAllObjects();
    }

}