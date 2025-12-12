---
title: AI Integration
description: Built-in AI capabilities for smart admin experiences.
---

# AI Integration

Laravilt includes powerful AI features out of the box.

## Features

- **AI Chat**: Conversational interface for your admin panel
- **AI Fields**: Smart form fields with AI assistance
- **Prompt Management**: Reusable prompts and templates
- **Context Awareness**: AI understands your data models

## AI Chat

Add an AI assistant to your panel:

```php
// config/laravilt/ai.php
return [
    'enabled' => true,
    'provider' => 'openai',
    'model' => 'gpt-4',
];
```

## AI Form Fields

```php
use Laravilt\Forms\Components\AITextarea;

AITextarea::make('description')
    ->prompt('Write a product description for: {name}')
    ->generateButton('Generate with AI'),
```

## Configuration

Set up your AI provider in `.env`:

```env
OPENAI_API_KEY=your-api-key
LARAVILT_AI_ENABLED=true
```

## Learn More

- [Configuration](/docs/ai/configuration)
- [AI Assistants](/docs/ai/assistants)
- [Prompts](/docs/ai/prompts)
