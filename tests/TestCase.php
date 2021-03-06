<?php

namespace Devio\Permalink\Tests;

use Arcanedev\SeoHelper\SeoHelperServiceProvider;
use Devio\Permalink\Middleware\BuildSeo;
use Devio\Permalink\PermalinkServiceProvider;
use Devio\Permalink\Routing\Router;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

//        $this->withMiddleware(BuildSeo::class);
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations('testing');
        $this->withFactories(__DIR__ . '/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            SeoHelperServiceProvider::class,
            PermalinkServiceProvider::class,
            \Cviebrock\EloquentSluggable\ServiceProvider::class
        ];
    }

    protected function reloadRoutes()
    {
        $this->app['router']->loadPermalinks();
    }
}