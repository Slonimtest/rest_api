<?php

namespace Database\Factories;

use App\Models\Activitie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ActivitieFactory extends Factory
{
    protected $model = Activitie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->generateActivityName(),
            'parent_id' => $this->faker->boolean(30)
                ? Activitie::inRandomOrder()->value('id')
                : null
        ];
    }

    protected function generateActivityName(): string
    {
        return Str::slug($this->faker->word) . '-' . Str::random(4);
    }
}
