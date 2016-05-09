<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use ReflectionProperty;
use stdClass;

class PropertyValueSpecialCasesCasterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PropertyValueCaster
	 */
	private $propertyValueCaster;

	/**
	 * @var PropertyDefinition
	 */
	private $objectProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $objectNullableProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $objectArrayProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $arrayProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $invalidBasicProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $basicTypeProperty;

	/**
	 * @var PropertyDefinition
	 */
	private $basicTypeNullableProperty;

	protected function setUp()
	{
		$this->propertyValueCaster = new PropertyValueCaster();

		$reflectionProperty = new ReflectionProperty(SimpleKlass::class, 'name'); // Just to build PropertyDefinition

		$nullable = true;
		$nonNullable = false;
		$this->objectProperty = new PropertyDefinition('objectProperty', true, $isObject = true, $isArray = false, SimpleKlass::class, $nonNullable, $reflectionProperty);
		$this->objectNullableProperty = new PropertyDefinition('objectProperty', true, $isObject = true, $isArray = false, SimpleKlass::class, $nullable, $reflectionProperty);
		$this->objectArrayProperty = new PropertyDefinition('objectArrayProperty', true, $isObject = true, $isArray = true, SimpleKlass::class, $nonNullable, $reflectionProperty);
		$this->arrayProperty = new PropertyDefinition('arrayProperty', true, $isObject = false, $isArray = true, PropertyDefinition::BASIC_TYPE_STRING, $nonNullable, $reflectionProperty);
		$this->invalidBasicProperty = new PropertyDefinition('invalidProperty', true, $isObject = false, $isArray = false, 'invalid-type', $nonNullable, $reflectionProperty);
		$this->basicTypeProperty = new PropertyDefinition('basicTypeProperty', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_STRING, $nonNullable, $reflectionProperty);
		$this->basicTypeNullableProperty = new PropertyDefinition('basicTypeProperty', true, $isObject = false, $isArray = false, PropertyDefinition::BASIC_TYPE_STRING, $nullable, $reflectionProperty);
	}

	public function testEdgeCasesForBasicValueForProperty()
	{
		$this->assertNull($this->propertyValueCaster->getBasicValueForProperty($this->objectProperty, 'foo'));
		$this->assertNull($this->propertyValueCaster->getBasicValueForProperty($this->objectArrayProperty, 'foo'));
		$this->assertNull($this->propertyValueCaster->getBasicValueForProperty($this->arrayProperty, 'foo'));
		$this->assertNull($this->propertyValueCaster->getBasicValueForProperty($this->invalidBasicProperty, 'foo'));
	}

	public function testEdgeCasesForEmptyBasicValueForProperty()
	{
		$this->assertNull($this->propertyValueCaster->getEmptyBasicValueForProperty($this->objectProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyBasicValueForProperty($this->objectArrayProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyBasicValueForProperty($this->arrayProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyBasicValueForProperty($this->invalidBasicProperty));
	}

	public function testEdgeCasesForEmptyBasicValueForArrayProperty()
	{
		// returns correct values only for basic typed arrays
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($this->objectProperty, ['aaa']));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($this->objectArrayProperty, ['aaa']));
		$this->assertSame([], $this->propertyValueCaster->getBasicValueForArrayProperty($this->invalidBasicProperty, ['aaa']));
	}

	// =================

	public function testEdgeCasesForObjectValueForProperty()
	{
		$this->assertNull($this->propertyValueCaster->getObjectValueForProperty($this->objectArrayProperty, new stdClass()));
		$this->assertNull($this->propertyValueCaster->getObjectValueForProperty($this->arrayProperty, new stdClass()));
		$this->assertNull($this->propertyValueCaster->getObjectValueForProperty($this->basicTypeProperty, new stdClass()));
	}

	public function testEdgeCasesForEmptyObjectValueForProperty()
	{
		$this->assertNull($this->propertyValueCaster->getEmptyObjectValueForProperty($this->objectArrayProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyObjectValueForProperty($this->arrayProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyObjectValueForProperty($this->basicTypeProperty));
	}

	public function testEdgeCasesForEmptyObjectValueForArrayProperty()
	{
		// returns correct values only for basic typed arrays
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($this->objectProperty, [new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($this->arrayProperty, [new stdClass()]));
		$this->assertSame([], $this->propertyValueCaster->getObjectValueForArrayProperty($this->basicTypeProperty, [new stdClass()]));
	}

	// getEmptyValueForArrayProperty()

	public function testEmptyValueForArrayProperty()
	{
		// basic
		$this->assertSame('', $this->propertyValueCaster->getEmptyValueForArrayProperty($this->basicTypeProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyValueForArrayProperty($this->basicTypeNullableProperty));

		// object
		$this->assertEquals(new SimpleKlass(), $this->propertyValueCaster->getEmptyValueForArrayProperty($this->objectProperty));
		$this->assertNull($this->propertyValueCaster->getEmptyValueForArrayProperty($this->objectNullableProperty));
	}

}
