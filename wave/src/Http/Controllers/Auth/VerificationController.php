<?php

namespace Wave\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Wave\Notifications\VerifyEmail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function notice()
    {
        // If no pending verification email in session, redirect to login
        if (!session('pending_verification_email')) {
            return redirect()->route('login');
        }

        return view('theme::auth.verify');
    }

    public function resend(Request $request)
    {
        // Get email from session, not from request
        $email = session('pending_verification_email');

        if (!$email) {
            return redirect()->route('login');
        }

        $user = User::where('email', $email)
                    ->where('verified', 0)
                    ->first();

        if (!$user) {
            return redirect()->route('login');
        }

        // Generate new verification code if needed
        if (empty($user->verification_code)) {
            $user->verification_code = Str::random(30);
            $user->save();
        }

        // Resend verification email
        $user->notify(new VerifyEmail($user));

        return back()->with([
            'message' => 'Verification email has been resent.',
            'message_type' => 'success'
        ]);
    }
}
