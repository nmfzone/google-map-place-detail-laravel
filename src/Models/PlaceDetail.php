<?php

namespace NMFCODES\GoogleMapPlaceDetail\Models;

class PlaceDetail
{
    /**
     * The place details collection.
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param  mixed  $data
     */
    public function __construct($data)
    {
        $this->data = (array) $data;
    }

    /**
     * Get place id.
     *
     * @return mixed
     */
    public function getPlaceId()
    {
        return array_get($this->data, 'result.place_id');
    }

    /**
     * Get place name.
     *
     * @return mixed
     */
    public function getName()
    {
        return array_get($this->data, 'result.name');
    }

    /**
     * Get phone number.
     *
     * @return mixed
     */
    public function getPhone()
    {
        return array_get($this->data, 'result.formatted_phone_number');
    }

    /**
     * Get international phone number.
     *
     * @return mixed
     */
    public function getInternationalPhone()
    {
        return array_get($this->data, 'result.international_phone_number');
    }

    /**
     * Get website.
     *
     * @return mixed
     */
    public function getWebsite()
    {
        return array_get($this->data, 'result.website');
    }

    /**
     * Get place rating.
     *
     * @return float
     */
    public function getRating()
    {
        return (float) array_get($this->data, 'result.rating', 0);
    }

    /**
     * Get place url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return array_get($this->data, 'result.url');
    }

    /**
     * Get opening hours.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOpeningHours()
    {
        return collect(array_get($this->data, 'result.opening_hours'));
    }

    /**
     * Get place latitude.
     *
     * @return mixed
     */
    public function getLatitude()
    {
        return array_get($this->getGeometry(), 'location.lat');
    }

    /**
     * Get place longitude.
     *
     * @return mixed
     */
    public function getLongitude()
    {
        return array_get($this->getGeometry(), 'location.lng');
    }

    /**
     * Get place geometry.
     *
     * @return mixed
     */
    public function getGeometry()
    {
        return array_get($this->data, 'result.geometry');
    }

    /**
     * Get formatted address.
     *
     * @return mixed
     */
    public function getFormattedAddress()
    {
        return array_get($this->data, 'result.formatted_address');
    }

    /**
     * Get province.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getProvince($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'administrative_area_level_1')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get district.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getDistrict($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'administrative_area_level_2')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get sub district.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getSubDistrict($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'administrative_area_level_3')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get village.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getVillage($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'administrative_area_level_4')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get street name.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getStreetName($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'route')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get street number.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getStreetNumber($short = false)
    {
        $result = $this->getAdministrativeArea()
            ->where('types.0', 'street_number')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get country.
     *
     * @param  bool  $short
     * @return mixed
     */
    public function getCountry($short = false)
    {
        $result = $this->getAddressComponents()
            ->where('types.0', 'country')->first();

        return $this->longShortChoice($result, $short);
    }

    /**
     * Get postal code.
     *
     * @return mixed
     */
    public function getPostalCode()
    {
        $result = $this->getAddressComponents()
            ->where('types.0', 'postal_code')->first();

        return $this->longShortChoice($result, false);
    }

    /**
     * Get administrative area.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAdministrativeArea()
    {
        $result = $this->getAddressComponents()
            ->whereIn('types.0', [
                'administrative_area_level_1',
                'administrative_area_level_2',
                'administrative_area_level_3',
                'administrative_area_level_4',
            ])->values();

        return $result;
    }

    /**
     * Get address components.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAddressComponents()
    {
        return collect(array_get($this->data, 'result.address_components'))->values();
    }

    /**
     * Get data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return collect($this->data);
    }

    /**
     * Determine has data.
     *
     * @return bool
     */
    public function hasData()
    {
        return empty($this->data);
    }

    /**
     * Long / short choice.
     *
     * @param  mixed  $data
     * @param  bool  $short
     * @return mixed
     */
    private function longShortChoice($data, $short = false)
    {
        return array_get($data, $short ? 'short_name' : 'long_name');
    }
}
