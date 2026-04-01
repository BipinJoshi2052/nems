<template>
  <AdminLayout>
    <div class="p-4 md:p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Staff Management</h2>
      <button 
        @click="showInviteModal = true"
        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span>Add Staff</span>
      </button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="md:col-span-2">
        <input 
          v-model="filters.q" 
          type="text" 
          placeholder="Search staff by name, email or designation..." 
          class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
        />
      </div>
      <select 
        v-model="filters.role"
        class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
      >
        <option value="">All Roles</option>
        <option value="Teacher">Teacher</option>
        <option value="Admin">Admin</option>
        <option value="Staff">Staff</option>
      </select>
      <select 
        v-model="filters.status"
        class="w-full px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark focus:outline-none focus:border-primary"
      >
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="deactivated">Deactivated</option>
      </select>
    </div>

    <!-- Staff Table -->
    <div class="bg-white dark:bg-boxdark rounded-lg shadow-sm overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-meta-4">
          <tr>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Staff Member</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Role / Designation</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Last Login</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-strokedark">
          <tr v-if="loading && staffList.length === 0">
            <td colspan="5" class="px-6 py-10 text-center">
              <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm text-gray-500">Loading staff...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="!loading && staffList.length === 0">
            <td colspan="5" class="px-6 py-10 text-center text-gray-500">No staff members found.</td>
          </tr>
          <tr v-else v-for="staff in staffList" :key="staff.id" class="hover:bg-gray-50 dark:hover:bg-meta-4 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <img v-if="staff.thumbnail" :src="staff.thumbnail.url" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                <div v-else class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-primary font-bold shadow-sm">
                  {{ staff.name.charAt(0) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900 dark:text-white">{{ staff.name }}</div>
                  <div class="text-xs text-gray-500">{{ staff.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ staff.role?.name || 'N/A' }}</div>
              <div class="text-xs text-gray-500 italic">{{ staff.staff?.designation || staff.designation || 'N/A' }}</div>
            </td>
            <td class="px-6 py-4">
              <span 
                :class="staff.deactivated_at ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                class="px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ staff.deactivated_at ? 'Deactivated' : 'Active' }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ staff.last_login_at ? formatDate(staff.last_login_at) : 'Never' }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <button @click="viewStaff(staff)" class="text-gray-500 hover:text-primary transition-colors" title="View Profile">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
                <button @click="editStaff(staff)" class="text-gray-500 hover:text-yellow-500 transition-colors" title="Edit Staff">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="confirmDelete(staff)" class="text-gray-500 hover:text-red-500 transition-colors" title="Delete Staff">
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

      <!-- Pagination -->
      <Pagination :meta="pagination" @change="fetchStaff" />
    </div>

    <!-- Invitation Modal -->
    <div v-if="showInviteModal" class="fixed inset-0 z-[99] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
      <div class="bg-white dark:bg-boxdark w-full max-w-4xl p-6 rounded-lg shadow-xl text-gray-900 dark:text-white overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold dark:text-white">Invite Staff Member</h3>
          <button @click="showInviteModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <form @submit.prevent="submitInvite" class="grid grid-cols-1 md:grid-cols-12 gap-8">
          <!-- Left Column: Photo Upload -->
          <div class="md:col-span-4 flex flex-col items-center pb-6 md:pb-0 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700">
            <label class="block text-sm font-medium mb-4 text-gray-700 dark:text-gray-300 w-full text-center">Staff Photo</label>
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
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Staff Full Name</label>
                <input v-model="inviteForm.name" type="text" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="Enter full name" />
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Email Address</label>
                <input v-model="inviteForm.email" type="email" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="email@example.com" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Assign Role</label>
                <select v-model="inviteForm.role" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all">
                  <option value="staff">Staff</option>
                  <option value="teacher">Teacher</option>
                  <option value="accountant">Accountant</option>
                  <option value="receptionist">Receptionist</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300">Designation</label>
                <input v-model="inviteForm.designation" type="text" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary outline-none transition-all" placeholder="e.g. Science Teacher" />
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
              <button @click="showInviteModal = false" type="button" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
              <button 
                type="submit" 
                :disabled="loading" 
                class="px-8 py-2.5 bg-primary text-white rounded-xl hover:bg-opacity-90 disabled:opacity-50 font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2"
              >
                <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ loading ? 'Sending...' : 'Send Invitation' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">Edit Staff</h3>
          <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="submitUpdate" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Name</label>
            <input v-model="selectedStaff!.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-form-input text-gray-500 dark:text-gray-400" disabled />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Email</label>
            <input v-model="selectedStaff!.email" type="email" class="w-full px-4 py-2 border rounded dark:bg-form-input text-gray-500 dark:text-gray-400" disabled />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Designation</label>
            <input v-model="selectedStaff!.designation" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Role</label>
            <select v-model="selectedStaff!.role" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white">
              <option value="Admin">Admin</option>
              <option value="Teacher">Teacher</option>
              <option value="Accountant">Accountant</option>
              <option value="Receptionist">Receptionist</option>
              <option value="Staff">General Staff</option>
            </select>
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="showEditModal = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50 font-semibold">
              {{ loading ? 'Updating...' : 'Update Staff' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import _ from 'lodash'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import Pagination from '@/components/common/Pagination.vue'

const router = useRouter()

interface Staff {
  id: string
  name: string
  email: string
  role?: { id: number, name: string }
  staff?: { designation: string }
  designation?: string
  deactivated_at?: string
  last_login_at?: string
  thumbnail?: { url: string }
}

const staffList = ref<Staff[]>([])
const loading = ref(false)
const showInviteModal = ref(false)
const showEditModal = ref(false)
const selectedStaff = ref<Staff | null>(null)
const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 1,
  to: 1,
  total: 0
})

