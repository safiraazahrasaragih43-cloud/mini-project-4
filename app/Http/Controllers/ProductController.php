<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Allowlist sorting (Slide 16 Modul P4 & P5)
        $allowedSort = ['name', 'sku', 'price', 'stock', 'created_at'];
        $sortBy = in_array($request->sort, $allowedSort, true) ? $request->sort : 'name';
        $orderDir = strtoupper($request->dir) === 'DESC' ? 'desc' : 'asc';

        // Eloquent Query builder with search, filter, and eager loading
        $products = Product::with(['category', 'suppliers'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($sortBy, $orderDir)
            ->paginate(6)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sortBy', 'orderDir'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'sku' => $validated['sku'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            if (!empty($validated['suppliers'])) {
                $product->suppliers()->sync($validated['suppliers']);
            }

            if ($validated['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $validated['stock'],
                    'notes' => 'Stok awal penambahan produk baru'
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', "Produk '{$validated['name']}' berhasil ditambahkan ke inventaris!");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $selectedSuppliers = $product->suppliers->pluck('id')->toArray();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'selectedSuppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ]);

        DB::transaction(function () use ($product, $validated) {
            $product->update([
                'category_id' => $validated['category_id'],
                'sku' => $validated['sku'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            $product->suppliers()->sync($validated['suppliers'] ?? []);
        });

        return redirect()->route('products.index')
            ->with('success', "Produk '{$validated['name']}' berhasil diperbarui!");
    }

    public function destroy(Product $product)
    {
        // Business Rule P4: Produk yang memiliki riwayat mutasi dilarang dihapus sembarangan
        if ($product->stockMovements()->count() > 0) {
            return back()->with('error', "Gagal menghapus produk: Produk '{$product->name}' memiliki catatan riwayat mutasi stok. Hapus dilarang untuk menjaga integritas data.");
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk berhasil dihapus dari inventaris.");
    }
}
