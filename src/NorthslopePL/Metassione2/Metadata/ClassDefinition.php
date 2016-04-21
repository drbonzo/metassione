<?php
namespace NorthslopePL\Metassione2\Metadata;

class ClassDefinition
{
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public  $namespace;

	/**
	 * @var PropertyDefinition[]
	 *
	 * index: property name
	 * value: PropertyDefinition
	 */
	public $properties = [];

}
