<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public function setLanguageAr()
    {
        $user = Auth::user();
        $user->lang = 'ar'; // Replace 'fr' with the desired language value
        $user->save();
        return redirect()->route('live_sales')->with('success', 'تم تغير اللغة الى العربية');
    }
    public function setLanguageEn()
    {
        $user = Auth::user();
        $user->lang = 'en'; // Replace 'fr' with the desired language value
        $user->save();
        return redirect()->route('live_sales')->with('success', 'The language has been changed to English');
    }
}
