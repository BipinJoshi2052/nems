<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Invite Teachers</h2>
      <p class="text-sm text-gray-500">Add email addresses of teachers you'd like to invite. You can also skip this and add them later.</p>
    </div>

    <div class="space-y-4">
      <div v-for="(invitation, index) in invitations" :key="index" class="flex gap-2">
        <input 
          v-model="invitation.email" 
          type="email" 
          placeholder="teacher@school.edu.np"
          class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700"
        />
        <button @click="removeInvitation(index)" class="p-2 text-red-500 hover:bg-red-50 rounded" :disabled="invitations.length === 1">
          <TrashIcon class="w-4 h-4" />
        </button>
      </div>

      <button @click="addInvitation" class="text-sm text-brand-500 font-semibold flex items-center hover:text-brand-600 transition-colors">
        <PlusIcon class="w-4 h-4 mr-1" /> Add Another
      </button>
    </div>

    <div v-if="error" class="p-3 bg-red-50 text-red-600 text-xs rounded-md border border-red-200">
      {{ error }}
    </div>

    <div class="pt-6 flex justify-between border-t dark:border-gray-700">
      <button @click="$emit('back')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
      <div class="flex gap-3">
        <button @click="handleSkip" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 font-medium">Skip for now</button>
        <button 
          @click="handleSubmit" 
          :disabled="loading || !isValid" 
          class="px-6 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition-all disabled:opacity-50"
        >
          <span v-if="loading">Sending...</span>
          <span v-else>Invite & Continue</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import axios from 'axios'
import { PlusIcon, TrashIcon } from '@/icons'

const props = defineProps({
  state: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['next', 'back'])

const loading = ref(false)
const error = ref('')
const invitations = ref<{email: string}[]>(props.state.invitations?.length ? props.state.invitations.map((e: string) => ({ email: e })) : [{ email: '' }])

const isValid = computed(() => invitations.value.some(i => i.email.trim() && /^\S+@\S+\.\S+$/.test(i.email)))

const addInvitation = () => {
  invitations.value.push({ email: '' })
}

const removeInvitation = (index: number) => {
  if (invitations.value.length > 1) {
    invitations.value.splice(index, 1)
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    const emails = invitations.value.map(i => i.email.trim()).filter(Boolean)
    await axios.post('/api/setup/invite-teachers', { emails })
    emit('next', { invitations: emails })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to send invitations'
  } finally {
    loading.value = false
  }
}

const handleSkip = () => {
  emit('next', { invitations: [] })
}
</script>
