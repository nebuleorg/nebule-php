<?php
declare(strict_types=1);
namespace Nebule\Application\Messae;
use Nebule\Library\Entity;
use Nebule\Library\Metrology;
use Nebule\Library\nebule;
use Nebule\Library\Actions;
use Nebule\Library\Applications;
use Nebule\Library\Displays;
use Nebule\Library\Modules;
use Nebule\Library\Node;
use Nebule\Library\Traductions;
use const Nebule\Bootstrap\BOOTSTRAP_NAME;

/*
------------------------------------------------------------------------------------------
 /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
------------------------------------------------------------------------------------------

 [FR] Toute modification de ce code entrainera une modification de son empreinte
      et entrainera donc automatiquement son invalidation !
 [EN] Any changes to this code will cause a change in its footprint and therefore
      automatically result in its invalidation!
 [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
      tanto lugar automáticamente a su anulación!

------------------------------------------------------------------------------------------
*/



/**
 * Class Application for messae
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Application extends Applications
{
    const APPLICATION_NAME = 'messae';
    const APPLICATION_SURNAME = 'nebule/messae';
    const APPLICATION_AUTHOR = 'Projet nebule';
    const APPLICATION_VERSION = '020220907';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2022';
    const APPLICATION_WEBSITE = 'www.messae.org';
    const APPLICATION_NODE = '88848d09edc416e443ce1491753c75d75d7d8790c1253becf9a2191ac369f4ea.sha2.256';
    const APPLICATION_CODING = 'application/x-httpd-php';

    const APPLICATION_ENVIRONMENT_FILE = 'nebule.env';
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP = true;
    const APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS = false;
    const APPLICATION_DEFAULT_DISPLAY_METROLOGY = false;
    const APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL = true;
    const APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT = false;
    const APPLICATION_DEFAULT_DISPLAY_NAME_SIZE = 128;
    const APPLICATION_DEFAULT_IO_READ_MAX_DATA = 1000000;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT = false;
    const APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS = false;
    const APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOG_LOCK_ENTITY = false;
    const APPLICATION_DEFAULT_LOAD_MODULES = 'd6105350a2680281474df5438ddcb3979e5575afba6fda7f646886e78394a4fb';

    /**
     * Paramètre d'activation de la gestion des modules dans l'application et la traduction.
     *
     * Par défault les applications n'utilisent pas les modules.
     *
     * @var boolean
     */
    protected $_useModules = true;

    /**
     * Liste des noms des modules par défaut.
     *
     * @var array
     */
    protected $_listDefaultModules = array(
        'ModuleHelp',
        'ModuleAdmin',
        'ModuleEntities',
        'ModuleGroups',
        'ModuleObjects',
        'ModuleTranslateFRFR',
    );


    /**
     * Constructeur.
     *
     * @param nebule $nebuleInstance
     * @return void
     */
    public function __construct(nebule $nebuleInstance)
    {
        parent::__construct($nebuleInstance);
    }


    /**
     * Marque un objet.
     *
     * @param string $object
     */
    public function setMarkObject(string $object): void
    {
        $list = $this->_nebuleInstance->getSessionStore('sylabeObjectMarkList');
        if ($list === false) {
            $list = array();
        }
        $list[$object] = true;
        $this->_nebuleInstance->setSessionStore('sylabeObjectMarkList', $list);
        unset($list);
    }

    /**
     * Supprime la marque d'un objet.
     * @param string $object
     */
    public function setUnmarkObject($object): void
    {
        $list = $this->_nebuleInstance->getSessionStore('sylabeObjectMarkList');
        if ($list === false)
            return;
        unset($list[$object]);
        $this->_nebuleInstance->setSessionStore('sylabeObjectMarkList', $list);
        unset($list);
    }

    /**
     * Supprime les marques de tous les objets.
     */
    public function setUnmarkAllObjects(): void
    {
        $this->_nebuleInstance->setSessionStore('sylabeObjectMarkList', array());
    }

    /**
     * Lit si un objet est marqué.
     *
     * @param string $object
     * @return boolean
     */
    public function getMarkObject(string $object): bool
    {
        $list = $this->_nebuleInstance->getSessionStore('sylabeObjectMarkList');
        if ($list === false)
            return false;
        if (isset($list[$object]))
            return true;
        return false;
    }

    /**
     * Lit la liste des objets marqués.
     * @return array
     */
    public function getMarkObjectList(): array
    {
        $list = $this->_nebuleInstance->getSessionStore('sylabeObjectMarkList');
        if ($list === false)
            $list = array();
        return $list;
    }
}


/**
 * Classe Display
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Display extends Displays
{
    const DEFAULT_DISPLAY_MODE = 'hlp';
    const DEFAULT_DISPLAY_VIEW = '1st';
    const DEFAULT_LINK_COMMAND = 'lnk';
    const DEFAULT_APPLICATION_LOGO = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAAXEElEQVR42u2dfUxb97nHf/gF1xgIGBxiuiZQXgKI1l5iVpKARoAyiGoCl04tQzHR1Z1SZT26MpEokTZtUiaFVaJWe/JH0KSb4kgXopVBcbdEiAALJk0TmtnTBISEQliCIYQDIWDPGMj9495KW26WhuQc+7x8P39GCj5+nuf74fc7nJcwQsivCABAkshQAgAgAAAABAAAgAAAABAAAAACAABAAAAACAAAAAEAACAAAAAEAACAAAAAEAAAAAIAAEAAAAAIAAAAAQAAIAAAAAQAAAglCpRA+Gg0Gnlubm5MVlZWTFJSUrRer4+Kj4+PjI2NjYiMjIzQaDQvqdVqVXh4eLhSqVQqFAq5TCaTy2SyMEII2djYeLSxsbG+tra2HggEAqurq6s+n8+/srLy9+XlZe/CwoL3/v37yx6P5+Hk5OTS8PDw4pUrVxZXVlbWUX0IAAQJnU6nLCsr22Yy
mbamp6frtm/fHp+QkBCn1WpjXuTnyuXyMLlcrlAqlQq1Wq161v/HMMzi7Ozs/NTU1P2xsbG5oaGhe+fPn5+Zm5sLoFvCIIzgqcC8paKiYltxcfH3DAbDyykpKYl6vX6rEI7b4/HcGx8fn3a73Xd7enrudHZ2zqCbEAD4Dsxmc0J5eXmyyWRKSk9P3x4REaEWw/fyer2+sbGxqaGhocmurq4Jh8Mxi25DAJJHpVLJrFZrWklJSZrBYEh50aW8UGAYZtHtdo93d3fftNlsN/1+/wamAQKQBBqNRt7Q0JBZWlqaYTAYdiqVSkmfhwkEAmtut/vGhQsXRhsbG0dwYhECECV1dXVpVVVV2Tk5OVlSD/3TZHDt2rXh9vb2v3700Uc3UREIQNAUFBRoKYoyFBQUvC6V5T2b24T+/v6/0DTt7u/vZ1ARCEAw1NfXp1dXV+8yGo07UY0Xx+Vy3Whtbb3+4YcfjqEaEABvOXXqlKmystKUmJiYgGqwz/T09GxHR8fQ
+++/P4RqQAC8QKfTKW02W67ZbP5BdHR0JCrCPUtLS8sOh+Oq1Wq9gouOIICQBZ+m6b3l5eV7NnP1HGAPn8/n7+rq+pKiqMsQAQQQNOx2+76qqqp9YrlQR+h4vV5fe3v7oMViGUQ1IADOsNlsuywWSz7O6PMThmEW7Xb7gNVqvY5qPBtyQkgByvB0KIpKaWtrqywrK8tRq9UvoSL8RK1Wv5Sbm7uztrY2ZWNjY+nq1asLqApWAM+N0WiMomm6MC8vz4hqCA+n0+miKKrX5XI9RDUggE3R3Nz8Rm1tbZFKpVKiGsLF7/cHWlpaLh45cuQrVANbgO+koqJiW0dHx7/96Ec/ylEoFHJURNgoFAr57t27U999990dd+/enR0dHV1GVbACeCJ2u33foUOHilEJ8XL27Nke/LUAK4B/oqioKO4Pf/hDVVFRkQkjIW4MBsOr77zzziujo6N3JyYmfBCAxAXQ1NT0/U8++aQ6ISEhHvGQBjqdTltdXb0rJiZmubu7
W9JPK5L0FqC3t/et/fv370YkpEtfX9/XhYWFX0j1+0vyseBmszlhcnLypwg/2L9//+7Jycmfms1mSd7AJbktwIkTJ7I/+eSTn2i12i0Yf0AIITExMVFvv/22MTw8fKGvr+8etgAipa2t7YfvvPNOAUYe/CvOnTvX/+677/4JAhAZAwMDB3FFH3gWnE6nKz8//3OcAxABqampESMjIxaEHzwreXl5xpGREUtqamoEBCBgSkpK4i9dulSbkZGRjLEGmyEjIyP50qVLtSUlJaL+87BoTwJaLJZXzpw5UxMfHx+LcQbPQ1RUlObgwYNZ09PTd9xu9xIEIBAoikqhafonGo0GD+wAL4RKpQo/cOBA9oMHD6bFeHux6ARQX1+f3tTUVIMbeQBbKBQKeWlp6es+n88zODg4DwHwlIaGhp2NjY3VYWFhmFrAKmFhYeTNN998ze/3zzidznkIgIe/+RsbG6sxqoBLiouLs8W0EhCFACiKSmlqaqrBb34QJAm8xjDM
HTGcExC8ACwWyys0Tf8Ee34QzO1AYWFh5tTU1KTQ/zogaAGUlJTEnzlzpgZn+0GwUSgU8qKiorTr16/fHB8f9wr1ewj2QqDU1NSITz/99Md4Gw8IFdHR0ZGffvrpj4V8xaBgBeBwON7W6/VbMYYglOj1+q0Oh+NtCCCIDAwMHMTlvYAvZGRkJA8MDByEAIJAW1vbD3FjD+AbeXl5xra2th8K7bgFdRLwxIkT2UePHj2AcQN8JDs7O0mhUMwL6aEiglkBmM3mhA8++ICVZZbL5cK0Ak5m4oMPPjgopMeLCUYANE2XK5VKBUvLNeJ0OjH1gBBCiNPpJHl5eaz8LKVSqaBpuhwCYJHe3t63duzYkcjWz1OpVCQ/Px8SAMTpdJL8/HyiUqlY+5k7duxI7O3tfQsCYIGmpqbvc/X0XkgA4c/Pz+fkZ+/fv393U1PT9yGAF6CoqCiOoihOT/pBAgg/V1AUdaCoqCgOAnj+fX8ZW/t+SAAEM/z/cD6gDAJ4Dux2
+77MzMyUYH0eJIDwc0FmZmaK3W7fBwFsgoqKim2heEsvJIDwc8GhQ4eKKyoqtkEAz8jJkydLQvXZkADCL7aZFpQAmpub3wj1df6QAMLPNhkZGcnNzc1vQABPwWg0RtXW1hbx4VggAYSfbWpra4uMRmMUBPAvoGm6UKVSKflyPJAAws8mKpVKSdN0IQTwBCiKSuHjXX6QAMLPJnl5eUaKolIggMewWq28vZUSEkD4xTrrvBCAzWbblZyc/AqfhwoSQPjZIjk5+RWbzbYLAvg/LBZLvhCGCxJA+MU28yEXgN1u36fVamOEMmSQAMLPBlqtNoYPVwiGVAA6nU5ZVVW1T2jDBgkg/GxQVVW1T6fTKSUrAJqm90ZERAjymf6QAML/okRERKhpmt4rSQHodDpleXn5HiEPHySA8L8o5eXle0K5CgiZAGw2W65arVYJfQghAYT/RVCr1SqbzZYrOQGYzeYfiGUYIQGEX6hZCIkATp06ZRLbK70gAYT/eYmOjo48
deqUSTICqKysNIlxOCEBhF9omQi6AOrr69MTExMTxDqkkADC/zwkJiYm1NfXp4teANXV1bvEPqyQAMIvlGwEVQAFBQVao9G4UwpDCwkg/JvFaDTuLCgo0IpWABRFGaQ0vJAAws/3jAR7BfC61IYYEkD4+ZyRoAmgrq4uTUg3/UACCH8o0Gq1MXV1dWmiE0BVVVW2lIcaEkD4+ZiVoAhAo9HIc3JysqQ+3JAAwv8s5OTkZGk0GrloBNDQ0JAZjFd8QQIIvxhQKpWKhoaGTNEIoLS0NANthQQQfv5lhnMBqFQqmcFg2ImWQgII/7NjMBh2qlQqmeAFYLVa07D8hwQQ/s1vA6xWa5rgBVBSUpKGdkICCD8/s8O5AAwGQwpaCQkg/PzMDqcCMJvNCVK9+AcSQPhfFK1WG2M2mxMEK4Dy8vJktBESQPj5myFOBWAymZLQQkgA4edvhjgVQHp6+na0EBJA+PmbIc4EUFFRsU2oz/yHBBB+vhAREaGuqKjYJjgB
FBcXfw/tk7YEEH7+Z4kzARgMhpfROulKAOEXRpY4E0BKSkoiWidNCSD8wskSJwLQ6XRKvV6/Fa2TngQQfvbR6/VbuXp9GCcCKCsr24a2SU8CCD93cJUpTgRgMpnw219iEkD4uYWrTHEigPT0dB1aJh0JIPzcw1WmOBHA9u3b49EyaUgA4Q8OXGWKEwEkJCTEoWXilwDCHzy4yhTrAtBoNHLcASh+CSD8wUWr1cZw8aBQ1gWQm5uL8ItcAgh/aOAiW6wLICsrCwIQsQQQ/tDBRbZYF0BSUlI0WiVOCSD8oYWLbLEuAL1eH4VWiU8CCH/o4SJbrAsgPj4+Eq0SlwQQfn7ARbZYF0BsbGwEWiUeCSD8/IGLbLEugMjISAhAJBJA+PkFF9ni4jqAl9Aq4UsA4ecfXGSLdQGo1WoVWiVsCSD8/ISLbLEugPDw8HC0SrgSQPj5CxfZYl0ASqVSiVYJUwIIP7/hIlusC0ChUMjRKuFJAOHnP1xki3UByGQyCEAA
EniWfwP8gotscSGAMLQKAE4EEMZ7AQAABCQVtn/gxsbGI5QVAPbhIltcCGAdrQKAEwGs814Aa2trEAAAHMBFtlgXQCAQCKBVALAPF9liXQCrq6uraBUA7MNFtlgXgM/n86NVALAPF9liXQArKyt/R6sAYB8ussW6AJaXl71oFQDsw0W2WBfAwsICBAAAB3CRLdYFcP/+/WW0CgD24SJbrAvA4/E8RKsAYB8ussW6ACYnJ5fQKgDYh4tssS6A4eHhRbQKAPbhIlusC+DKlSsQAAAcwEW2uLgOYJ1hGEgAABZhGGZxZWWF//cCEELI7OzsPFoGAP8zxYkApqam7qNlAPA/U5wIYGxsbA4tA4D/meJEAENDQ/fQMgD4nylOBHD+/PkZtAwA/meKEwHMzc0FPB4PVgEAsIDH47k3NzcXEIwACCFkfHx8Gq0DgN9Z4kwAbrf7LloHAL+zxJkAenp67qB1APA7S5wJoLOzc8br9frQPgCeH6/X6+vs7JwRnAAI
IWRsbGwKLQSAvxniVABDQ0OTaCEA/M0QpwLo6uqaQAsB4G+GOBWAw+GYxZ2BADwfDMMsOhyOWcEKgBBC3G73OFoJAD+zw7kAuru7b6KVAPAzO2GEkF9x+QEqlUr28OHD40qlUsGXwsbFxRGGYSQ9XI8e/fObpsPCwiRdD61WS+bn+fMYi0AgsBYVFXXS7/dvCHoF4Pf7N9xu9w34HIBNLf9vcB3+oAiAEEIuXLgwipYCwL/McL4FIIQQjUYjX1hYaODLNgBbAGwB+LwFCAQCa7GxsY1cPAMwJCuAlZWV9WvXrg3D6wB8N9euXRsORviDJgBCCGlvb/8rWgsAv7ISlC3At8zPz/+nVquNwRYAWwBsAZ4MwzCLcXFxHwfr82TB/HL9/f1/gd8B4E9GgioAmqbdaDEA/MlIsFcAjMvlwjUBADwBl8t1o7+/nxGtAAghpLW19TpaDQA/shHUk4Dfcvfu3fcSExMTQlVonATEScDHCfVJwOnp6dmXX375dLA/
VxaKL9vR0TEE3wMQ+kyEZAVACCEPHjw4Fh0dHYkVAFYAUl8BLC0tLW/ZsqUpFJ8tC1XBHQ7HVXgfgNBmIWQCsFqtV3w+nx/tB1LG5/P5rVbrFckJYG5uLtDV1fUlRgBIma6uri+5eu0XrwVACCEURV3GuwOAVPF6vT6Koi6H8hhCKoC5ublAe3v7IEYBSJH29vbBUP72D7kACCHEYrEM4snBQGowDLNosVhC/stPxodi2O32AYwEkBJ8mfmQXQfwON98882/JycnvxKMz8J1ALgO4HGCeR3AxMTE31599dX/4sP3lvGlATab7U/4vQCkAJ9mnTcCoGl63Ol0ujAeQMw4nU4XTdPjEMAToCiq1+/3BzAmQIz4/f4ARVG9fDomXgnA5XI9bGlpuYhRAWKkpaXlosvleggBPIUjR458NTo6ircKA1ExOjo6ceTIka/4dlwyPhbr+PHj3RgZICb4OtO8FEBnZ+fM2bNnezA2QAycPXu2p7OzcwYC2AQWi2Vw
ZGQErxYHgmZkZGScD1f8CU4AhBBCUdT5QCCwhjECQiQQCKxRFHWez8fIawFcvHhxnqbpP2KUgBChafqPFy9enIcAXoBjx479ua+v72uMExASfX19Xx87duzPfD9OmRCKWVhY+MXt27enMVZACNy+fXu6sLDwCyEcq0IoRaUoqqu9vf0/2HjF+JYtW8j6+jom9bGa4Puztu/vEsr35s3dgM/CiRMnsn/+859XIa6Ar/z6179u/8UvfiGYN2HLCSEFAtpX3cvMzCTZ2dlJGDXAN86dO9f/s5/97JqQjllQK4BvGRgYOJiXl2fEyAG+4HQ6Xfn5+Z8L7bhlQix2fn7+57hfAPCF0dHRCSGGX7ACIIQQs9n8mcfjuYfxA6HE4/HcM5vNnwn1+AUrgFu3bnkPHz78u6WlpWWMIQgFS0tLy4cPH/7drVu3vEL9DoI6Cfg44+Pj3unp6TsHDhzIVigUcowkCBZ+vz9w9OjR1t///vczQv4eghYAIYS43e6lBw8e
TJeWlr4u9QdbguDw6NEjUldX1/rb3/52UujfRfACIISQq1evLvh8Ps+bb775GsYTcE1DQ0OrzWa7JYbvIgoBEELI4ODgvN/vnykuLs7GiAKuOH78eNtvfvObMbF8H9EIgBBCnE7nvM/n8xQXF7+G7QBge9nf0NDQKqbwi04A364EGIa5U1hYmIkTg4AN/H5/oK6uTjTLflEL4NtzAlNTU5NFRUVpKpUqHCMMnpelpaXlo0ePiuKEn2QEQMj//nXg+vXrN4uLi5OioqI0GGWwWTwez72ampr/Fvqf+p6GIO8F2AypqakRDofj7YyMjGSMNHhWRkdHJ8xm82dCvsgHAvgHcAMReFaEemPP8yCTSlPz8/M/P3fuXD/GGzyNc+fO9Usl/KI+B/AkPvvss9sKhWJ+z5496XK5XIZxB98SCATWTp482SG0+/khgE3S19d3z+Vyje3duzcxJiYmCqMPbt++PX348OHW06dPS+4Wc8mcA3gSvb29b+3fv383IiBd
+vr6vhbKAzyxAmCZlpaWsejo6CWTyZSCLYH0lvwff/zxFzU1NZekXAdJC4AQQrq7u2cuX748nJOTE6fT6bSIhvgZGRkZr6mpaTt9+vQ3Uq+FpLcAj2O32/cdOnSoGJUQL2fPnu3h87v6gg2Wvf+AxWIZrKysbMbzBsXH6OjoRGVlZTPCjxXAM9Hc3PxGbW1tkUqlUqIawsXv9wdaWlouHjly5CtUAwLYFEajMYqm6UJcQShMnE6ni6KoXpfL9RDVeDKSPwn4NGZmZlbPnDlzg2GYOxkZGbGxsbFbUBX+MzEx8bdf/vKXjvfee+/LmZmZVVQEKwBWsNlsuywWS75Wq41BNfgHwzCLdrt9wGq1Xkc1IADOsNvt+6qqqvZFRESoUY3Q4/V6fe3t7YM4wQcBBA2dTqekaXpveXn5HrVarUJFgo/P5/N3dXV9SVHU5bm5uQAqAgGERAQ2my3XbDb/IDo6OhIV4Z6lpaVlh8Nx1Wq1XkHwIQDecOrUKVNlZaUp
MTExAdVgn+np6dmOjo6h999/fwjVgAB4S319fXp1dfUuo9G4E9V4cVwu143W1tbrH3744RiqAQEIhoKCAi1FUYaCgoLX8ZeDzcEwzGJ/f/9faJp29/f3M6gIBCBo6urq0qqqqrJzcnKylEqlAhX5/wQCgbVr164Nt7e3//Wjjz66iYpAAKJDo9HIGxoaMktLSzMMBsNOqcsgEAisud3uGxcuXBhtbGwcWVlZWceUQACSQKVSyaxWa1pJSUmawWBIkco2gWGYRbfbPd7d3X3TZrPd9Pv9G5gGCEDymM3mhPLy8mSTyZSUnp6+XSwXGnm9Xt/Y2NjU0NDQZFdX14TD4ZhFtyEA8B1UVFRsKy4u/p7BYHg5JSUlUa/XbxXCcXs8nnvj4+PTbrf7bk9Pz53Ozs4ZdBMCAC+ITqdTlpWVbTOZTFvT09N127dvj09ISIgL1daBYZjF2dnZ+ampqftjY2NzQ0ND986fPz+Di3MgABBENBqNPDc3NyYrKysmKSkp
Wq/XR8XHx0fGxsZGREZGRmg0mpfUarUqPDw8XKlUKhUKhVwmk8llMlkYIYRsbGw82tjYWF9bW1sPBAKB1dXVVZ/P519ZWfn78vKyd2FhwXv//v1lj8fzcHJycml4eHjxypUrizhhBwEAAAQMHgkGAAQAAIAAAAAQAAAAAgAAQAAAAAgAAAABAAAgAAAABAAAgAAAABAAAAACAABAAAAACAAAAAEAACAAAAAEAACAAAAAIeV/ANV+Y0OjQprZAAAAAElFTkSuQmCC";
    const DEFAULT_APPLICATION_LOGO_LINK = '?mod=hlp&view=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690';
    const DEFAULT_LOGO_MODULE = '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859';

    const APPLICATION_LICENCE_LOGO = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAA
sTAQCanBgAAAAHdElNRQfeDAYWDCX7YSGrAAABn0lEQVRIx62VPU8CQRCGn7toYWKCBRY0FNQe9CZ+dPwAt6AxkmgrJYX2JsaOVgtNTKQYfwCdYGKvUFOYGAooxJiYWHgWd5wL3C0Lc
aqbnZ1nd2923nUwmMqRBgbSTZ7jxCbuUKZIRhvq0eBamhYAVaLGesJyfSpSNwBUihZ5zPbCtgxjAWqDNn78oTTzcfCkMwVQG7SxtwjhRJt/Zz5LyQfAUui2ws1/cs+KIe2HTbKAzyOF
CKBK0a9b5U1OjXfjjizgkFclqYMLQE2bcaLOjVt3o69a6KrdibpXZyBGtq52At7BVMgWUQ4AxZiQHaIYADKxQRtEBlyVSwxbIFTOJW2IV9Xl1Nj3mJdemgjfyv7EGlccmqs6GPOXJyf
IEReG/IFrUpsQUU1GSNcFegsjekEZG7MLnoBoBIAbm0sXi7gGF+SB/kKIvjRHvVXR+t2MONPcY12RnvFwgFeetIadti/2WAV82lLQFWk7lLQsWas+dNgakwcZ4s2liF6giJq+SAcP8G
em+rom6wKFdFizkPY2qb/0/37a/uVxnfd5/wWNcHiC0uUMVAAAAABJRU5ErkJggg==';


    /**
     * Liste des objets nécessaires au bon fonctionnement.
     *
     * @var array
     */
    protected $_neededObjectsList = array(
        self::DEFAULT_LOGO_MENUS,
        self::DEFAULT_ICON_ALPHA_COLOR,
        self::DEFAULT_ICON_LC,
        self::DEFAULT_ICON_LD,
        self::DEFAULT_ICON_LE,
        self::DEFAULT_ICON_LF,
        self::DEFAULT_ICON_LK,
        self::DEFAULT_ICON_LL,
        self::DEFAULT_ICON_LLL,
        self::DEFAULT_ICON_LO,
        self::DEFAULT_ICON_LS,
        self::DEFAULT_ICON_LU,
        self::DEFAULT_ICON_LX,
        self::DEFAULT_ICON_IOK,
        self::DEFAULT_ICON_IWARN,
        self::DEFAULT_ICON_IERR,
        self::DEFAULT_ICON_IINFO,
        self::DEFAULT_ICON_IMLOG,
        self::DEFAULT_ICON_IMODIFY,
        self::DEFAULT_ICON_IDOWNLOAD,
        self::DEFAULT_ICON_HELP,
        self::DEFAULT_ICON_WORLD);

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load displays', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
        $this->_traductionInstance = $this->_applicationInstance->getTraductionInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Vide, est surchargé juste avant l'affichage.
        $this->setHtlinkObjectPrefix('?');
        $this->setHtlinkGroupPrefix('?');
        $this->setHtlinkConversationPrefix('?');
        $this->setHtlinkEntityPrefix('?');
        $this->setHtlinkCurrencyPrefix('?');
        $this->setHtlinkTokenPoolPrefix('?');
        $this->setHtlinkTokenPrefix('?');
        $this->setHtlinkTransactionPrefix('?');
        $this->setHtlinkWalletPrefix('?');

        $this->_findLogoApplication();
        $this->_findLogoApplicationLink();
        $this->_findLogoApplicationName();
        $this->_findCurrentDisplayMode();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Si en mode téléchargement d'objet ou de lien, pas de traduction.
        if ($this->_traductionInstance !== null) {
            $this->_currentDisplayLanguage = $this->_traductionInstance->getCurrentLanguage();
            $this->_currentDisplayLanguageInstance = $this->_traductionInstance->getCurrentLanguageInstance();
            $this->_displayLanguageList = $this->_traductionInstance->getLanguageList();
            $this->_displayLanguageInstanceList = $this->_traductionInstance->getLanguageInstanceList();
        }
    }

    /**
     * Initialisation des variables et instances interdépendantes.
     *
     * @return void
     */
    public function initialisation2(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_ioInstance = $this->_nebuleInstance->getIoInstance();
        $this->_metrologyInstance = $this->_nebuleInstance->getMetrologyInstance();
        $this->_metrologyInstance->addLog('Load displays', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
        $this->_traductionInstance = $this->_applicationInstance->getTraductionInstance();
        $this->_actionInstance = $this->_applicationInstance->getActionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();

        // Vide, est surchargé juste avant l'affichage.
        $this->setHtlinkObjectPrefix('?');
        $this->setHtlinkGroupPrefix('?');
        $this->setHtlinkConversationPrefix('?');
        $this->setHtlinkEntityPrefix('?');
        $this->setHtlinkCurrencyPrefix('?');
        $this->setHtlinkTokenPoolPrefix('?');
        $this->setHtlinkTokenPrefix('?');
        $this->setHtlinkTransactionPrefix('?');
        $this->setHtlinkWalletPrefix('?');

        $this->_findLogoApplication();
        $this->_findLogoApplicationLink();
        $this->_findLogoApplicationName();
        $this->_findCurrentDisplayMode();
        $this->_findCurrentDisplayView();
        $this->_findInlineContentID();

        // Si en mode téléchargement d'objet ou de lien, pas de traduction.
        if ($this->_traductionInstance !== null) {
            $this->_currentDisplayLanguage = $this->_traductionInstance->getCurrentLanguage();
            $this->_currentDisplayLanguageInstance = $this->_traductionInstance->getCurrentLanguageInstance();
            $this->_displayLanguageList = $this->_traductionInstance->getLanguageList();
            $this->_displayLanguageInstanceList = $this->_traductionInstance->getLanguageInstanceList();
        }
    }



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------
	 *
	 * Pour l'instant, rien n'est personnalisable dans le style, mais ça viendra...
	 * @todo
	 */
    /**
     * Variable du logo de l'application.
     * @var string
     */
    private $_logoApplication = '';

    /**
     * Recherche le logo de l'application.
     */
    private function _findLogoApplication(): void
    {
        $this->_logoApplication = self::DEFAULT_APPLICATION_LOGO;
        // @todo
    }

    /**
     * Variable du lien du logo de l'application.
     * @var string
     */
    private $_logoApplicationLink = '';

    /**
     * Recherche le lien du logo de l'application.
     */
    private function _findLogoApplicationLink(): void
    {
        $this->_logoApplicationLink = self::DEFAULT_APPLICATION_LOGO_LINK;
        // @todo
    }

    /**
     * Variable du nom de l'application.
     * @var string
     */
    private $_logoApplicationName = '';

    /**
     * Recherche le nom de l'application.
     */
    private function _findLogoApplicationName(): void
    {
        $this->_logoApplicationName = Application::APPLICATION_NAME;
        // @todo
    }


    /**
     * Vérifie que toutes les icônes déclarées soient présentes.
     * Sinon les synchronise.
     */
    private function _checkDefinedIcons(): void
    {
        // @todo
    }


    /**
     * Affichage de la page.
     */
    public function display(): void
    {
        // Préfix pour les objets. Les module sont chargés, on peut les utiliser.
        $this->setHtlinkObjectPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        // Préfix pour les groupes.
        if ($this->_applicationInstance->isModuleLoaded('ModuleGroups')) {
            $this->setHtlinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleGroups')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleGroups')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_GROUP . '=');
        } else {
            $this->setHtlinkGroupPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        }
        // Préfix pour les conversations.
        if ($this->_applicationInstance->isModuleLoaded('ModuleMessenger')) {
            $this->setHtlinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleMessenger')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleMessenger')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=');
        } else {
            $this->setHtlinkConversationPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        }
        // Préfix pour les entités.
        $this->setHtlinkEntityPrefix('?'
            . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getDefaultView()
            . '&' . nebule::COMMAND_SELECT_ENTITY . '=');
        // Préfix pour les monnaies.
        if ($this->_applicationInstance->isModuleLoaded('Moduleqantion')) {
            $this->setHtlinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[3]
                . '&' . nebule::COMMAND_SELECT_CURRENCY . '=');
            $this->setHtlinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[8]
                . '&' . nebule::COMMAND_SELECT_TOKENPOOL . '=');
            $this->setHtlinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[13]
                . '&' . nebule::COMMAND_SELECT_TOKEN . '=');
            $this->setHtlinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[19]
                . '&' . nebule::COMMAND_SELECT_TRANSACTION . '=');
            $this->setHtlinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('Moduleqantion')->getRegisteredViews()[23]
                . '&' . nebule::COMMAND_SELECT_WALLET . '=');
        } else {
            $this->setHtlinkCurrencyPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
            $this->setHtlinkTokenPoolPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
            $this->setHtlinkTokenPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
            $this->setHtlinkTransactionPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
            $this->setHtlinkWalletPrefix('?'
                . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getModule('ModuleObjects')->getDefaultView()
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=');
        }

        // Lit si la variable GET existe.
        if (filter_has_var(INPUT_GET, self::DEFAULT_INLINE_COMMAND)) {
            $this->_displayInline();
        } else {
            $this->_displayFull();
        }
    }


    /**
     * Affichage de la page complète.
     */
    protected function _displayFull(): void
    {
        $this->_metrologyInstance->addLog('Display full', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo $this->_currentDisplayLanguage; ?>">
        <head>
            <meta charset="utf-8"/>
            <title><?php echo Application::APPLICATION_NAME . ' - ' . $this->_nebuleInstance->getCurrentEntityInstance()->getFullName('all'); ?></title>
            <link rel="icon" type="image/png" href="favicon.png"/>
            <meta name="keywords" content="<?php echo Application::APPLICATION_SURNAME; ?>"/>
            <meta name="description" content="<?php echo Application::APPLICATION_NAME . ' - ';
            $this->_traductionInstance->echoTraduction('::::HtmlHeadDescription'); ?>"/>
            <meta name="author" content="<?php echo Application::APPLICATION_AUTHOR . ' - ' . Application::APPLICATION_WEBSITE; ?>"/>
            <meta name="licence" content="<?php echo Application::APPLICATION_LICENCE; ?>"/>
            <?php
            $this->_metrologyInstance->addLog('Display css', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->commonCSS();
            $this->displayCSS();

            $this->_metrologyInstance->addLog('Display vbs', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
            $this->_displayScripts();
            ?>

        </head>
        <body>
        <?php
        $this->_metrologyInstance->addLog('Display actions', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_displayActions();

        $this->_metrologyInstance->addLog('Display header', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_displayHeader();

        $this->_metrologyInstance->addLog('Display menu apps', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_displayMenuApplications();
        ?>

        <div class="layout-main">
            <div class="layout-content">
                <div id="curseur" class="infobulle"></div>
                <div class="content">
                    <?php
                    $this->_metrologyInstance->addLog('Display checks', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                    $this->_displayChecks();

                    $this->_metrologyInstance->addLog('Display content', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                    $this->_displayContent();

                    $this->_metrologyInstance->addLog('Display metrology', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
                    $this->_displayMetrology();
                    ?>

                </div>
            </div>
        </div>
        <?php
        $this->_metrologyInstance->addLog('Display footer', Metrology::LOG_LEVEL_DEBUG, __FUNCTION__, '00000000'); // Log
        $this->_displayFooter();
    }


    /**
     * Affichage de la partie de page en ligne.
     */
    protected function _displayInline(): void
    {
        $this->_metrologyInstance->addLog('Display inline', Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log

        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->displayInline();
                echo "\n";
            }
        }
    }


    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $bgobj = $this->_nebuleInstance->newObject(self::DEFAULT_CSS_BACKGROUND);
        $background = $bgobj->getUpdateNID(true, false);
        ?>

        <style type="text/css">
            html, body {
                font-family: Sans-Serif, monospace, Arial, Helvetica;
                font-stretch: condensed;
                font-size: 0.9em;
                text-align: left;
                min-height: 100%;
                width: 100%;
            }

            @media screen {
                body {
                    background: #f0f0f0 url("<?php echo 'o/'.$background; ?>") no-repeat;
                    background-position: center center;
                    background-size: cover;
                    background-attachment: fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                }
            }

            @media print {
                body {
                    background: #ffffff;
                }
            }

            .logoFloatLeft {
                float: left;
                margin: 5px;
                height: 64px;
                width: 64px;
            }

            .floatLeft {
                float: left;
                margin-right: 5px;
            }

            .floatRight {
                float: right;
                margin-left: 5px;
            }

            .textAlignLeft {
                text-align: left;
            }

            .textAlignRight {
                text-align: right;
            }

            .textAlignCenter {
                text-align: center;
            }

            .iconInlineDisplay {
                height: 16px;
                width: 16px;
            }

            .iconNormalDisplay {
                height: 64px;
                width: 64px;
            }

            .hideOnSmallMedia {
                display: block;
            }

            .divHeaderH1, .divHeaderH2 {
                overflow: hidden;
            }

            .layout-header {
                height: 74px;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-header {
                border-bottom-style: solid;
                border-bottom-color: #222222;
                border-bottom-width: 5px;
            }

            .layout-footer {
                height: auto;
                background: rgba(34, 34, 34, 0.8);
                color: #ffffff;
            }

            .layout-footer {
                border-top-style: solid;
                border-top-color: #222222;
                border-top-width: 5px;
            }

            .header-left {
                margin: 5px 0 5px 5px;
            }

            .header-left2 {
                margin: 5px;
                height: 64px;
                width: 207px;
                float: left;
            }

            .header-left2 img {
                margin-right: 5px;
            }

            .layout-header .layoutObject {
                float: left;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText .objectTitleMediumName {
                overflow: visible;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText {
                background: none;
                color: #ffffff;
            }

            .layout-header .layoutObject .objectTitle .objectTitleText a {
                color: #ffffff;
            }

            .header-right {
                margin: 5px;
            }

            .text a {
                color: #000000;
            }

            .menuListContentAction {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .messaeMenuListContentActionModules {
                background: rgba(0, 0, 0, 0.5);
                color: #ffffff;
            }

            .messaeMenuListContentActionHooks {
                background: rgba(255, 255, 255, 0.66);
            }

            /* Liserets de verrouillage. */
            .headerUnlock {
                border-bottom-color: #e0000e;
            }

            .footerUnlock {
                border-top-color: #e0000e;
            }

            /* Le menu de gauche, contextuel et toujours visible même partiellement. */
            .layout-menu-left {
                display: inline;
                min-width: 217px;
                max-width: 217px;
                min-height: 100px;
                margin: 0;
                margin-right: 10px;
                margin-bottom: 20px;
                margin-top: 120px;
            }

            @media screen and (max-width: 1020px) {
                .layout-menu-left {
                    min-width: 74px;
                    max-width: 74px;
                    margin-right: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .layout-menu-left {
                    min-width: 32px;
                    max-width: 32px;
                    margin-right: 5px;
                }
            }

            .menu-left {
                width: 217px;
                color: #000000;
                background: rgba(255, 255, 255, 0.2);
                background-origin: border-box;
            }

            .menu-left img {
                height: 64px;
                width: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left {
                    width: 74px;;
                }

                .menu-left img {
                    height: 64px;
                    width: 64px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left {
                    width: 32px;
                }

                .menu-left img {
                    height: 32px;
                    width: 32px;
                }
            }

            .menu-left-module {
                height: 64px;
                width: 207px;
                padding: 5px;
                margin-bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                background-origin: border-box;
                color: #ffffff;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-module {
                    height: 64px;
                    width: 64px;
                    padding: 5px;
                    margin-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-module {
                    height: 32px;
                    width: 32px;
                    padding: 0;
                    margin-bottom: 5px;
                }
            }

            .menu-left-links {
                width: 207px;
                padding: 5px;
                padding-bottom: 0;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-links {
                    width: 64px;
                    padding: 5px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-links {
                    width: 32px;
                    padding: 0;
                    padding-bottom: 0;
                }
            }

            .menu-left-interlinks {
                height: 15px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-interlinks {
                    height: 10px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-interlinks {
                    height: 5px;
                }
            }

            .menu-left-one {
                clear: both;
                padding-bottom: 5px;
                width: 207px;
                min-height: 64px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-one {
                    min-height: 64px;
                    width: 64px;
                    padding-bottom: 5px;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-one {
                    min-height: 32px;
                    width: 32px;
                    padding-bottom: 0;
                }
            }

            .menu-left-icon {
                float: left;
                margin-right: 5px;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-icon {
                    margin-right: 0;
                }
            }

            .menu-left-modname p {
                font-size: 0.6em;
                font-style: italic;
            }

            .menu-left-title p {
                font-size: 1.1em;
                font-weight: bold;
            }

            .menu-left-text p {
                font-size: 0.8em;
            }

            @media screen and (max-width: 1020px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-height: 500px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            @media screen and (max-width: 575px) {
                .menu-left-modname, .menu-left-title, .menu-left-text {
                    display: none;
                }

                .menu-left-icon {
                    margin-right: 0;
                }
            }

            /* Correction affichage objets */
            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 256px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 343px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 511px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 767px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 943px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1343px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1663px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1791px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2143px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3583px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3839px;
                }
            }

            .objectsListContent {
                background: rgba(255, 255, 255, 0.1);
            }

            .informationDisplayMessage {
                background: rgba(0, 0, 0, 0.5);
            }

            .informationDisplayInfo {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContentDiv {
                background: rgba(255, 255, 255, 0.5);
            }

            .titleContent h1 {
                color: #333333;
            }
        </style>
        <?php

        // Ajout de la partie CSS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }


    /**
     * Affichage des scripts JS.
     */
    private function _displayScripts(): void
    {
        $this->commonScripts();

        // Ajout de la partie script JS du module en cours d'utilisation, si présent.
        foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                $module->headerScript();
                echo "\n";
            }
        }
    }


    /**
     * Affichage des actions.
     */
    private function _displayActions(): void
    {
        ?>

        <div class="layout-footer footer<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <p>
                <?php
                // Vérifie le ticket.
                if ($this->_nebuleInstance->getTicketingInstance()->checkActionTicket()) {
                    // Appelle les actions spéciales.
                    $this->_actionInstance->specialActions();

                    // Appelle les actions génériques.
                    $this->_actionInstance->genericActions();

                    // Appelle les actions du module concerné par le mode d'affichage.
                    foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                        if ($module->getCommandName() == $this->_currentDisplayMode) {
                            $this->_metrologyInstance->addLog('Actions for module ' . $module->getCommandName(), Metrology::LOG_LEVEL_NORMAL, __FUNCTION__, '00000000'); // Log
                            $module->action();
                        }
                    }
                }
                ?>

            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la barre supérieure.
     *
     * La partie gauche présente le menu et l'entité en cours.
     *
     * La partie droite présente :
     * - Un ok si tout se passe bien.
     * - Un warning si il y a un problème. En mode rescue, on peut quand même verrouiller/déverrouiller l'entité.
     * - Une erreur si il y a un gros problème. Il n'est pas possible de déverrouiller l'entité.
     */
    private function _displayHeader(): void
    {
        ?>

        <div class="layout-header header<?php if ($this->_unlocked) {
            echo 'Unlock';
        } ?>">
            <div class="header-left">
                <?php
                if ($this->_configuration->getOptionAsBoolean('permitJavaScript')) {
                    ?>
                    <img src="<?php echo self::DEFAULT_APPLICATION_LOGO; ?>" alt="[M]"
                         title="<?php echo $this->_traductionInstance->getTraduction('::menu'); ?>"
                         onclick="display_menu('layout-menu-applications');"/>
                    <?php
                } else {
                    ?>
                    <a href="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->getCurrentDisplayMode() . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW; ?>=menu">
                        <img src="<?php echo self::DEFAULT_APPLICATION_LOGO; ?>" alt="[M]"
                             title="<?php echo $this->_traductionInstance->getTraduction('::menu'); ?>"/>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
            // Affiche l'entité et son image.
            $param = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => true,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'short',
                'enableDisplayJS' => false,
                'enableDisplayObjectActions' => false,
            );
            if ($this->_unlocked) {
                $param['flagUnlockedLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth&' . ModuleEntities::COMMAND_LOGOUT_ENTITY
                    . '&' . nebule::COMMAND_FLUSH;
            } else {
                $param['flagUnlockedLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth'
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity();
            }
            echo $this->getDisplayObject($this->_nebuleInstance->getCurrentEntity(), $param);
            ?>

            <div class="header-right">
                <?php
                if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
                    echo "&nbsp;\n";
                } // Si un test est en warning maximum.
                elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN') {
                    // Si mode rescue et en warning.
                    if ($this->_nebuleInstance->getModeRescue()) {
                        // Si l'entité est déverrouillées.
                        if ($this->_unlocked) {
                            // Affiche le lien de verrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_iconIwarn, 'Etat déverrouillé, verrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleHelp')->getCommandName()
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth&' . ModuleEntities::COMMAND_LOGOUT_ENTITY
                                . '&' . nebule::COMMAND_FLUSH);
                        } else {
                            // Affiche de lien de déverrouillage sans les effets.
                            $this->displayHypertextLink(
                                $this->convertUpdateImage(
                                    $this->_iconIwarn, 'Etat verrouillé, déverrouiller ?',
                                    '',
                                    '',
                                    'name="ico_lock"'),
                                '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleHelp')->getCommandName()
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=auth&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity());
                        }
                    } // Sinon affiche le warning.
                    else {
                        $this->displayHypertextLink(
                            $this->convertUpdateImage(
                                self::DEFAULT_ICON_IWARN,
                                'WARNING'),
                            '?' . ModuleEntities::COMMAND_LOGOUT_ENTITY
                            . '&' . ModuleEntities::COMMAND_SWITCH_TO_ENTITY);
                    }
                } // Sinon c'est une erreur.
                else {
                    $this->displayHypertextLink(
                        $this->convertUpdateImage(
                            self::DEFAULT_ICON_IERR,
                            'ERROR'),
                        '?' . ModuleEntities::COMMAND_LOGOUT_ENTITY
                        . '&' . nebule::COMMAND_FLUSH);
                }
                ?>

            </div>
            <div class="header-center">
                <?php $this->_displayHeaderCenter(); ?>

            </div>
        </div>
        <?php
    }


    /**
     * Affiche la partie centrale de l'entête.
     * Non utilisé.
     */
    private function _displayHeaderCenter(): void
    {
        //...
    }


    /**
     * Affiche le menu des applications.
     */
    private function _displayMenuApplications(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false) {
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;
        }
        ?>

        <div class="layout-menu-applications" id="layout-menu-applications">
            <div class="menu-applications-sign">
                <img alt="<?php echo Application::APPLICATION_NAME; ?>" src="<?php echo self::DEFAULT_APPLICATION_LOGO; ?>"/><br/>
                <?php echo Application::APPLICATION_NAME; ?><br/>
                (c) <?php echo Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR; ?><br/>
                <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction('::Version');
                echo ' : ' . Application::APPLICATION_VERSION; ?><br/>
                <a href="<?php echo $linkApplicationWebsite; ?>" target="_blank"><?php echo Application::APPLICATION_WEBSITE; ?></a>
            </div>
            <div class="menu-applications-logo">
                <img src="<?php echo self::DEFAULT_APPLICATION_LOGO; ?>" alt="[M]"
                     title="<?php echo $this->_applicationInstance->getTraductionInstance()->getTraduction('::menu'); ?>"
                     onclick="display_menu('layout-menu-applications');"/>
            </div>
            <div class="menu-applications">
                <?php
                $this->_displayInternalMenuApplications();
                ?>

            </div>
        </div>
        <?php
    }

    /**
     * Affiche le menu des applications.
     */
    private function _displayInternalMenuApplications(): void
    {
        $modules = $this->_applicationInstance->getModulesListInstances();
        $list = array();
        $j = 0;
        $currentModuleName = 'noModuleFind-';

        // Appelle les actions du module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() == $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());

                // Mémorise le nom du module trouvé.
                $currentModuleName = $module->getMenuName();

                // Affiche le lien du menu seul (sans JS).
                if ($this->_currentDisplayView != 'menu') {
                    $list[$j]['icon'] = self::DEFAULT_LOGO_MODULE;
                    $list[$j]['title'] = $this->_applicationInstance->getTraductionInstance()->getTraduction('::menu', $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=menu';
                    $list[$j]['desc'] = $this->_applicationInstance->getTraductionInstance()->getTraduction('::menuDesc', $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                    $list[$j]['ref'] = Application::APPLICATION_NAME;
                    $list[$j]['class'] = 'messaeMenuListContentActionModules';
                    $j++;
                }

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('selfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le module concerné par le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList($currentModuleName . 'SelfMenu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle les actions d'autres modules pour le mode d'affichage.
        foreach ($modules as $module) {
            if ($module->getCommandName() != $this->_currentDisplayMode) {
                // Extrait le nom du module.
                $moduleName = $module->getTraduction($module->getMenuName(), $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());

                // Liste les points d'encrages à afficher.
                $appHookList = $module->getHookList('menu');
                if (sizeof($appHookList) != 0) {
                    foreach ($appHookList as $appHook) {
                        if ($appHook['name'] != '') {
                            $icon = $appHook['icon'];
                            if ($icon == '') {
                                $icon = $module->getLogo();
                            }
                            if ($icon == '') {
                                $icon = self::DEFAULT_ICON_IMLOG;
                            }
                            $desc = $module->getTraduction($appHook['desc'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            if ($desc == '') {
                                $desc = '&nbsp;';
                            }

                            $list[$j]['icon'] = $icon;
                            $list[$j]['title'] = $module->getTraduction($appHook['name'], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                            $list[$j]['htlink'] = $appHook['link'];
                            $list[$j]['desc'] = $desc;
                            $list[$j]['ref'] = $moduleName;
                            $list[$j]['class'] = 'messaeMenuListContentActionHooks';
                            $j++;
                        }
                    }
                }
            }
        }

        // Appelle la liste des modules.
        foreach ($modules as $module) {
            // Extrait le nom du module.
            $moduleName = $module->getTraduction($module->getName(), $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());

            // Liste les options à afficher.
            $appTitleList = $module->getAppTitleList();
            if (sizeof($appTitleList) != 0) {
                $appIconList = $module->getAppIconList();
                $appDescList = $module->getAppDescList();
                $appViewList = $module->getAppViewList();
                for ($i = 0; $i < sizeof($appTitleList); $i++) {
                    $icon = $appIconList[$i];
                    if ($icon == '') {
                        $icon = $module->getLogo();
                    }
                    if ($icon == '') {
                        $icon = self::DEFAULT_ICON_LSTOBJ;
                    }
                    $desc = $module->getTraduction($appDescList[$i], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                    if ($desc == '') {
                        $desc = '&nbsp;';
                    }

                    $list[$j]['icon'] = $icon;
                    $list[$j]['title'] = $module->getTraduction($appTitleList[$i], $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
                    $list[$j]['htlink'] = '?' . self::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $module->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $appViewList[$i];
                    $list[$j]['desc'] = $desc;
                    $list[$j]['ref'] = $moduleName;
                    $list[$j]['class'] = 'messaeMenuListContentActionModules';
                    $j++;
                }
            }
        }

        // Ajoute l'application 0.
        $list[$j]['icon'] = parent::DEFAULT_APPLICATION_LOGO;
        $list[$j]['title'] = BOOTSTRAP_NAME;
        $list[$j]['htlink'] = '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP;
        $list[$j]['desc'] = $this->_applicationInstance->getTraductionInstance()->getTraduction('::appSwitch', $this->_applicationInstance->getTraductionInstance()->getCurrentLanguage());
        $list[$j]['ref'] = 'nebule';
        $list[$j]['class'] = 'messaeMenuListContentActionModules';

        echo $this->getDisplayMenuList($list, 'Medium');
    }


    /**
     * Affiche les alertes.
     */
    private function _displayChecks(): void
    {
        if ($this->_nebuleInstance->getModeRescue()) {
            $this->displayMessageWarning('::::RESCUE');
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoHash() == 'ERROR') {
            $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoHashMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoSym() == 'ERROR') {
            $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoSymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityCryptoAsym() == 'ERROR') {
            $this->displayMessageError($this->_applicationInstance->getCheckSecurityCryptoAsymMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'ERROR') {
            $this->displayMessageError($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityBootstrap() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityBootstrapMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecuritySign() == 'ERROR') {
            $this->displayMessageError($this->_applicationInstance->getCheckSecuritySignMessage());
        }
        if ($this->_applicationInstance->getCheckSecurityURL() == 'WARN') {
            $this->displayMessageWarning($this->_applicationInstance->getCheckSecurityURLMessage());
        }
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')) {
            $this->displayMessageWarning(':::warn_ServNotPermitWrite');
        }
        if ($this->_nebuleInstance->getFlushCache()) {
            $this->displayMessageWarning(':::warn_flushSessionAndCache');
        }
    }

    /**
     * Contenu de la page.
     *
     * Affiche le contenu des pages en fonction du mode demandé.
     * Un seul mode est pris en compte pour l'affichage, les autres sont ignorés.
     *
     * Le traitement de l'affichage de la vue est faite par le module gérant le mode d'affichage.
     *
     * Seule exception, la vue 'menu' est traitée comme un affichage du menu sans JS et sans passer directement par un module.
     * Le contenu du menu est lui dépendant du module en cours et de certaines réponses des autres modules.
     */
    private function _displayContent(): void
    {
        if ($this->_currentDisplayView == 'menu') {
            $this->_displayInternalMenuApplications();
        } else {
            foreach ($this->_applicationInstance->getModulesListInstances() as $module) {
                if ($module->getCommandName() == $this->_currentDisplayMode) {
                    $module->display();
                }
            }
        }
        $this->_displayInlineContentID();
    }


    /**
     * Affiche la métrologie.
     */
    private function _displayMetrology(): void
    {
        if ($this->_configuration->getOptionUntyped('messaeDisplayMetrology')) {
            ?>

            <?php $this->displayDivTextTitle(self::DEFAULT_ICON_IMLOG, 'Métrologie', 'Mesures quantitatives et temporelles.') ?>
            <div class="text">
                <p>
                    <?php
                    //		aff_title('::bloc_metrolog','imlog');
                    // Affiche les valeurs de la librairie.
                    /*		echo 'Bootstrap : ';
		$this->_traductionInstance->echoTraduction('%01.0f liens lus,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkRead()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f liens vérifiés,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkVerify()); echo ' ';
		$this->_traductionInstance->echoTraduction('%01.0f objets vérifiés.','',$this->_bootstrapInstance->getMetrologyInstance()->getObjectVerify()); echo "<br />\n";*/
                    echo 'Lib nebule : ';
                    $this->_traductionInstance->echoTraduction('%01.0f liens lus,', '', $this->_metrologyInstance->getLinkRead());
                    echo ' ';
                    $this->_traductionInstance->echoTraduction('%01.0f liens vérifiés,', '', $this->_metrologyInstance->getLinkVerify());
                    echo ' ';
                    $this->_traductionInstance->echoTraduction('%01.0f objets lus.', '', $this->_metrologyInstance->getObjectRead());
                    echo ' ';
                    $this->_traductionInstance->echoTraduction('%01.0f objets vérifiés.', '', $this->_metrologyInstance->getObjectVerify());
                    echo "<br />\n";
                    // Calcul de temps de chargement de la page - stop
                    /*		$bootstrapTimeList = $this->_bootstrapInstance->getMetrologyInstance()->getTimeArray();
		$bootstrap_time_total = 0;
		foreach ( $bootstrapTimeList as $time )
		{
			$bootstrap_time_total = $bootstrap_time_total + $time;
		}
		$this->_traductionInstance->echoTraduction('Le bootstrap à pris %01.4fs pour appeler la page.','',$bootstrap_time_total);
		echo ' (';
		foreach ( $bootstrapTimeList as $time )
		{
			echo sprintf(" %1.4fs", $time);
		}
		echo " )\n";
		echo "<br />\n";*/
                    $this->_metrologyInstance->addTime();
                    $messaeTimeList = $this->_metrologyInstance->getTimeArray();
                    $messae_time_total = 0;
                    foreach ($messaeTimeList as $time) {
                        $messae_time_total = $messae_time_total + $time;
                    }
                    $this->_traductionInstance->echoTraduction('Le serveur à pris %01.4fs pour calculer la page.', '', $messae_time_total);
                    echo ' (';
                    foreach ($messaeTimeList as $time) {
                        echo sprintf(" %1.4fs", $time);
                    }
                    echo " )\n";
                    ?>

                </p>
            </div>
            <?php
        }
    }

    // Affiche la fin de page.
    private function _displayFooter(): void
    {
        ?>

        </body>
        </html>
        <?php
    }


    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1($object, $help = '', $desc = ''): void
    {
        $object = $this->_nebuleInstance->convertIdToTypedObjectInstance($object);
        // Prépare le type mime.
        $typemime = $object->getType('all');
        if ($desc == '') {
            $desc = $this->_applicationInstance->getTraductionInstance()->getTraduction($typemime);
        }

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == Entity::ENTITY_TYPE && strpos($objHead, nebule::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) {
            $object = $this->_nebuleInstance->newEntity($object->getID());
        }
        if ($isGroup && !is_a($object, 'Group')) {
            $object = $this->_nebuleInstance->newGroup($object->getID());
        }
        if ($isConversation && !is_a($object, 'Conversation')) {
            $object = $this->_nebuleInstance->newConversation($object->getID());
        }

        $name = $object->getFullName('all');
        $present = $object->checkPresent();

        // Si le contenu est présent.
        if ($present) {
            // Prépare l'état de l'objet.
            $status = 'ok';
            $content = $object->getContent(0);
            $tooBig = false;
            if ($content == null) {
                $status = 'tooBig';
            }
            unset($content);
        } elseif ($isConversation
            || $isGroup
        ) {
            $status = 'notAnObject';
        } else {
            $status = 'notPresent';
        }
        if ($object->getMarkWarning()) {
            $status = 'warning';
        }
        if ($object->getMarkDanger()) {
            $status = 'danger';
        }
        // Prépare l'aide en ligne.
        if ($help == '') {
            if ($isEntity) {
                $help = ':::display:default:help:Entity';
            } elseif ($isConversation) {
                $help = ':::display:default:help:Conversation';
            } elseif ($isGroup) {
                $help = ':::display:default:help:Group';
            } else {
                $help = ':::display:default:help:Object';
            }
        }
        ?>

        <div class="textTitle">
            <?php
            $this->_displayDivOnlineHelp($help);
            ?>

            <div class="floatRight">
                <?php
                switch ($status) {
                    case 'notPresent':
                        $msg = $this->_traductionInstance->getTraduction(':::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configuration->getOptionUntyped('messaeDisplayUnverifyLargeContent')) {
                            $msg = $this->_traductionInstance->getTraduction(':::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_traductionInstance->getTraduction(':::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_traductionInstance->getTraduction(':::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_traductionInstance->getTraduction(':::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_traductionInstance->getTraduction(':::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_traductionInstance->getTraduction(':::display:content:OK');
                        $this->displayIcon(self::DEFAULT_ICON_IOK, $msg, 'iconNormalDisplay');
                        break;
                }
                unset($msg);
                ?>

            </div>
            <div style="float:left;">
                <?php
                $this->displayObjectColorIcon($object);
                ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $name; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php
        unset($name, $typemime, $isEntity, $isGroup, $isConversation);
    }

    /**
     * Affiche dans les barres de titres l'icône d'aide contextuelle.
     * @param string $help
     */
    private function _displayDivOnlineHelp($help): void
    {
        // Si authorisé à afficher l'aide.
        if ($this->_configuration->getOptionUntyped('messaeDisplayOnlineHelp')) {
            // Prépare le texte à afficher dans la bulle.
            $txt = $this->_applicationInstance->getTraductionInstance()->getTraduction($help);
            $txt = str_replace('&', '&amp;', $txt);
            $txt = str_replace('"', '&quot;', $txt);
            $txt = str_replace("'", '&acute;', $txt);
            //$txt = str_replace('<','&lt;',$txt);
            $txt = str_replace("\n", ' ', $txt);
            // Prépare l'extension de lien.
            $linkext = 'onmouseover="montre(\'<b>' . $this->_applicationInstance->getTraductionInstance()->getTraduction('Aide') . ' :</b><br />' . $txt . '\');" onmouseout="cache();"';
            unset($txt);
            // Affiche la bulle et le texte.
            ?>

            <div style="float:right;">
                <?php
                $image = $this->prepareIcon(self::DEFAULT_ICON_HELP);
                ?>

                <img alt="[]" src="<?php echo $image; ?>" class="iconNormalDisplay"
                     id="curseur" <?php echo $linkext; ?> />
            </div>
            <?php
            unset($linkext, $image);
        }
    }


    /**
     * Affiche le titre pour un paragraphe de texte. Par défaut, affiche le titre H1.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitle($icon, $title = '', $desc = '', $help = ''): void
    {
        $this->displayDivTextTitleH1($icon, $title, $desc, $help);
    }

    /**
     * Affiche le titre H1 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH1($icon, $title = '', $desc = '', $help = ''): void
    {
        ?>

        <div class="textTitle">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTraductionInstance()->getTraduction($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTraductionInstance()->getTraduction($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h1 class="divHeaderH1"><?php echo $title; ?></h1>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

    }

    /**
     * Affiche le titre H2 pour un paragraphe de texte.
     *
     * @param string $icon
     * @param string $title
     * @param string $desc
     * @param string $help
     * @return void
     */
    public function displayDivTextTitleH2($icon, $title = '', $desc = '', $help = ''): void
    {
        ?>

        <div class="textTitle2">
            <?php
            if ($title != '') {
                $title = $this->_applicationInstance->getTraductionInstance()->getTraduction($title);
            }

            if ($desc == '') {
                $desc = '-';
            } else {
                $desc = $this->_applicationInstance->getTraductionInstance()->getTraduction($desc);
            }

            $this->_displayDivOnlineHelp($help);
            ?>

            <div style="float:left;">
                <?php $this->displayUpdateImage($icon, $title, 'iconegrandepuce'); ?>

            </div>
            <h2 class="divHeaderH2"><?php echo $title; ?></h2>
            <p class="hideOnSmallMedia"><?php echo $desc; ?></p>
        </div>
        <?php

    }
}


/**
 * Classe Action
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Action extends Actions
{
    // Tout par défaut.
}


/**
 * Classe Traduction
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class Traduction extends Traductions
{
    /**
     * Langue par défaut.
     *
     * @var string
     */
    protected $DEFAULT_LANGUAGE = 'fr-fr';

    /**
     * @var \Nebule\Library\Configuration
     */
    private $_configuration;


    /**
     * Constructeur.
     *
     * @param Application $applicationInstance
     * @return void
     */
    public function __construct(Application $applicationInstance)
    {
        parent::__construct($applicationInstance);
        $this->_configuration = $applicationInstance->getNebuleInstance()->getConfigurationInstance();
    }

    // Tout par défaut.
}


/**
 * Cette application permet d'afficher l'aide et la page par défaut de messae.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleHelp extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::messae:module:help:ModuleName';
    protected $MODULE_MENU_NAME = '::messae:module:help:MenuName';
    protected $MODULE_COMMAND_NAME = 'hlp';
    protected $MODULE_DEFAULT_VIEW = '1st';
    protected $MODULE_DESCRIPTION = '::messae:module:help:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2016-2020';
    protected $MODULE_LOGO = '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d';
    protected $MODULE_HELP = '::messae:module:help:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('1st', 'hlp', 'lang', 'about');
    protected $MODULE_REGISTERED_ICONS = array(
        '1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d',    // 0 : icône d'aide.
        '47e168b254f2dfd0a4414a0b96f853eed3df0315aecb8c9e8e505fa5d0df0e9c',    // 1 : module
        'd7f68db0a1d0977fb8e521fd038b18cd601946aa0e26071ff8c02c160549633b',    // 2 : bootstrap (metrologie)
        '3638230cde600865159d5b5f7993d8a3310deb35aa1f6f8f57429b16472e03d6',    // 3 : world
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1',    // 4 : lister entités
    );
    protected $MODULE_APP_TITLE_LIST = array('::messae:module:help:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('1543e2549dc52d2972a5b444a4d935360a97c125b72c6946ae9dc980077b8b7d');
    protected $MODULE_APP_DESC_LIST = array('::messae:module:help:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('hlp');


    /**
     * Configuration spécifique au module.
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        if ($object == 'none') {
            $object = $this->_applicationInstance->getCurrentObject();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        $hookArray = array();
        switch ($hookName) {
            case 'menu':
                $hookArray[0]['name'] = '::messae:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::messae:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                break;
            case 'selfMenu':
                // Affiche l'aide.
                $hookArray[0]['name'] = '::messae:module:help:AppTitle1';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[0]['desc'] = '::messae:module:help:AppDesc1';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];

                // Choix de la langue.
                $hookArray[1]['name'] = '::messae:module:help:Langue';
                $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                $hookArray[1]['desc'] = '::messae:module:help:ChangerLangue';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];

                // A propos.
                $hookArray[2]['name'] = '::messae:module:help:About';
                $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3];

                // Bootstrap.
                $hookArray[3]['name'] = '::messae:module:help:Bootstrap';
                $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Actions::DEFAULT_COMMAND_NEBULE_BOOTSTRAP;
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     */
    protected function _displayFull(): void
    {
        $this->_displayHlpHeader();
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case '1st':
                $this->_displayHlpFirst();
                break;
            case 'hlp':
                $this->_displayHlpHelp();
                break;
            case 'lang':
                $this->_displayHlpLang();
                break;
            case 'about':
                $this->_displayHlpAbout();
                break;
            default:
                $this->_displayHlpFirst();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     */
    protected function _displayInline(): void
    {
        // Rien à faire.
    }

    /**
     * Affichage de surcharges CSS.
     */
    public function headerStyle(): void
    {
        ?>

        .messaeModuleHelpText1st { margin:10%; }
        <?php
    }


    /**
     * Affichage de l'entête des pages.
     */
    private function _displayHlpHeader(): void
    {
    }


    /**
     * Affichage de la page par défaut.
     */
    private function _displayHlpFirst(): void
    {
        // Affiche le titre.
        echo $this->_display->getDisplayTitle($this->_applicationInstance->getTraductionInstance()->getTraduction('::SelectUser'), $this->MODULE_REGISTERED_ICONS[4]);

        // Liste des entités déjà affichées.
        $listOkEntities = $this->_nebuleInstance->getSpecialEntities();

        // Liste les entités marquées comme connu.
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            $this->_applicationInstance->getCurrentEntity(),
            '',
            'f',
            $this->_applicationInstance->getCurrentEntity(),
            '',
            '');

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_nebuleInstance->newEntity($link->getParsed()['bl/rl/nid2']);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkEntities);
    }


    /**
     * Affichage de la page de choix de la langue.
     */
    private function _displayHlpLang(): void
    {
        $module = $this->_applicationInstance->getTraductionInstance()->getCurrentLanguageInstance();

        // Affiche la langue en cours.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => true,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
            'icon' => $module->getLogo(),
        );
        echo $this->_display->getDisplayInformation($module->getTraduction($module->getName()), $param);

        // Affiche le titre.
        echo $this->_display->getDisplayTitle($this->_applicationInstance->getTraductionInstance()->getTraduction('::ChangeLanguage'), $this->MODULE_REGISTERED_ICONS[3]);

        // Affiche la liste des langues.
        echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('helpLanguages', 'Medium');
    }


    /**
     * Affichage de l'aide générale.
     */
    private function _displayHlpHelp(): void
    {
        ?>
        <div class="text">
            <p>
                <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction('::messae:module:help:AideGenerale:Text') ?>
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la page à propos.
     */
    private function _displayHlpAbout(): void
    {
        $linkApplicationWebsite = Application::APPLICATION_WEBSITE;
        if (strpos(Application::APPLICATION_WEBSITE, '://') === false)
            $linkApplicationWebsite = 'http://' . Application::APPLICATION_WEBSITE;

        // Affiche les informations de l'application.
        $param = array(
            'enableDisplayIcon' => true,
            'enableDisplayAlone' => false,
            'informationType' => 'information',
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list = array();
        $list[0]['information'] = Application::APPLICATION_NAME;
        $list[0]['param'] = $param;
        $list[0]['param']['icon'] = Displays::DEFAULT_APPLICATION_LOGO;
        $list[0]['object'] = '1';
        $list[1]['information'] = $this->_applicationInstance->getTraductionInstance()->getTraduction('::Version') . ' : ' . Application::APPLICATION_VERSION;
        $list[1]['param'] = $param;
        $list[2]['information'] = Application::APPLICATION_LICENCE . ' ' . Application::APPLICATION_AUTHOR;
        $list[2]['param'] = $param;
        $list[3]['information'] = '<a href="' . $linkApplicationWebsite . '" target="_blank">' . Application::APPLICATION_WEBSITE . '</a>';
        $list[3]['param'] = $param;
        echo $this->_display->getDisplayObjectsList($list, 'Medium');

        ?>
        <div class="text">
            <p>
                <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction('::messae:module:help:APropos:Text') ?>
            </p>
            <p>
                <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction('::messae:module:help:APropos:Liens') ?>
            </p>
        </div>
        <?php
        if ($this->_unlocked && $this->_configuration->getOptionUntyped('messaeDisplayOnlineOptions')) {
            ?>
            <div class="text">
                <p>
                    <b>messae :</b><br/>
                    <?php
                    $options = array('messaeDisplayOnlineHelp', 'messaeDisplayOnlineOptions',
                        'messaeDisplayMetrology', 'messaeDisplayUnsecureURL',
                        'messaeDisplayUnverifyLargeContent', 'messaeDisplayNameSize',
                        'messaeIOReadMaxDataPHP', 'messaePermitUploadObject', 'messaePermitUploadLinks',
                        'messaePermitPublicUploadObject', 'messaePermitPublicUploadLinks', 'messaeLogUnlockEntity',
                        'messaeLogLockEntity', 'messaeLoadModules', 'default',
                    );
                    foreach ($options as $option) {
                        echo $option . ' : ';
                        var_dump($this->_configuration->getOptionUntyped($option));
                        echo "<br />\n";
                    }
                    ?>
                </p>
            </div>
            <?php
        }
    }


    /**
     * Initialisation de la table de traduction.
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::messae:module:help:ModuleName'] = "Module d'aide";
        $this->_table['en-en']['::messae:module:help:ModuleName'] = 'Help module';
        $this->_table['es-co']['::messae:module:help:ModuleName'] = 'Módulo de ayuda';
        $this->_table['fr-fr']['::messae:module:help:MenuName'] = 'Aide';
        $this->_table['en-en']['::messae:module:help:MenuName'] = 'Help';
        $this->_table['es-co']['::messae:module:help:MenuName'] = 'Ayuda';
        $this->_table['fr-fr']['::messae:module:help:ModuleDescription'] = "Module d'aide en ligne.";
        $this->_table['en-en']['::messae:module:help:ModuleDescription'] = 'Online help module.';
        $this->_table['es-co']['::messae:module:help:ModuleDescription'] = 'Módulo de ayuda en línea.';
        $this->_table['fr-fr']['::messae:module:help:ModuleHelp'] = "Cette application permet d'afficher de l'aide générale sur l'interface.";
        $this->_table['en-en']['::messae:module:help:ModuleHelp'] = 'This application permit to display general help about the interface.';
        $this->_table['es-co']['::messae:module:help:ModuleHelp'] = 'Esta aplicatión te permite ver la ayuda general sobre la interfaz.';

        $this->_table['fr-fr']['::messae:module:help:AppTitle1'] = 'Aide';
        $this->_table['en-en']['::messae:module:help:AppTitle1'] = 'Help';
        $this->_table['es-co']['::messae:module:help:AppTitle1'] = 'Ayuda';
        $this->_table['fr-fr']['::messae:module:help:AppDesc1'] = "Affiche l'aide en ligne.";
        $this->_table['en-en']['::messae:module:help:AppDesc1'] = 'Display online help.';
        $this->_table['es-co']['::messae:module:help:AppDesc1'] = 'Muestra la ayuda en línea.';

        $this->_table['fr-fr']['::messae:module:help:Bienvenue'] = 'Bienvenue sur <b>messae</b>.';
        $this->_table['en-en']['::messae:module:help:Bienvenue'] = 'Welcome to <b>messae</b>.';
        $this->_table['es-co']['::messae:module:help:Bienvenue'] = 'Bienviedo en <b>messae</b>.';

        $this->_table['fr-fr']['::messae:module:help:Langue'] = 'Langue';
        $this->_table['en-en']['::messae:module:help:Langue'] = 'Language';
        $this->_table['es-co']['::messae:module:help:Langue'] = 'Idioma';
        $this->_table['fr-fr']['::messae:module:help:ChangerLangue'] = 'Changer de langue';
        $this->_table['en-en']['::messae:module:help:ChangerLangue'] = 'Change language';
        $this->_table['es-co']['::messae:module:help:ChangerLangue'] = 'Cambio de idioma';

        $this->_table['fr-fr']['::messae:module:help:About'] = 'A propos';
        $this->_table['en-en']['::messae:module:help:About'] = 'About';
        $this->_table['es-co']['::messae:module:help:About'] = 'About';

        $this->_table['fr-fr']['::messae:module:help:Bootstrap'] = 'Bootstrap';
        $this->_table['en-en']['::messae:module:help:Bootstrap'] = 'Bootstrap';
        $this->_table['es-co']['::messae:module:help:Bootstrap'] = 'Bootstrap';

        $this->_table['fr-fr']['::messae:module:help:Demarrage'] = 'Démarrage';
        $this->_table['en-en']['::messae:module:help:Demarrage'] = 'Start';
        $this->_table['es-co']['::messae:module:help:Demarrage'] = 'Comienzo';
        $this->_table['fr-fr']['::messae:module:help:AideGenerale'] = 'Aide générale';
        $this->_table['en-en']['::messae:module:help:AideGenerale'] = 'General help';
        $this->_table['es-co']['::messae:module:help:AideGenerale'] = 'Ayuda general';
        $this->_table['fr-fr']['::messae:module:help:APropos'] = 'A propos';
        $this->_table['en-en']['::messae:module:help:APropos'] = 'About';
        $this->_table['es-co']['::messae:module:help:APropos'] = 'Acerca';

        $this->_table['fr-fr']['::messae:module:help:APropos:Text'] = "Le projet messae est une implémentation logicielle basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.<br />
<br />
Les systèmes informatiques actuels sont incapables de gérer directement les objets et les liens. Il n’est donc pas possible d’utiliser nebule nativement.
Le projet messae permet un accès à cette nouvelle façon de gérer nos informations sans remettre en question fondamentalement l’organisation et notamment les systèmes d’exploitation de notre système d’information.<br />
<br />
L’interface messae est une page web destinée à être mise en place sur un serveur pour manipuler des objets et des liens.
Cela s’apparente tout à fait à ce qui se fait déjà communément : Google (et sa galaxie de sites), Facebook, Twitter, Outlook, Yahoo et leurs innombrables concurrents et prétendants…
Tous ces sites sont globalement plus concurrents que complémentaires, et tous sans exception sont fermés à leurs concurrents.
Cela se traduit pour l’utilisateur par la nécessité de, soit disposer d’un compte sur chaque site, soit de ne fréquenter que certains d’entre eux, voir un seul.
Cela se traduit aussi par l’impossibilité d’échanger directement des données et informations d’un site à l’autre.<br />
<br />
Le projet messae reproduit la concentration des données vers des serveurs sur l’Internet.
Il est nativement prévu pour pouvoir être implanté sur n’importe quel serveur web.
Et, se basant sur les principes de nebule, tout serveur hébergeant messae peut nativement :<br />
 1. gérer les identités générées par les autres serveurs, que ce soit un utilisateur ou un robot ;<br />
 2. échanger des données et des informations avec tous les autres serveurs implémentant nebule ;<br />
 3. relayer les données et les informations d’autres serveurs ;<br />
 4. permettre à tout utilisateur (connu du serveur) de s’y connecter.<br />
<br />
Grâce à IPv6, nous avons la possibilité de réellement connecter toutes les machines sur l’Internet.
Chacun peut ainsi mettre en place simplement messae chez lui, ou continuer à l’utiliser sur un autre serveur de l’Internet.
Chacun peut devenir individuellement actif.<br />
<br />
Enfin, si un jour nebule s’étend à toutes les machines et que toutes ces machines l’implémentent nativement, alors le projet messae disparaîtra.
Il aura rempli sa mission : permettre une transition douce vers nebule.<br />
Il sera vu comme bizarrerie symptomatique d’une époque.";

        $this->_table['en-en']['::messae:module:help:APropos:Text'] = 'The messae project is a software implementation based on nebule project.<br />
This php implementation is intended to be a reference of the potential of objects and relationships as defined in nebule.<br />
<br />
Current computer systems are unable to directly manage objects and links. It is thus not possible to use native nebule.
The messae project provides access to this new way of managing our information without questioning fundamentally the organization including the operating system of our information systems.<br />
<br />
The messae interface is a web page to be set up on a server to handle objects and links.
This all sounds a lot to what is already commonly exist: Google (and its galaxy of sites), Facebook, Twitter, Outlook, Yahoo and countless competitors and pretenders…
All these sites are generally more competitive than complementary, and all without exception are closed to competitors.
This means to the user by the need to either have an account on each site, or attend only some of them, to see one.
This also results in the inability to directly exchange data and information from one site to another.<br />
<br />
The project messae reproduced concentration data to servers on the Internet.
It is expected to be natively installed on any web server.
And, based on the principles of nebule, any server hosting messae can natively:<br />
 1. manage the identities generated by the other server, whether a user or a robot;<br />
 2. exchange data and information with all other servers implementing nebule;<br />
 3. relaying the data and the other data servers;<br />
 4. allow any user (known to the server) to connect to it.<br />
<br />
With IPv6, we have the ability to actually connect all the machines on the Internet.
Everyone can simply set up messae at home, or continue using another Internet server.
Each individual can become active.<br />
<br />
Finally, if one day nebule extends to all machines and all these machines implement it natively, then the project messae will disappear.
He will have served its purpose: to allow a smooth transition to nebule.<br />
It will be seen as symptomatic of an era oddity.';

        $this->_table['es-co']['::messae:module:help:APropos:Text'] = 'Sylabe El proyecto es un proyecto basado nebule implementación de software.<br />
Esta aplicación php está pensado como una referencia del potencial de los objetos y las relaciones como se define en nebule.<br />
<br />
Sistemas informáticos actuales son incapaces de gestionar directamente los objetos y enlaces. Por tanto, no es posible utilizar nebule nativo.
El proyecto messae proporciona acceso a esta nueva forma de gestionar nuestra información sin cuestionar en profundidad la organización incluyendo el sistema operativo de nuestros sistemas de información.<br />
<br />
La interfaz messae es una página web que se creará en el servidor para manejar objetos y enlaces.
Todo esto suena muy parecido a lo que ya es común: Google (y su galaxia de sitios), Facebook, Twitter, Outlook, Yahoo e innumerables competidores y pretendientes…
Todos estos sitios son generalmente más competitivas que complementarias, y todo sin excepción están cerrados a la competencia.
Esto se traduce en el usuario por la necesidad, ya sea tener una cuenta en cada sitio, o asistir sólo a algunos de ellos, para ver uno.
Esto también resulta en la incapacidad para intercambiar directamente datos y la información de un sitio a otro.<br />
<br />
El messae proyecto reproduce datos de concentración a los servidores de Internet.
Se espera que esté instalado de forma nativa en cualquier servidor web.
Y, en base a los principios de nebule, cualquier servidor de alojamiento messae puede nativa:<br />
 1. gestionar las identidades generadas por el otro servidor, si un usuario o un robot;<br />
 2. el intercambio de datos e información con el resto de servidores de aplicación nebule;<br />
 3. la retransmisión de los datos y los otros servidores de datos;<br />
 4. permitir a cualquier usuario (conocidos por el servidor) para conectarse a él.<br />
<br />
Con IPv6, tenemos la capacidad de conectarse en realidad todas las máquinas en Internet.
Todo el mundo puede simplemente configurar messae casa, o continuar utilizando otro servidor de Internet.
Cada individuo puede llegar a ser activo.<br />
<br />
Por último, si un día nebule se extiende a todas las máquinas y todas estas máquinas implementar de forma nativa, entonces el proyecto messae desaparecer.
Él habrá cumplido su propósito: permitir una transición suave a nebule.
Se verá como un síntoma de una rareza era.';

        $this->_table['fr-fr']['::messae:module:help:APropos:Liens'] = 'Voir aussi :<br /><a href="http://blog.messae.org/">Le blog du projet messae</a><br /><a href="http://blog.nebule.org/">Le blog du projet nebule</a>';
        $this->_table['en-en']['::messae:module:help:APropos:Liens'] = 'See also :<br /><a href="http://blog.messae.org/">The blog of messae projet</a><br /><a href="http://blog.nebule.org/">the blog of nebule projet</a>';
        $this->_table['es-co']['::messae:module:help:APropos:Liens'] = 'Ver también :<br /><a href="http://blog.messae.org/">El blog del proyecto messae</a><br /><a href="http://blog.nebule.org/">El blog del proyecto nebule</a>';

        $this->_table['fr-fr']['::messae:module:help:AideGenerale:Text'] = "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu est le meilleur moyen de se déplacer dans l'interface.";

        $this->_table['en-en']['::messae:module:help:AideGenerale:Text'] = 'The software is composed of three parts:<br />
1. the top banner that contains the application menu and the current entity.<br />
2. <br />
3. <br />
<br />
<br />
';

        $this->_table['es-co']['::messae:module:help:AideGenerale:Text'] = 'El software se compone de tres partes:<br />
1. el banner superior que contiene el menu de la aplicacion y la entidad actual.<br />
2. <br />
3. <br />
<br />
<br />
';
    }
}


/**
 * Cette application permet de gérer les options de l'application messae.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleAdmin extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::messae:module:admin:ModuleName';
    protected $MODULE_MENU_NAME = '::messae:module:admin:MenuName';
    protected $MODULE_COMMAND_NAME = 'adm';
    protected $MODULE_DEFAULT_VIEW = 'options';
    protected $MODULE_DESCRIPTION = '::messae:module:admin:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2016-2020';
    protected $MODULE_LOGO = '1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3';
    protected $MODULE_HELP = '::messae:module:admin:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('options', 'admins', 'recovery');
    protected $MODULE_REGISTERED_ICONS = array('1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3',        // 0 : Icône admin.
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1');    // 1 : Icône liste entités.
    protected $MODULE_APP_TITLE_LIST = array('::messae:module:admin:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('1408c87c876ff05cb392b990fcc54ad46dbee69a45c07cdb1b60d6fe4b0a0ae3');
    protected $MODULE_APP_DESC_LIST = array('::messae:module:admin:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('options');


    /**
     * Configuration spécifique au module.
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        if ($object == 'none') {
            $object = $this->_applicationInstance->getCurrentObject();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        $hookArray = array();
        switch ($hookName) {
            case 'selfMenu':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[0]) {
                    // Voir les options.
                    $hookArray[0]['name'] = '::messae:module:admin:display:seeOptions';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[1]) {
                    // Voir les admins.
                    $hookArray[1]['name'] = '::messae:module:admin:display:seeAdmins';
                    $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[2]) {
                    // Voir les entités de recouvrement.
                    $hookArray[2]['name'] = '::messae:module:admin:display:seeRecovery';
                    $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];
                }
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     */
    protected function _displayFull(): void
    {
        $this->_displayHeader();

        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayOptions();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayAdmins();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayRecoveryEntities();
                break;
            default:
                $this->_displayOptions();
                break;
        }
    }


    /**
     * Affichage en ligne comme élément inseré dans une page web.
     */
    protected function _displayInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayInlineAdmins();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayInlineRecoveryEntities();
                break;
        }
    }


    private $_listOptions = array(
        'messaeDisplayOnlineHelp',
        'messaeDisplayOnlineOptions',
        'messaeDisplayMetrology',
        'messaeDisplayUnsecureURL',
        'messaeDisplayUnverifyLargeContent',
        'messaeDisplayNameSize',
        'messaeIOReadMaxDataPHP',
        'messaePermitUploadObject',
        'messaePermitUploadLinks',
        'messaePermitPublicUploadObject',
        'messaePermitPublicUploadLinks',
        'messaeLogUnlockEntity',
        'messaeLogLockEntity',
        'messaeLoadModules');
    private $_listOptionsType = array(
        'messaeDisplayOnlineHelp' => 'b',        // Booléen
        'messaeDisplayOnlineOptions' => 'b',        // Booléen
        'messaeDisplayMetrology' => 'b',        // Booléen
        'messaeDisplayUnsecureURL' => 'b',        // Booléen
        'messaeDisplayUnverifyLargeContent' => 'b',        // Booléen
        'messaeDisplayNameSize' => 'i',        // Entier
        'messaeIOReadMaxDataPHP' => 'i',        // Entier
        'messaePermitUploadObject' => 'b',        // Booléen
        'messaePermitUploadLinks' => 'b',        // Booléen
        'messaePermitPublicUploadObject' => 'b',        // Booléen
        'messaePermitPublicUploadLinks' => 'b',        // Booléen
        'messaeLogUnlockEntity' => 'b',        // Booléen
        'messaeLogLockEntity' => 'b',        // Booléen
        'messaeLoadModules' => 't');    // Texte
    private $_listOptionsDefault = array(
        'messaeDisplayOnlineHelp' => Application::APPLICATION_DEFAULT_DISPLAY_ONLINE_HELP,
        'messaeDisplayOnlineOptions' => Application::APPLICATION_DEFAULT_DISPLAY_ONLINE_OPTIONS,
        'messaeDisplayMetrology' => Application::APPLICATION_DEFAULT_DISPLAY_METROLOGY,
        'messaeDisplayUnsecureURL' => Application::APPLICATION_DEFAULT_DISPLAY_UNSECURE_URL,
        'messaeDisplayUnverifyLargeContent' => Application::APPLICATION_DEFAULT_DISPLAY_UNVERIFY_LARGE_CONTENT,
        'messaeDisplayNameSize' => Application::APPLICATION_DEFAULT_DISPLAY_NAME_SIZE,
        'messaeIOReadMaxDataPHP' => Application::APPLICATION_DEFAULT_IO_READ_MAX_DATA,
        'messaePermitUploadObject' => Application::APPLICATION_DEFAULT_PERMIT_UPLOAD_OBJECT,
        'messaePermitUploadLinks' => Application::APPLICATION_DEFAULT_PERMIT_UPLOAD_LINKS,
        'messaePermitPublicUploadObject' => Application::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_OBJECT,
        'messaePermitPublicUploadLinks' => Application::APPLICATION_DEFAULT_PERMIT_PUBLIC_UPLOAD_LINKS,
        'messaeLogUnlockEntity' => Application::APPLICATION_DEFAULT_LOG_UNLOCK_ENTITY,
        'messaeLogLockEntity' => Application::APPLICATION_DEFAULT_LOG_LOCK_ENTITY,
        'messaeLoadModules' => Application::APPLICATION_DEFAULT_LOAD_MODULES);
    private $_listOptionsHelp = array(
        'messaeDisplayOnlineHelp' => '::messae:module:admin:option:help:messaeDisplayOnlineHelp',
        'messaeDisplayOnlineOptions' => '::messae:module:admin:option:help:messaeDisplayOnlineOptions',
        'messaeDisplayMetrology' => '::messae:module:admin:option:help:messaeDisplayMetrology',
        'messaeDisplayUnsecureURL' => '::messae:module:admin:option:help:messaeDisplayUnsecureURL',
        'messaeDisplayUnverifyLargeContent' => '::messae:module:admin:option:help:messaeDisplayUnverifyLargeContent',
        'messaeDisplayNameSize' => '::messae:module:admin:option:help:messaeDisplayNameSize',
        'messaeIOReadMaxDataPHP' => '::messae:module:admin:option:help:messaeIOReadMaxDataPHP',
        'messaePermitUploadObject' => '::messae:module:admin:option:help:messaePermitUploadObject',
        'messaePermitUploadLinks' => '::messae:module:admin:option:help:messaePermitUploadLinks',
        'messaePermitPublicUploadObject' => '::messae:module:admin:option:help:messaePermitPublicUploadObject',
        'messaePermitPublicUploadLinks' => '::messae:module:admin:option:help:messaePermitPublicUploadLinks',
        'messaeLogUnlockEntity' => '::messae:module:admin:option:help:messaeLogUnlockEntity',
        'messaeLogLockEntity' => '::messae:module:admin:option:help:messaeLogLockEntity',
        'messaeLoadModules' => '::messae:module:admin:option:help:messaeLoadModules');

    private function _displayHeader(): void
    {
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation.
     */
    private function _displayOptions(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayDivTextTitle(
            $this->MODULE_REGISTERED_ICONS[0],
            '::messae:module:admin:display:seeOptions',
            '',
            '::messae:module:admin:display:seeOptionsHelp');

        if ($this->_unlocked) {
            // Affiche les point d'encrage.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::messae:module:admin:DisplayOptions');

            foreach ($this->_listOptions as $option) {
                // Extrait le type de valeur de l'option.
                $type = '';
                switch ($this->_listOptionsType[$option]) {
                    case 'i' :
                        $type = $this->_traduction('::messae:module:admin:option:type:int');
                        break;
                    case 't' :
                        $type = $this->_traduction('::messae:module:admin:option:type:text');
                        break;
                    case 'b' :
                        $type = $this->_traduction('::messae:module:admin:option:type:bool');
                        break;
                }

                $this->_applicationInstance->getDisplayInstance()->displayDivTextTitleH2(
                    $this->MODULE_REGISTERED_ICONS[0],
                    '::messae:module:admin:option:' . $option,
                    $type,
                    $this->_listOptionsHelp[$option]);
                ?>

                <div class="text">
                    <p>
                        <?php
                        echo $option . ' = ';
                        if ($this->_listOptionsType[$option] == 'b') {
                            if ($this->_configuration->getOptionUntyped($option))
                                echo 'true';
                            else
                                echo 'false';
                        } elseif ($this->_listOptionsType[$option] == 'i') {
                            echo $this->_configuration->getOptionUntyped($option);
                        } elseif ($this->_listOptionsType[$option] == 't') {
                            echo $this->_configuration->getOptionUntyped($option);
                        }
                        ?>

                    </p>
                    <p>
                        <?php
                        $this->_echoTraduction('::messae:module:admin:display:defaultValue') . ' = ';
                        if ($this->_listOptionsType[$option] == 'b') {
                            if ($this->_listOptionsDefault[$option])
                                echo 'true';
                            else
                                echo 'false';
                        } elseif ($this->_listOptionsType[$option] == 'i') {
                            echo $this->_listOptionsDefault[$option];
                        } elseif ($this->_listOptionsType[$option] == 't') {
                            echo $this->_listOptionsDefault[$option];
                        }
                        ?>

                    </p>
                </div>
                <?php
            }
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageError(':::err_NotPermit');
        }
    }

    private function _displayAdmins(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayDivTextTitle(
            $this->MODULE_REGISTERED_ICONS[1],
            '::messae:module:admin:display:seeAdmins',
            '',
            '::messae:module:admin:display:seeAdminsHelp');

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('adminlist');
    }

    private function _displayInlineAdmins(): void
    {
        if ($this->_unlocked) {
            // Liste les entités que j'ai marqué comme connues.
            $listEntities = $this->_nebuleInstance->getLocalAuthoritiesInstance();

            // Prépare l'affichage.
            $list = array();
            $i = 0;
            foreach ($listEntities as $instance) {
                $id = $instance->getID();
                $list[$i]['object'] = $instance;
                $list[$i]['entity'] = '';
                $list[$i]['icon'] = '';
                $list[$i]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Nebule\Application\Messae\ModuleEntities::MODULE_DEFAULT_VIEW
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $id;
                $list[$i]['desc'] = '';
                $list[$i]['actions'] = array();
                $i++;
            }
            unset($instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                // Affiche les point d'encrage de toutes les entités.
                echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::messae:module:admin:DisplayLocalAuthorities');

                // Affiche les entités.
                $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::messae:module:admin:Display:NoLocalAuthority');
            }
            unset($list, $listEntities);
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageError(':::err_NotPermit');
        }
    }

    private function _displayRecoveryEntities(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayDivTextTitle(
            $this->MODULE_REGISTERED_ICONS[1],
            '::messae:module:admin:display:seeRecovery',
            '',
            '::messae:module:admin:display:seeRecoveryHelp');

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('recoverylist');
    }

    private function _displayInlineRecoveryEntities(): void
    {
        // Liste les entités marquées comme entités de recouvrement.
        $listEntities = $this->_nebuleInstance->getRecoveryEntitiesInstance();

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($listEntities as $instance) {
            $id = $instance->getID();
            $list[$i]['object'] = $instance;
            $list[$i]['entity'] = '';
            $list[$i]['icon'] = '';
            $list[$i]['htlink'] = '?'
                . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Nebule\Application\Messae\ModuleEntities::MODULE_DEFAULT_VIEW
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $id;
            $list[$i]['desc'] = '';
            $list[$i]['actions'] = array();
            $i++;
        }
        unset($instance, $id);
        // Affichage
        if (sizeof($list) != 0) {
            // Affiche les point d'encrage de toutes les entités.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::messae:module:admin:DisplayRecoveryEntities');

            // Affiche les entités.
            $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::messae:module:admin:Display:NoRecoveryEntity');
        }
        unset($list, $listEntities);
    }


    // Initialisation de la table de traduction.
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::messae:module:admin:ModuleName'] = "Module d'administration";
        $this->_table['en-en']['::messae:module:admin:ModuleName'] = 'Administration module';
        $this->_table['es-co']['::messae:module:admin:ModuleName'] = 'Administration module';
        $this->_table['fr-fr']['::messae:module:admin:MenuName'] = 'Options';
        $this->_table['en-en']['::messae:module:admin:MenuName'] = 'Options';
        $this->_table['es-co']['::messae:module:admin:MenuName'] = 'Options';
        $this->_table['fr-fr']['::messae:module:admin:ModuleDescription'] = 'Module de gestion des options de configuration et de personnalisation.';
        $this->_table['en-en']['::messae:module:admin:ModuleDescription'] = 'Options management module for configration and customisation.';
        $this->_table['es-co']['::messae:module:admin:ModuleDescription'] = 'Options management module for configration and customisation.';
        $this->_table['fr-fr']['::messae:module:admin:ModuleHelp'] = "Cette application permet de voir et de gérer les options.";
        $this->_table['en-en']['::messae:module:admin:ModuleHelp'] = 'This application permit to see and manage options.';
        $this->_table['es-co']['::messae:module:admin:ModuleHelp'] = 'This application permit to see and manage options.';

        $this->_table['fr-fr']['::messae:module:admin:AppTitle1'] = 'Options';
        $this->_table['en-en']['::messae:module:admin:AppTitle1'] = 'Options';
        $this->_table['es-co']['::messae:module:admin:AppTitle1'] = 'Options';
        $this->_table['fr-fr']['::messae:module:admin:AppDesc1'] = 'Modifier les options.';
        $this->_table['en-en']['::messae:module:admin:AppDesc1'] = 'Modify all options.';
        $this->_table['es-co']['::messae:module:admin:AppDesc1'] = 'Modify all options.';

        $this->_table['fr-fr']['::messae:module:admin:display:seeOptions'] = 'Les options';
        $this->_table['en-en']['::messae:module:admin:display:seeOptions'] = 'Options';
        $this->_table['es-co']['::messae:module:admin:display:seeOptions'] = 'Options';
        $this->_table['fr-fr']['::messae:module:admin:display:seeOptionsHelp'] = 'Voir les options';
        $this->_table['en-en']['::messae:module:admin:display:seeOptionsHelp'] = 'See options';
        $this->_table['es-co']['::messae:module:admin:display:seeOptionsHelp'] = 'See options';

        $this->_table['fr-fr']['::messae:module:admin:display:seeAdmins'] = 'Les autorités';
        $this->_table['en-en']['::messae:module:admin:display:seeAdmins'] = 'Autorities';
        $this->_table['es-co']['::messae:module:admin:display:seeAdmins'] = 'Autorities';
        $this->_table['fr-fr']['::messae:module:admin:display:seeAdminsHelp'] = 'Voir les autorités';
        $this->_table['en-en']['::messae:module:admin:display:seeAdminsHelp'] = 'See autorities';
        $this->_table['es-co']['::messae:module:admin:display:seeAdminsHelp'] = 'See autorities';
        $this->_table['fr-fr']['::messae:module:admin:Display:NoLocalAuthority'] = "Pas d'entité autorité sur ce serveur.";
        $this->_table['en-en']['::messae:module:admin:Display:NoLocalAuthority'] = 'No autority entity on this server.';
        $this->_table['es-co']['::messae:module:admin:Display:NoLocalAuthority'] = 'No autority entity on this server.';

        $this->_table['fr-fr']['::messae:module:admin:display:seeRecovery'] = 'Entités de recouvrement';
        $this->_table['en-en']['::messae:module:admin:display:seeRecovery'] = 'Recovery entities';
        $this->_table['es-co']['::messae:module:admin:display:seeRecovery'] = 'Recovery entities';
        $this->_table['fr-fr']['::messae:module:admin:display:seeRecoveryHelp'] = 'Voir les entités de recouvrement';
        $this->_table['en-en']['::messae:module:admin:display:seeRecoveryHelp'] = 'See recovery entities';
        $this->_table['es-co']['::messae:module:admin:display:seeRecoveryHelp'] = 'See recovery entities';
        $this->_table['fr-fr']['::messae:module:admin:Display:NoRecoveryEntity'] = "Pas d'entité de recouvrement des objets protégés sur ce serveur.";
        $this->_table['en-en']['::messae:module:admin:Display:NoRecoveryEntity'] = 'No recovery entity for protected objects on this server.';
        $this->_table['es-co']['::messae:module:admin:Display:NoRecoveryEntity'] = 'No recovery entity for protected objects on this server.';

        $this->_table['fr-fr']['::messae:module:admin:display:defaultValue'] = 'Valeur par défaut';
        $this->_table['en-en']['::messae:module:admin:display:defaultValue'] = 'Default value';
        $this->_table['es-co']['::messae:module:admin:display:defaultValue'] = 'Default value';

        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayOnlineHelp'] = "Affichage de l'aide en ligne";
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayOnlineHelp'] = 'Display online help';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayOnlineHelp'] = 'Display online help';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayMetrology'] = 'Affichage de la métrologie';
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayMetrology'] = 'Display metrology';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayMetrology'] = 'Display metrology';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayUnsecureURL'] = 'Affiche une URL non sécurisée';
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayUnsecureURL'] = 'Display unsecure URL';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayUnsecureURL'] = 'Display unsecure URL';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeDisplayNameSize'] = 'Taille maximum des noms';
        $this->_table['en-en']['::messae:module:admin:option:messaeDisplayNameSize'] = 'Maximum name size';
        $this->_table['es-co']['::messae:module:admin:option:messaeDisplayNameSize'] = 'Maximum name size';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['en-en']['::messae:module:admin:option:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['es-co']['::messae:module:admin:option:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['fr-fr']['::messae:module:admin:option:messaePermitUploadObject'] = 'Autorise la synchronisation des objets';
        $this->_table['en-en']['::messae:module:admin:option:messaePermitUploadObject'] = 'Permit object synchronisation';
        $this->_table['es-co']['::messae:module:admin:option:messaePermitUploadObject'] = 'Permit object synchronisation';
        $this->_table['fr-fr']['::messae:module:admin:option:messaePermitUploadLinks'] = 'Autorise la synchronisation des liens';
        $this->_table['en-en']['::messae:module:admin:option:messaePermitUploadLinks'] = 'Permit links synchronisation';
        $this->_table['es-co']['::messae:module:admin:option:messaePermitUploadLinks'] = 'Permit links synchronisation';
        $this->_table['fr-fr']['::messae:module:admin:option:messaePermitPublicUploadObject'] = 'Autorise la synchronisation publique des objets';
        $this->_table['en-en']['::messae:module:admin:option:messaePermitPublicUploadObject'] = 'Permit public object synchronisation';
        $this->_table['es-co']['::messae:module:admin:option:messaePermitPublicUploadObject'] = 'Permit public object synchronisation';
        $this->_table['fr-fr']['::messae:module:admin:option:messaePermitPublicUploadLinks'] = 'Autorise la synchronisation publique des liens';
        $this->_table['en-en']['::messae:module:admin:option:messaePermitPublicUploadLinks'] = 'Permit public links synchronisation';
        $this->_table['es-co']['::messae:module:admin:option:messaePermitPublicUploadLinks'] = 'Permit public links synchronisation';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['en-en']['::messae:module:admin:option:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['es-co']['::messae:module:admin:option:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['en-en']['::messae:module:admin:option:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['es-co']['::messae:module:admin:option:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['fr-fr']['::messae:module:admin:option:messaeLoadModules'] = 'Modules à charger';
        $this->_table['en-en']['::messae:module:admin:option:messaeLoadModules'] = 'Modules to load';
        $this->_table['es-co']['::messae:module:admin:option:messaeLoadModules'] = 'Modules to load';

        $this->_table['fr-fr']['::messae:module:admin:option:type:int'] = 'Entier';
        $this->_table['en-en']['::messae:module:admin:option:type:int'] = 'Integer';
        $this->_table['es-co']['::messae:module:admin:option:type:int'] = 'Integer';
        $this->_table['fr-fr']['::messae:module:admin:option:type:text'] = 'Texte';
        $this->_table['en-en']['::messae:module:admin:option:type:text'] = 'Text';
        $this->_table['es-co']['::messae:module:admin:option:type:text'] = 'Text';
        $this->_table['fr-fr']['::messae:module:admin:option:type:bool'] = 'Booléen';
        $this->_table['en-en']['::messae:module:admin:option:type:bool'] = 'Boolean';
        $this->_table['es-co']['::messae:module:admin:option:type:bool'] = 'Boolean';

        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayOnlineHelp'] = 'messaeDisplayOnlineHelp';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayOnlineHelp'] = 'messaeDisplayOnlineHelp';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayOnlineHelp'] = 'messaeDisplayOnlineHelp';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayOnlineOptions'] = 'messaeDisplayOnlineOptions';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayMetrology'] = 'messaeDisplayMetrology';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayMetrology'] = 'messaeDisplayMetrology';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayMetrology'] = 'messaeDisplayMetrology';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayUnsecureURL'] = 'messaeDisplayUnsecureURL';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayUnsecureURL'] = 'messaeDisplayUnsecureURL';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayUnsecureURL'] = 'messaeDisplayUnsecureURL';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayUnverifyLargeContent'] = 'messaeDisplayUnverifyLargeContent';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeDisplayNameSize'] = 'messaeDisplayNameSize';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeDisplayNameSize'] = 'messaeDisplayNameSize';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeDisplayNameSize'] = 'messaeDisplayNameSize';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeIOReadMaxDataPHP'] = 'messaeIOReadMaxDataPHP';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaePermitUploadObject'] = 'messaePermitUploadObject';
        $this->_table['en-en']['::messae:module:admin:option:help:messaePermitUploadObject'] = 'messaePermitUploadObject';
        $this->_table['es-co']['::messae:module:admin:option:help:messaePermitUploadObject'] = 'messaePermitUploadObject';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaePermitUploadLinks'] = 'messaePermitUploadLinks';
        $this->_table['en-en']['::messae:module:admin:option:help:messaePermitUploadLinks'] = 'messaePermitUploadLinks';
        $this->_table['es-co']['::messae:module:admin:option:help:messaePermitUploadLinks'] = 'messaePermitUploadLinks';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaePermitPublicUploadObject'] = 'messaePermitPublicUploadObject';
        $this->_table['en-en']['::messae:module:admin:option:help:messaePermitPublicUploadObject'] = 'messaePermitPublicUploadObject';
        $this->_table['es-co']['::messae:module:admin:option:help:messaePermitPublicUploadObject'] = 'messaePermitPublicUploadObject';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaePermitPublicUploadLinks'] = 'messaePermitPublicUploadLinks';
        $this->_table['en-en']['::messae:module:admin:option:help:messaePermitPublicUploadLinks'] = 'messaePermitPublicUploadLinks';
        $this->_table['es-co']['::messae:module:admin:option:help:messaePermitPublicUploadLinks'] = 'messaePermitPublicUploadLinks';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeLogUnlockEntity'] = 'messaeLogUnlockEntity';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeLogLockEntity'] = 'messaeLogLockEntity';
        $this->_table['fr-fr']['::messae:module:admin:option:help:messaeLoadModules'] = 'messaeLoadModules';
        $this->_table['en-en']['::messae:module:admin:option:help:messaeLoadModules'] = 'messaeLoadModules';
        $this->_table['es-co']['::messae:module:admin:option:help:messaeLoadModules'] = 'messaeLoadModules';

    }
}


/**
 * Cette application permet de gérer les entités.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleEntities extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:entities:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:entities:MenuName';
    protected $MODULE_COMMAND_NAME = 'ent';
    protected $MODULE_DEFAULT_VIEW = 'disp';
    protected $MODULE_DESCRIPTION = '::sylabe:module:entities:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60';
    protected $MODULE_HELP = '::sylabe:module:entities:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('list', 'disp', 'auth', 'crea', 'srch', 'logs', 'acts', 'prop', 'klst', 'ulst', 'slst', 'kblst');
    protected $MODULE_REGISTERED_ICONS = array(
        '94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0',    // 0 entité (personnage)
        '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1',    // 1 lien chiffrement/protection
        '7e9726b5aec1b2ab45c70f882f56ea0687c27d0739022e907c50feb87dfaf37d',    // 2 lien mise à jour
        'cc2a24b13d8e03a5de238a79a8adda1a9744507b8870d59448a23b8c8eeb5588',    // 3 lister objets
        '3edf52669e7284e4cefbdbb00a8b015460271765e97a0d6ce6496b11fe530ce1',    // 4 lister entités
        'cba3712128bbdd5243af372884eb647595103bb4c1f1b4d2e2bf62f0eba3d6e6',    // 5 ajouter entité
        '468f2e420371343c58dcdb49c4db9f00b81cce029a5ee1de627b9486994ee199',    // 6 sync entité
        '4de7b15b364506d693ce0cd078398fa38ff941bf58c5f556a68a1dcd7209a2fc',    // 7 messagerie down
        'a16490f9b25b2d3d055e50a2593ceda10c9d1608505e27acf15a5e2ecc314b52',    // 8 messagerie up
        '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5',    // 9 clé
        '94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60',    // 10 entité (objet)
        'de62640d07ac4cb2f50169fa361e062ed3595be1e973c55eb3ef623ed5661947',    // 11 verrouillage entité.
    );
    protected $MODULE_APP_TITLE_LIST = array('::sylabe:module:entities:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('94d5243e2b48bb89e91f2906bdd7f9006b1632203e831ff09615ad2ccaf20a60');
    protected $MODULE_APP_DESC_LIST = array('::sylabe:module:entities:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('list');

    const COMMAND_LOGOUT_ENTITY = 'logout';
    const COMMAND_SWITCH_TO_ENTITY = 'switch';
    const COMMAND_SYNC_KNOWN_ENTITIES = 'synknownent';
    const COMMAND_SYNC_NEBULE_ENTITIES = 'synnebent';
    const DEFAULT_ENTITIES_DISPLAY_NUMBER = 12;
    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;

    /**
     * L'ID de l'entité en cours d'affichage.
     *
     * @var string
     */
    private $_displayEntity;

    /**
     * L'instance de l'entité en cours d'affichage.
     *
     * @var Entity
     */
    private $_displayEntityInstance;

    /**
     * L'ID de l'objet de référence des entités.
     *
     * @var string
     */
    private $_hashEntity;

    /**
     * L'instance de l'objet de référence des entités.
     *
     * @var Node
     */
    private $_hashEntityObject;

    /**
     * L'ID de l'objet de référence pour le type d'entité.
     *
     * @var string
     */
    private $_hashType;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_findDisplayEntity();
        $this->_initTable();
        $this->_hashType = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/type');
        $this->_hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
        $this->_hashEntityObject = $this->_nebuleInstance->newObject($this->_hashEntity);
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        $hookArray = array();
        if ($object == 'none') {
            $object = $this->_nebuleInstance->getCurrentEntity();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        switch ($hookName) {
            case 'selfMenu':
            case 'typeMenuEntity':
                // Lister des entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:KnownEntities';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                $hookArray[0]['desc'] = '::sylabe:module:entities:KnownEntitiesDesc';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités qui me connuaissent.
                $hookArray[1]['name'] = '::sylabe:module:entities:KnownByEntities';
                $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                $hookArray[1]['desc'] = '::sylabe:module:entities:KnownByEntitiesDesc';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[11]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister de mes entités.
                $hookArray[2]['name'] = '::sylabe:module:entities:MyEntities';
                $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                $hookArray[2]['desc'] = '::sylabe:module:entities:MyEntitiesDesc';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[8]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités inconnues.
                $hookArray[3]['name'] = '::sylabe:module:entities:UnknownEntities';
                $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                $hookArray[3]['desc'] = '::sylabe:module:entities:UnknownEntitiesDesc';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[9]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Lister des entités spéciales.
                $hookArray[4]['name'] = '::sylabe:module:entities:SpecialEntities';
                $hookArray[4]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                $hookArray[4]['desc'] = '::sylabe:module:entities:SpecialEntitiesDesc';
                $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[10]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Voir les propriétés de l'entité.
                $hookArray[5]['name'] = '::sylabe:module:entities:DescriptionEntity';
                $hookArray[5]['icon'] = $this->MODULE_REGISTERED_ICONS[10];
                $hookArray[5]['desc'] = '::sylabe:module:entities:DescriptionEntityDesc';
                $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Vérifie que la création soit authorisée.
                if ($this->_configuration->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteEntity'))
                    && ($this->_unlocked || $this->_configuration->getOptionAsBoolean('permitPublicCreateEntity'))
                ) {
                    // Créer une nouvelle entité.
                    $hookArray[6]['name'] = '::sylabe:module:entities:CreateEntity';
                    $hookArray[6]['icon'] = $this->MODULE_REGISTERED_ICONS[5];
                    $hookArray[6]['desc'] = '::sylabe:module:entities:CreateEntityDesc';
                    $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity();
                }

                // Vérifie que la synchronisation soit authorisée.
                if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject','permitSynchronizeLink'))) {
                    // Rechercher une entité.
                    $hookArray[7]['name'] = '::sylabe:module:entities:SearchEntity';
                    $hookArray[7]['icon'] = Displays::DEFAULT_ICON_LF;
                    $hookArray[7]['desc'] = '::sylabe:module:entities:SearchEntityDesc';
                    $hookArray[7]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[4]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity();
                }
                break;

            case 'selfMenuObject':
                $instance = $this->_applicationInstance->getCurrentObjectInstance();
                $id = $instance->getID();
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_nebuleInstance->newObject($id);
                }

                // Si l'objet est une entité.
                if ($instance->getType('all') == 'application/x-pem-file') {
                    // Voir l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:ShowEntity';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[10];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $id;
                }
                break;

            case 'selfMenuEntity':
                if ($object != $this->_nebuleInstance->getCurrentEntity()) {
                    // Basculer et se connecter avec cette entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:ConnectWith';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                        . '&' . nebule::COMMAND_LOGOUT_ENTITY
                        . '&' . nebule::COMMAND_SWITCH_TO_ENTITY
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;
                } elseif (!$this->_unlocked) {
                    // Se connecter avec l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:ConnectWith';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;
                } else {
                    // Se déconnecter de l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:entities:disp:Disconnect';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[11];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                        . '&' . nebule::COMMAND_LOGOUT_ENTITY
                        . '&' . nebule::COMMAND_FLUSH;
                }

                // Synchroniser l'entité.
                $hookArray[2]['name'] = '::sylabe:module:entities:SynchronizeEntity';
                $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[6];
                $hookArray[2]['desc'] = '';
                $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                // Voir l'entité.
                $hookArray[3]['name'] = '::sylabe:module:entities:ShowEntity';
                $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[3]['desc'] = '';
                $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;

                // Recherche si l'objet est marqué.
                if (!$this->_applicationInstance->getMarkObject($object)) {
                    // Ajouter la marque de l'objet.
                    $hookArray[4]['name'] = '::MarkAdd';
                    $hookArray[4]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[4]['desc'] = '';
                    $hookArray[4]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $object
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                break;

            case '::sylabe:module:entities:DisplayMyEntities':
            case '::sylabe:module:entities:DisplayKnownEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . self::COMMAND_SYNC_KNOWN_ENTITIES
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                break;

            case '::sylabe:module:entities:DisplayNebuleEntity':
                // Synchroniser les entités connues.
                $hookArray[0]['name'] = '::sylabe:module:entities:SynchronizeKnownEntities';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[6];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY
                    . '&' . self::COMMAND_SYNC_NEBULE_ENTITIES
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    protected function _displayFull(): void
    {
        switch ($this->_display->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayKnownEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayEntityDisp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayEntityAuth();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayEntityCreate();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayEntitySearch();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_displayEntityLogs();
                break;
            case $this->MODULE_REGISTERED_VIEWS[6]:
                $this->_displayEntityActs();
                break;
            case $this->MODULE_REGISTERED_VIEWS[7]:
                $this->_displayEntityProp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[8]:
                $this->_displayMyEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[9]:
                $this->_displayUnknownEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[10]:
                $this->_displaySpecialEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[11]:
                $this->_displayKnownByEntitiesList();
                break;
            default:
                $this->_displayEntityDisp();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    protected function _displayInline(): void
    {
        switch ($this->_display->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineKnownEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineEntitySearch();
                break;
            case $this->MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineEntityProp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[8]:
                $this->_display_InlineMyEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[9]:
                $this->_display_InlineUnknownEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[10]:
                $this->_display_InlineSpecialEntitiesList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[11]:
                $this->_display_InlineKnownByEntitiesList();
                break;
        }
    }

    /**
     * Affichage de surcharges CSS.
     *
     * Obsolète !
     *
     * @return void
     */
    public function getCSS(): void
    {
        ?>

        <style type="text/css">
            /* Création d'entité */
            input {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
                margin: 0;
                margin-top: 5px;
                border: 0;
                box-shadow: initial;
                padding: 5px;
                background-origin: border-box;
                text-align: left;
            }

            .sylabeModuleEntityCreate {
                margin-bottom: 60px;
                clear: both;
            }

            .sylabeModuleEntityCreateHeader p {
                font-weight: bold;
                margin: 0;
                padding: 0;
            }

            .sylabeModuleEntityCreateProperty {
                clear: both;
            }

            .sylabeModuleEntityCreatePropertyName {
                float: left;
                width: 25%;
                text-align: right;
                padding-top: 10px;
            }

            .sylabeModuleEntityCreatePropertyEntry {
                margin-top: 2px;
                margin-bottom: 2px;
                float: right;
                width: 70%;
            }

            .sylabeModuleEntityCreateSubmit {
                clear: both;
            }

            .sylabeModuleEntityCreateSubmitEntry {
                width: 100%;
                text-align: center;
            }

            #sylabeModuleEntityCreatePropertyEntryNom {
                background: #ffffff;
            }

            #sylabeModuleEntityCreatePropertyNamePWD1 {
                font-weight: bold;
                text-align: left;
            }

            #sylabeModuleEntityCreatePropertyEntryPWD1 {
                background: #ffffff;
            }

            #sylabeModuleEntityCreatePropertyEntryPWD2 {
                background: #ffffff;
            }

            /* Les logs et acts */
            .sylabeModuleEntityActionText {
                padding: 20px;
                padding-left: 74px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionTextList1 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionTextList2 {
                padding: 10px;
                padding-left: 74px;
                min-height: 64px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityActionDivIcon {
                float: left;
                margin-right: 5px;
            }

            .sylabeModuleEntityActionDate {
                float: right;
                margin-left: 5px;
                font-family: monospace;
            }

            .sylabeModuleEntityActionTitle {
                font-weight: bold;
                font-size: 1.2em;
            }

            .sylabeModuleEntityActionType {
                font-style: italic;
                font-size: 0.8em;
                margin-bottom: 10px;
            }

            .sylabeModuleEntityActionFromTo {
            }

            /* Les propriétés */
            .sylabeModuleEntityDescList1 {
                padding: 5px;
                background: rgba(255, 255, 255, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityDescList2 {
                padding: 5px;
                background: rgba(230, 230, 230, 0.5);
                background-origin: border-box;
                color: #000000;
                clear: both;
            }

            .sylabeModuleEntityDescError {
                padding: 5px;
                background: rgba(0, 0, 0, 0.3);
                background-origin: border-box;
                clear: both;
            }

            .sylabeModuleEntityDescError .sylabeModuleEntityDescAttrib {
                font-style: italic;
                color: #202020;
            }

            .sylabeModuleEntityDescIcon {
                float: left;
                margin-right: 5px;
            }

            .sylabeModuleEntityDescContent {
                min-width: 300px;
            }

            .sylabeModuleEntityDescDate, .sylabeModuleEntityDescSigner {
                float: right;
                margin-left: 10px;
            }

            .sylabeModuleEntityDescValue {
                font-weight: bold;
            }

            .sylabeModuleEntityDescEmotion {
                font-weight: bold;
            }

            .sylabeModuleEntityDescEmotion img {
                height: 16px;
                width: 16px;
            }

            /* Connexion */
            #sylabeModuleEntityConnect {
                text-align: center;
            }
        </style>
        <?php
    }

    /**
     * Affichage de surcharges CSS.
     *
     * Obsolète !
     *
     * @return void
     */
    public function headerStyle(): void
    {
    }

    /**
     * Action principale.
     *
     * @return void
     */
    public function action(): void
    {
        $this->_findSynchronizeEntity();
        $this->_actionSynchronizeEntity();
        $this->_findSearchEntity();
        $this->_actionSearchEntity();
        $this->_findCreateEntity();
        $this->_actionCreateEntity();
    }


    /**
     * Recherche l'entité en cours d'utilisation.
     * Utilisé par le constructeur et non comme action.
     */
    private function _findDisplayEntity(): void
    {
        $this->_displayEntity = $this->_applicationInstance->getCurrentEntity();
        $this->_displayEntityInstance = $this->_applicationInstance->getCurrentEntityInstance();
    }


    /**
     * Mémorise si l'entité doit être synchronisée.
     * @var string
     */
    private $_synchronizeEntity = false;

    /**
     * Détermine si l'entité doit être synchronisée.
     *
     * @return void
     */
    private function _findSynchronizeEntity(): void
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $arg = filter_has_var(INPUT_GET, Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_ENTITY);

        // Vérifie que la création de liens et d'objets soit authorisée et que l'action soit demandée.
        if ($arg !== false
            && $this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject','permitSynchronizeLink'))
        ) {
            $this->_synchronizeEntity = true;
        }
        unset($arg);
    }

    /**
     * Réalise la synchronisation de l'entité.
     * @return void
     * @todo
     *
     */
    private function _actionSynchronizeEntity(): void
    {
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject','permitSynchronizeLink'))
            && $this->_synchronizeEntity
        ) {
            // Synchronize l'entité.
            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNLNK')
                . $this->_display->displayInlineObjectColorIconName($this->_displayEntityInstance->getID());
            echo ' &nbsp;&nbsp;';
            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNOBJ')
                . $this->_display->displayInlineObjectColorIconName($this->_displayEntityInstance->getID());
            echo ' &nbsp;&nbsp;';
            echo $this->_display->convertInlineIconFace('DEFAULT_ICON_SYNENT')
                . $this->_display->displayInlineObjectColorIconName($this->_displayEntityInstance->getID());

            // A faire...

        }
    }


    private $_searchEntityURL = '';

    private $_searchEntityID = '';

    /**
     * Recherche une entité sur ID connu et/ou URL connue.
     *
     * @return void
     */
    private function _findSearchEntity(): void
    {
        $arg_url = trim(filter_input(INPUT_GET, 'srchurl', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW)); // Lit et nettoye le contenu de la variable GET.
        if ($arg_url != ''
            && strlen($arg_url) >= 8
        )
            $this->_searchEntityURL = $arg_url;

        $arg_id = trim(filter_input(INPUT_GET, 'srchid', FILTER_SANITIZE_URL, FILTER_FLAG_ENCODE_LOW)); // Lit et nettoye le contenu de la variable GET.
        if (Node::checkNID($arg_id)
            && $arg_url != 'http://localhost'
            && $arg_url != 'http://127.0.0.1'
            && $arg_url != 'http://localhost/'
            && $arg_url != 'http://127.0.0.1/'
            && $arg_url != 'https://localhost'
            && $arg_url != 'https://127.0.0.1'
            && $arg_url != 'https://localhost/'
            && $arg_url != 'https://127.0.0.1/'
        )
            $this->_searchEntityID = $arg_id;
    }

    private function _actionSearchEntity(): void
    {
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject','permitSynchronizeLink'))
            && ($this->_searchEntityID != ''
                || $this->_searchEntityURL != ''
            )
        ) {
            // Recherche l'entité.
            if ($this->_searchEntityID != ''
                && $this->_searchEntityURL != ''
            ) {
                // Si recherche sur ID et URL.
                echo $this->_applicationInstance->getTraductionInstance()->getTraduction('Recherche')
                    . ' ' . $this->_searchEntityURL
                    . ' ' . $this->_display->displayInlineObjectColorIconName($this->_searchEntityID);
            } elseif ($this->_searchEntityID != ''
                && $this->_searchEntityURL == ''
            ) {
                // Sinon recherche sur ID.
                echo $this->_applicationInstance->getTraductionInstance()->getTraduction('Recherche')
                    . ' ' . $this->_display->displayInlineObjectColorIconName($this->_searchEntityID);
            } elseif ($this->_searchEntityID == ''
                && $this->_searchEntityURL != ''
            ) {
                // Sinon recherche sur URL.
                echo $this->_applicationInstance->getTraductionInstance()->getTraduction('Recherche')
                    . ' ' . $this->_searchEntityURL;
            }

            // A faire...

        }
    }


    // Crée une entité.
    private $_createEntityAction = false;

    private $_createEntityID = '0';

    private $_createEntityInstance = '';

    private $_createEntityError = false;

    private $_createEntityErrorMessage = '';

    private function _findCreateEntity(): void
    {
        // Regarde si une entité a été créée lors des actions.
        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        $this->_createEntityID = $this->_applicationInstance->getActionInstance()->getCreateEntityID();
        $this->_createEntityInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityInstance();
        $this->_createEntityError = $this->_applicationInstance->getActionInstance()->getCreateEntityError();
        $this->_createEntityErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateEntityErrorMessage();
    }

    private function _actionCreateEntity(): void
    {
        $this->_createEntityAction = $this->_applicationInstance->getActionInstance()->getCreateEntity();
        $this->_createEntityID = $this->_applicationInstance->getActionInstance()->getCreateEntityID();
        $this->_createEntityInstance = $this->_applicationInstance->getActionInstance()->getCreateEntityInstance();
        $this->_createEntityError = $this->_applicationInstance->getActionInstance()->getCreateEntityError();
        $this->_createEntityErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateEntityErrorMessage();
    }


    /**
     * Affiche les caractéristiques de l'entité.
     */
    private function _displayEntityDisp(): void
    {
        echo '<div class="layout-list">' . "\n";
        echo '<div class="textListObjects">' . "\n";

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => true,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'flagUnlocked' => $this->_unlocked,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'large',
            'displayRatio' => 'short',
        );
        echo $this->_display->getDisplayObject($this->_displayEntityInstance, $param);

        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }


    /**
     * Affiche l'authentification pour une entité.
     */
    private function _displayEntityAuth(): void
    {
        echo '<div class="layoutAloneItem">' . "\n";
        echo '<div class="aloneItemContent">' . "\n";

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => true,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            //'flagUnlocked' => $this->_unlocked,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => false,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        echo $this->_display->getDisplayObject($this->_displayEntityInstance, $param);

        echo '</div>' . "\n";
        echo '</div>' . "\n";

        if ($this->_displayEntityInstance->issetPrivateKeyPassword()
            || ($this->_displayEntity == $this->_nebuleInstance->getCurrentEntity()
                && $this->_unlocked
            )
        ) {
            echo $this->_display->getDisplayTitle('::EntityUnlocked', $this->MODULE_REGISTERED_ICONS[9]);
        } else {
            echo $this->_display->getDisplayTitle('::EntityLocked', $this->MODULE_REGISTERED_ICONS[11]);
        }

        // Extrait les états de tests en warning ou en erreur.
        $idCheck = 'Error';
        if ($this->_applicationInstance->getCheckSecurityAll() == 'OK') {
            $idCheck = 'Ok';
        } elseif ($this->_applicationInstance->getCheckSecurityAll() == 'WARN') {
            $idCheck = 'Warn';
        }
        // Affiche les tests.
        if ($idCheck != 'Ok') {
            $list = array();
            $check = array(
                $this->_applicationInstance->getCheckSecurityBootstrap(),
                $this->_applicationInstance->getCheckSecurityCryptoHash(),
                $this->_applicationInstance->getCheckSecurityCryptoSym(),
                $this->_applicationInstance->getCheckSecurityCryptoAsym(),
                $this->_applicationInstance->getCheckSecuritySign(),
                $this->_applicationInstance->getCheckSecurityURL(),
            );
            $chnam = array('Bootstrap', 'Crypto Hash', 'Crypto Sym', 'Crypto Asym', 'Link Sign', 'URL');
            for ($i = 0; $i < sizeof($check); $i++) {
                $list[$i]['param'] = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => false,
                    'displayRatio' => 'short',
                );
                $list[$i]['information'] = $chnam[$i];
                $list[$i]['object'] = '1';
                $list[$i]['param']['informationType'] = 'error';
                if ($check[$i] == 'OK') {
                    $list[$i]['param']['informationType'] = 'ok';
                } elseif ($check[$i] == 'WARN') {
                    $list[$i]['param']['informationType'] = 'warn';
                }
            }
            echo $this->_display->getDisplayObjectsList($list, 'small');
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'enableDisplayAlone' => true,
                'informationType' => 'ok',
                'displaySize' => 'small',
                'displayRatio' => 'short',
            );
            echo $this->_display->getDisplayInformation('::::SecurityChecks', $param);
        }

        // Affiche le champs de mot de passe.
        if ($this->_displayEntityInstance->issetPrivateKeyPassword()
            || ($this->_displayEntity == $this->_nebuleInstance->getCurrentEntity()
                && $this->_unlocked
            )
        ) {
            // Propose de la verrouiller.
            $list = array();
            $list[0]['title'] = $this->_traduction('::Lock');
            $list[0]['desc'] = $this->_traduction('::EntityUnlocked');
            $list[0]['icon'] = $this->MODULE_REGISTERED_ICONS[11];
            $list[0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                . '&' . nebule::COMMAND_LOGOUT_ENTITY
                . '&' . nebule::COMMAND_FLUSH;
            echo $this->_display->getDisplayMenuList($list, 'Medium');
        } else {
            if ($idCheck != 'Error') {
                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";
                $param['displaySize'] = 'small';
                $param['displayRatio'] = 'long';
                $param['objectIcon'] = $this->MODULE_REGISTERED_ICONS[9];
                echo $this->_display->getDisplayObject($this->_nebuleInstance->getCurrentEntityPrivateKey(), $param);
                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '<div class="layoutAloneItem">' . "\n";
                echo '<div class="aloneItemContent">' . "\n";

                echo '<div class="layoutObject layoutInformation">' . "\n";
                echo '<div class="objectTitle objectDisplayMediumShort objectTitleMedium objectDisplayShortMedium informationDisplay informationDisplayMedium informationDisplay' . $idCheck . '">' . "\n";

                echo '<div class="objectTitleText objectTitleMediumText objectTitleText0 informationTitleText">' . "\n";

                echo '<div class="objectTitleRefs objectTitleMediumRefs informationTitleRefs informationTitleRefs' . $idCheck . '" id="sylabeModuleEntityConnect">' . "\n";
                echo $this->_traduction('::Password') . "<br />\n";
                echo '</div>' . "\n";

                echo '<div class="objectTitleName objectTitleMediumName informationTitleName informationTitleName' . $idCheck . ' informationTitleMediumName" id="sylabeModuleEntityConnect">' . "\n";
                ?>
                <form method="post"
                      action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                          . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_displayEntity; ?>">
                    <input type="hidden" name="ent" value="<?php echo $this->_displayEntity; ?>">
                    <input type="password" name="pwd">
                    <input type="submit" value="<?php echo $this->_traduction('::Unlock'); ?>">
                </form>
                <?php
                echo '</div>' . "\n";

                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";

                echo '</div>' . "\n";
                echo '</div>' . "\n";
            } else {
                // Affiche un message d'erreur.
                $param = array(
                    'enableDisplayIcon' => true,
                    'enableDisplayAlone' => true,
                    'informationType' => 'error',
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );
                echo $this->_display->getDisplayInformation(':::err_NotPermit', $param);
            }
        }
    }


    /**
     * Affiche les activités vers l'entité.
     */
    private function _displayEntityLogs(): void
    {
        // Entité en cours.
        if ($this->_nebuleInstance->getCurrentEntity() != $this->_applicationInstance->getCurrentEntity()) {
            $this->_display->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:ObjectTitle1', $this->MODULE_REGISTERED_ICONS[7], true);

        // Extrait des propriétés de l'objet.
        $entity = $this->_displayEntity;
        $instance = $this->_displayEntityInstance;
        ?>

        <div class="sylabeModuleEntityActionText">
            <p>
                <?php
                if ($entity == $this->_nebuleInstance->getCurrentEntity() && $this->_unlocked) {
                    $this->_applicationInstance->getTraductionInstance()->echoTraduction(
                        '::sylabe:module:entities:DisplayEntityMessages',
                        '',
                        $this->_display->convertInlineObjectColorIconName($instance));
                    $dispWarn = false;
                } else {
                    $this->_applicationInstance->getTraductionInstance()->echoTraduction(
                        '::sylabe:module:entities:DisplayEntityPublicMessages',
                        '',
                        $this->_display->convertInlineObjectColorIconName($instance));
                    $dispWarn = true;
                }
                ?>

            </p>
        </div>
        <?php
        // Si besoin, affiche le message d'information.
        if ($dispWarn) {
            $this->_display->displayMessageInformation(
                $this->_traduction('::sylabe:module:entities:DisplayEntityPublicMessagesWarning'));
        }
        unset($dispWarn);

        // liste les liens pour l'entité.
        $linksUnprotected = $instance->getLinksOnFields(
            '',
            '',
            'f',
            $entity,
            '',
            $entity);
        $linksProtected = $instance->getLinksOnFields(
            '',
            '',
            'k',
            '',
            '',
            $entity);
        $linksObfuscated = $instance->getLinksOnFields(
            '',
            '',
            'c',
            $entity,
            '',
            '');

        // Reconstitue une seule liste.
        $links = array();
        $i = 0;
        foreach ($linksUnprotected as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksUnprotected);
        foreach ($linksProtected as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksProtected);
        foreach ($linksObfuscated as $link) {
            $links[$i] = $link;
            $i++;
        }
        unset($linksObfuscated);

        // Tri les liens par date.
        if (sizeof($links) != 0) {
            foreach ($links as $n => $t) {
                $linkdate[$n] = $t->getDate();
            }
            array_multisort($linkdate, SORT_STRING, SORT_ASC, $links);
            unset($linkdate, $n, $t);
        }

        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            // Pour chaque lien.
            foreach ($links as $link) {
                ?>

                <div class="sylabeModuleEntityActionTextList<?php echo $bg; ?>">
                    <?php
                    // Extrait l'action.
                    $action = $link->getAction();

                    if ($action == 'c') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_display->displayUpdateImage(Display::DEFAULT_ICON_LC); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_display->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php $this->_echoTraduction('::sylabe:module:entities:Obfuscated'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php $this->_echoTraduction('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_display->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    } elseif ($action == 'k') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_display->displayUpdateImage(Display::DEFAULT_ICON_LK); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_display->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php $this->_echoTraduction('::sylabe:module:entities:Protected'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php $this->_echoTraduction('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_display->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    } elseif ($action == 'f') {
                        // Extrait nom et ID pour affichage.
                        $signer = $link->getParsed()['bs/rs1/eid'];
                        $date = $link->getDate();
                        $object = $link->getParsed()['bl/rl/nid2'];
                        $objectInstance = new Node($this->_nebuleInstance, $object);
                        ?>

                        <div class="sylabeModuleEntityActionDivIcon">
                            <?php $this->_display->displayObjectColorIcon(
                                $objectInstance, Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $link->getParsed()['bl/rl/nid2']); ?>
                        </div>
                        <div>
                            <p class="sylabeModuleEntityActionDate">
                                <?php $this->_display->displayDate($date);
                                echo "\n"; ?>
                            </p>
                            <p class="sylabeModuleEntityActionTitle">
                                <?php echo $objectInstance->getFullName('all'); ?>
                            </p>
                            <p class="sylabeModuleEntityActionType">
                                <?php echo $this->_applicationInstance->getTraductionInstance()->getTraduction($objectInstance->getType('all')); ?>
                            </p>
                            <p class="sylabeModuleEntityActionFromTo">
                                <?php $this->_echoTraduction('::sylabe:module:entities:From'); ?>
                                &nbsp;<?php $this->_display->displayInlineObjectColorIconName($signer); ?><br/>
                            </p>
                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance);
                    }
                    unset($action);

                    // Permutation de l'indice de fond.
                    $bg = 3 - $bg;
                    ?>

                </div>
                <?php
            }
            unset($link, $bg);
        }
        unset($links);
    }


    /**
     * Affiche les activités depuis l'entité.
     */
    private function _displayEntityActs(): void
    {
        // Entité en cours.
        if ($this->_nebuleInstance->getCurrentEntity() != $this->_applicationInstance->getCurrentEntity()) {
            $this->_display->displayObjectDivHeaderH1($this->_displayEntityInstance, '', $this->_displayEntity);
        }

        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:ObjectTitle2', $this->MODULE_REGISTERED_ICONS[8], true);

        // Extrait des propriétés de l'objet.
        $id = $this->_applicationInstance->getCurrentObjectInstance()->getID();
        $typemime = $this->_applicationInstance->getCurrentObjectInstance()->getType('all');
        $ispresent = $this->_nebuleInstance->getIoInstance()->checkObjectPresent($id);
        $owned = false;
        ?>

        <div class="sylabeModuleEntityActionText">
            <p>
                <?php
                $dispWarn = false;
                // Vérifie si l'objet courant est une entité, affiche les messages de cette entité.
                if ($typemime == 'application/x-pem-file' && $ispresent) {
                    $entity = $this->_nebuleInstance->newEntity($id);
                    $this->_applicationInstance->getTraductionInstance()->echoTraduction(
                        '::sylabe:module:entities:DisplayEntityPublicMessages',
                        '',
                        $this->_display->convertInlineObjectColorIconName($entity));
                    $dispWarn = true;
                } // Sinon, affiche les messages de l'entité courante.
                else {
                    $entity = $this->_nebuleInstance->getCurrentEntityInstance();
                    $id = $this->_nebuleInstance->getCurrentEntity();
                    $owned = true;
                    if ($this->_unlocked) {
                        $this->_applicationInstance->getTraductionInstance()->echoTraduction(
                            '::sylabe:module:entities:DisplayEntityMessages',
                            '',
                            $this->_display->convertInlineObjectColorIconName($entity));
                    } else {
                        $this->_applicationInstance->getTraductionInstance()->echoTraduction(
                            '::sylabe:module:entities:DisplayEntityPublicMessages',
                            '',
                            $this->_display->convertInlineObjectColorIconName($entity));
                        $dispWarn = true;
                    }
                }
                ?>

            </p>
        </div>
        <?php
        // Si besoin, affiche le message d'information.
        if ($dispWarn) {
            $this->_display->displayMessageInformation(
                $this->_traduction('::sylabe:module:entities:DisplayEntityPublicMessagesWarning'));
        }
        unset($dispWarn);

        // liste les liens pour l'entité.
        $links = $entity->getLinksOnFields($entity->getID(), '', 'f', '', '', '');

        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            // Pour chaque lien.
            foreach ($links as $link) {
                // Extrait nom et ID pour affichage.
                $source = $link->getParsed()['bl/rl/nid1'];
                $date = $link->getDate();
                $object = $link->getParsed()['bl/rl/nid2'];
                $objectInstance = $this->_nebuleInstance->newObject($object);

                ?>

                <div class="sylabeModuleEntityActionTextList<?php echo $bg; ?>">
                    <div class="sylabeModuleEntityActionDivIcon">
                        <?php $this->_display->displayObjectColorIcon($objectInstance); ?>
                    </div>
                    <div>
                        <p class="sylabeModuleEntityActionDate">
                            <?php $this->_display->displayDate($date);
                            echo "\n"; ?>
                        </p>
                        <p class="sylabeModuleEntityActionTitle">
                            <?php echo $objectInstance->getFullName('all'); ?>
                        </p>
                        <p class="sylabeModuleEntityActionType">
                            <?php echo $objectInstance->getType('all'); ?>
                        </p>
                        <p class="sylabeModuleEntityActionFromTo">
                            <?php $this->_echoTraduction('::sylabe:module:entities:To'); ?>
                            &nbsp;<?php $this->_display->displayInlineObjectColorIconName($source); ?><br/>
                        </p>
                    </div>
                </div>
                <?php

                unset($source, $date, $object, $objectInstance);

                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }
            unset($link, $bg);
        }
        unset($links);
    }


    /**
     * Affiche la liste de entités.
     *
     * @return void
     */
    private function _displayMyEntitiesList(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:MyEntities', $this->MODULE_REGISTERED_ICONS[4], true);

        $this->_display->registerInlineContentID('myentities');
    }

    /**
     * Affiche en ligne la liste des entités.
     *
     * @return void
     */
    private function _display_InlineMyEntitiesList(): void
    {
        $list = array();
        $i = 0;
        $list[$i]['object'] = $this->_applicationInstance->getCurrentEntityInstance();
        $list[$i]['param'] = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => false,
            'enableDisplayContent' => false,
            'enableDisplayJS' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        $list[$i]['param']['objectRefs'][0] = $this->_applicationInstance->getCurrentEntityInstance();

        // Marque comme vu.
        //$listOkEntities[$this->_applicationInstance->getCurrentEntity()] = true;
        //$i++;

        // @todo pour les autres entités...

        //$this->_display->displayMessageInformation('A faire...');


        // Affichage
        if ($this->_unlocked) {
            echo $this->_display->getDisplayHookMenuList('::sylabe:module:entities:DisplayMyEntities');
        }

        // Affiche les entités.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
        unset($list);
    }


    /**
     * Affiche la liste des entités connues.
     *
     * @return void
     */
    private function _displayKnownEntitiesList(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:KnownEntities', $this->MODULE_REGISTERED_ICONS[4], true);

        $this->_display->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_nebuleInstance->getSpecialEntities();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            $this->_applicationInstance->getCurrentEntity(),
            '',
            'f',
            $this->_applicationInstance->getCurrentEntity(),
            '',
            '');

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_nebuleInstance->newEntity($link->getParsed()['bl/rl/nid2']);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkEntities);
    }


    /**
     * Affiche la liste des entités qui me connaissent.
     *
     * @return void
     */
    private function _displayKnownByEntitiesList(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:KnownByEntities', $this->MODULE_REGISTERED_ICONS[4], true);

        $this->_display->registerInlineContentID('knownentities');
    }

    private function _display_InlineKnownByEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_nebuleInstance->getSpecialEntities();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            '',
            '',
            'f',
            '',
            $this->_applicationInstance->getCurrentEntity(),
            '');

        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($links as $link) {
            $instance = $this->_nebuleInstance->newEntity($link->getParsed()['bl/rl/nid2']);
            if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid2']])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );

                // Marque comme vu.
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkEntities);
    }


    /**
     * Affiche la liste des entités inconnues.
     *
     * @return void
     */
    private function _displayUnknownEntitiesList(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:UnknownEntities', $this->MODULE_REGISTERED_ICONS[4], true);

        $this->_display->registerInlineContentID('unknownentities');
    }

    private function _display_InlineUnknownEntitiesList(): void
    {
        // Liste des entités déjà affichées.
        $listOkEntities = $this->_nebuleInstance->getSpecialEntities();

        // Liste les entités que j'ai marqué comme connues. @todo revoir la méthode !
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            $this->_applicationInstance->getCurrentEntity(),
            '',
            'f',
            $this->_applicationInstance->getCurrentEntity(),
            '',
            '');
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                $listOkEntities[$link->getParsed()['bl/rl/nid2']] = true;
            }
        }

        // Liste les entités dont je suis marqué comme connu.
        $links = $this->_applicationInstance->getCurrentEntityInstance()->getLinksOnFields(
            '',
            '',
            'f',
            '',
            $this->_applicationInstance->getCurrentEntity(),
            '');

        // Prépare l'affichage.
        if (sizeof($links) != 0) {
            foreach ($links as $link) {
                $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
            }
        }

        // Liste toutes les autres entités.
        $links = $this->_hashEntityObject->getLinksOnFields(
            '',
            '',
            'l',
            '',
            $this->_hashEntity,
            $this->_hashType);

        //Prépare l'affichage.
        if (sizeof($links) != 0) {
            $list = array();
            $i = 0;
            foreach ($links as $link) {
                $id = $link->getParsed()['bl/rl/nid1'];
                $instance = $this->_nebuleInstance->newEntity($id);
                if (!isset($listOkEntities[$id])
                    && $instance->getType('all') == Entity::ENTITY_TYPE
                    && $instance->getIsPublicKey()
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => false,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => true,
                        'displaySize' => 'medium',
                        'displayRatio' => 'short',
                    );

                    // Marque comme vu.
                    $listOkEntities[$id] = true;
                    $i++;
                }
            }
            unset($link, $instance, $id);
            // Affichage
            if (sizeof($list) != 0) {
                echo $this->_display->getDisplayObjectsList($list, 'medium');
            }
            unset($list);
        } else {
            // Pas d'entité.
            $this->_display->displayMessageInformation(
                '::sylabe:module:entities:Display:NoEntity');
        }
        unset($links, $listOkEntities);
    }


    /**
     * Affiche la liste des entités spéciales.
     *
     * @return void
     */
    private function _displaySpecialEntitiesList(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:SpecialEntities', $this->MODULE_REGISTERED_ICONS[4]);

        $this->_display->registerInlineContentID('specialentities');
    }

    private function _display_InlineSpecialEntitiesList(): void
    {
        // Liste des entités.
        $entities = array(
            0 => $this->_nebuleInstance->getPuppetmasterInstance(),
            1 => $this->_nebuleInstance->getSecurityMasterInstance(),
            2 => $this->_nebuleInstance->getCodeMasterInstance(),
            3 => $this->_nebuleInstance->getDirectoryMasterInstance(),
            4 => $this->_nebuleInstance->getTimeMasterInstance(),
            5 => $this->_nebuleInstance->getInstanceEntityInstance(),
        );
        $masters = array(
            0 => '',
            1 => nebule::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE,
            2 => nebule::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE,
            3 => nebule::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE,
            4 => nebule::REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS,
            5 => 'Hôte',
        );
        // Prépare l'affichage.
        $list = array();
        $i = 0;
        foreach ($entities as $i => $entity) {
            $list[$i]['object'] = $entity;
            $list[$i]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => true,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => true,
                'status' => $this->_applicationInstance->getTraductionInstance()->getTraduction($masters[$i]),
                'enableDisplayContent' => false,
                'enableDisplayObjectActions' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
            );
            if ($i < 5) {
                $list[$i]['param']['enableDisplayRefs'] = true;
                $list[$i]['param']['objectRefs'] = array($this->_nebuleInstance->getPuppetmasterInstance());
            }

            $i++;
        }
        unset($entities, $masters, $entity);

        // Affiche les entités.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
        unset($list);
    }


    /**
     * Affiche la création d'une entité.
     */
    private function _displayEntityCreate(): void
    {
        // Si une nouvelle entité vient d'être créée par l'instance des actions.
        if ($this->_createEntityAction) {
            // Prépare l'affichage.
            $list = array();

            if (!$this->_createEntityError && is_a($this->_createEntityInstance, 'Entity')) {
                // Message de bonne création.
                $list[0]['information'] = '::sylabe:module:entities:EntityCreated';
                $list[0]['param']['informationType'] = 'ok';
                $list[0]['param']['displayRatio'] = 'long';

                // Ajoute l'ID public de l'entité.
                $list[1]['object'] = $this->_createEntityInstance;
                $list[1]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    //'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectIcon' => $this->MODULE_REGISTERED_ICONS[0],
                    'status' => $this->_traduction('ID public'),
                );

                // Ajoute l'ID privé de l'entité.
                $privInstance = $this->_nebuleInstance->newObject($this->_createEntityInstance->getPrivateKeyID());
                $list[2]['object'] = $privInstance;
                $list[2]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    //'enableDisplayObjectActions' => false,
                    'enableDisplayJS' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectIcon' => $this->MODULE_REGISTERED_ICONS[9],
                    'status' => $this->_traduction('ID prive'),
                );
                unset($privInstance);
            } else {
                // Affiche un message d'erreur.
                $list[0]['information'] = $this->_traduction('::sylabe:module:entities:EntityNotCreated') . ' : "' . $this->_createEntityErrorMessage . '"';
                $list[0]['param']['informationType'] = 'error';
                $list[0]['param']['displayRatio'] = 'long';
            }

            // Affiche le message et les objets créés.
            echo $this->_display->getDisplayObjectsList($list, 'medium');
            unset($list);
        }

        // Affiche le titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:CreateEntity', $this->MODULE_REGISTERED_ICONS[5], false);

        // Vérifie que la création soit authorisée.
        if ($this->_configuration->checkBooleanOptions(array('permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteEntity'))
            && ($this->_unlocked
                || $this->_configuration->getOptionAsBoolean('permitPublicCreateEntity')
            )
        ) {
            ?>

            <div class="layoutAloneItem">
                <div class="aloneTextItemContent">
                    <form method="post"
                          action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                              . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                              . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY
                              . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                              . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreateNames">
                            <div class="sylabeModuleEntityCreateHeader">
                                <p>
                                    <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityNommage'); ?>

                                </p>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/prefix'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PREFIX; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPrefix"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/prenom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_FIRSTNAME; ?>"
                                       size="20" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPrenom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/surnom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NIKENAME; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntrySurnom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/nom'); ?>

                                </div>
                                <input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_NAME; ?>"
                                       size="20" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryNom"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/suffix'); ?>
                                </div>
                                <input type="text"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_SUFFIX; ?>"
                                       size="10" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntrySuffix"/>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreatePassword">
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName"
                                     id="sylabeModuleEntityCreatePropertyNamePWD1">
                                    <?php $this->_echoTraduction('::Password'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD1; ?>"
                                       size="30" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPWD1"/>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName"
                                     id="sylabeModuleEntityCreatePropertyNamePWD2">
                                    <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityConfirm'); ?>

                                </div>
                                <input type="password"
                                       name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_PASSWORD2; ?>"
                                       size="30" value=""
                                       class="sylabeModuleEntityCreatePropertyEntry"
                                       id="sylabeModuleEntityCreatePropertyEntryPWD2"/>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreate" id="sylabeModuleEntityCreateOther">
                            <div class="sylabeModuleEntityCreateHeader">
                                <p>
                                    <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityOther'); ?>

                                </p>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityAlgorithm'); ?>

                                </div>
                                <select
                                        name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_ALGORITHM; ?>"
                                        class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="<?php echo $this->_configuration->getOptionAsString('cryptoAsymmetricAlgorithm'); ?>"
                                            selected>
                                        <?php echo $this->_nebuleInstance->getConfigurationInstance()->getOptionAsString('cryptoAsymmetricAlgorithm'); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('nebule/objet/entite/type'); ?>

                                </div>
                                <select
                                        name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_TYPE; ?>"
                                        class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="undef" selected>
                                        <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityTypeUndefined'); ?>

                                    </option>
                                    <option value="human">
                                        <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityTypeHuman'); ?>

                                    </option>
                                    <option value="robot">
                                        <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityTypeRobot'); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="sylabeModuleEntityCreateProperty">
                                <div class="sylabeModuleEntityCreatePropertyName">
                                    <?php $this->_echoTraduction('::sylabe:module:entities:CreateEntityAutonomy'); ?>

                                </div>
                                <select
                                        name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_ENTITY_AUTONOMY; ?>"
                                        class="sylabeModuleEntityCreatePropertyEntry">
                                    <option value="y" selected>
                                        <?php $this->_echoTraduction('::yes'); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sylabeModuleEntityCreateSubmit">
                            <input type="submit"
                                   value="<?php $this->_echoTraduction('::sylabe:module:entities:CreateTheEntity'); ?>"
                                   class="sylabeModuleEntityCreateSubmitEntry"/>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        } else {
            $this->_display->displayMessageWarning('::sylabe:module:entities:CreateEntityNotAllowed');
        }
    }


    /**
     * Affiche la recherche d'une entité.
     */
    private function _displayEntitySearch(): void
    {
        // Affiche la création d'une entité.
        $this->_display->displayDivTextTitleH2(
            Displays::DEFAULT_ICON_LF,
            '::sylabe:module:entities:SearchEntity',
            '::sylabe:module:entities:SearchEntityDesc',
            '::sylabe:module:entities:SearchEntityHelp');

        // Vérifie que la création soit authorisée.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitSynchronizeObject','permitSynchronizeLink'))) {
            ?>
            <div class="text">
                <p>


                <form method="get" action="">
                    <input type="hidden" name="mod" value="ent">
                    <input type="hidden" name="view" value="srch">
                    <input type="hidden" name="obj"
                           value="<?php echo $this->_applicationInstance->getCurrentObject(); ?>">
                    <input type="hidden" name="ent"
                           value="<?php echo $this->_nebuleInstance->getCurrentEntity(); ?>">
                    <table border="0" padding="2px">
                        <tr>
                            <td align="right"><?php $this->_echoTraduction('::sylabe:module:entities:Search:URL') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchurl" size="80"
                                       value="<?php echo $this->_searchEntityURL; ?>"></td>
                        </tr>
                        <tr>
                            <td align="right"><?php $this->_echoTraduction('::sylabe:module:entities:Search:AndOr') ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right"><?php $this->_echoTraduction('::sylabe:module:entities:Search:ID') ?>
                            </td>
                            <td>:</td>
                            <td><input type="text" name="srchid" size="80"
                                       value="<?php echo $this->_searchEntityID; ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><input type="submit"
                                       value="<?php $this->_echoTraduction('::sylabe:module:entities:Search:Submit'); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
                <?php

                ?>

                </p>
            </div>
            <?php
            $this->_display->displayMessageInformation('::sylabe:module:entities:SearchEntityLongTime');
        } else {
            $this->_display->displayMessageWarning('::sylabe:module:entities:SearchEntityNotAllowed');
        }
    }


    private function _display_InlineEntitySearch(): void
    {
    }


    /**
     * Affiche les propriétés de l'entité.
     */
    private function _displayEntityProp(): void
    {
        echo $this->_display->getDisplayTitle('::sylabe:module:entities:Desc:AttribsTitle', $this->MODULE_REGISTERED_ICONS[3]);

        // Affiche les propriétés.
        $this->_display->registerInlineContentID('properties');
    }

    private function _display_InlineEntityProp(): void
    {
        // Préparation de la gestion de l'affichage par parties.
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject();
        $displayCount = 0;
        $okDisplay = false;
        if ($startLinkSigne == '') {
            $okDisplay = true;
        }
        $displayNext = false;
        $nextLinkSigne = '';

        $list = array(); // @todo refaire la liste d'affichage avec les nouvelles fonctions.
        $i = 0;

        // Recherche si l'objet a une mise à jour.
        $update = $this->_displayEntityInstance->findUpdate(false, false);
        if ($update != $this->_displayEntity) {
            // A affiner...
            //
            $this->_display->displayMessageWarning(
                $this->_traduction('::sylabe:module:objects:warning:ObjectHaveUpdate'));
        }
        unset($update);

        // Liste des attributs, càd des liens de type l.
        $links = $this->_displayEntityInstance->getLinksOnFields(
            '',
            '',
            '',
            $this->_displayEntityInstance->getID(),
            '',
            '');

        // Affichage des attributs de base.
        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            $attribList = nebule::RESERVED_OBJECTS_LIST;
            $emotionsList = array(
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
            );
            $emotionsIcons = array(
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
            );

            foreach ($links as $i => $link) {
                // Vérifie si la signature de lien est celle de départ de l'affichage.
                if ($link->getSigneValue() == $startLinkSigne) {
                    $okDisplay = true;
                }

                // Enregistre le message suivant à afficher si le compteur d'affichage est dépassé.
                if ($displayNext
                    && $nextLinkSigne == ''
                ) {
                    $nextLinkSigne = $link->getSigneValue();
                }

                // Si l'affichage est permit.
                if ($okDisplay) {
                    // Extraction des attributs.
                    $action = $link->getAction();
                    $showAttrib = false;
                    $showEmotion = false;
                    $hashAttrib = $link->getParsed()['bl/rl/nid3'];
                    $attribName = '';
                    $attribTraduction = '';
                    $hashValue = $link->getParsed()['bl/rl/nid2'];
                    $value = '';
                    $attribValue = '';
                    $emotion = '';

                    // Si action type l.
                    if ($action == 'l') {
                        // Extrait le nom.
                        if ($hashAttrib != '0'
                            && $hashAttrib != ''
                            && $hashValue != '0'
                            && $hashValue != ''
                        ) {
                            $attribInstance = $this->_nebuleInstance->newObject($hashAttrib);
                            $attribName = $attribInstance->readOneLineAsText();
                            unset($attribInstance);
                        }

                        // Vérifie si l'attribut est dans la liste des objets réservés à afficher.
                        if ($attribName != '') {
                            foreach ($attribList as $attribItem) {
                                if ($attribItem == $attribName) {
                                    $showAttrib = true;
                                    break;
                                }
                            }
                        }
                    } // Si action de type f, vérifie si l'attribut est dans la liste des émotions à afficher.
                    elseif ($action == 'f'
                        && $hashValue != '0'
                    ) {
                        foreach ($emotionsList as $item => $emotionItem) {
                            if ($item == $hashValue) {
                                $showEmotion = true;
                                $emotion = $emotionItem;
                                break;
                            }
                        }
                    }

                    // Extrait la valeur.
                    if ($showAttrib
                        && $attribName != ''
                    ) {
                        $valueInstance = $this->_nebuleInstance->newObject($hashValue);
                        $attribValue = $valueInstance->readOneLineAsText();
                        unset($valueInstance);
                        // Vérifie la valeur.
                        if ($attribValue == null) {
                            $attribValue = $this->_applicationInstance->getTraductionInstance()->getTraduction('::noContent');
                        }
                    }

                    if ($showAttrib) {
                        // Affiche l'attribut.
                        ?>

                        <div class="sylabeModuleEntityDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_display->displayHypertextLink($this->_display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
                                <span class="sylabeModuleEntityDescAttrib"><?php $this->_applicationInstance->getTraductionInstance()->echoTraduction($attribName); ?></span>
                                =
                                <span class="sylabeModuleEntityDescValue"><?php echo $attribValue; ?></span>
                            </div>
                        </div>
                        <?php
                    } elseif ($showEmotion) {
                        // Affiche l'émotion.
                        ?>

                        <div class="sylabeModuleEntityDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_display->displayHypertextLink($this->_display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
		<span class="sylabeModuleEntityDescEmotion">
			<?php $this->_display->displayReferenceImage($emotionsIcons[$emotion], $emotionsList[$hashValue]); ?>
            <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction($emotionsList[$hashValue]); ?>
		</span>
                            </div>
                        </div>
                        <?php
                    } elseif ($action == 'l') {
                        // Affiche une erreur si la propriété n'est pas lisible.
                        ?>

                        <div class="sylabeModuleEntityDescError">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleEntityDescIcon">
                                    <?php $this->_display->displayHypertextLink($this->_display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>
                                    &nbsp;
                                    <?php $this->_display->displayInlineIconFace('DEFAULT_ICON_IWARN'); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleEntityDescDate"><?php $this->_display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleEntityDescSigner"><?php $this->_display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleEntityDescContent">
                                <span class="sylabeModuleEntityDescAttrib"><?php $this->_echoTraduction('::sylabe:module:entities:AttribNotDisplayable'); ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        // Si non affichable et lien de type autre que l, annule la permutation de l'indice de fond.
                        $bg = 3 - $bg;
                    }

                    // Actualise le compteur d'affichage.
                    $displayCount++;
                    if ($displayCount >= self::DEFAULT_ATTRIBS_DISPLAY_NUMBER) {
                        $okDisplay = false;
                        $displayNext = true;
                    }
                }

                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }

            // Affiche au besoin le bouton pour afficher les objets suivants.
            if ($displayNext
                && $nextLinkSigne != ''
            ) {
                $url = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_display->getCurrentDisplayView()
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . Displays::DEFAULT_INLINE_COMMAND . '&' . Displays::DEFAULT_INLINE_CONTENT_COMMAND . '=properties'
                    . '&' . Displays::DEFAULT_NEXT_COMMAND . '=' . $nextLinkSigne;
                $this->_display->displayButtonNextObject($nextLinkSigne, $url, $this->_applicationInstance->getTraductionInstance()->getTraduction('::seeMore'));
            }
            unset($links);
        }
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:entities:ModuleName'] = 'Module des entités';
        $this->_table['en-en']['::sylabe:module:entities:ModuleName'] = 'Entities module';
        $this->_table['es-co']['::sylabe:module:entities:ModuleName'] = 'Entities module';
        $this->_table['fr-fr']['::sylabe:module:entities:MenuName'] = 'Entités';
        $this->_table['en-en']['::sylabe:module:entities:MenuName'] = 'Entities';
        $this->_table['es-co']['::sylabe:module:entities:MenuName'] = 'Entities';
        $this->_table['fr-fr']['::sylabe:module:entities:ModuleDescription'] = 'Module de gestion des entités.';
        $this->_table['en-en']['::sylabe:module:entities:ModuleDescription'] = 'Module to manage entities.';
        $this->_table['es-co']['::sylabe:module:entities:ModuleDescription'] = 'Module to manage entities.';
        $this->_table['fr-fr']['::sylabe:module:entities:ModuleHelp'] = "Ce module permet de voir les entités, de gérer les relations et de changer l'entité en cours d'utilisation.";
        $this->_table['en-en']['::sylabe:module:entities:ModuleHelp'] = 'This module permit to see entites, to manage related and to change of corrent entity.';
        $this->_table['es-co']['::sylabe:module:entities:ModuleHelp'] = 'This module permit to see entites, to manage related and to change of corrent entity.';

        $this->_table['fr-fr']['::sylabe:module:entities:AppTitle1'] = 'Entités';
        $this->_table['en-en']['::sylabe:module:entities:AppTitle1'] = 'Entities';
        $this->_table['es-co']['::sylabe:module:entities:AppTitle1'] = 'Entities';
        $this->_table['fr-fr']['::sylabe:module:entities:AppDesc1'] = 'Gestion des entités.';
        $this->_table['en-en']['::sylabe:module:entities:AppDesc1'] = 'Manage entities.';
        $this->_table['es-co']['::sylabe:module:entities:AppDesc1'] = 'Manage entities.';

        /*$this->_table['fr-fr']['::sylabe:module:entities:display:SynchronizeEntities']="Synchronise l'entité en cours";
		$this->_table['en-en']['::sylabe:module:entities:display:SynchronizeEntities']='Synchronize current entity';
		$this->_table['es-co']['::sylabe:module:entities:display:SynchronizeEntities']='Synchronize current entity';
		$this->_table['fr-fr']['::sylabe:module:entities:display:ListEntities']='Lister les entités';
		$this->_table['en-en']['::sylabe:module:entities:display:ListEntities']='Show list of entities';
		$this->_table['es-co']['::sylabe:module:entities:display:ListEntities']='Show list of entities';
		*/

        $this->_table['fr-fr']['::sylabe:module:entities:EntityCreated'] = 'Nouvelle entité créée';
        $this->_table['en-en']['::sylabe:module:entities:EntityCreated'] = 'New entity created';
        $this->_table['es-co']['::sylabe:module:entities:EntityCreated'] = 'New entity created';
        $this->_table['fr-fr']['::sylabe:module:entities:EntityNotCreated'] = "La nouvelle entité n'a pas pu être créée"; // Message d'erreur.
        $this->_table['en-en']['::sylabe:module:entities:EntityNotCreated'] = "The new entity can't be created";
        $this->_table['es-co']['::sylabe:module:entities:EntityNotCreated'] = "The new entity can't be created";

        $this->_table['fr-fr']['::sylabe:module:entities:ShowEntity'] = "L'entité";
        $this->_table['en-en']['::sylabe:module:entities:ShowEntity'] = 'The entity';
        $this->_table['es-co']['::sylabe:module:entities:ShowEntity'] = 'The entity';

        $this->_table['fr-fr']['::sylabe:module:entities:DescriptionEntity'] = "L'entité";
        $this->_table['en-en']['::sylabe:module:entities:DescriptionEntity'] = 'This entity';
        $this->_table['es-co']['::sylabe:module:entities:DescriptionEntity'] = 'This entity';
        $this->_table['fr-fr']['::sylabe:module:entities:DescriptionEntityDesc'] = "Description de l'entité.";
        $this->_table['en-en']['::sylabe:module:entities:DescriptionEntityDesc'] = 'About this entity.';
        $this->_table['es-co']['::sylabe:module:entities:DescriptionEntityDesc'] = 'About this entity.';

        $this->_table['fr-fr']['::sylabe:module:entities:MyEntities'] = 'Mes entités';
        $this->_table['en-en']['::sylabe:module:entities:MyEntities'] = 'My entities';
        $this->_table['es-co']['::sylabe:module:entities:MyEntities'] = 'My entities';
        $this->_table['fr-fr']['::sylabe:module:entities:MyEntitiesDesc'] = 'Toutes les entités sous contrôle.';
        $this->_table['en-en']['::sylabe:module:entities:MyEntitiesDesc'] = 'All entities under control.';
        $this->_table['es-co']['::sylabe:module:entities:MyEntitiesDesc'] = 'All entities under control.';
        $this->_table['fr-fr']['::sylabe:module:entities:MyEntitiesHelp'] = "La liste des entités sous contrôle, c'est à dire avec lequelles ont peut instantanément basculer.";
        $this->_table['en-en']['::sylabe:module:entities:MyEntitiesHelp'] = 'The list of all entities under control.';
        $this->_table['es-co']['::sylabe:module:entities:MyEntitiesHelp'] = 'The list of all entities under control.';

        $this->_table['fr-fr']['::sylabe:module:entities:KnownEntities'] = 'Entités connues';
        $this->_table['en-en']['::sylabe:module:entities:KnownEntities'] = 'Known entities';
        $this->_table['es-co']['::sylabe:module:entities:KnownEntities'] = 'Known entities';
        $this->_table['fr-fr']['::sylabe:module:entities:KnownEntitiesDesc'] = 'Toutes les entités connues.';
        $this->_table['en-en']['::sylabe:module:entities:KnownEntitiesDesc'] = 'All known entities.';
        $this->_table['es-co']['::sylabe:module:entities:KnownEntitiesDesc'] = 'All known entities.';
        $this->_table['fr-fr']['::sylabe:module:entities:KnownEntitiesHelp'] = "La liste des entités que l'on connait, amies ou pas.";
        $this->_table['en-en']['::sylabe:module:entities:KnownEntitiesHelp'] = 'The list of all entities we known, friends or not.';
        $this->_table['es-co']['::sylabe:module:entities:KnownEntitiesHelp'] = 'The list of all entities we known, friends or not.';
        $this->_table['fr-fr']['::sylabe:module:entities:KnownEntity'] = 'Je connais cette entité';
        $this->_table['en-en']['::sylabe:module:entities:KnownEntity'] = 'I known this entity';
        $this->_table['es-co']['::sylabe:module:entities:KnownEntity'] = 'I known this entity';

        $this->_table['fr-fr']['::sylabe:module:entities:SpecialEntities'] = 'entités spéciales';
        $this->_table['en-en']['::sylabe:module:entities:SpecialEntities'] = 'Specials entities';
        $this->_table['es-co']['::sylabe:module:entities:SpecialEntities'] = 'Specials entities';
        $this->_table['fr-fr']['::sylabe:module:entities:SpecialEntitiesDesc'] = 'Les entités spécifiques à <i>nebule</i>.';
        $this->_table['en-en']['::sylabe:module:entities:SpecialEntitiesDesc'] = 'Specifics entities to <i>nebule</i>.';
        $this->_table['es-co']['::sylabe:module:entities:SpecialEntitiesDesc'] = 'Specifics entities to <i>nebule</i>.';
        $this->_table['fr-fr']['::sylabe:module:entities:SpecialEntitiesHelp'] = 'La liste des entités spécifiques à <i>nebule</i>.';
        $this->_table['en-en']['::sylabe:module:entities:SpecialEntitiesHelp'] = 'The list of specifics entities to <i>nebule</i>.';
        $this->_table['es-co']['::sylabe:module:entities:SpecialEntitiesHelp'] = 'The list of specifics entities to <i>nebule</i>.';

        $this->_table['fr-fr']['::sylabe:module:entities:UnknownEntities'] = 'Entités inconnues';
        $this->_table['en-en']['::sylabe:module:entities:UnknownEntities'] = 'Unknown entities';
        $this->_table['es-co']['::sylabe:module:entities:UnknownEntities'] = 'Unknown entities';
        $this->_table['fr-fr']['::sylabe:module:entities:UnknownEntitiesDesc'] = 'Toutes les autres entités, non connues.';
        $this->_table['en-en']['::sylabe:module:entities:UnknownEntitiesDesc'] = 'All unknown entities.';
        $this->_table['es-co']['::sylabe:module:entities:UnknownEntitiesDesc'] = 'All unknown entities.';
        $this->_table['fr-fr']['::sylabe:module:entities:UnknownEntitiesHelp'] = "La liste des entités que l'on connait pas.";
        $this->_table['en-en']['::sylabe:module:entities:UnknownEntitiesHelp'] = 'The list of all others entities.';
        $this->_table['es-co']['::sylabe:module:entities:UnknownEntitiesHelp'] = 'The list of all others entities.';

        $this->_table['fr-fr']['::sylabe:module:entities:KnownByEntities'] = 'Connu de ces entités';
        $this->_table['en-en']['::sylabe:module:entities:KnownByEntities'] = 'Known by entities';
        $this->_table['es-co']['::sylabe:module:entities:KnownByEntities'] = 'Known by entities';
        $this->_table['fr-fr']['::sylabe:module:entities:KnownByEntitiesDesc'] = 'Toutes les entités qui me connaissent.';
        $this->_table['en-en']['::sylabe:module:entities:KnownByEntitiesDesc'] = 'All known by entities.';
        $this->_table['es-co']['::sylabe:module:entities:KnownByEntitiesDesc'] = 'All known by entities.';
        $this->_table['fr-fr']['::sylabe:module:entities:KnownByEntitiesHelp'] = "La liste des entités qui me connaissent.";
        $this->_table['en-en']['::sylabe:module:entities:KnownByEntitiesHelp'] = 'The list of all entities who known me.';
        $this->_table['es-co']['::sylabe:module:entities:KnownByEntitiesHelp'] = 'The list of all entities who known me.';

        $this->_table['fr-fr']['::sylabe:module:entities:SynchronizeEntity'] = 'Synchroniser';
        $this->_table['en-en']['::sylabe:module:entities:SynchronizeEntity'] = 'Synchronize';
        $this->_table['es-co']['::sylabe:module:entities:SynchronizeEntity'] = 'Synchronize';
        $this->_table['fr-fr']['::sylabe:module:entities:SynchronizeKnownEntities'] = 'Synchroniser toutes les entités';
        $this->_table['en-en']['::sylabe:module:entities:SynchronizeKnownEntities'] = 'Synchronize all entities';
        $this->_table['es-co']['::sylabe:module:entities:SynchronizeKnownEntities'] = 'Synchronize all entities';

        $this->_table['fr-fr']['::sylabe:module:entities:AttribNotDisplayable'] = 'Propriété non affichable !';
        $this->_table['en-en']['::sylabe:module:entities:AttribNotDisplayable'] = 'Attribut not displayable!';
        $this->_table['es-co']['::sylabe:module:entities:AttribNotDisplayable'] = 'Attribut not displayable!';

        $this->_table['fr-fr']['::sylabe:module:entities:ListEntities'] = 'Lister';
        $this->_table['en-en']['::sylabe:module:entities:ListEntities'] = 'List';
        $this->_table['es-co']['::sylabe:module:entities:ListEntities'] = 'List';
        $this->_table['fr-fr']['::sylabe:module:entities:ListEntitiesDesc'] = 'Lister les entités';
        $this->_table['en-en']['::sylabe:module:entities:ListEntitiesDesc'] = 'Show list of entities';
        $this->_table['es-co']['::sylabe:module:entities:ListEntitiesDesc'] = 'Show list of entities';

        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntity'] = 'Créer';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntity'] = 'Create';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntity'] = 'Create';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityNommage'] = 'Nommage';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityNommage'] = 'Naming';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityNommage'] = 'Naming';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityConfirm'] = 'Confirmation';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityConfirm'] = 'Confirm';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityConfirm'] = 'Confirm';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityOther'] = 'Autre';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityOther'] = 'Other';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityOther'] = 'Otro';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityAlgorithm'] = 'Algorithme';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityAlgorithm'] = 'Algorithm';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityAlgorithm'] = 'Algoritmo';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityTypeUndefined'] = '(Indéfini)';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityTypeUndefined'] = '(Undefined)';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityTypeUndefined'] = '(Undefined)';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityTypeHuman'] = 'Humain';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityTypeHuman'] = 'Human';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityTypeHuman'] = 'Humano';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityTypeRobot'] = 'Robot';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityTypeRobot'] = 'Robot';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityTypeRobot'] = 'Robot';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityAutonomy'] = 'Entité indépendante';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityAutonomy'] = 'Independent entity';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityAutonomy'] = 'Entidad independiente';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateTheEntity'] = "Créer l'entité";
        $this->_table['en-en']['::sylabe:module:entities:CreateTheEntity'] = 'Create the entity';
        $this->_table['es-co']['::sylabe:module:entities:CreateTheEntity'] = 'Create the entity';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityDesc'] = 'Créer une entité.';
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityDesc'] = 'Create entity.';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityDesc'] = 'Create entity.';
        $this->_table['fr-fr']['::sylabe:module:entities:CreateEntityNotAllowed'] = "La création d'entité est désactivée !";
        $this->_table['en-en']['::sylabe:module:entities:CreateEntityNotAllowed'] = 'Entity creation is disabled!';
        $this->_table['es-co']['::sylabe:module:entities:CreateEntityNotAllowed'] = 'Entity creation is disabled!';

        $this->_table['fr-fr']['::sylabe:module:entities:SearchEntity'] = 'Chercher';
        $this->_table['en-en']['::sylabe:module:entities:SearchEntity'] = 'Search';
        $this->_table['es-co']['::sylabe:module:entities:SearchEntity'] = 'Search';
        $this->_table['fr-fr']['::sylabe:module:entities:SearchEntityDesc'] = 'Rechercher une entité.';
        $this->_table['en-en']['::sylabe:module:entities:SearchEntityDesc'] = 'Search entity.';
        $this->_table['es-co']['::sylabe:module:entities:SearchEntityDesc'] = 'Search entity.';
        $this->_table['fr-fr']['::sylabe:module:entities:SearchEntityHelp'] = 'Rechercher une entité connue.';
        $this->_table['en-en']['::sylabe:module:entities:SearchEntityHelp'] = 'Search a known entity.';
        $this->_table['es-co']['::sylabe:module:entities:SearchEntityHelp'] = 'Search a known entity.';
        $this->_table['fr-fr']['::sylabe:module:entities:SearchEntityNotAllowed'] = "La recherche d'entité est désactivée !";
        $this->_table['en-en']['::sylabe:module:entities:SearchEntityNotAllowed'] = 'Entity search is disabled!';
        $this->_table['es-co']['::sylabe:module:entities:SearchEntityNotAllowed'] = 'Entity search is disabled!';
        $this->_table['fr-fr']['::sylabe:module:entities:SearchEntityLongTime'] = 'La recherche sur identifiant uniquement peut prendre du temps...';
        $this->_table['en-en']['::sylabe:module:entities:SearchEntityLongTime'] = 'The search on identifier only can take some time...';
        $this->_table['es-co']['::sylabe:module:entities:SearchEntityLongTime'] = 'The search on identifier only can take some time...';
        $this->_table['fr-fr']['::sylabe:module:entities:Search:URL'] = 'Adresse de présence';
        $this->_table['en-en']['::sylabe:module:entities:Search:URL'] = 'Address of localisation';
        $this->_table['es-co']['::sylabe:module:entities:Search:URL'] = 'Address of localisation';
        $this->_table['fr-fr']['::sylabe:module:entities:Search:AndOr'] = 'et/ou';
        $this->_table['en-en']['::sylabe:module:entities:Search:AndOr'] = 'and/or';
        $this->_table['es-co']['::sylabe:module:entities:Search:AndOr'] = 'y/o';
        $this->_table['fr-fr']['::sylabe:module:entities:Search:ID'] = 'Identifiant';
        $this->_table['en-en']['::sylabe:module:entities:Search:ID'] = 'Identifier';
        $this->_table['es-co']['::sylabe:module:entities:Search:ID'] = 'Identifier';
        $this->_table['fr-fr']['::sylabe:module:entities:Search:Submit'] = 'Rechercher';
        $this->_table['en-en']['::sylabe:module:entities:Search:Submit'] = 'Submit';
        $this->_table['es-co']['::sylabe:module:entities:Search:Submit'] = 'Submit';

        $this->_table['fr-fr']['::sylabe:module:entities:disp:ConnectWith'] = 'Se connecter avec cette entité';
        $this->_table['en-en']['::sylabe:module:entities:disp:ConnectWith'] = 'Connect with this entity';
        $this->_table['es-co']['::sylabe:module:entities:disp:ConnectWith'] = 'Connect with this entity';
        $this->_table['fr-fr']['::sylabe:module:entities:disp:Disconnect'] = "Verrouiller l'entité";
        $this->_table['en-en']['::sylabe:module:entities:disp:Disconnect'] = 'Lock entity';
        $this->_table['es-co']['::sylabe:module:entities:disp:Disconnect'] = 'Lock entity';

        $this->_table['fr-fr']['::sylabe:module:entities:puppetmaster'] = "l'entité de référence de <i>nebule</i>, le maître des clés.";
        $this->_table['en-en']['::sylabe:module:entities:puppetmaster'] = 'The reference entity of <i>nebule</i>, the master of keys.';
        $this->_table['es-co']['::sylabe:module:entities:puppetmaster'] = 'The reference entity of <i>nebule</i>, the master of keys.';

        $this->_table['fr-fr']['::sylabe:module:entities:SecurityMaster'] = "l'entité maîtresse de la sécurité.";
        $this->_table['en-en']['::sylabe:module:entities:SecurityMaster'] = 'The master entity of security.';
        $this->_table['es-co']['::sylabe:module:entities:SecurityMaster'] = 'The master entity of security.';

        $this->_table['fr-fr']['::sylabe:module:entities:CodeMaster'] = "l'entité maîtresse du code.";
        $this->_table['en-en']['::sylabe:module:entities:CodeMaster'] = 'The master entity of code.';
        $this->_table['es-co']['::sylabe:module:entities:CodeMaster'] = 'The master entity of code.';

        $this->_table['fr-fr']['::sylabe:module:entities:DirectoryMaster'] = "l'entité maîtresse de l'annuaire.";
        $this->_table['en-en']['::sylabe:module:entities:DirectoryMaster'] = 'The master entity of directory.';
        $this->_table['es-co']['::sylabe:module:entities:DirectoryMaster'] = 'The master entity of directory.';

        $this->_table['fr-fr']['::sylabe:module:entities:TimeMaster'] = "l'entité maîtresse du temps.";
        $this->_table['en-en']['::sylabe:module:entities:TimeMaster'] = 'The master entity of time.';
        $this->_table['es-co']['::sylabe:module:entities:TimeMaster'] = 'The master entity of time.';

        $this->_table['fr-fr']['::sylabe:module:entities:From'] = 'De';
        $this->_table['en-en']['::sylabe:module:entities:From'] = 'From';
        $this->_table['es-co']['::sylabe:module:entities:From'] = 'From';
        $this->_table['fr-fr']['::sylabe:module:entities:To'] = 'Pour';
        $this->_table['en-en']['::sylabe:module:entities:To'] = 'To';
        $this->_table['es-co']['::sylabe:module:entities:To'] = 'To';

        $this->_table['fr-fr']['::sylabe:module:entities:DisplayEntityMessages'] = 'Liste des messages de %s.';
        $this->_table['en-en']['::sylabe:module:entities:DisplayEntityMessages'] = 'List of messages for %s.';
        $this->_table['es-co']['::sylabe:module:entities:DisplayEntityMessages'] = 'List of messages for %s.';
        $this->_table['fr-fr']['::sylabe:module:entities:DisplayEntityPublicMessages'] = 'Liste des messages publics de %s.';
        $this->_table['en-en']['::sylabe:module:entities:DisplayEntityPublicMessages'] = 'List of public messages for %s.';
        $this->_table['es-co']['::sylabe:module:entities:DisplayEntityPublicMessages'] = 'List of public messages for %s.';
        $this->_table['fr-fr']['::sylabe:module:entities:DisplayEntityPublicMessagesWarning'] = 'Les messages protégés ou dissimulés ne sont pas accessibles.';
        $this->_table['en-en']['::sylabe:module:entities:DisplayEntityPublicMessagesWarning'] = 'Protected ou hidden messages are not availables.';
        $this->_table['es-co']['::sylabe:module:entities:DisplayEntityPublicMessagesWarning'] = 'Protected ou hidden messages are not availables.';

        $this->_table['fr-fr']['::sylabe:module:entities:AuthLockHelp'] = 'Se déconnecter...';
        $this->_table['en-en']['::sylabe:module:entities:AuthLockHelp'] = 'Unconnecting...';
        $this->_table['es-co']['::sylabe:module:entities:AuthLockHelp'] = 'Unconnecting...';
        $this->_table['fr-fr']['::sylabe:module:entities:AuthUnlockHelp'] = 'Se connecter...';
        $this->_table['en-en']['::sylabe:module:entities:AuthUnlockHelp'] = 'Connecting...';
        $this->_table['es-co']['::sylabe:module:entities:AuthUnlockHelp'] = 'Connecting...';

        $this->_table['fr-fr']['::sylabe:module:entities:Protected'] = 'Message protégé.';
        $this->_table['en-en']['::sylabe:module:entities:Protected'] = 'Message protected.';
        $this->_table['es-co']['::sylabe:module:entities:Protected'] = 'Message protected.';
        $this->_table['fr-fr']['::sylabe:module:entities:Obfuscated'] = 'Message dissimulé.';
        $this->_table['en-en']['::sylabe:module:entities:Obfuscated'] = 'Message obfuscated.';
        $this->_table['es-co']['::sylabe:module:entities:Obfuscated'] = 'Message obfuscated.';

        $this->_table['fr-fr']['::sylabe:module:entities:Desc:AttribsTitle'] = "Propriétés de l'objet";
        $this->_table['en-en']['::sylabe:module:entities:Desc:AttribsTitle'] = "Object's attributs";
        $this->_table['es-co']['::sylabe:module:entities:Desc:AttribsTitle'] = "Object's attributs";
        $this->_table['fr-fr']['::sylabe:module:entities:Desc:AttribsDesc'] = "Toutes les propriétés de l'objet de l'entité.";
        $this->_table['en-en']['::sylabe:module:entities:Desc:AttribsDesc'] = "All attributs of the entity's object.";
        $this->_table['es-co']['::sylabe:module:entities:Desc:AttribsDesc'] = "All attributs of the entity's object.";
        $this->_table['fr-fr']['::sylabe:module:entities:Desc:AttribsHelp'] = "Toutes les propriétés de l'objet.";
        $this->_table['en-en']['::sylabe:module:entities:Desc:AttribsHelp'] = 'All attributs of the object.';
        $this->_table['es-co']['::sylabe:module:entities:Desc:AttribsHelp'] = 'All attributs of the object.';
        $this->_table['fr-fr']['::sylabe:module:entities:Desc:Attrib'] = 'Propriété';
        $this->_table['en-en']['::sylabe:module:entities:Desc:Attrib'] = 'Attribut';
        $this->_table['es-co']['::sylabe:module:entities:Desc:Attrib'] = 'Attribut';
        $this->_table['fr-fr']['::sylabe:module:entities:Desc:Value'] = 'Valeur';
        $this->_table['en-en']['::sylabe:module:entities:Desc:Value'] = 'Value';
        $this->_table['es-co']['::sylabe:module:entities:Desc:Value'] = 'Value';
        $this->_table['fr-fr']['::sylabe:module:entities:Desc:Signer'] = 'Emetteur';
        $this->_table['en-en']['::sylabe:module:entities:Desc:Signer'] = 'Sender';
        $this->_table['es-co']['::sylabe:module:entities:Desc:Signer'] = 'Sender';

        $this->_table['fr-fr']['::sylabe:module:entities:Display:NoEntity'] = "Pas d'entité à afficher.";
        $this->_table['en-en']['::sylabe:module:entities:Display:NoEntity'] = 'No entity to display.';
        $this->_table['es-co']['::sylabe:module:entities:Display:NoEntity'] = 'No entity to display.';
    }
}


/**
 * Cette application permet de gérer les groupes.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleGroups extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:groups:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:groups:MenuName';
    protected $MODULE_COMMAND_NAME = 'grp';
    protected $MODULE_DEFAULT_VIEW = 'disp';
    protected $MODULE_DESCRIPTION = '::sylabe:module:groups:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2';
    protected $MODULE_HELP = '::sylabe:module:groups:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('list', 'listall', 'setgroup', 'unsetgroup', 'addmarked', 'disp');
    protected $MODULE_REGISTERED_ICONS = array(
        '0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2',    // 0 : Icône des groupes.
        '819babe3072d50f126a90c982722568a7ce2ddd2b294235f40679f9d220e8a0a',    // 1 : Créer un groupe.
        'a269514d2b940d8269993a6f0138f38bbb86e5ac387dcfe7b810bf871002edf3',    // 2 : Ajouter objets marqués.
    );
    protected $MODULE_APP_TITLE_LIST = array('::sylabe:module:groups:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('0390b7edb0dc9d36b9674c8eb045a75a7380844325be7e3b9557c031785bc6a2');
    protected $MODULE_APP_DESC_LIST = array('::sylabe:module:groups:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('list');

    private $_hashGroup, $_hashGroupClosed, $_hashGroupObject, $_hashGroupClosedObject;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
        $this->_hashGroup = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE);
        $this->_hashGroupObject = $this->_nebuleInstance->newObject($this->_hashGroup);
        $this->_hashGroupClosed = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
        $this->_hashGroupClosedObject = $this->_nebuleInstance->newObject($this->_hashGroupClosed);
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        $hookArray = array();
        if ($object == 'none') {
            $object = $this->_nebuleInstance->getCurrentGroup();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        switch ($hookName) {
            case 'selfMenu':
            case 'typeMenuGroup':
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() != $this->MODULE_REGISTERED_VIEWS[0]) {
                    // Voir les groupes de l'entité.
                    $hookArray[0]['name'] = '::sylabe:module:groups:display:MyGroups';
                    $hookArray[0]['icon'] = $this->MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                } else {
                    // Voir les groupes des autres entités.
                    $hookArray[0]['name'] = '::sylabe:module:groups:display:Groups';
                    $hookArray[0]['icon'] = $this->MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1];
                }
                // Si l'entité est déverrouillée.
                if ($this->_unlocked) {
                    if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[0]) {
                        // Créer un groupe.
                        $hookArray[1]['name'] = '::sylabe:module:groups:display:createGroup';
                        $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2];

                        // Recherche si il y a des objets marqués.
                        $markList = $this->_applicationInstance->getMarkObjectList();

                        // Si la liste des marques n'est pas vide.
                        if (sizeof($markList) != 0) {
                            // Ajouter les objets marqués.
                            $hookArray[2]['name'] = '::sylabe:module:groups:display:AddMarkedObjects';
                            $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                            $hookArray[2]['desc'] = '';
                            $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[4]
                                . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $object
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;
                        }
                        unset($markList);
                    }
                }
                if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[5]) {
                    // Refuser l'objet comme un groupe.
                    $hookArray[1]['name'] = '::sylabe:module:groups:display:unmakeGroup';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                break;

            case 'selfMenuGroup':
                // Refuser l'objet comme un groupe.
                $hookArray[1]['name'] = '::sylabe:module:groups:display:unmakeGroup';
                $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                $hookArray[1]['desc'] = '';
                $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                break;

            case '::sylabe:module:group:remove':
                // Si l'entité est déverrouillée.
                if ($this->_unlocked && $this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                    // Retourner à la liste des groupes.
                    $hookArray[0]['name'] = '::sylabe:module:groups:display:MyGroups';
                    $hookArray[0]['icon'] = $this->MODULE_LOGO;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                    // Supprimer le groupe.
                    $hookArray[1]['name'] = '::sylabe:module:groups:display:deleteGroup';
                    $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['css'] = 'oneAction-bg-warn';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_GROUP . '=' . $object
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                break;

            case '::sylabe:module:entities:MenuNameSelfMenu':
                $hookArray[0]['name'] = '::sylabe:module:groups:display:MyGroups';
                $hookArray[0]['icon'] = $this->MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity();
                break;

            case 'selfMenuObject':
                // Si l'entité est déverrouillée.
                if ($this->_unlocked) {
                    // Affiche si l'objet courant est un groupe.
                    if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
                        // Voir comme groupe.
                        $hookArray[0]['name'] = '::sylabe:module:groups:display:seeAsGroup';
                        $hookArray[0]['icon'] = $this->MODULE_LOGO;
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5]
                            . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $object;

                        if ($this->_unlocked) {
                            // Refuser l'objet comme un groupe.
                            $hookArray[1]['name'] = '::sylabe:module:groups:display:unmakeGroup';
                            $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LX;
                            $hookArray[1]['desc'] = '';
                            $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    } // Ou si c'est un groupe pour une autre entité.
                    elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
                        // Voir comme groupe.
                        $hookArray[0]['name'] = '::sylabe:module:groups:display:seeAsGroup';
                        $hookArray[0]['icon'] = $this->MODULE_LOGO;
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5]
                            . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $object;

                        if ($this->_unlocked) {
                            // Faire de l'objet un groupe pour moi aussi.
                            $hookArray[1]['name'] = '::sylabe:module:groups:display:makeGroupMe';
                            $hookArray[1]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                            $hookArray[1]['desc'] = '';
                            $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                            // Refuser l'objet comme un groupe.
                            $hookArray[2]['name'] = '::sylabe:module:groups:display:refuseGroup';
                            $hookArray[2]['icon'] = Displays::DEFAULT_ICON_LX;
                            $hookArray[2]['desc'] = '';
                            $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_hashGroup . '_' . $object . '_0'
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    } // Ou si ce n'est pas un groupe.
                    else {
                        if ($this->_unlocked) {
                            // Faire de l'objet un groupe.
                            $hookArray[0]['name'] = '::sylabe:module:groups:display:makeGroup';
                            $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                            $hookArray[0]['desc'] = '';
                            $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_' . $this->_hashGroup . '_' . $object . '_0'
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    }
                }
                break;

            case 'selfMenuEntity':
                $hookArray[0]['name'] = '::sylabe:module:groups:display:MyGroups';
                $hookArray[0]['icon'] = $this->MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object;
                break;

            case 'typeMenuEntity':
                $hookArray[0]['name'] = '::sylabe:module:groups:display:Groups';
                $hookArray[0]['icon'] = $this->MODULE_LOGO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0];
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    protected function _displayFull(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayGroups();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayAllGroups();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateGroup();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayRemoveGroup();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayAddMarkedObjects();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_displayGroup();
                break;
            default:
                $this->_displayGroups();
                break;
        }
    }


    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    protected function _displayInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineGroups();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineAllGroups();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineAddMarkedObjects();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_display_InlineGroup();
                break;
        }
    }


    /**
     * Affiche les groupes de l'entité en cours de visualisation.
     *
     * @return void
     */
    private function _displayGroups(): void
    {
        // Si un groupe a été créé.
        if ($this->_applicationInstance->getActionInstance()->getCreateGroup()) {
            $createGroupID = $this->_applicationInstance->getActionInstance()->getCreateGroupID();
            $createGroupInstance = $this->_applicationInstance->getActionInstance()->getCreateGroupInstance();
            $createGroupError = $this->_applicationInstance->getActionInstance()->getCreateGroupError();
            $createGroupErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateGroupErrorMessage();

            // Si la création à réussi.
            if (!$createGroupError
                && is_a($createGroupInstance, 'Group')
            ) {
                $instance = $this->_nebuleInstance->newObject($createGroupID);

                $list = array();

                // Ajout du message de création.
                $list[0]['information'] = '::sylabe:module:groups:display:OKCreateGroup';
                $list[0]['param'] = array(
                    'enableDisplayIcon' => true,
                    'informationType' => 'ok',
                    'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                    'displayRatio' => 'short',
                );

                // Affiche l'objet référence.
                $list[1]['object'] = $instance;
                $list[1]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => false,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                    'displayRatio' => 'short',
                    'enableDisplayID' => false,
                    'flagProtection' => $instance->getMarkProtected(),
                    'enableDisplaySelfHook' => true,
                    'selfHookName' => 'selfMenuObject',
                    'enableDisplayTypeHook' => false,
                    'enableDisplayJS' => true,
                    'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5]
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $createGroupID,
                );

                // Affiche la liste de l'objet et du message.
                echo $this->_display->getDisplayObjectsList($list, 'medium');
                unset($list);
            } else {
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'informationType' => 'error',
                );
                echo $this->_display->getDisplayInformation('::sylabe:module:groups:display:notOKCreateGroup', $param);
            }
        }

        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:groups:display:MyGroups', $this->MODULE_LOGO);

        // Affiche le contenu.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('groups');
    }

    /**
     * Affiche les groupes de l'entité en cours de visualisation, en ligne.
     *
     * @return void
     */
    private function _display_InlineGroups(): void
    {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID($this->_applicationInstance->getCurrentEntity(), '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_nebuleInstance->newGroup($group);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkGroups);
    }


    /**
     * Affiche les groupes de toutes les entités.
     *
     * @return void
     */
    private function _displayAllGroups(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:groups:display:otherGroups', $this->MODULE_LOGO);

        // Affiche le contenu.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('allgroups');
    }

    /**
     * Affiche les groupes de toutes les entités, en ligne.
     *
     * @todo filtrer sur tout sauf l'entité en cours.
     *
     * @return void
     */
    private function _display_InlineAllGroups(): void
    {
        // Liste tous les groupes.
        $listGroups = $this->_nebuleInstance->getListGroupsID('', '');

        // Prépare l'affichage.
        $list = array();
        $listOkGroups = array();
        $i = 0;
        foreach ($listGroups as $group) {
            $instance = $this->_nebuleInstance->newGroup($group);

            // Extraction des entités signataires.
            $signers = $instance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_GROUPE);

            if (!isset($listOkGroups[$group])
                && !isset($signers[$this->_applicationInstance->getCurrentEntity()])
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $signers,
                );

                // Marque comme vu.
                $listOkGroups[$group] = true;
                $i++;
            }
            unset($signers);
        }
        unset($link, $instance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        unset($list, $links, $listOkGroups);


        /*		// Liste tous les groupes.
		$links = $this->_nebuleInstance->getListGroupsLinks('', '');
		if ( sizeof($links) != 0 )
		{
			$list = array();
			$i=0;
			foreach ( $links as $i => $link )
			{
				if ( $link->getParsed()['bs/rs1/eid'] != $this->_nebuleInstance->getCurrentEntity() )
				{
					$instance = $this->_nebuleInstance->newObject($link->getParsed()['bl/rl/nid1']);   erreur de fonction !!!
					$instanceEntity = $this->_nebuleInstance->newEntity($link->getParsed()['bs/rs1/eid']);
					$closed = '::GroupeOuvert';
					if ( $instance->getMarkClosed() )
						$closed = '::GroupeFerme';
					$list[$i]['object'] = $instance;
					$list[$i]['entity'] = $instanceEntity;
					$list[$i]['icon'] = '';
					$list[$i]['htlink'] = '';
					$list[$i]['desc'] = $this->_traduction($closed);
					$list[$i]['actions'] = array();
					if ( $this->_unlocked )
					{
						// Supprimer le groupe.
						$list[$i]['actions'][0]['name'] = '::sylabe:module:groups:display:unmakeGroup';
						$list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
						$list[$i]['actions'][0]['htlink'] = '?'.Display::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this->MODULE_COMMAND_NAME
							.'&'.Display::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this->MODULE_REGISTERED_VIEWS[3]
							.'&'.nebule::COMMAND_SELECT_OBJECT.'='.$link->getParsed()['bl/rl/nid1'];
						// Utiliser comme groupe ouvert.
						$list[$i]['actions'][1]['name'] = '::sylabe:module:groups:display:useAsGroupOpened';
						$list[$i]['actions'][1]['icon'] = Displays::DEFAULT_ICON_LL;
						$list[$i]['actions'][1]['htlink'] = '?'.Display::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this->MODULE_COMMAND_NAME
							.'&'.Display::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this->MODULE_REGISTERED_VIEWS[1]
							.'&'.Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroup.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
						// Utiliser comme groupe fermé.
						$list[$i]['actions'][2]['name'] = '::sylabe:module:groups:display:useAsGroupClosed';
						$list[$i]['actions'][2]['icon'] = Displays::DEFAULT_ICON_LLL;
						$list[$i]['actions'][2]['htlink'] = '?'.Display::DEFAULT_DISPLAY_COMMAND_MODE.'='.$this->MODULE_COMMAND_NAME
							.'&'.Display::DEFAULT_DISPLAY_COMMAND_VIEW.'='.$this->MODULE_REGISTERED_VIEWS[1]
							.'&'.Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1.'=f_'.$this->_hashGroupClosed.'_'.$link->getParsed()['bl/rl/nid1'].'_0'
							.$this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
					}
					$i++;
				}
			}
			unset($link, $type, $instance, $instanceEntity, $closed);
			if ( sizeof($list) != 0 )
				$this->_applicationInstance->getDisplayInstance()->displayItemList($list);
			else
				// Pas de groupe.
				$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::sylabe:module:groups:display:noGroup');
			unset($list);
		}
		else
		{
			// Pas de groupe.
			$this->_applicationInstance->getDisplayInstance()->displayMessageOk('::sylabe:module:groups:display:noGroup');
		}
		unset($links, $okobj, $count);*/
    }

    /**
     *
     *
     * @return void
     */
    private function _displayAllGroupsOld(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance(), 'test');

        // Affiche si l'objet courant est un groupe.
        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('myself')) {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    $this->_echoTraduction('::sylabe:module:groups:display:isGroup');
                    ?>
                </p>
            </div>
            <?php
        } // Ou si c'est un groupe pour une autre entité.
        elseif ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    $this->_echoTraduction('::sylabe:module:groups:display:isGroupToOther');
                    echo ' ';
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorIconName($this->_applicationInstance->getCurrentObjectInstance()); // Modifié !!!
                    echo '.';
                    ?>
                </p>
            </div>
            <?php
        } // Ou si ce n'est pas un groupe.
        else {
            ?>
            <div class="text">
                <p>
                    <?php
                    $this->_applicationInstance->getDisplayInstance()->displayInlineObjectColorName($this->_applicationInstance->getCurrentObjectInstance());
                    echo ' ';
                    $this->_echoTraduction('::sylabe:module:groups:display:isNotGroup');
                    ?>
                </p>
            </div>
            <?php
        }
    }


    /**
     * Création d'un groupe.
     *
     * @return void
     */
    private function _displayCreateGroup(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:groups:display:createGroup', $this->MODULE_REGISTERED_ICONS[1]);

        // Si autorisé à créer un groupe.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteGroup'))) {
            ?>

            <div class="text">
                <p>
                <form method="post"
                      action="?<?php echo Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                          . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                    <div class="floatRight textAlignRight">
                        <input type="checkbox"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP_CLOSED; ?>"
                               value="y" checked>
                        <?php $this->_echoTraduction('::GroupeFerme'); ?>
                    </div>
                    <?php $this->_echoTraduction('::sylabe:module:groups:display:nom'); ?>
                    <input type="text"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_GROUP_NAME; ?>"
                           size="20" value="" class="klictyModuleEntityInput"><br/>
                    <input type="submit"
                           value="<?php $this->_echoTraduction('::sylabe:module:groups:display:createTheGroup'); ?>"
                           class="klictyModuleEntityInput">
                </form>
                </p>
            </div>
            <?php
        } else {
            $this->_applicationInstance->getDisplayInstance()->displayMessageError(':::err_NotPermit');
        }
    }

    private function _displayRemoveGroup(): void
    {
        // Affichage de l'entête.
        $this->_applicationInstance->getDisplayInstance()->displayObjectDivHeaderH1($this->_applicationInstance->getCurrentObjectInstance());

        if ($this->_applicationInstance->getCurrentObjectInstance()->getIsGroup('all')) {
            // Affichage les actions possibles.
            echo $this->_applicationInstance->getDisplayInstance()->getDisplayHookMenuList('::sylabe:module:group:remove');
        } else {
            // Ce n'est pas un groupe.
            $this->_applicationInstance->getDisplayInstance()->displayMessageError('::sylabe:module:groups:display:thisIsNotGroup');
        }
    }


    /**
     * Permet d'ajouter des objets marqués à un groupe.
     *
     * @return void
     */
    private function _displayAddMarkedObjects(): void
    {
    }

    /**
     * Affichage en ligne de l'ajout des objets à un groupe.
     *
     * @return void
     */
    private function _display_InlineAddMarkedObjects(): void
    {
    }


    /**
     * Affiche le groupe.
     *
     * @return void
     */
    private function _displayGroup(): void
    {
        $instance = $this->_nebuleInstance->getCurrentGroupInstance();

        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => false,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        $param['objectRefs'] = $instance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_GROUPE);
        echo $this->_display->getDisplayObject($instance, $param);
        unset($instance);

        // Affichage des membres du groupe.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('grpdisp');
    }

    /**
     * Affiche le groupe, en ligne.
     *
     * @return void
     */
    private function _display_InlineGroup(): void
    {
        // Détermine si c'est un groupe fermé.
        if ($this->_nebuleInstance->getCurrentGroupInstance()->getMarkClosed()) {
            // Liste tous les objets du groupe fermé.
            $groupListID = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersID('self', null);

            //Prépare l'affichage.
            if (sizeof($groupListID) != 0) {
                $list = array();
                $listOkItems = array($this->_hashGroup => true, $this->_hashGroupClosed => true);
                $i = 0;
                foreach ($groupListID as $item) {
                    if (!isset($listOkItems[$item])) {
                        $instance = $this->_nebuleInstance->convertIdToTypedObjectInstance($item);

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = '';
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = '';
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink', 'permitWriteGroup'))) {
                            $list[$i]['actions'][0]['name'] = '::sylabe:module:groups:display:removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_DEFAULT_VIEW
                                . '&' . $this->_nebuleInstance->getCurrentGroup()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP . '=' . $item
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }

                        // Marque comme vu.
                        $listOkItems[$item] = true;
                        $i++;
                    }
                }
                unset($groupListID, $listOkItems, $item, $instance);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);

                // Liste tous les objets du groupe ouvert.
                $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

                //Prépare l'affichage.
                if (sizeof($groupListLinks) != 0) {
                    $hashGroupPriv = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                    $list = array();
                    $listOkItems = array();
                    $i = 0;
                    foreach ($groupListLinks as $item) {
                        // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                        // Vérifie si le signataire n'est pas l'entité en cours.
                        if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])
                            && $item->getParsed()['bs/rs1/eid'] != $this->_applicationInstance->getCurrentEntity()
                        ) {
                            $instance = $this->_nebuleInstance->convertIdToTypedObjectInstance($item->getParsed()['bl/rl/nid1']);
                            $instanceSigner = $this->_nebuleInstance->newEntity($item->getParsed()['bs/rs1/eid']);
                            $closed = '::GroupeOuvert';
                            if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                                $closed = '::GroupeFerme';

                            $list[$i]['object'] = $instance;
                            $list[$i]['entity'] = $instanceSigner;
                            $list[$i]['icon'] = '';
                            $list[$i]['htlink'] = '';
                            $list[$i]['desc'] = $this->_traduction($closed);
                            $list[$i]['actions'] = array();

                            // Supprimer le groupe.
                            if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink', 'permitWriteGroup'))) {
                                $list[$i]['actions'][0]['name'] = '::sylabe:module:groups:display:removeFromGroup';
                                $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                                $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_DEFAULT_VIEW
                                    . '&' . nebule::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP . '=' . $item->getParsed()['bl/rl/nid1']
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }

                            // Marque comme vu.
                            $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                            $i++;
                        }
                    }
                    unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                    // Affichage
                    if (sizeof($list) != 0) {
                        echo "<div class=\"sequence\"></div>\n";
                        // Affiche le titre.
                        $this->_applicationInstance->getDisplayInstance()->displayDivTextTitle(
                            $this->MODULE_LOGO,
                            '::sylabe:module:groups:display:seenFromOthers',
                            '',
                            '');
                        $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                    }
                }
                unset($list);
            } else {
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::sylabe:module:groups:display:noGroupMember');
            }
        } // Sinon c'est un groupe ouvert.
        else {
            // Liste tous les groupes.
            $groupListLinks = $this->_nebuleInstance->getCurrentGroupInstance()->getListMembersLinks('self'); // @todo à vérifier self.

            //Prépare l'affichage.
            if (sizeof($groupListLinks) != 0) {
                $hashGroupPriv = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_GROUPE_FERME);
                $list = array();
                $listOkItems = array();
                $i = 0;
                foreach ($groupListLinks as $item) {
                    // Vérifie si le couple membre/signataire n'est pas déjà pris en compte.
                    if (!isset($listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']])) {
                        $instance = $this->_nebuleInstance->convertIdToTypedObjectInstance($item->getParsed()['bl/rl/nid1']);
                        $instanceSigner = $this->_nebuleInstance->newEntity($item->getParsed()['bs/rs1/eid']);
                        $closed = '::GroupeOuvert';
                        if ($item->getParsed()['bl/rl/nid3'] == $hashGroupPriv)
                            $closed = '::GroupeFerme';

                        $list[$i]['object'] = $instance;
                        $list[$i]['entity'] = $instanceSigner;
                        $list[$i]['icon'] = '';
                        $list[$i]['htlink'] = '';
                        $list[$i]['desc'] = $this->_traduction($closed);
                        $list[$i]['actions'] = array();

                        // Supprimer le groupe.
                        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink', 'permitWriteGroup'))) {
                            $list[$i]['actions'][0]['name'] = '::sylabe:module:groups:display:removeFromGroup';
                            $list[$i]['actions'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['actions'][0]['htlink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . nebule::COMMAND_SELECT_GROUP . '=' . $this->_nebuleInstance->getCurrentGroup()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_ITEM_FROM_GROUP . '=' . $item->getParsed()['bl/rl/nid1']
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }

                        // Marque comme vu.
                        $listOkItems[$item->getParsed()['bl/rl/nid1'] . $item->getParsed()['bs/rs1/eid']] = true;
                        $i++;
                    }
                }
                unset($groupListLinks, $listOkItems, $item, $instance, $hashGroupPriv, $closed);
                // Affichage
                if (sizeof($list) != 0)
                    $this->_applicationInstance->getDisplayInstance()->displayItemList($list);
                unset($list);
            } else {
                // Pas d'entité.
                $this->_applicationInstance->getDisplayInstance()->displayMessageInformation('::sylabe:module:groups:display:noGroupMember');
            }
        }
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:groups:ModuleName'] = 'Module des groupes';
        $this->_table['en-en']['::sylabe:module:groups:ModuleName'] = 'Groups module';
        $this->_table['es-co']['::sylabe:module:groups:ModuleName'] = 'Groups module';
        $this->_table['fr-fr']['::sylabe:module:groups:MenuName'] = 'Groupes';
        $this->_table['en-en']['::sylabe:module:groups:MenuName'] = 'Groups';
        $this->_table['es-co']['::sylabe:module:groups:MenuName'] = 'Groups';
        $this->_table['fr-fr']['::sylabe:module:groups:ModuleDescription'] = 'Module de gestion des groupes.';
        $this->_table['en-en']['::sylabe:module:groups:ModuleDescription'] = 'Groups management module.';
        $this->_table['es-co']['::sylabe:module:groups:ModuleDescription'] = 'Groups management module.';
        $this->_table['fr-fr']['::sylabe:module:groups:ModuleHelp'] = "Ce module permet de voir et de gérer les groupes.";
        $this->_table['en-en']['::sylabe:module:groups:ModuleHelp'] = 'This module permit to see and manage groups.';
        $this->_table['es-co']['::sylabe:module:groups:ModuleHelp'] = 'This module permit to see and manage groups.';

        $this->_table['fr-fr']['::sylabe:module:groups:AppTitle1'] = 'Groupes';
        $this->_table['en-en']['::sylabe:module:groups:AppTitle1'] = 'Groups';
        $this->_table['es-co']['::sylabe:module:groups:AppTitle1'] = 'Groups';
        $this->_table['fr-fr']['::sylabe:module:groups:AppDesc1'] = 'Gestion des groupes.';
        $this->_table['en-en']['::sylabe:module:groups:AppDesc1'] = 'Manage groups.';
        $this->_table['es-co']['::sylabe:module:groups:AppDesc1'] = 'Manage groups.';

        $this->_table['fr-fr']['::sylabe:module:groups:display:Groups'] = 'Les groupes';
        $this->_table['en-en']['::sylabe:module:groups:display:Groups'] = 'The groups';
        $this->_table['es-co']['::sylabe:module:groups:display:Groups'] = 'The groups';
        $this->_table['fr-fr']['::sylabe:module:groups:display:MyGroups'] = 'Mes groupes';
        $this->_table['en-en']['::sylabe:module:groups:display:MyGroups'] = 'My groups';
        $this->_table['es-co']['::sylabe:module:groups:display:MyGroups'] = 'My groups';
        $this->_table['fr-fr']['::sylabe:module:groups:display:seeAsGroup'] = 'Voir comme groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:seeAsGroup'] = 'See as group';
        $this->_table['es-co']['::sylabe:module:groups:display:seeAsGroup'] = 'See as group';

        $this->_table['fr-fr']['::sylabe:module:groups:display:seenFromOthers'] = 'Vu depuis les autres entités';
        $this->_table['en-en']['::sylabe:module:groups:display:seenFromOthers'] = 'Seen from others entities';
        $this->_table['es-co']['::sylabe:module:groups:display:seenFromOthers'] = 'Seen from others entities';
        $this->_table['fr-fr']['::sylabe:module:groups:display:otherGroups'] = 'Les groupes des autres entités';
        $this->_table['en-en']['::sylabe:module:groups:display:otherGroups'] = 'Groups of other entities';
        $this->_table['es-co']['::sylabe:module:groups:display:otherGroups'] = 'Groups of other entities';

        $this->_table['fr-fr']['::sylabe:module:groups:display:createGroup'] = 'Créer un groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:createGroup'] = 'Create a group';
        $this->_table['es-co']['::sylabe:module:groups:display:createGroup'] = 'Create a group';
        $this->_table['fr-fr']['::sylabe:module:groups:display:AddMarkedObjects'] = 'Ajouter les objets marqués';
        $this->_table['en-en']['::sylabe:module:groups:display:AddMarkedObjects'] = 'Add marked objects';
        $this->_table['es-co']['::sylabe:module:groups:display:AddMarkedObjects'] = 'Add marked objects';
        $this->_table['fr-fr']['::sylabe:module:groups:display:deleteGroup'] = 'Supprimer le groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:deleteGroup'] = 'Delete group';
        $this->_table['es-co']['::sylabe:module:groups:display:deleteGroup'] = 'Delete group';

        $this->_table['fr-fr']['::sylabe:module:groups:display:createTheGroup'] = 'Créer le groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:createTheGroup'] = 'Create the group';
        $this->_table['es-co']['::sylabe:module:groups:display:createTheGroup'] = 'Create the group';
        $this->_table['fr-fr']['::sylabe:module:groups:display:nom'] = 'Nom';
        $this->_table['en-en']['::sylabe:module:groups:display:nom'] = 'Name';
        $this->_table['es-co']['::sylabe:module:groups:display:nom'] = 'Name';
        $this->_table['fr-fr']['::sylabe:module:groups:display:OKCreateGroup'] = 'Le groupe a été créé.';
        $this->_table['en-en']['::sylabe:module:groups:display:OKCreateGroup'] = 'The group have been created.';
        $this->_table['es-co']['::sylabe:module:groups:display:OKCreateGroup'] = 'The group have been created.';
        $this->_table['fr-fr']['::sylabe:module:groups:display:notOKCreateGroup'] = "Le groupe n'a pas été créé ! %s";
        $this->_table['en-en']['::sylabe:module:groups:display:notOKCreateGroup'] = 'The group have not been created! %s';
        $this->_table['es-co']['::sylabe:module:groups:display:notOKCreateGroup'] = 'The group have not been created! %s';

        $this->_table['fr-fr']['::sylabe:module:groups:display:noGroup'] = 'Pas de groupe.';
        $this->_table['en-en']['::sylabe:module:groups:display:noGroup'] = 'No group.';
        $this->_table['es-co']['::sylabe:module:groups:display:noGroup'] = 'No group.';
        $this->_table['fr-fr']['::sylabe:module:groups:display:noGroupMember'] = 'Pas de membre.';
        $this->_table['en-en']['::sylabe:module:groups:display:noGroupMember'] = 'No member.';
        $this->_table['es-co']['::sylabe:module:groups:display:noGroupMember'] = 'No member.';

        $this->_table['fr-fr']['::sylabe:module:groups:display:makeGroup'] = 'Faire de cet objet un groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:makeGroup'] = 'Make this object a group';
        $this->_table['es-co']['::sylabe:module:groups:display:makeGroup'] = 'Make this object a group';
        $this->_table['fr-fr']['::sylabe:module:groups:display:makeGroupMe'] = 'Faire de cet objet un groupe pour moi aussi';
        $this->_table['en-en']['::sylabe:module:groups:display:makeGroupMe'] = 'Make this object a group for me too';
        $this->_table['es-co']['::sylabe:module:groups:display:makeGroupMe'] = 'Make this object a group for me too';
        $this->_table['fr-fr']['::sylabe:module:groups:display:unmakeGroup'] = 'Ne plus faire de cet objet un groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:unmakeGroup'] = 'Unmake this object a group';
        $this->_table['es-co']['::sylabe:module:groups:display:unmakeGroup'] = 'Unmake this object a group';
        $this->_table['fr-fr']['::sylabe:module:groups:display:useAsGroupOpened'] = 'Utiliser comme groupe ouvert';
        $this->_table['en-en']['::sylabe:module:groups:display:useAsGroupOpened'] = 'Use as group opened';
        $this->_table['es-co']['::sylabe:module:groups:display:useAsGroupOpened'] = 'Use as group opened';
        $this->_table['fr-fr']['::sylabe:module:groups:display:useAsGroupClosed'] = 'Utiliser comme groupe fermé';
        $this->_table['en-en']['::sylabe:module:groups:display:useAsGroupClosed'] = 'Use as group closed';
        $this->_table['es-co']['::sylabe:module:groups:display:useAsGroupClosed'] = 'Use as group closed';

        $this->_table['fr-fr']['::sylabe:module:groups:display:refuseGroup'] = 'Refuser cet objet comme un groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:refuseGroup'] = 'Refuse this object as group';
        $this->_table['es-co']['::sylabe:module:groups:display:refuseGroup'] = 'Refuse this object as group';
        $this->_table['fr-fr']['::sylabe:module:groups:display:removeFromGroup'] = 'Retirer du groupe';
        $this->_table['en-en']['::sylabe:module:groups:display:removeFromGroup'] = 'Remove from group';
        $this->_table['es-co']['::sylabe:module:groups:display:removeFromGroup'] = 'Remove from group';

        $this->_table['fr-fr']['::sylabe:module:groups:display:isGroup'] = 'est un groupe.';
        $this->_table['en-en']['::sylabe:module:groups:display:isGroup'] = 'is a group.';
        $this->_table['es-co']['::sylabe:module:groups:display:isGroup'] = 'is a group.';
        $this->_table['fr-fr']['::sylabe:module:groups:display:isGroupToOther'] = 'est un groupe de';
        $this->_table['en-en']['::sylabe:module:groups:display:isGroupToOther'] = 'is a group of';
        $this->_table['es-co']['::sylabe:module:groups:display:isGroupToOther'] = 'is a group of';
        $this->_table['fr-fr']['::sylabe:module:groups:display:isNotGroup'] = "n'est pas un groupe.";
        $this->_table['en-en']['::sylabe:module:groups:display:isNotGroup'] = 'is not a group.';
        $this->_table['es-co']['::sylabe:module:groups:display:isNotGroup'] = 'is not a group.';
        $this->_table['fr-fr']['::sylabe:module:groups:display:thisIsGroup'] = "C'est un groupe.";
        $this->_table['en-en']['::sylabe:module:groups:display:thisIsGroup'] = 'This is a group.';
        $this->_table['es-co']['::sylabe:module:groups:display:thisIsGroup'] = 'This is a group.';
        $this->_table['fr-fr']['::sylabe:module:groups:display:thisIsNotGroup'] = "Ce n'est pas un groupe.";
        $this->_table['en-en']['::sylabe:module:groups:display:thisIsNotGroup'] = 'This is not a group.';
        $this->_table['es-co']['::sylabe:module:groups:display:thisIsNotGroup'] = 'This is not a group.';
    }
}


/**
 * Cette application permet gérer les objets.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleObjects extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:objects:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:objects:MenuName';
    protected $MODULE_COMMAND_NAME = 'obj';
    protected $MODULE_DEFAULT_VIEW = 'disp';
    protected $MODULE_DESCRIPTION = '::sylabe:module:objects:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee';
    protected $MODULE_HELP = '::sylabe:module:objects:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array('disp', 'desc', 'nav', 'prot', 'sprot');
    protected $MODULE_REGISTERED_ICONS = array(
        '26d3b259b94862aecac064628ec02a38e30e9da9b262a7307453046e242cc9ee',    // 0 : Objet.
        '2e836dd0ca088d84cbc472093a14445e5c81ee0998293b46a479fedc41adf10d',    // 1 : Loupe.
        '06cac4acb887cff2c7ba6653f865d800276a4e9d493a3be4e1b05d107f5ecbaf',    // 2 : Fork.
        '6d1d397afbc0d2f6866acd1a30ac88abce6a6c4c2d495179504c2dcb09d707c1',    // 3 : Protection d'un objet.
        '1c6db1c9b3b52a9b68d19c936d08697b42595bec2f0adf16e8d9223df3a4e7c5',    // 4 : Clé.
    );
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();

    const DEFAULT_ATTRIBS_DISPLAY_NUMBER = 10;


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        $hookArray = array();
        if ($object == 'none') {
            $object = $this->_applicationInstance->getCurrentObject();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        switch ($hookName) {
            case 'selfMenu':
            case 'selfMenuObject':
                //$instance = $this->_applicationInstance->getCurrentObjectInstance();
                $instance = $this->_nebuleInstance->newObject($object);
                $id = $instance->getID();

                // Recherche si l'objet est protégé.
                $protected = $instance->getMarkProtected();
                if ($protected) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_nebuleInstance->newObject($id);
                }

                // Recherche une mise à jour.
                $update = $instance->getUpdateNID(false, false);

                // Recherche si l'objet est marqué.
                $marked = $this->_applicationInstance->getMarkObject($id);
                $markList = $this->_applicationInstance->getMarkObjectList();
                $mode = $this->_display->getCurrentDisplayMode();
                $view = $this->_display->getCurrentDisplayView();

                if ($mode != $this->MODULE_COMMAND_NAME
                    || ($mode == $this->MODULE_COMMAND_NAME
                        && $view != $this->MODULE_REGISTERED_VIEWS[0]
                    )
                ) {
                    // Affichage de l'objet.
                    $hookArray[0]['name'] = '::sylabe:module:objects:DisplayObject';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id;

                    // Si l'objet a une mise à jour.
                    if ($update != $id) {
                        // Affichage de l'objet à jour.
                        $hookArray[1]['name'] = '::sylabe:module:objects:DisplayObjectUpdated';
                        $hookArray[1]['icon'] = Displays::DEFAULT_ICON_LU;
                        $hookArray[1]['desc'] = '';
                        $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $update;
                    }
                }

                // Description de l'objet.
                if ($mode == $this->MODULE_COMMAND_NAME
                    && $view != $this->MODULE_REGISTERED_VIEWS[1]
                ) {
                    $hookArray[2]['name'] = '::sylabe:module:objects:ObjectDescription';
                    $hookArray[2]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[2]['desc'] = '';
                    $hookArray[2]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id;
                }

                // Naviguer autour de l'objet.
                if ($mode == $this->MODULE_COMMAND_NAME
                    && $view != $this->MODULE_REGISTERED_VIEWS[2]
                ) {
                    $hookArray[3]['name'] = '::sylabe:module:objects:ObjectRelations';
                    $hookArray[3]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                    $hookArray[3]['desc'] = '';
                    $hookArray[3]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id;
                }

                // Si le contenu de l'objet est présent.
                if ($instance->checkPresent()) {
                    // Télécharger l'objet.
                    $hookArray[4]['name'] = '::sylabe:module:objects:ObjectDownload';
                    $hookArray[4]['icon'] = Displays::DEFAULT_ICON_IDOWNLOAD;
                    $hookArray[4]['desc'] = '::sylabe:module:objects:Action:Download';
                    $hookArray[4]['link'] = '?o=' . $id;

                    // Si l'entité est déverrouillée.
                    if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))) {
                        // Supprimer l'objet.
                        $hookArray[5]['name'] = '::sylabe:module:objects:ObjectDelete';
                        $hookArray[5]['icon'] = Displays::DEFAULT_ICON_LD;
                        $hookArray[5]['desc'] = '';
                        $hookArray[5]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_DELETE_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                        // Protéger l'objet.
                        if ($mode == $this->MODULE_COMMAND_NAME
                            && $view != $this->MODULE_REGISTERED_VIEWS[3]
                        ) {
                            $hookArray[6]['name'] = '::sylabe:module:objects:Protection';
                            $hookArray[6]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                            $hookArray[6]['desc'] = '';
                            $hookArray[6]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id;
                        }

                        // Partager la protection
                        if ($mode == $this->MODULE_COMMAND_NAME
                            && $view != $this->MODULE_REGISTERED_VIEWS[4]
                            && $protected
                        ) {
                            $hookArray[7]['name'] = '::sylabe:module:objects:ShareProtection';
                            $hookArray[7]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                            $hookArray[7]['desc'] = '';
                            $hookArray[7]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[4]
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id;
                        }
                    }
                }

                // Si l'objet n'est pas marqué.
                if (!$marked) {
                    // Ajouter la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkAdd';
                    $hookArray[8]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                } else {
                    // Retirer la marque de l'objet.
                    $hookArray[8]['name'] = '::MarkRemove';
                    $hookArray[8]['icon'] = Display::DEFAULT_ICON_UNMARK;
                    $hookArray[8]['desc'] = '';
                    $hookArray[8]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Si la liste des marques n'est pas vide.
                if (sizeof($markList) != 0) {
                    // Retirer la marque de tous les objets.
                    $hookArray[9]['name'] = '::MarkRemoveAll';
                    $hookArray[9]['icon'] = Display::DEFAULT_ICON_UNMARKALL;
                    $hookArray[9]['desc'] = '';
                    $hookArray[9]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($instance, $id, $protected, $update, $markList, $marked);
                break;

            case 'menu':
                // Recherche si il y a des objets marqués.
                $markList = $this->_applicationInstance->getMarkObjectList();

                // Si la liste des marques n'est pas vide.
                if (sizeof($markList) != 0) {
                    // Retirer la marque de tous les objets.
                    $hookArray[0]['name'] = '::MarkRemoveAll';
                    $hookArray[0]['icon'] = Display::DEFAULT_ICON_UNMARKALL;
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_ALL_OBJECT
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($markList);
                break;

            case '::sylabe:module:links:MenuNameSelfMenu':
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayObject';
                $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;
                break;

            case '::sylabe:module:upload:FileUploaded':
                // Si il y a eu le téléchargement d'un fichier.
                if ($this->_applicationInstance->getActionInstance()->getUploadObject()) {
                    // Si pas d'erreur.
                    if (!$this->_applicationInstance->getActionInstance()->getUploadObjectError()) {
                        $hookArray[0]['name'] = '::sylabe:module:objects:DisplayNewObject';
                        $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                        $hookArray[0]['desc'] = '';
                        $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[0]
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getActionInstance()->getUploadObjectID();
                    }
                }
                break;

            case 'typeMenuEntity':
            case 'typeMenuCurrency':
            case 'typeMenuTokenPool':
            case 'typeMenuToken':
                // Voir l'objet de l'entité.
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayAsObject';
                $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_DEFAULT_VIEW
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;

                $instance = $this->_applicationInstance->getCurrentEntityInstance();
                $id = $instance->getID();

                // Recherche si l'objet est protégé.
                if ($instance->getMarkProtected()) {
                    $id = $instance->getUnprotectedID();
                    $instance = $this->_nebuleInstance->newObject($id);
                }

                // Recherche si l'objet est marqué.
                $marked = $this->_applicationInstance->getMarkObject($id);

                // Si l'objet n'est pas marqué.
                if (!$marked) {
                    // Ajouter la marque de l'objet.
                    $hookArray[1]['name'] = '::MarkAdd';
                    $hookArray[1]['icon'] = Display::DEFAULT_ICON_MARK;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_MARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                } else {
                    // Retirer la marque de l'objet.
                    $hookArray[1]['name'] = '::MarkRemove';
                    $hookArray[1]['icon'] = Display::DEFAULT_ICON_UNMARK;
                    $hookArray[1]['desc'] = '';
                    $hookArray[1]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleEntities')->getCommandName()
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $id
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_UNMARK_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }
                unset($instance, $id, $marked);
                break;

            case '::sylabe:module:entities:DisplayEntity':
            case '::sylabe:module:filesystem:adminInpoint' :
            case '::sylabe:module:filesystem:adminFolder' :
            case '::sylabe:module:filesystem:adminObject' :
            case 'selfMenuConversation':
                // Voir comme objet simplement.
                $hookArray[0]['name'] = '::sylabe:module:objects:DisplayAsObject';
                $hookArray[0]['icon'] = Displays::DEFAULT_ICON_LO;
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_DEFAULT_VIEW
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;
                break;

            case '::sylabe:module:objet:ProtectionAdd' :
                // Actions sur l'objet lors de l'ajout de la protection.
                break;

            case '::sylabe:module:object:protectShared' :
                // Actions pour retirer le partage de protection de l'objet à l'entité.
                break;

            case '::sylabe:module:object:protectShareTo' :
                // Actions pour partager la protection de l'objet à l'entité.
                break;

            case '::sylabe:module:object:protectShareToGroup' :
                // Actions pour partager la protection de l'objet aux entités du groupe.
                break;

            case '::sylabe:module:objet:ProtectionButtons' :
                // Si l'entité est déverrouillée.
                if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))
                    && $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected()
                ) {
                    $hookArray[0]['name'] = '::sylabe:module:objects:ShareProtection';
                    $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                    $hookArray[0]['desc'] = '';
                    $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[4]
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObject();
                }
                break;

            case '::sylabe:module:objet:ProtectionShareButtons' :
                // Protéger l'objet.
                $hookArray[0]['name'] = '::sylabe:module:objects:Protection';
                $hookArray[0]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                $hookArray[0]['desc'] = '';
                $hookArray[0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_applicationInstance->getCurrentObject();
                break;
        }
        return $hookArray;
    }


    /**
     * Affichage principale.
     *
     * @return void
     */
    protected function _displayFull(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayObjectContent();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayObjectDescription();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayObjectRelations();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayObjectProtection();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_displayObjectProtectionShare();
                break;
            default:
                $this->_displayObjectContent();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    protected function _displayInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineObjectDescription();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_display_InlineObjectRelations();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_display_InlineObjectProtection();
                break;
            case $this->MODULE_REGISTERED_VIEWS[4]:
                $this->_display_InlineObjectProtectionShare();
                break;
        }
    }


    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        ?>

        /* Module objets */
        .sylabeModuleObjectsDescList1 { padding:5px; background:rgba(255,255,255,0.5); background-origin:border-box; color:#000000; clear:both; }
        .sylabeModuleObjectsDescList2 { padding:5px; background:rgba(230,230,230,0.5); background-origin:border-box; color:#000000; clear:both; }
        .sylabeModuleObjectsDescError { padding:5px; background:rgba(0,0,0,0.3); background-origin:border-box; clear:both; }
        .sylabeModuleObjectsDescError .sylabeModuleObjectsDescAttrib { font-style:italic; color:#202020; }
        .sylabeModuleObjectsDescIcon { float:left; margin-right:5px; }
        .sylabeModuleObjectsDescContent { min-width:300px; }
        .sylabeModuleObjectsDescDate, .sylabeModuleObjectsDescSigner { float:right; margin-left:10px; }
        .sylabeModuleObjectsDescSigner a { color:#000000; }
        .sylabeModuleObjectsDescValue { font-weight:bold; }
        .sylabeModuleObjectsDescEmotion { font-weight:bold; }
        .sylabeModuleObjectsDescEmotion img { height:16px; width:16px; }
        <?php
    }


    /**
     * Affichage de la vue disp.
     */
    private function _displayObjectContent(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        echo $this->_display->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);
    }


    /**
     * Affichage de la vue desc.
     */
    private function _displayObjectDescription(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        echo $this->_display->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);

        // Affiche les propriétés.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objprop');
    }

    /**
     * Affichage de la vue desc en ligne.
     */
    private function _display_InlineObjectDescription(): void
    {
        /**
         * @var Display $display
         */
        $display = $this->_applicationInstance->getDisplayInstance();

        // Préparation de la gestion de l'affichage par parties.
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject();
        $displayCount = 0;
        $okDisplay = false;
        if ($startLinkSigne == '') {
            $okDisplay = true;
        }
        $displayNext = false;
        $nextLinkSigne = '';

        // Liste des attributs, càd des liens de type l.
        $links = $this->_applicationInstance->getCurrentObjectInstance()->getLinksOnFields(
            '',
            '',
            '',
            $this->_applicationInstance->getCurrentObject(),
            '',
            '');

        // Affichage des attributs de base.
        if (sizeof($links) != 0) {
            // Indice de fond paire ou impaire.
            $bg = 1;
            $attribList = nebule::RESERVED_OBJECTS_LIST;
            $emotionsList = array(
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE,
                $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET) => nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET,
            );
            $emotionsIcons = array(
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_JOIE => Displays::REFERENCE_ICON_EMOTION_JOIE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE => Displays::REFERENCE_ICON_EMOTION_CONFIANCE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_PEUR => Displays::REFERENCE_ICON_EMOTION_PEUR1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE => Displays::REFERENCE_ICON_EMOTION_SURPRISE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE => Displays::REFERENCE_ICON_EMOTION_TRISTESSE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT => Displays::REFERENCE_ICON_EMOTION_DEGOUT1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_COLERE => Displays::REFERENCE_ICON_EMOTION_COLERE1,
                nebule::REFERENCE_NEBULE_OBJET_EMOTION_INTERET => Displays::REFERENCE_ICON_EMOTION_INTERET1,
            );

            foreach ($links as $i => $link) {
                // Vérifie si la signature de lien est celle de départ de l'affichage.
                if ($link->getSigneValue() == $startLinkSigne) {
                    $okDisplay = true;
                }

                // Enregistre le message suivant à afficher si le compteur d'affichage est dépassé.
                if ($displayNext
                    && $nextLinkSigne == ''
                ) {
                    $nextLinkSigne = $link->getSigneValue();
                }

                // Si l'affichage est permit.
                if ($okDisplay) {
                    // Extraction des attributs.
                    $action = $link->getAction();
                    $showAttrib = false;
                    $showEmotion = false;
                    $hashAttrib = $link->getParsed()['bl/rl/nid3'];
                    $attribName = '';
                    $attribTraduction = '';
                    $hashValue = $link->getParsed()['bl/rl/nid2'];
                    $value = '';
                    $attribValue = '';
                    $emotion = '';

                    // Si action type l.
                    if ($action == 'l') {
                        // Extrait le nom.
                        if ($hashAttrib != '0'
                            && $hashAttrib != ''
                            && $hashValue != '0'
                            && $hashValue != ''
                        ) {
                            $attribInstance = $this->_nebuleInstance->newObject($hashAttrib);
                            $attribName = $attribInstance->readOneLineAsText();
                            unset($attribInstance);
                            // Vérifie le nom.
                            if ($attribName == null) {
                                $attribName = '';
                            }
                        }

                        // Vérifie si l'attribut est dans la liste des objets réservés à afficher.
                        if ($attribName != '') {
                            foreach ($attribList as $attribItem) {
                                if ($attribItem == $attribName) {
                                    $showAttrib = true;
                                    break;
                                }
                            }
                        }
                    }

                    // Si action de type f, vérifie si l'attribut est dans la liste des émotions à afficher.
                    if ($action == 'f'
                        && $hashValue != '0'
                    ) {
                        foreach ($emotionsList as $item => $emotionItem) {
                            if ($item == $hashValue) {
                                $showEmotion = true;
                                $emotion = $emotionItem;
                                break;
                            }
                        }
                    }

                    // Extrait la valeur.
                    if ($showAttrib
                        && $attribName != ''
                    ) {
                        $valueInstance = $this->_nebuleInstance->newObject($hashValue);
                        $value = $valueInstance->readOneLineAsText();
                        unset($valueInstance);
                        // Vérifie la valeur.
                        if ($value == null) {
                            $value = $this->_applicationInstance->getTraductionInstance()->getTraduction('::noContent');
                        }
                    }

                    if ($showAttrib) {
                        // Affiche l'attribut.
                        ?>

                        <div class="sylabeModuleObjectsDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
                                <span class="sylabeModuleObjectsDescAttrib"><?php $this->_applicationInstance->getTraductionInstance()->echoTraduction($attribName); ?></span>
                                =
                                <span class="sylabeModuleObjectsDescValue"><?php echo $value; ?></span>
                            </div>
                        </div>
                        <?php
                    } elseif ($showEmotion) {
                        // Affiche l'émotion.
                        ?>

                        <div class="sylabeModuleObjectsDescList<?php echo $bg; ?>">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
		<span class="sylabeModuleObjectsDescEmotion">
			<?php $display->displayReferenceImage($emotionsIcons[$emotion], $emotionsList[$hashValue]); ?>
            <?php $this->_applicationInstance->getTraductionInstance()->echoTraduction($emotionsList[$hashValue]); ?>
		</span>
                            </div>
                        </div>
                        <?php
                    } elseif ($action == 'l') {
                        // Affiche une erreur si la propriété n'est pas lisible.
                        ?>

                        <div class="sylabeModuleObjectsDescError">
                            <?php
                            if ($this->_applicationInstance->isModuleLoaded('ModuleLinks')) {
                                // Affiche l'icône pour voir le lien.
                                ?>

                                <div class="sylabeModuleObjectsDescIcon">
                                    <?php $display->displayHypertextLink($display->convertInlineIconFace('DEFAULT_ICON_LL'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->_applicationInstance->getModule('ModuleLinks')->getCommandName()
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . Display::DEFAULT_LINK_COMMAND
                                        . '&' . ModuleLinks::DEFAULT_LINK_COMMAND . '=' . $link->getFullLink()); ?>
                                    &nbsp;
                                    <?php $display->displayInlineIconFace('DEFAULT_ICON_IWARN'); ?>

                                </div>
                                <?php
                            }
                            ?>

                            <div class="sylabeModuleObjectsDescDate"><?php $display->displayDate($link->getDate()); ?></div>
                            <div class="sylabeModuleObjectsDescSigner"><?php $display->displayInlineObjectColorIconName($link->getParsed()['bs/rs1/eid']); ?></div>
                            <div class="sylabeModuleObjectsDescContent">
                                <span class="sylabeModuleObjectsDescAttrib"><?php $this->_echoTraduction('::sylabe:module:objects:AttribNotDisplayable'); ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        // Si non affichable et lien de type autre que l, annule la permutation de l'indice de fond.
                        $bg = 3 - $bg;
                    }

                    // Actualise le compteur d'affichage.
                    $displayCount++;
                    if ($displayCount >= self::DEFAULT_ATTRIBS_DISPLAY_NUMBER) {
                        $okDisplay = false;
                        $displayNext = true;
                    }
                }

                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }

            // Affiche au besoin le bouton pour afficher les objets suivants.
            if ($displayNext
                && $nextLinkSigne != ''
            ) {
                $url = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_nebuleInstance->getCurrentObject()
                    . '&' . Displays::DEFAULT_INLINE_COMMAND . '&' . Displays::DEFAULT_INLINE_CONTENT_COMMAND . '=objprop'
                    . '&' . Displays::DEFAULT_NEXT_COMMAND . '=' . $nextLinkSigne;
                $display->displayButtonNextObject($nextLinkSigne, $url, $this->_applicationInstance->getTraductionInstance()->getTraduction('::seeMore'));
            }
            unset($links);
        }
    }


    /**
     * Affichage de la vue nav.
     */
    private function _displayObjectRelations(): void
    {
        $param = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'flagProtection' => $this->_applicationInstance->getCurrentObjectInstance()->getMarkProtected(),
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );
        echo $this->_display->getDisplayObject($this->_applicationInstance->getCurrentObjectInstance(), $param);

        // Affiche la navigation.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objnav');
    }

    /**
     * Affichage de la vue nav en ligne.
     */
    private function _display_InlineObjectRelations(): void
    {
        ?>
        <div class="text">
            <p>
                Nav<br/>
                En cours...
            </p>
        </div>
        <?php
    }


    /**
     * Affichage de la vue de protection.
     */
    private function _displayObjectProtection(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        if ($object->getMarkProtected()) {
            // Affiche l'objet seul dans une liste.
            $list = array();
            $list[0]['object'] = $object;
            $list[0]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => true,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => true,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplayID' => false,
                'flagProtection' => true,
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
                'enableDisplayJS' => true,
                //'selfHookName' => 'selfMenuObject',
            );
            echo $this->_display->getDisplayObjectsList($list, 'long');
            unset($list);

            // Affiche en ligne les entités pour qui c'est partagé.
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objectprotectionshared');

            // affichage des boutons.
            echo $this->_display->getDisplayHookMenuList('::sylabe:module:objet:ProtectionButtons', 'medium');
        } else {
            $list = array();

            // Ajout du message de non protection.
            $list[0]['information'] = '::UnprotectedObject';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'information',
                'displaySize' => 'small',
                'displayRatio' => 'long',
            );

            // N'affiche un message que si la modification est possible.
            if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))) {
                // Vérifie la présence de l'objet.
                if ($object->checkPresent()
                    && $this->_applicationInstance->getCurrentObject() != $this->_nebuleInstance->getCurrentEntity()
                ) {
                    // Ajout du message d'avertissement.
                    if ($object->getIsEntity('all')) {
                        $list[1]['information'] = '::WarningDoNotProtectEntity';
                        $list[1]['param'] = array(
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displaySize' => 'small',
                            'displayRatio' => 'long',
                        );
                    } else {
                        $list[1]['information'] = '::WarningProtectObject';
                        $list[1]['param'] = array(
                            'enableDisplayIcon' => true,
                            'informationType' => 'warn',
                            'displaySize' => 'small',
                            'displayRatio' => 'long',
                        );
                    }
                } else {
                    $list[1]['information'] = '::ErrorCantProtectObject';
                    $list[1]['param'] = array(
                        'enableDisplayIcon' => true,
                        'informationType' => 'error',
                        'displaySize' => 'small',
                        'displayRatio' => 'long',
                    );
                }
            }

            // Ajout l'objet.
            $list[2]['object'] = $object->getID();
            $list[2]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => true,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => true,
                'enableDisplayContent' => false,
                'displaySize' => 'small',
                'displayRatio' => 'long',
                'enableDisplayID' => true,
                'flagProtection' => false,
                'enableDisplaySelfHook' => true,
                'selfHookName' => '::sylabe:module:objet:ProtectionAdd',
                'enableDisplayJS' => false,
            );

            // Ajoute l'action de protection.
            if ($object->checkPresent()
                && $this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))
                && $this->_applicationInstance->getCurrentObject() != $this->_nebuleInstance->getCurrentEntity()
            ) {
                $list[2]['param']['selfHookList'][0]['name'] = '::ProtectObject';
                $list[2]['param']['selfHookList'][0]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                $list[2]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT . '=' . $object->getID()
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }

            // Affichage.
            echo $this->_display->getDisplayObjectsList($list, 'medium');
            unset($list);
        }
    }

    /**
     * Affichage en ligne de la vue de protection.
     *
     * @return void
     */
    private function _display_InlineObjectProtection(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        // Affichage les actions possibles.
        $id = $object->getID();

        // Si l'objet est présent.
        if ($object->checkPresent()
            && $object->getMarkProtected()
        ) {
            // Prépare l'affichage.
            $list = array();

            // Ajout du message de protection.
            $list[0]['information'] = '::ProtectedObject';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'ok',
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Ajout l'objet non protégé.
            $list[1]['object'] = $object->getUnprotectedID();
            $list[1]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'enableDisplayJS' => false,
                'enableDisplaySelfHook' => false,
                'enableDisplayTypeHook' => false,
                'objectName' => $this->_applicationInstance->getTraductionInstance()->getTraduction('::ProtectedID'),
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Ajout l'objet non protégé.
            $list[2]['object'] = $object->getProtectedID();
            $list[2]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => false,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => false,
                'enableDisplayContent' => false,
                'enableDisplayJS' => false,
                'enableDisplaySelfHook' => false,
                'enableDisplayTypeHook' => false,
                'objectName' => $this->_applicationInstance->getTraductionInstance()->getTraduction('::UnprotectedID'),
                'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            $listOkEntities = array();
            $shareTo = $object->getProtectedTo();
            $i = 3; // Pas 0.
            $instance = null;
            $typeEntity = false;
            foreach ($shareTo as $entity) {
                $instance = $this->_nebuleInstance->newEntity($entity);
                $typeEntity = $instance->getIsEntity('all');
                if (!isset($listOkEntities[$entity])
                    && $typeEntity
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => false,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => false,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShared',
                        'typeHookName' => '',
                    );


                    // Ajout l'action de déprotection ou de suppression de partage de protection.
                    if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))) {
                        if ($entity == $this->_nebuleInstance->getCurrentEntity()) {
                            // Déprotéger l'objet.
                            $list[$i]['param']['selfHookList'][0]['name'] = '::UnprotectObject';
                            $list[$i]['param']['selfHookList'][0]['icon'] = $this->MODULE_REGISTERED_ICONS[3];
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_ICONS[3]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT . '=' . $object->getID()
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        } elseif (!$this->_nebuleInstance->getIsRecoveryEntity($entity)
                            || $this->_configuration->getOptionAsBoolean('permitRecoveryRemoveEntity')
                        ) {
                            // Annuler le partage de protection. Non fiable...
                            $list[$i]['param']['selfHookList'][0]['name'] = '::RemoveShareProtect';
                            $list[$i]['param']['selfHookList'][0]['icon'] = Displays::DEFAULT_ICON_LX;
                            $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_ICONS[3]
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_CANCEL_SHARE_PROTECT_TO_ENTITY . '=' . $entity
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object->getID()
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    }


                    // Marque comme vu.
                    $listOkEntities[$entity] = true;
                    $i++;
                }
            }
            unset($instance, $typeEntity, $shareTo, $listOkEntities);

            // Affichage.
            echo $this->_display->getDisplayObjectsList($list, 'medium');

            unset($list);
        }
    }


    /**
     * Affichage de la vue de protection.
     */
    private function _displayObjectProtectionShare(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();

        if ($object->getMarkProtected()) {
            // Affiche l'objet seul danns une liste.
            $list = array();
            $list[0]['object'] = $object;
            $list[0]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => true,
                'enableDisplayFlagObfuscate' => false,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => false,
                'enableDisplayStatus' => true,
                'enableDisplayContent' => false,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplayID' => false,
                'flagProtection' => true,
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
                'enableDisplayJS' => true,
            );
            echo $this->_display->getDisplayObjectsList($list, 'long');

            // affichage des boutons.
            echo $this->_display->getDisplayHookMenuList('::sylabe:module:objet:ProtectionShareButtons', 'medium');

            if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))) {
                // Affiche le titre.
                echo $this->_display->getDisplayTitle('::sylabe:module:objects:ShareObjectProtection', $this->MODULE_REGISTERED_ICONS[3], false);

                // Affiche en ligne les entités pour qui cela est partagé.
                $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('objectprotectionshareto');
            }
        }
    }

    /**
     * Affichage en ligne des entités pour lesquelles la protection peut être partagée.
     *
     * @return void
     */
    private function _display_InlineObjectProtectionShare(): void
    {
        $object = $this->_applicationInstance->getCurrentObjectInstance();
        $id = $this->_applicationInstance->getCurrentObject();

        // Si l'objet est présent et protégé et si l'entité est déverrouillée
        if ($object->getMarkProtected()
            && $this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))
        ) {
            $listOkEntities = array();
            $listOkGroups = array();
            $list = array();
            $i = 1;
            $instance = null;

            // Ajoute des entités et groupes à ne pas afficher.
            $listOkEntities[$this->_applicationInstance->getCurrentEntity()] = true;
            $listOkEntities[$this->_nebuleInstance->getInstanceEntity()] = true;
            $listOkEntities[$this->_nebuleInstance->getPuppetmaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getSecurityMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getCodeMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getDirectoryMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getTimeMaster()] = true;
            $listOkEntities[$this->_nebuleInstance->getCurrentEntity()] = true;
            $listOkGroups[$this->_applicationInstance->getCurrentEntity()] = true;
            $listOkGroups[$this->_nebuleInstance->getInstanceEntity()] = true;
            $listOkGroups[$this->_nebuleInstance->getPuppetmaster()] = true;
            $listOkGroups[$this->_nebuleInstance->getSecurityMaster()] = true;
            $listOkGroups[$this->_nebuleInstance->getCodeMaster()] = true;
            $listOkGroups[$this->_nebuleInstance->getDirectoryMaster()] = true;
            $listOkGroups[$this->_nebuleInstance->getTimeMaster()] = true;
            $listOkGroups[$this->_nebuleInstance->getCurrentEntity()] = true;

            // Ajout du message de protection.
            $list[0]['information'] = '::WarningSharedProtection';
            $list[0]['param'] = array(
                'enableDisplayIcon' => true,
                'informationType' => 'warn',
                'displaySize' => 'small', // Forcé par getDisplayObjectsList().
                'displayRatio' => 'short',
            );

            // Liste et enlève les entités pour lequelles la protection est déjà faite.
            $sharedTo = $object->getProtectedTo();
            foreach ($sharedTo as $entity) {
                $listOkEntities[$entity] = true;
            }

            // Liste et ajoute tous les groupes.
            $listGroups = $this->_nebuleInstance->getListGroupsID($this->_nebuleInstance->getCurrentEntity(), '');
            $typeGroup = false;
            $group = null;
            foreach ($listGroups as $group) {
                // @todo vérifier que le groupe ne contient pas juste des entités pour lesquelles le partage est effectif.

                $instance = $this->_nebuleInstance->newGroup($group);
                $typeGroup = $instance->getIsEntity('all');
                if (!isset($listOkGroups[$group])
                    && $typeGroup
                ) {
                    // Si c'est un groupe fermé.
                    $typeClosed = $instance->getMarkClosed();

                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => true,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => true,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShareToGroup',
                        'typeHookName' => '',
                    );

                    if ($typeClosed) {
                        $list[$i]['param']['status'] = '::GroupeFerme';
                    } else {
                        $list[$i]['param']['status'] = '::GroupeOuvert';
                    }

                    // Ajout l'action de partage d eprotection au groupe.
                    $list[$i]['param']['selfHookList'][0]['name'] = '::sylabe:module:objects:ShareProtectionToGroup';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                    if ($typeClosed) {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_ICONS[4]
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_CLOSED . '=' . $group
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    } else {
                        $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_ICONS[4]
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_GROUP_OPENED . '=' . $group
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }

                    // Marque comme vu.
                    $listOkGroups[$group] = true;
                    $i++;
                }
            }
            unset($listGroups, $group, $listOkGroups, $typeGroup, $sharedTo);

            // Liste toutes les autres entités.
            $hashType = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_TYPE);
            $hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
            $hashEntityObject = $this->_nebuleInstance->newObject($hashEntity);
            $links = $hashEntityObject->getLinksOnFields('', '', 'l', '', $hashEntity, $hashType);

            $typeEntity = false;
            $link = null;
            foreach ($links as $link) {
                $instance = $this->_nebuleInstance->newEntity($link->getParsed()['bl/rl/nid1']);
                $typeEntity = $instance->getIsEntity('all');
                if (!isset($listOkEntities[$link->getParsed()['bl/rl/nid1']])
                    && $typeEntity
                ) {
                    $list[$i]['object'] = $instance;
                    $list[$i]['param'] = array(
                        'enableDisplayColor' => true,
                        'enableDisplayIcon' => true,
                        'enableDisplayRefs' => false,
                        'enableDisplayName' => true,
                        'enableDisplayID' => true,
                        'enableDisplayFlags' => true,
                        'enableDisplayFlagProtection' => false,
                        'enableDisplayFlagObfuscate' => false,
                        'enableDisplayFlagUnlocked' => true,
                        'enableDisplayFlagState' => true,
                        'enableDisplayFlagEmotions' => true,
                        'enableDisplayStatus' => false,
                        'enableDisplayContent' => false,
                        'enableDisplayJS' => false,
                        'displaySize' => 'medium', // Forcé par getDisplayObjectsList().
                        'displayRatio' => 'short',
                        'link2Object' => '',
                        'enableDisplaySelfHook' => false,
                        'enableDisplayTypeHook' => false,
                        'selfHookName' => '::sylabe:module:object:protectShareTo',
                        'typeHookName' => '',
                    );

                    // Partager avec cette entité.
                    $list[$i]['param']['selfHookList'][0]['name'] = '::sylabe:module:objects:ShareProtectionToEntity';
                    $list[$i]['param']['selfHookList'][0]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                    $list[$i]['param']['selfHookList'][0]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[4]
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SHARE_PROTECT_TO_ENTITY . '=' . $link->getParsed()['bl/rl/nid1']
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                    // Marque comme vu.
                    $listOkEntities[$link->getParsed()['bl/rl/nid1']] = true;
                    $i++;
                }
            }
            unset($instance, $link, $typeEntity, $links, $listOkEntities);

            // Affichage.
            echo $this->_display->getDisplayObjectsList($list, 'medium');

            unset($list);
        }
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleName'] = 'Module des objets';
        $this->_table['en-en']['::sylabe:module:objects:ModuleName'] = 'Objects module';
        $this->_table['es-co']['::sylabe:module:objects:ModuleName'] = 'Módulo de objetos';
        $this->_table['fr-fr']['::sylabe:module:objects:MenuName'] = 'Objets';
        $this->_table['en-en']['::sylabe:module:objects:MenuName'] = 'Objects';
        $this->_table['es-co']['::sylabe:module:objects:MenuName'] = 'Objetos';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleDescription'] = 'Module de gestion des objets.';
        $this->_table['en-en']['::sylabe:module:objects:ModuleDescription'] = 'Object management module.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleDescription'] = 'Módulo de gestión de objetos.';
        $this->_table['fr-fr']['::sylabe:module:objects:ModuleHelp'] = "Ce module permet de voir et de gérer les objets.";
        $this->_table['en-en']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage objects.';
        $this->_table['es-co']['::sylabe:module:objects:ModuleHelp'] = 'This module permit to see and manage objects.';

        $this->_table['fr-fr']['::sylabe:module:objects:AppTitle1'] = 'Objets';
        $this->_table['en-en']['::sylabe:module:objects:AppTitle1'] = 'Objects';
        $this->_table['es-co']['::sylabe:module:objects:AppTitle1'] = 'Objetos';
        $this->_table['fr-fr']['::sylabe:module:objects:AppDesc1'] = 'Affiche les objets.';
        $this->_table['en-en']['::sylabe:module:objects:AppDesc1'] = 'Display objects.';
        $this->_table['es-co']['::sylabe:module:objects:AppDesc1'] = 'Display objects.';

        $this->_table['fr-fr']['::sylabe:module:objects:DisplayObject'] = "Afficher l'objet";
        $this->_table['en-en']['::sylabe:module:objects:DisplayObject'] = 'Display object';
        $this->_table['es-co']['::sylabe:module:objects:DisplayObject'] = 'Display object';
        $this->_table['fr-fr']['::sylabe:module:objects:DisplayObjectUpdated'] = "Afficher l'objet à jour";
        $this->_table['en-en']['::sylabe:module:objects:DisplayObjectUpdated'] = 'Display updated object';
        $this->_table['es-co']['::sylabe:module:objects:DisplayObjectUpdated'] = 'Display updated object';
        $this->_table['fr-fr']['::sylabe:module:objects:ObjectDescription'] = 'Afficher la description';
        $this->_table['en-en']['::sylabe:module:objects:ObjectDescription'] = 'Display description';
        $this->_table['es-co']['::sylabe:module:objects:ObjectDescription'] = 'Display description';
        $this->_table['fr-fr']['::sylabe:module:objects:ObjectRelations'] = 'Relations';
        $this->_table['en-en']['::sylabe:module:objects:ObjectRelations'] = 'Relations';
        $this->_table['es-co']['::sylabe:module:objects:ObjectRelations'] = 'Relations';

        $this->_table['fr-fr']['::sylabe:module:objects:ObjectDelete'] = 'Supprimer';
        $this->_table['en-en']['::sylabe:module:objects:ObjectDelete'] = 'Delete';
        $this->_table['es-co']['::sylabe:module:objects:ObjectDelete'] = 'Delete';
        $this->_table['fr-fr']['::sylabe:module:objects:ObjectDownload'] = 'Télécharger';
        $this->_table['en-en']['::sylabe:module:objects:ObjectDownload'] = 'Download';
        $this->_table['es-co']['::sylabe:module:objects:ObjectDownload'] = 'Download';

        $this->_table['fr-fr']['::sylabe:module:objects:LinksSrc'] = 'Source des liens bruts.';
        $this->_table['en-en']['::sylabe:module:objects:LinksSrc'] = 'Source of raw links.';
        $this->_table['es-co']['::sylabe:module:objects:LinksSrc'] = 'Fuente de enlaces primas.';

        $this->_table['fr-fr']['::sylabe:module:objects:DisplayAsObject'] = 'Voir comme objet';
        $this->_table['en-en']['::sylabe:module:objects:DisplayAsObject'] = 'See as object';
        $this->_table['es-co']['::sylabe:module:objects:DisplayAsObject'] = 'See as object';
        $this->_table['fr-fr']['::sylabe:module:objects:DisplayNewObject'] = 'Voir le nouvel objet';
        $this->_table['en-en']['::sylabe:module:objects:DisplayNewObject'] = 'See the new object';
        $this->_table['es-co']['::sylabe:module:objects:DisplayNewObject'] = 'See the new object';

        $this->_table['fr-fr']['::sylabe:module:objects:Actions'] = "Actions sur l'objet";
        $this->_table['en-en']['::sylabe:module:objects:Actions'] = 'Actions on object';
        $this->_table['es-co']['::sylabe:module:objects:Actions'] = 'Actions on object';
        $this->_table['fr-fr']['::sylabe:module:objects:ActionsDesc'] = 'Les actions simples sur cet objet.';
        $this->_table['en-en']['::sylabe:module:objects:ActionsDesc'] = 'Simple actions on this object.';
        $this->_table['es-co']['::sylabe:module:objects:ActionsDesc'] = 'Simple actions on this object.';

        $this->_table['fr-fr']['::sylabe:module:objects:ExtendedActions'] = "Actions étendues sur l'objet";
        $this->_table['en-en']['::sylabe:module:objects:ExtendedActions'] = 'Extended actions on object';
        $this->_table['es-co']['::sylabe:module:objects:ExtendedActions'] = 'Extended actions on object';
        $this->_table['fr-fr']['::sylabe:module:objects:ExtendedActionsDesc'] = 'Les actions avancées sur cet objet.';
        $this->_table['en-en']['::sylabe:module:objects:ExtendedActionsDesc'] = 'Advanced actions on this object.';
        $this->_table['es-co']['::sylabe:module:objects:ExtendedActionsDesc'] = 'Advanced actions on this object.';

        $this->_table['fr-fr']['::sylabe:module:objects:Description'] = "Description de l'objet";
        $this->_table['en-en']['::sylabe:module:objects:Description'] = 'Object description';
        $this->_table['es-co']['::sylabe:module:objects:Description'] = 'Object description';
        $this->_table['fr-fr']['::sylabe:module:objects:DescriptionDesc'] = "Les propriétés de l'objet.";
        $this->_table['en-en']['::sylabe:module:objects:DescriptionDesc'] = "Object's properties.";
        $this->_table['es-co']['::sylabe:module:objects:DescriptionDesc'] = "Object's properties.";
        $this->_table['fr-fr']['::sylabe:module:objects:Nothing'] = 'Rien à afficher.';
        $this->_table['en-en']['::sylabe:module:objects:Nothing'] = 'Nothing to display.';
        $this->_table['es-co']['::sylabe:module:objects:Nothing'] = 'Nothing to display.';

        $this->_table['fr-fr']['::sylabe:module:objects:Action:Download'] = "Télécharger l'objet.";
        $this->_table['en-en']['::sylabe:module:objects:Action:Download'] = 'Download object.';
        $this->_table['es-co']['::sylabe:module:objects:Action:Download'] = 'Download object.';

        $this->_table['fr-fr']['::sylabe:module:objects:Desc:Attrib'] = 'Propriété';
        $this->_table['en-en']['::sylabe:module:objects:Desc:Attrib'] = 'Attribut';
        $this->_table['es-co']['::sylabe:module:objects:Desc:Attrib'] = 'Attribut';
        $this->_table['fr-fr']['::sylabe:module:objects:Desc:Value'] = 'Valeur';
        $this->_table['en-en']['::sylabe:module:objects:Desc:Value'] = 'Value';
        $this->_table['es-co']['::sylabe:module:objects:Desc:Value'] = 'Value';
        $this->_table['fr-fr']['::sylabe:module:objects:Desc:Signer'] = 'Emetteur';
        $this->_table['en-en']['::sylabe:module:objects:Desc:Signer'] = 'Sender';
        $this->_table['es-co']['::sylabe:module:objects:Desc:Signer'] = 'Sender';

        $this->_table['fr-fr']['::sylabe:module:objects:Protection'] = 'Protection';
        $this->_table['en-en']['::sylabe:module:objects:Protection'] = 'Protection';
        $this->_table['es-co']['::sylabe:module:objects:Protection'] = 'Protection';
        $this->_table['fr-fr']['::sylabe:module:objects:ShareProtection'] = 'Partager la protection';
        $this->_table['en-en']['::sylabe:module:objects:ShareProtection'] = 'Share protection';
        $this->_table['es-co']['::sylabe:module:objects:ShareProtection'] = 'Share protection';
        $this->_table['fr-fr']['::sylabe:module:objects:ShareObjectProtection'] = "Partager la protection de l'objet";
        $this->_table['en-en']['::sylabe:module:objects:ShareObjectProtection'] = 'Share protection of this objet';
        $this->_table['es-co']['::sylabe:module:objects:ShareObjectProtection'] = 'Share protection of this objet';
        $this->_table['fr-fr']['::sylabe:module:objects:ShareProtectionToGroup'] = 'Partager la protection';
        $this->_table['en-en']['::sylabe:module:objects:ShareProtectionToGroup'] = 'Share protection';
        $this->_table['es-co']['::sylabe:module:objects:ShareProtectionToGroup'] = 'Share protection';
        $this->_table['fr-fr']['::sylabe:module:objects:ShareProtectionToEntity'] = 'Partager la protection';
        $this->_table['en-en']['::sylabe:module:objects:ShareProtectionToEntity'] = 'Share protection';
        $this->_table['es-co']['::sylabe:module:objects:ShareProtectionToEntity'] = 'Share protection';
        $this->_table['fr-fr']['::WarningSharedProtection'] = "Lorsque la protection d'un objet est partagée, son annulation est incertaine !";
        $this->_table['en-en']['::WarningSharedProtection'] = 'When protection of an object is shared, its cancellation is uncertain!';
        $this->_table['es-co']['::WarningSharedProtection'] = 'Donde se comparte la protección de un objeto, su cancelación esta incierto!';
        $this->_table['fr-fr']['::RemoveShareProtect'] = 'Annuler le partage de protection';
        $this->_table['en-en']['::RemoveShareProtect'] = 'Cancel share protection';
        $this->_table['es-co']['::RemoveShareProtect'] = 'Cancel share protection';
        $this->_table['fr-fr']['::ProtectObject'] = "Protéger l'objet";
        $this->_table['en-en']['::ProtectObject'] = 'Protect the object';
        $this->_table['es-co']['::ProtectObject'] = 'Protect the object';
        $this->_table['fr-fr']['::ProtectedObject'] = 'Cet objet est protégé.';
        $this->_table['en-en']['::ProtectedObject'] = 'This object is protected.';
        $this->_table['es-co']['::ProtectedObject'] = 'This object is protected.';
        $this->_table['fr-fr']['::ProtectedID'] = 'ID objet en clair';
        $this->_table['en-en']['::ProtectedID'] = 'Clear object ID';
        $this->_table['es-co']['::ProtectedID'] = 'Clear object ID';
        $this->_table['fr-fr']['::UnprotectedID'] = 'ID objet chiffré';
        $this->_table['en-en']['::UnprotectedID'] = 'Encrypted object ID';
        $this->_table['es-co']['::UnprotectedID'] = 'Encrypted object ID';
        $this->_table['fr-fr']['::UnprotectedObject'] = "Cet objet n'est pas protégé.";
        $this->_table['en-en']['::UnprotectedObject'] = 'This object is not protected.';
        $this->_table['es-co']['::UnprotectedObject'] = 'This object is not protected.';
        $this->_table['fr-fr']['::UnprotectObject'] = "Déprotéger l'objet";
        $this->_table['en-en']['::UnprotectObject'] = 'Unprotect the object';
        $this->_table['es-co']['::UnprotectObject'] = 'Unprotect the object';
        $this->_table['fr-fr']['::WarningProtectObject'] = "La protection d'un objet déjà existant est incertaine !";
        $this->_table['en-en']['::WarningProtectObject'] = 'The protection of an existing object is uncertain!';
        $this->_table['es-co']['::WarningProtectObject'] = 'The protection of an existing object is uncertain!';
        $this->_table['fr-fr']['::WarningDoNotProtectEntity'] = "La protection d'une entité la rend indisponible !";
        $this->_table['en-en']['::WarningDoNotProtectEntity'] = 'The protection of an entity make it unavailable!';
        $this->_table['es-co']['::WarningDoNotProtectEntity'] = 'The protection of an entity make it unavailable!';
        $this->_table['fr-fr']['::ErrorCantProtectObject'] = 'Cet objet ne peut pas être protégé.';
        $this->_table['en-en']['::ErrorCantProtectObject'] = "This object can't be protected.";
        $this->_table['es-co']['::ErrorCantProtectObject'] = "This object can't be protected.";

        $this->_table['fr-fr']['::sylabe:module:objects:AttribNotDisplayable'] = 'Propriété non affichable !';
        $this->_table['en-en']['::sylabe:module:objects:AttribNotDisplayable'] = 'Attribut not displayable!';
        $this->_table['es-co']['::sylabe:module:objects:AttribNotDisplayable'] = 'Attribut not displayable!';

        $this->_table['fr-fr']['::GroupeFerme'] = 'Groupe fermé';
        $this->_table['en-en']['::GroupeFerme'] = 'Closed group';
        $this->_table['es-co']['::GroupeFerme'] = 'Closed group';
        $this->_table['fr-fr']['::GroupeOuvert'] = 'Groupe ouvert';
        $this->_table['en-en']['::GroupeOuvert'] = 'Opened group';
        $this->_table['es-co']['::GroupeOuvert'] = 'Opened group';
    }
}


/**
 * Cette application permet de gérer et de générer les messages.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleMessenger extends Modules
{
    protected $MODULE_TYPE = 'Application';
    protected $MODULE_NAME = '::sylabe:module:messenger:ModuleName';
    protected $MODULE_MENU_NAME = '::sylabe:module:messenger:MenuName';
    protected $MODULE_COMMAND_NAME = 'msg';
    protected $MODULE_DEFAULT_VIEW = 'list';
    protected $MODULE_DESCRIPTION = '::sylabe:module:messenger:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = '836e7786bbfc0f360d9c85908920b6dcaee34d46ca8e9c1de3a8462d95388b49';
    protected $MODULE_HELP = '::sylabe:module:messenger:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array(
        'disp',        // 0 : Lister mes conversations ou mes messages.
        'list',        // 1 : Lister les conversations.
        'newcvt',    // 2 : Créer une conversation.
        'cvt',        // 3 : Voir conversation.
        'asopened',    // 4 : Option, voir la conversation comme ouverte.
        'newmsg',    // 5 : Créer un nouveau message.
        'msg',        // 6 : Voir le message (inline).
        'dest',        // 7 : Gérer les destinataires de la conversation.
        'hlp',        // 8 : Voir l'aide.
        'listall',    // 9 : Option, voir toutes les conversations.
    );
    protected $MODULE_REGISTERED_ICONS = array(
        '836e7786bbfc0f360d9c85908920b6dcaee34d46ca8e9c1de3a8462d95388b49',    // 0 : conversations
        '5ad3eeed7276a8556a4bb7dad9f9a1dc9cf5bf62869bc0ec391f58f06255ace8',    // 1 : créer conversation
        'ff3fb6c1cfee8d9199e298afcf9e283c5db1b91696dc2677e110a026991aeea6',    // 2 : ajouter message
        '9a7ff374f5de2b18975f2965339fb5b25915d1e64a5034354ca1607deaade057',    // 3 : ajouter entité
        '77a2eaeedd706b8d62c6e7f39f4f42d4dcf3d78d2145bcd0e6d9d7b4d6852e8f',    // 4 : objet d'une conversation
        'fdc7745129235ee0952a3276a862c0f36c070005f677889543ce8120c79c5e76',    // 5 : entité d'une conversation
    );
    protected $MODULE_APP_TITLE_LIST = array('::sylabe:module:messenger:AppTitle1');
    protected $MODULE_APP_ICON_LIST = array('836e7786bbfc0f360d9c85908920b6dcaee34d46ca8e9c1de3a8462d95388b49');
    protected $MODULE_APP_DESC_LIST = array('::sylabe:module:messenger:AppDesc1');
    protected $MODULE_APP_VIEW_LIST = array('list');

    // Valeurs par défaut des options.
    const DEFAULT_PERMIT_SUB_REPLY = false;
    const DEFAULT_FOLLOWERS_DISPLAY_NUMBER = 5;
    const DEFAULT_CONVERSATIONS_DISPLAY_NUMBER = 10;
    const DEFAULT_MESSAGES_DISPLAY_NUMBER = 10;
    const COMMAND_SELECT_LINK_SIGN = 'lnksign';

    private $_hashConversation, $_hashConversationFollow, $_hashConversationObject, $_hashConversationClosed, $_hashConversationClosedObject;
    /**
     * ID de la conversation.
     */
    private $_conversation = '';
    private $_conversationInstance = '';


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_display = $this->_applicationInstance->getDisplayInstance();
        $this->_traduction = $this->_applicationInstance->getTraductionInstance();
        $this->_unlocked = $this->_nebuleInstance->getCurrentEntityUnlocked();
        $this->_initTable();
        $this->_hashConversation = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION);
        $this->_hashConversationFollow = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE);
        $this->_hashConversationObject = $this->_nebuleInstance->newObject($this->_hashConversation);
        $this->_hashConversationClosed = $this->_nebuleInstance->getCryptoInstance()->hash(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION_FERMEE);
        $this->_hashConversationClosedObject = $this->_nebuleInstance->newObject($this->_hashConversationClosed);
        $this->_conversation = $this->_nebuleInstance->getCurrentConversation();
        $this->_conversationInstance = $this->_nebuleInstance->getCurrentConversationInstance();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        if ($object == 'none') {
            $object = $this->_conversation;
        }
        if (is_a($object, 'Node')) {
            $objectInstance = $object;
            $object = $object->getID();
        } else {
            $objectInstance = $this->_nebuleInstance->newConversation($object);
        }

        $hookArray = array();
        $arraySeed = 0;
        switch ($hookName) {
            case 'menu':
                if ($object != '0'
                    && $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayMode() != $this->MODULE_COMMAND_NAME
                ) {
                    // Retour à la conversation en cours.
                    $arraySeed++;
                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:seeCurrentConversation';
                    $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[$arraySeed]['desc'] = '';
                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object;
                }
                break;
            case 'selfMenu':
                // Détection de la vue réelle.
                $view = $this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView();
                if ($view == $this->MODULE_REGISTERED_VIEWS[0]) {
                    if ($this->_conversation == ''
                        || $this->_conversation == '0'
                        || $this->_conversation === false
                    ) {
                        $view = $this->MODULE_REGISTERED_VIEWS[1];
                    } else {
                        $view = $this->MODULE_REGISTERED_VIEWS[3];
                    }
                }

                if ($view == $this->MODULE_REGISTERED_VIEWS[1]) {
                    // Regarde si l'entité affichée est l'entité connectée.
                    if ($this->_applicationInstance->getCurrentEntity() != $this->_nebuleInstance->getCurrentEntity()) {
                        // Mes conversations.
                        $arraySeed++;
                        $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:myConversationsListTitle';
                        $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                        $hookArray[$arraySeed]['desc'] = '';
                        $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                    } else {
                        // Regarde si toutes les conversations sont affichées.
                        if ($this->_extractDisplayListAll()) {
                            // Les conversations.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:myConversationsListTitle';
                            $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';

                            // Tous les objetcs conversations.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:conversationsListAllObjects';
                            $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[9]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                        } else {
                            // Voir toutes les conversations.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:conversationsListAllTitle';
                            $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                                . '&' . $this->MODULE_REGISTERED_VIEWS[9]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                        }
                    }
                } else {
                    // Mes conversations.
                    $arraySeed++;
                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:myConversationsListTitle';
                    $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                    $hookArray[$arraySeed]['desc'] = '';
                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                }

                if ($view == $this->MODULE_REGISTERED_VIEWS[1]
                    && $this->_unlocked
                ) {
                    // Ajouter une conversation.
                    $arraySeed++;
                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:conversationsAddTitle';
                    $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                    $hookArray[$arraySeed]['desc'] = '';
                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[2]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity();
                }

                // Si ajout d'un message ou de destinataires.
                if ($view == $this->MODULE_REGISTERED_VIEWS[5]
                    || $view == $this->MODULE_REGISTERED_VIEWS[7]
                ) {
                    // Retour à la conversation lors de la création de message ou lors de la gestion des correspondants.
                    $arraySeed++;
                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:returnToConversation';
                    $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[4];
                    $hookArray[$arraySeed]['desc'] = '';
                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;
                }

                if ($view != $this->MODULE_REGISTERED_VIEWS[8]) {
                    // Affiche l'aide.
                    $arraySeed++;
                    $hookArray[$arraySeed]['name'] = $this->_traduction->getTraduction('::::help');
                    $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_HELP;
                    $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:helpConversation';
                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[8];
                }
                break;

            case 'selfMenuConversation':
                if ($this->_display->getCurrentDisplayMode() == $this->MODULE_COMMAND_NAME) {
                    if ($this->_display->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[3]) {
                        if ($this->_unlocked) {
                            // Synchroniser.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::Synchronize';
                            $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_SYNOBJ;
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SYNCHRONIZE_OBJECT . '=' . $object
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();

                            // Création d'un message.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:messageAddTitle';
                            $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[2];
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[5]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object;

                            // Si la conversation est marquée fermée.
                            if ($objectInstance->getMarkClosed()) {
                                // Si on demande à voir la conversation comme ouverte.
                                if ($this->_extractDisplayAsOpened()) {
                                    // Voir la conversation comme fermée.
                                    $arraySeed++;
                                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:SeeConversationAsClosed';
                                    $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LF;
                                    $hookArray[$arraySeed]['desc'] = '';
                                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;
                                } else {
                                    // Voir la conversation comme ouverte.
                                    $arraySeed++;
                                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:SeeConversationAsOpened';
                                    $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LF;
                                    $hookArray[$arraySeed]['desc'] = '';
                                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . $this->MODULE_REGISTERED_VIEWS[4]
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object;
                                }
                                // Rendre la conversation ouverte.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:OpeningConversation';
                                $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LF;
                                $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:OpenTheConversation';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $object
                                    . '_' . $this->_applicationInstance->getCurrentEntity()
                                    . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            } else {
                                // Rendre la conversation fermée.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:ClosingConversation';
                                $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LF;
                                $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:CloseTheConversation';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $object
                                    . '_' . $this->_applicationInstance->getCurrentEntity()
                                    . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }

                            // Si la conversation est marquée protégée.
                            if ($objectInstance->getMarkProtected()) {
                                // Rendre la conversation non protégée.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:unprotectingConversationMessages';
                                $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LK;
                                $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:unprotectTheConversationMessages';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $object
                                    . '_' . $this->_applicationInstance->getCurrentEntity()
                                    . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            } else {
                                // Rendre la conversation protégée.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:protectingConversationMessages';
                                $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LK;
                                $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:protectTheConversationMessages';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $object
                                    . '_' . $this->_applicationInstance->getCurrentEntity()
                                    . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }

                            // Si authorisé à dissimuler les liens.
                            if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                                // Si la conversation est marquée dissimulée.
                                if ($objectInstance->getMarkObfuscated()) {
                                    // Rendre la conversation non dissimulée.
                                    $arraySeed++;
                                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:unobfuscatingConversationMessages';
                                    $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LC;
                                    $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:unobfuscateTheConversationMessages';
                                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $object
                                        . '_' . $this->_applicationInstance->getCurrentEntity()
                                        . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                                } else {
                                    // Rendre la conversation dissimulée.
                                    $arraySeed++;
                                    $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:obfuscatingConversationMessages';
                                    $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LC;
                                    $hookArray[$arraySeed]['desc'] = '::sylabe:module:messenger:obfuscateTheConversationMessages';
                                    $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $object
                                        . '_' . $this->_applicationInstance->getCurrentEntity()
                                        . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                                }
                            }

                            // Si pas une de mes conversations, ajouter la conversation.
                            if (!$this->_conversationInstance->getIsFollower($this->_applicationInstance->getCurrentEntity(), 'myself')) {
                                // Ajouter à ma liste de conversations.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:addAsMyConversation';
                                $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                                $hookArray[$arraySeed]['desc'] = '';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_nebuleInstance->getCurrentEntity()
                                    . '_' . $this->_conversation
                                    . '_' . $this->_hashConversationFollow
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            } else {
                                // Retirer de ma liste de conversations.
                                $arraySeed++;
                                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:removeAsMyConversation';
                                $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LX;
                                $hookArray[$arraySeed]['desc'] = '';
                                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_nebuleInstance->getCurrentEntity()
                                    . '_' . $this->_conversation
                                    . '_' . $this->_hashConversationFollow
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                            }
                        }

                        // Voir/modifier les destinataires d'une conversation.
                        $arraySeed++;
                        $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:conversationDestEntities';
                        $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[5];
                        $hookArray[$arraySeed]['desc'] = '';
                        $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object;
                    } else {
                        // Retour à la conversation en cours.
                        $arraySeed++;
                        $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:seeCurrentConversation';
                        $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                        $hookArray[$arraySeed]['desc'] = '';
                        $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object;
                    }

                    if ($this->_display->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[7]
                        && $this->_unlocked
                    ) {
                        // Si pas une de mes conversations, ajouter la conversation.
                        if (!$this->_conversationInstance->getIsFollower($this->_applicationInstance->getCurrentEntity(), 'myself')) {
                            // Ajouter à ma liste de conversations.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:addAsMyConversation';
                            $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[1];
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_nebuleInstance->getCurrentEntity()
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        } else {
                            // Retirer de ma liste de conversations.
                            $arraySeed++;
                            $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:removeAsMyConversation';
                            $hookArray[$arraySeed]['icon'] = Displays::DEFAULT_ICON_LX;
                            $hookArray[$arraySeed]['desc'] = '';
                            $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_nebuleInstance->getCurrentEntity()
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                        }
                    }
                }
                break;

            case 'selfMenuEntity':
                $arraySeed++;
                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:MyConversations';
                $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[$arraySeed]['desc'] = '';
                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                break;

            case 'typeMenuEntity':
                $arraySeed++;
                $hookArray[$arraySeed]['name'] = '::sylabe:module:messenger:Conversations';
                $hookArray[$arraySeed]['icon'] = $this->MODULE_REGISTERED_ICONS[0];
                $hookArray[$arraySeed]['desc'] = '';
                $hookArray[$arraySeed]['link'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $object
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0';
                break;
        }
        return $hookArray;
    }

    private function _extractDisplayListAll(): bool
    {
        return filter_has_var(INPUT_GET, $this->MODULE_REGISTERED_VIEWS[9]);
    }

    private function _extractDisplayAsOpened(): bool
    {
        return filter_has_var(INPUT_GET, $this->MODULE_REGISTERED_VIEWS[4]);
    }

    /**
     * Extrait la signature du lien à afficher.
     *
     * @return string
     */
    private function _extractLinkSign(): string
    {
        /*
		 *  ------------------------------------------------------------------------------------------
		 *  DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER DANGER
		 *  ------------------------------------------------------------------------------------------
		 */
        // Lit et nettoye le contenu de la variable GET.
        $linkid = trim(filter_input(INPUT_GET, self::COMMAND_SELECT_LINK_SIGN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));

        // Si le champs est vide, extrait le contenu de la variable POST.
        if ($linkid == '') {
            $linkid = trim(filter_input(INPUT_POST, self::COMMAND_SELECT_LINK_SIGN, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW));
        }

        return $linkid;
    }

    /**
     * Extrait le lien à afficher.
     *
     * @return string
     */
    private function _extractLink(): string
    {
        $linkid = $this->_extractLinkSign();
        $linkok = '';
        $links = $this->_conversationInstance->readLinksFilterOnce($linkid);
        foreach ($links as $link) {
            if ($link->getSigneValueAlgo() == $linkid) {
                $linkok = $link->getFullLink();
                break;
            }
        }
        unset($links, $linkid, $link);

        return $linkok;
    }


    /**
     * Affichage principale.
     *
     * La vue 4 sert à passer une option et non à l'affichage direct.
     * La vue 9 sert à passer une option ou à l'affichage direct.
     *
     * @return void
     */
    protected function _displayFull(): void
    {
        $this->_displayHeader();
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_displayDisp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_displayList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[2]:
                $this->_displayCreateConversation();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_displayConversation();
                break;
            case $this->MODULE_REGISTERED_VIEWS[5]:
                $this->_displayCreateMessage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[6]:
                $this->_displayMessage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[7]:
                $this->_displayFollowers();
                break;
            case $this->MODULE_REGISTERED_VIEWS[8]:
                $this->_displayHelp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[9]:
                $this->_displayListAll();
                break;
            default:
                $this->_displayDisp();
                break;
        }
    }

    /**
     * Affichage en ligne comme élément inseré dans une page web.
     *
     * @return void
     */
    protected function _displayInline(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView()) {
            case $this->MODULE_REGISTERED_VIEWS[0]:
                $this->_display_InlineDisp();
                break;
            case $this->MODULE_REGISTERED_VIEWS[1]:
                $this->_display_InlineList();
                break;
            case $this->MODULE_REGISTERED_VIEWS[3]:
                $this->_display_InlineConversation();
                break;
            case $this->MODULE_REGISTERED_VIEWS[6]:
                $this->_display_InlineMessage();
                break;
            case $this->MODULE_REGISTERED_VIEWS[7]:
                $this->_display_InlineFollowers();
                break;
            case $this->MODULE_REGISTERED_VIEWS[9]:
                $this->_display_InlineListAll();
                break;
        }
    }

    /**
     * Affichage de surcharges CSS.
     *
     * @return void
     */
    public function headerStyle(): void
    {
        ?>
        #sylabeModuleMessagerieBoutonSubmitMessage { cursor: pointer; background:url(<?php echo $this->_applicationInstance->getDisplayInstance()->prepareIcon(display::DEFAULT_ICON_LL); ?>) no-repeat; margin-right:5px; margin-top:0; float:left; }
        #sylabeModuleMessagerieBoutonSubmitMessage:hover { background:url(<?php echo $this->_applicationInstance->getDisplayInstance()->prepareIcon(display::DEFAULT_ICON_LLL); ?>) no-repeat; }

        <?php
    }


    /**
     * Affichage de l'entête des pages.
     */
    private function _displayHeader(): void
    {
        //...
    }


    /**
     * Si aucune conversation n'est précisée, affiche la liste des conversations.
     * Sinon affiche la conversation.
     */
    private function _displayDisp(): void
    {
        if ($this->_conversation == ''
            || $this->_conversation == '0'
            || $this->_conversation === false
        ) {
            $this->_displayList();
        } else {
            $this->_displayConversation();
        }
    }

    private function _display_InlineDisp(): void
    {
        if ($this->_conversation == ''
            || $this->_conversation == '0'
            || $this->_conversation === false
        ) {
            $this->_display_InlineList();
        } else {
            $this->_display_InlineConversation();
        }
    }


    /**
     * Affichage de la liste des conversations de l'entité.
     */
    private function _displayList(): void
    {
        // Si une conversation à été créée.
        if ($this->_applicationInstance->getActionInstance()->getCreateConversation()) {
            // Titre.
            echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationsAddTitle', $this->MODULE_REGISTERED_ICONS[1]);

            // Récupération du résultat de la création.
            $createConversationID = $this->_applicationInstance->getActionInstance()->getCreateConversationID();
            $createConversationInstance = $this->_applicationInstance->getActionInstance()->getCreateConversationInstance();
            $this->_nebuleInstance->unsetCacheConversation($createConversationID);
            $createConversationError = $this->_applicationInstance->getActionInstance()->getCreateConversationError();
            $createConversationErrorMessage = $this->_applicationInstance->getActionInstance()->getCreateConversationErrorMessage();

            //Ecriture comme conversation courante.
            $this->_conversation = $createConversationID;
            $this->_conversationInstance = $createConversationInstance;

            // Si la création à réussi.
            if (!$createConversationError
                && is_a($createConversationInstance, 'Conversation')
            ) {
                $list = array();

                // Détection de l'état de fermeture.
                $markClosed = $this->_conversationInstance->getMarkClosed($this->_nebuleInstance->getCurrentEntity());
                $markClosedDesc = '::sylabe:module:messenger:conversationOpened';
                $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
                    . '_' . $this->_applicationInstance->getCurrentEntity()
                    . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                if ($markClosed) {
                    $markClosedDesc = '::sylabe:module:messenger:conversationClosed';
                    $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                        . '_' . $this->_applicationInstance->getCurrentEntity()
                        . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Détection de l'état de protection.
                $markProtected = $this->_conversationInstance->getMarkProtected($this->_nebuleInstance->getCurrentEntity());
                $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultUnprotected';
                $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
                    . '_' . $this->_applicationInstance->getCurrentEntity()
                    . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                if ($markProtected) {
                    $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultProtected';
                    $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                        . '_' . $this->_applicationInstance->getCurrentEntity()
                        . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                }

                // Détection de l'état de dissimulation.
                if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    $markObfuscated = $this->_conversationInstance->getMarkObfuscated($this->_nebuleInstance->getCurrentEntity());
                    $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultUnobfuscated';
                    $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
                        . '_' . $this->_applicationInstance->getCurrentEntity()
                        . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    if ($markObfuscated) {
                        $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultObfuscated';
                        $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                            . '_' . $this->_applicationInstance->getCurrentEntity()
                            . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
                    }
                }

                // Si pas de droits, pas d'actions possibles.
                if (!$this->_configuration->getOptionAsBoolean('permitWrite')
                    || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
                    || !$this->_unlocked
                ) {
                    $markClosedLink = '';
                    $markProtectedLink = '';
                    $markObfuscatedLink = '';
                }

                $list[0]['object'] = $this->_conversationInstance;
                $list[0]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'enableDisplayObjectActions' => true,
                    'enableDisplaySelfHook' => true,
                    'enableDisplayTypeHook' => true,
                    'enableDisplayJS' => true,
                    'objectName' => $this->_conversationInstance->getName(),
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectRefs' => $this->_conversationInstance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION),
                    'status' => $this->_conversationInstance->getCountMembers('all', null),
                    'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation,
                    'flagProtection' => $markProtected,
                    'flagProtectionText' => $markProtectedDesc,
                    'flagProtectionLink' => $markProtectedLink,
                    'flagUnlocked' => $markClosed,
                    'flagUnlockedText' => $markClosedDesc,
                    'flagUnlockedIcon' => Displays::DEFAULT_ICON_LF,
                    'flagUnlockedLink' => $markClosedLink,
                );
                if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    $list[0]['param']['enableDisplayFlagObfuscate'] = true;
                    $list[0]['param']['flagObfuscate'] = $markObfuscated;
                    $list[0]['param']['flagObfuscateText'] = $markObfuscatedDesc;
                    $list[0]['param']['flagObfuscateLink'] = $markObfuscatedLink;
                }

                // Affichage.
                echo $this->_display->getDisplayObjectsList($list, 'medium');
                unset($list);
            } else {
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'informationType' => 'error',
                );
                echo $this->_display->getDisplayInformation('::sylabe:module:messenger:create:notOKCreateConversation', $param);
            }
        }

        if ($this->_extractDisplayListAll()) {
            // Titre.
            echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationsListAllTitle', $this->MODULE_LOGO);

            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listconversations', $this->MODULE_REGISTERED_VIEWS[9]);
        } else {
            // Titre.
            /*if ( $this->_applicationInstance->getCurrentEntity() == $this->_nebuleInstance->getCurrentEntity() )
			{*/
            echo $this->_display->getDisplayTitle('::sylabe:module:messenger:myConversationsListTitle', $this->MODULE_LOGO);
            /*}
			else
			{
				echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationsListTitle', $this->MODULE_LOGO);
			}*/

            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listconversations');
        }
    }

    /**
     * Affichage de la liste des conversations de l'entité, en ligne.
     */
    private function _display_InlineList(): void
    {
        $listInstances = array();
        $objectInstance = null;
        $id = null;

        // Extrait la liste des conversations.
        if ($this->_extractDisplayListAll()) {
            $socialClass = 'all';
        } else {
            $socialClass = 'self';
        }
        $listID = array_reverse($this->_applicationInstance->getCurrentEntityInstance()->getListIsFollowerOnConversationID($socialClass));

        $list = array();
        $i = 0;
        $listOkConversation = array();

        // Pour chaque lien.
        foreach ($listID as $id) {
            if (!isset($listOkConversation[$id])) {
                $objectInstance = $this->_nebuleInstance->newConversation($id);
                // Détection de l'état de fermeture.
                $markClosed = $objectInstance->getMarkClosed($this->_nebuleInstance->getCurrentEntity());
                $markClosedDesc = '::sylabe:module:messenger:conversationOpened';
                if ($markClosed) {
                    $markClosedDesc = '::sylabe:module:messenger:conversationClosed';
                }

                // Détection de l'état de protection.
                $markProtected = $objectInstance->getMarkProtected($this->_nebuleInstance->getCurrentEntity());
                $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultUnprotected';
                if ($markProtected) {
                    $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultProtected';
                }

                // Détection de l'état de dissimulation.
                if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    $markObfuscated = $objectInstance->getMarkObfuscated($this->_nebuleInstance->getCurrentEntity());
                    $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultUnobfuscated';
                    if ($markObfuscated) {
                        $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultObfuscated';
                    }
                }

                $list[$i]['object'] = $objectInstance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'enableDisplayObjectActions' => false,
                    'enableDisplaySelfHook' => false,
                    'enableDisplayTypeHook' => false,
                    'enableDisplayJS' => false,
                    'objectName' => $objectInstance->getName('all'),
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'objectRefs' => $objectInstance->getListFollowersID($socialClass, null),
                    'status' => $objectInstance->getCountMembers('all', null),
                    'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $id
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $id,
                    'flagProtection' => $markProtected,
                    'flagProtectionText' => $markProtectedDesc,
                    'flagObfuscate' => $markObfuscated,
                    'flagObfuscateText' => $markObfuscatedDesc,
                    'flagUnlocked' => $markClosed,
                    'flagUnlockedText' => $markClosedDesc,
                    'flagUnlockedIcon' => Displays::DEFAULT_ICON_LF,
                );
                if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    $list[$i]['param']['enableDisplayFlagObfuscate'] = true;
                    $list[$i]['param']['flagObfuscate'] = $markObfuscated;
                    $list[$i]['param']['flagObfuscateText'] = $markObfuscatedDesc;
                }

                // Marquer comme vu.
                $listOkConversation[$id] = true;
                $i++;
            }
        }
        unset($listInstances, $id, $objectInstance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
        unset($list);
    }


    /**
     * Affichage de la création d'une nouvelle conversation.
     */
    private function _displayCreateConversation(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationsAddTitle', $this->MODULE_REGISTERED_ICONS[1]);

        // Si autorisé à créer une conversation.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteConversation'))) {
            ?>

            <div class="text">
                <form enctype="multipart/form-data" method="post"
                      action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[1]
                          . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                          . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                          . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=0'; ?>">
                    <?php $this->_echoTraduction('::sylabe:module:messenger:create:title'); ?><br/>
                    <input type="text"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_NAME; ?>"
                           size="80"/><br/>
                    <br/>
                    <input type="checkbox"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_CLOSED; ?>"
                           value="y"<?php if ($this->_configuration->getOptionUntyped('defaultObfuscateLinks')) echo ' checked'; ?> />
                    <?php $this->_echoTraduction('::sylabe:module:messenger:create:closedConversation'); ?><br/>
                    <input type="checkbox"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_PROTECTED; ?>"
                           value="y"<?php if ($this->_configuration->getOptionUntyped('defaultObfuscateLinks')) echo ' checked'; ?> />
                    <?php $this->_echoTraduction('::sylabe:module:messenger:create:defaultProtectedConversation'); ?>
                    <br/>
                    <?php if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) { ?>
                        <input type="checkbox"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_CREATE_CONVERSATION_OBFUSCATE_LINKS; ?>"
                               value="y"<?php if ($this->_configuration->getOptionUntyped('defaultObfuscateLinks')) echo ' checked'; ?> />
                        <?php $this->_echoTraduction('::sylabe:module:messenger:create:defaultProtectedConversationLinks'); ?>
                        <br/>
                    <?php } ?>
                    <br/>
                    <input type="submit"
                           value="<?php $this->_echoTraduction('::sylabe:module:messenger:create:newConversation') ?>"/>
                </form>
            </div>
            <?php
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'error',
            );
            echo $this->_display->getDisplayInformation(':::err_NotPermit', $param);
        }
    }


    /**
     * Affichage d'une conversation.
     */
    private function _displayConversation(): void
    {
        $list = array();

        // Détection de l'état de fermeture.
        $markClosed = $this->_conversationInstance->getMarkClosed($this->_nebuleInstance->getCurrentEntity());
        $markClosedDesc = '::sylabe:module:messenger:conversationOpened';
        $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
            . '_' . $this->_applicationInstance->getCurrentEntity()
            . '_' . $this->_conversationInstance->getReferenceObjectClosed()
            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        if ($markClosed) {
            $markClosedDesc = '::sylabe:module:messenger:conversationClosed';
            $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        }

        // Détection de l'état de protection.
        $markProtected = $this->_conversationInstance->getMarkProtected($this->_nebuleInstance->getCurrentEntity());
        $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultUnprotected';
        $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
            . '_' . $this->_applicationInstance->getCurrentEntity()
            . '_' . $this->_conversationInstance->getReferenceObjectProtected()
            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        if ($markProtected) {
            $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultProtected';
            $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        }

        // Détection de l'état de dissimulation.
        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            $markObfuscated = $this->_conversationInstance->getMarkObfuscated($this->_nebuleInstance->getCurrentEntity());
            $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultUnobfuscated';
            $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            if ($markObfuscated) {
                $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultObfuscated';
                $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                    . '_' . $this->_applicationInstance->getCurrentEntity()
                    . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }
        }

        // Si pas de droits, pas d'actions possibles.
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_unlocked
        ) {
            $markClosedLink = '';
            $markProtectedLink = '';
            $markObfuscatedLink = '';
        }

        $list[0]['object'] = $this->_conversationInstance;
        $list[0]['param'] = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => true,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'enableDisplayFlagState' => false,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => false,
            'enableDisplayObjectActions' => true,
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => true,
            'enableDisplayJS' => true,
            'objectName' => $this->_conversationInstance->getName('all'),
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'objectRefs' => $this->_conversationInstance->getListFollowersID('self', null),
            'status' => $this->_conversationInstance->getCountMembers('all', null),
            'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation,
            'flagProtection' => $markProtected,
            'flagProtectionText' => $markProtectedDesc,
            'flagProtectionLink' => $markProtectedLink,
            'flagUnlocked' => $markClosed,
            'flagUnlockedText' => $markClosedDesc,
            'flagUnlockedIcon' => Displays::DEFAULT_ICON_LF,
            'flagUnlockedLink' => $markClosedLink,
        );
        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            $list[0]['param']['enableDisplayFlagObfuscate'] = true;
            $list[0]['param']['flagObfuscate'] = $markObfuscated;
            $list[0]['param']['flagObfuscateText'] = $markObfuscatedDesc;
            $list[0]['param']['flagObfuscateLink'] = $markObfuscatedLink;
        }

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        // Affiche la création d'un message.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink','permitWriteConversation'))) {
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('dispconversationhead');
        }


        $listID = array();
        $displayAsOpen1 = '';
        $displayAsOpen2 = '';
        if ($this->_extractDisplayAsOpened()) {
            $displayAsOpen1 = '&' . $this->MODULE_REGISTERED_VIEWS[4];
            $displayAsOpen2 = $this->MODULE_REGISTERED_VIEWS[4];
        }

        // Calcul le filtrage social à appliquer et pré-charge la liste de filtrage social.
        $socialClass = 'all';
        if ($this->_conversationInstance->getMarkClosed()
            && !$this->_extractDisplayAsOpened()
        ) {
            // Liste des correspondants.
            $listID = $this->_conversationInstance->getListFollowersID('self', null);

            // Initialise le tri social.
            $socialClass = 'onlist';
            $this->_nebuleInstance->getSocialInstance()->setList($listID, 'onlist');
        }

        // Extrait la liste des conversations.
        $links = array_reverse($this->_conversationInstance->getListMembersLinks($socialClass, $listID));
        unset($listID);

        foreach ($links as $link) {
            $headerLink = substr($link->getSigneValue(), 0, 10);
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('message' . $headerLink, self::COMMAND_SELECT_LINK_SIGN . '=' . $link->getSigneValueAlgo() . $displayAsOpen1);
        }

        // Affichage du bonton de synchro.
        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('dispsyncconversation', $displayAsOpen2);
    }

    /**
     * Affichage en ligne d'une conversation.
     * Fait un tri de la partie demandée et appelle la fonction d'affichage associée.
     *
     * Pour les messages, la partie demandée doit être unique mais elle commence par 'message'.
     * Le mot clé est utilisé dans le code JS pour mettre à jour la bonne partie.
     *
     * @return void
     */
    private function _display_InlineConversation(): void
    {
        $value = $this->_applicationInstance->getDisplayInstance()->getInlineContentID();

        // filtre spécifique pour les messages.
        if (substr($value, 0, 7) == 'message') {
            $value = 'message';
        }

        switch ($value) {
            case 'dispconversationhead':
                $this->_display_InlineCreateMessage();
                break;
            case 'dispsyncconversation':
                $this->_display_InlineSyncConversation();
                break;
            case 'message':
                $this->_display_InlineMessage();
                break;
        }
    }

    /**
     * Affichage de la création d'un nouveau message dans le fil de la conversation.
     *
     * @return void
     */
    private function _display_InlineCreateMessage(): void
    {
        // Si autorisé à créer un message.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink', 'permitWriteConversation'))) {
            ?>

            <div class="textSmall">
                <form
                        enctype="multipart/form-data" method="post"
                        action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_nebuleInstance->getCurrentEntity()
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                            . '#refTitle'; ?>">
                    <input
                            type="submit"
                            class="iconegrossepuce"
                            id="sylabeModuleMessagerieBoutonSubmitMessage"
                            value=" "/>
                    <textarea
                            name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT; ?>"
                            rows="2" cols="80" wrap="soft"
                            maxlength="<?php echo $this->_configuration->getOptionUntyped('sylabeIOReadMaxDataPHP'); ?>"></textarea><br/>
                    <?php if ($this->_configuration->getOptionAsBoolean('permitProtectedObject')) { ?>
                        <input
                                type="checkbox"
                                name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT; ?>"
                                value="y"<?php if ($this->_conversationInstance->getMarkProtected()) echo ' checked'; ?> />
                        <?php $this->_echoTraduction('::sylabe:module:messenger:create:createProtectedMessageShort'); ?>&nbsp;
                    <?php }
                    if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                        ?>

                        <input
                                type="checkbox"
                                name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS; ?>"
                                value="y"<?php if ($this->_conversationInstance->getMarkObfuscated()) echo ' checked'; ?> />
                        <?php $this->_echoTraduction('::sylabe:module:messenger:create:createProtectMessageLinksShort'); ?>
                    <?php } ?>

                </form>
            </div>
            <?php
        }
    }

    /**
     * Affichage en ligne des messages de la conversation.
     *
     * @todo à supprimer
     *
     * L'affichage commence à partir du lien avec la signature correspondant à l'argument Displays::DEFAULT_NEXT_COMMAND.
     * L'affichage s'arrête après self::DEFAULT_MESSAGES_DISPLAY_NUMBER messages affichés.
     * Si il reste des messages à afficher, affiche un bouton pour appeler les suivants
     *   en partant de la signature du dernier lien de message affiché +1.
     */
    private function _display_InlineConversationList(): void
    {
        $listID = array();

        // Calcul le filtrage social à appliquer et pré-charge la liste de filtrage social.
        $socialClass = 'all';
        if ($this->_conversationInstance->getMarkClosed()
            && !$this->_extractDisplayAsOpened()
        ) {
            // Liste des correspondants.
            $listID = $this->_conversationInstance->getListFollowersID('self', null);

            // Initialise le tri social.
            $socialClass = 'onlist';
            $this->_nebuleInstance->getSocialInstance()->setList($listID, 'onlist');
        }

        // Extrait la liste des conversations.
        $links = array_reverse($this->_conversationInstance->getListMembersLinks($socialClass, $listID));
        unset($listID);

        $list = array();
        $i = 0;

        foreach ($links as $link) {
            $messageInstance = $this->_nebuleInstance->convertIdToTypedObjectInstance($link->getParsed()['bl/rl/nid2']);

            // Paramètres de l'objet
            $list[$i]['link'] = $link;
            $list[$i]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => true,
                'enableDisplayFlagObfuscate' => true,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => true,
                'enableDisplayStatus' => true,
                'enableDisplayContent' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
                'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[6]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . self::COMMAND_SELECT_LINK_SIGN . '=' . $link->getSigneValueAlgo(),
            );

            // Gestion de la protection du message.
            if ($messageInstance->getMarkProtected()) {
                $list[$i]['param']['flagProtectionLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $messageInstance->getID()
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT . '=' . $messageInstance->getID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            } else {
                $list[$i]['param']['flagProtectionLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $messageInstance->getID()
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT . '=' . $messageInstance->getID()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }

            $i++;
        }
        unset($links, $link);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
    }

    /**
     * @todo à supprimer
     */
    private function _display_InlineConversationListOld(): void
    {
        // Préparation de la gestion de l'affichage par parties.
        $startLinkSigne = $this->_nebuleInstance->getDisplayNextObject();

        // Liste des correspondants.
        $listID = $this->_conversationInstance->getListFollowersID('self', null);

        // Calcul le filtrage social à appliquer et pré-charge la liste de filtrage social.
        $socialClass = 'all';
        if ($this->_conversationInstance->getMarkClosed()
            && !$this->_extractDisplayAsOpened()
        ) {
            $socialClass = 'onlist';
            $this->_nebuleInstance->getSocialInstance()->setList($listID, 'onlist');
        }

        // Extrait la liste des conversations.
        $links = array_reverse($this->_conversationInstance->getListMembersLinks($socialClass, $listID));


        $list = array();
        $i = 0;

        foreach ($links as $link) {
            // Paramètres de l'objet
            $list[$i]['link'] = $link;
            $list[$i]['param'] = array(
                'enableDisplayColor' => true,
                'enableDisplayIcon' => true,
                'enableDisplayRefs' => false,
                'enableDisplayName' => true,
                'enableDisplayID' => false,
                'enableDisplayFlags' => true,
                'enableDisplayFlagProtection' => true,
                'enableDisplayFlagObfuscate' => true,
                'enableDisplayFlagUnlocked' => false,
                'enableDisplayFlagState' => true,
                'enableDisplayFlagEmotions' => true,
                'enableDisplayStatus' => true,
                'enableDisplayContent' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'enableDisplaySelfHook' => true,
                'enableDisplayTypeHook' => false,
            );
            $i++;
        }

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');


        // Comptage des messages affichés.
        $displayCount = 0;
        $okDisplay = false;
        if ($startLinkSigne == '') {
            $okDisplay = true;
        }
        $displayNext = false;
        $nextLinkSigne = '';

        if (sizeof($links) != 0) {
            $display = $this->_applicationInstance->getDisplayInstance();

            // Si besoin, affiche le message d'information. Uniquement pour le premier affichage des messages.
            if ($okDisplay
                && ($this->_applicationInstance->getCurrentEntity() != $this->_nebuleInstance->getCurrentEntity()
                    || !$this->_unlocked
                )
            ) {
                $param = array(
                    'enableDisplayIcon' => true,
                    'displaySize' => 'medium',
                    'displayRatio' => 'long',
                    'informationType' => 'information',
                );
                echo $this->_display->getDisplayInformation('::sylabe:module:messenger:entityPublicConversationsWarning', $param);
            }

            // Indice de fond paire ou impaire.
            $bg = 1;
            // Pour chaque lien.
            foreach ($links as $count => $link) {
                // Vérifie si la signature de lien est celle de départ de l'affichage.
                if ($link->getSigneValue() == $startLinkSigne) {
                    $okDisplay = true;
                }
                // Enregistre le message suivant à afficher si le compteur d'affichage est dépassé.
                if ($displayNext
                    && $nextLinkSigne == ''
                ) {
                    $nextLinkSigne = $link->getSigneValue();
                }

                // Si l'affichage est permit.
                if ($okDisplay) {
                    // Extrait nom et ID pour affichage.
                    $signer = $link->getParsed()['bs/rs1/eid'];
                    $date = $link->getDate();
                    $object = $link->getParsed()['bl/rl/nid2'];
                    $objectInstance = $this->_nebuleInstance->newObject($object);
                    $name = $objectInstance->getFullName('all');            // Ne marche pas pour les noms protégés !!!???
                    $protected = $objectInstance->getMarkProtected();
                    $obfuscated = $link->getObfuscated();

                    // Détermine si l'entité est marqué comme correspondant de la conversation.
                    $signerIsFollower = false;
                    foreach ($listID as $id) {
                        if ($id == $signer) {
                            $signerIsFollower = true;
                            break;
                        }
                    }
                    ?>

                    <div class="sylabeModuleMessagerieTextList<?php if ($signer == $this->_nebuleInstance->getCurrentEntity()) echo 'Self';
                    echo $bg; ?>">
                        <div class="sylabeModuleMessagerieEtatMessageDissimulationO<?php if ($obfuscated) echo 'n'; else echo 'ff'; ?>">
                            <p class="sylabeModuleMessagerieFromToMessage">
                                <?php
                                if ($signer == $this->_nebuleInstance->getCurrentEntity()) {
                                    $this->_echoTraduction('::sylabe:module:messenger:myself');
                                } else {
                                    $display->displayInlineObjectColorIconName($signer);
                                }
                                ?>

                            </p>
                            <?php
                            if (!$signerIsFollower
                                && $signer != $this->_nebuleInstance->getCurrentEntity()
                                && $this->_unlocked
                            ) {
                                ?>

                                <p class="sylabeModuleMessagerieFromToAction">
                                    <?php
                                    // Ajouter l'entité comme destinataire de la conversation.
                                    $display->displayHypertextLink(
                                        $display->convertInlineIconFace(Display::DEFAULT_ICON_ADDENT)
                                        . ' ' . $this->_traduction('::sylabe:module:messenger:addAsFollower'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_'
                                        . $signer . '_' . $this->_conversation . '_' . $this->_hashConversation
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                        . '#refTitle');
                                    ?>

                                </p>
                                <?php
                            }
                            ?>

                            <p class="sylabeModuleMessagerieDateMessage">
                                <?php $display->displayDate($date);
                                echo "\n"; ?>
                            </p>
                        </div>
                        <div class="sylabeModuleMessagerieEtatMessageProtectionO<?php if ($protected) echo 'n'; else echo 'ff'; ?>">
                            <div class="sylabeModuleMessagerieContenu">
                                <p class="sylabeModuleMessagerieTitleMessage<?php if ($name != $object) echo 'Name'; ?>">
                                    <?php
                                    if ($name != $object) {
                                        $display->displayInlineObjectColorIconName($object);
                                    } else {
                                        $display->displayInlineObjectColorIcon($object);
                                    }
                                    ?>

                                </p>
                                <div class="sylabeModuleMessagerieContenuObjet">
                                    <?php $display->displayObjectContentHalf($objectInstance, false) ?>

                                </div>
                            </div>
                        </div>
                        <div class="sylabeModuleMessagerieEtatMessage">
                            <div class="sylabeModuleMessagerieEtatMessageBoutonProtectionO<?php if ($protected) echo 'n'; else echo 'ff'; ?>">
                                <?php $display->displayInlineIconFace('DEFAULT_ICON_LK'); ?>
                            </div>
                            <div class="sylabeModuleMessagerieEtatMessageBoutonDissimulationO<?php if ($obfuscated) echo 'n'; else echo 'ff'; ?>">
                                <?php $display->displayInlineIconFace('DEFAULT_ICON_LC'); ?>
                            </div>
                        </div>
                        <div class="sylabeModuleMessagerieActionsMessage" id="actionsMessageAfter">
                            <?php
                            if ($signer != $this->_nebuleInstance->getCurrentEntity() && $this->_unlocked) {
                                ?>

                                <p class="sylabeModuleMessagerieAction">
                                    <?php
                                    // Retransmettre à son compte le message.
                                    $display->displayHypertextLink(
                                        $display->convertInlineIconFace('DEFAULT_ICON_LL') . ' ' . $this->_traduction('::sylabe:module:messenger:resendMessage'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                        . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=f_'
                                        . $link->getParsed()['bl/rl/nid1'] . '_' . $object . '_' . $this->_hashConversation
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                        . '#refTitle');
                                    ?>

                                </p>
                                <?php
                            }
                            if ($this->_unlocked) {
                                ?>

                                <p class="sylabeModuleMessagerieAction">
                                    <?php
                                    // Masquer le message.
                                    $display->displayHypertextLink(
                                        $display->convertInlineIconFace('DEFAULT_ICON_LX') . ' ' . $this->_traduction('::sylabe:module:messenger:hideMessage'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                        . '&' . Actions::DEFAULT_COMMAND_ACTION_REMOVE_FROM_CONVERSATION . '=' . $this->_conversation
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                        . '#refTitle');
                                    ?>

                                </p>
                                <?php
                            }
                            if ($this->_configuration->getOptionUntyped('sylabeModuleMessageriePermitSubReply') && $this->_unlocked) {
                                ?>

                                <p class="sylabeModuleMessagerieAction">
                                    <?php
                                    // Répondre au message.
                                    $display->displayHypertextLink(
                                        $display->convertInlineIconFace('DEFAULT_ICON_LLL') . ' ' . $this->_traduction('::sylabe:module:messenger:replyMessage'),
                                        '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object
                                        . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue()
                                        . '#refTitle');
                                    ?>

                                </p>
                                <?php
                            }
                            ?>

                        </div>
                        <?php
                        unset($signer, $date, $object, $objectInstance, $name);
                        ?>

                    </div>
                    <?php
                    // Actualise le compteur d'affichage.
                    $displayCount++;
                    if ($displayCount >= self::DEFAULT_MESSAGES_DISPLAY_NUMBER) {
                        $okDisplay = false;
                        $displayNext = true;
                    }
                }
                // Permutation de l'indice de fond.
                $bg = 3 - $bg;
            }

            // Affiche au besoin le bouton pour afficher les objets suivants.
            if ($displayNext && $nextLinkSigne != '') {
                $url = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $display->getCurrentDisplayView()
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . Displays::DEFAULT_INLINE_COMMAND . '&' . Displays::DEFAULT_INLINE_CONTENT_COMMAND . '=dispconversationlist'
                    . '&' . Displays::DEFAULT_NEXT_COMMAND . '=' . $nextLinkSigne;
                $display->displayButtonNextObject($nextLinkSigne, $url, $this->_applicationInstance->getTraductionInstance()->getTraduction('::seeMore'));
            }
            unset($link, $bg, $display);
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'information',
            );
            echo $this->_display->getDisplayInformation('::sylabe:module:messenger:noMessage', $param);
        }
        unset($links);
    }

    /**
     * Partie qui fait la synchronisation de la conversation.
     *
     * @return void
     */
    private function _display_InlineSyncConversation(): void
    {
        // A faire...
    }


    /**
     * Affichage d'un message en particulier.
     *
     * @return void
     */
    private function _displayMessage(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:messenger:messageDispTitle', $this->MODULE_REGISTERED_ICONS[4]);

        // Extrait le lien de message à afficher.
        $linkid = $this->_extractLinkSign();

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('message', self::COMMAND_SELECT_LINK_SIGN . '=' . $linkid);
    }

    /**
     * Affichage en ligne d'un message.
     *
     * @return void
     */
    private function _display_InlineMessage(): void
    {
        $linkArg = $this->_extractLink();

        if ($linkArg == '') {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'info',
            );
            echo $this->_display->getDisplayInformation('::EmptyList', $param);

            return;
        }

        $link = $this->_nebuleInstance->newLink($linkArg);

        // Vérifie que le lien est structurellement valide. Vérifie que les champs sont non nuls.
        if (!$link->getValidStructure()
            || $link->getParsed()['bl/rl/nid1'] == ''
            || $link->getParsed()['bl/rl/nid1'] == '0'
            || $link->getParsed()['bl/rl/nid2'] == ''
            || $link->getParsed()['bl/rl/nid2'] == '0'
            || $link->getParsed()['bl/rl/nid3'] == ''
            || $link->getParsed()['bl/rl/nid3'] == '0'
        ) {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'error',
            );
            echo $this->_display->getDisplayInformation(':::display:link:errorInvalid', $param);

            return;
        }

        $messageInstance = $this->_nebuleInstance->convertIdToTypedObjectInstance($link->getParsed()['bl/rl/nid2']);

        // Paramètres de l'objet
        $list[0]['link'] = $link;
        $list[0]['param'] = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'enableDisplayFlagObfuscate' => true,
            'enableDisplayFlagUnlocked' => false,
            'enableDisplayFlagState' => true,
            'enableDisplayFlagEmotions' => true,
            'enableDisplayStatus' => true,
            'enableDisplayContent' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => false,
        );

        // Modifie le lien html en fonction de la vue.
        if ($this->_applicationInstance->getDisplayInstance()->getCurrentDisplayView() == $this->MODULE_REGISTERED_VIEWS[6]) {
            $list[0]['param']['link2Object'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . nebule::COMMAND_SELECT_OBJECT
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . ModuleObjects::DEFAULT_COMMAND_ACTION_DISPLAY_MODULE
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $messageInstance->getID();
        } else {
            $list[0]['param']['link2Object'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[6]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . self::COMMAND_SELECT_LINK_SIGN . '=' . $link->getSigneValueAlgo();
        }

        // Gestion de la protection du message.
        if ($messageInstance->getMarkProtected()) {
            $list[0]['param']['flagProtectionLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[6]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $messageInstance->getID()
                . '&' . Actions::DEFAULT_COMMAND_ACTION_UNPROTECT_OBJECT . '=' . $messageInstance->getID()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        } else {
            $list[0]['param']['flagProtectionLink'] = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[6]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $messageInstance->getID()
                . '&' . Actions::DEFAULT_COMMAND_ACTION_PROTECT_OBJECT . '=' . $messageInstance->getID()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        }

        // Détermine la validité du lien. @todo à vérifier !!!
        $link->getValid();
        //if ( $link->getValid() )
        if ($link->getSigned()) {
            $list[0]['param']['flagState'] = 'o';
        } else {
            $list[0]['param']['flagState'] = 'e';
        }

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
    }


    /**
     * Affichage de la création d'un nouveau message.
     *
     * @return void
     */
    private function _displayCreateMessage(): void
    {
        // Affiche le titre.
        $this->_applicationInstance->getDisplayInstance()->displayDivTextTitleH2(
            $this->MODULE_REGISTERED_ICONS[4],
            '::sylabe:module:messenger:messageAddTitle',
            '',
            '');

        // Si autorisé à créer un message.
        if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteObject', 'permitWriteLink'))
            && $this->_configuration->getOptionUntyped('sylabePermitUploadObject') // FIXME
        ) {
            ?>

            <div class="text">
                <form enctype="multipart/form-data" method="post"
                      action="<?php echo '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                          . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                          . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                          . '&' . Actions::DEFAULT_COMMAND_ACTION_CREATE_MESSAGE
                          . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                          . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                          . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(); ?>">
                    <?php $this->_echoTraduction('::sylabe:module:messenger:create:title'); ?><br/>
                    <input type="text"
                           name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_NAME; ?>"
                           size="80"/><br/>
                    <?php $this->_echoTraduction('::sylabe:module:messenger:create:content'); ?><br/>
                    <textarea
                            name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT; ?>"
                            rows="12" cols="80" wrap="soft"
                            maxlength="<?php echo $this->_configuration->getOptionUntyped('sylabeIOReadMaxDataPHP'); ?>"></textarea><br/>
                    <?php if ($this->_configuration->getOptionAsBoolean('permitProtectedObject')) { ?>
                        <input type="checkbox"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_PROTECT; ?>"
                               value="y"<?php if ($this->_conversationInstance->getMarkProtected()) echo ' checked'; ?> />
                        <?php $this->_echoTraduction('::sylabe:module:messenger:create:createProtectedMessage'); ?><br/>
                    <?php }
                    if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                        ?>

                        <input type="checkbox"
                               name="<?php echo Actions::DEFAULT_COMMAND_ACTION_UPLOAD_TEXT_OBFUSCATE_LINKS; ?>"
                               value="y"<?php if ($this->_conversationInstance->getMarkObfuscated()) echo ' checked'; ?> />
                        <?php $this->_echoTraduction('::sylabe:module:messenger:create:createProtectMessageLinks'); ?>
                    <?php } ?>

                    <input type="submit"
                           value="<?php $this->_echoTraduction('::sylabe:module:messenger:create:newMessage') ?>"/>
                </form>
            </div>
            <?php
            //$this->_applicationInstance->getDisplayInstance()->displayMessageInformation($this->_traduction('::sylabe:module:messenger:MaxTextSize').' '.$this->_configuration->getOption('sylabeIOReadMaxDataPHP').'o');
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'information',
            );
            echo $this->_display->getDisplayInformation('::sylabe:module:messenger:MaxTextSize', $param, $this->_configuration->getOptionUntyped('sylabeIOReadMaxDataPHP'));
        } else {
            $param = array(
                'enableDisplayIcon' => true,
                'displaySize' => 'medium',
                'displayRatio' => 'long',
                'informationType' => 'error',
            );
            echo $this->_display->getDisplayInformation(':::err_NotPermit', $param);
        }
    }


    /**
     * Affichage des destinataires d'un message.
     *
     * @return void
     */
    private function _displayFollowers(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationDestEntities', $this->MODULE_REGISTERED_ICONS[5]);

        $list = array();

        // Détection de l'état de fermeture.
        $markClosed = $this->_conversationInstance->getMarkClosed($this->_nebuleInstance->getCurrentEntity());
        $markClosedDesc = '::sylabe:module:messenger:conversationOpened';
        $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
            . '_' . $this->_applicationInstance->getCurrentEntity()
            . '_' . $this->_conversationInstance->getReferenceObjectClosed()
            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        if ($markClosed) {
            $markClosedDesc = '::sylabe:module:messenger:conversationClosed';
            $markClosedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectClosed()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        }

        // Détection de l'état de protection.
        $markProtected = $this->_conversationInstance->getMarkProtected($this->_nebuleInstance->getCurrentEntity());
        $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultUnprotected';
        $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
            . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
            . '_' . $this->_applicationInstance->getCurrentEntity()
            . '_' . $this->_conversationInstance->getReferenceObjectProtected()
            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        if ($markProtected) {
            $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultProtected';
            $markProtectedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectProtected()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
        }

        // Détection de l'état de dissimulation.
        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            $markObfuscated = $this->_conversationInstance->getMarkObfuscated($this->_nebuleInstance->getCurrentEntity());
            $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultUnobfuscated';
            $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $this->_conversation
                . '_' . $this->_applicationInstance->getCurrentEntity()
                . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            if ($markObfuscated) {
                $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultObfuscated';
                $markObfuscatedLink = '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                    . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation
                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $this->_conversation
                    . '_' . $this->_applicationInstance->getCurrentEntity()
                    . '_' . $this->_conversationInstance->getReferenceObjectObfuscated()
                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue();
            }
        }

        // Si pas de droits, pas d'actions possibles.
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_unlocked
        ) {
            $markClosedLink = '';
            $markProtectedLink = '';
            $markObfuscatedLink = '';
        }

        $list[0]['object'] = $this->_conversationInstance;
        $list[0]['param'] = array(
            'enableDisplayColor' => true,
            'enableDisplayIcon' => true,
            'enableDisplayRefs' => false,
            'enableDisplayName' => true,
            'enableDisplayID' => false,
            'enableDisplayFlags' => true,
            'enableDisplayFlagProtection' => true,
            'enableDisplayFlagObfuscate' => false,
            'enableDisplayFlagUnlocked' => true,
            'enableDisplayFlagState' => false,
            'enableDisplayFlagEmotions' => false,
            'enableDisplayStatus' => false,
            'enableDisplayContent' => false,
            'enableDisplayObjectActions' => true,
            'enableDisplaySelfHook' => true,
            'enableDisplayTypeHook' => true,
            'enableDisplayJS' => true,
            'objectName' => $this->_conversationInstance->getName('all'),
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $this->_conversation,
            'flagProtection' => $markProtected,
            'flagProtectionText' => $markProtectedDesc,
            'flagProtectionLink' => $markProtectedLink,
            'flagUnlocked' => $markClosed,
            'flagUnlockedText' => $markClosedDesc,
            'flagUnlockedIcon' => Displays::DEFAULT_ICON_LF,
            'flagUnlockedLink' => $markClosedLink,
        );
        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            $list[0]['param']['enableDisplayFlagObfuscate'] = true;
            $list[0]['param']['flagObfuscate'] = $markObfuscated;
            $list[0]['param']['flagObfuscateText'] = $markObfuscatedDesc;
            $list[0]['param']['flagObfuscateLink'] = $markObfuscatedLink;
        }

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listmyfollowers');

        if ($this->_extractDisplayListAll()) {
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listotherfollowers', $this->MODULE_REGISTERED_VIEWS[9]);
        } else {
            $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listotherfollowers');
        }
    }

    /**
     * Affichage en ligne des destinataires d'un message.
     *
     * @return void
     */
    private function _display_InlineFollowers(): void
    {
        switch ($this->_applicationInstance->getDisplayInstance()->getInlineContentID()) {
            case 'listmyfollowers':
                $this->_display_InlineListFollowers();
                break;
            case 'listotherfollowers':
                $this->_display_InlineAddFollowers();
                break;
        }
    }

    /**
     * Affichage en ligne des destinataires d'un message que j'ai ajouté.
     * Ils peuvent voir les nouveaux messages protégés et/ou dissimulés.
     * Et je vois leurs messages si je ferme la conversation.
     *
     * @return void
     */
    private function _display_InlineListFollowers(): void
    {
        $listID = $this->_conversationInstance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION);
        $listOkEntities = array();

        $instance = null;
        $list = array();
        $i = 0;

        foreach ($listID as $id) {
            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplaySelfHook' => false,
                    'enableDisplayTypeHook' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                    'objectRefs' => $this->_traduction('::sylabe:module:messenger:conversationDestCreator'),
                );

                // Marque comme vu.
                $listOkEntities[$id] = true;
                $i++;
            }
        }

        // Extrait la liste des destinataires de la conversation que j'ai ajouté.
        $listID = array_reverse($this->_conversationInstance->getListFollowersID('all', null));

        // Prépare l'affichage des destinataires déjà ajoutés par d'autres entités.
        $instance = null;
        //$list = array();
        $listOkEntities = array(); // Permet à une entité d'être créatrice et suiveuse.
        //$i = 0;

        foreach ($listID as $id) {
            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                // Recherche si je l'ai ajouté à la conversation.
                $addedBy = $this->_conversationInstance->getListFollowerAddedByID($id, 'all', null);
                $addedByMe = false;
                foreach ($addedBy as $entityID) {
                    if ($entityID == $this->_nebuleInstance->getCurrentEntity()) {
                        $addedByMe = true;
                        break;
                    }
                }

                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplaySelfHook' => false,
                    'enableDisplayTypeHook' => false,
                    'enableDisplayJS' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'selfHookName' => '::module:messagerie:dest:followers:remove',
                    'objectRefs' => $addedBy,
                );
                if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink'))) {
                    $list[$i]['param']['enableDisplaySelfHook'] = true;
                    if ($addedByMe) {
                        $list[$i]['param']['selfHookList'][0] = array(
                            'name' => '::sylabe:module:messenger:removeAsFollower',
                            'icon' => Displays::DEFAULT_ICON_LX,
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $id
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                        );
                        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) // @todo à vérifier si le lien dissimulé est bien pris en compte...
                        {
                            $list[$i]['param']['selfHookList'][1] = array(
                                'name' => '::sylabe:module:messenger:hideAsFollower',
                                'icon' => Displays::DEFAULT_ICON_LC,
                                'desc' => '',
                                'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=x_' . $id
                                    . '_' . $this->_conversation
                                    . '_' . $this->_hashConversationFollow
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK2 . '=l_' . $id
                                    . '_' . $this->_conversation
                                    . '_' . $this->_hashConversationFollow
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK2_OBFUSCATE
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                            );
                        }
                    } else {
                        $list[$i]['param']['selfHookList'][0] = array(
                            'name' => '::sylabe:module:messenger:addAsFollower',
                            'icon' => $this->MODULE_REGISTERED_ICONS[3],
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                        );
                        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                            $list[$i]['param']['selfHookList'][1] = array(
                                'name' => '::sylabe:module:messenger:addAsHiddenFollower',
                                'icon' => Displays::DEFAULT_ICON_LC,
                                'desc' => '',
                                'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                    . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                    . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                    . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                                    . '_' . $this->_conversation
                                    . '_' . $this->_hashConversationFollow
                                    . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE
                                    . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                            );
                        }
                    }
                }

                // Marque comme vu.
                $listOkEntities[$id] = true;
                $i++;
            }
        }

        echo $this->_display->getDisplayObjectsList($list, 'medium');
        unset($list, $listID);
    }

    /**
     * Affichage en ligne des destinataires que je peux ajouter à la conversation et qui suivent déjà la conversation.
     * Une fois ajouté, ils pourront voir les nouveaux messages protégés et/ou dissimulés.
     * Et je verrais leurs messages si je ferme la conversation.
     *
     * @return void
     */
    private function _display_InlineAddFollowers(): void
    {
        // Vérifie que une entité est connectée.
        if (!$this->_configuration->getOptionAsBoolean('permitWrite')
            || !$this->_configuration->getOptionAsBoolean('permitWriteLink')
            || !$this->_unlocked) {
            return;
        }
        $listOkEntities = array();

        // Liste des entités à ne pas afficher.
        if (!$this->_extractDisplayListAll()) {
            $listOkEntities = $this->_nebuleInstance->getSpecialEntities();
        }

        // Retire les entités qui se sont déjà ajoutées.
        $listID = $this->_conversationInstance->getListFollowersID('all', null);
        foreach ($listID as $id) {
            $listOkEntities[$id] = true;
        }

        // Prépare l'affichage des destinataires déjà ajoutés par d'autres entités.
        $instance = null;
        $list = array();
        $i = 0;

        foreach ($listID as $id) {
            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => true,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'enableDisplaySelfHook' => true,
                    'enableDisplayTypeHook' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                );
                if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink'))) {
                    $list[$i]['param']['selfHookList'][0] = array(
                        'name' => '::sylabe:module:messenger:addAsFollower',
                        'icon' => $this->MODULE_REGISTERED_ICONS[3],
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                            . '_' . $this->_conversation
                            . '_' . $this->_hashConversationFollow
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                    );
                    if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                        $list[$i]['param']['selfHookList'][1] = array(
                            'name' => '::sylabe:module:messenger:addAsHiddenFollower',
                            'icon' => Displays::DEFAULT_ICON_LC,
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                        );
                    }
                }

                // Marque comme vu.
                $listOkEntities[$id] = true;
                $i++;
            }
        }

        // Affichage
        if (sizeof($list) != 0) {
            echo "<div class=\"sequence\"></div>\n";

            // Titre.
            echo $this->_display->getDisplayTitle('::sylabe:module:messenger:alreadyFollower', $this->MODULE_REGISTERED_ICONS[3]);

            echo $this->_display->getDisplayObjectsList($list, 'medium');
        }
        unset($list, $listID);


        // Extrait la liste des destinataires de la conversation.
        $listID = array_reverse($this->_conversationInstance->getListMembersID('all', null));

        //Prépare l'affichage.
        foreach ($listID as $id) {
            $listOkEntities[$id] = true;
        }

        // Liste toutes les autres entités.
        $hashType = $this->_nebuleInstance->getCryptoInstance()->hash('nebule/objet/type');
        $hashEntity = $this->_nebuleInstance->getCryptoInstance()->hash('application/x-pem-file');
        $hashEntityObject = $this->_nebuleInstance->newObject($hashEntity);
        $links = $hashEntityObject->getLinksOnFields(
            '',
            '',
            'l',
            '',
            $hashEntity,
            $hashType);

        // Prépare l'affichage.
        $list = array();
        $i = 0;

        foreach ($links as $link) {
            $id = $link->getParsed()['bl/rl/nid1'];

            $instance = $this->_nebuleInstance->newEntity($id);
            if (!isset($listOkEntities[$id])
                && $instance->getType('all') == Entity::ENTITY_TYPE
                && $instance->getIsPublicKey()
            ) {
                $list[$i]['object'] = $instance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => false,
                    'enableDisplayName' => true,
                    'enableDisplayID' => true,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => false,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => false,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => true,
                    'enableDisplayContent' => false,
                    'enableDisplaySelfHook' => true,
                    'enableDisplayTypeHook' => false,
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'enableDisplayJS' => false,
                    'selfHookName' => '::module:messagerie:dest:followers:add',
                );
                if ($this->_configuration->checkBooleanOptions(array('unlocked', 'permitWrite', 'permitWriteLink'))) {
                    $list[$i]['param']['selfHookList'][0] = array(
                        'name' => '::sylabe:module:messenger:addAsFollower',
                        'icon' => $this->MODULE_REGISTERED_ICONS[3],
                        'desc' => '',
                        'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                            . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                            . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                            . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                            . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                            . '_' . $this->_conversation
                            . '_' . $this->_hashConversationFollow
                            . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                    );
                    if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                        $list[$i]['param']['selfHookList'][1] = array(
                            'name' => '::sylabe:module:messenger:addAsHiddenFollower',
                            'icon' => Displays::DEFAULT_ICON_LC,
                            'desc' => '',
                            'link' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                                . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[7]
                                . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                                . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $this->_conversation
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1 . '=l_' . $id
                                . '_' . $this->_conversation
                                . '_' . $this->_hashConversationFollow
                                . '&' . Actions::DEFAULT_COMMAND_ACTION_SIGN_LINK1_OBFUSCATE
                                . $this->_nebuleInstance->getTicketingInstance()->getActionTicketValue(),
                        );
                    }
                }

                // Marque comme vu.
                $listOkEntities[$id] = true;
                $i++;
            }
        }
        unset($instance, $id);

        // Affichage
        if (sizeof($list) != 0) {
            echo "<div class=\"sequence\"></div>\n";

            // Titre.
            echo $this->_display->getDisplayTitle('::sylabe:module:messenger:addAsFollower', $this->MODULE_REGISTERED_ICONS[3]);

            echo $this->_display->getDisplayObjectsList($list, 'medium');
        }
        unset($list, $listID);
    }


    /**
     * Affichage l'aide.
     *
     * @return void
     */
    private function _displayHelp(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:messenger:AppTitle1', $this->MODULE_LOGO);

        // Aide traduite.
        $param = array(
            'enableDisplayIcon' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'long',
            'informationType' => 'information',
        );
        echo $this->_display->getDisplayInformation('::sylabe:module:messenger:conversationHelp', $param);

        $list = array();

        // Affichage de la capacité de protection des objets.
        $list[0]['param'] = array(
            'enableDisplayIcon' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        if ($this->_configuration->getOptionAsBoolean('permitProtectedObject')) {
            $list[0]['param']['informationType'] = 'ok';
            $list[0]['information'] = '::sylabe:module:messenger:protectingEnabled';
        } else {
            $list[0]['param']['informationType'] = 'error';
            $list[0]['information'] = '::sylabe:module:messenger:protectingDisabled';
        }

        // Affichage de la capacité de dissimulation des liens.
        $list[1]['param'] = array(
            'enableDisplayIcon' => true,
            'displaySize' => 'medium',
            'displayRatio' => 'short',
        );
        if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
            $list[1]['param']['informationType'] = 'ok';
            $list[1]['information'] = '::sylabe:module:messenger:obfuscatingEnabled';
        } else {
            $list[1]['param']['informationType'] = 'error';
            $list[1]['information'] = '::sylabe:module:messenger:obfuscatingDisabled';
        }

        echo $this->_display->getDisplayObjectsList($list, 'medium', true);
    }


    /**
     * Affichage de la liste des conversations de l'entité.
     *
     * @return void
     */
    private function _displayListAll(): void
    {
        // Titre.
        echo $this->_display->getDisplayTitle('::sylabe:module:messenger:conversationsListAllObjects', $this->MODULE_LOGO);

        $this->_applicationInstance->getDisplayInstance()->registerInlineContentID('listallconversations');
    }

    /**
     * Affichage de la liste des conversations de l'entité, en ligne.
     *
     * @return void
     */
    private function _display_InlineListAll(): void
    {
        // Extrait la liste des objetsconversations.
        $links = array_reverse($this->_nebuleInstance->getListLinksByType(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION, '', 'all'));

        $list = array();
        $i = 0;
        $object = null;
        $objectInstance = null;
        $listOkConversation = array();

        // Pour chaque lien.
        foreach ($links as $link) {
            // Extrait nom et ID pour affichage.
            $object = $link->getParsed()['bl/rl/nid1'];

            if (!isset($listOkConversation[$object])) {
                $objectInstance = $this->_nebuleInstance->newConversation($object);

                // Détection de l'état de fermeture.
                $markClosed = $objectInstance->getMarkClosed($this->_nebuleInstance->getCurrentEntity());
                $markClosedDesc = '::sylabe:module:messenger:conversationOpened';
                if ($markClosed) {
                    $markClosedDesc = '::sylabe:module:messenger:conversationClosed';
                }

                // Détection de l'état de protection.
                $markProtected = $objectInstance->getMarkProtected($this->_nebuleInstance->getCurrentEntity());
                $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultUnprotected';
                if ($markProtected) {
                    $markProtectedDesc = '::sylabe:module:messenger:conversation:defaultProtected';
                }

                // Détection de l'état de dissimulation.
                $markObfuscated = $objectInstance->getMarkObfuscated($this->_nebuleInstance->getCurrentEntity());
                $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultUnobfuscated';
                if ($markObfuscated) {
                    $markObfuscatedDesc = '::sylabe:module:messenger:conversation:defaultObfuscated';
                }

                $list[$i]['object'] = $objectInstance;
                $list[$i]['param'] = array(
                    'enableDisplayColor' => true,
                    'enableDisplayIcon' => true,
                    'enableDisplayRefs' => true,
                    'enableDisplayName' => true,
                    'enableDisplayID' => false,
                    'enableDisplayFlags' => true,
                    'enableDisplayFlagProtection' => true,
                    'enableDisplayFlagObfuscate' => false,
                    'enableDisplayFlagUnlocked' => true,
                    'enableDisplayFlagState' => false,
                    'enableDisplayFlagEmotions' => true,
                    'enableDisplayStatus' => false,
                    'enableDisplayContent' => false,
                    'enableDisplayObjectActions' => false,
                    'enableDisplaySelfHook' => false,
                    'enableDisplayTypeHook' => false,
                    'enableDisplayJS' => false,
                    'objectName' => $objectInstance->getName('all'),
                    'displaySize' => 'medium',
                    'displayRatio' => 'short',
                    'objectRefs' => $objectInstance->getPropertySigners(nebule::REFERENCE_NEBULE_OBJET_CONVERSATION),
                    'link2Object' => '?' . Displays::DEFAULT_DISPLAY_COMMAND_MODE . '=' . $this->MODULE_COMMAND_NAME
                        . '&' . Displays::DEFAULT_DISPLAY_COMMAND_VIEW . '=' . $this->MODULE_REGISTERED_VIEWS[3]
                        . '&' . nebule::COMMAND_SELECT_ENTITY . '=' . $this->_applicationInstance->getCurrentEntity()
                        . '&' . nebule::COMMAND_SELECT_CONVERSATION . '=' . $object
                        . '&' . nebule::COMMAND_SELECT_OBJECT . '=' . $object,
                    'flagProtection' => $markProtected,
                    'flagProtectionText' => $markProtectedDesc,
                    'flagUnlocked' => $markClosed,
                    'flagUnlockedText' => $markClosedDesc,
                    'flagUnlockedIcon' => Displays::DEFAULT_ICON_LF,
                );
                if ($this->_configuration->getOptionAsBoolean('permitObfuscatedLink')) {
                    $list[$i]['param']['enableDisplayFlagObfuscate'] = true;
                    $list[$i]['param']['flagObfuscate'] = $markObfuscated;
                    $list[$i]['param']['flagObfuscateText'] = $markObfuscatedDesc;
                }

                // Marquer comme vu.
                $listOkConversation[$object] = true;
                $i++;
            }
        }
        unset($link, $object, $objectInstance);

        // Affichage.
        echo $this->_display->getDisplayObjectsList($list, 'medium');
        unset($links);
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::sylabe:module:messenger:ModuleName'] = 'Module de messagerie';
        $this->_table['en-en']['::sylabe:module:messenger:ModuleName'] = 'Messenger module';
        $this->_table['es-co']['::sylabe:module:messenger:ModuleName'] = 'Módulo de messaje';
        $this->_table['fr-fr']['::sylabe:module:messenger:MenuName'] = 'Messagerie';
        $this->_table['en-en']['::sylabe:module:messenger:MenuName'] = 'Messenger';
        $this->_table['es-co']['::sylabe:module:messenger:MenuName'] = 'Messajes';
        $this->_table['fr-fr']['::sylabe:module:messenger:ModuleDescription'] = 'Module de gestion des conversations et messages';
        $this->_table['en-en']['::sylabe:module:messenger:ModuleDescription'] = 'Conversations and messages manage module.';
        $this->_table['es-co']['::sylabe:module:messenger:ModuleDescription'] = 'Módulo de mesajes y .';
        $this->_table['fr-fr']['::sylabe:module:messenger:ModuleHelp'] = 'Ce module permet de gérer et de générer les conversations et les messages.';
        $this->_table['en-en']['::sylabe:module:messenger:ModuleHelp'] = 'This module permit to manage and create conversations and messages.';
        $this->_table['es-co']['::sylabe:module:messenger:ModuleHelp'] = 'This module permit to manage and create conversations and messages.';

        $this->_table['fr-fr']['::sylabe:module:messenger:AppTitle1'] = 'Messagerie';
        $this->_table['en-en']['::sylabe:module:messenger:AppTitle1'] = 'Messenger';
        $this->_table['es-co']['::sylabe:module:messenger:AppTitle1'] = 'Messenger';
        $this->_table['fr-fr']['::sylabe:module:messenger:AppDesc1'] = 'Gestion des messages.';
        $this->_table['en-en']['::sylabe:module:messenger:AppDesc1'] = 'Manage messages.';
        $this->_table['es-co']['::sylabe:module:messenger:AppDesc1'] = 'Manage messages.';

        $this->_table['fr-fr']['::sylabe:module:messenger:conversationOpened'] = 'Conversation ouverte';
        $this->_table['en-en']['::sylabe:module:messenger:conversationOpened'] = 'Opened conversation';
        $this->_table['es-co']['::sylabe:module:messenger:conversationOpened'] = 'Opened conversation';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationSeenAsOpened'] = 'Conversation fermée vue comme ouverte';
        $this->_table['en-en']['::sylabe:module:messenger:conversationSeenAsOpened'] = 'Conversation closed seen as opened';
        $this->_table['es-co']['::sylabe:module:messenger:conversationSeenAsOpened'] = 'Conversation closed seen as opened';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationClosed'] = 'Conversation fermée';
        $this->_table['en-en']['::sylabe:module:messenger:conversationClosed'] = 'Closed conversation';
        $this->_table['es-co']['::sylabe:module:messenger:conversationClosed'] = 'Closed conversation';

        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListTitle'] = 'Les conversations';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListTitle'] = 'Conversations';
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListTitle'] = 'Conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListDesc'] = 'Liste des conversations';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListDesc'] = 'List of conversations';
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListDesc'] = 'List of conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationHelp'] = "Chaque conversation est un espace privilégié avec une ou plusieurs autres entités dans lequel vous échangez des messages.
Les messages peuvent être constitués de tous types d'objets.<br />
Les conversations peuvent être <u>fermées</u> et/ou <u>dissimulées</u>. Les messages peuvent être <u>protégés</u> et/ou <u>dissimulés</u>.<br />
<br />
<b>Conversation ouverte ou fermée :</b><br />
Toute entité peut s'ajouter à une conversation mais si vous définisez la conversation comme fermée vous ne verrez que les entités que vous avez choisi pour la conversation.
Si elle est ouverte vous verrez tout le monde.
La notion de conversation ouverte ou fermée n'est pas un attribut global mais un paramétrage de votre affichage de la conversation.<br />
Une même conversation peut être vu comme ouverte ou fermée par les différentes entités de la conversation.
Et, même si toutes les entités définissent la conversation comme fermée, chaque entité peut ne pas reconnaitre dans la conversation les mêmes destinataires que les autres.<br />
S'inclure dans une conversation suffit à suivre celle-ci.
Les autres participants vous verront apparaitre si ils ont définit la conversation comme ouverte, et vous pourrez intervenir.
Si la conversation est fermée, vous pourrez la suivre mais vos interventions ne seront pas vus.<br />
<br />
<b>Protection des messages :</b><br />
Une conversation fermée n'est pas une conversation privée, pour cela il faut en plus activer la protection des messages de la conversation.<br />
Losrque la protection d'une conversation est activée, tous les nouveaux messages sont par défaut protégés pour les destinataires que vous avez définis.
Les autres entités ne peuvent pas voir le contenu de ces messages, ils sont chiffrés.
La notion de protection des messages n'est pas un attribut global mais un paramétrage de votre façon de les transmettre.<br />
L'activation de la protection n'a pas d'effet sur les anciens messages.<br />
Tous les intervenants ne sont pas obligés d'activer la protection des messages, seuls les messages non protégés seront visibles par tout le monde.<br />
<br />
<b>Dissimulation :</b><br />
Une conversation fermée n'est pas une conversation privée, pour cela il peut être utile en plus activer la dissimulation des messages.
La conversation peut aussi être dissimulée mais ce doit être fait dès le début.
La notion de dissimulation des messages n'est pas un attribut global mais un paramétrage de votre façon de les transmettre.<br />
<i>La dissimulation des conversations et des messages n'est pas encore fonctionnelle...</i>
";
        $this->_table['en-en']['::sylabe:module:messenger:conversationHelp'] = 'All conversations are specials spaces with one or more entities in witch you can exchange messages.<br />
...';
        $this->_table['es-co']['::sylabe:module:messenger:conversationHelp'] = 'All conversations are specials spaces with one or more entities in witch you can exchange messages.<br />
...';
        $this->_table['fr-fr']['::sylabe:module:messenger:myConversationsListTitle'] = 'Mes conversations';
        $this->_table['en-en']['::sylabe:module:messenger:myConversationsListTitle'] = 'My conversations';
        $this->_table['es-co']['::sylabe:module:messenger:myConversationsListTitle'] = 'My conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:myConversationsListDesc'] = 'Liste de mes conversations';
        $this->_table['en-en']['::sylabe:module:messenger:myConversationsListDesc'] = 'List of my conversations';
        $this->_table['es-co']['::sylabe:module:messenger:myConversationsListDesc'] = 'List of my conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:myConversationsListHelp'] = $this->_table['fr-fr']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['en-en']['::sylabe:module:messenger:myConversationsListHelp'] = $this->_table['en-en']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['es-co']['::sylabe:module:messenger:myConversationsListHelp'] = $this->_table['es-co']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListAllTitle'] = 'Toutes les conversations';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListAllTitle'] = 'All conversations';
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListAllTitle'] = 'All conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListAllDesc'] = 'Liste de toutes les conversations';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListAllDesc'] = 'List of all conversations';
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListAllDesc'] = 'List of all conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListAllObjects'] = 'Tous les objets conversations';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListAllObjects'] = "All conversation's objects";
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListAllObjects'] = "All conversation's objects";
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListAllHelp'] = $this->_table['fr-fr']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['en-en']['::sylabe:module:messenger:conversationsListAllHelp'] = $this->_table['en-en']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['es-co']['::sylabe:module:messenger:conversationsListAllHelp'] = $this->_table['es-co']['::sylabe:module:messenger:conversationHelp'];
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationsAddTitle'] = 'Ajouter une conversation';
        $this->_table['en-en']['::sylabe:module:messenger:conversationsAddTitle'] = 'Add a conversation';
        $this->_table['es-co']['::sylabe:module:messenger:conversationsAddTitle'] = 'Add a conversation';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationDispTitle'] = 'Conversation';
        $this->_table['en-en']['::sylabe:module:messenger:conversationDispTitle'] = 'Conversation';
        $this->_table['es-co']['::sylabe:module:messenger:conversationDispTitle'] = 'Conversation';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationDispHelp'] = $this->_table['fr-fr']['::sylabe:module:messenger:conversationsListAllHelp'];
        $this->_table['en-en']['::sylabe:module:messenger:conversationDispHelp'] = $this->_table['en-en']['::sylabe:module:messenger:conversationsListAllHelp'];
        $this->_table['es-co']['::sylabe:module:messenger:conversationDispHelp'] = $this->_table['es-co']['::sylabe:module:messenger:conversationsListAllHelp'];
        $this->_table['fr-fr']['::sylabe:module:messenger:messageAddTitle'] = 'Ajouter un message';
        $this->_table['en-en']['::sylabe:module:messenger:messageAddTitle'] = 'Add a message';
        $this->_table['es-co']['::sylabe:module:messenger:messageAddTitle'] = 'Add a message';
        $this->_table['fr-fr']['::sylabe:module:messenger:messageAddDesc'] = 'Ajouter un message';
        $this->_table['en-en']['::sylabe:module:messenger:messageAddDesc'] = 'Add a message';
        $this->_table['es-co']['::sylabe:module:messenger:messageAddDesc'] = 'Add a message';
        $this->_table['fr-fr']['::sylabe:module:messenger:messageAddHelp'] = 'Ajouter un message';
        $this->_table['en-en']['::sylabe:module:messenger:messageAddHelp'] = 'Add a message';
        $this->_table['es-co']['::sylabe:module:messenger:messageAddHelp'] = 'Add a message';
        $this->_table['fr-fr']['::sylabe:module:messenger:messageDispTitle'] = 'Message';
        $this->_table['en-en']['::sylabe:module:messenger:messageDispTitle'] = 'Message';
        $this->_table['es-co']['::sylabe:module:messenger:messageDispTitle'] = 'Message';
        $this->_table['fr-fr']['::sylabe:module:messenger:messageDispDesc'] = 'Contenu du message';
        $this->_table['en-en']['::sylabe:module:messenger:messageDispDesc'] = 'Content of message';
        $this->_table['es-co']['::sylabe:module:messenger:messageDispDesc'] = 'Content of message';
        $this->_table['fr-fr']['::sylabe:module:messenger:messageDispHelp'] = 'Affiche le contenu du message dans la conversation.';
        $this->_table['en-en']['::sylabe:module:messenger:messageDispHelp'] = 'Display message content into the conversation.';
        $this->_table['es-co']['::sylabe:module:messenger:messageDispHelp'] = 'Display message content into the conversation.';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationDestEntities'] = 'Correspondants';
        $this->_table['en-en']['::sylabe:module:messenger:conversationDestEntities'] = 'Correspondents';
        $this->_table['es-co']['::sylabe:module:messenger:conversationDestEntities'] = 'Correspondents';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversationDestCreator'] = 'Créateur';
        $this->_table['en-en']['::sylabe:module:messenger:conversationDestCreator'] = 'Creator';
        $this->_table['es-co']['::sylabe:module:messenger:conversationDestCreator'] = 'Creator';

        $this->_table['fr-fr']['::sylabe:module:messenger:Conversations'] = 'Les conversations';
        $this->_table['en-en']['::sylabe:module:messenger:Conversations'] = 'The conversations';
        $this->_table['es-co']['::sylabe:module:messenger:Conversations'] = 'The conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:MyConversations'] = 'Mes conversations';
        $this->_table['en-en']['::sylabe:module:messenger:MyConversations'] = 'My conversations';
        $this->_table['es-co']['::sylabe:module:messenger:MyConversations'] = 'My conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:seeCurrentConversation'] = 'Conversation en cours';
        $this->_table['en-en']['::sylabe:module:messenger:seeCurrentConversation'] = 'Current conversation';
        $this->_table['es-co']['::sylabe:module:messenger:seeCurrentConversation'] = 'Current conversation';

        $this->_table['fr-fr']['::sylabe:module:messenger:helpConversation'] = 'Aide sur les conversations.';
        $this->_table['en-en']['::sylabe:module:messenger:helpConversation'] = 'Help about conversations.';
        $this->_table['es-co']['::sylabe:module:messenger:helpConversation'] = 'Help about conversations.';

        $this->_table['fr-fr']['::sylabe:module:messenger:noConversation'] = 'Pas de conversation';
        $this->_table['en-en']['::sylabe:module:messenger:noConversation'] = 'No conversation';
        $this->_table['es-co']['::sylabe:module:messenger:noConversation'] = 'No conversation';
        $this->_table['fr-fr']['::sylabe:module:messenger:noMessage'] = 'Pas de message';
        $this->_table['en-en']['::sylabe:module:messenger:noMessage'] = 'No message';
        $this->_table['es-co']['::sylabe:module:messenger:noMessage'] = 'No message';
        $this->_table['fr-fr']['::sylabe:module:messenger:noFollower'] = 'Pas de correspondant';
        $this->_table['en-en']['::sylabe:module:messenger:noFollower'] = 'No follower';
        $this->_table['es-co']['::sylabe:module:messenger:noFollower'] = 'No follower';

        $this->_table['fr-fr']['::sylabe:module:messenger:TransmitAsMessageTo'] = 'Transmettre cet objet comme message à :';
        $this->_table['en-en']['::sylabe:module:messenger:TransmitAsMessageTo'] = 'Transmit this object as message to :';
        $this->_table['es-co']['::sylabe:module:messenger:TransmitAsMessageTo'] = 'Transmit this object as message to :';

        $this->_table['fr-fr']['::sylabe:module:messenger:TransmitObjectAsMessageOK'] = "La transmission de l'objet est terminée. Le destinataire doit synchroniser son entité pour le recevoir.";
        $this->_table['en-en']['::sylabe:module:messenger:TransmitObjectAsMessageOK'] = 'Transmission of the object is complete. The recipient must synchronize its entity to receiveit.';
        $this->_table['es-co']['::sylabe:module:messenger:TransmitObjectAsMessageOK'] = 'Transmission of the object is complete. The recipient must synchronize its entity to receiveit.';
        $this->_table['fr-fr']['::sylabe:module:messenger:TransmitObjectAsMessageError'] = "La transmission de l'objet a échouée.";
        $this->_table['en-en']['::sylabe:module:messenger:TransmitObjectAsMessageError'] = 'Transmission of the object has failed.';
        $this->_table['es-co']['::sylabe:module:messenger:TransmitObjectAsMessageError'] = 'Transmission of the object has failed.';

        $this->_table['fr-fr']['::sylabe:module:messenger:LockedNoMessage'] = 'Entité verrouillée, action impossible !';
        $this->_table['en-en']['::sylabe:module:messenger:LockedNoMessage'] = 'Entity locked, unable to do this !';
        $this->_table['es-co']['::sylabe:module:messenger:LockedNoMessage'] = 'Entity locked, unable to do this !';

        $this->_table['fr-fr']['::sylabe:module:messenger:From'] = 'De';
        $this->_table['en-en']['::sylabe:module:messenger:From'] = 'From';
        $this->_table['es-co']['::sylabe:module:messenger:From'] = 'From';
        $this->_table['fr-fr']['::sylabe:module:messenger:To'] = 'Pour';
        $this->_table['en-en']['::sylabe:module:messenger:To'] = 'To';
        $this->_table['es-co']['::sylabe:module:messenger:To'] = 'To';
        $this->_table['fr-fr']['::sylabe:module:messenger:myself'] = 'Moi';
        $this->_table['en-en']['::sylabe:module:messenger:myself'] = 'Me';
        $this->_table['es-co']['::sylabe:module:messenger:myself'] = 'Yo';

        $this->_table['fr-fr']['::sylabe:module:messenger:create:newMessage'] = 'Envoyer';
        $this->_table['en-en']['::sylabe:module:messenger:create:newMessage'] = 'Send';
        $this->_table['es-co']['::sylabe:module:messenger:create:newMessage'] = 'Send';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:createProtectedMessage'] = 'Protéger le message.';
        $this->_table['en-en']['::sylabe:module:messenger:create:createProtectedMessage'] = 'Protect the message.';
        $this->_table['es-co']['::sylabe:module:messenger:create:createProtectedMessage'] = 'Protect the message.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:createProtectedMessageShort'] = 'Protéger';
        $this->_table['en-en']['::sylabe:module:messenger:create:createProtectedMessageShort'] = 'Protect';
        $this->_table['es-co']['::sylabe:module:messenger:create:createProtectedMessageShort'] = 'Protect';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:createProtectMessageLinks'] = 'Dissimuler le message.';
        $this->_table['en-en']['::sylabe:module:messenger:create:createProtectMessageLinks'] = 'Hide the message.';
        $this->_table['es-co']['::sylabe:module:messenger:create:createProtectMessageLinks'] = 'Hide the message.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:createProtectMessageLinksShort'] = 'Dissimuler';
        $this->_table['en-en']['::sylabe:module:messenger:create:createProtectMessageLinksShort'] = 'Hide';
        $this->_table['es-co']['::sylabe:module:messenger:create:createProtectMessageLinksShort'] = 'Hide';
        $this->_table['fr-fr']['::sylabe:module:messenger:returnToConversation'] = 'Retourner à la conversation';
        $this->_table['en-en']['::sylabe:module:messenger:returnToConversation'] = 'Return to conversation';
        $this->_table['es-co']['::sylabe:module:messenger:returnToConversation'] = 'Return to conversation';

        $this->_table['fr-fr']['::sylabe:module:messenger:resendMessage'] = 'Retransmettre';
        $this->_table['en-en']['::sylabe:module:messenger:resendMessage'] = 'Resend';
        $this->_table['es-co']['::sylabe:module:messenger:resendMessage'] = 'Resend';
        $this->_table['fr-fr']['::sylabe:module:messenger:hideMessage'] = 'Masquer';
        $this->_table['en-en']['::sylabe:module:messenger:hideMessage'] = 'Hide';
        $this->_table['es-co']['::sylabe:module:messenger:hideMessage'] = 'Hide';
        $this->_table['fr-fr']['::sylabe:module:messenger:replyMessage'] = 'Répondre';
        $this->_table['en-en']['::sylabe:module:messenger:replyMessage'] = 'Reply';
        $this->_table['es-co']['::sylabe:module:messenger:replyMessage'] = 'Reply';
        $this->_table['fr-fr']['::sylabe:module:messenger:addAsFollower'] = 'Ajouter';
        $this->_table['en-en']['::sylabe:module:messenger:addAsFollower'] = 'Add';
        $this->_table['es-co']['::sylabe:module:messenger:addAsFollower'] = 'Add';
        $this->_table['fr-fr']['::sylabe:module:messenger:addAsHiddenFollower'] = 'Ajouter caché';
        $this->_table['en-en']['::sylabe:module:messenger:addAsHiddenFollower'] = 'Hidden add';
        $this->_table['es-co']['::sylabe:module:messenger:addAsHiddenFollower'] = 'Hidden add';
        $this->_table['fr-fr']['::sylabe:module:messenger:alreadyFollower'] = "Déjà ajoutés par d'autres entités";
        $this->_table['en-en']['::sylabe:module:messenger:alreadyFollower'] = 'Already added by others entities';
        $this->_table['es-co']['::sylabe:module:messenger:alreadyFollower'] = 'Already added by others entities';
        $this->_table['fr-fr']['::sylabe:module:messenger:removeAsFollower'] = 'Retirer';
        $this->_table['en-en']['::sylabe:module:messenger:removeAsFollower'] = 'Remove';
        $this->_table['es-co']['::sylabe:module:messenger:removeAsFollower'] = 'Remove';
        $this->_table['fr-fr']['::sylabe:module:messenger:hideAsFollower'] = 'Cacher';
        $this->_table['en-en']['::sylabe:module:messenger:hideAsFollower'] = 'Hide';
        $this->_table['es-co']['::sylabe:module:messenger:hideAsFollower'] = 'Hide';

        $this->_table['fr-fr']['::sylabe:module:messenger:entityConversations'] = 'Conversations de %s';
        $this->_table['en-en']['::sylabe:module:messenger:entityConversations'] = 'Conversations for %s';
        $this->_table['es-co']['::sylabe:module:messenger:entityConversations'] = 'Conversations for %s';
        $this->_table['fr-fr']['::sylabe:module:messenger:entityPublicConversations'] = 'Conversations publics de %s';
        $this->_table['en-en']['::sylabe:module:messenger:entityPublicConversations'] = 'Public conversations for %s';
        $this->_table['es-co']['::sylabe:module:messenger:entityPublicConversations'] = 'Public conversations for %s';
        $this->_table['fr-fr']['::sylabe:module:messenger:entityPublicConversationsWarning'] = 'Les messages protégés ou dissimulés ne sont pas accessibles.';
        $this->_table['en-en']['::sylabe:module:messenger:entityPublicConversationsWarning'] = 'Protected ou hidden messages are not availables.';
        $this->_table['es-co']['::sylabe:module:messenger:entityPublicConversationsWarning'] = 'Protected ou hidden messages are not availables.';

        $this->_table['fr-fr']['::sylabe:module:messenger:Protected'] = 'Message protégé.';
        $this->_table['en-en']['::sylabe:module:messenger:Protected'] = 'Message protected.';
        $this->_table['es-co']['::sylabe:module:messenger:Protected'] = 'Message protected.';
        $this->_table['fr-fr']['::sylabe:module:messenger:Obfuscated'] = 'Message dissimulé.';
        $this->_table['en-en']['::sylabe:module:messenger:Obfuscated'] = 'Message obfuscated.';
        $this->_table['es-co']['::sylabe:module:messenger:Obfuscated'] = 'Message obfuscated.';

        $this->_table['fr-fr']['::sylabe:module:messenger:addAsMyConversation'] = 'Ajouter à mes conversations';
        $this->_table['en-en']['::sylabe:module:messenger:addAsMyConversation'] = 'Add to my conversations';
        $this->_table['es-co']['::sylabe:module:messenger:addAsMyConversation'] = 'Add to my conversations';
        $this->_table['fr-fr']['::sylabe:module:messenger:removeAsMyConversation'] = 'Retirer de mes conversations';
        $this->_table['en-en']['::sylabe:module:messenger:removeAsMyConversation'] = 'Remove from my conversations';
        $this->_table['es-co']['::sylabe:module:messenger:removeAsMyConversation'] = 'Remove from my conversations';

        $this->_table['fr-fr']['::sylabe:module:messenger:SeeConversationAsOpened'] = 'Voir comme ouverte';
        $this->_table['en-en']['::sylabe:module:messenger:SeeConversationAsOpened'] = 'See as opened';
        $this->_table['es-co']['::sylabe:module:messenger:SeeConversationAsOpened'] = 'See as opened';
        $this->_table['fr-fr']['::sylabe:module:messenger:SeeConversationAsClosed'] = 'Voir comme fermée';
        $this->_table['en-en']['::sylabe:module:messenger:SeeConversationAsClosed'] = 'See as closed';
        $this->_table['es-co']['::sylabe:module:messenger:SeeConversationAsClosed'] = 'See as closed';
        $this->_table['fr-fr']['::sylabe:module:messenger:OpenTheConversation'] = 'Rendre ouverte la conversation';
        $this->_table['en-en']['::sylabe:module:messenger:OpenTheConversation'] = 'Make the conversation opened';
        $this->_table['es-co']['::sylabe:module:messenger:OpenTheConversation'] = 'Make the conversation opened';
        $this->_table['fr-fr']['::sylabe:module:messenger:OpeningConversation'] = 'Ouvrir';
        $this->_table['en-en']['::sylabe:module:messenger:OpeningConversation'] = 'Opening';
        $this->_table['es-co']['::sylabe:module:messenger:OpeningConversation'] = 'Opening';
        $this->_table['fr-fr']['::sylabe:module:messenger:CloseTheConversation'] = 'Rendre fermée la conversation';
        $this->_table['en-en']['::sylabe:module:messenger:CloseTheConversation'] = 'Make the conversation closed';
        $this->_table['es-co']['::sylabe:module:messenger:CloseTheConversation'] = 'Make the conversation closed';
        $this->_table['fr-fr']['::sylabe:module:messenger:ClosingConversation'] = 'Fermer';
        $this->_table['en-en']['::sylabe:module:messenger:ClosingConversation'] = 'Closing';
        $this->_table['es-co']['::sylabe:module:messenger:ClosingConversation'] = 'Closing';
        $this->_table['fr-fr']['::sylabe:module:messenger:protectTheConversationMessages'] = 'Protèger les nouveaux messages';
        $this->_table['en-en']['::sylabe:module:messenger:protectTheConversationMessages'] = 'Protect new messages';
        $this->_table['es-co']['::sylabe:module:messenger:protectTheConversationMessages'] = 'Protect new messages';
        $this->_table['fr-fr']['::sylabe:module:messenger:protectingConversationMessages'] = 'Protèger';
        $this->_table['en-en']['::sylabe:module:messenger:protectingConversationMessages'] = 'Protecting';
        $this->_table['es-co']['::sylabe:module:messenger:protectingConversationMessages'] = 'Protecting';
        $this->_table['fr-fr']['::sylabe:module:messenger:unprotectTheConversationMessages'] = 'Ne pas protèger les nouveaux messages';
        $this->_table['en-en']['::sylabe:module:messenger:unprotectTheConversationMessages'] = 'Do not protect new messages';
        $this->_table['es-co']['::sylabe:module:messenger:unprotectTheConversationMessages'] = 'Do not protect new messages';
        $this->_table['fr-fr']['::sylabe:module:messenger:unprotectingConversationMessages'] = 'Déprotèger';
        $this->_table['en-en']['::sylabe:module:messenger:unprotectingConversationMessages'] = 'Unprotecting';
        $this->_table['es-co']['::sylabe:module:messenger:unprotectingConversationMessages'] = 'Unprotecting';
        $this->_table['fr-fr']['::sylabe:module:messenger:obfuscateTheConversationMessages'] = 'Dissimuler les nouveaux messages';
        $this->_table['en-en']['::sylabe:module:messenger:obfuscateTheConversationMessages'] = 'Obfuscate new messages';
        $this->_table['es-co']['::sylabe:module:messenger:obfuscateTheConversationMessages'] = 'Obfuscate new messages';
        $this->_table['fr-fr']['::sylabe:module:messenger:obfuscatingConversationMessages'] = 'Dissimuler';
        $this->_table['en-en']['::sylabe:module:messenger:obfuscatingConversationMessages'] = 'Obfuscating';
        $this->_table['es-co']['::sylabe:module:messenger:obfuscatingConversationMessages'] = 'Obfuscating';
        $this->_table['fr-fr']['::sylabe:module:messenger:unobfuscateTheConversationMessages'] = 'Ne pas dissimuler les nouveaux messages';
        $this->_table['en-en']['::sylabe:module:messenger:unobfuscateTheConversationMessages'] = 'Do not obfuscate new messages';
        $this->_table['es-co']['::sylabe:module:messenger:unobfuscateTheConversationMessages'] = 'Do not obfuscate new messages';
        $this->_table['fr-fr']['::sylabe:module:messenger:unobfuscatingConversationMessages'] = 'Révèler';
        $this->_table['en-en']['::sylabe:module:messenger:unobfuscatingConversationMessages'] = 'Unobfuscating';
        $this->_table['es-co']['::sylabe:module:messenger:unobfuscatingConversationMessages'] = 'Unobfuscating';

        $this->_table['fr-fr']['::sylabe:module:messenger:conversation:defaultProtected'] = 'Par défaut les nouveaux messages de cette conversation sont protégés.';
        $this->_table['en-en']['::sylabe:module:messenger:conversation:defaultProtected'] = 'By default new messages into this conversation are protected.';
        $this->_table['es-co']['::sylabe:module:messenger:conversation:defaultProtected'] = 'By default new messages into this conversation are protected.';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversation:defaultUnprotected'] = 'Par défaut les nouveaux messages de cette conversation ne sont pas protégés.';
        $this->_table['en-en']['::sylabe:module:messenger:conversation:defaultUnprotected'] = 'By default new messages into this conversation are not protected.';
        $this->_table['es-co']['::sylabe:module:messenger:conversation:defaultUnprotected'] = 'By default new messages into this conversation are not protected.';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversation:defaultObfuscated'] = 'Par défaut les nouveaux messages de cette conversation sont dissimulés.';
        $this->_table['en-en']['::sylabe:module:messenger:conversation:defaultObfuscated'] = 'By default new messages into this conversation are obfuscated.';
        $this->_table['es-co']['::sylabe:module:messenger:conversation:defaultObfuscated'] = 'By default new messages into this conversation are obfuscated.';
        $this->_table['fr-fr']['::sylabe:module:messenger:conversation:defaultUnobfuscated'] = 'Par défaut les nouveaux messages de cette conversation ne sont pas dissimulés.';
        $this->_table['en-en']['::sylabe:module:messenger:conversation:defaultUnobfuscated'] = 'By default new messages into this conversation are not obfuscated.';
        $this->_table['es-co']['::sylabe:module:messenger:conversation:defaultUnobfuscated'] = 'By default new messages into this conversation are not obfuscated.';

        $this->_table['fr-fr']['::sylabe:module:messenger:create:newConversation'] = 'Créer la conversation';
        $this->_table['en-en']['::sylabe:module:messenger:create:newConversation'] = 'Create the conversation';
        $this->_table['es-co']['::sylabe:module:messenger:create:newConversation'] = 'Create the conversation';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:title'] = 'Titre';
        $this->_table['en-en']['::sylabe:module:messenger:create:title'] = 'Title';
        $this->_table['es-co']['::sylabe:module:messenger:create:title'] = 'Title';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:content'] = 'Contenu';
        $this->_table['en-en']['::sylabe:module:messenger:create:content'] = 'Content';
        $this->_table['es-co']['::sylabe:module:messenger:create:content'] = 'Content';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:closedConversation'] = 'Créer une conversation fermée.';
        $this->_table['en-en']['::sylabe:module:messenger:create:closedConversation'] = 'Create a closed conversation.';
        $this->_table['es-co']['::sylabe:module:messenger:create:closedConversation'] = 'Create a closed conversation.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:defaultProtectedConversation'] = 'Protéger par défaut les messages de cette conversation.';
        $this->_table['en-en']['::sylabe:module:messenger:create:defaultProtectedConversation'] = 'Protect by default messages into this conversation.';
        $this->_table['es-co']['::sylabe:module:messenger:create:defaultProtectedConversation'] = 'Protect by default messages into this conversation.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:defaultProtectedConversationLinks'] = 'Dissimuler par défaut les liens de cette conversation.';
        $this->_table['en-en']['::sylabe:module:messenger:create:defaultProtectedConversationLinks'] = 'Obfuscate by default links into this conversation.';
        $this->_table['es-co']['::sylabe:module:messenger:create:defaultProtectedConversationLinks'] = 'Obfuscate by default links into this conversation.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:OKCreateConversation'] = 'La conversation a été créée.';
        $this->_table['en-en']['::sylabe:module:messenger:create:OKCreateConversation'] = 'The conversation have been created.';
        $this->_table['es-co']['::sylabe:module:messenger:create:OKCreateConversation'] = 'The conversation have been created.';
        $this->_table['fr-fr']['::sylabe:module:messenger:create:notOKCreateConversation'] = "La conversation n'a pas été créée ! %s";
        $this->_table['en-en']['::sylabe:module:messenger:create:notOKCreateConversation'] = 'The conversation have not been created! %s';
        $this->_table['es-co']['::sylabe:module:messenger:create:notOKCreateConversation'] = 'The conversation have not been created! %s';

        $this->_table['fr-fr']['::sylabe:module:messenger:MaxTextSize'] = 'Taille maximum du texte : %so';
        $this->_table['en-en']['::sylabe:module:messenger:MaxTextSize'] = 'Maximum text size : %so';
        $this->_table['es-co']['::sylabe:module:messenger:MaxTextSize'] = 'Maximum text size : %so';

        $this->_table['fr-fr']['::sylabe:module:messenger:protectingEnabled'] = 'Protection autorisée.<br />Voir <a href="?a=1#cko">CKO</a>.';
        $this->_table['en-en']['::sylabe:module:messenger:protectingEnabled'] = 'Protection authorized.<br />See <a href="?a=1#cko">CKO</a>.';
        $this->_table['es-co']['::sylabe:module:messenger:protectingEnabled'] = 'Protection authorized.<br />See <a href="?a=1#cko">CKO</a>.';
        $this->_table['fr-fr']['::sylabe:module:messenger:protectingDisabled'] = 'Protection non autorisée !<br />Voir <a href="?a=1#cko">CKO</a>.';
        $this->_table['en-en']['::sylabe:module:messenger:protectingDisabled'] = 'Protection not authorized!<br />See <a href="?a=1#cko">CKO</a>.';
        $this->_table['es-co']['::sylabe:module:messenger:protectingDisabled'] = 'Protection not authorized!<br />See <a href="?a=1#cko">CKO</a>.';
        $this->_table['fr-fr']['::sylabe:module:messenger:obfuscatingEnabled'] = 'Dissimulation autorisée.<br />Voir <a href="?a=1#ckl">CKL</a>.';
        $this->_table['en-en']['::sylabe:module:messenger:obfuscatingEnabled'] = 'Obfuscation authorized.<br />See <a href="?a=1#ckl">CKL</a>.';
        $this->_table['es-co']['::sylabe:module:messenger:obfuscatingEnabled'] = 'Obfuscation authorized.<br />See <a href="?a=1#ckl">CKL</a>.';
        $this->_table['fr-fr']['::sylabe:module:messenger:obfuscatingDisabled'] = 'Dissimulation non autorisée !<br />Voir <a href="?a=1#ckl">CKL</a>.';
        $this->_table['en-en']['::sylabe:module:messenger:obfuscatingDisabled'] = 'Obfuscation not authorized!<br />See <a href="?a=1#ckl">CKL</a>.';
        $this->_table['es-co']['::sylabe:module:messenger:obfuscatingDisabled'] = 'Obfuscation not authorized!<br />See <a href="?a=1#ckl">CKL</a>.';

    }
}


/**
 * Cette application permet la prise en compte dans l'interface de la langue française.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateFRFR extends Modules
{
    protected $MODULE_TYPE = 'Traduction';
    protected $MODULE_NAME = '::translateModule:fr-fr:ModuleName';
    protected $MODULE_MENU_NAME = '::translateModule:fr-fr:MenuName';
    protected $MODULE_COMMAND_NAME = 'fr-fr';
    protected $MODULE_DEFAULT_VIEW = '';
    protected $MODULE_DESCRIPTION = '::translateModule:fr-fr:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = 'b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac';
    protected $MODULE_HELP = '::translateModule:fr-fr:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array();
    protected $MODULE_REGISTERED_ICONS = array('b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac');
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_initTable();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        if ($object == 'none') {
            $object = $this->_applicationInstance->getCurrentObject();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        $hookArray = array();
        switch ($hookName) {
            case 'helpLanguages':
                $hookArray[0]['name'] = $this->_traduction('::::Bienvenue', $this->MODULE_COMMAND_NAME);
                $hookArray[0]['icon'] = $this->MODULE_LOGO;
                $hookArray[0]['desc'] = $this->_traduction('::translateModule:' . $this->MODULE_COMMAND_NAME . ':ModuleDescription', $this->MODULE_COMMAND_NAME);
                $hookArray[0]['link'] = '?mod=hlp&view=lang&' . Traductions::DEFAULT_COMMAND_LANGUAGE . '=' . $this->MODULE_COMMAND_NAME;
                break;
        }
        return $hookArray;
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleName'] = 'Français (France)';
        $this->_table['en-en']['::translateModule:fr-fr:ModuleName'] = 'French (France)';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleName'] = 'Francés (Francia)';
        $this->_table['fr-fr']['::translateModule:fr-fr:MenuName'] = 'Français (France)';
        $this->_table['en-en']['::translateModule:fr-fr:MenuName'] = 'French (France)';
        $this->_table['es-co']['::translateModule:fr-fr:MenuName'] = 'Francés (Francia)';
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleDescription'] = "Traduction de l'interface en Français.";
        $this->_table['en-en']['::translateModule:fr-fr:ModuleDescription'] = 'Interface translation in French.';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleDescription'] = 'Interface translation in French.';
        $this->_table['fr-fr']['::translateModule:fr-fr:ModuleHelp'] = "Ce module permet de mettre en place la traduction de l'interface de sylabe en Français.";
        $this->_table['en-en']['::translateModule:fr-fr:ModuleHelp'] = 'This module permit to translate the sylabe interface in French.';
        $this->_table['es-co']['::translateModule:fr-fr:ModuleHelp'] = 'This module permit to translate the sylabe interface in French.';

        // Salutations.
        $this->_table['fr-fr']['::::Bienvenue'] = 'Bienvenue.';

        // Traduction de mots.
        $this->_table['fr-fr']['::Password'] = 'Mot de passe';
        $this->_table['fr-fr']['::yes'] = 'Oui';
        $this->_table['fr-fr']['::no'] = 'Non';
        $this->_table['fr-fr']['::::SecurityChecks'] = 'Tests de sécurité';
        $this->_table['fr-fr']['::Lock'] = 'Verrouiller';
        $this->_table['fr-fr']['::Unlock'] = 'Déverrouiller';
        $this->_table['fr-fr']['::EntityLocked'] = 'Entité verrouillée. Déverrouiller ?';
        $this->_table['fr-fr']['::EntityUnlocked'] = 'Entité déverrouillée. Verrouiller ?';
        $this->_table['fr-fr']['::::INFO'] = 'Information';
        $this->_table['fr-fr']['::::OK'] = 'OK';
        $this->_table['fr-fr']['::::INFORMATION'] = 'Message';
        $this->_table['fr-fr']['::::WARN'] = 'ATTENTION !';
        $this->_table['fr-fr']['::::ERROR'] = 'ERREUR !';
        $this->_table['fr-fr']['::::RESCUE'] = 'Mode de sauvetage !';
        $this->_table['fr-fr']['::::icon:DEFAULT_ICON_LO'] = 'Objet';
        $this->_table['fr-fr']['::::HtmlHeadDescription'] = 'Page web cliente sylabe pour nebule.';
        $this->_table['fr-fr']['::::Experimental'] = '[Experimental]';
        $this->_table['fr-fr']['::::Developpement'] = '[En cours de développement]';
        $this->_table['fr-fr']['::::help'] = 'Aide';
        $this->_table['fr-fr']['nebule/objet'] = 'Objet';
        $this->_table['fr-fr']['nebule/objet/hash'] = "Type d'empreinte";
        $this->_table['fr-fr']['nebule/objet/type'] = 'Type MIME';
        $this->_table['fr-fr']['nebule/objet/taille'] = 'Taille';
        $this->_table['fr-fr']['nebule/objet/nom'] = 'Nom';
        $this->_table['fr-fr']['nebule/objet/prefix'] = 'Préfix';
        $this->_table['fr-fr']['nebule/objet/prenom'] = 'Prénom';
        $this->_table['fr-fr']['nebule/objet/suffix'] = 'Suffix';
        $this->_table['fr-fr']['nebule/objet/surnom'] = 'Surnom';
        $this->_table['fr-fr']['nebule/objet/postnom'] = 'Surnom';
        $this->_table['fr-fr']['nebule/objet/entite'] = 'Entité';
        $this->_table['fr-fr']['nebule/objet/entite/type'] = 'Type';
        $this->_table['fr-fr']['nebule/objet/date'] = 'Date';
        $this->_table['fr-fr']['nebule/objet/date/annee'] = 'Année';
        $this->_table['fr-fr']['nebule/objet/date/mois'] = 'Mois';
        $this->_table['fr-fr']['nebule/objet/date/jour'] = 'Jour';
        $this->_table['fr-fr']['nebule/objet/date/heure'] = 'Heure';
        $this->_table['fr-fr']['nebule/objet/date/minute'] = 'Minute';
        $this->_table['fr-fr']['nebule/objet/date/seconde'] = 'Seconde';
        $this->_table['fr-fr']['nebule/objet/date/zone'] = 'Zone de temps';
        $this->_table['fr-fr']['nebule/objet/emotion/colere'] = 'Contrariant';
        $this->_table['fr-fr']['nebule/objet/emotion/degout'] = 'Dégôuté';
        $this->_table['fr-fr']['nebule/objet/emotion/surprise'] = 'Étonnant';
        $this->_table['fr-fr']['nebule/objet/emotion/peur'] = 'Inquiétant';
        $this->_table['fr-fr']['nebule/objet/emotion/interet'] = 'Intéressé';
        $this->_table['fr-fr']['nebule/objet/emotion/joie'] = "J'aime";
        $this->_table['fr-fr']['nebule/objet/emotion/confiance'] = "J'approuve";
        $this->_table['fr-fr']['nebule/objet/emotion/tristesse'] = 'Tristement';
        $this->_table['fr-fr']['nebule/objet/entite/localisation'] = 'Localisation';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/securite'] = 'Maître de la sécurité';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/code'] = 'Maître du code';
        $this->_table['fr-fr']['nebule/objet/entite/maitre/annuaire'] = "Maître de l'annuaire";
        $this->_table['fr-fr']['nebule/objet/entite/maitre/temps'] = 'Maître du temps';

        // Type mime
        $this->_table['fr-fr'][nebule::REFERENCE_OBJECT_TEXT] = 'Texte brute';
        $this->_table['fr-fr']['application/x-pem-file'] = 'Entité';
        $this->_table['fr-fr']['image/jpeg'] = 'Image JPEG';
        $this->_table['fr-fr']['image/png'] = 'Image PNG';
        $this->_table['fr-fr']['application/x-bzip2'] = 'Archive BZIP2';
        $this->_table['fr-fr']['text/html'] = 'Page HTML';
        $this->_table['fr-fr']['application/x-php'] = 'Code PHP';
        $this->_table['fr-fr']['text/x-php'] = 'Code PHP';
        $this->_table['fr-fr']['text/css'] = 'Feuille de style CSS';
        $this->_table['fr-fr']['audio/mpeg'] = 'Audio MP3';
        $this->_table['fr-fr']['audio/x-vorbis+ogg'] = 'Audio OGG';
        $this->_table['fr-fr']['application/x-encrypted/rsa'] = 'Chiffré';
        $this->_table['fr-fr']['application/x-encrypted/aes-256-ctr'] = 'Chiffré';
        $this->_table['fr-fr']['application/x-folder'] = 'Dossier';

        // Espressions courtes.
        $this->_table['fr-fr']['::::IDprivateKey'] = 'ID privé';
        $this->_table['fr-fr']['::::IDpublicKey'] = 'ID public';
        $this->_table['fr-fr']['::Version'] = 'Version';
        $this->_table['fr-fr']['::UniqueID'] = 'Identifiant universel : %s';
        $this->_table['fr-fr']['::GroupeFerme'] = 'Groupe fermé';
        $this->_table['fr-fr']['::GroupeOuvert'] = 'Groupe ouvert';
        $this->_table['fr-fr']['::ConversationFermee'] = 'Conversation fermée';
        $this->_table['fr-fr']['::ConversationOuverte'] = 'Conversation ouverte';
        $this->_table['fr-fr']['::progress'] = 'Chargement en cours...';
        $this->_table['fr-fr']['::seeMore'] = 'Voir plus...';
        $this->_table['fr-fr']['::noContent'] = '(contenu indisponible)';
        $this->_table['fr-fr']['::appSwitch'] = "Changer d'application";
        $this->_table['fr-fr']['::menu'] = 'Menu';
        $this->_table['fr-fr']['::menuDesc'] = 'Page du menu complet';
        $this->_table['fr-fr']['::EmptyList'] = 'Liste vide.';
        $this->_table['fr-fr']['::ChangeLanguage'] = 'Changer de langue';
        $this->_table['fr-fr']['::SelectUser'] = 'Sélectionner un utilisateur';
        $this->_table['fr-fr']['::MarkAdd'] = 'Marquer';
        $this->_table['fr-fr']['::MarkRemove'] = 'Démarquer';
        $this->_table['fr-fr']['::MarkRemoveAll'] = 'Démarquer tout';
        $this->_table['fr-fr']['::Synchronize'] = 'Synchroniser';

        // Phrases complètes.
        $this->_table['fr-fr'][':::display:content:errorBan'] = 'Cet objet est banni, il ne peut pas être affiché !';
        $this->_table['fr-fr'][':::display:content:warningTaggedWarning'] = 'Cet objet est marqué comme dangereux, attention à son contenu !';
        $this->_table['fr-fr'][':::display:content:ObjectProctected'] = 'Cet objet est protégé !';
        $this->_table['fr-fr'][':::display:content:warningObjectProctected'] = 'Cet objet est marqué comme protégé, attention à la divulgation de son contenu en public !!!';
        $this->_table['fr-fr'][':::display:content:OK'] = 'Cet objet est valide, son contenu a été vérifié.';
        $this->_table['fr-fr'][':::display:content:warningTooBig'] = "Cet objet est trop volumineux, son contenu n'a pas été vérifié !";
        $this->_table['fr-fr'][':::display:content:errorNotDisplayable'] = 'Cet objet ne peut pas être affiché !';
        $this->_table['fr-fr'][':::display:content:errorNotAvailable'] = "Cet objet n'est pas disponible, il ne peut pas être affiché !";
        $this->_table['fr-fr'][':::display:content:notAnObject'] = "Cet objet de référence n'a pas de contenu.";
        $this->_table['fr-fr'][':::display:content:ObjectHaveUpdate'] = 'Cet objet a été mis à jour vers :';
        $this->_table['fr-fr'][':::display:content:Activated'] = 'Cet objet est activé.';
        $this->_table['fr-fr'][':::display:content:NotActivated'] = 'Cet objet est désactivé.';
        $this->_table['fr-fr'][':::display:link:OK'] = 'Ce lien est valide.';
        $this->_table['fr-fr'][':::display:link:errorInvalid'] = "Ce lien n'est pas valide !";
        $this->_table['fr-fr'][':::warn_ServNotPermitWrite'] = "Ce serveur n'autorise pas les modifications.";
        $this->_table['fr-fr'][':::warn_flushSessionAndCache'] = "Toutes les données de connexion ont été effacées.";
        $this->_table['fr-fr'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['fr-fr'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['fr-fr'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['fr-fr'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['fr-fr'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['fr-fr'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['fr-fr'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';

        $this->_table['fr-fr'][':::display:object:flag:protected'] = 'Cet objet est protégé.';
        $this->_table['fr-fr'][':::display:object:flag:unprotected'] = "Cet objet n'est pas protégé.";
        $this->_table['fr-fr'][':::display:object:flag:obfuscated'] = 'Cet objet est dissimulé.';
        $this->_table['fr-fr'][':::display:object:flag:unobfuscated'] = "Cet objet n'est pas dissimulé.";
        $this->_table['fr-fr'][':::display:object:flag:locked'] = 'Cet entité est déverrouillée.';
        $this->_table['fr-fr'][':::display:object:flag:unlocked'] = 'Cet entité est verrouillée.';
        $this->_table['fr-fr'][':::display:object:flag:activated'] = 'Cet objet est activé.';
        $this->_table['fr-fr'][':::display:object:flag:unactivated'] = "Cet objet n'est pas activé.";

        /*
		$this->_table['fr-fr']['Lien']='Lien';
		$this->_table['fr-fr']['-indéfini-']='-indéfini-';
		$this->_table['fr-fr']['-indéterminé-']='-indéterminé-';
		$this->_table['fr-fr']['Affichage']='Affichage';
		$this->_table['fr-fr']['Aide']='Aide';
		$this->_table['fr-fr']['Algorithme']='Algorithme';
		$this->_table['fr-fr']['nebule/avis/ambigue']='Ambiguë';
		$this->_table['fr-fr']['Ambiguë']='Ambiguë';
		$this->_table['fr-fr']['Ambigue']='Ambiguë';
		$this->_table['fr-fr']['Année']='Année';
		$this->_table['fr-fr']['Attention !']='Attention !';
		$this->_table['fr-fr']['Aucun']='Aucun';
		$this->_table['fr-fr']['Aucune']='Aucune';
		$this->_table['fr-fr']['Bannissement']='Bannissement';
		$this->_table['fr-fr']['nebule/avis/beau']='Beau';
		$this->_table['fr-fr']['Beau']='Beau';
		$this->_table['fr-fr']['bits']='bits';
		$this->_table['fr-fr']['nebule/avis/bon']='Bon';
		$this->_table['fr-fr']['Bon']='Bon';
		$this->_table['fr-fr']['Bootstrap']='Bootstrap';
		$this->_table['fr-fr']['Caractéristiques']='Caractéristiques';
		$this->_table['fr-fr']['Charger']='Charger';
		$this->_table['fr-fr']['Chiffré']='Chiffré';
		$this->_table['fr-fr']['Chiffrement']='Chiffrement';
		$this->_table['fr-fr']['nebule/avis/clair']='Clair';
		$this->_table['fr-fr']['Clair']='Clair';
		$this->_table['fr-fr']['Commenter']='Commenter';
		$this->_table['fr-fr']['nebule/avis/complet']='Complet';
		$this->_table['fr-fr']['Complet']='Complet';
		$this->_table['fr-fr']['Contrariant']='Contrariant';
		$this->_table['fr-fr']['Cryptographie']='Cryptographie';
		$this->_table['fr-fr']['Date']='Date';
		$this->_table['fr-fr']['Déchiffrement']='Déchiffrement';
		$this->_table['fr-fr']['Dégôuté']='Dégôuté';
		$this->_table['fr-fr']['Déprotéger']='Déprotéger';
		$this->_table['fr-fr']['Description']='Description';
		$this->_table['fr-fr']['Déverrouillage']='Déverrouillage';
		$this->_table['fr-fr']['Déverrouiller']='Déverrouiller';
		$this->_table['fr-fr']["D'accord"]="D'accord";
		$this->_table['fr-fr']['Émotion']='Émotion';
		$this->_table['fr-fr']['Emotion']='Émotion';
		$this->_table['fr-fr']['Empreinte']='Empreinte';
		$this->_table['fr-fr']['Entité']='Entité';
		$this->_table['fr-fr']['Entités']='Entités';
		$this->_table['fr-fr']['ERREUR !']='ERREUR !';
		$this->_table['fr-fr']['ERROR']='ERREUR';
		$this->_table['fr-fr']['Étonnant']='Étonnant';
		$this->_table['fr-fr']['Etonnant']='Étonnant';
		$this->_table['fr-fr']['Expérimental']='Expérimental';
		$this->_table['fr-fr']['nebule/avis/faux']='Faux';
		$this->_table['fr-fr']['Faux']='Faux';
		$this->_table['fr-fr']['nebule/avis/génial']='Génial';
		$this->_table['fr-fr']['Génial']='Génial';
		$this->_table['fr-fr']['Genre']='Genre';
		$this->_table['fr-fr']['Heure']='Heure';
		$this->_table['fr-fr']['humain']='humain';
		$this->_table['fr-fr']['Identifiant']='Identifiant';
		$this->_table['fr-fr']['nebule/avis/important']='Important';
		$this->_table['fr-fr']['important']='Important';
		$this->_table['fr-fr']['Inconnu']='Inconnu';
		$this->_table['fr-fr']['nebule/avis/incomplet']='Incomplet';
		$this->_table['fr-fr']['Incomplet']='Incomplet';
		$this->_table['fr-fr']['nebule/avis/incomprehensible']='Incompréhensible';
		$this->_table['fr-fr']['Incompréhensible']='Incompréhensible';
		$this->_table['fr-fr']['Inquiétant']='Inquiétant';
		$this->_table['fr-fr']['Intéressé']='Intéressé';
		$this->_table['fr-fr']['nebule/avis/inutile']='Inutile';
		$this->_table['fr-fr']['Inutile']='Inutile';
		$this->_table['fr-fr']['Invalide']='Invalide';
		$this->_table['fr-fr']["J'aime"]="J'aime";
		$this->_table['fr-fr']["J'approuve"]="J'approuve";
		$this->_table['fr-fr']['Jour']='Jour';
		$this->_table['fr-fr']['Liens']='Liens';
		$this->_table['fr-fr']["L'objet"]="L'objet";
		$this->_table['fr-fr']['nebule/avis/mauvais']='Mauvais';
		$this->_table['fr-fr']['Mauvais']='Mauvais';
		$this->_table['fr-fr']['Minute']='Minute';
		$this->_table['fr-fr']['nebule/avis/moche']='Moche';
		$this->_table['fr-fr']['Moche']='Moche';
		$this->_table['fr-fr']['Mois']='Mois';
		$this->_table['fr-fr']['nebule/avis/moyen']='Moyen';
		$this->_table['fr-fr']['Moyen']='Moyen';
		$this->_table['fr-fr']['Navigation']='Navigation';
		$this->_table['fr-fr']['Nœud']='Nœud';
		$this->_table['fr-fr']['Noeud']='Nœud';
		$this->_table['fr-fr']['Nœuds']='Nœuds';
		$this->_table['fr-fr']['Noeuds']='Nœuds';
		$this->_table['fr-fr']['NOK']='NOK';
		$this->_table['fr-fr']['nebule/avis/nul']='Nul';
		$this->_table['fr-fr']['Nul']='Nul';
		$this->_table['fr-fr']['Objet']='Objet';
		$this->_table['fr-fr']['Objets']='Objets';
		$this->_table['fr-fr']['octet']='octet';
		$this->_table['fr-fr']['octets']='octets';
		$this->_table['fr-fr']['OK']='OK';
		$this->_table['fr-fr']['nebule/avis/perime']='Périmé';
		$this->_table['fr-fr']['Périmé']='Périmé';
		$this->_table['fr-fr']['privée']='privée';
		$this->_table['fr-fr']['Protection']='Protection';
		$this->_table['fr-fr']['Protéger']='Protéger';
		$this->_table['fr-fr']['publique']='publique';
		$this->_table['fr-fr']['Rafraîchir']='Rafraîchir';
		$this->_table['fr-fr']['Recherche']='Recherche';
		$this->_table['fr-fr']['Rechercher']='Rechercher';
		$this->_table['fr-fr']['Régénération']='Régénération';
		$this->_table['fr-fr']['Répéter']='Répéter';
		$this->_table['fr-fr']['robot']='robot';
		$this->_table['fr-fr']['Seconde']='Seconde';
		$this->_table['fr-fr']['Source']='Source';
		$this->_table['fr-fr']['Synchroniser']='Synchroniser';
		$this->_table['fr-fr']['Suppression']='Suppression';
		$this->_table['fr-fr']['Supprimer']='Supprimer';
		$this->_table['fr-fr']['Taille']='Taille';
		$this->_table['fr-fr']['Téléchargement']='Téléchargement';
		$this->_table['fr-fr']['Télécharger']='Télécharger';
		$this->_table['fr-fr']['Transfert']='Transfert';
		$this->_table['fr-fr']['Transmettre']='Transmettre';
		$this->_table['fr-fr']['Tristement']='Tristement';
		$this->_table['fr-fr']['Valeur']='Valeur';
		$this->_table['fr-fr']['Validité']='Validité';
		$this->_table['fr-fr']['Version']='Version';
		$this->_table['fr-fr']['Verrouillage']='Verrouillage';
		$this->_table['fr-fr']['Verrouiller']='Verrouiller';
		$this->_table['fr-fr']['Voir']='Voir';				// ok
		$this->_table['fr-fr']['nebule/avis/vrai']='Vrai';
		$this->_table['fr-fr']['Vrai']='Vrai';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		*/

        /*
		$this->_table['fr-fr']['Creation nouveau lien']="Création d'un nouveau lien";
		$this->_table['fr-fr']['::::GenNewEnt']='Génération nouvelle entité';
		$this->_table['fr-fr']['%01.0f liens lus,']='%01.0f liens lus,';
		$this->_table['fr-fr']['%01.0f liens vérifiés,']='%01.0f liens vérifiés,';
		$this->_table['fr-fr']['%01.0f objets vérifiés.']='%01.0f objets vérifiés.';
		$this->_table['fr-fr']['Accès au bootstrap.']='Accès au bootstrap.';
		$this->_table['fr-fr']["Afficher l'objet"]="Afficher l'objet";
		$this->_table['fr-fr']['Aide en ligne']='Aide en ligne';
		$this->_table['fr-fr']['::::AddNotice2Obj']='Ajouter un avis sur cet objet';
		$this->_table['fr-fr']['Ajout du nouveau lien non autorisé.']='Ajout du nouveau lien non autorisé.';
		$this->_table['fr-fr']['::::HashAlgo']="Algorithme de prise d'empreinte";
		$this->_table['fr-fr']['::::SymCryptAlgo']='Algorithme de chiffrement symétrique';
		$this->_table['fr-fr']['::::AsymCryptAlgo']='Algorithme de chiffrement asymétrique';
		$this->_table['fr-fr']['Annuler bannissement']='Annuler bannissement';
		$this->_table['fr-fr']['Archive BZIP2']='Archive BZIP2';
		$this->_table['fr-fr']['Aucun objet à afficher.']='Aucun objet à afficher.';
		$this->_table['fr-fr']['Aucun objet dérivé à afficher.']='Aucun objet dérivé à afficher.';
		$this->_table['fr-fr']['Audio MP3']='Audio MP3';
		$this->_table['fr-fr']['Audio OGG']='Audio OGG';
		$this->_table['fr-fr']['::::Switch2Ent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::::LoadObj2Browser']="Charger directement le code de l'objet dans le navigateur.";
		$this->_table['fr-fr']['Chiffré, non affichable.']='Chiffré, non affichable.';
		$this->_table['fr-fr']['Code PHP']='Code PHP';
		$this->_table['fr-fr']['Connexion non sécurisée']='Connexion non sécurisée';
		$this->_table['fr-fr']['::::AskEntSyncObj']="Demander à l'entité de bien vouloir synchroniser l'objet.";
		$this->_table['fr-fr']["Déverrouillage de l'entité"]="Déverrouillage de l'entité";
		$this->_table['fr-fr']['Émotions et avis']='Émotions et avis';
		$this->_table['fr-fr']['Empreinte cryptographique du bootstrap']='Empreinte cryptographique du bootstrap';
		$this->_table['fr-fr']['Entité déverrouillée.']='Entité déverrouillée.';
		$this->_table['fr-fr']['Entité en cours.']='Entité en cours.';
		$this->_table['fr-fr']['Entité verrouillée (non connectée).']='Entité verrouillée (non connectée).';
		$this->_table['fr-fr']['Essayer plutôt']='Essayer plutôt';
		$this->_table['fr-fr']['est à jour.']='est à jour.';
		$this->_table['fr-fr']['Erreur lors du chiffrement !']='Erreur lors du chiffrement !';
		$this->_table['fr-fr']['Erreur lors du déchiffrement !']='Erreur lors du déchiffrement !';
		$this->_table['fr-fr']['Feuille de style CSS']='Feuille de style CSS';
		$this->_table['fr-fr']["Fil d'actualités"]="Fil d'actualités";
		$this->_table['fr-fr']['Génération de miniatures']='Génération de miniatures';
		$this->_table['fr-fr']['Identifiant universel']='Identifiant universel';
		$this->_table['fr-fr']['Image JPEG']='Image JPEG';
		$this->_table['fr-fr']['Image PNG']='Image PNG';
		$this->_table['fr-fr']['Informations sur le serveur']='Informations sur le serveur';
		$this->_table['fr-fr']['Lien de mise à jour']='Lien de mise à jour';
		$this->_table['fr-fr']['Lien écrit.']='Lien écrit.';
		$this->_table['fr-fr']['Lien invalide']='Lien invalide';
		$this->_table['fr-fr']['Lien non vérifié']='Lien non vérifié';
		$this->_table['fr-fr']['Lien valide']='Lien valide';
		$this->_table['fr-fr']['::::EncryptedFor']="L'objet est chiffré pour";
		$this->_table['fr-fr']['mis à jour vers %s.']='mis à jour vers %s.';
		$this->_table['fr-fr']['Mise à jour']='Mise à jour';
		$this->_table['fr-fr']['Mise à jour de sylabe']='Mise à jour de sylabe';
		$this->_table['fr-fr']['Mise à jour de tous les composants.']='Mise à jour de tous les composants.';
		$this->_table['fr-fr']['Mise en place du mot de passe sur la clé privée.']='Mise en place du mot de passe sur la clé privée.';
		$this->_table['fr-fr']["Mode d'affichage"]="Mode d'affichage";
		$this->_table['fr-fr']["Naviguer autour de l'objet"]="Naviguer autour de l'objet";
		$this->_table['fr-fr']['Nœuds connus']='Nœuds connus';
		$this->_table['fr-fr']['Nom complet']='Nom complet';
		$this->_table['fr-fr']['Nom de variable']='Nom de variable';
		$this->_table['fr-fr']['Non affichable.']='Non affichable.';
		$this->_table['fr-fr']['Non déverrouillée.']='Non déverrouillée.';
		$this->_table['fr-fr']['Non disponible.']='Non disponible.';
		$this->_table['fr-fr']['Non fonctionnel.']='Non fonctionnel.';
		$this->_table['fr-fr']['Objet de test']='Objet de test';
		$this->_table['fr-fr']['Page HTML']='Page HTML';
		$this->_table['fr-fr']['Objet non disponible localement.']='Objet non disponible localement.';
		$this->_table['fr-fr']['Pas de mise à jour connue de cet objet.']='Pas de mise à jour connue de cet objet.';
		$this->_table['fr-fr']["Pas d'accord"]="Pas d'accord";
		$this->_table['fr-fr']["Pas d'action à traiter."]="Pas d'action à traiter.";
		$this->_table['fr-fr']['Pas un nœud']='Pas un nœud';
		$this->_table['fr-fr']['Pas un noeud']='Pas un nœud';
		$this->_table['fr-fr']["Protection de l'objet"]="Protection de l'objet";
		$this->_table['fr-fr']["Protéger l'objet."]="Protéger l'objet.";
		$this->_table['fr-fr']['Rafraîchir la vue']='Rafraîchir la vue';
		$this->_table['fr-fr']['Rafraichir la vue']='Rafraîchir la vue';
		$this->_table['fr-fr']['Rafraichir la vue et charger les nouvelles versions.']='Rafraichir la vue et charger les nouvelles versions.';
		$this->_table['fr-fr']['Recharger la page.']='Recharger la page.';
		$this->_table['fr-fr']['Régénération des composants manquants.']='Régénération des composants manquants.';
		$this->_table['fr-fr']["Revenir au menu des capacités de transfert d'objets et de liens"]="Revenir au menu des capacités de transfert d'objets et de liens";
		$this->_table['fr-fr']['Session utilisateur']='Session utilisateur';
		$this->_table['fr-fr']["Supprimer l'avis."]="Supprimer l'avis.";
		$this->_table['fr-fr']["Supprimer l'émotion."]="Supprimer l'émotion.";
		$this->_table['fr-fr']["Synchronisation d'un objet non reconnu localement"]="Synchronisation d'un objet non reconnu localement";
		$this->_table['fr-fr']['Taille des clés de chiffrement asymétrique']='Taille des clés de chiffrement asymétrique';
		$this->_table['fr-fr']['Taille des clés de chiffrement symétrique']='Taille des clés de chiffrement symétrique';
		$this->_table['fr-fr']['Taille des empreintes cryptographiques']='Taille des empreintes cryptographiques';
		$this->_table['fr-fr']['::::DownloadAsFile']="Télécharger l'objet sous forme de fichier.";
		$this->_table['fr-fr']["Toutes les capacités de transfert d'objets et de liens"]="Toutes les capacités de transfert d'objets et de liens";
		$this->_table['fr-fr']["Transférer la protection à l'entité"]="Transférer la protection à l'entité";
		$this->_table['fr-fr']['Type de clé']='Type de clé';
		$this->_table['fr-fr']['type inconnu']='type inconnu';
		$this->_table['fr-fr']['Type MIME']='Type MIME';
		$this->_table['fr-fr']['URL de connexion']='URL de connexion';
		$this->_table['fr-fr']['::::VerifLinkSign']='Vérification des signatures de liens';
		$this->_table['fr-fr']["Verrouillage (déconnexion) de l'entité."]="Verrouillage (déconnexion) de l'entité.";
		$this->_table['fr-fr']["Verrouiller l'entité."]="Verrouiller l'entité.";
		$this->_table['fr-fr']["Variables d'environnement"]="Variables d'environnement";
		$this->_table['fr-fr']['Voir déchiffré']='Voir déchiffré';
		$this->_table['fr-fr']['Voir les liens']='Voir les liens';
		$this->_table['fr-fr']['Voir tout']='Voir tout';
		$this->_table['fr-fr']['Zone de temps']='Zone de temps';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';



		$this->_table['fr-fr']['Cet objet a été mise à jour vers']='Cet objet a été mise à jour vers';
		$this->_table['fr-fr'][':::warn_InvalidPubKey']='La clé publique semble invalide !';
		$this->_table['fr-fr'][':::nav_aff_MaxFileSize']='La taille maximum du fichier ne doit pas dépasser %.0f caractères (octets).';
		$this->_table['fr-fr'][':::nav_aff_MaxTextSize']='La taille maximum du texte ne doit pas dépasser %.0f caractères (octets).';
		$this->_table['fr-fr']["Le lien n'a pas été écrit !"]="Le lien n'a pas été écrit !";
		$this->_table['fr-fr']['Le serveur à pris %01.4fs pour calculer la page.']='Le serveur à pris %01.4fs pour calculer la page.';
		$this->_table['fr-fr']["L'opération peut prendre un peu de temps."]="L'opération peut prendre un peu de temps.";
		$this->_table['fr-fr'][':::warn_NoAudioTagSupport']='Votre navigateur ne supporte pas le tag audio.';
		$this->_table['fr-fr'][':::err_CantWriteLink']="Une erreur s'est produite lors de l'écriture d'un lien !";
		$this->_table['fr-fr'][':::warn_CantGenThumNoGD']="Les miniatures de l'image n'ont pas été générées (lib GD2 no présente).";
		$this->_table['fr-fr'][':::err_CantAnalysImg']="Erreur lors de l'analyse de l'image.";
		$this->_table['fr-fr'][':::warn_CantGenThumUnknowImg']="Les miniatures de l'image n'ont pas été générées. Le type d'image n'est pas reconnu.";
		$this->_table['fr-fr'][':::hlp_DescObjLnk']="Le monde de <i>sylabe</i> est peuplé d'objets et de liens.";
		$this->_table['fr-fr'][':::ent_create_WarnAutonomNewEnt']='Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.';
		$this->_table['fr-fr'][':::ent_create_WarnMustHaveMDP']='Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.';
		$this->_table['fr-fr'][':::act_MustUnlockEnt']="Il est nécessaire de déverrouiller l'entité pour pouvoir agir sur les objets et les liens.";
		$this->_table['fr-fr'][':::warn_NoObjDesc']="Pas de description pour ce type d'objet.";
		$this->_table['fr-fr'][':::warn_LoadObj2Browser']="Charger directement le code de l'objet dans votre navigateur peut être dangereux !!!";
		$this->_table['fr-fr'][':::aff_protec_Protected']="L'objet est marqué comme protégé.";
		$this->_table['fr-fr'][':::aff_protec_Unprotected']="L'objet n'est pas marqué comme protégé.";
		$this->_table['fr-fr'][':::aff_protec_RemProtect']="Retirer la protection de l'objet.";
		$this->_table['fr-fr'][':::aff_protec_FollowProtTo']="Transférer la protection à l'entité";
		$this->_table['fr-fr'][':::aff_sync_SyncLnkObj']="Synchroniser les liens de l'objet.";
		$this->_table['fr-fr'][':::aff_sync_SyncObj']="Synchroniser le contenu de l'objet.";
		$this->_table['fr-fr'][':::aff_sync_SearchUpdate']="Rechercher les mises à jour de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_SuppObj']="Supprimer l'objet.";
		$this->_table['fr-fr'][':::aff_supp_RemSuppObj']="Annuler la suppression de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_BanObj']="Supprimer et bannir l'objet.";
		$this->_table['fr-fr'][':::aff_supp_RemBanObj']="Annuler le bannissement de l'objet.";
		$this->_table['fr-fr'][':::aff_supp_ForceSuppObj']="Forcer la suppression de l'objet sur ce serveur.";
		$this->_table['fr-fr'][':::aff_node_IsNode']="L'objet est un nœud.";
		$this->_table['fr-fr'][':::aff_node_IsnotNode']="L'objet n'est pas un nœud.";
		$this->_table['fr-fr'][':::aff_node_DefineNode']="Définir l'objet comme étant un nœud.";
		$this->_table['fr-fr'][':::aff_node_RemDefineNode']="Ne plus définir l'objet comme étant un nœud.";
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';
		$this->_table['fr-fr']['noooops']='';



		// Blocs de texte.
		$this->_table['fr-fr']['::hlp_msgaffok']='Ceci est un message pour une opération se terminant sans erreur.';
		$this->_table['fr-fr']['::hlp_msgaffwarn']="Ceci est un message d'avertissement.";
		$this->_table['fr-fr']['::hlp_msgafferror']="Ceci est un message d'erreur.";
		$this->_table['fr-fr']['::hlp_text']="";
		$this->_table['fr-fr']['::bloc_hlp_head']='Aide en ligne';
		$this->_table['fr-fr']['::bloc_hlp_head_hlp']="C'est la page de l'aide en ligne. En cours de rédactions...";
		$this->_table['fr-fr']['::bloc_metrolog']='Métrologie';
		$this->_table['fr-fr']['::bloc_metrolog_hlp']="La partie métrologie donne les mesures de temps globaux et partiels pour le traitement et l'affichage de la page web.";
		$this->_table['fr-fr']['::bloc_aff_head_hlp']="Affichage de l'objet";
		$this->_table['fr-fr']['::bloc_aff_chent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_aff_chent_hlp']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_aff_dwload']='Téléchargement et transmission';
		$this->_table['fr-fr']['::bloc_aff_dwload_hlp']='Téléchargement et transmission';
		$this->_table['fr-fr']['::bloc_aff_protec']='Protection';
		$this->_table['fr-fr']['::bloc_aff_protec_hlp']="<p>1 - L'objet peut être protégé ou non protégé, c'est à dire chiffré ou non.</p>\n
		<p>2 - Cette commande permet de protéger l'objet, il sera automatiquement marqué comme supprimé <u>et</u> supprimé localement.</p>\n
		<p>3 - Cette commande permet de lever la protection de l'objet et de le restaurer. La marque de suppression sera annulée.</p>\n
		<p>4 - Cette commande permet de transmettre la protection de l'objet à une autre entité. L'entité pourra voir l'objet protégé mais aussi annuler ou retransmettre cette protection.</p>\n
		<p><b>Une donnée que l’on transmet à autrui, c’est une donnée sur laquelle on perd irrémédiablement tout contrôle.</b></p>\n
		<p>Un objet qui a été protégé est normalement marqué supprimé et localement supprimé en même temps. Il devrait donc ne plus être disponible publiquement, mais ce n'est pas obligatoire :<br />\n
		- Si l'objet a été diffusé à d'autres entités préalablement à sa protection, les autres entités verront qu'il est marqué supprimé, donc a supprimer, mais n'en tiendront peut-être pas compte.<br />\n
		- Si cette instance de <i>sylabe</i> héberge plusieurs entités et qu'une des entité locale utilise cet objet, il ne pourra pas être localement supprimé.
		Il sera malgré tout marqué supprimé. Seule l'entité propriétaire de l'instance pourra forcer localement la suppression de l'objet.</p>";
		$this->_table['fr-fr']['::bloc_aff_sync']='Synchronisation et mise à jour';
		$this->_table['fr-fr']['::bloc_aff_sync_hlp']='Synchronisation et mise à jour';
		$this->_table['fr-fr']['::bloc_aff_supp']='Suppression et bannissement';
		$this->_table['fr-fr']['::bloc_aff_supp_hlp']='Suppression et bannissement';
		$this->_table['fr-fr']['::bloc_aff_node']='Nœud';
		$this->_table['fr-fr']['::bloc_aff_node_hlp']='Nœud';
		$this->_table['fr-fr']['::bloc_aff_deriv']='Dérivation';
		$this->_table['fr-fr']['::bloc_aff_deriv_hlp']='Dérivation';
		$this->_table['fr-fr']['::bloc_aff_maj']="Mise à jour de l'objet";
		$this->_table['fr-fr']['::bloc_aff_maj_hlp']="Mise à jour de l'objet";
		$this->_table['fr-fr']['::bloc_nav_head_hlp']="<p>Dans le mode de navigation, l'objet est affiché de façon réduite ou tronquée.
		Ce mode ne permet d'avoir qu'une vision globale de l'objet mais se focalise sur ses relations avec les autres objets.</p>";
		$this->_table['fr-fr']['::bloc_nav_chent']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_nav_chent_hlp']='Basculer vers cette entité';
		$this->_table['fr-fr']['::bloc_nav_update']='Mise à jour';
		$this->_table['fr-fr']['::bloc_nav_update_hlp']='Mise à jour';
		$this->_table['fr-fr']['::bloc_nav_actu']="Fil d'actualités";
		$this->_table['fr-fr']['::bloc_nav_actu_hlp']="Fil d'actualités";
		$this->_table['fr-fr']['::bloc_log_head']="Session de l'entité";
		$this->_table['fr-fr']['::bloc_log_head_hlp']="Session de l'entité";
		$this->_table['fr-fr']['::bloc_obj_head']='Les objets';
		$this->_table['fr-fr']['::bloc_obj_head_hlp']='Les objets';
		$this->_table['fr-fr']['::bloc_nod_head']="Nœuds et points d'entrée";
		$this->_table['fr-fr']['::bloc_nod_head_hlp']="Nœuds et points d'entrée";
		$this->_table['fr-fr']['::bloc_nod_create']='Créer un nœud';
		$this->_table['fr-fr']['::bloc_nod_create_hlp']="<p>Le champs attendu est un texte sans caractères spéciaux. Le texte sera transformé en un objet et celui-ci sera définit comme un nœud.
		Il n'est pas recommandé d'avoir des retours à la ligne dans ce texte.<br />
		Si un objet existe déjà avec ce texte, il sera simplement définit comme nœud.</p>";
		$this->_table['fr-fr']['::bloc_ent_head']='Gestion des entités';
		$this->_table['fr-fr']['::bloc_ent_head_hlp']='Gestion des entités';
		$this->_table['fr-fr']['::bloc_ent_known']='Entités connues';
		$this->_table['fr-fr']['::bloc_ent_known_hlp']='Entités connues';
		$this->_table['fr-fr']['::bloc_ent_ctrl']='Entités sous contrôle';
		$this->_table['fr-fr']['::bloc_ent_ctrl_hlp']='Entités sous contrôle';
		$this->_table['fr-fr']['::bloc_ent_unknown']='Entités inconnues';
		$this->_table['fr-fr']['::bloc_ent_unknown_hlp']='Entités inconnues';
		$this->_table['fr-fr']['::bloc_ent_follow']='Reconnaître une entité';
		$this->_table['fr-fr']['::bloc_ent_follow_hlp']="     <p>Au moins un des deux champs doit être renseigné.</p>\n
		<p>1 - L'<b>URL de présence</b> est une adresse sur le web (http, rfc2616) hébergeant un serveur nebule capable de délivrer publiquement les informations sur l'entité recherchée.
		Cette adresse web doit être valide, elle a typiquement la forme <i>http://puppetmaster.nebule.org</i> .<br />\n
		Si ce champs n'est pas renseigné, l'adresse sera recherchée automatiquement. Elle est dabort recherchée localement si l'entité est déjà connue sans être reconnue.
		Elle est ensuite par défaut replacée par l'adresse de l'annuaire par défaut, c'est à dire <i>asabiyya</i>. Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
		L'adresse ne doit pas être une adresse locale, c'est à dire <code>localhost</code>.<br />\n
		Si la valeur renseignée est fausse, la recherche a de bonnes chances de ne pas aboutir.\n
		</p>\n
		<p>2 - L'<b>Objet ID public</b> est <u>le</u> numéro unique identifiant sans ambiguité l'entité recherchée. Ce numéro en héxadécimal est l'empreinte de la clé publique de l'entité.
		Sans ce numéro, il sera impossible de récupérer l'objet contenant la clé publique, et donc il sera impossible de vérifier les liens que cette entité a généré.<br />\n
		Ce champs, si le numéro est connu, doit être très précisément renseigné et de façon complet. Si ce champs n'est pas renseigné, le numéro sera recherché automatiquement à l'adresse web renseignée.
		Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
		Si la valeur renseignée est fausse, la recherche n'aboutira pas.\n
		</p>";
		$this->_table['fr-fr']['::bloc_ent_create']='Créer une entité';
		$this->_table['fr-fr']['::bloc_ent_create_hlp']="<p>Si l'entité créé est autonome, le champs <b>Mot de passe</b> doit être renseigné.</p>
		<p>1 - Le champs <b>Prénom</b> permet de donner un prénom à l'entité. Ce champs est facultatif.</p>
		<p>2 - Le champs <b>Nom</b> permet de donner un nom patronymique à l'entité. Ce champs est facultatif.</p>
		<p>3 - Le champs <b>Surnom</b> permet de donner un surnom à l'entité. Ce champs est facultatif.</p>
		<p>4 - Le choix du <b>Type</b> permet de catégoriser l'entité comme humain ou robot. Ce champs est facultatif.</p>
		<p>5 - Le champs <b>URL de présence</b> permet de donner à l'entité une localisation définie (http, rfc2616). Ce champs est facultatif.<br />
		C'est à cette localisation que l'on devra pouvoir synchroniser les liens et objets de l'entité. Si la localisation n'est pas ce serveur, il est de votre ressort de préparer cette localisation.<br />
		Par défaut, ce sera l'adresse web du serveur, sauf si c'est <code>localhost</code>.<br />
		Il est fortement déconseillé d'utiliser une adresse locale, c'est à dire <code>localhost</code>.</p>
		<p>6 - La case à cocher permet de préciser si l'entité créé est une entité autonome, c'est à dire ne dépendant pas de l'entité en cours.<br />
		Si l'entité n'est pas autonome, elle sera automatiquement reconnue comme dépendante de l'entité courante et un mot de passe sera automatiquement généré pour être utilisé par l'entité courante.<br />
		Si l'entité créé est autonome, un mot de passe est obligatoire.</p>
		<p>7 - Le champs <b>Mot de passe</b> permet donner un mot de passe <i>secret</i> pour pouvoir déverrouiller la nouvelle entité.<br />
		Ce mot de passe n'est pas celui de l'entité en cours (si déverrouillée) mais le mot de passe qui sera utilisé par la nouvelle entité.<br />
		Si l'entité créé est autonome, un mot de passe est obligatoire.<br />
		Le mot de passe doit être saisi deux fois pour prévenir toute erreur de saisi.
		<p>8 - La valeur de <b>Taille de clé</b> définit la longueur de la clé générée et donc permet de maîtriser directement sa solidité. Cependant, des clés trop longues pénalisent les performances.<br />
		Il est recommandé en 2014 de choisir une taille de clé au moins égale à <b>2048bits</b>.<br />
		Le choix par défaut est définit pour le serveur.</p>
		<p>9 - Le choix de l'<b>Algo chiffrement</b> définit le type d'algorithme utilisé. Il y a peu de choix actuellement.<br />
		Il est recommandé de choisir l'algorithme <b>RSA</b>.<br />
		Le choix par défaut est définit pour le serveur.</p>
		<p>10 - Le choix de l'<b>Algo empreinte</b> définit l'algorithme de prise d'empreinte et donc permet de maîtriser directement sa solidité. Cependant, les algorithmes les plus élevés pénalisent les performances.<br />
		Il est recommandé en 2014 de choisir l'algorithme <b>sha256</b> ou plus.<br />
		Le choix par défaut est définit pour le serveur.
		</p>";
		$this->_table['fr-fr']['::bloc_chr_head']='Recherche';
		$this->_table['fr-fr']['::bloc_chr_head_hlp']='Recherche';
		$this->_table['fr-fr']['::bloc_lnk_head']="Liens de l'objet";
		$this->_table['fr-fr']['::bloc_lnk_head_hlp']="<p>Le filtrage permet de réduire l'affichage des liens dans la liste ci-dessous.</p>
		<p>1 - Active le filtrage et cache les liens qui ont été marqués comme supprimés, c'est à dire lorsque le même lien a été généré mais avec l'action <code>x</code>.
		Les liens de suppression ne sont pas affichés non plus.</p>
		<p>2 - On peut ne conserver à l'affichage que certains types de liens, c'est en fait l'action qu'ils ont sur l'objet et les autres liens.
		Par exemple on peut ne vouloir que les liens de chiffrement dont le type est <code>k</code>.</p>
		<p>3 - On peut n'afficher que les liens de l'objet courant a avec un autre objet.
		Ce peut être par exemple la description du type mime (5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0) de l'objet.
		</p>";
		//$this->_table['fr-fr']['::bloc_lnk_list']='Liste des liens';
		$this->_table['fr-fr']['::bloc_lnk_list_hlp']="";
		$this->_table['fr-fr']['::bloc_upl_head']="Transfert d'objets et de liens";
		$this->_table['fr-fr']['::bloc_upl_head_hlp']="Transfert d'objets et de liens";
		$this->_table['fr-fr']['::bloc_upl_upfile']="Envoi d'un fichier comme nouvel objet";
		$this->_table['fr-fr']['::bloc_upl_upfile_hlp']="<p>Cette partie permet de transmettre un fichier à nébuliser, c'est à dire à transformer en objet <i>nebule</i>.</p>
		<p>L'empreinte du fichier est automatiquement calculée, elle deviendra l'identifiant (ID) de l'objet.
		En fonction du type de fichier, il est analysé afin d'en extraire certaines caractéristiques personnalisées.</p>";
		$this->_table['fr-fr']['::bloc_upl_uptxt']="Envoi d'un nouveau texte";
		$this->_table['fr-fr']['::bloc_upl_uptxt_hlp']="<p>Cette partie permet la création d'un objet à partir d'un texte brute, c'est à dire sans formatage.</p>";
		$this->_table['fr-fr']['::bloc_upl_synobj']="Synchronisation d'un objet non reconnu localement";
		$this->_table['fr-fr']['::bloc_upl_synobj_hlp']="<p>Cette partie permet de tenter de trouver un objet et ses liens aux différents emplacements connus.
		L'objet est recherche par rapport à son identifiant, c'est à dire son empreinte.</p>";
		$this->_table['fr-fr']['::bloc_upl_uplnk']="Envoie d'un simple lien";
		$this->_table['fr-fr']['::bloc_upl_uplnk_hlp']="Cette partie permet de transmettre un lien à ajouter. Après vérification, le lien est automatiquement attaché aux objets concernés.</p>
		<p>Si une entité n'est pas déverrouillée, le lien doit être signé par l'entité indiqué. C'est dans ce cas un import d'un seul lien.
		Pour transmettre plusieurs liens simultanément, il faut passer par la partie '<i>Envoie d'un fichier de liens pré-signés</i>'.</p>";
		$this->_table['fr-fr']['::bloc_upl_crlnk']="Création d'un nouveau lien";
		$this->_table['fr-fr']['::bloc_upl_crlnk_hlp']="<p>Cette partie permet la création d'un nouveau lien et sa signature. Il faut renseigner les différents champs correspondants au registre du lien attendu.</p>";
		$this->_table['fr-fr']['::bloc_upl_upfilelnk']="Envoie d'un fichier de liens pré-signés";
		$this->_table['fr-fr']['::bloc_upl_upfilelnk_hlp']="<p>Cette partie permet de transmettre un fichier contenant des liens à ajouter. Tous les liens doivent être signés pour être analysés.
		Après vérification, les liens sont automatiquement attachés aux objets concernés.</p>";

		// Description des variables
		$this->_table['fr-fr']['::var_nebule_hashalgo']="Algorithme de prise d'empreinte utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_symalgo']="Algorithme de chiffrement symétrique utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_symkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement symétrique.";
		$this->_table['fr-fr']['::var_nebule_asymalgo']="Algorithme de chiffrement asymétrique utilisé par défaut.";
		$this->_table['fr-fr']['::var_nebule_asymkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement asymétrique.";
		$this->_table['fr-fr']['::var_nebule_io_maxlink']="Limite du nombre de liens à lire pour un objet, les suivants sont ignorés. Utilisé par les fonctions <code>_l_ls1</code> et <code>__io_lr</code>.";
		$this->_table['fr-fr']['::var_nebule_io_maxdata']="Limite de la quantité de données en octets à lire pour un objet, le reste est ignorés. Utilisé par les fonctions <code>_o_dl1</code> et <code>__io_or</code>.";
		$this->_table['fr-fr']['::var_nebule_checksign']="Autorise ou non la vérification de la signature des liens. Utilisé par la fonction <code>_l_vr</code> et surtout lors d'un transfert. Devrait toujours être à <u>true</u>.";
		$this->_table['fr-fr']['::var_nebule_listchecklinks']="Autorise ou non la vérification de la validité des liens lors de leur lecture, signature compris. Utilisé par la fonction <code>_l_ls1</code>. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_listinvalidlinks']="Autorise ou non la lecture des liens invalides. C'est destiné à de l'affichage, les liens ne sont pas pris en compte. Utilisé par la fonction <code>_l_ls1</code>.";
		$this->_table['fr-fr']['::var_nebule_permitwrite']="Autorise ou non l'écriture par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_dl1</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code>,
		<code>_l_wr</code>, <code>_l_gen</code>, <code>__io_lw</code> et <code>__io_ow</code>. Positionné à <u>false</u>, c'est une protection globale en lecture seule.";
		$this->_table['fr-fr']['::var_nebule_permitcreatelink']="Autorise ou non la création de nouveaux liens par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_l_wr</code>, <code>_l_gen</code> et <code>__io_lw</code>.";
		$this->_table['fr-fr']['::var_nebule_permitcreateobj']="Autorise ou non la création de nouveaux objets par le code <code>php</code>.
		Utilisé par les fonctions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code> et <code>__io_ow</code>.";
		$this->_table['fr-fr']['::var_nebule_permitcreatentity']="Autorise ou non la création de nouvelles entités par le code <code>php</code>. Utilisé par la fonction <code>_e_gen</code>.";
		$this->_table['fr-fr']['::var_nebule_permitsynclink']="Autorise ou non le transfert de liens depuis un autre serveur nebule. Utilisé par la fonction <code>_l_dl1</code>.";
		$this->_table['fr-fr']['::var_nebule_permitsyncobject']="Autorise ou non le transfert d'objets depuis un autre serveur nebule. Utilisé par la fonction <code>_o_dl1</code>.";
		$this->_table['fr-fr']['::var_nebule_createhistory']="Autorise ou non la tenue d'un historique des derniers liens créés. Cela crée un fichier de liens <code>/l/f</code> qui doit être nettoyé régulièrement.
		C'est utilisé pour exporter plus facilement les derniers liens créés sur une entité déconnectée du réseau.";
		$this->_table['fr-fr']['::var_nebule_curentnotauthority']="Interdit à l'entité courante d'être autorité. Cela l'empêche de charger des composants externes par elle-même. Dans le bootstrap, le comportement est un peu différent.";
		$this->_table['fr-fr']['::var_nebule_local_authority']="C'est la liste des entités reconnues comme autorités locales. Seules ces entités peuvent signer des modules à charger localement.";
		$this->_table['fr-fr']['::var_sylabe_affuntrustedsign']="Affiche ou non le résultat de la vérification des liens, mode d'affichage <code>lnk</code> uniquement.";
		$this->_table['fr-fr']['::var_sylabe_hidedevmods']="Bascule l'affichage entre le mode de développement et le mode épuré.";
		$this->_table['fr-fr']['::var_sylabe_permitsendlink']="Autorise ou non le transfert de liens vers ce serveur.";
		$this->_table['fr-fr']['::var_sylabe_permitsendobject']="Autorise ou non le transfert d'objets vers ce serveur.";
		$this->_table['fr-fr']['::var_sylabe_permitpubcreatentity']="Autorise ou non la création d'une entité (autonome) de façon publique, c'est à dire même si aucune entité n'est préalablement déverrouillée.
		Doit être à <u>false</u> sur un serveur public.";
		$this->_table['fr-fr']['::var_nebule_permitcreatentnopwd']="Autorise ou non la création d'une entité sans mot de passe. Devrait toujours être à <u>false</u>.";
		$this->_table['fr-fr']['::var_sylabe_permitaskbootstrap']="Autorise ou non le passage de consigne au <i>bootstrap</i> pour sélectionner une version de sylabe et de la librairie. Doit être à <u>false</u> sur un serveur public.";
		$this->_table['fr-fr']['::var_sylabe_affonlinehelp']="Autorise ou non l'affichage de l'aide en ligne.";
		$this->_table['fr-fr']['::var_sylabe_showvars']="Affiche ou non les variables internes, mode d'affichage <code>log</code> uniquement.";
		$this->_table['fr-fr']['::var_sylabe_timedebugg']="Affiche les temps de traitements intermédiaires, en ligne.";
		$this->_table['fr-fr']['::var_sylabe_upfile_maxsize']="Définit la taille maximale en octets (après uuencode) des fichiers lors d'un téléchargement vers ce serveur.";
		$this->_table['fr-fr']['::var_nebule_followxonsamedate']="Prendre en compte le lien x si la date est identique avec un autre lien, ou pas.";
		$this->_table['fr-fr']['::var_nebule_maxrecurse']="Définit le maximum de niveaux parcourus pour la recherche des objets enfants d'un objet. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_maxupdates']="Définit le maximum de niveaux parcourus poue la recherche des mises à jours d'un objet. Affecte les performances.";
		$this->_table['fr-fr']['::var_nebule_linkversion']="Définit la version de nebule utilisée pour les liens.";
		$this->_table['fr-fr']['::var_nebule_usecache']="Autorise ou non l'utilisation du cache. Affecte les performances.";
		$this->_table['fr-fr']['::var_sylabe_permitfollowcss']="Autorise ou non l’utilisation d’une feuille de style (CSS) personnalisée.";
		$this->_table['fr-fr']['::var_sylabe_permitphpcss']="Autorise ou non l'utilisation de code php dans la feuille de style (CSS).";
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		$this->_table['fr-fr']['::none']='';
		*/

        /* à faire...

		Ajout des propriétés de l'entité
		Aucun mot de passe n'est définit
		Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.
		Ce nouveau texte va remplacer un objet existant!
		Ce texte remplace un objet existant.
		Création d'une nouvelle entité dépendante de l'entité courante.
		Création d'une nouvelle entité indépendante.
		La clé publique %s a été créé.
		La clé publique n'a pas été créé !!!
		La clé privée %s a été créé.
		La clé privée n'a pas été créé !!!
		La génération de la nouvelle entité a échouée !
		Lancer une mise à jour de tous les composants.
		Lancer une régénération des composants manquants.
		Le mot de passe n'est pas valide !!!
		Le mot de passe est valide.
		Le transfert a échoué.
		Les mots de passe ne sont pas identiques.
		L'affichage de l'objet a été tronqué.
		L'opération peut prendre un peu de temps.
		Pas de dossier temporaire, les téléchargements vont échouer !
		Rafraichir la vue et charger les nouvelles versions.
		Rechercher les mises à jour de l'objet.
		Restreint à des liens <u>signés</u> par l'entité <i>%s</i> !
		Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.
		Transmettre la protection de l'objet %s à l'entité %s.
		Voir les propriétés de l'objet.
		Voir les propriétés et l'intégralité de l'objet.
		(1) Impossible d'écrire dans le dossier temporaire, les téléchargements vont échouer !
		(2) Impossible d'écrire dans le dossier temporaire, les téléchargements vont échouer !!
		L'affichage de la liste des objets est désactivé pour les entités non déverrouillées.

		*/
    }
}


/**
 * Cette application permet la prise en compte dans l'interface de la langue anglaise.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class ModuleTranslateENEN extends Modules
{
    protected $MODULE_TYPE = 'Traduction';
    protected $MODULE_NAME = '::translateModule:en-en:ModuleName';
    protected $MODULE_MENU_NAME = '::translateModule:en-en:MenuName';
    protected $MODULE_COMMAND_NAME = 'en-en';
    protected $MODULE_DEFAULT_VIEW = '';
    protected $MODULE_DESCRIPTION = '::translateModule:en-en:ModuleDescription';
    protected $MODULE_VERSION = '020220824';
    protected $MODULE_AUTHOR = 'Projet nebule';
    protected $MODULE_LICENCE = '(c) GLPv3 nebule 2013-2020';
    protected $MODULE_LOGO = '7796077f1b865951946dd40ab852f6f4d21e702e7c4f47bd5fa6cb9ce94a4c5f';
    protected $MODULE_HELP = '::translateModule:en-en:ModuleHelp';
    protected $MODULE_INTERFACE = '3.0';

    protected $MODULE_REGISTERED_VIEWS = array();
    protected $MODULE_REGISTERED_ICONS = array('7796077f1b865951946dd40ab852f6f4d21e702e7c4f47bd5fa6cb9ce94a4c5f');
    protected $MODULE_APP_TITLE_LIST = array();
    protected $MODULE_APP_ICON_LIST = array();
    protected $MODULE_APP_DESC_LIST = array();
    protected $MODULE_APP_VIEW_LIST = array();


    /**
     * Configuration spécifique au module.
     *
     * @return void
     */
    public function initialisation(): void
    {
        $this->_nebuleInstance = $this->_applicationInstance->getNebuleInstance();
        $this->_initTable();
    }


    /**
     * Ajout de fonctionnalités à des points d'ancrage.
     *
     * @param string $hookName
     * @param string $object
     * @return array
     */
    public function getHookList(string $hookName, string $object = 'none'): array
    {
        if ($object == 'none') {
            $object = $this->_applicationInstance->getCurrentObject();
        }
        if (is_a($object, 'Node')) {
            $object = $object->getID();
        }

        $hookArray = array();
        switch ($hookName) {
            case 'helpLanguages':
                $hookArray[0]['name'] = $this->_traduction('::::Bienvenue', $this->MODULE_COMMAND_NAME);
                $hookArray[0]['icon'] = $this->MODULE_LOGO;
                $hookArray[0]['desc'] = $this->_traduction('::translateModule:' . $this->MODULE_COMMAND_NAME . ':ModuleDescription', $this->MODULE_COMMAND_NAME);
                $hookArray[0]['link'] = '?mod=hlp&view=lang&' . Traductions::DEFAULT_COMMAND_LANGUAGE . '=' . $this->MODULE_COMMAND_NAME;
                break;
        }
        return $hookArray;
    }


    /**
     * Initialisation de la table de traduction.
     *
     * @return void
     */
    protected function _initTable(): void
    {
        $this->_table['fr-fr']['::translateModule:en-en:ModuleName'] = 'Anglais (Angleterre)';
        $this->_table['en-en']['::translateModule:en-en:ModuleName'] = 'English (England)';
        $this->_table['es-co']['::translateModule:en-en:ModuleName'] = 'Inglés (Inglaterra)';
        $this->_table['fr-fr']['::translateModule:en-en:MenuName'] = 'Anglais (Angleterre)';
        $this->_table['en-en']['::translateModule:en-en:MenuName'] = 'English (England)';
        $this->_table['es-co']['::translateModule:en-en:MenuName'] = 'Inglés (Inglaterra)';
        $this->_table['fr-fr']['::translateModule:en-en:ModuleDescription'] = "Traduction de l'interface en Anglais.";
        $this->_table['en-en']['::translateModule:en-en:ModuleDescription'] = 'Interface translation in English.';
        $this->_table['es-co']['::translateModule:en-en:ModuleDescription'] = 'Interface translation in English.';
        $this->_table['fr-fr']['::translateModule:en-en:ModuleHelp'] = "Ce module permet de mettre en place la traduction de l'interface de sylabe et des applications en Anglais.";
        $this->_table['en-en']['::translateModule:en-en:ModuleHelp'] = 'This module permit to translate the sylabe interface in English.';
        $this->_table['es-co']['::translateModule:en-en:ModuleHelp'] = 'This module permit to translate the sylabe interface in English.';

        // Salutations.
        $this->_table['en-en']['::::Bienvenue'] = 'Welcome.';

        // Traduction de mots.
        $this->_table['en-en']['::Password'] = 'Password';
        $this->_table['en-en']['::yes'] = 'Yes';
        $this->_table['en-en']['::no'] = 'No';
        $this->_table['en-en']['::::SecurityChecks'] = 'Security checks';
        $this->_table['en-en']['::Lock'] = 'Locking';
        $this->_table['en-en']['::Unlock'] = 'Unlocking';
        $this->_table['en-en']['::EntityLocked'] = 'Entity locked. Unlock?';
        $this->_table['en-en']['::EntityUnlocked'] = 'Entity unlocked. Lock?';
        $this->_table['en-en']['::::INFO'] = 'Information';
        $this->_table['en-en']['::::OK'] = 'OK';
        $this->_table['en-en']['::::INFORMATION'] = 'Message';
        $this->_table['en-en']['::::WARN'] = 'WARNING!';
        $this->_table['en-en']['::::ERROR'] = 'ERROR!';
        $this->_table['en-en']['::::RESCUE'] = 'Rescue mode!';
        $this->_table['en-en']['::::icon:DEFAULT_ICON_LO'] = 'Object';
        $this->_table['en-en']['::::HtmlHeadDescription'] = 'Client web page sylabe for nebule.';
        $this->_table['en-en']['::::Experimental'] = '[Experimental]';
        $this->_table['en-en']['::::Developpement'] = '[Under developpement]';
        $this->_table['en-en']['::::help'] = 'Help';
        $this->_table['en-en']['nebule/objet'] = 'Object';
        $this->_table['en-en']['nebule/objet/hash'] = 'Hash type';
        $this->_table['en-en']['nebule/objet/type'] = 'MIME type';
        $this->_table['en-en']['nebule/objet/taille'] = 'Size';
        $this->_table['en-en']['nebule/objet/nom'] = 'Name';
        $this->_table['en-en']['nebule/objet/prefix'] = 'Prefix';
        $this->_table['en-en']['nebule/objet/prenom'] = 'First name';
        $this->_table['en-en']['nebule/objet/suffix'] = 'Suffix';
        $this->_table['en-en']['nebule/objet/surnom'] = 'Nikename';
        $this->_table['en-en']['nebule/objet/postnom'] = 'Nikename';
        $this->_table['en-en']['nebule/objet/entite'] = 'Entity';
        $this->_table['en-en']['nebule/objet/entite/type'] = 'Type';
        $this->_table['en-en']['nebule/objet/date'] = 'Date';
        $this->_table['en-en']['nebule/objet/date/annee'] = 'Year';
        $this->_table['en-en']['nebule/objet/date/mois'] = 'Month';
        $this->_table['en-en']['nebule/objet/date/jour'] = 'Day';
        $this->_table['en-en']['nebule/objet/date/heure'] = 'Hour';
        $this->_table['en-en']['nebule/objet/date/minute'] = 'Minute';
        $this->_table['en-en']['nebule/objet/date/seconde'] = 'Second';
        $this->_table['en-en']['nebule/objet/date/zone'] = 'Zone de temps';
        $this->_table['en-en']['nebule/objet/emotion/colere'] = 'Annoying';
        $this->_table['en-en']['nebule/objet/emotion/degout'] = 'Bummed';
        $this->_table['en-en']['nebule/objet/emotion/surprise'] = 'Surprised';
        $this->_table['en-en']['nebule/objet/emotion/peur'] = 'disturbed';
        $this->_table['en-en']['nebule/objet/emotion/interet'] = 'Interested';
        $this->_table['en-en']['nebule/objet/emotion/joie'] = 'I like';
        $this->_table['en-en']['nebule/objet/emotion/confiance'] = 'Agree';
        $this->_table['en-en']['nebule/objet/emotion/tristesse'] = 'Sadly';
        $this->_table['en-en']['nebule/objet/entite/localisation'] = 'Localisation';
        $this->_table['en-en']['nebule/objet/entite/maitre/securite'] = 'Master of security';
        $this->_table['en-en']['nebule/objet/entite/maitre/code'] = 'Master of code';
        $this->_table['en-en']['nebule/objet/entite/maitre/annuaire'] = 'Master of directory';
        $this->_table['en-en']['nebule/objet/entite/maitre/temps'] = 'Master of time';

        // Type mime
        $this->_table['en-en'][nebule::REFERENCE_OBJECT_TEXT] = 'RAW text';
        $this->_table['en-en']['application/x-pem-file'] = 'Entity';
        $this->_table['en-en']['image/jpeg'] = 'JPEG picture';
        $this->_table['en-en']['image/png'] = 'PNG picture';
        $this->_table['en-en']['application/x-bzip2'] = 'Archive BZIP2';
        $this->_table['en-en']['text/html'] = 'HTML page';
        $this->_table['en-en']['application/x-php'] = 'PHP code';
        $this->_table['en-en']['text/x-php'] = 'PHP code';
        $this->_table['en-en']['text/css'] = 'Cascading Style Sheet CSS';
        $this->_table['en-en']['audio/mpeg'] = 'Audio MP3';
        $this->_table['en-en']['audio/x-vorbis+ogg'] = 'Audio OGG';
        $this->_table['en-en']['application/x-encrypted/rsa'] = 'Encrypted';
        $this->_table['en-en']['application/x-encrypted/aes-256-ctr'] = 'Encrypted';
        $this->_table['en-en']['application/x-folder'] = 'Folder';

        // Espressions courtes.
        $this->_table['en-en']['::::IDprivateKey'] = 'Private ID';
        $this->_table['en-en']['::::IDpublicKey'] = 'Public ID';
        $this->_table['en-en']['::Version'] = 'Version';
        $this->_table['en-en']['::UniqueID'] = 'Universal identifier : %s';
        $this->_table['en-en']['::GroupeFerme'] = 'Closed group';
        $this->_table['en-en']['::GroupeOuvert'] = 'Opened group';
        $this->_table['en-en']['::ConversationFermee'] = 'Closed conversation';
        $this->_table['en-en']['::ConversationOuverte'] = 'Opened conversation';
        $this->_table['en-en']['::progress'] = 'In progress...';
        $this->_table['en-en']['::seeMore'] = 'See more...';
        $this->_table['en-en']['::noContent'] = '(content not available)';
        $this->_table['en-en']['::appSwitch'] = 'Switch application';
        $this->_table['en-en']['::menu'] = 'Menu';
        $this->_table['en-en']['::menuDesc'] = 'Page with full menu';
        $this->_table['en-en']['::EmptyList'] = 'Empty list.';
        $this->_table['en-en']['::ChangeLanguage'] = 'Change language';
        $this->_table['en-en']['::SelectUser'] = 'Select user';
        $this->_table['en-en']['::MarkAdd'] = 'Mark';
        $this->_table['en-en']['::MarkRemove'] = 'Unmark';
        $this->_table['en-en']['::MarkRemoveAll'] = 'Unmark all';
        $this->_table['en-en']['::Synchronize'] = 'Synchronize';

        // Phrases complètes.
        $this->_table['en-en'][':::display:content:errorBan'] = "This object is banned, it can't be displayed!";
        $this->_table['en-en'][':::display:content:warningTaggedWarning'] = "This object is marked as dangerous, be carfull with it's content!";
        $this->_table['en-en'][':::display:content:ObjectProctected'] = "This object is marked as protected!";
        $this->_table['en-en'][':::display:content:warningObjectProctected'] = "This object is marked as protected, be careful when it's content is displayed in public!";
        $this->_table['en-en'][':::display:content:OK'] = "This object is valid, it's content have been checked.";
        $this->_table['en-en'][':::display:content:warningTooBig'] = "This object is too big, it's content have not been checked!";
        $this->_table['en-en'][':::display:content:errorNotDisplayable'] = "This object can't be displayed!";
        $this->_table['en-en'][':::display:content:errorNotAvailable'] = "This object is not available, it can't be displayed!";
        $this->_table['en-en'][':::display:content:notAnObject'] = 'This reference object do not have content.';
        $this->_table['en-en'][':::display:content:ObjectHaveUpdate'] = 'This object have been updated to:';
        $this->_table['en-en'][':::display:content:Activated'] = 'This object is activated.';
        $this->_table['en-en'][':::display:content:NotActivated'] = 'This object is not activated.';
        $this->_table['en-en'][':::display:link:OK'] = 'This link is valid.';
        $this->_table['en-en'][':::display:link:errorInvalid'] = 'This link is invalid!';
        $this->_table['en-en'][':::warn_ServNotPermitWrite'] = 'This server do not permit modifications.';
        $this->_table['en-en'][':::warn_flushSessionAndCache'] = 'All datas of this connexion have been flushed.';
        $this->_table['en-en'][':::err_NotPermit'] = 'Non autorisé sur ce serveur !';
        $this->_table['en-en'][':::act_chk_errCryptHash'] = "La fonction de prise d'empreinte cryptographique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptHashkey'] = "La taille de l'empreinte cryptographique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptHashkey'] = "La taille de l'empreinte cryptographique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptSym'] = "La fonction de chiffrement cryptographique symétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptSymkey'] = "La taille de clé de chiffrement cryptographique symétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errCryptAsym'] = "La fonction de chiffrement cryptographique asymétrique ne fonctionne pas correctement !";
        $this->_table['en-en'][':::act_chk_warnCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est trop petite !";
        $this->_table['en-en'][':::act_chk_errCryptAsymkey'] = "La taille de clé de chiffrement cryptographique asymétrique est invalide !";
        $this->_table['en-en'][':::act_chk_errBootstrap'] = "L'empreinte cryptographique du bootstrap est invalide !";
        $this->_table['en-en'][':::act_chk_warnSigns'] = 'La vérification des signatures de liens est désactivée !';
        $this->_table['en-en'][':::act_chk_errSigns'] = 'La vérification des signatures de liens ne fonctionne pas !';

        $this->_table['en-en'][':::display:object:flag:protected'] = 'This object is protected.';
        $this->_table['en-en'][':::display:object:flag:unprotected'] = 'This object is not protected.';
        $this->_table['en-en'][':::display:object:flag:obfuscated'] = 'This object is obfuscated.';
        $this->_table['en-en'][':::display:object:flag:unobfuscated'] = 'This object is not obfuscated.';
        $this->_table['en-en'][':::display:object:flag:locked'] = 'This entity is unlocked.';
        $this->_table['en-en'][':::display:object:flag:unlocked'] = 'This entity is locked.';
        $this->_table['en-en'][':::display:object:flag:activated'] = 'This object is activated.';
        $this->_table['en-en'][':::display:object:flag:unactivated'] = 'This object is not activated.';

        /*
		$this->_table['en-en']['Lien']='Link';
		$this->_table['en-en']['-indéfini-']='-undefined-';
		$this->_table['en-en']['-indéterminé-']='-undetermined-';
		$this->_table['en-en']['Affichage']='Display';
		$this->_table['en-en']['Aide']='Help';
		$this->_table['en-en']['Algorithme']='Algorithm';
		$this->_table['en-en']['Ambiguë']='Ambiguous';
		$this->_table['en-en']['Ambigue']='Ambiguous';
		$this->_table['en-en']['Année']='Year';
		$this->_table['en-en']['Attention !']='Warning!';
		$this->_table['en-en']['Aucun']='None';
		$this->_table['en-en']['Aucune']='None';
		$this->_table['en-en']['Bannissement']='Banishment';
		$this->_table['en-en']['nebule/avis/beau']='Beautiful';
		$this->_table['en-en']['beau']='beautiful';
		$this->_table['en-en']['bits']='bits';
		$this->_table['en-en']['nebule/avis/bon']='Good';
		$this->_table['en-en']['Bon']='Good';
		$this->_table['en-en']['Bootstrap']='Bootstrap';
		$this->_table['en-en']['Caractéristiques']='Specifications';
		$this->_table['en-en']['Charger']='Load';
		$this->_table['en-en']['Chiffré']='Encrypted';
		$this->_table['en-en']['Chiffrement']='Encryption';
		$this->_table['en-en']['nebule/avis/clair']='Clear';
		$this->_table['en-en']['Clair']='Clear';
		$this->_table['en-en']['Commenter']='Comment on';
		$this->_table['en-en']['nebule/avis/complet']='Complete';
		$this->_table['en-en']['Complet']='Complete';
		$this->_table['en-en']['Contrariant']='Annoying';
		$this->_table['en-en']['Cryptographie']='Cryptography';
		$this->_table['en-en']['Date']='Date';
		$this->_table['en-en']['Déchiffrement']='Decrytion';
		$this->_table['en-en']['nebule/avis/ambigue']='Ambiguous';
		$this->_table['en-en']['Dégôuté']='Bummed';
		$this->_table['en-en']['Déprotéger']='Unprotect';
		$this->_table['en-en']['Description']='Description';
		$this->_table['en-en']['Déverrouillage']='Unlocking';
		$this->_table['en-en']['Déverrouiller']='Unlock';
		$this->_table['en-en']["D'accord"]='Agree';
		$this->_table['en-en']['Émotion']='Emotion';
		$this->_table['en-en']['Emotion']='Emotion';
		$this->_table['en-en']['Empreinte']='Footprint';
		$this->_table['en-en']['Entité']='Entity';
		$this->_table['en-en']['Entités']='Entities';
		$this->_table['en-en']['ERREUR !']='ERROR!';
		$this->_table['en-en']['ERROR']='ERROR';
		$this->_table['en-en']['Étonnant']='Surprised';
		$this->_table['en-en']['Etonnant']='Surprised';
		$this->_table['en-en']['Expérimental']='Experimental';
		$this->_table['en-en']['nebule/avis/faux']='False';
		$this->_table['en-en']['Faux']='False';
		$this->_table['en-en']['nebule/avis/génial']='Great';
		$this->_table['en-en']['Génial']='Great';
		$this->_table['en-en']['Genre']='Gender';
		$this->_table['en-en']['Heure']='Hour';
		$this->_table['en-en']['humain']='human';
		$this->_table['en-en']['Identifiant']='Identifier';
		$this->_table['en-en']['nebule/avis/important']='Important';
		$this->_table['en-en']['Important']='Important';
		$this->_table['en-en']['Inconnu']='Unknown';
		$this->_table['en-en']['nebule/avis/incomplet']='Incomplete';
		$this->_table['en-en']['Incomplet']='Incomplete';
		$this->_table['en-en']['nebule/avis/incomprehensible']='Unintelligible';
		$this->_table['en-en']['Incompréhensible']='Unintelligible';
		$this->_table['en-en']['Inquiétant']='Disturbed';
		$this->_table['en-en']['Intéressé']='Interested';
		$this->_table['en-en']['nebule/avis/inutile']='Useless';
		$this->_table['en-en']['Inutile']='Useless';
		$this->_table['en-en']['Invalide']='Invalid';
		$this->_table['en-en']["J'aime"]='I like';
		$this->_table['en-en']["J'approuve"]='Agree';
		$this->_table['en-en']['Jour']='Day';
		$this->_table['en-en']['Lien']='Link';
		$this->_table['en-en']['Liens']='Links';
		$this->_table['en-en']["L'objet"]='The object';
		$this->_table['en-en']['nebule/avis/mauvais']='Bad';
		$this->_table['en-en']['Mauvais']='Bad';
		$this->_table['en-en']['Minute']='Minute';
		$this->_table['en-en']['nebule/avis/moche']='Ugly';
		$this->_table['en-en']['Moche']='Ugly';
		$this->_table['en-en']['Mois']='Month';
		$this->_table['en-en']['nebule/avis/moyen']='Middling';
		$this->_table['en-en']['Moyen']='Middling';
		$this->_table['en-en']['Navigation']='Navigation';
		$this->_table['en-en']['Nœud']='Node';
		$this->_table['en-en']['Noeud']='Node';
		$this->_table['en-en']['Nœuds']='Nodes';
		$this->_table['en-en']['Noeuds']='Nodes';
		$this->_table['en-en']['NOK']='NOK';
		$this->_table['en-en']['nebule/avis/nul']='Zero';
		$this->_table['en-en']['Nul']='Zero';
		$this->_table['en-en']['Objet']='Object';
		$this->_table['en-en']['Objets']='Objects';
		$this->_table['en-en']['octet']='byte';
		$this->_table['en-en']['octets']='bytes';
		$this->_table['en-en']['OK']='OK';
		$this->_table['en-en']['nebule/avis/perime']='Obsolete';
		$this->_table['en-en']['Périmé']='Obsolete';
		$this->_table['en-en']['privée']='private';
		$this->_table['en-en']['Protection']='Protection';
		$this->_table['en-en']['Protéger']='Protect';
		$this->_table['en-en']['publique']='public';
		$this->_table['en-en']['Rafraîchir']='Refresh';
		$this->_table['en-en']['Recherche']='Search';
		$this->_table['en-en']['Rechercher']='Searching';
		$this->_table['en-en']['Régénération']='Regeneration';
		$this->_table['en-en']['Répéter']='Repeat';
		$this->_table['en-en']['robot']='robot';
		$this->_table['en-en']['Seconde']='Second';
		$this->_table['en-en']['Source']='Source';
		$this->_table['en-en']['Synchroniser']='Synchronize';
		$this->_table['en-en']['Suppression']='Suppression';
		$this->_table['en-en']['Supprimer']='Suppress';
		$this->_table['en-en']['Taille']='Size';
		$this->_table['en-en']['Téléchargement']='Downloading';
		$this->_table['en-en']['Télécharger']='Download';
		$this->_table['en-en']['Transfert']='Transfer';
		$this->_table['en-en']['Transmettre']='Transmit';
		$this->_table['en-en']['Tristement']='Sadly';
		$this->_table['en-en']['Valeur']='Value';
		$this->_table['en-en']['Validité']='Validity';
		$this->_table['en-en']['Version']='Version';
		$this->_table['en-en']['Verrouillage']='Locking';
		$this->_table['en-en']['Verrouiller']='Lock on';
		$this->_table['en-en']['Voir']='See';
		$this->_table['en-en']['nebule/avis/vrai']='True';
		$this->_table['en-en']['Vrai']='True';
		*/

        /*
		$this->_table['en-en']['Creation nouveau lien']='Creating a new link';
		$this->_table['en-en']['::::GenNewEnt']='Generate new entity';
		$this->_table['en-en']['%01.0f liens lus,']='%01.0f links readed,';
		$this->_table['en-en']['%01.0f liens vérifiés,']='%01.0f links checked,';
		$this->_table['en-en']['%01.0f objets vérifiés.']='%01.0f objects checked.';
		$this->_table['en-en']['Accès au bootstrap.']='Access to the bootstrap.';
		$this->_table['en-en']["Afficher l'objet"]='View object';
		$this->_table['en-en']['Aide en ligne']='Online help';
		$this->_table['en-en']['::::AddNotice2Obj']='Add a comment on this object';
		$this->_table['en-en']['Ajout du nouveau lien non autorisé.']='Adding new link is not allowed.';
		$this->_table['en-en']['::::HashAlgo']='Fingerprinting algorithm';
		$this->_table['en-en']['::::SymCryptAlgo']='Symmetric encryption algorithm';
		$this->_table['en-en']['::::AsymCryptAlgo']='Asymmetric encryption algorithm';
		$this->_table['en-en']['Annuler bannissement']='Cancel ban';
		$this->_table['en-en']['Archive BZIP2']='Archive BZIP2';
		$this->_table['en-en']['Aucun objet à afficher.']='No object to display.';
		$this->_table['en-en']['Aucun objet dérivé à afficher.']='No derived object to display.';
		$this->_table['en-en']['Audio MP3']='Audio MP3';
		$this->_table['en-en']['Audio OGG']='Audio OGG';
		$this->_table['en-en']['::::Switch2Ent']='Switch to this entity';
		$this->_table['en-en']['::::LoadObj2Browser']='Directly load the object code in the browser.';
		$this->_table['en-en']['Chiffré, non affichable.']='Encrypted, can not be displayed.';
		$this->_table['en-en']['Code PHP']='PHP code';
		$this->_table['en-en']['Connexion non sécurisée']='Connexion not secure';
		$this->_table['en-en']['::::AskEntSyncObj']='Ask the entity to kindly synchronize the object.';
		$this->_table['en-en']["Déverrouillage de l'entité"]='Unlocking the entity';
		$this->_table['en-en']['Émotions et avis']='Emotions and opinions';
		$this->_table['en-en']['Empreinte cryptographique du bootstrap']='Cryptographic fingerprint of the bootstrap';
		$this->_table['en-en']['Entité déverrouillée.']='Unlocked entity.';
		$this->_table['en-en']['Entité en cours.']='Current entity.';
		$this->_table['en-en']['Entité verrouillée (non connectée).']='Locked entity (not connected).';
		$this->_table['en-en']['Essayer plutôt']='Try rather';
		$this->_table['en-en']['est à jour.']='is up to date.';
		$this->_table['en-en']['Erreur lors du chiffrement !']='Error encryption!';
		$this->_table['en-en']['Erreur lors du déchiffrement !']='Error decryption!';
		$this->_table['en-en']['Feuille de style CSS']='Cascading Style Sheet CSS';
		$this->_table['en-en']["Fil d'actualités"]='Newsfeed';
		$this->_table['en-en']['Génération de miniatures']='Thumbnail generation';
		$this->_table['en-en']['Identifiant universel']='Universal identifier';
		$this->_table['en-en']['Image JPEG']='JPEG picture';
		$this->_table['en-en']['Image PNG']='PNG picture';
		$this->_table['en-en']['Informations sur le serveur']='Server Informations';
		$this->_table['en-en']['Lien de mise à jour']='Link update';
		$this->_table['en-en']['Lien écrit.']='Link writed.';
		$this->_table['en-en']['Lien invalide']='Invalid link';
		$this->_table['en-en']['Lien non vérifié']='Unaudited link';
		$this->_table['en-en']['Lien valide']='Valid link';
		$this->_table['en-en']['::::EncryptedFor']='The object is encrypted for';
		$this->_table['en-en']['mis à jour vers %s.']='updated to %s.';
		$this->_table['en-en']['Mise à jour']='Update';
		$this->_table['en-en']['Mise à jour de sylabe']='Update for sylabe';
		$this->_table['en-en']['Mise à jour de tous les composants.']='Update all components.';
		$this->_table['en-en']['Mise en place du mot de passe sur la clé privée.']='Implementation of the password on the private key.';
		$this->_table['en-en']["Mode d'affichage"]='Display mode';
		$this->_table['en-en']["Naviguer autour de l'objet"]='Navigate around the object';
		$this->_table['en-en']['Nœuds connus']='known nodes';
		$this->_table['en-en']['Nom complet']='Full name';
		$this->_table['en-en']['Nom de variable']='Variable name';
		$this->_table['en-en']['Non affichable.']='Not displayable.';
		$this->_table['en-en']['Non déverrouillée.']='Not unlocked.';
		$this->_table['en-en']['Non disponible.']='Not available.';
		$this->_table['en-en']['Non fonctionnel.']='Nonfunctional.';
		$this->_table['en-en']['Objet de test']='Test object';
		$this->_table['en-en']['Page HTML']='HTML page';
		$this->_table['en-en']['Objet non disponible localement.']='Object not available locally.';
		$this->_table['en-en']['Pas de mise à jour connue de cet objet.']='No update known for this object.';
		$this->_table['en-en']["Pas d'accord"]='Disagree';
		$this->_table['en-en']["Pas d'action à traiter."]='No action to deal.';
		$this->_table['en-en']['Pas un nœud']='Not a node';
		$this->_table['en-en']['Pas un noeud']='Not a node';
		$this->_table['en-en']["Protection de l'objet"]='Protection of the object';
		$this->_table['en-en']["Protéger l'objet."]='Protect the object.';
		$this->_table['en-en']['Rafraîchir la vue']='Refresh display';
		$this->_table['en-en']['Rafraichir la vue']='Refresh display';
		$this->_table['en-en']['Rafraichir la vue et charger les nouvelles versions.']='Refresh display and download the new versions.';
		$this->_table['en-en']['Recharger la page.']='Reload the page.';
		$this->_table['en-en']['Régénération des composants manquants.']='Regeneration of missing components.';
		$this->_table['en-en']["Revenir au menu des capacités de transfert d'objets et de liens"]='Back to main menu transfer capabilities of objects and links';
		$this->_table['en-en']['Session utilisateur']='User session';
		$this->_table['en-en']["Supprimer l'avis."]='Remove the notice.';
		$this->_table['en-en']["Supprimer l'émotion."]='Remove the emotion.';
		$this->_table['en-en']["Synchronisation d'un objet non reconnu localement"]='Synchronization of an object not recognized locally';
		$this->_table['en-en']['Taille des clés de chiffrement asymétrique']='Key size for asymmetric encryption';
		$this->_table['en-en']['Taille des clés de chiffrement symétrique']='Key size for symmetric encryption';
		$this->_table['en-en']['Taille des empreintes cryptographiques']='Key size for cryptographic fingerprints';
		$this->_table['en-en']['::::DownloadAsFile']='Download object as file.';
		$this->_table['en-en']['Texte brute']='RAW text';
		$this->_table['en-en']["Toutes les capacités de transfert d'objets et de liens"]='All transfer capabilities of objects and links';
		$this->_table['en-en']["Transférer la protection à l'entité"]='Transfer the protection to the entity';
		$this->_table['en-en']['Type de clé']='Key type';
		$this->_table['en-en']['type inconnu']='unknown type';
		$this->_table['en-en']['Type MIME']='MIME type';
		$this->_table['en-en']['URL de connexion']='Connection URL';
		$this->_table['en-en']['::::VerifLinkSign']='Vérification des signatures de liens';
		$this->_table['en-en']["Verrouillage (déconnexion) de l'entité."]='Lock (disconnection) the entity.';
		$this->_table['en-en']["Verrouiller l'entité."]='Lock the entity.';
		$this->_table['en-en']["Variables d'environnement"]='Environment variables';
		$this->_table['en-en']['Voir déchiffré']='See decrypted';
		$this->_table['en-en']['Voir les liens']='See links';
		$this->_table['en-en']['Voir tout']='See all';
		$this->_table['en-en']['Zone de temps']='Timezone';
		*/

        /*
		$this->_table['en-en']['Cet objet a été mise à jour vers']='This object have been updated to';
		$this->_table['en-en'][':::warn_InvalidPubKey']='The public key seems to be invalid!';
		$this->_table['en-en'][':::nav_aff_MaxFileSize']='The maximum file size must not exceed %.0f characters (bytes).';
		$this->_table['en-en'][':::nav_aff_MaxTextSize']='The maximum size must not exceed %.0f characters (bytes).';
		$this->_table['en-en']["Le lien n'a pas été écrit !"]="The link can't be writed !";
		$this->_table['en-en']['Le serveur à pris %01.4fs pour calculer la page.']='The computer took %01.4fs to calculate the page.';
		$this->_table['en-en']["L'opération peut prendre un peu de temps."]="L'opération peut prendre un peu de temps.";
		$this->_table['en-en'][':::warn_NoAudioTagSupport']='Your browser does not support the audio tag.';
		$this->_table['en-en'][':::err_CantWriteLink']="Une erreur s'est produite lors de l'écriture d'un lien !";
		$this->_table['en-en'][':::warn_CantGenThumNoGD']="Les miniatures de l'image n'ont pas été générées (lib GD2 no présente).";
		$this->_table['en-en'][':::err_CantAnalysImg']="Erreur lors de l'analyse de l'image.";
		$this->_table['en-en'][':::warn_CantGenThumUnknowImg']="Les miniatures de l'image n'ont pas été générées. Le type d'image n'est pas reconnu.";
		$this->_table['en-en'][':::hlp_DescObjLnk']="Le monde de <i>sylabe</i> est peuplé d'objets et de liens.";
		$this->_table['en-en'][':::ent_create_WarnAutonomNewEnt']='Aucune entité déverrouillée, donc la nouvelle entité est <u>obligatoirement autonome</u>.';
		$this->_table['en-en'][':::ent_create_WarnMustHaveMDP']='Si la nouvelle entité est <b>autonome</b>, un <u>mot de passe est obligatoire</u>. Sinon, le mot de passe est géré automatiquement.';
		$this->_table['en-en'][':::act_MustUnlockEnt']="Il est nécessaire de déverrouiller l'entité pour pouvoir agir sur les objets et les liens.";
		$this->_table['en-en'][':::warn_NoObjDesc']="Pas de description pour ce type d'objet.";
		$this->_table['en-en'][':::warn_LoadObj2Browser']="Charger directement le code de l'objet dans votre navigateur peut être dangereux !!!";
		$this->_table['en-en'][':::aff_protec_Protected']="L'objet est marqué comme protégé.";
		$this->_table['en-en'][':::aff_protec_Unprotected']="L'objet n'est pas marqué comme protégé.";
		$this->_table['en-en'][':::aff_protec_RemProtect']="Retirer la protection de l'objet.";
		$this->_table['en-en'][':::aff_protec_FollowProtTo']="Transférer la protection à l'entité";
		$this->_table['en-en'][':::aff_sync_SyncLnkObj']="Synchronize object's links.";
		$this->_table['en-en'][':::aff_sync_SyncObj']="Synchroniser le contenu de l'objet.";
		$this->_table['en-en'][':::aff_sync_SearchUpdate']="Search object's updates.";
		$this->_table['en-en'][':::aff_supp_SuppObj']='Suppress this object.';
		$this->_table['en-en'][':::aff_supp_RemSuppObj']='Cancel supression for the object.';
		$this->_table['en-en'][':::aff_supp_BanObj']='Suppress and ban this object.';
		$this->_table['en-en'][':::aff_supp_RemBanObj']='Cancel ban for the object.';
		$this->_table['en-en'][':::aff_supp_ForceSuppObj']='Force suppression of this object on the server.';
		$this->_table['en-en'][':::aff_node_IsNode']='This object is a node.';
		$this->_table['en-en'][':::aff_node_IsnotNode']='This object is not a node.';
		$this->_table['en-en'][':::aff_node_DefineNode']='Define this object as a node.';
		$this->_table['en-en'][':::aff_node_RemDefineNode']='Cancel define this object as a node.';

		// Blocs de texte.
		$this->_table['en-en']['::hlp_msgaffok']='Ceci est un message pour une opération se terminant sans erreur.';
		$this->_table['en-en']['::hlp_msgaffwarn']="Ceci est un message d'avertissement.";
		$this->_table['en-en']['::hlp_msgafferror']="Ceci est un message d'erreur.";
		$this->_table['en-en']['::hlp_text']="";
		$this->_table['en-en']['::bloc_hlp_head']='Online help';
		$this->_table['en-en']['::bloc_hlp_head_hlp']='This is le main help page. In progress...';
		$this->_table['en-en']['::bloc_metrolog']='Metrology';
		$this->_table['en-en']['::bloc_metrolog_hlp']="La partie métrologie donne les mesures de temps globaux et partiels pour le traitement et l'affichage de la page web.";
		$this->_table['en-en']['::bloc_aff_head_hlp']='Display the object';
		$this->_table['en-en']['::bloc_aff_chent']='Switch to this entity';
		$this->_table['en-en']['::bloc_aff_chent_hlp']='Switch to this entity';
		$this->_table['en-en']['::bloc_aff_dwload']='Download and transmission';
		$this->_table['en-en']['::bloc_aff_dwload_hlp']='Download and transmission';
		$this->_table['en-en']['::bloc_aff_protec']='Protect';
		$this->_table['en-en']['::bloc_aff_protec_hlp']="<p>1 - The object can be protected or unprotected, This means encrypted or not.</p>\n
		<p>2 - This command is used to protect the object, it will automatically be marked as deleted <u>and</u> locally removed.</p>\n
		<p>3 - This command is used to remove the protection of the object and restore. The deletion mark will be canceled.</p>\n
		<p>4 - This command is used to transmit the protection of the object to another entity. The entity may see this protected object and also cancel or relay this protection.</p>\n
		<p><b>A data which have been transmitted to others, it is a data on which all control is irretrievably lost.</b></p>\n
		<p>An object that has been protected and is normally marked deleted and locally removed at the same time. It should not be publicly available, but it is not mandatory :<br />\n
		- If the object was distributed to other entities prior to its protection, others see it is marked deleted, thus deleting, but do take maybe not count.<br />\n
		- If this instance of <i>sylabe</i> hosts several entities and a local entity uses this object, it can't be locally removed. It will still be marked deleted.
		Only the entity instance owner may locally force the removal of the object.</p>";
		$this->_table['en-en']['::bloc_aff_sync']='Synchronisation and updating';
		$this->_table['en-en']['::bloc_aff_sync_hlp']='Synchronisation and updating';
		$this->_table['en-en']['::bloc_aff_supp']='Removal and banishment';
		$this->_table['en-en']['::bloc_aff_supp_hlp']='Removal and banishment';
		$this->_table['en-en']['::bloc_aff_node']='Node';
		$this->_table['en-en']['::bloc_aff_node_hlp']='Node';
		$this->_table['en-en']['::bloc_aff_deriv']='Derivation';
		$this->_table['en-en']['::bloc_aff_deriv_hlp']='Derivation';
		$this->_table['en-en']['::bloc_aff_maj']='Updates of the object';
		$this->_table['en-en']['::bloc_aff_maj_hlp']='Updates of the object';
		$this->_table['en-en']['::bloc_nav_head_hlp']="<p>In this display mode, the object is displayed in a reduced or truncated.
		This mode only allows you to have a global vision of the object, and focuses on its relations with other objects.</p>";
		$this->_table['en-en']['::bloc_nav_chent']='Switch to this entity';
		$this->_table['en-en']['::bloc_nav_chent_hlp']='Switch to this entity';
		$this->_table['en-en']['::bloc_nav_update']='Update';
		$this->_table['en-en']['::bloc_nav_update_hlp']='Update';
		$this->_table['en-en']['::bloc_nav_actu']='Newsfeed';
		$this->_table['en-en']['::bloc_nav_actu_hlp']='Newsfeed';
		$this->_table['en-en']['::bloc_log_head']="Entity's session";
		$this->_table['en-en']['::bloc_log_head_hlp']="Entity's session";
		$this->_table['en-en']['::bloc_obj_head']='The objects';
		$this->_table['en-en']['::bloc_obj_head_hlp']='The objects';
		$this->_table['en-en']['::bloc_nod_head']='Nodes and entry points';
		$this->_table['en-en']['::bloc_nod_head_hlp']='Nodes and entry points';
		$this->_table['en-en']['::bloc_nod_create']='Create a node';
		$this->_table['en-en']['::bloc_nod_create_hlp']="<p>Le champs attendu est un texte sans caractères spéciaux. Le texte sera transformé en un objet et celui-ci sera définit comme un nœud.
		Il n'est pas recommandé d'avoir des retours à la ligne dans ce texte.<br />
		Si un objet existe déjà avec ce texte, il sera simplement définit comme nœud.</p>";
		$this->_table['en-en']['::bloc_ent_head']='Management entities';
		$this->_table['en-en']['::bloc_ent_head_hlp']='Management entities';
		$this->_table['en-en']['::bloc_ent_known']='Known entities';
		$this->_table['en-en']['::bloc_ent_known_hlp']='Known entities';
		$this->_table['en-en']['::bloc_ent_ctrl']='Entities under control';
		$this->_table['en-en']['::bloc_ent_ctrl_hlp']='Entities under control';
		$this->_table['en-en']['::bloc_ent_unknown']='Unknown entities';
		$this->_table['en-en']['::bloc_ent_unknown_hlp']='Unknown entities';
		$this->_table['en-en']['::bloc_ent_follow']='Recognize an entity';
		$this->_table['en-en']['::bloc_ent_follow_hlp']="     <p>Au moins un des deux champs doit être renseigné.</p>\n
		<p>1 - L'<b>URL de présence</b> est une adresse sur le web (http, rfc2616) hébergeant un serveur nebule capable de délivrer publiquement les informations sur l'entité recherchée.
		Cette adresse web doit être valide, elle a typiquement la forme <i>http://puppetmaster.nebule.org</i> .<br />\n
		Si ce champs n'est pas renseigné, l'adresse sera recherchée automatiquement. Elle est dabort recherchée localement si l'entité est déjà connue sans être reconnue.
		Elle est ensuite par défaut replacée par l'adresse de l'annuaire par défaut, c'est à dire <i>asabiyya</i>. Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
		L'adresse ne doit pas être une adresse locale, c'est à dire <code>localhost</code>.<br />\n
		Si la valeur renseignée est fausse, la recherche a de bonnes chances de ne pas aboutir.\n
		</p>\n
		<p>2 - L'<b>Objet ID public</b> est <u>le</u> numéro unique identifiant sans ambiguité l'entité recherchée. Ce numéro en héxadécimal est l'empreinte de la clé publique de l'entité.
		Sans ce numéro, il sera impossible de récupérer l'objet contenant la clé publique, et donc il sera impossible de vérifier les liens que cette entité a généré.<br />\n
		Ce champs, si le numéro est connu, doit être très précisément renseigné et de façon complet. Si ce champs n'est pas renseigné, le numéro sera recherché automatiquement à l'adresse web renseignée.
		Si ce champs n'est pas renseigné, la recherche peut ne pas aboutir.<br />\n
		Si la valeur renseignée est fausse, la recherche n'aboutira pas.\n
		</p>";
		$this->_table['en-en']['::bloc_ent_create']='Create an entity';
		$this->_table['en-en']['::bloc_ent_create_hlp']="<p>Si l'entité créé est autonome, le champs <b>Mot de passe</b> doit être renseigné.</p>
		<p>1 - Le champs <b>Prénom</b> permet de donner un prénom à l'entité. Ce champs est facultatif.</p>
		<p>2 - Le champs <b>Nom</b> permet de donner un nom patronymique à l'entité. Ce champs est facultatif.</p>
		<p>3 - Le champs <b>Surnom</b> permet de donner un surnom à l'entité. Ce champs est facultatif.</p>
		<p>4 - Le choix du <b>Type</b> permet de catégoriser l'entité comme humain ou robot. Ce champs est facultatif.</p>
		<p>5 - Le champs <b>URL de présence</b> permet de donner à l'entité une localisation définie (http, rfc2616). Ce champs est facultatif.<br />
		C'est à cette localisation que l'on devra pouvoir synchroniser les liens et objets de l'entité. Si la localisation n'est pas ce serveur, il est de votre ressort de préparer cette localisation.<br />
		Par défaut, ce sera l'adresse web du serveur, sauf si c'est <code>localhost</code>.<br />
		Il est fortement déconseillé d'utiliser une adresse locale, c'est à dire <code>localhost</code>.</p>
		<p>6 - La case à cocher permet de préciser si l'entité créé est une entité autonome, c'est à dire ne dépendant pas de l'entité en cours.<br />
		Si l'entité n'est pas autonome, elle sera automatiquement reconnue comme dépendante de l'entité courante et un mot de passe sera automatiquement généré pour être utilisé par l'entité courante.<br />
		Si l'entité créé est autonome, un mot de passe est obligatoire.</p>
		<p>7 - Le champs <b>Mot de passe</b> permet donner un mot de passe <i>secret</i> pour pouvoir déverrouiller la nouvelle entité.<br />
		Ce mot de passe n'est pas celui de l'entité en cours (si déverrouillée) mais le mot de passe qui sera utilisé par la nouvelle entité.<br />
		Si l'entité créé est autonome, un mot de passe est obligatoire.<br />
		Le mot de passe doit être saisi deux fois pour prévenir toute erreur de saisi.
		<p>8 - La valeur de <b>Taille de clé</b> définit la longueur de la clé générée et donc permet de maîtriser directement sa solidité. Cependant, des clés trop longues pénalisent les performances.<br />
		Il est recommandé en 2014 de choisir une taille de clé au moins égale à <b>2048bits</b>.<br />
		Le choix par défaut est définit pour le serveur.</p>
		<p>9 - Le choix de l'<b>Algo chiffrement</b> définit le type d'algorithme utilisé. Il y a peu de choix actuellement.<br />
		Il est recommandé de choisir l'algorithme <b>RSA</b>.<br />
		Le choix par défaut est définit pour le serveur.</p>
		<p>10 - Le choix de l'<b>Algo empreinte</b> définit l'algorithme de prise d'empreinte et donc permet de maîtriser directement sa solidité. Cependant, les algorithmes les plus élevés pénalisent les performances.<br />
		Il est recommandé en 2014 de choisir l'algorithme <b>sha256</b> ou plus.<br />
		Le choix par défaut est définit pour le serveur.
		</p>";
		$this->_table['en-en']['::bloc_chr_head']='Search';
		$this->_table['en-en']['::bloc_chr_head_hlp']='Search';
		$this->_table['en-en']['::bloc_lnk_head']='Links of the object';
		$this->_table['en-en']['::bloc_lnk_head_hlp']="<p>Le filtrage permet de réduire l'affichage des liens dans la liste ci-dessous.</p>
		<p>1 - Active le filtrage et cache les liens qui ont été marqués comme supprimés, c'est à dire lorsque le même lien a été généré mais avec l'action <code>x</code>.
		Les liens de suppression ne sont pas affichés non plus.</p>
		<p>2 - On peut ne conserver à l'affichage que certains types de liens, c'est en fait l'action qu'ils ont sur l'objet et les autres liens.
		Par exemple on peut ne vouloir que les liens de chiffrement dont le type est <code>k</code>.</p>
		<p>3 - On peut n'afficher que les liens de l'objet courant a avec un autre objet.
		Ce peut être par exemple la description du type mime (5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0) de l'objet.
		</p>";
		//$this->_table['en-en']['::bloc_lnk_list']='List of links';
		$this->_table['en-en']['::bloc_lnk_list_hlp']="";
		$this->_table['en-en']['::bloc_upl_head']='Transfer of objects and links';
		$this->_table['en-en']['::bloc_upl_head_hlp']='Transfer of objects and links';
		$this->_table['en-en']['::bloc_upl_upfile']='Send file as new object';
		$this->_table['en-en']['::bloc_upl_upfile_hlp']="<p>Cette partie permet de transmettre un fichier à nébuliser, c'est à dire à transformer en objet <i>nebule</i>.</p>
		<p>L'empreinte du fichier est automatiquement calculée, elle deviendra l'identifiant (ID) de l'objet.
		En fonction du type de fichier, il est analysé afin d'en extraire certaines caractéristiques personnalisées.</p>";
		$this->_table['en-en']['::bloc_upl_uptxt']='Send new text';
		$this->_table['en-en']['::bloc_upl_uptxt_hlp']="<p>Cette partie permet la création d'un objet à partir d'un texte brute, c'est à dire sans formatage.</p>";
		$this->_table['en-en']['::bloc_upl_synobj']='Synchronization of an object not recognized locally';
		$this->_table['en-en']['::bloc_upl_synobj_hlp']="<p>Cette partie permet de tenter de trouver un objet et ses liens aux différents emplacements connus.
		L'objet est recherche par rapport à son identifiant, c'est à dire son empreinte.</p>";
		$this->_table['en-en']['::bloc_upl_uplnk']='Send a simple link';
		$this->_table['en-en']['::bloc_upl_uplnk_hlp']="Cette partie permet de transmettre un lien à ajouter. Après vérification, le lien est automatiquement attaché aux objets concernés.</p>
		<p>Si une entité n'est pas déverrouillée, le lien doit être signé par l'entité indiqué. C'est dans ce cas un import d'un seul lien.
		Pour transmettre plusieurs liens simultanément, il faut passer par la partie '<i>Envoie d'un fichier de liens pré-signés</i>'.</p>";
		$this->_table['en-en']['::bloc_upl_crlnk']='Create a new link';
		$this->_table['en-en']['::bloc_upl_crlnk_hlp']="<p>Cette partie permet la création d'un nouveau lien et sa signature. Il faut renseigner les différents champs correspondants au registre du lien attendu.</p>";
		$this->_table['en-en']['::bloc_upl_upfilelnk']='Send file with pre-signed links';
		$this->_table['en-en']['::bloc_upl_upfilelnk_hlp']="<p>Cette partie permet de transmettre un fichier contenant des liens à ajouter. Tous les liens doivent être signés pour être analysés.
		Après vérification, les liens sont automatiquement attachés aux objets concernés.</p>";

		// Description des variables
		$this->_table['en-en']['::var_nebule_hashalgo']="Algorithme de prise d'empreinte utilisé par défaut.";
		$this->_table['en-en']['::var_nebule_symalgo']="Algorithme de chiffrement symétrique utilisé par défaut.";
		$this->_table['en-en']['::var_nebule_symkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement symétrique.";
		$this->_table['en-en']['::var_nebule_asymalgo']="Algorithme de chiffrement asymétrique utilisé par défaut.";
		$this->_table['en-en']['::var_nebule_asymkeylen']="Taille de la clé par défaut utilisée par l'algorithme de chiffrement asymétrique.";
		$this->_table['en-en']['::var_nebule_io_maxlink']='Limit the number of links to read for an object, the following are ignored. Used by functions <code>_l_ls1</code> and <code>__io_lr</code>.';
		$this->_table['en-en']['::var_nebule_io_maxdata']='Limit the amount of data in bytes to read for an object, the rest is ignored.. Used by functions <code>_o_dl1</code> and <code>__io_or</code>.';
		$this->_table['en-en']['::var_nebule_checksign']='Permit or not links signs inspection. Used by function <code>_l_vr</code>, on links read and load. Should always be <u>true</u>.';
		$this->_table['en-en']['::var_nebule_listchecklinks']='Permit or not on the read verification of links and signs. Used by function <code>_l_ls1</code>. Affect performances.';
		$this->_table['en-en']['::var_nebule_listinvalidlinks']='Permit or not on the read invalids links. For display purpose only, not used. Used by function <code>_l_ls1</code>.';
		$this->_table['en-en']['::var_nebule_permitwrite']="Permit or not writing operations by <code>php</code> code.
		Used by functions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_dl1</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code>,
		<code>_l_wr</code>, <code>_l_gen</code>, <code>__io_lw</code> and <code>__io_ow</code>. On <u>false</u>, it's an global read only lock.";
		$this->_table['en-en']['::var_nebule_permitcreatelink']='Permit or not new link creation by <code>php</code> code.
		Used by functions <code>_e_gen</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_l_wr</code>, <code>_l_gen</code> and <code>__io_lw</code>.';
		$this->_table['en-en']['::var_nebule_permitcreateobj']='Permit or not new object creation by <code>php</code> code.
		Used by functions <code>_e_gen</code>, <code>_o_gen</code>, <code>_o_wr</code>, <code>_o_prt</code>, <code>_o_uprt</code>, <code>_o_del</code> and <code>__io_ow</code>.';
		$this->_table['en-en']['::var_nebule_permitcreatentity']='Permit or not the create of new entity by <code>php</code> code. Used by function <code>_e_gen</code>.';
		$this->_table['en-en']['::var_nebule_permitsynclink']='Permit or not links download from others nebule servers. Used by function <code>_l_dl1</code>.';
		$this->_table['en-en']['::var_nebule_permitsyncobject']='Permit or not objects download from others nebule servers. Used by function <code>_o_dl1</code>.';
		$this->_table['en-en']['::var_nebule_createhistory']='Permit or not logging about all lasts new links creation. This create file <code>/l/f</code> with links which have to be flush regularly.
		Used to export easily new links generated by an offline entity.';
		$this->_table['en-en']['::var_nebule_curentnotauthority']="Interdit à l'entité courante d'être autorité. Cela l'empêche de charger des composants externes par elle-même. Dans le bootstrap, le comportement est un peu différent.";
		$this->_table['en-en']['::var_nebule_local_authority']="C'est la liste des entités reconnues comme autorités locales. Seules ces entités peuvent signer des modules à charger localement.";
		$this->_table['en-en']['::var_sylabe_affuntrustedsign']='Display or not results of links verify, on <code>lnk</code> display mode only.';
		$this->_table['en-en']['::var_sylabe_hidedevmods']='Switch display between development mode and quiet mode.';
		$this->_table['en-en']['::var_sylabe_permitsendlink']='Permit or not links upload to this server.';
		$this->_table['en-en']['::var_sylabe_permitsendobject']='Permit or not objects upload to this server.';
		$this->_table['en-en']['::var_sylabe_permitpubcreatentity']="Permit or not the create of new entity (autonomous) publicly, event if there's no previously unlocked entity. Must be <u>false</u> on public server.";
		$this->_table['en-en']['::var_nebule_permitcreatentnopwd']="Autorise ou non la création d'une entité sans mot de passe. Devrait toujours être à <u>false</u>.";
		$this->_table['en-en']['::var_sylabe_permitaskbootstrap']='Permit or not the sending of commands to the <i>bootstrap</i> which select sylabe version and library version. Must be <u>false</u> on public server.';
		$this->_table['en-en']['::var_sylabe_affonlinehelp']='Permit or not the online help.';
		$this->_table['en-en']['::var_sylabe_showvars']='Display or not internals variables, on <code>log</code> display mode only.';
		$this->_table['en-en']['::var_sylabe_timedebugg']='Display inline woking times on the flow.';
		$this->_table['en-en']['::var_sylabe_upfile_maxsize']='Define max size in bytes (after uuencode) of uploaded files on this server.';
		$this->_table['en-en']['::var_nebule_followxonsamedate']="Prendre en compte le lien x si la date est identique avec un autre lien, ou pas.";
		$this->_table['en-en']['::var_nebule_maxrecurse']="Définit le maximum de niveaux parcourus pour la recherche des objets enfants d'un objet. Affecte les performances.";
		$this->_table['en-en']['::var_nebule_maxupdates']="Définit le maximum de niveaux parcourus poue la recherche des mises à jours d'un objet. Affecte les performances.";
		$this->_table['en-en']['::var_nebule_linkversion']="Définit la version de nebule utilisée pour les liens.";
		$this->_table['en-en']['::var_nebule_usecache']="Autorise ou non l'utilisation du cache. Affecte les performances.";
		$this->_table['en-en']['::var_sylabe_permitfollowcss']='Permit or not the use of custom style sheets (CSS).';
		$this->_table['en-en']['::var_sylabe_permitphpcss']='Permit or not the use of php code in the style sheets (CSS).';
		*/
    }
}
