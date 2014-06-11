<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class ParentKlass extends GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $parentProperty;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $grandparentProperty
	 */
	public function setParentProperty($grandparentProperty)
	{
		$this->parentProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getParentProperty()
	{
		return $this->parentProperty;
	}

}
