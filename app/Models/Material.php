<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'unit_price',
    ];

    public static function getAll(Request $request): JsonResponse
    {
        $materials = self::all();

        return response()->json([
            'status' => 'success',
            'materials' => $materials
        ]);
    }

    public static function getById(Request $request, $id): JsonResponse
    {
        $material = self::find($id);

        if (!$material) {
            return response()->json([
                'status' => 'error',
                'message' => 'Material not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'material' => $material
        ]);
    }

    public static function createMaterial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $material = self::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Material created successfully',
            'material' => $material
        ], 201);
    }

    public static function updateMaterial(Request $request, $id): JsonResponse
    {
        $material = self::find($id);

        if (!$material) {
            return response()->json([
                'status' => 'error',
                'message' => 'Material not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
        ]);

        $material->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Material updated successfully',
            'material' => $material
        ]);
    }

    public static function deleteMaterial(Request $request, $id): JsonResponse
    {
        $material = self::find($id);

        if (!$material) {
            return response()->json([
                'status' => 'error',
                'message' => 'Material not found'
            ], 404);
        }

        $material->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Material deleted successfully'
        ]);
    }
}