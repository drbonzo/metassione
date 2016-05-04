<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\Simple\KlassWithStrings;
use stdClass;

class POPOObjectFillerStringValueCastingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	public function testFillingStringTypesWithCorrectTypeValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();
		$rawData->normalProperty = 'foo';
		$rawData->nullableProperty = 'bar';
		$rawData->arrayProperty = ['aaa', 'bbb'];

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = 'foo';
		$expectedObject->nullableProperty = 'bar';
		$expectedObject->arrayProperty = ['aaa', 'bbb'];

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingStringTypesWithObjectValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();
		$rawData->normalProperty = $this->buildObject(['foo' => 'bar']);
		$rawData->nullableProperty = $this->buildObject(['foo' => 'bar']);
		$rawData->arrayProperty = $this->buildObject(['foo' => 'bar']);

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = '';
		$expectedObject->nullableProperty = null;
		$expectedObject->arrayProperty = [];

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingStringTypesWithArrayValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();
		$rawData->normalProperty = ['foo', 'bar'];
		$rawData->nullableProperty = ['foo', 'bar'];
		$rawData->arrayProperty = ['foo', 'bar'];

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = '';
		$expectedObject->nullableProperty = null;
		$expectedObject->arrayProperty = ['foo', 'bar'];

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingStringTypesWithOtherBasicTypeValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();
		$rawData->normalProperty = 12.95;
		$rawData->nullableProperty = true;
		$rawData->arrayProperty = 'foobar';

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = '12.95';
		$expectedObject->nullableProperty = '1';
		$expectedObject->arrayProperty = [];

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingStringTypesWithNoValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = '';
		$expectedObject->nullableProperty = null;
		$expectedObject->arrayProperty = [];

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingStringTypesWithNullValues()
	{
		$targetObject = new KlassWithStrings();

		$rawData = new stdClass();
		$rawData->normalProperty = null;
		$rawData->nullableProperty = null;
		$rawData->arrayProperty = null;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new KlassWithStrings();
		$expectedObject->normalProperty = '';
		$expectedObject->nullableProperty = null;
		$expectedObject->arrayProperty = [];

		$this->assertEquals($expectedObject, $targetObject);
	}

	/**
	 * @param $properties
	 * @return stdClass
	 */
	private function buildObject($properties)
	{
		$object = new stdClass();
		foreach ($properties as $name => $value) {
			$object->{$name} = $value;
		}
		return $object;
	}

}
