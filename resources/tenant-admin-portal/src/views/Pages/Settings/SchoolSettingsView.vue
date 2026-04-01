<template>
  <MainLayout>
    <div class="p-4 md:p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">School Settings</h2>
        <p class="text-gray-500">Configure academic years, classes, subjects, and grading systems.</p>
      </div>

      <!-- Tabs Header -->
      <div class="flex border-b border-gray-200 dark:border-strokedark mb-6">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="activeTab === tab.id ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
          class="px-6 py-3 border-b-2 font-medium transition-colors"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Tabs Content -->
      <div class="bg-white dark:bg-boxdark rounded-lg shadow-sm p-6 min-h-[400px]">
        <!-- Academic Years Tab -->
        <div v-if="activeTab === 'academic_years'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Academic Years</h3>
            <button @click="openYearModal()" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2">
              <PlusIcon class="w-4 h-4" /> <span>Add Academic Year</span>
            </button>
          </div>
          
          <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-meta-4">
              <tr>
                <th class="px-4 py-3 text-sm font-medium uppercase">Year Name</th>
                <th class="px-4 py-3 text-sm font-medium uppercase">Duration</th>
                <th class="px-4 py-3 text-sm font-medium uppercase">Status</th>
                <th class="px-4 py-3 text-sm font-medium uppercase text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-strokedark text-gray-900 dark:text-white">
              <tr v-if="loading && academicYears.length === 0">
                <td colspan="4" class="px-4 py-10 text-center">
                  <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm text-gray-500">Loading academic years...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="!loading && academicYears.length === 0">
                <td colspan="4" class="px-4 py-10 text-center text-gray-500 font-medium">No academic years found.</td>
              </tr>
              <tr v-for="year in academicYears" :key="year.id" class="dark:text-white">
                <td class="px-4 py-4">{{ year.name }}</td>
                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ year.start_date }} to {{ year.end_date }}</td>
                <td class="px-4 py-4">
                  <span :class="year.is_active ? 'text-green-600 bg-green-100 dark:bg-green-600/20 dark:text-green-400' : 'text-gray-500 bg-gray-100 dark:bg-gray-700'" class="px-2 py-1 rounded text-xs font-semibold">
                    {{ year.is_active ? 'Current Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-4 py-4 text-right">
                  <button @click="openYearModal(year)" class="text-primary mr-3 font-medium hover:underline">Edit</button>
                  <button v-if="!year.is_active" @click="activateYear(year)" class="text-green-600 mr-3 font-medium hover:underline">Activate</button>
                  <button v-if="!year.is_active" @click="deleteYear(year)" class="text-red-600 font-medium hover:underline">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Classes Tab -->
        <div v-if="activeTab === 'classes'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Class Configuration</h3>
            <button @click="openClassModal()" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2">
              <PlusIcon class="w-4 h-4" /> <span>Add Class</span>
            </button>
          </div>
          
          <div class="mb-4">
            <select v-model="filters.academic_year_id" @change="fetchClasses()" class="px-4 py-2 border rounded-lg dark:bg-boxdark dark:border-strokedark">
              <option value="">All Academic Years</option>
              <option v-for="year in academicYears" :key="year.id" :value="year.id">{{ year.name }}</option>
            </select>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-if="loading && classes.length === 0" class="col-span-full py-10 flex flex-col items-center gap-2">
              <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
              <span class="text-sm text-gray-500">Loading classes...</span>
            </div>
            <div v-else-if="!loading && classes.length === 0" class="col-span-full py-10 text-center text-gray-500 font-medium">
              No classes found for this year.
            </div>
            <div v-else v-for="cls in classes" :key="cls.id" class="p-4 border rounded-lg dark:border-strokedark hover:shadow-md transition text-gray-900 dark:text-white">
              <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-lg dark:text-white">Class {{ cls.name }}</div>
                <div class="flex gap-2">
                    <button @click="openClassModal(cls)" class="text-primary text-sm hover:underline font-medium">Edit</button>
                    <button @click="deleteClass(cls)" class="text-red-600 text-sm hover:underline font-medium">Delete</button>
                </div>
              </div>
              <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ cls.academic_year?.name }}</div>
            </div>
          </div>
        </div>

        <!-- Sections Tab -->
        <div v-if="activeTab === 'sections'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Global Sections</h3>
            <button @click="openSectionModal()" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2">
              <PlusIcon class="w-4 h-4" /> <span>Add Section</span>
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-if="loading && sections.length === 0" class="col-span-full py-10 flex flex-col items-center gap-2">
              <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
              <span class="text-sm text-gray-500 font-normal">Loading sections...</span>
            </div>
            <div v-else-if="!loading && sections.length === 0" class="col-span-full py-10 text-center text-gray-500 font-medium">
              No sections created.
            </div>
            <div v-else v-for="sec in sections" :key="sec.id" class="p-4 border rounded-lg dark:border-strokedark hover:shadow-md transition text-gray-900 dark:text-white flex justify-between items-center font-bold">
              <span>Section {{ sec.name }}</span>
              <div class="flex gap-2">
                <button @click="openSectionModal(sec)" class="text-primary hover:underline">Edit</button>
                <button @click="deleteSection(sec)" class="text-red-600 hover:underline">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Subjects Tab -->
        <div v-if="activeTab === 'subjects'">
           <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Subject Library</h3>
            <button @click="openSubjectModal()" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2">
              <PlusIcon class="w-4 h-4" /> <span>Add Subject</span>
            </button>
          </div>
          
          <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-meta-4">
              <tr>
                <th class="px-4 py-3 text-sm font-medium uppercase">Name</th>
                <th class="px-4 py-3 text-sm font-medium uppercase">Code</th>
                <th class="px-4 py-3 text-sm font-medium uppercase">Credit Hours</th>
                <th class="px-4 py-3 text-sm font-medium uppercase text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-strokedark text-gray-900 dark:text-white font-medium">
              <tr v-if="loading && subjects.length === 0">
                <td colspan="4" class="px-4 py-10 text-center">
                  <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm text-gray-500 font-normal">Loading subjects...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="!loading && subjects.length === 0">
                <td colspan="4" class="px-4 py-10 text-center text-gray-500">No subjects in the library.</td>
              </tr>
              <tr v-for="sub in subjects" :key="sub.id">
                <td class="px-4 py-4 dark:text-white">{{ sub.name }}</td>
                <td class="px-4 py-4 text-gray-500 dark:text-gray-400">{{ sub.code || 'N/A' }}</td>
                <td class="px-4 py-4 text-gray-500 dark:text-gray-400">{{ sub.credit_hours || '0' }}</td>
                <td class="px-4 py-4 text-right">
                  <button @click="openSubjectModal(sub)" class="text-primary mr-3 hover:underline font-bold">Edit</button>
                  <button @click="deleteSubject(sub)" class="text-red-600 hover:underline font-bold">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Academic Year Modal -->
    <div v-if="yearModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">{{ yearModal.editing ? 'Edit' : 'Add' }} Academic Year</h3>
          <button @click="yearModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="saveYear" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Year Name (e.g. 2081/82)</label>
            <input v-model="yearModal.form.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1 dark:text-white">Start Date</label>
              <input v-model="yearModal.form.start_date" type="date" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1 dark:text-white">End Date</label>
              <input v-model="yearModal.form.end_date" type="date" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Number of Terms</label>
            <input v-model="yearModal.form.terms" type="number" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="yearModal.show = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 font-semibold">Save Year</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Class Modal -->
    <div v-if="classModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">{{ classModal.editing ? 'Edit' : 'Add' }} Class</h3>
          <button @click="classModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="saveClass" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Class Name</label>
            <input v-model="classModal.form.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Academic Year</label>
            <select v-model="classModal.form.academic_year_id" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" :disabled="classModal.editing">
              <option v-for="year in academicYears" :key="year.id" :value="year.id">{{ year.name }}</option>
            </select>
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="classModal.show = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 font-semibold">Save Class</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Subject Modal -->
    <div v-if="subjectModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">{{ subjectModal.editing ? 'Edit' : 'Add' }} Subject</h3>
          <button @click="subjectModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="saveSubject" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Subject Name</label>
            <input v-model="subjectModal.form.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Code</label>
            <input v-model="subjectModal.form.code" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Credit Hours</label>
            <input v-model="subjectModal.form.credit_hours" type="number" step="0.5" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" />
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="subjectModal.show = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 font-semibold">Save Subject</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Section Modal -->
    <div v-if="sectionModal.show" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-md rounded-lg shadow-lg text-gray-900 dark:text-white">
        <div class="flex justify-between items-center p-4 border-b dark:border-strokedark">
          <h3 class="text-lg font-bold dark:text-white">{{ sectionModal.editing ? 'Edit' : 'Add' }} Section</h3>
          <button @click="sectionModal.show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">&times;</button>
        </div>
        <form @submit.prevent="saveSection" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1 dark:text-white">Section Name (e.g. A, B, Blue)</label>
            <input v-model="sectionModal.form.name" type="text" class="w-full px-4 py-2 border rounded dark:bg-meta-4 dark:border-strokedark text-black dark:text-white" required />
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <button type="button" @click="sectionModal.show = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 font-semibold">Save Section</button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios'
import MainLayout from '@/components/layout/MainLayout.vue'
import { PlusIcon } from '@/icons'

