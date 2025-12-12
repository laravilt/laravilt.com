---
title: Forms
description: Build powerful forms with 30+ field types.
---

# Forms

Laravilt Forms provides a powerful form builder with over 30 field types.

## Available Fields

- **Text Inputs**: TextInput, Textarea, RichEditor, MarkdownEditor
- **Selection**: Select, Radio, Checkbox, Toggle, CheckboxList
- **Date & Time**: DatePicker, TimePicker, DateTimePicker
- **Files**: FileUpload, ImageUpload
- **Special**: ColorPicker, IconPicker, TagsInput, KeyValue
- **Layout**: Section, Tabs, Grid, Fieldset

## Basic Example

```php
use Laravilt\Forms\Components\TextInput;
use Laravilt\Forms\Components\Select;
use Laravilt\Forms\Components\RichEditor;

public static function form(): array
{
    return [
        TextInput::make('title')
            ->required()
            ->maxLength(255),

        Select::make('category_id')
            ->relationship('category', 'name')
            ->searchable()
            ->preload(),

        RichEditor::make('content')
            ->columnSpanFull(),
    ];
}
```

## Validation

All fields support Laravel validation rules:

```php
TextInput::make('email')
    ->email()
    ->required()
    ->unique('users', 'email'),
```

## Learn More

- [Getting Started with Forms](/docs/forms/getting-started)
- [Field Types](/docs/forms/fields)
- [Form Layout](/docs/forms/layout)
- [Validation](/docs/forms/validation)
