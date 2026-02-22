# Settings Bundle

[![CI](https://github.com/symkit/settings-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/symkit/settings-bundle/actions)
[![Latest Version](https://img.shields.io/packagist/v/symkit/settings-bundle.svg)](https://packagist.org/packages/symkit/settings-bundle)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg)](https://phpstan.org/)

A Symfony bundle for managing application-wide settings with a sectioned UI, automatic cache invalidation, and WebManifest generation. Features are **activable per block** (admin, webmanifest, Twig, metadata provider). Entity and repository are **configurable** so you can use your own classes.

## Requirements

- PHP 8.2+
- Symfony 7.x or 8.x
- **symkit/media-bundle** (for Media fields and entity relations)
- **symkit/form-bundle**, **symkit/crud-bundle**, **symkit/metadata-bundle**, **symkit/menu-bundle** (for the admin UI)

## Features

- **Sectioned Form**: Organize settings into logical groups (General, Logos, Social, Icons) via `FormSectionType` (symkit/form-bundle).
- **Singleton Management**: Single settings entity with cache and invalidation on update.
- **WebManifest**: Service to generate `site.webmanifest` data; route can be enabled/disabled.
- **Twig**: Global `settings` variable and `app_settings()` function (optional).
- **Metadata**: Optional `SiteInfoProvider` for symkit/metadata-bundle.
- **Translations**: Domain `SymkitSettingsBundle` with EN and FR provided.

## Installation

1. Install the bundle:
   ```bash
   composer require symkit/settings-bundle
   ```

2. Register the bundle in `config/bundles.php`:
   ```php
   return [
       // ...
       Symkit\SettingsBundle\SettingsBundle::class => ['all' => true],
   ];
   ```

3. Configure Doctrine mapping in `config/packages/doctrine.yaml` (adjust `dir`/`prefix` if using a custom entity):
   ```yaml
   doctrine:
       orm:
           mappings:
               Settings:
                   type: attribute
                   is_bundle: false
                   dir: '%kernel.project_dir%/vendor/symkit/settings-bundle/src/Entity'
                   prefix: 'Symkit\SettingsBundle\Entity'
                   alias: Settings
   ```

4. Create `config/packages/symkit_settings.yaml`:
   ```yaml
   symkit_settings:
       cache_expires_after: 86400
       admin:
           enabled: true
           route_prefix: admin
       webmanifest:
           enabled: true
       twig:
           enabled: true
       metadata_provider:
           enabled: true
       doctrine:
           entity: Symkit\SettingsBundle\Entity\Settings
           repository: Symkit\SettingsBundle\Repository\SettingsRepository
   ```

5. **Routes**: Import only the route files for features you enable.
   - If `admin.enabled` is true, in `config/routes.yaml`:
     ```yaml
     symkit_settings_admin:
         resource: '@SymkitSettingsBundle/config/routes_admin.yaml'
         prefix: '/%symkit_settings.admin.route_prefix%'
     ```
   - If `webmanifest.enabled` is true:
     ```yaml
     symkit_settings_webmanifest:
         resource: '@SymkitSettingsBundle/config/routes_webmanifest.yaml'
     ```

6. **Metadata (optional)**  
   If `metadata_provider.enabled` is true, set your app’s metadata bundle to use the settings provider, e.g. in `config/packages/symkit_metadata.yaml`:
   ```yaml
   symkit_metadata:
       site_info_provider: Symkit\SettingsBundle\Provider\SettingsSiteInfoProvider
   ```

## Configuration

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `cache_expires_after` | int | `86400` | Cache TTL in seconds. |
| `admin.enabled` | bool | `true` | Register admin controller and admin routes. |
| `admin.route_prefix` | string | `admin` | URL prefix for admin (e.g. `/admin/settings`). |
| `webmanifest.enabled` | bool | `true` | Register webmanifest controller and route. |
| `twig.enabled` | bool | `true` | Register Twig extension (`settings`, `app_settings()`). |
| `metadata_provider.enabled` | bool | `true` | Register `SiteInfoProvider` for symkit/metadata-bundle. |
| `doctrine.entity` | string | `Symkit\SettingsBundle\Entity\Settings` | FQCN of settings entity. |
| `doctrine.repository` | string | `Symkit\SettingsBundle\Repository\SettingsRepository` | FQCN of settings repository. |

Disabling a feature (e.g. `admin.enabled: false`) means the corresponding services and routes are not registered; do not import the related route file in that case.

## Custom entity and repository

Your entity must implement `Symkit\SettingsBundle\Contract\SettingsInterface`. Your repository must implement `Symkit\SettingsBundle\Contract\SettingsRepositoryInterface` and provide a constructor compatible with the bundle (e.g. `ManagerRegistry` + `string $entityClass` for a Doctrine repository).

Example:

```yaml
symkit_settings:
    doctrine:
        entity: App\Entity\MySettings
        repository: App\Repository\MySettingsRepository
```

Map your entity in Doctrine and import only the admin route if you use the bundled admin UI.

## Usage

### Twig (when `twig.enabled` is true)

```twig
{{ settings.websiteName }}
{% set app_settings = app_settings() %}
{{ app_settings.websiteDescription }}
```

### PHP

Inject the manager interface:

```php
use Symkit\SettingsBundle\Contract\SettingsManagerInterface;

public function __construct(
    private SettingsManagerInterface $settingsManager,
) {}

public function someMethod(): void
{
    $settings = $this->settingsManager->get();
}
```

### WebManifest

When `webmanifest.enabled` is true and the route is imported, the manifest is served at `/site.webmanifest`.

## Translations

The bundle uses the domain **SymkitSettingsBundle** and ships with `translations/SymkitSettingsBundle.en.xlf` and `SymkitSettingsBundle.fr.xlf`. You can override or add messages in your app’s `translations/` directory with the same domain.

## Contributing

Run `make quality` before committing.

## License

MIT.
