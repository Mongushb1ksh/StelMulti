<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        $stats = $this->getDashboardStats();
        $recentOrders = Order::with('product')->latest()->take(5)->get();
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();
        
        return view('reports.dashboard', compact('stats', 'recentOrders', 'recentInvoices'));
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $salesData = $this->getSalesData($startDate, $endDate, $period);
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $topCustomers = $this->getTopCustomers($startDate, $endDate);

        return view('reports.sales', compact('salesData', 'topProducts', 'topCustomers', 'startDate', 'endDate', 'period'));
    }

    public function inventory()
    {
        $lowStockProducts = Product::where('quantity', '<', 10)->get();
        $outOfStockProducts = Product::where('quantity', 0)->get();
        $stockValue = Product::sum(DB::raw('quantity * unit_price'));
        $materialValue = Material::sum(DB::raw('quantity * unit_price'));

        return view('reports.inventory', compact('lowStockProducts', 'outOfStockProducts', 'stockValue', 'materialValue'));
    }

    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $revenue = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount');

        $pendingInvoices = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->whereIn('status', ['sent', 'overdue'])
            ->sum('total_amount');

        $overdueInvoices = Invoice::where('status', 'overdue')->sum('total_amount');

        $invoiceStats = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('status')
            ->get();

        return view('reports.financial', compact('revenue', 'pendingInvoices', 'overdueInvoices', 'invoiceStats', 'startDate', 'endDate'));
    }

    public function production()
    {
        $productionStats = DB::table('production_tasks')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $completedTasks = DB::table('production_tasks')
            ->where('status', 'completed')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        $pendingTasks = DB::table('production_tasks')
            ->where('status', 'in_progress')
            ->count();

        return view('reports.production', compact('productionStats', 'completedTasks', 'pendingTasks'));
    }

    private function getDashboardStats()
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('quantity', '<', 10)->count(),
            'total_employees' => Employee::where('status', 'active')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_invoices' => Invoice::whereIn('status', ['sent', 'overdue'])->sum('total_amount'),
        ];
    }

    private function getSalesData($startDate, $endDate, $period)
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        if ($period === 'day') {
            return $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } elseif ($period === 'week') {
            return $query->select(DB::raw('YEARWEEK(created_at) as week'), DB::raw('count(*) as count'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();
        } else {
            return $query->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }
    }

    private function getTopProducts($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('product')
            ->select('product_id', DB::raw('sum(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();
    }

    private function getTopCustomers($startDate, $endDate)
    {
        return Customer::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        })
        ->withCount(['orders' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        }])
        ->orderByDesc('orders_count')
        ->take(10)
        ->get();
    }
}

