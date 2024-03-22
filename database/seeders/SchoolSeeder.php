<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\School;
use App\Models\User;
use App\Models\UserClass;
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
        $class_lists = ['RPL', 'TKJ', 'MULTIMEDIA', 'MATEMATIKA', 'IPA', 'IPS'];
        for ($i = 0; $i < 50; $i++) {
            $head_school = School::create([
                'name' => 'SMK ' . rand(1, 5) . ' ' . Str::random(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            for ($j = 0; $j < rand(1, 6); $j++) {
                $class = UserClass::create([
                    'head_school_id' => $head_school->id,
                    'class' => $class_lists[$j]
                ]);
            }
        }
        $admin = User::create([
            'username' => "adminleo",
            'email' => 'adminleo@yopmail.com',
            'password' => Hash::make('111111111'),
            'email_verified_at' => '2024-03-11 08:33:33',
            'approved' => 1,
        ]);
        Profile::create([
            'user_id' => $admin->id,
            'school_id' => null,
            'class_id' => null,
            'role_id' => 1,
            'gender' => 'laki-laki',
            'date_birth' => '2024-03-10'
        ]);

        $principle = User::create([
            'username' => "principleleo",
            'email' => 'principleleo@yopmail.com',
            'password' => Hash::make('111111111'),
            'email_verified_at' => '2024-03-11 08:33:33',
            'approved' => 1,
        ]);
        Profile::create([
            'user_id' => $principle->id,
            'school_id' => $head_school->id,
            'class_id' => NULL,
            'role_id' => 2,
            'gender' => 'laki-laki',
            'date_birth' => '2024-03-10'
        ]);

        $user1 = User::create([
            'username' => "userleo",
            'email' => 'userleo@yopmail.com',
            'password' => Hash::make('111111111'),
            'email_verified_at' => '2024-03-11 08:33:33',
            'approved' => 1,
        ]);
        Profile::create([
            'user_id' => $user1->id,
            'school_id' => $head_school->id,
            'class_id' => $class->id,
            'role_id' => 3,
            'gender' => 'laki-laki',
            'date_birth' => '2024-03-10'
        ]);

        $user2 = User::create([
            'username' => "userleo1",
            'email' => 'userleo1@yopmail.com',
            'password' => Hash::make('111111111'),
            'email_verified_at' => '2024-03-11 08:33:33',
            'approved' => 1,
        ]);
        Profile::create([
            'user_id' => $user2->id,
            'school_id' => $head_school->id,
            'class_id' => $class->id,
            'role_id' => 3,
            'gender' => 'laki-laki',
            'date_birth' => '2024-03-10'
        ]);
    }
}
