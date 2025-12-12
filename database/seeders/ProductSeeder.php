<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No user found. Please create a user first.');
            return;
        }

        $categories = Category::where('user_id', $user->id)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        // First, create sample products with real images
        $this->createSampleProducts($user, $categories);

        // Then, generate 10k random products
        $this->generateRandomProducts($user, $categories);
    }

    private function createSampleProducts($user, $categories): void
    {
        $categoriesMap = $categories->keyBy('name');

        $products = [
            // Electronics
            [
                'category' => 'Electronics',
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple smartphone with advanced features',
                'content' => 'The iPhone 15 Pro features a titanium design, A17 Pro chip, and an improved camera system with a 48MP main sensor.',
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=400&h=400&fit=crop',
                'price' => 999.99,
                'compare_price' => 1099.99,
                'stock' => 50,
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'category' => 'Electronics',
                'name' => 'MacBook Pro 14"',
                'description' => 'Professional laptop with M3 Pro chip',
                'content' => 'The MacBook Pro 14" combines power and portability with the M3 Pro chip, delivering exceptional performance for professionals.',
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop',
                'price' => 1999.00,
                'compare_price' => null,
                'stock' => 25,
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'category' => 'Electronics',
                'name' => 'Samsung Galaxy Tab S9',
                'description' => 'Premium Android tablet with S Pen',
                'content' => 'The Galaxy Tab S9 features a stunning display, powerful processor, and comes with the S Pen for productivity and creativity.',
                'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400&h=400&fit=crop',
                'price' => 799.99,
                'compare_price' => 849.99,
                'stock' => 30,
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'category' => 'Electronics',
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-canceling headphones',
                'content' => 'Industry-leading noise cancellation with exceptional sound quality and all-day comfort.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
                'price' => 349.99,
                'compare_price' => 399.99,
                'stock' => 100,
                'status' => 'published',
                'is_featured' => false,
            ],
            // Clothing
            [
                'category' => 'Clothing',
                'name' => 'Premium Cotton T-Shirt',
                'description' => 'Comfortable 100% cotton t-shirt',
                'content' => 'Made from premium organic cotton, this t-shirt offers supreme comfort and durability for everyday wear.',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
                'price' => 29.99,
                'compare_price' => null,
                'stock' => 200,
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'category' => 'Clothing',
                'name' => 'Slim Fit Jeans',
                'description' => 'Modern slim fit denim jeans',
                'content' => 'Classic slim fit jeans made with stretch denim for comfort and style.',
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop',
                'price' => 79.99,
                'compare_price' => 99.99,
                'stock' => 150,
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'category' => 'Clothing',
                'name' => 'Leather Jacket',
                'description' => 'Genuine leather motorcycle jacket',
                'content' => 'Classic motorcycle jacket crafted from genuine leather with quilted lining.',
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
                'price' => 299.99,
                'compare_price' => 399.99,
                'stock' => 20,
                'status' => 'draft',
                'is_featured' => false,
            ],
            // Books
            [
                'category' => 'Books',
                'name' => 'The Art of Programming',
                'description' => 'Comprehensive guide to software development',
                'content' => 'A comprehensive guide covering modern software development practices, design patterns, and best practices.',
                'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400&h=400&fit=crop',
                'price' => 49.99,
                'compare_price' => null,
                'stock' => 500,
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'category' => 'Books',
                'name' => 'Laravel Mastery',
                'description' => 'Complete Laravel framework guide',
                'content' => 'Master the Laravel PHP framework with practical examples and real-world projects.',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=400&fit=crop',
                'price' => 39.99,
                'compare_price' => 59.99,
                'stock' => 300,
                'status' => 'published',
                'is_featured' => true,
            ],
            // Home & Garden
            [
                'category' => 'Home & Garden',
                'name' => 'Ergonomic Office Chair',
                'description' => 'Premium ergonomic chair for home office',
                'content' => 'Designed for all-day comfort with adjustable lumbar support, armrests, and breathable mesh back.',
                'image' => 'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?w=400&h=400&fit=crop',
                'price' => 449.99,
                'compare_price' => 549.99,
                'stock' => 40,
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'category' => 'Home & Garden',
                'name' => 'Smart LED Desk Lamp',
                'description' => 'Adjustable LED lamp with USB charging',
                'content' => 'Energy-efficient LED desk lamp with touch controls, adjustable brightness, and built-in USB port.',
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop',
                'price' => 59.99,
                'compare_price' => null,
                'stock' => 80,
                'status' => 'published',
                'is_featured' => false,
            ],
            // Sports & Outdoors
            [
                'category' => 'Sports & Outdoors',
                'name' => 'Yoga Mat Premium',
                'description' => 'Non-slip yoga and exercise mat',
                'content' => 'Extra thick, non-slip yoga mat perfect for all types of exercises and yoga practices.',
                'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=400&fit=crop',
                'price' => 34.99,
                'compare_price' => 44.99,
                'stock' => 150,
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'category' => 'Sports & Outdoors',
                'name' => 'Running Shoes Pro',
                'description' => 'Professional running shoes with cushioning',
                'content' => 'Lightweight running shoes with advanced cushioning technology for maximum comfort and performance.',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop',
                'price' => 129.99,
                'compare_price' => 159.99,
                'stock' => 75,
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'category' => 'Sports & Outdoors',
                'name' => 'Camping Tent 4-Person',
                'description' => 'Waterproof family camping tent',
                'content' => 'Spacious 4-person tent with waterproof coating, easy setup, and excellent ventilation.',
                'image' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=400&fit=crop',
                'price' => 199.99,
                'compare_price' => 249.99,
                'stock' => 30,
                'status' => 'draft',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            $category = $categories->get($product['category']);

            if (!$category) {
                continue;
            }

            Product::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'content' => $product['content'],
                'image' => $product['image'] ?? null,
                'price' => $product['price'],
                'compare_price' => $product['compare_price'],
                'stock' => $product['stock'],
                'sku' => strtoupper(Str::random(8)),
                'status' => $product['status'],
                'is_featured' => $product['is_featured'],
                'is_downloadable' => false,
            ]);
        }

        $this->command->info('Sample products seeded successfully!');
    }

    private function generateRandomProducts($user, $categories): void
    {
        $faker = Faker::create();
        $count = 10000;

        $this->command->info("Generating {$count} random products...");

        $statuses = ['draft', 'published', 'archived'];
        $categoryIds = $categories->pluck('id')->toArray();

        // Product name prefixes and suffixes for variety
        $prefixes = ['Premium', 'Professional', 'Ultimate', 'Essential', 'Classic', 'Modern', 'Deluxe', 'Elite', 'Advanced', 'Basic'];
        $types = ['Widget', 'Gadget', 'Device', 'Tool', 'Kit', 'Set', 'Bundle', 'Pack', 'System', 'Solution'];

        $products = [];
        $now = now();

        for ($i = 0; $i < $count; $i++) {
            $name = $faker->randomElement($prefixes) . ' ' . $faker->words(2, true) . ' ' . $faker->randomElement($types);
            $price = $faker->randomFloat(2, 9.99, 999.99);
            $hasComparePrice = $faker->boolean(30);

            $products[] = [
                'user_id' => $user->id,
                'category_id' => $faker->randomElement($categoryIds),
                'name' => $name,
                'slug' => Str::slug($name) . '-' . strtolower(Str::random(6)),
                'description' => $faker->sentence(10),
                'content' => $faker->paragraphs(3, true),
                'image' => 'https://picsum.photos/seed/' . Str::random(8) . '/400/400',
                'price' => $price,
                'compare_price' => $hasComparePrice ? $price * $faker->randomFloat(2, 1.1, 1.5) : null,
                'stock' => $faker->numberBetween(0, 500),
                'sku' => strtoupper(Str::random(8)),
                'status' => $faker->randomElement($statuses),
                'is_featured' => $faker->boolean(10),
                'is_downloadable' => $faker->boolean(5),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert in batches of 500
            if (count($products) >= 500) {
                Product::insert($products);
                $products = [];
                $this->command->info("Created " . ($i + 1) . " products...");
            }
        }

        // Insert remaining products
        if (!empty($products)) {
            Product::insert($products);
        }

        $this->command->info("{$count} random products generated successfully!");
    }
}
