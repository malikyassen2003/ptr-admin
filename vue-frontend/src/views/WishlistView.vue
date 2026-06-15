<template>
  <div class="wishlist-page">
    <div class="page-header">
      <div class="page-title">
        <v-icon size="28" color="#e53935" style="margin-right:10px">mdi-heart</v-icon>
        Wishlist
      </div>
      <div class="page-count">{{ items.length }} items</div>
    </div>

    <div v-if="items.length > 0" class="wishlist-grid">
      <div v-for="product in items" :key="product.id" class="wish-card">
        <button class="remove-wish" @click="removeFromWishlist(product)" title="Remove from Wishlist">
          <v-icon size="18" color="#e53935">mdi-heart</v-icon>
        </button>

        <div class="wish-img" @click="$router.push('/product/' + product.id)">
          <img :src="product.image" :alt="product.name" />
          <div class="wish-badge" v-if="product.badge">{{ product.badge }}</div>
        </div>

        <div class="wish-info">
          <div class="wish-brand">{{ product.brand }}</div>
          <div class="wish-name" @click="$router.push('/product/' + product.id)">{{ product.name }}</div>
          <div class="wish-spec">{{ product.shortSpec }}</div>
          <div class="wish-price">JOD {{ product.basePrice }}</div>
        </div>

        <div class="wish-actions">
          <button class="cart-btn" @click="addToCart(product)">
            <v-icon size="16" color="white">mdi-cart-plus</v-icon>
            Add to Cart
          </button>
          <button class="compare-btn" @click="addToCompare(product)">
            <v-icon size="16" color="#1565C0">mdi-compare-horizontal</v-icon>
            Compare
          </button>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <v-icon size="100" color="#e8ecf0">mdi-heart-outline</v-icon>
      <h2>Your wishlist is empty</h2>
      <p>Save items you love and find them here any time</p>
      <button class="shop-btn" @click="$router.push('/')">Start Shopping</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WishlistView',
  data() {
    return { items: [] }
  },
  mounted() {
    this.loadWishlist()
    this.$bus.on('wishlist-updated', this.loadWishlist)
  },
  beforeUnmount() {
    this.$bus.off('wishlist-updated', this.loadWishlist)
  },
  methods: {
    loadWishlist() {
      try {
        this.items = JSON.parse(localStorage.getItem('malik_wishlist') || '[]')
      } catch { this.items = [] }
    },
    removeFromWishlist(product) {
      this.items = this.items.filter(p => p.id !== product.id)
      localStorage.setItem('malik_wishlist', JSON.stringify(this.items))
      this.$bus.emit('wishlist-updated')
      this.$bus.emit('show-snackbar', { msg: `${product.name} removed from wishlist`, color: 'grey' })
    },
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
    addToCompare(product) {
      const compare = JSON.parse(localStorage.getItem('malik_compare') || '[]')
      if (compare.some(p => p.id === product.id)) {
        this.$bus.emit('show-snackbar', { msg: 'Already in compare list', color: 'info' })
        return
      }
      if (compare.length >= 4) {
        this.$bus.emit('show-snackbar', { msg: 'You can compare up to 4 products', color: 'warning' })
        return
      }
      compare.push(product)
      localStorage.setItem('malik_compare', JSON.stringify(compare))
      this.$bus.emit('compare-updated')
      this.$bus.emit('show-snackbar', { msg: `${product.name} added to compare` })
    }
  }
}
</script>

<style scoped>
.wishlist-page { padding: 32px; background: #f4f7fb; min-height: 100vh; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
.page-title { font-size: 26px; font-weight: 900; color: #0d1b2a; display: flex; align-items: center; }
.page-count { font-size: 14px; color: #90a4ae; }
.wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
.wish-card { background: white; border-radius: 12px; border: 1px solid #e8ecf0; overflow: hidden; position: relative; display: flex; flex-direction: column; transition: all 0.2s; }
.wish-card:hover { box-shadow: 0 8px 32px rgba(21,101,192,0.1); transform: translateY(-3px); }
.remove-wish { position: absolute; top: 10px; right: 10px; z-index: 2; background: white; border: 1px solid #e8ecf0; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.remove-wish:hover { background: #fff0f0; border-color: #e53935; }
.wish-img { height: 180px; background: #f8fafc; overflow: hidden; cursor: pointer; position: relative; }
.wish-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.wish-card:hover .wish-img img { transform: scale(1.04); }
.wish-badge { position: absolute; top: 10px; left: 10px; background: #e53935; color: white; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
.wish-info { padding: 16px; flex: 1; }
.wish-brand { font-size: 11px; font-weight: 700; color: #1565C0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.wish-name { font-size: 14px; font-weight: 700; color: #0d1b2a; margin-bottom: 6px; cursor: pointer; }
.wish-name:hover { color: #1565C0; }
.wish-spec { font-size: 12px; color: #90a4ae; margin-bottom: 10px; line-height: 1.5; }
.wish-price { font-size: 18px; font-weight: 900; color: #1565C0; }
.wish-actions { padding: 12px 16px 16px; display: flex; gap: 8px; }
.cart-btn { flex: 1; padding: 9px; background: #1565C0; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; }
.cart-btn:hover { background: #0d47a1; }
.compare-btn { padding: 9px 12px; background: #e3f2fd; color: #1565C0; border: 1px solid #bbdefb; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px; }
.compare-btn:hover { background: #bbdefb; }
.empty-state { text-align: center; padding: 100px 20px; color: #90a4ae; }
.empty-state h2 { font-size: 22px; color: #0d1b2a; margin: 16px 0 8px; }
.empty-state p { font-size: 14px; margin-bottom: 24px; }
.shop-btn { padding: 14px 36px; background: #1565C0; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; }
.shop-btn:hover { background: #0d47a1; }
</style>