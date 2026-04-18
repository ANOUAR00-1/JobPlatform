import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Building, MapPin, Clock, ArrowLeft, CheckCircle, Loader2, Upload, ArrowRight, Calendar, Briefcase, DollarSign } from 'lucide-react';
import { useAuth } from '../../hooks/useAuth';
import { useToast } from '../../hooks/useToast';
import { useTranslation } from 'react-i18next';
import api from '../../services/api';

const OffreDetails: React.FC = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user } = useAuth();
  const { addToast } = useToast();
  const { t } = useTranslation();

  interface Offre {
    id: number;
    titre: string;
    description: string;
    type_contrat: string;
    statut: string;
    salaire?: string;
    company_name?: string;
    date_expiration?: string;
    created_at?: string;
    competences_requises?: string | string[];
    ville?: { nom: string };
  }

  const [offre, setOffre] = useState<Offre | null>(null);
  const [loading, setLoading] = useState(true);

  // Application Modal state
  const [showApplyModal, setShowApplyModal] = useState(false);
  const [lettreMotivation, setLettreMotivation] = useState('');
  const [cvFile, setCvFile] = useState<File | null>(null);
  const [isApplying, setIsApplying] = useState(false);

  useEffect(() => {
    const fetchJob = async () => {
      try {
        setLoading(true);
        const res = await api.get(`/jobs/${id}`);
        setOffre(res.data.data || res.data);
      } catch {
        addToast(t('errors.unexpected', 'Offre introuvable'), 'error');
        navigate('/offres');
      } finally {
        setLoading(false);
      }
    };

    fetchJob();
  }, [id, addToast, navigate, t]);

  const handleApply = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!cvFile) {
      addToast(t('public_jobs.apply_modal.error_no_cv', 'Veuillez joindre votre CV'), 'error');
      return;
    }

    try {
      setIsApplying(true);
      
      const formData = new FormData();
      formData.append('offre_id', id as string);
      formData.append('lettre_motivation', lettreMotivation);
      formData.append('cv', cvFile);

      await api.post('/candidatures', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      });

      addToast(t('public_jobs.apply_modal.success', 'Candidature envoyée avec succès!'), 'success');
      setShowApplyModal(false);
      
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      addToast(error.response?.data?.message || t('public_jobs.apply_modal.error_submit', 'Erreur lors de la postulation'), 'error');
    } finally {
      setIsApplying(false);
    }
  };

  if (loading) {
    return <div className="flex h-[50vh] items-center justify-center bg-transparent"><Loader2 className="w-10 h-10 animate-spin text-emerald-600" /></div>;
  }

  if (!offre) return null;

  return (
    <div className="w-full min-h-screen bg-slate-50 font-sans">
      {/* Back Nav */}
      <div className="border-b border-slate-200 bg-white px-6">
        <div className="max-w-5xl mx-auto py-4">
          <button 
            onClick={() => navigate(-1)} 
            className="flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-950 transition-colors"
          >
            <ArrowLeft className="w-4 h-4 rtl:-scale-x-100" /> {t('public_jobs.details.back', 'Retour aux annonces')}
          </button>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-6 py-12">
        <div className="flex flex-col lg:flex-row gap-10">
          
          {/* Main Content Area */}
          <div className="flex-1 space-y-8">
            
            {/* Header / Title Area */}
            <div className="space-y-4">
              <div className="flex items-center gap-3">
                <span className="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-800">
                  {offre.type_contrat}
                </span>
                {offre.salaire && (
                  <span className="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                    {offre.salaire}
                  </span>
                )}
                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${
                  offre.statut === 'ouverte' 
                    ? 'bg-blue-100 text-blue-800' 
                    : 'bg-red-100 text-red-800'
                }`}>
                  {offre.statut}
                </span>
              </div>
              
              <h1 className="text-3xl md:text-5xl font-bold tracking-tight text-slate-950">
                {offre.titre}
              </h1>
              
              <div className="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-500 pt-2 border-b border-slate-200 pb-6">
                <span className="flex items-center gap-2 font-medium text-slate-700">
                  <Building className="w-4 h-4" /> {offre.company_name || t('public_jobs.details.confidential', 'Entreprise Confidentielle')}
                </span>
                <span className="flex items-center gap-2">
                  <MapPin className="w-4 h-4" /> {offre.ville?.nom || t('public_jobs.details.not_specified', 'Non specifié')}
                </span>
                <span className="flex items-center gap-2">
                  <Clock className="w-4 h-4" /> {t('public_jobs.details.expires', 'Expire le :')} {offre.date_expiration ? new Date(offre.date_expiration).toLocaleDateString() : t('public_jobs.details.not_specified', 'N/A')}
                </span>
              </div>
            </div>

            {/* Description Section */}
            <section className="space-y-4">
              <h2 className="text-xl font-semibold text-slate-950">
                {t('public_jobs.details.about_role', 'À propos du poste')}
              </h2>
              <div className="text-slate-600 leading-relaxed whitespace-pre-wrap">
                {offre.description}
              </div>
            </section>

            {/* Skills Section */}
            {offre.competences_requises && (
              <section className="space-y-4 pt-6">
                <h2 className="text-xl font-semibold text-slate-950">
                  {t('public_jobs.details.skills', 'Compétences requises')}
                </h2>
                <ul className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  {Array.isArray(offre.competences_requises) 
                    ? offre.competences_requises.map((item: string, i: number) => (
                        <li key={i} className="flex items-start gap-3 text-slate-600">
                          <CheckCircle className="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                          <span>{item}</span>
                        </li>
                      ))
                    : (
                      <li className="flex items-start gap-3 text-slate-600">
                        <CheckCircle className="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                        <span>{offre.competences_requises}</span>
                      </li>
                    )
                  }
                </ul>
              </section>
            )}
          </div>

          {/* Action Sidebar / Right Card */}
          <div className="w-full lg:w-80 shrink-0">
            <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6 sticky top-24 space-y-6">
              
              <button 
                onClick={() => {
                  if(!user) {
                    addToast(t('public_jobs.apply_modal.error_login', 'Veuillez vous connecter pour postuler'), 'info');
                    navigate('/login');
                  } else if (user.type === 'entreprise' || user.raison_social) {
                    addToast(t('public_jobs.apply_modal.error_enterprise', 'Les entreprises ne peuvent pas postuler'), 'error');
                  } else {
                    setShowApplyModal(true);
                  }
                }}
                className="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-[#8cedaa] px-4 py-3 text-sm font-semibold text-slate-950 shadow-sm hover:bg-[#7bc897] transition-colors"
              >
                {t('public_jobs.details.apply', 'Postuler maintenant')} <ArrowRight className="w-4 h-4 rtl:-scale-x-100" />
              </button>

              <div className="pt-2">
                <p className="text-xs text-center text-slate-500 mb-6">
                  {t('public_jobs.details.reviewed', 'Candidatures examinées sous 48h')}
                </p>

                <h3 className="text-sm font-semibold text-slate-950 mb-4 pb-2 border-b border-slate-100">
                  {t('public_jobs.details.job_details', 'Détails de l\'offre')}
                </h3>
                
                <div className="space-y-4">
                  <div className="flex gap-3">
                    <Briefcase className="w-5 h-5 text-slate-400 shrink-0 mt-0.5" />
                    <div className="flex flex-col gap-0.5 text-sm">
                      <span className="text-slate-500">{t('public_jobs.details.contract', 'Contrat')}</span>
                      <span className="font-medium text-slate-950">{offre.type_contrat}</span>
                    </div>
                  </div>

                  <div className="flex gap-3">
                    <MapPin className="w-5 h-5 text-slate-400 shrink-0 mt-0.5" />
                    <div className="flex flex-col gap-0.5 text-sm">
                      <span className="text-slate-500">{t('public_jobs.details.location', 'Localisation')}</span>
                      <span className="font-medium text-slate-950">{offre.ville?.nom || t('public_jobs.details.not_specified', 'N/A')}</span>
                    </div>
                  </div>

                  <div className="flex gap-3">
                    <DollarSign className="w-5 h-5 text-slate-400 shrink-0 mt-0.5" />
                    <div className="flex flex-col gap-0.5 text-sm">
                      <span className="text-slate-500">{t('public_jobs.details.salary', 'Salaire')}</span>
                      <span className="font-medium text-slate-950">{offre.salaire || t('public_jobs.details.negotiable', 'Négociable')}</span>
                    </div>
                  </div>
                  
                  <div className="flex gap-3">
                    <Calendar className="w-5 h-5 text-slate-400 shrink-0 mt-0.5" />
                    <div className="flex flex-col gap-0.5 text-sm">
                      <span className="text-slate-500">{t('public_jobs.details.posted', 'Publié le')}</span>
                      <span className="font-medium text-slate-950">
                        {offre.created_at ? new Date(offre.created_at).toLocaleDateString() : t('public_jobs.details.not_specified', 'N/A')}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Application Modal — Clean SaaS Form */}
      {showApplyModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
          {/* Backdrop */}
          <div className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onClick={() => setShowApplyModal(false)} />
          
          {/* Modal Body */}
          <div className="bg-white border border-slate-200 rounded-2xl shadow-xl w-full max-w-lg p-6 sm:p-8 relative z-10 animate-in fade-in zoom-in-95 duration-200">
            <div className="mb-6">
              <h2 className="text-2xl font-bold tracking-tight text-slate-950">
                {t('public_jobs.apply_modal.title', 'Postuler à cette offre')}
              </h2>
              <p className="text-sm text-slate-500 mt-1">
                {'Remplissez le formulaire ci-dessous pour envoyer votre candidature.'}
              </p>
            </div>

            <form onSubmit={handleApply} className="space-y-5">
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  {t('public_jobs.apply_modal.cover_letter', 'Lettre de motivation (Optionnel)')}
                </label>
                <textarea 
                  value={lettreMotivation}
                  onChange={(e) => setLettreMotivation(e.target.value)}
                  rows={4}
                  placeholder={t('public_jobs.apply_modal.cover_letter_ph', 'Exprimez vos motivations...')}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  {t('public_jobs.apply_modal.cv', 'Curriculum Vitae (PDF) *')}
                </label>
                <div className="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 hover:border-emerald-500 transition-colors group cursor-pointer">
                  <input 
                    type="file" 
                    accept=".pdf,.doc,.docx"
                    onChange={(e) => setCvFile(e.target.files?.[0] || null)}
                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    required
                  />
                  <div className="pointer-events-none">
                    <Upload className="w-8 h-8 text-slate-400 mx-auto mb-3 group-hover:text-emerald-600 transition-colors" />
                    <p className="text-sm font-medium text-slate-950">
                      {cvFile ? cvFile.name : t('public_jobs.apply_modal.drop_cv', 'Cliquez ou glissez votre CV ici')}
                    </p>
                    <p className="text-xs text-slate-500 mt-1">PDF, DOC, DOCX (Max. 5MB)</p>
                  </div>
                </div>
              </div>

              <div className="flex items-center gap-3 pt-6 border-t border-slate-200">
                <button 
                  type="button" 
                  onClick={() => setShowApplyModal(false)}
                  className="flex-1 rounded-xl px-4 py-2 bg-white border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors"
                >
                  {t('public_jobs.apply_modal.cancel', 'Annuler')}
                </button>
                <button 
                  type="submit" 
                  disabled={isApplying}
                  className="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-[#8cedaa] px-4 py-2 text-sm font-semibold text-slate-950 shadow-sm hover:bg-[#7bc897] transition-colors disabled:opacity-50"
                >
                  {isApplying && <Loader2 className="w-4 h-4 animate-spin" />}
                  {t('public_jobs.apply_modal.submit', 'Envoyer')}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default OffreDetails;
