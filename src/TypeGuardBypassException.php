<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

use DomainException;

/**
 * Domain exception: some value was of an unexpected type, should have been
 * prevented earlier due to type checking, either manually or via PHP typehints.
 * Should only be reachable when using reflection.
 */
class TypeGuardBypassException extends DomainException
	implements LibraryException {
}
