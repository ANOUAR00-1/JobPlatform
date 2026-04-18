import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Save, ArrowLeft, Loader2 } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useToast } from '../../hooks/useToast';
import api from '../../services/api';

const CreateOffre: React.FC = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { addToast } = useToast();

  const [loading, setLoading] = useState(false);
  
  const [formData, setFormData] = useState({
    titre: '',
    type_contrat: 'CDI',
    ville_id: '1',
    salaire: '',
    description: '',
    competences_requises: '', 
    date_expiration: ''
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!formData.titre || !formData.description) {
      addToast(t('enterprise_dashboard.create.validation_error', 'Title and description are required'), 'error');
      return;
    }

    try {
      setLoading(true);
      const competencesArray = formData.competences_requises
        .split(',')
        .map(s => s.trim())
        .filter(s => s !== '');

      await api.post('/offres', {
        ...formData,
        competences_requises: competencesArray
      });

      addToast(t('enterprise_dashboard.create.success', 'Offer published successfully!'), 'success');
      navigate('/entreprise/offres');
      
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      addToast(error.response?.data?.message || t('errors.unexpected', 'An unexpected error occurred'), 'error');
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#8cedaa]/20 focus:border-[#8cedaa] transition-all placeholder:text-slate-400 text-slate-950 shadow-sm font-medium";
  const labelClass = "block text-sm font-bold text-slate-950 mb-2";

  return (
    <div className="flex-1 max-w-4xl min-h-screen font-sans bg-white space-y-8">
      {/* Header section */}
      <div className="flex items-center gap-4 pb-2">
        <button 
          onClick={() => navigate('/entreprise/offres')}
          className="p-2 -ml-2 rounded-xl hover:bg-slate-100 text-slate-600 hover:text-slate-950 transition-all"
        >
          <ArrowLeft className="w-5 h-5" />
        </button>
        <div>
          <h2 className="text-3xl md:text-4xl font-bold tracking-tight text-slate-950">
            {t('enterprise_dashboard.create.title', 'Créer une offre')}
          </h2>
          <p className="text-base text-slate-600 mt-2 font-medium">
            {t('enterprise_dashboard.create.subtitle', 'Remplissez les détails du poste pour publier votre annonce.')}
          </p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="space-y-8">
        
        <div className="bg-slate-50 border border-slate-200 rounded-2xl p-8">
          <div className="pb-6 mb-8 border-b border-slate-200">
            <h3 className="text-lg font-bold text-slate-950">
              {t('enterprise_dashboard.create.section_general', 'Informations générales')}
            </h3>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="md:col-span-2">
              <label className={labelClass}>
                {t('enterprise_dashboard.create.job_title', 'Titre du poste')}
              </label>
              <input 
                name="titre" 
                value={formData.titre} 
                onChange={handleChange} 
                placeholder={t('enterprise_dashboard.create.job_title_ph', 'ex: Développeur React Senior')} 
                required 
                className={inputClass}
              />
            </div>
            
            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.contract_type', 'Type de contrat')}
              </label>
              <select name="type_contrat" value={formData.type_contrat} onChange={handleChange} className={inputClass}>
                <option value="CDI">CDI</option>
                <option value="CDD">CDD</option>
                <option value="Stage">{t('enterprise_dashboard.create.options.internship', 'Stage')}</option>
                <option value="Freelance">Freelance</option>
              </select>
            </div>

            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.location', 'Localisation')}
              </label>
              <select name="ville_id" value={formData.ville_id} onChange={handleChange} className={inputClass}>
                <option value="1">Casablanca</option>
                <option value="2">Rabat</option>
                <option value="3">{t('enterprise_dashboard.create.options.remote', 'Télétravail (Remote)')}</option>
              </select>
            </div>
            
            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.salary', 'Salaire (Optionnel)')}
              </label>
              <input 
                name="salaire" 
                value={formData.salaire} 
                onChange={handleChange} 
                placeholder={t('enterprise_dashboard.create.salary_ph', 'ex: 12000 MAD')} 
                className={inputClass}
              />
            </div>

            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.expiration_date', 'Date d\'expiration (Optionnel)')}
              </label>
              <input 
                type="date" 
                name="date_expiration" 
                value={formData.date_expiration} 
                onChange={handleChange} 
                className={inputClass}
              />
            </div>
          </div>
        </div>

        <div className="bg-slate-50 border border-slate-200 rounded-2xl p-8">
          <div className="pb-6 mb-8 border-b border-slate-200">
            <h3 className="text-lg font-bold text-slate-950">
              {t('enterprise_dashboard.create.section_description', 'Description du poste')}
            </h3>
          </div>
          <div className="space-y-6">
            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.mission', 'Description des missions')}
              </label>
              <textarea 
                name="description"
                value={formData.description}
                onChange={handleChange}
                rows={5}
                required
                placeholder={t('enterprise_dashboard.create.mission_ph', 'Détaillez les missions et l\'environnement technique...')}
                className={`${inputClass} resize-y min-h-[140px]`}
              />
            </div>

            <div>
              <label className={labelClass}>
                {t('enterprise_dashboard.create.skills', 'Compétences requises (séparées par des virgules)')}
              </label>
              <textarea 
                name="competences_requises"
                value={formData.competences_requises}
                onChange={handleChange}
                rows={3}
                placeholder={t('enterprise_dashboard.create.skills_ph', 'ex: React, Node.js, TypeScript...')}
                className={`${inputClass} resize-y bg-white`}
              />
            </div>
          </div>
        </div>
        
        <div className="flex justify-end pt-4">
          <button 
            type="submit" 
            disabled={loading}
            className="inline-flex items-center justify-center rounded-xl font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8cedaa] focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-[#8cedaa] text-slate-950 hover:bg-[#7bc897] px-8 py-3 w-full md:w-auto shadow-sm gap-2"
          >
            {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : <Save className="w-5 h-5" />}
            {t('enterprise_dashboard.create.publish_btn', 'Publier l\'offre')}
          </button>
        </div>
      </form>
    </div>
  );
};

export default CreateOffre;
