<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler;

class ArrayTypedPropertyKlass
{
	/**
	 * @var SimpleKlass[]
	 */
	public $objectItemsNotNullable = [];

	/**
	 * @var SimpleKlass[]|null
	 */
	public $objectItemsNullable = [];

	/**
	 * @var string[]
	 */
	public $stringItemsNotNullable = [];

	/**
	 * @var string[]|null
	 */
	public $stringItemsNullable = [];
}
