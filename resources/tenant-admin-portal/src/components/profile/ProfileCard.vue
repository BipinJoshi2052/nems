<template>
  <div>
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
      <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
        <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
          <div class="relative group">
            <div class="w-24 h-24 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
              <img 
                v-if="user.original?.url"
                :src="user.original.url" 
                @click="showFullImage"
                class="w-full h-full object-cover cursor-pointer hover:scale-110 transition-transform duration-300"
                alt="user" 
              />
              <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center text-primary text-3xl font-bold">
                {{ (user.name || 'U').charAt(0) }}
              </div>
            </div>
            <button 
              @click="$refs.fileInput.click()"
              class="absolute bottom-0 right-0 p-1.5 bg-primary text-white rounded-full shadow-lg hover:bg-primary/90 transition-all opacity-0 group-hover:opacity-100"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                <circle cx="12" cy="13" r="4"></circle>
              </svg>
            </button>
            <input 
              type="file" 
              ref="fileInput" 
              class="hidden" 
              accept="image/*"
              @change="handlePhotoUpload"
            />
          </div>
          <div class="order-3 xl:order-2">
            <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
              {{ user.name || user.user?.name }}
            </h4>
            <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.designation || user.staff?.designation || type }}</p>
              <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email || user.user?.email }}</p>
            </div>
            <div v-if="type === 'staff'" class="mt-4 flex justify-center xl:justify-start">
              <button 
                @click="toggleDeactivate" 
                class="flex items-center justify-center gap-2 rounded-full border px-4 py-3 text-sm font-medium shadow-theme-xs transition-colors dark:bg-gray-800 lg:inline-flex lg:w-auto"
                :class="user.deactivated_at ? 'text-green-600 border-green-600 hover:bg-green-50' : 'text-red-600 border-red-600 hover:bg-red-50'"
              >
                <svg v-if="!user.deactivated_at" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 11V7a5 5 0 0 1 9.9-1"></path><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M12 16v2"></path></svg>
                {{ user.deactivated_at ? 'Activate Account' : 'Deactivate Account' }}
              </button>
            </div>
          </div>
        </div>
        <div class="flex items-center order-2 gap-2 grow xl:order-3 xl:justify-end">
          <!-- Social Icons -->
          <div class="flex items-center gap-2 mr-4">
            <template v-for="social in socialLinks" :key="social.key">
              <a 
                v-if="social.url"
                :href="social.url"
                target="_blank" rel="noopener"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-400 transition-colors hover:bg-brand-500 hover:text-white dark:border-gray-800 dark:text-gray-400 dark:hover:bg-brand-500 dark:hover:text-white"
              >
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"><path :d="social.icon" fill="" /></svg>
              </a>
            </template>
          </div>
          <button 
            @click="isEditModalOpen = true"
            class="edit-button"
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Socials Modal -->
    <ProfileEditModal 
      v-if="isEditModalOpen"
      :isOpen="isEditModalOpen"
      :user="user"
      :type="type"
      mode="social"
      @close="isEditModalOpen = false"
      @refresh="$emit('refresh')"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import ProfileEditModal from './ProfileEditModal.vue'

const props = defineProps({
  user: { type: Object, required: true },
  type: { type: String, required: true }
})

const emit = defineEmits(['refresh'])

const isEditModalOpen = ref(false)

