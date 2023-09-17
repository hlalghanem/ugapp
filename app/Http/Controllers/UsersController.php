<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    private function isAdmin()
    {
        return auth()->check() && auth()->user()->group_id == 2;
    }
    public function showUsers()
    {
        
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $users = User::orderBy('last_login', 'desc')->get();
        return view('admin_views.users', ['users' => $users])->with('success','Record added Successfully');
    }
   
    public function resetpassword(Request $request,$id)
    {
        $newpassword=123456;
        $user= User::find($id);
        $user->password =Hash::make($newpassword);
        $user->save();
        session()->flash('success', 'Reset Password is done');

    // Redirect back to the previous page
    return redirect()->back();
    }
    public function userUpdate(Request $request,$id)
    {
        $currentTime = Carbon::now()->format('H:i:s');
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $user= User::find($id);
        $user->name =$request->input('name');
        $user->company =$request->input('company');
        $user->group_id =$request->input('group');
        $user->lang =$request->input('lang');
        $user->is_active =$request->has('is_active')? 1 : 0;
        $user->is_admin =$request->has('is_admin')? 1 : 0;
        $user->save();

        

        return redirect()->route('user.edit',$id)->with('success','User Successfuly Updated on ' .$currentTime);

    }
    public function userPage($id)
    {
        
        if (!$this->isAdmin()) {
            // User is not authenticated or is not Admin
            return redirect()->route('logout');
        }
        $user= User::find($id);

        $userBranches = Branch::select('branches.id','branches.name','branches.name_ar','branch_user.user_id as userid')
        ->join('branch_user','branches.id','=', 'branch_user.branch_id')
        ->where( 'branch_user.user_id','=',$id)
        ->get();
       


        return view('admin_views.userPage', ['user' => $user,'userBranches' => $userBranches]);
    }
    public function setLanguageAr()
    {
        $user = Auth::user();
        $user->lang = 'ar'; // Replace 'fr' with the desired language value
        $user->save();
        return redirect()->back()->with('success', 'تم تغير اللغة الى العربية');
    }
    public function setLanguageEn()
    {
        $user = Auth::user();
        $user->lang = 'en'; // Replace 'fr' with the desired language value
        $user->save();
       
        return redirect()->back()->with('success', 'The language has been changed to English');
    }
}
