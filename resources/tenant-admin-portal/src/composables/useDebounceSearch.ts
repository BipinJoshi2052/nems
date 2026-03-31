import { ref, watch } from 'vue'

export function useDebounceSearch(callback: (value: string) => void, delay = 400) {
  const search = ref('')
  let timeout: ReturnType<typeof setTimeout> | null = null

  watch(search, (newValue) => {
    if (timeout) clearTimeout(timeout)
    timeout = setTimeout(() => {
      callback(newValue)
    }, delay)
  })

  function reset() {
    search.value = ''
  }

  return {
    search,
    reset
  }
}
