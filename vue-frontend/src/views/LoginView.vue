<template>
  <div class="auth-page">
    <div class="auth-left">
      <div class="auth-brand">
        <span class="brand-m">M</span>
        <div>
          <div class="brand-name">MALIK ELECTRONICS</div>
          <div class="brand-since">Since 2003</div>
        </div>
      </div>
      <div class="auth-tagline">Your trusted tech partner<br/>in Jordan since 2003.</div>
      <div class="auth-features">
        <div class="feature-item">
          <v-icon color="#64b5f6">mdi-shield-check</v-icon>
          <span>Secure Shopping</span>
        </div>
        <div class="feature-item">
          <v-icon color="#64b5f6">mdi-truck-fast</v-icon>
          <span>Fast Delivery</span>
        </div>
        <div class="feature-item">
          <v-icon color="#64b5f6">mdi-headset</v-icon>
          <span>24/7 Support</span>
        </div>
      </div>
    </div>

    <div class="auth-right">
      <div class="auth-card">
        <div class="auth-title">Welcome Back.</div>
        <div class="auth-sub">Sign in to your account</div>

        <div class="field-wrap">
          <v-icon class="field-icon">mdi-email-outline</v-icon>
          <input type="email" placeholder="Email Address" class="auth-input" v-model="email" />
        </div>

        <div class="field-wrap">
          <v-icon class="field-icon">mdi-lock-outline</v-icon>
          <input :type="showPass ? 'text' : 'password'" placeholder="Password" class="auth-input" v-model="password" />
          <v-icon class="field-icon-right" @click="showPass = !showPass">
            {{ showPass ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}
          </v-icon>
        </div>

        <div class="auth-row">
          <label class="remember-wrap">
            <input type="checkbox" v-model="remember" />
            <span>Remember me</span>
          </label>
          <span class="forgot-link">Forgot Password?</span>
        </div>

        <button class="auth-btn" @click="login">Sign In</button>

        <div class="divider"><span>Or sign in with</span></div>

        <div class="social-row">
          <button class="social-btn facebook">
            <v-icon color="white" size="18">mdi-facebook</v-icon>
          </button>
          <button class="social-btn google">
            <v-icon color="white" size="18">mdi-google</v-icon>
          </button>
          <button class="social-btn twitter">
            <v-icon color="white" size="18">mdi-twitter</v-icon>
          </button>
        </div>

        <div class="auth-switch">
          Don't have an account?
          <span class="switch-link" @click="$router.push('/register')">Create one</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return { email: '', password: '', remember: false, showPass: false }
  },
  methods: {
    login() {
      this.$root.$emit('show-snackbar', 'Logged in successfully ✓')
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>
.auth-page { display: flex; min-height: 100vh; }
.auth-left {
  width: 420px;
  background: linear-gradient(160deg, #0d1b2a 0%, #1565C0 100%);
  display: flex; flex-direction: column; justify-content: center;
  padding: 60px 48px; flex-shrink: 0;
}
.auth-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 48px; }
.brand-m { font-size: 48px; font-weight: 900; color: white; line-height: 1; }
.brand-name { font-size: 14px; font-weight: 800; color: white; letter-spacing: 1px; }
.brand-since { font-size: 12px; color: #90caf9; }
.auth-tagline { font-size: 28px; font-weight: 800; color: white; line-height: 1.4; margin-bottom: 48px; }
.auth-features { display: flex; flex-direction: column; gap: 20px; }
.feature-item { display: flex; align-items: center; gap: 14px; font-size: 14px; color: #90caf9; font-weight: 500; }
.auth-right { flex: 1; background: #f4f7fb; display: flex; align-items: center; justify-content: center; padding: 40px; }
.auth-card { background: white; border-radius: 16px; padding: 48px 44px; width: 100%; max-width: 440px; box-shadow: 0 8px 40px rgba(0,0,0,0.08); }
.auth-title { font-size: 28px; font-weight: 900; color: #0d1b2a; margin-bottom: 6px; }
.auth-sub { font-size: 14px; color: #90a4ae; margin-bottom: 32px; }
.field-wrap { position: relative; margin-bottom: 16px; }
.field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #90a4ae !important; font-size: 18px !important; }
.field-icon-right { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #90a4ae !important; font-size: 18px !important; cursor: pointer; }
.auth-input { width: 100%; padding: 14px 44px; border: 1.5px solid #e8ecf0; border-radius: 10px; font-size: 14px; color: #0d1b2a; outline: none; transition: border-color 0.2s; background: #f8fafc; box-sizing: border-box; }
.auth-input:focus { border-color: #1565C0; background: white; }
.auth-input::placeholder { color: #b0bec5; }
.auth-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.remember-wrap { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #546e7a; cursor: pointer; }
.forgot-link { font-size: 13px; color: #1565C0; font-weight: 600; cursor: pointer; }
.auth-btn { width: 100%; padding: 15px; background: #1565C0; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; margin-bottom: 24px; }
.auth-btn:hover { background: #0d47a1; }
.divider { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: #b0bec5; font-size: 13px; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e8ecf0; }
.social-row { display: flex; justify-content: center; gap: 14px; margin-bottom: 28px; }
.social-btn { width: 46px; height: 46px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: opacity 0.2s; }
.social-btn:hover { opacity: 0.85; }
.facebook { background: #1877f2; }
.google { background: #ea4335; }
.twitter { background: #1da1f2; }
.auth-switch { text-align: center; font-size: 14px; color: #90a4ae; }
.switch-link { color: #1565C0; font-weight: 700; cursor: pointer; margin-left: 4px; }
.switch-link:hover { text-decoration: underline; }
</style>