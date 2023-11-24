# SPARQL

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/SPARQL/ci.yml?branch=master)](https://github.com/ProfessionalWiki/SPARQL/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/ProfessionalWiki/SPARQL/coverage.svg)](https://shepherd.dev/github/ProfessionalWiki/SPARQL)
[![Psalm level](https://shepherd.dev/github/ProfessionalWiki/SPARQL/level.svg)](psalm.xml)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/sparql/version.png)](https://packagist.org/packages/professional-wiki/sparql)
[![Download count](https://poser.pugx.org/professional-wiki/sparql/d/total.png)](https://packagist.org/packages/professional-wiki/sparql)

MediaWiki extension for executing SPARQL queries and templating their results via Lua.

[Professional.Wiki] created and maintains SPARQL. We provide [Wikibase hosting],
[Wikibase development], [MediaWiki development], and [Wikibase consulting].

**Table of Contents**

- [Usage](#usage-documentation)
- [Installation](#installation)
- [PHP Configuration](#php-configuration)
- [Development](#development)
- [Release notes](#release-notes)

## Usage Documentation

Define a lua module that requires the `SPARQL` binding and uses its runQuery method.

Example: create page `Module:MySPARQL`:

```lua
local sparql = require('SPARQL') -- Load the SPARQL binding

local p = {}

function p.showFirstValue(frame)
  local sparqlQuery = frame.args[1]
  local queryResults = sparql.runQuery(sparqlQuery) -- Use the runQuery method

  -- Replace "work" with the first SELECT variable in your SPARQL query
  return queryResults['results']['bindings'][1]['work']['value']
end

return p
```

Which can then be invoked via [Scribunto]'s normal mechanisms from within wikitext. Example:

`{{#invoke:MySPARQL|showFirstValue|your SPARQL query here}}`

Lua module examples:

* [Show the first value (README example)](demoLua/firstValue.lua)
* [Show everything](demoLua/showEverything.lua)
* [Build an HTML table](demoLua/htmlTable.lua)

## Installation

Platform requirements:

* [PHP] 8.1 or later (tested up to 8.2)
* [MediaWiki] 1.39 or later (tested up to 1.42-dev)
* [Scribunto] and lua

We also recommend installing the [CodeEditor extension]
for a better editing experience of Lua modules.

### Installing The SPARQL Extension

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

### SPARQL Endpoint URL

Variable: `$wgSPARQLEndpoint`

Required for the extension to function. You can enable the extension without setting this variable without
breaking your wiki, but the extension will not work.

Example:

```php
$wgSPARQLEndpoint = 'https://query.portal.mardi4nfdi.de/proxy/wdqs/bigdata/namespace/wdq/sparql';
```

## Development

Run `composer install` in `extensions/SPARQL/` to make the code quality tools available.

### Running Tests and CI Checks

You can use the `Makefile` by running make commands in the `SPARQL` directory.

* `make ci`: Run everything
* `make test`: Run all tests
* `make phpunit --filter FooBar`: run only PHPUnit tests with FooBar in their name
* `make phpcs`: Run all style checks
* `make cs`: Run all style checks and static analysis

### Updating Baseline Files

Sometimes Psalm and PHPStan generate errors or warnings we do not wish to fix.
These can be ignored by adding them to the respective baseline file. You can update
these files with `make stan-baseline` and `make psalm-baseline`.

## Release Notes

### Version 1.0.0 - TBD

* Lua binding `SPARQL.runQuery` to execute SPARQL queries and return the results as a Lua table
* Compatibility with MediaWiki 1.39, 1.40 and 1.41
* Compatibility with PHP 8.1 and 8.2

[Professional.Wiki]: https://professional.wiki
[Wikibase]: https://wikibase.consulting/what-is-wikibase/
[Wikibase hosting]: https://professional.wiki/en/hosting/wikibase
[Wikibase development]: https://professional.wiki/en/wikibase-software-development
[MediaWiki development]: https://professional.wiki/en/mediawiki-development
[Wikibase consulting]: https://wikibase.consulting/
[MediaWiki]: https://www.mediawiki.org
[PHP]: https://www.php.net
[Composer]: https://getcomposer.org
[Composer install]: https://professional.wiki/en/articles/installing-mediawiki-extensions-with-composer
[LocalSettings.php]: https://www.pro.wiki/help/mediawiki-localsettings-php-guide
[Scribunto]: https://www.mediawiki.org/wiki/Extension:Scribunto
[CodeEditor extension]: https://www.mediawiki.org/wiki/Extension:CodeEditor
