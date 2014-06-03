<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\POPOConverter;

class POPOConverterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOConverter
	 */
	private $converter;

	protected function setUp()
	{
		$this->converter = new POPOConverter();
	}

	public function testConvertingEmptyObjectGivesEmptyStdClass()
	{
		$emptyObject = new EmptyKlass();
		$result = $this->converter->convert($emptyObject);

		$this->assertEquals(new \stdClass(), $result);
	}

	public function testConvertingArrayOfEmptyObjectsGivesArrayOfEmptyStdClasses()
	{
		$emptyObjects = [new EmptyKlass(), new EmptyKlass(), new EmptyKlass()];
		$expected = [new \stdClass(), new \stdClass(), new \stdClass()];

		$this->assertEquals($expected, $this->converter->convert($emptyObjects));
	}

	/**
	 * @param $value
	 * @dataProvider convertingNotObjectNorArrayThrowsExceptionDataProvider
	 */
	public function testConvertingNotObjectNorArrayThrowsException($value)
	{
		$this->setExpectedException('NorthslopePL\Metassione\ConversionException');
		$this->converter->convert($value);
	}

	public function convertingNotObjectNorArrayThrowsExceptionDataProvider()
	{
		return [
			[null],
			[true],
			[false],
			[10],
			[12.345],
			['foobar']
		];
	}

	// FIXME 1 level, int, string, float, bool
	// FIXME 1 level, array|string[]
	// FIXME 1 level, object
	// FIXME 1 array of objects with full classname
	// FIXME many levels of objects (3) Blog, Post, Comment

}

