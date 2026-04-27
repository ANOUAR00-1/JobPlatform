# Vercel Environment Variables Setup

## CRITICAL: You MUST set these in Vercel Dashboard

Go to: **Vercel Dashboard → Your Backend Project → Settings → Environment Variables**

## Required Environment Variables for Backend

### Application Settings (REQUIRED)
```
APP_NAME=JobNow
APP_ENV=production
APP_KEY=base64:MV2jLlQk5HSLaB0UaBZSxbTTvMFFfIMyMG+JD6DFpeo=
APP_DEBUG=false
APP_URL=https://job-platform-api-nine.vercel.app
```

**CRITICAL**: 
- `APP_URL` should NOT have a trailing slash!
- `APP_KEY` MUST be set exactly as shown (including `base64:` prefix)

### Database (Neon PostgreSQL - Use POOLER for production)
```
DB_CONNECTION=pgsql
DB_HOST=your-neon-host-pooler.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=your_neon_username
DB_PASSWORD=your_neon_password
DB_SSLMODE=require
```

**NOTE**: Get your Neon credentials from: https://console.neon.tech/
- Use the **pooler** endpoint for production (ends with `-pooler.neon.tech`)
- Use the direct endpoint for migrations

### Session & Cache (REQUIRED for Vercel)
```
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

### Localization
```
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
```

### Mail Configuration
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jobnow.ma
MAIL_FROM_NAME=JobNow
```

**NOTE**: For Gmail, use an App Password (not your regular password): https://myaccount.google.com/apppasswords

### OAuth & API Keys
```
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://job-platform-api-nine.vercel.app/api/auth/google/callback

TURNSTILE_SITE_KEY=your_turnstile_site_key
TURNSTILE_SECRET_KEY=your_turnstile_secret_key

GROQ_API_KEY=your_groq_api_key
```

**NOTE**: Replace the placeholder values with your actual API keys from:
- Google OAuth: https://console.cloud.google.com/
- Cloudflare Turnstile: https://dash.cloudflare.com/
- Groq AI: https://console.groq.com/

### Frontend URL (IMPORTANT for CORS)
```
FRONTEND_URL=https://job-platform-dun.vercel.app
```

**NOTE**: This is your FRONTEND URL (where users access the site), NOT the API URL!

## After Setting Environment Variables

1. Go to Vercel Deployments
2. Click "Redeploy" on the latest deployment
3. Wait for deployment to complete
4. Check the Function Logs if still getting errors

## Frontend Environment Variable

In your **FRONTEND** Vercel project, set:
```
VITE_API_URL=https://job-platform-api-nine.vercel.app/api
```

**IMPORTANT**: The API URL should include `/api` at the end!

## Troubleshooting HTTP 500 Errors

If you still get HTTP 500 after setting all variables:

1. **Check Vercel Function Logs**:
   - Go to Vercel Dashboard → Deployments → Click on latest deployment
   - Click on "Functions" tab
   - Click on "api/index.php" function
   - Check the logs for detailed error messages

2. **Common Issues**:
   - Missing `APP_KEY` environment variable
   - Wrong `APP_KEY` format (must start with `base64:`)
   - Database connection issues (check DB credentials)
   - Missing required environment variables

3. **Verify Environment Variables**:
   - Make sure ALL variables are set in "Production" environment
   - After adding variables, you MUST redeploy (not just refresh)

## Laravel 12 Compatibility

This setup uses Laravel 12's new bootstrap method (`$app->handleRequest()`) which is required for proper Vercel deployment.
