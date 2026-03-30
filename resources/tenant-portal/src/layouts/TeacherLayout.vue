<template>
  <v-layout>
    <v-navigation-drawer v-model="drawer" permanent color="teal-darken-4" theme="dark">
      <v-list-item
        prepend-icon="mdi-human-male-board"
        :title="authStore.user?.name || 'Teacher'"
        subtitle="Staff Role"
      ></v-list-item>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item prepend-icon="mdi-view-dashboard" :title="$t('dashboard')" value="dashboard" to="/dashboard"></v-list-item>
        <v-list-item prepend-icon="mdi-book" title="My Classes" value="classes"></v-list-item>
        <v-list-item prepend-icon="mdi-file-edit" title="Homework" value="homework"></v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn block color="error" @click="authStore.logout">
            {{ $t('logout') }}
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-app-bar flat border>
      <v-app-bar-title>{{ $t('dashboard') }} - Academic</v-app-bar-title>
      <v-spacer></v-spacer>

      <!-- Notification Bell -->
      <v-btn icon>
        <v-badge :content="unreadCount" :model-value="unreadCount > 0" color="error" overlap>
          <v-icon>mdi-bell</v-icon>
        </v-badge>
      </v-btn>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-layout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';

const drawer = ref(true);
const authStore = useAuthStore();

const unreadCount = ref(0);
let pollInterval = null;

const fetchUnreadCount = async () => {
  try {
    const { data } = await axios.get('/api/notifications/unread-count');
    unreadCount.value = data.count;
  } catch (err) {
    // Silent fail
  }
};

onMounted(() => {
  fetchUnreadCount();
  pollInterval = setInterval(fetchUnreadCount, 30000);
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});
</script>
