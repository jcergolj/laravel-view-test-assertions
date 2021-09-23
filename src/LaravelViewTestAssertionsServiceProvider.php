<?php

namespace Jcergolj\LaravelViewTestAssertions;

use Illuminate\Testing\TestResponse;
use Illuminate\Support\ServiceProvider;
use Jcergolj\LaravelViewTestAssertions\ViewTestAssertions;

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
