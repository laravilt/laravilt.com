<?php

namespace App\Laravilt\Admin\Resources\Customer\Pages;

use App\Laravilt\Admin\Resources\Customer\CustomerResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Panel\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static ?string $resource = CustomerResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}