<template>
  <div class="contact-page">

    <!-- ── Hero ── -->
    <div class="contact-hero">
      <div class="hero-inner">
        <div class="hero-tag">Get in Touch</div>
        <h1 class="hero-title">We're here to help</h1>
        <p class="hero-sub">
          Have a question, need a quote, or want to visit the store?
          Our team is ready to assist you every day except Friday.
        </p>
        <div class="hero-contact-quick">
          <a class="quick-pill" href="tel:+96278714741">
            <v-icon size="16" color="white">mdi-phone</v-icon>
            +962 787 144 741
          </a>
          <a class="quick-pill" href="mailto:malikyassen2003@gmail.com">
            <v-icon size="16" color="white">mdi-email</v-icon>
            malikyassen2003@gmail.com
          </a>
        </div>
      </div>
    </div>

    <!-- ── Info strip ── -->
    <div class="info-strip">
      <div class="info-card" v-for="item in contactInfo" :key="item.label">
        <div class="info-icon">
          <v-icon size="22" color="#1565C0">{{ item.icon }}</v-icon>
        </div>
        <div class="info-content">
          <div class="info-label">{{ item.label }}</div>
          <div class="info-value">{{ item.value }}</div>
        </div>
      </div>
    </div>

    <!-- ── Main 3-col grid ── -->
    <div class="contact-body">

      <!-- Col 1: Store Hours (expanded) -->
      <div class="main-card hours-card">
        <div class="card-title">
          <div class="card-title-icon">
            <v-icon size="20" color="#1565C0">mdi-clock-outline</v-icon>
          </div>
          Store Hours
        </div>
        <p class="card-sub">We're open 6 days a week. Come visit us in store.</p>
        <div class="hours-list">
          <div class="hours-row" v-for="h in hours" :key="h.day" :class="{ today: h.isToday, closed: h.time === 'Closed' }">
            <div class="hours-day-wrap">
              <div class="today-dot" v-if="h.isToday"></div>
              <span class="hours-day">{{ h.day }}</span>
              <span class="today-badge" v-if="h.isToday">Today</span>
            </div>
            <span class="hours-time" :class="{ 'time-closed': h.time === 'Closed' }">{{ h.time }}</span>
          </div>
        </div>
      </div>

      <!-- Col 2: Map + Directions -->
      <div class="main-card map-card">
        <div class="card-title">
          <div class="card-title-icon">
            <v-icon size="20" color="#1565C0">mdi-map-marker</v-icon>
          </div>
          Find Us
        </div>
        <p class="card-sub">Located on Wasfi Al-Tal Street in central Amman.</p>

        <div class="map-visual">
          <div class="map-pin-wrap">
            <div class="map-pin-pulse"></div>
            <div class="map-pin">
              <v-icon size="28" color="white">mdi-map-marker</v-icon>
            </div>
          </div>
          <div class="map-grid">
            <div v-for="n in 30" :key="n" class="map-cell" :class="{ highlight: [8,9,10,13,14,15,16].includes(n) }"></div>
          </div>
        </div>

        <div class="address-block">
          <div class="address-line">
            <v-icon size="16" color="#90a4ae">mdi-map-marker-outline</v-icon>
            Wasfi Al-Tal Street, Amman, Jordan
          </div>
          <div class="address-line">
            <v-icon size="16" color="#90a4ae">mdi-city</v-icon>
            Near 3rd Circle, Jabal Amman
          </div>
        </div>

        <a
          class="directions-btn"
          href="https://maps.google.com/?q=Wasfi+Al-Tal+Street+Amman+Jordan"
          target="_blank"
          rel="noopener"
        >
          <v-icon size="16" color="white">mdi-directions</v-icon>
          Get Directions on Google Maps
        </a>
      </div>

      <!-- Col 3: Social + contact channels -->
      <div class="main-card connect-card">
        <div class="card-title">
          <div class="card-title-icon">
            <v-icon size="20" color="#1565C0">mdi-share-variant</v-icon>
          </div>
          Connect With Us
        </div>
        <p class="card-sub">Reach us through any channel that works for you.</p>

        <div class="channel-list">
          <a class="channel-item facebook" href="#" target="_blank" rel="noopener">
            <div class="channel-icon-wrap">
              <v-icon size="20" color="white">mdi-facebook</v-icon>
            </div>
            <div class="channel-info">
              <div class="channel-name">Facebook</div>
              <div class="channel-handle">@MalikElectronics</div>
            </div>
            <v-icon size="16" color="#90a4ae">mdi-chevron-right</v-icon>
          </a>

          <a class="channel-item whatsapp" href="https://wa.me/96278714741" target="_blank" rel="noopener">
            <div class="channel-icon-wrap">
              <v-icon size="20" color="white">mdi-whatsapp</v-icon>
            </div>
            <div class="channel-info">
              <div class="channel-name">WhatsApp</div>
              <div class="channel-handle">+962 787 144 741</div>
            </div>
            <v-icon size="16" color="#90a4ae">mdi-chevron-right</v-icon>
          </a>

          <a class="channel-item instagram" href="#" target="_blank" rel="noopener">
            <div class="channel-icon-wrap">
              <v-icon size="20" color="white">mdi-instagram</v-icon>
            </div>
            <div class="channel-info">
              <div class="channel-name">Instagram</div>
              <div class="channel-handle">@malik_electronics</div>
            </div>
            <v-icon size="16" color="#90a4ae">mdi-chevron-right</v-icon>
          </a>

          <a class="channel-item email" href="mailto:malikyassen2003@gmail.com">
            <div class="channel-icon-wrap">
              <v-icon size="20" color="white">mdi-email-outline</v-icon>
            </div>
            <div class="channel-info">
              <div class="channel-name">Email</div>
              <div class="channel-handle">malikyassen2003@gmail.com</div>
            </div>
            <v-icon size="16" color="#90a4ae">mdi-chevron-right</v-icon>
          </a>

          <a class="channel-item phone" href="tel:+96278714741">
            <div class="channel-icon-wrap">
              <v-icon size="20" color="white">mdi-phone</v-icon>
            </div>
            <div class="channel-info">
              <div class="channel-name">Phone</div>
              <div class="channel-handle">+962 787 144 741</div>
            </div>
            <v-icon size="16" color="#90a4ae">mdi-chevron-right</v-icon>
          </a>
        </div>

        <!-- Response time note -->
        <div class="response-note">
          <v-icon size="15" color="#1565C0">mdi-lightning-bolt</v-icon>
          We typically respond within the same business day
        </div>
      </div>

    </div>

    <Footer />
  </div>
