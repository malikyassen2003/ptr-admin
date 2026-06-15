<template>
  <div class="footer-outer">
    <div class="policy-strip">
      <div v-for="p in policies" :key="p.label" class="policy-item">
        <div class="policy-icon-wrap">
          <v-icon size="22" color="#1565C0">{{ p.icon }}</v-icon>
        </div>
        <span class="policy-label">{{ p.label }}</span>
      </div>
    </div>

    <div class="footer-body">
      <div class="footer-grid">
        <!-- Brand + Newsletter -->
        <div class="footer-brand-col">
          <div class="footer-brand">
            <span class="brand-m">M</span>
            <span class="brand-text">MALIK ELECTRONICS</span>
          </div>
          <p class="footer-desc">Leading Jordanian distributor of IT, audiovisual, and security solutions since 2003.</p>
          <div class="subscribe-wrap">
            <input
              id="newsletter-email"
              name="newsletter-email"
              type="email"
              placeholder="Your email address"
              class="sub-input"
              autocomplete="email"
            />
            <button class="sub-btn">Subscribe</button>
          </div>
          <div class="store-badges">
            <div class="store-badge">
              <span class="badge-icon">▶</span>
              <div><div class="badge-small">Get it on</div><div class="badge-big">Google Play</div></div>
            </div>
            <div class="store-badge">
              <span class="badge-icon">🍎</span>
              <div><div class="badge-small">Download on the</div><div class="badge-big">App Store</div></div>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div>
          <div class="footer-heading">CONTACT INFO</div>
          <div class="footer-detail">
            <div class="detail-row"><span class="detail-icon">📍</span><span>Wasfi Al-Tal St, Amman, Jordan</span></div>
            <div class="detail-row"><span class="detail-icon">🕐</span><span>9:30 AM – 8:00 PM · Fri: Closed</span></div>
            <div class="detail-row"><span class="detail-icon">📞</span><span>+962 787144741</span></div>
            <div class="detail-row"><span class="detail-icon">✉️</span><span>malikyassen@gmail.com</span></div>
          </div>
        </div>

        <!-- Information -->
        <div>
          <div class="footer-heading">INFORMATION</div>
          <div
            v-for="item in infoLinks"
            :key="item.label"
            class="footer-link"
            :class="{ clickable: !!item.route }"
            @click="item.route && $router.push(item.route)"
          >
            <span class="link-arrow">›</span> {{ item.label }}
          </div>
        </div>

        <!-- My Account -->
        <div>
          <div class="footer-heading">MY ACCOUNT</div>
          <div
            v-for="item in accountLinks"
            :key="item.label"
            class="footer-link"
            :class="{ clickable: !!item.route }"
            @click="item.route && $router.push(item.route)"
          >
            <span class="link-arrow">›</span> {{ item.label }}
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <span>© 2026 Malik Electronics · All Rights Reserved</span>
        <div class="footer-socials">
          <div class="social-btn">f</div>
          <div class="social-btn">in</div>
          <div class="social-btn">𝕏</div>
          <div class="social-btn">▶</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Footer',
  data() {
    return {
      policies: [
        { label: 'Terms & Conditions', icon: 'mdi-file-document-outline' },
        { label: 'Return Policy',      icon: 'mdi-arrow-u-left-top' },
        { label: 'Support Policy',     icon: 'mdi-lifebuoy' },
        { label: 'Privacy Policy',     icon: 'mdi-information-outline' }
      ],
      infoLinks: [
        { label: 'Contact us',         route: '/contact' },
        { label: 'About Us',           route: '/about' },
        { label: 'Return Policy',      route: null },
        { label: 'Privacy Policy',     route: null },
        { label: 'Terms & Conditions', route: null },
        { label: 'Flash Sale',         route: '/category/Laptop' },
      ],
      accountLinks: [
        { label: 'Login',         route: '/login' },
        { label: 'Order History', route: null },
        { label: 'My Wishlist',   route: '/wishlist' },
        { label: 'Track Order',   route: null },
      ]
    }
  }
}
</script>

