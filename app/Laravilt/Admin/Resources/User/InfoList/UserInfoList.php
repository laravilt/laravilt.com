<?php

namespace App\Laravilt\Admin\Resources\User\InfoList;

use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class UserInfoList
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
                                TextEntry::make('name'),

                                TextEntry::make('email'),

                                TextEntry::make('email_verified_at')
                                    ->dateTime(),

                                TextEntry::make('created_at')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->dateTime(),

                                TextEntry::make('two_factor_secret'),

                                TextEntry::make('two_factor_recovery_codes'),

                                TextEntry::make('two_factor_confirmed_at')
                                    ->dateTime(),

                                TextEntry::make('two_factor_enabled'),

                                TextEntry::make('two_factor_method'),

                                TextEntry::make('locale'),

                                TextEntry::make('timezone'),
                            ]),
                    ]),
            ]);
    }
}