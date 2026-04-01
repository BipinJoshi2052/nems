<template>
  <MainLayout>
    <div class="p-4 md:p-6">
      <PageBreadcrumb pageTitle="Parent Management" />
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div></div> <!-- Empty div to push button to the right since breadcrumb is now above -->
        <button 
          @click="showAddModal = true"
          class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>Add Parent</span>
        </button>
      </div>

      <!-- Filters & Search -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-2">
          <input 
            v-model="filters.q" 
            @input="debouncedFetchParents"
            type="text" 
            placeholder="Search parents by name, email or phone..." 
            class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
          />
        </div>
      </div>

      <!-- Parents Table -->
      <div class="bg-white dark:bg-boxdark rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-meta-4">
          <tr>
            <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Parent</th>
            <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Contact</th>
            <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Students</th>
            <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-strokedark">
          <tr v-if="loading && parents.length === 0">
            <td colspan="4" class="px-6 py-10 text-center">
              <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm text-gray-500">Loading parents...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="!loading && parents.length === 0">
            <td colspan="4" class="px-6 py-10 text-center text-gray-500">No parents found.</td>
          </tr>
          <tr v-else v-for="parent in parents" :key="parent.id" class="hover:bg-gray-50 dark:hover:bg-meta-4 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <img v-if="parent.thumbnail" :src="parent.thumbnail.url" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                <div v-else class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-primary font-bold shadow-sm">
                  {{ parent.name.charAt(0) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900 dark:text-white">{{ parent.name }}</div>
                  <div class="text-xs text-gray-500 italic">{{ parent.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ parent.phone || 'N/A' }}</div>
              <div class="text-[10px] text-gray-400 uppercase tracking-tighter">Phone Number</div>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-1">
                <span v-for="student in parent.students" :key="student.id" class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-[10px] font-bold rounded-full text-gray-600 dark:text-gray-300">
                  {{ student.name }}
                </span>
                <span v-if="!parent.students?.length" class="text-xs text-gray-400">None</span>
              </div>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <button @click="viewParent(parent)" class="text-gray-500 hover:text-primary transition-colors" title="View Profile">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
                <button @click="editParent(parent)" class="text-gray-500 hover:text-yellow-500 transition-colors" title="Edit Parent">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="confirmDelete(parent)" class="text-gray-500 hover:text-red-500 transition-colors" title="Delete Parent">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      </div>

      <Pagination :meta="pagination" @change="fetchParents" />
    </div>

    <!-- Parent Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
      <div class="bg-white dark:bg-boxdark w-full max-w-4xl p-6 rounded-lg shadow-xl text-gray-900 dark:text-white overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold dark:text-white">{{ showEditModal ? 'Edit Parent' : 'Add New Parent' }}</h3>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-12 gap-8">
          <!-- Left Column: Photo Upload -->
          <div class="md:col-span-4 flex flex-col items-center pb-6 md:pb-0 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700">
            <label class="block text-sm font-medium mb-4 text-gray-700 dark:text-gray-300 w-full text-center">Parent Photo</label>
            <div 
              @click="photoInput?.click()"
              class="w-52 h-52 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-all overflow-hidden group relative bg-gray-50 dark:bg-gray-800/50"
            >
              <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover shadow-inner" />
              <div v-else class="flex flex-col items-center text-gray-400 group-hover:text-primary transition-colors text-center p-4">
                <svg class="w-14 h-14 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-bold tracking-wider uppercase">Upload Photo</span>
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
          </div>

          <!-- Right Column: Form Inputs -->
          <div class="md:col-span-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Name</label>
                <input v-model="form.name" type="text" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="Enter Full Name" />
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Email</label>
                <input v-model="form.email" type="email" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="email@example.com" />
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Phone</label>
                <input v-model="form.phone" type="text" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="Phone number" />
              </div>
              
              <!-- Student Multi-select -->
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Link Students</label>
                <div class="relative">
                  <input 
                    v-model="studentSearch" 
                    @input="searchStudents"
                    type="text" 
                    placeholder="Search students by name..." 
                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all"
                  />
                  <div v-if="searchingStudents" class="absolute right-3 top-2.5">
                    <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                  </div>
                  <!-- Search Results Dropdown -->
                  <div v-if="searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                    <div 
                      v-for="student in searchResults" 
                      :key="student.id"
                      @click="addStudent(student)"
                      class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer flex items-center justify-between border-b last:border-0 border-gray-100 dark:border-gray-700"
                    >
                      <div class="flex items-center gap-3">
                        <img v-if="student.thumbnail" :src="student.thumbnail.url" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                        <div v-else class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-primary font-bold shadow-sm">
                          {{ student.name.charAt(0) }}
                        </div>
                        <div>
                          <div class="text-sm font-bold dark:text-gray-200">{{ student.name }}</div>
                          <div class="text-[10px] text-gray-500">
                            {{ student.enrollments?.[0]?.class?.name || 'N/A' }} - {{ student.enrollments?.[0]?.section?.name || 'N/A' }}
                          </div>
                        </div>
                      </div>
                      <span class="text-[10px] bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded font-bold text-gray-500 uppercase tracking-tighter">{{ student.admission_no }}</span>
                    </div>
                  </div>
                </div>
                
                <!-- Selected Students List -->
                <div class="mt-4 space-y-3">
                  <div 
                    v-for="student in selectedStudents" 
                    :key="student.id"
                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl group"
                  >
                    <div class="flex items-center gap-3">
                      <img v-if="student.thumbnail" :src="student.thumbnail" class="w-12 h-12 rounded-lg object-cover border border-white shadow-sm" />
                      <div v-else class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold">
                        {{ student.name.charAt(0) }}
                      </div>
                      <div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ student.name }}</div>
                        <div class="text-[10px] text-gray-500 font-medium">
                          {{ student.class || 'N/A' }} • {{ student.section || 'N/A' }}
                        </div>
                      </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                      <div class="flex flex-col">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Relationship</label>
                        <select 
                          v-model="student.relationship"
                          @change="updateFormStudents"
                          class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 text-xs outline-none focus:border-primary"
                        >
                          <option value="father">Father</option>
                          <option value="mother">Mother</option>
                          <option value="brother">Brother</option>
                          <option value="sister">Sister</option>
                          <option value="guardian">Guardian</option>
                        </select>
                      </div>
                      
                      <button @click="removeStudent(student.id)" type="button" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
              <button @click="closeModal" type="button" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
              <button 
                type="submit" 
                :disabled="loading" 
                class="px-8 py-2.5 bg-primary text-white rounded-xl hover:bg-opacity-90 disabled:opacity-50 font-bold shadow-lg shadow-primary/20 transition-all"
              >
                {{ loading ? 'Saving...' : (showEditModal ? 'Update Parent' : 'Add Parent') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div> <!-- closing p-4 md:p-6 -->
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import _ from 'lodash'
import MainLayout from '@/components/layout/MainLayout.vue'
import Pagination from '@/components/common/Pagination.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

const router = useRouter()

interface Parent {
  id: string
  name: string
  email: string
  phone?: string
  thumbnail?: { url: string }
  students?: { 
    id: string, 
    name: string,
    thumbnail?: { url: string },
    enrollments?: { class: { name: string }, section: { name: string } }[],
    pivot?: { relationship: string }
  }[]
}

const parents = ref<Parent[]>([])
const loading = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const selectedParentId = ref<string | null>(null)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 1,
  to: 1,
  total: 0
})

