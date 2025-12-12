<?php

namespace App\Laravilt\Admin\Resources\Product\Table;

use Laravilt\Actions\BulkActionGroup;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\DeleteBulkAction;
use Laravilt\Actions\EditAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Actions\ForceDeleteAction;
use Laravilt\Actions\ForceDeleteBulkAction;
use Laravilt\Actions\RestoreAction;
use Laravilt\Actions\RestoreBulkAction;
use Laravilt\Tables\Filters\TrashedFilter;
use Laravilt\Tables\Columns\ImageColumn;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Columns\ToggleColumn;
use Laravilt\Tables\Table;

class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('User')->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')->sortable(),

                TextColumn::make('name')->searchable()->sortable(),

                TextColumn::make('slug')->sortable(),

                TextColumn::make('description')->searchable()->sortable(),

                TextColumn::make('content')->sortable(),

                TextColumn::make('price')->sortable()
                    ->money('USD'),

                TextColumn::make('compare_price')->sortable()
                    ->money('USD'),

                TextColumn::make('stock')->sortable(),

                TextColumn::make('sku')->sortable(),

                ImageColumn::make('image')
                    ->circular(),

                TextColumn::make('gallery')->sortable(),

                TextColumn::make('status')->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        default => 'secondary',
                    }),

                ToggleColumn::make('is_featured'),

                ToggleColumn::make('is_downloadable'),

                TextColumn::make('created_at')->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                // Add filters here
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
            ->paginated([10, 25, 50, 100])
            ->searchable();
    }
}