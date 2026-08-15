<?php
// FILE: app/Http/Controllers/Api/Admin/OrderController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number',    'like', "%{$search}%")
                  ->orWhere('customer_name',  'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->through(fn ($o) => [
                'id'             => $o->id,
                'order_number'   => $o->order_number,
                'customer_name'  => $o->customer_name,
                'customer_email' => $o->customer_email,
                'status'         => $o->status,
                'payment_status' => $o->payment_status,
                'total'          => $o->total,
                'items_count'    => $o->items->count(),
                'created_at'     => $o->created_at->format('d M Y'),
            ]);

        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status updated.',
            'order'   => $order->fresh(),
        ]);
    }
}