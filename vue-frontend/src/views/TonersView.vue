<template>
  <SectionPageLayout title="Toners & Cartridges">
    <!-- Future: replace with API call: await api.getByCategory('Toners') -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { allProducts } from '../ProductDB.js'

export default {
  name: 'TonersView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return { products: allProducts.Toners }
  }
}
</script>