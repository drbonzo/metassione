<?php
namespace NorthslopePL\Metassione2;

use NorthslopePL\Metassione2\Metadata\ClassDefinition;
use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;

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

				$dataForProperty = isset($rawData->{$reflectionProperty->getName()}) ? $rawData->{$reflectionProperty->getName()} : null;

				if ($propertyDefinition->getIsObject()) {

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

						$targetObjectForProperty = $this->newInstance($classDefinitionForProperty->name);

						$this->processObject($classDefinitionForProperty, $targetObjectForProperty, $dataForProperty);

						$reflectionProperty->setValue($targetObject, $targetObjectForProperty);

					}

				} else {

					$reflectionProperty->setValue($targetObject, $dataForProperty);
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
}
