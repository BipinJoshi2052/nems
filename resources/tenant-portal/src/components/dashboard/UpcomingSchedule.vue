<template>
  <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 h-full">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-bold text-slate-900 dark:text-white">Upcoming Schedule</h3>
      <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
        <i class="bi bi-three-dots"></i>
      </button>
    </div>

    <div class="space-y-6">
      <div v-for="(item, index) in scheduleItems" :key="index" class="flex items-start space-x-4">
        <div class="pt-1">
          <input
            type="checkbox"
            v-model="item.completed"
            class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600"
          >
        </div>
        <div class="flex-1">
          <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
            <span>{{ item.date }}</span>
            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
            <span class="text-slate-600 dark:text-slate-300 font-black">{{ item.time }}</span>
          </div>
          <h4 :class="['text-sm font-bold text-slate-900 dark:text-white transition-all', item.completed ? 'line-through opacity-50' : '']">
            {{ item.title }}
          </h4>
          <p class="text-xs text-slate-500 mt-1">
            {{ item.description }} <span class="text-blue-500 font-bold cursor-pointer">+{{ item.more }} more</span>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const scheduleItems = ref([
  {
    date: 'Wed, 11 jan',
    time: '09:20 AM',
    title: 'Business Analytics Press',
    description: 'Exploring the Future of Data-Driven',
    more: 6,
    completed: false
  },
  {
    date: 'Fri, 15 feb',
    time: '10:35 AM',
    title: 'Business Sprint',
    description: 'Techniques from Business Sprint',
    more: 2,
    completed: false
  },
  {
    date: 'Thu, 18 mar',
    time: '01:15 AM',
    title: 'Customer Review Meeting',
    description: 'Insights from the Customer Review Meeting',
    more: 8,
    completed: false
  }
]);
</script>
