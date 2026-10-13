<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Produk: COUNT(products.id)
        $totalProducts = Product::count();

        // 2. Nilai Inventory: SUM(stock * price)
        $totalInventoryValue = Product::selectRaw('SUM(stock * price) as total_val')->value('total_val') ?? 0;

        // 3. Produk Stok Rendah: stock <= minimum_stock
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // 4. Akumulasi Barang Masuk & Keluar
        $stockStats = StockMovement::selectRaw("
            SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END) as total_in,
            SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) as total_out
        ")->first();

        $totalIn = $stockStats->total_in ?? 0;
        $totalOut = $stockStats->total_out ?? 0;

        // 5. Produk Stok Rendah (Listing)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // 6. Ringkasan Kategori (Aggregate Queries via Eloquent)
        $categoryReports = Category::withCount('products')
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COALESCE(SUM(products.stock), 0) as total_stock'),
                DB::raw('COALESCE(AVG(products.price), 0) as avg_price'),
                DB::raw('COALESCE(SUM(products.stock * products.price), 0) as category_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();

        // 7. 5 Pergerakan Stok Terkini
        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalInventoryValue',
            'lowStockCount',
            'totalIn',
            'totalOut',
            'lowStockProducts',
            'categoryReports',
            'recentMovements'
        ));
    }
}
