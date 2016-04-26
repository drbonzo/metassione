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

			if ($propertyDefinition->getIsDefined()) {

				$reflectionProperty = $propertyDefinition->getReflectionProperty();
				$reflectionProperty->setAccessible(true);

				$hasData = is_object($rawData) && property_exists($rawData, $reflectionProperty->getName());
				$rawValue = $hasData ? $rawData->{$reflectionProperty->getName()} : null;

				if ($propertyDefinition->getIsObject()) {

					if ($propertyDefinition->getIsArray()) {

						$values = [];
						foreach ((array)$rawValue as $item) {
							$classDefinitionForProperty = $this->classDefinitionBuilder->buildFromClass($propertyDefinition->getType());
							$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);
							$this->processObject($classDefinitionForProperty, $targetObjectForProperty, $item);
							$values[] = $targetObjectForProperty;
						}
						$reflectionProperty->setValue($targetObject, $values);

					} else {
						$this->setObjectValue($hasData, $reflectionProperty, $targetObject, $rawValue, $propertyDefinition);
					}

				} else {

					$this->setBasicValue($hasData, $reflectionProperty, $targetObject, $rawValue);
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
	 */
	private function setBasicValue($hasData, ReflectionProperty $reflectionProperty, $targetObject, $value)
	{
		if ($hasData) {
			$reflectionProperty->setValue($targetObject, $value);
		} else {
			// we dont have data for this property - so dont change it - default value will be used
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
