<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
