<?php

namespace App\Laravilt\Admin\Resources\Customer\Table;

use Laravilt\Actions\BulkActionGroup;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\DeleteBulkAction;
use Laravilt\Actions\EditAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Actions\ForceDeleteAction;
use Laravilt\Actions\ForceDeleteBulkAction;
use Laravilt\Actions\RestoreAction;
use Laravilt\Actions\RestoreBulkAction;
use Laravilt\Tables\Card;
use Laravilt\Tables\Filters\SelectFilter;
use Laravilt\Tables\Filters\TernaryFilter;
use Laravilt\Tables\Filters\TrashedFilter;
use Laravilt\Tables\Columns\ImageColumn;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Table;

class CustomerTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name) . '&background=random'),

                TextColumn::make('full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->email),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->icon('Phone')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->label('Location')
                    ->description(fn ($record) => $record->country)
                    ->toggleable(),

                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable()
                    ->color(fn ($state) => $state > 1000 ? 'success' : 'gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('last_order_at')
                    ->label('Last Order')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                        'suspended' => 'Suspended',
                    ]),

                SelectFilter::make('country')
                    ->label('Country')
                    ->options([
                        'US' => 'United States',
                        'CA' => 'Canada',
                        'GB' => 'United Kingdom',
                        'AU' => 'Australia',
                        'DE' => 'Germany',
                        'FR' => 'France',
                    ])
                    ->multiple(),

                SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),

                TernaryFilter::make('has_orders')
                    ->label('Has Orders')
                    ->trueLabel('With Orders')
                    ->falseLabel('No Orders')
                    ->queries(
                        true: fn ($query) => $query->where('orders_count', '>', 0),
                        false: fn ($query) => $query->where('orders_count', '=', 0)
                    ),

                TernaryFilter::make('high_value')
                    ->label('High Value')
                    ->trueLabel('High Value (>$1000)')
                    ->falseLabel('Standard (<$1000)')
                    ->queries(
                        true: fn ($query) => $query->where('total_spent', '>', 1000),
                        false: fn ($query) => $query->where('total_spent', '<=', 1000)
                    ),

                TrashedFilter::make(),
            ])
            ->filtersLayout('dropdown')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->searchable()
            ->striped()
            // Grid view configuration
            ->card(
                Card::make()
                    ->image('avatar')
                    ->title('full_name')
                    ->subtitle('email')
                    ->badge('status', fn ($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->actionsPosition('bottom')
                    ->metadata([
                        ['label' => 'Orders', 'field' => 'orders_count'],
                        ['label' => 'Total Spent', 'field' => 'total_spent', 'format' => 'money'],
                        ['label' => 'Location', 'field' => 'city'],
                    ])
            )
            ->cardsPerRow(4);
    }
}
