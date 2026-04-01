<template>
  <div v-if="meta.last_page > 1" class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6 dark:bg-boxdark dark:border-strokedark">
    <div class="flex justify-between flex-1 sm:hidden">
      <button
        @click="$emit('change', meta.current_page - 1)"
        :disabled="meta.current_page === 1"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50"
      >
        Previous
      </button>
      <button
        @click="$emit('change', meta.current_page + 1)"
        :disabled="meta.current_page === meta.last_page"
        class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50"
      >
        Next
      </button>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-400">
          Showing
          <span class="font-medium">{{ meta.from }}</span>
          to
          <span class="font-medium">{{ meta.to }}</span>
          of
          <span class="font-medium">{{ meta.total }}</span>
          results
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <button
            @click="$emit('change', meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 disabled:opacity-50 dark:bg-boxdark dark:border-strokedark"
          >
            <span class="sr-only">Previous</span>
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <button
            v-for="page in pages"
            :key="page"
            @click="$emit('change', page)"
            :class="[
              page === meta.current_page
                ? 'z-10 bg-primary border-primary text-white'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-boxdark dark:border-strokedark dark:text-gray-400',
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
            ]"
          >
            {{ page }}
          </button>

          <button
            @click="$emit('change', meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 disabled:opacity-50 dark:bg-boxdark dark:border-strokedark"
          >
            <span class="sr-only">Next</span>
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  meta: {
    current_page: number
    last_page: number
    from: number
    to: number
    total: number
  }
}>()

defineEmits(['change'])

const pages = computed(() => {
  const range = []
  for (let i = Math.max(1, props.meta.current_page - 2); i <= Math.min(props.meta.last_page, props.meta.current_page + 2); i++) {
    range.push(i)
  }
  return range
})
</script>
