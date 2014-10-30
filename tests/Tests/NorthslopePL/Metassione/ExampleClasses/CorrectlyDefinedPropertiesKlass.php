<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class CorrectlyDefinedPropertiesKlass
{
	/**
	 * @var int
	 */
	private $intProperty;

	/**
	 * @var integer
	 */
	private $integerProperty;

	/**
	 * @var float
	 */
	private $floatProperty;

	/**
	 * @var double
	 */
	private $doubleProperty;

	/**
	 * @var bool
	 */
	private $boolProperty;

	/**
	 * @var boolean
	 */
	private $booleanProperty;

	/**
	 * @var string
	 */
	private $stringProperty;

	/**
	 * @var mixed
	 */
	private $mixedProperty;

	/**
	 * @var null
	 */
	private $nullProperty;

	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass
	 */
	private $simpleKlassProperty;

	/**
	 * @var int[]
	 */
	private $intArrayProperty;

	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\SimpleKlass[]
	 */
	private $simpleKlassArrayProperty;

	/**
	 * @return boolean
	 */
	public function isBoolProperty()
	{
		return $this->boolProperty;
	}

	/**
	 * @param boolean $boolProperty
	 */
	public function setBoolProperty($boolProperty)
	{
		$this->boolProperty = $boolProperty;
	}

	/**
	 * @return boolean
	 */
	public function isBooleanProperty()
	{
		return $this->booleanProperty;
	}

	/**
	 * @param boolean $booleanProperty
	 */
	public function setBooleanProperty($booleanProperty)
	{
		$this->booleanProperty = $booleanProperty;
	}

	/**
	 * @return float
	 */
	public function getDoubleProperty()
	{
		return $this->doubleProperty;
	}

	/**
	 * @param float $doubleProperty
	 */
	public function setDoubleProperty($doubleProperty)
	{
		$this->doubleProperty = $doubleProperty;
	}

	/**
	 * @return float
	 */
	public function getFloatProperty()
	{
		return $this->floatProperty;
	}

	/**
	 * @param float $floatProperty
	 */
	public function setFloatProperty($floatProperty)
	{
		$this->floatProperty = $floatProperty;
	}

	/**
	 * @return int
	 */
	public function getIntProperty()
	{
		return $this->intProperty;
	}

	/**
	 * @param int $intProperty
	 */
	public function setIntProperty($intProperty)
	{
		$this->intProperty = $intProperty;
	}

	/**
	 * @return int
	 */
	public function getIntegerProperty()
	{
		return $this->integerProperty;
	}

	/**
	 * @param int $integerProperty
	 */
	public function setIntegerProperty($integerProperty)
	{
		$this->integerProperty = $integerProperty;
	}

	/**
	 * @return mixed
	 */
	public function getMixedProperty()
	{
		return $this->mixedProperty;
	}

	/**
	 * @param mixed $mixedProperty
	 */
	public function setMixedProperty($mixedProperty)
	{
		$this->mixedProperty = $mixedProperty;
	}

	/**
	 * @return null
	 */
	public function getNullProperty()
	{
		return $this->nullProperty;
	}

	/**
	 * @param null $nullProperty
	 */
	public function setNullProperty($nullProperty)
	{
		$this->nullProperty = $nullProperty;
	}

	/**
	 * @return string
	 */
	public function getStringProperty()
	{
		return $this->stringProperty;
	}

	/**
	 * @param string $stringProperty
	 */
	public function setStringProperty($stringProperty)
	{
		$this->stringProperty = $stringProperty;
	}

	/**
	 * @return SimpleKlass
	 */
	public function getSimpleKlassProperty()
	{
		return $this->simpleKlassProperty;
	}

	/**
	 * @param SimpleKlass $simpleKlassProperty
	 */
	public function setSimpleKlassProperty($simpleKlassProperty)
	{
		$this->simpleKlassProperty = $simpleKlassProperty;
	}

	/**
	 * @return \int[]
	 */
	public function getIntArrayProperty()
	{
		return $this->intArrayProperty;
	}

	/**
	 * @param \int[] $intArrayProperty
	 */
	public function setIntArrayProperty($intArrayProperty)
	{
		$this->intArrayProperty = $intArrayProperty;
	}

	/**
	 * @return SimpleKlass[]
	 */
	public function getSimpleKlassArrayProperty()
	{
		return $this->simpleKlassArrayProperty;
	}

	/**
	 * @param SimpleKlass[] $simpleKlassArrayProperty
	 */
	public function setSimpleKlassArrayProperty($simpleKlassArrayProperty)
	{
		$this->simpleKlassArrayProperty = $simpleKlassArrayProperty;
	}

}
