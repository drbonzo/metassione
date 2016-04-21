<?php
namespace NorthslopePL\Metassione2\Tests\Metadata;

use NorthslopePL\Metassione2\Metadata\ClassDefinition;
use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\Metadata\PropertyDefinition;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\EmptyKlass;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\BasicTypesKlass;
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

}
