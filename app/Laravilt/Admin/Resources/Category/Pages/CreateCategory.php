<?php

namespace App\Laravilt\Admin\Resources\Category\Pages;

use App\Laravilt\Admin\Resources\Category\CategoryResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static ?string $resource = CategoryResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}