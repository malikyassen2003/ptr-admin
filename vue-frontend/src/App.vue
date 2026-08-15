<template>
  <v-app style="background-color: #f4f7fb;">
    <template v-if="!isAuthPage">
      <v-main style="padding-top: 0;">
        <TopBar />
        <Header />
        <NavigationBar />
        <router-view />
      </v-main>
    </template>
    <template v-else>
      <v-main style="padding-top: 0;">
        <router-view />
      </v-main>
    </template>

    <!-- ── Global Snackbar ── -->
    <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000" location="top">
      {{ snackbarText }}
    </v-snackbar>

    <!-- ── Cart Variant Popup ── -->
    <transition name="popup-fade">
      <div v-if="popup.show" class="popup-backdrop" @click.self="closePopup">
        <div class="popup-modal">

          <!-- Close -->
          <button class="popup-close" @click="closePopup">
            <v-icon size="20" color="#546e7a">mdi-close</v-icon>
          </button>

          <!-- Product image + name header -->
          <div class="popup-header">
            <div class="popup-img-wrap">
              <img :src="popupCurrentImage" :alt="popup.product?.name" />
            </div>
            <div class="popup-header-info">
              <div class="popup-brand">{{ popup.product?.brand }}</div>
              <div class="popup-name">{{ popup.product?.name }}</div>
              <div class="popup-price">JOD {{ popupCurrentPrice }}</div>
            </div>
          </div>

          <div class="popup-body">

            <!-- Colors (only if productDB entry) -->
            <template v-if="popup.detail">
              <div class="popup-label">Color</div>
              <div class="popup-colors">
                <div
                  v-for="color in popup.detail.colors"
                  :key="color.name"
                  class="popup-color-swatch"
                  :style="{ background: color.hex }"
                  :class="{ selected: popup.selectedColor === color.name }"
                  :title="color.name"
                  @click="popupSelectColor(color)"
                >
                  <v-icon v-if="popup.selectedColor === color.name" size="13" :color="isLight(color.hex) ? '#333' : 'white'">mdi-check</v-icon>
                </div>
              </div>
              <div class="popup-color-name" v-if="popup.selectedColor">{{ popup.selectedColor }}</div>

              <div class="popup-label" style="margin-top:16px">Configuration</div>
              <div class="popup-variants">
                <div
                  v-for="v in popupVariants"
                  :key="v.label"
                  class="popup-variant"
                  :class="{ selected: popup.selectedVariant?.label === v.label, oos: v.stock === 0 }"
                  @click="v.stock > 0 && (popup.selectedVariant = v)"
                >
                  <div class="pv-label">{{ v.label }}</div>
                  <div class="pv-price">JOD {{ v.price }}</div>
                  <div class="pv-stock-dot" :class="v.stock === 0 ? 'dot-out' : v.stock <= 5 ? 'dot-low' : 'dot-ok'"></div>
                </div>
              </div>

              <!-- Stock info -->
              <div
                v-if="popup.selectedVariant"
                class="popup-stock-bar"
                :class="popup.selectedVariant.stock === 0 ? 'sbar-out' : popup.selectedVariant.stock <= 5 ? 'sbar-low' : 'sbar-ok'"
              >
                <v-icon size="14" :color="popup.selectedVariant.stock === 0 ? '#e53935' : popup.selectedVariant.stock <= 5 ? '#f57c00' : '#2e7d32'">
                  {{ popup.selectedVariant.stock === 0 ? 'mdi-close-circle-outline' : 'mdi-check-circle-outline' }}
                </v-icon>
                <span>{{ popup.selectedVariant.stock === 0 ? 'Out of Stock' : popup.selectedVariant.stock <= 5 ? `Only ${popup.selectedVariant.stock} left` : `In Stock` }}</span>
              </div>
            </template>

            <!-- Qty row -->
            <div class="popup-label" style="margin-top:16px">Quantity</div>
            <div class="popup-qty-row">
              <button class="popup-qty-btn" @click="popup.qty > 1 && popup.qty--">−</button>
              <span class="popup-qty-val">{{ popup.qty }}</span>
              <button class="popup-qty-btn" @click="popupIncrementQty">+</button>
            </div>

          </div>

          <!-- Footer actions -->
          <div class="popup-footer">
            <button class="popup-view-btn" @click="goToDetail">
              <v-icon size="15" color="#1565C0">mdi-eye-outline</v-icon>
              View Details
            </button>
            <button
              class="popup-cart-btn"
              :class="{ disabled: popup.detail && (!popup.selectedVariant || popup.selectedVariant.stock === 0) }"
              :disabled="popup.detail && (!popup.selectedVariant || popup.selectedVariant.stock === 0)"
              @click="popupAddToCart"
            >
              <v-icon size="16" color="white">mdi-cart-plus</v-icon>
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </transition>

  </v-app>
</template>

