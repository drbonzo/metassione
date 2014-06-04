<?php
namespace NorthslopePL\Metassione\Tests;

class PropertyNotFoundKlass
{
	/**
	 * Class Foo just does not exist. This is expected behaviour for that test.
	 *
	 * @var \NorthslopePL\Metassione\Tests\Foo
	 */
	private $foo;


	/**
	 * Unqualified class name
	 *
	 * @var OnePropertyKlass
	 */
	private $one;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Foo $foo
	 */
	public function setFoo($foo)
	{
		$this->foo = $foo;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Foo
	 */
	public function getFoo()
	{
		return $this->foo;
	}

	/**
	 * @param \NorthslopePL\Metassione\Tests\OnePropertyKlass $one
	 */
	public function setOne($one)
	{
		$this->one = $one;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\OnePropertyKlass
	 */
	public function getOne()
	{
		return $this->one;
	}

}
