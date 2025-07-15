# CRM Investisseurs - Bank of Africa

Application de gestion des relations investisseurs développée pour la Direction de la Communication Financière de Bank of Africa. Cette plateforme centralise les données des investisseurs, automatise les interactions et offre des outils d'analyse avancés.

## 📋 À propos du projet

Le CRM Investisseurs est une solution interne sécurisée permettant à l'équipe de communication financière de :

- **Centraliser** toutes les informations des investisseurs dans une base structurée
- **Suivre** l'historique complet des interactions (emails, appels, réunions)
- **Automatiser** l'envoi et la réception d'emails via des adresses uniques
- **Générer** des rapports et exports personnalisés
- **Gérer** les droits d'accès avec un système de rôles granulaire

## 🛠️ Technologies utilisées

### Backend
- **Laravel 12** - Framework PHP moderne avec architecture MVC
- **PHP 8.4** - Dernière version stable avec performances optimisées
- **MySQL 8.0** - Base de données relationnelle robuste
- **Laravel Sanctum** - Authentification API sécurisée

### Frontend
- **TailwindCSS 3.4** - Framework CSS utilitaire pour un design moderne
- **DaisyUI** - Composants préfabriqués basés sur TailwindCSS
- **AlpineJS** - Framework JavaScript léger pour l'interactivité
- **Vite** - Build tool rapide pour les assets

### Packages principaux
- **Spatie Laravel Permission** - Gestion des rôles et permissions
- **Spatie Laravel ActivityLog** - Journalisation des actions utilisateurs
- **Maatwebsite Excel** - Exports Excel avancés
- **Barryvdh Laravel DomPDF** - Génération de documents PDF
- **Intervention Image** - Traitement d'images

## 📋 Prérequis système

### Requis
- **PHP** >= 8.4 avec extensions : mbstring, xml, bcmath, curl, json, openssl, tokenizer
- **Composer** >= 2.0 (gestionnaire de dépendances PHP)
- **Node.js** >= 18.0 et **npm** >= 9.0 (pour la compilation des assets)
- **MySQL** >= 8.0 ou **MariaDB** >= 10.3
- **Git** pour le versioning

### Recommandé
- **PHP 8.4** pour les meilleures performances
- **MySQL 8.0** pour les fonctionnalités avancées
- **16 GB RAM** minimum pour un environnement de développement fluide

## 🚀 Installation

### 1. Cloner le repository

```
git clone [URL_DU_REPOSITORY]
cd crm-investisseurs
```

### 2. Installer les dépendances PHP

```
composer install
```

Cette commande télécharge et installe tous les packages PHP nécessaires définis dans `composer.json`.

### 3. Installer les dépendances Node.js

```
npm install
```

Installe TailwindCSS, DaisyUI, AlpineJS et tous les outils de build frontend.

### 4. Configuration de l'environnement

Créez le fichier de configuration depuis le template :

```
cp .env.example .env
```

Générez la clé d'application Laravel :

```
php artisan key:generate
```

### 5. Configuration de la base de données

Ouvrez le fichier `.env` et configurez votre base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_investisseurs
DB_USERNAME=votre_utilisateur_mysql
DB_PASSWORD=votre_mot_de_passe_mysql
```

### 6. Créer la base de données

Connectez-vous à MySQL et créez la base de données :

```sql
CREATE DATABASE crm_investisseurs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Publier les configurations des packages

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
```

### 8. Exécuter les migrations et seeders

```
php artisan migrate
php artisan db:seed
```

Ces commandes créent toutes les tables nécessaires et insèrent les données de test.

### 9. Créer le lien symbolique pour le stockage

```
php artisan storage:link
```

Permet l'accès public aux fichiers uploadés.

### 10. Compiler les assets frontend

Pour le développement avec rechargement automatique :

```
npm run dev
```

Pour la production :

```
npm run build
```

## 🏃‍♂️ Lancement du projet

### Démarrer le serveur de développement

```
php artisan serve --port=8888
```

L'application sera accessible à l'adresse : **http://localhost:8080**

### Compilation des assets en temps réel

Dans un second terminal, lancez :

```
npm run dev
```

Cette commande surveille les changements dans les fichiers CSS et JS et les recompile automatiquement.

## 👥 Comptes de test

L'application est livrée avec trois comptes utilisateurs de démonstration :

| Rôle | Email | Mot de passe | Permissions |
|------|-------|--------------|-------------|
| **Administrateur** | admin@bankofafrica.com | password123 | Accès complet + gestion utilisateurs |
| **Éditeur** | houda@bankofafrica.com | password123 | CRUD investisseurs + interactions |
| **Lecture seule** | ahmed@bankofafrica.com | password123 | Consultation uniquement |

## 📁 Structure du projet

```
crm-investisseurs/
├── app/
│   ├── Models/              # Modèles Eloquent (User, Investor, Interaction)
│   ├── Http/Controllers/    # Contrôleurs MVC
│   ├── Http/Middleware/     # Middlewares personnalisés
│   └── Http/Requests/       # Validation des formulaires
├── database/
│   ├── migrations/          # Schémas de base de données
│   └── seeders/            # Données de test et initialisation
├── resources/
│   ├── views/              # Templates Blade
│   ├── css/                # Styles TailwindCSS
│   └── js/                 # Scripts AlpineJS
├── routes/
│   ├── web.php             # Routes web principales
│   └── api.php             # API endpoints
├── public/                 # Fichiers publics accessibles
├── storage/                # Fichiers uploadés et logs
└── tests/                  # Tests automatisés
```

## 🎯 Fonctionnalités principales

- **Authentification sécurisée** avec design Bank of Africa
- **Dashboard responsive** avec sidebar collapsible
- **Système de rôles** et permissions granulaires
- **Base de données** complète avec relations optimisées
- **Interface moderne** avec animations et micro-interactions
- **CRUD Investisseurs** - Interface de gestion complète
- **Module d'interactions** - Timeline et historique détaillé
- **Système d'emails** - Envoi/réception automatisé
- **Recherche avancée** - Filtres dynamiques et full-text
- **Exports personnalisés** - Excel, PDF avec templates

## 🔧 Commandes utiles pour les développeurs

### Base de données
```
# Recréer complètement la base avec données de test
php artisan migrate:fresh --seed

# Créer une nouvelle migration
php artisan make:migration create_nouvelle_table

# Créer un modèle avec migration et contrôleur
php artisan make:model NouveauModele -mcr
```

### Cache et optimisation
```
# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Tests
```
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage
```

## 🎨 Personnalisation du thème

Le design utilise le thème corporate de Bank of Africa avec les couleurs principales :

- **Primaire** : #2563eb (Bleu BOA)
- **Secondaire** : #64748b (Gris ardoise)
- **Accent** : #06b6d4 (Cyan)

Pour modifier les couleurs, éditez `tailwind.config.js` dans la section `daisyui.themes`.

## 🐛 Dépannage

### Erreur de mémoire PHP
Si vous rencontrez des erreurs de mémoire, augmentez la limite :

```
php -d memory_limit=512M artisan serve --port=8080
```

### Problème de permissions sur storage/
```
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Assets non compilés
```
rm -rf node_modules
npm install
npm run build
```

### Base de données corrompue
```
php artisan migrate:fresh --seed
```

---

**Version** : 1.0.0  
**Dernière mise à jour** : Mai 2025  
**Licence** : Propriétaire - Bank of Africa
