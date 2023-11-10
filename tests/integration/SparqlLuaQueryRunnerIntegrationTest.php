<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL\Tests\Integration;

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestCase;
use ProfessionalWiki\SPARQL\SparqlLuaQueryRunner;

/**
 * @covers SparqlLuaQueryRunner
 */
class SparqlLuaQueryRunnerIntegrationTest extends TestCase {

	private SparqlLuaQueryRunner $queryRunner;

	protected function setUp(): void {
		$sparqlEndpoint = MediaWikiServices::getInstance()->getMainConfig()->get( 'SPARQLEndpoint' );

		if ( !is_string( $sparqlEndpoint ) ) {
			$this->markTestSkipped( 'Missing required config variable SPARQLEndpoint' );
		}

		$requestFactory = MediaWikiServices::getInstance()->getHttpRequestFactory();
		$this->queryRunner = new SparqlLuaQueryRunner( $requestFactory, $sparqlEndpoint );
	}

	public function testRunQueryWhenSparqlQueryIsEmpty(): void {
		$sparqlQuery = '';
		$result = $this->queryRunner->runQuery( $sparqlQuery );
		$this->assertNull( $result );
	}

	public function testRunQueryReturnsRightHeadVars(): void {
		$sparqlQuery = "SELECT ?subject ?predicate ?object WHERE { ?subject ?predicate ?object } LIMIT 1";
		$data = $this->queryRunner->runQuery( $sparqlQuery );

		$this->assertArrayHasKey( 'head', $data );
		$this->assertArrayHasKey( 'vars', $data['head'] );
		$this->assertSame( [ 'subject', 'predicate', 'object' ], $data['head']['vars'] );
	}

	public function testRunQueryReturnsRightNoOfResults(): void {
		$limit = 3;
		$sparqlQuery = "SELECT ?subject ?predicate ?object WHERE { ?subject ?predicate ?object } LIMIT $limit";
		$responseData = $this->queryRunner->runQuery( $sparqlQuery );

		$this->assertArrayHasKey( 'results', $responseData );
		$this->assertArrayHasKey( 'bindings', $responseData['results'] );
		$this->assertCount( $limit, $responseData['results']['bindings'] );
	}

}
