<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function get_today_sales()
    {
        $user = Auth::user(); // get the authenticated user
        $user_id = $user->id;
        $activeBranches = DB::table('branches')
            ->where('is_active', 1)
            ->pluck('id');
        $user_active_branches = DB::table('branch_user')
            ->where('user_id', $user_id)
            ->whereIn('branch_id', $activeBranches)
            ->where('is_active', 1)
            ->get();

        if ($user_active_branches->count() < 1) {
            abort(404, 'you do not have any active branch');
        } else if ($user_active_branches->count() === 1) {
            $omega_id = DB::table('branches')
                ->select('omega_id')
                ->where('id', $user_active_branches->pluck('branch_id'))
                ->first();

            //dd($omega_id);
            return redirect()->route('showBranchSales', ['omega_id' => $omega_id->omega_id]);
        }


        $branchIds = $user_active_branches->pluck('branch_id'); // get the IDs of the user's active branches
        $branchCount = $user_active_branches->count(); // get the count of the user's active branches
        $customerIds = $branchIds;


        $transactions = DB::table('transactions as a')
            ->select('cust_name', 'omega_id', 'eod_date', DB::raw('SUM(amount_paid)'))
            ->whereIn('branch_id', $customerIds)
            ->where('eod_date', function ($query) {
                $query->select(DB::raw('MAX(eod_date)'))
                    ->from('transactions')
                    ->whereRaw('transactions.cust_name = a.cust_name');
            })
            ->groupBy('cust_name', 'omega_id', 'eod_date')
            ->orderBy('SUM(amount_paid)', 'desc')
            ->get();
        // dd($transactions);
        return view('sales.sales', compact('transactions'));


    }

    public function showBranchSales($omega_id)
    {
        $user = Auth::user(); // get the authenticated user
        $user_id = $user->id;

        $getbranchmainID = DB::table('branches')
            ->where('omega_id', $omega_id)
            ->where('is_active', 1)
            ->pluck('id');
        if ($getbranchmainID->count() < 1) {
            abort(404, 'This branch is not active');
        }
        $user_active_branches = DB::table('branch_user')
            // ->select('id')
            ->where('user_id', $user_id)
            ->where('branch_id', $getbranchmainID)
            ->where('is_active', 1)
            ->get();

        // dd( $user_active_branches );

        if ($user_active_branches->count() < 1) {
            abort(404, 'you do not have any branch');
        }


        $maxDate = DB::table('transactions')
            ->where('omega_id', $omega_id)
            ->max('eod_date');

        $brTodayTotal = Transaction::selectRaw('cust_name,eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $maxDate)
            ->groupBy('cust_name', 'eod_date')
            ->first();

        $totals = Transaction::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $maxDate)
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        $totalsbyDate = Transaction::selectRaw('eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '<', $maxDate)
            ->groupBy('eod_date')
            ->orderBy('eod_date', 'desc')
            ->take(5)
            ->get();
        return view('sales.branchSales', ['totals' => $totals, 'brTodayTotal' => $brTodayTotal, 'totalsbyDate' => $totalsbyDate]);

    }

    public function showBranchSalesbyDate($omega_id,$eod_date)
    {
        $user = Auth::user(); // get the authenticated user
        $user_id = $user->id;

        $getbranchmainID = DB::table('branches')
            ->where('omega_id', $omega_id)
            ->where('is_active', 1)
            ->pluck('id');
        if ($getbranchmainID->count() < 1) {
            abort(404, 'This branch is not active');
        }
        $user_active_branches = DB::table('branch_user')
            // ->select('id')
            ->where('user_id', $user_id)
            ->where('branch_id', $getbranchmainID)
            ->where('is_active', 1)
            ->get();

        // dd( $user_active_branches );

        if ($user_active_branches->count() < 1) {
            abort(404, 'you do not have any branch');
        }


        $brTodayTotal = Transaction::selectRaw('cust_name,eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $eod_date)
            ->groupBy('cust_name', 'eod_date')
            ->first();

            // if (is_null($brTodayTotal)){
            //     abort(400, 'Invalid request. <a href="' . back()->getTargetUrl() . '">Go back</a>');

            // }



        $totals = Transaction::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $eod_date)
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        $totalsbyDate = Transaction::selectRaw('eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '<', $eod_date)
            ->groupBy('eod_date')
            ->orderBy('eod_date', 'desc')
            ->take(5)
            ->get();
        return view('sales.branch_sales_by_date', ['totals' => $totals, 'brTodayTotal' => $brTodayTotal, 'totalsbyDate' => $totalsbyDate, 'omega_id' => $omega_id ]);

    }
}