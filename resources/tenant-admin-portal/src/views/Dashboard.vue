<template>
  <MainLayout>
    <div v-if="authStore.user?.role === 'admin'" class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12 space-y-6 xl:col-span-7">
        <ecommerce-metrics />
        <monthly-target />
      </div>
      <div class="col-span-12 xl:col-span-5">
        <monthly-sale />
      </div>
      <div class="col-span-12">
        <statistics-chart />
      </div>
      <div class="col-span-12 xl:col-span-5">
        <customer-demographic />
      </div>
      <div class="col-span-12 xl:col-span-7">
        <recent-orders />
      </div>
    </div>
    
    <div v-else-if="authStore.user?.role === 'teacher'" class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12">
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
          <h2 class="text-xl font-bold mb-4">Teacher Dashboard</h2>
          <p class="text-gray-500">My Classes, Today's Attendance, Pending Homework reviews...</p>
        </div>
      </div>
    </div>

    <div v-else-if="authStore.user?.role === 'parent'" class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12">
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
          <h2 class="text-xl font-bold mb-4">Parent Portal</h2>
          <p class="text-gray-500">Children Summary, Pending Fees, Latest Daily Journal...</p>
        </div>
      </div>
    </div>

    <div v-else class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12">
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
          <h2 class="text-xl font-bold mb-4">Dashboard</h2>
          <p class="text-gray-500">Welcome, {{ authStore.user?.name }}.</p>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/components/layout/MainLayout.vue'

import EcommerceMetrics from '../components/ecommerce/EcommerceMetrics.vue'
import MonthlyTarget from '../components/ecommerce/MonthlySale.vue'
import MonthlySale from '../components/ecommerce/MonthlyTarget.vue'
import CustomerDemographic from '../components/ecommerce/CustomerDemographic.vue'
import StatisticsChart from '../components/ecommerce/StatisticsChart.vue'
import RecentOrders from '../components/ecommerce/RecentOrders.vue'

import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>
