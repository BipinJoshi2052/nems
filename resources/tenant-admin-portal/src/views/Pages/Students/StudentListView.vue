<template>
  <MainLayout>
    <div class="p-4 md:p-6">
    <PageBreadcrumb pageTitle="Student Directory" />

    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Student Directory</h2>
      <p class="text-sm text-gray-500">Manage student admissions, enrollments, and academic records.</p>
    </div>

    <!-- Filters Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm overflow-hidden mb-6">
      <div class="p-5 md:p-6">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-8">
          <!-- Search -->
          <div class="relative flex-1 max-w-xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
            <input 
              v-model="filters.q" 
              type="text" 
              placeholder="Search students by name or admission no..." 
              class="w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-boxdark focus:outline-none focus:border-primary transition-all shadow-sm text-sm"
            />
          </div>
          
          <div class="flex items-center gap-3">
            <!-- Add New -->
            <button 
              @click="showAddModal = true"
              class="flex items-center justify-center gap-2 px-6 py-3 text-white bg-primary rounded-xl hover:bg-opacity-90 font-bold shadow-lg shadow-primary/20 transition-all text-sm whitespace-nowrap"
            >
              <PlusIcon class="w-5 h-5" />
              <span>New Admission</span>
            </button>

            <!-- Bulk Actions -->
            <div class="relative" v-if="selectedIds.length > 0" v-click-outside="() => showActionsDropdown = false">
              <button 
                @click="showActionsDropdown = !showActionsDropdown"
                class="flex items-center gap-2 px-5 py-3 bg-gray-50 dark:bg-meta-4 border border-gray-200 dark:border-strokedark rounded-xl hover:bg-gray-100 transition-all font-bold group text-sm"
              >
                <span class="text-primary group-hover:text-primary/80">Bulk Actions ({{ selectedIds.length }})</span>
                <ChevronDownIcon class="w-4 h-4 transition-transform text-primary" :class="{ 'rotate-180': showActionsDropdown }" />
              </button>
              
              <div v-if="showActionsDropdown" class="absolute right-0 mt-2 w-56 bg-white dark:bg-boxdark border border-gray-200 dark:border-strokedark rounded-xl shadow-xl z-[100] overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                <div class="p-2 space-y-1">
                  <button @click="openActionModal('email')" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors">
                    <MailIcon class="w-4 h-4" />
                    Send Email
                  </button>
                  <button @click="openActionModal('sms')" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors">
                    <Message2Line class="w-4 h-4" />
                    Send SMS
                  </button>
                  <button @click="openActionModal('notification')" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors">
                    <BellIcon class="w-4 h-4" />
                    Send Notification
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 ml-1">Academic Year</label>
            <select 
              v-model="filters.academic_year_id"
              class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-boxdark outline-none focus:border-primary transition-all cursor-pointer text-sm font-medium"
            >
              <option value="">All Years</option>
              <option v-for="year in academicYears" :key="year.id" :value="year.id">
                {{ year.name }} {{ year.is_active ? '(Active)' : '' }}
              </option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 ml-1">Class</label>
            <select 
              v-model="filters.class_id"
              class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-boxdark outline-none focus:border-primary transition-all cursor-pointer text-sm font-medium"
            >
              <option value="">All Classes</option>
              <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 ml-1">Section</label>
            <select 
              v-model="filters.section_id"
              class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-boxdark outline-none focus:border-primary transition-all cursor-pointer text-sm font-medium"
            >
              <option value="">All Sections</option>
              <option v-for="sec in sections" :key="sec.id" :value="sec.id">Section {{ sec.name }}</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 ml-1">Status</label>
            <select 
              v-model="filters.status"
              class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-boxdark outline-none focus:border-primary transition-all cursor-pointer text-sm font-medium"
            >
              <option value="">All Status</option>
              <option value="active">Active Only</option>
              <option value="withdrawn">Withdrawn</option>
              <option value="graduated">Graduated</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Students Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm overflow-hidden mb-6">
      <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-meta-4">
          <tr>
            <th class="px-4 py-4 w-10">
              <input 
                type="checkbox" 
                :checked="isAllSelected" 
                @change="toggleSelectAll"
                class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
              />
            </th>
            <th class="px-4 py-4 text-sm font-medium text-gray-500 uppercase w-16">SN</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Student Info</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Class & Roll</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase">Parent Contact</th>
            <th class="px-6 py-4 text-sm font-medium text-gray-500 uppercase text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-strokedark">
          <tr v-if="loading && students.length === 0">
            <td colspan="7" class="px-6 py-10 text-center">
              <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm text-gray-500">Loading students...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="!loading && students.length === 0">
            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No students found.</td>
          </tr>
          <tr v-else v-for="(student, index) in students" :key="student.id" 
            :class="{ 'bg-primary/5': selectedIds.includes(student.id) }"
            class="hover:bg-gray-50 dark:hover:bg-meta-4 transition"
          >
            <td class="px-4 py-4">
              <input 
                type="checkbox" 
                v-model="selectedIds" 
                :value="student.id"
                class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
              />
            </td>
            <td class="px-4 py-4 text-sm font-medium text-gray-400">
              {{ (pagination.current_page - 1) * 15 + index + 1 }}
            </td>
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
      
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <Pagination :meta="pagination" @change="fetchStudents" />
      </div>
    </div>
  </div>

    <!-- Enrollment Wizard Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-[99] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4 font-outfit">
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

              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Email Address</label>
                <input v-model="form.email" type="email" placeholder="student@example.com" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600" required />
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
                <flat-pickr v-model="form.date_of_birth_ad" :config="flatpickrConfig" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" placeholder="YYYY-MM-DD" required />
              </div>
              <div class="nepali-datepicker-wrapper">
                <label class="block text-sm font-medium mb-1.5 text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[10px]">Date of Birth (BS)</label>
                <VNepaliDatePicker v-model="dobAsDate" class="vue-nepali-datepicker" placeholder="YYYY-MM-DD" />
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
                {{ loading ? 'Enrolling...' : $t('common.save') }}
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
               <option value="active">{{ $t('status.active') }}</option>
               <option value="withdrawn">Withdrawn</option>
               <option value="graduated">Graduated</option>
             </select>
           </div>
           <div v-if="statusForm.status !== 'active'">
             <label class="block text-sm font-medium mb-1 dark:text-white">Reason</label>
             <textarea v-model="statusForm.reason" class="w-full px-4 py-2 border rounded dark:bg-form-input text-black dark:text-white" required></textarea>
           </div>
           <div class="flex justify-end gap-2 mt-6">
             <button type="button" @click="showStatusModal = false" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">{{ $t('common.cancel') }}</button>
             <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50 font-semibold">
               Update Status
             </button>
           </div>
         </form>
      </div>
    </div>

    <!-- Bulk Action Message Modal -->
    <div v-if="showMsgModal" class="fixed inset-0 z-[100000] flex items-center justify-center bg-gray-400/20 backdrop-blur-[32px] p-4">
      <div class="bg-white dark:bg-boxdark w-full max-w-2xl rounded-2xl shadow-2xl text-gray-900 dark:text-white overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b dark:border-strokedark">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-primary/10 text-primary rounded-lg">
              <svg v-if="currAction === 'email'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
              <svg v-else-if="currAction === 'sms'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
            <h3 class="text-lg font-bold capitalize">Send {{ currAction }}</h3>
          </div>
          <button @click="showMsgModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors text-2xl">&times;</button>
        </div>
        
        <div class="p-6 space-y-6">
          <!-- Recipients chips -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Recipients ({{ selectedStudents.length }})</label>
            <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto p-3 border border-gray-100 dark:border-strokedark rounded-xl bg-gray-50/50 dark:bg-gray-900/50">
              <span v-for="s in selectedStudents" :key="s.id" class="px-3 py-1 bg-white dark:bg-boxdark border border-gray-200 dark:border-strokedark rounded-full text-xs font-medium shadow-sm">
                {{ s.name }}
              </span>
            </div>
          </div>

          <!-- Form -->
          <div class="space-y-4">
            <div v-if="currAction === 'email'">
              <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Subject</label>
              <input v-model="msgForm.subject" type="text" placeholder="Enter email subject" class="w-full px-4 py-3 border border-gray-200 dark:border-strokedark rounded-xl dark:bg-gray-900 focus:outline-none focus:border-primary" />
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Message Body</label>
              <textarea v-model="msgForm.body" rows="6" placeholder="Type your message here..." class="w-full px-4 py-3 border border-gray-200 dark:border-strokedark rounded-xl dark:bg-gray-900 focus:outline-none focus:border-primary resize-none"></textarea>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t dark:border-strokedark flex justify-end gap-3">
          <button @click="showMsgModal = false" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
          <button @click="sendBulkMsg" :disabled="loading || !msgForm.body" class="px-8 py-2.5 bg-primary text-white rounded-xl shadow-lg shadow-primary/30 hover:bg-opacity-90 disabled:opacity-50 font-bold transition-all flex items-center gap-2">
            <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Send Now
          </button>
        </div>
      </div>
    </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import _ from 'lodash'
