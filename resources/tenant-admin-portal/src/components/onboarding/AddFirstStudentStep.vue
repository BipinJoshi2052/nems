<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add Your First Student</h2>
      <p class="text-sm text-gray-500">Optionally add one student to test the system. You can add the rest later.</p>
    </div>

    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student Full Name</label>
        <input 
          v-model="form.name" 
          type="text" 
          placeholder="e.g. Rahul Sharma"
          class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admission Number</label>
        <input 
          v-model="form.admission_no" 
          type="text" 
          placeholder="e.g. 2081/001"
          class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
        <select 
          v-model="form.class" 
          class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
        >
          <option value="">Select a class</option>
          <option v-for="c in availableClasses" :key="c" :value="c">{{ c }}</option>
        </select>
      </div>

      <div v-if="error" class="sm:col-span-2 p-3 bg-red-50 text-red-600 text-xs rounded-md border border-red-200">
        {{ error }}
      </div>

      <div class="sm:col-span-2 pt-6 flex justify-between border-t dark:border-gray-700">
        <button type="button" @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
        <div class="flex gap-3">
          <button type="button" @click="handleSkip" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 font-medium">Skip for now</button>
          <button 
            type="submit" 
            :disabled="loading || !form.name || !form.class" 
            class="px-6 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 disabled:opacity-50 shadow-brand-sm"
          >
            <span v-if="loading">Saving...</span>
            <span v-else>Finish Setup</span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
  state: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(false)
const error = ref('')
const availableClasses = ref<string[]>(props.state.classes || [])

const form = reactive({
  name: '',
  admission_no: '',
  class: ''
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await axios.post('/api/setup/first-student', form)
    emit('next', { student: form })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to save student'
  } finally {
    loading.value = false
  }
}

const handleSkip = () => {
  emit('next', { student: null })
}
</script>
