<?php

namespace App\Laravilt\Admin\Resources\Customer\InfoList;

use Laravilt\Infolists\Entries\ImageEntry;
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class CustomerInfoList
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Customer Profile')
                    ->icon('User')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar')
                            ->label('')
                            ->circular()
                            ->size(80)
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name) . '&background=random&size=160'),

                        Grid::make(2)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('full_name')
                                    ->label('Full Name')
                                    ->size('lg')
                                    ->weight('bold'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        'pending' => 'warning',
                                        'suspended' => 'danger',
                                        default => 'gray',
                                    }),

                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('Mail')
                                    ->copyable(),

                                TextEntry::make('phone')
                                    ->label('Phone')
                                    ->icon('Phone')
                                    ->copyable(),

                                TextEntry::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->date()
                                    ->icon('Calendar'),

                                TextEntry::make('gender')
                                    ->label('Gender')
                                    ->badge()
                                    ->color('gray'),
                            ]),
                    ]),

                Section::make('Address Information')
                    ->icon('MapPin')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('address')
                            ->label('Street Address')
                            ->columnSpanFull(),

                        TextEntry::make('city')
                            ->label('City'),

                        TextEntry::make('state')
                            ->label('State / Province'),

                        TextEntry::make('postal_code')
                            ->label('Postal Code'),

                        TextEntry::make('country')
                            ->label('Country')
                            ->badge(),
                    ]),

                Section::make('Order Statistics')
                    ->icon('ShoppingCart')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('orders_count')
                            ->label('Total Orders')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary'),

                        TextEntry::make('total_spent')
                            ->label('Total Spent')
                            ->money('USD')
                            ->size('lg')
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('last_order_at')
                            ->label('Last Order')
                            ->since()
                            ->icon('Clock'),
                    ]),

                Section::make('Internal Notes')
                    ->icon('FileText')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->prose()
                            ->placeholder('No notes available'),
                    ]),

                Section::make('Timeline')
                    ->icon('Clock')
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Customer Since')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->since(),
                    ]),
            ]);
    }
}
