<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\PHPDocParser;

class PHPDocParserTest extends \PHPUnit_Framework_TestCase
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
		$this->assertEquals(array(PHPDocParser::TYPE_UNKNOWN, null), $this->parser->getPropertyTypeFromPHPDoc(''));
	}

	public function testCommentWithoutTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_UNKNOWN, null), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithBasicTypeSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var integer
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_OTHER, null), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithClassnameSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var NorthslopePL\\Metassione\\Tests\\SimpleKlass
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_OBJECT, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass'), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithJustArraySpecifiedThrowsException()
	{
		$comment = <<<COMMENT
/**
 * @var array
 */
COMMENT;

		$this->setExpectedException('NorthslopePL\Metassione\PHPDocParserException');
		$this->assertEquals(array(PHPDocParser::TYPE_UNKNOWN, null), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfSimpleTypes()
	{
		$comment = <<<COMMENT
/**
 * @var array|int[]
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_OTHER, null), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified()
	{
		$comment = <<<COMMENT
/**
 * @var array|\NorthslopePL\Metassione\Tests\SimpleKlass[]
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_ARRAY, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass'), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}

	public function testCommentWithArrayOfObjectsSpecified_2()
	{
		$comment = <<<COMMENT
/**
 * @var \NorthslopePL\Metassione\Tests\SimpleKlass[]|array
 */
COMMENT;

		$this->assertEquals(array(PHPDocParser::TYPE_ARRAY, 'NorthslopePL\\Metassione\\Tests\\SimpleKlass'), $this->parser->getPropertyTypeFromPHPDoc($comment));
	}
}
