# JobNow Deployment Guide

## Production URLs

- **Frontend**: https://job-platform-dun.vercel.app
- **Backend API**: https://job-platform-api-nine.vercel.app
- **API Test Endpoint**: https://job-platform-api-nine.vercel.app/test

## Environment Variables

### Backend (job-platform-api-nine)
- `APP_URL`: https://job-platform-api-nine.vercel.app
- `FRONTEND_URL`: https://job-platform-dun.vercel.app
- `DB_HOST`: ep-floral-fog-anb36854-pooler.c-6.us-east-1.aws.neon.tech (Neon PostgreSQL)
- All other Laravel environment variables

### Frontend (job-platform-dun)
- `VITE_API_URL`: https://job-platform-api-nine.vercel.app/api
- `VITE_TURNSTILE_SITE_KEY`: 0x4AAAAABnvjF_NO5kPM5rJ9

## Deployment Status

✅ Backend deployed and working
✅ Frontend deployed
🔄 Connecting frontend to backend API

## Next Steps

1. Redeploy frontend to pick up new VITE_API_URL
2. Test login functionality
3. Configure Cloudflare Turnstile for production domain
4. Test all API endpoints

## Database

- **Provider**: Neon PostgreSQL
- **Connection**: Pooler endpoint for production
- **Migrations**: All 19 migrations completed
- **Seeders**: 106 Moroccan cities, sample companies, 10 job offers

## Issues Resolved

1. ✅ Fixed `/vendor` exclusion in `.vercelignore`
2. ✅ Disabled Telescope in production
3. ✅ Configured storage paths for Vercel `/tmp`
4. ✅ Fixed Laravel 12 bootstrap compatibility
5. ✅ Removed vite.config.js from backend

Last updated: April 30, 2026
