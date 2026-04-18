import React, { useState, useRef, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Globe } from 'lucide-react';

const LANGUAGES = [
  { code: 'en', label: 'EN', flag: '🇬🇧' },
  { code: 'fr', label: 'FR', flag: '🇫🇷' },
  { code: 'ar', label: 'ع', flag: '🇲🇦' },
];

const LanguageSwitcher: React.FC = () => {
  const { i18n } = useTranslation();
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  const currentLang = LANGUAGES.find((l) => l.code === i18n.language) || LANGUAGES[1]; // default FR

  const handleChange = (code: string) => {
    i18n.changeLanguage(code);
    setIsOpen(false);
  };

  // Close dropdown on outside click
  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target as Node)) {
        setIsOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  return (
    <div className="relative" ref={dropdownRef}>
      {/* Trigger Button */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center gap-2 px-3 py-2 border border-slate-200 bg-white hover:border-emerald-500 text-slate-600 hover:text-slate-950 transition-all text-xs font-semibold rounded-xl"
        aria-label="Change language"
      >
        <Globe className="w-4 h-4 text-emerald-600" />
        <span>{currentLang.flag}</span>
        <span>{currentLang.label}</span>
      </button>

      {/* Dropdown */}
      {isOpen && (
        <div className="absolute top-full mt-1 right-0 rtl:right-auto rtl:left-0 z-999 min-w-[140px] border border-slate-200 bg-white shadow-lg rounded-xl overflow-hidden animate-slide-up-stagger">
          {LANGUAGES.map((lang) => (
            <button
              key={lang.code}
              onClick={() => handleChange(lang.code)}
              className={`w-full flex items-center gap-3 px-4 py-3 text-xs font-medium transition-all
                ${
                  i18n.language === lang.code
                    ? 'bg-emerald-50 text-emerald-700 border-l-2 border-emerald-500'
                    : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50 border-l-2 border-transparent'
                }
              `}
            >
              <span className="text-base">{lang.flag}</span>
              <span>{lang.label}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  );
};

export default LanguageSwitcher;
