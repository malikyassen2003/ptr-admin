<template>
  <div class="topbar">
    <div class="topbar-inner">
      <div class="topbar-left">
        <span class="topbar-dot"></span>
        <v-icon size="13" color="#64b5f6">mdi-map-marker</v-icon>
        <span>Amman, Jordan</span>
        <span class="sep">|</span>
        <v-icon size="13" color="#64b5f6">mdi-clock-outline</v-icon>
        <span>9:30 AM – 8:00 PM &nbsp;·&nbsp; Friday: Closed</span>
      </div>
      <div class="topbar-right">
        <span class="topbar-link lang-btn">🇯🇴 عربي</span>
        <span class="sep">|</span>
        <span class="topbar-link" @click="$router.push('/login')">Login</span>
        <span class="sep">|</span>
        <span class="topbar-link register-btn" @click="$router.push('/register')">Register</span>
      </div>
    </div>
  </div>
</template>

<script>
export default { name: 'TopBar' }
</script>

<style scoped>
.topbar {
  background: linear-gradient(90deg, #060f1e 0%, #0d1b2a 60%, #0a1525 100%);
  height: 38px;
  display: flex;
  align-items: center;
  padding: 0 32px;
  border-bottom: 1px solid rgba(100,181,246,0.08);
}
.topbar-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}
.topbar-left, .topbar-right {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: #78909c;
}
.topbar-dot {
  width: 6px; height: 6px;
  background: #4caf50;
  border-radius: 50%;
  box-shadow: 0 0 6px #4caf50;
  margin-right: 2px;
}
.topbar-link {
  cursor: pointer;
  transition: color 0.2s;
  padding: 2px 4px;
  border-radius: 3px;
}
.topbar-link:hover { color: #90caf9; }
.register-btn {
  color: #64b5f6;
  font-weight: 600;
}
.register-btn:hover { color: #90caf9; }
.sep { color: #1e2d3d; }
</style>