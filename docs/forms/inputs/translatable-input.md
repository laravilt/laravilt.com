---
title: TranslatableInput
description: Multi-language text input with a per-locale dialog, RTL support and per-locale validation.
order: 3
---

# TranslatableInput

A multi-language text field. Its value is an array keyed by locale code:

```php
['en' => 'Title', 'ar' => 'العنوان', 'ckb' => 'ناونیشان']
```

The main input edits the active locale. A globe button inside the input shows the active locale's badge and opens a dialog with one input per locale, so every translation is edited in one place. Right-to-left locales render with `dir="rtl"`. The globe is hidden when only one locale is allowed.

## Basic usage

```php
use Laravilt\Forms\Components\TranslatableInput;

TranslatableInput::make('name')
    ->label('Name')
    ->required();
```

## Locales

Restrict the editable locales on the field:

```php
TranslatableInput::make('name')
    ->locales(['en', 'ar', 'ckb']);
```

`locales()` also accepts a closure. It only picks which locales are editable; their names and text directions always come from config, as described below.

When a field does not call `locales()`, the list resolves in this order:

1. `config('laravilt-forms.locales')`
2. `config('app.available_locales')`
3. The application locale

By default the field reuses the languages the panel already knows about. That is the same list the [Locale & Timezone](/docs/auth/profile/preferences) settings page shows, so a project defines its languages once in `config/app.php`. `label` becomes the native name shown in the dialog and `dir` sets the text direction of each input:

```php
// config/app.php
'available_locales' => [
    ['value' => 'en', 'label' => 'English', 'dir' => 'ltr'],
    ['value' => 'ar', 'label' => 'العربية', 'dir' => 'rtl'],
    ['value' => 'ckb', 'label' => 'کوردی', 'dir' => 'rtl'],
],
```

Set `laravilt-forms.locales` only when the languages content is written in differ from the languages the UI is shown in, for example an English-only admin panel that manages content in three languages. Publish the config first:

```bash
php artisan vendor:publish --tag="laravilt-forms-config"
```

```php
// config/laravilt-forms.php
'locales' => [
    'en' => ['name' => 'English', 'direction' => 'ltr'],
    'ar' => ['name' => 'العربية', 'direction' => 'rtl'],
    'ckb' => ['name' => 'کوردی', 'direction' => 'rtl'],
],

// or simply
'locales' => ['en', 'ar', 'ckb'],
```

Each entry accepts a native `name`, a `direction` (`ltr` or `rtl`) and an optional `label` that overrides the short badge on the globe button.

Names and directions are looked up in `laravilt-forms.locales` first and `app.available_locales` second, so plain codes in the forms config still pick up the names the panel defines. A code found in neither is shown as its own code, labelled with its uppercased base code (`en-US` becomes `EN`), and rendered left-to-right.

## Active locale

The active locale is the one the main (collapsed) input edits:

```php
TranslatableInput::make('name')
    ->locales(['en', 'ar', 'ckb'])
    ->activeLocale('ar');
```

It defaults to the application locale when that locale is allowed, otherwise to the first allowed locale. `activeLocale()` also accepts a closure.

## Multiline

Render a textarea per locale instead of a single-line input:

```php
TranslatableInput::make('description')
    ->multiline()
    ->rows(4);
```

## Required locales

`required()` makes every allowed locale required. Narrow it down with `requiredLocales()`:

```php
TranslatableInput::make('name')
    ->locales(['en', 'ar', 'ckb'])
    ->requiredLocales(['en']);
```

Required locales are marked with an asterisk in the dialog. Locales that are not allowed on the field are ignored.

## Validation

Every locale is validated on its own and errors are reported per locale key (`name.en`, `name.ar`, ...). The field shows each error next to the matching input in the dialog and the first one under the main input.

`maxLength()` and any rules added with `rules()` apply to each locale, not to the array:

```php
TranslatableInput::make('name')
    ->locales(['en', 'ar', 'ckb'])
    ->required()
    ->maxLength(120);
```

`getValidationRules()` returns one rule list for the field, as the schema expects, with a `TranslationsRule` carrying the per-locale rules. Use `getLocaleValidationRules()` for flat keys when validating by hand:

```php
$field->getValidationRules();
// ['required', 'array', TranslationsRule(en|ar|ckb => ['required', 'string', 'max:120'])]

$field->getLocaleValidationRules();
// [
//     'name'     => ['required', 'array'],
//     'name.en'  => ['required', 'string', 'max:120'],
//     'name.ar'  => ['required', 'string', 'max:120'],
//     'name.ckb' => ['required', 'string', 'max:120'],
// ]
```

Messages use the field label plus the locale, e.g. "The Name (EN) field is required." A custom message applies to every locale, or to one locale when prefixed with its code:

```php
TranslatableInput::make('name')
    ->required()
    ->validationMessages([
        'required' => 'Please add a translation.',
        'en.required' => 'The English name is required.',
    ]);
```

## Storing translations

The field accepts a JSON string, an array or `null` and always returns an array with every allowed locale present, so it works with both storage styles:

```php
// spatie/laravel-translatable
class Product extends Model
{
    use \Spatie\Translatable\HasTranslations;

    public $translatable = ['name'];
}

// or a plain JSON column
class Category extends Model
{
    protected $casts = ['name' => 'array'];
}

// Both accept the field's value as-is:
$product->setTranslations('name', $data['name']); // or $product->name = $data['name'];
$category->name = $data['name'];
```

A plain string that is not JSON is treated as the translation for the active locale.

## API reference

| Method | Description |
|--------|-------------|
| `locales(array\|Closure)` | Editable locale codes (defaults to the configured locales) |
| `activeLocale(string\|Closure)` | Locale edited by the main input |
| `requiredLocales(array)` | Locales that must be filled in (defaults to all when `required()`) |
| `multiline(bool\|Closure)` | Render a textarea per locale |
| `rows(int)` | Visible rows (multiline only) |
| `maxLength(int)` | Maximum length per locale (also validated) |
| `getLocaleValidationRules()` | Flat rules (`name`, `name.en`, ...) for manual validation |

## Related

- [TextInput](text-input.md)
- [Textarea](textarea.md)
