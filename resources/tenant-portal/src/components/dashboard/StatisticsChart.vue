<template>
  <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
      <div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Statistics</h3>
        <p class="text-xs text-slate-500 font-medium tracking-wide">Target you've set for each month</p>
      </div>
      <div class="flex items-center space-x-2 bg-slate-50 dark:bg-slate-900 p-1 rounded-lg border border-slate-200 dark:border-slate-700">
        <button
          v-for="mode in ['Monthly', 'Quarterly', 'Annually']"
          :key="mode"
          @click="$emit('update:mode', mode)"
          :class="[
            'px-3 py-1 rounded-md text-xs font-bold transition',
            currentMode === mode ? 'bg-slate-900 text-white dark:bg-blue-600 dark:text-white' : 'text-slate-500 hover:bg-slate-200 dark:text-slate-400 dark:hover:bg-slate-800'
          ]"
        >
          {{ mode }}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-4">
      <div class="flex flex-col">
        <div class="flex items-center space-x-2 mb-1">
          <span class="text-2xl font-bold text-slate-900 dark:text-white">$212,142.12</span>
          <span class="bg-green-100 text-green-700 text-[10px] px-1.5 py-0.5 rounded-full font-bold">+23.2%</span>
        </div>
        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest italic">Avg. Yearly Profit</p>
      </div>
      <div class="flex flex-col">
        <div class="flex items-center space-x-2 mb-1">
          <span class="text-2xl font-bold text-slate-900 dark:text-white">$30,321.23</span>
          <span class="bg-red-100 text-red-700 text-[10px] px-1.5 py-0.5 rounded-full font-bold">-12.3%</span>
        </div>
        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest italic">Avg. Yearly Profit</p>
      </div>
    </div>

    <div class="h-80 w-full mt-4">
      <apexchart
        type="area"
        height="100%"
        :options="chartOptions"
        :series="series"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  currentMode: String,
  data: Object,
  isDarkMode: Boolean
});

defineEmits(['update:mode']);

const series = computed(() => [
  { name: 'Revenue', data: props.data.series1 },
  { name: 'Profit', data: props.data.series2 }
]);

const chartOptions = computed(() => ({
  chart: {
    fontFamily: 'Inter, sans-serif',
    toolbar: { show: false },
    sparkline: { enabled: false },
    background: 'transparent'
  },
  theme: {
    mode: props.isDarkMode ? 'dark' : 'light'
  },
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: 3 },
  xaxis: {
    categories: props.data.labels,
    labels: {
      style: { colors: props.isDarkMode ? '#94a3b8' : '#64748b', fontSize: '10px' }
    }
  },
  yaxis: {
    labels: {
      style: { colors: props.isDarkMode ? '#94a3b8' : '#64748b', fontSize: '10px' }
    }
  },
  grid: {
    borderColor: props.isDarkMode ? '#334155' : '#e2e8f0',
    strokeDashArray: 4
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.45,
      opacityTo: 0.05,
      stops: [50, 100]
    }
  },
  colors: ['#3b82f6', '#8b5cf6'],
  legend: { show: false }
}));
</script>
