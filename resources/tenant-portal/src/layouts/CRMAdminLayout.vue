<template>
  <div class="flex h-screen bg-slate-100 dark:bg-slate-900 overflow-hidden">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <!-- Logo -->
      <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
        <span class="text-xl font-bold text-slate-900 dark:text-white">TailAdmin</span>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto px-4 py-4">
        <!-- MENU Section -->
        <div class="mb-6">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Menu</p>

          <!-- Dashboard -->
          <div class="mb-1">
            <button
              @click="toggleSection('dashboard')"
              class="w-full flex items-center justify-between px-3 py-2.5 text-white bg-blue-500 rounded-lg group"
            >
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
              </div>
              <svg :class="['w-4 h-4 transition-transform', openSections.dashboard ? 'transform rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <transition name="slide-fade">
              <div v-if="openSections.dashboard" class="ml-4 mt-1 space-y-1">
                <router-link to="/dashboard/ecommerce" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">Ecommerce</router-link>
                <router-link to="/dashboard/analytics" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">Analytics</router-link>
                <router-link to="/dashboard/marketing" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">Marketing</router-link>
                <router-link to="/dashboard/crm" class="block px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-lg font-medium">CRM</router-link>
                <router-link to="/dashboard/stocks" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">Stocks</router-link>
                <router-link to="/dashboard/saas" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">
                  <span>SaaS</span>
                  <span class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">NEW</span>
                </router-link>
                <router-link to="/dashboard/logistics" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">
                  <span>Logistics</span>
                  <span class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">NEW</span>
                </router-link>
              </div>
            </transition>
          </div>

          <!-- Other Menu Items -->
          <NavItem icon="sparkles" label="AI Assistant" badge="NEW" collapsible />
          <NavItem icon="shopping-cart" label="E-commerce" badge="NEW" collapsible />
          <NavItem icon="calendar" label="Calendar" />
          <NavItem icon="user" label="User Profile" />
          <NavItem icon="clipboard-list" label="Task" collapsible />
          <NavItem icon="document-text" label="Forms" collapsible />
          <NavItem icon="table" label="Tables" collapsible />
          <NavItem icon="document-duplicate" label="Pages" collapsible />
        </div>

        <!-- SUPPORT Section -->
        <div class="mb-6">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Support</p>
          <NavItem icon="chat" label="Chat" />
          <NavItem icon="mail" label="Email" collapsible />
          <NavItem icon="ticket" label="Support Ticket" collapsible />
        </div>

        <!-- OTHERS Section -->
        <div class="mb-6">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Others</p>
          <NavItem icon="chart-bar" label="Charts" collapsible />
          <NavItem icon="color-swatch" label="UI Elements" collapsible />
          <NavItem icon="lock-closed" label="Authentication" collapsible />
        </div>
      </nav>

      <!-- Bottom Banner -->
      <div class="p-4 m-4 bg-blue-50 dark:bg-slate-700 rounded-lg border border-blue-100 dark:border-slate-600">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">#1 Tailwind CSS Dashboard</h3>
        <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">Leading Tailwind CSS Admin Template with 400+ UI Component and Pages.</p>
        <button class="w-full px-3 py-2 text-xs font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
          Purchase Plan
        </button>
      </div>
    </aside>

    <!-- Sidebar Backdrop (mobile) -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
    ></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="bg-white dark:bg-slate-800 shadow-sm z-30">
        <div class="flex items-center justify-between px-6 py-4">
          <!-- Left: Hamburger + Search -->
          <div class="flex items-center gap-4 flex-1">
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="lg:hidden p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <div class="relative flex-1 max-w-md">
              <input
                ref="searchInput"
                type="text"
                placeholder="Search or type command..."
                class="w-full px-4 py-2.5 pr-16 text-sm bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white dark:placeholder-slate-400"
                @keydown="handleSearch"
              />
              <kbd class="absolute right-3 top-1/2 transform -translate-y-1/2 px-2 py-1 text-xs font-semibold text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-600 border border-slate-200 dark:border-slate-500 rounded shadow-sm">
                {{ isMac ? '⌘K' : 'Ctrl+K' }}
              </kbd>
            </div>
          </div>

          <!-- Right: Dark Mode, Notifications, User -->
          <div class="flex items-center gap-4">
            <button
              @click="toggleDarkMode"
              class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
            >
              <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </button>

            <button class="relative p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div class="relative">
              <button
                @click="userMenuOpen = !userMenuOpen"
                class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
              >
                <img
                  src="https://ui-avatars.com/api/?name=Musharof&background=3B82F6&color=fff"
                  alt="User"
                  class="w-8 h-8 rounded-full"
                />
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200 hidden md:block">Musharof</span>
                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <transition name="dropdown">
                <div
                  v-if="userMenuOpen"
                  class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-1"
                >
                  <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Profile</a>
                  <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Settings</a>
                  <hr class="my-1 border-slate-200 dark:border-slate-700" />
                  <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-slate-100 dark:hover:bg-slate-700">Logout</a>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useDarkMode } from '../composables/useDarkMode';
import NavItem from '../components/NavItem.vue';

const { isDark, toggle: toggleDarkMode } = useDarkMode();

const sidebarOpen = ref(false);
const userMenuOpen = ref(false);
const openSections = ref({
  dashboard: true,
});
const searchInput = ref(null);

const isMac = computed(() => {
  return navigator.platform.toUpperCase().indexOf('MAC') >= 0;
});

const toggleSection = (section) => {
  openSections.value[section] = !openSections.value[section];
};

const handleSearch = (e) => {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault();
    searchInput.value?.focus();
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleSearch);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleSearch);
});
</script>

<style scoped>
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
