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
		$rawData->name = 'foobar';

		$simpleObject = new SimpleKlass();
		$this->metassione->fillObjectWithRawData($simpleObject, $rawData);

		$this->assertEquals('foobar', $simpleObject->getName());
	}
}
