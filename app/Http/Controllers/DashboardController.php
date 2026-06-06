<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Expense;
use App\Models\SavingsGoal;
use App\Models\Brankas;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalIncome = Income::where('user_id', $userId)->sum('amount');
        $totalExpense = Expense::where('user_id', $userId)->sum('amount');
        
        // Saldo total dari semua wallet
        $balance = Wallet::where('user_id', $userId)->sum('balance');

        $activeSavings = Brankas::where('user_id', $userId)
            ->where('status', 'belum_tercapai')
            ->count();

        $savingsGoals = Brankas::where('user_id', $userId)
            ->orderByRaw("FIELD(priority, 'tinggi', 'sedang', 'rendah')")
            ->get();

        $recentIncomes = Income::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $recentExpenses = Expense::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Data grafik 6 bulan terakhir
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData[] = [
                'month' => $month->translatedFormat('M Y'),
                'income' => Income::where('user_id', $userId)
                    ->whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->sum('amount'),
                'expense' => Expense::where('user_id', $userId)
                    ->whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->sum('amount'),
            ];
        }

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'activeSavings', 'savingsGoals', 'recentIncomes',
            'recentExpenses', 'chartData'
        ));
    }
}