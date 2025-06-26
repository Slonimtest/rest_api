<?php

namespace Database\Seeders;

use App\Models\Activitie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $root = Activitie::factory()
            ->count(5)
            ->create(['parent_id' => null]);

        $root->each(function (Activitie $root) {
            $children = Activitie::factory()
                ->count(rand(2, 4))
                ->create(['parent_id' => $root->id]);

            $children->each(function (Activitie $child) {
                Activitie::factory()
                    ->count(rand(1, 3))
                    ->create(['parent_id' => $child->id]);
            });
        });
    }
}
