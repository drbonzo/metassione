<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;

class PropertyValueCaster
{
	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @param mixed $rawValue
	 * @return mixed
	 */
	public function getBasicValueForProperty(PropertyDefinition $propertyDefinition, $rawValue)
	{
		if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
			if (is_object($rawValue) || is_array($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : 0;
			} else {
				if (is_numeric($rawValue) || is_bool($rawValue)) {
					return intval($rawValue);
				} else {
					return $propertyDefinition->getIsNullable() ? null : 0;
				}
			}
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_FLOAT) {
			if (is_object($rawValue) || is_array($rawValue)) {
				return $propertyDefinition->getIsNullable() ? null : 0.0;
			} else {
				if (is_numeric($rawValue) || is_bool($rawValue)) {
					return floatval($rawValue);
				} else {
					return $propertyDefinition->getIsNullable() ? null : 0.0;
				}
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
		if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
			return $propertyDefinition->getIsNullable() ? null : 0;
		} else if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_FLOAT) {
			return $propertyDefinition->getIsNullable() ? null : 0.0;
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
		if (is_array($rawValue)) {
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
				}
			}

			return $values;
		} else {
			return [];
		}
	}

	/**
	 * @param PropertyDefinition $propertyDefinition
	 * @return array
	 */
	public function getEmptyBasicValueForArrayProperty(PropertyDefinition $propertyDefinition)
	{
		return [];
	}
}
