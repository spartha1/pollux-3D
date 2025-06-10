<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\ThreeDModel;
use App\Policies\ThreeDModelPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...existing code...
        ThreeDModel::class => ThreeDModelPolicy::class,
    ];

    // ...existing code...
}
