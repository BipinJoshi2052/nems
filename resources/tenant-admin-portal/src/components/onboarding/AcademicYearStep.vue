<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Academic Year Setup</h2>
      <p class="text-sm text-gray-500">Configure your primary academic calendar and structure.</p>
    </div>

    <AcademicYearFlow @next="$emit('next', $event)" @back="$emit('back')" />
  </div>
</template>
<script setup lang="ts">
import AcademicYearFlow from '../academic-year/AcademicYearFlow.vue'
defineEmits(['next', 'back'])
</script>
