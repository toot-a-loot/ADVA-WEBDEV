<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ForgotPasswordController extends Controller
{

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }


        $code = random_int(100000, 999999);


        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(30),
                'used' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


        Mail::raw("Your password reset code is: $code", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Password Reset Code');
        });


        return redirect()->route('login')
            ->with('email', $request->email)
            ->with('code_active', true);
    }


    public function showCodeForm(Request $request)
    {
        return view('auth.enter_code', ['email' => session('email')]);
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            // If code is invalid or expired, redirect back with error and keep code entry active
            return redirect()->route('login')
                ->with('email', $request->email)
                ->with('code_active', true)
                ->withErrors(['code' => 'The verification code is invalid or has expired. Please try again.']);
        }

        // If code is valid, redirect to login with reset_active
        return redirect()->route('login')
            ->with('email', $request->email)
            ->with('reset_active', true);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid or expired reset request.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')
            ->where('email', $request->email)
            ->update(['used' => true]);

        return redirect()->route('login')->with('status', 'Password has been reset!');
    }
}
