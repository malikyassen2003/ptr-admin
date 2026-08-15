<template>
  <div class="section-wrap">
    <div class="section-header">
      <div>
        <div class="section-title">Top 10 Brands</div>
        <div class="title-bar"></div>
      </div>
      <button class="view-all-btn" @click="$router.push('/category/Laptop')">View All Brands →</button>
    </div>
    <div class="brands-grid">
      <div
        v-for="(brand, i) in brands"
        :key="i"
        class="brand-card"
        @click="$router.push('/category/' + brand.route)"
      >
        <div class="brand-logo">
          <img :src="brand.logo" :alt="brand.name" @error="onImgError($event, brand)" />
        </div>
        <div class="brand-name">{{ brand.name }}</div>
        <div class="brand-arrow">›</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TopBrands',
  data() {
    return {
      brands: [
        { name: 'HP',        route: 'Laptop',      logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/HP_logo_2012.svg/80px-HP_logo_2012.svg.png' },
        { name: 'Dell',      route: 'Laptop',      logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Dell_Logo.svg/80px-Dell_Logo.svg.png' },
        { name: 'Lenovo',    route: 'Laptop',      logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Lenovo_logo_2015.svg/80px-Lenovo_logo_2015.svg.png' },
        { name: 'Acer',      route: 'Laptop',      logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Acer_2011.svg/80px-Acer_2011.svg.png' },
        { name: 'Asus',      route: 'Laptop',      logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/ASUS_Logo.svg/80px-ASUS_Logo.svg.png' },
        { name: 'Canon',     route: 'Printers',    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Canon_logo.svg/80px-Canon_logo.svg.png' },
        { name: 'Microsoft', route: 'Accessories', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/80px-Microsoft_logo.svg.png' },
        { name: 'Samsung',   route: 'Monitors',    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Samsung_Logo.svg/80px-Samsung_Logo.svg.png' },
        { name: 'Brother',   route: 'Printers',    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Brother_logo.svg/80px-Brother_logo.svg.png' },
        { name: 'Sony',      route: 'Accessories', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Sony_logo.svg/80px-Sony_logo.svg.png' }
      ]
    }
  },
  methods: {
    onImgError(e, brand) {
      e.target.style.display = 'none'
      e.target.parentElement.innerHTML = `<span style="font-size:13px;font-weight:800;color:#1565C0">${brand.name}</span>`
    }
  }
}
</script>

<style scoped>
.section-wrap { padding: 28px 24px; background: #f4f7fb; }

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 20px;
}
.section-title { font-size: 20px; font-weight: 800; color: #0d1b2a; letter-spacing: -0.2px; }
.title-bar { width: 36px; height: 3px; background: linear-gradient(90deg, #1565C0, #42a5f5); border-radius: 2px; margin-top: 6px; }

.view-all-btn {
  background: transparent;
  color: #1565C0;
  border: 1.5px solid #1565C0;
  padding: 9px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}
.view-all-btn:hover { background: #1565C0; color: white; box-shadow: 0 4px 12px rgba(21,101,192,0.3); }

.brands-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
  gap: 20px;
}

.brand-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: white;
  border: 1.5px solid #e8edf5;
  border-radius: 12px;
  padding: 14px 16px;
  cursor: pointer;
  transition: all 0.22s;
}
.brand-card:hover {
  border-color: #1565C0;
  box-shadow: 0 6px 20px rgba(21,101,192,0.12);
  transform: translateY(-2px);
}
.brand-card:hover .brand-name { color: #1565C0; }
.brand-card:hover .brand-arrow { color: #1565C0; transform: translateX(3px); }

.brand-logo {
  width: 44px; height: 44px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  background: #f5f7fa;
  border-radius: 8px;
  padding: 6px;
}
.brand-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }

.brand-name { flex: 1; font-size: 13.5px; font-weight: 700; color: #1a2332; transition: color 0.2s; }

.brand-arrow {
  color: #90a4ae;
  font-size: 20px;
  font-weight: 300;
  line-height: 1;
  transition: all 0.2s;
  flex-shrink: 0;
}
</style>