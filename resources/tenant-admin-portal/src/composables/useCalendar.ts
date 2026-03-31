import { useI18n } from 'vue-i18n'

export function useCalendar() {
  const { locale } = useI18n()

  // Placeholder for BS/AD conversion logic
  // In a real scenario, this would import a library like 'ad-bs-converter'
  
  function formatDate(date: Date | string) {
    const d = new Date(date)
    return d.toLocaleDateString(locale.value === 'ne' ? 'ne-NP' : 'en-US')
  }

  return {
    formatDate
  }
}