const socialLinks = computed(() => {
  const configs = [
    { key: 'facebook_url', icon: 'M11.6666 11.2503H13.7499L14.5833 7.91699H11.6666V6.25033C11.6666 5.39251 11.6666 4.58366 13.3333 4.58366H14.5833V1.78374C14.3118 1.7477 13.2858 1.66699 12.2023 1.66699C9.94025 1.66699 8.33325 3.04771 8.33325 5.58342V7.91699H5.83325V11.2503H8.33325V18.3337H11.6666V11.2503Z' },
    { key: 'x_url', icon: 'M15.1708 1.875H17.9274L11.9049 8.75833L18.9899 18.125H13.4424L9.09742 12.4442L4.12578 18.125H1.36745L7.80912 10.7625L1.01245 1.875H6.70078L10.6283 7.0675L15.1708 1.875ZM14.2033 16.475H15.7308L5.87078 3.43833H4.23162L14.2033 16.475Z' },
    { key: 'linkedin_url', icon: 'M5.78381 4.16645C5.78351 4.84504 5.37181 5.45569 4.74286 5.71045C4.11391 5.96521 3.39331 5.81321 2.92083 5.32613C2.44836 4.83904 2.31837 4.11413 2.59216 3.49323C2.86596 2.87233 3.48886 2.47942 4.16715 2.49978C5.06804 2.52682 5.78422 3.26515 5.78381 4.16645ZM5.83381 7.06645H2.50048V17.4998H5.83381V7.06645ZM11.1005 7.06645H7.78381V17.4998H11.0672V12.0248C11.0672 8.97475 15.0422 8.69142 15.0422 12.0248V17.4998H18.3338V10.8914C18.3338 5.74978 12.4505 5.94145 11.0672 8.46642L11.1005 7.06645Z' },
    { key: 'instagram_url', icon: 'M10.8567 1.66699C11.7946 1.66854 12.2698 1.67351 12.6805 1.68573L12.8422 1.69102C13.0291 1.69766 13.2134 1.70599 13.4357 1.71641C14.3224 1.75738 14.9273 1.89766 15.4586 2.10391C16.0078 2.31572 16.4717 2.60183 16.9349 3.06503C17.3974 3.52822 17.6836 3.99349 17.8961 4.54141C18.1016 5.07197 18.2419 5.67753 18.2836 6.56433C18.2935 6.78655 18.3015 6.97088 18.3081 7.15775L18.3133 7.31949C18.3255 7.73011 18.3311 8.20543 18.3328 9.1433L18.3335 9.76463C18.3336 9.84055 18.3336 9.91888 18.3336 9.99972L18.3335 10.2348L18.3336 10.8562C18.3314 11.794 18.3265 12.2694 18.3142 12.68L18.3089 12.8417C18.3023 13.0286 18.294 13.213 18.2836 13.4351C18.2426 14.322 18.1016 14.9268 17.8961 15.458C17.6842 16.0074 17.3974 16.4713 16.9349 16.9345C16.4717 17.397 16.0057 17.6831 15.4586 17.8955C14.9273 18.1011 14.3224 18.2414 13.4357 18.2831C13.2134 18.293 13.0291 18.3011 12.8422 18.3076L12.8422 18.3076L12.6805 18.3128C12.2698 18.3251 11.7946 18.3306 10.8567 18.3324L10.2353 18.333C10.1594 18.333 10.0811 18.333 10.0002 18.333H9.76516L9.14375 18.3325C8.20591 18.331 7.7306 18.326 7.31997 18.3137L7.15824 18.3085C6.97136 18.3018 6.78703 18.2935 6.56481 18.2831C5.67801 18.2421 5.07384 18.1011 4.5419 17.8955C3.99328 17.6838 3.5287 17.397 3.06551 16.9345C2.60231 16.4713 2.3169 16.0053 2.1044 15.458C1.89815 14.9268 1.75856 14.322 1.7169 13.4351C1.707 13.213 1.69892 13.0286 1.69238 12.8417L1.68714 12.68C1.67495 12.2694 1.66939 11.794 1.66759 10.8562L1.66748 9.1433C1.66903 8.20543 1.67399 7.73011 1.68621 7.31949L1.69151 7.15775C1.69815 6.97088 1.70648 6.78655 1.7169 6.56433C1.75786 5.67683 1.89815 5.07266 2.1044 4.54141C2.3162 3.9928 2.60231 3.52822 3.06551 3.06503C3.5287 2.60183 3.99398 2.31641 4.5419 2.10391C5.07315 1.89766 5.67731 1.75808 6.56481 1.71641C6.78703 1.70652 6.97136 1.69844 7.15824 1.6919L7.31997 1.68666C7.7306 1.67446 8.20591 1.6689 9.14375 1.6671L10.8567 1.66699ZM10.0002 5.83308C7.69781 5.83308 5.83356 7.69935 5.83356 9.99972C5.83356 12.3021 7.69984 14.1664 10.0002 14.1664C12.3027 14.1664 14.1669 12.3001 14.1669 9.99972C14.1669 7.69732 12.3006 5.83308 10.0002 5.83308ZM10.0002 7.49974C11.381 7.49974 12.5002 8.61863 12.5002 9.99972C12.5002 11.3805 11.3813 12.4997 10.0002 12.4997C8.6195 12.4997 7.50023 11.3809 7.50023 9.99972C7.50023 8.61897 8.61908 7.49974 10.0002 7.49974ZM14.3752 4.58308C13.8008 4.58308 13.3336 5.04967 13.3336 5.62403C13.3336 6.19841 13.8002 6.66572 14.3752 6.66572C14.9496 6.66572 15.4169 6.19913 15.4169 5.62403C15.4169 5.04967 14.9488 4.58236 14.3752 4.58308Z' }
  ]
  
  return configs.map(config => {
    const url = props.user[config.key] || 
                props.user.staff?.[config.key] || 
                props.user.student?.[config.key] || 
                props.user.parent_profile?.[config.key] ||
                props.user.user?.[config.key]
    return { ...config, url }
  })
})

