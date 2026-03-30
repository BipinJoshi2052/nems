import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import ne from './locales/ne.json';

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en,
    ne
  }
});

export default i18n;
