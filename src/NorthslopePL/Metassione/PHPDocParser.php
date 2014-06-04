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
	 *
	 * @param string $phpdoc
	 *
	 * @return array|string[2]
	 * 0 - type category, see self::TYPE_* constants
	 * 1 - null or classname
	 */
	public function getPropertyTypeFromPHPDoc($phpdoc)
	{
		$phpdoc = trim($phpdoc);
		if (empty($phpdoc))
		{
			return array(self::TYPE_UNKNOWN, null);
		}

		$pattern = '#@var\\s*(.+?)\\s*\\n#m';
		if (preg_match($pattern, $phpdoc, $m))
		{
			$typesSpecification = explode('|', $m[1]);
			$classname = $this->findClassnameInTypesArrayForArray($typesSpecification);

			if ($classname)
			{
				if ($this->typeIsArray($typesSpecification))
				{
					return array(self::TYPE_ARRAY, $classname);
				}
				else
				{
					return array(self::TYPE_OBJECT, $classname);
				}
			}
			else
			{
				if ($this->typeIsArray($typesSpecification))
				{
					return array(self::TYPE_ARRAY, null);
				}
				else
				{
					return array(self::TYPE_OTHER, null);
				}
			}
		}
		else
		{
			return array(self::TYPE_UNKNOWN, null);
		}
	}

	/**
	 * @param array|string[] $typesSpecification
	 * @return bool
	 */
	private function typeIsArray($typesSpecification)
	{
		return in_array('array', $typesSpecification);
	}

	/**
	 * @param array|string[] $typesSpecification
	 *
	 * @return string|null
	 *
	 * If string - then it is class name
	 * If null - then it is a basic type
	 *
	 * @throws PHPDocParserException
	 */
	private function findClassnameInTypesArrayForArray($typesSpecification)
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
				return null;
			}

			return $type;
		}

		throw new PHPDocParserException('No type specified for property');
	}
}
