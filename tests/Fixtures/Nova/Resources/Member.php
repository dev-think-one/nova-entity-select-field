<?php

namespace NovaEntitySelectField\Tests\Fixtures\Nova\Resources;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

/**
 * @extends Resource<\NovaEntitySelectField\Tests\Fixtures\Models\Member>
 */
class Member extends Resource
{

    public static $model = \NovaEntitySelectField\Tests\Fixtures\Models\Member::class;

    public static $title = 'name';

    public static $search = [
        'name',
        'email',
    ];

    public function subtitle()
    {
        return $this->resource?->email;
    }

    public function fields(NovaRequest $request)
    {
        return [
            Image::make('Image'),
        ];
    }
}
