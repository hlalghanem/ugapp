<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class BranchesController extends Controller
{

    private function isAdmin()
    {
        return auth()->check() && auth()->user()->group_id == 2;
    }

    public function get_all_branches(Request $request)
    {
        if (auth()->check() && (auth()->user()->group_id == 2 || auth()->user()->group_id == 3)) {


            $sort = $request->query('sort', 'last_eod');
            $branches = Branch::orderByDesc($sort)->get();

            return view('admin_views.branches.branch_show_all', ['branches' => $branches]);
        } else {
            return redirect()->route('logout');
        }


    }

    public function create()
    {
        if (auth()->check() && (auth()->user()->group_id == 2 || auth()->user()->group_id == 3)) {
            return view('admin_views.branches.branch_new');
        } else {
            return redirect()->route('logout');
        }
      
    }


    public function store(Request $request)
    {
        if (auth()->check() && (auth()->user()->group_id == 2 || auth()->user()->group_id == 3)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:branches',
                'omega_id' => 'required|integer|digits:6|unique:branches',
    
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
    
    
            $branch = new Branch();
            $branch->name = $request->input('name');
            $branch->omega_id = $request->input('omega_id');
            $branch->created_by =auth()->user()->name;
            $branch->save();
    
            return redirect()->route('get_all_branches')->with('success', 'Branch created successfully!');

        } else {
            return redirect()->route('logout');
        }
      

       
    }
    
    public function deletepayments($id, Request $request)
    {
        
       $startDate= $request->input('start_date');
       $endDate= $request->input('end_date');
       $deletedRowsPayments = Payment::where('branch_id', $id)
       ->whereBetween('eod_date', [$startDate, $endDate])
       ->delete();

       $deletedRowsSales = Sale::where('branch_id', $id)
       ->whereBetween('eoddate', [$startDate, $endDate])
       ->delete();


    //    return redirect()->back()->with('message','Record added Successfully');
      return redirect()->back()->with('success', $deletedRowsPayments .' Payments and ' . $deletedRowsSales .' Sales Details records deleted successfully.');
        
           
       
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $branch = Branch::find($id);

        $users = User::select('users.id', 'users.name', 'users.email', 'branch_user.branch_id as branch_id')
            ->join('branch_user', 'users.id', '=', 'branch_user.user_id')
            ->where('branch_user.branch_id', '=', $id)
            ->get();

        $users_not_assigned = User::select('users.id', 'users.name')
            ->whereNotIn('id', $users->pluck('id'))
            ->orderBy('name')
            ->get();




        return view('admin_views.branches.branch_edit', ['branch' => $branch, 'users' => $users, 'users_not_assigned' => $users_not_assigned]);
    }

    public function update(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sync_interval' => 'required|integer|min:3|max:30',
            // 'omega_id' => 'required|integer|digits:6|unique:branches',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $branch = Branch::find($id);
        $branch->name = $request->input('name');
        $branch->sync_interval = $request->input('sync_interval');
        $branch->is_active = $request->has('is_active') ? 1 : 0;
        $branch->send_payments = $request->has('send_payments') ? 1 : 0;
        $branch->send_sales_details = $request->has('send_sales_details') ? 1 : 0;
        $branch->save();

        return redirect()->route('get_all_branches')->with('success', 'Branch updated successfully!');


    }

}