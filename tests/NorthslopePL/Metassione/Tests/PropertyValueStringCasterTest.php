<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueStringCasterTest extends \PHPUnit_Framework_TestCase
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

	public function testStringNotNullable()
	{
		$stringProperty = new PropertyDefinition('stringNotNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_STRING, $isNullable = false, $this->reflectionProperty);

		$this->assertSame('1', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 1));
		$this->assertSame('0', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 0));
		//
		$this->assertSame('foobar', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 'foobar'));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, ''));
		$this->assertSame('123.456', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, '123.456'));
		//
		$this->assertSame('12.34', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 12.34));
		$this->assertSame('0', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 0.0));
		//
		$this->assertSame('1', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, true));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, false));
		//
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, new stdClass()));
		//
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, []));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [1, 2, 0]));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, ['aa', 'bb', '']));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [12.34, 56.78, 0.0]));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [true, false]));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [new stdClass(), new stdClass()]));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, null));
		$this->assertSame('', $this->propertyValueCaster->getEmptyBasicValueForProperty($stringProperty));
	}

	public function testStringNullable()
	{
		$stringProperty = new PropertyDefinition('stringNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_STRING, $isNullable = true, $this->reflectionProperty);

		$this->assertSame('1', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 1));
		$this->assertSame('0', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 0));
		//
		$this->assertSame('foobar', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 'foobar'));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, ''));
		$this->assertSame('123.456', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, '123.456'));
		//
		$this->assertSame('12.34', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 12.34));
		$this->assertSame('0', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, 0.0));
		//
		$this->assertSame('1', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, true));
		$this->assertSame('', $this->propertyValueCaster->getBasicValueForProperty($stringProperty, false));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, new stdClass()));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, []));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [1, 2, 0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, ['aa', 'bb', '']));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [true, false]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($stringProperty, null));
		$this->assertSame(null, $this->propertyValueCaster->getEmptyBasicValueForProperty($stringProperty));
	}

	public function testStringArray()
	{
		$stringProperty = new PropertyDefinition('stringArray', true, $isObject = false, $isArray = true, PropertyDefinition::BASIC_TYPE_STRING, $isNullable = false, $this->reflectionProperty);

		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, 1));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, 0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, 'foobar'));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, ''));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, '123.456'));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, 12.34));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, 0.0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, true));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, false));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, new stdClass()));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, []));
		$this->assertSame(['1', '2', '0'], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, [1, 2, 0]));
		$this->assertSame(['aa', 'bb', ''], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, ['aa', 'bb', '']));
		$this->assertSame(['12.34', '56.78', '0'], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(['1', ''], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, [true, false]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, [new stdClass(), new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($stringProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyValueForArrayProperty($stringProperty));
	}
}
