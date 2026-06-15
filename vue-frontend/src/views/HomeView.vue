<template>
  <div class="home-wrap">

    <!-- TOP BOX: Sidebar + Banner + Deals all same height -->
    <div class="top-box">
      <!-- Sidebar column -->
      <div class="top-sidebar">
        <div class="top-sidebar-header">
          <span class="header-title">Categories</span>
          <span class="header-see-all" @click="$router.push('/category/Laptop')">See All &gt;</span>
        </div>
        <div
          v-for="category in categories"
          :key="category.name"
          class="top-sidebar-item"
          :class="{ active: $route.params.name === category.route }"
          @click="$router.push('/category/' + category.route)"
        >
          <div class="top-sidebar-icon">
            <v-icon size="16" color="#78909c">{{ category.icon }}</v-icon>
          </div>
          <span class="top-sidebar-name">{{ category.name }}</span>
          <v-icon size="13" color="#b0bec5">mdi-chevron-right</v-icon>
        </div>
      </div>

      <!-- Banner -->
      <div class="top-banner">
        <HeroBanner />
      </div>

      <!-- Today's Deals -->
      <div class="top-deals">
        <TodaysDeals />
      </div>
    </div>

    <!-- CATEGORY GRID — spans full top-box width -->
    <div class="catgrid-row">
      <CategoryGrid />
    </div>

    <!-- FULL WIDTH CONTENT: carousels centered across entire screen -->
    <div class="full-content">
      <div class="content-center">
        <ProductCarousel title="Featured Products" category="Laptop"      view-label="View All →" view-route="/featured"       :products="featured"    />
        <ProductCarousel title="Best Selling"       category="Accessories" view-label="Top 20"     view-route="/best-selling"   :products="bestSelling" />

        <div class="two-col">
          <TopCategories />
          <TopBrands />
        </div>

        <ProductCarousel title="Laptop"              category="Laptop"   view-label="View More" view-route="/laptops"          :products="laptops"  />
        <ProductCarousel title="Gaming Monitors"     category="Monitors" view-label="View More" view-route="/gaming-monitors"   :products="monitors" />
        <ProductCarousel title="Desktop"             category="Desktop"  view-label="View More" view-route="/desktops"          :products="desktops" />
        <ProductCarousel title="Laser Printers"      category="Printers" view-label="View More" view-route="/laser-printers"    :products="printers" />
        <ProductCarousel title="Toners & Cartridges" category="Toners"   view-label="View More" view-route="/toners"            :products="toners"   />
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
import HeroBanner      from '../components/home/HeroBanner.vue'
import TodaysDeals     from '../components/home/TodaysDeals.vue'
import CategoryGrid    from '../components/home/CategoryGrid.vue'
import TopCategories   from '../components/home/TopCategories.vue'
import TopBrands       from '../components/home/TopBrands.vue'
import ProductCarousel from '../components/home/ProductCarousel.vue'
import Footer          from '../components/layout/Footer.vue'
import { allProducts, featuredProducts, bestSellingProducts } from '../ProductDB.js'

export default {
  name: 'HomeView',
  components: { HeroBanner, TodaysDeals, CategoryGrid, TopCategories, TopBrands, ProductCarousel, Footer },
  data() {
    return {
      featured:    featuredProducts,
      bestSelling: bestSellingProducts,
      laptops:     allProducts.Laptop,
      monitors:    allProducts.Monitors,
      desktops:    allProducts.Desktop,
      printers:    allProducts.Printers,
      toners:      allProducts.Toners,
      categories: [
        { name: 'Laptop',              route: 'Laptop',      icon: 'mdi-laptop' },
        { name: 'Printers & Scanners', route: 'Printers',    icon: 'mdi-printer' },
        { name: 'Monitors',            route: 'Monitors',    icon: 'mdi-monitor' },
        { name: 'Tablet',              route: 'Tablet',      icon: 'mdi-tablet' },
        { name: 'Desktop',             route: 'Desktop',     icon: 'mdi-desktop-tower' },
        { name: 'Components',          route: 'Accessories', icon: 'mdi-cpu-64-bit' },
        { name: 'Accessories',         route: 'Accessories', icon: 'mdi-headphones' },
        { name: 'Point of Sale',       route: 'Accessories', icon: 'mdi-cash-register' },
        { name: 'Software',            route: 'Accessories', icon: 'mdi-file-code' },
        { name: 'Networking',          route: 'Accessories', icon: 'mdi-wifi' }
      ]
    }
  }
}
</script>

