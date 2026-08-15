<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const router   = useRouter()
const auth     = useAdminAuthStore()
const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)
const showPw   = ref(false)

async function handleLogin() {
  if (!email.value || !password.value) { error.value = 'Please enter your email and password.'; return }
  error.value = ''
  loading.value = true
  const result = await auth.login(email.value, password.value)
  loading.value = false
  if (result.success) router.push({ name: 'admin.dashboard' })
  else error.value = result.message
}
</script>

<template>
  <div class="min-h-screen flex">
    <!-- Left panel -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden" style="background:#0d1b2a;">
      <div class="absolute inset-0 opacity-[0.03]" style="background-image:linear-gradient(rgba(255,255,255,1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,1) 1px,transparent 1px);background-size:32px 32px;"></div>
      <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-10 blur-3xl" style="background:#1565C0;"></div>

      <div class="relative flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1565C0,#1976D2);">
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72" />
          </svg>
        </div>
        <span class="text-white font-semibold text-lg">Malik Electronics</span>
      </div>

      <div class="relative">
        <h2 class="text-4xl font-bold text-white mb-4 leading-tight">
          Manage your<br />
          <span style="color:#1976D2;">electronics store</span><br />
          with precision.
        </h2>
        <p class="text-sm" style="color:rgba(255,255,255,0.4);">Full control over products, categories, orders and analytics.</p>
      </div>

      <div class="relative flex items-center gap-6">
        <div class="text-center"><p class="text-2xl font-bold text-white">46+</p><p class="text-xs" style="color:rgba(255,255,255,0.35);">Products</p></div>
        <div class="h-8 w-px bg-white/10"></div>
        <div class="text-center"><p class="text-2xl font-bold text-white">9</p><p class="text-xs" style="color:rgba(255,255,255,0.35);">Categories</p></div>
        <div class="h-8 w-px bg-white/10"></div>
        <div class="text-center"><p class="text-2xl font-bold text-white">2003</p><p class="text-xs" style="color:rgba(255,255,255,0.35);">Since</p></div>
      </div>
    </div>

    <!-- Right panel -->
    <div class="flex-1 flex items-center justify-center p-8" style="background:#f0f4f9;">
      <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
          <h1 class="text-2xl font-bold text-gray-900 mb-1">Welcome back</h1>
          <p class="text-sm text-gray-400 mb-7">Sign in to your admin account</p>

          <div v-if="error" class="mb-5 px-4 py-3 rounded-xl text-sm flex items-center gap-2" style="background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
            {{ error }}
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Email</label>
              <input v-model="email" type="email" placeholder="admin@malik.jo" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all" @keyup.enter="handleLogin" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Password</label>
              <div class="relative">
                <input v-model="password" :type="showPw ? 'text' : 'password'" placeholder="••••••••" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all" @keyup.enter="handleLogin" />
                <button type="button" @click="showPw=!showPw" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path v-if="!showPw" stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path v-if="!showPw" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path v-if="showPw" stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                  </svg>
                </button>
              </div>
            </div>
            <button @click="handleLogin" :disabled="loading" class="w-full py-3 rounded-xl text-sm font-semibold text-white transition-all active:scale-95 mt-2" :class="loading?'opacity-70 cursor-not-allowed':'hover:brightness-110'" style="background:linear-gradient(135deg,#1565C0,#1976D2);box-shadow:0 4px 15px rgba(21,101,192,0.3);">
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                Signing in…
              </span>
              <span v-else>Sign In</span>
            </button>
          </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-5">Malik Electronics Admin · Amman, Jordan · Est. 2003</p>
      </div>
    </div>
  </div>
</template>