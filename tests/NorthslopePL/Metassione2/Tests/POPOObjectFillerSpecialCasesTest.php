<?php
namespace NorthslopePL\Metassione2\Tests;

use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\POPOObjectFiller;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\Blog;
use NorthslopePL\Metassione2\Tests\Fixtures\Blog\TestBlogBuilder;
use NorthslopePL\Metassione2\Tests\Fixtures\Builder\BasicTypesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Filler\PrivateAndProtectedPropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ChildKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass;
use stdClass;

class POPOObjectFillerSpecialCasesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	public function testFillingPrivateAndProtectedProperties()
	{
		$targetObject = new PrivateAndProtectedPropertiesKlass();

		$rawData = new stdClass();
		$rawData->privateValue = 'aaa';
		$rawData->protectedValue = 'bbb';
		$rawData->publicValue = 'ccc';

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$this->assertEquals('aaa', $targetObject->getPrivateValue());
		$this->assertEquals('bbb', $targetObject->getProtectedValue());
		$this->assertEquals('ccc', $targetObject->getPublicValue());
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

	public function testFillingPropertiesOfParentClasses()
	{
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

}
