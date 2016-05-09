<?php
namespace NorthslopePL\Metassione\Metadata;

use ReflectionProperty;

class PropertyDefinition
{
	const BASIC_TYPE_STRING = 'string';
	const BASIC_TYPE_INTEGER = 'integer';
	const BASIC_TYPE_INT = 'int';
	const BASIC_TYPE_FLOAT = 'float';
	const BASIC_TYPE_DOUBLE = 'double';
	const BASIC_TYPE_BOOLEAN = 'boolean';
	const BASIC_TYPE_BOOL = 'bool';
	const BASIC_TYPE_VOID = 'void'; // not processed
	const BASIC_TYPE_MIXED = 'mixed'; // not processed
	const BASIC_TYPE_NULL = 'null';  // not processed

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $isDefined = false;

	/**
	 * 'int', 'integer', ...
	 * or classname  'Foo\Bar'
	 *
	 * @var string
	 */
	private $type;

	/**
	 * @var bool
	 */
	private $isObject = false;

	/**
	 * @var bool
	 */
	private $isArray = false;

	/**
	 * Whether property can have NULL value
	 * @var bool
	 */
	private $isNullable = false;

	/**
	 * @var ReflectionProperty
	 */
	private $reflectionProperty;

	public function __construct($name, $isDefined, $isObject, $isArray, $type, $isNullable, ReflectionProperty $reflectionProperty)
	{
		$this->isDefined = $isDefined;
		$this->isObject = $isObject;
		$this->isArray = $isArray;
		$this->type = $type;
		$this->isNullable = $isNullable;
		$this->name = $name;
		$this->reflectionProperty = $reflectionProperty;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return boolean
	 */
	public function getIsDefined()
	{
		return $this->isDefined;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return boolean
	 */
	public function getIsObject()
	{
		return $this->isObject;
	}

	/**
	 * @return bool
	 */
	public function getIsBasicType()
	{
		return !$this->getIsObject();
	}

	/**
	 * @return boolean
	 */
	public function getIsArray()
	{
		return $this->isArray;
	}

	/**
	 * @return boolean
	 */
	public function getIsNullable()
	{
		return $this->isNullable;
	}

	/**
	 * @return ReflectionProperty
	 */
	public function getReflectionProperty()
	{
		return $this->reflectionProperty;
	}

}
