<?php
namespace NorthslopePL\Metassione\Tests\Examples;

use NorthslopePL\Metassione\Tests\OtherExamples\OtherOnePropertyKlass;

class PropertyNotFoundKlass
{
	/**
	 * Class Foo just does not exist. This is expected behaviour for that test.
	 *
	 * @var \NorthslopePL\Metassione\Tests\Examples\Foo
	 */
	private $foo;


	/**
	 * Unqualified class name.
	 * This class exists, it is loaded via 'use ...OtherOnePropertyKlass'
	 * but this is not supported. This will be handled as 'class not found'
	 *
	 * @var OtherOnePropertyKlass
	 */
	private $one;

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\Foo $foo
	 */
	public function setFoo($foo)
	{
		$this->foo = $foo;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\Foo
	 */
	public function getFoo()
	{
		return $this->foo;
	}

	/**
	 * @param \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass $one
	 */
	public function setOne($one)
	{
		$this->one = $one;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Examples\OnePropertyKlass
	 */
	public function getOne()
	{
		return $this->one;
	}

}
