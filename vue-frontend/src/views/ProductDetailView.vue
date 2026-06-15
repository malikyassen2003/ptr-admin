<template>
  <div class="detail-page" v-if="product">

    <!-- ── ZOOM LIGHTBOX ── -->
    <transition name="zoom-fade">
      <div v-if="zoomOpen" class="zoom-overlay" @click.self="zoomOpen = false">
        <button class="zoom-close" @click="zoomOpen = false">
          <v-icon color="white" size="24">mdi-close</v-icon>
        </button>
        <div class="zoom-controls">
          <button class="zoom-ctrl-btn" @click="zoomScale = Math.max(1, zoomScale - 0.5)">
            <v-icon color="white" size="18">mdi-minus</v-icon>
          </button>
          <span class="zoom-level">{{ Math.round(zoomScale * 100) }}%</span>
          <button class="zoom-ctrl-btn" @click="zoomScale = Math.min(4, zoomScale + 0.5)">
            <v-icon color="white" size="18">mdi-plus</v-icon>
          </button>
          <button class="zoom-ctrl-btn" @click="zoomScale = 1" style="margin-left:8px">
            <v-icon color="white" size="18">mdi-fit-to-screen</v-icon>
          </button>
        </div>
        <div
          class="zoom-img-wrap"
          @wheel.prevent="onZoomWheel"
        >
          <img
            :src="activeGallery[activeThumb] || product.image"
            :alt="product.name"
            :style="{ transform: `scale(${zoomScale})` }"
            class="zoom-img"
          />
        </div>
      </div>
    </transition>

    <div class="breadcrumb">
      <span class="bc-link" @click="$router.push('/')">Home</span>
      <v-icon size="14" color="#90a4ae">mdi-chevron-right</v-icon>
      <span class="bc-link" @click="$router.push('/category/' + product.category)">{{ product.category }}</span>
      <v-icon size="14" color="#90a4ae">mdi-chevron-right</v-icon>
      <span class="bc-cur">{{ product.name }}</span>
    </div>

    <div class="detail-body">
      <!-- ── Left: gallery ── -->
      <div class="detail-left">
        <div class="main-img" @click="openZoom">
          <img :src="activeGallery[activeThumb] || product.image" :alt="product.name" />
          <div class="img-badge" v-if="product.badge">{{ product.badge }}</div>
          <div class="zoom-hint">
            <v-icon size="16" color="white">mdi-magnify-plus-outline</v-icon>
            Click to zoom
          </div>
        </div>
        <div class="thumb-row">
          <div
            v-for="(img, i) in activeGallery"
            :key="i"
            class="thumb"
            :class="{ active: activeThumb === i }"
            @click="activeThumb = i"
          >
            <img :src="img" />
          </div>
        </div>
      </div>

      <!-- ── Right: info ── -->
      <div class="detail-right">
        <div class="product-brand">{{ product.brand }}</div>
        <div class="product-name">{{ product.name }}</div>

        <div class="price-row">
          <span class="current-price">JOD {{ currentPrice }}</span>
          <span class="original-price" v-if="product.originalPrice">JOD {{ product.originalPrice }}</span>
          <span class="discount-tag" v-if="product.discount">-{{ product.discount }}%</span>
        </div>

        <!-- ── Color picker ── -->
        <div class="section-label">Color</div>
        <div class="color-row">
          <div
            v-for="color in product.colors"
            :key="color.name"
            class="color-option"
            :class="{ selected: selectedColor === color.name }"
            :style="{ background: color.hex }"
            :title="color.name"
            @click="selectColor(color)"
          >
            <v-icon v-if="selectedColor === color.name" size="14" :color="isLightColor(color.hex) ? '#333' : 'white'">mdi-check</v-icon>
          </div>
        </div>
        <div class="selected-label" v-if="selectedColor">
          Color: <strong>{{ selectedColor }}</strong>
        </div>

        <!-- ── Variant picker ── -->
        <div class="section-label">Configuration</div>
        <div class="variant-row">
          <div
            v-for="variant in availableVariants"
            :key="variant.label"
            class="variant-chip"
            :class="{
              selected:     selectedVariant && selectedVariant.label === variant.label,
              'out-of-stock': variant.stock === 0
            }"
            @click="variant.stock > 0 && selectVariant(variant)"
          >
            <span class="variant-label">{{ variant.label }}</span>
            <span class="variant-price">JOD {{ variant.price }}</span>
            <span class="variant-stock-dot" :class="stockDotClass(variant.stock)"></span>
          </div>
        </div>

        <!-- ── Stock status bar ── -->
        <transition name="fade">
          <div v-if="selectedVariant" class="stock-bar" :class="stockBarClass">
            <v-icon size="15" :color="stockIconColor">{{ stockIcon }}</v-icon>
            <span>{{ stockLabel }}</span>
          </div>
        </transition>

        <!-- ── Quantity ── -->
        <div class="section-label">Quantity</div>
        <div class="qty-row">
          <button class="qty-btn" @click="qty > 1 && qty--" :disabled="!selectedVariant || currentStock === 0">−</button>
          <span class="qty-val">{{ qty }}</span>
          <button class="qty-btn" @click="incrementQty" :disabled="!selectedVariant || qty >= currentStock">+</button>
        </div>

        <!-- ── Actions ── -->
        <div class="action-row">
          <button
            class="cart-btn"
            :class="{ disabled: !selectedVariant || currentStock === 0 }"
            :disabled="!selectedVariant || currentStock === 0"
            @click="addToCart"
          >
            <v-icon color="white" size="18">mdi-cart-plus</v-icon>
            {{ currentStock === 0 ? 'Out of Stock' : 'Add to Cart' }}
          </button>
          <button class="wish-btn" @click="toggleWishlist" :title="wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'">
            <v-icon size="18" :color="wishlisted ? 'red' : '#455a64'">
              {{ wishlisted ? 'mdi-heart' : 'mdi-heart-outline' }}
            </v-icon>
          </button>
          <button class="compare-btn" @click="toggleCompare" :title="compared ? 'Remove from Compare' : 'Add to Compare'">
            <v-icon size="18" :color="compared ? '#1565C0' : '#455a64'">mdi-compare-horizontal</v-icon>
          </button>
        </div>

        <!-- ── Specs ── -->
        <div class="specs-section">
          <div class="specs-title">Specifications</div>
          <div v-for="(val, key) in product.specs" :key="key" class="spec-row">
            <span class="spec-key">{{ key }}</span>
            <span class="spec-val">{{ val }}</span>
          </div>
        </div>

        <div class="desc-section">
          <div class="specs-title">Description</div>
          <p class="desc-text">{{ product.description }}</p>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="not-found">
    <v-icon size="64" color="#e8ecf0">mdi-package-variant</v-icon>
    <div>Product not found</div>
    <button class="back-btn" @click="$router.push('/')">Go Home</button>
  </div>
