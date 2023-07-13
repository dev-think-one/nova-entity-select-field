<?php

namespace NovaEntitySelectField\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NovaEntitySelectField\Tests\Fixtures\Factories\TeamFactory;

class Team extends Model
{
    use HasFactory;

    protected static function newFactory(): TeamFactory
    {
        return TeamFactory::new();
    }
}
