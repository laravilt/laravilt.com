<?php

namespace App\Laravilt\Admin\Resources\Order\Pages;

use App\Laravilt\Admin\Resources\Order\OrderResource;
use Laravilt\Panel\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static ?string $resource = OrderResource::class;
}