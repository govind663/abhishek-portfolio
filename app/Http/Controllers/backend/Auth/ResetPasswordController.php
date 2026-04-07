<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Services\UserHistoryService;

class ResetPasswordController extends Controller
{
    protected $historyService;

    // Dependency Injection
    public function __construct(UserHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function showResetForm($token)
    {
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$tokenData) {
            return redirect()->route('admin.login')->with('error', 'Invalid token');
        } else {
            return view('backend.auth.passwords.reset', ['token' => $token]);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([

            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],

            'password_confirmation' => [
                'required',
            ],

        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.exists' => 'Email does not exist',

            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',

            'password_confirmation.required' => 'Password confirmation is required',
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        // Update password
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete token
        DB::table('password_reset_tokens')
            ->where(['email' => $request->email])
            ->delete();

        // Get user
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Store history log
            $this->historyService->store($user->id, 'password_reset');
        }

        return redirect()->route('admin.login')
            ->with('message', 'Your password has been changed successfully. You can now log in with your new password.');
    }
}