<?php
namespace NorthslopePL\Metassione;

class POPOObjectFiller
{
	const PROPERTY_TYPE_ARRAY = 'array';
	const PROPRTTY_TYPE_OBJECT = 'object';
	const PROPERTY_TYPE_OTHER = 'other';

	/**
	 * @param object $targetObject
	 * @param \stdClass $rawData
	 */
	public function fillObjectWithRawData($targetObject, $rawData)
	{
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
	 */
	private function processProperty(\ReflectionProperty $rawDataProperty, $rawData, $targetObject, \ReflectionObject $targetObjectReflection)
	{
		if (!$targetObjectReflection->hasProperty($rawDataProperty->getName()))
		{
			return;
		}

		$targetObjectProperty = $targetObjectReflection->getProperty($rawDataProperty->getName());
		$newPropertyValue = $this->buildPropertyValue($rawDataProperty, $rawData, $targetObjectProperty);
		$targetObjectProperty->setAccessible(true); // to access private/protected properties
		$targetObjectProperty->setValue($targetObject, $newPropertyValue);
	}

	/**
	 * @param \ReflectionProperty $rawDataProperty
	 * @param \stdClass $rawData
	 * @param \ReflectionProperty $targetObjectProperty
	 *
	 * @return mixed new value for the property
	 */
	private function buildPropertyValue(\ReflectionProperty $rawDataProperty, $rawData, \ReflectionProperty $targetObjectProperty)
	{
		$rawDataPropertyValue = $rawDataProperty->getValue($rawData);

		$phpDocParser = new PHPDocParser();
		list($mainType, $classname) = $phpDocParser->getPropertyTypeFromPHPDoc($targetObjectProperty->getDocComment());
		/* @var $mainType string */
		/* @var $classname string */

		$targetObjectPropertyClassReflection = ($classname ? new \ReflectionClass($classname) : null);

		if ($mainType == self::PROPRTTY_TYPE_OBJECT)
		{
			if ($targetObjectPropertyClassReflection)
			{
				// this property is an object
				//
				// /**
				//  * @var Foobar
				//  */
				// private $fooBar;
				//
				// so build object of this class and fill this object with data from $propertyValue
				$newValue = $targetObjectPropertyClassReflection->newInstance();
				$this->fillObjectWithRawData($newValue, $rawDataPropertyValue);
			}
			else
			{
				// this is object but we dont know the class - so store it as raw stdClass etc
				$newValue = $rawDataPropertyValue;
			}

			return $newValue;
		}
		else if ($mainType == self::PROPERTY_TYPE_ARRAY)
		{
			$newValues = [];
			foreach ($rawDataPropertyValue as $rawDataPropertyValueItem)
			{
				if ($targetObjectPropertyClassReflection)
				{
					// this property is an array of objects of given class ($classname)
					//
					// /**
					//  * @var array|Foobar[]
					//  */
					// private $fooBars;
					//
					$newValue = $targetObjectPropertyClassReflection->newInstance();
					$this->fillObjectWithRawData($newValue, $rawDataPropertyValueItem);
					$newValues[] = $newValue;
				}
				else
				{
					// this is object but we dont know the class or this is a simple type
					// @var array|AClassThatIsNotFound[]
					$newValue = $rawDataPropertyValueItem;
					$newValues[] = $newValue;
				}
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
}