<script>
import TopBar from './components/layout/TopBar.vue'
import Header from './components/layout/Header.vue'
import NavigationBar from './components/layout/NavigationBar.vue'
import { productDB } from './ProductDB.js'

export default {
  name: 'App',
  components: { TopBar, Header, NavigationBar },
  data() {
    return {
      snackbar: false,
      snackbarText: '',
      snackbarColor: 'success',
      popup: {
        show:            false,
        product:         null,
        detail:          null,
        selectedColor:   null,
        selectedVariant: null,
        qty:             1
      }
    }
  },
  computed: {
    isAuthPage() {
      return ['/login', '/register'].includes(this.$route?.path)
    },
    popupVariants() {
      if (!this.popup.detail || !this.popup.selectedColor) return []
      const colorObj = this.popup.detail.colors.find(c => c.name === this.popup.selectedColor)
      return colorObj?.variants || []
    },
    popupCurrentPrice() {
      if (this.popup.selectedVariant) return this.popup.selectedVariant.price
      if (this.popup.detail) return this.popup.detail.colors[0]?.variants[0]?.price
      return this.popup.product?.basePrice || 0
    },
    popupCurrentImage() {
      if (!this.popup.product) return ''
      if (this.popup.detail && this.popup.selectedColor) {
        const colorObj = this.popup.detail.colors.find(c => c.name === this.popup.selectedColor)
        if (colorObj?.images?.[0]) return colorObj.images[0]
      }
      return this.popup.product.image
    }
  },
  mounted() {
    this.$bus.on('show-snackbar', ({ msg, color = 'success' }) => {
      this.snackbarText  = msg
      this.snackbarColor = color
      this.snackbar      = true
    })
    this.$bus.on('cart-updated',    () => this.$bus.emit('refresh-counts'))
    this.$bus.on('wishlist-updated',() => this.$bus.emit('refresh-counts'))
    this.$bus.on('compare-updated', () => this.$bus.emit('refresh-counts'))

    // Open variant popup
    this.$bus.on('show-cart-popup', (product) => {
      const detail = productDB[product.id] || null
      this.popup.product         = product
      this.popup.detail          = detail
      this.popup.qty             = 1
      this.popup.selectedColor   = detail ? detail.colors[0].name   : null
      this.popup.selectedVariant = detail ? detail.colors[0].variants[0] : null
      this.popup.show            = true
    })
  },
  beforeUnmount() {
    this.$bus.off('show-snackbar')
    this.$bus.off('cart-updated')
    this.$bus.off('wishlist-updated')
    this.$bus.off('compare-updated')
    this.$bus.off('show-cart-popup')
  },
  methods: {
    closePopup() {
      this.popup.show = false
    },
    popupSelectColor(color) {
      this.popup.selectedColor   = color.name
      this.popup.selectedVariant = color.variants[0]
      this.popup.qty             = 1
    },
    popupIncrementQty() {
      const maxStock = this.popup.selectedVariant?.stock ?? 99
      if (this.popup.qty < maxStock) this.popup.qty++
    },
    isLight(hex) {
      const c = hex.replace('#', '')
      const r = parseInt(c.substring(0,2), 16)
      const g = parseInt(c.substring(2,4), 16)
      const b = parseInt(c.substring(4,6), 16)
      return (r * 299 + g * 587 + b * 114) / 1000 > 160
    },
    goToDetail() {
      this.closePopup()
      this.$router.push('/product/' + this.popup.product.id)
    },
    popupAddToCart() {
      const p = this.popup
      if (!p.product) return
      if (p.detail && (!p.selectedVariant || p.selectedVariant.stock === 0)) return

      const cart  = JSON.parse(localStorage.getItem('malik_cart') || '[]')
      const price = p.detail ? p.selectedVariant.price : p.product.basePrice
      const idx   = cart.findIndex(item => item.id === p.product.id)
      const item  = {
        ...p.product,
        basePrice:       price,
        quantity:        p.qty,
        selectedColor:   p.selectedColor,
        selectedVariant: p.selectedVariant?.label
      }
      if (idx > -1) {
        cart[idx].quantity = (cart[idx].quantity || 1) + p.qty
      } else {
        cart.push(item)
      }
      localStorage.setItem('malik_cart', JSON.stringify(cart))
      this.$bus.emit('cart-updated')
      this.$bus.emit('show-snackbar', { msg: `${p.product.name} added to cart ✓` })
      this.closePopup()
    }
  }
}
</script>

<style>
/* ── Popup backdrop ── */
.popup-backdrop {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(13, 27, 42, 0.6);
  display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(4px);
  padding: 20px;
}

.popup-modal {
  background: white;
  border-radius: 20px;
  width: 100%; max-width: 520px;
  box-shadow: 0 32px 80px rgba(0,0,0,0.25);
  position: relative;
  overflow: hidden;
}

