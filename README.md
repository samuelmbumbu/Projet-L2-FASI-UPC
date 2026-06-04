# SyAnFraud

SyAnFraud est une application web dark/golden de simulation et de détection de fraude transactionnelle.

## Accès de démonstration

- Admin : `admin` / `password`
- Client : `clientdemo` / `client123`
- PIN client de démonstration : `1234`

## Rôles

### Administrateur
- Dashboard global combiné des clients
- Création des comptes clients
- Transactions bloquées avec bouton de traitement
- Carte des flux globale
- Analytique globale

### Client
- Dashboard personnel
- Simulation de transaction
- Transactions bloquées
- Alertes et décisions admin
- Carte des flux personnelle
- Analytique personnelle
- Profil : modifier nom, photo de profil et PIN

## Installation

1. Copier le dossier `SyAnFraud` dans `htdocs` de MAMP.
2. Créer/importer la base avec `database/schema.sql` puis `database/seed.sql`.
3. Vérifier `backend-php/config/db.php` : port MAMP par défaut `8889`, utilisateur `root`, mot de passe `root`.
4. Lancer : `http://localhost:8888/SyAnFraud/`

## ML

L'algorithme de ML utilise la regression logistic binairy 
