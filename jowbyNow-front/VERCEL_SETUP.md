# Configuration Vercel - JobNow Frontend

## Etapes Rapides de Deploiement

### 1. Connecter le Repository

1. Allez sur [vercel.com](https://vercel.com)
2. Cliquez sur "Add New Project"
3. Importez depuis GitLab : `projet_joby_now_v2/backend_joby_now`

### 2. Configuration du Projet

**Framework Preset:** Vite

**Root Directory:** `jowbyNow-front` ⚠️ IMPORTANT

**Build Settings:**
- Build Command: `npm run build`
- Output Directory: `dist`
- Install Command: `npm install`

### 3. Variables d'Environnement

Ajoutez ces variables dans Vercel Dashboard > Settings > Environment Variables :

| Variable | Valeur | Environnement |
|----------|--------|---------------|
| `VITE_API_URL` | `https://votre-backend.com/api` | Production |
| `VITE_TURNSTILE_SITE_KEY` | `0x4AAAAABnvjF_NO5kPM5rJ9` | All |

**⚠️ Important:** Remplacez `https://votre-backend.com/api` par l'URL reelle de votre API backend.

### 4. Deployer

Cliquez sur "Deploy" et attendez 2-3 minutes.

### 5. Configuration Backend CORS

Apres le deploiement, copiez votre URL Vercel (ex: `https://jobnow.vercel.app`)

Dans votre backend `.env`, ajoutez :
```env
FRONTEND_URL=https://jobnow.vercel.app
```

Le fichier `config/cors.php` est deja configure pour accepter :
- Tous les domaines `*.vercel.app`
- Votre domaine personnalise (via `FRONTEND_URL`)

### 6. Tester

1. Ouvrez votre site Vercel
2. Testez la connexion/inscription
3. Verifiez qu'il n'y a pas d'erreurs CORS dans la console

---

## Domaine Personnalise (Optionnel)

### Ajouter un Domaine

1. Vercel Dashboard > Settings > Domains
2. Ajoutez votre domaine (ex: `www.jobnow.ma`)
3. Configurez les DNS :

**Type A Record:**
```
Host: @
Value: 76.76.21.21
```

**Type CNAME Record:**
```
Host: www
Value: cname.vercel-dns.com
```

4. Attendez la propagation DNS (1-24h)

### Mettre a Jour le Backend

Apres avoir configure le domaine personnalise :

```env
FRONTEND_URL=https://www.jobnow.ma
```

---

## Deployments Automatiques

Vercel deploie automatiquement :
- **Production:** Chaque push sur la branche `main`
- **Preview:** Chaque push sur d'autres branches

---

## Troubleshooting

### Erreur CORS

**Symptome:** `Access to fetch at 'https://api...' from origin 'https://...vercel.app' has been blocked by CORS policy`

**Solution:**
1. Verifiez que `FRONTEND_URL` est configure dans le backend
2. Verifiez que le backend est accessible (pas de firewall)
3. Verifiez que le backend retourne les headers CORS corrects

### Build Failed

**Symptome:** Le build echoue sur Vercel

**Solutions:**
- Verifiez que `Root Directory` est bien `jowbyNow-front`
- Verifiez que toutes les dependances sont dans `package.json`
- Verifiez les logs de build pour voir l'erreur exacte

### Variables d'Environnement Non Chargees

**Symptome:** `VITE_API_URL is undefined`

**Solution:**
- Les variables doivent commencer par `VITE_`
- Redeployez apres avoir ajoute les variables
- Verifiez qu'elles sont bien dans "Production" environment

---

## Support

- Documentation Vercel : https://vercel.com/docs
- Support JobNow : support@jobnow.com
