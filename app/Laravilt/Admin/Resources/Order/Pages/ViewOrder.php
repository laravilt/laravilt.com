<?php

namespace App\Laravilt\Admin\Resources\Order\Pages;

use App\Laravilt\Admin\Resources\Order\OrderResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Panel\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static ?string $resource = OrderResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}