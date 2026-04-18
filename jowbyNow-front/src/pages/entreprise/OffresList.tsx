import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { FileText, Loader2, Plus, MapPin, Calendar, Clock } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useToast } from '../../hooks/useToast';
import api from '../../services/api';

const OffresList: React.FC = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { addToast } = useToast();

  const [offres, setOffres] = useState<Array<{
    id: number;
    titre: string;
    statut: string;
    type_contrat: string;
    created_at: string;
    ville?: { nom: string };
  }>>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchOffres = async () => {
      try {
        setLoading(true);
        const res = await api.get('/entreprise/offres');
        setOffres(res.data.data.offres || []);
      } catch {
        addToast(t('errors.unexpected', 'An unexpected error occurred'), 'error');
      } finally {
        setLoading(false);
      }
    };

    fetchOffres();
  }, [addToast, t]);

  return (
    <div className="flex-1 space-y-8 min-h-screen font-sans bg-white">
      {/* Header section */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between space-y-4 sm:space-y-0 pb-4">
        <div>
          <h2 className="text-3xl md:text-4xl font-bold tracking-tight text-slate-950">
            {t('dashboard_layout.enterprise.offers', 'Offres')}
          </h2>
          <p className="text-base text-slate-600 mt-2 font-medium">
            {t('enterprise_dashboard.manage_offers_desc', 'Gérez vos annonces et le suivi de vos offres publiées.')}
          </p>
        </div>

        <button 
          onClick={() => navigate('/entreprise/offres/new')} 
          className="px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm flex items-center gap-2"
        >
          <Plus className="h-5 w-5" />
          {t('enterprise_dashboard.create_new_offer', 'Créer une offre')}
        </button>
      </div>

      {loading ? (
        <div className="flex items-center justify-center py-24">
          <Loader2 className="w-8 h-8 text-[#2aa354] animate-spin" />
        </div>
      ) : offres.length === 0 ? (
        <div className="flex flex-col items-center justify-center p-16 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
          <div className="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center mb-6 shadow-sm">
            <FileText className="w-8 h-8 text-slate-400" />
          </div>
          <h3 className="text-xl font-bold text-slate-950 mb-2">Aucune offre</h3>
          <p className="text-sm text-slate-600 max-w-sm mb-8 font-medium leading-relaxed">
            {t('enterprise_dashboard.no_offers_yet', 'You haven\'t listed any job offers yet.')}
          </p>
          <button 
            onClick={() => navigate('/entreprise/offres/new')} 
            className="px-6 py-3 bg-slate-950 hover:bg-slate-800 text-white font-semibold rounded-xl transition-all shadow-sm"
          >
            {t('enterprise_dashboard.no_offers_create_btn', 'Créer une offre')}
          </button>
        </div>
      ) : (
        <div className="flex flex-col gap-4">
          {offres.map((offre) => (
            <div 
              key={offre.id} 
              className="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:bg-slate-100 transition-all group"
            >
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div className="space-y-3 flex-1">
                  <div className="flex items-center gap-3 flex-wrap">
                    <h3 className="text-xl font-bold text-slate-950">{offre.titre}</h3>
                    <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                      offre.statut === 'ouverte' 
                        ? 'bg-[#8cedaa]/20 text-[#2aa354] border border-[#8cedaa]/30' 
                        : 'bg-slate-200 text-slate-600 border border-slate-300'
                    }`}>
                      {offre.statut}
                    </span>
                  </div>
                  
                  <div className="flex flex-wrap items-center gap-4 text-sm text-slate-600 font-medium">
                    {offre.ville && (
                      <div className="flex items-center gap-2">
                        <MapPin className="w-4 h-4" />
                        <span>{offre.ville.nom}</span>
                      </div>
                    )}
                    <div className="flex items-center gap-2">
                      <Clock className="w-4 h-4" />
                      <span>{offre.type_contrat}</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <Calendar className="w-4 h-4" />
                      <span>{new Date(offre.created_at).toLocaleDateString()}</span>
                    </div>
                  </div>
                </div>

                <button 
                  onClick={() => navigate(`/offres/${offre.id}`)}
                  className="px-5 py-2.5 bg-white hover:bg-slate-950 text-slate-950 hover:text-white font-semibold rounded-xl transition-all border border-slate-200 hover:border-slate-950 shadow-sm"
                >
                  {t('enterprise_dashboard.view_btn', 'Voir la fiche')}
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default OffresList;
