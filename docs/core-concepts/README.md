---
title: Core Concepts
description: Understanding the architecture of Laravilt.
---

# Core Concepts

Learn the fundamental concepts behind Laravilt.

## Architecture

Laravilt follows a modular architecture with these key concepts:

- **Resources**: Define CRUD operations for your models
- **Forms**: Build input forms with validation
- **Tables**: Display and manage data
- **Actions**: Perform operations on records
- **Widgets**: Dashboard components

## Type Safety

Laravilt is built with TypeScript-first approach:

```typescript
interface Product {
    id: number;
    name: string;
    price: number;
    created_at: string;
}
```

## Frontend Integration

Uses Vue 3 + Inertia.js for seamless SPA experience:

- Composition API
- TypeScript support
- Reactive forms
- Real-time validation

## Learn More

- [Architecture](/docs/core-concepts/architecture)
- [Type Safety](/docs/core-concepts/type-safety)
- [Frontend Integration](/docs/core-concepts/frontend-integration)
