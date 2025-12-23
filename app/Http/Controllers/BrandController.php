<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\WithSeo;
use App\Support\SeoData;
use Inertia\Inertia;

class BrandController extends Controller
{
    use WithSeo;

    public function index()
    {
        $this->seo(
            SeoData::make('Brand Assets')
                ->description('Download Laravilt brand assets including logos, colors, and guidelines. Use our official branding for your projects and presentations.')
                ->keywords('laravilt brand, laravilt logo, laravilt assets, brand guidelines')
                ->image('/screenshots/14-dashboard-widgets.png')
                ->url('/brand')
        );

        return Inertia::render('Brand/Index');
    }
}
