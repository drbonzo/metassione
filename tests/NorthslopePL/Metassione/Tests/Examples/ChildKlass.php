<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class ChildKlass extends ParentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $childProperty;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $grandparentProperty
	 */
	public function setChildProperty($grandparentProperty)
	{
		$this->childProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getChildProperty()
	{
		return $this->childProperty;
	}

}
