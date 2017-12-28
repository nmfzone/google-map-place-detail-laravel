<?php

namespace NMFCODES\GoogleMapPlaceDetail;

use Illuminate\Support\Facades\Facade;

class GoogleMapPlaceDetailFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GoogleMapPlaceDetail::class;
    }
}
