<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'customer_id', 'date_from', 'date_to']);
        $invoices = Invoice::filterInvoices($filters);
        $customers = Customer::all();
        
        return view('invoices.index', compact('invoices', 'customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        $orders = Order::where('status', 'completed')->get();
        
        return view('invoices.create', compact('customers', 'orders'));
    }

    public function store(Request $request)
    {
        try {
            $invoice = Invoice::createInvoice($request->all());
            
            Log::info('Invoice created', ['invoice_id' => $invoice->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('invoices.index')
                ->with('success', 'Счет успешно создан');
        } catch (\Exception $e) {
            Log::error('Error creating invoice', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при создании счета']);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'order']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::all();
        $orders = Order::where('status', 'completed')->get();
        
        return view('invoices.edit', compact('invoice', 'customers', 'orders'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        try {
            Invoice::updateInvoice($request->all(), $invoice->id);
            
            Log::info('Invoice updated', ['invoice_id' => $invoice->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('invoices.index')
                ->with('success', 'Счет успешно обновлен');
        } catch (\Exception $e) {
            Log::error('Error updating invoice', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Ошибка при обновлении счета']);
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            Invoice::deleteInvoice($invoice->id);
            
            Log::info('Invoice deleted', ['invoice_id' => $invoice->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('invoices.index')
                ->with('success', 'Счет успешно удален');
        } catch (\Exception $e) {
            Log::error('Error deleting invoice', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Ошибка при удалении счета']);
        }
    }

    public function markAsPaid(Invoice $invoice)
    {
        try {
            $invoice->markAsPaid();
            
            Log::info('Invoice marked as paid', ['invoice_id' => $invoice->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Счет отмечен как оплаченный');
        } catch (\Exception $e) {
            Log::error('Error marking invoice as paid', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Ошибка при отметке счета как оплаченного']);
        }
    }

    public function generatePDF(Invoice $invoice)
    {
        // Здесь будет логика генерации PDF
        return response()->json(['message' => 'PDF generation not implemented yet']);
    }
}

