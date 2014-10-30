<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class ParentKlass extends GrandparentKlass
{
	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	private $parentProperty;

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $grandparentProperty
	 */
	public function setParentProperty($grandparentProperty)
	{
		$this->parentProperty = $grandparentProperty;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getParentProperty()
	{
		return $this->parentProperty;
	}

}
