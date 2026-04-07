<template>
  <MainLayout>
    <div class="p-4 md:p-6">
      <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">School Settings</h2>
        <p class="text-sm text-gray-500">Configure academic years, classes, subjects, and grading systems.</p>
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
        <!-- Academic Years Tab -->
        <div v-if="activeTab === 'academic_years'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">Academic Years</h3>
            <button @click="openYearModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
              <PlusIcon class="w-4 h-4" /> <span>Add Academic Year</span>
            </button>
          </div>
          
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-transparent">
            <table class="w-full text-left">
              <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02]">
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider w-16">S.N.</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Year Name</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Duration</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Status</th>
                  <th class="px-5 py-3 text-right font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
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
              <tr v-for="(year, index) in academicYears" :key="year.id" class="border-b border-gray-50 dark:border-gray-800/50 last:border-0 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4 text-theme-sm font-medium text-gray-500">{{ index + 1 }}</td>
                <td class="px-5 py-4 text-theme-sm font-bold text-gray-900 dark:text-white">{{ year.name }}</td>
                <td class="px-5 py-4 text-theme-xs text-gray-500 dark:text-gray-400">{{ year.start_date }} to {{ year.end_date }}</td>
                <td class="px-5 py-4">
                  <span :class="year.is_active ? 'text-green-600 bg-green-100 dark:bg-success-500/15 dark:text-success-500' : 'text-gray-500 bg-gray-100 dark:bg-gray-800'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                    {{ year.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right">
                  <div class="flex items-center justify-end gap-3">
                    <button @click="openYearModal(year)" class="text-gray-500 hover:text-primary transition-colors p-1" title="Edit">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button v-if="!year.is_active" @click="activateYear(year)" class="text-gray-500 hover:text-green-600 transition-colors p-1" title="Activate">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </button>
                    <button v-if="!year.is_active" @click="deleteYear(year)" class="text-gray-500 hover:text-red-500 transition-colors p-1" title="Delete">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

        <!-- Classes Tab -->
        <div v-if="activeTab === 'classes'">
          <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">Classes Management</h3>
              <p class="text-xs text-gray-500">Configure levels and their associated academic years.</p>
            </div>
            <button @click="openClassModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
              <PlusIcon class="w-4 h-4" /> <span>Add Class</span>
            </button>
          </div>
          
          <div class="mb-6 flex items-center gap-3 bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Filter By Year:</span>
            <select v-model="filters.academic_year_id" @change="fetchClasses()" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-boxdark outline-none focus:border-primary transition-all text-sm font-medium">
              <option value="">All Academic Years</option>
              <option v-for="year in academicYears" :key="year.id" :value="year.id">{{ year.name }}</option>
            </select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div v-if="loading && classes.length === 0" class="col-span-full py-20 flex flex-col items-center gap-4">
              <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
              <span class="text-sm text-gray-500 font-bold uppercase tracking-widest">Loading Classes...</span>
            </div>
            <div v-else-if="!loading && classes.length === 0" class="col-span-full py-20 text-center bg-gray-50/50 dark:bg-white/[0.01] rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800">
              <p class="text-gray-400 font-bold">No classes found for the selected criteria.</p>
            </div>
            <div v-else v-for="cls in classes" :key="cls.id" class="group p-5 border border-gray-100 dark:border-gray-800 bg-white dark:bg-white/[0.02] rounded-2xl hover:border-primary/50 hover:shadow-xl hover:shadow-primary/5 transition-all">
              <div class="flex justify-between items-start mb-4">
                <div class="font-black text-xl text-gray-900 dark:text-white">Class {{ cls.name }}</div>
                <div class="flex gap-1 transition-opacity">
                    <button @click="openClassModal(cls)" class="p-1 text-gray-500 hover:text-primary transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                    <button @click="deleteClass(cls)" class="p-1 text-gray-500 hover:text-red-500 transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary/40"></span>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">{{ cls.academic_year?.name }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sections Tab -->
        <div v-if="activeTab === 'sections'">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">Sections</h3>
              <p class="text-xs text-gray-500">Global section identifiers for classes.</p>
            </div>
            <button @click="openSectionModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
              <PlusIcon class="w-4 h-4" /> <span>Add Section</span>
            </button>
          </div>
          
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
              <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02]">
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider w-16">S.N.</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Section Name</th>
                  <th class="px-5 py-3 text-right font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <tr v-if="loading && sections.length === 0">
                  <td colspan="3" class="px-5 py-10 text-center">
                    <div class="flex flex-col items-center gap-2">
                       <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                       <span class="text-xs text-gray-400 font-bold uppercase">Loading...</span>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="!loading && sections.length === 0">
                  <td colspan="3" class="px-5 py-8 text-center text-gray-400 italic font-medium">No sections found.</td>
                </tr>
                <tr v-for="(sec, index) in sections" :key="sec.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-5 py-4 text-theme-xs font-bold text-gray-400">{{ index + 1 }}</td>
                  <td class="px-5 py-4 text-theme-sm font-bold text-gray-900 dark:text-white">Section {{ sec.name }}</td>
                  <td class="px-5 py-4 text-right">
                    <div class="flex gap-3 justify-end items-center">
                      <button @click="openSectionModal(sec)" class="text-gray-500 hover:text-primary transition-colors p-1" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                      <button @click="deleteSection(sec)" class="text-gray-500 hover:text-red-500 transition-colors p-1" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Subjects Tab -->
        <div v-if="activeTab === 'subjects'">
           <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">Subject Library</h3>
              <p class="text-xs text-gray-500">Repository of all subjects offered across the curriculum.</p>
            </div>
            <button @click="openSubjectModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all font-bold shadow-lg shadow-primary/20">
              <PlusIcon class="w-4 h-4" /> <span>Add Subject</span>
            </button>
          </div>
          
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
              <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02]">
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider w-16">S.N.</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Name</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Code</th>
                  <th class="px-5 py-3 text-left font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                  <th class="px-5 py-3 text-right font-bold text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
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
              <tr v-for="(sub, index) in subjects" :key="sub.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4 text-theme-xs font-bold text-gray-400">{{ index + 1 }}</td>
                <td class="px-5 py-4 text-theme-sm font-bold text-gray-900 dark:text-white">{{ sub.name }}</td>
                <td class="px-5 py-4 text-theme-xs text-gray-500 dark:text-gray-400"><span class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded uppercase font-bold text-[10px]">{{ sub.code || 'N/A' }}</span></td>
                <td class="px-5 py-4 text-theme-xs text-gray-500 dark:text-gray-400 font-bold">{{ sub.credit_hours || '0' }}</td>
                <td class="px-5 py-4 text-right">
                  <div class="flex gap-3 justify-end items-center">
                    <button @click="openSubjectModal(sub)" class="text-gray-500 hover:text-primary transition-colors p-1" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                    <button @click="deleteSubject(sub)" class="text-gray-500 hover:text-red-500 transition-colors p-1" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
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
import { ref, onMounted, reactive, watch } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import MainLayout from '@/components/layout/MainLayout.vue'
import { PlusIcon } from '@/icons'

