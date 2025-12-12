<?php

namespace App\Laravilt\Admin\Resources\Order\Pages;

use App\Laravilt\Admin\Resources\Order\OrderResource;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static ?string $resource = OrderResource::class;

    public function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}