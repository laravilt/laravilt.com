<?php

namespace App\Laravilt\Admin\Resources\Order\InfoList;

use Laravilt\Infolists\Entries\RepeatableEntry;
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class OrderInfoList
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Order Details')
                    ->icon('ShoppingCart')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('order_number')
                            ->label('Order Number')
                            ->size('lg')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                'refunded' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'refunded' => 'gray',
                                default => 'gray',
                            }),
                    ]),

                Section::make('Customer Information')
                    ->icon('User')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.full_name')
                            ->label('Customer Name'),

                        TextEntry::make('customer.email')
                            ->label('Email')
                            ->icon('Mail')
                            ->copyable(),

                        TextEntry::make('customer.phone')
                            ->label('Phone')
                            ->icon('Phone')
                            ->copyable(),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->color('gray'),
                    ]),

                Section::make('Order Items')
                    ->icon('Package')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('product_name')
                                    ->label('Product'),

                                TextEntry::make('product_sku')
                                    ->label('SKU')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('quantity')
                                    ->label('Qty'),

                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('USD'),

                                TextEntry::make('discount')
                                    ->label('Discount')
                                    ->money('USD'),

                                TextEntry::make('total_price')
                                    ->label('Total')
                                    ->money('USD')
                                    ->weight('bold'),
                            ]),
                    ]),

                Section::make('Pricing Summary')
                    ->icon('DollarSign')
                    ->collapsible()
                    ->columns(5)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('USD'),

                        TextEntry::make('tax')
                            ->label('Tax')
                            ->money('USD'),

                        TextEntry::make('shipping')
                            ->label('Shipping')
                            ->money('USD'),

                        TextEntry::make('discount')
                            ->label('Discount')
                            ->money('USD')
                            ->color('danger'),

                        TextEntry::make('total')
                            ->label('Total')
                            ->money('USD')
                            ->size('lg')
                            ->weight('bold')
                            ->color('success'),
                    ]),

                Section::make('Addresses')
                    ->icon('MapPin')
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('shipping_address')
                            ->label('Shipping Address')
                            ->prose(),

                        TextEntry::make('billing_address')
                            ->label('Billing Address')
                            ->prose(),
                    ]),

                Section::make('Timeline')
                    ->icon('Clock')
                    ->collapsible()
                    ->collapsed()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime()
                            ->icon('Calendar'),

                        TextEntry::make('paid_at')
                            ->label('Paid')
                            ->dateTime()
                            ->icon('CreditCard')
                            ->placeholder('Not paid'),

                        TextEntry::make('shipped_at')
                            ->label('Shipped')
                            ->dateTime()
                            ->icon('Truck')
                            ->placeholder('Not shipped'),

                        TextEntry::make('delivered_at')
                            ->label('Delivered')
                            ->dateTime()
                            ->icon('CheckCircle')
                            ->placeholder('Not delivered'),
                    ]),

                Section::make('Notes')
                    ->icon('FileText')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->prose()
                            ->placeholder('No notes'),
                    ]),
            ]);
    }
}
