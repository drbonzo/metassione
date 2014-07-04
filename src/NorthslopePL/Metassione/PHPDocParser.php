<?php
namespace NorthslopePL\Metassione;

class PHPDocParser
{
	const TYPE_OBJECT = 'object';
	const TYPE_ARRAY = 'array';
	const TYPE_OTHER = 'other';
	const TYPE_UNKNOWN = 'unknown';

	static private $basicTypes = ['string', 'integer', 'int', 'boolean', 'bool', 'float', 'double', 'void', 'mixed', 'null'];

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
	 * @param string $phpdoc
	 *
	 * @return ObjectPropertyType
	 */
	public function getPropertyTypeFromPHPDoc($phpdoc)
	{
		$phpdoc = trim($phpdoc);
		if (empty($phpdoc))
		{
			$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
			return $objectPropertyType;
		}

		$pattern = '#@var\\s*(.+?)\\s*\\n#m';
		if (preg_match($pattern, $phpdoc, $m))
		{
			$phpdocTypeSpecification = $m[1];
		}
		else
		{
			$phpdocTypeSpecification = '';
		}

		$objectPropertyType = $this->buildObjectPropertyTypeFromTypeSpecification($phpdocTypeSpecification);
		$this->injectPropertyDescription($objectPropertyType, $phpdoc);
		return $objectPropertyType;
	}

	/**
	 * '[AT]return ANamespace\AKlass'
	 *
	 * @param string $phpdocTypeSpecification
	 *
	 * @return ObjectPropertyType
	 */
	private function buildObjectPropertyTypeFromTypeSpecification($phpdocTypeSpecification)
	{
		if ($phpdocTypeSpecification)
		{
			if ($phpdocTypeSpecification == 'void')
			{
				$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_NONE);
				return $objectPropertyType;
			}

			$typesSpecification = explode('|', $phpdocTypeSpecification);

			if ($this->typeIsArray($typesSpecification))
			{
				// array|Klass[], array|OtherKlass[]
				// or
				// array|int[], array|string[], array|float[]
				$objectPropertyType = $this->buildObjectPropertyTypeForArray($typesSpecification);
				return $objectPropertyType;
			}
			else
			{
				if (in_array($phpdocTypeSpecification, self::$basicTypes))
				{
					$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_SIMPLE_TYPE, $phpdocTypeSpecification);
					return $objectPropertyType;
				}
				else
				{
					$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_OBJECT, $phpdocTypeSpecification);
					return $objectPropertyType;
				}
			}
		}
		else
		{
			$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
			return $objectPropertyType;
		}
	}

	/**
	 * @param string $phpdoc
	 *
	 * @return ObjectPropertyType
	 */
	public function getReturnValueTypeFromMethodPhpdoc($phpdoc)
	{
		if (preg_match('#@return\s+(.+?)\\n#', $phpdoc, $matches))
		{
			$typeSpecification = $matches[1];
			$objectPropertyType = $this->buildObjectPropertyTypeFromTypeSpecification($typeSpecification);
			$this->injectPropertyDescription($objectPropertyType, $phpdoc);
			return $objectPropertyType;
		}
		else
		{
			$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_UNKNOWN);
			return $objectPropertyType;
		}
	}

	/**
	 * @param array|string[] $typesSpecification
	 * @return bool
	 */
	private function typeIsArray($typesSpecification)
	{
		foreach ($typesSpecification as $typeSpecification)
		{
			// @var array|SomeType[]
			if ($typeSpecification == 'array')
			{
				return true;
			}

			// @var SomeType[] - without array| keyword
			if (substr($typeSpecification, -2, 2) === '[]')
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array|string[] $typesSpecification
	 *
	 * @return \NorthslopePL\Metassione\ObjectPropertyType
	 *
	 * @throws PHPDocParserException
	 */
	private function buildObjectPropertyTypeForArray($typesSpecification)
	{
		// @param array|Foobar[] $propertyName
		// => ['array', 'Foobar[]']
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
				$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_SIMPLE_TYPES, $type);
				return $objectPropertyType;
			}
			else
			{
				$objectPropertyType = new ObjectPropertyType(ObjectPropertyType::GENERAL_TYPE_ARRAY_OF_OBJECTS, $type);
				return $objectPropertyType;
			}
		}

		throw new PHPDocParserException('No type specified for property');
	}

	/**
	 * @param ObjectPropertyType $objectPropertyType
	 * @param string $phpdoc
	 */
	private function injectPropertyDescription(ObjectPropertyType $objectPropertyType, $phpdoc)
	{
		$descriptionLines = [];
		foreach (explode("\n", $phpdoc) as $line)
		{
			// ' * .........'
			if (preg_match('#^\\s*\\*\\s*(.+?)$#', $line, $matches))
			{
				$line = $matches[1];
				$shouldSkipPhpdocDirectives = ($line{0} == '@' || $line{0} == '/'); // '/' - end of phpdoc
				if ($shouldSkipPhpdocDirectives)
				{
					continue;
				}

				$descriptionLines[] = $line;
			}
		}

		$description = join("\n", $descriptionLines);
		$objectPropertyType->setDescription($description);
	}
}
