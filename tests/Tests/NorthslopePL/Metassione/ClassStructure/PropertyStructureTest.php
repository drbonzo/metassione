<?php
namespace Tests\NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\ClassStructure\PropertyStructure;

class PropertyStructureTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PropertyStructure
	 */
	private $propertyStructure;

	protected function setUp()
	{
		$this->propertyStructure = new PropertyStructure();
	}

	public function testDefaultValues()
	{
		$this->assertNull($this->propertyStructure->getDefinedInClass());
		$this->assertNull($this->propertyStructure->getName());
		$this->assertTrue($this->propertyStructure->getIsTypeKnown());
		$this->assertFalse($this->propertyStructure->getIsArray());
		$this->assertTrue($this->propertyStructure->getIsPrimitive());
		$this->assertFalse($this->propertyStructure->getIsObject());
		$this->assertEquals('mixed', $this->propertyStructure->getType());
	}

	public function testSettingValues()
	{
		$this->propertyStructure->setDefinedInClass('Foo\BarKlass');
		$this->assertEquals('Foo\BarKlass', $this->propertyStructure->getDefinedInClass());

		$this->propertyStructure->setName('baz');
		$this->assertEquals('baz', $this->propertyStructure->getName());

		$this->assertTrue($this->propertyStructure->getIsTypeKnown());

		$this->propertyStructure->setIsArray(true);
		$this->assertTrue($this->propertyStructure->getIsArray());

		$this->propertyStructure->setIsPrimitive(false);
		$this->assertFalse($this->propertyStructure->getIsPrimitive());
		$this->assertTrue($this->propertyStructure->getIsObject());

		$this->propertyStructure->setType('Foo\BazKlass');
		$this->assertEquals('Foo\BazKlass', $this->propertyStructure->getType());
	}

	public function testSettingTypeUnknown()
	{
		$this->propertyStructure->markAsTypeUnknown();

		$this->assertNull($this->propertyStructure->getDefinedInClass());
		$this->assertNull($this->propertyStructure->getName());

		$this->assertFalse($this->propertyStructure->getIsTypeKnown());
		// we should not read these values below:
		$this->assertFalse($this->propertyStructure->getIsArray());
		$this->assertTrue($this->propertyStructure->getIsPrimitive());
		$this->assertNull($this->propertyStructure->getType());
	}
}
