<?php

namespace App\Laravilt\Admin\Resources\Product\InfoList;

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
                Section::make('Information')
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('User'),

                                TextEntry::make('category.name')
                                    ->label('Category'),

                                TextEntry::make('name'),

                                TextEntry::make('slug'),

                                TextEntry::make('description'),

                                TextEntry::make('content'),

                                TextEntry::make('price')
                                    ->money('USD'),

                                TextEntry::make('compare_price')
                                    ->money('USD'),

                                TextEntry::make('stock'),

                                TextEntry::make('sku'),

                                TextEntry::make('image'),

                                TextEntry::make('gallery'),

                                TextEntry::make('status')
                                    ->badge(),

                                TextEntry::make('is_featured'),

                                TextEntry::make('is_downloadable'),

                                TextEntry::make('created_at')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}