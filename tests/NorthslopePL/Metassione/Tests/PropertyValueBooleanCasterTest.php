<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueBooleanCasterTest extends \PHPUnit_Framework_TestCase
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

	public function testBooleanNotNullable()
	{
		$booleanProperty = new PropertyDefinition('booleanNotNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_BOOLEAN, $isNullable = false, $this->reflectionProperty);

		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 1));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 0));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 'foobar'));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, ''));
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, '123.456'));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 12.34));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 0.0));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, true));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, false));
		//
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, new stdClass()));
		//
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, []));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [1, 2, 0]));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, ['aa', 'bb', '']));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [true, false]));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, null));
		$this->assertSame(false, $this->propertyValueCaster->getEmptyBasicValueForProperty($booleanProperty));
	}

	public function testBooleanNullable()
	{
		$booleanProperty = new PropertyDefinition('booleanNullable', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_BOOLEAN, $isNullable = true, $this->reflectionProperty);

		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 1));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 0));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 'foobar'));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, ''));
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, '123.456'));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 12.34));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, 0.0));
		//
		$this->assertSame(true, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, true));
		$this->assertSame(false, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, false));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, new stdClass()));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, []));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [1, 2, 0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, ['aa', 'bb', '']));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [12.34, 56.78, 0.0]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [true, false]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [new stdClass(), new stdClass()]));
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame(null, $this->propertyValueCaster->getBasicValueForProperty($booleanProperty, null));
		$this->assertSame(null, $this->propertyValueCaster->getEmptyBasicValueForProperty($booleanProperty));
	}

	public function testBooleanArray()
	{
		$booleanProperty = new PropertyDefinition('booleanArray', true, $isObject = false, $isArray = true, PropertyDefinition::BASIC_TYPE_BOOLEAN, $isNullable = false, $this->reflectionProperty);

		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, 1));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, 0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, 'foobar'));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, ''));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, '123.456'));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, 12.34));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, 0.0));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, true));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, false));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, new stdClass()));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, []));
		$this->assertSame([true, true, false], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, [1, 2, 0]));
		$this->assertSame([true, true, false], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, ['aa', 'bb', ''])); // if 'foobar' is cast to 0 then, ['foobar'] is cast to [0]
		$this->assertSame([true, true, false], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, [12.34, 56.78, 0.0]));
		$this->assertSame([true, false], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, [true, false]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, [new stdClass(), new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, [[1, 2], ['a', 'b'], [1.0, 2.0]]));
		//
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($booleanProperty, null));
		$this->assertSame([], $this->propertyValueCaster->getEmptyValueForArrayProperty());
	}
}
