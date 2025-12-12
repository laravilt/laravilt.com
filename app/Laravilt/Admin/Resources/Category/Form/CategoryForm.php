<?php

namespace App\Laravilt\Admin\Resources\Category\Form;

use Laravilt\Forms\Components\FileUpload;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Forms\Components\Toggle;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->icon('info')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', str($state)->slug()->toString())
                                    ),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),

                Section::make('Content')
                    ->icon('FileText')
                    ->collapsible()
                    ->schema([
                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media')
                    ->icon('Image')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->directory('categories')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->helperText('Max 2MB. Accepted: JPG, PNG, WebP, GIF'),
                    ]),
            ]);
    }
}