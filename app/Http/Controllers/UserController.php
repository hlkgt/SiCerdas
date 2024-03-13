<?php

namespace App\Http\Controllers;

use App\Mail\ApproveMail;
use App\Mail\RejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            $user->approved = true;
            $user->save();

            Mail::to($user)->send(new ApproveMail($user));

            return redirect()->back()->with(["success" => "Approved " . $user->username . " successfully"]);
        } else {
            $user->delete();
            Mail::to($user)->send(new RejectedMail($user));
            return redirect()->back()->with(["message" => "Rejected user " . $user->username . " successfully, and user being deleted"]);
        }
    }
}
