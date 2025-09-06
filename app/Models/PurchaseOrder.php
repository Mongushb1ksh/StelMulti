<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'created_by',
        'total_amount',
        'status',
        'order_date',
        'expected_delivery',
        'received_date',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery' => 'date',
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function validateData(array $data, $purchaseOrderId = null)
    {
        $rules = [
            'supplier_id' => 'required|exists:suppliers,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,confirmed,received,cancelled',
            'order_date' => 'required|date',
            'expected_delivery' => 'nullable|date|after:order_date',
            'received_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createPurchaseOrder(array $data): self
    {
        $validated = self::validateData($data);
        $validated['po_number'] = self::generatePONumber();
        $validated['created_by'] = \Illuminate\Support\Facades\Auth::id();
        return self::create($validated);
    }

    public static function updatePurchaseOrder(array $data, int $id): self
    {
        $purchaseOrder = self::findOrFail($id);
        $validated = self::validateData($data, $id);
        $purchaseOrder->update($validated);
        return $purchaseOrder;
    }

    private static function generatePONumber(): string
    {
        $prefix = 'PO';
        $year = date('Y');
        $lastPO = self::where('po_number', 'like', $prefix . $year . '%')
            ->orderBy('po_number', 'desc')
            ->first();

        if ($lastPO) {
            $lastNumber = (int) substr($lastPO->po_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function markAsReceived(): void
    {
        $this->update([
            'status' => 'received',
            'received_date' => now(),
        ]);
    }

    public function markAsConfirmed(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    public static function filterPurchaseOrders(array $filters)
    {
        $query = self::with(['supplier', 'createdBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('order_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('order_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'draft' => 'Черновик',
            'sent' => 'Отправлен',
            'confirmed' => 'Подтвержден',
            'received' => 'Получен',
            'cancelled' => 'Отменен',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public static function deletePurchaseOrder(int $id): void
    {
        $purchaseOrder = self::findOrFail($id);
        $purchaseOrder->delete();
    }
}
