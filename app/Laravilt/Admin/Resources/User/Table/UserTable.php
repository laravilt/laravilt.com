<?php

namespace App\Laravilt\Admin\Resources\User\Table;

use Laravilt\Actions\BulkActionGroup;
use Laravilt\Actions\DeleteAction;
use Laravilt\Actions\DeleteBulkAction;
use Laravilt\Actions\EditAction;
use Laravilt\Actions\ViewAction;

use Laravilt\Tables\Columns\ImageColumn;
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Card;
use Laravilt\Tables\Table;

class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')->searchable()->sortable(),

                TextColumn::make('email')->searchable()->sortable(),

                TextColumn::make('email_verified_at')->sortable()
                    ->dateTime(),

                TextColumn::make('password')->sortable(),

                TextColumn::make('remember_token')->sortable(),

                TextColumn::make('created_at')->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('two_factor_secret')->sortable(),

                ImageColumn::make('two_factor_recovery_codes')
                    ->circular(),

                TextColumn::make('two_factor_confirmed_at')->sortable()
                    ->dateTime(),

                TextColumn::make('two_factor_enabled')->sortable(),

                TextColumn::make('two_factor_method')->sortable(),

                TextColumn::make('locale')->sortable(),

                TextColumn::make('timezone')->sortable(),
            ])
            ->filters([
                // Add filters here
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->card(Card::simple('name', 'description'))
            ->cardsPerRow(3)
            ->paginated([10, 25, 50, 100])
            ->searchable()
            ->infiniteScroll()
            ->fixActions();
    }
}