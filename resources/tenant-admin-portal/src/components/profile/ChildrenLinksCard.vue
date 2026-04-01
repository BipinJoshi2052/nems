<template>
  <div v-if="type === 'parent' && user.students?.length" class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
      Children Links
    </h4>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div 
        v-for="student in user.students" 
        :key="student.id" 
        @click="goToStudentProfile(student.id)"
        class="flex items-center gap-4 p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:shadow-md transition-shadow cursor-pointer group"
      >
        <img v-if="student.thumbnail" :src="student.thumbnail.url" class="w-14 h-14 rounded-full object-cover border-2 border-primary/20 group-hover:border-primary transition-colors" />
        <div v-else class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold group-hover:bg-primary group-hover:text-white transition-all">
          {{ student.name.charAt(0) }}
        </div>
        
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors">{{ student.name }}</p>
          <p class="text-[10px] text-gray-500 font-medium">
            {{ student.enrollments?.[0]?.class?.name || 'N/A' }} • {{ student.enrollments?.[0]?.section?.name || 'N/A' }}
          </p>
          <div class="mt-1 flex items-center gap-1.5">
            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-[9px] font-bold rounded uppercase tracking-wider text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
              {{ student.pivot?.relationship || 'Parent' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const props = defineProps({
  user: { type: Object, required: true },
  type: { type: String, required: true }
})

const router = useRouter()

const goToStudentProfile = (id) => {
  router.push({ 
    name: 'user-profile', 
    params: { type: 'student', id }, 
    query: { from: 'parent', parentId: props.user.id, parentName: props.user.name } 
  })
}
</script>
