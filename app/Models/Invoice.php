<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'order_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function validateData(array $data, $invoiceId = null)
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'paid_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createInvoice(array $data): self
    {
        $validated = self::validateData($data);
        $validated['invoice_number'] = self::generateInvoiceNumber();
        return self::create($validated);
    }

    public static function updateInvoice(array $data, int $id): self
    {
        $invoice = self::findOrFail($id);
        $validated = self::validateData($data, $id);
        $invoice->update($validated);
        return $invoice;
    }

    private static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $lastInvoice = self::where('invoice_number', 'like', $prefix . $year . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
    }

    public function markAsOverdue(): void
    {
        if ($this->status !== 'paid' && $this->due_date < now()) {
            $this->update(['status' => 'overdue']);
        }
    }

    public static function filterInvoices(array $filters)
    {
        $query = self::with(['customer', 'order']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('issue_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('issue_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'draft' => 'Черновик',
            'sent' => 'Отправлен',
            'paid' => 'Оплачен',
            'overdue' => 'Просрочен',
            'cancelled' => 'Отменен',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public static function deleteInvoice(int $id): void
    {
        $invoice = self::findOrFail($id);
        $invoice->delete();
    }
}

