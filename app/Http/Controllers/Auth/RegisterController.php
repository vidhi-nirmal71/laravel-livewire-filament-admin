<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{

    public function registrationFormForApp()
    {
        return view('auth.register');
    }

    public function registrationFormStore(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'min:2'],
            'last_name' => ['required', 'string', 'min:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'digits_between:10,15'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'first_name.required' => 'Please enter your first name.',
            'first_name.min' => 'First name must be at least 2 characters long.',
            'last_name.required' => 'Please enter your last name.',
            'last_name.min' => 'Last name must be at least 2 characters long.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.digits_between' => 'Please enter a valid phone number between 10 and 15 digits.',
            'password.required' => 'Please provide a password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null,
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Registration successful!');
    }
}