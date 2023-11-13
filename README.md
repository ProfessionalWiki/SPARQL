# SPARQL

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/SPARQL/ci.yml?branch=master)](https://github.com/ProfessionalWiki/SPARQL/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/ProfessionalWiki/SPARQL/coverage.svg)](https://shepherd.dev/github/ProfessionalWiki/SPARQL)
[![Psalm level](https://shepherd.dev/github/ProfessionalWiki/SPARQL/level.svg)](psalm.xml)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/sparql/version.png)](https://packagist.org/packages/professional-wiki/sparql)
[![Download count](https://poser.pugx.org/professional-wiki/sparql/d/total.png)](https://packagist.org/packages/professional-wiki/sparql)

MediaWiki extension for executing SPARQL queries and templating their results via Lua.

[Professional.Wiki] created and maintains SPARQL. We provide [Wikibase hosting], [Wikibase development]
and [Wikibase consulting].

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

Define a lua module that requires the `SPARQL` binding and uses its runQuery method.

Example: create page `Module:MySPARQL`:

```lua
local sparql = require('SPARQL')
local mwHtml = require('mw.html')

local p = {}


local function trimAndLower(str)
    if str == nil then return nil end
    str = str:gsub("^%s*(.-)%s*$", "%1")  -- Trim spaces from both ends
    return str:lower()  -- Convert to lowercase
end

local function convertJsonToTable(jsonResults)
    local resultsTable = {}
    if jsonResults and jsonResults.results and jsonResults.results.bindings then
        local bindings = jsonResults.results.bindings
        for j=0, #bindings do
            local row = {}
            for key, value in pairs(bindings[j]) do
                table.insert(row, value.value)
            end
            table.insert(resultsTable, row)
        end
    end
    return resultsTable
end

local function createHtmlTable(dataTable, headers, combineFirstTwoColumns)
    local htmlTable = mwHtml.create('table')
    htmlTable:addClass('wikitable'):attr('border', '1')

    if combineFirstTwoColumns and #headers > 1 then
        local headerRow = htmlTable:tag('tr')
        headerRow:tag('th'):wikitext(headers[0] .. " + " .. headers[1])
        for j = 2, #headers do
            headerRow:tag('th'):wikitext(headers[j])
        end
        for _, row in ipairs(dataTable) do
            local dataRow = htmlTable:tag('tr')
            local combinedData = '[' .. row[1] .. ' ' .. row[2] .. ']'
            dataRow:tag('td'):wikitext(combinedData)
            for j = 3, #row do
                dataRow:tag('td'):wikitext(row[j])
            end
        end
    else
        if #headers > 1 then
            local headerRow = htmlTable:tag('tr')
            for j = 0, #headers do
                headerRow:tag('th'):wikitext(headers[j])
            end
        end
        for _, row in ipairs(dataTable) do
            local dataRow = htmlTable:tag('tr')
            for _, data in ipairs(row) do
                dataRow:tag('td'):wikitext(data)
            end
        end
    end

    return tostring(htmlTable)
end

function p.buildTableFromSparql(frame)
    local sparqlQuery = frame.args[1]
    local combineFirstTwoColumns = trimAndLower(frame.args[2]) == "true"
    -- PHP function sparql.runQuery(query) is called
    local jsonResults = sparql.runQuery(sparqlQuery)
    local headers = {}
    if jsonResults and jsonResults.head and jsonResults.head.vars then
        headers = jsonResults.head.vars
    end
    local dataTable = convertJsonToTable(jsonResults)
    return createHtmlTable(dataTable, headers, combineFirstTwoColumns)
end

return p

```

Which can then be invoked via [Scribunto]'s normal mechanisms from within wikitext. Example:

`{{#invoke:MySPARQL|buildTableFromSparql|your SPARQL query here}}`

## Installation

Platform requirements:

* [PHP] 8.1 or later (tested up to 8.2)
* [MediaWiki] 1.39 or later (tested up to 1.40)
* [Scribunto] and lua

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

Run `composer install` in `extensions/SPARQL/` to make the code quality tools available.

### Running tests and CI checks

You can use the `Makefile` by running make commands in the `SPARQL` directory.

* `make ci`: Run everything
* `make test`: Run all tests
* `make phpunit --filter FooBar`: run only PHPUnit tests with FooBar in their name
* `make phpcs`: Run all style checks
* `make cs`: Run all style checks and static analysis

### Updating baseline files

Sometimes Psalm and PHPStan generate errors or warnings we do not wish to fix.
These can be ignored by adding them to the respective baseline file. You can update
these files with `make stan-baseline` and `make psalm-baseline`.

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

[Scribunto]: https://www.mediawiki.org/wiki/Extension:Scribunto
