<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class PropertyNotFoundKlass
{
	/** @noinspection PhpUndefinedClassInspection */
	/**
	 * Class Foo just does not exist. This is expected behaviour for that test.
	 *
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\Foo
	 */
	private $foo;


	/**
	 * Unqualified class name
	 *
	 * @var OnePropertyKlass
	 */
	private $one;

	/** @noinspection PhpUndefinedClassInspection */
	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\Foo $foo
	 */
	public function setFoo($foo)
	{
		$this->foo = $foo;
	}

	/** @noinspection PhpUndefinedClassInspection */
	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\Foo
	 */
	public function getFoo()
	{
		return $this->foo;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass $one
	 */
	public function setOne($one)
	{
		$this->one = $one;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\OnePropertyKlass
	 */
	public function getOne()
	{
		return $this->one;
	}

}
