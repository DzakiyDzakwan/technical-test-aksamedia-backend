<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $user = User::where('username', 'admin')->first();

        if (!$user) {
            $user = new User();
        }

        $user->name = "Admin";
        $user->username = "admin";
        $user->phone = "08" . $faker->unique()->numerify('##########');
        $user->password = Hash::make("pastibisa");
        $user->email = $faker->email();

        $user->save();
    }
}
