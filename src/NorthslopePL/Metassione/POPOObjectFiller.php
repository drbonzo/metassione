<?php
namespace NorthslopePL\Metassione;

class POPOObjectFiller
{
	/**
	 * @param object $targetObject
	 * @param \stdClass $rawData
	 *
	 * @throws ObjectFillingException
	 * - if class for property does not exist
	 * - if $rawData is not an object
	 */
	public function fillObjectWithRawData($targetObject, $rawData)
	{
		if (!is_object($rawData))
		{
			$message = sprintf('Raw data should be an object, but %s was given. I was trying to fill object of class %s.', gettype($rawData), get_class($targetObject));
			if (is_array($rawData))
			{
				$message .= sprintf(' Maybe you have forgotten about adding "array|" for property that holds this class %s?', get_class($targetObject));
				throw new ObjectFillingException($message);
			}
			else
			{
				throw new ObjectFillingException($message);
			}
		}

		$rawDataReflection = new \ReflectionObject($rawData);
		$targetObjectReflection = new \ReflectionObject($targetObject);

		foreach ($rawDataReflection->getProperties() as $rawDataProperty)
		{
			$this->processProperty($rawDataProperty, $rawData, $targetObject, $targetObjectReflection);
		}
	}

	/**
	 * @param \ReflectionProperty $rawDataProperty
	 * @param \stdClass $rawData
	 * @param object $targetObject
	 * @param \ReflectionObject $targetObjectReflection
	 *
	 * @throws ObjectFillingException
	 */
	private function processProperty(\ReflectionProperty $rawDataProperty, $rawData, $targetObject, \ReflectionObject $targetObjectReflection)
	{
		try
		{
			$targetObjectProperty = $this->getPropertyFromObjectOrParentClasses($targetObjectReflection, $rawDataProperty->getName());
			if ($targetObjectProperty)
			{
				$newPropertyValue = $this->buildPropertyValue($rawDataProperty, $rawData, $targetObjectProperty);
				$targetObjectProperty->setAccessible(true); // to access private/protected properties
				$targetObjectProperty->setValue($targetObject, $newPropertyValue);
			}
		}
		catch (\ReflectionException $e)
		{
			throw new ObjectFillingException($e);
		}
	}

	/**
	 * @param \ReflectionProperty $rawDataProperty
	 * @param \stdClass $rawData
	 * @param \ReflectionProperty $targetObjectProperty
	 *
	 * @return mixed new value for the property
	 *
	 * @throws ObjectFillingException
	 * @throws \ReflectionException
	 */
	private function buildPropertyValue(\ReflectionProperty $rawDataProperty, $rawData, \ReflectionProperty $targetObjectProperty)
	{
		$rawDataPropertyValue = $rawDataProperty->getValue($rawData);

		$phpDocParser = new PHPDocParser();
		$objectPropertyType = $phpDocParser->getPropertyTypeFromPHPDoc($targetObjectProperty->getDocComment());

		if ($objectPropertyType->getGeneralType() == ObjectPropertyType::GENERAL_TYPE_OBJECT)
		{
			$classname = $objectPropertyType->getDataType();
			$targetObjectPropertyClassReflection = $this->getClassReflectionForClassName($classname, $targetObjectProperty);
			// this property is an object
			//
			// /**
			//  * @var Foobar
			//  */
			// private $fooBar;
			//
			// so build object of this class and fill this object with data from $propertyValue
			if ($rawDataPropertyValue === null)
			{
				// there is no data for given object
				// use null instead of empty object
				return null;
			}

			$newValue = $targetObjectPropertyClassReflection->newInstance();
			$this->fillObjectWithRawData($newValue, $rawDataPropertyValue);

			return $newValue;
		}
		else if ($objectPropertyType->getGeneralType() == ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS)
		{
			$newValues = [];
			// optimization, take class reflection creation out of the loop. Its 2x faster 1.3s vs 3.3s for 1 000 000 iterations.
			$classname = $objectPropertyType->getDataType();
			$targetObjectPropertyClassReflection = $this->getClassReflectionForClassName($classname, $targetObjectProperty);

			$rawDataPropertyValue = is_array($rawDataPropertyValue) ? $rawDataPropertyValue : (array)$rawDataPropertyValue;

			foreach ($rawDataPropertyValue as $rawDataPropertyValueItem) // cast to array handles null values when expecting arrays
			{
				$newValue = $targetObjectPropertyClassReflection->newInstance();
				$this->fillObjectWithRawData($newValue, $rawDataPropertyValueItem);
				$newValues[] = $newValue;
			}

			return $newValues;
		}
		else if ($objectPropertyType->getGeneralType() == ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_SIMPLE_TYPES)
		{
			$newValues = [];
			// this is object but we dont know the class or this is a simple type
			// @var array|int[]
			// @var array|AClassThatIsNotFound[]
			$rawDataPropertyValue = is_array($rawDataPropertyValue) ? $rawDataPropertyValue : (array)$rawDataPropertyValue;
			foreach ($rawDataPropertyValue as $rawDataPropertyValueItem)
			{
				$newValue = $rawDataPropertyValueItem;
				$newValues[] = $newValue;
			}

			return $newValues;
		}
		else
		{
			// other type
			$newValue = $rawDataPropertyValue;
			return $newValue;
		}
	}

	/**
	 * @param string $classname
	 * @param \ReflectionProperty $targetObjectProperty
	 *
	 * @return \ReflectionClass
	 *
	 * @throws ObjectFillingException
	 */
	private function getClassReflectionForClassName($classname, \ReflectionProperty $targetObjectProperty)
	{
		$classname = ltrim($classname, '\\');

		if (!class_exists($classname, true))
		{
			$classnameWithNamespaceAdded = $targetObjectProperty->getDeclaringClass()->getNamespaceName() . '\\' . $classname;
			if (class_exists($classnameWithNamespaceAdded, true))
			{
				$classname = $classnameWithNamespaceAdded;
			}
			else
			{
				$message = sprintf('Class "%s" does not exist for property %s::$%s.', $classname, $targetObjectProperty->getDeclaringClass()->getName(), $targetObjectProperty->getName());

				$namespaceNotDetectedInClassname = (strpos($classname, '\\') === false);
				if ($namespaceNotDetectedInClassname)
				{
					$message .= sprintf(' Maybe you have forgotten to use fully qualified class name (with namespace, example: \Foo\Bar\%s)?', $classname);
				}

				throw new ObjectFillingException($message);
			}
		}

		$classReflection = new \ReflectionClass($classname);
		return $classReflection;
	}

	/**
	 * @param \ReflectionObject $object
	 * @param string $propertyName
	 * @return null|\ReflectionProperty
	 * null if property is not found
	 *
	 *
	 * \ReflectionClass::hasProperty() checks only properties from current class and all nonprivate properties from parent classes.
	 * So private properties from parent classes will not be found.
	 *
	 * So does getProperty().
	 *
	 * We have workaround for this problem, by checking parent classes for property with name $propertyName.
	 *
	 * http://www.php.net/manual/en/reflectionclass.hasproperty.php#94038
	 * http://stackoverflow.com/questions/9913680/does-reflectionclassgetproperties-also-get-properties-of-the-parent
	 */
	private function getPropertyFromObjectOrParentClasses(\ReflectionObject $object, $propertyName)
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
