<?php
namespace NorthslopePL\Metassione\ClassStructure;

class ClassStructureBuilder
{
	/**
	 * @param string $classname
	 * @return ClassStructure
	 */
	public function buildClassStructure($classname)
	{
		$classStructure = new ClassStructure();
		$classStructure->setClassname($classname);
		
		return $classStructure;
	}
}
