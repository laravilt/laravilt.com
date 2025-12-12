<?php

namespace App\Laravilt\Admin\Resources\Category;

use App\Laravilt\Admin\Resources\Category\Form\CategoryForm;
use App\Laravilt\Admin\Resources\Category\InfoList\CategoryInfoList;
use App\Laravilt\Admin\Resources\Category\Pages\ManageCategories;
use App\Laravilt\Admin\Resources\Category\Table\CategoryTable;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\ApiColumn;
use Laravilt\Tables\ApiResource;
use Laravilt\Tables\Table;

class CategoryResource extends Resource
{
    protected static string $model = Category::class;

    protected static ?string $table = 'categories';

    protected static ?string $navigationIcon = 'FolderTree';

    protected static ?string $navigationGroup = 'Shop';

    protected static int $navigationSort = 1;

    /**
     * Filter records to show only current user's categories (demo isolation).
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    /**
     * Check if the resource is grid-only (table view disabled).
     */
    public static function isGridOnly(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfoList::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    /**
     * Configure the API resource for this resource.
     */
    public static function api(ApiResource $api): ApiResource
    {
        return $api
            ->description('Categories API - Manage product categories')
            ->authenticated()
            ->columns([
                ApiColumn::make('id')->type('integer'),
                ApiColumn::make('name')->type('string')->searchable(),
                ApiColumn::make('slug')->type('string'),
                ApiColumn::make('description')->type('string'),
                ApiColumn::make('image')->type('string'),
                ApiColumn::make('is_active')->type('boolean')->filterable(),
                ApiColumn::make('sort_order')->type('integer'),
                ApiColumn::make('products_count')->type('integer')->label('Products Count'),
                ApiColumn::make('created_at')->type('datetime'),
                ApiColumn::make('updated_at')->type('datetime'),
            ])
            ->allowedFilters(['is_active'])
            ->allowedSorts(['name', 'sort_order', 'created_at'])
            ->allowedIncludes(['products']);
    }
}
