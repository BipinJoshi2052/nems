<template>
  <v-layout>
    <v-navigation-drawer v-model="drawer" permanent>
      <v-list-item
        prepend-icon="mdi-mortarboard"
        :title="authStore.user?.name || 'School Admin'"
        subtitle="Institution Admin"
      ></v-list-item>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item prepend-icon="mdi-view-dashboard" :title="$t('dashboard')" value="dashboard" to="/dashboard"></v-list-item>
        <v-list-item prepend-icon="mdi-account-group" title="Students" value="students"></v-list-item>
        <v-list-item prepend-icon="mdi-calendar-check" title="Attendance" value="attendance"></v-list-item>
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
      <v-app-bar-title>{{ $t('dashboard') }}</v-app-bar-title>
      <v-spacer></v-spacer>
      
      <!-- Notification Bell -->
      <v-menu offset-y>
        <template v-slot:activator="{ props }">
          <v-btn icon v-bind="props">
            <v-badge :content="unreadCount" :model-value="unreadCount > 0" color="error" overlap>
              <v-icon>mdi-bell</v-icon>
            </v-badge>
          </v-btn>
        </template>
        <v-list width="300">
          <v-list-item v-if="notifications.length === 0">
            <v-list-item-title class="text-center text-muted py-2">No new notifications</v-list-item-title>
          </v-list-item>
          <v-list-item v-for="item in notifications" :key="item.id" @click="markAsRead(item)">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
            <v-list-item-subtitle>{{ item.body }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-btn icon @click="changeLanguage">
        <v-icon>mdi-translate</v-icon>
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
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const drawer = ref(true);
const authStore = useAuthStore();
const { locale } = useI18n();

const unreadCount = ref(0);
const notifications = ref([]);
let pollInterval = null;

const fetchUnreadCount = async () => {
  try {
    // const { data } = await axios.get('/api/notifications/unread-count');
    // unreadCount.value = data.count;
  } catch (err) {
    // Silent fail for polling
  }
};

const changeLanguage = () => {
  locale.value = locale.value === 'en' ? 'ne' : 'en';
};

onMounted(() => {
  fetchUnreadCount();
  pollInterval = setInterval(fetchUnreadCount, 30000); // 30s
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});
</script>