const activeTab = ref('academic_years')
const tabs = [
  { id: 'academic_years', label: 'Academic Years' },
  { id: 'classes', label: 'Classes' },
  { id: 'sections', label: 'Sections' },
  { id: 'subjects', label: 'Library (Subjects)' },
]

const academicYears = ref<any[]>([])
const classes = ref<any[]>([])
const sections = ref<any[]>([])
const subjects = ref<any[]>([])
const loading = ref(false)
const filters = reactive({ academic_year_id: '' })

// Year Modal Logic
const yearModal = reactive({
    show: false,
    editing: false,
    id: null as any,
    form: { name: '', start_date: '', end_date: '', terms: 3 }
})

const openYearModal = (year: any = null) => {
    if (year) {
        yearModal.editing = true
        yearModal.id = year.id
        yearModal.form = { ...year }
    } else {
        yearModal.editing = false
        yearModal.form = { name: '', start_date: '', end_date: '', terms: 3 }
    }
    yearModal.show = true
}

// Class Modal Logic
const classModal = reactive({
    show: false,
    editing: false,
    id: null as any,
    form: { name: '', academic_year_id: '', subject_ids: [] as any[] }
})

const openClassModal = (cls: any = null) => {
    if (cls) {
        classModal.editing = true
        classModal.id = cls.id
        classModal.form = { 
            name: cls.name, 
            academic_year_id: cls.academic_year_id,
            subject_ids: []
        }
    } else {
        classModal.editing = false
        classModal.form = { name: '', academic_year_id: filters.academic_year_id || '', subject_ids: [] }
    }
    classModal.show = true
}

