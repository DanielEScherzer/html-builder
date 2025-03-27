<?php
declare( strict_types = 1 );

namespace DanielEScherzer\HTMLBuilder\Tests;

use DanielEScherzer\HTMLBuilder\FluentHTML;
use DanielEScherzer\HTMLBuilder\InapplicableTypeException;
use DanielEScherzer\HTMLBuilder\RawHTML;
use DanielEScherzer\HTMLBuilder\TypeGuardBypassException;
use DanielEScherzer\HTMLBuilder\VoidElementChildException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wikimedia\TestingAccessWrapper;

#[CoversClass( FluentHTML::class )]
class FluentHTMLTest extends TestCase {

	public function testBasic() {
		$this->assertSame(
			"<div></div>",
			FluentHTML::fromTag( 'div' )->getHTML()
		);
		$this->assertSame(
			"<hr>",
			FluentHTML::fromTag( 'hr' )->getHTML()
		);
	}

	public function testVoidAttributes() {
		$this->assertSame(
			"<hr foo=\"bar\">",
			FluentHTML::fromTag( 'hr' )
				->setAttribute( 'foo', 'bar' )
				->getHTML()
		);
	}

	public function testVoidChildren() {
		$this->expectException( VoidElementChildException::class );
		$this->expectExceptionMessage(
			"Tag `hr` is a void element and cannot have children"
		);
		FluentHTML::fromTag( 'hr' )->addChild( 'foo' );
	}

	public function testVoidChildren_reflection() {
		$hr = FluentHTML::fromTag( 'hr' );
		TestingAccessWrapper::newFromObject( $hr )->contents = [ 'foo' ];
		$this->expectException( VoidElementChildException::class );
		$this->expectExceptionMessage(
			"Tag `hr` is a void element and cannot have children"
		);
		$hr->getHTML();
	}

	public function testBooleanAttributes() {
		$this->assertSame(
			"<button disabled></button>",
			FluentHTML::fromTag( 'button' )
				->setAttribute( 'disabled', true )
				->setAttribute( 'checked', false )
				->getHTML()
		);
	}

	public function testBooleanAttributes_validation() {
		$this->expectException( InapplicableTypeException::class );
		$this->expectExceptionMessage(
			"Cannot use boolean attribute value for non-boolean attribute " .
			"`example`"
		);
		FluentHTML::fromTag( 'button' )->setAttribute( 'example', false );
	}

	public function testSpaceSeparatedAttributes() {
		$this->assertSame(
			"<div class=\"foo bar\"></div>",
			FluentHTML::fromTag( 'div' )
				->setAttribute( 'class', [ 'foo', 'bar' ] )
				->getHTML()
		);
		$this->assertSame(
			"<div class=\"foo bar\"></div>",
			FluentHTML::fromTag( 'div' )
				->setAttribute( 'class', 'foo bar' )
				->getHTML()
		);
		$this->assertSame(
			"<div class=\"foo bar\"></div>",
			FluentHTML::fromTag( 'div' )
				->setAttribute(
					'class',
					[ 'foo', 'bar' => true, 'baz' => false ]
				)
				->getHTML()
		);
	}

	public function testSpaceSeparatedAttributes_validation() {
		$this->expectException( InapplicableTypeException::class );
		$this->expectExceptionMessage(
			"Cannot use array attribute value for non-space-separated " .
			"attribute `example`"
		);
		FluentHTML::fromTag( 'button' )->setAttribute( 'example', [ 'foo' ] );
	}

	public function testSpaceSeparatedAttributes_validationArray() {
		$this->expectException( InapplicableTypeException::class );
		$this->expectExceptionMessage(
			"Array attribute value for attribute `class` has a value of type " .
			"int for key 0"
		);
		FluentHTML::fromTag( 'div' )->setAttribute( 'class', [ 123 ] );
	}

	public function testStringAttributes() {
		$this->assertSame(
			// phpcs:ignore Generic.Files.LineLength.TooLong
			'<div id="example" data-foo="with&quot;quotes" data-bar="with&gt;brackets"></div>',
			FluentHTML::fromTag( 'div' )
				->setAttribute( 'id', 'example' )
				->setAttribute( 'data-foo', 'with"quotes' )
				->setAttribute( 'data-bar', 'with>brackets' )
				->getHTML()
		);
	}

