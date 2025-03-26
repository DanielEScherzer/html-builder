<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

use RuntimeException;

/**
 * Exception when trying to add contents to a void element
 */
class VoidElementChildException extends RuntimeException
	implements LibraryException {
}
