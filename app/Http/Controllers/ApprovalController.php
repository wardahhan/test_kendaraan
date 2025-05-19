<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 

class ApprovalController extends Controller
{
    public function index()
    {
       
        $orders = Order::where('status', 'pending')->get();

      
        return view('approvals.index', compact('orders'));
    }
}
