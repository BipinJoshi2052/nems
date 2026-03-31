<template>
  <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 flex justify-between items-end">
    <div>
      <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ value }}</h3>
      <p class="text-slate-500 dark:text-slate-400 font-medium">{{ label }}</p>
    </div>
    <div class="text-right">
      <div :class="[
        'inline-flex items-center px-2 py-1 rounded-full text-xs font-bold mb-1',
        isPositive ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
      ]">
        {{ isPositive ? '+' : '' }}{{ change }}
      </div>
      <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium uppercase tracking-wider">From last month</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: String,
  change: String,
  isPositive: {
    type: Boolean,
    default: true
  }
});
</script>
