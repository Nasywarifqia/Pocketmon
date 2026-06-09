<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index()
    {
        $this->initGuestData();

        $incomes  = collect(session('guest_incomes', []));
        $expenses = collect(session('guest_expenses', []));
        $brankas  = collect(session('guest_brankas', []));
        $wallets  = collect(session('guest_wallets', []));

        $totalIncome  = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance      = $wallets->sum('balance');
        $activeSavings = $brankas->where('status', 'belum_tercapai')->count();

        $recentIncomes  = $incomes->sortByDesc('date')->take(5);
        $recentExpenses = $expenses->sortByDesc('date')->take(5);
        $savingsGoals   = $brankas->take(4);

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $chartData[] = [
                'month'   => now()->subMonths($i)->format('M Y'),
                'income'  => $incomes->filter(fn($t) => substr($t['date'], 0, 7) == $month)->sum('amount'),
                'expense' => $expenses->filter(fn($t) => substr($t['date'], 0, 7) == $month)->sum('amount'),
            ];
        }

        return view('guest.dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'activeSavings', 'savingsGoals', 'recentIncomes',
            'recentExpenses', 'chartData', 'wallets'
        ));
    }

    private function initGuestData()
    {
        if (!session()->has('guest_initialized')) {
            session([
                'guest_initialized' => true,
                'guest_wallets' => [
                    ['id' => 1, 'name' => 'Cash', 'type' => 'cash', 'balance' => 500000],
                    ['id' => 2, 'name' => 'BCA', 'type' => 'bank', 'balance' => 5000000],
                    ['id' => 3, 'name' => 'Dana', 'type' => 'e_wallet', 'balance' => 250000],
                ],
                'guest_incomes' => [
                    ['id' => 1, 'date' => '2026-06-01', 'amount' => 3000000, 'category' => 'Gaji', 'description' => 'Gaji bulan Juni', 'wallet_id' => 2, 'wallet_name' => 'BCA'],
                    ['id' => 2, 'date' => '2026-06-03', 'amount' => 1000000, 'category' => 'Freelance', 'description' => 'Project website', 'wallet_id' => 1, 'wallet_name' => 'Cash'],
                    ['id' => 3, 'date' => '2026-06-05', 'amount' => 500000, 'category' => 'Bonus', 'description' => 'Bonus kinerja', 'wallet_id' => 2, 'wallet_name' => 'BCA'],
                ],
                'guest_expenses' => [
                    ['id' => 1, 'date' => '2026-06-06', 'amount' => 350000, 'category' => 'Makanan', 'description' => 'Makan siang', 'wallet_id' => 1, 'wallet_name' => 'Cash'],
                    ['id' => 2, 'date' => '2026-06-05', 'amount' => 200000, 'category' => 'Transportasi', 'description' => 'Bensin', 'wallet_id' => 1, 'wallet_name' => 'Cash'],
                    ['id' => 3, 'date' => '2026-06-04', 'amount' => 500000, 'category' => 'Belanja', 'description' => 'Belanja bulanan', 'wallet_id' => 3, 'wallet_name' => 'Dana'],
                ],
                'guest_brankas' => [
                    ['id' => 1, 'item_name' => 'Liburan ke Jepang', 'target_price' => 15000000, 'collected_amount' => 6000000, 'deadline' => '2026-12-31', 'priority' => 'tinggi', 'status' => 'belum_tercapai', 'description' => ''],
                    ['id' => 2, 'item_name' => 'Laptop Baru', 'target_price' => 12000000, 'collected_amount' => 4500000, 'deadline' => '2026-09-30', 'priority' => 'sedang', 'status' => 'belum_tercapai', 'description' => ''],
                    ['id' => 3, 'item_name' => 'Dana Darurat', 'target_price' => 10000000, 'collected_amount' => 8000000, 'deadline' => '2026-08-31', 'priority' => 'tinggi', 'status' => 'belum_tercapai', 'description' => ''],
                ],
            ]);
        }
    }

    // Income CRUD
    public function incomeIndex()
    {
        $incomes  = collect(session('guest_incomes', []))->sortByDesc('date')->values();
        $wallets  = collect(session('guest_wallets', []));
        $categories = ['Gaji', 'Bonus', 'Bisnis', 'Freelance', 'Hadiah', 'Lainnya'];
        return view('guest.incomes', compact('incomes', 'wallets', 'categories'));
    }

    public function incomeStore(Request $request)
    {
        $request->validate([
            'date'      => 'required|date',
            'amount'    => 'required|numeric|min:1',
            'category'  => 'required|string',
            'wallet_id' => 'required',
        ]);

        $incomes  = session('guest_incomes', []);
        $wallets  = session('guest_wallets', []);
        $walletId = (int)$request->wallet_id;

        $walletName = collect($wallets)->firstWhere('id', $walletId)['name'] ?? '-';

        $newId = count($incomes) > 0 ? max(array_column($incomes, 'id')) + 1 : 1;

        $incomes[] = [
            'id'          => $newId,
            'date'        => $request->date,
            'amount'      => (int)$request->amount,
            'category'    => $request->category,
            'description' => $request->description,
            'wallet_id'   => $walletId,
            'wallet_name' => $walletName,
        ];

        // Update wallet balance
        foreach ($wallets as &$wallet) {
            if ($wallet['id'] == $walletId) {
                $wallet['balance'] += (int)$request->amount;
                break;
            }
        }

        session(['guest_incomes' => $incomes, 'guest_wallets' => $wallets]);
        return redirect()->route('guest.incomes')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function incomeDestroy($id)
    {
        $incomes = session('guest_incomes', []);
        $wallets = session('guest_wallets', []);

        $income = collect($incomes)->firstWhere('id', (int)$id);
        if ($income) {
            foreach ($wallets as &$wallet) {
                if ($wallet['id'] == $income['wallet_id']) {
                    $wallet['balance'] -= $income['amount'];
                    break;
                }
            }
            $incomes = collect($incomes)->filter(fn($i) => $i['id'] != (int)$id)->values()->toArray();
        }

        session(['guest_incomes' => $incomes, 'guest_wallets' => $wallets]);
        return redirect()->route('guest.incomes')->with('success', 'Pemasukan berhasil dihapus!');
    }

    // Expense CRUD
    public function expenseIndex()
    {
        $expenses   = collect(session('guest_expenses', []))->sortByDesc('date')->values();
        $wallets    = collect(session('guest_wallets', []));
        $categories = ['Makanan', 'Transportasi', 'Pendidikan', 'Belanja', 'Hiburan', 'Kesehatan', 'Tagihan', 'Lainnya'];
        return view('guest.expenses', compact('expenses', 'wallets', 'categories'));
    }

    public function expenseStore(Request $request)
    {
        $request->validate([
            'date'      => 'required|date',
            'amount'    => 'required|numeric|min:1',
            'category'  => 'required|string',
            'wallet_id' => 'required',
        ]);

        $expenses = session('guest_expenses', []);
        $wallets  = session('guest_wallets', []);
        $walletId = (int)$request->wallet_id;

        $walletName = collect($wallets)->firstWhere('id', $walletId)['name'] ?? '-';
        $newId = count($expenses) > 0 ? max(array_column($expenses, 'id')) + 1 : 1;

        $expenses[] = [
            'id'          => $newId,
            'date'        => $request->date,
            'amount'      => (int)$request->amount,
            'category'    => $request->category,
            'description' => $request->description,
            'wallet_id'   => $walletId,
            'wallet_name' => $walletName,
        ];

        foreach ($wallets as &$wallet) {
            if ($wallet['id'] == $walletId) {
                $wallet['balance'] -= (int)$request->amount;
                break;
            }
        }

        session(['guest_expenses' => $expenses, 'guest_wallets' => $wallets]);
        return redirect()->route('guest.expenses')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function expenseDestroy($id)
    {
        $expenses = session('guest_expenses', []);
        $wallets  = session('guest_wallets', []);

        $expense = collect($expenses)->firstWhere('id', (int)$id);
        if ($expense) {
            foreach ($wallets as &$wallet) {
                if ($wallet['id'] == $expense['wallet_id']) {
                    $wallet['balance'] += $expense['amount'];
                    break;
                }
            }
            $expenses = collect($expenses)->filter(fn($e) => $e['id'] != (int)$id)->values()->toArray();
        }

        session(['guest_expenses' => $expenses, 'guest_wallets' => $wallets]);
        return redirect()->route('guest.expenses')->with('success', 'Pengeluaran berhasil dihapus!');
    }

    // Brankas CRUD
    public function brankasIndex()
    {
        $brankas = collect(session('guest_brankas', []));
        return view('guest.brankas', compact('brankas'));
    }

    public function brankasStore(Request $request)
    {
        $request->validate([
            'item_name'    => 'required|string',
            'target_price' => 'required|numeric|min:1',
            'priority'     => 'required|in:tinggi,sedang,rendah',
        ]);

        $brankas   = session('guest_brankas', []);
        $collected = (int)($request->collected_amount ?? 0);
        $newId     = count($brankas) > 0 ? max(array_column($brankas, 'id')) + 1 : 1;

        $brankas[] = [
            'id'               => $newId,
            'item_name'        => $request->item_name,
            'target_price'     => (int)$request->target_price,
            'collected_amount' => $collected,
            'deadline'         => $request->deadline,
            'priority'         => $request->priority,
            'description'      => $request->description,
            'status'           => $collected >= (int)$request->target_price ? 'tercapai' : 'belum_tercapai',
        ];

        session(['guest_brankas' => $brankas]);
        return redirect()->route('guest.brankas')->with('success', 'Brankas berhasil ditambahkan!');
    }

    public function brankasDestroy($id)
    {
        $brankas = collect(session('guest_brankas', []))
            ->filter(fn($b) => $b['id'] != (int)$id)
            ->values()->toArray();

        session(['guest_brankas' => $brankas]);
        return redirect()->route('guest.brankas')->with('success', 'Brankas berhasil dihapus!');
    }

    public function reset()
    {
        session()->forget(['guest_initialized', 'guest_wallets', 'guest_incomes', 'guest_expenses', 'guest_brankas']);
        return redirect()->route('guest.dashboard')->with('success', 'Data berhasil direset!');
    }
}