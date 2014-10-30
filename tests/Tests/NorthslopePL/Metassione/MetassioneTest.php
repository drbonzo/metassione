<?php
namespace Tests\NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metassione;
use Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass;

class MetassioneTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Metassione
	 */
	private $metassione;

	protected function setUp()
	{
		$this->metassione = new Metassione();
	}

	public function testConvertingPOPO()
	{
		$result = $this->metassione->convertToStdClass(new \stdClass());
		$this->assertInstanceOf('\\stdClass', $result);
	}

	public function testFillingPOPO()
	{
		$rawData = new \stdClass();
		$rawData->nullValue = null;
		$rawData->boolValue = true;
		$rawData->intValue = 123;
		$rawData->floatValue = 12.95;
		$rawData->stringValue = 'foobar';

		$simpleObject = new SimpleKlass();
		$this->metassione->fillObjectWithRawData($simpleObject, $rawData);

		$this->assertNull($simpleObject->getNullValue());
		$this->assertTrue($simpleObject->getBoolValue());
		$this->assertEquals(123, $simpleObject->getIntValue());
		$this->assertEquals(12.95, $simpleObject->getFloatValue());
		$this->assertEquals('foobar', $simpleObject->getStringValue());
	}
}
