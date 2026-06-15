<template>
  <div class="sidebar-outer">
    <div class="sidebar-header">
      <v-icon size="16" color="white">mdi-view-grid</v-icon>
      <span>All Categories</span>
    </div>
    <div
      v-for="category in categories"
      :key="category.name"
      class="sidebar-item"
      :class="{ active: $route.params.name === category.route }"
      @click="$router.push('/category/' + category.route)"
    >
      <div class="sidebar-icon">
        <v-icon size="18" color="#1565C0">{{ category.icon }}</v-icon>
      </div>
      <span class="sidebar-name">{{ category.name }}</span>
      <v-icon size="14" color="#cfd8dc" class="ml-auto">mdi-chevron-right</v-icon>
    </div>
    <!-- Filler stretches the white background to the bottom of the page -->
    <div class="sidebar-filler"></div>
  </div>
</template>

<script>
export default {
  name: 'Sidebar',
  data() {
    return {
      categories: [
        { name: 'Laptop',              route: 'Laptop',      icon: 'mdi-laptop' },
        { name: 'Printers & Scanners', route: 'Printers',    icon: 'mdi-printer' },
        { name: 'Monitors',            route: 'Monitors',    icon: 'mdi-monitor' },
        { name: 'Tablet',              route: 'Tablet',      icon: 'mdi-tablet' },
        { name: 'Desktop',             route: 'Desktop',     icon: 'mdi-desktop-tower' },
        { name: 'Accessories',         route: 'Accessories', icon: 'mdi-headphones' },
        { name: 'Gaming Mouse',        route: 'Gaming Mouse',icon: 'mdi-mouse' },
        { name: 'Gaming PC',           route: 'Gaming PC',   icon: 'mdi-desktop-tower-monitor' },
        { name: 'Networking',          route: 'Accessories', icon: 'mdi-wifi' },
        { name: 'Software',            route: 'Accessories', icon: 'mdi-file-code' }
      ]
    }
  }
}
</script>

<style scoped>
/* Outer wrapper — always fills full height of its parent flex row */
.sidebar-outer {
  width: 220px;
  flex-shrink: 0;
  background: white;
  border-right: 1px solid #e8ecf0;
  display: flex;
  flex-direction: column;
  /* Stretch to match the tallest sibling (home-main) */
  align-self: stretch;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 14px 16px;
  background: #1565C0;
  font-size: 13px;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 16px;
  cursor: pointer;
  border-bottom: 0px solid #f4f6f8;
  transition: background 0.15s;
  flex-shrink: 0;
}
.sidebar-item:hover { background: #f0f7ff; }
.sidebar-item:hover .sidebar-name { color: #1565C0; }
.sidebar-item.active { background: #e3f2fd; }
.sidebar-item.active .sidebar-name { color: #1565C0; font-weight: 700; }

.sidebar-icon {
  width: 30px;
  height: 30px;
  background: #e3f2fd;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sidebar-name { font-size: 13px; color: #37474f; font-weight: 500; transition: color 0.15s; }

/* This div grows to fill all remaining vertical space, keeping the white background full height */
.sidebar-filler {
  flex: 1;
  background: white;
}
</style>