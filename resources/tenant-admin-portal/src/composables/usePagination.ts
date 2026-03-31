import { ref, computed } from 'vue'

export function usePagination(initialPerPage = 10) {
  const page = ref(1)
  const perPage = ref(initialPerPage)
  const total = ref(0)

  const totalPages = computed(() => Math.ceil(total.value / perPage.value))

  function goToPage(newPage: number) {
    if (newPage >= 1 && newPage <= totalPages.value) {
      page.value = newPage
    }
  }

  function reset() {
    page.value = 1
  }

  return {
    page,
    perPage,
    total,
    totalPages,
    goToPage,
    reset
  }
}
