<?php

namespace App\Laravilt\Admin\Resources\User\Api;

use Laravilt\Tables\ApiColumn;
use Laravilt\Tables\ApiResource;

class UserApi
{
    public static function configure(ApiResource $api): ApiResource
    {
        return $api
            ->columns([
                ApiColumn::make('id')->type('integer')->sortable(),
                ApiColumn::make('name')->searchable()->sortable(),
                ApiColumn::make('email')->searchable()->sortable(),
                ApiColumn::make('email_verified_at')->type('datetime'),
                ApiColumn::make('created_at')->type('datetime')->sortable(),
                ApiColumn::make('updated_at')->type('datetime')->sortable(),
                ApiColumn::make('two_factor_confirmed_at')->type('datetime'),
                ApiColumn::make('two_factor_enabled'),
                ApiColumn::make('two_factor_method'),
                ApiColumn::make('locale'),
                ApiColumn::make('timezone'),
            ])
            ->useAPITester();
    }
}