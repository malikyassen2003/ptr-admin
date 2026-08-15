const Q = '?w=600&auto=format&fit=crop&q=80&ixlib=rb-4.1.0'

const IMG = {
  laptop1:   `https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGxhcHRvcHN8ZW58MHx8MHx8fDA%3D}${Q}`,
  laptop2:   `https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fGxhcHRvcHN8ZW58MHx8MHx8fDA%3D${Q}`,
  laptop3:   `https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2${Q}`,
  laptop4:   `https://images.unsplash.com/photo-1541807084-5c52b6b3adef${Q}`,
  laptop5:   `https://images.unsplash.com/photo-1588872657578-7efd1f1555ed${Q}`,
  desktop1:  `https://images.unsplash.com/photo-1618339220157-daa2cd9ade56?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGRlc2t0b3AlMjBwY3xlbnwwfHwwfHx8MA%3D%3D${Q}`,
  desktop2:  `https://images.unsplash.com/photo-1587302912306-cf1ed9c33146?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fGRlc2t0b3AlMjBwY3xlbnwwfHwwfHx8MA%3D%3D${Q}`,
  desktop3:  `https://images.unsplash.com/photo-1660855551740-4474188debdb?w=1000&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZGVza3RvcCUyMGNhc2V8ZW58MHx8MHx8fDA%3D${Q}`,
  monitor1:  `https://images.unsplash.com/photo-1666771410003-8437c4781d49${Q}`,
  monitor2:  `https://images.unsplash.com/photo-1527443224154-c4a3942d3acf${Q}`,
  monitor3:  `https://images.unsplash.com/photo-1591370874773-6702e4b4f8e4${Q}`,
  printer1:  `https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6${Q}`,
  printer2:  `https://images.unsplash.com/photo-1563689985107-34e3b89d5b4c${Q}`,
  accessory1:`https://images.unsplash.com/photo-1600861195091-690c92f1d2cc${Q}`,
  headset1:  `https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb${Q}`,
  headset2:  `https://images.unsplash.com/photo-1505740420928-5e560c06d30e${Q}`,
  mouse1:    `https://images.unsplash.com/photo-1631749352438-7d576312185d${Q}`,
  mouse2:    `https://images.unsplash.com/photo-1527864550417-7fd91fc51a46${Q}`,
  gamingpc1: `https://images.unsplash.com/photo-1587202372775-e229f172b9d7${Q}`,
  gamingpc2: `https://images.unsplash.com/photo-1719927604476-dc404b85358f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MzB8fGdhbWluZ3BjfGVufDB8fDB8fHww${Q}`,
  tablet1:   `https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0${Q}`,
  tablet2:   `https://images.unsplash.com/photo-1632634571518-ece4b8e2b2f7${Q}`,
  toner1:    `https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6${Q}`,
}

