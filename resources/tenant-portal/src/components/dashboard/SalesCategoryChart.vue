<template>
  <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-bold text-slate-900 dark:text-white">Sales Category</h3>
      <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
        <i class="bi bi-three-dots"></i>
      </button>
    </div>

    <div class="flex flex-col lg:flex-row items-center gap-8">
      <div class="relative w-64 h-64">
        <apexchart
          type="donut"
          height="100%"
          :options="chartOptions"
          :series="[48, 33, 19]"
        />
        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
          <span class="text-xs text-slate-400 font-bold uppercase tracking-tight">Total</span>
          <span class="text-xl font-extrabold text-slate-900 dark:text-white">3.5K</span>
        </div>
      </div>
      
      <div class="flex-1 space-y-4 w-full">
        <div v-for="(item, index) in legendItems" :key="item.label" class="flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-100 dark:border-slate-700">
          <div class="flex items-center space-x-3">
            <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: colors[index] }"></div>
            <div>
              <p class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ item.label }}</p>
              <p class="text-[10px] text-slate-400">{{ item.total }} Products</p>
            </div>
          </div>
          <span class="text-xs font-black text-slate-900 dark:text-white">{{ item.percentage }}%</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  isDarkMode: Boolean
});

const colors = ['#1e40af', '#3b82f6', '#93c5fd'];

const legendItems = [
  { label: 'Affiliate Program', percentage: 48, total: '2,040' },
  { label: 'Direct Buy', percentage: 33, total: '1,402' },
  { label: 'Adsense', percentage: 19, total: '510' }
];

const chartOptions = computed(() => ({
  chart: {
    fontFamily: 'Inter, sans-serif'
  },
  theme: {
    mode: props.isDarkMode ? 'dark' : 'light'
  },
  plotOptions: {
    pie: {
      donut: {
        size: '80%',
        background: 'transparent'
      }
    }
  },
  dataLabels: { enabled: false },
  legend: { show: false },
  colors: colors,
  stroke: { show: false },
  tooltip: {
    enabled: true,
    y: { formatter: (val) => val + '%' }
  }
}));
</script>