const currentPage = computed(() => pagination.value.current_page)

const filters = ref({
  q: '',
  role: '',
  status: '',
  page: 1
})

const inviteForm = ref({
  name: '',
  email: '',
  role: 'staff',
  designation: '',
  photo: null as File | null
})

const photoPreview = ref<string | null>(null)
const photoInput = ref<HTMLInputElement | null>(null)

const onFileSelected = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    inviteForm.value.photo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const fetchStaff = async (page = 1) => {
  loading.value = true
  filters.value.page = page
  try {
    const response = await axios.get('/api/staff', { params: filters.value })
    staffList.value = response.data.data
    pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        from: response.data.from,
        to: response.data.to,
        total: response.data.total
    }
  } catch (error) {
    console.error('Failed to fetch staff:', error)
  } finally {
    loading.value = false
  }
}

const submitInvite = async () => {
  loading.value = true
  try {
    const formData = new FormData()
    formData.append('name', inviteForm.value.name)
    formData.append('email', inviteForm.value.email)
    formData.append('role', inviteForm.value.role)
    formData.append('designation', inviteForm.value.designation)
    if (inviteForm.value.photo) {
      formData.append('photo', inviteForm.value.photo)
    }

    await axios.post('/api/staff/invite', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    showInviteModal.value = false
    inviteForm.value = { name: '', email: '', role: 'staff', designation: '', photo: null }
    photoPreview.value = null
    fetchStaff()
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Staff member invited successfully',
      timer: 2000,
      showConfirmButton: false,
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } catch (error: any) {
    Swal.fire({
      icon: 'error',
      title: 'Invitation Failed',
      text: error.response?.data?.message || 'Failed to invite staff member.',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    loading.value = false
  }
}

const formatDate = (date: string) => new Date(date).toLocaleDateString()

const viewStaff = (staff: Staff) => {
  router.push({ name: 'user-profile', params: { type: 'staff', id: staff.id } })
}

const confirmDelete = async (staff: Staff) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `You are about to delete ${staff.name}. This action cannot be undone.`,
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
      await axios.delete(`/api/staff/${staff.id}`)
      fetchStaff(currentPage.value)
      Swal.fire({
        title: 'Deleted!',
        text: 'The staff member has been removed.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    } catch (error) {
      Swal.fire({
        title: 'Error!',
        text: 'Failed to delete staff member.',
        icon: 'error',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    }
  }
}

const editStaff = (staff: Staff) => {
  selectedStaff.value = { ...staff }
  showEditModal.value = true
}

const submitUpdate = async () => {
    if (!selectedStaff.value) return
    loading.value = true
    try {
        await axios.patch(`/api/staff/${selectedStaff.value.id}`, selectedStaff.value)
        showEditModal.value = false
        fetchStaff(pagination.value.current_page)
    } catch (error) {
        alert('Failed to update staff')
    } finally {
        loading.value = false
    }
}

const debouncedFetchStaff = _.debounce(fetchStaff, 500)
watch(filters, () => debouncedFetchStaff(), { deep: true })

onMounted(fetchStaff)
</script>
