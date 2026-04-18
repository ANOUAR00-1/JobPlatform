import React from 'react';
import { Outlet, Link, useNavigate } from 'react-router-dom';
import { Menu, X, ArrowUpRight, Globe } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import ThemeToggle from '../components/ThemeToggle';

const PublicLayout: React.FC = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isLangOpen, setIsLangOpen] = useState(false);
  const navigate = useNavigate();
  const { isAuthenticated, user } = useAuth();
  const { t, i18n } = useTranslation();

  const navLinks = [
    { label: t('nav.home'), path: '/' },
    { label: t('nav.offers'), path: '/offres' },
  ];

  const languages = [
    { code: 'en', label: 'EN', flag: '🇬🇧' },
    { code: 'fr', label: 'FR', flag: '🇫🇷' },
    { code: 'ar', label: 'ع', flag: '🇲🇦' },
  ];

  const currentLang = languages.find((l) => l.code === i18n.language) || languages[1];

  return (
    <div className="min-h-screen flex flex-col bg-white dark:bg-slate-950 text-slate-600 dark:text-slate-300 font-sans relative">

      {/* Header — Monotree Style: Clean, light, subtle borders */}
      <header className="sticky top-0 z-50 w-full bg-white/95 dark:bg-slate-950/95 backdrop-blur-sm border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
          
          <Link to="/" className="flex items-center gap-3 group">
            <span className="text-2xl font-display font-bold tracking-tight">
              <span className="text-[#2aa354]">J</span><span className="text-slate-950 dark:text-white">oby</span><span className="text-[#2aa354]">N</span><span className="text-slate-600 dark:text-slate-400">ow</span>
            </span>
          </Link>

          {/* Desktop Nav */}
          <nav className="hidden md:flex items-center gap-2">
            {navLinks.map((link) => (
              <Link 
                key={link.path} 
                to={link.path}
                className="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white px-4 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
              >
                {link.label}
              </Link>
            ))}
          </nav>

          <div className="hidden md:flex items-center gap-3">
            {/* Theme Toggle */}
            <ThemeToggle />

            {/* Language Switcher */}
            <div className="relative">
              <button
                onClick={() => setIsLangOpen(!isLangOpen)}
                className="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-all text-sm font-medium"
              >
                <Globe className="w-4 h-4" />
                <span>{currentLang.flag}</span>
                <span>{currentLang.label}</span>
              </button>

              {isLangOpen && (
                <div className="absolute top-full mt-2 right-0 rtl:right-auto rtl:left-0 z-50 min-w-[140px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden">
                  {languages.map((lang) => (
                    <button
                      key={lang.code}
                      onClick={() => { i18n.changeLanguage(lang.code); setIsLangOpen(false); }}
                      className={`w-full flex items-center gap-3 px-4 py-3 text-sm font-medium transition-all ${
                        i18n.language === lang.code
                          ? 'bg-[#8cedaa]/10 text-slate-950 dark:text-white font-semibold'
                          : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'
                      }`}
                    >
                      <span className="text-base">{lang.flag}</span>
                      <span>{lang.label}</span>
                    </button>
                  ))}
                </div>
              )}
            </div>

            {isAuthenticated ? (
              <button 
                onClick={() => navigate(`/${user?.role || 'candidat'}`)}
                className="px-6 py-2.5 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm text-sm"
              >
                {t('nav.dashboard')}
              </button>
            ) : (
              <>
                <button 
                  onClick={() => navigate('/login')}
                  className="px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-sm"
                >
                  {t('nav.login')}
                </button>
                <button 
                  onClick={() => navigate('/register')}
                  className="px-6 py-2.5 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm text-sm"
                >
                  {t('nav.signup')}
                </button>
              </>
            )}
          </div>

          {/* Mobile Menu Toggle */}
          <button 
            className="md:hidden p-2 text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-all"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
          >
            {isMenuOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>

        {/* Mobile Nav Dropdown */}
        {isMenuOpen && (
          <div className="md:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 py-6 flex flex-col gap-4">
            {navLinks.map((link) => (
              <Link 
                key={link.path} 
                to={link.path}
                className="text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white py-3 border-b border-slate-100 dark:border-slate-800 transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                {link.label}
              </Link>
            ))}
            
            {/* Mobile Theme Toggle */}
            <div className="py-3 border-b border-slate-100 dark:border-slate-800">
              <div className="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{t('nav.theme') || 'Theme'}</div>
              <ThemeToggle />
            </div>

            {/* Mobile Language Switcher */}
            <div className="py-3 border-b border-slate-100 dark:border-slate-800">
              <div className="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{t('nav.language') || 'Language'}</div>
              <div className="flex gap-2">
                {languages.map((lang) => (
                  <button
                    key={lang.code}
                    onClick={() => { i18n.changeLanguage(lang.code); setIsMenuOpen(false); }}
                    className={`flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all ${
                      i18n.language === lang.code
                        ? 'bg-[#8cedaa]/20 text-slate-950 font-semibold border border-[#8cedaa]/30'
                        : 'bg-slate-50 text-slate-600 border border-slate-200'
                    }`}
                  >
                    <span>{lang.flag}</span>
                    <span>{lang.label}</span>
                  </button>
                ))}
              </div>
            </div>

            <div className="flex flex-col gap-3 pt-2">
              {isAuthenticated ? (
                <button 
                  onClick={() => { setIsMenuOpen(false); navigate(`/${user?.role || 'candidat'}`); }}
                  className="w-full px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm"
                >
                  {t('nav.dashboard')}
                </button>
              ) : (
                <>
                  <button 
                    onClick={() => { setIsMenuOpen(false); navigate('/login'); }}
                    className="w-full px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-950 font-semibold rounded-xl transition-all border border-slate-200"
                  >
                    {t('nav.login')}
                  </button>
                  <button 
                    onClick={() => { setIsMenuOpen(false); navigate('/register'); }}
                    className="w-full px-6 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm"
                  >
                    {t('nav.signup')}
                  </button>
                </>
              )}
            </div>
          </div>
        )}
      </header>

      {/* Main Content Area */}
      <main className="flex-1 relative z-10 flex flex-col">
        <Outlet />
      </main>

      {/* Footer — Monotree Style: Clean, light, subtle borders */}
      <footer className="relative z-10 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div className="max-w-7xl mx-auto px-6 py-16">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            {/* Brand Column */}
            <div className="md:col-span-1">
              <Link to="/" className="flex items-center gap-2 mb-4">
                <span className="text-xl font-display font-bold tracking-tight">
                  <span className="text-[#2aa354]">J</span><span className="text-slate-950 dark:text-white">oby</span><span className="text-[#2aa354]">N</span><span className="text-slate-950 dark:text-white">ow</span>
                </span>
              </Link>
              <p className="text-slate-500 dark:text-slate-400 text-sm leading-relaxed max-w-xs font-medium">
                {t('footer.description')}
              </p>
            </div>
            
            {/* Navigation Column */}
            <div>
              <h4 className="text-slate-950 dark:text-white font-display font-bold text-sm mb-4">{t('footer.navigation')}</h4>
              <ul className="space-y-3 text-sm">
                {[
                  { label: t('footer.browse_jobs'), path: '/offres' },
                  { label: t('footer.create_account'), path: '/register' },
                  { label: t('footer.sign_in'), path: '/login' },
                ].map(link => (
                  <li key={link.path}>
                    <Link to={link.path} className="text-slate-500 dark:text-slate-400 hover:text-[#2aa354] transition-colors flex items-center gap-2 group font-medium">
                      {link.label} 
                      <ArrowUpRight className="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Legal Column */}
            <div>
              <h4 className="text-slate-950 dark:text-white font-display font-bold text-sm mb-4">{t('footer.legal')}</h4>
              <ul className="space-y-3 text-sm">
                <li>
                  <Link to="/privacy-policy" className="text-slate-500 dark:text-slate-400 hover:text-[#2aa354] transition-colors font-medium">
                    {t('footer.privacy')}
                  </Link>
                </li>
                <li>
                  <Link to="/terms-of-service" className="text-slate-500 dark:text-slate-400 hover:text-[#2aa354] transition-colors font-medium">
                    {t('footer.terms')}
                  </Link>
                </li>
                <li>
                  <Link to="/cookie-policy" className="text-slate-500 dark:text-slate-400 hover:text-[#2aa354] transition-colors font-medium">
                    {t('footer.cookies')}
                  </Link>
                </li>
              </ul>
            </div>

            {/* CTA Column */}
            <div>
              <h4 className="text-slate-950 dark:text-white font-display font-bold text-sm mb-4">{t('footer.get_started') || 'Get Started'}</h4>
              <p className="text-slate-500 dark:text-slate-400 text-sm mb-4 font-medium">
                {t('footer.cta_text') || 'Join thousands of companies transforming their recruitment.'}
              </p>
              <button 
                onClick={() => navigate('/register')}
                className="w-full px-5 py-3 bg-[#8cedaa] hover:bg-[#7bc897] text-slate-950 font-semibold rounded-xl transition-all shadow-sm text-sm"
              >
                {t('footer.start_free') || 'Start for free'}
              </button>
            </div>
          </div>
          
          {/* Bottom Bar */}
          <div className="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p className="text-xs text-slate-500 dark:text-slate-400 font-medium">
              {t('footer.rights')}
            </p>
            <div className="flex items-center gap-3">
              <a href="#" className="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-[#8cedaa] hover:bg-[#8cedaa]/10 transition-all text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white text-xs font-bold">
                LI
              </a>
              <a href="#" className="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-[#8cedaa] hover:bg-[#8cedaa]/10 transition-all text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white text-xs font-bold">
                TW
              </a>
              <a href="#" className="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-[#8cedaa] hover:bg-[#8cedaa]/10 transition-all text-slate-600 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white text-xs font-bold">
                GH
              </a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default PublicLayout;
