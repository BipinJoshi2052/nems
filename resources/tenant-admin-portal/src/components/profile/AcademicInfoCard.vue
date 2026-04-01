<template>
  <div v-if="type === 'student' && user.enrollments?.[0]" class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
      Academic Details
    </h4>

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
  </div>
</template>

<script setup>
defineProps({
  user: { type: Object, required: true },
  type: { type: String, required: true }
})
</script>
