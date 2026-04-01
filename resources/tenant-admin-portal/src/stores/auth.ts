import { defineStore } from 'pinia';
import axios from 'axios';

// Enable cookies for all requests
axios.defaults.withCredentials = true;

export const useAuthStore = defineStore('auth', {
  state: () => ({
    accessToken: null as string | null,
    user: null as {
      id: number,
      name: string,
      email: string,
      role: string,
      userType: 'staff' | 'parent',
      language_preference: 'en' | 'ne',
      is_setup_complete: boolean
    } | null,
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.accessToken,
  },

  actions: {
    setAccessToken(token: string) {
      this.accessToken = token;
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    async login(credentials: any) {
      this.loading = true;
      try {
        const { data } = await axios.post('/api/auth/login', credentials);
        this.setAccessToken(data.access_token);
        this.user = data.user;
        this.syncLocale();
        return data;
      } finally {
        this.loading = false;
      }
    },

    async refresh() {
      try {
        const { data } = await axios.post('/api/auth/refresh');
        this.setAccessToken(data.access_token);
        this.user = data.user;
        this.syncLocale();
      } catch (err) {
        this.clearAuth();
      }
    },

    syncLocale() {
      if (this.user?.language_preference) {
        import('../plugins/i18n').then((m) => {
          m.default.global.locale.value = this.user!.language_preference;
        });
      }
    },

    async updatePreferences(preferences: { language_preference: 'en' | 'ne' }) {
      try {
        await axios.put('/api/user/preferences', preferences);
        if (this.user) {
          this.user.language_preference = preferences.language_preference;
          this.syncLocale();
        }
      } catch (err) {
        console.error('Failed to update preferences', err);
      }
    },

    clearAuth() {
      this.accessToken = null;
      this.user = null;
      delete axios.defaults.headers.common['Authorization'];
    },

    async logout() {
      try {
        await axios.post('/api/auth/logout');
      } catch (err) {
        // Ignore
      } finally {
        this.clearAuth();
        window.location.href = '/login';
      }
    }
  }
});