.popup-close {
  position: absolute; top: 14px; right: 14px; z-index: 2;
  background: #f4f7fb; border: none; border-radius: 50%;
  width: 34px; height: 34px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s;
}
.popup-close:hover { background: #e8ecf0; }

/* Header */
.popup-header {
  display: flex; gap: 16px; align-items: center;
  padding: 20px 20px 16px;
  border-bottom: 1px solid #f0f4f8;
  background: #f8fafc;
}
.popup-img-wrap {
  width: 88px; height: 88px; flex-shrink: 0;
  border-radius: 12px; overflow: hidden;
  border: 1px solid #e8ecf0;
  background: white;
}
.popup-img-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
.popup-brand  { font-size: 10px; font-weight: 800; color: #1565C0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }
.popup-name   { font-size: 14px; font-weight: 800; color: #0d1b2a; line-height: 1.3; margin-bottom: 6px; }
.popup-price  { font-size: 22px; font-weight: 900; color: #1565C0; }

/* Body */
.popup-body { padding: 18px 20px; }
.popup-label { font-size: 11px; font-weight: 800; color: #546e7a; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; }

/* Colors */
.popup-colors { display: flex; gap: 9px; flex-wrap: wrap; margin-bottom: 6px; }
.popup-color-swatch {
  width: 28px; height: 28px; border-radius: 50%; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid transparent; transition: all 0.2s;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.popup-color-swatch:hover { transform: scale(1.1); }
.popup-color-swatch.selected { border-color: #1565C0; transform: scale(1.2); box-shadow: 0 0 0 3px rgba(21,101,192,0.2); }
.popup-color-name { font-size: 11px; color: #90a4ae; margin-bottom: 4px; }

/* Variants */
.popup-variants { display: flex; flex-direction: column; gap: 7px; }
.popup-variant {
  position: relative;
  padding: 10px 36px 10px 14px;
  border: 2px solid #e8ecf0; border-radius: 10px;
  cursor: pointer; transition: all 0.2s;
  display: flex; align-items: center; gap: 12px;
}
.popup-variant:hover:not(.oos) { border-color: #1565C0; background: #f0f7ff; }
.popup-variant.selected { border-color: #1565C0; background: #e3f2fd; }
.popup-variant.oos { opacity: 0.4; cursor: not-allowed; }
.pv-label { font-size: 12.5px; font-weight: 600; color: #0d1b2a; flex: 1; }
.pv-price { font-size: 13px; font-weight: 800; color: #1565C0; }
.pv-stock-dot {
  position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
  width: 7px; height: 7px; border-radius: 50%;
}
.dot-ok  { background: #4caf50; }
.dot-low { background: #ff9800; }
.dot-out { background: #e53935; }

/* Stock bar */
.popup-stock-bar {
  display: inline-flex; align-items: center; gap: 6px;
  margin-top: 10px; padding: 6px 12px; border-radius: 7px;
  font-size: 12px; font-weight: 600;
}
.sbar-ok  { background: #e8f5e9; color: #2e7d32; }
.sbar-low { background: #fff3e0; color: #e65100; }
.sbar-out { background: #ffebee; color: #c62828; }

/* Qty */
.popup-qty-row {
  display: flex; align-items: center;
  border: 1.5px solid #e8ecf0; border-radius: 9px;
  width: fit-content; overflow: hidden;
}
.popup-qty-btn {
  width: 38px; height: 38px; background: #f4f7fb;
  border: none; font-size: 20px; cursor: pointer; color: #37474f;
  transition: background 0.2s;
}
.popup-qty-btn:hover { background: #e3f2fd; color: #1565C0; }
.popup-qty-val { width: 44px; text-align: center; font-size: 15px; font-weight: 700; color: #0d1b2a; }

/* Footer */
.popup-footer {
  display: flex; gap: 10px;
  padding: 16px 20px;
  border-top: 1px solid #f0f4f8;
  background: #f8fafc;
}
.popup-view-btn {
  flex: 0 0 auto; padding: 12px 18px;
  background: white; color: #1565C0;
  border: 1.5px solid #1565C0; border-radius: 10px;
  font-size: 13px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; gap: 6px;
  transition: all 0.2s;
}
.popup-view-btn:hover { background: #e3f2fd; }
.popup-cart-btn {
  flex: 1; padding: 12px;
  background: #1565C0; color: white;
  border: none; border-radius: 10px;
  font-size: 14px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 7px;
  transition: background 0.2s;
}
.popup-cart-btn:hover:not(.disabled) { background: #0d47a1; }
.popup-cart-btn.disabled { background: #b0bec5; cursor: not-allowed; }

/* Popup transition */
.popup-fade-enter-active { transition: opacity 0.2s, transform 0.25s; }
.popup-fade-leave-active { transition: opacity 0.18s, transform 0.2s; }
.popup-fade-enter-from   { opacity: 0; transform: scale(0.93); }
.popup-fade-leave-to     { opacity: 0; transform: scale(0.96); }
</style>