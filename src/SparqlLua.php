<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL;

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use Scribunto_LuaLibraryBase;

class SparqlLua extends Scribunto_LuaLibraryBase {

	public function register(): array {
		return $this->getEngine()->registerInterface(
			__DIR__ . '/Sparql.lua',
			[
				'runQuery' => fn ( string $subjectId ): array => [ $this->runSparqlQuery( $subjectId ) ],
			]
		);
	}

	private function runSparqlQuery( string $sparql ): array|null {
		$sparqlEndpoint = MediaWikiServices::getInstance()->getMainConfig()->get( 'SPARQLEndpoint' );

		if ( !is_string( $sparqlEndpoint ) ) {
			throw new InvalidArgumentException( 'SPARQLEndpoint must be configured properly' );
		}

		$requestFactory = MediaWikiServices::getInstance()->getHttpRequestFactory();
		$queryRunner = new SparqlLuaQueryRunner( $requestFactory, $sparqlEndpoint );
		return $queryRunner->runQuery( $sparql );
	}

}
