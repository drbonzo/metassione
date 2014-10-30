<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\ClassStructureBuilder;
use NorthslopePL\Metassione\ClassStructure\PropertyStructure;
use Tests\NorthslopePL\Metassione\ExampleClasses\ChildKlass;
use Tests\NorthslopePL\Metassione\ExampleClasses\EmptyKlass;
use Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass;

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
		$classStructure = $this->classStructureBuilder->buildClassStructure(SimpleKlass::class);
		$this->assertCount(3, $classStructure->getPropertyStructures());
		$this->assertEquals(SimpleKlass::class, $classStructure->getClassname());

		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('count'));
		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('name'));
		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('value'));
	}

	public function testBuildingForClassWithDeepInheritance()
	{
		$classStructure = $this->classStructureBuilder->buildClassStructure(ChildKlass::class);
		$this->assertCount(4, $classStructure->getPropertyStructures());
		$this->assertEquals(ChildKlass::class, $classStructure->getClassname());

		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('childProperty'));
		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('parentProperty'));
		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('grandparentProperty'));
		$this->assertInstanceOf(PropertyStructure::class, $classStructure->getPropertyStructure('grandparentProtectedProperty'));
	}

	public function testBuildingForNonexistingClassname()
	{
		$nonexistingClassname = 'Foo\Bar\ThisClassDoesNotExist';
		$this->setExpectedException('NorthslopePL\Metassione\ClassStructure\ClassStructureException', "Class not found: 'Foo\\Bar\\ThisClassDoesNotExist'");
		$this->classStructureBuilder->buildClassStructure($nonexistingClassname);
	}
}
