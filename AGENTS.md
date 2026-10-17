# Consignes pour les agents IA - Projet nebule-php

## 📝 Conventions générales

### Logging

Le projet utilise **deux systèmes de logging parallèles** :

#### 1. Logging procédural (bootstrap.php)
Fonction : `log_add()`

**Règle impérative** : Chaque appel doit utiliser un **ID unique de 8 caractères hexadécimaux**.

```php
log_add($message, $level, __FUNCTION__, $unique_id);
```

**Niveaux disponibles** : `debug`, `info`, `msg`, `normal`, `warning`, `error`

**Exceptions autorisées** (ne pas remplacer) :
- `1111c0de` : Utilisé pour le tracking standard des fonctions (`'track functions'`)
- `00000000` : Réservé pour le debug temporaire (à supprimer avant commit)

#### 2. Logging orienté objet (lib_nebule/004_metrology.php)

La classe **`Metrology`** ré-implémente les fonctions de logging pour la librairie OO.

**Fonction principale** :
```php
Metrology::addLog(string $message, int $level, string $function, string $luid): void
```

**Équivalence importante** :
- `$luid` (OO) **≡** `$id` (procédural) : Même rôle, même format (8 chars hexadécimaux)

**Niveaux de log (constantes de classe)** :
```php
Metrology::LOG_LEVEL_NORMAL   = 0;   // niveau par défaut
Metrology::LOG_LEVEL_ERROR    = 1;   // erreurs
Metrology::LOG_LEVEL_AUDIT    = 2;   // audit
Metrology::LOG_LEVEL_DEVELOP  = 4;   // développement
Metrology::LOG_LEVEL_FUNCTION  = 8;   // suivi des fonctions
Metrology::LOG_LEVEL_DEBUG    = 16;  // debug
```

**Format de sortie** :
Les deux systèmes produisent des logs **identiques** au format :
```
LogT=0.123456 LogL="function(8)" LogI="1111c0de" LogF="maFonction" LogM="mon message"
```

**Génération d'IDs** :
- Utiliser des IDs aléatoires pour les nouveaux logs
- Exemple : `7f3a8e2d`, `4c9b1e5a`, `a6d2e8f3`
- Vérifier l'unicité avec : `grep -oP "'[0-9a-f]{8}'" lib_nebule/004_metrology.php | sort | uniq -c`

#### 3. Codes de journalisation documentés

À la fin de `004_metrology.php`, une documentation liste les codes standards :

**Codes génériques** :
- `100000XX` : Error - Code générique de traçage des interruptions du bootstrap (XX = code spécifique)
- `1111c0de` : Function - Code générique de traçage des appels des fonctions

**Codes communs (liste non exhaustive)** :
- `76941959` : Info - Démarrage du bootstrap
- `50615f80` : Info - Création du fichier de debug
- `41ba02a9` : Error - Erreur d'initialisation de l'instance d'application
- `d121af4c` : Error - Erreur d'initialisation de l'instance de traduction
- `4bb6af65` : Error - Erreur d'initialisation de l'instance d'affichage
- `308b8a96` : Error - Erreur d'initialisation de l'instance des actions

**Codes Tokenize** :
- `d396f0a9` : Debug - Ticket vide
- `d516f0d4` : Error - Ticket déjà utilisé (tentative de rejeu)
- `7083b07d` : Audit - Ticket valide
- `b221e760` : Error - Ticket inconnu
- `8957de86` : Debug - Génération d'un nouveau token
- `d767b2ca` : Debug - Option permitActionWithoutToken=true
- `80fa0154` : Error - Impossible de récupérer le token depuis GET
- `65b5e0cc` : Error - Impossible de récupérer le token depuis POST

**Règle** : Utiliser les codes existants pour les mêmes événements, ou générer de nouveaux IDs uniques pour les nouveaux cas.

---

## 🏗️ Structure du projet

