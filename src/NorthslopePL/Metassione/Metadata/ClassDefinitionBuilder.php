<?php
namespace NorthslopePL\Metassione\Metadata;

use LogicException;
use ReflectionClass;
use ReflectionProperty;

class ClassDefinitionBuilder implements ClassDefinitionBuilderInterface
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


		$typeSpecifications = explode('|', $phpdocTypeSpecification);
		$firstConcreteTypeSpecification = $this->extractConcreteTypeSpecification($typeSpecifications);

		if ($this->propertyTypeIsUndefined($firstConcreteTypeSpecification)) {
			return new PropertyDefinition($reflectionProperty->getName(), false, false, false, PropertyDefinition::BASIC_TYPE_NULL, true, $reflectionProperty);
		}

		$typeIsNullable = $this->isNullable($typeSpecifications);

		// only first defined type is taken
		if ($this->typeIsArray($typeSpecifications)) {
			// array cannot be set to null, if empty - then set to empty array
			return $this->buildPropertyDefinitionForArray($reflectionProperty, $firstConcreteTypeSpecification, false);
		} else {
			if ($this->isBasicType($firstConcreteTypeSpecification)) {
				return new PropertyDefinition($reflectionProperty->getName(), true, false, false, $this->mapBasicType($firstConcreteTypeSpecification), $typeIsNullable, $reflectionProperty);
			} else {
				$classname = $this->buildClassnameForType($firstConcreteTypeSpecification, $reflectionProperty);
				return new PropertyDefinition($reflectionProperty->getName(), true, true, false, $classname, $typeIsNullable, $reflectionProperty);
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
	 * @return PropertyDefinition
	 */
	private function buildPropertyDefinitionForArray(ReflectionProperty $reflectionProperty, $type, $typeIsNullable)
	{
		// 'Foobar[]'
		$type = str_replace('[]', '', $type); // Fooobar[] => Foobar
		$type = ltrim($type, '\\'); // remove \ from the beginning

		if ($this->isBasicType($type)) {
			return new PropertyDefinition($reflectionProperty->getName(), true, false, true, $type, $typeIsNullable, $reflectionProperty);
		} else {
			$classname = $this->buildClassnameForType($type, $reflectionProperty);
			return new PropertyDefinition($reflectionProperty->getName(), true, true, true, $classname, $typeIsNullable, $reflectionProperty);
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

		// @var void|null
		// etc.
		return PropertyDefinition::BASIC_TYPE_NULL;
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

		if (strpos(PropertyDefinition::BASIC_TYPE_NULL, $phpdocTypeSpecification) !== false) {
			// BASIC_TYPE_VOID and BASIC_TYPE_MIXED are coverted to BASIC_TYPE_NULL in extractConcreteTypeSpecification()
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

	private function buildClassnameForType($classname, ReflectionProperty $reflectionProperty)
	{
		$classname = ltrim($classname, '\\'); // classnames are written as: "\Foo\Bar" not "Foo\Bar"
		if (class_exists($classname, true)) {
			// Fully qualified classname
			// or classname without namespace
			return $classname;
		}

		$fullyQualifiedClassName = $reflectionProperty->getDeclaringClass()->getNamespaceName() . '\\' . $classname;

		if (class_exists($fullyQualifiedClassName, true)) {
			return $fullyQualifiedClassName;
		} else {
			throw new LogicException(sprintf('Class %s (%s) not found for property %s::%s', $classname, $fullyQualifiedClassName, $reflectionProperty->getDeclaringClass()->getName(), $reflectionProperty->getName()));
		}
	}

	/**
	 * @param string $basicType
	 * @return string
	 */
	private function mapBasicType($basicType)
	{
		$map = [
			PropertyDefinition::BASIC_TYPE_INT => PropertyDefinition::BASIC_TYPE_INTEGER,
			PropertyDefinition::BASIC_TYPE_BOOL => PropertyDefinition::BASIC_TYPE_BOOLEAN,
			PropertyDefinition::BASIC_TYPE_DOUBLE => PropertyDefinition::BASIC_TYPE_FLOAT,
		];

		if (isset($map[$basicType])) {
			return $map[$basicType];
		} else {
			return $basicType;
		}
	}
}
