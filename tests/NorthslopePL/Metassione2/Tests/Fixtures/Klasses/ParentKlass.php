<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Klasses;

class ParentKlass extends GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	private $parentProperty;

	/**
	 * @param \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass $grandparentProperty
	 */
	public function setParentProperty($grandparentProperty)
	{
		$this->parentProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	public function getParentProperty()
	{
		return $this->parentProperty;
	}

}
