<?php

$wgUseFileCache = true;
$wgFileCacheDepth = 0;
$wgFileCacheDirectory = "/workspace/dist";

$wgRestrictDisplayTitle = false;

wfLoadSkin( 'Vector' );
$wgDefaultSkin = 'vector';

wfLoadExtension( 'ThisIsNotAWiki' );
