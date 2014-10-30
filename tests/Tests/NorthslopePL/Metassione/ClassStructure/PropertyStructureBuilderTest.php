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
	 * @dataProvider buildingPropertiesDataProvider()
	 * @param string $classname
	 * @param string $propertyName
	 * @param bool $isArray
	 * @param bool $isPrimitive
	 * @param string $type
	 */
	public function testBuildingIntProperty($classname, $propertyName, $isArray, $isPrimitive, $type)
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
	public function buildingPropertiesDataProvider()
	{
		return [
			['Tests\NorthslopePL\Metassione\ExampleClasses\CorrectlyDefinedPropertiesKlass', 'intProperty', false, true, 'int']
		];
	}
}
