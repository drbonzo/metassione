<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Builder;

class ArrayPropertiesNullableKlass
{
	/**
	 * @var string[]|null
	 */
	public $stringArray_1 = [];

	/**
	 * @var string[]|array|null
	 */
	public $stringArray_2 = [];

	/**
	 * @var array|string[]|null
	 */
	public $stringArray_3 = [];

	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Builder\SimpleKlass[]|null
	 */
	public $objectArray_1 = [];

	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Builder\SimpleKlass[]|array|null
	 */
	public $objectArray_2 = [];

	/**
	 * @var array|\NorthslopePL\Metassione2\Tests\Fixtures\Builder\SimpleKlass[]|null
	 */
	public $objectArray_3 = [];

	/**
	 * Not fully qualified class name; Same namespace;
	 * @var SimpleKlass[]|null
	 */
	public $objectArray_4 = [];
}
