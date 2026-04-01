<template>
  <div class="space-y-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Academic Year Details</h3>
      <p class="text-sm text-gray-500">Define the basic timeframe and structure.</p>
    </div>

    <form @submit.prevent="handleValidate" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year Name</label>
        <input 
          v-model="form.year" 
          type="text" 
          required 
          placeholder="e.g. 2081-2082"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date (AD)</label>
        <input 
          v-model="form.startDate" 
          type="date" 
          required 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date (AD)</label>
        <input 
          v-model="form.endDate" 
          type="date" 
          required 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white"
        />
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Terms</label>
        <select 
          v-model="form.terms" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white"
        >
          <option :value="1">1 Term (Annual)</option>
          <option :value="2">2 Terms (Semester)</option>
          <option :value="3">3 Terms (Terminal)</option>
        </select>
      </div>

      <div class="sm:col-span-2 pt-4 flex justify-between">
        <button type="button" @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
        <button type="submit" :disabled="loading" class="px-4 py-2 text-sm text-white bg-brand-500 rounded-md hover:bg-brand-600">
          <span v-if="loading">Validating...</span>
          <span v-else>Continue</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  data: { type: Object, required: true }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(false)
const form = reactive({
  year: props.data.year || '',
  startDate: props.data.startDate || '',
  endDate: props.data.endDate || '',
  terms: props.data.terms || 3
})

const handleValidate = async () => {
  loading.value = true
  try {
    await axios.post('/api/academic-years/validate', form)
    emit('next', form)
  } catch (err: any) {
    alert(err.response?.data?.message || 'Validation failed')
  } finally {
    loading.value = false
  }
}
</script>
