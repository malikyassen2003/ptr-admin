<template>
  <div class="about-page">

    <!-- ── Hero ── -->
    <div class="hero">
      <div class="hero-content">
        <div class="hero-tag">Since 2003</div>
        <h1 class="hero-title">Jordan's Trusted<br/>Tech Partner</h1>
        <p class="hero-sub">
          For over two decades, Malik Electronics has been supplying IT professionals,
          businesses, and everyday users across Jordan with premium technology — laptops,
          desktops, monitors, printers, accessories, and more.
        </p>
        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-num">20+</span>
            <span class="stat-label">Years in Business</span>
          </div>
          <div class="stat-div"></div>
          <div class="stat-item">
            <span class="stat-num">5K+</span>
            <span class="stat-label">Happy Customers</span>
          </div>
          <div class="stat-div"></div>
          <div class="stat-item">
            <span class="stat-num">1200+</span>
            <span class="stat-label">Products Available</span>
          </div>
        </div>
      </div>
      <div class="hero-visual">
        <img
          src="https://images.unsplash.com/photo-1580894894513-541e068a3e2b?w=600&auto=format&fit=crop&q=80"
          alt="Malik Electronics Store"
        />
        <div class="hero-img-badge">
          <v-icon size="18" color="#1565C0">mdi-map-marker</v-icon>
          Wasfi Al-Tal St, Amman
        </div>
      </div>
    </div>

    <!-- ── Story ── -->
    <div class="section story-section">
      <div class="section-inner two-col">
        <div class="story-text">
          <div class="section-tag">Our Story</div>
          <h2 class="section-title">Built on trust,<br/>driven by technology</h2>
          <p class="body-text">
            Malik Electronics was founded in Amman in 2003 with a single goal: make
            world-class technology accessible to Jordanians at honest prices. What started
            as a small shop on Wasfi Al-Tal Street grew into one of the city's most
            recognised IT destinations.
          </p>
          <p class="body-text">
            Today we stock products from over 30 leading brands — HP, Dell, ASUS, Lenovo,
            Samsung, Canon, and more — and we back every sale with a team of experienced
            technicians and a robust after-sales support policy.
          </p>
        </div>
        <div class="story-img">
          <img
            src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600&auto=format&fit=crop&q=80"
            alt="Technology"
          />
        </div>
      </div>
    </div>

    <!-- ── Values ── -->
    <div class="section values-section">
      <div class="section-inner">
        <div class="section-tag center">What We Stand For</div>
        <h2 class="section-title center">Our Core Values</h2>
        <div class="values-grid">
          <div class="value-card" v-for="v in values" :key="v.title">
            <div class="value-icon">
              <v-icon size="26" color="#1565C0">{{ v.icon }}</v-icon>
            </div>
            <div class="value-title">{{ v.title }}</div>
            <div class="value-desc">{{ v.desc }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Brands ── -->
    <div class="section brands-section">
      <div class="section-inner">
        <div class="section-tag center">Authorised Retailer</div>
        <h2 class="section-title center">Brands We Carry</h2>
        <div class="brands-row">
          <div class="brand-pill" v-for="b in brands" :key="b">{{ b }}</div>
        </div>
      </div>
    </div>

    <!-- ── CTA ── -->
    <div class="cta-section">
      <div class="cta-inner">
        <h2 class="cta-title">Ready to find your next device?</h2>
        <p class="cta-sub">Browse our full catalogue or visit us in-store on Wasfi Al-Tal Street.</p>
        <div class="cta-btns">
          <button class="cta-btn primary" @click="$router.push('/')">Shop Now</button>
          <button class="cta-btn secondary" @click="$router.push('/contact')">Contact Us</button>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
import Footer from '../components/layout/Footer.vue'

export default {
  name: 'AboutView',
  components: { Footer },
  data() {
    return {
      values: [
        { icon: 'mdi-shield-check',   title: 'Genuine Products',    desc: 'Every item we sell is 100% authentic, sourced directly from authorised distributors.' },
        { icon: 'mdi-headset',        title: 'Expert Support',      desc: 'Our in-house technicians are on hand before and after your purchase — no call centres.' },
        { icon: 'mdi-tag-heart',      title: 'Fair Pricing',        desc: 'Competitive JOD prices with no hidden fees. What you see in the store is what you pay.' },
        { icon: 'mdi-truck-delivery', title: 'Fast Delivery',       desc: 'Same-day delivery across Amman and next-day shipping to the rest of Jordan.' },
        { icon: 'mdi-arrow-u-left-top', title: 'Easy Returns',      desc: '14-day hassle-free return policy on all products in original condition.' },
        { icon: 'mdi-account-group',  title: 'Community First',     desc: 'We sponsor local tech events, schools, and startups because a stronger tech community benefits everyone.' },
      ],
      brands: ['HP', 'Dell', 'ASUS', 'Lenovo', 'Acer', 'Samsung', 'Canon', 'Xerox', 'Brother', 'Sony', 'Logitech', 'Razer', 'HyperX', 'Gigabyte', 'LG']
    }
  }
}
</script>

<style scoped>
.about-page { background: #f4f7fb; min-height: 100vh; }

/* ── Hero ── */
.hero {
  display: flex; align-items: center;
  background: linear-gradient(135deg, #0d1b2a 0%, #1565C0 100%);
  padding: 64px 64px;
  gap: 48px; min-height: 480px;
}
.hero-content { flex: 1; }
.hero-tag {
  display: inline-block;
  background: rgba(255,255,255,0.12); color: #90caf9;
  font-size: 11px; font-weight: 800; letter-spacing: 2px;
  padding: 5px 14px; border-radius: 20px;
  text-transform: uppercase; margin-bottom: 18px;
  border: 1px solid rgba(144,202,249,0.3);
}
.hero-title {
  font-size: 48px; font-weight: 900; color: white;
  line-height: 1.1; margin: 0 0 18px;
}
.hero-sub { font-size: 15px; color: rgba(207,216,220,0.9); line-height: 1.75; max-width: 480px; margin-bottom: 36px; }
.hero-stats { display: flex; align-items: center; gap: 28px; }
.stat-item  { display: flex; flex-direction: column; gap: 3px; }
.stat-num   { font-size: 28px; font-weight: 900; color: white; line-height: 1; }
.stat-label { font-size: 11px; color: #90caf9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-div   { width: 1px; height: 40px; background: rgba(255,255,255,0.2); }

.hero-visual { flex-shrink: 0; position: relative; }
.hero-visual img {
  width: 380px; height: 320px; object-fit: cover;
  border-radius: 20px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.4);
}
.hero-img-badge {
  position: absolute; bottom: -14px; left: 16px;
  background: white; padding: 10px 16px;
  border-radius: 10px;
  font-size: 12px; font-weight: 700; color: #0d1b2a;
  display: flex; align-items: center; gap: 6px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

/* ── Sections ── */
.section { padding: 72px 64px; }
.section-inner { max-width: 1100px; margin: 0 auto; }
.story-section  { background: white; }
.values-section { background: #f4f7fb; }
.brands-section { background: white; }

.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }

.section-tag {
  display: inline-block;
  font-size: 11px; font-weight: 800; letter-spacing: 2px;
  text-transform: uppercase; color: #1565C0;
  background: #e3f2fd; padding: 4px 12px; border-radius: 20px;
  margin-bottom: 12px;
}
.section-tag.center { display: block; text-align: center; }
.section-title {
  font-size: 34px; font-weight: 900; color: #0d1b2a;
  line-height: 1.15; margin: 0 0 20px;
}
.section-title.center { text-align: center; }
.body-text { font-size: 14.5px; color: #546e7a; line-height: 1.85; margin-bottom: 14px; }

.story-img img { width: 100%; border-radius: 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.1); }

/* ── Values grid ── */
.values-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 20px; margin-top: 36px;
}
.value-card {
  background: white; border: 1.5px solid #e8ecf0;
  border-radius: 14px; padding: 28px 24px;
  transition: all 0.2s;
}
.value-card:hover { box-shadow: 0 8px 28px rgba(21,101,192,0.1); border-color: #90caf9; transform: translateY(-3px); }
.value-icon {
  width: 52px; height: 52px;
  background: #e3f2fd; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 14px;
}
.value-title { font-size: 15px; font-weight: 800; color: #0d1b2a; margin-bottom: 8px; }
.value-desc  { font-size: 13px; color: #607d8b; line-height: 1.65; }

/* ── Brands ── */
.brands-row {
  display: flex; flex-wrap: wrap; gap: 10px;
  margin-top: 32px; justify-content: center;
}
.brand-pill {
  padding: 8px 20px;
  background: #f4f7fb; border: 1.5px solid #e0e8f0;
  border-radius: 30px; font-size: 13px; font-weight: 700;
  color: #0d1b2a; cursor: default; transition: all 0.2s;
}
.brand-pill:hover { background: #1565C0; color: white; border-color: #1565C0; }

/* ── CTA ── */
.cta-section {
  background: linear-gradient(135deg, #0d1b2a 0%, #1565C0 100%);
  padding: 72px 64px; text-align: center;
}
.cta-inner { max-width: 600px; margin: 0 auto; }
.cta-title { font-size: 36px; font-weight: 900; color: white; margin: 0 0 14px; }
.cta-sub   { font-size: 15px; color: rgba(207,216,220,0.85); margin-bottom: 32px; }
.cta-btns  { display: flex; gap: 14px; justify-content: center; }
.cta-btn   { padding: 15px 36px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; }
.cta-btn.primary   { background: white; color: #1565C0; }
.cta-btn.primary:hover   { background: #e3f2fd; transform: translateY(-2px); }
.cta-btn.secondary { background: transparent; color: white; border: 2px solid rgba(255,255,255,0.4); }
.cta-btn.secondary:hover { background: rgba(255,255,255,0.1); border-color: white; }

@media (max-width: 900px) {
  .hero         { flex-direction: column; padding: 40px 24px; }
  .hero-visual  { display: none; }
  .hero-title   { font-size: 34px; }
  .two-col      { grid-template-columns: 1fr; gap: 30px; }
  .section      { padding: 48px 24px; }
  .values-grid  { grid-template-columns: 1fr 1fr; }
  .cta-section  { padding: 48px 24px; }
}
@media (max-width: 560px) {
  .values-grid { grid-template-columns: 1fr; }
  .cta-btns    { flex-direction: column; }
}
</style>