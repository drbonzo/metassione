<?php
namespace NorthslopePL\Metassione\Tests;

class ArrayedKlass
{
	/**
	 * @var array|OnePropertyKlass[]
	 */
	private $objects;

	/**
	 * @var array|int[]
	 */
	private $numbers;

	/**
	 * @param array|\NorthslopePL\Metassione\Tests\OnePropertyKlass[] $objects
	 */
	public function setObjects($objects)
	{
		$this->objects = $objects;
	}

	/**
	 * @return array|\NorthslopePL\Metassione\Tests\OnePropertyKlass[]
	 */
	public function getObjects()
	{
		return $this->objects;
	}

	/**
	 * @param array|\int[] $numbers
	 */
	public function setNumbers($numbers)
	{
		$this->numbers = $numbers;
	}

	/**
	 * @return array|\int[]
	 */
	public function getNumbers()
	{
		return $this->numbers;
	}

}
