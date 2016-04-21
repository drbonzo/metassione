<?php
namespace NorthslopePL\Metassione2\Metadata;

use ReflectionClass;
use ReflectionProperty;

class ClassDefinitionBuilder
{
	/**
	 * @var ClassPropertyFinder
	 */
	private $classPropertyFinder;

	public function __construct(ClassPropertyFinder $classPropertyFinder)
	{
		$this->classPropertyFinder = $classPropertyFinder;
	}

	/**
	 * @param string $classname
	 *
	 * @return ClassDefinition
	 */
	public function buildFromClass($classname)
	{
		$reflectionClass = new ReflectionClass($classname);

		$classDefinition = new ClassDefinition();
		$classDefinition->name = $reflectionClass->getName();
		$classDefinition->namespace = $reflectionClass->getNamespaceName();

		$reflectionProperties = $this->classPropertyFinder->findProperties($reflectionClass);

		foreach ($reflectionProperties as $reflectionProperty) {
			$classDefinition->properties[$reflectionProperty->getName()] = $this->buildPropertyDefinition($reflectionProperty);
		}

		return $classDefinition;
	}

	private function buildPropertyDefinition(ReflectionProperty $reflectionProperty)
	{
		$phpdoc = $reflectionProperty->getDocComment();

		// -------------------------------+-----------+-----------+------------------
		//									isObject	isArray		type
		// -------------------------------+-----------+-----------+------------------
		// @var integer						false		false		integer
		// @var integer[]					false		true		integer
		// @var integer[]|array				false		true		integer
		// @var array|integer[]				false		true		integer
		// @var Klass						true		false		Klass
		// @var Klass[]						true		true		Klass
		// @var Klass[]|array				true		true		Klass
		// @var array|Klass[]				true		true		Klass
		// @var Namespaced\Klass			true		false		Namespaced\Klass
		// @var Namespaced\Klass[]			true		true		Namespaced\Klass
		// @var Namespaced\Klass[]|array	true		true		Namespaced\Klass
		// @var array\Namespaced\Klass[]	true		true		Namespaced\Klass
		//
		// all other definitions are invalid

		$pattern = '#@var\\s+(.+?)\\s*\\n#m';
		if (preg_match($pattern, $phpdoc, $m)) {
			$phpdocTypeSpecification = $m[1];
		} else {
			$phpdocTypeSpecification = null;
		}

		if ($this->propertyTypeIsUndefined($phpdocTypeSpecification)) {
			return new PropertyDefinition($reflectionProperty->getName(), false, false, false, 'null', true);
		}

		$typeSpecifications = explode('|', $phpdocTypeSpecification);
		$typeIsNullable = $this->isNullable($typeSpecifications);
		$firstConcreteTypeSpecification = $this->extractConcreteTypeSpecification($typeSpecifications);

		// only first defined type is taken
		if ($this->typeIsArray($typeSpecifications)) {
			return $this->buildPropertyDefinitionForArray($reflectionProperty, $firstConcreteTypeSpecification, $typeIsNullable); // WTF teraz to
		} else {
			if ($this->isBasicType($firstConcreteTypeSpecification)) {
				return new PropertyDefinition($reflectionProperty->getName(), true, false, false, $firstConcreteTypeSpecification, $typeIsNullable);
			} else {
				return new PropertyDefinition($reflectionProperty->getName(), true, true, false, $firstConcreteTypeSpecification, $typeIsNullable);
			}
		}
	}

	/**
	 * @param array|string[] $typeSpecifications
	 * @return bool
	 */
	private function typeIsArray($typeSpecifications)
	{
		foreach ($typeSpecifications as $typeSpecification) {
			// @var SomeType[]
			// @var SomeType[]|array
			// @var array|SomeType[]
			// but not:
			// @var array
			if (substr($typeSpecification, -2, 2) === '[]') {
				return true;
			}
		}

		return false;
	}

	/**
	 *
	 * @param ReflectionProperty $reflectionProperty
	 * @param string $type
	 * @param boolean $typeIsNullable
	 *
	 * @return \NorthslopePL\Metassione\ObjectPropertyType
	 */
	private function buildPropertyDefinitionForArray(ReflectionProperty $reflectionProperty, $type, $typeIsNullable)
	{
		// 'Foobar[]'
		$type = str_replace('[]', '', $type); // Fooobar[] => Foobar
		$type = ltrim($type, '\\'); // remove \ from the beginning

		if ($this->isBasicType($type)) {
			return new PropertyDefinition($reflectionProperty->getName(), true, false, true, $type, $typeIsNullable);
		} else {
			return new PropertyDefinition($reflectionProperty->getName(), true, true, true, $type, $typeIsNullable);
		}
	}

	/**
	 * @param $type
	 * @return boolean
	 */
	private function isBasicType($type)
	{
		$basicTypes = [
			PropertyDefinition::BASIC_TYPE_STRING,
			PropertyDefinition::BASIC_TYPE_INTEGER,
			PropertyDefinition::BASIC_TYPE_INT,
			PropertyDefinition::BASIC_TYPE_FLOAT,
			PropertyDefinition::BASIC_TYPE_DOUBLE,
			PropertyDefinition::BASIC_TYPE_BOOLEAN,
			PropertyDefinition::BASIC_TYPE_BOOL,
			PropertyDefinition::BASIC_TYPE_VOID,
			PropertyDefinition::BASIC_TYPE_MIXED,
			PropertyDefinition::BASIC_TYPE_NULL,
		];

		$isBasic = in_array($type, $basicTypes);
		return $isBasic;
	}

	/**
	 * @param string[] $typeSpecifications
	 * @return string|null
	 */
	private function extractConcreteTypeSpecification($typeSpecifications)
	{
		$nonConcreteTypes = [
			PropertyDefinition::BASIC_TYPE_VOID,
			PropertyDefinition::BASIC_TYPE_NULL,
			PropertyDefinition::BASIC_TYPE_MIXED,
			'array',
		];
		foreach ($typeSpecifications as $typeSpecification) {
			if (!in_array($typeSpecification, $nonConcreteTypes)) {
				return $typeSpecification;
			}
		}

		return null; // FIXME exception? DRY this with propertyTypeIsUndefined()
	}

	/**
	 * @param $phpdocTypeSpecification
	 * @return bool
	 */
	private function propertyTypeIsUndefined($phpdocTypeSpecification)
	{
		if (empty($phpdocTypeSpecification)) {
			return true;
		}

		if (strpos(PropertyDefinition::BASIC_TYPE_VOID, $phpdocTypeSpecification) !== false) {
			return true;
		}

		if (strpos(PropertyDefinition::BASIC_TYPE_NULL, $phpdocTypeSpecification) !== false) {
			return true;
		}

		if (strpos(PropertyDefinition::BASIC_TYPE_MIXED, $phpdocTypeSpecification) !== false) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param $typeSpecifications
	 * @return bool
	 */
	private function isNullable($typeSpecifications)
	{
		// @var integer|null
		// @var Klass|NULL
		// @var null|Klass
		//
		// are nullable
		return in_array('null', $typeSpecifications) || in_array('NULL', $typeSpecifications);
	}
}
