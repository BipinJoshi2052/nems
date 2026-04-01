<template>
  <div class="relative">
    <button
      @click="isOpen = !isOpen"
      v-outside="closeDropdown"
      class="flex items-center justify-center w-10 h-10 text-gray-500 bg-white border border-gray-200 rounded-lg dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11 transition-all hover:bg-gray-50 dark:hover:bg-gray-800"
    >
      <span class="text-[10px] font-bold uppercase tracking-tighter">{{ currentLocale }}</span>
    </button>

    <div
      v-if="isOpen"
      class="absolute right-0 z-9999 mt-2 w-32 origin-top-right rounded-xl bg-white p-1 shadow-lg ring-1 ring-gray-200 focus:outline-none dark:bg-gray-900 dark:ring-gray-800"
    >
      <button
        @click="setLocale('en')"
        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5 transition-colors"
        :class="{ 'bg-gray-50 dark:bg-white/5 text-primary-600 dark:text-primary-400 font-medium': currentLocale === 'en' }"
      >
        <span class="w-4">🇺🇸</span>
        English
      </button>
      <button
        @click="setLocale('ne')"
        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5 transition-colors"
        :class="{ 'bg-gray-50 dark:bg-white/5 text-primary-600 dark:text-primary-400 font-medium': currentLocale === 'ne' }"
      >
        <span class="w-4">🇳🇵</span>
        नेपाली
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const isOpen = ref(false)

const currentLocale = computed(() => locale.value)

const closeDropdown = () => {
  isOpen.value = false
}

const setLocale = (lang: string) => {
  locale.value = lang
  localStorage.setItem('locale', lang)
  isOpen.value = false
}

// Simple outside click directive logic
const vOutside = {
  mounted(el: any, binding: any) {
    el.clickOutsideEvent = (event: Event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value()
      }
    }
    document.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el: any) {
    document.removeEventListener('click', el.clickOutsideEvent)
  },
}
</script>
