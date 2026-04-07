<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Services\UserHistoryService;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    protected $historyService;

    // Dependency Injection
    public function __construct(UserHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function showLinkRequestForm()
    {
        return view('backend.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email,deleted_at,NULL',
                'unique:password_reset_tokens,email',
            ],
        ],[
            'email.required' => 'Email Id is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email does not exist.',
            'email.unique' => 'This email has already requested password reset.',
        ]);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Send mail
        Mail::send('backend.auth.verify',['token' => $token], function($message) use ($request) {
            $message->from('codingthunder1997@gmail.com','Abhishek Jha');
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        // Get user id from email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Store activity log
            $this->historyService->store($user->id, 'password_reset_request');
        }

        return back()->with('message', 'Password reset link has been sent to your email.');
    }
}