// Section Modal Logic
const sectionModal = reactive({
    show: false,
    editing: false,
    id: null as any,
    form: { name: '' }
})

const openSectionModal = (sec: any = null) => {
    if (sec) {
        sectionModal.editing = true
        sectionModal.id = sec.id
        sectionModal.form = { ...sec }
    } else {
        sectionModal.editing = false
        sectionModal.form = { name: '' }
    }
    sectionModal.show = true
}

// Subject Modal Logic
const subjectModal = reactive({
    show: false,
    editing: false,
    id: null as any,
    form: { name: '', code: '', credit_hours: 0 }
})

const openSubjectModal = (sub: any = null) => {
    if (sub) {
        subjectModal.editing = true
        subjectModal.id = sub.id
        subjectModal.form = { ...sub }
    } else {
        subjectModal.editing = false
        subjectModal.form = { name: '', code: '', credit_hours: 0 }
    }
    subjectModal.show = true
}

// API Methods
const fetchAcademicYears = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/academic-years')
        academicYears.value = res.data.data
        if (academicYears.value.length > 0 && !filters.academic_year_id) {
            filters.academic_year_id = academicYears.value.find((y: any) => y.is_active)?.id || academicYears.value[0].id
        }
    } finally { loading.value = false }
}

const fetchClasses = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/classes', { params: { academic_year_id: filters.academic_year_id } })
        classes.value = res.data.data
    } finally { loading.value = false }
}

