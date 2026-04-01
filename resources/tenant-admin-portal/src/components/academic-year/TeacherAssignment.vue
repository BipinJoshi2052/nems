<template>
  <div class="space-y-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teacher Assignment</h3>
      <p class="text-sm text-gray-500">Assign teachers to your classes.</p>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
    </div>

    <div v-else class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
      <div v-for="(cls, index) in data.classes" :key="index" class="p-4 bg-gray-50 rounded-lg dark:bg-gray-800 border dark:border-gray-700">
        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ cls.name }}</label>
        <div class="flex items-center space-x-3">
          <select 
            v-model="assignments[Number(index)].teacher_id" 
            class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
          >
            <option :value="null">Unassigned</option>
            <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
              {{ teacher.name }} {{ teacher.is_active ? '' : '(Inactive)' }}
            </option>
          </select>
          <span v-if="assignments[Number(index)].matchStatus === 'Confirmed'" class="text-xs text-green-500 font-medium">Matched</span>
          <span v-else-if="assignments[Number(index)].matchStatus === 'New'" class="text-xs text-blue-500 font-medium">New Class</span>
        </div>
      </div>
    </div>

    <div class="pt-4 flex justify-between border-t dark:border-gray-700">
      <button @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
      <button @click="handleNext" class="px-4 py-2 text-sm text-white bg-brand-500 rounded-md hover:bg-brand-600">
        Continue
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  data: { type: Object, required: true }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(true)
const teachers = ref<any[]>([])
const assignments = ref<any[]>([])

const fetchData = async () => {
  loading.value = true
  try {
    const [tRes, aRes] = await Promise.all([
      axios.get('/api/teachers'),
      axios.get('/api/academic-years/previous-assignments')
    ])
    teachers.value = tRes.data
    
    // Initialize assignments based on classes and previous year data
    assignments.value = props.data.classes.map((cls: any) => {
      const prev = aRes.data.find((a: any) => a.class_name === cls.name)
      return {
        class_name: cls.name,
        prev_id: cls.prev_id,
        teacher_id: prev?.teacher_id || null,
        matchStatus: prev ? 'Confirmed' : (cls.prev_id ? 'Unmatched' : 'New')
      }
    })
  } catch (err) {
    console.error('Failed to fetch teacher assignment data')
  } finally {
    loading.value = false
  }
}

const handleNext = () => {
  emit('next', { assignments: assignments.value })
}

onMounted(fetchData)
</script>
