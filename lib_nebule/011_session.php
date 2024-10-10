<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Recovery entities class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Session extends Functions
{
    private bool $_flushCache = false;

    protected function _initialisation()
    {
        $this->_metrologyInstance->addLog('instancing class Session', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '5a444198');
    }

    /**
     * Lit la valeur d'une option dans la session php.
     * Si l'option n'est pas renseignée, retourne false.
     *
     * @param string $name
     * @return int|string|bool|null|array
     */
    public function getSessionStore(string $name)
    {
        session_start();

        if ($name == ''
            || $this->_flushCache
            || !$this->_configurationInstance->getOptionAsBoolean('permitSessionOptions')
            || !isset($_SESSION['Option'][$name])
        ) {
            session_write_close();
            return false;
        }
        $val = $_SESSION['Option'][$name];

        session_write_close();

        return $val;
    }

    /**
     * Ecrit la valeur d'une option dans la session php.
     *
     * @param string $name
     * @param int|string|bool|null|array $content
     * @return boolean
     */
    public function setSessionStore(string $name, $content): bool
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
    private function _getSessionBuffer(string $name)
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
     * @param string $name
     * @param int|string|bool|null $content
     * @return boolean
     */
    private function _setSessionBuffer(string $name, $content): bool
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

    /**
     * Re-sauvegarde les instances des certains objets avant la sauvegarde du cache vers le buffer de session.
     * Ces objets sont potentiellement modifiés depuis leur première instanciation.
     * FIXME
     *
     * @return void
     */
    private function _saveCurrentsObjectsOnSessionBuffer(): void
    {
        $this->setSessionStore('nebuleHostEntityInstance', serialize($this->_entitiesInstance->getInstanceEntityInstance()));
        $this->setSessionStore('nebulePublicEntityInstance', serialize($this->_entitiesInstance->getCurrentEntityInstance()));
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
