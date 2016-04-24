<?php
namespace NorthslopePL\Metassione2\Tests\Metadata;

use NorthslopePL\Metassione2\Metadata\ClassDefinition;
use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\Metadata\PropertyDefinition;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ArrayPropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ArrayPropertiesNullableKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesWithNullsKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ClassTypesTypePropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\EmptyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SubNamespace\OtherSimpleKlass;
use PHPUnit_Framework_TestCase;

class ClassDefinitionBuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var ClassDefinitionBuilder
	 */
	private $builder;

	protected function setUp()
	{
		$this->builder = new ClassDefinitionBuilder(new ClassPropertyFinder());
	}

	public function testEmptyClass()
	{
		$classDefinition = $this->builder->buildFromClass(EmptyKlass::class);
		$this->assertInstanceOf(ClassDefinition::class, $classDefinition);
		$this->assertEquals(EmptyKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);
		$this->assertEquals([], $classDefinition->properties);
	}

	public function testClassWithBasicTypeProperties()
	{
		$classDefinition = $this->builder->buildFromClass(BasicTypesKlass::class);
		$this->assertEquals(BasicTypesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(10, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringValue', true, false, false, PropertyDefinition::BASIC_TYPE_STRING, false), $classDefinition->properties['stringValue']);
		$this->assertEquals(new PropertyDefinition('integerValue', true, false, false, PropertyDefinition::BASIC_TYPE_INTEGER, false), $classDefinition->properties['integerValue']);
		$this->assertEquals(new PropertyDefinition('intValue', true, false, false, PropertyDefinition::BASIC_TYPE_INT, false), $classDefinition->properties['intValue']);
		$this->assertEquals(new PropertyDefinition('floatValue', true, false, false, PropertyDefinition::BASIC_TYPE_FLOAT, false), $classDefinition->properties['floatValue']);
		$this->assertEquals(new PropertyDefinition('doubleValue', true, false, false, PropertyDefinition::BASIC_TYPE_DOUBLE, false), $classDefinition->properties['doubleValue']);
		$this->assertEquals(new PropertyDefinition('booleanValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOLEAN, false), $classDefinition->properties['booleanValue']);
		$this->assertEquals(new PropertyDefinition('boolValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOL, false), $classDefinition->properties['boolValue']);
		$this->assertEquals(new PropertyDefinition('voidValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['voidValue']);
		$this->assertEquals(new PropertyDefinition('mixedValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['mixedValue']);
		$this->assertEquals(new PropertyDefinition('nullValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['nullValue']);
	}

	public function testClassWithNullableBasicTypeProperties()
	{
		$classDefinition = $this->builder->buildFromClass(BasicTypesWithNullsKlass::class);
		$this->assertEquals(BasicTypesWithNullsKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(10, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringValue', true, false, false, PropertyDefinition::BASIC_TYPE_STRING, true), $classDefinition->properties['stringValue']);
		$this->assertEquals(new PropertyDefinition('integerValue', true, false, false, PropertyDefinition::BASIC_TYPE_INTEGER, true), $classDefinition->properties['integerValue']);
		$this->assertEquals(new PropertyDefinition('intValue', true, false, false, PropertyDefinition::BASIC_TYPE_INT, true), $classDefinition->properties['intValue']);
		$this->assertEquals(new PropertyDefinition('floatValue', true, false, false, PropertyDefinition::BASIC_TYPE_FLOAT, true), $classDefinition->properties['floatValue']);
		$this->assertEquals(new PropertyDefinition('doubleValue', true, false, false, PropertyDefinition::BASIC_TYPE_DOUBLE, true), $classDefinition->properties['doubleValue']);
		$this->assertEquals(new PropertyDefinition('booleanValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOLEAN, true), $classDefinition->properties['booleanValue']);
		$this->assertEquals(new PropertyDefinition('boolValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOL, true), $classDefinition->properties['boolValue']);
		$this->assertEquals(new PropertyDefinition('voidValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['voidValue']);
		$this->assertEquals(new PropertyDefinition('mixedValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['mixedValue']);
		$this->assertEquals(new PropertyDefinition('nullValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true), $classDefinition->properties['nullValue']);
	}

	public function testClassWithClassTypeProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ClassTypesTypePropertiesKlass::class);
		$this->assertEquals(ClassTypesTypePropertiesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(4, $classDefinition->properties);

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyA', true, true, false, SimpleKlass::class, false), $classDefinition->properties['propertyA']);
		// not Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyB', true, true, false, SimpleKlass::class, false), $classDefinition->properties['propertyB']);

		//
		//
		//

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyM', true, true, false, OtherSimpleKlass::class, false), $classDefinition->properties['propertyM']);
		// partialy Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyO', true, true, false, OtherSimpleKlass::class, false), $classDefinition->properties['propertyO']);
	}

	public function testWithArrayProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ArrayPropertiesKlass::class);
		$this->assertEquals(ArrayPropertiesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(7, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringArray_1', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false), $classDefinition->properties['stringArray_1']);
		$this->assertEquals(new PropertyDefinition('stringArray_2', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false), $classDefinition->properties['stringArray_2']);
		$this->assertEquals(new PropertyDefinition('stringArray_3', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false), $classDefinition->properties['stringArray_3']);
		//
		$this->assertEquals(new PropertyDefinition('objectArray_1', true, true, true, SimpleKlass::class, false), $classDefinition->properties['objectArray_1']);
		$this->assertEquals(new PropertyDefinition('objectArray_2', true, true, true, SimpleKlass::class, false), $classDefinition->properties['objectArray_2']);
		$this->assertEquals(new PropertyDefinition('objectArray_3', true, true, true, SimpleKlass::class, false), $classDefinition->properties['objectArray_3']);
		$this->assertEquals(new PropertyDefinition('objectArray_4', true, true, true, SimpleKlass::class, false), $classDefinition->properties['objectArray_4']);
	}

	public function testWithNullableArrayProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ArrayPropertiesNullableKlass::class);
		$this->assertEquals(ArrayPropertiesNullableKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(7, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringArray_1', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true), $classDefinition->properties['stringArray_1']);
		$this->assertEquals(new PropertyDefinition('stringArray_2', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true), $classDefinition->properties['stringArray_2']);
		$this->assertEquals(new PropertyDefinition('stringArray_3', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true), $classDefinition->properties['stringArray_3']);
		//
		$this->assertEquals(new PropertyDefinition('objectArray_1', true, true, true, SimpleKlass::class, true), $classDefinition->properties['objectArray_1']);
		$this->assertEquals(new PropertyDefinition('objectArray_2', true, true, true, SimpleKlass::class, true), $classDefinition->properties['objectArray_2']);
		$this->assertEquals(new PropertyDefinition('objectArray_3', true, true, true, SimpleKlass::class, true), $classDefinition->properties['objectArray_3']);
		$this->assertEquals(new PropertyDefinition('objectArray_4', true, true, true, SimpleKlass::class, true), $classDefinition->properties['objectArray_4']);
	}

}
