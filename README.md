# Liberu Social Network

> An open, modular Laravel platform for building communities and social products you control.

[Software](https://liberusoftware.com) · [Hosting](https://liberuhosting.com) · [Services](https://liberuservices.com) · [Liberu Group](https://liberugroup.com)

[![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)](https://www.php.net/) [![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/) [![Filament](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com/) [![Livewire](https://img.shields.io/badge/Livewire-4-FB70A9)](https://livewire.laravel.com/)

[![Install](https://github.com/liberusoftware/social-network-laravel/actions/workflows/install.yml/badge.svg?branch=main)](https://github.com/liberusoftware/social-network-laravel/actions/workflows/install.yml) [![Tests](https://github.com/liberusoftware/social-network-laravel/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/liberusoftware/social-network-laravel/actions/workflows/tests.yml) [![Docker](https://github.com/liberusoftware/social-network-laravel/actions/workflows/docker.yml/badge.svg?branch=main)](https://github.com/liberusoftware/social-network-laravel/actions/workflows/docker.yml) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE.md)

## What it is

Liberu Social Network is a self-hostable social platform and reference application. It gives teams a working foundation for launching a public community, private network, creator product, membership service, or specialised social experience without rebuilding the core social primitives from scratch.

The application is built from independently versioned Composer modules. Product capabilities, APIs, Livewire interactions, Filament administration, and themes can evolve independently while the host application supplies configuration and integration.

## Product capabilities

The current product modules cover:

- Profiles with handles, bios, avatars, verification, visibility, and blocking
- Follows, friendship requests, lists, suggestions, and relationship visibility
- Home and following timelines with cursors, ranking, and visibility filters
- Posts, articles, drafts, audiences, mentions, hashtags, polls, links, and scheduling
- Reactions, comments, replies, sharing, bookmarks, counters, and abuse limits
- Private conversations with membership, delivery/read state, attachments, safety, and retention controls
- Events with invitations, attendance, capacity, locations, schedules, reminders, and updates
- Images, video, audio, files, processing, accessibility metadata, rights, delivery, and private albums
- Communities with groups, memberships, roles, rules, feeds, questions, files, and moderation boundaries
- Privacy-aware search, hashtags, trends, recommendations, and directories
- Reports, evidence, moderation decisions, appeals, sanctions, and transparency records
- In-app, email, and push notification preferences, grouping, digests, quiet hours, and read state
- ActivityPub-compatible identity, inbox, outbox, and delivery primitives
- Privacy-governed analytics for growth, engagement, retention, health, delivery, and moderation

The platform also includes authentication, two-factor authentication, roles and permissions, organisations, audit trails, API access, feature flags, settings, localisation, files, webhooks, queues, scheduling, observability, and theme support.

## Stack

| Dependency | Version |
|---|---|
| PHP | 8.5 |
| Laravel | 13.x |
| Filament | 5.x |
| Livewire | 4.x |
| Composer | 2.x |
| Node.js | Current supported release |
| Database | A Laravel-supported SQL database |

## Quick start

```bash
git clone https://github.com/liberusoftware/social-network-laravel.git
cd social-network-laravel

composer install
cp .env.example .env
php artisan key:generate

npm install
npm run build

php artisan migrate
php artisan serve
```

Configure the database, mail, application URL, and external integrations in `.env` before running migrations. Use `php artisan migrate --seed` when you want available example data.

For interactive setup, run:

```bash
bash install.sh
```

## How the modules fit together

The host application composes three kinds of package:

```text
modules/
├── social-network-*   # social product domains and API/UI adapters
├── *                  # shared foundation capabilities
themes/                # replaceable visual themes
app/                   # host-specific composition and integration
config/                # application and module policy
tests/                 # cross-module application tests
```

Each social domain is separated from its presentation adapters. For example, the publishing domain can be used through its API, Livewire composer, or Filament administration package. This keeps domain rules reusable across web, mobile, and partner integrations.

Modules are enabled from their manifests and can be adjusted for a deployment through `MODULES_ENABLED` and `MODULES_DISABLED`. Inspect the installed capability catalog with:

```bash
php artisan module:features
php artisan module:features health
php artisan module:status search
```

Installed module and theme directories are tracked so deployments and reviews contain the exact package contents used by the application. Composer remains the source of package versions and dependency resolution. Generic module changes belong in the module repository; host-specific composition belongs here.

## APIs and administration

Versioned API adapters are provided for the social product domains and use Laravel Sanctum authentication. API routes live in the relevant `social-network-*-api` modules, keeping HTTP concerns out of domain packages.

Filament 5 provides administration surfaces for profiles, relationships, publishing, communities, events, media, messaging, moderation, analytics, and network settings. Livewire 4 provides interactive web surfaces such as timelines, composers, conversations, profiles, notifications, and relationship controls.

## Themes

Themes are replaceable packages under `themes/`. The base theme defines shared contracts and fallback behaviour; the default, dark, and clear-signal themes provide application presentations. See [theme architecture](docs/THEME_ARCHITECTURE.md) and [theme system](docs/THEME_SYSTEM.md) for extension guidance.

## Testing and quality

```bash
composer validate --strict
vendor/bin/pest
vendor/bin/pint --test
npm run build
```

The test suite covers cross-domain behaviour, module providers, API boundaries, presentation integration, and architecture rules. See [module development](docs/MODULE_DEVELOPMENT.md) before adding or changing a package.

## Documentation

- [Module development](docs/MODULE_DEVELOPMENT.md)
- [Foundation module matrix](docs/FOUNDATION_MODULE_MATRIX.md)
- [Messaging](docs/MESSAGING.md)
- [Messaging architecture](docs/MESSAGING_ARCHITECTURE.md)
- [Search functionality](docs/SEARCH_FUNCTIONALITY.md)
- [Search architecture](docs/SEARCH_ARCHITECTURE.md)
- [Notifications](docs/NOTIFICATIONS.md)
- [Localisation](docs/MULTI_LANGUAGE.md)
- [Theme architecture](docs/THEME_ARCHITECTURE.md)
- [Theme system](docs/THEME_SYSTEM.md)

## Publishing component repositories

The publishing helper derives component repository names from directories under `modules/` and `themes/`:

```bash
scripts/publish-components
scripts/publish-components --create
scripts/publish-components --push
```

Push mode requires a clean, committed worktree and authenticated GitHub access. It publishes component repositories as subtree splits and never force-updates an existing non-fast-forward history.

## Contributing

Bug reports, focused improvements, documentation, translations, tests, and new social capabilities are welcome. Keep changes scoped, follow existing module boundaries, and include tests for user-visible behaviour. Search existing issues before opening a new one.

Do not report security vulnerabilities in public issues. Email `security@liberusoftware.com` with reproduction details and the affected version.

## License

Liberu Social Network is open-source software released under the [MIT License](LICENSE.md).


### Automatic module and theme publishing

`.github/workflows/sync-packages.yml` publishes packages changed under `modules/`
or `themes/` on pushes to `main`. A manual run reconciles all tracked packages.
Each package needs a `composer.json` with a `liberusoftware/*` name. Files are
copied from Git into the root of `liberusoftware/module-<directory>` or
`liberusoftware/theme-<directory>`. Where `.liberu-meta.json` defines a
`repositoryPrefix`, that design is authoritative (for example,
`modules/module-manager` becomes `module-module-manager`). Without a configured
prefix, an existing `module-` or `theme-` directory prefix is kept. Destination
history is preserved, deleted files are removed, and unchanged packages do not
receive another commit or release. Removing an entire package leaves its public
repository intact. This workflow makes tracked package edits publishable; install
checks still require the project's Composer dependencies to resolve to those
published package contents.

Configure these secrets in the `packagist` GitHub environment (or as repository
or organization secrets available to this repository):

- `MODULE_SYNC_TOKEN`: organization-authorized GitHub token able to create public
  repositories and read/write package contents and releases across the target
  repositories. Because package snapshots include `.github/workflows`, allow
  workflow writes too. A classic PAT needs `repo` and `workflow` scopes and an
  organization role permitted to create repositories. The default `GITHUB_TOKEN`
  cannot publish across these repositories.
- `PACKAGIST_USERNAME`: Packagist account that owns or maintains these packages.
- `PACKAGIST_API_TOKEN`: that account's **main** API token, required to submit
  new packages as well as update existing packages.

New packages start at their declared stable Composer version, or `v1.0.0`.
Existing packages use the highest stable repository tag: a higher declared
Composer version wins, `BREAKING CHANGE:` or `type!:` commits bump major,
`feat:` commits bump minor, and other changes bump patch. Only commits touching
the package since the last successful sync affect this decision. The first
automated run and manual runs reconcile all packages, so canceled or failed
runs cannot silently drop package changes. Tags and commits are
pushed atomically, then a GitHub release is created and the package is submitted
to or refreshed on Packagist. Rerunning a failed job completes publication
without issuing another release for the same contents. Concurrent pushes from
other projects are rejected instead of force-pushed; rerun the failed job.

The source project and its lockfile are not rewritten by publication. Different
projects publishing the same directory name share a destination repository;
coordinate their package contents. Run the publisher's local tests with
`python3 -m unittest discover -s .github/scripts -p 'test_sync_packages.py'`.
