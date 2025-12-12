<?php

namespace App\Laravilt\Admin\Resources\Order\Form;

use App\Models\Product;
use Laravilt\Forms\Components\DateTimePicker;
use Laravilt\Forms\Components\Hidden;
use Laravilt\Forms\Components\NumberField;
use Laravilt\Forms\Components\NumberInput;
use Laravilt\Forms\Components\Repeater;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

class OrderForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Order Information')
                    ->icon('ShoppingCart')
                    ->description('Basic order details and customer information')
                    ->collapsible()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('Order Number')
                                    ->default(fn () => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)))
                                    ->disabled()
                                    ->required(),

                                Select::make('customer_id')
                                    ->label('Customer')
                                    ->relationship('customer', 'email')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->email})")
                                    ->searchable(['first_name', 'last_name', 'email'])
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('first_name')->required(),
                                        TextInput::make('last_name')->required(),
                                        TextInput::make('email')->email()->required(),
                                    ]),

                                Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'credit_card' => 'Credit Card',
                                        'debit_card' => 'Debit Card',
                                        'paypal' => 'PayPal',
                                        'bank_transfer' => 'Bank Transfer',
                                        'cash' => 'Cash on Delivery',
                                        'stripe' => 'Stripe',
                                    ])
                                    ->native(false),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Order Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'cancelled' => 'Cancelled',
                                        'refunded' => 'Refunded',
                                    ])
                                    ->default('pending')
                                    ->native(false)
                                    ->required(),

                                Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                        'refunded' => 'Refunded',
                                    ])
                                    ->default('pending')
                                    ->native(false)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Order Items')
                    ->icon('Package')
                    ->description('Add products to this order')
                    ->collapsible()
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->label('Products')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(fn () => Product::where('user_id', auth()->id())->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('product_name', $product->name);
                                                $set('product_sku', $product->sku);
                                                $set('unit_price', $product->price);
                                                $qty = $get('quantity') ?: 1;
                                                $set('total_price', $product->price * $qty);
                                            }
                                        }
                                    })
                                    ->columnSpan(2),

                                Hidden::make('product_name'),
                                Hidden::make('product_sku'),

                                NumberField::make('quantity')
                                    ->label('Qty')
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(1000)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $unitPrice = $get('unit_price') ?: 0;
                                        $set('total_price', $unitPrice * ($state ?: 1));
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $qty = $get('quantity') ?: 1;
                                        $set('total_price', ($state ?: 0) * $qty);
                                    }),

                                TextInput::make('discount')
                                    ->label('Discount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                TextInput::make('total_price')
                                    ->label('Total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled(),

                                Textarea::make('notes')
                                    ->label('Item Notes')
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->addActionLabel('Add Product')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['product_name'] ?? 'New Item'),
                    ]),

                Section::make('Addresses')
                    ->icon('MapPin')
                    ->description('Shipping and billing addresses')
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Textarea::make('shipping_address')
                            ->label('Shipping Address')
                            ->rows(4),

                        Textarea::make('billing_address')
                            ->label('Billing Address')
                            ->rows(4),
                    ]),

                Section::make('Pricing')
                    ->icon('DollarSign')
                    ->description('Order totals and pricing')
                    ->collapsible()
                    ->columns(5)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(),

                        TextInput::make('tax')
                            ->label('Tax')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        TextInput::make('shipping')
                            ->label('Shipping')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        TextInput::make('discount')
                            ->label('Discount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(),

                        Select::make('currency')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'CAD' => 'CAD - Canadian Dollar',
                                'AUD' => 'AUD - Australian Dollar',
                                'JPY' => 'JPY - Japanese Yen',
                            ])
                            ->default('USD')
                            ->native(false),
                    ]),

                Section::make('Dates & Notes')
                    ->icon('Calendar')
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        DateTimePicker::make('paid_at')
                            ->label('Paid At'),

                        DateTimePicker::make('shipped_at')
                            ->label('Shipped At'),

                        DateTimePicker::make('delivered_at')
                            ->label('Delivered At'),

                        Textarea::make('notes')
                            ->label('Order Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
