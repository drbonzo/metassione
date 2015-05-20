<?php
namespace NorthslopePL\Metassione\Tests\Examples;

class PropertyWithoutFullClassname
{
	/**
	 * @var ChildKlass
	 */
	private $child;

	/**
	 * @var SimpleKlass[]
	 */
	private $simpleKlasses;

	/**
	 * @return ChildKlass
	 */
	public function getChild()
	{
		return $this->child;
	}

	/**
	 * @param ChildKlass $child
	 */
	public function setChild($child)
	{
		$this->child = $child;
	}

	/**
	 * @return SimpleKlass[]
	 */
	public function getSimpleKlasses()
	{
		return $this->simpleKlasses;
	}

	/**
	 * @param SimpleKlass[] $simpleKlasses
	 */
	public function setSimpleKlasses($simpleKlasses)
	{
		$this->simpleKlasses = $simpleKlasses;
	}

}