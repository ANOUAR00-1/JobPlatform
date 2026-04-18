import React from 'react';
import { useAuth } from '../../hooks/useAuth';
import { Briefcase, Clock, CheckCircle, ArrowRight } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

const CandidatDashboard: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const stats = [
    { label: t('candidat_dashboard.stats.applications', 'APPLICATIONS SENT'), value: '12', icon: <Briefcase className="text-slate-600 w-5 h-5" /> },
    { label: t('candidat_dashboard.stats.pending', 'PENDING'), value: '4', icon: <Clock className="text-slate-600 w-5 h-5" /> },
    { label: t('candidat_dashboard.stats.interviews', 'INTERVIEWS'), value: '2', icon: <CheckCircle className="text-slate-600 w-5 h-5" /> },
  ];

  return (
    <div className="space-y-8 max-w-6xl mx-auto pb-12 bg-white">
      {/* Header */}
      <header className="pb-6">
        <h1 className="text-4xl font-bold text-slate-950 tracking-tight">
          {t('candidat_dashboard.welcome', { name: (user?.prenom || t('candidat_dashboard.candidate', 'Candidat')) })}
        </h1>
        <p className="text-slate-600 mt-2 text-base font-medium">
          {t('candidat_dashboard.subtitle', "Here's a summary of your recent activity on JobyNow.")}
        </p>
      </header>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {stats.map((stat, idx) => (
          <div key={idx} className="bg-slate-50 border border-slate-200 p-6 rounded-2xl hover:bg-slate-100 transition-colors">
            <div className="flex items-center justify-between mb-4">
              <div className="w-12 h-12 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm">
                {stat.icon}
              </div>
            </div>
            <div className="text-4xl font-bold text-slate-950 mb-1 tracking-tight">{stat.value}</div>
            <div className="text-sm font-medium text-slate-500">{stat.label}</div>
          </div>
        ))}
      </div>

      {/* Quick Action Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          onClick={() => navigate('/candidat/candidatures')} 
          className="group bg-slate-50 border border-slate-200 p-8 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all"
        >
          <div className="flex items-start justify-between mb-4">
            <div className="w-14 h-14 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm group-hover:-translate-y-1 transition-transform">
              <Briefcase className="w-6 h-6 text-slate-950" />
            </div>
            <ArrowRight className="w-5 h-5 text-slate-400 group-hover:text-[#2aa354] transition-colors rtl:-scale-x-100" />
          </div>
          <h3 className="text-xl font-bold text-slate-950 mb-2">
            {t('candidat_dashboard.actions.my_applications', 'My Applications')}
          </h3>
          <p className="text-sm text-slate-600 font-medium leading-relaxed">
            {t('candidat_dashboard.actions.my_applications_desc', 'Track the status of all your submitted applications in one place.')}
          </p>
        </div>
        
        <div 
          onClick={() => navigate('/offres')}
          className="group bg-slate-50 border border-slate-200 p-8 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all"
        >
          <div className="flex items-start justify-between mb-4">
            <div className="w-14 h-14 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm group-hover:-translate-y-1 transition-transform">
              <Briefcase className="w-6 h-6 text-slate-950" />
            </div>
            <ArrowRight className="w-5 h-5 text-slate-400 group-hover:text-[#2aa354] transition-colors rtl:-scale-x-100" />
          </div>
          <h3 className="text-xl font-bold text-slate-950 mb-2">
            {t('candidat_dashboard.actions.browse', 'Browse Open Roles')}
          </h3>
          <p className="text-sm text-slate-600 font-medium leading-relaxed">
            {t('candidat_dashboard.actions.browse_desc', 'Discover new opportunities matching your skills and experience level.')}
          </p>
        </div>
      </div>
    </div>
  );
};

export default CandidatDashboard;
