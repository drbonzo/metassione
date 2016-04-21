<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Klasses;

class OnePropertyKlass
{
	/**
	 * @var int
	 */
	private $value;

	/**
	 * @param int $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return int
	 */
	public function getValue()
	{
		return $this->value;
	}

}
