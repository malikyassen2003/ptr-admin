<script setup>
import { ref } from 'vue'

const toasts = ref([])

let nextId = 0

function show(message, type = 'success') {
  const id = nextId++
  toasts.value.push({ id, message, type })
  setTimeout(() => remove(id), 3500)
}

function remove(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

defineExpose({ show })
</script>

<template>
  <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none">
    <transition-group name="toast">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium pointer-events-auto max-w-sm"
        :style="toast.type === 'success'
          ? 'background:#0d1b2a; color:white;'
          : toast.type === 'error'
          ? 'background:#BE123C; color:white;'
          : 'background:#B45309; color:white;'"
      >
        <!-- Success -->
        <svg v-if="toast.type === 'success'" class="w-4 h-4 flex-shrink-0" style="color:#4ade80;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        <!-- Error -->
        <svg v-else-if="toast.type === 'error'" class="w-4 h-4 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
        <!-- Warning -->
        <svg v-else class="w-4 h-4 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        <span>{{ toast.message }}</span>
        <button @click="remove(toast.id)" class="ml-auto opacity-60 hover:opacity-100 transition-opacity">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<style scoped>
.toast-enter-active { transition: all 0.25s ease; }
.toast-leave-active { transition: all 0.2s ease; }
.toast-enter-from  { opacity: 0; transform: translateY(12px) scale(0.96); }
.toast-leave-to    { opacity: 0; transform: translateX(100%); }
</style>