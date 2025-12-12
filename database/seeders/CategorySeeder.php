<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
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

        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Phones, laptops, tablets, and other electronic devices',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=400&fit=crop',
                'color' => '#3B82F6',
                'icon' => 'Smartphone',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion items for men, women, and children',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=400&fit=crop',
                'color' => '#EC4899',
                'icon' => 'Shirt',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Books',
                'description' => 'Fiction, non-fiction, educational, and more',
                'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=400&h=400&fit=crop',
                'color' => '#8B5CF6',
                'icon' => 'Book',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Furniture, decor, and gardening supplies',
                'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop',
                'color' => '#10B981',
                'icon' => 'Home',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Equipment and gear for sports and outdoor activities',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=400&h=400&fit=crop',
                'color' => '#F59E0B',
                'icon' => 'Bike',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Health & Beauty',
                'description' => 'Personal care, cosmetics, and wellness products',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=400&fit=crop',
                'color' => '#EF4444',
                'icon' => 'Heart',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Fun toys and games for all ages',
                'image' => 'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=400&h=400&fit=crop',
                'color' => '#F97316',
                'icon' => 'Gamepad2',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car parts, accessories, and tools',
                'image' => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=400&h=400&fit=crop',
                'color' => '#6B7280',
                'icon' => 'Car',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Food & Beverages',
                'description' => 'Gourmet food, snacks, and drinks',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop',
                'color' => '#84CC16',
                'icon' => 'Coffee',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Pet Supplies',
                'description' => 'Food, toys, and accessories for pets',
                'image' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=400&fit=crop',
                'color' => '#A855F7',
                'icon' => 'PawPrint',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'Stationery, furniture, and office equipment',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=400&fit=crop',
                'color' => '#0EA5E9',
                'icon' => 'Briefcase',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Music & Instruments',
                'description' => 'Musical instruments and audio equipment',
                'image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=400&h=400&fit=crop',
                'color' => '#14B8A6',
                'icon' => 'Music',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'user_id' => $user->id,
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'color' => $category['color'],
                'icon' => $category['icon'],
                'is_active' => $category['is_active'],
                'sort_order' => $category['sort_order'],
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
