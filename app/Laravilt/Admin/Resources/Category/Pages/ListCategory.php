<?php

namespace App\Laravilt\Admin\Resources\Category\Pages;

use App\Laravilt\Admin\Resources\Category\CategoryResource;
use Laravilt\Actions\CreateAction;
use Laravilt\Panel\Pages\ListRecords;

class ListCategory extends ListRecords
{
    protected static ?string $resource = CategoryResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}