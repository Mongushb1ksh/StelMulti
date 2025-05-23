<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_email',
        'product_id',
        'quantity',
        'status',
        'manager_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function productionTask()
    {
        return $this->hasOne(ProductionTask::class);
    }

    public static function getAll(Request $request): JsonResponse
    {
        $orders = self::with(['product', 'manager', 'productionTask'])->get();

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    public static function getById(Request $request, $id): JsonResponse
    {
        $order = self::with(['product', 'manager', 'productionTask'])->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }

    public static function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $validated['manager_id'] = $request->user()->id;

        $order = self::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    public static function updateOrder(Request $request, $id): JsonResponse
    {
        $order = self::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $validated = $request->validate([
            'client_name' => 'sometimes|string|max:255',
            'client_email' => 'sometimes|email|max:255',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|string|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'order' => $order
        ]);
    }

    public static function deleteOrder(Request $request, $id): JsonResponse
    {
        $order = self::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully'
        ]);
    }
}