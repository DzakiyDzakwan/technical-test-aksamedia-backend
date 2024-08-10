<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Str;
use App\Traits\FileTrait;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    use FileTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $divisions = Division::all();
        $avatars = ["A1.png", "A2.png", "A3.png"];

        for ($i = 0; $i < 20; $i++) {
            $uuid = Str::uuid();

            $employee = new Employee();

            $employee->uuid = $uuid;
            $employee->name = $faker->name();
            $employee->phone = "08" . $faker->unique()->numerify('##########');
            $employee->division_id = $divisions->random(1)->first()->uuid;
            $employee->position = "staff";
            $employee->image = $this->saveDummyImage($avatars[rand(0, 2)], $uuid);
            $employee->save();
        }
    }
}
