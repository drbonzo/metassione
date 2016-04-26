<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Builder;

class ClassTypesTypeNullablePropertiesKlass
{
	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Builder\SimpleKlass|null
	 */
	public $propertyA;

	/**
	 * @var SimpleKlass|null
	 */
	public $propertyB;

	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Builder\SubNamespace\OtherSimpleKlass|null
	 */
	public $propertyM;

	/**
	 * @var SubNamespace\OtherSimpleKlass|null
	 */
	public $propertyO;
}
