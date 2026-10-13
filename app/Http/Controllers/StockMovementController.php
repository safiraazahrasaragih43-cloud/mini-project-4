<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'user'])
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->type, function ($query, $type) {
                if (in_array($type, ['IN', 'OUT'])) {
                    $query->where('type', $type);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function create(Request $request)
    {
        $defaultType = in_array(strtoupper($request->type), ['IN', 'OUT']) ? strtoupper($request->type) : 'IN';
        $selectedProductId = $request->product_id;
        $products = Product::orderBy('name')->get();

        return view('stock.create', compact('defaultType', 'selectedProductId', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:IN,OUT'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            // Business Logic P4: Transaction, lock row with FOR UPDATE, and prevent negative stock
            DB::transaction(function () use ($validated, $request) {
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

                if ($validated['type'] === 'OUT') {
                    if ($product->stock < $validated['quantity']) {
                        throw new Exception("Stok tidak mencukupi! Stok saat ini untuk '{$product->name}' hanya {$product->stock} unit, sedangkan permintaan keluar adalah {$validated['quantity']} unit.");
                    }
                    $product->decrement('stock', $validated['quantity']);
                } else {
                    $product->increment('stock', $validated['quantity']);
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => $validated['type'],
                    'quantity' => $validated['quantity'],
                    'notes' => $validated['notes'],
                ]);
            });

            $typeLabel = $validated['type'] === 'IN' ? 'Masuk' : 'Keluar';
            return redirect()->route('stock.index')
                ->with('success', "Transaksi Barang {$typeLabel} sebanyak {$validated['quantity']} unit berhasil dicatat!");

        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
