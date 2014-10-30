<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\ClassStructure\ClassStructure;
use NorthslopePL\Metassione\ClassStructure\ClassStructureBuilder;
use NorthslopePL\Metassione\ClassStructure\ClassStructureException;

class ReflectionCache
{
	/**
	 * @var ClassStructure[]
	 */
	private $classStructureCache = [];

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
}
