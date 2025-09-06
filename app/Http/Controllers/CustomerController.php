<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'name', 'email']);
        $customers = Customer::filterCustomers($filters);
        
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        try {
            $customer = Customer::createCustomer($request->all());
            
            Log::info('Customer created', ['customer_id' => $customer->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('customers.index')
                ->with('success', 'Клиент успешно создан');
        } catch (\Exception $e) {
            Log::error('Error creating customer', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при создании клиента']);
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['orders', 'invoices']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            Customer::updateCustomer($request->all(), $customer->id);
            
            Log::info('Customer updated', ['customer_id' => $customer->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('customers.index')
                ->with('success', 'Клиент успешно обновлен');
        } catch (\Exception $e) {
            Log::error('Error updating customer', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при обновлении клиента']);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            Customer::deleteCustomer($customer->id);
            
            Log::info('Customer deleted', ['customer_id' => $customer->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('customers.index')
                ->with('success', 'Клиент успешно удален');
        } catch (\Exception $e) {
            Log::error('Error deleting customer', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Ошибка при удалении клиента']);
        }
    }
}
