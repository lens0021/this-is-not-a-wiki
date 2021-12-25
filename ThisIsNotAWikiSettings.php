<?php

$wgUseFileCache = true;
$wgFileCacheDepth = 0;
$wgFileCacheDirectory = "/workspace/dist";

$wgCapitalLinks = false;
$wgRestrictDisplayTitle = false;

wfLoadSkin( 'Vector' );
$wgDefaultSkin = 'vector';

wfLoadExtension( 'ThisIsNotAWiki' );
