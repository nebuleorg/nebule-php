<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Class Application for preloading another application.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class AppPreload extends App0
{
    const APPLICATION_NAME = 'preload';
    const APPLICATION_SURNAME = 'nebule/preload';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020260101';
    const APPLICATION_LICENCE = 'GNU GPL 2024-2026';
    const APPLICATION_WEBSITE = 'www.nebule.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    public function display(): void
    {
        $this->_metrologyInstance->log_reopen('preload');
        $this->_metrologyInstance->addLog('Loading', Metrology::LOG_LEVEL_NORMAL, __METHOD__, '331f2c5b');

        echo 'CHK';
        ob_end_clean();

        $this->_htmlHeader('preload');
        $this->_htmlTop();

        echo '<div class="preload">' . "\n";
        echo "Please wait...<br/>\n";
        echo 'tB=' . \Nebule\Bootstrap\lib_getMetrologyTimer('tB') . "<br />\n";
        echo '</div>' . "\n";
        flush();

        echo '<div class="preload">' . "\n";
        echo '<img title="bootstrap" style="background:#ababab;" alt="[]" src="data:image/png;base64,' . References::OBJ_IMG['nebule'] . '"/>' . "\n";
        echo 'Load nebule library POO<br/>' . "\n";
        echo 'ID=' . $this->_routerInstance->getLibraryIID() . "<br />\n";
        echo 'tL=' . \Nebule\Bootstrap\lib_getMetrologyTimer('tL') . "<br />\n";
        echo '</div>' . "\n";
        flush();

        echo '<div class="preload">' . "\n";
        echo '<img title="bootstrap" style="background:#' . substr($this->_routerInstance->getApplicationIID() . '000000', 0, 6) . ';"' . "\n";
        echo 'alt="[]" src="data:image/png;base64,' . References::OBJ_IMG['lo'] . '"/>' . "\n";
        echo 'Load application<br/>' . "\n";
        echo 'IID=' . $this->_routerInstance->getApplicationIID() . "<br />\n";
        echo 'OID=' . $this->_routerInstance->getApplicationOID() . "<br />\n";
        flush();

        $this->_routerInstance->instancingApplication();

        if ($this->_routerInstance->getApplicationInstance() === null) {
            $this->_metrologyInstance->addLog('error preload application code OID=' . $this->_routerInstance->getApplicationOID() . ' null', Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '9c685c75');
            echo ' <span class="error">ERROR!</span> error preload application code OID null';
            echo '<br/>' . "\n";
            return;
        } elseif (!is_a($this->_routerInstance->getApplicationInstance(), $this->_routerInstance->getApplicationNameSpace() . '\Application')) {
            $this->_metrologyInstance->addLog('error preload application code OID=' . $this->_routerInstance->getApplicationOID() . ' with class=' . get_class($this->_routerInstance->getApplicationInstance()), Metrology::LOG_LEVEL_ERROR, __FUNCTION__, '2e87a827');
            echo ' <span class="error">ERROR!</span> error preload application code OID with class ' . get_class($this->_routerInstance->getApplicationInstance());
            echo '<br/>' . "\n";
            return;
        } else {
            echo 'Name=' . $this->_routerInstance->getApplicationInstance()->getClassName() . "<br/>\n";

            echo 'sync<span class="preloadsync">' . "\n";
            $items = $this->_routerInstance->getApplicationInstance()->getDisplayInstance()->getNeededObjectsList();
            $nb = 0;
            foreach ($items as $item) {
                if (!$this->_ioInstance->checkObjectPresent($item)) {
                    $instance = $this->_cacheInstance->newNode($item);
                    $this->_routerInstance->getApplicationInstance()->getDisplayInstance()->displayInlineObjectColorNolink($instance);
                    echo "\n";
                    $instance->syncObject(false);
                    $nb++;
                }
            }
            if ($nb == 0)
                echo '-';
            echo '</span><br/>' . "\n";
            \Nebule\Bootstrap\lib_setMetrologyTimer('tP');
            echo 'tP=' . \Nebule\Bootstrap\lib_getMetrologyTimer('tP') . "<br />\n";
        }
        echo '</div>' . "\n";
        flush();

        $this->_partDisplayReloadPage(true, 500);
        $this->_htmlBottom();
    }
}
