<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Branch_Users_Controller extends Controller
{
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
        return redirect()->route('branches.users')->with('success', 'Branch assighned to user successfully!');
    }
}
