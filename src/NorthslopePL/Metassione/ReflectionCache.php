<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\ClassStructure\ClassStructure;
use NorthslopePL\Metassione\ClassStructure\ClassStructureBuilder;
use NorthslopePL\Metassione\ClassStructure\ClassStructureException;
use NorthslopePL\Metassione\Metadata\MetadataHelper;

class ReflectionCache
{
	/**
	 * @var MetadataHelper
	 */
	private $metadataHelper;

	/**
	 * @var ClassStructure[]
	 */
	private $classStructureCache = [];

	/**
	 * @var \ReflectionProperty[]
	 */
	private $reflectionPropertyCache = [];

	public function __construct(MetadataHelper $metadataHelper)
	{
		$this->metadataHelper = $metadataHelper;
	}

	/**
	 * @param string $classname
	 * @return ClassStructure
	 * @throws ClassStructureException
	 */
	public function getClassStructureForClassname($classname)
	{
		if (!isset($this->classStructureCache[$classname]))
		{
			$classStructureBuilder = new ClassStructureBuilder();
			$classStructure = $classStructureBuilder->buildClassStructure($classname);
			$this->classStructureCache[$classname] = $classStructure;
		}

		return $this->classStructureCache[$classname];
	}

	/**
	 * @param string $className
	 * @param string $propertyName
	 * @return \ReflectionProperty
	 */
	public function getReflectionPropertyForClassnameAndPropertyName($className, $propertyName)
	{
		$hash = $className . '::' . $propertyName;
		if (!isset($this->reflectionPropertyCache[$hash]))
		{
			$this->reflectionPropertyCache[$hash] = $this->metadataHelper->getPropertyReflectionFromReflectionClassOrParentClasses(new \ReflectionClass($className), $propertyName);
		}

		return $this->reflectionPropertyCache[$hash];
	}
}
