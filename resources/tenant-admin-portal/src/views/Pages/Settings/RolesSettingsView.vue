<template>
  <MainLayout>
    <div class="p-4 md:p-6">
      <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Roles & Permissions</h2>
        <p class="text-sm text-gray-500">Manage user roles, access levels, and granular permissions.</p>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm overflow-hidden">
        <!-- Tabs Header -->
        <div class="pt-2 px-2 pb-0 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-transparent">
          <div class="flex gap-2 overflow-x-auto no-scrollbar">
            <button 
              v-for="tab in tabs" 
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="activeTab === tab.id ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
              class="px-5 py-4 border-b-2 transition-all whitespace-nowrap text-sm tracking-tight"
            >
              {{ tab.label }}
            </button>
          </div>
        </div>

        <!-- Card Body / Content -->
        <div class="p-6">
          <!-- Roles Tab -->
          <div v-if="activeTab === 'roles'">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">System Roles</h3>
              <button @click="openRoleModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
                <PlusIcon class="w-4 h-4" /> <span>Add Role</span>
              </button>
            </div>
            
            <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
              <table class="w-full text-left font-outfit">
                <thead>
                  <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02]">
                    <th class="px-5 py-3 text-left font-extrabold text-gray-400 text-[10px] uppercase tracking-widest w-16">S.N.</th>
                    <th class="px-5 py-3 text-left font-extrabold text-gray-400 text-[10px] uppercase tracking-widest">Role Name</th>
                    <th class="px-5 py-3 text-left font-extrabold text-gray-400 text-[10px] uppercase tracking-widest text-center">Permissions</th>
                    <th class="px-5 py-3 text-right font-extrabold text-gray-400 text-[10px] uppercase tracking-widest">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                  <tr v-if="loading && roles.length === 0">
                    <td colspan="4" class="px-5 py-10 text-center">
                      <div class="flex flex-col items-center gap-2">
                         <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                         <span class="text-xs text-gray-400 font-bold uppercase">Loading Roles...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-for="(role, index) in roles" :key="role.id" class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-5 py-4 text-xs font-bold text-gray-400">{{ index + 1 }}</td>
                    <td class="px-5 py-4 font-bold text-gray-900 dark:text-white capitalize">{{ role.name }}</td>
                    <td class="px-5 py-4 text-center">
                      <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-black tracking-tight">
                        {{ role.permissions_count }} Permissions
                      </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                      <div class="flex gap-3 justify-end items-center">
                        <button v-if="role.name !== 'school_admin'" @click="openPermissionAssignModal(role)" class="text-gray-500 hover:text-primary transition-colors p-1" title="Manage Permissions">
                          <PlugInIcon class="w-5 h-5" />
                        </button>
                        <button @click="openRoleModal(role)" class="text-gray-500 hover:text-primary transition-colors p-1" title="Edit Role">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button v-if="role.name !== 'school_admin'" @click="deleteRole(role)" class="text-gray-500 hover:text-red-500 transition-colors p-1" title="Delete Role">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Permissions Tab -->
          <div v-if="activeTab === 'permissions'">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">System Permissions</h3>
                <p class="text-xs text-gray-500">Atomic access controls grouped by module.</p>
              </div>
              <button @click="openPermissionModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
                <PlusIcon class="w-4 h-4" /> <span>Add Permission</span>
              </button>
            </div>

            <div v-if="loading && Object.keys(groupedPermissions).length === 0" class="flex flex-col items-center py-20 gap-3">
              <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
              <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Scanning Registry...</span>
            </div>
            
            <div v-else class="space-y-8">
              <div v-for="(perms, category) in groupedPermissions" :key="category" class="bg-gray-50/50 dark:bg-white/[0.01] p-6 rounded-2xl border border-gray-100 dark:border-gray-800">
                <h4 class="text-sm font-black uppercase tracking-tighter text-primary mb-4 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-primary ring-4 ring-primary/10"></span>
                  {{ category }} Module 
                  <span class="ml-auto text-[10px] text-gray-400 font-bold border border-gray-200 dark:border-gray-800 px-2 py-0.5 rounded-full">{{ perms.length }} Items</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                  <div v-for="p in perms" :key="p.id" class="bg-white dark:bg-boxdark p-3 rounded-xl border border-gray-100 dark:border-gray-800 flex justify-between items-center group shadow-sm hover:shadow-md transition-shadow">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ p.name }}</span>
                    <span class="text-[9px] font-black bg-gray-100 dark:bg-gray-800 text-gray-400 px-1.5 py-0.5 rounded group-hover:bg-primary/10 group-hover:text-primary transition-colors uppercase tracking-tight">{{ p.guard_name }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Role Modal -->
      <div v-if="roleModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
        <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-2xl shadow-2xl text-gray-900 dark:text-white overflow-hidden border border-white/20">
          <div class="flex justify-between items-center p-6 border-b dark:border-strokedark bg-gray-50/50 dark:bg-transparent">
            <h3 class="text-lg font-black dark:text-white uppercase tracking-tighter">{{ roleModal.editing ? 'Refine' : 'Assign' }} Role</h3>
            <button @click="roleModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white text-3xl">&times;</button>
          </div>
          <form @submit.prevent="saveRole" class="p-6 space-y-6">
            <div>
              <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Role Name</label>
              <input 
                v-model="roleModal.form.name" 
                type="text" 
                placeholder="e.g. Academic Lead"
                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all placeholder:text-gray-400 font-bold" 
                required 
              />
              <div v-if="roleModal.form.name" class="mt-2 ml-1 flex items-center gap-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">System Identifier:</span>
                <span class="text-[10px] font-black bg-primary/5 text-primary px-2 py-0.5 rounded border border-primary/10 transition-all">
                  {{ roleModal.form.name.toLowerCase().trim().replace(/[\s-]+/g, '_') }}
                </span>
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="roleModal.show = false" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Discard</button>
              <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-xl hover:bg-opacity-90 font-black shadow-xl shadow-primary/20 transition-all uppercase text-[11px] tracking-widest">Commit Changes</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Permission Assignment Modal -->
      <div v-if="assignModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
        <div class="bg-white dark:bg-boxdark w-full max-w-4xl max-h-[90vh] flex flex-col rounded-3xl shadow-2xl text-gray-900 dark:text-white overflow-hidden border border-white/10">
          <div class="flex justify-between items-center px-8 py-6 border-b dark:border-strokedark bg-gray-50/50 dark:bg-transparent">
            <div>
               <h3 class="text-xl font-black dark:text-white uppercase tracking-tighter">Entitlement Matrix</h3>
               <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Role: <span class="text-primary">{{ assignModal.role?.name }}</span></p>
            </div>
            <button @click="assignModal.show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors text-3xl">&times;</button>
          </div>
          
          <div class="overflow-y-auto p-8 flex-1">
             <div class="space-y-10">
                <div v-for="(perms, category) in groupedPermissions" :key="category">
                   <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-5 flex items-center gap-3">
                     {{ category }} 
                     <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
                     <label class="flex items-center gap-2 cursor-pointer group ml-3" @click.stop>
                        <input type="checkbox" :checked="isCategoryAllSelected(perms)" @change="toggleCategoryPermissions(perms)" class="hidden" />
                        <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors" :class="isCategoryAllSelected(perms) ? 'bg-primary border-primary' : 'border-gray-300 dark:border-gray-600 group-hover:border-primary/50'">
                            <svg v-if="isCategoryAllSelected(perms)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400 group-hover:text-primary transition-colors">Select All</span>
                     </label>
                   </h4>
                   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8">
                      <div v-for="p in perms" :key="p.id" class="flex items-center gap-3 group cursor-pointer" @click="togglePermission(p.name)">
                         <div 
                           class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all duration-200"
                           :class="assignModal.form.permissions.includes(p.name) ? 'bg-primary border-primary shadow-lg shadow-primary/20' : 'border-gray-200 dark:border-gray-700 group-hover:border-primary/50'"
                         >
                           <svg v-if="assignModal.form.permissions.includes(p.name)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                         </div>
                         <span class="text-sm font-bold text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ p.name }}</span>
                      </div>
                   </div>
                </div>
             </div>
          </div>

          <div class="px-8 py-6 bg-gray-50/50 dark:bg-transparent border-t dark:border-strokedark flex justify-between items-center">
             <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
               {{ assignModal.form.permissions.length }} Entitlements Selected
             </div>
             <div class="flex gap-4">
                <button @click="assignModal.show = false" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Discard</button>
                <button 
                  @click="saveRolePermissions" 
                  :disabled="loading"
                  class="px-8 py-3 bg-primary text-white rounded-2xl hover:bg-opacity-90 font-black shadow-2xl shadow-primary/30 transition-all uppercase text-xs tracking-widest flex items-center gap-2"
                >
                  <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                  Sync Permissions
                </button>
             </div>
          </div>
        </div>
      </div>

      <!-- Permission Modal -->
      <div v-if="permissionModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
        <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-2xl shadow-2xl text-gray-900 dark:text-white overflow-hidden border border-white/20">
          <div class="flex justify-between items-center p-6 border-b dark:border-strokedark bg-gray-50/50 dark:bg-transparent">
            <h3 class="text-lg font-black dark:text-white uppercase tracking-tighter">New Entitlement</h3>
            <button @click="permissionModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white text-3xl">&times;</button>
          </div>
          <form @submit.prevent="savePermission" class="p-6 space-y-6">
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Module</label>
                <select 
                  v-model="permissionModal.form.module" 
                  class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all font-bold"
                  required
                >
                  <option value="" disabled>Select Module</option>
                  <option v-for="m in modules" :key="m" :value="m">{{ m }}</option>
                </select>
              </div>
              <div>
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Actions</label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" :checked="isAllActionsSelected" @change="toggleSelectAllActions" class="hidden" />
                        <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors" :class="isAllActionsSelected ? 'bg-primary border-primary' : 'border-gray-300 dark:border-gray-600 group-hover:border-primary/50'">
                            <svg v-if="isAllActionsSelected" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-primary transition-colors">Select All</span>
                    </label>
                </div>
                <div class="grid grid-cols-2 gap-3 p-4 border border-gray-100 dark:border-gray-800 rounded-2xl bg-gray-50/30 dark:bg-white/[0.01]">
                  <div v-for="a in actions" :key="a" class="flex items-center gap-3 group cursor-pointer" @click="toggleAction(a)">
                    <div 
                      class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-200"
                      :class="permissionModal.form.actions.includes(a) ? 'bg-primary border-primary shadow-lg shadow-primary/20' : 'border-gray-200 dark:border-gray-700 group-hover:border-primary/50'"
                    >
                      <svg v-if="permissionModal.form.actions.includes(a)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ a }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="permissionModal.form.module && permissionModal.form.actions.length" class="p-4 bg-primary/5 rounded-xl border border-primary/10">
              <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Generated Identifiers</p>
              <div class="flex flex-wrap gap-2 mt-2">
                <span v-for="a in permissionModal.form.actions" :key="a" class="text-[10px] font-black bg-white dark:bg-gray-800 border border-primary/20 text-primary px-2 py-0.5 rounded shadow-sm">
                  {{ permissionModal.form.module }}.{{ a }}
                </span>
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="permissionModal.show = false" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Discard</button>
              <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-xl hover:bg-opacity-90 font-black shadow-xl shadow-primary/20 transition-all uppercase text-[11px] tracking-widest">Append to Registry</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import MainLayout from '@/components/layout/MainLayout.vue'
import { PlusIcon, PlugInIcon } from '@/icons'

const activeTab = ref<string>('roles')
const tabs = [
  { id: 'roles', label: 'Roles' },
  { id: 'permissions', label: 'Permissions' },
]

const roles = ref<any[]>([])
const groupedPermissions = ref<any>({})
const loading = ref(false)

const roleModal = reactive({
    show: false,
    editing: false,
    id: null as any,
    form: { name: '' }
})

interface PermissionForm {
    module: string;
    actions: string[];
}

const permissionModal = reactive({
    show: false,
    form: { module: '', actions: [] as string[] } as PermissionForm
})

const modules = ['staff', 'student', 'parent', 'homework', 'attendance', 'finance', 'academic_year', 'class', 'section', 'subject']
const actions = ['view', 'create', 'update', 'delete', 'manage']

const isAllActionsSelected = computed(() => {
    return actions.length > 0 && actions.every(a => permissionModal.form.actions.includes(a))
})

const toggleSelectAllActions = () => {
    if (isAllActionsSelected.value) {
        permissionModal.form.actions = []
    } else {
        permissionModal.form.actions = [...actions]
    }
}

const toggleAction = (action: string) => {
    const idx = permissionModal.form.actions.indexOf(action)
    if (idx === -1) {
        permissionModal.form.actions.push(action)
    } else {
        permissionModal.form.actions.splice(idx, 1)
    }
}

const assignModal = reactive({
    show: false,
    role: null as any,
    form: { permissions: [] as string[] }
})

const openRoleModal = (role: any = null) => {
    if (role) {
        roleModal.editing = true
        roleModal.id = role.id
        roleModal.form = { name: role.name }
    } else {
        roleModal.editing = false
        roleModal.form = { name: '' }
    }
    roleModal.show = true
}

const openPermissionModal = () => {
    permissionModal.form.module = ''
    permissionModal.form.actions = []
    permissionModal.show = true
}

const openPermissionAssignModal = (role: any) => {
    assignModal.role = role
    assignModal.form.permissions = [...(role.permissions || [])]
    assignModal.show = true
}

const togglePermission = (name: string) => {
    const idx = assignModal.form.permissions.indexOf(name)
    if (idx === -1) assignModal.form.permissions.push(name)
    else assignModal.form.permissions.splice(idx, 1)
}

const isCategoryAllSelected = (perms: any[]) => {
    return perms.every(p => assignModal.form.permissions.includes(p.name))
}

const toggleCategoryPermissions = (perms: any[]) => {
    const allSelected = isCategoryAllSelected(perms)
    perms.forEach(p => {
        const idx = assignModal.form.permissions.indexOf(p.name)
        if (allSelected) {
            if (idx !== -1) assignModal.form.permissions.splice(idx, 1)
        } else {
            if (idx === -1) assignModal.form.permissions.push(p.name)
        }
    })
}

const fetchRoles = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/roles')
        roles.value = res.data
    } finally { loading.value = false }
}

