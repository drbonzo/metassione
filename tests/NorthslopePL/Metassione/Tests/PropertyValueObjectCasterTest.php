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

		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 1));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0));
		//
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 'foobar'));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ''));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, '123.456'));
		//
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 12.34));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, 0.0));
		//
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, true));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, false));
		//
		$obj = new stdClass();
		$obj->name = 'foobar';
		$objValue = $this->propertyValueCaster->getObjectValueForProperty($objectProperty, $obj);
		$this->assertTrue(is_object($objValue));
		$this->assertEquals('foobar', $objValue->name);

		//
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, []));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [1, 2, 0]));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, ['aa', 'bb', '']));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [12.34, 56.78, 0.0]));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [true, false]));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [new stdClass(), new stdClass()]));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getObjectValueForProperty($objectProperty, null));
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getEmptyObjectValueForProperty($objectProperty));
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
		$objValue = $this->propertyValueCaster->getObjectValueForProperty($objectProperty, $obj);
		$this->assertTrue(is_object($objValue));
		$this->assertEquals('foobar', $objValue->name);
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
		$obj = new stdClass();
		$obj->name = 'foobar';
		$arrayValue = $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [$obj]);

		$this->assertCount(1, $arrayValue);
		$this->assertTrue(is_array($arrayValue));
		$this->assertEquals('foobar', $arrayValue[0]->name);
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, []));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [1, 2, 0]));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, ['aa', 'bb', ''])); // if 'foobar' is cast to 0 then, ['foobar'] is cast to [0]
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [12.34, 56.78, 0.0]));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [true, false]));

		$obj_1 = new stdClass();
		$obj_1->name = 'foo';
		$obj_2 = new stdClass();
		$obj_2->name = 'bar';

		$arrayResult = $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [$obj_1, $obj_2]);
		$this->assertTrue(is_array($arrayResult));
		$this->assertCount(2, $arrayResult);
		$this->assertEquals('foo', $arrayResult[0]->name);
		$this->assertEquals('bar', $arrayResult[1]->name);
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($objectProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyValueForArrayProperty());
	}
}
