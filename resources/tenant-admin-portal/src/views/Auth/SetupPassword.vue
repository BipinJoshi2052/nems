<template>
  <FullScreenLayout>
    <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
      <div class="relative flex flex-col justify-center w-full h-screen lg:flex-row dark:bg-gray-900">
        <div class="flex flex-col flex-1 w-full lg:w-1/2">
          <div class="w-full max-w-md pt-10 mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Setup Your Account</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Please choose a secure password for your new account.</p>
          </div>

          <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Password -->
              <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  New Password<span class="text-error-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    placeholder="Min. 8 characters"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    required
                  />
                  <span @click="showPassword = !showPassword" class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400">
                    <svg v-if="!showPassword" class="fill-current w-5 h-5" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 13.862c-2.767 0-5.132-1.725-6.077-4.16.945-2.434 3.31-4.159 6.077-4.159s5.132 1.725 6.077 4.16c-.945 2.434-3.31 4.159-6.077 4.159zm0-9.82c-3.518 0-6.505 2.266-7.585 5.416a.916.916 0 00.015.486c1.08 3.15 4.067 5.416 7.585 5.416s6.505-2.266 7.585-5.416a.916.916 0 00-.015-.486c-1.08-3.15-4.067-5.416-7.585-5.416zm-.008 3.801c-1.027 0-1.859.832-1.859 1.859s.832 1.859 1.859 1.859h.015c1.026 0 1.858-.832 1.858-1.859s-.832-1.859-1.858-1.859h-.015z" fill="#98A2B3"/></svg>
                    <svg v-else class="fill-current w-5 h-5" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.638 3.577a.75.75 0 00-1.06 1.06l1.276 1.277c-1.107.928-1.96 2.15-2.438 3.545a.917.917 0 00.015.486c1.08 3.15 4.067 5.416 7.585 5.416 1.255 0 2.442-.288 3.499-.802l1.863 1.863a.75.75 0 101.06-1.06l-11.785-11.785zm7.723 9.844l-1.913-1.913c-.142.034-.29.053-.441.053h-.015c-1.026 0-1.858-.832-1.858-1.858 0-.157.02-.308.056-.453L5.919 6.98c-.884.71-1.578 1.647-1.996 2.723.945 2.435 3.31 4.16 6.077 4.16.832 0 1.629-.156 2.36-.441zm3.716-3.719a8.55 8.55 0 00-1.257-2.028l1.061 1.061c.752-.811 1.338-1.778 1.704-2.847a.917.917 0 00-.015-.486c-1.08-3.15-4.067-5.416-7.585-5.416-.865 0-1.698.137-2.478.39l1.229 1.229c.404-.078.822-.12 1.25-.12 2.766 0 5.132 1.725 6.076 4.16l.015.048z" fill="#98A2B3"/></svg>
                  </span>
                </div>
              </div>

              <!-- Confirm Password -->
              <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Confirm Password<span class="text-error-500">*</span>
                </label>
                <input
                  v-model="password_confirmation"
                  type="password"
                  id="password_confirmation"
                  placeholder="Re-enter password"
                  class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                  required
                />
              </div>

              <!-- Button -->
              <div>
                <button
                  type="submit"
                  :disabled="loading"
                  class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 disabled:opacity-50"
                >
                  <span v-if="loading" class="mr-2">
                    <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                  </span>
                  Set Password
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </FullScreenLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'
import FullScreenLayout from '@/components/layout/FullScreenLayout.vue'

const route = useRoute()
const router = useRouter()

const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)
const showPassword = ref(false)

const handleSubmit = async () => {
    if (password.value !== password_confirmation.value) {
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Passwords do not match!' })
        return
    }

    loading.value = true
    try {
        const queryParams = new URLSearchParams(window.location.search);
        
        await axios.post(`/api/auth/setup-password`, {
            token: route.params.token,
            email: queryParams.get('email'),
            password: password.value,
            password_confirmation: password_confirmation.value
        })

        await Swal.fire({
            icon: 'success',
            title: 'Account Secured!',
            text: 'Your password has been set. You can now log in.',
            timer: 3000
        })

        router.push('/signin')
    } catch (err: any) {
        const message = err.response?.data?.message || 'Failed to set password. Link may be invalid or expired.'
        Swal.fire({ icon: 'error', title: 'Error', text: message })
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    const queryParams = new URLSearchParams(window.location.search);
    if (!route.params.token || !queryParams.get('email')) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Link',
            text: 'This setup link is missing required authorization parameters.'
        })
    }
})
</script>
