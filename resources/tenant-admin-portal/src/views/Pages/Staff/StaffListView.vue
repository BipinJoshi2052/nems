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
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Name</th>
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
              <div class="font-medium text-gray-900 dark:text-white">{{ staff.name }}</div>
              <div class="text-xs text-gray-500">{{ staff.email }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900 dark:text-white">{{ staff.role || staff.designation }}</div>
              <div class="text-xs text-gray-500">{{ staff.designation }}</div>
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
    <div v-if="showInviteModal" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
      <div class="bg-white dark:bg-boxdark w-full max-w-md p-6 rounded-lg shadow-xl text-gray-900 dark:text-white">
        <h3 class="text-xl font-bold mb-4 dark:text-white">Invite Staff Member</h3>
        <form @submit.prevent="submitInvite">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1 dark:text-white">Full Name</label>
            <input v-model="inviteForm.name" type="text" required class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1 dark:text-white">Email Address</label>
            <input v-model="inviteForm.email" type="email" required class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <label class="block text-sm font-medium mb-1 dark:text-white">Role</label>
              <select v-model="inviteForm.role" required class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white">
                <option value="Teacher">Teacher</option>
                <option value="Admin">Admin</option>
                <option value="Accountant">Accountant</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1 dark:text-white">Designation</label>
              <input v-model="inviteForm.designation" type="text" placeholder="e.g. Science Teacher" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
            </div>
          </div>
          <div class="flex justify-end gap-3">
            <button @click="showInviteModal = false" type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancel</button>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50">
              {{ loading ? 'Inviting...' : 'Send Invitation' }}
            </button>
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
  role?: string
  designation: string
  deactivated_at?: string
  last_login_at?: string
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
  role: 'Teacher',
  designation: ''
})

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
    await axios.post('/api/staff/invite', inviteForm.value)
    showInviteModal.value = false
    inviteForm.value = { name: '', email: '', role: 'Teacher', designation: '' }
    fetchStaff()
  } catch (error) {
    alert('Failed to invite staff. Please check if email is unique.')
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
