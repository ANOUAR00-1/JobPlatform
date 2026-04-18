import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Check, X, Eye, Search, Loader2, Star, Send } from 'lucide-react';
import { useToast } from '../../hooks/useToast';
import api from '../../services/api';
import EvaluateCandidateModal from '../../components/EvaluateCandidateModal';
import ConvoquerModal from '../../components/ConvoquerModal';

const ManageCandidatures: React.FC = () => {
  const { t } = useTranslation();
  const { addToast } = useToast();

  interface Candidature {
    id: number;
    statut: string;
    created_at: string;
    cv_path?: string;
    note_evaluation?: number;
    commentaire_recruteur?: string;
    candidat?: {
      nom: string;
      prenom: string;
      email: string;
    };
    offre?: {
      titre: string;
    };
  }

  const [candidatures, setCandidatures] = useState<Candidature[]>([]);
  const [loading, setLoading] = useState(true);
  const [actioningId, setActioningId] = useState<number | null>(null);
  const [isEvalModalOpen, setIsEvalModalOpen] = useState(false);
  const [evalCandidature, setEvalCandidature] = useState<Candidature | null>(null);
  const [isConvModalOpen, setIsConvModalOpen] = useState(false);
  const [convCandidature, setConvCandidature] = useState<Candidature | null>(null);

  useEffect(() => {
    const fetchCandidatures = async () => {
      try {
        setLoading(true);
        const res = await api.get('/entreprise/candidatures');
        setCandidatures(res.data.data.candidatures || res.data.data || []);
      } catch {
        addToast(t('errors.unexpected', 'An unexpected error occurred'), 'error');
      } finally {
        setLoading(false);
      }
    };

    fetchCandidatures();
  }, [addToast, t]);

  const handleAction = async (id: number, action: 'accepter' | 'refuser') => {
    try {
      setActioningId(id);
      await api.post(`/candidatures/${id}/${action}`);
      
      setCandidatures(prev => 
        prev.map(cand => {
          if (cand.id === id) {
            return {
              ...cand,
              statut: action === 'accepter' ? 'acceptee' : 'refusee'
            };
          }
          return cand;
        })
      );

      addToast(
        action === 'accepter' 
          ? t('enterprise_dashboard.notifications.accepted', 'Application accepted successfully')
          : t('enterprise_dashboard.notifications.refused', 'Application refused successfully'), 
        'success'
      );
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      addToast(
        `${t('enterprise_dashboard.notifications.action_error', 'Error during action: ')}${error.response?.data?.message || 'Unknown error'}`, 
        'error'
      );
    } finally {
      setActioningId(null);
    }
  };

  const submitEvaluation = async (note: number, commentaire: string) => {
    if (!evalCandidature) return;
    try {
      await api.put(`/candidatures/${evalCandidature.id}/evaluate`, {
        note_evaluation: note,
        commentaire_recruteur: commentaire
      });
      
      setCandidatures(prev => 
        prev.map(cand => {
          if (cand.id === evalCandidature.id) {
            return {
              ...cand,
              note_evaluation: note,
              commentaire_recruteur: commentaire
            };
          }
          return cand;
        })
      );

      addToast(t('enterprise_dashboard.evaluate_success', 'Évaluation enregistrée avec succès'), 'success');
      setIsEvalModalOpen(false);
    } catch (err) {
      addToast(t('errors.unexpected', 'Une erreur inattendue est survenue'), 'error');
      throw err;
    }
  };

  const submitConvocation = async (date: string, time: string, location: string) => {
    if (!convCandidature) return;
    try {
      await api.post(`/candidatures/${convCandidature.id}/convoquer`, {
        date,
        time,
        location
      });
      
      setCandidatures(prev => 
        prev.map(cand => {
          if (cand.id === convCandidature.id) {
            return {
              ...cand,
              statut: 'convoquée' // optimistic update
            };
          }
          return cand;
        })
      );

      addToast(t('enterprise_dashboard.convocation_success', 'Convocation envoyée avec succès!'), 'success');
      setIsConvModalOpen(false);
    } catch (err) {
      addToast(t('errors.unexpected', 'Une erreur inattendue est survenue'), 'error');
      throw err;
    }
  };

  const getStatusStyle = (statut: string) => {
    const s = statut?.toLowerCase();
    if (['accepted', 'acceptée', 'acceptee', 'accepté'].includes(s)) {
      return 'bg-[#8cedaa]/20 text-[#2aa354] border-[#8cedaa]/30';
    }
    if (['refused', 'refusée', 'refusee', 'refusé'].includes(s)) {
      return 'bg-red-100 text-red-700 border-red-200';
    }
    if (['convoquée', 'convoquee', 'convoqué'].includes(s)) {
      return 'bg-purple-100 text-purple-700 border-purple-200';
    }
    return 'bg-slate-100 text-slate-700 border-slate-200';
  };

  return (
    <div className="flex-1 space-y-8 min-h-screen font-sans bg-white">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between space-y-4 sm:space-y-0 pb-4">
        <div>
          <h2 className="text-3xl md:text-4xl font-bold tracking-tight text-slate-950">
            {t('dashboard_layout.enterprise.candidatures', 'Candidatures')}
          </h2>
          <p className="text-base text-slate-600 mt-2 font-medium">
            {t('enterprise_dashboard.manage_candidatures_desc', 'Examinez et traitez les candidatures reçues.')}
          </p>
        </div>

        {/* Search */}
        <div className="relative w-full sm:w-80">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
          <input 
            type="text" 
            placeholder={t('enterprise_dashboard.search_candidate', 'Rechercher un candidat...')} 
            className="w-full text-sm rounded-xl border border-slate-200 bg-white px-11 py-3 outline-none focus:ring-2 focus:ring-[#8cedaa]/20 focus:border-[#8cedaa] transition-all placeholder:text-slate-400 text-slate-950 shadow-sm font-medium"
          />
        </div>
      </div>

      {loading ? (
        <div className="flex items-center justify-center py-24">
          <Loader2 className="w-8 h-8 text-[#2aa354] animate-spin" />
        </div>
      ) : candidatures.length === 0 ? (
        <div className="flex flex-col items-center justify-center p-16 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
          <div className="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center mb-6 shadow-sm">
            <Search className="w-8 h-8 text-slate-400" />
          </div>
          <p className="text-base font-semibold text-slate-600">
            {t('enterprise_dashboard.no_applications_yet', 'Aucune candidature reçue pour le moment.')}
          </p>
        </div>
      ) : (
        <div className="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm whitespace-nowrap">
              <thead>
                <tr className="border-b border-slate-200 bg-white">
                  <th className="px-6 py-4 font-bold text-slate-950">{t('enterprise_dashboard.candidate_th', 'Candidat')}</th>
                  <th className="px-6 py-4 font-bold text-slate-950">{t('enterprise_dashboard.position_th', 'Poste')}</th>
                  <th className="px-6 py-4 font-bold text-slate-950">{t('enterprise_dashboard.date_th', 'Date')}</th>
                  <th className="px-6 py-4 font-bold text-slate-950">{t('enterprise_dashboard.status_th', 'Statut')}</th>
                  <th className="px-6 py-4 font-bold text-slate-950 text-right">{t('enterprise_dashboard.actions_th', 'Actions')}</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-200">
                {candidatures.map((cand) => (
                  <tr key={cand.id} className="hover:bg-white transition-colors bg-slate-50">
                    <td className="px-6 py-5">
                      <div className="flex flex-col">
                        <span className="font-bold text-slate-950 flex items-center gap-2">
                          {cand.candidat?.nom} {cand.candidat?.prenom}
                          {cand.note_evaluation > 0 && (
                            <span className="flex items-center gap-1 text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full text-xs font-bold border border-yellow-200">
                              <Star className="w-3 h-3 fill-yellow-600" />
                              {cand.note_evaluation}
                            </span>
                          )}
                        </span>
                        <span className="text-sm text-slate-600 mt-1 font-medium">{cand.candidat?.email}</span>
                      </div>
                    </td>
                    <td className="px-6 py-5 text-slate-950 font-semibold">{cand.offre?.titre || 'Offre Supprimée'}</td>
                    <td className="px-6 py-5 text-slate-600 font-medium">{new Date(cand.created_at).toLocaleDateString()}</td>
                    <td className="px-6 py-5">
                      <span className={`inline-flex items-center border px-3 py-1 text-xs font-bold rounded-full transition-colors ${getStatusStyle(cand.statut)}`}>
                        {cand.statut}
                      </span>
                    </td>
                    <td className="px-6 py-5 text-right">
                      <div className="flex items-center justify-end gap-2">
                        {cand.cv_path && (
                          <button 
                            onClick={() => window.open(`http://localhost:8000/storage/${cand.cv_path}`, '_blank')}
                            title={t('enterprise_dashboard.tooltips.view_cv', 'Voir CV')} 
                            className="p-2 text-slate-600 hover:text-slate-950 hover:bg-slate-100 rounded-lg transition-all"
                          >
                            <Eye className="w-4 h-4" />
                          </button>
                        )}
                        {['pending', 'en_attente', 'attente'].includes(cand.statut?.toLowerCase()) && (
                          <>
                            <button 
                              title={t('enterprise_dashboard.tooltips.accept', 'Accept')} 
                              onClick={() => handleAction(cand.id, 'accepter')}
                              disabled={actioningId === cand.id}
                              className="p-2 text-slate-600 hover:text-[#2aa354] hover:bg-[#8cedaa]/10 rounded-lg transition-all disabled:opacity-50"
                            >
                              {actioningId === cand.id ? <Loader2 className="w-4 h-4 animate-spin" /> : <Check className="w-4 h-4" />}
                            </button>
                            <button 
                              title={t('enterprise_dashboard.tooltips.reject', 'Reject')} 
                              onClick={() => handleAction(cand.id, 'refuser')}
                              disabled={actioningId === cand.id}
                              className="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all disabled:opacity-50"
                            >
                              {actioningId === cand.id ? <Loader2 className="w-4 h-4 animate-spin" /> : <X className="w-4 h-4" />}
                            </button>
                          </>
                        )}
                        <button 
                          title={t('enterprise_dashboard.tooltips.evaluate', 'Évaluer')} 
                          onClick={() => {
                            setEvalCandidature(cand);
                            setIsEvalModalOpen(true);
                          }}
                          className="p-2 text-slate-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                        >
                          <Star className="w-4 h-4" />
                        </button>
                        <button 
                          title={t('enterprise_dashboard.tooltips.convoquer', 'Convoquer pour un entretien')} 
                          onClick={() => {
                            setConvCandidature(cand);
                            setIsConvModalOpen(true);
                          }}
                          className="p-2 text-slate-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all"
                        >
                          <Send className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {evalCandidature && (
        <EvaluateCandidateModal
          isOpen={isEvalModalOpen}
          onClose={() => setIsEvalModalOpen(false)}
          candidateName={`${evalCandidature.candidat?.nom || ''} ${evalCandidature.candidat?.prenom || ''}`}
          initialNote={evalCandidature.note_evaluation || 0}
          initialComment={evalCandidature.commentaire_recruteur || ''}
          onSubmit={submitEvaluation}
        />
      )}

      {convCandidature && (
        <ConvoquerModal
          isOpen={isConvModalOpen}
          onClose={() => setIsConvModalOpen(false)}
          candidateName={`${convCandidature.candidat?.nom || ''} ${convCandidature.candidat?.prenom || ''}`}
          position={convCandidature.offre?.titre || ''}
          onSubmit={submitConvocation}
        />
      )}
    </div>
  );
};

export default ManageCandidatures;
