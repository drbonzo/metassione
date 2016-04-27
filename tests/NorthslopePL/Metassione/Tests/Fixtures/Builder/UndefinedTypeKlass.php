<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Builder;

class UndefinedTypeKlass
{
	/**
	 * @var
	 */
	public $undefinedProperty_1; // no type

	/**
	 */
	public $undefinedProperty_2; // no @var

	public $undefinedProperty_3; // no phpdoc

	/**
	 * @var void
	 */
	public $voidValue; // is void

	/**
	 * @var mixed
	 */
	public $mixedValue; // is mixed

	/**
	 * @var null
	 */
	public $nullValue; // is null

}