const activeTab = ref<string>('academic_years')
const tabs = [
  { id: 'academic_years', label: 'Academic Years' },
  { id: 'classes', label: 'Classes' },
  { id: 'sections', label: 'Sections' },
  { id: 'subjects', label: 'Subjects' },
]

const academicYears = ref<any[]>([])
const classes = ref<any[]>([])
const sections = ref<any[]>([])
const subjects = ref<any[]>([])
const loading = ref(false)
const filters = reactive({ academic_year_id: '' })

// Track which tabs have been loaded to avoid redundant API calls
const loadedTabs = reactive({
    academic_years: false, 
    classes: false, 
    sections: false, 
    subjects: false
})

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
        loadedTabs.academic_years = true
    } finally { loading.value = false }
}

const fetchClasses = async () => {
    // If academic years haven't been loaded, load them first as they are needed for filters/context
    if (!loadedTabs.academic_years) {
        await fetchAcademicYears()
    }
    
    loading.value = true
    try {
        const res = await axios.get('/api/classes', { params: { academic_year_id: filters.academic_year_id } })
        classes.value = res.data.data
        loadedTabs.classes = true
    } finally { loading.value = false }
}

const fetchSections = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/sections', { params: { per_page: -1 } })
        sections.value = Array.isArray(res.data) ? res.data : (res.data.data || [])
        loadedTabs.sections = true
    } finally { loading.value = false }
}

const fetchSubjects = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/subjects')
        subjects.value = res.data.data
        loadedTabs.subjects = true
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
    const result = await Swal.fire({
        title: 'Activate Academic Year?',
        text: `Are you sure you want to activate "${year.name}"? This will deactivate the current active year.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, activate it!',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })

    if (result.isConfirmed) {
        try {
            await axios.patch(`/api/academic-years/${year.id}`, { is_active: true })
            
            await Swal.fire({
                title: 'Activated!',
                text: `${year.name} is now the active academic year.`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
            })
            
            fetchAcademicYears()
        } catch (e: any) {
            console.error('Activation error:', e)
            Swal.fire({
                title: 'Error',
                text: e.response?.data?.message || 'Failed to activate the academic year.',
                icon: 'error',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
            })
        }
    }
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

// Handle lazy loading on tab change
watch(activeTab, (newTab) => {
    if (newTab === 'academic_years' && !loadedTabs.academic_years) {
        fetchAcademicYears()
    } else if (newTab === 'classes' && !loadedTabs.classes) {
        fetchClasses()
    } else if (newTab === 'sections' && !loadedTabs.sections) {
        fetchSections()
    } else if (newTab === 'subjects' && !loadedTabs.subjects) {
        fetchSubjects()
    }
}, { immediate: true })

onMounted(async () => {
    // Initial fetch for the default tab (academic_years) is handled by the immediate watch
})
</script>
