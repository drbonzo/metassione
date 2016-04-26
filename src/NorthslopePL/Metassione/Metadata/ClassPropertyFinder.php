<?php
namespace NorthslopePL\Metassione\Metadata;

use ReflectionClass;
use ReflectionProperty;

class ClassPropertyFinder
{
	/**
	 * @param ReflectionClass $reflectionClass
	 *
	 * @return ReflectionProperty[]
	 */
	public function findProperties(ReflectionClass $reflectionClass)
	{
		$allProperties = [];

		/** @var ReflectionClass $currentReflectionClass */
		$currentReflectionClass = $reflectionClass;

		while ($currentReflectionClass) {// class without parent has null in getParentClass()

			$properties = $currentReflectionClass->getProperties();

			foreach ($properties as $property) {
				if (isset($allProperties[$property->getName()])) {
					// Do not add the same property few times
					// If parent class has public or protected property - the same property will be returned for the Child class
					// As child class is processed first - its property will be taken
					continue;
				}

				$allProperties[$property->getName()] = $property;
			}

			$currentReflectionClass = $currentReflectionClass->getParentClass();
		}

		return $allProperties;
	}
}