<?php
namespace NorthslopePL\Metassione2\Tests;

use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\POPOObjectFiller;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\Author;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\Blog;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\Post;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\TestBlogBuilder;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ArrayedKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ChildKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\GrandparentKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass;
use stdClass;

class POPOObjectFillerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->markTestSkipped();
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	public function testFillingArrayOfObjectsProperty()
	{
		$rawBlog = new stdClass();
		$rawPost1 = new stdClass();
		$rawPost1->title = 'title_1';

		$rawPost2 = new stdClass();
		$rawPost2->title = 'title_2';

		$rawPost3 = new stdClass();
		$rawPost3->title = 'title_3';
		$rawBlog->posts = [$rawPost1, $rawPost2, $rawPost3];

		$actualBlog = new Blog();
		$this->objectFiller->fillObjectWithRawData($actualBlog, $rawBlog);

		$expectedBlog = new Blog();
		$expectedBlog->setAuthor(new Author());

		$expectedPost1 = new Post();
		$expectedPost1->setComments([]);
		$expectedPost1->setTitle('title_1');

		$expectedPost2 = new Post();
		$expectedPost2->setTitle('title_2');
		$expectedPost2->setComments([]);

		$expectedPost3 = new Post();
		$expectedPost3->setTitle('title_3');
		$expectedPost3->setComments([]);

		$expectedBlog->setPosts([$expectedPost1, $expectedPost2, $expectedPost3]);

		$this->assertEquals($expectedBlog, $actualBlog);
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
		$this->markTestIncomplete(); // FIXME
		$simpleObject = new BasicTypesKlass();
		$rawData = 'foobar';

		$this->setExpectedException('NorthslopePL\Metassione2\ObjectFillingException', 'Raw data should be an object, but string was given. I was trying to fill object of class NorthslopePL\Metassione2\Tests\Examples\BasicTypesKlass.');

		/** @noinspection PhpParamsInspection */
		$this->objectFiller->fillObjectWithRawData($simpleObject, $rawData);
	}

	public function testFillingObjectWhenItsPropertysClassDoesNotExist()
	{
		$targetObject = new TypeNotFoundKlass();
		$rawData = new stdClass();
		$rawData->foo = 'bar';

		$this->setExpectedException(\LogicException::class, 'Class Foo (NorthslopePL\Metassione2\Tests\Fixtures\Klasses\Foo) not found for property NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass::fooValue');
		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);
	}

	public function testFillingArrays()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new ArrayedKlass();
		$rawData = new stdClass();

		$object1 = new stdClass();
		$object1->value = 1;
		$object2 = new stdClass();
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
		$this->markTestIncomplete(); // FIXME

		$targetObject = new OneObjectPropertyKlass();
		$rawData = new stdClass();
		$rawData->value = (object)array('value' => 42);

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new OneObjectPropertyKlass();
		$expectedSubObject = new OnePropertyKlass();
		$expectedSubObject->setValue(42);
		$expectedObject->setValue($expectedSubObject);

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingObjectWithArrayPropertyDocumentedWithoutArrayKeyword()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new ArrayedKlass();

		$rawData = new stdClass();
		{
			$object1 = new stdClass();
			$object1->value = 1;
			$object2 = new stdClass();
			$object2->value = 2;
		}
		$rawData->invalidSpecificationForObjects = array($object1, $object2);

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expected = new ArrayedKlass();
		$o1 = new OnePropertyKlass();
		$o1->setValue(1);

		$o2 = new OnePropertyKlass();
		$o2->setValue(2);

		$expected->setInvalidSpecificationForObjects(array($o1, $o2));

		$this->assertEquals(print_r($expected, true), print_r($targetObject, true));
		$this->assertEquals($expected, $targetObject);
	}

	public function testFillingObjectUsingDataWithPrivatePropertiesThrowsException()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new OnePropertyKlass();
		$sourceData = new OnePropertyKlass();
		$sourceData->setValue(42);

		$this->setExpectedException('NorthslopePL\Metassione2\ObjectFillingException', 'exception \'ReflectionException\' with message '); // ...
		/** @noinspection PhpParamsInspection */
		$this->objectFiller->fillObjectWithRawData($targetObject, $sourceData);
	}

	public function testUsingUnqalifiedClassname()
	{
		$this->markTestIncomplete(); // FIXME
		$targetObject = new PropertyNotFoundKlass();

		$sourceData = new stdClass();
		$sourceData->one = (object)array('value' => 42);

		$this->setExpectedException(
			'NorthslopePL\Metassione2\ObjectFillingException',
			'Class "OtherOnePropertyKlass" does not exist for property NorthslopePL\Metassione2\Tests\Examples\PropertyNotFoundKlass::$one. Maybe you have forgotten to use fully qualified class name (with namespace, example: \Foo\Bar\OtherOnePropertyKlass)?'
		);
		$this->objectFiller->fillObjectWithRawData($targetObject, $sourceData);
	}

	public function testFillingPropertiesOfParentClasses()
	{
		$this->markTestIncomplete(); // FIXME
		$sourceData = new stdClass();
		$sourceData->childProperty = (object)array('value' => 1);
		$sourceData->parentProperty = (object)array('value' => 2);
		$sourceData->grandparentProperty = (object)array('value' => 3);
		$sourceData->grandparentProtectedProperty = (object)array('value' => 4);

		$actualObject = new ChildKlass();
		$this->objectFiller->fillObjectWithRawData($actualObject, $sourceData);


		$expectedObject = new ChildKlass();
		$prop1 = new OnePropertyKlass();
		$prop1->setValue(1);
		$prop2 = new OnePropertyKlass();
		$prop2->setValue(2);
		$prop3 = new OnePropertyKlass();
		$prop3->setValue(3);
		$prop4 = new OnePropertyKlass();
		$prop4->setValue(4);

		$expectedObject->setChildProperty($prop1);
		$expectedObject->setParentProperty($prop2);
		$expectedObject->setGrandparentProperty($prop3);
		$expectedObject->setGrandparentProtectedProperty($prop4);

		$this->assertEquals(print_r($expectedObject, true), print_r($actualObject, true));
		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testFillingWithNullValueForSomeObjects()
	{
		$this->markTestIncomplete(); // FIXME
		$sourceData = new stdClass();
		$sourceData->grandparentProperty = (object)array('value' => 3);
		$sourceData->grandparentProtectedProperty = null; // there should/can be an object

		$grandparentObject = new GrandparentKlass();
		$this->objectFiller->fillObjectWithRawData($grandparentObject, $sourceData);

		$expectedObject = new GrandparentKlass();
		$prop1 = new OnePropertyKlass();
		$prop1->setValue(3);
		$expectedObject->setGrandparentProperty($prop1);

		$this->assertEquals(print_r($expectedObject, true), print_r($grandparentObject, true));
		$this->assertEquals($expectedObject, $grandparentObject);

		$this->assertNull($grandparentObject->getGrandparentProtectedProperty(), 'No data was given for this property - so set it to null');
	}

	public function testPropertyWithoutFullClassname()
	{
		$this->markTestIncomplete(); // FIXME
		$sourceData = new stdClass();
		$sourceData->child = new stdClass();
		$sourceData->child->childProperty = new stdClass();
		$sourceData->child->childProperty->value = 'child-prop';

		$simpleKlass_1 = new stdClass();
		$simpleKlass_1->intValue = 123;
		$simpleKlass_1->stringValue = 'foo';

		$simpleKlass_2 = new stdClass();
		$simpleKlass_2->intValue = 456;
		$simpleKlass_2->stringValue = 'bar';

		$sourceData->simpleKlasses = [$simpleKlass_1, $simpleKlass_2];

		$targetObject = new PropertyWithoutFullClassname();
		$this->objectFiller->fillObjectWithRawData($targetObject, $sourceData);

		$expectedObject = new PropertyWithoutFullClassname();
		$prop1 = new ChildKlass();
		$prop1_1 = new OnePropertyKlass();
		$prop1_1->setValue('child-prop');
		$prop1->setChildProperty($prop1_1);
		$expectedObject->setChild($prop1);

		$prop2 = [];
		$prop2_1 = new BasicTypesKlass();
		$prop2_1->setIntValue(123);
		$prop2_1->setStringValue('foo');

		$prop2_2 = new BasicTypesKlass();
		$prop2_2->setIntValue(456);
		$prop2_2->setStringValue('bar');

		$prop2[] = $prop2_1;
		$prop2[] = $prop2_2;
		$expectedObject->setSimpleKlasses($prop2);

		$this->assertEquals(print_r($expectedObject, true), print_r($targetObject, true));
		$this->assertEquals($expectedObject, $targetObject);
	}
}
