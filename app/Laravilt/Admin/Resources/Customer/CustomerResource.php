<?php

namespace App\Laravilt\Admin\Resources\Customer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Laravilt\Admin\Resources\Customer\Form\CustomerForm;
use App\Laravilt\Admin\Resources\Customer\InfoList\CustomerInfoList;
use App\Laravilt\Admin\Resources\Customer\Pages\CreateCustomer;
use App\Laravilt\Admin\Resources\Customer\Pages\EditCustomer;
use App\Laravilt\Admin\Resources\Customer\Pages\ListCustomer;
use App\Laravilt\Admin\Resources\Customer\Pages\ViewCustomer;
use App\Laravilt\Admin\Resources\Customer\Table\CustomerTable;
use App\Models\Customer;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\ApiColumn;
use Laravilt\Tables\ApiResource;
use Laravilt\Tables\Table;

class CustomerResource extends Resource
{
    protected static string $model = Customer::class;

    protected static ?string $table = 'customers';

    protected static ?string $navigationIcon = 'Users';

    // No navigation group - shows as standalone item in sidebar
    protected static ?string $navigationGroup = null;

    protected static int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'email';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfoList::configure($schema);
    }

    public static function api(ApiResource $api): ApiResource
    {
        return $api
            ->description('Customers API - Manage customer records')
            ->authenticated()
            ->columns([
                ApiColumn::make('id')->type('integer'),
                ApiColumn::make('first_name')->type('string')->searchable(),
                ApiColumn::make('last_name')->type('string')->searchable(),
                ApiColumn::make('email')->type('string')->searchable(),
                ApiColumn::make('phone')->type('string'),
                ApiColumn::make('date_of_birth')->type('date'),
                ApiColumn::make('gender')->type('string')->filterable(),
                ApiColumn::make('address')->type('string'),
                ApiColumn::make('city')->type('string')->filterable(),
                ApiColumn::make('state')->type('string'),
                ApiColumn::make('postal_code')->type('string'),
                ApiColumn::make('country')->type('string')->filterable(),
                ApiColumn::make('status')->type('string')->filterable(),
                ApiColumn::make('total_spent')->type('decimal'),
                ApiColumn::make('orders_count')->type('integer'),
                ApiColumn::make('last_order_at')->type('datetime'),
                ApiColumn::make('created_at')->type('datetime'),
            ])
            ->allowedFilters(['status', 'country', 'gender', 'city'])
            ->allowedSorts(['first_name', 'last_name', 'email', 'total_spent', 'orders_count', 'created_at'])
            ->allowedIncludes(['orders']);
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
            'list' => ListCustomer::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
            'view' => ViewCustomer::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email', 'phone'];
    }
}