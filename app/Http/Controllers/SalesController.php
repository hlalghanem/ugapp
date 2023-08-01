<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\TempSale;
use App\Models\Sale;
use App\Models\VoidRefund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{

    public function deletetodaytranactions($omega_id)
    {
        if( auth()->check() && auth()->user()->is_admin == 1)
        {
            DB::table('transactions')->where('omega_id', '=', $omega_id)->delete();
            DB::table('temp_sales')->where('omega_id', '=', $omega_id)->delete();
            // return redirect('/users')->with('success', 'User deleted successfully.');
            return redirect()->route('showBranchSales', ['omega_id' => $omega_id])->with('success', 'transactions deleted successfully. Please refresh after 5 minuts to get the fresh data');

        }
    }
    public function live_sales()
    {
        // $user = Auth::user(); // get the authenticated user
        // $user_id = $user->id;
        // $activeBranches = DB::table('branches')
        //     ->where('is_active', 1)
        //     ->pluck('id');
        // $user_active_branches = DB::table('branch_user')
        //     ->where('user_id', $user_id)
        //     ->whereIn('branch_id', $activeBranches)
        //     ->where('is_active', 1)
        //     ->get();
        //     $branchIds = $user_active_branches->pluck('branch_id'); // get the IDs of the user's active branches
    //    $branchCount = $user_active_branches->count(); // get the count of the user's active branches
    $user = Auth::user(); // get the authenticated user
    $user_id = $user->id;
   
    $user_active_branches = DB::table('branch_user')
        ->where('user_id', $user_id)
        ->where('is_active', 1)
        ->pluck('branch_id');
       if ($user_active_branches->count() === 1) {
        $omega_id = DB::table('branches')
            ->select('omega_id')
            ->where('id', $user_active_branches->pluck('branch_id'))
            ->first();

        //dd($omega_id);
        return redirect()->route('showBranchSales', ['omega_id' => $omega_id->omega_id]);
    }
       
        // $customerIds = $branchIds;
        // $transactions = DB::table('branches AS A')
        //             ->leftJoin('transactions AS B', 'B.branch_id', '=', 'A.id')
        //             ->groupBy('A.name', 'A.last_eod','A.omega_id')
        //             ->select('A.name', 'A.last_eod','A.omega_id', DB::raw('COALESCE(SUM(B.amount_paid), 0) AS total_paid'))
        //             ->whereIn('A.id', $customerIds)
        //             // ->where('A.last_eod','=','B.eod_date')
        //             ->orderBy('A.last_eod', 'desc')
        //             ->orderBy('total_paid', 'desc')
        //             ->get();
      
        $transactions = Branch::select('branches.id', 'branches.name', 'branches.name_ar', 'branches.omega_id', 'branches.last_eod', 'branches.last_sync',  \DB::raw('COALESCE(SUM(transactions.amount_paid), 0) as total_paid'))
        ->leftJoin('transactions', 'branches.id', '=', 'transactions.branch_id')
        ->whereIn('branches.id', $user_active_branches)
        ->groupBy('branches.id', 'branches.name', 'branches.name_ar', 'branches.omega_id', 'branches.last_eod', 'branches.last_sync')
        ->orderByDesc('branches.last_eod')
        ->orderByDesc('total_paid')
        ->get();
    //   dd($transactions);
        
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

           $open_orders = TempSale::selectRaw('SUM(total) as open_orders')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', 0)
            ->first();
            $discount = TempSale::selectRaw('SUM(discount) as discount')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->first();
            $refund = TempSale::selectRaw('SUM(total) as refund')
            ->where('omega_id', '=', $omega_id)
            ->where('total', '<', 0)
            ->where('closed', '=', -1)
            ->first();
            
            $menu = TempSale::selectRaw('menu, SUM(total) as total_menu')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->groupBy('menu')
            
            ->orderBy('total_menu', 'desc')
            ->get();

            $employee = TempSale::selectRaw('employee, SUM(total) as total_employee')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->groupBy('employee')
            
            ->orderBy('total_employee', 'desc')
            ->get();

           

        return view('sales.branchSales', ['employee' => $employee,'menu' => $menu,'refund' => $refund,'discount' => $discount,'open_orders' => $open_orders,'branchinfo' => $branchinfo,'totals' => $totals, 'brTodayTotal' => $brTodayTotal, 'totalsbyDate' => $totalsbyDate , 'prevSales' => $prevSales]);

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

         $branchinfo =Branch::where('omega_id', '=', $omega_id)->first();
         

        $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('omega_id', '=', $omega_id)
            ->where('eod_date', '=', $eod_date)
            ->groupBy('payment_type')
            
            ->orderBy('total_amount', 'desc')
            ->get();


         
            $discount = Sale::selectRaw('SUM(discount) as discount')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->where('eoddate', '=', $eod_date)
            ->first();
            $refund = Sale::selectRaw('SUM(total) as refund')
            ->where('omega_id', '=', $omega_id)
            ->where('total', '<', 0)
            ->where('eoddate', '=', $eod_date)
            ->where('closed', '=', -1)
            ->first();
            $voids = VoidRefund::selectRaw('SUM(totalprice) as voids')
            ->where('omega_id', '=', $omega_id)
            ->where('totalprice', '<', 0)
            ->where('eoddate', '=', $eod_date)
            ->where('invoicenumber', '<', 20000000)
            ->first();
            
            $menu = Sale::selectRaw('menu, SUM(total) as total_menu')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->where('eoddate', '=', $eod_date)
            ->groupBy('menu')
            
            ->orderBy('total_menu', 'desc')
            ->get();

            $employee = Sale::selectRaw('employee, SUM(total) as total_employee')
            ->where('omega_id', '=', $omega_id)
            ->where('closed', '=', -1)
            ->where('eoddate', '=', $eod_date)
            ->groupBy('employee')
            
            ->orderBy('total_employee', 'desc')
            ->get();

           

        return view('sales.branch_sales_by_date', ['voids' => $voids,'employee' => $employee,'menu' => $menu,'refund' => $refund,'discount' => $discount,'branchinfo' => $branchinfo,'totals' => $totals, 'brTodayTotal' => $brTodayTotal]);




       
    }
}