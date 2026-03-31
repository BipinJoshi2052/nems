import { createI18n } from 'vue-i18n'
import en from '../locales/en.json'
import ne from '../locales/ne.json'

const i18n = createI18n({
  legacy: false, // use Composition API
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en,
    ne
  },
  // Ensure that the locale can be changed globally
  globalInjection: true
})

export default i18n
