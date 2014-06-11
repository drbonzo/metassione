<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\Tests\Blog\Blog;
use NorthslopePL\Metassione\Tests\Examples\ArrayedKlass;
use NorthslopePL\Metassione\Tests\Examples\ChildKlass;
use NorthslopePL\Metassione\Tests\Examples\EmptyKlass;
use NorthslopePL\Metassione\Tests\Examples\OneObjectPropertyKlass;
use NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass;
use NorthslopePL\Metassione\Tests\Examples\PropertyNotFoundKlass;
use NorthslopePL\Metassione\Tests\Examples\SimpleKlass;

class POPOObjectFillerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller();
	}

	public function testFillingEmptyKlass()
	{
		$targetObject = new EmptyKlass();
		$rawData = new \stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expected = new EmptyKlass();
		$this->assertEquals($expected, $targetObject);
	}

	public function testFillingEmptyKlassWithUnknownProperties()
	{
		$targetObject = new EmptyKlass();
		$rawData = new \stdClass();
		$rawData->foo = 'bar';

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expected = new EmptyKlass();
		$this->assertEquals($expected, $targetObject, '"foo" property should not be added');
	}

	public function testFillingSimpleKlassWithEmptyData()
	{
		$simpleObject = new SimpleKlass();
		$rawData = new \stdClass();
		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);

		$expectedObject = new SimpleKlass();
		$this->assertEquals($expectedObject, $simpleObject, 'all properties have default values');
	}

	public function testFillingSimpleKlassWithAllItsProperties()
	{
		$simpleObject = new SimpleKlass();

		$rawData = new \stdClass();
		$rawData->nullValue = null;
		$rawData->boolValue = true;
		$rawData->intValue = 42;
		$rawData->floatValue = 12.95;
		$rawData->stringValue = 'foobar';

		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);

		$expectedObject = new SimpleKlass();
		$expectedObject->setNullValue(null);
		$expectedObject->setBoolValue(true);
		$expectedObject->setIntValue(42);
		$expectedObject->setFloatValue(12.95);
		$expectedObject->setStringValue('foobar');

		$this->assertEquals($expectedObject, $simpleObject);
	}

	public function testComplexObjectHierarchyIsFilledWithComplexStdClassesAndArrays()
	{
		$blogBuilder = new TestBlogBuilder();
		$rawBlogData = $blogBuilder->buildBlogAsStdClass();

		$blog = new Blog();
		$this->objectFiller->fillObjectWithRawData($blog, $rawBlogData);

		$expectedBlog = $blogBuilder->buildBlog();

		$this->assertEquals(print_r($expectedBlog, true), print_r($blog, true));
		$this->assertEquals($expectedBlog, $blog);
	}

	public function testFillingSimpleObjectWithRawDataThatIsNotAnObject()
	{
		$simpleObject = new SimpleKlass();
		$rawData = 'foobar';

		$this->setExpectedException('NorthslopePL\Metassione\ObjectFillingException', 'Raw data should be an object, but string was given. I was trying to fill object of class NorthslopePL\Metassione\Tests\Examples\SimpleKlass.');

		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);
	}

	public function testFillingObjectWhenItsPropertysClassDoesNotExist()
	{
		$targetObject = new PropertyNotFoundKlass();
		$rawData = new \stdClass();
		$rawData->foo = 'bar';

		$this->setExpectedException('NorthslopePL\Metassione\ObjectFillingException', 'Class "NorthslopePL\Metassione\Tests\Examples\Foo" does not exist for property NorthslopePL\Metassione\Tests\Examples\PropertyNotFoundKlass::$foo');
		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);
	}

	public function testFillingArrays()
	{
		$targetObject = new ArrayedKlass();
		$rawData = new \stdClass();

		$object1 = new \stdClass();
		$object1->value = 1;
		$object2 = new \stdClass();
		$object2->value = 2;
		$rawData->objects = array($object1, $object2);
		$rawData->objects2 = array($object1, $object2);
		$rawData->objects3 = array($object1, $object2);
		$rawData->numbers = array(4, 5, 6);


		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$object1 = new OnePropertyKlass();
		$object1->setValue(1);
		$object2 = new OnePropertyKlass();
		$object2->setValue(2);

		$expected = new ArrayedKlass();
		$expected->setObjects(array($object1, $object2));
		$expected->setObjects2(array($object1, $object2));
		$expected->setObjects3(array($object1, $object2));
		$expected->setNumbers(array(4, 5, 6));

		$this->assertEquals(print_r($expected, true), print_r($targetObject, true));
		$this->assertEquals($expected, $targetObject);
	}

	public function testSettingPropertyThatIsAnObject()
	{
		$targetObject = new OneObjectPropertyKlass();
		$rawData = new \stdClass();
		$rawData->value = (object)array('value' => 42);

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new OneObjectPropertyKlass();
		$expectedSubObject = new OnePropertyKlass();
		$expectedSubObject->setValue(42);
		$expectedObject->setValue($expectedSubObject);

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingObjectWithIncorrectlyDocumentedArrayProperty()
	{
		$targetObject = new ArrayedKlass();

		$rawData = new \stdClass();
		{
			$object1 = new \stdClass();
			$object1->value = 1;
			$object2 = new \stdClass();
			$object2->value = 2;
		}
		$rawData->invalidSpecificationForObjects = array($object1, $object2);

		$this->setExpectedException('NorthslopePL\Metassione\ObjectFillingException', 'Raw data should be an object, but array was given. I was trying to fill object of class NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass. Maybe you have forgotten about adding "array|" for property that holds this class NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass');
		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);
	}

	public function testFillingObjectUsingDataWithPrivatePropertiesThrowsException()
	{
		$targetObject = new OnePropertyKlass();
		$sourceData = new OnePropertyKlass();
		$sourceData->setValue(42);

		$this->setExpectedException('NorthslopePL\Metassione\ObjectFillingException', 'exception \'ReflectionException\' with message '); // ...
		$this->objectFiller->fillObjectWithRawData($targetObject, $sourceData);
	}

	public function testUsingUnqalifiedClassname()
	{
		$targetObject = new PropertyNotFoundKlass();

		$sourceData = new \stdClass();
		$sourceData->one = (object)array('value' => 42);

		$this->setExpectedException('NorthslopePL\Metassione\ObjectFillingException', 'Class "OnePropertyKlass" does not exist for property NorthslopePL\Metassione\Tests\Examples\PropertyNotFoundKlass::$one. Maybe you have forgotten to use fully qualified class name (with namespace, example: \Foo\Bar\OnePropertyKlass)?');
		$this->objectFiller->fillObjectWithRawData($targetObject, $sourceData);
	}

	public function testFillingPropertiesOfParentClasses()
	{
		$sourceData = new \stdClass();
		$sourceData->childProperty = (object)array('value' => 11);
		$sourceData->parentProperty = (object)array('value' => 22);
		$sourceData->grandparentProperty = (object)array('value' => 33);

		$childObject = new ChildKlass();
		$this->objectFiller->fillObjectWithRawData($childObject, $sourceData);

		$this->assertInstanceOf('NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass', $childObject->getChildProperty());
		$this->assertEquals(11, $childObject->getChildProperty()->getValue());

		$this->assertInstanceOf('NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass', $childObject->getParentProperty());
		$this->assertEquals(22, $childObject->getParentProperty()->getValue());

		$this->assertInstanceOf('NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass', $childObject->getGrandparentProperty());
		$this->assertEquals(33, $childObject->getGrandparentProperty()->getValue());
	}
}
