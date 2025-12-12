<?php

namespace App\Laravilt\Admin\Resources\Product\Pages;

use App\Laravilt\Admin\Resources\Product\ProductResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static ?string $resource = ProductResource::class;

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