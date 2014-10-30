<?php
namespace NorthslopePL\Metassione\Metadata;

class MetadataHelper
{
	// WTF sprawdz uzycia tego kodu - kazdej metody - czy ma sens kazde jego uzycie, czy dobrze ze uzywam object/RC/RO?

	/**
	 * @param object $object
	 * @return \array[]|\ReflectionProperty[]
	 */
	public function getPropertyReflectionsFromObjectOrItsParentClasses($object)
	{
		$reflectionObject = new \ReflectionObject($object);
		return $this->getPropertyReflectionsFromReflectionObjectOrItsParentClasses($reflectionObject);
	}

	/**
	 * @param \ReflectionClass $reflectionClass
	 *
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
	public function getPropertyReflectionsFromReflectionClassOrItsParentClasses(\ReflectionClass $reflectionClass)
	{
		$allProperties = array();
		$currentClassReflection = $reflectionClass;

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

	/**
	 * @param \ReflectionObject $reflectionObject
	 * @return \array[]|\ReflectionProperty[]
	 */
	public function getPropertyReflectionsFromReflectionObjectOrItsParentClasses(\ReflectionObject $reflectionObject)
	{
		return $this->getPropertyReflectionsFromReflectionClassOrItsParentClasses($reflectionObject);
	}

	/**
	 * @param \ReflectionClass $object
	 * @param string $propertyName
	 * @return null|\ReflectionProperty
	 */
	public function getPropertyReflectionFromReflectionClassOrParentClasses(\ReflectionClass $object, $propertyName)
	{
		$currentClassReflection = $object;

		while ((bool)$currentClassReflection) // class without parent has null in getParentClass()
		{
			if ($currentClassReflection->hasProperty($propertyName))
			{
				return $currentClassReflection->getProperty($propertyName);
			}

			// go to parent class
			$currentClassReflection = $currentClassReflection->getParentClass();
		}

		return null;
	}
}
