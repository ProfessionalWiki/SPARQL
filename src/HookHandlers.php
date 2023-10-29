<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL;

final class HookHandlers {

	/**
	 * @param array<string, string> $extraLibraries
	 */
	public static function onScribuntoExternalLibraries( string $engine, array &$extraLibraries ): void {
		$extraLibraries['SPARQL'] = SparqlLua::class;
	}

}