</template>

<script>
import { productDB } from '../ProductDB.js'

export default {
  name: 'ProductDetailView',
  data() {
    return {
      activeThumb:     0,
      selectedColor:   null,
      selectedVariant: null,
      qty:             1,
      wishlisted:      false,
      compared:        false,
      // zoom
      zoomOpen:        false,
      zoomScale:       1,
    }
  },

  computed: {
    product() {
      return productDB[this.$route.params.id] || null
    },
    selectedColorObj() {
      if (!this.product || !this.selectedColor) return null
      return this.product.colors.find(c => c.name === this.selectedColor) || null
    },
    // Gallery changes when color changes
    activeGallery() {
      return this.selectedColorObj?.images || this.product?.gallery || []
    },
    availableVariants() {
      return this.selectedColorObj?.variants || []
    },
    currentPrice() {
      return this.selectedVariant?.price ?? this.product?.colors[0]?.variants[0]?.price ?? 0
    },
    currentStock() {
      return this.selectedVariant?.stock ?? null
    },
    stockBarClass() {
      if (this.currentStock === null) return ''
      if (this.currentStock === 0)   return 'stock-out'
      if (this.currentStock <= 5)    return 'stock-low'
      return 'stock-ok'
    },
    stockIconColor() {
      if (this.currentStock === 0)  return '#e53935'
      if (this.currentStock <= 5)   return '#f57c00'
      return '#2e7d32'
    },
    stockIcon() {
      if (this.currentStock === 0)  return 'mdi-close-circle-outline'
      if (this.currentStock <= 5)   return 'mdi-alert-circle-outline'
      return 'mdi-check-circle-outline'
    },
    stockLabel() {
      if (this.currentStock === 0)  return 'Out of Stock'
      if (this.currentStock <= 5)   return `Low Stock — only ${this.currentStock} left`
      return `In Stock (${this.currentStock} available)`
    }
  },

  mounted() {
    if (this.product) {
      const firstColor = this.product.colors[0]
      this.selectedColor   = firstColor.name
      this.selectedVariant = firstColor.variants[0]
    }
    this.syncStatus()
  },

  methods: {
    selectColor(color) {
      this.selectedColor   = color.name
      this.selectedVariant = color.variants[0]
      this.qty             = 1
      this.activeThumb     = 0  // reset to first image of new color
    },
    selectVariant(variant) {
      this.selectedVariant = variant
      this.qty             = 1
    },
    incrementQty() {
      if (this.currentStock !== null && this.qty >= this.currentStock) return
      this.qty++
    },
    openZoom() {
      this.zoomScale = 1
      this.zoomOpen  = true
    },
    onZoomWheel(e) {
      const delta = e.deltaY > 0 ? -0.25 : 0.25
      this.zoomScale = Math.min(4, Math.max(1, this.zoomScale + delta))
    },
    isLightColor(hex) {
      const c = hex.replace('#', '')
      const r = parseInt(c.substring(0,2), 16)
      const g = parseInt(c.substring(2,4), 16)
      const b = parseInt(c.substring(4,6), 16)
      return (r * 299 + g * 587 + b * 114) / 1000 > 160
    },
    stockDotClass(stock) {
      if (stock === 0)  return 'dot-out'
      if (stock <= 5)   return 'dot-low'
      return 'dot-ok'
    },
    syncStatus() {
      try {
        const wl = JSON.parse(localStorage.getItem('malik_wishlist') || '[]')
        this.wishlisted = wl.some(p => p.id === this.product?.id)
        const cp = JSON.parse(localStorage.getItem('malik_compare') || '[]')
        this.compared = cp.some(p => p.id === this.product?.id)
      } catch { /* ignore */ }
    },
    addToCart() {
      if (!this.selectedVariant || this.currentStock === 0) return
      const cart = JSON.parse(localStorage.getItem('malik_cart') || '[]')
      const idx  = cart.findIndex(p => p.id === this.product.id)
      const item = {
        ...this.product,
        quantity:  this.qty,
        basePrice: this.currentPrice,
        selectedColor:   this.selectedColor,
        selectedVariant: this.selectedVariant.label
      }
      if (idx > -1) {
        cart[idx].quantity = (cart[idx].quantity || 1) + this.qty
      } else {
        cart.push(item)
      }
      localStorage.setItem('malik_cart', JSON.stringify(cart))
      this.$bus.emit('cart-updated')
      this.$bus.emit('show-snackbar', { msg: `${this.product.name} added to cart ✓` })
    },
    toggleWishlist() {
      const wl  = JSON.parse(localStorage.getItem('malik_wishlist') || '[]')
      const idx = wl.findIndex(p => p.id === this.product.id)
      if (idx > -1) {
        wl.splice(idx, 1); this.wishlisted = false
        this.$bus.emit('show-snackbar', { msg: 'Removed from Wishlist', color: 'grey' })
      } else {
        wl.push(this.product); this.wishlisted = true
        this.$bus.emit('show-snackbar', { msg: 'Added to Wishlist ❤️', color: 'pink' })
      }
      localStorage.setItem('malik_wishlist', JSON.stringify(wl))
      this.$bus.emit('wishlist-updated')
    },
    toggleCompare() {
      const cp  = JSON.parse(localStorage.getItem('malik_compare') || '[]')
      const idx = cp.findIndex(p => p.id === this.product.id)
      if (idx > -1) {
        cp.splice(idx, 1); this.compared = false
        this.$bus.emit('show-snackbar', { msg: 'Removed from Compare', color: 'grey' })
      } else {
        if (cp.length >= 4) {
          this.$bus.emit('show-snackbar', { msg: 'You can compare up to 4 products', color: 'warning' })
          return
        }
        cp.push(this.product); this.compared = true
        this.$bus.emit('show-snackbar', { msg: 'Added to Compare' })
      }
      localStorage.setItem('malik_compare', JSON.stringify(cp))
      this.$bus.emit('compare-updated')
    }
  }
}
</script>

