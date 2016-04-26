<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Klasses;

class ParentKlass extends GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	private $parentProperty;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass $grandparentProperty
	 */
	public function setParentProperty($grandparentProperty)
	{
		$this->parentProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	public function getParentProperty()
	{
		return $this->parentProperty;
	}

}
