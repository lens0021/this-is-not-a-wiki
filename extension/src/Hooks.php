<?php

namespace MediaWiki\Extension\ThisIsNotAWiki;

use Title;

class Hooks implements
	\MediaWiki\Hook\GetLocalURLHook,
	\MediaWiki\Hook\OutputPageAfterGetHeadLinksArrayHook
{

	/** @inheritDoc */
	public function onGetLocalURL( $title, &$url, $query ) {
		$name = Title::makeName( $title->getNamespace(), $title->getDBkey() );
		$url = "./$name.html";
	}

	/** @inheritDoc */
	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out ) {
		foreach( [
			'alternative-edit',
			'opensearch',
			'rsd',
			'universal-edit-button',
			] as $key ) {
			unset( $tags[$key] );
		}
	}
}
