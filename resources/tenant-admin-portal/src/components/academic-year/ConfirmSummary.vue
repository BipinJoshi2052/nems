<template>
  <div class="space-y-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirm & Create</h3>
      <p class="text-sm text-gray-500">Review the setup before finalizing the academic year.</p>
    </div>

    <div class="bg-gray-50 p-6 rounded-xl dark:bg-gray-800 border dark:border-gray-700 space-y-4">
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <span class="block text-gray-500">Year Name</span>
          <span class="font-bold">{{ data.year }}</span>
        </div>
        <div>
          <span class="block text-gray-500">Terms</span>
          <span class="font-bold">{{ data.terms }}</span>
        </div>
        <div>
          <span class="block text-gray-500">Start Date (AD)</span>
          <span class="font-bold">{{ data.startDate }}</span>
        </div>
        <div>
          <span class="block text-gray-500">End Date (AD)</span>
          <span class="font-bold">{{ data.endDate }}</span>
        </div>
      </div>

      <div class="border-t dark:border-gray-700 pt-4">
        <span class="block text-sm text-gray-500 mb-2">Classes</span>
        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
          <div v-for="(cls, idx) in data.classes" :key="idx" class="flex justify-between items-center text-xs p-2 bg-white dark:bg-gray-900 rounded border dark:border-gray-700">
            <span>{{ cls.name }}</span>
            <span v-if="cls.prev_id" class="text-[10px] text-gray-400">From previous year</span>
          </div>
        </div>
      </div>
    </div>

    <div v-if="error" class="p-3 bg-red-50 text-red-600 text-xs rounded-md border border-red-200">
      {{ error }}
    </div>

    <div class="pt-4 flex justify-between border-t dark:border-gray-700">
      <button @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
      <button @click="handleCreate" :disabled="loading" class="px-6 py-2 text-sm text-white bg-green-500 rounded-md hover:bg-green-600 font-bold shadow-md">
        <span v-if="loading">Creating...</span>
        <span v-else>Confirm & Create Year</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  data: { type: Object, required: true }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(false)
const error = ref('')

const getTeacherName = (id: number | null) => {
  // Ideally we should have passed the teacher name along, but let's assume we can fetch it or ignore for now
  return id ? `Teacher ID: ${id}` : 'Unassigned'
}

const handleCreate = async () => {
  loading.value = true
  error.value = ''
  try {
    const payload = {
      name: props.data.year,
      start_date: props.data.startDate,
      end_date: props.data.endDate,
      terms: props.data.terms,
      classes: props.data.classes.map((c: any) => ({
        name: c.name,
        prev_id: c.prev_id
      }))
    }
    await axios.post('/api/academic-years', payload)
    emit('next', { academicYearCreated: true })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create academic year'
  } finally {
    loading.value = false
  }
}
</script>
