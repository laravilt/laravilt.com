<?php

namespace App\Laravilt\Admin\Resources\Product\RelationManagers;

use Laravilt\Actions\ViewAction;
use Laravilt\Panel\Resources\RelationManagers\RelationManager;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $recordTitleAttribute = 'order.order_number';

    protected static ?string $label = 'Order';

    protected static ?string $pluralLabel = 'Orders';

    protected static ?string $icon = 'ShoppingCart';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('order.customer.full_name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('USD')
                    ->alignEnd(),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('USD')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('order.status')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->searchable();
    }
}
