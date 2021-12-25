<?php

namespace MediaWiki\Extension\ThisIsNotAWiki;

use Config;
use FauxRequest;
use Html;
use OutputPage;
use ResourceLoader;
use ResourceLoaderContext;
use Title;

class Hooks implements
	\MediaWiki\Hook\GetLocalURLHook,
	\MediaWiki\Hook\OutputPageAfterGetHeadLinksArrayHook
{
	/** @var ResourceLoaderContext */
	private $rlClientContext;

	/**
	 * @var string The path to the dist directory.
	 */
	private $dist;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->dist = $config->get( 'ThisIsNotAWikiDist' );
	}

	/** @inheritDoc */
	public function onGetLocalURL( $title, &$url, $query ) {
		$name = Title::makeName( $title->getNamespace(), $title->getDBkey() );
		$url = "./$name.html";
	}

	/** @inheritDoc */
	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out ) {
		// Remove unreachable links, for example, api calls.
		foreach ( [
			'alternative-edit',
			'opensearch',
			'rsd',
			'universal-edit-button',
			] as $key ) {
			unset( $tags[$key] );
		}

		// Links static stylesheet files
		$moduleStyles = $out->getModuleStyles( true );
		$rl = $out->getResourceLoader();
		$context = $this->getRlClientContext( $out );
		$moduleStyles = array_filter( $moduleStyles,
			static function ( $name ) use ( $rl ) {
				$module = $rl->getModule( $name );
				if ( !$module ) {
					return false;
				}
				if ( in_array( $module->getGroup(), [ 'site', 'noscript', 'private', 'user' ] ) ) {
					return false;
				}
				return true;
			}
		);
		foreach ( $moduleStyles as $name ) {
			$module = $out->getResourceLoader()->getModule( $name );
			$group = $module->getGroup();
			if ( !$module->shouldEmbedModule( $context ) ) {
				if ( $group !== 'user' || !$module->isKnownEmpty( $context ) ) {
					$this->addStyleToList( $name );
					$tags[$name] = Html::linkedStyle( "./load/$name.css" );
				}
			}
		}
	}

	/**
	 * @param string $module
	 */
	private function addStyleToList( $name ) {
		$dist = $this->dist;
		if ( !is_dir( $dist ) ) {
			return;
		}

		if ( str_ends_with( $dist, '/' ) ) {
			$dist = rtrim( $dist, '/' );
		}
		$load = "$dist/load";
		if ( !is_dir( $load ) ) {
			mkdir( $load, 0777, true );
		}
		if ( file_exists( "$load/$name" ) ) {
			return;
		}
		touch( "$load/$name.css" );
	}

	/**
	 * @param OutputPage $output
	 * @return ResourceLoaderContext
	 */
	private function getRlClientContext( $output ) {
		if ( !$this->rlClientContext ) {
			$query = ResourceLoader::makeLoaderQuery(
				// modules; not relevant
				[],
				$output->getLanguage()->getCode(),
				$output->getSkin()->getSkinName(),
				null,
				// version; not relevant
				null,
				// inDebugMode
				null,
				// only; not relevant
				null,
				// printable
				false,
				$output->getRequest()->getBool( 'handheld' )
			);
			$this->rlClientContext = new ResourceLoaderContext(
				$output->getResourceLoader(),
				new FauxRequest( $query )
			);
		}
		return $this->rlClientContext;
	}
}
