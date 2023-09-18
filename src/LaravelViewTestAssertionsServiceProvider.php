<?php

namespace Jcergolj\LaravelViewTestAssertions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;

class LaravelViewTestAssertionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        TestResponse::mixin(new ViewTestAssertions());
    }

    public function register()
    {

    }
}
