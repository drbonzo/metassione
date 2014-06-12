<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $grandparentProperty;

	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	protected $grandparentProtectedProperty;

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

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $grandparentProtectedProperty
	 */
	public function setGrandparentProtectedProperty($grandparentProtectedProperty)
	{
		$this->grandparentProtectedProperty = $grandparentProtectedProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getGrandparentProtectedProperty()
	{
		return $this->grandparentProtectedProperty;
	}
}
