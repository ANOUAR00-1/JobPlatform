import React, { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Lock, Loader2 } from 'lucide-react';
import api from '../../services/api';

const ResetPassword: React.FC = () => {
  const navigate = useNavigate();
  const { t } = useTranslation();
  const [searchParams] = useSearchParams();
  
  const token = searchParams.get('token');
  const emailQuery = searchParams.get('email');

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (emailQuery) {
      setEmail(emailQuery);
    }
  }, [emailQuery]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError('');
    
    if (!password || !passwordConfirmation) {
      setError(t('auth.reset.error_empty', 'Veuillez remplir tous les champs.'));
      return;
    }

    if (password !== passwordConfirmation) {
      setError(t('auth.reset.error_match', 'Les mots de passe ne correspondent pas.'));
      return;
    }

    if (!token) {
        setError(t('auth.reset.error_token', 'Jeton invalide.'));
        return;
    }

    setLoading(true);
    try {
      const response = await api.post('/reset-password', { 
          token,
          email,
          password,
          password_confirmation: passwordConfirmation 
      });
      if (response.data.success) {
        setSuccess(true);
        setTimeout(() => {
            navigate('/login');
        }, 2000);
      }
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      setError(error.response?.data?.message || 'Une erreur est survenue.');
    } finally {
      setLoading(false);
    }
  };

  if (success) {
      return (
        <div className="flex flex-col text-center items-center justify-center space-y-4">
             <div className="w-16 h-16 rounded-full bg-emerald-100 flex flex-col items-center justify-center mb-2">
                  <Lock size={32} className="text-emerald-600" />
             </div>
             <h2 className="text-2xl font-bold text-slate-950 tracking-tight mb-1">
                {t('auth.reset.success_title', 'Succès')}
             </h2>
             <p className="text-slate-600">
                {t('auth.reset.success_desc', 'Votre mot de passe a bien été réinitialisé. Redirection...')}
             </p>
        </div>
      );
  }

  return (
    <div className="flex flex-col">
      <h2 className="text-2xl font-bold text-slate-950 tracking-tight mb-1">
        {t('auth.reset.title', 'Nouveau mot de passe')}
      </h2>
      <p className="text-slate-500 text-sm mb-8 font-medium">
        {t('auth.reset.subtitle', 'Saisissez votre nouveau mot de passe.')}
      </p>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm font-semibold text-slate-950 mb-2">
            {t('auth.login.email', 'Email')}
          </label>
          <div className="relative">
            <div className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
              <Lock size={18} />
            </div>
            <input
              type="email"
              value={email}
              readOnly={!!emailQuery}
              onChange={(e) => setEmail(e.target.value)}
              disabled={!!emailQuery}
              className="w-full h-12 pl-12 pr-4 rtl:pl-4 rtl:pr-12 bg-slate-50 border border-slate-200 rounded-xl text-slate-950 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all disabled:opacity-50"
            />
          </div>
        </div>

        <div>
          <label className="block text-sm font-semibold text-slate-950 mb-2">
            {t('auth.reset.password', 'Nouveau mot de passe')}
          </label>
          <div className="relative">
            <div className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
              <Lock size={18} />
            </div>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full h-12 pl-12 pr-4 rtl:pl-4 rtl:pr-12 bg-slate-50 border border-slate-200 rounded-xl text-slate-950 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
            />
          </div>
        </div>

        <div>
          <label className="block text-sm font-semibold text-slate-950 mb-2">
            {t('auth.reset.confirm_password', 'Confirmer le mot de passe')}
          </label>
          <div className="relative">
            <div className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
              <Lock size={18} />
            </div>
            <input
              type="password"
              value={passwordConfirmation}
              onChange={(e) => setPasswordConfirmation(e.target.value)}
              className="w-full h-12 pl-12 pr-4 rtl:pl-4 rtl:pr-12 bg-slate-50 border border-slate-200 rounded-xl text-slate-950 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
            />
          </div>
        </div>

        {error && (
          <div className="p-4 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl">
            {error}
          </div>
        )}

        <button 
          type="submit" 
          disabled={loading}
          className="w-full h-12 bg-[#8cedaa] text-slate-950 font-semibold rounded-xl hover:bg-[#7bc897] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          {loading && <Loader2 className="w-4 h-4 animate-spin" />}
          {t('auth.reset.submit', 'Réinitialiser')}
        </button>
      </form>
    </div>
  );
};

export default ResetPassword;
