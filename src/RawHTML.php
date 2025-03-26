<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

/**
 * Raw HTML is HTML that should not be escaped because the contents are known
 * to be safe (e.g. the developer writes the HTML manually)
 */
class RawHTML {

	private string $html;

	public function __construct( string $html ) {
		$this->html = $html;
	}

	public function getHTML(): string {
		return $this->html;
	}
}
