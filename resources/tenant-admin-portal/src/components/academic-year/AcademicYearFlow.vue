<template>
  <div class="space-y-6">
    <!-- Sub-step Progress -->
    <div class="flex items-center space-x-2 mb-8">
      <div v-for="s in 3" :key="s" class="flex-1 h-1 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
        <div v-if="currentSubStep >= s" class="h-full bg-brand-500 transition-all duration-300"></div>
      </div>
    </div>

    <component 
      :is="currentSubStepComponent" 
      :data="flowData"
      @next="nextSubStep" 
      @back="prevSubStep"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import YearDetails from './YearDetails.vue'
import ClassSetup from './ClassSetup.vue'
import ConfirmSummary from './ConfirmSummary.vue'

const emit = defineEmits(['next', 'back'])

const currentSubStep = ref(1)
const flowData = ref({
  year: '',
  startDate: '',
  endDate: '',
  terms: 3,
  classes: [] as any[],
  assignments: [] as any[]
})

const subSteps = [
  YearDetails,
  ClassSetup,
  ConfirmSummary
]

const currentSubStepComponent = computed(() => subSteps[currentSubStep.value - 1])

const nextSubStep = (stepData: any) => {
  flowData.value = { ...flowData.value, ...stepData }
  if (currentSubStep.value < 3) {
    currentSubStep.value++
  } else {
    emit('next', flowData.value)
  }
}

const prevSubStep = () => {
  if (currentSubStep.value > 1) {
    currentSubStep.value--
  } else {
    emit('back')
  }
}
</script>
