<template>
  <div class="grid-wrap">
    <div class="grid-toolbar">
      <span class="grid-count">{{ products.length }} Products</span>
      <div class="grid-sort">
        <label>Sort by:</label>
        <select v-model="sortBy">
          <option value="default">Default</option>
          <option value="price_asc">Price: Low to High</option>
          <option value="price_desc">Price: High to Low</option>
          <option value="name">Name A–Z</option>
        </select>
      </div>
    </div>

    <div class="product-grid" v-if="sorted.length > 0">
      <div
        v-for="product in sorted"
        :key="product.id"
        class="product-card"
        @click="$router.push('/product/' + product.id)"
      >
        <div class="card-img-wrap">
          <img :src="product.image" :alt="product.name" @error="onImgError($event, product)" />
          <div class="card-badge" v-if="product.badge">{{ product.badge }}</div>
          <div class="card-hover-actions">
            <button class="hov-btn" @click.stop="toggleWishlist(product)">
              <v-icon size="14" :color="isWishlisted(product.id) ? 'red' : '#555'">
                {{ isWishlisted(product.id) ? 'mdi-heart' : 'mdi-heart-outline' }}
              </v-icon>
            </button>
            <button class="hov-btn" @click.stop="toggleCompare(product)">
              <v-icon size="14" :color="isCompared(product.id) ? '#1565C0' : '#555'">mdi-compare-horizontal</v-icon>
            </button>
            <button class="hov-btn hov-btn-cart" @click.stop="openCartPopup(product)">
              <v-icon size="14" color="#1565C0">mdi-cart-outline</v-icon>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="card-price">JOD{{ product.basePrice }}.00</div>
          <div class="card-stars">
            <v-icon v-for="n in 5" :key="n" size="11" color="#e0e0e0">mdi-star</v-icon>
          </div>
          <div class="card-name">{{ product.name }}</div>
          <div class="card-spec">{{ product.shortSpec }}</div>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <v-icon size="80" color="#e0e0e0">mdi-package-variant-closed</v-icon>
      <p>No products found</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductGrid',
  props: {
    products: { type: Array, required: true }
  },
  data() {
    return { sortBy: 'default', wishlist: [], compare: [] }
  },
  computed: {
    sorted() {
      const list = [...this.products]
      if (this.sortBy === 'price_asc')  return list.sort((a, b) => a.basePrice - b.basePrice)
      if (this.sortBy === 'price_desc') return list.sort((a, b) => b.basePrice - a.basePrice)
      if (this.sortBy === 'name')       return list.sort((a, b) => a.name.localeCompare(b.name))
      return list
    }
  },
  mounted() {
    this.loadLists()
    this.$bus.on('refresh-counts', this.loadLists)
  },
  beforeUnmount() {
    this.$bus.off('refresh-counts', this.loadLists)
  },
  methods: {
    loadLists() {
      try { this.wishlist = JSON.parse(localStorage.getItem('malik_wishlist') || '[]') } catch { this.wishlist = [] }
      try { this.compare  = JSON.parse(localStorage.getItem('malik_compare')  || '[]') } catch { this.compare  = [] }
    },
    isWishlisted(id) { return this.wishlist.some(p => p.id === id) },
    isCompared(id)   { return this.compare.some(p => p.id === id)  },
    onImgError(e, product) {
      e.target.onerror = null
      e.target.src = `https://placehold.co/218x200/f7f9fc/90a4ae?text=${encodeURIComponent(product.brand || 'Product')}`
    },
    openCartPopup(product) {
      this.$bus.emit('show-cart-popup', product)
    },
    toggleWishlist(product) {
      const idx = this.wishlist.findIndex(p => p.id === product.id)
      if (idx > -1) { this.wishlist.splice(idx, 1); this.$bus.emit('show-snackbar', { msg: 'Removed from wishlist', color: 'grey' }) }
      else { this.wishlist.push(product); this.$bus.emit('show-snackbar', { msg: `${product.name} added to wishlist ❤️`, color: 'pink' }) }
      localStorage.setItem('malik_wishlist', JSON.stringify(this.wishlist))
      this.$bus.emit('wishlist-updated')
    },
    toggleCompare(product) {
      const idx = this.compare.findIndex(p => p.id === product.id)
      if (idx > -1) { this.compare.splice(idx, 1); this.$bus.emit('show-snackbar', { msg: 'Removed from compare', color: 'grey' }) }
      else {
        if (this.compare.length >= 4) { this.$bus.emit('show-snackbar', { msg: 'Max 4 products to compare', color: 'warning' }); return }
        this.compare.push(product); this.$bus.emit('show-snackbar', { msg: `${product.name} added to compare` })
      }
      localStorage.setItem('malik_compare', JSON.stringify(this.compare))
      this.$bus.emit('compare-updated')
    }
  }
}
</script>

<style scoped>
.grid-wrap { width: 100%; }
.grid-toolbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 0 20px; border-bottom: 1px solid #eef0f4; margin-bottom: 24px;
}
.grid-count { font-size: 14px; color: #90a4ae; font-weight: 600; }
.grid-sort { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #546e7a; }
.grid-sort select {
  border: 1px solid #e0e6ef; border-radius: 6px; padding: 6px 10px;
  font-size: 13px; color: #0d1b2a; background: white; cursor: pointer; outline: none;
}
.product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 18px; }
.product-card {
  border: 1.5px solid #eef0f4; border-radius: 12px; overflow: hidden;
  cursor: pointer; transition: box-shadow 0.22s, transform 0.22s, border-color 0.22s;
  background: white;
}
.product-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.1); transform: translateY(-3px); border-color: #d0dcf0; }
.card-img-wrap {
  position: relative; height: 200px; background: #f7f9fc; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}
.card-img-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 12px; transition: transform 0.3s; }
.product-card:hover .card-img-wrap img { transform: scale(1.06); }
.card-badge {
  position: absolute; top: 10px; left: 10px;
  background: #e53935; color: white; font-size: 10px; font-weight: 700;
  padding: 3px 10px; border-radius: 20px;
}
.card-hover-actions {
  position: absolute; top: 8px; right: 8px;
  display: flex; flex-direction: column; gap: 5px;
  opacity: 0; transition: opacity 0.2s;
}
.product-card:hover .card-hover-actions { opacity: 1; }
.hov-btn {
  width: 30px; height: 30px; background: white;
  border: 1.5px solid #e0e6ef; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.18s;
}
.hov-btn:hover { background: #e3f2fd; border-color: #1565C0; transform: scale(1.1); }
.hov-btn-cart:hover { background: #1565C0; border-color: #1565C0; }
.hov-btn-cart:hover .v-icon { color: white !important; }
.card-body { padding: 13px 15px 15px; }
.card-price { font-size: 16px; font-weight: 900; color: #1565C0; margin-bottom: 5px; }
.card-stars { display: flex; gap: 1px; margin-bottom: 7px; }
.card-name {
  font-size: 13px; font-weight: 600; color: #1a2332; margin-bottom: 5px; line-height: 1.4;
  display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.card-spec {
  font-size: 11px; color: #90a4ae; line-height: 1.45;
  display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.empty-state { text-align: center; padding: 80px 20px; color: #90a4ae; font-size: 16px; }
</style>