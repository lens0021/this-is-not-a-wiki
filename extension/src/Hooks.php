<?php

namespace MediaWiki\Extension\ThisIsNotAWiki;

use Title;

class Hooks implements
	\MediaWiki\Hook\GetLocalURLHook
{

	/** @inheritDoc */
	public function onGetLocalURL( $title, &$url, $query ) {
		$name = Title::makeName( $title->getNamespace(), $title->getDBkey() );
		$url = "./$name.html";
	}
}
