<template>
  <div class="header">
    <!-- Logo -->
    <div class="header-logo" @click="$router.push('/')">
      <div class="logo-icon">M</div>
      <div class="logo-text">
        <div class="logo-name">MALIK ELECTRONICS</div>
        <div class="logo-since">Since 2003</div>
      </div>
    </div>

    <!-- Search -->
    <div class="header-search">
      <div class="search-inner">
        <v-icon size="18" color="#90a4ae" class="search-icon-left">mdi-magnify</v-icon>
        <input
          type="text"
          id="search"
          name="search"
          v-model="searchQuery"
          autocomplete="off"
          placeholder="Search for laptops, monitors, accessories..."
          class="search-input"
          @keyup.enter="doSearch"
        />
        <button class="search-btn" @click="doSearch">Search</button>
      </div>
    </div>

    <!-- Actions -->
    <div class="header-actions">
      <div class="action-item" @click="$router.push('/compare')">
        <div class="action-icon-wrap">
          <v-badge v-if="compareCount > 0" :content="String(compareCount)" color="orange" overlap>
            <v-icon size="19" color="#455a64">mdi-compare-horizontal</v-icon>
          </v-badge>
          <v-icon v-else size="19" color="#455a64">mdi-compare-horizontal</v-icon>
        </div>
        <span class="action-label">Compare</span>
      </div>

      <div class="action-item" @click="$router.push('/wishlist')">
        <div class="action-icon-wrap">
          <v-badge v-if="wishlistCount > 0" :content="String(wishlistCount)" color="#e53935" overlap>
            <v-icon size="19" color="#455a64">mdi-heart-outline</v-icon>
          </v-badge>
          <v-icon v-else size="19" color="#455a64">mdi-heart-outline</v-icon>
        </div>
        <span class="action-label">Wishlist</span>
      </div>

      <div class="action-item cart-item" @click="$router.push('/cart')">
        <div class="action-icon-wrap cart-icon-wrap">
          <v-badge v-if="cartCount > 0" :content="String(cartCount)" color="white" text-color="#1565C0" overlap>
            <v-icon size="19" color="white">mdi-cart-outline</v-icon>
          </v-badge>
          <v-icon v-else size="19" color="white">mdi-cart-outline</v-icon>
        </div>
        <span class="action-label cart-label">Cart</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return { searchQuery: '', cartCount: 0, wishlistCount: 0, compareCount: 0 }
  },
  mounted() {
    this.refreshCounts()
    this.$bus.on('refresh-counts', this.refreshCounts)
  },
  beforeUnmount() { this.$bus.off('refresh-counts', this.refreshCounts) },
  methods: {
    refreshCounts() {
      try { const c = JSON.parse(localStorage.getItem('malik_cart') || '[]'); this.cartCount = c.reduce((s, i) => s + (i.quantity || 1), 0) } catch { this.cartCount = 0 }
      try { this.wishlistCount = JSON.parse(localStorage.getItem('malik_wishlist') || '[]').length } catch { this.wishlistCount = 0 }
      try { this.compareCount  = JSON.parse(localStorage.getItem('malik_compare')  || '[]').length } catch { this.compareCount = 0 }
    },
    doSearch() {
      if (!this.searchQuery.trim()) return
      this.$router.push('/category/Laptop')
    }
  }
}
</script>

<style scoped>
.header {
  display: flex;
  align-items: center;
  padding: 0 28px;
  height: 76px;
  background: white;
  border-bottom: 1px solid #eef0f4;
  gap: 20px;
  box-shadow: 0 1px 8px rgba(0,0,0,0.05);
  position: relative;
  z-index: 10;
}

/* Logo */
.header-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
  cursor: pointer;
  text-decoration: none;
}
.logo-icon {
  width: 42px; height: 42px;
  background: linear-gradient(135deg, #1565C0, #1976d2);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
  font-weight: 900;
  color: white;
  box-shadow: 0 4px 12px rgba(21,101,192,0.35);
  flex-shrink: 0;
}
.logo-text { display: flex; flex-direction: column; }
.logo-name { font-size: 13px; font-weight: 800; letter-spacing: 0.4px; color: #0d1b2a; white-space: nowrap; }
.logo-since { font-size: 11px; color: #90a4ae; margin-top: 1px; }

/* Search */
.header-search { flex: 1; min-width: 0; }
.search-inner {
  display: flex;
  align-items: center;
  background: #f5f7fa;
  border: 2px solid #e0e6ef;
  border-radius: 10px;
  overflow: hidden;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-inner:focus-within {
  border-color: #1565C0;
  box-shadow: 0 0 0 3px rgba(21,101,192,0.1);
  background: white;
}
.search-icon-left { padding: 0 10px 0 14px; flex-shrink: 0; }
.search-input {
  flex: 1;
  padding: 12px 8px;
  font-size: 13.5px;
  border: none;
  outline: none;
  background: transparent;
  color: #0d1b2a;
}
.search-input::placeholder { color: #b0bec5; }
.search-btn {
  background: #1565C0;
  color: white;
  border: none;
  padding: 0 22px;
  height: 100%;
  min-height: 46px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  letter-spacing: 0.2px;
  flex-shrink: 0;
}
.search-btn:hover { background: #0d47a1; }

/* Actions */
.header-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
.action-item {
  display: flex;
  align-items: center;
  gap: 7px;
  cursor: pointer;
  padding: 7px 10px;
  border-radius: 10px;
  transition: background 0.18s;
}
.action-item:hover { background: #f0f4fa; }
.action-icon-wrap {
  width: 38px; height: 38px;
  border: 1.5px solid #e0e6ef;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  background: white;
  transition: border-color 0.2s;
}
.action-item:hover .action-icon-wrap { border-color: #90caf9; }
.cart-icon-wrap {
  background: linear-gradient(135deg, #1565C0, #1976d2) !important;
  border-color: #1565C0 !important;
  box-shadow: 0 3px 10px rgba(21,101,192,0.3);
}
.action-label { font-size: 12px; font-weight: 600; color: #546e7a; }
.cart-item .action-label { color: #1565C0; }
</style>