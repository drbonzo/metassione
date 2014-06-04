<?php
namespace NorthslopePL\Metassione\Tests;

class OneObjectPropertyKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\OnePropertyKlass
	 */
	private $value;

	/**
	 * @param \NorthslopePL\Metassione\Tests\OnePropertyKlass $value
	 */
	public function setValue(\NorthslopePL\Metassione\Tests\OnePropertyKlass $value)
	{
		$this->value = $value;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\OnePropertyKlass
	 */
	public function getValue()
	{
		return $this->value;
	}

}
