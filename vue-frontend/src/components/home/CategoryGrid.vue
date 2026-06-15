<template>
  <div class="catgrid-wrap">
    <div class="catgrid-scroll">
      <div
        v-for="(cat, index) in categories"
        :key="index"
        class="cat-card"
        @click="$router.push('/category/' + cat.route)"
      >
        <div class="cat-img">
          <img :src="cat.image" :alt="cat.name" @error="onImgError($event, cat)" />
        </div>
        <div class="cat-name">{{ cat.name }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoryGrid',
  data() {
    return {
      categories: [
        { name: 'Laptop',         route: 'Laptop',       image: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8TGFwdG9wfGVufDB8fDB8fHww' },
        { name: 'Desktop',        route: 'Desktop',      image: 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZGVza3RvcCUyMGNvbXB1dGVyfGVufDB8fDB8fHww' },
        { name: 'Printers & ...', route: 'Printers',     image: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8UHJpbnRlcnN8ZW58MHx8MHx8fDA%3D' },
        { name: 'Monitors',       route: 'Monitors',     image: 'https://images.unsplash.com/photo-1666771410003-8437c4781d49?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fE1vbml0b3JzfGVufDB8fDB8fHww' },
        { name: 'Gaming Mouse',   route: 'Gaming Mouse', image: 'https://images.unsplash.com/photo-1631749352438-7d576312185d?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fEdhbWluZyUyME1vdXNlfGVufDB8fDB8fHww' },
        { name: 'Accessories',    route: 'Accessories',  image: 'https://images.unsplash.com/photo-1600861195091-690c92f1d2cc?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8cGMlMjBBY2Nlc3Nvcmllc3xlbnwwfHwwfHx8MA%3D%3D' },
        { name: 'GAMING PC',      route: 'Gaming PC',    image: 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8R0FNSU5HJTIwUEN8ZW58MHx8MHx8fDA%3D' },
        { name: 'Tablet',         route: 'Tablet',       image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&auto=format&fit=crop&q=80&ixlib=rb-4.1.0' }
      ]
    }
  },
  methods: {
    onImgError(e) {
      e.target.src = 'https://placehold.co/130x110/f7f9fc/90a4ae?text=IMG'
      e.target.onerror = null
    }
  }
}
</script>

<style scoped>
.catgrid-wrap {
  background: white;
  padding: 14px 35px;
  border-top: 1px solid #e8ecf0;
  border-bottom: 1px solid #e8ecf0;
  max-width: 1200px;
  margin: 0 auto;
}

.catgrid-scroll {
  display: flex;
  overflow-x: auto;
  scrollbar-width: none;
  gap: 12px;
  padding: 0 24px;
}
.catgrid-scroll::-webkit-scrollbar { display: none; }

.cat-card {
  flex: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 10px 5px;
  cursor: pointer;
  transition: all 0.2s;
  border-radius: 5px;
}
.cat-card:hover { background: #f0f7ff; }

.cat-img {
  width: 110px; height: 90px;
  border-radius: 20px; overflow: hidden;
  background: #f8fafc; margin-bottom: 8px;
}
.cat-img img { width: 100%; height: 100%; object-fit: cover; }

.cat-name { font-size: 12px; font-weight: 700; color: #37474f; text-align: center; }

/* ═══════════════════════════════════════════════════════════
   TABLET  ≤ 1024px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .cat-card  { flex: 0 0 110px; }
  .cat-img   { width: 90px; height: 74px; }
  .cat-name  { font-size: 11px; }
}

/* ═══════════════════════════════════════════════════════════
   MOBILE  ≤ 768px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .catgrid-wrap  { padding: 10px 0; }
  .catgrid-scroll { padding: 0 12px; gap: 8px; }
  .cat-card  { flex: 0 0 90px; padding: 8px 4px; }
  .cat-img   { width: 74px; height: 60px; }
  .cat-name  { font-size: 10px; }
}

/* ═══════════════════════════════════════════════════════════
   SMALL MOBILE  ≤ 480px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 480px) {
  .cat-card { flex: 0 0 76px; }
  .cat-img  { width: 62px; height: 50px; }
}
</style>