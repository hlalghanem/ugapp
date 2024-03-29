<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Events\NewUserRegistered;
use App\Notifications\NewUserRegisteredNotification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'integer', 'digits:8'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(4)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        // event(new NewUserRegistered($user));

        // $user->notify(new NewUserRegisteredNotification($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Your branches will be activated soon, our team will contact you soon to confirm it.سيتم تفعيل فروعك قريبًا وسيتصل بك فريقنا قريبًا لتأكيد ذلك. ');
    }
}
