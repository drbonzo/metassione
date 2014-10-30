<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\PropertyStructure;
use NorthslopePL\Metassione\ClassStructure\PropertyStructureBuilder;
use Tests\NorthslopePL\Metassione\ExampleClasses\CorrectlyDefinedPropertiesKlass;
use Tests\NorthslopePL\Metassione\ExampleClasses\IncorrectlyDefinedPropertiesKlass;

class PropertyStructureBuilderForIncorrectDefinitionsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var IncorrectlyDefinedPropertiesKlass
	 */
	private $incorrectlyDefinedPropertiesKlass;

	/**
	 * @var PropertyStructureBuilder
	 */
	private $propertyStructureBuilder;

	protected function setUp()
	{
		$this->incorrectlyDefinedPropertiesKlass = new IncorrectlyDefinedPropertiesKlass();
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
		$reflectionClass = new \ReflectionObject($this->incorrectlyDefinedPropertiesKlass);
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
		$classname = 'Tests\NorthslopePL\Metassione\ExampleClasses\IncorrectlyDefinedPropertiesKlass';
		return [
			[$classname, 'noPhpdocProperty', false, false, true, null],
			[$classname, 'noVarInPhpdocProperty', false, false, true, null],
			[$classname, 'voidProperty', false, false, true, null],
			[$classname, 'invalidArrayProperty', true, true, true, 'mixed'],
		];
	}
}
