<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected function getClassUser()
    {
        return $user = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('u.id', auth()->user()->id)
            ->select('p.class_id')
            ->first();
    }

    protected function getClassLists()
    {
        $id = auth()->user()->id;
        $get_profile = DB::table('profiles')->where('user_id', $id)->first();
        $class_lists = DB::table('user_classes')->where('head_school_id', $get_profile->school_id)->get();
        return $class_lists;
    }

    public function home()
    {
        return Inertia::render('Dashboard/Home');
    }

    public function absen()
    {
        $user = $this->getClassUser();
        if ($user->class_id === null) {
            return redirect()->route('profile')->with(["message" => "Opps, complete your personal data first"]);
        }
        return Inertia::render('Dashboard/Absen', ["classLists" => $this->getClassLists()]);
    }

    public function profile()
    {
        return Inertia::render('User/User', ["classLists" => $this->getClassLists()]);
    }

    public function chat()
    {
        $user = $this->getClassUser();
        if ($user->class_id === null) {
            return redirect()->route('profile')->with(["message" => "Opps, complete your personal data first"]);
        }

        $user = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('u.id', auth()->user()->id)
            ->select('p.school_id', 'u.id')
            ->first();

        $friend_lists = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->join('user_classes as c', 'p.class_id', '=', 'c.id')
            ->where([['p.school_id', $user->school_id], ['p.user_id', '<>', $user->id]])
            ->select('p.*', 'u.username', 'c.class')
            ->get();
        return Inertia::render('Chat/Chat', ["friendLists" => $friend_lists]);
    }
}
