<?php
namespace NorthslopePL\Metassione\ClassStructure;

use NorthslopePL\Metassione\Metadata\MetadataHelper;

class ClassStructureBuilder
{
	/**
	 * @param string $classname
	 *
	 * @return ClassStructure
	 *
	 * @throws ClassStructureException if class is not found
	 */
	public function buildClassStructure($classname)
	{
		$classStructure = new ClassStructure();
		$metadataHelper = new MetadataHelper();

		if (!class_exists($classname))
		{
			throw new ClassStructureException(sprintf("Class not found: '%s'", $classname));
		}

		$classStructure->setClassname($classname);

		$propertyStructureBuilder = new PropertyStructureBuilder();
		$reflectionProperties = $metadataHelper->getPropertyReflectionsFromReflectionClassOrItsParentClasses(new \ReflectionClass($classname));
		foreach ($reflectionProperties as $reflectionProperty)
		{
			$propertyStructure = $propertyStructureBuilder->buildPropertyStructure($reflectionProperty);
			$classStructure->addPropertyStructure($propertyStructure);
		}

		return $classStructure;
	}
}
