<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class ChildKlass extends ParentKlass
{
	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	private $childProperty;

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $grandparentProperty
	 */
	public function setChildProperty($grandparentProperty)
	{
		$this->childProperty = $grandparentProperty;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getChildProperty()
	{
		return $this->childProperty;
	}

}
