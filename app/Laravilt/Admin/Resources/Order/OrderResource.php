<?php

namespace App\Laravilt\Admin\Resources\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Laravilt\Admin\Resources\Order\Form\OrderForm;
use App\Laravilt\Admin\Resources\Order\InfoList\OrderInfoList;
use App\Laravilt\Admin\Resources\Order\Pages\CreateOrder;
use App\Laravilt\Admin\Resources\Order\Pages\EditOrder;
use App\Laravilt\Admin\Resources\Order\Pages\ListOrder;
use App\Laravilt\Admin\Resources\Order\Pages\ViewOrder;
use App\Laravilt\Admin\Resources\Order\RelationManagers\OrderItemsRelationManager;
use App\Laravilt\Admin\Resources\Order\Table\OrderTable;
use App\Models\Order;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\ApiColumn;
use Laravilt\Tables\ApiResource;
use Laravilt\Tables\Table;

class OrderResource extends Resource
{
    protected static string $model = Order::class;

    protected static ?string $table = 'orders';

    protected static ?string $navigationIcon = 'ShoppingCart';

    protected static ?string $navigationGroup = 'Sales';

    protected static int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('status', 'pending')
            ->count();

        return $count > 10 ? 'danger' : ($count > 5 ? 'warning' : 'success');
    }

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfoList::configure($schema);
    }

    public static function api(ApiResource $api): ApiResource
    {
        return $api
            ->description('Orders API - Manage customer orders')
            ->authenticated()
            ->columns([
                ApiColumn::make('id')->type('integer'),
                ApiColumn::make('order_number')->type('string')->searchable(),
                ApiColumn::make('customer_id')->type('integer')->filterable(),
                ApiColumn::make('customer.first_name')->type('string')->label('Customer First Name'),
                ApiColumn::make('customer.last_name')->type('string')->label('Customer Last Name'),
                ApiColumn::make('status')->type('string')->filterable(),
                ApiColumn::make('payment_status')->type('string')->filterable(),
                ApiColumn::make('payment_method')->type('string'),
                ApiColumn::make('subtotal')->type('decimal'),
                ApiColumn::make('tax')->type('decimal'),
                ApiColumn::make('shipping')->type('decimal'),
                ApiColumn::make('discount')->type('decimal'),
                ApiColumn::make('total')->type('decimal'),
                ApiColumn::make('currency')->type('string'),
                ApiColumn::make('paid_at')->type('datetime'),
                ApiColumn::make('shipped_at')->type('datetime'),
                ApiColumn::make('delivered_at')->type('datetime'),
                ApiColumn::make('created_at')->type('datetime'),
            ])
            ->allowedFilters(['status', 'payment_status', 'customer_id'])
            ->allowedSorts(['order_number', 'total', 'status', 'created_at'])
            ->allowedIncludes(['customer', 'items']);
    }

    /**
     * Get the Eloquent query for retrieving records.
     * This removes the SoftDeletingScope to allow accessing trashed records
     * when the TrashedFilter is active.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'list' => ListOrder::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['order_number', 'customer.first_name', 'customer.last_name', 'customer.email'];
    }
}