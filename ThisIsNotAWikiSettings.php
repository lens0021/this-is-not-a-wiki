<?php

wfLoadExtension( 'ThisIsNotAWiki' );

// File caches
$wgUseFileCache = true;
$wgFileCacheDepth = 0;
$wgFileCacheDirectory = '/workspace/dist';

// Contents
$wgCapitalLinks = false;
$wgRestrictDisplayTitle = false;
$wgUseInstantCommons = true;

// Etc
$wgJobRunRate = 0;

// Skin
$wgVectorDefaultSkinVersion = '2';
$wgVectorDefaultSkinVersionForExistingAccounts = '2';
$wgVectorDefaultSkinVersionForNewAccounts = '2';
$wgVectorStickyHeader = [ 'logged_out' => true ];
$wgVectorLanguageInHeader = $wgVectorStickyHeader;
$wgVectorResponsive = true;

// Read configurations from .nowiki.json
if ( file_exists( '/workspace/src/.nowiki.json' ) ) {
	$text = file_get_contents( '/workspace/src/.nowiki.json' );
	$config = json_decode( $text, true );

	// Skins
	if ( isset( $config['skin'] ) ) {
		wfLoadSkin( $config['skin'] );
		$wgDefaultSkin = strtolower( $config['skin'] );
	}

	// Extensions
	if ( isset( $config['extensions'] ) && is_array( $config['extensions'] ) ) {
		wfLoadExtensions( $config['extensions'] );
	}

	// Globals
	if ( isset( $config['wg'] ) ) {
		foreach ( $config['wg'] as $key => $val ) {
			$key = 'wg' . ucfirst( $key );
			$GLOBALS[$key] = $val;
		}
	}
}
