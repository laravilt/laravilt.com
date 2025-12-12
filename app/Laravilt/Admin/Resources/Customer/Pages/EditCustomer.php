<?php

namespace App\Laravilt\Admin\Resources\Customer\Pages;

use App\Laravilt\Admin\Resources\Customer\CustomerResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static ?string $resource = CustomerResource::class;

    public function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}