<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\ClassStructureBuilder;
use Tests\NorthslopePL\Metassione\ExampleClasses\EmptyKlass;

class ClassStructureBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var ClassStructureBuilder
	 */
	private $classStructureBuilder;

	protected function setUp()
	{
		$this->classStructureBuilder = new ClassStructureBuilder();
	}

	public function testBuildingForEmptyKlass()
	{
		$classStructure = $this->classStructureBuilder->buildClassStructure(EmptyKlass::class);
		$this->assertCount(0, $classStructure->getPropertyStructures());
		$this->assertEquals(EmptyKlass::class, $classStructure->getClassname());
	}

	public function testBuildingForFlatKlass()
	{

	}

	public function testBuildingForClassWithDeepInheritance()
	{

	}
}
