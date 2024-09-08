<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;

/**
 * Functions for nebule library and more.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Functions
{
    protected ?nebule $_nebuleInstance = null;
    protected ?Metrology $_metrologyInstance = null;
    protected ?Configuration $_configurationInstance = null;
    protected ?Rescue $_rescueInstance = null;
    protected ?Authorities $_authoritiesInstance = null;
    protected ?Entities $_entitiesInstance = null;
    protected ?Recovery $_recoveryInstance = null;
    protected ?Cache $_cacheInstance = null;
    protected ?Session $_sessionInstance = null;
    protected ?Ticketing $_ticketingInstance = null;
    protected ?ioInterface $_ioInstance = null;
    protected ?CryptoInterface $_cryptoInstance = null;
    protected ?SocialInterface $_socialInstance = null;

    public function __construct(nebule $nebuleInstance)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->setEnvironment();
        $this->_initialisation();
    }

    public function setEnvironment()
    {
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_configurationInstance = $this->_nebuleInstance->getConfigurationInstance();
        $this->_rescueInstance = $this->_nebuleInstance->getRescueInstance();
        $this->_authoritiesInstance = $this->_nebuleInstance->getAuthoritiesInstance();
        $this->_entitiesInstance = $this->_nebuleInstance->getEntitiesInstance();
        $this->_recoveryInstance = $this->_nebuleInstance->getRecoveryInstance();
        $this->_cacheInstance = $this->_nebuleInstance->getCacheInstance();
        $this->_sessionInstance = $this->_nebuleInstance->getSessionInstance();
        $this->_ticketingInstance = $this->_nebuleInstance->getTicketingInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_cryptoInstance = $this->_nebuleInstance->getCryptoInstance();
        $this->_socialInstance = $this->_nebuleInstance->getSocialInstance();
    }

    protected function _initialisation()
    {
        // Replace on children classes.
    }



    static public function signReferences($nebuleInstance): bool
    {
        $ok = true;

        // Load entity before sign.
        // TODO

        // Generate links for icons.
        foreach ( References::OBJ_IMG as $name => $content) {
            $instance = new Node($nebuleInstance, '0');
            $decoded = (string)base64_decode($content, false);
            if (!$instance->setContent($decoded))
                $ok = false; // FIXME

            $reference = $nebuleInstance->getNIDfromData(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE);
            if (References::REF_IMG[$name] != '') {
                $nebuleInstance->getMetrologyInstance()->addLog('sign ref icon ' . 'f>' . References::REF_IMG[$name] . '>' . $instance->getID() . '>' . $reference, Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '08d23b22');

                // Use credentials on the first run with local entity. FIXME peut être refait avec Entities::setTempCurrentEntity()
                $newLink = \Nebule\Bootstrap\blk_generateSign('',
                    'f',
                    References::REF_IMG[$name],
                    $instance->getID(),
                    $reference
                );
                if (!\Nebule\Bootstrap\blk_write($newLink))
                    $ok = false;
            }
        }
        return $ok;
    }
}
