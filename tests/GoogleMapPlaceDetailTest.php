<?php

namespace NMFCODES\GoogleMapPlaceDetail\Tests;

use NMFCODES\GoogleMapPlaceDetail\GoogleMapPlaceDetailFacade as GoogleMapPlaceDetail;

class GoogleMapPlaceDetailTest extends TestCase
{
    protected $placeDetails;

    public function setUp()
    {
        parent::setUp();

        $this->placeDetails = GoogleMapPlaceDetail::getDetails('ChIJY67epzRYei4R5AnGbv3UNXQ');
    }

    public function test_get_name()
    {
        $this->assertEquals('Jogja Digital Valley', $this->placeDetails->getName());
    }

    public function test_get_country()
    {
        $this->assertEquals('Indonesia', $this->placeDetails->getCountry());
    }
}
