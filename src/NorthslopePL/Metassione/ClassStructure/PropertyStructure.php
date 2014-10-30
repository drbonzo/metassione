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
	private $isArray = false;

	/**
	 * if TRUE - then `type` contains value of: 'string', 'int', 'integer', 'float', 'double', 'bool', etc.
	 * if FALSE - then `type` contains value of classname
	 * @var bool
	 */
	private $isPrimitive = true;

	/**
	 * @var string
	 */
	private $type = 'mixed';

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
	public function getIsArray()
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
	public function getIsPrimitive()
	{
		return $this->isPrimitive;
	}

	/**
	 * true - yes, 'int', 'float', 'string', 'bool', etc.
	 * false - no, it is an object
	 * @param boolean $isPrimitive
	 */
	public function setIsPrimitive($isPrimitive)
	{
		$this->isPrimitive = $isPrimitive;
	}

	/**
	 * @return bool
	 */
	public function getIsObject()
	{
		return !$this->getIsPrimitive();
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
	public function getIsTypeKnown()
	{
		return $this->isTypeKnown;
	}

	/**
	 * @param boolean $isTypeKnown
	 */
	private function setIsTypeKnown($isTypeKnown)
	{
		$this->isTypeKnown = $isTypeKnown;
	}

	public function markAsTypeUnknown()
	{
		$this->setIsTypeKnown(false);
		$this->isArray = false;
		$this->isPrimitive = true;
		$this->type = null;
	}

	public function markAsTypeKnown()
	{
		$this->setIsTypeKnown(true);
	}

}
