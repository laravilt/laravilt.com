<?php

namespace App\Laravilt\Admin\Resources\Customer\Pages;

use App\Laravilt\Admin\Resources\Customer\CustomerResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static ?string $resource = CustomerResource::class;
}