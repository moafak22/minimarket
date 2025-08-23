<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'Latest iPhone with advanced camera system',
                'price' => 999.99,
                'stock_quantity' => 50,
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'brand' => 'Apple',
                'sku' => 'IPHONE-14-PRO',
                'is_active' => true,
                'weight' => 0.2,
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'description' => 'Premium Android smartphone',
                'price' => 899.99,
                'stock_quantity' => 30,
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'brand' => 'Samsung',
                'sku' => 'GALAXY-S23',
                'is_active' => true,
                'weight' => 0.18,
            ],
            [
                'name' => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes',
                'price' => 149.99,
                'stock_quantity' => 75,
                'category_id' => $categories->where('slug', 'clothing-fashion')->first()->id,
                'brand' => 'Nike',
                'sku' => 'NIKE-AM270',
                'is_active' => true,
                'weight' => 0.8,
            ],
            [
                'name' => 'The Great Gatsby',
                'description' => 'Classic American novel by F. Scott Fitzgerald',
                'price' => 12.99,
                'stock_quantity' => 100,
                'category_id' => $categories->where('slug', 'books-media')->first()->id,
                'brand' => 'Scribner',
                'sku' => 'BOOK-GATSBY',
                'is_active' => true,
                'weight' => 0.3,
            ],
            [
                'name' => 'Garden Hose 50ft',
                'description' => 'Heavy-duty garden hose for watering',
                'price' => 39.99,
                'stock_quantity' => 25,
                'category_id' => $categories->where('slug', 'home-garden')->first()->id,
                'brand' => 'FlexiHose',
                'sku' => 'HOSE-50FT',
                'is_active' => true,
                'weight' => 2.5,
            ],
            [
                'name' => 'Wilson Tennis Racket',
                'description' => 'Professional tennis racket for advanced players',
                'price' => 199.99,
                'stock_quantity' => 15,
                'category_id' => $categories->where('slug', 'sports-recreation')->first()->id,
                'brand' => 'Wilson',
                'sku' => 'TENNIS-RACKET-PRO',
                'is_active' => true,
                'weight' => 0.3,
            ],
            [
                'name' => 'Organic Face Moisturizer',
                'description' => 'Natural face moisturizer for all skin types',
                'price' => 24.99,
                'stock_quantity' => 60,
                'category_id' => $categories->where('slug', 'health-beauty')->first()->id,
                'brand' => 'NatureGlow',
                'sku' => 'MOISTURIZER-ORG',
                'is_active' => true,
                'weight' => 0.15,
            ],
            [
                'name' => 'Premium Coffee Beans',
                'description' => 'Single-origin coffee beans from Colombia',
                'price' => 18.99,
                'stock_quantity' => 80,
                'category_id' => $categories->where('slug', 'food-beverages')->first()->id,
                'brand' => 'CoffeeCraft',
                'sku' => 'COFFEE-COLOMBIA',
                'is_active' => true,
                'weight' => 0.5,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
