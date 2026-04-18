import React from 'react';
import { useAuth } from '../../hooks/useAuth';
import { useTranslation } from 'react-i18next';
import { Users, FilePlus, Eye, Plus, ArrowRight } from 'lucide-react';
import { useNavigate } from 'react-router-dom';

const EntrepriseDashboard: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const stats = [
    { label: t('enterprise_dashboard.stats.active_offers', 'Offres actives'), value: '3', icon: <FilePlus className="w-5 h-5 text-slate-600" /> },
    { label: t('enterprise_dashboard.stats.new_applications', 'Nouvelles candidatures'), value: '24', icon: <Users className="w-5 h-5 text-slate-600" /> },
    { label: t('enterprise_dashboard.stats.profile_views', 'Vues du profil'), value: '1,204', icon: <Eye className="w-5 h-5 text-slate-600" /> },
  ];

  return (
    <div className="flex-1 space-y-8 min-h-screen font-sans bg-white">
      
      {/* Header section */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between space-y-4 sm:space-y-0">
        <div>
          <h2 className="text-3xl md:text-4xl font-bold tracking-tight text-slate-950">{t('enterprise_dashboard.title')}</h2>
          <p className="text-base text-slate-600 mt-2 font-medium">
            {t('enterprise_dashboard.welcome')}, {user?.raison_social || t('enterprise_dashboard.admin')}
          </p>
        </div>
        <button 
          onClick={() => navigate('/entreprise/offres/new')} 
          className="px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2"
        >
          <Plus className="h-5 w-5" />
          {t('enterprise_dashboard.create_offer')}
        </button>
      </div>

      {/* Stats Grid */}
      <div className="grid gap-6 md:grid-cols-3">
        {stats.map((stat, idx) => (
          <div key={idx} className="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:bg-slate-100 transition-colors">
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

      {/* Quick Actions Grid */}
      <div className="grid gap-6 md:grid-cols-2">
        <div 
          onClick={() => navigate('/entreprise/candidatures')} 
          className="group bg-slate-50 border border-slate-200 rounded-2xl p-8 cursor-pointer hover:bg-slate-100 transition-all"
        >
          <div className="flex items-start justify-between mb-4">
            <div className="w-14 h-14 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm group-hover:-translate-y-1 transition-transform">
              <Users className="w-6 h-6 text-slate-950" />
            </div>
            <ArrowRight className="w-5 h-5 text-slate-400 group-hover:text-[#2aa354] transition-colors rtl:-scale-x-100" />
          </div>
          <h3 className="text-xl font-bold text-slate-950 mb-2">
            {t('enterprise_dashboard.actions.manage_applications')}
          </h3>
          <p className="text-sm text-slate-600 font-medium leading-relaxed">
            {t('enterprise_dashboard.actions.manage_applications_desc')}
          </p>
        </div>

        <div 
          onClick={() => navigate('/entreprise/offres/new')} 
          className="group bg-slate-50 border border-slate-200 rounded-2xl p-8 cursor-pointer hover:bg-slate-100 transition-all"
        >
          <div className="flex items-start justify-between mb-4">
            <div className="w-14 h-14 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm group-hover:-translate-y-1 transition-transform">
              <FilePlus className="w-6 h-6 text-slate-950" />
            </div>
            <ArrowRight className="w-5 h-5 text-slate-400 group-hover:text-[#2aa354] transition-colors rtl:-scale-x-100" />
          </div>
          <h3 className="text-xl font-bold text-slate-950 mb-2">
            {t('enterprise_dashboard.actions.post_role')}
          </h3>
          <p className="text-sm text-slate-600 font-medium leading-relaxed">
            {t('enterprise_dashboard.actions.post_role_desc')}
          </p>
        </div>
      </div>
    </div>
  );
};

export default EntrepriseDashboard;
