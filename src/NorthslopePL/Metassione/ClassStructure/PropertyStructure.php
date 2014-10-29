<?php
namespace NorthslopePL\Metassione\ClassStructure;

class PropertyStructure
{
	/**
	 * @var string
	 */
	private $definedInClass;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * TRUE - isArray and isPrimitive and type have correct values - use them
	 * FALSE - we dont know the type - so dont read values from these properties
	 * @var bool
	 */
	private $isTypeKnown = true;

	/**
	 * @var bool
	 */
	private $isArray;

	/**
	 * if TRUE - then `type` contains value of: 'string', 'int', 'integer', 'float', 'double', 'bool', etc.
	 * if FALSE - then `type` contains value of classname
	 * @var bool
	 */
	private $isPrimitive;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @return string
	 */
	public function getDefinedInClass()
	{
		return $this->definedInClass;
	}

	/**
	 * @param string $classname
	 */
	public function setDefinedInClass($classname)
	{
		$this->definedInClass = $classname;
	}

	/**
	 * @return boolean
	 */
	public function isIsArray()
	{
		return $this->isArray;
	}

	/**
	 * @param boolean $isArray
	 */
	public function setIsArray($isArray)
	{
		$this->isArray = $isArray;
	}

	/**
	 * @return boolean
	 */
	public function isIsPrimitive()
	{
		return $this->isPrimitive;
	}

	/**
	 * @param boolean $isPrimitive
	 */
	public function setIsPrimitive($isPrimitive)
	{
		$this->isPrimitive = $isPrimitive;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return boolean
	 */
	public function isIsTypeKnown()
	{
		return $this->isTypeKnown;
	}

	/**
	 * @param boolean $isTypeKnown
	 */
	public function setIsTypeKnown($isTypeKnown)
	{
		$this->isTypeKnown = $isTypeKnown;
	}

}
