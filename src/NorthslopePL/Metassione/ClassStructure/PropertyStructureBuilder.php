<?php

namespace NorthslopePL\Metassione\ClassStructure;

class PropertyStructureBuilder
{
	public function buildPropertyStructure(\ReflectionProperty $reflectionProperty)
	{
		$propertyStructure = new PropertyStructure();

		$propertyStructure->setName($reflectionProperty->getName());
//		$propertyStructure->setIsTypeKnown(true);
//		$propertyStructure->setIsArray(false);
//		$propertyStructure->setIsPrimitive(true);
//		$propertyStructure->setType('int');
		$propertyStructure->setDefinedInClass($reflectionProperty->getDeclaringClass()->getName());

		return $propertyStructure;
	}
}
