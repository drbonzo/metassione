<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler\Simple;

class KlassWithStrings
{
	/**
	 * @var string
	 */
	public $normalProperty = 'normal';

	/**
	 * @var string|null
	 */
	public $nullableProperty = 'nullable';

	/**
	 * @var string[]
	 */
	public $arrayProperty = ['foo', 'bar'];
}