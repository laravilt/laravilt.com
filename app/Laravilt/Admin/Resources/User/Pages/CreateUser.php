<?php

namespace App\Laravilt\Admin\Resources\User\Pages;

use App\Laravilt\Admin\Resources\User\UserResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static ?string $resource = UserResource::class;
}