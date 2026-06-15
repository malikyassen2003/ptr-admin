<template>
  <div class="cart-page">
    <div class="page-header">
      <div class="page-title">سلة الشراء</div>
      <div class="page-count">{{ cartItems.length }} منتج</div>
    </div>

    <div v-if="cartItems.length > 0" class="cart-content">
      <div class="cart-items">
        <div v-for="(item, index) in cartItems" :key="item.id || index" class="cart-item">
          <div class="item-image">
            <img :src="item.image" :alt="item.name" />
          </div>

          <div class="item-info">
            <div class="item-brand">{{ item.brand }}</div>
            <div class="item-name">{{ item.name }}</div>
            <div class="item-specs">{{ item.shortSpec || 'قياسي' }}</div>
          </div>

          <div class="item-price">JOD {{ item.basePrice || item.price || 0 }}</div>

          <div class="item-qty">
            <button class="qty-btn" @click="decreaseQty(index)">−</button>
            <span class="qty-val">{{ item.quantity || 1 }}</span>
            <button class="qty-btn" @click="increaseQty(index)">+</button>
          </div>

          <div class="item-total">
            JOD {{ Math.round((item.basePrice || item.price || 0) * (item.quantity || 1)) }}
          </div>

          <button class="remove-btn" @click="removeFromCart(index)">
            <v-icon size="20" color="#e53935">mdi-delete-outline</v-icon>
          </button>
        </div>
      </div>

      <div class="cart-summary">
        <div class="summary-title">ملخص الطلب</div>
        <div class="summary-row">
          <span>المجموع</span>
          <span>JOD {{ subtotal }}</span>
        </div>
        <div class="summary-row">
          <span>الشحن</span>
          <span class="free">مجاني</span>
        </div>
        <div class="summary-total">
          <span>الإجمالي</span>
          <span>JOD {{ subtotal }}</span>
        </div>
        <button class="checkout-btn" @click="checkout">إتمام الشراء</button>
      </div>
    </div>

    <div v-else class="empty-state">
      <v-icon size="120" color="#e8ecf0">mdi-cart-outline</v-icon>
      <h2>السلة فارغة</h2>
      <p>لم تقم بإضافة أي منتجات بعد</p>
      <button class="shop-btn" @click="$router.push('/')">ابدأ التسوق</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CartView',
  data() {
    return { cartItems: [] }
  },
  computed: {
    subtotal() {
      return this.cartItems.reduce((sum, item) => {
        return sum + (item.basePrice || item.price || 0) * (item.quantity || 1)
      }, 0)
    }
  },
  mounted() {
    this.loadCart()
    // Listen for add-to-cart events emitted from any component
    this.$bus.on('add-to-cart', this.addToCart)
  },
  beforeUnmount() {
    this.$bus.off('add-to-cart', this.addToCart)
  },
  methods: {
    addToCart(product) {
      if (!product) return
      const existing = this.cartItems.findIndex(p => p.id === product.id)
      if (existing > -1) {
        this.cartItems[existing].quantity = (this.cartItems[existing].quantity || 1) + 1
      } else {
        this.cartItems.push({ ...product, quantity: 1 })
      }
      this.saveCart()
    },
    increaseQty(index) {
      this.cartItems[index].quantity = (this.cartItems[index].quantity || 1) + 1
      this.saveCart()
    },
    decreaseQty(index) {
      if (this.cartItems[index].quantity > 1) {
        this.cartItems[index].quantity--
        this.saveCart()
      }
    },
    removeFromCart(index) {
      const name = this.cartItems[index].name
      this.cartItems.splice(index, 1)
      this.saveCart()
      this.$bus.emit('show-snackbar', { msg: `${name} تم حذفه`, color: 'grey' })
    },
    saveCart() {
      localStorage.setItem('malik_cart', JSON.stringify(this.cartItems))
      this.$bus.emit('cart-updated')
    },
    loadCart() {
      try {
        const saved = localStorage.getItem('malik_cart')
        if (saved) this.cartItems = JSON.parse(saved)
      } catch { this.cartItems = [] }
    },
    checkout() {
      this.$bus.emit('show-snackbar', { msg: '  لسا بدها شغل ', color: 'info' })
    }
  }
}
</script>

<style scoped>
.cart-page { padding: 32px; background: #f4f7fb; min-height: 100vh; }
.page-header { margin-bottom: 32px; }
.page-title { font-size: 28px; font-weight: 900; color: #0d1b2a; }
.page-count { font-size: 14px; color: #90a4ae; }
.cart-content { display: grid; grid-template-columns: 1fr 380px; gap: 32px; }
.cart-items { background: white; border-radius: 12px; overflow: hidden; }
.cart-item { display: grid; grid-template-columns: 100px 2fr 130px 130px 130px 60px; align-items: center; padding: 20px 24px; border-bottom: 1px solid #f4f7fb; gap: 16px; }
.cart-item:last-child { border-bottom: none; }
.item-image { width: 90px; height: 90px; border-radius: 10px; overflow: hidden; border: 1px solid #e8ecf0; }
.item-image img { width: 100%; height: 100%; object-fit: cover; }
.item-brand { font-size: 12px; color: #1565C0; font-weight: 700; }
.item-name { font-size: 15px; font-weight: 700; color: #0d1b2a; margin: 4px 0; }
.item-specs { font-size: 13px; color: #90a4ae; }
.item-price, .item-total { font-size: 16px; font-weight: 800; color: #1565C0; }
.item-qty { display: flex; align-items: center; gap: 6px; }
.qty-btn { width: 32px; height: 32px; border: 1px solid #e8ecf0; background: white; border-radius: 6px; cursor: pointer; font-size: 18px; }
.qty-btn:hover { background: #f0f7ff; }
.remove-btn { background: none; border: none; cursor: pointer; }
.cart-summary { background: white; border-radius: 12px; padding: 28px; height: fit-content; position: sticky; top: 20px; }
.summary-title { font-size: 18px; font-weight: 800; margin-bottom: 20px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
.summary-total { border-top: 2px solid #e8ecf0; padding-top: 16px; margin-top: 16px; font-size: 18px; font-weight: 800; display: flex; justify-content: space-between; }
.free { color: #2e7d32; font-weight: 600; }
.checkout-btn { width: 100%; margin-top: 24px; padding: 16px; background: #1565C0; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; }
.checkout-btn:hover { background: #0d47a1; }
.empty-state { text-align: center; padding: 120px 20px; color: #90a4ae; }
.shop-btn { margin-top: 24px; padding: 14px 36px; background: #1565C0; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
</style>