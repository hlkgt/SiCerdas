<?php

namespace App\Http\Controllers;

use App\Events\RegisterEvent;
use App\Events\SendApproval;
use App\Models\Profile;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    public function isLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:16',
        ]);

        if (Auth::attempt(['email' => $credentials["email"], 'password' => $credentials["password"]], $request->remember_me)) {
            $request->session()->regenerate();

            if (auth()->user()->approved) {
                return redirect()->intended('/');
            } else {
                return redirect()->route('approval');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid to login',
        ])->onlyInput('email');
    }

    public function register()
    {
        $head_schools = School::all();
        return Inertia::render('Auth/Register', ["headSchools" => $head_schools]);
    }

    public function createUser(Request $request)
    {
        if ($request->password !== $request->confirm_password) {
            return redirect()->back()->withErrors(["message" => "Oops! Password doesn't match"]);
        }

        $validateRegister = $request->validate([
            'headschool' => 'required',
            'username' => 'required|unique:users|min:5|max:15',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:16',
            'confirm_password' => 'required'
        ]);

        $user = User::create([
            "username" => $validateRegister["username"],
            "email" => $validateRegister["email"],
            "password" => $validateRegister["password"],
            "approved" => false
        ]);

        Profile::create([
            "user_id" => $user->id,
            "school_id" => $validateRegister["headschool"],
            "role_id" => 3
        ]);

        event(new SendApproval($user));
        event(new RegisterEvent($user->email));

        if (Auth::attempt(['email' => $validateRegister["email"], 'password' => $validateRegister["password"]])) {
            $request->session()->regenerate();
        }
        return redirect()->route('approval');
    }

    public function forgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token)
    {
        return Inertia::render('Auth/ResetPassword', ["token" => $token, "email" => request()->query('email')]);
    }

    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/auth/login')->with('message', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function unApprove()
    {
        $user = Auth::user();
        if ($user->approved === 1) {
            return redirect('/');
        }
        return Inertia::render('Auth/ApproveView', [
            "user" => $user
        ]);
    }

    public function unVerified()
    {
        $user = Auth::user();
        if ($user->approved !== 1) {
            return redirect()->route('approval');
        }
        if ($user->email_verified_at !== null) {
            return redirect('/');
        }
        return Inertia::render('Auth/VerificationNotice', [
            "user" => $user
        ]);
    }

    public function isVerified(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/');
    }

    public function resendLink(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return redirect()->back()->with('message', 'Verification link sent!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
