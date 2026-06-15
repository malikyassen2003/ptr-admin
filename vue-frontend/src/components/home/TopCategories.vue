<template>
  <div class="section-wrap">
    <div class="section-header">
      <div>
        <div class="section-title">Top 10 Categories</div>
        <div class="title-bar"></div>
      </div>
      <button class="view-btn" @click="$router.push('/category/Laptop')">View All Categories</button>
    </div>
    <div class="cat-grid">
      <div
        v-for="(cat, index) in categories"
        :key="index"
        class="cat-row"
        @click="$router.push('/category/' + cat.route)"
      >
        <div class="cat-img">
          <img :src="cat.image" :alt="cat.name" @error="onImgError($event)" />
        </div>
        <div class="cat-name">{{ cat.name }}</div>
        <div class="cat-arrow">›</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TopCategories',
  data() {
    return {
      categories: [
        { name: 'Laptop',               route: 'Laptop',       image: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=100&auto=format&fit=crop' },
        { name: 'Gaming Laptops',       route: 'Laptop',       image: 'https://images.unsplash.com/photo-1716681863353-afe3b9f5d69d?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDAzfHxnYW1pbmclMjBsYXB0b3B8ZW58MHx8MHx8fDA%3D' },
        { name: 'Business Laptops',     route: 'Laptop',       image: 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&auto=format&fit=crop' },
        { name: 'Everyday Use Laptops', route: 'Laptop',       image: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=100&auto=format&fit=crop' },
        { name: 'Desktop',              route: 'Desktop',      image: 'https://images.unsplash.com/photo-1634003309303-442c7518f9e9?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGRlc2t0b3AlMjBjYXNlfGVufDB8fDB8fHww' },
        { name: 'Printers & Scanners',  route: 'Printers',     image: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=100&auto=format&fit=crop' },
        { name: 'Monitors',             route: 'Monitors',     image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=100&auto=format&fit=crop' },
        { name: 'Accessories',          route: 'Accessories',  image: 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=100&auto=format&fit=crop' },
        { name: 'Tablets',              route: 'Tablet',       image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=100&auto=format&fit=crop' },
        { name: 'Gaming Mouse',         route: 'Gaming Mouse', image: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=100&auto=format&fit=crop' }
      ]
    }
  },
  methods: {
    onImgError(e) { e.target.onerror = null; e.target.src = ''; e.target.style.display = 'none' }
  }
}
</script>

<style scoped>
.section-wrap { padding: 28px 24px; background: #f4f7fb; }
.section-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 10px;
}
.section-title { font-size: 20px; font-weight: 800; color: #0d1b2a; letter-spacing: -0.2px; }
.title-bar { width: 36px; height: 3px; background: linear-gradient(90deg, #1565C0, #42a5f5); border-radius: 2px; margin-top: 6px; }
.view-btn {
  background: #1565C0;
  color: white;
  border: none;
  padding: 10px 22px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 3px 10px rgba(21,101,192,0.25);
  transition: all 0.2s;
}
.view-btn:hover { background: #0d47a1; transform: translateY(-1px); box-shadow: 0 5px 14px rgba(21,101,192,0.35); }

.cat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.cat-row {
  display: flex;
  align-items: center;
  gap: 14px;
  background: white;
  border: 1.5px solid #e8edf5;
  border-radius: 12px;
  padding: 14px 16px;
  cursor: pointer;
  transition: all 0.22s;
}
.cat-row:hover {
  border-color: #1565C0;
  box-shadow: 0 6px 20px rgba(21,101,192,0.12);
  transform: translateY(-2px);
}
.cat-row:hover .cat-name { color: #1565C0; }
.cat-row:hover .cat-arrow { color: #1565C0; transform: translateX(3px); }

.cat-img {
  width: 60px; height: 60px;
  border-radius: 10px;
  overflow: hidden;
  flex-shrink: 0;
  background: #f0f4f8;
  display: flex; align-items: center; justify-content: center;
}
.cat-img img { width: 100%; height: 100%; object-fit: cover; }

.cat-name { flex: 1; font-size: 13.5px; font-weight: 600; color: #1a2332; transition: color 0.2s; }

.cat-arrow {
  color: #90a4ae;
  font-size: 20px;
  font-weight: 300;
  line-height: 1;
  transition: all 0.2s;
  flex-shrink: 0;
}
</style>