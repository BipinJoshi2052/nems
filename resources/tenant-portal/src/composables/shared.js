import { ref, watch } from 'vue';

export function usePagination(callback, initialPerPage = 10) {
  const page = ref(1);
  const perPage = ref(initialPerPage);
  const total = ref(0);

  const goToPage = (newPage) => {
    page.value = newPage;
    callback();
  };

  const reset = () => {
    page.value = 1;
    callback();
  };

  return {
    page,
    perPage,
    total,
    goToPage,
    reset
  };
}

export function useDebounceSearch(callback, delay = 400) {
  const search = ref('');
  let timeout = null;

  watch(search, () => {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
      callback();
    }, delay);
  });

  return {
    search
  };
}

export function useApiResponse() {
  const loading = ref(false);
  const error = ref(null);
  const success = ref(false);

  const handleResponse = async (promise) => {
    loading.value = true;
    error.value = null;
    success.value = false;
    try {
      const response = await promise;
      success.value = true;
      return response.data;
    } catch (err) {
      error.value = err.response?.data?.message || 'An unexpected error occurred.';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    error,
    success,
    handleResponse
  };
}
