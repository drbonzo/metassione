<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler;

class SimpleKlass
{
	public function __construct($name = null)
	{
		$this->name = $name;
	}

	/**
	 * @var string
	 */
	public $name = '';
}
