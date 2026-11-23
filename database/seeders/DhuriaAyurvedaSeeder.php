<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DhuriaAyurvedaSeeder extends Seeder
{
    public function run()
    {
        // 1. Categories
        $categories = [
            'madhucurex' => [
                'name' => 'MadhuCurex',
                'slug' => 'madhucurex',
                'image' => 'images/card_diabetes_pro_hd.jpg',
                'is_active' => 1
            ],
            'liver-detox' => [
                'name' => 'Liver Detox',
                'slug' => 'liver-detox',
                'image' => 'images/card_liver_detox_hd.jpg',
                'is_active' => 1
            ],
            'pilecurex' => [
                'name' => 'Pilecurex Kit',
                'slug' => 'pilecurex',
                'image' => 'images/card_pilecurex_hd.jpg',
                'is_active' => 1
            ],
            'herbal-powders' => [
                'name' => 'Herbal Powders & Churna',
                'slug' => 'herbal-powders',
                'image' => 'images/card_triphala_hd.jpg',
                'is_active' => 1
            ],
            'daily-wellness' => [
                'name' => 'Daily Wellness & Capsules',
                'slug' => 'daily-wellness',
                'image' => 'images/card_immuno_rasayan_hd.jpg',
                'is_active' => 1
            ],
        ];

        $catModels = [];
        foreach ($categories as $key => $data) {
            $catModels[$key] = Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 2. Products
        $products = [
            [
                'name' => 'Diabetes Pro MadhuCurex powder',
                'slug' => 'diabetes-pro-madhucurex-powder',
                'price' => 3499.00,
                'sale_price' => 2499.00,
                'category_id' => $catModels['madhucurex']->id,
                'images' => ['images/card_diabetes_pro_hd.jpg', 'images/diabetes_pro_product_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 1,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Liver Detox capsule',
                'slug' => 'liver-detox-capsule',
                'price' => 3499.00,
                'sale_price' => 2499.00,
                'category_id' => $catModels['liver-detox']->id,
                'images' => ['images/card_liver_detox_hd.jpg', 'images/liver_detox_product_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 1,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Pilecurex kit',
                'slug' => 'pilecurex-kit',
                'price' => 3999.00,
                'sale_price' => 2699.00,
                'category_id' => $catModels['pilecurex']->id,
                'images' => ['images/card_pilecurex_hd.jpg', 'images/pilecurex_product_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 1,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Paachan Sathi pro',
                'slug' => 'paachan-sathi-pro',
                'price' => 2999.00,
                'sale_price' => 1999.00,
                'category_id' => $catModels['herbal-powders']->id,
                'images' => ['images/card_paachan_sathi_hd.jpg', 'images/paachan_sathi_product_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 0,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Triphala Churna Pure Detox Powder',
                'slug' => 'triphala-churna-pure-detox',
                'price' => 1999.00,
                'sale_price' => 1499.00,
                'category_id' => $catModels['herbal-powders']->id,
                'images' => ['images/card_triphala_hd.jpg', 'images/triphala_churna_pure_detox_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 1,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Arthro Relief Joint Care Tablets',
                'slug' => 'arthro-relief-joint-care',
                'price' => 2800.00,
                'sale_price' => 2199.00,
                'category_id' => $catModels['daily-wellness']->id,
                'images' => ['images/card_arthro_relief_hd.jpg', 'images/arthro_relief_joint_care_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 0,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Himalayan Pure Shilajit Resin',
                'slug' => 'himalayan-pure-shilajit-resin',
                'price' => 4200.00,
                'sale_price' => 2999.00,
                'category_id' => $catModels['daily-wellness']->id,
                'images' => ['images/card_shilajit_hd.jpg', 'images/himalayan_pure_shilajit_resin_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 1,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
            [
                'name' => 'Immuno Rasayan Herbal Care',
                'slug' => 'immuno-rasayan-herbal-care',
                'price' => 2400.00,
                'sale_price' => 1899.00,
                'category_id' => $catModels['daily-wellness']->id,
                'images' => ['images/card_immuno_rasayan_hd.jpg', 'images/immuno_rasayan_herbal_care_hd.jpg'],
                'is_featured' => 1,
                'is_bestseller' => 0,
                'quantity' => 100,
                'pack_sizes' => [
                    ['qty' => 1, 'badge' => 'Regular Supply', 'discount_percent' => 0],
                    ['qty' => 2, 'badge' => 'Save Extra 15%', 'discount_percent' => 15],
                    ['qty' => 3, 'badge' => 'Best Value Combo', 'discount_percent' => 25],
                ]
            ],
        ];

        foreach ($products as $pData) {
            Product::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );
        }

        // 3. Banners
        $banners = [
            [
                'title' => 'Detox your liver, live healthier!',
                'subtitle' => 'Experience the pure power of nature with our Liver Detox Herbal Formulation.',
                'image_path' => 'images/banner_liver_detox_hero_hd.jpg',
                'link' => '/product/liver-detox-capsule',
                'is_active' => 1
            ],
            [
                'title' => 'PILECUREX KIT',
                'subtitle' => 'Natural Ayurvedic Care for Relief from Piles Discomfort',
                'image_path' => 'images/banner_pilecurex_care_hd.jpg',
                'link' => '/product/pilecurex-kit',
                'is_active' => 1
            ],
            [
                'title' => 'Diabetes Pro MadhuCurex',
                'subtitle' => 'शुगर और संपूर्ण स्वास्थ्य संतुलन बनाए रखे',
                'image_path' => 'images/banner_diabetes_pro_hero_hd.jpg',
                'link' => '/product/diabetes-pro-madhucurex-powder',
                'is_active' => 1
            ],
            [
                'title' => 'Choose Pure • Choose Dhuria Ayurveda',
                'subtitle' => 'Authentic formulations crafted to bring health, balance & vitality to your home.',
                'image_path' => 'images/banner_choose_pure_hd.jpg',
                'link' => '/shop',
                'is_active' => 1
            ],
        ];

        foreach ($banners as $bData) {
            Banner::updateOrCreate(
                ['title' => $bData['title']],
                $bData
            );
        }
    }
}
