<?php

namespace App\Laravilt\Admin\Resources\Product\Pages;

use App\Laravilt\Admin\Resources\Product\ProductResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Panel\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static ?string $resource = ProductResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}