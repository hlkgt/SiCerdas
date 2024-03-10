<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
