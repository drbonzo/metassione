<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Klasses;

class ArrayPropertiesKlass
{
	/**
	 * @var string[]
	 */
	public $stringArray_1 = [];

	/**
	 * @var string[]|array
	 */
	public $stringArray_2 = [];

	/**
	 * @var array|string[]
	 */
	public $stringArray_3 = [];

	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass[]
	 */
	public $objectArray_1 = [];

	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass[]|array
	 */
	public $objectArray_2 = [];

	/**
	 * @var array|\NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass[]
	 */
	public $objectArray_3 = [];

	/**
	 * Not fully qualified class name; Same namespace;
	 * @var SimpleKlass[]
	 */
	public $objectArray_4 = [];
}
