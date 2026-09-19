<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'details.product'])->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }
}
