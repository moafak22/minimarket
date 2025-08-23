<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'is_active' => true,
            ],
            [
                'name' => 'Clothing & Fashion',
                'slug' => 'clothing-fashion',
                'description' => 'Clothes, shoes, and fashion accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and gardening items',
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, movies, music, and educational materials',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Recreation',
                'slug' => 'sports-recreation',
                'description' => 'Sports equipment and recreational items',
                'is_active' => true,
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'description' => 'Personal care, health, and beauty products',
                'is_active' => true,
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Food items and beverages',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
