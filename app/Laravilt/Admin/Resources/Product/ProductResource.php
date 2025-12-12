<?php

namespace App\Laravilt\Admin\Resources\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Laravilt\Admin\Resources\Product\Form\ProductForm;
use App\Laravilt\Admin\Resources\Product\InfoList\ProductInfoList;
use App\Laravilt\Admin\Resources\Product\Pages\CreateProduct;
use App\Laravilt\Admin\Resources\Product\Pages\EditProduct;
use App\Laravilt\Admin\Resources\Product\Pages\ListProduct;
use App\Laravilt\Admin\Resources\Product\Pages\ViewProduct;
use App\Laravilt\Admin\Resources\Product\Table\ProductTable;
use App\Models\Product;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\Table;

class ProductResource extends Resource
{
    protected static string $model = Product::class;

    protected static ?string $table = 'products';

    protected static ?string $navigationIcon = 'Package';

    protected static ?string $navigationGroup = 'Shop';

    protected static int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfoList::configure($schema);
    }

    /**
     * Get the Eloquent query for retrieving records.
     * Filters by current user (demo isolation) and removes SoftDeletingScope
     * to allow accessing trashed records when the TrashedFilter is active.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'list' => ListProduct::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
            'view' => ViewProduct::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
    }
}