<?php

namespace NovaEntitySelectField\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NovaEntitySelectField\Tests\Fixtures\Models\Member;
use NovaEntitySelectField\Tests\Fixtures\Models\Team;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{

    protected $model = Team::class;

    public function definition(): array
    {
        return [
        ];
    }

    public function member(Member|int $member): static
    {
        return $this->state([
            'member' => $member instanceof Member ? $member->getKey() : $member,
        ]);
    }
}
