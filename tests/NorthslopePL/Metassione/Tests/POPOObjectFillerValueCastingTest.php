<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\BasicTypesKlass;
use stdClass;

class POPOObjectFillerValueCastingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	// FIXME objects
	// FIXME arrays
	//
	// FIXME the same with NULLS - copy the test class

	public function testFillingBasicTypesWithBasicTypeValues()
	{
		$targetObject = new BasicTypesKlass();

		$rawData = new stdClass();
		$rawData->stringValue = 'foo';
		$rawData->integerValue = 123;
		$rawData->intValue = 456;
		$rawData->floatValue = 12.34;
		$rawData->doubleValue = 56.78;
		$rawData->booleanValue = true;
		$rawData->boolValue = true;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();
		$expectedObject->stringValue = 'foo';
		$expectedObject->integerValue = 123;
		$expectedObject->intValue = 456;
		$expectedObject->floatValue = 12.34;
		$expectedObject->doubleValue = 56.78;
		$expectedObject->booleanValue = true;
		$expectedObject->boolValue = true;

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingBasicTypesWithObjectValues()
	{
		$targetObject = new BasicTypesKlass();

		$sampleObject = new stdClass();
		$rawData = new stdClass();
		$rawData->stringValue = clone $sampleObject;
		$rawData->integerValue = clone $sampleObject;
		$rawData->intValue = clone $sampleObject;
		$rawData->floatValue = clone $sampleObject;
		$rawData->doubleValue = clone $sampleObject;
		$rawData->booleanValue = clone $sampleObject;
		$rawData->boolValue = clone $sampleObject;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingBasicTypesWithArrayValues()
	{
		$targetObject = new BasicTypesKlass();

		$sampleArray = ['foo' => 'bar'];
		$rawData = new stdClass();
		$rawData->stringValue = $sampleArray;
		$rawData->integerValue = $sampleArray;
		$rawData->intValue = $sampleArray;
		$rawData->floatValue = $sampleArray;
		$rawData->doubleValue = $sampleArray;
		$rawData->booleanValue = $sampleArray;
		$rawData->boolValue = $sampleArray;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

	//

	public function testFillingObjectTypesWithBasicTypeValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$rawData = new stdClass();
		$rawData->stringValue = 'foo';
		$rawData->integerValue = 123;
		$rawData->intValue = 456;
		$rawData->floatValue = 12.34;
		$rawData->doubleValue = 56.78;
		$rawData->booleanValue = true;
		$rawData->boolValue = true;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();
		$expectedObject->stringValue = 'foo';
		$expectedObject->integerValue = 123;
		$expectedObject->intValue = 456;
		$expectedObject->floatValue = 12.34;
		$expectedObject->doubleValue = 56.78;
		$expectedObject->booleanValue = true;
		$expectedObject->boolValue = true;

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingObjectTypesWithObjectValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$sampleObject = new stdClass();
		$rawData = new stdClass();
		$rawData->stringValue = clone $sampleObject;
		$rawData->integerValue = clone $sampleObject;
		$rawData->intValue = clone $sampleObject;
		$rawData->floatValue = clone $sampleObject;
		$rawData->doubleValue = clone $sampleObject;
		$rawData->booleanValue = clone $sampleObject;
		$rawData->boolValue = clone $sampleObject;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingObjectTypesWithArrayValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$sampleArray = ['foo' => 'bar'];
		$rawData = new stdClass();
		$rawData->stringValue = $sampleArray;
		$rawData->integerValue = $sampleArray;
		$rawData->intValue = $sampleArray;
		$rawData->floatValue = $sampleArray;
		$rawData->doubleValue = $sampleArray;
		$rawData->booleanValue = $sampleArray;
		$rawData->boolValue = $sampleArray;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

	//

	public function testFillingArrayTypesWithBasicTypeValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$rawData = new stdClass();
		$rawData->stringValue = 'foo';
		$rawData->integerValue = 123;
		$rawData->intValue = 456;
		$rawData->floatValue = 12.34;
		$rawData->doubleValue = 56.78;
		$rawData->booleanValue = true;
		$rawData->boolValue = true;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();
		$expectedObject->stringValue = 'foo';
		$expectedObject->integerValue = 123;
		$expectedObject->intValue = 456;
		$expectedObject->floatValue = 12.34;
		$expectedObject->doubleValue = 56.78;
		$expectedObject->booleanValue = true;
		$expectedObject->boolValue = true;

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingArrayTypesWithObjectValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$sampleObject = new stdClass();
		$rawData = new stdClass();
		$rawData->stringValue = clone $sampleObject;
		$rawData->integerValue = clone $sampleObject;
		$rawData->intValue = clone $sampleObject;
		$rawData->floatValue = clone $sampleObject;
		$rawData->doubleValue = clone $sampleObject;
		$rawData->booleanValue = clone $sampleObject;
		$rawData->boolValue = clone $sampleObject;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingArrayTypesWithArrayValues()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new BasicTypesKlass();

		$sampleArray = ['foo' => 'bar'];
		$rawData = new stdClass();
		$rawData->stringValue = $sampleArray;
		$rawData->integerValue = $sampleArray;
		$rawData->intValue = $sampleArray;
		$rawData->floatValue = $sampleArray;
		$rawData->doubleValue = $sampleArray;
		$rawData->booleanValue = $sampleArray;
		$rawData->boolValue = $sampleArray;

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new BasicTypesKlass();

		$this->assertEquals($expectedObject, $targetObject);
	}

}
