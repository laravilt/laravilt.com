<?php

namespace App\Laravilt\Admin\Resources\Category\RelationManagers;

use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Toggle;
use Laravilt\Panel\Resources\RelationManagers\RelationManager;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\Columns\BadgeColumn;
use Laravilt\Tables\Columns\ImageColumn;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Columns\ToggleColumn;
use Laravilt\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Product';

    protected static ?string $pluralLabel = 'Products';

    protected static ?string $icon = 'Package';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

            TextInput::make('price')
                ->label('Price')
                ->required()
                ->numeric()
                ->prefix('$')
                ->step(0.01),

            TextInput::make('compare_price')
                ->label('Compare Price')
                ->numeric()
                ->prefix('$')
                ->step(0.01),

            TextInput::make('stock')
                ->label('Stock')
                ->required()
                ->numeric()
                ->default(0),

            TextInput::make('sku')
                ->label('SKU')
                ->maxLength(50),

            Select::make('status')
                ->label('Status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ])
                ->default('draft')
                ->required(),

            Toggle::make('is_featured')
                ->label('Featured'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->width(40)
                    ->height(40),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),

                ToggleColumn::make('is_featured')
                    ->label('Featured'),
            ])
            ->defaultSort('name')
            ->searchable()
            ->paginated([10, 25, 50]);
    }
}
