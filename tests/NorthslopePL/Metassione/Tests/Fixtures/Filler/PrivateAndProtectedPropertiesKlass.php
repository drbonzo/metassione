<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler;

class PrivateAndProtectedPropertiesKlass
{
	/**
	 * @var string
	 */
	private $privateValue;

	/**
	 * @var string
	 */
	protected $protectedValue;

	/**
	 * @var string
	 */
	public $publicValue;

	/**
	 * @return string
	 */
	public function getPrivateValue()
	{
		return $this->privateValue;
	}

	/**
	 * @return string
	 */
	public function getProtectedValue()
	{
		return $this->protectedValue;
	}

	/**
	 * @return string
	 */
	public function getPublicValue()
	{
		return $this->publicValue;
	}

}
