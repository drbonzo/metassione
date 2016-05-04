<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\ClassDefinition;
use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use ReflectionProperty;

class POPOObjectFiller
{
	/**
	 * @var ClassDefinitionBuilder
	 */
	private $classDefinitionBuilder;

	public function __construct(ClassDefinitionBuilder $classDefinitionBuilder)
	{
		$this->classDefinitionBuilder = $classDefinitionBuilder;
	}

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
		$classDefinition = $this->classDefinitionBuilder->buildFromClass($targetObject);

		$this->processObject($classDefinition, $targetObject, $rawData);
	}

	private function processObject(ClassDefinition $classDefinition, $targetObject, $rawData)
	{
		foreach ($classDefinition->properties as $propertyDefinition) {

			$this->processObjectProperty($propertyDefinition, $targetObject, $rawData);
		}
	}

	private function processObjectProperty(PropertyDefinition $propertyDefinition, $targetObject, $rawData)
	{
		if ($propertyDefinition->getIsDefined()) {

			$reflectionProperty = $propertyDefinition->getReflectionProperty();
			$reflectionProperty->setAccessible(true);

			$hasData = is_object($rawData) && property_exists($rawData, $reflectionProperty->getName());
			$rawValue = $hasData ? $rawData->{$reflectionProperty->getName()} : null;

			if ($propertyDefinition->getIsObject()) {

				if ($propertyDefinition->getIsArray()) {

					// FIXME
//						$values = [];
//						foreach ((array)$rawValue as $rawValueItem) {
//							$classDefinitionForProperty = $this->classDefinitionBuilder->buildFromClass($propertyDefinition->getType());
//							$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);
//							$this->processObject($classDefinitionForProperty, $targetObjectForProperty, $rawValueItem);
//							$values[] = $targetObjectForProperty;
//						}
//					$reflectionProperty->setValue($targetObject, $values);

				} else {
					$this->setObjectValue($hasData, $reflectionProperty, $targetObject, $rawValue, $propertyDefinition);
				}

			} else {

				if ($propertyDefinition->getIsArray()) {

					if (is_array($rawValue)) {
						$values = [];
						foreach ($rawValue as $rawValueItem) {
							$basicValue = $this->getBasicValue(true, $reflectionProperty, $targetObject, $rawValueItem, $propertyDefinition);
							$values[] = $basicValue;
						}
						$reflectionProperty->setValue($targetObject, $values);
					} else {
						$reflectionProperty->setValue($targetObject, []);
					}

				} else {
					$basicValue = $this->getBasicValue($hasData, $reflectionProperty, $targetObject, $rawValue, $propertyDefinition);
					$reflectionProperty->setValue($targetObject, $basicValue);
				}
			}
		}
	}

	/**
	 * @param $classname
	 * @return object
	 */
	private function newInstance($classname)
	{
		// FIXME class exists?
		return new $classname();
	}

	/**
	 * @param boolean $hasData
	 * @param ReflectionProperty $reflectionProperty
	 * @param object $targetObject
	 * @param mixed $value
	 * @param PropertyDefinition $propertyDefinition
	 *
	 * @return mixed
	 */
	private function getBasicValue($hasData, ReflectionProperty $reflectionProperty, $targetObject, $value, PropertyDefinition $propertyDefinition)
	{
		if ($hasData) {
			if ($propertyDefinition->getIsNullable() && $value === null) {
				return null;
			} else {

				if ($propertyDefinition->getType() === 'string') {
					if (is_object($value) || is_array($value)) {
						return ($propertyDefinition->getIsNullable() ? null : '');
					} else {
						return strval($value);
					}
				} else {
					// FIXME
					return $value;
				}
			}

		} else {

			// NULL values when there is no value

			if ($propertyDefinition->getIsNullable()) {
				return null;
			} else {

				if ($propertyDefinition->getType() === 'string') {
					return '';
				} else {
					// FIXME
					return $value;
				}
			}
		}
	}

	/**
	 * @param boolean $hasData
	 * @param ReflectionProperty $reflectionProperty
	 * @param object $targetObject
	 * @param mixed $dataForProperty
	 * @param PropertyDefinition $propertyDefinition
	 */
	private function setObjectValue($hasData, ReflectionProperty $reflectionProperty, $targetObject, $dataForProperty, PropertyDefinition $propertyDefinition)
	{
		$classDefinitionForProperty = $this->classDefinitionBuilder->buildFromClass($propertyDefinition->getType());

		if ($hasData) {
			$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);
			$this->processObject($classDefinitionForProperty, $targetObjectForProperty, $dataForProperty);
			$reflectionProperty->setValue($targetObject, $targetObjectForProperty);
		} else {
			// no data for property
			if ($propertyDefinition->getIsNullable()) {
				$reflectionProperty->setValue($targetObject, null);
			} else {
				// empty object
				$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);
				$reflectionProperty->setValue($targetObject, $targetObjectForProperty);
			}
		}

	}

}
