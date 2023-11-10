<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL;

use MediaWiki\Http\HttpRequestFactory;

class SparqlLuaQueryRunner {

	public function __construct(
		private readonly HttpRequestFactory $requestFactory,
		private readonly string $sparqlEndpoint
	) {
	}

	public function runQuery( string $sparql ): array|null {
		$options = [
			'postData' => [
				'query' => $sparql,
				'format' => 'json'
			],
		];
		$response = $this->requestFactory->request( 'POST', $this->sparqlEndpoint, $options );

		return $response !== null ? json_decode( $response, true ) : null;
	}

}
