<template>
  <div class="compare-page">

    <!-- ── Header ── -->
    <div class="page-header">
      <div class="header-left">
        <div class="page-title">
          <v-icon size="26" color="#1565C0" style="margin-right:10px">mdi-compare-horizontal</v-icon>
          Compare Products
        </div>
        <div class="page-sub">{{ items.length }} of 4 products selected</div>
      </div>
      <button v-if="items.length > 0" class="clear-btn" @click="clearAll">
        <v-icon size="15" color="#e53935">mdi-trash-can-outline</v-icon>
        Clear All
      </button>
    </div>

    <div v-if="items.length > 0">

      <!-- ── Product Cards Row ── -->
      <div class="cards-grid">

        <!-- Filled product cards -->
        <div v-for="product in items" :key="product.id" class="product-col">
          <button class="remove-btn" @click="removeItem(product)" title="Remove">
            <v-icon size="14" color="#90a4ae">mdi-close</v-icon>
          </button>

          <!-- Image -->
          <div class="prod-img" @click="$router.push('/product/' + product.id)">
            <img :src="product.image" :alt="product.name" />
            <div class="prod-badge" v-if="product.badge">{{ product.badge }}</div>
          </div>

          <!-- Info -->
          <div class="prod-info">
            <div class="prod-brand">{{ product.brand }}</div>
            <div class="prod-name" @click="$router.push('/product/' + product.id)">{{ product.name }}</div>
            <div class="prod-price">JOD {{ product.basePrice }}<span class="prod-price-sub">.00</span></div>
            <div class="prod-desc">{{ product.shortSpec }}</div>
          </div>

          <!-- Actions -->
          <div class="prod-actions">
            <button class="cart-btn" @click="addToCart(product)">
              <v-icon size="15" color="white">mdi-cart-plus</v-icon>
              Add to Cart
            </button>
            <button
              class="wish-btn"
              :class="{ wishlisted: isWishlisted(product.id) }"
              @click="toggleWishlist(product)"
              :title="isWishlisted(product.id) ? 'Remove from Wishlist' : 'Add to Wishlist'"
            >
              <v-icon size="17" :color="isWishlisted(product.id) ? '#e53935' : '#455a64'">
                {{ isWishlisted(product.id) ? 'mdi-heart' : 'mdi-heart-outline' }}
              </v-icon>
            </button>
          </div>
        </div>

        <!-- Empty slot(s) -->
        <div v-for="n in emptySlots" :key="'empty-' + n" class="product-col empty-col">
          <div class="empty-slot">
            <div class="empty-plus">
              <v-icon size="32" color="#c8d6e0">mdi-plus</v-icon>
            </div>
            <p class="empty-label">Add a product</p>
            <button class="browse-btn" @click="$router.push('/')">Browse Store</button>
          </div>
        </div>

      </div>

      <!-- ── Specs Comparison Table ── -->
      <div class="specs-wrap">
        <div class="specs-title-row">
          <v-icon size="17" color="#1565C0" style="margin-right:8px">mdi-table-check</v-icon>
          Specifications Comparison
        </div>

        <div class="specs-table">
          <!-- Table header: blank label cell + one cell per product + empties -->
          <div class="table-row table-head">
            <div class="label-cell">Specification</div>
            <div class="val-cell" v-for="product in items" :key="product.id">
              {{ product.name }}
            </div>
            <div class="val-cell empty-val" v-for="n in emptySlots" :key="'eh-'+n">—</div>
          </div>

          <!-- Data rows -->
          <div class="table-row" v-for="field in specFields" :key="field.key">
            <div class="label-cell">{{ field.label }}</div>
            <div
              class="val-cell"
              :class="{ 'best-val': isBest(field, product) }"
              v-for="product in items"
              :key="product.id"
            >
              <span v-if="field.key === 'basePrice'" class="price-cell">JOD {{ product[field.key] }}</span>
              <span v-else-if="field.key === 'badge'">
                <span v-if="product.badge" class="badge-chip">{{ product.badge }}</span>
                <span v-else class="null-val">—</span>
              </span>
              <span v-else>{{ product[field.key] || '—' }}</span>
            </div>
            <div class="val-cell empty-val" v-for="n in emptySlots" :key="'ev-'+n">—</div>
          </div>
        </div>
      </div>

    </div>

    <!-- ── Empty state ── -->
    <div v-else class="empty-state">
      <div class="empty-icon">
        <v-icon size="72" color="#c8d6e0">mdi-compare-horizontal</v-icon>
      </div>
      <h2>No products to compare</h2>
      <p>Browse the store and click the compare icon on any product card</p>
      <button class="shop-btn" @click="$router.push('/')">Browse Products</button>
    </div>

  </div>
</template>

