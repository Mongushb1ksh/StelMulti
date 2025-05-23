<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAll(Request $request): JsonResponse
    {
        $logs = self::with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'logs' => $logs
        ]);
    }

    public static function createLog($userId, $action, $details = null): void
    {
        self::create([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
        ]);
    }
}