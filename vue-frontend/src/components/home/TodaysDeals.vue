<template>
  <div class="deals-panel">
    <div class="deals-header">
      <div class="deals-title-wrap">
        <span class="deals-title">Today's Deal</span>
        <span class="deals-badge">🔥 Hot</span>
      </div>
    </div>
    <div class="deals-list">
      <div
        v-for="product in products"
        :key="product.id"
        class="deal-card"
        @click="$router.push('/product/' + product.id)"
      >
        <div class="deal-img">
          <img :src="product.image" :alt="product.name" @error="onImgError" />
        </div>
        <div class="deal-info">
          <div class="deal-name">{{ product.name }}</div>
          <div class="deal-price">JOD{{ product.basePrice }}.00</div>
        </div>
        <button class="deal-cart-btn" @click.stop="openCartPopup(product)" title="Add to cart">
          <v-icon size="17" color="#1565C0">mdi-cart-plus</v-icon>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TodaysDeals',
  data() {
    return {
      products: [
        { id: 9,  name: 'LaserJet Pro M404n', basePrice: 125, image: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=160&auto=format&fit=crop' },
        { id: 7,  name: 'Samsung Odyssey G7',  basePrice: 179, image: 'https://images.unsplash.com/photo-1591370874773-6702e4b4f8e4?w=160&auto=format&fit=crop' },
        { id: 36, name: 'HyperX Cloud II',     basePrice: 49,  image: 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=160&auto=format&fit=crop' },
        { id: 1,  name: 'ROG Strix SCAR 16',   basePrice: 589, image: 'https://images.unsplash.com/photo-1603302576837-37561b2e2308?w=160&auto=format&fit=crop' }
      ]
    }
  },
  methods: {
    onImgError(e) { e.target.onerror = null; e.target.src = 'https://placehold.co/80x64?text=IMG' },
    openCartPopup(product) {
      this.$bus.emit('show-cart-popup', product)
    }
  }
}
</script>

<style scoped>
.deals-panel {
  background: white; border-left: 1px solid #eef0f4;
  height: 100%; display: flex; flex-direction: column; overflow: hidden;
}
.deals-header {
  padding: 12px 14px 10px; border-bottom: 1px solid #eef0f4;
  flex-shrink: 0; background: linear-gradient(135deg, #fff8f0, #fff);
}
.deals-title-wrap { display: flex; align-items: center; justify-content: space-between; }
.deals-title { font-size: 14px; font-weight: 800; color: #0d1b2a; }
.deals-badge {
  background: #fff0f0; color: #e53935; font-size: 11px; font-weight: 700;
  padding: 3px 10px; border-radius: 20px; border: 1px solid #ffcdd2;
}
.deals-list {
  flex: 1; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #e0e6ef transparent;
  padding: 6px; display: flex; flex-direction: column; gap: 6px;
}
.deals-list::-webkit-scrollbar { width: 3px; }
.deals-list::-webkit-scrollbar-thumb { background: #e0e6ef; border-radius: 3px; }
.deal-card {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 10px; border: 1.5px solid #e8edf5; border-radius: 10px;
  cursor: pointer; transition: all 0.2s; background: white; flex: 1; min-height: 0;
}
.deal-card:hover { border-color: #1565C0; box-shadow: 0 4px 14px rgba(21,101,192,0.12); transform: translateY(-1px); }
.deal-img {
  width: 62px; height: 52px; flex-shrink: 0; border-radius: 8px; overflow: hidden;
  background: #f5f7fa; display: flex; align-items: center; justify-content: center;
}
.deal-img img { width: 100%; height: 100%; object-fit: contain; }
.deal-info { flex: 1; min-width: 0; }
.deal-name { font-size: 11px; color: #546e7a; font-weight: 500; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.deal-price { font-size: 14px; font-weight: 900; color: #1565C0; }
.deal-cart-btn {
  background: #e3f2fd; border: 1.5px solid #bbdefb; cursor: pointer;
  padding: 6px; border-radius: 8px; flex-shrink: 0; width: 34px; height: 34px;
  display: flex; align-items: center; justify-content: center; transition: all 0.2s;
}
.deal-cart-btn:hover { background: #1565C0; border-color: #1565C0; }
.deal-cart-btn:hover .v-icon { color: white !important; }
@media (max-width: 768px) {
  .deals-panel { height: auto; border-left: none; }
  .deals-list { flex-direction: row; overflow-x: auto; overflow-y: hidden; padding: 8px; gap: 10px; }
  .deal-card { flex: 0 0 160px; flex-direction: column; align-items: flex-start; min-height: 140px; padding: 10px; }
  .deal-img { width: 100%; height: 80px; margin-bottom: 6px; }
  .deal-name { font-size: 10px; }
  .deal-price { font-size: 13px; }
}
</style>