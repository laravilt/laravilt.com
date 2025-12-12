<?php

namespace App\Laravilt\Admin\Resources\Product\InfoList;

use Laravilt\Infolists\Entries\ImageEntry;
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class ProductInfoList
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Product Details')
                    ->icon('Package')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('image')
                            ->label('')
                            ->size(120),

                        Grid::make(2)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->size('lg')
                                    ->weight('bold'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'published' => 'success',
                                        'draft' => 'gray',
                                        'archived' => 'danger',
                                        default => 'gray',
                                    }),

                                TextEntry::make('category.name')
                                    ->label('Category')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->copyable()
                                    ->icon('Barcode'),

                                TextEntry::make('slug')
                                    ->label('Slug')
                                    ->color('gray'),

                                TextEntry::make('is_featured')
                                    ->label('Featured')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state) => $state ? 'Yes' : 'No')
                                    ->color(fn (bool $state) => $state ? 'success' : 'gray'),
                            ]),
                    ]),

                Section::make('Pricing & Inventory')
                    ->icon('DollarSign')
                    ->collapsible()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('price')
                            ->label('Price')
                            ->money('USD')
                            ->size('lg')
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('compare_price')
                            ->label('Compare at Price')
                            ->money('USD')
                            ->color('gray')
                            ->strikethrough(),

                        TextEntry::make('stock')
                            ->label('Stock')
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                $state <= 0 => 'danger',
                                $state <= 10 => 'warning',
                                default => 'success',
                            }),

                        TextEntry::make('is_downloadable')
                            ->label('Type')
                            ->formatStateUsing(fn (bool $state) => $state ? 'Digital' : 'Physical')
                            ->badge()
                            ->color(fn (bool $state) => $state ? 'info' : 'gray'),
                    ]),

                Section::make('Description')
                    ->icon('FileText')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->prose()
                            ->placeholder('No description'),
                    ]),

                Section::make('Content')
                    ->icon('AlignLeft')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('content')
                            ->label('')
                            ->html()
                            ->placeholder('No content'),
                    ]),

                Section::make('Gallery')
                    ->icon('Images')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        ImageEntry::make('gallery')
                            ->label('')
                            ->size(100)
                            ->stacked(),
                    ]),

                Section::make('Timeline')
                    ->icon('Clock')
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime()
                            ->icon('Calendar'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->since()
                            ->icon('Clock'),
                    ]),
            ]);
    }
}
