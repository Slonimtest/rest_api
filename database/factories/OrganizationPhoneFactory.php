<?php

namespace Database\Factories;

use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationPhone>
 */
class OrganizationPhoneFactory extends Factory
{
    protected $model = OrganizationPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => null,
            'phone'           => $this->faker->phoneNumber(),
        ];
    }

    /**
     * Allows you to set organization_id on the fly
     */
    public function forOrganization(int $organizationId): self
    {
        return $this->state(fn(array $attrs) => [
            'organization_id' => $organizationId,
        ]);
    }
}
