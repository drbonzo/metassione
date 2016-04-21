<?php
namespace NorthslopePL\Metassione2;

class POPOConverter
{
	/**
	 * @param object|array $value
	 *
	 * @return \stdClass
	 */
	public function convert($value)
	{
		if (is_array(($value)))
		{
			$retval = [];
			foreach ($value as $object)
			{
				$retval[] = $this->convert($object); // force sequential indexing
			}

			return $retval;
		}
		else if (is_object($value))
		{
			return $this->convertObject($value);
		}
		else
		{
			return $value;
		}
	}

	private function convertObject($object)
	{
		$retvalObject = new \stdClass();

		$reflectionObject = new \ReflectionObject($object);

		foreach ($this->getPropertiesFromObjectOrParentClasses($reflectionObject) as $property)
		{
			$property->setAccessible(true);
			$propertyName = $property->getName();
			$propertyValue = $property->getValue($object);

			if (is_object($propertyValue))
			{
				$propertyValue = $this->convert($propertyValue);
			}
			else if (is_array($propertyValue))
			{
				$propertyValue = $this->convert($propertyValue);
			}
			else
			{
				// just use $propertyValue unchanged
			}

			$retvalObject->$propertyName = $propertyValue;
		}

		return $retvalObject;
	}

	/**
	 * @param \ReflectionObject $object
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
	private function getPropertiesFromObjectOrParentClasses(\ReflectionObject $object)
	{
		$allProperties = array();
		$currentClassReflection = $object;

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
