<?php

namespace App\Laravilt\Admin\Resources\User\Form;

use Laravilt\Forms\Components\DateTimePicker;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class UserForm
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
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('two_factor_method')
                                    ->maxLength(255),

                                TextInput::make('locale')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Dates & Time')
                    ->icon('Calendar')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                Select::make('timezone')
                                    ->options([
                                        // Add options here
                                    ]),
                            ]),
                    ]),

                Section::make('Options')
                    ->icon('ToggleLeft')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('two_factor_enabled')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}