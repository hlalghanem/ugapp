<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{

    public function deletetodaytranactions($omega_id)
    {
        if( auth()->check() && auth()->user()->is_admin == 1)
        {
            DB::table('transactions')->where('omega_id', '=', $omega_id)->delete();
            // return redirect('/users')->with('success', 'User deleted successfully.');
            return redirect()->route('showBranchSales', ['omega_id' => $omega_id])->with('success', 'transactions deleted successfully. Please refresh after 5 minuts to get the fresh data');

        }
    }
    public function live_sales()
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
            $branchIds = $user_active_branches->pluck('branch_id'); // get the IDs of the user's active branches
        // $branchCount = $user_active_branches->count(); // get the count of the user's active branches
        $customerIds = $branchIds;
        $transactions = DB::table('branches AS A')
                    ->leftJoin('transactions AS B', 'B.branch_id', '=', 'A.id')
                    ->groupBy('A.name', 'A.last_eod','A.omega_id')
                    ->select('A.name', 'A.last_eod','A.omega_id', DB::raw('COALESCE(SUM(B.amount_paid), 0) AS total_paid'))
                    ->whereIn('A.id', $customerIds)
                    // ->where('A.last_eod','=','B.eod_date')
                    ->orderBy('A.last_eod', 'desc')
                    ->orderBy('total_paid', 'desc')
                    ->get();
        
        return view('sales.livesales', ['transactions' => $transactions]);
    }
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
            ->orderBy('eod_date', 'desc')
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


        // $maxDate = DB::table('transactions')
        //     ->where('omega_id', $omega_id)
        //     ->max('eod_date');
        //     if  (is_null($maxDate)) {
                // abort(404, 'no transaction for selected branch #19202');
                $last_eod = DB::table('branches')
                ->select('last_eod')
                ->where('omega_id', '=', $omega_id)
                ->first();
                $maxDate=$last_eod->last_eod;
            // }
    //    dd($maxDate);

        $brTodayTotal = Transaction::selectRaw('cust_name,eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $maxDate)
            ->groupBy('cust_name', 'eod_date')
            ->first();

            $branchinfo =Branch::where('omega_id', '=', $omega_id)->first();
         

        $totals = Transaction::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $maxDate)
            ->groupBy('payment_type')
            
            ->orderBy('total_amount', 'desc')
            ->get();

        $totalsbyDate = Payment::selectRaw('eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            // ->where('eod_date', '<', $maxDate)
            ->groupBy('eod_date')
            ->orderBy('eod_date', 'desc')
            ->take(5)
            ->get();

            $prevSales = Payment::selectRaw('eod_date')
            ->where('omega_id', '=', $omega_id)
            
            ->groupBy('eod_date')
            ->orderBy('eod_date', 'desc')
            ->skip(5)->take(30)->get();

           // dd( $prevSales );
           

        return view('sales.branchSales', ['branchinfo' => $branchinfo,'totals' => $totals, 'brTodayTotal' => $brTodayTotal, 'totalsbyDate' => $totalsbyDate , 'prevSales' => $prevSales]);

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


        $brTodayTotal = Payment::selectRaw('cust_name,eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $eod_date)
            ->groupBy('cust_name', 'eod_date')
            ->first();

            // if (is_null($brTodayTotal)){
            //     abort(400, 'Invalid request. <a href="' . back()->getTargetUrl() . '">Go back</a>');

            // }



        $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $eod_date)
            ->groupBy('payment_type')
           
            ->orderBy('total_amount', 'desc')
            ->get();

        $totalsbyDate = Payment::selectRaw('eod_date, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '<', $eod_date)
            ->groupBy('eod_date')
            ->orderBy('eod_date', 'desc')
            ->take(5)
            ->get();
        return view('sales.branch_sales_by_date', ['totals' => $totals, 'brTodayTotal' => $brTodayTotal, 'totalsbyDate' => $totalsbyDate, 'omega_id' => $omega_id ]);

    }
}