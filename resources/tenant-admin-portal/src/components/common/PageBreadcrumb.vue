<template>
  <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageTitle">
      {{ pageTitle }}
    </h2>
    <nav>
      <ol class="flex items-center gap-1.5 text-sm">
        <li>
          <router-link
            class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary transition-colors"
            to="/"
          >
            Home
            <svg
              class="stroke-current mt-0.5"
              width="14"
              height="14"
              viewBox="0 0 17 16"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </router-link>
        </li>
        <li v-for="(item, index) in items" :key="index" class="flex items-center gap-1.5">
          <router-link
            v-if="item.route"
            class="text-gray-500 dark:text-gray-400 hover:text-primary transition-colors"
            :to="item.route"
          >
            {{ item.label }}
          </router-link>
          <span v-else class="text-gray-500 dark:text-gray-400">{{ item.label }}</span>
          
          <svg
            class="stroke-current mt-0.5 text-gray-400"
            width="14"
            height="14"
            viewBox="0 0 17 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </li>
        <li class="text-gray-800 dark:text-white/90 font-medium">
          {{ pageTitle }}
        </li>
      </ol>
    </nav>
  </div>
</template>

<script setup lang="ts">
interface BreadcrumbItem {
  label: string
  route?: string | object
}

interface BreadcrumbProps {
  pageTitle: string
  items?: BreadcrumbItem[]
}

defineProps<BreadcrumbProps>()
</script>