<script>
export default {
  name: 'CompareView',
  data() {
    return {
      items:    [],
      wishlist: [],
      specFields: [
        { key: 'category',   label: 'Category' },
        { key: 'brand',      label: 'Brand' },
        { key: 'shortSpec',  label: 'Overview' },
        { key: 'basePrice',  label: 'Price (JOD)', numeric: true, lower: false },
        { key: 'badge',      label: 'Status' },
      ]
    }
  },
  computed: {
    emptySlots() {
      // always show at least 2 columns total, max 4
      return Math.max(0, Math.max(2, 4) - this.items.length)
    }
  },
  mounted() {
    this.load()
    this.$bus.on('compare-updated', this.load)
    this.$bus.on('wishlist-updated', this.loadWishlist)
  },
  beforeUnmount() {
    this.$bus.off('compare-updated', this.load)
    this.$bus.off('wishlist-updated', this.loadWishlist)
  },
  methods: {
    load() {
      try { this.items = JSON.parse(localStorage.getItem('malik_compare') || '[]') } catch { this.items = [] }
      this.loadWishlist()
    },
    loadWishlist() {
      try { this.wishlist = JSON.parse(localStorage.getItem('malik_wishlist') || '[]') } catch { this.wishlist = [] }
    },
    isWishlisted(id) { return this.wishlist.some(p => p.id === id) },

    removeItem(product) {
      this.items = this.items.filter(p => p.id !== product.id)
      localStorage.setItem('malik_compare', JSON.stringify(this.items))
      this.$bus.emit('compare-updated')
      this.$bus.emit('show-snackbar', { msg: `${product.name} removed`, color: 'grey' })
    },
    clearAll() {
      this.items = []
      localStorage.setItem('malik_compare', '[]')
      this.$bus.emit('compare-updated')
    },
    addToCart(product) {
      const cart = JSON.parse(localStorage.getItem('malik_cart') || '[]')
      const idx  = cart.findIndex(p => p.id === product.id)
      if (idx > -1) cart[idx].quantity = (cart[idx].quantity || 1) + 1
      else cart.push({ ...product, quantity: 1 })
      localStorage.setItem('malik_cart', JSON.stringify(cart))
      this.$bus.emit('cart-updated')
      this.$bus.emit('show-snackbar', { msg: `${product.name} added to cart ✓` })
    },
    toggleWishlist(product) {
      const idx = this.wishlist.findIndex(p => p.id === product.id)
      if (idx > -1) {
        this.wishlist.splice(idx, 1)
        this.$bus.emit('show-snackbar', { msg: 'Removed from wishlist', color: 'grey' })
      } else {
        this.wishlist.push(product)
        this.$bus.emit('show-snackbar', { msg: `${product.name} added to wishlist ❤️`, color: 'pink' })
      }
      localStorage.setItem('malik_wishlist', JSON.stringify(this.wishlist))
      this.$bus.emit('wishlist-updated')
    },
    isBest(field, product) {
      if (!field.numeric || this.items.length < 2) return false
      const vals = this.items.map(p => Number(p[field.key]) || 0)
      const val  = Number(product[field.key]) || 0
      return field.lower ? val === Math.min(...vals) : val === Math.max(...vals)
    }
  }
}
</script>

