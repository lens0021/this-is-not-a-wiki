<?php

namespace MediaWiki\Extension\ThisIsNotAWiki;

use FauxRequest;
use Maintenance;
use MediaWiki\MediaWikiServices;
use ResourceLoader;
use ResourceLoaderContext;

$IP = strval( getenv( 'MW_INSTALL_PATH' ) ) !== ''
	? getenv( 'MW_INSTALL_PATH' )
	: realpath( __DIR__ . '/../../../' );

require_once "$IP/maintenance/Maintenance.php";

class BuildStyles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build styles based on the CSS files on $wgThisIsNotAWikiStyleDirectory.' );
	}

	public function execute() {
		global $wgThisIsNotAWikiHtmlDirectory, $wgThisIsNotAWikiStyleDirectory, $wgLanguageCode, $wgDefaultSkin;

		if ( str_ends_with( $wgThisIsNotAWikiHtmlDirectory, '/' ) ) {
			$wgThisIsNotAWikiHtmlDirectory = rtrim( $wgThisIsNotAWikiHtmlDirectory, '/' );
		}
		if ( str_ends_with( $wgThisIsNotAWikiStyleDirectory, '/' ) ) {
			$wgThisIsNotAWikiStyleDirectory = rtrim( $wgThisIsNotAWikiStyleDirectory, '/' );
		}

		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->disableChronologyProtection();

		$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();

		foreach ( glob( "$wgThisIsNotAWikiHtmlDirectory/$wgThisIsNotAWikiStyleDirectory/*.css" ) as $filename ) {
			$query = ResourceLoader::makeLoaderQuery(
				[ basename( $filename, '.css' ) ],
				$wgLanguageCode,
				$wgDefaultSkin,
				// user
				null,
				// version; not relevant
				null,
				// inDebugMode
				null,
				// only
				'styles',
			);

			$context = new ResourceLoaderContext(
				$resourceLoader,
				new FauxRequest( $query )
			);

			ob_start();
			$resourceLoader->respond( $context );
			$text = ob_get_clean();

			if ( !file_put_contents( $filename, $text, LOCK_EX ) ) {
				wfDebug( __METHOD__ . "() failed saving " . $filename() );
				continue;
			}
		}
	}
}

$maintClass = BuildStyles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
