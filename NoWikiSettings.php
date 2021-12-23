<?php

$wgUseFileCache = true;
$wgFileCacheDepth = 0;
$wgFileCacheDirectory = "/workspace/dist";

wfLoadSkin( 'Vector' );
$wgDefaultSkin = 'vector';

wfLoadExtension( 'ThisIsNotAWiki' );
