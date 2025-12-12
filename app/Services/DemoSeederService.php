<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class DemoSeederService
{
    /**
     * Seed demo data for a user.
     */
    public function seedForUser(User $user): void
    {
        $categories = $this->seedCategories($user);
        $this->seedProducts($user, $categories);
    }

    /**
     * Seed sample categories.
     */
    protected function seedCategories(User $user): array
    {
        $categoriesData = [
            ['name' => 'Electronics', 'description' => 'Gadgets, devices, and electronic accessories'],
            ['name' => 'Clothing', 'description' => 'Fashion and apparel for all ages'],
            ['name' => 'Home & Garden', 'description' => 'Furniture, decor, and garden supplies'],
            ['name' => 'Sports', 'description' => 'Sports equipment and fitness gear'],
            ['name' => 'Books', 'description' => 'Physical and digital books across all genres'],
        ];

        $categories = [];
        foreach ($categoriesData as $index => $data) {
            $categories[] = Category::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . $user->id,
                'description' => $data['description'],
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        return $categories;
    }

    /**
     * Seed sample products.
     */
    protected function seedProducts(User $user, array $categories): void
    {
        $productsData = [
            // Electronics
            ['name' => 'Wireless Bluetooth Headphones', 'category' => 0, 'price' => 79.99, 'compare_price' => 99.99, 'stock' => 50, 'status' => 'published', 'is_featured' => true],
            ['name' => 'Smart Watch Pro', 'category' => 0, 'price' => 249.99, 'compare_price' => null, 'stock' => 25, 'status' => 'published', 'is_featured' => true],
            ['name' => 'USB-C Hub Adapter', 'category' => 0, 'price' => 34.99, 'compare_price' => 44.99, 'stock' => 100, 'status' => 'published', 'is_featured' => false],
            ['name' => 'Portable Power Bank 20000mAh', 'category' => 0, 'price' => 49.99, 'compare_price' => null, 'stock' => 75, 'status' => 'published', 'is_featured' => false],

            // Clothing
            ['name' => 'Premium Cotton T-Shirt', 'category' => 1, 'price' => 29.99, 'compare_price' => null, 'stock' => 200, 'status' => 'published', 'is_featured' => false],
            ['name' => 'Classic Denim Jeans', 'category' => 1, 'price' => 59.99, 'compare_price' => 79.99, 'stock' => 150, 'status' => 'published', 'is_featured' => true],
            ['name' => 'Winter Jacket', 'category' => 1, 'price' => 149.99, 'compare_price' => 199.99, 'stock' => 30, 'status' => 'draft', 'is_featured' => false],

            // Home & Garden
            ['name' => 'Modern Table Lamp', 'category' => 2, 'price' => 45.99, 'compare_price' => null, 'stock' => 80, 'status' => 'published', 'is_featured' => false],
            ['name' => 'Indoor Plant Pot Set', 'category' => 2, 'price' => 24.99, 'compare_price' => 34.99, 'stock' => 120, 'status' => 'published', 'is_featured' => true],
            ['name' => 'Memory Foam Pillow', 'category' => 2, 'price' => 39.99, 'compare_price' => null, 'stock' => 60, 'status' => 'published', 'is_featured' => false],

            // Sports
            ['name' => 'Yoga Mat Premium', 'category' => 3, 'price' => 35.99, 'compare_price' => null, 'stock' => 90, 'status' => 'published', 'is_featured' => false],
            ['name' => 'Resistance Bands Set', 'category' => 3, 'price' => 19.99, 'compare_price' => 29.99, 'stock' => 150, 'status' => 'published', 'is_featured' => true],
            ['name' => 'Running Shoes Pro', 'category' => 3, 'price' => 89.99, 'compare_price' => 119.99, 'stock' => 40, 'status' => 'published', 'is_featured' => true],

            // Books
            ['name' => 'The Art of Programming', 'category' => 4, 'price' => 49.99, 'compare_price' => null, 'stock' => 500, 'status' => 'published', 'is_featured' => false],
            ['name' => 'Modern Web Development', 'category' => 4, 'price' => 39.99, 'compare_price' => 54.99, 'stock' => 300, 'status' => 'published', 'is_featured' => true],
            ['name' => 'Design Patterns Explained', 'category' => 4, 'price' => 44.99, 'compare_price' => null, 'stock' => 200, 'status' => 'draft', 'is_featured' => false],
        ];

        foreach ($productsData as $data) {
            Product::create([
                'user_id' => $user->id,
                'category_id' => $categories[$data['category']]->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . $user->id,
                'description' => fake()->paragraph(2),
                'content' => fake()->paragraphs(3, true),
                'price' => $data['price'],
                'compare_price' => $data['compare_price'],
                'stock' => $data['stock'],
                'sku' => strtoupper(Str::random(8)),
                'status' => $data['status'],
                'is_featured' => $data['is_featured'],
                'is_downloadable' => false,
            ]);
        }
    }

    /**
     * Clear all demo data for a user.
     */
    public function clearForUser(User $user): void
    {
        $user->products()->forceDelete();
        $user->categories()->delete();
    }

    /**
     * Reset demo data for a user.
     */
    public function resetForUser(User $user): void
    {
        $this->clearForUser($user);
        $this->seedForUser($user);
    }
}
