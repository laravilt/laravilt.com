<?php

namespace App\Laravilt\Admin\Resources\Category;

use App\Laravilt\Admin\Resources\Category\Form\CategoryForm;
use App\Laravilt\Admin\Resources\Category\InfoList\CategoryInfoList;
use App\Laravilt\Admin\Resources\Category\Pages\CreateCategory;
use App\Laravilt\Admin\Resources\Category\Pages\EditCategory;
use App\Laravilt\Admin\Resources\Category\Pages\ListCategory;
use App\Laravilt\Admin\Resources\Category\Pages\ViewCategory;
use App\Laravilt\Admin\Resources\Category\Table\CategoryTable;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
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
            'list' => ListCategory::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
            'view' => ViewCategory::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
    }
}