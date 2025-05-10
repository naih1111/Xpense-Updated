<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'last_name' => ['required', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
            'terms' => ['required', 'accepted'],
        ], [
            'first_name.regex' => 'First name must only contain letters.',
            'last_name.regex' => 'Last name must only contain letters.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ]);

        try {
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'monthly_income' => 0, // Default value
                'email_verified_at' => now(), // Mark email as verified
            ]);

            event(new Registered($user));

            return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }
} 