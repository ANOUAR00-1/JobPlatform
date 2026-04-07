# JobNow API

Backend API for JobNow - Job recruitment platform.

## Features

- **JNV-2**: Entreprise Registration
- **JNV-15**: Post Job Offer  
- **JNV-22**: Apply for Job

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

## Run Server

```bash
php artisan serve
```

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | ❌ | Register candidat |
| POST | `/api/login` | ❌ | Login |
| POST | `/api/auth/register/entreprise` | ❌ | Register entreprise |
| GET | `/api/jobs` | ✅ | List all jobs |
| POST | `/api/offres` | ✅ | Create job offer (entreprise) |
| POST | `/api/candidatures` | ✅ | Apply for job (candidat) |
