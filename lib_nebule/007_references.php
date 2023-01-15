<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Configuration class for the nebule library.
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class References
{
    // Les commandes.
    const COMMAND_SWITCH_APPLICATION = 'a';
    const COMMAND_FLUSH = 'f';
    const COMMAND_RESCUE = 'r';
    const COMMAND_LOGOUT_ENTITY = 'logout';
    const COMMAND_SWITCH_TO_ENTITY = 'switch';
    const COMMAND_SELECT_OBJECT = 'obj';
    const COMMAND_SELECT_LINK = 'lnk';
    const COMMAND_SELECT_ENTITY = 'ent';
    const COMMAND_SELECT_GROUP = 'grp';
    const COMMAND_SELECT_CONVERSATION = 'cvt';
    const COMMAND_SELECT_CURRENCY = 'cur';
    const COMMAND_SELECT_TOKENPOOL = 'tkp';
    const COMMAND_SELECT_TOKEN = 'tkn';
    const COMMAND_SELECT_WALLET = 'wal';
    const COMMAND_SELECT_TRANSACTION = 'trs';
    const COMMAND_SELECT_PASSWORD = 'pwd';
    const COMMAND_SELECT_TICKET = 'tkt';

    // Les références.
    const REFERENCE_OBJECT_TEXT = 'text/plain';
    const REFERENCE_OBJECT_HTML = 'text/html';
    const REFERENCE_OBJECT_CSS = 'text/css';
    const REFERENCE_OBJECT_PHP = 'text/x-php';
    const REFERENCE_OBJECT_APP_PHP = 'application/x-php';
    const REFERENCE_OBJECT_PNG = 'image/png';
    const REFERENCE_OBJECT_JPEG = 'image/jpeg';
    const REFERENCE_OBJECT_MP3 = 'audio/mpeg';
    const REFERENCE_OBJECT_OGG = 'audio/x-vorbis+ogg';
    const REFERENCE_OBJECT_CRYPT_RSA = 'application/x-encrypted/rsa';
    const REFERENCE_OBJECT_ENTITY = 'application/x-pem-file';
    const REFERENCE_ENTITY_HEADER = '-----BEGIN PUBLIC KEY-----';
    const REFERENCE_CRYPTO_HASH_ALGORITHM = 'sha2.256';
    const LIB_RID_SECURITY_AUTHORITY = 'a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288';
    const LIB_RID_CODE_AUTHORITY = '2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288';
    const LIB_RID_TIME_AUTHORITY = 'bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288';
    const LIB_RID_DIRECTORY_AUTHORITY = '0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288';

    // Les objets références de nebule.
    const REFERENCE_NEBULE_OBJET = 'nebule/objet';
    const REFERENCE_NEBULE_OBJET_HASH = 'nebule/objet/hash';
    const REFERENCE_NEBULE_OBJET_HOMOMORPHE = 'nebule/objet/homomorphe';
    const REFERENCE_NEBULE_OBJET_TYPE = 'nebule/objet/type';
    const REFERENCE_NEBULE_OBJET_LOCALISATION = 'nebule/objet/localisation';
    const REFERENCE_NEBULE_OBJET_TAILLE = 'nebule/objet/taille';
    const REFERENCE_NEBULE_OBJET_PRENOM = 'nebule/objet/prenom';
    const REFERENCE_NEBULE_OBJET_NOM = 'nebule/objet/nom';
    const REFERENCE_NEBULE_OBJET_SURNOM = 'nebule/objet/surnom';
    const REFERENCE_NEBULE_OBJET_PREFIX = 'nebule/objet/prefix';
    const REFERENCE_NEBULE_OBJET_SUFFIX = 'nebule/objet/suffix';
    const REFERENCE_NEBULE_OBJET_LIEN = 'nebule/objet/lien';
    const REFERENCE_NEBULE_OBJET_DATE = 'nebule/objet/date';
    const REFERENCE_NEBULE_OBJET_DATE_ANNEE = 'nebule/objet/date/annee';
    const REFERENCE_NEBULE_OBJET_DATE_MOIS = 'nebule/objet/date/mois';
    const REFERENCE_NEBULE_OBJET_DATE_JOUR = 'nebule/objet/date/jour';
    const REFERENCE_NEBULE_OBJET_DATE_HEURE = 'nebule/objet/date/heure';
    const REFERENCE_NEBULE_OBJET_DATE_MINUTE = 'nebule/objet/date/minute';
    const REFERENCE_NEBULE_OBJET_DATE_SECONDE = 'nebule/objet/date/seconde';
    const REFERENCE_NEBULE_OBJET_DATE_ZONE = 'nebule/objet/date/zone';
    const REFERENCE_NEBULE_OBJET_ENTITE = 'nebule/objet/entite';
    const REFERENCE_NEBULE_OBJET_ENTITE_TYPE = 'nebule/objet/entite/type';
    const REFERENCE_NEBULE_OBJET_ENTITE_LOCALISATION = 'nebule/objet/entite/localisation';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI = 'nebule/objet/entite/suivi';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_SECONDE = 'nebule/objet/entite/suivi/seconde';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_MINUTE = 'nebule/objet/entite/suivi/minute';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_HEURE = 'nebule/objet/entite/suivi/heure';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_JOUR = 'nebule/objet/entite/suivi/jour';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_SEMAINE = 'nebule/objet/entite/suivi/semaine';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_MOIS = 'nebule/objet/entite/suivi/mois';
    const REFERENCE_NEBULE_OBJET_ENTITE_SUIVI_ANNEE = 'nebule/objet/entite/suivi/annee';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE = 'nebule/objet/entite/maitre';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_SECURITE = 'nebule/objet/entite/maitre/securite';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_CODE = 'nebule/objet/entite/maitre/code';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_ANNUAIRE = 'nebule/objet/entite/maitre/annuaire';
    const REFERENCE_NEBULE_OBJET_ENTITE_MAITRE_TEMPS = 'nebule/objet/entite/maitre/temps';
    const REFERENCE_NEBULE_OBJET_ENTITE_AUTORITE_LOCALE = 'nebule/objet/entite/autorite/locale';
    const REFERENCE_NEBULE_OBJET_ENTITE_RECOUVREMENT = 'nebule/objet/entite/recouvrement';
    const REFERENCE_NEBULE_OBJET_INTERFACE_BOOTSTRAP = 'nebule/objet/interface/web/php/bootstrap';
    const REFERENCE_NEBULE_OBJET_INTERFACE_BIBLIOTHEQUE = 'nebule/objet/interface/web/php/bibliotheque';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APPLICATIONS = 'nebule/objet/interface/web/php/applications';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_DIRECT = 'nebule/objet/interface/web/php/applications/direct';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_ACTIVE = 'nebule/objet/interface/web/php/applications/active';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_MODULES = 'nebule/objet/interface/web/php/applications/modules';
    const REFERENCE_NEBULE_OBJET_INTERFACE_APP_MOD_ACTIVE = 'nebule/objet/interface/web/php/applications/modules/active';
    const REFERENCE_NEBULE_OBJET_NOEUD = 'nebule/objet/noeud';
    const REFERENCE_NEBULE_OBJET_IMAGE_REFERENCE = 'nebule/objet/image/reference';
    const REFERENCE_NEBULE_OBJET_EMOTION = 'nebule/objet/emotion';
    const REFERENCE_NEBULE_OBJET_EMOTION_JOIE = 'nebule/objet/emotion/joie';
    const REFERENCE_NEBULE_OBJET_EMOTION_CONFIANCE = 'nebule/objet/emotion/confiance';
    const REFERENCE_NEBULE_OBJET_EMOTION_PEUR = 'nebule/objet/emotion/peur';
    const REFERENCE_NEBULE_OBJET_EMOTION_SURPRISE = 'nebule/objet/emotion/surprise';
    const REFERENCE_NEBULE_OBJET_EMOTION_TRISTESSE = 'nebule/objet/emotion/tristesse';
    const REFERENCE_NEBULE_OBJET_EMOTION_DEGOUT = 'nebule/objet/emotion/degout';
    const REFERENCE_NEBULE_OBJET_EMOTION_COLERE = 'nebule/objet/emotion/colere';
    const REFERENCE_NEBULE_OBJET_EMOTION_INTERET = 'nebule/objet/emotion/interet';
    const REFERENCE_NEBULE_OBJET_GROUPE = 'nebule/objet/groupe';
    const REFERENCE_NEBULE_OBJET_GROUPE_SUIVI = 'nebule/objet/groupe/suivi';
    const REFERENCE_NEBULE_OBJET_GROUPE_FERME = 'nebule/objet/groupe/ferme';
    const REFERENCE_NEBULE_OBJET_GROUPE_PROTEGE = 'nebule/objet/groupe/protege';
    const REFERENCE_NEBULE_OBJET_GROUPE_DISSIMULE = 'nebule/objet/groupe/dissimule';
    const REFERENCE_NEBULE_OBJET_CONVERSATION = 'nebule/objet/conversation';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_SUIVIE = 'nebule/objet/conversation/suivie';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_FERMEE = 'nebule/objet/conversation/fermee';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_PROTEGEE = 'nebule/objet/conversation/protegee';
    const REFERENCE_NEBULE_OBJET_CONVERSATION_DISSIMULEE = 'nebule/objet/conversation/dissimulee';
    const REFERENCE_NEBULE_OBJET_ARBORESCENCE = 'nebule/objet/arborescence';
    const REFERENCE_NEBULE_OBJET_MONNAIE = 'nebule/objet/monnaie';
    const REFERENCE_NEBULE_OBJET_MONNAIE_JETON = 'nebule/objet/monnaie/jeton';
    const REFERENCE_NEBULE_OBJET_MONNAIE_SAC = 'nebule/objet/monnaie/sac';
    const REFERENCE_NEBULE_OBJET_MONNAIE_PORTEFEUILLE = 'nebule/objet/monnaie/portefeuille';
    const REFERENCE_NEBULE_OBJET_MONNAIE_TRANSACTION = 'nebule/objet/monnaie/transaction';
    const REFERENCE_NEBULE_OPTION = 'nebule/option';
    const REFERENCE_NEBULE_DANGER = 'nebule/danger';
    const REFERENCE_NEBULE_WARNING = 'nebule/warning';
    const REFERENCE_NEBULE_REFERENCE = 'nebule/reference';

    const OBJ_IMG = array(
        'OBJ_IMG_ADD' => 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAkElEQVR42u3ZMQ6AMAgFUDDeWz05XkCHDkqTvj92IX2BNKEZEWcsnC0WDwAAAAAAAAAAAIBVs3cUrarj6TwzLx0AAAAAAAAAAAAAAAAAAAAAAPg6
GYOfo2/LjGkuNLhUMQIAAADwCvxd1FrcCAAAAAAAAAAAAAAAAAAAAACdaVmI6AAAAAAAAAAAAAAAANpzA7EpDn+g1WKvAAAAAElFTkSuQmCC',
        'OBJ_IMG_ADDENT' => 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAEWElEQVR42u2bT2gaWRzHn1EDHpSGMUXaCM5lD6UI6SGgZaQMQQhlwfE6wrJUcEAo5CRZCrsLpeIlcxqIYBIC5tLLSEMIzMGDsx1PIkjpKYttXYo0
DoZ4GEhG3MsuZMaZmPG/T383hRn9fub3b977PRMA4A8wx7YE5tzmHoBlHD+CoqjN7/cjPp/P5fV6H7nd7odOp/OBzWazAQCAJElSo9G4rNVqPyqVyvdisVgXBEGsVqvSqP+baRQ5wGQygWAwuBqNRp+GQqHnFovF
3M99ZFlu53K5j5lM5hPHcRedTme6ASAIYk0kEk+2t7d/7lf0XTBomj5JpVKfRVG8mSoAdrvdnEwm1+Px+MtxhBTDMKc7OzvlVqvVnjiAWCzm2dvb+2USCYyiqKN0Ov1lIgAQBLGyLLuFYdj6JLM4z/NlgiDO+g2L
vgBgGLaSz+fjw47zQfIDjuMMz/PNkfcBJEmuFQqF19MiHgAALBaLuVAovCZJcs3otWYAwAsj8X5wcPDrtDY14XD4Wb1e/1oqlS6HHgIkSa5ls9lXQ+oT/tT6vtPp/D6M+0cikf3j4+N/hhYCGIatDEv8OCybzb7C
MGxlKAAQBLHm8/n4rPX4+Xw+jiCIdWAALMtuTVPCM5IYWZbdGghALBbzTLrOD2IYhq3HYjFPX0nQbrebr66u3ozkDWzESVBtDofjrV7brOsByWRyZp+8ES2aHoAgiLXRaPw2snfwMXsAAAA4nc53Wu2ypgckEokn
sK386Gnq8gCTyQSur6/fjDLzT8IDZFluLy8vv1UvqnR5QDAYXJ3FsnefshgMBld7hkA0Gn0KIDUtbV0AQqHQc1gBaGlTAEBR1Aaj+98OAxRFbboA/H4/AiA3tUYFAJ/P54IdgFqjAoDX630EOwC1RsXOkNvtfthv
DR9lf2DEevUSao1LqnbxAeweoNaoAPD/Xh3Mpta42B6//UGSJAl2wWqNCgCNRuMSdgBqjYoqUKvVfng8nseDZNlpfRu8rVHXAyqVynfYPUCtUQGgWCzWYQeg1qgAIAiCCDsAtUYFgGq1Ksmy3IZVvCzLbfXcUVcf
kMvlPsIKQEtbF4BMJvMJVgBa2roAcBx3AWMYyLLc5jjuoieATqcDaJo+gQ0ATdMnWmN2mu8CqVTqM2wA9DRpAhBF8YZhmFNYxDMMc6o3RLXYHNW7qNVqtSmKOpr1p09R1NFdA5V3rgek0+kvPM+XZ1U8z/PlXoOU
PRdECII4m8WyKMtymyCIM0MLInoJEcdxZtYA4DjO3Gd6dOmertSMRCL7syI+Eons33dq1NCo7CQHo40kPSMD1IYmRUul0uX5+fnf4XD42bQ++cPDw2+GSjJYDEv3VV6aLpcrNQ0lkuf58n//pdnP9X3vC4iieBMI
BD5MslmiKOooEAh8GOQIjaEcoJcXdnd3/3I4HK2NjY2fxtXbb25uvhcEoTnovRaHpsDi2Nzozw7P3cHJWbLF7vC8A/gX6P4s7uYkotgAAAAASUVORK5CYII=',
        'OBJ_IMG_ADDLNK' => 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAE7UlEQVR42u2aT0gjdxTHX5wYyGrG7CYRd9dAUgiUkg0Ie0jGHSk5BEUoSY81pQcXVJKACioulrZUKiKokLEqxENp0pM0aYss5JCD2Z2ICKXZ0EvA
DMTdFZ2pJuIGTNL00oDYmcQ/WTqZ8Qe5vDfkFz95v+/3vZ+RAMDXwJNVKBS+RBCk4WK8ubl5+vT0tPg+9mwAHq1cLpdjixsMhjvva09eAaBp+pgtjmGYWhQA0un0AQeAh6IAEI/H37DFjUZjuygAxGKxfba4Tqe7
LwoAJEkybHEURVFRAEilUrlCofAfu5NIJOD3+58IHgAAQCgUeskWt9lsHaIA4PP5EmxxjUZzz263t9V6PwmfOsFyuZ+dnU1JpVLkYo6iqD29Xr9Wy/0QAPiYb1WgUCgynZ2dH16MK5VKlGGY9Pb29lFdV0CpVPqK
49v/BgBApVI10jT9jO2ZTCaTVSqVC4LVAAAAhmHyS0tLG2y5lpYWNBaLfSpoAAAAk5OTv3PlzGbzI6/X+1jQAE5OToqDg4M/cOWHhoZ63G73B4IFAACwurpKRaNR1kpAEKRhfn7+s5tC4DUAAACHw/GcrTsEAGhs
bEQWFxf7bnIceA+AYZi81Wpd4vRxBGlwu9291xVG3gMAAIhGo0dOp7NiA2Q2mx8dHx+PXPVI1AUAAIBAILBXSRTLFun1ej9PpVL9l22b6wZAWRSrVcK/9wftwWBw4ODgwFNtiuRlJ1ht4Th+NxKJuNjmBY79IJvN
ZimKeptIJPZIknxNkiSdTCbf1SWAcrscDAZ7cBy/0ZhcV0fgojt0dXX9Wk0XBAvgvC6gKDrNNTsIHkC5bXa73Ttqtfq7ubm5EFfjJFgA54/F+Pj4HzKZbLq7u/v79fX1zWow6lYEr7L0er0cwzCVxWJpM5lMD7Ra
batarVbK5XL5lQFwfXi+rKtCFNQREK0I3gK4BXAL4PqiWSgUirlcLkfT9HE6nT6Ix+NvYrHYPkmSTCqVygnBBisCKJVKJa5koVAohkKhlz6fLxEOhw8rPCpMABdhLCws/DY7O/snwzB5oQC4tAZIpVJkbGzMTtP0
M4IgHisUCkQIGnAtEXS5XL3ZbHZqYGBAJ2oXWFlZ+WJzc/MTlUrVWLcu0NTU9K3BYLiDYZgaw7CHRqOxXafT3UdRFJVIJHBZfbBarUvRaPSo3jSg4jDk9/uf2Gy2Do1Gc+8yb+Z0OtcCgcCeYETQ6XS+aG1t9Toc
jlWKoqr+YX6/v7/edOFSGhAKhfb1ev2ax+P5MZPJZKvpQl9fX7ugAJQXQRC7SqVyYWtr61W1SsBx/K5gXcBisfxMEMRGsVj8m+uZSCTiqgd3uLYNejyeneHh4UA+ny9yNU7BYLBH0H0AQRC7o6OjP3FVAo7jHXwX
xRuPwwRB7C4vLz+vJIp8bptrch/g8Xh2KgnjzMxMh6ABlIWRyyJdLlcvXwWxpjdCU1NTv3DlJiYmPjrf8bG9/pdZAGr8j5FUKtWv0+na2eYFmUw2XatLFV5WAADAyMjIBpct2mw2jaCPQLltPjw8/Ist9/TpU6Pg
AQAAhMNh1t/22e32TlEAcDqdL9jOulQqRfR6vVzwAAAAstksqyViGKYSBQCKot5y9AttogCQSCRYL1BMJtMDUQAgSfI1W1yr1baKBQDNFler1UpRAEgmk+/Y4nK5nFcu8A9zSxQEwKfvpgAAAABJRU5ErkJggg==',
        'OBJ_IMG_ADDOBJ' => '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',
        'OBJ_IMG_ADDOBJMARK' => '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',
        'OBJ_IMG_ADDTXT' => 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAA10lEQVR42u3bOxLCIBAA0MXx3urJSWMaZ2IIAyHK2yqNWzz3A05MEfGMd+ScHzFBpJRe6/MtJg8AAADMHfejU/OXomSrqYAWomuF7ImXVtJnntL8
NRVrCAIwA64znUdsGxVQe4vq+Q1u5elRISqg9cnq2+6uPQds5WxREdYgAAAAbIFW54CzPtPyPKAFAAAAAMAa7HQhGpH76IrUAgAAALAFel9uSqe9n8W1AAAAAAAAAAAAAAAAAAAAAHBGDHlP8Epvn2sBAAAA2AJ7
8c//KNUCAAAAmDoWM6tBNO5T/B4AAAAASUVORK5CYII=',
        'OBJ_IMG_ADMIN' => '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',
    );

    static public function installation($nebuleInstance): void
    {
        # Generate objects for icons.
        foreach ( self::OBJ_IMG as $name => $content)
        {
            $instance = new Node($nebuleInstance, '0');
            $instance->setContent($content, false, false);
            // TODO
        }
    }
}
