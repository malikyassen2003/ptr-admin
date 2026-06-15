<template>
  <SectionPageLayout title="Featured Products">
    <!-- Future: replace featuredProducts with an API call here -->
    <ProductGrid :products="products" />
  </SectionPageLayout>
</template>

<script>
import SectionPageLayout from '../components/layout/SectionPageLayout.vue'
import ProductGrid from '../components/home/ProductGrid.vue'
import { featuredProducts } from '../ProductDB.js'

export default {
  name: 'FeaturedProductsView',
  components: { SectionPageLayout, ProductGrid },
  data() {
    return {
      products: featuredProducts
      // Future API call:
      // async mounted() { this.products = await api.getFeatured() }
    }
  }
}
</script>