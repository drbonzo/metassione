<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class IncorrectlyDefinedPropertiesKlass
{
	/**
	 * @var void
	 */
	private $voidProperty;

	private $noPhpdocProperty;

	/**
	 * Foo bar but no [AT]var
	 */
	private $noVarInPhpdocProperty;

	/**
	 * but not array type specification
	 *
	 * @var array
	 */
	private $invalidArrayProperty;

	/**
	 * @param void $voidProperty
	 */
	public function setVoidProperty($voidProperty)
	{
		$this->voidProperty = $voidProperty;
	}

	/**
	 * @return mixed
	 */
	public function getNoPhpdocProperty()
	{
		return $this->noPhpdocProperty;
	}

	/**
	 * @param mixed $noPhpdocProperty
	 */
	public function setNoPhpdocProperty($noPhpdocProperty)
	{
		$this->noPhpdocProperty = $noPhpdocProperty;
	}

	/**
	 * @return mixed
	 */
	public function getNoVarInPhpdocProperty()
	{
		return $this->noVarInPhpdocProperty;
	}

	/**
	 * @param mixed $noVarInPhpdocProperty
	 */
	public function setNoVarInPhpdocProperty($noVarInPhpdocProperty)
	{
		$this->noVarInPhpdocProperty = $noVarInPhpdocProperty;
	}

	/**
	 * @return array
	 */
	public function getInvalidArrayProperty()
	{
		return $this->invalidArrayProperty;
	}

	/**
	 * @param array $invalidArrayProperty
	 */
	public function setInvalidArrayProperty($invalidArrayProperty)
	{
		$this->invalidArrayProperty = $invalidArrayProperty;
	}
}