const fetchSections = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/sections', { params: { per_page: -1 } })
        sections.value = Array.isArray(res.data) ? res.data : (res.data.data || [])
    } finally { loading.value = false }
}

const fetchSubjects = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/subjects')
        subjects.value = res.data.data
    } finally { loading.value = false }
}

const saveYear = async () => {
    try {
        if (yearModal.editing) {
            await axios.patch(`/api/academic-years/${yearModal.id}`, yearModal.form)
        } else {
            await axios.post('/api/academic-years', yearModal.form)
        }
        yearModal.show = false
        fetchAcademicYears()
    } catch (e) { alert('Failed to save year') }
}

const activateYear = async (year: any) => {
    try {
        await axios.patch(`/api/academic-years/${year.id}`, { is_active: true })
        fetchAcademicYears()
    } catch (e) { alert('Failed to activate year') }
}

const deleteYear = async (year: any) => {
    if (confirm('Delete this academic year?')) {
        try {
            await axios.delete(`/api/academic-years/${year.id}`)
            fetchAcademicYears()
        } catch (e) { alert('Delete failed (Check if active or has classes)') }
    }
}

const saveClass = async () => {
    try {
        if (classModal.editing) {
            await axios.patch(`/api/classes/${classModal.id}`, classModal.form)
        } else {
            await axios.post('/api/classes', classModal.form)
        }
        classModal.show = false
        fetchClasses()
    } catch (e) { alert('Failed to save class') }
}

const deleteClass = async (cls: any) => {
    if (confirm('Delete this class?')) {
        try {
            await axios.delete(`/api/classes/${cls.id}`)
            fetchClasses()
        } catch (e) { alert('Delete failed (Check for enrolled students)') }
    }
}

const saveSubject = async () => {
    try {
        if (subjectModal.editing) {
            await axios.patch(`/api/subjects/${subjectModal.id}`, subjectModal.form)
        } else {
            await axios.post('/api/subjects', subjectModal.form)
        }
        subjectModal.show = false
        fetchSubjects()
    } catch (e) { alert('Failed to save subject') }
}

const saveSection = async () => {
    try {
        if (sectionModal.editing) {
            await axios.patch(`/api/sections/${sectionModal.id}`, sectionModal.form)
        } else {
            await axios.post('/api/sections', sectionModal.form)
        }
        sectionModal.show = false
        fetchSections()
    } catch (e) { alert('Failed to save section') }
}

const deleteSection = async (sec: any) => {
    if (confirm(`Delete section ${sec.name}?`)) {
        try {
            await axios.delete(`/api/sections/${sec.id}`)
            fetchSections()
        } catch (e) { alert('Delete failed (Check if section has students)') }
    }
}

const deleteSubject = async (sub: any) => {
    if (confirm('Delete this subject?')) {
        try {
            await axios.delete(`/api/subjects/${sub.id}`)
            fetchSubjects()
        } catch (e) { alert('Delete failed (Check if linked to classes)') }
    }
}

onMounted(async () => {
    await fetchAcademicYears()
    await fetchSubjects()
    await fetchClasses()
    await fetchSections()
})
</script>
