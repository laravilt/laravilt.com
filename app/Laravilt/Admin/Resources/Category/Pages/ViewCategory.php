<?php

namespace App\Laravilt\Admin\Resources\Category\Pages;

use App\Laravilt\Admin\Resources\Category\CategoryResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Panel\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static ?string $resource = CategoryResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}