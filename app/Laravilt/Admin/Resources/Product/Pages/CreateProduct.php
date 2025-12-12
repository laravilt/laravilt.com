<?php

namespace App\Laravilt\Admin\Resources\Product\Pages;

use App\Laravilt\Admin\Resources\Product\ProductResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static ?string $resource = ProductResource::class;
}