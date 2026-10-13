<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users (Admin & Staff)
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Gudang',
                'email' => 'admin@inventory.local',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'Administrator Gudang',
                'email' => 'admin@inventory.local',
                'phone' => '081234567890',
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Operasional',
                'email' => 'staff@inventory.local',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $staff->id],
            [
                'full_name' => 'Staff Operasional',
                'email' => 'staff@inventory.local',
                'phone' => '089876543210',
            ]
        );

        // 2. Seed Categories
        $categoriesData = [
            ['name' => 'Kamera Analog & Retro', 'description' => 'Kamera film 35mm, point and shoot retro vintage'],
            ['name' => 'Digicam & Compact', 'description' => 'Kamera digital saku klasik era 2000-an'],
            ['name' => 'Lensa & Aksesoris', 'description' => 'Lensa fix, zoom, filter, strap, dan tas kamera'],
            ['name' => 'Film Roll & Baterai', 'description' => 'Roll film color/BW, baterai khusus kamera jadul'],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[] = Category::firstOrCreate(['name' => $cData['name']], $cData);
        }

        // 3. Seed Suppliers
        $suppliersData = [
            [
                'name' => 'PT Retro Optik Nusantara',
                'contact_person' => 'Budi Santoso',
                'phone' => '081122334455',
                'address' => 'Jl. Malioboro No. 45, Yogyakarta'
            ],
            [
                'name' => 'DigiStore Importir Utama',
                'contact_person' => 'Siti Rahma',
                'phone' => '082233445566',
                'address' => 'Jl. Mangga Dua Raya No. 12, Jakarta'
            ],
            [
                'name' => 'Analog Supply Co.',
                'contact_person' => 'Andi Pratama',
                'phone' => '083344556677',
                'address' => 'Jl. Riau No. 88, Bandung'
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sData['name']], $sData);
        }

        // 4. Seed Products
        $productsData = [
            [
                'category_id' => $categories[0]->id,
                'sku' => 'CAM-OLY-01',
                'name' => 'Olympus Trip 35 Vintage',
                'price' => 1250000.00,
                'stock' => 8,
                'minimum_stock' => 3,
                'supplier_ids' => [$suppliers[0]->id, $suppliers[2]->id]
            ],
            [
                'category_id' => $categories[0]->id,
                'sku' => 'CAM-CAN-02',
                'name' => 'Canon Canonet QL17 GIII',
                'price' => 2100000.00,
                'stock' => 2,
                'minimum_stock' => 4,
                'supplier_ids' => [$suppliers[0]->id]
            ],
            [
                'category_id' => $categories[1]->id,
                'sku' => 'DIG-SON-01',
                'name' => 'Sony Cyber-shot DSC-W55 Silver',
                'price' => 850000.00,
                'stock' => 15,
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[1]->id]
            ],
            [
                'category_id' => $categories[1]->id,
                'sku' => 'DIG-CAN-02',
                'name' => 'Canon IXY Digital 50 (Mint)',
                'price' => 950000.00,
                'stock' => 4,
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[1]->id]
            ],
            [
                'category_id' => $categories[2]->id,
                'sku' => 'ACC-FLT-01',
                'name' => 'Filter UV Kenko 49mm',
                'price' => 75000.00,
                'stock' => 25,
                'minimum_stock' => 10,
                'supplier_ids' => [$suppliers[2]->id]
            ],
            [
                'category_id' => $categories[3]->id,
                'sku' => 'FLM-KOD-01',
                'name' => 'Kodak ColorPlus 200 (36 Exp)',
                'price' => 145000.00,
                'stock' => 30,
                'minimum_stock' => 10,
                'supplier_ids' => [$suppliers[2]->id]
            ],
        ];

        foreach ($productsData as $pData) {
            $supplierIds = $pData['supplier_ids'];
            unset($pData['supplier_ids']);

            $product = Product::firstOrCreate(['sku' => $pData['sku']], $pData);
            $product->suppliers()->syncWithoutDetaching($supplierIds);

            // Log Initial Stock IN
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'type' => 'IN',
                'quantity' => $product->stock,
                'notes' => 'Stok awal inisialisasi sistem'
            ]);
        }
    }
}
