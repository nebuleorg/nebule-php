<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Head class to manage social interacts with links for current entity.
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

    public function __toString(): string
    {
        return self::TYPE;
    }

    protected function _initialisation(): void
    {
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
    public function arraySocialFilter(array &$links, string $socialClass = ''): void
    {
        if ($socialClass == ''
            || !isset($this->_listClasses[$socialClass])
            || !isset($this->_listInstances[$socialClass])
        )
        {
            $this->_defaultInstance->arraySocialFilter($links, '');
            return;
        }

        $this->_listInstances[$socialClass]->arraySocialFilter($links, '');
    }

    /**
     * Calcul le score social d'un lien.
     *
     * @param LinkRegister $link
     * @param string       $socialClass
     * @return float
     */
    public function linkSocialScore(LinkRegister &$link, string $socialClass = ''): float
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->linkSocialScore($link, '');
        else
            $result = $this->_defaultInstance->linkSocialScore($link, '');
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
    public function setList(array $listID, string $socialClass = ''): bool
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->setList($listID);
        else
            $result = $this->_defaultInstance->setList($listID);
        return $result;
    }

    /**
     * Permet de vider la liste pour le calcul/filtrage social.
     *
     * @param string $socialClass
     * @return boolean
     */
    public function unsetList(string $socialClass = ''): bool
    {
        if ($socialClass != '')
            $result = $this->_listInstances[get_class($this) . $this->_listTypes[$socialClass]]->unsetList();
        else
            $result = $this->_defaultInstance->unsetList();
        return $result;
    }

    /**
     * Retourne la liste des modes de calculs sociaux disponibles.
     *
     * @return array
     */
    public function getSocialNames(): array
    {
        return $this->_listTypes;
    }

    public function getSocialInstances(): array
    {
        return $this->_listInstances;
    }

    /**
     * Affiche la partie menu de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationTitles(): void
    {
        ?>

        <li><a href="#s">S / Social</a></li>

        <?php
    }

    /**
     * Affiche la partie texte de la documentation.
     *
     * @return void
     */
    static public function echoDocumentationCore(): void
    {
        ?>

        <?php Displays::docDispTitle(1, 's', 'Social'); ?>
        <p>Dans la reconnaissance et la confiance dans les liens que l'on va partager, mais surtout que l'on va
            recevoir, il faut pouvoir faire le tri. Les liens définissants les relations entre les objets, un certain
            nombre de règles sont définie pour pondérer, et donc sélectionner, des liens plutôt que d'autres. Le système
            de pondération fonctionne sur la base de modèles de classification dites sociales, c'est-à-dire sur la
            façon dont les personnes, ici les entités, interagissent.</p>
        <p>Il y a un certain nombre de classifications implémentées dans la bibliothèque :</p>
        <ul>
            <li><b>myself</b> : Classe de gestion du côté social des liens limités à l'entité en cours.</li>
            <li><b>notmyself</b> : Classe de gestion du côté social des liens limités à tout sauf l'entité en cours.</li>
            <li><b>self</b> : Classe de gestion du côté social des liens limités à l'entité en cours d'affichage.
                Ce peut être une entité différente de l'entité déverrouillée.</li>
            <li><b>notself</b> : Classe de gestion du côté social des liens limités à tout sauf l'entité en cours
                d'affichage. Ce peut être une entité différente de l'entité déverrouillée.</li>
            <li><b>authority</b> : Classe de gestion du côté social stricte des liens générés par des autorités locales
                et globales.</li>
            <li><b>all</b> : Classe de gestion du côté social des liens, garde tout.</li>
            <li><b>none</b> : Classe de gestion du côté social des liens, supprime tout.</li>
            <li><b>onlist</b> : Classe de gestion du côté social des liens par rapport à une liste d'ID.
                Ne sélectionne que les liens des entités de la liste.
                La liste doit être pré-alimentée.</li>
            <li><b>offlist</b> : Classe de gestion du côté social des liens par rapport à une liste d'ID.
                Ne sélectionne que les liens des entités qui ne sont pas dans la liste.
                La liste doit être pré-alimentée.</li>
            <li><b>reputation</b> : Classe de gestion du côté social des liens par rapport à la réputation des entités.
                Ne sélectionne que les liens des entités qui sont bien réputées. TODO.</li>
            <li><b>unreputation</b> : Classe de gestion du côté social des liens par rapport à la réputation des entités.
                Ne sélectionne que les liens des entités qui ne sont pas bien réputées. TODO.</li>
        </ul>

        <?php
    }
}
