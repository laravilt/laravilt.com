<?php

namespace App\Laravilt\Admin\Resources\Customer\Form;

use Laravilt\Forms\Components\DatePicker;
use Laravilt\Forms\Components\FileUpload;
use Laravilt\Forms\Components\Hidden;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Textarea;
use Laravilt\Schemas\Components\Grid;
use Laravilt\Schemas\Components\Section;
use Laravilt\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->icon('User')
                    ->description('Customer basic details')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Hidden::make('user_id')->default(auth()->id()),
                                FileUpload::make('avatar')
                                    ->label('Photo')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->directory('customers/avatars')
                                    ->maxSize(1024)
                                    ->columnSpanFull(),

                                TextInput::make('first_name')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('John'),

                                TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Doe'),

                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('john@example.com'),

                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('+1 (555) 123-4567'),

                                DatePicker::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->maxDate(now()->subYears(13))
                                    ->native(false),

                                Select::make('gender')
                                    ->label('Gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                        'prefer_not_to_say' => 'Prefer not to say',
                                    ])
                                    ->native(false),
                            ]),
                    ]),

                Section::make('Address')
                    ->icon('MapPin')
                    ->description('Shipping and billing address')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Textarea::make('address')
                                    ->label('Street Address')
                                    ->rows(2)
                                    ->placeholder('123 Main Street, Apt 4B')
                                    ->columnSpanFull(),

                                TextInput::make('city')
                                    ->label('City')
                                    ->maxLength(100)
                                    ->placeholder('New York'),

                                TextInput::make('state')
                                    ->label('State / Province')
                                    ->maxLength(100)
                                    ->placeholder('NY'),

                                TextInput::make('postal_code')
                                    ->label('Postal Code')
                                    ->maxLength(20)
                                    ->placeholder('10001'),

                                Select::make('country')
                                    ->label('Country')
                                    ->options([
                                        'US' => 'United States',
                                        'CA' => 'Canada',
                                        'GB' => 'United Kingdom',
                                        'AU' => 'Australia',
                                        'DE' => 'Germany',
                                        'FR' => 'France',
                                        'ES' => 'Spain',
                                        'IT' => 'Italy',
                                        'NL' => 'Netherlands',
                                        'BR' => 'Brazil',
                                        'MX' => 'Mexico',
                                        'JP' => 'Japan',
                                        'CN' => 'China',
                                        'IN' => 'India',
                                        'AE' => 'United Arab Emirates',
                                        'SA' => 'Saudi Arabia',
                                        'EG' => 'Egypt',
                                    ])
                                    ->searchable()
                                    ->native(false),
                            ]),
                    ]),

                Section::make('Status & Notes')
                    ->icon('Settings')
                    ->description('Account status and internal notes')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Account Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'pending' => 'Pending Verification',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),

                        Textarea::make('notes')
                            ->label('Internal Notes')
                            ->rows(3)
                            ->placeholder('Add any internal notes about this customer...')
                            ->helperText('These notes are only visible to staff')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
