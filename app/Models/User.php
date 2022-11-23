<?php

namespace App\Models;

use Orchid\Filters\Filterable;
use Orchid\Platform\Models\User as Authenticatable;
use Orchid\Screen\AsSource;

class User extends Authenticatable
{
    use AsSource, Filterable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
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
}
