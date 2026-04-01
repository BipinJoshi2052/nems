<template>
  <MainLayout>
    <div class="p-4 md:p-6">
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Parent Management</h2>
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
                <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Name</th>
                <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Email</th>
                <th class="px-6 py-4 text-sm font-medium uppercase text-gray-500">Phone</th>
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
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ parent.name }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-medium">{{ parent.email }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-medium">{{ parent.phone || 'N/A' }}</td>
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
    </div>

    <!-- Parent Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">{{ showEditModal ? 'Edit Parent' : 'Add New Parent' }}</h3>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="submitForm" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Name</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Email</label>
            <input v-model="form.email" type="email" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Phone</label>
            <input v-model="form.phone" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="closeModal" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50 font-semibold">
              {{ loading ? 'Saving...' : (showEditModal ? 'Update Parent' : 'Add Parent') }}
            </button>
          </div>
        </form>
      </div>
    </div>
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

const router = useRouter()

interface Parent {
  id: string
  name: string
  email: string
  phone?: string
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
  phone: ''
})

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
  showEditModal.value = true
}

const submitForm = async () => {
  loading.value = true
  try {
    if (showEditModal.value && selectedParentId.value) {
      await axios.patch(`/api/parents/${selectedParentId.value}`, form)
    } else {
      await axios.post('/api/parents', form)
    }
    closeModal()
    fetchParents(showEditModal.value ? pagination.value.current_page : 1)
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to save parent')
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
}

onMounted(() => {
  fetchParents()
})
</script>
