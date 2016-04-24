<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Klasses;

class ClassTypesTypeNullablePropertiesKlass
{
	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SimpleKlass|null
	 */
	public $propertyA;

	/**
	 * @var SimpleKlass|null
	 */
	public $propertyB;

	/**
	 * @var \NorthslopePL\Metassione2\Tests\Fixtures\Klasses\SubNamespace\OtherSimpleKlass|null
	 */
	public $propertyM;

	/**
	 * @var SubNamespace\OtherSimpleKlass|null
	 */
	public $propertyO;
}
