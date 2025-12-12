<?php

namespace App\Laravilt\Admin\Resources\Product\Pages;

use App\Laravilt\Admin\Resources\Product\ProductResource;
use Laravilt\Actions\CreateAction;
use Laravilt\Panel\Pages\ListRecords;

class ListProduct extends ListRecords
{
    protected static ?string $resource = ProductResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}