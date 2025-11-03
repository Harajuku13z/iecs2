# IESCA - Plateforme Éducative

Plateforme web complète pour l'Institut d'Enseignement Supérieur de la Côte Africaine (IESCA).

## 🚀 Technologies Utilisées

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5
- **Base de données**: SQLite (par défaut)
- **Authentification**: Laravel Breeze

## ✨ Fonctionnalités

### 🌐 Site Vitrine Public
- Page d'accueil dynamique
- Présentation des formations/filières
- Processus d'admission en ligne
- Design responsive avec Bootstrap 5

### 👨‍💼 Espace Administration (/admin)
- Dashboard avec statistiques
- CRUD complet pour:
  - Filières
  - Niveaux
  - Classes
  - Cours
  - Utilisateurs (Admin, Enseignants, Étudiants)
- Gestion des candidatures (validation, affectation)
- Configuration des paramètres du site
- Attribution Enseignant-Cours-Classe

### 👨‍🏫 Espace Enseignant (/enseignant)
- Tableau de bord personnalisé
- Liste des cours assignés
- Gestion des notes (saisie manuelle ou import CSV)
- Partage de ressources pédagogiques

### 👨‍🎓 Espace Étudiant (/etudiant)
- Suivi de candidature (pour les candidats)
- Relevé de notes avec calcul de moyenne
- Accès aux ressources pédagogiques
- Informations de classe

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js & npm

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/Harajuku13z/iecs2.git
cd IECS2
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Le projet utilise SQLite par défaut. La base de données est déjà configurée dans `.env`:
```
DB_CONNECTION=sqlite
```

5. **Exécuter les migrations et seeders**
```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

6. **Compiler les assets**
```bash
npm run build
```

7. **Démarrer le serveur**
```bash
php artisan serve
```

Le site sera accessible à: `http://localhost:8000`

## 🔐 Accès par Défaut

Après l'exécution du seeder, vous pouvez vous connecter avec:

- **Email**: admin@iesca.com
- **Mot de passe**: password

## 📁 Structure du Projet

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Contrôleurs admin
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/                 # Modèles Eloquent
├── database/
│   ├── migrations/             # Migrations de base de données
│   └── seeders/                # Seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Vues administration
│   │   ├── enseignant/         # Vues enseignant
│   │   ├── etudiant/           # Vues étudiant
│   │   ├── public/             # Vues site public
│   │   └── layouts/            # Layouts Blade
│   ├── css/
│   └── js/
└── routes/
    └── web.php                 # Routes de l'application
```

## 🎯 Rôles Utilisateurs

Le système gère 4 types de rôles:

1. **Admin**: Accès complet à l'administration
2. **Enseignant**: Gestion des notes et ressources
3. **Étudiant**: Consultation des notes et ressources
4. **Candidat**: Suivi de candidature

## 📝 Modèles de Données

- **User**: Utilisateurs (avec rôles)
- **Filiere**: Filières d'études
- **Niveau**: Niveaux académiques (L1, L2, L3, M1, M2)
- **Classe**: Classes (combinaison Filière + Niveau)
- **Cours**: Cours avec coefficients
- **Note**: Notes des étudiants
- **Candidature**: Dossiers de candidature
- **Ressource**: Ressources pédagogiques
- **Setting**: Paramètres configurables du site

## 🔧 Configuration

Les paramètres du site sont gérables via l'interface admin (`/admin/settings`):

- `homepage_title`: Titre de la page d'accueil
- `inscription_start_date`: Date de début des inscriptions
- `frais_mensuels`: Frais de scolarité mensuels
- `banner_image`: Image de bannière

## 🚦 Routes Principales

- `/` - Page d'accueil
- `/formations` - Liste des formations
- `/admission` - Procédure d'admission
- `/login` - Connexion
- `/register` - Inscription
- `/admin/dashboard` - Dashboard admin
- `/enseignant/dashboard` - Dashboard enseignant
- `/etudiant/dashboard` - Dashboard étudiant

## 🤝 Contribution

Les contributions sont les bienvenues! N'hésitez pas à ouvrir une issue ou une pull request.

## 📄 Licence

Ce projet est sous licence MIT.

## 👨‍💻 Auteur

Développé pour l'IESCA - Institut d'Enseignement Supérieur de la Côte Africaine
