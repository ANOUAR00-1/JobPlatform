import React, { useState, useRef } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../../hooks/useAuth';
import { Mail, Lock } from 'lucide-react';
import api from '../../services/api';
import { Turnstile } from '@marsidev/react-turnstile';
import type { TurnstileInstance } from '@marsidev/react-turnstile';

const Login: React.FC = () => {
  const navigate = useNavigate();
  const { login } = useAuth();
  const { t } = useTranslation();
  
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [turnstileToken, setTurnstileToken] = useState('');
  const turnstileRef = useRef<TurnstileInstance>(null);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError('');
    
    if (!email || !password) {
      setError(t('auth.login.error_empty'));
      return;
    }

    if (!turnstileToken) {
      setError(t('auth.login.error_captcha'));
      return;
    }

    setLoading(true);
    try {
      const response = await api.post('/login', { 
        email, 
        password,
        'cf-turnstile-response': turnstileToken,
      });
      const { user, profile, access_token } = response.data;
      
      const fullUser = { ...user, ...profile };
      
      login(fullUser, access_token);
      
      if (fullUser.raison_social || fullUser.role === 'entreprise') {
        navigate('/entreprise');
      } else {
        navigate('/candidat');
      }
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      setError(error.response?.data?.message || 'Identifiants incorrects');
      turnstileRef.current?.reset();
      setTurnstileToken('');
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pl-10 text-sm outline-none focus:ring-1 focus:ring-[#8cedaa] focus:border-[#8cedaa] transition-all placeholder:text-slate-400 text-slate-950";
  const labelClass = "block text-xs font-semibold text-slate-700 mb-1.5";

  return (
    <div className="flex flex-col">
      <h2 className="text-2xl font-bold text-slate-950 mb-1">{t('auth.login.title')}</h2>
      <p className="text-slate-600 text-sm mb-5">{t('auth.login.subtitle')}</p>
      
      <form onSubmit={handleSubmit} className="space-y-3">
        <div>
          <label className={labelClass}>{t('auth.login.email')}</label>
          <div className="relative">
            <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input 
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              autoComplete="email"
              className={inputClass}
              placeholder="votre@email.com"
            />
          </div>
        </div>
        
        <div>
          <label className={labelClass}>{t('auth.login.password')}</label>
          <div className="relative">
            <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input 
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              autoComplete="current-password"
              className={inputClass}
              placeholder="••••••••"
            />
          </div>
        </div>

        <div className="flex justify-center pt-1">
          <Turnstile
            ref={turnstileRef}
            siteKey={import.meta.env.VITE_TURNSTILE_SITE_KEY}
            onSuccess={(token) => setTurnstileToken(token)}
            onError={() => { setTurnstileToken(''); setError(t('auth.login.error_captcha_fail')); }}
            onExpire={() => setTurnstileToken('')}
            options={{
              theme: 'light',
              size: 'flexible',
            }}
          />
        </div>

        {error && (
          <div className="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-xs font-medium">
            {error}
          </div>
        )}

        <div className="flex justify-end">
           <Link to="/forgot-password" className="text-slate-600 hover:text-slate-950 transition-colors text-xs">
             {t('auth.login.forgot', 'Mot de passe oublié ?')}
           </Link>
        </div>

        <button 
          type="submit" 
          disabled={loading}
          className="w-full px-4 py-2.5 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {loading ? t('common.loading', 'Chargement...') : t('auth.login.submit')}
        </button>
      </form>

      {/* OR Divider */}
      <div className="relative my-5">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t border-slate-200"></div>
        </div>
        <div className="relative flex justify-center text-xs">
          <span className="bg-white px-3 text-slate-500 font-medium">OR</span>
        </div>
      </div>

      {/* Google OAuth Button */}
      <button
        onClick={() => window.location.href = `${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/auth/google`}
        className="w-full px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-950 font-semibold rounded-lg transition-all flex items-center justify-center gap-2"
      >
        <svg className="w-5 h-5" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continue with Google
      </button>

      <div className="mt-5 text-center text-sm">
        <span className="text-slate-600 font-medium">{t('auth.login.no_account')} </span>
        <Link to="/register" className="text-slate-950 hover:text-[#2aa354] transition-colors font-bold">
          {t('auth.login.signup_link')}
        </Link>
      </div>
    </div>
  );
};

export default Login;
