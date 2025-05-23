<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'start_date',
        'end_date',
        'quality_check',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function getAll(Request $request): JsonResponse
    {
        $tasks = self::with('order')->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }

    public static function getById(Request $request, $id): JsonResponse
    {
        $task = self::with('order')->find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Production task not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'task' => $task
        ]);
    }

    public static function createTask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|in:queued,in_progress,completed',
            'start_date' => 'required|date',
            'quality_check' => 'nullable|string',
        ]);

        $task = self::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Production task created successfully',
            'task' => $task
        ], 201);
    }

    public static function updateTask(Request $request, $id): JsonResponse
    {
        $task = self::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Production task not found'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'sometimes|string|in:queued,in_progress,completed',
            'end_date' => 'nullable|date',
            'quality_check' => 'nullable|string',
        ]);

        $task->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Production task updated successfully',
            'task' => $task
        ]);
    }

    public static function deleteTask(Request $request, $id): JsonResponse
    {
        $task = self::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Production task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Production task deleted successfully'
        ]);
    }
}