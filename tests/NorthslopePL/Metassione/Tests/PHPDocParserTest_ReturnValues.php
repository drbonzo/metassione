<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\ObjectPropertyType;
use NorthslopePL\Metassione\PHPDocParser;

class PHPDocParserTest_ReturnValues extends \PHPUnit_Framework_TestCase
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
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc(''));
	}

	public function testCommentWithoutTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @return
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithVoidTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @return void
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_NONE);
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithBasicTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @return integer
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_SIMPLE_TYPE, 'integer');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithClassnameSpecified()
	{
		$comment = <<<COMMENT
/**
 * @return NorthslopePL\\Metassione\\Tests\\SimpleKlass
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_OBJECT, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithJustArraySpecifiedThrowsException()
	{
		$comment = <<<COMMENT
/**
 * @return array
 */
COMMENT;

		$this->setExpectedException('NorthslopePL\Metassione\PHPDocParserException');
		$this->parser->getReturnValueTypeFromMethodPhpdoc($comment);
	}

	public function testCommentWithArrayOfSimpleTypes()
	{
		$comment = <<<COMMENT
/**
 * @return array|int[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_SIMPLE_TYPES, 'int');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified()
	{
		$comment = <<<COMMENT
/**
 * @return array|\NorthslopePL\Metassione\Tests\SimpleKlass[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecifiedWithoutArrayKeyword()
	{
		$comment = <<<COMMENT
/**
 * @return \NorthslopePL\Metassione\Tests\SimpleKlass[]
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified_2()
	{
		$comment = <<<COMMENT
/**
 * @return \NorthslopePL\Metassione\Tests\SimpleKlass[]|array
 */
COMMENT;

		$expected = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass');
		$this->assertEquals($expected, $this->parser->getReturnValueTypeFromMethodPhpdoc($comment));
	}
}
