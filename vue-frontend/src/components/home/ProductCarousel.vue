<template>
  <div class="carousel-section">
    <div class="carousel-inner">
      <div class="carousel-header">
        <div>
          <div class="carousel-title">{{ title }}</div>
          <div class="title-bar"></div>
        </div>
        <button class="view-more-btn" @click="$router.push(viewRoute || '/category/' + category)">
          {{ viewLabel }}
        </button>
      </div>

      <div class="carousel-wrap">
        <button class="arrow-btn left" v-show="offset > 0" @click="prev">&#8249;</button>
        <div class="carousel-track-wrap">
          <div class="carousel-track" :style="{ transform: `translateX(-${offset * (cardWidth + cardGap)}px)` }">
            <div
              v-for="product in products"
              :key="product.id"
              class="product-card"
              @click="$router.push('/product/' + product.id)"
            >
              <div class="card-img-wrap">
                <img :src="getImage(product)" :alt="product.name" @error="onImgError($event, product)" />
                <div class="card-badge" v-if="product.badge">{{ product.badge }}</div>
                <div class="card-hover-actions">
                  <button class="hov-btn" @click.stop="toggleWishlist(product)" :title="isWishlisted(product.id) ? 'Remove from wishlist' : 'Add to wishlist'">
                    <v-icon size="14" :color="isWishlisted(product.id) ? 'red' : '#555'">
                      {{ isWishlisted(product.id) ? 'mdi-heart' : 'mdi-heart-outline' }}
                    </v-icon>
                  </button>
                  <button class="hov-btn" @click.stop="toggleCompare(product)" title="Compare">
                    <v-icon size="14" :color="isCompared(product.id) ? '#1565C0' : '#555'">mdi-compare-horizontal</v-icon>
                  </button>
                  <button class="hov-btn hov-btn-cart" @click.stop="openCartPopup(product)" title="Add to cart">
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
        </div>
        <button class="arrow-btn right" v-show="offset < maxOffset" @click="next">&#8250;</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCarousel',
  props: {
    title:     { type: String, required: true },
    category:  { type: String, required: true },
    products:  { type: Array,  required: true },
    viewLabel: { type: String, default: 'View More' },
    viewRoute: { type: String, default: null }
  },
  data() {
    return { offset: 0, cardWidth: 218, cardGap: 16, visibleCount: 6, wishlist: [], compare: [] }
  },
  computed: {
    maxOffset() { return Math.max(0, this.products.length - this.visibleCount) }
  },
  mounted() {
    this.loadLists()
    this.$bus.on('refresh-counts', this.loadLists)
  },
  beforeUnmount() { this.$bus.off('refresh-counts', this.loadLists) },
  methods: {
    next() { if (this.offset < this.maxOffset) this.offset++ },
    prev() { if (this.offset > 0) this.offset-- },
    loadLists() {
      try { this.wishlist = JSON.parse(localStorage.getItem('malik_wishlist') || '[]') } catch { this.wishlist = [] }
      try { this.compare  = JSON.parse(localStorage.getItem('malik_compare')  || '[]') } catch { this.compare  = [] }
    },
    isWishlisted(id) { return this.wishlist.some(p => p.id === id) },
    isCompared(id)   { return this.compare.some(p => p.id === id) },
    getImage(product) {
      if (!product.image) return `https://placehold.co/218x200/f7f9fc/90a4ae?text=${encodeURIComponent(product.brand || 'Product')}`
      return product.image
    },
    onImgError(e, product) {
      e.target.onerror = null
      e.target.src = `https://placehold.co/218x200/f7f9fc/90a4ae?text=${encodeURIComponent(product.brand || 'Product')}`
    },
    // Opens the variant popup instead of directly adding
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
        if (this.compare.length >= 4) { this.$bus.emit('show-snackbar', { msg: 'Max 4 products', color: 'warning' }); return }
        this.compare.push(product); this.$bus.emit('show-snackbar', { msg: `${product.name} added to compare` })
      }
      localStorage.setItem('malik_compare', JSON.stringify(this.compare))
      this.$bus.emit('compare-updated')
    }
  }
}
</script>

<style scoped>
.carousel-section {
  background: white;
  border-top: 1px solid #eef0f4;
  border-bottom: 1px solid #eef0f4;
  width: 100%;
  box-sizing: border-box;
}
.carousel-inner {
  width: 100%;
  padding: 24px 44px 22px;
  box-sizing: border-box;
}
.carousel-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 22px;
}
.carousel-title { font-size: 20px; font-weight: 800; color: #0d1b2a; letter-spacing: -0.3px; }
.title-bar      { width: 36px; height: 3px; background: linear-gradient(90deg, #1565C0, #42a5f5); border-radius: 2px; margin-top: 6px; }
.view-more-btn  {
  display: flex; align-items: center; gap: 6px;
  background: #1565C0; color: white; border: none;
  padding: 9px 20px; border-radius: 8px;
  font-size: 13px; font-weight: 700; cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 3px 10px rgba(21,101,192,0.25);
}
.view-more-btn:hover { background: #0d47a1; transform: translateY(-1px); }

.carousel-wrap        { position: relative; }
.carousel-track-wrap  { width: 100%; overflow: hidden; }
.carousel-track       { display: flex; gap: 16px; transition: transform 0.4s cubic-bezier(0.4,0,0.2,1); }

.arrow-btn {
  position: absolute; top: 50%; transform: translateY(-50%); z-index: 2;
  width: 36px; height: 36px; border-radius: 50%;
  background: white; border: 1.5px solid #e0e6ef;
  font-size: 24px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 3px 12px rgba(0,0,0,0.12);
  transition: all 0.2s; color: #455a64; line-height: 1;
}
.arrow-btn.left  { left: -18px; }
.arrow-btn.right { right: -18px; }
.arrow-btn:hover { background: #1565C0; color: white; border-color: #1565C0; }

.product-card {
  flex: 0 0 218px;
  border: 1.5px solid #eef0f4; border-radius: 12px;
  overflow: hidden; cursor: pointer;
  transition: box-shadow 0.22s, transform 0.22s, border-color 0.22s;
  background: white;
}
.product-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.1); transform: translateY(-3px); border-color: #d0dcf0; }

.card-img-wrap {
  position: relative; height: 200px;
  background: #f7f9fc; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}
.card-img-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 12px; transition: transform 0.3s; }
.product-card:hover .card-img-wrap img { transform: scale(1.06); }

.card-badge {
  position: absolute; top: 10px; left: 10px;
  background: #e53935; color: white;
  font-size: 10px; font-weight: 700;
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
  cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.18s;
}
.hov-btn:hover { background: #e3f2fd; border-color: #1565C0; transform: scale(1.1); }
.hov-btn-cart:hover { background: #1565C0; border-color: #1565C0; }
.hov-btn-cart:hover .v-icon { color: white !important; }

.card-body { padding: 13px 15px 15px; }
.card-price { font-size: 16px; font-weight: 900; color: #1565C0; margin-bottom: 5px; }
.card-stars { display: flex; gap: 1px; margin-bottom: 7px; }
.card-name {
  font-size: 13px; font-weight: 600; color: #1a2332; margin-bottom: 5px; line-height: 1.4;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2;
}
.card-spec {
  font-size: 11px; color: #90a4ae; line-height: 1.45;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2;
}
</style>