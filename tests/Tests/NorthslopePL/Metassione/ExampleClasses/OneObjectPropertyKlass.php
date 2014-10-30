<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class OneObjectPropertyKlass
{
	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	private $value;

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $value
	 */
	public function setValue(OnePropertyKlass $value)
	{
		$this->value = $value;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getValue()
	{
		return $this->value;
	}

}
