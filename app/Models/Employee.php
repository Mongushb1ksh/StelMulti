<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'position',
        'department',
        'hire_date',
        'salary',
        'employment_type',
        'status',
        'phone',
        'address',
        'birth_date',
        'emergency_contact',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function validateData(array $data, $employeeId = null)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'employee_number' => 'required|string|max:50|unique:employees,employee_number' . ($employeeId ? ",$employeeId" : ''),
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:active,inactive,terminated',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createEmployee(array $data): self
    {
        $validated = self::validateData($data);
        return self::create($validated);
    }

    public static function updateEmployee(array $data, int $id): self
    {
        $employee = self::findOrFail($id);
        $validated = self::validateData($data, $id);
        $employee->update($validated);
        return $employee;
    }

    public static function filterEmployees(array $filters)
    {
        $query = self::with('user');

        if (isset($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }

        if (isset($filters['name'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getEmploymentTypeTextAttribute(): string
    {
        $types = [
            'full_time' => 'Полная занятость',
            'part_time' => 'Частичная занятость',
            'contract' => 'Контракт',
            'intern' => 'Стажер',
        ];

        return $types[$this->employment_type] ?? $this->employment_type;
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'active' => 'Активный',
            'inactive' => 'Неактивный',
            'terminated' => 'Уволен',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getYearsOfServiceAttribute(): int
    {
        return $this->hire_date->diffInYears(now());
    }

    public static function deleteEmployee(int $id): void
    {
        $employee = self::findOrFail($id);
        $employee->delete();
    }

    public static function getDepartments(): array
    {
        return self::distinct()->pluck('department')->toArray();
    }

    public static function getPositions(): array
    {
        return self::distinct()->pluck('position')->toArray();
    }
}

