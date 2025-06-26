<?php

namespace Database\Seeders;

use App\Models\Activitie;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::factory()
            ->count(50)
            ->create()
            ->each(function ($organization) {
                OrganizationPhone::factory()
                    ->count(rand(1, 3))
                    ->forOrganization($organization->id)
                    ->create();

                $activities = Activitie::inRandomOrder()
                    ->take(rand(1, 3))
                    ->pluck('id')
                    ->toArray();
                $organization->activities()->sync($activities);
            });
    }
}
