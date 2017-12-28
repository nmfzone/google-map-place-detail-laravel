<?php

namespace NMFCODES\GoogleMapPlaceDetail\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use NMFCODES\GoogleMapPlaceDetail\GoogleMapPlaceDetailServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase,
        CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        app()->register(GoogleMapPlaceDetailServiceProvider::class);
    }
}
