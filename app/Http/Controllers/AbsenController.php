<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function createAbsen(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'class' => 'required',
            'present' => 'required',
            'information' => 'required',
        ]);

        Absen::create($validate);

        return redirect()->back()->with(["success" => "Create absen successfully"]);
    }
}
