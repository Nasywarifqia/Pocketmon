<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Wallet;
use App\Models\Brankas;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $totalIncome  = Income::where('user_id', $userId)->sum('amount');
        $totalExpense = Expense::where('user_id', $userId)->sum('amount');
        $balance      = Wallet::where('user_id', $userId)->sum('balance');
        $activeSavings = Brankas::where('user_id', $userId)->where('status', 'belum_tercapai')->count();

        $recentIncomes = Income::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $recentExpenses = Expense::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData[] = [
                'month'   => $month->format('M Y'),
                'income'  => Income::where('user_id', $userId)->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount'),
                'expense' => Expense::where('user_id', $userId)->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount'),
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'total_income'   => $totalIncome,
                'total_expense'  => $totalExpense,
                'balance'        => $balance,
                'active_savings' => $activeSavings,
                'recent_incomes' => $recentIncomes,
                'recent_expenses'=> $recentExpenses,
                'chart_data'     => $chartData,
            ]
        ]);
    }
}