<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Apple',
            'Samsung',
            'Sony',
            'Nike',
            'Adidas',
            'Microsoft',
            'Dell',
            'HP',
            'Lenovo',
            'Asus',
            'Canon',
            'Logitech',
            'Xiaomi',
            'JBL',
            'Bose',
            'Puma',
            'LG',
            'Philips',
            'Razer',
            'Acer',
        ];
        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'is_active' => true,
            ]);
        }
    }
}
