<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            // Generate a random 6-digit code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store the code in the database with expiration
            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'code' => Hash::make($code),
                    'created_at' => now(),
                    'expires_at' => now()->addMinutes(30)
                ]
            );

            // Send email with the code
            Mail::send('emails.reset-code', ['code' => $code], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset Code');
            });

            Log::info('Reset code sent successfully to: ' . $request->email);
            return back()->with('status', 'Reset code has been sent to your email.')
                ->with('code_active', true)
                ->with('email', $request->email);
        } catch (\Exception $e) {
            Log::error('Password reset code sending failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset code. Please try again.']);
        }
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        try {
            $reset = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();

            if (!$reset) {
                Log::warning('Reset code not found for email: ' . $request->email);
                return back()->withErrors(['code' => 'Invalid reset code.'])
                    ->with('code_active', true)
                    ->with('email', $request->email);
            }

            if (now()->isAfter($reset->expires_at)) {
                Log::warning('Reset code expired for email: ' . $request->email);
                return back()->withErrors(['code' => 'Reset code has expired. Please request a new one.'])
                    ->with('code_active', true)
                    ->with('email', $request->email);
            }

            if (!Hash::check($request->code, $reset->code)) {
                Log::warning('Invalid reset code for email: ' . $request->email);
                return back()->withErrors(['code' => 'Invalid reset code.'])
                    ->with('code_active', true)
                    ->with('email', $request->email);
            }

            Log::info('Code verified successfully for email: ' . $request->email);
            return back()->with('reset_active', true)
                ->with('email', $request->email)
                ->with('code', $request->code);
        } catch (\Exception $e) {
            Log::error('Code verification failed: ' . $e->getMessage());
            return back()->withErrors(['code' => 'Failed to verify code. Please try again.'])
                ->with('code_active', true)
                ->with('email', $request->email);
        }
    }

    public function resetPassword(Request $request)
    {
        Log::info('Reset password attempt for email: ' . $request->email);

        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            $reset = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();

            if (!$reset) {
                Log::warning('Reset record not found for email: ' . $request->email);
                return back()->withErrors(['password' => 'Invalid reset code.'])
                    ->with('reset_active', true)
                    ->with('email', $request->email)
                    ->with('code', $request->code);
            }

            if (!Hash::check($request->code, $reset->code)) {
                Log::warning('Invalid reset code for email: ' . $request->email);
                return back()->withErrors(['password' => 'Invalid reset code.'])
                    ->with('reset_active', true)
                    ->with('email', $request->email)
                    ->with('code', $request->code);
            }

            if (now()->isAfter($reset->expires_at)) {
                Log::warning('Reset code expired for email: ' . $request->email);
                return back()->withErrors(['password' => 'Reset code has expired. Please request a new one.'])
                    ->with('reset_active', true)
                    ->with('email', $request->email)
                    ->with('code', $request->code);
            }

            // Update the user's password
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                Log::error('User not found for email: ' . $request->email);
                return back()->withErrors(['password' => 'User not found.'])
                    ->with('reset_active', true)
                    ->with('email', $request->email)
                    ->with('code', $request->code);
            }

            // Hash the password manually to ensure it's hashed correctly
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the used reset code
            DB::table('password_resets')->where('email', $request->email)->delete();

            // Clear any existing session data
            session()->forget(['reset_active', 'code_active', 'email', 'code']);

            Log::info('Password reset successful for email: ' . $request->email);
            return back()
                ->with('status', 'Your password has been reset successfully.')
                ->with('success_active', true);
        } catch (\Exception $e) {
            Log::error('Password reset failed: ' . $e->getMessage());
            return back()->withErrors(['password' => 'Failed to reset password. Please try again.'])
                ->with('reset_active', true)
                ->with('email', $request->email)
                ->with('code', $request->code);
        }
    }
}
