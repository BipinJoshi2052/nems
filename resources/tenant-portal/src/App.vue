<template>
  <v-app>
    <router-view></router-view>
  </v-app>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();

onMounted(async () => {
  // Silent refresh on app mount
  await authStore.refresh();
});
</script>

<style>
/* Global styles can go here */
</style>
