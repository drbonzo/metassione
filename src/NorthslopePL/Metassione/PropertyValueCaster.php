<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use ReflectionClass;

class PropertyValueCaster
{
	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @param mixed $rawValue
	 * @return mixed
	 */
	public function getBasicValueForProperty(PropertyDefinition $propertyDefinition, $rawValue)
	{
		if ($propertyDefinition->getIsObject() || $propertyDefinition->getIsArray()) {
			return null;
		}

		if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
			if (is_object($rawValue) || is_array($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : 0;
			} else if (is_numeric($rawValue) || is_bool($rawValue)) {
				return intval($rawValue);
			} else {
				return $propertyDefinition->getIsNullable() ? null : 0;
			}
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_FLOAT) {
			if (is_object($rawValue) || is_array($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : 0.0;
			} else if (is_numeric($rawValue) || is_bool($rawValue)) {
				return floatval($rawValue);
			} else {
				return $propertyDefinition->getIsNullable() ? null : 0.0;
			}
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_STRING) {
			if (is_object($rawValue) || is_array($rawValue) || is_null($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : '';
			} else {
				return strval($rawValue);
			}
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_BOOLEAN) {
			if (is_object($rawValue) || is_array($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : false;
			} else if ($rawValue !== null) {
				return boolval($rawValue);
			} else {
				return $propertyDefinition->getIsNullable() ? null : false;
			}
		} else {
			return null;
		}
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @return mixed
	 */
	public function getEmptyBasicValueForProperty(PropertyDefinition $propertyDefinition)
	{
		if ($propertyDefinition->getIsObject() || $propertyDefinition->getIsArray()) {
			return null;
		}

		if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
			return $propertyDefinition->getIsNullable() ? null : 0;
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_FLOAT) {
			return $propertyDefinition->getIsNullable() ? null : 0.0;
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_STRING) {
			return $propertyDefinition->getIsNullable() ? null : '';
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_BOOLEAN) {
			return $propertyDefinition->getIsNullable() ? null : false;
		} else {
			return null;
		}
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @param $rawValue
	 *
	 * @return array|mixed[]
	 */
	public function getBasicValueForArrayProperty(PropertyDefinition $propertyDefinition, $rawValue)
	{
		if (!is_array($rawValue)) {
			return [];
		}

		if (!$propertyDefinition->getIsArray()) {
			return [];
		}

		$values = [];

		foreach ($rawValue as $rawValueItem) {

			if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
				if (is_numeric($rawValueItem) || is_bool($rawValueItem) || is_string($rawValueItem)) {
					$values[] = intval($rawValueItem);
				}

			} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_FLOAT) {
				if (is_numeric($rawValueItem) || is_bool($rawValueItem) || is_string($rawValueItem)) {
					$values[] = floatval($rawValueItem);
				}
			} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_STRING) {
				if (!is_array($rawValueItem) && !is_object($rawValueItem)) {
					$values[] = strval($rawValueItem);
				}
			} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_BOOLEAN) {
				if (!is_array($rawValueItem) && !is_object($rawValueItem)) {
					$values[] = boolval($rawValueItem);
				}
			} else {
				// skip this value
			}
		}

		return $values;
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @return array
	 */
	public function getEmptyValueForArrayProperty(PropertyDefinition $propertyDefinition)
	{
		return [];
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @param mixed $rawValue
	 * @return object
	 */
	public function getObjectValueForProperty(PropertyDefinition $propertyDefinition, $rawValue)
	{
		if (!$propertyDefinition->getIsObject() || $propertyDefinition->getIsArray()) {
			return null;
		}

		if (is_object($rawValue)) {
			return $rawValue;
		} else {
			if ($propertyDefinition->getIsNullable()) {
				return null;
			} else {
				$reflectionClass = new ReflectionClass($propertyDefinition->getType());
				return $reflectionClass->newInstance();
			}
		}
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @return object|null
	 */
	public function getEmptyObjectValueForProperty(PropertyDefinition $propertyDefinition)
	{
		if (!$propertyDefinition->getIsObject() || $propertyDefinition->getIsArray()) {
			return null;
		}

		if ($propertyDefinition->getIsNullable()) {
			return null;
		} else {
			$reflectionClass = new ReflectionClass($propertyDefinition->getType());
			return $reflectionClass->newInstance();
		}
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @param mixed $rawValue
	 * @return object[]
	 */
	public function getObjectValueForArrayProperty(PropertyDefinition $propertyDefinition, $rawValue)
	{
		if (!$propertyDefinition->getIsObject() || !$propertyDefinition->getIsArray()) {
			return [];
		}

		if (is_array($rawValue)) {
			$values = [];

			foreach ($rawValue as $rawValueItem) {
				if (is_object($rawValueItem)) {
					$values[] = $rawValueItem;
				}
			}

			return $values;
		} else {
			return [];
		}
	}

}
