---
title: Authentication
description: Complete authentication system with social login, 2FA, and passkeys.
---

# Authentication

Laravilt provides a complete authentication solution.

## Features

- Email/Password login
- Social authentication (Google, GitHub, etc.)
- Two-factor authentication (2FA)
- Passkey support (WebAuthn)
- Session management
- API tokens

## Social Login

Configure social providers in `config/laravilt-auth.php`:

```php
'socialite' => [
    'providers' => ['google', 'github'],
],
```

## Two-Factor Authentication

Enable 2FA for enhanced security:

```php
'two_factor' => [
    'enabled' => true,
    'issuer' => 'My App',
],
```

## Passkeys

Modern passwordless authentication:

```php
'passkeys' => [
    'enabled' => true,
],
```

## Learn More

- [Configuration](/docs/authentication/configuration)
- [Social Login](/docs/authentication/social-login)
- [Two-Factor Auth](/docs/authentication/two-factor)
- [Passkeys](/docs/authentication/passkeys)