<style scoped>
.footer-outer {
  margin-left: calc(-20px);
  width: calc(100% + 220px);
}

/* ── Policy Strip ───────────────────────────────────────────── */
.policy-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  background: white;
  border-top: 1px solid #e8ecf0;
  border-bottom: 1px solid #e8ecf0;
}
.policy-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px 30px;
  border-right: 1px solid #e8ecf0;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
}
.policy-item:last-child { border-right: none; }
.policy-item:hover { background: #f0f7ff; }
.policy-icon-wrap {
  width: 48px; height: 48px;
  background: linear-gradient(135deg, #e3f2fd, #bbdefb);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(21,101,192,0.12);
}
.policy-label { font-size: 13px; font-weight: 700; color: #2c3e50; letter-spacing: 0.1px; }

/* ── Footer Body ────────────────────────────────────────────── */
.footer-body {
  background: linear-gradient(160deg, #0a1428 0%, #0d1f3c 100%);
  padding: 56px 44px 28px;
}
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1.5fr 1fr 1fr;
  gap: 52px;
}

/* Brand column */
.footer-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 14px;
}
.brand-m {
  font-size: 32px;
  font-weight: 900;
  color: #1565C0;
  line-height: 1;
  width: 40px; height: 40px;
  background: rgba(21,101,192,0.15);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  border: 1px solid rgba(21,101,192,0.3);
}
.brand-text { font-size: 13px; font-weight: 800; color: white; letter-spacing: 0.8px; }

.footer-desc { font-size: 13px; color: #607d8b; line-height: 1.85; margin-bottom: 22px; }

/* Newsletter */
.subscribe-wrap { display: flex; gap: 0; margin-bottom: 18px; border-radius: 8px; overflow: hidden; border: 1px solid #1e2d3d; }
.sub-input {
  flex: 1;
  background: #162032;
  border: none;
  padding: 11px 16px;
  color: white;
  font-size: 13px;
  outline: none;
}
.sub-input::placeholder { color: #455a64; }
.sub-btn {
  background: #1565C0;
  color: white;
  border: none;
  padding: 11px 20px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  white-space: nowrap;
}
.sub-btn:hover { background: #0d47a1; }

/* Store badges */
.store-badges { display: flex; gap: 10px; }
.store-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #162032;
  border: 1px solid #1e2d3d;
  color: white;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
}
.store-badge:hover { border-color: #1565C0; background: #1a2540; }
.badge-icon { font-size: 18px; }
.badge-small { font-size: 9px; color: #607d8b; line-height: 1; }
.badge-big   { font-size: 12px; font-weight: 700; color: white; line-height: 1.4; }

/* Headings & Links */
.footer-heading {
  font-size: 11px;
  font-weight: 800;
  color: #64b5f6;
  letter-spacing: 2px;
  margin-bottom: 20px;
  text-transform: uppercase;
  padding-bottom: 10px;
  border-bottom: 1px solid #162032;
}
.footer-detail { display: flex; flex-direction: column; gap: 10px; }
.detail-row { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: #607d8b; line-height: 1.5; }
.detail-icon { flex-shrink: 0; margin-top: 1px; }

.footer-link {
  font-size: 13px;
  color: #607d8b;
  padding: 6px 0;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color 0.2s, gap 0.2s;
}
/* only animate/highlight links that have a route */
.footer-link.clickable { cursor: pointer; }
.footer-link.clickable:hover { color: #64b5f6; gap: 10px; }
.link-arrow { color: #1565C0; font-size: 16px; line-height: 1; }

/* Bottom bar */
.footer-bottom {
  border-top: 1px solid #162032;
  margin-top: 52px;
  padding-top: 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 12px;
  color: #455a64;
}
.footer-socials { display: flex; gap: 8px; }
.social-btn {
  width: 32px; height: 32px;
  background: #162032;
  border: 1px solid #1e2d3d;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px;
  color: #607d8b;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 700;
}
.social-btn:hover { background: #1565C0; border-color: #1565C0; color: white; }
</style>