<?php

namespace App\Laravilt\Admin\Resources\Product\Pages;

use App\Laravilt\Admin\Resources\Product\ProductResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static ?string $resource = ProductResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}