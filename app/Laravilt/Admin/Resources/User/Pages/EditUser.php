<?php

namespace App\Laravilt\Admin\Resources\User\Pages;

use App\Laravilt\Admin\Resources\User\UserResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static ?string $resource = UserResource::class;

    public function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}