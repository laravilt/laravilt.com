<?php

namespace App\Laravilt\Admin\Resources\Product\Table;

use Illuminate\Database\Eloquent\SoftDeletingScope;
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
use Laravilt\Tables\Columns\ImageColumn;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Columns\ToggleColumn;
use Laravilt\Tables\Filters\TrashedFilter;
use Laravilt\Tables\Table;

class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn($query) => $query->where('user_id', auth()->id())
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            )
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->sku ? "SKU: {$record->sku}" : null),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('stock')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state <= 0 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'archived' => 'danger',
                        default => 'secondary',
                    }),

                ToggleColumn::make('is_featured')
                    ->label('Featured'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->infiniteScroll()
            ->filters([
                TrashedFilter::make(),
            ])
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
            ->paginated([12, 24, 48, 98])
            ->gridOnly()
            ->searchable()
            // Grid view configuration
            ->card(
                Card::product(
                    imageField: 'image',
                    titleField: 'name',
                    priceField: 'price',
                    descriptionField: 'description',
                    badgeField: 'status'
                )
                ->badge('status', fn ($state) => match ($state) {
                    'published' => 'success',
                    'draft' => 'gray',
                    'archived' => 'danger',
                    default => 'secondary',
                })
                ->subtitle('category.name')
                ->actionsPosition('bottom')
                ->metadata([
                    ['label' => 'Stock', 'field' => 'stock'],
                    ['label' => 'SKU', 'field' => 'sku'],
                ])
            )
            ->cardsPerRow(4);
    }
}
