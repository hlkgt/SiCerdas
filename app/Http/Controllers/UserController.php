<?php

namespace App\Http\Controllers;

use App\Events\ApproveProcessed;
use App\Events\CheckTokenEvent;
use App\Events\UsageTokenEvent;
use App\Mail\ApproveMail;
use App\Mail\RejectedMail;
use App\Mail\ShareToken;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|min:5|max:15',
            'class' => 'required',
            'gender' => 'required',
            'date' => 'required',
        ]);

        $user_id = auth()->user()->id;

        if (auth()->user()->username !== $validate["username"]) {
            $user = User::find($user_id);
            $user->username = $validate["username"];
            $user->update();
        }

        DB::table('profiles')->where('user_id', $user_id)->update(
            [
                'class_id' => $validate["class"],
                'gender' => $validate["gender"],
                'date_birth' => $validate["date"],
            ]
        );

        return redirect()->back()->with(["success" => "Profile update successfully"]);
    }

    public function approveUser()
    {
        $principle = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('u.id', auth()->user()->id)
            ->select('p.school_id')
            ->first();

        $list_approve = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->join('head_schools as h', 'h.id', '=', 'p.school_id')
            ->where([['p.school_id', $principle->school_id], ['p.role_id', '<>', 2], ['u.approved', 0],])
            ->select('h.name', 'u.id', 'u.username', 'u.email')
            ->get();
        return Inertia::render('Dashboard/ApprovalList', ['listApproval' => $list_approve]);
    }

    public function handleApprove(Request $request)
    {
        $user = User::find($request->userId);
        if ($request->type === "approved") {
            event(new ApproveProcessed($request->userId, "approved"));
            $user->approved = true;
            $user->save();

            Mail::to($user)->send(new ApproveMail($user));
            return redirect()->back()->with(["success" => "Approved " . $user->username . " successfully"]);
        } else {
            event(new ApproveProcessed($request->userId, "rejected"));

            $user->delete();

            Mail::to($user)->send(new RejectedMail($user));
            return redirect()->back()->with(["message" => "Rejected user " . $user->username . " successfully, and user being deleted"]);
        }
    }

    public function generateToken()
    {
        Token::create([
            "token" => Str::random(12),
            "usage" => 0,
            "max_usage" => 15,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);

        return redirect()->back()->with(["message" => "generate token successfully"]);
    }

    public function shareToken(Request $request)
    {
        if ($request->email === null) {
            return redirect()->back()->withErrors(["error" => "email field is required"]);
        }
        Mail::to($request->email)->send(new ShareToken($request->token));
        return redirect()->back()->with(["message" => "share token to " . $request->email . " successfully"]);
    }

    public function checkToken(Request $request)
    {
        $find_token = DB::table('principle_tokens')->where('token', '=', $request->token)->first();
        if ($find_token === null || $find_token->usage === $find_token->max_usage) {
            event(new CheckTokenEvent("token invalid, follow up on your admin", false));
        } else {
            event(new CheckTokenEvent("token valid, please entry input bellow", true));
        }

        return back();
    }

    public function loginPrinciple(Request $request)
    {
        $validate = $request->validate([
            'gender' => 'required',
            'date' => 'required',
        ]);

        DB::table('users')
            ->where('id', auth()->user()->id)
            ->update([
                "approved" => true,
                "email_verified_at" => Carbon::now()
            ]);

        DB::table('profiles')
            ->where('user_id', auth()->user()->id)
            ->update([
                "gender" => $validate["gender"],
                "date_birth" => $validate["date"],
                "role_id" => 2
            ]);

        DB::table('principle_tokens')
            ->where('token', '=', $request->token)
            ->increment('usage');

        $token = DB::table('principle_tokens')
            ->where('token', '=', $request->token)->first();

        event(new UsageTokenEvent($token->token, $token->usage));

        if ($token->usage === $token->max_usage) {
            DB::table('principle_tokens')
                ->where('token', '=', $request->token)->update([
                    "expired_at" => Carbon::now()
                ]);
        }

        return redirect()->route('home');
    }
}
