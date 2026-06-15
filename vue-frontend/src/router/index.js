import { createRouter, createWebHistory } from 'vue-router'

// Core pages
import HomeView          from '../views/HomeView.vue'
import LoginView         from '../views/LoginView.vue'
import RegisterView      from '../views/RegisterView.vue'
import ProductsView      from '../views/ProductsView.vue'
import ProductDetailView from '../views/ProductDetailView.vue'
import CartView          from '../views/CartView.vue'
import WishlistView      from '../views/WishlistView.vue'
import CompareView       from '../views/CompareView.vue'

// Section pages — each is standalone, easy to swap in an API call later
import FeaturedProductsView from '../views/FeaturedProductsView.vue'
import BestSellingView      from '../views/BestSellingView.vue'
import LaptopsView          from '../views/LaptopsView.vue'
import GamingMonitorsView   from '../views/GamingMonitorsView.vue'
import DesktopView          from '../views/DesktopView.vue'
import LaserPrintersView    from '../views/LaserPrintersView.vue'
import TonersView           from '../views/TonersView.vue'

// Info pages
import AboutView   from '../views/AboutView.vue'
import ContactView from '../views/ContactView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Core
    { path: '/',                component: HomeView },
    { path: '/login',           component: LoginView },
    { path: '/register',        component: RegisterView },
    { path: '/category/:name',  component: ProductsView },
    { path: '/product/:id',     component: ProductDetailView },
    { path: '/cart',            component: CartView },
    { path: '/wishlist',        component: WishlistView },
    { path: '/compare',         component: CompareView },

    // Section pages
    { path: '/featured',        component: FeaturedProductsView },
    { path: '/best-selling',    component: BestSellingView },
    { path: '/laptops',         component: LaptopsView },
    { path: '/gaming-monitors', component: GamingMonitorsView },
    { path: '/desktops',        component: DesktopView },
    { path: '/laser-printers',  component: LaserPrintersView },
    { path: '/toners',          component: TonersView },

    // Info pages
    { path: '/about',           component: AboutView },
    { path: '/contact',         component: ContactView },

    { path: '/:pathMatch(.*)*', redirect: '/' }
  ],
  // Scroll to top on every navigation
  scrollBehavior() {
    return { top: 0 }
  }
})

export default router