export const allProducts = {
  Laptop: [
    { id: 1,  brand: 'ASUS',   name: 'ROG Strix SCAR 16',       shortSpec: 'Intel i9 | RTX 4090 | 32GB RAM',          basePrice: 589,  badge: 'Hot',  image: IMG.laptop2,  category: 'Laptop' },
    { id: 2,  brand: 'Lenovo', name: 'ThinkPad X1 Carbon',       shortSpec: 'Intel i7 | 16GB RAM | 512GB SSD',         basePrice: 459,  badge: null,   image: IMG.laptop3,  category: 'Laptop' },
    { id: 3,  brand: 'HP',     name: 'Spectre x360',             shortSpec: 'Intel i7 | 16GB RAM | 1TB SSD',           basePrice: 399,  badge: 'Sale', image: IMG.laptop1,  category: 'Laptop' },
    { id: 4,  brand: 'Dell',   name: 'XPS 15',                   shortSpec: 'Intel i9 | 32GB RAM | RTX 3050',          basePrice: 520,  badge: null,   image: IMG.laptop4,  category: 'Laptop' },
    { id: 19, brand: 'HP',     name: 'HP Victus 15',             shortSpec: 'Intel i5-13420H | RTX 3050 | 16GB',       basePrice: 629,  badge: 'Hot',  image: IMG.laptop5,  category: 'Laptop' },
    { id: 20, brand: 'Acer',   name: 'Acer Nitro ANV15',         shortSpec: 'Intel i5 13th Gen | RTX 4050 | 16GB',     basePrice: 589,  badge: 'New',  image: IMG.laptop2,  category: 'Laptop' },
    { id: 21, brand: 'ASUS',   name: 'ASUS Vivobook Go 15',      shortSpec: 'AMD Ryzen 5 | 8GB | 512GB SSD',           basePrice: 359,  badge: null,   image: IMG.laptop3,  category: 'Laptop' },
    { id: 22, brand: 'ASUS',   name: 'ASUS TUF Gaming F16',      shortSpec: 'Intel i7-13620H | RTX 4060 | 16GB',       basePrice: 819,  badge: 'Hot',  image: IMG.laptop2,  category: 'Laptop' }
  ],
  Desktop: [
    { id: 5,  brand: 'HP',     name: 'Pavilion Desktop',         shortSpec: 'Intel i5 | 8GB RAM | 512GB SSD',          basePrice: 299,  badge: null,   image: IMG.desktop1, category: 'Desktop' },
    { id: 6,  brand: 'Dell',   name: 'OptiPlex 7090',            shortSpec: 'Intel i7 | 16GB RAM | 1TB HDD',           basePrice: 389,  badge: 'New',  image: IMG.desktop2, category: 'Desktop' },
    { id: 23, brand: 'Custom', name: 'Gaming PC Core i7-13700F', shortSpec: 'i7-13700F | RTX 4070 | 32GB | 1TB NVMe', basePrice: 977,  badge: 'New',  image: IMG.gamingpc1,category: 'Desktop' },
    { id: 24, brand: 'Custom', name: 'Gaming PC Core i5-12400F', shortSpec: 'i5-12400F | RTX 3060 | 16GB | 500GB',    basePrice: 537,  badge: null,   image: IMG.gamingpc2,category: 'Desktop' },
    { id: 25, brand: 'Custom', name: 'PC Gaming PC5',            shortSpec: 'i7-13700F | 500GB NVMe | 16GB',           basePrice: 717,  badge: null,   image: IMG.desktop3, category: 'Desktop' },
    { id: 26, brand: 'Custom', name: 'PC Gaming PC4',            shortSpec: 'i5-12400F | 500GB NVMe | 16GB',           basePrice: 617,  badge: 'Sale', image: IMG.desktop2, category: 'Desktop' }
  ],
  Monitors: [
    { id: 7,  brand: 'Samsung',   name: 'Odyssey G7',            shortSpec: '27" | 4K | 144Hz | Curved',               basePrice: 179,  badge: 'Hot',  image: IMG.monitor1, category: 'Monitors' },
    { id: 8,  brand: 'LG',        name: 'UltraWide 34"',         shortSpec: '34" | 2K | 100Hz | IPS',                  basePrice: 229,  badge: null,   image: IMG.monitor2, category: 'Monitors' },
    { id: 27, brand: 'Gigabyte',  name: 'Gigabyte GS27QXA',      shortSpec: '27" QHD | 240Hz | IPS | DisplayHDR',      basePrice: 199,  badge: 'Hot',  image: IMG.monitor3, category: 'Monitors' },
    { id: 28, brand: 'Dahua',     name: 'Dahua LM27 E241b',      shortSpec: '27" IPS | Full HD | 240Hz | 0.5ms',       basePrice: 129,  badge: null,   image: IMG.monitor1, category: 'Monitors' },
    { id: 29, brand: 'Dahua',     name: 'Dahua LM27-E240A',      shortSpec: '27" VA Display | 165Hz | Curved',          basePrice: 149,  badge: 'Sale', image: IMG.monitor2, category: 'Monitors' },
    { id: 30, brand: 'RedragoN',  name: 'RedragoN GMW2581SE',    shortSpec: '24.5" | 240Hz | Fast IPS | 1080p',        basePrice: 129,  badge: null,   image: IMG.monitor3, category: 'Monitors' }
  ],
  Printers: [
    { id: 9,  brand: 'HP',     name: 'LaserJet Pro M404n',       shortSpec: 'Laser | Mono | 38ppm',                    basePrice: 125,  badge: null,   image: IMG.printer1, category: 'Printers' },
    { id: 10, brand: 'Canon',  name: 'PIXMA G3420',              shortSpec: 'Inkjet | Color | WiFi',                   basePrice: 89,   badge: 'Sale', image: IMG.printer2, category: 'Printers' },
    { id: 31, brand: 'Xerox',  name: 'Xerox 3025 Laser Printer', shortSpec: '3 IN 1 Wireless Laser Printer',           basePrice: 127,  badge: null,   image: IMG.printer1, category: 'Printers' },
    { id: 32, brand: 'Xerox',  name: 'Xerox Black Phaser 3020W', shortSpec: 'Laser Black | 20ppm | USB/WiFi',          basePrice: 89,   badge: null,   image: IMG.printer1, category: 'Printers' },
    { id: 33, brand: 'Canon',  name: 'Canon i-SENSYS MF651Cw',   shortSpec: 'Wireless Colour 3-in-1 Laser',            basePrice: 227,  badge: 'Hot',  image: IMG.printer2, category: 'Printers' },
    { id: 34, brand: 'HP',     name: 'HP Laser MFP 137fnw',      shortSpec: 'All-In-One Laser Printer | WiFi',         basePrice: 169,  badge: null,   image: IMG.printer1, category: 'Printers' },
    { id: 35, brand: 'Brother',name: 'Brother Printer HL-1110',  shortSpec: 'Mono Laser Printer | USB',                basePrice: 79,   badge: 'Sale', image: IMG.printer2, category: 'Printers' }
  ],
  Accessories: [
    { id: 11, brand: 'Sony',       name: 'WH-1000XM5',           shortSpec: 'Noise Cancelling | 30hr Battery',         basePrice: 49,   badge: 'Hot',  image: IMG.headset2, category: 'Accessories' },
    { id: 12, brand: 'Logitech',   name: 'MX Master 3',          shortSpec: 'Wireless | 7 Buttons | Ergonomic',        basePrice: 35,   badge: null,   image: IMG.mouse2,   category: 'Accessories' },
    { id: 36, brand: 'HyperX',    name: 'HyperX Cloud II',       shortSpec: 'Gaming Headset 7.1 Virtual Surround',     basePrice: 49,   badge: 'Hot',  image: IMG.headset1, category: 'Accessories' },
    { id: 37, brand: 'PXN',       name: 'PXN U306 Wired Headset',shortSpec: 'PC Gaming | USB | 7.1 Surround',          basePrice: 15,   badge: null,   image: IMG.headset2, category: 'Accessories' },
    { id: 38, brand: 'North Bayou',name: 'NB F80 Monitor Mount',  shortSpec: 'Full Motion | 17"-32" | VESA',            basePrice: 19,   badge: null,   image: IMG.accessory1,category:'Accessories' },
    { id: 39, brand: 'Sunlux',    name: 'Sunlux XL-3610 Scanner',shortSpec: 'USB Wired | 2D Omnidirectional',          basePrice: 39,   badge: null,   image: IMG.accessory1,category:'Accessories' }
  ],
  Tablet: [
    { id: 13, brand: 'Apple',   name: 'iPad Pro 12.9"',          shortSpec: 'M2 Chip | 256GB | WiFi',                  basePrice: 349,  badge: 'New',  image: IMG.tablet1,  category: 'Tablet' },
    { id: 14, brand: 'Samsung', name: 'Galaxy Tab S8',           shortSpec: 'Snapdragon | 128GB | AMOLED',             basePrice: 249,  badge: null,   image: IMG.tablet2,  category: 'Tablet' }
  ],
  'Gaming Mouse': [
    { id: 15, brand: 'Logitech',name: 'G Pro X Superlight',      shortSpec: '25K DPI | 63g | HERO Sensor',             basePrice: 45,   badge: 'Hot',  image: IMG.mouse1,   category: 'Gaming Mouse' },
    { id: 16, brand: 'Razer',   name: 'DeathAdder V3',           shortSpec: '30K DPI | 59g | Optical',                 basePrice: 39,   badge: null,   image: IMG.mouse2,   category: 'Gaming Mouse' },
    { id: 40, brand: 'Razer',   name: 'Razer Blackshark V2 X',   shortSpec: 'Multi-Platform Wired Gaming Headset',     basePrice: 19,   badge: 'Hot',  image: IMG.headset1, category: 'Gaming Mouse' }
  ],
  'Gaming PC': [
    { id: 17, brand: 'Custom',  name: 'Beast Gaming Rig',        shortSpec: 'i9-13900K | RTX 4090 | 64GB',             basePrice: 1299, badge: 'New',  image: IMG.gamingpc1,category: 'Gaming PC' },
    { id: 18, brand: 'ASUS',    name: 'ROG Strix Desktop',       shortSpec: 'i7-13700K | RTX 4070 | 32GB',             basePrice: 899,  badge: null,   image: IMG.gamingpc2,category: 'Gaming PC' }
  ],
  Toners: [
    { id: 41, brand: 'Canon',     name: 'Canon Ink CL-446 Color',shortSpec: 'Original | PIXMA Series',                 basePrice: 13,   badge: null,   image: IMG.toner1,   category: 'Toners' },
    { id: 42, brand: 'Canon',     name: 'Canon Ink PG-445 Black', shortSpec: 'Original | PIXMA Series',                basePrice: 12,   badge: null,   image: IMG.toner1,   category: 'Toners' },
    { id: 43, brand: 'HP',        name: 'HP Toner 103A Original', shortSpec: 'Black | LaserJet Series',                basePrice: 15,   badge: null,   image: IMG.toner1,   category: 'Toners' },
    { id: 44, brand: 'Pantum',    name: 'Refil De Toner RK-219',  shortSpec: 'PD-219 | BK-Pantum Compatible',          basePrice: 10,   badge: 'Sale', image: IMG.toner1,   category: 'Toners' },
    { id: 45, brand: 'Compatible',name: 'Compatible CE505A/CF280A',shortSpec: 'CRG-719A Black Toner Cartridge',        basePrice: 10,   badge: null,   image: IMG.toner1,   category: 'Toners' },
    { id: 46, brand: 'Compatible',name: '12A Compatible Toner',   shortSpec: 'For HP LaserJet Series',                 basePrice: 8,    badge: null,   image: IMG.toner1,   category: 'Toners' }
  ]
}

