<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\ClassStructure\ClassStructure;
use NorthslopePL\Metassione\ClassStructure\ClassStructureException;

interface ReflectionCache
{
	/**
	 * @param string $classname
	 *
	 * @return ClassStructure
	 *
	 * @throws ClassStructureException
	 */
	public function getClassStructureForClassname($classname);

	/**
	 * @param string $className
	 * @param string $propertyName
	 *
	 * @return \ReflectionProperty
	 */
	public function getReflectionPropertyForClassnameAndPropertyName($className, $propertyName);

	/**
	 * @param string $classname
	 *
	 * @return object
	 *
	 * @throws ObjectFillingException
	 */
	public function buildNewInstanceOfClass($classname);
}
