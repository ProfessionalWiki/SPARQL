<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL\Tests;

use MediaWiki\Http\HttpRequestFactory;
use PHPUnit\Framework\TestCase;
use ProfessionalWiki\SPARQL\SparqlLuaQueryRunner;

/**
 * @covers SparqlLuaQueryRunner::runQuery
 */
class SparqlLuaQueryRunnerTest extends TestCase {

	public function testRunQuery() {
		$SPARQLEndpoint = 'https://www.example.com';
		$httpRequestFactory = $this->createMock( HttpRequestFactory::class );
		$sparqlLua = new SparqlLuaQueryRunner( $httpRequestFactory, $SPARQLEndpoint );

		$sparql = 'SELECT * WHERE { ?s ?p ?o }';
		$expectedResponse = json_encode( [ 'name' => 'john doe' ] );

		$httpRequestFactory
			->expects( $this->once() )
			->method( 'request' )
			->with(
				'POST',
				$SPARQLEndpoint,
				[
					'postData' => [ 'query' => $sparql, 'format' => 'json' ]
				]
			)
			->willReturn( $expectedResponse );

		$response = $sparqlLua->runQuery( $sparql );
		$this->assertSame( json_decode( $expectedResponse, true ), $response );
	}

}
