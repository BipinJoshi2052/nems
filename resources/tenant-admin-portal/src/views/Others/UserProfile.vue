<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div v-if="loading" class="flex justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
    </div>
    
    <div v-else-if="userData"
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
        {{ entityType }} Profile: {{ userData.name }}
      </h3>
      <profile-card :user="userData" :type="type" @refresh="fetchUserData" />
      <academic-info-card :user="userData" :type="type" />
      <personal-info-card :user="userData" :type="type" />
      <address-card :user="userData" :type="type" />
    </div>
    
    <div v-else class="text-center py-20 text-gray-500">
      User not found or error loading profile.
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import ProfileCard from '../../components/profile/ProfileCard.vue'
import AcademicInfoCard from '../../components/profile/AcademicInfoCard.vue'
import PersonalInfoCard from '../../components/profile/PersonalInfoCard.vue'
import AddressCard from '../../components/profile/AddressCard.vue'

const route = useRoute()
const authStore = useAuthStore()
const userData = ref(null)
const loading = ref(true)

const type = computed(() => route.params.type || 'staff')
const id = computed(() => route.params.id || authStore.user?.id)

const entityType = computed(() => {
  const t = type.value
  return t.charAt(0).toUpperCase() + t.slice(1)
})

const currentPageTitle = computed(() => `${entityType.value} Profile`)

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

onMounted(fetchUserData)
</script>
