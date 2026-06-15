<template>
  <div class="products-page">
    <div class="page-header">
      <div class="breadcrumb">
        <span class="breadcrumb-link" @click="$router.push('/')">Home</span>
        <v-icon size="14" color="#90a4ae">mdi-chevron-right</v-icon>
        <span class="breadcrumb-current">{{ $route.params.name }}</span>
      </div>
      <div class="page-title">{{ $route.params.name }}</div>
      <div class="page-count">{{ products.length }} Products</div>
    </div>

    <div class="products-grid">
      <div
        v-for="product in products"
        :key="product.id"
        class="product-card"
        @click="$router.push('/product/' + product.id)"
      >
        <div class="product-img">
          <img :src="product.image" :alt="product.name" />
          <div class="product-badge" v-if="product.badge">{{ product.badge }}</div>
          <div class="card-actions">
            <button class="icon-btn" @click.stop="toggleWishlist(product)" :title="isWishlisted(product.id) ? 'Remove from Wishlist' : 'Add to Wishlist'">
              <v-icon size="16" :color="isWishlisted(product.id) ? 'red' : '#455a64'">
                {{ isWishlisted(product.id) ? 'mdi-heart' : 'mdi-heart-outline' }}
              </v-icon>
            </button>
            <button class="icon-btn" @click.stop="toggleCompare(product)" :title="isCompared(product.id) ? 'Remove from Compare' : 'Add to Compare'">
              <v-icon size="16" :color="isCompared(product.id) ? '#1565C0' : '#455a64'">mdi-compare-horizontal</v-icon>
            </button>
          </div>
        </div>
        <div class="product-info">
          <div class="product-brand">{{ product.brand }}</div>
          <div class="product-name">{{ product.name }}</div>
          <div class="product-specs">{{ product.shortSpec }}</div>
          <div class="product-price">JOD {{ product.basePrice }}</div>
        </div>
        <button class="add-btn" @click.stop="addToCart(product)">
          <v-icon size="16" color="white">mdi-cart-plus</v-icon>
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { allProducts } from '../ProductDB.js'

export default {
  name: 'ProductsView',
  data() {
    return {
      wishlist: [],
      compare: []
    }
  },
  computed: {
    products() {
      return allProducts[this.$route.params.name] || []
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
      try { this.compare = JSON.parse(localStorage.getItem('malik_compare') || '[]') } catch { this.compare = [] }
    },
    isWishlisted(id) { return this.wishlist.some(p => p.id === id) },
    isCompared(id) { return this.compare.some(p => p.id === id) },

    addToCart(product) {
      const cart = JSON.parse(localStorage.getItem('malik_cart') || '[]')
      const idx = cart.findIndex(p => p.id === product.id)
      if (idx > -1) {
        cart[idx].quantity = (cart[idx].quantity || 1) + 1
      } else {
        cart.push({ ...product, quantity: 1 })
      }
      localStorage.setItem('malik_cart', JSON.stringify(cart))
      this.$bus.emit('cart-updated')
      this.$bus.emit('show-snackbar', { msg: `${product.name} added to cart ✓` })
    },

    toggleWishlist(product) {
      const idx = this.wishlist.findIndex(p => p.id === product.id)
      if (idx > -1) {
        this.wishlist.splice(idx, 1)
        this.$bus.emit('show-snackbar', { msg: `${product.name} removed from wishlist`, color: 'grey' })
      } else {
        this.wishlist.push(product)
        this.$bus.emit('show-snackbar', { msg: `${product.name} added to wishlist ❤️`, color: 'pink' })
      }
      localStorage.setItem('malik_wishlist', JSON.stringify(this.wishlist))
      this.$bus.emit('wishlist-updated')
    },

    toggleCompare(product) {
      const idx = this.compare.findIndex(p => p.id === product.id)
      if (idx > -1) {
        this.compare.splice(idx, 1)
        this.$bus.emit('show-snackbar', { msg: `${product.name} removed from compare`, color: 'grey' })
      } else {
        if (this.compare.length >= 4) {
          this.$bus.emit('show-snackbar', { msg: 'You can compare up to 4 products', color: 'warning' })
          return
        }
        this.compare.push(product)
        this.$bus.emit('show-snackbar', { msg: `${product.name} added to compare` })
      }
      localStorage.setItem('malik_compare', JSON.stringify(this.compare))
      this.$bus.emit('compare-updated')
    }
  }
}
</script>

<style scoped>
.products-page { padding: 32px; background: #f4f7fb; min-height: 100vh; }
.page-header { margin-bottom: 32px; }
.breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 12px; }
.breadcrumb-link { font-size: 13px; color: #1565C0; cursor: pointer; font-weight: 600; }
.breadcrumb-link:hover { text-decoration: underline; }
.breadcrumb-current { font-size: 13px; color: #90a4ae; }
.page-title { font-size: 26px; font-weight: 900; color: #0d1b2a; margin-bottom: 4px; }
.page-count { font-size: 13px; color: #90a4ae; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
.product-card { background: white; border-radius: 12px; border: 1px solid #e8ecf0; overflow: hidden; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; }
.product-card:hover { box-shadow: 0 8px 32px rgba(21,101,192,0.12); transform: translateY(-4px); border-color: #1565C0; }
.product-img { position: relative; height: 180px; background: #f8fafc; overflow: hidden; }
.product-img img { width: 100%; height: 100%; object-fit: cover; }
.product-badge { position: absolute; top: 12px; left: 12px; background: #e53935; color: white; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
.card-actions { position: absolute; top: 8px; right: 8px; display: flex; flex-direction: column; gap: 4px; opacity: 0; transition: opacity 0.2s; }
.product-card:hover .card-actions { opacity: 1; }
.icon-btn { width: 30px; height: 30px; background: white; border: 1px solid #e8ecf0; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.icon-btn:hover { background: #f0f7ff; }
.product-info { padding: 16px; flex: 1; }
.product-brand { font-size: 11px; font-weight: 700; color: #1565C0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.product-name { font-size: 14px; font-weight: 700; color: #0d1b2a; margin-bottom: 6px; }
.product-specs { font-size: 12px; color: #90a4ae; margin-bottom: 10px; line-height: 1.5; }
.product-price { font-size: 18px; font-weight: 900; color: #1565C0; }
.add-btn { margin: 0 16px 16px; padding: 10px; background: #1565C0; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; }
.add-btn:hover { background: #0d47a1; }
</style>