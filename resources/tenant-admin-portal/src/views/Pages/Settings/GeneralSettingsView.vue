<template>
  <MainLayout>
    <div class="p-4 md:p-8 font-outfit">
      <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">General Settings</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your institution's core preferences and system appearance.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar Navigation (Internal) -->
        <div class="lg:col-span-3 space-y-2">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-sm',
              activeTab === tab.id 
                ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' 
                : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800'
            ]"
          >
            <component :is="tab.icon" class="w-5 h-5" />
            {{ tab.name }}
          </button>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-9">
          <div class="bg-white dark:bg-boxdark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8">
            
            <!-- Appearance Group -->
            <div v-if="activeTab === 'appearance'" class="space-y-8 animate-fadeIn">
              <div>
                <h3 class="text-xl font-bold dark:text-white mb-6 border-b dark:border-gray-800 pb-4">Personalization</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <!-- Theme Toggle -->
                  <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">System Theme</label>
                    <div class="flex items-center gap-4">
                      <button 
                        @click="theme = 'light'"
                        :class="['flex-1 p-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all', theme === 'light' ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 dark:border-gray-800 hover:border-gray-200']"
                      >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m3.343-5.657l-.707.707m12.728 12.728l-.707.707M6.343 17.657l-.707-.707M17.657 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span class="text-xs font-bold uppercase">Light</span>
                      </button>
                      <button 
                        @click="theme = 'dark'"
                        :class="['flex-1 p-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all', theme === 'dark' ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 dark:border-gray-800 hover:border-gray-200']"
                      >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <span class="text-xs font-bold uppercase">Dark</span>
                      </button>
                    </div>
                  </div>

                  <!-- Language -->
                  <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">System Language</label>
                    <select v-model="language" class="w-full p-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 focus:border-primary outline-none font-medium">
                      <option value="en">English (United States)</option>
                      <option value="ne">Nepali (Nepal)</option>
                    </select>
                    <p class="text-[10px] text-gray-400 italic">Interface translations will update immediately after saving.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Academic Group -->
            <div v-if="activeTab === 'academic'" class="space-y-8 animate-fadeIn">
              <div>
                <h3 class="text-xl font-bold dark:text-white mb-6 border-b dark:border-gray-800 pb-4">Academic Preferences</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Current Academic Year</label>
                    <select v-model="academicYear" class="w-full p-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 focus:border-primary outline-none font-medium">
                      <option>2080 BS</option>
                      <option>2081 BS</option>
                      <option>2023 AD</option>
                      <option>2024 AD</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">School Type</label>
                    <div class="flex gap-2">
                       <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg text-sm font-bold text-gray-600 dark:text-gray-400">Boarding</span>
                       <span class="px-4 py-2 bg-primary/10 rounded-lg text-sm font-bold text-primary">Day School</span>
                    </div>
                  </div>
                </div>

                <div class="mt-8">
                   <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Enabled Modules</label>
                   <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                      <div v-for="mod in ['Attendance', 'Examinations', 'Library', 'Transport', 'Hostel', 'Account']" :key="mod" class="flex items-center gap-3 p-3 border border-gray-100 dark:border-gray-800 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-primary">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ mod }}</span>
                      </div>
                   </div>
                </div>
              </div>
            </div>

            <!-- Security/System Group -->
            <div v-if="activeTab === 'security'" class="space-y-8 animate-fadeIn">
              <div>
                <h3 class="text-xl font-bold dark:text-white mb-6 border-b dark:border-gray-800 pb-4">Security & System</h3>
                
                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl">
                   <div class="flex items-center gap-4">
                      <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                      </div>
                      <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Maintenance Mode</h4>
                        <p class="text-xs text-gray-500">Temporarily disable portal access for all users except admins.</p>
                      </div>
                   </div>
                   <button class="px-6 py-2 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 transition-colors shadow-lg shadow-red-200 dark:shadow-none">Enable</button>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-4 mt-12 pt-8 border-t dark:border-gray-800">
              <button class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700">Discard Changes</button>
              <button @click="saveSettings" class="px-10 py-3 bg-primary text-white rounded-xl font-bold shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all">Save Preferences</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import MainLayout from '@/components/layout/MainLayout.vue'
import Swal from 'sweetalert2'

const activeTab = ref('appearance')
const theme = ref('light')
const language = ref('en')
const academicYear = ref('2081 BS')

const tabs = [
  { id: 'appearance', name: 'Appearance', icon: 'svg-appearance' },
  { id: 'academic', name: 'Academic', icon: 'svg-academic' },
  { id: 'security', name: 'Security', icon: 'svg-security' }
]

// Mock Icons for tabs (Real components would be imported)
const svgAppearance = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>`
}
const svgAcademic = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>`
}
const svgSecurity = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>`
}

const saveSettings = () => {
  Swal.fire({
    icon: 'success',
    title: 'Settings Saved',
    text: 'Your preferences have been updated successfully.',
    timer: 2000,
    showConfirmButton: false,
    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
    color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
  })
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