### Architecture du code source
```
nebule-php/
├── AGENTS.md                 # Ce fichier - consignes pour les agents IA
├── bootstrap.php            # Point d'entrée (Lib PP + routage principal)
├── nebule.env               # Configuration par défaut
├── prep_loop_code_master_php.sh  # Script de build et déploiement
│
├── lib_nebule/               # Bibliothèque procédurale (Lib PP)
│   ├── Cache.php             # Gestion du cache
│   ├── Crypto.php            # Fonctions cryptographiques
│   ├── io.php                # Entrées/sorties
│   ├── nebule.php            # Fonctions principales nebule
│   └── References.php        # Gestion des références
│
├── applications/             # Applications OOP (Lib POO)
│   └── atrium.php            # Application atrium
│
├── modules/                 # Modules complémentaires
│   ├── module_galleries.php  # Gestion des galeries
│   ├── module_groups.php     # Gestion des groupes
│   └── module_objects.php    # Gestion des objets
│
└── modules/langs/           # Fichiers de traduction
    ├── module_lang_de-de.php
    ├── module_lang_en-en.php
    ├── module_lang_es-co.php
    ├── module_lang_es-es.php
    ├── module_lang_fr-fr.php
    ├── module_lang_it-it.php
    └── module_lang_...php
```

---

## 🔗 Structure des liens et leur stockage

### Architecture des blocs et registres de liens

Le système nebule utilise une **structure hiérarchisée en trois niveaux** pour organiser les liens entre objets :

#### 1. Bloc de liens (BH_BL_BS)

Un **bloc de liens** est une chaîne de caractères signée qui agrège un ou plusieurs **registres de liens**. 
Format général : `BH_BL_BS`

**Composants séparés par underscore "_"** :

```
┌─────────────────────────────────────────────────────────────────────┐
│                      BLOC DE LIENS (BlocLink)                         │
├─────────────────┬─────────────────┬─────────────────────────────────┤
│      BH          │       BL         │            BS                    │
│  (Header)        │    (Links)       │        (Signatures)               │
│  RF/RV           │  RC/RL1/RL2/...  │      RS1/RS2/...                  │
└─────────────────┴─────────────────┴─────────────────────────────────┘
```

**BH - Bloc Header (En-tête)** :
- Format : `RF/RV`
- **RF** (Registre de Forme) : `APP:TYP`
  - `APP` = "nebule" (application)
  - `TYP` = "link" (type)
- **RV** (Registre de Version) : `VER:SUB`
  - `VER` = Version majeure (ex: "2")
  - `SUB` = Version mineure (ex: "0")
- Exemple : `nebule:link/2:0`

**BL - Bloc Links (Conteneur de liens)** :
- Format : `RC/RL1/RL2/RL3/...`
- **RC** (Registre de Chronologie) : `MOD>CHR`
  - `MOD` = Mode (actuellement toujours "0")
  - `CHR` = Timestamp numérique commençant par 0 (ex: "020211018195523")
- **RL** (Registre de Lien) : Voir section suivante

**BS - Bloc Signature** :
- Format : `RS1/RS2/RS3/...`
- **RS** (Registre de Signature) : `EID>SIG`
  - `EID` = Identifiant de l'entité signataire (hash hexadécimal)
  - `SIG` = Signature cryptographique (hexadécimal)
