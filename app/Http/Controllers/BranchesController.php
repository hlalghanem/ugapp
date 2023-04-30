<?php

namespace App\Http\Controllers;

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
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        

        $sort = $request->query('sort', 'last_sync');
        $branches = Branch::orderByDesc($sort)->get();

        return view('admin_views.branches.branch_show_all', ['branches' => $branches]);
    }

     public function create()
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        return view('admin_views.branches.branch_new');
    }

  
    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
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
        $branch->save();

        return redirect()->route('get_all_branches')->with('success', 'Branch created successfully!');
    }

    
    public function edit($id)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $branch =Branch::find($id);

        $users = User::select('users.id', 'users.name', 'users.email','branch_user.branch_id as branch_id')
        ->join('branch_user', 'users.id', '=', 'branch_user.user_id')
        ->where('branch_user.branch_id','=',$id)
        ->get();

        $users_not_assigned = User::select('users.id', 'users.name')
        ->whereNotIn('id',  $users->pluck('id'))
        ->orderBy('name')
        ->get();

        
       

        return view('admin_views.branches.branch_edit',['branch' => $branch,'users' => $users,'users_not_assigned' => $users_not_assigned]);
    }

    public function update(Request $request,$id)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sync_interval'=>'required|integer|min:3|max:30',
            // 'omega_id' => 'required|integer|digits:6|unique:branches',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $branch= Branch::find($id);
        $branch->name = $request->input('name');
        $branch->sync_interval = $request->input('sync_interval');
        $branch->is_active =$request->has('is_active') ? 1 : 0;
        $branch->send_payments =$request->has('send_payments') ? 1 : 0;
        $branch->send_sales_details =$request->has('send_sales_details') ? 1 : 0;
        $branch->save();
        
        return redirect()->route('get_all_branches')->with('success', 'Branch updated successfully!');


    }

}