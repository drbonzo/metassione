<?php
namespace NorthslopePL\Metassione\ClassStructure;

class ClassStructure
{
	/**
	 * @var string
	 */
	private $classname;

	/**
	 * @var PropertyStructure[]
	 */
	private $propertyStructures = [];

	/**
	 * @return string
	 */
	public function getClassname()
	{
		return $this->classname;
	}

	/**
	 * @param string $classname
	 */
	public function setClassname($classname)
	{
		$this->classname = $classname;
	}

	/**
	 * @return PropertyStructure[]
	 */
	public function getPropertyStructures()
	{
		return $this->propertyStructures;
	}

	/**
	 * @param PropertyStructure $propertyStructure
	 */
	public function addPropertyStructure(PropertyStructure $propertyStructure)
	{
		$this->propertyStructures[$propertyStructure->getName()] = $propertyStructure;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasProperty($name)
	{
		return isset($this->propertyStructures[$name]);
	}

	/**
	 * @param string $name
	 * @return PropertyStructure
	 * @throws ClassStructureException
	 */
	public function getPropertyStructure($name)
	{
		if ($this->hasProperty($name))
		{
			return $this->propertyStructures[$name];
		}
		else
		{
			throw new ClassStructureException(sprintf("Property '%s' not found for class '%s'", $name, $this->classname));
		}
	}

}
