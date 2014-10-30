<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class GrandparentKlass
{
	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	private $grandparentProperty;

	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	protected $grandparentProtectedProperty;

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $grandparentProperty
	 */
	public function setGrandparentProperty($grandparentProperty)
	{
		$this->grandparentProperty = $grandparentProperty;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getGrandparentProperty()
	{
		return $this->grandparentProperty;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $grandparentProtectedProperty
	 */
	public function setGrandparentProtectedProperty($grandparentProtectedProperty)
	{
		$this->grandparentProtectedProperty = $grandparentProtectedProperty;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getGrandparentProtectedProperty()
	{
		return $this->grandparentProtectedProperty;
	}
}
