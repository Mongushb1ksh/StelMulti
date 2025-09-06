<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['department', 'status', 'employment_type', 'name']);
        $employees = Employee::filterEmployees($filters);
        $departments = Employee::getDepartments();
        $positions = Employee::getPositions();
        
        return view('employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        $departments = Employee::getDepartments();
        $positions = Employee::getPositions();
        
        return view('employees.create', compact('users', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        try {
            $employee = Employee::createEmployee($request->all());
            
            Log::info('Employee created', ['employee_id' => $employee->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('employees.index')
                ->with('success', 'Сотрудник успешно создан');
        } catch (\Exception $e) {
            Log::error('Error creating employee', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при создании сотрудника']);
        }
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $users = User::all();
        $departments = Employee::getDepartments();
        $positions = Employee::getPositions();
        
        return view('employees.edit', compact('employee', 'users', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            Employee::updateEmployee($request->all(), $employee->id);
            
            Log::info('Employee updated', ['employee_id' => $employee->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('employees.index')
                ->with('success', 'Сотрудник успешно обновлен');
        } catch (\Exception $e) {
            Log::error('Error updating employee', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при обновлении сотрудника']);
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            Employee::deleteEmployee($employee->id);
            
            Log::info('Employee deleted', ['employee_id' => $employee->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('employees.index')
                ->with('success', 'Сотрудник успешно удален');
        } catch (\Exception $e) {
            Log::error('Error deleting employee', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Ошибка при удалении сотрудника']);
        }
    }

    public function terminate(Employee $employee)
    {
        try {
            $employee->update(['status' => 'terminated']);
            
            Log::info('Employee terminated', ['employee_id' => $employee->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('employees.show', $employee)
                ->with('success', 'Сотрудник уволен');
        } catch (\Exception $e) {
            Log::error('Error terminating employee', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Ошибка при увольнении сотрудника']);
        }
    }
}

