<template>
  <div class="space-y-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Class Setup</h3>
      <p class="text-sm text-gray-500">Initialize classes for the new year.</p>
    </div>

    <div v-if="false && previousYearClasses.length" class="bg-gray-50 p-4 rounded-lg dark:bg-gray-800 border dark:border-gray-700">
      <h4 class="text-sm font-bold mb-3">Copy from previous year?</h4>
      <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
        <label v-for="cls in previousYearClasses" :key="cls.id" class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-white dark:hover:bg-gray-700 rounded transition-colors">
          <input 
            type="checkbox" 
            :checked="form.selectedClasses.some((c: any) => c.id === cls.id)"
            @change="togglePreviousClass(cls)"
            class="rounded text-brand-500 focus:ring-brand-500"
          />
          <span class="text-sm">{{ cls.name }}</span>
        </label>
      </div>
    </div>

    <div class="space-y-4">
      <h4 class="text-sm font-bold">Manage New Classes</h4>
      <div v-for="(cls, index) in form.newClasses" :key="index" class="flex items-center space-x-2">
        <input 
          v-model="cls.name" 
          type="text" 
          placeholder="Class Name"
          class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
        />
        <button @click="removeNewClass(Number(index))" class="p-2 text-red-500 hover:text-red-700">
          <TrashIcon class="w-4 h-4" />
        </button>
      </div>
      <button @click="addNewClass" class="text-xs text-brand-500 font-semibold flex items-center hover:text-brand-600">
        <PlusIcon class="w-3 h-3 mr-1" /> Add Custom Class
      </button>
    </div>

    <div class="pt-4 flex justify-between border-t dark:border-gray-700">
      <button @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
      <button @click="handleNext" :disabled="!hasClasses" class="px-4 py-2 text-sm text-white bg-brand-500 rounded-md hover:bg-brand-600 disabled:opacity-50">
        Continue
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { PlusIcon, TrashIcon } from '@/icons'

const props = defineProps({
  data: { type: Object, required: true }
})

const emit = defineEmits(['next', 'back'])

const previousYearClasses = ref<any[]>([])
const form = reactive({
  selectedClasses: props.data.classes?.filter((c: any) => c.prev_id) || [] as any[],
  newClasses: props.data.classes?.filter((c: any) => !c.prev_id) || [] as any[]
})

const hasClasses = computed(() => form.selectedClasses.length + form.newClasses.length > 0)

const fetchPreviousYear = async () => {
  try {
    const { data } = await axios.get('/api/academic-years/previous-classes')
    previousYearClasses.value = data
  } catch (err) {
    console.error('Failed to fetch previous years classes')
  }
}

const togglePreviousClass = (cls: any) => {
  const index = form.selectedClasses.findIndex((c: any) => c.id === cls.id)
  if (index > -1) {
    form.selectedClasses.splice(index, 1)
  } else {
    form.selectedClasses.push({ id: Number(cls.id), name: cls.name, prev_id: Number(cls.id) })
  }
}

const addNewClass = () => {
  form.newClasses.push({ name: '' })
}

const removeNewClass = (index: number) => {
  form.newClasses.splice(index, 1)
}

const handleNext = () => {
  const allFormatted = [
    ...form.selectedClasses.map((c: any) => ({ name: c.name, prev_id: c.id })),
    ...form.newClasses.filter((c: any) => c.name.trim())
  ]
  emit('next', { classes: allFormatted })
}

onMounted(() => {
  // fetchPreviousYear() // Disabled for first-time onboarding
})
</script>
