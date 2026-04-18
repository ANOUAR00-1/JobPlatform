import React, { useState } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { ShieldAlert, KeyRound } from 'lucide-react';
import { useAuth } from '../../hooks/useAuth';
import { useTranslation } from 'react-i18next';
import api from '../../services/api';

const VerifyEmail: React.FC = () => {
  const [searchParams] = useSearchParams();
  const email = searchParams.get('email') || '';
  const navigate = useNavigate();
  const { login } = useAuth();
  const { t } = useTranslation();
  
  const [code, setCode] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!code || code.length !== 6) {
      setError(t('auth.verify_email.error_invalid_code', 'Please enter a valid 6-digit code'));
      return;
    }

    setLoading(true);
    setError('');

    try {
      const response = await api.post('/verify-email', { email, code });
      const { user, profile, access_token } = response.data;
      
      const fullUser = { ...user, ...profile };
      login(fullUser, access_token);
      
      navigate('/candidat');
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      setError(error.response?.data?.message || t('auth.verify_email.error_failed', 'Verification failed. Please check your code.'));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex flex-col">
      <div className="flex items-center gap-3 mb-2">
        <ShieldAlert className="w-8 h-8 text-emerald-600 rtl:-scale-x-100" />
        <h2 className="text-2xl font-bold text-slate-950 tracking-tight">
          {t('auth.verify_email.title', 'VERIFY IDENTITY')}
        </h2>
      </div>
      <p className="text-slate-500 text-sm mb-8 font-medium">
        {t('auth.verify_email.subtitle_part1', "We've sent a 6-digit code to ")}
        <span className="text-slate-950 font-semibold">{email}</span>
        {t('auth.verify_email.subtitle_part2', ". Enter it below to unlock your workspace.")}
      </p>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm font-semibold text-slate-950 mb-2">
            {t('auth.verify_email.code_label', 'VERIFICATION CODE')}
          </label>
          <div className="relative">
            <div className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
              <KeyRound size={18} className="rtl:-scale-x-100" />
            </div>
            <input
              type="text"
              value={code}
              onChange={(e) => setCode(e.target.value.replace(/\D/g, '').slice(0, 6))}
              placeholder={t('auth.verify_email.placeholder', '000000')}
              className="w-full h-12 pl-12 pr-4 rtl:pl-4 rtl:pr-12 bg-slate-50 border border-slate-200 rounded-xl text-slate-950 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-center tracking-[1em] text-xl font-mono"
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
          className="w-full h-12 bg-[#8cedaa] text-slate-950 font-semibold rounded-xl hover:bg-[#7bc897] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {loading ? t('common.loading', 'Loading...') : t('auth.verify_email.btn_verify', 'VERIFY & LOGIN')}
        </button>
      </form>
    </div>
  );
};

export default VerifyEmail;
