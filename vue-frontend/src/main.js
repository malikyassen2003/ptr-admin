import { createApp } from 'vue'
import App from './App.vue'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Icons (MDI)
import '@mdi/font/css/materialdesignicons.css'

// Router
import router from './router'

// Event bus — replaces Vue 2's $root.$on/$root.$emit (removed in Vue 3)
import mitt from 'mitt'
const emitter = mitt()

const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp(App)
//font awosome library
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(fas, fab)
app.component('font-awesome-icon', FontAwesomeIcon)

// Available events:
//   this.$bus.emit('show-snackbar', { msg: '...', color: 'success' })
//   this.$bus.emit('add-to-cart', product)
//   this.$bus.emit('add-to-wishlist', product)
//   this.$bus.emit('add-to-compare', product)
//   this.$bus.emit('cart-updated')
//   this.$bus.emit('wishlist-updated')
//   this.$bus.emit('compare-updated')
app.config.globalProperties.$bus = emitter

app.use(vuetify)
app.use(router)
app.mount('#app')