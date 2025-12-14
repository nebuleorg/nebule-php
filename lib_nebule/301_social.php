<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Top class to manage social interacts with links for the current entity.
 * Must be serialized on PHP session with nebule class.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Social extends Functions implements SocialInterface
{
    const DEFAULT_CLASS = 'authority';

    private ?SocialInterface $_defaultInstance = null;

    public function __toString(): string { return self::TYPE; }

    protected function _initialisation(): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $myClass = get_class($this);
        $size = strlen($myClass);
        $list = get_declared_classes();
        foreach ($list as $class) {
            if (substr($class, 0, $size) == $myClass && $class != $myClass) {
                $this->_metrologyInstance->addLog('add class ' . $class, Metrology::LOG_LEVEL_DEBUG, __METHOD__, 'aa8a205b');
                $this->_initSubInstance($class);
            }
        }
        $this->_defaultInstance = $this->_getDefaultSubInstance('socialLibrary');
    }

    /**
     * Gère le classement social des liens.
     *
     * @param array  $links
     * @param string $socialClass
     * @return void
     */
    public function arraySocialFilter(array &$links, string $socialClass = ''): void {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($socialClass != '' && isset($this->_listClasses[$socialClass]) && isset($this->_listInstances[$socialClass]))
            $this->_listInstances[$socialClass]->arraySocialFilter($links, $socialClass);
        elseif (is_a($this->_defaultInstance, 'SocialInterface'))
            $this->_defaultInstance->arraySocialFilter($links, '');
        else
            $this->_metrologyInstance->addLog('error no default social class', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c04e8d5b');
    }

    /**
     * Calcul le score social d'un lien.
     *
     * @param LinkRegister $link
     * @param string       $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        $result = 0.0;
        if ($socialClass != '')
            $result = $this->_listInstances[$socialClass]->linkSocialScore($link, '');
        elseif (is_a($this->_defaultInstance, 'SocialInterface'))
            $result = $this->_defaultInstance->linkSocialScore($link, '');
        else
            $this->_metrologyInstance->addLog('error no default social class', Metrology::LOG_LEVEL_ERROR, __METHOD__, '74686ed7');
        return $result;
    }

    /**
     * Permet d'injecter une liste pour le calcul/filtrage social.
     * Nécessaire à certains filtrages sociaux, ignoré par d'autres.
     * La liste doit contenir des ID d'objet et non des objets.
     *
     * @param array  $listID
     * @param string $socialClass
     * @return boolean
     */
    public function setList(array $listID, string $socialClass = ''): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($socialClass == '') {
            $this->_metrologyInstance->addLog('error empty social class', Metrology::LOG_LEVEL_ERROR, __METHOD__, '18dbcb17');
            return false;
        }
        if (! isset($this->_listInstances[$socialClass])) {
            $this->_metrologyInstance->addLog('error unknown socialClass=' . $socialClass, Metrology::LOG_LEVEL_ERROR, __METHOD__, '0c070b7e');
            return false;
        }
        return $this->_listInstances[$socialClass]->setList($listID, $socialClass);
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function unsetList(string $socialClass = ''): bool {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($socialClass == '') {
            $this->_metrologyInstance->addLog('error empty social class', Metrology::LOG_LEVEL_ERROR, __METHOD__, 'c52dcb51');
            return false;
        }
        if (! isset($this->_listInstances[$socialClass])) {
            $this->_metrologyInstance->addLog('error unknown socialClass=' . $socialClass, Metrology::LOG_LEVEL_ERROR, __METHOD__, 'b924d3be');
            return false;
        }
        return $this->_listInstances[$socialClass]->unsetList();
    }

    /**
     * Retourne la liste des modes de calculs sociaux disponibles.
     *
     * @return array
     */
    public function getSocialNames(): array { return $this->_listTypes; }
    public function getSocialInstances(): array { return $this->_listInstances; }
}
