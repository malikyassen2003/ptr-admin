<template>
  <div class="navbar">
    <div class="navbar-scroll">
      <div
        v-for="link in navLinks"
        :key="link.label"
        class="nav-link"
        :class="{ active: isActive(link), sale: link.label === 'Flash Sale' }"
        @click="navigate(link)"
      >
        <span v-if="link.label === 'Flash Sale'" class="sale-dot"></span>
        {{ link.label }}
        <span v-if="link.label === 'Flash Sale'" class="sale-badge">HOT</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NavigationBar',
  data() {
    return {
      navLinks: [
        { label: 'Home',        route: '/' },
        { label: 'Laptops',     route: '/category/Laptop' },
        { label: 'Desktops',    route: '/category/Desktop' },
        { label: 'Monitors',    route: '/category/Monitors' },
        { label: 'Tablets',     route: '/category/Tablet' },
        { label: 'Accessories', route: '/category/Accessories' },
        { label: 'Printers',    route: '/category/Printers' },
        { label: 'Flash Sale',  route: '/category/Laptop' },
        { label: 'About us',    route: '/' },
        { label: 'Contact us',  route: '/' }
      ]
    }
  },
  methods: {
    navigate(link) { this.$router.push(link.route) },
    isActive(link) {
      return this.$route.path === link.route ||
        (link.route !== '/' && this.$route.path.startsWith(link.route))
    }
  }
}
</script>

<style scoped>
.navbar {
  background: white;
  border-bottom: 1px solid #eef0f4;
  padding: 0 28px;
  overflow-x: auto;
  scrollbar-width: none;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.navbar::-webkit-scrollbar { display: none; }
.navbar-scroll { display: flex; align-items: center; min-width: max-content; }

.nav-link {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 15px 17px;
  font-size: 13px;
  font-weight: 600;
  color: #546e7a;
  cursor: pointer;
  border-bottom: 2.5px solid transparent;
  margin-bottom: -1px;
  transition: color 0.2s, border-color 0.2s;
  white-space: nowrap;
  letter-spacing: 0.1px;
  position: relative;
}
.nav-link:hover { color: #1565C0; border-bottom-color: #1565C0; }
.nav-link.active {
  color: #1565C0;
  border-bottom-color: #1565C0;
  font-weight: 700;
}

/* Flash Sale */
.nav-link.sale {
  color: #e53935;
  font-weight: 700;
}
.nav-link.sale:hover { color: #c62828; border-bottom-color: #e53935; }
.sale-dot {
  width: 6px; height: 6px;
  background: #e53935;
  border-radius: 50%;
  animation: pulse 1.4s ease-in-out infinite;
  flex-shrink: 0;
}
.sale-badge {
  font-size: 9px;
  font-weight: 800;
  background: #e53935;
  color: white;
  padding: 1px 5px;
  border-radius: 4px;
  letter-spacing: 0.5px;
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(0.85); }
}
</style>