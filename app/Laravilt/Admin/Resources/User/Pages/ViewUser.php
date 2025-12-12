<?php

namespace App\Laravilt\Admin\Resources\User\Pages;

use App\Laravilt\Admin\Resources\User\UserResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Panel\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static ?string $resource = UserResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}