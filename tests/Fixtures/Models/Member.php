<?php

namespace NovaEntitySelectField\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NovaEntitySelectField\Tests\Fixtures\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory;

    protected static function newFactory(): MemberFactory
    {
        return MemberFactory::new();
    }
}
