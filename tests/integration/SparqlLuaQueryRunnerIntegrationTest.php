<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL\Tests\Integration;

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestCase;
use ProfessionalWiki\SPARQL\SparqlLuaQueryRunner;

/**
 * @covers \ProfessionalWiki\SPARQL\SparqlLuaQueryRunner
 */
class SparqlLuaQueryRunnerIntegrationTest extends TestCase {

	private function newQueryRunner(): SparqlLuaQueryRunner {
		$sparqlEndpoint = MediaWikiServices::getInstance()->getMainConfig()->get( 'SPARQLEndpoint' );

		if ( !is_string( $sparqlEndpoint ) ) {
			$this->markTestSkipped( 'Missing required config variable SPARQLEndpoint' );
		}

		$requestFactory = MediaWikiServices::getInstance()->getHttpRequestFactory();
		return new SparqlLuaQueryRunner( $requestFactory, $sparqlEndpoint );
	}

	public function testRunQueryWhenSparqlQueryIsEmpty(): void {
		$sparqlQuery = '';
		$result = $this->newQueryRunner()->runQuery( $sparqlQuery );
		$this->assertNull( $result );
	}

	public function testRunQueryReturnsRightHeadVars(): void {
		$sparqlQuery = "SELECT ?subject ?predicate ?object WHERE { ?subject ?predicate ?object } LIMIT 1";
		$data = $this->newQueryRunner()->runQuery( $sparqlQuery );

		$this->assertArrayHasKey( 'head', $data );
		$this->assertArrayHasKey( 'vars', $data['head'] );
		$this->assertSame( [ 'subject', 'predicate', 'object' ], $data['head']['vars'] );
	}

	public function testRunQueryReturnsRightNoOfResults(): void {
		$limit = 3;
		$sparqlQuery = "SELECT ?subject ?predicate ?object WHERE { ?subject ?predicate ?object } LIMIT $limit";
		$responseData = $this->newQueryRunner()->runQuery( $sparqlQuery );

		$this->assertArrayHasKey( 'results', $responseData );
		$this->assertArrayHasKey( 'bindings', $responseData['results'] );
		$this->assertCount( $limit, $responseData['results']['bindings'] );
	}

}
