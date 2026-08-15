<template>
  <SectionPageLayout title="Best Selling">
    <template #header-actions>
      <div class="top-badge">Top 20</div>
    </template>
    <!-- Future: replace bestSellingProducts with an API call here -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { bestSellingProducts } from '../ProductDB.js'

export default {
  name: 'BestSellingView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return {
      products: bestSellingProducts
    }
  }
}
</script>

<style scoped>
.top-badge {
  background: #1565C0;
  color: white;
  font-size: 13px;
  font-weight: 700;
  padding: 8px 20px;
  border-radius: 6px;
}
</style>