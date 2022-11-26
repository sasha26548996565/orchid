<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    use AsSource, Filterable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'phone',
        'assessment',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
        'email_verified_at',
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
        'email_verified_at',
    ];

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn($phone) => phone_normalized($phone),
        );
    }
}
