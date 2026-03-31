import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import './assets/main.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import router from './router';
import i18n from './i18n';

// Vuetify
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';

// ApexCharts
import VueApexCharts from 'vue3-apexcharts';

import { useAuthStore } from './stores/auth';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: { mdi }
  },
  theme: {
    defaultTheme: 'light',
  }
});

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(vuetify);
app.use(i18n);
app.use(VueApexCharts);

// Restore session before mounting
const authStore = useAuthStore(pinia);
authStore.refresh().finally(() => {
  app.mount('#app');
});
