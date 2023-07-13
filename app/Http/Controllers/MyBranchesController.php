<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyBranchesController extends Controller
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
}
