<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

use InvalidArgumentException;

/**
 * Exception when using a parameter that generally might have an okay type but
 * in the specific case doesn't
 */
class InapplicableTypeException extends InvalidArgumentException
	implements LibraryException {
}
