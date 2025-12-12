<?php

namespace App\Laravilt\Admin\Resources\Category\Pages;

use App\Laravilt\Admin\Resources\Category\CategoryResource;
use App\Models\Category;
use Laravilt\Panel\Pages\ManageRecords;

class ManageCategories extends ManageRecords
{
    protected static ?string $resource = CategoryResource::class;

    public function getHeaderActions(): array
    {
        return [
            $this->getCreateAction()
        ];
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['sort_order'] = (Category::query()->orderBy('id', 'desc')->first()?->id +1);

        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure user_id cannot be changed
        unset($data['user_id']);

        return $data;
    }
}
