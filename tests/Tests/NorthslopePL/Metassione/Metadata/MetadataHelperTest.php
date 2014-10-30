<?php
namespace Tests\NorthslopePL\Metassione\Metadata;

use NorthslopePL\Metassione\Metadata\MetadataHelper;
use Tests\NorthslopePL\Metassione\ExampleClasses\ChildKlass;
use Tests\NorthslopePL\Metassione\ExampleClasses\GrandparentKlass;

class MetadataHelperTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var MetadataHelper
	 */
	private $metadataHelper;

	protected function setUp()
	{
		$this->metadataHelper = new MetadataHelper();
	}

	public function testGettingDirectClassProperties()
	{
		$grandparentObject = new GrandparentKlass();
		$properties = $this->metadataHelper->getPropertyReflectionsFromObjectOrItsParentClasses($grandparentObject);

		$this->assertCount(2, $properties);

		$this->assertEquals('grandparentProperty', $properties[0]->getName());
		$this->assertEquals('grandparentProtectedProperty', $properties[1]->getName());
	}

	public function testGettingDirectAndParentClassProperties()
	{
		$childObject = new ChildKlass();
		$properties = $this->metadataHelper->getPropertyReflectionsFromObjectOrItsParentClasses($childObject);

		$this->assertCount(4, $properties);

		$this->assertEquals('childProperty', $properties[0]->getName());
		$this->assertEquals('parentProperty', $properties[1]->getName());
		$this->assertEquals('grandparentProperty', $properties[2]->getName());
		$this->assertEquals('grandparentProtectedProperty', $properties[3]->getName());
	}
}
