<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Klasses;

class GrandparentKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	private $grandparentProperty;

	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	protected $grandparentProtectedProperty;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass $grandparentProperty
	 */
	public function setGrandparentProperty($grandparentProperty)
	{
		$this->grandparentProperty = $grandparentProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	public function getGrandparentProperty()
	{
		return $this->grandparentProperty;
	}

	/**
	 * @param \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass $grandparentProtectedProperty
	 */
	public function setGrandparentProtectedProperty($grandparentProtectedProperty)
	{
		$this->grandparentProtectedProperty = $grandparentProtectedProperty;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Fixtures\Klasses\OnePropertyKlass
	 */
	public function getGrandparentProtectedProperty()
	{
		return $this->grandparentProtectedProperty;
	}
}
