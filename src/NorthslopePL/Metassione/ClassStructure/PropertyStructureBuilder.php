<?php
namespace NorthslopePL\Metassione\ClassStructure;

class PropertyStructureBuilder
{
	/**
	 * Basic PHP types
	 *
	 * @var array|string[]
	 */
	static private $basicTypes = ['string', 'integer', 'int', 'boolean', 'bool', 'float', 'double', 'void', 'mixed', 'null'];

	public function buildPropertyStructure(\ReflectionProperty $reflectionProperty)
	{
		$propertyStructure = new PropertyStructure();
		$propertyStructure->setName($reflectionProperty->getName());
		$propertyStructure->setDefinedInClass($reflectionProperty->getDeclaringClass()->getName());

		$phpdocTypeSpecification = $this->getTypeSpecificationFromPhpdoc($reflectionProperty);
		$this->buildPropertyStructureFromTypeSpecification($propertyStructure, $phpdocTypeSpecification);

		return $propertyStructure;
	}

	/**
	 * @param \ReflectionProperty $reflectionProperty
	 * @return string
	 */
	private function getTypeSpecificationFromPhpdoc(\ReflectionProperty $reflectionProperty)
	{
		$phpdoc = trim($reflectionProperty->getDocComment());
		if (empty($phpdoc))
		{
			return '';
		}
		else
		{
			$pattern = '#@var\\s*(.+?)\\s*\\n#m';
			if (preg_match($pattern, $phpdoc, $m))
			{
				$phpdocTypeSpecification = $m[1];
			}
			else
			{
				$phpdocTypeSpecification = '';
			}

			return $phpdocTypeSpecification;
		}
	}

	/**
	 * Extracts proeprty type from its php doc comment
	 *
	 * Searches in property phpdoc's comment for:
	 *
	 * [AT]var SomeClass => ['object', 'SomeClass']
	 * [AT]var NameSpace\SomeClass => ['object', 'NameSpace\SomeClass']
	 * [AT]var array|int[] => ['array', null ]
	 * [AT]var array|SomeClass[] => ['array', 'SomeClass']
	 * [AT]var SomeClass[]|array => ['array', 'SomeClass']
	 * [AT]var SomeClass[] => ['array', 'SomeClass']
	 *
	 * @param PropertyStructure $propertyStructure
	 * @param string $phpdocTypeSpecification
	 *
	 * @return void
	 */
	private function buildPropertyStructureFromTypeSpecification(PropertyStructure $propertyStructure, $phpdocTypeSpecification)
	{
		if ($phpdocTypeSpecification)
		{
			$propertyStructure->markAsTypeKnown();

			if ($phpdocTypeSpecification == 'void')
			{
				$propertyStructure->setIsArray(false);
				$propertyStructure->setIsPrimitive(true);
				$propertyStructure->setType('void');

				return;
			}

			$typesSpecification = explode('|', $phpdocTypeSpecification);

			if ($this->typeIsArray($typesSpecification))
			{
				// Klass[], OtherKlass[]
				// or
				// int[], string[], float[]

				$this->buildObjectPropertyTypeForArray($propertyStructure, $typesSpecification);
			}
			else
			{

				if (in_array($phpdocTypeSpecification, self::$basicTypes))
				{
					$propertyStructure->setIsArray(false);
					$propertyStructure->setIsPrimitive(true);
					$propertyStructure->setType($phpdocTypeSpecification);
				}
				else
				{
					$propertyStructure->setIsArray(false);
					$propertyStructure->setIsPrimitive(false);
					$propertyStructure->setType($phpdocTypeSpecification);
				}
			}
		}
		else
		{
			$propertyStructure->markAsTypeUnknown();
		}
	}

	/**
	 * @param array|string[] $typesSpecification
	 *
	 * @return bool
	 */
	private function typeIsArray($typesSpecification)
	{
		foreach ($typesSpecification as $typeSpecification)
		{
			// @var SomeType[]
			if (substr($typeSpecification, -2, 2) === '[]')
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param PropertyStructure $propertyStructure
	 * @param array|string[] $typesSpecification
	 *
	 * @return void
	 */
	private function buildObjectPropertyTypeForArray(PropertyStructure $propertyStructure, $typesSpecification)
	{
		// @param array|Foobar[] $propertyName
		// => ['array', 'Foobar[]']
		// => ['array', 'Foobar']
		// we take first value that is not 'array', here: 'Foobar'
		foreach ($typesSpecification as $type)
		{
			$type = str_replace('[]', '', $type); // Fooobar[] => Foobar
			$type = ltrim($type, '\\'); // remove \ from the beginning

			if ($type == 'array')
			{
				// 'array|...'
				continue;
			}

			if (in_array($type, self::$basicTypes))
			{
				$propertyStructure->setIsArray(true);
				$propertyStructure->setIsPrimitive(true);
				$propertyStructure->setType($type);

				return;
			}
			else
			{
				$propertyStructure->setIsArray(true);
				$propertyStructure->setIsPrimitive(false);
				$propertyStructure->setType($type);
				return;
			}
		}

		$propertyStructure->setIsArray(true);
		$propertyStructure->setIsPrimitive(true);
		$propertyStructure->setType('mixed');
	}
}
