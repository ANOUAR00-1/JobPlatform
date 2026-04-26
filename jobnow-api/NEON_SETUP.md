# Neon Database Setup Guide

## Step 1: Create Neon Account

1. Go to [neon.tech](https://neon.tech)
2. Click "Sign Up" (GitHub signup is fastest)
3. Verify your email

## Step 2: Create a New Project

1. Click "Create Project"
2. **Project Name:** JobNow
3. **Region:** Choose closest to your users (e.g., US East, EU West)
4. **PostgreSQL Version:** 16 (latest)
5. Click "Create Project"

## Step 3: Get Connection Details

After project creation, you'll see a connection string like:

```
postgresql://username:password@ep-cool-name-123456.us-east-2.aws.neon.tech/neondb?sslmode=require
```

**Extract these values:**

- **DB_HOST:** `ep-cool-name-123456.us-east-2.aws.neon.tech`
- **DB_DATABASE:** `neondb`
- **DB_USERNAME:** `username` (shown in Neon dashboard)
- **DB_PASSWORD:** `password` (shown in Neon dashboard)
- **DB_PORT:** `5432` (default)

## Step 4: Update Local .env File

Open `jobnow-api/.env` and update these lines with YOUR Neon credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=ep-your-project.us-east-2.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=your-username
DB_PASSWORD=your-password
DB_SSLMODE=require
```

## Step 5: Install PostgreSQL Extension (if needed)

Check if you have the PostgreSQL PHP extension:

```bash
php -m | grep pgsql
```

If not installed:

**Windows (XAMPP):**
1. Open `php.ini` (C:\xampp\php\php.ini)
2. Find `;extension=pgsql` and remove the `;`
3. Find `;extension=pdo_pgsql` and remove the `;`
4. Restart Apache

**Mac (Homebrew):**
```bash
brew install php-pgsql
```

**Linux:**
```bash
sudo apt-get install php-pgsql
```

## Step 6: Run Migrations

```bash
cd jobnow-api
php artisan migrate:fresh --seed
```

This will:
- Create all tables in Neon
- Seed initial data

## Step 7: Test Connection

```bash
php artisan tinker
```

Then run:
```php
DB::connection()->getPdo();
```

If you see a PDO object, connection is successful! ✅

## Step 8: Update Vercel Environment Variables

When deploying to Vercel, add these environment variables:

| Variable | Value |
|----------|-------|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | Your Neon host |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `neondb` |
| `DB_USERNAME` | Your Neon username |
| `DB_PASSWORD` | Your Neon password |
| `DB_SSLMODE` | `require` |

## Troubleshooting

### Error: "could not find driver"

**Solution:** Install PostgreSQL PHP extension (see Step 5)

### Error: "SQLSTATE[08006] Connection refused"

**Solution:** 
- Check DB_HOST is correct
- Verify Neon project is active
- Check firewall/network settings

### Error: "SQLSTATE[08006] SSL required"

**Solution:** Make sure `DB_SSLMODE=require` is set

### Error: "password authentication failed"

**Solution:**
- Double-check username and password from Neon dashboard
- Copy-paste to avoid typos
- Check for extra spaces

## Neon Free Tier Limits

- ✅ **Storage:** 3 GB
- ✅ **Compute:** 191.9 hours/month
- ✅ **Branches:** 10
- ✅ **Projects:** Unlimited

Perfect for development and small production apps!

## Tips

1. **Branching:** Neon allows database branches (like Git) - great for testing
2. **Scale to Zero:** Database auto-sleeps when not in use (saves resources)
3. **Backups:** Automatic point-in-time recovery
4. **Monitoring:** Check usage in Neon dashboard

## Next Steps

After Neon is set up:

1. ✅ Test locally with `php artisan serve`
2. ✅ Deploy backend to Vercel
3. ✅ Add Neon credentials to Vercel env vars
4. ✅ Run migrations on production

---

**Need Help?** 
- Neon Docs: https://neon.tech/docs
- Neon Discord: https://discord.gg/neon
