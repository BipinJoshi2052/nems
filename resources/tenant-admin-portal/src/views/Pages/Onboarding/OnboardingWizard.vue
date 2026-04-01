<template>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-3xl w-full">
      <!-- Header -->
      <div class="text-center mb-12">
        <img class="mx-auto h-12 w-auto mb-4" src="/images/logo/logo-icon.svg" alt="NEMS Logo" />
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Welcome to NEMS</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Let's get your school set up in a few simple steps.
        </p>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-8 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
        <div 
          class="h-2 bg-brand-500 transition-all duration-500 ease-in-out" 
          :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
        ></div>
      </div>

      <!-- Step Counter -->
      <div class="flex justify-between items-center mb-6 px-1">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
          Step {{ currentStep }} of {{ totalSteps }}
        </span>
        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
          {{ Math.round((currentStep / totalSteps) * 100) }}% Complete
        </span>
      </div>

      <!-- Step Content Container -->
      <div class="bg-white px-8 py-10 rounded-2xl shadow-theme-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
        <component 
          :is="currentStepComponent" 
          @next="nextStep" 
          @back="prevStep" 
          :state="wizardState"
        />
      </div>

      <!-- Footer Help -->
      <p class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
        Need help? Contact our support at support@nems.edu.np
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SchoolInfoStep from '@/components/onboarding/SchoolInfoStep.vue'
import AcademicYearStep from '@/components/onboarding/AcademicYearStep.vue'
import SubjectsStep from '@/components/onboarding/SubjectsStep.vue'
import ClassesStep from '@/components/onboarding/ClassesStep.vue'
import InviteTeachersStep from '@/components/onboarding/InviteTeachersStep.vue'
import AddFirstStudentStep from '@/components/onboarding/AddFirstStudentStep.vue'

const currentStep = ref(1)
const totalSteps = 6
const wizardState = ref({})

const steps = [
  SchoolInfoStep,
  AcademicYearStep,
  SubjectsStep,
  ClassesStep,
  InviteTeachersStep,
  AddFirstStudentStep
]

const currentStepComponent = computed(() => steps[currentStep.value - 1])

const nextStep = (stepData: any) => {
  // Merge step data into overall state
  wizardState.value = { ...wizardState.value, ...stepData }
  
  if (currentStep.value < totalSteps) {
    currentStep.value++
    saveProgress()
  } else {
    completeSetup()
  }
}

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const saveProgress = async () => {
  try {
    await axios.post('/api/setup/save-progress', {
      step: currentStep.value,
      state: wizardState.value
    })
  } catch (err) {
    console.error('Failed to save progress', err)
  }
}

const fetchProgress = async () => {
  try {
    const { data } = await axios.get('/api/setup/progress')
    if (data.step) {
      currentStep.value = data.step
      wizardState.value = data.state || {}
    }
  } catch (err) {
    console.error('Failed to fetch progress', err)
  }
}

const completeSetup = async () => {
  try {
    await axios.patch('/api/setup/complete')
    window.location.href = '/dashboard'
  } catch (err) {
    console.error('Setup completion failed', err)
  }
}

onMounted(() => {
  fetchProgress()
})
</script>
