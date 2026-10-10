# Next Meetup Hint

## About

This repository provides the features of WordPress plugin _Next meetup hint_. The repository is used as a basis for deploying the plugin to the WordPress repository. It is not intended to run as a plugin as it is, even if that is possible for development.

## Usage

### For users

Download the plugin [from the WordPress Repository](https://wordpress.org/plugins/next-meetup-hint/).
Or download the lastest release ZIP [from GitHub](https://github.com/threadi/next-meetup-hint/releases).

### For developers

Checkout this repository in your development environment.

## Check for WordPress Coding Standards

### Initialize

`composer install`

### Run

`vendor/bin/phpcs .`

### Repair

`vendor/bin/phpcbf .`

## Check for WordPress VIP Coding Standards

Hint: this check runs against the VIP-GO-platform, not our target for this plugin. Many warnings can be ignored.

### Run

`vendor/bin/phpcs --extensions=php --ignore=*/vendor/*,*/svn/* --standard=WordPress-VIP-Go .`

## Analyze with PHPStan

`vendor/bin/phpstan analyse`

## Check PHP compatibility

`vendor/bin/phpcs -p next-meetup-hint.php uninstall.php templates --extensions=php --standard=PHPCompatibilityWP --runtime-set testVersion 8.2-`

## Smoke test in WordPress Playground

```
composer install --no-dev
npm ci
npx --no -- @wp-playground/cli run-blueprint --blueprint=./.github/blueprints/smoke.json --mount="$(pwd):/wordpress/wp-content/plugins/next-meetup-hint"
```

The test writes `SMOKE_OK` to `smoke-result.txt` if the plugin could be activated and works.

## Release

Push a tag with the version number, e.g. `3.2.0`. The workflow "Build release zip" checks the code, builds the zip, runs the smoke test and creates the release with the notes of this version from `changelog.md`.
