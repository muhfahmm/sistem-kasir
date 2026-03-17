<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions and dashboard stats.
     */
    public function index()
    {
        $today = Carbon::today();
        
        $omzetToday = Transaction::whereDate('created_at', $today)->sum('total');
        $transactionsCount = Transaction::whereDate('created_at', $today)->count();
        $recentTransactions = Transaction::with('items.product')->orderBy('created_at', 'desc')->take(10)->get();
        
        // Top Products based on quantity sold
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with('product')
            ->take(5)
            ->get();

        return response()->json([
            'stats' => [
                'omzet_today' => $omzetToday,
                'transactions_count' => $transactionsCount,
            ],
            'recent_transactions' => $recentTransactions,
            'top_products' => $topProducts
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|unique:transactions',
            'customer_name' => 'nullable|string',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric'
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $transaction = Transaction::create([
                    'order_id' => $validated['order_id'],
                    'customer_name' => $validated['customer_name'] ?? 'Pelanggan Umum',
                    'subtotal' => $validated['subtotal'],
                    'tax' => $validated['tax'],
                    'total' => $validated['total']
                ]);

                foreach ($validated['items'] as $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);

                    // Optional: Deduct stock
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                return response()->json([
                    'message' => 'Transaction saved successfully',
                    'transaction' => $transaction->load('items')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
