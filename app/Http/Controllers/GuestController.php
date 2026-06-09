<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        // Data dummy untuk mode tamu
        $totalIncome  = 4500000;
        $totalExpense = 1850000;
        $balance      = 8750000;
        $activeSavings = 3;

        $recentIncomes = collect([
            (object)['category' => 'Gaji', 'amount' => 3000000, 'date' => '2026-06-01'],
            (object)['category' => 'Freelance', 'amount' => 1000000, 'date' => '2026-06-03'],
            (object)['category' => 'Bonus', 'amount' => 500000, 'date' => '2026-06-05'],
        ]);

        $recentExpenses = collect([
            (object)['category' => 'Makanan', 'amount' => 350000, 'date' => '2026-06-06'],
            (object)['category' => 'Transportasi', 'amount' => 200000, 'date' => '2026-06-05'],
            (object)['category' => 'Belanja', 'amount' => 500000, 'date' => '2026-06-04'],
        ]);

        $savingsGoals = collect([
            (object)[
                'item_name'        => 'Liburan ke Jepang',
                'target_price'     => 15000000,
                'collected_amount' => 6000000,
            ],
            (object)[
                'item_name'        => 'Laptop Baru',
                'target_price'     => 12000000,
                'collected_amount' => 4500000,
            ],
            (object)[
                'item_name'        => 'Dana Darurat',
                'target_price'     => 10000000,
                'collected_amount' => 8000000,
            ],
        ]);

        $chartData = [
            ['month' => 'Jan 2026', 'income' => 3500000, 'expense' => 1200000],
            ['month' => 'Feb 2026', 'income' => 3800000, 'expense' => 1500000],
            ['month' => 'Mar 2026', 'income' => 4000000, 'expense' => 1800000],
            ['month' => 'Apr 2026', 'income' => 3200000, 'expense' => 2100000],
            ['month' => 'May 2026', 'income' => 4500000, 'expense' => 1600000],
            ['month' => 'Jun 2026', 'income' => 4500000, 'expense' => 1850000],
        ];

        return view('guest.dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'activeSavings', 'savingsGoals', 'recentIncomes',
            'recentExpenses', 'chartData'
        ));
    }
}