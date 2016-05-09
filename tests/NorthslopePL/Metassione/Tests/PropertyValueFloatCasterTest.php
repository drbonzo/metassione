<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueFloatCasterTest extends \PHPUnit_Framework_TestCase
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

	public function testFloatNotNullable()
	{
		$floatProperty = new PropertyDefinition('floatNotNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_FLOAT, $isNullable = false, $this->reflectionProperty);

		$this->assertSame(1.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 1));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 0));
		//
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 'foobar'));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, ''));
		$this->assertSame(123.456, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, '123.456'));
		//
		$this->assertSame(12.34, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 12.34));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 0.0));
		//
		$this->assertSame(1.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, true));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, false));
		//
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, new stdClass()));
		//
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, []));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [1, 2, 0]));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, ['aa', 'bb', '']));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [true, false]));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, null));
		$this->assertSame(0.0, $this->propertyValueCaster->getEmptyBasicValueForProperty($floatProperty));
	}

	public function testFloatNullable()
	{
		$floatProperty = new PropertyDefinition('floatNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_FLOAT, $isNullable = true, $this->reflectionProperty);

		$this->assertSame(1.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 1));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 0));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 'foobar'));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, ''));
		$this->assertSame(123.456, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, '123.456'));
		//
		$this->assertSame(12.34, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 12.34));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, 0.0));
		//
		$this->assertSame(1.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, true));
		$this->assertSame(0.0, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, false));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, new stdClass()));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, []));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [1, 2, 0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, ['aa', 'bb', '']));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [true, false]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($floatProperty, null));
		$this->assertSame(null, $this->propertyValueCaster->getEmptyBasicValueForProperty($floatProperty));
	}

	public function testFloatArray()
	{
		$floatProperty = new PropertyDefinition('floatArray', true, $isObject = false, $isArray = true, PropertyDefinition::BASIC_TYPE_FLOAT, $isNullable = false, $this->reflectionProperty);

		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, 1));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, 0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, 'foobar'));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, ''));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, '123.456'));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, 12.34));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, 0.0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, true));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, false));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, new stdClass()));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, []));
		$this->assertSame([1.0, 2.0, 0.0], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, [1, 2, 0]));
		$this->assertSame([0.0, 0.0, 0.0], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, ['aa', 'bb', ''])); // if 'foobar' is cast to 0 then, ['foobar'] is cast to [0]
		$this->assertSame([12.34, 56.78, 0.0], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, [12.34, 56.78, 0.0]));
		$this->assertSame([1.0, 0.0], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, [true, false]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, [new stdClass(), new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($floatProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyValueForArrayProperty());
	}
}
