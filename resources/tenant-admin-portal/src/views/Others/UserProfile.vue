<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" :items="breadcrumbItems" />

    <div v-if="loading" class="flex justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
    </div>
    
    <div v-else-if="userData"
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="flex items-center justify-between mb-5 lg:mb-7">
        <div class="flex items-center gap-4">
          <button 
            @click="goBack"
            class="flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white text-gray-400 hover:text-primary hover:border-primary transition-all dark:border-gray-800 dark:bg-white/[0.03]"
            title="Go Back"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
          </button>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ entityType }} Profile: {{ userData.name }}
          </h3>
        </div>
        <button 
          @click="triggerEdit"
          class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white hover:bg-primary/90 shadow-theme-xs transition-all"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          Edit
        </button>
      </div>
      <profile-card :user="userData" :type="type" @refresh="fetchUserData" />
      
      <!-- Tabs Navigation -->
      <div v-if="type === 'student'" class="flex border-b border-gray-200 dark:border-gray-800 mb-6 mt-8 overflow-x-auto no-scrollbar">
        <button 
          v-for="tab in studentTabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-6 py-3 font-bold text-sm transition-all border-b-2 whitespace-nowrap',
            activeTab === tab.id 
              ? 'border-primary text-primary bg-primary/5' 
              : 'border-transparent text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Tab Content -->
      <div v-if="type === 'student' && activeTab === 'profile'" class="space-y-6 animate-fadeIn">
        <academic-info-card :user="userData" :type="type" @refresh="fetchUserData" />
        <personal-info-card :user="userData" :type="type" @refresh="fetchUserData" />
      </div>

      <div v-else-if="type === 'student'" class="bg-white dark:bg-white/[0.03] rounded-2xl border border-gray-200 dark:border-gray-800 p-10 text-center animate-fadeIn">
        <div class="mb-4 flex justify-center">
          <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
        </div>
        <h4 class="text-xl font-bold text-gray-800 dark:text-white capitalize">{{ activeTab }} Content</h4>
        <p class="text-gray-500 mt-2">This section is currently under development. Stay tuned!</p>
      </div>

      <div v-if="type !== 'student'" class="space-y-6">
        <academic-info-card :user="userData" :type="type" @refresh="fetchUserData" />
        <children-links-card :user="userData" :type="type" />
        <personal-info-card :user="userData" :type="type" @refresh="fetchUserData" />
      </div>

      <!-- Centralized Edit Modal -->
      <ProfileEditModal 
        v-if="isEditModalOpen"
        :isOpen="isEditModalOpen"
        :user="userData"
        :type="type"
        mode="all"
        @close="isEditModalOpen = false"
        @refresh="fetchUserData"
      />
    </div>
    
    <div v-else class="text-center py-20 text-gray-500">
      User not found or error loading profile.
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import ProfileCard from '../../components/profile/ProfileCard.vue'
import AcademicInfoCard from '../../components/profile/AcademicInfoCard.vue'
import PersonalInfoCard from '../../components/profile/PersonalInfoCard.vue'
import ChildrenLinksCard from '../../components/profile/ChildrenLinksCard.vue'
import ProfileEditModal from '../../components/profile/ProfileEditModal.vue'

const route = useRoute()
const authStore = useAuthStore()
const userData = ref(null)
const loading = ref(true)
const isEditModalOpen = ref(false)
const activeTab = ref('profile')

const studentTabs = [
  { id: 'profile', label: 'Profile' },
  { id: 'attendance', label: 'Attendance' },
  { id: 'homework', label: 'Homework' },
  { id: 'fees', label: 'Fees' },
  { id: 'reviews', label: 'Reviews' }
]

const triggerEdit = () => {
  isEditModalOpen.value = true
}

const goBack = () => {
  const from = route.query.from
  if (from === 'parent' && route.query.parentId) {
    router.push({ 
      name: 'user-profile', 
      params: { type: 'parent', id: route.query.parentId } 
    })
  } else if (type.value === 'student') {
    router.push({ name: 'Students' })
  } else if (type.value === 'parent') {
    router.push({ name: 'parents' })
  } else {
    router.back()
  }
}

const type = computed(() => route.params.type || 'staff')
const id = computed(() => route.params.id || authStore.user?.id)

const entityType = computed(() => {
  const t = type.value
  return t.charAt(0).toUpperCase() + t.slice(1)
})

const currentPageTitle = computed(() => `${entityType.value} Profile`)

const breadcrumbItems = computed(() => {
  const from = route.query.from
  if (from === 'parent' && type.value === 'student' && route.query.parentId) {
    return [
      { label: 'Parents', route: { name: 'parents' } },
      { label: `${route.query.parentName} Profile`, route: { name: 'user-profile', params: { type: 'parent', id: route.query.parentId } } }
    ]
  }

  if (type.value === 'parent') {
    return [{ label: 'Parents', route: { name: 'parents' } }]
  } else if (type.value === 'student') {
    return [{ label: 'Students', route: { name: 'Students' } }]
  }
  return []
})

const fetchUserData = async () => {
  loading.value = true
  try {
    let endpoint = ''
    if (type.value === 'staff') endpoint = `/api/staff/${id.value}`
    else if (type.value === 'student') endpoint = `/api/students/${id.value}`
    else if (type.value === 'parent') endpoint = `/api/parents/${id.value}`
    
    if (endpoint) {
      const response = await axios.get(endpoint)
      userData.value = response.data
    }
  } catch (error) {
    console.error('Failed to fetch user data:', error)
  } finally {
    loading.value = false
  }
}

watch([() => route.params.type, () => route.params.id], fetchUserData)

onMounted(fetchUserData)
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
