import { ref } from 'vue'

export function useApiResponse() {
  const loading = ref(false)
  const error = ref<string | null>(null)
  const success = ref(false)

  async function execute<T>(promise: Promise<T>): Promise<T | null> {
    loading.value = true
    error.value = null
    success.value = false
    try {
      const result = await promise
      success.value = true
      return result
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'An error occurred'
      return null
    } finally {
      loading.value = false
    }
  }

  function clear() {
    loading.value = false
    error.value = null
    success.value = false
  }

  return {
    loading,
    error,
    success,
    execute,
    clear
  }
}
