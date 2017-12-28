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
        $language = config('google-map.place_details.lang') ?? config('app.locale');
        $cacheKey = "place-details-{$placeId}-{$language}";

        $result = app('cache')->get($cacheKey);

        if (is_null($result) && ! $this->hasExceededMaxRetry($cacheKey)) {
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

        list($maxRetry, $retryTotalKey, $retryTotal) = $this->getRetryData($cacheKey);

        if (is_null($result)) {
            if ($this->hasExceededMaxRetry($cacheKey)) {
                app('cache')->put(
                    $retryTotalKey,
                    ++$retryTotal,
                    config('google-map.place_details.cache_duration', 0)
                );

                app('cache')->forget($cacheKey);
            }
        }
    }

    /**
     * Determine has exceeded max retry.
     *
     * @param  string  $cacheKey
     * @return bool
     */
    protected function hasExceededMaxRetry($cacheKey)
    {
        list($maxRetry, $retryTotalKey, $retryTotal) = $this->getRetryData($cacheKey);

        return $retryTotal > $maxRetry-2;
    }

    /**
     * Get the retry data.
     *
     * @param  string  $cacheKey
     * @return array
     */
    protected function getRetryData($cacheKey)
    {
        $retryTotalKey = $cacheKey . '-retry_total';

        return [
            config('google-map.place_details.max_retry', 0),
            $retryTotalKey,
            (int) app('cache')->get($retryTotalKey, 0),
        ];
    }
}