<style scoped>
.detail-page   { padding: 32px; background: #f4f7fb; min-height: 100vh; }
.breadcrumb    { display: flex; align-items: center; gap: 6px; margin-bottom: 28px; }
.bc-link       { font-size: 13px; color: #1565C0; cursor: pointer; font-weight: 600; }
.bc-link:hover { text-decoration: underline; }
.bc-cur        { font-size: 13px; color: #90a4ae; }

/* ── Layout ── */
.detail-body  { display: flex; gap: 40px; align-items: flex-start; }
.detail-left  { flex-shrink: 0; width: 420px; }

/* ── Gallery ── */
.main-img {
  position: relative; width: 100%; height: 340px;
  background: white; border-radius: 16px; overflow: hidden;
  border: 1px solid #e8ecf0; margin-bottom: 12px;
  cursor: zoom-in;
}
.main-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.main-img:hover img { transform: scale(1.04); }
.img-badge { position: absolute; top: 16px; left: 16px; background: #e53935; color: white; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 20px; }

.zoom-hint {
  position: absolute; bottom: 12px; right: 12px;
  background: rgba(0,0,0,0.55); color: white;
  font-size: 11px; font-weight: 600;
  padding: 5px 12px; border-radius: 20px;
  display: flex; align-items: center; gap: 5px;
  opacity: 0; transition: opacity 0.2s;
  pointer-events: none;
}
.main-img:hover .zoom-hint { opacity: 1; }

.thumb-row     { display: flex; gap: 10px; }
.thumb         { width: 72px; height: 72px; border-radius: 10px; overflow: hidden; border: 2px solid #e8ecf0; cursor: pointer; transition: border-color 0.2s; }
.thumb.active  { border-color: #1565C0; }
.thumb img     { width: 100%; height: 100%; object-fit: cover; }

/* ── ZOOM LIGHTBOX ── */
.zoom-overlay {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(0,0,0,0.92);
  display: flex; align-items: center; justify-content: center;
}
.zoom-close {
  position: absolute; top: 20px; right: 20px;
  background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
  border-radius: 50%; width: 44px; height: 44px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; z-index: 2;
}
.zoom-close:hover { background: rgba(255,255,255,0.2); }
.zoom-controls {
  position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%);
  display: flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
  border-radius: 30px; padding: 8px 16px;
  backdrop-filter: blur(10px); z-index: 2;
}
.zoom-ctrl-btn {
  background: rgba(255,255,255,0.15); border: none; border-radius: 50%;
  width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s;
}
.zoom-ctrl-btn:hover { background: rgba(255,255,255,0.3); }
.zoom-level { font-size: 13px; font-weight: 700; color: white; min-width: 48px; text-align: center; }
.zoom-img-wrap {
  width: 80vw; height: 80vh;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
}
.zoom-img {
  max-width: 100%; max-height: 100%;
  object-fit: contain;
  transition: transform 0.2s;
  transform-origin: center center;
  cursor: grab;
}

/* Zoom fade transition */
.zoom-fade-enter-active, .zoom-fade-leave-active { transition: opacity 0.25s; }
.zoom-fade-enter-from, .zoom-fade-leave-to       { opacity: 0; }

/* ── Right panel ── */
.detail-right    { flex: 1; }
.product-brand   { font-size: 12px; font-weight: 700; color: #1565C0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
.product-name    { font-size: 26px; font-weight: 900; color: #0d1b2a; margin-bottom: 16px; line-height: 1.2; }

/* ── Price ── */
.price-row       { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.current-price   { font-size: 32px; font-weight: 900; color: #1565C0; }
.original-price  { font-size: 18px; color: #b0bec5; text-decoration: line-through; }
.discount-tag    { background: #e8f5e9; color: #2e7d32; font-size: 13px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }

/* ── Labels ── */
.section-label   { font-size: 13px; font-weight: 700; color: #0d1b2a; margin-bottom: 10px; margin-top: 20px; }
.selected-label  { font-size: 12px; color: #90a4ae; margin-top: 8px; }

/* ── Color swatches ── */
.color-row { display: flex; gap: 10px; flex-wrap: wrap; }
.color-option {
  width: 32px; height: 32px;
  border-radius: 50%;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid transparent;
  transition: all 0.2s;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.color-option:hover    { transform: scale(1.1); }
.color-option.selected { border-color: #1565C0; transform: scale(1.18); box-shadow: 0 0 0 3px rgba(21,101,192,0.2); }

/* ── Variant chips ── */
.variant-row { display: flex; gap: 10px; flex-wrap: wrap; }
.variant-chip {
  position: relative;
  padding: 10px 16px;
  border: 2px solid #e8ecf0;
  border-radius: 10px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 600;
  color: #37474f;
  background: white;
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  min-width: 130px;
}
.variant-chip:hover:not(.out-of-stock) { border-color: #1565C0; background: #f0f7ff; }
.variant-chip.selected  { border-color: #1565C0; background: #e3f2fd; color: #1565C0; }
.variant-chip.out-of-stock { opacity: 0.45; cursor: not-allowed; }
.variant-label { font-size: 12.5px; }
.variant-price { font-size: 12px; color: #1565C0; font-weight: 700; }
.variant-stock-dot {
  position: absolute; top: 7px; right: 8px;
  width: 7px; height: 7px; border-radius: 50%;
}
.dot-ok  { background: #4caf50; }
.dot-low { background: #ff9800; }
.dot-out { background: #e53935; }

/* ── Stock bar ── */
.stock-bar {
  display: inline-flex; align-items: center; gap: 7px;
  margin-top: 12px; padding: 8px 14px;
  border-radius: 8px; font-size: 13px; font-weight: 600;
}
.stock-ok  { background: #e8f5e9; color: #2e7d32; }
.stock-low { background: #fff3e0; color: #e65100; }
.stock-out { background: #ffebee; color: #c62828; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }

/* ── Qty ── */
.qty-row  { display: flex; align-items: center; border: 1px solid #e8ecf0; border-radius: 8px; overflow: hidden; width: fit-content; }
.qty-btn  { width: 40px; height: 40px; background: #f4f7fb; border: none; font-size: 20px; cursor: pointer; color: #37474f; transition: background 0.2s; }
.qty-btn:hover:not(:disabled) { background: #e3f2fd; color: #1565C0; }
.qty-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.qty-val  { width: 48px; text-align: center; font-size: 15px; font-weight: 700; color: #0d1b2a; }

/* ── Actions ── */
.action-row  { display: flex; gap: 12px; margin-top: 24px; margin-bottom: 32px; }
.cart-btn {
  flex: 1; padding: 14px;
  background: #1565C0; color: white;
  border: none; border-radius: 10px;
  font-size: 15px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: background 0.2s;
}
.cart-btn:hover:not(.disabled) { background: #0d47a1; }
.cart-btn.disabled { background: #b0bec5; cursor: not-allowed; }
.wish-btn, .compare-btn {
  width: 50px; height: 50px;
  border: 2px solid #e8ecf0; border-radius: 10px;
  background: white; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s;
}
.wish-btn:hover    { border-color: red; }
.compare-btn:hover { border-color: #1565C0; }

/* ── Specs ── */
.specs-section { margin-bottom: 24px; }
.specs-title { font-size: 15px; font-weight: 800; color: #0d1b2a; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 2px solid #e8ecf0; }
.spec-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f4f7fb; font-size: 13px; }
.spec-key { width: 160px; color: #90a4ae; font-weight: 600; flex-shrink: 0; }
.spec-val { color: #0d1b2a; font-weight: 500; }
.desc-text { font-size: 14px; color: #546e7a; line-height: 1.8; }

/* ── Not found ── */
.not-found { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 60vh; gap: 16px; color: #90a4ae; font-size: 18px; }
.back-btn  { background: #1565C0; color: white; border: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
</style>