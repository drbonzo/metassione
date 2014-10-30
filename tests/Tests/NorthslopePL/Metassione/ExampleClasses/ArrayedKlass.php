<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class ArrayedKlass
{
	/**
	 * @var int[]
	 */
	private $numbers;

	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]
	 */
	private $objects;

	/**
	 * Inverted order of 'array' and classname
	 *
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]|array
	 */
	private $objects2;

	/**
	 * Without [] at the end
	 *
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	private $objects3;

	/**
	 * Without ''
	 *
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]
	 */
	private $invalidSpecificationForObjects;


	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[] $objects
	 */
	public function setObjects($objects)
	{
		$this->objects = $objects;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]
	 */
	public function getObjects()
	{
		return $this->objects;
	}

	/**
	 * @param \int[] $numbers
	 */
	public function setNumbers($numbers)
	{
		$this->numbers = $numbers;
	}

	/**
	 * @return \int[]
	 */
	public function getNumbers()
	{
		return $this->numbers;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[] $objects2
	 */
	public function setObjects2($objects2)
	{
		$this->objects2 = $objects2;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]
	 */
	public function getObjects2()
	{
		return $this->objects2;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $objects3
	 */
	public function setObjects3($objects3)
	{
		$this->objects3 = $objects3;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getObjects3()
	{
		return $this->objects3;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[] $invalidSpecificationForObjects
	 */
	public function setInvalidSpecificationForObjects($invalidSpecificationForObjects)
	{
		$this->invalidSpecificationForObjects = $invalidSpecificationForObjects;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass[]
	 */
	public function getInvalidSpecificationForObjects()
	{
		return $this->invalidSpecificationForObjects;
	}

}
