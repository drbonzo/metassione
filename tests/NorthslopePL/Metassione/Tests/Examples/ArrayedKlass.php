<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class ArrayedKlass
{
	/**
	 * @var array|int[]
	 */
	private $numbers;

	/**
	 * @var array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]
	 */
	private $objects;

	/**
	 * Inverted order of 'array' and classname
	 *
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]|array
	 */
	private $objects2;

	/**
	 * Without [] at the end
	 *
	 * @var array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	private $objects3;

	/**
	 * Without 'array|'
	 *
	 * @var \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]
	 */
	private $invalidSpecificationForObjects;


	/**
	 * @param array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[] $objects
	 */
	public function setObjects($objects)
	{
		$this->objects = $objects;
	}

	/**
	 * @return array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]
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

	/**
	 * @param array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[] $objects2
	 */
	public function setObjects2($objects2)
	{
		$this->objects2 = $objects2;
	}

	/**
	 * @return array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]
	 */
	public function getObjects2()
	{
		return $this->objects2;
	}

	/**
	 * @param array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $objects3
	 */
	public function setObjects3($objects3)
	{
		$this->objects3 = $objects3;
	}

	/**
	 * @return array|\NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getObjects3()
	{
		return $this->objects3;
	}

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[] $invalidSpecificationForObjects
	 */
	public function setInvalidSpecificationForObjects($invalidSpecificationForObjects)
	{
		$this->invalidSpecificationForObjects = $invalidSpecificationForObjects;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass[]
	 */
	public function getInvalidSpecificationForObjects()
	{
		return $this->invalidSpecificationForObjects;
	}

}
