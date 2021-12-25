<?php

namespace MediaWiki\Extension\ThisIsNotAWiki\Hooks;

use Html;
use Skin;

class Adder implements
	\MediaWiki\Hook\BeforePageDisplayHook,
	\MediaWiki\Hook\SkinAddFooterLinksHook
{

	/** @inheritDoc */
	public function onBeforePageDisplay( $out, $skin ): void {
		$out->addModuleStyles( 'ext.ThisIsNotAWiki.styles' );
	}

	/** @inheritDoc */
	public function onSkinAddFooterLinks( Skin $skin, string $key, array &$footerItems ) {
		global $wgThisIsNotAWikiFooterUrl;

		if ( $key !== 'places' || !$wgThisIsNotAWikiFooterUrl ) {
			return;
		}
		$footerItems['github'] = Html::element(
			'a',
			[ 'href' => $wgThisIsNotAWikiFooterUrl ],
			// TODO: it could not be Github.
			'View project on Github'
		);
	}
}
