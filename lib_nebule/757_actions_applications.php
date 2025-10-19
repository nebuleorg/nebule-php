<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Actions on applications.
 * TODO must be refactored!
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ActionsApplications extends Actions implements ActionsInterface {
    // WARNING: contents of constants for actions must be uniq for all actions classes!
    const SYNCHRONIZE = 'actsynapp';



    public function initialisation(): void {}
    public function genericActions(): void {
        $this->_extractActionSynchronizeApplication();
        if ($this->_actionSynchronizeApplicationInstance != '')
            $this->_actionSynchronizeApplication();
    }
    public function specialActions(): void {}



    protected ?Node $_actionSynchronizeApplicationInstance = null;
    public function getSynchronizeApplicationInstance(): ?Node
    {
        return $this->_actionSynchronizeApplicationInstance;
    }
    protected function _extractActionSynchronizeApplication(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');
        if ($this->_configurationInstance->checkGroupedBooleanOptions('GroupSynchronizeApplication')
            && ($this->_configurationInstance->getOptionAsBoolean('permitPublicSynchronizeApplication')
                || $this->_entitiesInstance->getConnectedEntityIsUnlocked()
            )
        ) {
            $arg = $this->getFilterInput(self::SYNCHRONIZE, FILTER_FLAG_ENCODE_LOW);

            if (Node::checkNID($arg))
                $this->_actionSynchronizeApplicationInstance = $this->_cacheInstance->newNode($arg);
        }
    }
    protected function _actionSynchronizeApplication(): void
    {
        $this->_metrologyInstance->addLog('track functions', Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '1111c0de');

        echo $this->_displayInstance->convertInlineIconFace('DEFAULT_ICON_SYNOBJ') . $this->_displayInstance->convertInlineObjectColor($this->_actionSynchronizeApplicationInstance);

        // Synchronisation des liens (l'objet est forcément présent).
        $this->_actionSynchronizeApplicationInstance->syncLinks();

        // Liste des liens l pour l'entité en source.
        $links = $this->_actionSynchronizeApplicationInstance->getLinksOnFields('', '', 'l', $this->_actionSynchronizeApplicationInstance->getID(), '', '');
        // Synchronise l'objet cible.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid2']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        // Liste des liens l pour l'entité en cible.
        $links = $this->_actionSynchronizeApplicationInstance->getLinksOnFields('', '', 'l', '', $this->_actionSynchronizeApplicationInstance->getID(), '');
        // Synchronise l'objet source.
        $object = null;
        foreach ($links as $link) {
            $object = $this->_cacheInstance->newNode($link->getParsed()['bl/rl/nid1']);
            // Synchronise les liens (avant).
            $object->syncLinks();
            // Synchronise l'objet.
            $object->syncObject();
        }
        unset($links, $link, $object);
    }
}