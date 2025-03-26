<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

use Throwable;

/**
 * Common interface for identifying all exceptions thrown directly by this
 * library. By extending `Throwable`, this interface is restricted to only
 * being used on classes that extend PHP's exceptions or errors, c.f. the
 * `zend_ce_throwable->interface_gets_implemented` handler
 * `zend_implement_throwable()` in PHP source.
 *
 * @ref https://guzalexander.com/2016/07/23/a-few-notes-on-php-exceptions.html
 */
interface LibraryException extends Throwable {
}
