<template>
  <SectionPageLayout title="Gaming Monitors">
    <!-- Future: replace with API call: await api.getByCategory('Monitors') -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { allProducts } from '../ProductDB.js'

export default {
  name: 'GamingMonitorsView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return { products: allProducts.Monitors }
  }
}
</script>