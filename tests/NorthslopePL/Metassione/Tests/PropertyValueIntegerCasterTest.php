<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueIntegerCasterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PropertyValueCaster
	 */
	private $propertyValueCaster;

	/**
	 * Just to build PropertyDefinition
	 * @var ReflectionProperty
	 */
	private $reflectionProperty;

	protected function setUp()
	{
		$this->propertyValueCaster = new PropertyValueCaster();

		$this->reflectionProperty = new ReflectionProperty(SimpleKlass::class, 'name');
	}

	public function testIntegerNotNullable()
	{
		$integerProperty = new PropertyDefinition('integerNotNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_INTEGER, $isNullable = false, $this->reflectionProperty);

		$this->assertSame(1, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 1));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 0));
		//
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 'foobar'));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, ''));
		$this->assertSame(123, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, '123.456'));
		//
		$this->assertSame(12, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 12.34));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 0.0));
		//
		$this->assertSame(1, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, true));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, false));
		//
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, new stdClass()));
		//
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, []));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [1, 2, 0]));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, ['aa', 'bb', '']));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [true, false]));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, null));
		$this->assertSame(0, $this->propertyValueCaster->getEmptyBasicValueForProperty($integerProperty));
	}

	public function testIntegerNullable()
	{
		$integerProperty = new PropertyDefinition('integerNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_INTEGER, $isNullable = true, $this->reflectionProperty);

		$this->assertSame(1, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 1));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 0));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 'foobar'));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, ''));
		$this->assertSame(123, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, '123.456'));
		//
		$this->assertSame(12, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 12.34));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, 0.0));
		//
		$this->assertSame(1, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, true));
		$this->assertSame(0, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, false));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, new stdClass()));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, []));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [1, 2, 0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, ['aa', 'bb', '']));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [true, false]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($integerProperty, null));
		$this->assertSame(null, $this->propertyValueCaster->getEmptyBasicValueForProperty($integerProperty));
	}

	public function testIntegerArray()
	{
		$integerProperty = new PropertyDefinition('integerArray', true, $isObject = false, $isArray = true, PropertyDefinition::BASIC_TYPE_INTEGER, $isNullable = false, $this->reflectionProperty);

		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, 1));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, 0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, 'foobar'));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, ''));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, '123.456'));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, 12.34));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, 0.0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, true));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, false));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, new stdClass()));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, []));
		$this->assertSame([1, 2, 0], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, [1, 2, 0]));
		$this->assertSame([0, 0, 0], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, ['aa', 'bb', ''])); // if 'foobar' is cast to 0 then, ['foobar'] is cast to [0]
		$this->assertSame([12, 56, 0], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, [12.34, 56.78, 0.0]));
		$this->assertSame([1, 0], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, [true, false]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, [new stdClass(), new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($integerProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyValueForArrayProperty($integerProperty));
	}
}
