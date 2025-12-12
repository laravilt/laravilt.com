<?php

namespace App\Laravilt\Admin\Resources\Order\RelationManagers;

use App\Models\Product;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\EditAction;
use Laravilt\Actions\ViewAction;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Panel\Resources\RelationManagers\RelationManager;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'product_name';

    protected static ?string $label = 'Order Item';

    protected static ?string $pluralLabel = 'Order Items';

    protected static ?string $icon = 'ShoppingBag';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(fn () => Product::where('user_id', auth()->id())
                        ->where('status', 'published')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->searchable()
                    ->required(),

                TextInput::make('product_name')
                    ->label('Product Name')
                    ->required(),

                TextInput::make('product_sku')
                    ->label('SKU'),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1),

                TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                    ->required()
                    ->prefix('$'),

                TextInput::make('discount')
                    ->label('Discount')
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),

                TextInput::make('notes')
                    ->label('Notes')
                    ->placeholder('Optional notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable()
                    ->description(fn ($record) => $record->product_sku),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('USD')
                    ->alignEnd(),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('USD')
                    ->alignEnd()
                    ->color('danger'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('USD')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->searchable();
    }
}
