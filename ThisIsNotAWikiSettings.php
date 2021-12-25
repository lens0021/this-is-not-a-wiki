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
$wgDefaultSkin = 'vector';

wfLoadSkin( 'Vector' );
$wgVectorDefaultSkinVersion = '2';
$wgVectorDefaultSkinVersionForExistingAccounts = '2';
$wgVectorDefaultSkinVersionForNewAccounts = '2';
$wgVectorStickyHeader = [ 'logged_out' => true ];
$wgVectorLanguageInHeader = $wgVectorStickyHeader;
$wgVectorResponsive = true;
