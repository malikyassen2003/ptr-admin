// FILE: src/data/specTemplates.js
// Category slug → array of spec keys with labels and placeholder hints

const templates = {
  laptop: [
    { key: 'Processor',    placeholder: 'e.g. Intel Core i9-13980HX'         },
    { key: 'Graphics',     placeholder: 'e.g. NVIDIA RTX 4090 16GB'           },
    { key: 'Display',      placeholder: 'e.g. 16" QHD+ 240Hz'                 },
    { key: 'RAM',          placeholder: 'e.g. 32GB DDR5'                      },
    { key: 'Storage',      placeholder: 'e.g. 1TB NVMe SSD'                   },
    { key: 'Battery',      placeholder: 'e.g. 90Wh'                           },
    { key: 'OS',           placeholder: 'e.g. Windows 11 Pro'                 },
    { key: 'Weight',       placeholder: 'e.g. 2.3 kg'                         },
  ],
  desktop: [
    { key: 'Processor',    placeholder: 'e.g. Intel Core i7-13700F'           },
    { key: 'Graphics',     placeholder: 'e.g. NVIDIA RTX 4070'                },
    { key: 'RAM',          placeholder: 'e.g. 32GB DDR4'                      },
    { key: 'Storage',      placeholder: 'e.g. 1TB NVMe SSD'                   },
    { key: 'PSU',          placeholder: 'e.g. 750W 80+ Gold'                  },
    { key: 'OS',           placeholder: 'e.g. Windows 11 Home'                },
    { key: 'Form Factor',  placeholder: 'e.g. Mid Tower'                      },
  ],
  'gaming-pc': [
    { key: 'Processor',    placeholder: 'e.g. Intel Core i9-13900K'           },
    { key: 'Graphics',     placeholder: 'e.g. NVIDIA RTX 4090'                },
    { key: 'RAM',          placeholder: 'e.g. 64GB DDR5'                      },
    { key: 'Storage',      placeholder: 'e.g. 2TB NVMe SSD'                   },
    { key: 'Cooling',      placeholder: 'e.g. 360mm AIO Liquid Cooler'        },
    { key: 'PSU',          placeholder: 'e.g. 1000W 80+ Platinum'             },
    { key: 'Case',         placeholder: 'e.g. Lian Li O11 Dynamic'            },
  ],
  monitors: [
    { key: 'Panel',        placeholder: 'e.g. IPS / VA / OLED'                },
    { key: 'Resolution',   placeholder: 'e.g. 2560×1440 QHD'                  },
    { key: 'Refresh Rate', placeholder: 'e.g. 165Hz'                          },
    { key: 'Response Time',placeholder: 'e.g. 1ms GTG'                       },
    { key: 'HDR',          placeholder: 'e.g. HDR400'                         },
    { key: 'Ports',        placeholder: 'e.g. 2× HDMI 2.1, 1× DP 1.4'        },
    { key: 'Size',         placeholder: 'e.g. 27 inch'                        },
  ],
  printers: [
    { key: 'Technology',   placeholder: 'e.g. Laser / Inkjet'                 },
    { key: 'Print Speed',  placeholder: 'e.g. 38 ppm'                         },
    { key: 'Connectivity', placeholder: 'e.g. USB, WiFi, Ethernet'            },
    { key: 'Paper Size',   placeholder: 'e.g. A4, A3'                         },
    { key: 'Functions',    placeholder: 'e.g. Print, Scan, Copy, Fax'         },
    { key: 'Resolution',   placeholder: 'e.g. 1200 × 1200 dpi'               },
  ],
  accessories: [
    { key: 'Type',         placeholder: 'e.g. Over-ear / In-ear / Headset'   },
    { key: 'Connectivity', placeholder: 'e.g. Bluetooth 5.2 / USB / 3.5mm'   },
    { key: 'Battery',      placeholder: 'e.g. 30 hours'                       },
    { key: 'Weight',       placeholder: 'e.g. 250g'                           },
    { key: 'Compatibility',placeholder: 'e.g. Windows, macOS, Android, iOS'  },
    { key: 'Features',     placeholder: 'e.g. Active Noise Cancellation'      },
  ],
  tablet: [
    { key: 'Processor',    placeholder: 'e.g. Apple M2 / Snapdragon 8 Gen 2' },
    { key: 'Display',      placeholder: 'e.g. 12.9" Liquid Retina XDR'       },
    { key: 'RAM',          placeholder: 'e.g. 8GB'                            },
    { key: 'Storage',      placeholder: 'e.g. 256GB'                          },
    { key: 'Battery',      placeholder: 'e.g. 10,758 mAh'                    },
    { key: 'OS',           placeholder: 'e.g. iPadOS 16 / Android 13'        },
    { key: 'Connectivity', placeholder: 'e.g. WiFi 6E, Bluetooth 5.3'        },
    { key: 'Camera',       placeholder: 'e.g. 12MP rear, 12MP front'         },
  ],
  'gaming-mouse': [
    { key: 'DPI',          placeholder: 'e.g. 100 – 25,600 DPI'              },
    { key: 'Sensor',       placeholder: 'e.g. HERO 25K Optical'              },
    { key: 'Buttons',      placeholder: 'e.g. 7 programmable buttons'        },
    { key: 'Weight',       placeholder: 'e.g. 63g'                           },
    { key: 'Connectivity', placeholder: 'e.g. Wireless 2.4GHz / USB-C'      },
    { key: 'Battery',      placeholder: 'e.g. 70 hours'                      },
    { key: 'Polling Rate', placeholder: 'e.g. 1000Hz'                        },
  ],
  toners: [
    { key: 'Compatible',   placeholder: 'e.g. HP LaserJet Pro M404n'         },
    { key: 'Color',        placeholder: 'e.g. Black / Cyan / Magenta / Yellow'},
    { key: 'Page Yield',   placeholder: 'e.g. 3,000 pages at 5% coverage'   },
    { key: 'Type',         placeholder: 'e.g. Original / Compatible'         },
    { key: 'OEM Number',   placeholder: 'e.g. CF258A / 58A'                  },
  ],
}

// Fallback: generic specs for unknown categories
const fallback = [
  { key: 'Specification 1', placeholder: 'Enter value' },
  { key: 'Specification 2', placeholder: 'Enter value' },
  { key: 'Specification 3', placeholder: 'Enter value' },
]

/**
 * Get spec template by category slug
 * Returns array of { key, placeholder }
 */
export function getSpecTemplate(categorySlug) {
  if (!categorySlug) return []
  const slug = categorySlug.toLowerCase().replace(/\s+/g, '-')
  return templates[slug] ?? fallback
}

export default templates