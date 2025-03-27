<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

/**
 * Interface for HTML that has been processed in some way by the system and can
 * be trusted, does not need to be escaped again
 */
interface ProcessedHTML {

	public function getHTML(): string;

}
