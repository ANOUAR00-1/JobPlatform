import React from 'react';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';
import { Cookie, Settings, Shield, Info } from 'lucide-react';

const CookiePolicy: React.FC = () => {
  const { t } = useTranslation();

  return (
    <div className="w-full min-h-screen bg-slate-50 font-sans">
      {/* Header */}
      <div className="bg-white border-b border-slate-200 py-16 px-6">
        <div className="max-w-4xl mx-auto text-center">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-6">
            <Cookie className="w-8 h-8 text-emerald-600" />
          </div>
          <h1 className="text-4xl md:text-5xl font-bold text-slate-950 tracking-tight mb-4">
            {t('cookie_policy.title')}
          </h1>
          <p className="text-lg text-slate-500">
            {t('cookie_policy.last_updated')}
          </p>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-4xl mx-auto px-6 py-16">
        <div className="bg-white rounded-2xl border border-slate-200 p-8 md:p-12 space-y-12">
          
          {/* Introduction */}
          <section>
            <p className="text-slate-600 leading-relaxed">
              {t('cookie_policy.intro')}
            </p>
          </section>

          {/* What Are Cookies */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Cookie className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">{t('cookie_policy.what_are_title')}</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              {t('cookie_policy.what_are_desc')}
            </p>
          </section>

          {/* Types of Cookies We Use */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Settings className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">{t('cookie_policy.types_title')}</h2>
            </div>
            
            <div className="space-y-6">
              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">{t('cookie_policy.essential_title')}</h3>
                <p className="text-slate-600 leading-relaxed">
                  {t('cookie_policy.essential_desc')}
                </p>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">{t('cookie_policy.performance_title')}</h3>
                <p className="text-slate-600 leading-relaxed">
                  {t('cookie_policy.performance_desc')}
                </p>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">{t('cookie_policy.functional_title')}</h3>
                <p className="text-slate-600 leading-relaxed">
                  {t('cookie_policy.functional_desc')}
                </p>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">{t('cookie_policy.targeting_title')}</h3>
                <p className="text-slate-600 leading-relaxed">
                  {t('cookie_policy.targeting_desc')}
                </p>
              </div>
            </div>
          </section>

          {/* Third-Party Cookies */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Shield className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">{t('cookie_policy.third_party_title')}</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              {t('cookie_policy.third_party_desc')} For more information about how we handle your data, please see our{' '}
              <Link 
                to="/privacy-policy" 
                className="text-emerald-600 hover:text-emerald-700 underline font-semibold transition-colors"
              >
                Privacy Policy
              </Link>.
            </p>
          </section>

          {/* Cookie Management and Control */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Settings className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">{t('cookie_policy.management_title')}</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed mb-4">
              {t('cookie_policy.management_desc')}
            </p>
            <p className="text-slate-600 leading-relaxed">
              {t('cookie_policy.browser_settings')}
            </p>
          </section>

          {/* Contact Information */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Info className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">{t('cookie_policy.contact_title')}</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed mb-4">
              {t('cookie_policy.contact_desc')}
            </p>
            <div className="bg-slate-50 rounded-xl p-6 border border-slate-200">
              <p className="text-slate-950 font-semibold mb-2">{t('cookie_policy.contact_team')}</p>
              <p className="text-slate-600">{t('cookie_policy.contact_email')}</p>
              <p className="text-slate-600">{t('cookie_policy.contact_address')}</p>
            </div>
          </section>
        </div>
      </div>
    </div>
  );
};

export default CookiePolicy;
