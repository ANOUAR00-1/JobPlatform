import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Mail, ArrowLeft } from 'lucide-react';
import api from '../../services/api';

const ForgotPassword: React.FC = () => {
  const { t } = useTranslation();
  
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError('');
    setMessage('');
    
    if (!email) {
      setError(t('auth.forgot.error_empty', 'Veuillez saisir votre adresse email.'));
      return;
    }

    setLoading(true);
    try {
      const response = await api.post('/forgot-password', { email });
      if (response.data.success) {
        setMessage(response.data.message || 'Lien de réinitialisation envoyé.');
      }
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      setError(error.response?.data?.message || 'Une erreur est survenue.');
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pl-11 text-sm outline-none focus:ring-2 focus:ring-[#8cedaa]/20 focus:border-[#8cedaa] transition-all placeholder:text-slate-400 text-slate-950 shadow-sm font-medium";
  const labelClass = "block text-sm font-bold text-slate-950 mb-2";

  return (
    <div className="flex flex-col">
      <Link to="/login" className="flex items-center gap-2 text-slate-600 hover:text-slate-950 transition-colors mb-6 font-semibold text-sm w-fit">
        <ArrowLeft size={18} />
        {t('common.back', 'Retour')}
      </Link>

      <h2 className="text-3xl font-display font-bold text-slate-950 tracking-tight mb-2">
        {t('auth.forgot.title', 'Mot de passe oublié ?')}
      </h2>
      <p className="text-slate-600 text-sm mb-8 font-medium">
        {t('auth.forgot.subtitle', 'Saisissez votre email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.')}
      </p>
      
      <form onSubmit={handleSubmit} className="space-y-5">
        <div>
          <label className={labelClass}>{t('auth.login.email', 'Email')}</label>
          <div className="relative">
            <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
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

        {error && (
          <div className="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-semibold">
            {error}
          </div>
        )}

        {message && (
          <div className="p-4 bg-[#8cedaa]/10 border border-[#8cedaa]/30 rounded-xl text-[#2aa354] text-sm font-semibold">
            {message}
          </div>
        )}

        <button 
          type="submit" 
          disabled={loading}
          className="w-full px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {loading ? t('common.loading', 'Chargement...') : t('auth.forgot.submit', 'Envoyer le lien')}
        </button>
      </form>
    </div>
  );
};

export default ForgotPassword;
