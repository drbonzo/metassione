<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\ClassStructure;
use NorthslopePL\Metassione\ClassStructure\PropertyStructure;

class ClassStructureTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var ClassStructure
	 */
	private $classStructure;

	protected function setUp()
	{
		$this->classStructure = new ClassStructure();
	}

	public function testDefaultValues()
	{
		$this->assertNull($this->classStructure->getClassname());
		$this->assertSame([], $this->classStructure->getPropertyStructures());
	}

	public function testRetrievingExistingPropertyStructures()
	{
		$propertyStructure = new PropertyStructure();
		$propertyStructure->setName('propertyName');
		$this->classStructure->addPropertyStructure($propertyStructure);

		$this->assertSame($propertyStructure, $this->classStructure->getPropertyStructure('propertyName'));
	}

	public function testRetrievingNonExistingPropertyStructures()
	{
		$this->classStructure->setClassname('FooKlass');
		$this->setExpectedException('NorthslopePL\Metassione\ClassStructure\ClassStructureException', "Property 'doesNotExist' not found for class 'FooKlass'");
		$this->classStructure->getPropertyStructure('doesNotExist');
	}

}
