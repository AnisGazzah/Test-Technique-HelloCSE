<?php

namespace Database\Seeders;

use App\Enums\ProfileStatusEnum;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Profile::create([
                'first_name' => 'Profile',
                'last_name' => 'Test ' . $i,
                'image' => 'profile.png',
                'status' => ProfileStatusEnum::all()[rand(0,1)],
            ]);
        }
    }
}
