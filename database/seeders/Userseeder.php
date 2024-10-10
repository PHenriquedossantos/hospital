<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add user
        $user = new User();
        $user->name = 'AppHospital';
        $user->email = 'AppHosital_001@api.com';
        $user->password = bcrypt('12345678');
        $user->save();
    }
}
