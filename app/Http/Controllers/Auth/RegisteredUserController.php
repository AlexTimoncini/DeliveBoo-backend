<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'vat_number' => ['required', 'string', 'max:11', 'unique:' . User::class],
            'address' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'closer_time' => ['required', 'date_format:H:i'],
            'open_time' => ['required', 'date_format:H:i'],
            'image' => ['required', 'image'],
            'logo' => ['required', 'image'],
            'phone' => ['required', 'numeric', 'digit:10'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'vat_number' => $request->vat_number,
            'address' => $request->address,
            'closer_time' => $request->closer_time,
            'open_time' => $request->open_time,
            'image' => $request->image,
            'logo' => $request->logo,
            'phone' => $request->phone,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
