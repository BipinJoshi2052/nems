<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Classes</h2>
      <p class="text-sm text-gray-500">Confirm the classes for this academic year. You can customize them now.</p>
    </div>

    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
      <div v-for="(cls, index) in classes" :key="index" class="flex gap-2">
        <input 
          v-model="cls.name" 
          type="text" 
          placeholder="e.g. Class 1-A"
          class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 font-medium"
        />
        <button @click="removeClass(index)" class="p-2 text-red-500 hover:bg-red-50 rounded" :disabled="classes.length === 1">
          <TrashIcon class="w-4 h-4" />
        </button>
      </div>

      <button @click="addClass" class="text-sm text-brand-500 font-semibold flex items-center hover:text-brand-600 transition-colors">
        <PlusIcon class="w-4 h-4 mr-1" /> Add Class
      </button>
    </div>

    <div v-if="error" class="p-3 bg-red-50 text-red-600 text-xs rounded-md border border-red-200">
      {{ error }}
    </div>

    <div class="pt-6 flex justify-between border-t dark:border-gray-700">
      <button @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
      <button 
        @click="handleSubmit" 
        :disabled="loading || !isValid" 
        class="px-6 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition-all disabled:opacity-50 shadow-brand-sm"
      >
        <span v-if="loading">Saving...</span>
        <span v-else>Continue</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { PlusIcon, TrashIcon } from '@/icons'

const props = defineProps({
  state: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(false)
const error = ref('')
const classes = ref<{name: string}[]>(props.state.classes?.length ? props.state.classes.map((c: any) => ({ name: typeof c === 'string' ? c : c.name })) : [{ name: '' }])

const isValid = computed(() => classes.value.some(c => c.name.trim()))

const addClass = () => {
  classes.value.push({ name: '' })
}

const removeClass = (index: number) => {
  if (classes.value.length > 1) {
    classes.value.splice(index, 1)
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    const names = classes.value.map(c => c.name.trim()).filter(Boolean)
    await axios.post('/api/setup/classes', { classes: names })
    emit('next', { classes: names })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to save classes'
  } finally {
    loading.value = false
  }
}
</script>
