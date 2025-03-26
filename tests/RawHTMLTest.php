<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder\Tests;

use DanielEScherzer\HTMLBuilder\RawHTML;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( RawHTML::class )]
class RawHTMLTest extends TestCase {

	public static function provideTestCases() {
		yield 'text' => [ 'testing 123' ];
		yield 'has single quotes' => [ "foo ' bar ' baz" ];
		yield 'has double quotes' => [ 'foo " bar " baz' ];
		yield 'has both quotes' => [ 'foo \' bar " baz' ];
		yield 'has brackets' => [ '<div id="example>Testing</div>' ];
		yield 'has script' => [ '<script>console.log("testing")</script>' ];
	}

	#[DataProvider( 'provideTestCases' )]
	public function testRawHTML( string $input ) {
		$rawHTML = new RawHTML( $input );
		$this->assertSame( $input, $rawHTML->getHTML() );
	}

}
