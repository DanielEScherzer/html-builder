<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder\Tests;

use DanielEScherzer\HTMLBuilder\SpecData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Non-exhaustive test cases for SpecData helper methods
 */
#[CoversClass( SpecData::class )]
class SpecDataTest extends TestCase {

	public function testVoidElement() {
		$void = [ 'br', 'hr', 'input', 'link', 'img' ];
		foreach ( $void as $tag ) {
			$this->assertTrue( SpecData::isVoidElement( $tag ) );
			$this->assertTrue( SpecData::isVoidElement( strtoupper( $tag ) ) );
		}
		$notVoid = [ 'div', 'span', 'p', 'h1', 'a' ];
		foreach ( $notVoid as $tag ) {
			$this->assertFalse( SpecData::isVoidElement( $tag ) );
			$this->assertFalse( SpecData::isVoidElement( strtoupper( $tag ) ) );
		}
	}

	public function testBooleanAttribute() {
		$boolAttribute = [ 'checked', 'disabled', 'required', 'selected' ];
		foreach ( $boolAttribute as $attrib ) {
			$this->assertTrue( SpecData::isBooleanAttribute( $attrib ) );
			$this->assertTrue(
				SpecData::isBooleanAttribute( strtoupper( $attrib ) )
			);
		}
		$notBoolAttribute = [ 'id', 'class', 'type', 'value' ];
		foreach ( $notBoolAttribute as $attrib ) {
			$this->assertFalse( SpecData::isBooleanAttribute( $attrib ) );
			$this->assertFalse(
				SpecData::isBooleanAttribute( strtoupper( $attrib ) )
			);
		}
	}

	public function testpaceAttribute() {
		$spaceAttributes = [ 'class', 'for', 'headers' ];
		foreach ( $spaceAttributes as $attrib ) {
			$this->assertTrue(
				SpecData::isSpaceSeparatedAttribute( $attrib )
			);
			$this->assertTrue( SpecData::isSpaceSeparatedAttribute(
				strtoupper( $attrib ) )
			);
		}
		$notSpaceAttributes = [ 'id', 'href', 'type', 'value' ];
		foreach ( $notSpaceAttributes as $attrib ) {
			$this->assertFalse(
				SpecData::isSpaceSeparatedAttribute( $attrib )
			);
			$this->assertFalse(
				SpecData::isSpaceSeparatedAttribute( strtoupper( $attrib ) )
			);
		}
	}

}
