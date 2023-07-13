<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class Branch_Users_Controller extends Controller
{
    public function showMyAllBranches(){

        $userid=auth()->user()->id;
        $userBranches = Branch::select('branches.id','branches.name','branches.name_ar','branch_user.user_id as userid','branch_user.is_active as is_active')
        ->join('branch_user','branches.id','=', 'branch_user.branch_id')
        ->where( 'branch_user.user_id','=',$userid)
        ->orderby('id','desc')
        ->get();
    
        return view('user_views.myBranches',['userBranches'=>$userBranches]);
    
     }
     public function updateMyActiveBranches($branchid,$value){
        $userid=auth()->user()->id; // get the authenticated user id
        //check if this user own this branch
        $user_branches = DB::table('branch_user')
                ->where('user_id', $userid)
                ->where('branch_id', $branchid)
                ->get();
            if ($user_branches->count() < 1) {
                abort(404, 'you do not own this branch');
            }
    
            DB::table('branch_user')
            ->where('user_id', $userid)
            ->where('branch_id', $branchid)
            ->update(['is_active' => $value]);
    
            return redirect()->route('showMyAllBranches')->with('success', 'updated!');
    
        //update set active if value=1
    
    
    
     }
    private function isAdmin()
    {
        return auth()->check() && auth()->user()->group_id == 2;
    }
    public function get_all_branches_users(Request $request)
    {
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $users =User::get();
        $branches = Branch::get();

        $branches_users =  User::select('users.id', 'users.name', 'users.email', 'branches.id as branch_id', 'branches.omega_id as branch_omega_id', 'branches.name as branch_name')
        ->join('branch_user', 'users.id', '=', 'branch_user.user_id')
        ->join('branches', 'branch_user.branch_id', '=', 'branches.id')
        ->orderBy('branches.id')
        ->get();
        return view('admin_views.branches.branches_users', ['branches_users' => $branches_users,'users' => $users ,'branches' => $branches ]);
    }
    public function assign_branch_User(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('logout');
        }
       $checkif = DB::table('branch_user')
       ->where('user_id','=', $request->user_id)
       ->where('branch_id','=',$request->branch_id)
       ->get();
       if (count($checkif) > 0)
       {
        return redirect()->back()->withErrors('Branch already assigned to this user!');
       }
        $branch =Branch::find($request->branch_id);
        $user_id= $request->user_id;
        $branch->users()->attach( $user_id);
      
        return back()->with('success', 'Branch assigned to user successfully!');

    //    return redirect()->route('branches.users')->with('success', 'Branch assigned to user successfully!');
    }

    public function delete_branch_User($user_id,$branch_id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('logout');
        }
        $branch =Branch::find($branch_id);
        $branch->users()->detach( $user_id);

        return back()->with('success', 'Branch unassigned successfully!');

        // return redirect()->route('branches.users')->with('success', 'Branch unassigned successfully!');
    }
}
