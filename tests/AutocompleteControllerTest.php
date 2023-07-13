<?php

namespace NovaEntitySelectField\Tests;

use Illuminate\Support\Facades\Storage;
use NovaEntitySelectField\Tests\Fixtures\Nova\Resources\Member;

class AutocompleteControllerTest extends TestCase
{
    /** @test */
    public function empty_response_list()
    {
        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
        ]));

        $response->assertJsonStructure([
            'resources',
            'withTrashed',
        ]);

        $this->assertIsArray($response->json('resources'));
        $this->assertCount(0, $response->json('resources'));
        $this->assertFalse($response->json('withTrashed'));
    }

    /** @test */
    public function filled_response_list()
    {
        \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->count(30)->create();


        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'limit'             => 6,
        ]));

        $response->assertJsonStructure([
            'resources' => [
                [
                    'value',
                    'display',
                    'avatar',
                    'subtitle',
                ],
            ],
            'withTrashed',
        ]);

        $this->assertCount(6, $response->json('resources'));
    }

    /** @test */
    public function search_response_list()
    {
        $member = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'foo',
            'email' => 'foo@test.com',
        ]);
        $member2 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'test',
            'email' => 'test@test.com',
        ]);
        $member3 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()
            ->image('test-foo.png')
            ->create([
                'name'  => 'qux',
                'email' => 'bar@foo.com',
            ]);

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'foo',
        ]));
        $this->assertCount(2, $response->json('resources'));

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'bar',
        ]));
        $this->assertCount(1, $response->json('resources'));

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'qux',
        ]));
        $this->assertCount(1, $response->json('resources'));
        $this->assertEquals($member3->name, $response->json('resources.0.display'));
        $this->assertEquals($member3->getKey(), $response->json('resources.0.value'));
        $this->assertEquals($member3->email, $response->json('resources.0.subtitle'));
        $this->assertEquals(Storage::url($member3->image), $response->json('resources.0.avatar'));
    }

    /** @test */
    public function prepend_current_response_list()
    {
        $member = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'foo',
            'email' => 'foo@test.com',
        ]);
        $member2 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'test',
            'email' => 'test@test.com',
        ]);
        $member3 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'qux',
            'email' => 'bar@foo.com',
        ]);

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'qux',
            'current'           => $member->getKey(),
        ]));
        $this->assertCount(2, $response->json('resources'));
        $this->assertEquals($member->name, $response->json('resources.0.display'));
        $this->assertEquals($member->getKey(), $response->json('resources.0.value'));
        $this->assertEquals($member3->name, $response->json('resources.1.display'));
        $this->assertEquals($member3->getKey(), $response->json('resources.1.value'));
    }

    /** @test */
    public function prepend_current_as_array_response_list()
    {
        $member = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'foo',
            'email' => 'foo@test.com',
        ]);
        $member2 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'test',
            'email' => 'test@test.com',
        ]);
        $member3 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'qux',
            'email' => 'bar@foo.com',
        ]);

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'qux',
            'current'           => [
                $member->getKey(),
                $member2->getKey(),
            ],
        ]));
        $this->assertCount(3, $response->json('resources'));
        $this->assertEquals($member2->name, $response->json('resources.0.display'));
        $this->assertEquals($member2->getKey(), $response->json('resources.0.value'));
        $this->assertEquals($member->name, $response->json('resources.1.display'));
        $this->assertEquals($member->getKey(), $response->json('resources.1.value'));
        $this->assertEquals($member3->name, $response->json('resources.2.display'));
        $this->assertEquals($member3->getKey(), $response->json('resources.2.value'));
    }

    /** @test */
    public function prepend_current_without_duplication_response_list()
    {
        $member = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'foo',
            'email' => 'foo@test.com',
        ]);
        $member2 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'test',
            'email' => 'test@test.com',
        ]);
        $member3 = \NovaEntitySelectField\Tests\Fixtures\Models\Member::factory()->create([
            'name'  => 'qux',
            'email' => 'bar@foo.com',
        ]);

        $response = $this->getJson(route('nova-entity-select-field.autocomplete', [
            'entityResourceKey' => Member::uriKey(),
            'search'            => 'qux',
            'current'           => $member3->getKey(),
        ]));
        $this->assertCount(1, $response->json('resources'));
        $this->assertEquals($member3->name, $response->json('resources.0.display'));
        $this->assertEquals($member3->getKey(), $response->json('resources.0.value'));
    }
}
