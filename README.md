# SPARQL

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/SPARQL/ci.yml?branch=master)](https://github.com/ProfessionalWiki/SPARQL/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/ProfessionalWiki/SPARQL/coverage.svg)](https://shepherd.dev/github/ProfessionalWiki/SPARQL)
[![Psalm level](https://shepherd.dev/github/ProfessionalWiki/SPARQL/level.svg)](psalm.xml)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/sparql/version.png)](https://packagist.org/packages/professional-wiki/sparql)
[![Download count](https://poser.pugx.org/professional-wiki/sparql/d/total.png)](https://packagist.org/packages/professional-wiki/sparql)

MediaWiki extension for executing SPARQL queries and templating their results via Lua.

[Professional.Wiki] created and maintains SPARQL. We provide [Wikibase hosting], [Wikibase development] and [Wikibase consulting].

**Table of Contents**

- [Demo](#demo)
- [Usage](#usage)
- [Installation](#installation)
- [PHP Configuration](#php-configuration)
- [Development](#development)
- [Release notes](#release-notes)

## Demo

Quickly get an idea about what this extension does by checking out the [demo wiki] or [demo video].

## Usage documentation

See the [usage documentation](https://professional.wiki/en/extension/sparql).

## Installation

Platform requirements:

* [PHP] 8.1 or later (tested up to 8.2)
* [MediaWiki] 1.39 or later (tested up to 1.40)

The recommended way to install the SPARQL extension is using [Composer] with
[MediaWiki's built-in support for Composer][Composer install].

On the commandline, go to your wikis root directory. Then run these two commands:

```shell script
COMPOSER=composer.local.json composer require --no-update professional-wiki/sparql:~1.0
```
```shell script
composer update professional-wiki/sparql --no-dev -o
```

Then enable the extension by adding the following to the bottom of your wikis [LocalSettings.php] file:

```php
wfLoadExtension( 'SPARQL' );
```

You can verify the extension was enabled successfully by opening your wikis Special:Version page.

## PHP Configuration

Configuration can be changed via [LocalSettings.php].



## Development

To ensure the dev dependencies get installed, have this in your `composer.local.json`:

```json
{
	"require": {
    "vimeo/psalm": "^5.15.0",
    "phpstan/phpstan": "^1.10.39"
	},
	"extra": {
		"merge-plugin": {
			"include": [
				"extensions/SPARQL/composer.json"
			]
		}
	}
}
```

### Running tests and CI checks

You can use the `Makefile` by running make commands in the `SPARQL` directory.

* `make ci`: Run everything
* `make test`: Run all tests
* `make cs`: Run all style checks and static analysis

Alternatively, you can execute commands from the MediaWiki root directory:

* PHPUnit: `php tests/phpunit/phpunit.php -c extensions/SPARQL/`
* Style checks: `vendor/bin/phpcs -p -s --standard=extensions/SPARQL/phpcs.xml`
* PHPStan: `vendor/bin/phpstan analyse --configuration=extensions/SPARQL/phpstan.neon --memory-limit=2G`
* Psalm: `php vendor/bin/psalm --config=extensions/SPARQL/psalm.xml`

## Release notes

### Version 1.0.0 - TBD



[Professional.Wiki]: https://professional.wiki
[Wikibase]: https://wikibase.consulting/what-is-wikibase/
[Wikibase hosting]: https://professional.wiki/en/hosting/wikibase
[Wikibase development]: https://professional.wiki/en/wikibase-software-development
[Wikibase consulting]: https://wikibase.consulting/
[MediaWiki]: https://www.mediawiki.org
[PHP]: https://www.php.net
[Composer]: https://getcomposer.org
[Composer install]: https://professional.wiki/en/articles/installing-mediawiki-extensions-with-composer
[LocalSettings.php]: https://www.pro.wiki/help/mediawiki-localsettings-php-guide
[demo wiki]: https://sparql.wikibase.wiki/
[demo video]: https://www.youtube.com/watch?v=TODO
