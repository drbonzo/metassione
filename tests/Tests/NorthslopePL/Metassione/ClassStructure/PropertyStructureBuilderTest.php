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

	public function testBuildingIntProperty()
	{
		$reflectionClass = new \ReflectionObject($this->correctlyDefinedPropertiesKlass);
		$reflectionProperty = $reflectionClass->getProperty('intProperty');

		$actualPropertyStructure = $this->propertyStructureBuilder->buildPropertyStructure($reflectionProperty);

		$expectedPropertyStructure = new PropertyStructure();
		$expectedPropertyStructure->setName('intProperty');
		$expectedPropertyStructure->setIsTypeKnown(true);
		$expectedPropertyStructure->setIsArray(false);
		$expectedPropertyStructure->setIsPrimitive(true);
		$expectedPropertyStructure->setType('int');
		$expectedPropertyStructure->setDefinedInClass('Tests\NorthslopePL\Metassione\ExampleClasses\CorrectlyDefinedPropertiesKlass');

		$this->assertEquals($expectedPropertyStructure, $actualPropertyStructure);
	}
}