<style scoped>
/* ─── Page wrapper ─────────────────────────────────────────── */
.home-wrap {
  background: #f4f7fb;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* ─── TOP BOX ───────────────────────────────────────────────── */
.top-box {
  display: flex;
  align-items: stretch;
  background: white;
  border-bottom: 1px solid #e8ecf0;
  flex-shrink: 0;
}

/* ── Sidebar ── */
.top-sidebar {
  width: 220px;
  flex-shrink: 0;
  background: white;
  border-right: 1px solid #e8ecf0;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.top-sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: #eef2f7;
  border-bottom: 1px solid #e0e6ed;
  flex-shrink: 0;
}
.header-title   { font-size: 14px; font-weight: 700; color: #0d1b2a; }
.header-see-all { font-size: 12px; color: #1565C0; cursor: pointer; font-weight: 600; }
.header-see-all:hover { text-decoration: underline; }

.top-sidebar-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 14px;
  cursor: pointer;
  border-bottom: 1px solid #f0f2f5;
  transition: background 0.15s;
  flex-shrink: 0;
}
.top-sidebar-item:hover { background: #f5f8ff; }
.top-sidebar-item:hover .top-sidebar-name { color: #1565C0; }
.top-sidebar-item.active { background: #eef4ff; }
.top-sidebar-item.active .top-sidebar-name { color: #1565C0; font-weight: 700; }

.top-sidebar-icon {
  width: 26px; height: 26px;
  background: #f0f2f5;
  border-radius: 5px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.top-sidebar-item:hover .top-sidebar-icon,
.top-sidebar-item.active .top-sidebar-icon { background: #dce8ff; }
.top-sidebar-name { flex: 1; font-size: 12px; color: #455a64; font-weight: 500; transition: color 0.15s; }
.sidebar-filler { flex: 1; background: white; }

/* ── Banner ── */
.top-banner { flex: 1; min-width: 0; overflow: hidden; }

/* ── Deals ── */
.top-deals {
  width: 260px;
  flex-shrink: 0;
  overflow: hidden;
  border-left: 1px solid #e8ecf0;
  display: flex;
  flex-direction: column;
}

/* ─── CATEGORY GRID ────────────────────────────────────────── */
.catgrid-row {
  background: white;
  border-bottom: 1px solid #e8ecf0;
}

/* ─── FULL WIDTH carousels ──────────────────────────────────── */
.full-content {
  flex: 1;
  background: #f4f7fb;
  padding: 16px 0;
}
.content-center {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.two-col {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  padding: 0 40px;
  background: #f4f7fb;
}

/* ═══════════════════════════════════════════════════════════
   TABLET  ≤ 1024px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .top-sidebar { width: 180px; }
  .top-deals   { width: 220px; }
  .two-col     { padding: 0 20px; gap: 12px; }
}

/* ═══════════════════════════════════════════════════════════
   MOBILE  ≤ 768px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .top-box      { flex-direction: column; }
  .top-sidebar  { display: none; }
  .top-deals    {
    width: 100%;
    border-left: none;
    border-top: 1px solid #e8ecf0;
    max-height: 300px;
  }
  .two-col      { grid-template-columns: 1fr; padding: 0 12px; }
  .full-content { padding: 8px 0; }
}

/* ═══════════════════════════════════════════════════════════
   SMALL MOBILE  ≤ 480px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 480px) {
  .two-col { padding: 0 8px; }
}
</style>