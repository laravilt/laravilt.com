<?php

namespace App\Laravilt\Admin\Resources\Order\Pages;

use App\Laravilt\Admin\Resources\Order\OrderResource;
use Laravilt\Actions\CreateAction;
use Laravilt\Panel\Pages\ListRecords;

class ListOrder extends ListRecords
{
    protected static ?string $resource = OrderResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}