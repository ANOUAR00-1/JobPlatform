import React, { useState } from 'react';
import { Upload, Save, User as UserIcon, Phone, MapPin, Briefcase } from 'lucide-react';
import { useAuth } from '../../hooks/useAuth';
import { useToast } from '../../hooks/useToast';
import { useTranslation } from 'react-i18next';
import api from '../../services/api';

const Profile: React.FC = () => {
  const { user } = useAuth();
  const { addToast } = useToast();
  const { t } = useTranslation();

  const [loading, setLoading] = useState(false);
  const [cvFile, setCvFile] = useState<File | null>(null);
  const [formData, setFormData] = useState({
    telephone: user?.telephone || '',
    ville_id: '1',
    experience: 'Junior'
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSave = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    try {
      setLoading(true);
      const data = new FormData();
      data.append('telephone', formData.telephone);
      data.append('ville_id', formData.ville_id);
      data.append('experience', formData.experience);
      if (cvFile) {
        data.append('cv', cvFile);
      }
      await api.post('/candidat/profile', data, { headers: { 'Content-Type': 'multipart/form-data', } });
      addToast(t('candidat_dashboard.profile.success_updated', 'Profil mis à jour avec succès'), 'success');
    } catch (err) {
      const error = err as { response?: { data?: { message?: string } } };
      addToast(error.response?.data?.message || t('candidat_dashboard.profile.error_update', 'Erreur lors de la mise à jour'), 'error');
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-[#8cedaa]/20 focus:border-[#8cedaa] text-sm text-slate-950 transition-all font-medium";
  const labelClass = "block text-sm font-bold text-slate-950 mb-2";

  return (
    <div className="max-w-5xl mx-auto space-y-8 pb-12 bg-white">
      {/* Header */}
      <div className="pb-6">
        <h1 className="text-4xl font-bold text-slate-950 tracking-tight">
          {t('candidat_dashboard.profile.title', 'My Profile')}
        </h1>
        <p className="text-slate-600 mt-2 text-base font-medium">
          {t('candidat_dashboard.profile.subtitle', 'Manage your personal information and uploaded documents.')}
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Left Col - Identity Card */}
        <div className="bg-slate-50 border border-slate-200 rounded-2xl p-8 flex flex-col items-center text-center shadow-sm h-fit">
          <div className="w-24 h-24 bg-white rounded-2xl border border-slate-200 flex items-center justify-center mb-6 shadow-sm">
            <UserIcon className="w-10 h-10 text-slate-400" />
          </div>
          
          <h2 className="text-xl font-bold text-slate-950 mb-1">{user?.prenom} {user?.nom}</h2>
          <p className="text-sm text-slate-600 mb-6 font-medium">{user?.email}</p>
          
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-950 rounded-xl text-xs font-bold shadow-sm">
            <MapPin className="w-4 h-4" /> 
            {formData.ville_id === '1' ? t('candidat_dashboard.profile.casablanca', 'Casablanca') : t('candidat_dashboard.profile.rabat', 'Rabat')}
          </div>
        </div>

        {/* Right Col - Form */}
        <div className="lg:col-span-2">
          <form onSubmit={handleSave} className="bg-slate-50 border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            
            {/* Contact Info */}
            <div className="p-8 border-b border-slate-200">
              <h3 className="text-base font-bold text-slate-950 mb-6 flex items-center gap-2">
                <Phone className="w-5 h-5 text-slate-600 rtl:-scale-x-100" /> {t('candidat_dashboard.profile.contact_info', 'Contact Information')}
              </h3>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className={labelClass}>{t('candidat_dashboard.profile.phone', 'Phone')}</label>
                  <input 
                    type="text" 
                    name="telephone" 
                    value={formData.telephone} 
                    onChange={handleChange} 
                    required 
                    className={inputClass}
                    placeholder="+212 6XX XXX XXX"
                  />
                </div>
                
                <div>
                  <label className={labelClass}>{t('candidat_dashboard.profile.city', 'City')}</label>
                  <select 
                    name="ville_id" 
                    value={formData.ville_id} 
                    onChange={handleChange} 
                    className={inputClass}
                  >
                    <option value="1">{t('candidat_dashboard.profile.casablanca', 'Casablanca')}</option>
                    <option value="2">{t('candidat_dashboard.profile.rabat', 'Rabat')}</option>
                  </select>
                </div>
              </div>
            </div>

            {/* Professional */}
            <div className="p-8 border-b border-slate-200">
              <h3 className="text-base font-bold text-slate-950 mb-6 flex items-center gap-2">
                <Briefcase className="w-5 h-5 text-slate-600" /> {t('candidat_dashboard.profile.professional_bg', 'Professional Background')}
              </h3>
              
              <div className="space-y-6">
                <div className="max-w-md">
                  <label className={labelClass}>{t('candidat_dashboard.profile.experience_level', 'Experience Level')}</label>
                  <select 
                    name="experience" 
                    value={formData.experience} 
                    onChange={handleChange} 
                    className={inputClass}
                  >
                    <option value="Junior">{t('candidat_dashboard.profile.junior', 'Junior (0-2 yrs)')}</option>
                    <option value="Medior">{t('candidat_dashboard.profile.medior', 'Mid-Level (2-5 yrs)')}</option>
                    <option value="Senior">{t('candidat_dashboard.profile.senior', 'Senior (5+ yrs)')}</option>
                  </select>
                </div>
                
                <div>
                  <label className={labelClass}>{t('candidat_dashboard.profile.upload_cv', 'Resume (CV)')}</label>
                  <div className="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 flex flex-col items-center justify-center hover:bg-white hover:border-[#8cedaa] transition-all cursor-pointer group bg-white">
                    <input 
                      type="file" 
                      accept=".pdf,.doc,.docx"
                      onChange={(e) => setCvFile(e.target.files?.[0] || null)}
                      className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    />
                    <div className="w-12 h-12 bg-[#8cedaa]/10 text-[#2aa354] rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                      <Upload className="w-6 h-6" />
                    </div>
                    <p className="text-sm font-bold text-slate-950 mb-1">
                      {cvFile ? cvFile.name : t('candidat_dashboard.profile.drop_cv', 'Click to upload or drag and drop')}
                    </p>
                    <p className="text-xs text-slate-600 font-medium">
                      {t('candidat_dashboard.profile.supported', 'PDF, DOC, DOCX up to 5MB')}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Submit */}
            <div className="p-6 bg-white flex justify-end">
              <button 
                type="submit" 
                disabled={loading}
                className="px-8 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <Save className="w-5 h-5" /> {loading ? t('common.loading', 'Chargement...') : t('candidat_dashboard.profile.save_changes', 'Save Changes')}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Profile;
