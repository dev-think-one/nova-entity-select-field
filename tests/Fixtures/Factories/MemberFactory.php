<?php

namespace NovaEntitySelectField\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NovaEntitySelectField\Tests\Fixtures\Models\Member;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{

    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'name'  => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->email(),
        ];
    }

    public function image(string $image): static
    {
        return $this->state([
            'image' => $image,
        ]);
    }
}
