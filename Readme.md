# 🚀 JobNow - Plateforme de Recrutement Intelligente

> Une plateforme ATS (Applicant Tracking System) moderne qui connecte les talents avec les meilleures opportunités d'emploi au Maroc.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![React](https://img.shields.io/badge/React-19.x-61DAFB?style=flat&logo=react)](https://reactjs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-6.0-3178C6?style=flat&logo=typescript)](https://www.typescriptlang.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table des Matières

- [Vue d'ensemble](#-vue-densemble)
- [Fonctionnalités](#-fonctionnalités)
- [Architecture Technique](#-architecture-technique)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [API Documentation](#-api-documentation)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Contribution](#-contribution)
- [Support](#-support)

---

##  Vue d'ensemble

**JobNow** est une plateforme de recrutement de nouvelle génération qui révolutionne la façon dont les entreprises trouvent des talents et dont les candidats découvrent des opportunités professionnelles au Maroc.

###  Points Forts

- **Intelligence Artificielle** : Assistant RH intelligent (JobyBot) avec RAG pour des recommandations contextuelles
- **Multi-rôles** : Expériences optimisées pour candidats et entreprises
- **Temps Réel** : Notifications instantanées et alertes emploi personnalisées
- **Multilingue** : Support complet FR/EN/AR (Darija)
- **Sécurisé** : Protection Cloudflare Turnstile, rate limiting, validation avancée
- **Performant** : Caching intelligent, indexation optimale, jobs en arrière-plan

---

##  Fonctionnalités

### 👤 Pour les Candidats

#### 🔐 Authentification & Profil
- **Inscription sécurisée** avec vérification email (code à 6 chiffres)
- **Connexion Google OAuth** pour une expérience fluide
- **Gestion de profil complète** : CV, photo, expérience, localisation
- **Réinitialisation de mot de passe** sécurisée

#### 💼 Recherche d'Emploi
- **Recherche avancée** avec filtres multiples :
  - Type de contrat (CDI, CDD, Stage, Freelance)
  - Localisation (toutes les villes du Maroc)
  - Mots-clés dans titre et description
  - Salaire et compétences requises
- **Autocomplétion intelligente** pour :
  - Titres de postes
  - Villes
  - Noms d'entreprises
- **Recherches populaires** pour découvrir les tendances
- **Pagination optimisée** pour une navigation fluide

#### 📝 Candidatures
- **Postuler en un clic** avec upload de CV (PDF/DOC/DOCX)
- **Lettre de motivation** personnalisée
- **Suivi des candidatures** en temps réel :
  - En attente
  - Acceptée
  - Refusée
  - Convoquée (entretien)
- **Historique complet** de toutes vos candidatures
- **Protection anti-doublon** : impossible de postuler deux fois au même poste

#### ⭐ Favoris & Alertes
- **Sauvegarder des offres** pour postuler plus tard
- **Alertes emploi personnalisées** :
  - Mots-clés spécifiques
  - Types de contrat préférés
  - Localisations ciblées
  - Fréquence configurable (instantané, quotidien, hebdomadaire)
- **Notifications email automatiques** pour les nouvelles offres correspondantes

#### 📊 Tableau de Bord Analytique
- **Vue d'ensemble** de votre activité :
  - Nombre total de candidatures
  - Candidatures récentes (30 derniers jours)
  - Taux de succès (acceptations)
  - Temps de réponse moyen des entreprises
- **Répartition par statut** (graphiques interactifs)
- **Répartition par type de contrat**
- **Historique des candidatures récentes**
- **Nombre d'offres sauvegardées**

#### 🤖 Assistant IA - JobyBot
- **Chatbot intelligent** alimenté par Groq AI (Llama 3.1)
- **RAG (Retrieval-Augmented Generation)** : réponses basées sur les vraies offres d'emploi
- **Multilingue** : répond en français, anglais ou darija selon votre langue
- **Conseils personnalisés** :
  - Recommandations d'offres
  - Conseils pour CV et lettres de motivation
  - Préparation aux entretiens
  - Stratégies de recherche d'emploi
- **Disponible 24/7** sur toutes les pages

### 🏢 Pour les Entreprises

#### 🔐 Authentification & Profil
- **Inscription entreprise** avec informations complètes :
  - Raison sociale
  - Adresse
  - Téléphone
  - Logo (optionnel)
- **Connexion sécurisée** avec protection anti-bot
- **Gestion du profil entreprise**

#### 📢 Gestion des Offres d'Emploi
- **Créer des offres** avec formulaire complet :
  - Titre du poste
  - Description détaillée
  - Compétences requises (tags multiples)
  - Type de contrat
  - Fourchette salariale
  - Localisation
  - Date d'expiration
- **Liste de vos offres** avec statuts :
  - Ouverte
  - Fermée
  - Pourvue
- **Fermeture automatique** des offres expirées (tâche planifiée)
- **Modification et suppression** d'offres

#### 📥 Gestion des Candidatures
- **Vue centralisée** de toutes les candidatures reçues
- **Filtrage par offre** et par statut
- **Consultation des CV** en ligne
- **Actions sur candidatures** :
  - ✅ Accepter
  - ❌ Refuser
  - ⭐ Évaluer (note de 1 à 5 + commentaire)
  - 📧 Convoquer à un entretien
- **Envoi automatique d'emails** :
  - Convocation à l'entretien (date, heure, lieu)
  - Notifications de changement de statut

#### 🔔 Notifications en Temps Réel
- **Alertes instantanées** pour :
  - Nouvelles candidatures reçues
  - Candidatures mises à jour
- **Centre de notifications** avec :
  - Notifications non lues
  - Marquage comme lu
  - Historique complet

#### 📊 Tableau de Bord Analytique Entreprise
- **Statistiques globales** :
  - Nombre total d'offres publiées
  - Offres actives vs fermées
  - Total de candidatures reçues
  - Candidatures récentes (30 jours)
  - Taux d'acceptation
  - Temps moyen avant première candidature
- **Répartition des candidatures** :
  - Par statut (en attente, acceptée, refusée)
  - Par type de contrat
- **Top 5 des offres** les plus populaires
- **Graphiques interactifs** (Recharts)

### 🌐 Fonctionnalités Communes

#### 📧 Système d'Emails
- **Emails transactionnels automatiques** :
  - Email de vérification avec code à 6 chiffres
  - Convocation à l'entretien (date, heure, lieu)
  - Alertes emploi personnalisées (quotidien/hebdomadaire)
  - Réinitialisation de mot de passe
- **Queue system** : envoi asynchrone pour performance
- **Templates personnalisables** : emails professionnels
- **Support SMTP** : Gmail, SendGrid, Mailgun, etc.
- **Logs d'envoi** pour debugging

#### 🔍 Laravel Telescope - Monitoring & Debugging
- **Monitoring en temps réel** de l'application :
  - Requêtes HTTP (méthode, URL, durée, statut)
  - Requêtes base de données (queries, bindings, temps d'exécution)
  - Jobs en queue (statut, payload, exceptions)
  - Emails envoyés (destinataire, sujet, contenu)
  - Notifications (type, destinataire, canal)
  - Cache (hits, misses, clés)
  - Exceptions et erreurs
  - Logs applicatifs
- **Interface web intuitive** : `/telescope`
- **Filtrage avancé** par type, statut, durée
- **Recherche** dans les requêtes et logs
- **Détails complets** de chaque opération
- **Performance profiling** pour optimisation
- **Désactivable en production** pour sécurité

#### 🎨 Interface Utilisateur
- **Design moderne** avec Tailwind CSS 4
- **Mode sombre/clair** avec persistance
- **Animations fluides** (Framer Motion)
- **Responsive** : mobile, tablette, desktop
- **Accessibilité** optimisée

#### 🌍 Internationalisation
- **3 langues supportées** :
  - 🇫🇷 Français
  - 🇬🇧 English
  - 🇲🇦 العربية (Darija)
- **Détection automatique** de la langue du navigateur
- **Changement de langue** en temps réel
- **Traductions complètes** de l'interface

#### 🔒 Sécurité
- **Cloudflare Turnstile** : protection anti-bot sur tous les formulaires
- **Rate Limiting intelligent** :
  - 10 requêtes/min sur authentification (register, login, verify-email)
  - 20 requêtes/min sur OAuth Google
  - 60 requêtes/min sur recherche publique et navigation
  - 20 requêtes/min sur chatbot IA
- **Validation avancée des fichiers** :
  - Vérification du type MIME
  - Validation des magic bytes (PDF)
  - Détection d'extensions dangereuses
  - Sanitization des noms de fichiers
  - Limite de taille (5MB pour CVs)
- **Tokens JWT** (Laravel Sanctum)
- **CORS configuré** pour frontend
- **Transactions DB** pour intégrité des données
- **Middleware RBAC** : vérification des rôles sur chaque endpoint protégé

#### ⚡ Performance
- **Caching intelligent** :
  - Liste des offres (1h)
  - Détails d'offre (1h)
  - Profils utilisateurs (1h)
  - Statistiques (30min)
  - Villes (24h)
- **Indexation optimale** :
  - Index sur statuts
  - Index sur dates
  - Index sur relations (foreign keys)
  - Index sur champs de recherche
- **Jobs en arrière-plan** :
  - Envoi d'emails
  - Envoi de notifications
  - Traitement de fichiers
- **Eager loading** pour éviter N+1 queries
- **Pagination** sur toutes les listes

---

## 🏗️ Architecture Technique

### Stack Backend

```
Laravel 12 (PHP 8.2+)
├── Sanctum          → Authentification API (tokens JWT)
├── Socialite        → OAuth Google
├── Telescope        → Monitoring & debugging en temps réel
├── Pest             → Testing framework moderne
├── L5-Swagger       → Documentation API OpenAPI/Swagger
├── Queue System     → Jobs asynchrones (emails, notifications)
├── Mail System      → Emails transactionnels (SMTP)
├── Cache System     → Performance (Database/Redis)
└── Storage System   → Upload de fichiers (local/S3)
```

### Stack Frontend

```
React 19 + TypeScript 6
├── Vite 8           → Build ultra-rapide
├── React Router 7   → Navigation
├── Tailwind CSS 4   → Styling
├── i18next          → Internationalisation
├── Framer Motion    → Animations
├── Recharts         → Graphiques
├── React Hook Form  → Formulaires
├── Zod              → Validation
└── Axios            → HTTP client
```

### Base de Données

```sql
users
├── candidats
│   ├── candidatures
│   │   └── entretiens (interviews)
│   ├── saved_jobs
│   └── job_alerts
└── entreprises
    └── offres
        └── candidatures
            └── entretiens (interviews)

villes (cities)
notifications
```

**Relations clés:**
- Un `user` peut être soit `candidat` soit `entreprise` (polymorphique)
- Un `candidat` peut avoir plusieurs `candidatures`
- Une `candidature` peut avoir un `entretien`
- Une `entreprise` peut avoir plusieurs `offres`
- Une `offre` peut avoir plusieurs `candidatures`
- Les `notifications` sont liées aux users via le système Laravel

### Services Externes

- **Groq AI** : Chatbot intelligent (Llama 3.1-8b-instant)
- **Cloudflare Turnstile** : Protection anti-bot
- **Google OAuth** : Authentification sociale
- **SMTP** : Envoi d'emails (Gmail, SendGrid, Mailgun)
- **Storage** : Stockage de fichiers (local ou AWS S3)

---

## 🚀 Installation

### Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- npm ou yarn
- SQLite (dev) ou PostgreSQL (prod)

### Backend (Laravel API)

```bash
# Cloner le repository
git clone https://github.com/votre-username/jobnow.git
cd jobnow/jobnow-api

# Installer les dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données SQLite
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# Créer le lien symbolique pour le storage
php artisan storage:link

# (Optionnel) Seed la base de données
php artisan db:seed

# Lancer le serveur de développement
php artisan serve
```

L'API sera accessible sur `http://localhost:8000`

### Accéder à Laravel Telescope

Pour le monitoring et debugging en développement :
```
http://localhost:8000/telescope
```

**Note:** Telescope est automatiquement désactivé en production pour des raisons de sécurité.

### Frontend (React)

```bash
# Aller dans le dossier frontend
cd ../jowbyNow-front

# Installer les dépendances
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Lancer le serveur de développement
npm run dev
```

Le frontend sera accessible sur `http://localhost:5173`

---

## ⚙️ Configuration

### Variables d'Environnement Backend (.env)

```env
# Application
APP_NAME=JobNow
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=sqlite
# Pour PostgreSQL en production :
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=jobnow
# DB_USERNAME=postgres
# DB_PASSWORD=secret

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@jobnow.ma"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/api/auth/google/callback

# Cloudflare Turnstile
TURNSTILE_SITE_KEY=your_site_key
TURNSTILE_SECRET_KEY=your_secret_key

# Groq AI
GROQ_API_KEY=your_groq_api_key

# Frontend URL
FRONTEND_URL=http://localhost:5173
```

### Variables d'Environnement Frontend (.env)

```env
VITE_API_URL=http://localhost:8000/api
VITE_TURNSTILE_SITE_KEY=your_site_key
```

---

## �️ Outils de Développement

### Laravel Telescope

Telescope est votre tableau de bord de monitoring en temps réel pour le développement.

**Accès:** `http://localhost:8000/telescope`

**Fonctionnalités:**
- 📊 **Requests** : Toutes les requêtes HTTP avec détails complets
- 🗄️ **Queries** : Requêtes SQL avec temps d'exécution et bindings
- 📬 **Mail** : Emails envoyés avec preview du contenu
- 🔔 **Notifications** : Toutes les notifications envoyées
- 🎯 **Jobs** : Queue jobs avec statut et payload
- 💾 **Cache** : Opérations de cache (get, set, forget)
- ⚠️ **Exceptions** : Erreurs et stack traces
- 📝 **Logs** : Logs applicatifs en temps réel
- 🔐 **Gates** : Vérifications d'autorisation
- 📅 **Schedule** : Tâches planifiées

**Configuration:**
```php
// config/telescope.php
'enabled' => env('TELESCOPE_ENABLED', true),
```

**Sécurité:** Telescope est automatiquement désactivé en production.

### Postman Collection

Une collection Postman complète est disponible : `jobnow-api/JobNow-API.postman_collection.json`

**Import:**
1. Ouvrir Postman
2. File → Import
3. Sélectionner le fichier JSON
4. Configurer les variables d'environnement

**Variables:**
- `base_url` : http://localhost:8000/api
- `token` : Votre token d'authentification

### Swagger/OpenAPI Documentation

Documentation interactive de l'API avec possibilité de tester les endpoints.

**Accès:** `http://localhost:8000/api/documentation`

**Génération:**
```bash
php artisan l5-swagger:generate
```

---

## �📖 Utilisation

### Démarrage Rapide

1. **Lancer le backend** :
```bash
cd jobnow-api
php artisan serve
```

2. **Lancer la queue** (dans un autre terminal) :
```bash
php artisan queue:work
```

3. **Lancer le frontend** (dans un autre terminal) :
```bash
cd jowbyNow-front
npm run dev
```

4. **Accéder à l'application** :
   - Frontend : http://localhost:5173
   - API : http://localhost:8000
   - API Docs : http://localhost:8000/api/documentation

### Tâches Planifiées

Ajouter au crontab pour exécuter les tâches automatiques :

```bash
* * * * * cd /path-to-your-project/jobnow-api && php artisan schedule:run >> /dev/null 2>&1
```

Tâches disponibles :
- `php artisan jobs:close-expired` : Ferme les offres expirées (quotidien)
- `php artisan alerts:send daily` : Envoie les alertes quotidiennes
- `php artisan alerts:send weekly` : Envoie les alertes hebdomadaires

### Configuration Email

Le système d'emails supporte plusieurs providers :

#### Gmail (Développement)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

**Note:** Créez un "App Password" dans Google Account → Security → 2-Step Verification → App passwords

#### SendGrid (Production recommandé)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
```

#### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your_domain.mailgun.org
MAILGUN_SECRET=your_mailgun_secret
```

#### Log (Développement - pas d'envoi réel)
```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log`

---

## 📚 API Documentation

### Documentation Interactive

Accédez à la documentation Swagger complète :
```
http://localhost:8000/api/documentation
```

### Endpoints Principaux

#### Authentification
```http
POST   /api/register                    # Inscription candidat
POST   /api/login                        # Connexion
POST   /api/verify-email                 # Vérification email
POST   /api/auth/register/entreprise     # Inscription entreprise
POST   /api/forgot-password              # Mot de passe oublié
POST   /api/reset-password               # Réinitialisation
GET    /api/auth/google                  # OAuth Google
GET    /api/auth/google/callback         # Callback OAuth
GET    /api/user                         # Profil utilisateur authentifié
```

#### Profil Candidat
```http
POST   /api/candidat/profile             # Mettre à jour profil (CV, photo, etc.)
GET    /api/candidat/candidatures        # Mes candidatures
```

#### Offres d'Emploi
```http
GET    /api/jobs                         # Liste publique
GET    /api/jobs/{id}                    # Détails d'une offre
POST   /api/offres                       # Créer (entreprise)
GET    /api/entreprise/offres            # Mes offres (entreprise)
```

#### Candidatures
```http
POST   /api/candidatures                 # Postuler (candidat)
GET    /api/entreprise/candidatures      # Candidatures reçues (entreprise)
POST   /api/candidatures/{id}/accepter   # Accepter (entreprise)
POST   /api/candidatures/{id}/refuser    # Refuser (entreprise)
PUT    /api/candidatures/{id}/evaluate   # Évaluer (entreprise)
POST   /api/candidatures/{id}/convoquer  # Convoquer à entretien (entreprise)
```

#### Notifications
```http
GET    /api/entreprise/notifications     # Liste des notifications (entreprise)
POST   /api/entreprise/notifications/{id}/read  # Marquer comme lu (entreprise)
```

#### Recherche & Autocomplete
```http
GET    /api/search/autocomplete/jobs         # Suggestions de postes
GET    /api/search/autocomplete/locations    # Suggestions de villes
GET    /api/search/autocomplete/companies    # Suggestions d'entreprises
GET    /api/search/popular                   # Recherches populaires
```

#### Favoris & Alertes
```http
GET    /api/candidat/saved-jobs              # Mes favoris
POST   /api/candidat/saved-jobs/{offreId}    # Sauvegarder une offre
DELETE /api/candidat/saved-jobs/{offreId}    # Retirer des favoris
GET    /api/candidat/saved-jobs/check/{offreId}  # Vérifier si offre est sauvegardée
GET    /api/candidat/job-alerts              # Mes alertes emploi
POST   /api/candidat/job-alerts              # Créer une alerte
PUT    /api/candidat/job-alerts/{id}         # Modifier une alerte
DELETE /api/candidat/job-alerts/{id}         # Supprimer une alerte
```

#### Analytics
```http
GET    /api/candidat/analytics           # Stats candidat
GET    /api/entreprise/analytics         # Stats entreprise
```

#### Chatbot
```http
POST   /api/chat                         # Discuter avec JobyBot
```

#### Monitoring & Debugging
```http
GET    /telescope                        # Laravel Telescope (dev only)
```

### Authentification API

Toutes les routes protégées nécessitent un token Bearer :

```http
Authorization: Bearer {your_token}
```

---

## 🧪 Tests

### Backend (Pest)

```bash
cd jobnow-api

# Exécuter tous les tests
php artisan test

# Exécuter avec couverture
php artisan test --coverage

# Exécuter un test spécifique
php artisan test --filter=AuthTest

# Tests en mode watch
php artisan test --watch
```

### Tests Disponibles

- ✅ **AuthTest** : Inscription, connexion, vérification email
- ✅ **OffreTest** : Création, liste, filtres, recherche
- ✅ **CandidatureTest** : Postulation, acceptation, refus, évaluation

### Frontend (À venir)

```bash
cd jowbyNow-front

# Tests unitaires
npm run test

# Tests E2E
npm run test:e2e
```

---

## 🚢 Déploiement

### Production Checklist

#### Backend
- [ ] Configurer PostgreSQL
- [ ] Définir `APP_ENV=production`
- [ ] Définir `APP_DEBUG=false`
- [ ] Générer nouvelle `APP_KEY`
- [ ] Configurer SMTP production
- [ ] Activer HTTPS
- [ ] Configurer Redis pour cache/queue
- [ ] Optimiser autoload : `composer install --optimize-autoloader --no-dev`
- [ ] Cacher config : `php artisan config:cache`
- [ ] Cacher routes : `php artisan route:cache`
- [ ] Cacher views : `php artisan view:cache`
- [ ] Configurer supervisor pour queue workers
- [ ] Configurer cron pour scheduled tasks

#### Frontend
- [ ] Build production : `npm run build`
- [ ] Configurer variables d'environnement production
- [ ] Déployer sur CDN (Vercel, Netlify, Cloudflare Pages)
- [ ] Configurer domaine personnalisé
- [ ] Activer HTTPS

### Exemple Docker (À venir)

```bash
# Build
docker-compose build

# Lancer
docker-compose up -d

# Migrations
docker-compose exec app php artisan migrate
```

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Voici comment participer :

1. **Fork** le projet
2. **Créer** une branche feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. **Push** vers la branche (`git push origin feature/AmazingFeature`)
5. **Ouvrir** une Pull Request

### Standards de Code

- **Backend** : PSR-12, Laravel Best Practices
- **Frontend** : ESLint, Prettier
- **Tests** : Couverture minimale de 80%
- **Commits** : Conventional Commits

---

## 📝 Roadmap

### Phase 1 : MVP ✅ (Complété)
- [x] Authentification multi-rôles
- [x] Gestion des offres
- [x] Système de candidatures
- [x] Recherche et filtres
- [x] Chatbot IA
- [x] Analytics basiques

### Phase 2 : Améliorations 🚧 (En cours)
- [ ] Tests E2E frontend
- [ ] CI/CD (GitHub Actions)
- [ ] Docker configuration
- [ ] Monitoring (Sentry)
- [ ] Email templates HTML professionnels
- [ ] Telescope en production (avec authentification)
- [ ] Redis pour cache et queue

### Phase 3 : Fonctionnalités Avancées 📋 (Planifié)
- [ ] Matching IA candidat-offre
- [ ] Entretiens vidéo intégrés
- [ ] Tests d'évaluation
- [ ] Système de référencement
- [ ] Intégration LinkedIn
- [ ] Application mobile (React Native)

### Phase 4 : Enterprise 🎯 (Futur)
- [ ] Multi-tenant
- [ ] API publique
- [ ] Webhooks
- [ ] SSO (SAML)
- [ ] Rapports avancés
- [ ] Conformité GDPR

---

## � Résumé Complet des Fonctionnalités

### 🎯 Endpoints API (Total: 35+)

| Catégorie | Nombre | Exemples |
|-----------|--------|----------|
| **Authentification** | 9 | register, login, verify-email, OAuth Google |
| **Profil** | 2 | get user, update profile |
| **Offres d'emploi** | 4 | list, show, create, my offers |
| **Candidatures** | 6 | apply, accept, reject, evaluate, convoke |
| **Recherche** | 4 | autocomplete jobs/locations/companies, popular |
| **Favoris** | 4 | list, save, remove, check |
| **Alertes emploi** | 4 | list, create, update, delete |
| **Notifications** | 2 | list, mark as read |
| **Analytics** | 2 | candidat stats, entreprise stats |
| **Chatbot IA** | 1 | chat with JobyBot |

### 🗄️ Modèles de Données (Total: 10)

1. **User** - Utilisateurs (candidats + entreprises)
2. **Candidat** - Profils candidats
3. **Entreprise** - Profils entreprises
4. **Offre** - Offres d'emploi
5. **Candidature** - Candidatures/Applications
6. **Entretien** - Entretiens programmés
7. **SavedJob** - Offres sauvegardées
8. **JobAlert** - Alertes emploi
9. **Notification** - Notifications système
10. **Ville** - Villes du Maroc

### 🔧 Commandes Artisan

```bash
# Migrations & Setup
php artisan migrate              # Créer les tables
php artisan db:seed             # Peupler la base de données
php artisan storage:link        # Lier le storage public

# Tâches planifiées
php artisan jobs:close-expired  # Fermer offres expirées
php artisan alerts:send daily   # Envoyer alertes quotidiennes
php artisan alerts:send weekly  # Envoyer alertes hebdomadaires

# Queue & Jobs
php artisan queue:work          # Traiter les jobs en queue
php artisan queue:listen        # Écouter la queue en continu

# Cache
php artisan cache:clear         # Vider le cache
php artisan config:cache        # Cacher la configuration
php artisan route:cache         # Cacher les routes
php artisan view:cache          # Cacher les vues

# Documentation
php artisan l5-swagger:generate # Générer docs Swagger

# Tests
php artisan test                # Lancer tous les tests
php artisan test --coverage     # Tests avec couverture
```

### 📦 Packages Principaux

**Backend:**
- `laravel/sanctum` - Authentification API
- `laravel/socialite` - OAuth (Google)
- `laravel/telescope` - Monitoring & debugging
- `pestphp/pest` - Testing framework
- `darkaonline/l5-swagger` - Documentation API

**Frontend:**
- `react` & `react-dom` - UI library
- `react-router-dom` - Navigation
- `axios` - HTTP client
- `react-hook-form` - Gestion formulaires
- `zod` - Validation schémas
- `i18next` - Internationalisation
- `framer-motion` - Animations
- `recharts` - Graphiques
- `tailwindcss` - Styling
- `lucide-react` - Icônes

---

## �📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 👥 Équipe

- **Product Owner** : [Votre Nom]
- **Lead Developer** : [Votre Nom]
- **UI/UX Designer** : [Votre Nom]

---

## 📞 Support

- **Email** : support@jobnow.ma
- **Documentation** : https://docs.jobnow.ma
- **Issues** : https://github.com/votre-username/jobnow/issues

---

## 🙏 Remerciements

- [Laravel](https://laravel.com) - Framework PHP
- [React](https://reactjs.org) - Bibliothèque UI
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Groq](https://groq.com) - Infrastructure IA
- [Cloudflare](https://cloudflare.com) - Sécurité & CDN

---

<div align="center">

**Fait avec ❤️ au Maroc 🇲🇦**

[⬆ Retour en haut](#-jobnow---plateforme-de-recrutement-intelligente)

</div>
