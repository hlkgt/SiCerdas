<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
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
        $user = DB::table('profiles as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('u.id', auth()->user()->id)
            ->select('p.class_id')
            ->first();
        if ($user->class_id === null) {
            return redirect()->route('profile')->with(["message" => "Opps, complete your personal data first"]);
        }
        return Inertia::render('Dashboard/Absen', ["classLists" => $this->getClassLists()]);
    }

    public function profile()
    {
        return Inertia::render('User/User', ["classLists" => $this->getClassLists()]);
    }
}
