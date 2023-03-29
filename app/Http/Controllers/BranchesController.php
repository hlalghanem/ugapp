<?php

namespace App\Http\Controllers;

use App\Models\Branch;
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
        return view('admin_views.branches.branch_new');
    }

  
    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|min:3',
        //     'omega_id' => 'required|integer|digits:6',
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

    
    public function edit(Branch $branch)
    {
        //
    }

    public function update(Request $request, Branch $branch)
    {
        //
    }

}