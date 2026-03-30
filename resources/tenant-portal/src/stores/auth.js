import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    accessToken: null,
    user: null,
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.accessToken,
    role: (state) => state.user?.role,
    userType: (state) => state.user?.userType,
  },

  actions: {
    setAccessToken(token) {
      this.accessToken = token;
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    async login(credentials) {
      this.loading = true;
      try {
        const { data } = await axios.post('/api/auth/login', credentials);
        this.setAccessToken(data.access_token);
        this.user = data.user;
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
      } catch (err) {
        this.logout();
      }
    },

    async logout() {
      try {
        await axios.post('/api/auth/logout');
      } catch (err) {
        // Ignore logout error
      } finally {
        this.accessToken = null;
        this.user = null;
        delete axios.defaults.headers.common['Authorization'];
        window.location.href = '/login';
      }
    }
  }
});
