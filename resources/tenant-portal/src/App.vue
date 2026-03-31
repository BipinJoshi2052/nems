<template>
  <component :is="useVuetify ? 'v-app' : 'div'" :class="{ 'min-h-screen': !useVuetify }">
    <router-view></router-view>
  </component>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();
const route = useRoute();

// Use Vuetify wrapper only for non-CRM routes
const useVuetify = computed(() => {
  return !route.path.includes('/crm');
});

onMounted(async () => {
  // Silent refresh on app mount
  await authStore.refresh();
});
</script>

<style>
/* Global styles can go here */
</style>