- Plusieurs signatures possibles (jusqu'à `linkMaxRS`)

#### 2. Registre de lien (RL)

Un **registre de lien** représente une relation entre nœuds. Format : `REQ>NID1>NID2>NID3>...`

**Composants séparés par ">"** :

```
┌─────────────────────────────────────────────────────────────────┐
│                   REGISTRE DE LIEN (LinkRegister)                   │
├──────────┬──────────────┬──────────────┬──────────────┬─────────┤
│   REQ    │     NID1      │     NID2      │     NID3      │   ...   │
│ (Action) │ (Source)      │ (Cible)       │ (Meta)        │         │
└──────────┴──────────────┴──────────────┴──────────────┴─────────┘
```

**REQ - Type de requête/action** (1 caractère) :

| Code | Nom | Description | NID requis | Méta requis |
|------|-----|-------------|------------|-------------|
| `l` | Link | Relation orientée entre deux objets | 2 | Optionnel |
| `f` | Fork | Relation de dérivation/parenté | 2-3 | Optionnel |
| `u` | Update | Mise à jour d'un objet par un autre | 2 | Non |
| `d` | Delete | Marque un objet comme à supprimer | 1 | Non (doit être 0) |
| `e` | Equivalent | Définit des objets interchangeables | 2+ | Non |
| `c` | Concealed | Lien dissimulé/chiffré pour anonymisation | 1+ | Oui (clé chiffrée) |
| `k` | Encrypted | Désigne la version chiffrée d'un objet | 2 | Non |
| `s` | Subdivision | Fragment d'un objet | 2+ | Oui (intervalle x-y) |
| `x` | Delete Link | Suppression d'un lien existant | 2-3 | Non |

**NID - Identifiant de nœud** :
- Hash hexadécimal (ex: sha256)
- Taille variable (64 à 8192 caractères)
- Au moins 1 NID requis, jusqu'à `linkMaxUIDbyRL` (défaut: 4)
- Pour les liens de type `d` (delete), NID2 et NID3 doivent être "0"

#### 3. Exemples concrets

```php
// Lien simple entre deux objets
l>abc123...def456...>ghi789...

// Lien avec méta (contexte)
f>abc123...>def456...>ghi789...  // Dérivé avec contexte

// Suppression d'objet
d>abc123...>0>0

// Lien dissimulé (chiffré)
c>destinataireEID>lien_chiffré_en_hex>clé_chiffrée_en_hex

// Subdivision d'objet (fragment)
s>sourceOID>fragmentOID>0-1024  // Bytes 0 à 1024
```

---

### Stockage physique dans le dossier `l/`

Les blocs de liens sont persistés dans le système de fichiers sous le dossier **`pub/l/`** 
(défini par `References::LINKS_FOLDER = 'l'`).

#### Organisation des fichiers :

```
nebule-php/
└── pub/
    └── l/                          # Dossier des liens
        ├── abc123...def456         # Fichier pour le nœud abc123...def456
        │   ├── BH1_BL1_BS1          # Ligne 1 : Bloc de liens 1
        │   └── BH2_BL2_BS2          # Ligne 2 : Bloc de liens 2
        │
        ├── def456...ghi789         # Fichier pour le nœud def456...ghi789
        │   └── BH3_BL3_BS3
        │
        ├── f                       # Fichier historique global
        │   ├── BH_historique1_BS1
        │   └── BH_historique2_BS2
        │
        └── entityEID-signerEID      # Fichier de liens dissimulés
            └── BH_obfuscated_BS
```

#### Règles de stockage :

1. **Un fichier par NID source** : Chaque nœud qui est source d'un lien a son propre fichier
2. **Un bloc par ligne** : Chaque ligne d'un fichier contient un **bloc de liens complet** (BH_BL_BS)
3. **Multiple écriture** : Un bloc de liens est écrit dans **tous les fichiers** des NID qu'il référence
4. **Fichier historique** : `pub/l/f` contient tous les blocs de liens créés
5. **Liens dissimulés** : Stockés dans des fichiers au format `{EID}-{SIGNER}`

#### Fonctions de gestion (ioInterface) :

| Fonction | Description | Fichier |
|----------|-------------|--------|
| `setBlockLink($oid, $link)` | Écrit un bloc de liens dans le fichier du nœud | `102_io_disk.php` |
| `getBlockLinks($oid)` | Lit tous les blocs de liens d'un nœud | `102_io_disk.php` |
| `getObfuscatedLinks($entity, $signer)` | Lit les liens dissimulés | `102_io_disk.php` |

---

### Limites techniques

| Constante | Valeur | Description |
|-----------|--------|-------------|
| `BlocLink::LINK_MAX_BL_SIZE` | 16384 | Taille max du bloc BL (octets) |
| `BlocLink::LINK_MAX_BS_SIZE` | 16384 | Taille max du bloc BS (octets) |
| `BlocLink::LINK_MAX_RL_SIZE` | 4096 | Taille max d'un registre RL (octets) |
| `linkMaxRL` | Configurable | Nombre max de registres par bloc |
| `linkMaxUIDbyRL` | Configurable | Nombre max de NID par registre (défaut: 4) |
| `linkMaxRS` | Configurable | Nombre max de signatures par bloc |

---

### Classes impliquées

| Classe | Fichier | Rôle |
|--------|--------|------|
| `BlocLink` | `501_bloclink.php` | Gestion d'un bloc de liens complet |
| `LinkRegister` | `502_link_register.php` | Gestion d'un registre de lien individuel |
| `blocLinkInterface` | `500_link_interface.php` | Interface pour les blocs de liens |
| `linkInterface` | `500_link_interface.php` | Interface pour les registres de liens |

---

## 🔧 Règles de développement

### 1. Fonctions de logging
- **Toujours** utiliser des IDs uniques de 8 caractères hexadécimaux
- **Ne pas** utiliser `00000000` en production
- **Conserver** `1111c0de` pour le tracking des entrées de fonction
- Utiliser les niveaux de log appropriés :
  - `debug` : Informations de débogage temporaires
  - `info` : Événements normaux
  - `warning` : Situations anormales mais gérées
  - `error` : Erreurs critiques

### 2. Gestion des erreurs
- Préférer retourner des chaînes vides (`''`) plutôt que `null` pour les NID
- Toujours vérifier l'existence des nœuds avec `io_checkNodeHaveLink()`
- Valider les signataires avec `blk_filterBySigners()`

### 3. Refactorisation
- Extraire la logique répétitive dans des sous-fonctions
- Maintenir la compatibilité ascendante avec les variables globales
- Conserver les anciennes fonctions avec le suffixe `_legacy` et le tag `@deprecated`
- Supprimer les commentaires de type `// FIXME` après résolution

### 4. Nommage
- Utiliser le préfixe `app_` pour les fonctions applicatives
- Utiliser le préfixe `lib_` pour les fonctions de bibliothèque
- Utiliser le préfixe `blk_` pour les fonctions de bloc/filtre
- Utiliser le préfixe `nod_` pour les fonctions de nœud
- Utiliser le préfixe `lnk_` pour les fonctions de lien
- Utiliser le préfixe `obj_` pour les fonctions d'objet

### 5. Types PHP
- Toujours utiliser les **types stricts** (`declare(strict_types=1)`)
- Préférer les types scalaires : `string`, `array`, `bool`, `int`
- Éviter `mixed` et `void` quand une valeur de retour est possible
- Utiliser les types de retour explicites dans les PHPDoc

### 6. Documentation
- **Obligatoire** : PHPDoc pour toutes les fonctions publiques
- Inclure dans chaque PHPDoc :
  - Description de la fonction
  - `@param` pour chaque paramètre (avec type)
  - `@return` pour le type de retour
  - `@deprecated` si la fonction est obsolète
  - Tags `@noinspection` pour supprimer les warnings IDE si nécessaire

---

## 🔄 Processus de build et déploiement

### Script principal : prep_loop_code_master_php.sh

Script bash (version 020260714) qui prépare l'environnement de développement et génère le code final.

#### Fonction `work_full_reinit()`
**Initialise complètement l'environnement de développement :**

1. **Prépare les espaces masters** :
   - `~/puppetmaster.nebule.org`
   - `~/security.master.nebule.org`
   - `~/code.master.nebule.org`
   - `~/time.master.nebule.org`
   - `~/directory.master.nebule.org`
   - `~/test.nebule.org`

2. **Génère les clés RSA** pour les autorités :
   - Puppetmaster : RSA 4096 bits
   - Security authorities : RSA 2048 bits
   - Code authorities : RSA 2048 bits
   - Time authorities : RSA 1024 bits
   - Directory authorities : RSA 1024 bits

3. **Configure les fichiers `c`** (configuration) dans chaque espace avec :
   - Référence au puppetmaster
   - Branche de code : `develop`
   - Permissions adaptées à chaque espace

4. **Crée les objets initiaux** :
   - Clés publiques/privées des autorités
   - Certificats PEM
   - Entités (puppetmaster, cerberus, bachue, kronos, asabiyya)

5. **Crée les liens initiaux** entre les autorités

6. **Définir les permissions** sur les dossiers `l/` et `o/`

---

#### Fonction `work_refresh()`
**Assemble la librairie orientée objet (Lib POO) et prépare le déploiement :**

1. **Calcule les hashs** des types et référentiels :
   ```bash
   echo -n 'application/x-pem-file' | sha256sum | cut -d' ' -f1
   # → pemOID
   echo -n 'nebule/objet/type' | sha256sum | cut -d' ' -f1
   # → typeRID
   echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1
   # → nameRID
   ```

2. **Crée `lib_nebule.php`** (Lib POO assemblée) :
   ```php
   <?php
   declare(strict_types=1);
   namespace Nebule\Library;
   
   // ========================================================================================
   //   Cache.php
   //   020260714 (date : YYYYMMDDhhmmss)
   //   a1b2c3d4e5f67890...sha2.256 (hash SHA256 du fichier)
   //   (contenu de Cache.php sans les imports redondants)
   
   // ========================================================================================
   //   Crypto.php
   //   020260714
   //   b2c3d4e5f67890a1...sha2.256
   ...
   ```

   **Règles d'assemblage** :
   - Concatène tous les fichiers de `lib_nebule/` dans l'ordre
   - `tail +4` pour sauter les 3 premières lignes (déclaration namespace, use)
   - Supprime les lignes :
     - `use Nebule\Library`
     - `use Nebule\Application`
     - `/** @noinspection`
   - Ajoute un séparateur entre chaque fichier avec :
     - Nom du fichier
     - Date de modification (format : `0YYYMMDDhhmmss`)
     - Hash SHA256 du fichier

3. **Assemble les applications** :
   - `applications/atrium.php` + tous les modules + toutes les traductions
   - Même principe de nettoyage des imports

4. **Calcule et stocke les hashs** :
   - `bootstrap_hash` : Hash de `bootstrap.php`
   - `library_hash` : Hash de `lib_nebule.php`
   - Chaque fichier est copié dans `~/code.master.nebule.org/o/{hash}.sha2.256`

---

#### Fonction `work_dev_deploy()`
**Déploie le code pour test :**

1. Copie `bootstrap.php` vers `~/code.master.nebule.org/index.php` dans tous les espaces
2. Crée les liens pour la branche de code (`LIB_RID_CODE_BRANCH`)
3. Crée les liens pour le nom de la branche (`develop`)

**Exemple de lien créé** :
```
nebule:link/2:0_0>020260714/l>LIB_RID_CODE_BRANCH>NID_CODE_BRANCH>LIB_RID_CODE_BRANCH
```

---

### Règles d'assemblage (À RESPECTER)

1. **Ne pas modifier** les 3 premières lignes des fichiers sources :
   ```php
   <?php
   declare(strict_types=1);
   namespace Nebule\Library;
   ```

2. **Toujours supprimer** les imports redondants lors de la concaténation :
   - `use Nebule\Library\...`
   - `use Nebule\Application\...`
   - `/** @noinspection ...`

3. **Conserver l'ordre** des fichiers dans `lib_nebule/` :
   L'ordre a un impact sur le hash final et la compatibilité

4. **Les hashs doivent être uniques et déterministes** :
   - Utiliser SHA256 du contenu complet
   - Format : `{hash}.sha2.256`

5. **Structure des objets** :
   - Objets dans `o/` (objects)
   - Liens dans `l/` (links)
   - Configuration dans `c`

---

### Commandes clés

```bash
# Voir l'aide du script
./prep_loop_code_master_php.sh

# Initialisation complète de l'environnement
./prep_loop_code_master_php.sh work_full_reinit

# Build de la librairie (après modification des fichiers sources)
./prep_loop_code_master_php.sh work_refresh

# Déploiement pour test
./prep_loop_code_master_php.sh work_dev_deploy

# Réinitialisation des espaces
./prep_loop_code_master_php.sh work_full_reinit
```

---

## 📊 Conventions de code

### Constantes
- Toutes les constantes sont en **MAJUSCULES** avec des underscores
- Préfixe `LIB_` pour les constantes de la bibliothèque
- Préfixe `BOOTSTRAP_` pour les constantes du bootstrap
- Exemple : `LIB_RID_CODE_BRANCH`, `BOOTSTRAP_VERSION`

### Variables globales
- Préfixe selon leur rôle :
  - `$codeBranchNID` : Branche de code courante
  - `$nebuleLocalAuthorities` : Autorités locales
  - `$bootstrapCodeIID`, `$bootstrapCodeBID`, `$bootstrapCodeSID` : Identifiants du bootstrap

### Format des RID (Resource ID)
- Format : `{contenu_hash}.{algorithme}.{taille}`
- Exemple : `50e1d0348892e7b8a555301983bccdb8a07871843ed8f392d539d3d90f37ea8c2a54d72a.none.288`
- Algorithme standard : `sha2.256`
- Taille : 288 bits pour les hashs standards

---

## 🔍 Bonnes pratiques

### Performance
- Éviter les boucles imbriquées (complexité O(n²))
- Préférer les maps de recherche (O(1)) pour les comparaisons fréquentes
- Limiter les appels `log_add` en production
- Utiliser `isset()` plutôt que `sizeof() > 0` pour vérifier les tableaux

### Sécurité
- Toujours valider les signataires avec `blk_filterBySigners()`
- Vérifier les NID avec `nod_checkNID()`
- Utiliser `io_checkNodeHaveLink()` pour vérifier l'existence des nœuds
- Ne jamais faire confiance aux entrées utilisateur sans validation

### Debug
- Les logs avec ID `00000000` sont temporaires et doivent être **supprimés avant commit**
- Utiliser `LIB_ARG_INLINE_DISPLAY` pour le debug inline
- Vérifier les hashs avec : `sha256sum fichier.php | cut -d' ' -f1`

---

## 📞 Contacts et ressources

### Autorité du projet
- **Site web** : [www.nebule.org](https://www.nebule.org)
- **Licence** : GNU GPL v3 2010-2026
- **Auteur** : Project nebule

### Versions actuelles (2026)
- Bootstrap : `020260929`
- Fonction : `020251230`
- Script prep : `020260714`

### IDs de référence importants

| Type | RID | Description |
|------|-----|-------------|
| Code Branch | `50e1d0348892e7b8a555301983bccdb8a07871843ed8f392d539d3d90f37ea8c2a54d72a.none.288` | Branche de code par défaut |
| Security Authority | `a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288` | Autorité de sécurité |
| Code Authority | `2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288` | Autorité de code |
| Time Authority | `bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288` | Autorité temporelle |
| Directory Authority | `0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288` | Autorité de répertoire |
| Interface Bootstrap | `fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288` | Interface bootstrap |
| Interface Library | `780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288` | Interface bibliothèque |

### Algorithmes
- **Hash par défaut** : `sha2.256` (SHA-256)
- **Algorithmes supportés** : `sha2.224`, `sha2.256`, `sha2.384`, `sha2.512`
- **Algorithmes RSA** : `rsa.1024`, `rsa.2048`, `rsa.4096`

---

## 🚀 Workflow de développement typique

1. **Modifier un fichier source** dans `lib_nebule/` ou `applications/`
2. **Exécuter le build** :
   ```bash
   ./prep_loop_code_master_php.sh work_refresh
   ```
3. **Vérifier les hashs** :
   ```bash
   sha256sum lib_nebule.php
   sha256sum bootstrap.php
   ```
4. **Déployer pour test** :
   ```bash
   ./prep_loop_code_master_php.sh work_dev_deploy
   ```
5. **Tester** via le navigateur ou l'interface CLI
6. **Valider** que tous les liens sont correctement signés

---

## 🎯 Particularités du projet

### Nommage
- **nebule et les applications s'écrivent en minuscules** : y compris la première lettre (ex: `nebule`, `sylabe`, `messae`, pas `Nebule` ou `Sylabe`)
- **Fichiers** : Les noms de fichiers de la librairie orientée objet suivent la numérotation par série (ex: `101_io.php`, `102_io_disk.php`)

### Documentation
- **Structure hiérarchique** : La documentation utilise un système de sections imbriquées avec des codes du type OALCS où :
  - **OA** = Objet / Application
  - **L** = Library (Librairie)
  - **C** = Configuration
  - **S** = Stockage
  - Chaque sous-section est numérotée (ex: OALCS, OALD, OALC, etc.)
- **Niveaux** : Les titres utilisent `Displays::docDispTitle(niveau, code, titre)` avec des niveaux allant de 1 à 5
- **Format** : Préférer les listes aux tableaux pour la documentation technique

### Gestion des options
- **Option JSON unique** : Pour les configurations complexes (comme les stockages), utiliser une seule option JSON plutôt que des options multiples
- **Noms d'options fixes** : Le système de configuration ne gère pas les options avec noms variables (ex: `ioStorage_{name}_* ` n'est pas supporté)
- **Valeurs par défaut** : Toujours fournir des valeurs par défaut pour les nouvelles options

### Accès web direct
- **Convention obligée** : Le stockage par défaut de type `disk` **doit** utiliser les chemins `/l` et `/o` pour permettre l'accès direct HTTP aux objets et liens
- **Implications** : Ces répertoires doivent être accessibles directement par le serveur web sans passer par le code PHP
- **Stockages supplémentaires** : Les autres stockages de type disk peuvent utiliser des chemins personnalisés, mais le stockage `default` est toujours forcé à `/l` et `/o`

### Workflow de validation
- **Processus** : L'utilisateur effectue lui-même les commits **uniquement après** :
  1. Mise à jour complète de la documentation
  2. Vérification que le code fonctionne correctement dans l'environnement de test
  3. Validation des modifications apportées
- **Important** : Ne jamais committer sans validation préalable de l'utilisateur

### Apprentissage et progression
- **Objectif pédagogique** : Le projet nebule est aussi un outil d'apprentissage et de progression dans divers domaines, notamment la programmation
- **Compréhension requise** : L'utilisateur **a besoin de comprendre** les modifications apportées avant de les valider
- **Explications nécessaires** : Toujours expliquer :
  - La raison des modifications
  - L'impact sur le code existant
  - Les bénéfices attendus
  - Les éventuels risques ou limitations
- **Pédagogie** : Proposer des solutions qui permettent à l'utilisateur de progresser dans sa maîtrise du code et des concepts

---

## ⚠️ Pièges à éviter

- ❌ **Ne pas modifier** l'ordre des fichiers dans `lib_nebule/` sans raison valable
- ❌ **Ne pas ajouter** de `use` statements dans les fichiers sources (ils seront supprimés à l'assemblage)
- ❌ **Ne pas utiliser** d'IDs de log dupliqués (sauf exceptions documentées)
- ❌ **Ne pas oublier** de supprimer les logs avec ID `00000000` avant commit
- ❌ **Ne pas modifier** les 3 premières lignes des fichiers (namespace et use)
- ❌ **Ne pas casser** la compatibilité ascendante sans version majore

---

*Dernière mise à jour : 2026-10-01*
*Maintenu par : Project nebule*
