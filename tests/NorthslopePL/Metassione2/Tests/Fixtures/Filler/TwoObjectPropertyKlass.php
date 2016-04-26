<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Filler;

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
