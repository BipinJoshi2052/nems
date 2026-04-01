<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">School Information</h2>
      <p class="text-sm text-gray-500">Provide basic details about your school.</p>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Logo Upload -->
      <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl dark:border-gray-600">
        <div v-if="logoPreview" class="relative mb-4">
          <img :src="logoPreview" class="h-24 w-24 object-cover rounded-full shadow-md" />
          <button @click="logoPreview = null" class="absolute -top-1 -right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
            <XIcon class="w-3 h-3" />
          </button>
        </div>
        <div v-else class="text-center">
          <div class="mx-auto h-12 w-12 text-gray-400">
            <UploadIcon class="w-12 h-12" />
          </div>
          <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400">
            <label for="logo-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-600 hover:text-brand-500 focus-within:outline-none dark:bg-gray-800">
              <span>Upload school logo</span>
              <input id="logo-upload" name="logo-upload" type="file" class="sr-only" @change="handleLogoChange" accept="image/*" />
            </label>
            <p class="pl-1">or drag and drop</p>
          </div>
          <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- School Name -->
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            required 
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all shadow-sm"
            placeholder="e.g. Kathmandu Education Academy"
          />
        </div>

        <!-- Address -->
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
          <input 
            v-model="form.address" 
            type="text" 
            required 
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all shadow-sm"
            placeholder="e.g. Ward No. 5, Kathmandu"
          />
        </div>

        <!-- Contact Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Phone</label>
          <input 
            v-model="form.phone" 
            type="tel" 
            required 
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all shadow-sm"
            placeholder="98XXXXXXXX"
          />
        </div>

        <!-- Contact Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            required 
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all shadow-sm"
            placeholder="admin@school.edu.np"
          />
        </div>

        <!-- PAN Number -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PAN Number</label>
          <input 
            v-model="form.pan" 
            type="text" 
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all shadow-sm"
            placeholder="9 digits"
          />
        </div>

        <!-- VAT Toggle -->
        <div class="flex items-center space-x-3 pt-6">
          <button 
            type="button"
            @click="form.vat_enabled = !form.vat_enabled"
            :class="form.vat_enabled ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
          >
            <span :class="form.vat_enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
          </button>
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Enable VAT (13%)</span>
        </div>
      </div>

      <div class="pt-6 flex justify-end">
        <button 
          type="submit" 
          :disabled="loading"
          class="px-6 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 focus:ring-4 focus:ring-brand-200 transition-all disabled:opacity-50 shadow-brand-sm"
        >
          <span v-if="loading">Saving...</span>
          <span v-else>Next Step</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { UploadIcon, XIcon } from '@/icons'

const props = defineProps({
  state: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['next'])

const loading = ref(false)
const logoPreview = ref<string | null>(null)

const form = reactive({
  name: props.state.name || '',
  address: props.state.address || '',
  phone: props.state.phone || '',
  email: props.state.email || '',
  pan: props.state.pan || '',
  vat_enabled: props.state.vat_enabled || false,
  logo: null as File | null
})

const handleLogoChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (file) {
    form.logo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      logoPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const handleSubmit = async () => {
  loading.value = true
  try {
    const formData = new FormData()
    Object.entries(form).forEach(([key, value]) => {
      if (value !== null) formData.append(key, value as any)
    })

    await axios.post('/api/setup/school-info', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    
    emit('next', { ...form, logo: null }) // Don't pass the file object in the emitted state
  } catch (err) {
    console.error('Failed to save school info', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (props.state.logo_url) {
    logoPreview.value = props.state.logo_url
  }
})
</script>
