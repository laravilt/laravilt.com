<?php

namespace App\Laravilt\Admin\Resources\Order\Table;

use Laravilt\Actions\BulkActionGroup;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\DeleteBulkAction;
use Laravilt\Actions\EditAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Actions\ForceDeleteAction;
use Laravilt\Actions\ForceDeleteBulkAction;
use Laravilt\Actions\RestoreAction;
use Laravilt\Actions\RestoreBulkAction;
use Laravilt\Tables\Filters\SelectFilter;
use Laravilt\Tables\Filters\TernaryFilter;
use Laravilt\Tables\Filters\TrashedFilter;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Grouping\Group;
use Laravilt\Tables\Table;

class OrderTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->customer?->email),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
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

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans()),

                TextColumn::make('shipped_at')
                    ->label('Shipped')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('delivered_at')
                    ->label('Delivered')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->multiple(),

                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'debit_card' => 'Debit Card',
                        'paypal' => 'PayPal',
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash on Delivery',
                        'stripe' => 'Stripe',
                    ]),

                TernaryFilter::make('today')
                    ->label('Today\'s Orders')
                    ->trueLabel('Today Only')
                    ->falseLabel('Older Orders')
                    ->queries(
                        true: fn ($query) => $query->whereDate('created_at', today()),
                        false: fn ($query) => $query->whereDate('created_at', '<', today())
                    ),

                TernaryFilter::make('high_value')
                    ->label('Order Value')
                    ->trueLabel('High Value (>$500)')
                    ->falseLabel('Standard (<$500)')
                    ->queries(
                        true: fn ($query) => $query->where('total', '>', 500),
                        false: fn ($query) => $query->where('total', '<=', 500)
                    ),

                TrashedFilter::make(),
            ])
            ->filtersLayout('dropdown')
            ->groups([
                Group::make('status')
                    ->label(__('Order Status'))
                    ->getTitleFromRecordUsing(fn ($record, $value) => match ($value) {
                        'pending' => __('Pending Orders'),
                        'processing' => __('Processing Orders'),
                        'shipped' => __('Shipped Orders'),
                        'delivered' => __('Delivered Orders'),
                        'cancelled' => __('Cancelled Orders'),
                        'refunded' => __('Refunded Orders'),
                        default => ucfirst($value).' '.__('Orders'),
                    })
                    ->collapsible(),

                Group::make('payment_status')
                    ->label(__('Payment Status'))
                    ->getTitleFromRecordUsing(fn ($record, $value) => match ($value) {
                        'paid' => __('Paid'),
                        'pending' => __('Payment Pending'),
                        'failed' => __('Payment Failed'),
                        'refunded' => __('Refunded'),
                        default => ucfirst($value),
                    })
                    ->collapsible(),

                Group::make('payment_method')
                    ->label(__('Payment Method'))
                    ->collapsible(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),
                DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),
                ForceDeleteAction::make()
                    ->visible(fn ($record) => $record->trashed()),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->infiniteScroll()
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->searchable()
            ->striped();
    }
}
