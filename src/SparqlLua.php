<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL;

use Scribunto_LuaLibraryBase;

class SparqlLua extends Scribunto_LuaLibraryBase {

	public function register(): array {
		return $this->getEngine()->registerInterface(
			__DIR__ . '/Sparql.lua',
			[
				'runQuery' => fn( string $subjectId ): array => [ $this->runQuery( $subjectId ) ],
			]
		);
	}

	private function runQuery( string $sparql ): array {
		return [ $sparql ]; // TODO
	}

}
