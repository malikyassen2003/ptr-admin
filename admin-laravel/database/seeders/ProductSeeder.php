<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use App\Models\ProductSpec;

class ProductSeeder extends Seeder
{
    // ── Shared image URLs (mirrors your ProductDB.js IMG object) ──────────────
    private function imgs(): array
    {
        $Q = '?w=600&auto=format&fit=crop&q=80&ixlib=rb-4.1.0';
        return [
            'laptop1'   => "https://images.unsplash.com/photo-1603302576837-37561b2e2302{$Q}",
            'laptop2'   => "https://images.unsplash.com/photo-1593642632823-8f785ba67e45{$Q}",
            'laptop3'   => "https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2{$Q}",
            'laptop4'   => "https://images.unsplash.com/photo-1541807084-5c52b6b3adef{$Q}",
            'laptop5'   => "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed{$Q}",
            'desktop1'  => "https://images.unsplash.com/photo-1618339220157-daa2cd9ade56{$Q}",
            'desktop2'  => "https://images.unsplash.com/photo-1587302912306-cf1ed9c33146{$Q}",
            'desktop3'  => "https://images.unsplash.com/photo-1660855551740-4474188debdb{$Q}",
            'monitor1'  => "https://images.unsplash.com/photo-1666771410003-8437c4781d49{$Q}",
            'monitor2'  => "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf{$Q}",
            'monitor3'  => "https://images.unsplash.com/photo-1591370874773-6702e4b4f8e4{$Q}",
            'printer1'  => "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6{$Q}",
            'printer2'  => "https://images.unsplash.com/photo-1563689985107-34e3b89d5b4c{$Q}",
            'accessory1'=> "https://images.unsplash.com/photo-1600861195091-690c92f1d2cc{$Q}",
            'headset1'  => "https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb{$Q}",
            'headset2'  => "https://images.unsplash.com/photo-1505740420928-5e560c06d30e{$Q}",
            'mouse1'    => "https://images.unsplash.com/photo-1631749352438-7d576312185d{$Q}",
            'mouse2'    => "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46{$Q}",
            'gamingpc1' => "https://images.unsplash.com/photo-1587202372775-e229f172b9d7{$Q}",
            'gamingpc2' => "https://images.unsplash.com/photo-1719927604476-dc404b85358f{$Q}",
            'tablet1'   => "https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0{$Q}",
            'tablet2'   => "https://images.unsplash.com/photo-1632634571518-ece4b8e2b2f7{$Q}",
            'toner1'    => "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6{$Q}",
        ];
    }

