<?php
declare(strict_types=1);
namespace Nebule\Library;
use Nebule\Library\nebule;
use Nebule\Library\linkInterface;

/**
 * La classe Link.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 *
 * Attend à la création :
 * - l'instance nebule utilisé ;
 * - un texte contenant le lien ;
 * - un texte contenant la version du lien.
 *
 * Le nombre de champs du lien doit être de 7 exactement.
 * Le champs signature peut être vide ou à 0 si c'est pour un nouveau lien à signer.
 * La signature doit être explicitement demandée par l'appelle de sign() après la création du lien.
 *
 * La version du lien n'est pas prise en compte.
 *
 * Un lien peut être valide si sa structure est correcte, et peut être signé si la signature est valide.
 *
 * Un lien dissimulé peut ne pas pouvoir être lu si l'entité n'est pas destinataire, donc n'a pas accès à la clé de déchiffrement.
 * Il reste dans ce cas géré comme un lien normal mais de type c.
 * Cependant, si l'entité destinataire est déverrouillée mais ne peut déchiffrer le lien, alors le lien est considéré corrompu.
 */
class Bloclink implements linkInterface
{
    /**
     * Liste des variables à enregistrer dans la session php lors de la mise en sommeil de l'instance.
     *
     * @var array:string
     */
    const SESSION_SAVED_VARS = array(
        '_fullLink',
        '_signe',
        '_signeValue',
        '_signeAlgo',
        '_signed',
        '_hashSigner',
        '_date',
        '_action',
        '_hashSource',
        '_hashTarget',
        '_hashMeta',
        '_obfuscated',
        '_verified',
        '_valid',
        '_validStructure',
        '_verifyNumError',
        '_verifyTextError',
    );

    /**
     * Instance nebule en cours.
     *
     * @var nebule
     */
    protected $_nebuleInstance;

    /**
     * Instance de gestion de la configuration et des options.
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     * Instance io en cours.
     *
     * @var io
     */
    protected $_io;

    /**
     * Instance crypto en cours.
     *
     * @var CryptoInterface $_crypto
     */
    protected $_crypto;

    /**
     * Instance métrologie en cours.
     *
     * @var Metrology
     */
    protected $_metrology;

    /**
     * @var string $_rawBloclink
     */
    protected $_rawBloclink = '';

    /**
     * @var array $_links
     */
    protected $_links = array();

    /**
     * Parsed link contents.
     *
     * @var array $_parsedLink
     */
    protected $_parsedLink = array();

    /**
     * Booléen si le lien a été vérifié.
     *
     * @var boolean $_verified
     */
    protected $_verified = false;

    /**
     * Booléen si le lien est vérifié et valide.
     *
     * @var boolean $_valid
     */
    protected $_valid = false;

    /**
     * Booléen si le lien a une structure valide.
     *
     * @var boolean $_validStructure
     */
    protected $_validStructure = false;

    /**
     * Booléen si le lien est signé.
     *
     * @var boolean $_signed
     */
    protected $_signed = false;

    /**
     * Nombre représentant un code d'erreur de vérification.
     *
     * @var integer $_verifyNumError
     */
    protected $_verifyNumError = 0;

    /**
     * Texte de la description de l'erreur de vérification.
     *
     * @var string $_verifyTextError
     */
    protected $_verifyTextError = 'Initialisation';

    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @param string $bloclink
     * @return boolean
     */
    public function __construct(nebule $nebuleInstance, string $bloclink)
    {
        $this->_nebuleInstance = $nebuleInstance;
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
        $this->_metrology->addLinkRead(); // Metrologie.

        // Extrait le bloc de liens et vérifie sa structure.
        if (!$this->_extract($bloclink))
            return false;

        // Vérifie la validité du lien.
        if (!$this->_verify())
            return false;

        return true;
    }

    /**
     * Donne le texte par défaut lorsque l'instance est utilisée comme texte.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_rawBloclink;
    }

    /**
     * Mise en sommeil de l'instance.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return self::SESSION_SAVED_VARS;
    }

    /**
     * Fonction de réveil de l'instance et de ré-initialisation de certaines variables non sauvegardées.
     *
     * @return null
     */
    public function __wakeup()
    {
        global $nebuleInstance;
        $this->_nebuleInstance = $nebuleInstance;
        $this->_metrology = $nebuleInstance->getMetrologyInstance();
        $this->_configuration = $nebuleInstance->getConfigurationInstance();
        $this->_io = $nebuleInstance->getIoInstance();
        $this->_crypto = $nebuleInstance->getCryptoInstance();
    }

    private function _extract(string $bloclink): bool
    {
        return true;
    }

    private function _verify():bool
    {
        return true;
    }

    /**
     * Retourne le lien complet.
     *
     * @return string
     */
    public function getRawLink(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '9c53bb45');

        return $this->_rawBloclink;
    }

    public function getLinks(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        return $this->_links;
    }

    public function getSigners(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, 'ad2228cc');

        return $this->_links;
    }

    public function getParsedLink(): array
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getParsedLink() method.
        return array();
    }

    public function getValid(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getValid() method.
        return false;
    }

    public function getValidStructure(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getValidStructure() method.
        return false;
    }

    public function getVerified(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerified() method.
        return false;
    }

    public function getSigned(): bool
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getSigned() method.
        return false;
    }

    public function getVersion(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }

    public function getDate(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }

    public function getVerifyNumError(): int
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyNumError() method.
        return 0;
    }

    public function getVerifyTextError(): string
    {
        $this->_metrology->addLog(substr($this->_rawBloclink, 0, 32), Metrology::LOG_LEVEL_FUNCTION, __METHOD__, '00000000');

        // TODO: Implement getVerifyTextError() method.
        return '';
    }
}
