<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\TwoBasicPropertyKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\OnlyDefinedBasicTypesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\EmptyKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\UndefinedTypeKlass;
use stdClass;

class POPOObjectFillerBasicExamplesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()), new PropertyValueCaster());
	}

	public function testFillingEmptyKlass()
	{
		$targetObject = new EmptyKlass();
		$rawData = new stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new EmptyKlass();
		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testUnknownPropertiesAreNotAddedToFilledObject()
	{
		$targetObject = new EmptyKlass();
		$rawData = new stdClass();
		$rawData->foo = 'bar';

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expected = new EmptyKlass();
		$this->assertFalse(property_exists($expected, 'foo'));
		$this->assertEquals($expected, $targetObject, '"foo" property should not be added');
	}

	public function testFillingKlassWithPropertiesWithNoData()
	{
		$targetObject = new TwoBasicPropertyKlass();
		$rawData = new stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$this->assertSame('', $targetObject->foo1);
		$this->assertSame('', $targetObject->foo2);
	}

	public function testFillingDefinedPropertiesOfAKlass()
	{
		$simpleObject = new OnlyDefinedBasicTypesKlass();

		$rawData = new stdClass();
		$rawData->stringValue = 'foobar';
		$rawData->integerValue = 123;
		$rawData->intValue = 321;
		$rawData->floatValue = 12.95;
		$rawData->doubleValue = 9.99;
		$rawData->booleanValue = true;
		$rawData->boolValue = true;

		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);

		$expectedObject = new OnlyDefinedBasicTypesKlass();
		$expectedObject->stringValue = 'foobar';
		$expectedObject->integerValue = 123;
		$expectedObject->intValue = 321;
		$expectedObject->floatValue = 12.95;
		$expectedObject->doubleValue = 9.99;
		$expectedObject->booleanValue = true;
		$expectedObject->boolValue = true;

		$this->assertEquals($expectedObject, $simpleObject);
	}

	public function testFillingUndefinedPropertiesOfAKlass()
	{
		$simpleObject = new UndefinedTypeKlass();

		$rawData = new stdClass();
		$rawData->undefinedProperty_1 = 'aaa';
		$rawData->undefinedProperty_2 = 'bbb';
		$rawData->undefinedProperty_3 = 'ccc';
		$rawData->voidValue = 123;
		$rawData->mixedValue = '123';
		$rawData->nullValue = '932';

		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);

		$expectedObject = new UndefinedTypeKlass();
		$expectedObject->undefinedProperty_1 = null;
		$expectedObject->undefinedProperty_2 = null;
		$expectedObject->undefinedProperty_3 = null;
		$expectedObject->voidValue = null;
		$expectedObject->mixedValue = null;
		$expectedObject->nullValue = null;

		$this->assertEquals($expectedObject, $simpleObject, 'undefined properties should not be filled');
	}
}
