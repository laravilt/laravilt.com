<?php

namespace App\Laravilt\Admin\Resources\Category\Pages;

use App\Laravilt\Admin\Resources\Category\CategoryResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static ?string $resource = CategoryResource::class;

    public function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure user_id cannot be changed
        unset($data['user_id']);

        return $data;
    }

    public function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}