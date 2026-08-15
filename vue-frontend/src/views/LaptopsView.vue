<template>
  <SectionPageLayout title="Laptop">
    <!-- Future: replace with API call: await api.getByCategory('Laptop') -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { allProducts } from '../ProductDB.js'

export default {
  name: 'LaptopsView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return { products: allProducts.Laptop }
  }
}
</script>