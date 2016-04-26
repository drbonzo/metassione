<?php
namespace NorthslopePL\Metassione2\Tests;

use NorthslopePL\Metassione2\POPOConverter;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\TestBlogBuilder;
use NorthslopePL\Metassione2\Tests\Fixtures\Converter\ArrayedKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Converter\SimpleKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ChildKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\EmptyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass;
use PHPUnit_Framework_TestCase;

class POPOConverterTest extends PHPUnit_Framework_TestCase
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

	public function testConversionOfSimpleClassWithBasicTypeValues()
	{
		$simpleObject = new SimpleKlass();
		$simpleObject->setNullValue(null);
		$simpleObject->setBoolValue(true);
		$simpleObject->setIntValue(42);
		$simpleObject->setFloatValue(12.95);
		$simpleObject->setStringValue('Lorem ipsum');

		$expectedObject = new \stdClass();
		$expectedObject->nullValue = null;
		$expectedObject->boolValue = true;
		$expectedObject->intValue = 42;
		$expectedObject->floatValue = 12.95;
		$expectedObject->stringValue = 'Lorem ipsum';

		$actual = $this->converter->convert($simpleObject);
		$this->assertEquals(print_r($expectedObject, 1), print_r($actual, 1));
		$this->assertEquals($expectedObject, $actual);
	}

	public function testConvertingObjectWithPropertiesOfTypeArrays()
	{
		// input
		$arrayedObject = new ArrayedKlass();
		$arrayedObject->setNumbers([1, 2, 3, 4, 5]);

		$object6 = new OnePropertyKlass();
		$object6->setValue(6);
		$object7 = new OnePropertyKlass();
		$object7->setValue(7);
		$object8 = new OnePropertyKlass();
		$object8->setValue(8);
		$objects = [$object6, $object7, $object8];

		$arrayedObject->setObjects($objects);
		$arrayedObject->setObjects2($objects);
		$arrayedObject->setObjects3($objects);

		// expected
		$expectedObject = new \stdClass();
		$expectedObject->numbers = [1, 2, 3, 4, 5];
		$expectedObject->objects = [];
		$expectedObject->objects[] = (object)['value' => 6];
		$expectedObject->objects[] = (object)['value' => 7];
		$expectedObject->objects[] = (object)['value' => 8];
		$expectedObject->objects2 = [];
		$expectedObject->objects2[] = (object)['value' => 6];
		$expectedObject->objects2[] = (object)['value' => 7];
		$expectedObject->objects2[] = (object)['value' => 8];
		$expectedObject->objects3 = [];
		$expectedObject->objects3[] = (object)['value' => 6];
		$expectedObject->objects3[] = (object)['value' => 7];
		$expectedObject->objects3[] = (object)['value' => 8];
		$expectedObject->invalidSpecificationForObjects = null;

		// result
		$actual = $this->converter->convert($arrayedObject);

		$this->assertEquals(print_r($expectedObject, true), print_r($actual, true));
		$this->assertEquals($expectedObject, $actual);
	}

	public function testComplexObjectHierarchyIsConvertedToComplexStdClassesAndArrays()
	{
		$blogBuilder = new TestBlogBuilder();
		$blog = $blogBuilder->buildBlog();
		$expectedObject = $blogBuilder->buildBlogAsStdClass();

		$actual = $this->converter->convert($blog);
		$this->assertEquals(print_r($expectedObject, true), print_r($actual, true));
		$this->assertEquals($expectedObject, $actual);
	}

	public function testConvertingPrivatePropertiesFromParentClasses()
	{
		$childObject = new ChildKlass();
		$prop1 = new OnePropertyKlass();
		$prop1->setValue(1);
		$prop2 = new OnePropertyKlass();
		$prop2->setValue(2);
		$prop3 = new OnePropertyKlass();
		$prop3->setValue(3);
		$prop4 = new OnePropertyKlass();
		$prop4->setValue(4);

		$childObject->setChildProperty($prop1);
		$childObject->setParentProperty($prop2);
		$childObject->setGrandparentProperty($prop3);
		$childObject->setGrandparentProtectedProperty($prop4);

		$expectedObject = new \stdClass();
		$expectedObject->childProperty = (object)array('value' => 1);
		$expectedObject->parentProperty = (object)array('value' => 2);
		$expectedObject->grandparentProperty = (object)array('value' => 3);
		$expectedObject->grandparentProtectedProperty = (object)array('value' => 4);

		$actual = $this->converter->convert($childObject);

		$this->assertEquals(print_r($expectedObject, true), print_r($actual, true));
		$this->assertEquals($expectedObject, $actual);
	}
}
