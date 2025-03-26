<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder;

/**
 * Collection of data based on the HTML5 spec, and tools to interact with it.
 *
 * Each constant uses array keys and values so that isset() can be used instead
 * of in_array(), which would be slower.
 *
 * All validation is case insensitive.
 *
 * Last updated: 2025-02-25 with data from living standard updated 2025-02-24
 */
class SpecData {

	/**
	 * Section 13.1.2 elements - void elements must not have an end tag.
	 */
	public const array VOID_ELEMENTS = [
		'area' => 'area',
		'base' => 'base',
		'br' => 'br',
		'col' => 'col',
		'embed' => 'embed',
		'hr' => 'hr',
		'img' => 'img',
		'input' => 'input',
		'link' => 'link',
		'meta' => 'meta',
		'source' => 'source',
		'track' => 'track',
		'wbr' => 'wbr',
	];

	/**
	 * Section 2.3.2 describes Boolean attributes, but doesn't list them out
	 * directly. Collected from the non-normative "Attributes" index
	 */
	public const array BOOLEAN_ATTRIBUTES = [
		'allowfullscreen' => 'allowfullscreen',
		'alpha' => 'alpha',
		'async' => 'async',
		'autofocus' => 'autofocus',
		'autoplay' => 'autoplay',
		'checked' => 'checked',
		'controls' => 'controls',
		'default' => 'default',
		'defer' => 'defer',
		'disabled' => 'disabled',
		'formnovalidate' => 'formnovalidate',
		'inert' => 'inert',
		'ismap' => 'ismap',
		'itemscope' => 'itemscope',
		'loop' => 'loop',
		'multiple' => 'multiple',
		'muted' => 'muted',
		'nomodule' => 'nomodule',
		'novalidate' => 'novalidate',
		'open' => 'open',
		'playsinline' => 'playsinline',
		'readonly' => 'readonly',
		'required' => 'required',
		'reversed' => 'reversed',
		'selected' => 'selected',
		'shadowrootclonable' => 'shadowrootclonable',
		'shadowrootdelegatesfocus' => 'shadowrootdelegatesfocus',
		'shadowrootserializable' => 'shadowrootserializable',
	];

	/**
	 * Section 2.3.7 describes space-separated tokens, which are used for the
	 * type of some attributes. List of the actual attributes collected from
	 * the non-normative "Attributes" index
	 */
	public const array SPACE_SEPARATED_ATTRIBUTES = [
		'accesskey' => 'accesskey',
		'blocking' => 'blocking',
		'class' => 'class',
		'for' => 'for',
		'headers' => 'headers',
		'itemprop' => 'itemprop',
		'itemref' => 'itemref',
		'itemtype' => 'itemtype',
		'ping' => 'ping',
		'rel' => 'rel',
		'sandbox' => 'sandbox',
		'sizes' => 'sizes',
	];

	public static function isVoidElement( string $tagName ): bool {
		$tagName = strtolower( $tagName );
		return isset( self::VOID_ELEMENTS[$tagName] );
	}

	public static function isBooleanAttribute( string $attributeName ): bool {
		$attributeName = strtolower( $attributeName );
		return isset( self::BOOLEAN_ATTRIBUTES[$attributeName] );
	}

	public static function isSpaceSeparatedAttribute(
		string $attributeName
	): bool {
		$attributeName = strtolower( $attributeName );
		return isset( self::SPACE_SEPARATED_ATTRIBUTES[$attributeName] );
	}

}
