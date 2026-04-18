import React, { useState } from 'react';
import { Star, Code2, Paintbrush, MonitorSmartphone, CandlestickChart, ChevronRight } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import or1Image from '../../assets/or1.webp';

const LandingPage: React.FC = () => {
  const navigate = useNavigate();
  const { t } = useTranslation();
  const [showCookie, setShowCookie] = useState(() => {
    // Check if user has already accepted/rejected cookies
    const cookieConsent = localStorage.getItem('cookieConsent');
    return cookieConsent === null; // Show banner only if no choice was made
  });

  const handleCookieChoice = (accepted: boolean) => {
    localStorage.setItem('cookieConsent', accepted ? 'accepted' : 'rejected');
    setShowCookie(false);
  };

  return (
    <div className="flex flex-col w-full min-h-screen bg-white selection:bg-emerald-200 font-sans">
      {/* Hero Section */}
      <section className="relative px-6 pt-24 pb-32 overflow-hidden flex items-center min-h-[90vh]">
        <div className="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
          
          <div className="flex flex-col z-20">
            <h1 className="text-6xl md:text-[5.5rem] font-bold text-slate-950 tracking-[-0.04em] leading-[1.05] mb-6">
              {t('hero.title_1')} <span className="relative inline-block">
                {t('hero.title_2')}
                <svg className="absolute w-full h-3 -bottom-1 left-0 text-[#8cedaa] -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                  <path d="M0 5 Q 50 10 100 5" stroke="currentColor" strokeWidth="8" fill="none" strokeLinecap="round" />
                </svg>
              </span>
              <br />{t('hero.title_3')}
            </h1>
            
            <p className="text-xl text-slate-600 max-w-lg font-normal leading-relaxed mb-10">
              {t('hero.subtitle')}
            </p>

            {/* Input Action inside a pill */}
            <div className="relative flex items-center w-full max-w-md bg-white border border-slate-200 rounded-2xl p-1.5 shadow-sm mb-12 focus-within:border-emerald-400 focus-within:ring-4 focus-within:ring-emerald-400/20 transition-all">
               <input 
                 type="text" 
                 placeholder={t('search.job_placeholder')}
                 className="flex-1 bg-transparent border-none text-slate-900 px-4 focus:outline-none placeholder:text-slate-400 w-full"
               />
               <button 
                 className="px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-colors shadow-sm whitespace-nowrap"
                 onClick={() => navigate('/offres')}
               >
                 {t('search.button')}
               </button>
            </div>

            {/* Stats Row exactly like the Monotree image */}
            <div className="flex flex-col space-y-8">
              <div className="flex items-center gap-12 border-b border-slate-100 pb-8">
                <div>
                  <div className="text-4xl font-bold text-slate-950 tracking-tight mb-1">75.2%</div>
                  <div className="text-sm font-medium text-slate-500">{t('stats.placement_rate')}</div>
                </div>
                <div className="w-px h-12 bg-slate-100"></div>
                <div>
                  <div className="text-4xl font-bold text-slate-950 tracking-tight mb-1">~20k</div>
                  <div className="text-sm font-medium text-slate-500">{t('stats.active_users')}</div>
                </div>
              </div>
              
              <div className="flex items-center gap-3">
                <div className="flex gap-1">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star key={star} className="w-5 h-5 fill-slate-950 text-slate-950" />
                  ))}
                </div>
                <div className="text-sm font-bold text-slate-950">4.9 <span className="text-slate-500 font-normal">{t('stats.user_rating')}</span></div>
              </div>
            </div>
          </div>

          {/* Right side - Hero Image */}
          <div className="relative hidden lg:flex items-start justify-start h-full w-full">
            <img 
              src={or1Image} 
              alt="Job Platform Dashboard" 
              className="w-full max-w-[650px] h-auto object-contain -mt-12"
            />
          </div>
        </div>
      </section>

      {/* Monotree style Sections */}
      <section className="py-24 bg-white border-t border-slate-100">
        <div className="max-w-7xl mx-auto w-full px-6">
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
             <div>
               <h2 className="text-4xl font-bold tracking-tight text-slate-950 mb-3">
                 {t('sectors.title')}
               </h2>
               <p className="text-lg text-slate-500 max-w-xl">
                 {t('sectors.subtitle')}
               </p>
             </div>
             <button className="text-slate-950 font-bold hover:text-emerald-500 flex items-center gap-1 transition-colors text-sm uppercase tracking-widest">
               {t('sectors.view_all')}
               <ChevronRight className="w-4 h-4" />
             </button>
          </div>
            
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {[
              { title: t('sectors.software'), jobs: 340, icon: <Code2 className="w-6 h-6" /> },
              { title: t('sectors.design'), jobs: 180, icon: <Paintbrush className="w-6 h-6" /> },
              { title: t('sectors.management'), jobs: 220, icon: <CandlestickChart className="w-6 h-6" /> },
              { title: t('sectors.mobile'), jobs: 110, icon: <MonitorSmartphone className="w-6 h-6" /> },
            ].map((cat, i) => (
              <div 
                key={i} 
                className="group p-8 bg-slate-50 rounded-4xl hover:bg-slate-100 transition-colors cursor-pointer flex flex-col justify-between min-h-[220px]"
              >
                <div className="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-slate-950 mb-8 group-hover:-translate-y-1 transition-transform">
                  {cat.icon}
                </div>
                <div>
                   <h3 className="text-xl font-bold text-slate-950 mb-2 leading-tight">{cat.title}</h3>
                   <div className="inline-flex items-center text-xs font-bold text-slate-500 bg-slate-200/50 px-3 py-1.5 rounded-full">
                     {cat.jobs} {t('sectors.open_roles')}
                   </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Footer / CTA Area */}
      <section className="py-32 px-6 bg-slate-50 border-t border-slate-100">
        <div className="max-w-4xl mx-auto text-center flex flex-col items-center">
          <h2 className="text-5xl font-bold text-slate-950 tracking-[-0.04em] mb-6">
            {t('cta.title')}
          </h2>
          <p className="text-xl text-slate-500 mb-10 max-w-2xl">
            {t('cta.subtitle')}
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
            <button 
              className="w-full sm:w-auto px-10 py-4 bg-[#8cedaa] text-slate-950 hover:bg-[#7bc897] font-bold rounded-2xl shadow-sm transition-colors text-lg whitespace-nowrap"
              onClick={() => navigate('/register')}
            >
              {t('cta.create_account')}
            </button>
            <button 
              className="w-full sm:w-auto px-10 py-4 bg-white text-slate-950 border border-slate-200 hover:border-slate-300 font-bold rounded-2xl shadow-sm transition-colors text-lg whitespace-nowrap"
              onClick={() => navigate('/login')}
            >
              {t('cta.login_secure')}
            </button>
          </div>
        </div>
      </section>

      {/* Mock Cookie Banner */}
      {showCookie && (
        <div className="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-3rem)] max-w-5xl bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 z-50">
          <div className="flex items-center gap-4">
            <div className="text-2xl">🍪</div>
            <p className="text-sm text-slate-600 font-medium">
              <span className="font-bold text-slate-950 mr-2">{t('cookie.title')}</span>
              {t('cookie.message')} <span className="underline decoration-slate-300 underline-offset-4 hover:text-slate-950 cursor-pointer transition-colors">{t('cookie.policy')}</span>.
            </p>
          </div>
          <div className="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
             <button onClick={() => handleCookieChoice(true)} className="flex-1 sm:flex-none px-5 py-2.5 text-sm font-bold text-slate-950 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">{t('cookie.accept')}</button>
             <button onClick={() => handleCookieChoice(false)} className="flex-1 sm:flex-none px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">{t('cookie.reject')}</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default LandingPage;

