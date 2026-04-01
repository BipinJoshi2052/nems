import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import ne from './locales/ne.json';

const i18n = createI18n({
  legacy: false,
  locale: typeof localStorage !== 'undefined' ? localStorage.getItem('locale') || 'en' : 'en',
  fallbackLocale: 'en',
  messages: {
    en,
    ne
  }
});

export default i18n;
