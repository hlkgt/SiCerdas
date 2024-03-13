<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_list = ['john', 'doe', 'joy', 'anna', 'tarz', 'zien', 'zann', 'eliz', 'zell', 'suki', 'suna', 'elixia'];
        $class_lists = ['RPL', 'TKJ', 'MULTIMEDIA', 'MATEMATIKA', 'IPA', 'IPS'];
        for ($i = 0; $i < 50; $i++) {
            $head_school = School::create([
                'name' => 'SMK ' . rand(1, 5) . ' ' . Str::random(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $school_principle = User::create([
                'username' => $user_list[rand(0, count($user_list) - 1)] . $i,
                'email' => $user_list[rand(0, count($user_list) - 1)] . $i . '@yopmail.com',
                'password' => Hash::make('111111111'),
                'email_verified_at' => '2024-03-11 08:33:33',
                'approved' => 1,
            ]);
            Profile::create([
                'user_id' => $school_principle->id,
                'school_id' => $i + 1,
                'class_id' => null,
                'role_id' => 2,
                'gender' => 'laki-laki',
                'date_birth' => '2024-03-10'
            ]);
            for ($j = 0; $j < rand(1, 6); $j++) {
                DB::table('user_classes')->insert([
                    'head_school_id' => $head_school->id,
                    'class' => $class_lists[$j]
                ]);
            }
        }
    }
}
