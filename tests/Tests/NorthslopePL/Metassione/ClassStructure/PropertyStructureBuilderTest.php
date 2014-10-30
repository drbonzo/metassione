<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\PropertyStructure;
use NorthslopePL\Metassione\ClassStructure\PropertyStructureBuilder;
use Tests\NorthslopePL\Metassione\ExampleClasses\CorrectlyDefinedPropertiesKlass;

class PropertyStructureBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var CorrectlyDefinedPropertiesKlass
	 */
	private $correctlyDefinedPropertiesKlass;

	/**
	 * @var PropertyStructureBuilder
	 */
	private $propertyStructureBuilder;

	protected function setUp()
	{
		$this->correctlyDefinedPropertiesKlass = new CorrectlyDefinedPropertiesKlass();
		$this->propertyStructureBuilder = new PropertyStructureBuilder();
	}

	/**
	 * @dataProvider buildingPropertyDataProvider()
	 * @param string $classname
	 * @param string $propertyName
	 * @param bool $isArray
	 * @param bool $isPrimitive
	 * @param string $type
	 */
	public function testBuildingProperty($classname, $propertyName, $isArray, $isPrimitive, $type)
	{
		$reflectionClass = new \ReflectionObject($this->correctlyDefinedPropertiesKlass);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);

		$actualPropertyStructure = $this->propertyStructureBuilder->buildPropertyStructure($reflectionProperty);

		$expectedPropertyStructure = new PropertyStructure();
		$expectedPropertyStructure->setName($propertyName);
		$expectedPropertyStructure->setDefinedInClass($classname);
		$expectedPropertyStructure->markAsTypeKnown(); // FIXME?
		$expectedPropertyStructure->setIsArray($isArray);
		$expectedPropertyStructure->setIsPrimitive($isPrimitive);
		$expectedPropertyStructure->setType($type);

		$this->assertEquals($expectedPropertyStructure, $actualPropertyStructure);
	}

	/**
	 * @return array
	 */
	public function buildingPropertyDataProvider()
	{
		$classname = 'Tests\NorthslopePL\Metassione\ExampleClasses\CorrectlyDefinedPropertiesKlass';
		return [
			[$classname, 'intProperty', false, true, 'int'],
			[$classname, 'integerProperty', false, true, 'integer'],
			[$classname, 'floatProperty', false, true, 'float'],
			[$classname, 'doubleProperty', false, true, 'double'],
			[$classname, 'boolProperty', false, true, 'bool'],
			[$classname, 'booleanProperty', false, true, 'boolean'],
			[$classname, 'stringProperty', false, true, 'string'],
			[$classname, 'mixedProperty', false, true, 'mixed'],
			[$classname, 'nullProperty', false, true, 'null'],

			[$classname, 'simpleKlassProperty', false, false, 'Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass'],

			[$classname, 'intArrayProperty', true, true, 'int'],
			[$classname, 'simpleKlassArrayProperty', true, false, 'Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass'],
		];
	}

}
