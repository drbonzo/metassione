<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\PropertyDefinition;

class PropertyValueCaster
{
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
		} else {
			return null;
		}
	}

	public function getEmptyBasicValueForProperty(PropertyDefinition $propertyDefinition)
	{
		if ($propertyDefinition->getType() == PropertyDefinition::BASIC_TYPE_INTEGER) {
			return $propertyDefinition->getIsNullable() ? null : 0;
		} else {
			return null;
		}
	}
}
