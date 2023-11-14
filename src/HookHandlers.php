<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\SPARQL;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Http\Telemetry;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

final class HookHandlers {

	/**
	 * @param array<string, string> &$extraLibraries
	 */
	public static function onScribuntoExternalLibraries( string $engine, array &$extraLibraries ): void {
		$extraLibraries['SPARQL'] = SparqlLua::class;
	}

	/**
	 * @param array<string, mixed> &$globals
	 */
	public static function onParserTestGlobals( array &$globals ): void {
		MediaWikiServices::getInstance()->resetServiceForTesting( 'HttpRequestFactory' );

		if ( version_compare( MW_VERSION, '1.41', '<' ) ) {
			MediaWikiServices::getInstance()->redefineService(
				'HttpRequestFactory',
				// The below function is copied from ServiceWiring.php. Not sure how to access the original function.
				static function ( MediaWikiServices $services ): HttpRequestFactory {
					return new HttpRequestFactory(
						new ServiceOptions(
							HttpRequestFactory::CONSTRUCTOR_OPTIONS,
							$services->getMainConfig()
						),
						LoggerFactory::getInstance( 'http' )
					);
				},
			);

			return;
		}

		MediaWikiServices::getInstance()->redefineService(
			'HttpRequestFactory',
			// The below function is copied from ServiceWiring.php. Not sure how to access the original function.
			static function ( MediaWikiServices $services ): HttpRequestFactory {
				return new HttpRequestFactory(
					new ServiceOptions(
						HttpRequestFactory::CONSTRUCTOR_OPTIONS,
						$services->getMainConfig()
					),
					LoggerFactory::getInstance( 'http' ),
					Telemetry::getInstance()
				);
			}
		);
	}

}
