<?php
namespace NorthslopePL\Metassione2\Tests\Metadata;

use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\Tests\Fixtures\Converter\SimpleKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ChildKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\EmptyKlass;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionProperty;

class ClassPropertyFinderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var ClassPropertyFinder
	 */
	private $classPropertyFinder;

	protected function setUp()
	{
		$this->classPropertyFinder = new ClassPropertyFinder();
	}

	public function testClassWithoutProperties()
	{
		$reflectionClass = new ReflectionClass(EmptyKlass::class);
		$properties = $this->classPropertyFinder->findProperties($reflectionClass);

		$this->assertEquals([], $properties);
	}

	public function testClassWithoutParentClass()
	{
		$reflectionClass = new ReflectionClass(SimpleKlass::class);
		$properties = $this->classPropertyFinder->findProperties($reflectionClass);

		$this->assertCount(5, $properties);
		foreach ($properties as $property) {
			$this->assertInstanceOf(ReflectionProperty::class, $property);
		}

		$this->assertPropertiesArrayContainPropertyNamed('nullValue', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('boolValue', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('intValue', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('floatValue', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('stringValue', $properties);
	}

	public function testClassWithParentAndGrandparentClass()
	{
		$reflectionClass = new ReflectionClass(ChildKlass::class);
		$properties = $this->classPropertyFinder->findProperties($reflectionClass);

		$this->assertCount(4, $properties);
		foreach ($properties as $property) {
			$this->assertInstanceOf(ReflectionProperty::class, $property);
		}

		$this->assertPropertiesArrayContainPropertyNamed('childProperty', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('parentProperty', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('grandparentProperty', $properties);
		$this->assertPropertiesArrayContainPropertyNamed('grandparentProtectedProperty', $properties);
	}

	/**
	 * @param string $propertyName
	 * @param ReflectionProperty[] $properties
	 */
	private function assertPropertiesArrayContainPropertyNamed($propertyName, $properties)
	{
		foreach ($properties as $property) {
			if ($property->getName() === $propertyName) {
				return;
			}
		}

		$this->fail('Property named: ' . $propertyName . ' was not found');
	}

}
