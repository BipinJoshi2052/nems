<template>
  <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Estimated Revenue</h3>
        <p class="text-xs text-slate-500 font-medium">Target you've set for each month</p>
      </div>
      <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
        <i class="bi bi-three-dots"></i>
      </button>
    </div>

    <div class="relative h-64">
      <apexchart
        type="radialBar"
        height="100%"
        :options="chartOptions"
        :series="[85]"
      />
      <div class="absolute inset-0 flex flex-col items-center justify-center pt-8">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-tight">June Goals</span>
        <span class="text-3xl font-extrabold text-slate-900 dark:text-white">$90</span>
      </div>
    </div>

    <div class="space-y-4 mt-4">
      <div v-for="item in progressItems" :key="item.label" class="space-y-1">
        <div class="flex justify-between text-xs font-bold">
          <span class="text-slate-900 dark:text-slate-200">{{ item.label }}</span>
          <span class="text-slate-400">{{ item.value }}</span>
        </div>
        <div class="h-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div
            class="h-full bg-blue-500 rounded-full transition-all duration-1000"
            :style="{ width: item.percentage + '%' }"
          ></div>
        </div>
        <div class="text-[10px] text-right font-bold text-slate-400">{{ item.percentage }}%</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  isDarkMode: Boolean
});

const progressItems = [
  { label: 'Marketing', value: '$30,569.00', percentage: 85 },
  { label: 'Sales', value: '$20,486.00', percentage: 55 }
];

const chartOptions = computed(() => ({
  chart: {
    fontFamily: 'Inter, sans-serif',
    sparkline: { enabled: true }
  },
  theme: {
    mode: props.isDarkMode ? 'dark' : 'light'
  },
  plotOptions: {
    radialBar: {
      startAngle: -135,
      endAngle: 135,
      hollow: { size: '70%', background: 'transparent' },
      track: {
        background: props.isDarkMode ? '#334155' : '#f1f5f9',
        strokeWidth: '100%',
        margin: 5
      },
      dataLabels: { show: false }
    }
  },
  fill: {
    type: 'gradient',
    gradient: {
      shade: 'dark',
      type: 'horizontal',
      shadeIntensity: 0.5,
      gradientToColors: ['#60a5fa'],
      inverseColors: true,
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100]
    }
  },
  stroke: { lineCap: 'round' },
  labels: ['Target Implementation'],
  colors: ['#3b82f6']
}));
</script>
