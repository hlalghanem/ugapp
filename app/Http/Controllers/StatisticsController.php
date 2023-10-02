<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\TempSale;
use App\Models\Sale;
use App\Models\VoidRefund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function statistics(){
        $paymentsPerBranch=DB::table('payments')
        ->select('cust_name', DB::raw('count(*) as count'))
        ->groupBy('cust_name')
        ->orderBy('count','desc')
        ->get();

        $sales=DB::table('sales')
        ->select('cust_name', DB::raw('count(*) as count'))
        ->groupBy('cust_name')
        ->orderBy('count','desc')
        ->get();
        $voidrefund=DB::table('void_refunds')
        ->select('cust_name', DB::raw('count(*) as count'))
        ->groupBy('cust_name')
        ->orderBy('count','desc')
        ->get();
        $todaypayemnts=DB::table('transactions')
        ->select('cust_name', DB::raw('count(*) as count'))
        ->groupBy('cust_name')
        ->orderBy('count','desc')
        ->get();
        $todaysales=DB::table('temp_sales')
        ->select('cust_name', DB::raw('count(*) as count'))
        ->groupBy('cust_name')
        ->orderBy('count','desc')
        ->get();

        return view('admin_views.statistics', [
            'paymentsPerBranch' => $paymentsPerBranch,
            'sales' => $sales,
            'voidrefund' => $voidrefund,
            'todaypayemnts' => $todaypayemnts,
            'todaysales' => $todaysales,
        ]);

    }
}
