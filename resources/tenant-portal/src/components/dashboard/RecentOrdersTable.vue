<template>
  <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-700">
      <h3 class="text-lg font-bold text-slate-900 dark:text-white">Recent Orders</h3>
      <div class="flex items-center space-x-3 w-full sm:w-auto">
        <div class="relative flex-1 sm:w-64">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 outline-none transition dark:text-white"
          >
        </div>
        <button class="flex items-center space-x-2 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
          <i class="bi bi-filter"></i>
          <span>Filter</span>
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
            <th class="py-4 px-6">
              <input
                type="checkbox"
                :checked="isAllSelected"
                @change="toggleSelectAll"
                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500"
              >
            </th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deal ID</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Product/Service</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deal Value</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Close Date</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
            <th class="py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="deal in filteredDeals"
            :key="deal.id"
            :class="[
              'border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition',
              selectedDeals.includes(deal.id) ? 'bg-blue-50/30 dark:bg-blue-900/10' : ''
            ]"
          >
            <td class="py-4 px-6">
              <input
                type="checkbox"
                v-model="selectedDeals"
                :value="deal.id"
                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500"
              >
            </td>
            <td class="py-4 px-4 text-xs font-bold text-slate-900 dark:text-slate-100">#{{ deal.id }}</td>
            <td class="py-4 px-4">
              <div class="flex items-center space-x-3">
                <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-black text-white', deal.avatarColor]">
                  {{ deal.initials }}
                </div>
                <div>
                  <p class="text-xs font-bold text-slate-900 dark:text-white leading-none mb-1">{{ deal.customer }}</p>
                  <p class="text-[10px] text-slate-400 tracking-tight">{{ deal.email }}</p>
                </div>
              </div>
            </td>
            <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-300">{{ deal.product }}</td>
            <td class="py-4 px-4 text-xs font-bold text-slate-900 dark:text-white">{{ deal.value }}</td>
            <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400">{{ deal.date }}</td>
            <td class="py-4 px-4">
              <span :class="[
                'inline-flex px-2 py-1 rounded-full text-[10px] font-bold',
                deal.status === 'Complete' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
              ]">
                {{ deal.status }}
              </span>
            </td>
            <td class="py-4 px-4 text-right">
              <button
                @click="deleteDeal(deal.id)"
                class="text-slate-400 hover:text-red-500 transition-colors p-1"
              >
                <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const searchQuery = ref('');
const selectedDeals = ref([]);

const deals = ref([
  { id: 'DE124321', customer: 'John Doe', email: 'johndoe@gmail.com', initials: 'JD', avatarColor: 'bg-purple-500', product: 'Software License', value: '$18,50.34', date: '2024-06-15', status: 'Complete' },
  { id: 'DE124322', customer: 'Kierra Franci', email: 'kierra@gmail.com', initials: 'KF', avatarColor: 'bg-pink-500', product: 'Software License', value: '$18,50.34', date: '2024-06-15', status: 'Complete' },
  { id: 'DE124323', customer: 'Emerson Workman', email: 'emerson@gmail.com', initials: 'EW', avatarColor: 'bg-teal-500', product: 'Software License', value: '$18,50.34', date: '2024-06-15', status: 'Pending' },
  { id: 'DE124324', customer: 'Chance Philips', email: 'chance@gmail.com', initials: 'CP', avatarColor: 'bg-orange-500', product: 'Software License', value: '$18,50.34', date: '2024-06-15', status: 'Complete' },
  { id: 'DE124325', customer: 'Terry Geidt', email: 'terry@gmail.com', initials: 'TG', avatarColor: 'bg-green-500', product: 'Software License', value: '$18,50.34', date: '2024-06-15', status: 'Complete' },
]);

const filteredDeals = computed(() => {
  if (!searchQuery.value) return deals.value;
  const q = searchQuery.value.toLowerCase();
  return deals.value.filter(d =>
    d.customer.toLowerCase().includes(q) ||
    d.id.toLowerCase().includes(q) ||
    d.email.toLowerCase().includes(q)
  );
});

const isAllSelected = computed(() => {
  return filteredDeals.value.length > 0 && selectedDeals.value.length === filteredDeals.value.length;
});

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedDeals.value = [];
  } else {
    selectedDeals.value = filteredDeals.value.map(d => d.id);
  }
};

const deleteDeal = (id) => {
  deals.value = deals.value.filter(d => d.id !== id);
  selectedDeals.value = selectedDeals.value.filter(sid => sid !== id);
};
</script>
