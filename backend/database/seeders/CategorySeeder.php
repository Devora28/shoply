<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'icon' => 'Laptop',
                'color' => 'bg-blue-100 text-blue-600',
                'children' => [
                    'Smartphones',
                    'Laptops',
                    'Tablets',
                    'Cameras',
                    'Televisions',
                    'Monitors',
                ],
            ],
            'Fashion' => [
                'icon' => 'Shirt',
                'color' => 'bg-pink-100 text-pink-600',
                'children' => [
                    'Men Clothing',
                    'Women Clothing',
                    'Shoes',
                    'Bags',
                    'Watches',
                    'Jewelry',
                ],
            ],
            'Home & Kitchen' => [
                'icon' => 'House',
                'color' => 'bg-amber-100 text-amber-600',
                'children' => [
                    'Furniture',
                    'Home Decor',
                    'Kitchen Appliances',
                    'Cookware',
                    'Bedding',
                    'Lighting',
                ],
            ],
            'Beauty & Health' => [
                'icon' => 'HeartPulse',
                'color' => 'bg-rose-100 text-rose-600',
                'children' => [
                    'Skincare',
                    'Makeup',
                    'Hair Care',
                    'Fragrances',
                    'Personal Care',
                    'Supplements',
                ],
            ],
            'Sports & Outdoors' => [
                'icon' => 'Dumbbell',
                'color' => 'bg-green-100 text-green-600',
                'children' => [
                    'Fitness',
                    'Camping',
                    'Cycling',
                    'Football',
                    'Swimming',
                    'Outdoor Clothing',
                ],
            ],
            'Books & Stationery' => [
                'icon' => 'BookOpen',
                'color' => 'bg-indigo-100 text-indigo-600',
                'children' => [
                    'Books',
                    'Notebooks',
                    'Office Supplies',
                    'Art Supplies',
                    'School Supplies',
                    'Printers',
                ],
            ],
        ];
        foreach ($categories as $parent => $data) {
            $parentCategory = Category::create([
                'name' => $parent,
                'slug' => Str::slug($parent),
                'image' => $data['icon'],
                'color' => $data['color'],
                'parent_id' => null,
                'is_active' => true,
            ]);
            foreach ($data['children'] as $child) {
                Category::create([
                    'name' => $child,
                    'slug' => Str::slug($child),
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
