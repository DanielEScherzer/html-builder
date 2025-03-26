<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

/**
 * Fluent interface for building HTML
 */
class FluentHTML {

	/** @var (string|RawHTML|FluentHTML)[] */
	private array $contents = [];

	private string $tag;

	/**
	 * @var array<string,string|array|bool>
	 *
	 * Keys are attribute names, values are:
	 * - true: boolean attribute to just put the name in
	 * - false: boolean attribute to omit
	 * - string: put the attribute key="value" where value is escaped with
	 *		   htmlspecialchars
	 * - array: string keys to boolean inclusion or not
	 */
	private array $attributes = [];

	public function __construct( string $tag ) {
		$this->tag = strtolower( $tag );
	}

	/**
	 * Static constructor for chaining
	 */
	public static function fromTag( string $tag ): FluentHTML {
		return new FluentHTML( $tag );
	}

	/**
	 * As a shortcut for arrays, string values with integer keys are the same
	 * as that string being a key with the value of true
	 */
	public function setAttribute(
		string $name,
		string|array|bool $val
	): static {
		// We don't validate that the actual usage of things is strictly
		// correct (e.g. the specific attributes that are allowed for any
		// given element type) but
		// - boolean values can only be used here for boolean attributes
		// - array values can only be used here for space-separated attributes
		if ( is_bool( $val ) && !SpecData::isBooleanAttribute( $name ) ) {
			throw new InapplicableTypeException(
				"Cannot use boolean attribute value for non-boolean " .
				"attribute `$name`"
			);
		} elseif ( is_array( $val ) &&
			!SpecData::isSpaceSeparatedAttribute( $name )
		) {
			throw new InapplicableTypeException(
				"Cannot use array attribute value for non-space-separated " .
				"attribute `$name`"
			);
		}
		// Always stored as arrays
		if ( SpecData::isSpaceSeparatedAttribute( $name ) ) {
			// String: space-separated list
			if ( is_string( $val ) ) {
				$val = explode( ' ', $val );
				$this->attributes[$name] = array_fill_keys( $val, true );
				return $this;
			}
			// Array: for string keys, value is boolean include or not, for int
			// keys, value is string to include
			$kept = [];
			foreach ( $val as $k => $v ) {
				if ( is_int( $k ) && is_string( $v ) ) {
					$kept[$v] = true;
				} elseif ( is_string( $k ) && is_bool( $v ) ) {
					$kept[$k] = $v;
				} else {
					throw new InapplicableTypeException(
						"Array attribute value for attribute `$name` has a " .
						"value of type " . get_debug_type( $v ) . " for key $k"
					);
				}
			}
			$this->attributes[$name] = $kept;
			return $this;
		}
		$this->attributes[$name] = $val;
		return $this;
	}

	/**
	 * Shortcut for setting multiple attributes at once - keys are the attribute
	 * names, values are string|array|bool as per setAttribute()
	 *
	 * @param array<string, string|array|bool> $attributes
	 */
	public function setAttributes( array $attributes ): static {
		foreach ( $attributes as $name => $val ) {
			$this->setAttribute( $name, $val );
		}
		return $this;
	}

	public function addClass( string $className ): static {
		$this->attributes['class'] ??= [];
		$this->attributes['class'][$className] = true;
		return $this;
	}

	public function removeClass( string $className ): static {
		$this->attributes['class'] ??= [];
		$this->attributes['class'][$className] = false;
		return $this;
	}

	public function addChild( string|RawHTML|FluentHTML $html ): static {
		if ( SpecData::isVoidElement( $this->tag ) ) {
			throw new VoidElementChildException(
				"Tag `$this->tag` is a void element and cannot have children"
			);
		}
		$this->contents[] = $html;
		return $this;
	}

	public function getHTML(): string {
		$result = "<";
		$result .= $this->tag;
		foreach ( $this->attributes as $name => $value ) {
			if ( $value === false ) {
				continue;
			}
			$escapedName = htmlspecialchars( $name );
			if ( $value === true ) {
				$result .= " $escapedName";
				continue;
			}
			if ( is_array( $value ) ) {
				$value = array_filter( $value, null, ARRAY_FILTER_USE_KEY );
				$value = array_keys( $value );
				$value = implode( " ", $value );
			}
			if ( is_string( $value ) ) {
				$value = htmlspecialchars( $value );
				$result .= " $escapedName=\"$value\"";
				continue;
			}
			throw new TypeGuardBypassException(
				"Attribute `$name` had unexpected value of type " .
				get_debug_type( $value )
			);
		}
		$result .= ">";
		if ( SpecData::isVoidElement( $this->tag ) ) {
			if ( $this->contents === [] ) {
				return $result;
			}
			// Should have been enforced above, maybe reflection?
			throw new VoidElementChildException(
				"Tag `$this->tag` is a void element and cannot have children"
			);
		}
		foreach ( $this->contents as $child ) {
			if ( $child instanceof RawHTML || $child instanceof FluentHTML ) {
				$result .= $child->getHTML();
				continue;
			}
			if ( is_string( $child ) ) {
				$result .= htmlspecialchars( $child );
				continue;
			}
			throw new TypeGuardBypassException(
				"One of the contents has unexpected value of type " .
				get_debug_type( $child )
			);
		}
		$result .= "</" . $this->tag . ">";
		return $result;
	}
}
