<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop',       'slug' => 'laptop',       'icon' => 'mdi-laptop'],
            ['name' => 'Desktop',      'slug' => 'desktop',      'icon' => 'mdi-desktop-tower'],
            ['name' => 'Monitors',     'slug' => 'monitors',     'icon' => 'mdi-monitor'],
            ['name' => 'Printers',     'slug' => 'printers',     'icon' => 'mdi-printer'],
            ['name' => 'Accessories',  'slug' => 'accessories',  'icon' => 'mdi-headphones'],
            ['name' => 'Tablet',       'slug' => 'tablet',       'icon' => 'mdi-tablet'],
            ['name' => 'Gaming Mouse', 'slug' => 'gaming-mouse', 'icon' => 'mdi-mouse'],
            ['name' => 'Gaming PC',    'slug' => 'gaming-pc',    'icon' => 'mdi-controller'],
            ['name' => 'Toners',       'slug' => 'toners',       'icon' => 'mdi-printer-settings'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}