<?php
namespace NorthslopePL\Metassione2;

use NorthslopePL\Metassione2\Metadata\ClassDefinition;
use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
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
				$dataForProperty = $hasData ? $rawData->{$reflectionProperty->getName()} : null;

				if ($propertyDefinition->getIsObject()) {

					// FIXME if ! hasData = use empty object or null (if alloed)
					$classDefinitionForProperty = $this->classDefinitionBuilder->buildFromClass($propertyDefinition->getType());
					if ($propertyDefinition->getIsArray()) {

						$values = [];
						foreach ((array)$dataForProperty as $item) {
							$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);
							$this->processObject($classDefinitionForProperty, $targetObjectForProperty, $item);
							$values[] = $targetObjectForProperty;
						}
						$reflectionProperty->setValue($targetObject, $values);

					} else {

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

				} else {

					$this->setBasicValue($hasData, $reflectionProperty, $targetObject, $dataForProperty);
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
}
