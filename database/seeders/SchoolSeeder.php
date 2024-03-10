<?php

namespace Database\Seeders;

use App\Models\School;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                DB::table('user_classes')->insert([
                    'head_school_id' => $head_school->id,
                    'class' => $class_lists[$j]
                ]);
            }
        }
    }
}
