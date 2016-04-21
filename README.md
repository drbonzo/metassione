metassione
==========

Allows to convert POPO to stdClass, and stdClass to POPO.

Why? json_encode() does not handle private properties. So you need to have all properties public or use `metassione`. 

**POPO** = Plain Old PHP Object

# POPO to stdClass

This allows to convert complex object to stdClass, and later JSON (with `json_encode()`).


## Example

Build object hierarchy

	$post = new \Blog\Post();
	$post->setTitle('il titolo');
	$post->setContents('Lorem ipsum dolor sit amet, consectetur adipiscing elit.
	Ut posuere risus eu commodo fermentum. Nullam nec dignissim est.
	Curabitur adipiscing massa sit amet velit vehicula aliquam.');
	
	{
		$comment_1 = new \Blog\Comment();
		$comment_1->setAuthorName("l'autore 1");
		$comment_1->setContents("Lorem ipsum");
	
		$comment_2 = new \Blog\Comment();
		$comment_2->setAuthorName("l'autore 2");
		$comment_2->setContents("dolor sit amet");
	
		$comments = [$comment_1, $comment_2];
		$post->setComments($comments);
	}
	

Converting to stdClass:

	$metassione = new \NorthslopePL\Metassione\Metassione();
	$rawData = $metassione->convertToStdClass($post);
	print_r($rawData);

gives:

	stdClass Object
	(
	    [title] => il titolo
	    [contents] => Lorem ipsum dolor sit amet, consectetur adipiscing elit.
	Ut posuere risus eu commodo fermentum. Nullam nec dignissim est.
	Curabitur adipiscing massa sit amet velit vehicula aliquam.
	    [comments] => Array
	        (
	            [0] => stdClass Object
	                (
	                    [authorName] => l'autore 1
	                    [contents] => Lorem ipsum
	                )
	
	            [1] => stdClass Object
	                (
	                    [authorName] => l'autore 2
	                    [contents] => dolor sit amet
	                )
	
	        )
	
	)

Then we can convert that to JSON (`JSON_PRETTY_PRINT` = php 5.4+):
	
	$json = json_encode($rawData, JSON_PRETTY_PRINT);
	print($json);
	
which gives:

	{
	    "title": "il titolo",
	    "contents": "Lorem ipsum dolor sit amet, consectetur adipiscing elit.\nUt posuere risus eu commodo fermentum. Nullam nec dignissim est.\nCurabitur adipiscing massa sit amet velit vehicula aliquam.",
	    "comments": [
	        {
	            "authorName": "l'autore 1",
	            "contents": "Lorem ipsum"
	        },
	        {
	            "authorName": "l'autore 2",
	            "contents": "dolor sit amet"
	        }
	    ]
	}


# stdClass to POPO

This allows to convert data from JSON into POPO. Instead of reading data from unspecified objects and arrays - you can use your own classes, type hinting, etc.

With your POPOs:

	$comments = $post->getComments();
	echo $comments[0]->getAuthor();
	echo $comments[0]->getContents();

Compare this to arrays and stdClasses (no type/method hinting):

	echo $post->comments[0]->author;
	echo $post->comments[0]->contents;
	
## Example
	
We are using $rawData from example above, to build the same `\Blog\Post` object:

	$otherPost = new \Blog\Post();
	$metassione->fillObjectWithRawData($otherPost, $rawData);
	
	print_r($otherPost);
	
which gives:

	Blog\Post Object
	(
	    [title:Blog\Post:private] => il titolo
	    [contents:Blog\Post:private] => Lorem ipsum dolor sit amet, consectetur adipiscing elit.
	Ut posuere risus eu commodo fermentum. Nullam nec dignissim est.
	Curabitur adipiscing massa sit amet velit vehicula aliquam.
	    [comments:Blog\Post:private] => Array
	        (
	            [0] => Blog\Comment Object
	                (
	                    [authorName:Blog\Comment:private] => l'autore 1
	                    [contents:Blog\Comment:private] => Lorem ipsum
	                )
	
	            [1] => Blog\Comment Object
	                (
	                    [authorName:Blog\Comment:private] => l'autore 2
	                    [contents:Blog\Comment:private] => dolor sit amet
	                )
	
	        )
	
	)
	
Which is equal to our starting `$post` object.

# why 'metassione'

1. METASSIONE
2. METAdati e di rifleSSIONE
3. metadati e di riflessione
4. translate to english: metadata and reflection

# Building POPOs

To make Metassione work, you need to add typehinting and phpdocs to your classes.

Example:

	<?php
	namespace NorthslopePL\Metassione\Tests\Examples;
	
	class PropertyWithoutFullClassname
	{
		/**
		 * Fully qualified class name - same namespace
		 *
		 * @var \NorthslopePL\Metassione\Tests\Examples\ChildKlass
		 */
		private $firstChild;

		/**
		 * Class from the same namespace as current file.
		 * This is the same as '\NorthslopePL\Metassione\Tests\Examples\ChildKlass'
		 *
		 * @var ChildKlass
		 */
		private $secondChild;

		/**
		 * Fully qualified class name - other namespace
		 *
		 * @var \Other\Lib\ChildKlass
		 */
		private $thirdChild;
		
		/**
		 * Not fully qualified class name - from global namespace
		 * @var SomeClassWithoutNamespace
		 */
		private $fourthChild;
	}
	?>

See **0.4.0 - Properties without full class name may be used**

## available property types

- basic types
	- `int`
	- `string`
	- `float`
	- `bool`
- arrays of basic types
	- `int[]`
	- `string[]`
	- `float[]`
	- `bool[]`
- classes
	- `FirstClass`
	- `OtherClass`
	- `\Foo\Bar\AnotherClass`
- array of classes
	- `FirstClass[]`
	- `OtherClass[]`
	- `\Foo\Bar\AnotherClass[]`

# Changelog

## 2.0.0

- Metassione::fillObjectWithRawData($targetObject, stdClass $rawData) returns $targetObject
	- This allows you to write: $myObject = $metassione->fillObjectWithRawData(new MyKlass(), $rawData);
	- without storing new MyKlass() in temporary variable

## 0.4.0 

### Properties without full class name may be used.

Example:

	<?php
	namespace NorthslopePL\Metassione\Tests\Examples;
	
	class PropertyWithoutFullClassname
	{
		/**
		 * @var \NorthslopePL\Metassione\Tests\Examples\ChildKlass
		 */
		private $firstChild;

		/**
		 * @var ChildKlass
		 */
		private $secondChild;

		/**
		 * @var \Other\Lib\ChildKlass
		 */
		private $thirdChild;
	}
	?>
	
- `$firstChild` - has full classname specified. That classname will be used
- `$secondChild` 
	- has juz `ChildKlass` specified. Attempt to load `NorthslopePL\Metassione\Tests\Examples\ChildKlass` will be made.
	- if `\ChildKlass` is found - it will be used
	- if not, then `NorthslopePL\Metassione\Tests\Examples\ChildKlass` will be used if found
	- else exception will be thrown
- `$thirdChild` - has full classname specified. That classname will be used.


This feature **will not work** with:

- with `use` (`use \Foo\Bar; ... @var Bar`)
- namespace aliases
