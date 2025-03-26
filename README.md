# HTML Builder

## About

This composer library implements a set of utilities for build HTML output in
PHP. It provides a fluent interface for manipulating attributes and building up
the output.

## Installation

The library is meant to be installed via composer, e.g. using
`composer require danielescherzer/html-builder`.

## Usage

Strings used are escaped automatically, special handling is available for
boolean and space-separated attributes. The primary entry point is the use of
the `FluentHTML` class:

```php
use DanielEScherzer\HTMLBuilder\FluentHTML;

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
```

results in the following HTML (split across multiple lines for readability):

```html
<form id="submission-form" class="my-project-forms example-form">
    <label for="username-input">&lt;username&gt;</label>
    <input type="text" name="username" id="username-input">
    <button type="submit" disabled class="btn-submit">Submit</button>
</form>
```
