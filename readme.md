# Next Meetup Hint

## About

This repository provides the features of WordPress plugin _Next meetup hint_. The repository is used as a basis for deploying the plugin to the WordPress repository. It is not intended to run as a plugin as it is, even if that is possible for development.

## Check for WordPress Coding Standards

### Initialize

`composer install`

### Run

`vendor/bin/phpcs --extensions=php --ignore=*/vendor/*,*/svn/* --standard=WordPress .`

### Repair

`vendor/bin/phpcbf --extensions=php --ignore=*/vendor/*,*/svn/* --standard=WordPress .`

## Check for WordPress VIP Coding Standards

Hint: this check runs against the VIP-GO-platform which is not our target for this plugin. Many warnings can be ignored.

### Run

`vendor/bin/phpcs --extensions=php --ignore=*/vendor/*,*/svn/* --standard=WordPress-VIP-Go .`
