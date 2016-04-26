<?php
namespace NorthslopePL\Metassione2\Tests\Metadata;

use LogicException;
use NorthslopePL\Metassione2\Metadata\ClassDefinition;
use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\Metadata\PropertyDefinition;
use NorthslopePL\Metassione2\Tests\Fixtures\Builder\UndefinedTypeKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ArrayPropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ArrayPropertiesNullableKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesWithNullsKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ClassTypesTypeNullablePropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\ClassTypesTypePropertiesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\EmptyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SubNamespace\OtherSimpleKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass2;
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

		$this->assertEquals(new PropertyDefinition('stringValue', true, false, false, PropertyDefinition::BASIC_TYPE_STRING, false, new \ReflectionProperty(BasicTypesKlass::class, 'stringValue')), $classDefinition->properties['stringValue']);
		$this->assertEquals(new PropertyDefinition('integerValue', true, false, false, PropertyDefinition::BASIC_TYPE_INTEGER, false, new \ReflectionProperty(BasicTypesKlass::class, 'integerValue')),  $classDefinition->properties['integerValue']);
		$this->assertEquals(new PropertyDefinition('intValue', true, false, false, PropertyDefinition::BASIC_TYPE_INT, false, new \ReflectionProperty(BasicTypesKlass::class, 'intValue')), $classDefinition->properties['intValue']);
		$this->assertEquals(new PropertyDefinition('floatValue', true, false, false, PropertyDefinition::BASIC_TYPE_FLOAT, false, new \ReflectionProperty(BasicTypesKlass::class, 'floatValue')), $classDefinition->properties['floatValue']);
		$this->assertEquals(new PropertyDefinition('doubleValue', true, false, false, PropertyDefinition::BASIC_TYPE_DOUBLE, false, new \ReflectionProperty(BasicTypesKlass::class, 'doubleValue')), $classDefinition->properties['doubleValue']);
		$this->assertEquals(new PropertyDefinition('booleanValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOLEAN, false, new \ReflectionProperty(BasicTypesKlass::class, 'booleanValue')), $classDefinition->properties['booleanValue']);
		$this->assertEquals(new PropertyDefinition('boolValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOL, false, new \ReflectionProperty(BasicTypesKlass::class, 'boolValue')), $classDefinition->properties['boolValue']);
		$this->assertEquals(new PropertyDefinition('voidValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesKlass::class, 'voidValue')), $classDefinition->properties['voidValue']);
		$this->assertEquals(new PropertyDefinition('mixedValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesKlass::class, 'mixedValue')), $classDefinition->properties['mixedValue']);
		$this->assertEquals(new PropertyDefinition('nullValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesKlass::class, 'nullValue')), $classDefinition->properties['nullValue']);
	}

	public function testClassWithNullableBasicTypeProperties()
	{
		$classDefinition = $this->builder->buildFromClass(BasicTypesWithNullsKlass::class);
		$this->assertEquals(BasicTypesWithNullsKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(10, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringValue', true, false, false, PropertyDefinition::BASIC_TYPE_STRING, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'stringValue')),$classDefinition->properties['stringValue']);
		$this->assertEquals(new PropertyDefinition('integerValue', true, false, false, PropertyDefinition::BASIC_TYPE_INTEGER, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'integerValue')),$classDefinition->properties['integerValue']);
		$this->assertEquals(new PropertyDefinition('intValue', true, false, false, PropertyDefinition::BASIC_TYPE_INT, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'intValue')),$classDefinition->properties['intValue']);
		$this->assertEquals(new PropertyDefinition('floatValue', true, false, false, PropertyDefinition::BASIC_TYPE_FLOAT, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'floatValue')),$classDefinition->properties['floatValue']);
		$this->assertEquals(new PropertyDefinition('doubleValue', true, false, false, PropertyDefinition::BASIC_TYPE_DOUBLE, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'doubleValue')),$classDefinition->properties['doubleValue']);
		$this->assertEquals(new PropertyDefinition('booleanValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOLEAN, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'booleanValue')),$classDefinition->properties['booleanValue']);
		$this->assertEquals(new PropertyDefinition('boolValue', true, false, false, PropertyDefinition::BASIC_TYPE_BOOL, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'boolValue')),$classDefinition->properties['boolValue']);
		$this->assertEquals(new PropertyDefinition('voidValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'voidValue')),$classDefinition->properties['voidValue']);
		$this->assertEquals(new PropertyDefinition('mixedValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'mixedValue')),$classDefinition->properties['mixedValue']);
		$this->assertEquals(new PropertyDefinition('nullValue', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(BasicTypesWithNullsKlass::class, 'nullValue')),$classDefinition->properties['nullValue']);
	}

	public function testClassWithClassTypeProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ClassTypesTypePropertiesKlass::class);
		$this->assertEquals(ClassTypesTypePropertiesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(4, $classDefinition->properties);

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyA', true, true, false, SimpleKlass::class, false, new \ReflectionProperty(ClassTypesTypePropertiesKlass::class, 'propertyA')),$classDefinition->properties['propertyA']);
		// not Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyB', true, true, false, SimpleKlass::class, false, new \ReflectionProperty(ClassTypesTypePropertiesKlass::class, 'propertyB')),$classDefinition->properties['propertyB']);

		//

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyM', true, true, false, OtherSimpleKlass::class, false, new \ReflectionProperty(ClassTypesTypePropertiesKlass::class, 'propertyM')),$classDefinition->properties['propertyM']);
		// partialy Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyO', true, true, false, OtherSimpleKlass::class, false, new \ReflectionProperty(ClassTypesTypePropertiesKlass::class, 'propertyO')),$classDefinition->properties['propertyO']);
	}

	public function testClassWithClassTypeNullableProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ClassTypesTypeNullablePropertiesKlass::class);
		$this->assertEquals(ClassTypesTypeNullablePropertiesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(4, $classDefinition->properties);

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyA', true, true, false, SimpleKlass::class, true, new \ReflectionProperty(ClassTypesTypeNullablePropertiesKlass::class, 'propertyA')),$classDefinition->properties['propertyA']);
		// not Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyB', true, true, false, SimpleKlass::class, true, new \ReflectionProperty(ClassTypesTypeNullablePropertiesKlass::class, 'propertyB')),$classDefinition->properties['propertyB']);

		//

		// Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyM', true, true, false, OtherSimpleKlass::class, true, new \ReflectionProperty(ClassTypesTypeNullablePropertiesKlass::class, 'propertyM')),$classDefinition->properties['propertyM']);
		// partialy Fully Qualified Class name
		$this->assertEquals(new PropertyDefinition('propertyO', true, true, false, OtherSimpleKlass::class, true, new \ReflectionProperty(ClassTypesTypeNullablePropertiesKlass::class, 'propertyO')),$classDefinition->properties['propertyO']);
	}

	public function testWithArrayProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ArrayPropertiesKlass::class);
		$this->assertEquals(ArrayPropertiesKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(7, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringArray_1', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'stringArray_1')),$classDefinition->properties['stringArray_1']);
		$this->assertEquals(new PropertyDefinition('stringArray_2', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'stringArray_2')),$classDefinition->properties['stringArray_2']);
		$this->assertEquals(new PropertyDefinition('stringArray_3', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'stringArray_3')),$classDefinition->properties['stringArray_3']);
		//
		$this->assertEquals(new PropertyDefinition('objectArray_1', true, true, true, SimpleKlass::class, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'objectArray_1')),$classDefinition->properties['objectArray_1']);
		$this->assertEquals(new PropertyDefinition('objectArray_2', true, true, true, SimpleKlass::class, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'objectArray_2')),$classDefinition->properties['objectArray_2']);
		$this->assertEquals(new PropertyDefinition('objectArray_3', true, true, true, SimpleKlass::class, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'objectArray_3')),$classDefinition->properties['objectArray_3']);
		$this->assertEquals(new PropertyDefinition('objectArray_4', true, true, true, SimpleKlass::class, false, new \ReflectionProperty(ArrayPropertiesKlass::class, 'objectArray_4')),$classDefinition->properties['objectArray_4']);
	}

	public function testWithNullableArrayProperties()
	{
		$classDefinition = $this->builder->buildFromClass(ArrayPropertiesNullableKlass::class);
		$this->assertEquals(ArrayPropertiesNullableKlass::class, $classDefinition->name);
		$this->assertEquals('NorthslopePL\Metassione2\Tests\Fixtures\Klasses', $classDefinition->namespace);

		$this->assertCount(7, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('stringArray_1', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'stringArray_1')),$classDefinition->properties['stringArray_1']);
		$this->assertEquals(new PropertyDefinition('stringArray_2', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'stringArray_2')),$classDefinition->properties['stringArray_2']);
		$this->assertEquals(new PropertyDefinition('stringArray_3', true, false, true, PropertyDefinition::BASIC_TYPE_STRING, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'stringArray_3')),$classDefinition->properties['stringArray_3']);
		//
		$this->assertEquals(new PropertyDefinition('objectArray_1', true, true, true, SimpleKlass::class, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'objectArray_1')),$classDefinition->properties['objectArray_1']);
		$this->assertEquals(new PropertyDefinition('objectArray_2', true, true, true, SimpleKlass::class, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'objectArray_2')),$classDefinition->properties['objectArray_2']);
		$this->assertEquals(new PropertyDefinition('objectArray_3', true, true, true, SimpleKlass::class, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'objectArray_3')),$classDefinition->properties['objectArray_3']);
		$this->assertEquals(new PropertyDefinition('objectArray_4', true, true, true, SimpleKlass::class, true, new \ReflectionProperty(ArrayPropertiesNullableKlass::class, 'objectArray_4')),$classDefinition->properties['objectArray_4']);
	}

	public function testWithUndefinedPropertyType()
	{
		$classDefinition = $this->builder->buildFromClass(UndefinedTypeKlass::class);
		$this->assertEquals(UndefinedTypeKlass::class, $classDefinition->name);

		$this->assertCount(3, $classDefinition->properties);

		$this->assertEquals(new PropertyDefinition('undefinedProperty_1', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(UndefinedTypeKlass::class, 'undefinedProperty_1')),$classDefinition->properties['undefinedProperty_1']);
		$this->assertEquals(new PropertyDefinition('undefinedProperty_2', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(UndefinedTypeKlass::class, 'undefinedProperty_2')),$classDefinition->properties['undefinedProperty_2']);
		$this->assertEquals(new PropertyDefinition('undefinedProperty_3', false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, new \ReflectionProperty(UndefinedTypeKlass::class, 'undefinedProperty_3')),$classDefinition->properties['undefinedProperty_3']);
	}

	public function testWithNotFoundClassProperty()
	{
		$this->setExpectedException(
			LogicException::class,
			'Class Foo (NorthslopePL\Metassione2\Tests\Fixtures\Klasses\Foo) not found for property NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass::fooValue'
		);

		$this->builder->buildFromClass(TypeNotFoundKlass::class);
	}

	public function testWithNotFoundClassProperty2()
	{
		$this->setExpectedException(
			LogicException::class,
			'Class NorthslopePL\Metassione2\Tests\Fixtures\Klasses\Foo (NorthslopePL\Metassione2\Tests\Fixtures\Klasses\NorthslopePL\Metassione2\Tests\Fixtures\Klasses\Foo) not found for property NorthslopePL\Metassione2\Tests\Fixtures\Klasses\TypeNotFoundKlass2::fooValue'
		// TODO duplicated namespaces in the error message
		);

		$this->builder->buildFromClass(TypeNotFoundKlass2::class);
	}
}
