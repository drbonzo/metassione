<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler;

class TwoObjectPropertyKlass
{
	/**
	 * @var TwoBasicPropertyKlass
	 */
	public $twoNotNull;

	/**
	 * @var TwoBasicPropertyKlass|null
	 */
	public $twoNullable;
}
