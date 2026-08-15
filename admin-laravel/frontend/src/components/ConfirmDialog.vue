<script setup>
import { ref } from 'vue'

const visible  = ref(false)
const title    = ref('')
const message  = ref('')
const loading  = ref(false)
let   resolver = null

function open(opts = {}) {
  title.value   = opts.title   ?? 'Are you sure?'
  message.value = opts.message ?? 'This action cannot be undone.'
  visible.value = true
  return new Promise(resolve => { resolver = resolve })
}

function confirm() {
  resolver?.(true)
  visible.value = false
}

function cancel() {
  resolver?.(false)
  visible.value = false
}

defineExpose({ open })
</script>

<template>
  <teleport to="body">
    <transition name="modal">
      <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0" style="background:rgba(13,27,42,0.6); backdrop-filter:blur(4px);" @click="cancel"></div>

        <!-- Dialog -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
          <!-- Icon -->
          <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#FFF1F2;">
            <svg class="w-6 h-6" style="color:#BE123C;" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
          </div>

          <h3 class="text-base font-bold text-gray-900 text-center mb-1">{{ title }}</h3>
          <p class="text-sm text-gray-400 text-center mb-6">{{ message }}</p>

          <div class="flex gap-3">
            <button
              @click="cancel"
              class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              @click="confirm"
              class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
              style="background:#BE123C;"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .relative, .modal-leave-to .relative { transform: scale(0.95); }
</style>