const filters = reactive({
  q: '',
  page: 1
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  photo: null as File | null,
  students: [] as { id: string, relationship: string }[]
})

const photoPreview = ref<string | null>(null)
const photoInput = ref<HTMLInputElement | null>(null)
const studentSearch = ref('')
const searchingStudents = ref(false)
const searchResults = ref<{ id: string, name: string, admission_no: string, thumbnail?: { url: string }, enrollments?: any[] }[]>([])
const selectedStudents = ref<{ id: string, name: string, relationship: string, class: string, section: string, thumbnail?: string }[]>([])

const onFileSelected = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    form.photo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const searchStudents = _.debounce(async () => {
  if (!studentSearch.value) {
    searchResults.value = []
    searchingStudents.value = false
    return
  }
  searchingStudents.value = true
  try {
    const response = await axios.get('/api/students', { params: { q: studentSearch.value } })
    searchResults.value = response.data.data
  } catch (error) {
    console.error('Search failed:', error)
  } finally {
    searchingStudents.value = false
  }
}, 2000)

const addStudent = (student: any) => {
  if (selectedStudents.value.find(s => s.id === student.id)) {
    Swal.fire({
      icon: 'warning',
      title: 'Already Added',
      text: `${student.name} is already in the list.`,
      timer: 2000,
      showConfirmButton: false,
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
    return
  }

  selectedStudents.value.push({ 
    id: student.id, 
    name: student.name,
    relationship: 'father', // default
    class: student.enrollments?.[0]?.class?.name || 'N/A',
    section: student.enrollments?.[0]?.section?.name || 'N/A',
    thumbnail: student.thumbnail?.url
  })
  updateFormStudents()
  studentSearch.value = ''
  searchResults.value = []
}

const updateFormStudents = () => {
  form.students = selectedStudents.value.map(s => ({
    id: s.id,
    relationship: s.relationship
  }))
}

const removeStudent = (id: string) => {
  selectedStudents.value = selectedStudents.value.filter(s => s.id !== id)
  updateFormStudents()
}

const fetchParents = async (page = 1) => {
  loading.value = true
  filters.page = page
  try {
    const response = await axios.get('/api/parents', { params: filters })
    parents.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      from: response.data.from,
      to: response.data.to,
      total: response.data.total
    }
  } catch (error) {
    console.error('Failed to fetch parents:', error)
  } finally {
    loading.value = false
  }
}

const debouncedFetchParents = _.debounce(() => fetchParents(1), 500)

const viewParent = (parent: Parent) => {
  router.push({ name: 'user-profile', params: { type: 'parent', id: parent.id } })
}

const confirmDelete = async (parent: Parent) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `You are about to delete ${parent.name}.`,
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
      await axios.delete(`/api/parents/${parent.id}`)
      fetchParents(pagination.value.current_page)
      Swal.fire({
        title: 'Deleted!',
        text: 'The parent record has been removed.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    } catch (error) {
      Swal.fire({
        title: 'Error!',
        text: 'Failed to delete parent record.',
        icon: 'error',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    }
  }
}

const editParent = (parent: Parent) => {
  selectedParentId.value = parent.id
  form.name = parent.name
  form.email = parent.email
  form.phone = parent.phone || ''
  if (parent.thumbnail) {
    photoPreview.value = parent.thumbnail.url
  }
  if (parent.students) {
    selectedStudents.value = parent.students.map(s => ({
      id: s.id,
      name: s.name,
      relationship: s.pivot?.relationship || 'father',
      class: s.enrollments?.[0]?.class?.name || 'N/A',
      section: s.enrollments?.[0]?.section?.name || 'N/A',
      thumbnail: s.thumbnail?.url
    }))
    updateFormStudents()
  }
  showEditModal.value = true
}

const submitForm = async () => {
  loading.value = true
  try {
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('email', form.email)
    formData.append('phone', form.phone)
    if (form.photo) {
      formData.append('photo', form.photo)
    }
    form.students.forEach((student, index) => {
      formData.append(`students[${index}][id]`, student.id)
      formData.append(`students[${index}][relationship]`, student.relationship)
    })

    if (showEditModal.value && selectedParentId.value) {
      // Use POST with _method=PATCH for FormData
      formData.append('_method', 'PATCH')
      await axios.post(`/api/parents/${selectedParentId.value}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    } else {
      await axios.post('/api/parents', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }
    closeModal()
    fetchParents(showEditModal.value ? pagination.value.current_page : 1)
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: `Parent ${showEditModal.value ? 'updated' : 'added'} successfully`,
      timer: 2000,
      showConfirmButton: false,
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } catch (error: any) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response?.data?.message || 'Failed to save parent',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  selectedParentId.value = null
  form.name = ''
  form.email = ''
  form.phone = ''
  form.photo = null
  form.students = []
  photoPreview.value = null
  selectedStudents.value = []
  studentSearch.value = ''
}

onMounted(() => {
  fetchParents()
})
</script>
