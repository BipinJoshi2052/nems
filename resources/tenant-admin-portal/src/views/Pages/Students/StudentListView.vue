<template>
  <MainLayout>
    <div class="p-4 md:p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Student Directory</h2>
      <button 
        @click="showAddModal = true"
        class="flex items-center gap-2 px-4 py-2 text-white transition bg-primary rounded-lg hover:bg-opacity-90 font-semibold"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        New Admission
      </button>
    </div>

    <!-- Filters & Search -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="md:col-span-2">
        <input 
          v-model="filters.q" 
          type="text" 
          placeholder="Search students by name or admission no..." 
          class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
        />
      </div>
      <select 
        v-model="filters.class_id"
        class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
      >
        <option value="">All Classes</option>
        <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
      </select>
      <select 
        v-model="filters.section_id"
        class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
      >
        <option value="">All Sections</option>
        <option v-for="sec in sections" :key="sec.id" :value="sec.id">Section {{ sec.name }}</option>
      </select>
      <select 
        v-model="filters.status"
        class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
      >
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="withdrawn">Withdrawn</option>
        <option value="graduated">Graduated</option>
      </select>
    </div>

    <!-- Students Table -->
    <div class="bg-white dark:bg-boxdark rounded-lg shadow-sm overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-meta-4">
          <tr>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Student Info</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Class & Roll</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Parent Contact</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-strokedark">
          <tr v-if="loading && students.length === 0">
            <td colspan="5" class="px-6 py-10 text-center">
              <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm text-gray-500">Loading students...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="!loading && students.length === 0">
            <td colspan="5" class="px-6 py-10 text-center text-gray-500">No students found.</td>
          </tr>
          <tr v-else v-for="student in students" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-meta-4 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <img v-if="student.thumbnail" :src="student.thumbnail.url" class="w-10 h-10 rounded-full object-cover border border-gray-200" />
                <div v-else class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-primary font-bold">
                  {{ student.name.charAt(0) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900 dark:text-white">{{ student.name }}</div>
                  <div class="text-xs text-gray-500">ID: {{ student.admission_no }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm font-medium">Class {{ student.current_class_name || student.enrollments?.[0]?.class?.name || 'N/A' }}</div>
              <div class="text-xs text-gray-500">Sec: {{ student.section?.name || student.enrollments?.[0]?.section?.name || 'N/A' }}</div>
              <div class="text-xs text-gray-400">Roll: {{ student.roll_no || student.enrollments?.[0]?.roll_no || 'N/A' }}</div>
            </td>
            <td class="px-6 py-4">
              <span 
                @click="updateStatus(student)"
                :class="{
                  'bg-green-100 text-green-600 cursor-pointer': student.status === 'active',
                  'bg-red-100 text-red-600 cursor-pointer': student.status === 'withdrawn',
                  'bg-blue-100 text-blue-600 cursor-pointer': student.status === 'graduated'
                }"
                class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
              >
                {{ student.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div v-if="student.primary_parent" class="text-sm">
                <div>{{ student.primary_parent.name }}</div>
                <div class="text-xs text-gray-500">{{ student.primary_parent.phone }}</div>
              </div>
              <span v-else class="text-xs text-red-500 italic">No Parent Linked</span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <button @click="viewStudent(student)" class="text-gray-500 hover:text-primary transition-colors" title="View Profile">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
                <button @click="editStudent(student)" class="text-gray-500 hover:text-yellow-500 transition-colors" title="Edit Student">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="confirmDelete(student)" class="text-gray-500 hover:text-red-500 transition-colors" title="Delete Student">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <Pagination :meta="pagination" @change="fetchStudents" />
    </div>

    <!-- Enrollment Wizard Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
      <div class="bg-white dark:bg-boxdark w-full max-w-4xl p-6 rounded-lg shadow-xl overflow-y-auto max-h-[90vh] text-gray-900 dark:text-white">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold dark:text-white">Enroll New Student</h3>
          <button @click="showAddModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <form @submit.prevent="submitAdmission" class="grid grid-cols-1 md:grid-cols-12 gap-8">
          <!-- Left Column: Photo Upload -->
          <div class="md:col-span-4 flex flex-col items-center pb-6 md:pb-0 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700">
            <label class="block text-sm font-medium mb-4 text-gray-700 dark:text-gray-300 w-full text-center">Student Photo</label>
            <div 
              @click="photoInput?.click()"
              class="w-52 h-52 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-all overflow-hidden group relative bg-gray-50 dark:bg-gray-800/50"
            >
              <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover shadow-inner" />
              <div v-else class="flex flex-col items-center text-gray-400 group-hover:text-primary transition-colors">
                <svg class="w-14 h-14 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-semibold tracking-wider uppercase">Click to Upload</span>
              </div>
              <div v-if="photoPreview" class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                <svg class="w-8 h-8 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                </svg>
                <span class="text-white text-[10px] font-bold uppercase tracking-tighter">Change Photo</span>
              </div>
            </div>
            <input 
              type="file" 
              ref="photoInput" 
              class="hidden" 
              accept="image/*" 
              @change="onFileSelected"
            />
            <div class="mt-6 space-y-2 px-4">
              <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <div class="w-1 h-1 rounded-full bg-primary"></div>
                <span>Square JPG/PNG preferred</span>
              </div>
              <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <div class="w-1 h-1 rounded-full bg-primary"></div>
                <span>Maximum size: 2MB</span>
              </div>
            </div>
          </div>

          <!-- Right Column: Form Inputs -->
          <div class="md:col-span-8 flex flex-col justify-between">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Student Full Name</label>
                <input v-model="form.name" type="text" placeholder="Enter student's full name" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600" required />
              </div>
              <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Admission No.</label>
                <input v-model="form.admission_no" type="text" placeholder="Unique ID" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600" required />
              </div>
              
              <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Assign Class</label>
                <select v-model="form.class_id" @change="handleClassChange" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all cursor-pointer" required>
                  <option value="">Select Class</option>
                  <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
                </select>
              </div>
              <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Assign Section</label>
                <select v-model="form.section_id" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all cursor-pointer" required>
                  <option value="">Select Section</option>
                  <option v-for="sec in sections" :key="sec.id" :value="sec.id">{{ sec.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Gender</label>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" v-model="form.gender" value="male" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">Male</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" v-model="form.gender" value="female" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary" />
                    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">Female</span>
                  </label>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Roll Number</label>
                <input v-model="form.roll_no" type="text" placeholder="Roll #" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" />
              </div>

              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Date of Birth (AD)</label>
                <input v-model="form.date_of_birth_ad" type="date" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" required />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Date of Birth (BS)</label>
                <input v-model="form.date_of_birth_bs" type="text" placeholder="YYYY-MM-DD" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" />
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
              <button type="button" @click="showAddModal = false" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Cancel</button>
              <button 
                type="submit" 
                :disabled="loading" 
                class="px-8 py-2.5 bg-primary text-white rounded-xl hover:bg-opacity-90 disabled:opacity-50 font-bold shadow-xl shadow-primary/30 transition-all flex items-center gap-2"
              >
                <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ loading ? 'Enrolling...' : 'Complete Admission' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Status Modal -->
    <div v-if="showStatusModal" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
         <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
           <h3 class="text-lg font-bold dark:text-white">Update Student Status</h3>
           <button @click="showStatusModal = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
         </div>
         <form @submit.prevent="submitStatusUpdate" class="p-4 space-y-4">
           <div>
             <label class="block text-sm font-medium mb-1 dark:text-white">New Status</label>
             <select v-model="statusForm.status" class="w-full px-4 py-2 border rounded dark:bg-form-input text-black dark:text-white">
               <option value="active">Active</option>
               <option value="withdrawn">Withdrawn</option>
               <option value="graduated">Graduated</option>
             </select>
           </div>
           <div v-if="statusForm.status !== 'active'">
             <label class="block text-sm font-medium mb-1 dark:text-white">Reason</label>
             <textarea v-model="statusForm.reason" class="w-full px-4 py-2 border rounded dark:bg-form-input text-black dark:text-white" required></textarea>
           </div>
           <div class="flex justify-end gap-2 mt-6">
             <button type="button" @click="showStatusModal = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
             <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50 font-semibold">
               Update Status
             </button>
           </div>
         </form>
      </div>
    </div>
  </div>
</MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import _ from 'lodash'
import MainLayout from '@/components/layout/MainLayout.vue'
import Pagination from '@/components/common/Pagination.vue'

const router = useRouter()

interface Student {
  id: string
  name: string
  admission_no: string
  current_class: string
  roll_no?: string
  status: string
  gender: string
  date_of_birth_ad: string
  primary_parent?: {
    name: string
    phone: string
  }
}

interface ClassItem {
  id: string
  name: string
}

const students = ref<any[]>([])
const classes = ref<any[]>([])
const sections = ref<any[]>([])
const loading = ref(true)
const showAddModal = ref(false)
const showStatusModal = ref(false)
const selectedStudent = ref<Student | null>(null)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 1,
  to: 1,
  total: 0
})

const filters = ref({
  q: '',
  class_id: '',
  section_id: '',
  status: 'active',
  page: 1
})

const initialFormStatus = {
  status: 'active',
  reason: ''
}

const initialForm = {
  name: '',
  admission_no: '',
  class_id: '',
  section_id: '',
  roll_no: '',
  gender: 'male',
  date_of_birth_ad: '',
  date_of_birth_bs: '',
  photo: null,
}

const photoPreview = ref(null)
const photoInput = ref<HTMLInputElement | null>(null)

const onFileSelected = (event: any) => {
  const file = event.target.files[0]
  if (file) {
    form.value.photo = file
    const reader = new FileReader()
    reader.onload = (e: any) => photoPreview.value = e.target.result
    reader.readAsDataURL(file)
  }
}

const form = ref({ ...initialForm })

const statusForm = ref({ ...initialFormStatus })

const fetchStudents = async (page = 1) => {
  loading.value = true
  filters.value.page = page
  try {
    const response = await axios.get('/api/students', { params: filters.value })
    students.value = response.data.data
    pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        from: response.data.from,
        to: response.data.to,
        total: response.data.total
    }
  } catch (error: any) {
    console.error('Failed to fetch students:', error)
    Swal.fire({
      title: 'Error!',
      text: 'Failed to load student list. Please try again.',
      icon: 'error',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    loading.value = false
  }
}

const fetchClasses = async () => {
  try {
    const response = await axios.get('/api/classes', { params: { per_page: -1 } })
    classes.value = Array.isArray(response.data) ? response.data : (response.data.data || [])
  } catch {}
}

const fetchSections = async () => {
  try {
    const response = await axios.get('/api/sections', { params: { per_page: -1 } })
    sections.value = Array.isArray(response.data) ? response.data : (response.data.data || [])
  } catch {}
}

const handleClassChange = () => {
  form.value.section_id = ''
}

watch(() => filters.value.class_id, (newVal) => {
  filters.value.section_id = ''
})

const activeYearId = ref('')

const fetchActiveYear = async () => {
    try {
        const res = await axios.get('/api/academic-years')
        const active = res.data.data.find((y: any) => y.is_active)
        if (active) activeYearId.value = active.id
    } catch {}
}

const submitAdmission = async () => {
  loading.value = true
  try {
    const formData = new FormData()
    formData.append('academic_year_id', activeYearId.value)
    
    Object.keys(form.value).forEach(key => {
      const val = (form.value as any)[key]
      if (val !== null && val !== undefined) {
        formData.append(key, val)
      }
    })

    await axios.post('/api/students', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    
    showAddModal.value = false
    form.value = { ...initialForm }
    photoPreview.value = null
    fetchStudents()
  } catch (error: any) {
    Swal.fire({
      title: 'Failed to Enroll',
      text: error.response?.data?.message || 'Please check all fields and try again.',
      icon: 'error',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    loading.value = false
  }
}

const viewStudent = (student: Student) => {
  router.push({ name: 'user-profile', params: { type: 'student', id: student.id } })
}

const confirmDelete = async (student: Student) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `You are about to delete ${student.name}. This will also remove their enrollment history.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#71717a',
    confirmButtonText: 'Yes, delete it!',
    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
    color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
  })

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/students/${student.id}`)
      fetchStudents(pagination.value.current_page)
      Swal.fire({
        title: 'Deleted!',
        text: 'The student has been removed.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    } catch (error: any) {
      Swal.fire({
        title: 'Error!',
        text: 'Failed to delete student.',
        icon: 'error',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    }
  }
}

const updateStatus = (student: Student) => {
  selectedStudent.value = student
  statusForm.value = { status: student.status, reason: '' }
  showStatusModal.value = true
}

const submitStatusUpdate = async () => {
    if (!selectedStudent.value) return
    loading.value = true
    try {
        await axios.patch(`/api/students/${selectedStudent.value.id}/status`, statusForm.value)
        showStatusModal.value = false
        fetchStudents(pagination.value.current_page)
    } catch (error: any) {
        Swal.fire({
          title: 'Update Failed',
          text: error.response?.data?.message || 'Failed to update student status.',
          icon: 'error',
          background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
          color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    } finally {
        loading.value = false
    }
}

const debouncedFetchStudents = _.debounce(() => fetchStudents(1), 500)
watch(filters, () => debouncedFetchStudents(), { deep: true })

onMounted(async () => {
  await fetchActiveYear()
  await fetchClasses()
  await fetchSections()
  await fetchStudents()
})

const viewProfile = (student: Student) => console.log('View profile:', student)
const editStudent = (student: Student) => console.log('Edit student:', student)
</script>
