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
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions') // FIXME this->_configurationInstance = null on wake up.
            || !isset($_SESSION['Option'][$name])
        ) {
            session_write_close();
            return false;
        }
        $val = $_SESSION['Option'][$name];

        session_write_close();

        return $val;
    }

    public function getSessionStoreAsSting(string $name): ?string
    {
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
            || !isset($_SESSION['Option'][$name])
        )
            $val = null;
        else
            $val = $_SESSION['Option'][$name];

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
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Option'][$name] = $content;
        session_write_close();
        return true;
    }

    public function setSessionStoreAsString(string $name, string $content): bool
    {
        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
        )
            return false;

        session_start();
        $_SESSION['Option'][$name] = $content;
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
        unset($_SESSION['Option']);
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

    public function setSessionHostEntity(?Node $instance): void {
        if ($instance !== null)
            $this->setSessionStore('nebuleHostEntityInstance', serialize($instance));
    }

    public function getSessionHostEntity(): ?Node {
        $this->_metrologyInstance->addLog('get host entity ' . $this->getSessionStoreAsSting('nebuleHostEntity'), Metrology::LOG_LEVEL_DEBUG, __METHOD__, '331c4f70');
        return $this->_getSessionEntity('nebuleHostEntityInstance');
    }

    public function setSessionCurrentEntity(?Node $instance): void {
        if ($instance !== null)
            $this->setSessionStore('nebulePublicEntityInstance', serialize($instance));
    }

    public function getSessionCurrentEntity(): ?Node {
        return $this->_getSessionEntity('nebulePublicEntityInstance');
    }

    private function _getSessionEntity(string $name): ?Node {
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
            $instance = unserialize($_SESSION['Option'][$name]);
            $instance->setEnvironment($this->_nebuleInstance);
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
/*        if ($name == ''
            || $this->_flushCache
            || !$this->_configuration->getOption('permitSessionBuffer')
        )
            return false;

        session_start();
        if (isset($_SESSION['Buffer'][$name]))
            unset($_SESSION['Buffer'][$name]);
        session_write_close();*/
        return true;
    }
}
