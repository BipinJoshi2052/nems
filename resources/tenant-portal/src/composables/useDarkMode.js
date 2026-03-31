import { ref, watch, onMounted } from 'vue';

export function useDarkMode() {
  const isDarkMode = ref(localStorage.getItem('darkMode') === 'true');

  const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
  };

  watch(isDarkMode, (val) => {
    localStorage.setItem('darkMode', val);
    if (val) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }, { immediate: true });

  onMounted(() => {
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark');
    }
  });

  return {
    isDarkMode,
    toggleDarkMode,
  };
}
