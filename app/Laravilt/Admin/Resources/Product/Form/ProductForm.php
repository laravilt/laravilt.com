<?php

namespace App\Laravilt\Admin\Resources\Product\Form;

use App\Models\Category;
use Laravilt\Forms\Components\FileUpload;
use Laravilt\Forms\Components\RichEditor;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Forms\Components\Toggle;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->icon('info')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', str($state)->slug()->toString())
                                    ),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),

                Section::make('Content')
                    ->icon('FileText')
                    ->collapsible()
                    ->schema([
                        Textarea::make('description')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products/attachments'),
                    ]),

                Section::make('Media')
                    ->icon('Image')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Main Image')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('products')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                    ->helperText('Max 2MB. Accepted: JPG, PNG, WebP, GIF'),

                                FileUpload::make('gallery')
                                    ->label('Gallery Images')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->directory('products/gallery')
                                    ->maxFiles(6)
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                    ->helperText('Up to 6 images, max 2MB each'),
                            ]),
                    ]),

                Section::make('Pricing & Inventory')
                    ->icon('DollarSign')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->minValue(0)
                                    ->step(0.01),

                                TextInput::make('compare_price')
                                    ->label('Compare at Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->helperText('Original price for showing discounts'),

                                TextInput::make('stock')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0),
                            ]),
                    ]),

                Section::make('Status & Options')
                    ->icon('ToggleLeft')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                Select::make('status')
                                    ->required()
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft'),

                                Toggle::make('is_featured')
                                    ->label('Featured Product')
                                    ->helperText('Display in featured sections'),

                                Toggle::make('is_downloadable')
                                    ->label('Downloadable')
                                    ->helperText('This is a digital product'),
                            ]),
                    ]),
            ]);
    }
}
