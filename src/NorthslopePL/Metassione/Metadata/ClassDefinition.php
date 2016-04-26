<?php
namespace NorthslopePL\Metassione\Metadata;

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
