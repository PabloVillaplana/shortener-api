<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\UrlShorterRequest;
use App\Models\UrlShortener;
use Illuminate\Support\Str;

class UrlShortenerController extends BaseApiController
{

    public function redirectShortUrl($shorUrl)
    {
        $url = UrlShortener::where('short_url', $shorUrl)->first();

        if ($url) {
            $url->visits++;
            $url->save();
        }

        return $url ? redirect($url->url) : $this->errorResponse('Short Url Not Found', 404);
    }

    public function topTenUrlVisits()
    {
        $urls = UrlShortener::orderBy('visits', 'desc')->get();

        return !$urls->isEmpty() ? $this->successResponse($urls, 200) : $this->errorResponse('There are not  Urls to show', 404);
    }

    public function short(UrlShorterRequest $request)
    {
        $uri = UrlShortener::where('url', $request->url)->first();

        if ($uri == null) {
            $short = $this->generateShortUrl();

            UrlShortener::create([
                'url' => $request->url,
                'short_url' => $short,
                'is_nsfw' => $request->is_nsfw
            ]);

            $url = UrlShortener::where('url', $request->url)->first();
        }

        return $this->successResponse(!empty($url) ? $url : $uri, 200);
    }

    public function generateShortUrl()
    {
        $result = Str::random(6);

        $data = UrlShortener::where('short_url', $result)->first();

        if ($data != null) {
            $this->generateShortUrl();
        }

        return $result;
    }
}
