<?php
namespace NorthslopePL\Metassione\Metadata;

class ClassDefinition
{
	/**
	 * Name of the class (with namespace)
	 * @var string
	 */
	public $name;

	/**
	 * Namespace of the class
	 * @var string
	 */
	public $namespace;

	/**
	 * @var PropertyDefinition[]
	 *
	 * index: property name
	 * value: PropertyDefinition
	 */
	public $properties = [];

}
