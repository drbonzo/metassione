<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\ObjectPropertyType;
use NorthslopePL\Metassione\PHPDocParser;

class PHPDocParserTest_Properties extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PHPDocParser
	 */
	private $parser;

	protected function setUp()
	{
		$this->parser = new PHPDocParser();
	}

	public function testEmptyCommentGivesNull()
	{
		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc(''));
	}

	public function testCommentWithoutTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithBasicTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var integer
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_SIMPLE_TYPE, 'integer');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithClassnameSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var NorthslopePL\\Metassione\\Tests\\SimpleKlass
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_OBJECT, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithClassnameSpecifiedAndWithDescription()
	{
		$comment = <<<COMMENT
	/**
	 * This is property description.
	 * It has few lines
	 *
	 * @see this should be skipped
	 *
	 * This line should be found in description too.
	 * @var NorthslopePL\\Metassione\\Tests\\SimpleKlass
	 */

COMMENT;

		$expectedDescription = "This is property description.
It has few lines
This line should be found in description too.";
		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_OBJECT, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$expected->setDescription($expectedDescription);
		$actual = $this->parser->getPropertyTypeFromPHPDoc($comment);

		$this->assertEquals($expectedDescription, $actual->getDescription());
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithJustArraySpecifiedThrowsException()
	{
		$comment = <<<COMMENT
/**
 * @var array
 */
COMMENT;

		$this->setExpectedException('NorthslopePL\Metassione\PHPDocParserException');
		$this->parser->getPropertyTypeFromPHPDoc($comment);
	}

	public function testCommentWithArrayOfSimpleTypes()
	{
		$comment = <<<COMMENT
/**
 * @var array|int[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_SIMPLE_TYPES, 'int');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var array|\NorthslopePL\Metassione\Tests\SimpleKlass[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecifiedWithoutArrayKeyword()
	{
		$comment = <<<COMMENT
/**
 * @var \NorthslopePL\Metassione\Tests\SimpleKlass[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified_2()
	{
		$comment = <<<COMMENT
/**
 * @var \NorthslopePL\Metassione\Tests\SimpleKlass[]|array
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getPropertyTypeFromPHPDoc($comment));
	}
}
