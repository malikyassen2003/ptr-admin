<template>
  <SectionPageLayout title="Laser Printers">
    <!-- Future: replace with API call: await api.getByCategory('Printers') -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { allProducts } from '../ProductDB.js'

export default {
  name: 'LaserPrintersView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return { products: allProducts.Printers }
  }
}
</script>