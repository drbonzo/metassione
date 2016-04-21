<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Klasses;

class ChildKlass extends ParentKlass
{
	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	private $childProperty;

	/**
	 * @param \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass $grandparentProperty
	 */
	public function setChildProperty($grandparentProperty)
	{
		$this->childProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	public function getChildProperty()
	{
		return $this->childProperty;
	}

}
