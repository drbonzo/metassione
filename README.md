# metassione


- Allows to convert POPO (**POPO** = Plain Old PHP Object) to stdClass
  - Why? json_encode() does not handle private properties.
  - So you need to have all properties public or use `metassione`.
- and stdClass to POPO
  - convert your JSON to PHP objects with type checking and casting, etc

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
​	
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

## available property types

- basic types
  - `int` | `integer`
  - `string`
  - `float` | `double`
  - `bool` | `boolean`
- arrays of basic types
  - `int[]` | `integer[]`
  - `string[]`
  - `float[]` | `double[]`
  - `bool[]` | `boolean[]`
- classes
  - `FirstClass`
  - `OtherClass`
  - `\Foo\Bar\AnotherClass`
- array of classes (`array` is ignored in `array|Foobar[]` )
  - `FirstClass[]`
  - `OtherClass[]`
  - `\Foo\Bar\AnotherClass[]`

# Changelog

## 0.6.0 Total rewrite

- `Metassione::fillObjectWithRawData($targetObject, stdClass $rawData)` returns `$targetObject`
  - This allows you to write: `$myObject = $metassione->fillObjectWithRawData(new MyKlass(), $rawData);`
  - without storing `new MyKlass()` in temporary variable
- Class metadata retrieval separated from filling the objects (`CachingClassDefinitionBuilder`)
  - Added caching class metadata (only in memory)
- Performance: ~20% slower than [JsonMapper](https://github.com/cweiske/jsonmapper), where metassione 0.4 was 100+% slower
- Improvement in properties handling
  - support for nullable properties (`integer|null`, `FooBar|null`)
  - missing or null value for properties
    - property value is set to `zero` value - for non-nullable properties
      - `integer` -> 0
      - `float` -> 0.0
      - `string` -> ''
      - `boolean` -> false
      - `SomeKlass` -> `new SomeKlass()`
      - any array -> `[]`
    - property value is set to null for *nullable* properties
      - `integer` -> `null`
      - `float` -> `null`
      - `string` -> `null`
      - `boolean` -> `null`
      - `SomeKlass` -> `null`
      - any array -> `[]` (**!!! WARNING: array typed property is always set to empty array, not null. by design**)
- casting values to proper type
  - example: property is of type integer, and `12.95` float is passed. Final object will contain `12` as its property value
- recognizing undefined properties - they will be filled with nulls. When type for property is specified in invalid way - it is treated as undefined and always will be filled with null values.
- still **no** support for importing classes from other namespaces (`use ACME\Foo\Bar` and then `/* @var Bar */`)

### Upgrading 0.4.0 -> 0.6.0

Metassione 0.6.0 is more strict when processing values for properties.
- If you allow nulls for your properties (`integer|null`) - be warned that metassione will set these values to `null` when there is no valid value for property.
- All properties of target class are processed. If no value is found for such property then it will be set to `zero value` or `null`
- In 0.4.0 you could set object to integer property - now it is impossible. Property will get value of 0.0 (or null).

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
