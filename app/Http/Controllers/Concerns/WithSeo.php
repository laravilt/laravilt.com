<?php

namespace App\Http\Controllers\Concerns;

use App\Support\SeoData;
use Illuminate\Http\Request;

trait WithSeo
{
    /**
     * Set SEO data for the current request.
     */
    protected function seo(SeoData|string $seo): void
    {
        if (is_string($seo)) {
            $seo = SeoData::make($seo);
        }

        request()->attributes->set('seo', $seo);
    }
}
