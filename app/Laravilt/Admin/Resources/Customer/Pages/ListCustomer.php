<?php

namespace App\Laravilt\Admin\Resources\Customer\Pages;

use App\Laravilt\Admin\Resources\Customer\CustomerResource;
use Laravilt\Actions\CreateAction;
use Laravilt\Panel\Pages\ListRecords;

class ListCustomer extends ListRecords
{
    protected static ?string $resource = CustomerResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}