export const allProductsFlat = Object.values(allProducts).flat()

export const featuredProducts = [
  allProducts.Printers[0],
  allProducts.Laptop[4],
  allProducts.Monitors[0],
  allProducts.Accessories[2],
  allProducts.Accessories[3],
  allProducts.Laptop[5]
]

export const bestSellingProducts = [
  allProducts.Accessories[4],
  allProducts['Gaming Mouse'][2],
  allProducts.Laptop[6],
  allProducts.Printers[0],
  allProducts.Accessories[5],
  allProducts.Monitors[3]
]

export const productDB = {

  // ── 1: ASUS ROG Strix SCAR 16 ──────────────────────────────────────────────
  1: {
    ...allProducts.Laptop[0],
    gallery: [IMG.laptop2, IMG.laptop1, IMG.laptop3],
    originalPrice: 650,
    discount: 9,
    colors: [
      {
        name: 'Midnight Black', hex: '#1a1a1a',
        images: [IMG.laptop2, IMG.laptop1],
        variants: [
          { label: '16GB RAM / 512GB SSD', price: 589, stock: 7 },
          { label: '32GB RAM / 1TB SSD',   price: 689, stock: 4 },
        ]
      },
      {
        name: 'Eclipse Gray', hex: '#546e7a',
        images: [IMG.laptop1, IMG.laptop3],
        variants: [
          { label: '32GB RAM / 1TB SSD',   price: 699, stock: 3 },
          { label: '32GB RAM / 2TB SSD',   price: 849, stock: 1 },
        ]
      },
      {
        name: 'Stealth White', hex: '#eceff1',
        images: [IMG.laptop3, IMG.laptop2],
        variants: [
          { label: '16GB RAM / 512GB SSD', price: 609, stock: 0 },
          { label: '32GB RAM / 1TB SSD',   price: 709, stock: 2 },
        ]
      }
    ],
    specs: {
      Processor:  'Intel Core i9-13980HX',
      Graphics:   'NVIDIA RTX 4090 16GB',
      Display:    '16" QHD+ 240Hz',
      RAM:        'Up to 32GB DDR5',
      Storage:    'Up to 2TB NVMe SSD',
      Battery:    '90Wh',
      OS:         'Windows 11 Pro',
      Weight:     '2.3 kg'
    },
    description: 'The ROG Strix SCAR 16 is built for esports professionals who demand the absolute best. Available in three finishes, each with memory and storage configurations tailored to your workload.'
  },

  // ── 2: Lenovo ThinkPad X1 Carbon ───────────────────────────────────────────
  2: {
    ...allProducts.Laptop[1],
    gallery: [IMG.laptop3, IMG.laptop1],
    originalPrice: 520,
    discount: 12,
    colors: [
      {
        name: 'Carbon Black', hex: '#212121',
        images: [IMG.laptop3, IMG.laptop4],
        variants: [
          { label: '16GB / 512GB SSD', price: 459, stock: 10 },
          { label: '16GB / 1TB SSD',   price: 509, stock: 6  },
          { label: '32GB / 1TB SSD',   price: 599, stock: 3  },
        ]
      },
      {
        name: 'Deep Silver', hex: '#9e9e9e',
        images: [IMG.laptop1, IMG.laptop3],
        variants: [
          { label: '16GB / 512GB SSD', price: 479, stock: 5 },
          { label: '32GB / 1TB SSD',   price: 619, stock: 2 },
        ]
      }
    ],
    specs: {
      Processor:    'Intel Core i7-1365U',
      Graphics:     'Intel Iris Xe',
      Display:      '14" 2.8K OLED',
      RAM:          'Up to 32GB LPDDR5',
      Storage:      'Up to 1TB SSD',
      Battery:      '57Wh',
      OS:           'Windows 11 Pro',
      Weight:       '1.12 kg'
    },
    description: 'The ThinkPad X1 Carbon Gen 11 — ultra-lightweight at 1.12 kg with military-grade durability. Carbon Black offers three storage tiers; Deep Silver is available in two.'
  },

  // ── 7: Samsung Odyssey G7 ──────────────────────────────────────────────────
  7: {
    ...allProducts.Monitors[0],
    gallery: [IMG.monitor1, IMG.monitor2],
    originalPrice: 210,
    discount: 15,
    colors: [
      {
        name: 'Matte Black', hex: '#212121',
        images: [IMG.monitor1, IMG.monitor3],
        variants: [
          { label: '27" / QHD 144Hz',  price: 179, stock: 9 },
          { label: '32" / 4K 144Hz',   price: 249, stock: 4 },
        ]
      },
      {
        name: 'Frost White', hex: '#f5f5f5',
        images: [IMG.monitor2, IMG.monitor1],
        variants: [
          { label: '27" / QHD 144Hz',  price: 199, stock: 5 },
          { label: '32" / 4K 144Hz',   price: 269, stock: 0 },
        ]
      }
    ],
    specs: {
      Panel:          'VA Curved 1000R',
      Resolution:     '2560×1440 QHD / 3840×2160 4K',
      'Refresh Rate': '144Hz',
      'Response Time':'1ms',
      HDR:            'HDR600',
      Ports:          '2× HDMI, 1× DP, 2× USB'
    },
    description: 'The Samsung Odyssey G7 delivers an immersive curved gaming experience. Matte Black ships in both 27" QHD and 32" 4K; Frost White in 27" only (32" 4K sold out).'
  },

  // ── 11: Sony WH-1000XM5 ────────────────────────────────────────────────────
  11: {
    ...allProducts.Accessories[0],
    gallery: [IMG.headset2, IMG.headset1],
    originalPrice: 60,
    discount: 18,
    colors: [
      {
        name: 'Black', hex: '#1a1a1a',
        images: [IMG.headset2, IMG.headset1],
        variants: [
          { label: 'Headphones Only',       price: 49, stock: 15 },
          { label: 'With Premium Carry Case', price: 59, stock: 8  },
        ]
      },
      {
        name: 'Platinum Silver', hex: '#bdbdbd',
        images: [IMG.headset1, IMG.headset2],
        variants: [
          { label: 'Headphones Only',       price: 54, stock: 10 },
          { label: 'With Premium Carry Case', price: 64, stock: 3  },
        ]
      },
      {
        name: 'Midnight Blue', hex: '#1a237e',
        images: [IMG.headset2, IMG.headset1],
        variants: [
          { label: 'Headphones Only',       price: 54, stock: 0 },
          { label: 'With Premium Carry Case', price: 64, stock: 2 },
        ]
      }
    ],
    specs: {
      Type:          'Over-ear Wireless',
      ANC:           'Industry-leading (8 mics + 2 processors)',
      Battery:       '30 hours',
      Charging:      'USB-C · 3 min = 3 hrs',
      Connectivity:  'Bluetooth 5.2',
      Weight:        '250 g'
    },
    description: 'The WH-1000XM5 redefines noise cancellation. Available in Black, Platinum Silver, and Midnight Blue — each as headphones only or bundled with a premium carry case.'
  }
}