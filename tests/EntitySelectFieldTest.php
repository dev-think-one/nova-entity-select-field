<?php

namespace NovaEntitySelectField\Tests;

use NovaEntitySelectField\EntitySelect;
use NovaEntitySelectField\Tests\Fixtures\Models\Team;
use NovaEntitySelectField\Tests\Fixtures\Nova\Resources\Member;

class EntitySelectFieldTest extends TestCase
{

    /** @test */
    public function component_returns_correct_value()
    {
        $this->assertEquals('entity-select-field', EntitySelect::make(Member::class)->component());
    }

    /** @test */
    public function field_has_default_values()
    {
        $field = EntitySelect::make(Member::class);
        $data  = $field->jsonSerialize();

        $this->assertEquals(Member::uriKey(), $data['entityResourceKey']);
        $this->assertEquals(Member::softDeletes(), $data['softDeletes']);
        $this->assertFalse($data['withSubtitles']);
        $this->assertEquals(500, $data['debounce']);
        $this->assertEquals(10, $data['limit']);
        $this->assertTrue($data['viewable']);
        $this->assertNull($data['display']);
    }

    /** @test */
    public function override_default_values()
    {

        \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->count(rand(2, 5))->create();
        $member = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create();
        \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->count(rand(2, 5))->create();

        $team = Team::factory()->member($member)->create();


        $field = EntitySelect::make(Member::class)
        ->limit(7)
        ->debounce(8)
        ->withSubtitles()
        ->viewable(false);

        $field->resolveForDisplay($team, 'member');

        $data = $field->jsonSerialize();

        $this->assertEquals(Member::uriKey(), $data['entityResourceKey']);
        $this->assertEquals(Member::softDeletes(), $data['softDeletes']);
        $this->assertTrue($data['withSubtitles']);
        $this->assertEquals(8, $data['debounce']);
        $this->assertEquals(7, $data['limit']);
        $this->assertFalse($data['viewable']);
        $this->assertEquals($member->name, $data['display']);
    }

}
