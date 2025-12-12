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
use App\Laravilt\Admin\Resources\Product\RelationManagers\OrderItemsRelationManager;
use App\Laravilt\Admin\Resources\Product\Table\ProductTable;
use App\Models\Product;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\ApiAction;
use Laravilt\Tables\ApiColumn;
use Laravilt\Tables\ApiResource;
use Laravilt\Tables\Table;

class ProductResource extends Resource
{
    protected static string $model = Product::class;

    protected static ?string $table = 'products';

    protected static ?string $navigationIcon = 'Package';

    protected static ?string $navigationGroup = 'Shop';

    protected static int $navigationSort = 2;

    /**
     * Filter records to show only current user's products (demo isolation).
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

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
            OrderItemsRelationManager::class,
        ];
    }

    /**
     * Configure the API resource for this resource.
     */
    public static function api(ApiResource $api): ApiResource
    {
        return $api
            ->description('Products API - Manage products in your store')
            ->authenticated()
            ->columns([
                ApiColumn::make('id')->type('integer'),
                ApiColumn::make('name')->type('string')->searchable(),
                ApiColumn::make('slug')->type('string'),
                ApiColumn::make('description')->type('string'),
                ApiColumn::make('price')->type('decimal'),
                ApiColumn::make('compare_price')->type('decimal'),
                ApiColumn::make('stock')->type('integer'),
                ApiColumn::make('sku')->type('string'),
                ApiColumn::make('image')->type('string'),
                ApiColumn::make('status')->type('string')->filterable(),
                ApiColumn::make('is_featured')->type('boolean')->filterable(),
                ApiColumn::make('category_id')->type('integer')->filterable(),
                ApiColumn::make('category.name')->type('string')->label('Category Name'),
                ApiColumn::make('created_at')->type('datetime'),
                ApiColumn::make('updated_at')->type('datetime'),
            ])
            ->allowedFilters(['status', 'is_featured', 'category_id'])
            ->allowedSorts(['name', 'price', 'stock', 'created_at'])
            ->allowedIncludes(['category'])
            ->actions([
                // Publish single product
                ApiAction::make('publish')
                    ->label('Publish')
                    ->description('Publish a product to make it visible in the store')
                    ->icon('Eye')
                    ->color('success')
                    ->post()
                    ->requiresRecord()
                    ->successMessage('Product published successfully')
                    ->action(function ($record, $request) {
                        $record->update(['status' => 'published']);
                        return ['status' => 'published', 'product' => $record];
                    }),

                // Unpublish single product
                ApiAction::make('unpublish')
                    ->label('Unpublish')
                    ->description('Unpublish a product to hide it from the store')
                    ->icon('EyeOff')
                    ->color('warning')
                    ->post()
                    ->requiresRecord()
                    ->successMessage('Product unpublished successfully')
                    ->action(function ($record, $request) {
                        $record->update(['status' => 'draft']);
                        return ['status' => 'draft', 'product' => $record];
                    }),

                // Archive single product
                ApiAction::make('archive')
                    ->label('Archive')
                    ->description('Archive a product')
                    ->icon('Archive')
                    ->color('gray')
                    ->post()
                    ->requiresRecord()
                    ->confirmable('Are you sure you want to archive this product?')
                    ->successMessage('Product archived successfully')
                    ->action(function ($record, $request) {
                        $record->update(['status' => 'archived']);
                        return ['status' => 'archived', 'product' => $record];
                    }),

                // Duplicate single product
                ApiAction::make('duplicate')
                    ->label('Duplicate')
                    ->description('Create a copy of this product')
                    ->icon('Copy')
                    ->color('info')
                    ->post()
                    ->requiresRecord()
                    ->successMessage('Product duplicated successfully')
                    ->action(function ($record, $request) {
                        $newProduct = $record->replicate();
                        $newProduct->name = $record->name . ' (Copy)';
                        $newProduct->slug = $record->slug . '-copy-' . uniqid();
                        $newProduct->sku = $record->sku ? $record->sku . '-COPY' : null;
                        $newProduct->status = 'draft';
                        $newProduct->save();
                        return ['original_id' => $record->id, 'duplicate' => $newProduct];
                    }),

                // Toggle featured status
                ApiAction::make('toggle-featured')
                    ->label('Toggle Featured')
                    ->description('Toggle the featured status of a product')
                    ->icon('Star')
                    ->color('warning')
                    ->patch()
                    ->requiresRecord()
                    ->successMessage('Featured status updated')
                    ->action(function ($record, $request) {
                        $record->update(['is_featured' => !$record->is_featured]);
                        return ['is_featured' => $record->is_featured, 'product' => $record];
                    }),

                // Update stock
                ApiAction::make('update-stock')
                    ->label('Update Stock')
                    ->description('Update product stock quantity')
                    ->icon('Package')
                    ->color('primary')
                    ->patch()
                    ->requiresRecord()
                    ->rules(['quantity' => 'required|integer|min:0'])
                    ->fields([
                        ApiColumn::make('quantity')->type('integer')->label('New Stock Quantity'),
                    ])
                    ->successMessage('Stock updated successfully')
                    ->action(function ($record, $request) {
                        $record->update(['stock' => $request->input('quantity')]);
                        return ['stock' => $record->stock, 'product' => $record];
                    }),

                // Adjust price
                ApiAction::make('adjust-price')
                    ->label('Adjust Price')
                    ->description('Adjust product price by percentage or amount')
                    ->icon('DollarSign')
                    ->color('success')
                    ->patch()
                    ->requiresRecord()
                    ->rules([
                        'adjustment_type' => 'required|in:percentage,amount',
                        'value' => 'required|numeric',
                        'direction' => 'required|in:increase,decrease',
                    ])
                    ->fields([
                        ApiColumn::make('adjustment_type')->type('string')->label('Adjustment Type'),
                        ApiColumn::make('value')->type('decimal')->label('Value'),
                        ApiColumn::make('direction')->type('string')->label('Direction'),
                    ])
                    ->successMessage('Price adjusted successfully')
                    ->action(function ($record, $request) {
                        $type = $request->input('adjustment_type');
                        $value = $request->input('value');
                        $direction = $request->input('direction');

                        $currentPrice = $record->price;
                        $adjustment = $type === 'percentage'
                            ? $currentPrice * ($value / 100)
                            : $value;

                        $newPrice = $direction === 'increase'
                            ? $currentPrice + $adjustment
                            : max(0, $currentPrice - $adjustment);

                        $record->update(['price' => round($newPrice, 2)]);
                        return [
                            'old_price' => $currentPrice,
                            'new_price' => $record->price,
                            'product' => $record,
                        ];
                    }),

                // Bulk publish
                ApiAction::make('bulk-publish')
                    ->label('Bulk Publish')
                    ->description('Publish multiple products at once')
                    ->icon('Eye')
                    ->color('success')
                    ->post()
                    ->bulk()
                    ->successMessage('Products published successfully')
                    ->action(function ($record, $request) {
                        $record->update(['status' => 'published']);
                        return ['status' => 'published'];
                    }),

                // Bulk unpublish
                ApiAction::make('bulk-unpublish')
                    ->label('Bulk Unpublish')
                    ->description('Unpublish multiple products at once')
                    ->icon('EyeOff')
                    ->color('warning')
                    ->post()
                    ->bulk()
                    ->successMessage('Products unpublished successfully')
                    ->action(function ($record, $request) {
                        $record->update(['status' => 'draft']);
                        return ['status' => 'draft'];
                    }),

                // Export products (collection action - no record required)
                ApiAction::make('export')
                    ->label('Export Products')
                    ->description('Export products to CSV format')
                    ->icon('Download')
                    ->color('gray')
                    ->get()
                    ->requiresRecord(false)
                    ->successMessage('Export generated')
                    ->action(function ($record, $request) {
                        $products = Product::where('user_id', auth()->id())
                            ->select(['id', 'name', 'sku', 'price', 'stock', 'status'])
                            ->get();

                        return response()->json([
                            'success' => true,
                            'data' => $products,
                            'count' => $products->count(),
                            'exported_at' => now()->toISOString(),
                        ]);
                    }),

                // Get statistics (collection action)
                ApiAction::make('statistics')
                    ->label('Get Statistics')
                    ->description('Get product statistics and analytics')
                    ->icon('BarChart')
                    ->color('info')
                    ->get()
                    ->requiresRecord(false)
                    ->action(function ($record, $request) {
                        $query = Product::where('user_id', auth()->id());

                        return [
                            'total_products' => (clone $query)->count(),
                            'published' => (clone $query)->where('status', 'published')->count(),
                            'draft' => (clone $query)->where('status', 'draft')->count(),
                            'archived' => (clone $query)->where('status', 'archived')->count(),
                            'featured' => (clone $query)->where('is_featured', true)->count(),
                            'out_of_stock' => (clone $query)->where('stock', '<=', 0)->count(),
                            'low_stock' => (clone $query)->whereBetween('stock', [1, 10])->count(),
                            'total_inventory_value' => (clone $query)
                                ->selectRaw('SUM(price * stock) as value')
                                ->value('value') ?? 0,
                            'average_price' => (clone $query)->avg('price') ?? 0,
                        ];
                    }),
            ]);
    }
}
