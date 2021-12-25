<?php

namespace MediaWiki\Extension\ThisIsNotAWiki\Hooks;

class Hider implements
	\MediaWiki\Hook\ParserOutputPostCacheTransformHook
{
	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	): void{
		$options['enableSectionEditLinks'] = false;
	}
}
