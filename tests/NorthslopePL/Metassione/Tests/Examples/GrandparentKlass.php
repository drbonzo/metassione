<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $grandparentProperty;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $grandparentProperty
	 */
	public function setGrandparentProperty($grandparentProperty)
	{
		$this->grandparentProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getGrandparentProperty()
	{
		return $this->grandparentProperty;
	}

}
