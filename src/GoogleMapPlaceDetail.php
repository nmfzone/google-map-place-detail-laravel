<?php

namespace NMFCODES\GoogleMapPlaceDetail;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use NMFCODES\GoogleMapPlaceDetail\Models\PlaceDetail;

class GoogleMapPlaceDetail
{
    /**
     * Get place details.
     *
     * @param  string  $placeId
     * @return \NMFCODES\GoogleMapPlaceDetail\Models\PlaceDetail
     */
    public function getDetails($placeId)
    {
        $language = config('google-map.place_details.lang', config('app.locale'));
        $cacheKey = "place-details-{$placeId}-{$language}";

        $result = app('cache')->get($cacheKey);

        if (is_null($result)) {
            try {
                $query = [
                    'place_id' => $placeId,
                    'language' => $language,
                    'key' => config('google-map.api_key'),
                ];

                $http = new Client();

                $response = $http->get(config('google-map.place_details.url') . '/json', [
                    'query' => $query,
                ]);

                $result = json_decode($response->getBody(), true);

                if ($result['status'] !== 'OK') {
                    $result = null;
                }

                $result = app('cache')->remember(
                    $cacheKey,
                    config('google-map.place_details.cache_duration', 0),
                    function () use ($result) {
                        return $result;
                    }
                );

                $this->removeCacheOnEmpty($cacheKey);
            } catch (BadResponseException $e) {
                //
            }
        }

        return new PlaceDetail($result);
    }

    /**
     * Remove cache on empty result.
     *
     * @param  string  $cacheKey
     * @return void
     */
    protected function removeCacheOnEmpty($cacheKey)
    {
        $result = app('cache')->get($cacheKey);

        $maxRetry = config('google-map.place_details.max_retry', 0);
        $retryTotalKey = $cacheKey . '.retry_total';
        $retryTotal = (int) app('cache')->get($retryTotalKey, 0);

        if (is_null($result)) {
            if ($retryTotal <= $maxRetry-2) {
                app('cache')->put(
                    $retryTotalKey,
                    ++$retryTotal,
                    config('google-map.place_details.cache_duration', 0)
                );

                app('cache')->forget($cacheKey);
            }
        }
    }
}
