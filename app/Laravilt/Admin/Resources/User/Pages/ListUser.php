<?php

namespace App\Laravilt\Admin\Resources\User\Pages;

use App\Laravilt\Admin\Resources\User\UserResource;
use Laravilt\Actions\CreateAction;
use Laravilt\Panel\Pages\ListRecords;
use Laravilt\Panel\Pages\ManageRecords;

class ListUser extends ManageRecords
{
    protected static ?string $resource = UserResource::class;

    public function getHeaderActions(): array
    {
        return [
           $this->getCreateAction()
        ];
    }
}
