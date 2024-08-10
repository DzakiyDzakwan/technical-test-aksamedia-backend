<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            "Mobile Apps",
            "QA",
            "Full Stack",
            "Backend",
            "Frontend",
            "UI/UX Designer"
        ];

        foreach ($divisions as $data) {
            $division = Division::where('name', $data)->first();

            if (!$division) {
                $division = new Division();
            }

            $division->name = $data;
            $division->save();
        }
    }
}
