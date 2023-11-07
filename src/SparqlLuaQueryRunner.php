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

	public function runQuery( string $sparql ): string|null {
		$options = [
			'postData' => [
				'query' => $sparql,
				'format' => 'json'
			],
		];

		return $this->requestFactory->request( 'POST', $this->sparqlEndpoint, $options );
	}

}
