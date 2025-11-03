# IESCA - Plateforme Éducative

**Institut d'Enseignement Supérieur de la Côte Africaine**  
Année Académique 2025-2026

## 📋 Informations Officielles

### 📅 Dates Importantes
- **Début des inscriptions** : 16 Septembre 2025
- **Début des cours** : 03 Novembre 2025

### 💰 Frais
- **Frais d'inscription** : 30 000 FCFA
  - Carte d'étudiant : **Gratuite**
  - Tote : **Gratuite**
  - Assurance : **Gratuite**
- **Frais mensuels** : 30 000 FCFA/mois

### 🎓 Nos Filières en Licence

#### 1. Sciences et Administration des Affaires
- Management et entrepreneuriat
- Gestion des ressources humaines

#### 2. Génie Informatique
- Réseaux et télécommunications
- Informatique de gestion

#### 3. Sciences Juridiques
- Droit privé
- Droit public
- Droit des affaires

#### 4. Sciences Commerciales
- Comptabilité
- Management de la chaîne logistique
- Banque, Assurance et finances

### 📋 Documents Requis
- Photocopie du Diplôme (BAC)
- Photocopie en couleur de l'acte de naissance
- Une enveloppe kraft A4
- 1 Paquet de marqueur tableau blanc (Permanent)

### ✨ Nos Atouts
- 💻 Salle d'informatique équipée
- 📚 Bibliothèque complète
- ❄️ Classes modernes et climatisées
- 👨‍🏫 Personnel théorique et pratique
- 📹 Caméras de surveillance
- 🏢 Stage garanti en fin de formation
- 💻 Possibilité d'achat d'ordinateur à crédit

### 📞 Contact
- **Adresse** : 112, Avenue de France (Poto poto)
- **Téléphones** : +242 06 541 98 61 / +242 05 022 64 08
- **Email** : institutenseignementsupérieur@gmail.com

---

## 🚀 Technologies Utilisées

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5
- **Base de données**: SQLite (local) / MySQL (production)
- **Authentification**: Laravel Breeze
- **Animations**: AOS Library
- **Typographie**: Google Fonts (Playfair Display + Inter)

## ✨ Fonctionnalités

### 🌐 Site Vitrine Public
- Page d'accueil moderne avec hero section
- Formulaire de recherche de formation
- Section À Propos avec les 7 atouts
- Actualités (3 dernières)
- Calendrier des événements (liste + calendrier visuel)
- Processus d'admission en 4 étapes
- Page des formations détaillées
- Formulaire de candidature en ligne

### 👨‍💼 Espace Administration (/admin)
- Dashboard avec statistiques
- CRUD complet : Filières, Niveaux, Classes, Cours, Utilisateurs
- Gestion des candidatures (validation, affectation)
- Gestion des actualités
- Gestion des événements
- Configuration des couleurs du site
- Configuration des paramètres

### 👨‍🏫 Espace Enseignant (/enseignant)
- Tableau de bord personnalisé
- Liste des cours assignés
- Gestion des notes
- Partage de ressources pédagogiques

### 👨‍🎓 Espace Étudiant (/etudiant)
- Suivi de candidature
- Relevé de notes avec calcul de moyenne
- Accès aux ressources pédagogiques
- Informations de classe

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL (production) ou SQLite (développement)

### Installation Locale (SQLite)

```bash
# 1. Cloner le repository
git clone https://github.com/Harajuku13z/iecs2.git
cd iecs2

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Créer la base de données SQLite
touch database/database.sqlite

# 5. Configurer .env pour SQLite
# Changer DB_CONNECTION=mysql en DB_CONNECTION=sqlite
# Supprimer les lignes DB_HOST, DB_PORT, DB_DATABASE, etc.

# 6. Migrations et seeders
php artisan migrate:fresh
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=IESCADataSeeder
php artisan db:seed --class=ActualitesEvenementsSeeder

# 7. Créer le lien symbolique
php artisan storage:link

# 8. Compiler les assets
npm run build

# 9. Démarrer le serveur
php artisan serve
```

Accédez à : **http://localhost:8000**

### Installation Production (MySQL)

```bash
# Sur le serveur
cd /home/u570136219/public_html

# Pull depuis GitHub
git pull origin main

# Installer les dépendances
composer install --no-dev --optimize-autoloader
npm install --production

# Compiler les assets
npm run build

# Configuration
cp .env.example .env
php artisan key:generate

# Migrations
php artisan migrate --force
php artisan db:seed --class=AdminSeeder --force
php artisan db:seed --class=IESCADataSeeder --force
php artisan db:seed --class=ActualitesEvenementsSeeder --force

# Optimisation
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 775 storage bootstrap/cache
```

## 🔐 Accès par Défaut

```
Email: admin@iesca.com
Mot de passe: password
```

## 🎨 Personnalisation des Couleurs

Les couleurs du site sont configurables depuis `/admin/settings` :

- **Couleur principale** : #A66060
- **Couleur secondaire** : #9E5A59
- **Couleur claire** : #F2F2F2
- **Couleur foncée** : #595959
- **Couleur noire** : #0D0D0D

## 📁 Structure

```
├── app/
│   ├── Http/Controllers/Admin/    # Contrôleurs admin
│   ├── Http/Middleware/           # Middleware de rôles
│   └── Models/                    # Modèles Eloquent
├── database/
│   ├── migrations/                # 16 migrations
│   └── seeders/                   # Seeders avec données réelles
├── resources/
│   ├── views/
│   │   ├── admin/                 # Interface administration
│   │   ├── enseignant/            # Interface enseignant
│   │   ├── etudiant/              # Interface étudiant
│   │   └── public/                # Site vitrine
│   ├── css/
│   └── js/
└── routes/web.php                 # Routes
```

## 🔧 Commandes Utiles

```bash
# Développement
php artisan serve
npm run dev

# Nettoyer les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Production
npm run build
php artisan optimize
```

## 📊 Modèles de Données

- **User** (4 rôles : admin, enseignant, etudiant, candidat)
- **Filiere** (4 filières)
- **Niveau** (5 niveaux : L1-L3, M1-M2)
- **Classe** (combinaison filière + niveau)
- **Cours** (avec coefficients)
- **Note** (notes des étudiants)
- **Candidature** (workflow d'admission)
- **Ressource** (ressources pédagogiques)
- **Actualite** (news du site)
- **Evenement** (calendrier des événements)
- **Setting** (configuration dynamique)

## 🌐 URLs Principales

- `/` - Page d'accueil
- `/formations` - Liste des formations
- `/admission` - Procédure d'admission
- `/login` - Connexion
- `/admin/dashboard` - Dashboard admin
- `/enseignant/dashboard` - Dashboard enseignant
- `/etudiant/dashboard` - Dashboard étudiant

## 🤝 Support

Pour toute question :
- 📧 institutenseignementsupérieur@gmail.com
- 📞 +242 06 541 98 61
- 📞 +242 05 022 64 08

## 📄 Licence

© 2025 IESCA - Tous droits réservés.
