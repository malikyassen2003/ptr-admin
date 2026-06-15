<template>
  <div
    class="banner-wrap"
    @mouseenter="pauseAuto"
    @mouseleave="startAuto"
  >
    <div class="banner-track" :style="{ transform: `translateX(-${current * 100}%)` }">
      <div v-for="(slide, i) in slides" :key="i" class="banner-slide">
        <img :src="slide.image" :alt="slide.title" loading="lazy" />
        <div class="banner-overlay">
          <div class="banner-tag">{{ slide.tag }}</div>
          <div class="banner-title">{{ slide.title }}</div>
          <div class="banner-sub">{{ slide.sub }}</div>
          <button class="banner-btn" @click="$router.push('/category/' + slide.category)">
            Shop Now <span class="btn-arrow">→</span>
          </button>
        </div>
      </div>
    </div>
    <button class="nav-btn left" @click="prev">&#8249;</button>
    <button class="nav-btn right" @click="next">&#8250;</button>
    <div class="dots">
      <span v-for="(s, i) in slides" :key="i" class="dot" :class="{ active: i === current }" @click="goTo(i)"></span>
    </div>
    <div class="slide-counter">{{ current + 1 }} / {{ slides.length }}</div>
  </div>
</template>

<script>
export default {
  name: 'HeroBanner',
  data() {
    return {
      current: 0, timer: null,
      slides: [
        { title: 'ROG STRIX SCAR 16 & 18', sub: 'For Those Who Dare — The Ultimate Gaming Machine', tag: 'New Arrival', category: 'Laptop', image: 'https://images.unsplash.com/photo-1603302576837-37561b2e2308?w=900&auto=format&fit=crop&q=60' },
        { title: 'Gaming Desktops', sub: 'Unleash Maximum Performance at Your Desk', tag: 'Best Seller', category: 'Desktop', image: 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=900&auto=format&fit=crop&q=60' },
        { title: 'Pro Gaming Monitors', sub: 'See Every Detail in Stunning 4K Clarity', tag: 'Hot Deal', category: 'Monitors', image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=900&auto=format&fit=crop&q=60' },
        { title: 'Sony WH-1000XM5', sub: 'Industry-Leading Noise Cancellation Headphones', tag: 'Sale — 18% Off', category: 'Accessories', image: 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=900&auto=format&fit=crop&q=60' }
      ]
    }
  },
  mounted()       { this.startAuto() },
  beforeUnmount() { this.pauseAuto() },
  methods: {
    next()  { this.goTo((this.current + 1) % this.slides.length) },
    prev()  { this.goTo((this.current - 1 + this.slides.length) % this.slides.length) },
    goTo(i) { this.current = i; clearInterval(this.timer); this.startAuto() },
    startAuto() { this.timer = setInterval(() => { this.current = (this.current + 1) % this.slides.length }, 5000) },
    pauseAuto() { clearInterval(this.timer); this.timer = null }
  }
}
</script>

<style scoped>
.banner-wrap { position: relative; overflow: hidden; background: #0a1428; height: 540px; }
.banner-track { display: flex; height: 100%; transition: transform 0.6s cubic-bezier(0.4,0,0.2,1); will-change: transform; }
.banner-slide { flex: 0 0 100%; position: relative; height: 100%; }
.banner-slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
.banner-overlay { position: absolute; inset: 0; background: linear-gradient(100deg, rgba(6,12,28,0.88) 0%, rgba(10,20,40,0.5) 50%, transparent 100%); display: flex; flex-direction: column; justify-content: center; padding: 40px 52px; }
.banner-tag { display: inline-flex; align-items: center; background: rgba(21,101,192,0.9); color: white; font-size: 10px; font-weight: 800; padding: 4px 14px; border-radius: 20px; letter-spacing: 1px; margin-bottom: 14px; width: fit-content; text-transform: uppercase; border: 1px solid rgba(255,255,255,0.15); }
.banner-title { font-size: 28px; font-weight: 900; color: white; margin-bottom: 10px; line-height: 1.2; max-width: 440px; text-shadow: 0 2px 20px rgba(0,0,0,0.4); }
.banner-sub { font-size: 13.5px; color: rgba(207,216,220,0.9); margin-bottom: 26px; max-width: 380px; line-height: 1.6; }
.banner-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #1565C0; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; width: fit-content; transition: all 0.2s; box-shadow: 0 4px 16px rgba(21,101,192,0.4); }
.banner-btn:hover { background: #0d47a1; transform: translateY(-2px); }
.btn-arrow { font-size: 16px; }
.nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.92); border: none; border-radius: 50%; width: 40px; height: 40px; font-size: 26px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(0,0,0,0.2); transition: all 0.2s; z-index: 2; color: #0d1b2a; }
.nav-btn:hover { background: white; transform: translateY(-50%) scale(1.08); }
.nav-btn.left { left: 16px; }
.nav-btn.right { right: 16px; }
.dots { position: absolute; bottom: 16px; width: 100%; display: flex; justify-content: center; gap: 8px; z-index: 2; }
.dot { width: 6px; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.35); cursor: pointer; transition: all 0.3s; }
.dot.active { background: white; width: 20px; }
.slide-counter { position: absolute; top: 16px; right: 16px; background: rgba(0,0,0,0.4); color: rgba(255,255,255,0.85); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; z-index: 2; border: 1px solid rgba(255,255,255,0.1); }

/* ═══════════════════════════════════════════════════════════
   TABLET  ≤ 1024px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .banner-wrap  { height: 280px; }
  .banner-title { font-size: 22px; max-width: 340px; }
  .banner-sub   { font-size: 12px; max-width: 300px; margin-bottom: 18px; }
  .banner-overlay { padding: 28px 36px; }
  .nav-btn { width: 34px; height: 34px; font-size: 22px; }
}

/* ═══════════════════════════════════════════════════════════
   MOBILE  ≤ 768px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .banner-wrap  { height: 220px; }
  .banner-overlay { padding: 20px 22px; background: linear-gradient(100deg, rgba(6,12,28,0.92) 0%, rgba(10,20,40,0.6) 60%, transparent 100%); }
  .banner-tag   { font-size: 9px; padding: 3px 10px; margin-bottom: 8px; }
  .banner-title { font-size: 16px; max-width: 240px; margin-bottom: 6px; }
  .banner-sub   { display: none; }
  .banner-btn   { padding: 8px 16px; font-size: 11px; }
  .nav-btn      { width: 28px; height: 28px; font-size: 18px; }
  .nav-btn.left  { left: 8px; }
  .nav-btn.right { right: 8px; }
  .slide-counter { font-size: 10px; padding: 3px 8px; top: 10px; right: 10px; }
  .dots { bottom: 10px; }
}

/* ═══════════════════════════════════════════════════════════
   SMALL MOBILE  ≤ 480px
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 480px) {
  .banner-wrap  { height: 180px; }
  .banner-title { font-size: 14px; max-width: 200px; }
  .banner-btn   { padding: 6px 12px; font-size: 10px; }
}
</style>