const fetchPermissions = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/permissions')
        groupedPermissions.value = res.data
    } finally { loading.value = false }
}

const saveRole = async () => {
    try {
        if (roleModal.editing) {
            await axios.patch(`/api/roles/${roleModal.id}`, roleModal.form)
        } else {
            await axios.post('/api/roles', roleModal.form)
        }
        roleModal.show = false
        fetchRoles()
        Swal.fire({
            title: 'Role Saved',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    } catch (e: any) {
        Swal.fire({
            title: 'Error',
            text: e.response?.data?.message || 'Failed to save role',
            icon: 'error',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    }
}

const savePermission = async () => {
    if (!permissionModal.form.module || !permissionModal.form.actions.length) {
        Swal.fire({
            title: 'Input Required',
            text: 'Please select a module and at least one action.',
            icon: 'warning',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
        return
    }
    
    try {
        const res = await axios.post('/api/permissions', permissionModal.form)
        permissionModal.show = false
        fetchPermissions()
        Swal.fire({
            title: 'Registry Updated',
            text: res.data.message,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    } catch (e: any) {
        Swal.fire({
            title: 'Addition Failed',
            text: e.response?.data?.message || 'Failed to sync with registry.',
            icon: 'error',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    }
}

const saveRolePermissions = async () => {
    if (!assignModal.role) return
    loading.value = true
    try {
        await axios.post(`/api/roles/${assignModal.role.id}/permissions`, assignModal.form)
        assignModal.show = false
        fetchRoles()
        Swal.fire({
            title: 'Permissions Synchronized',
            text: `Access levels for ${assignModal.role.name} have been updated.`,
            icon: 'success',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
        })
    } catch (e: any) {
        Swal.fire({
            title: 'Sync Failed',
            text: 'There was an issue updating the roles matrix.',
            icon: 'error'
        })
    } finally { loading.value = false }
}

const deleteRole = async (role: any) => {
    const result = await Swal.fire({
        title: 'Delete Role?',
        text: `Are you sure you want to remove the "${role.name}" role? This might affect existing users.`,
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
            await axios.delete(`/api/roles/${role.id}`)
            fetchRoles()
        } catch (e: any) {
            Swal.fire({
                title: 'Operation Refused',
                text: e.response?.data?.message || 'Role could not be deleted.',
                icon: 'error'
            })
        }
    }
}

watch(activeTab, (newTab) => {
    if (newTab === 'roles') fetchRoles()
    else fetchPermissions()
}, { immediate: true })

onMounted(() => {
    fetchPermissions() // Always fetch permissions as they are needed for assignment matrix
})
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
