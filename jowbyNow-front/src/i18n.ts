import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

import en from './locales/en.json';
import fr from './locales/fr.json';
import ar from './locales/ar.json';

const RTL_LANGUAGES = ['ar'];

/**
 * Update the <html> tag's `dir` and `lang` attributes
 * whenever the language changes, for proper RTL/LTR rendering.
 */
function applyDirection(lng: string) {
  const dir = RTL_LANGUAGES.includes(lng) ? 'rtl' : 'ltr';
  document.documentElement.setAttribute('dir', dir);
  document.documentElement.setAttribute('lang', lng);
  // Add/remove a class for Tailwind RTL utilities if needed
  if (dir === 'rtl') {
    document.documentElement.classList.add('rtl');
  } else {
    document.documentElement.classList.remove('rtl');
  }
}

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources: {
      en: { translation: en },
      fr: { translation: fr },
      ar: { translation: ar },
    },
    fallbackLng: 'fr', // Default to French (Moroccan platform)
    interpolation: {
      escapeValue: false, // React already escapes
    },
    detection: {
      order: ['localStorage', 'navigator'],
      caches: ['localStorage'],
    },
  });

// Apply direction on initialization
applyDirection(i18n.language || 'fr');

// Apply direction on every language change
i18n.on('languageChanged', (lng) => {
  applyDirection(lng);
});

export default i18n;
