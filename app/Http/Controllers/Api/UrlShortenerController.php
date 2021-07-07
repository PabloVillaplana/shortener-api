<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Models\UrlShortener;
use Illuminate\Http\Request;

class UrlShortenerController extends BaseApiController
{

    public function redirectShortUrl($shorUrl)
    {
        $url = UrlShortener::where('short_url', $shorUrl);

        if ($url) {
            $url->visits +=;
        }

        return $url ? redirect($url->url) : $this->errorResponse('Short Url Not Found', $shorUrl);
    }

    public function topTenUrlVisits()
    {
        $urls = UrlShortener::all()->sortBy('visits');

        return $this->successResponse($urls, 200);
    }

    public function short(Request $request)
    {
        // First check if the url exist
        $uri = UrlShortener::where('url', $request->url)->first();

        if ($uri == null) {
            $short = $this->generateShortUrl();

            UrlShortener::create([
                'url' => $request->url,
                'short' => $short
            ]);

            $url = UrlShortener::where('url', $request->url)->first();
        }


        return $url;
    }

    public function generateShortUrl()
    {
        $result = base_convert(rand(1000, 9999), 10, 36);

        $data = UrlShortener::where('short', $result)->first();

        if ($data != null) {
            $this->generateShortUrl();
        }

        return $result;
    }
}