</template>

<script>
import Footer from '../components/layout/Footer.vue'

export default {
  name: 'ContactView',
  components: { Footer },
  data() {
    const day = new Date().getDay()
    return {
      contactInfo: [
        { icon: 'mdi-map-marker',    label: 'Address', value: 'Wasfi Al-Tal St, Amman, Jordan' },
        { icon: 'mdi-phone',         label: 'Phone',   value: '+962 787 144 741' },
        { icon: 'mdi-email',         label: 'Email',   value: 'malikyassen2003@gmail.com' },
        { icon: 'mdi-clock-outline', label: 'Hours',   value: '9:30 AM – 8:00 PM (Fri: Closed)' },
      ],
      hours: [
        { day: 'Saturday',  time: '9:30 AM – 8:00 PM', isToday: day === 6 },
        { day: 'Sunday',    time: '9:30 AM – 8:00 PM', isToday: day === 0 },
        { day: 'Monday',    time: '9:30 AM – 8:00 PM', isToday: day === 1 },
        { day: 'Tuesday',   time: '9:30 AM – 8:00 PM', isToday: day === 2 },
        { day: 'Wednesday', time: '9:30 AM – 8:00 PM', isToday: day === 3 },
        { day: 'Thursday',  time: '9:30 AM – 8:00 PM', isToday: day === 4 },
        { day: 'Friday',    time: 'Closed',              isToday: day === 5 },
      ]
    }
  }
}
</script>