	public function testSetAttributes() {
		$this->assertSame(
			'<div id="example" checked class="foo bar"></div>',
			FluentHTML::fromTag( 'div' )
				->setAttributes( [
					'id' => 'example',
					'checked' => true,
					'disabled' => false,
					'class' => [ 'foo', 'bar' => true, 'baz' => false ],
				] )
				->getHTML()
		);
	}

	public function testClassUtils() {
		$this->assertSame(
			'<div class="foo"></div>',
			FluentHTML::fromTag( 'div' )->addClass( 'foo' )->getHTML()
		);
		$this->assertSame(
			'<div class="foo bar"></div>',
			FluentHTML::fromTag( 'div' )
				->addClass( 'foo' )
				->addClass( 'bar' )
				->getHTML()
		);
		$this->assertSame(
			'<div class="bar"></div>',
			FluentHTML::fromTag( 'div' )
				->addClass( 'foo' )
				->addClass( 'bar' )
				->removeClass( 'foo' )
				->getHTML()
		);
	}

	public function testChildren() {
		$this->assertSame(
			// phpcs:ignore Generic.Files.LineLength.TooLong
			'<div>Escaped&#039;&quot;&lt;&gt;<p>Paragraph 1</p><p>Paragraph 2</p></div>',
			FluentHTML::fromTag( 'div' )
				->addChild( 'Escaped\'"<>' )
				->addChild( new RawHTML( "<p>Paragraph 1</p>" ) )
				->addChild(
					FluentHTML::fromTag( 'p' )
						->addChild( 'Paragraph 2' )
				)
				->getHTML()
		);
	}

	public function testBypassAttribute() {
		$div = FluentHTML::fromTag( 'div' );
		$access = TestingAccessWrapper::newFromObject( $div );
		$access->attributes = [ 'foo' => 123 ];
		$this->expectException( TypeGuardBypassException::class );
		$this->expectExceptionMessage(
			"Attribute `foo` had unexpected value of type int"
		);
		$div->getHTML();
	}

	public function testBypasChild() {
		$div = FluentHTML::fromTag( 'div' );
		$access = TestingAccessWrapper::newFromObject( $div );
		$access->contents = [ 123 ];
		$this->expectException( TypeGuardBypassException::class );
		$this->expectExceptionMessage(
			"One of the contents has unexpected value of type int"
		);
		$div->getHTML();
	}

	public function testIntegrationCase() {
		$result = FluentHTML::fromTag( 'form' )
			->setAttribute( 'id', 'submission-form' )
			->setAttribute( 'class', [ 'my-project-forms', 'example-form' ] )
			->addChild(
				FluentHTML::fromTag( 'label' )
					->setAttribute( 'for', 'username-input' )
					->addChild(
						'<username>'
					)
			)
			->addChild(
				FluentHTML::fromTag( 'input' )
					->setAttribute( 'type', 'text' )
					->setAttribute( 'name', 'username' )
					->setAttribute( 'id', 'username-input' )
			)
			->addChild(
				FluentHTML::fromTag( 'button' )
					->setAttribute( 'type', 'submit' )
					->setAttribute( 'disabled', true )
					->addClass( 'btn-submit' )
					->addChild( 'Submit' )
			)
			->getHTML();

		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->assertSame(
			'<form id="submission-form" class="my-project-forms example-form">' .
				'<label for="username-input">&lt;username&gt;</label>' .
				'<input type="text" name="username" id="username-input">' .
				'<button type="submit" disabled class="btn-submit">Submit</button>' .
			'</form>',
			$result
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
	}

	public function testIntegrationCase2() {
		$result = FluentHTML::fromTag( 'form' )
			->setAttributes( [
				'id' => 'submission-form',
				'class' => [ 'my-project-forms', 'example-form' ],
			] )
			->append(
				FluentHTML::fromTag( 'label' )
					->setAttribute( 'for', 'username-input' )
					->addChild(
						'<username>'
					),
				FluentHTML::fromTag( 'input' )
					->setAttribute( 'type', 'text' )
					->setAttribute( 'name', 'username' )
					->setAttribute( 'id', 'username-input' ),
				FluentHTML::fromTag( 'button' )
					->setAttribute( 'type', 'submit' )
					->setAttribute( 'disabled', true )
					->addClass( 'btn-submit' )
					->addChild( 'Submit' )
			)
			->getHTML();

		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->assertSame(
			'<form id="submission-form" class="my-project-forms example-form">' .
				'<label for="username-input">&lt;username&gt;</label>' .
				'<input type="text" name="username" id="username-input">' .
				'<button type="submit" disabled class="btn-submit">Submit</button>' .
			'</form>',
			$result
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
	}

}
