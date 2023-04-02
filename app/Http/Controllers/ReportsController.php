<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
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
            $totals = Transaction::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->whereIn('branch_id', $user_active_branches)
            ->whereBetween('eod_date', [$start_date, $end_date])
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        }
        else
        {
            $totals = Transaction::selectRaw('payment_type, SUM(amount_paid) as total_amount')
            ->where('branch_id', '=', $branch)
            ->whereBetween('eod_date', [$start_date, $end_date])
            ->groupBy('payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();
        }

    return view('sales.sales_report_by_date', ['branches' => $branches,'totals' => $totals, 'start_date' => $start_date ,'end_date' => $end_date , 'branch' => $branch]);
   }
}
