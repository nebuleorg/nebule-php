<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Recovery entities class for the nebule library.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Session extends Functions
{
    const SESSION_SAVED_VARS = array(
        '_flushCache',
    );

    private bool $_flushCache = false;

    /*protected function _initialisation(): void
    {
        $this->_metrologyInstance->addLog('instancing class Session', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '5a444198');
    }*/

    /**
     * Lit la valeur d'une option dans la session php.
     * Si l'option n'est pas renseignée, retourne false.
     *
     * @param string $name
     * @return int|string|bool|null|array
     */
    public function getSessionStore(string $name): array|bool|int|string|null
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions') // FIXME this->_configurationInstance = null on wake up.
            || !isset($_SESSION['Options'][$name])
        ) {
            session_write_close();
            return false;
        }
        $val = $_SESSION['Options'][$name];

        session_write_close();

        return $val;
    }

    public function getSessionStoreAsString(string $name): string
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
            || !isset($_SESSION['Options'][$name])
        )
            $val = '';
        else
            $val = $_SESSION['Options'][$name];

        session_write_close();

        return $val;
    }

    public function getSessionStoreAsArray(string $name): array
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
            || !isset($_SESSION['Options'][$name])
        )
            $val = array();
        else
            $val = $_SESSION['Options'][$name];

        session_write_close();

        return $val;
    }

    /**
     * Ecrit la valeur d'une option dans la session php.
     *
     * @param string                     $name
     * @param int|bool|array|string|null $content
     * @return boolean
     */
    public function setSessionStore(string $name, int|bool|array|string|null $content): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Options'][$name] = $content;
        session_write_close();
        return true;
    }

    public function setSessionStoreAsString(string $name, string $content): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Options'][$name] = $content;
        session_write_close();
        return true;
    }

    public function setSessionStoreAsArray(string $name, array $content): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Options'][$name] = $content;
        session_write_close();
        return true;
    }

    /**
     * Vide les options dans la session php.
     * FIXME
     *
     * @return boolean
     */
    private function _flushSessionStore(): bool
    {
        $this->_metrologyInstance->addLog('Flush session store', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '1b83a0d1');
        session_start();
        unset($_SESSION['Options']);
        session_write_close();
        return true;
    }

    /**
     * Lit la valeur d'un contenu mémorisé dans la session php.
     * Si le contenu mémorisé n'est pas renseigné, retourne false.
     * FIXME
     *
     * @param string $name
     * @return int|string|bool|null
     */
    private function _getSessionBuffer(string $name): bool|int|string|null
    {
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
            || !isset($_SESSION['Buffer'][$name])
        ) {
            session_write_close();
            return false;
        }

        $val = unserialize($_SESSION['Buffer'][$name]);
        session_write_close();
        return $val;
    }

    /**
     * Ecrit la valeur d'un contenu mémorisé dans la session php.
     * Le nombre de contenus mémorisés n'est pas comptabilisé par cette fonction.
     * FIXME
     *
     * @param string               $name
     * @param bool|int|string|null $content
     * @return boolean
     */
    private function _setSessionBuffer(string $name, bool|int|string|null $content): bool
    {
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
        )
            return false;

        session_start();
        $_SESSION['Buffer'][$name] = serialize($content);
        session_write_close();
        return true;
    }

    public function setSessionStoreAsEntity(string $name, ?Entity $instance): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($instance !== null)
            $this->setSessionStore($name, serialize($instance));
    }

    public function getSessionStoreAsEntity(string $name): ?Entity {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $instance = $this->_getEntityFromSession($name);
        if (!$instance instanceof Entity)
            $instance = new Entity($this->_nebuleInstance, '0');
        $this->_metrologyInstance->addLog('get entity for ' . $name . ' from session EID=' . $instance->getID(), Metrology::LOG_LEVEL_AUDIT, __METHOD__, '19f3e422');
        return $instance;
    }

    private function _getEntityFromSession(string $name): ?Entity {
        session_start();
        if ($this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionBuffer')
            || !isset($_SESSION['Buffer'][$name])
            || !is_string($_SESSION['Buffer'][$name])
        ) {
            session_write_close();
            return null;
        }
        try {
            $instance = unserialize($_SESSION['Options'][$name]);
            $instance->setEnvironmentLibrary($this->_nebuleInstance);
            $instance->initialisation();
        } catch (\Exception $e) {
            $this->_metrologyInstance->addLog('unable to restore entity ' . $name . ' from session', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b6dc60cc');
            return null;
        }
        session_write_close();
        return $instance;
    }

    /**
     * Supprime un contenu mémorisé dans la session php.
     * FIXME
     *
     * Fonction désactivée !
     *
     * @param string $name
     * @return boolean
     */
    public function unsetSessionBuffer(string $name): bool
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
/*        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOptionAsString('permitSessionBuffer')
        )
            return false;

        session_start();
        if (isset($_SESSION['Buffer'][$name]))
            unset($_SESSION['Buffer'][$name]);
        session_write_close();*/
        return true;
    }
}
