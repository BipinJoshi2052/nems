<template>
  <component :is="activeLayout">
    <slot></slot>
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import AdminLayout from './AdminLayout.vue'
import TeacherLayout from './TeacherLayout.vue'
import AccountantLayout from './AccountantLayout.vue'
import ReceptionistLayout from './ReceptionistLayout.vue'
import StaffLayout from './StaffLayout.vue'
import ParentLayout from './ParentLayout.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const activeLayout = computed(() => {
  const role = authStore.user?.role
  switch (role) {
    case 'admin': return AdminLayout
    case 'teacher': return TeacherLayout
    case 'accountant': return AccountantLayout
    case 'receptionist': return ReceptionistLayout
    case 'parent': return ParentLayout
    default: return StaffLayout
  }
})
</script>