const toggleDeactivate = async () => {
  const isDeactivating = !props.user.deactivated_at
  const action = isDeactivating ? 'deactivate' : 'activate'
  
  const result = await Swal.fire({
    title: `Are you sure?`,
    text: isDeactivating 
      ? `This will prevent ${props.user.name} from logging into the portal. You can reactivate the account at any time.` 
      : `This will restore portal access for ${props.user.name}.`,
    icon: isDeactivating ? 'warning' : 'info',
    showCancelButton: true,
    confirmButtonColor: isDeactivating ? '#ef4444' : '#22c55e',
    cancelButtonColor: '#71717a',
    confirmButtonText: `Yes, ${action}!`,
    cancelButtonText: 'No, cancel',
    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
    color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
    customClass: {
      popup: 'rounded-2xl border border-gray-200 dark:border-gray-800',
      confirmButton: 'rounded-lg px-5 py-2.5 text-white font-medium',
      cancelButton: 'rounded-lg px-5 py-2.5 text-white font-medium'
    }
  })

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/staff/${props.user.id}`)
      emit('refresh')
      Swal.fire({
        title: 'Success!',
        text: `The account has been ${action}d successfully.`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    } catch (error) {
      Swal.fire({
        title: 'Error!',
        text: `Failed to ${action} user. Please try again.`,
        icon: 'error',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
      })
    }
  }
}
const showFullImage = () => {
  if (!props.user.original?.url) return
  
  Swal.fire({
    imageUrl: props.user.original.url,
    imageAlt: props.user.name,
    showConfirmButton: false,
    showCloseButton: true,
    background: 'transparent',
    backdrop: `rgba(0,0,0,0.9)`,
    customClass: {
      image: 'max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl',
      popup: 'bg-transparent border-none shadow-none p-0',
    },
    didOpen: (popup) => {
      if (popup.parentElement) {
        popup.parentElement.style.zIndex = '1000000'
      }
    }
  })
}

const fileInput = ref(null)

const handlePhotoUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('photo', file)
  formData.append('_method', 'PATCH') // Use PATCH for update

  try {
    Swal.fire({
      title: 'Uploading...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    })

    let endpoint = ''
    if (props.type === 'staff') endpoint = `/api/staff/${props.user.id}`
    else if (props.type === 'student') endpoint = `/api/students/${props.user.id}`
    else if (props.type === 'parent') endpoint = `/api/parents/${props.user.id}`

    await axios.post(endpoint, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    emit('refresh')
    
    Swal.fire({
      title: 'Success!',
      text: 'Profile picture updated.',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (error) {
    console.error('Upload failed:', error)
    Swal.fire({
      title: 'Upload Failed',
      text: error.response?.data?.message || 'Failed to upload image.',
      icon: 'error'
    })
  }
}
</script>
