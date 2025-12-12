<?php

namespace App\Laravilt\Admin\Resources\User;

use App\Laravilt\Admin\Resources\User\Api\UserApi;
use Laravilt\Tables\ApiResource;
use App\Laravilt\Admin\Resources\User\Form\UserForm;
use App\Laravilt\Admin\Resources\User\InfoList\UserInfoList;
use App\Laravilt\Admin\Resources\User\Pages\CreateUser;
use App\Laravilt\Admin\Resources\User\Pages\EditUser;
use App\Laravilt\Admin\Resources\User\Pages\ListUser;
use App\Laravilt\Admin\Resources\User\Pages\ViewUser;
use App\Laravilt\Admin\Resources\User\Table\UserTable;
use App\Models\User;
use Laravilt\Panel\Resources\Resource;
use Laravilt\Schemas\Schema;
use Laravilt\Tables\Table;

class UserResource extends Resource
{
    protected static string $model = User::class;

    protected static ?string $table = 'users';

    protected static ?string $navigationIcon = 'Users';

    protected static ?string $navigationGroup = null;

    protected static int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfoList::configure($schema);
    }

    public static function api(ApiResource $api): ApiResource
    {
        return UserApi::configure($api);
    }

    public static function getPages(): array
    {
        return [
            'list' => ListUser::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
    }
}