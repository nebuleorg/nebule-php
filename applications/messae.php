<?php
declare(strict_types=1);
namespace Nebule\Application\Messae;
use Nebule\Library\nebule;
use Nebule\Library\References;
use Nebule\Library\Metrology;
use Nebule\Library\ApplicationInterface;
use Nebule\Library\Applications;
use Nebule\Library\DisplayInterface;
use Nebule\Library\Displays;
use Nebule\Library\ActionsInterface;
use Nebule\Library\Actions;
use Nebule\Library\ModuleTranslateInterface;
use Nebule\Library\Translates;
use Nebule\Library\ModuleInterface;
use Nebule\Library\Modules;
use Nebule\Library\ModelModuleHelp;
use Nebule\Library\ModuleTranslates;

/*
|------------------------------------------------------------------------------------------
| /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING /// WARNING ///
|------------------------------------------------------------------------------------------
|
|  [FR] Toute modification de ce code entraînera une modification de son empreinte
|       et entraînera donc automatiquement son invalidation !
|  [EN] Any modification of this code will result in a modification of its hash digest
|       and will therefore automatically result in its invalidation!
|  [ES] Cualquier cambio en el código causarán un cambio en su presencia y por lo
|       tanto lugar automáticamente a su anulación!
|  [UA] Будь-яка модифікація цього коду призведе до зміни його відбитку пальця і,
|       відповідно, автоматично призведе до його анулювання!
|
|------------------------------------------------------------------------------------------
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
    const APPLICATION_VERSION = '020260101';
    const APPLICATION_LICENCE = 'GNU GPL 2016-2026';
    const APPLICATION_WEBSITE = 'www.messae.org';
    const APPLICATION_NODE = '2060a0d21853a42093f01d2e4809c2a5e9300b4ec31afbaf18af66ec65586d6c78b2823a.none.288';
    const APPLICATION_CODING = 'application/x-httpd-php';
    const USE_MODULES = true;
    const USE_MODULES_TRANSLATE = true;
    const USE_MODULES_EXTERNAL = false;
    const LIST_MODULES_INTERNAL = array(
        'ModuleHelp',
        'ModuleMessages',
        'ModuleAdmin',
        'ModuleObjects',
        'ModuleGroupEntities',
        'ModuleLang',
        'ModuleTranslateDEDE',
        'ModuleTranslateENEN',
        'ModuleTranslateESCO',
        'ModuleTranslateESES',
        'ModuleTranslateFRFR',
        'ModuleTranslateITIT',
        'ModuleTranslatePLPL',
        'ModuleTranslateUAUA',
    );
    const LIST_MODULES_EXTERNAL = array();
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
    const DEFAULT_DISPLAY_MODE = 'msg';
    const DEFAULT_DISPLAY_VIEW = 'groups';
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
    const DEFAULT_APPLICATION_LOGO_LINK = '?dm=hlp&dv=about';
    const DEFAULT_LOGO_MENUS = '15eb7dcf0554d76797ffb388e4bb5b866e70a3a33e7d394a120e68899a16c690.sha2.256';
    const DEFAULT_CSS_BACKGROUND = 'f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499.sha2.256';

    // Icônes de marquage.
    const DEFAULT_ICON_MARK = '65fb7dbaaa90465da5cb270da6d3f49614f6fcebb3af8c742e4efaa2715606f0.sha2.256';
    const DEFAULT_ICON_UNMARK = 'ee1d761617468ade89cd7a77ac96d4956d22a9d4cbedbec048b0c0c1bd3d00d2.sha2.256';
    const DEFAULT_ICON_UNMARKALL = 'fa40e3e73b9c11cb5169f3916b28619853023edbbf069d3bd9be76387f03a859.sha2.256';

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
    protected array $_neededObjectsList = array(
        self::DEFAULT_LOGO_MENUS,
        self::DEFAULT_ICON_ALPHA_COLOR,
        self::DEFAULT_ICON_LC,
        self::DEFAULT_ICON_LD,
        self::DEFAULT_ICON_LE,
        self::DEFAULT_ICON_LF,
        self::DEFAULT_ICON_LK,
        self::DEFAULT_ICON_LL,
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



    /*
	 * --------------------------------------------------------------------------------
	 * La personnalisation.
	 * --------------------------------------------------------------------------------

    /**
     * Affichage du style CSS.
     */
    public function displayCSS(): void
    {
        // Recherche l'image de fond.
        $bgobj = $this->_cacheInstance->newNode($this::DEFAULT_CSS_BACKGROUND);
        if ($this->_nebuleInstance->getNodeIsRID($bgobj))
            $bgobj = $bgobj->getReferencedObjectInstance(References::REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE, 'authority');
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
            if ($module::MODULE_COMMAND_NAME == $this->_currentDisplayMode) {
                $module->getCSS();
            }
        }
    }

    /**
     * Affiche la métrologie.
     */
    private function _displayMetrology(): void
    {
        if ($this->_configurationInstance->getOptionUntyped('messaeDisplayMetrology')) {
            ?>

            <?php $this->displayDivTextTitle_DEPRECATED(self::DEFAULT_ICON_IMLOG, 'Métrologie', 'Mesures quantitatives et temporelles.') ?>
            <div class="text">
                <p>
                    <?php
                    //		aff_title('::bloc_metrolog','imlog');
                    // Affiche les valeurs de la librairie.
                    /*		echo 'Bootstrap : ';
		$this->_translateInstance->echoTraduction('%01.0f liens lus,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkRead()); echo ' ';
		$this->_translateInstance->echoTraduction('%01.0f liens vérifiés,','',$this->_bootstrapInstance->getMetrologyInstance()->getLinkVerify()); echo ' ';
		$this->_translateInstance->echoTraduction('%01.0f objets vérifiés.','',$this->_bootstrapInstance->getMetrologyInstance()->getObjectVerify()); echo "<br />\n";*/
                    echo 'Lib nebule : ';
                    echo $this->_translateInstance->getTranslate('%01.0f liens lus,', (string)$this->_metrologyInstance->getLinkRead());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f liens vérifiés,', (string)$this->_metrologyInstance->getLinkVerify());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f objets lus.', (string)$this->_metrologyInstance->getObjectRead());
                    echo ' ';
                    echo $this->_translateInstance->getTranslate('%01.0f objets vérifiés.', (string)$this->_metrologyInstance->getObjectVerify());
                    echo "<br />\n";
                    // Calcul de temps de chargement de la page - stop
                    /*		$bootstrapTimeList = $this->_bootstrapInstance->getMetrologyInstance()->getTimeArray();
		$bootstrap_time_total = 0;
		foreach ( $bootstrapTimeList as $time )
		{
			$bootstrap_time_total = $bootstrap_time_total + $time;
		}
		$this->_translateInstance->echoTraduction('Le bootstrap à pris %01.4fs pour appeler la page.','',$bootstrap_time_total);
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
                    echo $this->_translateInstance->getTranslate('Le serveur à pris %01.4fs pour calculer la page.', $messae_time_total);
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



    /* --------------------------------------------------------------------------------
	 *  Affichage des objets.
	 * -------------------------------------------------------------------------------- */
    public function displayObjectDivHeaderH1($object, $help = '', $desc = ''): void
    {
        $object = $this->_applicationInstance->getTypedInstanceFromNID($object);
        // Prépare le type mime.
        $typemime = $object->getType('all');
        if ($desc == '') {
            $desc = $this->_applicationInstance->getTranslateInstance()->getTranslate($typemime);
        }

        // Détermine si c'est une entité.
        $objHead = $object->readOneLineAsText(\Nebule\Library\Entity::ENTITY_MAX_SIZE);
        $isEntity = ($typemime == \Nebule\Library\Entity::ENTITY_TYPE && strpos($objHead, References::REFERENCE_ENTITY_HEADER) !== false);

        // Détermine si c'est un groupe.
        $isGroup = $object->getIsGroup('all');

        // Détermine si c'est une conversation.
        $isConversation = $object->getIsConversation('all');

        // Modifie le type au besoin.
        if ($isEntity && !is_a($object, 'Entity')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_ENTITY);
        }
        if ($isGroup && !is_a($object, 'Group')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_GROUP);
        }
        if ($isConversation && !is_a($object, 'Conversation')) {
            $object = $this->_cacheInstance->newNode($object->getID(), \Nebule\Library\Cache::TYPE_CONVERSATION);
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
                $help = '::display:default:help:Entity';
            } elseif ($isConversation) {
                $help = '::display:default:help:Conversation';
            } elseif ($isGroup) {
                $help = '::display:default:help:Group';
            } else {
                $help = '::display:default:help:Object';
            }
        }
        ?>

        <div class="textTitle">
            <?php
            $this->_displayDivOnlineHelp_DEPRECATED($help);
            ?>

            <div class="floatRight">
                <?php
                switch ($status) {
                    case 'notPresent':
                        $msg = $this->_translateInstance->getTranslate('::display:content:errorNotAvailable');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'tooBig':
                        if ($this->_configurationInstance->getOptionUntyped('messaeDisplayUnverifyLargeContent')) {
                            $msg = $this->_translateInstance->getTranslate('::display:content:warningTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        } else {
                            $msg = $this->_translateInstance->getTranslate('::display:content:errorTooBig');
                            $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        }
                        break;
                    case 'warning':
                        $msg = $this->_translateInstance->getTranslate('::display:content:warningTaggedWarning');
                        $this->displayIcon(self::DEFAULT_ICON_IWARN, $msg, 'iconNormalDisplay');
                        break;
                    case 'danger':
                        $msg = $this->_translateInstance->getTranslate('::display:content:errorBan');
                        $this->displayIcon(self::DEFAULT_ICON_IERR, $msg, 'iconNormalDisplay');
                        break;
                    case 'notAnObject':
                        $msg = $this->_translateInstance->getTranslate('::display:content:notAnObject');
                        $this->displayIcon(self::DEFAULT_ICON_ALPHA_COLOR, $msg, 'iconNormalDisplay');
                        break;
                    default:
                        $msg = $this->_translateInstance->getTranslate('::display:content:OK');
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
}



class Action extends Actions {}

class Translate extends Translates {}



class ModuleHelp extends \Nebule\Library\ModelModuleHelp {
    const MODULE_TYPE = 'Application';
    const MODULE_VERSION = '020260101';

    CONST TRANSLATE_TABLE = [
        'fr-fr' => [
            '::ModuleName' => "Module d'aide",
            '::MenuName' => 'Aide',
            '::ModuleDescription' => "Module d'aide en ligne.",
            '::ModuleHelp' => "Ce module permet d'afficher de l'aide générale sur l'interface.",
            '::AppTitle1' => 'Aide',
            '::AppDesc1' => "Affiche l'aide en ligne.",
            '::Bienvenue' => 'Bienvenue sur <b>messae</b>.',
            '::About' => 'A propos',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Démarrage',
            '::AideGenerale' => 'Aide générale',
            '::APropos' => 'A propos',
            '::APropos:Text' => "Le projet <i>messae</i> est une implémentation logicielle basée sur le projet nebule.<br />
Cette implémentation en php est voulue comme une référence des possibilités offertes par les objets et les liens tels que définis dans nebule.",
            '::AideGenerale:Text' => "Le logiciel est composé de trois parties :<br />
1. le bandeau du haut qui contient le menu de l'application et l'entité en cours.<br />
2. la partie centrale qui contient le contenu à afficher, les objets, les actions, etc...<br />
3. le bandeau du bas qui apparaît lorsqu'une action est réalisée.<br />
<br />
D'un point de vue général, tout ce qui est sur fond clair concerne une action en cours ou l'objet en cours d'utilisation. Et tout ce qui est sur fond noir concerne l'interface globale ou d'autres actions non liées à ce que l'on fait.<br />
Le menu en haut à gauche est le meilleur moyen de se déplacer dans l'interface.",
        ],
        'en-en' => [
            '::ModuleName' => 'Help module',
            '::MenuName' => 'Help',
            '::ModuleDescription' => 'Online help module.',
            '::ModuleHelp' => 'This module permit to display general help about the interface.',
            '::AppTitle1' => 'Help',
            '::AppDesc1' => 'Display online help.',
            '::Bienvenue' => 'Welcome to <b>messae</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Start',
            '::AideGenerale' => 'General help',
            '::APropos' => 'About',
            '::APropos:Text' => 'The <i>messae</i> project is a software implementation based on nebule project.<br />
This php implementation is intended to be a reference of the potential of objects and relationships as defined in nebule.',
            '::AideGenerale:Text' => "The software is composed of three parts:<br />
1. The top banner, which contains the application menu and the current entity.<br />
2. The central part, which contains the content to display, objects, actions, etc...<br />
3. The bottom banner, which appears when an action is performed.<br />
<br />
From a general point of view, everything on a light background relates to an ongoing action or the object currently in use. And everything on a dark background relates to the global interface or other actions unrelated to what you're doing.<br />
The menu at the top left is the best way to navigate the interface.",
        ],
        'es-co' => [
            '::ModuleName' => 'Módulo de ayuda',
            '::MenuName' => 'Ayuda',
            '::ModuleDescription' => 'Módulo de ayuda en línea.',
            '::ModuleHelp' => 'Esta modulo te permite ver la ayuda general sobre la interfaz.',
            '::AppTitle1' => 'Ayuda',
            '::AppDesc1' => 'Muestra la ayuda en línea.',
            '::Bienvenue' => 'Bienviedo en <b>messae</b>.',
            '::About' => 'About',
            '::Bootstrap' => 'Bootstrap',
            '::Demarrage' => 'Comienzo',
            '::AideGenerale' => 'Ayuda general',
            '::APropos' => 'Acerca',
            '::APropos:Text' => 'El proyecto <i>messae</i> es un proyecto basado nebule implementación de software.<br />
Esta aplicación php está pensado como una referencia del potencial de los objetos y las relaciones como se define en nebule.',
            '::AideGenerale:Text' => "El software se compone de tres partes:<br />
1. La banda superior, que contiene el menú de la aplicación y la entidad actual.<br />
2. La parte central, que contiene el contenido a mostrar, los objetos, las acciones, etc...<br />
3. La banda inferior, que aparece cuando se realiza una acción.<br />
<br />
Desde un punto de vista general, todo lo que está sobre un fondo claro está relacionado con una acción en curso o el objeto que se está utilizando. Y todo lo que está sobre un fondo oscuro se refiere a la interfaz global u otras acciones no relacionadas con lo que se está haciendo.<br />
El menú en la parte superior izquierda es la mejor manera de navegar por la interfaz.",
        ],
    ];
}
