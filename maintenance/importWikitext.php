<?php

namespace MediaWiki\Extension\ThisIsNotAWiki;

use CommentStoreComment;
use ContentHandler;
use Maintenance;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use StubGlobalUser;
use Title;
use User;

$IP = strval( getenv( 'MW_INSTALL_PATH' ) ) !== ''
	? getenv( 'MW_INSTALL_PATH' )
	: realpath( __DIR__ . '/../../../' );

require_once "$IP/maintenance/Maintenance.php";

class ImportWikitext extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Import *.wikitext files from the given path' );
		$this->addArg( 'path', 'Path to the directory wikitext files locate' );
	}

	public function execute() {
		$path = $this->getArg( 0 );
		if ( str_ends_with( $path, '/' ) ) {
			$path = rtrim( $path, '/' );
		}

		$slot = SlotRecord::MAIN;

		$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		StubGlobalUser::setUser( $user );

		$status = \StatusValue::newGood();
		foreach ( glob( "$path/*.wikitext" ) as $filename ) {
			$title = Title::newFromText( $this->filenameToTitle( $filename ) );
			if ( !$title ) {
				$this->output( "Invalid title: $title" );
				continue;
			}

			$page = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $title );

			# Read the text
			$text = file_get_contents( $filename );

			$content = ContentHandler::makeContent( $text, $title );

			# Do the edit
			$this->output( "Saving... $title" );
			$updater = $page->newPageUpdater( $user );

			if ( $content === false ) {
				$updater->removeSlot( $slot );
			} else {
				$updater->setContent( $slot, $content );
			}

			$updater->saveRevision( CommentStoreComment::newUnsavedComment( '' ), EDIT_SUPPRESS_RC );
			$subStatus = $updater->getStatus();

			if ( $subStatus->isOK() ) {
				$this->output( " done\n" );
			} else {
				$this->output( " failed\n" );
			}
			$status->merge( $subStatus );
		}

		if ( !$status->isGood() ) {
			$this->output( $status->getMessage( false, false, 'en' )->text() . "\n" );
		}
		return $status->isOK();
	}

	/**
	 * @param string $name
	 * @return string
	 */
  private function filenameToTitle( $name ) {
		$name = basename( $name );
		$name = preg_replace( '/\.wikitext$/', '', $name );
		return $name;
  }
}

$maintClass = ImportWikitext::class;
require_once RUN_MAINTENANCE_IF_MAIN;
