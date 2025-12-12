<?php

namespace App\Laravilt\Admin\Resources\Category\InfoList;

use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class CategoryInfoList
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')
                    ->label('User'),

                TextEntry::make('name'),

                TextEntry::make('slug'),

                TextEntry::make('description'),

                TextEntry::make('image'),

                TextEntry::make('is_active'),

                TextEntry::make('sort_order'),

                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
