<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueObjectCasterTest extends \PHPUnit_Framework_TestCase
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

	public function testObjectNotNullable()
	{
		$objectProperty = new PropertyDefinition('objectNotNullable', true, $isObject = true, $isArray = false, SimpleKlass::class, $isNullable = false, $this->reflectionProperty);

		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 1));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0));
		//
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 'foobar'));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ''));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, '123.456'));
		//
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 12.34));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0.0));
		//
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, true));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, false));
		//
		$obj = new stdClass();
		$obj->name = 'foobar';
		$this->assertSame(new SimpleKlass('foobar'), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, $obj));
		//
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, []));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [1, 2, 0]));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ['aa', 'bb', '']));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [true, false]));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, null));
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getEmptyObjectValueForProperty($objectProperty));
	}

	public function testObjectNullable()
	{
		$objectProperty = new PropertyDefinition('objectNullable', true, $isObject = true, $isArray = false, SimpleKlass::class, $isNullable = true, $this->reflectionProperty);

		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 1));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0));
		//
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 'foobar'));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ''));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, '123.456'));
		//
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 12.34));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0.0));
		//
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, true));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, false));
		//
		$obj = new stdClass();
		$obj->name = 'foobar';
		$this->assertSame(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, $obj));
		//
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, []));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [1, 2, 0]));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ['aa', 'bb', '']));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [true, false]));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(null, $this->propertyValueCaster->getObjectValueForProperty($objectProperty, null));
		$this->assertSame(null, $this->propertyValueCaster->getEmptyObjectValueForProperty($objectProperty));
	}

	public function testObjectArray()
	{
		$objectProperty = new PropertyDefinition('objectArray', true, $isObject = true, $isArray = true, SimpleKlass::class, $isNullable = false, $this->reflectionProperty);

		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, 1));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, 0));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, 'foobar'));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, ''));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, '123.456'));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, 12.34));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, 0.0));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, true));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, false));
		//
		$this->assertSame([new SimpleKlass()], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, new stdClass()));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, []));
		$this->assertSame([true, true, false], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [1, 2, 0]));
		$this->assertSame([true, true, false], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, ['aa', 'bb', ''])); // if 'foobar' is cast to 0 then, ['foobar'] is cast to [0]
		$this->assertSame([true, true, false], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [12.34, 56.78, 0.0]));
		$this->assertSame([true, false], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [true, false]));

		$obj_1 = new stdClass();
		$obj_1->name = 'foo';
		$obj_2 = new stdClass();
		$obj_2->name = 'bar';

		$this->assertSame([new SimpleKlass('foo'), new SimpleKlass('bar')], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [$obj_1, $obj_2]));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyObjectValueForProperty($objectProperty));
	}
}
