<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Builder;

class SimpleKlass
{
	/**
	 * @var null
	 */
	private $nullValue;

	/**
	 * @var bool
	 */
	private $boolValue;

	/**
	 * @var int
	 */
	private $intValue;

	/**
	 * @var float
	 */
	private $floatValue;

	/**
	 * @var string
	 */
	private $stringValue;

	/**
	 * @param boolean $boolValue
	 */
	public function setBoolValue($boolValue)
	{
		$this->boolValue = $boolValue;
	}

	/**
	 * @return boolean
	 */
	public function getBoolValue()
	{
		return $this->boolValue;
	}

	/**
	 * @param float $floatValue
	 */
	public function setFloatValue($floatValue)
	{
		$this->floatValue = $floatValue;
	}

	/**
	 * @return float
	 */
	public function getFloatValue()
	{
		return $this->floatValue;
	}

	/**
	 * @param int $intValue
	 */
	public function setIntValue($intValue)
	{
		$this->intValue = $intValue;
	}

	/**
	 * @return int
	 */
	public function getIntValue()
	{
		return $this->intValue;
	}

	/**
	 * @param null $nullValue
	 */
	public function setNullValue($nullValue)
	{
		$this->nullValue = $nullValue;
	}

	/**
	 * @return null
	 */
	public function getNullValue()
	{
		return $this->nullValue;
	}

	/**
	 * @param string $stringValue
	 */
	public function setStringValue($stringValue)
	{
		$this->stringValue = $stringValue;
	}

	/**
	 * @return string
	 */
	public function getStringValue()
	{
		return $this->stringValue;
	}

}
