<template>
  <div v-if="type === 'student' && user.enrollments?.[0]" class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
    <div class="flex items-center justify-between mb-6">
      <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Academic Details
      </h4>
      <button 
        @click="isEditModalOpen = true"
        class="edit-button"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        Edit
      </button>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
      <div>
        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Current Class</p>
        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ user.enrollments[0].class?.name || 'N/A' }}</p>
      </div>

      <div>
        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Section</p>
        <p class="text-sm font-medium text-gray-800 dark:text-white/90 font-bold text-primary">{{ user.section?.name || user.enrollments[0].section?.name || 'N/A' }}</p>
      </div>

      <div>
        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Roll Number</p>
        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ user.roll_no || user.enrollments[0].roll_no || 'N/A' }}</p>
      </div>

      <div>
        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Academic Year</p>
        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ user.enrollments[0].academic_year?.name || 'N/A' }}</p>
      </div>
    </div>

    <!-- Edit Modal -->
    <ProfileEditModal 
      v-if="isEditModalOpen"
      :isOpen="isEditModalOpen"
      :user="user"
      :type="type"
      mode="academic"
      @close="isEditModalOpen = false"
      @refresh="$emit('refresh')"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProfileEditModal from './ProfileEditModal.vue'

const props = defineProps({
  user: { type: Object, required: true },
  type: { type: String, required: true }
})

defineEmits(['refresh'])

const isEditModalOpen = ref(false)
</script>
