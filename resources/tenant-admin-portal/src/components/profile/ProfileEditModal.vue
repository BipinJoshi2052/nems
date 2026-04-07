<template>
  <Modal v-if="isOpen" @close="$emit('close')">
    <template #body>
      <div class="no-scrollbar relative w-full max-w-[800px] max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <!-- Close button -->
        <button
          @click="$emit('close')"
          class="transition-color absolute right-5 top-5 z-999 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
        >
          <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" fill="" />
          </svg>
        </button>

        <div class="mb-8">
          <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ mode === 'all' ? 'Edit Profile' : 
               mode === 'social' ? 'Edit Social Media' : 
               mode === 'academic' ? 'Edit Academic Info' : 
               'Edit Personal Info' }}
          </h4>
          <p class="text-sm text-gray-500 mt-1">Update details for <span class="text-primary font-medium">{{ user.name }}</span></p>
        </div>

        <form @submit.prevent="save" class="space-y-8">
          <!-- Personal Section -->
          <div v-if="mode === 'all' || mode === 'personal'" class="space-y-6">
            <div class="flex items-center gap-3">
              <div class="h-6 w-1 bg-primary rounded-full"></div>
              <h5 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white/70">Personal Details</h5>
            </div>
            
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div class="md:col-span-2">
                <label class="form-label">Full Name</label>
                <input v-model="form.name" type="text" class="form-input" placeholder="Full Name" />
              </div>

              <div>
                <label class="form-label">Email Address</label>
                <input v-model="form.email" type="email" class="form-input opacity-60 bg-gray-50 cursor-not-allowed" disabled />
              </div>

              <div>
                <label class="form-label">Phone Number</label>
                <input v-model="form.phone" type="text" class="form-input" placeholder="Phone Number" />
              </div>

              <div v-if="type === 'staff'">
                <label class="form-label">Designation</label>
                <input v-model="form.designation" type="text" class="form-input" placeholder="e.g. Senior Teacher" />
              </div>

              <div v-if="type === 'student'">
                <label class="form-label">Admission No</label>
                <input v-model="form.admission_no" type="text" class="form-input" placeholder="Admission Number" />
              </div>

              <div v-if="type === 'student'">
                <label class="form-label">Gender</label>
                <select v-model="form.gender" class="form-input">
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Academic Info Section (Student only) -->
          <div v-if="(mode === 'all' || mode === 'academic') && type === 'student'" class="space-y-6">
            <div class="flex items-center gap-3 border-t border-gray-100 dark:border-gray-800 pt-8">
              <div class="h-6 w-1 bg-primary rounded-full"></div>
              <h5 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white/70">Academic Information</h5>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="form-label">Roll Number</label>
                <input v-model="form.roll_no" type="text" class="form-input" placeholder="Roll Number" />
              </div>
              <div>
                <label class="form-label">Section</label>
                <select v-model="form.section_id" class="form-input">
                  <option value="">Select Section</option>
                  <option v-for="sec in sections" :key="sec.id" :value="sec.id">{{ sec.name }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Address Section -->
          <div v-if="mode === 'all' || mode === 'personal'" class="space-y-6">
            <div class="flex items-center gap-3 border-t border-gray-100 dark:border-gray-800 pt-8">
              <div class="h-6 w-1 bg-primary rounded-full"></div>
              <h5 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white/70">Address Information</h5>
            </div>

            <div class="grid grid-cols-1 gap-5">
              <div>
                <label class="form-label">Complete Address</label>
                <textarea v-model="form.address" rows="3" class="form-input" placeholder="Enter full address..."></textarea>
              </div>
            </div>
          </div>

          <!-- Social Links Section -->
          <div v-if="mode === 'all' || mode === 'social'" class="space-y-6">
            <div class="flex items-center gap-3 border-t border-gray-100 dark:border-gray-800 pt-8">
              <div class="h-6 w-1 bg-primary rounded-full"></div>
              <h5 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white/70">Social Media Profiles</h5>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label class="form-label">Facebook URL</label>
                <input v-model="form.facebook_url" type="url" class="form-input" placeholder="https://facebook.com/..." />
              </div>
              <div>
                <label class="form-label">X (Twitter) URL</label>
                <input v-model="form.x_url" type="url" class="form-input" placeholder="https://x.com/..." />
              </div>
              <div>
                <label class="form-label">LinkedIn URL</label>
                <input v-model="form.linkedin_url" type="url" class="form-input" placeholder="https://linkedin.com/in/..." />
              </div>
              <div>
                <label class="form-label">Instagram URL</label>
                <input v-model="form.instagram_url" type="url" class="form-input" placeholder="https://instagram.com/..." />
              </div>
            </div>
          </div>

          <!-- Footer Buttons -->
          <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100 dark:border-gray-800">
            <button @click="$emit('close')" type="button" class="btn-secondary">Cancel</button>
            <button type="submit" :disabled="saving" class="btn-primary">
              <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ saving ? 'Saving Changes...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import Modal from './Modal.vue'

const props = defineProps({
  isOpen: { type: Boolean, required: true },
  user: { type: Object, required: true },
  type: { type: String, required: true },
  mode: { type: String, default: 'all' } // 'all', 'personal', 'social', 'academic'
})

const emit = defineEmits(['close', 'refresh'])

const saving = ref(false)
const sections = ref([])

const form = reactive({
  name: '',
  email: '',
  phone: '',
  designation: '',
  admission_no: '',
  gender: '',
  roll_no: '',
  section_id: '',
  address: '',
  facebook_url: '',
  x_url: '',
  linkedin_url: '',
  instagram_url: '',
})

const fetchSections = async () => {
  try {
    const response = await axios.get('/api/sections')
    sections.value = response.data.data || response.data
  } catch (error) {
    console.error('Failed to fetch sections:', error)
  }
}

const initializeForm = () => {
  const u = props.user
  const profile = u.staff || u.student || u
  
  form.name = u.name || u.user?.name || ''
  form.email = u.email || u.user?.email || ''
  form.phone = u.phone || u.staff?.phone || u.user?.phone || ''
  form.designation = u.staff?.designation || u.designation || ''
  form.admission_no = u.admission_no || ''
  form.gender = u.gender || ''
  form.roll_no = u.roll_no || u.enrollments?.[0]?.roll_no || ''
  form.section_id = u.section_id || u.enrollments?.[0]?.section_id || ''
  form.address = profile.address || ''
  form.facebook_url = profile.facebook_url || ''
  form.x_url = profile.x_url || ''
  form.linkedin_url = profile.linkedin_url || ''
  form.instagram_url = profile.instagram_url || ''
}

onMounted(() => {
  initializeForm()
  if (props.type === 'student') {
    fetchSections()
  }
})

const save = async () => {
  saving.value = true
  try {
    let endpoint = ''
    if (props.type === 'staff') endpoint = `/api/staff/${props.user.id}`
    else if (props.type === 'student') endpoint = `/api/students/${props.user.id}`
    else if (props.type === 'parent') endpoint = `/api/parents/${props.user.id}`

    await axios.patch(endpoint, form)
    
    Swal.fire({
      icon: 'success',
      title: 'Profile Updated',
      text: 'Changes have been saved successfully.',
      timer: 2000,
      showConfirmButton: false,
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
    
    emit('refresh')
    emit('close')
  } catch (error) {
    console.error('Update failed:', error)
    Swal.fire({
      icon: 'error',
      title: 'Update Failed',
      text: error.response?.data?.message || 'Failed to save changes.',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
@reference "../../assets/main.css";

.form-label {
  @apply mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400;
}

.form-input {
  @apply h-11 w-full appearance-none rounded-xl border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 transition-all;
}

textarea.form-input {
  @apply h-auto py-3;
}

.btn-primary {
  @apply flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary/90 disabled:opacity-50 transition-all shadow-lg shadow-primary/20;
}

.btn-secondary {
  @apply flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-all;
}
</style>