import MainLayout from '@/components/layout/MainLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import FlatPickr from 'vue-flatpickr-component'
import { PlusIcon, ChevronDownIcon, MailIcon, Message2Line, BellIcon } from '@/icons'
import 'flatpickr/dist/flatpickr.css'
import { VNepaliDatePicker } from 'vue-nepali-date-picker'
import 'vue-nepali-date-picker/dist/style.css'
import NepaliDate from 'nepali-date-converter'

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
const selectedIds = ref<string[]>([])
const showActionsDropdown = ref(false)
const showMsgModal = ref(false)
const currAction = ref('')
const msgForm = ref({ subject: '', body: '' })

const selectedStudents = computed(() => {
  return students.value.filter(s => selectedIds.value.includes(s.id))
})

const isAllSelected = computed(() => {
  return students.value.length > 0 && selectedIds.value.length === students.value.length
})

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedIds.value = []
  } else {
    selectedIds.value = students.value.map(s => s.id)
  }
}

const openActionModal = (action: string) => {
  if (selectedIds.value.length === 0) {
    Swal.fire({
      title: 'No Selection',
      text: 'Please select at least one student to perform this action.',
      icon: 'warning',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
    return
  }
  currAction.value = action
  showActionsDropdown.value = false
  msgForm.value = { subject: '', body: '' }
  showMsgModal.value = true
}

const sendBulkMsg = async () => {
  loading.value = true
  try {
    await axios.post('/api/students/bulk-message', {
      student_ids: selectedIds.value,
      type: currAction.value,
      ...msgForm.value
    })
    showMsgModal.value = false
    Swal.fire({
      title: 'Sent!',
      text: `Your ${currAction.value} has been queued for delivery.`,
      icon: 'success',
      timer: 2000,
      showConfirmButton: false,
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } catch (error: any) {
    Swal.fire({
      title: 'Failed',
      text: error.response?.data?.message || 'Failed to send message.',
      icon: 'error',
      background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
      color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
    })
  } finally {
    loading.value = false
  }
}

const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 1,
  to: 1,
  total: 0
})

const savedFilters = sessionStorage.getItem('student_filters')
const filters = ref(savedFilters ? JSON.parse(savedFilters) : {
  q: '',
  academic_year_id: '',
  class_id: '',
  section_id: '',
  status: 'active',
  page: 1
})

watch(filters, (newVal) => {
  sessionStorage.setItem('student_filters', JSON.stringify(newVal))
}, { deep: true })

const initialFormStatus = {
  status: 'active',
  reason: ''
}

const initialForm = {
  name: '',
  email: '',
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
const isSyncing = ref(false)

const dobAsDate = computed({
  get: () => {
    if (!form.value.date_of_birth_ad) return null
    const [y, m, d] = form.value.date_of_birth_ad.split('-').map(Number)
    return new Date(y, m - 1, d)
  },
  set: (val) => {
    if (val instanceof Date && !isNaN(val.getTime())) {
      const y = val.getFullYear()
      const m = String(val.getMonth() + 1).padStart(2, '0')
      const d = String(val.getDate()).padStart(2, '0')
      form.value.date_of_birth_ad = `${y}-${m}-${d}`
    } else {
      form.value.date_of_birth_ad = ''
    }
  }
})

const flatpickrConfig = {
  allowInput: true,
  dateFormat: 'Y-m-d',
}

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

// Auto-sync AD to BS
watch(() => form.value.date_of_birth_ad, (newAd) => {
  if (isSyncing.value || !newAd) return
  isSyncing.value = true
  try {
    const [y, m, d] = newAd.split('-').map(Number)
    if (y && m && d) {
      const nDate = new NepaliDate(y, m - 1, d)
      form.value.date_of_birth_bs = nDate.format('YYYY-MM-DD')
    }
  } catch (e) {
    console.error('AD to BS conversion failed', e)
  }
  setTimeout(() => isSyncing.value = false, 50)
})

// Auto-sync BS to AD
watch(() => form.value.date_of_birth_bs, (newBs) => {
  if (isSyncing.value || !newBs || newBs.length < 10) return
  isSyncing.value = true
  try {
    const [y, m, d] = newBs.split('-').map(Number)
    if (y && m && d) {
        const nDate = new NepaliDate(y, m - 1, d)
        const adDate = nDate.toJsDate()
        const ay = adDate.getFullYear()
        const am = String(adDate.getMonth() + 1).padStart(2, '0')
        const ad = String(adDate.getDate()).padStart(2, '0')
        form.value.date_of_birth_ad = `${ay}-${am}-${ad}`
    }
  } catch (e) {
    console.error('BS to AD conversion failed', e)
  }
  setTimeout(() => isSyncing.value = false, 50)
})

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

const academicYears = ref<any[]>([])
const activeYearId = ref('')

const fetchAcademicYears = async () => {
    try {
        const res = await axios.get('/api/academic-years', { params: { per_page: -1 } })
        academicYears.value = res.data.data || res.data
        const active = academicYears.value.find((y: any) => y.is_active)
        if (active) {
            activeYearId.value = active.id
            if (!filters.value.academic_year_id) {
                filters.value.academic_year_id = active.id
            }
        }
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

const debouncedFetchStudents = _.debounce(() => {
  selectedIds.value = []
  fetchStudents(1)
}, 500)
watch(filters, () => debouncedFetchStudents(), { deep: true })

onMounted(async () => {
  await fetchAcademicYears()
  await fetchClasses()
  await fetchSections()
  await fetchStudents()
})

const viewProfile = (student: Student) => console.log('View profile:', student)
const editStudent = (student: Student) => console.log('Edit student:', student)
</script>

<style scoped>
.nepali-datepicker-wrapper :deep(.vue-nepali-datepicker) {
  width: 100% !important;
}

.nepali-datepicker-wrapper :deep(input) {
  width: 100% !important;
  padding: 0.625rem 1rem !important;
  border-radius: 0.5rem !important;
  border: 1px solid #e5e7eb !important;
  background-color: #fff !important;
  color: #111827 !important;
  outline: none !important;
  transition: all 0.2s !important;
}

:deep(.dark) .nepali-datepicker-wrapper :deep(input) {
  border-color: #374151 !important;
  background-color: #1f2937 !important;
  color: #fff !important;
}

.nepali-datepicker-wrapper :deep(input:focus) {
  border-color: #465fff !important;
  box-shadow: 0 0 0 4px rgba(70, 95, 255, 0.1) !important;
}

/* Calendar Popup Styles */
:deep(.v-nepali-datepicker-container) {
  z-index: 999999 !important;
  border-radius: 0.75rem !important;
  box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
  border: 1px solid #e5e7eb !important;
  margin-top: 0.5rem !important;
}

:deep(.dark) :deep(.v-nepali-datepicker-container) {
  background-color: #1f2937 !important;
  border-color: #374151 !important;
  color: #fff !important;
}

:deep(.v-nepali-datepicker-calendar-day.selected) {
  background-color: #465fff !important;
  color: white !important;
}

:deep(.v-nepali-datepicker-calendar-day:hover:not(.selected)) {
  background-color: #f3f4f6 !important;
}

:deep(.dark) :deep(.v-nepali-datepicker-calendar-day:hover:not(.selected)) {
  background-color: #374151 !important;
}
</style>

