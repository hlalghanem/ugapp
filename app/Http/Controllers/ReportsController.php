<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\VoidRefund;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function completereport(Request $request){
        $user = Auth::user(); // get the authenticated user
        $user_id = $user->id;
       
        $user_active_branches = DB::table('branch_user')
            ->where('user_id', $user_id)
            ->where('is_active', 1)
            ->pluck('branch_id');
    
            $branches = DB::table('branches')
            ->whereIn('id', $user_active_branches)
            ->where('is_active', 1)
            ->get();
    
           $start_date= $request->input('start_date');
           $end_date= $request->input('end_date');
           $branch= $request->input('branch');
            if($branch=="all")
            {
                $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
                ->whereIn('branch_id', $user_active_branches)
                ->whereBetween('eod_date', [$start_date, $end_date])
                ->groupBy('payment_type')
                ->orderBy('total_amount', 'desc')
                ->get();
                $discount = Sale::selectRaw('SUM(discount) as discount')
                ->whereIn('branch_id', $user_active_branches)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->first();
                  $refund = Sale::selectRaw('SUM(total) as refund')
                  ->whereIn('branch_id', $user_active_branches)
                  ->where('total', '<', 0)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->where('closed', '=', -1)
                  ->first();
                //   $voids = VoidRefund::selectRaw('SUM(totalprice) as voids')
                //   ->whereIn('branch_id', $user_active_branches)
                //   ->where('totalprice', '<', 0)
                //   ->whereBetween('eoddate', [$start_date, $end_date])
                // //   ->where('invoicenumber', '<', 20000000)
                //   ->first();
                
                $refund_beforeDisc = Sale::selectRaw('SUM(amount) as refund')
                ->whereIn('branch_id', $user_active_branches)
                ->where('total', '<', 0)
                ->whereBetween('eoddate', [$start_date, $end_date])
                ->where('closed', '=', -1)
                ->first();
                $voidswithRefund = VoidRefund::selectRaw('SUM(totalprice) as voids')
                ->whereIn('branch_id', $user_active_branches)
                ->where('totalprice', '<', 0)
                ->whereBetween('eoddate', [$start_date, $end_date])
              //   ->where('invoicenumber', '<', 20000000)
                ->first();
                $refundAmount = $refund_beforeDisc->refund; // Get refund amount or default to 0 if null
                $voidsAmount = $voidswithRefund->voids;       // Get voids amount or default to 0 if null
                
                $voids = $voidsAmount - $refundAmount;
                
                  $menu = Sale::selectRaw('menu, SUM(total) as total_menu')
                  ->whereIn('branch_id', $user_active_branches)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->groupBy('menu')
                  
                  ->orderBy('total_menu', 'desc')
                  ->get();
      
                  $employee = Sale::selectRaw('employee, SUM(total) as total_employee')
                  ->whereIn('branch_id', $user_active_branches)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->groupBy('employee')
                  
                  ->orderBy('total_employee', 'desc')
                  ->get();
            }
            else
            {
                $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
                ->where('branch_id', '=', $branch)
                ->whereBetween('eod_date', [$start_date, $end_date])
                ->groupBy('payment_type')
                ->orderBy('total_amount', 'desc')
                ->get();
                $discount = Sale::selectRaw('SUM(discount) as discount')
                ->where('branch_id', '=', $branch)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->first();
                  $refund = Sale::selectRaw('SUM(total) as refund')
                  ->where('branch_id', '=', $branch)
                  ->where('total', '<', 0)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->where('closed', '=', -1)
                  ->first();

                  $refund_beforeDisc = Sale::selectRaw('SUM(amount) as refund')
                  ->where('branch_id', '=', $branch)
                  ->where('total', '<', 0)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->where('closed', '=', -1)
                  ->first();
                  $voidswithRefund = VoidRefund::selectRaw('SUM(totalprice) as voids')
                  ->where('branch_id', '=', $branch)
                  ->where('totalprice', '<', 0)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                //   ->where('invoicenumber', '<', 20000000)
                  ->first();
                  $refundAmount = $refund_beforeDisc->refund; // Get refund amount or default to 0 if null
                  $voidsAmount = $voidswithRefund->voids;       // Get voids amount or default to 0 if null
                  
                  $voids = $voidsAmount - $refundAmount;
              // dd( $voidsAmount);
                  
                  $menu = Sale::selectRaw('menu, SUM(total) as total_menu')
                  ->where('branch_id', '=', $branch)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->groupBy('menu')
                  
                  ->orderBy('total_menu', 'desc')
                  ->get();
      
                  $employee = Sale::selectRaw('employee, SUM(total) as total_employee')
                  ->where('branch_id', '=', $branch)
                  ->where('closed', '=', -1)
                  ->whereBetween('eoddate', [$start_date, $end_date])
                  ->groupBy('employee')
                  
                  ->orderBy('total_employee', 'desc')
                  ->get();


            }
    
        return view('sales.complete_report', ['voids' => $voids,
        'employee' => $employee,
        'menu' => $menu,
        'refund' => $refund,
        'discount' => $discount,
        'branches' => $branches,
        'totals' => $totals,
        'start_date' => $start_date ,'end_date' => $end_date ,
         'branch' => $branch]);
       }

       public function sales_summary_by_day_report(Request $request){
        $user = Auth::user(); // get the authenticated user
        $user_id = $user->id;
       
        $user_active_branches = DB::table('branch_user')
            ->where('user_id', $user_id)
            ->where('is_active', 1)
            ->pluck('branch_id');
    
            $branches = DB::table('branches')
            ->whereIn('id', $user_active_branches)
            ->where('is_active', 1)
            ->get();
    
           $start_date= $request->input('start_date');
           $end_date= $request->input('end_date');
           $branch= $request->input('branch');
            if($branch=="all")
            {
                $totals = Payment::selectRaw('eod_date, SUM(amount_paid) as total_amount')
                ->whereIn('branch_id', $user_active_branches)
                ->whereBetween('eod_date', [$start_date, $end_date])
                ->groupBy('eod_date')
                ->orderBy('eod_date')
                ->get();
    
            }
            else
            {
                $totals = Payment::selectRaw('eod_date, SUM(amount_paid) as total_amount')
                ->where('branch_id', '=', $branch)
                ->whereBetween('eod_date', [$start_date, $end_date])
                ->groupBy('eod_date')
                ->orderBy('eod_date')
                ->get();
            }
    
        return view('sales.sales_summary_report_by_date', ['branches' => $branches,'totals' => $totals, 'start_date' => $start_date ,'end_date' => $end_date , 'branch' => $branch]);
       }
   public function sales_report_by_date(Request $request){
    $user = Auth::user(); // get the authenticated user
    $user_id = $user->id;
   
    $user_active_branches = DB::table('branch_user')
        ->where('user_id', $user_id)
        ->where('is_active', 1)
        ->pluck('branch_id');

        $branches = DB::table('branches')
        ->whereIn('id', $user_active_branches)
        ->where('is_active', 1)
        ->get();

       $start_date= $request->input('start_date');
       $end_date= $request->input('end_date');
       $branch= $request->input('branch');
        if($branch=="all")
        {
            $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->whereIn('branch_id', $user_active_branches)
            ->whereBetween('eod_date', [$start_date, $end_date])
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        }
        else
        {
            $totals = Payment::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('branch_id', '=', $branch)
            ->whereBetween('eod_date', [$start_date, $end_date])
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();
        }

    return view('sales.sales_report_by_date', ['branches' => $branches,'totals' => $totals, 'start_date' => $start_date ,'end_date' => $end_date , 'branch' => $branch]);
   }
   public function sales_report_summary(Request $request){
    $user = Auth::user(); // get the authenticated user
    $user_id = $user->id;
   
    $user_active_branches = DB::table('branch_user')
        ->where('user_id', $user_id)
        ->where('is_active', 1)
        ->pluck('branch_id');

        $branches = DB::table('branches')
        ->whereIn('id', $user_active_branches)
        ->where('is_active', 1)
        ->get();

       $start_date= $request->input('start_date');
       $end_date= $request->input('end_date');
      
            // $totals = Payment::selectRaw('cust_name, SUM(amount_paid) as total_amount')
            // ->whereIn('branch_id', $user_active_branches)
            // ->whereBetween('eod_date', [$start_date, $end_date])
            // ->groupBy('cust_name')
            // ->orderBy('total_amount', 'desc')
            // ->get();

            $totals = Branch::select('branches.id', 'branches.name', 'branches.name_ar', \DB::raw('SUM(payments.amount_paid) as total_amount'))
              ->leftJoin('payments', 'branches.id', '=', 'payments.branch_id')
              ->whereIn('branches.id', $user_active_branches)
              ->whereBetween('payments.eod_date', [$start_date, $end_date])
              ->groupBy('branches.id', 'branches.name', 'branches.name_ar')
              ->orderByDesc('total_amount')
              ->get();

        

    return view('sales.sales_report_summary', ['totals' => $totals, 'start_date' => $start_date ,'end_date' => $end_date]);
   }

}
