<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class OneObjectPropertyKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $value;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $value
	 */
	public function setValue(OnePropertyKlass $value)
	{
		$this->value = $value;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getValue()
	{
		return $this->value;
	}

}
