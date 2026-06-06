<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Expense;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // Data bulanan
        $monthlyIncomes = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $monthlyExpenses = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $monthlyBalance = $monthlyIncomes - $monthlyExpenses;

        // Data grafik 12 bulan
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $chartData[] = [
                'month'   => $m->translatedFormat('M Y'),
                'income'  => Income::where('user_id', $userId)
                    ->whereYear('date', $m->year)
                    ->whereMonth('date', $m->month)
                    ->sum('amount'),
                'expense' => Expense::where('user_id', $userId)
                    ->whereYear('date', $m->year)
                    ->whereMonth('date', $m->month)
                    ->sum('amount'),
            ];
        }

        // Data per kategori bulan ini
        $incomeByCategory = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $expenseByCategory = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('reports.index', compact(
            'monthlyIncomes', 'monthlyExpenses', 'monthlyBalance',
            'chartData', 'incomeByCategory', 'expenseByCategory',
            'year', 'month'
        ));
    }
}