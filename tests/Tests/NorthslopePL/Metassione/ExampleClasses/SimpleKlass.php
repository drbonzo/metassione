<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class SimpleKlass
{
	/**
	 * @var int
	 */
	private $count;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var float
	 */
	private $value;

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * @param int $count
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return float
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param float $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

}
