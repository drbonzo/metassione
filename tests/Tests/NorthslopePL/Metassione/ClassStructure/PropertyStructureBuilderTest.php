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
	 * @param bool $typeIsKnown
	 * @param bool $isArray
	 * @param bool $isPrimitive
	 * @param string $type
	 */
	public function testBuildingProperty($classname, $propertyName, $typeIsKnown, $isArray, $isPrimitive, $type)
	{
		$reflectionClass = new \ReflectionObject($this->correctlyDefinedPropertiesKlass);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);

		$actualPropertyStructure = $this->propertyStructureBuilder->buildPropertyStructure($reflectionProperty);

		$expectedPropertyStructure = new PropertyStructure();
		$expectedPropertyStructure->setName($propertyName);
		$expectedPropertyStructure->setDefinedInClass($classname);
		if ($typeIsKnown)
		{
			$expectedPropertyStructure->markAsTypeKnown();
		}
		else
		{
			$expectedPropertyStructure->markAsTypeUnknown();
		}
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
			[$classname, 'intProperty', true, false, true, 'int'],
			[$classname, 'integerProperty', true, false, true, 'integer'],
			[$classname, 'floatProperty', true, false, true, 'float'],
			[$classname, 'doubleProperty', true, false, true, 'double'],
			[$classname, 'boolProperty', true, false, true, 'bool'],
			[$classname, 'booleanProperty', true, false, true, 'boolean'],
			[$classname, 'stringProperty', true, false, true, 'string'],
			[$classname, 'mixedProperty', true, false, true, 'mixed'],
			[$classname, 'nullProperty', true, false, true, 'null'],

			[$classname, 'simpleKlassProperty', true, false, false, 'Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass'],

			[$classname, 'intArrayProperty', true, true, true, 'int'],
			[$classname, 'simpleKlassArrayProperty', true, true, false, 'Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass'],
			[$classname, 'intArray2Property', true, true, true, 'int'],
			[$classname, 'simpleKlassArray2Property', true, true, false, 'Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass'],
		];
	}

}