<style scoped>
.compare-page { padding: 32px 40px; background: #f4f7fb; min-height: 100vh; }

/* ── Header ── */
.page-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  margin-bottom: 28px;
}
.page-title {
  font-size: 26px; font-weight: 900; color: #0d1b2a;
  display: flex; align-items: center; margin-bottom: 4px;
}
.page-sub  { font-size: 13px; color: #90a4ae; padding-left: 36px; }
.clear-btn {
  display: flex; align-items: center; gap: 7px;
  padding: 9px 18px;
  background: white; border: 1.5px solid #ffcdd2;
  border-radius: 8px; font-size: 13px; font-weight: 700;
  color: #e53935; cursor: pointer; transition: all 0.2s;
}
.clear-btn:hover { background: #ffebee; border-color: #e53935; }

/* ── Cards grid ── always 4 equal columns ── */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

/* ── Product column ── */
.product-col {
  background: white;
  border: 1.5px solid #e8ecf0;
  border-radius: 16px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  position: relative;
  transition: box-shadow 0.2s, border-color 0.2s;
}
.product-col:hover { box-shadow: 0 8px 28px rgba(21,101,192,0.1); border-color: #bbdefb; }

/* remove button */
.remove-btn {
  position: absolute; top: 10px; right: 10px; z-index: 2;
  width: 26px; height: 26px;
  background: #f5f5f5; border: 1px solid #e0e0e0;
  border-radius: 50%; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s;
}
.remove-btn:hover { background: #ffebee; border-color: #e53935; }

/* product image */
.prod-img {
  width: 100%; height: 200px;
  overflow: hidden; position: relative;
  background: #f7f9fc; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
}
.prod-img img {
  width: 100%; height: 100%;
  object-fit: contain; padding: 16px;
  transition: transform 0.3s;
}
.product-col:hover .prod-img img { transform: scale(1.05); }
.prod-badge {
  position: absolute; top: 10px; left: 10px;
  background: #e53935; color: white;
  font-size: 10px; font-weight: 700;
  padding: 3px 10px; border-radius: 20px;
}

/* product info */
.prod-info { padding: 16px 16px 12px; flex: 1; display: flex; flex-direction: column; gap: 5px; }
.prod-brand {
  font-size: 10px; font-weight: 800; color: #1565C0;
  text-transform: uppercase; letter-spacing: 1px;
}
.prod-name {
  font-size: 14px; font-weight: 700; color: #0d1b2a;
  line-height: 1.35; cursor: pointer;
  display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.prod-name:hover { color: #1565C0; }
.prod-price {
  font-size: 22px; font-weight: 900; color: #1565C0;
  margin: 4px 0 2px;
}
.prod-price-sub { font-size: 14px; font-weight: 700; }
.prod-desc {
  font-size: 11.5px; color: #90a4ae; line-height: 1.5;
  display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

/* product actions */
.prod-actions {
  padding: 12px 14px 16px;
  display: flex; gap: 8px;
  border-top: 1px solid #f4f7fb;
}
.cart-btn {
  flex: 1; padding: 10px 8px;
  background: #1565C0; color: white;
  border: none; border-radius: 9px;
  font-size: 12.5px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: background 0.2s;
}
.cart-btn:hover { background: #0d47a1; }
.wish-btn {
  width: 42px; height: 42px; flex-shrink: 0;
  border: 1.5px solid #e8ecf0; border-radius: 9px;
  background: white; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s;
}
.wish-btn:hover      { border-color: #e53935; background: #fff0f0; }
.wish-btn.wishlisted { border-color: #e53935; background: #fff0f0; }

/* empty slot */
.empty-col {
  border: 2px dashed #d8e4ed !important;
  background: #fafcfe !important;
  box-shadow: none !important;
}
.empty-col:hover { border-color: #90caf9 !important; }
.empty-slot {
  flex: 1;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 12px; padding: 40px 20px;
  text-align: center;
}
.empty-plus {
  width: 64px; height: 64px;
  border: 2px dashed #c8d6e0;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  background: #f0f6fb;
}
.empty-label { font-size: 13px; color: #b0bec5; font-weight: 600; margin: 0; }
.browse-btn {
  padding: 9px 22px;
  background: #e3f2fd; color: #1565C0;
  border: 1.5px solid #90caf9;
  border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;
  transition: all 0.2s;
}
.browse-btn:hover { background: #1565C0; color: white; border-color: #1565C0; }

/* ── Specs table ── */
.specs-wrap {
  background: white;
  border: 1.5px solid #e8ecf0;
  border-radius: 16px;
  overflow: hidden;
}
.specs-title-row {
  display: flex; align-items: center;
  padding: 16px 20px;
  background: linear-gradient(90deg, #1565C0, #1976d2);
  color: white;
  font-size: 14px; font-weight: 700;
}
.specs-table { width: 100%; }

.table-row {
  display: grid;
  grid-template-columns: 160px repeat(4, 1fr);
  border-bottom: 1px solid #f0f4f8;
}
.table-row:last-child { border-bottom: none; }
.table-row:hover:not(.table-head) { background: #fafcff; }

.table-head { background: #f8fafc; }
.table-head .label-cell,
.table-head .val-cell {
  font-size: 12px; font-weight: 800;
  color: #546e7a; padding: 12px 16px;
  text-transform: uppercase; letter-spacing: 0.5px;
}
.table-head .val-cell {
  color: #0d1b2a;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.label-cell {
  padding: 14px 20px;
  font-size: 12.5px; font-weight: 700; color: #607d8b;
  background: #f8fafc;
  border-right: 1px solid #e8ecf0;
  display: flex; align-items: center;
}
.val-cell {
  padding: 14px 16px;
  font-size: 13px; color: #0d1b2a;
  border-right: 1px solid #f0f4f8;
  display: flex; align-items: center;
}
.val-cell:last-child { border-right: none; }

.best-val   { background: #e8f5e9 !important; color: #2e7d32 !important; font-weight: 700; }
.empty-val  { color: #d0d9e0 !important; }
.null-val   { color: #cfd8dc; }
.price-cell { font-weight: 800; color: #1565C0; }
.badge-chip {
  background: #e3f2fd; color: #1565C0;
  font-size: 11px; font-weight: 700;
  padding: 2px 10px; border-radius: 20px;
  border: 1px solid #bbdefb;
}

/* ── Empty state ── */
.empty-state {
  text-align: center; padding: 100px 20px; color: #90a4ae;
}
.empty-icon {
  width: 120px; height: 120px;
  background: #f0f6fb; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 24px;
}
.empty-state h2 { font-size: 22px; color: #0d1b2a; margin: 0 0 8px; }
.empty-state p  { font-size: 14px; margin-bottom: 24px; }
.shop-btn {
  padding: 14px 36px; background: #1565C0; color: white;
  border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer;
}
.shop-btn:hover { background: #0d47a1; }

/* ── Responsive ── */
@media (max-width: 1100px) {
  .compare-page { padding: 24px 20px; }
}
@media (max-width: 900px) {
  .cards-grid    { grid-template-columns: repeat(2, 1fr); }
  .table-row     { grid-template-columns: 130px repeat(4, 1fr); overflow-x: auto; }
  .compare-page  { padding: 20px 12px; }
}
@media (max-width: 560px) {
  .cards-grid { grid-template-columns: 1fr; }
}
</style>