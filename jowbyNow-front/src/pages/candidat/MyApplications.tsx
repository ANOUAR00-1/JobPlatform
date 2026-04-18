import React, { useEffect, useState } from 'react';
import { Briefcase, MapPin, Loader2, ArrowUpRight } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useToast } from '../../hooks/useToast';
import { useTranslation } from 'react-i18next';
import api from '../../services/api';

const MyApplications: React.FC = () => {
  const { addToast } = useToast();
  const { t } = useTranslation();
  
  const [candidatures, setCandidatures] = useState<Array<{
    id: number;
    statut: string;
    created_at: string;
    offre?: {
      id: number;
      titre: string;
      company_name: string;
      type_contrat: string;
      ville?: { nom: string };
    };
  }>>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchMyApplications = async () => {
      try {
        setLoading(true);
        const res = await api.get('/candidat/candidatures');
        setCandidatures(res.data.data || res.data);
      } catch {
        addToast(t('candidat_dashboard.my_applications.error_load', 'Erreur lors du chargement de vos candidatures'), 'error');
      } finally {
        setLoading(false);
      }
    };

    fetchMyApplications();
  }, [addToast, t]);

  const getStatusStyle = (statut: string) => {
    const s = statut?.toLowerCase();
    if (['accepted', 'acceptée', 'acceptee', 'accepté'].includes(s)) {
      return 'bg-[#8cedaa]/20 text-[#2aa354] border-[#8cedaa]/30';
    }
    if (['refused', 'refusée', 'refusee', 'refusé'].includes(s)) {
      return 'bg-red-100 text-red-700 border-red-200';
    }
    return 'bg-amber-100 text-amber-700 border-amber-200';
  };

  return (
    <div className="space-y-8 max-w-6xl mx-auto pb-12 relative min-h-[400px] bg-white">
      {/* Header */}
      <div className="pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 className="text-4xl font-bold text-slate-950 tracking-tight">
            {t('candidat_dashboard.my_applications.title', 'My Applications')}
          </h1>
          <p className="text-slate-600 mt-2 text-base font-medium">
            {t('candidat_dashboard.my_applications.subtitle', 'Track the status of your submitted candidatures.')}
          </p>
        </div>
      </div>

      {loading ? (
        <div className="flex items-center justify-center py-24">
          <Loader2 className="w-8 h-8 text-[#2aa354] animate-spin" />
        </div>
      ) : candidatures.length === 0 ? (
        <div className="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-center p-16 flex flex-col items-center">
          <div className="w-16 h-16 bg-white rounded-2xl border border-slate-200 flex items-center justify-center mb-6 shadow-sm">
            <Briefcase className="w-8 h-8 text-slate-400" />
          </div>
          <h2 className="text-xl font-bold text-slate-950 mb-2">
            {t('candidat_dashboard.my_applications.no_applications', 'No applications yet')}
          </h2>
          <p className="text-slate-600 mb-8 max-w-sm text-sm font-medium">
            {t('candidat_dashboard.my_applications.no_applications_desc', 'Discover open roles and kickstart your career journey.')}
          </p>
          <Link to="/offres">
            <button className="px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm">
              {t('candidat_dashboard.my_applications.browse_btn', 'Browse Open Roles')}
            </button>
          </Link>
        </div>
      ) : (
        <div className="flex flex-col gap-4">
          {candidatures.map((cand) => (
            <div 
              key={cand.id} 
              className="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:bg-slate-100 transition-all"
            >
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div className="flex-1">
                  <div className="flex items-center gap-3 mb-3">
                    <span className={`px-3 py-1 rounded-full text-xs font-bold border ${getStatusStyle(cand.statut)}`}>
                      {cand.statut}
                    </span>
                    <span className="text-xs text-slate-600 font-medium">
                      {new Date(cand.created_at).toLocaleDateString()}
                    </span>
                  </div>
                  <h3 className="text-xl font-bold text-slate-950 mb-2">
                    {cand.offre?.titre || t('candidat_dashboard.my_applications.deleted_offer', 'Deleted Offer')}
                  </h3>
                  <div className="flex flex-wrap items-center gap-4 text-sm text-slate-600 font-medium">
                    <span className="font-semibold text-slate-950">{cand.offre?.company_name || t('candidat_dashboard.my_applications.confidential', 'Confidential')}</span>
                    <span className="flex items-center gap-2">
                      <MapPin className="w-4 h-4" /> {cand.offre?.ville?.nom || t('candidat_dashboard.my_applications.not_specified', 'N/A')}
                    </span>
                    <span className="flex items-center gap-2">
                      <Briefcase className="w-4 h-4" /> {cand.offre?.type_contrat || t('candidat_dashboard.my_applications.not_specified', 'N/A')}
                    </span>
                  </div>
                </div>
                
                {cand.offre && (
                  <Link 
                    to={`/offres/${cand.offre.id}`} 
                    className="px-5 py-2.5 bg-white hover:bg-slate-950 text-slate-950 hover:text-white font-semibold rounded-xl transition-all border border-slate-200 hover:border-slate-950 shadow-sm flex items-center gap-2"
                  >
                    {t('candidat_dashboard.my_applications.view', 'View')}
                    <ArrowUpRight className="w-4 h-4 rtl:-scale-x-100" />
                  </Link>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default MyApplications;
