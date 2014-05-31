<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metassione;

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

		$sampleObject = new SampleClass();
		$this->metassione->fillObjectWithRawData($sampleObject, $rawData);

		$this->assertEquals('foobar', $sampleObject->getName());
	}
}

class SampleClass
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}