    public function run(): void
    {
        $I = $this->imgs();

        // ── Helper: create colors + variants for a product ────────────────────
        $addColors = function (Product $product, array $colors) {
            foreach ($colors as $colorData) {
                $color = ProductColor::create([
                    'product_id' => $product->id,
                    'name'       => $colorData['name'],
                    'hex'        => $colorData['hex'],
                ]);
                foreach ($colorData['variants'] as $v) {
                    ProductVariant::create([
                        'product_color_id' => $color->id,
                        'label'            => $v['label'],
                        'price'            => $v['price'],
                        'stock'            => $v['stock'],
                    ]);
                }
                // Gallery images for this color
                if (!empty($colorData['images'])) {
                    foreach ($colorData['images'] as $idx => $url) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'url'        => $url,
                            'sort_order' => $idx,
                        ]);
                    }
                }
            }
        };

        // ── Helper: create specs ──────────────────────────────────────────────
        $addSpecs = function (Product $product, array $specs) {
            $i = 0;
            foreach ($specs as $key => $value) {
                ProductSpec::create([
                    'product_id' => $product->id,
                    'key'        => $key,
                    'value'      => $value,
                    'sort_order' => $i++,
                ]);
            }
        };

        // ═════════════════════════════════════════════════════════════════════
        // LAPTOPS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'laptop')->first();

        // ID 1 — ASUS ROG Strix SCAR 16 (full detail)
        $p = Product::create([
            'category_id'    => $cat->id,
            'brand'          => 'ASUS',
            'name'           => 'ROG Strix SCAR 16',
            'short_spec'     => 'Intel i9 | RTX 4090 | 32GB RAM',
            'base_price'     => 589,
            'original_price' => 650,
            'discount'       => 9,
            'badge'          => 'Hot',
            'image'          => $I['laptop2'],
            'is_featured'    => false,
            'is_best_selling'=> false,
            'has_variants'   => true,
            'description'    => 'The ROG Strix SCAR 16 is built for esports professionals who demand the absolute best. Available in three finishes, each with memory and storage configurations tailored to your workload.',
        ]);
        $addColors($p, [
            ['name' => 'Midnight Black', 'hex' => '#1a1a1a', 'images' => [$I['laptop2'], $I['laptop1']],
             'variants' => [['label' => '16GB RAM / 512GB SSD', 'price' => 589, 'stock' => 7],
                            ['label' => '32GB RAM / 1TB SSD',   'price' => 689, 'stock' => 4]]],
            ['name' => 'Eclipse Gray',   'hex' => '#546e7a', 'images' => [$I['laptop1'], $I['laptop3']],
             'variants' => [['label' => '32GB RAM / 1TB SSD',   'price' => 699, 'stock' => 3],
                            ['label' => '32GB RAM / 2TB SSD',   'price' => 849, 'stock' => 1]]],
            ['name' => 'Stealth White',  'hex' => '#eceff1', 'images' => [$I['laptop3'], $I['laptop2']],
             'variants' => [['label' => '16GB RAM / 512GB SSD', 'price' => 609, 'stock' => 0],
                            ['label' => '32GB RAM / 1TB SSD',   'price' => 709, 'stock' => 2]]],
        ]);
        $addSpecs($p, ['Processor' => 'Intel Core i9-13980HX', 'Graphics' => 'NVIDIA RTX 4090 16GB',
            'Display' => '16" QHD+ 240Hz', 'RAM' => 'Up to 32GB DDR5', 'Storage' => 'Up to 2TB NVMe SSD',
            'Battery' => '90Wh', 'OS' => 'Windows 11 Pro', 'Weight' => '2.3 kg']);

        // ID 2 — Lenovo ThinkPad X1 Carbon (full detail)
        $p = Product::create([
            'category_id'    => $cat->id,
            'brand'          => 'Lenovo',
            'name'           => 'ThinkPad X1 Carbon',
            'short_spec'     => 'Intel i7 | 16GB RAM | 512GB SSD',
            'base_price'     => 459,
            'original_price' => 520,
            'discount'       => 12,
            'badge'          => null,
            'image'          => $I['laptop3'],
            'has_variants'   => true,
            'description'    => 'The ThinkPad X1 Carbon Gen 11 — ultra-lightweight at 1.12 kg with military-grade durability.',
        ]);
        $addColors($p, [
            ['name' => 'Carbon Black', 'hex' => '#212121', 'images' => [$I['laptop3'], $I['laptop4']],
             'variants' => [['label' => '16GB / 512GB SSD', 'price' => 459, 'stock' => 10],
                            ['label' => '16GB / 1TB SSD',   'price' => 509, 'stock' => 6],
                            ['label' => '32GB / 1TB SSD',   'price' => 599, 'stock' => 3]]],
            ['name' => 'Deep Silver',  'hex' => '#9e9e9e', 'images' => [$I['laptop1'], $I['laptop3']],
             'variants' => [['label' => '16GB / 512GB SSD', 'price' => 479, 'stock' => 5],
                            ['label' => '32GB / 1TB SSD',   'price' => 619, 'stock' => 2]]],
        ]);
        $addSpecs($p, ['Processor' => 'Intel Core i7-1365U', 'Graphics' => 'Intel Iris Xe',
            'Display' => '14" 2.8K OLED', 'RAM' => 'Up to 32GB LPDDR5', 'Storage' => 'Up to 1TB SSD',
            'Battery' => '57Wh', 'OS' => 'Windows 11 Pro', 'Weight' => '1.12 kg']);

        // Simple laptops
        foreach ([
            ['brand'=>'HP',    'name'=>'Spectre x360',        'short_spec'=>'Intel i7 | 16GB RAM | 1TB SSD',       'base_price'=>399, 'badge'=>'Sale','image'=>$I['laptop1']],
            ['brand'=>'Dell',  'name'=>'XPS 15',              'short_spec'=>'Intel i9 | 32GB RAM | RTX 3050',       'base_price'=>520, 'badge'=>null,  'image'=>$I['laptop4']],
            ['brand'=>'HP',    'name'=>'HP Victus 15',        'short_spec'=>'Intel i5-13420H | RTX 3050 | 16GB',    'base_price'=>629, 'badge'=>'Hot', 'image'=>$I['laptop5'], 'is_featured'=>true],
            ['brand'=>'Acer',  'name'=>'Acer Nitro ANV15',    'short_spec'=>'Intel i5 13th Gen | RTX 4050 | 16GB',  'base_price'=>589, 'badge'=>'New', 'image'=>$I['laptop2'], 'is_featured'=>true],
            ['brand'=>'ASUS',  'name'=>'ASUS Vivobook Go 15', 'short_spec'=>'AMD Ryzen 5 | 8GB | 512GB SSD',        'base_price'=>359, 'badge'=>null,  'image'=>$I['laptop3'], 'is_best_selling'=>true],
            ['brand'=>'ASUS',  'name'=>'ASUS TUF Gaming F16', 'short_spec'=>'Intel i7-13620H | RTX 4060 | 16GB',   'base_price'=>819, 'badge'=>'Hot', 'image'=>$I['laptop2']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // DESKTOPS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'desktop')->first();
        foreach ([
            ['brand'=>'HP',    'name'=>'Pavilion Desktop',         'short_spec'=>'Intel i5 | 8GB RAM | 512GB SSD',          'base_price'=>299, 'badge'=>null,  'image'=>$I['desktop1']],
            ['brand'=>'Dell',  'name'=>'OptiPlex 7090',            'short_spec'=>'Intel i7 | 16GB RAM | 1TB HDD',            'base_price'=>389, 'badge'=>'New', 'image'=>$I['desktop2']],
            ['brand'=>'Custom','name'=>'Gaming PC Core i7-13700F', 'short_spec'=>'i7-13700F | RTX 4070 | 32GB | 1TB NVMe',  'base_price'=>977, 'badge'=>'New', 'image'=>$I['gamingpc1']],
            ['brand'=>'Custom','name'=>'Gaming PC Core i5-12400F', 'short_spec'=>'i5-12400F | RTX 3060 | 16GB | 500GB',     'base_price'=>537, 'badge'=>null,  'image'=>$I['gamingpc2']],
            ['brand'=>'Custom','name'=>'PC Gaming PC5',            'short_spec'=>'i7-13700F | 500GB NVMe | 16GB',            'base_price'=>717, 'badge'=>null,  'image'=>$I['desktop3']],
            ['brand'=>'Custom','name'=>'PC Gaming PC4',            'short_spec'=>'i5-12400F | 500GB NVMe | 16GB',            'base_price'=>617, 'badge'=>'Sale','image'=>$I['desktop2']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // MONITORS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'monitors')->first();

        // ID 7 — Samsung Odyssey G7 (full detail)
        $p = Product::create([
            'category_id'    => $cat->id,
            'brand'          => 'Samsung',
            'name'           => 'Odyssey G7',
            'short_spec'     => '27" | 4K | 144Hz | Curved',
            'base_price'     => 179,
            'original_price' => 210,
            'discount'       => 15,
            'badge'          => 'Hot',
            'image'          => $I['monitor1'],
            'has_variants'   => true,
            'description'    => 'The Samsung Odyssey G7 delivers an immersive curved gaming experience.',
        ]);
        $addColors($p, [
            ['name' => 'Matte Black', 'hex' => '#212121', 'images' => [$I['monitor1'], $I['monitor3']],
             'variants' => [['label' => '27" / QHD 144Hz', 'price' => 179, 'stock' => 9],
                            ['label' => '32" / 4K 144Hz',  'price' => 249, 'stock' => 4]]],
            ['name' => 'Frost White', 'hex' => '#f5f5f5', 'images' => [$I['monitor2'], $I['monitor1']],
             'variants' => [['label' => '27" / QHD 144Hz', 'price' => 199, 'stock' => 5],
                            ['label' => '32" / 4K 144Hz',  'price' => 269, 'stock' => 0]]],
        ]);
        $addSpecs($p, ['Panel' => 'VA Curved 1000R', 'Resolution' => '2560×1440 QHD / 3840×2160 4K',
            'Refresh Rate' => '144Hz', 'Response Time' => '1ms', 'HDR' => 'HDR600',
            'Ports' => '2× HDMI, 1× DP, 2× USB']);

        foreach ([
            ['brand'=>'LG',       'name'=>'UltraWide 34"',       'short_spec'=>'34" | 2K | 100Hz | IPS',           'base_price'=>229,'badge'=>null,  'image'=>$I['monitor2'], 'is_best_selling'=>true],
            ['brand'=>'Gigabyte', 'name'=>'Gigabyte GS27QXA',    'short_spec'=>'27" QHD | 240Hz | IPS | DisplayHDR','base_price'=>199,'badge'=>'Hot', 'image'=>$I['monitor3']],
            ['brand'=>'Dahua',    'name'=>'Dahua LM27 E241b',    'short_spec'=>'27" IPS | Full HD | 240Hz | 0.5ms', 'base_price'=>129,'badge'=>null,  'image'=>$I['monitor1'], 'is_best_selling'=>true],
            ['brand'=>'Dahua',    'name'=>'Dahua LM27-E240A',    'short_spec'=>'27" VA Display | 165Hz | Curved',   'base_price'=>149,'badge'=>'Sale','image'=>$I['monitor2']],
            ['brand'=>'RedragoN', 'name'=>'RedragoN GMW2581SE',  'short_spec'=>'24.5" | 240Hz | Fast IPS | 1080p', 'base_price'=>129,'badge'=>null,  'image'=>$I['monitor3']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // PRINTERS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'printers')->first();
        foreach ([
            ['brand'=>'HP',     'name'=>'LaserJet Pro M404n',      'short_spec'=>'Laser | Mono | 38ppm',             'base_price'=>125,'badge'=>null,  'image'=>$I['printer1'],'is_featured'=>true,'is_best_selling'=>true],
            ['brand'=>'Canon',  'name'=>'PIXMA G3420',             'short_spec'=>'Inkjet | Color | WiFi',            'base_price'=>89, 'badge'=>'Sale','image'=>$I['printer2']],
            ['brand'=>'Xerox',  'name'=>'Xerox 3025 Laser Printer','short_spec'=>'3 IN 1 Wireless Laser Printer',    'base_price'=>127,'badge'=>null,  'image'=>$I['printer1']],
            ['brand'=>'Xerox',  'name'=>'Xerox Black Phaser 3020W','short_spec'=>'Laser Black | 20ppm | USB/WiFi',   'base_price'=>89, 'badge'=>null,  'image'=>$I['printer1']],
            ['brand'=>'Canon',  'name'=>'Canon i-SENSYS MF651Cw',  'short_spec'=>'Wireless Colour 3-in-1 Laser',     'base_price'=>227,'badge'=>'Hot', 'image'=>$I['printer2']],
            ['brand'=>'HP',     'name'=>'HP Laser MFP 137fnw',     'short_spec'=>'All-In-One Laser Printer | WiFi',  'base_price'=>169,'badge'=>null,  'image'=>$I['printer1']],
            ['brand'=>'Brother','name'=>'Brother Printer HL-1110', 'short_spec'=>'Mono Laser Printer | USB',         'base_price'=>79, 'badge'=>'Sale','image'=>$I['printer2']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // ACCESSORIES
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'accessories')->first();

        // ID 11 — Sony WH-1000XM5 (full detail)
        $p = Product::create([
            'category_id'    => $cat->id,
            'brand'          => 'Sony',
            'name'           => 'WH-1000XM5',
            'short_spec'     => 'Noise Cancelling | 30hr Battery',
            'base_price'     => 49,
            'original_price' => 60,
            'discount'       => 18,
            'badge'          => 'Hot',
            'image'          => $I['headset2'],
            'is_featured'    => true,
            'has_variants'   => true,
            'description'    => 'The WH-1000XM5 redefines noise cancellation. Available in Black, Platinum Silver, and Midnight Blue.',
        ]);
        $addColors($p, [
            ['name' => 'Black',           'hex' => '#1a1a1a', 'images' => [$I['headset2'], $I['headset1']],
             'variants' => [['label' => 'Headphones Only',         'price' => 49, 'stock' => 15],
                            ['label' => 'With Premium Carry Case', 'price' => 59, 'stock' => 8]]],
            ['name' => 'Platinum Silver', 'hex' => '#bdbdbd', 'images' => [$I['headset1'], $I['headset2']],
             'variants' => [['label' => 'Headphones Only',         'price' => 54, 'stock' => 10],
                            ['label' => 'With Premium Carry Case', 'price' => 64, 'stock' => 3]]],
            ['name' => 'Midnight Blue',   'hex' => '#1a237e', 'images' => [$I['headset2'], $I['headset1']],
             'variants' => [['label' => 'Headphones Only',         'price' => 54, 'stock' => 0],
                            ['label' => 'With Premium Carry Case', 'price' => 64, 'stock' => 2]]],
        ]);
        $addSpecs($p, ['Type' => 'Over-ear Wireless', 'ANC' => 'Industry-leading (8 mics + 2 processors)',
            'Battery' => '30 hours', 'Charging' => 'USB-C · 3 min = 3 hrs',
            'Connectivity' => 'Bluetooth 5.2', 'Weight' => '250 g']);

        foreach ([
            ['brand'=>'Logitech',   'name'=>'MX Master 3',          'short_spec'=>'Wireless | 7 Buttons | Ergonomic',    'base_price'=>35,'badge'=>null, 'image'=>$I['mouse2']],
            ['brand'=>'HyperX',    'name'=>'HyperX Cloud II',       'short_spec'=>'Gaming Headset 7.1 Virtual Surround', 'base_price'=>49,'badge'=>'Hot','image'=>$I['headset1'],'is_featured'=>true],
            ['brand'=>'PXN',       'name'=>'PXN U306 Wired Headset','short_spec'=>'PC Gaming | USB | 7.1 Surround',      'base_price'=>15,'badge'=>null, 'image'=>$I['headset2'],'is_featured'=>true],
            ['brand'=>'North Bayou','name'=>'NB F80 Monitor Mount',  'short_spec'=>'Full Motion | 17"-32" | VESA',        'base_price'=>19,'badge'=>null, 'image'=>$I['accessory1'],'is_best_selling'=>true],
            ['brand'=>'Sunlux',    'name'=>'Sunlux XL-3610 Scanner','short_spec'=>'USB Wired | 2D Omnidirectional',      'base_price'=>39,'badge'=>null, 'image'=>$I['accessory1'],'is_best_selling'=>true],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // TABLETS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'tablet')->first();
        foreach ([
            ['brand'=>'Apple',  'name'=>'iPad Pro 12.9"',  'short_spec'=>'M2 Chip | 256GB | WiFi',       'base_price'=>349,'badge'=>'New','image'=>$I['tablet1']],
            ['brand'=>'Samsung','name'=>'Galaxy Tab S8',   'short_spec'=>'Snapdragon | 128GB | AMOLED',  'base_price'=>249,'badge'=>null, 'image'=>$I['tablet2']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // GAMING MOUSE
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'gaming-mouse')->first();
        foreach ([
            ['brand'=>'Logitech','name'=>'G Pro X Superlight',   'short_spec'=>'25K DPI | 63g | HERO Sensor',           'base_price'=>45,'badge'=>'Hot', 'image'=>$I['mouse1']],
            ['brand'=>'Razer',  'name'=>'DeathAdder V3',         'short_spec'=>'30K DPI | 59g | Optical',               'base_price'=>39,'badge'=>null,  'image'=>$I['mouse2']],
            ['brand'=>'Razer',  'name'=>'Razer Blackshark V2 X', 'short_spec'=>'Multi-Platform Wired Gaming Headset',   'base_price'=>19,'badge'=>'Hot', 'image'=>$I['headset1'],'is_best_selling'=>true],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // GAMING PC
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'gaming-pc')->first();
        foreach ([
            ['brand'=>'Custom','name'=>'Beast Gaming Rig',  'short_spec'=>'i9-13900K | RTX 4090 | 64GB', 'base_price'=>1299,'badge'=>'New','image'=>$I['gamingpc1']],
            ['brand'=>'ASUS', 'name'=>'ROG Strix Desktop',  'short_spec'=>'i7-13700K | RTX 4070 | 32GB', 'base_price'=>899, 'badge'=>null, 'image'=>$I['gamingpc2']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }

        // ═════════════════════════════════════════════════════════════════════
        // TONERS
        // ═════════════════════════════════════════════════════════════════════
        $cat = Category::where('slug', 'toners')->first();
        foreach ([
            ['brand'=>'Canon',      'name'=>'Canon Ink CL-446 Color',   'short_spec'=>'Original | PIXMA Series',         'base_price'=>13,'badge'=>null,  'image'=>$I['toner1']],
            ['brand'=>'Canon',      'name'=>'Canon Ink PG-445 Black',   'short_spec'=>'Original | PIXMA Series',         'base_price'=>12,'badge'=>null,  'image'=>$I['toner1']],
            ['brand'=>'HP',         'name'=>'HP Toner 103A Original',   'short_spec'=>'Black | LaserJet Series',         'base_price'=>15,'badge'=>null,  'image'=>$I['toner1']],
            ['brand'=>'Pantum',     'name'=>'Refil De Toner RK-219',    'short_spec'=>'PD-219 | BK-Pantum Compatible',   'base_price'=>10,'badge'=>'Sale','image'=>$I['toner1']],
            ['brand'=>'Compatible', 'name'=>'Compatible CE505A/CF280A', 'short_spec'=>'CRG-719A Black Toner Cartridge',  'base_price'=>10,'badge'=>null,  'image'=>$I['toner1']],
            ['brand'=>'Compatible', 'name'=>'12A Compatible Toner',     'short_spec'=>'For HP LaserJet Series',          'base_price'=>8, 'badge'=>null,  'image'=>$I['toner1']],
        ] as $data) {
            Product::create(array_merge(['category_id' => $cat->id, 'is_featured' => false,
                'is_best_selling' => false, 'has_variants' => false, 'stock' => 10], $data));
        }
    }
}