# calendrier

1er projet réalisé en développement web, en autonomie.
Site web qui génère un calendrier français sur **3 années consécutives** à partir d'une année donnée, avec les jours fériés **fixes et mobiles** (calculés à partir de Pâques), le choix de la formation liée au calendrier (base de données très simple), la **sélection de jours** (passés en violet) et l'**export en PDF**.

Projet volontairement simple, sans framework : PHP, HTML et JavaScript cohabitent dans les mêmes fichiers.

## Installation
1. Importer `formations.sql` (crée la base `formation` et la table `formations`).
2. Copier `config.example.php` en `config.local.php` (ignoré par Git) et renseigner la connexion MySQL
   (ou définir les variables d'environnement `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
3. Extensions PHP requises : `pdo_mysql` et `calendar`. Ouvrir `index.php`.

## Fichiers
- `index.php` : point d'entrée (choix de l'année et de la formation).
- `calendrier.php` : page des calendriers, sélection de jours, export PDF (jsPDF + html2canvas, chargés depuis cdnjs).
- `function_optimize.php` : plage d'années valide, Pâques, jours fériés, génération du tableau.
- `config.php` : connexion PDO unique. `config.example.php` : modèle de configuration.
- `fond.jpg` : image de fond de la page d'accueil.

## Contrôles
L'année doit être comprise entre 1970 (limite de `easter_date()`) et 2097 (PHP ≥ 8.3, sinon 2035) ; la formation doit exister.
En cas d'erreur, retour à l'accueil avec un message.
