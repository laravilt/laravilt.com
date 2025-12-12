<?php

namespace App\Laravilt\Admin\Resources\Category\Form;

use Laravilt\Forms\Components\ColorPicker;
use Laravilt\Forms\Components\FileUpload;
use Laravilt\Forms\Components\IconPicker;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Forms\Components\Toggle;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;
use Laravilt\Support\Utilities\Get;
use Laravilt\Support\Utilities\Set;

class CategoryForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->description('Enter the category details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) =>
                                        $set('slug', str($get('name'))->slug()->toString())
                                    ),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(1000),

                        FileUpload::make('image')
                            ->image()
                            ->directory('categories')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(800)
                            ->imageResizeTargetHeight(450),
                    ]),

                Section::make('Appearance')
                    ->description('Customize the category appearance')
                    ->columns(2)
                    ->schema([
                        ColorPicker::make('color')
                            ->label('Category Color')
                            ->helperText('Choose a color to represent this category'),

                        IconPicker::make('icon')
                            ->label('Category Icon')
                            ->helperText('Select an icon for this category'),
                    ]),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive categories will not be visible to customers')
                            ->default(true),
                    ]),
            ]);
    }
}