<style scoped>
.contact-page { background: #f4f7fb; min-height: 100vh; }

/* ── Hero ── */
.contact-hero {
  background: linear-gradient(135deg, #0d1b2a 0%, #1565C0 100%);
  padding: 60px 64px;
}
.hero-inner  { max-width: 680px; }
.hero-tag {
  display: inline-block;
  background: rgba(255,255,255,0.12); color: #90caf9;
  font-size: 11px; font-weight: 800; letter-spacing: 2px;
  padding: 5px 14px; border-radius: 20px;
  text-transform: uppercase; margin-bottom: 16px;
  border: 1px solid rgba(144,202,249,0.3);
}
.hero-title { font-size: 42px; font-weight: 900; color: white; margin: 0 0 14px; }
.hero-sub   { font-size: 15px; color: rgba(207,216,220,0.85); line-height: 1.7; margin: 0 0 28px; }
.hero-contact-quick { display: flex; gap: 12px; flex-wrap: wrap; }
.quick-pill {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,0.12); color: white;
  border: 1px solid rgba(255,255,255,0.2);
  padding: 10px 18px; border-radius: 30px;
  font-size: 13px; font-weight: 700; text-decoration: none;
  transition: background 0.2s;
}
.quick-pill:hover { background: rgba(255,255,255,0.2); }

/* ── Info strip ── */
.info-strip {
  display: grid; grid-template-columns: repeat(4, 1fr);
  background: white;
  border-bottom: 1px solid #e8ecf0;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.info-card {
  display: flex; align-items: center; gap: 14px;
  padding: 22px 24px;
  border-right: 1px solid #e8ecf0;
  transition: background 0.2s;
}
.info-card:last-child { border-right: none; }
.info-card:hover { background: #f8faff; }
.info-icon {
  width: 46px; height: 46px; flex-shrink: 0;
  background: #e3f2fd; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.info-label { font-size: 11px; font-weight: 700; color: #90a4ae; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
.info-value { font-size: 13px; font-weight: 700; color: #0d1b2a; }

/* ── Body 3-col ── */
.contact-body {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  padding: 32px 64px 64px;
  max-width: 1300px;
  margin: 0 auto;
}

/* ── Cards ── */
.main-card {
  background: white;
  border: 1.5px solid #e8ecf0;
  border-radius: 18px;
  padding: 28px;
  display: flex;
  flex-direction: column;
}
.card-title {
  display: flex; align-items: center; gap: 12px;
  font-size: 17px; font-weight: 900; color: #0d1b2a;
  margin-bottom: 8px;
}
.card-title-icon {
  width: 40px; height: 40px; flex-shrink: 0;
  background: #e3f2fd; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.card-sub { font-size: 13px; color: #90a4ae; margin: 0 0 22px; line-height: 1.5; }

/* ── Hours ── */
.hours-list { display: flex; flex-direction: column; gap: 4px; }
.hours-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 10px 12px; border-radius: 10px;
  font-size: 13px; transition: background 0.15s;
}
.hours-row:hover { background: #f8fafc; }
.hours-row.today { background: #e3f2fd; }
.hours-row.closed .hours-time { color: #e53935; }
.hours-day-wrap { display: flex; align-items: center; gap: 8px; }
.today-dot { width: 7px; height: 7px; border-radius: 50%; background: #1565C0; flex-shrink: 0; }
.hours-day { font-weight: 600; color: #546e7a; }
.today-badge {
  font-size: 10px; font-weight: 800; color: #1565C0;
  background: white; padding: 2px 8px; border-radius: 20px;
  border: 1px solid #90caf9;
}
.hours-time { font-weight: 700; color: #0d1b2a; }
.time-closed { color: #e53935 !important; }

/* ── Map ── */
.map-visual {
  position: relative;
  background: #e8f4fd;
  border-radius: 14px;
  height: 160px;
  overflow: hidden;
  margin-bottom: 18px;
  display: flex; align-items: center; justify-content: center;
}
.map-grid {
  position: absolute; inset: 0;
  display: grid; grid-template-columns: repeat(6, 1fr); grid-template-rows: repeat(5, 1fr);
  gap: 2px; padding: 6px;
}
.map-cell { border-radius: 4px; background: rgba(255,255,255,0.4); }
.map-cell.highlight { background: rgba(21,101,192,0.15); }
.map-pin-wrap { position: absolute; z-index: 2; display: flex; flex-direction: column; align-items: center; }
.map-pin {
  width: 48px; height: 48px; border-radius: 50% 50% 50% 0;
  transform: rotate(-45deg);
  background: #1565C0;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 24px rgba(21,101,192,0.4);
}
.map-pin .v-icon { transform: rotate(45deg); }
.map-pin-pulse {
  position: absolute;
  width: 64px; height: 64px; border-radius: 50%;
  background: rgba(21,101,192,0.2);
  animation: pulse-ring 2s ease-out infinite;
}
@keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(1.6); opacity: 0; } }

.address-block { display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; }
.address-line {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: #546e7a; font-weight: 500;
}
.directions-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 13px;
  background: #1565C0; color: white;
  border-radius: 10px; text-decoration: none;
  font-size: 13px; font-weight: 700;
  transition: background 0.2s;
  margin-top: auto;
}
.directions-btn:hover { background: #0d47a1; }

/* ── Connect ── */
.channel-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; }
.channel-item {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 14px; border-radius: 12px;
  border: 1.5px solid #e8ecf0; text-decoration: none;
  transition: all 0.2s; cursor: pointer;
}
.channel-item:hover { transform: translateX(4px); border-color: #90caf9; box-shadow: 0 4px 14px rgba(21,101,192,0.1); }
.channel-icon-wrap {
  width: 38px; height: 38px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.channel-item.facebook .channel-icon-wrap { background: #1877f2; }
.channel-item.whatsapp .channel-icon-wrap { background: #25d366; }
.channel-item.instagram .channel-icon-wrap { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.channel-item.email .channel-icon-wrap { background: #1565C0; }
.channel-item.phone .channel-icon-wrap { background: #2e7d32; }
.channel-info { flex: 1; }
.channel-name { font-size: 13px; font-weight: 700; color: #0d1b2a; }
.channel-handle { font-size: 11.5px; color: #90a4ae; margin-top: 1px; }
.response-note {
  display: flex; align-items: center; gap: 7px;
  padding: 10px 14px; background: #e3f2fd; border-radius: 9px;
  font-size: 12px; font-weight: 600; color: #1565C0; margin-top: auto;
}

/* ── Responsive ── */
@media (max-width: 1100px) {
  .contact-body { padding: 24px 24px 48px; }
  .contact-hero { padding: 48px 24px; }
  .info-strip   { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 900px) {
  .contact-body { grid-template-columns: 1fr; }
}
@media (max-width: 560px) {
  .info-strip   { grid-template-columns: 1fr; }
  .hero-title   { font-size: 30px; }
  .hero-contact-quick { flex-direction: column; }
}
</style>