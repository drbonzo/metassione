<?php
namespace NorthslopePL\Metassione\Metadata;

class MetadataHelper
{
	/**
	 * @param object $object
	 * @return array[]|\ReflectionProperty[]
	 *
	 * \ReflectionClass::getProperties() checks only properties from current class and all nonprivate properties from parent classes.
	 * So private properties from parent classes will not be found.
	 *
	 * We have workaround for this problem, by checking parent classes directly for their properties
	 *
	 * http://www.php.net/manual/en/reflectionclass.hasproperty.php#94038
	 * http://stackoverflow.com/questions/9913680/does-reflectionclassgetproperties-also-get-properties-of-the-parent
	 */
	public function getPropertyReflectionsFromObjectOrItsParentClasses($object) // TODO DRY POPOConverter
	{
		$reflectionObject = new \ReflectionObject($object);
		$allProperties = array();
		$currentClassReflection = $reflectionObject;

		while ((bool)$currentClassReflection) // class without parent has null in getParentClass()
		{
			$properties = $currentClassReflection->getProperties();

			foreach ($properties as $property)
			{
				// add only properties defined in current class
				// properties added in parent classes will be added later
				if ($property->getDeclaringClass()->getName() == $currentClassReflection->getName())
				{
					$allProperties[] = $property;
				}
			}

			// go to parent class
			$currentClassReflection = $currentClassReflection->getParentClass();
		}

		return $allProperties;
	}

}
